<?php

namespace App\Http\Controllers;

use App\Models\Eleve;
use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user()->load(['eleve', 'enseignant']);

        return view('profile.edit', [
            'user' => $user,
            'eleve' => $user->eleve,
            'enseignant' => $user->enseignant,
        ]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'whatsapp_phone' => Schema::hasColumn('users', 'whatsapp_phone')
                ? ['nullable', 'string', 'max:25', Rule::unique('users', 'whatsapp_phone')->ignore($user->id)]
                : ['nullable'],
            'classe' => ['nullable', 'string', 'max:255'],
            'date_naissance' => ['nullable', 'date', 'before:today'],
            'nom' => ['nullable', 'string', 'max:255'],
            'prenom' => ['nullable', 'string', 'max:255'],
            'specialite' => ['nullable', 'string', 'max:255'],
            'diplome' => ['nullable', 'string', 'max:255'],
            'annees_experience' => ['nullable', 'integer', 'min:0', 'max:80'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (Schema::hasColumn('users', 'whatsapp_phone')) {
            $user->whatsapp_phone = ! empty($validated['whatsapp_phone'])
                ? preg_replace('/\D+/', '', (string) $validated['whatsapp_phone'])
                : null;
        }
        $user->save();

        if ($user->role === User::ROLE_ELEVE && Schema::hasTable('eleves')) {
            Eleve::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'classe' => $validated['classe'] ?? optional($user->eleve)->classe ?? 'N/A',
                    'date_naissance' => $validated['date_naissance'] ?? optional($user->eleve)->date_naissance ?? now()->subYears(18)->toDateString(),
                ]
            );
        }

        if ($user->role === User::ROLE_ENSEIGNANT && Schema::hasTable('enseignants')) {
            Enseignant::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nom' => $validated['nom'] ?? optional($user->enseignant)->nom ?? $user->name,
                    'prenom' => $validated['prenom'] ?? optional($user->enseignant)->prenom ?? '',
                    'email' => $validated['email'],
                    'specialite' => $validated['specialite'] ?? null,
                    'diplome' => $validated['diplome'] ?? null,
                    'annees_experience' => $validated['annees_experience'] ?? null,
                ]
            );
        }

        return back()->with('success_profile', 'Profil mis a jour avec succes.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = $request->user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success_password', 'Mot de passe modifie avec succes.');
    }
}
