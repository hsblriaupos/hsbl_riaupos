<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('official_list', function (Blueprint $table) {
            // Tambah kolom category
            $table->enum('category', [
                'basket_putra', 
                'basket_putri', 
                'dancer', 
                'lainnya'
            ])->default('lainnya')->after('team_role');
            
            // Optional: Tambah index untuk performa query
            $table->index(['team_id', 'category'], 'official_team_category_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('official_list', function (Blueprint $table) {
            $table->dropIndex('official_team_category_index');
            $table->dropColumn('category');
        });
    }
};