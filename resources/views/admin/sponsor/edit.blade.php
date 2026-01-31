@extends('admin.layouts.app')
@section('title', 'Edit Sponsor - Administrator')

@section('content')

{{-- Load SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

{{-- Toast Notification with SweetAlert2 --}}
@if(session('success'))
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
      });
    });
  </script>
@endif

<div class="container mt-4">
    <!-- Page Header with Action Buttons -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="page-title">
                <i class="fas fa-edit text-primary me-2"></i> Edit Sponsor
            </h1>
            <p class="page-subtitle">Update sponsor information</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="{{ route('admin.sponsor.sponsor') }}" 
               class="btn btn-outline-primary d-flex align-items-center">
                <i class="fas fa-list me-2"></i> Sponsor List
            </a>
            <a href="{{ route('admin.sponsor.create') }}" 
               class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-plus me-2"></i> Add New
            </a>
        </div>
    </div>

    {{-- Error Alert --}}
    @if ($errors->any())
    <div class="alert alert-danger border-2 border-start-0 border-end-0 border-danger bg-danger bg-opacity-10 mb-4">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle me-3 text-danger"></i>
            <div>
                <h6 class="alert-heading mb-1">Please fix the following errors:</h6>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                    <li class="small">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Form Container -->
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.sponsor.update', $sponsor->id) }}" enctype="multipart/form-data" id="sponsorForm">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    {{-- Left Column --}}
                    <div class="col-lg-8">
                        {{-- Sponsor Name --}}
                        <div class="mb-4">
                            <label for="sponsor_name" class="form-label fw-semibold">
                                <i class="fas fa-building text-primary me-2"></i> Sponsor Name
                                <span class="text-danger">*</span>
                            </label>
                            <input id="sponsor_name" 
                                   name="sponsor_name" 
                                   type="text"
                                   value="{{ old('sponsor_name', $sponsor->sponsor_name) }}"
                                   class="form-control"
                                   placeholder="Enter sponsor company name..."
                                   required
                                   autofocus>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Full name of the sponsor company or organization
                                </small>
                            </div>
                            @error('sponsor_name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Website URL --}}
                        <div class="mb-4">
                            <label for="sponsors_web" class="form-label fw-semibold">
                                <i class="fas fa-globe text-primary me-2"></i> Website URL
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-link"></i>
                                </span>
                                <input id="sponsors_web" 
                                       name="sponsors_web" 
                                       type="url"
                                       value="{{ old('sponsors_web', $sponsor->sponsors_web) }}"
                                       class="form-control"
                                       placeholder="https://example.com">
                            </div>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Official website (optional)
                                </small>
                            </div>
                            @error('sponsors_web')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Update Notes --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-lightbulb text-warning me-2"></i> Update Guidelines
                            </label>
                            <div class="alert alert-info bg-opacity-10 border-info">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-info-circle mt-1 me-3 text-info"></i>
                                    <div>
                                        <strong class="d-block mb-1">Tips for updating sponsors:</strong>
                                        <ul class="mb-0 ps-3">
                                            <li class="small mb-1">Leave logo field empty to keep current logo</li>
                                            <li class="small mb-1">Update category if sponsor level has changed</li>
                                            <li class="small mb-1">Verify website URL is still accurate</li>
                                            <li class="small">Review all changes before saving</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="col-lg-4">
                        {{-- Category --}}
                        <div class="mb-4">
                            <label for="category" class="form-label fw-semibold">
                                <i class="fas fa-tag text-primary me-2"></i> Category
                                <span class="text-danger">*</span>
                            </label>
                            <select id="category" 
                                    name="category" 
                                    required 
                                    class="form-select">
                                <option value="">-- Select Category --</option>
                                @foreach($categories as $category)
                                <option value="{{ $category }}" 
                                        {{ old('category', $sponsor->category) == $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Select the appropriate sponsor level
                                </small>
                            </div>
                            @error('category')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Logo Upload --}}
                        <div class="mb-4">
                            <label for="logo" class="form-label fw-semibold">
                                <i class="fas fa-image text-primary me-2"></i> Logo
                            </label>
                            <div class="card border-dashed">
                                <div class="card-body text-center p-4">
                                    {{-- Current Logo --}}
                                    @if($sponsor->logo)
                                    <div class="mb-3" id="currentLogoContainer">
                                        <img src="{{ asset('uploads/sponsors/'.$sponsor->logo) }}" 
                                             alt="{{ $sponsor->sponsor_name }}" 
                                             class="img-fluid rounded mb-3"
                                             style="max-height: 150px; object-fit: contain;">
                                        <p class="text-muted small mb-2">Current Logo</p>
                                    </div>
                                    @else
                                    <div class="alert alert-warning py-2 mb-3" id="noLogoAlert">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <small>No logo uploaded</small>
                                    </div>
                                    @endif

                                    {{-- New Logo Preview --}}
                                    <div id="newLogoPreview" class="mb-3 d-none">
                                        <img src="" 
                                             alt="New Logo Preview" 
                                             class="img-fluid rounded mb-3"
                                             style="max-height: 150px; object-fit: contain;">
                                        <button type="button" 
                                                class="btn btn-sm btn-danger"
                                                onclick="removeNewLogo()">
                                            <i class="fas fa-trash me-1"></i> Remove New Logo
                                        </button>
                                    </div>

                                    {{-- Upload Area --}}
                                    <div id="uploadArea" class="{{ old('logo') ? 'd-none' : '' }}">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-3"></i>
                                        <p class="small text-muted mb-3">Click to upload new logo (optional)</p>
                                        <input id="logo" 
                                               name="logo" 
                                               type="file" 
                                               accept=".jpg,.jpeg,.png,.svg,.webp,.gif"
                                               class="form-control d-none"
                                               onchange="previewNewLogo(event)">
                                        <label for="logo" 
                                               class="btn btn-outline-primary btn-sm cursor-pointer">
                                            <i class="fas fa-upload me-2"></i> Change Logo
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Leave empty to keep current logo. Max 2MB, JPG/PNG/SVG/WebP/GIF.
                                </small>
                            </div>
                            @error('logo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Sponsor Details --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-info-circle text-info me-2"></i> Sponsor Details
                            </label>
                            <div class="card bg-light">
                                <div class="card-body p-3 small">
                                    <div class="mb-2">
                                        <strong class="text-muted d-block">Created:</strong>
                                        <span>{{ $sponsor->created_at->format('M d, Y H:i') }}</span>
                                    </div>
                                    <div class="mb-2">
                                        <strong class="text-muted d-block">Last Updated:</strong>
                                        <span>{{ $sponsor->updated_at->format('M d, Y H:i') }}</span>
                                    </div>
                                    <div class="mb-0">
                                        <strong class="text-muted d-block">Current Category:</strong>
                                        <span class="badge bg-primary bg-opacity-10 text-primary">
                                            {{ $sponsor->category }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form Actions --}}
                <div class="border-top pt-4 mt-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="reset" 
                                    class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-2"></i> Reset Changes
                            </button>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.sponsor.sponsor') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                            <button type="button" 
                                    onclick="validateAndSubmit()"
                                    class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update Sponsor
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Delete Card --}}
    <div class="card mt-4 border-danger">
        <div class="card-header bg-danger bg-opacity-10 text-danger border-danger">
            <i class="fas fa-exclamation-triangle me-2"></i> Danger Zone
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Delete Sponsor</h6>
                    <p class="small text-muted mb-0">
                        Once deleted, this sponsor cannot be recovered. All associated data will be permanently removed.
                    </p>
                </div>
                <button type="button" 
                        onclick="confirmDelete()"
                        class="btn btn-outline-danger">
                    <i class="fas fa-trash me-2"></i> Delete Sponsor
                </button>
            </div>
        </div>
    </div>

    {{-- Hidden Delete Form --}}
    <form id="deleteForm" method="POST" action="{{ route('admin.sponsor.destroy', $sponsor->id) }}" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
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

    .card.border-dashed {
        border: 2px dashed #dee2e6;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .card.border-danger {
        border-color: #f8d7da;
    }

    .card-header.border-danger {
        border-color: #f8d7da;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .accordion-button {
        font-size: 0.875rem;
        padding: 0.5rem 1rem;
    }

    .accordion-button:not(.collapsed) {
        background-color: #f8f9fa;
        color: #495057;
        box-shadow: none;
    }

    .accordion-button:focus {
        border-color: #dee2e6;
        box-shadow: none;
    }

    .accordion-body {
        font-size: 0.875rem;
        background-color: #f8f9fa;
    }

    .form-text small {
        font-size: 0.75rem;
    }

    .alert-info.bg-opacity-10 {
        background-color: rgba(23, 162, 184, 0.1) !important;
    }

    .alert-info.border-info {
        border-color: rgba(23, 162, 184, 0.3) !important;
    }

    .bg-danger.bg-opacity-10 {
        background-color: rgba(220, 53, 69, 0.1) !important;
    }

    @media (max-width: 768px) {
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .d-flex.flex-md-row {
            flex-direction: column !important;
            align-items: flex-start !important;
        }
        
        .d-flex.gap-2 {
            margin-top: 15px;
            width: 100%;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }
        
        .row.g-4 {
            gap: 1.5rem !important;
        }
    }
</style>

<script>
    // New Logo Preview Functionality
    function previewNewLogo(event) {
        const input = event.target;
        const preview = document.getElementById('newLogoPreview');
        const currentLogo = document.getElementById('currentLogoContainer');
        const noLogoAlert = document.getElementById('noLogoAlert');
        const uploadArea = document.getElementById('uploadArea');
        
        if (input.files && input.files[0]) {
            // Validate file type
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/svg+xml', 'image/webp', 'image/gif'];
            const fileType = input.files[0].type;
            
            if (!allowedTypes.includes(fileType)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Please upload only JPG, PNG, SVG, WebP, or GIF files',
                    confirmButtonColor: '#3498db'
                });
                input.value = '';
                return;
            }
            
            // Validate file size (2MB max)
            const maxSize = 2 * 1024 * 1024; // 2MB
            if (input.files[0].size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'Logo size should not exceed 2MB',
                    confirmButtonColor: '#3498db'
                });
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.querySelector('img').src = e.target.result;
                preview.classList.remove('d-none');
                
                // Hide current logo and upload area
                if (currentLogo) currentLogo.style.display = 'none';
                if (noLogoAlert) noLogoAlert.style.display = 'none';
                uploadArea.style.display = 'none';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeNewLogo() {
        const preview = document.getElementById('newLogoPreview');
        const currentLogo = document.getElementById('currentLogoContainer');
        const noLogoAlert = document.getElementById('noLogoAlert');
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('logo');
        
        preview.classList.add('d-none');
        
        // Show appropriate elements
        if (currentLogo) currentLogo.style.display = 'block';
        if (noLogoAlert) noLogoAlert.style.display = 'block';
        uploadArea.style.display = 'block';
        
        fileInput.value = '';
    }
    
    // Form Validation and Submission
    function validateAndSubmit() {
        console.log('Update Sponsor clicked'); // Debug log
        
        // Get form values
        const sponsorName = document.getElementById('sponsor_name').value.trim();
        const category = document.getElementById('category').value;
        const logoInput = document.getElementById('logo');
        
        // Validation
        if (!sponsorName) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please enter sponsor name',
                confirmButtonColor: '#3498db'
            });
            document.getElementById('sponsor_name').focus();
            return false;
        }
        
        if (!category) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please select a category',
                confirmButtonColor: '#3498db'
            });
            document.getElementById('category').focus();
            return false;
        }
        
        // Validate logo file if uploaded
        if (logoInput.files && logoInput.files.length > 0) {
            const file = logoInput.files[0];
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/svg+xml', 'image/webp', 'image/gif'];
            const maxSize = 2 * 1024 * 1024; // 2MB
            
            if (!allowedTypes.includes(file.type)) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Please upload only JPG, PNG, SVG, WebP, or GIF files',
                    confirmButtonColor: '#3498db'
                });
                return false;
            }
            
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'Logo size should not exceed 2MB',
                    confirmButtonColor: '#3498db'
                });
                return false;
            }
        }
        
        // Show confirmation with SweetAlert
        Swal.fire({
            title: 'Update Sponsor?',
            html: 'Are you sure you want to update this sponsor?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3498db',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, update sponsor',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading indicator
                Swal.fire({
                    title: 'Updating...',
                    text: 'Please wait while we update the sponsor',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                // Submit the form
                setTimeout(() => {
                    document.getElementById('sponsorForm').submit();
                }, 500);
            }
        });
        
        return false;
    }
    
    // Delete Confirmation
    function confirmDelete() {
        Swal.fire({
            title: 'Delete Sponsor?',
            html: `Are you sure you want to delete <strong>"${'{{ $sponsor->sponsor_name }}'}"</strong>?<br><br>
                   <small class="text-danger">This action cannot be undone!</small>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            reverseButtons: true,
            showLoaderOnConfirm: true,
            preConfirm: () => {
                return new Promise((resolve) => {
                    // Submit the delete form
                    document.getElementById('deleteForm').submit();
                    resolve();
                });
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                // Already submitted in preConfirm
            }
        });
    }
    
    // Prevent default form submission
    document.getElementById('sponsorForm').addEventListener('submit', function(e) {
        // We handle submission manually via validateAndSubmit()
        e.preventDefault();
    });
    
    // Reset form functionality
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
        // Also reset new logo preview if exists
        removeNewLogo();
        
        // Reset form to original values
        setTimeout(() => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Form reset to original values',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true,
            });
        }, 100);
    });
    
    // Auto-focus on first field
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('sponsor_name').focus();
    });
</script>

@endsection