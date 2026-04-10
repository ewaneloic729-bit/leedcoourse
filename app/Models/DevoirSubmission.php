<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevoirSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'eleve_user_id',
        'student_name',
        'student_email',
        'pdf_path',
        'status',
        'correction_note',
        'corrected_pdf_path',
        'score',
        'corrected_at',
    ];

    protected $casts = [
        'corrected_at' => 'datetime',
        'score' => 'decimal:2',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function eleve()
    {
        return $this->belongsTo(User::class, 'eleve_user_id');
    }
}

