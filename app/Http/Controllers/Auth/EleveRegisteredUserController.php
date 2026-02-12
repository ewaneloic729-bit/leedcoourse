<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Eleve;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class EleveRegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register-eleve');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'classe' => ['required', 'string', 'max:255'],
            'date_naissance' => ['required', 'date'],
        ]);

        $user = null;

        DB::transaction(function () use ($validated, &$user) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => User::ROLE_ELEVE,
            ]);

            Eleve::create([
                'user_id' => $user->id,
                'classe' => $validated['classe'],
                'date_naissance' => $validated['date_naissance'],
            ]);
        });

        event(new Registered($user));

        Auth::login($user);

        $request->session()->regenerate();
        $user->loadMissing('roleEntity.permissions');
        $request->session()->put('role', $user->role);
        $request->session()->put('permissions', $user->permissionNames());
        $request->session()->put('dashboard_route', $user->dashboardRouteName());

        return redirect()->route('dashboard');
    }
}
