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
        // 1. KEMBALIKAN REGISTERED_BY TIM PUTRA KE BONAR
        // ============================================
        DB::statement("
            UPDATE team_list t
            INNER JOIN player_list p ON p.team_id = t.team_id
            SET t.registered_by = p.name
            WHERE t.school_name = 'MA NEGERI 1 SIAK'
                AND t.team_category = 'Basket Putra'
                AND p.role = 'Leader'
                AND p.gender = 'Male'
                AND p.name = 'bonar'
        ");
        
        // ============================================
        // 2. CEK APAKAH TIM BASKET PUTRI SUDAH ADA
        // ============================================
        $timPutra = DB::table('team_list')
            ->where('school_name', 'MA NEGERI 1 SIAK')
            ->where('team_category', 'Basket Putra')
            ->first();
            
        $timPutri = DB::table('team_list')
            ->where('school_name', 'MA NEGERI 1 SIAK')
            ->where('team_category', 'Basket Putri')
            ->first();
        
        // ============================================
        // 3. BUAT TIM BASKET PUTRI JIKA BELUM ADA
        // ============================================
        if (!$timPutri && $timPutra) {
            // Generate referral code unik
            $referralCode = 'MAN1SIAK-PUTRI-' . strtoupper(substr(md5(uniqid()), 0, 6));
            
            // Buat tim putri baru
            $newTeamId = DB::table('team_list')->insertGetId([
                'school_name' => $timPutra->school_name,
                'school_id' => $timPutra->school_id,
                'school_logo' => $timPutra->school_logo,
                'referral_code' => $referralCode,
                'competition' => $timPutra->competition,
                'season' => $timPutra->season,
                'series' => $timPutra->series,
                'team_category' => 'Basket Putri',
                'team_name' => null,
                'registered_by' => 'Laylaaa',
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
            
            // ============================================
            // 4. PINDAHKAN LAYLA KE TIM PUTRI
            // ============================================
            DB::table('player_list')
                ->where('name', 'Laylaaa')
                ->where('gender', 'Female')
                ->update([
                    'team_id' => $newTeamId,
                    'category' => 'putri',
                    'updated_at' => now()
                ]);
                
            // ============================================
            // 5. UPDATE PAYMENT PROOF KE TIM PUTRI
            // ============================================
            // Ambil payment proof dari player Layla
            $paymentProof = DB::table('player_list')
                ->where('name', 'Laylaaa')
                ->value('payment_proof');
                
            if ($paymentProof) {
                DB::table('team_list')
                    ->where('team_id', $newTeamId)
                    ->update(['payment_proof' => $paymentProof]);
            }
        }
        
        // ============================================
        // 6. UPDATE REGISTERED_BY UNTUK TIM LAINNYA
        // ============================================
        // Pastikan setiap tim punya registered_by dari Leader yang benar
        DB::statement("
            UPDATE team_list t
            INNER JOIN player_list p ON p.team_id = t.team_id
            SET t.registered_by = p.name
            WHERE p.role = 'Leader'
                AND (t.registered_by != p.name OR t.registered_by IS NULL)
        ");
    }

    public function down()
    {
        // Tidak perlu rollback
    }
};