<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBankItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'formateur_user_id',
        'title',
        'type',
        'question',
        'choices',
        'correct_choice',
        'default_points',
    ];

    protected $casts = [
        'choices' => 'array',
    ];
}
