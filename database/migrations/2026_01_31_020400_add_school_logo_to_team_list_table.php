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
        Schema::table('team_list', function (Blueprint $table) {
            // Tambahkan kolom school_logo jika belum ada
            if (!Schema::hasColumn('team_list', 'school_logo')) {
                $table->string('school_logo', 255)->nullable()->after('team_category');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_list', function (Blueprint $table) {
            // Hapus kolom jika rollback
            if (Schema::hasColumn('team_list', 'school_logo')) {
                $table->dropColumn('school_logo');
            }
        });
    }
};