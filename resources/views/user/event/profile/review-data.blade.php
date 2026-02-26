@extends('user.form.layout')

@section('title', 'Review My Data - SBL Student Portal')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('student.dashboard') }}" class="text-decoration-none">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('student.event.histories') }}" class="text-decoration-none">
                            <i class="fas fa-history me-1"></i>Event Histories
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-user-edit me-1"></i>Review My Data
                    </li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="d-flex align-items-center mb-2">
                <div class="bg-primary bg-gradient rounded-3 p-3 me-3 shadow-sm">
                    <i class="fas fa-user-edit text-white fa-2x"></i>
                </div>
                <div>
                    <h1 class="h3 mb-1 fw-bold">Review My Data</h1>
                    <p class="text-muted mb-0">Complete information of your registration across all teams</p>
                </div>
            </div>
        </div>
    </div>

    @php
        // ============ AMBIL DATA USER LOGIN ============
        $currentUser = Auth::user();
        $currentUserName = strtolower(trim($currentUser->name ?? ''));
        
        // ============ INISIALISASI ARRAY DATA ============
        $myPlayerRecords = [];
        $myDancerRecords = [];
        $myOfficialRecords = [];
        
        // ============ HELPER FUNCTION UNTUK CEK DAN FORMAT FILE ============
        function formatFileSize($bytes) {
            if ($bytes === null || $bytes === 0) return '0 B';
            $units = ['B', 'KB', 'MB', 'GB'];
            $i = 0;
            while ($bytes >= 1024 && $i < count($units) - 1) {
                $bytes /= 1024;
                $i++;
            }
            return round($bytes, 2) . ' ' . $units[$i];
        }
        
        function formatRole($role) {
            if (empty($role)) return '-';
            $formatted = str_replace('_', ' ', $role);
            return ucwords($formatted);
        }
        
        function formatGender($gender) {
            $genderLower = strtolower($gender ?? '');
            if (in_array($genderLower, ['male', 'laki-laki', 'putra'])) {
                return ['text' => 'Male', 'class' => 'bg-success', 'icon' => 'fa-male'];
            } elseif (in_array($genderLower, ['female', 'perempuan', 'putri'])) {
                return ['text' => 'Female', 'class' => 'bg-info', 'icon' => 'fa-female'];
            }
            return ['text' => 'Not Specified', 'class' => 'bg-secondary', 'icon' => 'fa-genderless'];
        }
        
        function getDocumentIcon($extension, $defaultIcon) {
            $icons = [
                'pdf' => 'fa-file-pdf text-danger',
                'jpg' => 'fa-file-image text-info',
                'jpeg' => 'fa-file-image text-info',
                'png' => 'fa-file-image text-info',
                'gif' => 'fa-file-image text-info',
                'doc' => 'fa-file-word text-primary',
                'docx' => 'fa-file-word text-primary',
                'xls' => 'fa-file-excel text-success',
                'xlsx' => 'fa-file-excel text-success',
            ];
            
            return $icons[$extension] ?? $defaultIcon;
        }
        
        // ============ AMBIL DATA DARI DATABASE ============
        try {
            if (!empty($currentUserName)) {
                // Player Records
                $myPlayerRecords = DB::table('player_list')
                    ->select(
                        'player_list.*',
                        'team_list.team_id as team_team_id',
                        'team_list.school_name as team_school_name',
                        'team_list.competition',
                        'team_list.team_category',
                        'team_list.season',
                        'team_list.series',
                        'team_list.referral_code',
                        'team_list.locked_status',
                        'team_list.verification_status as team_verification_status'
                    )
                    ->leftJoin('team_list', 'player_list.team_id', '=', 'team_list.team_id')
                    ->whereRaw('LOWER(TRIM(player_list.name)) = ?', [$currentUserName])
                    ->orderBy('player_list.created_at', 'desc')
                    ->get()
                    ->map(function($item) {
                        $item->type = 'player';
                        $item->type_label = 'Basketball Player';
                        $item->type_icon = 'fa-basketball-ball';
                        $item->type_color = 'success';
                        $item->type_color_hex = '2e7d32';
                        return $item;
                    })
                    ->toArray();

                // Dancer Records
                $myDancerRecords = DB::table('dancer_list')
                    ->select(
                        'dancer_list.*',
                        'team_list.team_id as team_team_id',
                        'team_list.school_name as team_school_name',
                        'team_list.competition',
                        'team_list.team_category',
                        'team_list.season',
                        'team_list.series',
                        'team_list.referral_code',
                        'team_list.locked_status',
                        'team_list.verification_status as team_verification_status'
                    )
                    ->leftJoin('team_list', 'dancer_list.team_id', '=', 'team_list.team_id')
                    ->whereRaw('LOWER(TRIM(dancer_list.name)) = ?', [$currentUserName])
                    ->orderBy('dancer_list.created_at', 'desc')
                    ->get()
                    ->map(function($item) {
                        $item->type = 'dancer';
                        $item->type_label = 'Dancer';
                        $item->type_icon = 'fa-music';
                        $item->type_color = 'pink-600';
                        $item->type_color_hex = 'd81b60';
                        return $item;
                    })
                    ->toArray();

                // Official Records
                $myOfficialRecords = DB::table('official_list')
                    ->select(
                        'official_list.*',
                        'team_list.team_id as team_team_id',
                        'team_list.school_name as team_school_name',
                        'team_list.competition',
                        'team_list.team_category',
                        'team_list.season',
                        'team_list.series',
                        'team_list.referral_code',
                        'team_list.locked_status',
                        'team_list.verification_status as team_verification_status'
                    )
                    ->leftJoin('team_list', 'official_list.team_id', '=', 'team_list.team_id')
                    ->whereRaw('LOWER(TRIM(official_list.name)) = ?', [$currentUserName])
                    ->orderBy('official_list.created_at', 'desc')
                    ->get()
                    ->map(function($item) {
                        $item->type = 'official';
                        $item->type_label = 'Official';
                        $item->type_icon = 'fa-user-tie';
                        $item->type_color = 'warning';
                        $item->type_color_hex = 'ed6c02';
                        return $item;
                    })
                    ->toArray();
            }
        } catch (\Exception $e) {
            Log::error('Error fetching records: ' . $e->getMessage());
        }
        
        // ============ GABUNGKAN DAN URUTKAN RECORD ============
        $allMyRecords = array_merge($myPlayerRecords, $myDancerRecords, $myOfficialRecords);
        usort($allMyRecords, fn($a, $b) => strtotime($b->created_at ?? 'now') - strtotime($a->created_at ?? 'now'));
        
        // ============ HITUNG STATISTIK ============
        $totalRecords = count($allMyRecords);
        $totalTeams = collect($allMyRecords)->pluck('team_id')->filter()->unique()->count();
        $totalAsLeader = collect($allMyRecords)->where('role', 'Leader')->count();
        $totalAsCaptain = collect($allMyRecords)->where('role', 'Leader')->where('type', 'player')->count();
        $totalAsDancerLeader = collect($allMyRecords)->where('role', 'Leader')->where('type', 'dancer')->count();
        
        // ============ DOKUMEN CONFIGURATION ============
        $documentConfig = [
            'player' => [
                'birth_certificate' => ['label' => 'Akta Kelahiran', 'icon' => 'fa-file-pdf text-danger', 'color' => 'danger'],
                'kk' => ['label' => 'Kartu Keluarga', 'icon' => 'fa-file-pdf text-danger', 'color' => 'danger'],
                'shun' => ['label' => 'SHUN', 'icon' => 'fa-file-pdf text-danger', 'color' => 'danger'],
                'report_identity' => ['label' => 'Identitas Raport', 'icon' => 'fa-file-pdf text-danger', 'color' => 'danger'],
                'last_report_card' => ['label' => 'Raport Terakhir', 'icon' => 'fa-file-pdf text-danger', 'color' => 'danger'],
                'formal_photo' => ['label' => 'Foto Formal', 'icon' => 'fa-file-image text-info', 'color' => 'info'],
                'assignment_letter' => ['label' => 'Surat Tugas', 'icon' => 'fa-file-pdf text-danger', 'color' => 'danger'],
                'payment_proof' => ['label' => 'Bukti Pembayaran', 'icon' => 'fa-file-invoice text-success', 'color' => 'success'],
            ],
            'dancer' => [
                'birth_certificate' => ['label' => 'Akta Kelahiran', 'icon' => 'fa-file-pdf text-danger', 'color' => 'danger'],
                'kk' => ['label' => 'Kartu Keluarga', 'icon' => 'fa-file-pdf text-danger', 'color' => 'danger'],
                'shun' => ['label' => 'SHUN', 'icon' => 'fa-file-pdf text-danger', 'color' => 'danger'],
                'report_identity' => ['label' => 'Identitas Raport', 'icon' => 'fa-file-pdf text-danger', 'color' => 'danger'],
                'last_report_card' => ['label' => 'Raport Terakhir', 'icon' => 'fa-file-pdf text-danger', 'color' => 'danger'],
                'formal_photo' => ['label' => 'Foto Formal', 'icon' => 'fa-file-image text-info', 'color' => 'info'],
                'assignment_letter' => ['label' => 'Surat Tugas', 'icon' => 'fa-file-pdf text-danger', 'color' => 'danger'],
                'payment_proof' => ['label' => 'Bukti Pembayaran', 'icon' => 'fa-file-invoice text-success', 'color' => 'success'],
            ],
            'official' => [
                'formal_photo' => ['label' => 'Foto Formal', 'icon' => 'fa-file-image text-info', 'color' => 'info'],
                'license_photo' => ['label' => 'Lisensi', 'icon' => 'fa-file-pdf text-danger', 'color' => 'danger'],
                'identity_card' => ['label' => 'KTP/SIM', 'icon' => 'fa-id-card text-primary', 'color' => 'primary'],
            ]
        ];
    @endphp

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
    </script>
    @endif

    @if(session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33',
                toast: true,
                position: 'top-end'
            });
        });
    </script>
    @endif

    @if(empty($allMyRecords))
    <!-- NO DATA STATE -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-5 text-center">
                    <div class="empty-state">
                        <div class="empty-state-icon mb-4">
                            <i class="fas fa-user-slash fa-4x text-muted"></i>
                        </div>
                        <h3 class="fw-bold mb-3">No Registration Data Found</h3>
                        <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">
                            We couldn't find any registration records associated with your account. 
                            This could mean you haven't registered for any events yet.
                        </p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('student.dashboard') }}" class="btn btn-outline-primary">
                                <i class="fas fa-home me-2"></i>Go to Dashboard
                            </a>
                            <a href="{{ route('student.event.histories') }}" class="btn btn-primary">
                                <i class="fas fa-history me-2"></i>View Event Histories
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- STATISTICS CARDS -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-user-check text-primary fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Total Registrations</h6>
                            <h3 class="fw-bold mb-0 text-primary">{{ $totalRecords }}</h3>
                            <small class="text-muted">{{ $totalTeams }} teams</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-success bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-basketball-ball text-success fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">As Player</h6>
                            <h3 class="fw-bold mb-0 text-success">{{ count($myPlayerRecords) }}</h3>
                            <small class="text-muted">{{ $totalAsCaptain }} Captain</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon" style="background-color: rgba(216,27,96,0.1); border-radius: 0.5rem; padding: 1rem; margin-right: 1rem;">
                            <i class="fas fa-music" style="color: #d81b60;"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">As Dancer</h6>
                            <h3 class="fw-bold mb-0" style="color: #d81b60;">{{ count($myDancerRecords) }}</h3>
                            <small class="text-muted">{{ $totalAsDancerLeader }} Leader</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="stat-icon bg-warning bg-opacity-10 rounded-3 p-3 me-3">
                            <i class="fas fa-user-tie text-warning fa-lg"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">As Official</h6>
                            <h3 class="fw-bold mb-0 text-warning">{{ count($myOfficialRecords) }}</h3>
                            <small class="text-muted">{{ $totalAsLeader - $totalAsCaptain - $totalAsDancerLeader }} Leader</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <!-- Card Header -->
                <div class="card-header bg-white border-0 py-3">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h5 class="mb-0 fw-bold">
                                <i class="fas fa-list me-2 text-primary"></i>My Registration Records
                            </h5>
                            <p class="text-muted small mb-0 mt-1">
                                {{ $totalRecords }} record(s) found across {{ $totalTeams }} team(s)
                            </p>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="search-box">
                                <i class="fas fa-search search-icon"></i>
                                <input type="text" class="form-control form-control-sm" id="searchRecords" 
                                       placeholder="Search by team, role, name..." autocomplete="off"
                                       style="padding-left: 2rem; width: 250px;">
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" 
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-filter me-1"></i><span id="filterLabel">All Records</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item filter-option active" href="#" data-filter="all">All Records</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-filter="player">Basketball Players</a></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-filter="dancer">Dancers</a></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-filter="official">Officials</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item filter-option" href="#" data-filter="leader">As Leader/Captain</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-3 p-lg-4">
                    @foreach($allMyRecords as $record)
                    @php
                        $genderInfo = formatGender($record->gender ?? '');
                        $isVerified = ($record->verification_status ?? 'unverified') === 'verified';
                        $isRejected = ($record->verification_status ?? '') === 'rejected';
                        $isLocked = ($record->locked_status ?? 'unlocked') === 'locked';
                        $isLeader = ($record->role ?? '') === 'Leader';
                        
                        $headerColor = [
                            'player' => 'success',
                            'dancer' => 'pink-600',
                            'official' => 'warning'
                        ][$record->type] ?? 'primary';
                        
                        $headerBgColor = [
                            'player' => '#2e7d32',
                            'dancer' => '#d81b60',
                            'official' => '#ed6c02'
                        ][$record->type] ?? '#0d6efd';
                        
                        $teamRoleDisplay = match($record->type) {
                            'player' => $record->basketball_position ?? null,
                            'official' => formatRole($record->team_role ?? ''),
                            default => null
                        };
                        
                        // ============ FIXED: Count uploaded documents properly ============
                        $documentFields = array_keys($documentConfig[$record->type] ?? []);
                        $uploadedDocs = 0;
                        foreach ($documentFields as $field) {
                            if (!empty($record->$field)) {
                                $uploadedDocs++;
                            }
                        }
                        $totalDocs = count($documentFields);
                    @endphp
                    
                    <div class="record-item mb-4" 
                         data-type="{{ $record->type }}" 
                         data-role="{{ strtolower($record->role ?? 'member') }}" 
                         data-team="{{ strtolower($record->team_school_name ?? '') }}" 
                         data-name="{{ strtolower($record->name ?? '') }}">
                        
                        <div class="card border-0 shadow-sm hover-card">
                            <!-- Card Header -->
                            <div class="card-header py-3 rounded-top-3 border-0 text-white" 
                                 style="background-color: {{ $headerBgColor }};">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-white bg-opacity-20 rounded-2 p-2 me-3">
                                            <i class="fas {{ $record->type_icon }} text-white fa-fw"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $record->type_label }}</h6>
                                            <small class="text-white-50">
                                                <i class="far fa-clock me-1"></i>
                                                {{ !empty($record->created_at) ? \Carbon\Carbon::parse($record->created_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') : '-' }} WIB
                                            </small>
                                        </div>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        @if($isLeader)
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                                <i class="fas fa-crown me-1"></i>{{ $record->role }}
                                            </span>
                                        @endif
                                        
                                        @if($isVerified)
                                            <span class="badge bg-success rounded-pill px-3 py-2">
                                                <i class="fas fa-check-circle me-1"></i>Verified
                                            </span>
                                        @elseif($isRejected)
                                            <span class="badge bg-danger rounded-pill px-3 py-2">
                                                <i class="fas fa-times-circle me-1"></i>Rejected
                                            </span>
                                        @else
                                            <span class="badge bg-warning text-dark rounded-pill px-3 py-2">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @endif
                                        
                                        @if($isLocked)
                                            <span class="badge bg-dark rounded-pill px-3 py-2">
                                                <i class="fas fa-lock me-1"></i>Locked
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card Body -->
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <!-- Left Column: Personal Info -->
                                    <div class="col-lg-4">
                                        <div class="d-flex align-items-start mb-3">
                                            <!-- Avatar -->
                                            <div class="avatar-wrapper me-3">
                                                @php
                                                    $avatarUrl = null;
                                                    $avatarBg = $headerBgColor;
                                                    $initial = strtoupper(substr($record->name ?? 'U', 0, 1));
                                                    
                                                    if (!empty($record->formal_photo)) {
                                                        try {
                                                            $avatarUrl = route('student.review.document.view', [
                                                                'teamId' => $record->team_id,
                                                                'documentType' => 'formal_photo'
                                                            ]);
                                                        } catch (\Exception $e) {
                                                            $avatarUrl = null;
                                                        }
                                                    }
                                                @endphp
                                                
                                                @if($avatarUrl)
                                                    <img src="{{ $avatarUrl }}" 
                                                         alt="{{ $record->name ?? 'User' }}"
                                                         class="rounded-3 w-100 h-100 object-fit-cover border border-2 border-white shadow-sm"
                                                         style="width: 80px; height: 80px; object-fit: cover;"
                                                         loading="lazy"
                                                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($record->name ?? 'User') }}&background={{ str_replace('#', '', $avatarBg) }}&color=fff&size=80&bold=true';">
                                                @else
                                                    <div class="rounded-3 d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                                                         style="width: 80px; height: 80px; background-color: {{ $avatarBg }}; font-size: 2rem;">
                                                        {{ $initial }}
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <!-- Basic Info -->
                                            <div class="flex-grow-1">
                                                <h5 class="fw-bold mb-1">{{ $record->name ?? '-' }}</h5>
                                                <div class="d-flex flex-wrap gap-1 mb-2">
                                                    <span class="badge {{ $genderInfo['class'] }} rounded-pill">
                                                        <i class="fas {{ $genderInfo['icon'] }} me-1"></i>{{ $genderInfo['text'] }}
                                                    </span>
                                                    @if(!empty($record->jersey_number))
                                                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">
                                                            <i class="fas fa-tshirt me-1"></i>#{{ $record->jersey_number }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <div class="small">
                                                    <div class="text-muted text-truncate" style="max-width: 200px;">
                                                        <i class="fas fa-envelope me-1"></i>{{ $record->email ?? '-' }}
                                                    </div>
                                                    <div class="text-muted mt-1">
                                                        <i class="fas fa-phone me-1"></i>{{ $record->phone ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Personal Details Card -->
                                        <div class="info-card bg-light rounded-3 p-3">
                                            <h6 class="fw-bold mb-3">
                                                <i class="fas fa-id-card me-2 text-primary"></i>Personal Details
                                            </h6>
                                            <div class="row g-2">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">NIK</small>
                                                    <span class="fw-medium">{{ $record->nik ?? '-' }}</span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Birthdate</small>
                                                    <span class="fw-medium">
                                                        {{ !empty($record->birthdate) ? \Carbon\Carbon::parse($record->birthdate)->format('d M Y') : '-' }}
                                                    </span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Height/Weight</small>
                                                    <span class="fw-medium">{{ $record->height ?? '0' }} cm / {{ $record->weight ?? '0' }} kg</span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">T-Shirt/Shoes</small>
                                                    <span class="fw-medium">{{ $record->tshirt_size ?? '-' }} / {{ $record->shoes_size ?? '-' }}</span>
                                                </div>
                                                @if(in_array($record->type, ['player', 'dancer']))
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Grade</small>
                                                    <span class="fw-medium">{{ $record->grade ?? '-' }}</span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">STTB Year</small>
                                                    <span class="fw-medium">{{ $record->sttb_year ?? '-' }}</span>
                                                </div>
                                                @endif
                                                @if($record->type === 'official')
                                                <div class="col-12 mt-2">
                                                    <small class="text-muted d-block">Category</small>
                                                    <span class="fw-medium">{{ formatRole($record->category ?? '') }}</span>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Middle Column: Team Info -->
                                    <div class="col-lg-4">
                                        <div class="info-card bg-light rounded-3 p-3 h-100">
                                            <h6 class="fw-bold mb-3">
                                                <i class="fas fa-users me-2 text-primary"></i>Team Information
                                            </h6>
                                            
                                            <div class="mb-3">
                                                <small class="text-muted d-block mb-1">School / Team</small>
                                                <div class="d-flex align-items-center">
                                                    <span class="fw-bold fs-6">
                                                        {{ $record->team_school_name ?? $record->school_name ?? '-' }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <div class="row g-2 mb-3">
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Competition</small>
                                                    <span class="fw-medium">{{ $record->competition ?? 'HSBL' }}</span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Category</small>
                                                    <span class="fw-medium">
                                                        @if($record->type === 'player')
                                                            Basketball {{ ucfirst($record->category ?? 'Putra') }}
                                                        @elseif($record->type === 'dancer')
                                                            Dancer
                                                        @else
                                                            {{ formatRole($record->category ?? 'Basketball') }}
                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Season/Series</small>
                                                    <span class="fw-medium">{{ $record->season ?? '2025' }}-{{ $record->series ?? '1' }}</span>
                                                </div>
                                                <div class="col-6">
                                                    <small class="text-muted d-block">Team ID</small>
                                                    <span class="fw-medium font-monospace text-primary">{{ $record->team_id ?? '-' }}</span>
                                                </div>
                                            </div>
                                            
                                            @if(!empty($record->referral_code))
                                            <div class="mt-2 p-2 bg-primary bg-opacity-10 rounded-3">
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-gift me-1 text-primary"></i>Referral Code
                                                </small>
                                                <span class="fw-bold font-monospace text-primary">{{ $record->referral_code }}</span>
                                            </div>
                                            @endif
                                            
                                            @if(!empty($teamRoleDisplay))
                                            <div class="mt-3 p-2 rounded-3" 
                                                 style="background-color: {{ $headerBgColor }}10;">
                                                <small class="text-muted d-block">
                                                    <i class="fas fa-id-card me-1"></i>
                                                    {{ $record->type === 'player' ? 'Position' : 'Team Role' }}
                                                </small>
                                                <span class="fw-bold" style="color: {{ $headerBgColor }};">
                                                    {{ $teamRoleDisplay }}
                                                </span>
                                            </div>
                                            @endif
                                            
                                            @if(!empty($record->instagram) || !empty($record->tiktok))
                                            <div class="mt-3">
                                                <small class="text-muted d-block mb-2">Social Media</small>
                                                <div class="d-flex gap-2">
                                                    @if($record->instagram)
                                                    <a href="https://instagram.com/{{ $record->instagram }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-outline-secondary rounded-circle"
                                                       style="width: 36px; height: 36px;"
                                                       title="Instagram">
                                                        <i class="fab fa-instagram"></i>
                                                    </a>
                                                    @endif
                                                    @if($record->tiktok)
                                                    <a href="https://tiktok.com/@{{ $record->tiktok }}" 
                                                       target="_blank"
                                                       class="btn btn-sm btn-outline-secondary rounded-circle"
                                                       style="width: 36px; height: 36px;"
                                                       title="TikTok">
                                                        <i class="fab fa-tiktok"></i>
                                                    </a>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <!-- Right Column: Documents -->
                                    <div class="col-lg-4">
                                        <div class="info-card bg-light rounded-3 p-3 h-100">
                                            <h6 class="fw-bold mb-3">
                                                <i class="fas fa-file-alt me-2 text-primary"></i>Documents
                                                <!-- FIXED: Use $uploadedDocs and $totalDocs variables -->
                                                <span class="badge bg-primary bg-opacity-10 text-primary ms-2 rounded-pill">
                                                    {{ $uploadedDocs }}/{{ $totalDocs }}
                                                </span>
                                            </h6>
                                            
                                            <div class="document-list" style="max-height: 300px; overflow-y: auto; padding-right: 5px;">
                                                @if(!empty($documentConfig[$record->type]))
                                                    @foreach($documentConfig[$record->type] as $field => $docInfo)
                                                        @php
                                                            $hasFile = !empty($record->$field);
                                                            $fileName = $hasFile ? basename($record->$field) : null;
                                                            $fileExtension = $hasFile ? strtolower(pathinfo($fileName, PATHINFO_EXTENSION)) : null;
                                                            $fileSize = null;
                                                            
                                                            if ($hasFile && $fileName) {
                                                                $paths = [
                                                                    'player' => storage_path('app/public/player_docs/' . $fileName),
                                                                    'dancer' => storage_path('app/public/dancer_docs/' . $fileName),
                                                                    'official' => storage_path('app/public/uploads/officials/' . str_replace('_photo', '_photos', $field) . '/' . $fileName),
                                                                ];
                                                                
                                                                $filePath = $paths[$record->type] ?? null;
                                                                if ($filePath && file_exists($filePath)) {
                                                                    $fileSize = formatFileSize(filesize($filePath));
                                                                }
                                                            }
                                                            
                                                            $viewRoute = null;
                                                            $downloadRoute = null;
                                                            
                                                            if ($hasFile && !empty($record->team_id)) {
                                                                try {
                                                                    $viewRoute = route('student.review.document.view', [
                                                                        'teamId' => $record->team_id,
                                                                        'documentType' => $field
                                                                    ]);
                                                                    $downloadRoute = route('student.review.document.download', [
                                                                        'teamId' => $record->team_id,
                                                                        'documentType' => $field
                                                                    ]);
                                                                } catch (\Exception $e) {
                                                                    $viewRoute = null;
                                                                    $downloadRoute = null;
                                                                }
                                                            }
                                                        @endphp
                                                        
                                                        <div class="document-item mb-2 p-2 bg-white rounded-3 border {{ $hasFile ? 'border-success border-opacity-25' : 'border-light' }}">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="me-2">
                                                                        @if($hasFile)
                                                                            @php
                                                                                $iconClass = getDocumentIcon($fileExtension, $docInfo['icon'] ?? 'fa-file');
                                                                            @endphp
                                                                            <i class="fas {{ $iconClass }} fa-lg"></i>
                                                                        @else
                                                                            <i class="fas {{ $docInfo['icon'] ?? 'fa-file' }} text-muted fa-lg"></i>
                                                                        @endif
                                                                    </div>
                                                                    <div>
                                                                        <small class="fw-medium d-block">{{ $docInfo['label'] ?? ucfirst($field) }}</small>
                                                                        @if($hasFile && $fileSize)
                                                                            <small class="text-muted">{{ $fileSize }}</small>
                                                                        @elseif($hasFile)
                                                                            <small class="text-muted">Uploaded</small>
                                                                        @else
                                                                            <small class="text-muted">Not uploaded</small>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                
                                                                @if($hasFile && $viewRoute && $downloadRoute)
                                                                <div class="d-flex gap-1">
                                                                    @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'pdf']))
                                                                        <a href="{{ $viewRoute }}" 
                                                                           class="btn btn-sm btn-outline-primary"
                                                                           target="_blank"
                                                                           title="View Document"
                                                                           data-bs-toggle="tooltip">
                                                                            <i class="fas fa-eye"></i>
                                                                        </a>
                                                                    @endif
                                                                    <a href="{{ $downloadRoute }}" 
                                                                       class="btn btn-sm btn-primary"
                                                                       title="Download Document"
                                                                       data-bs-toggle="tooltip">
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                </div>
                                                                @else
                                                                    <span class="badge bg-light text-muted px-3 py-2 rounded-pill">
                                                                        <i class="fas fa-times me-1"></i>Missing
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <div class="text-center py-3">
                                                        <i class="fas fa-file-alt fa-2x text-muted mb-2"></i>
                                                        <p class="text-muted small mb-0">No document configuration</p>
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            @if($record->type === 'player' && !empty($record->payment_proof) && $isLeader)
                                            <div class="mt-3 p-2 bg-success bg-opacity-10 rounded-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-check-circle text-success me-2"></i>
                                                    <div>
                                                        <small class="text-muted d-block">Payment Status</small>
                                                        <span class="fw-bold text-success">Paid (Leader)</span>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Parent/Guardian Info -->
                                @if(in_array($record->type, ['player', 'dancer']) && (!empty($record->father_name) || !empty($record->mother_name)))
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="info-card bg-light rounded-3 p-3">
                                            <h6 class="fw-bold mb-3">
                                                <i class="fas fa-family me-2 text-primary"></i>Parent/Guardian Information
                                            </h6>
                                            <div class="row g-3">
                                                @if(!empty($record->father_name))
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                                            <i class="fas fa-user text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block">Father</small>
                                                            <span class="fw-medium">{{ $record->father_name }}</span>
                                                            @if(!empty($record->father_phone))
                                                            <small class="d-block text-muted">{{ $record->father_phone }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                @if(!empty($record->mother_name))
                                                <div class="col-md-6">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                                            <i class="fas fa-user text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <small class="text-muted d-block">Mother</small>
                                                            <span class="fw-medium">{{ $record->mother_name }}</span>
                                                            @if(!empty($record->mother_phone))
                                                            <small class="d-block text-muted">{{ $record->mother_phone }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Action Buttons -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-end gap-2">
                                            @if(!empty($record->team_id))
                                            <a href="{{ route('student.team.list.with_id', $record->team_id) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-users me-1"></i>View Full Team
                                            </a>
                                            @endif
                                            @if(!$isLocked && $record->type === 'player')
                                                <button type="button" 
                                                        class="btn btn-outline-warning btn-sm"
                                                        onclick="alert('Edit functionality will be available soon')">
                                                    <i class="fas fa-edit me-1"></i>Edit Data
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card Footer -->
                            <div class="card-footer bg-white border-0 rounded-bottom-3 p-3">
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">
                                    <div>
                                        <small class="text-muted">
                                            <i class="fas fa-fingerprint me-1"></i>Record ID: 
                                            <span class="font-monospace">
                                                @if($record->type === 'player')
                                                    {{ $record->id ?? $record->player_id ?? '-' }}
                                                @elseif($record->type === 'dancer')
                                                    {{ $record->dancer_id ?? $record->id ?? '-' }}
                                                @else
                                                    {{ $record->official_id ?? $record->id ?? '-' }}
                                                @endif
                                            </span>
                                        </small>
                                        @if(!empty($record->updated_at) && $record->updated_at != ($record->created_at ?? ''))
                                        <small class="text-muted ms-3 d-block d-md-inline mt-1 mt-md-0">
                                            <i class="fas fa-edit me-1"></i>Last updated: 
                                            {{ !empty($record->updated_at) ? \Carbon\Carbon::parse($record->updated_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') : '-' }} WIB
                                        </small>
                                        @endif
                                    </div>
                                    <div>
                                        @if($isVerified)
                                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                                <i class="fas fa-check-circle me-1"></i>Verified by Admin
                                            </span>
                                        @elseif($isRejected)
                                            <span class="badge bg-danger bg-opacity-10 text-danger px-3 py-2 rounded-pill">
                                                <i class="fas fa-exclamation-circle me-1"></i>Rejected
                                            </span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill">
                                                <i class="fas fa-clock me-1"></i>Awaiting Verification
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Search functionality
    const searchInput = document.getElementById('searchRecords');
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase().trim();
            const records = document.querySelectorAll('.record-item');
            
            records.forEach(record => {
                const team = record.dataset.team || '';
                const role = record.dataset.role || '';
                const name = record.dataset.name || '';
                const type = record.dataset.type || '';
                
                const searchText = `${team} ${role} ${name} ${type}`.toLowerCase();
                record.style.display = searchText.includes(filter) ? '' : 'none';
            });
            
            updateEmptyState();
        });
    }
    
    // Filter dropdown
    const filterOptions = document.querySelectorAll('.filter-option');
    const filterLabel = document.getElementById('filterLabel');
    
    filterOptions.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Update active state
            filterOptions.forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            const records = document.querySelectorAll('.record-item');
            
            // Update filter label
            if (filterLabel) {
                filterLabel.textContent = this.textContent.trim();
            }
            
            records.forEach(record => {
                if (filter === 'all') {
                    record.style.display = '';
                } else if (filter === 'leader') {
                    const role = record.dataset.role;
                    record.style.display = role === 'leader' ? '' : 'none';
                } else {
                    const type = record.dataset.type;
                    record.style.display = type === filter ? '' : 'none';
                }
            });
            
            updateEmptyState();
        });
    });
    
    // Show empty state message when no records after filter
    function updateEmptyState() {
        const records = document.querySelectorAll('.record-item');
        const visibleRecords = Array.from(records).filter(r => r.style.display !== 'none');
        
        let emptyState = document.querySelector('.filter-empty-state');
        
        if (visibleRecords.length === 0 && records.length > 0) {
            if (!emptyState) {
                emptyState = document.createElement('div');
                emptyState.className = 'filter-empty-state text-center py-5';
                emptyState.innerHTML = `
                    <div class="empty-state">
                        <i class="fas fa-filter fa-3x text-muted mb-3"></i>
                        <h5 class="fw-bold mb-2">No matching records</h5>
                        <p class="text-muted mb-0">Try adjusting your search or filter</p>
                    </div>
                `;
                document.querySelector('.card-body').appendChild(emptyState);
            }
        } else {
            if (emptyState) {
                emptyState.remove();
            }
        }
    }
});
</script>

<style>
/* Custom Styles */
:root {
    --primary-color: #0d6efd;
    --success-color: #2e7d32;
    --pink-color: #d81b60;
    --warning-color: #ed6c02;
    --border-radius: 0.5rem;
}

/* Card Styles */
.card {
    border-radius: var(--border-radius);
    transition: all 0.2s ease;
}

.hover-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1.5rem rgba(0,0,0,0.08) !important;
}

.rounded-top-3 {
    border-top-left-radius: var(--border-radius) !important;
    border-top-right-radius: var(--border-radius) !important;
}

.rounded-bottom-3 {
    border-bottom-left-radius: var(--border-radius) !important;
    border-bottom-right-radius: var(--border-radius) !important;
}

.rounded-3 {
    border-radius: var(--border-radius) !important;
}

.rounded-2 {
    border-radius: calc(var(--border-radius) - 0.125rem) !important;
}

/* Info Card */
.info-card {
    transition: background-color 0.2s ease;
}

.info-card:hover {
    background-color: #f8f9fa !important;
}

/* Avatar */
.avatar-wrapper {
    width: 80px;
    height: 80px;
    flex-shrink: 0;
}

.object-fit-cover {
    object-fit: cover;
}

/* Search Box */
.search-box {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6c757d;
    font-size: 0.875rem;
    z-index: 10;
}

/* Document List Scrollbar */
.document-list::-webkit-scrollbar {
    width: 4px;
}

.document-list::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.document-list::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}

.document-list::-webkit-scrollbar-thumb:hover {
    background: #999;
}

/* Document Item */
.document-item {
    transition: all 0.2s ease;
}

.document-item:hover {
    border-color: #0d6efd !important;
    background-color: #f8f9fa !important;
}

/* Badge */
.badge {
    font-weight: 500;
    font-size: 0.75rem;
    padding: 0.4rem 0.8rem;
}

.badge.bg-opacity-10 {
    background-color: rgba(0,0,0,0.05) !important;
}

/* Text Colors */
.text-pink-600 {
    color: var(--pink-color) !important;
}

.bg-pink-600 {
    background-color: var(--pink-color) !important;
}

/* White with opacity */
.bg-white.bg-opacity-20 {
    background-color: rgba(255, 255, 255, 0.2) !important;
}

.text-white-50 {
    color: rgba(255, 255, 255, 0.7) !important;
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.record-item {
    animation: fadeIn 0.4s ease-out;
}

/* Empty State */
.empty-state {
    opacity: 0.8;
    transition: opacity 0.2s ease;
}

.empty-state:hover {
    opacity: 1;
}

.empty-state-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    border-radius: 50%;
}

/* Stat Icon */
.stat-icon {
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Responsive */
@media (max-width: 768px) {
    .avatar-wrapper {
        width: 70px;
        height: 70px;
    }
    
    .document-list {
        max-height: 250px !important;
    }
    
    .card-body.p-4 {
        padding: 1.25rem !important;
    }
}

@media (max-width: 576px) {
    .search-box .form-control {
        width: 100% !important;
    }
    
    .stat-icon {
        width: 42px;
        height: 42px;
        padding: 0.75rem !important;
    }
    
    .stat-icon i {
        font-size: 1rem;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.3rem 0.6rem;
    }
}

/* Font Monospace */
.font-monospace {
    font-family: 'SF Mono', 'Menlo', 'Monaco', 'Consolas', monospace;
}

/* Dropdown Active State */
.dropdown-item.active {
    background-color: #0d6efd;
    color: white;
}

.dropdown-item.active i {
    color: white !important;
}

/* Tooltip */
.tooltip {
    font-size: 0.75rem;
}

/* Loading State */
.skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection