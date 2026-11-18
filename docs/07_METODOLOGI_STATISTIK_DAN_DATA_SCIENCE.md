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

