<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Ubah empty string menjadi NULL
        DB::statement("UPDATE team_list SET referral_code = NULL WHERE referral_code = ''");

        Schema::table('team_list', function (Blueprint $table) {

            // Drop unique index jika sudah ada
            if ($this->hasUniqueIndex('team_list', 'team_list_referral_code_unique')) {
                $table->dropUnique('team_list_referral_code_unique');
            }

            // Ubah kolom jadi nullable
            $table->string('referral_code')->nullable()->change();
        });

        // Tambahkan unique kembali
        Schema::table('team_list', function (Blueprint $table) {
            $table->unique('referral_code');
        });
    }

    public function down()
    {
        Schema::table('team_list', function (Blueprint $table) {

            // Drop unique dulu
            $table->dropUnique(['referral_code']);

            // Kembalikan jadi NOT NULL
            $table->string('referral_code')->nullable(false)->change();
        });

        // NULL dikembalikan ke empty string
        DB::statement("UPDATE team_list SET referral_code = '' WHERE referral_code IS NULL");

        // Tambahkan unique lagi
        Schema::table('team_list', function (Blueprint $table) {
            $table->unique('referral_code');
        });
    }

    /**
     * Helper untuk cek apakah index ada
     */
    private function hasUniqueIndex($table, $indexName)
    {
        $indexes = DB::select("SHOW INDEX FROM {$table}");
        foreach ($indexes as $index) {
            if ($index->Key_name === $indexName) {
                return true;
            }
        }
        return false;
    }
};
