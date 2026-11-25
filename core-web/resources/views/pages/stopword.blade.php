@extends('layouts.app')

@section('title', 'Manajemen Stopwords & Pembersih Teks')

@section('content')
<div x-data="stopwordManager()" class="space-y-6">

    {{-- ALERT MESSAGES --}}
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-800 dark:text-emerald-300 flex items-center justify-between text-xs font-bold shadow-2xs">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button @click="$el.parentElement.remove()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white">&times;</button>
        </div>
    @endif

    {{-- HEADER --}}
    <div class="p-5 sm:p-6 rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 border border-indigo-500/30">
                    Modul Text Preprocessing & NLP
                </span>
                <span class="text-xs font-mono font-bold text-slate-400">Total: {{ number_format($totalAll) }} Kata</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white tracking-tight">
                Manajemen Stopwords & Filter Kebisingan
            </h1>
            <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 max-w-2xl leading-relaxed">
                Kelola kamus kata yang diabaikan (stopword) dalam pemrosesan NLP dan visualisasi Wordcloud Sentimen. Kata aktif akan disaring agar dashboard hanya menampilkan inti isu dan kata sentimen substansial.
            </p>
        </div>

        <div class="flex items-center gap-2.5 shrink-0">
            <button type="button" @click="openAddModal()"
                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-black text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 shadow-md shadow-emerald-500/20 transition-all cursor-pointer">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
                </svg>
                <span>+ Tambah Stopwords</span>
            </button>
        </div>
    </div>

    {{-- STATS PER CATEGORY --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
        @php
            $badges = [
                'daerah' => ['bg' => 'from-blue-500/10 to-indigo-500/10', 'border' => 'border-blue-500/30', 'text' => 'text-blue-700 dark:text-blue-300', 'badge' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300'],
                'waktu' => ['bg' => 'from-amber-500/10 to-orange-500/10', 'border' => 'border-amber-500/30', 'text' => 'text-amber-700 dark:text-amber-300', 'badge' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300'],
                'angka' => ['bg' => 'from-emerald-500/10 to-teal-500/10', 'border' => 'border-emerald-500/30', 'text' => 'text-emerald-700 dark:text-emerald-300', 'badge' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300'],
                'sapaan_gelar' => ['bg' => 'from-purple-500/10 to-pink-500/10', 'border' => 'border-purple-500/30', 'text' => 'text-purple-700 dark:text-purple-300', 'badge' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/40 dark:text-purple-300'],
                'aksi_umum' => ['bg' => 'from-rose-500/10 to-red-500/10', 'border' => 'border-rose-500/30', 'text' => 'text-rose-700 dark:text-rose-300', 'badge' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/40 dark:text-rose-300'],
                'umum' => ['bg' => 'from-slate-500/10 to-slate-700/10', 'border' => 'border-slate-300 dark:border-slate-700', 'text' => 'text-slate-700 dark:text-slate-300', 'badge' => 'bg-slate-100 text-slate-800 dark:bg-slate-800 dark:text-slate-300'],
            ];
        @endphp

        @foreach($categories as $catKey => $catLabel)
            @php
                $style = $badges[$catKey] ?? $badges['umum'];
                $count = $categoryCounts[$catKey] ?? 0;
                $isActiveCat = ($category === $catKey);
            @endphp
            <a href="{{ route('stopwords.index', ['category' => $catKey, 'search' => $search]) }}"
                class="p-3.5 rounded-2xl bg-gradient-to-br {{ $style['bg'] }} border {{ $isActiveCat ? 'ring-2 ring-emerald-500 ' . $style['border'] : $style['border'] }} transition hover:scale-102 flex flex-col justify-between">
                <div class="text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400 truncate" title="{{ $catLabel }}">
                    {{ $catLabel }}
                </div>
                <div class="mt-2 flex items-baseline justify-between">
                    <span class="text-lg font-black {{ $style['text'] }} font-mono">
                        {{ $count }}
                    </span>
                    <span class="text-[9px] font-bold px-1.5 py-0.5 rounded-md {{ $style['badge'] }}">
                        Kata
                    </span>
                </div>
            </a>
        @endforeach
    </div>

    {{-- FILTER & SEARCH TOOLBAR --}}
    <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
        {{-- Category Tabs --}}
        <div class="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 text-xs">
            <a href="{{ route('stopwords.index', ['category' => 'all', 'search' => $search, 'status' => $status]) }}"
                class="px-3 py-1.5 rounded-xl font-bold transition whitespace-nowrap {{ $category === 'all' ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                Semua Kategori ({{ $totalAll }})
            </a>
            @foreach($categories as $catKey => $catLabel)
                <a href="{{ route('stopwords.index', ['category' => $catKey, 'search' => $search, 'status' => $status]) }}"
                    class="px-2.5 py-1.5 rounded-xl font-bold transition whitespace-nowrap {{ $category === $catKey ? 'bg-emerald-600 text-white dark:bg-emerald-500 dark:text-slate-950 shadow-xs' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                    {{ $catLabel }}
                </a>
            @endforeach
        </div>

        {{-- Search & Status Filter Form --}}
        <form method="GET" action="{{ route('stopwords.index') }}" class="flex items-center gap-2">
            <input type="hidden" name="category" value="{{ $category }}">
            
            <select name="status" onchange="this.form.submit()"
                class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="all" {{ $status === 'all' ? 'selected' : '' }}>Semua Status</option>
                <option value="active" {{ $status === 'active' ? 'selected' : '' }}>Hanya Aktif</option>
                <option value="inactive" {{ $status === 'inactive' ? 'selected' : '' }}>Hanya Nonaktif</option>
            </select>

            <div class="relative flex-1 md:w-56">
                <input type="text" name="search" value="{{ $search }}" placeholder="Cari kata stopword..."
                    class="w-full pl-8 pr-3 py-1.5 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <svg class="w-3.5 h-3.5 absolute left-2.5 top-2.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700 transition">
                Cari
            </button>
            @if(!empty($search) || $category !== 'all' || $status !== 'all')
                <a href="{{ route('stopwords.index') }}" class="px-2.5 py-1.5 rounded-xl text-xs font-bold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 transition" title="Reset Filter">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- DATA TABLE --}}
    <div class="rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-800/80 border-b border-slate-200 dark:border-slate-700 text-[10px] font-black uppercase tracking-wider text-slate-500 dark:text-slate-400">
                        <th class="py-3.5 px-4 w-12 text-center">#</th>
                        <th class="py-3.5 px-4">Kata (Stopword)</th>
                        <th class="py-3.5 px-4 w-52">Kategori Klasifikasi</th>
                        <th class="py-3.5 px-4 w-32 text-center">Status Filter</th>
                        <th class="py-3.5 px-4 w-44 text-center">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-slate-800">
                    @forelse($stopwords as $idx => $sw)
                        @php
                            $catStyle = $badges[$sw->category] ?? $badges['umum'];
                        @endphp
                        <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/40 transition">
                            <td class="py-3.5 px-4 text-center font-mono text-slate-400 font-bold">
                                {{ $stopwords->firstItem() + $idx }}
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2.5 py-1 rounded-lg font-mono text-xs font-bold bg-slate-100 dark:bg-slate-800 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700">
                                    {{ $sw->word }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold {{ $catStyle['badge'] }}">
                                    {{ $categories[$sw->category] ?? $sw->category }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase {{ $sw->is_active ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-500/20 dark:text-emerald-300' : 'bg-slate-100 text-slate-500 dark:bg-slate-800 dark:text-slate-400' }}">
                                    {{ $sw->is_active ? 'Aktif (Disaring)' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    {{-- Toggle Status Button --}}
                                    <form action="{{ route('stopwords.toggle', $sw->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" 
                                            class="px-2.5 py-1 rounded-lg text-[11px] font-bold transition {{ $sw->is_active ? 'text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/40' : 'text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-950/40' }}"
                                            title="{{ $sw->is_active ? 'Nonaktifkan agar kata ini muncul di Wordcloud' : 'Aktifkan agar kata ini disaring dari Wordcloud' }}">
                                            {{ $sw->is_active ? 'Matikan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    {{-- Edit Button --}}
                                    <button type="button" @click="openEditModal({{ json_encode($sw) }})"
                                        class="px-2.5 py-1 rounded-lg text-[11px] font-bold text-indigo-600 hover:bg-indigo-50 dark:text-indigo-400 dark:hover:bg-indigo-950/40 transition">
                                        Ubah
                                    </button>

                                    {{-- Delete Button --}}
                                    <form action="{{ route('stopwords.destroy', $sw->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kata \'{{ $sw->word }}\' dari daftar stopword?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-2 py-1 rounded-lg text-[11px] font-bold text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 transition">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-10 h-10 text-slate-300 dark:text-slate-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <p class="font-bold text-slate-600 dark:text-slate-300">Tidak ada kata stopword yang cocok dengan kriteria filter.</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Silakan reset filter pencarian atau tambahkan kata baru.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="p-4 border-t border-slate-200 dark:border-slate-800">
            {{ $stopwords->links() }}
        </div>
    </div>

    {{-- MODAL TAMBAH STOPWORD (SUPPORT BULK) --}}
    <div x-show="showAddModal" x-cloak
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="showAddModal = false"
            class="w-full max-w-lg rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl p-6 space-y-4">
            
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                    Tambah Kata Stopword Baru
                </h3>
                <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white text-lg font-bold">&times;</button>
            </div>

            <form action="{{ route('stopwords.store') }}" method="POST" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Pilih Kategori Klasifikasi
                    </label>
                    <select name="category" required
                        class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white font-bold focus:outline-none focus:ring-2 focus:ring-emerald-500">
                        @foreach($categories as $catKey => $catLabel)
                            <option value="{{ $catKey }}" {{ $category === $catKey ? 'selected' : '' }}>
                                {{ $catLabel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Kata Stopword (Bisa Banyak Sekaligus)
                    </label>
                    <textarea name="words" rows="4" required placeholder="Contoh: kabupaten, jombang, pemkab, pemda (pisahkan dengan koma atau baris baru)"
                        class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white font-mono focus:outline-none focus:ring-2 focus:ring-emerald-500"></textarea>
                    <p class="text-[10px] text-slate-400 mt-1">
                        Tip: Anda dapat memasukkan satu kata atau banyak kata yang dipisahkan dengan tanda koma atau baris baru.
                    </p>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showAddModal = false"
                        class="px-4 py-2 rounded-xl font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-xl font-black text-white bg-emerald-600 hover:bg-emerald-500 shadow-md shadow-emerald-500/20 transition">
                        Simpan Stopword
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL UBAH STOPWORD --}}
    <div x-show="showEditModal" x-cloak
        class="fixed inset-0 z-50 overflow-y-auto bg-slate-950/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div @click.away="showEditModal = false"
            class="w-full max-w-md rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-2xl p-6 space-y-4">
            
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                <h3 class="text-base font-black text-slate-900 dark:text-white flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-indigo-500"></span>
                    Ubah Data Stopword
                </h3>
                <button @click="showEditModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-white text-lg font-bold">&times;</button>
            </div>

            <form :action="'/stopwords/' + editData.id" method="POST" class="space-y-4 text-xs">
                @csrf
                @method('PUT')
                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Kata Stopword
                    </label>
                    <input type="text" name="word" x-model="editData.word" required
                        class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white font-mono font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                        Kategori Klasifikasi
                    </label>
                    <select name="category" x-model="editData.category" required
                        class="w-full px-3 py-2 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white font-bold focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        @foreach($categories as $catKey => $catLabel)
                            <option value="{{ $catKey }}">{{ $catLabel }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2 pt-1">
                    <input type="checkbox" name="is_active" id="edit_is_active" value="1" :checked="editData.is_active"
                        class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                    <label for="edit_is_active" class="font-bold text-slate-700 dark:text-slate-300">
                        Aktifkan Filter (Kata ini diabaikan dari Wordcloud)
                    </label>
                </div>

                <div class="flex items-center justify-end gap-2 pt-2 border-t border-slate-100 dark:border-slate-800">
                    <button type="button" @click="showEditModal = false"
                        class="px-4 py-2 rounded-xl font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 rounded-xl font-black text-white bg-indigo-600 hover:bg-indigo-500 shadow-md shadow-indigo-500/20 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    function stopwordManager() {
        return {
            showAddModal: false,
            showEditModal: false,
            editData: {
                id: null,
                word: '',
                category: 'umum',
                is_active: true
            },
            openAddModal() {
                this.showAddModal = true;
            },
            openEditModal(item) {
                this.editData = {
                    id: item.id,
                    word: item.word,
                    category: item.category,
                    is_active: !!item.is_active
                };
                this.showEditModal = true;
            }
        }
    }
</script>
@endsection

