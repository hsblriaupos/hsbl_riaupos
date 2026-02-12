<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('dancer_list', 'school_id')) {

            Schema::table('dancer_list', function (Blueprint $table) {
                $table->unsignedBigInteger('school_id')
                      ->nullable()
                      ->after('team_id');
            });
        }

        // Cek apakah foreign key sudah ada
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = 'dancer_list'
            AND COLUMN_NAME = 'school_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if (empty($foreignKeys)) {
            Schema::table('dancer_list', function (Blueprint $table) {
                $table->foreign('school_id')
                      ->references('id')
                      ->on('schools')
                      ->onDelete('set null')
                      ->onUpdate('cascade');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('dancer_list', 'school_id')) {

            Schema::table('dancer_list', function (Blueprint $table) {
                $table->dropForeign(['school_id']);
                $table->dropColumn('school_id');
            });
        }
    }
};
