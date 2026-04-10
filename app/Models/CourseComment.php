<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'eleve_user_id',
        'rating',
        'comment',
        'formateur_reply',
        'status',
        'moderated_by_user_id',
        'moderated_at',
    ];

    protected $casts = [
        'moderated_at' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function eleve()
    {
        return $this->belongsTo(User::class, 'eleve_user_id');
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderated_by_user_id');
    }
}
