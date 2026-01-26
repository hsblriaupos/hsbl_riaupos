<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('add_data', function (Blueprint $table) {
            $table->id();
            $table->string('season_name')->nullable();
            $table->string('series_name')->nullable();
            $table->string('competition')->nullable(); 
            $table->string('competition_type')->nullable(); 
            $table->string('phase')->nullable();
            $table->timestamps();
        });
        
    }

    public function down(): void
    {
        Schema::dropIfExists('add_data');
    }
};
