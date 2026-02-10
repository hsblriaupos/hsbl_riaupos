<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // 1. Update data terlebih dahulu
        $this->fixData();
        
        // 2. Drop foreign key yang sudah ada (jika ada)
        $this->dropExistingForeignKey();
        
        // 3. Pastikan kolom tidak null
        Schema::table('player_list', function (Blueprint $table) {
            $table->bigInteger('school_id')->unsigned()->nullable(false)->change();
        });
        
        // 4. Tambahkan foreign key baru
        Schema::table('player_list', function (Blueprint $table) {
            $table->foreign('school_id', 'fk_player_list_school_id')
                  ->references('id')
                  ->on('schools')
                  ->onDelete('restrict')
                  ->onUpdate('cascade');
        });
    }
    
    private function fixData()
    {
        // Update data yang belum memiliki school_id
        // Via team_list.school_id
        DB::statement("
            UPDATE player_list pl
            INNER JOIN team_list tl ON pl.team_id = tl.team_id
            INNER JOIN schools s ON tl.school_id = s.id
            SET pl.school_id = s.id
            WHERE pl.school_id IS NULL OR pl.school_id = 0
        ");
        
        // Via team_list.school_name (fallback)
        DB::statement("
            UPDATE player_list pl
            INNER JOIN team_list tl ON pl.team_id = tl.team_id
            INNER JOIN schools s ON tl.school_name = s.school_name
            SET pl.school_id = s.id
            WHERE pl.school_id IS NULL OR pl.school_id = 0
        ");
        
        // Set default untuk yang masih null
        $defaultSchool = DB::table('schools')->orderBy('id')->first();
        if ($defaultSchool) {
            DB::table('player_list')
                ->whereNull('school_id')
                ->orWhere('school_id', 0)
                ->update(['school_id' => $defaultSchool->id]);
        }
    }
    
    private function dropExistingForeignKey()
    {
        // Cek foreign key yang ada
        $sql = "
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'player_list'
            AND COLUMN_NAME = 'school_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ";
        
        $constraints = DB::select($sql);
        
        foreach ($constraints as $constraint) {
            // Drop foreign key yang sudah ada
            DB::statement("ALTER TABLE player_list DROP FOREIGN KEY {$constraint->CONSTRAINT_NAME}");
        }
    }

    public function down()
    {
        // Drop foreign key dengan nama yang spesifik
        Schema::table('player_list', function (Blueprint $table) {
            $table->dropForeign('fk_player_list_school_id');
            $table->bigInteger('school_id')->unsigned()->nullable()->change();
        });
    }
};