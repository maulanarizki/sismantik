<?php

namespace App\Filament\Widgets;

use App\Models\RawContent;
use App\Models\SentimentResult;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ExecutiveStatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $startDate = $this->filters['startDate'] ?? now()->subYear()->startOfDay();
        $endDate = $this->filters['endDate'] ?? now()->endOfDay();
        $scope = $this->filters['scope'] ?? 'all';

        $baseQuery = RawContent::query()
            ->whereBetween('published_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);

        if ($scope && $scope !== 'all') {
            $baseQuery->where('scope', $scope);
        }

        $totalContents = (clone $baseQuery)->count();
        $totalAnalyzed = (clone $baseQuery)->where('is_analyzed', true)->count();

        $contentIds = (clone $baseQuery)->where('is_analyzed', true)->pluck('id');

        $posCount = SentimentResult::whereIn('raw_content_id', $contentIds)->where('sentiment_label', 'positive')->count();
        $negCount = SentimentResult::whereIn('raw_content_id', $contentIds)->where('sentiment_label', 'negative')->count();
        $neuCount = SentimentResult::whereIn('raw_content_id', $contentIds)->where('sentiment_label', 'neutral')->count();

        // Hitung Net Sentiment Score: (%Positif - %Negatif)
        $nss = 0;
        if ($totalAnalyzed > 0) {
            $nss = round((($posCount - $negCount) / $totalAnalyzed) * 100, 1);
        }

        $nssColor = $nss >= 0 ? 'success' : 'danger';
        $nssIcon = $nss >= 0 ? 'heroicon-m-arrow-trending-up' : 'heroicon-m-arrow-trending-down';

        // Hitung Indeks Kepuasan Publik (Skala 1 - 10)
        $satisfactionIndex = round(5.0 + ($nss / 20.0), 1);
        $satisfactionIndex = max(1.0, min(10.0, $satisfactionIndex));

        $botCount = (clone $baseQuery)->where('is_suspected_bot', true)->count();

        // Metodologi Statistika Lanjutan: Bayesian Smoothing & Wilson Confidence Interval
        $statService = app(\App\Services\StatisticalService::class);
        $bayes = $statService->calculateBayesianSentiment($posCount, $neuCount, $negCount);
        $wilson = $statService->calculateWilsonInterval($posCount, max(1, $totalAnalyzed));

        $nssBayes = $bayes['nss_bayes'];

        $scopeLabel = match($scope) {
            'national_strategic' => 'PSN Pusat',
            'local_pemkab' => 'Lokal Pemkab',
            default => 'Lokal & PSN',
        };

        return [
            Stat::make('Total Berita Terpublikasi', number_format($totalContents) . ' Konten')
                ->description("Rentang Terbit Aktif • {$scopeLabel} ({$totalAnalyzed} dianalisis)")
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('primary')
                ->chart([12, 18, 25, 30, 42, 50, $totalContents])
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-[1.01] transition',
                    'wire:click' => "\$dispatch('open-article-drilldown', { title: 'Semua Berita pada Periode Aktif', subtitle: 'Rentang Publikasi: " . Carbon::parse($startDate)->format('d M Y') . " s/d " . Carbon::parse($endDate)->format('d M Y') . "', sentiment: 'all' })",
                ]),

            Stat::make('Net Sentiment Score (NSS)', ($nssBayes >= 0 ? '+' : '') . $nssBayes . '%')
                ->description("Bayesian Smoothed: {$nssBayes}% (Raw: " . ($nss >= 0 ? '+' : '') . "{$nss}%) • Klik untuk detail")
                ->descriptionIcon($nssIcon)
                ->color($nssColor)
                ->chart([$nssBayes - 4, $nssBayes - 1, $nssBayes + 2, $nssBayes])
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-[1.01] transition',
                    'wire:click' => "\$dispatch('open-article-drilldown', { title: 'Berita Berdasarkan Sentimen', subtitle: 'Positif: {$posCount} | Negatif: {$negCount} | Netral: {$neuCount}', sentiment: 'negative' })",
                ]),

            Stat::make('Indeks Kepuasan Warga', $satisfactionIndex . ' / 10.0')
                ->description("CI 95%: {$wilson['lower_bound']}% – {$wilson['upper_bound']}% (MoE ±{$wilson['margin_of_error']}%)")
                ->descriptionIcon('heroicon-m-chart-bar-square')
                ->color('warning')
                ->extraAttributes([
                    'class' => 'cursor-pointer hover:scale-[1.01] transition',
                    'wire:click' => "\$dispatch('open-article-drilldown', { title: 'Berita Sentimen Positif (Apresiasi)', subtitle: 'Total: {$posCount} Berita Positif', sentiment: 'positive' })",
                ]),

            Stat::make('Audit Anomali & Bot Buzzer', $botCount . ' Terindikasi')
                ->description($botCount > 0 ? 'Ditemukan duplikasi narasi terkoordinasi' : 'Opini masyarakat terpantau organik')
                ->descriptionIcon($botCount > 0 ? 'heroicon-m-shield-exclamation' : 'heroicon-m-shield-check')
                ->color($botCount > 0 ? 'danger' : 'success'),
        ];
    }
}
