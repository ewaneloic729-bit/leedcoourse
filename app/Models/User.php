<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Permission;
use App\Models\Role;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public const ROLE_ELEVE = 'eleve';
    public const ROLE_ENSEIGNANT = 'enseignant';
    public const ROLE_SUPERADMIN = 'superadmin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];
// Vérifier si l'utilisateur est un élève
    public function isEleve()
    {
        return $this->role === self::ROLE_ELEVE;
    }

    // Vérifier si l'utilisateur est un enseignant
    public function isEnseignant()
    {
        return $this->role === self::ROLE_ENSEIGNANT;
    }

    public function isSuperadmin()
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    public function roleEntity()
    {
        return $this->belongsTo(Role::class, 'role', 'name');
    }

    public function permissionNames(): array
    {
        if ($this->isSuperadmin()) {
            return Permission::pluck('name')->all();
        }

        $role = $this->roleEntity;

        if (! $role) {
            return [];
        }

        return $role->permissions->pluck('name')->all();
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperadmin()) {
            return true;
        }

        $role = $this->roleEntity;

        if (! $role) {
            return false;
        }

        return $role->permissions->pluck('name')->contains($permission);
    }

    public function dashboardRouteName(): string
    {
        if ($this->isSuperadmin()) {
            return 'dashboard.superadmin';
        }

        if ($this->isEnseignant()) {
            return 'dashboard.enseignant';
        }

        if ($this->isEleve()) {
            return 'dashboard.eleve';
        }

        return 'dashboard';
    }

    public function eleve()
    {
        return $this->hasOne(Eleve::class);
    }

    public function enseignant()
    {
        return $this->hasOne(Enseignant::class);
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
