<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TeamList;
use App\Models\PlayerList;
use App\Models\DancerList;
use App\Models\OfficialList;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class TeamListProfileController extends Controller
{
    /**
     * Display team members list based on team_id
     * Hanya berelasi dengan tabel team_list, player_list, dancer_list, official_list
     */
    public function index(Request $request, $teamId = null)
    {
        // Jika teamId tidak diberikan, coba ambil dari request
        if (!$teamId) {
            $teamId = $request->get('team_id');
        }

        // Validasi teamId
        if (!$teamId) {
            return redirect()->route('student.event.histories')
                ->with('error', 'Team ID tidak ditemukan.');
        }

        // Ambil data team dari team_list
        $team = $this->getTeamData($teamId);
        
        if (!$team) {
            return redirect()->route('student.event.histories')
                ->with('error', 'Data tim tidak ditemukan.');
        }

        // Ambil data players berdasarkan team_id
        $players = $this->getPlayersByTeamId($teamId);
        
        // Ambil data dancers berdasarkan team_id
        $dancers = $this->getDancersByTeamId($teamId);
        
        // Ambil data officials berdasarkan team_id
        $officials = $this->getOfficialsByTeamId($teamId);

        // Pisahkan players berdasarkan category (Putra/Putri)
        $playersPutra = $players->where('category', 'Putra')->values();
        $playersPutri = $players->where('category', 'Putri')->values();
        
        // Jika category menggunakan format lowercase atau variasi lain
        if ($playersPutra->isEmpty() && $playersPutri->isEmpty()) {
            $playersPutra = $players->filter(function($player) {
                $category = strtolower($player->category ?? '');
                return $category === 'putra' || $category === 'male' || $category === 'laki-laki';
            })->values();
            
            $playersPutri = $players->filter(function($player) {
                $category = strtolower($player->category ?? '');
                return $category === 'putri' || $category === 'female' || $category === 'perempuan';
            })->values();
        }

        // Hitung total masing-masing role
        $totalPlayers = $players->count();
        $totalPlayersPutra = $playersPutra->count();
        $totalPlayersPutri = $playersPutri->count();
        $totalDancers = $dancers->count();
        $totalOfficials = $officials->count();

        // Data untuk header - ambil dari team_list
        $teamName = $team->school_name ?? $team->team_name ?? 'Team';
        $competition = $team->competition ?? 'HSBL';
        $teamCategory = $team->team_category ?? 'Basketball';
        $season = $team->season ?? date('Y');
        $series = $team->series ?? '1';
        
        // Logo team - format seperti di schooldata-edit.blade.php
        $teamLogo = $this->getTeamLogo($team);
        
        // Referral code
        $referralCode = $team->referral_code ?? null;

        return view('user.event.profile.teamlist', compact(
            'teamId',
            'team',
            'teamName',
            'competition',
            'teamCategory',
            'season',
            'series',
            'teamLogo',
            'referralCode',
            'players',
            'playersPutra',
            'playersPutri',
            'dancers',
            'officials',
            'totalPlayers',
            'totalPlayersPutra',
            'totalPlayersPutri',
            'totalDancers',
            'totalOfficials'
        ));
    }

    /**
     * Get team data from team_list table
     */
    private function getTeamData($teamId)
    {
        // Ambil dari team_list - sumber utama data team
        return TeamList::where('team_id', $teamId)->first();
    }

    /**
     * Get players by team_id from player_list table
     * DIPERBAIKI: Mengikuti logika review-data.blade.php untuk pencarian file
     */
    private function getPlayersByTeamId($teamId)
    {
        $players = PlayerList::where('team_id', $teamId)->get();

        // Format foto path
        foreach ($players as $player) {
            // Gunakan fungsi lengkap untuk pencarian foto formal
            $player->formal_photo_url = $this->getFormalPhotoUrl($player->formal_photo, 'player', $player->team_id);
            
            // Tambahkan flag untuk cek apakah foto benar-benar ada
            $player->has_formal_photo = !empty($player->formal_photo_url);
            
            // Format data tambahan untuk tampilan
            $player->formatted_role = $player->role ? ucfirst(str_replace('_', ' ', $player->role)) : null;
            $player->formatted_category = $player->category ?? 'Putra';
            $player->jersey_display = $player->jersey_number ?? '00';
            $player->height_display = $player->height ?? null;
            
            // Avatar color berdasarkan kategori
            $player->avatar_color = $this->getAvatarColor($player->category);
            $player->avatar_bg_class = $this->getAvatarBgClass($player->category);
        }

        return $players;
    }

    /**
     * Get dancers by team_id from dancer_list table
     * DIPERBAIKI: Mengikuti logika review-data.blade.php untuk pencarian file
     */
    private function getDancersByTeamId($teamId)
    {
        $dancers = DancerList::where('team_id', $teamId)->get();

        // Format foto path
        foreach ($dancers as $dancer) {
            // Gunakan fungsi lengkap untuk pencarian foto formal
            $dancer->formal_photo_url = $this->getFormalPhotoUrl($dancer->formal_photo, 'dancer', $dancer->team_id);
            
            // Tambahkan flag untuk cek apakah foto benar-benar ada
            $dancer->has_formal_photo = !empty($dancer->formal_photo_url);
            
            // Format data tambahan untuk tampilan
            $dancer->formatted_role = $dancer->role ? ucfirst(str_replace('_', ' ', $dancer->role)) : null;
            $dancer->formatted_gender = $dancer->gender ?? 'Dancer';
            $dancer->avatar_color = 'd81b60'; // Pink
            $dancer->avatar_bg_class = 'bg-pink-600';
        }

        return $dancers;
    }

    /**
     * Get officials by team_id from official_list table
     * DIPERBAIKI: Mengikuti logika review-data.blade.php untuk pencarian file
     */
    private function getOfficialsByTeamId($teamId)
    {
        $officials = OfficialList::where('team_id', $teamId)->get();

        // Format foto path
        foreach ($officials as $official) {
            // Gunakan fungsi lengkap untuk pencarian foto formal
            $official->formal_photo_url = $this->getFormalPhotoUrl($official->formal_photo, 'official', $official->team_id);
            
            // Tambahkan flag untuk cek apakah foto benar-benar ada
            $official->has_formal_photo = !empty($official->formal_photo_url);
            
            // Format data tambahan untuk tampilan
            $official->formatted_role = $official->role ? ucfirst(str_replace('_', ' ', $official->role)) : null;
            $official->formatted_team_role = $official->team_role ? ucfirst(str_replace('_', ' ', $official->team_role)) : null;
            $official->formatted_gender = $official->gender ?? 'Official';
            $official->avatar_color = 'ed6c02'; // Orange
            $official->avatar_bg_class = 'bg-warning';
        }

        return $officials;
    }

    /**
     * ===============================================
     * FORMAL PHOTO URL - MENGIKUTI REVIEW-DATA.BLADE.PHP
     * ===============================================
     * Fungsi utama untuk mendapatkan URL foto formal
     * dengan multiple fallback paths seperti di review-data
     *
     * @param string|null $photo Nama file atau path foto
     * @param string $type Tipe record (player, dancer, official)
     * @param string|null $teamId Team ID untuk route fallback
     * @return string|null URL foto atau null jika tidak ditemukan
     */
    private function getFormalPhotoUrl($photo, $type, $teamId = null)
    {
        if (empty($photo)) {
            return null;
        }

        try {
            $fileName = basename($photo);
            
            // ============ DEFINISI PATH BERDASARKAN TIPE ============
            $paths = $this->getPhotoPathsByType($type, $fileName);
            
            // Hapus duplikat
            $paths = array_unique($paths);
            
            // Cek setiap lokasi
            foreach ($paths as $path) {
                if (File::exists($path) && !is_dir($path)) {
                    return $this->convertPathToUrl($path) . '?v=' . time();
                }
            }
            
            // ============ FALLBACK: Route view document ============
            if (!empty($teamId)) {
                try {
                    $routeUrl = route('student.review.document.view', [
                        'teamId' => $teamId,
                        'documentType' => 'formal_photo'
                    ]);
                    return $routeUrl;
                } catch (\Exception $e) {
                    // Abaikan jika route tidak tersedia
                }
            }
            
            // ============ FALLBACK: Coba path langsung dari database ============
            if (strpos($photo, 'http') === 0) {
                return $photo;
            }
            
            if (File::exists(public_path($photo))) {
                return asset($photo) . '?v=' . time();
            }
            
            // Log error untuk debugging
            \Log::warning('FORMAL PHOTO TIDAK DITEMUKAN', [
                'type' => $type,
                'photo' => $photo,
                'filename' => $fileName,
                'paths_checked' => $paths,
                'team_id' => $teamId
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error getting formal photo URL: ' . $e->getMessage(), [
                'type' => $type,
                'photo' => $photo,
                'trace' => $e->getTraceAsString()
            ]);
        }

        return null;
    }

    /**
     * Mendapatkan daftar path yang mungkin untuk foto berdasarkan tipe
     *
     * @param string $type Tipe (player, dancer, official)
     * @param string $fileName Nama file
     * @return array Daftar path absolut
     */
    private function getPhotoPathsByType($type, $fileName)
    {
        $paths = [];
        
        switch ($type) {
            case 'player':
                // Standard paths
                $paths[] = storage_path('app/public/player_docs/' . $fileName);
                $paths[] = public_path('storage/player_docs/' . $fileName);
                $paths[] = public_path('player_docs/' . $fileName);
                
                // Legacy paths
                $paths[] = storage_path('app/player_docs/' . $fileName);
                $paths[] = base_path('player_docs/' . $fileName);
                
                // Path dari database
                $paths[] = public_path('uploads/player_docs/' . $fileName);
                $paths[] = storage_path('app/public/uploads/player_docs/' . $fileName);
                $paths[] = public_path('storage/uploads/player_docs/' . $fileName);
                break;
                
            case 'dancer':
                // Standard paths
                $paths[] = storage_path('app/public/dancer_docs/' . $fileName);
                $paths[] = public_path('storage/dancer_docs/' . $fileName);
                $paths[] = public_path('dancer_docs/' . $fileName);
                
                // Legacy paths
                $paths[] = storage_path('app/dancer_docs/' . $fileName);
                $paths[] = base_path('dancer_docs/' . $fileName);
                
                // Path dari database
                $paths[] = public_path('uploads/dancer_docs/' . $fileName);
                $paths[] = storage_path('app/public/uploads/dancer_docs/' . $fileName);
                $paths[] = public_path('storage/uploads/dancer_docs/' . $fileName);
                break;
                
            case 'official':
                // Official paths - formal_photos
                $paths[] = storage_path('app/public/uploads/officials/formal_photos/' . $fileName);
                $paths[] = public_path('storage/uploads/officials/formal_photos/' . $fileName);
                $paths[] = public_path('uploads/officials/formal_photos/' . $fileName);
                
                // Legacy paths
                $paths[] = storage_path('app/uploads/officials/formal_photos/' . $fileName);
                $paths[] = base_path('uploads/officials/formal_photos/' . $fileName);
                
                // Alternative official paths
                $paths[] = storage_path('app/public/officials/formal_photos/' . $fileName);
                $paths[] = public_path('storage/officials/formal_photos/' . $fileName);
                $paths[] = public_path('officials/formal_photos/' . $fileName);
                break;
        }
        
        // ============ TAMBAHKAN PATH DENGAN PREFIX LAIN ============
        // Coba dengan berbagai prefix
        $prefixes = [
            'public/',
            'uploads/',
            'storage/',
            'app/public/',
            'public/uploads/',
            'public/storage/'
        ];
        
        foreach ($prefixes as $prefix) {
            switch ($type) {
                case 'player':
                    $paths[] = base_path($prefix . 'player_docs/' . $fileName);
                    $paths[] = public_path($prefix . 'player_docs/' . $fileName);
                    $paths[] = storage_path($prefix . 'player_docs/' . $fileName);
                    break;
                case 'dancer':
                    $paths[] = base_path($prefix . 'dancer_docs/' . $fileName);
                    $paths[] = public_path($prefix . 'dancer_docs/' . $fileName);
                    $paths[] = storage_path($prefix . 'dancer_docs/' . $fileName);
                    break;
                case 'official':
                    $paths[] = base_path($prefix . 'uploads/officials/formal_photos/' . $fileName);
                    $paths[] = public_path($prefix . 'uploads/officials/formal_photos/' . $fileName);
                    $paths[] = storage_path($prefix . 'uploads/officials/formal_photos/' . $fileName);
                    break;
            }
        }
        
        return $paths;
    }

    /**
     * Convert absolute path ke URL
     *
     * @param string $path Absolute path
     * @return string URL
     */
    private function convertPathToUrl($path)
    {
        // Jika path di dalam public directory
        if (strpos($path, public_path()) === 0) {
            $relativePath = str_replace(public_path(), '', $path);
            return asset(ltrim($relativePath, '\\/'));
        }
        
        // Jika path di dalam storage/app/public
        if (strpos($path, storage_path('app/public')) === 0) {
            $relativePath = str_replace(storage_path('app/public'), '', $path);
            return asset('storage' . str_replace('\\', '/', $relativePath));
        }
        
        // Jika path di dalam storage/app
        if (strpos($path, storage_path('app')) === 0) {
            $relativePath = str_replace(storage_path('app'), '', $path);
            // Coba cek apakah ada symlink
            if (file_exists(public_path('storage' . $relativePath))) {
                return asset('storage' . str_replace('\\', '/', $relativePath));
            }
        }
        
        // Fallback
        return asset($path);
    }

    /**
     * Get avatar background color hex based on category
     */
    private function getAvatarColor($category)
    {
        $cat = strtolower($category ?? '');
        if ($cat === 'putra' || $cat === 'male' || $cat === 'laki-laki') {
            return '2e7d32'; // Hijau
        } elseif ($cat === 'putri' || $cat === 'female' || $cat === 'perempuan') {
            return '0288d1'; // Biru
        }
        return '1565c0'; // Biru tua (default)
    }

    /**
     * Get avatar background class based on category
     */
    private function getAvatarBgClass($category)
    {
        $cat = strtolower($category ?? '');
        if ($cat === 'putra' || $cat === 'male' || $cat === 'laki-laki') {
            return 'bg-success';
        } elseif ($cat === 'putri' || $cat === 'female' || $cat === 'perempuan') {
            return 'bg-info';
        }
        return 'bg-primary';
    }

    /**
     * Get team logo URL from team_list - format seperti schooldata-edit.blade.php
     */
    private function getTeamLogo($team)
    {
        if (!$team) return null;
        
        // Cek di kolom school_logo
        if (!empty($team->school_logo)) {
            $logoFile = basename($team->school_logo);
            
            // Priority 1: public/storage/school_logos/
            if (file_exists(public_path('storage/school_logos/' . $logoFile))) {
                return asset('storage/school_logos/' . $logoFile) . '?v=' . time();
            }
            
            // Priority 2: storage/school_logos (via Storage facade)
            if (Storage::disk('public')->exists('school_logos/' . $logoFile)) {
                return Storage::url('school_logos/' . $logoFile) . '?v=' . time();
            }
            
            // Priority 3: public/school_logos/
            if (file_exists(public_path('school_logos/' . $logoFile))) {
                return asset('school_logos/' . $logoFile) . '?v=' . time();
            }
            
            // Priority 4: public/uploads/school_logos/
            if (file_exists(public_path('uploads/school_logos/' . $logoFile))) {
                return asset('uploads/school_logos/' . $logoFile) . '?v=' . time();
            }
        }
        
        // Fallback ke logo_url jika ada
        if (isset($team->logo_url) && $team->logo_url) {
            if (filter_var($team->logo_url, FILTER_VALIDATE_URL)) {
                return $team->logo_url;
            }
            
            // Cek apakah file ada
            if (file_exists(public_path($team->logo_url))) {
                return asset($team->logo_url) . '?v=' . time();
            }
            
            return $team->logo_url;
        }
        
        return null;
    }

    /**
     * API endpoint untuk mendapatkan data players dengan filter category (AJAX)
     */
    public function getPlayers(Request $request, $teamId)
    {
        $category = $request->get('category'); // Putra, Putri, atau null
        
        $query = PlayerList::where('team_id', $teamId);
        
        if ($category && in_array($category, ['Putra', 'Putri'])) {
            $query->where('category', $category);
        }
        
        $players = $query->get();
        
        foreach ($players as $player) {
            // Gunakan fungsi utama untuk foto formal
            $player->formal_photo_url = $this->getFormalPhotoUrl($player->formal_photo, 'player', $player->team_id);
            $player->has_formal_photo = !empty($player->formal_photo_url);
            $player->formatted_role = $player->role ? ucfirst(str_replace('_', ' ', $player->role)) : null;
            $player->jersey_display = $player->jersey_number ?? '00';
            $player->avatar_color = $this->getAvatarColor($player->category);
            $player->avatar_bg_class = $this->getAvatarBgClass($player->category);
        }
        
        return response()->json([
            'success' => true,
            'data' => $players,
            'total' => $players->count(),
            'category' => $category
        ]);
    }

    /**
     * API endpoint untuk mendapatkan data dancers (AJAX)
     */
    public function getDancers($teamId)
    {
        $dancers = DancerList::where('team_id', $teamId)->get();
        
        foreach ($dancers as $dancer) {
            // Gunakan fungsi utama untuk foto formal
            $dancer->formal_photo_url = $this->getFormalPhotoUrl($dancer->formal_photo, 'dancer', $dancer->team_id);
            $dancer->has_formal_photo = !empty($dancer->formal_photo_url);
            $dancer->formatted_role = $dancer->role ? ucfirst(str_replace('_', ' ', $dancer->role)) : null;
            $dancer->avatar_color = 'd81b60';
            $dancer->avatar_bg_class = 'bg-pink-600';
        }
        
        return response()->json([
            'success' => true,
            'data' => $dancers,
            'total' => $dancers->count()
        ]);
    }

    /**
     * API endpoint untuk mendapatkan data officials (AJAX)
     */
    public function getOfficials($teamId)
    {
        $officials = OfficialList::where('team_id', $teamId)->get();
        
        foreach ($officials as $official) {
            // Gunakan fungsi utama untuk foto formal
            $official->formal_photo_url = $this->getFormalPhotoUrl($official->formal_photo, 'official', $official->team_id);
            $official->has_formal_photo = !empty($official->formal_photo_url);
            $official->formatted_role = $official->role ? ucfirst(str_replace('_', ' ', $official->role)) : null;
            $official->formatted_team_role = $official->team_role ? ucfirst(str_replace('_', ' ', $official->team_role)) : null;
            $official->avatar_color = 'ed6c02';
            $official->avatar_bg_class = 'bg-warning';
        }
        
        return response()->json([
            'success' => true,
            'data' => $officials,
            'total' => $officials->count()
        ]);
    }

    /**
     * Search team members
     */
    public function searchMembers(Request $request, $teamId)
    {
        $keyword = $request->get('keyword');
        $type = $request->get('type', 'all'); // all, players, dancers, officials
        
        $results = [];
        
        if ($type == 'all' || $type == 'players') {
            $players = PlayerList::where('team_id', $teamId)
                ->where(function($q) use ($keyword) {
                    $q->where('name', 'LIKE', "%{$keyword}%")
                      ->orWhere('email', 'LIKE', "%{$keyword}%")
                      ->orWhere('phone', 'LIKE', "%{$keyword}%")
                      ->orWhere('instagram', 'LIKE', "%{$keyword}%")
                      ->orWhere('tiktok', 'LIKE', "%{$keyword}%")
                      ->orWhere('jersey_number', 'LIKE', "%{$keyword}%")
                      ->orWhere('basketball_position', 'LIKE', "%{$keyword}%");
                })
                ->get();
                
            foreach ($players as $player) {
                $player->formal_photo_url = $this->getFormalPhotoUrl($player->formal_photo, 'player', $player->team_id);
                $player->has_formal_photo = !empty($player->formal_photo_url);
                $player->formatted_role = $player->role ? ucfirst(str_replace('_', ' ', $player->role)) : null;
                $player->jersey_display = $player->jersey_number ?? '00';
                $player->avatar_color = $this->getAvatarColor($player->category);
                $player->avatar_bg_class = $this->getAvatarBgClass($player->category);
            }
            
            $results['players'] = $players;
        }
        
        if ($type == 'all' || $type == 'dancers') {
            $dancers = DancerList::where('team_id', $teamId)
                ->where(function($q) use ($keyword) {
                    $q->where('name', 'LIKE', "%{$keyword}%")
                      ->orWhere('email', 'LIKE', "%{$keyword}%")
                      ->orWhere('phone', 'LIKE', "%{$keyword}%")
                      ->orWhere('instagram', 'LIKE', "%{$keyword}%")
                      ->orWhere('tiktok', 'LIKE', "%{$keyword}%");
                })
                ->get();
                
            foreach ($dancers as $dancer) {
                $dancer->formal_photo_url = $this->getFormalPhotoUrl($dancer->formal_photo, 'dancer', $dancer->team_id);
                $dancer->has_formal_photo = !empty($dancer->formal_photo_url);
                $dancer->formatted_role = $dancer->role ? ucfirst(str_replace('_', ' ', $dancer->role)) : null;
                $dancer->avatar_color = 'd81b60';
                $dancer->avatar_bg_class = 'bg-pink-600';
            }
            
            $results['dancers'] = $dancers;
        }
        
        if ($type == 'all' || $type == 'officials') {
            $officials = OfficialList::where('team_id', $teamId)
                ->where(function($q) use ($keyword) {
                    $q->where('name', 'LIKE', "%{$keyword}%")
                      ->orWhere('email', 'LIKE', "%{$keyword}%")
                      ->orWhere('phone', 'LIKE', "%{$keyword}%")
                      ->orWhere('instagram', 'LIKE', "%{$keyword}%")
                      ->orWhere('tiktok', 'LIKE', "%{$keyword}%");
                })
                ->get();
                
            foreach ($officials as $official) {
                $official->formal_photo_url = $this->getFormalPhotoUrl($official->formal_photo, 'official', $official->team_id);
                $official->has_formal_photo = !empty($official->formal_photo_url);
                $official->formatted_role = $official->role ? ucfirst(str_replace('_', ' ', $official->role)) : null;
                $official->formatted_team_role = $official->team_role ? ucfirst(str_replace('_', ' ', $official->team_role)) : null;
                $official->avatar_color = 'ed6c02';
                $official->avatar_bg_class = 'bg-warning';
            }
            
            $results['officials'] = $officials;
        }
        
        return response()->json([
            'success' => true,
            'data' => $results,
            'keyword' => $keyword
        ]);
    }

    /**
     * Get member detail
     */
    public function getMemberDetail(Request $request)
    {
        $type = $request->get('type'); // player, dancer, official
        $id = $request->get('id');
        
        if (!$type || !$id) {
            return response()->json(['error' => 'Parameter tidak lengkap'], 400);
        }
        
        $member = null;
        
        switch ($type) {
            case 'player':
                $member = PlayerList::find($id);
                if ($member) {
                    $member->formal_photo_url = $this->getFormalPhotoUrl($member->formal_photo, 'player', $member->team_id);
                    $member->has_formal_photo = !empty($member->formal_photo_url);
                    $member->formatted_role = $member->role ? ucfirst(str_replace('_', ' ', $member->role)) : null;
                    $member->jersey_display = $member->jersey_number ?? '00';
                    $member->avatar_color = $this->getAvatarColor($member->category);
                    $member->avatar_bg_class = $this->getAvatarBgClass($member->category);
                }
                break;
            case 'dancer':
                $member = DancerList::find($id);
                if ($member) {
                    $member->formal_photo_url = $this->getFormalPhotoUrl($member->formal_photo, 'dancer', $member->team_id);
                    $member->has_formal_photo = !empty($member->formal_photo_url);
                    $member->formatted_role = $member->role ? ucfirst(str_replace('_', ' ', $member->role)) : null;
                    $member->avatar_color = 'd81b60';
                    $member->avatar_bg_class = 'bg-pink-600';
                }
                break;
            case 'official':
                $member = OfficialList::find($id);
                if ($member) {
                    $member->formal_photo_url = $this->getFormalPhotoUrl($member->formal_photo, 'official', $member->team_id);
                    $member->has_formal_photo = !empty($member->formal_photo_url);
                    $member->formatted_role = $member->role ? ucfirst(str_replace('_', ' ', $member->role)) : null;
                    $member->formatted_team_role = $member->team_role ? ucfirst(str_replace('_', ' ', $member->team_role)) : null;
                    $member->avatar_color = 'ed6c02';
                    $member->avatar_bg_class = 'bg-warning';
                }
                break;
            default:
                return response()->json(['error' => 'Tipe member tidak valid'], 400);
        }
        
        if (!$member) {
            return response()->json(['error' => 'Member tidak ditemukan'], 404);
        }
        
        return response()->json([
            'success' => true,
            'data' => $member
        ]);
    }

    /**
     * Get team statistics - diperbarui dengan split Putra/Putri
     */
    public function getTeamStatistics($teamId)
    {
        $totalPlayers = PlayerList::where('team_id', $teamId)->count();
        $totalPlayersPutra = PlayerList::where('team_id', $teamId)
            ->where('category', 'Putra')
            ->count();
        $totalPlayersPutri = PlayerList::where('team_id', $teamId)
            ->where('category', 'Putri')
            ->count();
        $totalDancers = DancerList::where('team_id', $teamId)->count();
        $totalOfficials = OfficialList::where('team_id', $teamId)->count();
        
        // Jika tidak ada data dengan category 'Putra/Putri', coba dengan lowercase
        if ($totalPlayersPutra == 0 && $totalPlayersPutri == 0) {
            $totalPlayersPutra = PlayerList::where('team_id', $teamId)
                ->where(function($q) {
                    $q->whereRaw('LOWER(category) = ?', ['putra'])
                      ->orWhereRaw('LOWER(category) = ?', ['male'])
                      ->orWhereRaw('LOWER(category) = ?', ['laki-laki']);
                })
                ->count();
                
            $totalPlayersPutri = PlayerList::where('team_id', $teamId)
                ->where(function($q) {
                    $q->whereRaw('LOWER(category) = ?', ['putri'])
                      ->orWhereRaw('LOWER(category) = ?', ['female'])
                      ->orWhereRaw('LOWER(category) = ?', ['perempuan']);
                })
                ->count();
        }
        
        $statistics = [
            'total_members' => $totalPlayers + $totalDancers + $totalOfficials,
            'players' => $totalPlayers,
            'players_putra' => $totalPlayersPutra,
            'players_putri' => $totalPlayersPutri,
            'dancers' => $totalDancers,
            'officials' => $totalOfficials,
            'players_by_category' => $this->getPlayersByCategory($teamId),
            'dancers_by_gender' => $this->getDancersByGender($teamId),
            'officials_by_role' => $this->getOfficialsByRole($teamId),
        ];
        
        return response()->json([
            'success' => true,
            'data' => $statistics
        ]);
    }

    /**
     * Get players grouped by category
     */
    private function getPlayersByCategory($teamId)
    {
        return PlayerList::where('team_id', $teamId)
            ->select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();
    }

    /**
     * Get dancers grouped by gender
     */
    private function getDancersByGender($teamId)
    {
        return DancerList::where('team_id', $teamId)
            ->select('gender', DB::raw('count(*) as total'))
            ->groupBy('gender')
            ->get();
    }

    /**
     * Get officials grouped by role
     */
    private function getOfficialsByRole($teamId)
    {
        return OfficialList::where('team_id', $teamId)
            ->select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->get();
    }

    /**
     * ===============================================
     * VERIFY AND FIX PHOTO PATHS - DEBUGGING UTILITY
     * ===============================================
     * Utility method untuk debugging dan verifikasi path foto
     */
    public function verifyPhotoPaths($teamId)
    {
        $results = [
            'players' => [],
            'dancers' => [],
            'officials' => []
        ];
        
        // Cek players
        $players = PlayerList::where('team_id', $teamId)->get();
        foreach ($players as $player) {
            if (!empty($player->formal_photo)) {
                $filename = basename($player->formal_photo);
                
                $results['players'][] = [
                    'id' => $player->id,
                    'name' => $player->name,
                    'photo' => $player->formal_photo,
                    'filename' => $filename,
                    'url_from_method' => $this->getFormalPhotoUrl($player->formal_photo, 'player', $player->team_id),
                    'paths_checked' => $this->getPhotoPathsByType('player', $filename)
                ];
            }
        }
        
        // Cek dancers
        $dancers = DancerList::where('team_id', $teamId)->get();
        foreach ($dancers as $dancer) {
            if (!empty($dancer->formal_photo)) {
                $filename = basename($dancer->formal_photo);
                
                $results['dancers'][] = [
                    'id' => $dancer->id,
                    'name' => $dancer->name,
                    'photo' => $dancer->formal_photo,
                    'filename' => $filename,
                    'url_from_method' => $this->getFormalPhotoUrl($dancer->formal_photo, 'dancer', $dancer->team_id),
                    'paths_checked' => $this->getPhotoPathsByType('dancer', $filename)
                ];
            }
        }
        
        // Cek officials
        $officials = OfficialList::where('team_id', $teamId)->get();
        foreach ($officials as $official) {
            if (!empty($official->formal_photo)) {
                $filename = basename($official->formal_photo);
                
                $results['officials'][] = [
                    'id' => $official->id,
                    'name' => $official->name,
                    'photo' => $official->formal_photo,
                    'filename' => $filename,
                    'url_from_method' => $this->getFormalPhotoUrl($official->formal_photo, 'official', $official->team_id),
                    'paths_checked' => $this->getPhotoPathsByType('official', $filename)
                ];
            }
        }
        
        // Tambahkan info system
        $results['system'] = [
            'storage_link_exists' => file_exists(public_path('storage')),
            'storage_link_is_link' => is_link(public_path('storage')),
            'storage_link_target' => is_link(public_path('storage')) ? readlink(public_path('storage')) : null,
            'public_path' => public_path(),
            'storage_path_app_public' => storage_path('app/public'),
            'review_data_controller' => 'ReviewDataController.php uses multiple fallback paths',
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        return response()->json([
            'success' => true,
            'team_id' => $teamId,
            'data' => $results
        ]);
    }

    /**
     * ===============================================
     * FALLBACK: Mendapatkan foto via route review document
     * ===============================================
     * Menggunakan route yang sama dengan ReviewDataController
     */
    private function getPhotoViaRoute($teamId)
    {
        if (empty($teamId)) {
            return null;
        }
        
        try {
            $routeUrl = route('student.review.document.view', [
                'teamId' => $teamId,
                'documentType' => 'formal_photo'
            ]);
            return $routeUrl;
        } catch (\Exception $e) {
            return null;
        }
    }
}