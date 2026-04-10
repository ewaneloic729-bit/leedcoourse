<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'formateur_user_id',
        'title',
        'description',
        'type',
        'total_points',
        'due_at',
        'opens_at',
        'is_published',
        'randomize_questions',
        'pass_score',
        'duration_minutes',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'due_at' => 'datetime',
        'opens_at' => 'datetime',
        'randomize_questions' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function formateur()
    {
        return $this->belongsTo(User::class, 'formateur_user_id');
    }

    public function questions()
    {
        return $this->hasMany(EvaluationQuestion::class)->orderBy('position');
    }

    public function attempts()
    {
        return $this->hasMany(EvaluationAttempt::class)->latest();
    }
}
