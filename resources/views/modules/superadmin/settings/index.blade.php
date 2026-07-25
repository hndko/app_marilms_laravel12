@extends('layouts.app-backend')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')
@section('breadcrumb') <span>Pengaturan</span> @endsection

@section('content')
<form method="POST" action="{{ route('superadmin.settings.update') }}">
    @csrf @method('PUT')

    @foreach($settings as $group => $items)
    <div class="card" style="margin-bottom: 24px;">
        <div class="card-header">
            <h3>
                @switch($group)
                    @case('token') <i class="fas fa-coins" style="color: var(--warning); margin-right: 8px;"></i>Token @break
                    @case('timer') <i class="fas fa-clock" style="color: var(--accent); margin-right: 8px;"></i>Timer @break
                    @case('general') <i class="fas fa-cog" style="color: var(--text-muted); margin-right: 8px;"></i>Umum @break
                    @default <i class="fas fa-sliders-h" style="margin-right: 8px;"></i>{{ ucfirst($group) }}
                @endswitch
            </h3>
        </div>
        <div class="card-body">
            @foreach($items as $setting)
            <div style="display: flex; align-items: flex-start; gap: 20px; padding: 16px 0; {{ !$loop->last ? 'border-bottom: 1px solid var(--border);' : '' }}">
                <div style="flex: 1;">
                    <div style="font-weight: 600; font-size: 14px; color: var(--text-primary); margin-bottom: 4px;">
                        {{ $setting->description ?? $setting->key }}
                    </div>
                    <div style="font-size: 12px; color: var(--text-muted);">
                        <code>{{ $setting->key }}</code> — {{ $setting->type }}
                    </div>
                </div>
                <div style="width: 260px; flex-shrink: 0;">
                    @if($setting->type === 'boolean')
                        <select name="settings[{{ $setting->key }}][value]" class="form-select">
                            <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Ya (Aktif)</option>
                            <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>Tidak (Nonaktif)</option>
                        </select>
                    @elseif($setting->type === 'integer')
                        <input type="number" name="settings[{{ $setting->key }}][value]" class="form-input" value="{{ $setting->value }}">
                    @else
                        <input type="text" name="settings[{{ $setting->key }}][value]" class="form-input" value="{{ $setting->value }}">
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    <div style="position: sticky; bottom: 0; background: var(--bg-body); padding: 16px 0; border-top: 1px solid var(--border);">
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save"></i> Simpan Semua Pengaturan
        </button>
    </div>
</form>
@endsection
