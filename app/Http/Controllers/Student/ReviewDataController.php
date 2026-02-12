<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\TeamList;
use Carbon\Carbon;

class ReviewDataController extends Controller
{
    /**
     * Menampilkan halaman review data kelengkapan student
     * Mengambil SEMUA kolom dari tabel player_list, dancer_list, official_list
     * berdasarkan kesamaan nama dengan users.name
     */
    public function index()
    {
        $user = Auth::user();
        $userName = $user->name;
        
        // Ambil SEMUA data dari ketiga tabel berdasarkan name yang sama dengan users.name
        // Menggunakan DB::table agar bisa mengambil semua kolom tanpa harus mendefinisikan model
        $playerData = DB::table('player_list')->where('name', $userName)->first();
        $dancerData = DB::table('dancer_list')->where('name', $userName)->first();
        $officialData = DB::table('official_list')->where('name', $userName)->first();
        
        // Tentukan data aktif (prioritas: player > dancer > official)
        $activeData = null;
        $activeTable = null;
        
        if ($playerData) {
            $activeData = $playerData;
            $activeTable = 'player';
        } elseif ($dancerData) {
            $activeData = $dancerData;
            $activeTable = 'dancer';
        } elseif ($officialData) {
            $activeData = $officialData;
            $activeTable = 'official';
        }
        
        // Ambil semua team yang diikuti user berdasarkan team_id dari ketiga tabel
        $teams = $this->getUserTeams($userName);
        
        // Ambil role per team untuk ditampilkan di blade
        $teamRoles = [];
        foreach ($teams as $team) {
            $teamRoles[$team->team_id] = $this->getTeamRoles($userName, $team->team_id);
        }
        
        // Hitung statistik kelengkapan
        $stats = $this->calculateStatistics($user, $playerData, $dancerData, $officialData, $teams);
        
        // Cek requirements yang belum dipenuhi
        $missingRequirements = $this->getMissingRequirements($user, $playerData, $dancerData, $officialData, $teams);
        
        // Kirim SEMUA data ke view
        return view('user.event.profile.review-data', [
            // User data
            'user' => $user,
            
            // Complete data from all tables - SEMUA KOLOM
            'playerData' => $playerData,      // Semua kolom dari player_list
            'dancerData' => $dancerData,      // Semua kolom dari dancer_list
            'officialData' => $officialData,  // Semua kolom dari official_list
            
            // Active data info
            'activeData' => $activeData,
            'activeTable' => $activeTable,
            
            // Teams and roles
            'teams' => $teams,
            'teamRoles' => $teamRoles,
            
            // Statistics
            'stats' => $stats,
            'missingRequirements' => $missingRequirements,
            
            // Counts for stats cards
            'totalSchools' => count($teams),
            'playerCount' => $playerData ? 1 : 0,
            'dancerCount' => $dancerData ? 1 : 0,
            'officialCount' => $officialData ? 1 : 0,
        ]);
    }
    
    /**
     * Menampilkan halaman checklist kelengkapan data
     */
    public function checklist()
    {
        $user = Auth::user();
        $userName = $user->name;
        
        // Ambil data dari ketiga tabel
        $playerData = DB::table('player_list')->where('name', $userName)->first();
        $dancerData = DB::table('dancer_list')->where('name', $userName)->first();
        $officialData = DB::table('official_list')->where('name', $userName)->first();
        
        // Profile checklist dari tabel users
        $profileChecklist = [
            'name' => !empty($user->name),
            'email' => !empty($user->email),
            'nik' => !empty($user->nik),
            'phone' => !empty($user->no_hp) || !empty($user->phone),
            'birth_date' => !empty($user->birth_date) || !empty($user->birthdate),
            'gender' => !empty($user->jenis_kelamin) || !empty($user->gender),
            'address' => !empty($user->address),
            'avatar' => !empty($user->avatar),
            'email_verified' => !empty($user->email_verified_at),
        ];
        
        // Role checklist
        $roleChecklist = [
            'has_player' => !is_null($playerData),
            'has_dancer' => !is_null($dancerData),
            'has_official' => !is_null($officialData),
        ];
        
        // Documents checklist berdasarkan data yang ada di masing-masing tabel
        $documentChecklist = $this->checkDocuments($userName, $playerData, $dancerData, $officialData);
        
        // Team checklist
        $teams = $this->getUserTeams($userName);
        $teamChecklist = [];
        
        foreach ($teams as $team) {
            $teamChecklist[$team->team_id] = [
                'team_name' => $team->school_name ?? $team->team_name ?? 'Team',
                'team_id' => $team->team_id,
                'verification_status' => $team->verification_status ?? 'pending',
                'payment_status' => $team->payment_status ?? 'unpaid',
                'player_complete' => $this->checkPlayerCompleteness($userName, $team->team_id),
                'dancer_complete' => $this->checkDancerCompleteness($userName, $team->team_id),
                'official_complete' => $this->checkOfficialCompleteness($userName, $team->team_id),
            ];
        }
        
        $checklist = [
            'profile' => $profileChecklist,
            'role' => $roleChecklist,
            'documents' => $documentChecklist,
            'teams' => $teamChecklist,
        ];
        
        $completeness = $this->calculateCompleteness($checklist);
        $completedItems = $this->countCompletedItems($checklist);
        $totalItems = $this->countTotalItems($checklist);
        
        return view('user.event.profile.review-checklist', compact(
            'user', 
            'playerData', 
            'dancerData', 
            'officialData',
            'checklist', 
            'completeness',
            'completedItems',
            'totalItems'
        ));
    }
    
    /**
     * Menampilkan halaman kelengkapan data per team
     */
    public function completeness()
    {
        $user = Auth::user();
        $userName = $user->name;
        
        // Ambil semua team
        $teams = $this->getUserTeams($userName);
        
        // Data kelengkapan per team
        $teamCompleteness = [];
        
        foreach ($teams as $team) {
            // Ambil data player di team ini
            $playerInTeam = DB::table('player_list')
                ->where('name', $userName)
                ->where('team_id', $team->team_id)
                ->first();
            
            // Ambil data dancer di team ini
            $dancerInTeam = DB::table('dancer_list')
                ->where('name', $userName)
                ->where('team_id', $team->team_id)
                ->first();
            
            // Ambil data official di team ini
            $officialInTeam = DB::table('official_list')
                ->where('name', $userName)
                ->where('team_id', $team->team_id)
                ->first();
            
            $teamCompleteness[$team->team_id] = [
                'team' => $team,
                'player' => $playerInTeam ? $this->checkPlayerCompleteness($userName, $team->team_id, true) : null,
                'dancer' => $dancerInTeam ? $this->checkDancerCompleteness($userName, $team->team_id, true) : null,
                'official' => $officialInTeam ? $this->checkOfficialCompleteness($userName, $team->team_id, true) : null,
                'payment' => $this->checkPaymentStatus($team->team_id),
                'verification' => $this->checkVerificationStatus($team->team_id),
                'team_leader' => $this->checkTeamLeader($team->team_id),
                'member_count' => $this->getTeamMemberCount($team->team_id),
            ];
        }
        
        // Statistik global
        $globalStats = [
            'total_teams' => count($teams),
            'teams_with_player' => DB::table('player_list')->where('name', $userName)->distinct('team_id')->count('team_id'),
            'teams_with_dancer' => DB::table('dancer_list')->where('name', $userName)->distinct('team_id')->count('team_id'),
            'teams_with_official' => DB::table('official_list')->where('name', $userName)->distinct('team_id')->count('team_id'),
        ];
        
        return view('user.event.profile.review-completeness', compact(
            'user', 
            'teams', 
            'teamCompleteness',
            'globalStats'
        ));
    }
    
    /**
     * Refresh data review
     */
    public function refresh(Request $request)
    {
        try {
            // Clear session cache terkait review data
            $keys = [
                'review_stats_' . Auth::id(),
                'review_checklist_' . Auth::id(),
                'review_teams_' . Auth::id(),
            ];
            
            foreach ($keys as $key) {
                if (session()->has($key)) {
                    session()->forget($key);
                }
            }
            
            Log::info('User ' . Auth::user()->email . ' melakukan refresh data review');
            
            return redirect()->back()->with('success', 'Data review berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error refresh review data: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui data. Silakan coba lagi.');
        }
    }
    
    /**
     * API endpoint untuk mendapatkan detail role user
     */
    public function getRoleDetails(Request $request, $role)
    {
        $user = Auth::user();
        $userName = $user->name;
        
        switch ($role) {
            case 'player':
                $data = DB::table('player_list')->where('name', $userName)->get();
                break;
            case 'dancer':
                $data = DB::table('dancer_list')->where('name', $userName)->get();
                break;
            case 'official':
                $data = DB::table('official_list')->where('name', $userName)->get();
                break;
            default:
                return response()->json(['error' => 'Invalid role'], 400);
        }
        
        return response()->json($data);
    }
    
    /**
     * ========== PRIVATE METHODS ==========
     */
    
    /**
     * Mendapatkan semua team yang diikuti user
     * Berdasarkan team_id dari player_list, dancer_list, official_list
     */
    private function getUserTeams($userName)
    {
        // Ambil team_id dari player_list
        $playerTeamIds = DB::table('player_list')
            ->where('name', $userName)
            ->pluck('team_id')
            ->toArray();
        
        // Ambil team_id dari dancer_list
        $dancerTeamIds = DB::table('dancer_list')
            ->where('name', $userName)
            ->pluck('team_id')
            ->toArray();
        
        // Ambil team_id dari official_list
        $officialTeamIds = DB::table('official_list')
            ->where('name', $userName)
            ->pluck('team_id')
            ->toArray();
        
        // Gabungkan semua team_id
        $allTeamIds = array_merge($playerTeamIds, $dancerTeamIds, $officialTeamIds);
        $allTeamIds = array_unique($allTeamIds);
        
        // Ambil detail team
        $teams = DB::table('team_list')->whereIn('team_id', $allTeamIds)->get();
        
        return $teams;
    }
    
    /**
     * Mendapatkan role user di team tertentu
     */
    private function getTeamRoles($userName, $teamId)
    {
        $roles = [];
        
        // Cek sebagai player
        $player = DB::table('player_list')
            ->where('name', $userName)
            ->where('team_id', $teamId)
            ->first();
            
        if ($player) {
            $roles[] = [
                'role' => 'player',
                'status' => $player->verification_status ?? 'pending',
                'data' => $player,
                'category' => $player->category ?? 'putra',
                'verified' => ($player->verification_status ?? 'pending') === 'verified',
                'verification_status' => $player->verification_status ?? 'pending',
            ];
        }
        
        // Cek sebagai dancer
        $dancer = DB::table('dancer_list')
            ->where('name', $userName)
            ->where('team_id', $teamId)
            ->first();
            
        if ($dancer) {
            $roles[] = [
                'role' => 'dancer',
                'status' => $dancer->verification_status ?? 'pending',
                'data' => $dancer,
                'category' => $dancer->role ?? 'umum',
                'verified' => ($dancer->verification_status ?? 'pending') === 'verified',
                'verification_status' => $dancer->verification_status ?? 'pending',
            ];
        }
        
        // Cek sebagai official
        $official = DB::table('official_list')
            ->where('name', $userName)
            ->where('team_id', $teamId)
            ->first();
            
        if ($official) {
            $roles[] = [
                'role' => 'official',
                'status' => $official->verification_status ?? 'pending',
                'data' => $official,
                'position' => $official->team_role ?? $official->role ?? '',
                'verified' => ($official->verification_status ?? 'pending') === 'verified',
                'verification_status' => $official->verification_status ?? 'pending',
            ];
        }
        
        return $roles;
    }
    
    /**
     * Hitung statistik kelengkapan
     */
    private function calculateStatistics($user, $playerData, $dancerData, $officialData, $teams)
    {
        // Kelengkapan profile user - berdasarkan tabel users
        $profileFields = ['nik', 'no_hp', 'birth_date', 'address', 'jenis_kelamin'];
        $profileComplete = 0;
        foreach ($profileFields as $field) {
            if (!empty($user->$field)) {
                $profileComplete++;
            }
        }
        
        // Kelengkapan data player - berdasarkan struktur kolom player_list
        $playerComplete = 0;
        $playerFields = ['nik', 'birthdate', 'gender', 'email', 'phone', 'grade', 'sttb_year', 
                        'height', 'weight', 'tshirt_size', 'shoes_size', 'basketball_position', 
                        'jersey_number', 'category', 'father_name', 'father_phone', 'mother_name', 'mother_phone'];
        if ($playerData) {
            foreach ($playerFields as $field) {
                if (!empty($playerData->$field)) {
                    $playerComplete++;
                }
            }
        }
        
        // Kelengkapan data dancer - berdasarkan struktur kolom dancer_list
        $dancerComplete = 0;
        $dancerFields = ['nik', 'birthdate', 'gender', 'email', 'phone', 'grade', 'sttb_year',
                        'height', 'weight', 'tshirt_size', 'shoes_size', 'father_name', 'father_phone', 
                        'mother_name', 'mother_phone'];
        if ($dancerData) {
            foreach ($dancerFields as $field) {
                if (!empty($dancerData->$field)) {
                    $dancerComplete++;
                }
            }
        }
        
        // Kelengkapan data official - berdasarkan struktur kolom official_list
        $officialComplete = 0;
        $officialFields = ['nik', 'birthdate', 'gender', 'email', 'phone', 'height', 'weight', 
                          'tshirt_size', 'shoes_size', 'team_role'];
        if ($officialData) {
            foreach ($officialFields as $field) {
                if (!empty($officialData->$field)) {
                    $officialComplete++;
                }
            }
        }
        
        // Status verifikasi team
        $verifiedTeams = 0;
        $pendingTeams = 0;
        $rejectedTeams = 0;
        
        foreach ($teams as $team) {
            if ($team->verification_status === 'verified') {
                $verifiedTeams++;
            } elseif ($team->verification_status === 'rejected') {
                $rejectedTeams++;
            } else {
                $pendingTeams++;
            }
        }
        
        return [
            'profile_completeness' => [
                'total' => count($profileFields),
                'completed' => $profileComplete,
                'percentage' => count($profileFields) > 0 ? round(($profileComplete / count($profileFields)) * 100) : 0,
            ],
            'player_completeness' => $playerData ? [
                'total' => count($playerFields),
                'completed' => $playerComplete,
                'percentage' => count($playerFields) > 0 ? round(($playerComplete / count($playerFields)) * 100) : 0,
            ] : null,
            'dancer_completeness' => $dancerData ? [
                'total' => count($dancerFields),
                'completed' => $dancerComplete,
                'percentage' => count($dancerFields) > 0 ? round(($dancerComplete / count($dancerFields)) * 100) : 0,
            ] : null,
            'official_completeness' => $officialData ? [
                'total' => count($officialFields),
                'completed' => $officialComplete,
                'percentage' => count($officialFields) > 0 ? round(($officialComplete / count($officialFields)) * 100) : 0,
            ] : null,
            'team_stats' => [
                'total' => count($teams),
                'verified' => $verifiedTeams,
                'pending' => $pendingTeams,
                'rejected' => $rejectedTeams,
            ],
            'has_player' => !is_null($playerData),
            'has_dancer' => !is_null($dancerData),
            'has_official' => !is_null($officialData),
        ];
    }
    
    /**
     * Mendapatkan daftar requirement yang belum dipenuhi
     */
    private function getMissingRequirements($user, $playerData, $dancerData, $officialData, $teams)
    {
        $missing = [];
        
        // Cek profile user
        if (empty($user->nik)) {
            $missing[] = [
                'category' => 'profile',
                'field' => 'NIK',
                'message' => 'NIK belum diisi',
                'route' => route('profile.edit'),
                'priority' => 'high',
            ];
        }
        
        if (empty($user->no_hp)) {
            $missing[] = [
                'category' => 'profile',
                'field' => 'Nomor Telepon',
                'message' => 'Nomor telepon belum diisi',
                'route' => route('profile.edit'),
                'priority' => 'high',
            ];
        }
        
        if (empty($user->birth_date)) {
            $missing[] = [
                'category' => 'profile',
                'field' => 'Tanggal Lahir',
                'message' => 'Tanggal lahir belum diisi',
                'route' => route('profile.edit'),
                'priority' => 'high',
            ];
        }
        
        if (empty($user->address)) {
            $missing[] = [
                'category' => 'profile',
                'field' => 'Alamat',
                'message' => 'Alamat belum diisi',
                'route' => route('profile.edit'),
                'priority' => 'medium',
            ];
        }
        
        // Cek kelengkapan data player
        if ($playerData) {
            $playerRequired = [
                'nik' => 'NIK',
                'birthdate' => 'Tanggal Lahir',
                'basketball_position' => 'Posisi Basket',
                'jersey_number' => 'Nomor Punggung',
                'height' => 'Tinggi Badan',
                'weight' => 'Berat Badan',
            ];
            
            foreach ($playerRequired as $field => $label) {
                if (empty($playerData->$field)) {
                    $missing[] = [
                        'category' => 'player',
                        'field' => $label,
                        'message' => "Data player: $label belum diisi",
                        'route' => route('profile.edit'),
                        'priority' => 'high',
                    ];
                }
            }
        }
        
        // Cek kelengkapan data dancer
        if ($dancerData) {
            $dancerRequired = [
                'nik' => 'NIK',
                'birthdate' => 'Tanggal Lahir',
                'height' => 'Tinggi Badan',
                'weight' => 'Berat Badan',
            ];
            
            foreach ($dancerRequired as $field => $label) {
                if (empty($dancerData->$field)) {
                    $missing[] = [
                        'category' => 'dancer',
                        'field' => $label,
                        'message' => "Data dancer: $label belum diisi",
                        'route' => route('profile.edit'),
                        'priority' => 'high',
                    ];
                }
            }
        }
        
        // Cek kelengkapan data official
        if ($officialData) {
            $officialRequired = [
                'nik' => 'NIK',
                'birthdate' => 'Tanggal Lahir',
                'team_role' => 'Jabatan',
            ];
            
            foreach ($officialRequired as $field => $label) {
                if (empty($officialData->$field)) {
                    $missing[] = [
                        'category' => 'official',
                        'field' => $label,
                        'message' => "Data official: $label belum diisi",
                        'route' => route('profile.edit'),
                        'priority' => 'high',
                    ];
                }
            }
        }
        
        // Cek team
        if (count($teams) === 0) {
            $missing[] = [
                'category' => 'team',
                'field' => 'Team',
                'message' => 'Belum bergabung dengan team manapun',
                'route' => route('form.team.choice'),
                'priority' => 'high',
            ];
        }
        
        return $missing;
    }
    
    /**
     * Cek dokumen yang sudah diupload berdasarkan data di tabel
     */
    private function checkDocuments($userName, $playerData, $dancerData, $officialData)
    {
        $documents = [
            // Dokumen umum
            'formal_photo' => false,
            'assignment_letter' => false,
            
            // Dokumen player & dancer
            'birth_certificate' => false,
            'kk' => false,
            'shun' => false,
            'report_identity' => false,
            'last_report_card' => false,
            
            // Dokumen official
            'license_photo' => false,
            'identity_card' => false,
            
            // Dokumen player
            'payment_proof' => false,
        ];
        
        // Cek dari player_data
        if ($playerData) {
            $documents['formal_photo'] = $documents['formal_photo'] || !empty($playerData->formal_photo);
            $documents['assignment_letter'] = $documents['assignment_letter'] || !empty($playerData->assignment_letter);
            $documents['birth_certificate'] = $documents['birth_certificate'] || !empty($playerData->birth_certificate);
            $documents['kk'] = $documents['kk'] || !empty($playerData->kk);
            $documents['shun'] = $documents['shun'] || !empty($playerData->shun);
            $documents['report_identity'] = $documents['report_identity'] || !empty($playerData->report_identity);
            $documents['last_report_card'] = $documents['last_report_card'] || !empty($playerData->last_report_card);
            $documents['payment_proof'] = $documents['payment_proof'] || !empty($playerData->payment_proof);
        }
        
        // Cek dari dancer_data
        if ($dancerData) {
            $documents['formal_photo'] = $documents['formal_photo'] || !empty($dancerData->formal_photo);
            $documents['assignment_letter'] = $documents['assignment_letter'] || !empty($dancerData->assignment_letter);
            $documents['birth_certificate'] = $documents['birth_certificate'] || !empty($dancerData->birth_certificate);
            $documents['kk'] = $documents['kk'] || !empty($dancerData->kk);
            $documents['shun'] = $documents['shun'] || !empty($dancerData->shun);
            $documents['report_identity'] = $documents['report_identity'] || !empty($dancerData->report_identity);
            $documents['last_report_card'] = $documents['last_report_card'] || !empty($dancerData->last_report_card);
        }
        
        // Cek dari official_data
        if ($officialData) {
            $documents['formal_photo'] = $documents['formal_photo'] || !empty($officialData->formal_photo);
            $documents['license_photo'] = !empty($officialData->license_photo);
            $documents['identity_card'] = !empty($officialData->identity_card);
        }
        
        // Cek dari avatar user
        $user = Auth::user();
        $documents['avatar'] = !empty($user->avatar);
        
        return $documents;
    }
    
    /**
     * Cek kelengkapan data player
     */
    private function checkPlayerCompleteness($userName, $teamId, $detailed = false)
    {
        $player = DB::table('player_list')
            ->where('name', $userName)
            ->where('team_id', $teamId)
            ->first();
            
        if (!$player) {
            return $detailed ? null : false;
        }
        
        $requiredFields = ['nik', 'birthdate', 'basketball_position', 'jersey_number', 'height', 'weight', 'phone'];
        $complete = true;
        $missingFields = [];
        
        foreach ($requiredFields as $field) {
            if (empty($player->$field)) {
                $complete = false;
                $missingFields[] = $field;
            }
        }
        
        if ($detailed) {
            return [
                'exists' => true,
                'complete' => $complete,
                'missing_fields' => $missingFields,
                'verification_status' => $player->verification_status ?? 'pending',
                'verified' => ($player->verification_status ?? 'pending') === 'verified',
                'data' => $player,
                'completeness_percentage' => count($requiredFields) > 0 
                    ? round(((count($requiredFields) - count($missingFields)) / count($requiredFields)) * 100) 
                    : 0,
            ];
        }
        
        return $complete;
    }
    
    /**
     * Cek kelengkapan data dancer
     */
    private function checkDancerCompleteness($userName, $teamId, $detailed = false)
    {
        $dancer = DB::table('dancer_list')
            ->where('name', $userName)
            ->where('team_id', $teamId)
            ->first();
            
        if (!$dancer) {
            return $detailed ? null : false;
        }
        
        $requiredFields = ['nik', 'birthdate', 'height', 'weight', 'phone'];
        $complete = true;
        $missingFields = [];
        
        foreach ($requiredFields as $field) {
            if (empty($dancer->$field)) {
                $complete = false;
                $missingFields[] = $field;
            }
        }
        
        if ($detailed) {
            return [
                'exists' => true,
                'complete' => $complete,
                'missing_fields' => $missingFields,
                'verification_status' => $dancer->verification_status ?? 'pending',
                'verified' => ($dancer->verification_status ?? 'pending') === 'verified',
                'data' => $dancer,
                'completeness_percentage' => count($requiredFields) > 0 
                    ? round(((count($requiredFields) - count($missingFields)) / count($requiredFields)) * 100) 
                    : 0,
            ];
        }
        
        return $complete;
    }
    
    /**
     * Cek kelengkapan data official
     */
    private function checkOfficialCompleteness($userName, $teamId, $detailed = false)
    {
        $official = DB::table('official_list')
            ->where('name', $userName)
            ->where('team_id', $teamId)
            ->first();
            
        if (!$official) {
            return $detailed ? null : false;
        }
        
        $requiredFields = ['nik', 'birthdate', 'team_role', 'phone'];
        $complete = true;
        $missingFields = [];
        
        foreach ($requiredFields as $field) {
            if (empty($official->$field)) {
                $complete = false;
                $missingFields[] = $field;
            }
        }
        
        if ($detailed) {
            return [
                'exists' => true,
                'complete' => $complete,
                'missing_fields' => $missingFields,
                'verification_status' => $official->verification_status ?? 'pending',
                'verified' => ($official->verification_status ?? 'pending') === 'verified',
                'data' => $official,
                'completeness_percentage' => count($requiredFields) > 0 
                    ? round(((count($requiredFields) - count($missingFields)) / count($requiredFields)) * 100) 
                    : 0,
            ];
        }
        
        return $complete;
    }
    
    /**
     * Cek status pembayaran team
     */
    private function checkPaymentStatus($teamId)
    {
        $team = DB::table('team_list')->where('team_id', $teamId)->first();
        
        if (!$team) {
            return [
                'status' => 'unknown',
                'paid' => false,
                'label' => 'Unknown',
                'class' => 'secondary',
            ];
        }
        
        $status = $team->payment_status ?? 'unpaid';
        $paid = $status === 'paid';
        
        $label = 'Unpaid';
        $class = 'danger';
        
        if ($status === 'paid') {
            $label = 'Paid';
            $class = 'success';
        } elseif ($status === 'pending') {
            $label = 'Pending';
            $class = 'warning';
        }
        
        return [
            'status' => $status,
            'paid' => $paid,
            'label' => $label,
            'class' => $class,
            'date' => $team->payment_date ? Carbon::parse($team->payment_date)->format('d M Y') : null,
            'method' => $team->payment_method ?? null,
        ];
    }
    
    /**
     * Cek status verifikasi team
     */
    private function checkVerificationStatus($teamId)
    {
        $team = DB::table('team_list')->where('team_id', $teamId)->first();
        
        if (!$team) {
            return [
                'status' => 'unknown',
                'verified' => false,
                'label' => 'Unknown',
                'class' => 'secondary',
            ];
        }
        
        $status = $team->verification_status ?? 'pending';
        $verified = $status === 'verified';
        
        $label = 'Pending';
        $class = 'warning';
        
        if ($status === 'verified') {
            $label = 'Verified';
            $class = 'success';
        } elseif ($status === 'rejected') {
            $label = 'Rejected';
            $class = 'danger';
        }
        
        return [
            'status' => $status,
            'verified' => $verified,
            'label' => $label,
            'class' => $class,
            'notes' => $team->verification_notes ?? null,
        ];
    }
    
    /**
     * Cek team leader
     */
    private function checkTeamLeader($teamId)
    {
        $team = DB::table('team_list')->where('team_id', $teamId)->first();
        
        if (!$team) {
            return null;
        }
        
        return [
            'name' => $team->leader_name ?? null,
            'email' => $team->leader_email ?? null,
            'phone' => $team->leader_phone ?? null,
        ];
    }
    
    /**
     * Hitung jumlah anggota team
     */
    private function getTeamMemberCount($teamId)
    {
        $playerCount = DB::table('player_list')->where('team_id', $teamId)->count();
        $dancerCount = DB::table('dancer_list')->where('team_id', $teamId)->count();
        $officialCount = DB::table('official_list')->where('team_id', $teamId)->count();
        
        return [
            'players' => $playerCount,
            'dancers' => $dancerCount,
            'officials' => $officialCount,
            'total' => $playerCount + $dancerCount + $officialCount,
        ];
    }
    
    /**
     * Hitung persentase kelengkapan dari checklist
     */
    private function calculateCompleteness($checklist)
    {
        $totalItems = 0;
        $completedItems = 0;
        
        // Hitung profile checklist
        foreach ($checklist['profile'] as $item) {
            $totalItems++;
            if ($item) {
                $completedItems++;
            }
        }
        
        // Hitung role checklist
        foreach ($checklist['role'] as $item) {
            $totalItems++;
            // Role checklist dihitung sebagai item yang ada
            if ($item) {
                $completedItems++;
            }
        }
        
        // Hitung documents checklist
        foreach ($checklist['documents'] as $item) {
            $totalItems++;
            if ($item) {
                $completedItems++;
            }
        }
        
        // Hitung team checklist
        foreach ($checklist['teams'] as $team) {
            if (isset($team['player_complete']) && $team['player_complete'] !== null) {
                $totalItems++;
                if ($team['player_complete'] === true) {
                    $completedItems++;
                }
            }
            
            if (isset($team['dancer_complete']) && $team['dancer_complete'] !== null) {
                $totalItems++;
                if ($team['dancer_complete'] === true) {
                    $completedItems++;
                }
            }
            
            if (isset($team['official_complete']) && $team['official_complete'] !== null) {
                $totalItems++;
                if ($team['official_complete'] === true) {
                    $completedItems++;
                }
            }
        }
        
        return $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
    }
    
    /**
     * Hitung jumlah item yang sudah lengkap
     */
    private function countCompletedItems($checklist)
    {
        $count = 0;
        
        foreach ($checklist['profile'] as $item) {
            if ($item) $count++;
        }
        
        foreach ($checklist['role'] as $item) {
            if ($item) $count++;
        }
        
        foreach ($checklist['documents'] as $item) {
            if ($item) $count++;
        }
        
        foreach ($checklist['teams'] as $team) {
            if (isset($team['player_complete']) && $team['player_complete'] === true) $count++;
            if (isset($team['dancer_complete']) && $team['dancer_complete'] === true) $count++;
            if (isset($team['official_complete']) && $team['official_complete'] === true) $count++;
        }
        
        return $count;
    }
    
    /**
     * Hitung total item keseluruhan
     */
    private function countTotalItems($checklist)
    {
        $total = count($checklist['profile']);
        $total += count($checklist['role']);
        $total += count($checklist['documents']);
        
        foreach ($checklist['teams'] as $team) {
            if (isset($team['player_complete']) && $team['player_complete'] !== null) $total++;
            if (isset($team['dancer_complete']) && $team['dancer_complete'] !== null) $total++;
            if (isset($team['official_complete']) && $team['official_complete'] !== null) $total++;
        }
        
        return $total;
    }
}