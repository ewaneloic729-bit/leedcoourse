<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class EnseignantRegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register-enseignant');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'unique:enseignants'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'specialite' => ['required', 'string', 'max:255'],
            'annees_experience' => ['required', 'integer', 'min:0'],
        ]);

        $user = null;

        DB::transaction(function () use ($validated, &$user) {
            $fullName = trim($validated['prenom'] . ' ' . $validated['nom']);

            $user = User::create([
                'name' => $fullName,
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => User::ROLE_ENSEIGNANT,
            ]);

            Enseignant::create([
                'user_id' => $user->id,
                'nom' => $validated['nom'],
                'prenom' => $validated['prenom'],
                'email' => $validated['email'],
                'specialite' => $validated['specialite'],
                'annees_experience' => $validated['annees_experience'],
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
