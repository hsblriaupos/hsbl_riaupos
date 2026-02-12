<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('team_list', function ($table) {
            $table->dropUnique('team_list_school_season_category_unique');
        });
    
        DB::statement("ALTER TABLE team_list MODIFY team_category VARCHAR(50) NOT NULL");
        
        DB::statement("UPDATE team_list SET team_category = 'Basket Putra' WHERE team_category = 'Basket Putra'");
        DB::statement("UPDATE team_list SET team_category = 'Basket Putri' WHERE team_category = 'Basket Putri'");
        DB::statement("UPDATE team_list SET team_category = 'Dancer' WHERE team_category = 'Dancer'");
        
        DB::statement("ALTER TABLE team_list MODIFY team_category ENUM('Basket Putra', 'Basket Putri', 'Dancer', 'Official') COLLATE utf8mb4_unicode_ci NOT NULL");
        
        Schema::table('team_list', function ($table) {
            $table->unique(['school_name', 'season', 'team_category'], 'team_list_school_season_category_unique');
        });
    }

    public function down(): void
    {

        DB::statement("ALTER TABLE team_list MODIFY team_category VARCHAR(50) NOT NULL");
        
        DB::statement("UPDATE team_list SET team_category = 'Basket Putra' WHERE team_category = 'Official'");
        

        DB::statement("ALTER TABLE team_list MODIFY team_category ENUM('Basket Putra', 'Basket Putri', 'Dancer') COLLATE utf8mb4_unicode_ci NOT NULL");
        

        Schema::table('team_list', function ($table) {
            $table->unique(['school_name', 'season', 'team_category'], 'team_list_school_season_category_unique');
        });
    }
};