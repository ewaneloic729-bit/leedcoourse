<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiplomeToEnseignantsTable extends Migration
{
    public function up()
    {
        Schema::table('enseignants', function (Blueprint $table) {
            $table->string('diplome')->nullable()->after('specialite');
        });
    }

    public function down()
    {
        Schema::table('enseignants', function (Blueprint $table) {
            $table->dropColumn('diplome');
        });
    }
}
