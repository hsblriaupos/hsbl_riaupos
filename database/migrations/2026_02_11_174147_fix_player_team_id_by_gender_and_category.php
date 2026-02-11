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
        // 1. PINDAHKAN SEMUA PLAYER PEREMPUAN KE TIM BASKET PUTRI
        // ============================================
        DB::statement("
            UPDATE player_list p
            INNER JOIN team_list t ON p.team_id = t.team_id
            INNER JOIN team_list t2 ON t2.school_name = t.school_name 
                AND t2.season = t.season
                AND t2.team_category = 'Basket Putri'
            SET 
                p.team_id = t2.team_id,
                p.category = 'putri',
                p.updated_at = NOW()
            WHERE p.gender = 'Female'
                AND p.category != 'putri'
                AND t.team_category = 'Basket Putra'
        ");
        
        // ============================================
        // 2. PASTIKAN SEMUA PLAYER PEREMPUAN DI TIM PUTRI
        // ============================================
        DB::statement("
            UPDATE player_list p
            INNER JOIN team_list t ON p.team_id = t.team_id
            SET p.category = 'putri'
            WHERE p.gender = 'Female'
                AND t.team_category = 'Basket Putri'
                AND p.category != 'putri'
        ");
        
        // ============================================
        // 3. PINDAHKAN SEMUA PLAYER LAKI-LAKI KE TIM BASKET PUTRA
        // ============================================
        DB::statement("
            UPDATE player_list p
            INNER JOIN team_list t ON p.team_id = t.team_id
            INNER JOIN team_list t2 ON t2.school_name = t.school_name 
                AND t2.season = t.season
                AND t2.team_category = 'Basket Putra'
            SET 
                p.team_id = t2.team_id,
                p.category = 'putra',
                p.updated_at = NOW()
            WHERE p.gender = 'Male'
                AND p.category != 'putra'
                AND t.team_category = 'Basket Putri'
        ");
        
        // ============================================
        // 4. PASTIKAN SEMUA PLAYER LAKI-LAKI DI TIM PUTRA
        // ============================================
        DB::statement("
            UPDATE player_list p
            INNER JOIN team_list t ON p.team_id = t.team_id
            SET p.category = 'putra'
            WHERE p.gender = 'Male'
                AND t.team_category = 'Basket Putra'
                AND p.category != 'putra'
        ");
        
        // ============================================
        // 5. HAPUS PLAYER YANG TIDAK SESUAI
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
        // Tidak perlu rollback
    }
};