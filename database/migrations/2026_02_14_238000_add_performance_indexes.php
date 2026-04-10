<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddPerformanceIndexes extends Migration
{
    public function up()
    {
        $this->addIndex('users', 'idx_users_role_active_created', ['role', 'is_active', 'created_at']);
        $this->addIndex('courses', 'idx_courses_visibility', ['is_available', 'publication_status', 'is_promo_only']);
        $this->addIndex('courses', 'idx_courses_formateur_created', ['formateur_user_id', 'created_at']);

        $this->addIndex('course_enrollments', 'idx_enrollments_eleve_status_course', ['eleve_user_id', 'status', 'course_id']);
        $this->addIndex('course_enrollments', 'idx_enrollments_status_deadline', ['status', 'response_deadline_at']);
        $this->addIndex('course_enrollments', 'idx_enrollments_course_created', ['course_id', 'created_at']);

        $this->addIndex('in_app_notifications', 'idx_notifications_user_read_created', ['user_id', 'is_read', 'created_at']);
        $this->addIndex('devoir_submissions', 'idx_devoirs_course_status_created', ['course_id', 'status', 'created_at']);
        $this->addIndex('devoir_submissions', 'idx_devoirs_eleve_created', ['eleve_user_id', 'created_at']);
        $this->addIndex('evaluation_attempts', 'idx_eval_attempts_eleve_submitted', ['eleve_user_id', 'submitted_at']);
        $this->addIndex('evaluation_attempts', 'idx_eval_attempts_status_expires', ['status', 'expires_at']);
        $this->addIndex('course_comments', 'idx_comments_course_status_created', ['course_id', 'status', 'created_at']);
        $this->addIndex('activity_logs', 'idx_activity_user_action_created', ['user_id', 'action', 'created_at']);
        $this->addIndex('platform_communications', 'idx_comms_published_dates', ['is_published', 'published_at']);
    }

    public function down()
    {
        $this->dropIndex('users', 'idx_users_role_active_created');
        $this->dropIndex('courses', 'idx_courses_visibility');
        $this->dropIndex('courses', 'idx_courses_formateur_created');

        $this->dropIndex('course_enrollments', 'idx_enrollments_eleve_status_course');
        $this->dropIndex('course_enrollments', 'idx_enrollments_status_deadline');
        $this->dropIndex('course_enrollments', 'idx_enrollments_course_created');

        $this->dropIndex('in_app_notifications', 'idx_notifications_user_read_created');
        $this->dropIndex('devoir_submissions', 'idx_devoirs_course_status_created');
        $this->dropIndex('devoir_submissions', 'idx_devoirs_eleve_created');
        $this->dropIndex('evaluation_attempts', 'idx_eval_attempts_eleve_submitted');
        $this->dropIndex('evaluation_attempts', 'idx_eval_attempts_status_expires');
        $this->dropIndex('course_comments', 'idx_comments_course_status_created');
        $this->dropIndex('activity_logs', 'idx_activity_user_action_created');
        $this->dropIndex('platform_communications', 'idx_comms_published_dates');
    }

    private function addIndex(string $table, string $indexName, array $columns): void
    {
        if (! Schema::hasTable($table) || ! $this->allColumnsExist($table, $columns) || $this->indexExists($table, $indexName)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($columns, $indexName) {
            $blueprint->index($columns, $indexName);
        });
    }

    private function dropIndex(string $table, string $indexName): void
    {
        if (! Schema::hasTable($table) || ! $this->indexExists($table, $indexName)) {
            return;
        }

        Schema::table($table, function (Blueprint $blueprint) use ($indexName) {
            $blueprint->dropIndex($indexName);
        });
    }

    private function allColumnsExist(string $table, array $columns): bool
    {
        foreach ($columns as $column) {
            if (! Schema::hasColumn($table, $column)) {
                return false;
            }
        }

        return true;
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $dbName = DB::getDatabaseName();
        if (! $dbName) {
            return false;
        }

        $row = DB::table('information_schema.statistics')
            ->select('index_name')
            ->where('table_schema', $dbName)
            ->where('table_name', $table)
            ->where('index_name', $indexName)
            ->first();

        return $row !== null;
    }
}
