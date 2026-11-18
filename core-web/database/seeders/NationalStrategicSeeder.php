<?php

namespace Database\Seeders;

use App\Models\Source;
use App\Models\SourceEndpoint;
use App\Models\TopicOrAgency;
use Illuminate\Database\Seeder;

class NationalStrategicSeeder extends Seeder
{
    public function run(): void
    {
        // 1. TAMBAHKAN 4 TOPIK STRATEGIS NASIONAL DI KABUPATEN JOMBANG
        $psnTopics = [
            [
                'name' => 'Satgas Program Makan Bergizi Gratis (MBG) Kab. Jombang',
                'code' => 'mbg_jombang',
                'category' => 'Program Strategis Nasional (Gizi & Pendidikan)',
                'scope' => 'national_strategic',
                'keywords' => [
                    'mbg jombang',
                    'makan bergizi gratis jombang',
                    'makan bergizi gratis',
                    'sppg jombang',
                    'dapur umum mbg',
                    'menu bergizi jombang',
                    'uji coba mbg jombang',
                    'bgn jombang',
                    'sarapan bergizi jombang',
                    'keracunan mbg',
                ],
                'aliases' => ['Satgas MBG Jombang', 'Badan Gizi Nasional Jombang', 'SPPG Jombang'],
                'is_active' => true,
            ],
            [
                'name' => 'Koperasi Desa (Kopdes) & Ketahanan Pangan Jombang',
                'code' => 'kopdes_jombang',
                'category' => 'Program Strategis Nasional (Ekonomi Desa)',
                'scope' => 'national_strategic',
                'keywords' => [
                    'koperasi desa jombang',
                    'kopdes jombang',
                    'koperasi merah putih jombang',
                    'lumbung pangan desa',
                    'bulog jombang',
                    'gabah jombang',
                    'serap gabah jombang',
                    'kud jombang',
                ],
                'aliases' => ['Kopdes Jombang', 'Koperasi Merah Putih Jombang', 'Bulog Jombang'],
                'is_active' => true,
            ],
            [
                'name' => 'Penegakan Hukum & Pemberantasan Korupsi Kab. Jombang',
                'code' => 'tipikor_jombang',
                'category' => 'Program Strategis Nasional (Hukum & Akuntabilitas)',
                'scope' => 'national_strategic',
                'keywords' => [
                    'korupsi jombang',
                    'kejari jombang',
                    'kejaksaan negeri jombang',
                    'kpk jombang',
                    'tipikor jombang',
                    'dana desa jombang',
                    'pungli jombang',
                    'ott jombang',
                    'saber pungli jombang',
                    'penggeledahan jombang',
                ],
                'aliases' => ['Kejari Jombang', 'KPK Jombang', 'Tipikor Kejari'],
                'is_active' => true,
            ],
            [
                'name' => 'Swasembada Pangan & Alsintan Kementan Kab. Jombang',
                'code' => 'swasembada_jombang',
                'category' => 'Program Strategis Nasional (Kedaulatan Pangan)',
                'scope' => 'national_strategic',
                'keywords' => [
                    'swasembada pangan jombang',
                    'pupuk kementan jombang',
                    'saluran irigasi bbws jombang',
                    'cetak sawah jombang',
                    'bantuan alsintan jombang',
                    'kedaulatan pangan jombang',
                ],
                'aliases' => ['Swasembada Jombang', 'Kementan Jombang'],
                'is_active' => true,
            ],
        ];

        foreach ($psnTopics as $item) {
            TopicOrAgency::updateOrCreate(
                ['code' => $item['code']],
                $item
            );
        }

        // 2. DAFTARKAN ENDPOINT PSN PADA GOOGLE NEWS AGGREGATOR
        $gnewsSource = Source::where('code', 'gnews_jombang_aggregator')->first();
        if ($gnewsSource) {
            $psnEndpoints = [
                [
                    'name' => 'PSN: Makan Bergizi Gratis (MBG) Kab. Jombang',
                    'url' => 'https://news.google.com/rss/search?q=makan+bergizi+gratis+jombang+OR+mbg+jombang+when:7d&hl=id&gl=ID&ceid=ID:id',
                    'scope' => 'national_strategic',
                ],
                [
                    'name' => 'PSN: Koperasi Desa (Kopdes) & Ketahanan Pangan Jombang',
                    'url' => 'https://news.google.com/rss/search?q=koperasi+desa+jombang+OR+kopdes+jombang+when:7d&hl=id&gl=ID&ceid=ID:id',
                    'scope' => 'national_strategic',
                ],
                [
                    'name' => 'PSN: Penegakan Hukum & Tipikor Kejari/KPK Jombang',
                    'url' => 'https://news.google.com/rss/search?q=korupsi+jombang+OR+kejari+jombang+OR+tipikor+jombang+when:7d&hl=id&gl=ID&ceid=ID:id',
                    'scope' => 'national_strategic',
                ],
                [
                    'name' => 'PSN: Swasembada Pangan & Alsintan Kementan Jombang',
                    'url' => 'https://news.google.com/rss/search?q=swasembada+pangan+jombang+when:7d&hl=id&gl=ID&ceid=ID:id',
                    'scope' => 'national_strategic',
                ],
            ];

            foreach ($psnEndpoints as $ep) {
                SourceEndpoint::updateOrCreate(
                    [
                        'source_id' => $gnewsSource->id,
                        'name' => $ep['name'],
                    ],
                    [
                        'endpoint_url' => $ep['url'],
                        'parser_type' => 'rss',
                        'scope' => $ep['scope'],
                        'default_max_items' => 30,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}

