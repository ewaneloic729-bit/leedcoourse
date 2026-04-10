<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimingFieldsToEvaluationsAndAttempts extends Migration
{
    public function up()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->timestamp('opens_at')->nullable()->after('due_at');
        });

        Schema::table('evaluation_attempts', function (Blueprint $table) {
            $table->timestamp('started_at')->nullable()->after('max_score');
            $table->timestamp('expires_at')->nullable()->after('started_at');
            $table->string('status', 20)->default('submitted')->after('expires_at');
        });
    }

    public function down()
    {
        Schema::table('evaluation_attempts', function (Blueprint $table) {
            $table->dropColumn(['started_at', 'expires_at', 'status']);
        });

        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropColumn(['opens_at']);
        });
    }
}
