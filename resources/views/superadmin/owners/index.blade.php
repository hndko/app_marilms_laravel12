@extends('layouts.superadmin')

@section('title', 'Manajemen Owner')
@section('page-title', 'Manajemen Owner')
@section('breadcrumb') <span>Manajemen Owner</span> @endsection

@section('top-bar-actions')
<a href="{{ route('superadmin.owners.index', ['status' => request('status'), 'type' => request('type')]) }}" class="btn btn-ghost btn-sm">
    <i class="fas fa-sync-alt"></i> Refresh
</a>
@endsection

@section('content')
<!-- Filter Bar -->
<div class="filter-bar">
    <div class="search-input">
        <i class="fas fa-search"></i>
        <form method="GET" action="{{ route('superadmin.owners.index') }}" style="width: 100%;">
            <input type="text" name="search" placeholder="Cari nama, email, atau organisasi..." value="{{ request('search') }}"
                onchange="this.form.submit()">
            @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
            @if(request('type'))<input type="hidden" name="type" value="{{ request('type') }}">@endif
        </form>
    </div>
    <a href="{{ route('superadmin.owners.index', ['status' => 'active']) }}"
       class="btn btn-sm {{ request('status') === 'active' ? 'btn-primary' : 'btn-ghost' }}">
        <i class="fas fa-check-circle"></i> Aktif
    </a>
    <a href="{{ route('superadmin.owners.index', ['status' => 'inactive']) }}"
       class="btn btn-sm {{ request('status') === 'inactive' ? 'btn-danger' : 'btn-ghost' }}">
        <i class="fas fa-ban"></i> Nonaktif
    </a>
    <a href="{{ route('superadmin.owners.index') }}" class="btn btn-ghost btn-sm">
        <i class="fas fa-times"></i> Reset
    </a>
</div>

<!-- Owners Table -->
<div class="card">
    <div class="card-header">
        <h3>Daftar Owner ({{ $owners->total() }})</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Owner</th>
                        <th>Organisasi</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Tipe</th>
                        <th>Token</th>
                        <th>Terdaftar</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($owners as $owner)
                    <tr>
                        <td>
                            <div style="display: flex; align-items: center; gap: 10px;">
                                <div style="width: 36px; height: 36px; border-radius: 50%; background: linear-gradient(135deg, #6366f1, #06b6d4); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; color: white; flex-shrink: 0;">
                                    {{ strtoupper(substr($owner->name, 0, 2)) }}
                                </div>
                                <div>
                                    <div style="font-weight: 600; font-size: 14px;">{{ $owner->name }}</div>
                                    <div style="font-size: 12px; color: var(--text-muted);">{{ $owner->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $owner->organization_name }}</td>
                        <td><code style="background: var(--bg-input); padding: 2px 8px; border-radius: 4px; font-size: 12px;">{{ $owner->slug }}</code></td>
                        <td>
                            @if($owner->status === 'active')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            @if($owner->tokenBalance?->is_unlimited)
                                <span class="badge badge-primary"><i class="fas fa-infinity"></i> Unlimited</span>
                            @else
                                <span class="badge badge-warning">Regular</span>
                            @endif
                        </td>
                        <td>
                            @if($owner->tokenBalance?->is_unlimited)
                                <span style="color: var(--primary-light); font-weight: 600;">∞</span>
                            @else
                                <span style="font-weight: 600;">{{ number_format($owner->tokenBalance?->balance ?? 0) }}</span>
                            @endif
                        </td>
                        <td style="font-size: 13px; color: var(--text-muted);">
                            {{ $owner->created_at->format('d M Y') }}
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                <a href="{{ route('superadmin.owners.show', $owner) }}" class="btn btn-ghost btn-xs" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('superadmin.owners.toggle-unlimited', $owner) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-xs {{ $owner->tokenBalance?->is_unlimited ? 'btn-warning' : 'btn-ghost' }}" title="Toggle Unlimited">
                                        <i class="fas fa-infinity"></i>
                                    </button>
                                </form>
                                <button class="btn btn-ghost btn-xs" title="Top-up Token"
                                    onclick="document.getElementById('topup-modal-{{ $owner->id }}').style.display='flex'">
                                    <i class="fas fa-plus-circle"></i>
                                </button>
                                <form method="POST" action="{{ route('superadmin.owners.impersonate', $owner) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-ghost btn-xs" title="Login As">
                                        <i class="fas fa-sign-in-alt"></i>
                                    </button>
                                </form>
                            </div>

                            <!-- Top-up Modal -->
                            <div id="topup-modal-{{ $owner->id }}" class="modal-backdrop" style="display: none;" onclick="if(event.target===this)this.style.display='none'">
                                <div class="modal" onclick="event.stopPropagation()">
                                    <div class="modal-header">
                                        <h3>Top-up Token — {{ $owner->name }}</h3>
                                        <button class="btn btn-ghost btn-xs" onclick="this.closest('.modal-backdrop').style.display='none'">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ route('superadmin.owners.topup', $owner) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <p style="color: var(--text-muted); font-size: 13px; margin-bottom: 16px;">
                                                Saldo saat ini: <strong style="color: var(--text-primary);">{{ number_format($owner->tokenBalance?->balance ?? 0) }} token</strong>
                                            </p>
                                            <div class="form-group">
                                                <label class="form-label">Jumlah Token</label>
                                                <input type="number" name="amount" class="form-input" min="1" max="100000" required placeholder="100">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Catatan (opsional)</label>
                                                <input type="text" name="note" class="form-input" placeholder="Top-up manual">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-ghost btn-sm" onclick="this.closest('.modal-backdrop').style.display='none'">Batal</button>
                                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> Top-up</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <h3>Belum ada Owner</h3>
                                <p>Owner akan muncul di sini setelah mendaftar melalui halaman registrasi.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($owners->hasPages())
    <div class="card-footer">
        {{ $owners->links() }}
    </div>
    @endif
</div>
@endsection
