<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationAttemptsTable extends Migration
{
    public function up()
    {
        Schema::create('evaluation_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained('evaluations')->cascadeOnDelete();
            $table->foreignId('eleve_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('answers')->nullable();
            $table->decimal('score', 6, 2)->default(0);
            $table->decimal('max_score', 6, 2)->default(0);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_attempts');
    }
}
