<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'matricule',
        'whatsapp_phone',
        'password',
        'role',
        'is_active',
    ];

    protected static function booted(): void
    {
        static::creating(function (User $user): void {
            if (! Schema::hasColumn('users', 'matricule')) {
                return;
            }

            if (empty($user->matricule)) {
                $user->matricule = self::generateUniqueMatricule();
            }
        });
    }

    public static function generateUniqueMatricule(): string
    {
        $prefix = 'LC'.now()->format('ymd');

        for ($attempt = 0; $attempt < 20; $attempt++) {
            $candidate = $prefix.str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            if (! self::query()->where('matricule', $candidate)->exists()) {
                return $candidate;
            }
        }

        return $prefix.strtoupper(Str::random(6));
    }
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

    public function coursesAsFormateur()
    {
        return $this->hasMany(Course::class, 'formateur_user_id');
    }

    public function evaluationsAsFormateur()
    {
        return $this->hasMany(Evaluation::class, 'formateur_user_id');
    }

    public function evaluationAttempts()
    {
        return $this->hasMany(EvaluationAttempt::class, 'eleve_user_id');
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class, 'eleve_user_id');
    }

    public function enrolledCourses()
    {
        return $this->belongsToMany(Course::class, 'course_enrollments', 'eleve_user_id', 'course_id')
            ->withTimestamps();
    }

    public function coFormateurCourses()
    {
        return $this->belongsToMany(Course::class, 'course_co_formateurs', 'formateur_user_id', 'course_id')
            ->withTimestamps();
    }

    public function inAppNotifications()
    {
        return $this->hasMany(InAppNotification::class);
    }

    public function courseComments()
    {
        return $this->hasMany(CourseComment::class, 'eleve_user_id');
    }

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class, 'conversation_participants')
            ->withTimestamps();
    }

    public function sentConversationMessages(): HasMany
    {
        return $this->hasMany(ConversationMessage::class, 'sender_id');
    }

    public function unreadConversationMessagesCount(): int
    {
        if (! Schema::hasTable('conversation_messages') || ! Schema::hasTable('conversation_participants')) {
            return 0;
        }

        return ConversationMessage::query()
            ->whereHas('conversation.participants', function ($query) {
                $query->whereKey($this->id);
            })
            ->where('sender_id', '!=', $this->id)
            ->whereNull('read_at')
            ->count();
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
        'is_active' => 'boolean',
    ];
}
