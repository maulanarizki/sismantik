<?php

namespace App\Http\Controllers;

use App\Models\RawContent;
use App\Models\TopicOrAgency;
use Illuminate\Http\Request;

class RawContentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $scope = $request->query('scope', 'all');
        $sentiment = $request->query('sentiment', 'all');
        $agencyId = $request->query('agency_id', 'all');

        $query = RawContent::with(['sentimentResult', 'endpoint.source'])
            ->orderByDesc('published_at');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('clean_text', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%");
            });
        }

        if ($scope !== 'all') {
            $query->where('scope', $scope);
        }

        if ($sentiment !== 'all') {
            $query->whereHas('sentimentResult', function ($q) use ($sentiment) {
                $q->where('sentiment_label', $sentiment);
            });
        }

        if ($agencyId !== 'all') {
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

        $articles = $query->paginate(15)->withQueryString();
        $agencies = TopicOrAgency::where('is_active', true)->get();

        return view('pages.berita', compact(
            'articles',
            'search',
            'scope',
            'sentiment',
            'agencyId',
            'agencies'
        ));
    }
}

