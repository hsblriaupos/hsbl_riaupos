@extends('admin.layouts.app')
@section('title', 'Data Venue - Administrator')

@section('content')
@php $activeTab = 'venue'; @endphp
@include('partials.tabs', compact('activeTab'))

@include('partials.sweetalert')

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
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
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

    .form-control,
    .form-select {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 8px 12px;
        font-size: 0.9rem;
        width: 100%;
        transition: all 0.2s;
    }

    .form-control:focus,
    .form-select:focus {
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
        margin-right: 10px;
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

    .badge-secondary {
        background-color: #f8f9fa;
        color: #6c757d;
        border: 1px solid #dee2e6;
    }

    .img-thumbnail {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 5px;
        border: 1px solid #e0e0e0;
        cursor: pointer;
        transition: all 0.2s;
    }

    .img-thumbnail:hover {
        transform: scale(1.8);
        border-color: #3498db;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        z-index: 10;
        position: relative;
    }

    .action-icons {
        display: flex;
        gap: 6px;
        justify-content: center;
        flex-wrap: wrap;
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
        gap: 12px;
        align-items: center;
    }

    .filter-select {
        min-width: 150px;
        max-width: 200px;
        flex: 1;
    }

    .search-box {
        flex: 2;
        min-width: 250px;
        max-width: 300px;
    }

    .input-group .btn-outline-secondary {
        border-left: 0;
        background-color: #f8f9fa;
        transition: all 0.2s;
    }

    .input-group .btn-outline-secondary:hover {
        background-color: #e9ecef;
        border-color: #ced4da;
        color: #495057;
    }

    /* Pagination */
    .pagination-container {
        background: #f8fafc !important;
        padding: 16px 24px !important;
        border-top: 1px solid #e2e8f0 !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        min-height: 65px;
    }

    .pagination-info {
        font-size: 0.85rem;
        color: #718096;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pagination-info i {
        color: #667eea;
    }

    .custom-pagination {
        display: flex;
        align-items: center;
        gap: 5px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .page-link {
        font-size: 0.85rem !important;
        padding: 7px 12px !important;
        border: 1px solid #e2e8f0 !important;
        color: #4a5568 !important;
        font-weight: 600 !important;
        border-radius: 6px !important;
        min-width: 35px !important;
        height: 35px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s !important;
        text-decoration: none !important;
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
        border-color: #667eea !important;
        color: white !important;
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2) !important;
    }

    .page-link:hover:not(.active .page-link) {
        background: #f1f5f9 !important;
        border-color: #cbd5e0 !important;
        color: #2d3748 !important;
        text-decoration: none !important;
    }

    .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Modal Gambar - Ukuran Lebih Kecil */
    .modal-dialog.modal-sm {
        max-width: 450px !important;
        margin-top: 30px !important;
    }

    .modal-content {
        border-radius: 8px;
        overflow: hidden;
        max-height: 80vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.15);
    }

    .modal-header {
        padding: 10px 15px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        flex-shrink: 0;
    }

    .modal-title {
        font-size: 1rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .modal-body {
        padding: 0;
        flex: 1;
        overflow: hidden;
    }

    .image-container {
        background-color: #f8f9fa;
        padding: 12px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: auto;
        max-height: 50vh;
        min-height: 180px;
    }

    #modalImage {
        transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        cursor: zoom-in;
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 4px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.12);
    }

    #modalImage.zoomed {
        transform: scale(1.25);
        cursor: zoom-out;
        box-shadow: 0 3px 15px rgba(0, 0, 0, 0.2);
    }

    .modal-footer {
        padding: 8px 15px;
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
        flex-shrink: 0;
    }

    .btn-close {
        padding: 6px;
        opacity: 0.7;
        transition: opacity 0.2s;
        font-size: 0.8rem;
    }

    .btn-close:hover {
        opacity: 1;
    }

    /* ===== RESPONSIVE FIX - OPTIMIZED ===== */
    @media (max-width: 768px) {
        /* Fix body overflow */
        body {
            overflow-x: hidden !important;
            width: 100% !important;
        }

        .admin-content-wrapper {
            padding-left: 8px !important;
            padding-right: 8px !important;
            max-width: 100vw !important;
            overflow-x: hidden !important;
        }

        /* Container */
        .container {
            padding-left: 5px !important;
            padding-right: 5px !important;
            max-width: 100% !important;
            margin: 0 auto !important;
            width: 100% !important;
        }

        /* Force all rows to be full width */
        .row {
            margin-left: 0 !important;
            margin-right: 0 !important;
            width: 100% !important;
        }

        /* Force all columns to be full width */
        .row>[class*="col-"] {
            padding-left: 5px !important;
            padding-right: 5px !important;
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }

        /* Button submit di HP */
        .text-end {
            width: 100% !important;
            padding-left: 5px !important;
            padding-right: 5px !important;
        }

        .btn-submit,
        .btn-secondary {
            width: 100% !important;
            margin-top: 8px;
            margin-right: 0 !important;
        }

        /* Card styling */
        .card {
            width: 100% !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
            margin-bottom: 15px;
        }

        .card-body {
            padding: 12px;
        }

        .card-header {
            padding: 10px 12px;
            font-size: 0.9rem;
        }

        /* Header flex untuk mobile */
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
        }

        /* Table styling */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0;
        }

        .data-table {
            font-size: 0.8rem;
            min-width: 600px;
        }

        .data-table th,
        .data-table td {
            padding: 8px 6px;
        }

        .action-icons {
            gap: 4px;
        }

        .action-icon {
            width: 28px;
            height: 28px;
            font-size: 0.75rem;
        }

        .page-title {
            font-size: 1.2rem;
            padding-left: 5px;
        }

        .page-subtitle {
            font-size: 0.8rem;
            padding-left: 5px;
        }

        .badge {
            font-size: 0.7rem;
            padding: 3px 6px;
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

        .d-flex.align-items-center.gap-2 {
            flex-direction: column;
            width: 100%;
            gap: 8px;
        }

        select[name="per_page"] {
            width: 100% !important;
        }

        .pagination-info {
            margin-right: 0;
            margin-bottom: 5px;
            font-size: 0.8rem;
        }

        /* Image thumbnail */
        .img-thumbnail {
            width: 50px;
            height: 50px;
        }

        /* Modal Responsive */
        .modal-dialog.modal-sm {
            max-width: 90% !important;
            margin: 20px auto !important;
        }

        .image-container {
            max-height: 45vh;
            padding: 10px;
        }

        #modalImage.zoomed {
            transform: scale(1.15);
        }

        .modal-header {
            padding: 8px 12px;
        }

        .modal-footer {
            padding: 6px 12px;
        }

        /* Pagination responsive */
        .pagination-container {
            flex-direction: column;
            gap: 12px;
            text-align: center;
            padding: 14px 20px !important;
        }

        .custom-pagination {
            flex-wrap: wrap;
            justify-content: center;
        }

        /* Fix any potential overflow */
        * {
            max-width: 100%;
            box-sizing: border-box;
        }
    }

    @media (max-width: 576px) {
        .admin-content-wrapper {
            padding-left: 5px !important;
            padding-right: 5px !important;
        }

        .container {
            padding-left: 3px !important;
            padding-right: 3px !important;
        }

        .row>[class*="col-"] {
            padding-left: 3px !important;
            padding-right: 3px !important;
        }

        .card-header.d-flex > div:last-child {
            flex-wrap: wrap;
        }

        .btn-sm {
            flex: 1;
            text-align: center;
        }

        .data-table {
            font-size: 0.75rem;
            min-width: 550px;
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

        .form-control,
        .form-select {
            padding: 6px 8px;
            font-size: 0.85rem;
        }

        .badge {
            font-size: 0.65rem;
            padding: 2px 5px;
        }

        /* Image thumbnail */
        .img-thumbnail {
            width: 40px;
            height: 40px;
        }

        .img-thumbnail:hover {
            transform: scale(1.5);
        }

        /* Modal lebih kecil */
        .modal-dialog.modal-sm {
            max-width: 95% !important;
            margin: 15px auto !important;
        }

        .image-container {
            max-height: 40vh;
            padding: 8px;
        }

        .modal-header,
        .modal-footer {
            padding: 6px 10px;
        }

        .modal-title {
            font-size: 0.9rem;
        }

        #modalImage.zoomed {
            transform: scale(1.1);
        }

        .btn-close {
            padding: 4px;
            font-size: 0.7rem;
        }

        .pagination-info {
            font-size: 0.75rem;
        }
    }

    @media (max-width: 400px) {
        .modal-dialog.modal-sm {
            max-width: 98% !important;
            margin: 10px auto !important;
        }

        .image-container {
            max-height: 35vh;
        }

        .data-table {
            min-width: 500px;
        }
    }
</style>
@endpush

<div class="container" style="max-width: 100%; padding-left: 15px; padding-right: 15px;">
    <!-- Header Halaman -->
    <div class="page-header">
        <h1 class="page-title mt-2">
            <i class="fas fa-map-marker-alt me-2"></i> Venue Management
        </h1>
        <p class="page-subtitle">Manage the list of venues for HSBL competitions</p>
    </div>

    <!-- Form Tambah Venue -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-plus-circle me-2"></i> Tambah Venue Baru
        </div>
        <div class="card-body">
            <form action="{{ route('admin.venue.store') }}" method="POST" enctype="multipart/form-data" id="addVenueForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="venue_name" class="form-label">Nama Venue <span class="text-danger">*</span></label>
                            <input type="text"
                                name="venue_name"
                                id="venue_name"
                                class="form-control"
                                placeholder="Contoh: GOR UNILAK"
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
                            <label for="location" class="form-label">Lokasi <span class="text-danger">*</span></label>
                            <input type="text"
                                name="location"
                                id="location"
                                class="form-control"
                                placeholder="Contoh: Jl. Riau No. 123"
                                required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="layout" class="form-label">Layout/Gambar</label>
                            <input type="file"
                                name="layout"
                                id="layout"
                                class="form-control"
                                accept="image/*">
                            <small class="text-muted">Opsional: Upload layout venue (JPG, PNG, GIF)</small>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button type="reset" class="btn-secondary">
                        <i class="fas fa-redo me-2"></i> Reset
                    </button>
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <i class="fas fa-plus me-2"></i> Tambah Venue
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Section Filter -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-filter me-2"></i> Filter & Pencarian
        </div>
        <div class="filter-section">
            <form method="GET" action="{{ route('admin.all_data_venue') }}" class="filter-form" id="filterForm">
                <select name="city_id" class="form-control filter-select">
                    <option value="">Semua Kota</option>
                    @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                        {{ $city->city_name }}
                    </option>
                    @endforeach
                </select>

                <div class="input-group search-box">
                    <input type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari nama venue atau lokasi..."
                        value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="pagination-info">
                        <i class="fas fa-database me-1"></i>
                        {{ $venues->total() }} Data
                    </span>
                    <select name="per_page" class="form-control" style="width: auto;">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>

                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="resetFilter()">
                    <i class="fas fa-redo me-1"></i> Reset
                </button>
            </form>
        </div>
    </div>

    <!-- Tabel Data Venue -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-list me-2"></i> Daftar Venue
                <span class="badge bg-primary ms-2">{{ $venues->total() }}</span>
            </div>
            <div>
                <a href="{{ url('/admin/export/venue') }}" class="btn-submit btn-sm">
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
                            <th>Nama Venue</th>
                            <th>Kota</th>
                            <th>Lokasi</th>
                            <th style="width: 80px;">Layout</th>
                            <th style="width: 100px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($venues as $index => $venue)
                        <tr>
                            <td class="text-center">{{ $venues->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    <span class="fw-medium">{{ $venue->venue_name }}</span>
                                </div>
                            </td>
                            <td>
                                <i class="fas fa-city text-info me-1"></i>
                                {{ $venue->city->city_name ?? 'N/A' }}
                            </td>
                            <td>
                                <i class="fas fa-location-dot text-danger me-1"></i>
                                {{ $venue->location ?? '-' }}
                            </td>
                            <td class="text-center">
                                @if($venue->layout)
                                <img src="{{ asset('storage/' . $venue->layout) }}"
                                    alt="Layout"
                                    class="img-thumbnail"
                                    data-bs-toggle="modal"
                                    data-bs-target="#imageModal"
                                    onclick="showImage('{{ asset('storage/' . $venue->layout) }}')">
                                @else
                                <span class="badge badge-secondary">
                                    <i class="fas fa-image-slash me-1"></i> -
                                </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="action-icons">
                                    <button type="button"
                                        class="action-icon edit-icon"
                                        title="Edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editVenueModal"
                                        data-id="{{ $venue->id }}"
                                        data-name="{{ $venue->venue_name }}"
                                        data-city="{{ $venue->city_id }}"
                                        data-location="{{ $venue->location }}"
                                        onclick="setEditVenueData(this)">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form method="POST" action="{{ route('admin.venue.delete') }}"
                                        class="delete-form d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="table" value="venue">
                                        <input type="hidden" name="id" value="{{ $venue->id }}">
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
                                    <i class="fas fa-map-marker-alt"></i>
                                    <p>Belum ada data venue.</p>
                                    @if(request()->hasAny(['city_id', 'search']))
                                    <a href="{{ route('admin.all_data_venue') }}" class="btn btn-primary btn-sm mt-2">
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

            <!-- Pagination -->
            <div class="pagination-container">
                <div class="pagination-info">
                    <i class="fas fa-info-circle me-2"></i>
                    @if($venues->total() > 0)
                    Menampilkan <strong>{{ $venues->firstItem() }} - {{ $venues->lastItem() }}</strong>
                    dari <strong>{{ $venues->total() }}</strong> data
                    @else
                    Tidak ada data yang ditampilkan
                    @endif
                </div>

                <nav>
                    <ul class="custom-pagination">
                        {{-- Previous Page Link --}}
                        <li class="page-item {{ $venues->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link" 
                               href="{{ $venues->onFirstPage() ? '#' : $venues->previousPageUrl() }}"
                               aria-label="Previous">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>

                        {{-- Page Numbers --}}
                        @php
                        $currentPage = $venues->currentPage();
                        $lastPage = $venues->lastPage();
                        $startPage = max($currentPage - 2, 1);
                        $endPage = min($currentPage + 2, $lastPage);
                        @endphp

                        {{-- First page --}}
                        @if($startPage > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $venues->url(1) . '&' . http_build_query(request()->except('page')) }}">1</a>
                        </li>
                        @if($startPage > 2)
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                        @endif
                        @endif

                        {{-- Pages around current --}}
                        @for ($i = $startPage; $i <= $endPage; $i++)
                            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                @if($i == $currentPage)
                                <span class="page-link">{{ $i }}</span>
                                @else
                                <a class="page-link" href="{{ $venues->url($i) . '&' . http_build_query(request()->except('page')) }}">{{ $i }}</a>
                                @endif
                            </li>
                        @endfor

                        {{-- Last page --}}
                        @if($endPage < $lastPage)
                            @if($endPage < $lastPage - 1)
                            <li class="page-item disabled">
                                <span class="page-link">...</span>
                            </li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $venues->url($lastPage) . '&' . http_build_query(request()->except('page')) }}">{{ $lastPage }}</a>
                            </li>
                        @endif

                        {{-- Next Page Link --}}
                        <li class="page-item {{ !$venues->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" 
                               href="{{ !$venues->hasMorePages() ? '#' : $venues->nextPageUrl() }}"
                               aria-label="Next">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal - Ukuran Lebih Kecil -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-image me-2"></i> Layout Venue
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="image-container">
                    <img id="modalImage" src="" alt="Layout Venue" class="img-fluid">
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <small class="text-muted d-flex align-items-center">
                    <i class="fas fa-info-circle me-1"></i> Klik untuk zoom
                </small>
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Venue Modal -->
<div class="modal fade" id="editVenueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i> Edit Venue
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editVenueForm" method="POST" action="{{ route('admin.venue.edit') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="table" value="venue">
                    <input type="hidden" name="id" id="editVenueId">

                    <div class="mb-3">
                        <label class="form-label">Nama Venue <span class="text-danger">*</span></label>
                        <input type="text" name="venue_name" id="editVenueName"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kota <span class="text-danger">*</span></label>
                        <select name="city_id" id="editVenueCity" class="form-select" required>
                            <option value="">-- Pilih Kota --</option>
                            @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                        <input type="text" name="location" id="editVenueLocation"
                            class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Layout/Gambar Baru</label>
                        <input type="file" name="layout" id="editVenueLayout" class="form-control" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar</small>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn-submit" id="saveEditBtn">
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
    // Set data untuk edit venue modal
    function setEditVenueData(button) {
        document.getElementById('editVenueId').value = button.dataset.id;
        document.getElementById('editVenueName').value = button.dataset.name;
        document.getElementById('editVenueCity').value = button.dataset.city;
        document.getElementById('editVenueLocation').value = button.dataset.location;
    }

    // Tampilkan gambar di modal dengan kontrol zoom
    function showImage(imageSrc) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = imageSrc;
        modalImage.classList.remove('zoomed');
        
        // Reset event listener
        modalImage.onclick = null;
        
        // Tambah fungsi klik untuk zoom
        modalImage.onclick = function() {
            if (this.classList.contains('zoomed')) {
                this.classList.remove('zoomed');
                this.style.cursor = 'zoom-in';
            } else {
                this.classList.add('zoomed');
                this.style.cursor = 'zoom-out';
            }
        };
        
        // Set cursor awal
        modalImage.style.cursor = 'zoom-in';
    }

    // Reset zoom ketika modal ditutup
    const imageModal = document.getElementById('imageModal');
    if (imageModal) {
        imageModal.addEventListener('hidden.bs.modal', function () {
            const modalImage = document.getElementById('modalImage');
            if (modalImage) {
                modalImage.classList.remove('zoomed');
                modalImage.style.cursor = 'zoom-in';
            }
        });
    }

    // Fungsi filter
    function resetFilter() {
        if (confirm('Reset semua filter?')) {
            window.location.href = "{{ route('admin.all_data_venue') }}";
        }
    }

    // DOM Ready function
    document.addEventListener('DOMContentLoaded', function() {
        // Auto submit filter ketika select berubah
        const filterSelects = document.querySelectorAll('#filterForm select:not([name="per_page"])');
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });

        // Submit ketika per_page berubah
        const perPageSelect = document.querySelector('select[name="per_page"]');
        if (perPageSelect) {
            perPageSelect.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        }

        // Form validation untuk tambah venue
        const addForm = document.getElementById('addVenueForm');
        const submitBtn = document.getElementById('submitBtn');

        if (addForm && submitBtn) {
            addForm.addEventListener('submit', function(e) {
                const venueName = this.querySelector('[name="venue_name"]').value.trim();
                const cityId = this.querySelector('[name="city_id"]').value;
                const location = this.querySelector('[name="location"]').value.trim();

                if (!venueName || !cityId || !location) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Form Tidak Lengkap',
                        text: 'Harap lengkapi semua field yang wajib diisi',
                        icon: 'warning',
                        confirmButtonColor: '#3085d6',
                    });
                } else {
                    // Tampilkan loading
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
                    
                    // Tampilkan toast loading
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    
                    Toast.fire({
                        icon: 'info',
                        title: 'Menyimpan data venue...'
                    });
                }
            });
        }

        // Form validation untuk edit venue
        const editForm = document.getElementById('editVenueForm');
        const saveEditBtn = document.getElementById('saveEditBtn');

        if (editForm && saveEditBtn) {
            editForm.addEventListener('submit', function(e) {
                const venueName = this.querySelector('[name="venue_name"]').value.trim();
                const cityId = this.querySelector('[name="city_id"]').value;
                const location = this.querySelector('[name="location"]').value.trim();

                if (!venueName || !cityId || !location) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Form Tidak Lengkap',
                        text: 'Harap lengkapi semua field yang wajib diisi',
                        icon: 'warning',
                        confirmButtonColor: '#3085d6',
                    });
                } else {
                    // Tampilkan loading
                    saveEditBtn.disabled = true;
                    saveEditBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
                    
                    // Tampilkan toast loading
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 1500,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    
                    Toast.fire({
                        icon: 'info',
                        title: 'Menyimpan perubahan...'
                    });
                }
            });
        }

        // Confirmation untuk delete
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const row = form.closest('tr');
                const venueName = row ? row.querySelector('.fw-medium').textContent : 'venue ini';

                Swal.fire({
                    title: 'Hapus Data Venue?',
                    html: `Apakah Anda yakin ingin menghapus venue <strong>${venueName}</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
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

        // Perbaiki issue modal close
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('hidden.bs.modal', function () {
                document.body.classList.remove('modal-open');
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(backdrop => backdrop.remove());
            });
        });
    });
</script>
@endpush
@endsection