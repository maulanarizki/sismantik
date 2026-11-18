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
| **Fase 6** | Dataset 12 Bulan & Global Article Drilldown | **SELESAI (100%)** | 2.952 berita terkurasi 12 bulan, modal drilldown berita interaktif global (`/api/drilldown-articles`), perintah `sismantik:replace-total` & `sismantik:sync`. |

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
* **Dataset Terverifikasi:** Sistem saat ini aktif dengan **2.952 berita** yang terpetakan rapi di database.
