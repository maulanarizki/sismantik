<?php

namespace App\Filament\Widgets;

use App\Models\RawContent;
use App\Models\SentimentResult;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class NationalStrategicWidget extends Widget
{
    use InteractsWithPageFilters;

    protected static string $view = 'filament.widgets.national-strategic-widget';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $startDate = $this->filters['startDate'] ?? now()->subYear()->startOfDay();
        $endDate = $this->filters['endDate'] ?? now()->endOfDay();
        $scope = $this->filters['scope'] ?? 'all';

        $programs = [
            [
                'id' => 'mbg',
                'name' => 'Program Makan Bergizi Gratis (MBG)',
                'badge' => 'Badan Gizi Nasional (BGN)',
                'color' => 'emerald',
                'icon' => 'heroicon-o-cake',
                'keywords' => ['mbg', 'makan bergizi gratis', 'sppg', 'dapur umum', 'menu bergizi', 'sarapan bergizi'],
                'focal_point' => 'Kesiapan Dapur SPPG Kodim/Pemkab, uji coba menu bergizi di SD/Madrasah, dan pemenuhan higienitas pangan di Jombang.',
            ],
            [
                'id' => 'kopdes',
                'name' => 'Koperasi Desa (Kopdes) & Ketahanan Pangan',
                'badge' => 'Kemenkop & Bulog',
                'color' => 'sky',
                'icon' => 'heroicon-o-building-storefront',
                'keywords' => ['koperasi desa', 'kopdes', 'koperasi merah putih', 'lumbung pangan', 'bulog', 'gabah', 'kud'],
                'focal_point' => 'Revitalisasi KUD/Kopdes Merah Putih, penyerapan gabah dan beras petani lokal Jombang oleh Bulog, dan penguatan ekonomi desa.',
            ],
            [
                'id' => 'tipikor',
                'name' => 'Pemberantasan Korupsi & Penegakan Hukum Daerah',
                'badge' => 'KPK & Kejari Jombang',
                'color' => 'rose',
                'icon' => 'heroicon-o-scale',
                'keywords' => ['korupsi', 'kejari jombang', 'kpk', 'tipikor', 'dana desa', 'pungli', 'ott', 'saber pungli'],
                'focal_point' => 'Penyidikan kasus dugaan korupsi proyek fisik, pengawasan dana desa, keterbukaan pengadaan lelang, dan operasi saber pungli.',
            ],
            [
                'id' => 'swasembada',
                'name' => 'Swasembada Pangan & Alsintan Nasional',
                'badge' => 'Kementan & BBWS Brantas',
                'color' => 'amber',
                'icon' => 'heroicon-o-sparkles',
                'keywords' => ['swasembada pangan', 'pupuk kementan', 'irigasi bbws', 'cetak sawah', 'alsintan', 'kedaulatan pangan'],
                'focal_point' => 'Penyaluran kuota pupuk subsidi pusat, rehabilitasi saluran irigasi sekunder Brantas di Jombang, dan mekanisasi traktor alsintan.',
            ],
        ];

        $results = [];
        $basePsnQuery = RawContent::where('scope', 'national_strategic')
            ->whereBetween('published_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);

        $totalPsnCount = (clone $basePsnQuery)->count();

        foreach ($programs as $prog) {
            $kwList = $prog['keywords'];
            
            $matched = RawContent::query()
                ->whereBetween('published_at', [
                    Carbon::parse($startDate)->startOfDay(),
                    Carbon::parse($endDate)->endOfDay(),
                ])
                ->where(function ($q) use ($kwList) {
                    foreach ($kwList as $kw) {
                        $q->orWhere('clean_text', 'LIKE', "%{$kw}%")
                          ->orWhere('title', 'LIKE', "%{$kw}%");
                    }
                })->get();

            $count = $matched->count();
            $pos = 0;
            $neg = 0;
            $neu = 0;
            $emotions = [];

            if ($count > 0) {
                $ids = $matched->pluck('id');
                $sentiments = SentimentResult::whereIn('raw_content_id', $ids)->get();

                foreach ($sentiments as $s) {
                    if ($s->sentiment_label === 'positive') $pos++;
                    elseif ($s->sentiment_label === 'negative') $neg++;
                    else $neu++;

                    if ($s->emotion_label) {
                        $emotions[$s->emotion_label] = ($emotions[$s->emotion_label] ?? 0) + 1;
                    }
                }
            }

            arsort($emotions);
            $dominantEmotion = !empty($emotions) ? array_key_first($emotions) : 'netral';
            $nss = round((($pos - $neg) / max(1, $count)) * 100, 1);

            $results[] = [
                'id' => $prog['id'],
                'name' => $prog['name'],
                'badge' => $prog['badge'],
                'color' => $prog['color'],
                'icon' => $prog['icon'],
                'keywords' => $prog['keywords'],
                'primary_keyword' => $prog['keywords'][0] ?? $prog['id'],
                'focal_point' => $prog['focal_point'],
                'total_mentions' => $count,
                'pos_count' => $pos,
                'neg_count' => $neg,
                'neu_count' => $neu,
                'nss' => $nss,
                'dominant_emotion' => ucfirst($dominantEmotion),
                'status' => $nss >= 20 ? 'Sangat Positif' : ($nss >= 0 ? 'Kondusif' : 'Perlu Atensi Khusus'),
            ];
        }

        return [
            'programs' => $results,
            'totalPsnCount' => $totalPsnCount,
            'startDate' => Carbon::parse($startDate)->format('Y-m-d'),
            'endDate' => Carbon::parse($endDate)->format('Y-m-d'),
        ];
    }
}
