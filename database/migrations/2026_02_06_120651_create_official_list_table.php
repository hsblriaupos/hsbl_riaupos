<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('official_list', function (Blueprint $table) {
            $table->id('official_id');
            $table->foreignId('team_id')->constrained('team_list', 'team_id')->onDelete('cascade');
            $table->foreignId('school_id')->nullable()->constrained('schools')->onDelete('set null');
            
            // Data Dasar
            $table->string('nik')->unique();
            $table->string('name');
            $table->date('birthdate');
            $table->enum('gender', ['male', 'female']);
            $table->string('email')->unique();
            $table->string('phone');
            
            // Asal & Fisik
            $table->string('school_name')->nullable(); // Backup jika school_id null
            $table->decimal('height', 5, 2)->nullable(); // dalam cm
            $table->decimal('weight', 5, 2)->nullable(); // dalam kg
            
            // Role & Ukuran
            $table->enum('team_role', [
                'Coach', 
                'Manager', 
                'Medical Support', 
                'Assistant Coach',
                'Pendamping'
            ]);
            $table->string('tshirt_size')->nullable(); // S, M, L, XL, XXL
            $table->string('shoes_size')->nullable(); // 40, 41, 42, etc
            
            // Sosial Media
            $table->string('instagram')->nullable();
            $table->string('tiktok')->nullable();
            
            // Dokumen
            $table->string('formal_photo')->nullable(); // Foto Formal
            $table->string('license_photo')->nullable(); // Foto Lisensi/Sertifikat
            $table->string('identity_card')->nullable(); // KTP/SIM
            
            // Status & Timestamps
            $table->enum('role', ['Leader', 'Member'])->default('Member');
            $table->enum('verification_status', ['unverified', 'verified', 'rejected'])->default('unverified');
            $table->boolean('is_finalized')->default(false);
            $table->timestamp('finalized_at')->nullable();
            $table->boolean('unlocked_by_admin')->default(false);
            $table->timestamp('unlocked_at')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('team_id');
            $table->index('school_id');
            $table->index('nik');
            $table->index('email');
            $table->index('verification_status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('official_list');
    }
};