<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('team_list', function (Blueprint $table) {
            // Tambahkan kolom school_logo setelah school_id
            $table->string('school_logo')->nullable()->after('school_id');
        });
    }

    public function down()
    {
        Schema::table('team_list', function (Blueprint $table) {
            $table->dropColumn('school_logo');
        });
    }
};