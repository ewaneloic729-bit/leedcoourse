<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'view-courses' => 'Voir les cours',
            'manage-courses' => 'Gerer les cours',
            'view-students' => 'Voir les eleves',
            'manage-users' => 'Gerer les utilisateurs',
            'manage-roles' => 'Gerer les roles',
            'manage-system' => 'Administrer la plateforme',
        ];

        $permissionModels = [];

        foreach ($permissions as $name => $label) {
            $permissionModels[$name] = Permission::firstOrCreate(
                ['name' => $name],
                ['label' => $label]
            );
        }

        $roleEleve = Role::firstOrCreate(
            ['name' => 'eleve'],
            ['label' => 'Etudiant']
        );
        $roleEnseignant = Role::firstOrCreate(
            ['name' => 'enseignant'],
            ['label' => 'Professeur']
        );
        $roleSuperadmin = Role::firstOrCreate(
            ['name' => 'superadmin'],
            ['label' => 'Superadmin']
        );

        $roleEleve->permissions()->sync([
            $permissionModels['view-courses']->id,
        ]);

        $roleEnseignant->permissions()->sync([
            $permissionModels['view-courses']->id,
            $permissionModels['manage-courses']->id,
            $permissionModels['view-students']->id,
        ]);

        $roleSuperadmin->permissions()->sync(
            Permission::pluck('id')->all()
        );
    }
}
