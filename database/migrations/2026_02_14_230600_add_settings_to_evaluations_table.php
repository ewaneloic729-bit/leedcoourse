<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettingsToEvaluationsTable extends Migration
{
    public function up()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->boolean('randomize_questions')->default(false)->after('is_published');
            $table->unsignedInteger('pass_score')->default(10)->after('randomize_questions');
            $table->unsignedInteger('duration_minutes')->nullable()->after('pass_score');
        });
    }

    public function down()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropColumn(['randomize_questions', 'pass_score', 'duration_minutes']);
        });
    }
}
