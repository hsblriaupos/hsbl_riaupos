@extends('admin.layouts.app')
@section('title', 'Sponsors - Administrator')

@section('content')

{{-- Include SweetAlert2 --}}
@include('partials.sweetalert')

<div class="container mt-4">
    <!-- Page Header with Action Buttons -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="page-title">
                <i class="fas fa-handshake text-primary me-2"></i> Sponsor Management
            </h1>
            <p class="page-subtitle">Manage all sponsors and partners</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="{{ route('admin.sponsor.create') }}" 
               class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-plus me-2"></i> Add Sponsor
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.sponsor.sponsor') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label small text-muted mb-1">Search Sponsor</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input name="search" 
                               type="text" 
                               value="{{ request('search') }}"
                               class="form-control border-start-0"
                               placeholder="Search sponsor name...">
                    </div>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Category Filter</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-filter text-muted"></i>
                        </span>
                        <select name="category" class="form-select border-start-0">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" @selected(request('category')==$category)>
                                    {{ $category }}
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
                                // Jika ada data sponsor, ambil tahun minimum dari created_at
                                $minYear = $sponsors->isNotEmpty() ? $sponsors->min('created_at')->format('Y') : $currentYear;
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
                
                <div class="col-md-2">
                    <a href="{{ route('admin.sponsor.sponsor') }}" 
                       class="btn btn-outline-secondary btn-sm w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-redo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Sponsors Table -->
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
                            <th class="px-2 py-1" style="min-width: 60px;">Sponsor Name</th>
                            <th class="px-2 py-1" style="width: 100px;">Category</th>
                            <th class="px-2 py-1" style="width: 80px;">Logo</th>
                            <th class="px-2 py-1" style="width: 150px;">Website</th>
                            <th class="px-2 py-1" style="width: 80px;">Created</th>
                            <th class="px-2 py-1 text-center" style="width: 70px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sponsors as $index => $sponsor)
                            <tr>
                                <td class="px-2 py-1">
                                    <input type="checkbox" 
                                           name="selected[]" 
                                           value="{{ $sponsor->id }}" 
                                           class="form-check-input item-checkbox">
                                </td>
                                <td class="px-2 py-1 fw-medium text-muted">
                                    {{ $sponsors->firstItem() + $index }}
                                </td>
                                <td class="px-2 py-1">
                                    <div class="fw-semibold text-dark" 
                                         data-bs-toggle="tooltip" 
                                         data-bs-title="{{ $sponsor->sponsor_name }}">
                                        {{ Str::limit($sponsor->sponsor_name, 25) }}
                                    </div>
                                </td>
                                <td class="px-2 py-1">
                                    @php
                                        $categoryColors = [
                                            'Presented By' => 'primary',
                                            'Official Partners' => 'success',
                                            'Official Suppliers' => 'info',
                                            'Supporting Partners' => 'warning',
                                            'Managed By' => 'secondary'
                                        ];
                                        $color = $categoryColors[$sponsor->category] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }} border-opacity-25 px-2 py-1" 
                                          data-bs-toggle="tooltip" data-bs-title="{{ $sponsor->category }}">
                                        {{ $sponsor->category }}
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    @if($sponsor->logo)
                                        <div class="bg-light rounded overflow-hidden d-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 40px;">
                                            <img src="{{ asset('uploads/sponsors/'.$sponsor->logo) }}" 
                                                 alt="{{ $sponsor->sponsor_name }}"
                                                 class="h-100 object-fit-contain"
                                                 style="object-fit: contain; max-width: 100%;"
                                                 data-bs-toggle="tooltip" 
                                                 data-bs-title="View Logo">
                                        </div>
                                    @else
                                        <span class="text-muted small" data-bs-toggle="tooltip" data-bs-title="No logo">-</span>
                                    @endif
                                </td>
                                <td class="px-2 py-1">
                                    @if($sponsor->sponsors_web)
                                        <a href="{{ $sponsor->sponsors_web }}" 
                                           target="_blank" 
                                           class="text-decoration-none"
                                           data-bs-toggle="tooltip" 
                                           data-bs-title="{{ $sponsor->sponsors_web }}">
                                            <i class="fas fa-external-link-alt text-primary me-1"></i>
                                            <span class="text-truncate d-inline-block" style="max-width: 100px;">
                                                {{ Str::limit(str_replace(['https://', 'http://', 'www.'], '', $sponsor->sponsors_web), 20) }}
                                            </span>
                                        </a>
                                    @else
                                        <span class="text-muted small" data-bs-toggle="tooltip" data-bs-title="No website">-</span>
                                    @endif
                                </td>
                                <td class="px-2 py-1">
                                    <div class="small">
                                        {{-- Format tanggal menjadi HH/BB/TT --}}
                                        <div class="text-dark" data-bs-toggle="tooltip" data-bs-title="{{ $sponsor->created_at->format('d F Y H:i') }}">
                                            {{ $sponsor->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="text-muted" style="font-size: 0.7rem;">
                                            {{ $sponsor->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-1 text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.sponsor.edit', $sponsor->id) }}" 
                                           class="btn btn-outline-primary border-1"
                                           data-bs-toggle="tooltip" 
                                           data-bs-title="Edit">
                                            <i class="fas fa-edit" style="font-size: 0.7rem;"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger border-1 btn-delete-sponsor"
                                                data-id="{{ $sponsor->id }}"
                                                data-name="{{ $sponsor->sponsor_name }}"
                                                data-bs-toggle="tooltip" 
                                                data-bs-title="Delete">
                                            <i class="fas fa-trash" style="font-size: 0.7rem;"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-3 text-center">
                                    <div class="py-3">
                                        <i class="fas fa-handshake fa-lg text-muted mb-2"></i>
                                        <h6 class="text-muted">No Sponsors Found</h6>
                                        <p class="text-muted small mb-2">Start by adding your first sponsor</p>
                                        <a href="{{ route('admin.sponsor.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i> Add First Sponsor
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
        @if($sponsors->hasPages() || $sponsors->total() > 10)
            <div class="card-footer bg-white border-top px-3 py-2">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-2 mb-md-0">
                        <p class="small text-muted mb-0">
                            Showing <span class="fw-semibold">{{ $sponsors->firstItem() ?: 0 }}</span> to 
                            <span class="fw-semibold">{{ $sponsors->lastItem() ?: 0 }}</span> of 
                            <span class="fw-semibold">{{ $sponsors->total() }}</span> results
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <!-- Per Page Selector -->
                        <div class="d-none d-md-block">
                            <form method="GET" action="{{ route('admin.sponsor.sponsor') }}" class="d-flex align-items-center">
                                @if(request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if(request('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
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
                        @if($sponsors->hasPages())
                            <div>
                                {{ $sponsors->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
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
                Select all items ({{ $sponsors->total() }} total)
            </label>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn">
                <i class="fas fa-trash me-1"></i> Delete Selected
            </button>
        </div>
    </div>
</div>

{{-- Hidden Delete Form --}}
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

{{-- Hidden Bulk Delete Form --}}
<form id="bulkDeleteForm" method="POST" action="{{ route('admin.sponsor.destroySelected') }}" style="display: none;">
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

    .btn-outline-primary, .btn-outline-danger {
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

    /* Category badge colors */
    .bg-primary.bg-opacity-10 {
        background-color: rgba(52, 152, 219, 0.2) !important;
    }
    
    .bg-success.bg-opacity-10 {
        background-color: rgba(40, 167, 69, 0.2) !important;
    }
    
    .bg-info.bg-opacity-10 {
        background-color: rgba(23, 162, 184, 0.2) !important;
    }
    
    .bg-warning.bg-opacity-10 {
        background-color: rgba(255, 193, 7, 0.2) !important;
    }
    
    .bg-secondary.bg-opacity-10 {
        background-color: rgba(108, 117, 125, 0.2) !important;
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

    /* Logo image container */
    .object-fit-contain {
        object-fit: contain !important;
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
        
        /* Mobile adjustments for sponsor table */
        .table td:nth-child(3), /* Sponsor Name */
        .table td:nth-child(6) { /* Website */
            max-width: 100px !important;
        }
        
        .badge {
            font-size: 0.65em;
            padding: 0.1em 0.4em;
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
            padding: 4px 6px !important;
        }
        
        .form-select-sm {
            padding: 0.125rem 0.25rem;
            font-size: 0.75rem;
        }
        
        .table-responsive {
            font-size: 0.75rem;
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
        
        // SweetAlert for delete confirmation (single item)
        document.querySelectorAll('.btn-delete-sponsor').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const sponsorId = this.getAttribute('data-id');
                const sponsorName = this.getAttribute('data-name');
                
                Swal.fire({
                    title: 'Delete Sponsor?',
                    html: `Are you sure you want to delete <strong>"${sponsorName}"</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Deleting...',
                            text: 'Please wait',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Prepare delete form
                        const deleteForm = document.getElementById('deleteForm');
                        deleteForm.action = `{{ url('admin/sponsor') }}/${sponsorId}`;
                        
                        // Submit form after a short delay
                        setTimeout(() => {
                            deleteForm.submit();
                        }, 500);
                    }
                });
            });
        });
        
        // Bulk Delete with SweetAlert
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        
        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', function() {
                const selectedItems = Array.from(document.querySelectorAll('.item-checkbox:checked'));
                
                if (selectedItems.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Items Selected',
                        text: 'Please select at least one sponsor to delete',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    return;
                }
                
                Swal.fire({
                    title: 'Delete Selected Sponsors?',
                    html: `Are you sure you want to delete <strong>${selectedItems.length}</strong> selected sponsor(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete them!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Deleting...',
                            text: `Deleting ${selectedItems.length} sponsor(s)`,
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        // Prepare bulk delete form data
                        const bulkDeleteForm = document.getElementById('bulkDeleteForm');
                        const selectedIds = selectedItems.map(item => item.value);
                        
                        // Clear any existing hidden inputs
                        bulkDeleteForm.querySelectorAll('input[name="ids[]"]').forEach(input => {
                            input.remove();
                        });
                        
                        // Add hidden inputs for selected IDs
                        selectedIds.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'ids[]';
                            input.value = id;
                            bulkDeleteForm.appendChild(input);
                        });
                        
                        // Submit form after a short delay
                        setTimeout(() => {
                            bulkDeleteForm.submit();
                        }, 500);
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