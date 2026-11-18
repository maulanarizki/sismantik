# TECH STACK ARCHITECTURE & ENVIRONMENT SPECIFICATION
## Proyek: SISMANTIK PRO (Native Local Linux Environment - Non-Docker)

SISMANTIK PRO dikonfigurasi untuk berjalan **100% secara langsung di lingkungan lokal (*Native Host Environment*)** tanpa menggunakan Docker, memanfaatkan software yang telah terpasang di sistem operasi Linux: PHP 8.4, Composer 2.8, Python 3.13, MariaDB 11.8 / MySQL 8, Redis 7, dan Node.js 24.

---

### 1. Spesifikasi Server & Lingkungan Lokal

* **Operating System:** Linux (Kernel 6.x / Debian/Ubuntu-based)
* **Koneksi Database MySQL / MariaDB Lokal:**
  * **Host:** `127.0.0.1`
  * **Port:** `3306`
  * **User:** `dev`
  * **Password:** `mbg`
  * **Database:** `hantu`
  * **Charset:** `utf8mb4` / `utf8mb4_unicode_ci`
* **Message Broker & Cache:** Redis Server Lokal (`127.0.0.1:6379`)
* **Spesifikasi Perangkat:** 12 CPU Threads, 16 GB RAM (Optimasi CPU-Threaded Multi-Core Inference)
* **Alamat Port Layanan:**
  * **Web Portal & Executive Dashboard:** `http://127.0.0.1:8000`
  * **Python NLP & Scraping Microservice:** `http://127.0.0.1:8001`

---

### 2. Diagram Alur Arsitektur Sistem Terpasang

```
+---------------------------------------------------------------------------------------+
|                 SISMANTIK EXECUTIVE COMMAND CENTER (PORT 8000)                        |
|                                                                                       |
|   Framework: PHP 8.4 + Laravel 11                                                     |
|   Frontend : Tailwind CSS (CDN/Custom Dark Mode) + Alpine.js v3.14 (Reactivity)       |
|   Grafik   : Chart.js v4 (12-Month Timeline Area Chart & 6-Emotion Donut Chart)       |
|   Peta GIS : Leaflet.js v1.9 (Local Asset) + 21 Poligon GADM 4.1 Kab. Jombang         |
|   Basemap  : OpenStreetMap Standard & Esri World Imagery (Zero Watermark / No API Key)|
|   Desain   : 100% Custom Bespoke UI (High Contrast, Light/Dark Switcher, Zero Filament|
+---------------------------------------------------------------------------------------+
                                           |
                          HTTP REST JSON (`POST /api/...`)
                                           |
+---------------------------------------------------------------------------------------+
|             PYTHON NLP & SCRAPING SERVICE (PORT 8001 / venv)                          |
|                                                                                       |
|   Framework: Python 3.13 + FastAPI + Uvicorn (Worker Background)                      |
|   Model    : IndoBERT Multi-Aspect + VADER Sentiment Engine (CPU Multi-Thread 12 Core)|
|   Emosi    : 6 Klasifikasi Diskrit (Apresiasi, Harapan, Kecewa, Marah, Sedih, Takut)   |
|   Deduplikasi: MinHash LSH (datasketch) & Deteksi Buzzer/Bot                          |
|   Scraper  : Trafilatura, Feedparser (RSS), Playwright Stealth                        |
+---------------------------------------------------------------------------------------+
                                           |
                                  SQL / ORM Query
                                           |
+---------------------------------------------------------------------------------------+
|                              BASIS DATA & STORAGE LAYER                               |
|                                                                                       |
|   MySQL/MariaDB : `hantu` @ 127.0.0.1:3306 (user: dev, pass: mbg)                     |
|                   2.952 Berita Terindeks, 2.952 Hasil Sentimen, 14 Topik/OPD,         |
|                   13 Endpoint Scraper, Riwayat Sinkronisasi                           |
|   Redis Server  : 127.0.0.1:6379 (Session, Cache, Queue Driver)                       |
+---------------------------------------------------------------------------------------+
```

---

### 3. Rincian Komponen Teknologi Aktual

#### A. Core Web & Executive Command Center (`core-web/`)
* **Framework:** **PHP 8.4 + Laravel 11**
  * Routing terpusat di `routes/web.php` dengan alias otomatis untuk rute `/admin/*` $\rightarrow$ `/dashboard`.
  * Middleware pengecualian CSRF token untuk rute AJAX interaktif (`/api/*` dan `/action/*`).
* **Frontend UI & Styling:**
  * **Tailwind CSS:** Dikonfigurasi dengan mode gelap (*dark mode class*) dan palet warna kontras tinggi (*slate-900*, *emerald-500*, *cyan-500*, *amber-500*, *rose-500*). Tidak ada lagi teks transparan/pudar (*bleached text*).
  * **Alpine.js v3.14.8:** Mengelola *state reactive* di sisi klien (jam digital real-time WIB, switcher tema, modal konfirmasi Replace Total, dan modal drilldown berita).
* **Visualisasi Grafik Data:**
  * **Chart.js v4:** Merender kurva tren sentimen 12 bulan terakhir (area chart) dan donat spektrum emosi dengan animasi halus dan rendering tajam di layar resolusi tinggi.
* **Geospatial GIS:**
  * **Leaflet.js:** Asset lokal mandiri di `public/vendor/leaflet/leaflet.js` & `leaflet.css` sehingga dapat beroperasi tanpa ketergantungan koneksi CDN luar.
  * **Basemap Tile Server:** Menggunakan OpenStreetMap (`https://tile.openstreetmap.org/{z}/{x}/{y}.png`) dan Esri World Imagery/Canvas. **Tidak lagi menggunakan CartoDB**, sehingga bersih dari watermark *"API KEY REQUIRED"*.
  * **GeoJSON Poligon 21 Kecamatan:** Tersimpan di `public/geojson/jombang_kecamatan.geojson` (132.9 KB), mencakup seluruh 21 kecamatan resmi Kabupaten Jombang.

#### B. NLP & Scraping Microservice (`engine-nlp/`)
* **Runtime:** **Python 3.13** dalam Virtual Environment terisolasi (`engine-nlp/venv/`).
* **API Service:** **FastAPI + Uvicorn** berjalan pada `127.0.0.1:8001`.
* **Arsitektur Model NLP:**
  * **IndoBERT Multi-Aspect Classifier:** Menghasilkan skor polaritas (Positif, Netral, Negatif).
  * **VADER Lexicon Fallback & Emoticon Converter (`demoji`):** Menganalisis sentimen dari ragam bahasa gaul dan emoji.
  * **Kamus Normalisasi Slang Jawa/Sunda:** Mengubah kata tidak baku seperti *"gak", "dalan ajur", "rusak parah"* ke makna standar.
  * **MinHash LSH (`datasketch`):** Menghitung nilai hash Jaccard untuk menyaring konten sindikasi/duplikat massal.
* **Ekstraktor Teks Berita:**
  * **Trafilatura:** Mengekstraksi artikel berita dari HTML mentah tanpa gangguan iklan, navigasi, atau banner.
  * **Feedparser:** Mengurai RSS feed portal media nasional dan Google News Aggregator secara berkala.

#### C. Daftar Controller & Rute Sistem Terpasang
| Controller | Rute URL | Fungsi Utama |
| :--- | :--- | :--- |
| `DashboardController` | `GET /` & `GET /dashboard` | Mengagregasi 4 Master KPI, 4 PSN cards, tren sentimen 12 bulan, 10 rapor OPD, matriks framing, 6 emosi, dan PDB AI. |
| `GeospatialController` | `GET /peta` | Menghitung statistik sentimen 21 kecamatan, passing GeoJSON, leaderboard, dan inspector HUD. |
| `RawContentController` | `GET /berita` | Menampilkan katalog arsip berita dengan filter pencarian, scope, sentimen, dan paginasi. |
| `TopicAgencyController`| `GET /opd` & `POST /opd/{id}/toggle` | Direktori 10 OPD Pemkab Jombang + PSN, daftar kata kunci, dan tombol On/Off status aktif. |
| `SourceController` | `GET /sumber` | Daftar portal media dan 13 endpoint scraper. |
| `SyncJobController` | `GET /sinkronisasi` | Log audit sesi sinkronisasi data, durasi proses, dan status. |
| `GuideController` | `GET /panduan` | SOP operasional, alur scraping, dan petunjuk pemulihan data total. |
| `ActionController` | `POST /action/sync` | Trigger penarikan berita inkremental (`sismantik:sync --max=20`). |
| `ActionController` | `POST /action/replace-total` | Trigger pembersihan data & penarikan ulang 12 bulan (`sismantik:replace-total`). |
| `ActionController` | `POST /api/drilldown-articles` | Endpoint JSON penyedia artikel berita untuk modal drilldown interaktif. |
| *Route Redirector* | `GET /admin/*` | Mengalihkan URL lama Filament secara otomatis (*302 Found*) ke antarmuka baru. |

---

### 4. Skrip Startup Mandiri (`start_sismantik.sh`)
Sistem dilengkapi skrip peluncur satu pintu:
```bash
./start_sismantik.sh
```
Skrip ini secara otomatis:
1. Memverifikasi konektivitas MySQL `hantu` dan Redis `127.0.0.1:6379`.
2. Menjalankan Python FastAPI Microservice di port 8001 (jika belum aktif).
3. Menjalankan server Laravel Web di port 8000.
