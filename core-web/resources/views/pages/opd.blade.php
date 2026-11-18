@extends('layouts.app')

@section('title', 'Daftar OPD & Kata Kunci Pemkab Jombang')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                Direktori OPD & Isu Strategis Kabupaten Jombang
            </h1>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                Daftar dinas/instansi Pemkab Jombang, klaster Program Strategis Nasional (PSN), dan pemetaan kata kunci monitoring.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-xl font-mono text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                Total: {{ $topics->total() }} Entitas
            </span>
        </div>
    </div>

    {{-- TOPICS / OPD TABLE --}}
    <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        <th class="py-3.5 px-4 w-12 text-center">#</th>
                        <th class="py-3.5 px-4 w-72">Nama Dinas / Entitas</th>
                        <th class="py-3.5 px-4 w-44">Kategori / Ruang Lingkup</th>
                        <th class="py-3.5 px-4">Kata Kunci Pemantauan (Keywords)</th>
                        <th class="py-3.5 px-4 w-28 text-center">Status</th>
                        <th class="py-3.5 px-4 w-24 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($topics as $idx => $t)
                        @php
                            $kws = is_array($t->keywords) ? $t->keywords : [];
                        @endphp
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                            <td class="py-3.5 px-4 text-center font-mono text-slate-400 font-bold">
                                {{ $topics->firstItem() + $idx }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900 dark:text-white leading-snug">
                                    {{ $t->name }}
                                </div>
                                <div class="text-[10px] font-mono text-slate-400">Kode: {{ $t->code ?: '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4 space-y-1">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold bg-cyan-100 text-cyan-800 dark:bg-cyan-500/20 dark:text-cyan-300">
                                    {{ $t->category ?: 'Pemerintahan' }}
                                </span>
                                <div>
                                    <span class="text-[10px] font-mono font-bold {{ $t->scope === 'national_strategic' ? 'text-amber-600 dark:text-amber-400' : 'text-emerald-600 dark:text-emerald-400' }}">
                                        {{ $t->scope === 'national_strategic' ? '● Strategis Nasional' : '● Lokal Pemkab' }}
                                    </span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex flex-wrap gap-1">
                                    @foreach($kws as $kw)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-mono bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700">
                                            {{ $kw }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase {{ $t->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400' }}">
                                    {{ $t->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <form action="/opd/{{ $t->id }}/toggle" method="POST">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition {{ $t->is_active ? 'text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40' : 'text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40' }}">
                                        {{ $t->is_active ? 'Matikan' : 'Aktifkan' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                Belum ada data OPD yang terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $topics->links() }}
        </div>
    </div>

</div>
@endsection

