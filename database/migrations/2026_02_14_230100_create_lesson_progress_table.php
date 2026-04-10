<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonProgressTable extends Migration
{
    public function up()
    {
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_lesson_id')->constrained('course_lessons')->cascadeOnDelete();
            $table->foreignId('eleve_user_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['course_lesson_id', 'eleve_user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('lesson_progress');
    }
}
