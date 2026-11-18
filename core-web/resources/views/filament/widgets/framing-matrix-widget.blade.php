<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-2">
                    <div class="p-1.5 rounded-lg bg-indigo-500/20 text-indigo-400">
                        <x-filament::icon icon="heroicon-o-view-columns" class="h-4 w-4" />
                    </div>
                    <div>
                        <span class="text-sm sm:text-base font-black text-gray-900 dark:text-white tracking-tight">
                            Matriks Framing Berita (5 Dimensi Analisis)
                        </span>
                        <div class="text-[10px] text-gray-500 dark:text-slate-400">
                            Taksonomi Konstruksi Narasi Media Massa & Publik
                        </div>
                    </div>
                </div>
                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                    5 Sudut Pandang
                </span>
            </div>
        </x-slot>

        <div class="space-y-3">
            <p class="text-xs text-gray-500 dark:text-slate-400">
                Memetakan motif dan sudut pandang framing pemberitaan pada periode aktif. <span class="text-indigo-400 font-bold">Klik dimensi untuk investigasi berita:</span>
            </p>

            <div class="space-y-2.5 max-h-[360px] overflow-y-auto pr-1">
                @foreach ($frames as $frame)
                    @php
                        $badgeBg = match ($frame['color'] ?? '') {
                            'rose', 'red' => 'bg-rose-500/15 text-rose-400 border-rose-500/30',
                            'amber' => 'bg-amber-500/15 text-amber-400 border-amber-500/30',
                            'sky' => 'bg-cyan-500/15 text-cyan-400 border-cyan-500/30',
                            'purple' => 'bg-purple-500/15 text-purple-400 border-purple-500/30',
                            default => 'bg-emerald-500/15 text-emerald-400 border-emerald-500/30',
                        };

                        $barColor = match ($frame['color'] ?? '') {
                            'rose', 'red' => 'bg-gradient-to-r from-rose-500 to-red-600',
                            'amber' => 'bg-gradient-to-r from-amber-500 to-yellow-600',
                            'sky' => 'bg-gradient-to-r from-cyan-500 to-blue-600',
                            'purple' => 'bg-gradient-to-r from-purple-500 to-indigo-600',
                            default => 'bg-gradient-to-r from-emerald-500 to-teal-600',
                        };
                    @endphp

                    <div class="p-3 rounded-xl border border-gray-100 dark:border-slate-800 bg-white dark:bg-slate-900/70 hover:bg-slate-50 dark:hover:bg-slate-800/60 transition-all cursor-pointer group shadow-2xs hover:shadow-md"
                         wire:click="$dispatch('open-article-drilldown', { 
                            title: 'Framing: {{ addslashes($frame['label'] ?? '') }}', 
                            subtitle: 'Dimensi: {{ addslashes($frame['name'] ?? '') }} • Total: {{ $frame['count'] ?? 0 }} Berita', 
                            framingCategory: '{{ addslashes($frame['category'] ?? '') }}',
                            startDate: '{{ $startDate ?? '' }}',
                            endDate: '{{ $endDate ?? '' }}'
                         })">
                        <div class="flex items-center justify-between text-xs mb-1.5">
                            <span class="font-bold text-gray-800 dark:text-slate-200 group-hover:text-indigo-400 transition flex items-center gap-1.5">
                                {{ $frame['label'] }}
                                <svg class="h-3 w-3 opacity-0 group-hover:opacity-100 text-indigo-400 transition transform group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                            <div class="flex items-center gap-2">
                                <span class="font-black font-mono text-gray-900 dark:text-white">{{ $frame['count'] }} <span class="text-[10px] font-normal text-gray-400">({{ $frame['percentage'] }}%)</span></span>
                                <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded-md border {{ $badgeBg }}">
                                    {{ $frame['risk_level'] }}
                                </span>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="w-full bg-gray-100 dark:bg-slate-800 rounded-full h-1.5 overflow-hidden">
                            <div class="{{ $barColor }} h-1.5 rounded-full transition-all duration-500" style="width: {{ $frame['percentage'] }}%"></div>
                        </div>

                        <p class="text-[11px] text-gray-400 dark:text-slate-400 mt-2 line-clamp-1 italic">
                            💡 Rekomendasi: {{ $frame['recommendation'] }}
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
