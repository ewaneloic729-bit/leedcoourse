<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseEnrollmentsTable extends Migration
{
    public function up()
    {
        Schema::create('course_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('eleve_user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('enrolled_at')->nullable();
            $table->timestamps();
            $table->unique(['course_id', 'eleve_user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_enrollments');
    }
}
