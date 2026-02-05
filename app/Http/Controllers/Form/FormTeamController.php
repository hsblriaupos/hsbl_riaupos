<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\TeamList;
use App\Models\City;
use App\Models\PlayerList;
use App\Models\DancerList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FormTeamController extends Controller
{
    /**
     * Tampilkan form pilihan join atau create
     */
    public function showChoiceForm()
    {
        $competitions = DB::table('add_data')
            ->whereNotNull('competition')
            ->distinct()
            ->pluck('competition');

        $seasons = DB::table('add_data')
            ->whereNotNull('season_name')
            ->distinct()
            ->pluck('season_name');

        $series = DB::table('add_data')
            ->whereNotNull('series_name')
            ->distinct()
            ->pluck('series_name');

        $teamCategories = DB::select("SHOW COLUMNS FROM team_list WHERE Field = 'team_category'");
        preg_match("/^enum\((.*)\)$/", $teamCategories[0]->Type, $matches);
        $teamCategoryEnums = collect(explode(',', str_replace("'", "", $matches[1])));

        return view('user.form.form_team', compact(
            'competitions',
            'seasons',
            'series',
            'teamCategoryEnums'
        ));
    }

    /**
     * Tampilkan form untuk CREATE TEAM
     */
    public function showCreateForm(Request $request)
    {
        $competitions = DB::table('add_data')
            ->whereNotNull('competition')
            ->distinct()
            ->pluck('competition');

        $seasons = DB::table('add_data')
            ->whereNotNull('season_name')
            ->distinct()
            ->pluck('season_name');

        $series = DB::table('add_data')
            ->whereNotNull('series_name')
            ->distinct()
            ->pluck('series_name');

        $teamCategories = DB::select("SHOW COLUMNS FROM team_list WHERE Field = 'team_category'");
        preg_match("/^enum\((.*)\)$/", $teamCategories[0]->Type, $matches);
        $teamCategoryEnums = collect(explode(',', str_replace("'", "", $matches[1])));

        $cities = City::all();
        $schools = School::with('city')->get();

        return view('user.form.form_create_team', compact(
            'schools',
            'cities',
            'competitions',
            'seasons',
            'series',
            'teamCategoryEnums'
        ));
    }

    /**
     * Tampilkan form untuk JOIN TEAM dengan referral code
     */
    public function showJoinForm()
    {
        return view('user.form.form_join_team');
    }

    /**
     * Proses JOIN TEAM dengan referral code
     */
    public function joinTeam(Request $request)
    {
        $request->validate([
            'referral_code' => [
                'required',
                'string',
                'min:3',
                'exists:team_list,referral_code'
            ]
        ]);

        $team = TeamList::where('referral_code', $request->referral_code)
            ->whereNotNull('referral_code')  // Pastikan tidak NULL
            ->first();

        if (!$team) {
            return redirect()->back()->withErrors(['referral_code' => 'Referral code tidak valid atau belum tersedia.']);
        }

        if ($team->locked_status == 'locked') {
            return redirect()->back()->withErrors(['referral_code' => 'Tim ini sudah terkunci dan tidak menerima anggota baru.']);
        }

        if (!$team->is_leader_paid) {
            return redirect()->back()->withErrors(['referral_code' => 'Tim ini belum memiliki Leader yang membayar.']);
        }

        // Simpan referral code di session
        session([
            'join_referral_code' => $request->referral_code,
            'join_team_id' => $team->team_id,
        ]);

        // Redirect ke form pilih role
        return redirect()->route('form.team.join.role')
            ->with('success', 'Referral code valid! Silakan pilih posisi Anda dalam tim.')
            ->with('team', $team);
    }

    /**
     * Tampilkan form pilih role untuk join team
     */
    public function showRoleSelectionForm()
    {
        $referralCode = session('join_referral_code');

        if (!$referralCode) {
            return redirect()->route('form.team.join')
                ->with('error', 'Silakan masukkan referral code terlebih dahulu.');
        }

        $team = TeamList::where('referral_code', $referralCode)->first();

        if (!$team) {
            return redirect()->route('form.team.join')
                ->with('error', 'Referral code tidak valid.');
        }

        return view('user.form.form_role_selection', [
            'referralCode' => $referralCode,
            'team' => $team
        ]);
    }

    /**
     * Proses pemilihan role dan redirect ke form yang sesuai
     */
    public function processRoleSelection(Request $request)
    {
        $request->validate([
            'referral_code' => 'required|exists:team_list,referral_code',
            'team_category' => 'required|in:Basket Putra,Basket Putri,Dancer'
        ]);

        $team = TeamList::where('referral_code', $request->referral_code)->first();

        if (!$team) {
            return redirect()->route('form.team.join')
                ->with('error', 'Referral code tidak valid.');
        }

        // Normalisasi kategori
        $playerCategory = $this->normalizeCategory($request->team_category);

        Log::info('Role Selection:', [
            'team_id' => $team->team_id,
            'team_category' => $request->team_category,
            'player_category' => $playerCategory,
            'is_leader_paid' => $team->is_leader_paid
        ]);

        // Simpan ke session - SET YANG MEMBUAT TIM JADI LEADER
        // Jika user join dengan referral code, default jadi Member
        // Kecuali jika tim belum punya leader untuk kategori tersebut
        $canBeLeader = false;
        
        // Cek apakah tim sudah bayar
        if ($team->is_leader_paid) {
            // Cek apakah sudah ada leader untuk kategori ini
            if ($playerCategory === 'dancer') {
                $existingLeaderCount = DancerList::where('team_id', $team->team_id)
                    ->where('role', 'Leader')
                    ->count();
                    
                if ($existingLeaderCount === 0) {
                    $canBeLeader = true; // Bisa jadi leader dancer
                }
            } else {
                $existingLeaderCount = PlayerList::where('team_id', $team->team_id)
                    ->where('category', $playerCategory)
                    ->where('role', 'Leader')
                    ->count();
                    
                if ($existingLeaderCount === 0) {
                    $canBeLeader = true; // Bisa jadi leader basket
                }
            }
        }

        session([
            'current_team_id' => $team->team_id,
            'current_team_category' => $request->team_category,
            'current_player_category' => $playerCategory,
            'join_referral_code' => $request->referral_code,
            'current_can_be_leader' => $canBeLeader, // 🔥 PENTING: Set boleh/tidak jadi leader
        ]);

        // Redirect ke form yang sesuai berdasarkan kategori
        if ($playerCategory === 'dancer') {
            Log::info('Redirecting to dancer form for team: ' . $team->team_id . ', canBeLeader: ' . ($canBeLeader ? 'YES' : 'NO'));
            return redirect()->route('form.dancer.create', [
                'team_id' => $team->team_id
            ]);
        } else {
            Log::info('Redirecting to player form for team: ' . $team->team_id . ' category: ' . $playerCategory . ', canBeLeader: ' . ($canBeLeader ? 'YES' : 'NO'));
            return redirect()->route('form.player.create.with-category', [
                'team_id' => $team->team_id,
                'category' => $playerCategory
            ]);
        }
    }

    /**
     * Normalisasi kategori dari team_list ke player_list/dancer_list
     */
    private function normalizeCategory($teamCategory)
    {
        $teamCategory = strtolower($teamCategory);

        if (str_contains($teamCategory, 'putra')) {
            return 'putra';
        } elseif (str_contains($teamCategory, 'putri')) {
            return 'putri';
        } elseif (str_contains($teamCategory, 'dancer')) {
            return 'dancer';
        } else {
            return strtolower($teamCategory);
        }
    }

    /**
     * Proses CREATE TEAM (TANPA BUKTI BAYAR)
     */
    public function createTeam(Request $request)
    {
        try {
            Log::info('=== CREATE TEAM START ===');
            Log::info('Create Team Request Data:', $request->all());

            // VALIDASI
            $validationRules = [
                'school_option' => 'required|in:existing,new',
                'competition' => 'required',
                'season' => 'required',
                'series' => 'required',
                'team_category' => 'required|in:Basket Putra,Basket Putri,Dancer',
                'registered_by' => 'required|string|max:255',
                'recommendation_letter' => 'required|file|mimes:pdf|max:2048',
                'koran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            ];

            if ($request->school_option == 'existing') {
                $validationRules['existing_school_id'] = 'required|exists:schools,id';
                $validationRules['school_logo'] = 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048';
            } else {
                $validationRules['new_school_name'] = 'required|string|max:255';
                $validationRules['new_city_id'] = 'required|exists:cities,id';
                $validationRules['new_category_name'] = 'required|in:SMA,SMK,MA';
                $validationRules['new_type'] = 'required|in:NEGERI,SWASTA';
                $validationRules['school_logo'] = 'required|image|mimes:jpg,jpeg,png,webp|max:2048';
            }

            $validated = $request->validate($validationRules);
            Log::info('✅ Validation passed');

            // PROSES SEKOLAH
            $schoolName = '';
            $schoolId = null;
            $schoolLogoPath = null;

            if ($validated['school_option'] == 'existing') {
                $school = School::findOrFail($validated['existing_school_id']);
                $schoolName = $school->school_name;
                $schoolId = $school->id;

                // Jika ada logo baru diupload, simpan ke team_list
                if ($request->hasFile('school_logo')) {
                    $schoolLogoPath = $this->uploadSchoolLogo(
                        $request->file('school_logo'),
                        $schoolName,
                        $validated['team_category']
                    );
                }
            } else {
                $existingSchool = School::where('school_name', $validated['new_school_name'])->first();
                if ($existingSchool) {
                    $school = $existingSchool;
                    $schoolName = $school->school_name;
                    $schoolId = $school->id;

                    // Upload logo untuk tim baru
                    if ($request->hasFile('school_logo')) {
                        $schoolLogoPath = $this->uploadSchoolLogo(
                            $request->file('school_logo'),
                            $schoolName,
                            $validated['team_category']
                        );
                    }

                    session()->flash('info', 'Sekolah "' . $schoolName . '" sudah terdaftar di sistem.');
                } else {
                    // Upload logo untuk tim (bukan untuk sekolah)
                    if ($request->hasFile('school_logo')) {
                        $schoolLogoPath = $this->uploadSchoolLogo(
                            $request->file('school_logo'),
                            $validated['new_school_name'],
                            $validated['team_category']
                        );
                    }

                    // Buat sekolah TANPA school_logo
                    $school = School::create([
                        'school_name' => $validated['new_school_name'],
                        'city_id' => $validated['new_city_id'],
                        'category_name' => $validated['new_category_name'],
                        'type' => $validated['new_type'],
                    ]);
                    $schoolName = $school->school_name;
                    $schoolId = $school->id;
                }
            }

            Log::info('✅ School processed: ' . $schoolName . ' (ID: ' . $schoolId . ')');

            // CEK TIM YANG SUDAH ADA
            $normalizedCategory = $this->normalizeCategory($validated['team_category']);
            $specificTeamCategory = $validated['team_category'];

            $existingTeamSameCategory = TeamList::where('school_name', $schoolName)
                ->where('team_category', $specificTeamCategory)
                ->where('season', $validated['season'])
                ->first();

            if ($existingTeamSameCategory) {
                Log::error('❌ Team already exists for category: ' . $specificTeamCategory);
                return back()->withErrors([
                    'team_category' => 'Tim ' . $specificTeamCategory . ' untuk ' . $schoolName . ' pada season ' . $validated['season'] . ' sudah ada!'
                ])->withInput();
            }

            Log::info('✅ No duplicate team found for category: ' . $specificTeamCategory);

            // CEK APAKAH SEKOLAH SUDAH PUNYA REFERRAL CODE (tidak NULL)
            $existingTeamWithReferral = TeamList::where('school_name', $schoolName)
                ->where('season', $validated['season'])
                ->whereNotNull('referral_code')  // 🔥 PERUBAHAN PENTING: cek NOT NULL
                ->where('is_leader_paid', true)
                ->first();

            $referralCode = null;  // 🔥 PERUBAHAN PENTING: set NULL bukan ''
            $isFirstTeamForSchool = false;

            if ($existingTeamWithReferral) {
                $referralCode = $existingTeamWithReferral->referral_code;
                Log::info('✅ Using existing referral code: ' . $referralCode);
            } else {
                $referralCode = null;  // 🔥 PERUBAHAN PENTING: NULL bukan ''
                $isFirstTeamForSchool = true;
                Log::info('ℹ️ No referral code yet (NULL), will generate after payment');
            }

            Log::info('Referral code for new team: ' . ($referralCode ?: 'NULL'));

            // SIMPAN FILE DOKUMEN
            Storage::disk('public')->makeDirectory('team_docs');
            $baseSlug = Str::slug($schoolName);
            $timestamp = time();

            $recommendationPath = $request->file('recommendation_letter')
                ->storeAs('team_docs', "{$baseSlug}_recommendation_{$timestamp}.pdf", 'public');

            $koranPath = $request->file('koran')
                ->storeAs('team_docs', "{$baseSlug}_koran_{$timestamp}." . $request->file('koran')->extension(), 'public');

            Log::info('✅ Documents saved: ' . $recommendationPath . ', ' . $koranPath);

            // BUAT TIM DI TEAM_LIST
            $team = TeamList::create([
                'school_name' => $schoolName,
                'school_id' => $schoolId,
                'school_logo' => $schoolLogoPath,
                'referral_code' => $referralCode,  // 🔥 Bisa NULL
                'competition' => $validated['competition'],
                'season' => $validated['season'],
                'series' => $validated['series'],
                'team_category' => $specificTeamCategory,
                'registered_by' => $validated['registered_by'],
                'locked_status' => 'unlocked',
                'verification_status' => 'unverified',
                'recommendation_letter' => $recommendationPath,
                'koran' => $koranPath,
                'payment_proof' => null,
                'is_leader_paid' => false,
                'payment_status' => 'pending',
                'payment_date' => null,
            ]);

            Log::info('✅ Team created with ID: ' . $team->team_id . ', Logo path: ' . ($schoolLogoPath ?: 'NULL'));

            // 🔥 PENTING: SET SESSION DENGAN BENAR!
            // Yang membuat tim OTOMATIS bisa jadi Leader
            session([
                'created_team_id' => $team->team_id,
                'created_team_category' => $team->team_category,
                'created_school_name' => $schoolName,
                'is_first_team_for_school' => $isFirstTeamForSchool,
                'registered_by_name' => $validated['registered_by'],
                'team_paid' => false,
                'current_can_be_leader' => true, // 🔥 INI YANG PENTING!
                'current_player_category' => $normalizedCategory,
            ]);

            Log::info('Session after create team:', [
                'created_team_id' => session('created_team_id'),
                'current_can_be_leader' => session('current_can_be_leader'),
                'current_player_category' => session('current_player_category')
            ]);

            // Redirect ke form yang sesuai
            if ($normalizedCategory === 'dancer') {
                Log::info('🎭 Redirecting creator to dancer form for team: ' . $team->team_id);
                return redirect()->route('form.dancer.create', [
                    'team_id' => $team->team_id
                ])->with('success', 'Tim berhasil dibuat! Sekarang lengkapi data diri Anda sebagai Kapten Dancer.')
                    ->with('info', 'Sebagai Kapten, Anda perlu upload bukti pembayaran di langkah berikutnya.');
            } else {
                Log::info('🏀 Redirecting creator to player form for team: ' . $team->team_id . ' category: ' . $normalizedCategory);
                return redirect()->route('form.player.create.with-category', [
                    'team_id' => $team->team_id,
                    'category' => $normalizedCategory
                ])->with('success', 'Tim berhasil dibuat! Sekarang lengkapi data diri Anda sebagai Kapten.')
                    ->with('info', 'Sebagai Kapten, Anda perlu upload bukti pembayaran di langkah berikutnya.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('❌ Validation error: ', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('❌ Error in createTeam: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return back()->withErrors(['error' => 'Terjadi kesalahan sistem: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Helper function untuk upload logo sekolah
     */
    private function uploadSchoolLogo($file, $schoolName, $teamCategory = null)
    {
        $baseSlug = Str::slug($schoolName);
        $timestamp = time();
        $extension = $file->extension();

        $categorySuffix = $teamCategory ? '_' . Str::slug($teamCategory) : '';
        $filename = "{$baseSlug}{$categorySuffix}_logo_{$timestamp}.{$extension}";

        $path = $file->storeAs('school_logos', $filename, 'public');

        Log::info('✅ School logo uploaded: ' . $path);
        return $path;
    }

    /**
     * Cek ketersediaan sekolah untuk autocomplete
     */
    public function checkSchool(Request $request)
    {
        $query = $request->get('query');

        $schools = School::where('school_name', 'LIKE', "%{$query}%")
            ->with('city')
            ->limit(10)
            ->get(['id', 'school_name', 'city_id', 'category_name', 'type']);

        return response()->json($schools);
    }

    /**
     * Cek apakah sekolah sudah ada
     */
    public function checkSchoolExists(Request $request)
    {
        $request->validate([
            'school_name' => 'required|string'
        ]);

        $exists = School::where('school_name', $request->school_name)->exists();
        $school = School::where('school_name', $request->school_name)->first();

        return response()->json([
            'exists' => $exists,
            'school' => $school,
            'message' => $exists ? 'Sekolah sudah ada di database' : 'Sekolah belum terdaftar'
        ]);
    }

    /**
     * Verifikasi apakah tim sudah ada untuk kategori tertentu
     */
    public function checkExistingTeam(Request $request)
    {
        $request->validate([
            'school_name' => 'required',
            'team_category' => 'required|in:Basket Putra,Basket Putri,Dancer',
            'season' => 'required'
        ]);

        $specificTeamCategory = $request->team_category;

        $existingTeam = TeamList::where('school_name', $request->school_name)
            ->where('team_category', $specificTeamCategory)
            ->where('season', $request->season)
            ->first();

        if ($existingTeam) {
            $message = 'Tim ' . $specificTeamCategory . ' untuk sekolah ini pada season ' . $request->season . ' sudah terdaftar!';

            $hasReferral = TeamList::where('school_name', $request->school_name)
                ->where('season', $request->season)
                ->whereNotNull('referral_code')  // 🔥 PERUBAHAN: cek NOT NULL
                ->where('is_leader_paid', true)
                ->exists();

            if ($hasReferral) {
                $firstTeam = TeamList::where('school_name', $request->school_name)
                    ->where('season', $request->season)
                    ->whereNotNull('referral_code')  // 🔥 PERUBAHAN: cek NOT NULL
                    ->where('is_leader_paid', true)
                    ->first();
                $message .= ' Sekolah ini sudah memiliki referral code: ' . $firstTeam->referral_code;
            } else {
                $message .= ' Tim ini belum memiliki Kapten yang membayar.';
            }

            return response()->json([
                'exists' => true,
                'team' => $existingTeam,
                'message' => $message,
                'has_paid_leader' => $hasReferral
            ]);
        }

        return response()->json(['exists' => false]);
    }
}