# STRATEGI PENGUMPULAN DATA MASSAL & HIRARKI MEDIA
## SISMANTIK Enterprise Intelligence Ingestion Framework
**Klasifikasi:** Media Lokal, Nasional, Internasional, dan Media Sosial  
**Prinsip Desain:** 100% Open Source, Zero Paid API, High-Throughput & Anti-Bot Resilient

---

### 1. Taksonomi & Hirarki Sumber Media (Media Hierarchy Matrix)

Dalam analisis intelijen media untuk institusi pemerintah dan politik, tidak semua berita memiliki bobot kredibilitas dan dampak opini yang sama. Data harus diklasifikasikan ke dalam 4 tingkatan (*Tiers*) dengan bobot pengaruh (*Impact Weight* $\omega_t$):

```
+---------------------------------------------------------------------------------------+
| TIER 1: MEDIA NASIONAL MAINSTREAM & KANTOR BERITA (Bobot: 1.0)                        |
| Antara News, Kompas.com, Detik.com, Tempo.co, CNN Indonesia, CNBC Indonesia, Bisnis.com|
+---------------------------------------------------------------------------------------+
| TIER 2: MEDIA REGIONAL & LOKAL TERAKREDITASI DEWAN PERS (Bobot: 0.85)                 |
| Jawa Pos, Suara Surabaya, Radar Daerah, Tribun Network Daerah, Pikiran Rakyat, dll.   |
+---------------------------------------------------------------------------------------+
| TIER 3: MEDIA INTERNASIONAL & MULTILATERAL (Bobot: 0.90)                              |
| Reuters, Bloomberg, BBC, Nikkei Asia, The Diplomat, Channel News Asia (CNA)          |
+---------------------------------------------------------------------------------------+
| TIER 4: MEDIA SOSIAL, FORUM WARGA & CITIZEN JOURNALISM (Bobot: 0.70)                  |
| Facebook Groups/Pages, Instagram Publik, TikTok Comments, X/Twitter, Forum Kaskus     |
+---------------------------------------------------------------------------------------+
```

#### A. Sumber Media Nasional (Tier 1)
* **Karakteristik:** Menentukan agenda kebijakan publik nasional (*Agenda-Setting Power*). Jika isu daerah masuk ke Tier 1, isu tersebut telah mencapai skala krisis atau prestasi luar biasa.
* **Mekanisme Penarikan:**
  * **RSS Feed Resmi:** Menarik feed kategori Politik, Hukum, Daerah, Bisnis, dan Metro.
  * **Sitemap.xml Ingestion:** Membaca sitemap berita harian untuk mengindeks seluruh artikel dalam 24 jam tanpa missing data.
  * **Contoh Target:**
    * `https://www.antaranews.com/rss/terkini.xml`
    * `https://news.detik.com/rss`
    * `https://nasional.kompas.com/rss`
    * `https://rss.tempo.co/nasional`
    * `https://www.cnnindonesia.com/nasional/rss`
    * `https://www.cnbcindonesia.com/news/rss`

#### B. Sumber Media Lokal & Regional (Tier 2)
* **Karakteristik:** Merupakan cermin langsung persoalan teknis di lapangan (jalan berlubang, antrean puskesmas, sengketa lahan, bansos, pilkada). Sangat vital bagi OPD teknis (PUPR, Dinkes, Dinsos).
* **Mekanisme Penarikan:**
  * **Targeted Web Scraper:** Menggunakan Trafilatura dengan selector spesifik per portal.
  * **Google News Regional Aggregator Query:** Penarikan dinamis via RSS Google News berdasarkan kata kunci kabupaten/kota (misal: `https://news.google.com/rss/search?q={nama_kabupaten}+when:7d&hl=id&gl=ID&ceid=ID:id`).
  * **Jaringan Tribun & Radar:** Crawling terstruktur pada sub-domain daerah.

#### C. Sumber Media Internasional (Tier 3)
* **Karakteristik:** Memantau persepsi global terhadap daerah, terutama terkait:
  * Iklim investasi, industri, dan hilirisasi tambang/pertanian.
  * Isu tata kelola lingkungan (*ESG / Deforestasi / Emisi*).
  * Isu stabilitas politik, keamanan, dan hak asasi manusia.
* **Mekanisme Penarikan:**
  * RSS Internasional: Reuters Asia, BBC Indonesia/Asia, Bloomberg Asia Pacific, Nikkei Asia.
  * Filter Keyword Geografis: Penyaringan berbasis *Entity Recognition* untuk menangkap artikel luar negeri yang menyebut nama provinsi/kabupaten/tokoh kunci daerah.

#### D. Media Sosial & Forum Publik Warga (Tier 4)
* **Karakteristik:** Sumber sentimen paling cepat bereaksi (*real-time*), menggunakan bahasa percakapan lokal/dialek, namun memiliki rasio kebisingan (*noise*) dan risiko bot paling tinggi.
* **Platform Target:**
  1. **Instagram (Humas & Akun Komunitas Warga):** Komentar warga pada postingan berita lokal dan pengumuman instansi.
  2. **Facebook (Pages & Public Groups):** Grup warga (contoh: *"Lapor Warga [Nama Kota]"*, *"Warga Peduli [Daerah]"*).
  3. **TikTok (Kolom Komentar Video Viral):** Sangat efektif untuk isu yang diviralkan oleh generasi muda/kreator lokal.
  4. **X / Twitter:** Pencarian kata kunci isu sensitif daerah.
  5. **YouTube (Komentar Liputan Berita Lokal):** Kanal media TV regional di YouTube.

---

### 2. Arsitektur Crawler Massal (High-Scale Scraper Fleet)

Untuk menangani ratusan portal web dan media sosial tanpa lisensi API berbayar, arsitektur crawler ditingkatkan menjadi model terdistribusi:

```
                                [ Dynamic Source Registry ]
                                (MySQL: source_endpoints)
                                             |
                                [ Ingestion Dispatcher ]
                                             |
                  +--------------------------+--------------------------+
                  |                                                     |
       [ High-Speed Feeders ]                                [ Stealth Browser Cluster ]
       (Scrapy + Trafilatura)                                (Playwright Stealth Workers)
                  |                                                     |
      (Untuk Portal RSS / Web Biasa)                         (Untuk Cloudflare / JS-Heavy / Medsos)
      - Rate: 1,000 artikel/menit                            - Rate: 60 halaman/menit
      - CPU/RAM: Sangat Ringan                               - Headless Chromium + Tor Proxy Rotation
                  |                                                     |
                  +--------------------------+--------------------------+
                                             |
                                [ Content Normalizer ]
                                (Demoji + Dialek + MinHash)
                                             |
                                  [ Queue: nlp_pipeline ]
```

---

### 3. Trik Anti-Bot & Bypass Proteksi (100% Free & Open Source)

1. **Tor Proxy Network Rotation:**
   * Menjalankan daemon `tor` lokal pada laptop/server dengan antarmuka SOCKS5 (`127.0.0.1:9050`).
   * Setiap request scraping media sosial dapat merotasi IP baru secara instan melalui sinyal kontrol `AUTHENTICATE "" -> SIGNAL NEWNYM` tanpa biaya langganan proxy residential.
2. **Playwright Stealth Fingerprint Masking:**
   * Injeksi evasions: menghapus atribut `navigator.webdriver`, memalsukan canvas fingerprint, merotasi resolusi viewport acak, dan menyelaraskan WebGL vendor dengan kartu grafis asli laptop.
3. **Session Cookies Harvester:**
   * Modul penyimpan sesi akun penjelajah publik (*burner account*) lokal terenkripsi di folder `engine-nlp/sessions/` untuk membaca komentar publik tanpa login berulang.
4. **Adaptive Exponential Backoff with Jitter:**
   * Mencegah pemblokiran IP target dengan penundaan dinamis: $Delay = Base \times 2^{retry} + \text{RandomJitter}(0.5, 2.0)$.
5. **Universal RSS-Bridge Integration:**
   * Menggunakan engine open-source **RSS-Bridge** (dapat di-host mandiri via PHP) untuk mengubah postingan Facebook publik, akun Instagram publik, tagar TikTok, dan akun X menjadi XML RSS standar yang ramah CPU.

---

### 4. Engine Penemuan Sumber Dinamis (Dynamic Source Discovery)

Sistem tidak hanya menunggu input manual admin, melainkan dilengkapi **Auto-Discovery Worker**:
1. Menjalankan pencarian terjadwal di mesin perayap berita dengan kata kunci instansi/kepala daerah/isu daerah.
2. Mengekstrak domain berita baru yang belum terdaftar di tabel `sources`.
3. Memeriksa keberadaan RSS feed atau sitemap pada domain baru tersebut.
4. Menyimpan domain baru ke antrean verifikasi admin dengan status *Draft / Review Recommended*.

