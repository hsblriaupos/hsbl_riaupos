<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('team_list', function (Blueprint $table) {
            // Ubah kolom referral_code jadi nullable
            $table->string('referral_code')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('team_list', function (Blueprint $table) {
            // Kembalikan ke not null (jika perlu rollback)
            $table->string('referral_code')->nullable(false)->change();
        });
    }
};