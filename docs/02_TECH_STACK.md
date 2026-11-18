# TECH STACK ARCHITECTURE (LOCAL NATIVE & MYSQL)
## Proyek: SISMANTIK (Lingkungan Laptop Lokal - Non-Docker)

Sistem ini dikonfigurasi untuk berjalan **100% secara langsung di lingkungan lokal laptop (*Native Host Environment*)** tanpa menggunakan Docker, memanfaatkan software yang telah terpasang di sistem (PHP 8.4, Composer 2.8, Python 3.13, MySQL 8, Redis, dan Node.js 24).

---

### 1. Konfigurasi Lingkungan Lokal (Target Laptop)

* **Operating System:** Linux (Debian/Ubuntu Kernel)
* **Koneksi Database MySQL Lokal:**
  * **Host:** `127.0.0.1`
  * **Port:** `3306`
  * **User:** `dev`
  * **Password:** `mbg`
  * **Database:** `hantu`
  * **Charset:** `utf8mb4` / `utf8mb4_unicode_ci`
* **Message Broker:** Redis Server Lokal (`127.0.0.1:6379`)
* **Spesifikasi Perangkat Keras:** 12 CPU Cores, 16 GB RAM (Optimasi CPU-Threaded Inference)

---

### 2. Diagram Alur Arsitektur Native

```
+-------------------------------------------------------------------------------+
|                    CORE WEB & ADMIN DASHBOARD (PHP 8.4)                       |
|   Laravel 11 | FilamentPHP v3 | Livewire 3 | Tailwind CSS | Apache ECharts    |
|               Berjalan via: php artisan serve (Port 8000)                     |
+-------------------------------------------------------------------------------+
                                     |
                       REST / Redis Queue (Port 6379)
                                     |
+-------------------------------------------------------------------------------+
|                 SCRAPING & INGESTION SERVICE (Python 3.13)                    |
|          FastAPI | Trafilatura | Playwright Stealth | BeautifulSoup4          |
|                 Feedparser | Custom Social Media Worker                       |
|                 Berjalan via: uvicorn (Port 8001 / venv)                      |
+-------------------------------------------------------------------------------+
                                     |
                          Asynchronous Pipeline
                                     |
+-------------------------------------------------------------------------------+
|                   LOCAL NLP & SENTIMENT ENGINE (PyTorch CPU)                  |
|          Hugging Face Transformers | IndoBERT-base-p1 (SmSA fine-tuned)       |
|          ABSA Engine | Kamus Slang & Dialek Lokal | Demoji | MinHash LSH      |
|               Dioptimasi untuk Multi-Core CPU (12 Threads)                    |
+-------------------------------------------------------------------------------+
                                     |
                         Database & Message Storage
                                     |
+-------------------------------------------------------------------------------+
|                         STORAGE & BROKER LAYER                                |
|        MySQL 8.x (127.0.0.1:3306 | db: hantu | user: dev)                     |
|        Redis 7.x (127.0.0.1:6379 | cache, queue & pubsub)                     |
+-------------------------------------------------------------------------------+
```

---

### 3. Rincian Komponen Teknologi

#### A. Core Web, Admin Panel & Dashboard
* **Framework:** **PHP 8.4 + Laravel 11**
  * Koneksi native ke MySQL melalui driver `pdo_mysql`.
  * Antrean job background menggunakan Laravel Queue dengan driver `redis`.
* **Admin & Dashboard Engine:** **FilamentPHP v3**
  * Resource-based panel untuk manajemen sumber data dan endpoint dinamis.
  * Form builder dinamis dengan toggle On/Off instan.
  * Modal Action terintegrasi untuk pemicu *Sync Now* (rentang tanggal & limit data).
* **Komponen Reaktif:** **Laravel Livewire 3 + Alpine.js**
  * Memberikan antarmuka reaktif tanpa beban berat framework JS terpisah.
* **Visualisasi Grafik:** **Apache ECharts**
  * Visualisasi barometer *Net Sentiment Score* (NSS), grafik tren volume, radar emosi 6 sumbu, dan word cloud.
* **Peta Geospasial:** **Leaflet.js**
  * Merender peta vektor batas kecamatan GeoJSON lokal secara offline/mandiri.

#### B. Scraping & Ingestion Service
* **Runtime:** **Python 3.13 (Virtual Environment `venv`)**
* **API Service:** **FastAPI + Uvicorn**
  * Menjalankan microservice scraping di `127.0.0.1:8001`.
* **Ekstraktor Berita Web Cepat:** **Trafilatura**
  * Pustaka terbaik untuk ekstraksi artikel utama web secara otomatis tanpa iklan/sidebar.
* **Headless Browser (Dynamic Sites):** **Playwright Python + playwright-stealth**
  * Digunakan untuk situs berita yang membutuhkan render JavaScript atau media sosial publik.
* **RSS & Feed Parser:** **Feedparser**
  * Mengambil feed berkala dengan konsumsi bandwidth dan CPU yang sangat minim.

#### C. Local NLP & Machine Learning Engine (Dioptimasi untuk CPU Laptop)
* **Framework:** **PyTorch 2.x (CPU Build dengan dukungan AVX2 / OpenMP)**
  * Dikonfigurasi dengan `torch.set_num_threads(8)` untuk memanfaatkan 12-core laptop secara efisien tanpa membuat sistem freeze.
* **Model Sentimen Bahasa Indonesia:** **`indobenchmark/indobert-base-p1`**
  * Model diunduh dan disimpan di folder lokal `engine-nlp/models/indobert-sentiment/`.
  * Menghasilkan probabilitas: Positif, Netral, Negatif.
* **Aspect-Based Sentiment Analysis (ABSA):**
  * Memetakan sentimen spesifik per dinas/OPD berdasarkan pencocokan entitas dan kamus topik di MySQL.
* **Modul Preprocessing Mandiri:**
  * Kamus slang & bahasa daerah lokal (Jawa/Sunda) format JSON.
  * `demoji` untuk konversi emoji teks.
  * `datasketch` (MinHash LSH) untuk deteksi duplikasi berita massal.

#### D. Database & Broker
* **Database Relasional:** **MySQL 8.x**
  * Menggunakan database `hantu` pada `127.0.0.1:3306`.
  * Tipe data `JSON` untuk konfigurasi selector, atribut fleksibel, dan metadata engagement.
  * Indeks `FULLTEXT` pada judul dan isi konten untuk fitur pencarian teks instan tanpa memerlukan Elasticsearch eksternal.
* **Message Broker & Cache:** **Redis 7.x**
  * Mengelola antrean penarikan data dan pertukaran status job antara Laravel dan Python.

---

### 4. Mengapa Pendekatan Native Local Ini Sangat Tepat untuk Laptop Anda?

1. **Hemat Memori:** Menghilangkan overhead memori Docker daemon, container virtualization, dan bridge network (menghemat ~2–4 GB RAM laptop).
2. **Setup Cepat & Langsung Nyambung:** Database MySQL `hantu` dan user `dev` sudah aktif dan siap digunakan secara langsung.
3. **Fleksibilitas Debugging:** Kode Laravel dan Python dapat di-edit langsung dengan reload seketika (*hot-reload*) tanpa proses `docker compose build`.
4. **Performa Maksimal:** Eksekusi langsung di atas CPU host 12-core memberikan kecepatan inferensi PyTorch dan penarikan Trafilatura yang optimal.
