@extends('admin.layouts.app')
@section('title', 'Reset User Password - SBL Riau Pos')

@section('content')

{{-- Include SweetAlert2 --}}
@include('partials.sweetalert')

<div class="container mt-4">
    <!-- Page Header with Action Buttons -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="page-title">
                <i class="fas fa-key text-primary me-2"></i> Reset User Password
            </h1>
            <p class="page-subtitle">Reset password untuk user student yang lupa password</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="{{ route('admin.resetpassword.logs') }}" 
               class="btn btn-outline-primary d-flex align-items-center">
                <i class="fas fa-history me-2"></i> View Logs
            </a>
            <button type="button" 
                    class="btn btn-success d-flex align-items-center" 
                    data-bs-toggle="modal" 
                    data-bs-target="#bulkResetModal">
                <i class="fas fa-users me-2"></i> Bulk Reset
            </button>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('temp_password_show'))
    <div class="modal fade show" id="passwordShowModal" tabindex="-1" style="display: block; background: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-key me-2"></i>Password Berhasil Direset
                    </h5>
                    <button type="button" class="btn-close btn-close-white" onclick="closePasswordModal()"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        Password berhasil direset untuk: <strong>{{ session('user_name') }}</strong>
                    </div>
                    
                    <div class="card border-primary mb-3">
                        <div class="card-body py-2">
                            <h6 class="card-title text-primary mb-2">
                                <i class="fas fa-user me-2"></i>Informasi User
                            </h6>
                            <table class="table table-sm mb-0">
                                <tr>
                                    <th width="100">Nama:</th>
                                    <td>{{ session('user_name') }}</td>
                                </tr>
                                <tr>
                                    <th>Email:</th>
                                    <td>{{ session('user_email') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="card border-warning mb-3">
                        <div class="card-body py-2">
                            <h6 class="card-title text-warning mb-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>PASSWORD SEMENTARA
                            </h6>
                            <div class="text-center my-3">
                                <div class="password-display bg-light p-3 rounded border">
                                    <code class="fs-2 font-monospace fw-bold">{{ session('temp_password_show') }}</code>
                                </div>
                                <div class="mt-3">
                                    <button class="btn btn-primary" onclick="copyPassword()">
                                        <i class="fas fa-copy me-1"></i> Copy Password
                                    </button>
                                    <button class="btn btn-outline-secondary ms-2" onclick="copyMessage()">
                                        <i class="fas fa-comment me-1"></i> Copy Pesan WhatsApp
                                    </button>
                                </div>
                            </div>
                            
                            <div class="alert alert-info mt-3 mb-0">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Instruksi untuk Admin:</strong>
                                <ol class="mb-0 mt-2">
                                    <li>Copy password di atas</li>
                                    <li>Kirim ke user via WhatsApp</li>
                                    <li>Berikan pesan bahwa ini password sementara</li>
                                    <li>Minta user segera login dan ganti password</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h6 class="mb-2"><i class="fas fa-comment me-2"></i>Template Pesan WhatsApp:</h6>
                        <div class="border rounded p-3 bg-light">
                            <pre id="whatsappMessage" class="mb-0">Halo {{ session('user_name') }},

Password akun SBL Riau Pos Anda telah direset.

Password sementara:
{{ session('temp_password_show') }}

🔐 *INSTRUKSI:*
1. Login ke: {{ url('/login') }}
2. Email: {{ session('user_email') }}
3. Password: {{ session('temp_password_show') }}
4. *Segera ganti password* setelah login

⚠️ Password ini hanya sementara, segera ubah untuk keamanan akun Anda.

Terima kasih,
Admin SBL Riau Pos</pre>
                        </div>
                    </div>
                </div>
                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-secondary" onclick="closePasswordModal()">
                        <i class="fas fa-times me-1"></i> Tutup
                    </button>
                    <button type="button" class="btn btn-success" onclick="copyMessage()">
                        <i class="fab fa-whatsapp me-1"></i> Copy Pesan WhatsApp
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body py-3">
            <form method="GET" action="{{ route('admin.resetpassword.index') }}" class="row g-3 align-items-end">
                <div class="col-md-6">
                    <label class="form-label small text-muted mb-1">Search User</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input name="search" 
                               type="text" 
                               value="{{ request('search') }}"
                               class="form-control border-start-0"
                               placeholder="Search by name or email...">
                        <button class="btn btn-outline-primary border-start-0" type="button" id="clearSearchBtn">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Password Status</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-key text-muted"></i>
                        </span>
                        <select name="has_temp" class="form-select border-start-0">
                            <option value="">All Status</option>
                            <option value="has_temp" @selected(request('has_temp') == 'has_temp')>Has Temp Password</option>
                            <option value="no_temp" @selected(request('has_temp') == 'no_temp')>No Temp Password</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm flex-fill d-flex align-items-center justify-content-center">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.resetpassword.index') }}" 
                       class="btn btn-outline-secondary btn-sm flex-fill d-flex align-items-center justify-content-center">
                        <i class="fas fa-redo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.8rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="px-2 py-1" style="width: 30px;">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th class="px-2 py-1" style="width: 40px;">No</th>
                            <th class="px-2 py-1">User</th>
                            <th class="px-2 py-1">Email</th>
                            <th class="px-2 py-1" style="width: 120px;">Temp Password</th>
                            <th class="px-2 py-1" style="width: 100px;">Password Status</th>
                            <th class="px-2 py-1" style="width: 100px;">Last Reset</th>
                            <th class="px-2 py-1" style="width: 80px;">Reset Count</th>
                            <th class="px-2 py-1 text-center" style="width: 70px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr class="{{ $user->temp_password ? 'table-warning' : '' }}">
                                <td class="px-2 py-1">
                                    <input type="checkbox" 
                                           value="{{ $user->id }}" 
                                           class="form-check-input user-checkbox">
                                </td>
                                <td class="px-2 py-1 fw-medium text-muted">
                                    {{ $users->firstItem() + $index }}
                                </td>
                                <td class="px-2 py-1">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                             style="width: 28px; height: 28px; font-size: 12px;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark" 
                                                 data-bs-toggle="tooltip" 
                                                 data-bs-title="{{ $user->name }}">
                                                {{ Str::limit($user->name, 25) }}
                                            </div>
                                            @if($user->temp_password)
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-1 py-0" 
                                                      style="font-size: 0.65rem;">
                                                    Temp
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    <div class="text-truncate" style="max-width: 200px;" 
                                         data-bs-toggle="tooltip" 
                                         data-bs-title="{{ $user->email }}">
                                        {{ $user->email }}
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    @if($user->temp_password)
                                        <div class="d-flex align-items-center">
                                            <code class="font-monospace bg-light px-1 rounded border" 
                                                  style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                                {{ $user->temp_password }}
                                            </code>
                                            <button class="btn btn-sm btn-outline-primary border-0 ms-1 copy-temp-password" 
                                                    data-password="{{ $user->temp_password }}"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-title="Copy Password"
                                                    style="padding: 0.1rem 0.3rem;">
                                                <i class="fas fa-copy" style="font-size: 0.7rem;"></i>
                                            </button>
                                        </div>
                                        @if($user->temp_password_created_at)
                                            <div class="small text-muted mt-1" style="font-size: 0.7rem;">
                                                {{ $user->temp_password_created_at->format('d/m/Y H:i') }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="px-2 py-1">
                                    {!! $user->password_status_badge !!}
                                </td>
                                <td class="px-2 py-1">
                                    <div class="small">
                                        <div class="text-dark" data-bs-toggle="tooltip" 
                                             data-bs-title="{{ $user->password_changed_at ? $user->password_changed_at->format('d F Y H:i') : 'Never changed' }}">
                                            {{ $user->formatted_password_changed_at }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25">
                                        {{ $user->password_reset_count }}
                                    </span>
                                </td>
                                <td class="px-2 py-1 text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" 
                                                class="btn btn-outline-primary border-1 reset-btn"
                                                data-user-id="{{ $user->id }}"
                                                data-user-name="{{ $user->name }}"
                                                data-user-email="{{ $user->email }}"
                                                data-bs-toggle="tooltip" 
                                                data-bs-title="Reset Password">
                                            <i class="fas fa-key" style="font-size: 0.7rem;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-3 text-center">
                                    <div class="py-3">
                                        <i class="fas fa-users fa-lg text-muted mb-2"></i>
                                        <h6 class="text-muted">No Users Found</h6>
                                        <p class="text-muted small mb-2">No student users available</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Table Footer with Pagination --}}
        @if($users->hasPages() || $users->total() > 10)
            <div class="card-footer bg-white border-top px-3 py-2">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-2 mb-md-0">
                        <p class="small text-muted mb-0">
                            Showing <span class="fw-semibold">{{ $users->firstItem() ?: 0 }}</span> to 
                            <span class="fw-semibold">{{ $users->lastItem() ?: 0 }}</span> of 
                            <span class="fw-semibold">{{ $users->total() }}</span> results
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Pagination -->
                        @if($users->hasPages())
                            <div>
                                {{ $users->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    {{-- Bulk Actions --}}
    <div class="mt-3 d-none" id="bulkActions">
        <div class="card border-primary">
            <div class="card-body py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong><span id="selectedCount">0</span> user selected</strong>
                        <p class="small text-muted mb-0">Select users to reset their passwords</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-primary btn-sm" id="bulkResetBtn">
                            <i class="fas fa-key me-1"></i> Reset Selected
                        </button>
                        <button type="button" class="btn btn-outline-secondary btn-sm" id="clearSelectionBtn">
                            <i class="fas fa-times me-1"></i> Clear
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Reset Password -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="resetPasswordModalLabel">
                    <i class="fas fa-key me-2"></i>Reset Password
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="resetPasswordForm" method="POST" action="{{ route('admin.resetpassword.update') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="modalUserId">
                    
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Password sementara akan dibuat. Admin akan copy password dan kirim manual ke user via WhatsApp.
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted mb-1">User</label>
                        <div class="card bg-light">
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong id="modalUserName">-</strong><br>
                                        <small class="text-muted" id="modalUserEmail">-</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">Student</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="passwordLength" class="form-label small text-muted mb-1">Password Length</label>
                            <select class="form-select form-select-sm" id="passwordLength" name="password_length">
                                <option value="8">8 characters</option>
                                <option value="10" selected>10 characters</option>
                                <option value="12">12 characters</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small text-muted mb-1 d-block">Include Characters</label>
                            <div class="d-flex gap-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="includeUppercase" checked>
                                    <label class="form-check-label small" for="includeUppercase">A-Z</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="includeNumbers" checked>
                                    <label class="form-check-label small" for="includeNumbers">0-9</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="generatedPassword" class="form-label small text-muted mb-1">Temporary Password</label>
                        <div class="input-group input-group-sm">
                            <input type="text" 
                                   class="form-control font-monospace" 
                                   id="generatedPassword" 
                                   name="temp_password" 
                                   readonly
                                   placeholder="Click generate button">
                            <button class="btn btn-outline-primary" type="button" id="generatePasswordBtn">
                                <i class="fas fa-sync-alt"></i> Generate
                            </button>
                        </div>
                        <div class="form-text small">This password will be given to user for first login</div>
                    </div>
                    
                    <div class="alert alert-warning small mb-0">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Attention:</strong> After password reset, copy password and send to user via WhatsApp.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save me-1"></i> Save & Reset
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Bulk Reset -->
<div class="modal fade" id="bulkResetModal" tabindex="-1" aria-labelledby="bulkResetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="bulkResetModalLabel">
                    <i class="fas fa-users me-2"></i>Bulk Reset Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulkResetForm" method="POST" action="{{ route('admin.resetpassword.bulk-update') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="user_ids" id="bulkUserIds">
                    
                    <div class="alert alert-warning mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        You will reset passwords for <span id="bulkUserCount">0</span> users.
                        Each user will get a different password.
                    </div>
                    
                    <div class="mb-3">
                        <label for="bulkPasswordLength" class="form-label small text-muted mb-1">Password Length</label>
                        <select class="form-select form-select-sm" id="bulkPasswordLength" name="password_length">
                            <option value="8">8 characters</option>
                            <option value="10" selected>10 characters</option>
                            <option value="12">12 characters</option>
                        </select>
                    </div>
                    
                    <div class="alert alert-info small mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Passwords will be reset and saved in database. Admin needs to record passwords for each user.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning btn-sm">
                        <i class="fas fa-key me-1"></i> Reset All
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .page-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .page-subtitle {
        color: #7f8c8d;
        font-size: 0.875rem;
    }

    .card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .card-body {
        padding: 16px;
    }

    .table {
        font-size: 0.8rem !important;
        margin-bottom: 0;
    }

    .table th {
        font-weight: 600;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        color: #475569;
        background-color: #f8fafc;
        white-space: nowrap;
        border-bottom: 2px solid #e9ecef;
    }

    .table td {
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
        font-size: 0.8rem !important;
    }

    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }

    .btn-group-sm > .btn {
        padding: 0.15rem 0.25rem;
        border-radius: 0.2rem;
        font-size: 0.7rem;
    }

    .btn-outline-primary, .btn-outline-danger {
        border-width: 1px;
    }

    .badge {
        padding: 0.2em 0.6em;
        font-weight: 500;
        font-size: 0.75em;
        border: 1px solid transparent;
    }

    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .avatar-sm {
        width: 28px;
        height: 28px;
        font-size: 12px;
        font-weight: bold;
    }

    .font-monospace {
        font-family: 'Courier New', monospace;
        letter-spacing: 1px;
    }

    .password-display {
        background: #f8f9fa;
        border: 2px dashed #dee2e6;
    }

    #passwordShowModal {
        z-index: 1060;
    }

    /* Category badge colors */
    .bg-primary.bg-opacity-10 {
        background-color: rgba(52, 152, 219, 0.2) !important;
    }
    
    .bg-success.bg-opacity-10 {
        background-color: rgba(40, 167, 69, 0.2) !important;
    }
    
    .bg-warning.bg-opacity-10 {
        background-color: rgba(255, 193, 7, 0.2) !important;
    }
    
    .bg-danger.bg-opacity-10 {
        background-color: rgba(220, 53, 69, 0.2) !important;
    }
    
    .bg-secondary.bg-opacity-10 {
        background-color: rgba(108, 117, 125, 0.2) !important;
    }

    /* Action buttons styling */
    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
        padding: 6px 12px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
    }

    .btn-outline-secondary {
        padding: 6px 12px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Pagination customization */
    .pagination {
        margin-bottom: 0;
    }

    .page-link {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .page-item.active .page-link {
        background-color: #3498db;
        border-color: #3498db;
    }

    @media (max-width: 768px) {
        .container {
            padding-left: 10px;
            padding-right: 10px;
        }
        
        .d-flex.flex-md-row {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
        
        .d-flex.gap-2 {
            margin-top: 10px;
            width: 100%;
        }
        
        .btn-primary, .btn-outline-secondary {
            flex: 1;
            justify-content: center;
            font-size: 0.8rem;
            padding: 5px 10px;
        }
        
        .table {
            font-size: 0.75rem !important;
        }
        
        .btn-group-sm > .btn {
            padding: 0.1rem 0.2rem;
            font-size: 0.65rem;
        }
        
        .page-title {
            font-size: 1rem;
        }
        
        .card-footer .d-flex {
            flex-direction: column !important;
            gap: 10px !important;
        }
        
        .card-footer .d-flex.align-items-center {
            align-items: flex-start !important;
        }
        
        /* Mobile adjustments for table */
        .table td:nth-child(3), /* User Name */
        .table td:nth-child(4) { /* Email */
            max-width: 100px !important;
        }
        
        .badge {
            font-size: 0.65em;
            padding: 0.1em 0.4em;
        }
        
        .avatar-sm {
            width: 24px;
            height: 24px;
            font-size: 10px;
        }
    }
    
    @media (max-width: 576px) {
        .page-title {
            font-size: 0.9rem;
        }
        
        .btn-primary, .btn-outline-secondary {
            font-size: 0.75rem;
            padding: 4px 8px;
        }
        
        .table th, .table td {
            padding: 4px 6px !important;
        }
        
        .form-select-sm {
            padding: 0.125rem 0.25rem;
            font-size: 0.75rem;
        }
        
        .table-responsive {
            font-size: 0.75rem;
        }
        
        .modal-dialog {
            margin: 10px;
        }
    }
</style>

<script>
    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Elements
        const searchInput = document.querySelector('input[name="search"]');
        const clearSearchBtn = document.getElementById('clearSearchBtn');
        const selectAllCheckbox = document.getElementById('selectAll');
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');
        const bulkResetBtn = document.getElementById('bulkResetBtn');
        const clearSelectionBtn = document.getElementById('clearSelectionBtn');
        const bulkUserIds = document.getElementById('bulkUserIds');
        const bulkUserCount = document.getElementById('bulkUserCount');
        
        // Reset Password Modal Elements
        const resetButtons = document.querySelectorAll('.reset-btn');
        const modalUserId = document.getElementById('modalUserId');
        const modalUserName = document.getElementById('modalUserName');
        const modalUserEmail = document.getElementById('modalUserEmail');
        const generatedPassword = document.getElementById('generatedPassword');
        const generatePasswordBtn = document.getElementById('generatePasswordBtn');
        
        // Clear search
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                searchInput.closest('form').submit();
            });
        }
        
        // Select All functionality
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                const isChecked = this.checked;
                userCheckboxes.forEach(checkbox => checkbox.checked = isChecked);
                updateBulkActions();
            });
        }
        
        userCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });
        
        function updateBulkActions() {
            const checkedBoxes = Array.from(userCheckboxes).filter(cb => cb.checked);
            const count = checkedBoxes.length;
            
            if (count > 0) {
                selectedCount.textContent = count;
                bulkActions.classList.remove('d-none');
                bulkUserIds.value = checkedBoxes.map(cb => cb.value).join(',');
                bulkUserCount.textContent = count;
            } else {
                bulkActions.classList.add('d-none');
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = false;
                }
            }
        }
        
        clearSelectionBtn.addEventListener('click', function() {
            userCheckboxes.forEach(checkbox => checkbox.checked = false);
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = false;
            }
            updateBulkActions();
        });
        
        // Reset Password Modal
        resetButtons.forEach(button => {
            button.addEventListener('click', function() {
                const userId = this.dataset.userId;
                const userName = this.dataset.userName;
                const userEmail = this.dataset.userEmail;
                
                modalUserId.value = userId;
                modalUserName.textContent = userName;
                modalUserEmail.textContent = userEmail;
                
                generatePassword();
                
                new bootstrap.Modal(document.getElementById('resetPasswordModal')).show();
            });
        });
        
        // Generate password function
        function generatePassword() {
            const length = parseInt(document.getElementById('passwordLength').value);
            const includeUpper = document.getElementById('includeUppercase').checked;
            const includeNumbers = document.getElementById('includeNumbers').checked;
            
            let charset = '';
            if (includeUpper) charset += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            charset += 'abcdefghijklmnopqrstuvwxyz';
            if (includeNumbers) charset += '0123456789';
            
            let password = '';
            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * charset.length);
                password += charset[randomIndex];
            }
            
            generatedPassword.value = password;
        }
        
        generatePasswordBtn.addEventListener('click', generatePassword);
        
        // Bulk Reset Modal
        bulkResetBtn.addEventListener('click', function() {
            const checkedBoxes = Array.from(userCheckboxes).filter(cb => cb.checked);
            if (checkedBoxes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'No Users Selected',
                    text: 'Please select at least one user to reset password',
                    timer: 3000,
                    showConfirmButton: false
                });
                return;
            }
            
            new bootstrap.Modal(document.getElementById('bulkResetModal')).show();
        });
        
        // Form validation
        document.getElementById('resetPasswordForm').addEventListener('submit', function(e) {
            if (!generatedPassword.value) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Generate Password Required',
                    text: 'Please generate a password first',
                    timer: 3000,
                    showConfirmButton: false
                });
            }
        });
        
        // Copy temporary password from table
        document.querySelectorAll('.copy-temp-password').forEach(button => {
            button.addEventListener('click', function() {
                const password = this.dataset.password;
                navigator.clipboard.writeText(password).then(() => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Password Copied!',
                        text: 'Temporary password has been copied to clipboard',
                        timer: 2000,
                        showConfirmButton: false
                    });
                });
            });
        });
        
        // Auto-refresh every 2 minutes
        setTimeout(() => {
            if (!document.hidden) {
                location.reload();
            }
        }, 120000);
    });

    // Functions for password show modal
    function closePasswordModal() {
        document.getElementById('passwordShowModal').style.display = 'none';
    }

    function copyPassword() {
        const password = '{{ session("temp_password_show") }}';
        navigator.clipboard.writeText(password).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Password Copied!',
                text: 'Password has been copied to clipboard',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }

    function copyMessage() {
        const message = document.getElementById('whatsappMessage').textContent;
        navigator.clipboard.writeText(message).then(() => {
            Swal.fire({
                icon: 'success',
                title: 'Message Copied!',
                text: 'WhatsApp message has been copied to clipboard',
                timer: 2000,
                showConfirmButton: false
            });
        });
    }
</script>

@endsection