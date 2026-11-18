<?php

namespace App\Console\Commands;

use App\Services\AnalysisService;
use Illuminate\Console\Command;

class AnalyzeContentCommand extends Command
{
    protected $signature = 'sismantik:analyze {--limit=50 : Jumlah konten yang dianalisis}';

    protected $description = 'Jalankan analisis sentimen, emosi, ABSA per OPD, dan ekstraksi geo';

    public function handle(AnalysisService $service): int
    {
        $limit = (int) $this->option('limit');
        $this->info("Menjalankan pipeline analisis NLP lokal untuk {$limit} konten...");

        $count = $service->analyzeUnanalyzed($limit);

        $this->info("✓ Sukses menganalisis {$count} konten!");
        return 0;
    }
}

