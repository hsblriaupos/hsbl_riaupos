<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('official_list', function (Blueprint $table) {
            $table->string('assignment_letter')->nullable()->after('identity_card');
        });
    }

    public function down()
    {
        Schema::table('official_list', function (Blueprint $table) {
            $table->dropColumn('assignment_letter');
        });
    }
};