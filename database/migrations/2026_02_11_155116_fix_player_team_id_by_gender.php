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
        // 1. PINDAHKAN PLAYER PEREMPUAN KE TIM BASKET PUTRI
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
        // 2. PINDAHKAN PLAYER LAKI-LAKI KE TIM BASKET PUTRA
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
        // 3. PASTIKAN SEMUA PLAYER PUNYA CATEGORY SESUAI GENDER
        // ============================================
        DB::statement("
            UPDATE player_list p
            INNER JOIN team_list t ON p.team_id = t.team_id
            SET p.category = 'putri'
            WHERE p.gender = 'Female'
                AND t.team_category = 'Basket Putri'
        ");
        
        DB::statement("
            UPDATE player_list p
            INNER JOIN team_list t ON p.team_id = t.team_id
            SET p.category = 'putra'
            WHERE p.gender = 'Male'
                AND t.team_category = 'Basket Putra'
        ");
        
        // ============================================
        // 4. HAPUS PLAYER YANG MASIH SALAH
        // ============================================
        DB::statement("
            DELETE FROM player_list 
            WHERE (gender = 'Female' AND category != 'putri')
                OR (gender = 'Male' AND category != 'putra')
                OR team_id NOT IN (SELECT team_id FROM team_list)
        ");
    }

    public function down()
    {
        // Ga usah diisi
    }
};