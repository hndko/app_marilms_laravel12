<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ParticipantAuthController extends Controller
{
    public function showLogin($tenant)
    {
        return view('auth.participant-login', compact('tenant'));
    }

    public function login(Request $request, $tenant)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('participant')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::guard('participant')->user();

            if (!$user->isActive()) {
                Auth::guard('participant')->logout();
                return back()->withErrors([
                    'email' => 'Akun Anda telah dinonaktifkan. Hubungi pengelola.',
                ]);
            }

            return redirect()->route('tenant.participant.dashboard', ['tenant' => $tenant]);
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    public function showRegister($tenant, $token)
    {
        // Token could be used for invitation-based registration
        return view('auth.participant-register', compact('tenant', 'token'));
    }

    public function register(Request $request, $tenant, $token)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        Auth::guard('participant')->login($user);

        return redirect()->route('tenant.participant.dashboard', ['tenant' => $tenant]);
    }

    public function logout(Request $request, $tenant)
    {
        Auth::guard('participant')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('tenant.login', ['tenant' => $tenant]);
    }
}
