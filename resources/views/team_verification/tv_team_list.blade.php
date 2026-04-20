@extends('admin.layouts.app')
@section('title', 'Team List - Administrator')

@section('content')
@include('partials.sweetalert')

@push('styles')
<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --primary: #667eea;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --dark: #1e293b;
        --gray: #64748b;
        --light: #f8fafc;
    }

    .container-fluid.py-4 {
        padding-top: 1rem !important;
        padding-bottom: 1rem !important;
        padding-left: 1rem !important;
        padding-right: 1rem !important;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.3rem;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 0.85rem;
    }

    .admin-card {
        background: white;
        border: none;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }

    .card-header {
        background: var(--primary-gradient);
        border: none;
        padding: 14px 20px;
        font-weight: 600;
        color: white;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .filter-section {
        background: white;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    .filter-tabs {
        display: flex;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        padding: 0 16px;
        gap: 4px;
    }

    .filter-tab {
        padding: 12px 20px;
        font-size: 0.8rem;
        font-weight: 500;
        color: #64748b;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .filter-tab i {
        font-size: 0.85rem;
    }

    .filter-tab:hover {
        color: #667eea;
    }

    .filter-tab.active {
        color: #667eea;
        border-bottom-color: #667eea;
        background: white;
        border-radius: 8px 8px 0 0;
    }

    .filter-panels {
        padding: 20px;
    }

    .filter-panel {
        display: none;
        animation: fadeIn 0.3s ease;
    }

    .filter-panel.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(5px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .filter-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        align-items: flex-end;
    }

    .filter-field {
        flex: 1;
        min-width: 160px;
    }

    .filter-label {
        font-size: 0.7rem;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        display: block;
    }

    .filter-select,
    .filter-input {
        width: 100%;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.85rem;
        padding: 8px 12px;
        background: white;
        transition: all 0.2s;
    }

    .filter-select:focus,
    .filter-input:focus {
        outline: none;
        border-color: #667eea;
        box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
    }

    .search-field {
        flex: 2;
        min-width: 220px;
        position: relative;
    }

    .search-field .filter-input {
        padding-right: 65px;
    }

    .search-icon {
        position: absolute;
        right: 40px;
        bottom: 9px;
        color: #94a3b8;
        font-size: 0.85rem;
        pointer-events: none;
    }

    .search-clear {
        position: absolute;
        right: 12px;
        bottom: 5px;
        background: none;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 0.7rem;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .search-clear:hover {
        color: #ef4444;
        background: #fef2f2;
    }

    .export-btn {
        background: #10b981;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.8rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .export-btn:hover {
        background: #059669;
        transform: translateY(-1px);
        color: white;
    }

    .table-container {
        overflow-x: auto;
        padding: 0;
    }

    .table {
        width: 100%;
        font-size: 0.8rem;
        margin-bottom: 0;
        border-collapse: collapse;
    }

    .table th {
        padding: 12px 10px;
        background: #f8fafc;
        font-size: 0.7rem;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        border-bottom: 2px solid #e2e8f0;
        text-align: center;
    }

    .table td {
        padding: 10px 10px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .table tbody tr:hover {
        background: #f8fafc;
    }

    .table th:nth-child(1),
    .table td:nth-child(1) {
        width: 70px;
        text-align: center;
    }

    .table th:nth-child(2),
    .table td:nth-child(2) {
        width: 60px;
        text-align: center;
    }

    .table th:nth-child(3),
    .table td:nth-child(3) {
        width: 140px;
    }

    .table th:nth-child(4),
    .table td:nth-child(4) {
        width: 120px;
    }

    .table th:nth-child(5),
    .table td:nth-child(5) {
        width: 140px;
    }

    .table th:nth-child(6),
    .table td:nth-child(6) {
        width: 90px;
        text-align: center;
    }

    .table th:nth-child(7),
    .table td:nth-child(7) {
        width: 80px;
        text-align: center;
    }

    .table th:nth-child(8),
    .table td:nth-child(8) {
        width: 80px;
        text-align: center;
    }

    .table th:nth-child(9),
    .table td:nth-child(9) {
        width: 85px;
        text-align: center;
    }

    .table th:nth-child(10),
    .table td:nth-child(10) {
        width: 85px;
        text-align: center;
    }

    .logo-container {
        width: 42px;
        height: 42px;
        margin: 0 auto;
        border-radius: 8px;
        overflow: hidden;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .logo-img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 4px;
    }

    .logo-placeholder {
        text-align: center;
        padding: 4px;
        color: #94a3b8;
        font-size: 0.6rem;
    }

    .logo-placeholder i {
        font-size: 1rem;
    }

    .team-number-badge {
        font-family: monospace;
        font-size: 0.75rem;
        font-weight: 700;
        background: #f1f5f9;
        padding: 4px 8px;
        border-radius: 6px;
        display: inline-block;
    }

    .school-name,
    .team-name,
    .competition-name {
        font-weight: 500;
        color: #1e293b;
        font-size: 0.8rem;
        line-height: 1.3;
    }

    .text-small {
        font-size: 0.65rem;
        color: #94a3b8;
        margin-top: 2px;
    }

    .status-badge {
        padding: 4px 8px;
        font-size: 0.65rem;
        font-weight: 600;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .badge-locked {
        background: linear-gradient(135deg, #fed7d7 0%, #fc8181 100%);
        color: #9b2c2c;
    }

    .badge-unlocked {
        background: linear-gradient(135deg, #c6f6d5 0%, #68d391 100%);
        color: #276749;
    }

    .badge-verified {
        background: linear-gradient(135deg, #c6f6d5 0%, #48bb78 100%);
        color: #276749;
    }

    .badge-unverified {
        background: linear-gradient(135deg, #fed7d7 0%, #feb2b2 100%);
        color: #9b2c2c;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
        justify-content: center;
    }

    .btn-action {
        width: 28px;
        height: 28px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
        cursor: pointer;
        border: none;
        font-size: 0.75rem;
    }

    .btn-view {
        background: var(--primary-gradient);
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

    .btn-action:hover {
        transform: scale(1.05);
    }

    .date-primary {
        font-weight: 500;
        color: #1e293b;
        font-size: 0.75rem;
    }

    .date-secondary {
        font-size: 0.65rem;
        color: #94a3b8;
    }

    .pagination-container {
        background: transparent;
        padding: 20px 24px;
        border-top: 1px solid #f0f2f5;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .custom-pagination {
        display: flex;
        gap: 6px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .page-link {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        padding: 0 10px;
        background: transparent;
        border: none;
        color: #64748b;
        font-size: 0.85rem;
        font-weight: 500;
        border-radius: 10px;
        transition: all 0.2s ease;
        text-decoration: none;
        cursor: pointer;
    }

    .page-link i {
        font-size: 0.85rem;
    }

    .page-item:not(.active) .page-link:hover {
        background: #f1f5f9;
        color: #1e293b;
    }

    .page-item.active .page-link {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.25);
    }

    .page-item.disabled .page-link {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }

    .page-link.active-page {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 10px rgba(102, 126, 234, 0.25);
    }

    .empty-state {
        padding: 50px;
        text-align: center;
    }

    .empty-icon {
        font-size: 3rem;
        color: #cbd5e0;
        margin-bottom: 15px;
    }

    .empty-title {
        font-size: 1rem;
        color: #64748b;
        margin-bottom: 5px;
    }

    .empty-text {
        font-size: 0.8rem;
        color: #94a3b8;
    }

    @media (max-width: 768px) {
        .filter-tabs {
            overflow-x: auto;
        }

        .filter-tab {
            white-space: nowrap;
        }

        .filter-grid {
            flex-direction: column;
        }

        .filter-field,
        .search-field {
            width: 100%;
        }

        .table {
            min-width: 750px;
        }

        .pagination-container {
            justify-content: center;
        }

        .search-clear span {
            display: none;
        }

        .search-clear i {
            margin: 0;
        }

        .custom-pagination {
            gap: 4px;
        }

        .page-link {
            min-width: 32px;
            height: 32px;
            font-size: 0.75rem;
        }

        .page-link i {
            font-size: 0.75rem;
        }
    }

  .danger-btn {
    background: #dc2626;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 500;
    font-size: 0.8rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s;
    cursor: pointer;
}

.danger-btn:hover {
    background: #b91c1c;
    transform: translateY(-1px);
}
</style>
@endpush

<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h1 class="page-title mb-1">
                <i class="fas fa-users me-2"></i>Daftar Tim
            </h1>
            <p class="page-subtitle">Kelola dan monitor tim peserta SBL</p>
        </div>
        <div class="btn-group-flex">
            <a href="#" class="export-btn" id="exportBtn">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
            <button class="export-btn danger-btn" id="deleteAllBtn">
                <i class="fas fa-trash-alt me-1"></i> Hapus Semua Data Tim
            </button>
        </div>
    </div>

    <div class="filter-section">
        <div class="filter-tabs">
            <div class="filter-tab active" data-panel="status">
                <i class="fas fa-flag-checkered"></i> Status
            </div>
            <div class="filter-tab" data-panel="school">
                <i class="fas fa-building"></i> Data Sekolah
            </div>
            <div class="filter-tab" data-panel="competition">
                <i class="fas fa-trophy"></i> Kompetisi
            </div>
        </div>

        <div class="filter-panels">
            <form method="GET" action="{{ route('admin.tv_team_list') }}" id="filterForm">
                <div class="filter-panel active" id="panel-status">
                    <div class="filter-grid">
                        <div class="filter-field">
                            <label class="filter-label">Verifikasi</label>
                            <select name="status" class="filter-select">
                                <option value="">Semua</option>
                                <option value="unverified" {{ request('status') == 'unverified' ? 'selected' : '' }}>⏳ Belum Verifikasi</option>
                                <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>✅ Terverifikasi</option>
                            </select>
                        </div>
                        <div class="filter-field">
                            <label class="filter-label">Status Kunci</label>
                            <select name="locked" class="filter-select">
                                <option value="">Semua</option>
                                <option value="locked" {{ request('locked') == 'locked' ? 'selected' : '' }}>🔒 Terkunci</option>
                                <option value="unlocked" {{ request('locked') == 'unlocked' ? 'selected' : '' }}>🔓 Terbuka</option>
                            </select>
                        </div>
                        <div class="filter-field">
                            <label class="filter-label">Urutkan</label>
                            <select name="sort" class="filter-select">
                                <option value="updated_at" {{ request('sort', 'updated_at') == 'updated_at' ? 'selected' : '' }}>Update Terbaru</option>
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Pendaftaran</option>
                                <option value="school_name" {{ request('sort') == 'school_name' ? 'selected' : '' }}>Nama Sekolah</option>
                            </select>
                        </div>
                        <div class="filter-field">
                            <label class="filter-label">Urutan</label>
                            <select name="order" class="filter-select">
                                <option value="desc" {{ request('order', 'desc') == 'desc' ? 'selected' : '' }}>↓ Descending</option>
                                <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>↑ Ascending</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="filter-panel" id="panel-school">
                    <div class="filter-grid">
                        <div class="filter-field">
                            <label class="filter-label">Sekolah</label>
                            <select name="school" class="filter-select">
                                <option value="">Semua Sekolah</option>
                                @foreach($schools as $school)
                                <option value="{{ $school }}" {{ request('school') == $school ? 'selected' : '' }}>
                                    {{ Str::limit($school, 35) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-field">
                            <label class="filter-label">Kategori</label>
                            <select name="category" class="filter-select">
                                <option value="">Semua Kategori</option>
                                <option value="Basket Putra" {{ request('category') == 'Basket Putra' ? 'selected' : '' }}>🏀 Basket Putra</option>
                                <option value="Basket Putri" {{ request('category') == 'Basket Putri' ? 'selected' : '' }}>🏀 Basket Putri</option>
                                <option value="Dancer" {{ request('category') == 'Dancer' ? 'selected' : '' }}>💃 Dancer</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="filter-panel" id="panel-competition">
                    <div class="filter-grid">
                        <div class="filter-field">
                            <label class="filter-label">Kompetisi</label>
                            <select name="competition" class="filter-select">
                                <option value="">Semua Kompetisi</option>
                                @foreach($competitions as $comp)
                                <option value="{{ $comp }}" {{ request('competition') == $comp ? 'selected' : '' }}>
                                    {{ Str::limit($comp, 30) }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="filter-field">
                            <label class="filter-label">Tahun</label>
                            <select name="year" class="filter-select">
                                <option value="">Semua Tahun</option>
                                @foreach($years as $year)
                                <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="search-field" style="margin-top: 16px; padding-top: 16px; border-top: 1px solid #e2e8f0;">
                    <label class="filter-label">PENCARIAN CEPAT</label>
                    <input type="text" name="search" id="searchInput" class="filter-input" placeholder="Cari nama sekolah, tim, atau kompetisi..." value="{{ request('search') }}">
                    <i class="fas fa-search search-icon"></i>
                    <button type="button" class="search-clear" id="clearSearchBtn" style="display: {{ request('search') ? 'flex' : 'none' }};">
                        <i class="fas fa-times-circle"></i>
                        <span>Hapus</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="admin-card">
        <div class="card-header">
            <div>
                <i class="fas fa-table me-2"></i>
                <span>Data Tim Terdaftar</span>
            </div>
            <div class="badge bg-white text-primary rounded-pill px-3 py-1 fw-semibold">
                <i class="fas fa-database me-1"></i> {{ $teamList->total() }} Tim
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>LOGO</th>
                            <th>NO</th>
                            <th>SEKOLAH</th>
                            <th>NAMA TIM</th>
                            <th>KOMPETISI</th>
                            <th>REG</th>
                            <th>KUNCI</th>
                            <th>VERIF</th>
                            <th>UPDATED</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($teamList as $team)
                        <tr>
                            <td>
                                <div class="logo-container" onclick="showLogoPopup('{{ asset('storage/' . $team->school_logo) }}', '{{ addslashes($team->school_name) }}')">
                                    @if(!empty($team->school_logo))
                                    <img src="{{ asset('storage/' . $team->school_logo) }}" class="logo-img" onerror="this.src='https://placehold.co/50x50?text=No+Logo'">
                                    @else
                                    <div class="logo-placeholder"><i class="fas fa-school"></i><br>No Logo</div>
                                    @endif
                                </div>
                            </td>
                            <td class="text-center"><span class="team-number-badge">{{ str_pad($team->team_id ?? $team->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                            <td>
                                <div class="school-name">{{ Str::limit($team->school_name ?? '-', 28) }}</div>
                                @if(!empty($team->season))<div class="text-small"><i class="fas fa-calendar-alt"></i> {{ $team->season }}</div>@endif
                            </td>
                            <td>
                                <div class="team-name">{{ Str::limit($team->team_name ?? '-', 20) }}</div>
                            </td>
                            <td>
                                <div class="competition-name">{{ Str::limit($team->competition ?? '-', 28) }}</div>
                                @if(!empty($team->series))<div class="text-small"><i class="fas fa-layer-group"></i> {{ $team->series }}</div>@endif
                            </td>
                            <td class="text-center">
                                <div>{{ Str::limit($team->registered_by ?? '-', 10) }}</div>
                                @if(!empty($team->referral_code))<div class="text-small"><i class="fas fa-hashtag"></i> {{ $team->referral_code }}</div>@endif
                            </td>
                            <td class="text-center">
                                @if(($team->locked_status ?? '') == 'locked')
                                <span class="status-badge badge-locked"><i class="fas fa-lock"></i> LOCK</span>
                                @else
                                <span class="status-badge badge-unlocked"><i class="fas fa-lock-open"></i> OPEN</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(($team->verification_status ?? '') == 'verified')
                                <span class="status-badge badge-verified"><i class="fas fa-check-circle"></i> DONE</span>
                                @else
                                <span class="status-badge badge-unverified"><i class="fas fa-clock"></i> PENDING</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="date-primary">{{ $team->updated_at ? \Carbon\Carbon::parse($team->updated_at)->format('d/m/Y') : '-' }}</div>
                                <div class="date-secondary">{{ $team->updated_at ? \Carbon\Carbon::parse($team->updated_at)->format('H:i') : '' }}</div>
                            </td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <a href="{{ route('admin.team-list.show', $team->team_id ?? $team->id) }}" class="btn-action btn-view" title="Detail"><i class="fas fa-eye"></i></a>
                                    @if(($team->verification_status ?? '') == 'unverified')
                                    <form action="{{ route('admin.team.verify', $team->team_id ?? $team->id) }}" method="POST" class="d-inline verify-form">
                                        @csrf
                                        <button type="submit" class="btn-action btn-verify" title="Verifikasi"><i class="fas fa-check"></i></button>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.team.unverify', $team->team_id ?? $team->id) }}" method="POST" class="d-inline unverify-form">
                                        @csrf
                                        <button type="submit" class="btn-action btn-unverify" title="Batalkan"><i class="fas fa-times"></i></button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <td>
                        <td colspan="10">
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-users-slash"></i></div>
                                <h5 class="empty-title">Tidak ada data</h5>
                                <p class="empty-text">Coba sesuaikan filter pencarian</p>
                            </div>
            </div>
            </td>
            </tr>
            @endforelse
            </tbody>
            </table>
        </div>

        <div class="pagination-container">
            <nav>
                <ul class="custom-pagination">
                    <li class="page-item {{ $teamList->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $teamList->onFirstPage() ? '#' : $teamList->url(1) }}" title="Halaman Pertama">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                    </li>
                    <li class="page-item {{ $teamList->onFirstPage() ? 'disabled' : '' }}">
                        <a class="page-link" href="{{ $teamList->onFirstPage() ? '#' : $teamList->previousPageUrl() }}" title="Halaman Sebelumnya">
                            <i class="fas fa-angle-left"></i>
                        </a>
                    </li>
                    @php
                    $currentPage = $teamList->currentPage();
                    $lastPage = max(5, $teamList->lastPage());
                    $startPage = 1;
                    $endPage = 5;
                    @endphp
                    @for ($i = $startPage; $i <= $endPage; $i++)
                        <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                        @if($i == $currentPage)
                        <span class="page-link active-page">{{ $i }}</span>
                        @else
                        <a class="page-link" href="{{ $teamList->url($i) }}">{{ $i }}</a>
                        @endif
                        </li>
                        @endfor
                        <li class="page-item {{ !$teamList->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ !$teamList->hasMorePages() ? '#' : $teamList->nextPageUrl() }}" title="Halaman Selanjutnya">
                                <i class="fas fa-angle-right"></i>
                            </a>
                        </li>
                        <li class="page-item {{ !$teamList->hasMorePages() ? 'disabled' : '' }}">
                            <a class="page-link" href="{{ !$teamList->hasMorePages() ? '#' : $teamList->url($teamList->lastPage()) }}" title="Halaman Terakhir">
                                <i class="fas fa-angle-double-right"></i>
                            </a>
                        </li>
                </ul>
            </nav>
        </div>
    </div>
</div>
</div>

@push('scripts')
<script>
    let debounceTimer;

    document.querySelectorAll('.filter-select').forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });

    const searchInput = document.getElementById('searchInput');
    const clearBtn = document.getElementById('clearSearchBtn');
    const filterForm = document.getElementById('filterForm');

    if (searchInput) {
        searchInput.addEventListener('input', function() {
            if (clearBtn) clearBtn.style.display = this.value.length > 0 ? 'flex' : 'none';
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                filterForm.submit();
            }, 500);
        });
    }

    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            searchInput.value = '';
            clearBtn.style.display = 'none';
            filterForm.submit();
        });
    }

    document.querySelectorAll('.filter-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.filter-panel').forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.getElementById('panel-' + this.dataset.panel).classList.add('active');
        });
    });

    function showLogoPopup(logoUrl, schoolName) {
        if (!logoUrl || logoUrl === 'null' || logoUrl.includes('placehold')) {
            Swal.fire({
                title: schoolName,
                html: '<div style="padding:20px"><i class="fas fa-school" style="font-size:4rem; color:#cbd5e1"></i><p style="margin-top:10px">Logo belum tersedia</p></div>',
                showCloseButton: true,
                showConfirmButton: false
            });
            return;
        }
        Swal.fire({
            title: schoolName,
            html: `<img src="${logoUrl}" style="max-width:250px; border-radius:8px;">`,
            showCloseButton: true,
            showConfirmButton: false
        });
    }

    document.querySelectorAll('.verify-form, .unverify-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const isVerify = this.classList.contains('verify-form');
            Swal.fire({
                title: 'Konfirmasi',
                text: `Yakin ingin ${isVerify ? 'memverifikasi' : 'membatalkan verifikasi'} tim ini?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: isVerify ? '#10b981' : '#f59e0b',
                confirmButtonText: `Ya, ${isVerify ? 'Verifikasi' : 'Batalkan'}!`,
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) form.submit();
            });
        });
    });

    // Export Button - UKURAN KECIL & COMPACT
    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const urlParams = new URLSearchParams(window.location.search);
            let activeFilters = [];
            if (urlParams.get('school')) activeFilters.push('Sekolah');
            if (urlParams.get('status')) activeFilters.push('Verifikasi');
            if (urlParams.get('category')) activeFilters.push('Kategori');
            if (urlParams.get('competition')) activeFilters.push('Kompetisi');
            if (urlParams.get('year')) activeFilters.push('Tahun');
            if (urlParams.get('locked')) activeFilters.push('Kunci');
            if (urlParams.get('search')) activeFilters.push('Pencarian');

            const filterText = activeFilters.length > 0 ? `✓ ${activeFilters.length} filter aktif` : '✗ Tidak ada filter';

            // HTML COMPACT - UKURAN KECIL
            const columnOptions = `
                <div style="text-align: left; max-height: 380px; overflow-y: auto; padding-right: 5px;">
                    <div style="background: #f1f5f9; padding: 6px 10px; border-radius: 6px; margin-bottom: 12px; font-size: 0.75rem;">
                        <i class="fas fa-filter" style="color: #667eea; margin-right: 4px;"></i>
                        <strong>Filter:</strong> ${filterText}
                    </div>
                    
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 4px; font-size: 0.75rem;">
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 4px 6px; border-radius: 4px;">
                            <input type="checkbox" class="export-col" value="team_id" checked style="width: 14px; height: 14px;"> 🆔 ID Tim
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 4px 6px; border-radius: 4px;">
                            <input type="checkbox" class="export-col" value="school_name" checked style="width: 14px; height: 14px;"> 🏫 Sekolah
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 4px 6px; border-radius: 4px;">
                            <input type="checkbox" class="export-col" value="team_category" checked style="width: 14px; height: 14px;"> 🏀 Kategori
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 4px 6px; border-radius: 4px;">
                            <input type="checkbox" class="export-col" value="competition" checked style="width: 14px; height: 14px;"> 🏆 Kompetisi
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 4px 6px; border-radius: 4px;">
                            <input type="checkbox" class="export-col" value="season" checked style="width: 14px; height: 14px;"> 📅 Tahun
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 4px 6px; border-radius: 4px;">
                            <input type="checkbox" class="export-col" value="series" checked style="width: 14px; height: 14px;"> 📊 Series
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 4px 6px; border-radius: 4px;">
                            <input type="checkbox" class="export-col" value="registered_by" checked style="width: 14px; height: 14px;"> 👤 Pendaftar
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 4px 6px; border-radius: 4px;">
                            <input type="checkbox" class="export-col" value="referral_code" checked style="width: 14px; height: 14px;"> 🔗 Referral
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 4px 6px; border-radius: 4px;">
                            <input type="checkbox" class="export-col" value="locked_status" checked style="width: 14px; height: 14px;"> 🔒 Status Kunci
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 4px 6px; border-radius: 4px;">
                            <input type="checkbox" class="export-col" value="verification_status" checked style="width: 14px; height: 14px;"> ✅ Verifikasi
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 4px 6px; border-radius: 4px;">
                            <input type="checkbox" class="export-col" value="payment_status" checked style="width: 14px; height: 14px;"> 💰 Status Bayar
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 4px 6px; border-radius: 4px;">
                            <input type="checkbox" class="export-col" value="created_at" checked style="width: 14px; height: 14px;"> 📅 Dibuat
                        </label>
                        <label style="display: flex; align-items: center; gap: 6px; cursor: pointer; padding: 4px 6px; border-radius: 4px;">
                            <input type="checkbox" class="export-col" value="updated_at" checked style="width: 14px; height: 14px;"> 🔄 Update
                        </label>
                    </div>
                    
                    <div style="margin-top: 12px; padding-top: 10px; border-top: 1px solid #e2e8f0; display: flex; gap: 8px;">
                        <button type="button" id="selectAllBtn" style="background: #667eea; color: white; border: none; padding: 5px 12px; border-radius: 6px; font-size: 0.7rem; cursor: pointer; flex: 1;">✅ Semua</button>
                        <button type="button" id="deselectAllBtn" style="background: #e2e8f0; color: #64748b; border: none; padding: 5px 12px; border-radius: 6px; font-size: 0.7rem; cursor: pointer; flex: 1;">❌ Hapus</button>
                    </div>
                </div>
            `;

            Swal.fire({
                title: 'Export Data Tim',
                html: columnOptions,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="fas fa-download"></i> Export',
                cancelButtonText: 'Batal',
                width: '480px',
                allowOutsideClick: false,
                didOpen: () => {
                    const selectAllBtn = document.getElementById('selectAllBtn');
                    if (selectAllBtn) {
                        selectAllBtn.addEventListener('click', () => {
                            document.querySelectorAll('.export-col').forEach(cb => cb.checked = true);
                        });
                    }
                    const deselectAllBtn = document.getElementById('deselectAllBtn');
                    if (deselectAllBtn) {
                        deselectAllBtn.addEventListener('click', () => {
                            document.querySelectorAll('.export-col').forEach(cb => cb.checked = false);
                        });
                    }
                },
                preConfirm: () => {
                    const selectedColumns = [];
                    document.querySelectorAll('.export-col:checked').forEach(cb => {
                        selectedColumns.push(cb.value);
                    });
                    if (selectedColumns.length === 0) {
                        Swal.showValidationMessage('⚠️ Pilih minimal 1 kolom!');
                        return false;
                    }
                    return {
                        columns: selectedColumns
                    };
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const selectedColumns = result.value.columns;

                    Swal.fire({
                        title: 'Mengekspor...',
                        text: `Mengekspor ${selectedColumns.length} kolom`,
                        icon: 'info',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    const currentUrlParams = new URLSearchParams(window.location.search);
                    const filterParams = {};
                    for (let [key, value] of currentUrlParams.entries()) {
                        if (key !== 'page') filterParams[key] = value;
                    }

                    fetch('{{ route("admin.team-list.export") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                columns: selectedColumns,
                                filters: filterParams
                            })
                        })
                        .then(response => {
                            if (!response.ok) throw new Error('Gagal export');
                            return response.blob();
                        })
                        .then(blob => {
                            const url = window.URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = 'teams_export_' + new Date().toISOString().slice(0, 19).replace(/:/g, '-') + '.csv';
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                            window.URL.revokeObjectURL(url);
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Data berhasil diexport.',
                                icon: 'success',
                                confirmButtonColor: '#10b981',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan saat export.',
                                icon: 'error',
                                confirmButtonColor: '#ef4444'
                            });
                        });
                }
            });
        });

    }
   // ========== DELETE ALL DATA (SUPER SIMPLE) ==========
const deleteAllBtn = document.getElementById('deleteAllBtn');
if (deleteAllBtn) {
    deleteAllBtn.addEventListener('click', function() {
        Swal.fire({
            title: '⚠️ Hapus Semua Data Tim?',
            html: `
                <p style="margin-bottom: 10px;">Data tim, pemain, dancer, dan official akan dihapus.</p>
                <p style="color: #dc2626; font-weight: 500; margin-bottom: 5px;">
                    <i class="fas fa-exclamation-triangle"></i> 
                    Data yang dihapus TIDAK DAPAT dikembalikan!
                </p>
                <p style="color: #10b981; font-size: 0.85rem;">
                    <i class="fas fa-shield-alt"></i> 
                    Data sekolah TETAP AMAN.
                </p>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            confirmButtonText: '<i class="fas fa-trash-alt"></i> Hapus Semua',
            cancelButtonText: '<i class="fas fa-times"></i> Batal',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Menghapus data...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                fetch('{{ route("admin.team-list.delete-all") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ confirmation: 'YA,HAPUS,SEMUA,DATA' })
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: '✅ Berhasil!',
                            text: data.message,
                            icon: 'success'
                        }).then(() => location.reload());
                    } else {
                        Swal.fire('❌ Gagal!', data.message, 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('❌ Error!', 'Terjadi kesalahan.', 'error');
                });
            }
        });
    });
}
</script>
@endpush
@endsection