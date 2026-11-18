<?php

namespace App\Services;

class StatisticalService
{
    /**
     * Menghitung Bayesian Sentiment Smoothing (Laplace Shrinkage)
     * Mencegah lonjakan ekstrem pada data bervolume kecil.
     *
     * @param int $pos Jumlah sentimen positif
     * @param int $neu Jumlah sentimen netral
     * @param int $neg Jumlah sentimen negatif
     * @param float $alpha Prior positif (default: 1.0)
     * @param float $beta Prior negatif (default: 1.0)
     * @param float $gamma Prior netral (default: 2.0)
     * @return array
     */
    public function calculateBayesianSentiment(
        int $pos,
        int $neu,
        int $neg,
        float $alpha = 1.0,
        float $beta = 1.0,
        float $gamma = 2.0
    ): array {
        $n = $pos + $neu + $neg;
        $denom = $n + $alpha + $beta + $gamma;

        $pPos = ($pos + $alpha) / $denom;
        $pNeg = ($neg + $beta) / $denom;
        $pNeu = ($neu + $gamma) / $denom;

        // Net Sentiment Score Bayesian (-100% s/d +100%)
        $nssBayes = round(($pPos - $pNeg) * 100, 2);

        return [
            'total_sample' => $n,
            'smoothed_positive_ratio' => round($pPos, 4),
            'smoothed_negative_ratio' => round($pNeg, 4),
            'smoothed_neutral_ratio' => round($pNeu, 4),
            'nss_bayes' => $nssBayes,
        ];
    }

    /**
     * Menghitung Rentang Keyakinan Wilson Score Interval 95%
     * Menghasilkan margin of error yang akurat untuk persentase kepuasan warga.
     *
     * @param int $success Jumlah sukses (misal: positif)
     * @param int $total Total sebutan
     * @param float $z Nilai z-score (1.96 untuk 95% confidence)
     * @return array [lower_bound, upper_bound, point_estimate]
     */
    public function calculateWilsonInterval(int $success, int $total, float $z = 1.96): array
    {
        if ($total <= 0) {
            return [
                'point_estimate' => 0.0,
                'lower_bound' => 0.0,
                'upper_bound' => 0.0,
                'margin_of_error' => 0.0,
            ];
        }

        $p = $success / $total;
        $z2 = $z * $z;

        $denom = 1 + ($z2 / $total);
        $center = $p + ($z2 / (2 * $total));
        $spread = $z * sqrt(($p * (1 - $p) / $total) + ($z2 / (4 * ($total * $total))));

        $lower = max(0.0, round(($center - $spread) / $denom, 4));
        $upper = min(1.0, round(($center + $spread) / $denom, 4));
        $margin = round(($upper - $lower) / 2 * 100, 1);

        return [
            'point_estimate' => round($p * 100, 1),
            'lower_bound' => round($lower * 100, 1),
            'upper_bound' => round($upper * 100, 1),
            'margin_of_error' => $margin,
        ];
    }

    /**
     * Menghitung Weighted Media Impact Score (WMIS)
     * Sentimen diberi bobot berdasarkan tingkat hirarki media dan interaksi sosial.
     *
     * @param float $rawSentiment Skor sentimen model (-1.0 s/d 1.0)
     * @param string $tier Tingkatan media ('tier_1', 'tier_2', 'tier_3', 'tier_4')
     * @param int $engagement Total likes, shares, comments
     * @param float $authorityScore Otoritas domain (0.0 s/d 1.0)
     * @return float
     */
    public function calculateWMIS(
        float $rawSentiment,
        string $tier = 'tier_2',
        int $engagement = 0,
        float $authorityScore = 0.85
    ): float {
        $tierWeights = [
            'tier_1' => 1.00,
            'tier_2' => 0.85,
            'tier_3' => 0.90,
            'tier_4' => 0.70,
        ];

        $wTier = $tierWeights[$tier] ?? 0.80;
        $engagementFactor = log10(1 + max(0, $engagement));
        $multiplier = max(1.0, 1.0 + ($engagementFactor * 0.3));

        return round($rawSentiment * $wTier * $authorityScore * $multiplier, 4);
    }

    /**
     * Deteksi Anomali Deret Waktu menggunakan Z-Score Dinamis
     * Mengembalikan true jika sentimen negatif hari ini melampaui rata-rata historis > 2.0 standar deviasi.
     */
    public function detectNegativeAnomaly(array $historicalNegativeCounts, int $currentNegativeCount): array
    {
        if (count($historicalNegativeCounts) < 3) {
            return ['is_anomaly' => false, 'z_score' => 0.0, 'threshold' => 0.0];
        }

        $mean = array_sum($historicalNegativeCounts) / count($historicalNegativeCounts);
        $variance = 0.0;
        foreach ($historicalNegativeCounts as $val) {
            $variance += pow($val - $mean, 2);
        }
        $stdDev = sqrt($variance / count($historicalNegativeCounts));

        if ($stdDev < 0.5) {
            $stdDev = 1.0; // minimal spread
        }

        $zScore = ($currentNegativeCount - $mean) / $stdDev;
        $isAnomaly = $zScore >= 2.0;

        return [
            'is_anomaly' => $isAnomaly,
            'z_score' => round($zScore, 2),
            'mean' => round($mean, 1),
            'std_dev' => round($stdDev, 1),
            'threshold' => round($mean + (2.0 * $stdDev), 1),
        ];
    }
}

