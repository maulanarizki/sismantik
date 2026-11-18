# SPESIFIKASI MEDIA INTELLIGENCE KABUPATEN JOMBANG
## Sistem Pemantauan Opini Publik & Analisis Kebijakan Daerah Kabupaten Jombang
**Identitas Wilayah:** Kabupaten Jombang, Provinsi Jawa Timur (Kota Santri)  
**Pusat Pemerintahan:** Jl. KH. Wahid Hasyim No. 137, Kepanjen, Jombang  
**Pembagian Wilayah:** 21 Kecamatan | 302 Desa & 4 Kelurahan  
**Dataset Terindeks Saat Ini:** 2.952 Berita Terkurasi (Rentang 12 Bulan Terakhir)

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

### 4. Daftar Resmi 21 Kecamatan Se-Kabupaten Jombang (GADM 4.1 GeoJSON)

Pusat Komando Geospasial SISMANTIK PRO memetakan sentimen dan keluhan warga ke dalam 21 kecamatan resmi berikut:

1. **Kecamatan Jombang (Kota)** - Pusat pemerintahan dan episentrum volume berita.
2. **Kecamatan Ploso** - Kawasan industri Utara Brantas & Jembatan Ploso.
3. **Kecamatan Peterongan** - Jalur arteri nasional dan kawasan perdagangan.
4. **Kecamatan Diwek** - Sentra wisata religi Tebuireng dan Makam Gus Dur.
5. **Kecamatan Mojoagung** - Titik rawan banjir DAS Kali Gunting dan jalur perbatasan Mojokerto.
6. **Kecamatan Tembelang** - Sentra pertanian dan koridor pintu tol Jombang.
7. **Kecamatan Kesamben** - Wilayah pertanian timur dan perbatasan sungai Brantas.
8. **Kecamatan Sumobito** - Daerah agraris dan titik rawan genangan banjir.
9. **Kecamatan Perak** - Jalur utama barat perbatasan Kabupaten Nganjuk.
10. **Kecamatan Bandar Kedungmulyo** - Titik rawan banjir tanggul sungai dan perlintasan KA.
11. **Kecamatan Ngoro** - Daerah transisi selatan menuju Kediri.
12. **Kecamatan Bareng** - Wilayah sub-pegunungan penyangga sektor perkebunan.
13. **Kecamatan Wonosalam** - Episentrum ekowisata, perkebunan durian, kopi, dan cengkeh.
14. **Kecamatan Mojowarno** - Sentra mebel kayu dan kawasan agraris tengah.
15. **Kecamatan Gudo** - Pusat kerajinan manik-manik kaca dan kerukunan warga klenteng.
16. **Kecamatan Kabuh** - Kawasan industri utara, ruas jalan Kabuh-Tapen, dan dapur SPPG.
17. **Kecamatan Plandaan** - Kawasan perbukitan utara dan wilayah lumbung jagung/jati.
18. **Kecamatan Kudu** - Wilayah perbatasan utara Brantas menuju Lamongan.
19. **Kecamatan Ngusikan** - Wilayah perbatasan perbukitan kapur Kabupaten Mojokerto.
20. **Kecamatan Megaluh** - Jalur penyeberangan perahu tambang sungai Brantas.
21. **Kecamatan Jogoroto** - Sentra industri rumahan makanan olahan dan tahu.
