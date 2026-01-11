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

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        letter-spacing: -0.5px;
        margin-bottom: 0.25rem;
    }

    .page-subtitle {
        color: #6c757d;
        font-size: 0.9rem;
        font-weight: 400;
    }

    .admin-card {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        margin-bottom: 24px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .admin-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .card-header {
        background: var(--primary-gradient);
        border: none;
        padding: 18px 24px;
        font-weight: 600;
        color: white;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .filter-section {
        background: white;
        border: none;
        border-radius: 12px;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .filter-header {
        background: #f8fafc;
        padding: 16px 24px;
        border-bottom: 1px solid #eaeaea;
        font-size: 0.95rem;
        color: #2c3e50;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .filter-body {
        padding: 24px;
    }

    .filter-label {
        font-size: 0.85rem;
        color: #4a5568;
        margin-bottom: 6px;
        font-weight: 600;
    }

    .form-control-sm {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.9rem;
        padding: 8px 12px;
        height: 38px;
    }

    /* ===== PERBAIKAN TABEL ===== */
    .table-container {
        overflow-x: auto;
        border-radius: 0 0 12px 12px;
    }

    .table {
        font-size: 0.9rem;
        margin-bottom: 0;
        color: #4a5568;
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    /* HEADER RATA TENGAH */
    .table th {
        text-align: center !important;
        vertical-align: middle !important;
        font-weight: 700 !important;
        color: #2d3748 !important;
        padding: 14px 12px !important;
        background: #f8fafc !important;
        font-size: 0.8rem !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
        border-bottom: 2px solid #e2e8f0 !important;
        white-space: nowrap !important;
        position: sticky !important;
        top: 0 !important;
        z-index: 10 !important;
    }

    /* TD ALIGNMENT */
    .table td {
        padding: 12px !important;
        vertical-align: middle !important;
        border-top: 1px solid #f7fafc !important;
    }

    /* Kolom No */
    .table td:nth-child(1) {
        text-align: center !important;
        font-weight: 600 !important;
        color: #6c757d !important;
        width: 50px !important;
    }

    /* Kolom Sekolah */
    .table td:nth-child(2) {
        text-align: left !important;
        min-width: 200px !important;
    }

    /* Kolom Kode Referral */
    .table td:nth-child(3) {
        text-align: center !important;
        min-width: 130px !important;
    }

    /* ===== KATEGORI (Basket Putra/Putri) ===== */
    .table td:nth-child(4) {
        text-align: center !important;
        min-width: 110px !important;
        max-width: 120px !important;
    }

    .category-tag {
        font-size: 0.7rem !important;
        padding: 4px 8px !important;
        border-radius: 6px !important;
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%) !important;
        color: #1565c0 !important;
        font-weight: 700 !important;
        border: 1px solid #90caf9 !important;
        text-transform: uppercase !important;
        display: inline-block !important;
        max-width: 100px !important;
        min-width: 80px !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        text-align: center !important;
        line-height: 1.1 !important;
        height: 24px !important;
    }

    /* Kolom Kompetisi */
    .table td:nth-child(5) {
        text-align: left !important;
        min-width: 200px !important;
        max-width: 250px !important;
    }

    /* Kolom Status Kunci & Verifikasi */
    .table td:nth-child(6),
    .table td:nth-child(7) {
        text-align: center !important;
        min-width: 120px !important;
    }

    /* Kolom Tanggal */
    .table td:nth-child(8) {
        text-align: center !important;
        min-width: 120px !important;
    }

    /* Kolom Aksi */
    .table td:nth-child(9) {
        text-align: center !important;
        min-width: 140px !important;
    }

    /* ===== BADGE STATUS ===== */
    .status-badge {
        padding: 6px 10px !important;
        font-size: 0.7rem !important;
        font-weight: 600 !important;
        border-radius: 20px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        text-transform: uppercase !important;
        min-width: 90px !important;
        max-width: 90px !important;
        height: 26px !important;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1) !important;
        border: 2px solid transparent !important;
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }

    .badge-locked {
        background: var(--danger-gradient);
        color: white;
        border-color: #ff4757;
    }

    .badge-unlocked {
        background: var(--success-gradient);
        color: white;
        border-color: #20bf6b;
    }

    .badge-verified {
        background: var(--success-gradient);
        color: white;
        border-color: #2ecc71;
    }

    .badge-unverified {
        background: #f1f5f9;
        color: #64748b;
        border-color: #cbd5e1;
    }

    .ref-code {
        font-family: monospace;
        font-size: 0.8rem;
        background: #f8fafc;
        padding: 6px 10px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        color: #4a5568;
        font-weight: 600;
        display: inline-block;
    }

    .competition-badge {
        font-size: 0.8rem;
        padding: 6px 10px;
        border-radius: 6px;
        background: #ede7f6;
        color: #5e35b1;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        font-weight: 600;
        border: 1px solid #b39ddb;
    }

    .action-buttons {
        display: flex;
        gap: 6px;
        flex-wrap: nowrap;
        justify-content: center;
    }

    .btn-action {
        padding: 6px 10px;
        font-size: 0.75rem;
        border-radius: 6px;
        line-height: 1;
        min-width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    .btn-view {
        background: var(--primary-gradient);
        color: white;
    }

    .btn-verify {
        background: var(--success-gradient);
        color: white;
    }

    .btn-unverify {
        background: var(--warning-gradient);
        color: white;
    }

    .btn-lock {
        background: var(--danger-gradient);
        color: white;
    }

    .btn-unlock {
        background: var(--info-gradient);
        color: white;
    }

    /* ===== PAGINATION - SELALU MUNCUL ===== */
    .pagination-container {
        background: #f8fafc !important;
        padding: 20px 24px !important;
        border-top: 1px solid #eaeaea !important;
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        min-height: 75px;
    }

    .pagination-info {
        font-size: 0.85rem;
        color: #718096;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .pagination-wrapper {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .custom-pagination {
        display: flex;
        align-items: center;
        gap: 4px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .page-item {
        margin: 0;
    }

    .page-link {
        font-size: 0.85rem !important;
        padding: 8px 12px !important;
        border: 1px solid #e2e8f0 !important;
        color: #4a5568 !important;
        font-weight: 600 !important;
        border-radius: 6px !important;
        min-width: 36px !important;
        text-align: center !important;
        text-decoration: none !important;
        display: block !important;
        transition: all 0.3s ease !important;
    }

    .page-item.active .page-link {
        background: var(--primary-gradient) !important;
        border-color: #667eea !important;
        color: white !important;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3) !important;
        transform: scale(1.05);
    }

    .page-item:not(.active) .page-link:hover {
        background-color: #f1f5f9 !important;
        border-color: #cbd5e0 !important;
        color: #2d3748 !important;
        transform: translateY(-2px) !important;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1) !important;
    }

    .page-item.disabled .page-link {
        background-color: #f8fafc !important;
        color: #cbd5e0 !important;
        border-color: #e2e8f0 !important;
        cursor: not-allowed !important;
    }

    .page-link:focus {
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15) !important;
    }

    .page-ellipsis {
        padding: 8px 4px;
        color: #a0aec0;
        font-weight: 600;
    }

    .export-btn {
        font-size: 0.9rem;
        padding: 10px 20px;
        font-weight: 600;
        border-radius: 8px;
        background: var(--success-gradient);
        border: none;
        color: white;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 15px rgba(32, 191, 107, 0.2);
    }

    .export-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(32, 191, 107, 0.3);
        color: white;
    }

    /* Empty State */
    .empty-state {
        padding: 80px 20px;
        text-align: center;
    }

    .empty-icon {
        font-size: 4rem;
        color: #cbd5e0;
        margin-bottom: 20px;
        opacity: 0.3;
    }

    .empty-title {
        font-size: 1.3rem;
        color: #718096;
        margin-bottom: 12px;
        font-weight: 700;
    }

    .empty-text {
        color: #a0aec0;
        font-size: 1rem;
        max-width: 400px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 0 16px;
        }

        .action-buttons {
            flex-direction: column;
            gap: 4px;
            width: 100%;
        }

        .btn-action {
            width: 100%;
            padding: 8px;
        }

        .table th,
        .table td {
            padding: 10px 8px !important;
            font-size: 0.8rem !important;
        }

        .category-tag {
            font-size: 0.65rem !important;
            padding: 3px 6px !important;
            min-width: 70px !important;
        }

        .pagination-container {
            flex-direction: column;
            gap: 15px;
            text-align: center;
            padding: 16px !important;
        }

        .pagination-wrapper {
            flex-direction: column;
            gap: 10px;
        }

        .custom-pagination {
            flex-wrap: wrap;
            justify-content: center;
        }

        .page-link {
            font-size: 0.8rem !important;
            padding: 6px 10px !important;
            min-width: 32px !important;
        }
    }
</style>
@endpush

<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <div>
            <h1 class="page-title mb-2">
                <i class="fas fa-users me-2"></i>Daftar Tim
            </h1>
            <p class="page-subtitle">Kelola dan monitor tim peserta HSBL</p>
        </div>
        <div class="d-flex gap-2">
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
            <i class="fas fa-sliders-h me-2"></i>
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
                                {{ $school }}
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
                        <label class="filter-label">Kategori Tim</label>
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
                                {{ Str::limit($comp, 25) }}
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

                    <div class="col-xl-4 col-lg-6 col-md-8 col-sm-12">
                        <label class="filter-label">Pencarian Cepat</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text"
                                name="search"
                                class="form-control form-control-sm border-start-0"
                                placeholder="Cari sekolah, kode referral, atau kompetisi..."
                                value="{{ request('search') }}">
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <label class="filter-label">Urutkan Berdasarkan</label>
                        <select name="sort" class="form-control form-control-sm">
                            <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Tanggal Daftar</option>
                            <option value="school_name" {{ request('sort') == 'school_name' ? 'selected' : '' }}>Nama Sekolah</option>
                            <option value="team_category" {{ request('sort') == 'team_category' ? 'selected' : '' }}>Kategori</option>
                            <option value="verification_status" {{ request('sort') == 'verification_status' ? 'selected' : '' }}>Status Verifikasi</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <label class="filter-label">Urutan</label>
                        <select name="order" class="form-control form-control-sm">
                            <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Terbaru</option>
                            <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Terlama</option>
                        </select>
                    </div>

                    <div class="col-xl-2 col-lg-12 col-md-12 col-sm-12">
                        <div class="d-flex gap-2 h-100 align-items-end">
                            <button type="submit" class="btn btn-primary btn-sm w-100 d-flex align-items-center justify-content-center gap-2" style="height: 38px;">
                                <i class="fas fa-filter"></i>
                                <span>Filter</span>
                            </button>
                            <a href="{{ route('admin.tv_team_list') }}" class="btn btn-outline-secondary btn-sm w-100 d-flex align-items-center justify-content-center gap-2" style="height: 38px;">
                                <i class="fas fa-redo"></i>
                                <span>Reset</span>
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Table -->
    <div class="admin-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-3">
                <i class="fas fa-table"></i>
                <span>Data Tim Terdaftar</span>
            </div>
            <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-semibold">
                <i class="fas fa-database me-2"></i>
                {{ $teamList->total() }} Data
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Sekolah & Season</th>
                            <th>Kode Referral</th>
                            <th>Kategori</th>
                            <th>Kompetisi</th>
                            <th>Status Kunci</th>
                            <th>Verifikasi</th>
                            <th>Tanggal Daftar</th>
                            <th width="140" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teamList as $index => $team)
                        <tr>
                            <td>{{ $teamList->firstItem() + $index }}</td>
                            <td>
                                <div class="school-name">{{ $team->school_name }}</div>
                                <div class="season-info">
                                    <i class="fas fa-calendar-alt"></i>
                                    <span>{{ $team->season }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="ref-code">{{ $team->referral_code }}</span>
                                <div class="text-muted mt-1" style="font-size: 0.75rem;">
                                    <i class="fas fa-user-check me-1"></i>{{ $team->registered_by }}
                                </div>
                            </td>
                            <td>
                                <span class="category-tag">{{ $team->team_category }}</span>
                            </td>
                            <td>
                                <div class="competition-badge">
                                    {{ Str::limit($team->competition, 30) }}
                                </div>
                                @if($team->series)
                                <div class="series-text">
                                    <i class="fas fa-layer-group me-1"></i>{{ $team->series }}
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($team->locked_status == 'locked')
                                <span class="status-badge badge-locked">
                                    <i class="fas fa-lock"></i> TERKUNCI
                                </span>
                                @else
                                <span class="status-badge badge-unlocked">
                                    <i class="fas fa-unlock"></i> TERBUKA
                                </span>
                                @endif
                            </td>
                            <td>
                                @if($team->verification_status == 'verified')
                                <span class="status-badge badge-verified">
                                    <i class="fas fa-check-circle"></i> VERIFIED
                                </span>
                                @else
                                <span class="status-badge badge-unverified">
                                    <i class="fas fa-hourglass-half"></i> UNVERIFIED
                                </span>
                                @endif
                            </td>
                            <td>
                                <div class="date-primary">{{ $team->created_at->format('d M Y') }}</div>
                                <div class="date-secondary">{{ $team->created_at->format('H:i') }}</div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <!-- Button View -->
                                    <a href="{{ route('admin.team-list.show', $team->team_id) }}"
                                        class="btn btn-action btn-view"
                                        title="Lihat Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <!-- Button Verifikasi / Batalkan Verifikasi -->
                                    @if($team->verification_status == 'unverified')
                                    <form action="{{ route('admin.team.verify', $team->team_id) }}"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-action btn-verify"
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
                                            class="btn btn-action btn-unverify"
                                            title="Batalkan Verifikasi"
                                            onclick="return confirm('Batalkan verifikasi tim {{ $team->school_name }}?')">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                    @endif

                                    <!-- Button Kunci / Buka Kunci -->
                                    @if($team->locked_status != 'locked')
                                    <form action="{{ route('admin.team.lock', $team->team_id) }}"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-action btn-lock"
                                            title="Kunci Tim"
                                            onclick="return confirm('Kunci tim {{ $team->school_name }}?')">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.team.unlock', $team->team_id) }}"
                                        method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit"
                                            class="btn btn-action btn-unlock"
                                            title="Buka Kunci"
                                            onclick="return confirm('Buka kunci tim {{ $team->school_name }}?')">
                                            <i class="fas fa-unlock"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="fas fa-users-slash"></i>
                                    </div>
                                    <h5 class="empty-title">Tidak ada data tim ditemukan</h5>
                                    <p class="empty-text">
                                        Data tim akan muncul di sini setelah proses pendaftaran dilakukan.
                                        Silakan periksa filter pencarian atau tunggu pendaftaran dari peserta.
                                    </p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination - SELALU MUNCUL -->
            <div class="pagination-container">
                <div class="pagination-info">
                    <i class="fas fa-info-circle me-2"></i>
                    @if($teamList->total() > 0)
                    Menampilkan <strong>{{ $teamList->firstItem() }} - {{ $teamList->lastItem() }}</strong>
                    dari total <strong>{{ $teamList->total() }}</strong> data
                    @else
                    Tidak ada data yang ditampilkan
                    @endif
                </div>

                @if($teamList->total() > 0)
                <div class="pagination-wrapper">
                    <ul class="custom-pagination">
                        {{-- Previous Button --}}
                        <li class="page-item {{ $teamList->onFirstPage() ? 'disabled' : '' }}">
                            <a class="page-link"
                                href="{{ $teamList->onFirstPage() ? '#' : $teamList->previousPageUrl() }}"
                                aria-label="Previous">
                                &laquo;
                            </a>
                        </li>

                        {{-- Page Numbers --}}
                        @php
                        $currentPage = $teamList->currentPage();
                        $lastPage = $teamList->lastPage();

                        // Untuk halaman sedikit, tampilkan semua
                        $showAll = $lastPage <= 5;

                            // Untuk halaman banyak, tampilkan current dan sekitar
                            $startPage=max($currentPage - 1, 1);
                            $endPage=min($currentPage + 1, $lastPage);
                            @endphp

                            {{-- Always show first page --}}
                            @if($currentPage> 2 && !$showAll)
                            <li class="page-item">
                                <a class="page-link" href="{{ $teamList->url(1) }}">1</a>
                            </li>
                            @if($currentPage > 3)
                            <li class="page-item disabled">
                                <span class="page-ellipsis">...</span>
                            </li>
                            @endif
                            @endif

                            {{-- Show pages around current --}}
                            @for ($i = ($showAll ? 1 : $startPage); $i <= ($showAll ? $lastPage : $endPage); $i++)
                                <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                                @if($i == $currentPage)
                                <span class="page-link">{{ $i }}</span>
                                @else
                                <a class="page-link" href="{{ $teamList->url($i) }}">{{ $i }}</a>
                                @endif
                                </li>
                                @endfor

                                {{-- Always show last page --}}
                                @if($currentPage < $lastPage - 1 && !$showAll)
                                    @if($currentPage < $lastPage - 2)
                                    <li class="page-item disabled">
                                    <span class="page-ellipsis">...</span>
                                    </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $teamList->url($lastPage) }}">{{ $lastPage }}</a>
                                    </li>
                                    @endif

                                    {{-- Next Button --}}
                                    <li class="page-item {{ !$teamList->hasMorePages() ? 'disabled' : '' }}">
                                        <a class="page-link"
                                            href="{{ !$teamList->hasMorePages() ? '#' : $teamList->nextPageUrl() }}"
                                            aria-label="Next">
                                            &raquo;
                                        </a>
                                    </li>
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit form on filter change
        const autoSubmitFilters = ['school', 'status', 'category', 'year', 'competition', 'locked', 'sort', 'order'];
        autoSubmitFilters.forEach(filterName => {
            const element = document.querySelector(`select[name="${filterName}"]`);
            if (element) {
                element.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            }
        });
    });
</script>
@endpush
@endsection