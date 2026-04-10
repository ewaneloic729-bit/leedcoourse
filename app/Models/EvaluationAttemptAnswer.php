<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationAttemptAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_attempt_id',
        'evaluation_question_id',
        'answer_text',
        'awarded_points',
        'teacher_feedback',
    ];

    protected $casts = [
        'awarded_points' => 'decimal:2',
    ];
}
