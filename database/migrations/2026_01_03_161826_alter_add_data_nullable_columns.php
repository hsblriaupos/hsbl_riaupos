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
        Schema::table('add_data', function (Blueprint $table) {
            $table->string('series_name')->nullable()->change();
            $table->string('competition')->nullable()->change();
            $table->string('phase')->nullable()->change();
            $table->string('competition_type')->nullable()->change();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
