<?php

namespace App\Filament\Widgets;

use App\Models\RawContent;
use App\Models\SentimentResult;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class SentimentTrendChart extends ChartWidget
{
    use InteractsWithPageFilters;

    protected static ?string $heading = 'Dinamika Tren Sentimen Publik Berdasarkan Tanggal Terbit Berita';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $startDate = Carbon::parse($this->filters['startDate'] ?? now()->subYear()->startOfDay());
        $endDate = Carbon::parse($this->filters['endDate'] ?? now()->endOfDay());
        $scope = $this->filters['scope'] ?? 'all';

        $diffDays = $startDate->diffInDays($endDate);

        $labels = [];
        $posData = [];
        $negData = [];
        $neuData = [];

        // Jika rentang > 90 hari: Kelompokkan per bulan (12 bulan)
        if ($diffDays > 90) {
            $current = $startDate->copy()->startOfMonth();
            while ($current <= $endDate) {
                $monthStart = $current->copy()->startOfMonth();
                $monthEnd = $current->copy()->endOfMonth();
                $labels[] = $current->translatedFormat('M Y');

                $ids = RawContent::whereBetween('published_at', [$monthStart, $monthEnd])
                    ->when($scope && $scope !== 'all', fn($q) => $q->where('scope', $scope))
                    ->pluck('id');

                $posData[] = SentimentResult::whereIn('raw_content_id', $ids)->where('sentiment_label', 'positive')->count();
                $negData[] = SentimentResult::whereIn('raw_content_id', $ids)->where('sentiment_label', 'negative')->count();
                $neuData[] = SentimentResult::whereIn('raw_content_id', $ids)->where('sentiment_label', 'neutral')->count();

                $current->addMonth();
            }
        } elseif ($diffDays > 14) {
            // Rentang 15 s.d. 90 hari: Kelompokkan per minggu
            $current = $startDate->copy()->startOfWeek();
            $weekNum = 1;
            while ($current <= $endDate) {
                $weekStart = $current->copy()->startOfWeek();
                $weekEnd = $current->copy()->endOfWeek();
                $labels[] = 'Mgg ' . $weekNum . ' (' . $weekStart->format('d M') . ')';

                $ids = RawContent::whereBetween('published_at', [$weekStart, $weekEnd])
                    ->when($scope && $scope !== 'all', fn($q) => $q->where('scope', $scope))
                    ->pluck('id');

                $posData[] = SentimentResult::whereIn('raw_content_id', $ids)->where('sentiment_label', 'positive')->count();
                $negData[] = SentimentResult::whereIn('raw_content_id', $ids)->where('sentiment_label', 'negative')->count();
                $neuData[] = SentimentResult::whereIn('raw_content_id', $ids)->where('sentiment_label', 'neutral')->count();

                $current->addWeek();
                $weekNum++;
            }
        } else {
            // Rentang <= 14 hari: Kelompokkan per hari
            $current = $startDate->copy();
            while ($current <= $endDate) {
                $dayStart = $current->copy()->startOfDay();
                $dayEnd = $current->copy()->endOfDay();
                $labels[] = $current->format('d M');

                $ids = RawContent::whereBetween('published_at', [$dayStart, $dayEnd])
                    ->when($scope && $scope !== 'all', fn($q) => $q->where('scope', $scope))
                    ->pluck('id');

                $posData[] = SentimentResult::whereIn('raw_content_id', $ids)->where('sentiment_label', 'positive')->count();
                $negData[] = SentimentResult::whereIn('raw_content_id', $ids)->where('sentiment_label', 'negative')->count();
                $neuData[] = SentimentResult::whereIn('raw_content_id', $ids)->where('sentiment_label', 'neutral')->count();

                $current->addDay();
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Sentimen Positif (Apresiasi/Dukungan)',
                    'data' => $posData,
                    'borderColor' => '#10B981', // Emerald-500
                    'backgroundColor' => 'rgba(16, 185, 129, 0.15)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
                [
                    'label' => 'Sentimen Netral (Pemberitaan/Faktual)',
                    'data' => $neuData,
                    'borderColor' => '#64748B', // Slate-500
                    'backgroundColor' => 'rgba(100, 116, 139, 0.10)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
                [
                    'label' => 'Sentimen Negatif (Keluhan/Kritik/Bencana)',
                    'data' => $negData,
                    'borderColor' => '#EF4444', // Red-500
                    'backgroundColor' => 'rgba(239, 68, 68, 0.15)',
                    'fill' => true,
                    'tension' => 0.35,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'grid' => [
                        'color' => 'rgba(148, 163, 184, 0.1)',
                    ],
                ],
                'x' => [
                    'grid' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}
