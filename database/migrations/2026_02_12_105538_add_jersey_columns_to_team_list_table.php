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
            if (!Schema::hasColumn('team_list', 'jersey_home')) {
                $table->string('jersey_home')->nullable()->after('koran');
            }
            if (!Schema::hasColumn('team_list', 'jersey_away')) {
                $table->string('jersey_away')->nullable()->after('jersey_home');
            }
            if (!Schema::hasColumn('team_list', 'jersey_alternate')) {
                $table->string('jersey_alternate')->nullable()->after('jersey_away');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_list', function (Blueprint $table) {
            $table->dropColumn(['jersey_home', 'jersey_away', 'jersey_alternate']);
        });
    }
};