<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormateurAndAvailabilityToCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('formateur_user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            $table->boolean('is_available')->default(false)->after('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('formateur_user_id');
            $table->dropColumn('is_available');
        });
    }
}

