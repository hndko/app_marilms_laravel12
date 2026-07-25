@extends('layouts.app-backend')

@section('title', 'Log & Audit')
@section('page-title', 'Log & Audit')
@section('breadcrumb') <span>Log & Audit</span> @endsection

@section('content')
<!-- Tabs -->
<div class="tabs">
    <button class="tab-link {{ $tab === 'activity' ? 'active' : '' }}" onclick="showTab('activity', this)">
        <i class="fas fa-scroll" style="margin-right: 6px;"></i>Activity Log
    </button>
    <button class="tab-link {{ $tab === 'token' ? 'active' : '' }}" onclick="showTab('token', this)">
        <i class="fas fa-coins" style="margin-right: 6px;"></i>Token Transactions
    </button>
</div>

<!-- Activity Tab -->
<div id="tab-activity" class="tab-content" style="{{ $tab !== 'activity' ? 'display: none;' : '' }}">
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Aksi</th>
                            <th>Deskripsi</th>
                            <th>User Type</th>
                            <th>IP Address</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activityLogs as $log)
                        <tr>
                            <td style="font-size: 13px; color: var(--text-muted); white-space: nowrap;">
                                {{ $log->created_at->format('d M Y, H:i:s') }}
                            </td>
                            <td><span class="badge badge-primary">{{ $log->action }}</span></td>
                            <td>{{ $log->description }}</td>
                            <td style="font-size: 13px; color: var(--text-secondary);">{{ $log->user_type ?? '-' }}</td>
                            <td style="font-family: monospace; font-size: 13px; color: var(--text-muted);">{{ $log->ip_address }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                Belum ada log aktivitas
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($activityLogs->hasPages())
        <div class="card-footer">
            {{ $activityLogs->appends(['tab' => 'activity'])->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Token Transactions Tab -->
<div id="tab-token" class="tab-content" style="{{ $tab !== 'token' ? 'display: none;' : '' }}">
    <div class="card">
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Owner</th>
                            <th>Tipe</th>
                            <th>Jumlah</th>
                            <th>Sumber</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tokenLogs as $tx)
                        <tr>
                            <td style="font-size: 13px; color: var(--text-muted); white-space: nowrap;">
                                {{ $tx->created_at->format('d M Y, H:i:s') }}
                            </td>
                            <td>
                                @if($tx->owner)
                                    <a href="{{ route('superadmin.owners.show', $tx->owner) }}" style="color: var(--primary-light); text-decoration: none; font-weight: 500;">
                                        {{ $tx->owner->name }}
                                    </a>
                                @else
                                    <span style="color: var(--text-muted);">—</span>
                                @endif
                            </td>
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
                            <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                Belum ada transaksi token
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($tokenLogs->hasPages())
        <div class="card-footer">
            {{ $tokenLogs->appends(['tab' => 'token'])->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
function showTab(name, btn) {
    document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.tab-link').forEach(el => el.classList.remove('active'));
    document.getElementById('tab-' + name).style.display = 'block';
    btn.classList.add('active');
}
</script>
@endsection
