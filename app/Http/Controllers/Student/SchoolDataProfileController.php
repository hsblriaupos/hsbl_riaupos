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
use Illuminate\Support\Str;

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

            // Get all schools where user is registered
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
                $school->registered_by = $school->registered_by ?? 'Self';
                
                return $school;
            });

            // Filter schools where user has at least one role
            $filteredSchools = $processedSchools->filter(function($school) {
                return $school->isPlayer || $school->isDancer || $school->isOfficial;
            });

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
     * Get user's roles for a specific school
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
            // 1. CHECK PLAYER ROLE - Use team_id
            $playerData = $playerLists->filter(function($player) use ($school, $user) {
                return $player->team_id == $school->team_id && 
                       ($player->email == $user->email || $player->nik == $user->nik);
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
            }

            // 2. CHECK DANCER ROLE - Use school_name
            $dancerData = $dancerLists->filter(function($dancer) use ($school, $user) {
                $dancerSchool = trim($dancer->school_name);
                $teamSchool = trim($school->school_name);
                $schoolMatch = strcasecmp($dancerSchool, $teamSchool) === 0;
                
                if (!$schoolMatch) {
                    $schoolMatch = str_contains(strtolower($dancerSchool), strtolower($teamSchool)) ||
                                   str_contains(strtolower($teamSchool), strtolower($dancerSchool));
                }
                
                return $schoolMatch && ($dancer->email == $user->email || $dancer->nik == $user->nik);
            })->first();

            if ($dancerData) {
                $roles['isDancer'] = true;
                $roles['dancerRole'] = $dancerData->role ? ucfirst($dancerData->role) : 'Dancer';
                $roles['dancerData'] = [
                    'dancer_id' => $dancerData->dancer_id,
                    'role' => $dancerData->role
                ];
            }

            // 3. CHECK OFFICIAL ROLE
            $officialData = $officialLists->filter(function($official) use ($school, $user) {
                $schoolMatch = $official->school_id == $school->school_id;
                
                if (!$schoolMatch && isset($official->team_id) && $official->team_id) {
                    $schoolMatch = $official->team_id == $school->team_id;
                }
                
                return $schoolMatch && ($official->email == $user->email || $official->nik == $user->nik);
            })->first();

            if ($officialData) {
                $roles['isOfficial'] = true;
                $role = $officialData->team_role ?? $officialData->role;
                $roles['officialRole'] = $role ? ucfirst($role) : 'Official';
                $roles['officialData'] = [
                    'official_id' => $officialData->official_id,
                    'team_role' => $officialData->team_role,
                    'role' => $officialData->role,
                    'team_id' => $officialData->team_id ?? null
                ];
            }

        } catch (\Exception $e) {
            Log::error('Error in getUserRolesForSchool: ' . $e->getMessage());
        }

        return $roles;
    }

    /**
     * Get all schools for a user based on their registrations
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

            $allSchools = collect();

            // 1. Get schools by team_id (from player_list)
            $teamIdsFromPlayers = $playerLists->pluck('team_id')->filter()->unique();
            if ($teamIdsFromPlayers->isNotEmpty()) {
                $schoolsByTeamId = TeamList::whereIn('team_id', $teamIdsFromPlayers)->get();
                $allSchools = $allSchools->merge($schoolsByTeamId);
            }

            // 2. Get schools by school_id (from official_list)
            $schoolIdsFromOfficials = $officialLists->pluck('school_id')->filter()->unique();
            if ($schoolIdsFromOfficials->isNotEmpty()) {
                $schoolsBySchoolId = TeamList::whereIn('school_id', $schoolIdsFromOfficials)->get();
                $allSchools = $allSchools->merge($schoolsBySchoolId);
            }

            // 3. Get schools by team_id from official_list
            $teamIdsFromOfficials = $officialLists->pluck('team_id')->filter()->unique();
            if ($teamIdsFromOfficials->isNotEmpty()) {
                $schoolsByTeamIdOfficial = TeamList::whereIn('team_id', $teamIdsFromOfficials)->get();
                $allSchools = $allSchools->merge($schoolsByTeamIdOfficial);
            }

            // 4. Get schools by school_name (from dancer_list)
            $schoolNamesFromDancers = $dancerLists->pluck('school_name')->filter()->unique();
            if ($schoolNamesFromDancers->isNotEmpty()) {
                foreach ($schoolNamesFromDancers as $schoolName) {
                    $matchingSchools = TeamList::whereRaw('LOWER(school_name) LIKE ?', 
                        ['%' . strtolower($schoolName) . '%'])->get();
                    $allSchools = $allSchools->merge($matchingSchools);
                }
            }

            // Remove duplicates by team_id
            $allSchools = $allSchools->unique('team_id');

            return $allSchools;

        } catch (\Exception $e) {
            Log::error('Error in getAllSchoolsForUser: ' . $e->getMessage());
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
        
        if (strpos($schoolLogo, 'http') === 0) {
            return $schoolLogo;
        }

        if (Storage::disk('public')->exists('school_logos/' . $logoFile)) {
            return Storage::disk('public')->url('school_logos/' . $logoFile);
        }

        if (file_exists(public_path('storage/school_logos/' . $logoFile))) {
            return asset('storage/school_logos/' . $logoFile);
        }

        if (file_exists(public_path('school_logos/' . $logoFile))) {
            return asset('school_logos/' . $logoFile);
        }

        return null;
    }

    /**
     * Get document URL from storage with FORCE cache busting
     */
    private function getDocumentUrl($path, $disk = 'public', $directory = 'team_docs')
    {
        if (!$path) {
            return null;
        }

        $fileName = basename($path);
        $url = null;
        
        // Clear file status cache
        clearstatcache();
        
        // Check in specified directory
        if (Storage::disk($disk)->exists($directory . '/' . $fileName)) {
            $url = Storage::disk($disk)->url($directory . '/' . $fileName);
        }
        
        // Check in public path
        elseif (file_exists(public_path($directory . '/' . $fileName))) {
            $url = asset($directory . '/' . $fileName);
        }
        
        // Check in storage
        elseif (file_exists(public_path('storage/' . $directory . '/' . $fileName))) {
            $url = asset('storage/' . $directory . '/' . $fileName);
        }
        
        // FORCE cache busting - selalu tambahkan timestamp
        if ($url) {
            // Gunakan file modification time jika ada
            $filePath = public_path('storage/' . $directory . '/' . $fileName);
            if (file_exists($filePath)) {
                $timestamp = filemtime($filePath);
            } else {
                $timestamp = time();
            }
            $url .= '?v=' . $timestamp;
        }
        
        return $url;
    }

    /**
     * Show the form for editing school data - PERBAIKAN DENGAN CACHE BUSTING
     */
    public function edit($team_id = null)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login first.');
            }

            // Jika tidak ada team_id, coba ambil dari route parameter
            if (!$team_id) {
                $team_id = request()->route('team_id') ?? request()->get('team_id') ?? request()->get('school_id');
            }

            // Cari data team berdasarkan team_id (prioritas) atau school_id
            $team = null;
            
            if ($team_id) {
                // Cari berdasarkan team_id
                $team = TeamList::where('team_id', $team_id)->first();
                
                // Jika tidak ditemukan, cari berdasarkan school_id
                if (!$team) {
                    $team = TeamList::where('school_id', $team_id)->first();
                }
            }

            // Jika data tidak ditemukan
            if (!$team) {
                Log::warning("Team not found for ID: {$team_id}");
                return redirect()->route('student.event.histories')
                    ->with('error', 'School data not found. Please check your registration.');
            }

            // Verifikasi bahwa user memiliki akses ke sekolah ini
            if (!$this->hasAccessToSchool($user, $team->school_id, $team->school_name, $team->team_id)) {
                Log::warning("User {$user->email} attempted to access unauthorized school: {$team->school_name}");
                return redirect()->route('student.event.histories')
                    ->with('error', 'You do not have permission to access this school data.');
            }

            // Refresh data team untuk memastikan data terbaru
            $team->refresh();
            
            // Clear cache
            clearstatcache();

            // Get user's roles for this school
            $playerLists = PlayerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->where('team_id', $team->team_id)->get();

            $dancerLists = DancerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->whereRaw('LOWER(school_name) = ?', [strtolower($team->school_name)])->get();

            $officialLists = OfficialList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->where(function($query) use ($team) {
                $query->where('school_id', $team->school_id)
                      ->orWhere('team_id', $team->team_id);
            })->get();

            // Generate document URLs dengan cache busting
            $team->recommendation_letter_url = $this->getDocumentUrl($team->recommendation_letter, 'public', 'team_docs');
            $team->koran_url = $this->getDocumentUrl($team->koran, 'public', 'team_docs');
            $team->payment_proof_url = $this->getDocumentUrl($team->payment_proof, 'public', 'payment_proofs');
            $team->logo_url = $this->getSchoolLogoUrl($team->school_logo);

            // Dapatkan ukuran file untuk ditampilkan di view
            $team->koran_size = $this->getFileSize($team->koran, 'team_docs');
            $team->recommendation_letter_size = $this->getFileSize($team->recommendation_letter, 'team_docs');
            $team->payment_proof_size = $this->getFileSize($team->payment_proof, 'payment_proofs');

            return view('user.event.profile.schooldata-edit', [
                'team' => $team,
                'team_id' => $team->team_id,
                'school_id' => $team->school_id,
                'userRoles' => [
                    'isPlayer' => $playerLists->isNotEmpty(),
                    'isDancer' => $dancerLists->isNotEmpty(),
                    'isOfficial' => $officialLists->isNotEmpty(),
                    'playerData' => $playerLists->first(),
                    'dancerData' => $dancerLists->first(),
                    'officialData' => $officialLists->first()
                ],
                'koran_updated' => session('koran_updated', false),
                'timestamp' => time()
            ]);

        } catch (\Exception $e) {
            Log::error('Error in SchoolDataProfileController@edit: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return redirect()->route('student.event.histories')
                ->with('error', 'Terjadi kesalahan saat memuat data sekolah. Error: ' . $e->getMessage());
        }
    }

    /**
     * 🔥 PERBAIKAN TOTAL: DOWNLOAD DOCUMENT - Menggunakan pendekatan yang berbeda
     * Menggunakan ID sebagai parameter, bukan filename, untuk menghindari masalah encoding dan CORS
     */
    public function downloadDocument($teamId, $documentType)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login first.');
            }

            Log::info('=== DOWNLOAD DOCUMENT STARTED ===');
            Log::info('User: ' . $user->email);
            Log::info('Team ID: ' . $teamId);
            Log::info('Document Type: ' . $documentType);

            // Validasi document type
            $validTypes = ['koran', 'recommendation_letter', 'payment_proof'];
            if (!in_array($documentType, $validTypes)) {
                Log::error('Invalid document type: ' . $documentType);
                return redirect()->back()->with('error', 'Invalid document type.');
            }

            // Cari data team
            $team = TeamList::where('team_id', $teamId)->first();
            
            if (!$team) {
                Log::error("Team not found for ID: {$teamId}");
                return redirect()->back()->with('error', 'Team not found.');
            }

            // Verifikasi akses user
            if (!$this->hasAccessToSchool($user, $team->school_id, $team->school_name, $team->team_id)) {
                Log::warning("User {$user->email} attempted to download unauthorized document from team: {$team->team_id}");
                return redirect()->back()->with('error', 'You do not have permission to download this document.');
            }

            // Dapatkan path file dari database
            $filePath = $team->{$documentType};
            
            if (empty($filePath)) {
                Log::error("No {$documentType} found for team: {$teamId}");
                return redirect()->back()->with('error', 'Document not found.');
            }

            $fileName = basename($filePath);
            $directory = 'team_docs';
            
            if ($documentType === 'payment_proof') {
                $directory = 'payment_proofs';
            }

            // Tentukan path lengkap file
            $fullPath = null;
            
            // Cek di storage
            if (Storage::disk('public')->exists($directory . '/' . $fileName)) {
                $fullPath = Storage::disk('public')->path($directory . '/' . $fileName);
                Log::info("Found in storage: " . $directory . '/' . $fileName);
            }
            // Cek di public path
            elseif (file_exists(public_path('storage/' . $directory . '/' . $fileName))) {
                $fullPath = public_path('storage/' . $directory . '/' . $fileName);
                Log::info("Found in public: " . $fullPath);
            }
            // Cek di public path tanpa storage
            elseif (file_exists(public_path($directory . '/' . $fileName))) {
                $fullPath = public_path($directory . '/' . $fileName);
                Log::info("Found in public root: " . $fullPath);
            }

            if (!$fullPath || !file_exists($fullPath)) {
                Log::error("File not found on disk: " . $filePath);
                return redirect()->back()->with('error', 'File tidak ditemukan di server.');
            }

            // Dapatkan mime type
            $mimeType = $this->getMimeTypeFromExtension($fileName);
            
            // Dapatkan nama file untuk download (format: JenisDocument_NamaSekolah_Timestamp.ext)
            $schoolName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $team->school_name);
            $documentLabels = [
                'koran' => 'Koran',
                'recommendation_letter' => 'Rekomendasi',
                'payment_proof' => 'Bukti_Pembayaran'
            ];
            $label = $documentLabels[$documentType] ?? ucfirst($documentType);
            
            $downloadName = $label . '_' . $schoolName . '_' . date('Ymd') . '.' . pathinfo($fileName, PATHINFO_EXTENSION);

            // FORCE DOWNLOAD dengan headers yang tepat
            $headers = [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'attachment; filename="' . $downloadName . '"',
                'Content-Transfer-Encoding' => 'binary',
                'Content-Length' => filesize($fullPath),
                'Cache-Control' => 'no-cache, no-store, must-revalidate, pre-check=0, post-check=0, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'X-Content-Type-Options' => 'nosniff',
            ];

            Log::info('Download successful: ' . $downloadName);
            Log::info('File path: ' . $fullPath);
            Log::info('File size: ' . filesize($fullPath));
            Log::info('Mime type: ' . $mimeType);

            return response()->download($fullPath, $downloadName, $headers);

        } catch (\Exception $e) {
            Log::error('Error in downloadDocument: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return redirect()->back()->with('error', 'Gagal mendownload file. Error: ' . $e->getMessage());
        }
    }

    /**
     * 🔥 PERBAIKAN: Get file size for display
     */
    private function getFileSize($path, $directory = 'team_docs')
    {
        if (!$path) {
            return null;
        }

        $fileName = basename($path);
        
        // Cek di storage
        if (Storage::disk('public')->exists($directory . '/' . $fileName)) {
            $bytes = Storage::disk('public')->size($directory . '/' . $fileName);
            return $this->formatBytes($bytes);
        }
        
        // Cek di public path
        $filePath = public_path('storage/' . $directory . '/' . $fileName);
        if (file_exists($filePath)) {
            $bytes = filesize($filePath);
            return $this->formatBytes($bytes);
        }
        
        return null;
    }

    /**
     * 🔥 PERBAIKAN: Format bytes to human readable
     */
    private function formatBytes($bytes, $precision = 2)
    {
        if ($bytes === null || $bytes === 0) {
            return '0 B';
        }
        
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * 🔥 PERBAIKAN: Get mime type from file extension
     */
    private function getMimeTypeFromExtension($filename)
    {
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'txt' => 'text/plain',
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
        ];
        
        return $mimeTypes[$extension] ?? 'application/octet-stream';
    }

    /**
     * PERBAIKAN: Update Koran document only - DENGAN REDIRECT YANG BENAR
     */
    public function updateKoran(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return redirect()->route('login')->with('error', 'Please login first.');
            }

            Log::info('=== UPDATE KORAN STARTED ===');
            Log::info('User: ' . $user->email);
            Log::info('Team ID: ' . $request->team_id);
            Log::info('File exists: ' . ($request->hasFile('koran_file') ? 'YES' : 'NO'));

            // Validasi input
            $validated = $request->validate([
                'team_id' => 'required|string|exists:team_list,team_id',
                'koran_file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            ]);

            // Cari data team
            $team = TeamList::where('team_id', $validated['team_id'])->first();
            
            if (!$team) {
                return redirect()->back()->with('error', 'Team not found.');
            }

            // Cek apakah team locked
            if ($team->locked_status === 'locked') {
                return redirect()->back()->with('error', 'Cannot update documents. Team is locked.');
            }

            // Verifikasi akses user
            if (!$this->hasAccessToSchool($user, $team->school_id, $team->school_name, $team->team_id)) {
                return redirect()->route('student.event.histories')
                    ->with('error', 'You do not have permission to update this school data.');
            }

            DB::beginTransaction();

            try {
                // Handle file upload untuk koran
                if ($request->hasFile('koran_file')) {
                    $file = $request->file('koran_file');
                    
                    Log::info('Original filename: ' . $file->getClientOriginalName());
                    
                    // Generate unique filename dengan timestamp
                    $timestamp = time();
                    $extension = $file->getClientOriginalExtension();
                    $fileName = 'koran_' . $team->team_id . '_' . $timestamp . '.' . $extension;
                    
                    // Hapus file LAMA dengan PASTI
                    if ($team->koran) {
                        $oldFile = basename($team->koran);
                        
                        // Hapus dari storage
                        if (Storage::disk('public')->exists('team_docs/' . $oldFile)) {
                            Storage::disk('public')->delete('team_docs/' . $oldFile);
                            Log::info("Deleted old koran file: team_docs/" . $oldFile);
                        }
                        
                        // Hapus dari public path
                        $oldFilePath = public_path('storage/team_docs/' . $oldFile);
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                            Log::info("Deleted old koran file from public: " . $oldFilePath);
                        }
                        
                        // Clear cache file lama
                        clearstatcache();
                    }
                    
                    // Store file baru
                    $path = $file->storeAs('team_docs', $fileName, 'public');
                    
                    // Update database
                    $team->koran = 'team_docs/' . $fileName;
                    $team->save();
                    
                    // Refresh model untuk memastikan data terbaru
                    $team->refresh();
                    
                    Log::info("Koran document updated for team {$team->team_id}");
                    Log::info("New file: {$fileName}");
                    Log::info("New path in DB: {$team->koran}");
                }

                DB::commit();

                // 🔥 PERBAIKAN: SELALU REDIRECT KE student.team.edit DENGAN team_id
                $redirectUrl = route('student.team.edit', ['team_id' => $team->team_id]);
                Log::info('Redirecting to student.team.edit: ' . $redirectUrl);

                return redirect($redirectUrl)
                    ->with('success', 'Koran document has been successfully updated.')
                    ->with('koran_updated', true)
                    ->with('timestamp', $timestamp);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error updating koran: ' . $e->getMessage());
                Log::error('Trace: ' . $e->getTraceAsString());
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error in SchoolDataProfileController@updateKoran: ' . $e->getMessage());
            Log::error('Trace: ' . $e->getTraceAsString());
            
            return redirect()->back()->withInput()
                ->with('error', 'Gagal mengupdate dokumen. Error: ' . $e->getMessage());
        }
    }

    /**
     * Update school data - GENERAL UPDATE (untuk data lain, tidak digunakan untuk koran)
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
                return redirect()->route('student.event.histories')->with('error', 'You do not have access to this school.');
            }

            // Cari atau buat data team
            $team = TeamList::where('school_id', $validated['school_id'])->first();
            
            if (!$team) {
                // Buat baru jika tidak ditemukan
                $team = new TeamList();
                $team->school_id = $validated['school_id'];
                $team->registered_by = $user->name ?? $user->email;
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
                $logoName = time() . '_' . Str::slug($validated['school_name']) . '_logo.' . $logoFile->getClientOriginalExtension();
                $logoPath = $logoFile->storeAs('school_logos', $logoName, 'public');
                $team->school_logo = 'school_logos/' . $logoName;
            }

            $team->save();

            return redirect()->route('student.event.histories')->with('success', 'School data updated successfully.');

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

            $team = TeamList::where('school_id', $school_id)->first();
            
            if (!$team) {
                return redirect()->route('student.event.histories')->with('error', 'School not found.');
            }

            if (!$this->hasAccessToSchool($user, $school_id, $team->school_name, $team->team_id)) {
                return redirect()->route('student.event.histories')->with('error', 'You do not have access to this school.');
            }

            DB::beginTransaction();

            try {
                // Hapus dari player_list
                PlayerList::where(function($query) use ($user) {
                    $query->where('email', $user->email)
                          ->orWhere('nik', $user->nik);
                })->where('team_id', $team->team_id)->delete();

                // Hapus dari dancer_list
                DancerList::where(function($query) use ($user) {
                    $query->where('email', $user->email)
                          ->orWhere('nik', $user->nik);
                })->whereRaw('LOWER(school_name) = ?', [strtolower($team->school_name)])->delete();

                // Hapus dari official_list
                OfficialList::where(function($query) use ($user) {
                    $query->where('email', $user->email)
                          ->orWhere('nik', $user->nik);
                })->where(function($query) use ($team) {
                    $query->where('school_id', $team->school_id)
                          ->orWhere('team_id', $team->team_id);
                })->delete();

                DB::commit();

                return redirect()->route('student.event.histories')->with('success', 'Successfully left the school team.');

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Error in SchoolDataProfileController@leave: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat meninggalkan sekolah.');
        }
    }

    /**
     * Check if user has access to a school - PERBAIKAN dengan parameter team_id
     */
    private function hasAccessToSchool($user, $school_id, $school_name, $team_id = null)
    {
        try {
            // Cari team berdasarkan school_id jika team_id tidak diberikan
            if (!$team_id) {
                $team = TeamList::where('school_id', $school_id)->first();
                $team_id = $team->team_id ?? null;
            }

            // Cek di player_list dengan team_id
            $isPlayer = PlayerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->where('team_id', $team_id)->exists();

            // Cek di dancer_list dengan school_name (case-insensitive)
            $isDancer = DancerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->whereRaw('LOWER(school_name) = ?', [strtolower($school_name)])->exists();

            // Cek di official_list dengan school_id atau team_id
            $isOfficial = OfficialList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->where(function($query) use ($school_id, $team_id) {
                $query->where('school_id', $school_id);
                if ($team_id) {
                    $query->orWhere('team_id', $team_id);
                }
            })->exists();

            Log::info("Access check for school {$school_name} (ID: {$school_id}, Team ID: {$team_id}):", [
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
     * Remove user from all roles in a school
     */
    private function removeUserFromSchool($user, $team)
    {
        try {
            DB::beginTransaction();

            // Hapus dari player_list dengan team_id
            PlayerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->where('team_id', $team->team_id)->delete();

            // Hapus dari dancer_list dengan school_name (case-insensitive)
            DancerList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->whereRaw('LOWER(school_name) = ?', [strtolower($team->school_name)])->delete();

            // Hapus dari official_list dengan school_id dan team_id
            OfficialList::where(function($query) use ($user) {
                $query->where('email', $user->email)
                      ->orWhere('nik', $user->nik);
            })->where(function($query) use ($team) {
                $query->where('school_id', $team->school_id)
                      ->orWhere('team_id', $team->team_id);
            })->delete();

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

            return response()->json([
                'user' => [
                    'email' => $user->email,
                    'nik' => $user->nik,
                    'name' => $user->name
                ],
                'player_count' => $playerData->count(),
                'player_data' => $playerData,
                'dancer_count' => $dancerData->count(),
                'dancer_data' => $dancerData,
                'official_count' => $officialData->count(),
                'official_data' => $officialData
            ]);

        } catch (\Exception $e) {
            Log::error('Error in debugUserData: ' . $e->getMessage());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    /**
     * Test roles for a specific school
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
                'school' => $school,
                'roles' => $roles
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
            $playerColumns = \Schema::getColumnListing('player_list');
            $dancerColumns = \Schema::getColumnListing('dancer_list');
            $officialColumns = \Schema::getColumnListing('official_list');
            $teamColumns = \Schema::getColumnListing('team_list');

            return response()->json([
                'player_list_columns' => $playerColumns,
                'dancer_list_columns' => $dancerColumns,
                'official_list_columns' => $officialColumns,
                'team_list_columns' => $teamColumns,
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}