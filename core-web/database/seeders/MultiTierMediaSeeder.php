<?php

namespace Database\Seeders;

use App\Models\Source;
use App\Models\SourceEndpoint;
use Illuminate\Database\Seeder;

class MultiTierMediaSeeder extends Seeder
{
    public function run(): void
    {
        $sources = [
            // =========================================================================
            // TIER 1: MEDIA NASIONAL MAINSTREAM & KANTOR BERITA
            // =========================================================================
            [
                'name' => 'Antara News (Kantor Berita Negara)',
                'code' => 'antara_news',
                'platform_type' => 'web_rss',
                'tier' => 'tier_1',
                'authority_score' => 1.00,
                'base_url' => 'https://www.antaranews.com',
                'description' => 'Kantor Berita Resmi Republik Indonesia (Tier 1)',
                'is_active' => true,
                'sync_interval_minutes' => 30,
                'endpoints' => [
                    ['name' => 'Feed Berita Terkini', 'url' => 'https://www.antaranews.com/rss/terkini.xml', 'parser' => 'rss', 'limit' => 30],
                    ['name' => 'Feed Politik & Hukum', 'url' => 'https://www.antaranews.com/rss/politik.xml', 'parser' => 'rss', 'limit' => 25],
                    ['name' => 'Feed Ekonomi & Bisnis', 'url' => 'https://www.antaranews.com/rss/ekonomi.xml', 'parser' => 'rss', 'limit' => 20],
                ]
            ],
            [
                'name' => 'Detikcom (Nasional)',
                'code' => 'detik_com',
                'platform_type' => 'web_rss',
                'tier' => 'tier_1',
                'authority_score' => 0.95,
                'base_url' => 'https://news.detik.com',
                'description' => 'Portal berita online nasional dengan pembaca terbanyak di Indonesia',
                'is_active' => true,
                'sync_interval_minutes' => 30,
                'endpoints' => [
                    ['name' => 'Detik News Terkini', 'url' => 'https://news.detik.com/rss', 'parser' => 'rss', 'limit' => 30],
                    ['name' => 'Detik Finance', 'url' => 'https://finance.detik.com/rss', 'parser' => 'rss', 'limit' => 20],
                ]
            ],
            [
                'name' => 'Kompas.com',
                'code' => 'kompas_com',
                'platform_type' => 'web_rss',
                'tier' => 'tier_1',
                'authority_score' => 0.98,
                'base_url' => 'https://www.kompas.com',
                'description' => 'Harian berita nasional arus utama terpercaya',
                'is_active' => true,
                'sync_interval_minutes' => 30,
                'endpoints' => [
                    ['name' => 'Kompas Nasional', 'url' => 'https://nasional.kompas.com/rss', 'parser' => 'rss', 'limit' => 25],
                    ['name' => 'Kompas Megapolitan', 'url' => 'https://megapolitan.kompas.com/rss', 'parser' => 'rss', 'limit' => 20],
                ]
            ],
            [
                'name' => 'Tempo.co',
                'code' => 'tempo_co',
                'platform_type' => 'web_rss',
                'tier' => 'tier_1',
                'authority_score' => 0.95,
                'base_url' => 'https://www.tempo.co',
                'description' => 'Media jurnalisme investigasi dan kebijakan publik nasional',
                'is_active' => true,
                'sync_interval_minutes' => 45,
                'endpoints' => [
                    ['name' => 'Tempo Nasional', 'url' => 'https://rss.tempo.co/nasional', 'parser' => 'rss', 'limit' => 20],
                    ['name' => 'Tempo Bisnis', 'url' => 'https://rss.tempo.co/bisnis', 'parser' => 'rss', 'limit' => 20],
                ]
            ],
            [
                'name' => 'CNN Indonesia',
                'code' => 'cnn_indonesia',
                'platform_type' => 'web_rss',
                'tier' => 'tier_1',
                'authority_score' => 0.92,
                'base_url' => 'https://www.cnnindonesia.com',
                'description' => 'Media berita nasional dengan analisis mendalam',
                'is_active' => true,
                'sync_interval_minutes' => 45,
                'endpoints' => [
                    ['name' => 'CNN Nasional', 'url' => 'https://www.cnnindonesia.com/nasional/rss', 'parser' => 'rss', 'limit' => 25],
                    ['name' => 'CNN Ekonomi', 'url' => 'https://www.cnnindonesia.com/ekonomi/rss', 'parser' => 'rss', 'limit' => 20],
                ]
            ],
            [
                'name' => 'CNBC Indonesia',
                'code' => 'cnbc_indonesia',
                'platform_type' => 'web_rss',
                'tier' => 'tier_1',
                'authority_score' => 0.90,
                'base_url' => 'https://www.cnbcindonesia.com',
                'description' => 'Portal intelijen ekonomi, pasar modal, dan kebijakan makro',
                'is_active' => true,
                'sync_interval_minutes' => 60,
                'endpoints' => [
                    ['name' => 'CNBC Market & News', 'url' => 'https://www.cnbcindonesia.com/news/rss', 'parser' => 'rss', 'limit' => 20],
                ]
            ],
            [
                'name' => 'Republika Online',
                'code' => 'republika_online',
                'platform_type' => 'web_rss',
                'tier' => 'tier_1',
                'authority_score' => 0.88,
                'base_url' => 'https://www.republika.co.id',
                'description' => 'Media berita nasional dengan fokus sosio-religius dan kebijakan',
                'is_active' => true,
                'sync_interval_minutes' => 60,
                'endpoints' => [
                    ['name' => 'Republika Nasional', 'url' => 'https://www.republika.co.id/rss/nasional', 'parser' => 'rss', 'limit' => 20],
                ]
            ],
            [
                'name' => 'Liputan6.com',
                'code' => 'liputan6_com',
                'platform_type' => 'web_rss',
                'tier' => 'tier_1',
                'authority_score' => 0.88,
                'base_url' => 'https://www.liputan6.com',
                'description' => 'Portal berita umum dan peristiwa nasional',
                'is_active' => true,
                'sync_interval_minutes' => 60,
                'endpoints' => [
                    ['name' => 'Liputan6 News', 'url' => 'https://feed.liputan6.com/rss/news', 'parser' => 'rss', 'limit' => 20],
                ]
            ],
            [
                'name' => 'Sindonews',
                'code' => 'sindonews_com',
                'platform_type' => 'web_rss',
                'tier' => 'tier_1',
                'authority_score' => 0.85,
                'base_url' => 'https://www.sindonews.com',
                'description' => 'Jaringan sindikasi media nasional MNC Group',
                'is_active' => true,
                'sync_interval_minutes' => 60,
                'endpoints' => [
                    ['name' => 'Sindo Nasional', 'url' => 'https://www.sindonews.com/feed', 'parser' => 'rss', 'limit' => 20],
                ]
            ],

            // =========================================================================
            // TIER 2: MEDIA REGIONAL & LOKAL (JATIM, JABAR, JATENG, LUAR JAWA)
            // =========================================================================
            [
                'name' => 'Google News Aggregator (Jawa Timur & Daerah)',
                'code' => 'gnews_jatim_aggregator',
                'platform_type' => 'web_rss',
                'tier' => 'tier_2',
                'authority_score' => 0.85,
                'base_url' => 'https://news.google.com',
                'description' => 'Aggregator otomatis puluhan media lokal seputar kebijakan Jawa Timur',
                'is_active' => true,
                'sync_interval_minutes' => 30,
                'endpoints' => [
                    ['name' => 'Isu Pemda Jatim', 'url' => 'https://news.google.com/rss/search?q=pemda+jawa+timur+when:7d&hl=id&gl=ID&ceid=ID:id', 'parser' => 'rss', 'limit' => 35],
                    ['name' => 'Isu Jalan Rusak & Infrastruktur Jatim', 'url' => 'https://news.google.com/rss/search?q=jalan+rusak+jawa+timur+when:7d&hl=id&gl=ID&ceid=ID:id', 'parser' => 'rss', 'limit' => 30],
                    ['name' => 'Isu Layanan RSUD & Kesehatan Jatim', 'url' => 'https://news.google.com/rss/search?q=rsud+puskesmas+jawa+timur+when:7d&hl=id&gl=ID&ceid=ID:id', 'parser' => 'rss', 'limit' => 25],
                ]
            ],
            [
                'name' => 'Suara Surabaya (Portal Warga & Berita Kota)',
                'code' => 'suara_surabaya',
                'platform_type' => 'web_rss',
                'tier' => 'tier_2',
                'authority_score' => 0.90,
                'base_url' => 'https://www.suarasurabaya.net',
                'description' => 'Pionir jurnalisme warga dan lalu lintas terbesar di Jawa Timur',
                'is_active' => true,
                'sync_interval_minutes' => 30,
                'endpoints' => [
                    ['name' => 'Keluhan & Laporan Warga SS', 'url' => 'https://news.google.com/rss/search?q=site:suarasurabaya.net+when:7d&hl=id&gl=ID&ceid=ID:id', 'parser' => 'rss', 'limit' => 30],
                ]
            ],
            [
                'name' => 'Beritajatim.com',
                'code' => 'beritajatim_com',
                'platform_type' => 'web_rss',
                'tier' => 'tier_2',
                'authority_score' => 0.85,
                'base_url' => 'https://beritajatim.com',
                'description' => 'Portal berita lokal independen seputar 38 kabupaten/kota di Jawa Timur',
                'is_active' => true,
                'sync_interval_minutes' => 45,
                'endpoints' => [
                    ['name' => 'Beritajatim Terkini', 'url' => 'https://beritajatim.com/feed/', 'parser' => 'rss', 'limit' => 25],
                ]
            ],
            [
                'name' => 'Tribun Jatim Network',
                'code' => 'tribun_jatim',
                'platform_type' => 'web_rss',
                'tier' => 'tier_2',
                'authority_score' => 0.80,
                'base_url' => 'https://jatim.tribunnews.com',
                'description' => 'Jaringan berita hyperlocal Jawa Timur dengan jangkauan viralitas luas',
                'is_active' => true,
                'sync_interval_minutes' => 45,
                'endpoints' => [
                    ['name' => 'Tribun Jatim News', 'url' => 'https://news.google.com/rss/search?q=site:jatim.tribunnews.com+when:7d&hl=id&gl=ID&ceid=ID:id', 'parser' => 'rss', 'limit' => 30],
                ]
            ],
            [
                'name' => 'Jawa Pos Radar Surabaya / Regional',
                'code' => 'jawapos_radar',
                'platform_type' => 'web_rss',
                'tier' => 'tier_2',
                'authority_score' => 0.88,
                'base_url' => 'https://radarsurabaya.jawapos.com',
                'description' => 'Grup surat kabar harian daerah Jawa Pos',
                'is_active' => true,
                'sync_interval_minutes' => 60,
                'endpoints' => [
                    ['name' => 'Radar Surabaya Terkini', 'url' => 'https://news.google.com/rss/search?q=site:radarsurabaya.jawapos.com+when:7d&hl=id&gl=ID&ceid=ID:id', 'parser' => 'rss', 'limit' => 25],
                ]
            ],
            [
                'name' => 'Solopos (Jawa Tengah)',
                'code' => 'solopos_jateng',
                'platform_type' => 'web_rss',
                'tier' => 'tier_2',
                'authority_score' => 0.82,
                'base_url' => 'https://www.solopos.com',
                'description' => 'Harian regional Jawa Tengah dan Soloraya terakreditasi',
                'is_active' => true,
                'sync_interval_minutes' => 60,
                'endpoints' => [
                    ['name' => 'Solopos Terkini', 'url' => 'https://news.google.com/rss/search?q=site:solopos.com+when:7d&hl=id&gl=ID&ceid=ID:id', 'parser' => 'rss', 'limit' => 20],
                ]
            ],
            [
                'name' => 'Pikiran Rakyat (Jawa Barat)',
                'code' => 'pikiran_rakyat_jabar',
                'platform_type' => 'web_rss',
                'tier' => 'tier_2',
                'authority_score' => 0.85,
                'base_url' => 'https://www.pikiran-rakyat.com',
                'description' => 'Jaringan media regional Jawa Barat terbesar',
                'is_active' => true,
                'sync_interval_minutes' => 60,
                'endpoints' => [
                    ['name' => 'Pikiran Rakyat Daerah', 'url' => 'https://news.google.com/rss/search?q=site:pikiran-rakyat.com+when:7d&hl=id&gl=ID&ceid=ID:id', 'parser' => 'rss', 'limit' => 20],
                ]
            ],

            // =========================================================================
            // TIER 3: MEDIA INTERNASIONAL & MULTILATERAL (PERSEPSI LUAR NEGERI)
            // =========================================================================
            [
                'name' => 'BBC News Indonesia (Internasional)',
                'code' => 'bbc_indonesia',
                'platform_type' => 'web_rss',
                'tier' => 'tier_3',
                'authority_score' => 0.95,
                'base_url' => 'https://www.bbc.com/indonesia',
                'description' => 'Layanan siaran internasional British Broadcasting Corporation (BBC) bahasa Indonesia',
                'is_active' => true,
                'sync_interval_minutes' => 60,
                'endpoints' => [
                    ['name' => 'BBC Indonesia Feed', 'url' => 'https://feeds.bbci.co.uk/indonesia/rss.xml', 'parser' => 'rss', 'limit' => 25],
                ]
            ],
            [
                'name' => 'Reuters Asia & World (Internasional)',
                'code' => 'reuters_asia',
                'platform_type' => 'web_rss',
                'tier' => 'tier_3',
                'authority_score' => 0.98,
                'base_url' => 'https://www.reuters.com',
                'description' => 'Kantor berita internasional terkemuka untuk pemantauan isu investasi & geopolitik',
                'is_active' => true,
                'sync_interval_minutes' => 60,
                'endpoints' => [
                    ['name' => 'Reuters Indonesia Monitor', 'url' => 'https://news.google.com/rss/search?q=site:reuters.com+indonesia+when:7d&hl=en&gl=US&ceid=US:en', 'parser' => 'rss', 'limit' => 20],
                ]
            ],
            [
                'name' => 'Bloomberg Asia Pacific',
                'code' => 'bloomberg_asia',
                'platform_type' => 'web_rss',
                'tier' => 'tier_3',
                'authority_score' => 0.95,
                'base_url' => 'https://www.bloomberg.com',
                'description' => 'Portal ekonomi & keuangan global untuk memantau peringkat investasi daerah',
                'is_active' => true,
                'sync_interval_minutes' => 90,
                'endpoints' => [
                    ['name' => 'Bloomberg Indonesia Tracker', 'url' => 'https://news.google.com/rss/search?q=site:bloomberg.com+indonesia+when:7d&hl=en&gl=US&ceid=US:en', 'parser' => 'rss', 'limit' => 15],
                ]
            ],
            [
                'name' => 'Nikkei Asia (Tokyo / Jepang)',
                'code' => 'nikkei_asia',
                'platform_type' => 'web_rss',
                'tier' => 'tier_3',
                'authority_score' => 0.90,
                'base_url' => 'https://asia.nikkei.com',
                'description' => 'Media bisnis terkemuka Jepang memantau proyek infrastruktur & investasi industri',
                'is_active' => true,
                'sync_interval_minutes' => 90,
                'endpoints' => [
                    ['name' => 'Nikkei Indonesia Coverage', 'url' => 'https://news.google.com/rss/search?q=site:asia.nikkei.com+indonesia+when:7d&hl=en&gl=US&ceid=US:en', 'parser' => 'rss', 'limit' => 15],
                ]
            ],

            // =========================================================================
            // TIER 4: MEDIA SOSIAL PUBLIK & FORUM WARGA
            // =========================================================================
            [
                'name' => 'Instagram Publik Humas & Info Warga',
                'code' => 'ig_warga_publik',
                'platform_type' => 'instagram',
                'tier' => 'tier_4',
                'authority_score' => 0.70,
                'base_url' => 'https://www.instagram.com',
                'description' => 'Kanal komentar publik Instagram resmi dan akun agregator kabar warga',
                'is_active' => true,
                'sync_interval_minutes' => 30,
                'endpoints' => [
                    ['name' => 'Komentar Akun Resmi Humas', 'url' => 'https://www.instagram.com/humaspemda/', 'parser' => 'social_profile', 'limit' => 40],
                    ['name' => 'Komentar Info Warga Terkini', 'url' => 'https://www.instagram.com/infowargaterkini/', 'parser' => 'social_profile', 'limit' => 40],
                ]
            ],
            [
                'name' => 'Facebook Forum Aspirasi & Keluhan Warga',
                'code' => 'fb_forum_warga',
                'platform_type' => 'facebook',
                'tier' => 'tier_4',
                'authority_score' => 0.65,
                'base_url' => 'https://www.facebook.com',
                'description' => 'Grup dan halaman publik media sosial Facebook penyampaian aspirasi warga',
                'is_active' => true,
                'sync_interval_minutes' => 60,
                'endpoints' => [
                    ['name' => 'Unggahan & Komentar Grup Lapor Warga', 'url' => 'https://www.facebook.com/groups/laporwargalokal/', 'parser' => 'social_profile', 'limit' => 50],
                ]
            ],
            [
                'name' => 'TikTok Suara Publik & Tren Isu',
                'code' => 'tiktok_suara_publik',
                'platform_type' => 'tiktok',
                'tier' => 'tier_4',
                'authority_score' => 0.60,
                'base_url' => 'https://www.tiktok.com',
                'description' => 'Pemantauan video dan komentar keluhan warga yang viral di TikTok',
                'is_active' => true,
                'sync_interval_minutes' => 60,
                'endpoints' => [
                    ['name' => 'Komentar Tagar Isu Daerah', 'url' => 'https://www.tiktok.com/tag/keluhanwarga', 'parser' => 'social_profile', 'limit' => 30],
                ]
            ],
        ];

        foreach ($sources as $srcData) {
            $endpoints = $srcData['endpoints'] ?? [];
            unset($srcData['endpoints']);

            $source = Source::updateOrCreate(
                ['code' => $srcData['code']],
                $srcData
            );

            foreach ($endpoints as $epData) {
                SourceEndpoint::updateOrCreate(
                    [
                        'source_id' => $source->id,
                        'name' => $epData['name'],
                    ],
                    [
                        'endpoint_url' => $epData['url'],
                        'parser_type' => $epData['parser'],
                        'default_max_items' => $epData['limit'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}

