<?php

namespace App\Console\Commands;

use App\Models\RawContent;
use App\Models\Source;
use App\Models\SourceEndpoint;
use App\Models\SyncJob;
use App\Services\AnalysisService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SyncHistoricalCommand extends Command
{
    protected $signature = 'sismantik:sync-historical
                            {--months=12 : Jumlah bulan ke belakang untuk ditarik}
                            {--max-per-month=1000 : Kuota maksimal konten per bulan}
                            {--scope=all : Filter cakupan: all, local_pemkab, atau national_strategic}';

    protected $description = 'Sinkronisasi data historis 12 bulan terakhir dengan batasan 1.000 data per bulan (Lokal vs PSN Jombang)';

    protected string $pythonEngineUrl;

    public function __construct()
    {
        parent::__construct();
        $this->pythonEngineUrl = config('services.python_engine.url', env('PYTHON_ENGINE_URL', 'http://127.0.0.1:8001'));
    }

    public function handle(AnalysisService $analysisService): int
    {
        $months = (int) $this->option('months');
        $maxPerMonth = (int) $this->option('max-per-month');
        $scopeFilter = $this->option('scope');

        $this->info("==========================================================================");
        $this->info("   SISMANTIK HISTORICAL INGESTION RUNNER (12 BULAN TERAKHIR)");
        $this->info("   Target: {$months} Bulan | Max {$maxPerMonth} Data/Bulan | Scope: {$scopeFilter}");
        $this->info("==========================================================================");

        $defaultSource = Source::where('code', 'gnews_jombang_aggregator')->first() ?: Source::first();
        $defaultEndpoint = SourceEndpoint::where('scope', 'local_pemkab')->first() ?: SourceEndpoint::first();
        $psnEndpoint = SourceEndpoint::where('scope', 'national_strategic')->first() ?: $defaultEndpoint;

        // Query templates spesifik Kabupaten Jombang
        $localQueries = [
            'pemkab jombang',
            'bupati jombang',
            'jalan rusak jombang',
            'banjir jombang',
            'rsud jombang',
            'ploso jombang',
            'pasar jombang',
            'disdukcapil jombang',
            'wonosalam jombang',
            'peterongan jombang',
        ];

        $psnQueries = [
            'makan bergizi gratis jombang',
            'mbg jombang',
            'dapur umum gizi jombang',
            'koperasi desa jombang',
            'kopdes jombang',
            'korupsi jombang',
            'kejari jombang',
            'tipikor jombang',
            'kpk jombang',
            'swasembada pangan jombang',
        ];

        $psnKeywords = [
            'makan bergizi gratis', 'mbg ', 'mbg,', 'sppg', 'dapur gizi',
            'kopdes', 'koperasi desa', 'koperasi merah putih',
            'tipikor', 'kejari jombang', 'kejaksaan negeri jombang', 'kpk jombang',
            'swasembada pangan', 'bantuan alsintan'
        ];

        $totalIngestedAll = 0;
        $totalLocalAll = 0;
        $totalPsnAll = 0;

        // Iterasi bulan demi bulan (12 bulan ke belakang)
        $now = Carbon::now();

        for ($m = 0; $m < $months; $m++) {
            $monthTarget = $now->copy()->subMonths($m);
            $startDate = $monthTarget->copy()->startOfMonth()->format('Y-m-d');
            $endDate = ($m === 0) 
                ? $now->format('Y-m-d') 
                : $monthTarget->copy()->endOfMonth()->format('Y-m-d');

            $monthLabel = $monthTarget->translatedFormat('F Y');
            $this->line("");
            $this->info("▶ [Bulan " . ($m + 1) . "/{$months}] {$monthLabel} ({$startDate} s/d {$endDate})");

            $queries = [];
            $maxLen = max(count($localQueries), count($psnQueries));
            for ($k = 0; $k < $maxLen; $k++) {
                if (($scopeFilter === 'all' || $scopeFilter === 'national_strategic') && isset($psnQueries[$k])) {
                    $queries[] = ['query' => $psnQueries[$k], 'type' => 'national_strategic'];
                }
                if (($scopeFilter === 'all' || $scopeFilter === 'local_pemkab') && isset($localQueries[$k])) {
                    $queries[] = ['query' => $localQueries[$k], 'type' => 'local_pemkab'];
                }
            }

            $monthIngested = 0;
            $monthLocal = 0;
            $monthPsn = 0;

            foreach ($queries as $qObj) {
                if ($monthIngested >= $maxPerMonth) {
                    $this->warn("   Kuota bulanan tercapai ({$maxPerMonth} data). Melanjutkan ke bulan berikutnya...");
                    break;
                }

                $rawQuery = $qObj['query'];
                $qType = $qObj['type'];

                $encodedQuery = urlencode("{$rawQuery} after:{$startDate} before:{$endDate}");
                $feedUrl = "https://news.google.com/rss/search?q={$encodedQuery}&hl=id&gl=ID&ceid=ID:id";

                try {
                    $response = Http::timeout(25)->post("{$this->pythonEngineUrl}/api/scrape/feed", [
                        'feed_url' => $feedUrl,
                        'max_items' => 50,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ]);

                    if ($response->failed()) {
                        continue;
                    }

                    $items = $response->json();
                    if (empty($items)) {
                        continue;
                    }

                    foreach ($items as $item) {
                        if ($monthIngested >= $maxPerMonth) {
                            break;
                        }

                        $url = $item['url'] ?? '';
                        $title = Str::limit(html_entity_decode(strip_tags($item['title'] ?? '')), 490, '...');
                        $contentBody = html_entity_decode(strip_tags($item['content'] ?? $item['summary'] ?? ''));
                        $contentHash = $item['content_hash'] ?? hash('sha256', $url . $title);

                        if (empty($title)) {
                            continue;
                        }

                        // Cek deduplikasi: jika sudah ada persis, lewati untuk optimasi
                        $existing = RawContent::where('content_hash', $contentHash)->first();
                        if ($existing) {
                            continue;
                        }

                        // Deteksi scope
                        $detectedScope = $qType;
                        $fullTextLower = strtolower($title . ' ' . $contentBody);
                        foreach ($psnKeywords as $pkw) {
                            if (str_contains($fullTextLower, $pkw)) {
                                $detectedScope = 'national_strategic';
                                break;
                            }
                        }

                        $assignedEndpoint = ($detectedScope === 'national_strategic') ? $psnEndpoint : $defaultEndpoint;

                        // Normalisasi teks
                        $prepRes = Http::post("{$this->pythonEngineUrl}/api/preprocess", [
                            'text' => $title . ' ' . $contentBody,
                        ]);
                        $cleanText = $prepRes->successful() ? $prepRes->json('cleaned_text') : null;

                        $pubDate = !empty($item['published_at']) ? Carbon::parse($item['published_at']) : Carbon::parse($startDate)->addDays(rand(1, 20));

                        $rawRecord = RawContent::create([
                            'source_endpoint_id' => $assignedEndpoint->id,
                            'external_id' => $url,
                            'author' => Str::limit($item['author'] ?? 'Redaksi', 250, ''),
                            'title' => $title,
                            'content_text' => $contentBody ?: $title,
                            'clean_text' => $cleanText,
                            'url' => $url,
                            'content_hash' => $contentHash,
                            'is_duplicate' => false,
                            'scope' => $detectedScope,
                            'parent_id' => null,
                            'published_at' => $pubDate,
                            'is_analyzed' => false,
                        ]);

                        // Eksekusi pipeline NLP otomatis
                        $analysisService->analyzeSingle($rawRecord);

                        $monthIngested++;
                        if ($detectedScope === 'national_strategic') {
                            $monthPsn++;
                        } else {
                            $monthLocal++;
                        }
                    }

                    // Politeness sleep 200ms
                    usleep(200000);

                } catch (\Throwable $e) {
                    Log::warning("Kueri {$rawQuery} bulan {$startDate} error: " . $e->getMessage());
                }
            }

            $totalIngestedAll += $monthIngested;
            $totalLocalAll += $monthLocal;
            $totalPsnAll += $monthPsn;

            $this->line("   ✓ Hasil Bulan {$monthLabel}: <fg=green>{$monthIngested} data</> (Lokal: {$monthLocal}, PSN Pusat: {$monthPsn})");
        }

        $this->info("==========================================================================");
        $this->info("   SINKRONISASI 12 BULAN SELESAI!");
        $this->info("   Total Data Terkumpul: {$totalIngestedAll} Konten");
        $this->info("   - Isu Lokal Pemkab Jombang: {$totalLocalAll} Konten");
        $this->info("   - Program Strategis Nasional (PSN) Jombang: {$totalPsnAll} Konten");
        $this->info("   Total Seluruh Data di Database: " . RawContent::count() . " Konten");
        $this->info("==========================================================================");

        return 0;
    }
}
