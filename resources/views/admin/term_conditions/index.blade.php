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
            <p class="page-subtitle">Manage Syarat & Ketentuan (S&K) documents via Google Drive links</p>
        </div>
    </div>

    <!-- Upload Section - Input Link Google Drive -->
    <div class="card mb-4">
        <div class="card-header bg-white border-bottom-0 py-3">
            <h5 class="mb-0">
                <i class="fab fa-google-drive text-primary me-2"></i> Tambah Link Google Drive
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.term_conditions.store') }}" method="POST" id="uploadForm">
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
                                   placeholder="Contoh: Syarat & Ketentuan Honda SBL 2024"
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
                        <label for="links" class="form-label small text-muted mb-1">Link Google Drive *</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="fab fa-google-drive text-muted"></i>
                            </span>
                            <input type="url" 
                                   name="links" 
                                   id="links" 
                                   class="form-control border-start-0"
                                   placeholder="https://drive.google.com/..."
                                   required
                                   value="{{ old('links') }}">
                        </div>
                        @error('links')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                        <div class="mt-1">
                            <small class="text-muted d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Contoh link FILE:</strong> https://drive.google.com/file/d/1ABC123xyz/view
                            </small>
                            <small class="text-muted d-block">
                                <i class="fas fa-info-circle me-1"></i>
                                <strong>Contoh link FOLDER:</strong> https://drive.google.com/drive/folders/1ABC123xyz
                            </small>
                        </div>
                        <div id="linkValidationMessage" class="mt-1" style="display: none;"></div>
                    </div>
                </div>
                
                <div class="mt-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary d-flex align-items-center" id="submitBtn">
                        <i class="fab fa-google-drive me-2"></i> Simpan Link
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
                            <th class="px-2 py-1" style="width: 250px;">Link Google Drive</th>
                            <th class="px-2 py-1" style="width: 90px;">Status</th>
                            <th class="px-2 py-1" style="width: 110px;">Upload Date</th>
                            <th class="px-2 py-1 text-center" style="width: 180px;">Actions</th>
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
                                </td>
                                <td class="px-2 py-1">
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-2 py-1">
                                        {{ $term->year }}
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    @if($term->links)
                                        <div class="d-flex align-items-center gap-1">
                                            {{-- Status Validitas Link --}}
                                            @if($term->has_valid_link)
                                                @if($term->is_file)
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-1" 
                                                          data-bs-toggle="tooltip" 
                                                          data-bs-title="Link File Valid">
                                                        <i class="fas fa-file-pdf" style="font-size: 0.6rem;"></i>
                                                    </span>
                                                @elseif($term->is_folder)
                                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 px-1"
                                                          data-bs-toggle="tooltip" 
                                                          data-bs-title="Link Folder">
                                                        <i class="fas fa-folder" style="font-size: 0.6rem;"></i>
                                                    </span>
                                                @endif
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-1"
                                                      data-bs-toggle="tooltip" 
                                                      data-bs-title="Link Tidak Valid">
                                                    <i class="fas fa-exclamation-circle" style="font-size: 0.6rem;"></i>
                                                </span>
                                            @endif
                                            
                                            {{-- Link --}}
                                            <a href="{{ $term->links }}" 
                                               target="_blank" 
                                               class="text-truncate text-decoration-none small" 
                                               style="max-width: 180px;"
                                               data-bs-toggle="tooltip" 
                                               data-bs-title="{{ $term->links }}">
                                                <i class="fab fa-google-drive text-primary me-1"></i>
                                                {{ Str::limit($term->links, 30) }}
                                            </a>
                                        </div>
                                        
                                        {{-- Tipe Link --}}
                                        @if($term->has_valid_link)
                                            <div class="mt-1">
                                                @if($term->is_file)
                                                    <span class="badge bg-light text-dark border px-1" style="font-size: 0.6rem;">
                                                        <i class="fas fa-file-pdf text-danger me-1"></i> File PDF
                                                    </span>
                                                @elseif($term->is_folder)
                                                    <span class="badge bg-light text-dark border px-1" style="font-size: 0.6rem;">
                                                        <i class="fas fa-folder text-warning me-1"></i> Folder
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
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
                                        @if($term->has_valid_link)
                                            {{-- Tombol untuk File --}}
                                            @if($term->is_file)
                                                <a href="{{ $term->getDirectDownloadLink() }}" 
                                                   target="_blank"
                                                   class="btn btn-outline-success border-1"
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-title="Download File">
                                                    <i class="fas fa-download" style="font-size: 0.7rem;"></i>
                                                </a>
                                                <a href="{{ $term->google_drive_embed_url }}" 
                                                   target="_blank"
                                                   class="btn btn-outline-info border-1"
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-title="Preview File">
                                                    <i class="fas fa-eye" style="font-size: 0.7rem;"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-outline-primary border-1 btn-preview-embed"
                                                        data-id="{{ $term->id }}"
                                                        data-title="{{ $term->title }}"
                                                        data-embed-url="{{ $term->google_drive_embed_url }}"
                                                        data-type="file"
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-title="Preview di Halaman">
                                                    <i class="fas fa-window-maximize" style="font-size: 0.7rem;"></i>
                                                </button>
                                            
                                            {{-- Tombol untuk Folder --}}
                                            @elseif($term->is_folder)
                                                <a href="{{ $term->links }}" 
                                                   target="_blank"
                                                   class="btn btn-outline-warning border-1"
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-title="Buka Folder">
                                                    <i class="fas fa-folder-open" style="font-size: 0.7rem;"></i>
                                                </a>
                                                <button type="button" 
                                                        class="btn btn-outline-secondary border-1"
                                                        disabled
                                                        data-bs-toggle="tooltip" 
                                                        data-bs-title="Preview tidak tersedia untuk folder">
                                                    <i class="fas fa-eye-slash" style="font-size: 0.7rem;"></i>
                                                </button>
                                            @endif
                                        @endif
                                        
                                        {{-- Tombol Edit Status --}}
                                        <button type="button" 
                                                class="btn btn-outline-warning border-1 btn-toggle-status"
                                                data-id="{{ $term->id }}"
                                                data-status="{{ $term->status }}"
                                                data-title="{{ $term->title }}"
                                                data-bs-toggle="tooltip" 
                                                data-bs-title="Ubah Status">
                                            <i class="fas fa-sync-alt" style="font-size: 0.7rem;"></i>
                                        </button>
                                        
                                        {{-- Tombol Delete --}}
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
                                        <i class="fab fa-google-drive fa-3x text-muted mb-3"></i>
                                        <h6 class="text-muted">No Documents Found</h6>
                                        <p class="text-muted small mb-3">Add your first Google Drive link using the form above</p>
                                        <button type="button" class="btn btn-sm btn-primary" onclick="document.getElementById('title').focus()">
                                            <i class="fas fa-plus me-1"></i> Tambah Dokumen
                                        </button>
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
                            <i class="fas fa-file me-1"></i> 
                            Menampilkan <span class="fw-semibold">{{ $terms->count() }}</span> dokumen
                            @php
                                $fileCount = $terms->filter(function($t) { return $t->is_file; })->count();
                                $folderCount = $terms->filter(function($t) { return $t->is_folder; })->count();
                            @endphp
                            @if($fileCount > 0 || $folderCount > 0)
                                <span class="ms-2">
                                    ({{ $fileCount }} file, {{ $folderCount }} folder)
                                </span>
                            @endif
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

{{-- Modal Preview untuk File --}}
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">
                    <i class="fab fa-google-drive text-primary me-2"></i>
                    Preview Dokumen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="previewIframe" 
                        src="" 
                        width="100%" 
                        height="600px" 
                        frameborder="0"
                        allowfullscreen="true"
                        style="background: #f8f9fa;">
                </iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Toggle Status --}}
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">
                    <i class="fas fa-sync-alt text-warning me-2"></i>
                    Ubah Status Dokumen
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="statusForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-body">
                    <p id="statusModalMessage"></p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="statusInfo"></span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-warning" id="confirmStatusBtn">
                        <i class="fas fa-check me-1"></i> Ya, Ubah Status
                    </button>
                </div>
            </form>
        </div>
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

<form id="toggleStatusForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
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
        padding: 0.2rem 0.3rem;
        border-radius: 0.2rem;
        font-size: 0.7rem;
    }

    .btn-outline-primary, .btn-outline-info, .btn-outline-danger, .btn-outline-success, .btn-outline-warning {
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

    .bg-success.bg-opacity-10 {
        background-color: rgba(40, 167, 69, 0.1) !important;
    }

    .bg-warning.bg-opacity-10 {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }

    .bg-danger.bg-opacity-10 {
        background-color: rgba(220, 53, 69, 0.1) !important;
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

    /* Modal styles */
    .modal-xl {
        max-width: 90%;
    }
    
    @media (min-width: 1200px) {
        .modal-xl {
            max-width: 1140px;
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
            padding: 0.15rem 0.25rem;
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
        .table th:nth-child(5), /* Hide Link column */
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
            padding: 0.1rem 0.2rem;
            font-size: 0.6rem;
        }
        
        .modal-xl {
            max-width: 100%;
            margin: 0;
        }
        
        .modal-body iframe {
            height: 400px;
        }
    }

    /* Loading spinner */
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
        border-width: 0.15em;
    }

    /* Link validation */
    #linkValidationMessage {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
    }
    
    .validation-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    
    .validation-error {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
    
    .validation-info {
        background-color: #d1ecf1;
        color: #0c5460;
        border: 1px solid #bee5eb;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // ========== SELECT ALL FUNCTIONALITY ==========
        const selectAllCheckbox = document.getElementById('selectAll');
        const termCheckboxes = document.querySelectorAll('.term-checkbox');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const footerBulkDeleteBtn = document.getElementById('footerBulkDeleteBtn');
        const selectedCount = document.getElementById('selectedCount');
        
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
        
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                termCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                updateSelectedCount();
            });
        }
        
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
        
        // ========== LINK VALIDATION WITH AJAX ==========
        const linksInput = document.getElementById('links');
        const validationMessage = document.getElementById('linkValidationMessage');
        const submitBtn = document.getElementById('submitBtn');
        let validationTimeout;
        
        if (linksInput) {
            linksInput.addEventListener('input', function() {
                const url = this.value.trim();
                
                // Clear previous timeout
                if (validationTimeout) {
                    clearTimeout(validationTimeout);
                }
                
                // Hide validation message if input is empty
                if (!url) {
                    validationMessage.style.display = 'none';
                    submitBtn.disabled = false;
                    return;
                }
                
                // Show loading state
                validationMessage.style.display = 'block';
                validationMessage.className = 'mt-1 validation-info';
                validationMessage.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Memvalidasi link...';
                submitBtn.disabled = true;
                
                // Set timeout to avoid too many requests
                validationTimeout = setTimeout(function() {
                    // Basic validation first
                    if (!url.includes('drive.google.com')) {
                        validationMessage.className = 'mt-1 validation-error';
                        validationMessage.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i> Bukan link Google Drive';
                        submitBtn.disabled = false;
                        return;
                    }
                    
                    // AJAX validation
                    fetch('{{ route("admin.term_conditions.validateLink") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ links: url })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.valid) {
                            let icon = data.type === 'file' ? 'fa-file-pdf' : 'fa-folder';
                            let typeText = data.type === 'file' ? 'File' : 'Folder';
                            
                            validationMessage.className = 'mt-1 validation-success';
                            validationMessage.innerHTML = `
                                <i class="fas fa-check-circle me-1"></i> 
                                Link ${typeText} Google Drive valid
                                <span class="badge bg-light text-dark ms-2">
                                    <i class="fas ${icon} me-1"></i> ${typeText}
                                </span>
                            `;
                            submitBtn.disabled = false;
                        } else {
                            validationMessage.className = 'mt-1 validation-error';
                            validationMessage.innerHTML = '<i class="fas fa-times-circle me-1"></i> ' + data.message;
                            submitBtn.disabled = false;
                        }
                    })
                    .catch(error => {
                        validationMessage.className = 'mt-1 validation-error';
                        validationMessage.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Gagal validasi link';
                        submitBtn.disabled = false;
                    });
                }, 500);
            });
            
            // Also validate on change
            linksInput.addEventListener('change', function() {
                const url = this.value.trim();
                if (url && !url.includes('drive.google.com')) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Peringatan',
                        text: 'Pastikan Anda memasukkan link Google Drive yang valid',
                        confirmButtonColor: '#3085d6'
                    });
                }
            });
        }
        
        // ========== FORM SUBMIT VALIDATION ==========
        const uploadForm = document.getElementById('uploadForm');
        if (uploadForm) {
            uploadForm.addEventListener('submit', function(e) {
                const title = document.getElementById('title').value.trim();
                const year = document.getElementById('year').value;
                const links = document.getElementById('links').value.trim();
                
                if (!title || !year || !links) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Form Tidak Lengkap',
                        text: 'Harap isi semua field yang wajib diisi',
                        confirmButtonColor: '#3085d6'
                    });
                } else if (!links.includes('drive.google.com')) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Link Tidak Valid',
                        text: 'Harap masukkan link Google Drive yang valid',
                        confirmButtonColor: '#d33'
                    });
                }
            });
        }
        
        // ========== PREVIEW MODAL ==========
        const previewButtons = document.querySelectorAll('.btn-preview-embed');
        const previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
        const previewIframe = document.getElementById('previewIframe');
        
        previewButtons.forEach(button => {
            button.addEventListener('click', function() {
                const title = this.getAttribute('data-title');
                const embedUrl = this.getAttribute('data-embed-url');
                const type = this.getAttribute('data-type');
                
                if (type === 'folder') {
                    Swal.fire({
                        icon: 'info',
                        title: 'Folder tidak dapat dipreview',
                        text: 'Link ini mengarah ke folder Google Drive. Silakan buka folder langsung.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }
                
                if (!embedUrl) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Preview Tidak Tersedia',
                        text: 'Link ini tidak dapat dipreview di halaman',
                        confirmButtonColor: '#d33'
                    });
                    return;
                }
                
                document.getElementById('previewModalLabel').innerHTML = `
                    <i class="fab fa-google-drive text-primary me-2"></i>
                    Preview: ${title}
                `;
                
                previewIframe.src = embedUrl;
                previewModal.show();
            });
        });
        
        // Clear iframe src when modal is closed
        document.getElementById('previewModal').addEventListener('hidden.bs.modal', function () {
            previewIframe.src = '';
        });
        
        // ========== TOGGLE STATUS MODAL ==========
        const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
        const statusForm = document.getElementById('statusForm');
        const statusModalMessage = document.getElementById('statusModalMessage');
        const statusInfo = document.getElementById('statusInfo');
        
        document.querySelectorAll('.btn-toggle-status').forEach(button => {
            button.addEventListener('click', function() {
                const termId = this.getAttribute('data-id');
                const currentStatus = this.getAttribute('data-status');
                const title = this.getAttribute('data-title');
                
                const newStatus = currentStatus === 'active' ? 'inactive' : 'active';
                const statusText = newStatus === 'active' ? 'Aktif' : 'Tidak Aktif';
                const badgeClass = newStatus === 'active' ? 'bg-success' : 'bg-secondary';
                
                statusModalMessage.innerHTML = `Apakah Anda yakin ingin mengubah status <strong>"${title}"</strong>?`;
                statusInfo.innerHTML = `
                    Status akan diubah dari <span class="badge ${currentStatus === 'active' ? 'bg-success' : 'bg-secondary'}">${currentStatus === 'active' ? 'Aktif' : 'Tidak Aktif'}</span> 
                    menjadi <span class="badge ${badgeClass}">${statusText}</span>
                `;
                
                statusForm.action = `{{ url('admin/term_conditions') }}/${termId}/toggle-status`;
                statusModal.show();
            });
        });
        
        // ========== SINGLE DELETE ==========
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
        
        // ========== BULK DELETE ==========
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
        
        // ========== AUTO-HIDE TOOLTIPS ON MOBILE ==========
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
        
        // Hide validation message
        const validationMessage = document.getElementById('linkValidationMessage');
        if (validationMessage) {
            validationMessage.style.display = 'none';
        }
    }
</script>
@endsection