@extends('layouts.superadmin')

@section('title', 'Paket Token')
@section('page-title', 'Paket Token')
@section('breadcrumb') <span>Paket Token</span> @endsection

@section('top-bar-actions')
<button class="btn btn-primary btn-sm" onclick="document.getElementById('add-package-modal').style.display='flex'">
    <i class="fas fa-plus"></i> Tambah Paket
</button>
@endsection

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
    @foreach($packages as $pkg)
    <div class="card" style="position: relative; {{ !$pkg->is_active ? 'opacity: 0.6;' : '' }}">
        @if(!$pkg->is_active)
            <div style="position: absolute; top: 12px; right: 12px;">
                <span class="badge badge-danger">Nonaktif</span>
            </div>
        @endif
        <div class="card-body" style="text-align: center; padding: 32px 24px;">
            <div style="width: 60px; height: 60px; margin: 0 auto 16px; background: linear-gradient(135deg, rgba(99,102,241,0.2), rgba(6,182,212,0.2)); border-radius: 16px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-coins" style="font-size: 24px; color: var(--primary-light);"></i>
            </div>
            <h3 style="font-size: 20px; font-weight: 800; color: var(--text-white); margin-bottom: 4px;">{{ $pkg->name }}</h3>
            <p style="font-size: 13px; color: var(--text-muted); margin-bottom: 20px;">{{ $pkg->description ?? '' }}</p>

            <div style="background: var(--bg-input); border-radius: 12px; padding: 16px; margin-bottom: 20px;">
                <div style="font-size: 32px; font-weight: 800; color: var(--accent-light); letter-spacing: -1px;">
                    {{ number_format($pkg->token_amount) }}
                </div>
                <div style="font-size: 12px; color: var(--text-muted); margin-bottom: 8px;">token</div>
                <div style="font-size: 20px; font-weight: 700; color: var(--text-white);">
                    Rp {{ number_format($pkg->price_idr, 0, ',', '.') }}
                </div>
                <div style="font-size: 12px; color: var(--text-muted);">
                    Rp {{ number_format($pkg->price_idr / $pkg->token_amount, 0, ',', '.') }} / token
                </div>
            </div>

            <div style="display: flex; gap: 8px;">
                <button class="btn btn-ghost btn-sm" style="flex: 1;"
                    onclick="editPackage({{ json_encode($pkg) }})">
                    <i class="fas fa-edit"></i> Edit
                </button>
                <form method="POST" action="{{ route('superadmin.token-packages.destroy', $pkg) }}" style="flex: 1;"
                    onsubmit="return confirm('Hapus paket {{ $pkg->name }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" style="width: 100%;">
                        <i class="fas fa-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach

    @if($packages->isEmpty())
    <div class="card" style="grid-column: 1 / -1;">
        <div class="empty-state">
            <i class="fas fa-coins"></i>
            <h3>Belum ada Paket Token</h3>
            <p>Buat paket token agar Owner dapat membeli token.</p>
            <button class="btn btn-primary" onclick="document.getElementById('add-package-modal').style.display='flex'">
                <i class="fas fa-plus"></i> Tambah Paket
            </button>
        </div>
    </div>
    @endif
</div>

<!-- Add Package Modal -->
<div id="add-package-modal" class="modal-backdrop" style="display: none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="modal" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>Tambah Paket Token</h3>
            <button class="btn btn-ghost btn-xs" onclick="this.closest('.modal-backdrop').style.display='none'"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="{{ route('superadmin.token-packages.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Paket</label>
                    <input type="text" name="name" class="form-input" required placeholder="Contoh: Starter">
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Jumlah Token</label>
                        <input type="number" name="token_amount" class="form-input" required min="1" placeholder="100">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga (IDR)</label>
                        <input type="number" name="price_idr" class="form-input" required min="0" placeholder="25000">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" class="form-textarea" rows="2" placeholder="Deskripsi singkat..."></textarea>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="sort_order" class="form-input" value="0" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-ghost btn-sm" onclick="this.closest('.modal-backdrop').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Package Modal -->
<div id="edit-package-modal" class="modal-backdrop" style="display: none;" onclick="if(event.target===this)this.style.display='none'">
    <div class="modal" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3>Edit Paket Token</h3>
            <button class="btn btn-ghost btn-xs" onclick="this.closest('.modal-backdrop').style.display='none'"><i class="fas fa-times"></i></button>
        </div>
        <form id="edit-package-form" method="POST">
            @csrf @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Paket</label>
                    <input type="text" name="name" id="edit-name" class="form-input" required>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Jumlah Token</label>
                        <input type="number" name="token_amount" id="edit-token_amount" class="form-input" required min="1">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Harga (IDR)</label>
                        <input type="number" name="price_idr" id="edit-price_idr" class="form-input" required min="0">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" id="edit-description" class="form-textarea" rows="2"></textarea>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label class="form-label">Urutan</label>
                        <input type="number" name="sort_order" id="edit-sort_order" class="form-input" min="0">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="is_active" id="edit-is_active" class="form-select">
                            <option value="1">Aktif</option>
                            <option value="0">Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>
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
function editPackage(pkg) {
    document.getElementById('edit-package-form').action = "{{ url('superadmin/token-packages') }}/" + pkg.id;
    document.getElementById('edit-name').value = pkg.name;
    document.getElementById('edit-token_amount').value = pkg.token_amount;
    document.getElementById('edit-price_idr').value = pkg.price_idr;
    document.getElementById('edit-description').value = pkg.description || '';
    document.getElementById('edit-sort_order').value = pkg.sort_order || 0;
    document.getElementById('edit-is_active').value = pkg.is_active ? '1' : '0';
    document.getElementById('edit-package-modal').style.display = 'flex';
}
</script>
@endsection
