@extends('admin.layouts.app')
@section('title', 'Edit News - Administrator')

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
                <i class="fas fa-edit text-primary me-2"></i> Edit News
            </h1>
            <p class="page-subtitle">Update news article information</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="{{ route('admin.news.index') }}" 
               class="btn btn-outline-primary d-flex align-items-center">
                <i class="fas fa-newspaper me-2"></i> News List
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
            <form method="POST" action="{{ route('admin.news.update', $news->id) }}" enctype="multipart/form-data" id="newsForm">
                @csrf
                @method('PUT')

                <!-- Hidden field for action type -->
                <input type="hidden" name="status" id="formStatus" value="{{ $news->status }}">

                <div class="row g-4">
                    {{-- Left Column --}}
                    <div class="col-lg-8">
                        {{-- Title --}}
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                <i class="fas fa-heading text-primary me-2"></i> News Title
                                <span class="text-danger">*</span>
                            </label>
                            <input id="title" 
                                   name="title" 
                                   type="text"
                                   value="{{ old('title', $news->title) }}"
                                   class="form-control"
                                   placeholder="Enter a compelling news title..."
                                   required
                                   autofocus>
                            @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Content --}}
                        <div class="mb-4">
                            <label for="content" class="form-label fw-semibold">
                                <i class="fas fa-align-left text-primary me-2"></i> Content
                                <span class="text-danger">*</span>
                            </label>
                            <div class="position-relative">
                                <textarea id="content" 
                                          name="content" 
                                          rows="12"
                                          class="form-control"
                                          placeholder="Write your news content here..."
                                          required>{{ old('content', $news->content) }}</textarea>
                                <div class="form-text mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Supports basic HTML formatting
                                    </small>
                                </div>
                            </div>
                            @error('content')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="col-lg-4">
                        {{-- Series --}}
                        <div class="mb-4">
                            <label for="series" class="form-label fw-semibold">
                                <i class="fas fa-tag text-primary me-2"></i> Series
                                <span class="text-danger">*</span>
                            </label>
                            <select id="series" 
                                    name="series" 
                                    required 
                                    class="form-select">
                                <option value="">-- Select Series --</option>
                                @php
                                    $seriesOptions = [
                                        'Bengkalis Series',
                                        'Indragiri Hilir Series',
                                        'Indragiri Hulu Series',
                                        'Kampar Series',
                                        'Kepulauan Meranti Series',
                                        'Kuantan Singingi Series',
                                        'Pelalawan Series',
                                        'Rokan Hilir Series',
                                        'Rokan Hulu Series',
                                        'Siak Series',
                                        'Dumai Series',
                                        'Pekanbaru Series'
                                    ];
                                @endphp
                                @foreach($seriesOptions as $series)
                                <option value="{{ $series }}" 
                                        {{ old('series', $news->series) == $series ? 'selected' : '' }}>
                                    {{ $series }}
                                </option>
                                @endforeach
                            </select>
                            @error('series')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-flag text-primary me-2"></i> Current Status
                            </label>
                            <div class="alert alert-{{ $news->status === 'view' ? 'success' : 'warning' }} mb-0">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-{{ $news->status === 'view' ? 'check-circle' : 'edit' }} me-2"></i>
                                    <div>
                                        <strong class="d-block">
                                            {{ ucfirst($news->status) }}
                                        </strong>
                                        <small class="d-block">
                                            @if($news->status === 'view')
                                                Published on {{ $news->created_at->format('M d, Y') }}
                                            @else
                                                Last updated: {{ $news->updated_at->format('M d, Y H:i') }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Posted By --}}
                        <div class="mb-4">
                            <label for="posted_by" class="form-label fw-semibold">
                                <i class="fas fa-user text-primary me-2"></i> Posted By
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-pen"></i>
                                </span>
                                <input id="posted_by" 
                                       name="posted_by" 
                                       type="text"
                                       value="{{ old('posted_by', $news->posted_by) }}"
                                       class="form-control"
                                       placeholder="Author name"
                                       required>
                            </div>
                            @error('posted_by')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Image Upload --}}
                        <div class="mb-4">
                            <label for="image" class="form-label fw-semibold">
                                <i class="fas fa-image text-primary me-2"></i> Featured Image
                            </label>
                            <div class="card border-dashed">
                                <div class="card-body text-center p-4">
                                    {{-- Current Image --}}
                                    @if($news->image)
                                    <div class="mb-3">
                                        <img src="{{ asset($news->image) }}" 
                                             alt="Current Image" 
                                             class="img-fluid rounded mb-3"
                                             style="max-height: 150px; object-fit: cover;">
                                        <p class="small text-muted mb-2">Current Image</p>
                                    </div>
                                    @endif

                                    {{-- Image Preview for New Image --}}
                                    <div id="imagePreview" class="mb-3 d-none">
                                        <img src="" 
                                             alt="Preview" 
                                             class="img-fluid rounded mb-3"
                                             style="max-height: 150px; object-fit: cover;">
                                        <button type="button" 
                                                class="btn btn-sm btn-danger"
                                                onclick="removeImage()">
                                            <i class="fas fa-trash me-1"></i> Remove New Image
                                        </button>
                                    </div>

                                    {{-- Upload Area --}}
                                    <div id="uploadArea" class="{{ old('image') ? 'd-none' : '' }}">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-3"></i>
                                        <p class="small text-muted mb-3">Click to upload new image (optional)</p>
                                        <input id="image" 
                                               name="image" 
                                               type="file" 
                                               accept=".jpg,.jpeg,.png"
                                               class="form-control d-none"
                                               onchange="previewImage(event)">
                                        <label for="image" 
                                               class="btn btn-outline-primary btn-sm cursor-pointer">
                                            <i class="fas fa-upload me-2"></i> Change Image
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Max 1MB, JPG/JPEG/PNG only. Leave empty to keep current image.
                                </small>
                            </div>
                            @error('image')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
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
                            <a href="{{ route('admin.news.index') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                            <button type="button" 
                                    onclick="saveAsDraft()"
                                    class="btn btn-outline-warning">
                                <i class="fas fa-save me-2"></i> Update as Draft
                            </button>
                            <button type="button" 
                                    onclick="publishNow()"
                                    class="btn btn-primary">
                                <i class="fas fa-upload me-2"></i> Update & Publish
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
                <i class="fas fa-lightbulb text-warning me-2"></i> Editing Tips
            </h6>
            <ul class="list-unstyled mb-0">
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>Review all changes before saving or publishing</small>
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>Use "Update as Draft" to save changes without publishing</small>
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>"Update & Publish" will make changes immediately visible</small>
                </li>
                <li>
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>Check the image preview if changing the featured image</small>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
    /* Reuse styles from news_create.blade.php */
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

    textarea.form-control {
        min-height: 300px;
        resize: vertical;
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
    }
</style>

<script>
    // Image Preview Functionality
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('imagePreview');
        const uploadArea = document.getElementById('uploadArea');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.querySelector('img').src = e.target.result;
                preview.classList.remove('d-none');
                uploadArea.classList.add('d-none');
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    function removeImage() {
        const preview = document.getElementById('imagePreview');
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('image');
        
        preview.classList.add('d-none');
        uploadArea.classList.remove('d-none');
        fileInput.value = '';
    }
    
    // Save as Draft Function
    function saveAsDraft() {
        console.log('Save as Draft clicked'); // Debug log
        
        // Validasi form terlebih dahulu
        const title = document.getElementById('title').value.trim();
        const content = document.getElementById('content').value.trim();
        const series = document.getElementById('series').value;
        const postedBy = document.getElementById('posted_by').value.trim();
        
        if (!title || !content || !series || !postedBy) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill in all required fields marked with *',
                confirmButtonColor: '#3498db'
            });
            return false;
        }
        
        // Check title length
        if (title.length > 200) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Title should not exceed 200 characters',
                confirmButtonColor: '#3498db'
            });
            return false;
        }
        
        // Check image size if uploaded
        const fileInput = document.getElementById('image');
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const maxSize = 1 * 1024 * 1024; // 1MB
            
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'Image Too Large',
                    text: 'Image size should not exceed 1MB',
                    confirmButtonColor: '#3498db'
                });
                return false;
            }
        }
        
        // Set status to draft
        document.getElementById('formStatus').value = 'draft';
        
        // Show confirmation with SweetAlert
        Swal.fire({
            title: 'Update as Draft?',
            html: 'Your changes will be saved as draft. The news will remain unpublished.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, update as draft',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                document.getElementById('newsForm').submit();
            }
        });
        
        return false;
    }
    
    // Publish Now Function
    function publishNow() {
        console.log('Publish Now clicked'); // Debug log
        
        // Validasi form terlebih dahulu
        const title = document.getElementById('title').value.trim();
        const content = document.getElementById('content').value.trim();
        const series = document.getElementById('series').value;
        const postedBy = document.getElementById('posted_by').value.trim();
        
        if (!title || !content || !series || !postedBy) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill in all required fields marked with *',
                confirmButtonColor: '#3498db'
            });
            return false;
        }
        
        // Check title length
        if (title.length > 200) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Title should not exceed 200 characters',
                confirmButtonColor: '#3498db'
            });
            return false;
        }
        
        // Check image size if uploaded
        const fileInput = document.getElementById('image');
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const maxSize = 1 * 1024 * 1024; // 1MB
            
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'Image Too Large',
                    text: 'Image size should not exceed 1MB',
                    confirmButtonColor: '#3498db'
                });
                return false;
            }
        }
        
        // Set status to published
        document.getElementById('formStatus').value = 'view';
        
        // Show confirmation with SweetAlert
        Swal.fire({
            title: 'Update & Publish?',
            html: 'Your changes will be published immediately and visible to readers.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3498db',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, update & publish',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                document.getElementById('newsForm').submit();
            }
        });
        
        return false;
    }
    
    // Remove old form validation (karena kita sudah pindah ke fungsi terpisah)
    document.getElementById('newsForm').addEventListener('submit', function(e) {
        // Mencegah submit otomatis karena kita sudah handle manual
        e.preventDefault();
    });
    
    // Debug: Cek apakah SweetAlert2 terload
    console.log('SweetAlert2 loaded:', typeof Swal !== 'undefined');
</script>

@endsection