<x-filament-widgets::widget>
    <x-filament::section>
        {{-- Header Section --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-gray-200 dark:border-slate-800 gap-3">
            <div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black tracking-wider uppercase bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                        PILAR STRATEGIS NASIONAL (PSN)
                    </span>
                    <h2 class="text-base sm:text-lg font-black text-gray-900 dark:text-white tracking-tight">
                        Monitoring Program Prioritas Pemerintah Pusat di Wilayah Kab. Jombang
                    </h2>
                </div>
                <p class="text-xs text-gray-500 dark:text-slate-400 mt-1">
                    Pelacakan independen opini publik dan dinamika media terhadap kebijakan strategis Presiden/Kementerian (MBG, Koperasi Desa, Penegakan Hukum, Swasembada Pangan) di wilayah Kabupaten Jombang.
                </p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <span class="px-3 py-1.5 text-xs font-bold rounded-xl bg-slate-100 dark:bg-slate-800/80 text-gray-700 dark:text-slate-300 border border-gray-200 dark:border-slate-700/60 shadow-xs">
                    Total Isu PSN: <strong class="text-indigo-600 dark:text-indigo-400 font-mono">{{ number_format($totalPsnCount) }} Konten</strong>
                </span>
            </div>
        </div>

        {{-- Grid Cards 4 Program Strategis --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 pt-4">
            @foreach ($programs as $prog)
                @php
                    $nssVal = $prog['nss'] ?? 0;
                    $isPos = $nssVal >= 0;
                    $nssColor = $nssVal >= 20 ? 'text-emerald-500 dark:text-emerald-400' : ($nssVal >= 0 ? 'text-cyan-500 dark:text-cyan-400' : 'text-rose-500 dark:text-rose-400');
                    $statusBg = $nssVal >= 20 ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/30' : ($nssVal >= 0 ? 'bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border-cyan-500/30' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/30');
                    $mentions = $prog['total_mentions'] ?? 0;
                    $posCount = $prog['pos_count'] ?? 0;
                    $negCount = $prog['neg_count'] ?? 0;
                    $neuCount = $prog['neu_count'] ?? 0;
                    $primaryKw = $prog['primary_keyword'] ?? ((isset($prog['keywords']) && is_array($prog['keywords']) && !empty($prog['keywords'])) ? $prog['keywords'][0] : ($prog['id'] ?? 'mbg'));

                    $accentBorder = match($prog['id'] ?? '') {
                        'mbg' => 'border-t-4 border-t-emerald-500 hover:shadow-emerald-500/10',
                        'kopdes' => 'border-t-4 border-t-cyan-500 hover:shadow-cyan-500/10',
                        'tipikor' => 'border-t-4 border-t-rose-500 hover:shadow-rose-500/10',
                        'swasembada' => 'border-t-4 border-t-amber-500 hover:shadow-amber-500/10',
                        default => 'border-t-4 border-t-indigo-500 hover:shadow-indigo-500/10',
                    };

                    $accentIconBg = match($prog['id'] ?? '') {
                        'mbg' => 'bg-emerald-500/20 text-emerald-400',
                        'kopdes' => 'bg-cyan-500/20 text-cyan-400',
                        'tipikor' => 'bg-rose-500/20 text-rose-400',
                        'swasembada' => 'bg-amber-500/20 text-amber-400',
                        default => 'bg-indigo-500/20 text-indigo-400',
                    };
                @endphp
                <div class="relative flex flex-col justify-between p-4 rounded-xl border border-gray-200 dark:border-slate-800 bg-white dark:bg-slate-900/90 shadow-sm hover:shadow-xl transition-all duration-300 {{ $accentBorder }}">
                    <div>
                        {{-- Top Meta --}}
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700/60">
                                {{ $prog['badge'] ?? 'PSN' }}
                            </span>
                            <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md border {{ $statusBg }}">
                                {{ $prog['status'] ?? 'Kondusif' }}
                            </span>
                        </div>

                        {{-- Program Name --}}
                        <h3 class="text-sm font-extrabold text-gray-900 dark:text-white leading-snug line-clamp-2">
                            {{ $prog['name'] ?? 'Program Strategis' }}
                        </h3>

                        {{-- Focal Point Summary --}}
                        <p class="text-[11px] text-gray-500 dark:text-slate-400 mt-2 line-clamp-3 leading-relaxed">
                            {{ $prog['focal_point'] ?? '' }}
                        </p>
                    </div>

                    {{-- Metrics Bottom --}}
                    <div class="mt-4 pt-3 border-t border-gray-100 dark:border-slate-800/80">
                        <div class="flex items-baseline justify-between">
                            <div>
                                <span class="text-[10px] uppercase font-bold text-gray-400 dark:text-slate-500">Net Sentiment</span>
                                <div class="text-xl font-black font-mono {{ $nssColor }}">
                                    {{ $isPos ? '+' : '' }}{{ $nssVal }}%
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-[10px] uppercase font-bold text-gray-400 dark:text-slate-500">Volume</span>
                                <div class="text-sm font-black font-mono text-gray-800 dark:text-slate-200">
                                    {{ number_format($mentions) }} <span class="text-[10px] font-normal text-gray-500">berita</span>
                                </div>
                            </div>
                        </div>

                        {{-- Distribution Micro Bar --}}
                        <div class="w-full bg-gray-200 dark:bg-slate-800 h-2 rounded-full overflow-hidden flex mt-2.5">
                            @php
                                $total = max(1, $mentions);
                                $posPct = ($posCount / $total) * 100;
                                $negPct = ($negCount / $total) * 100;
                                $neuPct = ($neuCount / $total) * 100;
                            @endphp
                            <div style="width: {{ $posPct }}%" class="bg-emerald-500 h-full transition-all duration-500" title="Positif: {{ $posCount }}"></div>
                            <div style="width: {{ $neuPct }}%" class="bg-slate-400 h-full transition-all duration-500" title="Netral: {{ $neuCount }}"></div>
                            <div style="width: {{ $negPct }}%" class="bg-rose-500 h-full transition-all duration-500" title="Negatif: {{ $negCount }}"></div>
                        </div>

                        <div class="flex items-center justify-between text-[10px] text-gray-400 mt-2">
                            <span>Emosi Dominan: <strong class="text-gray-700 dark:text-slate-300">{{ $prog['dominant_emotion'] ?? 'Netral' }}</strong></span>
                            <div class="flex items-center gap-1.5 font-mono">
                                <span class="text-emerald-500 font-bold">+{{ $posCount }}</span>
                                <span class="text-rose-500 font-bold">-{{ $negCount }}</span>
                            </div>
                        </div>

                        {{-- Drilldown Click Button --}}
                        <button type="button"
                            wire:click="$dispatch('open-article-drilldown', { 
                                title: 'Program: {{ addslashes($prog['name'] ?? 'PSN') }}', 
                                subtitle: 'Kategori: {{ addslashes($prog['badge'] ?? 'PSN') }} • Wilayah: Kab. Jombang', 
                                keyword: '{{ addslashes($primaryKw) }}',
                                startDate: '{{ $startDate ?? '' }}',
                                endDate: '{{ $endDate ?? '' }}'
                            })"
                            class="w-full mt-3 text-center py-2 px-3 rounded-lg text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800/80 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-100 transition-all flex items-center justify-center gap-1.5 cursor-pointer shadow-xs hover:shadow-md hover:scale-[1.02]">
                            <span>Buka Berita Terkait ({{ $mentions }})</span>
                            <svg class="h-3.5 w-3.5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
