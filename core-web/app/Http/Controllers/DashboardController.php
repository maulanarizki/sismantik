<?php

namespace App\Http\Controllers;

use App\Models\GeoExtraction;
use App\Models\RawContent;
use App\Models\SentimentResult;
use App\Models\TopicOrAgency;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->query('startDate', now()->subYear()->format('Y-m-d'));
        $endDate = $request->query('endDate', now()->format('Y-m-d'));
        $scope = $request->query('scope', 'all');

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        // Base Query
        $baseQuery = RawContent::whereBetween('published_at', [$start, $end]);
        if ($scope !== 'all') {
            $baseQuery->where('scope', $scope);
        }

        $totalContent = (clone $baseQuery)->count();
        $contentIds = (clone $baseQuery)->pluck('id');

        // Sentiments
        $sentiments = SentimentResult::whereIn('raw_content_id', $contentIds)->get();
        $posCount = $sentiments->where('sentiment_label', 'positive')->count();
        $negCount = $sentiments->where('sentiment_label', 'negative')->count();
        $neuCount = $sentiments->where('sentiment_label', 'neutral')->count();

        $nss = $totalContent > 0 ? round((($posCount - $negCount) / $totalContent) * 100, 1) : 0.0;
        $trustIndex = $totalContent > 0 ? round(5.0 + ($nss / 20.0), 1) : 5.0;
        $trustIndex = max(1.0, min(10.0, $trustIndex));

        $botCount = (clone $baseQuery)->where('is_suspected_bot', true)->count();

        // 1. Program Strategis Nasional (PSN)
        $psnConfigs = [
            [
                'id' => 'mbg',
                'name' => 'Program Makan Bergizi Gratis (MBG)',
                'badge' => 'Badan Gizi Nasional (BGN)',
                'keywords' => ['mbg', 'makan bergizi gratis', 'sppg', 'dapur umum', 'menu bergizi', 'sarapan bergizi'],
                'focal_point' => 'Kesiapan Dapur SPPG Kodim/Pemkab, uji coba menu bergizi di SD/Madrasah, dan pemenuhan higienitas pangan di Jombang.',
            ],
            [
                'id' => 'kopdes',
                'name' => 'Koperasi Desa (Kopdes) & Ketahanan Pangan',
                'badge' => 'Kemenkop & Bulog',
                'keywords' => ['koperasi desa', 'kopdes', 'koperasi merah putih', 'lumbung pangan', 'bulog', 'gabah', 'kud'],
                'focal_point' => 'Revitalisasi KUD/Kopdes Merah Putih, penyerapan gabah dan beras petani lokal Jombang oleh Bulog, dan penguatan ekonomi desa.',
            ],
            [
                'id' => 'tipikor',
                'name' => 'Pemberantasan Korupsi & Penegakan Hukum Daerah',
                'badge' => 'KPK & Kejari Jombang',
                'keywords' => ['korupsi', 'kejari jombang', 'kpk', 'tipikor', 'dana desa', 'pungli', 'ott', 'saber pungli'],
                'focal_point' => 'Penyidikan kasus dugaan korupsi proyek fisik, pengawasan dana desa, keterbukaan pengadaan lelang, dan operasi saber pungli.',
            ],
            [
                'id' => 'swasembada',
                'name' => 'Swasembada Pangan & Alsintan Nasional',
                'badge' => 'Kementan & BBWS Brantas',
                'keywords' => ['swasembada pangan', 'pupuk kementan', 'irigasi bbws', 'cetak sawah', 'alsintan', 'kedaulatan pangan'],
                'focal_point' => 'Penyaluran kuota pupuk subsidi pusat, rehabilitasi saluran irigasi sekunder Brantas di Jombang, dan mekanisasi traktor alsintan.',
            ],
        ];

        $psnPrograms = [];
        foreach ($psnConfigs as $p) {
            $kw = $p['keywords'];
            $matched = RawContent::whereBetween('published_at', [$start, $end])
                ->where(function ($q) use ($kw) {
                    foreach ($kw as $k) {
                        $q->orWhere('clean_text', 'LIKE', "%{$k}%")->orWhere('title', 'LIKE', "%{$k}%");
                    }
                })->get();

            $pCount = $matched->count();
            $pPos = 0; $pNeg = 0; $pNeu = 0;
            if ($pCount > 0) {
                $pIds = $matched->pluck('id');
                $pSents = SentimentResult::whereIn('raw_content_id', $pIds)->get();
                $pPos = $pSents->where('sentiment_label', 'positive')->count();
                $pNeg = $pSents->where('sentiment_label', 'negative')->count();
                $pNeu = $pSents->where('sentiment_label', 'neutral')->count();
            }

            $pNss = $pCount > 0 ? round((($pPos - $pNeg) / $pCount) * 100, 1) : 0.0;
            $pStatus = $pNss >= 20 ? 'Sangat Positif' : ($pNss >= 0 ? 'Kondusif' : 'Perlu Atensi');

            $psnPrograms[] = [
                'id' => $p['id'],
                'name' => $p['name'],
                'badge' => $p['badge'],
                'focal_point' => $p['focal_point'],
                'primary_keyword' => $p['keywords'][0],
                'mentions' => $pCount,
                'pos' => $pPos,
                'neg' => $pNeg,
                'neu' => $pNeu,
                'nss' => $pNss,
                'status' => $pStatus,
            ];
        }

        // 2. 12-Month Sentiment Timeline Trend
        $timelineMonths = [];
        $timelineLabels = [];
        $timelinePos = [];
        $timelineNeg = [];
        $timelineNeu = [];
        $timelineTotal = [];

        $current = Carbon::parse($startDate)->startOfMonth();
        $targetEnd = Carbon::parse($endDate)->endOfMonth();

        while ($current->lte($targetEnd)) {
            $mStart = (clone $current)->startOfMonth();
            $mEnd = (clone $current)->endOfMonth();
            $label = $mStart->translatedFormat('M Y');

            $mIds = RawContent::whereBetween('published_at', [$mStart, $mEnd])
                ->when($scope !== 'all', fn($q) => $q->where('scope', $scope))
                ->pluck('id');

            $mTotal = $mIds->count();
            $mPos = 0; $mNeg = 0; $mNeu = 0;

            if ($mTotal > 0) {
                $mSents = SentimentResult::whereIn('raw_content_id', $mIds)->get();
                $mPos = $mSents->where('sentiment_label', 'positive')->count();
                $mNeg = $mSents->where('sentiment_label', 'negative')->count();
                $mNeu = $mSents->where('sentiment_label', 'neutral')->count();
            }

            $timelineLabels[] = $label;
            $timelinePos[] = $mPos;
            $timelineNeg[] = $mNeg;
            $timelineNeu[] = $mNeu;
            $timelineTotal[] = $mTotal;

            $timelineMonths[] = [
                'label' => $label,
                'total' => $mTotal,
                'pos' => $mPos,
                'neg' => $mNeg,
                'neu' => $mNeu,
            ];

            $current->addMonth();
        }

        // 3. Rapor 10 OPD Pemkab Jombang
        $opdList = TopicOrAgency::where('is_active', true)->get();
        $opdScorecards = [];

        foreach ($opdList as $opd) {
            $kws = is_array($opd->keywords) ? $opd->keywords : [];
            $opdContent = RawContent::whereBetween('published_at', [$start, $end])
                ->where(function ($q) use ($opd, $kws) {
                    $q->where('clean_text', 'LIKE', "%{$opd->name}%")
                      ->orWhere('title', 'LIKE', "%{$opd->name}%");
                    foreach ($kws as $k) {
                        $q->orWhere('clean_text', 'LIKE', "%{$k}%");
                    }
                })->get();

            $oCount = $opdContent->count();
            $oPos = 0; $oNeg = 0; $oNeu = 0;

            if ($oCount > 0) {
                $oIds = $opdContent->pluck('id');
                $oSents = SentimentResult::whereIn('raw_content_id', $oIds)->get();
                $oPos = $oSents->where('sentiment_label', 'positive')->count();
                $oNeg = $oSents->where('sentiment_label', 'negative')->count();
                $oNeu = $oSents->where('sentiment_label', 'neutral')->count();
            }

            $oNss = $oCount > 0 ? round((($oPos - $oNeg) / $oCount) * 100, 1) : 0.0;

            $opdScorecards[] = [
                'id' => $opd->id,
                'name' => $opd->name,
                'category' => $opd->category ?? 'Pemerintahan',
                'primary_keyword' => count($kws) > 0 ? $kws[0] : $opd->name,
                'mentions' => $oCount,
                'pos' => $oPos,
                'neg' => $oNeg,
                'neu' => $oNeu,
                'nss' => $oNss,
            ];
        }

        usort($opdScorecards, fn($a, $b) => $b['mentions'] <=> $a['mentions']);

        // 4. Matriks Framing Isu Warga
        $framingCategories = [
            'Infrastruktur & Layanan Publik' => ['jalan rusak', 'jembatan', 'pju', 'aspal', 'saluran air', 'tpa'],
            'Kesehatan & Kesejahteraan' => ['rsud', 'puskesmas', 'bpjs', 'stunting', 'gizi', 'obat'],
            'Pertanian & Ketahanan Pangan' => ['pupuk subsidi', 'petani', 'irigasi', 'gabah', 'tengkulak', 'panen'],
            'Pendidikan & Budaya' => ['sekolah', 'ppdb', 'zonasi', 'gedung rusak', 'guru honorer'],
            'Tata Kelola & Penegakan Hukum' => ['korupsi', 'pungli', 'kejari', 'dana desa', 'lelang'],
        ];

        $framingMatrix = [];
        foreach ($framingCategories as $fCat => $fKws) {
            $fMatched = RawContent::whereBetween('published_at', [$start, $end])
                ->where(function ($q) use ($fKws) {
                    foreach ($fKws as $kw) {
                        $q->orWhere('clean_text', 'LIKE', "%{$kw}%")->orWhere('title', 'LIKE', "%{$kw}%");
                    }
                })->get();

            $fCount = $fMatched->count();
            $fPos = 0; $fNeg = 0; $fNeu = 0;

            if ($fCount > 0) {
                $fIds = $fMatched->pluck('id');
                $fSents = SentimentResult::whereIn('raw_content_id', $fIds)->get();
                $fPos = $fSents->where('sentiment_label', 'positive')->count();
                $fNeg = $fSents->where('sentiment_label', 'negative')->count();
                $fNeu = $fSents->where('sentiment_label', 'neutral')->count();
            }

            $fNss = $fCount > 0 ? round((($fPos - $fNeg) / $fCount) * 100, 1) : 0.0;

            $framingMatrix[] = [
                'category' => $fCat,
                'sample_issue' => implode(', ', array_slice($fKws, 0, 3)),
                'primary_keyword' => $fKws[0],
                'mentions' => $fCount,
                'pos' => $fPos,
                'neg' => $fNeg,
                'neu' => $fNeu,
                'nss' => $fNss,
            ];
        }

        // 5. Anatomi Emosi Publik
        $emotionDefinitions = [
            'apresiasi' => ['label' => 'Apresiasi / Senang', 'color' => '#10b981'],
            'harapan' => ['label' => 'Harapan / Doa', 'color' => '#f59e0b'],
            'kecewa' => ['label' => 'Kecewa / Frustrasi', 'color' => '#ea580c'],
            'marah' => ['label' => 'Marah / Geram', 'color' => '#ef4444'],
            'sedih' => ['label' => 'Sedih / Prihatin', 'color' => '#3b82f6'],
            'takut' => ['label' => 'Takut / Cemas', 'color' => '#8b5cf6'],
        ];

        $emotionData = [];
        $totalEmotions = 0;
        foreach ($emotionDefinitions as $eKey => $eMeta) {
            $eCount = SentimentResult::whereIn('raw_content_id', $contentIds)
                ->where('emotion_label', $eKey)
                ->count();
            $totalEmotions += $eCount;
            $emotionData[$eKey] = [
                'label' => $eMeta['label'],
                'count' => $eCount,
                'color' => $eMeta['color'],
            ];
        }

        foreach ($emotionData as $k => $v) {
            $emotionData[$k]['percentage'] = $totalEmotions > 0 ? round(($v['count'] / $totalEmotions) * 100, 1) : 0;
        }

        // 6. Bilik Geospasial 21 Kecamatan (Top 5 Preview)
        $geoQuery = GeoExtraction::query()
            ->join('raw_contents', 'geo_extractions.raw_content_id', '=', 'raw_contents.id')
            ->leftJoin('sentiment_results', 'raw_contents.id', '=', 'sentiment_results.raw_content_id')
            ->whereBetween('raw_contents.published_at', [$start, $end]);

        if ($scope !== 'all') {
            $geoQuery->where('raw_contents.scope', $scope);
        }

        $rawDistricts = $geoQuery->selectRaw('
                geo_extractions.district_name,
                COUNT(raw_contents.id) as mentions,
                SUM(CASE WHEN sentiment_results.sentiment_label = "positive" THEN 1 ELSE 0 END) as pos,
                SUM(CASE WHEN sentiment_results.sentiment_label = "negative" THEN 1 ELSE 0 END) as neg,
                SUM(CASE WHEN sentiment_results.sentiment_label = "neutral" THEN 1 ELSE 0 END) as neu
            ')
            ->groupBy('geo_extractions.district_name')
            ->orderByDesc('mentions')
            ->limit(5)
            ->get();

        $topDistrictsPreview = [];
        foreach ($rawDistricts as $rd) {
            $dTot = (int) $rd->mentions;
            $dPos = (int) $rd->pos;
            $dNeg = (int) $rd->neg;
            $dNss = $dTot > 0 ? round((($dPos - $dNeg) / $dTot) * 100, 1) : 0.0;
            $topDistrictsPreview[] = [
                'name' => $rd->district_name,
                'total' => $dTot,
                'pos' => $dPos,
                'neg' => $dNeg,
                'nss' => $dNss,
                'status' => $dNss >= 15 ? 'Sangat Kondusif' : ($dNss >= 0 ? 'Kondusif' : 'Perlu Atensi'),
            ];
        }

        return view('pages.dashboard', compact(
            'startDate',
            'endDate',
            'scope',
            'totalContent',
            'posCount',
            'negCount',
            'neuCount',
            'nss',
            'trustIndex',
            'botCount',
            'psnPrograms',
            'timelineMonths',
            'timelineLabels',
            'timelinePos',
            'timelineNeg',
            'timelineNeu',
            'timelineTotal',
            'opdScorecards',
            'framingMatrix',
            'emotionData',
            'totalEmotions',
            'topDistrictsPreview'
        ));
    }
}

