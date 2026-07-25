<div class="modal-body">
    <div class="form-group">
        <label class="form-label">Nama Provider</label>
        <input type="text" name="name" id="{{ isset($isEdit) ? 'edit-' : '' }}name" class="form-input" required placeholder="OpenRouter (Default)">
    </div>
    <div class="grid-2">
        <div class="form-group">
            <label class="form-label">Tipe Provider</label>
            <select name="provider_type" id="{{ isset($isEdit) ? 'edit-' : '' }}provider_type" class="form-select" required>
                <option value="openrouter">OpenRouter</option>
                <option value="deepseek">DeepSeek</option>
                <option value="custom">Custom</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Status</label>
            <select name="status" id="{{ isset($isEdit) ? 'edit-' : '' }}status" class="form-select" required>
                <option value="active">Active</option>
                <option value="fallback">Fallback</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label class="form-label">Base URL</label>
        <input type="url" name="base_url" id="{{ isset($isEdit) ? 'edit-' : '' }}base_url" class="form-input" required placeholder="https://openrouter.ai/api/v1">
    </div>
    <div class="form-group">
        <label class="form-label">API Key</label>
        <input type="password" name="api_key" id="{{ isset($isEdit) ? 'edit-' : '' }}api_key" class="form-input" {{ isset($isEdit) ? '' : 'required' }} placeholder="{{ isset($isEdit) ? 'Kosongkan jika tidak ingin mengubah' : 'sk-or-...' }}">
        @if(isset($isEdit))
            <div class="form-hint">Kosongkan jika tidak ingin mengubah API Key</div>
        @endif
    </div>
    <div class="form-group">
        <label class="form-label">Model</label>
        <input type="text" name="model" id="{{ isset($isEdit) ? 'edit-' : '' }}model" class="form-input" required placeholder="openai/gpt-4o-mini">
    </div>
    <div class="grid-2">
        <div class="form-group">
            <label class="form-label">Max Tokens</label>
            <input type="number" name="max_tokens" id="{{ isset($isEdit) ? 'edit-' : '' }}max_tokens" class="form-input" value="4000" min="100" max="32000" required>
        </div>
        <div class="form-group">
            <label class="form-label">Temperature</label>
            <input type="number" name="temperature" id="{{ isset($isEdit) ? 'edit-' : '' }}temperature" class="form-input" value="0.70" step="0.01" min="0" max="1" required>
        </div>
    </div>
    <div class="form-group">
        <label class="form-label">Priority (1 = tertinggi)</label>
        <input type="number" name="priority" id="{{ isset($isEdit) ? 'edit-' : '' }}priority" class="form-input" value="1" min="1" required>
        <div class="form-hint">Provider dengan priority lebih rendah akan digunakan sebagai fallback</div>
    </div>
</div>
