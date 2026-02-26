@extends('user.form.layout')

@section('title', 'Team Members - SBL Student Portal')

@section('content')
<div class="container py-4">
    <!-- Header Section - Consistent with schooldata-list -->
    <div class="row mb-4">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}" class="text-decoration-none">
                        <i class="fas fa-home me-1"></i>Dashboard
                    </a></li>
                    <li class="breadcrumb-item"><a href="{{ route('student.event.histories') }}" class="text-decoration-none">
                        <i class="fas fa-history me-1"></i>Event Histories
                    </a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-users me-1"></i>Team Members
                    </li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="d-flex align-items-center mb-2">
                <div class="bg-primary bg-gradient rounded-circle p-3 me-3 shadow-sm">
                    <i class="fas fa-users text-white fa-2x"></i>
                </div>
                <div>
                    <h1 class="h3 mb-1 fw-bold">Team Members</h1>
                    <p class="text-muted mb-0">{{ $teamName ?? 'Team Details' }} - Complete list of all team members</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Container untuk pesan dinamis -->
    <div id="alert-container"></div>

    @php
        // ============ AMBIL DATA USER LOGIN ============
        $currentUser = Auth::user();
        $currentUserName = strtolower(trim($currentUser->name ?? ''));
        
        // ============ AMBIL DATA TEAM LENGKAP DENGAN LOGO ============
        $teamId = $teamId ?? request()->route('team_id') ?? request()->get('team_id');
        $teamData = null;
        $logoUrl = null;
        
        if ($teamId) {
            $teamData = \DB::table('team_list')->where('team_id', $teamId)->first();
            
            // Format logo URL seperti di schooldata-edit.blade.php
            if ($teamData && !empty($teamData->school_logo)) {
                $logoFile = basename($teamData->school_logo);
                if (file_exists(public_path('storage/school_logos/' . $logoFile))) {
                    $logoUrl = asset('storage/school_logos/' . $logoFile) . '?v=' . time();
                } elseif (file_exists(public_path('school_logos/' . $logoFile))) {
                    $logoUrl = asset('school_logos/' . $logoFile) . '?v=' . time();
                } elseif (Storage::disk('public')->exists('school_logos/' . $logoFile)) {
                    $logoUrl = Storage::url('school_logos/' . $logoFile) . '?v=' . time();
                }
            }
        }
        
        // Gunakan teamData jika ada, fallback ke data yang sudah ada
        $teamName = $teamName ?? ($teamData->school_name ?? 'Team Name');
        $competition = $competition ?? ($teamData->competition ?? 'HSBL');
        $teamCategory = $teamCategory ?? ($teamData->team_category ?? 'Basketball');
        $season = $season ?? ($teamData->season ?? date('Y'));
        $series = $series ?? ($teamData->series ?? '1');
        
        // ============ PISAHKAN PLAYER BERDASARKAN GENDER ============
        $playersMale = [];
        $playersFemale = [];
        
        if (!empty($players)) {
            foreach ($players as $player) {
                $gender = strtolower($player->gender ?? $player->category ?? '');
                if ($gender === 'male' || $gender === 'putra' || $gender === 'laki-laki') {
                    $playersMale[] = $player;
                } elseif ($gender === 'female' || $gender === 'putri' || $gender === 'perempuan') {
                    $playersFemale[] = $player;
                } else {
                    // Default masukkan ke male jika tidak jelas
                    $playersMale[] = $player;
                }
            }
        }
        
        // ============ AMBIL DATA DANCERS (TANPA PEMISAH GENDER) ============
        $dancersList = [];
        
        if (!empty($dancers)) {
            foreach ($dancers as $dancer) {
                $dancersList[] = $dancer;
            }
        }
        
        // Hitung total dancers per gender untuk statistik
        $totalDancersMale = 0;
        $totalDancersFemale = 0;
        
        foreach ($dancersList as $dancer) {
            $gender = strtolower($dancer->gender ?? $dancer->category ?? '');
            if ($gender === 'male' || $gender === 'putra' || $gender === 'laki-laki') {
                $totalDancersMale++;
            } elseif ($gender === 'female' || $gender === 'putri' || $gender === 'perempuan') {
                $totalDancersFemale++;
            } else {
                $totalDancersFemale++;
            }
        }
        
        $totalDancers = count($dancersList);
        
        // ============ PISAHKAN OFFICIALS BERDASARKAN KATEGORI ============
        $officialsBasketMale = [];
        $officialsBasketFemale = [];
        $officialsDancer = [];
        
        if (!empty($officials)) {
            foreach ($officials as $official) {
                $category = strtolower($official->category ?? $official->team_category ?? '');
                $gender = strtolower($official->gender ?? $official->category_gender ?? '');
                
                if (strpos($category, 'dancer') !== false || strpos($category, 'cheer') !== false) {
                    $officialsDancer[] = $official;
                } elseif ($gender === 'male' || $gender === 'putra' || $gender === 'laki-laki') {
                    $officialsBasketMale[] = $official;
                } elseif ($gender === 'female' || $gender === 'putri' || $gender === 'perempuan') {
                    $officialsBasketFemale[] = $official;
                } else {
                    $officialsBasketMale[] = $official;
                }
            }
        }
        
        // Hitung ulang total per kategori
        $totalPlayersMale = count($playersMale);
        $totalPlayersFemale = count($playersFemale);
        $totalPlayers = $totalPlayersMale + $totalPlayersFemale;
        
        $totalOfficialsBasketMale = count($officialsBasketMale);
        $totalOfficialsBasketFemale = count($officialsBasketFemale);
        $totalOfficialsDancer = count($officialsDancer);
        $totalOfficials = $totalOfficialsBasketMale + $totalOfficialsBasketFemale + $totalOfficialsDancer;
        
        // ============ FORMAT ROLE UNTUK OFFICIAL ============
        function formatTeamRole($role) {
            if (empty($role)) return 'Official';
            
            // Replace underscores with spaces and capitalize each word
            $formatted = str_replace('_', ' ', $role);
            $formatted = ucwords($formatted);
            
            return $formatted;
        }
        
        // ============ FORMAT GENDER UNTUK BADGE ============
        function formatGenderBadge($gender) {
            $genderLower = strtolower($gender ?? '');
            if ($genderLower === 'male' || $genderLower === 'putra' || $genderLower === 'laki-laki') {
                return ['text' => 'Male', 'class' => 'bg-success'];
            } elseif ($genderLower === 'female' || $genderLower === 'putri' || $genderLower === 'perempuan') {
                return ['text' => 'Female', 'class' => 'bg-info'];
            } else {
                return ['text' => 'Dancer', 'class' => 'bg-pink-600'];
            }
        }
        
        // ============ FUNGSI UNTUK MENDAPATKAN URL FOTO FORMAL - MENGIKUTI REVIEW-DATA ============
        function getFormalPhotoUrl($record, $recordType) {
            if (empty($record) || empty($record->formal_photo)) {
                return null;
            }
            
            try {
                $fileName = basename($record->formal_photo);
                
                // Cek di berbagai lokasi yang mungkin
                $locations = [
                    // Player documents
                    storage_path('app/public/player_docs/' . $fileName),
                    public_path('storage/player_docs/' . $fileName),
                    public_path('player_docs/' . $fileName),
                    
                    // Dancer documents
                    storage_path('app/public/dancer_docs/' . $fileName),
                    public_path('storage/dancer_docs/' . $fileName),
                    public_path('dancer_docs/' . $fileName),
                    
                    // Official documents
                    storage_path('app/public/uploads/officials/formal_photos/' . $fileName),
                    public_path('storage/uploads/officials/formal_photos/' . $fileName),
                    public_path('uploads/officials/formal_photos/' . $fileName),
                    
                    // Legacy paths
                    storage_path('app/player_docs/' . $fileName),
                    storage_path('app/dancer_docs/' . $fileName),
                    storage_path('app/uploads/officials/formal_photos/' . $fileName),
                    
                    // Direct path from database
                    base_path($record->formal_photo),
                    public_path($record->formal_photo)
                ];
                
                // Tambahkan path spesifik berdasarkan tipe record
                if ($recordType === 'player') {
                    array_unshift($locations, storage_path('app/public/player_docs/' . $fileName));
                    array_unshift($locations, public_path('storage/player_docs/' . $fileName));
                } elseif ($recordType === 'dancer') {
                    array_unshift($locations, storage_path('app/public/dancer_docs/' . $fileName));
                    array_unshift($locations, public_path('storage/dancer_docs/' . $fileName));
                } elseif ($recordType === 'official') {
                    array_unshift($locations, storage_path('app/public/uploads/officials/formal_photos/' . $fileName));
                    array_unshift($locations, public_path('storage/uploads/officials/formal_photos/' . $fileName));
                }
                
                // Hapus duplikat
                $locations = array_unique($locations);
                
                foreach ($locations as $location) {
                    if (file_exists($location) && !is_dir($location)) {
                        // Convert to URL
                        if (strpos($location, public_path()) === 0) {
                            $relativePath = str_replace(public_path(), '', $location);
                            return asset(ltrim($relativePath, '\\/')) . '?v=' . time();
                        } elseif (strpos($location, storage_path('app/public')) === 0) {
                            $relativePath = str_replace(storage_path('app/public'), '', $location);
                            return asset('storage' . str_replace('\\', '/', $relativePath)) . '?v=' . time();
                        } elseif (strpos($location, storage_path('app')) === 0) {
                            // Coba cek di public/storage dulu
                            $publicPath = public_path('storage/' . basename(dirname($location)) . '/' . $fileName);
                            if (file_exists($publicPath)) {
                                return asset('storage/' . basename(dirname($location)) . '/' . $fileName) . '?v=' . time();
                            }
                        }
                        return asset($record->formal_photo) . '?v=' . time();
                    }
                }
                
                // Fallback: coba langsung dari route view document seperti di review-data
                if (!empty($record->team_id) && !empty($recordType)) {
                    try {
                        $routeUrl = route('student.review.document.view', [
                            'teamId' => $record->team_id,
                            'documentType' => 'formal_photo'
                        ]);
                        return $routeUrl;
                    } catch (\Exception $e) {
                        // Abaikan jika route tidak tersedia
                    }
                }
                
            } catch (\Exception $e) {
                \Log::error('Error getting formal photo URL: ' . $e->getMessage());
            }
            
            return null;
        }
    @endphp

    <!-- Team Info Card with Logo -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-primary-light">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <!-- Team Logo -->
                        <div class="avatar-container me-4" style="width: 80px; height: 80px;">
                            @if($logoUrl)
                            <img src="{{ $logoUrl }}" 
                                 alt="{{ $teamName }}" 
                                 class="rounded-circle w-100 h-100 object-fit-cover border border-3 border-white shadow-sm"
                                 loading="lazy"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($teamName) }}&background=1565c0&color=fff&size=80&bold=true';">
                            @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center w-100 h-100 bg-primary bg-gradient text-white fw-bold shadow-sm" 
                                 style="font-size: 2rem;">
                                {{ strtoupper(substr($teamName, 0, 1)) }}
                            </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h4 class="mb-1 fw-bold">{{ $teamName }}</h4>
                                    <div class="d-flex flex-wrap gap-3">
                                        <span class="text-muted">
                                            <i class="fas fa-trophy me-1 text-primary"></i>{{ $competition }}
                                        </span>
                                        <span class="text-muted">
                                            <i class="fas fa-tag me-1 text-primary"></i>{{ $teamCategory }}
                                        </span>
                                        <span class="text-muted">
                                            <i class="fas fa-calendar me-1 text-primary"></i>{{ $season }}-{{ $series }}
                                        </span>
                                        @if($teamData && $teamData->referral_code)
                                        <span class="text-muted">
                                            <i class="fas fa-gift me-1 text-primary"></i>Ref: {{ $teamData->referral_code }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <a href="{{ route('student.event.histories') }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Histories
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Players</h6>
                            <h3 class="fw-bold mb-0 text-success">{{ $totalPlayers }}</h3>
                            <small class="text-muted">
                                <span class="text-success">{{ $totalPlayersMale }}</span> Male · 
                                <span class="text-info">{{ $totalPlayersFemale }}</span> Female
                            </small>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-basketball-ball text-success fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Dancers</h6>
                            <h3 class="fw-bold mb-0 text-pink-600">{{ $totalDancers }}</h3>
                            <small class="text-muted">
                                <span class="text-success">{{ $totalDancersMale }}</span> Male · 
                                <span class="text-info">{{ $totalDancersFemale }}</span> Female
                            </small>
                        </div>
                        <div class="bg-pink-100 rounded-circle p-3">
                            <i class="fas fa-music text-pink-600 fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Officials</h6>
                            <h3 class="fw-bold mb-0 text-warning">{{ $totalOfficials }}</h3>
                            <small class="text-muted">
                                <span class="text-success">{{ $totalOfficialsBasketMale }}</span> Basketball Male · 
                                <span class="text-info">{{ $totalOfficialsBasketFemale }}</span> Basketball Female ·
                                <span class="text-pink-600">{{ $totalOfficialsDancer }}</span> Dancer
                            </small>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-user-tie text-warning fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom p-0">
            <ul class="nav nav-tabs nav-fill flex-column flex-md-row" id="teamTabs" role="tablist">
                <!-- PLAYERS TAB -->
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="players-tab" data-bs-toggle="tab" data-bs-target="#players" type="button" role="tab" aria-controls="players" aria-selected="true">
                        <i class="fas fa-basketball-ball me-2 text-success"></i>Players
                        <span class="badge bg-success bg-opacity-10 text-success ms-2 rounded-pill">{{ $totalPlayers }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="dancers-tab" data-bs-toggle="tab" data-bs-target="#dancers" type="button" role="tab" aria-controls="dancers" aria-selected="false">
                        <i class="fas fa-music me-2 text-pink-600"></i>Dancers
                        <span class="badge bg-pink-100 text-pink-600 ms-2 rounded-pill">{{ $totalDancers }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="officials-tab" data-bs-toggle="tab" data-bs-target="#officials" type="button" role="tab" aria-controls="officials" aria-selected="false">
                        <i class="fas fa-user-tie me-2 text-warning"></i>Officials
                        <span class="badge bg-warning bg-opacity-10 text-warning ms-2 rounded-pill">{{ $totalOfficials }}</span>
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <!-- Tab Content -->
            <div class="tab-content" id="teamTabsContent">
                
                <!-- ============ PLAYERS TAB ============ -->
                <div class="tab-pane fade show active" id="players" role="tabpanel" aria-labelledby="players-tab">
                    <!-- SUB TABS UNTUK MALE / FEMALE -->
                    <ul class="nav nav-pills nav-justified mb-4" id="playerSubTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="players-male-tab" data-bs-toggle="pill" data-bs-target="#players-male" type="button" role="tab" aria-controls="players-male" aria-selected="true">
                                <i class="fas fa-male me-2"></i>Basketball Male
                                <span class="badge bg-success ms-2 rounded-pill">{{ $totalPlayersMale }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="players-female-tab" data-bs-toggle="pill" data-bs-target="#players-female" type="button" role="tab" aria-controls="players-female" aria-selected="false">
                                <i class="fas fa-female me-2"></i>Basketball Female
                                <span class="badge bg-info ms-2 rounded-pill">{{ $totalPlayersFemale }}</span>
                            </button>
                        </li>
                    </ul>

                    <!-- SUB TAB CONTENT -->
                    <div class="tab-content" id="playerSubTabsContent">
                        <!-- ============ MALE TAB ============ -->
                        <div class="tab-pane fade show active" id="players-male" role="tabpanel" aria-labelledby="players-male-tab">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0">
                                    <i class="fas fa-basketball-ball me-2 text-success"></i>Player Roster - Male
                                </h5>
                                <div class="input-group input-group-sm" style="width: 260px;">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="searchPlayersMale" 
                                           placeholder="Search players..." autocomplete="off">
                                </div>
                            </div>

                            @if(empty($playersMale) || count($playersMale) === 0)
                                <div class="text-center py-5">
                                    <div class="avatar-container mx-auto mb-4" style="width: 100px; height: 100px;">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                                            <i class="fas fa-basketball-ball text-muted fa-3x"></i>
                                        </div>
                                    </div>
                                    <h4 class="text-muted mb-3">No Male Players Found</h4>
                                    <p class="text-muted mb-0">This team doesn't have any male players registered yet.</p>
                                </div>
                            @else
                                <div class="row g-4" id="playersMaleContainer">
                                    @foreach($playersMale as $player)
                                    @php
                                        // ============ MENGGUNAKAN FUNGSI BARU UNTUK FOTO FORMAL ============
                                        $playerPhotoUrl = getFormalPhotoUrl($player, 'player');
                                        
                                        // CEK APAKAH INI USER YANG SEDANG LOGIN
                                        $isCurrentUser = false;
                                        if ($currentUserName && !empty($player->name)) {
                                            $playerName = strtolower(trim($player->name));
                                            $isCurrentUser = ($playerName === $currentUserName);
                                        }
                                    @endphp
                                    <div class="col-lg-6 col-xl-4 player-card-male" data-name="{{ strtolower($player->name ?? '') }}">
                                        <div class="card h-100 border-0 shadow-sm hover-card">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="avatar-container me-3" style="width: 70px; height: 70px;">
                                                        @if($playerPhotoUrl)
                                                            <img src="{{ $playerPhotoUrl }}" 
                                                                 alt="{{ $player->name }}"
                                                                 class="rounded-circle w-100 h-100 object-fit-cover border border-3 border-light shadow-sm"
                                                                 loading="lazy"
                                                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($player->name) }}&background=2e7d32&color=fff&size=70&bold=true';">
                                                        @else
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center w-100 h-100 bg-success bg-gradient text-white fw-bold shadow-sm" 
                                                             style="font-size: 1.5rem;">
                                                            {{ strtoupper(substr($player->name, 0, 1)) }}
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold">{{ $player->name }}</h6>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            <span class="badge bg-success rounded-pill">
                                                                Male
                                                            </span>
                                                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">
                                                                #{{ $player->jersey_number ?? '00' }}
                                                            </span>
                                                        </div>
                                                        @if($isCurrentUser)
                                                            <div class="mt-2">
                                                                <a href="{{ route('review.data') }}" class="btn btn-sm btn-warning text-white fw-bold">
                                                                    <i class="fas fa-edit me-1"></i>Edit Your Data
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-3 pb-2 border-bottom">
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-users me-1"></i>Team: 
                                                        <span class="fw-medium text-dark">{{ $teamName }}</span>
                                                    </small>
                                                    @if($player->role)
                                                    <small class="text-muted d-block mt-1">
                                                        <i class="fas fa-user-tag me-1"></i>Role: 
                                                        <span class="fw-medium text-dark">{{ ucfirst(str_replace('_', ' ', $player->role)) }}</span>
                                                    </small>
                                                    @endif
                                                </div>
                                                
                                                <div class="row g-2 mb-3">
                                                    @if($player->basketball_position)
                                                    <div class="col-6">
                                                        <div class="detail-item p-2 bg-light rounded">
                                                            <small class="text-muted d-block">Position</small>
                                                            <span class="fw-medium small">{{ $player->basketball_position }}</span>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @if($player->height)
                                                    <div class="col-6">
                                                        <div class="detail-item p-2 bg-light rounded">
                                                            <small class="text-muted d-block">Height</small>
                                                            <span class="fw-medium small">{{ $player->height }} cm</span>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    <div class="col-6">
                                                        <div class="detail-item p-2 bg-light rounded">
                                                            <small class="text-muted d-block">Jersey</small>
                                                            <span class="fw-medium small">#{{ $player->jersey_number ?? '00' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="contact-info mt-2">
                                                    @if($player->email)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-envelope text-muted me-2" style="width: 16px;"></i>
                                                        <small class="text-truncate">{{ $player->email }}</small>
                                                    </div>
                                                    @endif
                                                    @if($player->phone)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-phone text-muted me-2" style="width: 16px;"></i>
                                                        <small>{{ $player->phone }}</small>
                                                    </div>
                                                    @endif
                                                    <div class="d-flex gap-2 mt-2">
                                                        @if($player->instagram)
                                                        <a href="https://instagram.com/{{ $player->instagram }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px;" title="Instagram">
                                                            <i class="fab fa-instagram"></i>
                                                        </a>
                                                        @endif
                                                        @if($player->tiktok)
                                                        <a href="https://tiktok.com/@{{ $player->tiktok }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px;" title="TikTok">
                                                            <i class="fab fa-tiktok"></i>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- ============ FEMALE TAB ============ -->
                        <div class="tab-pane fade" id="players-female" role="tabpanel" aria-labelledby="players-female-tab">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0">
                                    <i class="fas fa-basketball-ball me-2 text-info"></i>Player Roster - Female
                                </h5>
                                <div class="input-group input-group-sm" style="width: 260px;">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="searchPlayersFemale" 
                                           placeholder="Search players..." autocomplete="off">
                                </div>
                            </div>

                            @if(empty($playersFemale) || count($playersFemale) === 0)
                                <div class="text-center py-5">
                                    <div class="avatar-container mx-auto mb-4" style="width: 100px; height: 100px;">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                                            <i class="fas fa-basketball-ball text-muted fa-3x"></i>
                                        </div>
                                    </div>
                                    <h4 class="text-muted mb-3">No Female Players Found</h4>
                                    <p class="text-muted mb-0">This team doesn't have any female players registered yet.</p>
                                </div>
                            @else
                                <div class="row g-4" id="playersFemaleContainer">
                                    @foreach($playersFemale as $player)
                                    @php
                                        // ============ MENGGUNAKAN FUNGSI BARU UNTUK FOTO FORMAL ============
                                        $playerPhotoUrl = getFormalPhotoUrl($player, 'player');
                                        
                                        // CEK APAKAH INI USER YANG SEDANG LOGIN
                                        $isCurrentUser = false;
                                        if ($currentUserName && !empty($player->name)) {
                                            $playerName = strtolower(trim($player->name));
                                            $isCurrentUser = ($playerName === $currentUserName);
                                        }
                                    @endphp
                                    <div class="col-lg-6 col-xl-4 player-card-female" data-name="{{ strtolower($player->name ?? '') }}">
                                        <div class="card h-100 border-0 shadow-sm hover-card">
                                            <div class="card-body p-4">
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="avatar-container me-3" style="width: 70px; height: 70px;">
                                                        @if($playerPhotoUrl)
                                                            <img src="{{ $playerPhotoUrl }}" 
                                                                 alt="{{ $player->name }}"
                                                                 class="rounded-circle w-100 h-100 object-fit-cover border border-3 border-light shadow-sm"
                                                                 loading="lazy"
                                                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($player->name) }}&background=0288d1&color=fff&size=70&bold=true';">
                                                        @else
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center w-100 h-100 bg-info bg-gradient text-white fw-bold shadow-sm" 
                                                             style="font-size: 1.5rem;">
                                                            {{ strtoupper(substr($player->name, 0, 1)) }}
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold">{{ $player->name }}</h6>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            <span class="badge bg-info rounded-pill">
                                                                Female
                                                            </span>
                                                            <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill">
                                                                #{{ $player->jersey_number ?? '00' }}
                                                            </span>
                                                        </div>
                                                        @if($isCurrentUser)
                                                            <div class="mt-2">
                                                                <a href="{{ route('review.data') }}" class="btn btn-sm btn-warning text-white fw-bold">
                                                                    <i class="fas fa-edit me-1"></i>Edit Your Data
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="mb-3 pb-2 border-bottom">
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-users me-1"></i>Team: 
                                                        <span class="fw-medium text-dark">{{ $teamName }}</span>
                                                    </small>
                                                    @if($player->role)
                                                    <small class="text-muted d-block mt-1">
                                                        <i class="fas fa-user-tag me-1"></i>Role: 
                                                        <span class="fw-medium text-dark">{{ ucfirst(str_replace('_', ' ', $player->role)) }}</span>
                                                    </small>
                                                    @endif
                                                </div>
                                                
                                                <div class="row g-2 mb-3">
                                                    @if($player->basketball_position)
                                                    <div class="col-6">
                                                        <div class="detail-item p-2 bg-light rounded">
                                                            <small class="text-muted d-block">Position</small>
                                                            <span class="fw-medium small">{{ $player->basketball_position }}</span>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @if($player->height)
                                                    <div class="col-6">
                                                        <div class="detail-item p-2 bg-light rounded">
                                                            <small class="text-muted d-block">Height</small>
                                                            <span class="fw-medium small">{{ $player->height }} cm</span>
                                                        </div>
                                                    </div>
                                                    @endif
                                                    <div class="col-6">
                                                        <div class="detail-item p-2 bg-light rounded">
                                                            <small class="text-muted d-block">Jersey</small>
                                                            <span class="fw-medium small">#{{ $player->jersey_number ?? '00' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="contact-info mt-2">
                                                    @if($player->email)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-envelope text-muted me-2" style="width: 16px;"></i>
                                                        <small class="text-truncate">{{ $player->email }}</small>
                                                    </div>
                                                    @endif
                                                    @if($player->phone)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-phone text-muted me-2" style="width: 16px;"></i>
                                                        <small>{{ $player->phone }}</small>
                                                    </div>
                                                    @endif
                                                    <div class="d-flex gap-2 mt-2">
                                                        @if($player->instagram)
                                                        <a href="https://instagram.com/{{ $player->instagram }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px;" title="Instagram">
                                                            <i class="fab fa-instagram"></i>
                                                        </a>
                                                        @endif
                                                        @if($player->tiktok)
                                                        <a href="https://tiktok.com/@{{ $player->tiktok }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px;" title="TikTok">
                                                            <i class="fab fa-tiktok"></i>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- ============ DANCERS TAB (GABUNGAN MALE & FEMALE) ============ -->
                <div class="tab-pane fade" id="dancers" role="tabpanel" aria-labelledby="dancers-tab">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="mb-0">
                            <i class="fas fa-music me-2 text-pink-600"></i>Dancer Squad
                            <span class="badge bg-pink-100 text-pink-600 ms-2 rounded-pill">{{ $totalDancers }} Total</span>
                        </h5>
                        <div class="d-flex gap-2">
                            <div class="input-group input-group-sm" style="width: 260px;">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                                <input type="text" class="form-control border-start-0 ps-0" id="searchDancers" 
                                       placeholder="Search dancers..." autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <!-- Statistik Dancer -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex gap-3 justify-content-start">
                                <span class="badge bg-success bg-opacity-10 text-success py-2 px-3 rounded-pill">
                                    <i class="fas fa-male me-1"></i> Male: {{ $totalDancersMale }}
                                </span>
                                <span class="badge bg-success bg-opacity-10 text-info py-2 px-3 rounded-pill">
                                    <i class="fas fa-female me-1"></i> Female: {{ $totalDancersFemale }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if(empty($dancersList) || count($dancersList) === 0)
                        <div class="text-center py-5">
                            <div class="avatar-container mx-auto mb-4" style="width: 100px; height: 100px;">
                                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                                    <i class="fas fa-music text-muted fa-3x"></i>
                                </div>
                            </div>
                            <h4 class="text-muted mb-3">No Dancers Found</h4>
                            <p class="text-muted mb-0">This team doesn't have any dancers registered yet.</p>
                        </div>
                    @else
                        <div class="row g-4" id="dancersContainer">
                            @foreach($dancersList as $dancer)
                            @php
                                // ============ MENGGUNAKAN FUNGSI BARU UNTUK FOTO FORMAL ============
                                $dancerPhotoUrl = getFormalPhotoUrl($dancer, 'dancer');
                                
                                // Format gender untuk badge
                                $genderBadge = formatGenderBadge($dancer->gender ?? $dancer->category ?? '');
                                
                                // CEK APAKAH INI USER YANG SEDANG LOGIN
                                $isCurrentUser = false;
                                if ($currentUserName && !empty($dancer->name)) {
                                    $dancerName = strtolower(trim($dancer->name));
                                    $isCurrentUser = ($dancerName === $currentUserName);
                                }
                            @endphp
                            <div class="col-lg-6 col-xl-4 dancer-card" data-name="{{ strtolower($dancer->name ?? '') }}">
                                <div class="card h-100 border-0 shadow-sm hover-card">
                                    <div class="card-body p-4">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar-container me-3" style="width: 70px; height: 70px;">
                                                @if($dancerPhotoUrl)
                                                    <img src="{{ $dancerPhotoUrl }}" 
                                                         alt="{{ $dancer->name }}"
                                                         class="rounded-circle w-100 h-100 object-fit-cover border border-3 border-light shadow-sm"
                                                         loading="lazy"
                                                         onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($dancer->name) }}&background={{ $genderBadge['class'] === 'bg-success' ? '2e7d32' : '0288d1' }}&color=fff&size=70&bold=true';">
                                                @else
                                                <div class="rounded-circle d-flex align-items-center justify-content-center w-100 h-100 {{ $genderBadge['class'] }} bg-gradient text-white fw-bold shadow-sm" 
                                                     style="font-size: 1.5rem;">
                                                    {{ strtoupper(substr($dancer->name, 0, 1)) }}
                                                </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold">{{ $dancer->name }}</h6>
                                                <div class="d-flex flex-wrap gap-1">
                                                    <span class="badge {{ $genderBadge['class'] }} rounded-pill">
                                                        <i class="fas fa-{{ $genderBadge['class'] === 'bg-success' ? 'male' : 'female' }} me-1"></i>
                                                        {{ $genderBadge['text'] }} Dancer
                                                    </span>
                                                </div>
                                                @if($isCurrentUser)
                                                    <div class="mt-2">
                                                        <a href="{{ route('review.data') }}" class="btn btn-sm btn-warning text-white fw-bold">
                                                            <i class="fas fa-edit me-1"></i>Edit Your Data
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="mb-3 pb-2 border-bottom">
                                            <small class="text-muted d-block">
                                                <i class="fas fa-users me-1"></i>Team: 
                                                <span class="fw-medium text-dark">{{ $teamName }}</span>
                                            </small>
                                            @if($dancer->role)
                                            <small class="text-muted d-block mt-1">
                                                <i class="fas fa-user-tag me-1"></i>Role: 
                                                <span class="fw-medium text-dark">{{ ucfirst(str_replace('_', ' ', $dancer->role)) }}</span>
                                            </small>
                                            @endif
                                        </div>
                                        
                                        <div class="contact-info mt-3">
                                            @if($dancer->email)
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-envelope text-muted me-2" style="width: 16px;"></i>
                                                <small class="text-truncate">{{ $dancer->email }}</small>
                                            </div>
                                            @endif
                                            @if($dancer->phone)
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-phone text-muted me-2" style="width: 16px;"></i>
                                                <small>{{ $dancer->phone }}</small>
                                            </div>
                                            @endif
                                            <div class="d-flex gap-2 mt-2">
                                                @if($dancer->instagram)
                                                <a href="https://instagram.com/{{ $dancer->instagram }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px;" title="Instagram">
                                                    <i class="fab fa-instagram"></i>
                                                </a>
                                                @endif
                                                @if($dancer->tiktok)
                                                <a href="https://tiktok.com/@{{ $dancer->tiktok }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px;" title="TikTok">
                                                    <i class="fab fa-tiktok"></i>
                                                </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- ============ OFFICIALS TAB ============ -->
                <div class="tab-pane fade" id="officials" role="tabpanel" aria-labelledby="officials-tab">
                    <!-- SUB TABS UNTUK OFFICIALS -->
                    <ul class="nav nav-pills nav-justified mb-4" id="officialSubTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="officials-basket-male-tab" data-bs-toggle="pill" data-bs-target="#officials-basket-male" type="button" role="tab" aria-controls="officials-basket-male" aria-selected="true">
                                <i class="fas fa-basketball-ball me-2 text-success"></i>Basketball Male
                                <span class="badge bg-success ms-2 rounded-pill">{{ $totalOfficialsBasketMale }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="officials-basket-female-tab" data-bs-toggle="pill" data-bs-target="#officials-basket-female" type="button" role="tab" aria-controls="officials-basket-female" aria-selected="false">
                                <i class="fas fa-basketball-ball me-2 text-info"></i>Basketball Female
                                <span class="badge bg-info ms-2 rounded-pill">{{ $totalOfficialsBasketFemale }}</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="officials-dancer-tab" data-bs-toggle="pill" data-bs-target="#officials-dancer" type="button" role="tab" aria-controls="officials-dancer" aria-selected="false">
                                <i class="fas fa-music me-2 text-pink-600"></i>Dancer
                                <span class="badge bg-pink-100 text-pink-600 ms-2 rounded-pill">{{ $totalOfficialsDancer }}</span>
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="officialSubTabsContent">
                        <!-- OFFICIALS BASKETBALL MALE -->
                        <div class="tab-pane fade show active" id="officials-basket-male" role="tabpanel" aria-labelledby="officials-basket-male-tab">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-tie me-2 text-success"></i>Official Staff - Basketball Male
                                </h5>
                                <div class="input-group input-group-sm" style="width: 260px;">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="searchOfficialsBasketMale" 
                                           placeholder="Search officials..." autocomplete="off">
                                </div>
                            </div>

                            @if(empty($officialsBasketMale) || count($officialsBasketMale) === 0)
                                <div class="text-center py-5">
                                    <div class="avatar-container mx-auto mb-4" style="width: 100px; height: 100px;">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                                            <i class="fas fa-user-tie text-muted fa-3x"></i>
                                        </div>
                                    </div>
                                    <h4 class="text-muted mb-3">No Basketball Male Officials Found</h4>
                                    <p class="text-muted mb-0">This team doesn't have any basketball male officials registered yet.</p>
                                </div>
                            @else
                                <div class="row g-4" id="officialsBasketMaleContainer">
                                    @foreach($officialsBasketMale as $official)
                                    @php
                                        // ============ MENGGUNAKAN FUNGSI BARU UNTUK FOTO FORMAL ============
                                        $officialPhotoUrl = getFormalPhotoUrl($official, 'official');
                                        
                                        // Format team role
                                        $teamRole = formatTeamRole($official->team_role ?? $official->role ?? '');
                                        
                                        // CEK APAKAH INI USER YANG SEDANG LOGIN
                                        $isCurrentUser = false;
                                        if ($currentUserName && !empty($official->name)) {
                                            $officialName = strtolower(trim($official->name));
                                            $isCurrentUser = ($officialName === $currentUserName);
                                        }
                                    @endphp
                                    <div class="col-lg-6 col-xl-4 official-card-basket-male" data-name="{{ strtolower($official->name ?? '') }}">
                                        <div class="card h-100 border-0 shadow-sm hover-card">
                                            <div class="card-body p-4">
                                                <!-- Header with Photo and Name -->
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="avatar-container me-3" style="width: 70px; height: 70px;">
                                                        @if($officialPhotoUrl)
                                                            <img src="{{ $officialPhotoUrl }}" 
                                                                 alt="{{ $official->name }}"
                                                                 class="rounded-circle w-100 h-100 object-fit-cover border border-3 border-light shadow-sm"
                                                                 loading="lazy"
                                                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($official->name) }}&background=2e7d32&color=fff&size=70&bold=true';">
                                                        @else
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center w-100 h-100 bg-success bg-gradient text-white fw-bold shadow-sm" 
                                                             style="font-size: 1.5rem;">
                                                            {{ strtoupper(substr($official->name, 0, 1)) }}
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold">{{ $official->name }}</h6>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            <span class="badge bg-success rounded-pill">
                                                                Basketball Male
                                                            </span>
                                                            @if($teamRole)
                                                            <span class="badge bg-warning bg-opacity-15 text-warning rounded-pill">
                                                                <i class="fas fa-user-tag me-1"></i>{{ $teamRole }}
                                                            </span>
                                                            @endif
                                                        </div>
                                                        @if($isCurrentUser)
                                                            <div class="mt-2">
                                                                <a href="{{ route('review.data') }}" class="btn btn-sm btn-warning text-white fw-bold">
                                                                    <i class="fas fa-edit me-1"></i>Edit Your Data
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Team Info with Team Role Highlight -->
                                                <div class="mb-3 pb-2 border-bottom">
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-users me-1"></i>Team: 
                                                        <span class="fw-medium text-dark">{{ $teamName }}</span>
                                                    </small>
                                                    @if($teamRole)
                                                    <div class="mt-2 p-2 bg-warning bg-opacity-10 rounded">
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-id-card me-1"></i>Team Role:
                                                        </small>
                                                        <span class="fw-bold text-warning">{{ $teamRole }}</span>
                                                    </div>
                                                    @elseif($official->role)
                                                    <small class="text-muted d-block mt-1">
                                                        <i class="fas fa-user-tag me-1"></i>Role: 
                                                        <span class="fw-medium text-dark">{{ ucfirst(str_replace('_', ' ', $official->role)) }}</span>
                                                    </small>
                                                    @endif
                                                </div>
                                                
                                                <!-- Contact Information -->
                                                <div class="contact-info mt-3">
                                                    @if($official->email)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-envelope text-muted me-2" style="width: 16px;"></i>
                                                        <small class="text-truncate">{{ $official->email }}</small>
                                                    </div>
                                                    @endif
                                                    @if($official->phone)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-phone text-muted me-2" style="width: 16px;"></i>
                                                        <small>{{ $official->phone }}</small>
                                                    </div>
                                                    @endif
                                                    <div class="d-flex gap-2 mt-2">
                                                        @if($official->instagram)
                                                        <a href="https://instagram.com/{{ $official->instagram }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px;" title="Instagram">
                                                            <i class="fab fa-instagram"></i>
                                                        </a>
                                                        @endif
                                                        @if($official->tiktok)
                                                        <a href="https://tiktok.com/@{{ $official->tiktok }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px;" title="TikTok">
                                                            <i class="fab fa-tiktok"></i>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- OFFICIALS BASKETBALL FEMALE -->
                        <div class="tab-pane fade" id="officials-basket-female" role="tabpanel" aria-labelledby="officials-basket-female-tab">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-tie me-2 text-info"></i>Official Staff - Basketball Female
                                </h5>
                                <div class="input-group input-group-sm" style="width: 260px;">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="searchOfficialsBasketFemale" 
                                           placeholder="Search officials..." autocomplete="off">
                                </div>
                            </div>

                            @if(empty($officialsBasketFemale) || count($officialsBasketFemale) === 0)
                                <div class="text-center py-5">
                                    <div class="avatar-container mx-auto mb-4" style="width: 100px; height: 100px;">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                                            <i class="fas fa-user-tie text-muted fa-3x"></i>
                                        </div>
                                    </div>
                                    <h4 class="text-muted mb-3">No Basketball Female Officials Found</h4>
                                    <p class="text-muted mb-0">This team doesn't have any basketball female officials registered yet.</p>
                                </div>
                            @else
                                <div class="row g-4" id="officialsBasketFemaleContainer">
                                    @foreach($officialsBasketFemale as $official)
                                    @php
                                        // ============ MENGGUNAKAN FUNGSI BARU UNTUK FOTO FORMAL ============
                                        $officialPhotoUrl = getFormalPhotoUrl($official, 'official');
                                        
                                        // Format team role
                                        $teamRole = formatTeamRole($official->team_role ?? $official->role ?? '');
                                        
                                        // CEK APAKAH INI USER YANG SEDANG LOGIN
                                        $isCurrentUser = false;
                                        if ($currentUserName && !empty($official->name)) {
                                            $officialName = strtolower(trim($official->name));
                                            $isCurrentUser = ($officialName === $currentUserName);
                                        }
                                    @endphp
                                    <div class="col-lg-6 col-xl-4 official-card-basket-female" data-name="{{ strtolower($official->name ?? '') }}">
                                        <div class="card h-100 border-0 shadow-sm hover-card">
                                            <div class="card-body p-4">
                                                <!-- Header with Photo and Name -->
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="avatar-container me-3" style="width: 70px; height: 70px;">
                                                        @if($officialPhotoUrl)
                                                            <img src="{{ $officialPhotoUrl }}" 
                                                                 alt="{{ $official->name }}"
                                                                 class="rounded-circle w-100 h-100 object-fit-cover border border-3 border-light shadow-sm"
                                                                 loading="lazy"
                                                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($official->name) }}&background=0288d1&color=fff&size=70&bold=true';">
                                                        @else
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center w-100 h-100 bg-info bg-gradient text-white fw-bold shadow-sm" 
                                                             style="font-size: 1.5rem;">
                                                            {{ strtoupper(substr($official->name, 0, 1)) }}
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold">{{ $official->name }}</h6>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            <span class="badge bg-info rounded-pill">
                                                                Basketball Female
                                                            </span>
                                                            @if($teamRole)
                                                            <span class="badge bg-warning bg-opacity-15 text-warning rounded-pill">
                                                                <i class="fas fa-user-tag me-1"></i>{{ $teamRole }}
                                                            </span>
                                                            @endif
                                                        </div>
                                                        @if($isCurrentUser)
                                                            <div class="mt-2">
                                                                <a href="{{ route('review.data') }}" class="btn btn-sm btn-warning text-white fw-bold">
                                                                    <i class="fas fa-edit me-1"></i>Edit Your Data
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Team Info with Team Role Highlight -->
                                                <div class="mb-3 pb-2 border-bottom">
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-users me-1"></i>Team: 
                                                        <span class="fw-medium text-dark">{{ $teamName }}</span>
                                                    </small>
                                                    @if($teamRole)
                                                    <div class="mt-2 p-2 bg-warning bg-opacity-10 rounded">
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-id-card me-1"></i>Team Role:
                                                        </small>
                                                        <span class="fw-bold text-warning">{{ $teamRole }}</span>
                                                    </div>
                                                    @elseif($official->role)
                                                    <small class="text-muted d-block mt-1">
                                                        <i class="fas fa-user-tag me-1"></i>Role: 
                                                        <span class="fw-medium text-dark">{{ ucfirst(str_replace('_', ' ', $official->role)) }}</span>
                                                    </small>
                                                    @endif
                                                </div>
                                                
                                                <!-- Contact Information -->
                                                <div class="contact-info mt-3">
                                                    @if($official->email)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-envelope text-muted me-2" style="width: 16px;"></i>
                                                        <small class="text-truncate">{{ $official->email }}</small>
                                                    </div>
                                                    @endif
                                                    @if($official->phone)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-phone text-muted me-2" style="width: 16px;"></i>
                                                        <small>{{ $official->phone }}</small>
                                                    </div>
                                                    @endif
                                                    <div class="d-flex gap-2 mt-2">
                                                        @if($official->instagram)
                                                        <a href="https://instagram.com/{{ $official->instagram }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px;" title="Instagram">
                                                            <i class="fab fa-instagram"></i>
                                                        </a>
                                                        @endif
                                                        @if($official->tiktok)
                                                        <a href="https://tiktok.com/@{{ $official->tiktok }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px;" title="TikTok">
                                                            <i class="fab fa-tiktok"></i>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- OFFICIALS DANCER -->
                        <div class="tab-pane fade" id="officials-dancer" role="tabpanel" aria-labelledby="officials-dancer-tab">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-tie me-2 text-pink-600"></i>Official Staff - Dancer
                                </h5>
                                <div class="input-group input-group-sm" style="width: 260px;">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0" id="searchOfficialsDancer" 
                                           placeholder="Search officials..." autocomplete="off">
                                </div>
                            </div>

                            @if(empty($officialsDancer) || count($officialsDancer) === 0)
                                <div class="text-center py-5">
                                    <div class="avatar-container mx-auto mb-4" style="width: 100px; height: 100px;">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                                            <i class="fas fa-user-tie text-muted fa-3x"></i>
                                        </div>
                                    </div>
                                    <h4 class="text-muted mb-3">No Dancer Officials Found</h4>
                                    <p class="text-muted mb-0">This team doesn't have any dancer officials registered yet.</p>
                                </div>
                            @else
                                <div class="row g-4" id="officialsDancerContainer">
                                    @foreach($officialsDancer as $official)
                                    @php
                                        // ============ MENGGUNAKAN FUNGSI BARU UNTUK FOTO FORMAL ============
                                        $officialPhotoUrl = getFormalPhotoUrl($official, 'official');
                                        
                                        // Format team role
                                        $teamRole = formatTeamRole($official->team_role ?? $official->role ?? '');
                                        
                                        // CEK APAKAH INI USER YANG SEDANG LOGIN
                                        $isCurrentUser = false;
                                        if ($currentUserName && !empty($official->name)) {
                                            $officialName = strtolower(trim($official->name));
                                            $isCurrentUser = ($officialName === $currentUserName);
                                        }
                                    @endphp
                                    <div class="col-lg-6 col-xl-4 official-card-dancer" data-name="{{ strtolower($official->name ?? '') }}">
                                        <div class="card h-100 border-0 shadow-sm hover-card">
                                            <div class="card-body p-4">
                                                <!-- Header with Photo and Name -->
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="avatar-container me-3" style="width: 70px; height: 70px;">
                                                        @if($officialPhotoUrl)
                                                            <img src="{{ $officialPhotoUrl }}" 
                                                                 alt="{{ $official->name }}"
                                                                 class="rounded-circle w-100 h-100 object-fit-cover border border-3 border-light shadow-sm"
                                                                 loading="lazy"
                                                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($official->name) }}&background=d81b60&color=fff&size=70&bold=true';">
                                                        @else
                                                        <div class="rounded-circle d-flex align-items-center justify-content-center w-100 h-100 bg-pink-600 text-white fw-bold shadow-sm" 
                                                             style="font-size: 1.5rem;">
                                                            {{ strtoupper(substr($official->name, 0, 1)) }}
                                                        </div>
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold">{{ $official->name }}</h6>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            <span class="badge bg-pink-100 text-pink-600 rounded-pill">
                                                                Dancer Official
                                                            </span>
                                                            @if($teamRole)
                                                            <span class="badge bg-warning bg-opacity-15 text-warning rounded-pill">
                                                                <i class="fas fa-user-tag me-1"></i>{{ $teamRole }}
                                                            </span>
                                                            @endif
                                                        </div>
                                                        @if($isCurrentUser)
                                                            <div class="mt-2">
                                                                <a href="{{ route('review.data') }}" class="btn btn-sm btn-warning text-white fw-bold">
                                                                    <i class="fas fa-edit me-1"></i>Edit Your Data
                                                                </a>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Team Info with Team Role Highlight -->
                                                <div class="mb-3 pb-2 border-bottom">
                                                    <small class="text-muted d-block">
                                                        <i class="fas fa-users me-1"></i>Team: 
                                                        <span class="fw-medium text-dark">{{ $teamName }}</span>
                                                    </small>
                                                    @if($teamRole)
                                                    <div class="mt-2 p-2 bg-warning bg-opacity-10 rounded">
                                                        <small class="text-muted d-block">
                                                            <i class="fas fa-id-card me-1"></i>Team Role:
                                                        </small>
                                                        <span class="fw-bold text-warning">{{ $teamRole }}</span>
                                                    </div>
                                                    @elseif($official->role)
                                                    <small class="text-muted d-block mt-1">
                                                        <i class="fas fa-user-tag me-1"></i>Role: 
                                                        <span class="fw-medium text-dark">{{ ucfirst(str_replace('_', ' ', $official->role)) }}</span>
                                                    </small>
                                                    @endif
                                                </div>
                                                
                                                <!-- Contact Information -->
                                                <div class="contact-info mt-3">
                                                    @if($official->email)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-envelope text-muted me-2" style="width: 16px;"></i>
                                                        <small class="text-truncate">{{ $official->email }}</small>
                                                    </div>
                                                    @endif
                                                    @if($official->phone)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-phone text-muted me-2" style="width: 16px;"></i>
                                                        <small>{{ $official->phone }}</small>
                                                    </div>
                                                    @endif
                                                    <div class="d-flex gap-2 mt-2">
                                                        @if($official->instagram)
                                                        <a href="https://instagram.com/{{ $official->instagram }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px;" title="Instagram">
                                                            <i class="fab fa-instagram"></i>
                                                        </a>
                                                        @endif
                                                        @if($official->tiktok)
                                                        <a href="https://tiktok.com/@{{ $official->tiktok }}" target="_blank" class="btn btn-sm btn-outline-secondary rounded-circle" style="width: 32px; height: 32px;" title="TikTok">
                                                            <i class="fab fa-tiktok"></i>
                                                        </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Footer Info -->
        <div class="card-footer bg-white border-top p-4">
            <div class="alert alert-info alert-hsbl mb-0">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-lg me-3"></i>
                    <div>
                        <strong class="d-block mb-1">Team Member Information</strong>
                        <p class="mb-0 small">This page displays all members registered under this team. Team logo and details are retrieved from team_list table. Officials now display Team Role from official_list table. Formal photos are displayed using the same system as Review My Data page.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Search Players Male
    const searchPlayersMale = document.getElementById('searchPlayersMale');
    if (searchPlayersMale) {
        searchPlayersMale.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.player-card-male');
            cards.forEach(card => {
                const name = card.getAttribute('data-name') || '';
                card.style.display = name.includes(filter) ? "" : "none";
            });
        });
    }
    
    // Search Players Female
    const searchPlayersFemale = document.getElementById('searchPlayersFemale');
    if (searchPlayersFemale) {
        searchPlayersFemale.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.player-card-female');
            cards.forEach(card => {
                const name = card.getAttribute('data-name') || '';
                card.style.display = name.includes(filter) ? "" : "none";
            });
        });
    }
    
    // Search Dancers (Gabungan)
    const searchDancers = document.getElementById('searchDancers');
    if (searchDancers) {
        searchDancers.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.dancer-card');
            cards.forEach(card => {
                const name = card.getAttribute('data-name') || '';
                card.style.display = name.includes(filter) ? "" : "none";
            });
        });
    }
    
    // Search Officials Basketball Male
    const searchOfficialsBasketMale = document.getElementById('searchOfficialsBasketMale');
    if (searchOfficialsBasketMale) {
        searchOfficialsBasketMale.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.official-card-basket-male');
            cards.forEach(card => {
                const name = card.getAttribute('data-name') || '';
                card.style.display = name.includes(filter) ? "" : "none";
            });
        });
    }
    
    // Search Officials Basketball Female
    const searchOfficialsBasketFemale = document.getElementById('searchOfficialsBasketFemale');
    if (searchOfficialsBasketFemale) {
        searchOfficialsBasketFemale.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.official-card-basket-female');
            cards.forEach(card => {
                const name = card.getAttribute('data-name') || '';
                card.style.display = name.includes(filter) ? "" : "none";
            });
        });
    }
    
    // Search Officials Dancer
    const searchOfficialsDancer = document.getElementById('searchOfficialsDancer');
    if (searchOfficialsDancer) {
        searchOfficialsDancer.addEventListener('keyup', function() {
            const filter = this.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.official-card-dancer');
            cards.forEach(card => {
                const name = card.getAttribute('data-name') || '';
                card.style.display = name.includes(filter) ? "" : "none";
            });
        });
    }
    
    // Debug: Log semua foto yang gagal load
    const images = document.querySelectorAll('img[onerror]');
    images.forEach(img => {
        img.addEventListener('error', function(e) {
            console.log('Image failed to load:', {
                src: this.src,
                alt: this.alt,
                card: this.closest('.card')?.querySelector('h6')?.textContent
            });
        });
    });
});
</script>

<style>
/* Pink palette untuk Dancer */
.bg-pink-100 {
    background-color: #fce4ec !important;
}

.text-pink-600 {
    color: #d81b60 !important;
}

.bg-pink-600 {
    background-color: #d81b60 !important;
}

/* Info palette untuk Female */
.bg-info {
    background-color: #0288d1 !important;
}

/* Warning opacity */
.bg-warning.bg-opacity-15 {
    background-color: rgba(237, 108, 2, 0.15) !important;
}

.bg-warning.bg-opacity-10 {
    background-color: rgba(237, 108, 2, 0.1) !important;
}

/* Card styles */
.card {
    border-radius: 10px;
    transition: all 0.3s ease;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 20px rgba(0,0,0,0.1) !important;
}

/* Team Info Card */
.bg-gradient-primary-light {
    background: linear-gradient(45deg, #f8faff, #ffffff);
}

/* Tab styles */
.nav-tabs {
    border-bottom: 1px solid #dee2e6;
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    padding: 1rem 1.5rem;
    font-weight: 600;
    color: #6c757d;
    transition: all 0.2s;
}

.nav-tabs .nav-link:hover {
    border-bottom-color: #dee2e6;
    color: #495057;
}

.nav-tabs .nav-link.active {
    border-bottom: 3px solid #1565c0;
    color: #1565c0;
    background-color: transparent;
}

.nav-tabs .nav-link i {
    margin-right: 8px;
}

/* Nav Pills untuk sub tabs */
.nav-pills .nav-link {
    border-radius: 30px;
    padding: 0.6rem 1.5rem;
    font-weight: 600;
    color: #6c757d;
    background-color: #f8f9fa;
    margin: 0 5px;
}

.nav-pills .nav-link.active {
    background-color: #1565c0;
    color: white;
}

.nav-pills .nav-link.active i {
    color: white;
}

.nav-pills .nav-link i {
    margin-right: 6px;
}

/* Badge styles */
.badge {
    font-weight: 500;
    font-size: 0.7rem;
    padding: 0.35rem 0.65rem;
}

/* Avatar styles */
.avatar-container {
    position: relative;
}

.object-fit-cover {
    object-fit: cover;
}

/* Detail item */
.detail-item {
    border-radius: 8px;
}

/* Contact info */
.contact-info small {
    font-size: 0.8rem;
}

/* Button Edit Your Data */
.btn-warning {
    background-color: #ff9800;
    border-color: #ff9800;
    transition: all 0.3s ease;
}

.btn-warning:hover {
    background-color: #f57c00;
    border-color: #f57c00;
    transform: scale(1.05);
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.card {
    animation: fadeIn 0.5s ease-out;
}

/* Alert styles */
.alert-hsbl {
    border-left-width: 4px;
    border-radius: 8px;
}

/* Responsive styles */
@media (max-width: 768px) {
    .nav-tabs .nav-link {
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
    }
    
    .nav-pills .nav-link {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }
    
    .card-body.p-4 {
        padding: 1.5rem !important;
    }
    
    .d-flex.justify-content-between.align-items-center.mb-4 {
        flex-direction: column;
        align-items: flex-start !important;
        gap: 1rem;
    }
    
    .input-group {
        width: 100% !important;
    }
}

@media (max-width: 576px) {
    .avatar-container {
        width: 60px !important;
        height: 60px !important;
    }
    
    .btn-sm.rounded-circle {
        width: 28px !important;
        height: 28px !important;
    }
}
</style>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection