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
     * Process role selection
     */
    public function processRoleSelection(Request $request)
    {
        $validated = $request->validate([
            'referral_code' => 'required|exists:team_list,referral_code',
            'team_category' => 'required|in:Basket Putra,Basket Putri,Dancer,Official'
        ]);

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

        $playerCategory = $this->normalizeCategory($validated['team_category']);

        $targetTeamId = $primaryTeam->team_id;
        $targetTeamCategory = $primaryTeam->team_category;
        $isNewTeamForCategory = false;

        if (in_array($validated['team_category'], ['Basket Putra', 'Basket Putri'])) {
            $existingBasketTeam = TeamList::where('school_name', $primaryTeam->school_name)
                ->where('season', $primaryTeam->season)
                ->where('team_category', $validated['team_category'])
                ->first();

            if ($existingBasketTeam) {
                $targetTeamId = $existingBasketTeam->team_id;
                $targetTeamCategory = $existingBasketTeam->team_category;
                $isNewTeamForCategory = false;
                Log::info('✅ Using existing basket team: ' . $targetTeamId);
            } else {
                $isNewTeamForCategory = true;
                Log::info('🆕 User will create new team for category: ' . $validated['team_category']);
            }
        }

        $canBeLeader = false;

        if ($isNewTeamForCategory) {
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

        session([
            'current_team_id' => $targetTeamId,
            'current_team_category' => $validated['team_category'],
            'current_player_category' => $playerCategory,
            'join_referral_code' => $validated['referral_code'],
            'join_school_name' => $primaryTeam->school_name,
            'join_season' => $primaryTeam->season,
            'current_can_be_leader' => $canBeLeader,
            'is_new_team_for_category' => $isNewTeamForCategory,
        ]);

        Log::info('✅ Session set for role selection:', [
            'team_id' => $targetTeamId,
            'team_category' => $validated['team_category'],
            'player_category' => $playerCategory,
            'canBeLeader' => $canBeLeader ? 'YES' : 'NO',
            'is_new_team' => $isNewTeamForCategory ? 'YES' : 'NO'
        ]);

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

  /**
 * 🔥 CREATE TEAM WITH LOGO LOGIC - FIXED
 * - Logo disimpan di tabel SCHOOLS, BUKAN di team_list
 * - Jika sekolah sudah punya logo, tidak perlu upload ulang
 * - Jika sekolah belum punya logo, wajib upload
 */
public function createTeam(Request $request)
{
    try {
        Log::info('=== CREATE TEAM START ===');

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
            // Jika sekolah existing tapi belum punya logo, wajib upload
            $existingSchool = School::find($request->existing_school_id);
            if ($existingSchool && !$existingSchool->hasLogo()) {
                $validationRules['school_logo'] = 'required|image|mimes:jpg,jpeg,png,webp|max:2048';
            }
        } else {
            $validationRules['new_school_name'] = 'required|string|max:255';
            $validationRules['new_city_id'] = 'required|exists:cities,id';
            $validationRules['new_category_name'] = 'required|in:SMA,SMK,MA';
            $validationRules['new_type'] = 'required|in:NEGERI,SWASTA';
            $validationRules['school_logo'] = 'required|image|mimes:jpg,jpeg,png,webp|max:2048';
        }

        $validated = $request->validate($validationRules);
        Log::info('✅ Validation passed');

        $schoolName = '';
        $schoolId = null;
        $schoolLogoPath = null;

        if ($validated['school_option'] == 'existing') {
            $school = School::findOrFail($validated['existing_school_id']);
            $schoolName = $school->school_name;
            $schoolId = $school->id;

            Log::info('School found: ' . $schoolName);

            // 🔥 CEK: Apakah sekolah sudah punya logo?
            if ($school->hasLogo()) {
                // ✅ Sekolah sudah punya logo, pakai yang existing
                $schoolLogoPath = $school->school_logo;
                Log::info('✅ Using existing school logo from schools table: ' . $schoolLogoPath);
            } else {
                // ❌ Sekolah belum punya logo, upload logo baru
                $uploadedFile = $request->file('school_logo');

                if ($uploadedFile && $uploadedFile->isValid()) {
                    $schoolLogoPath = $this->uploadSchoolLogo($uploadedFile, $schoolName, $validated['team_category']);
                    
                    // 🔥 SIMPAN LOGO KE TABEL SCHOOLS
                    $school->school_logo = $schoolLogoPath;
                    $school->save();
                    Log::info('✅ Logo uploaded and saved to schools table: ' . $schoolLogoPath);
                } else {
                    Log::warning('No logo uploaded for school without logo');
                    return back()->withErrors(['school_logo' => 'Sekolah belum memiliki logo. Silakan upload logo.'])->withInput();
                }
            }
        } else {
            // 🔥 NEW SCHOOL
            $existingSchool = School::where('school_name', $validated['new_school_name'])->first();

            if ($existingSchool) {
                return back()->withErrors([
                    'new_school_name' => 'Sekolah sudah terdaftar! Gunakan opsi "Pilih Sekolah".'
                ])->withInput();
            }

            $school = School::create([
                'school_name' => $validated['new_school_name'],
                'city_id' => $validated['new_city_id'],
                'category_name' => $validated['new_category_name'],
                'type' => $validated['new_type'],
            ]);
            $schoolName = $school->school_name;
            $schoolId = $school->id;

            if ($request->hasFile('school_logo')) {
                $schoolLogoPath = $this->uploadSchoolLogo($request->file('school_logo'), $schoolName, $validated['team_category']);
                $school->school_logo = $schoolLogoPath;
                $school->save();
                Log::info('✅ New school created with logo: ' . $schoolLogoPath);
            }
        }

        // Cek tim existing untuk season dan kategori yang sama
        $existingTeam = TeamList::where('school_name', $schoolName)
            ->where('season', $validated['season'])
            ->where('team_category', $validated['team_category'])
            ->first();

        if ($existingTeam) {
            $referralCode = $existingTeam->referral_code;
            if ($referralCode && $existingTeam->is_leader_paid) {
                return redirect()->route('form.team.join')
                    ->with('warning', 'Tim sudah ada! Gunakan referral code: ' . $referralCode)
                    ->with('referral_code', $referralCode);
            } else {
                return redirect()->route('form.team.join')
                    ->with('warning', 'Tim sudah ada tetapi Kapten belum membayar.');
            }
        }

        // Save documents
        Storage::disk('public')->makeDirectory('team_docs');
        $baseSlug = Str::slug($schoolName);
        $categorySlug = Str::slug($validated['team_category']);
        $timestamp = time();

        $recommendationPath = $request->file('recommendation_letter')
            ->storeAs('team_docs', "{$baseSlug}_{$categorySlug}_rec_{$timestamp}.pdf", 'public');

        $koranPath = $request->file('koran')
            ->storeAs('team_docs', "{$baseSlug}_{$categorySlug}_koran_{$timestamp}." . $request->file('koran')->extension(), 'public');

        // Generate referral code (Format: NAMASEKOLAH-KATEGORI-RANDOM)
        $categoryShort = match ($validated['team_category']) {
            'Basket Putra' => 'BP',
            'Basket Putri' => 'BPT',
            'Dancer' => 'DNC',
            'Official' => 'OFC',
            default => 'TM'
        };

        // Ambil 5 karakter pertama dari slug (tanpa dash)
        $schoolCode = strtoupper(substr(str_replace('-', '', $baseSlug), 0, 5));

        do {
            $randomPart = strtoupper(Str::random(4));
            $referralCode = $schoolCode . '-' . $categoryShort . '-' . $randomPart;
        } while (TeamList::where('referral_code', $referralCode)->exists());

        Log::info('✅ Generated referral code: ' . $referralCode);

        // 🔥 CREATE TEAM - TIDAK MENYIMPAN LOGO DI TEAM_LIST
        $team = TeamList::create([
            'school_name' => $schoolName,
            'school_id' => $schoolId,
            'school_logo' => null, // 🔥 KOSONGKAN! Logo diambil dari tabel schools
            'referral_code' => $referralCode,
            'competition' => $validated['competition'],
            'season' => $validated['season'],
            'series' => $validated['series'],
            'team_category' => $validated['team_category'],
            'registered_by' => $validated['registered_by'],
            'locked_status' => 'unlocked',
            'verification_status' => 'unverified',
            'recommendation_letter' => $recommendationPath,
            'koran' => $koranPath,
            'is_leader_paid' => false,
            'payment_status' => 'pending',
        ]);

        Log::info('✅ Team created with ID: ' . $team->team_id);

        session([
            'created_team_id' => $team->team_id,
            'created_team_category' => $validated['team_category'],
            'created_school_name' => $schoolName,
            'normalized_category' => $this->normalizeCategory($validated['team_category']),
            'referral_code' => $referralCode,
            'current_can_be_leader' => true,
        ]);

        return redirect()->route('form.team.success', ['team_id' => $team->team_id])
            ->with('success', 'Tim berhasil dibuat!');
    } catch (\Illuminate\Validation\ValidationException $e) {
        return back()->withErrors($e->errors())->withInput();
    } catch (\Exception $e) {
        Log::error('❌ Error: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])->withInput();
    }
}

    /**
     * Show Team Success Page
     */
    public function showTeamSuccessPage($team_id)
    {
        try {
            Log::info('=== SHOW TEAM SUCCESS PAGE ===');
            Log::info('Team ID: ' . $team_id);

            $team = TeamList::findOrFail($team_id);

            $normalizedCategory = session('normalized_category', $this->normalizeCategory($team->team_category));
            $referralCode = session('referral_code', $team->referral_code);

            $latestTerm = \App\Models\TermCondition::orderBy('year', 'desc')->first();

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
            ->get(['id', 'school_name', 'city_id', 'category_name', 'type', 'school_logo']);

        return response()->json($schools);
    }

    /**
     * Check if school exists (untuk validasi real-time)
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
            'has_logo' => $school ? $school->hasLogo() : false,
            'message' => $exists ? 'Sekolah sudah terdaftar! Silakan gunakan opsi "Pilih Sekolah".' : 'Sekolah belum terdaftar. Silakan lengkapi data.'
        ]);
    }

    /**
     * Check if school has logo (untuk validasi)
     */
    public function checkSchoolLogo(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id'
        ]);

        $school = School::find($request->school_id);

        return response()->json([
            'has_logo' => $school && $school->hasLogo(),
            'logo_url' => $school && $school->hasLogo() ? $school->logo_url : null,
            'message' => $school && $school->hasLogo() ? 'Sekolah sudah memiliki logo.' : 'Sekolah belum memiliki logo. Silakan upload logo.'
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

    /**
     * Download Syarat & Ketentuan Terbaru
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
     * Preview Syarat & Ketentuan Terbaru
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
