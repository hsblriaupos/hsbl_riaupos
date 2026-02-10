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
        $validated = $request->validate([
            'referral_code' => 'required|exists:team_list,referral_code',
            'team_category' => 'required|in:Basket Putra,Basket Putri,Dancer,Official'
        ]);

        $team = TeamList::where('referral_code', $validated['referral_code'])->first();

        if (!$team) {
            return redirect()->route('form.team.join')
                ->with('error', 'Referral code tidak valid.');
        }

        // Normalisasi kategori
        $playerCategory = $this->normalizeCategory($validated['team_category']);

        Log::info('Role Selection:', [
            'team_id' => $team->team_id,
            'team_category' => $validated['team_category'],
            'player_category' => $playerCategory,
            'is_leader_paid' => $team->is_leader_paid
        ]);

        // LOGIKA LEADER
        $canBeLeader = false;
        
        if ($validated['team_category'] === 'Official') {
            // Cek apakah sudah ada leader official di tim ini
            $existingLeaderCount = OfficialList::where('team_id', $team->team_id)
                ->where('role', 'Leader')
                ->count();
                
            if ($existingLeaderCount === 0) {
                $canBeLeader = true;
            }
        } else {
            // Untuk player/dancer, cek apakah tim sudah bayar
            if ($team->is_leader_paid) {
                // Cek apakah sudah ada leader untuk kategori ini
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

        // Redirect ke form yang sesuai berdasarkan kategori
        switch ($validated['team_category']) {
            case 'Basket Putra':
            case 'Basket Putri':
                Log::info('Redirecting to player form for team: ' . $team->team_id . ' category: ' . $playerCategory . ', canBeLeader: ' . ($canBeLeader ? 'YES' : 'NO'));
                return redirect()->route('form.player.create.with-category', [
                    'team_id' => $team->team_id,
                    'category' => $playerCategory
                ]);
                
            case 'Dancer':
                Log::info('Redirecting to dancer form for team: ' . $team->team_id . ', canBeLeader: ' . ($canBeLeader ? 'YES' : 'NO'));
                return redirect()->route('form.dancer.create', [
                    'team_id' => $team->team_id
                ]);
                
            case 'Official':
                Log::info('Redirecting to official form for team: ' . $team->team_id . ', canBeLeader: ' . ($canBeLeader ? 'YES' : 'NO'));
                return redirect()->route('form.official.create', [
                    'team_id' => $team->team_id
                ]);
                
            default:
                Log::error('Invalid category: ' . $validated['team_category']);
                return redirect()->back()->with('error', 'Kategori tidak valid.');
        }
    }

    /**
     * Normalisasi kategori dari team_list ke player_list/dancer_list/official
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

            // PROSES SEKOLAH
            $schoolName = '';
            $schoolId = null;
            $schoolLogoPath = null;

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
                    // Buat sekolah baru
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

            // 🔥 PERUBAHAN PENTING: CEK APAKAH SEKOLAH SUDAH PUNYA TIM DI SEASON INI
            $existingTeamForSchool = TeamList::where('school_name', $schoolName)
                ->where('season', $validated['season'])
                ->first();

            $team = null;
            $isFirstTeamForSchool = false;
            $specificTeamCategory = $validated['team_category'];

            if ($existingTeamForSchool) {
                // 🔥 SEKOLAH SUDAH PUNYA TIM → ARAHKAN KE FORM JOIN
                $team = $existingTeamForSchool;
                $referralCode = $team->referral_code;
                
                Log::info('✅ School already has team: ' . $schoolName . ' (ID: ' . $team->team_id . ')');
                
                if ($referralCode && $team->is_leader_paid) {
                    // Sudah ada referral code dan sudah bayar → arahkan ke join
                    return redirect()->route('form.team.join')
                        ->with('warning', 'Sekolah "' . $schoolName . '" sudah memiliki tim untuk season ' . $validated['season'] . '. Gunakan referral code: ' . $referralCode . ' untuk bergabung.')
                        ->with('referral_code', $referralCode);
                } else {
                    // Belum ada referral code atau belum bayar
                    return redirect()->route('form.team.join')
                        ->with('warning', 'Sekolah "' . $schoolName . '" sudah memiliki tim untuk season ' . $validated['season'] . ' tetapi Kapten belum menyelesaikan pembayaran. Silakan tunggu atau hubungi Kapten tim.');
                }
            }

            // 🔥 SEKOLAH BELUM PUNYA TIM → BUAT TIM BARU (HANYA UNTUK PERTAMA KALI)
            $isFirstTeamForSchool = true;
            
            // Upload logo sekolah jika ada
            if ($request->hasFile('school_logo')) {
                $schoolLogoPath = $this->uploadSchoolLogo(
                    $request->file('school_logo'),
                    $schoolName,
                    $specificTeamCategory
                );
            }

            // SIMPAN FILE DOKUMEN
            Storage::disk('public')->makeDirectory('team_docs');
            $baseSlug = Str::slug($schoolName);
            $timestamp = time();

            $recommendationPath = $request->file('recommendation_letter')
                ->storeAs('team_docs', "{$baseSlug}_recommendation_{$timestamp}.pdf", 'public');

            $koranPath = $request->file('koran')
                ->storeAs('team_docs', "{$baseSlug}_koran_{$timestamp}." . $request->file('koran')->extension(), 'public');

            Log::info('✅ Documents saved: ' . $recommendationPath . ', ' . $koranPath);

            // BUAT TIM BARU DI TEAM_LIST (HANYA 1 UNTUK SEKOLAH)
            $team = TeamList::create([
                'school_name' => $schoolName,
                'school_id' => $schoolId,
                'school_logo' => $schoolLogoPath,
                'referral_code' => null, // Akan digenerate setelah pembayaran
                'competition' => $validated['competition'],
                'season' => $validated['season'],
                'series' => $validated['series'],
                'team_category' => $specificTeamCategory, // Kategori tim pertama yang mendaftar
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

            Log::info('✅ New team created with ID: ' . $team->team_id . ' for school: ' . $schoolName);

            // 🔥 SET SESSION
            $normalizedCategory = $this->normalizeCategory($specificTeamCategory);
            
            session([
                'created_team_id' => $team->team_id,
                'created_team_category' => $specificTeamCategory,
                'created_school_name' => $schoolName,
                'is_first_team_for_school' => $isFirstTeamForSchool,
                'registered_by_name' => $validated['registered_by'],
                'team_paid' => false,
                'current_can_be_leader' => true, // Yang membuat tim pertama OTOMATIS bisa jadi Leader
                'current_player_category' => $normalizedCategory,
            ]);

            Log::info('Session after create team:', [
                'created_team_id' => session('created_team_id'),
                'is_first_team_for_school' => session('is_first_team_for_school'),
                'current_can_be_leader' => session('current_can_be_leader'),
                'current_player_category' => session('current_player_category')
            ]);

            // Redirect ke form yang sesuai
            switch ($specificTeamCategory) {
                case 'Basket Putra':
                case 'Basket Putri':
                    Log::info('🏀 Redirecting FIRST TEAM creator to player form');
                    return redirect()->route('form.player.create.with-category', [
                        'team_id' => $team->team_id,
                        'category' => $normalizedCategory
                    ])->with('success', 'Tim berhasil dibuat! Sekarang lengkapi data diri Anda sebagai Kapten.')
                        ->with('info', 'Sebagai Kapten, Anda perlu upload bukti pembayaran di langkah berikutnya.');
                        
                case 'Dancer':
                    Log::info('🎭 Redirecting FIRST TEAM creator to dancer form');
                    return redirect()->route('form.dancer.create', [
                        'team_id' => $team->team_id
                    ])->with('success', 'Tim berhasil dibuat! Sekarang lengkapi data diri Anda sebagai Kapten Dancer.')
                        ->with('info', 'Sebagai Kapten, Anda perlu upload bukti pembayaran di langkah berikutnya.');
                        
                case 'Official':
                    Log::info('📋 Redirecting FIRST TEAM creator to official form');
                    return redirect()->route('form.official.create', [
                        'team_id' => $team->team_id
                    ])->with('success', 'Tim berhasil dibuat! Sekarang lengkapi data diri Anda sebagai Leader Official.')
                        ->with('info', 'Official tidak memerlukan pembayaran.');
                        
                default:
                    Log::error('Invalid team category after create: ' . $specificTeamCategory);
                    return back()->withErrors(['error' => 'Kategori tim tidak valid.'])->withInput();
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
            'team_category' => 'required|in:Basket Putra,Basket Putri,Dancer,Official',
            'season' => 'required'
        ]);

        // 🔥 PERUBAHAN: Cek apakah sekolah sudah punya tim di season ini (APAPUN kategorinya)
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