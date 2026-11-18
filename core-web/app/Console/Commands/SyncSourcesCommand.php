<?php

namespace App\Console\Commands;

use App\Models\Source;
use App\Services\IngestionService;
use Illuminate\Console\Command;

class SyncSourcesCommand extends Command
{
    protected $signature = 'sismantik:sync 
                            {--source= : Kode atau ID sumber tertentu} 
                            {--start-date= : Dari tanggal YYYY-MM-DD} 
                            {--end-date= : Sampai tanggal YYYY-MM-DD} 
                            {--max=30 : Batas maksimal konten per endpoint}';

    protected $description = 'Eksekusi sinkronisasi data media monitoring (portal berita & media sosial)';

    public function handle(IngestionService $service): int
    {
        $this->info("=== SISMANTIK Data Ingestion Runner ===");

        $sourceCode = $this->option('source');
        $startDate = $this->option('start-date');
        $endDate = $this->option('end-date');
        $max = (int) $this->option('max');

        $query = Source::where('is_active', true);
        if ($sourceCode) {
            $query->where(function ($q) use ($sourceCode) {
                $q->where('code', $sourceCode)->orWhere('id', $sourceCode);
            });
        }

        $sources = $query->get();
        if ($sources->isEmpty()) {
            $this->warn("Tidak ada sumber aktif yang ditemukan untuk disinkronkan.");
            return 0;
        }

        foreach ($sources as $source) {
            $this->line("-> Menyinkronkan sumber: <fg=cyan>{$source->name}</>");
            $jobs = $service->syncSource($source, $startDate, $endDate, $max);
            foreach ($jobs as $job) {
                $statusColor = $job->status === 'completed' ? 'green' : 'red';
                $this->line("   [Endpoint #{$job->endpoint_id}] Status: <fg={$statusColor}>{$job->status}</>, Terambil: {$job->items_scraped}");
            }
        }

        $this->info("Sinkronisasi selesai! Total konten saat ini: " . \App\Models\RawContent::count());
        return 0;
    }
}

