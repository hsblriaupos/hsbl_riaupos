@extends('admin.layouts.app')
@section('title', 'Term and Conditions - Administrator')

@section('content')
{{-- Include SweetAlert2 --}}
@include('partials.sweetalert')

<div class="container mt-4">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="page-title">
                <i class="fas fa-file-contract text-primary me-2"></i> Term and Conditions Management
            </h1>
            <p class="page-subtitle">Manage Syarat & Ketentuan (S&K) documents for events</p>
        </div>
    </div>

    <!-- Upload Section -->
    <div class="card mb-4">
        <div class="card-header bg-white border-bottom-0 py-3">
            <h5 class="mb-0">
                <i class="fas fa-upload text-primary me-2"></i> Upload Syarat & Ketentuan
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.term_conditions.store') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-5">
                        <label for="title" class="form-label small text-muted mb-1">Judul Dokumen *</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-heading text-muted"></i>
                            </span>
                            <input type="text" 
                                   name="title" 
                                   id="title" 
                                   class="form-control border-start-0"
                                   placeholder="Contoh: Syarat & Ketentuan Honda HSBL 2024"
                                   required
                                   value="{{ old('title') }}">
                        </div>
                        @error('title')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-3">
                        <label for="year" class="form-label small text-muted mb-1">Tahun *</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-calendar text-muted"></i>
                            </span>
                            <input type="number" 
                                   name="year" 
                                   id="year" 
                                   class="form-control border-start-0"
                                   value="{{ old('year', date('Y')) }}"
                                   min="2000"
                                   max="{{ date('Y') + 5 }}"
                                   required>
                        </div>
                        @error('year')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    
                    <div class="col-md-4">
                        <label for="file" class="form-label small text-muted mb-1">Dokumen PDF * (Max: 2MB)</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fas fa-file-pdf text-muted"></i>
                            </span>
                            <input type="file" 
                                   name="file" 
                                   id="file" 
                                   class="form-control border-start-0"
                                   accept="application/pdf"
                                   required>
                        </div>
                        @error('file')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <small class="text-muted mt-1 d-block">Hanya format PDF, maksimal 2MB</small>
                    </div>
                </div>
                
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <i class="fas fa-upload me-2"></i> Upload Dokumen
                    </button>
                    <button type="button" class="btn btn-outline-secondary" onclick="resetForm()">
                        <i class="fas fa-redo me-2"></i> Reset Form
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Documents List -->
    <div class="card">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list text-primary me-2"></i> Daftar Dokumen S&K
                </h5>
                <div class="d-flex gap-2 align-items-center">
                    <span id="selectedCount" class="text-muted small d-none">0 selected</span>
                    <button type="button" 
                            class="btn btn-sm btn-outline-danger d-none" 
                            id="bulkDeleteBtn"
                            data-bs-toggle="tooltip"
                            data-bs-title="Delete selected documents">
                        <i class="fas fa-trash me-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.8rem;">
                    <thead class="table-light">
                        <tr>
                            <th class="px-2 py-1" style="width: 30px;">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th class="px-2 py-1" style="width: 40px;">No</th>
                            <th class="px-2 py-1" style="min-width: 180px;">Judul Dokumen</th>
                            <th class="px-2 py-1" style="width: 80px;">Tahun</th>
                            <th class="px-2 py-1" style="width: 90px;">Ukuran</th>
                            <th class="px-2 py-1" style="width: 90px;">Status</th>
                            <th class="px-2 py-1" style="width: 110px;">Upload Date</th>
                            <th class="px-2 py-1 text-center" style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="documentsTable">
                        @forelse($terms as $index => $term)
                            <tr id="term-{{ $term->id }}">
                                <td class="px-2 py-1">
                                    <input type="checkbox" 
                                           name="selected_ids[]" 
                                           value="{{ $term->id }}" 
                                           class="form-check-input term-checkbox">
                                </td>
                                <td class="px-2 py-1 fw-medium text-muted">
                                    {{ $loop->iteration }}
                                </td>
                                <td class="px-2 py-1">
                                    <div class="fw-semibold text-dark text-truncate" style="max-width: 180px;" 
                                         data-bs-toggle="tooltip" data-bs-title="{{ $term->title }}">
                                        <i class="fas fa-file-alt text-primary me-1" style="font-size: 0.7rem;"></i>
                                        {{ Str::limit($term->title, 30) }}
                                    </div>
                                    @if($term->document)
                                        <div class="text-muted small text-truncate mt-1" style="max-width: 180px;"
                                             data-bs-toggle="tooltip" data-bs-title="{{ basename($term->document) }}">
                                            <i class="fas fa-paperclip me-1"></i>
                                            {{ Str::limit(basename($term->document), 25) }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-2 py-1">
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1">
                                        {{ $term->year }}
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    @if($term->file_exists && $term->file_size_formatted)
                                        <span class="text-muted small" data-bs-toggle="tooltip" 
                                              data-bs-title="File Size: {{ $term->file_size_formatted }}">
                                            {{ $term->file_size_formatted }}
                                        </span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="px-2 py-1">
                                    <span class="badge {{ $term->status_badge }} px-2 py-1" 
                                          data-bs-toggle="tooltip" 
                                          data-bs-title="{{ $term->status_text }}">
                                        {{ $term->status_text }}
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    <div class="small">
                                        <div class="text-dark">
                                            {{ $term->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="text-muted">
                                            {{ $term->created_at->format('H:i') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-2 py-1 text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if($term->download_url)
                                            <a href="{{ route('admin.term_conditions.view', $term->id) }}" 
                                               target="_blank"
                                               class="btn btn-outline-info border-1"
                                               data-bs-toggle="tooltip" 
                                               data-bs-title="View PDF">
                                                <i class="fas fa-eye" style="font-size: 0.7rem;"></i>
                                            </a>
                                        @endif
                                        <button type="button" 
                                                class="btn btn-outline-danger border-1 btn-delete-term"
                                                data-id="{{ $term->id }}"
                                                data-title="{{ $term->title }}"
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
                                        <i class="fas fa-file-pdf fa-lg text-muted mb-2"></i>
                                        <h6 class="text-muted">No Documents Found</h6>
                                        <p class="text-muted small mb-2">Upload your first S&K document using the form above</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Table Footer --}}
        @if($terms->count() > 0)
            <div class="card-footer bg-white border-top px-3 py-2">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="small text-muted mb-0">
                            Menampilkan <span class="fw-semibold">{{ $terms->count() }}</span> dokumen
                        </p>
                    </div>
                    <div>
                        <button type="button" 
                                class="btn btn-sm btn-outline-danger" 
                                id="footerBulkDeleteBtn"
                                style="display: none;">
                            <i class="fas fa-trash me-1"></i> Hapus Terpilih
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Hidden Forms --}}
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<form id="bulkDeleteForm" method="POST" action="{{ route('admin.term_conditions.destroySelected') }}" style="display: none;">
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

    .card-header {
        border-bottom: 1px solid #e9ecef;
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
        padding: 0.15rem 0.3rem;
        border-radius: 0.2rem;
        font-size: 0.7rem;
    }

    .btn-outline-primary, .btn-outline-info, .btn-outline-danger {
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

    .bg-info.bg-opacity-10 {
        background-color: rgba(13, 202, 240, 0.1) !important;
    }

    /* Status badge colors */
    .badge.bg-success {
        background-color: #28a745 !important;
        color: white !important;
    }
    
    .badge.bg-secondary {
        background-color: #6c757d !important;
        color: white !important;
    }
    
    .badge.bg-warning {
        background-color: #ffc107 !important;
        color: #212529 !important;
    }
    
    .badge.bg-info {
        background-color: #17a2b8 !important;
        color: white !important;
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
        
        .row.g-3 {
            margin-bottom: 10px;
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
        
        /* Hide some columns on mobile */
        .table th:nth-child(5), /* Hide File Size column */
        .table td:nth-child(5) {
            display: none;
        }
    }
    
    @media (max-width: 576px) {
        .page-title {
            font-size: 0.9rem;
        }
        
        /* Hide more columns on small mobile */
        .table th:nth-child(6), /* Hide Status column */
        .table td:nth-child(6),
        .table th:nth-child(7), /* Hide Upload Date column */
        .table td:nth-child(7) {
            display: none;
        }
        
        .btn-group-sm > .btn {
            padding: 0.05rem 0.1rem;
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
        
        // Select All functionality
        const selectAllCheckbox = document.getElementById('selectAll');
        const termCheckboxes = document.querySelectorAll('.term-checkbox');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const footerBulkDeleteBtn = document.getElementById('footerBulkDeleteBtn');
        const selectedCount = document.getElementById('selectedCount');
        
        // Function to update selected count
        function updateSelectedCount() {
            const selectedItems = Array.from(termCheckboxes).filter(cb => cb.checked);
            const count = selectedItems.length;
            
            if (count > 0) {
                selectedCount.textContent = `${count} terpilih`;
                selectedCount.classList.remove('d-none');
                bulkDeleteBtn.classList.remove('d-none');
                footerBulkDeleteBtn.style.display = 'block';
            } else {
                selectedCount.classList.add('d-none');
                bulkDeleteBtn.classList.add('d-none');
                footerBulkDeleteBtn.style.display = 'none';
            }
        }
        
        // Select All checkbox
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                termCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                updateSelectedCount();
            });
        }
        
        // Individual checkbox change
        termCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(termCheckboxes).every(cb => cb.checked);
                const anyChecked = Array.from(termCheckboxes).some(cb => cb.checked);
                
                if (selectAllCheckbox) {
                    selectAllCheckbox.checked = allChecked;
                    selectAllCheckbox.indeterminate = anyChecked && !allChecked;
                }
                
                updateSelectedCount();
            });
        });
        
        // File size validation
        const fileInput = document.getElementById('file');
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                const maxSize = 2 * 1024 * 1024; // 2MB in bytes
                
                if (file && file.size > maxSize) {
                    Swal.fire({
                        icon: 'error',
                        title: 'File Terlalu Besar',
                        text: 'Ukuran file maksimal 2MB',
                        confirmButtonColor: '#d33'
                    });
                    this.value = ''; // Clear the file input
                }
                
                // Validate file type
                if (file && file.type !== 'application/pdf') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format Tidak Didukung',
                        text: 'Hanya file PDF yang diizinkan',
                        confirmButtonColor: '#d33'
                    });
                    this.value = '';
                }
            });
        }
        
        // Form validation
        const uploadForm = document.getElementById('uploadForm');
        if (uploadForm) {
            uploadForm.addEventListener('submit', function(e) {
                const title = document.getElementById('title').value.trim();
                const year = document.getElementById('year').value;
                const file = document.getElementById('file').files[0];
                
                if (!title || !year || !file) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Form Tidak Lengkap',
                        text: 'Harap isi semua field yang wajib diisi',
                        confirmButtonColor: '#3085d6'
                    });
                }
            });
        }
        
        // Single delete with SweetAlert
        document.querySelectorAll('.btn-delete-term').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const termId = this.getAttribute('data-id');
                const title = this.getAttribute('data-title');
                
                Swal.fire({
                    title: 'Hapus Dokumen?',
                    html: `Apakah Anda yakin ingin menghapus <strong>"${title}"</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Harap tunggu',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        const deleteForm = document.getElementById('deleteForm');
                        deleteForm.action = `{{ url('admin/term_conditions') }}/${termId}`;
                        
                        setTimeout(() => {
                            deleteForm.submit();
                        }, 500);
                    }
                });
            });
        });
        
        // Bulk Delete
        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', handleBulkDelete);
            footerBulkDeleteBtn.addEventListener('click', handleBulkDelete);
        }
        
        function handleBulkDelete() {
            const selectedItems = Array.from(termCheckboxes).filter(cb => cb.checked);
            
            if (selectedItems.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak Ada Item Terpilih',
                    text: 'Harap pilih setidaknya satu dokumen untuk dihapus',
                    timer: 3000,
                    showConfirmButton: false
                });
                return;
            }
            
            Swal.fire({
                title: 'Hapus Dokumen Terpilih?',
                html: `Apakah Anda yakin ingin menghapus <strong>${selectedItems.length}</strong> dokumen terpilih?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus semua!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menghapus...',
                        text: `Menghapus ${selectedItems.length} dokumen`,
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    const bulkDeleteForm = document.getElementById('bulkDeleteForm');
                    const selectedIds = selectedItems.map(item => item.value);
                    
                    // Clear any existing hidden inputs
                    bulkDeleteForm.querySelectorAll('input[name="selected_ids[]"]').forEach(input => {
                        input.remove();
                    });
                    
                    // Add hidden inputs for selected IDs
                    selectedIds.forEach(id => {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'selected_ids[]';
                        input.value = id;
                        bulkDeleteForm.appendChild(input);
                    });
                    
                    setTimeout(() => {
                        bulkDeleteForm.submit();
                    }, 500);
                }
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
    });
    
    // Reset form function
    function resetForm() {
        document.getElementById('uploadForm').reset();
        document.getElementById('year').value = "{{ date('Y') }}";
    }
</script>
@endsection