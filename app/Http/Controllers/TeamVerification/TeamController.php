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
     * Helper method untuk mendapatkan semua data tim berdasarkan nama sekolah (PERBAIKAN UTAMA)
     */
    private function getAllTeamData($schoolName)
    {
        $teamData = [];

        // Ambil semua tim dari sekolah ini
        $teams = TeamList::where('school_name', $schoolName)->get();

        // 🔥 BASKET PUTRA - FILTER TEAM_ID
        $teamPutra = $teams->where('team_category', 'Basket Putra')->first();
        $teamData['Basket Putra'] = [
            'team' => $teamPutra,
            'players' => $teamPutra ? PlayerList::where('team_id', $teamPutra->team_id)
                ->orderByRaw("CASE WHEN role = 'Leader' THEN 0 ELSE 1 END")
                ->orderBy('jersey_number', 'asc')
                ->get() : collect(),
            'officials' => $teamPutra ? OfficialList::where('team_id', $teamPutra->team_id)
                ->where('category', 'basket_putra')
                ->orderByRaw("CASE WHEN role = 'Leader' THEN 0 ELSE 1 END")
                ->get() : collect(),
            'exists' => !is_null($teamPutra)
        ];

        // 🔥 BASKET PUTRI - FILTER TEAM_ID
        $teamPutri = $teams->where('team_category', 'Basket Putri')->first();
        $teamData['Basket Putri'] = [
            'team' => $teamPutri,
            'players' => $teamPutri ? PlayerList::where('team_id', $teamPutri->team_id)
                ->orderByRaw("CASE WHEN role = 'Leader' THEN 0 ELSE 1 END")
                ->orderBy('jersey_number', 'asc')
                ->get() : collect(),
            'officials' => $teamPutri ? OfficialList::where('team_id', $teamPutri->team_id)
                ->where('category', 'basket_putri')
                ->orderByRaw("CASE WHEN role = 'Leader' THEN 0 ELSE 1 END")
                ->get() : collect(),
            'exists' => !is_null($teamPutri)
        ];

        // 🔥 DANCER - FILTER TEAM_ID
        $teamDancer = $teams->where('team_category', 'Dancer')->first();
        $teamData['Dancer'] = [
            'team' => $teamDancer,
            'players' => $teamDancer ? DancerList::where('team_id', $teamDancer->team_id)
                ->orderByRaw("CASE WHEN role = 'Leader' THEN 0 ELSE 1 END")
                ->orderBy('name', 'asc')
                ->get() : collect(),
            'officials' => $teamDancer ? OfficialList::where('team_id', $teamDancer->team_id)
                ->where('category', 'dancer')
                ->orderByRaw("CASE WHEN role = 'Leader' THEN 0 ELSE 1 END")
                ->get() : collect(),
            'exists' => !is_null($teamDancer)
        ];

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
