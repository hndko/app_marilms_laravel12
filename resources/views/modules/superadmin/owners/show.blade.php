@extends('layouts.app-backend')

@section('title', 'Detail Owner — ' . $owner->name)
@section('page-title', 'Detail Owner')
@section('breadcrumb')
    <a href="{{ route('superadmin.owners.index') }}">Owner</a>
    <i class="fas fa-chevron-right" style="font-size: 10px;"></i>
    <span>{{ $owner->name }}</span>
@endsection

@section('content')
<div class="grid-2" style="margin-bottom: 24px;">
    <!-- Owner Info Card -->
    <div class="card">
        <div class="card-header">
            <h3>Informasi Owner</h3>
            <span class="badge {{ $owner->status === 'active' ? 'badge-success' : 'badge-danger' }}">
                {{ $owner->status === 'active' ? 'Aktif' : 'Nonaktif' }}
            </span>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('superadmin.owners.update', $owner) }}">
                @csrf @method('PUT')
                <div class="form-group">
                    <label class="form-label">Nama</label>
                    <input type="text" name="name" class="form-input" value="{{ $owner->name }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-input" value="{{ $owner->email }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Organisasi</label>
                    <input type="text" name="organization_name" class="form-input" value="{{ $owner->organization_name }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Telepon</label>
                    <input type="text" name="phone" class="form-input" value="{{ $owner->phone }}">
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ $owner->status === 'active' ? 'selected' : '' }}>Aktif</option>
                        <option value="inactive" {{ $owner->status === 'inactive' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan</button>
            </form>
        </div>
    </div>

    <!-- Token & Actions Card -->
    <div>
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h3>Token</h3>
                @if($owner->tokenBalance?->is_unlimited)
                    <span class="badge badge-primary"><i class="fas fa-infinity"></i> Unlimited</span>
                @endif
            </div>
            <div class="card-body">
                <div style="text-align: center; margin-bottom: 20px;">
                    <div style="font-size: 48px; font-weight: 800; color: var(--text-white); letter-spacing: -2px;">
                        @if($owner->tokenBalance?->is_unlimited)
                            <span style="color: var(--primary-light);">∞</span>
                        @else
                            {{ number_format($owner->tokenBalance?->balance ?? 0) }}
                        @endif
                    </div>
                    <div style="font-size: 13px; color: var(--text-muted);">Saldo Token</div>
                </div>

                <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                    <form method="POST" action="{{ route('superadmin.owners.toggle-unlimited', $owner) }}" style="flex: 1;">
                        @csrf
                        <button type="submit" class="btn {{ $owner->tokenBalance?->is_unlimited ? 'btn-warning' : 'btn-ghost' }} btn-sm" style="width: 100%;">
                            <i class="fas fa-infinity"></i> {{ $owner->tokenBalance?->is_unlimited ? 'Set Regular' : 'Set Unlimited' }}
                        </button>
                    </form>
                    <button class="btn btn-primary btn-sm" style="flex: 1;" onclick="document.getElementById('topup-modal').style.display='flex'">
                        <i class="fas fa-plus"></i> Top-up
                    </button>
                </div>
            </div>
        </div>

        <!-- Reset Password -->
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header"><h3>Reset Password</h3></div>
            <div class="card-body">
                <form method="POST" action="{{ route('superadmin.owners.reset-password', $owner) }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password" class="form-input" required minlength="8">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-input" required>
                    </div>
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-key"></i> Reset Password</button>
                </form>
            </div>
        </div>

        <!-- Quick Info -->
        <div class="card">
            <div class="card-body" style="padding: 16px 24px;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="color: var(--text-muted); font-size: 13px;">Slug</span>
                    <code style="font-size: 13px;">{{ $owner->slug }}</code>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                    <span style="color: var(--text-muted); font-size: 13px;">Terdaftar</span>
                    <span style="font-size: 13px;">{{ $owner->created_at->format('d M Y, H:i') }}</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted); font-size: 13px;">Tenant</span>
                    <span style="font-size: 13px;">{{ $owner->tenant?->name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Transaction History -->
<div class="card">
    <div class="card-header">
        <h3>Riwayat Token (20 terakhir)</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Sumber</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                    <tr>
                        <td style="font-size: 13px; color: var(--text-muted);">{{ $tx->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            @if($tx->type === 'credit')
                                <span class="badge badge-success">+ Credit</span>
                            @else
                                <span class="badge badge-danger">- Debit</span>
                            @endif
                        </td>
                        <td style="font-weight: 600;">{{ number_format($tx->amount) }}</td>
                        <td><span class="badge badge-info">{{ $tx->source }}</span></td>
                        <td style="font-size: 13px; color: var(--text-secondary);">{{ $tx->note ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                            Belum ada transaksi token
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Top-up Modal -->
<div id="topup-modal" class="modal-backdrop" style="display: none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="modal" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>Top-up Token</h3>
            <button class="btn btn-ghost btn-xs" onclick="this.closest('.modal-backdrop').style.display='none'"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('superadmin.owners.topup', $owner) }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Jumlah Token</label>
                    <input type="number" name="amount" class="form-input" min="1" max="100000" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <input type="text" name="note" class="form-input" placeholder="Top-up manual oleh SuperAdmin">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost btn-sm" onclick="this.closest('.modal-backdrop').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Top-up</button>
            </div>
        </form>
    </div>
</div>
@endsection
