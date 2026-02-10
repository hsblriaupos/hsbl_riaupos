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

    /* Team Information Grid - Logo di kiri, konten di kanan */
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

    /* Document Link Colors */
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

    /* Button Base Style */
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

    .btn-action-simple i {
        font-size: 14px;
    }

    .btn-lock {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .btn-lock:hover {
        background: linear-gradient(135deg, #047857 0%, #065f46 100%);
    }

    .btn-unlock {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
    }

    .btn-unlock:hover {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    }

    .btn-verify {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .btn-verify:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .btn-unverify {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .btn-unverify:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
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

    table.data tbody tr {
        transition: all 0.2s ease;
    }

    table.data tbody tr:hover {
        background-color: #f8fafc;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
    }

    /* Fixed column widths for better alignment */
    table.data th:nth-child(1),
    table.data td:nth-child(1) {
        /* # */
        width: 60px;
        min-width: 60px;
        max-width: 60px;
        text-align: center;
    }

    table.data th:nth-child(2),
    table.data td:nth-child(2) {
        /* Nama */
        width: 200px;
        min-width: 200px;
        max-width: 200px;
    }

    table.data th:nth-child(3),
    table.data td:nth-child(3) {
        /* No. Jersey */
        width: 100px;
        min-width: 100px;
        max-width: 100px;
        text-align: center;
    }

    table.data th:nth-child(4),
    table.data td:nth-child(4) {
        /* Tanggal Lahir */
        width: 140px;
        min-width: 140px;
        max-width: 140px;
    }

    table.data th:nth-child(5),
    table.data td:nth-child(5) {
        /* Jenis Kelamin */
        width: 120px;
        min-width: 120px;
        max-width: 120px;
        text-align: center;
    }

    table.data th:nth-child(6),
    table.data td:nth-child(6) {
        /* Posisi */
        width: 120px;
        min-width: 120px;
        max-width: 120px;
        text-align: center;
    }

    table.data th:nth-child(7),
    table.data td:nth-child(7) {
        /* Kelas */
        width: 100px;
        min-width: 100px;
        max-width: 100px;
        text-align: center;
    }

    table.data th:nth-child(8),
    table.data td:nth-child(8) {
        /* Tahun STTB */
        width: 120px;
        min-width: 120px;
        max-width: 120px;
        text-align: center;
    }

    table.data th:nth-child(9),
    table.data td:nth-child(9) {
        /* Tindakan */
        width: 120px;
        min-width: 120px;
        max-width: 120px;
        text-align: center;
    }

    /* Official table specific widths */
    table.data.official th:nth-child(1),
    table.data.official td:nth-child(1) {
        /* # */
        width: 60px;
        min-width: 60px;
        max-width: 60px;
        text-align: center;
    }

    table.data.official th:nth-child(2),
    table.data.official td:nth-child(2) {
        /* Nama */
        width: 200px;
        min-width: 200px;
        max-width: 200px;
    }

    table.data.official th:nth-child(3),
    table.data.official td:nth-child(3) {
        /* Jabatan */
        width: 140px;
        min-width: 140px;
        max-width: 140px;
        text-align: center;
    }

    table.data.official th:nth-child(4),
    table.data.official td:nth-child(4) {
        /* Email */
        width: 220px;
        min-width: 220px;
        max-width: 220px;
    }

    table.data.official th:nth-child(5),
    table.data.official td:nth-child(5) {
        /* Telepon */
        width: 140px;
        min-width: 140px;
        max-width: 140px;
    }

    table.data.official th:nth-child(6),
    table.data.official td:nth-child(6) {
        /* Jenis Kelamin */
        width: 120px;
        min-width: 120px;
        max-width: 120px;
        text-align: center;
    }

    table.data.official th:nth-child(7),
    table.data.official td:nth-child(7) {
        /* Status Verifikasi */
        width: 150px;
        min-width: 150px;
        max-width: 150px;
        text-align: center;
    }

    table.data.official th:nth-child(8),
    table.data.official td:nth-child(8) {
        /* Tindakan */
        width: 120px;
        min-width: 120px;
        max-width: 120px;
        text-align: center;
    }

    /* ===== BUTTON DETAIL ===== */
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

    /* Responsive untuk jersey */
    @media (max-width: 1200px) {
        .team-info {
            grid-template-columns: 180px 1fr;
            gap: 25px;
        }

        .content-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .logo-box-square {
            height: 220px;
        }

        .logo-box-square img {
            width: 130px;
            height: 130px;
        }

        .jersey-image-container {
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
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

        .logo-box-square {
            max-width: 250px;
            height: 250px;
        }

        .jersey-image-container {
            grid-template-columns: 1fr;
            gap: 20px;
            max-width: 400px;
        }
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 20px;
        }

        .card-header {
            padding: 16px 20px;
            font-size: 1rem;
        }

        .back-link {
            padding: 12px 16px;
            font-size: 13px;
        }

        table.data th,
        table.data td {
            padding: 14px 12px;
            font-size: 13px;
        }

        .btn-detail {
            padding: 8px 14px;
            font-size: 12px;
            min-width: 90px;
            height: 34px;
        }

        .document-link {
            padding: 12px 14px;
            font-size: 13px;
        }

        .btn-action-simple {
            padding: 12px 16px;
            font-size: 13px;
        }

        .jersey-single-item {
            padding: 15px;
        }

        .jersey-image,
        .no-image {
            height: 180px;
        }
    }

    @media (max-width: 576px) {
        .card-body {
            padding: 16px;
        }

        .page-title {
            font-size: 1.4rem;
        }

        .page-subtitle {
            font-size: 0.85rem;
        }

        .back-link {
            padding: 10px 14px;
            font-size: 12px;
        }

        .logo-box-square {
            height: 200px;
        }

        .logo-box-square img {
            width: 120px;
            height: 120px;
        }

        table.data th,
        table.data td {
            padding: 12px 8px;
            font-size: 12px;
        }

        .btn-detail {
            padding: 6px 12px;
            font-size: 11px;
            min-width: 80px;
            height: 32px;
        }

        .document-link {
            padding: 10px 12px;
            font-size: 12px;
        }

        .btn-action-simple {
            padding: 10px 14px;
            font-size: 12px;
        }

        .jersey-main-title {
            font-size: 18px;
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
        <h1 class="page-title">Detail Tim</h1>
        <p class="page-subtitle">Informasi lengkap tim <strong>{{ $mainTeam->school_name }}</strong></p>
    </div>

    <!-- =============================== -->
    <!-- 🔥 INI TAB CATEGORY YANG KAMU TANYAKAN -->
    <!-- =============================== -->
    @include('team_verification.partials.category-tabs')

    <!-- =============================== -->
    <!-- KONTEN DINAMIS BERDASARKAN TAB -->
    <!-- =============================== -->
    @if($activeTab == 'Basket Putra')
        @php
            if(isset($teamData['Basket Putra']) && $teamData['Basket Putra']['exists']) {
                $teamInfo = $teamData['Basket Putra'];
                $team = $teamInfo['team'];
                $players = $teamInfo['players'];
                $officials = $teamInfo['officials'];
            } else {
                $team = null;
                $players = collect();
                $officials = collect();
            }
        @endphp

        @if($team)
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
                                                <span class="status-locked">
                                                    <i class="fas fa-lock"></i> Terkunci
                                                </span>
                                                @else
                                                <span class="status-open">
                                                    <i class="fas fa-lock-open"></i> Terbuka
                                                </span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status Verifikasi</td>
                                            <td>:
                                                @if($team->verification_status == 'verified')
                                                <span class="status-verified">
                                                    <i class="fas fa-check-circle"></i> Terverifikasi
                                                </span>
                                                @else
                                                <span class="status-unverified">
                                                    <i class="fas fa-clock"></i> Belum Diverifikasi
                                                </span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Dokumen -->
                                <div class="documents-section">
                                    <h4><i class="fas fa-file-alt"></i> Dokumen</h4>
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
                                            class="document-link warning"
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
                                <div class="action-buttons">
                                    <h4><i class="fas fa-cogs"></i> Aksi Tim</h4>
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
                                <td class="text-center">
                                    @if($player->role == 'Leader')
                                    <span class="badge-role-leader">Leader</span>
                                    @else
                                    <span class="badge-role-player">Pemain</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($player->jersey_number)
                                    <span class="badge-jersey">{{ $player->jersey_number }}</span>
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
                                    <span class="badge-gender-male">Laki-laki</span>
                                    @elseif($player->gender == 'Female')
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
                                <td class="text-center">
                                    @if($official->team_role)
                                    <span class="badge-official-role">{{ $official->team_role }}</span>
                                    @else
                                    <span style="color: #718096;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($official->email)
                                    <a href="mailto:{{ $official->email }}" style="color: #3b82f6; text-decoration: none; font-weight: 500;">
                                        {{ $official->email }}
                                    </a>
                                    @else
                                    <span style="color: #718096;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($official->phone)
                                    <a href="tel:{{ $official->phone }}" style="color: #10b981; text-decoration: none; font-weight: 500;">
                                        {{ $official->phone }}
                                    </a>
                                    @else
                                    <span style="color: #718096;">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($official->gender == 'male')
                                    <span class="badge-gender-male">Laki-laki</span>
                                    @elseif($official->gender == 'female')
                                    <span class="badge-gender-female">Perempuan</span>
                                    @else
                                    <span style="color: #718096;">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($official->verification_status == 'verified')
                                    <span class="status-verified" style="display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-check-circle"></i> Terverifikasi
                                    </span>
                                    @elseif($official->verification_status == 'rejected')
                                    <span class="status-open" style="display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-times-circle"></i> Ditolak
                                    </span>
                                    @else
                                    <span class="status-unverified" style="display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fas fa-clock"></i> Belum Diverifikasi
                                    </span>
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

        <!-- JERSEY LIST CARD -->
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
        @else
        <!-- Empty State for Unregistered Team -->
        <div class="empty-state">
            <i class="fas fa-basketball-ball" style="color: #3b82f6;"></i>
            <h5>Tim Basket Putra Belum Terdaftar</h5>
            <p>Sekolah <strong>{{ $mainTeam->school_name }}</strong> belum mendaftarkan tim untuk kategori Basket Putra.</p>
            <p style="margin-top: 20px; font-size: 13px; color: #6b7280;">
                <i class="fas fa-info-circle"></i>
                Untuk mendaftarkan tim, gunakan referral code: <strong>{{ $mainTeam->referral_code ?? 'N/A' }}</strong>
            </p>
        </div>
        @endif

    @elseif($activeTab == 'Basket Putri')
        @php
            if(isset($teamData['Basket Putri']) && $teamData['Basket Putri']['exists']) {
                $teamInfo = $teamData['Basket Putri'];
                $team = $teamInfo['team'];
                $players = $teamInfo['players'];
                $officials = $teamInfo['officials'];
            } else {
                $team = null;
                $players = collect();
                $officials = collect();
            }
        @endphp

        @if($team)
        <!-- Team Information Card - Basket Putri -->
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); color: white;">
                <i class="fas fa-info-circle"></i>
                <span>Informasi Tim Basket Putri</span>
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
                                                <span class="status-locked"><i class="fas fa-lock"></i> Terkunci</span>
                                                @else
                                                <span class="status-open"><i class="fas fa-lock-open"></i> Terbuka</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Status Verifikasi</td>
                                            <td>:
                                                @if($team->verification_status == 'verified')
                                                <span class="status-verified"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                                                @else
                                                <span class="status-unverified"><i class="fas fa-clock"></i> Belum Diverifikasi</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>

                                <!-- Dokumen -->
                                <div class="documents-section">
                                    <h4><i class="fas fa-file-alt"></i> Dokumen</h4>
                                    <div class="document-links compact">
                                        <!-- Surat Rekomendasi -->
                                        @if($team->recommendation_letter)
                                        <a href="{{ asset('storage/' . $team->recommendation_letter) }}"
                                            target="_blank"
                                            class="document-link available">
                                            <i class="fas fa-file-pdf"></i>
                                            <span>Surat Rekomendasi</span>
                                            <i class="fas fa-external-link-alt ms-auto" style="font-size: 12px;"></i>
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
                                            <i class="fas fa-external-link-alt ms-auto" style="font-size: 12px;"></i>
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
                                    <h4><i class="fas fa-cogs"></i> Aksi Tim</h4>
                                    <div class="action-buttons-row compact">
                                        @if($team->locked_status != 'locked')
                                        <form action="{{ route('admin.team.lock', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Kunci tim Basket Putri {{ $team->school_name }}?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-lock">
                                                <i class="fas fa-lock"></i> Kunci Tim
                                            </button>
                                        </form>
                                        @else
                                        <form action="{{ route('admin.team.unlock', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Buka kunci tim Basket Putri {{ $team->school_name }}?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-unlock">
                                                <i class="fas fa-unlock"></i> Buka Kunci
                                            </button>
                                        </form>
                                        @endif

                                        @if($team->verification_status != 'verified')
                                        <form action="{{ route('admin.team.verify', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Verifikasi tim Basket Putri {{ $team->school_name }}?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-verify">
                                                <i class="fas fa-check"></i> Verifikasi Tim
                                            </button>
                                        </form>
                                        @else
                                        <form action="{{ route('admin.team.unverify', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Batalkan verifikasi tim Basket Putri {{ $team->school_name }}?')">
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

        <!-- Player List Card - Basket Putri -->
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); color: white;">
                <i class="fas fa-users"></i>
                <span>Daftar Pemain Basket Putri ({{ $players->count() }})</span>
            </div>
            <div class="card-body">
                @if($players->count() > 0)
                <div class="table-container">
                    <table class="data">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Nama</th>
                                <th>Role</th>
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
                                <td>
                                    @if($player->role == 'Leader')
                                    <span class="status-locked"><i class="fas fa-crown"></i> Leader</span>
                                    @else
                                    <span class="status-open"><i class="fas fa-user"></i> Pemain</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span style="display: inline-block; width: 30px; height: 30px; line-height: 30px; background: #ec4899; color: white; border-radius: 50%; font-weight: bold;">
                                        {{ $player->jersey_number ?? '-' }}
                                    </span>
                                </td>
                                <td>
                                    @if($player->birthdate)
                                    {{ \Carbon\Carbon::parse($player->birthdate)->isoFormat('D MMMM YYYY') }}
                                    @else
                                    <span style="color: #718096;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($player->gender == 'Male')
                                    <i class="fas fa-mars text-primary me-1"></i> Laki-laki
                                    @elseif($player->gender == 'Female')
                                    <i class="fas fa-venus text-pink me-1"></i> Perempuan
                                    @else
                                    <span style="color: #718096;">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $player->basketball_position ?? '-' }}</span>
                                </td>
                                <td>{{ $player->grade ?? '-' }}</td>
                                <td>{{ $player->sttb_year ?? '-' }}</td>
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

        <!-- Official List Card - Basket Putri -->
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); color: white;">
                <i class="fas fa-user-tie"></i>
                <span>Daftar Official Basket Putri ({{ $officials->count() }})</span>
            </div>
            <div class="card-body">
                @if($officials->count() > 0)
                <div class="table-container">
                    <table class="data">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Nama</th>
                                <th width="120">Jabatan</th>
                                <th width="150">Email</th>
                                <th width="100">Telepon</th>
                                <th width="120">Jenis Kelamin</th>
                                <th width="120">Status Verifikasi</th>
                                <th width="100" class="text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($officials as $index => $official)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $official->name ?? 'N/A' }}</strong></td>
                                <td>
                                    <span class="badge bg-light text-dark border">{{ $official->team_role ?? '-' }}</span>
                                </td>
                                <td>
                                    <a href="mailto:{{ $official->email }}" class="text-decoration-none">
                                        <i class="fas fa-envelope me-1 text-primary"></i>
                                        {{ $official->email ?? '-' }}
                                    </a>
                                </td>
                                <td>
                                    @if($official->phone)
                                    <a href="tel:{{ $official->phone }}" class="text-decoration-none">
                                        <i class="fas fa-phone me-1 text-success"></i>
                                        {{ $official->phone }}
                                    </a>
                                    @else
                                    <span style="color: #718096;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($official->gender == 'male')
                                    <i class="fas fa-mars text-primary me-1"></i> Laki-laki
                                    @elseif($official->gender == 'female')
                                    <i class="fas fa-venus text-pink me-1"></i> Perempuan
                                    @else
                                    <span style="color: #718096;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($official->verification_status == 'verified')
                                    <span class="status-verified"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                                    @elseif($official->verification_status == 'rejected')
                                    <span class="status-open"><i class="fas fa-times-circle"></i> Ditolak</span>
                                    @else
                                    <span class="status-unverified"><i class="fas fa-clock"></i> Belum Diverifikasi</span>
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
                    <i class="fas fa-inbox"></i>
                    <h5>Belum ada data official</h5>
                    <p>Data official akan muncul di sini setelah ditambahkan.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Jersey List Card - Basket Putri -->
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); color: white;">
                <i class="fas fa-tshirt"></i>
                <span>Daftar Jersey Basket Putri</span>
            </div>
            <div class="card-body">
                <div class="jersey-single-container">
                    <h3 class="jersey-main-title">Galeri Jersey Tim Basket Putri</h3>
                    <div class="jersey-image-container">
                        <!-- Jersey Kandang -->
                        <div class="jersey-single-item" onclick="showJerseyPopup('home')">
                            <p>Jersey Kandang</p>
                            @if($team->jersey_home)
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
                                <span>Belum Upload</span>
                            </div>
                            @endif
                        </div>

                        <!-- Jersey Tandang -->
                        <div class="jersey-single-item" onclick="showJerseyPopup('away')">
                            <p>Jersey Tandang</p>
                            @if($team->jersey_away)
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
                                <span>Belum Upload</span>
                            </div>
                            @endif
                        </div>

                        <!-- Jersey Alternatif -->
                        <div class="jersey-single-item" onclick="showJerseyPopup('alternate')">
                            <p>Jersey Alternatif</p>
                            @if($team->jersey_alternate)
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
                                <span>Belum Upload</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Empty State for Unregistered Team -->
        <div class="empty-state">
            <i class="fas fa-basketball-ball" style="color: #ec4899;"></i>
            <h5>Tim Basket Putri Belum Terdaftar</h5>
            <p>Sekolah <strong>{{ $mainTeam->school_name }}</strong> belum mendaftarkan tim untuk kategori Basket Putri.</p>
            <p style="margin-top: 20px; font-size: 13px; color: #6b7280;">
                <i class="fas fa-info-circle"></i>
                Untuk mendaftarkan tim, gunakan referral code: <strong>{{ $mainTeam->referral_code ?? 'N/A' }}</strong>
            </p>
        </div>
        @endif

    @elseif($activeTab == 'Dancer')
        @php
            if(isset($teamData['Dancer']) && $teamData['Dancer']['exists']) {
                $teamInfo = $teamData['Dancer'];
                $team = $teamInfo['team'];
                $players = $teamInfo['players'];
                $officials = $teamInfo['officials'];
            } else {
                $team = null;
                $players = collect();
                $officials = collect();
            }
        @endphp

        @if($team)
        <!-- Team Information Card - Dancer -->
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
                <i class="fas fa-info-circle"></i>
                <span>Informasi Tim Dancer</span>
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
                                        <form action="{{ route('admin.team.lock', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Kunci tim Dancer {{ $team->school_name }}?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-lock">
                                                <i class="fas fa-lock"></i> Kunci Tim
                                            </button>
                                        </form>
                                        @else
                                        <form action="{{ route('admin.team.unlock', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Buka kunci tim Dancer {{ $team->school_name }}?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-unlock">
                                                <i class="fas fa-unlock"></i> Buka Kunci
                                            </button>
                                        </form>
                                        @endif

                                        @if($team->verification_status != 'verified')
                                        <form action="{{ route('admin.team.verify', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Verifikasi tim Dancer {{ $team->school_name }}?')">
                                            @csrf
                                            <button type="submit" class="btn-action-simple btn-verify">
                                                <i class="fas fa-check"></i> Verifikasi Tim
                                            </button>
                                        </form>
                                        @else
                                        <form action="{{ route('admin.team.unverify', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Batalkan verifikasi tim Dancer {{ $team->school_name }}?')">
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

        <!-- Dancer List Card -->
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
                <i class="fas fa-music"></i>
                <span>Daftar Dancer ({{ $players->count() }})</span>
            </div>
            <div class="card-body">
                @if($players->count() > 0)
                <div class="table-container">
                    <table class="data">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Nama</th>
                                <th width="100">Role</th>
                                <th width="120">Tanggal Lahir</th>
                                <th width="100">Jenis Kelamin</th>
                                <th width="100">Tinggi</th>
                                <th width="100">Berat</th>
                                <th width="100">Ukuran Kaos</th>
                                <th width="100">Status Verifikasi</th>
                                <th width="100" class="text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($players as $index => $player)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $player->name ?? 'N/A' }}</strong></td>
                                <td>
                                    @if($player->role == 'Leader')
                                    <span class="status-locked">Leader</span>
                                    @else
                                    <span class="status-open">Member</span>
                                    @endif
                                </td>
                                <td>
                                    @if($player->birthdate)
                                    {{ \Carbon\Carbon::parse($player->birthdate)->isoFormat('D MMMM YYYY') }}
                                    @else
                                    <span style="color: #718096;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($player->gender == 'male' || $player->gender == 'Laki-laki')
                                    Laki-laki
                                    @elseif($player->gender == 'female' || $player->gender == 'Perempuan')
                                    Perempuan
                                    @else
                                    <span style="color: #718096;">-</span>
                                    @endif
                                </td>
                                <td>{{ $player->height ?? '-' }} cm</td>
                                <td>{{ $player->weight ?? '-' }} kg</td>
                                <td>{{ $player->tshirt_size ?? '-' }}</td>
                                <td>
                                    @if($player->verification_status == 'verified')
                                    <span class="status-verified">Terverifikasi</span>
                                    @elseif($player->verification_status == 'rejected')
                                    <span class="status-open">Ditolak</span>
                                    @else
                                    <span class="status-unverified">Belum Diverifikasi</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.dancer.detail', $player->dancer_id) }}" class="btn-detail">
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
                    <p class="text-muted mt-2" style="font-size: 12px;">
                        <i class="fas fa-info-circle"></i>
                        Tim ini memiliki referral code: <strong>{{ $team->referral_code ?? 'N/A' }}</strong>
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Official List Card - Dancer -->
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
                <i class="fas fa-user-tie"></i>
                <span>Daftar Official Dancer ({{ $officials->count() }})</span>
            </div>
            <div class="card-body">
                @if($officials->count() > 0)
                <div class="table-container">
                    <table class="data">
                        <thead>
                            <tr>
                                <th width="50">#</th>
                                <th>Nama</th>
                                <th width="120">Jabatan</th>
                                <th width="150">Email</th>
                                <th width="100">Telepon</th>
                                <th width="120">Jenis Kelamin</th>
                                <th width="120">Status Verifikasi</th>
                                <th width="100" class="text-center">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($officials as $index => $official)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $official->name ?? 'N/A' }}</strong></td>
                                <td>{{ $official->team_role ?? '-' }}</td>
                                <td>{{ $official->email ?? '-' }}</td>
                                <td>{{ $official->phone ?? '-' }}</td>
                                <td>
                                    @if($official->gender == 'male')
                                    Laki-laki
                                    @elseif($official->gender == 'female')
                                    Perempuan
                                    @else
                                    <span style="color: #718096;">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($official->verification_status == 'verified')
                                    <span class="status-verified">Terverifikasi</span>
                                    @elseif($official->verification_status == 'rejected')
                                    <span class="status-open">Ditolak</span>
                                    @else
                                    <span class="status-unverified">Belum Diverifikasi</span>
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
                    <i class="fas fa-inbox"></i>
                    <h5>Belum ada data official</h5>
                    <p>Data official akan muncul di sini setelah ditambahkan.</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Costume List Card -->
        <div class="card">
            <div class="card-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
                <i class="fas fa-tshirt"></i>
                <span>Daftar Kostum Dancer</span>
            </div>
            <div class="card-body">
                <div class="jersey-single-container">
                    <h3 class="jersey-main-title">Galeri Kostum Tim Dancer</h3>
                    <div class="jersey-image-container">
                        <!-- Kostum Utama -->
                        <div class="jersey-single-item" onclick="showJerseyPopup('home')">
                            <p>Kostum Utama</p>
                            @if($team->jersey_home)
                            <img src="{{ asset('storage/' . $team->jersey_home) }}"
                                alt="Kostum Utama {{ $team->school_name }}"
                                class="jersey-image"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="no-image" style="display: none;">
                                <i class="fas fa-tshirt"></i>
                                <span>Gambar Error</span>
                            </div>
                            @else
                            <div class="no-image">
                                <i class="fas fa-tshirt"></i>
                                <span>Belum Upload</span>
                            </div>
                            @endif
                        </div>

                        <!-- Kostum Alternatif -->
                        <div class="jersey-single-item" onclick="showJerseyPopup('away')">
                            <p>Kostum Alternatif</p>
                            @if($team->jersey_away)
                            <img src="{{ asset('storage/' . $team->jersey_away) }}"
                                alt="Kostum Alternatif {{ $team->school_name }}"
                                class="jersey-image"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="no-image" style="display: none;">
                                <i class="fas fa-tshirt"></i>
                                <span>Gambar Error</span>
                            </div>
                            @else
                            <div class="no-image">
                                <i class="fas fa-tshirt"></i>
                                <span>Belum Upload</span>
                            </div>
                            @endif
                        </div>

                        <!-- Kostum Khusus -->
                        <div class="jersey-single-item" onclick="showJerseyPopup('alternate')">
                            <p>Kostum Khusus</p>
                            @if($team->jersey_alternate)
                            <img src="{{ asset('storage/' . $team->jersey_alternate) }}"
                                alt="Kostum Khusus {{ $team->school_name }}"
                                class="jersey-image"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="no-image" style="display: none;">
                                <i class="fas fa-tshirt"></i>
                                <span>Gambar Error</span>
                            </div>
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
        @else
        <!-- Empty State for Unregistered Team -->
        <div class="empty-state">
            <i class="fas fa-music" style="color: #8b5cf6;"></i>
            <h5>Tim Dancer Belum Terdaftar</h5>
            <p>Sekolah <strong>{{ $mainTeam->school_name }}</strong> belum mendaftarkan tim untuk kategori Dancer.</p>
            <p style="margin-top: 20px; font-size: 13px; color: #6b7280;">
                <i class="fas fa-info-circle"></i>
                Untuk mendaftarkan tim, gunakan referral code: <strong>{{ $mainTeam->referral_code ?? 'N/A' }}</strong>
            </p>
        </div>
        @endif

    @else
        <!-- Fallback jika tidak ada tab aktif -->
        <div class="empty-state">
            <i class="fas fa-exclamation-circle" style="color: #6b7280;"></i>
            <h5>Pilih Kategori Tim</h5>
            <p>Silakan pilih kategori tim dari tab di atas untuk melihat detail.</p>
        </div>
    @endif
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
                cancelButtonColor: '#6b7280',
                reverseButtons: true
            }).then((result) => {
                return result.isConfirmed;
            });
        };

        // Function to show logo popup
        window.showLogoPopup = function() {
            const logoImg = document.getElementById('team-logo');
            const logoPlaceholder = document.getElementById('logo-placeholder-fallback');

            // Cek apakah logo ada dan terlihat
            if (logoImg && logoImg.style.display !== 'none' && logoImg.src) {
                Swal.fire({
                    title: 'Logo Sekolah',
                    html: `<div style="text-align: center;">
                    <img src="${logoImg.src}" alt="Logo Sekolah" style="max-width: 400px; max-height: 400px; border-radius: 12px; margin-bottom: 20px; border: 2px solid #dbeafe; padding: 10px; background: white;">
                    <p style="color: #666; font-size: 16px; font-weight: 600;">{{ $team->school_name ?? $mainTeam->school_name }}</p>
                    <p style="color: #6b7280; font-size: 14px;">{{ $team->category ?? 'Tim' }}</p>
                  </div>`,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: 550,
                    padding: '25px',
                    background: '#fff'
                });
            } else {
                // Tampilkan placeholder di popup
                Swal.fire({
                    title: 'Logo Sekolah',
                    html: `<div style="text-align: center;">
                    <div style="width: 250px; height: 250px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #eff6ff, #dbeafe); border: 2px dashed #bfdbfe; border-radius: 12px; margin: 0 auto 20px;">
                        <i class="fas fa-school" style="font-size: 4rem; color: #3b82f6; margin-bottom: 20px;"></i>
                        <span style="color: #3b82f6; font-size: 16px; font-weight: 500;">Logo Tidak Tersedia</span>
                    </div>
                    <p style="color: #666; font-size: 16px; font-weight: 600;">{{ $team->school_name ?? $mainTeam->school_name }}</p>
                    <p style="color: #6b7280; font-size: 14px;">{{ $team->category ?? 'Tim' }}</p>
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
        window.showJerseyPopup = function(type) {
            const activeTab = '{{ $activeTab }}';
            let itemNames = {
                'home': 'Jersey Kandang',
                'away': 'Jersey Tandang',
                'alternate': 'Jersey Alternatif'
            };

            if (activeTab === 'Dancer') {
                itemNames = {
                    'home': 'Kostum Utama',
                    'away': 'Kostum Alternatif',
                    'alternate': 'Kostum Khusus'
                };
            }

            const itemName = itemNames[type] || 'Jersey/Kostum';
            const itemElement = document.querySelector(`.jersey-single-item:nth-child(${type === 'home' ? 1 : type === 'away' ? 2 : 3})`);

            if (!itemElement) return;

            const itemImg = itemElement.querySelector('.jersey-image');

            if (itemImg && itemImg.style.display !== 'none' && itemImg.src) {
                Swal.fire({
                    title: `${itemName} - ${activeTab}`,
                    html: `<div style="text-align: center;">
                        <img src="${itemImg.src}" alt="${itemName}" style="max-width: 400px; max-height: 400px; border-radius: 8px; margin-bottom: 15px;">
                        <p style="color: #666; font-size: 14px;">{{ $team->school_name ?? $mainTeam->school_name }}</p>
                    </div>`,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: 550,
                    padding: '20px',
                    background: '#fff'
                });
            } else {
                Swal.fire({
                    title: `${itemName} - ${activeTab}`,
                    html: `<div style="text-align: center;">
                        <div style="width: 300px; height: 300px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #f7fafc, #edf2f7); border: 2px dashed #cbd5e0; border-radius: 8px; margin: 0 auto 15px;">
                            <i class="fas fa-tshirt" style="font-size: 4rem; color: #a0aec0; margin-bottom: 20px;"></i>
                            <span style="color: #718096; font-size: 16px;">${itemName} Belum Diupload</span>
                        </div>
                        <p style="color: #666; font-size: 14px;">{{ $team->school_name ?? $mainTeam->school_name }}</p>
                    </div>`,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: 500,
                    padding: '20px',
                    background: '#fff'
                });
            }
        };

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
                    confirmButtonColor: '#3b82f6',
                    cancelButtonColor: '#6b7280',
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
                        }, 800);
                    }
                });
            });
        });
    });
</script>
@endpush
@endsection