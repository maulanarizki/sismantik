# PRODUCT REQUIREMENT DOCUMENT (PRD)
## Proyek: SISMANTIK PRO (Sistem Pemantauan Sentimen & Intelijen Media Publik)
**Target Pengguna:** Pemerintah Daerah (Dinas Kominfo, Bupati/Wakil Bupati, Sekretaris Daerah, dan 10 OPD Pemkab Jombang)  
**Lingkungan Operasional:** 100% Mandiri, Native Host Laptop/Server (Non-Docker), Zero Paid API Dependency  
**Basis Data:** MySQL 8.x / MariaDB 11.x (`hantu` @ 127.0.0.1:3306)  
**Status Implementasi:** **Aktif & Siap Produksi (Executive Command Center Native Blade + FastAPI)**

---

### 1. Eksekutif Ringkasan (Executive Summary)
SISMANTIK PRO adalah platform intelijen media dan opini publik mandiri (*self-hosted media intelligence platform*) yang dirancang khusus untuk Pemerintah Kabupaten Jombang. Sistem ini bertugas memantau pemberitaan media daring (lokal Jawa Timur, nasional, dan agregator Google News) serta percakapan media sosial secara *real-time*.

Sistem beroperasi **100% mandiri tanpa ketergantungan API komersial berbayar** (Rp 0,- biaya lisensi eksternal), berjalan langsung (*natively*) pada lingkungan Linux lokal tanpa container Docker. Menggabungkan backend analitik **PHP 8.4 / Laravel 11** dengan microservice **Python 3.13 / FastAPI** yang menjalankan model inferensi IndoBERT multi-aspek, VADER, dan MinHash LSH.

Antarmuka pengguna menggunakan arsitektur **Executive Command Center murni (Laravel Blade, Tailwind CSS, Alpine.js, Chart.js, dan Leaflet GIS)** yang dirancang dengan kontras tinggi (*high contrast*), responsif, bebas dari ketergantungan Filament bawaan, serta mendukung peralihan instan tema Terang (*Light*) dan Gelap (*Dark*).

---

### 2. Latar Belakang & Masalah Riil yang Dipecahkan (Problem Statement)
1. **Biaya API Berbayar & Kuota Terbatas:** Layanan komersial mematok biaya puluhan juta per bulan dengan batasan kuota *mention*. SISMANTIK memproses ribuan data tanpa kuota dan tanpa biaya API.
2. **Kedaulatan & Keamanan Data:** Seluruh data keluhan warga, data aduan OPD, dan hasil analisis sentimen tersimpan secara privat di database MySQL lokal (`hantu`).
3. **Karakteristik Teks Lokal Jombang & Jawa Timuran:** Opini publik lokal sarat akan bahasa gaul daerah (*"dalan ajur", "rusak parah", "tambal sulam", "bapuk"*). Model NLP dilengkapi kamus normalisasi slang lokal.
4. **Kebutuhan Dashboard Eksekutif Khas Pimpinan:** Menggantikan tampilan admin standar dengan antarmuka *war-room* eksekutif yang menyajikan 4 Master KPI, grafik tren 12 bulan berdasarkan tanggal terbit berita (`published_at`), dan bilik interaktif *drilldown* artikel berita secara langsung ketika komponen diklik.

---

### 3. Tolok Ukur Keberhasilan (Real KPIs & Status Terkini)
* **Zero External API Cost:** Biaya operasional lisensi API eksternal = **Rp 0,-**.
* **Kecepatan Inferensi CPU:** Pemrosesan batch NLP memanfaatkan 12 CPU Cores secara optimal ($\approx 50$ artikel per 5-10 detik).
* **Dataset Riil Aktif:** Telah memuat dan menganalisis **3.680 berita terkurasi** dalam kurun waktu 12 bulan terakhir seputar Kabupaten Jombang (2.452 Isu Lokal Pemkab Jombang & 1.228 Program Strategis Nasional).
* **Basemap GIS Bebas Watermark:** Menggunakan OpenStreetMap dan Esri World Imagery (bebas watermark CartoDB "API KEY REQUIRED").
* **Akurasi & Integritas:** Tidak ada manipulasi data; tanggal grafik dihitung murni dari tanggal terbit asli berita (`published_at`).

---

### 4. Persona Pengguna & Alur Akses (User Personas)
| Persona | Hak Akses & URL | Kebutuhan Utama |
| :--- | :--- | :--- |
| **Bupati / Wakil Bupati / Sekda** | `/dashboard` | Meninjau 4 Master KPI (Volume, NSS, Trust Index, Bot/Anomaly), tren sentimen 12 bulan, evaluasi 4 Program Strategis Nasional (PSN), Rapor 10 OPD, dan Wordcloud Sentimen. |
| **Kepala & Tim Humas Diskominfo** | `/dashboard` & `/berita` | Memantau isu viral, narasi framing publik, anatomi 6 spektrum emosi, kata kunci terpopuler dalam sentimen, klik *drilldown* berita, dan tombol Tarik Berita. |
| **Pimpinan OPD (Kadis PUPR, Dinkes, dll.)** | `/opd` & `/berita` | Melihat rapor penerimaan publik instansinya, keluhan spesifik (misal: jalan rusak, antrean RSUD), dan filter wilayah kecamatan terkait. |
| **Operator Teknis / GIS / Admin Data** | `/peta`, `/stopwords`, `/sumber`, `/sinkronisasi` | Meninjau peta geospasial 21 kecamatan, mengelola database kata henti (*stopwords*), memeriksa kesehatan 13 endpoint scraper, audit log sinkronisasi, dan panduan operasional sistem (`/panduan`). |

---

### 5. Ruang Lingkup Fitur Nyata (Current Production Features)

#### 5.1 Dashboard Eksekutif Komando (`/dashboard` & `/`)
* **4 Master KPI Cards:**
  1. *Total Berita Terpublikasi:* Total volume data berita terindeks (3.680 konten).
  2. *Net Sentiment Score (NSS):* Formula persentase $(Positif - Negatif) / Total \times 100$.
  3. *Indeks Kepuasan Warga (Trust Index):* Skala 1.0 s.d. 10.0 berdasarkan rasio penerimaan publik.
  4. *Bot / Anomali Terindikasi:* Deteksi postingan duplikat/sindikasi massal via MinHash LSH.
* **4 Pilar Monitoring Program Strategis Nasional (PSN):**
  1. *Program Makan Bergizi Gratis (MBG)* (Badan Gizi Nasional / Kodim 0814 Jombang / Dapur SPPG).
  2. *Koperasi Desa (Kopdes) & Ketahanan Pangan* (Kemenkop, Bulog, penyerapan gabah lokal).
  3. *Pemberantasan Korupsi & Penegakan Hukum Daerah* (KPK, Kejari Jombang, pengawasan dana desa).
  4. *Swasembada Pangan & Alsintan Nasional* (Kementan, BBWS Brantas, pupuk subsidi).
* **Grafik Tren Sentimen 12 Bulan Terakhir (Chart.js Area Chart):**
  * Pergerakan kurva Total Volume, Sentimen Positif, dan Sentimen Negatif per bulan berdasarkan tanggal riil terbit berita (`published_at`).
* **Pusat Analisis Kata Kunci & Wordcloud Sentimen:**
  * Tag cloud interaktif dengan 3 bilik klasifikasi: *Sentimen Positif*, *Sentimen Negatif*, dan *Isu Topik Dominan*.
  * Setiap kata ditampilkan dengan ukuran font proporsional terhadap frekuensi kemunculan (*term frequency*) serta badge jumlah sebutan.
  * Didukung filtrasi kata henti dinamis dari database untuk membuang kebisingan kata umum, nama daerah, gelar, dan angka.
* **Rapor Kinerja 10 OPD Pemkab Jombang:**
  * Peringkat instansi berdasarkan volume sebutan media & skor NSS, dilengkapi micro-progress bar proporsi positif/netral/negatif.
* **Matriks Framing Isu & Aduan Warga:**
  * 5 Klaster isu: *Infrastruktur & Layanan Publik*, *Kesehatan & Kesejahteraan*, *Pertanian & Ketahanan Pangan*, *Pendidikan & Budaya*, dan *Tata Kelola & Penegakan Hukum*.
* **Anatomi 6 Spektrum Emosi Publik:**
  * Donut chart interaktif & breakdown persentase: *Apresiasi/Senang, Harapan/Doa, Kecewa/Frustrasi, Marah/Geram, Sedih/Prihatin, Takut/Cemas*.
* **AI Executive Digest & Rekomendasi Diskominfo:**
  * Protokol PDB (*Presidential Daily Brief*) mencakup ringkasan narasi dominan, potensi kerawanan, dan rekomendasi langkah strategis humas.

#### 5.2 Pusat Komando Geospasial 21 Kecamatan (`/peta`)
* **GIS Interaktif Mandiri:** Halaman terpisah khusus peta interaktif Kabupaten Jombang.
* **21 Poligon Resmi GADM 4.1 & Normalisasi Properti:** Seluruh batas kecamatan di Kabupaten Jombang terpetakan presisi via GeoJSON lokal offline-ready menggunakan atribut `district_name` yang dinormalisasi otomatis.
* **5 Header KPI Teritorial:** Menampilkan Total Berita Teritori, Jumlah Wilayah Sangat Kondusif, Kondusif & Stabil, Perlu Atensi, dan Rawan Keluhan secara live.
* **Pewarnaan Sentimen Wilayah (Choropleth Terkalibrasi):**
  * *Sangat Kondusif* (Hijau Emerald `#10b981`, NSS $\ge +15\%$).
  * *Kondusif & Stabil* (Biru Cyan `#06b6d4`, $0\% \le \text{NSS} < 15\%$).
  * *Perlu Atensi* (Kuning Amber `#f59e0b`, $-30\% \le \text{NSS} < 0\%$).
  * *Rawan Keluhan* (Merah Kirmizi `#f43f5e`, Rasio Negatif $\ge 40\%$ atau NSS $< -30\%$).
* **Basemap Multi-Layer:** Pilihan basemap *OpenStreetMap*, *Esri Canvas*, dan *Esri Satellite* yang 100% bebas watermark.
* **Inspektor Wilayah Terpilih (HUD) & Live Article Feed:** Panel info detail statistik saat kecamatan diklik, dilengkapi rekomendasi respon, statistik persentase sentimen, dan **feed artikel berita interaktif teritorial** yang dapat dibaca dan dibuka langsung.
* **Leaderboard 21 Kecamatan:** Peringkat kecamatan berdasarkan volume berita, tingkat keluhan, dan sentimen positif.

#### 5.3 Modal Drilldown Berita Interaktif Global (`/api/drilldown-articles`)
* Mengizinkan pimpinan mengklik kartu PSN, baris OPD, matriks framing isu, irisan emosi, maupun poligon kecamatan untuk membuka daftar berita terkait secara *real-time*.
* Menampilkan judul artikel, cuplikan isi teks, badge sentimen, label emosi, nama portal media, tanggal rilis, dan tautan langsung ke artikel asli.
* Dilengkapi kolom pencarian instan (*live search*) di dalam modal.

#### 5.4 Katalog Berita & Opini (`/berita`)
* Pencarian teks bebas (judul, isi, penulis).
* Filter ruang lingkup: *Semua*, *Lokal Pemkab*, dan *Strategis Nasional*.
* Filter sentimen: *Semua*, *Positif*, *Netral*, *Negatif*.
* Filter instansi/OPD terkait.
* Paginasi 15 item/halaman dengan tautan eksternal ke sumber asli.

#### 5.5 Manajemen Sumber & Endpoint (`/sumber`)
* Daftar portal media (Radar Jombang, Kabar Jombang, Beritajatim, Detik Jatim, Antara Jatim, Google News Aggregator).
* 13 Endpoint scraper aktif dengan konfigurasi parser RSS, Trafilatura, dan Playwright.

#### 5.6 Siklus Pembaruan Data (`/sinkronisasi` & `/panduan`)
* **Tarik Berita Baru (*Incremental Sync*):** Menarik berita teranyar tanpa menghapus data lama via perintah `php artisan sismantik:sync --max=20`.
* **Replace Data Total (*Full Re-ingest*):** Mengosongkan data lama dan menarik ulang data segar 12 bulan terakhir via `php artisan sismantik:replace-total --months=12 --max-per-month=1000 --confirm=yes` dengan modal konfirmasi keamanan. Pada level HTTP controller (`ActionController`), batas waktu eksekusi default 30 detik dinonaktifkan (`set_time_limit(0)`, `ignore_user_abort(true)`) serta dilengkapi indikator loading state interaktif pada antarmuka web guna mencegah interupsi.

#### 5.7 Manajemen Stopwords Database & Filter Kebisingan Teks (`/stopwords`)
* **CRUD Lengkap Berbasis Database:** Antarmuka web penuh untuk mengelola daftar kata henti (*stopwords*) yang tersimpan di tabel `stopwords` database `hantu`.
* **6 Kategori Terstruktur:**
  1. *Daerah:* `kabupaten, jombang, pemkab, pemda, pemerintah, kecamatan, kelurahan, desa, jatim, jawa, timur`.
  2. *Waktu:* nama hari, nama bulan (penuh & singkatan), waktu hari (`pagi, siang, sore, malam`).
  3. *Angka:* `ribu, juta, miliar, triliun, persen, rupiah`.
  4. *Sapaan & Gelar:* `bapak, ibu, pak, bu, mas, mbak, prof, drs, dra, dr, hj`.
  5. *Aksi Umum:* `gelar, lakukan, melakukan, laksanakan, adakan, hadiri, hadir, mulai`.
  6. *Umum:* kata tugas bahasa Indonesia standar.
* **Integrasi Dinamis API:** Menyediakan rute `GET /api/stopwords` untuk dikonsumsi mesin NLP Python dan agregasi frekuensi kata dashboard secara dinamis.

#### 5.8 Kamus Slang & Leksikon Sentimen InSet
* **Kamus Normalisasi Slang (Ade Arini Putri + Lokal):** Memuat 1.509 lema kata gaul dan singkatan percakapan (`engine-nlp/data/slang.csv`) untuk standarisasi teks opini warga.
* **InSet Sentiment Lexicon (fajri91/InSet):** Memuat 9.119 kata leksikon berbobot polaritas (`positive.tsv` 3.610 lema, `negative.tsv` 6.610 lema) yang dikonversi dari skala $[-5, +5]$ ke $[-1.0, +1.0]$ untuk mempertajam deteksi sentimen nuansa Indonesia.
