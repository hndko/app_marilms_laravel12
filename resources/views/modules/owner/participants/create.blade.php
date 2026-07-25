@extends('layouts.owner')

@section('title', 'Tambah Peserta Baru')
@section('page-title', 'Tambah Peserta')

@section('breadcrumb')
    <a href="{{ route('tenant.owner.participants.index', ['tenant' => $tenant]) }}">Data Peserta</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Tambah Peserta</span>
@endsection

@section('content')
<div style="max-width: 650px; margin: 0 auto;">
    <div class="card" style="border-color: rgba(6,182,212,0.4); box-shadow: 0 0 30px rgba(6,182,212,0.1);">
        <div class="card-header" style="background: linear-gradient(135deg, rgba(6,182,212,0.15), rgba(99,102,241,0.1));">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, var(--accent), var(--primary)); display: flex; align-items: center; justify-content: center; font-size: 20px; color: white;">
                    <i class="fas fa-user-plus"></i>
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--text-white);">Registrasi Peserta Baru</h3>
                    <p style="font-size: 13px; color: var(--text-muted);">Daftarkan siswa atau peserta ujian ke dalam tenant Anda secara manual.</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('tenant.owner.participants.store', ['tenant' => $tenant]) }}">
            @csrf
            <div class="card-body">
                
                <div class="form-group">
                    <label class="form-label">Nama Lengkap Peserta <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" class="form-input" required placeholder="Contoh: Ahmad Zaky / Siti Aminah" value="{{ old('name') }}" style="font-size: 15px; padding: 12px;">
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Alamat Email <span style="color: var(--danger);">*</span></label>
                        <input type="email" name="email" class="form-input" required placeholder="siswa@example.com" value="{{ old('email') }}">
                        <p style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Digunakan untuk login peserta</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone" class="form-input" placeholder="081234567890" value="{{ old('phone') }}">
                        <p style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Untuk notifikasi hasil & undangan</p>
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Password Awal (Opsional)</label>
                        <input type="password" name="password" class="form-input" placeholder="Default: password123">
                        <p style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Minimal 6 karakter</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status Akun <span style="color: var(--danger);">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

                <div style="background: rgba(99,102,241,0.08); border: 1px solid rgba(99,102,241,0.2); border-radius: 12px; padding: 14px; margin-top: 10px;">
                    <label style="display: flex; align-items: center; gap: 12px; cursor: pointer;">
                        <input type="checkbox" name="send_invite" value="1" {{ old('send_invite', true) ? 'checked' : '' }} style="width: 18px; height: 18px; accent-color: var(--primary);">
                        <div>
                            <span style="font-size: 13px; font-weight: 700; color: var(--text-white); display: block;">Kirim Notifikasi Undangan (Email & WA)</span>
                            <span style="font-size: 11px; color: var(--text-muted);">Sistem akan otomatis mengirimkan pesan sambutan beserta kredensial login ke kontak peserta.</span>
                        </div>
                    </label>
                </div>

            </div>

            <div class="card-footer" style="justify-content: flex-end; gap: 12px; background: rgba(0,0,0,0.15);">
                <a href="{{ route('tenant.owner.participants.index', ['tenant' => $tenant]) }}" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-size: 15px; background: linear-gradient(135deg, var(--accent), var(--primary));">
                    <i class="fas fa-save"></i> Simpan Peserta Baru
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
