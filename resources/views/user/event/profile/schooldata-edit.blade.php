@extends('user.form.layout')

@section('title', 'Team Details - HSBL Student Portal')

@section('content')
<div class="container py-4">
    <!-- Header Section - Consistent with event histories -->
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
                    <h1 class="h3 mb-1 fw-bold">Team Details</h1>
                    <p class="text-muted mb-0">View and manage your team information and documents</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Container untuk pesan dinamis -->
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
                    $bytes /= 1024;
                    $i++;
                }
                return round($bytes, 2) . ' ' . $units[$i];
            }
        }

        // Gunakan data dari controller terlebih dahulu
        $team = $team ?? null;
        $team_id = $team_id ?? $school_id ?? request()->route('team_id') ?? request()->get('school_id') ?? request()->get('team_id');
        
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
        
        // ============ AMBIL URL DOKUMEN DENGAN CACHE BUSTING ============
        
        // 1. RECOMMENDATION LETTER
        $recommendationLetterUrl = null;
        $recommendationLetterName = null;
        $recommendationLetterSize = null;
        if (!empty($team->recommendation_letter)) {
            $recommendationLetterName = basename($team->recommendation_letter);
            
            // Dapatkan ukuran file
            $recommendationPath = public_path('storage/team_docs/' . $recommendationLetterName);
            if (file_exists($recommendationPath)) {
                $recommendationLetterSize = formatFileSize(filesize($recommendationPath));
            }
            
            if (isset($team->recommendation_letter_url)) {
                $recommendationLetterUrl = $team->recommendation_letter_url;
            } else {
                if (Storage::disk('public')->exists('team_docs/' . $recommendationLetterName)) {
                    $recommendationLetterUrl = Storage::disk('public')->url('team_docs/' . $recommendationLetterName);
                } elseif (file_exists(public_path('storage/team_docs/' . $recommendationLetterName))) {
                    $recommendationLetterUrl = asset('storage/team_docs/' . $recommendationLetterName);
                }
            }
            
            // Cache busting - TAPI JANGAN DIGUNAKAN UNTUK DOWNLOAD
            if ($recommendationLetterUrl) {
                $recommendationLetterUrlView = $recommendationLetterUrl . '?v=' . time();
            } else {
                $recommendationLetterUrlView = $recommendationLetterUrl;
            }
        }
        
        // 2. KORAN - DENGAN CACHE BUSTING
        $koranUrl = null;
        $koranName = null;
        $koranSize = null;
        if (!empty($team->koran)) {
            $koranName = basename($team->koran);
            
            // Dapatkan ukuran file
            $koranPath = public_path('storage/team_docs/' . $koranName);
            if (file_exists($koranPath)) {
                $koranSize = formatFileSize(filesize($koranPath));
            }
            
            if (isset($team->koran_url)) {
                $koranUrl = $team->koran_url;
            } else {
                if (Storage::disk('public')->exists('team_docs/' . $koranName)) {
                    $koranUrl = Storage::disk('public')->url('team_docs/' . $koranName);
                } elseif (file_exists(public_path('storage/team_docs/' . $koranName))) {
                    $koranUrl = asset('storage/team_docs/' . $koranName);
                }
            }
            
            // Cache busting - UNTUK VIEW SAJA, BUKAN UNTUK DOWNLOAD
            if ($koranUrl) {
                $koranUrlView = $koranUrl . '?v=' . time();
            } else {
                $koranUrlView = $koranUrl;
            }
        }
        
        // 3. PAYMENT PROOF
        $paymentProofUrl = null;
        $paymentProofName = null;
        $paymentProofSize = null;
        if (!empty($team->payment_proof)) {
            $paymentProofName = basename($team->payment_proof);
            
            // Dapatkan ukuran file
            $paymentPath = public_path('storage/payment_proofs/' . $paymentProofName);
            if (file_exists($paymentPath)) {
                $paymentProofSize = formatFileSize(filesize($paymentPath));
            }
            
            if (isset($team->payment_proof_url)) {
                $paymentProofUrl = $team->payment_proof_url;
            } else {
                if (Storage::disk('public')->exists('payment_proofs/' . $paymentProofName)) {
                    $paymentProofUrl = Storage::disk('public')->url('payment_proofs/' . $paymentProofName);
                } elseif (file_exists(public_path('storage/payment_proofs/' . $paymentProofName))) {
                    $paymentProofUrl = asset('storage/payment_proofs/' . $paymentProofName);
                }
            }
            
            // Cache busting - UNTUK VIEW SAJA
            if ($paymentProofUrl) {
                $paymentProofUrlView = $paymentProofUrl . '?v=' . time();
            }
        }
        
        // 4. LOGO
        $logoUrl = null;
        if (!empty($team->school_logo)) {
            if (isset($team->logo_url)) {
                $logoUrl = $team->logo_url;
            } else {
                $logoFile = basename($team->school_logo);
                if (file_exists(public_path('storage/school_logos/' . $logoFile))) {
                    $logoUrl = asset('storage/school_logos/' . $logoFile);
                } elseif (file_exists(public_path('school_logos/' . $logoFile))) {
                    $logoUrl = asset('school_logos/' . $logoFile);
                }
            }
        }
        
        // 5. User roles dari controller
        $userRoles = $userRoles ?? [
            'isPlayer' => false,
            'isDancer' => false,
            'isOfficial' => false
        ];
        
        // 6. Gunakan team_id yang benar
        $teamId = $team->team_id ?? $team->school_id ?? $team_id;
        
        // 7. Dapatkan ekstensi file untuk icon
        $koranExtension = $koranName ? strtolower(pathinfo($koranName, PATHINFO_EXTENSION)) : null;
        $recommendationExtension = $recommendationLetterName ? strtolower(pathinfo($recommendationLetterName, PATHINFO_EXTENSION)) : null;
        $paymentExtension = $paymentProofName ? strtolower(pathinfo($paymentProofName, PATHINFO_EXTENSION)) : null;
    @endphp

    <!-- Main Content Card -->
    <div class="row g-4">
        <!-- Left Column: Team Info & Status -->
        <div class="col-lg-4">
            <!-- Team Profile Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2 text-primary"></i>Team Profile
                    </h5>
                </div>
                <div class="card-body p-4">
                    <!-- Team Logo -->
                    <div class="text-center mb-4">
                        <div class="avatar-container mx-auto mb-3" style="width: 120px; height: 120px;">
                            @if($logoUrl)
                            <img src="{{ $logoUrl }}" 
                                 alt="{{ $team->school_name }}" 
                                 class="rounded-circle w-100 h-100 object-fit-cover border border-3 border-primary"
                                 onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($team->school_name) }}&background=1565c0&color=fff&size=120&bold=true';">
                            @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center w-100 h-100 bg-primary bg-gradient text-white fw-bold" 
                                 style="font-size: 3rem;">
                                {{ strtoupper(substr($team->school_name, 0, 1)) }}
                            </div>
                            @endif
                        </div>
                        <h4 class="fw-bold mb-1">{{ $team->school_name }}</h4>
                        <p class="text-muted small mb-0">Team ID: {{ $teamId }}</p>
                    </div>

                    <!-- Status Badges -->
                    <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                        @if($isLocked)
                        <span class="badge bg-danger rounded-pill px-3 py-2">
                            <i class="fas fa-lock me-1"></i>Locked
                        </span>
                        @else
                        <span class="badge bg-success rounded-pill px-3 py-2">
                            <i class="fas fa-unlock me-1"></i>Unlocked
                        </span>
                        @endif
                        
                        @if($isVerified)
                        <span class="badge bg-success rounded-pill px-3 py-2">
                            <i class="fas fa-check-circle me-1"></i>Verified
                        </span>
                        @elseif($team->verification_status === 'rejected')
                        <span class="badge bg-danger rounded-pill px-3 py-2">
                            <i class="fas fa-times-circle me-1"></i>Rejected
                        </span>
                        @else
                        <span class="badge bg-warning rounded-pill px-3 py-2">
                            <i class="fas fa-clock me-1"></i>Pending
                        </span>
                        @endif
                        
                        @if($isPaid)
                        <span class="badge bg-success rounded-pill px-3 py-2">
                            <i class="fas fa-check-circle me-1"></i>Paid
                        </span>
                        @else
                        <span class="badge bg-danger rounded-pill px-3 py-2">
                            <i class="fas fa-times-circle me-1"></i>Unpaid
                        </span>
                        @endif
                    </div>

                    <!-- My Roles -->
                    <div class="alert alert-light border mb-3">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-user-tag text-primary me-3 fa-lg"></i>
                            <div>
                                <small class="text-muted d-block">My Roles in this Team</small>
                                <div class="d-flex flex-wrap gap-2 mt-1">
                                    @if($userRoles['isPlayer'])
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success">
                                        <i class="fas fa-basketball-ball me-1"></i>Player
                                    </span>
                                    @endif
                                    @if($userRoles['isDancer'])
                                    <span class="badge bg-pink-100 text-pink-600 border border-pink">
                                        <i class="fas fa-music me-1"></i>Dancer
                                    </span>
                                    @endif
                                    @if($userRoles['isOfficial'])
                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning">
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

                    <!-- Registered By -->
                    <div class="alert alert-light border mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-user-check text-primary me-3 fa-lg"></i>
                            <div>
                                <small class="text-muted d-block">Registered by</small>
                                <span class="fw-bold">{{ $team->registered_by ?? 'Self' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Competition Details Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-trophy me-2 text-primary"></i>Competition Details
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Competition:</span>
                        <span class="fw-bold text-primary">{{ ucfirst($team->competition ?? 'Basketball') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Category:</span>
                        <span class="fw-bold">{{ ucfirst($team->team_category ?? 'Putra') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Season:</span>
                        <span class="fw-bold">{{ $team->season ?? '2025' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Series:</span>
                        <span class="fw-bold">{{ $team->series ?? '1' }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Referral Code:</span>
                        <span class="fw-bold text-primary">{{ $team->referral_code ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>

            <!-- Payment Status Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card me-2 text-primary"></i>Payment Information
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Payment Status:</span>
                        @if($isPaid)
                        <span class="badge bg-success">Paid</span>
                        @else
                        <span class="badge bg-danger">Unpaid</span>
                        @endif
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Leader Payment:</span>
                        @if($team->is_leader_paid)
                        <span class="badge bg-success">Paid</span>
                        @else
                        <span class="badge bg-danger">Unpaid</span>
                        @endif
                    </div>
                    @if($team->payment_date)
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Payment Date:</span>
                        <span class="fw-bold">{{ \Carbon\Carbon::parse($team->payment_date)->timezone('Asia/Jakarta')->format('d M Y') }}</span>
                    </div>
                    @endif
                    
                    <!-- Payment Proof Document -->
                    <div class="mt-3 pt-3 border-top">
                        <label class="form-label fw-semibold mb-2">
                            <i class="fas fa-file-invoice me-1 text-primary"></i>Payment Proof
                        </label>
                        @if($paymentProofUrl)
                        <div class="d-flex align-items-center justify-content-between bg-light rounded p-2">
                            <div class="d-flex align-items-center">
                                @if($paymentExtension === 'pdf')
                                    <i class="fas fa-file-pdf text-danger me-2 fa-lg"></i>
                                @elseif(in_array($paymentExtension, ['jpg', 'jpeg', 'png', 'gif']))
                                    <i class="fas fa-file-image text-info me-2 fa-lg"></i>
                                @else
                                    <i class="fas fa-file-alt text-secondary me-2 fa-lg"></i>
                                @endif
                                <div>
                                    <small class="d-block fw-medium">{{ $paymentProofName }}</small>
                                    <small class="text-muted">{{ $paymentProofSize ?? 'Unknown' }}</small>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ $paymentProofUrlView ?? $paymentProofUrl }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                                <!-- 🔥 PERBAIKAN: Gunakan route download yang BARU -->
                                <a href="{{ route('student.document.download', ['teamId' => $teamId, 'documentType' => 'payment_proof']) }}" 
                                   class="btn btn-sm btn-primary">
                                    <i class="fas fa-download me-1"></i>Download
                                </a>
                            </div>
                        </div>
                        @else
                        <p class="text-muted small mb-0">
                            <i class="fas fa-exclamation-circle me-1"></i>No payment proof uploaded
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Documents & Editables -->
        <div class="col-lg-8">
            <!-- Team Information Card (View Only) -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-info-circle fa-lg me-3 text-primary"></i>
                        <div>
                            <h2 class="h5 mb-0">Team Information</h2>
                            <p class="mb-0 text-muted small">Complete team details and registration data</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="bg-light rounded p-3">
                                <small class="text-muted d-block mb-1">School Name</small>
                                <span class="fw-bold">{{ $team->school_name }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light rounded p-3">
                                <small class="text-muted d-block mb-1">Team ID</small>
                                <span class="fw-bold text-primary">{{ $teamId }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light rounded p-3">
                                <small class="text-muted d-block mb-1">Referral Code</small>
                                <span class="fw-bold text-primary">{{ $team->referral_code ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light rounded p-3">
                                <small class="text-muted d-block mb-1">Registration Date</small>
                                <span class="fw-bold">
                                    @if($team->created_at)
                                        {{ \Carbon\Carbon::parse($team->created_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light rounded p-3">
                                <small class="text-muted d-block mb-1">Competition</small>
                                <span class="fw-bold">{{ ucfirst($team->competition ?? 'Basketball') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light rounded p-3">
                                <small class="text-muted d-block mb-1">Category</small>
                                <span class="fw-bold">{{ ucfirst($team->team_category ?? 'Putra') }}</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="bg-light rounded p-3">
                                <small class="text-muted d-block mb-1">Season/Series</small>
                                <span class="fw-bold">{{ $team->season ?? '2025' }}-{{ $team->series ?? '1' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light rounded p-3">
                                <small class="text-muted d-block mb-1">Registered By</small>
                                <span class="fw-bold">{{ $team->registered_by ?? 'Self' }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="bg-light rounded p-3">
                                <small class="text-muted d-block mb-1">Last Updated</small>
                                <span class="fw-bold">
                                    @if($team->updated_at)
                                        {{ \Carbon\Carbon::parse($team->updated_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommendation Letter Card (View Only) -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-file-alt fa-lg me-3 text-primary"></i>
                        <div>
                            <h2 class="h5 mb-0">Recommendation Letter</h2>
                            <p class="mb-0 text-muted small">Official recommendation letter from school</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    @if($recommendationLetterUrl)
                    <div class="d-flex align-items-center justify-content-between bg-light rounded p-3">
                        <div class="d-flex align-items-center">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                                @if($recommendationExtension === 'pdf')
                                    <i class="fas fa-file-pdf text-primary fa-lg"></i>
                                @else
                                    <i class="fas fa-file-alt text-primary fa-lg"></i>
                                @endif
                            </div>
                            <div>
                                <span class="fw-medium d-block">{{ $recommendationLetterName }}</span>
                                <small class="text-muted d-block">Recommendation Letter</small>
                                <small class="text-muted">{{ $recommendationLetterSize ?? '' }}</small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ $recommendationLetterUrlView ?? $recommendationLetterUrl }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="fas fa-eye me-1"></i>View
                            </a>
                            <!-- 🔥 PERBAIKAN: Gunakan route download yang BARU -->
                            <a href="{{ route('student.document.download', ['teamId' => $teamId, 'documentType' => 'recommendation_letter']) }}" 
                               class="btn btn-sm btn-primary">
                                <i class="fas fa-download me-1"></i>Download
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-light border mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-exclamation-circle text-warning me-3 fa-lg"></i>
                            <div>
                                <span class="fw-medium d-block">No Recommendation Letter</span>
                                <small class="text-muted">This team has not uploaded a recommendation letter yet.</small>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- ========== 🔥 PERBAIKAN UTAMA: KORAN DOCUMENT CARD DENGAN ROUTE DOWNLOAD BARU ========== -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-newspaper fa-lg me-3 text-primary"></i>
                        <div>
                            <h2 class="h5 mb-0">Koran / Newspaper Document</h2>
                            <p class="mb-0 text-muted small">Upload or update your team's newspaper/document</p>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Form untuk upload koran -->
                    <form id="koran-form" method="POST" action="{{ route('student.team.update.koran') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="team_id" value="{{ $teamId }}">
                        
                        <div class="row g-4">
                            <!-- Current Koran Document -->
                            <div class="col-12">
                                <label class="form-label fw-semibold">Current Document</label>
                                @if($koranUrl)
                                <div class="d-flex align-items-center justify-content-between bg-light rounded p-3 mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-pink-100 rounded-circle p-3 me-3">
                                            @if($koranExtension === 'pdf')
                                                <i class="fas fa-file-pdf text-pink-600 fa-lg"></i>
                                            @elseif(in_array($koranExtension, ['jpg', 'jpeg', 'png', 'gif']))
                                                <i class="fas fa-file-image text-pink-600 fa-lg"></i>
                                            @else
                                                <i class="fas fa-file-alt text-pink-600 fa-lg"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="fw-medium d-block">{{ $koranName }}</span>
                                            <small class="text-muted d-block">
                                                Uploaded: @if($team->updated_at)
                                                    {{ \Carbon\Carbon::parse($team->updated_at)->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                                                @else
                                                    N/A
                                                @endif
                                            </small>
                                            @if($koranSize)
                                            <small class="text-muted d-block">
                                                Size: {{ $koranSize }}
                                            </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ $koranUrlView ?? $koranUrl }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                            <i class="fas fa-eye me-1"></i>View
                                        </a>
                                        <!-- 🔥 PERBAIKAN: Gunakan route download yang BARU -->
                                        <a href="{{ route('student.document.download', ['teamId' => $teamId, 'documentType' => 'koran']) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-download me-1"></i>Download
                                        </a>
                                    </div>
                                </div>
                                @else
                                <div class="alert alert-light border mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-info-circle text-info me-3 fa-lg"></i>
                                        <div>
                                            <span class="fw-medium d-block">No Document Uploaded</span>
                                            <small class="text-muted">Please upload your team's newspaper/document using the form below.</small>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>

                            <!-- Upload New Koran -->
                            <div class="col-12">
                                <label for="koran_file" class="form-label fw-semibold">
                                    <i class="fas fa-upload me-1 text-primary"></i>Upload New Document
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <input type="file" 
                                           class="form-control @error('koran_file') is-invalid @enderror" 
                                           id="koran_file" 
                                           name="koran_file"
                                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                           onchange="previewFileName(this)"
                                           required>
                                    <button type="button" class="btn btn-outline-secondary" onclick="clearFileInput()">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div id="file-preview" class="mt-2" style="display: none;">
                                    <div class="d-flex align-items-center bg-light rounded p-2">
                                        <i class="fas fa-file text-primary me-2"></i>
                                        <span id="file-name" class="small"></span>
                                    </div>
                                </div>
                                <div class="form-text mt-2">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG. Max size: 5MB
                                </div>
                                @error('koran_file')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Submit Button -->
                            <div class="col-12 mt-3">
                                @if($isLocked)
                                <div class="alert alert-warning mb-0">
                                    <i class="fas fa-lock me-2"></i>
                                    This team is locked. You cannot update documents at this time.
                                </div>
                                @else
                                <button type="submit" class="btn btn-primary px-4" id="submit-koran-btn">
                                    <i class="fas fa-upload me-2"></i>Upload Koran Document
                                </button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <a href="{{ route('student.event.histories') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Event Histories
                    </a>
                </div>
                <div>
                    <a href="{{ route('student.team.list.with_id', $teamId) }}" class="btn btn-outline-success">
                        <i class="fas fa-users me-2"></i>View Team Members
                    </a>
                </div>
                <div>
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        @if($isLocked)
                        <span class="text-danger">This team is locked. Contact administrator for changes.</span>
                        @else
                        <span class="text-success">You can only update the Koran document.</span>
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Preview file name when selected
function previewFileName(input) {
    const filePreview = document.getElementById('file-preview');
    const fileName = document.getElementById('file-name');
    
    if (input.files && input.files[0]) {
        fileName.textContent = input.files[0].name;
        filePreview.style.display = 'block';
    } else {
        filePreview.style.display = 'none';
    }
}

// Clear file input
function clearFileInput() {
    const fileInput = document.getElementById('koran_file');
    fileInput.value = '';
    document.getElementById('file-preview').style.display = 'none';
}

// Form submission dengan SweetAlert
document.addEventListener('DOMContentLoaded', function() {
    const koranForm = document.getElementById('koran-form');
    if (koranForm) {
        koranForm.addEventListener('submit', function(e) {
            const fileInput = document.getElementById('koran_file');
            
            // Validasi file
            if (!fileInput.files || !fileInput.files[0]) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'No File Selected',
                    text: 'Please select a file to upload.',
                    confirmButtonColor: '#1565c0'
                });
                return false;
            }
            
            // Validasi ukuran file (max 5MB)
            const fileSize = fileInput.files[0].size / 1024 / 1024;
            if (fileSize > 5) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'Maximum file size is 5MB. Your file is ' + fileSize.toFixed(2) + 'MB.',
                    confirmButtonColor: '#1565c0'
                });
                return false;
            }
            
            // Validasi tipe file
            const allowedTypes = ['.pdf', '.doc', '.docx', '.jpg', '.jpeg', '.png'];
            const fileName = fileInput.files[0].name;
            const fileExt = fileName.substring(fileName.lastIndexOf('.')).toLowerCase();
            
            if (!allowedTypes.includes(fileExt)) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Please upload PDF, DOC, DOCX, JPG, JPEG, or PNG files only.',
                    confirmButtonColor: '#1565c0'
                });
                return false;
            }
            
            // Show loading state
            Swal.fire({
                title: 'Uploading...',
                text: 'Please wait while your document is being uploaded.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Show loading state on button
            const submitBtn = document.getElementById('submit-koran-btn');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
                submitBtn.disabled = true;
            }
        });
    }
    
    // Tampilkan SweetAlert untuk session messages
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: '{{ session('success') }}',
            timer: 3000,
            showConfirmButton: false
        }).then(() => {
            location.reload();
        });
    @endif
    
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            text: '{{ session('error') }}',
            confirmButtonColor: '#d33',
            didClose: () => {
                const submitBtn = document.getElementById('submit-koran-btn');
                if (submitBtn) {
                    submitBtn.innerHTML = '<i class="fas fa-upload me-2"></i>Upload Koran Document';
                    submitBtn.disabled = false;
                }
            }
        });
    @endif
    
    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert:not(.alert-warning)');
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
/* Pink palette for Dancer */
.bg-pink-100 {
    background-color: #fce4ec !important;
}

.text-pink-600 {
    color: #d81b60 !important;
}

.border-pink {
    border-color: #d81b60 !important;
}

/* Card styles */
.card {
    border-radius: 10px;
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
}

/* Avatar styles */
.avatar-container {
    position: relative;
}

.object-fit-cover {
    object-fit: cover;
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

/* Form styles */
.form-control:focus {
    border-color: #1565c0;
    box-shadow: 0 0 0 0.2rem rgba(21, 101, 192, 0.25);
}

.input-group-text {
    background-color: #f8f9fa;
}

/* Animation */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.card {
    animation: fadeIn 0.5s ease-out;
}

/* Required field indicator */
.form-label .text-danger {
    margin-left: 2px;
}

/* Responsive styles */
@media (max-width: 768px) {
    .card-body {
        padding: 1.25rem !important;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 1rem;
    }
    
    .d-flex.justify-content-between .btn {
        width: 100%;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        width: 100%;
    }
    
    .d-flex.gap-2 .btn {
        width: 100%;
    }
}

@media (max-width: 576px) {
    .avatar-container {
        width: 80px !important;
        height: 80px !important;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.25rem 0.5rem;
    }
}
</style>

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection