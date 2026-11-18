<x-filament-widgets::widget>
    <x-filament::section class="h-full flex flex-col justify-between">
        <div>
            {{-- Header --}}
            <div class="flex items-center justify-between pb-3.5 border-b border-gray-200 dark:border-slate-800">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[9px] font-black tracking-wider uppercase bg-cyan-500/15 text-cyan-500 dark:text-cyan-400 border border-cyan-500/30">
                            <span class="relative flex h-1.5 w-1.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-cyan-500"></span>
                            </span>
                            GIS RADAR WILAYAH
                        </span>
                        <h2 class="text-sm sm:text-base font-extrabold text-gray-900 dark:text-white tracking-tight">
                            Pemetaan Isu Geospasial 21 Kecamatan
                        </h2>
                    </div>
                    <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-1">
                        Distribusi aduan warga dan persebaran topik berita per wilayah kecamatan di Kab. Jombang.
                    </p>
                </div>

                <a href="/admin/peta-geospasial" 
                    class="hidden sm:inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold text-cyan-600 dark:text-cyan-400 hover:text-cyan-700 dark:hover:text-cyan-300 hover:bg-cyan-50 dark:hover:bg-cyan-950/40 transition">
                    <span>Layar Penuh</span>
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                </a>
            </div>

            {{-- 3 Quick Metrics Pills --}}
            <div class="grid grid-cols-3 gap-2.5 my-3.5">
                <div class="p-2.5 rounded-xl bg-gray-50 dark:bg-slate-800/60 border border-gray-100 dark:border-slate-800 text-center">
                    <span class="text-[9px] uppercase font-bold text-gray-400 dark:text-slate-500">Cakupan Wilayah</span>
                    <div class="text-base font-black font-mono text-cyan-600 dark:text-cyan-400">21 <span class="text-[10px] font-normal text-gray-500">Kecamatan</span></div>
                </div>
                <div class="p-2.5 rounded-xl bg-gray-50 dark:bg-slate-800/60 border border-gray-100 dark:border-slate-800 text-center">
                    <span class="text-[9px] uppercase font-bold text-gray-400 dark:text-slate-500">Kondusif</span>
                    <div class="text-base font-black font-mono text-emerald-600 dark:text-emerald-400">{{ $kondusifCount }} <span class="text-[10px] font-normal text-gray-500">Wilayah</span></div>
                </div>
                <div class="p-2.5 rounded-xl bg-gray-50 dark:bg-slate-800/60 border border-gray-100 dark:border-slate-800 text-center">
                    <span class="text-[9px] uppercase font-bold text-gray-400 dark:text-slate-500">Perlu Atensi</span>
                    <div class="text-base font-black font-mono text-rose-600 dark:text-rose-400">{{ $rawanCount }} <span class="text-[10px] font-normal text-gray-500">Wilayah</span></div>
                </div>
            </div>

            {{-- Top 4 Active Districts Mini Cards --}}
            <div class="space-y-2 mt-2">
                <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 dark:text-slate-500">Kecamatan Paling Aktif Dinamika Opini:</span>
                @foreach ($topDistricts as $d)
                    <div class="p-2.5 rounded-xl border border-gray-100 dark:border-slate-800/80 bg-gray-50/50 dark:bg-slate-900/60 hover:bg-cyan-50/50 dark:hover:bg-cyan-950/20 transition-all flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ $d['name'] }}</span>
                                <span class="text-[9px] font-black px-1.5 py-0.2 rounded border {{ $d['badgeClass'] }}">
                                    {{ $d['status'] }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-[10px] font-mono text-gray-500 dark:text-slate-400 mt-0.5">
                                <span>{{ $d['total'] }} berita</span>
                                <span>•</span>
                                <span class="font-bold {{ $d['nss'] >= 15 ? 'text-emerald-500' : ($d['nss'] >= 0 ? 'text-cyan-500' : 'text-rose-500') }}">
                                    NSS: {{ $d['nss'] > 0 ? '+' : '' }}{{ $d['nss'] }}%
                                </span>
                            </div>
                        </div>

                        {{-- Micro ratio bar --}}
                        <div class="w-24 shrink-0 space-y-1">
                            <div class="w-full bg-gray-200 dark:bg-slate-800 h-2 rounded-full overflow-hidden flex">
                                <div style="width: {{ $d['total'] > 0 ? ($d['pos'] / $d['total']) * 100 : 0 }}%" class="bg-emerald-500 h-full"></div>
                                <div style="width: {{ $d['total'] > 0 ? ($d['neu'] / $d['total']) * 100 : 100 }}%" class="bg-slate-400 h-full"></div>
                                <div style="width: {{ $d['total'] > 0 ? ($d['neg'] / $d['total']) * 100 : 0 }}%" class="bg-rose-500 h-full"></div>
                            </div>
                            <div class="flex justify-between text-[8px] font-mono font-bold text-gray-400">
                                <span class="text-emerald-500">+{{ $d['pos'] }}</span>
                                <span class="text-rose-500">-{{ $d['neg'] }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Big CTA Button to Dedicated Map Page --}}
        <div class="pt-4 mt-2 border-t border-gray-100 dark:border-slate-800">
            <a href="/admin/peta-geospasial" 
                class="w-full py-2.5 px-4 rounded-xl text-xs font-extrabold text-white bg-gradient-to-r from-cyan-600 via-teal-600 to-emerald-600 hover:from-cyan-500 hover:to-emerald-500 shadow-md shadow-cyan-500/20 transition-all flex items-center justify-center gap-2 cursor-pointer hover:scale-[1.02]">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                </svg>
                <span>Buka Peta Satelit & Poligon Geospasial Lengkap ➜</span>
            </a>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>

