<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'eleve_user_id',
        'answers',
        'score',
        'max_score',
        'started_at',
        'expires_at',
        'status',
        'submitted_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'submitted_at' => 'datetime',
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function eleve()
    {
        return $this->belongsTo(User::class, 'eleve_user_id');
    }

    public function answerDetails()
    {
        return $this->hasMany(EvaluationAttemptAnswer::class);
    }
}
