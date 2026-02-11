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
        // 1. FIX PLAYER_LIST - UPDATE berdasarkan team_id
        // ============================================
        DB::statement("
            UPDATE player_list p
            INNER JOIN team_list t ON p.team_id = t.team_id
            SET 
                p.school_name = t.school_name,
                p.school_id = t.school_id,
                p.category = CASE 
                    WHEN t.team_category = 'Basket Putra' THEN 'putra'
                    WHEN t.team_category = 'Basket Putri' THEN 'putri'
                    ELSE 'putra'
                END
            WHERE p.team_id IS NOT NULL
        ");
        
        // ============================================
        // 2. FIX DANCER_LIST - UPDATE berdasarkan team_id
        // ============================================
        DB::statement("
            UPDATE dancer_list d
            INNER JOIN team_list t ON d.team_id = t.team_id
            SET 
                d.school_name = t.school_name,
                d.school_id = t.school_id
            WHERE d.team_id IS NOT NULL
        ");
        
        // ============================================
        // 3. FIX OFFICIAL_LIST - UPDATE berdasarkan team_id
        // ============================================
        DB::statement("
            UPDATE official_list o
            INNER JOIN team_list t ON o.team_id = t.team_id
            SET 
                o.school_name = t.school_name,
                o.school_id = t.school_id,
                o.category = CASE 
                    WHEN t.team_category = 'Basket Putra' THEN 'basket_putra'
                    WHEN t.team_category = 'Basket Putri' THEN 'basket_putri'
                    WHEN t.team_category = 'Dancer' THEN 'dancer'
                    ELSE 'lainnya'
                END
            WHERE o.team_id IS NOT NULL
        ");
        
        // ============================================
        // 4. HAPUS DATA YANG TIDAK VALID
        // ============================================
        // Hapus player yang team_id-nya ga ada
        DB::statement("
            DELETE FROM player_list 
            WHERE team_id NOT IN (SELECT team_id FROM team_list)
        ");
        
        // Hapus dancer yang team_id-nya ga ada
        DB::statement("
            DELETE FROM dancer_list 
            WHERE team_id NOT IN (SELECT team_id FROM team_list)
        ");
        
        // Hapus official yang team_id-nya ga ada
        DB::statement("
            DELETE FROM official_list 
            WHERE team_id NOT IN (SELECT team_id FROM team_list)
        ");
    }

    public function down()
    {
        // Ga usah diisi, aman
    }
};