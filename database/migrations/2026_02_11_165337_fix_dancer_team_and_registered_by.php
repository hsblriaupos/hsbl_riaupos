<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // ============================================
        // 1. KEMBALIKAN REGISTERED_BY TIM PUTRA
        // ============================================
        DB::statement("
            UPDATE team_list t
            INNER JOIN player_list p ON p.team_id = t.team_id
            SET t.registered_by = p.name
            WHERE t.team_category = 'Basket Putra'
                AND p.role = 'Leader'
                AND p.gender = 'Male'
        ");
        
        // ============================================
        // 2. KEMBALIKAN REGISTERED_BY TIM PUTRI
        // ============================================
        DB::statement("
            UPDATE team_list t
            INNER JOIN player_list p ON p.team_id = t.team_id
            SET t.registered_by = p.name
            WHERE t.team_category = 'Basket Putri'
                AND p.role = 'Leader'
                AND p.gender = 'Female'
        ");
        
        // ============================================
        // 3. BUAT TIM DANCER UNTUK MA NEGERI 1 SIAK
        // ============================================
        $timPutra = DB::table('team_list')
            ->where('school_name', 'MA NEGERI 1 SIAK')
            ->where('team_category', 'Basket Putra')
            ->first();
            
        if ($timPutra) {
            // Cek apakah sudah ada tim dancer
            $timDancer = DB::table('team_list')
                ->where('school_name', 'MA NEGERI 1 SIAK')
                ->where('team_category', 'Dancer')
                ->first();
                
            if (!$timDancer) {
                // Buat tim dancer baru
                $newTeamId = DB::table('team_list')->insertGetId([
                    'school_name' => $timPutra->school_name,
                    'school_id' => $timPutra->school_id,
                    'school_logo' => $timPutra->school_logo,
                    'referral_code' => 'MAN1SIAK-DNC-' . strtoupper(substr(md5(uniqid()), 0, 6)),
                    'competition' => $timPutra->competition,
                    'season' => $timPutra->season,
                    'series' => $timPutra->series,
                    'team_category' => 'Dancer',
                    'team_name' => null,
                    'registered_by' => 'putriii',
                    'locked_status' => 'unlocked',
                    'verification_status' => 'unverified',
                    'recommendation_letter' => null,
                    'koran' => null,
                    'jersey_home' => null,
                    'jersey_away' => null,
                    'jersey_alternate' => null,
                    'is_leader_paid' => 1,
                    'payment_status' => 'paid',
                    'payment_date' => now(),
                    'payment_proof' => null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                // Pindahkan dancer ke tim dancer
                DB::table('dancer_list')
                    ->where('school_name', 'MA NEGERI 1 SIAK')
                    ->update(['team_id' => $newTeamId]);
            } else {
                // Pindahkan dancer ke tim dancer yang sudah ada
                DB::table('dancer_list')
                    ->where('school_name', 'MA NEGERI 1 SIAK')
                    ->update(['team_id' => $timDancer->team_id]);
            }
        }
        
        // ============================================
        // 4. PINDAHKAN SEMUA DANCER KE TIM DANCER MASING-MASING
        // ============================================
        DB::statement("
            UPDATE dancer_list d
            INNER JOIN team_list t ON t.school_name = d.school_name 
                AND t.team_category = 'Dancer'
                AND t.season = (SELECT season FROM team_list WHERE team_id = d.team_id LIMIT 1)
            SET d.team_id = t.team_id
            WHERE d.team_id != t.team_id
        ");
    }

    public function down()
    {
        // Tidak perlu rollback
    }
};