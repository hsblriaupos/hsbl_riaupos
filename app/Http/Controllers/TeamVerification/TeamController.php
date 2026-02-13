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
use Illuminate\Support\Facades\DB;

class TeamController extends Controller
{
    /**
     * Display team list with filters
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
            $teams = TeamList::where('school_name', $school->school_name)
                ->orderBy('created_at', 'asc')
                ->get();

            if ($teams->count() > 0) {
                $firstTeam = $teams->first();

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
                $mergedTeam->registered_by = $firstTeam->registered_by;
                $mergedTeam->team_name = $firstTeam->team_category . ' - ' . $firstTeam->school_name;
                $mergedTeam->referral_code = $firstTeam->referral_code;
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
     * 🔥🔥🔥 FIX UTAMA: Show team detail - MENGIKUTI TeamListProfileController
     */
    public function teamShow($id)
    {
        $mainTeam = $this->getMainTeam($id);
        
        // AMBIL SEMUA DATA SEPERTI DI TEAMLISTPROFILECONTROLLER
        $teamData = $this->getAllTeamData($mainTeam->school_name);
        
        $activeTab = request()->get('tab', $this->getDefaultActiveTab($teamData));
        
        Log::info('=== TEAM SHOW ===', [
            'school' => $mainTeam->school_name,
            'activeTab' => $activeTab,
            'basket_putra_exists' => isset($teamData['team_putra']),
            'basket_putra_players' => count($teamData['players_male'] ?? []),
            'basket_putri_exists' => isset($teamData['team_putri']),
            'basket_putri_players' => count($teamData['players_female'] ?? []),
            'dancer_exists' => isset($teamData['team_dancer']),
            'dancer_count' => count($teamData['dancers'] ?? [])
        ]);

        return view('team_verification.tv_team_detail', compact('mainTeam', 'teamData', 'activeTab'));
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

        return $school->id;
    }

    /**
     * 🔥🔥🔥 FIX UTAMA: GET ALL TEAM DATA - MENGIKUTI TEAMLISTPROFILECONTROLLER
     */
    private function getAllTeamData($schoolName)
    {
        $this->ensureConsistentSchoolId($schoolName);
        
        // AMBIL SEMUA TIM UNTUK SEKOLAH INI
        $teams = TeamList::where('school_name', $schoolName)->get();
        
        // ========== AMBIL DATA TEAM LENGKAP DENGAN LOGO ==========
        $teamPutra = $teams->where('team_category', 'Basket Putra')->first();
        $teamPutri = $teams->where('team_category', 'Basket Putri')->first();
        $teamDancer = $teams->where('team_category', 'Dancer')->first();
        
        // ========== FORMAT LOGO URL ==========
        $teamPutra = $this->formatTeamLogo($teamPutra);
        $teamPutri = $this->formatTeamLogo($teamPutri);
        $teamDancer = $this->formatTeamLogo($teamDancer);
        
        // ========== FORMAT DOKUMEN URL ==========
        $teamPutra = $this->formatTeamDocuments($teamPutra);
        $teamPutri = $this->formatTeamDocuments($teamPutri);
        $teamDancer = $this->formatTeamDocuments($teamDancer);
        
        // ========== FORMAT JERSEY URL ==========
        $teamPutra = $this->formatTeamJersey($teamPutra);
        $teamPutri = $this->formatTeamJersey($teamPutri);
        $teamDancer = $this->formatTeamJersey($teamDancer);
        
        // ========== AMBIL PLAYER BASED ON GENDER ==========
        $playersMale = [];
        $playersFemale = [];
        
        if ($teamPutra) {
            $playersMale = $this->getPlayersByTeamAndGender($teamPutra->team_id, 'male');
        }
        
        if ($teamPutri) {
            $playersFemale = $this->getPlayersByTeamAndGender($teamPutri->team_id, 'female');
        }
        
        // ========== AMBIL DANCERS ==========
        $dancers = collect();
        if ($teamDancer) {
            $dancers = $this->getDancersByTeamId($teamDancer->team_id);
        }
        
        // ========== AMBIL OFFICIALS PER KATEGORI ==========
        $officialsBasketMale = collect();
        $officialsBasketFemale = collect();
        $officialsDancer = collect();
        $allOfficials = collect();
        
        if ($teamPutra) {
            $officialsBasketMale = $this->getOfficialsByTeamAndCategory($teamPutra->team_id, 'basket_putra');
            $allOfficials = $allOfficials->merge($officialsBasketMale);
        }
        
        if ($teamPutri) {
            $officialsBasketFemale = $this->getOfficialsByTeamAndCategory($teamPutri->team_id, 'basket_putri');
            $allOfficials = $allOfficials->merge($officialsBasketFemale);
        }
        
        if ($teamDancer) {
            $officialsDancer = $this->getOfficialsByTeamAndCategory($teamDancer->team_id, 'dancer');
            $allOfficials = $allOfficials->merge($officialsDancer);
        }
        
        // ========== FORMAT DATA UNTUK VIEW ==========
        $teamData = [
            // TEAM OBJECTS
            'team_putra' => $teamPutra,
            'team_putri' => $teamPutri,
            'team_dancer' => $teamDancer,
            
            // PLAYERS
            'players_male' => $playersMale,
            'players_female' => $playersFemale,
            'total_players_male' => count($playersMale),
            'total_players_female' => count($playersFemale),
            'total_players' => count($playersMale) + count($playersFemale),
            
            // DANCERS
            'dancers' => $dancers,
            'total_dancers' => $dancers->count(),
            
            // OFFICIALS
            'officials_basket_male' => $officialsBasketMale,
            'officials_basket_female' => $officialsBasketFemale,
            'officials_dancer' => $officialsDancer,
            'all_officials' => $allOfficials,
            'total_officials' => $allOfficials->count(),
            
            // TEAM INFO
            'team_name' => $teamPutra->school_name ?? $teamPutri->school_name ?? $teamDancer->school_name ?? $schoolName,
            'competition' => $teamPutra->competition ?? $teamPutri->competition ?? $teamDancer->competition ?? 'HSBL',
            'team_category' => $teamPutra->team_category ?? $teamPutri->team_category ?? $teamDancer->team_category ?? 'Basketball',
            'season' => $teamPutra->season ?? $teamPutri->season ?? $teamDancer->season ?? date('Y'),
            'series' => $teamPutra->series ?? $teamPutri->series ?? $teamDancer->series ?? '1',
        ];
        
        return $teamData;
    }

    /**
     * Format team logo with multiple fallback
     */
    private function formatTeamLogo($team)
    {
        if (!$team) return null;
        
        if (!empty($team->school_logo)) {
            $logoFile = basename($team->school_logo);
            
            if (file_exists(public_path('storage/school_logos/' . $logoFile))) {
                $team->logo_url = asset('storage/school_logos/' . $logoFile) . '?v=' . time();
            } elseif (Storage::disk('public')->exists('school_logos/' . $logoFile)) {
                $team->logo_url = Storage::url('school_logos/' . $logoFile) . '?v=' . time();
            } elseif (file_exists(public_path('school_logos/' . $logoFile))) {
                $team->logo_url = asset('school_logos/' . $logoFile) . '?v=' . time();
            } elseif (file_exists(public_path('uploads/school_logos/' . $logoFile))) {
                $team->logo_url = asset('uploads/school_logos/' . $logoFile) . '?v=' . time();
            } elseif (file_exists(public_path($team->school_logo))) {
                $team->logo_url = asset($team->school_logo) . '?v=' . time();
            }
        }
        
        return $team;
    }

    /**
     * Format team documents URL
     */
    private function formatTeamDocuments($team)
    {
        if (!$team) return null;
        
        // Recommendation letter
        if (!empty($team->recommendation_letter)) {
            $team->recommendation_url = $this->getDocumentUrl($team->recommendation_letter);
        }
        
        // Koran
        if (!empty($team->koran)) {
            $team->koran_url = $this->getDocumentUrl($team->koran);
        }
        
        return $team;
    }

    /**
     * Format team jersey URL
     */
    private function formatTeamJersey($team)
    {
        if (!$team) return null;
        
        // Jersey Home
        if (!empty($team->jersey_home)) {
            $team->jersey_home_url = $this->getJerseyUrl($team->jersey_home);
        }
        
        // Jersey Away
        if (!empty($team->jersey_away)) {
            $team->jersey_away_url = $this->getJerseyUrl($team->jersey_away);
        }
        
        // Jersey Alternate
        if (!empty($team->jersey_alternate)) {
            $team->jersey_alternate_url = $this->getJerseyUrl($team->jersey_alternate);
        }
        
        return $team;
    }

    /**
     * Get document URL with multiple fallback
     */
    private function getDocumentUrl($path)
    {
        if (empty($path)) {
            return null;
        }

        if (file_exists(public_path('storage/' . $path))) {
            return asset('storage/' . $path) . '?v=' . time();
        }
        
        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path) . '?v=' . time();
        }
        
        if (file_exists(public_path($path))) {
            return asset($path) . '?v=' . time();
        }
        
        return null;
    }

    /**
     * Get jersey URL with multiple fallback
     */
    private function getJerseyUrl($path)
    {
        if (empty($path)) {
            return null;
        }

        if (file_exists(public_path('storage/' . $path))) {
            return asset('storage/' . $path) . '?v=' . time();
        }
        
        if (Storage::disk('public')->exists($path)) {
            return Storage::url($path) . '?v=' . time();
        }
        
        if (file_exists(public_path($path))) {
            return asset($path) . '?v=' . time();
        }
        
        return null;
    }

    /**
     * 🔥🔥🔥 FIX: Filter player berdasarkan gender - SAMA PERSIS DENGAN teamlist.blade.php
     */
    private function getPlayersByTeamAndGender($teamId, $gender)
    {
        $players = PlayerList::where('team_id', $teamId)->get();
        
        $filteredPlayers = [];
        
        foreach ($players as $player) {
            $playerGender = strtolower($player->gender ?? $player->category ?? '');
            
            if ($gender == 'male') {
                if (in_array($playerGender, ['male', 'putra', 'laki-laki'])) {
                    $filteredPlayers[] = $player;
                }
            } else {
                if (in_array($playerGender, ['female', 'putri', 'perempuan'])) {
                    $filteredPlayers[] = $player;
                }
            }
        }
        
        // Format data untuk tampilan
        foreach ($filteredPlayers as $player) {
            $player->formatted_role = $this->formatRole($player->role);
            $player->jersey_display = $player->jersey_number ?? '00';
            $player->formal_photo_url = $this->getFormalPhotoUrl($player->formal_photo, 'player');
        }
        
        Log::info('FILTER PLAYERS ' . strtoupper($gender), [
            'team_id' => $teamId,
            'total' => count($filteredPlayers)
        ]);
        
        return $filteredPlayers;
    }

    /**
     * 🔥🔥🔥 FIX: Get dancers - RETURN COLLECTION, BUKAN ARRAY
     */
    private function getDancersByTeamId($teamId)
    {
        $dancers = DancerList::where('team_id', $teamId)
            ->orderByRaw("CASE WHEN role = 'Leader' THEN 0 ELSE 1 END")
            ->orderBy('name', 'asc')
            ->get();
        
        // Format data untuk tampilan
        foreach ($dancers as $dancer) {
            $dancer->formatted_role = $this->formatRole($dancer->role);
            $dancer->formatted_gender = $dancer->gender ?? 'Dancer';
            $dancer->avatar_color = $this->getDancerAvatarColor($dancer->gender);
            $dancer->avatar_bg_class = $this->getDancerAvatarBgClass($dancer->gender);
            $dancer->formal_photo_url = $this->getFormalPhotoUrl($dancer->formal_photo, 'dancer');
        }
        
        Log::info('DANCERS:', [
            'team_id' => $teamId,
            'total' => $dancers->count()
        ]);
        
        return $dancers; // ✅ RETURN COLLECTION, BUKAN ARRAY
    }

    /**
     * 🔥🔥🔥 FIX: Get officials by team and category - RETURN COLLECTION, BUKAN ARRAY
     */
    private function getOfficialsByTeamAndCategory($teamId, $category)
    {
        $officials = OfficialList::where('team_id', $teamId)
            ->where('category', $category)
            ->orderBy('name', 'asc')
            ->get();
        
        // Format data untuk tampilan
        foreach ($officials as $official) {
            $official->formatted_team_role = $this->formatRole($official->team_role);
            $official->formatted_role = $this->formatRole($official->role);
            $official->formatted_gender = $official->gender ?? '-';
            $official->avatar_color = 'ed6c02';
            $official->avatar_bg_class = 'bg-warning';
            $official->formal_photo_url = $this->getFormalPhotoUrl($official->formal_photo, 'official');
            
            // Format category badge
            $official->category_badge_class = $this->getCategoryBadgeClass($official->category);
            $official->category_badge_icon = $this->getCategoryBadgeIcon($official->category);
            $official->category_display = $this->formatCategory($official->category);
        }
        
        return $officials; // ✅ RETURN COLLECTION, BUKAN ARRAY
    }

    /**
     * 🔥🔥🔥 Get all officials by team_ids - RETURN COLLECTION
     */
    private function getAllOfficialsByTeamIds($teamIds)
    {
        if (empty($teamIds)) {
            return collect();
        }

        $officials = OfficialList::whereIn('team_id', $teamIds)
            ->orderBy('category', 'asc')
            ->orderBy('name', 'asc')
            ->get();

        // Format data untuk tampilan
        foreach ($officials as $official) {
            $official->formatted_role = $this->formatRole($official->role);
            $official->formatted_team_role = $this->formatRole($official->team_role);
            $official->formatted_gender = $official->gender ?? '-';
            $official->avatar_color = 'ed6c02';
            $official->avatar_bg_class = 'bg-warning';
            $official->formal_photo_url = $this->getFormalPhotoUrl($official->formal_photo, 'official');
            
            // Format category badge
            $official->category_badge_class = $this->getCategoryBadgeClass($official->category);
            $official->category_badge_icon = $this->getCategoryBadgeIcon($official->category);
            $official->category_display = $this->formatCategory($official->category);
        }

        return $officials; // ✅ RETURN COLLECTION
    }

    /**
     * Get dancer avatar color
     */
    private function getDancerAvatarColor($gender)
    {
        $genderLower = strtolower($gender ?? '');
        if (in_array($genderLower, ['male', 'laki-laki', 'putra'])) {
            return '2e7d32'; // Hijau
        }
        return 'd81b60'; // Pink
    }

    /**
     * Get dancer avatar background class
     */
    private function getDancerAvatarBgClass($gender)
    {
        $genderLower = strtolower($gender ?? '');
        if (in_array($genderLower, ['male', 'laki-laki', 'putra'])) {
            return 'bg-success';
        }
        return 'bg-pink-600'; // Pink
    }

    /**
     * Get category badge class
     */
    private function getCategoryBadgeClass($category)
    {
        switch ($category) {
            case 'basket_putra':
                return 'badge-category-putra';
            case 'basket_putri':
                return 'badge-category-putri';
            case 'dancer':
                return 'badge-category-dancer';
            default:
                return 'badge-category-lainnya';
        }
    }

    /**
     * Get category badge icon
     */
    private function getCategoryBadgeIcon($category)
    {
        switch ($category) {
            case 'basket_putra':
            case 'basket_putri':
                return 'fas fa-basketball-ball';
            case 'dancer':
                return 'fas fa-music';
            default:
                return 'fas fa-user-tie';
        }
    }

    /**
     * Format category for display
     */
    private function formatCategory($category)
    {
        if (empty($category)) return '-';
        
        $mapping = [
            'basket_putra' => 'Basket Putra',
            'basket_putri' => 'Basket Putri',
            'dancer' => 'Dancer'
        ];
        
        return $mapping[$category] ?? ucfirst(str_replace('_', ' ', $category));
    }

    /**
     * Format role string
     */
    private function formatRole($role)
    {
        if (empty($role)) return null;
        return ucfirst(str_replace('_', ' ', $role));
    }

    /**
     * Get formal photo URL with multiple fallback
     */
    private function getFormalPhotoUrl($photo, $type)
    {
        if (empty($photo)) {
            return null;
        }

        $fileName = basename($photo);
        
        // Cek di berbagai lokasi
        $locations = $this->getPhotoPathsByType($type, $fileName);
        
        foreach ($locations as $location) {
            if (file_exists($location) && !is_dir($location)) {
                return $this->convertPathToUrl($location) . '?v=' . time();
            }
        }
        
        return null;
    }

    /**
     * Get photo paths based on type
     */
    private function getPhotoPathsByType($type, $fileName)
    {
        $paths = [];
        
        switch ($type) {
            case 'player':
                $paths[] = storage_path('app/public/player_docs/' . $fileName);
                $paths[] = public_path('storage/player_docs/' . $fileName);
                $paths[] = public_path('player_docs/' . $fileName);
                $paths[] = storage_path('app/player_docs/' . $fileName);
                break;
            case 'dancer':
                $paths[] = storage_path('app/public/dancer_docs/' . $fileName);
                $paths[] = public_path('storage/dancer_docs/' . $fileName);
                $paths[] = public_path('dancer_docs/' . $fileName);
                $paths[] = storage_path('app/dancer_docs/' . $fileName);
                break;
            case 'official':
                $paths[] = storage_path('app/public/uploads/officials/formal_photos/' . $fileName);
                $paths[] = public_path('storage/uploads/officials/formal_photos/' . $fileName);
                $paths[] = public_path('uploads/officials/formal_photos/' . $fileName);
                $paths[] = storage_path('app/uploads/officials/formal_photos/' . $fileName);
                break;
        }
        
        return array_unique($paths);
    }

    /**
     * Convert path to URL
     */
    private function convertPathToUrl($path)
    {
        if (strpos($path, public_path()) === 0) {
            $relativePath = str_replace(public_path(), '', $path);
            return asset(ltrim($relativePath, '\\/'));
        }
        
        if (strpos($path, storage_path('app/public')) === 0) {
            $relativePath = str_replace(storage_path('app/public'), '', $path);
            return asset('storage' . str_replace('\\', '/', $relativePath));
        }
        
        return asset($path);
    }

    /**
     * Get default active tab
     */
    private function getDefaultActiveTab($teamData)
    {
        if (isset($teamData['team_putra'])) return 'Basket Putra';
        if (isset($teamData['team_putri'])) return 'Basket Putri';
        if (isset($teamData['team_dancer'])) return 'Dancer';
        return 'Basket Putra';
    }

    /**
     * 🔥🔥🔥 DEBUG: Cek data di database
     */
    public function debugTeamData($teamId)
    {
        $team = TeamList::where('team_id', $teamId)->first();
        if (!$team) {
            return response()->json(['error' => 'Team tidak ditemukan'], 404);
        }

        $debug = [
            'team' => [
                'id' => $team->team_id,
                'school' => $team->school_name,
                'category' => $team->team_category
            ],
            'players' => [
                'total' => PlayerList::where('team_id', $teamId)->count(),
                'male' => PlayerList::where('team_id', $teamId)
                    ->whereIn('gender', ['Male', 'male', 'Laki-laki', 'Putra'])
                    ->count(),
                'female' => PlayerList::where('team_id', $teamId)
                    ->whereIn('gender', ['Female', 'female', 'Perempuan', 'Putri'])
                    ->count(),
                'by_category' => PlayerList::where('team_id', $teamId)
                    ->select('category', DB::raw('count(*) as total'))
                    ->groupBy('category')
                    ->get(),
                'by_gender' => PlayerList::where('team_id', $teamId)
                    ->select('gender', DB::raw('count(*) as total'))
                    ->groupBy('gender')
                    ->get(),
                'sample' => PlayerList::where('team_id', $teamId)->take(5)->get()
            ],
            'dancers' => [
                'total' => DancerList::where('team_id', $teamId)->count(),
                'data' => DancerList::where('team_id', $teamId)->get(),
                'columns' => $this->getTableColumns('dancer_list')
            ],
            'officials' => [
                'total' => OfficialList::where('team_id', $teamId)->count(),
                'by_category' => OfficialList::where('team_id', $teamId)
                    ->select('category', DB::raw('count(*) as total'))
                    ->groupBy('category')
                    ->get()
            ]
        ];

        return response()->json($debug);
    }

    /**
     * Get table columns
     */
    private function getTableColumns($table)
    {
        try {
            return DB::getSchemaBuilder()->getColumnListing($table);
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
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

    public function teamDetailOfficial($teamId)
    {
        return redirect()->route('admin.team-list.show', [
            'id' => $teamId,
            'tab' => 'Official'
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

        $schoolName = $player->team->school_name ?? null;
        $player->formal_photo_url = $this->getFormalPhotoUrl($player->formal_photo, 'player');

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

        $schoolName = $dancer->team->school_name ?? null;
        $dancer->formal_photo_url = $this->getFormalPhotoUrl($dancer->formal_photo, 'dancer');

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

        $schoolName = $official->team->school_name ?? null;
        $official->formal_photo_url = $this->getFormalPhotoUrl($official->formal_photo, 'official');
        $official->formatted_team_role = $this->formatRole($official->team_role);
        $official->formatted_category = $this->formatCategory($official->category);
        $official->category_badge_class = $this->getCategoryBadgeClass($official->category);
        $official->category_badge_icon = $this->getCategoryBadgeIcon($official->category);

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