<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkflowFieldsToCourseEnrollmentsTable extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('course_enrollments')) {
            return;
        }

        Schema::table('course_enrollments', function (Blueprint $table) {
            if (! Schema::hasColumn('course_enrollments', 'status')) {
                $table->string('status', 20)->default('approved')->after('eleve_user_id');
            }
            if (! Schema::hasColumn('course_enrollments', 'requested_at')) {
                $table->timestamp('requested_at')->nullable()->after('status');
            }
            if (! Schema::hasColumn('course_enrollments', 'response_deadline_at')) {
                $table->timestamp('response_deadline_at')->nullable()->after('requested_at');
            }
            if (! Schema::hasColumn('course_enrollments', 'decision_at')) {
                $table->timestamp('decision_at')->nullable()->after('response_deadline_at');
            }
            if (! Schema::hasColumn('course_enrollments', 'responded_by_user_id')) {
                $table->foreignId('responded_by_user_id')->nullable()->after('decision_at')->constrained('users')->nullOnDelete();
            }
            if (! Schema::hasColumn('course_enrollments', 'response_note')) {
                $table->string('response_note', 500)->nullable()->after('responded_by_user_id');
            }
        });
    }

    public function down()
    {
        if (! Schema::hasTable('course_enrollments')) {
            return;
        }

        Schema::table('course_enrollments', function (Blueprint $table) {
            if (Schema::hasColumn('course_enrollments', 'responded_by_user_id')) {
                $table->dropConstrainedForeignId('responded_by_user_id');
            }
            if (Schema::hasColumn('course_enrollments', 'response_note')) {
                $table->dropColumn('response_note');
            }
            if (Schema::hasColumn('course_enrollments', 'decision_at')) {
                $table->dropColumn('decision_at');
            }
            if (Schema::hasColumn('course_enrollments', 'response_deadline_at')) {
                $table->dropColumn('response_deadline_at');
            }
            if (Schema::hasColumn('course_enrollments', 'requested_at')) {
                $table->dropColumn('requested_at');
            }
            if (Schema::hasColumn('course_enrollments', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
}
