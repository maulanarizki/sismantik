<?php

namespace App\Services;

use App\Models\RawContent;
use App\Models\Source;
use App\Models\SourceEndpoint;
use App\Models\SyncJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class IngestionService
{
    protected string $pythonEngineUrl;

    public function __construct()
    {
        $this->pythonEngineUrl = config('services.python_engine.url', env('PYTHON_ENGINE_URL', 'http://127.0.0.1:8001'));
    }

    /**
     * Sinkronisasi data pada satu endpoint spesifik.
     */
    public function syncEndpoint(
        SourceEndpoint $endpoint,
        ?string $startDate = null,
        ?string $endDate = null,
        int $maxItems = 30,
        string $triggeredBy = 'manual_user'
    ): SyncJob {
        $syncJob = SyncJob::create([
            'job_uuid' => (string) Str::uuid(),
            'source_id' => $endpoint->source_id,
            'endpoint_id' => $endpoint->id,
            'status' => 'running',
            'triggered_by' => $triggeredBy,
            'start_date' => $startDate ? Carbon::parse($startDate) : null,
            'end_date' => $endDate ? Carbon::parse($endDate) : null,
            'max_items_limit' => $maxItems,
            'items_scraped' => 0,
            'items_processed' => 0,
            'started_at' => now(),
            'log_messages' => "Memulai sinkronisasi endpoint: {$endpoint->name}\n",
        ]);

        try {
            if ($endpoint->parser_type === 'rss') {
                $response = Http::timeout(30)->post("{$this->pythonEngineUrl}/api/scrape/feed", [
                    'feed_url' => $endpoint->endpoint_url,
                    'max_items' => $maxItems,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]);

                if ($response->failed()) {
                    throw new \Exception("Gagal menghubungi engine Python: " . $response->body());
                }

                $items = $response->json();
                $scrapedCount = count($items);
                $processedCount = 0;

                foreach ($items as $item) {
                    $contentHash = $item['content_hash'] ?? hash('sha256', $item['url'] . $item['title']);
                    
                    // Cek apakah konten sudah ada
                    $exists = RawContent::where('content_hash', $contentHash)->first();
                    $isDuplicate = false;
                    $parentId = null;

                    if ($exists) {
                        $isDuplicate = true;
                        $parentId = $exists->id;
                    }

                    $cleanTitle = Str::limit(html_entity_decode(strip_tags($item['title'] ?? 'Tanpa Judul')), 490, '...');
                    $rawBody = html_entity_decode(strip_tags($item['content'] ?? $item['summary'] ?? ''));

                    // Preprocess teks lokal
                    $prepResponse = Http::post("{$this->pythonEngineUrl}/api/preprocess", [
                        'text' => $cleanTitle . ' ' . $rawBody
                    ]);

                    $cleanText = $prepResponse->successful() ? $prepResponse->json('cleaned_text') : null;

                    // Klasifikasi Scope: Deteksi apakah Isu Lokal Pemda atau Program Strategis Nasional (PSN) di Jombang
                    $scope = $endpoint->scope ?? 'local_pemkab';
                    $fullTextLower = strtolower($cleanTitle . ' ' . $rawBody);
                    $psnPatterns = ['makan bergizi gratis', 'mbg ', 'mbg,', 'sppg', 'dapur gizi', 'kopdes', 'koperasi desa', 'koperasi merah putih', 'tipikor', 'kejari jombang', 'kejaksaan negeri jombang', 'kpk jombang', 'swasembada pangan', 'bantuan alsintan'];
                    foreach ($psnPatterns as $pkw) {
                        if (str_contains($fullTextLower, $pkw)) {
                            $scope = 'national_strategic';
                            break;
                        }
                    }

                    $rawContent = RawContent::create([
                        'source_endpoint_id' => $endpoint->id,
                        'sync_job_id' => $syncJob->id,
                        'external_id' => $item['url'] ?? null,
                        'author' => Str::limit($item['author'] ?? 'Redaksi', 250, ''),
                        'title' => $cleanTitle,
                        'content_text' => $rawBody ?: $cleanTitle,
                        'clean_text' => $cleanText,
                        'url' => $item['url'],
                        'content_hash' => $contentHash,
                        'is_duplicate' => $isDuplicate,
                        'scope' => $scope,
                        'parent_id' => $parentId,
                        'published_at' => !empty($item['published_at']) ? Carbon::parse($item['published_at']) : now(),
                        'is_analyzed' => false,
                    ]);

                    // Otomatis jalankan pipeline NLP & ABSA
                    app(AnalysisService::class)->analyzeSingle($rawContent);

                    $processedCount++;
                }

                $syncJob->update([
                    'status' => 'completed',
                    'items_scraped' => $scrapedCount,
                    'items_processed' => $processedCount,
                    'finished_at' => now(),
                    'log_messages' => $syncJob->log_messages . "Sukses mengambil {$scrapedCount} item dan memproses {$processedCount} konten.\n",
                ]);

                // Update last_synced_at pada source
                $endpoint->source->update(['last_synced_at' => now()]);
            } else {
                // Untuk parser selain RSS (mock scraping artikel/medsos publik sementara)
                $syncJob->update([
                    'status' => 'completed',
                    'items_scraped' => 0,
                    'items_processed' => 0,
                    'finished_at' => now(),
                    'log_messages' => $syncJob->log_messages . "Endpoint tipe {$endpoint->parser_type} siap diproses via worker headless.\n",
                ]);
            }
        } catch (\Throwable $e) {
            Log::error("Sync error: " . $e->getMessage());
            $syncJob->update([
                'status' => 'failed',
                'finished_at' => now(),
                'log_messages' => $syncJob->log_messages . "ERROR: " . $e->getMessage() . "\n",
            ]);
        }

        return $syncJob;
    }

    /**
     * Sinkronisasi seluruh endpoint aktif pada sebuah source.
     */
    public function syncSource(
        Source $source,
        ?string $startDate = null,
        ?string $endDate = null,
        int $maxItems = 30
    ): array {
        $jobs = [];
        foreach ($source->endpoints()->where('is_active', true)->get() as $endpoint) {
            $jobs[] = $this->syncEndpoint($endpoint, $startDate, $endDate, $maxItems);
        }
        return $jobs;
    }
}

