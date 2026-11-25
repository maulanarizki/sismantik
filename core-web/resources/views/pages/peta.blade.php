@extends('layouts.app')

@section('title', 'Pusat Komando Geospasial Sentimen 21 Kecamatan')

@section('content')
<div x-data="gisCommander()" x-init="initMap()" class="space-y-6">

    {{-- HEADER & STATS BAR --}}
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-cyan-100 text-cyan-800 dark:bg-cyan-500/20 dark:text-cyan-300 border border-cyan-300 dark:border-cyan-500/30">
                        GIS Radar 21 Poligon Resmi
                    </span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-500/30">
                        GADM 4.1 Kab. Jombang
                    </span>
                </div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                    Pusat Komando Geospasial & Pemetaan Sentimen 21 Kecamatan
                </h1>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                    Deteksi persebaran berita, hotspot keluhan warga, dan dinamika sentimen per teritori kecamatan di seluruh Kabupaten Jombang.
                </p>
            </div>

            {{-- 5 Stat Micro-Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 text-xs">
                <div class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase">Berita Teritori</span>
                    <div class="text-base font-black text-slate-900 dark:text-white font-mono">{{ number_format($totalTerritorialMentions) }}</div>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase">Kondusif & Stabil</span>
                    <div class="text-base font-black text-emerald-600 dark:text-emerald-400 font-mono">{{ $kondusifCount }} Wilayah</div>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase">Perlu Atensi</span>
                    <div class="text-base font-black text-amber-600 dark:text-amber-400 font-mono">{{ $atensiCount }} Wilayah</div>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700">
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase">Rawan Keluhan</span>
                    <div class="text-base font-black text-rose-600 dark:text-rose-400 font-mono">{{ $rawanCount }} Wilayah</div>
                </div>
                <div class="p-2.5 rounded-xl bg-slate-100 dark:bg-slate-800/80 border border-slate-200 dark:border-slate-700 col-span-2 sm:col-span-1">
                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase">Volume Tertinggi</span>
                    <div class="text-base font-black text-cyan-600 dark:text-cyan-400 truncate">{{ $topVolumeDistrict ? $topVolumeDistrict['name'] : '-' }}</div>
                </div>
            </div>
        </div>

        {{-- Filter & Control Bar --}}
        <form method="GET" action="/peta" class="pt-3 border-t border-slate-200 dark:border-slate-800 flex flex-wrap items-center justify-between gap-3 text-xs">
            {{-- Quick Presets --}}
            <div class="flex items-center gap-1.5">
                <span class="text-slate-500 font-bold uppercase text-[10px] tracking-wider mr-1">Preset:</span>
                <a href="/peta?startDate={{ now()->subMonth()->format('Y-m-d') }}&endDate={{ now()->format('Y-m-d') }}&scope={{ $scope }}" 
                    class="px-2.5 py-1 rounded-lg font-bold border transition {{ request('startDate') == now()->subMonth()->format('Y-m-d') ? 'bg-cyan-500 text-white border-cyan-500' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700 hover:bg-slate-200' }}">
                    1 Bulan
                </a>
                <a href="/peta?startDate={{ now()->subMonths(3)->format('Y-m-d') }}&endDate={{ now()->format('Y-m-d') }}&scope={{ $scope }}" 
                    class="px-2.5 py-1 rounded-lg font-bold border transition {{ request('startDate') == now()->subMonths(3)->format('Y-m-d') ? 'bg-cyan-500 text-white border-cyan-500' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700 hover:bg-slate-200' }}">
                    3 Bulan
                </a>
                <a href="/peta?startDate={{ now()->subMonths(6)->format('Y-m-d') }}&endDate={{ now()->format('Y-m-d') }}&scope={{ $scope }}" 
                    class="px-2.5 py-1 rounded-lg font-bold border transition {{ request('startDate') == now()->subMonths(6)->format('Y-m-d') ? 'bg-cyan-500 text-white border-cyan-500' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700 hover:bg-slate-200' }}">
                    6 Bulan
                </a>
                <a href="/peta?startDate={{ now()->subYear()->format('Y-m-d') }}&endDate={{ now()->format('Y-m-d') }}&scope={{ $scope }}" 
                    class="px-2.5 py-1 rounded-lg font-bold border transition {{ !request('startDate') || request('startDate') == now()->subYear()->format('Y-m-d') ? 'bg-cyan-500 text-white border-cyan-500' : 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border-slate-300 dark:border-slate-700 hover:bg-slate-200' }}">
                    1 Tahun
                </a>
            </div>

            {{-- Date Inputs & Scope --}}
            <div class="flex flex-wrap items-center gap-2">
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-500 font-bold">Dari:</span>
                    <input type="date" name="startDate" value="{{ $startDate }}" 
                        class="px-2.5 py-1 rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-1 focus:ring-cyan-500">
                </div>
                <div class="flex items-center gap-1.5">
                    <span class="text-slate-500 font-bold">Sampai:</span>
                    <input type="date" name="endDate" value="{{ $endDate }}" 
                        class="px-2.5 py-1 rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-1 focus:ring-cyan-500">
                </div>
                <div class="flex items-center gap-1.5">
                    <select name="scope" class="px-2.5 py-1 rounded-lg border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-medium focus:ring-1 focus:ring-cyan-500">
                        <option value="all" {{ $scope === 'all' ? 'selected' : '' }}>Semua Isu (Lokal & PSN)</option>
                        <option value="local_pemkab" {{ $scope === 'local_pemkab' ? 'selected' : '' }}>Khusus Lokal Pemkab Jombang</option>
                        <option value="national_strategic" {{ $scope === 'national_strategic' ? 'selected' : '' }}>Khusus Isu Strategis Nasional</option>
                    </select>
                </div>
                <button type="submit" class="px-3.5 py-1 rounded-lg font-bold text-white bg-slate-900 hover:bg-slate-800 dark:bg-cyan-600 dark:hover:bg-cyan-500 transition shadow-xs">
                    Terapkan
                </button>
            </div>
        </form>
    </div>

    {{-- INTERACTIVE MAP CARD --}}
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
        {{-- Map Toolbar --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs">
            <div class="flex items-center gap-2">
                <span class="font-bold text-slate-700 dark:text-slate-300">Fokus Wilayah:</span>
                <select x-model="selectedDistrictKey" @change="zoomToDistrict(selectedDistrictKey)" 
                    class="px-3 py-1.5 rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white font-semibold focus:ring-2 focus:ring-cyan-500">
                    <option value="">-- Pilih Kecamatan (21 Wilayah) --</option>
                    @foreach($canonicalDistricts as $dName)
                        @php $s = $districtStats[$dName] ?? null; @endphp
                        <option value="{{ $dName }}">
                            {{ $dName }} ({{ $s ? $s['total'] : 0 }} Berita • NSS: {{ $s ? ($s['nss'] > 0 ? '+' : '') . $s['nss'] : '0' }}%)
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                {{-- Basemap Switcher --}}
                <div class="flex items-center bg-slate-100 dark:bg-slate-800 p-0.5 rounded-xl border border-slate-200 dark:border-slate-700">
                    <button type="button" @click="setTileLayer('osm')" :class="currentTile === 'osm' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs font-bold' : 'text-slate-500 hover:text-slate-900'" class="px-2.5 py-1 rounded-lg transition text-[11px]">
                        OpenStreetMap
                    </button>
                    <button type="button" @click="setTileLayer('esri_canvas')" :class="currentTile === 'esri_canvas' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs font-bold' : 'text-slate-500 hover:text-slate-900'" class="px-2.5 py-1 rounded-lg transition text-[11px]">
                        Esri Canvas
                    </button>
                    <button type="button" @click="setTileLayer('satellite')" :class="currentTile === 'satellite' ? 'bg-white dark:bg-slate-700 text-slate-900 dark:text-white shadow-xs font-bold' : 'text-slate-500 hover:text-slate-900'" class="px-2.5 py-1 rounded-lg transition text-[11px]">
                        Satelit
                    </button>
                </div>

                <button type="button" @click="resetMapZoom()" 
                    class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 font-bold text-slate-700 dark:text-slate-200 transition flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Reset Posisi Peta</span>
                </button>
            </div>
        </div>

        {{-- MAP CONTAINER (Leaflet) --}}
        <div class="relative w-full h-[520px] rounded-xl overflow-hidden border border-slate-200 dark:border-slate-800">
            <div id="jombangGisMap" class="w-full h-full z-10"></div>

            {{-- Floating Legend Overlay --}}
            <div class="absolute bottom-4 right-4 z-20 p-3 rounded-xl bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border border-slate-300 dark:border-slate-700 shadow-xl space-y-1.5 text-xs">
                <div class="font-black text-slate-900 dark:text-white uppercase tracking-wider text-[10px] border-b border-slate-200 dark:border-slate-800 pb-1">
                    Legenda Sentimen Wilayah
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded bg-emerald-500 shadow-xs"></span>
                    <span class="text-slate-700 dark:text-slate-300 font-medium">Sangat Kondusif (NSS ≥ +15%)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded bg-cyan-500 shadow-xs"></span>
                    <span class="text-slate-700 dark:text-slate-300 font-medium">Kondusif & Stabil (0% s/d +14%)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded bg-amber-500 shadow-xs"></span>
                    <span class="text-slate-700 dark:text-slate-300 font-medium">Perlu Atensi (NSS < 0%)</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="w-3.5 h-3.5 rounded bg-rose-500 shadow-xs"></span>
                    <span class="text-slate-700 dark:text-slate-300 font-medium">Rawan Keluhan (Negatif ≥ 40%)</span>
                </div>
            </div>
        </div>
    </div>

    {{-- BOTTOM DUAL PANE: INSPECTOR HUD & 21 DISTRICT LEADERBOARD --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Pane Left: Inspektor Wilayah Terpilih --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    </svg>
                    <span>Inspektor Wilayah Terpilih</span>
                </h3>
                <span x-text="inspector.name ? 'Terpilih' : 'Standby'" class="px-2 py-0.5 rounded text-[10px] font-black uppercase" :class="inspector.name ? 'bg-cyan-100 text-cyan-800 dark:bg-cyan-500/20 dark:text-cyan-300' : 'bg-slate-100 text-slate-600'"></span>
            </div>

            <template x-if="!inspector.name">
                <div class="py-12 text-center text-xs text-slate-400 space-y-2">
                    <svg class="w-8 h-8 mx-auto text-slate-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <p class="font-bold text-slate-600 dark:text-slate-400">Klik salah satu poligon kecamatan pada peta di atas</p>
                    <p>atau pilih melalui dropdown untuk melihat rincian opini, statistik sentimen, dan artikel terkait.</p>
                </div>
            </template>

            <template x-if="inspector.name">
                <div class="space-y-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kecamatan</span>
                            <h4 class="text-xl font-black text-slate-900 dark:text-white" x-text="inspector.name"></h4>
                            <p class="text-xs text-slate-500 mt-0.5">Wilayah Hukum Pemerintah Kabupaten Jombang</p>
                        </div>
                        <span class="px-3 py-1 rounded-xl text-xs font-black uppercase" :style="`background-color: ${inspector.color}20; color: ${inspector.color}; border: 1px solid ${inspector.color}50`" x-text="inspector.status"></span>
                    </div>

                    <div class="grid grid-cols-3 gap-2.5">
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 text-center">
                            <span class="text-[10px] uppercase font-bold text-slate-400">Total Berita</span>
                            <div class="text-lg font-black text-slate-900 dark:text-white font-mono mt-0.5" x-text="inspector.total"></div>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 text-center">
                            <span class="text-[10px] uppercase font-bold text-slate-400">Net Sentimen</span>
                            <div class="text-lg font-black font-mono mt-0.5" :class="inspector.nss >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400'" x-text="(inspector.nss > 0 ? '+' : '') + inspector.nss + '%'"></div>
                        </div>
                        <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/60 text-center">
                            <span class="text-[10px] uppercase font-bold text-slate-400">Rincian Pos/Neg</span>
                            <div class="text-xs font-bold font-mono mt-1 text-slate-600 dark:text-slate-300">
                                <span class="text-emerald-600 font-black" x-text="inspector.pos"></span> / 
                                <span class="text-rose-600 font-black" x-text="inspector.neg"></span> / 
                                <span class="text-slate-400 font-black" x-text="inspector.neu"></span>
                            </div>
                        </div>
                    </div>

                    <div class="p-3.5 rounded-xl bg-slate-100 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 text-xs text-slate-700 dark:text-slate-300 leading-relaxed">
                        <span class="font-bold text-slate-900 dark:text-white">Rekomendasi Respons Teritori:</span>
                        <p class="mt-1" x-text="inspector.nss < 0 ? 'Tingkat sentimen negatif memerlukan respons cepat dari OPD terkait terutama persoalan layanan publik dan sarana infrastruktur jalan.' : 'Stabilitas opini publik teritorial kondusif. Pertahankan amplifikasi berita pembangunan dan kemudahan pelayanan warga.'"></p>
                    </div>

                    {{-- LIVE FEED DOKUMEN BERITA DI KECAMATAN TERPILIH --}}
                    <div class="space-y-2.5 pt-2 border-t border-slate-200 dark:border-slate-700/60">
                        <div class="flex items-center justify-between text-xs">
                            <h5 class="font-black text-slate-900 dark:text-white uppercase tracking-wider flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                                <span>Berita Terkini di Kec. <span x-text="inspector.name"></span></span>
                            </h5>
                            <span class="text-[10px] font-mono text-slate-400" x-text="isLoadingDistrictArticles ? 'Memuat data...' : (districtArticles.length + ' berita ditampilkan')"></span>
                        </div>

                        {{-- Loading State --}}
                        <div x-show="isLoadingDistrictArticles" class="py-6 text-center text-xs text-slate-400 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 animate-spin text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            <span>Mengambil data berita teritori...</span>
                        </div>

                        {{-- Articles List --}}
                        <div x-show="!isLoadingDistrictArticles && districtArticles.length > 0" class="space-y-2 max-h-[320px] overflow-y-auto pr-1">
                            <template x-for="art in districtArticles" :key="art.id">
                                <div class="p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 hover:border-cyan-500/50 hover:bg-slate-100 dark:hover:bg-slate-800/80 transition space-y-1.5 text-xs">
                                    <div class="flex items-start justify-between gap-2">
                                        <a :href="art.url" target="_blank" class="font-bold text-slate-900 dark:text-white hover:text-cyan-600 dark:hover:text-cyan-400 line-clamp-2 leading-snug" x-text="art.title"></a>
                                        <span class="px-2 py-0.5 rounded text-[9px] font-black uppercase shrink-0"
                                            :class="art.sentiment === 'positive' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : (art.sentiment === 'negative' ? 'bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-300' : 'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-300')"
                                            x-text="art.sentiment"></span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed" x-text="art.clean_text"></p>
                                    <div class="flex items-center justify-between text-[10px] text-slate-400 pt-1 border-t border-slate-200/60 dark:border-slate-700/60 font-mono">
                                        <span class="font-bold text-slate-600 dark:text-slate-300" x-text="art.source_name"></span>
                                        <span x-text="art.published_at"></span>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Empty State --}}
                        <div x-show="!isLoadingDistrictArticles && districtArticles.length === 0" class="py-6 text-center text-xs text-slate-400">
                            Belum ada artikel spesifik terpetakan di wilayah ini untuk filter periode yang dipilih.
                        </div>

                        {{-- Drilldown Full View Button --}}
                        <button type="button" 
                            @click="$dispatch('open-article-drilldown', {
                                title: 'Kecamatan ' + inspector.name,
                                subtitle: 'Kabupaten Jombang • Total: ' + inspector.total + ' Berita',
                                districtName: inspector.name,
                                startDate: '{{ $startDate }}',
                                endDate: '{{ $endDate }}',
                                scope: '{{ $scope }}'
                            })"
                            class="w-full py-2.5 px-4 rounded-xl font-black text-xs text-white bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 transition flex items-center justify-center gap-2 shadow-md shadow-cyan-500/20 cursor-pointer">
                            <span>Buka Semua Berita Lengkap (<span x-text="inspector.total"></span> Konten)</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        {{-- Pane Right: Peringkat 21 Kecamatan --}}
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-800 pb-3">
                <h3 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">
                    Peringkat 21 Kecamatan Se-Kabupaten Jombang
                </h3>
                <div class="flex items-center gap-1 text-[11px] font-bold">
                    <button type="button" @click="activeRankTab = 'mentions'" :class="activeRankTab === 'mentions' ? 'bg-cyan-500 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-2 py-0.5 rounded-lg transition">
                        Volume
                    </button>
                    <button type="button" @click="activeRankTab = 'negative'" :class="activeRankTab === 'negative' ? 'bg-rose-500 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-2 py-0.5 rounded-lg transition">
                        Keluhan
                    </button>
                    <button type="button" @click="activeRankTab = 'positive'" :class="activeRankTab === 'positive' ? 'bg-emerald-500 text-white' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400'" class="px-2 py-0.5 rounded-lg transition">
                        Positif
                    </button>
                </div>
            </div>

            <div class="space-y-2 max-h-[380px] overflow-y-auto pr-1">
                {{-- By Mentions --}}
                <template x-if="activeRankTab === 'mentions'">
                    <div class="space-y-2">
                        @foreach($leaderboardByMentions as $idx => $d)
                            <div @click="zoomToDistrict('{{ $d['name'] }}')" class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 hover:border-cyan-500/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition flex items-center justify-between text-xs cursor-pointer">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-5 h-5 rounded-md bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 font-mono font-bold text-[11px] flex items-center justify-center">
                                        {{ $idx + 1 }}
                                    </span>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $d['name'] }}</span>
                                    <span class="px-2 py-0.2 rounded text-[10px] font-black {{ $d['status'] === 'Sangat Kondusif' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : ($d['status'] === 'Kondusif' ? 'bg-cyan-100 text-cyan-800 dark:bg-cyan-500/20 dark:text-cyan-300' : 'bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-300') }}">
                                        {{ $d['status'] }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-3 font-mono">
                                    <span class="font-bold text-slate-500">{{ $d['total'] }} berita</span>
                                    <span class="font-black {{ $d['nss'] >= 0 ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                        {{ $d['nss'] > 0 ? '+' : '' }}{{ $d['nss'] }}%
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </template>

                {{-- By Negative --}}
                <template x-if="activeRankTab === 'negative'">
                    <div class="space-y-2">
                        @foreach($leaderboardByNegative as $idx => $d)
                            <div @click="zoomToDistrict('{{ $d['name'] }}')" class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 hover:border-rose-500/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition flex items-center justify-between text-xs cursor-pointer">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-5 h-5 rounded-md bg-rose-100 text-rose-800 dark:bg-rose-950 dark:text-rose-300 font-mono font-bold text-[11px] flex items-center justify-center">
                                        {{ $idx + 1 }}
                                    </span>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $d['name'] }}</span>
                                </div>
                                <div class="flex items-center gap-3 font-mono">
                                    <span class="font-bold text-rose-600 dark:text-rose-400">{{ $d['neg'] }} Keluhan</span>
                                    <span class="text-slate-400">Total: {{ $d['total'] }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </template>

                {{-- By Positive --}}
                <template x-if="activeRankTab === 'positive'">
                    <div class="space-y-2">
                        @foreach($leaderboardByPositive as $idx => $d)
                            <div @click="zoomToDistrict('{{ $d['name'] }}')" class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/40 hover:border-emerald-500/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition flex items-center justify-between text-xs cursor-pointer">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-5 h-5 rounded-md bg-emerald-100 text-emerald-800 dark:bg-emerald-950 dark:text-emerald-300 font-mono font-bold text-[11px] flex items-center justify-center">
                                        {{ $idx + 1 }}
                                    </span>
                                    <span class="font-bold text-slate-900 dark:text-white">{{ $d['name'] }}</span>
                                </div>
                                <div class="flex items-center gap-3 font-mono">
                                    <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $d['pos'] }} Positif</span>
                                    <span class="text-slate-400">NSS: +{{ $d['nss'] }}%</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </template>
            </div>
        </div>
    </div>

</div>

{{-- Leaflet GIS Map Logic --}}
<script>
    function gisCommander() {
        return {
            map: null,
            geoJsonLayer: null,
            baseTileLayers: {},
            currentTile: 'osm',
            selectedDistrictKey: '',
            activeRankTab: 'mentions',
            districtStats: @json($districtStats),
            geoJsonRaw: @json($geoJsonData),
            districtArticles: [],
            isLoadingDistrictArticles: false,
            inspector: {
                name: '',
                total: 0,
                pos: 0,
                neg: 0,
                neu: 0,
                nss: 0,
                status: '',
                color: '#06b6d4'
            },

            initMap() {
                // Initialize Leaflet Map centered on Jombang coordinates
                this.map = L.map('jombangGisMap', {
                    center: [-7.550, 112.235],
                    zoom: 11,
                    minZoom: 10,
                    maxZoom: 16,
                    zoomControl: true
                });

                // 1. OpenStreetMap Layer (Standard, 100% Free, NO Watermarks!)
                this.baseTileLayers['osm'] = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                });

                // 2. Esri Gray Canvas (Clean Executive Map, NO Watermarks!)
                this.baseTileLayers['esri_canvas'] = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/Canvas/World_Light_Gray_Base/MapServer/tile/{z}/{y}/{x}', {
                    maxZoom: 16,
                    attribution: 'Tiles &copy; Esri &mdash; Esri, DeLorme, NAVTEQ'
                });

                // 3. Esri World Imagery (Satellite, NO Watermarks!)
                this.baseTileLayers['satellite'] = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    maxZoom: 18,
                    attribution: 'Tiles &copy; Esri'
                });

                // Add default layer
                this.baseTileLayers['osm'].addTo(this.map);

                // Load GeoJSON Polygons & Auto-select Top District
                if (this.geoJsonRaw) {
                    this.renderGeoJson();
                    const topDistrict = '{{ $topVolumeDistrict ? $topVolumeDistrict['name'] : 'Ploso' }}';
                    setTimeout(() => {
                        this.zoomToDistrict(topDistrict);
                    }, 350);
                }
            },

            setTileLayer(type) {
                this.currentTile = type;
                Object.values(this.baseTileLayers).forEach(layer => this.map.removeLayer(layer));
                if (this.baseTileLayers[type]) {
                    this.baseTileLayers[type].addTo(this.map);
                }
            },

            renderGeoJson() {
                const self = this;

                this.geoJsonLayer = L.geoJSON(this.geoJsonRaw, {
                    style: function (feature) {
                        const p = feature.properties || {};
                        const name = p.district_name || p.raw_name || p.NAME_3 || p.name || '';
                        const stat = self.getStatByName(name);
                        const fillColor = stat ? stat.color : '#06b6d4';

                        return {
                            fillColor: fillColor,
                            weight: 2,
                            opacity: 1,
                            color: '#ffffff',
                            fillOpacity: 0.75
                        };
                    },
                    onEachFeature: function (feature, layer) {
                        const p = feature.properties || {};
                        const name = p.district_name || p.raw_name || p.NAME_3 || p.name || '';
                        const stat = self.getStatByName(name);
                        const total = stat ? stat.total : 0;
                        const nss = stat ? (stat.nss > 0 ? '+' : '') + stat.nss : '0';
                        const status = stat ? stat.status : 'Kondusif';
                        const color = stat ? stat.color : '#06b6d4';

                        // Tooltip on Hover
                        layer.bindTooltip(`
                            <div class="px-2.5 py-1.5 text-center font-bold text-xs space-y-0.5">
                                <div class="text-white font-black text-sm">Kec. ${name}</div>
                                <div class="text-[10px] font-mono text-slate-300">${total} Berita • NSS: ${nss}%</div>
                                <div class="text-[9px] font-black uppercase tracking-wider" style="color: ${color}">${status}</div>
                            </div>
                        `, {
                            permanent: false,
                            sticky: true,
                            className: 'bg-slate-900/95 text-white rounded-xl shadow-xl border border-slate-700/80'
                        });

                        // Mouse Events
                        layer.on({
                            mouseover: function (e) {
                                const l = e.target;
                                l.setStyle({
                                    weight: 3.5,
                                    color: '#ffffff',
                                    fillOpacity: 0.95
                                });
                                l.bringToFront();
                            },
                            mouseout: function (e) {
                                self.geoJsonLayer.resetStyle(e.target);
                            },
                            click: function (e) {
                                self.selectDistrict(name, layer);
                            }
                        });
                    }
                }).addTo(this.map);

                this.map.fitBounds(this.geoJsonLayer.getBounds(), { padding: [20, 20] });
            },

            getStatByName(name) {
                if (!name) return null;
                const clean = name.toLowerCase().replace(/^(kecamatan|kec\.)\s+/i, '').trim();
                for (const key in this.districtStats) {
                    const cleanKey = key.toLowerCase().replace(/^(kecamatan|kec\.)\s+/i, '').trim();
                    if (clean === cleanKey || clean.includes(cleanKey) || cleanKey.includes(clean)) {
                        return this.districtStats[key];
                    }
                }
                return null;
            },

            selectDistrict(name, layer) {
                const stat = this.getStatByName(name);
                if (stat) {
                    this.inspector = {
                        name: stat.name,
                        total: stat.total,
                        pos: stat.pos,
                        neg: stat.neg,
                        neu: stat.neu,
                        nss: stat.nss,
                        status: stat.status,
                        color: stat.color
                    };
                    this.selectedDistrictKey = stat.name;
                    this.fetchDistrictArticles(stat.name);
                }

                if (layer) {
                    this.map.fitBounds(layer.getBounds(), { padding: [50, 50], maxZoom: 13 });
                }
            },

            fetchDistrictArticles(districtName) {
                this.isLoadingDistrictArticles = true;
                this.districtArticles = [];

                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = tokenMeta ? tokenMeta.getAttribute('content') : '';

                fetch('/api/drilldown-articles', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        districtName: districtName,
                        startDate: '{{ $startDate }}',
                        endDate: '{{ $endDate }}',
                        scope: '{{ $scope }}'
                    })
                })
                .then(res => res.json())
                .then(data => {
                    this.districtArticles = (data && data.articles) ? data.articles.slice(0, 8) : [];
                    this.isLoadingDistrictArticles = false;
                })
                .catch(err => {
                    console.error('Error fetching district articles:', err);
                    this.isLoadingDistrictArticles = false;
                });
            },

            zoomToDistrict(name) {
                if (!name) return;
                const self = this;
                if (!this.geoJsonLayer) return;

                const target = name.toLowerCase().replace(/^(kecamatan|kec\.)\s+/i, '').trim();

                this.geoJsonLayer.eachLayer(function (layer) {
                    const p = layer.feature ? layer.feature.properties || {} : {};
                    const lName = (p.district_name || p.raw_name || p.NAME_3 || p.name || '').toLowerCase().replace(/^(kecamatan|kec\.)\s+/i, '').trim();
                    if (lName === target || lName.includes(target) || target.includes(lName)) {
                        self.selectDistrict(p.district_name || name, layer);
                    }
                });
            },

            resetMapZoom() {
                if (this.geoJsonLayer) {
                    this.map.fitBounds(this.geoJsonLayer.getBounds(), { padding: [20, 20] });
                } else {
                    this.map.setView([-7.550, 112.235], 11);
                }
            }
        };
    }
</script>
@endsection

