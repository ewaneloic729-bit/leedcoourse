<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_chapter_id',
        'title',
        'lesson_type',
        'content',
        'video_url',
        'pdf_path',
        'position',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function chapter()
    {
        return $this->belongsTo(CourseChapter::class, 'course_chapter_id');
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class, 'course_lesson_id');
    }
}
