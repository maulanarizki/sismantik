# SPESIFIKASI MEDIA INTELLIGENCE KABUPATEN JOMBANG
## Sistem Pemantauan Opini Publik & Analisis Kebijakan Daerah Kabupaten Jombang
**Identitas Wilayah:** Kabupaten Jombang, Provinsi Jawa Timur (Kota Santri)  
**Pusat Pemerintahan:** Jl. KH. Wahid Hasyim No. 137, Kepanjen, Jombang  
**Pembagian Wilayah:** 21 Kecamatan | 302 Desa & 4 Kelurahan  
**Dataset Terindeks Saat Ini:** 3.680 Berita Terkurasi (Rentang 12 Bulan Terakhir) — 2.452 Isu Lokal Pemkab & 1.228 Program Strategis Nasional (858 Geo Extractions)

---

### 1. Profil Sosio-Demografis & Karakteristik 4 Zona Jombang

Kabupaten Jombang memiliki posisi geografis yang sangat strategis di persimpangan koridor utama Jawa Timur (Surabaya–Madiun dan Malang–Tuban). Dalam analisis opini publik SISMANTIK PRO, wilayah ini terbagi ke dalam 4 zona strategis:

```
+----------------------------------------------------------------------------------------------------+
| 1. KAWASAN UTARA BRANTAS (Pusat Industri Baru & Akselerasi Infrastruktur)                          |
|    Kecamatan: Ploso, Kabuh, Plandaan, Kudu, Ngusikan                                               |
|    Karakteristik: Zona industri bertumbuh, pembangunan jalan ruas Kabuh-Tapen, Jembatan Ploso baru. |
|    Isu Utama: Proyek perbaikan jalan rusak, debu pabrik, keluhan program MBG sekolah, PJU malam.   |
+----------------------------------------------------------------------------------------------------+
| 2. KAWASAN KOTA & SENTRA LAYANAN (Pusat Pemerintahan, Layanan Publik & Pesantren)                  |
|    Kecamatan: Jombang (Kota), Peterongan, Diwek, Jogoroto, Megaluh                                 |
|    Karakteristik: Konsentrasi penduduk padat, pusat birokrasi, dan pesantren besar (Tebuireng, dll)|
|    Isu Utama: Antrean RSUD Jombang, pencetakan KTP/IKD di Disdukcapil, penataan PKL & Pasar PCN.  |
+----------------------------------------------------------------------------------------------------+
| 3. KAWASAN TIMUR (Episentrum Rawan Banjir Musiman & Jalur Logistik)                                |
|    Kecamatan: Mojoagung, Kesamben, Sumobito, Bandar Kedungmulyo                                    |
|    Karakteristik: Dilintasi Daerah Aliran Sungai (DAS) Kali Gunting & Kali Konto, jalur arteri KA. |
|    Isu Utama: Luapan banjir musiman kali gunting, tanggul jebol, kemacetan perlintasan sebidang.   |
+----------------------------------------------------------------------------------------------------+
| 4. KAWASAN SELATAN & PEGUNUNGAN (Sentra Agribisnis & Ekowisata)                                    |
|    Kecamatan: Wonosalam, Bareng, Mojowarno, Ngoro, Gudo, Perak                                     |
|    Karakteristik: Dataran tinggi pegunungan Anjasmoro, sentra perkebunan cengkeh, kopi, dan durian.|
|    Isu Utama: Event tahunan Kenduren Durian Wonosalam, bencana longsor, alokasi pupuk subsidi tebu.|
+----------------------------------------------------------------------------------------------------+
```

---

### 2. Sepuluh (10) OPD Kunci Pemkab Jombang yang Dipantau

| No | Nama Organisasi Perangkat Daerah (OPD) | Kode Sistem | Kata Kunci Pemantauan (*Keywords*) |
| :-: | :--- | :--- | :--- |
| 1 | **Dinas PUPR Jombang** | `dinas_pupr_jombang` | `jalan rusak, aspal, jembatan ploso, lubang jalan, tambal sulam, ploso-kabuh, kabuh-tapen, kali konto, drainase, trotoar wahid hasyim` |
| 2 | **Dinas Kesehatan (RSUD Jombang & RSUD Ploso)** | `dinkes_jombang` | `rsud jombang, rsud ploso, puskesmas, antrean bpjs, dokter, nakes, obat, rawat inap, stunting, posyandu` |
| 3 | **Disdukcapil Jombang** | `disdukcapil_jombang` | `ktp, ikd, kartu keluarga, akta, antrean dukcapil, mobil keliling dukcapil, server offline, suket` |
| 4 | **Dinas Pertanian Jombang** | `disperta_jombang` | `pupuk subsidi, petani tebu, pupuk langka, gabah, gagal panen, saluran irigasi, kenduren durian wonosalam` |
| 5 | **Dinas Pendidikan & Kebudayaan Jombang** | `disdikbud_jombang` | `sekolah, gedung sd rusak, guru honorer, ppdb zonasi, beasiswa pemkab, seragam sekolah, cagar budaya` |
| 6 | **Dinas Perhubungan Jombang** | `dishub_jombang` | `lampu pju mati, kemacetan mojoagung, palang pintu ka, parkir liar, uji kir, rambu lalu lintas` |
| 7 | **Dinas Lingkungan Hidup Jombang** | `dlh_jombang` | `alun-alun jombang, sampah, bau pabrik, amdal industri ploso, rth taman keplaksari, tpa banjardowo` |
| 8 | **BPBD Jombang** | `bpbd_jombang` | `banjir bandar kedungmulyo, kali gunting mojoagung, tanggul jebol, tanah longsor wonosalam, kekeringan kabuh` |
| 9 | **Disperindag Jombang** | `disperindag_jombang` | `pasar citra niaga, pcn, pkl alun-alun, operasi pasar, beras murah, tera ulang, revitalisasi pasar` |
| 10 | **Bagian Kesra Setda Jombang (Pesantren & Sosial)** | `kesra_jombang` | `pesantren, tebuireng, tambakberas, denanyar, darul ulum, beasiswa santri, kiai, haul gus dur, hibah madrasah` |

---

### 3. Empat (4) Pilar Pemantauan Program Strategis Nasional (PSN) di Jombang

Selain 10 dinas daerah, SISMANTIK PRO memantau secara spesifik 4 pilar kebijakan strategis nasional yang berdampak langsung di Kabupaten Jombang:

1. **Program Makan Bergizi Gratis (MBG):**
   * *Mitra Kebijakan:* Badan Gizi Nasional (BGN), Kodim 0814 Jombang, dan Satgas MBG Pemkab.
   * *Fokus Isu:* Kesiapan Dapur SPPG di Mangunan/Kabuh, uji coba menu bergizi anak sekolah di SD/SMPN, evaluasi SOP higienitas, dan pasokan bahan pangan dari BUMDes lokal.
2. **Koperasi Desa (Kopdes) & Ketahanan Pangan:**
   * *Mitra Kebijakan:* Kementerian Koperasi & Bulog Cabang Surabaya/Mojokerto-Jombang.
   * *Fokus Isu:* Revitalisasi Koperasi Desa Merah Putih, penyerapan gabah dan beras petani lokal Jombang oleh Bulog, serta stabilisasi harga pangan.
3. **Pemberantasan Korupsi & Penegakan Hukum Daerah (Tipikor):**
   * *Mitra Kebijakan:* Komisi Pemberantasan Korupsi (KPK) & Kejaksaan Negeri Jombang.
   * *Fokus Isu:* Penanganan dugaan korupsi proyek fisik, pengawasan tata kelola dana desa, akuntabilitas lelang LPSE, dan operasi saber pungli.
4. **Swasembada Pangan & Alsintan Nasional:**
   * *Mitra Kebijakan:* Kementerian Pertanian & BBWS Brantas.
   * *Fokus Isu:* Distribusi kuota pupuk subsidi, rehabilitasi jaringan irigasi sekunder Brantas, dan mekanisasi alat mesin pertanian (traktor/alsintan).

---

### 4. Status Kondusivitas Riil 21 Kecamatan Se-Kabupaten Jombang

Berdasarkan hasil analisis kalibrasi geospasial 12 bulan terakhir pada 858 berita teritorial, status 21 kecamatan terpetakan sebagai berikut:

| Status Teritorial | Warna Choropleth | Kriteria Skor | Daftar Kecamatan Terpetakan |
| :--- | :---: | :--- | :--- |
| **Sangat Kondusif** | Hijau Emerald (`#10b981`) | $\text{NSS} \ge +15\%$ | **Kecamatan Jombang (Kota)** |
| **Kondusif & Stabil** | Biru Cyan (`#06b6d4`) | $0\% \le \text{NSS} < +15\%$ | **Kecamatan Wonosalam**, **Kecamatan Ploso**, **Kecamatan Gudo** |
| **Perlu Atensi** | Kuning Amber (`#f59e0b`) | $-30\% \le \text{NSS} < 0\%$ | **Kecamatan Peterongan**, **Kecamatan Mojoagung**, **Kecamatan Kabuh**, **Kecamatan Bareng**, **Kecamatan Diwek**, **Kecamatan Kesamben**, **Kecamatan Kudu** |
| **Rawan Keluhan** | Merah Kirmizi (`#f43f5e`) | Rasio Negatif $\ge 40\%$ atau $\text{NSS} < -30\%$ | **Kecamatan Bandar Kedungmulyo**, **Kecamatan Sumobito**, **Kecamatan Perak**, **Kecamatan Tembelang**, **Kecamatan Megaluh**, **Kecamatan Mojowarno**, **Kecamatan Ngoro**, **Kecamatan Plandaan**, **Kecamatan Jogoroto**, **Kecamatan Ngusikan** |

---

### 5. Basis Data Kata Henti Lokal Jombang (*Stopwords*)

Untuk menyaring kebisingan kata pada Wordcloud dan Topic Modelling, sistem mengelola kata henti di database `stopwords` yang terbagi dalam 6 klaster:

1. **Klaster Daerah:** `kabupaten, jombang, pemkab, pemda, pemerintah, kecamatan, kelurahan, desa, jatim, jawa, timur`.
2. **Klaster Waktu:** `senin, selasa, rabu, kamis, jumat, sabtu, minggu`, nama bulan (`januari` s.d. `desember`, `jan` s.d. `des`), serta waktu (`pagi, siang, sore, malam`).
3. **Klaster Angka:** `ribu, juta, miliar, triliun, persen, rupiah`.
4. **Klaster Sapaan & Gelar:** `bapak, ibu, pak, bu, mas, mbak, kak, saudara, saudari, prof, drs, dra, dr, hj`.
5. **Klaster Aksi Umum:** `gelar, lakukan, melakukan, laksanakan, adakan, hadiri, hadir, mulai`.
6. **Klaster Umum:** Kata hubung dan kata tugas bahasa Indonesia standar.
