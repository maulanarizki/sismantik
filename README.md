# SISMANTIK PRO — Media Intelligence & Public Sentiment Analytics
## Pusat Intelijen Opini Publik & Pemantauan Media Pemerintah Kabupaten Jombang

![PHP 8.4](https://img.shields.io/badge/PHP-8.4-777BB4?style=flat-square&logo=php&logoColor=white)
![Laravel 11](https://img.shields.io/badge/Laravel-11-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![Python 3.13](https://img.shields.io/badge/Python-3.13-3776AB?style=flat-square&logo=python&logoColor=white)
![FastAPI](https://img.shields.io/badge/FastAPI-0.115-009688?style=flat-square&logo=fastapi&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-MariaDB_11-4479A1?style=flat-square&logo=mysql&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/TailwindCSS-v3-38B2AC?style=flat-square&logo=tailwind-css&logoColor=white)
![Leaflet GIS](https://img.shields.io/badge/Leaflet-GIS_21_Kecamatan-199900?style=flat-square&logo=leaflet&logoColor=white)
![Zero Paid API](https://img.shields.io/badge/License_Cost-Rp_0_(100%25_Mandiri)-success?style=flat-square)

---

### 1. Mengenai SISMANTIK PRO

**SISMANTIK PRO** adalah platform intelijen media dan monitoring sentimen publik mandiri (*self-hosted media intelligence*) yang dikembangkan untuk Pemerintah Kabupaten Jombang (Bupati, Sekretaris Daerah, Dinas Kominfo, serta 10 Organisasi Perangkat Daerah / OPD). 

Sistem ini beroperasi **100% mandiri tanpa ketergantungan API eksternal berbayar** (Rp 0,- biaya lisensi). Seluruh pemrosesan web scraping, dedukplikasi, inferensi AI NLP multi-aspek, dan visualisasi spasial berjalan langsung (*natively*) pada lingkungan Linux lokal tanpa container Docker.

---

### 2. Fitur Utama Sistem

* **Executive Command Center (`/dashboard`):**
  * **4 Master KPI Cards:** Total Berita Terpublikasi (2.952 konten aktif), Net Sentiment Score (NSS), Indeks Kepuasan Warga (Trust Index), dan Deteksi Bot/Anomali MinHash LSH.
  * **4 Pilar Monitoring Program Strategis Nasional (PSN):**
    1. *Program Makan Bergizi Gratis (MBG)* (BGN, Kodim 0814, Satgas Pemkab).
    2. *Koperasi Desa (Kopdes) & Ketahanan Pangan* (Kemenkop, Bulog, penyerapan gabah lokal).
    3. *Pemberantasan Korupsi & Penegakan Hukum Daerah* (KPK, Kejari Jombang).
    4. *Swasembada Pangan & Alsintan Nasional* (Kementan, BBWS Brantas).
  * **Grafik Tren Sentimen 12 Bulan (Chart.js Area Chart):** Fluktuasi volume dan sentimen berdasarkan tanggal riil rilis berita (`published_at`).
  * **Rapor Kinerja 10 OPD Pemkab Jombang:** Pemeringkatan volume dan penerimaan publik per dinas dilengkapi multi-sentiment progress bar.
  * **Matriks Framing Isu & Aduan Warga:** 5 Klaster isu (Infrastruktur, Kesehatan, Pertanian, Pendidikan, Hukum).
  * **Anatomi 6 Spektrum Emosi:** *Apresiasi, Harapan, Kecewa, Marah, Sedih, Takut*.
  * **Executive AI Digest:** Ringkasan narasi harian, kerawanan isu, dan rekomendasi langkah strategis humas standar *Presidential Daily Brief* (PDB).
* **Pusat Komando Geospasial 21 Kecamatan (`/peta`):**
  * Peta GIS interaktif mandiri dengan **21 poligon batas resmi GADM 4.1 Kabupaten Jombang**.
  * Pewarnaan Choropleth sentimen (*Sangat Kondusif*, *Kondusif*, *Perlu Atensi*, *Rawan Keluhan*).
  * Multi-layer basemap (OpenStreetMap, Esri Canvas, Esri Satellite) **100% bebas watermark**.
  * Panel Inspector HUD interaktif dan Leaderboard 21 kecamatan.
* **Global Interactive Article Drilldown Modal (`/api/drilldown-articles`):**
  * Klik pada kartu PSN, baris OPD, klaster framing, irisan emosi, maupun poligon kecamatan untuk membuka daftar berita asli beserta cuplikan teks, badge sentimen, dan tautan sumber portal.
* **Katalog Berita & Opini Publik (`/berita`):** Pencarian teks penuh, filter ruang lingkup (Semua, Lokal Pemkab, PSN), filter sentimen, dan paginasi.
* **Direktori OPD & Kata Kunci (`/opd`):** Manajemen 10 OPD + PSN, kata kunci pemantauan, dan toggle aktifasi status dinas.
* **Portal & Sumber Data (`/sumber`):** Daftar portal media terakreditasi dan 13 endpoint scraper.
* **Siklus Pembaruan Data (`/panduan` & Header):**
  * *Tarik Berita:* Sinkronisasi inkremental berita terbaru (`sismantik:sync --max=20`).
  * *Replace Data Total:* Mengosongkan data lama dan menarik ulang data segar 12 bulan terakhir (`sismantik:replace-total --months=12`).
* **Desain & UX Modern:**
  * Kontras ultra-tajam (*zero bleached text*).
  * Tombol 1-klik *Instant Dark/Light Mode Switcher*.
  * Pengalihan otomatis URL lama `/admin/*` $\rightarrow$ `/dashboard`.

---

### 3. Arsitektur Komponen

```
+---------------------------------------------------------------------------------------+
|                 SISMANTIK EXECUTIVE COMMAND CENTER (PORT 8000)                        |
|                                                                                       |
|   Framework: PHP 8.4 + Laravel 11                                                     |
|   Frontend : Tailwind CSS (Custom Dark Mode) + Alpine.js v3.14 (Reactivity)           |
|   Grafik   : Chart.js v4 (Area & Donut Charts)                                        |
|   Peta GIS : Leaflet.js v1.9 + 21 Poligon GADM 4.1 Kab. Jombang                       |
|   Basemap  : OpenStreetMap Standard & Esri World Imagery (Zero Watermark / No API Key)|
+---------------------------------------------------------------------------------------+
                                           |
                          HTTP REST JSON (`POST /api/...`)
                                           |
+---------------------------------------------------------------------------------------+
|             PYTHON NLP & SCRAPING SERVICE (PORT 8001 / venv)                          |
|                                                                                       |
|   Framework: Python 3.13 + FastAPI + Uvicorn                                          |
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

### 4. Persyaratan Sistem & Dependensi

* **OS:** Linux (Ubuntu 22.04+ / Debian 12+)
* **PHP:** $\ge 8.2$ (Disarankan PHP 8.4) dengan ekstensi `pdo_mysql`, `curl`, `mbstring`, `xml`, `redis`
* **Python:** $\ge 3.11$ (Disarankan Python 3.13) dengan Virtual Environment (`venv`)
* **Database:** MySQL 8.x atau MariaDB 11.x (Database: `hantu`, User: `dev`, Password: `mbg`)
* **Redis:** $\ge 6.x$ (Port `6379`)

---

### 5. Cara Menjalankan Sistem (Quick Start)

Sistem telah dilengkapi skrip peluncur otomatis satu pintu:

```bash
# 1. Masuk ke direktori root proyek
cd /home/digimon/Documents/KOMINFO/PROJEK/HANTU

# 2. Jalankan skrip startup
./start_sismantik.sh
```

Skrip di atas akan secara otomatis:
1. Memverifikasi koneksi database MySQL `hantu` dan Redis `6379`.
2. Menjalankan Python FastAPI Microservice di latar belakang (Port `8001`).
3. Menjalankan Laravel Development Server di Port `8000`.

Buka browser Anda dan akses:
* **Dashboard Eksekutif:** [http://127.0.0.1:8000](http://127.0.0.1:8000) atau [http://127.0.0.1:8000/dashboard](http://127.0.0.1:8000/dashboard)
* **Peta Geospasial 21 Kecamatan:** [http://127.0.0.1:8000/peta](http://127.0.0.1:8000/peta)
* **Dokumentasi & Panduan Operasional:** [http://127.0.0.1:8000/panduan](http://127.0.0.1:8000/panduan)

---

### 6. Perintah Artisan Mandiri

Jika ingin menjalankan operasi pemeliharaan via CLI terminal:

```bash
cd core-web

# Sinkronisasi berita terbaru (Inkremental 20 data)
php artisan sismantik:sync --max=20

# Menarik histori 12 bulan terakhir tanpa menghapus data
php artisan sismantik:sync-historical --months=12 --max-per-month=1000

# Replace Data Total (Reset tabel & tarik ulang 12 bulan secara bersih)
php artisan sismantik:replace-total --months=12 --max-per-month=1000 --confirm=yes

# Analisis ulang artikel yang belum terproses oleh NLP Engine
php artisan sismantik:analyze-content
```

---

### 7. Indeks Dokumentasi Proyek (`docs/`)

Dokumentasi teknis, metodologi, dan spesifikasi arsitektur tersimpan lengkap di direktori `docs/`:

1. [01_PRD.md](file:///home/digimon/Documents/KOMINFO/PROJEK/HANTU/docs/01_PRD.md) — Product Requirement Document & Spesifikasi Sistem Terpasang
2. [02_TECH_STACK.md](file:///home/digimon/Documents/KOMINFO/PROJEK/HANTU/docs/02_TECH_STACK.md) — Arsitektur Teknologi Native, Spesifikasi Port & Environment
3. [03_UI_UX.md](file:///home/digimon/Documents/KOMINFO/PROJEK/HANTU/docs/03_UI_UX.md) — Cetak Biru Antarmuka Executive Command Center & Interaksi Drilldown
4. [04_ARCHITECTURE.md](file:///home/digimon/Documents/KOMINFO/PROJEK/HANTU/docs/04_ARCHITECTURE.md) — Diagram Arsitektur, Alur Data, dan DDL Basis Data MySQL
5. [05_MILESTONES.md](file:///home/digimon/Documents/KOMINFO/PROJEK/HANTU/docs/05_MILESTONES.md) — Log Pencapaian Roadmap & Implementasi Riil
6. [06_STRATEGI_PENGUMPULAN_DATA_MASSAL.md](file:///home/digimon/Documents/KOMINFO/PROJEK/HANTU/docs/06_STRATEGI_PENGUMPULAN_DATA_MASSAL.md) — Hirarki Media Tier 1 s.d. 4 & Metodologi Ingestion
7. [07_METODOLOGI_STATISTIK_DAN_DATA_SCIENCE.md](file:///home/digimon/Documents/KOMINFO/PROJEK/HANTU/docs/07_METODOLOGI_STATISTIK_DAN_DATA_SCIENCE.md) — Formulasi Bayesian Smoothing & Wilson Score Interval
8. [08_ANALISIS_INTELIJEN_POLITIK_DAN_INSTANSI.md](file:///home/digimon/Documents/KOMINFO/PROJEK/HANTU/docs/08_ANALISIS_INTELIJEN_POLITIK_DAN_INSTANSI.md) — Teori Framing Isu, Social Network Analysis, dan Mitigasi Buzzer
9. [09_BLUEPRINT_TEKNOLOGI_LANJUTAN_DAN_ROADMAP.md](file:///home/digimon/Documents/KOMINFO/PROJEK/HANTU/docs/09_BLUEPRINT_TEKNOLOGI_LANJUTAN_DAN_ROADMAP.md) — Rencana Pengembangan Semantic Clustering & Local LLM
10. [10_SPESIFIKASI_KABUPATEN_JOMBANG.md](file:///home/digimon/Documents/KOMINFO/PROJEK/HANTU/docs/10_SPESIFIKASI_KABUPATEN_JOMBANG.md) — Sosio-Demografis 21 Kecamatan, 10 OPD Kunci & 4 PSN Jombang

---

### 8. Lisensi & Hak Cipta
Hak Cipta © 2026 Pemerintah Kabupaten Jombang / Diskominfo Kab. Jombang.  
Dibangun 100% menggunakan perangkat lunak *open-source* bebas biaya lisensi eksternal.

