@extends('admin.layouts.app')
@section('title', 'Detail Tim - Administrator')

@section('content')
@include('partials.sweetalert')

@push('styles')
<style>
    /* ===== CSS ORIGINAL - TETAP SAMA ===== */
    body {
        background: #f4f6f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .page-subtitle {
        color: #7f8c8d;
        font-size: 0.9rem;
    }

    /* Card Styles */
    .card {
        background: #fff;
        border-radius: 10px;
        margin-bottom: 25px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        border: 1px solid #e0e0e0;
        overflow: hidden;
    }

    .card-header {
        padding: 18px 22px;
        font-weight: 600;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .card-header i {
        font-size: 1.2rem;
    }

    .card-body {
        padding: 25px;
    }

    /* Team Information Grid */
    .team-info {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 30px;
        align-items: start;
    }

    /* Logo Column */
    .logo-column {
        display: flex;
        flex-direction: column;
    }

    .logo-box-square {
        text-align: center;
        padding: 25px;
        background: #f8fafc;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 0;
        height: 250px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .logo-box-square:hover {
        border-color: #3b82f6;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
        transform: translateY(-3px);
    }

    .logo-box-square img {
        width: 150px;
        height: 150px;
        object-fit: contain;
        margin-bottom: 15px;
        border-radius: 8px;
        background: white;
        padding: 12px;
        border: 1px solid #e2e8f0;
    }

    .logo-placeholder {
        width: 150px;
        height: 150px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border: 2px dashed #bfdbfe;
        border-radius: 8px;
        color: #3b82f6;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    /* Content Column */
    .content-column {
        display: flex;
        flex-direction: column;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    .info-section,
    .status-doc-section {
        display: flex;
        flex-direction: column;
    }

    /* Info Tables */
    .info-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .info-table td {
        padding: 14px 16px;
        vertical-align: middle;
        font-size: 14px;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-table td:first-child {
        width: 160px;
        color: #2d3748;
        font-weight: 600;
        background: #f8fafc;
        border-right: 1px solid #e2e8f0;
    }

    .info-table.compact td {
        padding: 12px 16px;
        font-size: 13px;
    }

    .info-table.compact td:first-child {
        width: 140px;
    }

    .info-table tr:last-child td {
        border-bottom: none;
    }

    /* Status Section */
    .status-section {
        margin-bottom: 20px;
    }

    /* Status Styles */
    .status-open {
        color: #dc2626;
        font-weight: 600;
        background: #fef2f2;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #fecaca;
    }

    .status-locked {
        color: #059669;
        font-weight: 600;
        background: #d1fae5;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #a7f3d0;
    }

    .status-verified {
        color: #059669;
        font-weight: 600;
        background: #d1fae5;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #a7f3d0;
    }

    .status-unverified {
        color: #d97706;
        font-weight: 600;
        background: #fef3c7;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #fde68a;
    }

    .status-rejected {
        color: #dc2626;
        font-weight: 600;
        background: #fee2e2;
        padding: 8px 14px;
        border-radius: 8px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border: 1px solid #fecaca;
    }

    /* Documents Section */
    .documents-section h4 {
        font-size: 15px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #dbeafe;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .documents-section h4 i {
        color: #3b82f6;
    }

    .document-links.compact {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .document-link {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        padding: 14px 16px;
        border-radius: 10px;
        transition: all 0.3s ease;
        width: 100%;
        box-sizing: border-box;
        border: 2px solid transparent;
    }

    .document-link.available {
        color: #1e40af;
        background: #eff6ff;
        border-color: #dbeafe;
    }

    .document-link.available:hover {
        background: #dbeafe;
        color: #1e3a8a;
        border-color: #bfdbfe;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
    }

    .document-link.available i {
        color: #3b82f6;
        font-size: 16px;
    }

    .document-link.warning {
        color: #92400e;
        background: #fffbeb;
        border-color: #fde68a;
    }

    .document-link.warning:hover {
        background: #fef3c7;
        color: #78350f;
        border-color: #fcd34d;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.15);
    }

    .document-link.warning i {
        color: #f59e0b;
        font-size: 16px;
    }

    .document-link.danger {
        color: #7f1d1d;
        background: #fef2f2;
        border-color: #fecaca;
    }

    .document-link.danger:hover {
        background: #fee2e2;
        color: #991b1b;
        border-color: #fca5a5;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15);
    }

    .document-link.danger i {
        color: #ef4444;
        font-size: 16px;
    }

    /* Action Buttons */
    .action-buttons h4 {
        font-size: 15px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 2px solid #dbeafe;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-buttons h4 i {
        color: #3b82f6;
    }

    .action-buttons-row.compact {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .btn-action-simple {
        color: #fff;
        padding: 14px 20px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-action-simple:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
        color: white;
    }

    .btn-lock {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .btn-unlock {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
    }

    .btn-verify {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .btn-unverify {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    /* ===== DATA TABLES ===== */
    .table-container {
        overflow-x: auto;
        border-radius: 12px;
        border: 2px solid #dbeafe;
        background: white;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    }

    table.data {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        min-width: 1200px;
    }

    table.data th {
        background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
        font-weight: 700;
        color: #2d3748;
        padding: 18px 16px;
        text-align: left;
        border-bottom: 3px solid #dbeafe;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    table.data td {
        border-bottom: 2px solid #f1f5f9;
        padding: 16px;
        vertical-align: middle;
        color: #4a5568;
        font-size: 14px;
    }

    table.data tbody tr:hover {
        background-color: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    /* Official table specific widths */
    table.data.official {
        min-width: 1400px;
    }

    table.data.official th:nth-child(1),
    table.data.official td:nth-child(1) { width: 60px; text-align: center; }
    table.data.official th:nth-child(2),
    table.data.official td:nth-child(2) { width: 180px; }
    table.data.official th:nth-child(3),
    table.data.official td:nth-child(3) { width: 150px; text-align: center; }
    table.data.official th:nth-child(4),
    table.data.official td:nth-child(4) { width: 200px; }
    table.data.official th:nth-child(5),
    table.data.official td:nth-child(5) { width: 140px; }
    table.data.official th:nth-child(6),
    table.data.official td:nth-child(6) { width: 120px; text-align: center; }
    table.data.official th:nth-child(7),
    table.data.official td:nth-child(7) { width: 150px; text-align: center; }
    table.data.official th:nth-child(8),
    table.data.official td:nth-child(8) { width: 150px; text-align: center; }
    table.data.official th:nth-child(9),
    table.data.official td:nth-child(9) { width: 120px; text-align: center; }

    /* Dancer table specific */
    table.data.dancer {
        min-width: 1300px;
    }

    /* Button Detail */
    .btn-detail {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
        min-width: 100px;
        height: 38px;
        cursor: pointer;
    }

    .btn-detail:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.3);
        color: white;
        text-decoration: none;
    }

    /* Badge Styles */
    .badge-jersey {
        display: inline-block;
        width: 36px;
        height: 36px;
        line-height: 36px;
        background: #3b82f6;
        color: white;
        border-radius: 50%;
        font-weight: bold;
        font-size: 14px;
        text-align: center;
    }

    .badge-gender-male {
        background: #dbeafe;
        color: #1e40af;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-gender-female {
        background: #fce7f3;
        color: #be185d;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-position {
        background: #f1f5f9;
        color: #475569;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        display: inline-block;
    }

    .badge-grade {
        background: #fef3c7;
        color: #92400e;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #fde68a;
        display: inline-block;
    }

    .badge-sttb {
        background: #d1fae5;
        color: #065f46;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #a7f3d0;
        display: inline-block;
    }

    .badge-role-leader {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-role-player {
        background: #f1f5f9;
        color: #475569;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        display: inline-block;
    }

    .badge-official-role {
        background: #e0e7ff;
        color: #3730a3;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: 1px solid #c7d2fe;
        display: inline-block;
    }

    .badge-category {
        display: inline-flex;
        align-items: center;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        color: white;
    }

    .badge-category i {
        margin-right: 4px;
        font-size: 11px;
    }

    .badge-category-putra {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    }

    .badge-category-putri {
        background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);
    }

    .badge-category-dancer {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }

    .badge-category-lainnya {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
    }

    /* Back Link */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #4a5568;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 25px;
        padding: 14px 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        border: 2px solid #e2e8f0;
    }

    .back-link:hover {
        background: #f7fafc;
        color: #2d3748;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
        border-color: #cbd5e0;
    }

    .back-link i {
        color: #3b82f6;
        font-size: 14px;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 60px 30px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px;
        border: 2px dashed #cbd5e0;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 25px;
        color: #cbd5e0;
        opacity: 0.5;
    }

    .empty-state h5 {
        font-size: 1.3rem;
        color: #4a5568;
        margin-bottom: 12px;
        font-weight: 600;
    }

    .empty-state p {
        font-size: 14px;
        color: #718096;
        max-width: 400px;
        margin: 0 auto;
        line-height: 1.6;
    }

    /* ===== JERSEY GALLERY ===== */
    .jersey-single-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 30px;
        background: #f8fafc;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        min-height: 320px;
    }

    .jersey-main-title {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 25px;
        text-align: center;
        position: relative;
        padding-bottom: 10px;
    }

    .jersey-main-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 3px;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        border-radius: 3px;
    }

    .jersey-image-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
        width: 100%;
        max-width: 900px;
    }

    .jersey-single-item {
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        border-radius: 10px;
        padding: 20px;
        border: 2px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .jersey-single-item:hover {
        transform: translateY(-5px);
        border-color: #3b82f6;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
    }

    .jersey-single-item p {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
        margin-top: 0;
    }

    .jersey-image {
        width: 100%;
        height: 200px;
        object-fit: contain;
        border-radius: 6px;
        background: white;
        padding: 15px;
        border: 1px solid #e2e8f0;
        margin-bottom: 10px;
    }

    .no-image {
        width: 100%;
        height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        border: 2px dashed #cbd5e0;
        border-radius: 6px;
        color: #718096;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .no-image i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #a0aec0;
    }

    /* Tabs Style */
    .nav-tabs {
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 25px;
    }

    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        padding: 12px 24px;
        font-weight: 600;
        color: #64748b;
        transition: all 0.2s;
    }

    .nav-tabs .nav-link:hover {
        border-bottom-color: #94a3b8;
        color: #334155;
    }

    .nav-tabs .nav-link.active {
        border-bottom: 3px solid #3b82f6;
        color: #3b82f6;
        background-color: transparent;
    }

    .nav-tabs .nav-link i {
        margin-right: 8px;
    }

    @media (max-width: 1200px) {
        .team-info {
            grid-template-columns: 180px 1fr;
            gap: 25px;
        }
        .content-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }
    }

    @media (max-width: 992px) {
        .team-info {
            grid-template-columns: 1fr;
            gap: 25px;
        }
        .logo-column {
            align-items: center;
        }
        .jersey-image-container {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .card-body { padding: 20px; }
        .nav-tabs .nav-link { padding: 10px 16px; font-size: 13px; }
    }
</style>
@endpush

<div class="container-fluid py-4">
    <!-- Back Button -->
    <a href="{{ route('admin.tv_team_list') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        Kembali ke Daftar Tim
    </a>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Detail Tim</h1>
        <p class="page-subtitle">Informasi lengkap tim <strong>{{ $teamData['team_name'] ?? $mainTeam->school_name ?? 'Tim' }}</strong></p>
    </div>

    <!-- ===== NAVIGATION TABS ===== -->
    <ul class="nav nav-tabs" id="teamTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $activeTab == 'Basket Putra' ? 'active' : '' }}" 
               href="{{ route('admin.team-list.show', ['id' => $mainTeam->team_id, 'tab' => 'Basket Putra']) }}">
                <i class="fas fa-basketball-ball"></i> Basket Putra
                <span class="badge bg-primary ms-2 rounded-pill">{{ $teamData['total_players_male'] ?? 0 }}</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $activeTab == 'Basket Putri' ? 'active' : '' }}" 
               href="{{ route('admin.team-list.show', ['id' => $mainTeam->team_id, 'tab' => 'Basket Putri']) }}">
                <i class="fas fa-basketball-ball"></i> Basket Putri
                <span class="badge bg-info ms-2 rounded-pill">{{ $teamData['total_players_female'] ?? 0 }}</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $activeTab == 'Dancer' ? 'active' : '' }}" 
               href="{{ route('admin.team-list.show', ['id' => $mainTeam->team_id, 'tab' => 'Dancer']) }}">
                <i class="fas fa-music"></i> Dancer
                <span class="badge bg-purple ms-2 rounded-pill">{{ $teamData['total_dancers'] ?? 0 }}</span>
            </a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link {{ $activeTab == 'Official' ? 'active' : '' }}" 
               href="{{ route('admin.team-list.show', ['id' => $mainTeam->team_id, 'tab' => 'Official']) }}">
                <i class="fas fa-user-tie"></i> Official
                <span class="badge bg-warning ms-2 rounded-pill">{{ $teamData['total_officials'] ?? 0 }}</span>
            </a>
        </li>
    </ul>

    <!-- ===== TAB CONTENT ===== -->
    <div class="tab-content mt-4">
        
        {{-- ================ BASKET PUTRA ================ --}}
        @if($activeTab == 'Basket Putra')
            @php
                $team = $teamData['team_putra'] ?? null;
                $players = $teamData['players_male'] ?? [];
                $officials = $teamData['officials_basket_male'] ?? [];
                $logoUrl = $team->logo_url ?? null;
            @endphp

            @if($team)
                <!-- TEAM INFORMATION CARD -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-info-circle"></i>
                        <span>Informasi Tim Basket Putra</span>
                    </div>
                    <div class="card-body">
                        @include('team_verification.partials.team_info_card', [
                            'team' => $team,
                            'category' => 'Basket Putra',
                            'logoUrl' => $logoUrl
                        ])
                    </div>
                </div>

                <!-- PLAYER LIST CARD -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-users"></i>
                        <span>Daftar Pemain Basket Putra ({{ count($players) }})</span>
                    </div>
                    <div class="card-body">
                        @if(count($players) > 0)
                            <div class="table-container">
                                <table class="data">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>NAMA</th>
                                            <th>ROLE</th>
                                            <th>NO. JERSEY</th>
                                            <th>TANGGAL LAHIR</th>
                                            <th>JENIS KELAMIN</th>
                                            <th>POSISI</th>
                                            <th>KELAS</th>
                                            <th>TAHUN STTB</th>
                                            <th>STATUS</th>
                                            <th>TINDAKAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($players as $index => $player)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $player->name ?? 'N/A' }}</strong></td>
                                            <td class="text-center">
                                                @if(strtolower($player->role ?? '') == 'leader')
                                                    <span class="badge-role-leader">Leader</span>
                                                @else
                                                    <span class="badge-role-player">Pemain</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($player->jersey_number)
                                                    <span class="badge-jersey" style="background: #3b82f6;">{{ $player->jersey_number }}</span>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($player->birthdate)
                                                    {{ \Carbon\Carbon::parse($player->birthdate)->isoFormat('D MMMM YYYY') }}
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(in_array(strtolower($player->gender ?? ''), ['male', 'laki-laki', 'putra']))
                                                    <span class="badge-gender-male">Laki-laki</span>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($player->basketball_position)
                                                    <span class="badge-position">{{ $player->basketball_position }}</span>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($player->grade)
                                                    <span class="badge-grade">{{ $player->grade }}</span>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($player->sttb_year)
                                                    <span class="badge-sttb">{{ $player->sttb_year }}</span>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($player->verification_status == 'verified')
                                                    <span class="status-verified"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                                                @elseif($player->verification_status == 'rejected')
                                                    <span class="status-rejected"><i class="fas fa-times-circle"></i> Ditolak</span>
                                                @else
                                                    <span class="status-unverified"><i class="fas fa-clock"></i> Pending</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.player.detail', $player->id) }}" class="btn-detail">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <h5>Belum ada data pemain</h5>
                                <p>Data pemain akan muncul di sini setelah mendaftar menggunakan referral code tim ini.</p>
                                <p class="text-muted mt-2">
                                    <i class="fas fa-info-circle"></i>
                                    Referral code: <strong>{{ $team->referral_code ?? 'N/A' }}</strong>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- OFFICIAL LIST CARD -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-user-tie"></i>
                        <span>Daftar Official Basket Putra ({{ count($officials) }})</span>
                    </div>
                    <div class="card-body">
                        @if(count($officials) > 0)
                            <div class="table-container">
                                <table class="data official">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>NAMA</th>
                                            <th>JABATAN</th>
                                            <th>EMAIL</th>
                                            <th>TELEPON</th>
                                            <th>JENIS KELAMIN</th>
                                            <th>KATEGORI</th>
                                            <th>STATUS</th>
                                            <th>TINDAKAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($officials as $index => $official)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $official->name ?? 'N/A' }}</strong></td>
                                            <td class="text-center">
                                                <span class="badge-official-role">{{ $official->formatted_team_role ?? $official->team_role ?? '-' }}</span>
                                            </td>
                                            <td>
                                                @if($official->email)
                                                    <a href="mailto:{{ $official->email }}" style="color: #3b82f6;">{{ $official->email }}</a>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($official->phone)
                                                    <a href="tel:{{ $official->phone }}" style="color: #10b981;">{{ $official->phone }}</a>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(strtolower($official->gender ?? '') == 'male')
                                                    <span class="badge-gender-male">Laki-laki</span>
                                                @elseif(strtolower($official->gender ?? '') == 'female')
                                                    <span class="badge-gender-female">Perempuan</span>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge-category badge-category-putra">
                                                    <i class="fas fa-basketball-ball"></i> Basket Putra
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($official->verification_status == 'verified')
                                                    <span class="status-verified"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                                                @elseif($official->verification_status == 'rejected')
                                                    <span class="status-rejected"><i class="fas fa-times-circle"></i> Ditolak</span>
                                                @else
                                                    <span class="status-unverified"><i class="fas fa-clock"></i> Pending</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.official.detail', $official->official_id) }}" class="btn-detail">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-user-tie"></i>
                                <h5>Belum ada data official</h5>
                                <p>Data official Basket Putra akan muncul di sini setelah ditambahkan.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- JERSEY GALLERY -->
                @include('team_verification.partials.jersey_gallery', [
                    'team' => $team,
                    'category' => 'Basket Putra'
                ])
            @else
                <div class="empty-state">
                    <i class="fas fa-basketball-ball" style="color: #3b82f6;"></i>
                    <h5>Tim Basket Putra Belum Terdaftar</h5>
                    <p>Sekolah <strong>{{ $mainTeam->school_name }}</strong> belum mendaftarkan tim untuk kategori Basket Putra.</p>
                    <p class="mt-3">
                        <i class="fas fa-info-circle"></i>
                        Referral code: <strong>{{ $mainTeam->referral_code ?? 'N/A' }}</strong>
                    </p>
                </div>
            @endif

        {{-- ================ BASKET PUTRI ================ --}}
        @elseif($activeTab == 'Basket Putri')
            @php
                $team = $teamData['team_putri'] ?? null;
                $players = $teamData['players_female'] ?? [];
                $officials = $teamData['officials_basket_female'] ?? [];
                $logoUrl = $team->logo_url ?? null;
            @endphp

            @if($team)
                <!-- TEAM INFORMATION CARD -->
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);">
                        <i class="fas fa-info-circle"></i>
                        <span>Informasi Tim Basket Putri</span>
                    </div>
                    <div class="card-body">
                        @include('team_verification.partials.team_info_card', [
                            'team' => $team,
                            'category' => 'Basket Putri',
                            'logoUrl' => $logoUrl
                        ])
                    </div>
                </div>

                <!-- PLAYER LIST CARD -->
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);">
                        <i class="fas fa-users"></i>
                        <span>Daftar Pemain Basket Putri ({{ count($players) }})</span>
                    </div>
                    <div class="card-body">
                        @if(count($players) > 0)
                            <div class="table-container">
                                <table class="data">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>NAMA</th>
                                            <th>ROLE</th>
                                            <th>NO. JERSEY</th>
                                            <th>TANGGAL LAHIR</th>
                                            <th>JENIS KELAMIN</th>
                                            <th>POSISI</th>
                                            <th>KELAS</th>
                                            <th>TAHUN STTB</th>
                                            <th>STATUS</th>
                                            <th>TINDAKAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($players as $index => $player)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $player->name ?? 'N/A' }}</strong></td>
                                            <td class="text-center">
                                                @if(strtolower($player->role ?? '') == 'leader')
                                                    <span class="badge-role-leader">Leader</span>
                                                @else
                                                    <span class="badge-role-player">Pemain</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($player->jersey_number)
                                                    <span class="badge-jersey" style="background: #ec4899;">{{ $player->jersey_number }}</span>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($player->birthdate)
                                                    {{ \Carbon\Carbon::parse($player->birthdate)->isoFormat('D MMMM YYYY') }}
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(in_array(strtolower($player->gender ?? ''), ['female', 'perempuan', 'putri']))
                                                    <span class="badge-gender-female">Perempuan</span>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($player->basketball_position)
                                                    <span class="badge-position">{{ $player->basketball_position }}</span>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($player->grade)
                                                    <span class="badge-grade">{{ $player->grade }}</span>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($player->sttb_year)
                                                    <span class="badge-sttb">{{ $player->sttb_year }}</span>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($player->verification_status == 'verified')
                                                    <span class="status-verified"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                                                @elseif($player->verification_status == 'rejected')
                                                    <span class="status-rejected"><i class="fas fa-times-circle"></i> Ditolak</span>
                                                @else
                                                    <span class="status-unverified"><i class="fas fa-clock"></i> Pending</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.player.detail', $player->id) }}" class="btn-detail">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <h5>Belum ada data pemain</h5>
                                <p>Data pemain akan muncul di sini setelah mendaftar menggunakan referral code tim ini.</p>
                                <p class="text-muted mt-2">
                                    <i class="fas fa-info-circle"></i>
                                    Referral code: <strong>{{ $team->referral_code ?? 'N/A' }}</strong>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- OFFICIAL LIST CARD -->
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);">
                        <i class="fas fa-user-tie"></i>
                        <span>Daftar Official Basket Putri ({{ count($officials) }})</span>
                    </div>
                    <div class="card-body">
                        @if(count($officials) > 0)
                            <div class="table-container">
                                <table class="data official">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>NAMA</th>
                                            <th>JABATAN</th>
                                            <th>EMAIL</th>
                                            <th>TELEPON</th>
                                            <th>JENIS KELAMIN</th>
                                            <th>KATEGORI</th>
                                            <th>STATUS</th>
                                            <th>TINDAKAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($officials as $index => $official)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $official->name ?? 'N/A' }}</strong></td>
                                            <td class="text-center">
                                                <span class="badge-official-role">{{ $official->formatted_team_role ?? $official->team_role ?? '-' }}</span>
                                            </td>
                                            <td>
                                                @if($official->email)
                                                    <a href="mailto:{{ $official->email }}" style="color: #3b82f6;">{{ $official->email }}</a>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($official->phone)
                                                    <a href="tel:{{ $official->phone }}" style="color: #10b981;">{{ $official->phone }}</a>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(strtolower($official->gender ?? '') == 'male')
                                                    <span class="badge-gender-male">Laki-laki</span>
                                                @elseif(strtolower($official->gender ?? '') == 'female')
                                                    <span class="badge-gender-female">Perempuan</span>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge-category badge-category-putri">
                                                    <i class="fas fa-basketball-ball"></i> Basket Putri
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($official->verification_status == 'verified')
                                                    <span class="status-verified"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                                                @elseif($official->verification_status == 'rejected')
                                                    <span class="status-rejected"><i class="fas fa-times-circle"></i> Ditolak</span>
                                                @else
                                                    <span class="status-unverified"><i class="fas fa-clock"></i> Pending</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.official.detail', $official->official_id) }}" class="btn-detail">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-user-tie"></i>
                                <h5>Belum ada data official</h5>
                                <p>Data official Basket Putri akan muncul di sini setelah ditambahkan.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- JERSEY GALLERY -->
                @include('team_verification.partials.jersey_gallery', [
                    'team' => $team,
                    'category' => 'Basket Putri'
                ])
            @else
                <div class="empty-state">
                    <i class="fas fa-basketball-ball" style="color: #ec4899;"></i>
                    <h5>Tim Basket Putri Belum Terdaftar</h5>
                    <p>Sekolah <strong>{{ $mainTeam->school_name }}</strong> belum mendaftarkan tim untuk kategori Basket Putri.</p>
                    <p class="mt-3">
                        <i class="fas fa-info-circle"></i>
                        Referral code: <strong>{{ $mainTeam->referral_code ?? 'N/A' }}</strong>
                    </p>
                </div>
            @endif

        {{-- ================ DANCER ================ --}}
        @elseif($activeTab == 'Dancer')
            @php
                $team = $teamData['team_dancer'] ?? null;
                $dancers = $teamData['dancers'] ?? [];
                $officials = $teamData['officials_dancer'] ?? [];
                $logoUrl = $team->logo_url ?? null;
            @endphp

            @if($team)
                <!-- TEAM INFORMATION CARD -->
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                        <i class="fas fa-info-circle"></i>
                        <span>Informasi Tim Dancer</span>
                    </div>
                    <div class="card-body">
                        @include('team_verification.partials.team_info_card', [
                            'team' => $team,
                            'category' => 'Dancer',
                            'logoUrl' => $logoUrl
                        ])
                    </div>
                </div>

                <!-- DANCER LIST CARD -->
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                        <i class="fas fa-music"></i>
                        <span>Daftar Dancer ({{ count($dancers) }})</span>
                    </div>
                    <div class="card-body">
                        @if(count($dancers) > 0)
                            <div class="table-container">
                                <table class="data dancer">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>NAMA</th>
                                            <th>ROLE</th>
                                            <th>TANGGAL LAHIR</th>
                                            <th>JENIS KELAMIN</th>
                                            <th>TINGGI</th>
                                            <th>BERAT</th>
                                            <th>UKURAN KAOS</th>
                                            <th>STATUS</th>
                                            <th>TINDAKAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dancers as $index => $dancer)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $dancer->name ?? 'N/A' }}</strong></td>
                                            <td class="text-center">
                                                @if(strtolower($dancer->role ?? '') == 'leader')
                                                    <span class="badge-role-leader">Leader</span>
                                                @else
                                                    <span class="badge-role-player">Member</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($dancer->birthdate)
                                                    {{ \Carbon\Carbon::parse($dancer->birthdate)->isoFormat('D MMMM YYYY') }}
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(in_array(strtolower($dancer->gender ?? ''), ['male', 'laki-laki']))
                                                    <span class="badge-gender-male">Laki-laki</span>
                                                @elseif(in_array(strtolower($dancer->gender ?? ''), ['female', 'perempuan']))
                                                    <span class="badge-gender-female">Perempuan</span>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td>{{ $dancer->height ?? '-' }} cm</td>
                                            <td>{{ $dancer->weight ?? '-' }} kg</td>
                                            <td>{{ $dancer->tshirt_size ?? '-' }}</td>
                                            <td>
                                                @if($dancer->verification_status == 'verified')
                                                    <span class="status-verified"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                                                @elseif($dancer->verification_status == 'rejected')
                                                    <span class="status-rejected"><i class="fas fa-times-circle"></i> Ditolak</span>
                                                @else
                                                    <span class="status-unverified"><i class="fas fa-clock"></i> Pending</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.dancer.detail', $dancer->dancer_id) }}" class="btn-detail">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-music"></i>
                                <h5>Belum ada data dancer</h5>
                                <p>Data dancer akan muncul di sini setelah mendaftar menggunakan referral code tim ini.</p>
                                <p class="text-muted mt-2">
                                    <i class="fas fa-info-circle"></i>
                                    Referral code: <strong>{{ $team->referral_code ?? 'N/A' }}</strong>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- OFFICIAL LIST CARD -->
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                        <i class="fas fa-user-tie"></i>
                        <span>Daftar Official Dancer ({{ count($officials) }})</span>
                    </div>
                    <div class="card-body">
                        @if(count($officials) > 0)
                            <div class="table-container">
                                <table class="data official">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>NAMA</th>
                                            <th>JABATAN</th>
                                            <th>EMAIL</th>
                                            <th>TELEPON</th>
                                            <th>JENIS KELAMIN</th>
                                            <th>KATEGORI</th>
                                            <th>STATUS</th>
                                            <th>TINDAKAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($officials as $index => $official)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td><strong>{{ $official->name ?? 'N/A' }}</strong></td>
                                            <td class="text-center">
                                                <span class="badge-official-role">{{ $official->formatted_team_role ?? $official->team_role ?? '-' }}</span>
                                            </td>
                                            <td>
                                                @if($official->email)
                                                    <a href="mailto:{{ $official->email }}" style="color: #3b82f6;">{{ $official->email }}</a>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($official->phone)
                                                    <a href="tel:{{ $official->phone }}" style="color: #10b981;">{{ $official->phone }}</a>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(strtolower($official->gender ?? '') == 'male')
                                                    <span class="badge-gender-male">Laki-laki</span>
                                                @elseif(strtolower($official->gender ?? '') == 'female')
                                                    <span class="badge-gender-female">Perempuan</span>
                                                @else
                                                    <span style="color: #718096;">-</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="badge-category badge-category-dancer">
                                                    <i class="fas fa-music"></i> Dancer
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if($official->verification_status == 'verified')
                                                    <span class="status-verified"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                                                @elseif($official->verification_status == 'rejected')
                                                    <span class="status-rejected"><i class="fas fa-times-circle"></i> Ditolak</span>
                                                @else
                                                    <span class="status-unverified"><i class="fas fa-clock"></i> Pending</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('admin.official.detail', $official->official_id) }}" class="btn-detail">
                                                    <i class="fas fa-eye"></i> Detail
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-user-tie"></i>
                                <h5>Belum ada data official</h5>
                                <p>Data official Dancer akan muncul di sini setelah ditambahkan.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- JERSEY GALLERY -->
                @include('team_verification.partials.jersey_gallery', [
                    'team' => $team,
                    'category' => 'Dancer'
                ])
            @else
                <div class="empty-state">
                    <i class="fas fa-music" style="color: #8b5cf6;"></i>
                    <h5>Tim Dancer Belum Terdaftar</h5>
                    <p>Sekolah <strong>{{ $mainTeam->school_name }}</strong> belum mendaftarkan tim untuk kategori Dancer.</p>
                    <p class="mt-3">
                        <i class="fas fa-info-circle"></i>
                        Referral code: <strong>{{ $mainTeam->referral_code ?? 'N/A' }}</strong>
                    </p>
                </div>
            @endif

        {{-- ================ OFFICIAL (ALL) ================ --}}
        @elseif($activeTab == 'Official')
            @php
                $officials = $teamData['all_officials'] ?? [];
            @endphp

            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <i class="fas fa-user-tie"></i>
                    <span>Daftar Semua Official ({{ count($officials) }})</span>
                </div>
                <div class="card-body">
                    @if(count($officials) > 0)
                        <div class="table-container">
                            <table class="data official">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NAMA</th>
                                        <th>JABATAN</th>
                                        <th>EMAIL</th>
                                        <th>TELEPON</th>
                                        <th>JENIS KELAMIN</th>
                                        <th>KATEGORI</th>
                                        <th>STATUS</th>
                                        <th>TINDAKAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($officials as $index => $official)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $official->name ?? 'N/A' }}</strong></td>
                                        <td class="text-center">
                                            <span class="badge-official-role">{{ $official->formatted_team_role ?? $official->team_role ?? '-' }}</span>
                                        </td>
                                        <td>
                                            @if($official->email)
                                                <a href="mailto:{{ $official->email }}" style="color: #3b82f6;">{{ $official->email }}</a>
                                            @else
                                                <span style="color: #718096;">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($official->phone)
                                                <a href="tel:{{ $official->phone }}" style="color: #10b981;">{{ $official->phone }}</a>
                                            @else
                                                <span style="color: #718096;">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if(strtolower($official->gender ?? '') == 'male')
                                                <span class="badge-gender-male">Laki-laki</span>
                                            @elseif(strtolower($official->gender ?? '') == 'female')
                                                <span class="badge-gender-female">Perempuan</span>
                                            @else
                                                <span style="color: #718096;">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($official->category == 'basket_putra')
                                                <span class="badge-category badge-category-putra">
                                                    <i class="fas fa-basketball-ball"></i> Basket Putra
                                                </span>
                                            @elseif($official->category == 'basket_putri')
                                                <span class="badge-category badge-category-putri">
                                                    <i class="fas fa-basketball-ball"></i> Basket Putri
                                                </span>
                                            @elseif($official->category == 'dancer')
                                                <span class="badge-category badge-category-dancer">
                                                    <i class="fas fa-music"></i> Dancer
                                                </span>
                                            @else
                                                <span class="badge-category badge-category-lainnya">
                                                    {{ $official->category_display ?? $official->category ?? '-' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($official->verification_status == 'verified')
                                                <span class="status-verified"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                                            @elseif($official->verification_status == 'rejected')
                                                <span class="status-rejected"><i class="fas fa-times-circle"></i> Ditolak</span>
                                            @else
                                                <span class="status-unverified"><i class="fas fa-clock"></i> Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.official.detail', $official->official_id) }}" class="btn-detail">
                                                <i class="fas fa-eye"></i> Detail
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="empty-state">
                            <i class="fas fa-user-tie"></i>
                            <h5>Belum ada data official</h5>
                            <p>Belum ada official yang terdaftar untuk sekolah ini.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to show alert for missing documents
        window.showAlert = function(documentName) {
            Swal.fire({
                title: 'Dokumen Tidak Tersedia',
                html: `<strong>${documentName}</strong> belum diupload oleh tim.<br><br>
                      Silakan hubungi tim untuk mengupload dokumen ini.`,
                icon: 'warning',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#f59e0b',
                timer: 5000,
                timerProgressBar: true
            });
            return false;
        };

        // Function for confirmation actions
        window.confirmAction = function(message) {
            return Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Lanjutkan',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#6b7280',
                reverseButtons: true
            }).then((result) => {
                return result.isConfirmed;
            });
        };

        // Function to show logo popup
        window.showLogoPopup = function(logoUrl, schoolName, category) {
            if (logoUrl) {
                Swal.fire({
                    title: 'Logo Sekolah',
                    html: `<div style="text-align: center;">
                        <img src="${logoUrl}" alt="Logo Sekolah" style="max-width: 400px; max-height: 400px; border-radius: 12px; margin-bottom: 20px; border: 2px solid #dbeafe; padding: 10px; background: white;">
                        <p style="color: #666; font-size: 16px; font-weight: 600;">${schoolName}</p>
                        <p style="color: #6b7280; font-size: 14px;">${category}</p>
                    </div>`,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: 550,
                    padding: '25px',
                    background: '#fff'
                });
            } else {
                Swal.fire({
                    title: 'Logo Sekolah',
                    html: `<div style="text-align: center;">
                        <div style="width: 250px; height: 250px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 2px dashed #bfdbfe; border-radius: 12px; margin: 0 auto 20px;">
                            <i class="fas fa-school" style="font-size: 4rem; color: #3b82f6; margin-bottom: 20px;"></i>
                            <span style="color: #3b82f6; font-size: 16px; font-weight: 500;">Logo Tidak Tersedia</span>
                        </div>
                        <p style="color: #666; font-size: 16px; font-weight: 600;">${schoolName}</p>
                        <p style="color: #6b7280; font-size: 14px;">${category}</p>
                    </div>`,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: 500,
                    padding: '25px',
                    background: '#fff'
                });
            }
        };

        // Function to show jersey/costume popup
        window.showJerseyPopup = function(imageUrl, title, schoolName, category) {
            if (imageUrl) {
                Swal.fire({
                    title: `${title} - ${category}`,
                    html: `<div style="text-align: center;">
                        <img src="${imageUrl}" alt="${title}" style="max-width: 400px; max-height: 400px; border-radius: 8px; margin-bottom: 15px;">
                        <p style="color: #666; font-size: 14px;">${schoolName}</p>
                    </div>`,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: 550,
                    padding: '20px',
                    background: '#fff'
                });
            } else {
                Swal.fire({
                    title: `${title} - ${category}`,
                    html: `<div style="text-align: center;">
                        <div style="width: 300px; height: 300px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #f7fafc, #edf2f7); border: 2px dashed #cbd5e0; border-radius: 8px; margin: 0 auto 15px;">
                            <i class="fas fa-tshirt" style="font-size: 4rem; color: #a0aec0; margin-bottom: 20px;"></i>
                            <span style="color: #718096; font-size: 16px;">${title} Belum Diupload</span>
                        </div>
                        <p style="color: #666; font-size: 14px;">${schoolName}</p>
                    </div>`,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: 500,
                    padding: '20px',
                    background: '#fff'
                });
            }
        };
    });
</script>
@endpush
@endsection