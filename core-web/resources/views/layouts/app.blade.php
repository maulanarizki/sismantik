<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Eksekutif') - SISMANTIK Kab. Jombang</title>

    <!-- Google Fonts: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- Leaflet CSS & JS (Local Assets) -->
    <link rel="stylesheet" href="/vendor/leaflet/leaflet.css" />
    <script src="/vendor/leaflet/leaflet.js"></script>

    <!-- Chart.js for Smooth Data Visualizations -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Tailwind CSS (CDN with Custom Config) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            800: '#065f46',
                            900: '#064e3b',
                        },
                        navy: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#070b13',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'system-ui', '-apple-system', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js for Ultra-Smooth Reactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Plus Jakarta Sans', system-ui, sans-serif; letter-spacing: -0.01em; }
        .font-mono { font-family: 'JetBrains Mono', monospace !important; }
        
        /* Custom High-Tech Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: rgba(0, 0, 0, 0.05); }
        ::-webkit-scrollbar-thumb { background: rgba(148, 163, 184, 0.4); border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #10b981; }

        /* Leaflet popup customization */
        .leaflet-popup-content-wrapper {
            border-radius: 1rem !important;
            box-shadow: 0 15px 35px -5px rgba(0, 0, 0, 0.5) !important;
            padding: 0 !important;
            overflow: hidden !important;
        }
        .leaflet-popup-content { margin: 0 !important; }
    </style>

    <!-- Theme Initialization Script -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body x-data="sismantikApp()" class="h-full bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 transition-colors duration-200">

    <div class="min-h-full flex flex-col">
        {{-- TOPBAR NAVIGATION --}}
        <header class="sticky top-0 z-40 bg-white/90 dark:bg-slate-900/90 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 shadow-2xs">
            <div class="px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
                {{-- Left: Mobile Hamburger & Brand Logo --}}
                <div class="flex items-center gap-3">
                    <button type="button" @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <a href="/dashboard" class="flex items-center gap-3 group select-none">
                        <div class="relative flex items-center justify-center w-9 h-9 rounded-xl bg-gradient-to-br from-emerald-500 via-teal-600 to-cyan-600 shadow-md shadow-emerald-500/25 ring-1 ring-white/20 shrink-0 group-hover:scale-105 transition-transform">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="absolute -top-0.5 -right-0.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                            </span>
                        </div>
                        <div class="flex flex-col text-left leading-tight">
                            <div class="flex items-center gap-1.5">
                                <span class="font-black text-base tracking-wider text-emerald-600 dark:text-emerald-400">
                                    SISMANTIK
                                </span>
                                <span class="px-1.5 py-0.2 rounded text-[9px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-500/40">
                                    PRO
                                </span>
                            </div>
                            <span class="text-[10px] font-extrabold uppercase tracking-widest text-slate-500 dark:text-slate-400">
                                KABUPATEN JOMBANG
                            </span>
                        </div>
                    </a>
                </div>

                {{-- Center: Live Operational Beacon & Real-time Clock --}}
                <div class="hidden md:flex items-center gap-4">
                    <div class="flex items-center gap-2 px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-500/30 shadow-2xs">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        <span>RADAR LIVE • KAB. JOMBANG</span>
                    </div>

                    <div class="text-xs font-mono font-bold text-slate-500 dark:text-slate-400" x-text="currentTime"></div>
                </div>

                {{-- Right: Quick Action Buttons & Theme Switcher --}}
                <div class="flex items-center gap-2.5">
                    {{-- Tarik Berita Action Button --}}
                    <button type="button" @click="triggerSync()" :disabled="isSyncing"
                        class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 shadow-md shadow-emerald-500/20 transition-all cursor-pointer disabled:opacity-50">
                        <svg class="w-3.5 h-3.5" :class="isSyncing ? 'animate-spin' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        <span x-text="isSyncing ? 'Menarik...' : 'Tarik Berita'"></span>
                    </button>

                    {{-- Replace Total Button --}}
                    <button type="button" @click="confirmReplaceModal = true"
                        class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1.5 rounded-xl text-xs font-bold text-rose-700 dark:text-rose-300 bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 border border-rose-200 dark:border-rose-800 transition">
                        <svg class="w-3.5 h-3.5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span>Replace Total</span>
                    </button>

                    {{-- Fast Theme Toggle --}}
                    <button type="button" @click="toggleTheme()" 
                        class="p-2 rounded-xl text-slate-700 dark:text-slate-200 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-700 transition"
                        title="Ganti Tema (Terang / Gelap)">
                        <template x-if="isDark">
                            <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd" />
                            </svg>
                        </template>
                        <template x-if="!isDark">
                            <svg class="w-4 h-4 text-indigo-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" />
                            </svg>
                        </template>
                    </button>
                </div>
            </div>
        </header>

        <div class="flex-1 flex overflow-hidden">
            {{-- SIDEBAR NAVIGATION --}}
            <aside 
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
                class="fixed inset-y-0 left-0 z-30 w-64 pt-16 lg:pt-0 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 transition-transform duration-300 ease-in-out lg:static lg:block shrink-0 shadow-lg lg:shadow-none">
                
                <div class="h-full overflow-y-auto px-4 py-5 space-y-6">
                    {{-- Main Navigation --}}
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 px-3 mb-2">
                            Pusat Komando
                        </div>
                        <nav class="space-y-1">
                            <a href="/dashboard" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-extrabold transition-all {{ request()->is('dashboard') || request()->is('/') ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 {{ request()->is('dashboard') || request()->is('/') ? 'text-emerald-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                    <span>Dashboard Eksekutif</span>
                                </div>
                            </a>

                            <a href="/peta" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-extrabold transition-all {{ request()->is('peta') ? 'bg-cyan-500/15 text-cyan-600 dark:text-cyan-400 border border-cyan-500/30' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 {{ request()->is('peta') ? 'text-cyan-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                                    </svg>
                                    <span>Peta Geospasial 21 Kecamatan</span>
                                </div>
                                <span class="text-[9px] font-black px-1.5 py-0.2 rounded bg-cyan-100 text-cyan-800 dark:bg-cyan-500/20 dark:text-cyan-300">
                                    GIS
                                </span>
                            </a>
                        </nav>
                    </div>

                    {{-- Data & Monitoring --}}
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 px-3 mb-2">
                            Pemantauan & Data Mentah
                        </div>
                        <nav class="space-y-1">
                            <a href="/berita" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-extrabold transition-all {{ request()->is('berita*') ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 {{ request()->is('berita*') ? 'text-emerald-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                    <span>Katalog Berita & Opini</span>
                                </div>
                                <span class="text-[10px] font-mono font-bold text-slate-500">
                                    {{ number_format(\App\Models\RawContent::count()) }}
                                </span>
                            </a>

                            <a href="/sinkronisasi" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-extrabold transition-all {{ request()->is('sinkronisasi*') ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 {{ request()->is('sinkronisasi*') ? 'text-emerald-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Riwayat Sinkronisasi</span>
                                </div>
                                <span class="text-[10px] font-mono font-bold text-slate-500">
                                    {{ \App\Models\SyncJob::count() }}
                                </span>
                            </a>
                        </nav>
                    </div>

                    {{-- Configuration --}}
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 px-3 mb-2">
                            Konfigurasi Isu & Sumber
                        </div>
                        <nav class="space-y-1">
                            <a href="/opd" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-extrabold transition-all {{ request()->is('opd*') ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 {{ request()->is('opd*') ? 'text-emerald-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span>Daftar OPD & Kata Kunci</span>
                                </div>
                                <span class="text-[10px] font-mono font-bold text-slate-500">
                                    {{ \App\Models\TopicOrAgency::count() }}
                                </span>
                            </a>

                            <a href="/sumber" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-extrabold transition-all {{ request()->is('sumber*') ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 {{ request()->is('sumber*') ? 'text-emerald-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                    </svg>
                                    <span>Portal & Sumber Data</span>
                                </div>
                                <span class="text-[10px] font-mono font-bold text-slate-500">
                                    {{ \App\Models\Source::count() }}
                                </span>
                            </a>
                        </nav>
                    </div>

                    {{-- Documentation --}}
                    <div>
                        <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 dark:text-slate-500 px-3 mb-2">
                            Dokumentasi & SOP
                        </div>
                        <nav class="space-y-1">
                            <a href="/panduan" class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-extrabold transition-all {{ request()->is('panduan*') ? 'bg-emerald-500/15 text-emerald-600 dark:text-emerald-400 border border-emerald-500/30' : 'text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60' }}">
                                <div class="flex items-center gap-2.5">
                                    <svg class="w-4 h-4 {{ request()->is('panduan*') ? 'text-emerald-500' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                    </svg>
                                    <span>Panduan & Prosedur Sistem</span>
                                </div>
                            </a>
                        </nav>
                    </div>
                </div>
            </aside>

            {{-- MAIN PAGE CONTENT CONTAINER --}}
            <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">
                @if(session('success'))
                    <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 text-xs font-bold flex items-center justify-between">
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if(session('error'))
                    <div class="p-4 rounded-xl bg-rose-50 dark:bg-rose-950/40 border border-rose-300 dark:border-rose-800 text-rose-800 dark:text-rose-200 text-xs font-bold flex items-center justify-between">
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- MODAL: Replace Data Total Confirmation --}}
    <div x-cloak x-show="confirmReplaceModal" class="fixed inset-0 z-50 overflow-y-auto bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="confirmReplaceModal = false" class="w-full max-w-md p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 shadow-2xl space-y-4">
            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-black text-slate-900 dark:text-white">Konfirmasi Replace Data Total</h3>
                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 leading-relaxed">
                    PERHATIAN: Tindakan ini akan <strong>mengosongkan seluruh histori data berita dan sentimen lama</strong>, lalu menarik ulang data segar 12 bulan terakhir (maksimal 1.000 data/bulan).
                </p>
            </div>
            <div class="flex items-center justify-end gap-2.5 pt-2">
                <button type="button" @click="confirmReplaceModal = false" class="px-4 py-2 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 border border-slate-300 dark:border-slate-700">
                    Batal
                </button>
                <form action="/action/replace-total" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 shadow-md transition">
                        Ya, Bersihkan & Tarik Ulang
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- GLOBAL ARTICLE DRILLDOWN MODAL --}}
    <div x-cloak x-show="drilldownModalOpen" class="fixed inset-0 z-50 overflow-y-auto bg-black/75 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="drilldownModalOpen = false" class="w-full max-w-4xl p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-800 shadow-2xl flex flex-col max-h-[88vh]">
            {{-- Modal Header --}}
            <div class="flex items-start justify-between pb-4 border-b border-slate-200 dark:border-slate-800">
                <div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white" x-text="drilldownTitle"></h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5" x-text="drilldownSubtitle"></p>
                </div>
                <button type="button" @click="drilldownModalOpen = false" class="p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Search within modal --}}
            <div class="py-3">
                <input type="text" x-model="drilldownSearch" placeholder="Cari dalam artikel terkait..." 
                    class="w-full py-2 px-3.5 text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-slate-900 dark:text-white focus:ring-2 focus:ring-emerald-500">
            </div>

            {{-- Articles List --}}
            <div class="flex-1 overflow-y-auto space-y-3 pr-1">
                <template x-if="isLoadingArticles">
                    <div class="py-12 text-center text-xs text-slate-400">
                        <div class="animate-spin w-6 h-6 border-2 border-emerald-500 border-t-transparent rounded-full mx-auto mb-2"></div>
                        Memuat data artikel berita...
                    </div>
                </template>

                <template x-if="!isLoadingArticles && filteredArticles().length === 0">
                    <div class="py-12 text-center text-xs text-slate-400">
                        Tidak ada artikel yang cocok dengan kriteria pencarian ini.
                    </div>
                </template>

                <template x-for="art in filteredArticles()" :key="art.id">
                    <div class="p-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/70 dark:bg-slate-800/40 hover:border-emerald-500/50 transition space-y-2">
                        <div class="flex items-start justify-between gap-3">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900 dark:text-white leading-snug">
                                <a :href="art.url" target="_blank" class="hover:text-emerald-600 dark:hover:text-emerald-400 transition" x-text="art.title"></a>
                            </h4>
                            <span class="shrink-0 text-[10px] font-black uppercase px-2 py-0.5 rounded"
                                :class="art.sentiment === 'positive' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : (art.sentiment === 'negative' ? 'bg-rose-100 text-rose-800 dark:bg-rose-500/20 dark:text-rose-300' : 'bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-300')"
                                x-text="art.sentiment">
                            </span>
                        </div>
                        <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed" x-text="art.clean_text"></p>
                        <div class="flex items-center justify-between text-[11px] font-mono text-slate-400 pt-1 border-t border-slate-200 dark:border-slate-700/60">
                            <span x-text="art.source_name + ' • ' + art.published_at"></span>
                            <a :href="art.url" target="_blank" class="text-emerald-600 dark:text-emerald-400 font-bold hover:underline flex items-center gap-1">
                                <span>Buka Berita Asli</span> ↗
                            </a>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    {{-- Main Application Script --}}
    <script>
        function sismantikApp() {
            return {
                sidebarOpen: false,
                isDark: document.documentElement.classList.contains('dark'),
                currentTime: '',
                isSyncing: false,
                confirmReplaceModal: false,
                drilldownModalOpen: false,
                drilldownTitle: '',
                drilldownSubtitle: '',
                drilldownSearch: '',
                isLoadingArticles: false,
                drilldownArticles: [],

                init() {
                    this.updateClock();
                    setInterval(() => this.updateClock(), 1000);

                    // Listen to global open-article-drilldown event
                    window.addEventListener('open-article-drilldown', (e) => {
                        const d = e.detail || {};
                        this.openDrilldown(d);
                    });
                },

                updateClock() {
                    const now = new Date();
                    this.currentTime = now.toLocaleDateString('id-ID', { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' }) + ' • ' + now.toLocaleTimeString('id-ID') + ' WIB';
                },

                toggleTheme() {
                    this.isDark = !this.isDark;
                    if (this.isDark) {
                        document.documentElement.classList.add('dark');
                        localStorage.setItem('theme', 'dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                        localStorage.setItem('theme', 'light');
                    }
                    window.dispatchEvent(new CustomEvent('theme-changed', { detail: this.isDark ? 'dark' : 'light' }));
                },

                triggerSync() {
                    this.isSyncing = true;
                    fetch('/action/sync', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.isSyncing = false;
                        alert(data.message || 'Sinkronisasi berhasil dijalankan!');
                        window.location.reload();
                    })
                    .catch(err => {
                        this.isSyncing = false;
                        alert('Gagal menjalankan sinkronisasi: ' + err.message);
                    });
                },

                openDrilldown(params) {
                    this.drilldownTitle = params.title || 'Daftar Berita Terkait';
                    this.drilldownSubtitle = params.subtitle || '';
                    this.drilldownSearch = '';
                    this.drilldownArticles = [];
                    this.isLoadingArticles = true;
                    this.drilldownModalOpen = true;

                    fetch('/api/drilldown-articles', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify(params)
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.isLoadingArticles = false;
                        this.drilldownArticles = data.articles || [];
                    })
                    .catch(err => {
                        this.isLoadingArticles = false;
                        console.error('Error fetching articles:', err);
                    });
                },

                filteredArticles() {
                    if (!this.drilldownSearch) return this.drilldownArticles;
                    const q = this.drilldownSearch.toLowerCase();
                    return this.drilldownArticles.filter(a => 
                        (a.title && a.title.toLowerCase().includes(q)) || 
                        (a.clean_text && a.clean_text.toLowerCase().includes(q))
                    );
                }
            }
        }
    </script>
</body>
</html>

