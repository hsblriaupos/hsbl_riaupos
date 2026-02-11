<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Update player_list
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
        
        // 2. Update dancer_list
        DB::statement("
            UPDATE dancer_list d
            INNER JOIN team_list t ON d.team_id = t.team_id
            SET 
                d.school_name = t.school_name,
                d.school_id = t.school_id
            WHERE d.team_id IS NOT NULL
        ");
        
        // 3. Update official_list
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
    }

    public function down()
    {
        // Gausah diisi, aman aja
    }
};