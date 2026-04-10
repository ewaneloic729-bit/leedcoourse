<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'evaluation_id',
        'type',
        'question',
        'choices',
        'correct_choice',
        'points',
        'position',
    ];

    protected $casts = [
        'choices' => 'array',
    ];

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
}
