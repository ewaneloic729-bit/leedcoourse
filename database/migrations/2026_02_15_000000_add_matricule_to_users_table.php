<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('matricule', 32)->nullable()->unique()->after('email');
        });

        DB::table('users')->orderBy('id')->chunkById(100, function ($users) {
            foreach ($users as $user) {
                if (! empty($user->matricule)) {
                    continue;
                }

                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['matricule' => $this->generateUniqueMatricule()]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_matricule_unique');
            $table->dropColumn('matricule');
        });
    }

    private function generateUniqueMatricule(): string
    {
        $prefix = 'LC'.date('ymd');

        for ($attempt = 0; $attempt < 20; $attempt++) {
            $candidate = $prefix.str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            $exists = DB::table('users')->where('matricule', $candidate)->exists();

            if (! $exists) {
                return $candidate;
            }
        }

        return $prefix.strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
    }
};
