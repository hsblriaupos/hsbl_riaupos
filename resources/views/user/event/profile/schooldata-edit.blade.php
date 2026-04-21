@extends('user.form.layout')

@section('title', 'Team Details - SBL Student Portal')

@section('content')
<div class="container py-4">
    <!-- Header Section -->
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
                        <i class="fas fa-info-circle me-1"></i>Team Details
                    </li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary bg-gradient rounded-circle p-3 me-3 shadow-sm">
                    <i class="fas fa-users text-white fa-2x"></i>
                </div>
                <div>
                    <h1 class="h4 mb-1 fw-bold">Team Details</h1>
                    <p class="text-muted mb-0 small">View and manage your team information and documents</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Container -->
    <div id="alert-container"></div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @php
    // Helper function untuk format file size
    if (!function_exists('formatFileSize')) {
    function formatFileSize($bytes) {
    if ($bytes === null || $bytes === 0) return '0 B';
    $units = ['B', 'KB', 'MB', 'GB'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /=1024;
        $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
        }
        }

        // Gunakan data dari controller terlebih dahulu
        $team=$team ?? null;
        $team_id=$team_id ?? $school_id ?? request()->route('team_id') ?? request()->get('school_id') ?? request()->get('team_id');

        // Fallback: jika tidak ada data dari controller, coba query manual
        if (!$team && $team_id) {
        $team = \DB::table('team_list')->where('team_id', $team_id)->first();
        if (!$team) {
        $team = \DB::table('team_list')->where('school_id', $team_id)->first();
        }
        }

        // Jika masih tidak ada data, tampilkan error
        if (!$team) {
        echo '<div class="alert alert-danger">Team data not found. Please check your registration.</div>';
        return;
        }

        // Format data untuk tampilan
        $isLocked = ($team->locked_status ?? 'unlocked') === 'locked';
        $isVerified = ($team->verification_status ?? 'pending') === 'verified';
        $isPaid = ($team->payment_status ?? 'unpaid') === 'paid';

        // ============ AMBIL URL DOKUMEN ============
        $recommendationLetterUrl = null;
        $recommendationLetterName = null;
        $recommendationLetterSize = null;
        if (!empty($team->recommendation_letter)) {
        $recommendationLetterName = basename($team->recommendation_letter);
        $recommendationPath = public_path('storage/team_docs/' . $recommendationLetterName);
        if (file_exists($recommendationPath)) {
        $recommendationLetterSize = formatFileSize(filesize($recommendationPath));
        }
        if (Storage::disk('public')->exists('team_docs/' . $recommendationLetterName)) {
        $recommendationLetterUrl = Storage::disk('public')->url('team_docs/' . $recommendationLetterName);
        } elseif (file_exists(public_path('storage/team_docs/' . $recommendationLetterName))) {
        $recommendationLetterUrl = asset('storage/team_docs/' . $recommendationLetterName);
        }
        $recommendationLetterUrlView = $recommendationLetterUrl ? $recommendationLetterUrl . '?v=' . time() : null;
        }

        // 2. KORAN
        $koranUrl = null;
        $koranName = null;
        $koranSize = null;
        if (!empty($team->koran)) {
        $koranName = basename($team->koran);
        $koranPath = public_path('storage/team_docs/' . $koranName);
        if (file_exists($koranPath)) {
        $koranSize = formatFileSize(filesize($koranPath));
        }
        if (Storage::disk('public')->exists('team_docs/' . $koranName)) {
        $koranUrl = Storage::disk('public')->url('team_docs/' . $koranName);
        } elseif (file_exists(public_path('storage/team_docs/' . $koranName))) {
        $koranUrl = asset('storage/team_docs/' . $koranName);
        }
        $koranUrlView = $koranUrl ? $koranUrl . '?v=' . time() : null;
        }

        // 3. PAYMENT PROOF
        $paymentProofUrl = null;
        $paymentProofName = null;
        $paymentProofSize = null;
        if (!empty($team->payment_proof)) {
        $paymentProofName = basename($team->payment_proof);
        $paymentPath = public_path('storage/payment_proofs/' . $paymentProofName);
        if (file_exists($paymentPath)) {
        $paymentProofSize = formatFileSize(filesize($paymentPath));
        }
        if (Storage::disk('public')->exists('payment_proofs/' . $paymentProofName)) {
        $paymentProofUrl = Storage::disk('public')->url('payment_proofs/' . $paymentProofName);
        } elseif (file_exists(public_path('storage/payment_proofs/' . $paymentProofName))) {
        $paymentProofUrl = asset('storage/payment_proofs/' . $paymentProofName);
        }
        $paymentProofUrlView = $paymentProofUrl ? $paymentProofUrl . '?v=' . time() : null;
        }

        // 4. LOGO
        $logoUrl = null;
        if (!empty($team->school_logo)) {
        $logoFile = basename($team->school_logo);
        if (file_exists(public_path('storage/school_logos/' . $logoFile))) {
        $logoUrl = asset('storage/school_logos/' . $logoFile);
        } elseif (file_exists(public_path('school_logos/' . $logoFile))) {
        $logoUrl = asset('school_logos/' . $logoFile);
        }
        }

        // 5. User roles
        $userRoles = $userRoles ?? [
        'isPlayer' => false,
        'isDancer' => false,
        'isOfficial' => false
        ];

        // 6. Team ID
        $teamId = $team->team_id ?? $team->school_id ?? $team_id;

        // 7. Ekstensi file
        $koranExtension = $koranName ? strtolower(pathinfo($koranName, PATHINFO_EXTENSION)) : null;
        $recommendationExtension = $recommendationLetterName ? strtolower(pathinfo($recommendationLetterName, PATHINFO_EXTENSION)) : null;
        $paymentExtension = $paymentProofName ? strtolower(pathinfo($paymentProofName, PATHINFO_EXTENSION)) : null;
        @endphp

        <!-- Main Content -->
        <div class="row g-4">
            <!-- Left Column -->
            <div class="col-lg-4">
                <!-- Team Profile Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-users me-2 text-primary"></i>Team Profile
                        </h6>
                    </div>
                    <div class="card-body p-4">
                        <!-- Team Logo -->
                        <div class="text-center mb-4">
                            <div class="avatar-container mx-auto mb-3" style="width: 100px; height: 100px;">
                                @if($logoUrl)
                                <img src="{{ $logoUrl }}"
                                    alt="{{ $team->school_name }}"
                                    class="rounded-circle w-100 h-100 object-fit-cover border border-2 border-primary"
                                    onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($team->school_name) }}&background=1565c0&color=fff&size=100&bold=true';">
                                @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center w-100 h-100 bg-primary bg-gradient text-white fw-bold"
                                    style="font-size: 2.5rem;">
                                    {{ strtoupper(substr($team->school_name, 0, 1)) }}
                                </div>
                                @endif
                            </div>
                            <h5 class="fw-bold mb-1 fs-6">{{ $team->school_name }}</h5>
                            <p class="text-muted small mb-0">ID: {{ $teamId }}</p>
                        </div>

                        <!-- Status Badges -->
                        <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                            @if($isLocked)
                            <span class="badge bg-danger px-3 py-2 fw-normal">
                                <i class="fas fa-lock me-1"></i>Locked
                            </span>
                            @else
                            <span class="badge bg-success px-3 py-2 fw-normal">
                                <i class="fas fa-unlock me-1"></i>Unlocked
                            </span>
                            @endif

                            @if($isVerified)
                            <span class="badge bg-success px-3 py-2 fw-normal">
                                <i class="fas fa-check-circle me-1"></i>Verified
                            </span>
                            @elseif($team->verification_status === 'rejected')
                            <span class="badge bg-danger px-3 py-2 fw-normal">
                                <i class="fas fa-times-circle me-1"></i>Rejected
                            </span>
                            @else
                            <span class="badge bg-warning px-3 py-2 fw-normal">
                                <i class="fas fa-clock me-1"></i>Pending
                            </span>
                            @endif

                            @if($isPaid)
                            <span class="badge bg-success px-3 py-2 fw-normal">
                                <i class="fas fa-check-circle me-1"></i>Paid
                            </span>
                            @else
                            <span class="badge bg-danger px-3 py-2 fw-normal">
                                <i class="fas fa-times-circle me-1"></i>Unpaid
                            </span>
                            @endif
                        </div>

                        <!-- My Roles - TANPA EFEK HILANG -->
                        <div class="border rounded-2 p-2 mb-3" style="background: #f8f9fa; transition: none !important; animation: none !important; opacity: 1 !important; display: block !important;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-tag text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">My Roles</small>
                                    <div class="d-flex flex-wrap gap-1 mt-1">
                                        @if($userRoles['isPlayer'])
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success fw-normal py-1 px-2">
                                            <i class="fas fa-basketball-ball me-1"></i>Player
                                        </span>
                                        @endif
                                        @if($userRoles['isDancer'])
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger fw-normal py-1 px-2">
                                            <i class="fas fa-music me-1"></i>Dancer
                                        </span>
                                        @endif
                                        @if($userRoles['isOfficial'])
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning fw-normal py-1 px-2">
                                            <i class="fas fa-user-tie me-1"></i>Official
                                        </span>
                                        @endif
                                        @if(!$userRoles['isPlayer'] && !$userRoles['isDancer'] && !$userRoles['isOfficial'])
                                        <span class="text-muted small">No roles assigned</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Registered By - TANPA EFEK HILANG -->
                        <div class="border rounded-2 p-2 mb-0" style="background: #f8f9fa; transition: none !important; animation: none !important; opacity: 1 !important; display: block !important;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-user-check text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">Registered by</small>
                                    <span class="fw-semibold small">{{ $team->registered_by ?? 'Self' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Competition Details Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-trophy me-2 text-primary"></i>Competition Details
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Competition:</span>
                            <span class="fw-semibold small">{{ ucfirst($team->competition ?? 'Basketball') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Category:</span>
                            <span class="fw-semibold small">{{ ucfirst($team->team_category ?? 'Putra') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Season:</span>
                            <span class="fw-semibold small">{{ $team->season ?? '2025' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Series:</span>
                            <span class="fw-semibold small">{{ $team->series ?? '1' }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Referral Code:</span>
                            <span class="fw-semibold small text-primary">{{ $team->referral_code ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Status Card -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3">
                        <h6 class="mb-0 fw-semibold">
                            <i class="fas fa-credit-card me-2 text-primary"></i>Payment Info
                        </h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Payment Status:</span>
                            @if($isPaid)
                            <span class="badge bg-success fw-normal">Paid</span>
                            @else
                            <span class="badge bg-danger fw-normal">Unpaid</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Leader Payment:</span>
                            @if($team->is_leader_paid)
                            <span class="badge bg-success fw-normal">Paid</span>
                            @else
                            <span class="badge bg-danger fw-normal">Unpaid</span>
                            @endif
                        </div>
                        @if($team->payment_date)
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Payment Date:</span>
                            <span class="fw-semibold small">{{ \Carbon\Carbon::parse($team->payment_date)->timezone('Asia/Jakarta')->format('d M Y') }}</span>
                        </div>
                        @endif

                        <!-- Payment Proof -->
                        <div class="mt-3 pt-2 border-top">
                            <label class="form-label fw-semibold small mb-2">Payment Proof</label>
                            @if($paymentProofUrl)
                            <div class="d-flex align-items-center justify-content-between bg-light rounded p-2">
                                <div class="d-flex align-items-center">
                                    @if($paymentExtension === 'pdf')
                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                    @elseif(in_array($paymentExtension, ['jpg', 'jpeg', 'png', 'gif']))
                                    <i class="fas fa-file-image text-info me-2"></i>
                                    @else
                                    <i class="fas fa-file-alt text-secondary me-2"></i>
                                    @endif
                                    <div>
                                        <small class="d-block">{{ Str::limit($paymentProofName, 20) }}</small>
                                        <small class="text-muted">{{ $paymentProofSize ?? '' }}</small>
                                    </div>
                                </div>
                                <div class="d-flex gap-1">
                                    <a href="{{ $paymentProofUrlView ?? $paymentProofUrl }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('student.document.download', ['teamId' => $teamId, 'documentType' => 'payment_proof']) }}"
                                        class="btn btn-sm btn-primary">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                            @else
                            <p class="text-muted small mb-0">No payment proof uploaded</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-8">
                <!-- Team Information Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-3 text-primary"></i>
                            <div>
                                <h6 class="mb-0 fw-semibold">Team Information</h6>
                                <p class="mb-0 text-muted small">Complete team details</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="bg-light rounded p-2">
                                    <small class="text-muted d-block">School Name</small>
                                    <span class="fw-semibold small">{{ $team->school_name }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light rounded p-2">
                                    <small class="text-muted d-block">Team ID</small>
                                    <span class="fw-semibold small text-primary">{{ $teamId }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light rounded p-2">
                                    <small class="text-muted d-block">Referral Code</small>
                                    <span class="fw-semibold small">{{ $team->referral_code ?? 'N/A' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light rounded p-2">
                                    <small class="text-muted d-block">Registration Date</small>
                                    <span class="fw-semibold small">
                                        @if($team->created_at)
                                        {{ \Carbon\Carbon::parse($team->created_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                        @else N/A @endif
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-light rounded p-2">
                                    <small class="text-muted d-block">Competition</small>
                                    <span class="fw-semibold small">{{ ucfirst($team->competition ?? 'Basketball') }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-light rounded p-2">
                                    <small class="text-muted d-block">Category</small>
                                    <span class="fw-semibold small">{{ ucfirst($team->team_category ?? 'Putra') }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="bg-light rounded p-2">
                                    <small class="text-muted d-block">Season/Series</small>
                                    <span class="fw-semibold small">{{ $team->season ?? '2025' }}/{{ $team->series ?? '1' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light rounded p-2">
                                    <small class="text-muted d-block">Registered By</small>
                                    <span class="fw-semibold small">{{ $team->registered_by ?? 'Self' }}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="bg-light rounded p-2">
                                    <small class="text-muted d-block">Last Updated</small>
                                    <span class="fw-semibold small">
                                        @if($team->updated_at)
                                        {{ \Carbon\Carbon::parse($team->updated_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') }}
                                        @else N/A @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recommendation Letter Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-file-alt me-3 text-primary"></i>
                            <div>
                                <h6 class="mb-0 fw-semibold">Recommendation Letter</h6>
                                <p class="mb-0 text-muted small">Official letter from school</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        @if($recommendationLetterUrl)
                        <div class="d-flex align-items-center justify-content-between bg-light rounded p-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                    <i class="fas fa-file-pdf text-primary"></i>
                                </div>
                                <div>
                                    <span class="fw-semibold small d-block">{{ Str::limit($recommendationLetterName, 25) }}</span>
                                    <small class="text-muted">{{ $recommendationLetterSize ?? '' }}</small>
                                </div>
                            </div>
                            <div class="d-flex gap-1">
                                <a href="{{ $recommendationLetterUrlView ?? $recommendationLetterUrl }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('student.document.download', ['teamId' => $teamId, 'documentType' => 'recommendation_letter']) }}"
                                    class="btn btn-sm btn-primary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                        @else
                        <div class="alert alert-light border mb-0 py-2">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle text-warning me-2"></i>
                                <small class="text-muted">No recommendation letter uploaded</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Koran Document Card -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom py-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-newspaper me-3 text-primary"></i>
                            <div>
                                <h6 class="mb-0 fw-semibold">Koran Document</h6>
                                <p class="mb-0 text-muted small">Upload or update newspaper/document</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        <form id="koran-form" method="POST" action="{{ route('student.team.update.koran') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="team_id" value="{{ $teamId }}">

                            <div class="row g-3">
                                <!-- Current Document -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold small mb-1">Current Document</label>
                                    @if($koranUrl)
                                    <div class="d-flex align-items-center justify-content-between bg-light rounded p-2 mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-2">
                                                <i class="fas fa-file-pdf text-danger"></i>
                                            </div>
                                            <div>
                                                <span class="fw-semibold small d-block">{{ Str::limit($koranName, 25) }}</span>
                                                <small class="text-muted">{{ $koranSize ?? '' }}</small>
                                            </div>
                                        </div>
                                        <div class="d-flex gap-1">
                                            <a href="{{ $koranUrlView ?? $koranUrl }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('student.document.download', ['teamId' => $teamId, 'documentType' => 'koran']) }}"
                                                class="btn btn-sm btn-primary">
                                                <i class="fas fa-download"></i>
                                            </a>
                                        </div>
                                    </div>
                                    @else
                                    <div class="alert alert-light border mb-2 py-2">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-info-circle text-info me-2"></i>
                                            <small class="text-muted">No document uploaded</small>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <!-- Upload New -->
                                <div class="col-12">
                                    <label class="form-label fw-semibold small mb-1">
                                        <i class="fas fa-upload me-1 text-primary"></i>Upload New Document
                                    </label>
                                    <div class="input-group">
                                        <input type="file"
                                            class="form-control form-control-sm @error('koran_file') is-invalid @enderror"
                                            id="koran_file"
                                            name="koran_file"
                                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearFileInput()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                    <div id="file-preview" class="mt-1" style="display: none;">
                                        <div class="d-flex align-items-center bg-light rounded p-1">
                                            <i class="fas fa-file text-primary me-1"></i>
                                            <span id="file-name" class="small"></span>
                                        </div>
                                    </div>
                                    <div class="form-text small mt-1">
                                        <i class="fas fa-info-circle me-1"></i>
                                        PDF, DOC, JPG, PNG. Max 5MB
                                    </div>
                                </div>

                                <!-- Submit -->
                                <div class="col-12 mt-2">
                                    @if($isLocked)
                                    <div class="alert alert-warning mb-0 py-1 small">
                                        <i class="fas fa-lock me-1"></i>Team locked. Cannot update.
                                    </div>
                                    @else
                                    <button type="submit" class="btn btn-primary btn-sm px-3" id="submit-koran-btn">
                                        <i class="fas fa-upload me-1"></i>Upload Document
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <a href="{{ route('student.event.histories') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i>Back
                    </a>
                    <a href="{{ route('student.team.list.with_id', $teamId) }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-users me-1"></i>Team Members
                    </a>
                    <small class="text-muted">
                        @if($isLocked)
                        <span class="text-danger">🔒 Team locked</span>
                        @else
                        <span class="text-success">✏️ Can update koran</span>
                        @endif
                    </small>
                </div>
            </div>
        </div>
</div>

<script>
    function previewFileName(input) {
        const preview = document.getElementById('file-preview');
        const fileName = document.getElementById('file-name');
        if (input.files && input.files[0]) {
            fileName.textContent = input.files[0].name;
            preview.style.display = 'block';
        } else {
            preview.style.display = 'none';
        }
    }

    function clearFileInput() {
        const fileInput = document.getElementById('koran_file');
        fileInput.value = '';
        document.getElementById('file-preview').style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
        const koranForm = document.getElementById('koran-form');
        if (koranForm) {
            koranForm.addEventListener('submit', function(e) {
                const fileInput = document.getElementById('koran_file');
                if (!fileInput.files || !fileInput.files[0]) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'No File',
                        text: 'Please select a file.',
                        confirmButtonColor: '#1565c0'
                    });
                    return false;
                }
                const fileSize = fileInput.files[0].size / 1024 / 1024;
                if (fileSize > 5) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'File Too Large',
                        text: 'Max 5MB.',
                        confirmButtonColor: '#1565c0'
                    });
                    return false;
                }
                Swal.fire({
                    title: 'Uploading...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                const submitBtn = document.getElementById('submit-koran-btn');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Uploading...';
                    submitBtn.disabled = true;
                }
            });
        }

        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('
            success ') }}',
            confirmButtonColor: '#1565c0'
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('
            error ') }}',
            confirmButtonColor: '#d33'
        });
        @endif
    });
</script>

<style>
    /* Font & Base */
    body {
        font-size: 0.875rem;
        line-height: 1.5;
    }

    /* Card - TANPA ANIMASI HILANG */
    .card {
        border-radius: 10px;
    }

    /* Badge */
    .badge {
        font-weight: 500;
        font-size: 0.7rem;
        padding: 0.25rem 0.6rem;
    }

    /* Form */
    .form-control-sm,
    .btn-sm {
        font-size: 0.75rem;
    }

    .form-control:focus {
        border-color: #1565c0;
        box-shadow: 0 0 0 2px rgba(21, 101, 192, 0.1);
    }

    /* Avatar */
    .avatar-container {
        position: relative;
    }

    .object-fit-cover {
        object-fit: cover;
    }

    /* Alert - TANPA EFEK HILANG */
    .alert {
        transition: none !important;
        animation: none !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 1rem !important;
        }

        .btn-sm {
            width: 100%;
            margin-bottom: 0.25rem;
        }

        .d-flex.gap-1 {
            flex-direction: column;
        }
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection