<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Pastikan semua player memiliki school_id yang valid
        DB::statement("
            UPDATE player_list pl
            INNER JOIN team_list tl ON pl.team_id = tl.team_id
            INNER JOIN schools s ON tl.school_name = s.school_name
            SET pl.school_id = s.id
            WHERE pl.school_id IS NULL
        ");
        
        // 2. Tambahkan foreign key constraint
        Schema::table('player_list', function (Blueprint $table) {
            // Pastikan school_id tidak null untuk data baru
            $table->bigInteger('school_id')->unsigned()->nullable(false)->change();
            
            // Tambahkan foreign key constraint
            $table->foreign('school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('player_list', function (Blueprint $table) {
            // Drop foreign key
            $table->dropForeign(['school_id']);
            
            // Kembalikan ke nullable
            $table->bigInteger('school_id')->unsigned()->nullable()->change();
        });
    }
};