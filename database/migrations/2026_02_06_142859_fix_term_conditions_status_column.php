<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Periksa tipe data kolom status
        Schema::table('term_conditions', function (Blueprint $table) {
            // Ubah kolom status menjadi string dengan length yang cukup
            $table->string('status', 20)->default('active')->change();
            
            // Tambahkan constraint enum jika perlu
            // $table->enum('status', ['active', 'inactive', 'draft', 'archived'])->default('active')->change();
        });
        
        // Update data yang mungkin rusak
        DB::table('term_conditions')
            ->whereNull('status')
            ->orWhere('status', '')
            ->update(['status' => 'active']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('term_conditions', function (Blueprint $table) {
            // Kembalikan ke tipe data sebelumnya jika perlu
            $table->string('status', 10)->default('active')->change();
        });
    }
};