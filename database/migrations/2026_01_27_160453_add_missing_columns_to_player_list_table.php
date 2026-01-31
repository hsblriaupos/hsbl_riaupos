<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('player_list', function (Blueprint $table) {
            // Tambah kolom category jika belum ada
            if (!Schema::hasColumn('player_list', 'category')) {
                $table->enum('category', ['putra', 'putri', 'dancer'])
                    ->nullable()
                    ->after('team_id');
            }
            
            // Tambah kolom role jika belum ada
            if (!Schema::hasColumn('player_list', 'role')) {
                $table->enum('role', ['Leader', 'Player'])
                    ->default('Player')
                    ->after('category');
            }
            
            // Ubah nama kolom school menjadi school_id untuk konsistensi
            if (Schema::hasColumn('player_list', 'school') && !Schema::hasColumn('player_list', 'school_id')) {
                $table->renameColumn('school', 'school_id');
            }
        });
    }

    public function down()
    {
        Schema::table('player_list', function (Blueprint $table) {
            // Drop kolom jika ada
            if (Schema::hasColumn('player_list', 'category')) {
                $table->dropColumn('category');
            }
            
            if (Schema::hasColumn('player_list', 'role')) {
                $table->dropColumn('role');
            }
            
            // Kembalikan nama kolom jika diubah
            if (Schema::hasColumn('player_list', 'school_id') && !Schema::hasColumn('player_list', 'school')) {
                $table->renameColumn('school_id', 'school');
            }
        });
    }
};