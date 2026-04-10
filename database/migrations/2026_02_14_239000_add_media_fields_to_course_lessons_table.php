<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMediaFieldsToCourseLessonsTable extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('course_lessons')) {
            return;
        }

        Schema::table('course_lessons', function (Blueprint $table) {
            if (! Schema::hasColumn('course_lessons', 'lesson_type')) {
                $table->string('lesson_type', 20)->default('text')->after('title');
            }
            if (! Schema::hasColumn('course_lessons', 'video_url')) {
                $table->string('video_url', 1200)->nullable()->after('content');
            }
            if (! Schema::hasColumn('course_lessons', 'pdf_path')) {
                $table->string('pdf_path')->nullable()->after('video_url');
            }
        });
    }

    public function down()
    {
        if (! Schema::hasTable('course_lessons')) {
            return;
        }

        Schema::table('course_lessons', function (Blueprint $table) {
            if (Schema::hasColumn('course_lessons', 'pdf_path')) {
                $table->dropColumn('pdf_path');
            }
            if (Schema::hasColumn('course_lessons', 'video_url')) {
                $table->dropColumn('video_url');
            }
            if (Schema::hasColumn('course_lessons', 'lesson_type')) {
                $table->dropColumn('lesson_type');
            }
        });
    }
}
