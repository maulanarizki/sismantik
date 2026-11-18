@php
    $data = $this->getViewData();
    $districtStats = $data['districtStats'];
    $leaderboardByMentions = $data['leaderboardByMentions'];
    $leaderboardByNegative = $data['leaderboardByNegative'];
    $leaderboardByPositive = $data['leaderboardByPositive'];
    $totalMentions = $data['totalTerritorialMentions'];
    $rawanCount = $data['rawanCount'];
    $kondusifCount = $data['kondusifCount'];
    $topVolume = $data['topVolumeDistrict'];
    $topPos = $data['topPositiveDistrict'];
    $topCrit = $data['topCriticalDistrict'];
    $geoJson = $data['geoJsonData'];
@endphp

{{-- Direct Asset Inclusions for Guaranteed Availability --}}
<link rel="stylesheet" href="/vendor/leaflet/leaflet.css" />
<script src="/vendor/leaflet/leaflet.js"></script>

<x-filament-panels::page class="fi-peta-geospasial-page">
    {{-- High-Impact GIS Command Center Hero Header (Always high contrast) --}}
    <div class="relative overflow-hidden rounded-2xl bg-slate-900 p-5 md:p-6 border border-slate-800 shadow-2xl text-white">
        {{-- Ambient Cyber Glow --}}
        <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-cyan-500/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-emerald-500/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div class="space-y-2">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-black tracking-wider uppercase bg-cyan-500/20 text-cyan-300 border border-cyan-500/40">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-400"></span>
                        </span>
                        GIS RADAR & POLIGON TERITORIAL KAB. JOMBANG
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide uppercase bg-slate-800 text-slate-200 border border-slate-700">
                        21 KECAMATAN TERPETAKAN
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold tracking-wide uppercase bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                        GADM 4.1 OFFLINE-READY
                    </span>
                </div>

                <h1 class="text-xl sm:text-2xl lg:text-3xl font-black tracking-tight text-white flex items-center gap-2">
                    <span>Pusat Komando Geospasial Sentimen & Dinamika Wilayah</span>
                </h1>
                
                <p class="text-xs sm:text-sm text-slate-300 max-w-4xl leading-relaxed">
                    Pemetaan geospasial interaktif berbasis Leaflet GIS dan batas poligon resmi 21 kecamatan Kabupaten Jombang. Mendeteksi persebaran berita, hotspot keluhan warga, dan sentimen kebijakan pembangunan daerah secara presisi per teritori.
                </p>
            </div>

            {{-- 4 Territorial Quick Metric Pills (High contrast in any mode) --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-2 gap-2.5 shrink-0 min-w-[300px]">
                <div class="p-3 rounded-xl bg-slate-950/80 border border-slate-800">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-300 block">Total Berita Teritori</span>
                    <div class="text-xl font-black font-mono text-cyan-400 mt-0.5">{{ number_format($totalMentions) }}</div>
                </div>
                <div class="p-3 rounded-xl bg-slate-950/80 border border-slate-800">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-300 block">Zona Kondusif</span>
                    <div class="text-xl font-black font-mono text-emerald-400 mt-0.5">{{ $kondusifCount }} <span class="text-[11px] font-normal text-slate-300">Wilayah</span></div>
                </div>
                <div class="p-3 rounded-xl bg-slate-950/80 border border-slate-800">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-300 block">Perlu Atensi</span>
                    <div class="text-xl font-black font-mono text-rose-400 mt-0.5">{{ $rawanCount }} <span class="text-[11px] font-normal text-slate-300">Wilayah</span></div>
                </div>
                <div class="p-3 rounded-xl bg-slate-950/80 border border-slate-800">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-300 block">Wilayah Teraktif</span>
                    <div class="text-sm font-extrabold text-white truncate mt-0.5">{{ $topVolume['name'] ?? 'Jombang' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Interactive GIS Filter & Presets Toolbar (Crisp high-contrast) --}}
    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        {{-- Quick Date Presets --}}
        <div class="flex items-center gap-1.5 flex-wrap">
            <span class="text-xs font-extrabold text-slate-700 dark:text-slate-300 mr-1">Preset Periode:</span>
            <button type="button" wire:click="setPreset(1)" 
                class="px-3 py-1 text-xs font-bold rounded-lg border transition cursor-pointer {{ $startDate === now()->subMonths(1)->format('Y-m-d') ? 'bg-cyan-600 text-white border-cyan-700 shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border-slate-300 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                1 Bulan
            </button>
            <button type="button" wire:click="setPreset(3)" 
                class="px-3 py-1 text-xs font-bold rounded-lg border transition cursor-pointer {{ $startDate === now()->subMonths(3)->format('Y-m-d') ? 'bg-cyan-600 text-white border-cyan-700 shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border-slate-300 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                3 Bulan
            </button>
            <button type="button" wire:click="setPreset(6)" 
                class="px-3 py-1 text-xs font-bold rounded-lg border transition cursor-pointer {{ $startDate === now()->subMonths(6)->format('Y-m-d') ? 'bg-cyan-600 text-white border-cyan-700 shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border-slate-300 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                6 Bulan
            </button>
            <button type="button" wire:click="setPreset(12)" 
                class="px-3 py-1 text-xs font-bold rounded-lg border transition cursor-pointer {{ $startDate === now()->subMonths(12)->format('Y-m-d') ? 'bg-cyan-600 text-white border-cyan-700 shadow-sm' : 'bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-slate-200 border-slate-300 dark:border-slate-700 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                1 Tahun Terakhir
            </button>
        </div>

        {{-- Date Range Inputs & Scope Select --}}
        <div class="flex items-center gap-3 flex-wrap">
            <div class="flex items-center gap-1.5 text-xs">
                <span class="text-slate-700 dark:text-slate-300 font-bold">Dari:</span>
                <input type="date" wire:model.live.debounce.500ms="startDate" 
                    class="py-1 px-2.5 text-xs font-semibold rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-cyan-500">
            </div>
            <div class="flex items-center gap-1.5 text-xs">
                <span class="text-slate-700 dark:text-slate-300 font-bold">Sampai:</span>
                <input type="date" wire:model.live.debounce.500ms="endDate" 
                    class="py-1 px-2.5 text-xs font-semibold rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-cyan-500">
            </div>
            <div class="flex items-center gap-1.5 text-xs">
                <select wire:model.live="scope" 
                    class="py-1 px-3 text-xs font-bold rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-cyan-500">
                    <option value="all">Semua Isu (Lokal & PSN)</option>
                    <option value="local_pemkab">Isu Lokal Pemkab Jombang</option>
                    <option value="national_strategic">Program Prioritas Nasional (PSN)</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Main GIS Workstation: Leaflet Map (Left) + Intelligence Inspector / Leaderboard (Right) --}}
    <div 
        x-data="jombangGisWorkspace({
            districtsData: {{ Js::from($districtStats) }},
            geoJsonData: {{ Js::from($geoJson) }},
            startDate: '{{ $startDate }}',
            endDate: '{{ $endDate }}'
        })"
        x-init="initWorkspace()"
        class="grid grid-cols-1 xl:grid-cols-12 gap-5"
    >
        {{-- Left: Massive Interactive Leaflet Map Canvas (8 Columns) --}}
        <div class="xl:col-span-8 flex flex-col gap-3">
            {{-- Map Action Toolbar --}}
            <div class="flex items-center justify-between gap-3 p-3.5 rounded-xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 text-xs shadow-xs">
                {{-- Quick District Jump Dropdown --}}
                <div class="flex items-center gap-2">
                    <span class="font-extrabold text-slate-800 dark:text-slate-200">Fokus Wilayah:</span>
                    <select 
                        x-model="selectedDistrictName" 
                        @change="focusDistrictByName(selectedDistrictName)"
                        class="text-xs font-bold py-1.5 px-3 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-cyan-500">
                        <option value="">-- Pilih Kecamatan (21 Wilayah) --</option>
                        @foreach ($leaderboardByMentions as $d)
                            <option value="{{ $d['name'] }}">{{ $d['name'] }} ({{ $d['total'] }} Berita • NSS: {{ $d['nss'] > 0 ? '+' : '' }}{{ $d['nss'] }}%)</option>
                        @endforeach
                    </select>
                </div>

                {{-- Map Reset Button --}}
                <div class="flex items-center gap-2">
                    <button 
                        type="button" 
                        @click="resetMapBounds()" 
                        class="px-3.5 py-1.5 text-xs font-bold rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-200 border border-slate-300 dark:border-slate-700 transition flex items-center gap-1.5 cursor-pointer shadow-xs">
                        <svg class="w-3.5 h-3.5 text-cyan-600 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                        </svg>
                        <span>Reset Posisi Peta</span>
                    </button>
                </div>
            </div>

            {{-- The Leaflet Map Canvas --}}
            <div class="relative w-full rounded-2xl overflow-hidden border border-slate-300 dark:border-slate-800 shadow-2xl bg-slate-950" style="height: 680px; min-height: 680px;">
                <div id="jombang-standalone-gis-map" wire:ignore class="w-full h-full z-10" style="min-height: 680px;"></div>

                {{-- Floating Legend Badge (High-contrast dark badge) --}}
                <div class="absolute top-3 left-3 z-[400] p-3 rounded-xl bg-slate-950/90 backdrop-blur-md border border-slate-800 text-[11px] font-bold text-white shadow-2xl space-y-1.5 pointer-events-none">
                    <div class="text-[10px] uppercase font-black tracking-wider text-slate-300 mb-1">Indeks Sentimen Poligon</div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-xs shadow-emerald-500/50"></span>
                        <span class="text-slate-200">Sangat Kondusif (NSS &ge; +15%)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-cyan-500 shadow-xs shadow-cyan-500/50"></span>
                        <span class="text-slate-200">Kondusif (NSS 0 s/d +15%)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-amber-500 shadow-xs shadow-amber-500/50"></span>
                        <span class="text-slate-200">Perlu Perhatian (NSS &lt; 0%)</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-rose-500 shadow-xs shadow-rose-500/50"></span>
                        <span class="text-slate-200">Rawan Keluhan Warga</span>
                    </div>
                </div>

                {{-- Floating Coordinates & System Tag --}}
                <div class="absolute bottom-3 left-3 z-[400] pointer-events-none px-3 py-1.5 rounded-lg bg-slate-950/90 backdrop-blur-md border border-slate-800 text-[10px] font-mono text-slate-300 flex items-center gap-3">
                    <span class="text-cyan-400 font-bold">● KABUPATEN JOMBANG</span>
                    <span>LAT: -7.546 • LON: 112.233</span>
                    <span>21 POLIGON RESMI GADM</span>
                </div>
            </div>
        </div>

        {{-- Right: Intelligence Inspector & District Ranking Leaderboard (4 Columns) --}}
        <div class="xl:col-span-4 flex flex-col gap-4">
            {{-- Tabs Switcher: Inspector vs Leaderboard --}}
            <div class="flex items-center p-1 rounded-xl bg-slate-100 dark:bg-slate-900 border border-slate-300 dark:border-slate-800">
                <button 
                    type="button" 
                    @click="activeView = 'inspector'"
                    class="flex-1 py-2 px-3 rounded-lg text-xs font-black transition flex items-center justify-center gap-1.5 cursor-pointer"
                    :class="activeView === 'inspector' ? 'bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 shadow-sm border border-slate-300 dark:border-slate-700' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Inspektor Wilayah</span>
                </button>
                <button 
                    type="button" 
                    @click="activeView = 'leaderboard'"
                    class="flex-1 py-2 px-3 rounded-lg text-xs font-black transition flex items-center justify-center gap-1.5 cursor-pointer"
                    :class="activeView === 'leaderboard' ? 'bg-white dark:bg-slate-800 text-cyan-600 dark:text-cyan-400 shadow-sm border border-slate-300 dark:border-slate-700' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    <span>Peringkat 21 Kecamatan</span>
                </button>
            </div>

            {{-- View 1: Inspector Card --}}
            <div x-show="activeView === 'inspector'" class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 shadow-sm flex flex-col justify-between" style="min-height: 615px;">
                <div>
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                        <span class="text-[11px] uppercase font-black tracking-wider text-slate-500 dark:text-slate-400">
                            Profil Wilayah Terpilih
                        </span>
                        <template x-if="selectedDistrict">
                            <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-md border"
                                :class="selectedDistrict.badgeClass"
                                x-text="selectedDistrict.status"></span>
                        </template>
                    </div>

                    {{-- Empty State (No district clicked yet) --}}
                    <template x-if="!selectedDistrict">
                        <div class="py-16 text-center space-y-3">
                            <div class="w-14 h-14 mx-auto rounded-2xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center border border-cyan-500/20">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h4 class="text-sm font-black text-slate-900 dark:text-white">Pilih Poligon Kecamatan</h4>
                            <p class="text-xs text-slate-600 dark:text-slate-400 max-w-xs mx-auto leading-relaxed">
                                Klik salah satu poligon kecamatan pada peta atau pilih dari dropdown di atas untuk melihat rincian opini masyarakat dan sentimen teritori.
                            </p>
                        </div>
                    </template>

                    {{-- Populated Inspector Detail --}}
                    <template x-if="selectedDistrict">
                        <div class="mt-4 space-y-5">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-slate-500 dark:text-slate-400">Kecamatan</span>
                                <h3 class="text-2xl font-black text-slate-900 dark:text-white" x-text="selectedDistrict.name"></h3>
                                <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mt-0.5">Wilayah Hukum Pemerintah Kabupaten Jombang</p>
                            </div>

                            {{-- Sentiment & Volume Metric Grid --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                                    <span class="text-[10px] uppercase font-bold text-slate-500 dark:text-slate-400 block">Net Sentiment Score</span>
                                    <div class="text-2xl font-black font-mono mt-0.5" 
                                        :class="selectedDistrict.nss >= 15 ? 'text-emerald-600 dark:text-emerald-400' : (selectedDistrict.nss >= 0 ? 'text-cyan-600 dark:text-cyan-400' : 'text-rose-600 dark:text-rose-400')"
                                        x-text="(selectedDistrict.nss > 0 ? '+' : '') + selectedDistrict.nss + '%'">
                                    </div>
                                </div>
                                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                                    <span class="text-[10px] uppercase font-bold text-slate-500 dark:text-slate-400 block">Volume Berita</span>
                                    <div class="text-2xl font-black font-mono text-slate-900 dark:text-white mt-0.5" 
                                        x-text="selectedDistrict.total">
                                    </div>
                                </div>
                            </div>

                            {{-- Sentiment Ratio Bar --}}
                            <div class="space-y-1.5">
                                <div class="flex items-center justify-between text-xs font-bold">
                                    <span class="text-slate-700 dark:text-slate-300">Komposisi Sentimen:</span>
                                    <div class="flex items-center gap-2 text-[11px] font-mono">
                                        <span class="text-emerald-600 dark:text-emerald-400 font-bold" x-text="'+' + selectedDistrict.pos"></span>
                                        <span class="text-slate-500 dark:text-slate-400 font-bold" x-text="'• ' + selectedDistrict.neu"></span>
                                        <span class="text-rose-600 dark:text-rose-400 font-bold" x-text="'-' + selectedDistrict.neg"></span>
                                    </div>
                                </div>
                                <div class="w-full bg-slate-200 dark:bg-slate-800 h-3 rounded-full overflow-hidden flex">
                                    <div :style="`width: ${selectedDistrict.total > 0 ? (selectedDistrict.pos / selectedDistrict.total) * 100 : 0}%`" class="bg-emerald-500 h-full transition-all"></div>
                                    <div :style="`width: ${selectedDistrict.total > 0 ? (selectedDistrict.neu / selectedDistrict.total) * 100 : 100}%`" class="bg-slate-400 h-full transition-all"></div>
                                    <div :style="`width: ${selectedDistrict.total > 0 ? (selectedDistrict.neg / selectedDistrict.total) * 100 : 0}%`" class="bg-rose-500 h-full transition-all"></div>
                                </div>
                            </div>

                            {{-- Operational Insight Box --}}
                            <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-950/80 text-xs space-y-2">
                                <div class="font-bold text-slate-800 dark:text-slate-200 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Rekomendasi Respons Wilayah:</span>
                                </div>
                                <p class="text-xs text-slate-700 dark:text-slate-300 leading-relaxed font-medium" 
                                    x-text="selectedDistrict.neg > 3 ? 'Perlu atensi respon cepat dari dinas terkait (PUPR/DLH/Satpol PP) atas aduan jalan rusak, genangan, atau pelayanan publik di kecamatan ini.' : 'Stabilitas opini teritorial kondusif. Tetap pertahankan publikasi capaian pembangunan dan keterbukaan informasi di wilayah ini.'">
                                </p>
                            </div>
                        </div>
                    </template>
                </div>

                {{-- Action Button: Trigger Drilldown Modal for Selected District --}}
                <template x-if="selectedDistrict">
                    <button 
                        type="button"
                        @click="openDistrictArticles(selectedDistrict.name, selectedDistrict.total)"
                        class="w-full mt-4 py-3 px-4 rounded-xl text-xs font-black text-white bg-gradient-to-r from-cyan-600 to-teal-600 hover:from-cyan-500 hover:to-teal-500 shadow-md shadow-cyan-500/25 transition-all flex items-center justify-center gap-2 cursor-pointer hover:scale-[1.02]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                        </svg>
                        <span>Telusuri Arsip Berita Wilayah (<span x-text="selectedDistrict.total"></span>)</span>
                    </button>
                </template>
            </div>

            {{-- View 2: District Leaderboard (Ranking 21 Kecamatan) --}}
            <div x-show="activeView === 'leaderboard'" class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 shadow-sm flex flex-col justify-between" style="min-height: 615px;">
                <div>
                    {{-- Sub-sort filters --}}
                    <div class="flex items-center justify-between pb-3 border-b border-slate-200 dark:border-slate-800">
                        <span class="text-[11px] uppercase font-black tracking-wider text-slate-500 dark:text-slate-400">
                            Peringkat 21 Kecamatan
                        </span>
                        <div class="flex items-center gap-1 text-[10px] font-bold">
                            <button type="button" @click="rankingSort = 'mentions'" 
                                class="px-2.5 py-1 rounded cursor-pointer transition font-bold"
                                :class="rankingSort === 'mentions' ? 'bg-cyan-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                                Terbanyak
                            </button>
                            <button type="button" @click="rankingSort = 'negative'" 
                                class="px-2.5 py-1 rounded cursor-pointer transition font-bold"
                                :class="rankingSort === 'negative' ? 'bg-rose-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                                Rawan
                            </button>
                            <button type="button" @click="rankingSort = 'positive'" 
                                class="px-2.5 py-1 rounded cursor-pointer transition font-bold"
                                :class="rankingSort === 'positive' ? 'bg-emerald-600 text-white' : 'text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white'">
                                Kondusif
                            </button>
                        </div>
                    </div>

                    {{-- 21 Districts Scrollable List --}}
                    <div class="mt-3 space-y-1.5 overflow-y-auto max-h-[510px] pr-1">
                        @foreach ($leaderboardByMentions as $idx => $d)
                            <div 
                                @click="focusDistrictByName('{{ $d['name'] }}')"
                                class="p-3 rounded-xl border border-slate-200 dark:border-slate-800 hover:border-cyan-500 bg-slate-50 dark:bg-slate-800/40 hover:bg-cyan-500/5 dark:hover:bg-cyan-500/10 transition-all flex items-center justify-between gap-3 cursor-pointer group shadow-2xs">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <span class="w-5 h-5 rounded-md text-[10px] font-black flex items-center justify-center shrink-0 {{ $idx < 3 ? 'bg-cyan-600 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }}">
                                        {{ $idx + 1 }}
                                    </span>
                                    <div class="truncate">
                                        <div class="text-xs font-extrabold text-slate-900 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors truncate">
                                            {{ $d['name'] }}
                                        </div>
                                        <div class="flex items-center gap-1.5 text-[10px] font-mono text-slate-500 dark:text-slate-400">
                                            <span>{{ $d['total'] }} berita</span>
                                            <span>•</span>
                                            <span class="{{ $d['nss'] >= 15 ? 'text-emerald-600 dark:text-emerald-400' : ($d['nss'] >= 0 ? 'text-cyan-600 dark:text-cyan-400' : 'text-rose-600 dark:text-rose-400') }} font-bold">
                                                NSS: {{ $d['nss'] > 0 ? '+' : '' }}{{ $d['nss'] }}%
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="shrink-0 flex items-center gap-1">
                                    <span class="text-[9px] font-bold px-2 py-0.5 rounded-md border {{ $d['badgeClass'] }}">
                                        {{ $d['status'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Livewire Global Article Drilldown Modal --}}
    @livewire('article-drilldown-modal')
</x-filament-panels::page>

{{-- Inline Leaflet Workspace Alpine Component & Redundant Script Loader --}}
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('jombangGisWorkspace', (config) => ({
        districtsData: config.districtsData || {},
        geoJsonData: config.geoJsonData || null,
        startDate: config.startDate || '',
        endDate: config.endDate || '',
        activeView: 'inspector',
        rankingSort: 'mentions',
        selectedDistrictName: '',
        selectedDistrict: null,
        map: null,
        geoJsonLayer: null,
        layerMap: {},

        initWorkspace() {
            this.ensureLeafletLoaded(() => {
                this.mountLeaflet();
            });
        },

        ensureLeafletLoaded(callback) {
            if (window.L) {
                callback();
                return;
            }

            // Fallback dynamically inject Leaflet if not present
            const script = document.createElement('script');
            script.src = '/vendor/leaflet/leaflet.js';
            script.onload = () => {
                callback();
            };
            script.onerror = () => {
                // Secondary CDN fallback
                const cdn = document.createElement('script');
                cdn.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                cdn.onload = () => callback();
                document.head.appendChild(cdn);
            };
            document.head.appendChild(script);
        },

        mountLeaflet() {
            const container = document.getElementById('jombang-standalone-gis-map');
            if (!container || !window.L) return;

            if (this.map) {
                this.map.remove();
                this.map = null;
            }

            // Center of Kabupaten Jombang
            const jombangCenter = [-7.546, 112.233];

            this.map = L.map('jombang-standalone-gis-map', {
                center: jombangCenter,
                zoom: 11,
                zoomControl: true,
                attributionControl: false
            });

            // Base Tile Layers
            const darkMatter = L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                maxZoom: 18,
                subdomains: 'abcd'
            });

            const positronLight = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                maxZoom: 18,
                subdomains: 'abcd'
            });

            const osmStandard = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 18
            });

            const esriSatellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 18
            });

            // Choose initial base tile based on active theme
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                darkMatter.addTo(this.map);
            } else {
                positronLight.addTo(this.map);
            }

            // Add layer controls in top right
            const baseMaps = {
                "Dark Matter": darkMatter,
                "Mode Terang": positronLight,
                "OpenStreetMap": osmStandard,
                "Citra Satelit": esriSatellite
            };
            L.control.layers(baseMaps, null, { position: 'topright' }).addTo(this.map);

            // Render polygons directly from GeoJSON
            if (this.geoJsonData) {
                this.renderPolygons(this.geoJsonData);
            } else {
                // Fallback fetch if not passed directly
                fetch('/geojson/jombang_kecamatan.geojson')
                    .then(r => r.json())
                    .then(data => this.renderPolygons(data))
                    .catch(e => console.error('GeoJSON fetch error:', e));
            }

            // Multi-stage invalidateSize to ensure container is fully dimensioned
            setTimeout(() => { if (this.map) this.map.invalidateSize(); }, 100);
            setTimeout(() => { if (this.map) this.map.invalidateSize(); }, 300);
            setTimeout(() => { if (this.map) this.map.invalidateSize(); }, 800);

            window.addEventListener('resize', () => {
                if (this.map) this.map.invalidateSize();
            });
        },

        renderPolygons(geoJson) {
            const self = this;

            this.geoJsonLayer = L.geoJSON(geoJson, {
                style: (feature) => {
                    const name = feature.properties.district_name;
                    const stat = self.districtsData[name] || { color: '#06b6d4' };

                    return {
                        fillColor: stat.color,
                        weight: 2,
                        opacity: 0.9,
                        color: '#0ea5e9', // Sky blue border
                        dashArray: '3',
                        fillOpacity: 0.55
                    };
                },
                onEachFeature: (feature, layer) => {
                    const name = feature.properties.district_name;
                    const stat = self.districtsData[name] || {
                        name: name,
                        total: 0,
                        pos: 0,
                        neg: 0,
                        neu: 0,
                        nss: 0,
                        status: 'Kondusif',
                        color: '#06b6d4',
                        badgeClass: 'bg-cyan-500/15 text-cyan-400 border-cyan-500/30'
                    };

                    self.layerMap[name] = layer;

                    // Tooltip
                    layer.bindTooltip(`
                        <div class="text-center font-sans p-1">
                            <strong class="text-xs font-black text-white">${name}</strong><br/>
                            <span class="text-[11px] text-cyan-300 font-mono font-bold">${stat.total} Berita • NSS: ${stat.nss > 0 ? '+' : ''}${stat.nss}%</span>
                        </div>
                    `, { sticky: true, className: 'cyber-leaflet-tooltip' });

                    // Popup
                    const popupHtml = `
                        <div class="p-3 font-sans text-slate-100 min-w-[210px]">
                            <div class="flex items-center justify-between pb-1.5 border-b border-slate-700">
                                <span class="font-black text-sm text-cyan-400">Kec. ${name}</span>
                                <span class="text-[9px] font-bold px-2 py-0.5 rounded ${stat.badgeClass}">${stat.status}</span>
                            </div>
                            <div class="mt-2 text-xs space-y-1 font-mono">
                                <div class="flex justify-between"><span>Total Berita:</span><strong>${stat.total}</strong></div>
                                <div class="flex justify-between text-emerald-400"><span>Positif:</span><strong>+${stat.pos}</strong></div>
                                <div class="flex justify-between text-rose-400"><span>Negatif:</span><strong>-${stat.neg}</strong></div>
                                <div class="flex justify-between text-slate-400"><span>Netral:</span><strong>${stat.neu}</strong></div>
                            </div>
                            <button 
                                onclick="window.dispatchEvent(new CustomEvent('open-article-drilldown', { detail: { title: 'Isu Wilayah: Kecamatan ${name}', subtitle: 'Total: ${stat.total} Berita (👍 ${stat.pos} | 👎 ${stat.neg})', districtName: '${name}', startDate: '${self.startDate}', endDate: '${self.endDate}' } }))"
                                class="w-full mt-3 py-1.5 px-3 rounded-lg text-xs font-bold bg-cyan-600 hover:bg-cyan-500 text-white transition text-center cursor-pointer">
                                Buka Berita Kecamatan 🔍
                            </button>
                        </div>
                    `;
                    layer.bindPopup(popupHtml, { className: 'cyber-leaflet-popup' });

                    // Hover effects
                    layer.on({
                        mouseover: (e) => {
                            const l = e.target;
                            l.setStyle({
                                weight: 3.5,
                                color: '#ffffff',
                                dashArray: '',
                                fillOpacity: 0.8
                            });
                            if (!L.Browser.ie && !L.Browser.opera && !L.Browser.edge) {
                                l.bringToFront();
                            }
                        },
                        mouseout: (e) => {
                            self.geoJsonLayer.resetStyle(e.target);
                        },
                        click: (e) => {
                            self.selectDistrict(name);
                            if (self.map) {
                                self.map.fitBounds(e.target.getBounds(), { padding: [40, 40], maxZoom: 13 });
                            }
                        }
                    });
                }
            }).addTo(this.map);

            if (this.geoJsonLayer && this.map) {
                this.map.fitBounds(this.geoJsonLayer.getBounds(), { padding: [20, 20] });
            }
        },

        selectDistrict(name) {
            this.selectedDistrictName = name;
            this.selectedDistrict = this.districtsData[name] || {
                name: name,
                total: 0,
                pos: 0,
                neg: 0,
                neu: 0,
                nss: 0,
                status: 'Kondusif',
                badgeClass: 'bg-cyan-500/15 text-cyan-400 border-cyan-500/30'
            };
            this.activeView = 'inspector';
        },

        focusDistrictByName(name) {
            if (!name) return;
            this.selectDistrict(name);
            const layer = this.layerMap[name];
            if (layer && this.map) {
                this.map.fitBounds(layer.getBounds(), { padding: [40, 40], maxZoom: 13 });
                layer.openPopup();
            }
        },

        resetMapBounds() {
            if (this.geoJsonLayer && this.map) {
                this.map.fitBounds(this.geoJsonLayer.getBounds(), { padding: [20, 20] });
            }
        },

        openDistrictArticles(name, total) {
            window.dispatchEvent(new CustomEvent('open-article-drilldown', {
                detail: {
                    title: `Isu Wilayah: Kecamatan ${name}`,
                    subtitle: `Total: ${total} Berita Terkait di Wilayah Kab. Jombang`,
                    districtName: name,
                    startDate: this.startDate,
                    endDate: this.endDate
                }
            }));
        }
    }));
});
</script>
