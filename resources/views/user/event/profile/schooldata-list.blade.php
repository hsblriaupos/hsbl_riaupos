@extends('user.form.layout')

@section('title', 'Event Histories - SBL Student Portal')

@section('content')
<div class="container py-4">
    <!-- Header Section - Consistent with profile-edit -->
    <div class="row mb-4">
        <div class="col-12">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-3">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('student.dashboard') }}" class="text-decoration-none">
                        <i class="fas fa-home me-1"></i>Dashboard
                    </a></li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-history me-1"></i>Event Histories
                    </li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="d-flex align-items-center mb-2">
                <div class="bg-primary bg-gradient rounded-circle p-3 me-3 shadow-sm">
                    <i class="fas fa-history text-white fa-2x"></i>
                </div>
                <div>
                    <h1 class="h3 mb-1 fw-bold">Event Histories</h1>
                    <p class="text-muted mb-0">My Team - Track all teams you've joined in SBL competitions</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Container untuk pesan dinamis -->
    <div id="alert-container"></div>

    <!-- Statistics Cards - Enhanced with profile-edit style -->
    @if($totalSchools > 0)
    <div class="row g-4 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Teams</h6>
                            <h3 class="fw-bold mb-0 text-primary">{{ $totalSchools }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-users text-primary fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">As Player</h6>
                            <h3 class="fw-bold mb-0 text-success">{{ $playerCount }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-basketball-ball text-success fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">As Dancer</h6>
                            <h3 class="fw-bold mb-0 text-pink-600">{{ $dancerCount }}</h3>
                        </div>
                        <div class="bg-pink-100 rounded-circle p-3">
                            <i class="fas fa-music text-pink-600 fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">As Official</h6>
                            <h3 class="fw-bold mb-0 text-warning">{{ $officialCount }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-user-tie text-warning fa-lg"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Teams List Card - Consistent with profile-edit style -->
    <div class="card border-0 shadow-sm">
        <!-- Card Header -->
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="mb-0">
                    <i class="fas fa-list-alt me-2 text-primary"></i>My Teams History
                </h5>
                <div class="d-flex align-items-center">
                    <!-- Search Input -->
                    <div class="input-group input-group-sm" style="width: 280px;">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" class="form-control border-start-0 ps-0" id="searchInput" 
                               placeholder="Search team name..." autocomplete="off">
                    </div>
                </div>
            </div>
        </div>

        <!-- Card Body -->
        <div class="card-body p-0">
            @if($schools->isEmpty())
                <!-- Empty State - Enhanced with profile-edit style -->
                <div class="text-center py-5">
                    <div class="avatar-container mx-auto mb-4" style="width: 100px; height: 100px;">
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                            <i class="fas fa-users text-muted fa-3x"></i>
                        </div>
                    </div>
                    <h4 class="text-muted mb-3">No Teams Found</h4>
                    <p class="text-muted mb-4">You haven't joined any team yet.</p>
                    <div class="alert alert-info alert-hsbl mx-auto" style="max-width: 600px;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-lg me-3"></i>
                            <div class="text-start">
                                <strong>How to join a team?</strong><br>
                                <small>Register as a Player, Dancer, or Official through the registration forms. Your team data will appear here automatically.</small>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('form.team.choice') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-2"></i>Join a Team
                        </a>
                    </div>
                </div>
            @else
                <!-- Table - Clean design -->
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" id="teamsTable">
                        <thead class="bg-light">
                            <tr>
                                <th width="50" class="ps-4">No</th>
                                <th>Team Information</th>
                                <th>Competition</th>
                                <th>My Roles</th>
                                <th>Status</th>
                                <th width="120" class="text-center pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schools as $index => $school)
                            @php
                                // GUNAKAN DATA YANG SUDAH DIPROSES DARI CONTROLLER
                                $isPlayer = $school->isPlayer ?? false;
                                $isDancer = $school->isDancer ?? false;
                                $isOfficial = $school->isOfficial ?? false;
                                $playerRole = $school->playerRole ?? null;
                                $dancerRole = $school->dancerRole ?? null;
                                $officialRole = $school->officialRole ?? null;
                                $playerData = $school->playerData ?? null;
                                $dancerData = $school->dancerData ?? null;
                                $officialData = $school->officialData ?? null;
                                $registeredBy = $school->registered_by ?? 'Self';
                                
                                // Bersihkan role jika nilainya sama dengan nama role
                                $cleanPlayerRole = ($playerRole && $playerRole != 'Player') ? $playerRole : null;
                                $cleanDancerRole = ($dancerRole && $dancerRole != 'Dancer') ? $dancerRole : null;
                                $cleanOfficialRole = ($officialRole && $officialRole != 'Official') ? $officialRole : null;
                                
                                // Determine team status badge
                                $statusText = '';
                                $badgeClass = '';
                                if ($school->verification_status === 'verified') {
                                    $badgeClass = 'bg-success';
                                    $statusText = 'Verified';
                                } elseif ($school->verification_status === 'pending') {
                                    $badgeClass = 'bg-warning';
                                    $statusText = 'Pending';
                                } elseif ($school->verification_status === 'rejected') {
                                    $badgeClass = 'bg-danger';
                                    $statusText = 'Rejected';
                                } else {
                                    $badgeClass = 'bg-secondary';
                                    $statusText = 'Not Submitted';
                                }
                                
                                // Payment status
                                $paymentText = '';
                                $paymentClass = '';
                                if ($school->payment_status === 'paid') {
                                    $paymentClass = 'bg-success';
                                    $paymentText = 'Paid';
                                } elseif ($school->payment_status === 'pending') {
                                    $paymentClass = 'bg-warning';
                                    $paymentText = 'Pending';
                                } else {
                                    $paymentClass = 'bg-danger';
                                    $paymentText = 'Unpaid';
                                }
                                
                                // Logo path
                                $logoPath = $school->logo_url ?? null;
                                if (!$logoPath && $school->school_logo) {
                                    $logoFile = basename($school->school_logo);
                                    if (file_exists(public_path('storage/school_logos/' . $logoFile))) {
                                        $logoPath = asset('storage/school_logos/' . $logoFile);
                                    } elseif (file_exists(public_path('school_logos/' . $logoFile))) {
                                        $logoPath = asset('school_logos/' . $logoFile);
                                    }
                                }
                                
                                // Team ID untuk route
                                $teamId = $school->team_id ?? $school->school_id;
                            @endphp
                            <tr data-status="{{ $school->verification_status }}" 
                                data-team-id="{{ $teamId }}" 
                                data-team-name="{{ strtolower($school->school_name) }}">
                                <td class="ps-4 text-muted fw-medium">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <!-- Team Logo/Avatar -->
                                        <div class="avatar-container me-3" style="width: 48px; height: 48px;">
                                            @if($logoPath)
                                            <img src="{{ $logoPath }}" 
                                                 alt="{{ $school->school_name }}" 
                                                 class="rounded-circle w-100 h-100 object-fit-cover border border-2 border-light"
                                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($school->school_name) }}&background=1565c0&color=fff&size=48&bold=true';">
                                            @else
                                            <div class="rounded-circle d-flex align-items-center justify-content-center w-100 h-100 bg-primary bg-gradient text-white fw-bold" 
                                                 style="font-size: 1.2rem;">
                                                {{ strtoupper(substr($school->school_name, 0, 1)) }}
                                            </div>
                                            @endif
                                        </div>
                                        <!-- Team Details -->
                                        <div>
                                            <h6 class="mb-1 fw-semibold">{{ $school->school_name }}</h6>
                                            <p class="text-muted mb-0 small">
                                                <i class="fas fa-user-check me-1 text-primary"></i>Registered by: {{ $registeredBy }}
                                            </p>
                                            <p class="text-muted mb-0 small">
                                                <i class="fas fa-calendar me-1 text-primary"></i>{{ \Carbon\Carbon::parse($school->created_at)->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="fw-medium text-primary">{{ ucfirst($school->competition) }}</span>
                                        <span class="text-muted small">{{ ucfirst($school->team_category) }}</span>
                                        <span class="text-muted small">{{ $school->season }}-{{ $school->series }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @if($isPlayer)
                                        <div class="text-muted small">
                                            <i class="fas fa-basketball-ball me-1 text-success"></i>
                                            Player @if($cleanPlayerRole)- {{ $cleanPlayerRole }} @endif
                                        </div>
                                        @endif
                                        @if($isDancer)
                                        <div class="text-muted small">
                                            <i class="fas fa-music me-1 text-pink-600"></i>
                                            Dancer @if($cleanDancerRole)- {{ $cleanDancerRole }} @endif
                                        </div>
                                        @endif
                                        @if($isOfficial)
                                        <div class="text-muted small">
                                            <i class="fas fa-user-tie me-1 text-warning"></i>
                                            Official @if($cleanOfficialRole)- {{ $cleanOfficialRole }} @endif
                                        </div>
                                        @endif
                                        @if(!$isPlayer && !$isDancer && !$isOfficial)
                                        <span class="text-muted small">
                                            <i class="fas fa-minus-circle me-1"></i>No Roles
                                        </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <span class="badge {{ $badgeClass }} rounded-pill" style="font-size: 0.75rem; padding: 0.35rem 0.65rem; width: fit-content;">
                                            {{ $statusText }}
                                        </span>
                                        <span class="badge {{ $paymentClass }} rounded-pill" style="font-size: 0.75rem; padding: 0.35rem 0.65rem; width: fit-content;">
                                            {{ $paymentText }}
                                        </span>
                                        @if($school->payment_date)
                                        <small class="text-muted mt-1">
                                            <i class="fas fa-calendar-check me-1"></i>
                                            {{ \Carbon\Carbon::parse($school->payment_date)->format('d/m/Y') }}
                                        </small>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- Detail Button - Mengarah ke halaman edit team -->
                                        <a href="{{ route('student.team.edit', $teamId) }}" 
                                           class="btn btn-sm btn-outline-primary rounded-circle" 
                                           title="Team Details"
                                           style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-info-circle"></i>
                                        </a>
                                        
                                        <!-- Team List Button - Mengarah ke halaman team list -->
                                        <a href="{{ route('student.team.list.with_id', $teamId) }}" 
                                           class="btn btn-sm btn-outline-success rounded-circle" 
                                           title="Team Members"
                                           style="width: 36px; height: 36px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fas fa-users"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($schools->hasPages())
                <div class="d-flex justify-content-center py-4 border-top">
                    {{ $schools->links() }}
                </div>
                @endif

                <!-- Info Alert -->
                <div class="p-4 border-top">
                    <div class="alert alert-info alert-hsbl mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle fa-lg me-3"></i>
                            <div>
                                <strong class="d-block mb-1">Information</strong>
                                <p class="mb-0 small">This page displays all teams where you are registered as Player, Dancer, or Official. Click the <i class="fas fa-info-circle"></i> icon to view team details, or the <i class="fas fa-users"></i> icon to see all team members.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Table filtering with search
function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase().trim();
    const table = document.getElementById('teamsTable');
    const tr = table.getElementsByTagName('tr');
    
    for (let i = 1; i < tr.length; i++) {
        const teamName = tr[i].getAttribute('data-team-name') || '';
        
        if (teamName.includes(filter)) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Search input event listener
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', filterTable);
        searchInput.addEventListener('search', filterTable);
    }
    
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
});
</script>

<style>
/* Pink palette untuk Dancer */
.bg-pink-100 {
    background-color: #fce4ec !important; /* Pink sangat muda */
}

.text-pink-600 {
    color: #d81b60 !important; /* Pink tua */
}

.bg-pink-600 {
    background-color: #d81b60 !important; /* Pink tua */
}

/* Card styles */
.card {
    border-radius: 10px;
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
}

/* Table styles */
.table {
    margin-bottom: 0;
}

.table thead th {
    background-color: #f8fafc;
    color: #37474f;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #e0e0e0;
    padding: 1rem 0.75rem;
}

.table tbody tr {
    transition: all 0.2s;
}

.table tbody tr:hover {
    background-color: #f5f9ff;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

.table td {
    padding: 1rem 0.75rem;
    vertical-align: middle;
    border-bottom: 1px solid #eef2f6;
}

/* Badge styles */
.badge {
    font-weight: 500;
    font-size: 0.75rem;
    padding: 0.35rem 0.65rem;
}

/* Alert styles */
.alert-hsbl {
    border-left-width: 4px;
    border-radius: 8px;
}

/* Avatar styles */
.avatar-container {
    position: relative;
}

.object-fit-cover {
    object-fit: cover;
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.card {
    animation: fadeIn 0.5s ease-out;
}

/* Button styles */
.btn-outline-primary, .btn-outline-success {
    transition: all 0.2s ease;
}

.btn-outline-primary:hover {
    background-color: #1565c0;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(21, 101, 192, 0.3);
}

.btn-outline-success:hover {
    background-color: #2e7d32;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(46, 125, 50, 0.3);
}

/* Responsive styles */
@media (max-width: 768px) {
    .table thead th {
        font-size: 0.75rem;
        padding: 0.75rem 0.5rem;
    }
    
    .table td {
        padding: 0.75rem 0.5rem;
        font-size: 0.85rem;
    }
    
    .card-header .d-flex {
        flex-direction: column;
        align-items: stretch !important;
    }
    
    .card-header .input-group {
        width: 100% !important;
    }
}

@media (max-width: 576px) {
    .btn-sm.rounded-circle {
        width: 32px !important;
        height: 32px !important;
    }
}
</style>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection