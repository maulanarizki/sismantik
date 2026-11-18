<div>
    @if($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            {{-- Backdrop --}}
            <div class="fixed inset-0 bg-slate-950/75 backdrop-blur-sm transition-opacity" wire:click="closeModal"></div>

            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-gray-200 dark:border-slate-800 flex flex-col max-h-[88vh]">
                    
                    {{-- Header Modal --}}
                    <div class="px-6 py-4 bg-gradient-to-r from-slate-50 to-gray-100 dark:from-slate-900 dark:to-slate-800/80 border-b border-gray-200 dark:border-slate-700/60 flex items-center justify-between shrink-0">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-950/60 dark:text-emerald-300 border border-emerald-300 dark:border-emerald-800">
                                    Detail Intelijen
                                </span>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white" id="modal-title">
                                    {{ $title }}
                                </h3>
                            </div>
                            @if($subtitle)
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                                    {{ $subtitle }}
                                </p>
                            @endif
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-xs px-2.5 py-1 rounded-full bg-slate-200 dark:bg-slate-800 font-bold text-slate-700 dark:text-slate-300">
                                {{ number_format($totalCount) }} Berita Ditemukan
                            </span>
                            <button wire:click="closeModal" type="button" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 p-1 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-800 transition">
                                <span class="sr-only">Tutup</span>
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Body: List Berita Terkait --}}
                    <div class="px-6 py-4 overflow-y-auto space-y-3 divide-y divide-gray-100 dark:divide-slate-800/80">
                        @forelse($articles as $art)
                            @php
                                $sent = $art->sentimentResult;
                                $sentLabel = $sent->sentiment_label ?? 'neutral';
                                $sentColor = match($sentLabel) {
                                    'positive' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300 dark:border-emerald-800',
                                    'negative' => 'bg-rose-50 text-rose-700 border-rose-200 dark:bg-rose-950/40 dark:text-rose-300 dark:border-rose-800',
                                    default => 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-slate-800 dark:text-gray-300 dark:border-slate-700',
                                };
                                $scoreText = $sent ? ($sent->sentiment_score >= 0 ? '+' : '') . number_format($sent->sentiment_score, 2) : '0.00';
                            @endphp
                            <div class="pt-3 first:pt-0 flex flex-col gap-1.5 hover:bg-gray-50/50 dark:hover:bg-slate-800/30 p-2.5 rounded-xl transition">
                                {{-- Meta Badges Top --}}
                                <div class="flex flex-wrap items-center gap-1.5 text-[11px]">
                                    {{-- Scope Badge --}}
                                    @if($art->scope === 'national_strategic')
                                        <span class="px-2 py-0.5 rounded font-semibold bg-indigo-50 text-indigo-700 dark:bg-indigo-950/50 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                            PSN Pusat
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 rounded font-semibold bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            Lokal Pemkab
                                        </span>
                                    @endif

                                    {{-- Sentiment Badge --}}
                                    <span class="px-2 py-0.5 rounded font-bold border {{ $sentColor }}">
                                        {{ ucfirst($sentLabel) }} ({{ $scoreText }})
                                    </span>

                                    {{-- Emotion Badge --}}
                                    @if($sent && $sent->emotion_label)
                                        <span class="px-2 py-0.5 rounded font-medium bg-amber-50 text-amber-700 dark:bg-amber-950/40 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                            Emosi: {{ ucfirst($sent->emotion_label) }}
                                        </span>
                                    @endif

                                    {{-- Framing Badge --}}
                                    @if($sent && $sent->framing_category)
                                        <span class="px-2 py-0.5 rounded font-medium bg-purple-50 text-purple-700 dark:bg-purple-950/40 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                                            Framing: {{ ucfirst(str_replace('_', ' ', $sent->framing_category)) }}
                                        </span>
                                    @endif

                                    {{-- Source & Date --}}
                                    <span class="text-gray-400 dark:text-slate-400 ml-auto">
                                        {{ $art->endpoint->source->name ?? 'Portal Berita' }} • {{ $art->published_at ? $art->published_at->format('d M Y, H:i') : '-' }} WIB
                                    </span>
                                </div>

                                {{-- Title with Outbound Link --}}
                                <a href="{{ $art->url }}" target="_blank" rel="noopener noreferrer" class="text-sm font-bold text-gray-900 dark:text-white hover:text-emerald-600 dark:hover:text-emerald-400 flex items-start gap-1.5 group leading-snug">
                                    <span>{{ $art->title }}</span>
                                    <svg class="h-3.5 w-3.5 text-gray-400 group-hover:text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>

                                {{-- Snippet Content --}}
                                <p class="text-xs text-gray-600 dark:text-slate-300 line-clamp-2 leading-relaxed">
                                    {{ Str::limit(strip_tags($art->clean_text ?: $art->content_text), 220) }}
                                </p>
                            </div>
                        @empty
                            <div class="py-12 text-center text-gray-500 dark:text-slate-400">
                                <svg class="mx-auto h-10 w-10 text-gray-300 dark:text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                </svg>
                                <p class="text-sm font-medium">Tidak ada berita yang cocok dengan kriteria filter aktif.</p>
                            </div>
                        @endforelse
                    </div>

                    {{-- Footer Modal with Pagination --}}
                    <div class="px-6 py-3 bg-gray-50 dark:bg-slate-900 border-t border-gray-200 dark:border-slate-800 flex items-center justify-between shrink-0">
                        <div class="text-xs text-gray-500 dark:text-slate-400">
                            Menampilkan halaman {{ $articles->currentPage() }} dari {{ $articles->lastPage() }}
                        </div>
                        <div class="flex items-center gap-2">
                            {{ $articles->links() }}
                            <button wire:click="closeModal" type="button" class="px-4 py-1.5 text-xs font-semibold rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-slate-800 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-200 transition">
                                Tutup
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endif
</div>

