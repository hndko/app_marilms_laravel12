@extends('layouts.app-backend')

@section('title', 'Edit Peserta: ' . $participant->name)
@section('page-title', 'Edit Data Peserta')

@section('breadcrumb')
    <a href="{{ route('tenant.owner.participants.index', ['tenant' => $tenant]) }}">Data Peserta</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <a href="{{ route('tenant.owner.participants.show', ['tenant' => $tenant, 'participant' => $participant->id]) }}">{{ Str::limit($participant->name, 20) }}</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>Edit</span>
@endsection

@section('content')
<div style="max-width: 650px; margin: 0 auto;">
    <div class="card" style="border-color: rgba(99,102,241,0.4); box-shadow: 0 0 30px rgba(99,102,241,0.1);">
        <div class="card-header" style="background: linear-gradient(135deg, rgba(99,102,241,0.15), rgba(6,182,212,0.1));">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 44px; height: 44px; border-radius: 12px; background: linear-gradient(135deg, var(--primary), var(--accent)); display: flex; align-items: center; justify-content: center; font-size: 20px; color: white;">
                    <i class="fas fa-user-edit"></i>
                </div>
                <div>
                    <h3 style="font-size: 18px; font-weight: 700; color: var(--text-white);">Edit Informasi Peserta</h3>
                    <p style="font-size: 13px; color: var(--text-muted);">Perbarui informasi pribadi, kontak, atau status akun peserta ujian.</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('tenant.owner.participants.update', ['tenant' => $tenant, 'participant' => $participant->id]) }}">
            @csrf
            @method('PUT')
            
            <div class="card-body">
                
                <div class="form-group">
                    <label class="form-label">Nama Lengkap Peserta <span style="color: var(--danger);">*</span></label>
                    <input type="text" name="name" class="form-input" required value="{{ old('name', $participant->name) }}" style="font-size: 15px; padding: 12px;">
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Alamat Email <span style="color: var(--danger);">*</span></label>
                        <input type="email" name="email" class="form-input" required value="{{ old('email', $participant->email) }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor WhatsApp / HP</label>
                        <input type="text" name="phone" class="form-input" value="{{ old('phone', $participant->phone) }}">
                    </div>
                </div>

                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Ganti Password (Opsional)</label>
                        <input type="password" name="password" class="form-input" placeholder="Kosongkan jika tidak diganti">
                        <p style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">Minimal 6 karakter</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status Akun <span style="color: var(--danger);">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $participant->status) === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status', $participant->status) === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                    </div>
                </div>

            </div>

            <div class="card-footer" style="justify-content: flex-end; gap: 12px; background: rgba(0,0,0,0.15);">
                <a href="{{ route('tenant.owner.participants.show', ['tenant' => $tenant, 'participant' => $participant->id]) }}" class="btn btn-ghost">Batal</a>
                <button type="submit" class="btn btn-primary" style="padding: 12px 28px; font-size: 15px;">
                    <i class="fas fa-save"></i> Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
