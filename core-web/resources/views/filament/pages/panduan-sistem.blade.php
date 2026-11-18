<x-filament-panels::page>
    <div x-data="{ activeTab: 'alur' }" class="space-y-6">
        
        {{-- Navigation Tabs Guide --}}
        <div class="flex items-center gap-2 border-b border-gray-200 dark:border-slate-800 pb-3 overflow-x-auto">
            <button @click="activeTab = 'alur'" 
                    :class="activeTab === 'alur' ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold shadow-lg shadow-emerald-500/20' : 'text-gray-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'"
                    class="px-4 py-2.5 rounded-xl text-xs font-semibold transition shrink-0 flex items-center gap-2">
                <x-filament::icon icon="heroicon-m-cpu-chip" class="h-4 w-4" />
                1. Alur & Arsitektur Sistem
            </button>
            <button @click="activeTab = 'sync'" 
                    :class="activeTab === 'sync' ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold shadow-lg shadow-emerald-500/20' : 'text-gray-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'"
                    class="px-4 py-2.5 rounded-xl text-xs font-semibold transition shrink-0 flex items-center gap-2">
                <x-filament::icon icon="heroicon-m-arrow-path" class="h-4 w-4" />
                2. Prosedur Penarikan Data Baru (Harian)
            </button>
            <button @click="activeTab = 'replace'" 
                    :class="activeTab === 'replace' ? 'bg-gradient-to-r from-rose-500 to-red-600 text-white font-bold shadow-lg shadow-rose-500/20' : 'text-gray-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'"
                    class="px-4 py-2.5 rounded-xl text-xs font-semibold transition shrink-0 flex items-center gap-2">
                <x-filament::icon icon="heroicon-m-trash" class="h-4 w-4" />
                3. Prosedur "Replace Data Total"
            </button>
            <button @click="activeTab = 'analisis'" 
                    :class="activeTab === 'analisis' ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold shadow-lg shadow-emerald-500/20' : 'text-gray-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800'"
                    class="px-4 py-2.5 rounded-xl text-xs font-semibold transition shrink-0 flex items-center gap-2">
                <x-filament::icon icon="heroicon-m-chart-bar" class="h-4 w-4" />
                4. Panduan Interpretasi & Tindak Lanjut Humas
            </button>
        </div>

        {{-- TAB 1: ALUR KERJA SISTEM --}}
        <div x-show="activeTab === 'alur'" class="space-y-6">
            <x-filament::section>
                <x-slot name="heading">
                    <span class="text-base font-bold text-gray-900 dark:text-white">Alur Pemrosesan Data & Arsitektur Zero-Paid API</span>
                </x-slot>

                <p class="text-sm text-gray-600 dark:text-slate-300 leading-relaxed mb-4">
                    SISMANTIK adalah sistem intelijen media mandiri yang beroperasi 100% pada CPU lokal (12 threads) tanpa biaya langganan API eksternal. Seluruh data melewati 4 tahapan pipeline otomatis:
                </p>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <div class="h-8 w-8 rounded-lg bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 flex items-center justify-center font-black mb-3">1</div>
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">Ingestion Multi-Sumber</h4>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 leading-relaxed">
                            Menarik berita dari Tier 1 (Antara News), Tier 2 (Radar Jombang, Google News, Beritajatim), dan Tier 4 (Lapor Warga ILKJ & IG Pemkab).
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <div class="h-8 w-8 rounded-lg bg-sky-100 text-sky-800 dark:bg-sky-950 dark:text-sky-300 flex items-center justify-center font-black mb-3">2</div>
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">Normalisasi & Dialek</h4>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 leading-relaxed">
                            Membersihkan noise, konversi emoji ke makna teks, dan menormalkan slang Jawa Timur/Jombangan (<em>ajur, ra cetho, suwun, dalan</em>) via kamus lokal.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <div class="h-8 w-8 rounded-lg bg-purple-100 text-purple-800 dark:bg-purple-950 dark:text-purple-300 flex items-center justify-center font-black mb-3">3</div>
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">NLP & Ekstraksi Jombang</h4>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 leading-relaxed">
                            Model IndoBERT mengkalkulasi skor sentimen, 6-aksis emosi, 5 dimensi framing, ABSA ke 10 OPD & 4 program PSN, serta pemetaan 21 kecamatan.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <div class="h-8 w-8 rounded-lg bg-amber-100 text-amber-800 dark:bg-amber-950 dark:text-amber-300 flex items-center justify-center font-black mb-3">4</div>
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">Audit Bot & Dashboard</h4>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 leading-relaxed">
                            Deteksi duplikasi kampanye copy-paste buzzer (CIB), Bayesian smoothing NSS, dan penyajian visual pada Dashboard Eksekutif.
                        </p>
                    </div>
                </div>
            </x-filament::section>
        </div>

        {{-- TAB 2: PENARIKAN DATA BARU (HARIAN) --}}
        <div x-show="activeTab === 'sync'" class="space-y-6">
            <x-filament::section>
                <x-slot name="heading">
                    <span class="text-base font-bold text-gray-900 dark:text-white">SOP Penarikan Berita Baru (Incremental Sync)</span>
                </x-slot>

                <p class="text-sm text-gray-600 dark:text-slate-300 leading-relaxed mb-4">
                    Penarikan incremental dilakukan secara berkala (misal: setiap pagi pk 07.00 WIB atau saat ada isu hangat) untuk mengambil berita terkini <strong>tanpa menghapus data lama</strong>.
                </p>

                <div class="space-y-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 dark:bg-emerald-950 text-xs font-bold">Metode 1</span>
                            Melalui Tombol di Dashboard Eksekutif
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                            Cukup buka halaman <strong>Dashboard Eksekutif</strong>, lalu klik tombol biru <strong>"Tarik Berita Terbaru"</strong> di pojok kanan atas. Sistem akan menjalankan background task untuk mengambil 20-30 berita terkini dan langsung menganalisisnya.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-slate-200 text-slate-800 dark:bg-slate-800 dark:text-slate-200 text-xs font-bold">Metode 2</span>
                            Melalui Baris Perintah Terminal (CLI)
                        </h4>
                        <div class="mt-2 p-3 rounded-lg bg-slate-950 text-emerald-400 font-mono text-xs overflow-x-auto">
                            cd /home/digimon/Documents/KOMINFO/PROJEK/HANTU/core-web<br/>
                            php artisan sismantik:sync --max=30
                        </div>
                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-2">
                            Anda juga dapat menargetkan sumber tertentu, contoh: <code>php artisan sismantik:sync --source=radar_jombang --max=15</code>.
                        </p>
                    </div>
                </div>
            </x-filament::section>
        </div>

        {{-- TAB 3: REPLACE DATA TOTAL --}}
        <div x-show="activeTab === 'replace'" class="space-y-6">
            <x-filament::section>
                <x-slot name="heading">
                    <div class="flex items-center gap-2">
                        <x-filament::icon icon="heroicon-m-exclamation-triangle" class="h-5 w-5 text-rose-500" />
                        <span class="text-base font-bold text-rose-600 dark:text-rose-400">Prosedur "Replace Data Terbaru Secara Total"</span>
                    </div>
                </x-slot>

                <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/30 border border-rose-200 dark:border-rose-900/50 mb-4">
                    <p class="text-xs text-rose-800 dark:text-rose-300 leading-relaxed font-medium">
                        <strong>Kapan opsi ini digunakan?</strong> Opsi ini digunakan saat Anda ingin mengosongkan seluruh histori percakapan lama (misal: saat audit pergantian tahun anggaran, penyetelan ulang kata kunci instansi, atau kalibrasi algoritma baru) dan menarik ulang data segar secara bersih dari sumber media.
                    </p>
                </div>

                <div class="space-y-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-rose-100 text-rose-800 dark:bg-rose-950 text-xs font-bold">Opsi A</span>
                            Eksekusi Langsung dari Panel Admin
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1.5 leading-relaxed">
                            Klik tombol merah <strong>"Replace Data Total"</strong> di pojok kanan atas Dashboard atau di bagian atas halaman ini. Sistem akan meminta konfirmasi keamanan sebelum mengosongkan dan menarik ulang data.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded bg-slate-200 text-slate-800 dark:bg-slate-800 dark:text-slate-200 text-xs font-bold">Opsi B</span>
                            Eksekusi Bersih via Terminal (CLI)
                        </h4>
                        <div class="mt-2 p-3 rounded-lg bg-slate-950 text-emerald-400 font-mono text-xs overflow-x-auto">
                            cd /home/digimon/Documents/KOMINFO/PROJEK/HANTU/core-web<br/>
                            php artisan sismantik:replace-total --months=12 --max-per-month=1000 --confirm=yes
                        </div>
                        <p class="text-xs text-gray-400 dark:text-slate-500 mt-2">
                            Perintah ini akan membersihkan tabel <code>raw_contents</code>, <code>sentiment_results</code>, <code>aspect_sentiments</code>, dan <code>geo_extractions</code> lalu menarik ulang 12 bulan terakhir.
                        </p>
                    </div>
                </div>
            </x-filament::section>
        </div>

        {{-- TAB 4: INTERPRETASI & TINDAK LANJUT HUMAS --}}
        <div x-show="activeTab === 'analisis'" class="space-y-6">
            <x-filament::section>
                <x-slot name="heading">
                    <span class="text-base font-bold text-gray-900 dark:text-white">Panduan Analisis Metrik & Respon Humas</span>
                </x-slot>

                <div class="space-y-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">1. Net Sentiment Score (NSS) & Bayesian Smoothing</h4>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 leading-relaxed">
                            NSS mentah dihitung dari <code>(%Positif - %Negatif)</code>. Algoritma kami menerapkan <strong>Bayesian Smoothing ($\alpha=5, \beta=5$)</strong> agar jika ada dinas yang hanya memiliki sedikit berita (misal hanya 1 berita negatif), skornya tidak langsung anjlok ekstrem ke -100%, melainkan dihaluskan mendekati prior realistis.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">2. Fitur Click-to-Drilldown (Membaca Bukti Berita)</h4>
                        <p class="text-xs text-gray-500 dark:text-slate-400 mt-1 leading-relaxed">
                            Pada Dashboard Eksekutif, setiap kali Anda melihat angka merah/keluhan (misal pada Program MBG, Kecamatan Ploso, atau Dinas PUPR), <strong>cukup klik nama atau tombol kartu tersebut</strong>. Sistem akan membuka jendela modal interaktif yang menampilkan daftar artikel riil, tanggal terbit, kutipan teks, serta tautan ke portal media aslinya.
                        </p>
                    </div>

                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white">3. Matriks 5 Dimensi Framing & Respon Taktis Humas</h4>
                        <ul class="text-xs text-gray-500 dark:text-slate-400 mt-2 space-y-1.5 list-disc list-inside">
                            <li><strong>Administrative Incompetence</strong>: Terkait server offline, birokrasi, atau antrean lambat. Solusi: Klarifikasi perbaikan SOP & teknis.</li>
                            <li><strong>Human Interest / Bencana</strong>: Terkait tanggul jebol Kali Konto, banjir Kali Gunting, korban kecelakaan. Solusi: Pimpinan daerah wajib meninjau lokasi dalam waktu &lt; 3 jam.</li>
                            <li><strong>Corruption & Morality</strong>: Terkait dugaan pungli atau kasus tipikor. Solusi: Komunikasi satu pintu dan fasilitasi audit internal Inspektorat.</li>
                        </ul>
                    </div>
                </div>
            </x-filament::section>
        </div>

    </div>
</x-filament-panels::page>

