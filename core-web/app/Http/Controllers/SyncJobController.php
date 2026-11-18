<?php

namespace App\Http\Controllers;

use App\Models\SyncJob;
use Illuminate\Http\Request;

class SyncJobController extends Controller
{
    public function index(Request $request)
    {
        $syncJobs = SyncJob::with(['source', 'endpoint'])
            ->orderByDesc('id')
            ->paginate(15);

        return view('pages.sinkronisasi', compact('syncJobs'));
    }
}

