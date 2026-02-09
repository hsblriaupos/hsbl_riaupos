@extends('admin.layouts.app')
@section('title', 'Edit Photo Gallery - Administrator')

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
                <i class="fas fa-images text-primary me-2"></i> Edit Photo Gallery
            </h1>
            <p class="page-subtitle">Update photo gallery information</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="{{ route('admin.gallery.photos.index') }}" 
               class="btn btn-outline-primary d-flex align-items-center">
                <i class="fas fa-list me-2"></i> Photos List
            </a>
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                <i class="fas fa-trash me-2"></i> Delete
            </button>
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

    <!-- Current File Info -->
    <div class="card mb-4">
        <div class="card-body">
            <h6 class="card-title mb-3">
                <i class="fas fa-info-circle text-primary me-2"></i> Current Gallery Information
            </h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="d-flex flex-column">
                        <span class="text-muted small mb-1">Current File:</span>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-file-archive text-warning me-2"></i>
                            <span class="fw-semibold">{{ $gallery->original_filename }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex flex-column">
                        <span class="text-muted small mb-1">File Size:</span>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-hdd text-muted me-2"></i>
                            <span class="fw-semibold">{{ $gallery->formattedFileSize }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex flex-column">
                        <span class="text-muted small mb-1">Downloads:</span>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-download text-muted me-2"></i>
                            <span class="fw-semibold">{{ $gallery->download_count }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Container -->
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.gallery.photos.update', $gallery->id) }}" enctype="multipart/form-data" id="galleryForm">
                @csrf
                @method('PUT')

                <!-- Hidden field untuk auto-generated data -->
                <input type="hidden" name="file_type" id="fileType" value="{{ $gallery->file_type ?? 'zip' }}">

                <div class="row g-4">
                    {{-- Left Column --}}
                    <div class="col-lg-8">
                        {{-- School Name Dropdown --}}
                        <div class="mb-4">
                            <label for="school_name" class="form-label fw-semibold">
                                <i class="fas fa-school text-primary me-2"></i> School Name
                                <span class="text-danger">*</span>
                            </label>
                            <select id="school_name" 
                                   name="school_name" 
                                   required 
                                   class="form-select"
                                   onchange="updateManualInput()">
                                <option value="">-- Select School --</option>
                                @php
                                    $uniqueSchools = \App\Models\TeamList::select('school_name')
                                        ->whereNotNull('school_name')
                                        ->where('school_name', '!=', '')
                                        ->distinct()
                                        ->orderBy('school_name')
                                        ->pluck('school_name');
                                @endphp
                                @foreach($uniqueSchools as $school)
                                    <option value="{{ $school }}" {{ old('school_name', $gallery->school_name) == $school ? 'selected' : '' }}>{{ $school }}</option>
                                @endforeach
                                <option value="other" {{ old('school_name') == 'other' || (!in_array($gallery->school_name, $uniqueSchools->toArray()) && $gallery->school_name) ? 'selected' : '' }}>Other (Manual Input)</option>
                            </select>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Select school from registered teams or choose "Other" for custom input
                                </small>
                            </div>
                            @error('school_name')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Manual School Input --}}
                        <div id="manualSchoolInput" class="mb-4 {{ old('school_name') == 'other' || (!in_array($gallery->school_name, $uniqueSchools->toArray()) && $gallery->school_name) ? '' : 'd-none' }}">
                            <label for="manual_school_name" class="form-label fw-semibold">
                                <i class="fas fa-edit text-primary me-2"></i> Enter School Name
                                <span class="text-danger">*</span>
                            </label>
                            <input id="manual_school_name" 
                                   name="manual_school_name" 
                                   type="text"
                                   value="{{ old('manual_school_name', (!in_array($gallery->school_name, $uniqueSchools->toArray()) && $gallery->school_name) ? $gallery->school_name : '') }}"
                                   class="form-control"
                                   placeholder="Enter school name...">
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Enter school name manually if not in dropdown list
                                </small>
                            </div>
                        </div>

                        {{-- ZIP File Upload (Optional Update) --}}
                        <div class="mb-4">
                            <label for="file" class="form-label fw-semibold">
                                <i class="fas fa-file-archive text-warning me-2"></i> Update ZIP File (Optional)
                            </label>
                            
                            <div class="alert alert-info border-info bg-info bg-opacity-10 mb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <span>Leave empty to keep current file: <strong>{{ $gallery->original_filename }}</strong></span>
                                </div>
                            </div>
                            
                            <div class="card border-dashed" id="fileContainer">
                                <div class="card-body text-center p-4">
                                    {{-- File Info Preview --}}
                                    <div id="fileInfoPreview" class="mb-3 d-none">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <i class="fas fa-file-archive fa-3x text-warning"></i>
                                        </div>
                                        <div class="file-info bg-light p-3 rounded mb-3">
                                            <p class="mb-1" id="fileNameDisplay">-</p>
                                            <p class="mb-0 small text-muted" id="fileSizeDisplay">-</p>
                                        </div>
                                        <button type="button" 
                                                class="btn btn-sm btn-danger"
                                                onclick="removeFile()">
                                            <i class="fas fa-trash me-1"></i> Remove New File
                                        </button>
                                    </div>

                                    {{-- Upload Area --}}
                                    <div id="uploadArea">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-3"></i>
                                        <p class="small text-muted mb-3">Click to upload new ZIP file (max 5GB)</p>
                                        <input id="file" 
                                               name="file" 
                                               type="file" 
                                               accept=".zip,.rar,.7z"
                                               class="form-control d-none"
                                               onchange="previewFile(event)">
                                        <label for="file" 
                                               class="btn btn-outline-primary btn-sm cursor-pointer">
                                            <i class="fas fa-upload me-2"></i> Choose New ZIP File
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Maximum file size: 5GB. Supported formats: ZIP, RAR, 7Z.
                                </small>
                            </div>
                            @error('file')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="mb-4">
                            <label for="description" class="form-label fw-semibold">
                                <i class="fas fa-align-left text-primary me-2"></i> Description (Optional)
                            </label>
                            <div class="position-relative">
                                <textarea id="description" 
                                          name="description" 
                                          rows="6"
                                          class="form-control"
                                          placeholder="Describe the photo gallery content...">{{ old('description', $gallery->description) }}</textarea>
                                <div class="position-absolute bottom-0 end-0 p-2">
                                    <small class="text-muted" id="charCount">0/1000</small>
                                </div>
                            </div>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Optional: Add description about the photos in this gallery
                                </small>
                            </div>
                            @error('description')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="col-lg-4">
                        {{-- Competition Dropdown --}}
                        <div class="mb-4">
                            <label for="competition" class="form-label fw-semibold">
                                <i class="fas fa-trophy text-primary me-2"></i> Competition
                                <span class="text-danger">*</span>
                            </label>
                            <select id="competition" 
                                    name="competition" 
                                    required 
                                    class="form-select">
                                <option value="">-- Select Competition --</option>
                                @php
                                    $uniqueCompetitions = \App\Models\TeamList::select('competition')
                                        ->whereNotNull('competition')
                                        ->where('competition', '!=', '')
                                        ->distinct()
                                        ->orderBy('competition')
                                        ->pluck('competition');
                                @endphp
                                @foreach($uniqueCompetitions as $competition)
                                    <option value="{{ $competition }}" {{ old('competition', $gallery->competition) == $competition ? 'selected' : '' }}>{{ $competition }}</option>
                                @endforeach
                            </select>
                            @error('competition')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Season Dropdown --}}
                        <div class="mb-4">
                            <label for="season" class="form-label fw-semibold">
                                <i class="fas fa-calendar text-primary me-2"></i> Season
                                <span class="text-danger">*</span>
                            </label>
                            <select id="season" 
                                    name="season" 
                                    required 
                                    class="form-select">
                                <option value="">-- Select Season --</option>
                                @php
                                    $uniqueSeasons = \App\Models\TeamList::select('season')
                                        ->whereNotNull('season')
                                        ->where('season', '!=', '')
                                        ->distinct()
                                        ->orderBy('season', 'desc')
                                        ->pluck('season');
                                @endphp
                                @foreach($uniqueSeasons as $season)
                                    <option value="{{ $season }}" {{ old('season', $gallery->season) == $season ? 'selected' : '' }}>{{ $season }}</option>
                                @endforeach
                            </select>
                            @error('season')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Series Dropdown --}}
                        <div class="mb-4">
                            <label for="series" class="form-label fw-semibold">
                                <i class="fas fa-list-ol text-primary me-2"></i> Series
                                <span class="text-danger">*</span>
                            </label>
                            <select id="series" 
                                    name="series" 
                                    required 
                                    class="form-select">
                                <option value="">-- Select Series --</option>
                                @php
                                    $uniqueSeries = \App\Models\TeamList::select('series')
                                        ->whereNotNull('series')
                                        ->where('series', '!=', '')
                                        ->distinct()
                                        ->orderBy('series')
                                        ->pluck('series');
                                @endphp
                                @foreach($uniqueSeries as $series)
                                    <option value="{{ $series }}" {{ old('series', $gallery->series) == $series ? 'selected' : '' }}>{{ $series }}</option>
                                @endforeach
                            </select>
                            @error('series')
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
                                <option value="published" {{ old('status', $gallery->status) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ old('status', $gallery->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="archived" {{ old('status', $gallery->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Current File Info --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-file text-primary me-2"></i> Current File
                            </label>
                            <div class="card bg-light">
                                <div class="card-body p-3">
                                    <div class="text-center">
                                        <i class="fas fa-file-archive fa-2x text-warning mb-2"></i>
                                        <p class="mb-1 fw-semibold text-truncate">{{ $gallery->original_filename }}</p>
                                        <p class="small text-muted mb-0">{{ $gallery->formattedFileSize }}</p>
                                        <p class="small text-muted mt-2">
                                            <i class="fas fa-download me-1"></i>
                                            {{ $gallery->download_count }} downloads
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="form-text">
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Current file will be replaced if you upload a new one
                                </small>
                            </div>
                        </div>

                        {{-- Created/Updated Info --}}
                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-history text-primary me-2"></i> Gallery History
                            </label>
                            <div class="card bg-light">
                                <div class="card-body p-3">
                                    <div class="d-flex flex-column">
                                        <div class="mb-2">
                                            <span class="text-muted small">Created:</span>
                                            <div class="fw-semibold">{{ $gallery->created_at->format('d M Y, H:i') }}</div>
                                        </div>
                                        <div>
                                            <span class="text-muted small">Last Updated:</span>
                                            <div class="fw-semibold">{{ $gallery->updated_at->format('d M Y, H:i') }}</div>
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
                            <button type="button" 
                                    class="btn btn-outline-secondary" 
                                    onclick="resetToOriginal()">
                                <i class="fas fa-undo me-2"></i> Reset Changes
                            </button>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.gallery.photos.index') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                            <button type="submit" 
                                    class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Update Gallery
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
                    <small>Updating school, competition, season, or series will update the gallery metadata</small>
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>ZIP file update is optional - leave empty to keep current file</small>
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>Changing status to "Draft" will hide the gallery from public view</small>
                </li>
                <li class="mb-2">
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>Description helps users understand what's in the gallery</small>
                </li>
                <li>
                    <i class="fas fa-check-circle text-success me-2"></i>
                    <small>Use "Reset Changes" to revert all fields to original values</small>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- Delete Confirmation Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="deleteModalLabel">Delete Photo Gallery</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                </div>
                <h5 class="text-center mb-3">Are you sure?</h5>
                <p class="text-center text-muted">
                    You are about to delete the gallery for <strong>{{ $gallery->school_name }}</strong>.
                    This action cannot be undone.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.gallery.photos.destroy', $gallery->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Yes, Delete Permanently</button>
                </form>
            </div>
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
        min-height: 150px;
        resize: vertical;
    }

    .border-top {
        border-top: 1px solid #dee2e6 !important;
    }

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

    .file-info {
        border-left: 3px solid #3498db;
    }

    .alert-info {
        border-left: 4px solid #17a2b8;
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
    // Track if file is uploaded
    let fileUploaded = false;
    let originalFilename = '';
    
    // School dropdown change handler
    function updateManualInput() {
        const schoolSelect = document.getElementById('school_name');
        const manualInput = document.getElementById('manualSchoolInput');
        if (schoolSelect.value === 'other') {
            manualInput.classList.remove('d-none');
        } else {
            manualInput.classList.add('d-none');
        }
    }
    
    // File Preview Functionality
    function previewFile(event) {
        const input = event.target;
        const preview = document.getElementById('fileInfoPreview');
        const uploadArea = document.getElementById('uploadArea');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const maxSize = 5 * 1024 * 1024 * 1024; // 5GB
            
            // Check file size
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'ZIP file size should not exceed 5GB',
                    confirmButtonColor: '#3498db'
                });
                input.value = '';
                return;
            }
            
            // Check file extension
            const validExtensions = ['.zip', '.rar', '.7z'];
            const fileName = file.name.toLowerCase();
            const isValidExtension = validExtensions.some(ext => fileName.endsWith(ext));
            
            if (!isValidExtension) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Please upload a ZIP, RAR, or 7Z file',
                    confirmButtonColor: '#3498db'
                });
                input.value = '';
                return;
            }
            
            // Get file info
            originalFilename = file.name;
            const fileSize = formatBytes(file.size);
            const fileType = file.type || 'application/zip';
            
            // Update preview
            document.getElementById('fileNameDisplay').textContent = originalFilename;
            document.getElementById('fileSizeDisplay').textContent = fileSize;
            preview.classList.remove('d-none');
            uploadArea.classList.add('d-none');
            
            // Update file type hidden field
            document.getElementById('fileType').value = fileType;
            
            fileUploaded = true;
        }
    }
    
    function removeFile() {
        const preview = document.getElementById('fileInfoPreview');
        const uploadArea = document.getElementById('uploadArea');
        const fileInput = document.getElementById('file');
        
        preview.classList.add('d-none');
        uploadArea.classList.remove('d-none');
        fileInput.value = '';
        originalFilename = '';
        fileUploaded = false;
    }
    
    // Format bytes to human readable format
    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }
    
    // Character count for description
    function updateCharCount() {
        const textarea = document.getElementById('description');
        const charCount = document.getElementById('charCount');
        const length = textarea.value.length;
        charCount.textContent = `${length}/1000`;
        
        if (length > 1000) {
            charCount.classList.add('text-danger');
        } else {
            charCount.classList.remove('text-danger');
        }
    }
    
    // Reset form to original values
    function resetToOriginal() {
        Swal.fire({
            title: 'Reset Changes?',
            text: 'All changes will be reverted to original values.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3498db',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, reset',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Reload the page to reset all fields
                window.location.reload();
            }
        });
    }
    
    // Validate school name
    function validateSchool() {
        const schoolSelect = document.getElementById('school_name');
        const manualInput = document.getElementById('manual_school_name');
        
        if (schoolSelect.value === 'other') {
            // Check manual input
            if (!manualInput.value.trim()) {
                Swal.fire({
                    icon: 'error',
                    title: 'School Name Required',
                    text: 'Please enter school name in the manual input field',
                    confirmButtonColor: '#3498db'
                });
                manualInput.focus();
                return false;
            }
        } else if (!schoolSelect.value) {
            Swal.fire({
                icon: 'error',
                title: 'School Selection Required',
                text: 'Please select a school from the dropdown',
                confirmButtonColor: '#3498db'
            });
            schoolSelect.focus();
            return false;
        }
        
        return true;
    }
    
    // Validate all required dropdowns
    function validateDropdowns() {
        const competition = document.getElementById('competition');
        const season = document.getElementById('season');
        const series = document.getElementById('series');
        
        if (!competition.value) {
            Swal.fire({
                icon: 'error',
                title: 'Competition Required',
                text: 'Please select a competition',
                confirmButtonColor: '#3498db'
            });
            competition.focus();
            return false;
        }
        
        if (!season.value) {
            Swal.fire({
                icon: 'error',
                title: 'Season Required',
                text: 'Please select a season',
                confirmButtonColor: '#3498db'
            });
            season.focus();
            return false;
        }
        
        if (!series.value) {
            Swal.fire({
                icon: 'error',
                title: 'Series Required',
                text: 'Please select a series',
                confirmButtonColor: '#3498db'
            });
            series.focus();
            return false;
        }
        
        return true;
    }
    
    // Validate file if uploaded
    function validateFile() {
        const fileInput = document.getElementById('file');
        
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const maxSize = 5 * 1024 * 1024 * 1024; // 5GB
            
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Too Large',
                    text: 'ZIP file size should not exceed 5GB',
                    confirmButtonColor: '#3498db'
                });
                return false;
            }
            
            const validExtensions = ['.zip', '.rar', '.7z'];
            const fileName = file.name.toLowerCase();
            const isValidExtension = validExtensions.some(ext => fileName.endsWith(ext));
            
            if (!isValidExtension) {
                Swal.fire({
                    icon: 'error',
                    title: 'Invalid File Type',
                    text: 'Please upload a ZIP, RAR, or 7Z file',
                    confirmButtonColor: '#3498db'
                });
                return false;
            }
        }
        
        return true;
    }
    
    // Form submission handler
    document.getElementById('galleryForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate all required fields
        if (!validateSchool()) return false;
        if (!validateDropdowns()) return false;
        if (!validateFile()) return false;
        
        // Show confirmation
        Swal.fire({
            title: 'Update Photo Gallery?',
            html: '<div class="text-start">' +
                  '<p class="mb-2">Are you sure you want to update this photo gallery?</p>' +
                  '<p class="mb-0 small text-muted">' +
                  '<i class="fas fa-info-circle me-1"></i>' +
                  'Current file will be replaced if you uploaded a new one.' +
                  '</p>' +
                  '</div>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3498db',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, update it',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // If school is "other", use manual input value
                const schoolSelect = document.getElementById('school_name');
                if (schoolSelect.value === 'other') {
                    const manualInput = document.getElementById('manual_school_name');
                    // Create a hidden input to submit manual school name
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'school_name';
                    hiddenInput.value = manualInput.value;
                    this.appendChild(hiddenInput);
                }
                
                this.submit();
            }
        });
        
        return false;
    });

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize character count
        updateCharCount();
        
        // Check if manual school input should be shown
        updateManualInput();
        
        // Check if there are old input values (from validation errors)
        const schoolSelect = document.getElementById('school_name');
        if (schoolSelect.value === 'other') {
            document.getElementById('manualSchoolInput').classList.remove('d-none');
        }
    });
</script>

@endsection