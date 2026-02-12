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
     * 🔥🔥🔥 FIX UTAMA: Process role selection with correct team assignment
     */
    public function processRoleSelection(Request $request)
    {
        $validated = $request->validate([
            'referral_code' => 'required|exists:team_list,referral_code',
            'team_category' => 'required|in:Basket Putra,Basket Putri,Dancer,Official'
        ]);

        $team = TeamList::where('referral_code', $validated['referral_code'])->first();

        if (!$team) {
            return redirect()->route('form.team.join')
                ->with('error', 'Referral code tidak valid.');
        }

        // Normalize category
        $playerCategory = $this->normalizeCategory($validated['team_category']);

        Log::info('Role Selection:', [
            'team_id' => $team->team_id,
            'team_category' => $validated['team_category'],
            'player_category' => $playerCategory,
            'is_leader_paid' => $team->is_leader_paid
        ]);

        // Leader logic
        $canBeLeader = false;

        if ($validated['team_category'] === 'Official') {
            $existingLeaderCount = OfficialList::where('team_id', $team->team_id)
                ->where('role', 'Leader')
                ->count();

            if ($existingLeaderCount === 0) {
                $canBeLeader = true;
            }
        } else {
            if ($team->is_leader_paid) {
                if ($playerCategory === 'dancer') {
                    $existingLeaderCount = DancerList::where('team_id', $team->team_id)
                        ->where('role', 'Leader')
                        ->count();

                    if ($existingLeaderCount === 0) {
                        $canBeLeader = true;
                    }
                } else {
                    $existingLeaderCount = PlayerList::where('team_id', $team->team_id)
                        ->where('category', $playerCategory)
                        ->where('role', 'Leader')
                        ->count();

                    if ($existingLeaderCount === 0) {
                        $canBeLeader = true;
                    }
                }
            }
        }

        session([
            'current_team_id' => $team->team_id,
            'current_team_category' => $validated['team_category'],
            'current_player_category' => $playerCategory,
            'join_referral_code' => $validated['referral_code'],
            'current_can_be_leader' => $canBeLeader,
        ]);

        Log::info('Session set for role selection:', [
            'team_id' => $team->team_id,
            'team_category' => $validated['team_category'],
            'canBeLeader' => $canBeLeader ? 'YES' : 'NO'
        ]);

        // 🔥🔥🔥 FIX: Redirect to CORRECT team based on category
        switch ($validated['team_category']) {
            case 'Basket Putra':
            case 'Basket Putri':
                // Find or create team with correct category
                $correctTeam = TeamList::where('school_name', $team->school_name)
                    ->where('season', $team->season)
                    ->where('team_category', $validated['team_category'])
                    ->first();

                if (!$correctTeam) {
                    Log::info('⚠️ Tim ' . $validated['team_category'] . ' belum ada, membuat baru...');

                    $correctTeam = TeamList::create([
                        'school_name' => $team->school_name,
                        'school_id' => $team->school_id,
                        'school_logo' => $team->school_logo,
                        'referral_code' => null,
                        'competition' => $team->competition,
                        'season' => $team->season,
                        'series' => $team->series,
                        'team_category' => $validated['team_category'],
                        'team_name' => null,
                        'registered_by' => '',
                        'locked_status' => 'unlocked',
                        'verification_status' => 'unverified',
                        'recommendation_letter' => null,
                        'koran' => null,
                        'jersey_home' => null,
                        'jersey_away' => null,
                        'jersey_alternate' => null,
                        'is_leader_paid' => $team->is_leader_paid,
                        'payment_status' => $team->payment_status,
                        'payment_date' => $team->payment_date,
                        'payment_proof' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Log::info('✅ Tim baru dibuat dengan ID: ' . $correctTeam->team_id);
                }

                Log::info('🏀 Redirecting to player form for team: ' . $correctTeam->team_id . ' category: ' . $playerCategory);

                return redirect()->route('form.player.create.with-category', [
                    'team_id' => $correctTeam->team_id,
                    'category' => $playerCategory
                ]);

            case 'Dancer':
                // Find or create dancer team
                $dancerTeam = TeamList::where('school_name', $team->school_name)
                    ->where('season', $team->season)
                    ->where('team_category', 'Dancer')
                    ->first();

                if (!$dancerTeam) {
                    Log::info('⚠️ Tim Dancer belum ada, membuat baru...');

                    $dancerTeam = TeamList::create([
                        'school_name' => $team->school_name,
                        'school_id' => $team->school_id,
                        'school_logo' => $team->school_logo,
                        'referral_code' => null,
                        'competition' => $team->competition,
                        'season' => $team->season,
                        'series' => $team->series,
                        'team_category' => 'Dancer',
                        'team_name' => null,
                        'registered_by' => '',
                        'locked_status' => 'unlocked',
                        'verification_status' => 'unverified',
                        'recommendation_letter' => null,
                        'koran' => null,
                        'jersey_home' => null,
                        'jersey_away' => null,
                        'jersey_alternate' => null,
                        'is_leader_paid' => $team->is_leader_paid,
                        'payment_status' => $team->payment_status,
                        'payment_date' => $team->payment_date,
                        'payment_proof' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Log::info('✅ Tim Dancer baru dibuat dengan ID: ' . $dancerTeam->team_id);
                }

                Log::info('💃 Redirecting to dancer form for team: ' . $dancerTeam->team_id);

                return redirect()->route('form.dancer.create', [
                    'team_id' => $dancerTeam->team_id
                ]);

            case 'Official':
                // Find or create official team
                $officialTeam = TeamList::where('school_name', $team->school_name)
                    ->where('season', $team->season)
                    ->where('team_category', 'Official')
                    ->first();

                if (!$officialTeam) {
                    Log::info('⚠️ Tim Official belum ada, membuat baru...');

                    $officialTeam = TeamList::create([
                        'school_name' => $team->school_name,
                        'school_id' => $team->school_id,
                        'school_logo' => $team->school_logo,
                        'referral_code' => null,
                        'competition' => $team->competition,
                        'season' => $team->season,
                        'series' => $team->series,
                        'team_category' => 'Official',
                        'team_name' => null,
                        'registered_by' => '',
                        'locked_status' => 'unlocked',
                        'verification_status' => 'unverified',
                        'recommendation_letter' => null,
                        'koran' => null,
                        'jersey_home' => null,
                        'jersey_away' => null,
                        'jersey_alternate' => null,
                        'is_leader_paid' => 0,
                        'payment_status' => 'pending',
                        'payment_date' => null,
                        'payment_proof' => null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    Log::info('✅ Tim Official baru dibuat dengan ID: ' . $officialTeam->team_id);
                }

                return redirect()->route('form.official.create', [
                    'team_id' => $officialTeam->team_id
                ]);

            default:
                Log::error('Invalid category: ' . $validated['team_category']);
                return redirect()->back()->with('error', 'Kategori tidak valid.');
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
     * Process CREATE TEAM - 🔥 FIXED: Redirect ke success page dulu!
     */
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

            // Check if school already has a team this season
            $existingTeamForSchool = TeamList::where('school_name', $schoolName)
                ->where('season', $validated['season'])
                ->first();

            if ($existingTeamForSchool) {
                $team = $existingTeamForSchool;
                $referralCode = $team->referral_code;

                Log::info('✅ School already has team: ' . $schoolName . ' (ID: ' . $team->team_id . ')');

                if ($referralCode && $team->is_leader_paid) {
                    return redirect()->route('form.team.join')
                        ->with('warning', 'Sekolah "' . $schoolName . '" sudah memiliki tim untuk season ' . $validated['season'] . '. Gunakan referral code: ' . $referralCode . ' untuk bergabung.')
                        ->with('referral_code', $referralCode);
                } else {
                    return redirect()->route('form.team.join')
                        ->with('warning', 'Sekolah "' . $schoolName . '" sudah memiliki tim untuk season ' . $validated['season'] . ' tetapi Kapten belum menyelesaikan pembayaran. Silakan tunggu atau hubungi Kapten tim.');
                }
            }

            // 🔥🔥🔥 School doesn't have team yet - create new team
            $isFirstTeamForSchool = true;

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
            $timestamp = time();

            $recommendationPath = $request->file('recommendation_letter')
                ->storeAs('team_docs', "{$baseSlug}_recommendation_{$timestamp}.pdf", 'public');

            $koranPath = $request->file('koran')
                ->storeAs('team_docs', "{$baseSlug}_koran_{$timestamp}." . $request->file('koran')->extension(), 'public');

            Log::info('✅ Documents saved: ' . $recommendationPath . ', ' . $koranPath);

            // Create new team
            $team = TeamList::create([
                'school_name' => $schoolName,
                'school_id' => $schoolId,
                'school_logo' => $schoolLogoPath,
                'referral_code' => null,
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

            Log::info('✅ New team created with ID: ' . $team->team_id . ' for school: ' . $schoolName);

            // 🔥🔥🔥 FIXED: Normalize category untuk success page
            $normalizedCategory = $this->normalizeCategory($validated['team_category']);

            // 🔥🔥🔥 SIMPAN KE SESSION untuk success page
            session([
                'created_team_id' => $team->team_id,
                'created_team_category' => $validated['team_category'],
                'created_school_name' => $schoolName,
                'is_first_team_for_school' => $isFirstTeamForSchool,
                'registered_by_name' => $validated['registered_by'],
                'normalized_category' => $normalizedCategory,
                'team_paid' => false,
                'current_can_be_leader' => true,
                'current_player_category' => $normalizedCategory,
            ]);

            Log::info('Session after create team:', [
                'created_team_id' => session('created_team_id'),
                'is_first_team_for_school' => session('is_first_team_for_school'),
                'current_can_be_leader' => session('current_can_be_leader'),
                'current_player_category' => session('current_player_category'),
                'normalized_category' => session('normalized_category')
            ]);

            // 🔥🔥🔥 FIXED: REDIRECT KE HALAMAN SUCCESS DULU!
            Log::info('🎉 Redirecting to team success page for team: ' . $team->team_id);

            return redirect()->route('form.team.success', [
                'team_id' => $team->team_id
            ])->with('success', 'Tim berhasil dibuat! Silakan lengkapi data diri Anda sebagai Kapten.');
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
     * 🔥🔥🔥 TAMBAHKAN METHOD BARU: Show Team Success Page
     */
    public function showTeamSuccessPage($team_id)
    {
        try {
            Log::info('=== SHOW TEAM SUCCESS PAGE ===');
            Log::info('Team ID: ' . $team_id);

            $team = TeamList::findOrFail($team_id);

            // Ambil dari session atau generate ulang
            $normalizedCategory = session('normalized_category', $this->normalizeCategory($team->team_category));

            Log::info('Team success page for: ' . $team->school_name . ' - ' . $team->team_category);
            Log::info('Normalized category: ' . $normalizedCategory);

            return view('user.form.form_team_success', compact('team', 'normalizedCategory'));
        } catch (\Exception $e) {
            Log::error('❌ Error in showTeamSuccessPage: ' . $e->getMessage());
            return redirect()->route('form.team.choice')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
            ->first();

        if ($existingTeam) {
            $message = 'Sekolah "' . $request->school_name . '" sudah memiliki tim untuk season ' . $request->season . '!';

            if ($existingTeam->referral_code && $existingTeam->is_leader_paid) {
                $message .= ' Gunakan referral code: ' . $existingTeam->referral_code . ' untuk bergabung dengan tim ' . $request->team_category . '.';
            } else {
                $message .= ' Tim ini belum memiliki Kapten yang membayar. Silakan tunggu atau hubungi Kapten tim.';
            }

            return response()->json([
                'exists' => true,
                'team' => $existingTeam,
                'message' => $message,
                'has_paid_leader' => $existingTeam->is_leader_paid,
                'referral_code' => $existingTeam->referral_code
            ]);
        }

        return response()->json(['exists' => false]);
    }
}
