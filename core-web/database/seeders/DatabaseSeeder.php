<?php

namespace Database\Seeders;

use App\Models\Source;
use App\Models\SourceEndpoint;
use App\Models\TopicOrAgency;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Admin User
        User::updateOrCreate(
            ['email' => 'admin@sismantik.local'],
            [
                'name' => 'Administrator SISMANTIK',
                'password' => Hash::make('admin123'),
            ]
        );

        // 2. Default OPD / Topics
        $agencies = [
            [
                'name' => 'Dinas Pekerjaan Umum & Penataan Ruang (PUPR)',
                'code' => 'dinas_pupr',
                'category' => 'Infrastruktur',
                'keywords' => ['jalan rusak', 'aspal', 'jembatan', 'lubang', 'drainase', 'banjir', 'irigasi', 'trotoar'],
                'aliases' => ['PUPR', 'Bina Marga', 'Cipta Karya'],
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Kesehatan (Dinkes)',
                'code' => 'dinkes',
                'category' => 'Kesehatan',
                'keywords' => ['puskesmas', 'rsud', 'antrean obat', 'bpjs', 'dokter', 'perawat', 'nakes', 'posyandu', 'stunting'],
                'aliases' => ['Dinkes', 'RSUD'],
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Kependudukan & Pencatatan Sipil (Disdukcapil)',
                'code' => 'disdukcapil',
                'category' => 'Pelayanan Publik',
                'keywords' => ['ktp', 'kartu keluarga', 'akta kelahiran', 'surat pindah', 'antrean dukcapil', 'identitas kependudukan digital'],
                'aliases' => ['Dukcapil', 'Capil'],
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Pendidikan (Disdik)',
                'code' => 'disdik',
                'category' => 'Pendidikan',
                'keywords' => ['sekolah', 'gedung sd rusak', 'ppdb', 'guru honorer', 'beasiswa', 'zonasi', 'buku pelajaran'],
                'aliases' => ['Disdik', 'Diknas'],
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Perhubungan (Dishub)',
                'code' => 'dishub',
                'category' => 'Transportasi',
                'keywords' => ['lampu merah mati', 'kemacetan', 'parkir liar', 'rambu lalu lintas', 'terminal', 'uji kir'],
                'aliases' => ['Dishub', 'LLAJ'],
                'is_active' => true,
            ],
            [
                'name' => 'Dinas Sosial (Dinsos)',
                'code' => 'dinsos',
                'category' => 'Sosial',
                'keywords' => ['bansos', 'pkh', 'bantuan beras', 'kemiskinan', 'orang terlantar', 'dtks'],
                'aliases' => ['Dinsos'],
                'is_active' => true,
            ],
        ];

        foreach ($agencies as $agency) {
            TopicOrAgency::updateOrCreate(['code' => $agency['code']], $agency);
        }

        // 3. Default Sources & Endpoints
        $source1 = Source::updateOrCreate(
            ['code' => 'antara_news'],
            [
                'name' => 'Antara News (Portal Berita Nasional/Regional)',
                'platform_type' => 'web_rss',
                'base_url' => 'https://www.antaranews.com',
                'description' => 'Feed sindikasi berita resmi kantor berita Antara',
                'is_active' => true,
                'sync_interval_minutes' => 60,
            ]
        );

        SourceEndpoint::updateOrCreate(
            ['source_id' => $source1->id, 'name' => 'Feed Utama Terkini'],
            [
                'endpoint_url' => 'https://www.antaranews.com/rss/terkini.xml',
                'parser_type' => 'rss',
                'default_max_items' => 30,
                'is_active' => true,
            ]
        );

        $source2 = Source::updateOrCreate(
            ['code' => 'detik_daerah'],
            [
                'name' => 'Detik News Regional',
                'platform_type' => 'web_rss',
                'base_url' => 'https://news.detik.com',
                'description' => 'Pemberitaan isu daerah dan kebijakan publik dari Detik',
                'is_active' => true,
                'sync_interval_minutes' => 60,
            ]
        );

        SourceEndpoint::updateOrCreate(
            ['source_id' => $source2->id, 'name' => 'Feed Detik News'],
            [
                'endpoint_url' => 'https://news.detik.com/rss',
                'parser_type' => 'rss',
                'default_max_items' => 30,
                'is_active' => true,
            ]
        );

        $source3 = Source::updateOrCreate(
            ['code' => 'humas_pemda_ig'],
            [
                'name' => 'Instagram Publik Humas Pemda',
                'platform_type' => 'instagram',
                'base_url' => 'https://www.instagram.com',
                'description' => 'Kanal resmi publikasi kegiatan dan komentar warga di Instagram Pemda',
                'is_active' => true,
                'sync_interval_minutes' => 30,
            ]
        );

        SourceEndpoint::updateOrCreate(
            ['source_id' => $source3->id, 'name' => 'Kolom Komentar Postingan Terkini'],
            [
                'endpoint_url' => 'https://www.instagram.com/humaspemda/',
                'parser_type' => 'social_profile',
                'default_max_items' => 50,
                'is_active' => true,
            ]
        );

        $source4 = Source::updateOrCreate(
            ['code' => 'lapor_warga_fb'],
            [
                'name' => 'Facebook Forum Warga Lokal',
                'platform_type' => 'facebook',
                'base_url' => 'https://www.facebook.com',
                'description' => 'Grup dan halaman Facebook penyampaian aspirasi dan keluhan warga',
                'is_active' => false,
                'sync_interval_minutes' => 120,
            ]
        );

        SourceEndpoint::updateOrCreate(
            ['source_id' => $source4->id, 'name' => 'Feed Unggahan & Komentar Grup'],
            [
                'endpoint_url' => 'https://www.facebook.com/groups/laporwargalokal/',
                'parser_type' => 'social_profile',
                'default_max_items' => 40,
                'is_active' => false,
            ]
        );
    }
}
