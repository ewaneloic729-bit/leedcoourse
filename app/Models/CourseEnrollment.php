<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use App\Support\PlatformEvents;

class CourseEnrollment extends Model
{
    use HasFactory;

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'course_id',
        'eleve_user_id',
        'status',
        'requested_at',
        'response_deadline_at',
        'decision_at',
        'responded_by_user_id',
        'response_note',
        'enrolled_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'response_deadline_at' => 'datetime',
        'decision_at' => 'datetime',
        'enrolled_at' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function eleve()
    {
        return $this->belongsTo(User::class, 'eleve_user_id');
    }

    public function responder()
    {
        return $this->belongsTo(User::class, 'responded_by_user_id');
    }

    public function scopeApproved(Builder $query): Builder
    {
        if (self::usesStatusWorkflow()) {
            $query->where('status', self::STATUS_APPROVED);
        }

        return $query;
    }

    public static function approvedForLearner(?int $learnerId): Builder
    {
        return self::query()
            ->where('eleve_user_id', $learnerId)
            ->approved();
    }

    public static function usesStatusWorkflow(): bool
    {
        return Schema::hasColumn('course_enrollments', 'status');
    }

    public static function rejectExpiredPending(?array $courseIds = null): int
    {
        if (! self::usesStatusWorkflow() || ! Schema::hasColumn('course_enrollments', 'response_deadline_at')) {
            return 0;
        }

        $query = self::query()
            ->where('status', self::STATUS_PENDING)
            ->whereNotNull('response_deadline_at')
            ->where('response_deadline_at', '<', now());

        if (! empty($courseIds)) {
            $query->whereIn('course_id', $courseIds);
        }

        $expired = $query->with(['course:id,title', 'eleve:id'])->get();

        foreach ($expired as $enrollment) {
            $enrollment->status = self::STATUS_REJECTED;
            $enrollment->decision_at = now();
            $enrollment->responded_by_user_id = null;
            $enrollment->response_note = 'Demande refusee automatiquement (delai de 3 jours depasse).';
            $enrollment->save();

            if ($enrollment->eleve_user_id) {
                PlatformEvents::notify(
                    (int) $enrollment->eleve_user_id,
                    'Demande d inscription refusee',
                    'Votre demande pour le cours '.optional($enrollment->course)->title.' a ete refusee automatiquement apres 3 jours sans reponse.'
                );
            }
            PlatformEvents::log(
                null,
                'course.enrollment.auto_rejected',
                self::class,
                $enrollment->id,
                ['course_id' => $enrollment->course_id, 'eleve_user_id' => $enrollment->eleve_user_id]
            );
        }

        return $expired->count();
    }
}
