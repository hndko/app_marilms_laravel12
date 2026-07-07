@extends('layouts.superadmin')

@section('title', 'LLM Provider')
@section('page-title', 'LLM Provider')
@section('breadcrumb') <span>LLM Provider</span> @endsection

@section('top-bar-actions')
<button class="btn btn-primary btn-sm" onclick="document.getElementById('add-llm-modal').style.display='flex'">
    <i class="fas fa-plus"></i> Tambah Provider
</button>
@endsection

@section('content')
<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Priority</th>
                        <th>Nama</th>
                        <th>Tipe</th>
                        <th>Model</th>
                        <th>Max Tokens</th>
                        <th>Temp</th>
                        <th>Status</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($providers as $p)
                    <tr>
                        <td>
                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; background: var(--bg-input); border-radius: 8px; font-size: 14px; font-weight: 700; color: var(--primary-light);">
                                {{ $p->priority }}
                            </span>
                        </td>
                        <td style="font-weight: 600;">{{ $p->name }}</td>
                        <td>
                            <span class="badge badge-info">{{ $p->provider_type }}</span>
                        </td>
                        <td><code style="font-size: 12px; background: var(--bg-input); padding: 3px 8px; border-radius: 4px;">{{ $p->model }}</code></td>
                        <td>{{ number_format($p->max_tokens) }}</td>
                        <td>{{ $p->temperature }}</td>
                        <td>
                            @if($p->status === 'active')
                                <span class="badge badge-success">Aktif</span>
                            @elseif($p->status === 'fallback')
                                <span class="badge badge-warning">Fallback</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td style="text-align: right;">
                            <div style="display: flex; gap: 6px; justify-content: flex-end;">
                                <form method="POST" action="{{ route('superadmin.llm.test', $p) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" class="btn btn-ghost btn-xs" title="Test Connection">
                                        <i class="fas fa-plug"></i>
                                    </button>
                                </form>
                                <button class="btn btn-ghost btn-xs" title="Edit" onclick="editLlm({{ json_encode($p) }})">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" action="{{ route('superadmin.llm.destroy', $p) }}" style="display: inline;" onsubmit="return confirm('Hapus provider ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-xs" title="Hapus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="fas fa-brain"></i>
                                <h3>Belum ada LLM Provider</h3>
                                <p>Tambahkan provider LLM untuk mengaktifkan fitur AI quiz generation.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add LLM Modal -->
<div id="add-llm-modal" class="modal-backdrop" style="display: none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="modal" style="max-width: 600px;" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>Tambah LLM Provider</h3>
            <button class="btn btn-ghost btn-xs" onclick="this.closest('.modal-backdrop').style.display='none'"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('superadmin.llm.store') }}">
            @csrf
            @include('superadmin.llm._form')
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost btn-sm" onclick="this.closest('.modal-backdrop').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit LLM Modal -->
<div id="edit-llm-modal" class="modal-backdrop" style="display: none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="modal" style="max-width: 600px;" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>Edit LLM Provider</h3>
            <button class="btn btn-ghost btn-xs" onclick="this.closest('.modal-backdrop').style.display='none'"><i class="fas fa-times"></i></button>
        </div>
        <form id="edit-llm-form" method="POST">
            @csrf @method('PUT')
            @include('superadmin.llm._form', ['isEdit' => true])
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost btn-sm" onclick="this.closest('.modal-backdrop').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Perbarui</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
function editLlm(p) {
    const form = document.getElementById('edit-llm-form');
    form.action = "{{ url('superadmin/llm') }}/" + p.id;
    const prefix = 'edit-';
    ['name', 'base_url', 'model', 'max_tokens', 'temperature', 'priority'].forEach(f => {
        const el = document.getElementById(prefix + f);
        if (el) el.value = p[f];
    });
    document.getElementById(prefix + 'provider_type').value = p.provider_type;
    document.getElementById(prefix + 'status').value = p.status;
    document.getElementById('edit-llm-modal').style.display = 'flex';
}
</script>
@endsection
