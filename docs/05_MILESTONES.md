# MILESTONES & IMPLEMENTATION LOG
## Proyek: SISMANTIK PRO (Status Implementasi Riil Sistem)

---

### 1. Ringkasan Status & Pencapaian Roadmap

| Fase | Fokus Pengembangan | Status | Target Luaran Terpasang |
| :--- | :--- | :---: | :--- |
| **Fase 0** | Fondasi Proyek & Database MySQL | **SELESAI (100%)** | Setup Laravel 11, MariaDB/MySQL `hantu` (`127.0.0.1:3306`), Redis (`127.0.0.1:6379`), dan inisialisasi Python 3.13 venv. |
| **Fase 1** | Dynamic Sources & Endpoint Management | **SELESAI (100%)** | 6 Portal sumber media (Radar Jombang, Kabar Jombang, Beritajatim, Detik, Antara, Google News) dan 13 endpoint scraper. |
| **Fase 2** | Scraping Engine Asinkron | **SELESAI (100%)** | FastAPI microservice (`:8001`), modul Trafilatura, Feedparser RSS, dan integrasi penarikan data berbasis tanggal publikasi asli. |
| **Fase 3** | Local NLP Engine & Geotagging | **SELESAI (100%)** | Inferensi IndoBERT multi-aspek, VADER fallback, klasifikasi 6 spektrum emosi diskrit, normalisasi dialek lokal, dan ekstraksi 21 kecamatan. |
| **Fase 4** | Redesain Total: Executive Command Center | **SELESAI (100%)** | Melepas Filament, membangun antarmuka murni Laravel Blade + Tailwind + Alpine.js + Chart.js, kontras tinggi (*zero bleached text*), dan 1-klik Dark/Light toggle. |
| **Fase 5** | Pusat Komando GIS 21 Kecamatan Mandiri | **SELESAI (100%)** | Pemisahan rute `/peta`, 21 poligon resmi GADM 4.1 Kab. Jombang, basemap OpenStreetMap & Esri World Imagery (**100% bebas watermark CartoDB**), Inspector HUD, dan Leaderboard. |
| **Fase 6** | Dataset 12 Bulan & Global Article Drilldown | **SELESAI (100%)** | 2.952 berita awal, modal drilldown berita interaktif global (`/api/drilldown-articles`), perintah `sismantik:replace-total` & `sismantik:sync`. |
| **Fase 7** | Pengayaan NLP (InSet & Slang), Wordcloud Sentimen, Stopwords DB, & Kalibrasi GIS | **SELESAI (100%)** | Integrasi 1.509 slang Ade Arini Putri, 9.119 leksikon InSet, modul Wordcloud sentimen & topik di dashboard, CRUD tabel `stopwords` (189 kata), endpoint `/api/stopwords`, fix timeout 30s `replaceTotal`, normalisasi GIS `district_name`, kalibrasi 4 level warna Choropleth, 5 metrik header peta, dan live feed dokumen berita per kecamatan. **Total aktif: 3.680 Berita**. |

---

### 2. Log Catatan Teknis Tiap Fase

#### Fase 0: Fondasi Mesin Lokal & Basis Data
* Berhasil mengonfigurasi database `hantu` pada MariaDB 11.8 dengan kredensial `user: dev`, `pass: mbg`.
* Menyiapkan tabel-tabel terstruktur dengan engine InnoDB dan collation `utf8mb4_unicode_ci`.
* Memverifikasi Redis 7 lokal pada port `6379` untuk session dan caching.

#### Fase 1 & 2: Ingestion & Scraping Massal
* Menghubungkan 13 target endpoint, mencakup RSS feed portal terakreditasi dan query terfokus Google News Aggregator untuk isu Kabupaten Jombang.
* Menerapkan sanitasi HTML otomatis dengan Trafilatura guna mengekstrak teks berita bersih dari iklan dan widget portal.

#### Fase 3: NLP Multi-Aspek & Deteksi Entitas Wilayah
* Mengimplementasikan analisis sentimen 3-kelas (Positif, Netral, Negatif).
* Mengintegrasikan klasifikasi 6 spektrum emosi: *Apresiasi, Harapan, Kecewa, Marah, Sedih, Takut*.
* Menambahkan kamus normalisasi istilah lokal Jawa Timuran (*"dalan ajur", "rusak parah", "tambal sulam", "bapuk"*).
* Ekstraksi nama wilayah secara otomatis memetakan teks ke 21 kecamatan resmi di Jombang.

#### Fase 4: Transformasi Antarmuka ke Executive Command Center (Bebas Filament)
* **Masalah Awal:** Tampilan admin panel Filament memiliki teks transparan/pudar (*bleached text*), widget grid yang kaku, dan benturan styling pada mode terang.
* **Solusi Nyata:**
  * Melepas ketergantungan tampilan dari Filament bawaan.
  * Membangun arsitektur frontend kustom menggunakan **Laravel Blade, Tailwind CSS, Alpine.js, dan Chart.js**.
  * Memberikan kontras teks ultra-tajam (`#0f172a` pada mode terang dan `#ffffff` pada mode gelap).
  * Menghadirkan tombol pengalih tema instan (*Dark/Light Mode Switcher*) di header.

#### Fase 5: Solusi Peta Geospasial & Hilangnya Watermark CartoDB
* **Masalah Awal:** Basemap CartoDB menampilkan watermark berulang *"API KEY REQUIRED"* di seluruh permukaan peta, dan peta lambat ketika digabung di dashboard utama.
* **Solusi Nyata:**
  * Memindahkan modul peta ke halaman mandiri di [`/peta`](http://127.0.0.1:8000/peta).
  * Mengganti basemap tile ke **OpenStreetMap** dan **Esri World Imagery (Satelit)** yang terbukti stabil, tanpa API key, dan **bersih dari watermark**.
  * Memuat 21 batas poligon resmi GADM 4.1 dari file lokal `core-web/public/geojson/jombang_kecamatan.geojson`.
  * Menyediakan Inspector HUD di bawah peta serta Leaderboard 21 kecamatan berdasarkan total volume, tingkat keluhan, dan sentimen positif.

#### Fase 6: Dataset 12 Bulan, Global Drilldown & Pemulihan Data
* **Akurasi Waktu:** Tanggal grafik dan filter dihitung berdasarkan `published_at` (tanggal asli saat berita dirilis oleh media), bukan tanggal saat robot scraper mengunduh artikel.
* **Modal Drilldown Global (`#article-drilldown-modal`):** Pimpinan dapat mengklik kartu PSN, baris OPD, matriks framing isu, spektrum emosi, maupun poligon peta untuk membaca artikel berita aslinya secara lengkap.
* **Fitur Replace Data Total:** Perintah artisan `sismantik:replace-total` dengan modal konfirmasi keamanan di antarmuka web untuk membersihkan data lama dan menarik ulang data segar 12 bulan terakhir.

#### Fase 7: Pengayaan NLP, Wordcloud Sentimen, Stopwords Database & Kalibrasi GIS Teritori
* **Kamus Slang & InSet Lexicon:**
  * Mengintegrasikan 1.509 padanan kata gaul dari repositori Ade Arini Putri + dialek lokal Jatim ke `engine-nlp/data/slang.csv`.
  * Mengintegrasikan 9.119 leksikon InSet (fajri91/InSet: `positive.tsv` 3.610 lema, `negative.tsv` 6.610 lema) dengan konversi polaritas $[-5, +5] \rightarrow [-1.0, +1.0]$ pada engine Python FastAPI.
* **Modul Wordcloud Sentimen & Isu Topik di Dashboard:**
  * Menghadirkan tag cloud kata kunci interaktif dengan 3 bilik: *Sentimen Positif*, *Sentimen Negatif*, dan *Isu Topik Dominan*.
  * Ukuran font kata proporsional terhadap frekuensi kemunculan (*Term Frequency*), dilengkapi badge frekuensi sebutan.
* **Manajemen Stopwords Database (`/stopwords`):**
  * Membuat tabel database `stopwords` dengan 6 kategori terstruktur (Daerah, Waktu, Angka, Sapaan & Gelar, Aksi Umum, Umum).
  * Mengembangkan antarmuka CRUD lengkap via `StopwordController` dan seeder awal 189 kata henti.
  * Membuka endpoint API `GET /api/stopwords` untuk sinkronisasi otomatis ke pipeline NLP.
* **Solusi Timeout 30 Detik Replace Data Total:**
  * Mengatasi `FatalError: Maximum execution time of 30 seconds exceeded` pada `ActionController` dengan konfigurasi `ini_set('max_execution_time', 0)`, `set_time_limit(0)`, dan `ignore_user_abort(true)`.
  * Menambahkan loading spinner animasi dan proteksi pencegah *double submit* pada modal antarmuka web.
  * Hasil eksekusi *Replace Total* sukses mengindeks **3.680 berita terkurasi** (2.452 Isu Lokal Pemkab, 1.228 Program Strategis Nasional), serta 858 ekstraksi geospasial.
* **Penyempurnaan GIS Peta 21 Kecamatan:**
  * Memperbaiki *property matching* GeoJSON dari `NAME_3` ke `district_name` (dengan regex pembersih prefix `kecamatan`).
  * Mengkalibrasi 4 level warna Choropleth sentimen teritori: *Sangat Kondusif* (`#10b981`), *Kondusif & Stabil* (`#06b6d4`), *Perlu Atensi* (`#f59e0b`), dan *Rawan Keluhan* (`#f43f5e`).
  * Menambahkan 5 kartu metrik teritorial di header halaman peta.
  * Menambahkan **Feed Dokumen Berita Teritorial Langsung** di dalam panel inspektor wilayah sehingga artikel berita per kecamatan dapat langsung dibaca dan ditelusuri.
