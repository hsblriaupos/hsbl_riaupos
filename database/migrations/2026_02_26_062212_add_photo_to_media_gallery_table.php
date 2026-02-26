<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhotoToMediaGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media_gallery', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('file');
            // atau jika ingin menyimpan path lengkap:
            // $table->text('photo')->nullable()->after('file');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media_gallery', function (Blueprint $table) {
            $table->dropColumn('photo');
        });
    }
}