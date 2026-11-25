# UI/UX DESIGN & INTERACTION BLUEPRINT
## Proyek: SISMANTIK PRO ("Executive Command Center & High-Contrast Visuals")

---

### 1. Filosofi Desain: *"Executive War-Room, Actionable & High-Contrast"*
Antarmuka SISMANTIK PRO dirancang khusus untuk memenuhi standar pimpinan eksekutif pemerintah daerah dan tim humas Diskominfo. Desain ini menganut 4 prinsip utama:
1. **Kejelasan & Kontras Tinggi (*High Contrast, Zero Bleached Text*):**
   * Mengeliminasi warna pudar dan teks transparan.
   * Pada mode terang (*Light Mode*), semua teks menggunakan warna solid `#0f172a` (Slate-900) dan `#1e293b` (Slate-800) pada kartu putih.
   * Pada mode gelap (*Dark Mode*), semua teks menggunakan `#ffffff` atau `#f8fafc` pada latar belakang `#020617` / `#0f172a`.
2. **Piramida Informasi Terarah (*3-Second to 3-Minute Rule*):**
   * *3 Detik Pertama:* Pimpinan melihat 4 Master KPI (Volume, Net Sentiment Score, Trust Index, Bot/Anomaly).
   * *30 Detik Berikutnya:* Pimpinan membaca 4 Pilar Strategis Nasional (MBG, Kopdes, Tipikor, Swasembada) dan tren 12 bulan.
   * *3 Menit Investigasi:* Pimpinan mengecek Rapor 10 OPD, Matriks Framing Isu, Pusat Analisis Kata Kunci & Wordcloud Sentimen, atau Peta GIS 21 Kecamatan.
3. **Interaktivitas Menyeluruh (*One-Click Article Drilldown*):**
   * Setiap angka, baris OPD, kartu PSN, klaster framing isu, irisan emosi, maupun poligon kecamatan dapat **diklik langsung** untuk memunculkan modal daftar artikel berita asli.
4. **Independensi Komponen (*100% Bespoke, Zero Filament UI*):**
   * Membangun tampilan menggunakan Laravel Blade native + Tailwind CSS + Alpine.js untuk fleksibilitas tata letak, kecepatan muat halaman, dan estetika modern tanpa batasan widget grid Filament.

---

### 2. Palet Warna & Desain Semantik

#### A. Skema Warna Sentimen
* **Sentimen Positif:** Hijau Zamrud (`#10b981` / Emerald-500) & Background Lembut (`#ecfdf5` / `#064e3b20`)
* **Sentimen Netral:** Abu-abu Slate Netral (`#64748b` / Slate-500)
* **Sentimen Negatif:** Merah Kirmizi (`#ef4444` / Rose-500) & Background Lembut (`#fff1f2` / `#88133720`)

#### B. Skema 6 Spektrum Emosi Publik
* **Apresiasi / Senang:** Hijau Zamrud (`#10b981`)
* **Harapan / Doa:** Kuning Emas Amber (`#f59e0b`)
* **Kecewa / Frustrasi:** Oranye Pekat (`#ea580c`)
* **Marah / Geram:** Merah Kirmizi (`#ef4444`)
* **Sedih / Prihatin:** Biru Cerah (`#3b82f6`)
* **Takut / Cemas:** Ungu Violet (`#8b5cf6`)

#### C. Skema Choropleth Peta Geospasial 21 Kecamatan
* **Sangat Kondusif (NSS $\ge +15\%$):** Hijau Emerald (`#10b981`)
* **Kondusif & Stabil ($0\% \le \text{NSS} < +15\%$):** Biru Cyan (`#06b6d4`)
* **Perlu Atensi ($-30\% \le \text{NSS} < 0\%$):** Kuning Amber (`#f59e0b`)
* **Rawan Keluhan (Rasio Negatif $\ge 40\%$ atau $\text{NSS} < -30\%$):** Merah Crimson (`#f43f5e`)

---

### 3. Struktur Layout & Navigasi (Master Layout)

```
+---------------------------------------------------------------------------------------------------------+
| [LOGO] SISMANTIK PRO KAB. JOMBANG  |  [● RADAR LIVE KAB. JOMBANG] [WIB CLOCK]  | [Tarik Berita] [Replace] [🌓]|
+---------------------------------------------------------------------------------------------------------+
| SIDEBAR NAVIGASI           | AREA KONTEN UTAMA                                                          |
| ├── PUSAT KOMANDO          |                                                                            |
| │   ├── Dashboard Eksekutif| [HEADER & FILTER: Preset 1B/3B/6B/1T | Custom Date | Scope Filter]          |
| │   └── Peta Geospasial 21 |                                                                            |
| ├── PEMANTAUAN DATA        | [4 MASTER KPI CARDS: Total Berita, NSS %, Indeks Kepuasan, Bot Anomali]    |
| │   ├── Katalog Berita     |                                                                            |
| │   └── Riwayat Sinkron    | [4 PILAR STRATEGIS NASIONAL (PSN): MBG, Kopdes, Tipikor, Swasembada Pangan]|
| ├── KONFIGURASI            |                                                                            |
| │   ├── Daftar OPD & Kunci | [GRAFIK TREN SENTIMEN 12 BULAN TERAKHIR (Chart.js Area Chart)]             |
| │   ├── Manajemen Stopwords|                                                                            |
| │   └── Sumber & Endpoint  | [PUSAT ANALISIS KATA KUNCI & WORDCLOUD SENTIMEN: Positif | Negatif | Topik]|
| └── DOKUMENTASI            |                                                                            |
|     └── Panduan Sistem     | [KOLOM KIRI: Rapor 10 OPD]          | [KOLOM KANAN: Matriks Framing Isu]   |
|                            |                                                                            |
|                            | [KOLOM KIRI: Anatomi 6 Emosi Donut] | [KOLOM KANAN: Bilik Geospasial Top 5]|
|                            |                                                                            |
|                            | [EXECUTIVE AI DIGEST: Presidential Daily Brief (PDB) Diskominfo Jombang]   |
+---------------------------------------------------------------------------------------------------------+
```

---

### 4. Rincian Antarmuka Tiap Halaman

#### Halaman 1: Dashboard Eksekutif Komando (`/dashboard` & `/`)
1. **Header Topbar:**
   * Logo SISMANTIK PRO dengan animasi *pulsing radar*.
   * Indikator status operasional: *"RADAR LIVE • KAB. JOMBANG"*.
   * Jam digital real-time (WIB) dengan tanggal bahasa Indonesia.
   * Tombol aksi instan *"Tarik Berita"* (inkremental) dan *"Replace Total"* (reset 12 bulan).
   * Tombol 1-klik *Instant Dark/Light Mode Switcher*.
2. **Filter Bar:**
   * Tombol pintas preset periode (1 Bulan, 3 Bulan, 6 Bulan, 1 Tahun).
   * Pemilih tanggal kustom (*Start Date* s.d. *End Date*).
   * Dropdown ruang lingkup: *Semua Isu*, *Lokal Pemkab*, *Strategis Nasional*.
3. **4 Master KPI Cards:**
   * Total Berita Terpublikasi (3.680 konten), Net Sentiment Score (NSS), Indeks Kepuasan Warga, dan Bot/Anomali.
4. **4 Kartu Program Strategis Nasional (PSN):**
   * Disertai badge lembaga (BGN, Kemenkop, KPK, Kementan), ringkasan fokus Jombang, volume sebutan, skor NSS, dan tombol *"Buka Artikel Terkait"* yang memicu modal drilldown.
5. **Grafik Tren 12 Bulan (Chart.js):**
   * Kurva area halus (*spline curve*) yang menampilkan perbandingan Total Volume Berita (Abu-abu), Sentimen Positif (Hijau), dan Sentimen Negatif (Merah) per bulan berdasarkan tanggal rilis asli (`published_at`).
6. **Pusat Analisis Frekuensi Kata Kunci & Wordcloud Sentimen:**
   * Bilik interaktif dengan 3 tab navigasi: *Sentimen Positif*, *Sentimen Negatif*, dan *Isu Topik Dominan*.
   * Menampilkan kumpulan kata kunci dengan ukuran font dinamis sesuai bobot kemunculan (*term frequency*), disertai badge jumlah sebutan.
   * Dilengkapi filtrasi stopwords dinamis berbasis database untuk menyaring kata tugas, nama daerah, gelar sapaan, dan satuan angka agar menghasilkan representasi isu murni.
7. **Rapor Kinerja 10 OPD:**
   * Menampilkan peringkat OPD (PUPR, Dinkes, Disdukcapil, Disperta, Disdikbud, dll.) dengan volume berita, skor NSS, dan *micro-progress bar* proporsi sentimen. Baris OPD dapat diklik langsung untuk memicu modal berita.
8. **Matriks Framing Isu & Aduan Warga:**
   * 5 Klaster isu keluhan warga (Infrastruktur, Kesehatan, Pertanian, Pendidikan, Hukum). Setiap kartu menampilkan contoh isu populer, jumlah berita, skor NSS, dan dapat diklik langsung.
9. **Anatomi 6 Spektrum Emosi:**
   * Diagram donat (*donut chart*) interaktif di sisi kiri dan daftar breakdown persentase di sisi kanan.
10. **Bilik Geospasial (Preview):**
    * Top 5 kecamatan teratas beserta status sentimen teritorial, plus tautan langsung menuju `/peta`.
11. **Executive AI Digest:**
    * Kotak gelap futuristik berisi 3 poin analisis komprehensif: *Ringkasan Narasi Dominan*, *Potensi Kerawanan Isu*, dan *Rekomendasi Tindakan Strategis Diskominfo*.

---

#### Halaman 2: Pusat Komando Geospasial 21 Kecamatan (`/peta`)
1. **5 Header Kartu Metrik Teritorial:**
   * *Total Berita Teritori:* Akumulasi berita terekstraksi ke wilayah kecamatan (858 Berita).
   * *Sangat Kondusif:* Jumlah wilayah dengan NSS $\ge +15\%$ (Hijau Emerald).
   * *Kondusif & Stabil:* Jumlah wilayah dengan $0\% \le \text{NSS} < +15\%$ (Biru Cyan).
   * *Perlu Atensi:* Jumlah wilayah dengan $-30\% \le \text{NSS} < 0\%$ (Kuning Amber).
   * *Rawan Keluhan:* Jumlah wilayah dengan Rasio Negatif $\ge 40\%$ atau NSS $< -30\%$ (Merah Kirmizi).
2. **Layar Peta Penuh (*Full-width GIS View*):**
   * Memuat 21 batas poligon resmi GADM 4.1 Kabupaten Jombang via GeoJSON lokal.
   * Pencocokan fitur presisi menggunakan atribut `district_name` yang dinormalisasi otomatis.
   * Pewarnaan tematik (*Choropleth*) sesuai tingkat stabilitas sentimen terkalibrasi.
   * *Hover Tooltip:* Menampilkan nama kecamatan, total berita teritori, dan skor NSS saat kursor diarahkan ke poligon.
   * *Mouse Click:* Otomatis memperbesar (*zoom*) ke kecamatan yang diklik dan memperbarui data pada panel inspektor di bawah peta.
3. **Pilihan Basemap Mandiri:**
   * *OpenStreetMap:* Peta jalan standar, sangat jernih dan bebas watermark.
   * *Esri Canvas:* Peta monokrom abu-abu elegan untuk presentasi eksekutif.
   * *Esri Satellite:* Citra satelit resolusi tinggi untuk memantau kontur fisik wilayah.
4. **Bilah Kontrol Teritorial:**
   * Dropdown *"Lompat ke Wilayah"*: Memungkinkan pengguna memilih salah satu dari 21 kecamatan secara instan.
   * Tombol *"Reset Posisi Peta"*: Mengembalikan tampilan ke titik tengah Kabupaten Jombang (`[-7.550, 112.235]`).
5. **Inspektor Wilayah Terpilih (HUD) & Live Article Feed:**
   * Menampilkan profil kecamatan, status sentimen, total berita teritori, skor NSS, komposisi Positif/Negatif/Netral, dan rekomendasi respon cepat.
   * **Feed Dokumen Berita Teritorial Langsung:** Daftar artikel berita spesifik kecamatan terkait ditampilkan langsung di dalam panel inspektor dengan judul, sumber media, tanggal rilis, cuplikan isi, badge sentimen, dan tautan langsung ke artikel asli.
6. **Leaderboard 21 Kecamatan:**
   * Tab interaktif untuk mengurutkan 21 kecamatan berdasarkan:
     * *Volume:* Kecamatan dengan pemberitaan terbanyak.
     * *Keluhan:* Kecamatan dengan isu negatif tertinggi.
     * *Positif:* Kecamatan dengan apresiasi pembangunan tertinggi.

---

#### Halaman 3: Manajemen Stopwords Database (`/stopwords`)
1. **Statistik Stopwords & Tab Kategori:**
   * 6 Tab filter kategori: *Semua*, *Daerah*, *Waktu*, *Angka*, *Sapaan & Gelar*, *Aksi Umum*, dan *Umum*.
   * Kartu ringkasan total kata aktif dan jumlah kata per kategori.
2. **Form Penambahan Kata Cepat (*Quick Add*):**
   * Input teks kata/frasa baru dengan pemilihan kategori dan tombol submit instan.
3. **Tabel Manajemen Kata Henti:**
   * Daftar kata henti dilengkapi badge kategori berwarna semantik, tanggal penambahan, tombol toggle switch status aktif/non-aktif, dan tombol hapus konfirmator.
4. **Sinkronisasi Otomatis:**
   * Perubahan langsung terefleksi ke endpoint `GET /api/stopwords` yang digunakan oleh modul NLP dan agregasi wordcloud.

---

#### Halaman 4: Modal Drilldown Berita Global (`#article-drilldown-modal`)
* Terhubung secara reaktif melalui event bus Alpine.js: `$dispatch('open-article-drilldown', {...})`.
* Menembak endpoint JSON `POST /api/drilldown-articles`.
* Mendukung filter otomatis berdasarkan kata kunci, OPD, kecamatan, sentimen, maupun rentang tanggal.
* Memiliki kolom pencarian cepat di dalam modal (*live filter*).
* Menampilkan judul artikel, kutipan teks bersih, label sentimen/emosi, nama media, tanggal rilis, dan tautan langsung untuk membuka artikel asli di tab baru.

---

#### Halaman 5: Proteksi Interaksi Modal Replace Data Total
* **Modal Konfirmasi Bertingkat:** Menjelaskan secara transparan bahwa proses mencakup pembersihan total data lama dan penarikan data baru 12 bulan terakhir.
* **Loading State & Anti-Interupsi:**
  * Tombol konfirmasi otomatis dinonaktifkan (*disabled*) saat ditekan untuk mencegah klik ganda (*double submit*).
  * Menampilkan spinner loading animasi dan pesan peringatan: *"Proses sinkronisasi 12 bulan sedang berjalan (estimasi 3-5 menit). Mohon jangan menutup halaman ini."*
  * Server Laravel mengeksekusi dengan `set_time_limit(0)` dan `ignore_user_abort(true)` sehingga proses dijamin tuntas tanpa terkena batas waktu timeout PHP 30 detik.
