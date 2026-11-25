# METODOLOGI STATISTIK & DATA SCIENCE UNTUK INTELIJEN MEDIA
## Menghasilkan "Data Matang" yang Sahih, Robust, dan Bebas Bias
**Disiplin:** Statistika Terapan, Time-Series Econometrics, dan Natural Language Processing

---

### 1. Masalah Utama Analisis Sentimen Mentah (Raw Sentiment Flaws)

Sistem media monitoring konvensional sering menghasilkan data mentah yang menyesatkan pimpinan karena 4 kelemahan fatal:
1. **Distorsi Sampel Kecil (Small-N Distortion):** Jika suatu topik hanya memiliki 2 sebutan dan keduanya negatif, skor sentimen menjadi $-100\%$, menimbulkan kepanikan palsu (*false alarm*).
2. **Ketiadaan Rentang Keyakinan (No Confidence Intervals):** Hanya menyajikan angka tunggal (point estimate) tanpa menginformasikan margin of error.
3. **Penyamaan Bobot Media:** Satu komentar anonim di Facebook dihitung sama nilainya dengan berita investigasi headline koran nasional.
4. **Bias Minoritas Vokal (Vocal Minority Bias):** $10\%$ pengguna internet hiperaktif memproduksi $80\%$ kebisingan, mendistorsi suara *silent majority* yang tenang.

---

### 2. Formulasi Matematis & Metodologi Statistik SISMANTIK

#### A. Bayesian Sentiment Smoothing (Laplace Shrinkage)
Untuk mencegah lonjakan ekstrem pada data bervolume kecil, sistem menerapkan estimasi Bayesian dengan distribusi prior global:

$$\hat{P}_{pos} = \frac{n_{pos} + \alpha}{N + \alpha + \beta + \gamma}, \quad \hat{P}_{neg} = \frac{n_{neg} + \beta}{N + \alpha + \beta + \gamma}$$

Di mana:
* $N = n_{pos} + n_{neu} + n_{neg}$ (total sebutan aktual).
* $\alpha, \beta, \gamma$ adalah parameter hiper-prior empiris yang diturunkan dari distribusi historis 30 hari terakhir.
* **Hasil:** Jika suatu topik baru memiliki 2 data negatif, skor tidak langsung $-100\%$, melainkan tertarik secara proporsional ke rata-rata populasi (*shrinkage effect*), mencegah kepanikan birokrasi.

---

#### B. Rentang Keyakinan Wilson Score Interval
Untuk setiap skor kepuasan publik atau persentase sentimen, sistem wajib menghitung batas bawah dan batas atas pada tingkat kepercayaan $95\%$ ($z = 1.96$):

$$CI = \frac{\hat{p} + \frac{z^2}{2N} \pm z \sqrt{\frac{\hat{p}(1-\hat{p})}{N} + \frac{z^2}{4N^2}}}{1 + \frac{z^2}{N}}$$

* **Interpretasi untuk Pimpinan:** Dashboard tidak hanya menampilkan *"Kepuasan Dinkes 72%"*, tetapi *"Kepuasan Dinkes berada di rentang 68.4% s.d. 75.6% (Validitas 95%)"*. Jika rentang sangat lebar (misal $30\% - 85\%$), pimpinan tahu bahwa data belum cukup untuk mengambil kesimpulan definitif.

---

#### C. Weighted Media Impact Score (WMIS)
Sentimen dihitung bukan sekadar frekuensi kemunculan, melainkan bobot pengaruh terukur:

$$WMIS_i = S_i \times \omega_{tier} \times \log_{10}(1 + Engagement_i) \times Auth_i$$

Di mana:
* $S_i \in [-1.0, +1.0]$: Skor sentimen hasil model IndoBERT.
* $\omega_{tier} \in \{1.0, 0.85, 0.90, 0.70\}$: Bobot hirarki media (Tier 1 s.d. Tier 4).
* $Engagement_i = Likes + 2 \times Comments + 3 \times Shares$ (pada media sosial).
* $Auth_i$: Tingkat otoritas domain sumber berita (berdasarkan reputasi Dewan Pers).

---

#### D. Time-Series Anomaly Detection (STL + CUSUM + Dynamic Z-Score)
Untuk mendeteksi krisis opini publik secara ilmiah, data deret waktu dipecah menggunakan **STL Decomposition** (*Seasonal-Trend decomposition using LOESS*):

$$Y_t = T_t + S_t + R_t$$

* $T_t$: Tren jangka panjang persepsi masyarakat.
* $S_t$: Pola musiman (misal: keluhan selalu naik pada hari kerja jam 08.00–16.00 dan turun saat akhir pekan).
* $R_t$: Residu / Noise acak.

**Deteksi Anomali Dinamis:**
Peringatan krisis (*Crisis Alert*) HANYA dipicu jika residu $R_t$ melebihi ambang batas adaptif:

$$Z_t = \frac{R_t - \mu_R}{\sigma_R} > 2.5 \quad (\text{P-Value} < 0.01)$$

Ini mencegah alarm palsu yang disebabkan oleh fluktuasi harian biasa.

---

#### E. Changepoint Detection (Deteksi Titik Balik Opini)
Menggunakan algoritma **PELT** (*Pruned Exact Linear Time*) atau **CUSUM** (*Cumulative Sum Control Chart*) untuk mendeteksi titik waktu persis ($\tau$) ketika sentimen publik berbalik arah secara struktural:

$$CUSUM_t = \max(0, CUSUM_{t-1} + (S_t - \mu_0 - k))$$

* **Nilai Manajerial:** Membuktikan secara saintifik apakah konferensi pers Bupati atau rilis klarifikasi Humas pada jam 14.00 benar-benar efektif meredam isu negatif atau justru memperkeruh keadaan pada jam-jam berikutnya.

---

#### F. Matriks Korelasi & Dependensi Antar-OPD
Seringkali keluhan pada satu dinas berakar dari keluhan dinas lain. Sistem menghitung koefisien korelasi silang (*Cross-Correlation Function - CCF*):

$$r_{xy}(k) = \frac{\sum (X_t - \bar{X})(Y_{t+k} - \bar{Y})}{\sqrt{\sum (X_t - \bar{X})^2 \sum (Y_t - \bar{Y})^2}}$$

* **Contoh Kasus Riil:** Keluhan antrean RSUD (Dinkes) berkorelasi tinggi dengan jeda waktu $k = 1$ jam terhadap server mati di Disdukcapil (karena verifikasi BPJS/NIK gagal). Sistem dapat menunjukkan akar masalah yang sesungguhnya ke pimpinan.

---

#### G. Hybrid Lexicon & Deep Learning Sentiment Engine (IndoBERT + InSet + Slang)
Untuk menangkap variasi bahasa gaul dan konteks opini publik lokal secara presisi, sistem menerapkan arsitektur *hybrid ensemble*:

1. **Normalisasi Slang (Ade Arini Putri + Dialek Jatim):**
   Setiap token input diperiksa terhadap 1.509 lema padanan pada `engine-nlp/data/slang.csv`. Kata gaul dan singkatan percakapan distandarkan sebelum klasifikasi.

2. **InSet Sentiment Lexicon Scoring:**
   Menggunakan 9.119 leksikon polaritas (3.610 lema positif berbobot $+1$ s.d. $+5$, 6.610 lema negatif berbobot $-1$ s.d. $-5$) yang dipetakan ke skala continuous $[-1.0, +1.0]$:

   $$S_{inset} = \frac{1}{|W_{match}|} \sum_{w \in W_{match}} \frac{Score(w)}{5.0}$$

3. **Skor Ensemble Gabungan:**
   $$S_{final} = 0.70 \times S_{bert} + 0.30 \times S_{inset}$$

   Di mana $S_{bert} = P(Positive) - P(Negative)$. Jika $|S_{final}| \le 0.05$, label ditetapkan sebagai *neutral*, $S_{final} > 0.05$ sebagai *positive*, dan $S_{final} < -0.05$ sebagai *negative*.

---

#### H. Algoritma Wordcloud Sentimen & Ekstraksi Term Frequency (TF)
Untuk memetakan topik dominan dan kata kunci dalam sentimen tanpa kebisingan (*noise*), sistem menerapkan pembersihan berbasis tabel database `stopwords`:

1. **Tokenisasi & Filtrasi Multi-Klaster:**
   Teks dibersihkan dari 6 klaster kata henti database:
   $$\mathcal{W}_{clean} = \{w \in \mathcal{W} \mid w \notin \mathcal{S}_{daerah} \cup \mathcal{S}_{waktu} \cup \mathcal{S}_{angka} \cup \mathcal{S}_{sapaan} \cup \mathcal{S}_{aksi} \cup \mathcal{S}_{umum} \land \text{length}(w) \ge 3\}$$

2. **Perhitungan Term Frequency (TF) & Pemisahan Sentimen:**
   Setiap kata dihitung frekuensi kemunculannya berdasarkan klasifikasi sentimen dokumen ($TF_{pos}(w)$, $TF_{neg}(w)$, dan $TF_{topik}(w)$).

3. **Pemetaan Skala Visual Font Wordcloud:**
   $$FontSize(w) = FontSize_{min} + \left( \frac{TF(w) - TF_{min}}{TF_{max} - TF_{min}} \right) \times (FontSize_{max} - FontSize_{min})$$
   dengan rentang font dinamis 12px s.d. 32px untuk visualisasi tag cloud berkontras tinggi.

---

#### I. Kalibrasi Klasifikasi Choropleth Sentimen Teritorial 21 Kecamatan
Pewarnaan peta geospasial 21 kecamatan dihitung berdasarkan Net Sentiment Score ($NSS$) dan Rasio Sentimen Negatif ($NegRatio$) per wilayah kecamatan $k$:

$$NSS_k = \frac{Pos_k - Neg_k}{Total_k} \times 100\%, \quad NegRatio_k = \frac{Neg_k}{Total_k} \times 100\%$$

**Kriteria Ambang Batas 4 Level Teritorial:**
1. **Sangat Kondusif (`#10b981` / Hijau Emerald):** $NSS_k \ge +15\%$
2. **Kondusif & Stabil (`#06b6d4` / Biru Cyan):** $0\% \le NSS_k < +15\%$
3. **Perlu Atensi (`#f59e0b` / Kuning Amber):** $-30\% \le NSS_k < 0\%$
4. **Rawan Keluhan (`#f43f5e` / Merah Kirmizi):** $NegRatio_k \ge 40\%$ ATAU $NSS_k < -30\%$
