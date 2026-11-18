# ANALISIS INTELIJEN POLITIK & KEBIJAKAN INSTANSI
## Mentransformasi Data Teks Menjadi Wawasan Strategis Pengambilan Keputusan
**Perspektif:** Analis Politik Senior, Intelijen Kebijakan Publik, dan Tata Kelola Pemerintahan

---

### 1. Dari Media Monitoring ke Media Intelligence

Media monitoring tradisional hanya menjawab: *"Berapa banyak berita hari ini dan apa sentimennya?"*  
**SISMANTIK Enterprise** dirancang untuk menjawab pertanyaan strategis pimpinan:
* *"Narasi apa yang sedang dibangun lawan politik atau pihak oposisi untuk mendelegitimasi program kerja?"*
* *"Siapa aktor kunci (Key Opinion Leader / Influencer) yang menggerakkan opini massa?"*
* *"Apakah kritik ini murni jeritan warga atau kampanye terkoordinasi (Buzzer/Astroturfing)?"*
* *"Bagaimana dampak kebijakan baru (misal: Perda Parkir / Tarif RSUD / Relokasi Pasar) terhadap elektabilitas dan stabilitas daerah?"*

---

### 2. Teori Pembingkaian Isu (Narrative Framing Analysis)

Setiap berita atau postingan viral diklasifikasikan ke dalam 5 kerangka naratif (*Framing Dimensions*):

| Kerangka Narasi (*Frame*) | Indikator Teks | Risiko Politik / Instansi | Tindakan Rekomendasi Humas |
| :--- | :--- | :--- | :--- |
| **Administrative Incompetence** | *"Layanan lambat", "tidak becus", "server down", "bapuk", "anggaran sia-sia"* | Menyerang profesionalisme ASN dan kapasitas kepemimpinan kepala dinas. | Buka data SOP, tampilkan bukti perbaikan sistem, beri sanksi internal jika ada pelanggaran. |
| **Corruption & Morality** | *"Pungli", "bagi-bagi proyek", "nepotisme", "pejabat pamer", "korup"* | **Risiko Sangat Tinggi:** Memicu pemeriksaan APH (KPK/Kejaksaan/Polisi) dan kemarahan moral warga. | Audit investigasi terbuka oleh Inspektorat, transparansi lelang LPSE. |
| **Economic Burden** | *"Pajak naik", "retribusi mahal", "pedagang tergusur", "rakyat makin susah"* | Menghantam daya beli warga dan menciptakan resistensi terhadap perda pendapatan daerah. | Sosialisasi skema subsidi, relaksasi tarif, atau dialog publik asosiasi pedagang. |
| **Conflict & Polarization** | *"Bupati vs DPRD", "saling lempar tanggung jawab", "perang opini elit"* | Menurunkan kewibawaan pemerintah daerah dan menciptakan instabilitas politik lokal. | Rekonsiliasi tertutup antar-lembaga, pernyataan satu pintu (*single voice communication*). |
| **Human Interest** | *"Korban jalan rusak", "anak putus sekolah", "pasien ditolak", "rumah roboh"* | **Viralitas Emosional Tertinggi:** Memicu empati massa dan tekanan viral di media sosial. | Respon darurat instan (< 3 jam), kunjungan langsung kepala dinas/bupati ke lokasi. |

---

### 3. Pemetaan Aktor & Social Network Analysis (SNA)

Sistem membangun graf relasi percakapan untuk menganalisis topologi jaringan sosial:

```
                  [ Akun Media Berita ]
                            |
                     (Menyebarkan Isu)
                            |
                            v
                  [ KOL / Tokoh Kritis ] <--- (Inisiator Narasi)
                        /        \
              (Retweet)            (Komentar)
              /                        \
    [ Klaster Buzzer A ]           [ Warga Asli / Organik ]
    - Akun baru (< 3 bulan)        - Akun usia > 2 tahun
    - Pola teks kembar             - Riwayat aktivitas bervariasi
    - Aktif jam 01.00-04.00        - Interaksi dua arah natural
```

#### Metrik Sentralitas Graf:
1. **Degree Centrality:** Menemukan akun yang paling banyak me-mention atau me-retweet isu tertentu.
2. **Betweenness Centrality (Jembatan Narasi):** Menemukan akun perantara yang menghubungkan dua kubu yang berseberangan. Jika akun ini berhasil dirangkul melalui komunikasi persuasif, eskalasi konflik dapat diredam.
3. **Eigenvector Centrality:** Menemukan figur yang memiliki pengaruh besar karena terhubung dengan aktor-aktor penting lainnya.

---

### 4. Metodologi Deteksi Coordinated Inauthentic Behavior (CIB)

Untuk membedakan **aspirasi murni warga** dengan **serangan buzzer politik**, algoritma memeriksa 3 vektor anomali:

1. **Temporal Clustering (Sinkronisasi Waktu):**
   * Jika $\ge 15$ akun memposting narasi dengan kata kunci serupa dalam jendela waktu $< 180$ detik, probabilitas CIB meningkat $+45\%$.
2. **Text Duplication Entropy (MinHash LSH + Jaccard Index):**
   * Menghitung tingkat keunikan kalimat. Serangan buzzer terorganisir biasanya memiliki skor kemiripan teks $\ge 85\%$ akibat instruksi *broadcast template* yang sama.
3. **Akun Anomali Metadata (Activity Profile):**
   * Menghitung rasio: `Jumlah Postingan Politik` dibanding `Postingan Kehidupan Sehari-hari`. Akun buzzer murni memiliki rasio $> 0.90$.

---

### 5. Siklus Hidup Isu & Manajemen Krisis (Issue Lifecycle)

Setiap isu yang dipantau dikategorikan ke dalam status siklus hidupnya:

```
[ TAHAP 1: DORMANT ] ----> [ TAHAP 2: EMERGING ] ----> [ TAHAP 3: CRISIS ] ----> [ TAHAP 4: RESOLUTION ]
(Keluhan warga lokal di    (Mulai diliput media        (Masuk Tier 1 Nasional,    (Respon pimpinan diterima,
forum RT/komentar kecil)   lokal Tier 2 & akun gossip)  trending topic, tuntutan) sentimen kembali stabil)
SLA Aksi: < 24 Jam         SLA Aksi: < 6 Jam           SLA Aksi: < 1 Jam          SLA Aksi: Monitoring 48 Jam
```

* **Early Warning Indicator:** Notifikasi Telegram otomatis ditembakkan saat isu berpindah dari *Stage 1* ke *Stage 2* agar OPD teknis dapat menyelesaikannya di lapangan sebelum mencapai *Stage 3 (Krisis Terbuka)*.

---

### 6. Format Memorandum Intelijen Eksekutif (Executive Briefing Standard)

Sistem secara otomatis menyusun laporan berstandar **Presidential Daily Brief (PDB)** yang ringkas, padat, dan bebas jargon teknis untuk Pimpinan Daerah:

```
========================================================================================
MEMORANDUM INTELIJEN MEDIA & OPINI PUBLIK KABUPATEN
Tanggal Analisis : 16 November 2026 | Pukul 07:00 WIB
Tingkat Ancaman  : KUNING (Waspada Eskalasi Isu Infrastruktur Ruas Barat)
========================================================================================

1. SITUASI UTAMA (EXECUTIVE ESSENCE):
   Dalam 24 jam terakhir, sentimen publik berada pada skor NSS +38% (Cenderung Positif), 
   namun terdapat anomali lonjakan sentimen negatif tajam (+65% dari rata-rata) pada topik 
   "Kerusakan Jalan Ruas Poros Barat Kecamatan Kenjeran". Isu telah mulai diliput oleh 
   3 media lokal Tier 2 dan berpotensi naik ke Tier 1 nasional jika tidak segera direspon.

2. ANATOMI MASALAH & TARGET INSTANSI:
   - OPD Terdampak : Dinas PUPR (Sentimen: -0.92, Dominasi Emosi: Marah & Kecewa 74%).
   - Pemicu Krisis : Kecelakaan pengendara motor akibat lubang jalan yang tergenang banjir.
   - Analisis Aktor: Didominasi warga lokal organik (91%), belum terdeteksi amplifikasi buzzer.

3. REKOMENDASI TAKTIS LANGKAH PIMPINAN:
   [ ] Prioritas 1: Kadis PUPR menurunkan tim sapu lubang darurat ke lokasi hari ini sebelum pkl 12.00.
   [ ] Prioritas 2: Humas mendokumentasikan proses penanganan fisik dan merilis klarifikasi 
       progres anggaran preservasi jalan tahun 2026 melalui kanal media sosial resmi.
   [ ] Prioritas 3: Kadishub memasang rambu peringatan darurat sementara di titik-titik rawan.
========================================================================================
```

