<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTeamNameToTeamListTable extends Migration
{
    public function up()
    {
        Schema::table('team_list', function (Blueprint $table) {
            $table->string('team_name')->after('team_category')->nullable();
        });
    }

    public function down()
    {
        Schema::table('team_list', function (Blueprint $table) {
            $table->dropColumn('team_name');
        });
    }
}