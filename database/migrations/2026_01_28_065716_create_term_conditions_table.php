<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('term_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->year('year');
            $table->string('document'); // path PDF
            $table->enum('status', ['view', 'draft'])->default('view');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('term_conditions');
    }
};
