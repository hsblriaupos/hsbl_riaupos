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
            // Tambahkan kolom season
            $table->string('season', 20)->nullable()->after('match_date');
            
            // Tambahkan kolom scoresheet_original_name (opsional, untuk keperluan download)
            $table->string('scoresheet_original_name')->nullable()->after('scoresheet');
            
            // Tambahkan kolom status (opsional, jika belum ada)
            if (!Schema::hasColumn('match_results', 'status')) {
                $table->enum('status', ['draft', 'publish', 'done'])->default('draft')->after('score_2');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_results', function (Blueprint $table) {
            $table->dropColumn('season');
            $table->dropColumn('scoresheet_original_name');
            
            if (Schema::hasColumn('match_results', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};