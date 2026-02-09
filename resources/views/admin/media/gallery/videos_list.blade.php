@extends('admin.layouts.app')
@section('title', 'Videos Management - Administrator')

@section('content')

{{-- Include SweetAlert2 --}}
@include('partials.sweetalert')

<div class="container mt-4">
    <!-- Page Header with Action Buttons -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="page-title">
                <i class="fas fa-video text-primary me-2"></i> Videos Management
            </h1>
            <p class="page-subtitle">Manage all video content and live streams</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="{{ route('admin.videos.create') }}" 
               class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-plus me-2"></i> Add Video
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.videos.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Search Title</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input name="search" 
                               type="text" 
                               value="{{ request('search') }}"
                               class="form-control border-start-0"
                               placeholder="Search video title...">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Year Filter</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-calendar text-muted"></i>
                        </span>
                        <select name="year" class="form-select border-start-0">
                            <option value="">All Years</option>
                            @php
                                $currentYear = date('Y');
                                for ($year = $currentYear; $year >= 2020; $year--) {
                                    echo '<option value="' . $year . '" ' . (request('year') == $year ? 'selected' : '') . '>' . $year . '</option>';
                                }
                            @endphp
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Type Filter</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-filter text-muted"></i>
                        </span>
                        <select name="type" class="form-select border-start-0">
                            <option value="">All Types</option>
                            <option value="video" @selected(request('type') == 'video')>Video</option>
                            <option value="live" @selected(request('type') == 'live')>Live Stream</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Status Filter</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-flag text-muted"></i>
                        </span>
                        <select name="status" class="form-select border-start-0">
                            <option value="">All Status</option>
                            <option value="draft" @selected(request('status') == 'draft')>Draft</option>
                            <option value="view" @selected(request('status') == 'view')>Published</option>
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
                        <a href="{{ route('admin.videos.index') }}" 
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

    <!-- Videos Table -->
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
                            <th class="px-2 py-1" style="min-width: 150px;">Title</th>
                            <th class="px-2 py-1" style="width: 100px;">Video Code</th>
                            <th class="px-2 py-1" style="width: 100px;">Type</th>
                            <th class="px-2 py-1" style="width: 90px;">Thumbnail</th>
                            <th class="px-2 py-1" style="min-width: 120px;">Description</th>
                            <th class="px-2 py-1" style="width: 70px;">Status</th>
                            <th class="px-2 py-1" style="width: 80px;">Created</th>
                            <th class="px-2 py-1 text-center" style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($videos as $index => $video)
                            <tr>
                                <td class="px-2 py-1">
                                    <input type="checkbox" 
                                           name="selected[]" 
                                           value="{{ $video->id }}" 
                                           class="form-check-input item-checkbox">
                                </td>
                                <td class="px-2 py-1 fw-medium text-muted">
                                    {{ $videos->firstItem() + $index }}
                                </td>
                                <td class="px-2 py-1">
                                    <div class="fw-semibold text-dark text-truncate" style="max-width: 150px;" 
                                         data-bs-toggle="tooltip" data-bs-title="{{ $video->title }}">
                                        <i class="fas {{ $video->type == 'live' ? 'fa-broadcast-tower text-danger' : 'fa-video text-primary' }} me-1"></i>
                                        {{ Str::limit($video->title, 30) }}
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1"
                                          data-bs-toggle="tooltip" data-bs-title="Video Code">
                                        <i class="fas fa-hashtag me-1" style="font-size: 0.7rem;"></i>
                                        <span class="small">{{ Str::limit($video->video_code, 10) }}</span>
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    @php
                                        $typeColors = [
                                            'video' => 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25',
                                            'live' => 'bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25'
                                        ];
                                        $typeIcon = [
                                            'video' => 'fa-video',
                                            'live' => 'fa-broadcast-tower'
                                        ];
                                        $typeClass = $typeColors[$video->type] ?? 'bg-secondary bg-opacity-10 text-secondary';
                                        $typeIconClass = $typeIcon[$video->type] ?? 'fa-video';
                                    @endphp
                                    <span class="badge {{ $typeClass }} px-2 py-1 d-flex align-items-center justify-content-center"
                                          data-bs-toggle="tooltip" 
                                          data-bs-title="{{ ucfirst($video->type) }}">
                                        <i class="fas {{ $typeIconClass }} me-1" style="font-size: 0.7rem;"></i>
                                        <span class="small">{{ ucfirst($video->type) }}</span>
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    @if($video->thumbnail)
                                        <div class="bg-light rounded overflow-hidden" style="width: 80px; height: 45px;">
                                            <img src="{{ asset($video->thumbnail) }}" 
                                                 alt="{{ $video->title }}"
                                                 class="w-100 h-100 object-fit-cover"
                                                 style="object-fit: cover;"
                                                 data-bs-toggle="tooltip" 
                                                 data-bs-title="View Thumbnail">
                                        </div>
                                    @else
                                        <span class="text-muted small" data-bs-toggle="tooltip" data-bs-title="No thumbnail">
                                            <i class="fas fa-image"></i> -
                                        </span>
                                    @endif
                                </td>
                                <td class="px-2 py-1">
                                    <div class="text-truncate" 
                                         style="max-width: 120px;"
                                         data-bs-toggle="tooltip" 
                                         data-bs-title="{{ $video->description }}">
                                        {{ Str::limit($video->description, 40) }}
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    @php
                                        if ($video->status === 'draft') {
                                            $badgeClass = 'bg-warning bg-opacity-20 text-warning border border-warning border-opacity-50';
                                            $badgeIcon = 'fas fa-edit';
                                            $statusText = 'Draft';
                                        } elseif ($video->status === 'view') {
                                            $badgeClass = 'bg-success bg-opacity-20 text-success border border-success border-opacity-50';
                                            $badgeIcon = 'fas fa-check-circle';
                                            $statusText = 'Published';
                                        } else {
                                            $badgeClass = 'bg-secondary bg-opacity-10 text-secondary';
                                            $badgeIcon = 'fas fa-archive';
                                            $statusText = ucfirst($video->status);
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-2 py-1 d-flex align-items-center justify-content-center"
                                          data-bs-toggle="tooltip" 
                                          data-bs-title="{{ $statusText }}">
                                        <i class="{{ $badgeIcon }} me-1" style="font-size: 0.7rem;"></i>
                                        <span class="small">{{ $statusText }}</span>
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    <div class="small">
                                        <div class="text-dark" data-bs-toggle="tooltip" 
                                             data-bs-title="{{ $video->created_at->format('Y-m-d H:i') }}">
                                            {{ $video->created_at->format('M d') }}
                                        </div>
                                        <div class="text-muted" style="font-size: 0.7rem;">
                                            {{ $video->created_at->format('Y') }}
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
                                                data-video-id="{{ $video->id }}"
                                                data-video-title="{{ $video->title }}"
                                                data-video-code="{{ $video->video_code }}"
                                                data-video-type="{{ $video->type }}"
                                                data-video-status="{{ $video->status }}"
                                                data-video-description="{{ $video->description }}"
                                                data-video-thumbnail="{{ asset($video->thumbnail) }}"
                                                data-video-youtube-link="{{ $video->youtube_link }}"
                                                data-video-created-at="{{ $video->created_at->format('d M Y H:i') }}"
                                                data-video-updated-at="{{ $video->updated_at->format('d M Y H:i') }}">
                                            <i class="fas fa-eye" style="font-size: 0.7rem;"></i>
                                        </button>
                                        
                                        <!-- Edit Button -->
                                        <a href="{{ route('admin.videos.edit', $video->id) }}" 
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
                                                data-video-id="{{ $video->id }}"
                                                data-video-title="{{ $video->title }}">
                                            <i class="fas fa-trash" style="font-size: 0.7rem;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-3 text-center">
                                    <div class="py-3">
                                        <i class="fas fa-video fa-lg text-muted mb-2"></i>
                                        <h6 class="text-muted">No Videos Found</h6>
                                        <p class="text-muted small mb-2">Start by adding your first video content</p>
                                        <a href="{{ route('admin.videos.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i> Add First Video
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
        @if($videos->hasPages() || $videos->total() > 10)
            <div class="card-footer bg-white border-top px-3 py-2">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-2 mb-md-0">
                        <p class="small text-muted mb-0">
                            Showing <span class="fw-semibold">{{ $videos->firstItem() ?: 0 }}</span> to 
                            <span class="fw-semibold">{{ $videos->lastItem() ?: 0 }}</span> of 
                            <span class="fw-semibold">{{ $videos->total() }}</span> results
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Per Page Selector -->
                        <div class="d-none d-md-block">
                            <form method="GET" action="{{ route('admin.videos.index') }}" class="d-flex align-items-center">
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if(request('type'))
                                    <input type="hidden" name="type" value="{{ request('type') }}">
                                @endif
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                @if(request('year'))
                                    <input type="hidden" name="year" value="{{ request('year') }}">
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
                        @if($videos->hasPages())
                            <div>
                                {{ $videos->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    {{-- Bulk Actions --}}
    @if($videos->count() > 0)
    <div class="mt-3 d-flex justify-content-between align-items-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="bulkSelectAll">
            <label class="form-check-label small text-muted" for="bulkSelectAll">
                Select all items ({{ $videos->total() }} total)
            </label>
        </div>
        <div class="d-flex gap-2">
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
                <h6 class="modal-title fw-semibold" id="detailsModalLabel">Video Details</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Thumbnail Section -->
                    <div class="col-md-4 border-end">
                        <div class="text-center py-4">
                            <div class="d-flex justify-content-center align-items-center mb-3" style="height: 180px;">
                                <div id="videoThumbnailContainer" class="d-flex justify-content-center align-items-center w-100">
                                    <!-- Thumbnail akan diisi oleh JavaScript -->
                                </div>
                            </div>
                            <h6 class="fw-bold mb-1" id="videoTitle"></h6>
                            <div class="badge bg-primary bg-opacity-10 text-primary py-1 px-3 mt-2">
                                Video Preview
                            </div>
                        </div>
                    </div>
                    
                    <!-- Video Info -->
                    <div class="col-md-8">
                        <div class="py-4 px-3">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="d-flex flex-column mb-2">
                                        <span class="text-muted small mb-1">Type:</span>
                                        <span class="fw-semibold" id="detailType">-</span>
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
                                <span class="text-muted small mb-1">Video Code:</span>
                                <span class="fw-semibold text-monospace" id="detailVideoCode">-</span>
                            </div>
                            
                            <div class="d-flex flex-column mb-2">
                                <span class="text-muted small mb-1">YouTube Link:</span>
                                <a href="#" id="detailYoutubeLink" target="_blank" class="text-decoration-none">
                                    <i class="fab fa-youtube text-danger me-1"></i>
                                    <span id="detailYoutubeLinkText">-</span>
                                </a>
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
                    <i class="fas fa-edit me-1"></i> Edit Video
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Hidden Bulk Delete Form --}}
<form id="bulkDeleteForm" method="POST" action="{{ route('admin.videos.bulk-destroy') }}" style="display: none;">
    @csrf
    @method('DELETE')
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

    .btn-outline-primary, .btn-outline-danger, .btn-outline-info {
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

    /* Warna type dan status */
    .bg-primary.bg-opacity-10 {
        background-color: rgba(52, 152, 219, 0.1) !important;
    }
    
    .bg-danger.bg-opacity-10 {
        background-color: rgba(231, 76, 60, 0.1) !important;
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
        
        #videoThumbnailContainer {
            height: 120px !important;
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
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Select All functionality (sama seperti pub_result)
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
        
        // Update checkboxes when individual items are checked (sama seperti pub_result)
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
        
        // Handle View Details button click (sama seperti pub_result)
        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                // Get data from button attributes
                const videoId = this.getAttribute('data-video-id');
                const videoTitle = this.getAttribute('data-video-title');
                const videoCode = this.getAttribute('data-video-code');
                const videoType = this.getAttribute('data-video-type');
                const videoStatus = this.getAttribute('data-video-status');
                const videoDescription = this.getAttribute('data-video-description');
                const videoThumbnail = this.getAttribute('data-video-thumbnail');
                const youtubeLink = this.getAttribute('data-video-youtube-link');
                const createdAt = this.getAttribute('data-video-created-at');
                const updatedAt = this.getAttribute('data-video-updated-at');
                
                // Set modal content
                document.getElementById('videoTitle').textContent = videoTitle;
                document.getElementById('detailVideoCode').textContent = videoCode || 'N/A';
                document.getElementById('detailDescription').textContent = videoDescription || 'No description';
                
                // Set type
                const typeElement = document.getElementById('detailType');
                typeElement.textContent = videoType ? videoType.charAt(0).toUpperCase() + videoType.slice(1) : 'Unknown';
                
                // Set status badge
                const statusElement = document.getElementById('detailStatus');
                const statusText = videoStatus === 'view' ? 'Published' : 
                                  (videoStatus === 'draft' ? 'Draft' : 'Unknown');
                statusElement.textContent = statusText;
                
                // Set status badge class
                if (videoStatus === 'view') {
                    statusElement.className = 'badge bg-success bg-opacity-20 text-success border border-success border-opacity-50 px-2 py-1';
                } else if (videoStatus === 'draft') {
                    statusElement.className = 'badge bg-warning bg-opacity-20 text-warning border border-warning border-opacity-50 px-2 py-1';
                } else {
                    statusElement.className = 'badge bg-secondary bg-opacity-10 text-secondary px-2 py-1';
                }
                
                // Set thumbnail
                const thumbnailContainer = document.getElementById('videoThumbnailContainer');
                if (videoThumbnail && videoThumbnail !== 'http://localhost' && !videoThumbnail.includes('/null')) {
                    thumbnailContainer.innerHTML = `
                        <img src="${videoThumbnail}" 
                             alt="${videoTitle}" 
                             class="img-fluid rounded border"
                             style="max-height: 160px; object-fit: cover;"
                             onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjU2IiBoZWlnaHQ9IjE0NCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMjU2IiBoZWlnaHQ9IjE0NCIgZmlsbD0iI2YxZjVmOSIvPjx0ZXh0IHg9IjUwJSIgeT0iNTAlIiBkb21pbmFudC1iYXNlbGluZT0iY2VudHJhbCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZm9udC1mYW1pbHk9IkFyaWFsIiBmb250LXNpemU9IjE0IiBmaWxsPSIjOTk5Ij5UaHVtYm5haWwgTm90IEZvdW5kPC90ZXh0Pjwvc3ZnPg=='">
                    `;
                } else {
                    thumbnailContainer.innerHTML = `
                        <div class="school-logo-placeholder school-logo-md">
                            <i class="fas fa-video text-secondary" style="font-size: 3rem;"></i>
                        </div>
                    `;
                }
                
                // Set YouTube link
                const youtubeLinkElement = document.getElementById('detailYoutubeLink');
                const youtubeLinkTextElement = document.getElementById('detailYoutubeLinkText');
                if (youtubeLink && youtubeLink !== 'http://localhost') {
                    youtubeLinkElement.href = youtubeLink;
                    youtubeLinkTextElement.textContent = 'Watch on YouTube';
                } else {
                    youtubeLinkElement.href = '#';
                    youtubeLinkElement.onclick = (e) => e.preventDefault();
                    youtubeLinkTextElement.textContent = 'No YouTube link';
                    youtubeLinkElement.classList.add('text-muted');
                }
                
                // Set dates
                document.getElementById('detailCreatedAt').textContent = createdAt || 'N/A';
                document.getElementById('detailUpdatedAt').textContent = updatedAt || 'N/A';
                
                // Set edit link
                document.getElementById('editLink').href = `/admin/videos/${videoId}/edit`;
                
                // Show modal
                const detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
                detailsModal.show();
            });
        });
        
        // Handle delete buttons (sama seperti pub_result)
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const videoId = this.getAttribute('data-video-id');
                const videoTitle = this.getAttribute('data-video-title');
                
                Swal.fire({
                    title: 'Delete Video?',
                    html: `Are you sure you want to delete <strong>"${videoTitle}"</strong>?`,
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
                        form.action = `/admin/videos/${videoId}`;
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
                        
                        return false; // Prevent SweetAlert from closing automatically
                    }
                });
            });
        });
        
        // Bulk Delete (sama seperti pub_result)
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        
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
                    html: `Are you sure you want to delete <strong>${selectedItems.length}</strong> selected video(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete them!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    showLoaderOnConfirm: true,
                    preConfirm: () => {
                        const bulkDeleteForm = document.getElementById('bulkDeleteForm');
                        const selectedIds = selectedItems.map(item => item.value);
                        
                        bulkDeleteForm.querySelectorAll('input[name="selected[]"]').forEach(input => {
                            input.remove();
                        });
                        
                        selectedIds.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'selected[]';
                            input.value = id;
                            bulkDeleteForm.appendChild(input);
                        });
                        
                        bulkDeleteForm.submit();
                        
                        return false; // Prevent SweetAlert from closing automatically
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