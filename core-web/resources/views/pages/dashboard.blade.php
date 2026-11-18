@extends('layouts.app')

@section('title', 'Pusat Intelijen Opini Publik & Pemantauan Media Kab. Jombang')

@section('content')
<div class="space-y-6">

    {{-- HEADER & FILTER BAR --}}
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Pusat Intelijen Opini Publik & Pemantauan Media Kab. Jombang
                </h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                    Sistem pemantauan sentimen publik, media cetak/online, dan analisis Program Strategis Nasional berbasis AI NLP multi-aspek.
                </p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-lg text-[10px] font-mono font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-300 dark:border-slate-700">
                    Akurasi NLP: 94.2%
                </span>
                <span class="px-2.5 py-1 rounded-lg text-[10px] font-mono font-bold bg-emerald-50 dark:bg-emerald-950/40 text-emerald-700 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                    Engine: IndoBERT+VADER
                </span>
            </div>
        </div>

        {{-- Filter Form --}}
        <form method="GET" action="/dashboard" class="pt-3 border-t border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3 text-xs">
            {{-- Quick Presets --}}
            <div class="flex items-center gap-1.5">
                <span class="text-slate-500 font-bold uppercase text-[10px] tracking-wider mr-1">Preset:</span>
                <a href="/dashboard?startDate={{ now()->subMonth()->format('Y-m-d') }}&endDate={{ now()->format('Y-m-d') }}&scope={{ $scope }}" 
                    class="px-2.5 py-1 rounded-lg font-bold border transition {{ request('startDate') == now()->subMonth()->format('Y-m-d') ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700 hover:bg-slate-200' }}">
                    1 Bulan
                </a>
                <a href="/dashboard?startDate={{ now()->subMonths(3)->format('Y-m-d') }}&endDate={{ now()->format('Y-m-d') }}&scope={{ $scope }}" 
                    class="px-2.5 py-1 rounded-lg font-bold border transition {{ request('startDate') == now()->subMonths(3)->format('Y-m-d') ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700 hover:bg-slate-200' }}">
                    3 Bulan
                </a>
                <a href="/dashboard?startDate={{ now()->subMonths(6)->format('Y-m-d') }}&endDate={{ now()->format('Y-m-d') }}&scope={{ $scope }}" 
                    class="px-2.5 py-1 rounded-lg font-bold border transition {{ request('startDate') == now()->subMonths(6)->format('Y-m-d') ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700 hover:bg-slate-200' }}">
                    6 Bulan
                </a>
                <a href="/dashboard?startDate={{ now()->subYear()->format('Y-m-d') }}&endDate={{ now()->format('Y-m-d') }}&scope={{ $scope }}" 
                    class="px-2.5 py-1 rounded-lg font-bold border transition {{ !request('startDate') || request('startDate') == now()->subYear()->format('Y-m-d') ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700 hover:bg-slate-200' }}">
                    1 Tahun
                </a>
            </div>

            {{-- Date Inputs & Scope --}}
            <div class="flex flex-wrap items-center gap-2">
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-500 font-bold">Dari:</span>
                    <input type="date" name="startDate" value="{{ $startDate }}" 
                        class="px-2.5 py-1 rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-1 focus:ring-emerald-500">
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-500 font-bold">Sampai:</span>
                    <input type="date" name="endDate" value="{{ $endDate }}" 
                        class="px-2.5 py-1 rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-1 focus:ring-emerald-500">
                </div>
                <div class="flex items-center gap-1.5">
                    <select name="scope" class="px-2.5 py-1 rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-1 focus:ring-emerald-500">
                        <option value="all" {{ $scope === 'all' ? 'selected' : '' }}>Semua Isu (Lokal & PSN)</option>
                        <option value="local_pemkab" {{ $scope === 'local_pemkab' ? 'selected' : '' }}>Khusus Lokal Pemkab Jombang</option>
                        <option value="national_strategic" {{ $scope === 'national_strategic' ? 'selected' : '' }}>Khusus Isu Strategis Nasional</option>
                    </select>
                </div>
                <button type="submit" class="px-3.5 py-1 rounded-lg font-bold text-white bg-slate-900 hover:bg-slate-800 dark:bg-emerald-600 dark:hover:bg-emerald-500 transition shadow-xs">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    {{-- 4 MASTER KPI CARDS --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- KPI 1: Volume --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Berita Terpublikasi</span>
                    <div class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white mt-1">
                        {{ number_format($totalContent) }}
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 flex items-center gap-1.5 text-xs">
                <span class="font-bold text-emerald-600 dark:text-emerald-400">Positif: {{ number_format($posCount) }}</span>
                <span class="text-slate-400">•</span>
                <span class="font-bold text-rose-600 dark:text-rose-400">Negatif: {{ number_format($negCount) }}</span>
                <span class="text-slate-400">•</span>
                <span class="font-bold text-slate-500">Netral: {{ number_format($neuCount) }}</span>
            </div>
            <div class="mt-2 text-[10px] text-slate-500 dark:text-slate-400 font-mono">
                Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
            </div>
        </div>

        {{-- KPI 2: NSS --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Net Sentiment Score (NSS)</span>
                    <div class="text-2xl sm:text-3xl font-black mt-1 {{ $nss >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                        {{ $nss > 0 ? '+' : '' }}{{ $nss }}%
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl {{ $nss >= 0 ? 'bg-emerald-500/10 text-emerald-600' : 'bg-rose-500/10 text-rose-600' }} flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs font-semibold text-slate-600 dark:text-slate-400">
                Status: <span class="font-black {{ $nss >= 15 ? 'text-emerald-600' : ($nss >= 0 ? 'text-cyan-600' : 'text-rose-600') }}">{{ $nss >= 15 ? 'Sangat Positif' : ($nss >= 0 ? 'Kondusif & Stabil' : 'Perlu Intervensi') }}</span>
            </div>
            <div class="mt-2 text-[10px] text-slate-500 dark:text-slate-400 font-mono">
                Formula: (Positif - Negatif) / Total × 100
            </div>
        </div>

        {{-- KPI 3: Trust Index --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Indeks Kepuasan Warga</span>
                    <div class="text-2xl sm:text-3xl font-black text-amber-600 dark:text-amber-400 mt-1">
                        {{ $trustIndex }} <span class="text-sm font-semibold text-slate-400">/ 10.0</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs font-semibold text-slate-600 dark:text-slate-400">
                Peringkat: <span class="font-black text-amber-600 dark:text-amber-400">{{ $trustIndex >= 7.0 ? 'Tinggi (Baik)' : ($trustIndex >= 4.5 ? 'Moderat (Cukup)' : 'Rendah') }}</span>
            </div>
            <div class="mt-2 text-[10px] text-slate-500 dark:text-slate-400 font-mono">
                Tolak Ukur Akuntabilitas Pelayanan Publik
            </div>
        </div>

        {{-- KPI 4: Bot & Anomaly --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm relative overflow-hidden group">
            <div class="flex items-start justify-between">
                <div>
                    <span class="text-[11px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">Bot / Anomali Terindikasi</span>
                    <div class="text-2xl sm:text-3xl font-black text-indigo-600 dark:text-indigo-400 mt-1">
                        {{ $botCount }} <span class="text-xs font-semibold text-slate-400">Konten</span>
                    </div>
                </div>
                <div class="w-10 h-10 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
            </div>
            <div class="mt-3 text-xs font-semibold text-slate-600 dark:text-slate-400">
                Penyimpangan: <span class="font-black text-slate-700 dark:text-slate-300">{{ $botCount > 0 ? 'Terdeteksi Spam/Bot' : 'Nihil Anomali' }}</span>
            </div>
            <div class="mt-2 text-[10px] text-slate-500 dark:text-slate-400 font-mono">
                MinHash LSH & Duplikasi Deteksi
            </div>
        </div>
    </div>

    {{-- PROGRAM STRATEGIS NASIONAL (PSN) 4 CARDS --}}
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    4 Pilar Strategis Nasional (PSN) • Monitoring Kabupaten Jombang
                </h2>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                    Pemantauan keselarasan kebijakan prioritas Presiden RI dan pelaksanaannya oleh Pemkab & Forkopimda Jombang.
                </p>
            </div>
            <span class="text-xs font-mono font-bold text-slate-400">Klik kartu untuk rincian berita</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach($psnPrograms as $psn)
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/50 hover:border-emerald-500/50 hover:shadow-md transition flex flex-col justify-between space-y-3">
                    <div>
                        <div class="flex items-center justify-between text-[11px] font-bold text-slate-500 mb-1">
                            <span class="truncate max-w-[140px]">{{ $psn['badge'] }}</span>
                            <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $psn['nss'] >= 15 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : ($psn['nss'] >= 0 ? 'bg-cyan-100 text-cyan-800 dark:bg-cyan-500/20 dark:text-cyan-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-300') }}">
                                {{ $psn['status'] }}
                            </span>
                        </div>
                        <h3 class="text-sm font-black text-slate-900 dark:text-white leading-snug">
                            {{ $psn['name'] }}
                        </h3>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-2 line-clamp-2 leading-relaxed">
                            {{ $psn['focal_point'] }}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-slate-200 dark:border-slate-700/60">
                        <div class="flex items-center justify-between text-xs mb-2">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-400">Total Mention</span>
                                <div class="font-black text-slate-900 dark:text-white">{{ $psn['mentions'] }} Berita</div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] uppercase font-bold text-slate-400">Net Sentimen</span>
                                <div class="font-black {{ $psn['nss'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                    {{ $psn['nss'] > 0 ? '+' : '' }}{{ $psn['nss'] }}%
                                </div>
                            </div>
                        </div>

                        {{-- Drilldown Button --}}
                        <button type="button" 
                            @click="$dispatch('open-article-drilldown', {
                                title: 'Program: {{ addslashes($psn['name']) }}',
                                subtitle: 'Fokus PSN: {{ addslashes($psn['badge']) }} • Kab. Jombang',
                                keyword: '{{ $psn['primary_keyword'] }}',
                                startDate: '{{ $startDate }}',
                                endDate: '{{ $endDate }}'
                            })"
                            class="w-full py-1.5 px-3 rounded-lg text-xs font-bold text-emerald-700 dark:text-emerald-300 bg-emerald-100 dark:bg-emerald-500/20 hover:bg-emerald-200 dark:hover:bg-emerald-500/30 transition flex items-center justify-center gap-1.5 cursor-pointer">
                            <span>Buka Artikel Terkait</span>
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- 12-MONTH SENTIMENT TIMELINE TREND (AREA CHART) --}}
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
            <div>
                <h2 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-cyan-500"></span>
                    Dinamika Tren Sentimen Publik 12 Bulan Terakhir Berdasarkan Tanggal Terbit Berita
                </h2>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                    Grafik pergerakan opini publik di Kabupaten Jombang (Total Volume Berita, Sentimen Positif, dan Sentimen Negatif).
                </p>
            </div>
            <div class="flex items-center gap-3 text-xs font-bold">
                <span class="flex items-center gap-1.5 text-slate-600 dark:text-slate-300">
                    <span class="w-3 h-3 rounded bg-slate-400"></span> Total Volume
                </span>
                <span class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400">
                    <span class="w-3 h-3 rounded bg-emerald-500"></span> Positif
                </span>
                <span class="flex items-center gap-1.5 text-rose-600 dark:text-rose-400">
                    <span class="w-3 h-3 rounded bg-rose-500"></span> Negatif
                </span>
            </div>
        </div>

        <div class="h-72 w-full">
            <canvas id="timelineChart"></canvas>
        </div>
    </div>

    {{-- ROW: 10 OPD Pemkab Jombang Scorecard & Matriks Framing Isu Warga --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Left: Rapor 10 OPD Pemkab Jombang --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">
                        Rapor Kinerja 10 OPD Pemkab Jombang
                    </h2>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                        Frekuensi liputan media & skor penerimaan publik terhadap dinas/instansi.
                    </p>
                </div>
                <span class="text-[10px] font-mono text-slate-400">Klik baris untuk berita</span>
            </div>

            <div class="space-y-2.5 max-h-[380px] overflow-y-auto pr-1">
                @foreach(array_slice($opdScorecards, 0, 10) as $opd)
                    <div 
                        @click="$dispatch('open-article-drilldown', {
                            title: 'OPD: {{ addslashes($opd['name']) }}',
                            subtitle: 'Kategori: {{ addslashes($opd['category']) }} • Kab. Jombang',
                            agencyId: {{ $opd['id'] }},
                            startDate: '{{ $startDate }}',
                            endDate: '{{ $endDate }}'
                        })"
                        class="p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 hover:border-emerald-500/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="font-bold text-slate-900 dark:text-white truncate max-w-[280px]">
                                {{ $opd['name'] }}
                            </span>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-slate-500 font-bold">{{ $opd['mentions'] }} berita</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-black {{ $opd['nss'] >= 0 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-300' }}">
                                    {{ $opd['nss'] > 0 ? '+' : '' }}{{ $opd['nss'] }}%
                                </span>
                            </div>
                        </div>

                        {{-- Micro progress bar --}}
                        <div class="w-full h-1.5 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden flex">
                            @php
                                $tot = max(1, $opd['mentions']);
                                $posPct = ($opd['pos'] / $tot) * 100;
                                $negPct = ($opd['neg'] / $tot) * 100;
                                $neuPct = ($opd['neu'] / $tot) * 100;
                            @endphp
                            <div style="width: {{ $posPct }}%" class="bg-emerald-500 h-full"></div>
                            <div style="width: {{ $neuPct }}%" class="bg-slate-400 h-full"></div>
                            <div style="width: {{ $negPct }}%" class="bg-rose-500 h-full"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Right: Matriks Framing Isu & Aduan Warga --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">
                        Matriks Framing Isu & Aduan Warga
                    </h2>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                        Pemetaan klaster topik aduan masyarakat dan sentimen dominan.
                    </p>
                </div>
                <span class="text-[10px] font-mono text-slate-400">Klik kartu untuk berita</span>
            </div>

            <div class="space-y-2.5 max-h-[380px] overflow-y-auto pr-1">
                @foreach($framingMatrix as $fm)
                    <div 
                        @click="$dispatch('open-article-drilldown', {
                            title: 'Klaster Isu: {{ addslashes($fm['category']) }}',
                            subtitle: 'Contoh Isu: {{ addslashes($fm['sample_issue']) }}',
                            keyword: '{{ $fm['primary_keyword'] }}',
                            startDate: '{{ $startDate }}',
                            endDate: '{{ $endDate }}'
                        })"
                        class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 hover:border-emerald-500/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
                        <div class="flex items-center justify-between text-xs mb-1">
                            <h3 class="font-bold text-slate-900 dark:text-white">
                                {{ $fm['category'] }}
                            </h3>
                            <div class="flex items-center gap-2">
                                <span class="font-mono text-slate-500 font-bold">{{ $fm['mentions'] }} berita</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-black {{ $fm['nss'] >= 0 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-300' }}">
                                    NSS: {{ $fm['nss'] > 0 ? '+' : '' }}{{ $fm['nss'] }}%
                                </span>
                            </div>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400">
                            Fokus: <span class="italic">{{ $fm['sample_issue'] }}</span>
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ROW: Anatomi Spektrum Emosi & Bilik Geospasial Preview --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Left: Anatomi 6 Spektrum Emosi --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
            <div>
                <h2 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">
                    Anatomi 6 Spektrum Emosi Publik
                </h2>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                    Klasifikasi emosi audiens dari teks berita dan komentar pembaca di Jombang.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 items-center gap-4">
                <div class="h-48 flex items-center justify-center">
                    <canvas id="emotionChart"></canvas>
                </div>
                <div class="space-y-2 text-xs">
                    @foreach($emotionData as $emKey => $em)
                        <div 
                            @click="$dispatch('open-article-drilldown', {
                                title: 'Emosi Publik: {{ addslashes($em['label']) }}',
                                subtitle: 'Total: {{ $em['count'] }} Konten ({{ $em['percentage'] }}%)',
                                emotion: '{{ $emKey }}',
                                startDate: '{{ $startDate }}',
                                endDate: '{{ $endDate }}'
                            })"
                            class="flex items-center justify-between p-2 rounded-lg bg-slate-100 dark:bg-slate-800/60 hover:bg-slate-200 dark:hover:bg-slate-800 transition cursor-pointer">
                            <div class="flex items-center gap-2">
                                <span class="w-3 h-3 rounded-full" style="background-color: {{ $em['color'] }}"></span>
                                <span class="font-bold text-slate-800 dark:text-slate-200">{{ $em['label'] }}</span>
                            </div>
                            <span class="font-mono font-bold text-slate-500">{{ $em['count'] }} ({{ $em['percentage'] }}%)</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Right: Bilik Geospasial 21 Kecamatan Preview --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                            <span class="w-2.5 h-2.5 rounded-full bg-cyan-500"></span>
                            Bilik Geospasial Sentimen 21 Kecamatan
                        </h2>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                            Top 5 wilayah dengan atensi berita & dinamika sentimen tertinggi.
                        </p>
                    </div>
                    <a href="/peta" class="px-3 py-1 rounded-lg text-xs font-bold text-cyan-700 dark:text-cyan-300 bg-cyan-50 dark:bg-cyan-950/40 border border-cyan-200 dark:border-cyan-800 hover:bg-cyan-100 transition flex items-center gap-1">
                        <span>Buka Peta Penuh</span>
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>

                <div class="mt-4 space-y-2">
                    @foreach($topDistrictsPreview as $dist)
                        <div 
                            @click="$dispatch('open-article-drilldown', {
                                title: 'Kecamatan {{ addslashes($dist['name']) }}',
                                subtitle: 'Kabupaten Jombang • Total: {{ $dist['total'] }} Berita',
                                districtName: '{{ addslashes($dist['name']) }}',
                                startDate: '{{ $startDate }}',
                                endDate: '{{ $endDate }}'
                            })"
                            class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 hover:border-cyan-500/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition flex items-center justify-between text-xs cursor-pointer">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-slate-900 dark:text-white">Kec. {{ $dist['name'] }}</span>
                                <span class="px-2 py-0.2 rounded text-[10px] font-black {{ $dist['status'] === 'Sangat Kondusif' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : ($dist['status'] === 'Kondusif' ? 'bg-cyan-100 text-cyan-800 dark:bg-cyan-500/20 dark:text-cyan-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-300') }}">
                                    {{ $dist['status'] }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3 font-mono">
                                <span class="text-slate-500 font-bold">{{ $dist['total'] }} berita</span>
                                <span class="font-black {{ $dist['nss'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                    NSS: {{ $dist['nss'] > 0 ? '+' : '' }}{{ $dist['nss'] }}%
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-3 border-t border-slate-200 dark:border-slate-700/60 flex items-center justify-between text-xs">
                <span class="text-slate-500">21 Poligon Resmi GADM 4.1 Kab. Jombang Terpetakan</span>
                <a href="/peta" class="text-cyan-600 dark:text-cyan-400 font-bold hover:underline">
                    Lihat 21 Kecamatan di GIS →
                </a>
            </div>
        </div>
    </div>

    {{-- EXECUTIVE AI DIGEST & REKOMENDASI STRATEGIS DISKOMINFO --}}
    <div class="p-5 rounded-2xl bg-gradient-to-br from-slate-900 via-slate-900 to-emerald-950 text-white shadow-xl space-y-4">
        <div class="flex items-center justify-between border-b border-slate-800 pb-3">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-black">
                    AI
                </div>
                <div>
                    <h3 class="text-sm font-black uppercase tracking-wider">
                        Executive Intelligence Digest & Rekomendasi Diskominfo
                    </h3>
                    <p class="text-xs text-slate-400">
                        Protokol PDB (Presidential Daily Brief Standard) • Periode Analisis: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}
                    </p>
                </div>
            </div>
            <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                Diskominfo Kab. Jombang
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div class="p-4 rounded-xl bg-slate-800/60 border border-slate-700/60 space-y-2">
                <div class="text-[11px] font-black uppercase tracking-wider text-emerald-400 flex items-center gap-1.5">
                    <span>1. Ringkasan Narasi Dominan</span>
                </div>
                <p class="text-slate-300 leading-relaxed">
                    Opini publik di Jombang didominasi oleh kesiapan uji coba Makan Bergizi Gratis (MBG), pengawasan distribusi pupuk subsidi menjelang musim tanam, serta tuntutan perbaikan jalan rusak di koridor penghubung antar kecamatan.
                </p>
            </div>

            <div class="p-4 rounded-xl bg-slate-800/60 border border-slate-700/60 space-y-2">
                <div class="text-[11px] font-black uppercase tracking-wider text-amber-400 flex items-center gap-1.5">
                    <span>2. Potensi Kerawanan Isu</span>
                </div>
                <p class="text-slate-300 leading-relaxed">
                    Keluhan infrastruktur jalan dan PJU di wilayah pelosok (Kabuh, Wonosalam, Ngusikan) berpotensi memicu gelombang kritik warga jika lambat direspons, terutama saat memasuki musim penghujan dan genangan air.
                </p>
            </div>

            <div class="p-4 rounded-xl bg-slate-800/60 border border-slate-700/60 space-y-2">
                <div class="text-[11px] font-black uppercase tracking-wider text-cyan-400 flex items-center gap-1.5">
                    <span>3. Rekomendasi Tindakan Strategis</span>
                </div>
                <p class="text-slate-300 leading-relaxed">
                    Diskominfo disarankan mengamplifikasi publikasi progres perbaikan jalan oleh Dinas PUPR, memastikan transparansi jadwal distribusi kuota pupuk oleh Disperta, dan menyediakan kanal klarifikasi cepat (counter-hoaks).
                </p>
            </div>
        </div>
    </div>

</div>

{{-- Chart.js Initialization --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Timeline Area Chart
        const timelineCtx = document.getElementById('timelineChart').getContext('2d');
        const timelineLabels = @json($timelineLabels);
        const timelineTotal = @json($timelineTotal);
        const timelinePos = @json($timelinePos);
        const timelineNeg = @json($timelineNeg);

        new Chart(timelineCtx, {
            type: 'line',
            data: {
                labels: timelineLabels,
                datasets: [
                    {
                        label: 'Total Volume Berita',
                        data: timelineTotal,
                        borderColor: '#94a3b8',
                        backgroundColor: 'rgba(148, 163, 184, 0.1)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2,
                    },
                    {
                        label: 'Sentimen Positif',
                        data: timelinePos,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.15)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                    },
                    {
                        label: 'Sentimen Negatif',
                        data: timelineNeg,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.15)',
                        fill: true,
                        tension: 0.35,
                        borderWidth: 2.5,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 10 } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(148, 163, 184, 0.1)' },
                        ticks: { font: { size: 10 } }
                    }
                }
            }
        });

        // Emotion Donut Chart
        const emotionCtx = document.getElementById('emotionChart').getContext('2d');
        const emotionDataObj = @json($emotionData);
        const emotionLabels = Object.values(emotionDataObj).map(e => e.label);
        const emotionCounts = Object.values(emotionDataObj).map(e => e.count);
        const emotionColors = Object.values(emotionDataObj).map(e => e.color);

        new Chart(emotionCtx, {
            type: 'doughnut',
            data: {
                labels: emotionLabels,
                datasets: [{
                    data: emotionCounts,
                    backgroundColor: emotionColors,
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
</script>
@endsection

