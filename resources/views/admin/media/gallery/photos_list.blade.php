@extends('admin.layouts.app')
@section('title', 'Photos Gallery - Administrator')

@section('content')

{{-- Include SweetAlert2 --}}
@include('partials.sweetalert')

<div class="container mt-4">
    <!-- Page Header with Action Buttons -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="page-title">
                <i class="fas fa-images text-primary me-2"></i> Photos Gallery
            </h1>
            <p class="page-subtitle">Manage all photo gallery content (ZIP files)</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="{{ route('admin.gallery.photos.form') }}" 
               class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-plus me-2"></i> Add Photo Gallery
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.gallery.photos.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Search School Name</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input name="search" 
                               type="text" 
                               value="{{ request('search') }}"
                               class="form-control border-start-0"
                               placeholder="Search school name...">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Competition</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-trophy text-muted"></i>
                        </span>
                        <select name="competition" class="form-select border-start-0">
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
                
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Season</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-calendar text-muted"></i>
                        </span>
                        <select name="season" class="form-select border-start-0">
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
                
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Series</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-list-ol text-muted"></i>
                        </span>
                        <select name="series" class="form-select border-start-0">
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
                
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Show Per Page</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-list text-muted"></i>
                        </span>
                        <select name="per_page" class="form-select border-start-0" onchange="this.form.submit()">
                            <option value="10" @selected(request('per_page', 10) == 10)>10</option>
                            <option value="25" @selected(request('per_page') == 25)>25</option>
                            <option value="50" @selected(request('per_page') == 50)>50</option>
                            <option value="100" @selected(request('per_page') == 100)>100</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-1">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-dark btn-sm flex-grow-1 d-flex align-items-center justify-content-center">
                            <i class="fas fa-filter me-1"></i> Filter
                        </button>
                        <a href="{{ route('admin.gallery.photos.index') }}" 
                           class="btn btn-outline-secondary btn-sm d-flex align-items-center justify-content-center"
                           style="width: 40px;"
                           data-bs-toggle="tooltip" data-bs-title="Reset">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Photos Gallery Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.8rem; min-width: 1000px;">
                    <thead class="table-light">
                        <tr>
                            <th class="px-2 py-1" style="width: 30px;">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th class="px-2 py-1" style="width: 40px;">No</th>
                            <th class="px-2 py-1" style="min-width: 120px; max-width: 150px;">School</th>
                            <th class="px-2 py-1" style="min-width: 120px; max-width: 150px;">File Name</th>
                            <th class="px-2 py-1" style="width: 80px;">Size</th>
                            <th class="px-2 py-1" style="width: 100px;">Competition</th>
                            <th class="px-2 py-1" style="width: 60px;">Season</th>
                            <th class="px-2 py-1" style="width: 60px;">Series</th>
                            <th class="px-2 py-1" style="width: 80px;">Status</th>
                            <th class="px-2 py-1" style="width: 70px;">D/L</th>
                            <th class="px-2 py-1" style="width: 90px;">Created</th>
                            <th class="px-2 py-1 text-center" style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($galleries as $index => $gallery)
                            <tr>
                                <td class="px-2 py-1">
                                    <input type="checkbox" 
                                           name="selected[]" 
                                           value="{{ $gallery->id }}" 
                                           class="form-check-input item-checkbox">
                                </td>
                                <td class="px-2 py-1 fw-medium text-muted">
                                    {{ $galleries->firstItem() + $index }}
                                </td>
                                <td class="px-2 py-1">
                                    <div class="fw-semibold text-dark text-truncate" style="max-width: 150px;" 
                                         data-bs-toggle="tooltip" data-bs-title="{{ $gallery->school_name }}">
                                        <i class="fas fa-school text-info me-1"></i>
                                        {{ Str::limit($gallery->school_name, 20) }}
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    <div class="text-truncate" 
                                         style="max-width: 150px;"
                                         data-bs-toggle="tooltip" 
                                         data-bs-title="{{ $gallery->original_filename }}">
                                        <i class="fas fa-file-archive text-warning me-1"></i>
                                        {{ Str::limit($gallery->original_filename, 20) }}
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1"
                                          data-bs-toggle="tooltip" data-bs-title="File Size">
                                        <i class="fas fa-hdd me-1" style="font-size: 0.7rem;"></i>
                                        <span class="small">
                                            @php
                                                $bytes = $gallery->file_size ?? 0;
                                                $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                                                $bytes = max($bytes, 0);
                                                $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                                                $pow = min($pow, count($units) - 1);
                                                $bytes /= pow(1024, $pow);
                                                echo round($bytes, 2) . ' ' . $units[$pow];
                                            @endphp
                                        </span>
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    @php
                                        $compColors = [
                                            'HSBL' => 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25',
                                            'HCC' => 'bg-success bg-opacity-10 text-success border border-success border-opacity-25',
                                            'Lainnya' => 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25'
                                        ];
                                        $compClass = $compColors[$gallery->competition] ?? 'bg-secondary bg-opacity-10 text-secondary';
                                    @endphp
                                    <span class="badge {{ $compClass }} px-2 py-1 d-flex align-items-center justify-content-center"
                                          data-bs-toggle="tooltip" 
                                          data-bs-title="{{ $gallery->competition }}">
                                        <i class="fas fa-trophy me-1" style="font-size: 0.7rem;"></i>
                                        <span class="small">{{ $gallery->competition }}</span>
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1"
                                          data-bs-toggle="tooltip" 
                                          data-bs-title="Season {{ $gallery->season }}">
                                        <i class="fas fa-calendar-alt me-1" style="font-size: 0.7rem;"></i>
                                        <span class="small">{{ $gallery->season }}</span>
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    <span class="badge bg-purple bg-opacity-10 text-purple border border-purple border-opacity-25 px-2 py-1"
                                          data-bs-toggle="tooltip" 
                                          data-bs-title="Series {{ $gallery->series }}">
                                        <i class="fas fa-list-ol me-1" style="font-size: 0.7rem;"></i>
                                        <span class="small">{{ $gallery->series }}</span>
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    @php
                                        if ($gallery->status === 'draft') {
                                            $badgeClass = 'bg-warning bg-opacity-20 text-warning border border-warning border-opacity-50';
                                            $badgeIcon = 'fas fa-edit';
                                            $statusText = 'Draft';
                                        } elseif ($gallery->status === 'published') {
                                            $badgeClass = 'bg-success bg-opacity-20 text-success border border-success border-opacity-50';
                                            $badgeIcon = 'fas fa-check-circle';
                                            $statusText = 'Published';
                                        } elseif ($gallery->status === 'archived') {
                                            $badgeClass = 'bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25';
                                            $badgeIcon = 'fas fa-archive';
                                            $statusText = 'Archived';
                                        } else {
                                            $badgeClass = 'bg-light text-dark';
                                            $badgeIcon = 'fas fa-question-circle';
                                            $statusText = ucfirst($gallery->status);
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-2 py-1 d-flex align-items-center justify-content-center"
                                          data-bs-toggle="tooltip" 
                                          data-bs-title="{{ $statusText }}">
                                        <i class="{{ $badgeIcon }} me-1" style="font-size: 0.7rem;"></i>
                                        <span class="small">{{ $statusText }}</span>
                                    </span>
                                </td>
                                <td class="px-2 py-1 text-center">
                                    <div class="fw-semibold text-dark">{{ $gallery->download_count ?? 0 }}</div>
                                    <small class="text-muted" style="font-size: 0.65rem;">downloads</small>
                                </td>
                                <td class="px-2 py-1">
                                    <div class="small">
                                        <div class="text-dark" data-bs-toggle="tooltip" 
                                             data-bs-title="{{ $gallery->created_at->format('Y-m-d H:i:s') }}">
                                            {{ $gallery->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="text-muted" style="font-size: 0.65rem;">
                                            {{ $gallery->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-1 text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- View Details (Eye Icon) -->
                                        <button type="button" 
                                                class="btn btn-outline-info border-1 view-details-btn"
                                                data-bs-toggle="tooltip" 
                                                data-bs-title="View Details"
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
                                            <i class="fas fa-eye" style="font-size: 0.7rem;"></i>
                                        </button>
                                        
                                        <!-- Download Button -->
                                        <a href="{{ route('admin.gallery.photos.download', $gallery->id) }}" 
                                           class="btn btn-outline-success border-1"
                                           data-bs-toggle="tooltip" 
                                           data-bs-title="Download ZIP">
                                            <i class="fas fa-download" style="font-size: 0.7rem;"></i>
                                        </a>
                                        
                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.gallery.photos.edit', $gallery->id) }}" 
                                           class="btn btn-outline-primary border-1"
                                           data-bs-toggle="tooltip" 
                                           data-bs-title="Edit">
                                            <i class="fas fa-edit" style="font-size: 0.7rem;"></i>
                                        </a>
                                        
                                        <!-- Delete Button -->
                                        <button type="button" 
                                                class="btn btn-outline-danger border-1 delete-btn"
                                                data-bs-toggle="tooltip" 
                                                data-bs-title="Delete"
                                                data-gallery-id="{{ $gallery->id }}"
                                                data-school-name="{{ $gallery->school_name }}">
                                            <i class="fas fa-trash" style="font-size: 0.7rem;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-4 py-3 text-center">
                                    <div class="py-3">
                                        <i class="fas fa-images fa-lg text-muted mb-2"></i>
                                        <h6 class="text-muted">No Photo Galleries Found</h6>
                                        <p class="text-muted small mb-2">Start by adding your first photo gallery (ZIP file)</p>
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
            <div class="card-footer bg-white border-top px-3 py-2">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-2 mb-md-0">
                        <p class="small text-muted mb-0">
                            Showing <span class="fw-semibold">{{ $galleries->firstItem() ?: 0 }}</span> to 
                            <span class="fw-semibold">{{ $galleries->lastItem() ?: 0 }}</span> of 
                            <span class="fw-semibold">{{ $galleries->total() }}</span> results
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Per Page Selector -->
                        <div class="d-none d-md-block">
                            <form method="GET" action="{{ route('admin.gallery.photos.index') }}" class="d-flex align-items-center">
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if(request('competition'))
                                    <input type="hidden" name="competition" value="{{ request('competition') }}">
                                @endif
                                @if(request('season'))
                                    <input type="hidden" name="season" value="{{ request('season') }}">
                                @endif
                                @if(request('series'))
                                    <input type="hidden" name="series" value="{{ request('series') }}">
                                @endif
                                <span class="small text-muted me-2">Show:</span>
                                <select name="per_page" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                    <option value="10" @selected(request('per_page', 10)==10)>10</option>
                                    <option value="25" @selected(request('per_page')==25)>25</option>
                                    <option value="50" @selected(request('per_page')==50)>50</option>
                                    <option value="100" @selected(request('per_page')==100)>100</option>
                                </select>
                            </form>
                        </div>
                        
                        <!-- Pagination -->
                        @if($galleries->hasPages())
                            <div>
                                {{ $galleries->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    {{-- Bulk Actions --}}
    @if($galleries->count() > 0)
    <div class="mt-3 d-flex justify-content-between align-items-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="bulkSelectAll">
            <label class="form-check-label small text-muted" for="bulkSelectAll">
                Select all items ({{ $galleries->total() }} total)
            </label>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-success" id="bulkDownloadBtn">
                <i class="fas fa-download me-1"></i> Download Selected
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn">
                <i class="fas fa-trash me-1"></i> Delete Selected
            </button>
        </div>
    </div>
    @endif
</div>

{{-- Modal for Viewing Details --}}
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title fw-semibold" id="detailsModalLabel">Gallery Details</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- File Icon Section -->
                    <div class="col-md-4 border-end">
                        <div class="text-center py-4">
                            <div class="d-flex justify-content-center align-items-center mb-3" style="height: 180px;">
                                <div id="fileIconContainer" class="d-flex justify-content-center align-items-center w-100">
                                    <!-- File icon akan diisi oleh JavaScript -->
                                </div>
                            </div>
                            <h6 class="fw-bold mb-1" id="schoolName"></h6>
                            <div class="badge bg-primary bg-opacity-10 text-primary py-1 px-3 mt-2">
                                ZIP Archive
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gallery Info -->
                    <div class="col-md-8">
                        <div class="py-4 px-3">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="d-flex flex-column mb-2">
                                        <span class="text-muted small mb-1">Competition:</span>
                                        <span class="fw-semibold" id="detailCompetition">-</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex flex-column mb-2">
                                        <span class="text-muted small mb-1">Status:</span>
                                        <span class="badge" id="detailStatus">-</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-column mb-2">
                                <span class="text-muted small mb-1">File Name:</span>
                                <span class="fw-semibold text-monospace small" id="detailFilename">-</span>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-6">
                                    <div class="d-flex flex-column">
                                        <span class="text-muted small mb-1">File Size:</span>
                                        <span class="fw-semibold" id="detailFileSize">-</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex flex-column">
                                        <span class="text-muted small mb-1">File Type:</span>
                                        <span class="fw-semibold" id="detailFileType">-</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mb-2">
                                <div class="col-6">
                                    <div class="d-flex flex-column">
                                        <span class="text-muted small mb-1">Season:</span>
                                        <span class="fw-semibold" id="detailSeason">-</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex flex-column">
                                        <span class="text-muted small mb-1">Series:</span>
                                        <span class="fw-semibold" id="detailSeries">-</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-column mb-2">
                                <span class="text-muted small mb-1">Total Downloads:</span>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold fs-5" id="detailDownloadCount">0</span>
                                    <span class="text-muted small">times</span>
                                </div>
                            </div>
                            
                            <div class="d-flex flex-column mb-2">
                                <span class="text-muted small mb-1">Description:</span>
                                <div id="detailDescription" class="small bg-light p-2 rounded" style="max-height: 100px; overflow-y: auto;">
                                    -
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-6">
                                    <div class="d-flex flex-column">
                                        <span class="text-muted small mb-1">Created:</span>
                                        <span class="small" id="detailCreatedAt">-</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="d-flex flex-column">
                                        <span class="text-muted small mb-1">Last Updated:</span>
                                        <span class="small" id="detailUpdatedAt">-</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="editLink" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit me-1"></i> Edit Gallery
                </a>
                <a href="#" id="downloadLink" class="btn btn-sm btn-success">
                    <i class="fas fa-download me-1"></i> Download
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Hidden Bulk Delete Form --}}
<form id="bulkDeleteForm" method="POST" action="{{ route('admin.gallery.photos.bulk-destroy') }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>

{{-- Hidden Bulk Download Form --}}
<form id="bulkDownloadForm" method="POST" action="{{ route('admin.gallery.photos.bulk-download') }}" style="display: none;">
    @csrf
</form>

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

    .btn-outline-primary, .btn-outline-danger, .btn-outline-info, .btn-outline-success {
        border-width: 1px;
    }

    .badge {
        padding: 0.25em 0.5em;
        font-weight: 500;
        font-size: 0.75em;
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

    /* Warna kompetisi dan status */
    .bg-primary.bg-opacity-10 {
        background-color: rgba(52, 152, 219, 0.1) !important;
    }
    
    .bg-success.bg-opacity-10 {
        background-color: rgba(40, 167, 69, 0.1) !important;
    }
    
    .bg-info.bg-opacity-10 {
        background-color: rgba(23, 162, 184, 0.1) !important;
    }
    
    .bg-warning.bg-opacity-20 {
        background-color: rgba(255, 193, 7, 0.2) !important;
    }
    
    .bg-success.bg-opacity-20 {
        background-color: rgba(40, 167, 69, 0.2) !important;
    }

    /* Modal styling */
    .modal-lg {
        max-width: 700px;
    }
    
    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    
    .modal-footer {
        border-top: 1px solid #dee2e6;
    }
    
    /* Responsive adjustments */
    @media (max-width: 992px) {
        .table-responsive {
            overflow-x: auto;
        }
        
        .btn-group-sm > .btn {
            padding: 0.1rem 0.2rem;
            font-size: 0.65rem;
        }
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
        
        .btn-primary {
            flex: 1;
            justify-content: center;
            font-size: 0.8rem;
            padding: 5px 10px;
        }
        
        .table {
            font-size: 0.75rem !important;
        }
        
        .table th, .table td {
            padding: 4px 6px !important;
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
        
        .modal-lg {
            max-width: 95%;
            margin: 10px;
        }
        
        .modal-body .row.g-0 {
            flex-direction: column;
        }
        
        .modal-body .col-md-4,
        .modal-body .col-md-8 {
            width: 100% !important;
            border: none !important;
        }
        
        #fileIconContainer {
            height: 120px !important;
        }
        
        /* Hide some columns on mobile */
        .table td:nth-child(4), /* File Size */
        .table td:nth-child(8), /* Series */
        .table td:nth-child(9)  /* Status */ {
            display: none;
        }
        
        .table th:nth-child(4),
        .table th:nth-child(8),
        .table th:nth-child(9) {
            display: none;
        }
    }
    
    @media (max-width: 576px) {
        .page-title {
            font-size: 0.9rem;
        }
        
        .btn-primary {
            font-size: 0.75rem;
            padding: 4px 8px;
        }
        
        .table th, .table td {
            padding: 3px 4px !important;
        }
        
        .btn-group-sm > .btn {
            padding: 0.08rem 0.15rem;
            font-size: 0.6rem;
        }
        
        /* Hide more columns on very small screens */
        .table td:nth-child(6), /* Season */
        .table td:nth-child(10) /* Downloads */ {
            display: none;
        }
        
        .table th:nth-child(6),
        .table th:nth-child(10) {
            display: none;
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
                document.getElementById('detailFileType').textContent = fileType || 'ZIP Archive';
                document.getElementById('detailDescription').textContent = description || 'No description';
                document.getElementById('detailDownloadCount').textContent = downloadCount || '0';
                document.getElementById('detailCreatedAt').textContent = createdAt || 'N/A';
                document.getElementById('detailUpdatedAt').textContent = updatedAt || 'N/A';
                
                // Set competition
                document.getElementById('detailCompetition').textContent = competition || 'N/A';
                
                // Set season and series
                document.getElementById('detailSeason').textContent = season || 'N/A';
                document.getElementById('detailSeries').textContent = series || 'N/A';
                
                // Set status badge
                const statusElement = document.getElementById('detailStatus');
                let statusText, statusClass;
                
                if (status === 'published') {
                    statusText = 'Published';
                    statusClass = 'badge bg-success bg-opacity-20 text-success border border-success border-opacity-50 px-2 py-1';
                } else if (status === 'draft') {
                    statusText = 'Draft';
                    statusClass = 'badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-50 px-2 py-1';
                } else if (status === 'archived') {
                    statusText = 'Archived';
                    statusClass = 'badge bg-secondary bg-opacity-10 text-secondary px-2 py-1';
                } else {
                    statusText = 'Unknown';
                    statusClass = 'badge bg-light text-dark px-2 py-1';
                }
                
                statusElement.textContent = statusText;
                statusElement.className = statusClass;
                
                // Set file icon
                const fileIconContainer = document.getElementById('fileIconContainer');
                fileIconContainer.innerHTML = `
                    <div class="school-logo-placeholder school-logo-md">
                        <i class="fas fa-file-archive text-warning" style="font-size: 4rem;"></i>
                    </div>
                `;
                
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
                    html: `Are you sure you want to delete the gallery for <strong>"${schoolName}"</strong>?<br><small class="text-muted">This will permanently delete the ZIP file and all associated data.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        // Create form and submit immediately
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/gallery/photos/${galleryId}`;
                        form.style.display = 'none';
                        
                        // Add CSRF token
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = "{{ csrf_token() }}";
                        form.appendChild(csrfInput);
                        
                        // Add method spoofing for DELETE
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);
                        
                        // Append form to body and submit
                        document.body.appendChild(form);
                        form.submit();
                        
                        return false;
                    }
                });
            });
        });
        
        // Bulk Delete - PERBAIKAN ROUTE
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const bulkDownloadBtn = document.getElementById('bulkDownloadBtn');
        
        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', function() {
                const selectedItems = Array.from(document.querySelectorAll('.item-checkbox:checked'));
                
                if (selectedItems.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Items Selected',
                        text: 'Please select at least one item to delete',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
                
                Swal.fire({
                    title: 'Delete Selected Items?',
                    html: `Are you sure you want to delete <strong>${selectedItems.length}</strong> selected gallery item(s)?<br><small class="text-muted">This action cannot be undone.</small>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete them!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        // Use the hidden form approach
                        const bulkDeleteForm = document.getElementById('bulkDeleteForm');
                        const selectedIds = selectedItems.map(item => item.value);
                        
                        // Clear existing selected inputs
                        const existingInputs = bulkDeleteForm.querySelectorAll('input[name="selected[]"]');
                        existingInputs.forEach(input => input.remove());
                        
                        // Add new hidden inputs for selected IDs
                        selectedIds.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'selected[]';
                            input.value = id;
                            bulkDeleteForm.appendChild(input);
                        });
                        
                        // Submit the form
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
                        text: 'Please select at least one item to download',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }
                
                // Show confirmation
                Swal.fire({
                    title: 'Download Selected Items?',
                    html: `You are about to download <strong>${selectedItems.length}</strong> ZIP file(s).<br><small class="text-muted">Files will be combined into a single ZIP archive.</small>`,
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, download',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        // Use the hidden form approach
                        const bulkDownloadForm = document.getElementById('bulkDownloadForm');
                        const selectedIds = selectedItems.map(item => item.value);
                        
                        // Clear existing selected inputs
                        const existingInputs = bulkDownloadForm.querySelectorAll('input[name="selected[]"]');
                        existingInputs.forEach(input => input.remove());
                        
                        // Add new hidden inputs for selected IDs
                        selectedIds.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'selected[]';
                            input.value = id;
                            bulkDownloadForm.appendChild(input);
                        });
                        
                        // Submit the form
                        bulkDownloadForm.submit();
                        
                        return false;
                    }
                });
            });
        }
        
        // Auto-hide tooltips on mobile
        if (window.innerWidth < 768) {
            document.body.addEventListener('touchstart', function() {
                var tooltips = document.querySelectorAll('.tooltip');
                tooltips.forEach(tooltip => {
                    if (tooltip.parentNode) {
                        tooltip.parentNode.removeChild(tooltip);
                    }
                });
            });
        }
        
        // Auto-submit per_page select in filter form
        document.querySelectorAll('select[name="per_page"]').forEach(select => {
            select.addEventListener('change', function() {
                if (this.closest('form').id !== '') {
                    this.closest('form').submit();
                }
            });
        });
    });
</script>

@endsection