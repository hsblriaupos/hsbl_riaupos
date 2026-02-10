<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Tujuan: Memperbaiki constraint UNIQUE agar sekolah bisa daftar untuk season berbeda
     */
    public function up(): void
    {
        // Langkah 1: Nonaktifkan foreign key checks sementara
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Langkah 2: Hapus constraint UNIQUE lama di school_name
        Schema::table('team_list', function (Blueprint $table) {
            // Drop semua unique constraint yang melibatkan school_name
            $indexes = DB::select("
                SELECT INDEX_NAME 
                FROM INFORMATION_SCHEMA.STATISTICS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'team_list' 
                AND COLUMN_NAME = 'school_name'
                AND NON_UNIQUE = 0
            ");

            foreach ($indexes as $index) {
                $table->dropUnique($index->INDEX_NAME);
            }

            // Atau cara cepat jika tahu nama indexnya:
            // $table->dropUnique('team_list_school_name_unique');
        });

        // Langkah 3: Hapus data duplikat untuk (school_name, season, team_category)
        // Simpan hanya data terbaru untuk setiap kombinasi
        $this->removeDuplicateEntries();

        // Langkah 4: Tambah composite UNIQUE constraint baru
        Schema::table('team_list', function (Blueprint $table) {
            /*
             * CONSTRAINT BARU:
             * - Sekolah TIDAK BOLEH daftar 2x untuk: season SAMA + kategori SAMA
             * - Sekolah BOLEH daftar untuk: season BERBEDA + kategori SAMA
             * - Sekolah BOLEH daftar untuk: season SAMA + kategori BERBEDA
             * 
             * Contoh:
             * ✅ SMA 1 Pekanbaru - HSBL 2026 - Basket Putra
             * ✅ SMA 1 Pekanbaru - HSBL 2027 - Basket Putra (season berbeda)
             * ✅ SMA 1 Pekanbaru - HSBL 2026 - Basket Putri (kategori berbeda)
             * ❌ SMA 1 Pekanbaru - HSBL 2026 - Basket Putra (duplikat, ERROR)
             */
            $table->unique(['school_name', 'season', 'team_category'], 'team_list_school_season_category_unique');
        });

        // Langkah 5: Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Langkah 6: Log perubahan
        $this->logMigrationChanges();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_list', function (Blueprint $table) {
            // Hapus composite unique constraint baru
            $table->dropUnique('team_list_school_season_category_unique');
            
            // Kembalikan unique constraint lama (hanya school_name)
            $table->unique(['school_name'], 'team_list_school_name_unique');
        });
    }

    /**
     * Hapus entri duplikat berdasarkan kombinasi (school_name, season, team_category)
     * Simpan hanya data dengan created_at terbaru
     */
    private function removeDuplicateEntries(): void
    {
        // Buat tabel backup jika belum ada
        if (!Schema::hasTable('team_list_backup_duplicates')) {
            Schema::create('team_list_backup_duplicates', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('original_team_id');
                $table->string('school_name');
                $table->string('season');
                $table->string('team_category');
                $table->text('team_data');
                $table->string('deletion_reason');
                $table->timestamp('deleted_at')->useCurrent();
                $table->timestamps();
            });
        }

        // Identifikasi data duplikat
        $duplicates = DB::select("
            SELECT school_name, season, team_category, COUNT(*) as count
            FROM team_list
            GROUP BY school_name, season, team_category
            HAVING count > 1
        ");

        foreach ($duplicates as $dup) {
            // Ambil semua ID untuk kombinasi ini, urutkan dari terbaru
            $allIds = DB::table('team_list')
                ->where('school_name', $dup->school_name)
                ->where('season', $dup->season)
                ->where('team_category', $dup->team_category)
                ->orderBy('created_at', 'desc')
                ->pluck('team_id')
                ->toArray();

            // Simpan ID yang akan dipertahankan (data terbaru)
            $keepId = array_shift($allIds);
            
            // Backup data yang akan dihapus
            foreach ($allIds as $idToDelete) {
                $teamData = DB::table('team_list')->where('team_id', $idToDelete)->first();
                
                DB::table('team_list_backup_duplicates')->insert([
                    'original_team_id' => $idToDelete,
                    'school_name' => $dup->school_name,
                    'season' => $dup->season,
                    'team_category' => $dup->team_category,
                    'team_data' => json_encode($teamData),
                    'deletion_reason' => 'Duplicate entry removal during migration fix',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            // Hapus data duplikat (kecuali yang terbaru)
            if (!empty($allIds)) {
                DB::table('team_list')->whereIn('team_id', $allIds)->delete();
            }
        }
    }

    /**
     * Catat perubahan yang dilakukan
     */
    private function logMigrationChanges(): void
    {
        // Buat migration log jika belum ada
        if (!Schema::hasTable('migration_logs')) {
            Schema::create('migration_logs', function (Blueprint $table) {
                $table->id();
                $table->string('migration_name');
                $table->text('description');
                $table->integer('duplicates_removed')->default(0);
                $table->json('changes_made')->nullable();
                $table->timestamps();
            });
        }

        // Hitung berapa duplikat yang dihapus
        $duplicatesRemoved = DB::table('team_list_backup_duplicates')
            ->where('deletion_reason', 'LIKE', '%Duplicate entry removal during migration fix%')
            ->count();

        // Catat di log
        DB::table('migration_logs')->insert([
            'migration_name' => 'fix_team_list_unique_constraint_for_multiple_seasons',
            'description' => 'Memperbaiki unique constraint agar sekolah bisa mendaftar untuk season/event berbeda',
            'duplicates_removed' => $duplicatesRemoved,
            'changes_made' => json_encode([
                'removed_constraint' => 'team_list_school_name_unique',
                'added_constraint' => 'team_list_school_season_category_unique',
                'constraint_columns' => ['school_name', 'season', 'team_category'],
                'effect' => 'Sekolah sekarang bisa mendaftar untuk season yang berbeda dan kategori yang berbeda dalam season yang sama'
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};