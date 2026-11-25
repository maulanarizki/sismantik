<?php

namespace App\Http\Controllers;

use App\Models\GeoExtraction;
use App\Models\RawContent;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GeospatialController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('startDate', now()->subYear()->format('Y-m-d'));
        $endDate = $request->query('endDate', now()->format('Y-m-d'));
        $scope = $request->query('scope', 'all');

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
        $kondusifCount = 0;
        $atensiCount = 0;
        $rawanCount = 0;

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
            $posPct = $total > 0 ? round(($pos / $total) * 100, 1) : 0.0;
            $totalTerritorialMentions += $total;

            // Klasifikasi Sentimen Teritori yang Realistis & Terkalibrasi
            if ($nss >= 10.0 || ($total >= 5 && $posPct >= 30.0)) {
                $status = 'Sangat Kondusif';
                $color = '#10b981'; // Emerald
                $kondusifCount++;
            } elseif ($nss >= -30.0 || ($total >= 5 && $posPct >= 20.0)) {
                $status = 'Kondusif';
                $color = '#06b6d4'; // Cyan
                $kondusifCount++;
            } elseif ($nss >= -60.0) {
                $status = 'Perlu Atensi';
                $color = '#f59e0b'; // Amber
                $atensiCount++;
            } else {
                $status = 'Rawan Keluhan';
                $color = '#f43f5e'; // Rose crimson
                $rawanCount++;
            }

            $districtStats[$name] = [
                'name' => $name,
                'total' => $total,
                'pos' => $pos,
                'neg' => $neg,
                'neu' => $neu,
                'nss' => $nss,
                'posPct' => $posPct,
                'status' => $status,
                'color' => $color,
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

        $topVolumeDistrict = $leaderboardByMentions[0] ?? null;
        $topPositiveDistrict = $leaderboardByPositive[0] ?? null;
        $topCriticalDistrict = $leaderboardByNegative[0] ?? null;

        // GeoJSON
        $geoJsonPath = public_path('geojson/jombang_kecamatan.geojson');
        $geoJsonData = file_exists($geoJsonPath) ? json_decode(file_get_contents($geoJsonPath), true) : null;

        return view('pages.peta', compact(
            'startDate',
            'endDate',
            'scope',
            'districtStats',
            'canonicalDistricts',
            'leaderboardByMentions',
            'leaderboardByNegative',
            'leaderboardByPositive',
            'totalTerritorialMentions',
            'kondusifCount',
            'atensiCount',
            'rawanCount',
            'topVolumeDistrict',
            'topPositiveDistrict',
            'topCriticalDistrict',
            'geoJsonData'
        ));
    }
}

