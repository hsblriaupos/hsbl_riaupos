@extends('admin.layouts.app')
@section('title', 'Results Management - Administrator')

@section('content')

@php $activeTab = 'result'; @endphp
@include('partials.tabs-pub', compact('activeTab'))

{{-- Include SweetAlert2 --}}
@include('partials.sweetalert')

<div class="container mt-4">
    <!-- Page Header with Action Buttons -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="page-title">
                <i class="fas fa-trophy text-primary me-2"></i> Results Management
            </h1>
            <p class="page-subtitle">Manage match results and scores</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="{{ route('admin.pub_result.create') }}" 
               class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-plus me-2"></i> Add Result
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pub_result.index') }}" class="row g-3 align-items-end">
                <div class="col-md-2 col-sm-6 col-12">
                    <label class="form-label small text-muted mb-1">Search Team</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input name="search" 
                               type="text" 
                               value="{{ request('search') }}"
                               class="form-control border-start-0"
                               placeholder="Search team...">
                    </div>
                </div>
                
                <div class="col-md-2 col-sm-6 col-12">
                    <label class="form-label small text-muted mb-1">Season</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-calendar-alt text-muted"></i>
                        </span>
                        <select name="season" class="form-select border-start-0">
                            <option value="">All Seasons</option>
                            @foreach($seasons as $season)
                                <option value="{{ $season }}" @selected(request('season')==$season)>
                                    {{ $season }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2 col-sm-6 col-12">
                    <label class="form-label small text-muted mb-1">Competition</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-trophy text-muted"></i>
                        </span>
                        <select name="competition" class="form-select border-start-0">
                            <option value="">All Competitions</option>
                            @foreach($competitions as $comp)
                                <option value="{{ $comp }}" @selected(request('competition')==$comp)>
                                    {{ $comp }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2 col-sm-6 col-12">
                    <label class="form-label small text-muted mb-1">Series</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-map-marker-alt text-muted"></i>
                        </span>
                        <select name="series" class="form-select border-start-0">
                            <option value="">All Series</option>
                            @foreach($seriesList as $series)
                                <option value="{{ $series }}" @selected(request('series')==$series)>
                                    {{ $series }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2 col-sm-6 col-12">
                    <label class="form-label small text-muted mb-1">Status</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-info-circle text-muted"></i>
                        </span>
                        <select name="status" class="form-select border-start-0">
                            <option value="">All Status</option>
                            <option value="draft" @selected(request('status')=='draft')>Draft</option>
                            <option value="publish" @selected(request('status')=='publish')>Published</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-1 col-sm-6 col-12">
                    <label class="form-label small text-muted mb-1">Show</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-list text-muted"></i>
                        </span>
                        <select name="per_page" class="form-select border-start-0">
                            <option value="10" @selected(request('per_page', 10)==10)>10</option>
                            <option value="25" @selected(request('per_page')==25)>25</option>
                            <option value="50" @selected(request('per_page')==50)>50</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-1 col-sm-6 col-12">
                    <button type="submit" class="btn btn-dark btn-sm w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
                
                <div class="col-md-1 col-sm-6 col-12">
                    <a href="{{ route('admin.pub_result.index') }}" 
                       class="btn btn-outline-secondary btn-sm w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-redo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.75rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="px-1 py-1" style="width: 25px;">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th class="px-1 py-1" style="width: 35px;">No</th>
                            <th class="px-1 py-1" style="width: 65px;">Date</th>
                            <th class="px-1 py-1" style="min-width: 70px;">Team 1</th>
                            <th class="px-1 py-1 text-center" style="width: 45px;">Score</th>
                            <th class="px-1 py-1" style="min-width: 70px;">Team 2</th>
                            <th class="px-1 py-1 text-center" style="width: 70px;">Season</th>
                            <th class="px-1 py-1" style="width: 70px;">Competition</th>
                            <th class="px-1 py-1 text-center" style="width: 60px;">Series</th>
                            <th class="px-1 py-1 text-center" style="width: 35px;">Scoresheet</th>
                            <th class="px-1 py-1 text-center" style="width: 50px;">Status</th>
                            <th class="px-1 py-1 text-center" style="width: 85px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $index => $result)
                            <tr>
                                <td class="px-1 py-1">
                                    <input type="checkbox" 
                                           name="selected[]" 
                                           value="{{ $result->id }}" 
                                           class="form-check-input item-checkbox">
                                </td>
                                <td class="px-1 py-1 fw-medium text-muted">
                                    {{ $results->firstItem() + $index }}
                                </td>
                                <td class="px-1 py-1">
                                    <div class="small">
                                        {{ \Carbon\Carbon::parse($result->match_date)->format('d M') }}
                                    </div>
                                </td>
                                <td class="px-1 py-1">
                                    <div class="fw-semibold text-dark text-truncate" style="max-width: 70px;" 
                                         data-bs-toggle="tooltip" data-bs-title="{{ $result->team1->school_name ?? 'N/A' }}">
                                        {{ $result->team1->school_name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-1 py-1 text-center">
                                    <span class="badge bg-dark text-white px-1 py-0" style="font-size: 0.7rem;">
                                        {{ $result->score_1 ?? '0' }} - {{ $result->score_2 ?? '0' }}
                                    </span>
                                </td>
                                <td class="px-1 py-1">
                                    <div class="fw-semibold text-dark text-truncate" style="max-width: 70px;" 
                                         data-bs-toggle="tooltip" data-bs-title="{{ $result->team2->school_name ?? 'N/A' }}">
                                        {{ $result->team2->school_name ?? 'N/A' }}
                                    </div>
                                </td>
                                <td class="px-1 py-1 text-center">
                                    <span class="badge bg-teal bg-opacity-10 text-teal border border-teal border-opacity-25 px-0 py-0" 
                                          data-bs-toggle="tooltip" data-bs-title="{{ $result->season ?? 'N/A' }}"
                                          style="font-size: 0.65rem;">
                                        {{ $result->season ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-1 py-1">
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-0 py-0" 
                                          data-bs-toggle="tooltip" data-bs-title="{{ $result->competition ?? 'N/A' }}"
                                          style="font-size: 0.65rem;">
                                        {{ $result->competition ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-1 py-1 text-center">
                                    <span class="badge bg-purple bg-opacity-10 text-purple border border-purple border-opacity-25 px-0 py-0" 
                                          data-bs-toggle="tooltip" data-bs-title="{{ $result->series ?? 'N/A' }}"
                                          style="font-size: 0.65rem;">
                                        {{ $result->series ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-1 py-1 text-center">
                                    @if($result->scoresheet)
                                        <a href="{{ asset($result->scoresheet) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-success border-1"
                                           data-bs-toggle="tooltip" 
                                           data-bs-title="View Scoresheet (Excel)">
                                            <i class="fas fa-file-excel" style="font-size: 0.65rem;"></i>
                                        </a>
                                    @else
                                        <span class="text-muted" style="font-size: 0.65rem;">-</span>
                                    @endif
                                </td>
                                <td class="px-1 py-1 text-center">
                                    @php
                                        if ($result->status === 'draft') {
                                            $badgeClass = 'bg-warning bg-opacity-20 text-warning border border-warning border-opacity-50';
                                            $badgeIcon = 'fas fa-edit';
                                            $statusText = 'Draft';
                                        } elseif ($result->status === 'publish') {
                                            $badgeClass = 'bg-success bg-opacity-20 text-success border border-success border-opacity-50';
                                            $badgeIcon = 'fas fa-check-circle';
                                            $statusText = 'Published';
                                        } else {
                                            $badgeClass = 'bg-secondary bg-opacity-10 text-secondary';
                                            $badgeIcon = 'fas fa-archive';
                                            $statusText = ucfirst($result->status);
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }} px-1 py-0 d-inline-flex align-items-center justify-content-center"
                                          data-bs-toggle="tooltip" 
                                          data-bs-title="{{ $statusText }}"
                                          style="font-size: 0.65rem;">
                                        <i class="{{ $badgeIcon }}" style="font-size: 0.6rem;"></i>
                                    </span>
                                </td>
                                <td class="px-1 py-1 text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- View Details (Eye Icon) -->
                                        <button type="button" 
                                                class="btn btn-outline-info border-1 view-details-btn"
                                                data-bs-toggle="tooltip" 
                                                data-bs-title="View Type & Phase"
                                                data-competition-type="{{ $result->competition_type ?? 'N/A' }}"
                                                data-phase="{{ $result->phase ?? 'N/A' }}">
                                            <i class="fas fa-eye" style="font-size: 0.65rem;"></i>
                                        </button>
                                        
                                        @if ($result->status !== 'done')
                                            <a href="{{ route('admin.pub_result.edit', $result->id) }}" 
                                               class="btn btn-outline-primary border-1"
                                               data-bs-toggle="tooltip" 
                                               data-bs-title="Edit">
                                                <i class="fas fa-edit" style="font-size: 0.65rem;"></i>
                                            </a>
                                        @else
                                            <button type="button" 
                                                    class="btn btn-outline-secondary border-1"
                                                    disabled
                                                    data-bs-toggle="tooltip" 
                                                    data-bs-title="Cannot edit done result">
                                                <i class="fas fa-edit" style="font-size: 0.65rem;"></i>
                                            </button>
                                        @endif
                                        
                                        <!-- Delete Form -->
                                        <form action="{{ route('admin.pub_result.destroy', $result->id) }}" 
                                              method="POST" 
                                              class="d-inline delete-form"
                                              data-title="{{ $result->team1->school_name ?? 'Team 1' }} vs {{ $result->team2->school_name ?? 'Team 2' }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-outline-danger border-1"
                                                    data-bs-toggle="tooltip" 
                                                    data-bs-title="Delete">
                                                <i class="fas fa-trash" style="font-size: 0.65rem;"></i>
                                            </button>
                                        </form>
                                        
                                        @if ($result->status === 'draft')
                                            <!-- Publish Form -->
                                            <form action="{{ route('admin.pub_result.publish', $result->id) }}" 
                                                  method="POST" 
                                                  class="d-inline publish-form"
                                                  data-title="{{ $result->team1->school_name ?? 'Team 1' }} vs {{ $result->team2->school_name ?? 'Team 2' }}">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-outline-success border-1"
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-title="Publish">
                                                    <i class="fas fa-paper-plane" style="font-size: 0.65rem;"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="px-4 py-3 text-center">
                                    <div class="py-3">
                                        <i class="fas fa-trophy fa-lg text-muted mb-2"></i>
                                        <h6 class="text-muted">No Results Found</h6>
                                        <p class="text-muted small mb-2">Start by adding your first match result</p>
                                        <a href="{{ route('admin.pub_result.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i> Add First Result
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
        @if($results->hasPages() || $results->total() > 10)
            <div class="card-footer bg-white border-top px-3 py-2">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-2 mb-md-0">
                        <p class="small text-muted mb-0">
                            Showing <span class="fw-semibold">{{ $results->firstItem() ?: 0 }}</span> to 
                            <span class="fw-semibold">{{ $results->lastItem() ?: 0 }}</span> of 
                            <span class="fw-semibold">{{ $results->total() }}</span> results
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Per Page Selector -->
                        <div class="d-none d-md-block">
                            <form method="GET" action="{{ route('admin.pub_result.index') }}" class="d-flex align-items-center">
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if(request('season'))
                                    <input type="hidden" name="season" value="{{ request('season') }}">
                                @endif
                                @if(request('competition'))
                                    <input type="hidden" name="competition" value="{{ request('competition') }}">
                                @endif
                                @if(request('series'))
                                    <input type="hidden" name="series" value="{{ request('series') }}">
                                @endif
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                <span class="small text-muted me-2">Show:</span>
                                <select name="per_page" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                                    <option value="10" @selected(request('per_page', 10)==10)>10</option>
                                    <option value="25" @selected(request('per_page')==25)>25</option>
                                    <option value="50" @selected(request('per_page')==50)>50</option>
                                </select>
                            </form>
                        </div>
                        
                        <!-- Pagination -->
                        @if($results->hasPages())
                            <div>
                                {{ $results->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
    
    {{-- Bulk Actions --}}
    <div class="mt-3 d-flex justify-content-between align-items-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="bulkSelectAll">
            <label class="form-check-label small text-muted" for="bulkSelectAll">
                Select all items ({{ $results->total() }} total)
            </label>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn">
                <i class="fas fa-trash me-1"></i> Delete Selected
            </button>
        </div>
    </div>
</div>

{{-- Modal for Viewing Details --}}
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title" id="detailsModalLabel">Result Details</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <span class="small text-muted">Competition Type:</span>
                        <span class="small fw-semibold" id="detailCompetitionType"></span>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                        <span class="small text-muted">Phase:</span>
                        <span class="small fw-semibold" id="detailPhase"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Hidden Bulk Delete Form --}}
<form id="bulkDeleteForm" method="POST" action="{{ route('admin.pub_result.bulk-destroy') }}" style="display: none;">
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
        font-size: 0.75rem !important;
        margin-bottom: 0;
    }

    .table th {
        font-weight: 600;
        font-size: 0.7rem;
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
        font-size: 0.75rem !important;
    }

    .table-hover tbody tr:hover {
        background-color: #f8fafc;
    }

    .btn-group-sm > .btn {
        padding: 0.1rem 0.2rem;
        border-radius: 0.15rem;
        font-size: 0.65rem;
    }

    .btn-outline-primary, .btn-outline-danger, .btn-outline-success, .btn-outline-info {
        border-width: 1px;
    }

    .badge {
        padding: 0.15em 0.4em;
        font-weight: 500;
        font-size: 0.65em;
        border: 1px solid transparent;
    }

    /* Warna custom untuk badge season */
    .bg-teal {
        background-color: #20c997 !important;
    }
    
    .bg-teal.bg-opacity-10 {
        background-color: rgba(32, 201, 151, 0.1) !important;
    }
    
    .text-teal {
        color: #20c997 !important;
    }
    
    .border-teal {
        border-color: #20c997 !important;
    }
    
    .border-teal.border-opacity-25 {
        border-color: rgba(32, 201, 151, 0.25) !important;
    }

    /* Warna custom untuk badge series */
    .bg-purple {
        background-color: #6f42c1 !important;
    }
    
    .bg-purple.bg-opacity-10 {
        background-color: rgba(111, 66, 193, 0.1) !important;
    }
    
    .text-purple {
        color: #6f42c1 !important;
    }
    
    .border-purple {
        border-color: #6f42c1 !important;
    }
    
    .border-purple.border-opacity-25 {
        border-color: rgba(111, 66, 193, 0.25) !important;
    }

    /* Warna status yang diperbaiki */
    .bg-warning.bg-opacity-20 {
        background-color: rgba(255, 193, 7, 0.2) !important;
    }
    
    .bg-success.bg-opacity-20 {
        background-color: rgba(40, 167, 69, 0.2) !important;
    }
    
    .bg-primary.bg-opacity-20 {
        background-color: rgba(13, 110, 253, 0.2) !important;
    }

    /* Modal styling */
    .modal-sm {
        max-width: 250px;
    }
    
    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    
    .modal-footer {
        border-top: 1px solid #dee2e6;
    }
    
    .list-group-item {
        border-left: 0;
        border-right: 0;
    }
    
    .list-group-item:first-child {
        border-top: 0;
    }
    
    .list-group-item:last-child {
        border-bottom: 0;
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
        
        .btn-primary, .btn-outline-secondary {
            flex: 1;
            justify-content: center;
            font-size: 0.8rem;
            padding: 5px 10px;
        }
        
        .table {
            font-size: 0.7rem !important;
        }
        
        .btn-group-sm > .btn {
            padding: 0.08rem 0.15rem;
            font-size: 0.6rem;
        }
        
        .page-title {
            font-size: 1rem;
        }
        
        .card-footer .d-flex {
            flex-direction: column !important;
            gap: 10px !important;
        }
        
        .card-footer .d-flex.align-items-center {
            align-items: flex-start !important;
        }
        
        .table th, .table td {
            padding: 3px 4px !important;
        }
        
        .table-responsive {
            font-size: 0.7rem;
        }
        
        /* Hide some columns on mobile */
        .table td:nth-child(9), /* Series */
        .table td:nth-child(8), /* Competition */
        .table th:nth-child(9),
        .table th:nth-child(8) {
            display: none;
        }
    }
    
    @media (max-width: 576px) {
        .page-title {
            font-size: 0.9rem;
        }
        
        .btn-primary, .btn-outline-secondary {
            font-size: 0.75rem;
            padding: 4px 8px;
        }
        
        .table th, .table td {
            padding: 2px 3px !important;
        }
        
        .form-select-sm {
            padding: 0.125rem 0.25rem;
            font-size: 0.75rem;
        }
        
        .btn-group-sm > .btn {
            padding: 0.06rem 0.12rem;
            font-size: 0.55rem;
        }
        
        .badge {
            padding: 0.1em 0.3em;
            font-size: 0.6em;
        }
        
        /* Hide more columns on very small screens */
        .table td:nth-child(7), /* Season */
        .table th:nth-child(7) {
            display: none;
        }
    }
</style>

<script>
    // Initialize Bootstrap tooltips
    document.addEventListener('DOMContentLoaded', function() {
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
            button.addEventListener('click', function() {
                const competitionType = this.getAttribute('data-competition-type');
                const phase = this.getAttribute('data-phase');
                
                // Set modal content
                document.getElementById('detailCompetitionType').textContent = competitionType;
                document.getElementById('detailPhase').textContent = phase;
                
                // Show modal
                const detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
                detailsModal.show();
            });
        });
        
        // Handle delete forms - SweetAlert sederhana
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const title = form.getAttribute('data-title');
                
                Swal.fire({
                    title: 'Delete Result?',
                    html: `Are you sure you want to delete <strong>"${title}"</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
        
        // Handle publish forms
        document.querySelectorAll('.publish-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const title = form.getAttribute('data-title');
                
                Swal.fire({
                    title: 'Publish Result?',
                    html: `Are you sure you want to publish <strong>"${title}"</strong>?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, publish it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
        
        // Bulk Delete dengan SweetAlert
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
                    html: `Are you sure you want to delete <strong>${selectedItems.length}</strong> selected result(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete them!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
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