<?php

namespace App\Http\Controllers\TeamVerification;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TeamList;
use App\Models\PlayerList;
use App\Models\DancerList;
use App\Models\OfficialList;
use App\Models\School;
use App\Exports\TeamsExport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

class TeamController extends Controller
{
    public function teamList(Request $request)
    {
        $query = TeamList::query();

        // Filter by school
        if ($request->filled('school')) {
            $query->where('school_name', $request->school);
        }

        // Filter by status - HANYA unverified/verified
        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('team_category', $request->category);
        }

        // Filter by competition
        if ($request->filled('competition')) {
            $query->where('competition', 'like', '%' . $request->competition . '%');
        }

        // Filter by tahun (dari season)
        if ($request->filled('year')) {
            $query->where('season', 'like', '%' . $request->year . '%');
        }

        // Filter by locked status
        if ($request->filled('locked')) {
            $query->where('locked_status', $request->locked);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('school_name', 'like', '%' . $search . '%')
                    ->orWhere('team_name', 'like', '%' . $search . '%')
                    ->orWhere('referral_code', 'like', '%' . $search . '%')
                    ->orWhere('competition', 'like', '%' . $search . '%')
                    ->orWhere('series', 'like', '%' . $search . '%')
                    ->orWhere('registered_by', 'like', '%' . $search . '%');
            });
        }

        // Sort - DEFAULT: updated_at descending (data terbaru di atas)
        $sort = $request->get('sort', 'updated_at');
        $order = $request->get('order', 'desc');

        // Validasi kolom sort untuk menghindari SQL injection
        $allowedSortColumns = ['updated_at', 'created_at', 'school_name', 'team_category', 'verification_status', 'locked_status'];
        $sort = in_array($sort, $allowedSortColumns) ? $sort : 'updated_at';

        $query->orderBy($sort, $order);

        // Get available years for filter
        $years = TeamList::selectRaw('DISTINCT season')
            ->whereNotNull('season')
            ->orderBy('season', 'desc')
            ->pluck('season')
            ->unique()
            ->values();

        // Get unique values for filters
        $schools = TeamList::distinct('school_name')->orderBy('school_name')->pluck('school_name');
        $competitions = TeamList::distinct('competition')->whereNotNull('competition')->orderBy('competition')->pluck('competition');

        // Pagination - 50 per page untuk data lebih banyak dalam satu halaman
        $teamList = $query->paginate(50)->withQueryString();

        return view('team_verification.tv_team_list', compact('teamList', 'schools', 'competitions', 'years'));
    }

    public function export(Request $request)
    {
        // Apply same filters as teamList
        $query = $this->applyFilters($request);

        // Get teams with filters applied
        $teams = $query->get();

        // Generate filename with timestamp
        $filename = 'teams_export_' . date('Y-m-d_H-i') . '.xlsx';

        // Export to Excel
        return Excel::download(new TeamsExport($teams), $filename);
    }

    private function applyFilters(Request $request)
    {
        $query = TeamList::query();

        if ($request->filled('school')) {
            $query->where('school_name', $request->school);
        }

        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        if ($request->filled('category')) {
            $query->where('team_category', $request->category);
        }

        if ($request->filled('competition')) {
            $query->where('competition', 'like', '%' . $request->competition . '%');
        }

        if ($request->filled('year')) {
            $query->where('season', 'like', '%' . $request->year . '%');
        }

        if ($request->filled('locked')) {
            $query->where('locked_status', $request->locked);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('school_name', 'like', '%' . $search . '%')
                    ->orWhere('team_name', 'like', '%' . $search . '%')
                    ->orWhere('referral_code', 'like', '%' . $search . '%')
                    ->orWhere('competition', 'like', '%' . $search . '%')
                    ->orWhere('registered_by', 'like', '%' . $search . '%');
            });
        }

        return $query->orderBy('updated_at', 'desc');
    }

    /**
     * Tampilkan halaman utama dengan semua kategori (SISTEM TAB BARU)
     */
    public function teamShow($id)
    {
        $mainTeam = $this->getMainTeam($id);
        
        // Ambil semua data tim untuk sekolah ini
        $teamData = $this->getAllTeamData($mainTeam->school_name);
        
        // Pastikan activeTab dikirim dengan benar
        $activeTab = request()->get('tab', $this->getDefaultActiveTab($teamData));
        
        // Log untuk debugging
        Log::info('Team Show - Active Tab:', [
            'request_tab' => request()->get('tab'),
            'activeTab' => $activeTab,
            'default_tab' => $this->getDefaultActiveTab($teamData)
        ]);
        
        // Get categories
        $categories = ['Basket Putra', 'Basket Putri', 'Dancer'];
        
        // **PERBAIKAN UTAMA: SELALU gunakan view utama tv_team_detail**
        return view('team_verification.tv_team_detail', compact('mainTeam', 'teamData', 'activeTab', 'categories'));
    }

    /**
     * Helper method untuk mendapatkan tim utama berdasarkan ID
     */
    private function getMainTeam($id)
    {
        $mainTeam = TeamList::where('team_id', $id)->first();

        if (!$mainTeam) {
            abort(404, 'Team tidak ditemukan');
        }

        Log::info('Main Team Found:', [
            'id' => $mainTeam->team_id,
            'school' => $mainTeam->school_name,
            'category' => $mainTeam->team_category,
            'referral' => $mainTeam->referral_code
        ]);

        return $mainTeam;
    }

    /**
     * Helper method untuk memastikan school_id konsisten di semua tabel
     */
    private function ensureConsistentSchoolId($schoolName)
    {
        // Cari school dari tabel schools
        $school = School::where('school_name', $schoolName)->first();
        
        if (!$school) {
            // Buat sekolah baru jika tidak ada
            $school = School::create([
                'school_name' => $schoolName,
                'category_name' => 'SMA',
                'type' => 'SWASTA',
                'city_id' => 1,
            ]);
            Log::info('Created new school: ' . $school->id . ' - ' . $schoolName);
        }
        
        // Update semua tim dengan school_id yang benar
        $updatedTeams = TeamList::where('school_name', $schoolName)
            ->where(function($query) use ($school) {
                $query->whereNull('school_id')
                    ->orWhere('school_id', '!=', $school->id);
            })
            ->update(['school_id' => $school->id]);
        
        if ($updatedTeams > 0) {
            Log::info('Updated ' . $updatedTeams . ' teams with school_id: ' . $school->id);
        }
        
        return $school->id;
    }

    /**
     * Helper method untuk mendapatkan semua data tim berdasarkan nama sekolah (PERBAIKAN UTAMA)
     */
    private function getAllTeamData($schoolName)
    {
        $categories = ['Basket Putra', 'Basket Putri', 'Dancer'];
        $teamData = [];
        
        // 🔥 Pastikan school_id konsisten sebelum mengambil data
        $schoolId = $this->ensureConsistentSchoolId($schoolName);
        
        // Get all teams for this school
        $schoolTeams = TeamList::where('school_name', $schoolName)->get();
        
        Log::info('=== ALL TEAM DATA START ===', [
            'school' => $schoolName,
            'school_id' => $schoolId,
            'total_teams_found' => $schoolTeams->count(),
        ]);
        
        foreach ($categories as $category) {
            $teamForCategory = $schoolTeams
                ->where('team_category', $category)
                ->first();
            
            if ($teamForCategory) {
                $teamId = $teamForCategory->team_id;
                
                Log::info("Processing category: {$category}", [
                    'team_id' => $teamId,
                    'team_category' => $teamForCategory->team_category
                ]);
                
                // Get players based on category AND school_id
                if ($category == 'Dancer') {
                    // Untuk Dancer - ambil semua dancer dengan school_name yang sama
                    $players = DancerList::where('school_name', $schoolName)
                        ->orderBy('role', 'desc')
                        ->orderBy('name', 'asc')
                        ->get();
                        
                    Log::info("Dancer players found for {$schoolName}: " . $players->count());
                } else {
                    $playerCategory = ($category == 'Basket Putra') ? 'putra' : 'putri';
                    
                    // 🔥 PERBAIKAN UTAMA: Ambil SEMUA player dengan school_id yang sama dan category yang sesuai
                    $players = PlayerList::where('school_id', $schoolId)
                        ->where('category', $playerCategory)
                        ->orderByRaw('CASE WHEN jersey_number REGEXP "^[0-9]+$" THEN CAST(jersey_number AS UNSIGNED) ELSE 999 END ASC')
                        ->orderBy('name', 'asc')
                        ->get();
                        
                    Log::info("Players found by school_id {$schoolId} for category {$playerCategory}: " . $players->count());
                }
                
                // 🔥 PERBAIKAN UTAMA: Ambil official dengan cara yang benar
                $officialCategory = $this->mapCategoryToOfficialCategory($category);
                
                // Ambil official berdasarkan category dan team_id
                $officials = OfficialList::where('team_id', $teamId)
                    ->where('category', $officialCategory)
                    ->orderBy('team_role', 'asc')
                    ->get();
                
                Log::info("Officials found for team_id {$teamId}, category {$officialCategory}: " . $officials->count());
                
                // Jika tidak ada official, coba ambil berdasarkan sekolah (fallback)
                if ($officials->count() == 0) {
                    Log::warning("No officials found for team_id {$teamId}, trying fallback...");
                    
                    // Fallback: cari official yang memiliki team dengan sekolah yang sama
                    $fallbackOfficials = OfficialList::whereHas('team', function($query) use ($schoolName) {
                            $query->where('school_name', $schoolName);
                        })
                        ->where('category', $officialCategory)
                        ->orderBy('team_role', 'asc')
                        ->get();
                    
                    if ($fallbackOfficials->count() > 0) {
                        $officials = $fallbackOfficials;
                        Log::info("Fallback found {$officials->count()} officials for school {$schoolName}");
                    }
                }
                
                $teamData[$category] = [
                    'team' => $teamForCategory,
                    'players' => $players,
                    'officials' => $officials,
                    'exists' => true
                ];
                
                Log::info("✅ Team data prepared for {$category}:", [
                    'team_id' => $teamForCategory->team_id,
                    'players_count' => $players->count(),
                    'officials_count' => $officials->count()
                ]);
                
            } else {
                $teamData[$category] = [
                    'team' => null,
                    'players' => collect(),
                    'officials' => collect(),
                    'exists' => false
                ];
                
                Log::info("❌ No team found for category: {$category}");
            }
        }
        
        Log::info('=== ALL TEAM DATA END ===');
        return $teamData;
    }

    /**
     * Helper method untuk mendapatkan default active tab
     */
    private function getDefaultActiveTab($teamData)
    {
        // Coba aktifkan tab pertama yang memiliki data
        foreach (['Basket Putra', 'Basket Putri', 'Dancer'] as $category) {
            if (isset($teamData[$category]['exists']) && $teamData[$category]['exists']) {
                return $category;
            }
        }
        
        // Jika tidak ada data, return kategori pertama
        return 'Basket Putra';
    }

    /**
     * Helper method untuk mendapatkan tim berdasarkan kategori
     */
    private function getTeamByCategory($teamId, $category)
    {
        $team = TeamList::where('team_id', $teamId)
            ->where('team_category', $category)
            ->firstOrFail();
        
        Log::info("Team {$category} found:", [
            'team_id' => $team->team_id,
            'school' => $team->school_name,
            'category' => $team->team_category
        ]);
        
        return $team;
    }

    /**
     * Helper method untuk mendapatkan tim utama dari tim kategori tertentu
     */
    private function getMainTeamFromTeam($team)
    {
        // Cari tim utama berdasarkan sekolah (yang pertama dibuat)
        $mainTeam = TeamList::where('school_name', $team->school_name)
            ->orderBy('team_id', 'asc')
            ->first();
        
        if (!$mainTeam) {
            return $team; // Fallback to the team itself
        }
        
        return $mainTeam;
    }

    /**
     * Map team category to official category
     */
    private function mapCategoryToOfficialCategory($teamCategory)
    {
        $mapping = [
            'Basket Putra' => 'basket_putra',
            'Basket Putri' => 'basket_putri',
            'Dancer' => 'dancer'
        ];

        return $mapping[$teamCategory] ?? 'lainnya';
    }

    /**
     * Halaman Detail Basket Putra dengan sistem tab
     */
    public function teamDetailBasketPutra($teamId)
    {
        // Redirect ke sistem tab dengan parameter tab
        return redirect()->route('admin.team-list.show', [
            'id' => $teamId,
            'tab' => 'Basket Putra'
        ]);
    }

    /**
     * Halaman Detail Basket Putri dengan sistem tab
     */
    public function teamDetailBasketPutri($teamId)
    {
        // Redirect ke sistem tab dengan parameter tab
        return redirect()->route('admin.team-list.show', [
            'id' => $teamId,
            'tab' => 'Basket Putri'
        ]);
    }

    /**
     * Halaman Detail Dancer dengan sistem tab
     */
    public function teamDetailDancer($teamId)
    {
        // Redirect ke sistem tab dengan parameter tab
        return redirect()->route('admin.team-list.show', [
            'id' => $teamId,
            'tab' => 'Dancer'
        ]);
    }

    public function teamVerification()
    {
        $unverifiedTeams = TeamList::where('verification_status', 'unverified')
            ->orderBy('updated_at', 'desc')
            ->paginate(50);
        return view('team_verification.tv_team_verification', compact('unverifiedTeams'));
    }

    public function teamAwards()
    {
        return view('team_verification.tv_team_awards');
    }

    public function lock($id)
    {
        $team = TeamList::where('team_id', $id)->firstOrFail();
        $team->locked_status = 'locked';
        $team->save();

        return back()->with('success', 'Tim berhasil dikunci!');
    }

    public function unlock($id)
    {
        $team = TeamList::where('team_id', $id)->firstOrFail();
        $team->locked_status = 'unlocked';
        $team->save();

        return back()->with('success', 'Tim berhasil dibuka!');
    }

    public function verify($id)
    {
        $team = TeamList::where('team_id', $id)->firstOrFail();
        $team->verification_status = 'verified';
        $team->save();

        return back()->with('success', 'Tim berhasil diverifikasi!');
    }

    public function unverify($id)
    {
        $team = TeamList::where('team_id', $id)->firstOrFail();
        $team->verification_status = 'unverified';
        $team->save();

        return back()->with('success', 'Verifikasi tim berhasil dibatalkan!');
    }

    public function playerDetail($id)
    {
        $player = PlayerList::with('team')
            ->where('id', $id)
            ->first();

        if (!$player) {
            abort(404, 'Pemain tidak ditemukan');
        }

        $schoolName = null;
        if ($player->team) {
            $schoolName = $player->team->school_name;
        }

        return view('team_verification.tv_player_detail', compact('player', 'schoolName'));
    }

    public function dancerDetail($id)
    {
        $dancer = DancerList::with('team')
            ->where('dancer_id', $id)
            ->first();

        if (!$dancer) {
            abort(404, 'Dancer tidak ditemukan');
        }

        $schoolName = null;
        if ($dancer->team) {
            $schoolName = $dancer->team->school_name;
        }

        return view('team_verification.tv_dancer_detail', compact('dancer', 'schoolName'));
    }

    public function verifyDancer($id)
    {
        $dancer = DancerList::where('dancer_id', $id)->firstOrFail();
        $dancer->verification_status = 'verified';
        $dancer->save();

        return back()->with('success', 'Dancer berhasil diverifikasi!');
    }

    public function unverifyDancer($id)
    {
        $dancer = DancerList::where('dancer_id', $id)->firstOrFail();
        $dancer->verification_status = 'unverified';
        $dancer->save();

        return back()->with('success', 'Verifikasi dancer berhasil dibatalkan!');
    }

    public function rejectDancer($id)
    {
        $dancer = DancerList::where('dancer_id', $id)->firstOrFail();
        $dancer->verification_status = 'rejected';
        $dancer->save();

        return back()->with('success', 'Dancer berhasil ditolak!');
    }

    public function officialDetail($id)
    {
        $official = OfficialList::with('team')
            ->where('official_id', $id)
            ->first();

        if (!$official) {
            abort(404, 'Official tidak ditemukan');
        }

        $schoolName = null;
        if ($official->team) {
            $schoolName = $official->team->school_name;
        }

        return view('team_verification.tv_official_detail', compact('official', 'schoolName'));
    }

    public function checkLogoPath()
    {
        // Debug path logo
        $teams = TeamList::whereNotNull('school_logo')->limit(5)->get();

        $results = [];
        foreach ($teams as $team) {
            $path = public_path('uploads/school_logo/' . $team->school_logo);
            $results[] = [
                'team_id' => $team->team_id,
                'school_name' => $team->school_name,
                'school_logo' => $team->school_logo,
                'exists' => file_exists($path),
                'path' => $path
            ];
        }

        return response()->json($results);
    }

    /**
     * FIX: Method untuk memperbaiki data school_id yang tidak konsisten
     */
    public function fixSchoolData($schoolName)
    {
        try {
            Log::info('=== FIX SCHOOL DATA START ===');
            Log::info('School Name: ' . $schoolName);
            
            // 1. Cari atau buat sekolah
            $school = School::where('school_name', $schoolName)->first();
            if (!$school) {
                $school = School::create([
                    'school_name' => $schoolName,
                    'category_name' => 'SMA',
                    'type' => 'SWASTA',
                    'city_id' => 1,
                ]);
                Log::info('Created new school: ID=' . $school->id);
            }
            
            // 2. Update semua tim dengan school_id yang benar
            $teamsUpdated = TeamList::where('school_name', $schoolName)
                ->update(['school_id' => $school->id]);
            Log::info('Updated ' . $teamsUpdated . ' teams');
            
            // 3. Update semua player dengan school_id yang benar
            // Pertama, cari semua team_id untuk sekolah ini
            $teamIds = TeamList::where('school_name', $schoolName)->pluck('team_id');
            
            $playersUpdated = PlayerList::whereIn('team_id', $teamIds)
                ->update(['school_id' => $school->id]);
            Log::info('Updated ' . $playersUpdated . ' players');
            
            // 4. Update semua dancer dengan school_name yang benar
            $dancersUpdated = DancerList::where('school_name', $schoolName)
                ->update(['school_id' => $school->id]);
            Log::info('Updated ' . $dancersUpdated . ' dancers');
            
            Log::info('=== FIX SCHOOL DATA END ===');
            
            return response()->json([
                'success' => true,
                'message' => 'Data sekolah berhasil diperbaiki',
                'data' => [
                    'school_id' => $school->id,
                    'teams_updated' => $teamsUpdated,
                    'players_updated' => $playersUpdated,
                    'dancers_updated' => $dancersUpdated
                ]
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error fixing school data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}