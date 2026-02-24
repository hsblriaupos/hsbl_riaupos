@extends('admin.layouts.app')
@section('title', 'Team List - Administrator')

@section('content')
@include('partials.sweetalert')

@push('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #20bf6b 0%, #01baef 100%);
        --danger-gradient: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
        --warning-gradient: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        --info-gradient: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    }

    /* MINIMAL CONTAINER PADDING */
    .container-fluid.py-4 {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        letter-spacing: -0.3px;
        margin-bottom: 0.3rem;
    }

    .page-subtitle {
        color: #718096;
        font-size: 0.9rem;
        font-weight: 400;
        margin-bottom: 0;
    }

    .admin-card {
        background: white;
        border: none;
        border-radius: 10px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .card-header {
        background: var(--primary-gradient);
        border: none;
        padding: 16px 24px;
        font-weight: 600;
        color: white;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .filter-section {
        background: white;
        border: none;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .filter-header {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        padding: 16px 24px;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.95rem;
        color: #2c3e50;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-body {
        padding: 24px;
    }

    .filter-label {
        font-size: 0.85rem;
        color: #4a5568;
        margin-bottom: 6px;
        font-weight: 600;
        display: block;
    }

    .form-control-sm {
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 0.85rem;
        padding: 8px 12px;
        height: 38px;
        transition: all 0.2s;
    }

    .form-control-sm:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    /* ===== TABEL KOMPAK & RESPONSIVE ===== */
    .table-container {
        overflow-x: auto;
        border-radius: 0 0 10px 10px;
        -webkit-overflow-scrolling: touch;
        padding: 5px;
    }

    .table {
        width: 100% !important;
        font-size: 0.82rem;
        margin-bottom: 0;
        color: #4a5568;
        border-collapse: separate;
        border-spacing: 0;
    }

    /* HEADER - KOMPAK & JELAS */
    .table th {
        text-align: center !important;
        vertical-align: middle !important;
        font-weight: 700 !important;
        color: #2d3748 !important;
        padding: 12px 8px !important;
        background: #f8fafc !important;
        font-size: 0.75rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.2px !important;
        border-bottom: 2px solid #e2e8f0 !important;
        white-space: nowrap !important;
        line-height: 1.2 !important;
        height: 48px;
    }

    /* CELL - KOMPAK & RAPI */
    .table td {
        padding: 10px 8px !important;
        vertical-align: middle !important;
        border-top: 1px solid #f7fafc !important;
        line-height: 1.3 !important;
        min-height: 56px;
    }

    /* ROW HOVER EFFECT */
    .table tbody tr {
        transition: all 0.15s ease;
    }

    .table tbody tr:hover {
        background-color: #f8fafc !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    /* ===== LEBAR KOLOM OPTIMAL (LEBIH KOMPAK) ===== */
    .table th:nth-child(1),
    .table td:nth-child(1) {
        width: 70px !important;
        min-width: 70px !important;
        max-width: 70px !important;
        text-align: center !important;
        padding: 5px !important;
    }

    .table th:nth-child(2),
    .table td:nth-child(2) {
        width: 60px !important;
        min-width: 60px !important;
        max-width: 60px !important;
        text-align: center !important;
    }

    .table th:nth-child(3),
    .table td:nth-child(3) {
        width: 120px !important;
        min-width: 120px !important;
        max-width: 120px !important;
        text-align: left !important;
    }

    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 100px !important;
        min-width: 100px !important;
        max-width: 160px !important;
        text-align: left !important;
    }

    .table th:nth-child(5),
    .table td:nth-child(5) {
        width: 110px !important;
        min-width: 110px !important;
        max-width: 110px !important;
        text-align: left !important;
    }

    .table th:nth-child(6),
    .table td:nth-child(6) {
        width: 85px !important;
        min-width: 85px !important;
        max-width: 85px !important;
        text-align: center !important;
    }

    .table th:nth-child(7),
    .table td:nth-child(7) {
        width: 85px !important;
        min-width: 85px !important;
        max-width: 85px !important;
        text-align: center !important;
    }

    .table th:nth-child(8),
    .table td:nth-child(8) {
        width: 85px !important;
        min-width: 85px !important;
        max-width: 85px !important;
        text-align: center !important;
    }

    .table th:nth-child(9),
    .table td:nth-child(9) {
        width: 120px !important;
        min-width: 90px !important;
        max-width: 90px !important;
        text-align: center !important;
    }

    .table th:nth-child(10),
    .table td:nth-child(10) {
        width: 90px !important;
        min-width: 90px !important;
        max-width: 90px !important;
        text-align: center !important;
    }

    /* ===== LOGO SEKOLAH - FIXED ===== */
    .logo-container {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 50px;
        height: 50px;
        margin: 0 auto;
        border-radius: 8px;
        overflow: hidden;
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .logo-container:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-color: #cbd5e0;
    }

    .logo-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 4px;
        border-radius: 6px;
        background: white;
    }

    .logo-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        color: #a0aec0;
        font-size: 0.7rem;
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        border: 1px dashed #cbd5e0;
        border-radius: 6px;
        text-align: center;
        padding: 4px;
    }

    .logo-placeholder i {
        font-size: 1.2rem;
        margin-bottom: 4px;
        color: #a0aec0;
    }

    /* ===== STYLING KONTEN YANG KOMPAK ===== */
    .team-number-badge {
        font-family: 'SF Mono', 'Courier New', monospace;
        font-size: 0.75rem;
        font-weight: 700;
        color: #2c3e50;
        background: #f8f9fa;
        padding: 4px 6px;
        border-radius: 4px;
        border: 1px solid #e2e8f0;
        display: inline-block;
        text-align: center;
        min-width: 45px;
        letter-spacing: 0.2px;
        line-height: 1.2;
    }

    .school-info {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .school-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.8rem;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-height: 2.6em;
    }

    .team-meta {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .team-name {
        font-size: 0.8rem;
        color: #4a5568;
        line-height: 1.3;
        font-weight: 500;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-height: 2.6em;
    }

    .competition-info {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .competition-name {
        font-size: 0.8rem;
        color: #4a5568;
        font-weight: 500;
        line-height: 1.3;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-height: 3.9em;
    }

    .registrant-info {
        text-align: center;
    }

    .registrant-name {
        font-size: 0.8rem;
        color: #4a5568;
        font-weight: 500;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-height: 2.6em;
    }

    /* ===== BADGE STATUS YANG JELAS & KOMPAK ===== */
    .status-badge {
        padding: 4px 8px !important;
        font-size: 0.65rem !important;
        font-weight: 700 !important;
        border-radius: 6px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 3px !important;
        text-transform: uppercase;
        min-width: 65px;
        max-width: 65px;
        height: 24px;
        border: 1px solid transparent;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .badge-locked {
        background: linear-gradient(135deg, #fed7d7 0%, #fc8181 100%);
        color: #9b2c2c;
        border-color: #fc8181;
    }

    .badge-unlocked {
        background: linear-gradient(135deg, #c6f6d5 0%, #68d391 100%);
        color: #276749;
        border-color: #68d391;
    }

    .badge-verified {
        background: linear-gradient(135deg, #c6f6d5 0%, #48bb78 100%);
        color: #276749;
        border-color: #48bb78;
    }

    .badge-unverified {
        background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
        color: #9b2c2c;
        border-color: #feb2b2;
    }

    .status-badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.12);
    }

    /* ===== ACTION BUTTONS YANG KOMPAK ===== */
    .action-buttons {
        display: flex;
        gap: 5px;
        flex-wrap: nowrap;
        justify-content: center;
    }

    .btn-action {
        padding: 5px 8px;
        font-size: 0.75rem;
        border-radius: 5px;
        line-height: 1;
        min-width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: none;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
        cursor: pointer;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
    }

    .btn-view {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .btn-verify {
        background: linear-gradient(135deg, #48bb78 0%, #38a169 100%);
        color: white;
    }

    .btn-unverify {
        background: linear-gradient(135deg, #ed8936 0%, #dd6b20 100%);
        color: white;
    }

    /* ===== TANGGAL ===== */
    .date-cell {
        text-align: center;
        font-size: 0.78rem;
    }

    .date-primary {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 2px;
        font-size: 0.75rem;
    }

    .date-secondary {
        font-size: 0.7rem;
        color: #718096;
    }

    /* ===== PAGINATION ===== */
    .pagination-container {
        background: #f8fafc !important;
        padding: 14px 20px !important;
        border-top: 1px solid #e2e8f0 !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        min-height: 60px;
    }

    .pagination-info {
        font-size: 0.8rem;
        color: #718096;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pagination-info i {
        color: #667eea;
    }

    .custom-pagination {
        display: flex;
        align-items: center;
        gap: 4px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .page-link {
        font-size: 0.8rem !important;
        padding: 5px 10px !important;
        border: 1px solid #e2e8f0 !important;
        color: #4a5568 !important;
        font-weight: 600 !important;
        border-radius: 5px !important;
        min-width: 30px !important;
        height: 30px;
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s !important;
    }

    .page-item.active .page-link {
        background: var(--primary-gradient) !important;
        border-color: #667eea !important;
        color: white !important;
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.2) !important;
    }

    .page-link:hover:not(.active .page-link) {
        background: #f1f5f9 !important;
        border-color: #cbd5e0 !important;
        color: #2d3748 !important;
    }

    /* ===== EXPORT BUTTON ===== */
    .export-btn {
        background: linear-gradient(135deg, #20bf6b 0%, #01baef 100%);
        color: white;
        border: none;
        padding: 9px 18px;
        border-radius: 7px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
        box-shadow: 0 2px 5px rgba(32, 191, 107, 0.15);
        text-decoration: none;
    }

    .export-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(32, 191, 107, 0.25);
        color: white;
    }

    /* ===== FILTER BUTTON ===== */
    .btn-filter-submit {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        padding: 9px 18px;
        border-radius: 7px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.3s;
        box-shadow: 0 2px 5px rgba(102, 126, 234, 0.15);
        height: 38px;
        width: 100%;
    }

    .btn-filter-submit:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.25);
        color: white;
    }

    .btn-reset {
        background: white;
        color: #4a5568;
        border: 1px solid #e2e8f0;
        padding: 9px 18px;
        border-radius: 7px;
        font-weight: 600;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.3s;
        height: 38px;
        width: 100%;
    }

    .btn-reset:hover {
        background: #f1f5f9;
        border-color: #cbd5e0;
        color: #2d3748;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        padding: 50px 15px;
        text-align: center;
    }

    .empty-icon {
        font-size: 3rem;
        color: #cbd5e0;
        margin-bottom: 15px;
        opacity: 0.4;
    }

    .empty-title {
        font-size: 1.1rem;
        color: #718096;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .empty-text {
        color: #a0aec0;
        font-size: 0.85rem;
        max-width: 350px;
        margin: 0 auto;
        line-height: 1.4;
    }

    /* ===== INFO SEKUNDER ===== */
    .text-small {
        font-size: 0.68rem !important;
        line-height: 1.1 !important;
        color: #718096;
        opacity: 0.8;
        display: flex;
        align-items: center;
        gap: 3px;
        margin-top: 1px;
    }

    /* ===== LOGO POPUP ===== */
    .logo-popup-img {
        max-width: 200px;
        max-height: 200px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding: 8px;
        background: white;
    }

    /* ===== RESPONSIVE ADJUSTMENTS ===== */
    @media (max-width: 1400px) {

        .table th:nth-child(3),
        .table td:nth-child(3) {
            width: 110px !important;
        }

        .table th:nth-child(4),
        .table td:nth-child(4) {
            width: 150px !important;
        }

        .table th:nth-child(5),
        .table td:nth-child(5) {
            width: 100px !important;
        }
    }

    @media (max-width: 1200px) {
        .container-fluid.py-4 {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }

        .table {
            min-width: 900px !important;
        }

        .table-container {
            overflow-x: auto;
        }

        .text-small {
            display: none;
        }

        .school-name,
        .team-name,
        .competition-name,
        .registrant-name {
            -webkit-line-clamp: 2;
        }
    }

    @media (max-width: 992px) {
        .filter-body {
            padding: 18px;
        }

        .filter-header,
        .card-header {
            padding: 14px 18px;
        }

        .pagination-container {
            flex-direction: column;
            gap: 10px;
            text-align: center;
            padding: 12px 18px !important;
        }

        .table th,
        .table td {
            padding: 9px 6px !important;
        }

        .logo-container {
            width: 45px;
            height: 45px;
        }
    }

    @media (max-width: 768px) {
        .container-fluid.py-4 {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }

        .page-title {
            font-size: 1.2rem;
        }

        .export-btn {
            padding: 8px 14px;
            font-size: 0.8rem;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 12px;
        }

        .logo-container {
            width: 40px;
            height: 40px;
        }

        .logo-placeholder i {
            font-size: 1rem;
        }
    }

    @media (max-width: 576px) {
        .filter-body {
            padding: 14px;
        }

        .export-btn {
            width: 100%;
            justify-content: center;
        }

        .pagination-info {
            font-size: 0.75rem;
        }

        .page-link {
            font-size: 0.75rem !important;
            padding: 4px 8px !important;
            min-width: 26px !important;
            height: 26px;
        }

        .logo-container {
            width: 35px;
            height: 35px;
        }
    }
</style>
@endpush

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-users me-2"></i>Daftar Tim
            </h1>
            <p class="page-subtitle">Kelola dan monitor tim peserta HSBL</p>
        </div>
        <div>
            <a href="{{ route('admin.team-list.export') . '?' . http_build_query(request()->query()) }}"
                class="btn export-btn"
                onclick="return confirm('Export data dengan filter saat ini?')">
                <i class="fas fa-file-export me-1"></i>Export Excel
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="filter-header">
            <i class="fas fa-filter me-2"></i>
            <span>Filter & Pencarian</span>
        </div>
        <div class="filter-body">
            <form method="GET" action="{{ route('admin.tv_team_list') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <label class="filter-label">Sekolah</label>
                        <select name="school" id="filter-school" class="form-control form-control-sm">
                            <option value="">Semua Sekolah</option>
                            @foreach($schools as $school)
                            <option value="{{ $school }}" {{ request('school') == $school ? 'selected' : '' }}>
                                {{ Str::limit($school, 18) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <label class="filter-label">Status Verifikasi</label>
                        <select name="status" id="filter-status" class="form-control form-control-sm">
                            <option value="">Semua Status</option>
                            <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Belum Verifikasi</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <label class="filter-label">Kategori</label>
                        <select name="category" id="filter-category" class="form-control form-control-sm">
                            <option value="">Semua Kategori</option>
                            <option value="Basket Putra" {{ request('category') == 'Basket Putra' ? 'selected' : '' }}>Basket Putra</option>
                            <option value="Basket Putri" {{ request('category') == 'Basket Putri' ? 'selected' : '' }}>Basket Putri</option>
                            <option value="Dancer" {{ request('category') == 'Dancer' ? 'selected' : '' }}>Dancer</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <label class="filter-label">Tahun</label>
                        <select name="year" id="filter-year" class="form-control form-control-sm">
                            <option value="">Semua Tahun</option>
                            @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <label class="filter-label">Kompetisi</label>
                        <select name="competition" id="filter-competition" class="form-control form-control-sm">
                            <option value="">Semua Kompetisi</option>
                            @foreach($competitions as $comp)
                            <option value="{{ $comp }}" {{ request('competition') == $comp ? 'selected' : '' }}>
                                {{ Str::limit($comp, 18) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <label class="filter-label">Status Kunci</label>
                        <select name="locked" id="filter-locked" class="form-control form-control-sm">
                            <option value="">Semua</option>
                            <option value="locked" {{ request('locked') == 'locked' ? 'selected' : '' }}>Terkunci</option>
                            <option value="unlocked" {{ request('locked') == 'unlocked' ? 'selected' : '' }}>Terbuka</option>
                        </select>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12">
                        <label class="filter-label">Pencarian</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text"
                                name="search"
                                id="filter-search"
                                class="form-control border-start-0"
                                placeholder="Cari sekolah, tim, atau kompetisi..."
                                value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <label class="filter-label">Urutkan</label>
                        <select name="sort" id="filter-sort" class="form-control form-control-sm">
                            <option value="updated_at" {{ request('sort', 'updated_at') == 'updated_at' ? 'selected' : '' }}>Update Terbaru</option>
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Pendaftaran</option>
                            <option value="school_name" {{ request('sort') == 'school_name' ? 'selected' : '' }}>Nama Sekolah</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <label class="filter-label">Urutan</label>
                        <select name="order" id="filter-order" class="form-control form-control-sm">
                            <option value="desc" {{ request('order', 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                            <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-6 col-md-6 col-sm-12">
                        <label class="filter-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn-filter-submit">
                                <i class="fas fa-search me-1"></i>
                                <span>Cari</span>
                            </button>
                            <button type="button" onclick="resetFilter()" class="btn-reset">
                                <i class="fas fa-undo-alt me-1"></i>
                                <span>Reset</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="admin-card">
        <div class="card-header">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-table"></i>
                <span>Data Tim Terdaftar</span>
            </div>
            <div class="badge bg-white text-primary rounded-pill px-3 py-2 fw-semibold">
                <i class="fas fa-database me-2"></i>
                {{ $teamList->total() }} Tim
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>LOGO</th>
                            <th>TEAM<br>NO</th>
                            <th>SCHOOL</th>
                            <th>TEAM<br>NAME</th>
                            <th>COMPETITION</th>
                            <th>REG<br>BY</th>
                            <th>LOCK<br>STATUS</th>
                            <th>VERIFY<br>STATUS</th>
                            <th>UPDATED</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teamList as $team)
                        <tr>
                            <!-- Logo Sekolah - FIXED -->
                            <td>
                                @if(!empty($team->school_logo))
                                <div class="logo-container" onclick="showLogoPopup('{{ asset('storage/' . $team->school_logo) }}', '{{ addslashes($team->school_name) }}')">
                                    <img src="{{ asset('storage/' . $team->school_logo) }}"
                                        alt="Logo {{ $team->school_name }}"
                                        class="logo-img"
                                        onerror="this.onerror=null; this.parentElement.innerHTML = '<div class=\'logo-placeholder\'><i class=\'fas fa-school\'></i><span>No Logo</span></div>'">
                                </div>
                                @else
                                <div class="logo-container" onclick="showLogoPopup(null, '{{ addslashes($team->school_name) }}')">
                                    <div class="logo-placeholder">
                                        <i class="fas fa-school"></i>
                                        <span>No Logo</span>
                                    </div>
                                </div>
                                @endif
                            </td>

                            <!-- Team Number -->
                            <td>
                                <span class="team-number-badge" title="TEA{{ str_pad($team->team_id ?? $team->id, 7, '0', STR_PAD_LEFT) }}">
                                    {{ str_pad($team->team_id ?? $team->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>

                            <!-- School -->
                            <td>
                                <div class="school-info">
                                    <div class="school-name" title="{{ $team->school_name ?? '' }}">
                                        {{ Str::limit($team->school_name ?? '-', 25) }}
                                    </div>
                                    @if(!empty($team->season))
                                    <div class="text-small">
                                        <i class="fas fa-calendar-alt"></i>{{ Str::limit($team->season, 12) }}
                                    </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Team Name -->
                            <td>
                                <div class="team-meta">
                                    <div class="team-name" title="{{ $team->team_name ?? '' }}">
                                        {{ Str::limit($team->team_name ?? '-', 20) }}
                                    </div>
                                </div>
                            </td>

                            <!-- Competition -->
                            <td>
                                <div class="competition-info">
                                    <div class="competition-name" title="{{ $team->competition ?? '' }}">
                                        {{ Str::limit($team->competition ?? '-', 28) }}
                                    </div>
                                    @if(!empty($team->series))
                                    <div class="text-small">
                                        <i class="fas fa-layer-group"></i>{{ Str::limit($team->series, 15) }}
                                    </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Registered By -->
                            <td>
                                <div class="registrant-info">
                                    <div class="registrant-name" title="{{ $team->registered_by ?? '' }}">
                                        {{ Str::limit($team->registered_by ?? '-', 15) }}
                                    </div>
                                    @if(!empty($team->referral_code))
                                    <div class="text-small">
                                        <i class="fas fa-hashtag"></i>{{ Str::limit($team->referral_code, 8) }}
                                    </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Lock Status -->
                            <td>
                                @if(($team->locked_status ?? '') == 'locked')
                                <span class="status-badge badge-locked" title="LOCKED">
                                    <i class="fas fa-lock"></i> LOCKED
                                </span>
                                @else
                                <span class="status-badge badge-unlocked" title="UNLOCKED">
                                    <i class="fas fa-unlock"></i> OPEN
                                </span>
                                @endif
                            </td>

                            <!-- Verify Status -->
                            <td>
                                @if(($team->verification_status ?? '') == 'verified')
                                <span class="status-badge badge-verified" title="VERIFIED">
                                    <i class="fas fa-check-circle"></i> VERIFIED
                                </span>
                                @else
                                <span class="status-badge badge-unverified" title="UNVERIFIED">
                                    <i class="fas fa-clock"></i> PENDING
                                </span>
                                @endif
                            </td>

                            <!-- Updated At -->
                            <td class="date-cell">
                                @if(!empty($team->updated_at))
                                <div class="date-primary">{{ $team->updated_at instanceof \Carbon\Carbon ? $team->updated_at->format('d/m/Y') : date('d/m/Y', strtotime($team->updated_at)) }}</div>
                                <div class="date-secondary">{{ $team->updated_at instanceof \Carbon\Carbon ? $team->updated_at->format('H:i') : date('H:i', strtotime($team->updated_at)) }}</div>
                                @else
                                <div class="date-primary">-</div>
                                @endif
                            </td>

                            <!-- Action -->
                            <td>
                                <div class="action-buttons">
                                    <!-- View -->
                                    <a href="{{ route('admin.team-list.show', $team->team_id ?? $team->id) }}"
                                        class="btn-action btn-view"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Verify/Unverify -->
                                    @if(($team->verification_status ?? '') == 'unverified')
                                    <form action="{{ route('admin.team.verify', $team->team_id ?? $team->id) }}"
                                        method="POST"
                                        class="d-inline verify-form"
                                        data-team-name="{{ addslashes($team->school_name ?? 'Tim') }}">
                                        @csrf
                                        <button type="submit"
                                            class="btn-action btn-verify"
                                            title="Verifikasi Tim">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.team.unverify', $team->team_id ?? $team->id) }}"
                                        method="POST"
                                        class="d-inline unverify-form"
                                        data-team-name="{{ addslashes($team->school_name ?? 'Tim') }}">
                                        @csrf
                                        <button type="submit"
                                            class="btn-action btn-unverify"
                                            title="Batalkan Verifikasi">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-users-slash"></i>
                                    </div>
                                    <h5 class="empty-title">Tidak ada data tim ditemukan</h5>
                                    <p class="empty-text">
                                        Coba sesuaikan filter pencarian atau tunggu pendaftaran dari peserta.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($teamList->hasPages())
            <div class="pagination-container">
                <div class="pagination-info">
                    <i class="fas fa-info-circle me-2"></i>
                    @if($teamList->total() > 0)
                    Menampilkan <strong>{{ $teamList->firstItem() }} - {{ $teamList->lastItem() }}</strong>
                    dari <strong>{{ $teamList->total() }}</strong> data
                    @else
                    Tidak ada data yang ditampilkan
                    @endif
                </div>

                <nav>
                    <ul class="custom-pagination">
                        {{-- Previous Page Link --}}
                        <li class="page-item {{ $teamList->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ $teamList->onFirstPage() ? '#' : $teamList->previousPageUrl() }}"
                                aria-label="Previous">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>

                        {{-- Page Numbers --}}
                        @php
                        $currentPage = $teamList->currentPage();
                        $lastPage = $teamList->lastPage();
                        $startPage = max($currentPage - 2, 1);
                        $endPage = min($currentPage + 2, $lastPage);
                        @endphp

                        {{-- First page --}}
                        @if($startPage > 1)
                        <li class="page-item">
                            <a class="page-link" href="{{ $teamList->url(1) }}">1</a>
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
                            <a class="page-link" href="{{ $teamList->url($i) }}">{{ $i }}</a>
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
                                    <a class="page-link" href="{{ $teamList->url($lastPage) }}">{{ $lastPage }}</a>
                                </li>
                                @endif

                                {{-- Next Page Link --}}
                                <li class="page-item {{ !$teamList->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link"
                                        href="{{ !$teamList->hasMorePages() ? '#' : $teamList->nextPageUrl() }}"
                                        aria-label="Next">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                    </ul>
                </nav>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Function to reset filter - BENAR-BENAR RESET KE KOSONG
    function resetFilter() {
        Swal.fire({
            title: 'Reset Filter',
            text: 'Reset semua filter ke default? Semua data akan ditampilkan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#667eea',
            cancelButtonColor: '#718096',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Dapatkan form
                const form = document.getElementById('filterForm');

                // Reset semua select ke value pertama (biasanya value="")
                const selects = form.querySelectorAll('select');
                selects.forEach(select => {
                    select.value = ''; // Set ke value kosong
                });

                // Set default untuk sort dan order
                const sortSelect = form.querySelector('select[name="sort"]');
                if (sortSelect) sortSelect.value = 'updated_at';

                const orderSelect = form.querySelector('select[name="order"]');
                if (orderSelect) orderSelect.value = 'desc';

                // Kosongkan semua input text
                const inputs = form.querySelectorAll('input[type="text"]');
                inputs.forEach(input => {
                    input.value = '';
                });

                // Submit form
                form.submit();
            }
        });
    }

    // Alternative: Hard reset dengan redirect ke URL tanpa parameter
    function hardResetFilter() {
        Swal.fire({
            title: 'Reset Filter',
            text: 'Reset semua filter ke default? Semua data akan ditampilkan.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#667eea',
            cancelButtonColor: '#718096',
            confirmButtonText: 'Ya, Reset!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Redirect ke URL tanpa query parameters
                window.location.href = "{{ route('admin.tv_team_list') }}";
            }
        });
    }

    // Function to show logo popup
    function showLogoPopup(logoUrl, schoolName) {
        let htmlContent;

        if (logoUrl) {
            htmlContent = `
                <div style="text-align: center;">
                    <img src="${logoUrl}" alt="Logo ${schoolName}" style="max-width: 250px; max-height: 250px; border-radius: 8px; border: 2px solid #e2e8f0; padding: 8px; background: white;">
                    <p style="color: #2d3748; font-size: 16px; font-weight: 600; margin-top: 15px;">${schoolName}</p>
                </div>
            `;
        } else {
            htmlContent = `
                <div style="text-align: center;">
                    <div style="width: 150px; height: 150px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #f7fafc, #edf2f7); border: 2px dashed #cbd5e0; border-radius: 8px; margin: 0 auto 15px;">
                        <i class="fas fa-school" style="font-size: 3rem; color: #a0aec0; margin-bottom: 10px;"></i>
                        <span style="color: #718096; font-size: 14px;">Logo Tidak Tersedia</span>
                    </div>
                    <p style="color: #2d3748; font-size: 16px; font-weight: 600;">${schoolName}</p>
                </div>
            `;
        }

        Swal.fire({
            title: 'Logo Sekolah',
            html: htmlContent,
            showCloseButton: true,
            showConfirmButton: false,
            width: 400,
            padding: '25px',
            background: '#fff',
            customClass: {
                popup: 'logo-popup'
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit untuk filter SELECT (kecuali sort dan order)
        const filterSelects = document.querySelectorAll('select:not([name="sort"]):not([name="order"])');
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });

        // Confirmation for verify actions with SweetAlert
        const verifyForms = document.querySelectorAll('.verify-form, .unverify-form');
        verifyForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const teamName = this.dataset.teamName || 'Tim';
                const isVerify = this.classList.contains('verify-form');
                const action = isVerify ? 'verifikasi' : 'batalkan verifikasi';

                Swal.fire({
                    title: 'Konfirmasi',
                    text: `Apakah Anda yakin ingin ${action} tim ${teamName}?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: isVerify ? '#48bb78' : '#ed8936',
                    cancelButtonColor: '#718096',
                    confirmButtonText: `Ya, ${isVerify ? 'Verifikasi' : 'Batalkan'}!`,
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Check for any broken images and fix them
        const logoImages = document.querySelectorAll('.logo-img');
        logoImages.forEach(img => {
            img.addEventListener('error', function() {
                const container = this.parentElement;
                container.innerHTML = `<div class="logo-placeholder">
                    <i class="fas fa-school"></i>
                    <span>No Logo</span>
                </div>`;
            });
        });

        // Responsive table adjustments
        function checkTableWidth() {
            const screenWidth = window.innerWidth;
            const container = document.querySelector('.table-container');

            if (container) {
                if (screenWidth < 1200) {
                    container.style.overflowX = 'auto';
                } else {
                    container.style.overflowX = 'hidden';
                }
            }
        }

        window.addEventListener('resize', checkTableWidth);
        checkTableWidth();

        // Tambahkan tombol reset yang lebih jelas
        console.log('Filter reset function ready');
    });
</script>

<style>
    /* Additional styles for logo popup */
    .logo-popup {
        border-radius: 12px !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
    }

    .swal2-close {
        color: #718096 !important;
        font-size: 24px !important;
    }

    .swal2-close:hover {
        color: #4a5568 !important;
    }

    /* Fix for logo container */
    .logo-container {
        position: relative;
        overflow: hidden;
    }

    .logo-img {
        transition: transform 0.3s ease;
    }

    .logo-container:hover .logo-img {
        transform: scale(1.1);
    }

    /* Style untuk button submit dan reset */
    .btn-filter-submit,
    .btn-reset {
        transition: all 0.3s ease;
    }

    .btn-filter-submit i,
    .btn-reset i {
        font-size: 0.85rem;
    }

    @media (max-width: 576px) {

        .btn-filter-submit,
        .btn-reset {
            padding: 8px 12px;
            font-size: 0.8rem;
        }
    }

    /* ===== RESPONSIVE FIX - SAMA DENGAN MASTER DATA ===== */
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
        .container-fluid.py-4 {
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
        .row>[class*="col-"] {
            padding-left: 3px !important;
            padding-right: 3px !important;
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }

        /* Form inputs jadi full width */
        .filter-body .row.g-3>[class*="col-"] {
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
            padding-left: 3px !important;
            padding-right: 3px !important;
        }

        /* Header flex untuk mobile */
        .d-flex.justify-content-between {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 10px !important;
            margin-bottom: 1rem !important;
        }

        .export-btn {
            width: 100% !important;
            justify-content: center;
        }

        /* Card styling */
        .card-header {
            padding: 10px 12px !important;
            font-size: 0.9rem;
        }

        .filter-body {
            padding: 10px !important;
        }

        .filter-header {
            padding: 10px 12px !important;
        }

        /* Filter buttons */
        .d-flex.gap-2 {
            width: 100%;
            display: flex;
            gap: 8px;
        }

        .btn-filter-submit,
        .btn-reset {
            flex: 1;
            font-size: 0.8rem;
            padding: 8px 12px;
        }

        /* Table styling */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding: 3px !important;
        }

        .table {
            min-width: 900px;
            /* Biar bisa scroll horizontal */
            font-size: 0.8rem;
        }

        .table th {
            padding: 8px 6px !important;
            font-size: 0.7rem !important;
        }

        .table td {
            padding: 8px 6px !important;
            font-size: 0.75rem !important;
        }

        .page-title {
            font-size: 1.1rem;
        }

        .page-subtitle {
            font-size: 0.75rem;
        }

        /* Logo container */
        .logo-container {
            width: 40px;
            height: 40px;
        }

        .logo-placeholder i {
            font-size: 1rem;
        }

        .team-number-badge {
            font-size: 0.7rem;
            min-width: 40px;
            padding: 3px 4px;
        }

        .school-name,
        .team-name,
        .competition-name,
        .registrant-name {
            font-size: 0.75rem;
        }

        .text-small {
            font-size: 0.6rem !important;
        }

        .status-badge {
            font-size: 0.6rem !important;
            min-width: 60px;
            height: 22px;
            padding: 3px 6px !important;
        }

        .date-primary {
            font-size: 0.7rem;
        }

        .date-secondary {
            font-size: 0.6rem;
        }

        .btn-action {
            width: 26px;
            height: 26px;
            font-size: 0.7rem;
            padding: 4px 6px;
        }

        .action-buttons {
            gap: 3px;
        }

        /* Pagination */
        .pagination-container {
            flex-direction: column;
            gap: 10px;
            text-align: center;
            padding: 12px 15px !important;
        }

        .pagination-info {
            font-size: 0.75rem;
        }

        .custom-pagination {
            flex-wrap: wrap;
            justify-content: center;
        }

        .page-link {
            font-size: 0.7rem !important;
            padding: 4px 8px !important;
            min-width: 28px !important;
            height: 28px;
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

        .container-fluid.py-4 {
            padding-left: 2px !important;
            padding-right: 2px !important;
        }

        .table {
            min-width: 850px;
            font-size: 0.75rem;
        }

        .table th {
            padding: 6px 4px !important;
            font-size: 0.65rem !important;
        }

        .table td {
            padding: 6px 4px !important;
            font-size: 0.7rem !important;
        }

        .page-title {
            font-size: 1rem;
        }

        .logo-container {
            width: 35px;
            height: 35px;
        }

        .btn-action {
            width: 24px;
            height: 24px;
            font-size: 0.65rem;
        }

        .status-badge {
            font-size: 0.55rem !important;
            min-width: 55px;
            height: 20px;
        }

        .page-link {
            font-size: 0.65rem !important;
            padding: 3px 6px !important;
            min-width: 26px !important;
            height: 26px;
        }

        .pagination-info {
            font-size: 0.7rem;
        }

        .btn-filter-submit,
        .btn-reset {
            font-size: 0.75rem;
            padding: 6px 10px;
        }
    }
</style>
@endpush
@endsection