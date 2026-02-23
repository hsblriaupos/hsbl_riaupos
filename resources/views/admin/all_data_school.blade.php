@extends('admin.layouts.app')
@section('title', 'Data Sekolah - Administrator')

@section('content')
@php $activeTab = 'school'; @endphp
@include('partials.tabs', compact('activeTab'))

{{-- Notifikasi Center --}}
@include('partials.center-notifications')

@push('styles')
<style>
    /* ===== TYPOGRAPHY ===== */
    .page-header {
        margin-bottom: 1.25rem;
    }

    .page-title {
        font-size: 1.25rem;
        font-weight: 500;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 0.8rem;
        font-weight: 400;
    }

    /* ===== CARD STYLING ===== */
    .card {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.25rem;
        background: white;
    }

    .card-header {
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 0.75rem 1rem;
        font-size: 0.9rem;
        font-weight: 500;
        color: #334155;
        border-radius: 10px 10px 0 0;
    }

    .card-body {
        padding: 1rem;
    }

    /* ===== FORM ELEMENTS ===== */
    .form-label {
        font-size: 0.8rem;
        font-weight: 500;
        color: #475569;
        margin-bottom: 0.25rem;
        display: block;
    }

    .form-control,
    .form-select {
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
        width: 100%;
        transition: all 0.2s;
        color: #1e293b;
        font-weight: 400;
    }

    /* ===== BUTTONS ===== */
    .btn-submit {
        background-color: #3b82f6;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-submit:hover {
        background-color: #2563eb;
    }

    .btn-secondary {
        background-color: #f1f5f9;
        color: #475569;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-secondary:hover {
        background-color: #e2e8f0;
        color: #1e293b;
    }

    .btn-sm {
        padding: 0.3rem 0.8rem;
        font-size: 0.75rem;
        border-radius: 6px;
    }

    /* ===== FILTER SECTION ===== */
    .filter-section {
        background-color: #f8fafc;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e2e8f0;
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        align-items: center;
    }

    .filter-select {
        min-width: 140px;
        max-width: 180px;
    }

    .search-box {
        flex-grow: 1;
        min-width: 200px;
        max-width: 300px;
    }

    /* ===== TABLE STYLING - VERSI HP OPTIMIZED ===== */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        margin: 0;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.8rem;
        color: #334155;
    }

    .table th {
        background-color: #f8fafc;
        padding: 0.5rem 0.4rem;
        text-align: left;
        font-weight: 500;
        color: #475569;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }

    .table td {
        padding: 0.4rem 0.4rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        font-weight: 400;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* ===== BADGE STYLING ===== */
    .badge {
        padding: 0.15rem 0.5rem;
        border-radius: 20px;
        font-size: 0.7rem;
        font-weight: 400;
        display: inline-block;
        border: 1px solid transparent;
    }

    .badge-primary {
        background-color: #eff6ff;
        color: #1d4ed8;
        border-color: #bfdbfe;
    }

    .badge-success {
        background-color: #f0fdf4;
        color: #166534;
        border-color: #bbf7d0;
    }

    .badge-warning {
        background-color: #fefce8;
        color: #854d0e;
        border-color: #fef08a;
    }

    /* ===== ACTION BUTTONS ===== */
    .action-buttons {
        display: flex;
        gap: 2px;
        justify-content: center;
    }

    .btn-action {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.75rem;
        background: transparent;
    }

    .btn-edit {
        background-color: #e3f2fd;
        color: #1976d2;
        border-color: #bbdefb;
    }

    .btn-edit:hover {
        background-color: #bbdefb;
    }

    .btn-delete {
        background-color: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .btn-delete:hover {
        background-color: #fecaca;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 2rem;
        color: #94a3b8;
        font-size: 0.85rem;
    }

    .empty-state i {
        font-size: 2.5rem;
        margin-bottom: 0.75rem;
        color: #cbd5e1;
    }

    /* ===== PAGINATION ===== */
    .pagination-container {
        padding: 0.75rem 1rem;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: flex-end;
    }

    .pagination {
        gap: 0.15rem;
        margin: 0;
        padding: 0;
    }

    .page-link {
        border-radius: 4px !important;
        color: #475569;
        border: 1px solid #e2e8f0;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        transition: all 0.2s;
        background: white;
        min-width: 30px;
        text-align: center;
    }

    .page-item.active .page-link {
        background: #3b82f6;
        border-color: #3b82f6;
        color: white;
    }

    /* ===== RESPONSIVE FIX - VERSI LAMA YANG UDAH OK ===== */
    @media (max-width: 768px) {
        body {
            overflow-x: hidden;
        }

        .admin-content-wrapper {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }

        .container {
            padding-left: 0.25rem !important;
            padding-right: 0.25rem !important;
            max-width: 100% !important;
        }

        /* Force all columns to full width */
        .row>[class*="col-"] {
            padding-left: 0.25rem !important;
            padding-right: 0.25rem !important;
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }

        /* Buttons full width */
        .text-end,
        .d-flex.justify-content-end {
            width: 100%;
        }

        .btn-submit,
        .btn-secondary {
            width: 100%;
            margin-top: 0.5rem;
            margin-right: 0 !important;
        }

        /* Card header */
        .card-header.d-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 0.75rem;
        }

        .card-header.d-flex>div:last-child {
            width: 100%;
            display: flex;
            gap: 0.5rem;
        }

        .card-header.d-flex .btn-sm {
            flex: 1;
            text-align: center;
        }

        /* Filter section */
        .filter-form {
            flex-direction: column;
            align-items: stretch;
            gap: 0.5rem;
        }

        .filter-select,
        .search-box {
            max-width: 100%;
            width: 100%;
        }

        .search-box {
            display: flex;
        }

        .search-box input {
            flex: 1;
        }

        /* Table styling - yang bikin ok di HP */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0;
        }

        .table {
            min-width: 650px;
            font-size: 0.75rem;
        }

        .table th,
        .table td {
            padding: 0.35rem 0.3rem;
        }

        .badge {
            font-size: 0.65rem;
            padding: 0.1rem 0.4rem;
        }

        .btn-action {
            width: 26px;
            height: 26px;
            font-size: 0.7rem;
        }

        .page-title {
            font-size: 1.1rem;
        }

        .page-subtitle {
            font-size: 0.75rem;
        }

        .pagination-container {
            justify-content: center;
        }

        .page-link {
            padding: 0.2rem 0.4rem;
            font-size: 0.7rem;
            min-width: 28px;
        }
    }

    @media (max-width: 576px) {
        .container {
            padding-left: 0.15rem !important;
            padding-right: 0.15rem !important;
        }

        .table {
            min-width: 600px;
            font-size: 0.7rem;
        }

        .table th,
        .table td {
            padding: 0.3rem 0.25rem;
        }

        .badge {
            font-size: 0.6rem;
            padding: 0.1rem 0.3rem;
        }

        .btn-action {
            width: 24px;
            height: 24px;
            font-size: 0.65rem;
        }

        .page-title {
            font-size: 1rem;
        }
    }
</style>
@endpush

<div class="container" style="max-width: 100%; padding-left: 10px; padding-right: 10px;">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title mt-2">
            <i class="fas fa-school me-2"></i> School Management
        </h1>
        <p class="page-subtitle">Manage the list of schools for HSBL competitions</p>
    </div>

    <!-- Form Tambah Sekolah -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-plus-circle me-2"></i> Tambah Sekolah Baru
        </div>
        <div class="card-body">
            <form action="{{ url('/admin/school/store') }}" method="POST" id="addSchoolForm">
                @csrf
                <div class="row g-1">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="school_name" class="form-label">Nama Sekolah <span class="text-danger">*</span></label>
                            <input type="text"
                                name="school_name"
                                id="school_name"
                                class="form-control"
                                placeholder="Contoh: SMA Negeri 1 Pekanbaru"
                                required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="city_id" class="form-label">Kota <span class="text-danger">*</span></label>
                            <select name="city_id" id="city_id" class="form-select" required>
                                <option value="">-- Pilih Kota --</option>
                                @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="category_name" class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select name="category_name" id="category_name" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach(['SMA', 'SMK', 'MA'] as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <label for="type" class="form-label">Jenis <span class="text-danger">*</span></label>
                            <select name="type" id="type" class="form-select" required>
                                <option value="">-- Pilih Jenis --</option>
                                @foreach(['NEGERI', 'SWASTA'] as $type)
                                <option value="{{ $type }}">{{ $type }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2 mt-2">
                    <button type="reset" class="btn-secondary">
                        <i class="fas fa-redo me-1"></i> Reset
                    </button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-plus me-1"></i> Tambah Sekolah
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-filter me-2"></i> Filter & Pencarian
        </div>
        <div class="filter-section">
            <form method="GET" action="{{ url('/admin/school') }}" class="filter-form" id="filterForm">
                <select name="city_filter" class="form-control filter-select">
                    <option value="">Semua Kota</option>
                    @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ request('city_filter') == $city->id ? 'selected' : '' }}>
                        {{ $city->city_name }}
                    </option>
                    @endforeach
                </select>

                <select name="category_filter" class="form-control filter-select">
                    <option value="">Semua Kategori</option>
                    @foreach(['SMA', 'SMK', 'MA'] as $category)
                    <option value="{{ $category }}" {{ request('category_filter') == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                    @endforeach
                </select>

                <select name="type_filter" class="form-control filter-select">
                    <option value="">Semua Jenis</option>
                    @foreach(['NEGERI', 'SWASTA'] as $type)
                    <option value="{{ $type }}" {{ request('type_filter') == $type ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                    @endforeach
                </select>

                <div class="input-group search-box">
                    <input type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari sekolah..."
                        value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="text-secondary small">
                        {{ $schools->total() }} data
                    </span>
                    <select name="per_page" class="form-control" style="width: auto;">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>

                <a href="{{ url('/admin/school') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-redo me-1"></i> Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Tabel Data Sekolah -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-list me-2"></i> Daftar Sekolah
                <span class="badge bg-secondary ms-1">{{ $schools->total() }}</span>
            </div>
            <div class="d-flex gap-1">
                <a href="{{ url('/admin/export/school') }}" class="btn-submit btn-sm">
                    <i class="fas fa-file-export me-1"></i> Export
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 45px;">No.</th>
                            <th>Nama Sekolah</th>
                            <th style="width: 80px;">Kategori</th>
                            <th style="width: 80px;">Jenis</th>
                            <th style="width: 100px;">Kota</th>
                            <th style="width: 70px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schools as $index => $school)
                        <tr>
                            <td class="text-secondary">{{ $schools->firstItem() + $index }}</td>
                            <td>
                                {{ $school->school_name }}
                            </td>
                            <td>
                                <span class="badge badge-primary">
                                    {{ $school->category_name }}
                                </span>
                            </td>
                            <td>
                                @if($school->type == 'NEGERI')
                                <span class="badge badge-success">
                                    {{ $school->type }}
                                </span>
                                @else
                                <span class="badge badge-warning">
                                    {{ $school->type }}
                                </span>
                                @endif
                            </td>
                            <td>
                                {{ $school->city->city_name ?? 'N/A' }}
                            </td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <button type="button"
                                        class="btn-action btn-edit"
                                        title="Edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        data-id="{{ $school->id }}"
                                        data-name="{{ $school->school_name }}"
                                        data-city="{{ $school->city_id }}"
                                        data-category="{{ $school->category_name }}"
                                        data-type="{{ $school->type }}"
                                        onclick="setEditData(this)">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form method="POST" action="{{ url('/admin/school/delete') }}" class="delete-form d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="table" value="schools">
                                        <input type="hidden" name="id" value="{{ $school->id }}">
                                        <button type="button"
                                            class="btn-action btn-delete btn-delete-item"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="empty-state">
                                    <i class="fas fa-school"></i>
                                    <p>Belum ada data sekolah.</p>
                                    @if(request()->hasAny(['search', 'city_filter', 'category_filter', 'type_filter']))
                                    <a href="{{ url('/admin/school') }}" class="btn btn-outline-primary btn-sm mt-2">
                                        <i class="fas fa-redo me-1"></i> Reset Filter
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination - Hanya Angka -->
            <div class="pagination-container">
                @if($schools->hasPages())
                    {{ $schools->onEachSide(1)->links('pagination::bootstrap-5') }}
                @else
                    <nav>
                        <ul class="pagination">
                            <li class="page-item disabled">
                                <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                            </li>
                            <li class="page-item active">
                                <span class="page-link">1</span>
                            </li>
                            <li class="page-item disabled">
                                <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                            </li>
                        </ul>
                    </nav>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i> Edit Sekolah
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="{{ url('/admin/school/edit') }}">
                    @csrf
                    <input type="hidden" name="table" value="schools">
                    <input type="hidden" name="id" id="editId">

                    <div class="mb-2">
                        <label class="form-label">Nama Sekolah <span class="text-danger">*</span></label>
                        <input type="text" name="school_name" id="editSchoolName"
                            class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Kota <span class="text-danger">*</span></label>
                        <select name="city_id" id="editCityId" class="form-select" required>
                            <option value="">-- Pilih Kota --</option>
                            @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-1">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label">Kategori <span class="text-danger">*</span></label>
                                <select name="category_name" id="editCategory" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach(['SMA', 'SMK', 'MA'] as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <label class="form-label">Jenis <span class="text-danger">*</span></label>
                                <select name="type" id="editType" class="form-select" required>
                                    <option value="">-- Pilih Jenis --</option>
                                    @foreach(['NEGERI', 'SWASTA'] as $type)
                                    <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save me-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Auto submit filter ketika select berubah
    document.addEventListener('DOMContentLoaded', function() {
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            const filterSelects = filterForm.querySelectorAll('select:not([name="per_page"])');
            
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    filterForm.submit();
                });
            });

            const perPageSelect = document.querySelector('select[name="per_page"]');
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    filterForm.submit();
                });
            }
        }
    });

    // Set data for edit modal
    function setEditData(button) {
        document.getElementById('editId').value = button.dataset.id;
        document.getElementById('editSchoolName').value = button.dataset.name;
        document.getElementById('editCityId').value = button.dataset.city;
        document.getElementById('editCategory').value = button.dataset.category;
        document.getElementById('editType').value = button.dataset.type;
    }

    // Delete Confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete-item');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const row = this.closest('tr');
                const schoolName = row ? row.querySelector('td:nth-child(2)').textContent.trim() : 'sekolah ini';

                Swal.fire({
                    title: 'Hapus Data Sekolah?',
                    html: `Apakah Anda yakin ingin menghapus <strong>${schoolName}</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        setTimeout(() => {
                            form.submit();
                        }, 500);
                    }
                });
            });
        });

        // Form validation untuk add
        const addForm = document.getElementById('addSchoolForm');
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                const schoolName = this.querySelector('[name="school_name"]').value.trim();
                const cityId = this.querySelector('[name="city_id"]').value;
                const category = this.querySelector('[name="category_name"]').value;
                const type = this.querySelector('[name="type"]').value;

                if (!schoolName || !cityId || !category || !type) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Form Tidak Lengkap',
                        text: 'Harap lengkapi semua field yang wajib diisi',
                        confirmButtonColor: '#3085d6',
                    });
                    return false;
                }

                // Loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
            });
        }

        // Form validation untuk edit
        const editForm = document.getElementById('editForm');
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                const schoolName = this.querySelector('[name="school_name"]').value.trim();
                const cityId = this.querySelector('[name="city_id"]').value;
                const category = this.querySelector('[name="category_name"]').value;
                const type = this.querySelector('[name="type"]').value;

                if (!schoolName || !cityId || !category || !type) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Form Tidak Lengkap',
                        text: 'Harap lengkapi semua field yang wajib diisi',
                        confirmButtonColor: '#3085d6',
                    });
                    return false;
                }

                // Loading state
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Menyimpan...';
            });
        }
    });
</script>
@endpush
@endsection