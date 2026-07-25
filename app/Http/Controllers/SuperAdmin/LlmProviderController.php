<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Central\LlmProvider;
use App\Models\Central\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class LlmProviderController extends Controller
{
    public function index()
    {
        $providers = LlmProvider::global()->orderBy('priority')->get();
        return view('superadmin.llm.index', compact('providers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'provider_type' => 'required|in:openrouter,deepseek,custom',
            'base_url' => 'required|url',
            'api_key' => 'required|string',
            'model' => 'required|string|max:255',
            'max_tokens' => 'required|integer|min:100|max:32000',
            'temperature' => 'required|numeric|min:0|max:1',
            'priority' => 'required|integer|min:1',
            'status' => 'required|in:active,fallback,inactive',
        ]);

        LlmProvider::create($request->all());

        ActivityLog::log('create_llm', "LLM provider baru: {$request->name}", 'superadmin', auth()->id());

        return back()->with('success', 'LLM Provider berhasil ditambahkan.');
    }

    public function update(Request $request, LlmProvider $llm)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'provider_type' => 'required|in:openrouter,deepseek,custom',
            'base_url' => 'required|url',
            'api_key' => 'nullable|string',
            'model' => 'required|string|max:255',
            'max_tokens' => 'required|integer|min:100|max:32000',
            'temperature' => 'required|numeric|min:0|max:1',
            'priority' => 'required|integer|min:1',
            'status' => 'required|in:active,fallback,inactive',
        ]);

        $data = $request->except('api_key');
        if ($request->filled('api_key')) {
            $data['api_key'] = $request->api_key;
        }

        $llm->update($data);

        ActivityLog::log('update_llm', "LLM provider diperbarui: {$request->name}", 'superadmin', auth()->id());

        return back()->with('success', 'LLM Provider berhasil diperbarui.');
    }

    public function destroy(LlmProvider $llm)
    {
        $name = $llm->name;
        $llm->delete();

        ActivityLog::log('delete_llm', "LLM provider dihapus: {$name}", 'superadmin', auth()->id());

        return back()->with('success', "LLM Provider {$name} berhasil dihapus.");
    }

    public function testConnection(LlmProvider $llm)
    {
        try {
            $apiKey = trim((string) $llm->api_key);
            if (empty($apiKey)) {
                $apiKey = match (strtolower($llm->provider_type)) {
                    'openrouter' => env('OPENROUTER_API_KEY', ''),
                    'deepseek' => env('DEEPSEEK_API_KEY', ''),
                    default => env('LLM_API_KEY', ''),
                };
            }

            if (empty($apiKey)) {
                return back()->with('error', "API Key untuk {$llm->name} belum dikonfigurasi.");
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(15)->post(rtrim($llm->base_url, '/') . '/chat/completions', [
                'model' => $llm->model,
                'messages' => [['role' => 'user', 'content' => 'Hello, respond with just "OK"']],
                'max_tokens' => 10,
            ]);

            if ($response->successful()) {
                return back()->with('success', "Koneksi ke {$llm->name} berhasil! Model: {$llm->model}");
            }

            return back()->with('error', "Koneksi gagal ({$response->status()}): " . $response->body());
        } catch (\Exception $e) {
            return back()->with('error', "Koneksi gagal: " . $e->getMessage());
        }
    }
}
