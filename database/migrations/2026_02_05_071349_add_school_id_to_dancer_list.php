<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Ubah semua empty string menjadi NULL
        DB::statement("UPDATE team_list SET referral_code = NULL WHERE referral_code = ''");
        
        // 2. Ubah kolom menjadi nullable
        Schema::table('team_list', function (Blueprint $table) {
            $table->string('referral_code')->nullable()->unique()->change();
        });
    }

    public function down()
    {
        // Kembalikan ke semula (optional)
        Schema::table('team_list', function (Blueprint $table) {
            $table->string('referral_code')->nullable(false)->change();
        });
        
        // Set NULL kembali ke empty string
        DB::statement("UPDATE team_list SET referral_code = '' WHERE referral_code IS NULL");
    }
};