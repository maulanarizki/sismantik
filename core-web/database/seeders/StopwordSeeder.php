<?php

namespace Database\Seeders;

use App\Models\Stopword;
use Illuminate\Database\Seeder;

class StopwordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'daerah' => [
                'kabupaten', 'jombang', 'pemkab', 'pemda', 'pemerintah', 
                'kecamatan', 'kelurahan', 'desa', 'jatim', 'jawa', 'timur'
            ],
            'waktu' => [
                'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu',
                'januari', 'februari', 'maret', 'april', 'mei', 'juni', 'juli', 
                'agustus', 'september', 'oktober', 'november', 'desember',
                'jan', 'feb', 'mar', 'apr', 'jun', 'jul', 'agu', 'agt', 'sep', 'okt', 'nov', 'des',
                'pagi', 'siang', 'sore', 'malam', 'kemarin', 'besok', 'hari', 'bulan', 'tahun', 'pekan'
            ],
            'angka' => [
                'ribu', 'juta', 'miliar', 'triliun', 'persen', 'rupiah', 'rp', 'satuan', 'lusin', 'kodi'
            ],
            'sapaan_gelar' => [
                'bapak', 'ibu', 'pak', 'bu', 'mas', 'mbak', 'kak', 'saudara', 'saudari', 
                'prof', 'drs', 'dra', 'dr', 'hj', 'haji', 'kyai', 'gus', 'ning', 'ustadz', 'kh'
            ],
            'aksi_umum' => [
                'gelar', 'lakukan', 'melakukan', 'laksanakan', 'adakan', 'hadiri', 'hadir', 'mulai',
                'menggelar', 'melaksanakan', 'mengadakan', 'menghadiri', 'dilakukan', 'dilaksanakan', 'diadakan'
            ],
            'umum' => [
                'yang', 'di', 'ke', 'dari', 'pada', 'ini', 'itu', 'untuk', 'dengan', 'dan', 'atau',
                'adalah', 'akan', 'bisa', 'ada', 'sudah', 'tidak', 'oleh', 'juga', 'dalam', 'lebih',
                'seperti', 'tersebut', 'para', 'jika', 'tapi', 'namun', 'serta', 'karena', 'sebagai',
                'bahwa', 'secara', 'harus', 'hanya', 'saat', 'dapat', 'oleh', 'antara', 'bahkan',
                'lain', 'ia', 'dia', 'mereka', 'kita', 'kami', 'saya', 'anda', 'hingga', 'sampai',
                'setelah', 'sebelum', 'kembali', 'sedang', 'sangat', 'terkait', 'tentang', 'atas',
                'terhadap', 'menurut', 'bagi', 'terus', 'selalu', 'lagi', 'masih', 'pun', 'begitu',
                'sehingga', 'agar', 'supaya', 'ketika', 'sementara', 'maupun', 'bila', 'walau', 'meski',
                'radar', 'memorandum', 'kabar', 'berita', 'info', 'portal', 'harian', 'surat', 'baca',
                'foto', 'caption', 'redaksi', 'editor', 'reporter', 'banget', 'jadi', 'hal', 'banyak'
            ]
        ];

        $inserted = 0;
        foreach ($data as $category => $words) {
            foreach ($words as $word) {
                $word = trim(strtolower($word));
                if (empty($word)) continue;
                
                Stopword::updateOrCreate(
                    ['word' => $word],
                    ['category' => $category, 'is_active' => true]
                );
                $inserted++;
            }
        }

        $this->command->info("StopwordSeeder: {$inserted} kata berhasil disinkronkan.");
    }
}
