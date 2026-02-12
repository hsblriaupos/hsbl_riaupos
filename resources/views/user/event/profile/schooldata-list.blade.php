@extends('user.form.layout')

@section('title', 'School Data List - HSBL Student Portal')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <!-- Header Section -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h3 fw-bold text-primary mb-2">
                        <i class="fas fa-school me-2"></i>My Schools
                    </h1>
                    <p class="text-muted mb-0">
                        View all schools you've joined for HSBL competition
                    </p>
                </div>
                <div class="text-end">
                    <p class="text-muted small mb-0">
                        <i class="fas fa-info-circle me-1"></i>
                        Data displayed based on your registration records
                    </p>
                </div>
            </div>

            <!-- Statistics Cards -->
            @if($totalSchools > 0)
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card card-hsbl">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">Total Schools</h6>
                                    <h3 class="fw-bold mb-0">{{ $totalSchools }}</h3>
                                </div>
                                <div class="avatar-icon" style="width: 50px; height: 50px;">
                                    <i class="fas fa-school fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card card-hsbl">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">As Player</h6>
                                    <h3 class="fw-bold mb-0">{{ $playerCount }}</h3>
                                </div>
                                <div class="avatar-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%);">
                                    <i class="fas fa-basketball-ball fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card card-hsbl">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">As Dancer</h6>
                                    <h3 class="fw-bold mb-0">{{ $dancerCount }}</h3>
                                </div>
                                <div class="avatar-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #7b1fa2 0%, #9c27b0 100%);">
                                    <i class="fas fa-music fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="card card-hsbl">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="text-muted mb-1">As Official</h6>
                                    <h3 class="fw-bold mb-0">{{ $officialCount }}</h3>
                                </div>
                                <div class="avatar-icon" style="width: 50px; height: 50px; background: linear-gradient(135deg, #f57c00 0%, #ff9800 100%);">
                                    <i class="fas fa-user-tie fa-lg"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Schools List -->
            <div class="card card-hsbl mb-4">
                <div class="card-header card-header-hsbl d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list-alt me-2"></i>List of Schools
                    </h5>
                    <div>
                        <div class="input-group input-group-sm" style="width: 250px;">
                            <span class="input-group-text bg-light">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control" id="searchInput" 
                                   placeholder="Search school name or ID...">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($schools->isEmpty())
                        <div class="text-center py-5">
                            <div class="avatar-icon mx-auto mb-3" style="width: 80px; height: 80px;">
                                <i class="fas fa-school fa-2x"></i>
                            </div>
                            <h4 class="text-muted mb-3">No Schools Found</h4>
                            <p class="text-muted mb-4">You haven't joined any school team yet.</p>
                            <div class="alert alert-info mt-4" style="max-width: 600px; margin: 0 auto;">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>How to join a school?</strong><br>
                                1. Register as a Player, Dancer, or Official through the registration forms<br>
                                2. Your school data will appear here automatically
                            </div>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="schoolsTable">
                                <thead class="table-light">
                                    <tr>
                                        <th width="40">No</th>
                                        <th>School Info</th>
                                        <th>Competition</th>
                                        <th>My Roles</th>
                                        <th>Status</th>
                                        <th width="100" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($schools as $index => $school)
                                    @php
                                        // GUNAKAN DATA YANG SUDAH DIPROSES DARI CONTROLLER
                                        // Controller sudah attach data role ke $school object
                                        $isPlayer = $school->isPlayer ?? false;
                                        $isDancer = $school->isDancer ?? false;
                                        $isOfficial = $school->isOfficial ?? false;
                                        $playerRole = $school->playerRole ?? null;
                                        $dancerRole = $school->dancerRole ?? null;
                                        $officialRole = $school->officialRole ?? null;
                                        $playerData = $school->playerData ?? null;
                                        $dancerData = $school->dancerData ?? null;
                                        $officialData = $school->officialData ?? null;
                                        
                                        // Debug data
                                        // {{-- Uncomment for debugging: 
                                        // <pre style="display:none;">School: {{ $school->school_name }}
                                        // isPlayer: {{ $isPlayer ? 'YES' : 'NO' }}
                                        // playerRole: {{ $playerRole }}
                                        // playerData: {{ json_encode($playerData) }}</pre> 
                                        // --}}
                                        
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
                                    @endphp
                                    <tr data-status="{{ $school->verification_status }}" data-school-id="{{ $school->school_id }}" data-school-name="{{ strtolower($school->school_name) }}">
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($logoPath)
                                                <img src="{{ $logoPath }}" 
                                                     alt="{{ $school->school_name }}" 
                                                     class="rounded-circle me-3" 
                                                     style="width: 48px; height: 48px; object-fit: cover; border: 2px solid #e0e0e0;"
                                                     onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($school->school_name) }}&background=1565c0&color=fff&size=48&bold=true';">
                                                @else
                                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                                                     style="width: 48px; height: 48px; background: linear-gradient(135deg, #1565c0 0%, #1e88e5 100%); color: white; font-weight: bold; font-size: 1.1rem;">
                                                    {{ strtoupper(substr($school->school_name, 0, 1)) }}
                                                </div>
                                                @endif
                                                <div>
                                                    <h6 class="mb-1 fw-bold">{{ $school->school_name }}</h6>
                                                    <p class="text-muted mb-0 small">
                                                        <i class="fas fa-id-card me-1"></i>ID: {{ $school->school_id }}
                                                    </p>
                                                    <p class="text-muted mb-0 small">
                                                        <i class="fas fa-calendar me-1"></i>{{ \Carbon\Carbon::parse($school->created_at)->format('d M Y') }}
                                                    </p>
                                                    @if($school->team_id)
                                                    <p class="text-muted mb-0 small">
                                                        <i class="fas fa-hashtag me-1"></i>Team: {{ $school->team_id }}
                                                    </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="badge bg-primary bg-opacity-10 text-primary mb-1">
                                                    {{ ucfirst($school->competition) }}
                                                </span>
                                                <span class="badge bg-info bg-opacity-10 text-info mb-1">
                                                    {{ ucfirst($school->team_category) }}
                                                </span>
                                                <span class="text-muted small">
                                                    {{ $school->season }}-{{ $school->series }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                @if($isPlayer)
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-success bg-opacity-20 text-success border border-success border-opacity-25 me-2">
                                                        <i class="fas fa-basketball-ball me-1"></i>Player
                                                    </span>
                                                    @if($playerRole)
                                                    <small class="text-muted">{{ $playerRole }}</small>
                                                    @endif
                                                </div>
                                                @endif
                                                @if($isDancer)
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-purple bg-opacity-20 text-purple border border-purple border-opacity-25 me-2" style="--bs-bg-opacity: .2; --bs-purple-rgb: 123, 31, 162;">
                                                        <i class="fas fa-music me-1"></i>Dancer
                                                    </span>
                                                    @if($dancerRole)
                                                    <small class="text-muted">{{ $dancerRole }}</small>
                                                    @endif
                                                </div>
                                                @endif
                                                @if($isOfficial)
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-25 me-2">
                                                        <i class="fas fa-user-tie me-1"></i>Official
                                                    </span>
                                                    @if($officialRole)
                                                    <small class="text-muted">{{ $officialRole }}</small>
                                                    @endif
                                                </div>
                                                @endif
                                                @if(!$isPlayer && !$isDancer && !$isOfficial)
                                                <span class="badge bg-secondary bg-opacity-20 text-secondary border border-secondary border-opacity-25">
                                                    <i class="fas fa-question-circle me-1"></i>No Roles
                                                </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <span class="badge {{ $badgeClass }}" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                                    {{ $statusText }}
                                                </span>
                                                <span class="badge {{ $paymentClass }}" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;">
                                                    {{ $paymentText }}
                                                </span>
                                                @if($school->payment_date)
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-check me-1"></i>
                                                    {{ \Carbon\Carbon::parse($school->payment_date)->format('d/m/Y') }}
                                                </small>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary" 
                                                        onclick="showSchoolDetails({{ json_encode([
                                                            'school_id' => $school->school_id,
                                                            'team_id' => $school->team_id ?? null,
                                                            'school_name' => $school->school_name,
                                                            'school_logo' => $logoPath,
                                                            'competition' => $school->competition,
                                                            'team_category' => $school->team_category,
                                                            'season' => $school->season,
                                                            'series' => $school->series,
                                                            'referral_code' => $school->referral_code ?? null,
                                                            'verification_status' => $school->verification_status,
                                                            'payment_status' => $school->payment_status,
                                                            'locked_status' => $school->locked_status ?? 'unlocked',
                                                            'is_leader_paid' => $school->is_leader_paid ?? false,
                                                            'payment_date' => $school->payment_date ? \Carbon\Carbon::parse($school->payment_date)->format('d M Y') : null,
                                                            'created_at' => \Carbon\Carbon::parse($school->created_at)->format('d M Y'),
                                                            'isPlayer' => $isPlayer,
                                                            'isDancer' => $isDancer,
                                                            'isOfficial' => $isOfficial,
                                                            'playerRole' => $playerRole,
                                                            'dancerRole' => $dancerRole,
                                                            'officialRole' => $officialRole,
                                                            'playerData' => $playerData,
                                                            'dancerData' => $dancerData,
                                                            'officialData' => $officialData
                                                        ]) }})"
                                                        title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if($isPlayer || $isDancer || $isOfficial)
                                                <button type="button" class="btn btn-outline-danger" 
                                                        onclick="confirmLeaveSchool('{{ $school->school_id }}', '{{ $school->school_name }}')"
                                                        title="Leave School">
                                                    <i class="fas fa-sign-out-alt"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        @if($schools->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $schools->links() }}
                        </div>
                        @endif
                        
                        <!-- Summary -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle fa-2x me-3"></i>
                                        <div>
                                            <h6 class="mb-1 fw-bold">Information</h6>
                                            <p class="mb-0 small">This page displays all schools where you are registered as Player, Dancer, or Official. Data is automatically updated based on your registration records.</p>
                                            @if(count($schools) > 0)
                                            <p class="mb-0 small mt-2">
                                                <i class="fas fa-lightbulb me-1"></i>
                                                <strong>Tip:</strong> Click the eye icon to view detailed information about each school and your roles.
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- School Details Modal - IMPROVED DESIGN -->
<div class="modal fade" id="schoolDetailsModal" tabindex="-1" aria-labelledby="schoolDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white rounded-top-3">
                <div class="d-flex align-items-center">
                    <div class="modal-logo me-3" id="modalLogoContainer" style="width: 40px; height: 40px;"></div>
                    <div>
                        <h5 class="modal-title mb-0" id="modalSchoolName"></h5>
                        <small class="text-white-50" id="modalSchoolId"></small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <!-- Modal Body -->
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Left Side - School Info -->
                    <div class="col-md-5 border-end">
                        <div class="p-4">
                            <!-- School Logo Display -->
                            <div class="text-center mb-4">
                                <div class="modal-school-logo mx-auto mb-3" id="modalSchoolLogoDisplay" 
                                     style="width: 120px; height: 120px; border: 3px solid #e0e0e0; background: linear-gradient(135deg, #f5f5f5 0%, #e0e0e0 100%);">
                                </div>
                                <h6 class="fw-bold mb-1 text-primary" id="modalSchoolFullId"></h6>
                                <p class="text-muted small mb-0">
                                    <i class="fas fa-calendar me-1"></i>
                                    <span id="modalRegisteredDate"></span>
                                </p>
                            </div>
                            
                            <!-- Competition Details Card -->
                            <div class="card border-0 shadow-sm mb-3">
                                <div class="card-body">
                                    <h6 class="card-title fw-bold mb-3 text-primary">
                                        <i class="fas fa-trophy me-2"></i>Competition Details
                                    </h6>
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted">Type:</span>
                                            <span class="badge bg-primary" id="modalCompetition"></span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted">Category:</span>
                                            <span class="badge bg-info" id="modalCategory"></span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Season/Series:</span>
                                            <span class="fw-bold text-primary" id="modalSeasonSeries"></span>
                                        </div>
                                    </div>
                                    <hr class="my-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-code text-muted me-2"></i>
                                        <div>
                                            <small class="text-muted d-block">Referral Code</small>
                                            <span class="fw-bold text-primary" id="modalReferralCode"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Status Card -->
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title fw-bold mb-3 text-primary">
                                        <i class="fas fa-info-circle me-2"></i>Status Information
                                    </h6>
                                    <div class="status-list">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted">Verification:</span>
                                            <span class="badge" id="modalVerificationStatus"></span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted">Payment:</span>
                                            <span class="badge" id="modalPaymentStatus"></span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted">Team Lock:</span>
                                            <span class="badge" id="modalLockStatus"></span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="text-muted">Leader Payment:</span>
                                            <span class="badge" id="modalLeaderPayment"></span>
                                        </div>
                                        <div class="mt-3 pt-2 border-top" id="modalPaymentDateContainer">
                                            <!-- Payment date will be inserted here -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Side - User Roles & Actions -->
                    <div class="col-md-7">
                        <div class="p-4">
                            <!-- Your Roles Card -->
                            <div class="card border-0 shadow-sm mb-4">
                                <div class="card-body">
                                    <h6 class="card-title fw-bold mb-3 text-primary">
                                        <i class="fas fa-user-check me-2"></i>Your Roles
                                    </h6>
                                    <div id="modalRolesContainer" class="roles-grid">
                                        <!-- Roles will be inserted here -->
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Action Buttons Card -->
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title fw-bold mb-3 text-primary">
                                        <i class="fas fa-bolt me-2"></i>Quick Actions
                                    </h6>
                                    <div class="action-buttons" id="modalActionButtons">
                                        <!-- Action buttons will be inserted here -->
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Information Box -->
                            <div class="alert alert-light border mt-4">
                                <div class="d-flex">
                                    <i class="fas fa-lightbulb text-warning me-3 mt-1"></i>
                                    <div>
                                        <small class="fw-bold d-block">Tip:</small>
                                        <small class="text-muted">Use the action buttons to view detailed profiles for your specific roles in this school.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer bg-light rounded-bottom-3">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Close
                </button>
                <button type="button" class="btn btn-primary" onclick="viewTeamProfile()">
                    <i class="fas fa-external-link-alt me-2"></i>View Full Team Profile
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Leave Modal -->
<div class="modal fade" id="confirmLeaveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Leave School
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to leave <strong id="leaveSchoolName"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>This action will remove all your roles (Player, Dancer, Official) from this school and cannot be undone.</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmLeaveBtn">Yes, Leave School</button>
            </div>
        </div>
    </div>
</div>

<script>
// Store user data for filtering
const authUserEmail = "{{ auth()->user()->email ?? '' }}";
const authUserNik = "{{ auth()->user()->nik ?? '' }}";

// Store current school ID for actions
let currentSchoolId = null;
let currentSchoolTeamId = null;

// School details modal
function showSchoolDetails(schoolData) {
    // Store school ID for action buttons
    currentSchoolId = schoolData.school_id;
    currentSchoolTeamId = schoolData.team_id;
    
    // Set basic school info
    document.getElementById('modalSchoolName').textContent = schoolData.school_name;
    document.getElementById('modalSchoolId').textContent = 'ID: ' + schoolData.school_id;
    document.getElementById('modalSchoolFullId').textContent = 'School ID: ' + schoolData.school_id;
    document.getElementById('modalRegisteredDate').textContent = 'Registered: ' + schoolData.created_at;
    
    // Set school logo or initial in header
    const headerLogo = document.getElementById('modalLogoContainer');
    const mainLogo = document.getElementById('modalSchoolLogoDisplay');
    
    function setLogo(container, schoolName, schoolLogo) {
        if (schoolLogo) {
            container.innerHTML = `<img src="${schoolLogo}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 8px;" alt="${schoolName}" onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(schoolName)}&background=1565c0&color=fff&size=128&bold=true';">`;
        } else {
            const initial = schoolName.charAt(0).toUpperCase();
            container.innerHTML = `<div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #1565c0 0%, #1e88e5 100%); color: white; font-weight: bold; font-size: 1.5rem; border-radius: 8px;">${initial}</div>`;
        }
    }
    
    setLogo(headerLogo, schoolData.school_name, schoolData.school_logo);
    setLogo(mainLogo, schoolData.school_name, schoolData.school_logo);
    
    // Set competition details
    document.getElementById('modalCompetition').textContent = formatText(schoolData.competition);
    document.getElementById('modalCategory').textContent = formatText(schoolData.team_category);
    document.getElementById('modalSeasonSeries').textContent = 'Season ' + schoolData.season + ', Series ' + schoolData.series;
    document.getElementById('modalReferralCode').textContent = schoolData.referral_code || 'N/A';
    
    // Set status badges with appropriate colors
    const verificationBadge = document.getElementById('modalVerificationStatus');
    verificationBadge.textContent = formatText(schoolData.verification_status);
    verificationBadge.className = 'badge ' + getStatusClass(schoolData.verification_status);
    
    const paymentBadge = document.getElementById('modalPaymentStatus');
    paymentBadge.textContent = formatText(schoolData.payment_status);
    paymentBadge.className = 'badge ' + getPaymentClass(schoolData.payment_status);
    
    const lockBadge = document.getElementById('modalLockStatus');
    lockBadge.textContent = formatText(schoolData.locked_status);
    lockBadge.className = 'badge ' + (schoolData.locked_status === 'locked' ? 'bg-danger' : 'bg-success');
    
    const leaderBadge = document.getElementById('modalLeaderPayment');
    leaderBadge.textContent = schoolData.is_leader_paid ? 'Paid' : 'Unpaid';
    leaderBadge.className = 'badge ' + (schoolData.is_leader_paid ? 'bg-success' : 'bg-danger');
    
    // Set payment date
    const paymentDateContainer = document.getElementById('modalPaymentDateContainer');
    if (schoolData.payment_date) {
        paymentDateContainer.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">Payment Date:</span>
                <span class="fw-bold text-success">
                    <i class="fas fa-calendar-check me-1"></i>${schoolData.payment_date}
                </span>
            </div>`;
    } else {
        paymentDateContainer.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted">Payment Date:</span>
                <span class="text-muted">Not available</span>
            </div>`;
    }
    
    // Set user roles - FIXED ROLE DISPLAY
    const rolesContainer = document.getElementById('modalRolesContainer');
    rolesContainer.innerHTML = '';
    
    if (schoolData.isPlayer || schoolData.isDancer || schoolData.isOfficial) {
        let rolesHTML = '<div class="row g-2">';
        
        if (schoolData.isPlayer) {
            const playerDetails = schoolData.playerData || {};
            rolesHTML += `
                <div class="col-12">
                    <div class="role-card bg-success bg-opacity-10 border border-success border-opacity-25 rounded-3 p-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="role-icon d-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #2e7d32 0%, #4caf50 100%); border-radius: 8px; color: white;">
                                <i class="fas fa-basketball-ball"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-success">Player</h6>
                                ${schoolData.playerRole ? `<small class="text-muted">${schoolData.playerRole}</small>` : ''}
                            </div>
                        </div>
                        <div class="row g-2 mt-2">
                            ${playerDetails.jersey_number ? `
                                <div class="col-6">
                                    <small class="text-muted d-block">Jersey Number</small>
                                    <span class="fw-bold">#${playerDetails.jersey_number}</span>
                                </div>` : ''}
                            ${playerDetails.basketball_position ? `
                                <div class="col-6">
                                    <small class="text-muted d-block">Position</small>
                                    <span class="fw-bold">${playerDetails.basketball_position}</span>
                                </div>` : ''}
                        </div>
                    </div>
                </div>`;
        }
        
        if (schoolData.isDancer) {
            rolesHTML += `
                <div class="col-12">
                    <div class="role-card bg-purple bg-opacity-10 border border-purple border-opacity-25 rounded-3 p-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="role-icon d-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #7b1fa2 0%, #9c27b0 100%); border-radius: 8px; color: white;">
                                <i class="fas fa-music"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-purple">Dancer</h6>
                                ${schoolData.dancerRole ? `<small class="text-muted">${schoolData.dancerRole}</small>` : ''}
                            </div>
                        </div>
                    </div>
                </div>`;
        }
        
        if (schoolData.isOfficial) {
            rolesHTML += `
                <div class="col-12">
                    <div class="role-card bg-warning bg-opacity-10 border border-warning border-opacity-25 rounded-3 p-3">
                        <div class="d-flex align-items-center mb-2">
                            <div class="role-icon d-flex align-items-center justify-content-center me-3" 
                                 style="width: 40px; height: 40px; background: linear-gradient(135deg, #f57c00 0%, #ff9800 100%); border-radius: 8px; color: white;">
                                <i class="fas fa-user-tie"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-warning">Official</h6>
                                ${schoolData.officialRole ? `<small class="text-muted">${schoolData.officialRole}</small>` : ''}
                            </div>
                        </div>
                    </div>
                </div>`;
        }
        
        rolesHTML += '</div>';
        rolesContainer.innerHTML = rolesHTML;
    } else {
        rolesContainer.innerHTML = `
            <div class="alert alert-warning border-0 rounded-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-3 text-warning" style="font-size: 1.5rem;"></i>
                    <div>
                        <h6 class="mb-1 fw-bold">No Active Roles</h6>
                        <p class="mb-0 small">You don't have any active roles in this school team.</p>
                    </div>
                </div>
            </div>`;
    }
    
    // Set action buttons
    const actionButtons = document.getElementById('modalActionButtons');
    actionButtons.innerHTML = '';
    
    let actionButtonsHTML = '<div class="row g-2">';
    
    // Team profile button (always shown)
    actionButtonsHTML += `
        <div class="col-md-6">
            <button class="btn btn-outline-primary w-100 d-flex align-items-center justify-content-center py-2" 
                    onclick="viewTeamProfile('${schoolData.school_id}')">
                <i class="fas fa-users me-2"></i>
                <span>Team Profile</span>
            </button>
        </div>`;
    
    // Player profile button (if user is player)
    if (schoolData.isPlayer && schoolData.playerData && schoolData.playerData.id) {
        actionButtonsHTML += `
            <div class="col-md-6">
                <button class="btn btn-outline-success w-100 d-flex align-items-center justify-content-center py-2" 
                        onclick="viewPlayerProfile(${schoolData.playerData.id})">
                    <i class="fas fa-user me-2"></i>
                    <span>Player Profile</span>
                </button>
            </div>`;
    }
    
    // Dancer profile button (if user is dancer)
    if (schoolData.isDancer && schoolData.dancerData && schoolData.dancerData.dancer_id) {
        actionButtonsHTML += `
            <div class="col-md-6">
                <button class="btn btn-outline-purple w-100 d-flex align-items-center justify-content-center py-2" 
                        style="border-color: #7b1fa2; color: #7b1fa2;" 
                        onclick="viewDancerProfile(${schoolData.dancerData.dancer_id})">
                    <i class="fas fa-music me-2"></i>
                    <span>Dancer Profile</span>
                </button>
            </div>`;
    }
    
    // Official profile button (if user is official)
    if (schoolData.isOfficial && schoolData.officialData && schoolData.officialData.official_id) {
        actionButtonsHTML += `
            <div class="col-md-6">
                <button class="btn btn-outline-warning w-100 d-flex align-items-center justify-content-center py-2" 
                        onclick="viewOfficialProfile(${schoolData.officialData.official_id})">
                    <i class="fas fa-id-badge me-2"></i>
                    <span>Official Profile</span>
                </button>
            </div>`;
    }
    
    actionButtonsHTML += '</div>';
    actionButtons.innerHTML = actionButtonsHTML;
    
    // Show the modal
    const modal = new bootstrap.Modal(document.getElementById('schoolDetailsModal'));
    modal.show();
}

// Helper functions
function formatText(text) {
    if (!text) return 'N/A';
    return text.charAt(0).toUpperCase() + text.slice(1);
}

function getStatusClass(status) {
    switch(status) {
        case 'verified': return 'bg-success';
        case 'pending': return 'bg-warning';
        case 'rejected': return 'bg-danger';
        default: return 'bg-secondary';
    }
}

function getPaymentClass(status) {
    switch(status) {
        case 'paid': return 'bg-success';
        case 'pending': return 'bg-warning';
        default: return 'bg-danger';
    }
}

// Action functions
function viewTeamProfile(schoolId = null) {
    const id = schoolId || currentSchoolId;
    if (id) {
        window.location.href = `/team/profile/${id}`;
    }
}

function viewPlayerProfile(playerId) {
    if (playerId) {
        window.location.href = `/player/profile/${playerId}`;
    }
}

function viewDancerProfile(dancerId) {
    if (dancerId) {
        window.location.href = `/dancer/profile/${dancerId}`;
    }
}

function viewOfficialProfile(officialId) {
    if (officialId) {
        window.location.href = `/official/profile/${officialId}`;
    }
}

// Leave school function
function confirmLeaveSchool(schoolId, schoolName) {
    document.getElementById('leaveSchoolName').textContent = schoolName;
    
    const confirmBtn = document.getElementById('confirmLeaveBtn');
    confirmBtn.onclick = function() {
        leaveSchool(schoolId);
    };
    
    const modal = new bootstrap.Modal(document.getElementById('confirmLeaveModal'));
    modal.show();
}

function leaveSchool(schoolId) {
    if (!schoolId) return;
    
    // Show loading
    const confirmBtn = document.getElementById('confirmLeaveBtn');
    const originalText = confirmBtn.innerHTML;
    confirmBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
    confirmBtn.disabled = true;
    
    // Send leave request
    fetch(`/student/school-data/leave/${schoolId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close modals
            bootstrap.Modal.getInstance(document.getElementById('confirmLeaveModal')).hide();
            if (document.getElementById('schoolDetailsModal').classList.contains('show')) {
                bootstrap.Modal.getInstance(document.getElementById('schoolDetailsModal')).hide();
            }
            
            // Show success message and reload
            showToast('Success', 'You have successfully left the school.', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 1500);
        } else {
            showToast('Error', data.message || 'Failed to leave school.', 'error');
            confirmBtn.innerHTML = originalText;
            confirmBtn.disabled = false;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'An error occurred. Please try again.', 'error');
        confirmBtn.innerHTML = originalText;
        confirmBtn.disabled = false;
    });
}

// Table filtering
function filterTable() {
    const input = document.getElementById('searchInput');
    const filter = input.value.toLowerCase().trim();
    const table = document.getElementById('schoolsTable');
    const tr = table.getElementsByTagName('tr');
    
    for (let i = 1; i < tr.length; i++) {
        const schoolName = tr[i].getAttribute('data-school-name') || '';
        const schoolId = tr[i].getAttribute('data-school-id') || '';
        
        if (schoolName.includes(filter) || schoolId.includes(filter)) {
            tr[i].style.display = "";
        } else {
            tr[i].style.display = "none";
        }
    }
}

// Status filtering
function filterByStatus(status) {
    const table = document.getElementById('schoolsTable');
    const tr = table.getElementsByTagName('tr');
    
    for (let i = 1; i < tr.length; i++) {
        if (status === 'all') {
            tr[i].style.display = "";
        } else {
            const rowStatus = tr[i].getAttribute('data-status');
            if (rowStatus === status) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

// Toast notification
function showToast(title, message, type = 'info') {
    const toastContainer = document.getElementById('toastContainer') || createToastContainer();
    
    const toastId = 'toast-' + Date.now();
    const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <strong>${title}</strong><br>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `;
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = document.getElementById(toastId);
    const toast = new bootstrap.Toast(toastElement, { delay: 3000 });
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', function () {
        this.remove();
    });
}

function createToastContainer() {
    const container = document.createElement('div');
    container.id = 'toastContainer';
    container.className = 'toast-container position-fixed bottom-0 end-0 p-3';
    container.style.zIndex = '1060';
    document.body.appendChild(container);
    return container;
}

document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });
    
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Search input event listener
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('keyup', filterTable);
        searchInput.addEventListener('search', filterTable);
    }
    
    // Add filter buttons if needed
    addStatusFilterButtons();
});

function addStatusFilterButtons() {
    const table = document.getElementById('schoolsTable');
    if (!table) return;
    
    const statuses = ['all', 'verified', 'pending', 'rejected'];
    const statusLabels = {
        'all': 'All Schools',
        'verified': 'Verified',
        'pending': 'Pending',
        'rejected': 'Rejected'
    };
    
    const cardHeader = document.querySelector('.card-header-hsbl');
    if (cardHeader) {
        const filterDiv = document.createElement('div');
        filterDiv.className = 'btn-group btn-group-sm mt-2 mt-md-0';
        filterDiv.setAttribute('role', 'group');
        
        statuses.forEach(status => {
            const button = document.createElement('button');
            button.type = 'button';
            button.className = `btn btn-outline-${status === 'all' ? 'primary' : 'secondary'}`;
            button.textContent = statusLabels[status];
            button.onclick = () => filterByStatus(status);
            filterDiv.appendChild(button);
        });
        
        cardHeader.appendChild(filterDiv);
    }
}
</script>

<style>
/* Custom Styles */
.bg-purple {
    background-color: #7b1fa2 !important;
}

.text-purple {
    color: #7b1fa2 !important;
}

.border-purple {
    border-color: #7b1fa2 !important;
}

.avatar-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 8px;
    color: white;
}

/* Table Styles */
.table th {
    font-weight: 600;
    color: #37474f;
    background-color: #f8fafc;
    border-bottom: 2px solid #e0e0e0;
    font-size: 0.9rem;
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
    vertical-align: middle;
    padding: 0.75rem;
}

/* Button Styles */
.btn-outline-purple {
    color: #7b1fa2;
    border-color: #7b1fa2;
}

.btn-outline-purple:hover {
    background-color: #7b1fa2;
    color: white;
}

.btn-group-sm .btn {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

/* Modal Styles */
.modal-content {
    border-radius: 12px;
    overflow: hidden;
}

.modal-header {
    border-bottom: none;
    padding: 1.25rem 1.5rem;
}

.modal-body {
    max-height: 70vh;
    overflow-y: auto;
}

.role-card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.role-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.action-buttons .btn {
    transition: all 0.2s;
    border-width: 2px;
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Status badges */
.badge {
    font-size: 0.75rem;
    font-weight: 500;
    padding: 0.35rem 0.65rem;
    border-radius: 20px;
}

/* Role icons */
.role-icon {
    flex-shrink: 0;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.85rem;
    }
    
    .table th, .table td {
        padding: 0.5rem;
    }
    
    .card-header {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    
    .card-header .input-group {
        width: 100% !important;
        margin-top: 10px;
    }
    
    .modal-dialog {
        margin: 0.5rem;
    }
    
    .modal-body .row {
        flex-direction: column;
    }
    
    .modal-body .col-md-5 {
        border-right: none;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .action-buttons .btn {
        padding: 0.5rem;
        font-size: 0.85rem;
    }
    
    .btn-group {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 576px) {
    .modal-header .modal-title {
        font-size: 1rem;
    }
    
    .modal-school-logo {
        width: 80px !important;
        height: 80px !important;
    }
    
    .role-card {
        padding: 1rem !important;
    }
    
    .btn-group .btn {
        flex: 1;
        font-size: 0.7rem;
    }
}

/* Custom scrollbar for modal */
.modal-body::-webkit-scrollbar {
    width: 6px;
}

.modal-body::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.modal-body::-webkit-scrollbar-thumb:hover {
    background: #a1a1a1;
}

/* Animation for modal */
.modal.fade .modal-dialog {
    transform: translateY(-50px);
    transition: transform 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: translateY(0);
}

/* Hover effects for table rows */
.table tbody tr {
    cursor: pointer;
}

.table tbody tr td:first-child {
    border-top-left-radius: 8px;
    border-bottom-left-radius: 8px;
}

.table tbody tr td:last-child {
    border-top-right-radius: 8px;
    border-bottom-right-radius: 8px;
}

/* Card hover effects */
.card-hsbl {
    transition: transform 0.3s, box-shadow 0.3s;
}

.card-hsbl:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}

/* Loading spinner */
.fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Toast styles */
.toast-container {
    z-index: 1060;
}

.toast {
    backdrop-filter: blur(10px);
    background-color: rgba(var(--bs-info-rgb), 0.9) !important;
}

.toast.text-bg-success {
    background-color: rgba(var(--bs-success-rgb), 0.9) !important;
}

.toast.text-bg-error {
    background-color: rgba(var(--bs-danger-rgb), 0.9) !important;
}

.toast.text-bg-warning {
    background-color: rgba(var(--bs-warning-rgb), 0.9) !important;
}
</style>

<!-- CSRF Token for AJAX requests -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection