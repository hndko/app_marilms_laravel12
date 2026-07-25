@extends('layouts.owner')

@section('title', 'Data Peserta')
@section('page-title', 'Manajemen Peserta')

@section('breadcrumb')
    <span>Data Peserta</span>
@endsection

@section('content')
<div style="display: flex; flex-direction: column; gap: 28px;">

    <!-- Banner & Action Buttons -->
    <div class="card" style="background: linear-gradient(135deg, rgba(6,182,212,0.15), rgba(99,102,241,0.15)); border: 1px solid rgba(6,182,212,0.3); padding: 24px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px;">
            <div>
                <span style="font-size: 12px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--accent-light);">Peserta & Siswa</span>
                <h2 style="font-size: 26px; font-weight: 800; color: var(--text-white); margin-top: 4px;">
                    Kelola Peserta Ujian Anda
                </h2>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 4px;">
                    Tambahkan peserta secara manual, impor massal melalui file CSV, atau pantau aktivitas pengerjaan kuis.
                </p>
            </div>
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <button type="button" onclick="openImportModal()" class="btn btn-secondary" style="padding: 12px 20px; font-size: 14px;">
                    <i class="fas fa-file-csv" style="color: var(--success);"></i> Impor CSV Massal
                </button>
                <a href="{{ route('tenant.owner.participants.create', ['tenant' => $tenant]) }}" class="btn btn-primary" style="padding: 12px 24px; font-size: 14px; background: linear-gradient(135deg, var(--accent), var(--primary));">
                    <i class="fas fa-user-plus"></i> Tambah Peserta
                </a>
            </div>
        </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="card" style="padding: 20px;">
        <form method="GET" action="{{ route('tenant.owner.participants.index', ['tenant' => $tenant]) }}" style="display: flex; gap: 16px; flex-wrap: wrap; align-items: flex-end;">
            <div style="flex: 1; min-width: 260px;">
                <label class="form-label" style="font-size: 12px;">Cari Peserta</label>
                <div style="position: relative;">
                    <input type="text" name="search" class="form-input" value="{{ request('search') }}" placeholder="Nama peserta, email, atau nomor WhatsApp..." style="padding-left: 36px;">
                    <i class="fas fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: var(--text-muted);"></i>
                </div>
            </div>

            <div style="width: 180px;">
                <label class="form-label" style="font-size: 12px;">Status Akun</label>
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>

            <div>
                <button type="submit" class="btn btn-secondary" style="height: 42px;">
                    <i class="fas fa-filter"></i> Filter
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ route('tenant.owner.participants.index', ['tenant' => $tenant]) }}" class="btn btn-ghost" style="height: 42px;">Reset</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Participants Table -->
    <div class="card">
        <div class="card-header" style="justify-content: space-between;">
            <h3><i class="fas fa-users" style="color: var(--accent); margin-right: 8px;"></i> Daftar Peserta Terdaftar</h3>
            <span class="badge badge-info">{{ $participants->total() }} Peserta</span>
        </div>

        @if($participants->isEmpty())
            <div style="padding: 60px 20px; text-align: center;">
                <div style="width: 72px; height: 72px; border-radius: 20px; background: rgba(6,182,212,0.1); color: var(--accent-light); display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 20px;">
                    <i class="fas fa-user-slash"></i>
                </div>
                <h3 style="font-size: 18px; font-weight: 700; color: var(--text-white);">Belum Ada Peserta Terdaftar</h3>
                <p style="font-size: 13px; color: var(--text-muted); max-width: 400px; margin: 8px auto 24px;">
                    Mulai tambahkan peserta pertama Anda atau lakukan impor massal melalui file CSV untuk memulai pengujian.
                </p>
                <div style="display: flex; gap: 12px; justify-content: center;">
                    <button type="button" onclick="openImportModal()" class="btn btn-secondary"><i class="fas fa-file-csv"></i> Impor CSV</button>
                    <a href="{{ route('tenant.owner.participants.create', ['tenant' => $tenant]) }}" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Manual</a>
                </div>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nama Peserta</th>
                            <th>Kontak & Email</th>
                            <th>Status</th>
                            <th>Total Kuis Dikerjakan</th>
                            <th>Tanggal Daftar</th>
                            <th style="text-align: right;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($participants as $p)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 12px;">
                                        <div style="width: 38px; height: 38px; border-radius: 10px; background: linear-gradient(135deg, var(--primary), var(--accent)); color: white; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 15px;">
                                            {{ strtoupper(substr($p->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('tenant.owner.participants.show', ['tenant' => $tenant, 'participant' => $p->id]) }}" style="font-weight: 700; color: var(--text-white); text-decoration: none;">
                                                {{ $p->name }}
                                            </a>
                                            <div style="font-size: 11px; color: var(--text-muted);">ID: #{{ $p->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: var(--text-white);"><i class="fas fa-envelope" style="color: var(--text-muted); width: 16px;"></i> {{ $p->email }}</div>
                                    @if($p->phone)
                                        <div style="font-size: 12px; color: var(--accent-light); margin-top: 2px;"><i class="fab fa-whatsapp" style="color: var(--success); width: 16px;"></i> {{ $p->phone }}</div>
                                    @else
                                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 2px;">No. WA tidak ada</div>
                                    @endif
                                </td>
                                <td>
                                    @if($p->status === 'active')
                                        <span class="badge badge-success"><i class="fas fa-check-circle"></i> Aktif</span>
                                    @else
                                        <span class="badge badge-danger"><i class="fas fa-ban"></i> Nonaktif</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-secondary" style="font-size: 13px; font-weight: 700;">
                                        <i class="fas fa-clipboard-check" style="color: var(--primary-light); margin-right: 4px;"></i> {{ $p->quiz_attempts_count }} Kali
                                    </span>
                                </td>
                                <td style="font-size: 13px; color: var(--text-muted);">
                                    {{ $p->created_at->format('d M Y') }}
                                </td>
                                <td style="text-align: right;">
                                    <div style="display: flex; justify-content: flex-end; gap: 6px;">
                                        <a href="{{ route('tenant.owner.participants.show', ['tenant' => $tenant, 'participant' => $p->id]) }}" class="btn btn-sm btn-icon btn-secondary" title="Lihat Detail & Riwayat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('tenant.owner.participants.edit', ['tenant' => $tenant, 'participant' => $p->id]) }}" class="btn btn-sm btn-icon btn-primary" title="Edit Peserta">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('tenant.owner.participants.reset-password', ['tenant' => $tenant, 'participant' => $p->id]) }}" onsubmit="return confirm('Reset password peserta ini menjadi password123?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-icon btn-warning" title="Reset Password ke password123">
                                                <i class="fas fa-key"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('tenant.owner.participants.destroy', ['tenant' => $tenant, 'participant' => $p->id]) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus peserta ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-icon btn-ghost" style="color: var(--danger);" title="Hapus Peserta">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($participants->hasPages())
                <div style="padding: 16px 24px; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-size: 13px; color: var(--text-muted);">
                        Menampilkan {{ $participants->firstItem() }} - {{ $participants->lastItem() }} dari {{ $participants->total() }} peserta
                    </span>
                    <div>
                        {{ $participants->links() }}
                    </div>
                </div>
            @endif
        @endif
    </div>

</div>

<!-- CSV Import Modal -->
<div id="import-modal" style="display: none; position: fixed; inset: 0; background: rgba(15,17,23,0.8); backdrop-filter: blur(8px); z-index: 1000; align-items: center; justify-content: center; padding: 20px;">
    <div class="card" style="width: 100%; max-width: 550px; border-color: var(--accent); box-shadow: 0 20px 50px rgba(0,0,0,0.7);">
        <div class="card-header" style="background: linear-gradient(135deg, rgba(6,182,212,0.15), rgba(99,102,241,0.1)); justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-file-csv" style="font-size: 20px; color: var(--success);"></i>
                <h3 style="font-size: 18px; font-weight: 700; color: white;">Impor Peserta Massal (CSV)</h3>
            </div>
            <button type="button" onclick="closeImportModal()" class="btn btn-sm btn-icon btn-ghost"><i class="fas fa-times"></i></button>
        </div>

        <form method="POST" action="{{ route('tenant.owner.participants.import', ['tenant' => $tenant]) }}" enctype="multipart/form-data">
            @csrf
            <div class="card-body">
                <div style="background: rgba(99,102,241,0.08); border-left: 3px solid var(--primary-light); padding: 14px; border-radius: 0 8px 8px 0; margin-bottom: 20px;">
                    <h4 style="font-size: 13px; font-weight: 700; color: var(--primary-light); margin-bottom: 6px;"><i class="fas fa-info-circle"></i> Format File CSV:</h4>
                    <p style="font-size: 12px; color: var(--text-secondary); line-height: 1.5; margin-bottom: 8px;">
                        Siapkan file .csv dengan baris pertama sebagai header. Urutan kolom yang diharapkan adalah:
                    </p>
                    <code style="display: block; background: rgba(0,0,0,0.3); padding: 8px 12px; border-radius: 6px; font-size: 12px; color: var(--accent-light);">
                        Nama Lengkap, Email, Nomor WhatsApp, Password (Opsional)
                    </code>
                    <p style="font-size: 11px; color: var(--text-muted); margin-top: 8px;">
                        * Jika password dikosongkan, sistem otomatis memberikan password default: <strong>password123</strong>
                    </p>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Pilih File CSV (.csv / .txt) <span style="color: var(--danger);">*</span></label>
                    <input type="file" name="file" class="form-input" required accept=".csv,.txt" style="padding: 10px; height: auto;">
                </div>
            </div>

            <div class="card-footer" style="justify-content: flex-end; gap: 12px; background: rgba(0,0,0,0.15);">
                <button type="button" onclick="closeImportModal()" class="btn btn-ghost">Batal</button>
                <button type="submit" class="btn btn-primary" style="background: var(--success);">
                    <i class="fas fa-upload"></i> Mulai Impor Sekarang
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openImportModal() {
    document.getElementById('import-modal').style.display = 'flex';
}
function closeImportModal() {
    document.getElementById('import-modal').style.display = 'none';
}
</script>
@endsection
