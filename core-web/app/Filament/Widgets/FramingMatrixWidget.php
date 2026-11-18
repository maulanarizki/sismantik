<?php

namespace App\Filament\Widgets;

use App\Models\RawContent;
use App\Models\SentimentResult;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class FramingMatrixWidget extends Widget
{
    use InteractsWithPageFilters;

    protected static string $view = 'filament.widgets.framing-matrix-widget';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = [
        'md' => 1,
        'xl' => 1,
    ];

    public function getViewData(): array
    {
        $startDate = $this->filters['startDate'] ?? now()->subYear()->startOfDay();
        $endDate = $this->filters['endDate'] ?? now()->endOfDay();
        $scope = $this->filters['scope'] ?? 'all';

        $contentQuery = RawContent::query()
            ->whereBetween('published_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);

        if ($scope && $scope !== 'all') {
            $contentQuery->where('scope', $scope);
        }

        $contentIds = $contentQuery->pluck('id');

        $counts = SentimentResult::whereIn('raw_content_id', $contentIds)
            ->selectRaw('framing_category, count(*) as total')
            ->groupBy('framing_category')
            ->pluck('total', 'framing_category')
            ->toArray();

        $countAdmin = $counts['administrative'] ?? 0;
        $countHuman = $counts['human_interest'] ?? 0;
        $countEcon = $counts['economic'] ?? 0;
        $countConflict = $counts['conflict'] ?? 0;
        $countCorrupt = $counts['corruption'] ?? 0;

        $totalMatches = $countAdmin + $countHuman + $countEcon + $countConflict + $countCorrupt;
        if ($totalMatches == 0) {
            $totalMatches = 1;
        }

        $frames = [
            [
                'category' => 'administrative',
                'name' => 'Administrative Incompetence',
                'label' => 'Birokrasi & Layanan Lambat',
                'count' => $countAdmin,
                'percentage' => round(($countAdmin / $totalMatches) * 100),
                'risk_level' => 'SEDANG',
                'color' => 'amber',
                'recommendation' => 'Perbaiki alur SOP & verifikasi teknis pelayanan di lapangan.',
            ],
            [
                'category' => 'human_interest',
                'name' => 'Human Interest & Emosional',
                'label' => 'Kemanusiaan / Bencana / Korban',
                'count' => $countHuman,
                'percentage' => round(($countHuman / $totalMatches) * 100),
                'risk_level' => 'TINGGI (Viral)',
                'color' => 'rose',
                'recommendation' => 'Kunjungan langsung pimpinan & respon darurat < 3 jam.',
            ],
            [
                'category' => 'economic',
                'name' => 'Economic Burden',
                'label' => 'Beban Ekonomi & Pasar',
                'count' => $countEcon,
                'percentage' => round(($countEcon / $totalMatches) * 100),
                'risk_level' => 'SEDANG',
                'color' => 'sky',
                'recommendation' => 'Sosialisasi skema subsidi & peninjauan ulang retribusi.',
            ],
            [
                'category' => 'conflict',
                'name' => 'Conflict & Polarization',
                'label' => 'Konflik Politik & Perang Opini',
                'count' => $countConflict,
                'percentage' => round(($countConflict / $totalMatches) * 100),
                'risk_level' => 'WASPADA',
                'color' => 'purple',
                'recommendation' => 'Komunikasi satu pintu & rekonsiliasi antar-lembaga daerah.',
            ],
            [
                'category' => 'corruption',
                'name' => 'Corruption & Morality',
                'label' => 'Isu Dugaan Pungli / Tipikor',
                'count' => $countCorrupt,
                'percentage' => round(($countCorrupt / $totalMatches) * 100),
                'risk_level' => $countCorrupt > 0 ? 'BAHAYA' : 'TERKENDALI',
                'color' => $countCorrupt > 0 ? 'red' : 'emerald',
                'recommendation' => 'Pemeriksaan audit investigatif internal Inspektorat Daerah.',
            ],
        ];

        return [
            'frames' => $frames,
            'totalAnalyzed' => count($contentIds),
            'startDate' => Carbon::parse($startDate)->format('Y-m-d'),
            'endDate' => Carbon::parse($endDate)->format('Y-m-d'),
        ];
    }
}
