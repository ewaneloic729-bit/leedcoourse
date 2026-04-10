<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionBankItemsTable extends Migration
{
    public function up()
    {
        Schema::create('question_bank_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('formateur_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title')->nullable();
            $table->enum('type', ['qcm', 'text'])->default('qcm');
            $table->text('question');
            $table->json('choices')->nullable();
            $table->string('correct_choice')->nullable();
            $table->unsignedInteger('default_points')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('question_bank_items');
    }
}
