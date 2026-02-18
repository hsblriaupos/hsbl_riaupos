<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\TeamList;
use App\Models\City;
use App\Models\PlayerList;
use App\Models\DancerList;
use App\Models\OfficialList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FormTeamController extends Controller
{
    /**
     * Show choice form (join or create)
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
     * Show create team form
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
     * Show join team form
     */
    public function showJoinForm()
    {
        return view('user.form.form_join_team');
    }

    /**
     * Process join team with referral code
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
            ->whereNotNull('referral_code')
            ->first();

        if (!$team) {
            return redirect()->back()->withErrors(['referral_code' => 'Referral code tidak valid atau belum tersedia.']);
        }

        if ($team->locked_status == 'locked') {
            return redirect()->back()->withErrors(['referral_code' => 'Tim ini sudah terkunci dan tidak menerima anggota baru.']);
        }

        session([
            'join_referral_code' => $request->referral_code,
            'join_team_id' => $team->team_id,
            'join_school_name' => $team->school_name,
            'join_season' => $team->season,
        ]);

        return redirect()->route('form.team.join.role')
            ->with('success', 'Referral code valid! Silakan pilih posisi Anda dalam tim.')
            ->with('team', $team);
    }

    /**
     * Show role selection form
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
     * 🔥🔥🔥 FIX UTAMA: Process role selection 
     * ✅ PERBAIKAN: Jika belum ada tim untuk kategori Basket, user bisa membuatnya sebagai Leader
     */
    public function processRoleSelection(Request $request)
    {
        $validated = $request->validate([
            'referral_code' => 'required|exists:team_list,referral_code',
            'team_category' => 'required|in:Basket Putra,Basket Putri,Dancer,Official'
        ]);

        // 🔥🔥🔥 AMBIL TIM DARI REFERRAL CODE (Ini adalah tim PERTAMA sekolah di season ini)
        $primaryTeam = TeamList::where('referral_code', $validated['referral_code'])->first();

        if (!$primaryTeam) {
            return redirect()->route('form.team.join')
                ->with('error', 'Referral code tidak valid.');
        }

        Log::info('Role Selection:', [
            'primary_team_id' => $primaryTeam->team_id,
            'primary_team_category' => $primaryTeam->team_category,
            'selected_category' => $validated['team_category'],
            'school_name' => $primaryTeam->school_name,
            'season' => $primaryTeam->season
        ]);

        // ✅ NORMALIZE CATEGORY
        $playerCategory = $this->normalizeCategory($validated['team_category']);

        // ✅ CEK APAKAH ADA TIM DENGAN KATEGORI YANG DIPILIH?
        $targetTeamId = $primaryTeam->team_id;
        $targetTeamCategory = $primaryTeam->team_category;
        $isNewTeamForCategory = false;

        // Untuk Basket Putra/Putri: cek apakah sudah ada tim khusus kategori tsb
        if (in_array($validated['team_category'], ['Basket Putra', 'Basket Putri'])) {
            // 🔥 CEK APAKAH SUDAH ADA TIM BASKET DENGAN KATEGORI INI?
            $existingBasketTeam = TeamList::where('school_name', $primaryTeam->school_name)
                ->where('season', $primaryTeam->season)
                ->where('team_category', $validated['team_category'])
                ->first();

            if ($existingBasketTeam) {
                // ✅ SUDAH ADA: gunakan tim yang sudah ada
                $targetTeamId = $existingBasketTeam->team_id;
                $targetTeamCategory = $existingBasketTeam->team_category;
                $isNewTeamForCategory = false;
                Log::info('✅ Using existing basket team: ' . $targetTeamId);
            } else {
                // ✅ BELUM ADA: User akan jadi Leader untuk kategori baru ini
                // Kita akan gunakan primary team sebagai referensi, tapi nanti di form player
                // akan dibuatkan tim baru untuk kategori ini
                $isNewTeamForCategory = true;
                Log::info('🆕 User will create new team for category: ' . $validated['team_category']);
            }
        }

        // ✅ CEK LEADER LOGIC
        $canBeLeader = false;

        if ($isNewTeamForCategory) {
            // Jika ini kategori baru, user OTOMATIS jadi Leader
            $canBeLeader = true;
            Log::info('✅ User will be Leader for new category');
        } elseif ($validated['team_category'] === 'Official') {
            $existingLeaderCount = OfficialList::where('team_id', $targetTeamId)
                ->where('role', 'Leader')
                ->count();

            if ($existingLeaderCount === 0) {
                $canBeLeader = true;
            }
        } elseif ($validated['team_category'] === 'Dancer') {
            $existingLeaderCount = DancerList::where('team_id', $targetTeamId)
                ->where('role', 'Leader')
                ->count();

            if ($existingLeaderCount === 0) {
                $canBeLeader = true;
            }
        } else {
            // Basket Putra/Putri (untuk tim yang sudah ada)
            if ($primaryTeam->is_leader_paid) {
                $existingLeaderCount = PlayerList::where('team_id', $targetTeamId)
                    ->where('category', $playerCategory)
                    ->where('role', 'Leader')
                    ->count();

                if ($existingLeaderCount === 0) {
                    $canBeLeader = true;
                }
            }
        }

        // ✅ SET SESSION
        session([
            'current_team_id' => $targetTeamId,
            'current_team_category' => $validated['team_category'],
            'current_player_category' => $playerCategory,
            'join_referral_code' => $validated['referral_code'],
            'join_school_name' => $primaryTeam->school_name,
            'join_season' => $primaryTeam->season,
            'current_can_be_leader' => $canBeLeader,
            'is_new_team_for_category' => $isNewTeamForCategory, // 🔥 TAMBAHKAN INI
        ]);

        Log::info('✅ Session set for role selection:', [
            'team_id' => $targetTeamId,
            'team_category' => $validated['team_category'],
            'player_category' => $playerCategory,
            'canBeLeader' => $canBeLeader ? 'YES' : 'NO',
            'is_new_team' => $isNewTeamForCategory ? 'YES' : 'NO'
        ]);

        // ✅ REDIRECT KE FORM YANG SESUAI
        switch ($validated['team_category']) {
            case 'Basket Putra':
            case 'Basket Putri':
                return redirect()->route('form.player.create.with-category', [
                    'team_id' => $targetTeamId,
                    'category' => $playerCategory
                ]);

            case 'Dancer':
                return redirect()->route('form.dancer.create', [
                    'team_id' => $targetTeamId
                ]);

            case 'Official':
                return redirect()->route('form.official.create', [
                    'team_id' => $targetTeamId
                ]);

            default:
                Log::error('Invalid category: ' . $validated['team_category']);
                return redirect()->back()->with('error', 'Kategori tidak valid.');
        }
    }

    public function createTeam(Request $request)
    {
        try {
            Log::info('=== CREATE TEAM START ===');
            Log::info('Create Team Request Data:', $request->all());

            // Validation
            $validationRules = [
                'school_option' => 'required|in:existing,new',
                'competition' => 'required',
                'season' => 'required',
                'series' => 'required',
                'team_category' => 'required|in:Basket Putra,Basket Putri,Dancer,Official',
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

            // Process school
            $schoolName = '';
            $schoolId = null;

            if ($validated['school_option'] == 'existing') {
                $school = School::findOrFail($validated['existing_school_id']);
                $schoolName = $school->school_name;
                $schoolId = $school->id;
            } else {
                $existingSchool = School::where('school_name', $validated['new_school_name'])->first();
                if ($existingSchool) {
                    $school = $existingSchool;
                    $schoolName = $school->school_name;
                    $schoolId = $school->id;
                    session()->flash('info', 'Sekolah "' . $schoolName . '" sudah terdaftar di sistem.');
                } else {
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

            // 🔥🔥🔥 CEK: Apakah sudah ada tim untuk KATEGORI INI di season ini?
            $existingTeamForCategory = TeamList::where('school_name', $schoolName)
                ->where('season', $validated['season'])
                ->where('team_category', $validated['team_category'])
                ->first();

            if ($existingTeamForCategory) {
                // 🔥 SUDAH ADA TIM UNTUK KATEGORI INI
                Log::info('⚠️ Team already exists for category: ' . $validated['team_category']);

                $team = $existingTeamForCategory;
                $referralCode = $team->referral_code;

                if ($referralCode && $team->is_leader_paid) {
                    return redirect()->route('form.team.join')
                        ->with('warning', 'Tim ' . $validated['team_category'] . ' untuk "' . $schoolName . '" sudah memiliki Kapten. Gunakan referral code: ' . $referralCode . ' untuk bergabung.')
                        ->with('referral_code', $referralCode);
                } else {
                    return redirect()->route('form.team.join')
                        ->with('warning', 'Tim ' . $validated['team_category'] . ' untuk "' . $schoolName . '" sudah ada tetapi Kapten belum menyelesaikan pembayaran. Silakan tunggu atau hubungi Kapten tim.');
                }
            }

            // 🔥🔥🔥 INI ADALAH TIM PERTAMA UNTUK KATEGORI INI
            Log::info('🎯 This is the FIRST team for category ' . $validated['team_category'] . ' - ' . $schoolName . ' in season ' . $validated['season']);

            // Upload school logo if exists
            $schoolLogoPath = null;
            if ($request->hasFile('school_logo')) {
                $schoolLogoPath = $this->uploadSchoolLogo(
                    $request->file('school_logo'),
                    $schoolName,
                    $validated['team_category']
                );
            }

            // Save documents
            Storage::disk('public')->makeDirectory('team_docs');
            $baseSlug = Str::slug($schoolName);
            $categorySlug = Str::slug($validated['team_category']);
            $timestamp = time();

            $recommendationPath = $request->file('recommendation_letter')
                ->storeAs('team_docs', "{$baseSlug}_{$categorySlug}_recommendation_{$timestamp}.pdf", 'public');

            $koranPath = $request->file('koran')
                ->storeAs('team_docs', "{$baseSlug}_{$categorySlug}_koran_{$timestamp}." . $request->file('koran')->extension(), 'public');

            Log::info('✅ Documents saved: ' . $recommendationPath . ', ' . $koranPath);

            // 🔥🔥🔥 GENERATE UNIK REFERRAL CODE UNTUK SETIAP KATEGORI
            $referralCode = strtoupper(Str::random(8));

            // Pastikan referral code unik
            while (TeamList::where('referral_code', $referralCode)->exists()) {
                $referralCode = strtoupper(Str::random(8));
            }

            // 🔥🔥🔥 CREATE TEAM UNTUK KATEGORI INI
            $team = TeamList::create([
                'school_name' => $schoolName,
                'school_id' => $schoolId,
                'school_logo' => $schoolLogoPath,
                'referral_code' => $referralCode, // ✅ SET REFERRAL CODE UNTUK KATEGORI INI
                'competition' => $validated['competition'],
                'season' => $validated['season'],
                'series' => $validated['series'],
                'team_category' => $validated['team_category'],
                'registered_by' => $validated['registered_by'],
                'locked_status' => 'unlocked',
                'verification_status' => 'unverified',
                'recommendation_letter' => $recommendationPath,
                'koran' => $koranPath,
                'jersey_home' => null,
                'jersey_away' => null,
                'jersey_alternate' => null,
                'payment_proof' => null,
                'is_leader_paid' => false,
                'payment_status' => 'pending',
                'payment_date' => null,
            ]);

            Log::info('✅ Team created with ID: ' . $team->team_id);
            Log::info('✅ Category: ' . $team->team_category);
            Log::info('✅ Referral Code: ' . $team->referral_code);
            Log::info('✅ Registered_by: ' . $team->registered_by);

            // 🔥🔥🔥 Normalize category untuk success page
            $normalizedCategory = $this->normalizeCategory($validated['team_category']);

            // 🔥🔥🔥 SIMPAN KE SESSION untuk success page
            session([
                'created_team_id' => $team->team_id,
                'created_team_category' => $validated['team_category'],
                'created_school_name' => $schoolName,
                'is_first_team_for_category' => true,
                'registered_by_name' => $validated['registered_by'],
                'normalized_category' => $normalizedCategory,
                'team_paid' => false,
                'current_can_be_leader' => true,
                'current_player_category' => $normalizedCategory,
                'referral_code' => $referralCode,
            ]);

            Log::info('Session after create team:', [
                'created_team_id' => session('created_team_id'),
                'team_category' => session('created_team_category'),
                'referral_code' => session('referral_code'),
                'registered_by' => session('registered_by_name')
            ]);

            // 🔥🔥🔥 REDIRECT KE HALAMAN SUCCESS
            Log::info('🎉 Redirecting to team success page for team: ' . $team->team_id);

            return redirect()->route('form.team.success', [
                'team_id' => $team->team_id
            ])->with('success', 'Tim ' . $validated['team_category'] . ' berhasil dibuat! Silakan lengkapi data diri Anda sebagai Kapten.');
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
     * 🔥🔥🔥 Show Team Success Page
     */
    public function showTeamSuccessPage($team_id)
    {
        try {
            Log::info('=== SHOW TEAM SUCCESS PAGE ===');
            Log::info('Team ID: ' . $team_id);

            $team = TeamList::findOrFail($team_id);

            // Ambil dari session atau generate ulang
            $normalizedCategory = session('normalized_category', $this->normalizeCategory($team->team_category));
            $referralCode = session('referral_code', $team->referral_code);

            // ✅ AMBIL TERMS & CONDITIONS UNTUK DITAMPILKAN DI SUCCESS PAGE
            $latestTerm = \App\Models\TermCondition::orderBy('year', 'desc')->first();

            Log::info('Team success page for: ' . $team->school_name . ' - ' . $team->team_category);
            Log::info('Referral Code: ' . $referralCode);
            Log::info('Registered_by: ' . $team->registered_by);

            return view('user.form.form_team_success', compact('team', 'normalizedCategory', 'referralCode', 'latestTerm'));
        } catch (\Exception $e) {
            Log::error('❌ Error in showTeamSuccessPage: ' . $e->getMessage());
            return redirect()->route('form.team.choice')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Normalize category
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
        } elseif (str_contains($teamCategory, 'official')) {
            return 'official';
        } else {
            return strtolower($teamCategory);
        }
    }

    /**
     * Upload school logo
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
     * Check school availability for autocomplete
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
     * Check if school exists
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
     * Check if team already exists for category
     */
    public function checkExistingTeam(Request $request)
    {
        $request->validate([
            'school_name' => 'required',
            'team_category' => 'required|in:Basket Putra,Basket Putri,Dancer,Official',
            'season' => 'required'
        ]);

        $existingTeam = TeamList::where('school_name', $request->school_name)
            ->where('season', $request->season)
            ->where('team_category', $request->team_category)
            ->first();

        if ($existingTeam) {
            $message = 'Tim ' . $request->team_category . ' untuk "' . $request->school_name . '" sudah ada untuk season ' . $request->season . '!';

            if ($existingTeam->referral_code && $existingTeam->is_leader_paid) {
                $message .= ' Gunakan referral code: ' . $existingTeam->referral_code . ' untuk bergabung.';
            } else {
                $message .= ' Tim ini belum memiliki Kapten yang membayar. Silakan tunggu atau hubungi Kapten tim.';
            }

            return response()->json([
                'exists' => true,
                'team' => $existingTeam,
                'message' => $message,
                'has_paid_leader' => $existingTeam->is_leader_paid,
                'referral_code' => $existingTeam->referral_code,
                'registered_by' => $existingTeam->registered_by
            ]);
        }

        return response()->json(['exists' => false]);
    }

    /**
     * Get all teams for a school in a season
     */
    public function getSchoolTeams(Request $request)
    {
        $request->validate([
            'school_name' => 'required',
            'season' => 'required'
        ]);

        $teams = TeamList::where('school_name', $request->school_name)
            ->where('season', $request->season)
            ->get();

        return response()->json([
            'teams' => $teams,
            'count' => $teams->count()
        ]);
    }
<<<<<<< HEAD

    /**
     * ✅ Download Syarat & Ketentuan Terbaru
     */
    public function downloadTerms()
    {
        $latestTerm = \App\Models\TermCondition::orderBy('year', 'desc')->first();

        if (!$latestTerm || !$latestTerm->links) {
            return redirect()->back()->with('error', 'Dokumen Syarat & Ketentuan tidak ditemukan.');
        }

        if ($latestTerm->is_file) {
            return redirect()->away($latestTerm->getDirectDownloadLink());
        } else {
            return redirect()->away($latestTerm->links);
        }
    }

    /**
     * ✅ Preview Syarat & Ketentuan Terbaru
     */
    public function previewTerms()
    {
        $latestTerm = \App\Models\TermCondition::orderBy('year', 'desc')->first();

        if (!$latestTerm || !$latestTerm->links) {
            return redirect()->back()->with('error', 'Dokumen Syarat & Ketentuan tidak ditemukan.');
        }

        if ($latestTerm->is_file && $latestTerm->google_drive_embed_url) {
            return redirect()->away($latestTerm->google_drive_embed_url);
        } else {
            return redirect()->away($latestTerm->links);
        }
    }
}
=======
}
>>>>>>> 8205add309977eadbd168ea201721274cc31f878
