@extends('layouts.app')

@section('title', 'Panduan Penggunaan Sistem SISMANTIK')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                Panduan Penggunaan & Prosedur Operasional SISMANTIK
            </h1>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">
                Petunjuk tata kelola sistem, siklus pembaruan data, integrasi NLP, dan opsi pemulihan data total.
            </p>
        </div>
        <div class="flex items-center gap-2">
            <button type="button" @click="confirmReplaceModal = true"
                class="px-4 py-2 rounded-xl text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 transition flex items-center gap-2 shadow-md">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                <span>Opsi: Replace Data Total</span>
            </button>
        </div>
    </div>

    {{-- WORKFLOW CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center font-black">
                01
            </div>
            <h3 class="text-base font-black text-slate-900 dark:text-white">
                Penarikan Berita (Inkremental)
            </h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                Mode ini dijalankan otomatis oleh scheduler atau via tombol <strong>"Tarik Berita"</strong> di topbar. Sistem akan memeriksa berita terbaru tanpa menghapus arsip histori yang telah tersimpan.
            </p>
            <div class="p-2.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-[11px] font-mono text-slate-700 dark:text-slate-300">
                php artisan sismantik:sync --max=20
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-black">
                02
            </div>
            <h3 class="text-base font-black text-slate-900 dark:text-white">
                Analisis Sentimen & Geotagging
            </h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                Setiap berita yang masuk langsung dianalisis oleh AI NLP Engine (FastAPI port 8001) untuk mengklasifikasi 3 sentimen, 6 spektrum emosi, ekstraksi 21 kecamatan, dan pencocokan 10 OPD.
            </p>
            <div class="p-2.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-[11px] font-mono text-slate-700 dark:text-slate-300">
                http://127.0.0.1:8001/analyze-content
            </div>
        </div>

        <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-3">
            <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-600 dark:text-rose-400 flex items-center justify-center font-black">
                03
            </div>
            <h3 class="text-base font-black text-slate-900 dark:text-white">
                Replace Data Total (Reset & Reprocess)
            </h3>
            <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed">
                Digunakan jika data histori rusak atau model NLP diperbarui. Sistem akan membersihkan tabel konten lama dan menarik ulang data segar 12 bulan terakhir secara bersih.
            </p>
            <div class="p-2.5 rounded-lg bg-slate-100 dark:bg-slate-800 text-[11px] font-mono text-slate-700 dark:text-slate-300">
                php artisan sismantik:replace-total --months=12
            </div>
        </div>
    </div>

    {{-- ARCHITECTURE & FAQS --}}
    <div class="p-6 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm space-y-5">
        <h2 class="text-base font-black text-slate-900 dark:text-white uppercase tracking-wider">
            Struktur Arsitektur & Probis Sistem
        </h2>

        <div class="space-y-4 text-xs text-slate-700 dark:text-slate-300 leading-relaxed">
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-2">
                <h4 class="font-bold text-slate-900 dark:text-white text-sm">Bagaimana Tanggal Berita Dihitung?</h4>
                <p>
                    Semua grafik dan filter tanggal dihitung berdasarkan <code>published_at</code> (tanggal riil saat berita tersebut diterbitkan oleh media bersangkutan), bukan tanggal saat robot scraper mengunduh berita. Ini memastikan akurasi timeline historis 12 bulan tetap valid.
                </p>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-2">
                <h4 class="font-bold text-slate-900 dark:text-white text-sm">Bagaimana Interaksi Drilldown Berita Bekerja?</h4>
                <p>
                    Setiap kartu Program Strategis Nasional (PSN), baris Rapor OPD, klaster Matriks Framing Isu, irisan Spektrum Emosi, dan wilayah Kecamatan di Peta Geospasial dapat diklik langsung. Sistem akan membuka modal drilldown yang memuat daftar artikel berita spesifik terkait topik tersebut.
                </p>
            </div>

            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-2">
                <h4 class="font-bold text-slate-900 dark:text-white text-sm">Peta Geospasial & Dukungan Offline Basemap</h4>
                <p>
                    Peta GIS terisolasi di halaman khusus <code>/peta</code> dengan 21 batas poligon resmi GADM 4.1 Kabupaten Jombang. Basemap menggunakan OpenStreetMap dan Esri World Imagery yang bebas watermark dan tidak memerlukan API key eksternal.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection

