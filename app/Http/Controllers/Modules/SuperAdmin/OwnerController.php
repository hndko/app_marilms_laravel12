<?php

namespace App\Http\Controllers\Modules\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Central\Owner;
use App\Models\Central\OwnerTokenBalance;
use App\Models\Central\ActivityLog;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OwnerController extends Controller
{
    public function __construct(private TokenService $tokenService) {}

    public function index(Request $request)
    {
        $query = Owner::with('tokenBalance', 'tenant')
            ->latest();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('organization_name', 'like', "%{$search}%");
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        $owners = $query->paginate(15)->withQueryString();

        return view('modules.superadmin.owners.index', compact('owners'));
    }

    public function show(Owner $owner)
    {
        $owner->load('tokenBalance', 'tenant', 'tokenTransactions');
        $transactions = $owner->tokenTransactions()->latest('created_at')->take(20)->get();

        return view('modules.superadmin.owners.show', compact('owner', 'transactions'));
    }

    public function toggleUnlimited(Request $request, Owner $owner)
    {
        $currentStatus = $owner->tokenBalance?->is_unlimited ?? false;
        $this->tokenService->toggleUnlimited($owner, !$currentStatus);

        $status = !$currentStatus ? 'Unlimited' : 'Regular';
        ActivityLog::log('toggle_unlimited', "Owner {$owner->name} diubah ke {$status}", 'superadmin', auth()->id());

        return back()->with('success', "Status token Owner berhasil diubah ke {$status}.");
    }

    public function topup(Request $request, Owner $owner)
    {
        $request->validate([
            'amount' => 'required|integer|min:1|max:100000',
            'note' => 'nullable|string|max:500',
        ]);

        $note = $request->input('note', 'Top-up manual oleh SuperAdmin');
        $this->tokenService->manualTopUp($owner, $request->amount, $note);

        ActivityLog::log('manual_topup', "Top-up {$request->amount} token ke Owner {$owner->name}", 'superadmin', auth()->id(), [
            'owner_id' => $owner->id,
            'amount' => $request->amount,
        ]);

        return back()->with('success', "Berhasil menambahkan {$request->amount} token ke {$owner->name}.");
    }

    public function resetPassword(Request $request, Owner $owner)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $owner->update(['password' => Hash::make($request->password)]);

        ActivityLog::log('reset_password', "Password Owner {$owner->name} di-reset", 'superadmin', auth()->id());

        return back()->with('success', "Password {$owner->name} berhasil direset.");
    }

    public function impersonate(Owner $owner)
    {
        session(['impersonating_owner' => $owner->id, 'impersonator_id' => auth()->id()]);
        Auth::guard('owner')->login($owner);

        ActivityLog::log('impersonate', "SuperAdmin login sebagai Owner {$owner->name}", 'superadmin', auth()->id());

        $tenant = $owner->tenant;
        if ($tenant) {
            return redirect()->route('tenant.owner.dashboard', ['tenant' => $tenant->slug]);
        }

        return back()->with('error', 'Tenant tidak ditemukan untuk owner ini.');
    }

    public function update(Request $request, Owner $owner)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:owners,email,' . $owner->id,
            'organization_name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
            'phone' => 'nullable|string|max:20',
        ]);

        $owner->update($request->only(['name', 'email', 'organization_name', 'status', 'phone']));

        ActivityLog::log('update_owner', "Owner {$owner->name} diperbarui", 'superadmin', auth()->id());

        return back()->with('success', 'Data Owner berhasil diperbarui.');
    }

    public function destroy(Owner $owner)
    {
        $name = $owner->name;

        // Soft-deactivate instead of hard delete
        $owner->update(['status' => 'inactive']);
        if ($owner->tenant) {
            $owner->tenant->update(['is_active' => false]);
        }

        ActivityLog::log('deactivate_owner', "Owner {$name} dinonaktifkan", 'superadmin', auth()->id());

        return redirect()->route('superadmin.owners.index')
            ->with('success', "Owner {$name} berhasil dinonaktifkan.");
    }
}
