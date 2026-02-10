<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('password_reset_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('email', 100);
            $table->string('new_password', 100);
            $table->text('notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->boolean('email_sent')->default(false);
            $table->timestamps();
            
            // Index for faster queries
            $table->index('user_id');
            $table->index('admin_id');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('password_reset_logs');
    }
};