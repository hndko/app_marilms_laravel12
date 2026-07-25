<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the unified central login page.
     */
    public function showLogin()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('superadmin.dashboard');
        }

        if (Auth::guard('owner')->check()) {
            $owner = Auth::guard('owner')->user();
            if ($owner && $owner->tenant) {
                return redirect()->route('tenant.owner.dashboard', ['tenant' => $owner->tenant->slug]);
            }
        }

        return view('auth.login');
    }

    /**
     * Handle unified authentication for SuperAdmin and Owner.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        // 1. Attempt SuperAdmin login (web guard)
        if (Auth::guard('web')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('superadmin.dashboard'));
        }

        // 2. Attempt Owner login (owner guard)
        if (Auth::guard('owner')->attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $owner = Auth::guard('owner')->user();

            if (!$owner->isActive()) {
                Auth::guard('owner')->logout();
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Hubungi administrator.',
                ])->onlyInput('email');
            }

            $tenant = $owner->tenant;
            if ($tenant) {
                return redirect()->route('tenant.owner.dashboard', ['tenant' => $tenant->slug]);
            }

            return back()->withErrors([
                'email' => 'Tenant tidak ditemukan untuk akun ini.',
            ])->onlyInput('email');
        }

        // 3. Failed authentication
        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    /**
     * Handle logout for both guards.
     */
    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }

        if (Auth::guard('owner')->check()) {
            Auth::guard('owner')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
