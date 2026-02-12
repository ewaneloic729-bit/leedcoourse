<?php

namespace Database\Seeders;

use App\Models\Eleve;
use App\Models\Enseignant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class DemoUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $password = Hash::make('Password123!');

        $eleve = User::firstOrCreate(
            ['email' => 'eleve@example.test'],
            [
                'name' => 'Eleve Demo',
                'password' => $password,
                'role' => User::ROLE_ELEVE,
            ]
        );

        Eleve::firstOrCreate(
            ['user_id' => $eleve->id],
            [
                'classe' => 'L1 Informatique',
                'date_naissance' => Carbon::create(2004, 5, 12),
            ]
        );

        $enseignant = User::firstOrCreate(
            ['email' => 'enseignant@example.test'],
            [
                'name' => 'Prof Demo',
                'password' => $password,
                'role' => User::ROLE_ENSEIGNANT,
            ]
        );

        Enseignant::firstOrCreate(
            ['user_id' => $enseignant->id],
            [
                'nom' => 'Demo',
                'prenom' => 'Prof',
                'email' => $enseignant->email,
                'specialite' => 'Web Dev',
                'annees_experience' => 6,
            ]
        );

        User::firstOrCreate(
            ['email' => 'superadmin@example.test'],
            [
                'name' => 'Superadmin Demo',
                'password' => $password,
                'role' => User::ROLE_SUPERADMIN,
            ]
        );
    }
}
