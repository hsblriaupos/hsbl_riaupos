<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Models\TeamList;
use App\Models\DancerList;
use App\Models\School;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FormDancerController extends Controller
{
    /**
     * Tampilkan form untuk pendaftaran dancer
     */
    public function showDancerForm(Request $request, $team_id)
    {
        try {
            Log::info('=== SHOW DANCER FORM ===');
            Log::info('Team ID: ' . $team_id);

            // Ambil data tim
            $team = TeamList::findOrFail($team_id);

            Log::info('Team info:', [
                'team_id' => $team->team_id,
                'school_name' => $team->school_name,
                'team_category' => $team->team_category,
                'is_leader_paid' => $team->is_leader_paid,
                'referral_code' => $team->referral_code
            ]);

            // ============================================
            // TENTUKAN ROLE DENGAN BENAR
            // ============================================
            $role = 'Member'; // default
            $isLeader = false;

            // Cek session untuk menentukan apakah user boleh jadi Leader
            $canBeLeader = session('current_can_be_leader', false);

            Log::info('Session check:', [
                'current_can_be_leader' => session('current_can_be_leader'),
                'created_team_id' => session('created_team_id'),
                'join_referral_code' => session('join_referral_code'),
                'team_id_from_session' => session('current_team_id')
            ]);

            // Jika boleh jadi Leader dari session
            if ($canBeLeader) {
                // Cek apakah sudah ada Leader dancer di tim ini
                $existingLeaderCount = DancerList::where('team_id', $team_id)
                    ->where('role', 'Leader')
                    ->count();

                Log::info('Existing Leader count in dancer team: ' . $existingLeaderCount);

                // Jika belum ada Leader dancer di tim ini
                if ($existingLeaderCount === 0) {
                    $role = 'Leader';
                    $isLeader = true;
                    Log::info('✅ User CAN register as Leader (from session, no dancer leader yet)');
                } else {
                    Log::info('❌ User must register as Member (dancer leader already exists)');
                }
            } else {
                Log::info('❌ User cannot be leader from session');
            }

            Log::info('Final Role: ' . $role . ', isLeader: ' . ($isLeader ? 'true' : 'false'));

            // Set session
            session([
                'current_team_id' => $team_id,
                'current_player_category' => 'dancer',
                'current_can_be_leader' => $isLeader
            ]);

            // Data untuk dropdown
            $grades = ['X', 'XI', 'XII'];
            $tshirtSizes = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
            $shoesSizes = range(36, 46);

            return view('user.form.form_dancer', compact(
                'team',
                'role',
                'isLeader',
                'grades',
                'tshirtSizes',
                'shoesSizes'
            ));
        } catch (\Exception $e) {
            Log::error('❌ Error in showDancerForm: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('form.team.choice')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Proses pendaftaran dancer
     */
    public function storeDancer(Request $request)
    {
        try {
            Log::info('=== STORE DANCER START ===');
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

            Log::info('Processing dancer for team_id: ' . $teamId);

            // Ambil data tim
            $team = TeamList::findOrFail($teamId);

            Log::info('Team info:', [
                'team_id' => $team->team_id,
                'school_name' => $team->school_name,
                'team_category' => $team->team_category,
                'is_leader_paid' => $team->is_leader_paid,
                'referral_code' => $team->referral_code
            ]);

            // ============================================
            // LOGIC PENENTUAN ROLE YANG AKURAT
            // ============================================
            $teamRole = $request->input('team_role', 'Member');
            $isLeader = ($teamRole === 'Leader');

            Log::info('Role from form: ' . $teamRole . ', isLeader: ' . ($isLeader ? 'true' : 'false'));

            // Validasi: jika mengaku Leader, pastikan memang boleh jadi Leader
            if ($isLeader) {
                // 1. Cek apakah sudah ada Leader dancer di tim ini
                $existingLeaderCount = DancerList::where('team_id', $teamId)
                    ->where('role', 'Leader')
                    ->count();

                if ($existingLeaderCount > 0) {
                    Log::error('❌ ERROR: Trying to register as Leader but leader already exists');
                    return back()->withErrors(['error' => 'Tim ini sudah memiliki Leader dancer.'])->withInput();
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
            // VALIDASI RULES - TANPA BATASAN STTB TAHUN
            // ============================================
            $rules = [
                'nik' => 'required|string|size:16|unique:dancer_list,nik',
                'name' => 'required|string|max:255',
                'birthdate' => 'required|date',
                'gender' => 'required|in:Laki-laki,Perempuan',
                'email' => 'required|email|unique:dancer_list,email',
                'phone' => 'required|string|max:20',
                'grade' => 'required|string|max:10',
                'sttb_year' => 'required|digits:4|integer', // ✅ HANYA CEK 4 DIGIT, TIDAK ADA MIN/MAX
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
                'terms' => 'required|accepted',
            ];

            // TAMBAHKAN payment_proof HANYA untuk Leader
            if ($isLeader) {
                $rules['payment_proof'] = 'required|file|mimes:jpg,jpeg,png,pdf|max:2048';
                Log::info('💰 PAYMENT PROOF REQUIRED FOR LEADER');
            }

            $validated = $request->validate($rules);

            Log::info('✅ Dancer validation passed');

            // ============================================
            // AMBIL DATA SEKOLAH - gunakan dari team, bukan dari form
            // ============================================
            $school = School::where('school_name', $team->school_name)->first();

            if (!$school) {
                $school = School::create([
                    'school_name' => $team->school_name,
                    'category_name' => 'SMA',
                    'type' => 'SWASTA',
                    'city_id' => 1,
                ]);
                Log::info('✅ Created new school: ' . $team->school_name);
            }

            // ============================================
            // GENERATE NAMA FILE
            // ============================================
            $schoolSlug = Str::slug($team->school_name);
            $dancerSlug = Str::slug($validated['name']);
            $timestamp = time();

            // Fungsi helper untuk menyimpan file
            $saveFile = function ($field, $folder) use ($request, $schoolSlug, $dancerSlug, $timestamp) {
                if ($request->hasFile($field)) {
                    $file = $request->file($field);
                    $extension = $file->getClientOriginalExtension();
                    $filename = "{$schoolSlug}_{$dancerSlug}_{$field}_{$timestamp}.{$extension}";

                    $path = $file->storeAs($folder, $filename, 'public');
                    Log::info('📁 File saved: ' . $field . ' -> ' . $path);
                    return $path;
                }
                return null;
            };

            // Buat folder jika belum ada
            Storage::disk('public')->makeDirectory('dancer_docs');
            Storage::disk('public')->makeDirectory('payment_proofs');

            // ============================================
            // PROSES PEMBAYARAN LEADER
            // ============================================
            $paymentProofPath = null;

            Log::info('=== PAYMENT PROCESSING ===');
            Log::info('Is Leader: ' . ($isLeader ? 'YES' : 'NO'));
            Log::info('Has Payment File: ' . ($request->hasFile('payment_proof') ? 'YES' : 'NO'));

            if ($isLeader) {
                if (!$request->hasFile('payment_proof')) {
                    Log::error('❌ ERROR: Leader but no payment file uploaded!');
                    return back()->withErrors(['payment_proof' => 'Sebagai Leader, Anda wajib mengupload bukti pembayaran.'])->withInput();
                }

                Log::info('💰 PROCESSING PAYMENT FOR LEADER...');

                // 1. Simpan bukti pembayaran
                $paymentProofPath = $saveFile('payment_proof', 'payment_proofs');
                Log::info('✅ Payment proof saved: ' . $paymentProofPath);

                // 2. UPDATE TEAM YANG SUDAH ADA
                $updateData = [
                    'payment_proof' => $paymentProofPath,
                    'is_leader_paid' => true,
                    'payment_status' => 'paid',
                    'payment_date' => now(),
                    'registered_by' => $validated['name'],
                ];

                // 3. Generate referral code JIKA BELUM ADA
                if (!$team->referral_code || $team->referral_code === '') {
                    $baseSlug = Str::slug($team->school_name);
                    $referralCodeGenerated = strtoupper($baseSlug) . '-' . strtoupper(Str::random(6));
                    $updateData['referral_code'] = $referralCodeGenerated;
                    session(['dancer_referral_code' => $referralCodeGenerated]);
                    Log::info('✅ New referral code generated: ' . $referralCodeGenerated);
                } else {
                    session(['dancer_referral_code' => $team->referral_code]);
                    Log::info('✅ Using existing referral code: ' . $team->referral_code);
                }

                // 4. Update team
                $team->update($updateData);
                Log::info('✅ Team updated with payment status - Team ID: ' . $team->team_id);
            } else {
                Log::info('✅ Member registration (no payment required)');
            }

            // ============================================
            // SIMPAN DATA DANCER
            // ============================================
            $dancerData = [
                'team_id' => $teamId,
                'school_id' => $school->id,
                'nik' => $validated['nik'],
                'name' => $validated['name'],
                'birthdate' => $validated['birthdate'],
                'gender' => $validated['gender'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'school_name' => $team->school_name, // 🔥 AMBIL DARI TEAM
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
                'birth_certificate' => $saveFile('birth_certificate', 'dancer_docs'),
                'kk' => $saveFile('kk', 'dancer_docs'),
                'shun' => $saveFile('shun', 'dancer_docs'),
                'report_identity' => $saveFile('report_identity', 'dancer_docs'),
                'last_report_card' => $saveFile('last_report_card', 'dancer_docs'),
                'formal_photo' => $saveFile('formal_photo', 'dancer_docs'),
                'assignment_letter' => $saveFile('assignment_letter', 'dancer_docs'),
                'role' => $teamRole,
                'verification_status' => 'unverified',
            ];

            // Simpan data dancer
            $dancer = DancerList::create($dancerData);
            Log::info('✅ Dancer created with ID: ' . $dancer->dancer_id . ', Role: ' . $teamRole);

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
                'dancer_registered' => true,
                'dancer_team_id' => $teamId,
                'dancer_id' => $dancer->dancer_id,
                'dancer_name' => $validated['name'],
                'dancer_role' => $teamRole,
                'is_leader_registered' => $isLeader,
            ]);

            // Ambil referral code terbaru
            $team->refresh();
            $referralCodeForSuccess = $team->referral_code;

            if ($referralCodeForSuccess && $referralCodeForSuccess !== '') {
                session(['dancer_referral_code' => $referralCodeForSuccess]);
                Log::info('✅ Final referral code for success page: ' . $referralCodeForSuccess);
            }

            Log::info('=== REDIRECTING TO SUCCESS PAGE ===');
            Log::info('Team ID: ' . $teamId);
            Log::info('Dancer ID: ' . $dancer->dancer_id);
            Log::info('Dancer Role: ' . $teamRole);

            return redirect()->route('form.dancer.success', [
                'team_id' => $teamId,
                'dancer_id' => $dancer->dancer_id
            ])->with('success', '🎉 Pendaftaran dancer berhasil!');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('❌ Validation error in storeDancer: ', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('❌ Error in storeDancer: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Halaman sukses pendaftaran dancer
     */
    public function showSuccessPage($team_id, $dancer_id)
    {
        try {
            Log::info('=== SHOW DANCER SUCCESS PAGE START ===');
            Log::info('Team ID from URL: ' . $team_id);
            Log::info('Dancer ID from URL: ' . $dancer_id);

            // Ambil data tim
            $team = TeamList::find($team_id);
            if (!$team) {
                Log::error('❌ Team not found: ' . $team_id);
                return redirect()->route('form.team.choice')
                    ->with('error', 'Tim tidak ditemukan.');
            }

            // Ambil data dancer
            $dancer = DancerList::find($dancer_id);
            if (!$dancer) {
                Log::error('❌ Dancer not found: ' . $dancer_id);
                return redirect()->route('form.team.choice')
                    ->with('error', 'Data dancer tidak ditemukan.');
            }

            Log::info('✅ Team found: ' . $team->school_name);
            Log::info('✅ Dancer found: ' . $dancer->name);
            Log::info('✅ Dancer Role from DB: ' . $dancer->role);

            // Ambil referral code dari TEAM
            $referralCode = $team->referral_code;

            if ($referralCode === '' || $referralCode === null) {
                $referralCode = null;
            }

            // Cek apakah ini Leader
            $isLeader = ($dancer->role === 'Leader');

            if ($isLeader) {
                $successMessage = '🎉 Selamat! Anda telah terdaftar sebagai LEADER tim dancer ' . $team->school_name . '.';
                if ($referralCode && $referralCode !== '') {
                    $instructions = 'Sebagai Leader, Anda telah berhasil membayar biaya registrasi tim dancer. Gunakan kode referral berikut untuk mengajak anggota lain bergabung:';
                } else {
                    $instructions = 'Sebagai Leader, Anda telah berhasil mendaftar. Referral code akan dibuat setelah pembayaran diverifikasi oleh admin.';
                }
            } else {
                $successMessage = '🎉 Selamat! Anda telah bergabung dengan tim dancer ' . $team->school_name . '.';
                $instructions = 'Anda telah terdaftar sebagai anggota tim dancer. Biaya pendaftaran sudah ditanggung oleh Leader tim.';
            }

            return view('user.form.form_dancer_success', compact(
                'team',
                'dancer',
                'referralCode',
                'isLeader',
                'successMessage',
                'instructions'
            ))->with('success', session('success'));
        } catch (\Exception $e) {
            Log::error('❌ Error in showSuccessPage: ' . $e->getMessage());
            return redirect()->route('form.team.choice')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Cek NIK unik (AJAX)
     */
    public function checkNik(Request $request)
    {
        $request->validate(['nik' => 'required|string']);
        $exists = DancerList::where('nik', $request->nik)->exists();
        return response()->json(['exists' => $exists]);
    }

    /**
     * Cek email unik (AJAX)
     */
    public function checkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $exists = DancerList::where('email', $request->email)->exists();
        return response()->json(['exists' => $exists]);
    }
}