<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between w-full gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="p-2 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/30">
                        <x-filament::icon icon="heroicon-o-shield-check" class="h-5 w-5" />
                    </div>
                    <div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm sm:text-base font-black text-gray-900 dark:text-white tracking-tight">
                                MEMORANDUM INTELIJEN MEDIA & KEBIJAKAN EKSEKUTIF
                            </span>
                        </div>
                        <div class="text-[11px] text-gray-500 dark:text-slate-400 font-mono">
                            Protokol PDB (Presidential Daily Brief Standard) • Diperbarui: <strong class="text-cyan-400">{{ $lastUpdated ?? ($last_updated ?? now()->format('H:i')) }} WIB</strong>
                        </div>
                    </div>
                </div>

                <div>
                    @php
                        $curThreat = $threatLevel ?? ($threat_level ?? 'HIJAU');
                    @endphp
                    @if(str_contains($curThreat, 'MERAH'))
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-black uppercase tracking-wider bg-rose-500/20 text-rose-400 border border-rose-500/40 animate-pulse flex items-center gap-1.5 shadow-lg shadow-rose-500/20">
                            <span class="h-2 w-2 rounded-full bg-rose-500 animate-ping"></span>
                            🚨 STATUS: {{ $curThreat }}
                        </span>
                    @elseif(str_contains($curThreat, 'KUNING'))
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-amber-500/20 text-amber-300 border border-amber-500/40 flex items-center gap-1.5">
                            <span class="h-2 w-2 rounded-full bg-amber-400"></span>
                            ⚠️ STATUS: {{ $curThreat }}
                        </span>
                    @else
                        <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 flex items-center gap-1.5 shadow-lg shadow-emerald-500/10">
                            <span class="h-2 w-2 rounded-full bg-emerald-400"></span>
                            🛡️ STATUS: {{ $curThreat }}
                        </span>
                    @endif
                </div>
            </div>
        </x-slot>

        <div class="space-y-4">
            <!-- 3 Pilar Eksekutif -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3.5">
                <div class="p-4 bg-emerald-500/10 dark:bg-emerald-950/30 border border-emerald-500/30 rounded-xl relative overflow-hidden group hover:border-emerald-500/50 transition">
                    <div class="flex items-center gap-2 font-black text-emerald-600 dark:text-emerald-400 text-xs mb-2 uppercase tracking-wide">
                        <x-filament::icon icon="heroicon-m-hand-thumb-up" class="w-4 h-4" />
                        <span>Apresiasi Warga Dominan</span>
                    </div>
                    <p class="text-xs text-gray-800 dark:text-emerald-100 leading-relaxed">
                        {{ $highlights['Isu Terhangat Positif'] ?? ($top_positive ?? 'Respon positif warga atas perbaikan sarana publik dan kelancaran event daerah.') }}
                    </p>
                </div>

                <div class="p-4 bg-rose-500/10 dark:bg-rose-950/30 border border-rose-500/30 rounded-xl relative overflow-hidden group hover:border-rose-500/50 transition">
                    <div class="flex items-center gap-2 font-black text-rose-600 dark:text-rose-400 text-xs mb-2 uppercase tracking-wide">
                        <x-filament::icon icon="heroicon-m-exclamation-triangle" class="w-4 h-4" />
                        <span>Titik Rentan & Sorotan Keluhan</span>
                    </div>
                    <p class="text-xs text-gray-800 dark:text-rose-100 leading-relaxed">
                        {{ $highlights['Akar Masalah Negatif'] ?? ($issue_root_cause ?? 'Tuntutan percepatan realisasi infrastruktur dan pemeliharaan sarana.') }}
                    </p>
                </div>

                <div class="p-4 bg-indigo-500/10 dark:bg-indigo-950/30 border border-indigo-500/30 rounded-xl relative overflow-hidden group hover:border-indigo-500/50 transition">
                    <div class="flex items-center gap-2 font-black text-indigo-600 dark:text-indigo-400 text-xs mb-2 uppercase tracking-wide">
                        <x-filament::icon icon="heroicon-m-arrow-right-circle" class="w-4 h-4" />
                        <span>Rekomendasi Respons Pimpinan</span>
                    </div>
                    <p class="text-xs text-gray-800 dark:text-indigo-100 leading-relaxed">
                        {{ $highlights['Rekomendasi Tindak Lanjut'] ?? 'Lakukan pemeliharaan rutin dan publikasikan progres kerja dinas.' }}
                    </p>
                </div>
            </div>

            <!-- Draf Siaran Pers Otomatis untuk Humas -->
            <div x-data="{ 
                open: false, 
                copied: false,
                copyDraft() {
                    const text = this.$refs.draftContent.innerText;
                    navigator.clipboard.writeText(text);
                    this.copied = true;
                    setTimeout(() => { this.copied = false }, 2500);
                }
            }" class="border border-gray-200 dark:border-slate-800 rounded-xl overflow-hidden bg-white dark:bg-slate-900/60 shadow-xs">
                <button
                    type="button"
                    @click="open = !open"
                    class="w-full px-4 py-3 flex items-center justify-between text-left hover:bg-gray-50 dark:hover:bg-slate-800/50 transition"
                >
                    <div class="flex items-center gap-2 text-xs font-bold text-gray-800 dark:text-slate-200">
                        <x-filament::icon icon="heroicon-o-document-text" class="w-4 h-4 text-emerald-400" />
                        <span>Draf Siaran Pers Klarifikasi Otomatis (Humas Pemkab Jombang)</span>
                    </div>
                    <span class="text-xs text-emerald-500 dark:text-emerald-400 font-bold flex items-center gap-1" x-text="open ? 'Tutup Draf ▲' : 'Buka Draf Siaran Pers Siap Rilis ▼'"></span>
                </button>

                <div x-show="open" x-collapse class="p-4 border-t border-gray-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/80">
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-200 dark:border-slate-800">
                        <span class="text-[11px] text-gray-500 dark:text-slate-400">
                            Format rilis standar Diskominfo & Protokol Komunikasi Pimpinan Setda Jombang:
                        </span>
                        <button 
                            type="button"
                            @click="copyDraft()"
                            class="px-3 py-1.5 rounded-lg text-xs font-bold bg-emerald-500 hover:bg-emerald-600 text-white transition flex items-center gap-1.5 shadow-sm hover:scale-[1.02]">
                            <svg x-show="!copied" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3" />
                            </svg>
                            <svg x-show="copied" class="h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span x-text="copied ? 'Tersalin ke Clipboard! ✓' : 'Salin Siaran Pers'"></span>
                        </button>
                    </div>

                    <pre x-ref="draftContent" class="text-xs font-mono text-gray-800 dark:text-slate-200 whitespace-pre-wrap leading-relaxed select-all bg-white dark:bg-slate-900 p-4 rounded-xl border border-gray-200 dark:border-slate-800 shadow-inner">{{ $draftPressRelease ?? ($draft_press_release ?? '-') }}</pre>
                    
                    <p class="text-[11px] text-gray-400 dark:text-slate-500 mt-2">
                        *Klik tombol "Salin Siaran Pers" di atas untuk langsung mendistribusikan ke awak media, kanal WhatsApp resmi Pemda, atau portal berita daerah.
                    </p>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
