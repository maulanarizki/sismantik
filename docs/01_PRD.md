# PRODUCT REQUIREMENT DOCUMENT (PRD)
## Proyek: SISMANTIK (Sistem Monitoring & Analisis Sentimen Publik Mandiri)
**Target Pengguna:** Pemerintah Daerah (Dinas Kominfo, Pimpinan Daerah, Organisasi Perangkat Daerah / OPD)  
**Lisensi & Ketergantungan:** 100% Open Source, Self-Hosted Native Local (Non-Docker), Zero Paid API Dependency.  
**Basis Data:** MySQL 8.x (`hantu` @ 127.0.0.1:3306)

---

### 1. Eksekutif Ringkasan (Executive Summary)
SISMANTIK adalah platform intelijen media dan opini publik mandiri (*self-hosted*) yang dirancang khusus untuk pemerintah daerah guna memantau pemberitaan media daring dan percakapan media sosial (Facebook, Instagram, TikTok, X, portal berita lokal/nasional). 

Sistem ini beroperasi **100% mandiri tanpa ketergantungan API berbayar pihak ketiga** (seperti Apify, BrightData, OpenAI API, atau Google Cloud NLP). Sistem dirancang berjalan langsung (*natively*) pada lingkungan mesin lokal/laptop tanpa overhead container Docker, menggunakan database MySQL lokal dan pustaka open-source (*IndoBERT, PyTorch CPU, Trafilatura, Playwright, MinHash*).

---

### 2. Latar Belakang & Permasalahan (Problem Statement)
1. **Ketergantungan Vendor & Biaya Tinggi:** Layanan *media monitoring* komersial mematok biaya langganan puluhan hingga ratusan juta rupiah per bulan dengan batasan kuota *mention*.
2. **Kerahasiaan & Kedaulatan Data Publik:** Data keluhan dan isu sensitif daerah tidak aman jika dialirkan ke cloud pihak ketiga publik tanpa tata kelola internal.
3. **Karakteristik Teks Lokal:** Opini warga di daerah didominasi oleh bahasa gaul, singkatan (*typo*), sarkasme, dan percampuran bahasa daerah (seperti Bahasa Jawa/Sunda). Model NLP luar negeri sering gagal menangkap sentimen asli.
4. **Fleksibilitas Deployment & Lingkungan Ringan:** Sistem harus bisa berjalan secara efisien langsung di lingkungan laptop/server lokal pengembang tanpa beban berat dari container Docker.

---

### 3. Tujuan & Tolok Ukur Keberhasilan (Objectives & KPIs)
* **Zero External API Cost:** Biaya operasional lisensi API eksternal = Rp 0,-.
* **Akurasi Sentimen Bahasa Indonesia & Daerah:** F1-Score model klasifikasi sentimen $\ge 88\%$ pada dataset komentar publik lokal.
* **Latency Ingestion & Scraping:** Waktu sinkronisasi manual untuk 50 artikel/komentar $\le 30$ detik pada mesin laptop lokal.
* **Local Native Execution:** Berjalan mulus di PHP 8.4, Python 3.13, MySQL 8, dan Redis lokal tanpa Docker.
* **Early Warning Time:** Notifikasi anomali krisis publik terkirim ke Telegram/WhatsApp dalam waktu $\le 3$ menit sejak isu terdeteksi.

---

### 4. Persona Pengguna (User Personas)
| Persona | Peran | Kebutuhan Utama |
| :--- | :--- | :--- |
| **Pimpinan Daerah (Bupati/Walikota/Sekda)** | *Executive Decision Maker* | Barometer sentimen publik harian, indeks kepuasan per OPD, ringkasan eksekutif 3 poin otomatis, sinyal krisis. |
| **Kepala & Tim Humas Diskominfo** | *Crisis & Issue Handler* | Dashboard interaktif real-time, monitoring sentimen berita & komentar, early warning via bot Telegram, deteksi buzzer/bot. |
| **Pimpinan OPD (Kadis PUPR, Dinkes, dll.)** | *Action Unit* | Matriks komplain spesifik instansinya (misal: jalan rusak, antrean RSUD, pungli bansos) dan lokasi kecamatan terkait. |
| **Administrator Sistem / Pengembang** | *Technical Operator* | Manajemen sumber data & endpoint dinamis (tambah website/medsos, on/off switch, setting selector, trigger sync manual dengan filter tanggal & limit). |

---

### 5. Ruang Lingkup Fitur (Functional Requirements)

#### 5.1 Modul Dynamic Source & Endpoint Management
* **FR-1.1 CRUD Data Sources:** Mengelola daftar portal berita (nasional, regional, blog warga) dan profil/grup publik media sosial (Facebook, Instagram, TikTok, X).
* **FR-1.2 Endpoint Configuration:**
  * Mode Web: URL RSS feed, URL Sitemap, atau URL halaman indeks berita dengan CSS/XPath Selector dinamis (judul, tanggal, isi artikel, pembuat).
  * Mode Medsos: URL akun/halaman/tagar publik, selector elemen komentar dan postingan.
* **FR-1.3 Dynamic Toggle (On/Off Switch):** Setiap endpoint memiliki status aktif/nonaktif untuk menghemat sumber daya laptop/server.
* **FR-1.4 Proxy & Header Customization:** Konfigurasi user-agent rotation dan session header jika diperlukan.

#### 5.2 Modul Sinkronisasi & Ingestion Kontrol
* **FR-2.1 Tombol Sinkronisasi Cepat ("Sync Now"):** Eksekusi scraping instan per endpoint atau secara massal (*batch*).
* **FR-2.2 Filter Rentang Tanggal (Date Range):** Pemilihan rentang tanggal publikasi (`start_date` s.d. `end_date`) sebelum proses pengambilan data.
* **FR-2.3 Limitasi Data per Endpoint (`max_items`):** Batasan jumlah data maksimal yang ditarik per sesi (contoh: 20, 50, 100, 250 item) agar ramah memori laptop.
* **FR-2.4 Live Ingestion Progress Bar:** Monitoring progres scraping dan pemrosesan data secara real-time via Server-Sent Events (SSE) / Polling.

#### 5.3 Modul Preprocessing & NLP Mandiri (Self-Hosted)
* **FR-3.1 Data Deduplication (MinHash LSH):** Mendeteksi dan mengelompokkan berita sindikasi yang disalin persis ke banyak portal media lokal agar tidak membiaskan sentimen.
* **FR-3.2 Kamus Slang & Bahasa Daerah:** Normalisasi kata tidak baku (misal: *"gak", "bgt", "dalan ajur", "suwun", "ngelu"*) ke format standar.
* **FR-3.3 Emoji Sentiment Converter:** Mengonversi emoji menjadi makna emosional teks dalam bahasa Indonesia (misal: 😡 $\rightarrow$ `[marah]`, 👍 $\rightarrow$ `[setuju]`).
* **FR-3.4 Klasifikasi Sentimen 3-Kelas:** Menggunakan model lokal fine-tuned `IndoBERT` untuk menentukan label (Positif, Netral, Negatif) serta skor keyakinan (-1.00 s.d. +1.00).
* **FR-3.5 Aspect-Based Sentiment Analysis (ABSA):** Memetakan sentimen spesifik ke instansi/OPD terkait dalam satu kalimat yang multi-isu.
* **FR-3.6 Emotion Analysis 6-Kelas:** Klasifikasi emosi (*Marah, Kecewa, Takut, Sedih, Apresiasi/Senang, Harapan*).
* **FR-3.7 Geotagging & Entity Extraction:** Ekstraksi otomatis nama kecamatan/kelurahan/jalan dari teks untuk pemetaan spasial.

#### 5.4 Modul Analitika & Visualisasi "Data Berbicara"
* **FR-4.1 Net Sentiment Score (NSS) Barometer:** Metrik utama persepsi warga ($NSS = \%Positif - \%Negatif$).
* **FR-4.2 Sentiment & Volume Timeline:** Grafik area interaktif membandingkan fluktuasi sentimen terhadap volume percakapan.
* **FR-4.3 OPD Performance Matrix:** Matriks komparasi rasio kepuasan warga antar dinas/instansi.
* **FR-4.4 Geospatial Heatmap:** Peta interaktif wilayah (Kecamatan/Kelurahan) dengan gradasi warna sentimen dan jumlah keluhan (Leaflet GeoJSON).
* **FR-4.5 Emotion Hexagon Radar:** Grafik radar emosi warga untuk membaca psikologi massa.
* **FR-4.6 N-Gram Topic & Word Cloud:** Visualisasi kata kunci keluhan dan apresiasi yang paling sering muncul.
* **FR-4.7 Deteksi Buzzer vs. Warga Organik:** Analisis duplikasi komentar dan pola anomali waktu.
* **FR-4.8 AI Executive Summary:** Ringkasan narasi 3 poin harian (ekstraksi poin utama & rekomendasi humas).

#### 5.5 Modul Early Warning & Krisis Alerting
* **FR-5.1 Threshold Engine:** Pemicu alert otomatis ketika sentimen negatif pada topik tertentu melonjak melebihi ambang batas.
* **FR-5.2 Self-Hosted Notification Delivery:** Pengiriman notifikasi darurat instan ke bot Telegram Pemda atau webhook WhatsApp lokal.
