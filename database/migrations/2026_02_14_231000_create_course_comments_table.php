<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('course_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('eleve_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->tinyInteger('rating')->nullable();
            $table->text('comment');
            $table->text('formateur_reply')->nullable();
            $table->enum('status', ['pending', 'approved', 'hidden'])->default('pending');
            $table->foreignId('moderated_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('moderated_at')->nullable();
            $table->timestamps();

            $table->index(['course_id', 'status']);
            $table->index(['eleve_user_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_comments');
    }
}
