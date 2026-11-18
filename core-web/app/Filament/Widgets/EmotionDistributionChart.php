<?php

namespace App\Filament\Widgets;

use App\Models\RawContent;
use App\Models\SentimentResult;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class EmotionDistributionChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Anatomi Emosi & Psikologi Warga Jombang';

    protected static ?int $sort = 6;

    protected int | string | array $columnSpan = 1;

    protected static ?string $maxHeight = '320px';

    protected function getData(): array
    {
        $startDate = $this->filters['startDate'] ?? now()->subYear()->startOfDay();
        $endDate = $this->filters['endDate'] ?? now()->endOfDay();
        $scope = $this->filters['scope'] ?? 'all';

        $contentIds = RawContent::whereBetween('published_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ])
            ->when($scope && $scope !== 'all', fn($q) => $q->where('scope', $scope))
            ->pluck('id');

        $emotions = [
            'apresiasi' => 'Apresiasi / Senang',
            'harapan' => 'Harapan / Doa',
            'kecewa' => 'Kecewa / Frustrasi',
            'marah' => 'Marah / Geram',
            'sedih' => 'Sedih / Prihatin',
            'takut' => 'Takut / Cemas',
        ];

        $labels = [];
        $data = [];
        $colors = [
            '#059669', // Apresiasi (Mint green)
            '#F59E0B', // Harapan (Gold)
            '#EA580C', // Kecewa (Orange)
            '#DC2626', // Marah (Red)
            '#3B82F6', // Sedih (Blue)
            '#7C3AED', // Takut (Purple)
        ];

        foreach ($emotions as $key => $label) {
            $count = SentimentResult::whereIn('raw_content_id', $contentIds)
                ->where('emotion_label', $key)
                ->count();
            $labels[] = $label;
            $data[] = max(1, $count);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Distribusi Emosi Warga',
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderWidth' => 0,
                    'hoverOffset' => 6,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'boxWidth' => 12,
                        'padding' => 12,
                        'font' => [
                            'size' => 11,
                            'weight' => '700',
                        ],
                    ],
                ],
            ],
            'cutout' => '65%',
        ];
    }
}
