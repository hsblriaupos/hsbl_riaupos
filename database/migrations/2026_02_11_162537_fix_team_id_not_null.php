<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // ============================================
        // 1. HAPUS PLAYER YANG TIDAK PUNYA TEAM
        // ============================================
        DB::table('player_list')
            ->whereNull('team_id')
            ->orWhere('team_id', 0)
            ->delete();
        
        // ============================================
        // 2. UBAH STRUCTUR - team_id WAJIB DIISI!
        // ============================================
        Schema::table('player_list', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->nullable(false)->change();
        });
        
        // ============================================
        // 3. PINDAHKAN PLAYER PEREMPUAN KE TIM PUTRI
        // ============================================
        DB::statement("
            UPDATE player_list p
            INNER JOIN team_list t ON p.team_id = t.team_id
            INNER JOIN team_list t2 ON t2.school_name = t.school_name 
                AND t2.team_category = 'Basket Putri'
                AND t2.season = t.season
            SET 
                p.team_id = t2.team_id,
                p.category = 'putri'
            WHERE p.gender = 'Female'
                AND t.team_category = 'Basket Putra'
        ");
        
        // ============================================
        // 4. PINDAHKAN PLAYER LAKI-LAKI KE TIM PUTRA
        // ============================================
        DB::statement("
            UPDATE player_list p
            INNER JOIN team_list t ON p.team_id = t.team_id
            INNER JOIN team_list t2 ON t2.school_name = t.school_name 
                AND t2.team_category = 'Basket Putra'
                AND t2.season = t.season
            SET 
                p.team_id = t2.team_id,
                p.category = 'putra'
            WHERE p.gender = 'Male'
                AND t.team_category = 'Basket Putri'
        ");
        
        // ============================================
        // 5. PASTIKAN CATEGORY SESUAI TEAM
        // ============================================
        DB::statement("
            UPDATE player_list p
            INNER JOIN team_list t ON p.team_id = t.team_id
            SET p.category = CASE
                WHEN t.team_category = 'Basket Putra' THEN 'putra'
                WHEN t.team_category = 'Basket Putri' THEN 'putri'
                ELSE p.category
            END
        ");
    }

    public function down()
    {
        Schema::table('player_list', function (Blueprint $table) {
            $table->unsignedBigInteger('team_id')->nullable()->change();
        });
    }
};