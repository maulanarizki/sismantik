<?php

namespace App\Http\Controllers;

use App\Models\GeoExtraction;
use App\Models\RawContent;
use App\Models\SentimentResult;
use App\Models\TopicOrAgency;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class ActionController extends Controller
{
    public function sync(Request $request)
    {
        try {
            $exitCode = Artisan::call('sismantik:sync', ['--max' => 20]);
            $output = Artisan::output();

            return response()->json([
                'success' => $exitCode === 0,
                'message' => 'Sinkronisasi berita terbaru berhasil dijalankan.',
                'output' => $output,
            ]);
        } catch (\Throwable $e) {
            Log::error('Sync error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menjalankan sinkronisasi: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function replaceTotal(Request $request)
    {
        try {
            Artisan::call('sismantik:replace-total', [
                '--months' => 12,
                '--max-per-month' => 1000,
                '--confirm' => 'yes'
            ]);
            $output = Artisan::output();

            return redirect()->back()->with('success', 'Replace Data Total Berhasil! Seluruh data 12 bulan terakhir telah ditarik ulang dan dianalisis NLP.');
        } catch (\Throwable $e) {
            Log::error('Replace Total error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal melakukan Replace Data Total: ' . $e->getMessage());
        }
    }

    public function drilldownArticles(Request $request)
    {
        $keyword = $request->input('keyword');
        $agencyId = $request->input('agencyId');
        $districtName = $request->input('districtName');
        $sentiment = $request->input('sentiment');
        $emotion = $request->input('emotion');
        $scope = $request->input('scope');
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        $query = RawContent::query()
            ->with(['sentimentResult', 'endpoint.source'])
            ->orderByDesc('published_at');

        if ($startDate && $endDate) {
            $query->whereBetween('published_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay(),
            ]);
        }

        if ($scope && $scope !== 'all') {
            $query->where('scope', $scope);
        }

        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('clean_text', 'LIKE', "%{$keyword}%")
                  ->orWhere('title', 'LIKE', "%{$keyword}%");
            });
        }

        if ($agencyId && $agencyId !== 'all') {
            $agency = TopicOrAgency::find($agencyId);
            if ($agency) {
                $kws = is_array($agency->keywords) ? $agency->keywords : [];
                $query->where(function ($q) use ($agency, $kws) {
                    $q->where('clean_text', 'LIKE', "%{$agency->name}%")
                      ->orWhere('title', 'LIKE', "%{$agency->name}%");
                    foreach ($kws as $k) {
                        $q->orWhere('clean_text', 'LIKE', "%{$k}%");
                    }
                });
            }
        }

        if ($districtName) {
            $alias = str_replace('Bandar Kedungmulyo', 'Bandarkedung', $districtName);
            $query->whereHas('geoExtractions', function ($q) use ($districtName, $alias) {
                $q->where('district_name', 'LIKE', "%{$districtName}%")
                  ->orWhere('district_name', 'LIKE', "%{$alias}%");
            });
        }

        if ($sentiment && $sentiment !== 'all') {
            $query->whereHas('sentimentResult', function ($q) use ($sentiment) {
                $q->where('sentiment_label', $sentiment);
            });
        }

        if ($emotion && $emotion !== 'all') {
            $query->whereHas('sentimentResult', function ($q) use ($emotion) {
                $q->where('emotion_label', $emotion);
            });
        }

        $items = $query->limit(50)->get();

        $formatted = $items->map(function ($item) {
            $sent = $item->sentimentResult;
            $source = $item->endpoint && $item->endpoint->source ? $item->endpoint->source->name : 'Media Online';

            return [
                'id' => $item->id,
                'title' => $item->title ?: 'Tanpa Judul',
                'clean_text' => mb_strimwidth($item->clean_text ?: $item->content_text ?: '', 0, 180, '...'),
                'url' => $item->url ?: '#',
                'source_name' => $source,
                'sentiment' => $sent ? $sent->sentiment_label : 'neutral',
                'emotion' => $sent ? $sent->emotion_label : '-',
                'published_at' => $item->published_at ? $item->published_at->format('d M Y, H:i') : '-',
            ];
        });

        return response()->json([
            'count' => $formatted->count(),
            'articles' => $formatted,
        ]);
    }
}

