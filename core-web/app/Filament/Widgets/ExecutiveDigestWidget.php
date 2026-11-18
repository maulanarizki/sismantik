<?php

namespace App\Filament\Widgets;

use App\Models\AspectSentiment;
use App\Models\RawContent;
use App\Models\SentimentResult;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\Widget;

class ExecutiveDigestWidget extends Widget
{
    use InteractsWithPageFilters;

    protected static string $view = 'filament.widgets.executive-digest-widget';

    protected static ?int $sort = 8;

    protected int | string | array $columnSpan = 'full';

    public function getViewData(): array
    {
        $startDate = $this->filters['startDate'] ?? now()->subYear()->startOfDay();
        $endDate = $this->filters['endDate'] ?? now()->endOfDay();
        $scope = $this->filters['scope'] ?? 'all';

        $baseContentQuery = RawContent::whereBetween('published_at', [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay(),
        ]);

        if ($scope && $scope !== 'all') {
            $baseContentQuery->where('scope', $scope);
        }

        $contentIds = (clone $baseContentQuery)->pluck('id');
        $botCount = (clone $baseContentQuery)->where('is_suspected_bot', true)->count();

        // 1. Ekstraksi Isu Terhangat
        $topPositiveAspect = AspectSentiment::whereIn('sentiment_result_id', function($q) use ($contentIds) {
                $q->select('id')->from('sentiment_results')->whereIn('raw_content_id', $contentIds);
            })
            ->where('aspect_sentiment_label', 'positive')
            ->with('agency')
            ->latest()
            ->first();

        $topNegativeAspect = AspectSentiment::whereIn('sentiment_result_id', function($q) use ($contentIds) {
                $q->select('id')->from('sentiment_results')->whereIn('raw_content_id', $contentIds);
            })
            ->where('aspect_sentiment_label', 'negative')
            ->with('agency')
            ->latest()
            ->first();

        $totalAnalyzed = SentimentResult::whereIn('raw_content_id', $contentIds)->count();
        $negCount = SentimentResult::whereIn('raw_content_id', $contentIds)->where('sentiment_label', 'negative')->count();

        $negRatio = $totalAnalyzed > 0 ? ($negCount / $totalAnalyzed) : 0;

        // Evaluasi Tingkat Ancaman (Threat Level Matrix)
        if ($negRatio >= 0.40 || $botCount >= 10) {
            $threatLevel = 'MERAH (Krisis Terbuka)';
            $threatBadgeColor = 'red';
        } elseif ($negRatio >= 0.20 || $botCount >= 3) {
            $threatLevel = 'KUNING (Waspada Eskalasi Isu)';
            $threatBadgeColor = 'amber';
        } else {
            $threatLevel = 'HIJAU (Opini Terkendali & Kondusif)';
            $threatBadgeColor = 'emerald';
        }

        $agencyName = $topNegativeAspect ? $topNegativeAspect->agency->name : 'Dinas Terkait Pemkab Jombang';
        $aspectKw = $topNegativeAspect ? $topNegativeAspect->aspect_keyword : 'infrastruktur jalan dan pelayanan dasar';
        $todayStr = now()->translatedFormat('d F Y');

        // Draf Siaran Pers Otomatis (Draft Press Release)
        $draftPressRelease = "SIARAN PERS RESMI PEMERINTAH KABUPATEN JOMBANG\n" .
            "Nomor: 045.2/HM.01/" . now()->format('Y/m') . "\n" .
            "Tentang: Respon Cepat Pemkab Jombang Terhadap Aspirasi Warga Mengenai " . strtoupper($aspectKw) . "\n\n" .
            "JOMBANG, {$todayStr} – Menindaklanjuti laporan masyarakat yang berkembang di media massa dan media sosial terkait {$aspectKw}, Pemerintah Kabupaten Jombang melalui {$agencyName} menyampaikan langkah penanganan langsung di lapangan:\n\n" .
            "1. Pemkab Jombang menyampaikan terima kasih dan apresiasi kepada warga serta insan pers atas keterbukaan informasi dan laporan konstruktif yang disampaikan.\n" .
            "2. Pimpinan daerah telah menginstruksikan tim teknis reaksi cepat {$agencyName} untuk meninjau titik lokasi hari ini dan melaksanakan tindakan perbaikan darurat.\n" .
            "3. Penanganan permanen telah dianggarkan pada APBD tahun berjalan guna memastikan kenyamanan dan keselamatan seluruh masyarakat Kabupaten Jombang.\n\n" .
            "Bagian Protokol & Komunikasi Pimpinan (Prokopim) Setda Kab. Jombang\n" .
            "Kanal Pengaduan Resmi: lapor.jombangkab.go.id";

        return [
            'threat_level' => $threatLevel,
            'threatLevel' => $threatLevel,
            'threat_badge_color' => $threatBadgeColor,
            'lastUpdated' => now()->format('H:i'),
            'last_updated' => now()->format('H:i'),
            'highlights' => [
                'Isu Terhangat Positif' => $topPositiveAspect ? "Apresiasi kinerja: {$topPositiveAspect->agency->name} ({$topPositiveAspect->aspect_keyword})" : 'Respon positif warga atas perbaikan sarana publik dan kelancaran event daerah.',
                'Akar Masalah Negatif' => $topNegativeAspect ? "Keluhan masyarakat menyoroti kendala pada {$topNegativeAspect->aspect_keyword} ({$agencyName}) di wilayah Kab. Jombang." : 'Tuntutan percepatan realisasi infrastruktur dan pemeliharaan sarana.',
                'Rekomendasi Tindak Lanjut' => $topNegativeAspect ? "Instruksikan tim teknis reaksi cepat {$agencyName} untuk verifikasi lapangan dan publikasikan klarifikasi resmi dalam 1x24 jam." : 'Lakukan pemeliharaan rutin dan publikasikan progres kerja dinas.',
            ],
            'issue_title' => $topNegativeAspect ? "Keluhan: {$topNegativeAspect->aspect_keyword} ({$agencyName})" : 'Penataan Fasilitas dan Layanan Umum',
            'issue_root_cause' => $topNegativeAspect ? "Laporan masyarakat dan media regional menyoroti kendala teknis pada {$topNegativeAspect->aspect_keyword} di wilayah Kabupaten Jombang." : 'Tuntutan percepatan realisasi infrastruktur.',
            'top_positive' => $topPositiveAspect ? "Apresiasi kinerja: {$topPositiveAspect->agency->name} ({$topPositiveAspect->aspect_keyword})" : 'Respon positif warga atas perbaikan sarana publik.',
            'draft_press_release' => $draftPressRelease,
            'draftPressRelease' => $draftPressRelease,
            'startDate' => Carbon::parse($startDate)->format('Y-m-d'),
            'endDate' => Carbon::parse($endDate)->format('Y-m-d'),
        ];
    }
}
