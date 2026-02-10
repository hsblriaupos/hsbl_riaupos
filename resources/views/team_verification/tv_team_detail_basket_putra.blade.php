@extends('admin.layouts.app')
@section('title', 'Detail Basket Putra - Administrator')

@section('content')
@include('partials.sweetalert')

@push('styles')
<style>
    /* Reuse styles from previous file, but with specific adjustments */
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
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
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

    /* Team Information Grid */
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
        border-color: #3b82f6;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.15);
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

    /* Documents Section */
    .documents-section h4 {
        font-size: 13px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px solid #e2e8f0;
    }

    .document-links.compact {
        display: flex;
        flex-direction: column;
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

    /* Action Buttons */
    .action-buttons h4 {
        font-size: 13px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px solid #e2e8f0;
    }

    .action-buttons-row {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .action-buttons-row.compact {
        gap: 8px;
    }

    /* Button Styles */
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

    .btn-action-simple:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.12);
        color: white;
    }

    .btn-lock {
        background: linear-gradient(135deg, #00a65a 0%, #008d4c 100%);
    }

    .btn-unlock {
        background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
    }

    .btn-verify {
        background: linear-gradient(135deg, #27ae60 0%, #219653 100%);
    }

    .btn-unverify {
        background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
    }

    /* PERBAIKAN UTAMA: Table Styles yang lebih ketat */
    .table-container {
        overflow-x: auto;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: white;
        margin-top: 10px;
    }

    /* Reset semua table styling untuk kontrol penuh */
    table.data {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 13px;
        table-layout: fixed;
        /* INI YANG PENTING! */
        min-width: 1200px;
        /* Lebar minimum agar tidak squeeze */
    }

    /* Header tabel - lebih kompak dan jelas */
    table.data thead tr {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    }

    table.data th {
        padding: 12px 8px !important;
        text-align: left;
        font-weight: 700;
        color: #2d3748;
        border-bottom: 2px solid #e2e8f0;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
        vertical-align: middle;
        position: sticky;
        top: 0;
        background: inherit;
        z-index: 10;
    }

    /* Cell tabel - rapi dan konsisten */
    table.data td {
        padding: 12px 8px !important;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        color: #4a5568;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* PERBAIKAN: Lebar kolom yang sangat spesifik */
    table.data th:nth-child(1),
    table.data td:nth-child(1) {
        /* # */
        width: 40px !important;
        min-width: 40px !important;
        max-width: 40px !important;
        text-align: center !important;
        padding-left: 5px !important;
        padding-right: 5px !important;
    }

    table.data th:nth-child(2),
    table.data td:nth-child(2) {
        /* Nama */
        width: 180px !important;
        min-width: 180px !important;
        max-width: 180px !important;
        text-align: left !important;
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    table.data th:nth-child(3),
    table.data td:nth-child(3) {
        /* Role */
        width: 90px !important;
        min-width: 90px !important;
        max-width: 90px !important;
        text-align: center !important;
        padding-left: 5px !important;
        padding-right: 5px !important;
    }

    table.data th:nth-child(4),
    table.data td:nth-child(4) {
        /* No. Jersey */
        width: 70px !important;
        min-width: 70px !important;
        max-width: 70px !important;
        text-align: center !important;
        padding-left: 5px !important;
        padding-right: 5px !important;
    }

    table.data th:nth-child(5),
    table.data td:nth-child(5) {
        /* Tanggal Lahir */
        width: 130px !important;
        min-width: 130px !important;
        max-width: 130px !important;
        text-align: left !important;
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    table.data th:nth-child(6),
    table.data td:nth-child(6) {
        /* Jenis Kelamin */
        width: 100px !important;
        min-width: 100px !important;
        max-width: 100px !important;
        text-align: center !important;
        padding-left: 5px !important;
        padding-right: 5px !important;
    }

    table.data th:nth-child(7),
    table.data td:nth-child(7) {
        /* Posisi */
        width: 120px !important;
        min-width: 120px !important;
        max-width: 120px !important;
        text-align: center !important;
        padding-left: 10px !important;
        padding-right: 10px !important;
    }

    table.data th:nth-child(8),
    table.data td:nth-child(8) {
        /* Kelas */
        width: 90px !important;
        min-width: 90px !important;
        max-width: 90px !important;
        text-align: center !important;
        padding-left: 5px !important;
        padding-right: 5px !important;
    }

    table.data th:nth-child(9),
    table.data td:nth-child(9) {
        /* Tahun STTB */
        width: 100px !important;
        min-width: 100px !important;
        max-width: 100px !important;
        text-align: center !important;
        padding-left: 5px !important;
        padding-right: 5px !important;
    }

    table.data th:nth-child(10),
    table.data td:nth-child(10) {
        /* Tindakan */
        width: 100px !important;
        min-width: 100px !important;
        max-width: 100px !important;
        text-align: center !important;
        padding-left: 5px !important;
        padding-right: 5px !important;
    }

    /* Row hover effect */
    table.data tbody tr {
        transition: all 0.2s ease;
    }

    table.data tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Official table specific widths */
    table.data.official {
        min-width: 1000px;
    }

    table.data.official th:nth-child(1),
    table.data.official td:nth-child(1) {
        /* # */
        width: 40px !important;
    }

    table.data.official th:nth-child(2),
    table.data.official td:nth-child(2) {
        /* Nama */
        width: 180px !important;
    }

    table.data.official th:nth-child(3),
    table.data.official td:nth-child(3) {
        /* Jabatan */
        width: 120px !important;
    }

    table.data.official th:nth-child(4),
    table.data.official td:nth-child(4) {
        /* Email */
        width: 180px !important;
        white-space: normal !important;
        /* Email bisa wrap */
        word-break: break-word;
    }

    table.data.official th:nth-child(5),
    table.data.official td:nth-child(5) {
        /* Telepon */
        width: 110px !important;
    }

    table.data.official th:nth-child(6),
    table.data.official td:nth-child(6) {
        /* Jenis Kelamin */
        width: 110px !important;
    }

    table.data.official th:nth-child(7),
    table.data.official td:nth-child(7) {
        /* Status Verifikasi */
        width: 130px !important;
    }

    table.data.official th:nth-child(8),
    table.data.official td:nth-child(8) {
        /* Tindakan */
        width: 100px !important;
    }

    /* Button Detail */
    .btn-detail {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
        color: white;
        padding: 6px 12px;
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
        box-shadow: 0 1px 3px rgba(59, 130, 246, 0.2);
        min-width: 80px;
        height: 30px;
        cursor: pointer;
    }

    .btn-detail:hover {
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(59, 130, 246, 0.3);
        color: white;
    }

    /* Jersey Gallery */
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
        background: #f8fafc;
        border-color: #3b82f6;
        color: #3b82f6;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        background: #f8fafc;
        border-radius: 6px;
        border: 1px dashed #e2e8f0;
    }

    .empty-state i {
        font-size: 48px;
        color: #cbd5e0;
        margin-bottom: 15px;
    }

    .empty-state h5 {
        font-size: 16px;
        color: #4a5568;
        margin-bottom: 8px;
        font-weight: 600;
    }

    .empty-state p {
        color: #718096;
        font-size: 13px;
        max-width: 400px;
        margin: 0 auto;
        line-height: 1.5;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .team-info {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .content-grid {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 15px;
        }

        .action-buttons-row.compact {
            flex-direction: row;
            flex-wrap: wrap;
        }

        .table-container {
            border-radius: 4px;
        }

        /* Tampilkan scrollbar yang lebih jelas di mobile */
        .table-container::-webkit-scrollbar {
            height: 6px;
        }

        .table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 3px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 3px;
        }

        .table-container::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
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
    }
</style>
@endpush

<div class="container-fluid py-4">
    <!-- Back Button -->
    <a href="{{ route('admin.team-list.show', $team->team_id) }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        Kembali ke Pilihan Kategori
    </a>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Detail Tim Basket Putra</h1>
        <p class="page-subtitle">Informasi lengkap tim Basket Putra <strong>{{ $team->school_name }}</strong></p>
    </div>

    <!-- Team Information Card -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-info-circle"></i>
            <span>Informasi Tim Basket Putra</span>
        </div>
        <div class="card-body">
            <div class="team-info">
                <!-- Logo di Kiri -->
                <div class="logo-column">
                    <div class="logo-box-square" onclick="showLogoPopup()">
                        @if($team->school_logo)
                        @php
                        $logoPath = storage_path('app/public/' . $team->school_logo);
                        $logoExists = file_exists($logoPath);
                        @endphp

                        @if($logoExists)
                        <img src="{{ asset('storage/' . $team->school_logo) }}"
                            alt="Logo Sekolah {{ $team->school_name }}"
                            id="team-logo"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div class="logo-placeholder" id="logo-placeholder-fallback" style="display: none;">
                            <i class="fas fa-school"></i>
                            <span>Logo Tidak Ditemukan</span>
                        </div>
                        @else
                        <div class="logo-placeholder">
                            <i class="fas fa-school"></i>
                            <span>Logo Tidak Ditemukan</span>
                        </div>
                        @endif
                        @else
                        <div class="logo-placeholder">
                            <i class="fas fa-school"></i>
                            <span>No Logo</span>
                        </div>
                        @endif
                    </div>
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
                                <td>: <strong>{{ $team->referral_code ?? 'N/A' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Leader</td>
                                <td>: {{ $team->registered_by ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Nama Sekolah</td>
                                <td>: <strong>{{ $team->school_name }}</strong></td>
                            </tr>
                            <tr>
                                <td>Kompetisi</td>
                                <td>: {{ $team->competition ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Musim</td>
                                <td>: {{ $team->season ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Seri</td>
                                <td>: {{ $team->series ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Wilayah</td>
                                <td>: {{ $team->region ?? '-' }}</td>
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
                                <form action="{{ route('admin.team.lock', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Kunci tim Basket Putra {{ $team->school_name }}?')">
                                    @csrf
                                    <button type="submit" class="btn-action-simple btn-lock">
                                        <i class="fas fa-lock"></i> Kunci Tim
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.team.unlock', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Buka kunci tim Basket Putra {{ $team->school_name }}?')">
                                    @csrf
                                    <button type="submit" class="btn-action-simple btn-unlock">
                                        <i class="fas fa-unlock"></i> Buka Kunci
                                    </button>
                                </form>
                                @endif

                                @if($team->verification_status != 'verified')
                                <form action="{{ route('admin.team.verify', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Verifikasi tim Basket Putra {{ $team->school_name }}?')">
                                    @csrf
                                    <button type="submit" class="btn-action-simple btn-verify">
                                        <i class="fas fa-check"></i> Verifikasi Tim
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.team.unverify', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Batalkan verifikasi tim Basket Putra {{ $team->school_name }}?')">
                                    @csrf
                                    <button type="submit" class="btn-action-simple btn-unverify">
                                        <i class="fas fa-times"></i> Batalkan Verifikasi
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
        <span>Daftar Pemain Basket Putra ({{ $players->count() }})</span>
    </div>
    <div class="card-body">
        @if($players->count() > 0)
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
                        <th>TINDAKAN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($players as $index => $player)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $player->name ?? 'N/A' }}</strong></td>
                        <td>
                            @if($player->role == 'Leader')
                            <span class="status-locked" style="font-size: 11px; padding: 2px 6px;">Leader</span>
                            @else
                            <span class="status-open" style="font-size: 11px; padding: 2px 6px;">Pemain</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($player->jersey_number)
                            <span style="background: #3b82f6; color: white; padding: 4px 8px; border-radius: 4px; font-weight: bold; font-size: 12px;">
                                {{ $player->jersey_number }}
                            </span>
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
                            @if($player->gender == 'Male')
                            <span style="background: #e3f2fd; color: #1565c0; padding: 3px 8px; border-radius: 4px; font-size: 11px;">Laki-laki</span>
                            @elseif($player->gender == 'Female')
                            <span style="background: #fce4ec; color: #c2185b; padding: 3px 8px; border-radius: 4px; font-size: 11px;">Perempuan</span>
                            @else
                            <span style="color: #718096;">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($player->basketball_position)
                            <span style="background: #f3f4f6; color: #374151; padding: 3px 8px; border-radius: 4px; font-size: 11px; display: inline-block;">
                                {{ $player->basketball_position }}
                            </span>
                            @else
                            <span style="color: #718096;">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($player->grade)
                            <span style="background: #fef3c7; color: #92400e; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                                {{ $player->grade }}
                            </span>
                            @else
                            <span style="color: #718096;">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($player->sttb_year)
                            <span style="background: #d1fae5; color: #065f46; padding: 3px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                                {{ $player->sttb_year }}
                            </span>
                            @else
                            <span style="color: #718096;">-</span>
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
            <p class="text-muted mt-2" style="font-size: 12px;">
                <i class="fas fa-info-circle"></i>
                Tim ini memiliki referral code: <strong>{{ $team->referral_code ?? 'N/A' }}</strong>
            </p>
        </div>
        @endif
    </div>
</div>

<!-- Official List Card - PERBAIKAN: SELALU tampilkan meski kosong -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-user-tie"></i>
        <span>Daftar Official Basket Putra ({{ $officials->count() }})</span>
    </div>
    <div class="card-body">
        @if($officials->count() > 0)
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
                        <th>STATUS VERIFIKASI</th>
                        <th>TINDAKAN</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($officials as $index => $official)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $official->name ?? 'N/A' }}</strong></td>
                        <td>
                            @if($official->team_role)
                            <span style="background: #f3f4f6; color: #374151; padding: 3px 8px; border-radius: 4px; font-size: 11px;">
                                {{ $official->team_role }}
                            </span>
                            @else
                            <span style="color: #718096;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($official->email)
                            <a href="mailto:{{ $official->email }}" style="color: #3b82f6; text-decoration: none;">
                                {{ $official->email }}
                            </a>
                            @else
                            <span style="color: #718096;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($official->phone)
                            <a href="tel:{{ $official->phone }}" style="color: #10b981; text-decoration: none;">
                                {{ $official->phone }}
                            </a>
                            @else
                            <span style="color: #718096;">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($official->gender == 'male')
                            <span style="background: #e3f2fd; color: #1565c0; padding: 3px 8px; border-radius: 4px; font-size: 11px;">Laki-laki</span>
                            @elseif($official->gender == 'female')
                            <span style="background: #fce4ec; color: #c2185b; padding: 3px 8px; border-radius: 4px; font-size: 11px;">Perempuan</span>
                            @else
                            <span style="color: #718096;">-</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($official->verification_status == 'verified')
                            <span class="status-verified" style="font-size: 11px; padding: 3px 8px;">Terverifikasi</span>
                            @elseif($official->verification_status == 'rejected')
                            <span class="status-open" style="font-size: 11px; padding: 3px 8px;">Ditolak</span>
                            @else
                            <span class="status-unverified" style="font-size: 11px; padding: 3px 8px;">Belum Diverifikasi</span>
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
            <p>Data official akan muncul di sini setelah ditambahkan.</p>
            <p class="text-muted mt-2" style="font-size: 12px;">
                <i class="fas fa-info-circle"></i>
                Silakan hubungi tim untuk menambahkan official.
            </p>
        </div>
        @endif
    </div>
</div>

<!-- Jersey List Card -->
<div class="card">
    <div class="card-header">
        <i class="fas fa-tshirt"></i>
        <span>Daftar Jersey Basket Putra</span>
    </div>
    <div class="card-body">
        <div class="jersey-single-container">
            <h3 class="jersey-main-title">Galeri Jersey Tim Basket Putra</h3>
            <div class="jersey-image-container">
                <!-- Jersey Kandang -->
                <div class="jersey-single-item" onclick="showJerseyPopup('home')">
                    <p>Jersey Kandang</p>
                    @if($team->jersey_home)
                    @php
                    $jerseyHomePath = storage_path('app/public/' . $team->jersey_home);
                    $jerseyHomeExists = file_exists($jerseyHomePath);
                    @endphp

                    @if($jerseyHomeExists)
                    <img src="{{ asset('storage/' . $team->jersey_home) }}"
                        alt="Jersey Kandang {{ $team->school_name }}"
                        class="jersey-image"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="no-image" style="display: none;">
                        <i class="fas fa-tshirt"></i>
                        <span>Gambar Error</span>
                    </div>
                    @else
                    <div class="no-image">
                        <i class="fas fa-tshirt"></i>
                        <span>File Tidak Ditemukan</span>
                    </div>
                    @endif
                    @else
                    <div class="no-image">
                        <i class="fas fa-tshirt"></i>
                        <span>Belum Upload</span>
                    </div>
                    @endif
                </div>

                <!-- Jersey Tandang -->
                <div class="jersey-single-item" onclick="showJerseyPopup('away')">
                    <p>Jersey Tandang</p>
                    @if($team->jersey_away)
                    @php
                    $jerseyAwayPath = storage_path('app/public/' . $team->jersey_away);
                    $jerseyAwayExists = file_exists($jerseyAwayPath);
                    @endphp

                    @if($jerseyAwayExists)
                    <img src="{{ asset('storage/' . $team->jersey_away) }}"
                        alt="Jersey Tandang {{ $team->school_name }}"
                        class="jersey-image"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="no-image" style="display: none;">
                        <i class="fas fa-tshirt"></i>
                        <span>Gambar Error</span>
                    </div>
                    @else
                    <div class="no-image">
                        <i class="fas fa-tshirt"></i>
                        <span>File Tidak Ditemukan</span>
                    </div>
                    @endif
                    @else
                    <div class="no-image">
                        <i class="fas fa-tshirt"></i>
                        <span>Belum Upload</span>
                    </div>
                    @endif
                </div>

                <!-- Jersey Alternatif -->
                <div class="jersey-single-item" onclick="showJerseyPopup('alternate')">
                    <p>Jersey Alternatif</p>
                    @if($team->jersey_alternate)
                    @php
                    $jerseyAltPath = storage_path('app/public/' . $team->jersey_alternate);
                    $jerseyAltExists = file_exists($jerseyAltPath);
                    @endphp

                    @if($jerseyAltExists)
                    <img src="{{ asset('storage/' . $team->jersey_alternate) }}"
                        alt="Jersey Alternatif {{ $team->school_name }}"
                        class="jersey-image"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="no-image" style="display: none;">
                        <i class="fas fa-tshirt"></i>
                        <span>Gambar Error</span>
                    </div>
                    @else
                    <div class="no-image">
                        <i class="fas fa-tshirt"></i>
                        <span>File Tidak Ditemukan</span>
                    </div>
                    @endif
                    @else
                    <div class="no-image">
                        <i class="fas fa-tshirt"></i>
                        <span>Belum Upload</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Basket Putra Detail Page Loaded');
    });

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
            confirmButtonColor: '#3b82f6',
            cancelButtonColor: '#e74c3c',
            reverseButtons: true
        }).then((result) => {
            return result.isConfirmed;
        });
    };

    // Function to show logo popup
    window.showLogoPopup = function() {
        const logoImg = document.getElementById('team-logo');

        if (logoImg && logoImg.src && !logoImg.src.includes('logo-placeholder')) {
            Swal.fire({
                title: 'Logo Sekolah',
                html: `<div style="text-align: center;">
                    <img src="${logoImg.src}" alt="Logo Sekolah" style="max-width: 300px; max-height: 300px; border-radius: 8px; margin-bottom: 15px;">
                    <p style="color: #666; font-size: 14px;">{{ $team->school_name }} - Basket Putra</p>
                  </div>`,
                showCloseButton: true,
                showConfirmButton: false,
                width: 500,
                padding: '20px',
                background: '#fff'
            });
        } else {
            Swal.fire({
                title: 'Logo Sekolah',
                html: `<div style="text-align: center;">
                    <div style="width: 200px; height: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #f7fafc, #edf2f7); border: 2px dashed #cbd5e0; border-radius: 8px; margin: 0 auto 15px;">
                        <i class="fas fa-school" style="font-size: 3rem; color: #a0aec0; margin-bottom: 15px;"></i>
                        <span style="color: #718096;">Logo Tidak Tersedia</span>
                    </div>
                    <p style="color: #666; font-size: 14px;">{{ $team->school_name }} - Basket Putra</p>
                  </div>`,
                showCloseButton: true,
                showConfirmButton: false,
                width: 450,
                padding: '20px',
                background: '#fff'
            });
        }
    };

    // Function to show jersey popup
    window.showJerseyPopup = function(type) {
        const jerseyNames = {
            'home': 'Jersey Kandang',
            'away': 'Jersey Tandang',
            'alternate': 'Jersey Alternatif'
        };

        const jerseyName = jerseyNames[type] || 'Jersey';
        const jerseyItem = document.querySelector(`.jersey-single-item:nth-child(${type === 'home' ? 1 : type === 'away' ? 2 : 3})`);

        if (!jerseyItem) return;

        const jerseyImg = jerseyItem.querySelector('.jersey-image');

        if (jerseyImg && jerseyImg.style.display !== 'none' && jerseyImg.src) {
            Swal.fire({
                title: `${jerseyName} - Basket Putra`,
                html: `<div style="text-align: center;">
                    <img src="${jerseyImg.src}" alt="${jerseyName}" style="max-width: 400px; max-height: 400px; border-radius: 8px; margin-bottom: 15px;">
                    <p style="color: #666; font-size: 14px;">{{ $team->school_name }}</p>
                  </div>`,
                showCloseButton: true,
                showConfirmButton: false,
                width: 550,
                padding: '20px',
                background: '#fff'
            });
        } else {
            Swal.fire({
                title: `${jerseyName} - Basket Putra`,
                html: `<div style="text-align: center;">
                    <div style="width: 300px; height: 300px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #f7fafc, #edf2f7); border: 2px dashed #cbd5e0; border-radius: 8px; margin: 0 auto 15px;">
                        <i class="fas fa-tshirt" style="font-size: 4rem; color: #a0aec0; margin-bottom: 20px;"></i>
                        <span style="color: #718096; font-size: 16px;">${jerseyName} Belum Diupload</span>
                    </div>
                    <p style="color: #666; font-size: 14px;">{{ $team->school_name }}</p>
                  </div>`,
                showCloseButton: true,
                showConfirmButton: false,
                width: 500,
                padding: '20px',
                background: '#fff'
            });
        }
    };
</script>
@endpush
@endsection