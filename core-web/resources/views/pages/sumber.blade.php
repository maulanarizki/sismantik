@extends('layouts.app')

@section('title', 'Portal & Sumber Data')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                Portal Media & Manajemen Sumber Data
            </h1>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                Kanal sumber berita daring, agregator RSS, dan endpoint web scraper otomatis Kabupaten Jombang.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl font-mono text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                {{ $sources->count() }} Portal • {{ $endpoints->count() }} Endpoint
            </span>
        </div>
    </div>

    {{-- SOURCES CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        @foreach($sources as $s)
            <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-sm flex flex-col justify-between space-y-3">
                <div>
                    <div class="flex items-center justify-between text-xs mb-1.5">
                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $s->tier === 'tier_1' ? 'bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300' : 'bg-slate-100 text-slate-700 dark:bg-slate-800 dark:text-slate-300' }}">
                            {{ $s->tier ?: 'Tier 2' }}
                        </span>
                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $s->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-rose-100 text-rose-800' }}">
                            {{ $s->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                    <h3 class="text-base font-black text-slate-900 dark:text-white">
                        {{ $s->name }}
                    </h3>
                    <a href="{{ $s->base_url }}" target="_blank" class="text-xs font-mono text-emerald-600 dark:text-emerald-400 hover:underline truncate block mt-0.5">
                        {{ $s->base_url }} ↗
                    </a>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 leading-relaxed">
                        {{ $s->description ?: 'Portal berita lokal dan regional liputan Jombang.' }}
                    </p>
                </div>

                <div class="pt-3 border-t border-slate-200 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500 font-mono">
                    <span>{{ $s->endpoints_count }} Endpoint</span>
                    <span>Otoritas: {{ $s->authority_score ?: '1.0' }}</span>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ENDPOINTS TABLE --}}
    <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden space-y-4 p-5">
        <div>
            <h2 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-wider">
                Daftar Scraper Endpoint & URL Target
            </h2>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                Konfigurasi URL spesifik yang dikeruk oleh worker scraper SISMANTIK.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        <th class="py-3 px-4 w-12 text-center">#</th>
                        <th class="py-3 px-4 w-48">Portal / Sumber</th>
                        <th class="py-3 px-4 w-52">Nama Endpoint</th>
                        <th class="py-3 px-4">URL Target</th>
                        <th class="py-3 px-4 w-32 text-center">Parser Type</th>
                        <th class="py-3 px-4 w-28 text-center">Total Berita</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @foreach($endpoints as $idx => $ep)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                            <td class="py-3 px-4 text-center font-mono text-slate-400 font-bold">
                                {{ $idx + 1 }}
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-900 dark:text-white">
                                {{ $ep->source ? $ep->source->name : '-' }}
                            </td>
                            <td class="py-3 px-4 font-semibold text-slate-800 dark:text-slate-200">
                                {{ $ep->name }}
                            </td>
                            <td class="py-3 px-4 font-mono text-[11px] text-slate-500 dark:text-slate-400 truncate max-w-xs">
                                <a href="{{ $ep->endpoint_url }}" target="_blank" class="hover:underline hover:text-emerald-500">
                                    {{ $ep->endpoint_url }}
                                </a>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 uppercase">
                                    {{ $ep->parser_type }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center font-mono font-bold text-slate-700 dark:text-slate-300">
                                {{ number_format($ep->raw_contents_count) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

