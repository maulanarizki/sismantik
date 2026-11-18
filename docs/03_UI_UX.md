# UI/UX DESIGN & VISUALIZATION BLUEPRINT
## Proyek: SISMANTIK ("Data Speaks, Action-Driven")

---

### 1. Filosofi Desain: *"Data Speaks, Action-Driven"*
Visualisasi data media monitoring untuk instansi pemerintah tidak boleh hanya menjadi pajangan grafik statis yang membingungkan. Desain UI/UX SISMANTIK dibangun dengan 3 prinsip utama:
1. **Piramida Informasi (Executive to Operational):**
   * *Level 1 (Sekilas 5 Detik):* Pimpinan daerah langsung tahu: "Apakah hari ini publik tenang atau ada krisis?" (Lihat skor *Net Sentiment Score* dan Barometer).
   * *Level 2 (Analisis 1 Menit):* "Isu apa yang meledak dan dinas mana yang menjadi sorotan?" (Lihat *OPD Scorecard* dan *Trending Topics*).
   * *Level 3 (Investigasi Detail):* "Siapa yang mengeluh, di mana lokasinya, dan apa isi komentarnya?" (Lihat *Content Explorer* dan *Peta Geospasial*).
2. **Konteks Emosional (Beyond Polarity):**
   * Mengklasifikasikan bukan hanya positif/negatif, tetapi **anatomi emosi warga** (*Marah, Kecewa, Takut, Sedih, Apresiasi, Harapan*).
3. **Pemisahan Persepsi Organik vs. Buzzer:**
   * Menampilkan indikator anomali agar pimpinan tidak salah mengambil kebijakan akibat serangan bot/buzzer terkoordinasi.

---

### 2. Tema & Palet Warna Semantik

* **Gaya:** *Modern Executive Intelligence Dashboard* (Clean, High-Contrast, Dark/Light Mode).
* **Warna Sentimen:**
  * **Sentimen Positif:** Hijau Zamrud (`#10B981` / Emerald-500)
  * **Sentimen Netral:** Abu-abu Slate (`#64748B` / Slate-500)
  * **Sentimen Negatif:** Merah Kirmizi (`#EF4444` / Red-500)
* **Warna Emosi (Hexagon):**
  * **Marah (*Anger*):** Merah Membara (`#DC2626`)
  * **Kecewa (*Disappointment*):** Oranye Pekat (`#EA580C`)
  * **Takut (*Fear*):** Ungu Violet (`#7C3AED`)
  * **Sedih (*Sadness*):** Biru Kelabu (`#3B82F6`)
  * **Apresiasi (*Gratitude*):** Hijau Mint (`#059669`)
  * **Harapan (*Hope*):** Kuning Emas (`#F59E0B`)

---

### 3. Arsitektur Informasi & Navigasi (Sitemap Filament)

```
SISMANTIK Menu
├── 1. Executive Command Center (Dashboard Utama)
├── 2. OPD / Instansi Scorecard (Perbandingan Citra Antar Dinas)
├── 3. Peta Geospasial Wilayah (Peta Sebaran Isu Kecamatan/Desa)
├── 4. Anomali & Deteksi Buzzer (Audit Keaslian Opini Medsos)
├── 5. Penjelajah Konten (Katalog Berita & Komentar dengan Filter Lengkap)
├── 6. Manajemen Sumber Data (Dynamic Sources & Endpoints)
│   ├── Portal Berita & RSS
│   ├── Akun & Halaman Media Sosial (FB, IG, TikTok, X)
│   └── Endpoint API & Webhook
├── 7. Pusat Kontrol Sinkronisasi (Manual Sync, Date Range & Limits)
└── 8. Pengaturan Early Warning & Bot Notifikasi
```

---

### 4. Detail Tampilan & Wireframe Utama

#### Tampilan 1: Executive Command Center (Halaman Utama)

```
+----------------------------------------------------------------------------------------------------+
| SISMANTIK - Executive Command Center              [Filter: 7 Hari Terakhir v] [OPD: Semua v] [Sync]|
+----------------------------------------------------------------------------------------------------+
| [ METRIK UTAMA ]                                                                                   |
| +-------------------+  +--------------------+  +--------------------+  +-------------------------+ |
| | TOTAL PERCAKAPAN  |  | NET SENTIMENT (NSS)|  | INDEKS KEPUASAN    |  | STATUS KRISIS PUBLIK    | |
| | 14,820 Sebutan    |  | +42% (Positif)     |  | 7.8 / 10.0         |  | AMAN (1 Peringatan Dini)| |
| | ^ +12% dari pekan |  | v -5% dari pekan   |  | ^ +0.4 dari pekan  |  | Topik: "Antrean Bansos" | |
| +-------------------+  +--------------------+  +--------------------+  +-------------------------+ |
+----------------------------------------------------------------------------------------------------+
| [ GRAFIK TREN SENTIMEN & VOLUME HARIAN ]                      | [ RADAR EMOSI PUBLIK (6-AXIS) ]    |
|  (Grafik Area: Hijau=Positif, Abu=Netral, Merah=Negatif)      |        Apresiasi (45%)             |
|  (Garis Kuning: Total Volume Harian)                          |              /\                    |
|   Vol                                                         |      Harapan/  \Kecewa (15%)       |
|    ^     /\                                                   |            /    \                  |
|    |    /  \      /\   /\                                     |     Marah (20%)--Sedih (5%)        |
|    |___/____\____/__\_/__\_______                             |              \  /                  |
|    01  03   05   07   09   11 Nov                             |               \/ Takut (3%)        |
+---------------------------------------------------------------+------------------------------------+
| [ PERINGKAT CITRA OPD / INSTANSI ]                            | [ AI EXECUTIVE BRIEF (Ollama) ]    |
| Dinas Kesehatan   [========================|====] 85% Positif | 1. Isu Terhangat: Proyek perbaikan |
| Dinas Pendidikan  [===================|=========] 68% Positif |    jalan ruas barat diapresiasi    |
| Dinas Perhubungan [=============|===============] 48% Positif |    warga di Facebook (450 mention).|
| Dinas PUPR        [=======|=====================] 28% Positif | 2. Akar Negatif: Antrean faskes    |
| Disdukcapil       [=====================|=======] 74% Positif |    Puskesmas Cikarang lambat       |
|                                                               |    karena server Dukcapil offline. |
| [ PETA GEOSPASIAL KABUPATEN (LEAFLET) ]                       | 3. Rekomendasi: Kadis Kesehatan    |
| (Klik kecamatan untuk melihat isu paling panas)               |    perlu klarifikasi alur cadangan |
| [Kec. Utara: Aman]  [Kec. Tengah: WASPADA (Jalan Rusak)]      |    layanan manual via medsos.      |
+----------------------------------------------------------------------------------------------------+
```

---

#### Tampilan 2: Manajemen Sumber Data & Endpoint Dinamis (Dynamic Source Manager)

Halaman ini menjawab kebutuhan pengguna untuk menambah, mengedit, mematikan, atau menyalakan endpoint secara dinamis.

```
+----------------------------------------------------------------------------------------------------+
| MANAJEMEN SUMBER DATA & ENDPOINT DINAMIS                        [+ Tambah Sumber Data Baru]        |
+----------------------------------------------------------------------------------------------------+
| Cari: [..................]  Filter Tipe: [Semua v]  Status: [Semua v]  Urutan: [Terakhir Update v] |
+----------------------------------------------------------------------------------------------------+
| NAMA SUMBER        | TIPE       | ENDPOINT / URL TARGET       | JADWAL     | STATUS  | AKSI        |
+--------------------+------------+-----------------------------+------------+---------+-------------+
| Portal Berita Jatim| Berita RSS | https://jatimtimes.com/feed | Tiap 1 Jam | [ ON  ] | [Sync] [Edit|
| Detik Jatim Daerah | Berita Web | https://news.detik.com/jatim| Tiap 2 Jam | [ ON  ] | [Sync] [Edit|
| Suara Surabaya FB  | FB Public  | facebook.com/e100ss/posts   | Tiap 4 Jam | [ ON  ] | [Sync] [Edit|
| Humas Pemda IG     | IG Official| instagram.com/humaspemda/   | Tiap 30 Mnt| [ ON  ] | [Sync] [Edit|
| Lapor Warga FB Grp | FB Group   | facebook.com/groups/lapor...| Manual     | [ OFF ] | [Sync] [Edit|
| Forum Kaskus/X     | Search X   | query: "jalan rusak pemda"  | Tiap 3 Jam | [ ON  ] | [Sync] [Edit|
+----------------------------------------------------------------------------------------------------+
```

*Setiap baris memiliki:*
* **Toggle Switch On/Off:** Mengaktifkan/menonaktifkan scraping pada target tersebut seketika.
* **Tombol Cepat [Sync]:** Membuka modal sinkronisasi khusus endpoint tersebut.
* **Modal Edit Form:**
  * Nama Sumber & Platform (`Web News`, `Facebook`, `Instagram`, `TikTok`, `X`, `RSS Feed`).
  * Base URL / Endpoint URL.
  * Tipe Parser: `Trafilatura (Auto Article)`, `RSS Parser`, `Playwright Custom Selector`.
  * Custom Selector (jika non-RSS): CSS selector untuk Judul, Tanggal, Isi, dan Penulis.
  * Opsi Header / Cookies (untuk akun medsos lokal).

---

#### Tampilan 3: Modal Interaktif Sinkronisasi Cepat ("Sync Controller")

Ketika pengguna menekan tombol **"Sinkronisasi Sekarang"** (baik di header utama atau pada baris endpoint), modal modern berikut akan muncul:

```
+-------------------------------------------------------------------------+
| SINKRONISASI DATA MEDIA & OPINI PUBLIK                             [ X ]|
+-------------------------------------------------------------------------+
| Sumber Data Target:                                                     |
| [*] Seluruh Sumber Aktif (14 Target)                                    |
| [ ] Pilih Sumber Spesifik: [ Portal Berita Jatim, Humas IG      v ]     |
|                                                                         |
| Pengaturan Rentang Tanggal Pengambilan (Date Range):                    |
| Dari Tanggal:  [ 2026-11-04 ]    s.d.    Sampai Tanggal: [ 2026-11-11 ] |
| (Data yang ditarik hanya artikel/komentar yang terbit di rentang ini)  |
|                                                                         |
| Batas Maksimal Data per Endpoint (Max Items):                           |
| [ 50  ] Konten per Endpoint                                             |
| [Pilihan Cepat: 20 | 50 | 100 | 250 | Tanpa Batas]                      |
|                                                                         |
| Opsi Eksekusi:                                                          |
| [x] Jalankan Analisis NLP & Sentimen Otomatis setelah Scraping          |
| [x] Kirim Notifikasi Ringkasan Hasil Sinkronisasi ke Telegram Humas     |
+-------------------------------------------------------------------------+
| [ Batal ]                                      [ MULAI SINKRONISASI >> ]|
+-------------------------------------------------------------------------+
| PROGRES REAL-TIME (Tampil setelah tombol ditekan):                      |
| [=======================================>             ] 72%             |
| Status: Mengambil komentar dari Humas Pemda IG (42/50 konten)...        |
| Log: [15:02:10] Trafilatura selesai memproses 20 berita JatimTimes      |
|      [15:02:14] IndoBERT mengklasifikasikan 85 komentar masuk           |
+-------------------------------------------------------------------------+
```

---

#### Tampilan 4: Deteksi Anomali & Buzzer vs. Komentar Warga Asli

Widget visual untuk membuktikan apakah isu yang viral adalah serangan buzzer politik atau keluhan riil warga:

```
+----------------------------------------------------------------------------------------------------+
| AUDIT KONTEN: PERSEPSI ORGANIK VS. BUZZER / BOT                                                    |
+----------------------------------------------------------------------------------------------------+
| [ INDIKATOR INTEGRITAS OPINI ]                                                                     |
| Total Komentar Dianalisis: 3,420 Komentar                                                          |
| Komentar Warga Asli (Organik) : 2,980 (87.1%) [==================================    ]             |
| Terindikasi Bot/Buzzer         :   440 (12.9%) [=====                                 ]             |
+----------------------------------------------------------------------------------------------------+
| POLA KEJANGGALAN YANG TERDETEKSI:                                                                  |
| 1. Duplikasi Teks Ekstrem (Copy-Paste Campaign):                                                   |
|    Ditemukan 112 komentar dengan kemiripan teks > 95% (MinHash Cluster #04):                       |
|    "Bupati payah tidak becus urus pasar baru, harus ganti kepemimpinan!"                           |
|    -> Disebarkan oleh 8 akun berbeda dalam kurun waktu 4 menit di Facebook.                        |
|                                                                                                    |
| 2. Lonjakan Waktu Tidak Wajar (Velocity Burst):                                                    |
|    Terjadi lonjakan 350 komentar antara pukul 02:00 - 03:30 dini hari di akun Instagram Diskominfo. |
|    Status: Diberi bobot rendah (Down-Weighted) pada perhitungan Net Sentiment Score Pimpinan.       |
+----------------------------------------------------------------------------------------------------+
```

---

### 5. Bagaimana Visualisasi Ini Membuat *"Data Berbicara"*?

| Pertanyaan Pimpinan / Pengguna | Komponen Visual yang Menjawab | Nilai Strategis yang Diperoleh |
| :--- | :--- | :--- |
| *"Apakah warga kita sedang puas atau kecewa hari ini?"* | **Barometer Net Sentiment Score (NSS)** | Angka tunggal komprehensif $(-100\% \text{ s.d. } +100\%)$ yang mudah dipahami pimpinan tanpa membaca ribuan teks. |
| *"Kenapa warga marah? Isu apa penyebabnya?"* | **AI Executive Brief & N-Gram Word Cloud** | LLM lokal langsung mengekstrak 3 akar masalah utama (misal: "Antrean Faskes", "Jalan Berlubang"). |
| *"Dinas mana yang kinerjanya paling disorot negatif?"* | **OPD Performance Matrix** | Memberikan basis data objektif untuk rapat koordinasi pimpinan dengan para Kepala Dinas. |
| *"Di wilayah mana keluhan paling parah?"* | **Peta Geospasial Heatmap Kecamatan** | Dinas teknis (misal PUPR) tahu persis kecamatan mana yang harus diprioritaskan perbaikan fisiknya. |
| *"Apakah ini serangan buzzer lawan politik atau suara warga asli?"* | **Widget Audit Bot & Anomali** | Mencegah kepanikan birokrasi akibat *coordinated bot attacks* di media sosial. |

