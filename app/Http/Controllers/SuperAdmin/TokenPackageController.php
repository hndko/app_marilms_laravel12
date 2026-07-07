<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Central\TokenPackage;
use App\Models\Central\ActivityLog;
use Illuminate\Http\Request;

class TokenPackageController extends Controller
{
    public function index()
    {
        $packages = TokenPackage::orderBy('sort_order')->get();
        return view('superadmin.token-packages.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'token_amount' => 'required|integer|min:1',
            'price_idr' => 'required|integer|min:0',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        TokenPackage::create($request->all());

        ActivityLog::log('create_package', "Paket token baru: {$request->name}", 'superadmin', auth()->id());

        return back()->with('success', 'Paket token berhasil ditambahkan.');
    }

    public function update(Request $request, TokenPackage $tokenPackage)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'token_amount' => 'required|integer|min:1',
            'price_idr' => 'required|integer|min:0',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        $tokenPackage->update($request->all());

        ActivityLog::log('update_package', "Paket token diperbarui: {$request->name}", 'superadmin', auth()->id());

        return back()->with('success', 'Paket token berhasil diperbarui.');
    }

    public function destroy(TokenPackage $tokenPackage)
    {
        $name = $tokenPackage->name;
        $tokenPackage->delete();

        ActivityLog::log('delete_package', "Paket token dihapus: {$name}", 'superadmin', auth()->id());

        return back()->with('success', "Paket token {$name} berhasil dihapus.");
    }
}
