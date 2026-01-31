<?php

namespace App\Http\Controllers\Form;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\TeamList;
use App\Models\City;
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
            ->where('referral_code', '!=', '')
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

        session([
            'joining_team_id' => $team->team_id,
            'joining_team_category' => $team->team_category,
            'joining_school_name' => $team->school_name,
        ]);

        return redirect()->route('form.player.create', ['team_id' => $team->team_id])
            ->with('success', 'Berhasil bergabung dengan tim! Silakan lengkapi data diri Anda.');
    }

    /**
     * Proses CREATE TEAM (TANPA BUKTI BAYAR)
     */
    public function createTeam(Request $request)
    {
        try {
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
            Log::info('Validation passed');

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
                // Jika tidak upload logo baru, ambil dari sekolah (jika ada)
                elseif ($school->school_logo) {
                    $schoolLogoPath = $school->school_logo;
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
                    // Jika tidak upload logo baru, ambil dari sekolah
                    elseif ($school->school_logo) {
                        $schoolLogoPath = $school->school_logo;
                    }
                    
                    session()->flash('info', 'Sekolah "' . $schoolName . '" sudah terdaftar di sistem.');
                } else {
                    // Upload logo untuk sekolah baru (ke tabel schools)
                    $schoolLogoForTableSchools = null;
                    if ($request->hasFile('school_logo')) {
                        $schoolLogoForTableSchools = $this->uploadSchoolLogo(
                            $request->file('school_logo'), 
                            $validated['new_school_name']
                        );
                        // Simpan juga untuk team_list
                        $schoolLogoPath = $schoolLogoForTableSchools;
                    }

                    $school = School::create([
                        'school_name' => $validated['new_school_name'],
                        'city_id' => $validated['new_city_id'],
                        'category_name' => $validated['new_category_name'],
                        'type' => $validated['new_type'],
                        'school_logo' => $schoolLogoForTableSchools,
                    ]);
                    $schoolName = $school->school_name;
                    $schoolId = $school->id;
                }
            }

            // CEK TIM YANG SUDAH ADA
            $existingTeamSameCategory = TeamList::where('school_name', $schoolName)
                ->where('team_category', $validated['team_category'])
                ->where('season', $validated['season'])
                ->first();

            if ($existingTeamSameCategory) {
                return back()->withErrors([
                    'team_category' => 'Tim ' . $validated['team_category'] . ' untuk ' . $schoolName . ' pada season ' . $validated['season'] . ' sudah ada!'
                ])->withInput();
            }

            // CEK APAKAH SEKOLAH SUDAH PUNYA REFERRAL CODE
            $existingTeamWithReferral = TeamList::where('school_name', $schoolName)
                ->where('season', $validated['season'])
                ->where('referral_code', '!=', '')
                ->where('is_leader_paid', true)
                ->first();

            $referralCode = '';
            $isFirstTeamForSchool = false;

            if ($existingTeamWithReferral) {
                $referralCode = $existingTeamWithReferral->referral_code;
                Log::info('Using existing referral code: ' . $referralCode);
            } else {
                $referralCode = '';
                $isFirstTeamForSchool = true;
                Log::info('No referral code yet (empty string), will generate after payment');
            }

            // SIMPAN FILE DOKUMEN
            Storage::disk('public')->makeDirectory('team_docs');
            $baseSlug = Str::slug($schoolName);
            $timestamp = time();

            $recommendationPath = $request->file('recommendation_letter')
                ->storeAs('team_docs', "{$baseSlug}_recommendation_{$timestamp}.pdf", 'public');

            $koranPath = $request->file('koran')
                ->storeAs('team_docs', "{$baseSlug}_koran_{$timestamp}." . $request->file('koran')->extension(), 'public');

            // BUAT TIM DI TEAM_LIST
            $team = TeamList::create([
                'school_name' => $schoolName,
                'school_id' => $schoolId,
                'school_logo' => $schoolLogoPath, // LOGO DISIMPAN DI TEAM_LIST
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
                'payment_proof' => null,
                'is_leader_paid' => false,
                'payment_status' => 'pending',
                'payment_date' => null,
            ]);

            Log::info('Team created with ID: ' . $team->team_id . ', Logo path: ' . $schoolLogoPath);

            // SIMPAN KE SESSION
            session([
                'created_team_id' => $team->team_id,
                'created_team_category' => $team->team_category,
                'created_school_name' => $schoolName,
                'is_first_team_for_school' => $isFirstTeamForSchool,
                'registered_by_name' => $validated['registered_by'],
                'team_paid' => false,
            ]);

            return redirect()->route('form.player.create', ['team_id' => $team->team_id])
                ->with('success', 'Tim berhasil dibuat! Sekarang lengkapi data diri Anda sebagai Kapten.')
                ->with('info', 'Sebagai Kapten, Anda perlu upload bukti pembayaran di langkah berikutnya.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error: ', $e->errors());
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error in createTeam: ' . $e->getMessage());
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
        
        // Tambahkan kategori tim ke nama file jika ada
        $categorySuffix = $teamCategory ? '_' . Str::slug($teamCategory) : '';
        $filename = "{$baseSlug}{$categorySuffix}_logo_{$timestamp}.{$extension}";
        
        // Simpan ke folder school_logos di storage public
        $path = $file->storeAs('school_logos', $filename, 'public');
        
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
            ->get(['id', 'school_name', 'city_id', 'category_name', 'type', 'school_logo']);

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

        $existingTeam = TeamList::where('school_name', $request->school_name)
            ->where('team_category', $request->team_category)
            ->where('season', $request->season)
            ->first();

        if ($existingTeam) {
            $message = 'Tim ' . $request->team_category . ' untuk sekolah ini pada season ' . $request->season . ' sudah terdaftar!';

            $hasReferral = TeamList::where('school_name', $request->school_name)
                ->where('season', $request->season)
                ->where('referral_code', '!=', '')
                ->where('is_leader_paid', true)
                ->exists();

            if ($hasReferral) {
                $firstTeam = TeamList::where('school_name', $request->school_name)
                    ->where('season', $request->season)
                    ->where('referral_code', '!=', '')
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