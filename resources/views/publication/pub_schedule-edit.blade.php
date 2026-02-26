@extends('admin.layouts.app')
@section('title', 'Edit Schedule - Administrator')

@section('content')

@php $activeTab = 'schedule'; @endphp
@include('partials.tabs-pub', compact('activeTab'))

{{-- Include SweetAlert2 --}}
@include('partials.sweetalert')

<div class="container mt-4">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="page-title">
                <i class="fas fa-calendar-edit text-primary me-2"></i> Edit Schedule
            </h1>
            <p class="page-subtitle">Update schedule information</p>
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
            <form action="{{ route('admin.pub_schedule.update', $schedule->id) }}" method="POST" enctype="multipart/form-data" id="scheduleForm">
                @csrf
                @method('PUT')
                
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
                                   value="{{ old('upload_date', \Carbon\Carbon::parse($schedule->upload_date)->format('Y-m-d')) }}"
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
                                   value="{{ old('main_title', $schedule->main_title) }}"
                                   placeholder="e.g., SBL Week 1 Schedule"
                                   required>
                            @error('main_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Enter a descriptive title for the schedule</small>
                        </div>

                        <!-- Caption -->
                        <div class="mb-3">
                            <label for="caption" class="form-label">
                                <i class="fas fa-comment me-1"></i> Caption (Optional)
                            </label>
                            <textarea class="form-control @error('caption') is-invalid @enderror" 
                                      id="caption" 
                                      name="caption" 
                                      rows="3"
                                      placeholder="Short description or caption for the schedule...">{{ old('caption', $schedule->caption) }}</textarea>
                            @error('caption')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Short description for the schedule image</small>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
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
                                    <option value="{{ $regency }}" @selected(old('series_name', $schedule->series_name) == $regency)>
                                        {{ $regency }}
                                    </option>
                                @endforeach
                            </select>
                            @error('series_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Select the series location</small>
                        </div>

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
                                Upload schedule image (JPG, PNG, GIF) - Max 2MB. Leave empty to keep current image.
                            </small>
                            
                            <!-- Current Image Preview -->
                            @if($schedule->layout_image)
                                <div class="mt-2">
                                    <p class="small text-muted mb-1">Current Image:</p>
                                    <img src="{{ asset($schedule->layout_image) }}" 
                                         alt="{{ $schedule->main_title }}"
                                         class="img-thumbnail mt-2"
                                         style="max-width: 200px; max-height: 150px;"
                                         onerror="this.onerror=null; this.src='{{ asset('img/no-image.png') }}';">
                                </div>
                            @endif
                            
                            <!-- New Image Preview -->
                            <div class="mt-2" id="imagePreview" style="display: none;">
                                <p class="small text-muted mb-1">New Image Preview:</p>
                                <img id="previewImage" 
                                     src="#" 
                                     alt="Preview" 
                                     class="img-thumbnail mt-2"
                                     style="max-width: 200px; max-height: 150px;">
                            </div>
                        </div>

                        <!-- Status Display (Read-only) -->
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-info-circle me-1"></i> Current Status
                            </label>
                            <div class="alert 
                                @if($schedule->status === 'draft') alert-warning
                                @elseif($schedule->status === 'publish') alert-success
                                @elseif($schedule->status === 'done') alert-primary
                                @else alert-secondary @endif">
                                <div class="d-flex align-items-center">
                                    @if($schedule->status === 'draft')
                                        <i class="fas fa-edit me-2"></i>
                                        <strong>Draft</strong>
                                        <span class="ms-2 small">- Only visible to admins</span>
                                    @elseif($schedule->status === 'publish')
                                        <i class="fas fa-globe me-2"></i>
                                        <strong>Published</strong>
                                        <span class="ms-2 small">- Visible to public</span>
                                    @elseif($schedule->status === 'done')
                                        <i class="fas fa-check-double me-2"></i>
                                        <strong>Done</strong>
                                        <span class="ms-2 small">- Cannot be edited</span>
                                    @endif
                                </div>
                                @if($schedule->status === 'done')
                                    <div class="small mt-1">
                                        <i class="fas fa-exclamation-triangle me-1"></i>
                                        This schedule is marked as "Done" and cannot be edited.
                                    </div>
                                @endif
                            </div>
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
                                @if($schedule->status !== 'done')
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i> Update Schedule
                                    </button>
                                @else
                                    <button type="button" class="btn btn-secondary" disabled>
                                        <i class="fas fa-ban me-2"></i> Cannot Edit (Done Status)
                                    </button>
                                @endif
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
        border: 1px solid transparent;
    }

    .alert-warning {
        background-color: #fff3cd;
        border-color: #ffecb5;
        color: #856404;
    }

    .alert-success {
        background-color: #d4edda;
        border-color: #c3e6cb;
        color: #155724;
    }

    .alert-primary {
        background-color: #d1ecf1;
        border-color: #bee5eb;
        color: #0c5460;
    }

    .btn {
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
        border-radius: 6px;
    }

    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
    }

    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }

    .btn-secondary {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .img-thumbnail {
        padding: 4px;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }

    .form-check-input:checked {
        background-color: #3498db;
        border-color: #3498db;
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Image preview for new image
        const layoutImageInput = document.getElementById('layout_image');
        const imagePreview = document.getElementById('imagePreview');
        const previewImage = document.getElementById('previewImage');
        
        if (layoutImageInput) {
            layoutImageInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
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
        
        // Remove invalid class on input
        document.querySelectorAll('.form-control, .form-select').forEach(element => {
            element.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
            
            element.addEventListener('change', function() {
                this.classList.remove('is-invalid');
            });
        });
        
        // Form validation and status check before submit
        const form = document.getElementById('scheduleForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                @if($schedule->status === 'done')
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Cannot Edit',
                        text: 'This schedule is marked as "Done" and cannot be edited.',
                        confirmButtonColor: '#3498db'
                    });
                    return false;
                @endif
                
                const title = document.getElementById('main_title');
                const series = document.getElementById('series_name');
                const uploadDate = document.getElementById('upload_date');
                
                let isValid = true;
                
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
                
                // Validate upload date
                if (!uploadDate.value) {
                    uploadDate.classList.add('is-invalid');
                    isValid = false;
                }
                
                if (!isValid) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Error',
                        text: 'Please fill all required fields',
                        timer: 3000,
                        showConfirmButton: false
                    });
                }
            });
        }
        
        // Disable form inputs if status is 'done'
        @if($schedule->status === 'done')
            document.querySelectorAll('#scheduleForm input, #scheduleForm select, #scheduleForm textarea, #scheduleForm button[type="submit"]').forEach(element => {
                if (element.id !== 'remove_image') {
                    element.disabled = true;
                }
            });
            
            // Also disable the remove image checkbox
            const removeImageCheckbox = document.getElementById('remove_image');
            if (removeImageCheckbox) {
                removeImageCheckbox.disabled = true;
            }
        @endif
    });
</script>

@endsection