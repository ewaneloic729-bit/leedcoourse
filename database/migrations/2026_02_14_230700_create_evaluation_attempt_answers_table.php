<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationAttemptAnswersTable extends Migration
{
    public function up()
    {
        Schema::create('evaluation_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_attempt_id')->constrained('evaluation_attempts')->cascadeOnDelete();
            $table->foreignId('evaluation_question_id')->constrained('evaluation_questions')->cascadeOnDelete();
            $table->text('answer_text')->nullable();
            $table->decimal('awarded_points', 6, 2)->nullable();
            $table->text('teacher_feedback')->nullable();
            $table->timestamps();
            $table->unique(['evaluation_attempt_id', 'evaluation_question_id'], 'attempt_question_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_attempt_answers');
    }
}
