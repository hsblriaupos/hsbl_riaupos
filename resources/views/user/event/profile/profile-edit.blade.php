@extends('user.form.layout')

@section('title', 'Edit Profile - HSBL Student Portal')

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
                    <li class="breadcrumb-item active" aria-current="page">
                        <i class="fas fa-user-edit me-1"></i>Edit Profile
                    </li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary bg-gradient rounded-circle p-3 me-3 shadow-sm">
                    <i class="fas fa-user-edit text-white fa-2x"></i>
                </div>
                <div>
                    <h1 class="h3 mb-1 fw-bold">Edit Profile</h1>
                    <p class="text-muted mb-0">Update your personal information and manage your account security</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Container untuk pesan dinamis -->
    <div id="alert-container"></div>

    <div class="row g-4">
        <!-- Left Column: Account Info -->
        <div class="col-lg-4">
            <!-- User Profile Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-user-circle me-2 text-primary"></i>Profile Information
                    </h5>
                </div>
                <div class="card-body text-center p-4">
                    <!-- Avatar -->
                    @php
                        $user = Auth::user();
                        
                        // Generate avatar dari kolom avatar atau email jika tidak ada
                        if (!empty($user->avatar) && filter_var($user->avatar, FILTER_VALIDATE_URL)) {
                            $avatarUrl = $user->avatar;
                        } elseif (!empty($user->avatar)) {
                            // Jika avatar adalah path lokal
                            $avatarUrl = asset('storage/' . $user->avatar);
                        } else {
                            // Generate avatar dari email menggunakan avataaars
                            $seed = $user->email ?? $user->id ?? rand(1, 999999);
                            $avatarUrl = "https://api.dicebear.com/7.x/avataaars/svg?seed=" . urlencode($seed) . "&backgroundColor=65c9ff,b6e3f4,c0aede,d1d4f9,ffd5dc,ffdfbf";
                        }
                        
                        // Hitung account age dalam hari (tanpa koma)
                        $createdAt = $user->created_at;
                        $now = now();
                        $accountAgeDays = floor($createdAt->diffInDays($now));
                        
                        // Cek apakah user memiliki password temporary
                        $hasTempPassword = !empty($user->temp_password);
                        $isUsingTempPassword = $hasTempPassword && empty($user->temp_password_used_at);
                    @endphp
                    
                    <div class="avatar-container mx-auto mb-3" style="width: 120px; height: 120px;">
                        <img src="{{ $avatarUrl }}" 
                             class="img-fluid rounded-circle border border-3 border-primary w-100 h-100 object-fit-cover"
                             alt="Profile Picture"
                             onerror="this.onerror=null; this.src='{{ asset('uploads/default-avatar.png') }}'">
                    </div>

                    <!-- User Info -->
                    <h5 class="fw-bold mb-2">{{ $user->name }}</h5>
                    <p class="text-muted small mb-3">
                        <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                    </p>
                    
                    <!-- Role Badge -->
                    <div class="mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary py-2 px-3">
                            <i class="fas fa-user-graduate me-1"></i>Student Account
                        </span>
                    </div>

                    <!-- Account Status -->
                    <div class="mb-4">
                        @if($isUsingTempPassword)
                            <span class="badge bg-warning text-dark" id="temp-password-badge">
                                <i class="fas fa-exclamation-triangle me-1"></i>Using Temporary Password
                            </span>
                        @elseif($hasTempPassword)
                            <span class="badge bg-info" id="temp-password-badge">
                                <i class="fas fa-info-circle me-1"></i>Has Temporary Password
                            </span>
                        @else
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Secure Account
                            </span>
                        @endif
                    </div>
                    
                    <!-- Account Info -->
                    <div class="border-top pt-3">
                        <div class="row text-start">
                            <div class="col-6">
                                <small class="text-muted d-block">Member Since</small>
                                <small class="fw-semibold">{{ $user->created_at->format('d M Y') }}</small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Account Age</small>
                                <small class="fw-semibold">{{ $accountAgeDays }} days</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Statistics -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2 text-primary"></i>Account Statistics
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Password Changes -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-key text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted small">Password Changes</div>
                            <div class="fw-bold fs-5">{{ $user->password_reset_count ?? 0 }}</div>
                        </div>
                    </div>

                    <!-- Account Age Detail -->
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-calendar-check text-success"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted small">Account Age Detail</div>
                            <div class="fw-bold">
                                <small>{{ $accountAgeDays }} days</small>
                            </div>
                        </div>
                    </div>

                    <!-- Password Status -->
                    <div class="d-flex align-items-center">
                        <div class="bg-info bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="fas fa-shield-alt text-info"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="text-muted small">Password Status</div>
                            <div class="fw-bold">
                                @if($isUsingTempPassword)
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-clock me-1"></i>Using Temporary
                                    </span>
                                @elseif($hasTempPassword)
                                    <span class="badge bg-info">
                                        <i class="fas fa-info-circle me-1"></i>Has Temporary
                                    </span>
                                @else
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i>Permanent
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Forms -->
        <div class="col-lg-8">
            <!-- Main Form Container -->
            <div class="card border-0 shadow-sm">
                <!-- Form Header -->
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-edit fa-lg me-3 text-primary"></i>
                        <div>
                            <h2 class="h5 mb-0">Edit Profile Information</h2>
                            <p class="mb-0 text-muted small">Update your personal details and security settings</p>
                        </div>
                    </div>
                </div>

                <!-- Form Content -->
                <form method="POST" action="{{ route('student.profile.update') }}" class="p-4" id="profile-form">
                    @csrf
                    @method('PUT')

                    <!-- Personal Information Section -->
                    <div class="mb-4">
                        <h5 class="mb-3 text-primary">
                            <i class="fas fa-user me-2"></i>Personal Information
                        </h5>
                        
                        <div class="row g-3">
                            <!-- Name -->
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">
                                    <i class="fas fa-user me-1 text-primary"></i>Full Name
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-user text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           placeholder="Enter your full name"
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text mt-1">
                                    Your full name as it appears on official documents
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-1 text-primary"></i>Email Address
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           placeholder="Enter your email"
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text mt-1">
                                    @if($user->email_verified_at)
                                        <span class="text-success">
                                            <i class="fas fa-check-circle me-1"></i>Verified on {{ $user->email_verified_at->format('d M Y') }}
                                        </span>
                                    @else
                                        <span class="text-warning">
                                            <i class="fas fa-exclamation-circle me-1"></i>Email not verified
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Account Info (Read-only) -->
                        <div class="row g-3 mt-4">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-calendar-plus me-1 text-primary"></i>Account Created
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-calendar text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control bg-light" 
                                           value="{{ $user->created_at->format('d F Y, H:i') }}" 
                                           readonly>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    <i class="fas fa-clock me-1"></i>Member since {{ $user->created_at->diffForHumans() }}
                                </small>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-history me-1 text-primary"></i>Account Age
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-hourglass-half text-muted"></i>
                                    </span>
                                    <input type="text" 
                                           class="form-control bg-light" 
                                           value="{{ $accountAgeDays }} days" 
                                           readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Change Section -->
                    <div class="mb-4" id="password-section">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="text-primary mb-0">
                                <i class="fas fa-key me-2"></i>Change Password
                            </h5>
                            @if($isUsingTempPassword)
                                <span class="badge bg-warning text-dark fs-6" id="temp-password-header-badge">
                                    <i class="fas fa-exclamation-triangle me-1"></i>Using Temporary Password
                                </span>
                            @elseif($hasTempPassword)
                                <span class="badge bg-info fs-6" id="temp-password-header-badge">
                                    <i class="fas fa-info-circle me-1"></i>Has Temporary Password
                                </span>
                            @endif
                        </div>

                        <!-- Password Hint Section -->
                        <div id="password-hint"></div>

                        <!-- Password Reset Count Info -->
                        @if($user->password_reset_count > 0)
                            <div class="alert alert-info alert-hsbl mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle fa-lg me-3"></i>
                                    <div>
                                        <p class="mb-0">
                                            You have changed your password <strong>{{ $user->password_reset_count }}</strong> time(s).
                                            @if($user->password_changed_at)
                                                <br><small class="text-muted">
                                                    Last changed: {{ $user->password_changed_at->format('d M Y H:i') }}
                                                </small>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Note: Password change is optional -->
                        <div class="alert alert-info alert-hsbl mb-4" id="password-change-note">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle fa-lg me-3"></i>
                                <div>
                                    <p class="mb-0">
                                        <strong>Password change is optional.</strong> You can update your name and email without changing your password.
                                        Only fill new password fields if you want to change your password.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Hidden current password field untuk verifikasi otomatis -->
                        <input type="hidden" id="auto_current_password" name="current_password" value="{{ session('auto_password_token') }}">
                        
                        <!-- Password Fields -->
                        <div class="row g-3">
                            <!-- New Password -->
                            <div class="col-md-6">
                                <label for="new_password" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-1 text-primary"></i>New Password (Optional)
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-key text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control @error('new_password') is-invalid @enderror" 
                                           id="new_password" 
                                           name="new_password"
                                           placeholder="Enter new password (leave empty to keep current)"
                                           autocomplete="new-password">
                                    <button type="button" 
                                            class="btn btn-outline-secondary" 
                                            onclick="togglePassword('new_password')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text mt-1">
                                    Minimum 8 characters with letters and numbers
                                </div>
                            </div>

                            <!-- Confirm New Password -->
                            <div class="col-md-6">
                                <label for="new_password_confirmation" class="form-label fw-semibold">
                                    <i class="fas fa-lock me-1 text-primary"></i>Confirm New Password
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-key text-muted"></i>
                                    </span>
                                    <input type="password" 
                                           class="form-control" 
                                           id="new_password_confirmation" 
                                           name="new_password_confirmation"
                                           placeholder="Confirm new password (if changing)"
                                           autocomplete="new-password">
                                    <button type="button" 
                                            class="btn btn-outline-secondary" 
                                            onclick="togglePassword('new_password_confirmation')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                <div class="form-text mt-1">
                                    Required only if changing password
                                </div>
                            </div>
                        </div>

                        <!-- Password Strength Meter -->
                        <div class="mt-4" id="password-strength-section" style="display: none;">
                            <div class="d-flex justify-content-between mb-1">
                                <small class="fw-semibold">Password Strength</small>
                                <small id="password-strength-text" class="fw-bold">None</small>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div id="password-strength-bar" 
                                     class="progress-bar" 
                                     style="width: 0%"></div>
                            </div>
                            <div id="password-requirements" class="mt-2">
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <div id="req-length" class="d-flex align-items-center text-danger">
                                            <i class="far fa-circle me-2 fa-xs"></i>
                                            <small>Minimum 8 characters</small>
                                        </div>
                                        <div id="req-uppercase" class="d-flex align-items-center text-danger mt-1">
                                            <i class="far fa-circle me-2 fa-xs"></i>
                                            <small>At least one uppercase letter</small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div id="req-lowercase" class="d-flex align-items-center text-danger">
                                            <i class="far fa-circle me-2 fa-xs"></i>
                                            <small>At least one lowercase letter</small>
                                        </div>
                                        <div id="req-number" class="d-flex align-items-center text-danger mt-1">
                                            <i class="far fa-circle me-2 fa-xs"></i>
                                            <small>At least one number</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="border-top pt-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                                </a>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="reset" class="btn btn-outline-secondary" id="reset-btn">
                                    <i class="fas fa-redo me-2"></i>Reset
                                </button>
                                <button type="submit" class="btn btn-primary px-4" id="submit-btn">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .avatar-container {
        position: relative;
    }

    .object-fit-cover {
        object-fit: cover;
    }

    .input-group-text {
        border-right: none;
        background-color: #f8f9fa;
    }

    .input-group .form-control {
        border-left: none;
    }

    .input-group .form-control:focus {
        border-color: #ced4da;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }

    .input-group .btn-outline-secondary {
        border-left: none;
        border-color: #ced4da;
    }

    .input-group .btn-outline-secondary:hover {
        background-color: #f8f9fa;
        border-color: #ced4da;
    }

    .card {
        border-radius: 10px;
    }

    .form-control:focus {
        border-color: #1565c0;
        box-shadow: 0 0 0 0.2rem rgba(21, 101, 192, 0.25);
    }

    .alert-hsbl {
        border-left-width: 4px;
        border-radius: 8px;
    }

    .btn {
        border-radius: 6px;
    }

    /* Animations */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .card {
        animation: fadeIn 0.5s ease-out;
    }
    
    /* Make cards more compact */
    .card-body {
        padding: 1.25rem;
    }
    
    .card-header {
        padding: 0.75rem 1.25rem;
    }
    
    .p-4 {
        padding: 1.5rem !important;
    }
    
    /* Responsive adjustments */
    @media (max-width: 992px) {
        .col-lg-4, .col-lg-8 {
            margin-bottom: 1rem;
        }
        
        .card {
            margin-bottom: 1rem;
        }
    }
    
    /* Consistent form spacing */
    .form-label {
        margin-bottom: 0.5rem;
    }
    
    .form-text {
        font-size: 0.85rem;
    }
    
    /* Highlight for temp password */
    .temp-password-highlight {
        background-color: #fff3cd !important;
        border: 1px solid #ffeaa7 !important;
        border-radius: 8px;
        padding: 15px;
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
    }
    
    /* Custom alert for sweetalert */
    .swal2-popup {
        border-radius: 12px !important;
    }
    
    /* Password verification status */
    .is-valid {
        border-color: #198754 !important;
    }
    
    .is-invalid {
        border-color: #dc3545 !important;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Toggle Password Visibility
function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const icon = field.parentElement.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        field.type = 'password';
        icon.className = 'fas fa-eye';
    }
}

// Password Strength Checker
const newPasswordInput = document.getElementById('new_password');
if (newPasswordInput) {
    newPasswordInput.addEventListener('input', function(e) {
        const password = e.target.value;
        const strengthSection = document.getElementById('password-strength-section');
        const strengthBar = document.getElementById('password-strength-bar');
        const strengthText = document.getElementById('password-strength-text');
        
        // Show/hide strength section based on input
        if (password.length > 0) {
            strengthSection.style.display = 'block';
        } else {
            strengthSection.style.display = 'none';
            return;
        }
        
        // Reset requirements
        const requirements = ['req-length', 'req-uppercase', 'req-lowercase', 'req-number'];
        requirements.forEach(reqId => {
            const element = document.getElementById(reqId);
            element.className = 'd-flex align-items-center text-danger';
            element.querySelector('i').className = 'far fa-circle me-2 fa-xs';
        });
        
        let strength = 0;
        
        // Check length
        if (password.length >= 8) {
            strength += 25;
            updateRequirement('req-length', true);
        }
        
        // Check uppercase
        if (/[A-Z]/.test(password)) {
            strength += 25;
            updateRequirement('req-uppercase', true);
        }
        
        // Check lowercase
        if (/[a-z]/.test(password)) {
            strength += 25;
            updateRequirement('req-lowercase', true);
        }
        
        // Check number
        if (/[0-9]/.test(password)) {
            strength += 25;
            updateRequirement('req-number', true);
        }
        
        // Update strength bar
        strengthBar.style.width = strength + '%';
        
        // Update strength text and color
        if (strength === 0) {
            strengthBar.className = 'progress-bar';
            strengthText.textContent = 'None';
            strengthText.className = 'fw-bold text-muted';
        } else if (strength < 50) {
            strengthBar.className = 'progress-bar bg-danger';
            strengthText.textContent = 'Weak';
            strengthText.className = 'fw-bold text-danger';
        } else if (strength < 75) {
            strengthBar.className = 'progress-bar bg-warning';
            strengthText.textContent = 'Fair';
            strengthText.className = 'fw-bold text-warning';
        } else if (strength < 100) {
            strengthBar.className = 'progress-bar bg-info';
            strengthText.textContent = 'Good';
            strengthText.className = 'fw-bold text-info';
        } else {
            strengthBar.className = 'progress-bar bg-success';
            strengthText.textContent = 'Strong';
            strengthText.className = 'fw-bold text-success';
        }
    });
}

function updateRequirement(id, met) {
    const element = document.getElementById(id);
    if (met) {
        element.className = 'd-flex align-items-center text-success';
        element.querySelector('i').className = 'fas fa-check-circle me-2 fa-xs';
    }
}

// Show SweetAlert
function showSweetAlert(icon, title, text, confirmButtonText = 'OK') {
    return Swal.fire({
        icon: icon,
        title: title,
        text: text,
        confirmButtonText: confirmButtonText,
        confirmButtonColor: '#1565c0',
        background: '#fff',
        color: '#333',
        customClass: {
            popup: 'swal2-popup'
        }
    });
}

// Flag untuk mencegah alert muncul berulang kali
let hasShownPasswordAlert = false;

// Get current password info and set auto password
async function initializeAutoPassword() {
    try {
        const response = await fetch('{{ route("student.profile.password.info") }}');
        const data = await response.json();
        
        if (data.success) {
            const passwordHintElement = document.getElementById('password-hint');
            const isUsingTempPassword = data.is_using_temp_password;
            const hasTempPassword = data.has_temp_password;
            
            // Update password hint
            if (passwordHintElement) {
                if (isUsingTempPassword) {
                    passwordHintElement.innerHTML = `
                        <div class="alert alert-warning alert-hsbl mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <div>
                                    <strong>You are using a temporary password</strong><br>
                                    <small>Your current password will be automatically verified. You can set a new password below or continue using the temporary one.</small>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    // Highlight password section
                    document.getElementById('password-section').classList.add('temp-password-highlight');
                } else if (hasTempPassword) {
                    passwordHintElement.innerHTML = `
                        <div class="alert alert-info alert-hsbl mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>
                                    <strong>You have a temporary password stored</strong><br>
                                    <small>Your current password will be automatically verified. Only fill new password fields if you want to change it.</small>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    passwordHintElement.innerHTML = `
                        <div class="alert alert-info alert-hsbl mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>
                                    <strong>Password change is optional</strong><br>
                                    <small>Your current password will be automatically verified. Only fill new password fields if you want to change your password.</small>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }
            
            // Jika menggunakan temporary password, buat token verifikasi
            if (isUsingTempPassword || hasTempPassword) {
                try {
                    const tokenResponse = await fetch('{{ route("student.profile.generate.password.token") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    });
                    
                    const tokenData = await tokenResponse.json();
                    
                    if (tokenData.success && tokenData.token) {
                        // Set token ke hidden field
                        document.getElementById('auto_current_password').value = tokenData.token;
                        console.log('Auto-password token generated for temp password');
                    }
                } catch (error) {
                    console.error('Error generating password token:', error);
                }
            }
            
        }
    } catch (error) {
        console.error('Error initializing auto password:', error);
    }
}

// Form Submission Handler
document.getElementById('profile-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const form = this;
    const newPassword = document.getElementById('new_password').value;
    const confirmPassword = document.getElementById('new_password_confirmation').value;
    const nameChanged = document.getElementById('name').value !== "{{ $user->name }}";
    const emailChanged = document.getElementById('email').value !== "{{ $user->email }}";
    
    // Check if password is being changed
    if (newPassword) {
        // Check password match
        if (newPassword !== confirmPassword) {
            await showSweetAlert('error', 'Password Mismatch', 'New password and confirmation do not match.');
            document.getElementById('new_password_confirmation').focus();
            return;
        }
        
        // Check password strength
        if (newPassword.length < 8) {
            await showSweetAlert('warning', 'Password Too Short', 'Password must be at least 8 characters long.');
            document.getElementById('new_password').focus();
            return;
        }
        
        // Check if password contains at least one letter and one number
        if (!/[a-zA-Z]/.test(newPassword) || !/[0-9]/.test(newPassword)) {
            await showSweetAlert('warning', 'Password Requirements', 'Password must contain both letters and numbers.');
            document.getElementById('new_password').focus();
            return;
        }
    }
    
    // Get password info to determine if using temp password
    let isUsingTempPassword = false;
    let hasTempPassword = false;
    try {
        const infoResponse = await fetch('{{ route("student.profile.password.info") }}');
        const infoData = await infoResponse.json();
        if (infoData.success) {
            isUsingTempPassword = infoData.is_using_temp_password;
            hasTempPassword = infoData.has_temp_password;
        }
    } catch (error) {
        console.error('Error getting password info:', error);
    }
    
    // Build confirmation message
    let changes = [];
    if (nameChanged) changes.push('name');
    if (emailChanged) changes.push('email address');
    if (newPassword) {
        if (isUsingTempPassword) {
            changes.push('password (replacing temporary password)');
        } else {
            changes.push('password');
        }
    }
    
    let confirmationMessage = '';
    if (changes.length > 0) {
        confirmationMessage = `You are updating your ${changes.join(', ')}.`;
    } else {
        confirmationMessage = 'No changes detected.';
    }
    
    // Special warning for temp password replacement
    if (isUsingTempPassword && newPassword) {
        confirmationMessage += '\n\n⚠️ IMPORTANT: This will permanently replace your temporary password.';
    } else if (isUsingTempPassword && !newPassword) {
        confirmationMessage += '\n\nℹ️ NOTE: Your temporary password will be promoted to your main password.';
    }
    
    // Ask for confirmation
    const result = await Swal.fire({
        title: 'Confirm Changes',
        html: confirmationMessage,
        icon: changes.length > 0 ? 'question' : 'info',
        showCancelButton: true,
        confirmButtonText: 'Yes, Update Profile',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#1565c0',
        cancelButtonColor: '#6c757d',
        background: '#fff',
        color: '#333',
        customClass: {
            popup: 'swal2-popup'
        }
    });
    
    if (!result.isConfirmed) {
        return;
    }
    
    // Show loading state on button
    const submitBtn = document.getElementById('submit-btn');
    const originalBtnText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
    submitBtn.disabled = true;
    
    try {
        // Submit form via AJAX
        const formData = new FormData(form);
        
        // Add auto verification flag jika menggunakan temporary password
        if (isUsingTempPassword || hasTempPassword) {
            formData.append('auto_verify_temp_password', 'true');
        }
        
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        });
        
        const data = await response.json();
        
        if (response.ok && data.success) {
            // Success
            await showSweetAlert(
                'success', 
                'Profile Updated!', 
                data.message || 'Your profile has been updated successfully.'
            );
            
            // If password was changed and user had temp password, update UI
            if (data.temp_password_cleared) {
                // Remove temp password indicators
                const tempBadge = document.getElementById('temp-password-badge');
                const tempHeaderBadge = document.getElementById('temp-password-header-badge');
                const passwordSection = document.getElementById('password-section');
                
                if (tempBadge) {
                    tempBadge.outerHTML = `
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i>Secure Account
                        </span>
                    `;
                }
                
                if (tempHeaderBadge) {
                    tempHeaderBadge.remove();
                }
                
                if (passwordSection) {
                    passwordSection.classList.remove('temp-password-highlight');
                }
                
                // Update password hint
                const passwordHintElement = document.getElementById('password-hint');
                if (passwordHintElement) {
                    passwordHintElement.innerHTML = `
                        <div class="alert alert-success alert-hsbl mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2"></i>
                                <div>
                                    <strong>Temporary password removed</strong><br>
                                    <small>Your password has been updated successfully. Temporary password is no longer available.</small>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }
            
            // Reload page to reflect changes
            setTimeout(() => {
                window.location.reload();
            }, 1500);
            
        } else {
            // Error from server
            let errorMessage = data.message || 'An error occurred while updating your profile.';
            
            if (data.errors) {
                // Show validation errors
                const errorList = Object.values(data.errors).flat().join('<br>');
                errorMessage = errorList;
            }
            
            await showSweetAlert('error', 'Update Failed', errorMessage);
            
            // Reset button state
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        }
        
    } catch (error) {
        console.error('Error:', error);
        await showSweetAlert('error', 'Network Error', 'Unable to connect to server. Please check your internet connection.');
        
        // Reset button state
        submitBtn.innerHTML = originalBtnText;
        submitBtn.disabled = false;
    }
});

// Reset button handler
document.getElementById('reset-btn').addEventListener('click', function(e) {
    e.preventDefault();
    
    Swal.fire({
        title: 'Reset Form?',
        text: 'Are you sure you want to reset all changes?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, Reset',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        background: '#fff',
        color: '#333',
        customClass: {
            popup: 'swal2-popup'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('profile-form').reset();
            
            // Hide password strength section
            const strengthSection = document.getElementById('password-strength-section');
            if (strengthSection) {
                strengthSection.style.display = 'none';
            }
            
            // Reset the alert flag
            hasShownPasswordAlert = false;
            
            // Show success message
            showSweetAlert('success', 'Form Reset', 'All fields have been reset to their original values.');
            
            // Re-initialize auto password
            initializeAutoPassword();
        }
    });
});

// Initialize form on load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize auto password system
    initializeAutoPassword();
    
    // Auto-focus on name field
    setTimeout(() => {
        document.getElementById('name').focus();
    }, 300);
    
    // Enhanced password field handling WITHOUT auto-showing alerts
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('new_password_confirmation');
    
    if (newPasswordInput) {
        // Hanya tampilkan tooltip jika user sudah mulai mengetik dan belum pernah melihat tooltip
        newPasswordInput.addEventListener('input', function(e) {
            if (e.target.value.length > 0 && !hasShownPasswordAlert) {
                // Hanya tampilkan tooltip sekali saja
                hasShownPasswordAlert = true;
                
                // Tampilkan tooltip kecil, bukan alert besar
                const tooltip = document.createElement('div');
                tooltip.className = 'alert alert-info alert-hsbl mt-2 py-2';
                tooltip.innerHTML = `
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Password change is optional.</strong> 
                        If you don't want to change your password, leave this field empty.
                    </small>
                `;
                
                // Tambahkan tooltip setelah input field
                const inputGroup = newPasswordInput.closest('.input-group');
                if (inputGroup && inputGroup.parentElement) {
                    // Hapus tooltip sebelumnya jika ada
                    const existingTooltip = inputGroup.parentElement.querySelector('.alert-hsbl.mt-2');
                    if (existingTooltip) {
                        existingTooltip.remove();
                    }
                    
                    // Tambahkan tooltip baru
                    inputGroup.parentElement.appendChild(tooltip);
                    
                    // Hapus tooltip setelah 5 detik
                    setTimeout(() => {
                        if (tooltip.parentElement) {
                            tooltip.remove();
                        }
                    }, 5000);
                }
            }
        });
        
        // Remove tooltip when field is cleared
        newPasswordInput.addEventListener('blur', function(e) {
            if (e.target.value.length === 0) {
                const inputGroup = newPasswordInput.closest('.input-group');
                if (inputGroup && inputGroup.parentElement) {
                    const tooltip = inputGroup.parentElement.querySelector('.alert-hsbl.mt-2');
                    if (tooltip) {
                        tooltip.remove();
                    }
                }
            }
        });
    }
    
    // Handle confirm password field similarly
    if (confirmPasswordInput) {
        confirmPasswordInput.addEventListener('input', function(e) {
            const newPassword = document.getElementById('new_password').value;
            
            // Jika confirm password diisi tapi new password kosong
            if (e.target.value.length > 0 && newPassword.length === 0 && !hasShownPasswordAlert) {
                hasShownPasswordAlert = true;
                
                // Set focus ke new password field
                newPasswordInput.focus();
                
                // Tampilkan tooltip kecil
                const tooltip = document.createElement('div');
                tooltip.className = 'alert alert-warning alert-hsbl mt-2 py-2';
                tooltip.innerHTML = `
                    <small>
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Please enter a new password first before confirming it.
                    </small>
                `;
                
                // Tambahkan tooltip setelah input field
                const inputGroup = newPasswordInput.closest('.input-group');
                if (inputGroup && inputGroup.parentElement) {
                    // Hapus tooltip sebelumnya jika ada
                    const existingTooltip = inputGroup.parentElement.querySelector('.alert-hsbl.mt-2');
                    if (existingTooltip) {
                        existingTooltip.remove();
                    }
                    
                    // Tambahkan tooltip baru
                    inputGroup.parentElement.appendChild(tooltip);
                    
                    // Hapus tooltip setelah 5 detik
                    setTimeout(() => {
                        if (tooltip.parentElement) {
                            tooltip.remove();
                        }
                    }, 5000);
                }
            }
        });
    }
    
    // Check for session messages
    @if(session('success'))
        showSweetAlert('success', 'Success!', '{{ session('success') }}');
    @endif
    
    @if(session('error'))
        showSweetAlert('error', 'Error!', '{{ session('error') }}');
    @endif
    
    @if($errors->any())
        let errorMessages = '';
        @foreach($errors->all() as $error)
            errorMessages += '• {{ $error }}<br>';
        @endforeach
        
        showSweetAlert('error', 'Validation Error', errorMessages);
    @endif
});
</script>
@endpush