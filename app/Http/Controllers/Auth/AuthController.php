<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Central\ActivityLog;
use App\Models\Central\Owner;
use App\Models\Central\OwnerTokenBalance;
use App\Models\Central\SystemSetting;
use App\Models\Central\Tenant;
use App\Models\Central\TokenTransaction;
use App\Models\Tenant\User as ParticipantUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Display login form (Central or Tenant Participant).
     */
    public function showLogin(Request $request, $tenant = null)
    {
        $tenantSlug = $tenant ?: tenancy()->tenant?->slug ?: request()->route('tenant');

        if ($tenantSlug) {
            if (Auth::guard('participant')->check()) {
                return redirect()->route('tenant.participant.dashboard', ['tenant' => $tenantSlug]);
            }
            return view('auth.login', [
                'mode' => 'participant_login',
                'tenant' => $tenantSlug,
                'tenantModel' => tenancy()->tenant ?: Tenant::find($tenantSlug),
            ]);
        }

        if (Auth::guard('web')->check()) {
            return redirect()->route('superadmin.dashboard');
        }

        if (Auth::guard('owner')->check()) {
            $owner = Auth::guard('owner')->user();
            if ($owner && $owner->tenant) {
                return redirect()->route('tenant.owner.dashboard', ['tenant' => $owner->tenant->slug]);
            }
        }

        return view('auth.login', ['mode' => 'central_login']);
    }

    /**
     * Handle login submission.
     */
    public function login(Request $request, $tenant = null)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');
        $tenantSlug = $tenant ?: tenancy()->tenant?->slug ?: request()->route('tenant');

        // Participant login inside tenant route
        if ($tenantSlug) {
            if (Auth::guard('participant')->attempt($credentials, $remember)) {
                $request->session()->regenerate();
                $user = Auth::guard('participant')->user();

                if (!$user->isActive()) {
                    Auth::guard('participant')->logout();
                    return back()->withErrors([
                        'email' => 'Akun Anda telah dinonaktifkan. Hubungi pengelola.',
                    ])->onlyInput('email');
                }

                return redirect()->route('tenant.participant.dashboard', ['tenant' => $tenantSlug]);
            }

            return back()->withErrors([
                'email' => 'Kredensial yang diberikan tidak cocok dengan data peserta kami.',
            ])->onlyInput('email');
        }

        // Central login (SuperAdmin or Owner)
        // 1. Try SuperAdmin
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('superadmin.dashboard'));
        }

        // 2. Try Owner
        if (Auth::guard('owner')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $owner = Auth::guard('owner')->user();
            if (!$owner->isActive()) {
                Auth::guard('owner')->logout();
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
                ])->onlyInput('email');
            }

            $ownerTenant = $owner->tenant;
            if ($ownerTenant) {
                return redirect()->route('tenant.owner.dashboard', ['tenant' => $ownerTenant->slug]);
            }

            return back()->withErrors([
                'email' => 'Tenant tidak ditemukan untuk akun ini.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    /**
     * Display registration form (Owner or Participant).
     */
    public function showRegister(Request $request, $tenant = null, $token = null)
    {
        $tenantSlug = $tenant ?: tenancy()->tenant?->slug ?: request()->route('tenant');

        if ($tenantSlug) {
            return view('auth.login', [
                'mode' => 'participant_register',
                'tenant' => $tenantSlug,
                'token' => $token,
                'tenantModel' => tenancy()->tenant ?: Tenant::find($tenantSlug),
            ]);
        }

        return view('auth.login', ['mode' => 'owner_register']);
    }

    /**
     * Handle registration submission.
     */
    public function register(Request $request, $tenant = null, $token = null)
    {
        $tenantSlug = $tenant ?: tenancy()->tenant?->slug ?: request()->route('tenant');

        // Participant Registration
        if ($tenantSlug) {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email'],
                'password' => ['required', 'confirmed', Password::defaults()],
            ]);

            $user = ParticipantUser::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'status' => 'active',
                'email_verified_at' => now(),
            ]);

            Auth::guard('participant')->login($user);

            return redirect()->route('tenant.participant.dashboard', ['tenant' => $tenantSlug]);
        }

        // Owner Registration
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:owners,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'organization_name' => ['required', 'string', 'max:255'],
        ]);

        try {
            DB::beginTransaction();

            $slug = Str::slug($request->organization_name);
            $originalSlug = $slug;
            $counter = 1;
            while (Tenant::where('slug', $slug)->exists() || Owner::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $owner = Owner::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'organization_name' => $request->organization_name,
                'slug' => $slug,
                'status' => 'active',
                'type' => 'regular',
            ]);

            Tenant::create([
                'id' => $slug,
                'slug' => $slug,
                'name' => $request->organization_name,
                'owner_id' => $owner->id,
                'is_active' => true,
            ]);

            $freeTokens = SystemSetting::getValue('free_token_on_register', 50);
            OwnerTokenBalance::create([
                'owner_id' => $owner->id,
                'balance' => $freeTokens,
                'is_unlimited' => false,
            ]);

            TokenTransaction::create([
                'owner_id' => $owner->id,
                'type' => 'credit',
                'amount' => $freeTokens,
                'source' => 'register',
                'reference_id' => $owner->id,
                'note' => "Token gratis registrasi ({$freeTokens} token)",
                'created_at' => now(),
            ]);

            ActivityLog::log(
                'owner_registered',
                "Owner baru terdaftar: {$owner->name} ({$owner->email})",
                'owner',
                $owner->id,
                ['organization' => $request->organization_name, 'slug' => $slug]
            );

            DB::commit();

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

    /**
     * Handle logout.
     */
    public function logout(Request $request, $tenant = null)
    {
        $tenantSlug = $tenant ?: tenancy()->tenant?->slug ?: request()->route('tenant');

        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        if (Auth::guard('owner')->check()) {
            Auth::guard('owner')->logout();
        }

        if (Auth::guard('participant')->check()) {
            Auth::guard('participant')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($tenantSlug) {
            return redirect()->route('tenant.login', ['tenant' => $tenantSlug]);
        }

        return redirect()->route('login');
    }
}
