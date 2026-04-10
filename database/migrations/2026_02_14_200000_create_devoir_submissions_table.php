<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevoirSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('devoir_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('eleve_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('student_name');
            $table->string('student_email');
            $table->string('pdf_path');
            $table->enum('status', ['pending', 'in_review', 'corrected'])->default('pending');
            $table->text('correction_note')->nullable();
            $table->string('corrected_pdf_path')->nullable();
            $table->decimal('score', 5, 2)->nullable();
            $table->timestamp('corrected_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devoir_submissions');
    }
}

