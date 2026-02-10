<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\TeamList;
use App\Models\PlayerList;
use App\Models\DancerList;
use App\Models\OfficialList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SchoolDataProfileController extends Controller
{
    /**
     * Display a listing of the user's schools.
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login first.');
            }

            Log::info('SchoolDataProfileController@index - User data:', [
                'email' => $user->email, 
                'nik' => $user->nik,
                'name' => $user->name
            ]);

            // Get user's data from all tables
            $playerLists = PlayerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->get();

            $dancerLists = DancerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->get();

            $officialLists = OfficialList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->get();

            // Debug: Log semua data yang ditemukan
            Log::info('User data in tables:', [
                'player_count' => $playerLists->count(),
                'player_data' => $playerLists->map(function($p) {
                    return [
                        'id' => $p->id,
                        'team_id' => $p->team_id,
                        'school_id' => $p->school_id,
                        'role' => $p->role,
                        'email' => $p->email
                    ];
                })->toArray(),
                'dancer_count' => $dancerLists->count(),
                'dancer_data' => $dancerLists->map(function($d) {
                    return [
                        'dancer_id' => $d->dancer_id,
                        'school_name' => $d->school_name,
                        'role' => $d->role,
                        'email' => $d->email
                    ];
                })->toArray(),
                'official_count' => $officialLists->count(),
                'official_data' => $officialLists->map(function($o) {
                    return [
                        'official_id' => $o->official_id,
                        'school_id' => $o->school_id,
                        'team_role' => $o->team_role,
                        'role' => $o->role,
                        'email' => $o->email
                    ];
                })->toArray()
            ]);

            // Get all schools where user is registered based on different matching criteria
            $allSchools = $this->getAllSchoolsForUser($user, $playerLists, $dancerLists, $officialLists);

            // Process each school to add logo URL and role data
            $processedSchools = $allSchools->map(function($school) use ($playerLists, $dancerLists, $officialLists, $user) {
                // Get logo URL
                $school->logo_url = $this->getSchoolLogoUrl($school->school_logo);
                
                // Get user's specific data for this school
                $userRoles = $this->getUserRolesForSchool($school, $playerLists, $dancerLists, $officialLists, $user);
                
                // Attach user roles to school object
                $school->isPlayer = $userRoles['isPlayer'];
                $school->isDancer = $userRoles['isDancer'];
                $school->isOfficial = $userRoles['isOfficial'];
                $school->playerRole = $userRoles['playerRole'];
                $school->dancerRole = $userRoles['dancerRole'];
                $school->officialRole = $userRoles['officialRole'];
                $school->playerData = $userRoles['playerData'];
                $school->dancerData = $userRoles['dancerData'];
                $school->officialData = $userRoles['officialData'];
                
                Log::info("School processed: {$school->school_name}", [
                    'team_id' => $school->team_id,
                    'school_id' => $school->school_id,
                    'isPlayer' => $school->isPlayer,
                    'playerRole' => $school->playerRole,
                    'isDancer' => $school->isDancer,
                    'dancerRole' => $school->dancerRole,
                    'isOfficial' => $school->isOfficial,
                    'officialRole' => $school->officialRole
                ]);
                
                return $school;
            });

            // Filter schools where user has at least one role
            $filteredSchools = $processedSchools->filter(function($school) {
                $hasRole = $school->isPlayer || $school->isDancer || $school->isOfficial;
                Log::info("School {$school->school_name} has role: " . ($hasRole ? 'YES' : 'NO'));
                return $hasRole;
            });

            Log::info('Final schools after filtering:', [
                'total' => $filteredSchools->count(),
                'schools' => $filteredSchools->pluck('school_name')->toArray()
            ]);

            // Count statistics
            $totalSchools = $filteredSchools->count();
            $playerCount = $playerLists->count();
            $dancerCount = $dancerLists->count();
            $officialCount = $officialLists->count();

            // Apply search filter
            if ($request->has('search') && !empty($request->search)) {
                $search = strtolower($request->search);
                $filteredSchools = $filteredSchools->filter(function($school) use ($search) {
                    return str_contains(strtolower($school->school_name), $search) || 
                           str_contains(strtolower($school->school_id), $search);
                });
            }

            // Apply status filter
            if ($request->has('status') && $request->status != 'all') {
                $filteredSchools = $filteredSchools->where('verification_status', $request->status);
            }

            // Sort by latest
            $filteredSchools = $filteredSchools->sortByDesc('created_at');

            // Paginate manually
            $page = $request->get('page', 1);
            $perPage = 10;
            $currentPageItems = $filteredSchools->slice(($page - 1) * $perPage, $perPage)->all();
            
            $paginatedSchools = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentPageItems,
                $filteredSchools->count(),
                $perPage,
                $page,
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return view('user.event.profile.schooldata-list', [
                'schools' => $paginatedSchools,
                'playerLists' => $playerLists,
                'dancerLists' => $dancerLists,
                'officialLists' => $officialLists,
                'totalSchools' => $totalSchools,
                'playerCount' => $playerCount,
                'dancerCount' => $dancerCount,
                'officialCount' => $officialCount,
                'authUser' => $user
            ]);

        } catch (\Exception $e) {
            Log::error('Error in SchoolDataProfileController@index: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memuat data sekolah. Silakan coba lagi.');
        }
    }

    /**
     * Get user's roles for a specific school - FIXED!
     */
    private function getUserRolesForSchool($school, $playerLists, $dancerLists, $officialLists, $user)
    {
        $roles = [
            'isPlayer' => false,
            'isDancer' => false,
            'isOfficial' => false,
            'playerRole' => null,
            'dancerRole' => null,
            'officialRole' => null,
            'playerData' => null,
            'dancerData' => null,
            'officialData' => null
        ];

        try {
            Log::info("=== Getting roles for school: {$school->school_name} ===");
            Log::info("School info:", [
                'team_id' => $school->team_id,
                'school_id' => $school->school_id,
                'school_name' => $school->school_name
            ]);

            // 1. CHECK PLAYER ROLE - FIXED: Use team_id from player_list
            $playerData = $playerLists->filter(function($player) use ($school, $user) {
                // Player_list menggunakan team_id, bukan school_id!
                $teamMatch = $player->team_id == $school->team_id;
                $userMatch = $player->email == $user->email || $player->nik == $user->nik;
                
                Log::info("Player match check:", [
                    'player_id' => $player->id,
                    'player_team_id' => $player->team_id,
                    'school_team_id' => $school->team_id,
                    'team_match' => $teamMatch,
                    'player_email' => $player->email,
                    'user_email' => $user->email,
                    'user_match' => $userMatch,
                    'player_role' => $player->role
                ]);
                
                return $teamMatch && $userMatch;
            })->first();

            if ($playerData) {
                $roles['isPlayer'] = true;
                $roles['playerRole'] = $playerData->role ? ucfirst($playerData->role) : 'Player';
                $roles['playerData'] = [
                    'id' => $playerData->id,
                    'team_id' => $playerData->team_id,
                    'role' => $playerData->role,
                    'jersey_number' => $playerData->jersey_number,
                    'basketball_position' => $playerData->basketball_position
                ];
                
                Log::info("✓ Player role FOUND: {$roles['playerRole']}");
            }

            // 2. CHECK DANCER ROLE - Use school_name
            $dancerData = $dancerLists->filter(function($dancer) use ($school, $user) {
                // Case-insensitive school name matching
                $dancerSchool = trim($dancer->school_name);
                $teamSchool = trim($school->school_name);
                $schoolMatch = strcasecmp($dancerSchool, $teamSchool) === 0;
                
                // Try partial matching if exact match fails
                if (!$schoolMatch) {
                    $schoolMatch = str_contains(strtolower($dancerSchool), strtolower($teamSchool)) ||
                                   str_contains(strtolower($teamSchool), strtolower($dancerSchool));
                }
                
                $userMatch = $dancer->email == $user->email || $dancer->nik == $user->nik;
                
                Log::info("Dancer match check:", [
                    'dancer_id' => $dancer->dancer_id,
                    'dancer_school' => $dancerSchool,
                    'team_school' => $teamSchool,
                    'school_match' => $schoolMatch,
                    'dancer_email' => $dancer->email,
                    'user_email' => $user->email,
                    'user_match' => $userMatch,
                    'dancer_role' => $dancer->role
                ]);
                
                return $schoolMatch && $userMatch;
            })->first();

            if ($dancerData) {
                $roles['isDancer'] = true;
                $roles['dancerRole'] = $dancerData->role ? ucfirst($dancerData->role) : 'Dancer';
                $roles['dancerData'] = [
                    'dancer_id' => $dancerData->dancer_id,
                    'role' => $dancerData->role
                ];
                
                Log::info("✓ Dancer role FOUND: {$roles['dancerRole']}");
            }

            // 3. CHECK OFFICIAL ROLE - Check both school_id and team_id
            $officialData = $officialLists->filter(function($official) use ($school, $user) {
                // Try matching by school_id first
                $schoolMatch = $official->school_id == $school->school_id;
                
                // If no match by school_id, try by team_id (if available)
                if (!$schoolMatch && isset($official->team_id) && $official->team_id) {
                    $schoolMatch = $official->team_id == $school->team_id;
                }
                
                $userMatch = $official->email == $user->email || $official->nik == $user->nik;
                
                Log::info("Official match check:", [
                    'official_id' => $official->official_id,
                    'official_school_id' => $official->school_id,
                    'team_school_id' => $school->school_id,
                    'official_team_id' => $official->team_id ?? 'N/A',
                    'school_team_id' => $school->team_id,
                    'school_match' => $schoolMatch,
                    'official_email' => $official->email,
                    'user_email' => $user->email,
                    'user_match' => $userMatch,
                    'official_team_role' => $official->team_role,
                    'official_role' => $official->role
                ]);
                
                return $schoolMatch && $userMatch;
            })->first();

            if ($officialData) {
                $roles['isOfficial'] = true;
                // Check both fields for role
                $role = $officialData->team_role ?? $officialData->role;
                $roles['officialRole'] = $role ? ucfirst($role) : 'Official';
                $roles['officialData'] = [
                    'official_id' => $officialData->official_id,
                    'team_role' => $officialData->team_role,
                    'role' => $officialData->role,
                    'team_id' => $officialData->team_id ?? null
                ];
                
                Log::info("✓ Official role FOUND: {$roles['officialRole']}");
            }

            Log::info("Final roles for {$school->school_name}:", [
                'isPlayer' => $roles['isPlayer'],
                'playerRole' => $roles['playerRole'],
                'isDancer' => $roles['isDancer'],
                'dancerRole' => $roles['dancerRole'],
                'isOfficial' => $roles['isOfficial'],
                'officialRole' => $roles['officialRole']
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getUserRolesForSchool: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
        }

        return $roles;
    }

    /**
     * Get all schools for a user based on their registrations - FIXED!
     */
    private function getAllSchoolsForUser($user, $playerLists = null, $dancerLists = null, $officialLists = null)
    {
        try {
            if (!$playerLists) {
                $playerLists = PlayerList::where(function($query) use ($user) {
                    $query->where('email', $user->email)
                          ->orWhere('nik', $user->nik);
                })->get();
            }
            
            if (!$dancerLists) {
                $dancerLists = DancerList::where(function($query) use ($user) {
                    $query->where('email', $user->email)
                          ->orWhere('nik', $user->nik);
                })->get();
            }
            
            if (!$officialLists) {
                $officialLists = OfficialList::where(function($query) use ($user) {
                    $query->where('email', $user->email)
                          ->orWhere('nik', $user->nik);
                })->get();
            }

            Log::info('=== Getting all schools for user ===');
            Log::info('Player team_ids:', $playerLists->pluck('team_id')->toArray());
            Log::info('Dancer school_names:', $dancerLists->pluck('school_name')->toArray());
            Log::info('Official school_ids:', $officialLists->pluck('school_id')->toArray());

            $allSchools = collect();

            // 1. Get schools by team_id (from player_list)
            $teamIdsFromPlayers = $playerLists->pluck('team_id')->filter()->unique();
            if ($teamIdsFromPlayers->isNotEmpty()) {
                Log::info('Querying schools by team_id:', $teamIdsFromPlayers->toArray());
                $schoolsByTeamId = TeamList::whereIn('team_id', $teamIdsFromPlayers)->get();
                Log::info('Found schools by team_id:', [
                    'count' => $schoolsByTeamId->count(),
                    'schools' => $schoolsByTeamId->pluck('school_name')->toArray()
                ]);
                $allSchools = $allSchools->merge($schoolsByTeamId);
            }

            // 2. Get schools by school_id (from official_list)
            $schoolIdsFromOfficials = $officialLists->pluck('school_id')->filter()->unique();
            if ($schoolIdsFromOfficials->isNotEmpty()) {
                Log::info('Querying schools by school_id:', $schoolIdsFromOfficials->toArray());
                $schoolsBySchoolId = TeamList::whereIn('school_id', $schoolIdsFromOfficials)->get();
                Log::info('Found schools by school_id:', [
                    'count' => $schoolsBySchoolId->count(),
                    'schools' => $schoolsBySchoolId->pluck('school_name')->toArray()
                ]);
                $allSchools = $allSchools->merge($schoolsBySchoolId);
            }

            // 3. Get schools by school_name (from dancer_list)
            $schoolNamesFromDancers = $dancerLists->pluck('school_name')->filter()->unique();
            if ($schoolNamesFromDancers->isNotEmpty()) {
                Log::info('Querying schools by school_name:', $schoolNamesFromDancers->toArray());
                
                // First try exact matches
                $schoolsByName = TeamList::whereIn('school_name', $schoolNamesFromDancers)->get();
                
                // If no exact matches, try case-insensitive partial matches
                if ($schoolsByName->isEmpty()) {
                    foreach ($schoolNamesFromDancers as $schoolName) {
                        $matchingSchools = TeamList::whereRaw('LOWER(school_name) LIKE ?', 
                            ['%' . strtolower($schoolName) . '%'])->get();
                        $schoolsByName = $schoolsByName->merge($matchingSchools);
                    }
                }
                
                Log::info('Found schools by school_name:', [
                    'count' => $schoolsByName->count(),
                    'schools' => $schoolsByName->pluck('school_name')->toArray()
                ]);
                $allSchools = $allSchools->merge($schoolsByName);
            }

            // Remove duplicates by team_id
            $allSchools = $allSchools->unique('team_id');

            Log::info('=== Final unique schools ===', [
                'total_count' => $allSchools->count(),
                'schools' => $allSchools->map(function($school) {
                    return [
                        'team_id' => $school->team_id,
                        'school_id' => $school->school_id,
                        'school_name' => $school->school_name
                    ];
                })->toArray()
            ]);

            return $allSchools;

        } catch (\Exception $e) {
            Log::error('Error in getAllSchoolsForUser: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            return collect();
        }
    }

    /**
     * Get school logo URL
     */
    private function getSchoolLogoUrl($schoolLogo)
    {
        if (!$schoolLogo) {
            return null;
        }

        $logoFile = basename($schoolLogo);
        
        // If already a full URL
        if (strpos($schoolLogo, 'http') === 0) {
            return $schoolLogo;
        }

        // Try storage first
        if (Storage::disk('public')->exists('school_logos/' . $logoFile)) {
            return Storage::disk('public')->url('school_logos/' . $logoFile);
        }

        // Try public storage
        $publicPath = public_path('storage/school_logos/' . $logoFile);
        if (file_exists($publicPath)) {
            return asset('storage/school_logos/' . $logoFile);
        }

        // Try the original path
        if (file_exists(public_path($schoolLogo))) {
            return asset($schoolLogo);
        }

        // Try with just the filename
        $simplePath = public_path('school_logos/' . $logoFile);
        if (file_exists($simplePath)) {
            return asset('school_logos/' . $logoFile);
        }

        return null;
    }

    /**
     * Show the form for editing school data.
     */
    public function edit($school_id = null)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login first.');
            }

            // Jika ada school_id, cari data sekolah tersebut
            $team = null;
            if ($school_id) {
                $team = TeamList::where('school_id', $school_id)->first();
                
                if (!$team) {
                    return redirect()->route('schooldata.list')->with('error', 'School not found.');
                }
                
                // Verifikasi bahwa user memiliki akses ke sekolah ini
                if (!$this->hasAccessToSchool($user, $school_id, $team->school_name)) {
                    return redirect()->route('schooldata.list')->with('error', 'You do not have access to this school.');
                }
            }

            return view('user.event.profile.schooldata-edit', compact('team', 'school_id'));

        } catch (\Exception $e) {
            Log::error('Error in SchoolDataProfileController@edit: ' . $e->getMessage());
            return redirect()->route('schooldata.list')->with('error', 'Terjadi kesalahan saat memuat form edit.');
        }
    }

    /**
     * Update school data.
     */
    public function update(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login first.');
            }

            $validated = $request->validate([
                'school_id' => 'required|string|max:50',
                'school_name' => 'required|string|max:255',
                'competition' => 'required|string|in:basketball,cheerleader',
                'team_category' => 'required|string|in:putra,putri',
                'season' => 'required|integer',
                'series' => 'required|integer',
                'referral_code' => 'nullable|string|max:50',
                'school_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Cek apakah user memiliki akses ke sekolah ini
            if (!$this->hasAccessToSchool($user, $validated['school_id'], $validated['school_name'])) {
                return redirect()->route('schooldata.list')->with('error', 'You do not have access to this school.');
            }

            // Cari atau buat data team
            $team = TeamList::where('school_id', $validated['school_id'])->first();
            
            if (!$team) {
                // Buat baru jika tidak ditemukan
                $team = new TeamList();
                $team->school_id = $validated['school_id'];
                $team->registered_by = $user->id;
                $team->verification_status = 'pending';
                $team->payment_status = 'unpaid';
                $team->locked_status = 'unlocked';
            }

            // Update data
            $team->school_name = $validated['school_name'];
            $team->competition = $validated['competition'];
            $team->team_category = $validated['team_category'];
            $team->season = $validated['season'];
            $team->series = $validated['series'];
            $team->referral_code = $validated['referral_code'] ?? null;

            // Handle upload logo
            if ($request->hasFile('school_logo')) {
                $logoFile = $request->file('school_logo');
                $logoName = time() . '_' . str_slug($validated['school_name']) . '_logo.' . $logoFile->getClientOriginalExtension();
                $logoPath = $logoFile->storeAs('school_logos', $logoName, 'public');
                $team->school_logo = $logoName;
            }

            $team->save();

            return redirect()->route('schooldata.list')->with('success', 'School data updated successfully.');

        } catch (\Exception $e) {
            Log::error('Error in SchoolDataProfileController@update: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan data. Error: ' . $e->getMessage());
        }
    }

    /**
     * Leave a school team.
     */
    public function leave(Request $request, $school_id)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login first.');
            }

            // Cari data sekolah
            $team = TeamList::where('school_id', $school_id)->first();
            
            if (!$team) {
                return redirect()->route('schooldata.list')->with('error', 'School not found.');
            }

            // Verifikasi bahwa user memiliki akses ke sekolah ini
            if (!$this->hasAccessToSchool($user, $school_id, $team->school_name)) {
                return redirect()->route('schooldata.list')->with('error', 'You do not have access to this school.');
            }

            // Hapus user dari semua roles di sekolah ini
            $this->removeUserFromSchool($user, $team);

            return redirect()->route('schooldata.list')->with('success', 'Successfully left the school team.');

        } catch (\Exception $e) {
            Log::error('Error in SchoolDataProfileController@leave: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat meninggalkan sekolah. Silakan coba lagi.');
        }
    }

    /**
     * Check if user has access to a school - FIXED!
     */
    private function hasAccessToSchool($user, $school_id, $school_name)
    {
        try {
            // Cari team berdasarkan school_id
            $team = TeamList::where('school_id', $school_id)->first();
            
            if (!$team) {
                Log::warning("Team not found for school_id: {$school_id}");
                return false;
            }

            // Cek di player_list dengan team_id
            $isPlayer = PlayerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->where('team_id', $team->team_id)->exists();

            // Cek di dancer_list dengan school_name (case-insensitive)
            $isDancer = DancerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->whereRaw('LOWER(school_name) = ?', [strtolower($school_name)])->exists();

            // Cek di official_list dengan school_id
            $isOfficial = OfficialList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->where('school_id', $school_id)->exists();

            // Juga cek official dengan team_id jika ada
            if (!$isOfficial && $team->team_id) {
                $isOfficial = OfficialList::where(function($query) use ($user) {
                    $query->where('email', $user->email)
                          ->orWhere('nik', $user->nik);
                })->where('team_id', $team->team_id)->exists();
            }

            Log::info("Access check for school {$school_name} (ID: {$school_id}, Team ID: {$team->team_id}):", [
                'isPlayer' => $isPlayer,
                'isDancer' => $isDancer,
                'isOfficial' => $isOfficial
            ]);

            return $isPlayer || $isDancer || $isOfficial;

        } catch (\Exception $e) {
            Log::error('Error in hasAccessToSchool: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove user from all roles in a school - FIXED!
     */
    private function removeUserFromSchool($user, $team)
    {
        try {
            DB::beginTransaction();

            // Hapus dari player_list dengan team_id
            $playerDeleted = PlayerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->where('team_id', $team->team_id)->delete();

            Log::info("Deleted {$playerDeleted} player records for team_id {$team->team_id}");

            // Hapus dari dancer_list dengan school_name (case-insensitive)
            $dancerDeleted = DancerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->whereRaw('LOWER(school_name) = ?', [strtolower($team->school_name)])->delete();

            Log::info("Deleted {$dancerDeleted} dancer records for school {$team->school_name}");

            // Hapus dari official_list dengan school_id
            $officialDeleted = OfficialList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->where('school_id', $team->school_id)->delete();

            // Juga hapus dengan team_id jika ada
            if ($team->team_id) {
                $officialDeleted += OfficialList::where(function($query) use ($user) {
                    $query->where('email', $user->email)
                          ->orWhere('nik', $user->nik);
                })->where('team_id', $team->team_id)->delete();
            }

            Log::info("Deleted {$officialDeleted} official records for school {$team->school_id}");

            // Cek jika sekolah menjadi kosong setelah user keluar
            $this->checkIfSchoolEmpty($team);

            DB::commit();

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in removeUserFromSchool: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check if school becomes empty after user leaves.
     */
    private function checkIfSchoolEmpty($team)
    {
        try {
            // Cek apakah masih ada anggota di sekolah ini
            $hasPlayers = PlayerList::where('team_id', $team->team_id)->exists();
            $hasDancers = DancerList::whereRaw('LOWER(school_name) = ?', [strtolower($team->school_name)])->exists();
            $hasOfficials = OfficialList::where('school_id', $team->school_id)
                ->orWhere('team_id', $team->team_id)->exists();

            // Jika tidak ada anggota sama sekali
            if (!$hasPlayers && !$hasDancers && !$hasOfficials) {
                Log::info("School {$team->school_name} (ID: {$team->school_id}) is now empty");
                // Optional: Delete team_list entry if desired
                // TeamList::where('school_id', $team->school_id)->delete();
            }

        } catch (\Exception $e) {
            Log::error('Error in checkIfSchoolEmpty: ' . $e->getMessage());
        }
    }

    /**
     * Get school statistics for API.
     */
    public function getStatistics()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $playerLists = PlayerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->get();

            $dancerLists = DancerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->get();

            $officialLists = OfficialList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->get();

            $allSchools = $this->getAllSchoolsForUser($user, $playerLists, $dancerLists, $officialLists);
            $totalSchools = $allSchools->count();

            return response()->json([
                'total_schools' => $totalSchools,
                'player_count' => $playerLists->count(),
                'dancer_count' => $dancerLists->count(),
                'official_count' => $officialLists->count(),
                'status' => 'success'
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getStatistics: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Debug function to check user's data in all tables
     */
    public function debugUserData()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $playerData = PlayerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->get();

            $dancerData = DancerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->get();

            $officialData = OfficialList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->get();

            // Get team data for each player
            $teamsForPlayer = [];
            foreach ($playerData as $player) {
                if ($player->team_id) {
                    $team = TeamList::where('team_id', $player->team_id)->first();
                    $teamsForPlayer[$player->id] = $team ? [
                        'team_id' => $team->team_id,
                        'school_id' => $team->school_id,
                        'school_name' => $team->school_name
                    ] : null;
                }
            }

            // Get team data for each dancer
            $teamsForDancer = [];
            foreach ($dancerData as $dancer) {
                if ($dancer->school_name) {
                    $team = TeamList::where('school_name', 'like', "%{$dancer->school_name}%")->first();
                    $teamsForDancer[$dancer->dancer_id] = $team ? [
                        'team_id' => $team->team_id,
                        'school_id' => $team->school_id,
                        'school_name' => $team->school_name
                    ] : null;
                }
            }

            // Get team data for each official
            $teamsForOfficial = [];
            foreach ($officialData as $official) {
                $team = null;
                if ($official->school_id) {
                    $team = TeamList::where('school_id', $official->school_id)->first();
                }
                if (!$team && $official->team_id) {
                    $team = TeamList::where('team_id', $official->team_id)->first();
                }
                $teamsForOfficial[$official->official_id] = $team ? [
                    'team_id' => $team->team_id,
                    'school_id' => $team->school_id,
                    'school_name' => $team->school_name
                ] : null;
            }

            return response()->json([
                'user' => [
                    'email' => $user->email,
                    'nik' => $user->nik,
                    'name' => $user->name
                ],
                'player_data' => $playerData->map(function($item) use ($teamsForPlayer) {
                    return [
                        'id' => $item->id,
                        'team_id' => $item->team_id,
                        'school_id' => $item->school_id,
                        'school_info' => $teamsForPlayer[$item->id] ?? 'No team found',
                        'role' => $item->role,
                        'email' => $item->email,
                        'nik' => $item->nik,
                        'name' => $item->name,
                        'jersey_number' => $item->jersey_number,
                        'position' => $item->basketball_position
                    ];
                }),
                'dancer_data' => $dancerData->map(function($item) use ($teamsForDancer) {
                    return [
                        'dancer_id' => $item->dancer_id,
                        'school_name_in_dancer' => $item->school_name,
                        'school_info' => $teamsForDancer[$item->dancer_id] ?? 'No team found',
                        'role' => $item->role,
                        'email' => $item->email,
                        'nik' => $item->nik,
                        'name' => $item->name
                    ];
                }),
                'official_data' => $officialData->map(function($item) use ($teamsForOfficial) {
                    return [
                        'official_id' => $item->official_id,
                        'school_id' => $item->school_id,
                        'team_id' => $item->team_id,
                        'school_info' => $teamsForOfficial[$item->official_id] ?? 'No team found',
                        'team_role' => $item->team_role,
                        'role' => $item->role,
                        'email' => $item->email,
                        'nik' => $item->nik,
                        'name' => $item->name
                    ];
                }),
                'player_count' => $playerData->count(),
                'dancer_count' => $dancerData->count(),
                'official_count' => $officialData->count()
            ]);

        } catch (\Exception $e) {
            Log::error('Error in debugUserData: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
    
    /**
     * Direct test function to check roles for a specific school
     */
    public function testRolesForSchool($school_id)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            $school = TeamList::where('school_id', $school_id)->first();
            
            if (!$school) {
                return response()->json(['error' => 'School not found'], 404);
            }

            $playerLists = PlayerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->get();

            $dancerLists = DancerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->get();

            $officialLists = OfficialList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->get();

            $roles = $this->getUserRolesForSchool($school, $playerLists, $dancerLists, $officialLists, $user);

            return response()->json([
                'school' => [
                    'team_id' => $school->team_id,
                    'school_id' => $school->school_id,
                    'name' => $school->school_name
                ],
                'roles' => $roles,
                'player_data' => $playerLists->where('team_id', $school->team_id)->values(),
                'dancer_data' => $dancerLists->filter(function($dancer) use ($school) {
                    return strcasecmp(trim($dancer->school_name), trim($school->school_name)) === 0;
                })->values(),
                'official_data' => $officialLists->filter(function($official) use ($school) {
                    return $official->school_id == $school->school_id || 
                           (isset($official->team_id) && $official->team_id == $school->team_id);
                })->values()
            ]);

        } catch (\Exception $e) {
            Log::error('Error in testRolesForSchool: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Check database structure
     */
    public function checkDatabaseStructure()
    {
        try {
            // Check if team_id exists in player_list
            $playerColumns = \Schema::getColumnListing('player_list');
            $dancerColumns = \Schema::getColumnListing('dancer_list');
            $officialColumns = \Schema::getColumnListing('official_list');
            $teamColumns = \Schema::getColumnListing('team_list');

            // Sample data
            $samplePlayer = PlayerList::first();
            $sampleDancer = DancerList::first();
            $sampleOfficial = OfficialList::first();
            $sampleTeam = TeamList::first();

            return response()->json([
                'player_list_columns' => $playerColumns,
                'dancer_list_columns' => $dancerColumns,
                'official_list_columns' => $officialColumns,
                'team_list_columns' => $teamColumns,
                'sample_player' => $samplePlayer ? [
                    'team_id' => $samplePlayer->team_id,
                    'school_id' => $samplePlayer->school_id,
                    'role' => $samplePlayer->role
                ] : null,
                'sample_dancer' => $sampleDancer ? [
                    'school_name' => $sampleDancer->school_name,
                    'role' => $sampleDancer->role
                ] : null,
                'sample_official' => $sampleOfficial ? [
                    'school_id' => $sampleOfficial->school_id,
                    'team_id' => $sampleOfficial->team_id ?? 'N/A',
                    'team_role' => $sampleOfficial->team_role,
                    'role' => $sampleOfficial->role
                ] : null,
                'sample_team' => $sampleTeam ? [
                    'team_id' => $sampleTeam->team_id,
                    'school_id' => $sampleTeam->school_id,
                    'school_name' => $sampleTeam->school_name
                ] : null
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}