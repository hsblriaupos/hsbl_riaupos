@extends('admin.layouts.app')
@section('title', 'Detail Tim - Administrator')

@section('content')
@include('partials.sweetalert')

@push('styles')
<style>
    /* ===== BASE & RESET ===== */
    * {
        box-sizing: border-box;
    }

    html,
    body {
        overflow-x: hidden;
        width: 100%;
        background: #f4f6f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .container-fluid {
        width: 100%;
        max-width: 100%;
        padding: 15px;
        margin: 0 auto;
    }

    /* ===== PAGE HEADER ===== */
    .page-header {
        margin-bottom: 20px;
    }

    .page-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .page-subtitle {
        color: #7f8c8d;
        font-size: 0.85rem;
    }

    /* ===== CARD STYLES ===== */
    .card {
        background: #fff;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border: 1px solid #e0e0e0;
        overflow: hidden;
    }

    .card-header {
        padding: 14px 20px;
        font-weight: 600;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-header i {
        font-size: 1.1rem;
    }

    .card-body {
        padding: 20px;
    }

    /* ===== PAYMENT PROOF CARD ===== */
    .payment-proof-card {
        margin-bottom: 20px;
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border: 1px solid #bbf7d0;
    }

    .payment-proof-card .card-header {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    }

    .payment-proof-content {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }

    .payment-proof-label {
        font-weight: 700;
        color: #166534;
        background: #dcfce7;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .payment-proof-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: white;
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        transition: all 0.3s ease;
        border: 1px solid #bbf7d0;
    }

    .payment-proof-link.available {
        color: #16a34a;
        background: #f0fdf4;
    }

    .payment-proof-link.available:hover {
        background: #dcfce7;
        transform: translateY(-2px);
    }

    .payment-proof-link.unavailable {
        color: #dc2626;
        background: #fef2f2;
        border-color: #fecaca;
        cursor: not-allowed;
    }

    .payment-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    .payment-status-paid {
        background: #dcfce7;
        color: #166534;
    }

    .payment-status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .payment-status-failed {
        background: #fee2e2;
        color: #991b1b;
    }

    /* PAYMENT & ACTION CARD - 2 KOLOM */
    .payment-action-card {
        margin-bottom: 20px;
        border: 1px solid #bbf7d0;
    }

    .payment-action-card .card-header {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    }

    .payment-info-box,
    .action-buttons-box {
        padding: 10px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        height: 100%;
    }

    .action-buttons-title {
        font-size: 13px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 2px solid #dbeafe;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-buttons-row {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .btn-action-simple {
        color: #fff;
        padding: 10px 15px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        transition: all 0.2s ease;
    }

    .btn-action-simple:hover {
        transform: translateY(-1px);
        filter: brightness(1.05);
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

    /* ===== TEAM INFORMATION ===== */
    .team-info-card {
        margin-bottom: 20px;
    }

    .team-info {
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
        align-items: flex-start;
    }

    .logo-column {
        flex: 0 0 180px;
    }

    .logo-box-square {
        text-align: center;
        padding: 15px;
        background: #f8fafc;
        border-radius: 12px;
        border: 2px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .logo-box-square:hover {
        border-color: #3b82f6;
        transform: translateY(-3px);
    }

    .logo-box-square img {
        width: 120px;
        height: 120px;
        object-fit: contain;
        margin-bottom: 10px;
        border-radius: 8px;
        background: white;
        padding: 10px;
        border: 1px solid #e2e8f0;
    }

    .logo-placeholder {
        width: 120px;
        height: 120px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        border: 2px dashed #bfdbfe;
        border-radius: 8px;
        color: #3b82f6;
        font-size: 12px;
    }

    .content-column {
        flex: 1;
        min-width: 0;
    }

    .content-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 25px;
    }

    .info-section,
    .status-doc-section {
        flex: 1;
        min-width: 250px;
    }

    /* ===== INFO TABLES ===== */
    .info-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .info-table td {
        padding: 10px 12px;
        vertical-align: middle;
        font-size: 13px;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-table td:first-child {
        width: 120px;
        color: #2d3748;
        font-weight: 600;
        background: #f8fafc;
        border-right: 1px solid #e2e8f0;
    }

    .info-table.compact td {
        padding: 8px 12px;
        font-size: 12px;
    }

    .info-table.compact td:first-child {
        width: 110px;
    }

    /* ===== STATUS BADGES ===== */
    .status-open,
    .status-locked,
    .status-verified,
    .status-unverified,
    .status-rejected {
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        white-space: nowrap;
    }

    .status-open {
        color: #dc2626;
        background: #fef2f2;
        border: 1px solid #fecaca;
    }

    .status-locked {
        color: #059669;
        background: #d1fae5;
        border: 1px solid #a7f3d0;
    }

    .status-verified {
        color: #059669;
        background: #d1fae5;
        border: 1px solid #a7f3d0;
    }

    .status-unverified {
        color: #d97706;
        background: #fef3c7;
        border: 1px solid #fde68a;
    }

    .status-rejected {
        color: #dc2626;
        background: #fee2e2;
        border: 1px solid #fecaca;
    }

    /* ===== DOCUMENTS ===== */
    .documents-section h4,
    .action-buttons h4 {
        font-size: 13px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 2px solid #dbeafe;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .document-links.compact,
    .action-buttons-row.compact {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .document-link {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        font-size: 12px;
        font-weight: 500;
        padding: 10px 12px;
        border-radius: 8px;
        transition: all 0.3s ease;
        border: 1px solid transparent;
    }

    .document-link.available {
        color: #1e40af;
        background: #eff6ff;
        border-color: #dbeafe;
    }

    .document-link.warning {
        color: #92400e;
        background: #fffbeb;
        border-color: #fde68a;
    }

    .document-link.danger {
        color: #7f1d1d;
        background: #fef2f2;
        border-color: #fecaca;
    }

    /* ===== TABEL RESPONSIF ===== */
    .table-container {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        background: white;
    }

    .table-container::-webkit-scrollbar {
        height: 6px;
    }

    .table-container::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    .table-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }

    table.data {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
        table-layout: auto;
        min-width: 800px;
    }

    table.data th {
        background: #f8fafc;
        font-weight: 700;
        color: #2d3748;
        padding: 12px 10px;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    table.data td {
        border-bottom: 1px solid #f1f5f9;
        padding: 10px 8px;
        vertical-align: middle;
        color: #4a5568;
        font-size: 12px;
        word-break: break-word;
    }

    table.data tbody tr:hover {
        background-color: #f8fafc;
    }

    table.data td:first-child,
    table.data th:first-child {
        text-align: center;
        width: 40px;
    }

    /* ===== BADGES ===== */
    .badge-jersey {
        display: inline-block;
        width: 32px;
        height: 32px;
        line-height: 32px;
        background: #3b82f6;
        color: white;
        border-radius: 50%;
        font-weight: bold;
        font-size: 12px;
        text-align: center;
    }

    .badge-gender-male {
        background: #dbeafe;
        color: #1e40af;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }

    .badge-gender-female {
        background: #fce7f3;
        color: #be185d;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }

    .badge-position,
    .badge-grade,
    .badge-sttb,
    .badge-role-player,
    .badge-official-role {
        background: #f1f5f9;
        color: #475569;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }

    .badge-role-leader {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 600;
        display: inline-block;
        white-space: nowrap;
    }

    .badge-category {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 600;
        color: white;
        white-space: nowrap;
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

    /* ===== BUTTON DETAIL ===== */
    .btn-detail {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        white-space: nowrap;
    }

    .btn-detail:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        color: white;
        text-decoration: none;
    }

    /* ===== BACK LINK ===== */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #4a5568;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 20px;
        padding: 8px 15px;
        background: white;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
    }

    .back-link:hover {
        background: #f8fafc;
        color: #1e40af;
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 10px;
        border: 1px dashed #cbd5e0;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #cbd5e0;
    }

    .empty-state h5 {
        font-size: 1.1rem;
        color: #4a5568;
        margin-bottom: 8px;
    }

    .empty-state p {
        font-size: 12px;
        color: #718096;
    }

    /* ===== JERSEY GALLERY ===== */
    .jersey-single-container {
        padding: 20px;
        background: #f8fafc;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
    }

    .jersey-main-title {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 15px;
        text-align: center;
    }

    .jersey-image-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    .jersey-single-item {
        text-align: center;
        cursor: pointer;
        background: white;
        border-radius: 8px;
        padding: 12px;
        border: 1px solid #e2e8f0;
        flex: 1;
        min-width: 140px;
        max-width: 180px;
    }

    .jersey-single-item:hover {
        transform: translateY(-3px);
        border-color: #3b82f6;
    }

    .jersey-single-item p {
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .jersey-image {
        width: 100%;
        height: 100px;
        object-fit: contain;
        padding: 8px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
    }

    .no-image {
        width: 100%;
        height: 100px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #f7fafc;
        border: 1px dashed #cbd5e0;
        border-radius: 6px;
        font-size: 11px;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
        .container-fluid {
            padding: 12px;
        }

        .card-body {
            padding: 15px;
        }

        .team-info {
            flex-direction: column;
            align-items: center;
        }

        .logo-column {
            flex: 0 0 auto;
        }

        .content-grid {
            flex-direction: column;
        }

        .info-table td:first-child {
            width: 100px;
        }

        table.data th,
        table.data td {
            padding: 8px 6px;
            font-size: 11px;
        }

        table.data th {
            font-size: 10px;
        }

        .btn-detail {
            padding: 3px 6px;
            font-size: 9px;
        }

        .badge-jersey {
            width: 28px;
            height: 28px;
            line-height: 28px;
            font-size: 11px;
        }

        .jersey-single-item {
            min-width: 110px;
        }
    }

    @media (max-width: 480px) {
        .container-fluid {
            padding: 10px;
        }

        .page-title {
            font-size: 1.2rem;
        }

        .badge-jersey {
            width: 24px;
            height: 24px;
            line-height: 24px;
            font-size: 10px;
        }
    }
</style>
@endpush

<div class="container-fluid">
    <!-- Back Button -->
    <a href="{{ route('admin.tv_team_list') }}" class="back-link">
        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Tim
    </a>

    <!-- TEAM INFO CARD -->
    <div class="card team-info-card">
        <div class="card-header">
            <i class="fas fa-info-circle"></i>
            Informasi Tim - {{ $mainTeam->school_name }}
        </div>
        <div class="card-body">
            <div class="team-info">
                <div class="logo-column">
                    <div class="logo-box-square" onclick="showLogoPopup('{{ $mainTeam->logo_url ?? '' }}', '{{ $mainTeam->school_name }}', 'Main Team')">
                        @if(isset($mainTeam->logo_url) && $mainTeam->logo_url)
                            <img src="{{ $mainTeam->logo_url }}" alt="Logo {{ $mainTeam->school_name }}">
                        @else
                            <div class="logo-placeholder">
                                <i class="fas fa-school fa-3x mb-2"></i>
                                <span>No Logo</span>
                            </div>
                        @endif
                        <small class="text-muted mt-2">Klik untuk perbesar</small>
                    </div>
                </div>
                <div class="content-column">
                    <div class="content-grid">
                        <div class="info-section">
                            <table class="info-table compact">
                                <tr><td>Nama Sekolah</td><td><strong>{{ $mainTeam->school_name }}</strong></td></tr>
                                <tr><td>Competition</td><td>{{ $mainTeam->competition ?? '-' }}</td></tr>
                                <tr><td>Season</td><td>{{ $mainTeam->season ?? '-' }}</td></tr>
                                <tr><td>Series</td><td>{{ $mainTeam->series ?? '-' }}</td></tr>
                                <tr><td>Referral Code</td><td><code>{{ $mainTeam->referral_code ?? '-' }}</code></td></tr>
                                <tr><td>Registered By</td><td>{{ $mainTeam->registered_by ?? '-' }}</td></tr>
                            </table>
                        </div>
                        <div class="status-doc-section">
                            <table class="info-table compact">
                                <tr>
                                    <td>Status Kunci</td>
                                    <td>
                                        @if($mainTeam->locked_status == 'locked')
                                            <span class="status-locked"><i class="fas fa-lock"></i> Terkunci</span>
                                        @else
                                            <span class="status-open"><i class="fas fa-lock-open"></i> Terbuka</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status Verifikasi</td>
                                    <td>
                                        @if($mainTeam->verification_status == 'verified')
                                            <span class="status-verified"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                                        @else
                                            <span class="status-unverified"><i class="fas fa-clock"></i> Belum Verifikasi</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- TABS -->
    @include('team_verification.partials.category-tabs', [
        'teamData' => $teamData,
        'activeTab' => $activeTab,
        'mainTeam' => $mainTeam
    ])

    <!-- TAB CONTENT -->
    <div class="tab-content">
        
        {{-- ==================== BASKET PUTRA ==================== --}}
        @if($activeTab == 'Basket Putra')
        @php
            $team = $teamData['team_putra'] ?? null;
            $players = $teamData['players_male'] ?? [];
            $officials = $teamData['officials_basket_male'] ?? [];
        @endphp

        @if($team)
            <div class="card payment-action-card">
                <div class="card-header">
                    <i class="fas fa-receipt"></i>
                    Bukti Pembayaran & Aksi Tim - Basket Putra
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="payment-info-box">
                                <div class="payment-proof-label mb-2">
                                    <i class="fas fa-money-bill-wave"></i> Status Pembayaran:
                                    @if($team->payment_status == 'paid')
                                        <span class="payment-status-badge payment-status-paid"><i class="fas fa-check-circle"></i> Lunas</span>
                                    @elseif($team->payment_status == 'failed')
                                        <span class="payment-status-badge payment-status-failed"><i class="fas fa-times-circle"></i> Gagal</span>
                                    @else
                                        <span class="payment-status-badge payment-status-pending"><i class="fas fa-clock"></i> Pending</span>
                                    @endif
                                </div>

                                @if($team->payment_proof)
                                    <a href="{{ Storage::url($team->payment_proof) }}" target="_blank" class="payment-proof-link available">
                                        <i class="fas fa-file-invoice-dollar"></i> Lihat Bukti Pembayaran
                                    </a>
                                @else
                                    <span class="payment-proof-link unavailable" onclick="showAlert('Bukti Pembayaran')">
                                        <i class="fas fa-exclamation-triangle"></i> Bukti Pembayaran Belum Diupload
                                    </span>
                                @endif

                                @if($team->payment_date)
                                    <div class="mt-2">
                                        <small class="text-muted"><i class="fas fa-calendar-alt"></i> Tanggal Bayar: {{ \Carbon\Carbon::parse($team->payment_date)->isoFormat('D MMM YYYY, HH:mm') }}</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="action-buttons-box">
                                <div class="action-buttons-title">
                                    <i class="fas fa-cogs"></i> Aksi Tim
                                </div>
                                <div class="action-buttons-row">
                                    @if($team->locked_status != 'locked')
                                        <form action="{{ route('admin.team.lock', $team->team_id) }}" method="POST" onsubmit="return confirmAction('Kunci tim Basket Putra?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-lock">
                                                <i class="fas fa-lock"></i> Kunci Tim
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.team.unlock', $team->team_id) }}" method="POST" onsubmit="return confirmAction('Buka kunci tim Basket Putra?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-unlock">
                                                <i class="fas fa-unlock"></i> Buka Kunci
                                            </button>
                                        </form>
                                    @endif

                                    @if($team->verification_status != 'verified')
                                        <form action="{{ route('admin.team.verify', $team->team_id) }}" method="POST" onsubmit="return confirmAction('Verifikasi tim Basket Putra?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-verify">
                                                <i class="fas fa-check-circle"></i> Verifikasi Tim
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.team.unverify', $team->team_id) }}" method="POST" onsubmit="return confirmAction('Batalkan verifikasi tim Basket Putra?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-unverify">
                                                <i class="fas fa-times-circle"></i> Batalkan Verifikasi
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PLAYERS TABLE -->
            <div class="card">
                <div class="card-header"><i class="fas fa-users"></i> Daftar Pemain Basket Putra ({{ count($players) }})</div>
                <div class="card-body">
                    @if(count($players) > 0)
                        <div class="table-container">
                            <table class="data">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Jersey</th>
                                        <th>Gender</th>
                                        <th>Posisi</th>
                                        <th>Tinggi</th>
                                        <th>Role Tim</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($players as $index => $player)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $player->name ?? 'N/A' }}</strong>
                                            @if(isset($player->email) && $player->email)
                                                <br><small class="text-muted">{{ $player->email }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($player->jersey_number)
                                                <span class="badge-jersey">{{ $player->jersey_number }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $gender = strtolower($player->gender ?? $player->category ?? '');
                                            @endphp
                                            @if(in_array($gender, ['male', 'putra', 'laki-laki']))
                                                <span class="badge-gender-male"><i class="fas fa-male"></i> Putra</span>
                                            @elseif(in_array($gender, ['female', 'putri', 'perempuan']))
                                                <span class="badge-gender-female"><i class="fas fa-female"></i> Putri</span>
                                            @else
                                                <span class="badge-gender-male">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($player->basketball_position)
                                                <span class="badge-position">{{ $player->basketball_position }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($player->height)
                                                {{ $player->height }} cm
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if(strtolower($player->role ?? '') == 'leader')
                                                <span class="badge-role-leader"><i class="fas fa-crown"></i> Leader</span>
                                            @else
                                                <span class="badge-role-player">Player</span>
                                            @endif
                                        </td>
                                        <td>
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
                            <p>Data pemain akan muncul setelah mendaftar menggunakan referral code tim ini.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- OFFICIALS TABLE -->
            <div class="card">
                <div class="card-header"><i class="fas fa-user-tie"></i> Daftar Official Basket Putra ({{ count($officials) }})</div>
                <div class="card-body">
                    @if(count($officials) > 0)
                        <div class="table-container">
                            <table class="data">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Email</th>
                                        <th>Telepon</th>
                                        <th>Gender</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($officials as $index => $official)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $official->name ?? 'N/A' }}</strong></td>
                                        <td><span class="badge-official-role">{{ $official->formatted_team_role ?? $official->team_role ?? '-' }}</span></td>
                                        <td>@if($official->email)<a href="mailto:{{ $official->email }}" style="color:#3b82f6;">{{ $official->email }}</a>@else-@endif</td>
                                        <td>@if($official->phone)<a href="tel:{{ $official->phone }}" style="color:#10b981;">{{ $official->phone }}</a>@else-@endif</td>
                                        <td>
                                            @if(strtolower($official->gender ?? '') == 'male')
                                                <span class="badge-gender-male"><i class="fas fa-male"></i> Laki-laki</span>
                                            @elseif(strtolower($official->gender ?? '') == 'female')
                                                <span class="badge-gender-female"><i class="fas fa-female"></i> Perempuan</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($official->verification_status == 'verified')
                                                <span class="status-verified"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                                            @elseif($official->verification_status == 'rejected')
                                                <span class="status-rejected"><i class="fas fa-times-circle"></i> Ditolak</span>
                                            @else
                                                <span class="status-unverified"><i class="fas fa-clock"></i> Pending</span>
                                            @endif
                                        </td>
                                        <td>
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
                        </div>
                    @endif
                </div>
            </div>

            @include('team_verification.partials.jersey_gallery', ['team' => $team, 'category' => 'Basket Putra'])
        @else
            <div class="empty-state">
                <i class="fas fa-basketball-ball" style="color:#3b82f6;"></i>
                <h5>Tim Basket Putra Belum Terdaftar</h5>
                <p>Sekolah <strong>{{ $mainTeam->school_name }}</strong> belum mendaftarkan tim untuk kategori ini.</p>
            </div>
        @endif

        {{-- ==================== BASKET PUTRI ==================== --}}
        @elseif($activeTab == 'Basket Putri')
        @php
            $team = $teamData['team_putri'] ?? null;
            $players = $teamData['players_female'] ?? [];
            $officials = $teamData['officials_basket_female'] ?? [];
        @endphp

        @if($team)
            <div class="card payment-action-card">
                <div class="card-header" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);">
                    <i class="fas fa-receipt"></i>
                    Bukti Pembayaran & Aksi Tim - Basket Putri
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="payment-info-box">
                                <div class="payment-proof-label mb-2">
                                    <i class="fas fa-money-bill-wave"></i> Status Pembayaran:
                                    @if($team->payment_status == 'paid')
                                        <span class="payment-status-badge payment-status-paid"><i class="fas fa-check-circle"></i> Lunas</span>
                                    @elseif($team->payment_status == 'failed')
                                        <span class="payment-status-badge payment-status-failed"><i class="fas fa-times-circle"></i> Gagal</span>
                                    @else
                                        <span class="payment-status-badge payment-status-pending"><i class="fas fa-clock"></i> Pending</span>
                                    @endif
                                </div>

                                @if($team->payment_proof)
                                    <a href="{{ Storage::url($team->payment_proof) }}" target="_blank" class="payment-proof-link available">
                                        <i class="fas fa-file-invoice-dollar"></i> Lihat Bukti Pembayaran
                                    </a>
                                @else
                                    <span class="payment-proof-link unavailable" onclick="showAlert('Bukti Pembayaran')">
                                        <i class="fas fa-exclamation-triangle"></i> Bukti Pembayaran Belum Diupload
                                    </span>
                                @endif

                                @if($team->payment_date)
                                    <div class="mt-2">
                                        <small class="text-muted"><i class="fas fa-calendar-alt"></i> Tanggal Bayar: {{ \Carbon\Carbon::parse($team->payment_date)->isoFormat('D MMM YYYY, HH:mm') }}</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="action-buttons-box">
                                <div class="action-buttons-title">
                                    <i class="fas fa-cogs"></i> Aksi Tim
                                </div>
                                <div class="action-buttons-row">
                                    @if($team->locked_status != 'locked')
                                        <form action="{{ route('admin.team.lock', $team->team_id) }}" method="POST" onsubmit="return confirmAction('Kunci tim Basket Putri?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-lock"><i class="fas fa-lock"></i> Kunci Tim</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.team.unlock', $team->team_id) }}" method="POST" onsubmit="return confirmAction('Buka kunci tim Basket Putri?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-unlock"><i class="fas fa-unlock"></i> Buka Kunci</button>
                                        </form>
                                    @endif
                                    @if($team->verification_status != 'verified')
                                        <form action="{{ route('admin.team.verify', $team->team_id) }}" method="POST" onsubmit="return confirmAction('Verifikasi tim Basket Putri?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-verify"><i class="fas fa-check-circle"></i> Verifikasi Tim</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.team.unverify', $team->team_id) }}" method="POST" onsubmit="return confirmAction('Batalkan verifikasi tim Basket Putri?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-unverify"><i class="fas fa-times-circle"></i> Batalkan Verifikasi</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);">
                    <i class="fas fa-users"></i> Daftar Pemain Basket Putri ({{ count($players) }})
                </div>
                <div class="card-body">
                    @if(count($players) > 0)
                        <div class="table-container">
                            <table class="data">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Jersey</th>
                                        <th>Gender</th>
                                        <th>Posisi</th>
                                        <th>Tinggi</th>
                                        <th>Role Tim</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($players as $index => $player)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $player->name ?? 'N/A' }}</strong>
                                            @if(isset($player->email) && $player->email)
                                                <br><small class="text-muted">{{ $player->email }}</small>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($player->jersey_number)
                                                <span class="badge-jersey">{{ $player->jersey_number }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $gender = strtolower($player->gender ?? $player->category ?? '');
                                            @endphp
                                            @if(in_array($gender, ['female', 'putri', 'perempuan']))
                                                <span class="badge-gender-female"><i class="fas fa-female"></i> Putri</span>
                                            @else
                                                <span class="badge-gender-female">Putri</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($player->basketball_position)
                                                <span class="badge-position">{{ $player->basketball_position }}</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($player->height)
                                                {{ $player->height }} cm
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if(strtolower($player->role ?? '') == 'leader')
                                                <span class="badge-role-leader"><i class="fas fa-crown"></i> Leader</span>
                                            @else
                                                <span class="badge-role-player">Player</span>
                                            @endif
                                        </td>
                                        <td>
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
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);">
                    <i class="fas fa-user-tie"></i> Daftar Official Basket Putri ({{ count($officials) }})
                </div>
                <div class="card-body">
                    @if(count($officials) > 0)
                        <div class="table-container">
                            <table class="data">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Email</th>
                                        <th>Telepon</th>
                                        <th>Gender</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($officials as $index => $official)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $official->name ?? 'N/A' }}</strong></td>
                                        <td><span class="badge-official-role">{{ $official->formatted_team_role ?? $official->team_role ?? '-' }}</span></td>
                                        <td>@if($official->email)<a href="mailto:{{ $official->email }}">{{ $official->email }}</a>@else-@endif</td>
                                        <td>@if($official->phone)<a href="tel:{{ $official->phone }}">{{ $official->phone }}</a>@else-@endif</td>
                                        <td>
                                            @if(strtolower($official->gender ?? '') == 'male')
                                                <span class="badge-gender-male"><i class="fas fa-male"></i> Laki-laki</span>
                                            @elseif(strtolower($official->gender ?? '') == 'female')
                                                <span class="badge-gender-female"><i class="fas fa-female"></i> Perempuan</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($official->verification_status == 'verified')
                                                <span class="status-verified">Terverifikasi</span>
                                            @elseif($official->verification_status == 'rejected')
                                                <span class="status-rejected">Ditolak</span>
                                            @else
                                                <span class="status-unverified">Pending</span>
                                            @endif
                                        </td>
                                        <td>
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
                        </div>
                    @endif
                </div>
            </div>

            @include('team_verification.partials.jersey_gallery', ['team' => $team, 'category' => 'Basket Putri'])
        @else
            <div class="empty-state">
                <i class="fas fa-basketball-ball" style="color:#ec4899;"></i>
                <h5>Tim Basket Putri Belum Terdaftar</h5>
            </div>
        @endif

        {{-- ==================== DANCER ==================== --}}
        @elseif($activeTab == 'Dancer')
        @php
            $team = $teamData['team_dancer'] ?? null;
            $dancers = $teamData['dancers'] ?? [];
            $officials = $teamData['officials_dancer'] ?? [];
        @endphp

        @if($team)
            <div class="card payment-action-card">
                <div class="card-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                    <i class="fas fa-receipt"></i>
                    Bukti Pembayaran & Aksi Tim - Dancer
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="payment-info-box">
                                <div class="payment-proof-label mb-2">
                                    <i class="fas fa-money-bill-wave"></i> Status Pembayaran:
                                    @if($team->payment_status == 'paid')
                                        <span class="payment-status-badge payment-status-paid"><i class="fas fa-check-circle"></i> Lunas</span>
                                    @elseif($team->payment_status == 'failed')
                                        <span class="payment-status-badge payment-status-failed"><i class="fas fa-times-circle"></i> Gagal</span>
                                    @else
                                        <span class="payment-status-badge payment-status-pending"><i class="fas fa-clock"></i> Pending</span>
                                    @endif
                                </div>

                                @if($team->payment_proof)
                                    <a href="{{ Storage::url($team->payment_proof) }}" target="_blank" class="payment-proof-link available">
                                        <i class="fas fa-file-invoice-dollar"></i> Lihat Bukti Pembayaran
                                    </a>
                                @else
                                    <span class="payment-proof-link unavailable" onclick="showAlert('Bukti Pembayaran')">
                                        <i class="fas fa-exclamation-triangle"></i> Bukti Pembayaran Belum Diupload
                                    </span>
                                @endif

                                @if($team->payment_date)
                                    <div class="mt-2">
                                        <small class="text-muted"><i class="fas fa-calendar-alt"></i> Tanggal Bayar: {{ \Carbon\Carbon::parse($team->payment_date)->isoFormat('D MMM YYYY, HH:mm') }}</small>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="action-buttons-box">
                                <div class="action-buttons-title">
                                    <i class="fas fa-cogs"></i> Aksi Tim
                                </div>
                                <div class="action-buttons-row">
                                    @if($team->locked_status != 'locked')
                                        <form action="{{ route('admin.team.lock', $team->team_id) }}" method="POST" onsubmit="return confirmAction('Kunci tim Dancer?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-lock"><i class="fas fa-lock"></i> Kunci Tim</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.team.unlock', $team->team_id) }}" method="POST" onsubmit="return confirmAction('Buka kunci tim Dancer?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-unlock"><i class="fas fa-unlock"></i> Buka Kunci</button>
                                        </form>
                                    @endif
                                    @if($team->verification_status != 'verified')
                                        <form action="{{ route('admin.team.verify', $team->team_id) }}" method="POST" onsubmit="return confirmAction('Verifikasi tim Dancer?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-verify"><i class="fas fa-check-circle"></i> Verifikasi Tim</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.team.unverify', $team->team_id) }}" method="POST" onsubmit="return confirmAction('Batalkan verifikasi tim Dancer?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-unverify"><i class="fas fa-times-circle"></i> Batalkan Verifikasi</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                    <i class="fas fa-music"></i> Daftar Dancer ({{ count($dancers) }})
                </div>
                <div class="card-body">
                    @if(count($dancers) > 0)
                        <div class="table-container">
                            <table class="data">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Gender</th>
                                        <th>Email</th>
                                        <th>Telepon</th>
                                        <th>Role Tim</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($dancers as $index => $dancer)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $dancer->name ?? 'N/A' }}</strong>
                                            @if(isset($dancer->birthdate) && $dancer->birthdate)
                                                <br><small class="text-muted">{{ \Carbon\Carbon::parse($dancer->birthdate)->isoFormat('D MMM YYYY') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $gender = strtolower($dancer->gender ?? $dancer->category ?? '');
                                            @endphp
                                            @if(in_array($gender, ['male', 'putra', 'laki-laki']))
                                                <span class="badge-gender-male"><i class="fas fa-male"></i> Putra</span>
                                            @else
                                                <span class="badge-gender-female"><i class="fas fa-female"></i> Putri</span>
                                            @endif
                                        </td>
                                        <td>@if($dancer->email)<a href="mailto:{{ $dancer->email }}">{{ $dancer->email }}</a>@else-@endif</td>
                                        <td>@if($dancer->phone)<a href="tel:{{ $dancer->phone }}">{{ $dancer->phone }}</a>@else-@endif</td>
                                        <td>
                                            @if(strtolower($dancer->role ?? '') == 'leader')
                                                <span class="badge-role-leader"><i class="fas fa-crown"></i> Leader</span>
                                            @else
                                                <span class="badge-role-player">Member</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($dancer->verification_status == 'verified')
                                                <span class="status-verified">Terverifikasi</span>
                                            @elseif($dancer->verification_status == 'rejected')
                                                <span class="status-rejected">Ditolak</span>
                                            @else
                                                <span class="status-unverified">Pending</span>
                                            @endif
                                        </td>
                                        <td>
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
                        </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
                    <i class="fas fa-user-tie"></i> Daftar Official Dancer ({{ count($officials) }})
                </div>
                <div class="card-body">
                    @if(count($officials) > 0)
                        <div class="table-container">
                            <table class="data">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Jabatan</th>
                                        <th>Email</th>
                                        <th>Telepon</th>
                                        <th>Gender</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($officials as $index => $official)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td><strong>{{ $official->name ?? 'N/A' }}</strong></td>
                                        <td><span class="badge-official-role">{{ $official->formatted_team_role ?? $official->team_role ?? '-' }}</span></td>
                                        <td>@if($official->email)<a href="mailto:{{ $official->email }}">{{ $official->email }}</a>@else-@endif</td>
                                        <td>@if($official->phone)<a href="tel:{{ $official->phone }}">{{ $official->phone }}</a>@else-@endif</td>
                                        <td>
                                            @if(strtolower($official->gender ?? '') == 'male')
                                                <span class="badge-gender-male"><i class="fas fa-male"></i> Laki-laki</span>
                                            @elseif(strtolower($official->gender ?? '') == 'female')
                                                <span class="badge-gender-female"><i class="fas fa-female"></i> Perempuan</span>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($official->verification_status == 'verified')
                                                <span class="status-verified">Terverifikasi</span>
                                            @elseif($official->verification_status == 'rejected')
                                                <span class="status-rejected">Ditolak</span>
                                            @else
                                                <span class="status-unverified">Pending</span>
                                            @endif
                                        </td>
                                        <td>
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
                        </div>
                    @endif
                </div>
            </div>

            @include('team_verification.partials.jersey_gallery', ['team' => $team, 'category' => 'Dancer'])
        @else
            <div class="empty-state">
                <i class="fas fa-music" style="color:#8b5cf6;"></i>
                <h5>Tim Dancer Belum Terdaftar</h5>
            </div>
        @endif

        @endif
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        window.showAlert = function(documentName) {
            Swal.fire({
                title: 'Dokumen Tidak Tersedia',
                html: `<strong>${documentName}</strong> belum diupload.`,
                icon: 'warning',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#f59e0b',
                timer: 3000
            });
            return false;
        };

        window.confirmAction = function(message) {
            return Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#3b82f6'
            }).then((result) => result.isConfirmed);
        };

        window.showLogoPopup = function(logoUrl, schoolName, category) {
            Swal.fire({
                title: 'Logo Sekolah',
                html: `<div style="text-align:center;"><img src="${logoUrl || ''}" style="max-width:300px; max-height:300px; border-radius:8px;"><p>${schoolName}</p><p>${category}</p></div>`,
                showCloseButton: true,
                showConfirmButton: false
            });
        };

        window.showJerseyPopup = function(imageUrl, title, schoolName, category) {
            Swal.fire({
                title: `${title}`,
                html: `<div style="text-align:center;"><img src="${imageUrl || ''}" style="max-width:300px; max-height:300px; border-radius:8px;"><p>${schoolName}</p></div>`,
                showCloseButton: true,
                showConfirmButton: false
            });
        };
    });
</script>
@endpush
@endsection