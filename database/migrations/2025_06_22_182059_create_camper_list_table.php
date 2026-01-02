<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('camper_list', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->unsignedBigInteger('player_id');
            $table->unsignedBigInteger('season_id');
            $table->string('selected_by')->nullable(); // atau bisa diganti jadi user_id kalau pakai foreign key
            $table->enum('camper_status', ['Selected', 'Reserve', 'Not Selected'])->default('Not Selected');
            $table->timestamps();

            // Foreign key
            $table->foreign('player_id')->references('id')->on('player_list')->onDelete('cascade');
            $table->foreign('season_id')->references('id')->on('add_data')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('camper_list');
    }
};
