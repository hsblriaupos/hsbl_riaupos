@extends('admin.layouts.app')
@section('title', 'Photos Gallery - Administrator')

@section('content')

{{-- Include SweetAlert2 --}}
@include('partials.sweetalert')

<div class="container-fluid px-3 mt-3">
    <!-- Page Header with Action Buttons -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-images text-primary me-2"></i> Photos Gallery
            </h1>
            <p class="page-subtitle mb-0">Manage all photo gallery content (ZIP files)</p>
        </div>
        
        <!-- Action Buttons - Posisi tengah -->
        <div>
            <a href="{{ route('admin.gallery.photos.form') }}" 
               class="btn btn-primary d-flex align-items-center px-3 py-2">
                <i class="fas fa-plus me-2"></i> Add Photo
            </a>
        </div>
    </div>

    <!-- Filter Section - Lebih Kompak -->
    <div class="card mb-3">
        <div class="card-body py-2 px-3">
            <form method="GET" action="{{ route('admin.gallery.photos.index') }}" class="row g-2 align-items-center">
                <div class="col-lg-3 col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted" style="font-size: 0.75rem;"></i>
                        </span>
                        <input name="search" 
                               type="text" 
                               value="{{ request('search') }}"
                               class="form-control form-control-sm border-start-0"
                               placeholder="Search school...">
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-trophy text-muted" style="font-size: 0.75rem;"></i>
                        </span>
                        <select name="competition" class="form-select form-select-sm border-start-0">
                            <option value="">All Competitions</option>
                            @forelse($competitions as $competition)
                                <option value="{{ $competition }}" {{ request('competition') == $competition ? 'selected' : '' }}>
                                    {{ $competition }}
                                </option>
                            @empty
                                <!-- Tidak ada data competition -->
                            @endforelse
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-calendar text-muted" style="font-size: 0.75rem;"></i>
                        </span>
                        <select name="season" class="form-select form-select-sm border-start-0">
                            <option value="">All Seasons</option>
                            @forelse($seasons as $season)
                                <option value="{{ $season }}" {{ request('season') == $season ? 'selected' : '' }}>
                                    {{ $season }}
                                </option>
                            @empty
                                <!-- Tidak ada data season -->
                            @endforelse
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-list-ol text-muted" style="font-size: 0.75rem;"></i>
                        </span>
                        <select name="series" class="form-select form-select-sm border-start-0">
                            <option value="">All Series</option>
                            @forelse($series as $serie)
                                <option value="{{ $serie }}" {{ request('series') == $serie ? 'selected' : '' }}>
                                    {{ $serie }}
                                </option>
                            @empty
                                <!-- Tidak ada data series -->
                            @endforelse
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-2 col-md-4">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-flag text-muted" style="font-size: 0.75rem;"></i>
                        </span>
                        <select name="status" class="form-select form-select-sm border-start-0">
                            <option value="">All Status</option>
                            <option value="draft" @selected(request('status') == 'draft')>Draft</option>
                            <option value="published" @selected(request('status') == 'published')>Published</option>
                            <option value="archived" @selected(request('status') == 'archived')>Archived</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-lg-1 col-md-6">
                    <div class="d-flex gap-1">
                        <button type="submit" class="btn btn-dark btn-sm flex-grow-1 d-flex align-items-center justify-content-center px-2">
                            <i class="fas fa-filter me-1" style="font-size: 0.7rem;"></i>
                            <span class="small">Filter</span>
                        </button>
                        <a href="{{ route('admin.gallery.photos.index') }}" 
                           class="btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center px-2"
                           data-bs-toggle="tooltip" data-bs-title="Reset">
                            <i class="fas fa-redo" style="font-size: 0.7rem;"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Photos Gallery Table - Lebih Kompak -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.7rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="px-1 py-1 text-center" style="width: 25px;">
                                <input type="checkbox" class="form-check-input" id="selectAll" style="margin: 0;">
                            </th>
                            <th class="px-1 py-1 text-center" style="width: 30px;">No</th>
                            <th class="px-1 py-1" style="min-width: 100px;">School</th>
                            <th class="px-1 py-1" style="min-width: 100px;">File Name</th>
                            <th class="px-1 py-1 text-center" style="width: 55px;">Size</th>
                            <th class="px-1 py-1 text-center" style="width: 70px;">Competition</th>
                            <th class="px-1 py-1 text-center" style="width: 45px;">Season</th>
                            <th class="px-1 py-1 text-center" style="width: 45px;">Series</th>
                            <th class="px-1 py-1 text-center" style="width: 60px;">Status</th>
                            <th class="px-1 py-1 text-center" style="width: 55px;">Downloads</th>
                            <th class="px-1 py-1 text-center" style="width: 70px;">Created</th>
                            <th class="px-1 py-1 text-center" style="width: 90px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($galleries as $index => $gallery)
                            <tr>
                                <td class="px-1 py-1 text-center">
                                    <input type="checkbox" 
                                           name="selected[]" 
                                           value="{{ $gallery->id }}" 
                                           class="form-check-input item-checkbox"
                                           style="margin: 0;">
                                </td>
                                <td class="px-1 py-1 text-center fw-medium text-muted">
                                    {{ $galleries->firstItem() + $index }}
                                </td>
                                <td class="px-1 py-1">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-school text-info me-1" style="font-size: 0.65rem;"></i>
                                        <span class="text-truncate" style="max-width: 90px;" 
                                              data-bs-toggle="tooltip" data-bs-title="{{ $gallery->school_name }}">
                                            {{ Str::limit($gallery->school_name, 15) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-1 py-1">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-file-archive text-warning me-1" style="font-size: 0.65rem;"></i>
                                        <span class="text-truncate" style="max-width: 90px;" 
                                              data-bs-toggle="tooltip" data-bs-title="{{ $gallery->original_filename }}">
                                            {{ Str::limit($gallery->original_filename, 15) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-1 py-1 text-center">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary px-1 py-0" 
                                          style="font-size: 0.6rem;"
                                          data-bs-toggle="tooltip" data-bs-title="File Size">
                                        @php
                                            $bytes = $gallery->file_size ?? 0;
                                            $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                                            $bytes = max($bytes, 0);
                                            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                                            $pow = min($pow, count($units) - 1);
                                            $bytes /= pow(1024, $pow);
                                            echo round($bytes, 1) . ' ' . $units[$pow];
                                        @endphp
                                    </span>
                                </td>
                                <td class="px-1 py-1 text-center">
                                    @php
                                        $compColors = [
                                            'HSBL' => 'bg-primary bg-opacity-10 text-primary',
                                            'HCC' => 'bg-success bg-opacity-10 text-success',
                                            'Lainnya' => 'bg-secondary bg-opacity-10 text-secondary'
                                        ];
                                        $compClass = $compColors[$gallery->competition] ?? 'bg-secondary bg-opacity-10 text-secondary';
                                    @endphp
                                    <span class="badge {{ $compClass }} px-1 py-0" style="font-size: 0.6rem;"
                                          data-bs-toggle="tooltip" data-bs-title="{{ $gallery->competition }}">
                                        {{ Str::limit($gallery->competition, 6) }}
                                    </span>
                                </td>
                                <td class="px-1 py-1 text-center">
                                    <span class="badge bg-info bg-opacity-10 text-info px-1 py-0" style="font-size: 0.6rem;"
                                          data-bs-toggle="tooltip" data-bs-title="Season {{ $gallery->season }}">
                                        {{ $gallery->season }}
                                    </span>
                                </td>
                                <td class="px-1 py-1 text-center">
                                    <span class="badge bg-purple bg-opacity-10 text-purple px-1 py-0" style="font-size: 0.6rem;"
                                          data-bs-toggle="tooltip" data-bs-title="Series {{ $gallery->series }}">
                                        {{ Str::limit($gallery->series, 4) }}
                                    </span>
                                </td>
                                <td class="px-1 py-1 text-center">
                                    @php
                                        if ($gallery->status === 'draft') {
                                            $badgeClass = 'bg-warning bg-opacity-20 text-warning';
                                            $badgeIcon = 'fa-edit';
                                            $statusText = 'Draft';
                                        } elseif ($gallery->status === 'published') {
                                            $badgeClass = 'bg-success bg-opacity-20 text-success';
                                            $badgeIcon = 'fa-check-circle';
                                            $statusText = 'Published';
                                        } elseif ($gallery->status === 'archived') {
                                            $badgeClass = 'bg-secondary bg-opacity-10 text-secondary';
                                            $badgeIcon = 'fa-archive';
                                            $statusText = 'Archived';
                                        } else {
                                            $badgeClass = 'bg-light text-dark';
                                            $badgeIcon = 'fa-question-circle';
                                            $statusText = ucfirst($gallery->status);
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-1 py-0" style="font-size: 0.6rem;"
                                          data-bs-toggle="tooltip" data-bs-title="{{ $statusText }}">
                                        <i class="fas {{ $badgeIcon }} me-1" style="font-size: 0.5rem;"></i>
                                        {{ Str::limit($statusText, 3) }}
                                    </span>
                                </td>
                                <td class="px-1 py-1 text-center">
                                    <div class="fw-semibold text-dark" style="font-size: 0.7rem;">{{ $gallery->download_count ?? 0 }}</div>
                                </td>
                                <td class="px-1 py-1 text-center">
                                    <div style="font-size: 0.6rem;" data-bs-toggle="tooltip" 
                                         data-bs-title="{{ $gallery->created_at->format('Y-m-d H:i') }}">
                                        {{ $gallery->created_at->format('d/m') }}
                                    </div>
                                </td>
                                <td class="px-1 py-1 text-center">
                                    <div class="btn-group btn-group-xs" role="group">
                                        <!-- View Details -->
                                        <button type="button" 
                                                class="btn btn-outline-info border-1 view-details-btn"
                                                data-bs-toggle="tooltip" 
                                                data-bs-title="Details"
                                                style="padding: 0.1rem 0.25rem; font-size: 0.6rem;"
                                                data-gallery-id="{{ $gallery->id }}"
                                                data-school-name="{{ $gallery->school_name }}"
                                                data-filename="{{ $gallery->original_filename }}"
                                                data-file-size="@php
                                                    $bytes = $gallery->file_size ?? 0;
                                                    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                                                    $bytes = max($bytes, 0);
                                                    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                                                    $pow = min($pow, count($units) - 1);
                                                    $bytes /= pow(1024, $pow);
                                                    echo round($bytes, 2) . ' ' . $units[$pow];
                                                @endphp"
                                                data-file-type="{{ $gallery->file_type }}"
                                                data-competition="{{ $gallery->competition }}"
                                                data-season="{{ $gallery->season }}"
                                                data-series="{{ $gallery->series }}"
                                                data-description="{{ $gallery->description }}"
                                                data-status="{{ $gallery->status }}"
                                                data-download-count="{{ $gallery->download_count ?? 0 }}"
                                                data-download-url="{{ route('admin.gallery.photos.download', $gallery->id) }}"
                                                data-created-at="{{ $gallery->created_at->format('d M Y H:i') }}"
                                                data-updated-at="{{ $gallery->updated_at->format('d M Y H:i') }}">
                                            <i class="fas fa-eye" style="font-size: 0.55rem;"></i>
                                        </button>
                                        
                                        <!-- Download Button -->
                                        <a href="{{ route('admin.gallery.photos.download', $gallery->id) }}" 
                                           class="btn btn-outline-success border-1"
                                           data-bs-toggle="tooltip" 
                                           data-bs-title="Download"
                                           style="padding: 0.1rem 0.25rem; font-size: 0.6rem;">
                                            <i class="fas fa-download" style="font-size: 0.55rem;"></i>
                                        </a>
                                        
                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.gallery.photos.edit', $gallery->id) }}" 
                                           class="btn btn-outline-primary border-1"
                                           data-bs-toggle="tooltip" 
                                           data-bs-title="Edit"
                                           style="padding: 0.1rem 0.25rem; font-size: 0.6rem;">
                                            <i class="fas fa-edit" style="font-size: 0.55rem;"></i>
                                        </a>
                                        
                                        <!-- Delete Button -->
                                        <button type="button" 
                                                class="btn btn-outline-danger border-1 delete-btn"
                                                data-bs-toggle="tooltip" 
                                                data-bs-title="Delete"
                                                style="padding: 0.1rem 0.25rem; font-size: 0.6rem;"
                                                data-gallery-id="{{ $gallery->id }}"
                                                data-school-name="{{ $gallery->school_name }}">
                                            <i class="fas fa-trash" style="font-size: 0.55rem;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-2 py-3 text-center">
                                    <div class="py-2">
                                        <i class="fas fa-images fa-lg text-muted mb-1"></i>
                                        <p class="text-muted small mb-1">No Photo Galleries Found</p>
                                        <a href="{{ route('admin.gallery.photos.form') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i> Add First Gallery
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Table Footer with Pagination --}}
        @if($galleries->hasPages() || $galleries->total() > 10)
            <div class="card-footer bg-white border-top px-2 py-1">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="small text-muted mb-0" style="font-size: 0.65rem;">
                            Showing {{ $galleries->firstItem() ?: 0 }} to {{ $galleries->lastItem() ?: 0 }} of {{ $galleries->total() }}
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Per Page Selector -->
                        <form method="GET" action="{{ route('admin.gallery.photos.index') }}" class="d-flex align-items-center">
                            @foreach(request()->except(['per_page', 'page']) as $key => $value)
                                @if($value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            <select name="per_page" class="form-select form-select-xs" style="width: auto; font-size: 0.65rem; padding: 0.15rem 0.5rem;" onchange="this.form.submit()">
                                <option value="10" @selected(request('per_page', 10)==10)>10</option>
                                <option value="25" @selected(request('per_page')==25)>25</option>
                                <option value="50" @selected(request('per_page')==50)>50</option>
                                <option value="100" @selected(request('per_page')==100)>100</option>
                            </select>
                        </form>
                        
                        <!-- Pagination -->
                        @if($galleries->hasPages())
                            <div style="font-size: 0.65rem;">
                                {{ $galleries->withQueryString()->onEachSide(0)->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    {{-- Bulk Actions --}}
    @if($galleries->count() > 0)
    <div class="mt-2 d-flex justify-content-between align-items-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="bulkSelectAll" style="margin-top: 0.15rem;">
            <label class="form-check-label small text-muted" for="bulkSelectAll" style="font-size: 0.7rem;">
                Select all ({{ $galleries->total() }})
            </label>
        </div>
        <div class="d-flex gap-1">
            <button type="button" class="btn btn-xs btn-outline-success" id="bulkDownloadBtn" style="font-size: 0.7rem; padding: 0.15rem 0.5rem;">
                <i class="fas fa-download me-1" style="font-size: 0.6rem;"></i> Download
            </button>
            <button type="button" class="btn btn-xs btn-outline-danger" id="bulkDeleteBtn" style="font-size: 0.7rem; padding: 0.15rem 0.5rem;">
                <i class="fas fa-trash me-1" style="font-size: 0.6rem;"></i> Delete
            </button>
        </div>
    </div>
    @endif
</div>

{{-- Modal for Viewing Details --}}
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header py-1 px-3">
                <h6 class="modal-title fw-semibold" id="detailsModalLabel" style="font-size: 0.9rem;">
                    <i class="fas fa-info-circle text-primary me-2"></i> Gallery Details
                </h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Cover Photo Section -->
                    <div class="col-md-5 border-end">
                        <div class="text-center py-3 px-2">
                            <div class="d-flex justify-content-center align-items-center mb-2" style="height: 140px;">
                                <div id="fileIconContainer" class="d-flex justify-content-center align-items-center w-100">
                                    <!-- Cover photo or file icon will be shown here -->
                                    <div id="coverPhotoDisplay">
                                        <!-- Akan diisi oleh JavaScript -->
                                        <i class="fas fa-file-archive text-warning" style="font-size: 3rem;"></i>
                                    </div>
                                </div>
                            </div>
                            <h6 class="fw-bold mb-1" id="schoolName" style="font-size: 0.85rem;">-</h6>
                            <div class="badge bg-primary bg-opacity-10 text-primary px-2 py-0" style="font-size: 0.6rem;">
                                ZIP Archive
                            </div>
                            <div class="mt-2" id="coverPhotoInfo" style="font-size: 0.65rem;">
                                <!-- Cover photo info will appear here -->
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gallery Info -->
                    <div class="col-md-7">
                        <div class="py-3 px-3">
                            <div class="row mb-2">
                                <div class="col-5">
                                    <span class="text-muted small" style="font-size: 0.65rem;">Competition:</span>
                                </div>
                                <div class="col-7">
                                    <span class="fw-semibold small" id="detailCompetition" style="font-size: 0.7rem;">-</span>
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-5">
                                    <span class="text-muted small" style="font-size: 0.65rem;">Season/Series:</span>
                                </div>
                                <div class="col-7">
                                    <span class="fw-semibold small" id="detailSeasonSeries" style="font-size: 0.7rem;">-</span>
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-5">
                                    <span class="text-muted small" style="font-size: 0.65rem;">File Name:</span>
                                </div>
                                <div class="col-7">
                                    <span class="small text-truncate" id="detailFilename" style="font-size: 0.7rem; display: block; max-width: 180px;" 
                                          data-bs-toggle="tooltip" data-bs-placement="top">-</span>
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-5">
                                    <span class="text-muted small" style="font-size: 0.65rem;">File Size:</span>
                                </div>
                                <div class="col-7">
                                    <span class="fw-semibold small" id="detailFileSize" style="font-size: 0.7rem;">-</span>
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-5">
                                    <span class="text-muted small" style="font-size: 0.65rem;">Status:</span>
                                </div>
                                <div class="col-7">
                                    <span class="badge" id="detailStatus" style="font-size: 0.6rem;">-</span>
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-5">
                                    <span class="text-muted small" style="font-size: 0.65rem;">Downloads:</span>
                                </div>
                                <div class="col-7">
                                    <span class="fw-semibold small" id="detailDownloadCount" style="font-size: 0.7rem;">0</span>
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-5">
                                    <span class="text-muted small" style="font-size: 0.65rem;">Created:</span>
                                </div>
                                <div class="col-7">
                                    <span class="small" id="detailCreatedAt" style="font-size: 0.65rem;">-</span>
                                </div>
                            </div>
                            
                            @if($gallery->description ?? false)
                            <div class="mt-2">
                                <span class="text-muted small" style="font-size: 0.65rem;">Description:</span>
                                <div id="detailDescription" class="small bg-light p-1 rounded mt-1" style="font-size: 0.65rem; max-height: 60px; overflow-y: auto;">
                                    -
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-1 px-3">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">
                    <i class="fas fa-times me-1" style="font-size: 0.6rem;"></i> Close
                </button>
                <a href="#" id="editLink" class="btn btn-sm btn-primary" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">
                    <i class="fas fa-edit me-1" style="font-size: 0.6rem;"></i> Edit
                </a>
                <a href="#" id="downloadLink" class="btn btn-sm btn-success" style="font-size: 0.7rem; padding: 0.2rem 0.5rem;">
                    <i class="fas fa-download me-1" style="font-size: 0.6rem;"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Hidden Bulk Forms --}}
<form id="bulkDeleteForm" method="POST" action="{{ route('admin.gallery.photos.bulk-destroy') }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<form id="bulkDownloadForm" method="POST" action="{{ route('admin.gallery.photos.bulk-download') }}" style="display: none;">
    @csrf
</form>

<style>
    .page-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .page-subtitle {
        color: #7f8c8d;
        font-size: 0.75rem;
    }

    .card {
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .card-body {
        padding: 8px;
    }

    .table {
        font-size: 0.7rem !important;
        margin-bottom: 0;
    }

    .table th {
        font-weight: 600;
        font-size: 0.65rem;
        text-transform: uppercase;
        letter-spacing: 0.2px;
        color: #475569;
        background-color: #f8fafc;
        white-space: nowrap;
        border-bottom: 1px solid #e9ecef;
        padding: 4px 2px !important;
    }

    .table td {
        vertical-align: middle;
        border-top: 1px solid #f1f5f9;
        padding: 4px 2px !important;
    }

    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }

    .btn-group-xs > .btn {
        padding: 0.1rem 0.25rem;
        border-radius: 0.15rem;
    }

    .form-check-input {
        width: 0.85rem;
        height: 0.85rem;
        margin-top: 0.1rem;
    }

    .badge {
        font-weight: 500;
        border: 1px solid transparent;
    }

    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .bg-purple {
        background-color: #6f42c1 !important;
        color: white !important;
    }
    
    .bg-purple.bg-opacity-10 {
        background-color: rgba(111, 66, 193, 0.1) !important;
        color: #6f42c1 !important;
    }

    .bg-warning.bg-opacity-20 {
        background-color: rgba(255, 193, 7, 0.2) !important;
    }
    
    .bg-success.bg-opacity-20 {
        background-color: rgba(40, 167, 69, 0.2) !important;
    }

    .form-select-xs {
        font-size: 0.65rem;
        padding: 0.15rem 0.5rem;
        height: 22px;
    }

    .btn-xs {
        font-size: 0.7rem;
        padding: 0.15rem 0.5rem;
        border-radius: 3px;
    }

    .container-fluid {
        max-width: 1600px;
    }

    /* Modal styling */
    .modal-md {
        max-width: 500px;
    }
    
    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    
    .modal-footer {
        border-top: 1px solid #dee2e6;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 8px;
            padding-right: 8px;
        }
        
        .page-title {
            font-size: 0.9rem;
        }
        
        .btn-primary {
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
        }
        
        .table th, .table td {
            padding: 3px 1px !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Select All functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        const bulkSelectAllCheckbox = document.getElementById('bulkSelectAll');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                if (bulkSelectAllCheckbox) {
                    bulkSelectAllCheckbox.checked = selectAllCheckbox.checked;
                }
            });
        }
        
        if (bulkSelectAllCheckbox) {
            bulkSelectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = bulkSelectAllCheckbox.checked;
                });
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = bulkSelectAllCheckbox.checked;
                }
            });
        }
        
        // Update checkboxes when individual items are checked
        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                const anyChecked = Array.from(itemCheckboxes).some(cb => cb.checked);
                
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = anyChecked && !allChecked;
                }
                
                if (bulkSelectAllCheckbox) {
                    bulkSelectAllCheckbox.checked = allChecked;
                    bulkSelectAllCheckbox.indeterminate = anyChecked && !allChecked;
                }
            });
        });
        
        // Handle View Details button click
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Get data from button attributes
                const galleryId = this.getAttribute('data-gallery-id');
                const schoolName = this.getAttribute('data-school-name');
                const filename = this.getAttribute('data-filename');
                const fileSize = this.getAttribute('data-file-size');
                const fileType = this.getAttribute('data-file-type');
                const competition = this.getAttribute('data-competition');
                const season = this.getAttribute('data-season');
                const series = this.getAttribute('data-series');
                const description = this.getAttribute('data-description');
                const status = this.getAttribute('data-status');
                const downloadCount = this.getAttribute('data-download-count');
                const downloadUrl = this.getAttribute('data-download-url');
                const createdAt = this.getAttribute('data-created-at');
                const updatedAt = this.getAttribute('data-updated-at');
                
                // Set modal content
                document.getElementById('schoolName').textContent = schoolName;
                document.getElementById('detailFilename').textContent = filename || 'N/A';
                document.getElementById('detailFileSize').textContent = fileSize || 'N/A';
                document.getElementById('detailCompetition').textContent = competition || 'N/A';
                document.getElementById('detailSeasonSeries').textContent = (season && series) ? season + ' - ' + series : (season || series || 'N/A');
                document.getElementById('detailDownloadCount').textContent = downloadCount || '0';
                document.getElementById('detailCreatedAt').textContent = createdAt || 'N/A';
                
                // Set description
                const descriptionElement = document.getElementById('detailDescription');
                if (descriptionElement) {
                    descriptionElement.textContent = description || 'No description provided';
                }
                
                // Set status badge
                const statusElement = document.getElementById('detailStatus');
                let statusText, statusClass;
                
                if (status === 'published') {
                    statusText = 'Published';
                    statusClass = 'bg-success bg-opacity-20 text-success';
                } else if (status === 'draft') {
                    statusText = 'Draft';
                    statusClass = 'bg-warning bg-opacity-20 text-warning';
                } else if (status === 'archived') {
                    statusText = 'Archived';
                    statusClass = 'bg-secondary bg-opacity-10 text-secondary';
                } else {
                    statusText = status || 'Unknown';
                    statusClass = 'bg-light text-dark';
                }
                
                statusElement.textContent = statusText;
                statusElement.className = `badge ${statusClass} px-2 py-0`;
                
                // Set edit link
                const editLinkElement = document.getElementById('editLink');
                editLinkElement.href = `/admin/gallery/photos/${galleryId}/edit`;
                
                // Set download link
                const downloadLinkElement = document.getElementById('downloadLink');
                if (downloadUrl && downloadUrl !== '#') {
                    downloadLinkElement.href = downloadUrl;
                    downloadLinkElement.onclick = null;
                } else {
                    downloadLinkElement.href = '#';
                    downloadLinkElement.onclick = (e) => {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Download Not Available',
                            text: 'The download link is not available for this gallery.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    };
                }
                
                // Show modal
                const detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
                detailsModal.show();
            });
        });
        
        // Handle delete buttons
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const galleryId = this.getAttribute('data-gallery-id');
                const schoolName = this.getAttribute('data-school-name');
                
                Swal.fire({
                    title: 'Delete Gallery?',
                    html: `Delete gallery for <strong>"${schoolName}"</strong>?`,
                    text: 'This will permanently delete the ZIP file.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/gallery/photos/${galleryId}`;
                        form.style.display = 'none';
                        
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = "{{ csrf_token() }}";
                        form.appendChild(csrfInput);
                        
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);
                        
                        document.body.appendChild(form);
                        form.submit();
                        
                        return false;
                    }
                });
            });
        });
        
        // Bulk Delete
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const bulkDownloadBtn = document.getElementById('bulkDownloadBtn');
        
        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', function() {
                const selectedItems = Array.from(document.querySelectorAll('.item-checkbox:checked'));
                
                if (selectedItems.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Items Selected',
                        text: 'Please select at least one item',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
                
                Swal.fire({
                    title: 'Delete Selected?',
                    text: `Delete ${selectedItems.length} selected item(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        const bulkDeleteForm = document.getElementById('bulkDeleteForm');
                        const selectedIds = selectedItems.map(item => item.value);
                        
                        const existingInputs = bulkDeleteForm.querySelectorAll('input[name="selected[]"]');
                        existingInputs.forEach(input => input.remove());
                        
                        selectedIds.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'selected[]';
                            input.value = id;
                            bulkDeleteForm.appendChild(input);
                        });
                        
                        bulkDeleteForm.submit();
                        
                        return false;
                    }
                });
            });
        }
        
        // Bulk Download
        if (bulkDownloadBtn) {
            bulkDownloadBtn.addEventListener('click', function() {
                const selectedItems = Array.from(document.querySelectorAll('.item-checkbox:checked'));
                
                if (selectedItems.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Items Selected',
                        text: 'Please select at least one item',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
                
                Swal.fire({
                    title: 'Download Selected?',
                    text: `Download ${selectedItems.length} ZIP file(s)?`,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, download',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        const bulkDownloadForm = document.getElementById('bulkDownloadForm');
                        const selectedIds = selectedItems.map(item => item.value);
                        
                        const existingInputs = bulkDownloadForm.querySelectorAll('input[name="selected[]"]');
                        existingInputs.forEach(input => input.remove());
                        
                        selectedIds.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'selected[]';
                            input.value = id;
                            bulkDownloadForm.appendChild(input);
                        });
                        
                        bulkDownloadForm.submit();
                        
                        return false;
                    }
                });
            });
        }
    });
</script>

@endsection