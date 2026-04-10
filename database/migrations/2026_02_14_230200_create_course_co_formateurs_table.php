<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseCoFormateursTable extends Migration
{
    public function up()
    {
        Schema::create('course_co_formateurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('formateur_user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['course_id', 'formateur_user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_co_formateurs');
    }
}
