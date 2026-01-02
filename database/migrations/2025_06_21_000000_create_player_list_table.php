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
        Schema::create('player_list', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id(); // unsignedBigInteger, cocok utk FK
        
            $table->unsignedBigInteger('team_id')->nullable();
        
            $table->string('nik')->nullable();
            $table->string('name');
            $table->date('birthdate')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();
        
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
        
            $table->unsignedBigInteger('school')->nullable();
            $table->string('grade')->nullable();
            $table->year('sttb_year')->nullable();
        
            $table->integer('height')->nullable();
            $table->integer('weight')->nullable();
            $table->string('tshirt_size')->nullable();
            $table->string('shoes_size')->nullable();
            $table->string('basketball_position')->nullable();
            $table->string('jersey_number')->nullable();
        
            $table->string('instagram')->nullable();
            $table->string('tiktok')->nullable();
        
            $table->string('father_name')->nullable();
            $table->string('father_phone')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_phone')->nullable();
        
            $table->string('birth_certificate')->nullable();
            $table->string('kk')->nullable();
            $table->string('report_identity')->nullable();
            $table->string('shun')->nullable();
            $table->string('last_report_card')->nullable();
            $table->string('formal_photo')->nullable();
            $table->string('assignment_letter')->nullable();
        
            $table->boolean('is_finalized')->default(false);
            $table->timestamp('finalized_at')->nullable();
            $table->boolean('unlocked_by_admin')->default(false);
            $table->timestamp('unlocked_at')->nullable();
        
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_list');
    }
};
