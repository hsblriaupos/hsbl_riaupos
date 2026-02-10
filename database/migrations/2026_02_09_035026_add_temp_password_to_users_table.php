<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('temp_password')->nullable()->after('password');
            $table->timestamp('temp_password_created_at')->nullable()->after('temp_password');
            $table->timestamp('password_changed_at')->nullable()->after('temp_password_created_at');
            $table->integer('password_reset_count')->default(0)->after('password_changed_at');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['temp_password', 'temp_password_created_at', 'password_changed_at', 'password_reset_count']);
        });
    }
};