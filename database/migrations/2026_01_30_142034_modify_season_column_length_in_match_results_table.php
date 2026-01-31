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
        Schema::table('match_results', function (Blueprint $table) {
            // Ubah panjang kolom season menjadi 100 karakter
            $table->string('season', 100)->change();
            
            // Pastikan kolom lain juga cukup panjang
            $table->string('competition', 150)->change();
            $table->string('competition_type', 100)->change();
            $table->string('series', 100)->change();
            $table->string('phase', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_results', function (Blueprint $table) {
            // Kembalikan ke panjang semula jika perlu
            $table->string('season', 20)->change();
            $table->string('competition', 100)->change();
            $table->string('competition_type', 50)->change();
            $table->string('series', 50)->change();
            $table->string('phase', 50)->change();
        });
    }
};