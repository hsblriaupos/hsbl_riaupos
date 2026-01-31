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
        Schema::table('team_list', function (Blueprint $table) {
            // Cek apakah kolom sudah ada (untuk keamanan)
            if (!Schema::hasColumn('team_list', 'payment_proof')) {
                // Tambah kolom payment_proof setelah payment_date
                $table->string('payment_proof', 255)->nullable()->after('payment_date');
            }
            
            // Jika perlu, tambahkan juga kolom lain yang belum ada
            if (!Schema::hasColumn('team_list', 'payment_status')) {
                $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending')->after('payment_proof');
            }
            
            if (!Schema::hasColumn('team_list', 'is_leader_paid')) {
                $table->boolean('is_leader_paid')->default(false)->after('payment_status');
            }
            
            if (!Schema::hasColumn('team_list', 'payment_date')) {
                $table->dateTime('payment_date')->nullable()->after('is_leader_paid');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('team_list', function (Blueprint $table) {
            // Hapus kolom jika rollback
            $columns = ['payment_proof', 'payment_status', 'is_leader_paid', 'payment_date'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('team_list', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};