<?php

namespace Database\Seeders;

use App\Models\RawContent;
use App\Models\SourceEndpoint;
use App\Services\AnalysisService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleAnalyticsSeeder extends Seeder
{
    public function run(): void
    {
        $endpoint = SourceEndpoint::first() ?? SourceEndpoint::create([
            'source_id' => 1,
            'name' => 'Komentar Media Warga',
            'endpoint_url' => 'https://facebook.com/komentarwarga',
            'parser_type' => 'social_profile',
            'default_max_items' => 50,
            'is_active' => true,
        ]);

        $samples = [
            // Dinas PUPR (Infrastruktur)
            [
                'author' => 'Budi Santoso (Warga Kenjeran)',
                'title' => 'Akses jalan berlubang di Kenjeran',
                'content' => 'Akses jalan di Kecamatan Kenjeran rusak parah bgt dalan ajur, membahayakan pengendara motor. Mohon dinas PUPR segera aspal perbaiki!',
                'days_ago' => 0,
            ],
            [
                'author' => 'Siti Rahma',
                'title' => 'Jembatan penghubung desa selesai',
                'content' => 'Alhamdulillah perbaikan jembatan dan aspal jalan di ruas timur sudah selesai dan sangat bagus halus mantap sekali dinas PUPR top!',
                'days_ago' => 1,
            ],
            [
                'author' => 'Agus Priyono',
                'title' => 'Saluran drainase mampet',
                'content' => 'Hujan sebentar langsung banjir rek jalan tergenang air, gorong-gorong dan drainase mampet PUPR tolong dinormalisasi.',
                'days_ago' => 2,
            ],

            // Dinas Kesehatan (Dinkes)
            [
                'author' => 'drg. Maya',
                'title' => 'Pelayanan ramah RSUD',
                'content' => 'Pelayanan di RSUD dan puskesmas sekarang sangat ramah dokter obat lancar cepat gercep suwun sanget!',
                'days_ago' => 0,
            ],
            [
                'author' => 'Rina Wijaya',
                'title' => 'Antrean obat puskesmas lama',
                'content' => 'Antrean loket obat di puskesmas lambat pol, nakes kurang tanggap sudah nunggu 3 jam belum dipanggil ngelu pusing!',
                'days_ago' => 1,
            ],
            [
                'author' => 'Pak Joko',
                'title' => 'Posyandu balita aktif',
                'content' => 'Program posyandu stunting pencegahan gizi buruk di kelurahan berjalan tertib dan sangat membantu ibu-ibu balita.',
                'days_ago' => 3,
            ],

            // Disdukcapil
            [
                'author' => 'Hendra Pratama',
                'title' => 'KTP digital sangat praktis',
                'content' => 'Urus IKD identitas kependudukan digital di Disdukcapil cepat sekali 10 menit beres tanpa ribet mantap!',
                'days_ago' => 0,
            ],
            [
                'author' => 'Dewi Lestari',
                'title' => 'Server dukcapil offline',
                'content' => 'Sudah antre dari subuh mau cetak KTP dan KK tapi server dukcapil offline terus ra cetho bgt pelayanan bapuk!',
                'days_ago' => 1,
            ],

            // Dinas Pendidikan
            [
                'author' => 'Wali Murid SDN',
                'title' => 'Gedung sekolah rusak',
                'content' => 'Kondisi atap kelas gedung SD Negeri rusak bocor dan membahayakan siswa saat hujan, dinas pendidikan mohon cek ke lokasi.',
                'days_ago' => 2,
            ],
            [
                'author' => 'Fajar Nugroho',
                'title' => 'Apresiasi beasiswa pemda',
                'content' => 'Program beasiswa pemuda tangguh berprestasi sangat adil transparan dan membantu anak-anak kurang mampu kuliah.',
                'days_ago' => 4,
            ],

            // Dishub
            [
                'author' => 'Rian Driver',
                'title' => 'Lampu merah mati',
                'content' => 'Lampu merah di perempatan simpang padam bikin macet parah dan rawan kecelakaan, dishub kemana ini?',
                'days_ago' => 0,
            ],
            [
                'author' => 'Nurul Huda',
                'title' => 'Uji KIR kendaraan cepat',
                'content' => 'Pelayanan uji kir kendaraan bermotor di kantor dishub sekarang bebas pungli dan cepat selesai apik tenan.',
                'days_ago' => 2,
            ],

            // Dinas Sosial
            [
                'author' => 'Mbah Sumo',
                'title' => 'Penyaluran beras bansos',
                'content' => 'Penyaluran bantuan beras bansos dan PKH di kelurahan gubeng lancar tepat sasaran suwun dinas sosial.',
                'days_ago' => 1,
            ],

            // Bot / Buzzer Spams (Duplikasi komentar massal)
            [
                'author' => 'bot_user_01',
                'title' => 'Kritik Pemda',
                'content' => 'Birokrasi pemda payah tidak becus pimpinan harus diganti sekarang juga bobrok!',
                'days_ago' => 0,
                'is_spam' => true,
            ],
            [
                'author' => 'bot_user_02',
                'title' => 'Kritik Pemda',
                'content' => 'Birokrasi pemda payah tidak becus pimpinan harus diganti sekarang juga bobrok!',
                'days_ago' => 0,
                'is_spam' => true,
            ],
            [
                'author' => 'bot_user_03',
                'title' => 'Kritik Pemda',
                'content' => 'Birokrasi pemda payah tidak becus pimpinan harus diganti sekarang juga bobrok!',
                'days_ago' => 0,
                'is_spam' => true,
            ],
            [
                'author' => 'bot_user_04',
                'title' => 'Kritik Pemda',
                'content' => 'Birokrasi pemda payah tidak becus pimpinan harus diganti sekarang juga bobrok!',
                'days_ago' => 0,
                'is_spam' => true,
            ],
        ];

        $analysisService = app(AnalysisService::class);

        foreach ($samples as $idx => $sample) {
            $contentHash = hash('sha256', $sample['content']);
            $raw = RawContent::create([
                'source_endpoint_id' => $endpoint->id,
                'external_id' => 'sample_' . ($idx + 1),
                'author' => $sample['author'],
                'title' => $sample['title'],
                'content_text' => $sample['content'],
                'clean_text' => strtolower($sample['content']),
                'url' => 'https://facebook.com/komentar/' . ($idx + 1),
                'content_hash' => $contentHash,
                'published_at' => Carbon::now()->subDays($sample['days_ago'])->subHours(rand(1, 12)),
                'is_analyzed' => false,
            ]);

            $analysisService->analyzeSingle($raw);
        }
    }
}

