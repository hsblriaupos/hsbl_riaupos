<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Models\TeamList;
use App\Models\PlayerList;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FormPlayerController extends Controller
{
    /**
     * Tampilkan form untuk pendaftaran player DENGAN kategori
     */
    public function showPlayerFormWithCategory(Request $request, $team_id, $category)
    {
        try {
            Log::info('=== SHOW PLAYER FORM WITH CATEGORY ===');
            Log::info('Team ID: ' . $team_id);
            Log::info('Category from URL: ' . $category);

            // Validasi kategori
            if (!in_array($category, ['putra', 'putri', 'dancer'])) {
                return redirect()->route('form.team.choice')
                    ->with('error', 'Kategori tidak valid.');
            }

            // Ambil data tim
            $team = TeamList::findOrFail($team_id);
            
            // Set session
            session([
                'current_team_id' => $team_id,
                'current_player_category' => $category
            ]);

            // Tentukan kategori untuk form
            $formCategory = $category;
            
            // Tentukan role berdasarkan kondisi
            $role = 'Player';
            $isCaptain = false;
            
            // ============================================
            // 🔥 LOGIC BARU: Tentukan apakah bisa jadi Leader
            // ============================================
            $canBeLeader = session('current_can_be_leader', false);
            
            if ($canBeLeader) {
                // Cek apakah sudah ada Leader di kategori ini
                $existingLeaderCount = PlayerList::where('team_id', $team_id)
                    ->where('category', $category)
                    ->where('role', 'Leader')
                    ->count();
                    
                Log::info('Existing Leader count in ' . $category . ': ' . $existingLeaderCount);
                
                // Jika belum ada Leader di kategori ini, bisa jadi Leader
                if ($existingLeaderCount === 0) {
                    $role = 'Leader';
                    $isCaptain = true;
                    Log::info('✅ User CAN register as Leader (no leader in category yet)');
                } else {
                    Log::info('❌ User must register as Player (leader already exists in category)');
                }
            } else {
                Log::info('❌ User must register as Player (cannot be leader from session)');
            }
            
            Log::info('Final Role: ' . $role . ', isCaptain: ' . ($isCaptain ? 'true' : 'false'));

            // Ambil data sekolah
            $school = School::where('school_name', $team->school_name)->first();

            // Ambil enum gender
            try {
                $col = DB::selectOne("SHOW COLUMNS FROM player_list WHERE Field = 'gender'");
                preg_match("/^enum\((.*)\)$/", $col->Type, $matches);
                $genderOptions = collect(explode(',', str_replace("'", '', $matches[1])));
            } catch (\Exception $e) {
                $genderOptions = collect(['Male', 'Female']);
            }

            // Data lainnya
            $tshirtSizes = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
            $shoesSizes = range(36, 46);
            $basketballPositions = [
                'Point Guard (PG)',
                'Shooting Guard (SG)',
                'Small Forward (SF)',
                'Power Forward (PF)',
                'Center (C)'
            ];
            $grades = ['X', 'XI', 'XII'];

            // Ambil referral code jika sudah ada
            $referralCode = ($team->referral_code && $team->referral_code !== '') ? $team->referral_code : null;

            return view('user.form.form_player', compact(
                'team',
                'school',
                'category',
                'role',
                'isCaptain',
                'genderOptions',
                'tshirtSizes',
                'shoesSizes',
                'basketballPositions',
                'grades',
                'referralCode'
            ));

        } catch (\Exception $e) {
            Log::error('❌ Error in showPlayerFormWithCategory: ' . $e->getMessage());
            return redirect()->route('form.team.choice')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan form untuk pendaftaran player (legacy method)
     */
    public function showPlayerForm(Request $request, $team_id = null)
    {
        try {
            // Jika team_id dikirim via parameter
            if ($team_id) {
                // Ambil data tim
                $team = TeamList::findOrFail($team_id);
                
                // Tentukan kategori default berdasarkan team_category
                $defaultCategory = 'putra'; // default
                if (str_contains(strtolower($team->team_category), 'putri')) {
                    $defaultCategory = 'putri';
                } elseif ($team->team_category == 'Dancer') {
                    $defaultCategory = 'dancer';
                }
                
                return $this->showPlayerFormWithCategory($request, $team_id, $defaultCategory);
            } else {
                // Ambil dari session
                $team_id = $request->session()->get('current_team_id');

                if (!$team_id) {
                    // Cek session lain
                    $team_id = $request->session()->get('created_team_id') ??
                        $request->session()->get('joining_team_id');

                    if (!$team_id) {
                        return redirect()->route('form.team.choice')
                            ->with('error', 'Silakan daftarkan atau bergabung dengan tim terlebih dahulu.');
                    }
                }
                
                // Ambil kategori dari session
                $category = session('current_player_category', 'putra');
                return $this->showPlayerFormWithCategory($request, $team_id, $category);
            }
        } catch (\Exception $e) {
            Log::error('❌ Error in showPlayerForm: ' . $e->getMessage());
            return redirect()->route('form.team.choice')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Proses pendaftaran player
     */
    public function storePlayer(Request $request)
    {
        try {
            Log::info('=== STORE PLAYER START ===');
            
            // Ambil team_id
            $teamId = $request->input('team_id') ??
                session('current_team_id') ??
                session('created_team_id') ??
                session('joining_team_id');

            if (!$teamId) {
                return redirect()->route('form.team.choice')
                    ->with('error', 'Tim tidak ditemukan. Silakan daftar ulang.');
            }

            Log::info('Processing player for team_id: ' . $teamId);

            // Ambil data tim
            $team = TeamList::findOrFail($teamId);
            
            // Ambil kategori dari request atau session
            $category = $request->input('category') ?? session('current_player_category', 'putra');
            
            Log::info('Player category: ' . $category);

            // ============================================
            // 🔥 PERBAIKAN UTAMA: TENTUKAN ROLE
            // ============================================
            // Cek apakah sudah ada Leader di kategori ini
            $existingLeaderCount = PlayerList::where('team_id', $teamId)
                ->where('category', $category)
                ->where('role', 'Leader')
                ->count();

            Log::info('Existing Leader count in category: ' . $existingLeaderCount);
            Log::info('Team payment status: ' . ($team->is_leader_paid ? 'paid' : 'not paid'));

            // Jika tim BELUM bayar dan BELUM ada Leader di kategori ini, maka ini adalah Kapten
            // Jika tim SUDAH bayar dan BELUM ada Leader di kategori ini, juga bisa jadi Leader
            $isCaptain = ($existingLeaderCount === 0);
            $teamRole = $isCaptain ? 'Leader' : 'Player';

            Log::info('🎯 Player role determined as: ' . $teamRole . ', isCaptain: ' . ($isCaptain ? 'true' : 'false'));

            // ============================================
            // VALIDASI RULES
            // ============================================
            $rules = [
                'nik' => 'required|unique:player_list,nik',
                'name' => 'required|string|max:255',
                'birthdate' => 'required|date',
                'gender' => 'required|in:Male,Female',
                'email' => 'required|email|unique:player_list,email',
                'phone' => 'required|string|max:20',
                'grade' => 'required|string|max:10',
                'sttb_year' => 'required|digits:4|integer|min:2000|max:' . (date('Y') + 1),
                'height' => 'required|numeric|min:100|max:250',
                'weight' => 'required|numeric|min:30|max:150',
                'tshirt_size' => 'required|string|max:10',
                'shoes_size' => 'required|string|max:10',
                'instagram' => 'nullable|string|max:255',
                'tiktok' => 'nullable|string|max:255',
                'father_name' => 'nullable|string|max:255',
                'father_phone' => 'nullable|string|max:20',
                'mother_name' => 'nullable|string|max:255',
                'mother_phone' => 'nullable|string|max:20',

                // File uploads
                'birth_certificate' => 'required|file|mimes:pdf|max:1024',
                'kk' => 'required|file|mimes:pdf|max:1024',
                'shun' => 'required|file|mimes:pdf|max:1024',
                'report_identity' => 'required|file|mimes:pdf|max:1024',
                'last_report_card' => 'required|file|mimes:pdf|max:1024',
                'formal_photo' => 'required|file|mimes:jpg,jpeg,png|max:1024',
                'assignment_letter' => 'nullable|file|mimes:pdf|max:1024',
            ];

            // 🔥 TAMBAHKAN payment_proof HANYA untuk Leader/Kapten
            if ($isCaptain) {
                $rules['payment_proof'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
                Log::info('💰 PAYMENT PROOF REQUIRED FOR LEADER');
            } else {
                Log::info('✅ No payment proof required for Player');
            }

            // Tambahkan field basket untuk non-dancer
            if ($category !== 'dancer') {
                $rules['basketball_position'] = 'nullable|string|max:50';
                $rules['jersey_number'] = 'nullable|numeric|min:0|max:99';
            }

            $validated = $request->validate($rules);

            Log::info('✅ Player validation passed');

            // ============================================
            // AMBIL DATA SEKOLAH
            // ============================================
            $school = School::where('school_name', $team->school_name)->first();

            if (!$school) {
                $school = School::create([
                    'school_name' => $team->school_name,
                    'category_name' => 'SMA',
                    'type' => 'SWASTA',
                    'city_id' => 1,
                ]);
            }

            // ============================================
            // GENERATE NAMA FILE
            // ============================================
            $schoolSlug = Str::slug($team->school_name);
            $playerSlug = Str::slug($validated['name']);
            $timestamp = time();

            // Fungsi helper untuk menyimpan file
            $saveFile = function ($field, $folder) use ($request, $schoolSlug, $playerSlug, $timestamp) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $extension = $file->getClientOriginalExtension();
                    $filename = "{$schoolSlug}_{$playerSlug}_{$field}_{$timestamp}.{$extension}";

                    $path = $file->storeAs($folder, $filename, 'public');
                    Log::info('📁 File saved: ' . $field . ' -> ' . $path);
                    return $path;
                }
                return null;
            };

            // Buat folder
            Storage::disk('public')->makeDirectory('player_docs');
            Storage::disk('public')->makeDirectory('payment_proofs');

            // ============================================
            // 🔥 BAGIAN PENTING: SIMPAN BUKTI PEMBAYARAN & GENERATE REFERRAL CODE
            // ============================================
            $paymentProofPath = null;
            $referralCodeGenerated = null;
            
            Log::info('=== PAYMENT PROCESSING ===');
            Log::info('Is Captain: ' . ($isCaptain ? 'YES' : 'NO'));
            Log::info('Has Payment File: ' . ($request->hasFile('payment_proof') ? 'YES' : 'NO'));
            Log::info('Current Team Referral Code: ' . ($team->referral_code ?: 'EMPTY'));
            
            if ($isCaptain && $request->hasFile('payment_proof')) {
                Log::info('💰 PROCESSING PAYMENT FOR LEADER...');
                
                // 1. Simpan bukti pembayaran PLAYER (Leader)
                $paymentFile = $request->file('payment_proof');
                $paymentExtension = $paymentFile->getClientOriginalExtension();
                $paymentFilename = "payment_{$schoolSlug}_{$playerSlug}_{$timestamp}.{$paymentExtension}";
                
                $paymentProofPath = $paymentFile->storeAs('payment_proofs', $paymentFilename, 'public');
                Log::info('✅ Payment proof saved: ' . $paymentProofPath);
                
                // 2. GENERATE REFERRAL CODE JIKA MASIH KOSONG
                if (!$team->referral_code || $team->referral_code === '') {
                    Log::info('🔑 GENERATING NEW REFERRAL CODE...');
                    $baseSlug = Str::slug($team->school_name);
                    $referralCodeGenerated = strtoupper($baseSlug) . '-' . strtoupper(Str::random(6));
                    
                    // Update SEMUA tim di sekolah yang sama (season sama) dengan referral code yang sama
                    $updatedCount = TeamList::where('school_name', $team->school_name)
                        ->where('season', $team->season)
                        ->update([
                            'referral_code' => $referralCodeGenerated,
                            'is_leader_paid' => true,
                            'payment_status' => 'paid',
                            'payment_date' => now(),
                            'payment_proof' => $paymentProofPath // Simpan di team juga
                        ]);
                        
                    Log::info('✅ Referral code generated: ' . $referralCodeGenerated);
                    Log::info('✅ Teams updated with new code: ' . $updatedCount);
                    
                    // Simpan ke session
                    session(['player_referral_code' => $referralCodeGenerated]);
                    Log::info('✅ Referral code saved to session: ' . $referralCodeGenerated);
                } else {
                    $referralCodeGenerated = $team->referral_code;
                    session(['player_referral_code' => $referralCodeGenerated]);
                    Log::info('✅ Using existing referral code: ' . $referralCodeGenerated);
                    
                    // Update payment proof untuk team ini
                    $team->update([
                        'payment_proof' => $paymentProofPath,
                        'is_leader_paid' => true,
                        'payment_status' => 'paid',
                        'payment_date' => now(),
                    ]);
                }
                
                Log::info('✅ Team payment status updated to PAID');
                
                // 3. RELOAD TEAM DATA untuk dapat data terbaru
                $team = TeamList::find($teamId);
                Log::info('✅ Team reloaded. New referral code: ' . ($team->referral_code ?: 'EMPTY'));
                Log::info('✅ Team is_leader_paid: ' . ($team->is_leader_paid ? 'YES' : 'NO'));
            } elseif ($isCaptain) {
                Log::error('❌ ERROR: Leader but no payment file uploaded!');
                return back()->withErrors(['payment_proof' => 'Sebagai Kapten, Anda wajib mengupload bukti pembayaran.'])->withInput();
            } else {
                Log::info('✅ Player registration (no payment required)');
            }

            // ============================================
            // SIMPAN DATA PLAYER
            // ============================================
            $playerData = [
                'team_id' => $teamId,
                'category' => $category,
                'role' => $teamRole,
                'nik' => $validated['nik'],
                'name' => $validated['name'],
                'birthdate' => $validated['birthdate'],
                'gender' => $validated['gender'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'school_id' => $school->id,
                'grade' => $validated['grade'],
                'sttb_year' => $validated['sttb_year'],
                'height' => $validated['height'],
                'weight' => $validated['weight'],
                'tshirt_size' => $validated['tshirt_size'],
                'shoes_size' => $validated['shoes_size'],
                'instagram' => $validated['instagram'] ?? null,
                'tiktok' => $validated['tiktok'] ?? null,
                'father_name' => $validated['father_name'] ?? null,
                'father_phone' => $validated['father_phone'] ?? null,
                'mother_name' => $validated['mother_name'] ?? null,
                'mother_phone' => $validated['mother_phone'] ?? null,

                // File paths
                'birth_certificate' => $saveFile('birth_certificate', 'player_docs'),
                'kk' => $saveFile('kk', 'player_docs'),
                'shun' => $saveFile('shun', 'player_docs'),
                'report_identity' => $saveFile('report_identity', 'player_docs'),
                'last_report_card' => $saveFile('last_report_card', 'player_docs'),
                'formal_photo' => $saveFile('formal_photo', 'player_docs'),
                'assignment_letter' => $saveFile('assignment_letter', 'player_docs'),
                'payment_proof' => $paymentProofPath, // Hanya ada jika Leader
            ];

            // Tambahkan field basket hanya untuk non-dancer
            if ($category !== 'dancer') {
                $playerData['basketball_position'] = $validated['basketball_position'] ?? null;
                $playerData['jersey_number'] = $validated['jersey_number'] ?? null;
            }

            // Simpan data player
            $player = PlayerList::create($playerData);
            Log::info('✅ Player created with ID: ' . $player->id . ', Role: ' . $teamRole);

            // ============================================
            // UPDATE NAMA REGISTERED_BY DI TIM (jika Leader)
            // ============================================
            if ($isCaptain && $team->registered_by !== $validated['name']) {
                $team->update(['registered_by' => $validated['name']]);
                Log::info('✅ Updated team registered_by to: ' . $validated['name']);
            }

            // ============================================
            // CLEAR SESSION
            // ============================================
            $sessionKeys = [
                'current_team_id',
                'created_team_id',
                'joining_team_id',
                'registered_by_name',
                'is_first_team_for_school',
                'team_paid',
                'current_can_be_leader',
                'current_player_category',
                'join_referral_code'
            ];

            foreach ($sessionKeys as $key) {
                if (session()->has($key)) {
                    session()->forget($key);
                    Log::info('🗑️ Cleared session key: ' . $key);
                }
            }

            // Simpan data untuk halaman sukses
            session([
                'player_registered' => true,
                'player_team_id' => $teamId,
                'player_id' => $player->id,
                'player_name' => $validated['name'],
                'player_role' => $teamRole,
                'is_captain_registered' => $isCaptain,
            ]);

            // Ambil referral code terbaru dari TIM yang sudah di-reload
            $referralCodeForSuccess = $team->referral_code;
            
            if ($referralCodeForSuccess && $referralCodeForSuccess !== '') {
                session(['player_referral_code' => $referralCodeForSuccess]);
                Log::info('✅ Final referral code for success page: ' . $referralCodeForSuccess);
            } else {
                Log::info('ℹ️ No referral code available for success page');
            }

            Log::info('=== REDIRECTING TO SUCCESS PAGE ===');
            Log::info('Team ID: ' . $teamId);
            Log::info('Player ID: ' . $player->id);
            Log::info('Player Role: ' . $teamRole);
            Log::info('Referral Code: ' . ($team->referral_code ?: 'EMPTY'));
            Log::info('Is Captain: ' . ($isCaptain ? 'YES' : 'NO'));

            // ============================================
            // REDIRECT KE HALAMAN SUKSES
            // ============================================
            return redirect()->route('form.player.success', [
                'team_id' => $teamId,
                'player_id' => $player->id
            ])->with('success', '🎉 Pendaftaran berhasil!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('❌ Validation error in storePlayer: ', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('❌ Error in storePlayer: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Halaman sukses pendaftaran player
     */
    public function showSuccessPage($team_id, $player_id)
    {
        try {
            Log::info('=== SHOW SUCCESS PAGE START ===');
            Log::info('Team ID from URL: ' . $team_id);
            Log::info('Player ID from URL: ' . $player_id);
            
            // Ambil data tim
            $team = TeamList::find($team_id);
            if (!$team) {
                Log::error('❌ Team not found: ' . $team_id);
                return redirect()->route('form.team.choice')
                    ->with('error', 'Tim tidak ditemukan.');
            }
            
            // Ambil data player
            $player = PlayerList::find($player_id);
            if (!$player) {
                Log::error('❌ Player not found: ' . $player_id);
                return redirect()->route('form.team.choice')
                    ->with('error', 'Data pemain tidak ditemukan.');
            }
            
            Log::info('✅ Team found: ' . $team->school_name);
            Log::info('✅ Player found: ' . $player->name);
            Log::info('✅ Player Role from DB: ' . $player->role);
            
            // DEBUG DETAIL
            Log::info('=== SUCCESS PAGE DEBUG ===');
            Log::info('Player Role from DB: ' . $player->role);
            Log::info('Team Referral Code: ' . ($team->referral_code ?: 'EMPTY/NULL'));
            Log::info('Team is_leader_paid: ' . ($team->is_leader_paid ? 'YES' : 'NO'));
            Log::info('Team payment_proof: ' . ($team->payment_proof ? 'EXISTS' : 'NULL'));
            
            // Cek apakah ada Leader lain di tim yang sama
            $otherLeaders = PlayerList::where('team_id', $team_id)
                ->where('role', 'Leader')
                ->where('id', '!=', $player_id)
                ->count();
                
            Log::info('Other Leaders in team: ' . $otherLeaders);
            
            // Ambil data sekolah
            $school = School::find($player->school_id);
            
            // 🔥 Ambil referral code dari TEAM
            $referralCode = $team->referral_code;
            
            // Filter empty string
            if ($referralCode === '' || $referralCode === null) {
                $referralCode = null;
                Log::info('ℹ️ Referral code filtered to NULL');
            } else {
                Log::info('✅ Referral code is valid: ' . $referralCode);
            }
            
            // Cek apakah ini Kapten (LEADER)
            $isCaptain = ($player->role === 'Leader');
            Log::info('Is Captain: ' . ($isCaptain ? 'YES' : 'NO'));

            // Tampilkan pesan sesuai role
            if ($isCaptain) {
                $successMessage = '🎉 Selamat! Anda telah terdaftar sebagai KAPTEN tim ' . $team->school_name . '.';
                
                if ($referralCode && $referralCode !== '') {
                    $instructions = 'Sebagai Kapten, Anda telah berhasil membayar biaya registrasi tim.';
                    Log::info('✅ Captain WITH referral code: ' . $referralCode);
                } else {
                    $instructions = 'Sebagai Kapten, Anda telah berhasil mendaftar. Referral code akan dibuat setelah pembayaran diverifikasi.';
                    Log::info('ℹ️ Captain WITHOUT referral code');
                }
            } else {
                $successMessage = '🎉 Selamat! Anda telah bergabung dengan tim ' . $team->school_name . '.';
                $instructions = 'Anda telah terdaftar sebagai anggota tim. Biaya sudah ditanggung oleh Kapten.';
            }
            
            Log::info('=== SHOW SUCCESS PAGE END ===');

            return view('user.form.form_player_success', compact(
                'team',
                'player',
                'school',
                'referralCode',
                'isCaptain',
                'successMessage',
                'instructions'
            ))->with('success', session('success'));
            
        } catch (\Exception $e) {
            Log::error('❌ Error in showSuccessPage: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('form.team.choice')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Cek NIK unik (AJAX)
     */
    public function checkNik(Request $request)
    {
        $request->validate([
            'nik' => 'required|string'
        ]);

        $exists = PlayerList::where('nik', $request->nik)->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'NIK sudah terdaftar' : 'NIK tersedia'
        ]);
    }

    /**
     * Cek email unik (AJAX)
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $exists = PlayerList::where('email', $request->email)->exists();

        return response()->json([
            'exists' => $exists,
            'message' => $exists ? 'Email sudah terdaftar' : 'Email tersedia'
        ]);
    }

    /**
     * Cek apakah player sudah ada di tim
     */
    public function checkLeaderExists(Request $request)
    {
        try {
            $request->validate([
                'team_id' => 'required|exists:team_list,team_id',
                'category' => 'required|in:putra,putri,dancer'
            ]);

            $team = TeamList::find($request->team_id);
            
            if (!$team) {
                return response()->json([
                    'exists' => false,
                    'role' => 'Player'
                ]);
            }

            // Cek apakah sudah ada Leader di kategori ini
            $existingLeaderCount = PlayerList::where('team_id', $request->team_id)
                ->where('category', $request->category)
                ->where('role', 'Leader')
                ->count();

            // Cek apakah tim sudah bayar
            $hasLeaderPaid = $team->is_leader_paid;
            
            // Jika tim SUDAH bayar dan BELUM ada Leader di kategori ini, maka bisa jadi Leader
            // Jika tim BELUM bayar, tidak bisa jadi Leader (harus ada yang bayar dulu)
            $canBeLeader = ($hasLeaderPaid && $existingLeaderCount === 0);

            return response()->json([
                'exists' => !$canBeLeader,
                'role' => $canBeLeader ? 'Leader' : 'Player',
                'has_paid_leader' => $hasLeaderPaid,
                'existing_leader_count' => $existingLeaderCount
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Error in checkLeaderExists: ' . $e->getMessage());
            return response()->json([
                'exists' => false,
                'role' => 'Player',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Cek status pembayaran tim
     */
    public function checkTeamPayment(Request $request)
    {
        try {
            $request->validate([
                'team_id' => 'required|exists:team_list,team_id'
            ]);

            $team = TeamList::find($request->team_id);

            if (!$team) {
                return response()->json([
                    'paid' => false,
                    'message' => 'Tim tidak ditemukan'
                ]);
            }

            $hasPaidLeader = $team->is_leader_paid;
            $referralCode = ($hasPaidLeader && $team->referral_code !== '') ? $team->referral_code : null;

            return response()->json([
                'paid' => $hasPaidLeader,
                'referral_code' => $referralCode,
                'message' => $hasPaidLeader ?
                    'Tim sudah memiliki Kapten yang membayar' :
                    'Tim belum memiliki Kapten yang membayar'
            ]);
        } catch (\Exception $e) {
            Log::error('❌ Error in checkTeamPayment: ' . $e->getMessage());
            return response()->json([
                'paid' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}