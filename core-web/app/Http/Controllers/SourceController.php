<?php

namespace App\Http\Controllers;

use App\Models\Source;
use App\Models\SourceEndpoint;
use Illuminate\Http\Request;

class SourceController extends Controller
{
    public function index(Request $request)
    {
        $sources = Source::withCount(['endpoints', 'syncJobs'])
            ->with(['endpoints'])
            ->orderBy('name')
            ->get();

        $endpoints = SourceEndpoint::with('source')
            ->withCount('rawContents')
            ->orderBy('source_id')
            ->get();

        return view('pages.sumber', compact('sources', 'endpoints'));
    }
}

