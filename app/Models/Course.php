<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'category',
        'level',
        'image',
        'formateur_user_id',
        'is_available',
        'is_promo_only',
        'publication_status',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_promo_only' => 'boolean',
    ];

    public function formateur()
    {
        return $this->belongsTo(User::class, 'formateur_user_id');
    }

    public function devoirSubmissions()
    {
        return $this->hasMany(DevoirSubmission::class);
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class);
    }

    public function chapters()
    {
        return $this->hasMany(CourseChapter::class)->orderBy('position');
    }

    public function announcements()
    {
        return $this->hasMany(CourseAnnouncement::class)->latest();
    }

    public function enrollments()
    {
        return $this->hasMany(CourseEnrollment::class);
    }

    public function apprenants()
    {
        return $this->belongsToMany(User::class, 'course_enrollments', 'course_id', 'eleve_user_id')
            ->withTimestamps();
    }

    public function coFormateurs()
    {
        return $this->belongsToMany(User::class, 'course_co_formateurs', 'course_id', 'formateur_user_id')
            ->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(CourseComment::class)->latest();
    }
}
