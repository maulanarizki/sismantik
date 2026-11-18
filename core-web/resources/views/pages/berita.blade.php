@extends('layouts.app')

@section('title', 'Katalog Berita & Opini')

@section('content')
<div class="space-y-6">

    {{-- HEADER & STATS --}}
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Katalog Berita & Opini Publik
                </h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                    Arsip lengkap konten berita media online, portal berita lokal, dan agregator seputar Kabupaten Jombang.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1.5 rounded-xl font-mono text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                    Total: {{ number_format($articles->total()) }} Konten
                </span>
            </div>
        </div>

        {{-- Filters Bar --}}
        <form method="GET" action="/berita" class="pt-3 border-t border-slate-200 dark:border-slate-800 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
            {{-- Search input --}}
            <div class="lg:col-span-2">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari judul atau isi berita..."
                    class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
            </div>

            {{-- Scope filter --}}
            <div>
                <select name="scope" class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                    <option value="all" {{ $scope === 'all' ? 'selected' : '' }}>Semua Ruang Lingkup</option>
                    <option value="local_pemkab" {{ $scope === 'local_pemkab' ? 'selected' : '' }}>Lokal Pemkab Jombang</option>
                    <option value="national_strategic" {{ $scope === 'national_strategic' ? 'selected' : '' }}>Strategis Nasional (PSN)</option>
                </select>
            </div>

            {{-- Sentiment filter --}}
            <div>
                <select name="sentiment" class="w-full px-3 py-2 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                    <option value="all" {{ $sentiment === 'all' ? 'selected' : '' }}>Semua Sentimen</option>
                    <option value="positive" {{ $sentiment === 'positive' ? 'selected' : '' }}>Positif</option>
                    <option value="neutral" {{ $sentiment === 'neutral' ? 'selected' : '' }}>Netral</option>
                    <option value="negative" {{ $sentiment === 'negative' ? 'selected' : '' }}>Negatif</option>
                </select>
            </div>

            {{-- Submit button --}}
            <div>
                <button type="submit" class="w-full py-2 px-4 rounded-xl font-bold text-white bg-slate-900 hover:bg-slate-800 dark:bg-emerald-600 dark:hover:bg-emerald-500 transition shadow-xs">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    {{-- ARTICLES TABLE / LIST --}}
    <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        <th class="py-3.5 px-4 w-12 text-center">#</th>
                        <th class="py-3.5 px-4">Judul & Cuplikan Konten</th>
                        <th class="py-3.5 px-4 w-40">Sumber & Penulis</th>
                        <th class="py-3.5 px-4 w-32">Tanggal Terbit</th>
                        <th class="py-3.5 px-4 w-28 text-center">Sentimen</th>
                        <th class="py-3.5 px-4 w-28 text-center">Emosi</th>
                        <th class="py-3.5 px-4 w-20 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($articles as $idx => $art)
                        @php
                            $sent = $art->sentimentResult;
                            $sentLabel = $sent ? $sent->sentiment_label : 'neutral';
                            $emotionLabel = $sent ? $sent->emotion_label : '-';
                            $sourceName = $art->endpoint && $art->endpoint->source ? $art->endpoint->source->name : 'Portal Berita';
                        @endphp
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                            <td class="py-3.5 px-4 text-center font-mono text-slate-400 font-bold">
                                {{ $articles->firstItem() + $idx }}
                            </td>
                            <td class="py-3.5 px-4 space-y-1">
                                <a href="{{ $art->url }}" target="_blank" class="font-bold text-slate-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 transition leading-snug line-clamp-1">
                                    {{ $art->title ?: 'Tanpa Judul' }}
                                </a>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                    {{ mb_strimwidth($art->clean_text ?: $art->content_text ?: '', 0, 150, '...') }}
                                </p>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-800 dark:text-slate-200">{{ $sourceName }}</div>
                                <div class="text-[10px] text-slate-400 truncate">{{ $art->author ?: 'Redaksi' }}</div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-500 dark:text-slate-400">
                                {{ $art->published_at ? $art->published_at->format('d M Y, H:i') : '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $sentLabel === 'positive' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : ($sentLabel === 'negative' ? 'bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-300' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300') }}">
                                    {{ $sentLabel }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono text-[11px] text-slate-500 capitalize">
                                {{ $emotionLabel }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ $art->url }}" target="_blank" class="inline-flex items-center justify-center p-1.5 rounded-lg text-emerald-600 hover:text-emerald-700 hover:bg-emerald-50 dark:hover:bg-emerald-950/40 transition" title="Buka tautan asli">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                Tidak ada data berita yang sesuai dengan filter pencarian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $articles->links() }}
        </div>
    </div>

</div>
@endsection

