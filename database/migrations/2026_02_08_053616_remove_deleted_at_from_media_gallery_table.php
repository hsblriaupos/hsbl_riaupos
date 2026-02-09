<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('media_gallery', function (Blueprint $table) {
            // Hapus kolom deleted_at jika ada
            if (Schema::hasColumn('media_gallery', 'deleted_at')) {
                $table->dropColumn('deleted_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('media_gallery', function (Blueprint $table) {
            // Tambahkan kembali kolom deleted_at jika rollback
            if (!Schema::hasColumn('media_gallery', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }
};