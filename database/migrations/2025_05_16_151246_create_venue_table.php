<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venue', function (Blueprint $table) {
            $table->id();

            $table->string('venue_name')->nullable();
            $table->unsignedBigInteger('city_id')->nullable();
            $table->string('location')->nullable();
            $table->string('layout')->nullable();

            $table->timestamps();

            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('venue');
    }
};
