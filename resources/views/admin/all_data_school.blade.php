@extends('admin.layouts.app')
@section('title', 'Data Sekolah - Administrator')

@section('content')
@php $activeTab = 'school'; @endphp
@include('partials.tabs', compact('activeTab'))

{{-- Notifikasi Center --}}
@include('partials.center-notifications')

@push('styles')
<style>
    .page-header {
        margin-bottom: 25px;
    }
    
    .page-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }
    
    .page-subtitle {
        color: #7f8c8d;
        font-size: 0.9rem;
    }
    
    .card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        margin-bottom: 20px;
    }
    
    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        padding: 12px 16px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #2c3e50;
    }
    
    .card-body {
        padding: 16px;
    }
    
    .form-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #2c3e50;
        margin-bottom: 6px;
        display: block;
    }
    
    .form-control, .form-select {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 8px 12px;
        font-size: 0.9rem;
        width: 100%;
        transition: all 0.2s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        outline: none;
    }
    
    .btn-submit {
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    
    .btn-submit:hover {
        background-color: #2980b9;
    }
    
    .btn-secondary {
        background-color: #95a5a6;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-secondary:hover {
        background-color: #7f8c8d;
    }
    
    .btn-sm {
        padding: 4px 10px;
        font-size: 0.8rem;
        border-radius: 4px;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }
    
    .data-table th {
        background-color: #f8f9fa;
        padding: 10px 12px;
        text-align: left;
        font-weight: 600;
        color: #2c3e50;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .data-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }
    
    .data-table tbody tr:hover {
        background-color: #f8fafc;
    }
    
    .empty-state {
        text-align: center;
        padding: 30px;
        color: #95a5a6;
        font-size: 0.9rem;
    }
    
    .empty-state i {
        font-size: 2rem;
        margin-bottom: 10px;
        color: #bdc3c7;
    }
    
    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
    }
    
    .badge-primary {
        background-color: #e3f2fd;
        color: #1565c0;
        border: 1px solid #bbdefb;
    }
    
    .badge-success {
        background-color: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
    }
    
    .badge-warning {
        background-color: #fff3e0;
        color: #ef6c00;
        border: 1px solid #ffcc80;
    }
    
    .action-icons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }
    
    .action-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        font-size: 0.85rem;
    }
    
    .edit-icon {
        background-color: #e3f2fd;
        color: #1976d2;
        border: 1px solid #bbdefb;
    }
    
    .edit-icon:hover {
        background-color: #bbdefb;
    }
    
    .delete-icon {
        background-color: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    
    .delete-icon:hover {
        background-color: #fecaca;
    }

    /* Filter Section */
    .filter-section {
        background-color: #f8fafc;
        padding: 12px 16px;
        border-bottom: 1px solid #e0e0e0;
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .filter-select {
        min-width: 150px;
        max-width: 200px;
    }

    .search-box {
        flex-grow: 1;
        min-width: 200px;
        max-width: 300px;
    }

    /* Pagination */
    .pagination-container {
        padding: 15px 20px;
        border-top: 1px solid #e0e0e0;
        display: flex;
        justify-content: flex-end;
    }

    .pagination {
        gap: 5px;
        margin: 0;
        padding: 0;
    }

    .page-link {
        border-radius: 4px !important;
        color: #4a5568;
        border: 1px solid #e2e8f0;
        padding: 0.4rem 0.7rem;
        font-size: 0.85rem;
        transition: all 0.2s;
        background: white;
    }

    .page-link:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e0;
        color: #2d3748;
    }

    .page-item.active .page-link {
        background: #3498db;
        border-color: #3498db;
        color: white;
    }

    .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f1f5f9;
    }

    /* Modal Styles */
    .modal-header {
        background-color: #3498db;
        color: white;
        border-radius: 8px 8px 0 0;
        padding: 12px 16px;
    }

    .modal-title {
        font-size: 1rem;
        font-weight: 600;
    }

    .btn-close-white {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* ===== RESPONSIVE FIX - SAMA KAYAK CITY ===== */
    @media (max-width: 768px) {
        /* Fix body overflow */
        body {
            overflow-x: hidden !important;
            width: 100% !important;
            position: relative !important;
        }
        
        .admin-content-wrapper {
            padding-left: 5px !important;
            padding-right: 5px !important;
            max-width: 100vw !important;
            overflow-x: hidden !important;
        }
        
        /* Container */
        .container {
            padding-left: 3px !important;
            padding-right: 3px !important;
            max-width: 100% !important;
            margin: 0 auto !important;
            width: 100% !important;
            overflow-x: hidden !important;
        }

        /* Force all rows to be full width */
        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
            width: 100% !important;
        }
        
        /* Force all columns to be full width */
        .row > [class*="col-"] {
            padding-left: 3px !important;
            padding-right: 3px !important;
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }

        /* Button submit di HP */
        .text-end {
            width: 100% !important;
            padding-left: 3px !important;
            padding-right: 3px !important;
        }

        .btn-submit, .btn-secondary {
            width: 100% !important;
            margin-top: 10px;
            margin-right: 0 !important;
        }

        /* Card header flex untuk mobile */
        .card-header.d-flex {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 10px;
        }

        .card-header.d-flex > div:last-child {
            width: 100%;
            display: flex;
            gap: 8px;
            justify-content: flex-start;
        }

        .card-header.d-flex .btn-sm {
            padding: 6px 10px;
            font-size: 0.75rem;
            white-space: nowrap;
            flex: 1;
            text-align: center;
        }

        /* Card styling */
        .card {
            width: 100% !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }
        
        .card-body {
            padding: 10px;
        }

        .card-header {
            padding: 10px 12px;
            font-size: 0.9rem;
        }

        /* Filter Section */
        .filter-form {
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
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

        /* Table styling - INI YANG PENTING! */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .data-table {
            font-size: 0.8rem;
            min-width: 100%; /* UBAH DARI 700px JADI 100% */
        }
        
        .data-table th,
        .data-table td {
            padding: 8px 6px;
            white-space: nowrap;
        }
        
        .action-icons {
            flex-direction: row;
            gap: 4px;
        }

        .action-icon {
            width: 28px;
            height: 28px;
            font-size: 0.75rem;
        }

        .page-title {
            font-size: 1.2rem;
            padding-left: 3px;
        }

        .page-subtitle {
            font-size: 0.8rem;
            padding-left: 3px;
        }
        
        .badge {
            font-size: 0.7rem;
            padding: 2px 6px;
        }

        /* Pagination */
        .pagination-container {
            justify-content: center;
            padding: 12px 15px;
        }

        .page-link {
            padding: 0.3rem 0.6rem;
            font-size: 0.75rem;
        }
        
        /* Fix any potential overflow */
        * {
            max-width: 100%;
            box-sizing: border-box;
        }
    }

    @media (max-width: 576px) {
        .admin-content-wrapper {
            padding-left: 3px !important;
            padding-right: 3px !important;
        }
        
        .container {
            padding-left: 2px !important;
            padding-right: 2px !important;
        }

        .row > [class*="col-"] {
            padding-left: 2px !important;
            padding-right: 2px !important;
        }

        .data-table {
            font-size: 0.75rem;
        }
        
        .data-table th,
        .data-table td {
            padding: 6px 4px;
        }
        
        .action-icon {
            width: 26px;
            height: 26px;
            font-size: 0.7rem;
        }

        .page-title {
            font-size: 1.1rem;
        }

        .card-header {
            padding: 8px 10px;
        }

        .card-body {
            padding: 8px;
        }

        .form-label {
            font-size: 0.8rem;
            margin-bottom: 4px;
        }

        .form-control, .form-select {
            padding: 6px 8px;
            font-size: 0.85rem;
        }
        
        .badge {
            font-size: 0.6rem;
            padding: 2px 5px;
        }

        .page-link {
            padding: 0.25rem 0.5rem;
            font-size: 0.7rem;
        }
    }
</style>
@endpush

<div class="container" style="max-width: 100%; padding-left: 15px; padding-right: 15px;">
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
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
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
                        <div class="mb-3">
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
                        <div class="mb-3">
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
                        <div class="mb-3">
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
                <div class="text-end mt-3">
                    <button type="reset" class="btn-secondary">
                        <i class="fas fa-redo me-2"></i> Reset
                    </button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-plus me-2"></i> Tambah Sekolah
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
                <span class="badge bg-primary ms-2">{{ $schools->total() }}</span>
            </div>
            <div class="d-flex gap-1">
                <a href="{{ url('/admin/school') }}" class="btn-secondary btn-sm">
                    <i class="fas fa-redo me-1"></i> Reset
                </a>
                <a href="{{ url('/admin/export/school') }}" class="btn-submit btn-sm">
                    <i class="fas fa-file-export me-1"></i> Export
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th>Nama Sekolah</th>
                            <th style="width: 90px;">Kategori</th>
                            <th style="width: 90px;">Jenis</th>
                            <th style="width: 120px;">Kota</th>
                            <th style="width: 100px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($schools as $index => $school)
                        <tr>
                            <td class="text-center">{{ $schools->firstItem() + $index }}</td>
                            <td>{{ $school->school_name }}</td>
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
                            <td>{{ $school->city->city_name ?? 'N/A' }}</td>
                            <td class="text-center">
                                <div class="action-icons">
                                    <button type="button"
                                        class="action-icon edit-icon"
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
                                            class="action-icon delete-icon btn-delete"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-school"></i>
                                    <p>Belum ada data sekolah.</p>
                                    @if(request()->hasAny(['search', 'city_filter', 'category_filter', 'type_filter']))
                                    <a href="{{ url('/admin/school') }}" class="btn btn-primary btn-sm mt-2">
                                        Reset Filter
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
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="{{ url('/admin/school/edit') }}">
                    @csrf
                    <input type="hidden" name="table" value="schools">
                    <input type="hidden" name="id" id="editId">

                    <div class="mb-3">
                        <label class="form-label">Nama Sekolah <span class="text-danger">*</span></label>
                        <input type="text" name="school_name" id="editSchoolName"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kota <span class="text-danger">*</span></label>
                        <select name="city_id" id="editCityId" class="form-select" required>
                            <option value="">-- Pilih Kota --</option>
                            @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-6">
                            <div class="mb-3">
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
                            <div class="mb-3">
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

                    <div class="d-flex justify-content-end gap-2 mt-4">
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
        const deleteButtons = document.querySelectorAll('.btn-delete');

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