@extends('layouts.app')

@section('title', 'Riwayat Sinkronisasi Berita')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                Riwayat & Log Audit Sinkronisasi
            </h1>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                Catatan sesi penarikan berita otomatis maupun manual dari portal eksternal beserta metrik durasi dan status.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="triggerSync()" :disabled="isSyncing"
                class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 transition flex items-center gap-2 shadow-md">
                <svg class="w-4 h-4" :class="isSyncing ? 'animate-spin' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                <span x-text="isSyncing ? 'Menjalankan...' : 'Tarik Berita Baru Sekarang'"></span>
            </button>
        </div>
    </div>

    {{-- SYNC JOBS TABLE --}}
    <div class="rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        <th class="py-3.5 px-4 w-12 text-center">#</th>
                        <th class="py-3.5 px-4 w-44">Trigger & Mode</th>
                        <th class="py-3.5 px-4 w-44">Sumber / Endpoint</th>
                        <th class="py-3.5 px-4 w-32 text-center">Data Ditarik</th>
                        <th class="py-3.5 px-4 w-32 text-center">Diproses NLP</th>
                        <th class="py-3.5 px-4 w-44">Waktu Mulai - Selesai</th>
                        <th class="py-3.5 px-4 w-28 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($syncJobs as $idx => $job)
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                            <td class="py-3.5 px-4 text-center font-mono text-slate-400 font-bold">
                                {{ $syncJobs->firstItem() + $idx }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 dark:text-white capitalize">
                                    {{ $job->triggered_by ?: 'Scheduler (Cron)' }}
                                </span>
                                <div class="text-[10px] font-mono text-slate-400 truncate">{{ $job->job_uuid ?: 'Manual Task' }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-800 dark:text-slate-200">
                                    {{ $job->source ? $job->source->name : 'Agregator Seluruh Sumber' }}
                                </div>
                                <div class="text-[10px] text-slate-400">{{ $job->endpoint ? $job->endpoint->name : '-' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-800 dark:text-slate-200">
                                {{ number_format($job->items_scraped) }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                {{ number_format($job->items_processed) }}
                            </td>
                            <td class="py-3.5 px-4 font-mono text-[11px] text-slate-500">
                                <div>{{ $job->started_at ? $job->started_at->format('d M Y, H:i') : '-' }}</div>
                                <div class="text-[10px] text-slate-400">
                                    @if($job->finished_at && $job->started_at)
                                        Durasi: {{ $job->started_at->diffInSeconds($job->finished_at) }} detik
                                    @else
                                        -
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase {{ $job->status === 'success' || $job->status === 'completed' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : ($job->status === 'running' ? 'bg-cyan-100 text-cyan-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ $job->status ?: 'Selesai' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                Belum ada riwayat sinkronisasi yang tercatat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $syncJobs->links() }}
        </div>
    </div>

</div>
@endsection

