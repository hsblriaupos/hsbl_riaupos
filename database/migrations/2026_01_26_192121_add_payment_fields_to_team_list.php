<?php
// database/migrations/xxxx_add_payment_fields_to_team_list.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('team_list', function (Blueprint $table) {
            if (!Schema::hasColumn('team_list', 'is_leader_paid')) {
                $table->boolean('is_leader_paid')->default(false);
            }
            if (!Schema::hasColumn('team_list', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            }
            if (!Schema::hasColumn('team_list', 'payment_date')) {
                $table->dateTime('payment_date')->nullable();
            }
            if (!Schema::hasColumn('team_list', 'school_id')) {
                $table->unsignedBigInteger('school_id')->nullable()->after('school_name');
                $table->foreign('school_id')->references('id')->on('schools')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('team_list', function (Blueprint $table) {
            $table->dropColumn(['is_leader_paid', 'payment_status', 'payment_date']);
            $table->dropForeign(['school_id']);
            $table->dropColumn('school_id');
        });
    }
};