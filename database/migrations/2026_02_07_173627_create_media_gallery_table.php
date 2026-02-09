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
        Schema::create('media_gallery', function (Blueprint $table) {
            $table->id();
            $table->string('school_name', 200);
            $table->string('file')->comment('Format ZIP maksimal 5 GB');
            $table->string('original_filename')->nullable();
            $table->bigInteger('file_size')->default(0)->comment('Dalam bytes');
            $table->string('file_type', 50)->default('zip');
            $table->string('competition', 100)->nullable();
            $table->string('season', 50)->nullable();
            $table->string('series', 100)->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->integer('download_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
            
            // Index untuk pencarian yang cepat
            $table->index('school_name');
            $table->index('competition');
            $table->index(['season', 'series']);
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_gallery');
    }
};