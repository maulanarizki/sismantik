<?php

namespace App\Filament\Widgets;

use App\Models\AspectSentiment;
use App\Models\TopicOrAgency;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class OpdScorecardChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Matriks Komparasi Citra & Sentimen per OPD / Instansi';

    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? now()->subYear()->startOfDay();
        $endDate = $this->filters['endDate'] ?? now()->endOfDay();
        $scope = $this->filters['scope'] ?? 'all';

        $query = TopicOrAgency::where('is_active', true);
        if ($scope && $scope !== 'all') {
            $query->where('scope', $scope);
        }

        $agencies = $query->get();

        $labels = [];
        $posData = [];
        $negData = [];

        foreach ($agencies as $agency) {
            $shortName = $agency->aliases[0] ?? $agency->name;
            $labels[] = $shortName;

            $baseAspectQuery = AspectSentiment::where('agency_id', $agency->id)
                ->whereHas('sentimentResult.rawContent', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('published_at', [
                        Carbon::parse($startDate)->startOfDay(),
                        Carbon::parse($endDate)->endOfDay(),
                    ]);
                });

            $pos = (clone $baseAspectQuery)->where('aspect_sentiment_label', 'positive')->count();
            $neg = (clone $baseAspectQuery)->where('aspect_sentiment_label', 'negative')->count();

            $posData[] = $pos;
            $negData[] = $neg;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sentimen Positif (Pujian/Apresiasi)',
                    'data' => $posData,
                    'backgroundColor' => '#10B981',
                    'borderRadius' => 6,
                ],
                [
                    'label' => 'Sentimen Negatif (Keluhan Masuk)',
                    'data' => $negData,
                    'backgroundColor' => '#F43F5E',
                    'borderRadius' => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 12,
                        'padding' => 15,
                        'font' => [
                            'size' => 11,
                            'weight' => '600',
                        ],
                    ],
                ],
            ],
            'scales' => [
                'x' => [
                    'grid' => [
                        'color' => 'rgba(148, 163, 184, 0.08)',
                    ],
                ],
                'y' => [
                    'grid' => [
                        'display' => false,
                    ],
                    'ticks' => [
                        'font' => [
                            'size' => 11,
                            'weight' => '600',
                        ],
                    ],
                ],
            ],
        ];
    }
}
