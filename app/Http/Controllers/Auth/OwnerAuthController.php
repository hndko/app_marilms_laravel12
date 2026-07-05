<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Central\Owner;
use App\Models\Central\OwnerTokenBalance;
use App\Models\Central\SystemSetting;
use App\Models\Central\Tenant;
use App\Models\Central\TokenTransaction;
use App\Models\Central\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class OwnerAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.owner-login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('owner')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $owner = Auth::guard('owner')->user();

            if (!$owner->isActive()) {
                Auth::guard('owner')->logout();
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
                ]);
            }

            $tenant = $owner->tenant;
            if ($tenant) {
                return redirect()->route('tenant.owner.dashboard', ['tenant' => $tenant->slug]);
            }

            return back()->withErrors([
                'email' => 'Tenant tidak ditemukan untuk akun ini.',
            ]);
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.owner-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:owners,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'organization_name' => ['required', 'string', 'max:255'],
        ]);

        try {
            DB::beginTransaction();

            // Generate unique slug
            $slug = Str::slug($request->organization_name);
            $originalSlug = $slug;
            $counter = 1;
            while (Tenant::where('slug', $slug)->exists() || Owner::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            // Create Owner
            $owner = Owner::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'organization_name' => $request->organization_name,
                'slug' => $slug,
                'status' => 'active',
                'type' => 'regular',
            ]);

            // Create Tenant
            $tenant = Tenant::create([
                'id' => $slug,
                'slug' => $slug,
                'name' => $request->organization_name,
                'owner_id' => $owner->id,
                'is_active' => true,
            ]);

            // Create token balance with free tokens
            $freeTokens = SystemSetting::getValue('free_token_on_register', 50);
            OwnerTokenBalance::create([
                'owner_id' => $owner->id,
                'balance' => $freeTokens,
                'is_unlimited' => false,
            ]);

            // Log token credit
            TokenTransaction::create([
                'owner_id' => $owner->id,
                'type' => 'credit',
                'amount' => $freeTokens,
                'source' => 'register',
                'reference_id' => $owner->id,
                'note' => "Token gratis registrasi ({$freeTokens} token)",
                'created_at' => now(),
            ]);

            // Log activity
            ActivityLog::log(
                'owner_registered',
                "Owner baru terdaftar: {$owner->name} ({$owner->email})",
                'owner',
                $owner->id,
                ['organization' => $request->organization_name, 'slug' => $slug]
            );

            DB::commit();

            // Login the owner
            Auth::guard('owner')->login($owner);

            return redirect()->route('tenant.owner.dashboard', ['tenant' => $slug]);

        } catch (\Exception $e) {
            DB::rollBack();
            report($e);

            return back()->withErrors([
                'email' => 'Terjadi kesalahan saat registrasi. Silakan coba lagi.',
            ])->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('owner')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('owner.login');
    }
}
