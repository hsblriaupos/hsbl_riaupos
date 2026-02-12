@extends('user.form.layout')

@section('title', 'Review Data - HSBL Student Portal')

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
                        <a href="{{ route('schooldata.list') }}" class="text-decoration-none">
                            <i class="fas fa-school me-1"></i>My Schools
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-clipboard-list me-1"></i>Review Data
                    </li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary bg-gradient rounded-circle p-3 me-3 shadow-sm">
                    <i class="fas fa-clipboard-check text-white fa-2x"></i>
                </div>
                <div>
                    <h1 class="h3 mb-1 fw-bold">Review Data</h1>
                    <p class="text-muted mb-0">Complete review of your personal data and registration information</p>
                </div>
            </div>
        </div>
    </div>

    @php
        $user = Auth::user();
        $userName = $user->name;
        
        // Ambil data dari ketiga tabel berdasarkan name yang sama dengan users.name
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
    @endphp

    <!-- Alert Container -->
    <div id="alert-container"></div>

    @if(!$activeData)
    <!-- Empty State - No Data Found -->
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <div class="avatar-container mx-auto mb-4" style="width: 120px; height: 120px;">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                    <i class="fas fa-database text-muted fa-4x"></i>
                </div>
            </div>
            <h4 class="text-muted mb-3">No Registration Data Found</h4>
            <p class="text-muted mb-4">You are not registered as Player, Dancer, or Official yet.</p>
            <div class="alert alert-info alert-hsbl mx-auto" style="max-width: 600px;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-lg me-3"></i>
                    <div class="text-start">
                        <strong>How to get registered?</strong><br>
                        <small>Register as a Player, Dancer, or Official through the team registration form. Your data will appear here automatically after registration.</small>
                    </div>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('form.team.choice') }}" class="btn btn-primary px-4">
                    <i class="fas fa-plus-circle me-1"></i>Register Now
                </a>
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary px-4 ms-2">
                    <i class="fas fa-arrow-left me-1"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </div>
    @else
    <!-- Statistics Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Role Type</h6>
                            <h4 class="fw-bold mb-0 text-primary text-uppercase">{{ $activeTable }}</h4>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            @if($activeTable == 'player')
                                <i class="fas fa-basketball-ball text-primary fa-lg"></i>
                            @elseif($activeTable == 'dancer')
                                <i class="fas fa-music text-pink-600 fa-lg"></i>
                            @else
                                <i class="fas fa-user-tie text-warning fa-lg"></i>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Team ID</h6>
                            <h4 class="fw-bold mb-0 text-success">{{ $activeData->team_id ?? 'N/A' }}</h4>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-users text-success fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Verification</h6>
                            @php
                                $status = $activeData->verification_status ?? 'pending';
                                $badgeClass = $status == 'verified' ? 'success' : ($status == 'rejected' ? 'danger' : 'warning');
                            @endphp
                            <h4 class="fw-bold mb-0 text-{{ $badgeClass }} text-uppercase">{{ $status }}</h4>
                        </div>
                        <div class="bg-{{ $badgeClass }} bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-check-circle text-{{ $badgeClass }} fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">School</h6>
                            <h4 class="fw-bold mb-0 text-info">{{ $activeData->school_name ?? $activeData->school_id ?? 'N/A' }}</h4>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-school text-info fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Role Tabs -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $activeTable == 'player' ? 'active' : '' }}" 
                       id="player-tab" 
                       data-bs-toggle="tab" 
                       href="#player-data" 
                       role="tab"
                       aria-selected="{{ $activeTable == 'player' ? 'true' : 'false' }}">
                        <i class="fas fa-basketball-ball me-2"></i>Player Data
                        @if($playerData) <span class="badge bg-success ms-2">✓</span> @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTable == 'dancer' ? 'active' : '' }}" 
                       id="dancer-tab" 
                       data-bs-toggle="tab" 
                       href="#dancer-data" 
                       role="tab"
                       aria-selected="{{ $activeTable == 'dancer' ? 'true' : 'false' }}">
                        <i class="fas fa-music me-2"></i>Dancer Data
                        @if($dancerData) <span class="badge bg-success ms-2">✓</span> @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $activeTable == 'official' ? 'active' : '' }}" 
                       id="official-tab" 
                       data-bs-toggle="tab" 
                       href="#official-data" 
                       role="tab"
                       aria-selected="{{ $activeTable == 'official' ? 'true' : 'false' }}">
                        <i class="fas fa-user-tie me-2"></i>Official Data
                        @if($officialData) <span class="badge bg-success ms-2">✓</span> @endif
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content">
        <!-- PLAYER DATA TAB -->
        <div class="tab-pane fade {{ $activeTable == 'player' ? 'show active' : '' }}" id="player-data" role="tabpanel">
            @if($playerData)
            <div class="row">
                <!-- Personal Information -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-user-circle me-2 text-primary"></i>Personal Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">Player ID</td>
                                    <td class="fw-medium">: {{ $playerData->id ?? $playerData->player_id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Full Name</td>
                                    <td class="fw-medium">: {{ $playerData->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">NIK</td>
                                    <td class="fw-medium">: {{ $playerData->nik ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Birth Date</td>
                                    <td class="fw-medium">: {{ $playerData->birthdate ? \Carbon\Carbon::parse($playerData->birthdate)->format('d M Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Gender</td>
                                    <td class="fw-medium">: {{ $playerData->gender == 'L' ? 'Male' : ($playerData->gender == 'P' ? 'Female' : '-') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email</td>
                                    <td class="fw-medium">: {{ $playerData->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Phone</td>
                                    <td class="fw-medium">: {{ $playerData->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Grade</td>
                                    <td class="fw-medium">: {{ $playerData->grade ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">STTB Year</td>
                                    <td class="fw-medium">: {{ $playerData->sttb_year ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Physical & Basketball Information -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-basketball-ball me-2 text-success"></i>Physical & Basketball Info
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">Height (cm)</td>
                                    <td class="fw-medium">: {{ $playerData->height ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Weight (kg)</td>
                                    <td class="fw-medium">: {{ $playerData->weight ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">T-Shirt Size</td>
                                    <td class="fw-medium">: {{ $playerData->tshirt_size ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Shoes Size</td>
                                    <td class="fw-medium">: {{ $playerData->shoes_size ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Basketball Position</td>
                                    <td class="fw-medium">: {{ $playerData->basketball_position ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Jersey Number</td>
                                    <td class="fw-medium">: {{ $playerData->jersey_number ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Category</td>
                                    <td class="fw-medium">: {{ $playerData->category ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Role</td>
                                    <td class="fw-medium">: {{ $playerData->role ?? 'Player' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- School Information -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-school me-2 text-info"></i>School Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">School ID</td>
                                    <td class="fw-medium">: {{ $playerData->school_id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">School Name</td>
                                    <td class="fw-medium">: {{ $playerData->school_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Grade</td>
                                    <td class="fw-medium">: {{ $playerData->grade ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">STTB Year</td>
                                    <td class="fw-medium">: {{ $playerData->sttb_year ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-share-alt me-2 text-purple"></i>Social Media
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">Instagram</td>
                                    <td class="fw-medium">: 
                                        @if($playerData->instagram)
                                            <a href="https://instagram.com/{{ $playerData->instagram }}" target="_blank" class="text-decoration-none">
                                                {{ $playerData->instagram }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">TikTok</td>
                                    <td class="fw-medium">: 
                                        @if($playerData->tiktok)
                                            <a href="https://tiktok.com/@{{ $playerData->tiktok }}" target="_blank" class="text-decoration-none">
                                                {{ $playerData->tiktok }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Parent Information -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-users me-2 text-warning"></i>Parent Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">Father's Name</td>
                                    <td class="fw-medium">: {{ $playerData->father_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Father's Phone</td>
                                    <td class="fw-medium">: {{ $playerData->father_phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Mother's Name</td>
                                    <td class="fw-medium">: {{ $playerData->mother_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Mother's Phone</td>
                                    <td class="fw-medium">: {{ $playerData->mother_phone ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-file-alt me-2 text-danger"></i>Documents
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="50%" class="text-muted">Birth Certificate</td>
                                    <td>: 
                                        @if($playerData->birth_certificate)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">KK (Family Card)</td>
                                    <td>: 
                                        @if($playerData->kk)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Report Identity</td>
                                    <td>: 
                                        @if($playerData->report_identity)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">SHUN</td>
                                    <td>: 
                                        @if($playerData->shun)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Last Report Card</td>
                                    <td>: 
                                        @if($playerData->last_report_card)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Formal Photo</td>
                                    <td>: 
                                        @if($playerData->formal_photo)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Assignment Letter</td>
                                    <td>: 
                                        @if($playerData->assignment_letter)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Payment Proof</td>
                                    <td>: 
                                        @if($playerData->payment_proof)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Status Information -->
                <div class="col-12 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2 text-secondary"></i>Status Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-check-circle fa-2x {{ $playerData->is_finalized ? 'text-success' : 'text-warning' }}"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Finalized Status</small>
                                            <strong>{{ $playerData->is_finalized ? 'Yes' : 'No' }}</strong>
                                            @if($playerData->finalized_at)
                                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($playerData->finalized_at)->format('d M Y H:i') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-lock fa-2x {{ $playerData->unlocked_by_admin ? 'text-danger' : 'text-success' }}"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Unlock Status</small>
                                            <strong>{{ $playerData->unlocked_by_admin ? 'Unlocked by Admin' : 'Locked' }}</strong>
                                            @if($playerData->unlocked_at)
                                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($playerData->unlocked_at)->format('d M Y H:i') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-clock fa-2x text-info"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Last Updated</small>
                                            <strong>{{ $playerData->updated_at ? \Carbon\Carbon::parse($playerData->updated_at)->format('d M Y H:i') : '-' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-basketball-ball text-muted fa-4x mb-3"></i>
                    <h5 class="text-muted">No Player Data Available</h5>
                    <p class="text-muted">You are not registered as a Player yet.</p>
                </div>
            </div>
            @endif
        </div>

        <!-- DANCER DATA TAB -->
        <div class="tab-pane fade {{ $activeTable == 'dancer' ? 'show active' : '' }}" id="dancer-data" role="tabpanel">
            @if($dancerData)
            <div class="row">
                <!-- Personal Information -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-user-circle me-2 text-pink-600"></i>Personal Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">Dancer ID</td>
                                    <td class="fw-medium">: {{ $dancerData->dancer_id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Full Name</td>
                                    <td class="fw-medium">: {{ $dancerData->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">NIK</td>
                                    <td class="fw-medium">: {{ $dancerData->nik ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Birth Date</td>
                                    <td class="fw-medium">: {{ $dancerData->birthdate ? \Carbon\Carbon::parse($dancerData->birthdate)->format('d M Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Gender</td>
                                    <td class="fw-medium">: {{ $dancerData->gender == 'L' ? 'Male' : ($dancerData->gender == 'P' ? 'Female' : '-') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email</td>
                                    <td class="fw-medium">: {{ $dancerData->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Phone</td>
                                    <td class="fw-medium">: {{ $dancerData->phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Grade</td>
                                    <td class="fw-medium">: {{ $dancerData->grade ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">STTB Year</td>
                                    <td class="fw-medium">: {{ $dancerData->sttb_year ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Physical & Dance Information -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-music me-2 text-pink-600"></i>Physical & Dance Info
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">Height (cm)</td>
                                    <td class="fw-medium">: {{ $dancerData->height ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Weight (kg)</td>
                                    <td class="fw-medium">: {{ $dancerData->weight ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">T-Shirt Size</td>
                                    <td class="fw-medium">: {{ $dancerData->tshirt_size ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Shoes Size</td>
                                    <td class="fw-medium">: {{ $dancerData->shoes_size ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Role</td>
                                    <td class="fw-medium">: {{ $dancerData->role ?? 'Dancer' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- School Information -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-school me-2 text-info"></i>School Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">School Name</td>
                                    <td class="fw-medium">: {{ $dancerData->school_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Grade</td>
                                    <td class="fw-medium">: {{ $dancerData->grade ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">STTB Year</td>
                                    <td class="fw-medium">: {{ $dancerData->sttb_year ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-share-alt me-2 text-purple"></i>Social Media
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">Instagram</td>
                                    <td class="fw-medium">: 
                                        @if($dancerData->instagram)
                                            <a href="https://instagram.com/{{ $dancerData->instagram }}" target="_blank" class="text-decoration-none">
                                                {{ $dancerData->instagram }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">TikTok</td>
                                    <td class="fw-medium">: 
                                        @if($dancerData->tiktok)
                                            <a href="https://tiktok.com/@{{ $dancerData->tiktok }}" target="_blank" class="text-decoration-none">
                                                {{ $dancerData->tiktok }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Parent Information -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-users me-2 text-warning"></i>Parent Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">Father's Name</td>
                                    <td class="fw-medium">: {{ $dancerData->father_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Father's Phone</td>
                                    <td class="fw-medium">: {{ $dancerData->father_phone ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Mother's Name</td>
                                    <td class="fw-medium">: {{ $dancerData->mother_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Mother's Phone</td>
                                    <td class="fw-medium">: {{ $dancerData->mother_phone ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-file-alt me-2 text-danger"></i>Documents
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="50%" class="text-muted">Birth Certificate</td>
                                    <td>: 
                                        @if($dancerData->birth_certificate)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">KK (Family Card)</td>
                                    <td>: 
                                        @if($dancerData->kk)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">SHUN</td>
                                    <td>: 
                                        @if($dancerData->shun)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Report Identity</td>
                                    <td>: 
                                        @if($dancerData->report_identity)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Last Report Card</td>
                                    <td>: 
                                        @if($dancerData->last_report_card)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Formal Photo</td>
                                    <td>: 
                                        @if($dancerData->formal_photo)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Assignment Letter</td>
                                    <td>: 
                                        @if($dancerData->assignment_letter)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Status Information -->
                <div class="col-12 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2 text-secondary"></i>Status Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-check-circle fa-2x {{ $dancerData->verification_status == 'verified' ? 'text-success' : 'text-warning' }}"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Verification Status</small>
                                            <strong class="text-uppercase">{{ $dancerData->verification_status ?? 'pending' }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-clock fa-2x text-info"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Last Updated</small>
                                            <strong>{{ $dancerData->updated_at ? \Carbon\Carbon::parse($dancerData->updated_at)->format('d M Y H:i') : '-' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-music text-muted fa-4x mb-3"></i>
                    <h5 class="text-muted">No Dancer Data Available</h5>
                    <p class="text-muted">You are not registered as a Dancer yet.</p>
                </div>
            </div>
            @endif
        </div>

        <!-- OFFICIAL DATA TAB -->
        <div class="tab-pane fade {{ $activeTable == 'official' ? 'show active' : '' }}" id="official-data" role="tabpanel">
            @if($officialData)
            <div class="row">
                <!-- Personal Information -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-user-circle me-2 text-warning"></i>Personal Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">Official ID</td>
                                    <td class="fw-medium">: {{ $officialData->official_id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Full Name</td>
                                    <td class="fw-medium">: {{ $officialData->name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">NIK</td>
                                    <td class="fw-medium">: {{ $officialData->nik ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Birth Date</td>
                                    <td class="fw-medium">: {{ $officialData->birthdate ? \Carbon\Carbon::parse($officialData->birthdate)->format('d M Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Gender</td>
                                    <td class="fw-medium">: {{ $officialData->gender == 'L' ? 'Male' : ($officialData->gender == 'P' ? 'Female' : '-') }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Email</td>
                                    <td class="fw-medium">: {{ $officialData->email ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Phone</td>
                                    <td class="fw-medium">: {{ $officialData->phone ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Position & Physical Information -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-briefcase me-2 text-warning"></i>Position & Physical Info
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">Team Role</td>
                                    <td class="fw-medium">: {{ $officialData->team_role ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Role</td>
                                    <td class="fw-medium">: {{ $officialData->role ?? 'Official' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Height (cm)</td>
                                    <td class="fw-medium">: {{ $officialData->height ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Weight (kg)</td>
                                    <td class="fw-medium">: {{ $officialData->weight ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">T-Shirt Size</td>
                                    <td class="fw-medium">: {{ $officialData->tshirt_size ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Shoes Size</td>
                                    <td class="fw-medium">: {{ $officialData->shoes_size ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- School Information -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-school me-2 text-info"></i>School Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">School ID</td>
                                    <td class="fw-medium">: {{ $officialData->school_id ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">School Name</td>
                                    <td class="fw-medium">: {{ $officialData->school_name ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-share-alt me-2 text-purple"></i>Social Media
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="40%" class="text-muted">Instagram</td>
                                    <td class="fw-medium">: 
                                        @if($officialData->instagram)
                                            <a href="https://instagram.com/{{ $officialData->instagram }}" target="_blank" class="text-decoration-none">
                                                {{ $officialData->instagram }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">TikTok</td>
                                    <td class="fw-medium">: 
                                        @if($officialData->tiktok)
                                            <a href="https://tiktok.com/@{{ $officialData->tiktok }}" target="_blank" class="text-decoration-none">
                                                {{ $officialData->tiktok }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                <div class="col-lg-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-file-alt me-2 text-danger"></i>Documents
                            </h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td width="50%" class="text-muted">Formal Photo</td>
                                    <td>: 
                                        @if($officialData->formal_photo)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">License Photo</td>
                                    <td>: 
                                        @if($officialData->license_photo)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Identity Card</td>
                                    <td>: 
                                        @if($officialData->identity_card)
                                            <span class="badge bg-success">Uploaded</span>
                                        @else
                                            <span class="badge bg-danger">Missing</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Status Information -->
                <div class="col-12 mb-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white border-bottom py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle me-2 text-secondary"></i>Status Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-check-circle fa-2x {{ $officialData->verification_status == 'verified' ? 'text-success' : 'text-warning' }}"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Verification Status</small>
                                            <strong class="text-uppercase">{{ $officialData->verification_status ?? 'pending' }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-check-double fa-2x {{ $officialData->is_finalized ? 'text-success' : 'text-warning' }}"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Finalized Status</small>
                                            <strong>{{ $officialData->is_finalized ? 'Yes' : 'No' }}</strong>
                                            @if($officialData->finalized_at)
                                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($officialData->finalized_at)->format('d M Y H:i') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <i class="fas fa-clock fa-2x text-info"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Last Updated</small>
                                            <strong>{{ $officialData->updated_at ? \Carbon\Carbon::parse($officialData->updated_at)->format('d M Y H:i') : '-' }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-user-tie text-muted fa-4x mb-3"></i>
                    <h5 class="text-muted">No Official Data Available</h5>
                    <p class="text-muted">You are not registered as an Official yet.</p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <a href="{{ route('schooldata.list') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to My Schools
        </a>
        <div>
            <button type="button" class="btn btn-info text-white me-2" onclick="window.print()">
                <i class="fas fa-print me-1"></i>Print
            </button>
            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i>Edit Profile
            </a>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
    
    // Tab persistence
    const activeTab = localStorage.getItem('activeReviewTab');
    if (activeTab) {
        const tab = document.querySelector(`a[href="${activeTab}"]`);
        if (tab) {
            const tabTrigger = new bootstrap.Tab(tab);
            tabTrigger.show();
        }
    }
    
    // Save active tab to localStorage
    document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(tab => {
        tab.addEventListener('shown.bs.tab', function(e) {
            localStorage.setItem('activeReviewTab', e.target.getAttribute('href'));
        });
    });
});
</script>

<style>
/* Pink palette for Dancer */
.bg-pink-100 { background-color: #fce4ec !important; }
.text-pink-600 { color: #d81b60 !important; }
.bg-pink-600 { background-color: #d81b60 !important; }
.text-purple { color: #6f42c1 !important; }
.bg-purple { background-color: #6f42c1 !important; }

/* Card styles */
.card {
    border-radius: 10px;
    transition: all 0.3s ease;
}
.card:hover {
    box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
}

/* Table styles */
.table-borderless td {
    padding: 0.75rem 0;
    border: none;
}
.table-borderless tr:hover td {
    background-color: transparent;
}

/* Badge styles */
.badge {
    font-weight: 500;
    padding: 0.5rem 0.75rem;
}

/* Nav tabs styling */
.nav-tabs .nav-link {
    border: none;
    color: #6c757d;
    font-weight: 500;
    padding: 0.75rem 1.25rem;
    border-radius: 0;
    border-bottom: 3px solid transparent;
}
.nav-tabs .nav-link:hover {
    border-bottom-color: #dee2e6;
}
.nav-tabs .nav-link.active {
    color: #1565c0;
    background: transparent;
    border-bottom-color: #1565c0;
    border-left: none;
    border-right: none;
    border-top: none;
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}
.card { animation: fadeIn 0.5s ease-out; }

/* Responsive */
@media (max-width: 768px) {
    .table-borderless td {
        padding: 0.5rem 0;
        font-size: 0.9rem;
    }
    .card-header h5 { font-size: 1rem; }
    .nav-tabs .nav-link { padding: 0.5rem 0.75rem; font-size: 0.9rem; }
}
@media (max-width: 576px) {
    .container { padding-left: 1rem; padding-right: 1rem; }
    .card-body { padding: 1.25rem !important; }
    .d-flex.justify-content-between { flex-direction: column; gap: 1rem; }
    .btn { width: 100%; }
}
</style>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection