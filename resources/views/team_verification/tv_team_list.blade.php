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

    /* ===== TABEL YANG LEGA & NYAMAN ===== */
    .table-container {
        overflow-x: auto;
        border-radius: 0 0 10px 10px;
        -webkit-overflow-scrolling: touch;
        padding: 5px;
    }

    .table {
        width: 100% !important;
        font-size: 0.87rem;
        margin-bottom: 0;
        color: #4a5568;
        border-collapse: separate;
        border-spacing: 0;
        table-layout: fixed;
    }

    /* HEADER - LEGA & JELAS */
    .table th {
        text-align: center !important;
        vertical-align: middle !important;
        font-weight: 700 !important;
        color: #2d3748 !important;
        padding: 14px 10px !important;
        background: #f8fafc !important;
        font-size: 0.8rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.3px !important;
        border-bottom: 2px solid #e2e8f0 !important;
        white-space: normal !important;
        line-height: 1.3 !important;
        word-break: break-word;
        height: 55px;
    }

    /* CELL - LEGA & RAPI */
    .table td {
        padding: 12px 10px !important;
        vertical-align: middle !important;
        border-top: 1px solid #f7fafc !important;
        line-height: 1.4 !important;
        min-height: 65px;
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

    /* ===== LEBAR KOLOM OPTIMAL ===== */
    /* Total: 120 + 190 + 160 + 210 + 140 + 105 + 105 + 140 + 110 = ~1280px */
    
    /* Team Number - Lega */
    .table th:nth-child(1),
    .table td:nth-child(1) {
        width: 120px !important;
        min-width: 120px !important;
        max-width: 120px !important;
        text-align: center !important;
    }

    /* School - Perbesar (penting) */
    .table th:nth-child(2),
    .table td:nth-child(2) {
        width: 190px !important;
        min-width: 190px !important;
        max-width: 190px !important;
        text-align: left !important;
    }

    /* Team Name - Lega */
    .table th:nth-child(3),
    .table td:nth-child(3) {
        width: 160px !important;
        min-width: 160px !important;
        max-width: 160px !important;
        text-align: left !important;
    }

    /* Competition - Perbesar (penting) */
    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 210px !important;
        min-width: 210px !important;
        max-width: 210px !important;
        text-align: left !important;
    }

    /* Registered By - Lega */
    .table th:nth-child(5),
    .table td:nth-child(5) {
        width: 140px !important;
        min-width: 140px !important;
        max-width: 140px !important;
        text-align: center !important;
    }

    /* Lock Status - Cukup */
    .table th:nth-child(6),
    .table td:nth-child(6) {
        width: 105px !important;
        min-width: 105px !important;
        max-width: 105px !important;
        text-align: center !important;
    }

    /* Verify Status - Cukup */
    .table th:nth-child(7),
    .table td:nth-child(7) {
        width: 105px !important;
        min-width: 105px !important;
        max-width: 105px !important;
        text-align: center !important;
    }

    /* Updated At - Lega */
    .table th:nth-child(8),
    .table td:nth-child(8) {
        width: 140px !important;
        min-width: 140px !important;
        max-width: 140px !important;
        text-align: center !important;
    }

    /* Action - Lega untuk button */
    .table th:nth-child(9),
    .table td:nth-child(9) {
        width: 110px !important;
        min-width: 110px !important;
        max-width: 110px !important;
        text-align: center !important;
    }

    /* ===== STYLING KONTEN YANG NYAMAN ===== */
    .team-number-badge {
        font-family: 'SF Mono', 'Courier New', monospace;
        font-size: 0.85rem;
        font-weight: 700;
        color: #2c3e50;
        background: #f8f9fa;
        padding: 7px 12px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        display: inline-block;
        text-align: center;
        min-width: 90px;
        letter-spacing: 0.3px;
        line-height: 1.3;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .school-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .school-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 0.87rem;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-height: 2.8em;
    }

    .team-meta {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .team-name {
        font-size: 0.87rem;
        color: #4a5568;
        line-height: 1.4;
        font-weight: 500;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-height: 2.8em;
    }

    .team-category {
        font-size: 0.75rem;
        color: #718096;
        background: #f1f5f9;
        padding: 3px 8px;
        border-radius: 4px;
        display: inline-block;
        width: fit-content;
    }

    .competition-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .competition-name {
        font-size: 0.87rem;
        color: #4a5568;
        font-weight: 500;
        line-height: 1.4;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-height: 4.2em;
    }

    .registrant-info {
        text-align: center;
    }

    .registrant-name {
        font-size: 0.87rem;
        color: #4a5568;
        font-weight: 500;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        max-height: 2.8em;
    }

    /* ===== BADGE STATUS YANG JELAS ===== */
    .status-badge {
        padding: 6px 12px !important;
        font-size: 0.73rem !important;
        font-weight: 700 !important;
        border-radius: 8px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 5px !important;
        text-transform: uppercase;
        min-width: 85px;
        height: 28px;
        border: 1px solid transparent;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        white-space: nowrap;
        transition: all 0.2s;
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

    /* ===== ACTION BUTTONS YANG NYAMAN ===== */
    .action-buttons {
        display: flex;
        gap: 8px;
        flex-wrap: nowrap;
        justify-content: center;
    }

    .btn-action {
        padding: 7px 10px;
        font-size: 0.82rem;
        border-radius: 6px;
        line-height: 1;
        min-width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: none;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        cursor: pointer;
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
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
        font-size: 0.85rem;
    }

    .date-primary {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .date-secondary {
        font-size: 0.75rem;
        color: #718096;
    }

    /* ===== PAGINATION ===== */
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
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s;
        box-shadow: 0 2px 6px rgba(32, 191, 107, 0.2);
        text-decoration: none;
    }

    .export-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(32, 191, 107, 0.3);
        color: white;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-icon {
        font-size: 3.5rem;
        color: #cbd5e0;
        margin-bottom: 20px;
        opacity: 0.4;
    }

    .empty-title {
        font-size: 1.2rem;
        color: #718096;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .empty-text {
        color: #a0aec0;
        font-size: 0.9rem;
        max-width: 400px;
        margin: 0 auto;
        line-height: 1.5;
    }

    /* ===== INFO SEKUNDER ===== */
    .text-small {
        font-size: 0.72rem !important;
        line-height: 1.2 !important;
        color: #718096;
        opacity: 0.8;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* ===== RESPONSIVE ADJUSTMENTS ===== */
    @media (max-width: 1600px) {
        /* Sedikit lebih kecil untuk 1600px */
        .table th:nth-child(2),
        .table td:nth-child(2) { width: 180px !important; }
        .table th:nth-child(4),
        .table td:nth-child(4) { width: 200px !important; }
    }

    @media (max-width: 1440px) {
        /* Untuk 1440px masih nyaman */
        .table th:nth-child(2),
        .table td:nth-child(2) { width: 175px !important; }
        .table th:nth-child(4),
        .table td:nth-child(4) { width: 190px !important; }
        .table th:nth-child(5),
        .table td:nth-child(5) { width: 135px !important; }
    }

    @media (max-width: 1366px) {
        /* Aktifkan scroll di 1366px ke bawah */
        .table-container {
            overflow-x: auto;
        }
        
        .table {
            min-width: 1280px !important;
        }
        
        /* Sembunyikan info sekunder */
        .text-small {
            display: none;
        }
        
        .school-name,
        .team-name,
        .competition-name,
        .registrant-name {
            -webkit-line-clamp: 2;
            max-height: 2.8em;
        }
    }

    @media (max-width: 1200px) {
        .container-fluid.py-4 {
            padding-left: 0.75rem !important;
            padding-right: 0.75rem !important;
        }
        
        .table th,
        .table td {
            padding: 11px 8px !important;
            font-size: 0.85rem !important;
        }
        
        .team-number-badge {
            font-size: 0.82rem;
            padding: 6px 10px;
            min-width: 85px;
        }
        
        .status-badge {
            min-width: 80px !important;
            max-width: 80px !important;
            font-size: 0.7rem !important;
            padding: 5px 10px !important;
        }
        
        .btn-action {
            min-width: 30px;
            height: 30px;
            padding: 6px 9px;
        }
    }

    @media (max-width: 992px) {
        .filter-body {
            padding: 20px;
        }
        
        .filter-header,
        .card-header {
            padding: 14px 20px;
        }
        
        .pagination-container {
            flex-direction: column;
            gap: 12px;
            text-align: center;
            padding: 14px 20px !important;
        }
        
        .table th,
        .table td {
            padding: 10px 6px !important;
        }
    }

    @media (max-width: 768px) {
        .container-fluid.py-4 {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        
        .page-title {
            font-size: 1.3rem;
        }
        
        .export-btn {
            padding: 8px 16px;
            font-size: 0.85rem;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 15px;
        }
    }

    @media (max-width: 576px) {
        .filter-body {
            padding: 16px;
        }
        
        .export-btn {
            width: 100%;
            justify-content: center;
        }
        
        .pagination-info {
            font-size: 0.8rem;
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
                        <select name="school" class="form-control form-control-sm">
                            <option value="">Semua Sekolah</option>
                            @foreach($schools as $school)
                            <option value="{{ $school }}" {{ request('school') == $school ? 'selected' : '' }}>
                                {{ Str::limit($school, 22) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <label class="filter-label">Status Verifikasi</label>
                        <select name="status" class="form-control form-control-sm">
                            <option value="">Semua Status</option>
                            <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>Belum Verifikasi</option>
                            <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <label class="filter-label">Kategori</label>
                        <select name="category" class="form-control form-control-sm">
                            <option value="">Semua Kategori</option>
                            <option value="Basket Putra" {{ request('category') == 'Basket Putra' ? 'selected' : '' }}>Basket Putra</option>
                            <option value="Basket Putri" {{ request('category') == 'Basket Putri' ? 'selected' : '' }}>Basket Putri</option>
                            <option value="Dancer" {{ request('category') == 'Dancer' ? 'selected' : '' }}>Dancer</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <label class="filter-label">Tahun</label>
                        <select name="year" class="form-control form-control-sm">
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
                        <select name="competition" class="form-control form-control-sm">
                            <option value="">Semua Kompetisi</option>
                            @foreach($competitions as $comp)
                            <option value="{{ $comp }}" {{ request('competition') == $comp ? 'selected' : '' }}>
                                {{ Str::limit($comp, 20) }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <label class="filter-label">Status Kunci</label>
                        <select name="locked" class="form-control form-control-sm">
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
                                class="form-control border-start-0"
                                placeholder="Cari sekolah, tim, atau kompetisi..."
                                value="{{ request('search') }}"
                                onkeypress="if(event.keyCode==13) this.form.submit()">
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <label class="filter-label">Urutkan</label>
                        <select name="sort" class="form-control form-control-sm">
                            <option value="updated_at" {{ request('sort', 'updated_at') == 'updated_at' ? 'selected' : '' }}>Update Terbaru</option>
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Pendaftaran</option>
                            <option value="school_name" {{ request('sort') == 'school_name' ? 'selected' : '' }}>Nama Sekolah</option>
                            <option value="team_category" {{ request('sort') == 'team_category' ? 'selected' : '' }}>Kategori</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <label class="filter-label">Urutan</label>
                        <select name="order" class="form-control form-control-sm">
                            <option value="desc" {{ request('order', 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                            <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        </select>
                    </div>

                    <div class="col-xl-1 col-lg-12 col-md-12 col-sm-12">
                        <div class="d-flex h-100 align-items-end">
                            <button type="button" 
                                onclick="document.getElementById('filterForm').reset(); document.getElementById('filterForm').submit();"
                                class="btn btn-outline-secondary btn-sm w-100 d-flex align-items-center justify-content-center gap-2"
                                style="height: 38px;"
                                title="Reset semua filter">
                                <i class="fas fa-redo"></i>
                                <span class="d-none d-md-inline">Reset</span>
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
                            <th>TEAM<br>NUMBER</th>
                            <th>SCHOOL</th>
                            <th>TEAM<br>NAME</th>
                            <th>COMPETITION</th>
                            <th>REGISTERED<br>BY</th>
                            <th>LOCK<br>STATUS</th>
                            <th>VERIFY<br>STATUS</th>
                            <th>UPDATED<br>AT</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teamList as $team)
                        <tr>
                            <!-- Team Number -->
                            <td>
                                <span class="team-number-badge" title="TEA{{ str_pad($team->team_id, 7, '0', STR_PAD_LEFT) }}">
                                    TEA{{ str_pad($team->team_id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>

                            <!-- School -->
                            <td>
                                <div class="school-info">
                                    <div class="school-name" title="{{ $team->school_name }}">
                                        {{ Str::limit($team->school_name, 35) }}
                                    </div>
                                    @if($team->season)
                                    <div class="text-small">
                                        <i class="fas fa-calendar-alt"></i>{{ $team->season }}
                                    </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Team Name -->
                            <td>
                                <div class="team-meta">
                                    <div class="team-name" title="{{ $team->school_name }}">
                                        {{ Str::limit($team->school_name, 30) }}
                                    </div>
                                    @if($team->team_category)
                                    <span class="team-category">
                                        {{ $team->team_category }}
                                    </span>
                                    @endif
                                </div>
                            </td>

                            <!-- Competition -->
                            <td>
                                <div class="competition-info">
                                    <div class="competition-name" title="{{ $team->competition }}">
                                        {{ Str::limit($team->competition, 40) }}
                                    </div>
                                    @if($team->series)
                                    <div class="text-small">
                                        <i class="fas fa-layer-group"></i>{{ Str::limit($team->series, 20) }}
                                    </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Registered By -->
                            <td>
                                <div class="registrant-info">
                                    <div class="registrant-name" title="{{ $team->registered_by }}">
                                        {{ Str::limit($team->registered_by, 20) }}
                                    </div>
                                    @if($team->referral_code)
                                    <div class="text-small">
                                        <i class="fas fa-hashtag"></i>{{ Str::limit($team->referral_code, 12) }}
                                    </div>
                                    @endif
                                </div>
                            </td>

                            <!-- Lock Status -->
                            <td>
                                @if($team->locked_status == 'locked')
                                <span class="status-badge badge-locked" title="LOCKED">
                                    <i class="fas fa-lock me-1"></i>LOCKED
                                </span>
                                @else
                                <span class="status-badge badge-unlocked" title="UNLOCKED">
                                    <i class="fas fa-unlock me-1"></i>UNLOCKED
                                </span>
                                @endif
                            </td>

                            <!-- Verify Status -->
                            <td>
                                @if($team->verification_status == 'verified')
                                <span class="status-badge badge-verified" title="VERIFIED">
                                    <i class="fas fa-check-circle me-1"></i>VERIFIED
                                </span>
                                @else
                                <span class="status-badge badge-unverified" title="UNVERIFIED">
                                    <i class="fas fa-clock me-1"></i>PENDING
                                </span>
                                @endif
                            </td>

                            <!-- Updated At -->
                            <td class="date-cell">
                                @if($team->updated_at)
                                <div class="date-primary">{{ $team->updated_at->format('d M Y') }}</div>
                                <div class="date-secondary">{{ $team->updated_at->format('H:i') }}</div>
                                @else
                                <div class="date-primary">-</div>
                                @endif
                            </td>

                            <!-- Action -->
                            <td>
                                <div class="action-buttons">
                                    <!-- View -->
                                    <a href="{{ route('admin.team-list.show', $team->team_id) }}"
                                        class="btn-action btn-view"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Verify/Unverify -->
                                    @if($team->verification_status == 'unverified')
                                    <form action="{{ route('admin.team.verify', $team->team_id) }}"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn-action btn-verify"
                                            title="Verifikasi Tim"
                                            onclick="return confirm('Verifikasi tim {{ $team->school_name }}?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.team.unverify', $team->team_id) }}"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn-action btn-unverify"
                                            title="Batalkan Verifikasi"
                                            onclick="return confirm('Batalkan verifikasi tim {{ $team->school_name }}?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9">
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
    document.addEventListener('DOMContentLoaded', function() {
        // Reset filter button
        const resetBtn = document.querySelector('button[onclick*="reset"]');
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                if (confirm('Reset semua filter ke pengaturan default?')) {
                    const form = document.getElementById('filterForm');
                    form.reset();
                    form.submit();
                }
            });
        }

        // Auto-submit for filter changes
        const filterSelects = document.querySelectorAll('select[name]');
        filterSelects.forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });

        // Enter key for search
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    this.form.submit();
                }
            });
        }

        // Confirmation for actions
        const actionForms = document.querySelectorAll('form[action*="verify"], form[action*="unverify"]');
        actionForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!confirm('Apakah Anda yakin?')) {
                    e.preventDefault();
                }
            });
        });

        // Responsive table adjustments
        function checkTableWidth() {
            const screenWidth = window.innerWidth;
            const table = document.querySelector('.table');
            const container = document.querySelector('.table-container');
            
            if (screenWidth < 1366) {
                container.style.overflowX = 'auto';
                if (table) {
                    table.style.minWidth = '1280px';
                }
            } else {
                container.style.overflowX = 'hidden';
                if (table) {
                    table.style.minWidth = '';
                }
            }
        }

        window.addEventListener('resize', checkTableWidth);
        checkTableWidth(); // Initial check
    });
</script>
@endpush
@endsection