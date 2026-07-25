<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::guard('web')->user() ?? Auth::guard('owner')->user() ?? Auth::guard('participant')->user();
        $isSuperAdmin = Auth::guard('web')->check();
        $isOwner = Auth::guard('owner')->check();
        $isParticipant = Auth::guard('participant')->check();

        return view('modules.profile.edit', compact('user', 'isSuperAdmin', 'isOwner', 'isParticipant'));
    }

    public function update(Request $request)
    {
        $guard = Auth::guard('web')->check() ? 'web' : (Auth::guard('owner')->check() ? 'owner' : 'participant');
        $user = Auth::guard($guard)->user();

        $rules = [
            'name' => ['required', 'string', 'max:255'],
        ];

        if ($guard === 'owner') {
            $rules['organization_name'] = ['nullable', 'string', 'max:255'];
            $rules['phone_number'] = ['nullable', 'string', 'max:50'];
        }

        if ($request->filled('password')) {
            $rules['current_password'] = ['required', 'current_password:' . $guard];
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $validated = $request->validate($rules);

        $user->name = $validated['name'];
        if ($guard === 'owner') {
            if (isset($validated['organization_name'])) {
                $user->organization_name = $validated['organization_name'];
            }
            if (isset($validated['phone_number'])) {
                $user->phone_number = $validated['phone_number'];
            }
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profil Anda telah berhasil diperbarui.');
    }
}
