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
            if (!in_array($category, ['putra', 'putri'])) {
                return redirect()->route('form.team.choice')
                    ->with('error', 'Kategori tidak valid.');
            }

            // Ambil data tim
            $team = TeamList::findOrFail($team_id);

            Log::info('Team info:', [
                'team_id' => $team->team_id,
                'school_name' => $team->school_name,
                'team_category' => $team->team_category,
                'is_leader_paid' => $team->is_leader_paid,
                'referral_code' => $team->referral_code,
                'school_id' => $team->school_id
            ]);

            // ============================================
            // 🔥 LOGIC PENENTUAN ROLE YANG LEBIH AKURAT
            // ============================================
            $role = 'Player'; // default
            $isCaptain = false;

            // Cek session untuk menentukan apakah user boleh jadi Leader
            // Di bagian penentuan role, setelah dapat $canBeLeader dari session
$canBeLeader = session('current_can_be_leader', false);
$isNewTeamForCategory = session('is_new_team_for_category', false); // 🔥 AMBIL DARI SESSION

Log::info('Session check:', [
    'current_can_be_leader' => session('current_can_be_leader'),
    'is_new_team_for_category' => session('is_new_team_for_category'),
    'created_team_id' => session('created_team_id'),
    'join_referral_code' => session('join_referral_code'),
    'team_id_from_session' => session('current_team_id')
]);

// Jika boleh jadi Leader dari session
if ($canBeLeader) {
    // Cek apakah sudah ada Leader di kategori ini
    $existingLeaderCount = PlayerList::where('team_id', $team_id)
        ->where('category', $category)
        ->where('role', 'Leader')
        ->count();

    Log::info('Existing Leader count in ' . $category . ': ' . $existingLeaderCount);

    // Jika belum ada Leader di kategori ini, atau ini adalah kategori baru, bisa jadi Leader
    if ($existingLeaderCount === 0 || $isNewTeamForCategory) {
        $role = 'Leader';
        $isCaptain = true;
        Log::info('✅ User CAN register as Leader (from session, no leader in category yet or new category)');
    } else {
        Log::info('❌ User must register as Player (leader already exists in category)');
    }
} else {
    Log::info('❌ User cannot be leader from session');
}


            Log::info('Session check:', [
                'current_can_be_leader' => session('current_can_be_leader'),
                'created_team_id' => session('created_team_id'),
                'join_referral_code' => session('join_referral_code'),
                'team_id_from_session' => session('current_team_id')
            ]);

            // Jika boleh jadi Leader dari session
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
                    Log::info('✅ User CAN register as Leader (from session, no leader in category yet)');
                } else {
                    Log::info('❌ User must register as Player (leader already exists in category)');
                }
            } else {
                Log::info('❌ User cannot be leader from session');
            }

            Log::info('Final Role: ' . $role . ', isCaptain: ' . ($isCaptain ? 'true' : 'false'));

            // Set session untuk store method
            session([
                'current_team_id' => $team_id,
                'current_player_category' => $category,
                'current_can_be_leader' => $isCaptain // 🔥 Simpan status ini untuk store
            ]);

            // Ambil data sekolah dari team.school_id
            $school = null;
            if ($team->school_id) {
                $school = School::find($team->school_id);
                Log::info('Found school by team.school_id: ' . $team->school_id);
            }

            // Jika tidak ditemukan, cari berdasarkan nama
            if (!$school) {
                $school = School::where('school_name', $team->school_name)->first();
                Log::info('Found school by name: ' . ($school ? $school->id : 'NOT FOUND'));
            }

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
            $referralCode = (!empty($team->referral_code)) ? $team->referral_code : null;

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
     * Helper function untuk generate referral code yang unik
     */
    private function generateUniqueReferralCode($baseSlug, $teamId = null)
    {
        $maxAttempts = 5;

        for ($i = 0; $i < $maxAttempts; $i++) {
            $code = strtoupper($baseSlug) . '-' . strtoupper(Str::random(6));

            $query = TeamList::where('referral_code', $code);

            if ($teamId) {
                $query->where('team_id', '!=', $teamId);
            }

            if (!$query->exists()) {
                return $code;
            }

            // Last attempt - add timestamp
            if ($i === $maxAttempts - 1) {
                return strtoupper($baseSlug) . '-' . strtoupper(Str::random(4)) . '-' . time();
            }
        }

        return strtoupper($baseSlug) . '-' . time();
    }

    /**
     * Proses pendaftaran player (DIPERBAIKI DENGAN JERSEY UPLOAD)
     */
    public function storePlayer(Request $request)
    {
        try {
            Log::info('=== STORE PLAYER START ===');
            Log::info('Request data keys:', array_keys($request->all()));

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
            Log::info('Team payment status:', [
                'is_leader_paid' => $team->is_leader_paid,
                'referral_code' => $team->referral_code,
                'team_category' => $team->team_category,
                'school_name' => $team->school_name,
                'school_id' => $team->school_id
            ]);

            // ============================================
            // 🔥 LOGIC PENENTUAN ROLE YANG LEBIH AKURAT
            // ============================================
            $teamRole = $request->input('team_role', 'Player');
            $isCaptain = ($teamRole === 'Leader');

            Log::info('Role from form: ' . $teamRole . ', isCaptain: ' . ($isCaptain ? 'true' : 'false'));

            // Validasi: jika mengaku Leader, pastikan memang boleh jadi Leader
            if ($isCaptain) {
                // 1. Cek apakah sudah ada Leader di kategori ini
                $existingLeaderCount = PlayerList::where('team_id', $teamId)
                    ->where('category', $category)
                    ->where('role', 'Leader')
                    ->count();

                if ($existingLeaderCount > 0) {
                    Log::error('❌ ERROR: Trying to register as Leader but leader already exists');
                    return back()->withErrors(['error' => 'Tim ini sudah memiliki Leader untuk kategori ' . $category . '.'])->withInput();
                }

                // 2. Cek apakah ini dari session boleh jadi Leader
                $canBeLeaderFromSession = session('current_can_be_leader', false);

                if (!$canBeLeaderFromSession) {
                    Log::error('❌ ERROR: Trying to register as Leader but not authorized from session');
                    return back()->withErrors(['error' => 'Anda tidak berhak menjadi Leader.'])->withInput();
                }

                Log::info('✅ User authorized to be Leader from session');
            }

            // ============================================
            // 🔥 VALIDASI RULES - TAMBAHKAN JERSEY UNTUK LEADER
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
                'last_report_card' => 'required|file|mimes:pdf|max:1024',
                'formal_photo' => 'required|file|mimes:jpg,jpeg,png|max:1024',
                'assignment_letter' => 'nullable|file|mimes:pdf|max:1024',
                'terms' => 'required|accepted',
            ];

            // 🔥 TAMBAHKAN payment_proof HANYA untuk Leader
            if ($isCaptain) {
                $rules['payment_proof'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
                Log::info('💰 PAYMENT PROOF REQUIRED FOR LEADER');
            }

            // Tambahkan field basket untuk non-dancer
            if ($category !== 'dancer') {
                $rules['basketball_position'] = 'nullable|string|max:50';
                $rules['jersey_number'] = 'required|numeric|min:0|max:99'; // WAJIB DIISI
            }

            // 🔥🔥🔥 TAMBAHKAN VALIDASI JERSEY UNTUK LEADER BASKET
            if ($isCaptain && $category !== 'dancer') {
                $rules['jersey_home'] = 'nullable|file|mimes:jpg,jpeg,png|max:2048';
                $rules['jersey_away'] = 'nullable|file|mimes:jpg,jpeg,png|max:2048';
                $rules['jersey_alternate'] = 'nullable|file|mimes:jpg,jpeg,png|max:2048';
                
                // Validasi custom: minimal upload 1 foto jersey
                if (!$request->hasFile('jersey_home') && 
                    !$request->hasFile('jersey_away') && 
                    !$request->hasFile('jersey_alternate')) {
                    return back()->withErrors(['jersey' => 'Sebagai Leader, Anda wajib upload minimal 1 foto jersey tim.'])->withInput();
                }
            }

            $validated = $request->validate($rules);

            Log::info('✅ Player validation passed');

            // ============================================
            // 🔥 FIX: AMBIL/MBUAT SEKOLAH DENGAN BENAR
            // ============================================
            Log::info('🔍 Looking for school: ' . $team->school_name);

            // Cari sekolah berdasarkan school_name (PASTIKAN SATU-SATUNYA)
            $school = School::where('school_name', $team->school_name)->first();

            if (!$school) {
                Log::info('📝 Creating new school: ' . $team->school_name);
                $school = School::create([
                    'school_name' => $team->school_name,
                    'category_name' => 'SMA',
                    'type' => 'SWASTA',
                    'city_id' => 1,
                ]);
            }

            // 🔥 PASTIKAN team_list punya school_id yang benar
            if ($team->school_id != $school->id) {
                $team->update(['school_id' => $school->id]);
                Log::info('✅ Updated team.school_id from ' . $team->school_id . ' to ' . $school->id);
            }

            // 🔥 Juga update semua tim dengan nama sekolah yang sama
            TeamList::where('school_name', $team->school_name)
                ->where('school_id', '!=', $school->id)
                ->update(['school_id' => $school->id]);

            Log::info('🎯 School for player: ID=' . $school->id . ', Name=' . $school->school_name);

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
            Storage::disk('public')->makeDirectory('jersey'); // 🔥 FOLDER UNTUK JERSEY

            // ============================================
            // 🔥🔥🔥 SIMPAN FOTO JERSEY UNTUK LEADER
            // ============================================
            $jerseyHomePath = null;
            $jerseyAwayPath = null;
            $jerseyAlternatePath = null;

            if ($isCaptain && $category !== 'dancer') {
                Log::info('👕 Processing jersey uploads for Leader...');
                
                // Buat folder jersey
                Storage::disk('public')->makeDirectory('jersey');
                
                // Simpan jersey home
                if ($request->hasFile('jersey_home')) {
                    $file = $request->file('jersey_home');
                    $extension = $file->getClientOriginalExtension();
                    $filename = "{$schoolSlug}_jersey_home_{$timestamp}.{$extension}";
                    $jerseyHomePath = $file->storeAs('jersey', $filename, 'public');
                    Log::info('✅ Jersey Home saved: ' . $jerseyHomePath);
                }
                
                // Simpan jersey away
                if ($request->hasFile('jersey_away')) {
                    $file = $request->file('jersey_away');
                    $extension = $file->getClientOriginalExtension();
                    $filename = "{$schoolSlug}_jersey_away_{$timestamp}.{$extension}";
                    $jerseyAwayPath = $file->storeAs('jersey', $filename, 'public');
                    Log::info('✅ Jersey Away saved: ' . $jerseyAwayPath);
                }
                
                // Simpan jersey alternate
                if ($request->hasFile('jersey_alternate')) {
                    $file = $request->file('jersey_alternate');
                    $extension = $file->getClientOriginalExtension();
                    $filename = "{$schoolSlug}_jersey_alternate_{$timestamp}.{$extension}";
                    $jerseyAlternatePath = $file->storeAs('jersey', $filename, 'public');
                    Log::info('✅ Jersey Alternate saved: ' . $jerseyAlternatePath);
                }
            }

            // ============================================
            // 🔥 BAGIAN PENTING: SIMPAN BUKTI PEMBAYARAN & GENERATE REFERRAL CODE
            // ============================================
            $paymentProofPath = null;
            $referralCodeGenerated = null;

            Log::info('=== PAYMENT PROCESSING ===');
            Log::info('Is Captain: ' . ($isCaptain ? 'YES' : 'NO'));
            Log::info('Has Payment File: ' . ($request->hasFile('payment_proof') ? 'YES' : 'NO'));

            if ($isCaptain) {
                if (!$request->hasFile('payment_proof')) {
                    Log::error('❌ ERROR: Leader but no payment file uploaded!');
                    return back()->withErrors(['payment_proof' => 'Sebagai Kapten, Anda wajib mengupload bukti pembayaran.'])->withInput();
                }

                Log::info('💰 PROCESSING PAYMENT FOR LEADER...');

                // 1. Simpan bukti pembayaran PLAYER (Leader)
                $paymentFile = $request->file('payment_proof');
                $paymentExtension = $paymentFile->getClientOriginalExtension();
                $paymentFilename = "payment_{$schoolSlug}_{$playerSlug}_{$timestamp}.{$paymentExtension}";

                $paymentProofPath = $paymentFile->storeAs('payment_proofs', $paymentFilename, 'public');
                Log::info('✅ Payment proof saved: ' . $paymentProofPath);

                // 2. 🔥 GENERATE REFERRAL CODE JIKA KOSONG/NULL
                if (empty($team->referral_code)) {
                    Log::info('🔑 GENERATING NEW REFERRAL CODE...');

                    // Generate unique referral code
                    $baseSlug = Str::slug($team->school_name);
                    $referralCodeGenerated = $this->generateUniqueReferralCode($baseSlug, $teamId);

                    Log::info('Generated referral code: ' . $referralCodeGenerated);

                    // Update ALL teams from same school & season
                    $updatedCount = TeamList::where('school_name', $team->school_name)
                        ->where('season', $team->season)
                        ->update([
                            'referral_code' => $referralCodeGenerated,
                            'is_leader_paid' => true,
                            'payment_status' => 'paid',
                            'payment_date' => now(),
                        ]);

                    Log::info('✅ Teams updated: ' . $updatedCount);
                    session(['player_referral_code' => $referralCodeGenerated]);
                } else {
                    // Referral code already exists
                    $referralCodeGenerated = $team->referral_code;
                    session(['player_referral_code' => $referralCodeGenerated]);
                    Log::info('✅ Using existing referral code: ' . $referralCodeGenerated);

                    // Update payment status for all teams
                    TeamList::where('school_name', $team->school_name)
                        ->where('season', $team->season)
                        ->update([
                            'is_leader_paid' => true,
                            'payment_status' => 'paid',
                            'payment_date' => now(),
                        ]);
                }

                // 🔥🔥🔥 UPDATE JERSEY KE TEAM_LIST
                $teamUpdateData = [
                    'payment_proof' => $paymentProofPath,
                ];
                
                // Tambahkan jersey jika diupload
                if ($jerseyHomePath) $teamUpdateData['jersey_home'] = $jerseyHomePath;
                if ($jerseyAwayPath) $teamUpdateData['jersey_away'] = $jerseyAwayPath;
                if ($jerseyAlternatePath) $teamUpdateData['jersey_alternate'] = $jerseyAlternatePath;
                
                // Update team dengan data jersey
                $team->update($teamUpdateData);
                
                Log::info('✅ Team updated with jersey data');
                Log::info('✅ Payment processed successfully');

                // 3. RELOAD TEAM DATA untuk dapat data terbaru
                $team = TeamList::find($teamId);
                Log::info('✅ Team reloaded. Referral code: ' . ($team->referral_code ?: 'NULL/EMPTY'));
                Log::info('✅ Team is_leader_paid: ' . ($team->is_leader_paid ? 'YES' : 'NO'));
            } else {
                Log::info('✅ Player registration (no payment required)');
            }

            // ============================================
            // 🔥🔥🔥 SIMPAN DATA PLAYER DENGAN SCHOOL_ID YANG BENAR
            // ============================================
            $playerData = [
                'team_id' => $teamId,
                'school_id' => $school->id,
                'category' => $category,
                'role' => $teamRole,
                'nik' => $validated['nik'],
                'name' => $validated['name'],
                'birthdate' => $validated['birthdate'],
                'gender' => $validated['gender'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'school_name' => $team->school_name,
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
                'last_report_card' => $saveFile('last_report_card', 'player_docs'),
                'formal_photo' => $saveFile('formal_photo', 'player_docs'),
                'assignment_letter' => $saveFile('assignment_letter', 'player_docs'),
                'payment_proof' => $paymentProofPath,
            ];

            // Tambahkan field basket untuk non-dancer (SEBELUM CREATE!)
            if ($category !== 'dancer') {
                $playerData['basketball_position'] = $validated['basketball_position'] ?? null;
                $playerData['jersey_number'] = $validated['jersey_number'] ?? null;
            }

            // Simpan data player - SEKALI AJA!
            Log::info('📝 Saving player data with school_id: ' . $school->id . ', school_name: ' . $team->school_name);
            $player = PlayerList::create($playerData);
            Log::info('✅ Player created with ID: ' . $player->id .
                ', Role: ' . $teamRole .
                ', Jersey Number: ' . ($player->jersey_number ?? 'N/A') .
                ', School ID: ' . $player->school_id);

            // ============================================
            // 🔥 PERBAIKAN: UPDATE REGISTERED_BY UNTUK TIM YANG SESUAI!
            // ============================================
            if ($isCaptain) {
                // Ambil tim yang BENAR berdasarkan kategori
                $correctTeam = TeamList::where('school_name', $team->school_name)
                    ->where('season', $team->season)
                    ->where(
                        'team_category',
                        $category == 'putra' ? 'Basket Putra' : ($category == 'putri' ? 'Basket Putri' : 'Dancer')
                    )
                    ->first();

                if ($correctTeam) {
                    // Update registered_by di tim yang BENAR
                    if ($correctTeam->registered_by !== $validated['name']) {
                        $correctTeam->update(['registered_by' => $validated['name']]);
                        Log::info('✅ Updated ' . $correctTeam->team_category . ' registered_by to: ' . $validated['name']);
                    }
                } else {
                    Log::warning('⚠️ Tim ' . $category . ' tidak ditemukan untuk sekolah ' . $team->school_name);
                }
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

            if (!empty($referralCodeForSuccess)) {
                session(['player_referral_code' => $referralCodeForSuccess]);
                Log::info('✅ Final referral code for success page: ' . $referralCodeForSuccess);
            } else {
                Log::info('ℹ️ No referral code available for success page');
            }

            Log::info('=== REDIRECTING TO SUCCESS PAGE ===');
            Log::info('Team ID: ' . $teamId);
            Log::info('Player ID: ' . $player->id);
            Log::info('Player Role: ' . $teamRole);
            Log::info('Player School ID: ' . $player->school_id);
            Log::info('Referral Code: ' . ($team->referral_code ?: 'EMPTY'));
            Log::info('Is Captain: ' . ($isCaptain ? 'YES' : 'NO'));

            // ============================================
            // 🔥🔥🔥 FIX: REDIRECT KE HALAMAN SUKSES DENGAN TEAM_ID YANG BENAR
            // ============================================
            $successTeamId = $player->team_id; // PAKE team_id DARI PLAYER!

            return redirect()->route('form.player.success', [
                'team_id' => $successTeamId,
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

            Log::info('✅ Team found: ' . $team->school_name . ' (ID: ' . $team->team_id . ')');
            Log::info('✅ Player found: ' . $player->name . ' (ID: ' . $player->id . ')');
            Log::info('✅ Player Role from DB: ' . $player->role);

            // DEBUG DETAIL
            Log::info('=== SUCCESS PAGE DEBUG ===');
            Log::info('Player Role from DB: ' . $player->role);
            Log::info('Player School ID: ' . $player->school_id);
            Log::info('Player Jersey Number: ' . $player->jersey_number);
            Log::info('Team Referral Code: ' . ($team->referral_code ?: 'EMPTY/NULL'));
            Log::info('Team is_leader_paid: ' . ($team->is_leader_paid ? 'YES' : 'NO'));
            Log::info('Team Jersey Home: ' . ($team->jersey_home ? 'EXISTS' : 'NULL'));
            Log::info('Team Jersey Away: ' . ($team->jersey_away ? 'EXISTS' : 'NULL'));
            Log::info('Team Jersey Alternate: ' . ($team->jersey_alternate ? 'EXISTS' : 'NULL'));

            // Cek apakah ada Leader lain di tim yang sama
            $otherLeaders = PlayerList::where('team_id', $team_id)
                ->where('role', 'Leader')
                ->where('id', '!=', $player_id)
                ->count();

            Log::info('Other Leaders in team: ' . $otherLeaders);

            // Ambil data sekolah DARI PLAYER.SCHOOL_ID
            $school = null;
            if ($player->school_id) {
                $school = School::find($player->school_id);
                if ($school) {
                    Log::info('✅ Found school from player.school_id: ' . $school->school_name);
                } else {
                    Log::warning('⚠️ Player.school_id (' . $player->school_id . ') not found in schools table');
                    // Fallback: cari dari team
                    $school = School::where('school_name', $team->school_name)->first();
                    if ($school) {
                        Log::info('✅ Found school from team.school_name: ' . $school->school_name);
                        // Update player dengan school_id yang benar
                        $player->update(['school_id' => $school->id]);
                        Log::info('✅ Updated player.school_id to: ' . $school->id);
                    }
                }
            } else {
                // Jika player tidak punya school_id, cari dari team
                $school = School::where('school_name', $team->school_name)->first();
                if ($school) {
                    Log::info('✅ Found school for player from team: ' . $school->school_name);
                    // Update player dengan school_id
                    $player->update(['school_id' => $school->id]);
                    Log::info('✅ Set player.school_id to: ' . $school->id);
                }
            }

            // Jika masih tidak ditemukan, buat sekolah baru
            if (!$school) {
                Log::info('📝 Creating new school for player: ' . $team->school_name);
                $school = School::create([
                    'school_name' => $team->school_name,
                    'category_name' => 'SMA',
                    'type' => 'SWASTA',
                    'city_id' => 1,
                ]);

                // Update player dan team dengan school_id yang baru
                $player->update(['school_id' => $school->id]);
                $team->update(['school_id' => $school->id]);
                Log::info('✅ Created new school and updated IDs');
            }

            // 🔥 Ambil referral code dari TEAM
            $referralCode = $team->referral_code;

            // Filter menggunakan empty()
            if (empty($referralCode)) {
                $referralCode = null;
                Log::info('ℹ️ Referral code is empty or NULL');
            } else {
                Log::info('✅ Referral code is valid: ' . $referralCode);
            }

            // Cek apakah ini Kapten (LEADER)
            $isCaptain = ($player->role === 'Leader');
            Log::info('Is Captain: ' . ($isCaptain ? 'YES' : 'NO'));

            // Tampilkan pesan sesuai role
            if ($isCaptain) {
                $successMessage = '🎉 Selamat! Anda telah terdaftar sebagai KAPTEN tim ' . $team->school_name . '.';

                if (!empty($referralCode)) {
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

            Log::info('✅ Success page ready with school: ' . ($school ? $school->school_name : 'NOT FOUND'));
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
            $referralCode = ($hasPaidLeader && !empty($team->referral_code)) ? $team->referral_code : null;

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