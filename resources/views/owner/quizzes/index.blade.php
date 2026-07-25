@extends('layouts.owner')

@section('title', 'Daftar Kuis')
@section('page-title', 'Manajemen Kuis AI')

@section('breadcrumb')
    <span>Daftar Kuis</span>
@endsection

@section('content')
<div style="display: flex; flex-direction: column; gap: 28px;">

    <!-- Top Action & Banner -->
    <div class="card" style="background: linear-gradient(135deg, rgba(99,102,241,0.15), rgba(6,182,212,0.15)); border: 1px solid rgba(99,102,241,0.3); padding: 24px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div>
                <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--accent-light);">Bank Soal & Evaluasi</span>
                <h2 style="font-size: 26px; font-weight: 800; color: var(--text-white); margin-top: 4px;">
                    Kelola Paket Kuis Anda
                </h2>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
                    Buat kuis baru secara otomatis menggunakan AI dari materi pengajaran atau bangun secara manual.
                </p>
            </div>
            <div style="display: flex; gap: 12px;">
                <a href="{{ route('tenant.owner.quizzes.create', ['tenant' => $tenant]) }}" class="btn btn-primary" style="padding: 12px 24px; font-size: 14px;">
                    <i class="fas fa-wand-magic-sparkles"></i> Buat Kuis Baru (AI / Manual)
                </a>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="card" style="padding: 20px;">
        <form method="GET" action="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenant]) }}" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 240px;">
                <label class="form-label" style="font-size: 12px;">Cari Kuis</label>
                <div style="position: relative;">
                    <input type="text" name="search" class="form-input" value="{{ request('search') }}" placeholder="Judul kuis, deskripsi, atau materi..." style="padding-left: 36px;">
                    <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                </div>
            </div>

            <div style="width: 180px;">
                <label class="form-label" style="font-size: 12px;">Kategori</label>
                <select name="category" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div style="width: 160px;">
                <label class="form-label" style="font-size: 12px;">Status</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="archived" {{ request('status') === 'archived' ? 'selected' : '' }}>Diarsipkan</option>
                </select>
            </div>

            <div>
                <button type="submit" class="btn btn-secondary" style="height: 42px;">
                    <i class="fas fa-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'category', 'status']))
                    <a href="{{ route('tenant.owner.quizzes.index', ['tenant' => $tenant]) }}" class="btn btn-ghost" style="height: 42px;">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Quiz Cards Grid -->
    @if($quizzes->isEmpty())
        <div class="card" style="padding: 60px 20px; text-align: center;">
            <div style="width: 72px; height: 72px; border-radius: 20px; background: rgba(99,102,241,0.1); color: var(--primary-light); display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 20px;">
                <i class="fas fa-folder-open"></i>
            </div>
            <h3 style="font-size: 18px; font-weight: 700; color: var(--text-white);">Belum Ada Kuis Ditemukan</h3>
            <p style="font-size: 13px; color: var(--text-muted); max-width: 400px; margin: 8px auto 24px;">
                Mulai buat kuis pertama Anda dengan generator soal otomatis berteknologi AI.
            </p>
            <a href="{{ route('tenant.owner.quizzes.create', ['tenant' => $tenant]) }}" class="btn btn-primary">
                <i class="fas fa-magic"></i> Generate Kuis Sekarang
            </a>
        </div>
    @else
        <div class="grid-3">
            @foreach($quizzes as $quiz)
                <div class="card" style="display: flex; flex-direction: column; justify-content: space-between; position: relative; transition: all 0.2s; border-color: {{ $quiz->status === 'active' ? 'rgba(16,185,129,0.3)' : 'var(--border)' }};">
                    
                    <div class="card-body" style="padding: 24px;">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 12px; margin-bottom: 12px;">
                            <span class="badge badge-info" style="font-size: 11px;">{{ $quiz->category ?: 'Umum' }}</span>
                            @if($quiz->status === 'active')
                                <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>
                            @elseif($quiz->status === 'draft')
                                <span class="badge badge-warning"><i class="fas fa-edit"></i> Draft</span>
                            @else
                                <span class="badge badge-danger"><i class="fas fa-archive"></i> Arsip</span>
                            @endif
                        </div>

                        <h3 style="font-size: 18px; font-weight: 700; color: var(--text-white); line-height: 1.4; margin-bottom: 8px;">
                            <a href="{{ route('tenant.owner.quizzes.show', ['quiz' => $quiz->id]) }}" style="color: inherit; text-decoration: none;">
                                {{ $quiz->title }}
                            </a>
                        </h3>

                        <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; height: 38px;">
                            {{ $quiz->description ?: 'Tidak ada deskripsi.' }}
                        </p>

                        <div style="margin-top: 20px; padding-top: 16px; border-top: 1px solid var(--border); display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 12px;">
                            <div>
                                <span style="color: var(--text-muted); display: block;">Jumlah Soal:</span>
                                <span style="font-weight: 700; color: var(--text-white); font-size: 14px;">
                                    <i class="fas fa-list-ol" style="color: var(--accent); margin-right: 4px;"></i> {{ $quiz->questions_count }} Soal
                                </span>
                            </div>
                            <div>
                                <span style="color: var(--text-muted); display: block;">Durasi:</span>
                                <span style="font-weight: 700; color: var(--text-white); font-size: 14px;">
                                    <i class="fas fa-clock" style="color: var(--warning); margin-right: 4px;"></i> {{ $quiz->time_limit }} Menit
                                </span>
                            </div>
                            <div>
                                <span style="color: var(--text-muted); display: block;">Nilai Lulus:</span>
                                <span style="font-weight: 700; color: var(--success); font-size: 14px;">
                                    <i class="fas fa-star" style="margin-right: 4px;"></i> {{ $quiz->passing_score }}%
                                </span>
                            </div>
                            <div>
                                <span style="color: var(--text-muted); display: block;">Peserta:</span>
                                <span style="font-weight: 700; color: var(--primary-light); font-size: 14px;">
                                    <i class="fas fa-users" style="margin-right: 4px;"></i> {{ $quiz->participants_count }} Orang
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer" style="background: rgba(0,0,0,0.15); padding: 12px 20px; justify-content: space-between;">
                        <a href="{{ route('tenant.owner.quizzes.show', ['quiz' => $quiz->id]) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-eye"></i> Detail
                        </a>
                        <div style="display: flex; gap: 8px;">
                            <a href="{{ route('tenant.owner.quizzes.edit', ['quiz' => $quiz->id]) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <form method="POST" action="{{ route('tenant.owner.quizzes.destroy', ['quiz' => $quiz->id]) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kuis ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-icon btn-ghost" style="color: var(--danger);"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        @if($quizzes->hasPages())
            <div class="card" style="padding: 16px 24px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 13px; color: var(--text-muted);">
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
