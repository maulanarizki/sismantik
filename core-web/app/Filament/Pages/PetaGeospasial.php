<?php

namespace App\Filament\Pages;

use App\Models\GeoExtraction;
use App\Models\RawContent;
use App\Models\SentimentResult;
use Carbon\Carbon;
use Filament\Pages\Page;
use Illuminate\Support\Facades\DB;

class PetaGeospasial extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'Peta Geospasial 21 Kecamatan';

    protected static ?string $title = 'Pusat Komando Geospasial & Pemetaan Sentimen 21 Kecamatan';

    protected static ?int $navigationSort = 2;

    protected static string $view = 'filament.pages.peta-geospasial';

    public ?string $startDate = null;
    public ?string $endDate = null;
    public string $scope = 'all';
    public string $selectedDistrictName = '';
    public string $rankingSort = 'mentions'; // 'mentions', 'negative', 'positive'

    public function mount(): void
    {
        $this->startDate = now()->subYear()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function setPreset(int $months): void
    {
        $this->startDate = now()->subMonths($months)->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function getBreadcrumbs(): array
    {
        return [
            '/admin' => 'Dashboard',
            'Peta Geospasial 21 Kecamatan' => null,
        ];
    }

    public function getViewData(): array
    {
        $startDate = $this->startDate ?? now()->subYear()->format('Y-m-d');
        $endDate = $this->endDate ?? now()->format('Y-m-d');
        $scope = $this->scope ?? 'all';

        $query = GeoExtraction::query()
            ->join('raw_contents', 'geo_extractions.raw_content_id', '=', 'raw_contents.id')
            ->leftJoin('sentiment_results', 'raw_contents.id', '=', 'sentiment_results.raw_content_id')
            ->whereBetween('raw_contents.published_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);

        if ($scope && $scope !== 'all') {
            $query->where('raw_contents.scope', $scope);
        }

        $rawDistricts = $query->selectRaw('
                geo_extractions.district_name,
                COUNT(raw_contents.id) as mentions,
                SUM(CASE WHEN sentiment_results.sentiment_label = "positive" THEN 1 ELSE 0 END) as pos,
                SUM(CASE WHEN sentiment_results.sentiment_label = "negative" THEN 1 ELSE 0 END) as neg,
                SUM(CASE WHEN sentiment_results.sentiment_label = "neutral" THEN 1 ELSE 0 END) as neu
            ')
            ->groupBy('geo_extractions.district_name')
            ->get();

        $canonicalDistricts = [
            'Bandar Kedungmulyo', 'Bareng', 'Diwek', 'Gudo', 'Jogoroto', 
            'Jombang', 'Kabuh', 'Kesamben', 'Kudu', 'Megaluh', 
            'Mojoagung', 'Mojowarno', 'Ngoro', 'Ngusikan', 'Perak', 
            'Peterongan', 'Plandaan', 'Ploso', 'Sumobito', 'Tembelang', 'Wonosalam'
        ];

        $districtStats = [];
        $totalTerritorialMentions = 0;
        $rawanCount = 0;
        $kondusifCount = 0;

        foreach ($canonicalDistricts as $name) {
            $alias = str_replace('Bandar Kedungmulyo', 'Bandarkedung', $name);
            $matched = $rawDistricts->filter(function ($item) use ($name, $alias) {
                return stripos($item->district_name, $name) !== false || stripos($item->district_name, $alias) !== false;
            });

            $total = (int) $matched->sum('mentions');
            $pos = (int) $matched->sum('pos');
            $neg = (int) $matched->sum('neg');
            $neu = (int) $matched->sum('neu');
            $nss = $total > 0 ? round((($pos - $neg) / $total) * 100, 1) : 0.0;
            $totalTerritorialMentions += $total;

            $status = 'Kondusif';
            $color = '#06b6d4'; // Cyan default
            $badgeClass = 'bg-cyan-500/15 text-cyan-400 border-cyan-500/30';

            if ($total >= 3 && ($neg / max(1, $total)) >= 0.40) {
                $status = 'Rawan Keluhan';
                $color = '#f43f5e'; // Rose crimson
                $badgeClass = 'bg-rose-500/15 text-rose-400 border-rose-500/30';
                $rawanCount++;
            } elseif ($nss >= 15) {
                $status = 'Sangat Kondusif';
                $color = '#10b981'; // Emerald
                $badgeClass = 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30';
                $kondusifCount++;
            } elseif ($nss < 0) {
                $status = 'Perlu Perhatian';
                $color = '#f59e0b'; // Amber
                $badgeClass = 'bg-amber-500/15 text-amber-400 border-amber-500/30';
                $rawanCount++;
            } else {
                $kondusifCount++;
            }

            $districtStats[$name] = [
                'name' => $name,
                'total' => $total,
                'pos' => $pos,
                'neg' => $neg,
                'neu' => $neu,
                'nss' => $nss,
                'status' => $status,
                'color' => $color,
                'badgeClass' => $badgeClass,
            ];
        }

        // Leaderboards
        $leaderboardByMentions = array_values($districtStats);
        usort($leaderboardByMentions, fn ($a, $b) => $b['total'] <=> $a['total']);

        $leaderboardByNegative = array_values($districtStats);
        usort($leaderboardByNegative, function ($a, $b) {
            if ($b['neg'] === $a['neg']) {
                return $a['nss'] <=> $b['nss'];
            }
            return $b['neg'] <=> $a['neg'];
        });

        $leaderboardByPositive = array_values($districtStats);
        usort($leaderboardByPositive, function ($a, $b) {
            if ($b['pos'] === $a['pos']) {
                return $b['nss'] <=> $a['nss'];
            }
            return $b['pos'] <=> $a['pos'];
        });

        // Top Districts for Summary Cards
        $topVolumeDistrict = $leaderboardByMentions[0] ?? null;
        $topPositiveDistrict = $leaderboardByPositive[0] ?? null;
        $topCriticalDistrict = $leaderboardByNegative[0] ?? null;

        // Load GeoJSON safely
        $geoJsonPath = public_path('geojson/jombang_kecamatan.geojson');
        $geoJsonData = file_exists($geoJsonPath) ? json_decode(file_get_contents($geoJsonPath), true) : null;

        return [
            'districtStats' => $districtStats,
            'leaderboardByMentions' => $leaderboardByMentions,
            'leaderboardByNegative' => $leaderboardByNegative,
            'leaderboardByPositive' => $leaderboardByPositive,
            'totalTerritorialMentions' => $totalTerritorialMentions,
            'rawanCount' => $rawanCount,
            'kondusifCount' => $kondusifCount,
            'topVolumeDistrict' => $topVolumeDistrict,
            'topPositiveDistrict' => $topPositiveDistrict,
            'topCriticalDistrict' => $topCriticalDistrict,
            'geoJsonData' => $geoJsonData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'scope' => $scope,
        ];
    }
}

