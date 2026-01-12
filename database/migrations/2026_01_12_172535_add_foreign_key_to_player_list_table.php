<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToPlayerListTable extends Migration
{
    public function up()
    {
        Schema::table('player_list', function (Blueprint $table) {
            // Pastikan dulu tipe data sama
            // $table->unsignedBigInteger('team_id')->change(); // jika perlu
            
            // Foreign key ke team_list.team_id (bukan id)
            $table->foreign('team_id')
                  ->references('team_id')  // <- INI YANG DIPERBAIKI
                  ->on('team_list')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('player_list', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
        });
    }
}