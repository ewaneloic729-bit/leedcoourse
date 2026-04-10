<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationQuestionsTable extends Migration
{
    public function up()
    {
        Schema::create('evaluation_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('evaluations')->cascadeOnDelete();
            $table->enum('type', ['qcm', 'text'])->default('qcm');
            $table->text('question');
            $table->json('choices')->nullable();
            $table->string('correct_choice')->nullable();
            $table->unsignedInteger('points')->default(1);
            $table->unsignedInteger('position')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_questions');
    }
}
