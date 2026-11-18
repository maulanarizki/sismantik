# MILESTONES & IMPLEMENTATION ROADMAP (LOCAL NATIVE EXECUTION)
## Proyek: SISMANTIK (Lingkungan Laptop Lokal - Non-Docker)

Roadmap implementasi ini disesuaikan untuk dieksekusi secara **langsung di mesin laptop lokal** tanpa Docker. Semua dependensi memanfaatkan software yang sudah aktif (PHP 8.4, Composer 2.8, Python 3.13, MySQL 8, Redis, Node.js 24).

---

### Timeline & Pembagian Sprint (Total 6 Fase)

| Fase | Fokus Utama | Target Luaran (Key Deliverables) |
| :--- | :--- | :--- |
| **Fase 0** | Fondasi Proyek & Database MySQL | Setup Laravel 11 di workspace, config `.env` MySQL `hantu`, setup Filament v3, migrasi DDL, dan inisialisasi Python 3.13 venv. |
| **Fase 1** | Dynamic Sources & Sync Controller | CRUD Sumber Data & Endpoints, Toggle On/Off dinamis, UI Modal Sinkronisasi (*Date Range* & *Max Limit*). |
| **Fase 2** | Python Scraping Service & Redis Worker | Service FastAPI (`:8001`), modul Trafilatura, Feedparser, Playwright Stealth, dan integrasi Laravel Queue. |
| **Fase 3** | Local NLP Engine (IndoBERT CPU) | Unduh bobot IndoBERT lokal, modul normalisasi dialek/slang lokal, ABSA per dinas/OPD, dan MinHash deduplikasi. |
| **Fase 4** | Visualisasi Eksekutif "Data Berbicara" | Widget Barometer NSS, Radar Emosi 6-Sumbu ECharts, OPD Scorecard, dan Leaflet GeoJSON Heatmap Kecamatan. |
| **Fase 5** | Deteksi Buzzer, Alerting & UAT | Audit pola komentar duplikat/anomali, Bot Telegram / WhatsApp alerting, uji alur menyeluruh di laptop. |

---

### Rincian Rencana Tindakan per Fase

#### Fase 0: Inisialisasi Proyek & Migrasi Basis Data MySQL (Target: Hari 1-2)
* **Langkah Teknis:**
  1. Inisialisasi proyek Laravel 11 di direktori kerja:
     ```bash
     composer create-project laravel/laravel core-web
     ```
  2. Konfigurasi file `core-web/.env`:
     ```ini
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=hantu
     DB_USERNAME=dev
     DB_PASSWORD=mbg
     QUEUE_CONNECTION=redis
     ```
  3. Instalasi **FilamentPHP v3**:
     ```bash
     composer require filament/filament:"^3.2" -W
     php artisan filament:install --panels
     ```
  4. Pembuatan file migrasi database sesuai spesifikasi DDL MySQL (`sources`, `source_endpoints`, `sync_jobs`, `topics_or_agencies`, `raw_contents`, `sentiment_results`, `aspect_sentiments`, dll.) dan eksekusi `php artisan migrate`.
  5. Inisialisasi Python Virtual Environment untuk microservice:
     ```bash
     python3 -m venv engine-nlp/venv
     source engine-nlp/venv/bin/activate
     pip install fastapi uvicorn trafilatura playwright feedparser torch transformers datasketch demoji
     playwright install chromium
     ```
* **Checkpoint Keberhasilan:**
  * Tabel-tabel terbentuk sempurna di database MySQL `hantu`.
  * Panel Filament terbuka di `http://127.0.0.1:8000/admin` dengan akun administrator.

---

#### Fase 1: Manajemen Sumber Data & Endpoint Dinamis (Target: Hari 3-5)
* **Langkah Teknis:**
  1. Pembuatan Filament Resources:
     * `SourceResource` (Nama media, tipe platform web/medsos, base URL, interval sinkronisasi, toggle On/Off).
     * `SourceEndpointResource` (URL spesifik, tipe parser: RSS / Trafilatura / Playwright, JSON selector config, max limit default, toggle On/Off).
     * `TopicOrAgencyResource` (Daftar OPD, alias, kata kunci isu).
  2. Implementasi **Action Modal "Sinkronisasi Sekarang"**:
     * Field *Date Range Picker* (`start_date` s.d. `end_date`).
     * Field *Number Input* (`max_items`, default: 50).
     * Checkbox pilihan sumber (Semua sumber aktif vs spesifik).
  3. Pembuatan seeder data awal: 5 portal berita lokal/nasional, 3 akun medsos humas, dan 5 dinas utama (Dinas PUPR, Dinkes, Disdik, Disdukcapil, Dishub).
* **Checkpoint Keberhasilan:**
  * Pengguna dapat menambah, mengedit, dan mematikan/menyalakan target endpoint secara instan melalui tabel UI.
  * Modal sinkronisasi berhasil memvalidasi input tanggal dan batas jumlah data.

---

#### Fase 2: Scraping Engine Asinkron & Integrasi Redis (Target: Pekan 2)
* **Langkah Teknis:**
  1. Membangun aplikasi FastAPI di `engine-nlp/main.py`:
     * Endpoint `POST /api/scrape`: Menerima konfigurasi endpoint, rentang tanggal, dan batas data.
     * Ekstraktor Trafilatura untuk berita web reguler.
     * Ekstraktor Feedparser untuk RSS feeds.
     * Ekstraktor Playwright Stealth untuk web dinamis.
  2. Implementasi Laravel Job `DispatchScrapeJob`:
     * Dikirim ke antrean Redis `scraping_tasks`.
     * Mengambil konten dan menyimpannya ke tabel `raw_contents`.
  3. Sistem *Progress Tracker*: Memperbarui status di tabel `sync_jobs` secara berkala sehingga pengguna di dashboard dapat melihat perkembangan penarikan data.
* **Checkpoint Keberhasilan:**
  * Klik "Sinkronisasi Sekarang" berhasil menarik puluhan artikel/komentar aktual dalam kurun tanggal yang ditentukan tanpa error.
  * Teks artikel berita otomatis tersaring bersih dari iklan dan navigasi situs.

---

#### Fase 3: Local NLP Engine (IndoBERT CPU & Dialek Lokal) (Target: Pekan 3)
* **Langkah Teknis:**
  1. Setup inferensi PyTorch CPU untuk model `IndoBERT` sentimen (Positif, Netral, Negatif).
  2. Pembuatan modul *Text Preprocessor*:
     * Kamus slang & dialek daerah (Jawa/Sunda) format JSON lokal.
     * `demoji` untuk konversi emotikon menjadi representasi teks.
     * MinHash LSH (`datasketch`) untuk menandai berita sindikasi yang duplikat.
  3. Implementasi Aspect-Based Sentiment Analysis (ABSA):
     * Memetakan kalimat ke OPD terkait berdasarkan pencocokan kamus `topics_or_agencies.keywords`.
     * Menghitung sentimen spesifik per dinas.
  4. Penyimpanan hasil olahan ke tabel `sentiment_results` dan `aspect_sentiments`.
* **Checkpoint Keberhasilan:**
  * Kalimat keluhan daerah berhasil dipahami dan dinormalisasi.
  * Inferensi berjalan cepat di CPU laptop (kecepatan < 100 ms per dokumen dengan optimasi multi-threading).

---

#### Fase 4: Visualisasi Eksekutif "Data Berbicara" (Target: Pekan 4)
* **Langkah Teknis:**
  1. Pembuatan Widget Dashboard Filament dengan Apache ECharts:
     * **Barometer Net Sentiment Score (NSS):** Menampilkan skor persepsi publik secara grafis.
     * **Volume vs Sentiment Dual-Axis:** Grafik area pergerakan isu harian.
     * **Emotion Hexagon Radar:** 6 sumbu emosi psikologi massa (*Marah, Kecewa, Takut, Sedih, Apresiasi, Harapan*).
     * **OPD Scorecard:** Bar chart komparasi kepuasan per dinas.
  2. Integrasi **Peta Geospasial Leaflet.js**:
     * Memuat file GeoJSON batas wilayah kecamatan/kelurahan.
     * Pewarnaan poligon wilayah sesuai intensitas sentimen negatif.
  3. Halaman *Content Explorer* dengan pencarian cepat `FULLTEXT` MySQL.
* **Checkpoint Keberhasilan:**
  * Tampilan dashboard menarik, profesional, dan responsif.
  * Data langsung menceritakan isu utama, dinas yang disorot, dan kecamatan yang membutuhkan tindakan pimpinan.

---

#### Fase 5: Deteksi Buzzer, Alerting & UAT (Target: Pekan 5)
* **Langkah Teknis:**
  1. Modul deteksi anomali buzzer:
     * Menghitung kemiripan teks komentar di media sosial (> 90%) untuk mendeteksi pesan copy-paste terkoordinasi.
     * Menandai konten di tabel `raw_contents.is_suspected_bot`.
  2. Sistem Early Warning:
     * Cron scheduler memeriksa lonjakan sentimen negatif $> 40\%$ dalam 2 jam terakhir.
     * Integrasi notifikasi darurat via Bot Telegram Pemda.
  3. Ringkasan Eksekutif Harian:
     * Ekstraksi 3 poin isu terpenting hari ini ke tabel `ai_executive_summaries`.
  4. Uji alur menyeluruh (*end-to-end testing*) di laptop lokal.
* **Checkpoint Keberhasilan:**
  * Seluruh sistem berjalan stabil secara lokal.
  * Siap digunakan langsung untuk pemantauan opini publik di lingkungan Pemda.
