<?php

namespace App\Http\Controllers;

use App\Models\TopicOrAgency;
use Illuminate\Http\Request;

class TopicAgencyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $query = TopicOrAgency::query()->orderBy('name');

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
        }

        $topics = $query->paginate(20)->withQueryString();

        return view('pages.opd', compact('topics', 'search'));
    }

    public function toggle($id)
    {
        $topic = TopicOrAgency::findOrFail($id);
        $topic->is_active = !$topic->is_active;
        $topic->save();

        return back()->with('success', "Status OPD '{$topic->name}' berhasil diubah menjadi " . ($topic->is_active ? 'Aktif' : 'Nonaktif') . '.');
    }
}

