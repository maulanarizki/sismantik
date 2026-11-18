<?php

namespace Database\Seeders;

use App\Models\AspectSentiment;
use App\Models\GeoExtraction;
use App\Models\RawContent;
use App\Models\SentimentResult;
use App\Models\Source;
use App\Models\SourceEndpoint;
use App\Models\SyncJob;
use App\Models\TopicOrAgency;
use App\Services\AnalysisService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JombangMasterSeeder extends Seeder
{
    public function run(): void
    {
        // =========================================================================
        // 1. CLEAN SLATE RESET: Kosongkan seluruh data lama
        // =========================================================================
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        DB::table('aspect_sentiments')->truncate();
        DB::table('sentiment_results')->truncate();
        DB::table('geo_extractions')->truncate();
        DB::table('raw_contents')->truncate();
        DB::table('sync_jobs')->truncate();
        DB::table('issue_clusters')->truncate();
        DB::table('actor_nodes')->truncate();
        DB::table('intelligence_briefs')->truncate();
        DB::table('source_endpoints')->truncate();
        DB::table('sources')->truncate();
        DB::table('topics_or_agencies')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');

        // =========================================================================
        // 2. DAFTAR 10 OPD & INSTANSI KUNCI PEMKAB JOMBANG
        // =========================================================================
        $agencies = [
            [
                'name' => 'Dinas Pekerjaan Umum & Penataan Ruang (PUPR) Kab. Jombang',
                'code' => 'dinas_pupr_jombang',
                'category' => 'Infrastruktur & Jalan',
                'keywords' => ['jalan rusak', 'aspal', 'jembatan ploso', 'lubang jalan', 'tambal sulam', 'ploso-kabuh', 'irigasi', 'drainase', 'trotoar', 'ruas jalan'],
                'aliases' => ['Dinas PUPR Jombang', 'Bina Marga Jombang'],
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Kesehatan Kab. Jombang (RSUD Jombang & RSUD Ploso)',
                'code' => 'dinkes_jombang',
                'category' => 'Layanan Kesehatan',
                'keywords' => ['rsud jombang', 'rsud ploso', 'puskesmas', 'antrean bpjs', 'dokter', 'perawat', 'nakes', 'obat', 'rawat inap', 'stunting', 'posyandu'],
                'aliases' => ['Dinkes Jombang', 'RSUD Jombang', 'RSUD Ploso'],
                'is_active' => true,
            ],
            [
                'name' => 'Disdukcapil Kab. Jombang',
                'code' => 'disdukcapil_jombang',
                'category' => 'Pelayanan Adminduk',
                'keywords' => ['ktp', 'ikd', 'kartu keluarga', 'akta kelahiran', 'antrean dukcapil', 'mobil keliling dukcapil', 'server offline', 'identitas kependudukan digital'],
                'aliases' => ['Disdukcapil Jombang', 'Capil Jombang'],
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Pertanian Kab. Jombang',
                'code' => 'disperta_jombang',
                'category' => 'Pertanian & Agribisnis',
                'keywords' => ['pupuk subsidi', 'petani tebu', 'pupuk langka', 'gabah', 'irigasi sawah', 'kenduren durian wonosalam', 'durian wonosalam', 'panen raya'],
                'aliases' => ['Disperta Jombang', 'Dinas Pertanian'],
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Pendidikan & Kebudayaan Kab. Jombang',
                'code' => 'disdikbud_jombang',
                'category' => 'Pendidikan & Kebudayaan',
                'keywords' => ['sekolah', 'gedung sd rusak', 'guru honorer', 'ppdb zonasi', 'beasiswa santri', 'seragam sekolah', 'madrasah', 'cagar budaya'],
                'aliases' => ['Disdikbud Jombang', 'Dinas Pendidikan'],
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Perhubungan Kab. Jombang',
                'code' => 'dishub_jombang',
                'category' => 'Lalu Lintas & Transportasi',
                'keywords' => ['lampu pju mati', 'kemacetan mojoagung', 'palang pintu ka', 'jembatan ploso', 'parkir liar', 'rambu lalu lintas', 'terminal kepuhsari', 'uji kir'],
                'aliases' => ['Dishub Jombang'],
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Lingkungan Hidup (DLH) Kab. Jombang',
                'code' => 'dlh_jombang',
                'category' => 'Kebersihan & Lingkungan Hidup',
                'keywords' => ['alun-alun jombang', 'sampah', 'bau limbah', 'tpa banjardowo', 'rth taman keplaksari', 'kebersihan kota', 'pohon tumbang'],
                'aliases' => ['DLH Jombang'],
                'is_active' => true,
            ],
            [
                'name' => 'BPBD Kab. Jombang',
                'code' => 'bpbd_jombang',
                'category' => 'Penanggulangan Bencana',
                'keywords' => ['banjir bandar kedungmulyo', 'kali gunting mojoagung', 'kali konto', 'tanggul jebol', 'longsor wonosalam', 'kekeringan kabuh', 'bencana banjir'],
                'aliases' => ['BPBD Jombang'],
                'is_active' => true,
            ],
            [
                'name' => 'Disperindag Kab. Jombang',
                'code' => 'disperindag_jombang',
                'category' => 'Pasar & Perdagangan',
                'keywords' => ['pasar citra niaga', 'pcn', 'pkl alun-alun', 'harga beras', 'pasar legi', 'revitalisasi pasar', 'minyak goreng', 'pedagang pasar'],
                'aliases' => ['Disperindag Jombang'],
                'is_active' => true,
            ],
            [
                'name' => 'Bagian Kesra Setda Kab. Jombang',
                'code' => 'kesra_jombang',
                'category' => 'Keagamaan & Kesejahteraan Rakyat',
                'keywords' => ['pesantren', 'tebuireng', 'tambakberas', 'denanyar', 'darul ulum', 'kiai', 'santri', 'haul gus dur', 'hibah keagamaan', 'guru ngaji'],
                'aliases' => ['Kesra Jombang', 'Kesra Pemkab'],
                'is_active' => true,
            ],
        ];

        foreach ($agencies as $a) {
            TopicOrAgency::create($a);
        }

        // =========================================================================
        // 3. DAFTAR SUMBER MEDIA & ENDPOINT SPESIFIK KABUPATEN JOMBANG
        // =========================================================================
        $sources = [
            [
                'name' => 'Radar Jombang (Jawa Pos Group)',
                'code' => 'radar_jombang',
                'platform_type' => 'web_rss',
                'tier' => 'tier_2',
                'authority_score' => 0.90,
                'base_url' => 'https://radarjombang.jawapos.com',
                'description' => 'Surat kabar harian regional utama di Kabupaten Jombang (Jawa Pos Group)',
                'is_active' => true,
                'sync_interval_minutes' => 30,
                'endpoints' => [
                    ['name' => 'Radar Jombang Berita Daerah', 'url' => 'https://news.google.com/rss/search?q=site:radarjombang.jawapos.com+when:7d&hl=id&gl=ID&ceid=ID:id', 'parser' => 'rss', 'limit' => 30],
                ]
            ],
            [
                'name' => 'Google News Aggregator Kab. Jombang',
                'code' => 'gnews_jombang_aggregator',
                'platform_type' => 'web_rss',
                'tier' => 'tier_2',
                'authority_score' => 0.88,
                'base_url' => 'https://news.google.com',
                'description' => 'Pelacak otomatis puluhan media daring seputar Kabupaten Jombang',
                'is_active' => true,
                'sync_interval_minutes' => 30,
                'endpoints' => [
                    ['name' => 'Isu Pemkab & Bupati Jombang', 'url' => 'https://news.google.com/rss/search?q=pemkab+jombang+when:7d&hl=id&gl=ID&ceid=ID:id', 'parser' => 'rss', 'limit' => 30],
                    ['name' => 'Isu Jalan Rusak & Infrastruktur Jombang', 'url' => 'https://news.google.com/rss/search?q=jalan+rusak+jombang+when:7d&hl=id&gl=ID&ceid=ID:id', 'parser' => 'rss', 'limit' => 25],
                    ['name' => 'Isu Banjir & Bencana Jombang', 'url' => 'https://news.google.com/rss/search?q=banjir+jombang+when:7d&hl=id&gl=ID&ceid=ID:id', 'parser' => 'rss', 'limit' => 25],
                    ['name' => 'Isu RSUD & Kesehatan Jombang', 'url' => 'https://news.google.com/rss/search?q=rsud+jombang+when:7d&hl=id&gl=ID&ceid=ID:id', 'parser' => 'rss', 'limit' => 25],
                ]
            ],
            [
                'name' => 'Beritajatim (Kanal Jombang)',
                'code' => 'beritajatim_jombang',
                'platform_type' => 'web_rss',
                'tier' => 'tier_2',
                'authority_score' => 0.85,
                'base_url' => 'https://beritajatim.com',
                'description' => 'Kanal berita seputar peristiwa dan pemerintahan di Jombang',
                'is_active' => true,
                'sync_interval_minutes' => 45,
                'endpoints' => [
                    ['name' => 'Beritajatim Liputan Jombang', 'url' => 'https://news.google.com/rss/search?q=site:beritajatim.com+jombang+when:7d&hl=id&gl=ID&ceid=ID:id', 'parser' => 'rss', 'limit' => 25],
                ]
            ],
            [
                'name' => 'Antara News (Biro Jawa Timur / Jombang)',
                'code' => 'antara_jatim_jombang',
                'platform_type' => 'web_rss',
                'tier' => 'tier_1',
                'authority_score' => 0.98,
                'base_url' => 'https://jatim.antaranews.com',
                'description' => 'Kantor Berita Negara liputan regional Jawa Timur & Jombang (Tier 1)',
                'is_active' => true,
                'sync_interval_minutes' => 30,
                'endpoints' => [
                    ['name' => 'Antara News Liputan Jombang', 'url' => 'https://news.google.com/rss/search?q=site:jatim.antaranews.com+jombang+when:7d&hl=id&gl=ID&ceid=ID:id', 'parser' => 'rss', 'limit' => 25],
                ]
            ],
            [
                'name' => 'Instagram Publik Humas Pemkab Jombang',
                'code' => 'ig_pemkab_jombang',
                'platform_type' => 'instagram',
                'tier' => 'tier_4',
                'authority_score' => 0.75,
                'base_url' => 'https://www.instagram.com/pemkabjombang/',
                'description' => 'Kanal resmi Pemerintah Kabupaten Jombang di Instagram',
                'is_active' => true,
                'sync_interval_minutes' => 30,
                'endpoints' => [
                    ['name' => 'Komentar Akun Resmi @pemkabjombang', 'url' => 'https://www.instagram.com/pemkabjombang/', 'parser' => 'social_profile', 'limit' => 40],
                ]
            ],
            [
                'name' => 'Facebook Forum Warga Jombang (ILKJ & Komunitas)',
                'code' => 'fb_warga_jombang',
                'platform_type' => 'facebook',
                'tier' => 'tier_4',
                'authority_score' => 0.65,
                'base_url' => 'https://www.facebook.com',
                'description' => 'Komunitas warga Facebook Info Lantas & Kriminal Jombang (ILKJ) dan aspirasi publik',
                'is_active' => true,
                'sync_interval_minutes' => 60,
                'endpoints' => [
                    ['name' => 'Unggahan & Komentar Lapor Warga Jombang', 'url' => 'https://www.facebook.com/groups/wargajombang/', 'parser' => 'social_profile', 'limit' => 50],
                ]
            ],
        ];

        $endpointMap = [];
        foreach ($sources as $sData) {
            $epList = $sData['endpoints'] ?? [];
            unset($sData['endpoints']);

            $src = Source::create($sData);
            foreach ($epList as $ep) {
                $endpointMap[$src->code] = SourceEndpoint::create([
                    'source_id' => $src->id,
                    'name' => $ep['name'],
                    'endpoint_url' => $ep['url'],
                    'parser_type' => $ep['parser'],
                    'default_max_items' => $ep['limit'],
                    'is_active' => true,
                ]);
            }
        }

        // =========================================================================
        // 4. SEEDING 35+ KONTEN NYATA ISU KABUPATEN JOMBANG (KOTA SANTRI)
        // =========================================================================
        $defaultEndpoint = $endpointMap['radar_jombang'] ?? SourceEndpoint::first();

        $jombangPosts = [
            // --- KAWASAN UTARA BRANTAS (Ploso, Kabuh, Plandaan, Kudu, Ngusikan) ---
            [
                'author' => 'Slamet Riyadi (Warga Ploso)',
                'title' => 'Jalan Poros Ploso-Kabuh Rusak Berlubang Parah',
                'content' => 'Ruas jalan poros Kecamatan Ploso menuju Kabuh rusak parah berlubang bgt dalan ajur, membahayakan pengendara motor truk industri sering melintas. Mohon dinas PUPR Jombang segera aspal perbaiki!',
                'days_ago' => 0,
            ],
            [
                'author' => 'Hj. Mahmudah',
                'title' => 'Apresiasi Akses Jembatan Ploso Baru',
                'content' => 'Alhamdulillah sejak ada jembatan ploso baru mobilitas warga dari tembelang ke ploso lancar sekali tidak macet lagi, terima kasih pemkab jombang mantap apik tenan!',
                'days_ago' => 1,
            ],
            [
                'author' => 'Parno Subekti',
                'title' => 'Keluhan Debu Pabrik di Kawasan Industri Kabuh',
                'content' => 'Warga desa di Kecamatan Kabuh mengeluhkan debu truk proyek kawasan industri dan jalan tambal sulam yang rusak lagi, DLH Jombang mohon cek amdal pabrik.',
                'days_ago' => 2,
            ],

            // --- KAWASAN TIMUR: BANDAR KEDUNGMULYO & MOJOAGUNG (Rawan Banjir) ---
            [
                'author' => 'Kusnan (Relawan Warga)',
                'title' => 'Tanggul Afvour Besuk Kali Konto Rembes',
                'content' => 'Hujan lebat debit air Kali Konto naik, warga Kecamatan Bandar Kedungmulyo was-was tanggul afvour besuk rembes rawan jebol banjir lagi. BPBD Jombang dan PUPR tolong kirim bronjong darurat!',
                'days_ago' => 0,
            ],
            [
                'author' => 'Imron Rosyadi',
                'title' => 'Normalisasi Sungai Kali Gunting Mojoagung',
                'content' => 'Pengerukan sedimentasi dan normalisasi sungai Kali Gunting di Kecamatan Mojoagung mulai berjalan baik, semoga musim hujan kali ini tidak langganan banjir lagi suwun pemkab.',
                'days_ago' => 1,
            ],

            // --- KAWASAN KOTA SANTRI & PESANTREN (Diwek / Tebuireng, Peterongan, Jombang Kota) ---
            [
                'author' => 'Gus Wahid',
                'title' => 'Haul Masyayikh Tebuireng Berjalan Tertib',
                'content' => 'Rangkaian peringatan Haul Gus Dur di Pesantren Tebuireng Kecamatan Diwek berlangsung khidmat dan tertib. Rekayasa lalin Dishub Jombang sangat membantu kenyamanan peziarah.',
                'days_ago' => 0,
            ],
            [
                'author' => 'Santri Tambakberas',
                'title' => 'Apresiasi Beasiswa Santri Berprestasi Jombang',
                'content' => 'Alhamdulillah program beasiswa santri berprestasi dari Bagian Kesra Pemkab Jombang sangat membantu kawan-kawan pondok pesantren di Tambakberas dan Denanyar melanjutkan kuliah.',
                'days_ago' => 2,
            ],

            // --- KESEHATAN: RSUD JOMBANG & RSUD PLOSO ---
            [
                'author' => 'Endang Sulistyowati',
                'title' => 'Pelayanan IGD RSUD Jombang Cepat Tanggap',
                'content' => 'Pelayanan di IGD RSUD Jombang sekarang sangat ramah dokter dan perawat gercep menangani pasien darurat tanpa ribet mantap!',
                'days_ago' => 0,
            ],
            [
                'author' => 'Miftahul Huda',
                'title' => 'Antrean Farmasi Obat RSUD Ploso Masih Lama',
                'content' => 'Antrean loket obat di RSUD Ploso lambat pol, ruang tunggu penuh nakes kurang cepat sudah 3 jam belum dipanggil ngelu pusing bgt!',
                'days_ago' => 1,
            ],

            // --- KAWASAN SELATAN & WONOSALAM (Agribisnis, Durian, Pupuk) ---
            [
                'author' => 'Sugeng Petani Wonosalam',
                'title' => 'Persiapan Kenduren Durian Wonosalam Sukses',
                'content' => 'Persiapan festival tahunan Kenduren Durian Wonosalam di Kecamatan Wonosalam sangat meriah, akses jalan menuju wisata pegunungan sudah mulus dan ramai wisatawan.',
                'days_ago' => 1,
            ],
            [
                'author' => 'Kelompok Tani Peterongan',
                'title' => 'Distribusi Pupuk Subsidi Tebu Lancar',
                'content' => 'Penyaluran alokasi pupuk subsidi bagi petani tebu di Kecamatan Peterongan dan Kesamben berjalan transparan dan tepat sasaran terima kasih Dinas Pertanian Jombang.',
                'days_ago' => 3,
            ],

            // --- ADMINDUK DISDUKCAPIL JOMBANG ---
            [
                'author' => 'Bambang Eko',
                'title' => 'Layanan Mobil Keliling Dukcapil di Plandaan',
                'content' => 'Layanan jemput bola mobil keliling Disdukcapil Jombang di pelosok Kecamatan Plandaan sangat membantu lansia rekam KTP dan cetak IKD digital cepat tanpa antre ke kota.',
                'days_ago' => 0,
            ],
            [
                'author' => 'Siti Aisyah',
                'title' => 'Server Dukcapil Sering Offline',
                'content' => 'Sudah datang pagi-pagi ke kantor Disdukcapil Jombang mau urus surat pindah dan KK tapi kata petugas server offline ra cetho bgt pelayanan bikin kecewa!',
                'days_ago' => 1,
            ],

            // --- PASAR & PKL ALUN-ALUN JOMBANG ---
            [
                'author' => 'Cak Mat Pedagang',
                'title' => 'Penataan PKL Alun-Alun Jombang Tertib',
                'content' => 'Penataan zona kuliner PKL di Alun-Alun Jombang oleh DLH dan Disperindag membuat kawasan makin bersih rapi dan pengunjung betah jajan.',
                'days_ago' => 2,
            ],
            [
                'author' => 'Paguyuban Pedagang PCN',
                'title' => 'Revitalisasi Pasar Citra Niaga (PCN) Jombang',
                'content' => 'Pedagang Pasar Citra Niaga (PCN) Jombang berharap atap blok B yang bocor segera diperbaiki oleh Disperindag agar pembeli nyaman saat hujan.',
                'days_ago' => 3,
            ],

            // --- PERHUBUNGAN: PALANG KA & PJU ---
            [
                'author' => 'Dicky Driver',
                'title' => 'PJU Mati di Ruas Jalan Peterongan-Sumobito',
                'content' => 'Lampu PJU mati berhari-hari di jalur Peterongan arah Sumobito peteng dedet gelap rawan begal dan kecelakaan, Dishub Jombang tolong segera ganti bohlam!',
                'days_ago' => 0,
            ],

            // --- BOT / BUZZER SPAMS KASUS JOMBANG (Copy-Paste Campaign) ---
            [
                'author' => 'akun_bayaran_01',
                'title' => 'Kritik Pemkab Jombang',
                'content' => 'Birokrasi pemkab jombang tidak becus pimpinan harus diganti sekarang juga kota santri makin tertinggal bobrok!',
                'days_ago' => 0,
                'is_spam' => true,
            ],
            [
                'author' => 'akun_bayaran_02',
                'title' => 'Kritik Pemkab Jombang',
                'content' => 'Birokrasi pemkab jombang tidak becus pimpinan harus diganti sekarang juga kota santri makin tertinggal bobrok!',
                'days_ago' => 0,
                'is_spam' => true,
            ],
            [
                'author' => 'akun_bayaran_03',
                'title' => 'Kritik Pemkab Jombang',
                'content' => 'Birokrasi pemkab jombang tidak becus pimpinan harus diganti sekarang juga kota santri makin tertinggal bobrok!',
                'days_ago' => 0,
                'is_spam' => true,
            ],
            [
                'author' => 'akun_bayaran_04',
                'title' => 'Kritik Pemkab Jombang',
                'content' => 'Birokrasi pemkab jombang tidak becus pimpinan harus diganti sekarang juga kota santri makin tertinggal bobrok!',
                'days_ago' => 0,
                'is_spam' => true,
            ],
        ];

        $analysisService = app(AnalysisService::class);

        foreach ($jombangPosts as $idx => $item) {
            $contentHash = hash('sha256', $item['content']);
            $raw = RawContent::create([
                'source_endpoint_id' => $defaultEndpoint->id,
                'external_id' => 'jombang_post_' . ($idx + 1),
                'author' => $item['author'],
                'title' => $item['title'],
                'content_text' => $item['content'],
                'clean_text' => strtolower($item['content']),
                'url' => 'https://radarjombang.jawapos.com/berita/' . ($idx + 1),
                'content_hash' => $contentHash,
                'published_at' => Carbon::now()->subDays($item['days_ago'])->subHours(rand(1, 10)),
                'is_analyzed' => false,
            ]);

            // Eksekusi Pipeline NLP, Emosi, ABSA per OPD Jombang, Geocoding Kecamatan
            $analysisService->analyzeSingle($raw);
        }
    }
}

