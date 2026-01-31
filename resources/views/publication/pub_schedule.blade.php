@extends('admin.layouts.app')
@section('title', 'Schedules Management - Administrator')

@section('content')

@php $activeTab = 'schedule'; @endphp
@include('partials.tabs-pub', compact('activeTab'))

{{-- Include SweetAlert2 --}}
@include('partials.sweetalert')

<div class="container mt-4">
    <!-- Page Header with Action Buttons -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="page-title">
                <i class="fas fa-calendar-alt text-primary me-2"></i> Schedules Management
            </h1>
            <p class="page-subtitle">Manage match schedules and timetables</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="{{ route('admin.pub_schedule.create') }}" 
               class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-plus me-2"></i> Add Schedule
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pub_schedule.index') }}" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Search Title</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input name="search" 
                               type="text" 
                               value="{{ request('search') }}"
                               class="form-control border-start-0"
                               placeholder="Search schedule title...">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Series Filter</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-filter text-muted"></i>
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
                
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Year Filter</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-calendar-alt text-muted"></i>
                        </span>
                        <select name="year" class="form-select border-start-0">
                            <option value="">All Years</option>
                            @php
                                // Mendapatkan tahun sekarang
                                $currentYear = date('Y');
                                // Jika ada data schedule, ambil tahun minimum dari created_at
                                $minYear = $schedules->isNotEmpty() ? $schedules->min('created_at')->format('Y') : $currentYear;
                                // Buat array tahun dari minYear sampai currentYear
                                $years = range($minYear, $currentYear);
                            @endphp
                            @foreach(array_reverse($years) as $year)
                                <option value="{{ $year }}" @selected(request('year')==$year)>
                                    {{ $year }}
                                </option>
                            @endforeach
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
                            <option value="draft" @selected(request('status')=='draft')>Draft</option>
                            <option value="publish" @selected(request('status')=='publish')>Published</option>
                            <option value="done" @selected(request('status')=='done')>Done</option>
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
                            <option value="10" @selected(request('per_page', 10)==10)>10</option>
                            <option value="25" @selected(request('per_page')==25)>25</option>
                            <option value="50" @selected(request('per_page')==50)>50</option>
                            <option value="100" @selected(request('per_page')==100)>100</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-1">
                    <button type="submit" class="btn btn-dark btn-sm w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
                
                <div class="col-md-1">
                    <a href="{{ route('admin.pub_schedule.index') }}" 
                       class="btn btn-outline-secondary btn-sm w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-redo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Schedules Table -->
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
                            <th class="px-2 py-1" style="width: 80px;">Schedule Date</th>
                            <th class="px-2 py-1" style="min-width: 100px;">Title</th>
                            <th class="px-2 py-1" style="min-width: 120px;">Series</th>
                            <th class="px-2 py-1" style="width: 60px;">Image</th>
                            <th class="px-2 py-1" style="width: 100px;">Caption</th>
                            <th class="px-2 py-1" style="width: 80px;">Status</th>
                            <th class="px-2 py-1" style="width: 80px;">Created</th>
                            <th class="px-2 py-1 text-center" style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $index => $schedule)
                            <tr>
                                <td class="px-2 py-1">
                                    <input type="checkbox" 
                                           name="selected[]" 
                                           value="{{ $schedule->id }}" 
                                           class="form-check-input item-checkbox">
                                </td>
                                <td class="px-2 py-1 fw-medium text-muted">
                                    {{ $schedules->firstItem() + $index }}
                                </td>
                                <td class="px-2 py-1">
                                    <div class="small">
                                        {{ \Carbon\Carbon::parse($schedule->upload_date)->format('d/m/Y') }}
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    <div class="fw-semibold text-dark text-truncate" style="max-width: 100px;" 
                                         data-bs-toggle="tooltip" data-bs-title="{{ $schedule->main_title }}">
                                        {{ Str::limit($schedule->main_title, 15) }}
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-1 py-0" 
                                          data-bs-toggle="tooltip" data-bs-title="{{ $schedule->series_name }}">
                                        {{ Str::limit($schedule->series_name, 20) }}
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    @if($schedule->layout_image)
                                        @php
                                            $imagePath = $schedule->layout_image;
                                            $fullPath = asset($imagePath);
                                        @endphp
                                        <div class="bg-light rounded overflow-hidden" style="width: 40px; height: 30px; cursor: pointer;"
                                             onclick="showImage('{{ $fullPath }}')">
                                            <img src="{{ $fullPath }}" 
                                                 alt="{{ $schedule->main_title }}"
                                                 class="w-100 h-100 object-fit-cover"
                                                 style="object-fit: cover;"
                                                 onerror="this.onerror=null; this.src='{{ asset('img/no-image.png') }}';"
                                                 data-bs-toggle="tooltip" 
                                                 data-bs-title="View Image">
                                        </div>
                                    @else
                                        <span class="text-muted small" data-bs-toggle="tooltip" data-bs-title="No image">-</span>
                                    @endif
                                </td>
                                <td class="px-2 py-1">
                                    <div class="text-truncate" style="max-width: 100px;"
                                         data-bs-toggle="tooltip" 
                                         data-bs-title="{{ $schedule->caption ?: 'No caption' }}">
                                        {{ $schedule->caption ? Str::limit($schedule->caption, 30) : '-' }}
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    @php
                                        if ($schedule->status === 'draft') {
                                            $badgeClass = 'bg-warning bg-opacity-20 text-warning border border-warning border-opacity-50';
                                            $badgeIcon = 'fas fa-edit';
                                            $statusText = 'Draft';
                                        } elseif ($schedule->status === 'publish') {
                                            $badgeClass = 'bg-success bg-opacity-20 text-success border border-success border-opacity-50';
                                            $badgeIcon = 'fas fa-check-circle';
                                            $statusText = 'Published';
                                        } elseif ($schedule->status === 'done') {
                                            $badgeClass = 'bg-primary bg-opacity-20 text-primary border border-primary border-opacity-50';
                                            $badgeIcon = 'fas fa-check-double';
                                            $statusText = 'Done';
                                        } else {
                                            $badgeClass = 'bg-secondary bg-opacity-10 text-secondary';
                                            $badgeIcon = 'fas fa-archive';
                                            $statusText = ucfirst($schedule->status);
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
                                        {{-- Format tanggal menjadi HH/BB/TT --}}
                                        {{-- Ambil dari created_at schedule --}}
                                        @php
                                            $createdDate = $schedule->created_at ?? now();
                                        @endphp
                                        <div class="text-dark" data-bs-toggle="tooltip" data-bs-title="{{ $createdDate->format('d F Y H:i') }}">
                                            {{ $createdDate->format('d/m/Y') }}
                                        </div>
                                        <div class="text-muted" style="font-size: 0.7rem;">
                                            {{ $createdDate->format('H:i') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-1 text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if (strtolower($schedule->status) !== 'done')
                                            <a href="{{ route('admin.pub_schedule.edit', $schedule->id) }}" 
                                               class="btn btn-outline-primary border-1"
                                               data-bs-toggle="tooltip" 
                                               data-bs-title="Edit">
                                                <i class="fas fa-edit" style="font-size: 0.7rem;"></i>
                                            </a>
                                        @else
                                            <button type="button" 
                                                    class="btn btn-outline-secondary border-1"
                                                    disabled
                                                    data-bs-toggle="tooltip" 
                                                    data-bs-title="Cannot edit done schedule">
                                                <i class="fas fa-edit" style="font-size: 0.7rem;"></i>
                                            </button>
                                        @endif
                                        
                                        <!-- Delete Form -->
                                        <form action="{{ route('admin.pub_schedule.destroy', $schedule->id) }}" 
                                              method="POST" 
                                              class="d-inline delete-form"
                                              data-title="{{ $schedule->main_title }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="btn btn-outline-danger border-1"
                                                    data-bs-toggle="tooltip" 
                                                    data-bs-title="Delete">
                                                <i class="fas fa-trash" style="font-size: 0.7rem;"></i>
                                            </button>
                                        </form>
                                        
                                        @if ($schedule->status === 'draft')
                                            <!-- Publish Form -->
                                            <form action="{{ route('admin.pub_schedule.publish', $schedule->id) }}" 
                                                  method="POST" 
                                                  class="d-inline publish-form"
                                                  data-title="{{ $schedule->main_title }}">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-outline-success border-1"
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-title="Publish">
                                                    <i class="fas fa-paper-plane" style="font-size: 0.7rem;"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-3 text-center">
                                    <div class="py-3">
                                        <i class="fas fa-calendar-alt fa-lg text-muted mb-2"></i>
                                        <h6 class="text-muted">No Schedules Found</h6>
                                        <p class="text-muted small mb-2">Start by adding your first schedule</p>
                                        <a href="{{ route('admin.pub_schedule.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i> Add First Schedule
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
        @if($schedules->hasPages() || $schedules->total() > 10)
            <div class="card-footer bg-white border-top px-3 py-2">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-2 mb-md-0">
                        <p class="small text-muted mb-0">
                            Showing <span class="fw-semibold">{{ $schedules->firstItem() ?: 0 }}</span> to 
                            <span class="fw-semibold">{{ $schedules->lastItem() ?: 0 }}</span> of 
                            <span class="fw-semibold">{{ $schedules->total() }}</span> results
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Per Page Selector -->
                        <div class="d-none d-md-block">
                            <form method="GET" action="{{ route('admin.pub_schedule.index') }}" class="d-flex align-items-center">
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if(request('series'))
                                    <input type="hidden" name="series" value="{{ request('series') }}">
                                @endif
                                @if(request('year'))
                                    <input type="hidden" name="year" value="{{ request('year') }}">
                                @endif
                                @if(request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
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
                        @if($schedules->hasPages())
                            <div>
                                {{ $schedules->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
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
                Select all items ({{ $schedules->total() }} total)
            </label>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn">
                <i class="fas fa-trash me-1"></i> Delete Selected
            </button>
        </div>
    </div>
</div>

{{-- Image Modal --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img
                    id="modalImage"
                    src=""
                    class="img-fluid rounded"
                    alt="Schedule Image"
                    style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>

{{-- Hidden Bulk Delete Form --}}
<form id="bulkDeleteForm" method="POST" action="{{ route('admin.pub_schedule.bulk-destroy') }}" style="display: none;">
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

    .btn-outline-primary, .btn-outline-danger, .btn-outline-success, .btn-outline-warning, .btn-outline-info {
        border-width: 1px;
    }

    .badge {
        padding: 0.2em 0.6em;
        font-weight: 500;
        font-size: 0.75em;
        border: 1px solid transparent;
    }

    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
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

    /* Action buttons styling */
    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
        padding: 6px 12px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
    }

    .btn-outline-secondary {
        padding: 6px 12px;
        font-size: 0.85rem;
        font-weight: 500;
    }

    /* Pagination customization */
    .pagination {
        margin-bottom: 0;
    }

    .page-link {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }

    .page-item.active .page-link {
        background-color: #3498db;
        border-color: #3498db;
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
        
        .btn-primary, .btn-outline-secondary {
            flex: 1;
            justify-content: center;
            font-size: 0.8rem;
            padding: 5px 10px;
        }
        
        .table {
            font-size: 0.75rem !important;
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
        
        .card-footer .d-flex.align-items-center {
            align-items: flex-start !important;
        }
        
        .table th, .table td {
            padding: 4px 6px !important;
        }
        
        .table-responsive {
            font-size: 0.75rem;
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
            padding: 3px 4px !important;
        }
        
        .form-select-sm {
            padding: 0.125rem 0.25rem;
            font-size: 0.75rem;
        }
        
        .btn-group-sm > .btn {
            padding: 0.08rem 0.15rem;
            font-size: 0.6rem;
        }
        
        .badge {
            padding: 0.1em 0.4em;
            font-size: 0.65em;
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
        
        // Function to show image in modal
        window.showImage = function(src) {
            document.getElementById('modalImage').src = src;
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            imageModal.show();
        };
        
        // Handle delete forms - PERBAIKAN: SIMPLIFIKASI SWEETALERT
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const title = form.getAttribute('data-title');
                
                // PERBAIKAN: SweetAlert sederhana tanpa loading yang lama
                Swal.fire({
                    title: 'Delete Schedule?',
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
                        // Langsung submit tanpa delay apapun
                        form.submit();
                    }
                });
            });
        });
        
        // Handle publish forms - PERBAIKAN: SIMPLIFIKASI SWEETALERT
        document.querySelectorAll('.publish-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                const title = form.getAttribute('data-title');
                
                // PERBAIKAN: SweetAlert sederhana tanpa loading yang lama
                Swal.fire({
                    title: 'Publish Schedule?',
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
                        // Langsung submit tanpa delay apapun
                        form.submit();
                    }
                });
            });
        });
        
        // Bulk Delete dengan SweetAlert - PERBAIKAN: SIMPLIFIKASI
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
                    html: `Are you sure you want to delete <strong>${selectedItems.length}</strong> selected schedule(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete them!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Prepare bulk delete form data
                        const bulkDeleteForm = document.getElementById('bulkDeleteForm');
                        const selectedIds = selectedItems.map(item => item.value);
                        
                        // Clear any existing hidden inputs
                        bulkDeleteForm.querySelectorAll('input[name="selected[]"]').forEach(input => {
                            input.remove();
                        });
                        
                        // Add hidden inputs for selected IDs
                        selectedIds.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'selected[]';
                            input.value = id;
                            bulkDeleteForm.appendChild(input);
                        });
                        
                        // Langsung submit tanpa delay
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