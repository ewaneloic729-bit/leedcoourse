<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseAnnouncement extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'formateur_user_id',
        'title',
        'message',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function formateur()
    {
        return $this->belongsTo(User::class, 'formateur_user_id');
    }
}
