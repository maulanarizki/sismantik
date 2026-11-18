<?php

namespace App\Filament\Widgets;

use App\Models\GeoExtraction;
use App\Models\RawContent;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class GeospatialGatewayWidget extends Widget
{
    use InteractsWithPageFilters;

    protected static string $view = 'filament.widgets.geospatial-gateway-widget';

    protected static ?int $sort = 7;

    protected int | string | array $columnSpan = 1;

    public function getViewData(): array
    {
        $startDate = $this->filters['startDate'] ?? now()->subYear()->startOfDay();
        $endDate = $this->filters['endDate'] ?? now()->endOfDay();
        $scope = $this->filters['scope'] ?? 'all';

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
            $badgeClass = 'bg-cyan-500/15 text-cyan-500 border-cyan-500/30';

            if ($total >= 3 && ($neg / max(1, $total)) >= 0.40) {
                $status = 'Rawan Keluhan';
                $badgeClass = 'bg-rose-500/15 text-rose-500 border-rose-500/30';
                $rawanCount++;
            } elseif ($nss >= 15) {
                $status = 'Sangat Kondusif';
                $badgeClass = 'bg-emerald-500/15 text-emerald-500 border-emerald-500/30';
                $kondusifCount++;
            } elseif ($nss < 0) {
                $status = 'Perlu Atensi';
                $badgeClass = 'bg-amber-500/15 text-amber-500 border-amber-500/30';
                $rawanCount++;
            } else {
                $kondusifCount++;
            }

            $districtStats[] = [
                'name' => $name,
                'total' => $total,
                'pos' => $pos,
                'neg' => $neg,
                'neu' => $neu,
                'nss' => $nss,
                'status' => $status,
                'badgeClass' => $badgeClass,
            ];
        }

        usort($districtStats, fn ($a, $b) => $b['total'] <=> $a['total']);
        $topDistricts = array_slice($districtStats, 0, 4);

        return [
            'topDistricts' => $topDistricts,
            'totalTerritorialMentions' => $totalTerritorialMentions,
            'rawanCount' => $rawanCount,
            'kondusifCount' => $kondusifCount,
            'startDate' => Carbon::parse($startDate)->format('Y-m-d'),
            'endDate' => Carbon::parse($endDate)->format('Y-m-d'),
        ];
    }
}

