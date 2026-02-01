<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dancer_list', function (Blueprint $table) {
            $table->id('dancer_id');
            $table->foreignId('team_id')->constrained('team_list', 'team_id')->onDelete('cascade');
            $table->string('nik', 16);
            $table->string('name');
            $table->date('birthdate');
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('school_name');
            $table->string('grade'); // Kelas
            $table->year('sttb_year'); // Tahun STTB
            $table->decimal('height', 5, 2); // Tinggi badan (cm)
            $table->decimal('weight', 5, 2); // Berat badan (kg)
            $table->string('tshirt_size'); // Ukuran kaos
            $table->string('shoes_size'); // Ukuran sepatu
            $table->string('instagram')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_phone')->nullable();
            
            // File dokumen (simpan path)
            $table->string('birth_certificate');
            $table->string('kk');
            $table->string('shun');
            $table->string('report_identity');
            $table->string('last_report_card');
            $table->string('formal_photo');
            $table->string('assignment_letter')->nullable();
            
            // Role dalam tim (Leader/Member)
            $table->enum('role', ['Leader', 'Member'])->default('Member');
            
            // Status dan timestamps
            $table->enum('verification_status', ['unverified', 'verified', 'rejected'])->default('unverified');
            $table->timestamps();
            
            // Index
            $table->index('nik');
            $table->index('email');
            $table->index('team_id');
            $table->index('verification_status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('dancer_list');
    }
};