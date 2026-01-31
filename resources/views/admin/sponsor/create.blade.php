@extends('admin.layouts.app')
@section('title', 'Add Sponsor - Administrator')

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
                <i class="fas fa-handshake text-primary me-2"></i> Add New Sponsor
            </h1>
            <p class="page-subtitle">Add new sponsor to the system</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="{{ route('admin.sponsor.sponsor') }}" 
               class="btn btn-outline-primary d-flex align-items-center">
                <i class="fas fa-list me-2"></i> Sponsor List
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
            <form method="POST" action="{{ route('admin.sponsor.store') }}" enctype="multipart/form-data" id="sponsorForm">
                @csrf

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
                                   value="{{ old('sponsor_name') }}"
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
                                       value="{{ old('sponsors_web') }}"
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

                        {{-- Quick Notes --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-lightbulb text-warning me-2"></i> Sponsor Guidelines
                            </label>
                            <div class="alert alert-info bg-opacity-10 border-info">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-info-circle mt-1 me-3 text-info"></i>
                                    <div>
                                        <strong class="d-block mb-1">Tips for adding sponsors:</strong>
                                        <ul class="mb-0 ps-3">
                                            <li class="small mb-1">Use high-quality logos with transparent background</li>
                                            <li class="small mb-1">Categorize sponsors appropriately</li>
                                            <li class="small mb-1">Double-check website URLs for accuracy</li>
                                            <li class="small">Ensure logo meets size requirements (max 2MB)</li>
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
                                        {{ old('category') == $category ? 'selected' : '' }}>
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
                                <span class="text-danger">*</span>
                            </label>
                            <div class="card border-dashed">
                                <div class="card-body text-center p-4">
                                    {{-- Logo Preview --}}
                                    <div id="logoPreview" class="mb-3 d-none">
                                        <img src="" 
                                             alt="Logo Preview" 
                                             class="img-fluid rounded mb-3"
                                             style="max-height: 150px; object-fit: contain;">
                                        <button type="button" 
                                                class="btn btn-sm btn-danger"
                                                onclick="removeLogo()">
                                            <i class="fas fa-trash me-1"></i> Remove
                                        </button>
                                    </div>

                                    {{-- Upload Area --}}
                                    <div id="uploadArea">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-3"></i>
                                        <p class="small text-muted mb-3">Click to upload sponsor logo</p>
                                        <input id="logo" 
                                               name="logo" 
                                               type="file" 
                                               accept=".jpg,.jpeg,.png,.svg,.webp,.gif"
                                               class="form-control d-none"
                                               onchange="previewLogo(event)"
                                               required>
                                        <label for="logo" 
                                               class="btn btn-outline-primary btn-sm cursor-pointer">
                                            <i class="fas fa-upload me-2"></i> Choose Logo
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Max 2MB, JPG/PNG/SVG/WebP/GIF. Transparent background preferred.
                                </small>
                            </div>
                            @error('logo')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Category Descriptions --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-info-circle text-info me-2"></i> Category Descriptions
                            </label>
                            <div class="accordion" id="categoryAccordion">
                                <div class="accordion-item border-0">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button collapsed bg-light py-2" 
                                                type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapseOne" 
                                                aria-expanded="false" 
                                                aria-controls="collapseOne">
                                            <small>View category details</small>
                                        </button>
                                    </h2>
                                    <div id="collapseOne" 
                                         class="accordion-collapse collapse" 
                                         aria-labelledby="headingOne" 
                                         data-bs-parent="#categoryAccordion">
                                        <div class="accordion-body p-3 small">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-2">
                                                    <strong class="text-primary">Presented By:</strong>
                                                    <small class="text-muted d-block">Main event sponsor</small>
                                                </li>
                                                <li class="mb-2">
                                                    <strong class="text-success">Official Partners:</strong>
                                                    <small class="text-muted d-block">Key partners supporting the event</small>
                                                </li>
                                                <li class="mb-2">
                                                    <strong class="text-info">Official Suppliers:</strong>
                                                    <small class="text-muted d-block">Suppliers providing goods/services</small>
                                                </li>
                                                <li class="mb-2">
                                                    <strong class="text-warning">Supporting Partners:</strong>
                                                    <small class="text-muted d-block">Additional supporting organizations</small>
                                                </li>
                                                <li>
                                                    <strong class="text-secondary">Managed By:</strong>
                                                    <small class="text-muted d-block">Event management/organization</small>
                                                </li>
                                            </ul>
                                        </div>
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
                                <i class="fas fa-redo me-2"></i> Reset Form
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
                                <i class="fas fa-save me-2"></i> Save Sponsor
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Help Card --}}
    <div class="card mt-4">
        <div class="card-body">
            <h6 class="card-title mb-3">
                <i class="fas fa-lightbulb text-warning me-2"></i> Adding Sponsor Tips
            </h6>
            <ul class="list-unstyled mb-0">
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>Ensure sponsor name is accurate and complete</small>
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>Upload high-quality logo with transparent background</small>
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>Double-check website URL for accuracy</small>
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>Select the appropriate category for sponsor level</small>
                </li>
                <li>
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>Review all information before saving</small>
                </li>
            </ul>
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

    .card.border-dashed {
        border: 2px dashed #dee2e6;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
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
    // Logo Preview Functionality
    function previewLogo(event) {
        const input = event.target;
        const preview = document.getElementById('logoPreview');
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
                uploadArea.style.display = 'none';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeLogo() {
        const preview = document.getElementById('logoPreview');
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('logo');
        
        preview.classList.add('d-none');
        uploadArea.style.display = 'block';
        fileInput.value = '';
    }
    
    // Form Validation and Submission
    function validateAndSubmit() {
        console.log('Save Sponsor clicked'); // Debug log
        
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
        
        if (!logoInput.files || logoInput.files.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please upload a logo',
                confirmButtonColor: '#3498db'
            });
            return false;
        }
        
        // Validate logo file
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
        
        // Show confirmation with SweetAlert
        Swal.fire({
            title: 'Add Sponsor?',
            html: 'Are you sure you want to add this sponsor?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3498db',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, add sponsor',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading indicator
                Swal.fire({
                    title: 'Processing...',
                    text: 'Please wait while we save the sponsor',
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
    
    // Prevent default form submission
    document.getElementById('sponsorForm').addEventListener('submit', function(e) {
        // We handle submission manually via validateAndSubmit()
        e.preventDefault();
    });
    
    // Reset form functionality
    document.querySelector('button[type="reset"]').addEventListener('click', function() {
        // Also reset logo preview
        removeLogo();
    });
    
    // Auto-focus on first field
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('sponsor_name').focus();
    });
</script>

@endsection