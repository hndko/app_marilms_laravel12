@extends('layouts.app-backend')

@section('title', 'Daftar Kuis AI')

@section('content')
<div x-data="{ 
    showInfoModal: false,
    init() {
        this.$watch('showInfoModal', val => document.body.style.overflow = val ? 'hidden' : 'unset');
    }
}" class="space-y-6">

    <!-- TailAdmin Top Header Card Wrapper with Panduan Modul Button -->
    <div class="p-5 md:p-6 rounded-2xl bg-white border border-gray-200 shadow-theme-xs flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800 tracking-tight">Manajemen Kuis & Bank Soal AI</h2>
            <p class="text-xs text-gray-500">Kelola paket kuis, buat kuis otomatis berbasis AI, dan atur hak akses peserta kuis.</p>
        </div>

        <div class="flex flex-wrap items-center gap-3">
            <button @click="showInfoModal = true" 
                class="px-3.5 py-2.5 rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-600 border border-brand-200 text-xs font-bold transition flex items-center gap-2 shadow-2xs">
                <i class="fas fa-circle-info text-brand-500 text-sm"></i>
                <span>Panduan Modul</span>
            </button>

            <a href="{{ route('tenant.owner.quizzes.create', ['tenant' => $tenant]) }}" 
                class="px-4 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs shadow-theme-xs transition flex items-center gap-2">
                <i class="fas fa-wand-magic-sparkles text-xs"></i>
                <span>Buat Kuis Baru (AI / Manual)</span>
            </a>
        </div>
    </div>

    <!-- Teleport Panduan Modul to Body -->
    <template x-teleport="body">
        <div x-show="showInfoModal" x-cloak
            @keydown.escape.window="showInfoModal = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[99999] flex items-center justify-center p-4 sm:p-6 bg-gray-900/60 backdrop-blur-md overflow-y-auto"
            style="display: none;">
            
            <div @click="showInfoModal = false" class="fixed inset-0 h-full w-full"></div>

            <div class="relative w-full max-w-[580px] rounded-3xl bg-white p-6 sm:p-8 shadow-2xl border border-gray-200 z-10 flex flex-col max-h-[85vh] animate-in fade-in zoom-in duration-150">
                
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-500 border border-brand-200 flex items-center justify-center font-bold shrink-0">
                            <i class="fas fa-wand-magic-sparkles text-base"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Panduan Modul Manajemen Kuis</h3>
                            <p class="text-xs text-gray-500">Tata cara pembuatan kuis, generator AI, dan pengelolaan soal.</p>
                        </div>
                    </div>
                    <button @click="showInfoModal = false" class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-100 text-gray-400 hover:bg-gray-200 hover:text-gray-700 transition">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <div class="flex-1 overflow-y-auto py-4 space-y-4 text-xs text-gray-600 leading-relaxed pr-2">
                    <div class="space-y-1.5 bg-gray-50 p-4 rounded-2xl border border-gray-200/80">
                        <h4 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-bullseye text-brand-500"></i>
                            Fungsi Utama Modul Kuis
                        </h4>
                        <p>
                            Modul ini digunakan oleh Owner Lembaga untuk membuat, mengedit, dan mempublikasikan kuis ujian. Anda dapat memanfaatkan AI Quiz Generator untuk membuat paket soal lengkap beserta opsi jawaban dan pembahasannya secara otomatis.
                        </p>
                    </div>

                    <div class="space-y-2">
                        <h4 class="font-bold text-gray-900 flex items-center gap-2">
                            <i class="fas fa-layer-group text-amber-500"></i>
                            Status Kuis
                        </h4>
                        <div class="space-y-2">
                            <div class="p-3 rounded-2xl bg-white border border-gray-200 space-y-1">
                                <span class="font-bold text-success-700 flex items-center gap-1.5">
                                    <i class="fas fa-check-circle text-xs"></i> Status Aktif (Public/Assigned)
                                </span>
                                <p>Kuis dapat dilihat dan dikerjakan oleh peserta yang terdaftar.</p>
                            </div>
                            <div class="p-3 rounded-2xl bg-white border border-gray-200 space-y-1">
                                <span class="font-bold text-amber-700 flex items-center gap-1.5">
                                    <i class="fas fa-edit text-xs"></i> Status Draft
                                </span>
                                <p>Kuis dalam tahap pembuatan atau penyuntingan soal, belum dapat dikerjakan oleh peserta.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end shrink-0">
                    <button @click="showInfoModal = false" 
                        class="px-6 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs shadow-theme-xs transition">
                        Saya Mengerti
                    </button>
                </div>
            </div>
        </div>
    </template>

    <!-- TailAdmin Filter & Search Card Wrapper -->
    <div class="p-5 rounded-2xl border border-gray-200 bg-white shadow-theme-xs">
        <form method="GET" action="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenant]) }}" 
            class="grid grid-cols-1 sm:grid-cols-12 gap-4 items-end">
            
            <!-- Search Query -->
            <div class="sm:col-span-5 space-y-1.5">
                <label for="search" class="block text-xs font-bold text-gray-700">Cari Judul / Deskripsi Kuis</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-search text-xs"></i>
                    </span>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                        placeholder="Ketik kata kunci judul, topik, atau materi..."
                        class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs" />
                </div>
            </div>

            <!-- Category Filter -->
            <div class="sm:col-span-3 space-y-1.5">
                <label for="category" class="block text-xs font-bold text-gray-700">Kategori</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-tag text-xs"></i>
                    </span>
                    <select name="category" id="category" @change="$el.closest('form').submit()"
                        class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="sm:col-span-2 space-y-1.5">
                <label for="status" class="block text-xs font-bold text-gray-700">Status</label>
                <div class="relative">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400">
                        <i class="fas fa-filter text-xs"></i>
                    </span>
                    <select name="status" id="status" @change="$el.closest('form').submit()"
                        class="h-11 w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 pl-10 pr-4 text-xs font-medium text-gray-800 focus:border-brand-500 focus:bg-white focus:outline-none transition shadow-2xs">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                    </select>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="sm:col-span-2 flex items-center gap-2">
                <button type="submit" 
                    class="h-11 px-4 rounded-xl bg-gray-900 hover:bg-gray-800 text-white font-bold text-xs shadow-2xs transition flex items-center justify-center gap-2 flex-1">
                    <i class="fas fa-filter text-xs"></i>
                    <span>Filter</span>
                </button>
                @if(request()->hasAny(['search', 'category', 'status']))
                    <a href="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenant]) }}" 
                        class="h-11 px-3.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-gray-600 font-bold text-xs transition flex items-center justify-center">
                        <i class="fas fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Quiz Cards Grid -->
    @if($quizzes->isEmpty())
        <div class="p-12 rounded-2xl border border-dashed border-gray-300 bg-white text-center space-y-4 shadow-theme-xs">
            <div class="w-16 h-16 rounded-2xl bg-brand-50 text-brand-500 mx-auto flex items-center justify-center text-2xl font-bold border border-brand-100">
                <i class="fas fa-folder-open"></i>
            </div>
            <div class="space-y-1">
                <h3 class="text-base font-bold text-gray-900">Belum Ada Kuis Ditemukan</h3>
                <p class="text-xs text-gray-500 max-w-md mx-auto">
                    Mulai buat kuis pertama Anda dengan generator otomatis berteknologi AI atau buat paket kuis manual.
                </p>
            </div>
            <a href="{{ route('tenant.owner.quizzes.create', ['tenant' => $tenant]) }}" 
                class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-xs shadow-theme-xs transition">
                <i class="fas fa-wand-magic-sparkles text-xs"></i>
                <span>Generate Kuis Sekarang</span>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($quizzes as $quiz)
                <div class="rounded-2xl border bg-white p-5 md:p-6 shadow-theme-xs hover:border-brand-300 transition flex flex-col justify-between {{ $quiz->status === 'active' ? 'border-gray-200' : 'border-gray-200/80 bg-gray-50/40' }}">
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-start gap-3">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-brand-50 text-brand-700 border border-brand-200">
                                {{ $quiz->category ?: 'Umum' }}
                            </span>
                            @if($quiz->status === 'active')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-success-50 text-success-700 border border-success-200">
                                    <i class="fas fa-check-circle mr-1"></i> Aktif
                                </span>
                            @elseif($quiz->status === 'draft')
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                    <i class="fas fa-pen mr-1"></i> Draft
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-error-50 text-error-700 border border-error-200">
                                    <i class="fas fa-archive mr-1"></i> Arsip
                                </span>
                            @endif
                        </div>

                        <div class="space-y-1">
                            <h3 class="text-base font-bold text-gray-900 leading-snug line-clamp-2">
                                <a href="{{ route('tenant.owner.quizzes.show', ['quiz' => $quiz->id]) }}" class="hover:text-brand-600 transition">
                                    {{ $quiz->title }}
                                </a>
                            </h3>
                            <p class="text-xs text-gray-500 line-clamp-2 leading-relaxed">
                                {{ $quiz->description ?: 'Tidak ada deskripsi.' }}
                            </p>
                        </div>

                        <!-- Specs Grid -->
                        <div class="pt-4 border-t border-gray-100 grid grid-cols-2 gap-3 text-xs">
                            <div class="space-y-0.5">
                                <span class="text-[10px] font-semibold text-gray-400 uppercase">Jumlah Soal</span>
                                <p class="font-extrabold text-gray-800 flex items-center gap-1.5">
                                    <i class="fas fa-list-ol text-brand-500 text-xs"></i>
                                    <span>{{ $quiz->questions_count }} Soal</span>
                                </p>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-[10px] font-semibold text-gray-400 uppercase">Durasi Waktu</span>
                                <p class="font-extrabold text-gray-800 flex items-center gap-1.5">
                                    <i class="fas fa-clock text-amber-500 text-xs"></i>
                                    <span>{{ $quiz->time_limit }} Menit</span>
                                </p>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-[10px] font-semibold text-gray-400 uppercase">Nilai Lulus</span>
                                <p class="font-extrabold text-success-600 flex items-center gap-1.5">
                                    <i class="fas fa-trophy text-xs"></i>
                                    <span>{{ $quiz->passing_score }}%</span>
                                </p>
                            </div>
                            <div class="space-y-0.5">
                                <span class="text-[10px] font-semibold text-gray-400 uppercase">Peserta</span>
                                <p class="font-extrabold text-gray-800 flex items-center gap-1.5">
                                    <i class="fas fa-users text-indigo-500 text-xs"></i>
                                    <span>{{ $quiz->participants_count }} Orang</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Footer Action Column -->
                    <div class="pt-4 border-t border-gray-100 mt-5 flex items-center justify-between gap-2">
                        <a href="{{ route('tenant.owner.quizzes.show', ['quiz' => $quiz->id]) }}" 
                            class="px-3 py-2 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold text-xs transition flex items-center gap-1.5">
                            <i class="fas fa-eye text-xs"></i>
                            <span>Detail</span>
                        </a>

                        <div class="flex items-center gap-1.5">
                            <a href="{{ route('tenant.owner.quizzes.edit', ['quiz' => $quiz->id]) }}" 
                                class="px-3 py-2 rounded-xl bg-brand-50 hover:bg-brand-100 text-brand-600 border border-brand-200 font-bold text-xs transition flex items-center gap-1.5">
                                <i class="fas fa-pen-to-square text-xs"></i>
                                <span>Edit</span>
                            </a>

                            <!-- Icon-Only Button for Delete -->
                            <form method="POST" action="{{ route('tenant.owner.quizzes.destroy', ['quiz' => $quiz->id]) }}" 
                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus kuis ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" title="Hapus Kuis"
                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-error-50 hover:bg-error-100 text-error-600 border border-error-200 transition">
                                    <i class="fas fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        @if($quizzes->hasPages())
            <div class="p-4 rounded-2xl border border-gray-200 bg-white shadow-theme-xs flex flex-col sm:flex-row items-center justify-between gap-4 text-xs">
                <span class="text-gray-500 font-medium">
                    Menampilkan {{ $quizzes->firstItem() }} - {{ $quizzes->lastItem() }} dari {{ $quizzes->total() }} kuis
                </span>
                <div>
                    {{ $quizzes->links() }}
                </div>
            </div>
        @endif
    @endif

</div>
@endsection
