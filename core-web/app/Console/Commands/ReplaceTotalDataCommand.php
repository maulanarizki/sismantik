<?php

namespace App\Console\Commands;

use App\Models\AspectSentiment;
use App\Models\GeoExtraction;
use App\Models\RawContent;
use App\Models\SentimentResult;
use App\Models\SyncJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ReplaceTotalDataCommand extends Command
{
    protected $signature = 'sismantik:replace-total
                            {--months=12 : Jumlah bulan ke belakang untuk ditarik ulang}
                            {--max-per-month=1000 : Batas maksimal konten per bulan}
                            {--confirm=no : Konfirmasi penghapusan data lama (yes/no)}';

    protected $description = 'Mengosongkan seluruh histori data berita lama dan menarik ulang data segar dari awal (Replace Total)';

    public function handle(): int
    {
        $months = (int) $this->option('months');
        $maxPerMonth = (int) $this->option('max-per-month');
        $confirm = $this->option('confirm');

        $this->warn("==========================================================================");
        $this->warn("   PERINGATAN: FITUR REPLACE DATA SECARA TOTAL (CLEAN RE-INGESTION)");
        $this->warn("==========================================================================");

        if ($confirm !== 'yes') {
            if (!$this->confirm('Apakah Anda yakin ingin MENGHAPUS SELURUH data berita dan analisis lama, lalu menarik ulang dari sumber media?', false)) {
                $this->info("Operasi dibatalkan.");
                return 0;
            }
        }

        $this->line("1. Mengosongkan data transaksi lama (Clean Slate)...");
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('aspect_sentiments')->truncate();
        DB::table('sentiment_results')->truncate();
        DB::table('geo_extractions')->truncate();
        DB::table('raw_contents')->truncate();
        DB::table('sync_jobs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
        $this->info("   ✓ Seluruh data berita dan analisis lama berhasil dibersihkan.");

        $this->line("2. Menjalankan penarikan ulang data segar selama {$months} bulan (Maks {$maxPerMonth} data/bulan)...");
        
        Artisan::call('sismantik:sync-historical', [
            '--months' => $months,
            '--max-per-month' => $maxPerMonth,
            '--scope' => 'all',
        ], $this->output);

        $totalNew = RawContent::count();
        $this->info("==========================================================================");
        $this->info("   REPLACE DATA TOTAL BERHASIL SELESAI!");
        $this->info("   Total Data Segar Baru di Database: {$totalNew} Konten.");
        $this->info("==========================================================================");

        return 0;
    }
}

