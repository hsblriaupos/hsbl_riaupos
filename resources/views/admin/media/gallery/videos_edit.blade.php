@extends('admin.layouts.app')
@section('title', 'Edit Video - Administrator')

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
                <i class="fas fa-edit text-primary me-2"></i> Edit Video
            </h1>
            <p class="page-subtitle">Update video information and settings</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="{{ route('admin.videos.index') }}" 
               class="btn btn-outline-primary d-flex align-items-center">
                <i class="fas fa-list me-2"></i> Videos List
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
            <form method="POST" action="{{ route('admin.videos.update', $video->id) }}" enctype="multipart/form-data" id="videoForm">
                @csrf
                @method('PUT')

                <!-- Hidden field untuk video code -->
                <input type="hidden" name="video_code" id="video_code" value="{{ $video->video_code }}">

                <div class="row g-4">
                    {{-- Left Column --}}
                    <div class="col-lg-8">
                        {{-- Title --}}
                        <div class="mb-4">
                            <label for="title" class="form-label fw-semibold">
                                <i class="fas fa-heading text-primary me-2"></i> Video Title
                                <span class="text-danger">*</span>
                            </label>
                            <input id="title" 
                                   name="title" 
                                   type="text"
                                   value="{{ old('title', $video->title) }}"
                                   class="form-control"
                                   placeholder="Enter video title..."
                                   required
                                   autofocus>
                            @error('title')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- YouTube Link --}}
                        <div class="mb-4">
                            <label for="youtube_link" class="form-label fw-semibold">
                                <i class="fab fa-youtube text-danger me-2"></i> YouTube Link
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-link"></i>
                                </span>
                                <input id="youtube_link" 
                                       name="youtube_link" 
                                       type="url"
                                       value="{{ old('youtube_link', $video->youtube_link) }}"
                                       class="form-control"
                                       placeholder="https://www.youtube.com/watch?v=..."
                                       required>
                            </div>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Supported formats: youtube.com/watch?v=..., youtu.be/..., youtube.com/embed/...
                                </small>
                            </div>
                            @error('youtube_link')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                <i class="fas fa-align-left text-primary me-2"></i> Description
                            </label>
                            <div class="position-relative">
                                <textarea id="description" 
                                          name="description" 
                                          rows="8"
                                          class="form-control"
                                          placeholder="Describe the video content...">{{ old('description', $video->description) }}</textarea>
                            </div>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Optional: Add video description or summary
                                </small>
                            </div>
                            @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="col-lg-4">
                        {{-- Video Code Display (Read Only) --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-hashtag text-primary me-2"></i> Video Code
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-code"></i>
                                </span>
                                <input type="text" 
                                       class="form-control bg-light"
                                       id="video_code_display"
                                       value="{{ $video->video_code }}"
                                       readonly
                                       style="cursor: not-allowed;">
                                <button type="button" 
                                        class="btn btn-outline-secondary"
                                        onclick="copyVideoCode()"
                                        data-bs-toggle="tooltip" 
                                        data-bs-title="Copy to clipboard">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Auto-generated unique identifier (cannot be changed)
                                </small>
                            </div>
                        </div>

                        {{-- Video Type --}}
                        <div class="mb-4">
                            <label for="type" class="form-label fw-semibold">
                                <i class="fas fa-film text-primary me-2"></i> Video Type
                                <span class="text-danger">*</span>
                            </label>
                            <select id="type" 
                                    name="type" 
                                    required 
                                    class="form-select">
                                <option value="">-- Select Type --</option>
                                <option value="video" {{ old('type', $video->type) == 'video' ? 'selected' : '' }}>Video</option>
                                <option value="live" {{ old('type', $video->type) == 'live' ? 'selected' : '' }}>Live Stream</option>
                            </select>
                            @error('type')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Slug --}}
                        <div class="mb-4">
                            <label for="slug" class="form-label fw-semibold">
                                <i class="fas fa-link text-primary me-2"></i> URL Slug
                                <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-at"></i>
                                </span>
                                <input id="slug" 
                                       name="slug" 
                                       type="text"
                                       value="{{ old('slug', $video->slug) }}"
                                       class="form-control"
                                       placeholder="video-title-url"
                                       required>
                            </div>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    URL-friendly identifier for the video
                                </small>
                            </div>
                            @error('slug')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Thumbnail Upload --}}
                        <div class="mb-4">
                            <label for="thumbnail" class="form-label fw-semibold">
                                <i class="fas fa-image text-primary me-2"></i> Custom Thumbnail
                            </label>
                            <div class="card border-dashed">
                                <div class="card-body text-center p-4">
                                    {{-- Current Thumbnail Preview --}}
                                    <div id="currentThumbnail" class="mb-3">
                                        @if($video->thumbnail)
                                            <img src="{{ asset($video->thumbnail) }}" 
                                                 alt="Current Thumbnail" 
                                                 class="img-fluid rounded mb-3"
                                                 style="max-height: 150px; object-fit: cover;">
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger"
                                                        onclick="removeThumbnail()">
                                                    <i class="fas fa-trash me-1"></i> Remove
                                                </button>
                                            </div>
                                            <input type="hidden" name="remove_thumbnail" id="remove_thumbnail" value="0">
                                        @else
                                            {{-- YouTube Thumbnail Preview --}}
                                            <div id="youtubeThumbnailPreview" class="mb-3">
                                                <div class="alert alert-info bg-info bg-opacity-10 border-info border-opacity-25">
                                                    <i class="fas fa-info-circle me-2"></i>
                                                    <small>Using YouTube thumbnail</small>
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Upload Area --}}
                                    <div id="uploadArea" class="{{ $video->thumbnail ? 'd-none' : '' }}">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-3"></i>
                                        <p class="small text-muted mb-3">Click to upload custom thumbnail</p>
                                        <input id="thumbnail" 
                                               name="thumbnail" 
                                               type="file" 
                                               accept=".jpg,.jpeg,.png,.gif,.webp"
                                               class="form-control d-none"
                                               onchange="previewThumbnail(event)">
                                        <label for="thumbnail" 
                                               class="btn btn-outline-primary btn-sm cursor-pointer">
                                            <i class="fas fa-upload me-2"></i> Choose Thumbnail
                                        </label>
                                    </div>
                                    
                                    {{-- New Thumbnail Preview --}}
                                    <div id="thumbnailPreview" class="mb-3 d-none">
                                        <img src="" 
                                             alt="New Thumbnail Preview" 
                                             class="img-fluid rounded mb-3"
                                             style="max-height: 150px; object-fit: cover;">
                                        <button type="button" 
                                                class="btn btn-sm btn-danger"
                                                onclick="cancelThumbnailUpload()">
                                            <i class="fas fa-times me-1"></i> Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Max 1MB, JPG/PNG/GIF/WebP. Leave empty to use YouTube thumbnail
                                </small>
                            </div>
                            @error('thumbnail')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="mb-4">
                            <label for="status" class="form-label fw-semibold">
                                <i class="fas fa-flag text-primary me-2"></i> Status
                                <span class="text-danger">*</span>
                            </label>
                            <select id="status" 
                                    name="status" 
                                    required 
                                    class="form-select">
                                <option value="draft" {{ old('status', $video->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="view" {{ old('status', $video->status) == 'view' ? 'selected' : '' }}>View</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- YouTube Link Preview --}}
                        <div class="mb-4" id="youtubePreviewContainer">
                            <label class="form-label fw-semibold">
                                <i class="fab fa-youtube text-danger me-2"></i> YouTube Preview
                            </label>
                            <div class="card">
                                <div class="card-body text-center p-3">
                                    <i class="fab fa-youtube fa-2x text-danger mb-2"></i>
                                    <p class="small text-muted mb-0">
                                        Preview will appear when you enter a valid YouTube URL
                                    </p>
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
                                    class="btn btn-outline-secondary" 
                                    onclick="resetForm()">
                                <i class="fas fa-redo me-2"></i> Reset Form
                            </button>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.videos.index') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                            <button type="submit" 
                                    class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update Video
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
                <i class="fas fa-lightbulb text-warning me-2"></i> Video Update Tips
            </h6>
            <ul class="list-unstyled mb-0">
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>Video code cannot be changed as it's a unique identifier</small>
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>Update the slug if you change the title to maintain SEO-friendly URLs</small>
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>Upload a new custom thumbnail (max 1MB) or remove to use YouTube thumbnail</small>
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>Set to "Draft" to work on it privately, "View" to make it public</small>
                </li>
                <li>
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>Always test the YouTube link after updating to ensure it works correctly</small>
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

    .card-body {
        padding: 16px;
    }

    .card.border-dashed {
        border: 2px dashed #dee2e6;
        background-color: #f8f9fa;
        transition: all 0.3s ease;
    }

    .card.border-dashed:hover {
        border-color: #6c757d;
        background-color: #e9ecef;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    textarea.form-control {
        min-height: 200px;
        resize: vertical;
    }

    .btn-outline-warning:hover {
        background-color: #ffc107;
        color: #000;
    }

    .border-top {
        border-top: 1px solid #dee2e6 !important;
    }

    /* Action buttons styling */
    .btn-outline-primary {
        border-color: #3498db;
        color: #3498db;
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .btn-outline-primary:hover {
        background-color: #3498db;
        color: white;
    }

    .btn-outline-secondary {
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
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
        
        .btn-outline-primary, .btn-outline-secondary {
            flex: 1;
            justify-content: center;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }
        
        .d-flex.gap-2 {
            flex-wrap: wrap;
        }
        
        .page-title {
            font-size: 1.1rem;
        }
    }
    
    @media (max-width: 576px) {
        .page-title {
            font-size: 1rem;
        }
        
        .btn-outline-primary, .btn-outline-secondary, .btn-primary {
            font-size: 0.85rem;
            padding: 6px 12px;
        }
    }
</style>

<script>
    // Thumbnail Preview Functionality
    function previewThumbnail(event) {
        const input = event.target;
        const preview = document.getElementById('thumbnailPreview');
        const currentThumbnail = document.getElementById('currentThumbnail');
        const uploadArea = document.getElementById('uploadArea');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const maxSize = 1 * 1024 * 1024; // 1MB
            
            // Check file size
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'Thumbnail size should not exceed 1MB',
                    confirmButtonColor: '#3498db'
                });
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.querySelector('img').src = e.target.result;
                preview.classList.remove('d-none');
                currentThumbnail.classList.add('d-none');
                uploadArea.classList.add('d-none');
            }
            
            reader.readAsDataURL(file);
        }
    }
    
    function removeThumbnail() {
        Swal.fire({
            title: 'Remove Thumbnail?',
            text: 'Are you sure you want to remove the current thumbnail?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, remove it',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('remove_thumbnail').value = '1';
                document.getElementById('currentThumbnail').classList.add('d-none');
                document.getElementById('uploadArea').classList.remove('d-none');
                document.getElementById('thumbnailPreview').classList.add('d-none');
            }
        });
    }
    
    function cancelThumbnailUpload() {
        document.getElementById('thumbnail').value = '';
        document.getElementById('thumbnailPreview').classList.add('d-none');
        
        if (document.getElementById('remove_thumbnail').value === '1') {
            document.getElementById('uploadArea').classList.remove('d-none');
        } else {
            document.getElementById('currentThumbnail').classList.remove('d-none');
            document.getElementById('uploadArea').classList.add('d-none');
        }
    }
    
    // Auto-generate slug from title
    document.getElementById('title').addEventListener('input', function() {
        const slugInput = document.getElementById('slug');
        const currentSlug = '{{ $video->slug }}';
        
        // Only auto-generate if slug hasn't been manually changed from original
        if (slugInput.value === currentSlug || slugInput.value === '') {
            const title = this.value.trim();
            const slug = title
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-')
                .substring(0, 100);
            slugInput.value = slug;
        }
    });
    
    // Copy video code to clipboard
    function copyVideoCode() {
        const videoCode = document.getElementById('video_code_display').value;
        navigator.clipboard.writeText(videoCode).then(() => {
            // Show tooltip feedback
            const tooltipElement = document.querySelector('[data-bs-title="Copy to clipboard"]');
            const originalTitle = tooltipElement.getAttribute('data-bs-original-title') || tooltipElement.getAttribute('data-bs-title');
            
            // Create new tooltip with success message
            const tooltip = new bootstrap.Tooltip(tooltipElement, {
                title: 'Copied!',
                trigger: 'manual'
            });
            tooltip.show();
            
            // Restore original tooltip after 1.5 seconds
            setTimeout(() => {
                tooltip.hide();
                // Re-initialize with original title
                new bootstrap.Tooltip(tooltipElement, {
                    title: originalTitle,
                    trigger: 'hover focus'
                });
            }, 1500);
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }
    
    // Reset form
    function resetForm() {
        Swal.fire({
            title: 'Reset Form?',
            text: 'Are you sure you want to reset all changes?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#6c757d',
            cancelButtonColor: '#3498db',
            confirmButtonText: 'Yes, reset',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('videoForm').reset();
                
                // Reset thumbnail previews
                document.getElementById('thumbnail').value = '';
                document.getElementById('thumbnailPreview').classList.add('d-none');
                document.getElementById('currentThumbnail').classList.remove('d-none');
                document.getElementById('uploadArea').classList.add('d-none');
                document.getElementById('remove_thumbnail').value = '0';
                
                // Reset YouTube preview
                document.getElementById('youtubePreviewContainer').innerHTML = `
                    <label class="form-label fw-semibold">
                        <i class="fab fa-youtube text-danger me-2"></i> YouTube Preview
                    </label>
                    <div class="card">
                        <div class="card-body text-center p-3">
                            <i class="fab fa-youtube fa-2x text-danger mb-2"></i>
                            <p class="small text-muted mb-0">
                                Preview will appear when you enter a valid YouTube URL
                            </p>
                        </div>
                    </div>
                `;
                
                // Trigger input event for YouTube link
                document.getElementById('youtube_link').dispatchEvent(new Event('input'));
            }
        });
    }
    
    // Extract YouTube video ID and show preview
    document.getElementById('youtube_link').addEventListener('input', function() {
        const url = this.value.trim();
        const container = document.getElementById('youtubePreviewContainer');
        
        if (url) {
            const videoId = extractYouTubeId(url);
            if (videoId) {
                const youtubeThumbnail = `https://img.youtube.com/vi/${videoId}/hqdefault.jpg`;
                
                // Update preview container
                container.innerHTML = `
                    <label class="form-label fw-semibold">
                        <i class="fab fa-youtube text-danger me-2"></i> YouTube Preview
                    </label>
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="ratio ratio-16x9 mb-3">
                                <img src="${youtubeThumbnail}" 
                                     alt="YouTube Thumbnail" 
                                     class="img-fluid rounded"
                                     style="object-fit: cover;">
                            </div>
                            <div class="d-flex align-items-center justify-content-between">
                                <small class="text-muted">
                                    <i class="fas fa-check-circle text-success me-1"></i>
                                    YouTube link detected
                                </small>
                                <a href="${url}" 
                                   target="_blank" 
                                   class="btn btn-sm btn-outline-danger">
                                    <i class="fab fa-youtube me-1"></i> Test Link
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                container.innerHTML = `
                    <label class="form-label fw-semibold">
                        <i class="fab fa-youtube text-danger me-2"></i> YouTube Preview
                    </label>
                    <div class="card">
                        <div class="card-body text-center p-3">
                            <i class="fas fa-exclamation-triangle text-warning fa-2x mb-2"></i>
                            <p class="small text-muted mb-0">
                                Please enter a valid YouTube URL
                            </p>
                        </div>
                    </div>
                `;
            }
        } else {
            container.innerHTML = `
                <label class="form-label fw-semibold">
                    <i class="fab fa-youtube text-danger me-2"></i> YouTube Preview
                </label>
                <div class="card">
                    <div class="card-body text-center p-3">
                        <i class="fab fa-youtube fa-2x text-danger mb-2"></i>
                        <p class="small text-muted mb-0">
                            Preview will appear when you enter a valid YouTube URL
                        </p>
                    </div>
                </div>
            `;
        }
    });
    
    // Extract YouTube video ID from URL
    function extractYouTubeId(url) {
        const patterns = [
            /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/,
            /youtube\.com\/.*[?&]v=([^&]+)/,
        ];
        
        for (const pattern of patterns) {
            const match = url.match(pattern);
            if (match && match[1]) {
                return match[1];
            }
        }
        return null;
    }
    
    // Form validation and submission
    document.getElementById('videoForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate required fields
        const title = document.getElementById('title').value.trim();
        const youtubeLink = document.getElementById('youtube_link').value.trim();
        const type = document.getElementById('type').value;
        const slug = document.getElementById('slug').value.trim();
        const status = document.getElementById('status').value;
        
        if (!title || !youtubeLink || !type || !slug || !status) {
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                text: 'Please fill in all required fields marked with *',
                confirmButtonColor: '#3498db'
            });
            return false;
        }
        
        // Validate YouTube URL format
        const youtubeId = extractYouTubeId(youtubeLink);
        if (!youtubeId) {
            Swal.fire({
                icon: 'error',
                title: 'Invalid YouTube URL',
                text: 'Please enter a valid YouTube video URL',
                confirmButtonColor: '#3498db'
            });
            return false;
        }
        
        // Validate thumbnail size if uploaded
        const fileInput = document.getElementById('thumbnail');
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const maxSize = 1 * 1024 * 1024; // 1MB
            
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'Thumbnail Too Large',
                    text: 'Thumbnail size should not exceed 1MB',
                    confirmButtonColor: '#3498db'
                });
                return false;
            }
        }
        
        // Show confirmation
        Swal.fire({
            title: 'Update Video?',
            html: 'Are you sure you want to update this video?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3498db',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, update it',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit the form
                this.submit();
            }
        });
        
        return false;
    });

    // Initialize with YouTube preview check
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Check existing YouTube link
        const youtubeLink = document.getElementById('youtube_link').value;
        if (youtubeLink) {
            document.getElementById('youtube_link').dispatchEvent(new Event('input'));
        }
    });
</script>

@endsection