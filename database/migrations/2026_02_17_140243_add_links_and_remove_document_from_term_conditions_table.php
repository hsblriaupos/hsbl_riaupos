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
        Schema::table('term_conditions', function (Blueprint $table) {
            // Tambahkan kolom links (nullable karena mungkin tidak semua data punya link)
            $table->text('links')->nullable()->after('title');
            
            // Hapus kolom document
            $table->dropColumn('document');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('term_conditions', function (Blueprint $table) {
            // Kembalikan kolom document jika rollback
            $table->string('document')->nullable();
            
            // Hapus kolom links
            $table->dropColumn('links');
        });
    }
};