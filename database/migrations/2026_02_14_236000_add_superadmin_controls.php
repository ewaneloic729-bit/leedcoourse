<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSuperadminControls extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
        });

        Schema::table('courses', function (Blueprint $table) {
            if (! Schema::hasColumn('courses', 'is_promo_only')) {
                $table->boolean('is_promo_only')->default(false)->after('is_available');
            }
        });

        Schema::create('platform_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('platform_communications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('superadmin_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('title');
            $table->text('message');
            $table->boolean('is_published')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('platform_communications');
        Schema::dropIfExists('platform_settings');

        Schema::table('courses', function (Blueprint $table) {
            if (Schema::hasColumn('courses', 'is_promo_only')) {
                $table->dropColumn('is_promo_only');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
}
