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
        Schema::table('media_news', function (Blueprint $table) {
            $table->enum('status', ['view', 'draft'])
                  ->default('view')
                  ->after('series'); // sesuaikan kalau mau taruh di kolom lain
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media_news', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
