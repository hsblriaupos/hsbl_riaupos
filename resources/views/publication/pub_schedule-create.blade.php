@extends('admin.layouts.app')
@section('title', 'Add Schedule - Administrator')

@section('content')

@php $activeTab = 'schedule'; @endphp
@include('partials.tabs-pub', compact('activeTab'))

<div class="container mt-4">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="page-title">
                <i class="fas fa-calendar-plus text-primary me-2"></i> Add New Schedule
            </h1>
            <p class="page-subtitle">Create a new match schedule</p>
        </div>
        
        <!-- Back Button -->
        <div>
            <a href="{{ route('admin.pub_schedule.index') }}" 
               class="btn btn-outline-secondary d-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i> Back to Schedules
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.pub_schedule.store') }}" method="POST" enctype="multipart/form-data" id="scheduleForm">
                @csrf
                
                <!-- Hidden input for action type -->
                <input type="hidden" name="action_type" id="action_type" value="draft">
                
                @if($event)
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        This schedule is linked to event: <strong>{{ $event->name }}</strong>
                        <input type="hidden" name="event_id" value="{{ $event->id }}">
                    </div>
                @endif

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <!-- Upload Date -->
                        <div class="mb-3">
                            <label for="upload_date" class="form-label required">
                                <i class="fas fa-calendar-day me-1"></i> Upload Date
                            </label>
                            <input type="date" 
                                   class="form-control @error('upload_date') is-invalid @enderror" 
                                   id="upload_date" 
                                   name="upload_date" 
                                   value="{{ old('upload_date', date('Y-m-d')) }}"
                                   required>
                            @error('upload_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Date when this schedule is uploaded</small>
                        </div>

                        <!-- Main Title -->
                        <div class="mb-3">
                            <label for="main_title" class="form-label required">
                                <i class="fas fa-heading me-1"></i> Main Title
                            </label>
                            <input type="text" 
                                   class="form-control @error('main_title') is-invalid @enderror" 
                                   id="main_title" 
                                   name="main_title" 
                                   value="{{ old('main_title') }}"
                                   placeholder="e.g., SBL Week 1 Schedule"
                                   required>
                            @error('main_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Enter a descriptive title for the schedule</small>
                        </div>

                        <!-- Series Name -->
                        <div class="mb-3">
                            <label for="series_name" class="form-label required">
                                <i class="fas fa-map-marker-alt me-1"></i> Series
                            </label>
                            <select class="form-select @error('series_name') is-invalid @enderror" 
                                    id="series_name" 
                                    name="series_name"
                                    required>
                                <option value="">-- Select Series --</option>
                                @php
                                    $riauRegencies = [
                                        'Pekanbaru Series',
                                        'Dumai Series',
                                        'Siak Series',
                                        'Kampar Series',
                                        'Indragiri Hulu Series',
                                        'Indragiri Hilir Series',
                                        'Pelalawan Series',
                                        'Rokan Hulu Series',
                                        'Rokan Hilir Series',
                                        'Kuantan Singingi Series',
                                        'Bengkalis Series',
                                        'Meranti Islands Series',
                                    ];
                                @endphp
                                @foreach($riauRegencies as $regency)
                                    <option value="{{ $regency }}" @selected(old('series_name') == $regency)>
                                        {{ $regency }}
                                    </option>
                                @endforeach
                            </select>
                            @error('series_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Select the series location</small>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <!-- Layout Image -->
                        <div class="mb-3">
                            <label for="layout_image" class="form-label">
                                <i class="fas fa-image me-1"></i> Layout Image
                            </label>
                            <input type="file" 
                                   class="form-control @error('layout_image') is-invalid @enderror" 
                                   id="layout_image" 
                                   name="layout_image"
                                   accept="image/*">
                            @error('layout_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Upload schedule image (JPG, PNG, GIF) - Max 2MB
                            </small>
                            
                            <!-- Image Preview -->
                            <div class="mt-2" id="imagePreview" style="display: none;">
                                <img id="previewImage" 
                                     src="#" 
                                     alt="Preview" 
                                     class="img-thumbnail mt-2"
                                     style="max-width: 200px; max-height: 150px;">
                            </div>
                        </div>

                        <!-- Caption -->
                        <div class="mb-3">
                            <label for="caption" class="form-label">
                                <i class="fas fa-comment me-1"></i> Caption / Additional Information
                            </label>
                            <textarea class="form-control @error('caption') is-invalid @enderror" 
                                      id="caption" 
                                      name="caption" 
                                      rows="6"
                                      placeholder="You can include complete schedule information here, such as:
• Match dates and times
• Venue locations
• Participating teams
• Tournament phases
• Special rules or announcements
• Contact information for inquiries
...">{{ old('caption') }}</textarea>
                            @error('caption')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                <i class="fas fa-info-circle text-info me-1"></i>
                                Add complete schedule information here. This will help users understand the schedule details.
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.pub_schedule.index') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                            <div class="d-flex gap-2">
                                <button type="button" id="draftBtn" 
                                        class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i> Save as Draft
                                </button>
                                <button type="submit" id="publishBtn" 
                                        class="btn btn-success">
                                    <i class="fas fa-paper-plane me-2"></i> Save & Publish
                                </button>
                            </div>
                        </div>
                    </div>
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
    }

    .card-body {
        padding: 24px;
    }

    .form-label {
        font-weight: 500;
        font-size: 0.9rem;
        color: #495057;
        margin-bottom: 6px;
    }

    .form-label.required:after {
        content: " *";
        color: #dc3545;
    }

    .form-control, .form-select {
        font-size: 0.9rem;
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #ced4da;
    }

    .form-control:focus, .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .invalid-feedback {
        font-size: 0.8rem;
    }

    .alert {
        border-radius: 6px;
        font-size: 0.9rem;
    }

    .badge {
        padding: 0.3em 0.6em;
        font-weight: 500;
        font-size: 0.75em;
    }

    .btn {
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .btn:active {
        transform: translateY(0);
    }

    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }

    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }

    .img-thumbnail {
        padding: 4px;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }

    @media (max-width: 768px) {
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .card-body {
            padding: 16px;
        }
        
        .d-flex.flex-md-row {
            flex-direction: column !important;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column !important;
            gap: 10px;
        }
        
        .d-flex.gap-2 {
            flex-wrap: wrap;
            gap: 5px !important;
        }
        
        .btn {
            padding: 6px 12px;
            font-size: 0.85rem;
        }
    }
    
    @media (max-width: 576px) {
        .page-title {
            font-size: 1rem;
        }
        
        .form-label {
            font-size: 0.85rem;
        }
        
        .btn {
            padding: 5px 10px;
            font-size: 0.8rem;
        }
        
        .col-md-6 {
            margin-bottom: 15px;
        }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Initialize SweetAlert2
const Swal2 = Swal.mixin({
    customClass: {
        confirmButton: 'btn btn-success mx-2',
        cancelButton: 'btn btn-secondary mx-2'
    },
    buttonsStyling: false
});

document.addEventListener('DOMContentLoaded', function() {
    console.log('Schedule Create Page Loaded');
    
    // Image preview
    const layoutImageInput = document.getElementById('layout_image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImage = document.getElementById('previewImage');
    
    if (layoutImageInput) {
        layoutImageInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                // Check file size (max 2MB)
                const maxSize = 2 * 1024 * 1024;
                if (this.files[0].size > maxSize) {
                    showError('File Too Large', 'Image size should not exceed 2MB');
                    this.value = '';
                    return;
                }
                
                // Check file type
                const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
                if (!validTypes.includes(this.files[0].type)) {
                    showError('Invalid File Type', 'Only JPG, PNG, and GIF images are allowed');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                
                reader.readAsDataURL(this.files[0]);
            } else {
                imagePreview.style.display = 'none';
            }
        });
    }
    
    // Set today's date as default if not set
    const uploadDateInput = document.getElementById('upload_date');
    if (uploadDateInput && !uploadDateInput.value) {
        uploadDateInput.value = new Date().toISOString().split('T')[0];
    }
    
    // Remove invalid class on input
    document.querySelectorAll('.form-control, .form-select').forEach(element => {
        element.addEventListener('input', function() {
            this.classList.remove('is-invalid');
        });
        
        element.addEventListener('change', function() {
            this.classList.remove('is-invalid');
        });
    });
    
    // Button event listeners
    const draftBtn = document.getElementById('draftBtn');
    const publishBtn = document.getElementById('publishBtn');
    const scheduleForm = document.getElementById('scheduleForm');
    
    if (draftBtn) {
        draftBtn.addEventListener('click', function(e) {
            e.preventDefault();
            handleDraftSubmit();
        });
    }
    
    if (publishBtn) {
        publishBtn.addEventListener('click', function(e) {
            e.preventDefault();
            handlePublishSubmit();
        });
    }
});

// Function to validate form
function validateForm() {
    const title = document.getElementById('main_title');
    const series = document.getElementById('series_name');
    const date = document.getElementById('upload_date');
    
    let isValid = true;
    
    // Clear previous errors
    [title, series, date].forEach(field => {
        if (field) {
            field.classList.remove('is-invalid');
        }
    });
    
    // Validate title
    if (!title.value.trim()) {
        title.classList.add('is-invalid');
        isValid = false;
    }
    
    // Validate series
    if (!series.value) {
        series.classList.add('is-invalid');
        isValid = false;
    }
    
    // Validate date
    if (!date.value) {
        date.classList.add('is-invalid');
        isValid = false;
    }
    
    return isValid;
}

// Function to handle draft submission
async function handleDraftSubmit() {
    if (!validateForm()) {
        await showError('Validation Error', 'Please fill all required fields marked with *');
        return;
    }
    
    try {
        document.getElementById('action_type').value = 'draft';
        
        const result = await Swal2.fire({
            title: 'Save as Draft?',
            html: '<div class="text-start">' +
                  '<p>This schedule will be saved as draft and will not be visible to the public.</p>' +
                  '<p class="text-muted small">You can publish it later from the schedule list.</p>' +
                  '</div>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save me-1"></i> Save Draft',
            cancelButtonText: '<i class="fas fa-times me-1"></i> Cancel',
            reverseButtons: true,
            focusConfirm: false
        });
        
        if (result.isConfirmed) {
            // Submit form immediately - no loading delay
            document.getElementById('scheduleForm').submit();
        }
    } catch (error) {
        console.error('Error in draft submission:', error);
        showError('Error', 'An error occurred. Please try again.');
    }
}

// Function to handle publish submission
async function handlePublishSubmit() {
    if (!validateForm()) {
        await showError('Validation Error', 'Please fill all required fields marked with *');
        return;
    }
    
    try {
        document.getElementById('action_type').value = 'publish';
        
        const result = await Swal2.fire({
            title: 'Save & Publish?',
            html: '<div class="text-start">' +
                  '<p>This schedule will be immediately visible to the public.</p>' +
                  '<p class="text-warning small"><i class="fas fa-exclamation-triangle me-1"></i> This action cannot be undone.</p>' +
                  '</div>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-paper-plane me-1"></i> Publish Now',
            cancelButtonText: '<i class="fas fa-times me-1"></i> Cancel',
            reverseButtons: true,
            focusConfirm: false
        });
        
        if (result.isConfirmed) {
            // Submit form immediately - no loading delay
            document.getElementById('scheduleForm').submit();
        }
    } catch (error) {
        console.error('Error in publish submission:', error);
        showError('Error', 'An error occurred. Please try again.');
    }
}

// Utility function to show error
function showError(title, text) {
    return Swal2.fire({
        icon: 'error',
        title: title,
        text: text,
        confirmButtonText: 'OK'
    });
}

// Utility function to show success
function showSuccess(title, text) {
    return Swal2.fire({
        icon: 'success',
        title: title,
        text: text,
        timer: 3000,
        showConfirmButton: false
    });
}

// Show success message if redirected from controller with success
@if(session('success'))
    showSuccess('Success!', '{{ session('success') }}');
@endif

// Show error message if redirected from controller with error
@if(session('error'))
    showError('Error!', '{{ session('error') }}');
@endif

// Show validation errors
@if($errors->any())
    showError('Validation Error', '{{ $errors->first() }}');
@endif
</script>

@endsection