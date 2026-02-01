@extends('admin.layouts.app')
@section('title', 'Detail Tim - Administrator')

@section('content')
@include('partials.sweetalert')

@push('styles')
<style>
    body {
        background: #f4f6f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .page-header {
        margin-bottom: 20px;
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
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        border: 1px solid #e0e0e0;
        overflow: hidden;
    }

    .card-header {
        padding: 14px 18px;
        font-weight: 600;
        border-bottom: 1px solid #eef2f7;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        font-size: 1rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .card-header i {
        font-size: 0.9rem;
    }

    .card-body {
        padding: 20px;
    }

    /* Team Information Grid - Logo di kiri, konten di kanan */
    .team-info {
        display: grid;
        grid-template-columns: 180px 1fr;
        gap: 20px;
        align-items: start;
    }

    /* Logo Column */
    .logo-column {
        display: flex;
        flex-direction: column;
    }

    .logo-box-square {
        text-align: center;
        padding: 15px;
        background: #f8fafc;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 0;
    }

    .logo-box-square:hover {
        border-color: #667eea;
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.15);
        transform: translateY(-2px);
    }

    .logo-box-square img {
        width: 120px;
        height: 120px;
        object-fit: contain;
        margin-bottom: 10px;
        border-radius: 4px;
        background: white;
        padding: 8px;
    }

    .logo-placeholder {
        width: 120px;
        height: 120px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        border: 2px dashed #cbd5e0;
        border-radius: 6px;
        color: #718096;
        font-size: 13px;
        margin: 0 auto 10px;
        transition: all 0.3s ease;
    }

    .logo-box-square:hover .logo-placeholder {
        border-color: #a0aec0;
        background: linear-gradient(135deg, #edf2f7, #e2e8f0);
    }

    .logo-placeholder i {
        font-size: 2.5rem;
        margin-bottom: 12px;
        color: #a0aec0;
    }

    .logo-box-square p {
        margin-top: 10px;
        font-size: 13px;
        color: #4a5568;
        font-weight: 600;
        background: #e2e8f0;
        padding: 4px 10px;
        border-radius: 15px;
        display: inline-block;
    }

    /* Content Column */
    .content-column {
        display: flex;
        flex-direction: column;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
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
    }

    .info-table td {
        padding: 7px 5px;
        vertical-align: top;
        font-size: 13px;
        border-bottom: 1px solid #f1f5f9;
    }

    .info-table td:first-child {
        width: 130px;
        color: #4a5568;
        font-weight: 600;
    }

    .info-table.compact td {
        padding: 5px 5px;
        font-size: 12px;
    }

    .info-table.compact td:first-child {
        width: 120px;
    }

    .info-table tr:last-child td {
        border-bottom: none;
    }

    /* Status Section */
    .status-section {
        margin-bottom: 15px;
    }

    /* Status Styles */
    .status-open {
        color: #e74c3c;
        font-weight: 600;
        background: #ffebee;
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 11px;
        display: inline-block;
        border: 1px solid #ffcdd2;
    }

    .status-locked {
        color: #2ecc71;
        font-weight: 600;
        background: #e8f5e9;
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 11px;
        display: inline-block;
        border: 1px solid #c8e6c9;
    }

    .status-verified {
        color: #27ae60;
        font-weight: 600;
        background: #d5f4e6;
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 11px;
        display: inline-block;
        border: 1px solid #a3e4d7;
    }

    .status-unverified {
        color: #f39c12;
        font-weight: 600;
        background: #fef9e7;
        padding: 2px 8px;
        border-radius: 3px;
        font-size: 11px;
        display: inline-block;
        border: 1px solid #fad7a0;
    }

    /* Documents Section - Compact */
    .documents-section h4 {
        font-size: 13px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px solid #e2e8f0;
    }

    .document-links.compact {
        gap: 6px;
    }

    .document-link {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        padding: 10px 12px;
        border-radius: 6px;
        transition: all 0.2s ease;
        width: 100%;
        box-sizing: border-box;
        border: 1px solid transparent;
    }

    .document-links.compact .document-link {
        padding: 8px 10px;
        font-size: 12px;
        min-height: 36px;
    }

    .document-links.compact .document-link i {
        font-size: 13px;
        min-width: 16px;
    }

    /* Dokumen tersedia (biru) */
    .document-link.available {
        color: #2c5282;
        background: #ebf8ff;
        border-color: #bee3f8;
    }

    .document-link.available:hover {
        background: #e1f5ff;
        color: #2a4365;
        border-color: #90cdf4;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(66, 153, 225, 0.1);
    }

    .document-link.available i {
        color: #3182ce;
        font-size: 14px;
        min-width: 18px;
    }

    /* Surat Rekomendasi belum upload (kuning) */
    .document-link.warning {
        color: #744210;
        background: #fffbeb;
        border-color: #fbd38d;
    }

    .document-link.warning:hover {
        background: #fef3c7;
        color: #5c3709;
        border-color: #f6ad55;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(237, 137, 54, 0.1);
    }

    .document-link.warning i {
        color: #d69e2e;
        font-size: 14px;
        min-width: 18px;
    }

    /* Bukti Koran belum upload (merah) */
    .document-link.danger {
        color: #742a2a;
        background: #fff5f5;
        border-color: #fed7d7;
    }

    .document-link.danger:hover {
        background: #fee;
        color: #5c1e1e;
        border-color: #fc8181;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(229, 62, 62, 0.1);
    }

    .document-link.danger i {
        color: #e53e3e;
        font-size: 14px;
        min-width: 18px;
    }

    .document-link span {
        flex: 1;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Action Buttons - Compact */
    .action-buttons h4 {
        font-size: 13px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px solid #e2e8f0;
    }

    .action-buttons-row.compact {
        gap: 8px;
    }

    .action-buttons-row {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* Button Base Style - Compact */
    .btn-action-simple {
        color: #fff;
        padding: 7px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        width: 100%;
        justify-content: center;
    }

    .action-buttons-row.compact .btn-action-simple {
        min-width: 100px;
    }

    .btn-action-simple:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12);
        color: white;
    }

    .btn-action-simple i {
        font-size: 11px;
    }

    .btn-lock {
        background: linear-gradient(135deg, #00a65a 0%, #008d4c 100%);
    }

    .btn-lock:hover {
        background: linear-gradient(135deg, #008d4c 0%, #00753c 100%);
    }

    .btn-unlock {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    }

    .btn-unlock:hover {
        background: linear-gradient(135deg, #2980b9 0%, #1f639e 100%);
    }

    .btn-verify {
        background: linear-gradient(135deg, #27ae60 0%, #219653 100%);
    }

    .btn-verify:hover {
        background: linear-gradient(135deg, #219653 0%, #1a7c42 100%);
    }

    .btn-unverify {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    }

    .btn-unverify:hover {
        background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
    }

    /* ===== DATA TABLES ===== */
    .table-container {
        overflow-x: auto;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }

    table.data {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        min-width: 800px;
    }

    table.data th {
        background: #f8fafc;
        font-weight: 600;
        color: #2d3748;
        padding: 12px 10px;
        text-align: left;
        border-bottom: 2px solid #e2e8f0;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    table.data td {
        border-bottom: 1px solid #f1f5f9;
        padding: 10px;
        vertical-align: middle;
        color: #4a5568;
    }

    table.data tbody tr:hover {
        background-color: #f7fafc;
    }

    /* ===== BUTTON DETAIL ===== */
    .btn-detail {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 6px 14px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 1px 3px rgba(102, 126, 234, 0.2);
        min-width: 90px;
        height: 32px;
        cursor: pointer;
    }

    .btn-detail:hover {
        background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(102, 126, 234, 0.25);
        color: white;
        text-decoration: none;
    }

    .btn-detail i {
        font-size: 11px;
    }

    /* ===== JERSEY SINGLE FULL BOX ===== */
    .jersey-single-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 25px;
        background: #f8fafc;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        min-height: 280px;
    }

    .jersey-main-title {
        font-size: 16px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 25px;
        text-align: center;
        position: relative;
        padding-bottom: 8px;
    }

    .jersey-main-title:after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 50px;
        height: 2px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 2px;
    }

    .jersey-image-container {
        display: flex;
        justify-content: center;
        align-items: stretch;
        gap: 20px;
        flex-wrap: wrap;
    }

    .jersey-single-item {
        text-align: center;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
        min-width: 200px;
        border: 1px solid #e2e8f0;
        flex: 1;
        max-width: 250px;
        transition: transform 0.3s ease;
    }

    .jersey-single-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .jersey-single-item p {
        margin-bottom: 15px;
        font-weight: 600;
        color: #2c3e50;
        font-size: 14px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
    }

    .jersey-single-item .no-image {
        width: 160px;
        height: 160px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        border: 2px dashed #cbd5e0;
        border-radius: 6px;
        color: #718096;
        font-size: 13px;
        margin: 0 auto;
        transition: all 0.3s ease;
    }

    .jersey-single-item:hover .no-image {
        border-color: #a0aec0;
        background: linear-gradient(135deg, #edf2f7, #e2e8f0);
    }

    .jersey-single-item .no-image i {
        font-size: 2.5rem;
        margin-bottom: 12px;
        color: #a0aec0;
    }

    /* Back Link */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #4a5568;
        text-decoration: none;
        font-size: 13px;
        font-weight: 500;
        margin-bottom: 20px;
        padding: 10px 16px;
        background: white;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }

    .back-link:hover {
        background: #f7fafc;
        color: #2d3748;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e0;
    }

    .back-link i {
        color: #667eea;
        font-size: 12px;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 50px 20px;
        color: #a0aec0;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #cbd5e0;
        opacity: 0.5;
    }

    .empty-state h5 {
        font-size: 1.1rem;
        color: #718096;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .empty-state p {
        font-size: 13px;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .team-info {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .logo-column {
            align-items: center;
        }

        .content-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .logo-box-square {
            max-width: 180px;
        }

        .jersey-image-container {
            gap: 15px;
        }

        .jersey-single-item {
            min-width: 180px;
        }
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 15px;
        }

        .content-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .action-buttons-row.compact {
            flex-direction: row;
            flex-wrap: wrap;
        }

        .action-buttons-row.compact .btn-action-simple {
            flex: 1;
            min-width: auto;
        }

        .jersey-image-container {
            flex-direction: column;
            gap: 15px;
        }

        .jersey-single-item {
            width: 100%;
            max-width: 100%;
        }

        table.data th,
        table.data td {
            padding: 8px 6px;
            font-size: 12px;
        }

        .btn-detail {
            padding: 5px 12px;
            font-size: 11px;
            min-width: 80px;
            height: 30px;
        }

        .document-link {
            padding: 8px 10px;
            font-size: 12px;
        }

        .btn-action-simple {
            padding: 7px 12px;
            font-size: 12px;
        }
    }

    @media (max-width: 576px) {
        .card-body {
            padding: 12px;
        }

        .page-title {
            font-size: 1.4rem;
        }

        .page-subtitle {
            font-size: 0.85rem;
        }

        .action-buttons-row.compact {
            flex-direction: column;
        }

        .action-buttons-row.compact .btn-action-simple {
            width: 100%;
        }

        .back-link {
            padding: 8px 12px;
            font-size: 12px;
        }

        .jersey-single-item .no-image {
            width: 140px;
            height: 140px;
        }

        table.data th,
        table.data td {
            padding: 6px 4px;
            font-size: 11px;
        }

        .btn-detail {
            padding: 4px 10px;
            font-size: 10px;
            min-width: 70px;
            height: 28px;
        }

        .document-link {
            padding: 7px 9px;
            font-size: 11px;
        }

        .document-link i {
            font-size: 12px;
        }

        .btn-action-simple {
            padding: 6px 10px;
            font-size: 11px;
        }
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
        <h1 class="page-title">Informasi Tim</h1>
        <p class="page-subtitle">Detail informasi tim <strong>{{ $team->school_name }}</strong></p>
    </div>

    <!-- Team Information Card -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-info-circle"></i>
            <span>Informasi Tim</span>
        </div>
        <div class="card-body">
            <div class="team-info">
                <!-- Logo di Kiri -->
                <div class="logo-column">
                    <div class="logo-box-square" onclick="showLogoPopup()">
                        @if($team->school_logo)
                            <!-- 🔥 PERBAIKAN: Gunakan storage path yang benar -->
                            <img src="{{ asset('storage/' . $team->school_logo) }}" 
                                 alt="Logo Sekolah {{ $team->school_name }}"
                                 id="team-logo"
                                 onerror="this.onerror=null; this.parentElement.innerHTML = '<div class=\"logo-placeholder\"><i class=\"fas fa-school\"></i><span>Logo Tidak Ditemukan</span></div><p>Logo Sekolah</p>
                        @else
                            <!-- Logo default jika tidak ada -->
                            <div class="logo-placeholder">
                                <i class="fas fa-school"></i>
                                <span>No Logo</span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Konten di Kanan -->
                <div class="content-column">
                    <div class="content-grid">
                        <!-- Info Dasar -->
                        <div class="info-section">
                            <table class="info-table">
                                <tr>
                                    <td>ID Tim</td>
                                    <td>: <strong>{{ $team->referral_code ?? 'Tahun 1986' }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Leader</td>
                                    <td>: {{ $team->registered_by ?? 'Muhammad Alfah Reza' }}</td>
                                </tr>
                                <tr>
                                    <td>Nama Sekolah</td>
                                    <td>: <strong>{{ $team->school_name }}</strong></td>
                                </tr>
                                <tr>
                                    <td>Kompetisi</td>
                                    <td>: {{ $team->competition }}</td>
                                </tr>
                                <tr>
                                    <td>Musim</td>
                                    <td>: {{ $team->season ?? 'Honda DBL 2019' }}</td>
                                </tr>
                                <tr>
                                    <td>Seri</td>
                                    <td>: {{ $team->series ?? 'Seri Riau' }}</td>
                                </tr>
                                <tr>
                                    <td>Wilayah</td>
                                    <td>: -</td>
                                </tr>
                            </table>
                        </div>

                        <!-- Status dan Dokumen -->
                        <div class="status-doc-section">
                            <!-- Status -->
                            <div class="status-section">
                                <table class="info-table compact">
                                    <tr>
                                        <td>Status Terkunci</td>
                                        <td>:
                                            @if($team->locked_status == 'locked')
                                            <span class="status-locked">Terkunci</span>
                                            @else
                                            <span class="status-open">Terbuka</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Status Verifikasi</td>
                                        <td>:
                                            @if($team->verification_status == 'verified')
                                            <span class="status-verified">Terverifikasi</span>
                                            @else
                                            <span class="status-unverified">Belum Diverifikasi</span>
                                            @endif
                                        </td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Dokumen -->
                            <div class="documents-section">
                                <h4>Dokumen</h4>
                                <div class="document-links compact">
                                    <!-- Surat Rekomendasi -->
                                    @if($team->recommendation_letter)
                                    <a href="{{ asset('storage/' . $team->recommendation_letter) }}"
                                        target="_blank"
                                        class="document-link available">
                                        <i class="fas fa-file-pdf"></i>
                                        <span>Surat Rekomendasi</span>
                                    </a>
                                    @else
                                    <a href="#"
                                        class="document-link warning mb-2"
                                        onclick="showAlert('Surat Rekomendasi')">
                                        <i class="fas fa-exclamation-circle"></i>
                                        <span>Surat Rekomendasi (Belum Upload)</span>
                                    </a>
                                    @endif

                                    <!-- Bukti Langganan Koran -->
                                    @if($team->koran)
                                    <a href="{{ asset('storage/' . $team->koran) }}"
                                        target="_blank"
                                        class="document-link available">
                                        <i class="fas fa-newspaper"></i>
                                        <span>Bukti Langganan Koran</span>
                                    </a>
                                    @else
                                    <a href="#"
                                        class="document-link danger"
                                        onclick="showAlert('Bukti Langganan Koran')">
                                        <i class="fas fa-times-circle"></i>
                                        <span>Bukti Langganan Koran (Belum Upload)</span>
                                    </a>
                                    @endif
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="action-buttons mt-2">
                                <h4>Aksi Tim</h4>
                                <div class="action-buttons-row compact">
                                    @if($team->locked_status != 'locked')
                                    <form action="{{ route('admin.team.lock', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Kunci tim {{ $team->school_name }}?')">
                                        @csrf
                                        <button type="submit" class="btn-action-simple btn-lock">
                                            <i class="fas fa-lock"></i> Kunci
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.team.unlock', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Buka kunci tim {{ $team->school_name }}?')">
                                        @csrf
                                        <button type="submit" class="btn-action-simple btn-unlock">
                                            <i class="fas fa-unlock"></i> Buka
                                        </button>
                                    </form>
                                    @endif

                                    @if($team->verification_status != 'verified')
                                    <form action="{{ route('admin.team.verify', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Verifikasi tim {{ $team->school_name }}?')">
                                        @csrf
                                        <button type="submit" class="btn-action-simple btn-verify">
                                            <i class="fas fa-check"></i> Verifikasi
                                        </button>
                                    </form>
                                    @else
                                    <form action="{{ route('admin.team.unverify', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Batalkan verifikasi tim {{ $team->school_name }}?')">
                                        @csrf
                                        <button type="submit" class="btn-action-simple btn-unverify">
                                            <i class="fas fa-times"></i> Batal
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Player List Card -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-users"></i>
            <span>Daftar Pemain ({{ $players->count() ?? 0 }})</span>
        </div>
        <div class="card-body">
            @if($players && $players->count() > 0)
            <div class="table-container">
                <table class="data">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Nama</th>
                            <th width="80">No. Jersey</th>
                            <th width="120">Tanggal Lahir</th>
                            <th width="100">Jenis Kelamin</th>
                            <th width="100">Posisi</th>
                            <th width="100">Kelas</th>
                            <th width="100">Tahun STTB</th>
                            <th width="100" class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($players as $index => $player)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $player->name ?? 'N/A' }}</strong></td>
                            <td class="text-center">{{ $player->jersey_number ?? '-' }}</td>
                            <td>
                                @if($player->birthdate)
                                {{ \Carbon\Carbon::parse($player->birthdate)->isoFormat('D MMMM YYYY') }}
                                @else
                                <span style="color: #718096;">-</span>
                                @endif
                            </td>
                            <td>
                                @if($player->gender == 'Male')
                                Laki-laki
                                @elseif($player->gender == 'Female')
                                Perempuan
                                @else
                                <span style="color: #718096;">-</span>
                                @endif
                            </td>
                            <td>{{ $player->basketball_position ?? '-' }}</td>
                            <td>{{ $player->grade ?? '-' }}</td>
                            <td>{{ $player->sttb_year ?? '-' }}</td>
                            <!-- Di dalam tabel daftar pemain -->
                            <td class="text-center">
                                <!-- GANTI ROUTE INI -->
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
                <p class="text-muted mt-2" style="font-size: 12px;">
                    <i class="fas fa-info-circle"></i>
                    Tim ini memiliki referral code: <strong>{{ $team->referral_code ?? 'N/A' }}</strong>
                </p>
            </div>
            @endif
        </div>
    </div>

    <!-- Official List Card -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-user-tie"></i>
            <span>Daftar Official</span>
        </div>
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h5>Belum ada data</h5>
                <p>Data official akan muncul di sini setelah ditambahkan.</p>
            </div>
        </div>
    </div>

    <!-- Jersey List Card -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-tshirt"></i>
            <span>Daftar Jersey</span>
        </div>
        <div class="card-body">
            <div class="jersey-single-container">
                <h3 class="jersey-main-title">Galeri Jersey Tim</h3>
                <div class="jersey-image-container">
                    <div class="jersey-single-item">
                        <p>Jersey Kandang</p>
                        <div class="no-image">
                            <i class="fas fa-tshirt"></i>
                            <span>No Image</span>
                        </div>
                    </div>
                    <div class="jersey-single-item">
                        <p>Jersey Tandang</p>
                        <div class="no-image">
                            <i class="fas fa-tshirt"></i>
                            <span>No Image</span>
                        </div>
                    </div>
                    <div class="jersey-single-item">
                        <p>Jersey Alternatif</p>
                        <div class="no-image">
                            <i class="fas fa-tshirt"></i>
                            <span>No Image</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                confirmButtonColor: '#f39c12',
                showCancelButton: false,
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
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#e74c3c',
                reverseButtons: true
            }).then((result) => {
                return result.isConfirmed;
            });
        };

        // Function to show logo popup
        window.showLogoPopup = function() {
            const logoImg = document.getElementById('team-logo');
            let logoUrl = '';
            let logoTitle = 'Logo Sekolah';
            
            if (logoImg) {
                logoUrl = logoImg.src;
                logoTitle = logoImg.alt || 'Logo Sekolah';
            } else {
                // Jika tidak ada logo, tampilkan placeholder
                Swal.fire({
                    title: 'Logo Sekolah',
                    html: `<div style="text-align: center;">
                            <div style="width: 200px; height: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #f7fafc, #edf2f7); border: 2px dashed #cbd5e0; border-radius: 8px; margin: 0 auto 15px;">
                                <i class="fas fa-school" style="font-size: 3rem; color: #a0aec0; margin-bottom: 15px;"></i>
                                <span style="color: #718096;">Logo Tidak Tersedia</span>
                            </div>
                            <p style="color: #666; font-size: 14px;">{{ $team->school_name }}</p>
                          </div>`,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: 450,
                    padding: '20px',
                    background: '#fff'
                });
                return;
            }

            Swal.fire({
                title: logoTitle,
                html: `<div style="text-align: center;">
                        <img src="${logoUrl}" alt="${logoTitle}" style="max-width: 300px; max-height: 300px; border-radius: 8px; margin-bottom: 15px;">
                        <p style="color: #666; font-size: 14px;">{{ $team->school_name }}</p>
                      </div>`,
                showCloseButton: true,
                showConfirmButton: false,
                width: 500,
                padding: '20px',
                background: '#fff'
            });
        };

        // Function to show player detail modal (untuk backup/demo)
        window.showPlayerDetail = function(playerId) {
            // Untuk sementara, tampilkan informasi sederhana
            // Nanti bisa diganti dengan AJAX request
            Swal.fire({
                title: 'Detail Pemain',
                html: `<div style="text-align: left; font-size: 14px;">
                <div class="text-center mb-3">
                    <i class="fas fa-user-circle" style="font-size: 4rem; color: #667eea;"></i>
                </div>
                <p><strong>Fitur detail pemain dalam pengembangan!</strong></p>
                <p>ID Pemain: <code>${playerId}</code></p>
                <p>Fitur yang akan datang:</p>
                <ul style="text-align: left; padding-left: 20px;">
                    <li>Data pribadi lengkap</li>
                    <li>Dokumen pemain (KK, Akte, Raport)</li>
                    <li>Foto formal & pas foto</li>
                    <li>Riwayat akademik</li>
                    <li>Data orang tua/wali</li>
                </ul>
               </div>`,
                icon: 'info',
                confirmButtonText: 'Mengerti',
                confirmButtonColor: '#667eea',
                showCloseButton: true,
                width: 500
            });
        };

        // PERBAIKAN: Event listener untuk tombol detail yang TIDAK memblokir navigasi
        const detailButtons = document.querySelectorAll('.btn-detail');
        detailButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Cek apakah link valid (tidak "#" atau kosong)
                const href = this.getAttribute('href');
                
                // Jika href adalah "#" atau kosong, tampilkan alert dan batalkan navigasi
                if (!href || href === '#' || href === 'javascript:void(0)') {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Informasi Detail',
                        text: 'Fitur detail pemain akan segera tersedia!',
                        icon: 'info',
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#667eea',
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
                // Jika href valid (misal: "/admin/player/1"), biarkan navigasi berjalan normal
                // TIDAK ADA e.preventDefault() di sini untuk link valid
            });
        });

        // Document links click handler
        const documentLinks = document.querySelectorAll('.document-link[href="#"]');
        documentLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const documentName = this.textContent.trim();
                showAlert(documentName);
            });
        });

        // SweetAlert untuk form actions
        const actionForms = document.querySelectorAll('form[onsubmit*="confirmAction"]');
        actionForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const message = this.getAttribute('onsubmit').match(/return confirmAction\('(.+)'\)/)[1];

                Swal.fire({
                    title: 'Konfirmasi',
                    text: message,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#667eea',
                    cancelButtonColor: '#e74c3c',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Sedang memproses permintaan Anda',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit form after a short delay to show loading
                        setTimeout(() => {
                            form.submit();
                        }, 500);
                    }
                });
            });
        });

        // Document preview if available
        const availableDocs = document.querySelectorAll('.document-link.available');
        availableDocs.forEach(doc => {
            doc.addEventListener('click', function(e) {
                // Allow default behavior (open in new tab)
                // Tidak perlu e.preventDefault() di sini
            });
        });
        
        // Debug: Log semua tombol detail untuk memastikan
        console.log('Detail buttons found:', detailButtons.length);
        detailButtons.forEach((btn, index) => {
            console.log(`Button ${index + 1}:`, {
                href: btn.getAttribute('href'),
                text: btn.textContent.trim(),
                onclick: btn.getAttribute('onclick')
            });
        });
    });
</script>
@endpush
@endsection