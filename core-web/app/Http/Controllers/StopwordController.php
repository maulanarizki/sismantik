<?php

namespace App\Http\Controllers;

use App\Models\Stopword;
use Illuminate\Http\Request;

class StopwordController extends Controller
{
    /**
     * Display a listing of the stopwords.
     */
    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));
        $category = $request->query('category', 'all');
        $status = $request->query('status', 'all');

        $query = Stopword::query()->orderBy('category')->orderBy('word');

        if (!empty($search)) {
            $query->where('word', 'LIKE', "%{$search}%");
        }

        if ($category !== 'all' && array_key_exists($category, Stopword::CATEGORIES)) {
            $query->where('category', $category);
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $stopwords = $query->paginate(25)->withQueryString();

        // Statistics
        $totalAll = Stopword::count();
        $totalActive = Stopword::where('is_active', true)->count();
        $totalInactive = Stopword::where('is_active', false)->count();

        $categoryCounts = [];
        foreach (Stopword::CATEGORIES as $key => $label) {
            $categoryCounts[$key] = Stopword::where('category', $key)->count();
        }

        $categories = Stopword::CATEGORIES;

        return view('pages.stopword', compact(
            'stopwords',
            'search',
            'category',
            'status',
            'totalAll',
            'totalActive',
            'totalInactive',
            'categoryCounts',
            'categories'
        ));
    }

    /**
     * Store a newly created stopword (supports single or comma/newline separated bulk input).
     */
    public function store(Request $request)
    {
        $request->validate([
            'words' => 'required|string',
            'category' => 'required|string|in:' . implode(',', array_keys(Stopword::CATEGORIES)),
        ]);

        $rawInput = $request->input('words');
        $category = $request->input('category');

        // Split by comma, newline, or tab
        $items = preg_split('/[\r\n,]+/', $rawInput);
        $added = 0;

        foreach ($items as $item) {
            $clean = trim(strtolower($item));
            // strip special characters except alphanumeric & dash
            $clean = preg_replace('/[^\p{L}\p{N}\-]/u', '', $clean);
            if (!empty($clean)) {
                Stopword::updateOrCreate(
                    ['word' => $clean],
                    ['category' => $category, 'is_active' => true]
                );
                $added++;
            }
        }

        return redirect()->route('stopwords.index', ['category' => $category])
            ->with('success', "Berhasil menyimpan {$added} kata ke dalam stopword kategori '" . Stopword::CATEGORIES[$category] . "'.");
    }

    /**
     * Update an existing stopword.
     */
    public function update(Request $request, $id)
    {
        $stopword = Stopword::findOrFail($id);

        $request->validate([
            'word' => 'required|string|max:100|unique:stopwords,word,' . $id,
            'category' => 'required|string|in:' . implode(',', array_keys(Stopword::CATEGORIES)),
        ]);

        $cleanWord = trim(strtolower($request->input('word')));
        $cleanWord = preg_replace('/[^\p{L}\p{N}\-]/u', '', $cleanWord);

        $stopword->update([
            'word' => $cleanWord,
            'category' => $request->input('category'),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return back()->with('success', "Stopword '{$stopword->word}' berhasil diperbarui.");
    }

    /**
     * Toggle active status.
     */
    public function toggle($id)
    {
        $stopword = Stopword::findOrFail($id);
        $stopword->is_active = !$stopword->is_active;
        $stopword->save();

        $statusLabel = $stopword->is_active ? 'Diaktifkan (Akan diabaikan dari Wordcloud)' : 'Dinonaktifkan (Akan muncul di Wordcloud)';

        return back()->with('success', "Kata '{$stopword->word}' berhasil {$statusLabel}.");
    }

    /**
     * Remove the specified stopword from database.
     */
    public function destroy($id)
    {
        $stopword = Stopword::findOrFail($id);
        $word = $stopword->word;
        $stopword->delete();

        return back()->with('success', "Kata '{$word}' berhasil dihapus dari daftar stopword.");
    }

    /**
     * API: Get active stopwords list (JSON).
     */
    public function apiIndex()
    {
        $words = Stopword::where('is_active', true)->pluck('word')->toArray();

        return response()->json([
            'status' => 'success',
            'count' => count($words),
            'stopwords' => $words,
        ]);
    }
}

