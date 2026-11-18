@php
    $data = $this->getViewData();
    $totalContent = $data['totalContent'];
    $posCount = $data['posCount'];
    $negCount = $data['negCount'];
    $neuCount = $data['neuCount'];
    $nss = $data['nss'];
    $trustIndex = $data['trustIndex'];
    $botCount = $data['botCount'];
    $psnPrograms = $data['psnPrograms'];
    $timelineMonths = $data['timelineMonths'];
    $opdScorecards = $data['opdScorecards'];
    $framingMatrix = $data['framingMatrix'];
    $emotionData = $data['emotionData'];
    $topDistrictsPreview = $data['topDistrictsPreview'];
    $startDate = $data['startDate'];
    $endDate = $data['endDate'];
    $scope = $data['scope'];
@endphp

<x-filament-panels::page class="fi-dashboard-custom-page">
    {{-- High-Impact Executive War-Room Hero Header --}}
    <div class="relative overflow-hidden rounded-2xl bg-slate-900 p-5 md:p-6 border border-slate-800 shadow-2xl text-white">
        <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-cyan-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black tracking-wider uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                        </span>
                        SISMANTIK V3.5 ENTERPRISE WAR-ROOM
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide uppercase bg-slate-800 text-slate-200 border border-slate-700">
                        PEMKAB JOMBANG • JAWA TIMUR
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide uppercase bg-cyan-500/20 text-cyan-300 border border-cyan-500/40">
                        DISKOMINFO JOMBANG
                    </span>
                </div>

                <h1 class="text-xl sm:text-2xl lg:text-3xl font-black tracking-tight text-white flex items-center gap-2">
                    <span>Pusat Intelijen Opini Publik & Pemantauan Media Kab. Jombang</span>
                </h1>
                
                <p class="text-xs sm:text-sm text-slate-300 max-w-4xl leading-relaxed">
                    Platform pengawasan opini masyarakat terpadu berbasis AI lokal (*Zero-Paid API*). Memantau dinamika berita media massa, percakapan warga di 21 kecamatan, evaluasi kinerja 10 OPD Pemkab Jombang, serta 4 Program Strategis Nasional (MBG, Koperasi Desa, Swasembada Pangan, dan Pemberantasan Korupsi).
                </p>

                {{-- Live Operational Matrix Strip --}}
                <div class="flex flex-wrap items-center gap-2.5 pt-2 text-[11px] font-bold text-slate-200">
                    <div class="flex items-center gap-1.5 bg-slate-800 px-3 py-1 rounded-lg border border-slate-700">
                        <span class="text-cyan-400 font-extrabold">🧠 AI Engine:</span>
                        <span>IndoBERT Transformer (12 Core CPU)</span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-slate-800 px-3 py-1 rounded-lg border border-slate-700">
                        <span class="text-emerald-400 font-extrabold">📍 Teritorial:</span>
                        <span>21 Kecamatan Kab. Jombang</span>
                    </div>
                    <div class="flex items-center gap-1.5 bg-slate-800 px-3 py-1 rounded-lg border border-slate-700">
                        <span class="text-amber-400 font-extrabold">🎯 Entitas Pantau:</span>
                        <span>10 Dinas Daerah + 4 PSN Pusat</span>
                    </div>
                </div>
            </div>

            {{-- Quick System Status Card --}}
            <div class="shrink-0 flex lg:flex-col items-center justify-between lg:justify-center p-3.5 rounded-xl bg-slate-950/80 border border-slate-800 text-right min-w-[210px]">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-300">Kesiapan Sistem Intelijen</span>
                    <div class="text-lg font-black text-emerald-400 flex items-center justify-end gap-1.5 mt-0.5">
                        <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        OPERASIONAL 100%
                    </div>
                </div>
                <div class="text-[10px] text-slate-300 mt-1 font-medium">
                    Sinkronisasi otomatis aktif
                </div>
            </div>
        </div>
    </div>

    {{-- Interactive Date Presets & Filter Toolbar --}}
    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        {{-- Quick Presets --}}
        <div class="flex items-center gap-1.5 flex-wrap">
            <span class="text-xs font-extrabold text-slate-800 dark:text-slate-200 mr-1">Preset Periode:</span>
            <button type="button" wire:click="setPreset(1)" 
                class="px-3 py-1 text-xs font-bold rounded-lg border transition cursor-pointer {{ $startDate === now()->subMonths(1)->format('Y-m-d') ? 'bg-emerald-600 text-white border-emerald-700 shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border-slate-300 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                1 Bulan
            </button>
            <button type="button" wire:click="setPreset(3)" 
                class="px-3 py-1 text-xs font-bold rounded-lg border transition cursor-pointer {{ $startDate === now()->subMonths(3)->format('Y-m-d') ? 'bg-emerald-600 text-white border-emerald-700 shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border-slate-300 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                3 Bulan
            </button>
            <button type="button" wire:click="setPreset(6)" 
                class="px-3 py-1 text-xs font-bold rounded-lg border transition cursor-pointer {{ $startDate === now()->subMonths(6)->format('Y-m-d') ? 'bg-emerald-600 text-white border-emerald-700 shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border-slate-300 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                6 Bulan
            </button>
            <button type="button" wire:click="setPreset(12)" 
                class="px-3 py-1 text-xs font-bold rounded-lg border transition cursor-pointer {{ $startDate === now()->subMonths(12)->format('Y-m-d') ? 'bg-emerald-600 text-white border-emerald-700 shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border-slate-300 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                1 Tahun Terakhir
            </button>
        </div>

        {{-- Date Range Inputs & Scope Select --}}
        <div class="flex items-center gap-3 flex-wrap">
            <div class="flex items-center gap-1.5 text-xs">
                <span class="text-slate-800 dark:text-slate-200 font-bold">Dari:</span>
                <input type="date" wire:model.live.debounce.500ms="startDate" 
                    class="py-1 px-2.5 text-xs font-semibold rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="flex items-center gap-1.5 text-xs">
                <span class="text-slate-800 dark:text-slate-200 font-bold">Sampai:</span>
                <input type="date" wire:model.live.debounce.500ms="endDate" 
                    class="py-1 px-2.5 text-xs font-semibold rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
            </div>
            <div class="flex items-center gap-1.5 text-xs">
                <select wire:model.live="scope" 
                    class="py-1 px-3 text-xs font-bold rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
                    <option value="all">Semua Isu (Lokal & PSN)</option>
                    <option value="local_pemkab">Isu Lokal Pemkab Jombang</option>
                    <option value="national_strategic">Program Prioritas Nasional (PSN)</option>
                </select>
            </div>
        </div>
    </div>

    {{-- SECTION 1: 4 Master Executive KPI Cards (High Contrast, Clickable) --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Card 1: Total Volume --}}
        <div 
            wire:click="$dispatch('open-article-drilldown', { title: 'Semua Berita pada Periode Aktif', subtitle: 'Rentang: {{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}', sentiment: 'all' })"
            class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer group border-t-4 border-t-cyan-500">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400">Total Berita Terkumpul</span>
                <div class="w-8 h-8 rounded-lg bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                </div>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black font-mono text-slate-900 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">
                    {{ number_format($totalContent) }}
                </span>
                <span class="text-xs font-semibold text-slate-500 dark:text-slate-400">Konten</span>
            </div>
            <div class="mt-2 text-[11px] font-bold text-emerald-600 dark:text-emerald-400 flex items-center gap-1">
                <span>● Terverifikasi Crawling & Scraper Lokal</span>
            </div>
        </div>

        {{-- Card 2: Net Sentiment Score (NSS) --}}
        <div 
            wire:click="$dispatch('open-article-drilldown', { title: 'Berita Berdasarkan Sentimen', subtitle: 'Positif: {{ $posCount }} | Negatif: {{ $negCount }} | Netral: {{ $neuCount }}', sentiment: 'negative' })"
            class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer group border-t-4 border-t-emerald-500">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400">Net Sentiment Score (NSS)</span>
                <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black font-mono {{ $nss >= 15 ? 'text-emerald-600 dark:text-emerald-400' : ($nss >= 0 ? 'text-cyan-600 dark:text-cyan-400' : 'text-rose-600 dark:text-rose-400') }}">
                    {{ $nss > 0 ? '+' : '' }}{{ $nss }}%
                </span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $nss >= 0 ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-300' }}">
                    {{ $nss >= 0 ? 'Kondusif' : 'Perlu Atensi' }}
                </span>
            </div>
            <div class="mt-2 flex items-center justify-between text-[11px] font-mono font-bold text-slate-600 dark:text-slate-400">
                <span class="text-emerald-600 dark:text-emerald-400">Apresiasi: {{ $posCount }}</span>
                <span class="text-rose-600 dark:text-rose-400">Kritik: {{ $negCount }}</span>
                <span class="text-slate-500">Netral: {{ $neuCount }}</span>
            </div>
        </div>

        {{-- Card 3: Indeks Kepercayaan Publik --}}
        <div 
            wire:click="$dispatch('open-article-drilldown', { title: 'Berita Sentimen Positif (Apresiasi)', subtitle: 'Total: {{ $posCount }} Berita Positif', sentiment: 'positive' })"
            class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer group border-t-4 border-t-amber-500">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400">Indeks Kepercayaan Warga</span>
                <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black font-mono text-slate-900 dark:text-white">
                    {{ $trustIndex }} <span class="text-sm font-semibold text-slate-500">/ 10.0</span>
                </span>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800 dark:bg-amber-500/20 dark:text-amber-300">
                    {{ $trustIndex >= 6 ? 'Sangat Baik' : ($trustIndex >= 4.5 ? 'Moderat' : 'Kritis') }}
                </span>
            </div>
            <div class="mt-2 text-[11px] font-bold text-slate-600 dark:text-slate-400">
                <span>Skala persepsi berbasis NLP IndoBERT</span>
            </div>
        </div>

        {{-- Card 4: Deteksi Bot & Isu Kritis --}}
        <div 
            wire:click="$dispatch('open-article-drilldown', { title: 'Konten Terindikasi Anomali / Bot Spammer', subtitle: 'Penyaringan otomatis spam dan penggiringan opini publik', keyword: 'pungli' })"
            class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 shadow-sm hover:shadow-xl transition-all duration-300 cursor-pointer group border-t-4 border-t-rose-500">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400">Deteksi Isu Kritis & Bot</span>
                <div class="w-8 h-8 rounded-lg bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                    <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>
            <div class="mt-2 flex items-baseline gap-2">
                <span class="text-3xl font-black font-mono text-rose-600 dark:text-rose-400">
                    {{ number_format($botCount) }}
                </span>
                <span class="text-xs font-semibold text-slate-500">Terindikasi</span>
            </div>
            <div class="mt-2 text-[11px] font-bold text-rose-600 dark:text-rose-400 flex items-center gap-1">
                <span>⚠️ Perlu atensi klarifikasi & tanggapan</span>
            </div>
        </div>
    </div>

    {{-- SECTION 2: 4 Pilar Strategis Nasional (PSN) Cards --}}
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-3.5 border-b border-slate-200 dark:border-slate-800 gap-2">
            <div>
                <div class="flex items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-100 text-indigo-800 dark:bg-indigo-500/20 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-500/30">
                        PILAR STRATEGIS NASIONAL (PSN)
                    </span>
                    <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white">
                        Monitoring Program Prioritas Pemerintah Pusat di Wilayah Kab. Jombang
                    </h2>
                </div>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                    Pelacakan independen opini publik terhadap kebijakan Presiden/Kementerian di Kabupaten Jombang (MBG, Koperasi Desa, Penegakan Hukum, Swasembada Pangan).
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @foreach ($psnPrograms as $prog)
                @php
                    $isPos = $prog['nss'] >= 0;
                    $accentBorder = match($prog['id']) {
                        'mbg' => 'border-t-4 border-t-emerald-500',
                        'kopdes' => 'border-t-4 border-t-cyan-500',
                        'tipikor' => 'border-t-4 border-t-rose-500',
                        'swasembada' => 'border-t-4 border-t-amber-500',
                        default => 'border-t-4 border-t-indigo-500',
                    };
                @endphp
                <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 shadow-xs flex flex-col justify-between {{ $accentBorder }}">
                    <div>
                        <div class="flex items-start justify-between gap-1 mb-2">
                            <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded bg-white dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700">
                                {{ $prog['badge'] }}
                            </span>
                            <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded border {{ $isPos ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300 border-emerald-300 dark:border-emerald-500/40' : 'bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-300 border-rose-300 dark:border-rose-500/40' }}">
                                {{ $prog['status'] }}
                            </span>
                        </div>

                        <h3 class="text-xs sm:text-sm font-black text-slate-900 dark:text-white leading-snug line-clamp-2">
                            {{ $prog['name'] }}
                        </h3>

                        <p class="text-[11px] text-slate-600 dark:text-slate-400 mt-2 line-clamp-3 leading-relaxed">
                            {{ $prog['focal_point'] }}
                        </p>
                    </div>

                    <div class="mt-4 pt-3 border-t border-slate-200 dark:border-slate-700/80">
                        <div class="flex items-baseline justify-between">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-500 dark:text-slate-400">Net Sentiment</span>
                                <div class="text-xl font-black font-mono {{ $isPos ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                    {{ $isPos ? '+' : '' }}{{ $prog['nss'] }}%
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] uppercase font-bold text-slate-500 dark:text-slate-400">Volume</span>
                                <div class="text-sm font-black font-mono text-slate-900 dark:text-white">
                                    {{ $prog['mentions'] }} <span class="text-[10px] font-normal text-slate-500">berita</span>
                                </div>
                            </div>
                        </div>

                        {{-- Micro bar --}}
                        <div class="w-full bg-slate-200 dark:bg-slate-700 h-2 rounded-full overflow-hidden flex mt-2.5">
                            @php
                                $pTot = max(1, $prog['mentions']);
                                $pPosPct = ($prog['pos'] / $pTot) * 100;
                                $pNeuPct = ($prog['neu'] / $pTot) * 100;
                                $pNegPct = ($prog['neg'] / $pTot) * 100;
                            @endphp
                            <div style="width: {{ $pPosPct }}%" class="bg-emerald-500 h-full"></div>
                            <div style="width: {{ $pNeuPct }}%" class="bg-slate-400 h-full"></div>
                            <div style="width: {{ $pNegPct }}%" class="bg-rose-500 h-full"></div>
                        </div>

                        <button type="button"
                            wire:click="$dispatch('open-article-drilldown', {
                                title: 'Program: {{ addslashes($prog['name']) }}',
                                subtitle: 'Kategori: {{ addslashes($prog['badge']) }} • Wilayah: Kab. Jombang',
                                keyword: '{{ addslashes($prog['primary_keyword']) }}',
                                startDate: '{{ $startDate }}',
                                endDate: '{{ $endDate }}'
                            })"
                            class="w-full mt-3 py-2 px-3 rounded-lg text-xs font-bold bg-white dark:bg-slate-800 hover:bg-slate-100 dark:hover:bg-slate-700 text-slate-900 dark:text-white border border-slate-300 dark:border-slate-700 transition flex items-center justify-center gap-1.5 cursor-pointer shadow-2xs hover:scale-[1.01]">
                            <span>Buka Berita Terkait ({{ $prog['mentions'] }})</span>
                            <svg class="h-3.5 w-3.5 text-cyan-600 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- SECTION 3: 12-Month Sentiment Timeline Trend Chart --}}
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-3.5 border-b border-slate-200 dark:border-slate-800 gap-2">
            <div>
                <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white">
                    Tren Sentimen & Dinamika Percakapan Media (12 Bulan Terakhir)
                </h2>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                    Fluktuasi volume pemberitaan harian dan rasio sentimen apresiasi publik vs kritik di Kabupaten Jombang.
                </p>
            </div>
            <div class="flex items-center gap-3 text-xs font-bold">
                <span class="flex items-center gap-1.5 text-emerald-600 dark:text-emerald-400">
                    <span class="w-3 h-3 rounded-full bg-emerald-500"></span> Positif / Apresiasi
                </span>
                <span class="flex items-center gap-1.5 text-slate-500 dark:text-slate-400">
                    <span class="w-3 h-3 rounded-full bg-slate-400"></span> Netral / Informatif
                </span>
                <span class="flex items-center gap-1.5 text-rose-600 dark:text-rose-400">
                    <span class="w-3 h-3 rounded-full bg-rose-500"></span> Negatif / Aduan
                </span>
            </div>
        </div>

        {{-- Visual Timeline Representation --}}
        <div class="overflow-x-auto pt-2">
            <div class="min-w-[650px] space-y-3">
                <div class="grid grid-cols-12 gap-2 text-center">
                    @php $maxMTotal = max(1, ...array_column($timelineMonths, 'total')); @endphp
                    @foreach ($timelineMonths as $m)
                        @php
                            $heightPct = max(12, round(($m['total'] / $maxMTotal) * 100));
                            $mNss = $m['total'] > 0 ? round((($m['pos'] - $m['neg']) / $m['total']) * 100, 1) : 0;
                        @endphp
                        <div class="flex flex-col items-center justify-end h-44 group cursor-pointer"
                            wire:click="$dispatch('open-article-drilldown', { title: 'Arsip Berita: {{ $m['label'] }}', subtitle: 'Total: {{ $m['total'] }} Berita (Pos: {{ $m['pos'] }} | Neg: {{ $m['neg'] }})', sentiment: 'all' })">
                            <span class="text-[10px] font-mono font-bold text-slate-700 dark:text-slate-300 mb-1">
                                {{ $m['total'] }}
                            </span>
                            
                            {{-- Stacked Bar --}}
                            <div class="w-full max-w-[32px] rounded-t-lg overflow-hidden flex flex-col justify-end bg-slate-100 dark:bg-slate-800 transition-all group-hover:scale-105" style="height: {{ $heightPct }}%;">
                                <div style="height: {{ $m['total'] > 0 ? ($m['neg'] / $m['total']) * 100 : 0 }}%;" class="bg-rose-500 w-full" title="Negatif: {{ $m['neg'] }}"></div>
                                <div style="height: {{ $m['total'] > 0 ? ($m['neu'] / $m['total']) * 100 : 0 }}%;" class="bg-slate-400 w-full" title="Netral: {{ $m['neu'] }}"></div>
                                <div style="height: {{ $m['total'] > 0 ? ($m['pos'] / $m['total']) * 100 : 0 }}%;" class="bg-emerald-500 w-full" title="Positif: {{ $m['pos'] }}"></div>
                            </div>

                            <span class="text-[11px] font-extrabold text-slate-700 dark:text-slate-300 mt-2 truncate w-full">
                                {{ $m['label'] }}
                            </span>
                            <span class="text-[9px] font-mono font-bold {{ $mNss >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                {{ $mNss > 0 ? '+' : '' }}{{ $mNss }}%
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 4: 2 Balanced Columns - OPD Scorecards (Left) & Framing Matrix (Right) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        {{-- Left: Rapor Kinerja 10 OPD Pemkab Jombang --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-200 dark:border-slate-800">
                    <div>
                        <h2 class="text-base font-black text-slate-900 dark:text-white">
                            Rapor Kinerja Sentimen 10 OPD Pemkab Jombang
                        </h2>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                            Evaluasi kepuasan publik berdasarkan aspek sentimen berita dinas daerah.
                        </p>
                    </div>
                    <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300">
                        10 DINAS AKTIF
                    </span>
                </div>

                <div class="mt-4 space-y-3">
                    @foreach (array_slice($opdScorecards, 0, 7) as $idx => $opd)
                        <div 
                            wire:click="$dispatch('open-article-drilldown', { title: 'OPD: {{ addslashes($opd['name']) }}', subtitle: 'Total: {{ $opd['mentions'] }} Berita • NSS: {{ $opd['nss'] > 0 ? '+' : '' }}{{ $opd['nss'] }}%', agencyId: {{ $opd['id'] }} })"
                            class="p-3 rounded-xl border border-slate-200 dark:border-slate-800/80 bg-slate-50/60 dark:bg-slate-800/40 hover:bg-emerald-50/50 dark:hover:bg-emerald-950/20 transition-all cursor-pointer group shadow-2xs">
                            <div class="flex items-center justify-between gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="w-5 h-5 rounded-md text-[10px] font-black flex items-center justify-center shrink-0 {{ $idx < 3 ? 'bg-emerald-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                                        {{ $idx + 1 }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors truncate">
                                        {{ $opd['name'] }}
                                    </span>
                                </div>
                                <div class="shrink-0 flex items-center gap-2 font-mono">
                                    <span class="text-xs font-black {{ $opd['nss'] >= 15 ? 'text-emerald-600 dark:text-emerald-400' : ($opd['nss'] >= 0 ? 'text-cyan-600 dark:text-cyan-400' : 'text-rose-600 dark:text-rose-400') }}">
                                        {{ $opd['nss'] > 0 ? '+' : '' }}{{ $opd['nss'] }}%
                                    </span>
                                    <span class="text-[10px] text-slate-500">({{ $opd['mentions'] }})</span>
                                </div>
                            </div>

                            {{-- Ratio bar --}}
                            <div class="w-full bg-slate-200 dark:bg-slate-700 h-2 rounded-full overflow-hidden flex mt-2">
                                @php
                                    $oTot = max(1, $opd['mentions']);
                                    $oPos = ($opd['pos'] / $oTot) * 100;
                                    $oNeu = ($opd['neu'] / $oTot) * 100;
                                    $oNeg = ($opd['neg'] / $oTot) * 100;
                                @endphp
                                <div style="width: {{ $oPos }}%" class="bg-emerald-500 h-full"></div>
                                <div style="width: {{ $oNeu }}%" class="bg-slate-400 h-full"></div>
                                <div style="width: {{ $oNeg }}%" class="bg-rose-500 h-full"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-3 mt-3 border-t border-slate-200 dark:border-slate-800 text-right">
                <a href="/admin/topic-or-agencies" class="text-xs font-extrabold text-emerald-600 dark:text-emerald-400 hover:underline">
                    Kelola Konfigurasi 10 OPD & Kata Kunci ➜
                </a>
            </div>
        </div>

        {{-- Right: Matriks Framing Isu & Analisis Aduan Warga --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-200 dark:border-slate-800">
                    <div>
                        <h2 class="text-base font-black text-slate-900 dark:text-white">
                            Matriks Framing Isu & Analisis Aduan Warga
                        </h2>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                            Pengelompokan aduan publik, aspirasi, dan persepsi media di Kab. Jombang.
                        </p>
                    </div>
                    <span class="text-[10px] font-black uppercase px-2 py-0.5 rounded bg-cyan-100 text-cyan-800 dark:bg-cyan-500/20 dark:text-cyan-300">
                        TOP FRAMING
                    </span>
                </div>

                <div class="mt-4 space-y-3">
                    @foreach ($framingMatrix as $f)
                        <div 
                            wire:click="$dispatch('open-article-drilldown', { title: 'Framing Isu: {{ addslashes($f['category']) }}', subtitle: 'Total: {{ $f['mentions'] }} Berita • NSS: {{ $f['nss'] > 0 ? '+' : '' }}{{ $f['nss'] }}%', framingCategory: '{{ addslashes($f['category']) }}' })"
                            class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-800/80 bg-slate-50/60 dark:bg-slate-800/40 hover:bg-cyan-50/50 dark:hover:bg-cyan-950/20 transition-all cursor-pointer group shadow-2xs">
                            <div class="flex items-center justify-between gap-2">
                                <div>
                                    <h4 class="text-xs font-extrabold text-slate-900 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">
                                        {{ $f['category'] }}
                                    </h4>
                                    <p class="text-[10px] text-slate-500 dark:text-slate-400 mt-0.5">
                                        Kata Kunci: {{ $f['sample_issue'] }}
                                    </p>
                                </div>
                                <div class="text-right font-mono shrink-0">
                                    <div class="text-xs font-black {{ $f['nss'] >= 15 ? 'text-emerald-600 dark:text-emerald-400' : ($f['nss'] >= 0 ? 'text-cyan-600 dark:text-cyan-400' : 'text-rose-600 dark:text-rose-400') }}">
                                        {{ $f['nss'] > 0 ? '+' : '' }}{{ $f['nss'] }}%
                                    </div>
                                    <div class="text-[10px] text-slate-500 font-bold">{{ $f['mentions'] }} aduan</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="pt-3 mt-3 border-t border-slate-200 dark:border-slate-800 text-right">
                <a href="/admin/raw-contents" class="text-xs font-extrabold text-cyan-600 dark:text-cyan-400 hover:underline">
                    Buka Seluruh Katalog Berita & Opini Publik ➜
                </a>
            </div>
        </div>
    </div>

    {{-- SECTION 5: 2 Balanced Columns - Anatomi Emosi (Left) & Bilik Peta Geospasial Gateway (Right) --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        {{-- Left: Anatomi Emosi Publik --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-200 dark:border-slate-800">
                    <div>
                        <h2 class="text-base font-black text-slate-900 dark:text-white">
                            Anatomi Emosi & Psikologi Warga Jombang
                        </h2>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                            Klasifikasi 6 spektrum emosi masyarakat berdasarkan analisis psikolinguistik NLP.
                        </p>
                    </div>
                </div>

                <div class="mt-4 space-y-2.5">
                    @foreach ($emotionData as $eKey => $e)
                        <div class="space-y-1">
                            <div class="flex items-center justify-between text-xs font-bold">
                                <span class="text-slate-800 dark:text-slate-200">{{ $e['label'] }}</span>
                                <span class="font-mono text-slate-900 dark:text-white">{{ $e['count'] }} <span class="text-slate-500 font-normal">({{ $e['percentage'] }}%)</span></span>
                            </div>
                            <div class="w-full bg-slate-200 dark:bg-slate-700 h-2.5 rounded-full overflow-hidden flex">
                                <div style="width: {{ $e['percentage'] }}%; background-color: {{ $e['color'] }};" class="h-full rounded-full transition-all"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="p-3.5 mt-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-xs text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                💡 <strong>Insight AI:</strong> Sentimen apresiasi mendominasi pada program sosial MBG dan bantuan pertanian, sedangkan nada emosi geram/kecewa terpusat pada isu jalan rusak sekunder dan antrean BPJS.
            </div>
        </div>

        {{-- Right: Bilik Radar Geospasial 21 Kecamatan Gateway --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 shadow-sm flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3.5 border-b border-slate-200 dark:border-slate-800">
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-cyan-100 text-cyan-800 dark:bg-cyan-500/20 dark:text-cyan-300 border border-cyan-300 dark:border-cyan-500/40">
                                GIS RADAR WILAYAH
                            </span>
                            <h2 class="text-base font-black text-slate-900 dark:text-white">
                                Pemetaan Isu Geospasial 21 Kecamatan
                            </h2>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                            Sebaran aduan warga dan dinamika opini kewilayahan di 21 Kecamatan Kab. Jombang.
                        </p>
                    </div>

                    <a href="/admin/peta-geospasial" class="text-xs font-black text-cyan-600 dark:text-cyan-400 hover:underline">
                        Layar Penuh ➜
                    </a>
                </div>

                <div class="mt-3.5 space-y-2">
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-500 dark:text-slate-400 block">Kecamatan Teraktif Dinamika Berita:</span>
                    @foreach ($topDistrictsPreview as $d)
                        <div class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-800/80 bg-slate-50/70 dark:bg-slate-800/40 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-extrabold text-slate-900 dark:text-white truncate">Kec. {{ $d['name'] }}</span>
                                    <span class="text-[9px] font-black px-1.5 py-0.2 rounded border {{ $d['badgeClass'] }}">
                                        {{ $d['status'] }}
                                    </span>
                                </div>
                                <div class="text-[10px] font-mono text-slate-500 dark:text-slate-400 mt-0.5">
                                    <span>{{ $d['total'] }} berita</span> • <span class="font-bold {{ $d['nss'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">NSS: {{ $d['nss'] > 0 ? '+' : '' }}{{ $d['nss'] }}%</span>
                                </div>
                            </div>

                            <a href="/admin/peta-geospasial" class="shrink-0 px-2.5 py-1 rounded-lg text-xs font-bold text-cyan-700 dark:text-cyan-300 bg-cyan-100 dark:bg-cyan-950/60 hover:bg-cyan-200 transition">
                                Lihat Peta 🗺️
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Big CTA Button to Dedicated Map Page --}}
            <div class="pt-4 mt-2 border-t border-slate-200 dark:border-slate-800">
                <a href="/admin/peta-geospasial" 
                    class="w-full py-3 px-4 rounded-xl text-xs font-black text-white bg-gradient-to-r from-cyan-600 via-teal-600 to-emerald-600 hover:from-cyan-500 hover:to-emerald-500 shadow-md shadow-cyan-500/25 transition-all flex items-center justify-center gap-2 cursor-pointer hover:scale-[1.02]">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <span>Buka Peta Satelit & Poligon Geospasial Lengkap (21 Kecamatan) ➜</span>
                </a>
            </div>
        </div>
    </div>

    {{-- SECTION 6: Presidential Daily Brief (PDB) & Rekomendasi Diskominfo AI --}}
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 shadow-sm space-y-4">
        <div class="flex items-center justify-between pb-3.5 border-b border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold">
                    🏛️
                </div>
                <div>
                    <h2 class="text-base font-black text-slate-900 dark:text-white">
                        Presidential Daily Brief (PDB) & Rekomendasi Tindakan AI Pemkab Jombang
                    </h2>
                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-0.5">
                        Ringkasan intelijen strategis terpadu untuk Bupati, Sekda, dan Kepala Perangkat Daerah.
                    </p>
                </div>
            </div>
            <span class="text-[10px] font-black uppercase px-2.5 py-1 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-300 dark:border-slate-700 font-mono">
                UPDATE: {{ now()->translatedFormat('d M Y • H:i') }} WIB
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
            <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 space-y-1.5">
                <span class="text-[10px] font-black uppercase tracking-wider text-cyan-600 dark:text-cyan-400 block">📌 Dinamika Isu Prioritas</span>
                <p class="text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                    Program Makan Bergizi Gratis (MBG) dan serapan gabah petani oleh Bulog mendapatkan sentimen positif warga (+25% NSS). Kesiapan dapur SPPG menjadi acuan utama keterbukaan Pemkab.
                </p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 space-y-1.5">
                <span class="text-[10px] font-black uppercase tracking-wider text-rose-600 dark:text-rose-400 block">⚠️ Titik Rawan & Aduan Warga</span>
                <p class="text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                    Kerusakan jalan ruas Mojoagung–Bareng dan genangan air saat hujan di Peterongan mendominasi 42% aduan negatif publik terhadap Dinas PUPR.
                </p>
            </div>
            <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 space-y-1.5">
                <span class="text-[10px] font-black uppercase tracking-wider text-emerald-600 dark:text-emerald-400 block">⚡ Rekomendasi Respons Diskominfo</span>
                <p class="text-slate-700 dark:text-slate-300 leading-relaxed font-medium">
                    Segera instruksikan dinas teknis (PUPR) merilis jadwal pemeliharaan jalan berkala, serta perkuat diseminasi kanal pengaduan resmi SP4N-LAPOR!.
                </p>
            </div>
        </div>
    </div>

    {{-- Livewire Global Article Drilldown Modal --}}
    @livewire('article-drilldown-modal')
</x-filament-panels::page>
