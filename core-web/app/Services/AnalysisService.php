<?php

namespace App\Services;

use App\Models\AspectSentiment;
use App\Models\GeoExtraction;
use App\Models\RawContent;
use App\Models\SentimentResult;
use App\Models\TopicOrAgency;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnalysisService
{
    protected string $pythonEngineUrl;

    public function __construct()
    {
        $this->pythonEngineUrl = config('services.python_engine.url', env('PYTHON_ENGINE_URL', 'http://127.0.0.1:8001'));
    }

    /**
     * Jalankan analisis NLP & Sentimen untuk sejumlah konten mentah yang belum dianalisis.
     */
    public function analyzeUnanalyzed(int $limit = 50): int
    {
        $contents = RawContent::where('is_analyzed', false)
            ->limit($limit)
            ->get();

        if ($contents->isEmpty()) {
            return 0;
        }

        $agencies = TopicOrAgency::where('is_active', true)
            ->get(['id', 'name', 'code', 'keywords'])
            ->toArray();

        $processed = 0;
        foreach ($contents as $content) {
            if ($this->analyzeSingle($content, $agencies)) {
                $processed++;
            }
        }

        return $processed;
    }

    /**
     * Menganalisis satu record konten mentah secara spesifik.
     */
    public function analyzeSingle(RawContent $content, ?array $agencies = null): bool
    {
        if ($agencies === null) {
            $agencies = TopicOrAgency::where('is_active', true)
                ->get(['id', 'name', 'code', 'keywords'])
                ->toArray();
        }

        $textToAnalyze = trim(($content->title ? $content->title . '. ' : '') . ($content->clean_text ?: $content->content_text));
        if (empty($textToAnalyze)) {
            $content->update(['is_analyzed' => true]);
            return false;
        }

        try {
            $response = Http::timeout(20)->post("{$this->pythonEngineUrl}/api/sentiment/analyze", [
                'text' => $textToAnalyze,
                'agencies' => $agencies,
            ]);

            if ($response->failed()) {
                Log::warning("Gagal menganalisis ID {$content->id}: " . $response->body());
                return false;
            }

            $data = $response->json();
            $sentimentData = $data['sentiment'] ?? [];
            $emotionData = $data['emotion'] ?? [];
            $framingData = $data['framing'] ?? [];
            $aspectsData = $data['aspects'] ?? [];
            $geoData = $data['geo_detected'] ?? null;

            // 1. Simpan Sentiment Result
            $sentimentResult = SentimentResult::create([
                'raw_content_id' => $content->id,
                'sentiment_label' => $sentimentData['sentiment_label'] ?? 'neutral',
                'sentiment_score' => $sentimentData['sentiment_score'] ?? 0.0,
                'confidence_score' => $sentimentData['confidence_score'] ?? 0.8,
                'emotion_label' => $emotionData['dominant_emotion'] ?? null,
                'emotion_scores' => $emotionData['emotion_scores'] ?? null,
                'framing_category' => $framingData['dominant_frame'] ?? 'administrative',
                'analyzed_by_model' => 'indobert-smsa-v1',
            ]);

            // 2. Simpan Aspect Sentiments per OPD
            foreach ($aspectsData as $asp) {
                AspectSentiment::create([
                    'sentiment_result_id' => $sentimentResult->id,
                    'agency_id' => $asp['agency_id'],
                    'aspect_keyword' => $asp['aspect_keyword'] ?? null,
                    'aspect_sentiment_label' => $asp['aspect_sentiment_label'] ?? 'neutral',
                    'aspect_score' => $asp['aspect_score'] ?? 0.0,
                ]);
            }

            // 3. Simpan Geo Extractions jika terdeteksi
            if (!empty($geoData['district_name'])) {
                GeoExtraction::create([
                    'raw_content_id' => $content->id,
                    'district_name' => $geoData['district_name'],
                    'match_confidence' => $geoData['match_confidence'] ?? 0.88,
                ]);
            }

            // 4. Deteksi Buzzer / Anomali Duplikasi Komentar (CIB Audit)
            $duplicateCount = RawContent::where('content_hash', $content->content_hash)->count();
            $isSuspected = false;
            $reason = null;

            if ($duplicateCount > 3) {
                $isSuspected = true;
                $reason = "Terdeteksi {$duplicateCount} duplikasi teks identik (indikasi kampanye copy-paste buzzer/CIB)";
                RawContent::where('content_hash', $content->content_hash)->update([
                    'is_suspected_bot' => true,
                    'bot_suspicion_reason' => $reason,
                ]);
            }

            // 5. Update Status Raw Content
            $content->update([
                'is_analyzed' => true,
                'is_suspected_bot' => $isSuspected,
                'bot_suspicion_reason' => $reason,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::error("Exception pada analisis sentimen: " . $e->getMessage());
            return false;
        }
    }
}

