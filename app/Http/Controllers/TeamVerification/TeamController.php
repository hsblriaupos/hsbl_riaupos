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
    /**
     * Display team list with filters
     */
    /**
     * 🔥🔥🔥 FIX: Tampilkan 1 baris per sekolah dengan informasi yang benar
     */
    public function teamList(Request $request)
    {
        // Ambil semua sekolah yang punya tim
        $schools = TeamList::select('school_name')
            ->distinct()
            ->orderBy('school_name')
            ->get();

        $teamList = collect();

        foreach ($schools as $school) {
            // Ambil semua tim untuk sekolah ini
            $teams = TeamList::where('school_name', $school->school_name)
                ->orderBy('created_at', 'asc') // Urutkan dari yang pertama daftar
                ->get();

            if ($teams->count() > 0) {
                // Tim PERTAMA yang mendaftar (ini yang akan tampil di tabel)
                $firstTeam = $teams->first();

                // Buat object gabungan
                $mergedTeam = new \stdClass();
                $mergedTeam->team_id = $firstTeam->team_id;
                $mergedTeam->school_name = $firstTeam->school_name;
                $mergedTeam->school_logo = $firstTeam->school_logo;
                $mergedTeam->competition = $firstTeam->competition;
                $mergedTeam->season = $firstTeam->season;
                $mergedTeam->series = $firstTeam->series;
                $mergedTeam->updated_at = $firstTeam->updated_at;
                $mergedTeam->locked_status = $firstTeam->locked_status;
                $mergedTeam->verification_status = $firstTeam->verification_status;

                // 🔥 REG BY = registered_by dari tim PERTAMA (yang buat sekolah)
                $mergedTeam->registered_by = $firstTeam->registered_by;

                // 🔥 TEAM NAME = kategori tim PERTAMA + school_name
                $mergedTeam->team_name = $firstTeam->team_category . ' - ' . $firstTeam->school_name;

                // 🔥 Referral code - tampilkan yang pertama
                $mergedTeam->referral_code = $firstTeam->referral_code;

                // Koleksi semua kategori yang tersedia di sekolah ini (untuk badge)
                $mergedTeam->categories = $teams->pluck('team_category')->unique()->values()->toArray();

                $teamList->push($mergedTeam);
            }
        }

        // Filter berdasarkan request
        if ($request->filled('school')) {
            $teamList = $teamList->where('school_name', $request->school);
        }

        if ($request->filled('category')) {
            $teamList = $teamList->filter(function ($item) use ($request) {
                return in_array($request->category, $item->categories);
            });
        }

        if ($request->filled('status')) {
            $teamList = $teamList->where('verification_status', $request->status);
        }

        if ($request->filled('locked')) {
            $teamList = $teamList->where('locked_status', $request->locked);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $teamList = $teamList->filter(function ($item) use ($search) {
                return str_contains(strtolower($item->school_name), strtolower($search)) ||
                    str_contains(strtolower($item->registered_by), strtolower($search)) ||
                    str_contains(strtolower($item->team_name), strtolower($search));
            });
        }

        // Sort
        $sort = $request->get('sort', 'updated_at');
        $order = $request->get('order', 'desc');

        if ($sort == 'updated_at' || $sort == 'created_at') {
            $teamList = $teamList->sortByDesc(function ($item) use ($sort) {
                return $item->$sort;
            });
        } else {
            $teamList = $teamList->sortBy($sort, SORT_REGULAR, $order == 'desc');
        }

        // Pagination manual
        $perPage = 50;
        $currentPage = $request->get('page', 1);
        $total = $teamList->count();
        $items = $teamList->forPage($currentPage, $perPage);

        $teamList = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get filter options
        $years = TeamList::selectRaw('DISTINCT season')
            ->whereNotNull('season')
            ->orderBy('season', 'desc')
            ->pluck('season')
            ->unique()
            ->values();

        $schools = TeamList::distinct('school_name')
            ->orderBy('school_name')
            ->pluck('school_name');

        $competitions = TeamList::distinct('competition')
            ->whereNotNull('competition')
            ->orderBy('competition')
            ->pluck('competition');

        return view('team_verification.tv_team_list', compact('teamList', 'schools', 'competitions', 'years'));
    }

    /**
     * Export teams to Excel
     */
    public function export(Request $request)
    {
        $query = $this->applyFilters($request);
        $teams = $query->get();
        $filename = 'teams_export_' . date('Y-m-d_H-i') . '.xlsx';
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
     * Show team detail with tabs
     */
    public function teamShow($id)
    {
        $mainTeam = $this->getMainTeam($id);
        $teamData = $this->getAllTeamData($mainTeam->school_name);
        $activeTab = request()->get('tab', $this->getDefaultActiveTab($teamData));
        $categories = ['Basket Putra', 'Basket Putri', 'Dancer'];

        Log::info('Team Show - Active Tab:', [
            'request_tab' => request()->get('tab'),
            'activeTab' => $activeTab,
            'default_tab' => $this->getDefaultActiveTab($teamData)
        ]);

        return view('team_verification.tv_team_detail', compact('mainTeam', 'teamData', 'activeTab', 'categories'));
    }

    /**
     * Get main team by ID
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
     * Ensure consistent school_id across tables
     */
    private function ensureConsistentSchoolId($schoolName)
    {
        $school = School::where('school_name', $schoolName)->first();

        if (!$school) {
            $school = School::create([
                'school_name' => $schoolName,
                'category_name' => 'SMA',
                'type' => 'SWASTA',
                'city_id' => 1,
            ]);
            Log::info('Created new school: ' . $school->id . ' - ' . $schoolName);
        }

        $updatedTeams = TeamList::where('school_name', $schoolName)
            ->where(function ($query) use ($school) {
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
     * 🔥🔥🔥 FIX UTAMA: Get all team data by school name
     * FILTER OFFICIAL BERDASARKAN CATEGORY!
     */
    private function getAllTeamData($schoolName)
    {
        $teamData = [];
        $schoolId = $this->ensureConsistentSchoolId($schoolName);
        $teams = TeamList::where('school_name', $schoolName)->get();

        Log::info('=== GET ALL TEAM DATA ===', [
            'school' => $schoolName,
            'total_teams' => $teams->count()
        ]);

        // ========== BASKET PUTRA ==========
        $teamPutra = $teams->where('team_category', 'Basket Putra')->first();
        $teamData['Basket Putra'] = [
            'team' => $teamPutra,
            'players' => $teamPutra ? PlayerList::where('team_id', $teamPutra->team_id)
                ->where('gender', 'Male')
                ->orderByRaw("CASE WHEN role = 'Leader' THEN 0 ELSE 1 END")
                ->orderBy('jersey_number', 'asc')
                ->get() : collect(),
            // ✅ FILTER OFFICIAL: HANYA category = 'basket_putra'
            'officials' => $teamPutra ? OfficialList::where('team_id', $teamPutra->team_id)
                ->where('category', 'basket_putra')  // FILTER PENTING!
                ->orderBy('name', 'asc')
                ->get() : collect(),
            'exists' => !is_null($teamPutra)
        ];

        Log::info('Basket Putra:', [
            'team_exists' => $teamData['Basket Putra']['exists'],
            'players_count' => $teamData['Basket Putra']['players']->count(),
            'officials_count' => $teamData['Basket Putra']['officials']->count(),
        ]);

        // ========== BASKET PUTRI ==========
        $teamPutri = $teams->where('team_category', 'Basket Putri')->first();
        $teamData['Basket Putri'] = [
            'team' => $teamPutri,
            'players' => $teamPutri ? PlayerList::where('team_id', $teamPutri->team_id)
                ->where('gender', 'Female')
                ->orderByRaw("CASE WHEN role = 'Leader' THEN 0 ELSE 1 END")
                ->orderBy('jersey_number', 'asc')
                ->get() : collect(),
            // ✅ FILTER OFFICIAL: HANYA category = 'basket_putri'
            'officials' => $teamPutri ? OfficialList::where('team_id', $teamPutri->team_id)
                ->where('category', 'basket_putri')  // FILTER PENTING!
                ->orderBy('name', 'asc')
                ->get() : collect(),
            'exists' => !is_null($teamPutri)
        ];

        Log::info('Basket Putri:', [
            'team_exists' => $teamData['Basket Putri']['exists'],
            'players_count' => $teamData['Basket Putri']['players']->count(),
            'officials_count' => $teamData['Basket Putri']['officials']->count(),
        ]);

        // ========== DANCER ==========
        $teamDancer = $teams->where('team_category', 'Dancer')->first();
        $teamData['Dancer'] = [
            'team' => $teamDancer,
            'players' => $teamDancer ? DancerList::where('team_id', $teamDancer->team_id)
                ->orderByRaw("CASE WHEN role = 'Leader' THEN 0 ELSE 1 END")
                ->orderBy('name', 'asc')
                ->get() : collect(),
            // ✅ FILTER OFFICIAL: HANYA category = 'dancer'
            'officials' => $teamDancer ? OfficialList::where('team_id', $teamDancer->team_id)
                ->where('category', 'dancer')  // FILTER PENTING!
                ->orderBy('name', 'asc')
                ->get() : collect(),
            'exists' => !is_null($teamDancer)
        ];

        Log::info('Dancer:', [
            'team_exists' => $teamData['Dancer']['exists'],
            'players_count' => $teamData['Dancer']['players']->count(),
            'officials_count' => $teamData['Dancer']['officials']->count(),
        ]);

        Log::info('=== END GET ALL TEAM DATA ===');

        return $teamData;
    }

    /**
     * Get default active tab
     */
    private function getDefaultActiveTab($teamData)
    {
        foreach (['Basket Putra', 'Basket Putri', 'Dancer'] as $category) {
            if (isset($teamData[$category]['exists']) && $teamData[$category]['exists']) {
                return $category;
            }
        }
        return 'Basket Putra';
    }

    /**
     * Tab redirect methods
     */
    public function teamDetailBasketPutra($teamId)
    {
        return redirect()->route('admin.team-list.show', [
            'id' => $teamId,
            'tab' => 'Basket Putra'
        ]);
    }

    public function teamDetailBasketPutri($teamId)
    {
        return redirect()->route('admin.team-list.show', [
            'id' => $teamId,
            'tab' => 'Basket Putri'
        ]);
    }

    public function teamDetailDancer($teamId)
    {
        return redirect()->route('admin.team-list.show', [
            'id' => $teamId,
            'tab' => 'Dancer'
        ]);
    }

    /**
     * Team actions
     */
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

    /**
     * Player detail
     */
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

    /**
     * Dancer detail
     */
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

    /**
     * Official detail
     */
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

    /**
     * Fix school data
     */
    public function fixSchoolData($schoolName)
    {
        try {
            Log::info('=== FIX SCHOOL DATA START ===');
            Log::info('School Name: ' . $schoolName);

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

            $teamsUpdated = TeamList::where('school_name', $schoolName)
                ->update(['school_id' => $school->id]);
            Log::info('Updated ' . $teamsUpdated . ' teams');

            $teamIds = TeamList::where('school_name', $schoolName)->pluck('team_id');

            $playersUpdated = PlayerList::whereIn('team_id', $teamIds)
                ->update(['school_id' => $school->id]);
            Log::info('Updated ' . $playersUpdated . ' players');

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
