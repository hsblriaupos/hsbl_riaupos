@extends('admin.layouts.app')
@section('title', 'Results Management - Administrator')

@section('content')
@php $activeTab = 'result'; @endphp
@include('partials.tabs-pub', compact('activeTab'))

{{-- Include SweetAlert2 --}}
@include('partials.sweetalert')

@php
    // Helper function untuk mendapatkan data logo untuk tabel
    if (!function_exists('getTeamLogoForTable')) {
        function getTeamLogoForTable($team) {
            if (!$team) {
                return [
                    'has_logo' => false,
                    'logo_url' => null,
                    'logo_html_sm' => '<div class="school-logo-placeholder school-logo-sm">
                        <i class="fas fa-school text-secondary"></i>
                    </div>',
                    'logo_html_md' => '<div class="school-logo-placeholder school-logo-md">
                        <i class="fas fa-school text-secondary"></i>
                    </div>',
                    'name' => 'Team Not Found'
                ];
            }
            
            // PERBAIKAN DI SINI:
            // Cek format path logo dari database
            $schoolLogo = $team->school_logo ?? null;
            $hasLogo = !empty($schoolLogo);
            
            // Generate URL yang benar berdasarkan format path
            $logoUrl = null;
            if ($hasLogo) {
                // Jika path sudah dimulai dengan http atau https, gunakan langsung
                if (str_starts_with($schoolLogo, 'http://') || str_starts_with($schoolLogo, 'https://')) {
                    $logoUrl = $schoolLogo;
                } 
                // Jika path relatif (school_logos/...)
                else if (str_starts_with($schoolLogo, 'school_logos/')) {
                    // Gunakan storage URL untuk file di storage
                    $logoUrl = asset('storage/' . $schoolLogo);
                }
                // Jika hanya nama file
                else {
                    // Asumsi file disimpan di storage/app/public/school_logos/
                    $logoUrl = asset('storage/school_logos/' . $schoolLogo);
                }
            }
            
            $schoolName = htmlspecialchars($team->school_name ?? 'N/A');
            $defaultLogoUrl = asset('assets/img/default-school.png');
            
            // Generate HTML untuk logo
            if ($hasLogo && $logoUrl) {
                $logoHtmlSm = '<img src="' . $logoUrl . '" 
                                 alt="' . $schoolName . '" 
                                 class="school-logo-sm rounded-circle border"
                                 onerror="this.onerror=null; this.src=\'' . $defaultLogoUrl . '\'">';
                
                $logoHtmlMd = '<img src="' . $logoUrl . '" 
                                 alt="' . $schoolName . '" 
                                 class="school-logo-md rounded-circle border"
                                 onerror="this.onerror=null; this.src=\'' . $defaultLogoUrl . '\'">';
            } else {
                $logoHtmlSm = '<div class="school-logo-placeholder school-logo-sm">
                    <i class="fas fa-school text-secondary"></i>
                </div>';
                
                $logoHtmlMd = '<div class="school-logo-placeholder school-logo-md">
                    <i class="fas fa-school text-secondary"></i>
                </div>';
            }
            
            return [
                'has_logo' => $hasLogo,
                'logo_url' => $logoUrl,
                'logo_html_sm' => $logoHtmlSm,
                'logo_html_md' => $logoHtmlMd,
                'name' => $schoolName
            ];
        }
    }
@endphp

@push('styles')
<style>
    /* ===== TYPOGRAPHY - SAMA DENGAN MASTER DATA ===== */
    .page-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .page-subtitle {
        color: #7f8c8d;
        font-size: 0.9rem;
    }

    /* ===== CARD STYLING ===== */
    .card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .card-body {
        padding: 16px;
    }

    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #e0e0e0;
        padding: 12px 16px;
    }

    /* ===== FORM ELEMENTS ===== */
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

    .form-control-sm,
    .form-select-sm {
        padding: 4px 8px;
        font-size: 0.8rem;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border: 1px solid #ced4da;
        border-radius: 5px;
        font-size: 0.9rem;
    }

    .input-group-text.bg-light {
        background-color: #f8f9fa !important;
    }

    /* ===== BUTTONS ===== */
    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
        color: white;
        border-radius: 5px;
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
    }

    .btn-outline-secondary {
        background-color: #f8f9fa;
        color: #6c757d;
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-outline-secondary:hover {
        background-color: #e9ecef;
        color: #495057;
    }

    .btn-dark {
        background-color: #2c3e50;
        border-color: #2c3e50;
        color: white;
        border-radius: 5px;
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-dark:hover {
        background-color: #1a252f;
        border-color: #1a252f;
    }

    .btn-sm {
        padding: 4px 10px;
        font-size: 0.8rem;
        border-radius: 4px;
    }

    .btn-group-sm>.btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.7rem;
        border-radius: 3px;
    }

    .btn-outline-primary,
    .btn-outline-danger,
    .btn-outline-success {
        border-width: 1px;
        background-color: transparent;
    }

    .btn-outline-primary {
        color: #3498db;
        border-color: #3498db;
    }

    .btn-outline-primary:hover {
        background-color: #3498db;
        color: white;
    }

    .btn-outline-danger {
        color: #dc2626;
        border-color: #fecaca;
    }

    .btn-outline-danger:hover {
        background-color: #dc2626;
        color: white;
    }

    .btn-outline-success {
        color: #28a745;
        border-color: #c8e6c9;
    }

    .btn-outline-success:hover {
        background-color: #28a745;
        color: white;
    }

    /* ===== TABLE STYLING ===== */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.85rem;
        color: #2c3e50;
        margin-bottom: 0;
    }

    .table th {
        background-color: #f8f9fa;
        padding: 10px 12px;
        text-align: left;
        font-weight: 600;
        color: #2c3e50;
        border-bottom: 2px solid #e0e0e0;
        white-space: nowrap;
        font-size: 0.85rem;
    }

    .table td {
        padding: 10px 12px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
        font-size: 0.85rem;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Status Badges */
    .badge {
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
    }

    .bg-warning.bg-opacity-20 {
        background-color: rgba(255, 193, 7, 0.15) !important;
        color: #b45b0a;
        border: 1px solid #ffeeba;
    }

    .bg-success.bg-opacity-20 {
        background-color: rgba(40, 167, 69, 0.15) !important;
        color: #1e7e34;
        border: 1px solid #c3e6cb;
    }

    .bg-primary.bg-opacity-20 {
        background-color: rgba(13, 110, 253, 0.15) !important;
        color: #0b5ed7;
        border: 1px solid #bbd6fe;
    }

    /* Styling untuk logo sekolah di tabel */
    .school-logo-placeholder {
        border-radius: 50%;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .school-logo-placeholder i {
        color: #6c757d;
    }

    .school-logo-sm {
        width: 30px;
        height: 30px;
    }

    .school-logo-sm i {
        font-size: 0.8rem;
    }

    .school-logo-md {
        width: 80px;
        height: 80px;
    }

    .school-logo-md i {
        font-size: 2rem;
    }

    img.school-logo-sm {
        width: 30px;
        height: 30px;
        object-fit: cover;
        border-radius: 50%;
        border: 1px solid #dee2e6;
    }

    img.school-logo-md {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        border: 2px solid #dee2e6;
        display: block;
        margin: 0 auto;
    }

    /* Warna custom untuk badge season */
    .bg-teal {
        background-color: #20c997 !important;
    }
    
    .bg-teal.bg-opacity-10 {
        background-color: rgba(32, 201, 151, 0.1) !important;
    }
    
    .text-teal {
        color: #20c997 !important;
    }
    
    .border-teal {
        border-color: #20c997 !important;
    }
    
    .border-teal.border-opacity-25 {
        border-color: rgba(32, 201, 151, 0.25) !important;
    }

    /* Warna custom untuk badge series */
    .bg-purple {
        background-color: #6f42c1 !important;
    }
    
    .bg-purple.bg-opacity-10 {
        background-color: rgba(111, 66, 193, 0.1) !important;
    }
    
    .text-purple {
        color: #6f42c1 !important;
    }
    
    .border-purple {
        border-color: #6f42c1 !important;
    }
    
    .border-purple.border-opacity-25 {
        border-color: rgba(111, 66, 193, 0.25) !important;
    }

    /* Pagination */
    .pagination {
        gap: 3px;
        margin: 0;
        padding: 0;
    }

    .page-link {
        border-radius: 4px !important;
        color: #4a5568;
        border: 1px solid #e2e8f0;
        padding: 0.3rem 0.6rem;
        font-size: 0.75rem;
        transition: all 0.2s;
        background: white;
    }

    .page-link:hover {
        background-color: #f1f5f9;
        border-color: #cbd5e0;
        color: #2d3748;
    }

    .page-item.active .page-link {
        background: #3498db;
        border-color: #3498db;
        color: white;
    }

    .page-item.disabled .page-link {
        opacity: 0.5;
        cursor: not-allowed;
        background: #f1f5f9;
    }

    /* Checkbox styling */
    .form-check-input {
        cursor: pointer;
    }

    .form-check-input:checked {
        background-color: #3498db;
        border-color: #3498db;
    }

    /* Empty state button outline - UKURAN LEBIH KECIL */
    .btn-outline-primary.rounded-pill {
        border-radius: 50px !important;
        padding: 0.3rem 1rem !important;
        font-size: 0.85rem;
        font-weight: 500;
        border-width: 1.5px;
        transition: all 0.3s ease;
    }

    .btn-outline-primary.rounded-pill:hover {
        background-color: #3498db;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 10px rgba(52, 152, 219, 0.15);
    }

    .btn-outline-primary.rounded-pill i {
        font-size: 0.9rem;
    }

    /* Modal styling */
    .modal-lg {
        max-width: 700px;
    }
    
    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
    
    .modal-footer {
        border-top: 1px solid #dee2e6;
    }

    /* ===== RESPONSIVE FIX - SAMA DENGAN MASTER DATA ===== */
    @media (max-width: 768px) {
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

        .container {
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

        .row>[class*="col-"] {
            padding-left: 3px !important;
            padding-right: 3px !important;
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }

        /* Button submit di HP */
        .text-end {
            width: 100% !important;
            padding-left: 3px !important;
            padding-right: 3px !important;
        }

        .btn-primary,
        .btn-outline-secondary,
        .btn-dark {
            width: 100% !important;
            margin-top: 5px;
            margin-right: 0 !important;
            font-size: 0.8rem;
            padding: 6px 12px;
        }

        /* Header flex untuk mobile */
        .d-flex.flex-column.flex-md-row {
            flex-direction: column !important;
            align-items: flex-start !important;
            gap: 0.25rem !important;
            margin-bottom: 0.5rem !important;
        }

        .mt-2.mt-md-0 {
            margin-top: 0 !important;
            width: 100%;
        }

        .mt-2.mt-md-0 .btn {
            width: 100%;
        }

        .d-flex.gap-2 {
            width: 100%;
            display: flex;
            gap: 8px;
        }

        .d-flex.gap-2 .btn {
            flex: 1;
            text-align: center;
        }

        /* Card styling */
        .card {
            width: 100% !important;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .card-body {
            padding: 10px;
        }

        .card-footer {
            padding: 10px;
        }

        /* Table styling */
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin: 0;
        }

        .table {
            font-size: 0.75rem;
            min-width: 100%;
        }

        .table th {
            padding: 6px 4px;
            font-size: 0.7rem;
            white-space: nowrap;
        }

        .table td {
            padding: 6px 4px;
            font-size: 0.7rem;
        }

        .page-title {
            font-size: 1.1rem;
        }

        .page-subtitle {
            font-size: 0.75rem;
        }

        .badge {
            font-size: 0.65rem;
            padding: 2px 5px;
        }

        .btn-group-sm>.btn {
            padding: 0.15rem 0.3rem;
            font-size: 0.65rem;
        }

        .btn-group-sm>.btn i {
            font-size: 0.6rem;
        }

        /* Hide some columns on mobile */
        .table td:nth-child(9), /* Series */
        .table td:nth-child(8), /* Competition */
        .table th:nth-child(9),
        .table th:nth-child(8) {
            display: none;
        }

        /* Filter Section */
        .row.g-3 {
            margin: 0;
        }

        .col-md-2,
        .col-md-1 {
            padding: 0 3px !important;
        }

        .form-label.small {
            font-size: 0.7rem !important;
            margin-bottom: 2px;
        }

        .form-control-sm,
        .form-select-sm {
            padding: 3px 6px;
            font-size: 0.7rem;
        }

        .input-group-text {
            padding: 3px 6px;
            font-size: 0.7rem;
        }

        /* Pagination */
        .card-footer .d-flex {
            flex-direction: column !important;
            gap: 10px !important;
        }

        .pagination {
            justify-content: center;
        }

        .page-link {
            padding: 0.2rem 0.4rem;
            font-size: 0.65rem;
        }

        /* Bulk actions */
        .mt-3.d-flex {
            flex-direction: column;
            gap: 10px;
            align-items: flex-start !important;
        }

        .form-check-label {
            font-size: 0.7rem;
        }

        /* Modal responsive */
        .modal-dialog {
            margin: 10px;
        }

        .modal-body .row.g-0 {
            flex-direction: column;
        }

        .modal-body .col-md-5,
        .modal-body .col-md-2,
        .modal-body .col-md-6 {
            width: 100% !important;
            border: none !important;
        }

        .modal-body .col-md-2 {
            order: 3;
            margin-top: 20px;
        }

        .school-logo-md,
        .modal-logo-img,
        .modal-logo-placeholder {
            width: 60px !important;
            height: 60px !important;
        }

        .school-logo-md i,
        .modal-logo-placeholder i {
            font-size: 1.5rem;
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

        .container {
            padding-left: 2px !important;
            padding-right: 2px !important;
        }

        .row>[class*="col-"] {
            padding-left: 2px !important;
            padding-right: 2px !important;
        }

        .table {
            font-size: 0.7rem;
        }

        .table th,
        .table td {
            padding: 4px 3px;
            font-size: 0.65rem;
        }

        .page-title {
            font-size: 1rem;
        }

        .btn-primary,
        .btn-outline-secondary,
        .btn-dark {
            font-size: 0.7rem;
            padding: 4px 8px;
        }

        .badge {
            font-size: 0.6rem;
            padding: 1px 4px;
        }

        .btn-group-sm>.btn {
            padding: 0.1rem 0.2rem;
        }

        .btn-group-sm>.btn i {
            font-size: 0.55rem;
        }

        /* Hide more columns on very small screens */
        .table td:nth-child(7), /* Season */
        .table th:nth-child(7) {
            display: none;
        }

        .form-label.small {
            font-size: 0.65rem !important;
        }

        .form-control-sm,
        .form-select-sm {
            padding: 2px 4px;
            font-size: 0.65rem;
        }

        .input-group-text {
            padding: 2px 4px;
            font-size: 0.65rem;
        }

        .pagination .page-link {
            padding: 0.15rem 0.3rem;
            font-size: 0.6rem;
        }

        /* Modal responsive */
        .school-logo-md,
        .modal-logo-img,
        .modal-logo-placeholder {
            width: 50px !important;
            height: 50px !important;
        }

        .school-logo-md i,
        .modal-logo-placeholder i {
            font-size: 1.2rem;
        }
    }
</style>
@endpush

<div class="container" style="max-width: 100%; padding-left: 15px; padding-right: 15px;">
    <!-- Page Header with Action Buttons -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center" style="margin-bottom: 0.5rem;">
        <div>
            <h1 class="page-title mt-3">
                <i class="fas fa-trophy text-primary me-2"></i> Results Management
            </h1>
            <p class="page-subtitle">Manage match results and scores</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="mt-2 mt-md-0">
            <a href="{{ route('admin.pub_result.create') }}" 
               class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-plus me-1"></i> Add Result
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pub_result.index') }}" class="row g-3 align-items-end">
                <div class="col-md-2 col-sm-6 col-12">
                    <label class="form-label small text-muted mb-1">Search Team</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input name="search" 
                               type="text" 
                               value="{{ request('search') }}"
                               class="form-control border-start-0"
                               placeholder="Search...">
                    </div>
                </div>
                
                <div class="col-md-2 col-sm-6 col-12">
                    <label class="form-label small text-muted mb-1">Season</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-calendar-alt text-muted"></i>
                        </span>
                        <select name="season" class="form-select border-start-0">
                            <option value="">All Seasons</option>
                            @foreach($seasons as $season)
                                <option value="{{ $season }}" @selected(request('season')==$season)>
                                    {{ $season }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2 col-sm-6 col-12">
                    <label class="form-label small text-muted mb-1">Competition</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-trophy text-muted"></i>
                        </span>
                        <select name="competition" class="form-select border-start-0">
                            <option value="">All Competitions</option>
                            @foreach($competitions as $comp)
                                <option value="{{ $comp }}" @selected(request('competition')==$comp)>
                                    {{ $comp }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2 col-sm-6 col-12">
                    <label class="form-label small text-muted mb-1">Series</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-map-marker-alt text-muted"></i>
                        </span>
                        <select name="series" class="form-select border-start-0">
                            <option value="">All Series</option>
                            @foreach($seriesList as $series)
                                <option value="{{ $series }}" @selected(request('series')==$series)>
                                    {{ $series }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2 col-sm-6 col-12">
                    <label class="form-label small text-muted mb-1">Status</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-info-circle text-muted"></i>
                        </span>
                        <select name="status" class="form-select border-start-0">
                            <option value="">All Status</option>
                            <option value="draft" @selected(request('status')=='draft')>Draft</option>
                            <option value="publish" @selected(request('status')=='publish')>Published</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-1 col-sm-6 col-12">
                    <label class="form-label small text-muted mb-1">Show</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-list text-muted"></i>
                        </span>
                        <select name="per_page" class="form-select border-start-0" onchange="this.form.submit()">
                            <option value="10" @selected(request('per_page', 10)==10)>10</option>
                            <option value="25" @selected(request('per_page')==25)>25</option>
                            <option value="50" @selected(request('per_page')==50)>50</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-1 col-sm-6 col-12">
                    <button type="submit" class="btn btn-dark btn-sm w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
                
                <div class="col-md-1 col-sm-6 col-12">
                    <a href="{{ route('admin.pub_result.index') }}" 
                       class="btn btn-outline-secondary btn-sm w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-redo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="px-2 py-1" style="width: 25px;">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th class="px-2 py-1" style="width: 35px;">No</th>
                            <th class="px-2 py-1" style="width: 65px;">Date</th>
                            <th class="px-2 py-1" style="min-width: 70px;">Team 1</th>
                            <th class="px-2 py-1 text-center" style="width: 45px;">Score</th>
                            <th class="px-2 py-1" style="min-width: 70px;">Team 2</th>
                            <th class="px-2 py-1 text-center" style="width: 70px;">Season</th>
                            <th class="px-2 py-1" style="width: 70px;">Competition</th>
                            <th class="px-2 py-1 text-center" style="width: 60px;">Series</th>
                            <th class="px-2 py-1 text-center" style="width: 35px;">Scoresheet</th>
                            <th class="px-2 py-1 text-center" style="width: 50px;">Status</th>
                            <th class="px-2 py-1 text-center" style="width: 85px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($results as $index => $result)
                            @php
                                // Data untuk team1 dan team2
                                $team1Data = getTeamLogoForTable($result->team1);
                                $team2Data = getTeamLogoForTable($result->team2);
                            @endphp
                            <tr>
                                <td class="px-2 py-1">
                                    <input type="checkbox" 
                                           name="selected[]" 
                                           value="{{ $result->id }}" 
                                           class="form-check-input item-checkbox">
                                </td>
                                <td class="px-2 py-1 fw-medium text-muted">
                                    {{ $results->firstItem() + $index }}
                                </td>
                                <td class="px-2 py-1">
                                    {{ \Carbon\Carbon::parse($result->match_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-2 py-1">
                                    <div class="d-flex align-items-center">
                                        {!! $team1Data['logo_html_sm'] !!}
                                        <span class="text-truncate d-inline-block" style="max-width: 70px;" 
                                              title="{{ $team1Data['name'] }}">
                                            {{ $team1Data['name'] }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-2 py-1 text-center">
                                    <span class="badge bg-dark text-white" style="font-size: 0.7rem;">
                                        {{ $result->score_1 ?? '0' }} - {{ $result->score_2 ?? '0' }}
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    <div class="d-flex align-items-center">
                                        {!! $team2Data['logo_html_sm'] !!}
                                        <span class="text-truncate d-inline-block" style="max-width: 70px;" 
                                              title="{{ $team2Data['name'] }}">
                                            {{ $team2Data['name'] }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-2 py-1 text-center">
                                    <span class="badge bg-teal bg-opacity-10 text-teal border border-teal border-opacity-25"
                                          title="{{ $result->season ?? 'N/A' }}">
                                        {{ $result->season ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25"
                                          title="{{ $result->competition ?? 'N/A' }}">
                                        {{ $result->competition ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-2 py-1 text-center">
                                    <span class="badge bg-purple bg-opacity-10 text-purple border border-purple border-opacity-25"
                                          title="{{ $result->series ?? 'N/A' }}">
                                        {{ $result->series ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-2 py-1 text-center">
                                    @if($result->scoresheet)
                                        <a href="{{ route('admin.pub_result.download_scoresheet', $result->id) }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-outline-success"
                                           title="View Scoresheet (Excel)">
                                            <i class="fas fa-file-excel"></i>
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-2 py-1 text-center">
                                    @php
                                        if ($result->status === 'draft') {
                                            $badgeClass = 'bg-warning bg-opacity-20';
                                            $badgeIcon = 'fas fa-edit';
                                            $statusText = 'Draft';
                                        } elseif ($result->status === 'publish') {
                                            $badgeClass = 'bg-success bg-opacity-20';
                                            $badgeIcon = 'fas fa-check-circle';
                                            $statusText = 'Published';
                                        } else {
                                            $badgeClass = 'bg-secondary bg-opacity-10';
                                            $badgeIcon = 'fas fa-archive';
                                            $statusText = ucfirst($result->status);
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }}" title="{{ $statusText }}">
                                        <i class="{{ $badgeIcon }} me-1"></i>
                                    </span>
                                </td>
                                <td class="px-2 py-1 text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- View Details (Eye Icon) -->
                                        <button type="button" 
                                                class="btn btn-outline-info view-details-btn"
                                                title="View Details"
                                                data-result-id="{{ $result->id }}"
                                                data-team1-name="{{ $team1Data['name'] }}"
                                                data-team2-name="{{ $team2Data['name'] }}"
                                                data-team1-logo-html="{{ htmlspecialchars($team1Data['logo_html_md']) }}"
                                                data-team2-logo-html="{{ htmlspecialchars($team2Data['logo_html_md']) }}"
                                                data-team1-has-logo="{{ $team1Data['has_logo'] ? 'true' : 'false' }}"
                                                data-team2-has-logo="{{ $team2Data['has_logo'] ? 'true' : 'false' }}"
                                                data-team1-logo-url="{{ $team1Data['logo_url'] ?? '' }}"
                                                data-team2-logo-url="{{ $team2Data['logo_url'] ?? '' }}"
                                                data-competition-type="{{ $result->competition_type ?? 'N/A' }}"
                                                data-phase="{{ $result->phase ?? 'N/A' }}"
                                                data-match-date="{{ \Carbon\Carbon::parse($result->match_date)->format('d M Y') }}"
                                                data-score="{{ $result->score_1 ?? '0' }} - {{ $result->score_2 ?? '0' }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        @if ($result->status !== 'done')
                                            <a href="{{ route('admin.pub_result.edit', $result->id) }}" 
                                               class="btn btn-outline-primary"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @else
                                            <button type="button" 
                                                    class="btn btn-outline-secondary"
                                                    disabled
                                                    title="Cannot edit done result">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif
                                        
                                        <!-- Delete Button -->
                                        <button type="button" 
                                                class="btn btn-outline-danger delete-btn"
                                                title="Delete"
                                                data-result-id="{{ $result->id }}"
                                                data-team1-name="{{ $team1Data['name'] }}"
                                                data-team2-name="{{ $team2Data['name'] }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        
                                        @if ($result->status === 'draft')
                                            <!-- Publish Button -->
                                            <button type="button" 
                                                    class="btn btn-outline-success publish-btn"
                                                    title="Publish"
                                                    data-result-id="{{ $result->id }}"
                                                    data-team1-name="{{ $team1Data['name'] }}"
                                                    data-team2-name="{{ $team2Data['name'] }}">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-trophy"></i>
                                        <h6 class="text-muted">No Results Found</h6>
                                        <p class="text-muted small mb-3">Start by adding your first match result</p>
                                        <a href="{{ route('admin.pub_result.create') }}" 
                                           class="btn btn-outline-primary px-3 py-1 rounded-pill">
                                            <i class="fas fa-plus-circle me-2"></i> Add First Result
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Table Footer with Pagination --}}
        @if($results->hasPages() || $results->total() > 0)
        <div class="card-footer">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <div class="mb-2 mb-md-0">
                    <p class="small text-muted mb-0">
                        Showing <span class="fw-semibold">{{ $results->firstItem() ?: 0 }}</span> to 
                        <span class="fw-semibold">{{ $results->lastItem() ?: 0 }}</span> of 
                        <span class="fw-semibold">{{ $results->total() }}</span> results
                    </p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    @if($results->hasPages())
                        {{ $results->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
    
    {{-- Bulk Actions --}}
    <div class="mt-3 d-flex justify-content-between align-items-center">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="bulkSelectAll">
            <label class="form-check-label small text-muted" for="bulkSelectAll">
                Select all items ({{ $results->total() }} total)
            </label>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn">
                <i class="fas fa-trash me-1"></i> Delete Selected
            </button>
        </div>
    </div>
</div>

{{-- Modal for Viewing Details --}}
<div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title fw-semibold" id="detailsModalLabel">Match Details</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Team 1 -->
                    <div class="col-md-5 border-end">
                        <div class="text-center py-4">
                            <div class="d-flex justify-content-center align-items-center mb-3" style="height: 100px;">
                                <div id="team1LogoContainer" class="d-flex justify-content-center align-items-center w-100">
                                    <!-- Logo akan diisi oleh JavaScript -->
                                </div>
                            </div>
                            <h6 class="fw-bold mb-1" id="team1Name"></h6>
                            <div class="badge bg-primary bg-opacity-10 text-primary py-1 px-3 mt-2">
                                Team 1
                            </div>
                        </div>
                    </div>
                    
                    <!-- Match Info -->
                    <div class="col-md-2">
                        <div class="text-center h-100 d-flex flex-column justify-content-center">
                            <div class="mb-2">
                                <span class="badge bg-dark text-white fs-6 py-2 px-3" id="matchScore">0-0</span>
                            </div>
                            <div class="text-muted small mt-2">
                                <i class="fas fa-calendar-alt me-1"></i>
                                <span id="matchDate">-</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Team 2 -->
                    <div class="col-md-5 border-start">
                        <div class="text-center py-4">
                            <div class="d-flex justify-content-center align-items-center mb-3" style="height: 100px;">
                                <div id="team2LogoContainer" class="d-flex justify-content-center align-items-center w-100">
                                    <!-- Logo akan diisi oleh JavaScript -->
                                </div>
                            </div>
                            <h6 class="fw-bold mb-1" id="team2Name"></h6>
                            <div class="badge bg-danger bg-opacity-10 text-danger py-1 px-3 mt-2">
                                Team 2
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Details -->
                <div class="border-top">
                    <div class="row g-0">
                        <div class="col-md-6 border-end">
                            <div class="p-3">
                                <h6 class="fw-semibold small text-muted mb-2">Competition Details</h6>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Type:</span>
                                    <span class="fw-semibold small" id="detailCompetitionType">-</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Phase:</span>
                                    <span class="fw-semibold small" id="detailPhase">-</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Series:</span>
                                    <span class="badge bg-purple bg-opacity-10 text-purple" id="detailSeries">-</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3">
                                <h6 class="fw-semibold small text-muted mb-2">Match Info</h6>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Season:</span>
                                    <span class="badge bg-teal bg-opacity-10 text-teal" id="detailSeason">-</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Competition:</span>
                                    <span class="badge bg-info bg-opacity-10 text-info" id="detailCompetition">-</span>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted small">Status:</span>
                                    <span class="badge" id="detailStatus">-</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer py-2">
                <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Close</button>
                <a href="#" id="editLink" class="btn btn-sm btn-primary">
                    <i class="fas fa-edit me-1"></i> Edit Result
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Hidden Bulk Delete Form --}}
<form id="bulkDeleteForm" method="POST" action="{{ route('admin.pub_result.bulk-destroy') }}" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ===== SELECT ALL CHECKBOX =====
        const selectAllCheckbox = document.getElementById('selectAll');
        const bulkSelectAllCheckbox = document.getElementById('bulkSelectAll');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');

        function updateSelectAllCheckboxes() {
            const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
            const anyChecked = Array.from(itemCheckboxes).some(cb => cb.checked);

            if (selectAllCheckbox) {
                selectAllCheckbox.checked = allChecked;
                selectAllCheckbox.indeterminate = anyChecked && !allChecked;
            }

            if (bulkSelectAllCheckbox) {
                bulkSelectAllCheckbox.checked = allChecked;
                bulkSelectAllCheckbox.indeterminate = anyChecked && !allChecked;
            }
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                updateSelectAllCheckboxes();
            });
        }

        if (bulkSelectAllCheckbox) {
            bulkSelectAllCheckbox.addEventListener('change', function() {
                itemCheckboxes.forEach(checkbox => {
                    checkbox.checked = bulkSelectAllCheckbox.checked;
                });
                updateSelectAllCheckboxes();
            });
        }

        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectAllCheckboxes);
        });

        // ===== SHOW IMAGE FUNCTION =====
        window.showImage = function(src) {
            // Not used in this page, but kept for consistency
        };

        // ===== VIEW DETAILS MODAL =====
        function decodeHtmlEntities(text) {
            const textArea = document.createElement('textarea');
            textArea.innerHTML = text;
            return textArea.value;
        }

        function setModalLogoCentered(containerId, logoHtml) {
            const container = document.getElementById(containerId);
            container.innerHTML = '';
            
            const wrapper = document.createElement('div');
            wrapper.className = 'modal-logo-wrapper';
            wrapper.innerHTML = decodeHtmlEntities(logoHtml);
            
            const elements = wrapper.children;
            for (let element of elements) {
                element.style.margin = '0 auto';
                element.style.display = 'block';
                
                if (element.classList.contains('school-logo-placeholder') || 
                    element.classList.contains('modal-logo-placeholder') ||
                    element.classList.contains('school-logo-md')) {
                    element.style.display = 'flex';
                    element.style.justifyContent = 'center';
                    element.style.alignItems = 'center';
                    element.style.margin = '0 auto';
                }
                
                if (element.tagName === 'IMG') {
                    element.style.display = 'block';
                    element.style.margin = '0 auto';
                    element.className = element.className + ' modal-logo-img';
                    
                    element.onerror = function() {
                        this.onerror = null;
                        this.src = "{{ asset('assets/img/default-school.png') }}";
                        this.style.margin = '0 auto';
                        this.style.display = 'block';
                    };
                }
            }
            
            container.appendChild(wrapper);
        }

        document.querySelectorAll('.view-details-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const team1Name = this.getAttribute('data-team1-name');
                const team2Name = this.getAttribute('data-team2-name');
                const team1LogoHtml = this.getAttribute('data-team1-logo-html');
                const team2LogoHtml = this.getAttribute('data-team2-logo-html');
                const competitionType = this.getAttribute('data-competition-type');
                const phase = this.getAttribute('data-phase');
                const matchDate = this.getAttribute('data-match-date');
                const score = this.getAttribute('data-score');
                const resultId = this.getAttribute('data-result-id');
                
                const row = this.closest('tr');
                const season = row.querySelector('td:nth-child(7) .badge')?.textContent || '-';
                const competition = row.querySelector('td:nth-child(8) .badge')?.textContent || '-';
                const series = row.querySelector('td:nth-child(9) .badge')?.textContent || '-';
                const statusBadge = row.querySelector('td:nth-child(11) .badge');
                const status = statusBadge ? statusBadge.getAttribute('title') : '-';
                const statusClass = statusBadge ? statusBadge.className : 'badge bg-secondary';
                
                document.getElementById('team1Name').textContent = team1Name;
                document.getElementById('team2Name').textContent = team2Name;
                
                setModalLogoCentered('team1LogoContainer', team1LogoHtml);
                setModalLogoCentered('team2LogoContainer', team2LogoHtml);
                
                document.getElementById('detailCompetitionType').textContent = competitionType;
                document.getElementById('detailPhase').textContent = phase;
                document.getElementById('matchScore').textContent = score;
                document.getElementById('matchDate').textContent = matchDate;
                document.getElementById('detailSeason').textContent = season;
                document.getElementById('detailCompetition').textContent = competition;
                document.getElementById('detailSeries').textContent = series;
                
                const detailStatus = document.getElementById('detailStatus');
                detailStatus.textContent = status;
                detailStatus.className = statusClass + ' px-2 py-1';
                
                document.getElementById('editLink').href = `/admin/pub_result/${resultId}/edit`;
                
                const detailsModal = new bootstrap.Modal(document.getElementById('detailsModal'));
                detailsModal.show();
            });
        });

        // ===== DELETE CONFIRMATION =====
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const resultId = this.getAttribute('data-result-id');
                const team1Name = this.getAttribute('data-team1-name');
                const team2Name = this.getAttribute('data-team2-name');
                const title = `${team1Name} vs ${team2Name}`;
                
                Swal.fire({
                    title: 'Delete Result?',
                    html: `Are you sure you want to delete <strong>"${title}"</strong>?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/pub_result/${resultId}`;
                        form.style.display = 'none';
                        
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = "{{ csrf_token() }}";
                        form.appendChild(csrfInput);
                        
                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'DELETE';
                        form.appendChild(methodInput);
                        
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

        // ===== PUBLISH CONFIRMATION =====
        document.querySelectorAll('.publish-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const resultId = this.getAttribute('data-result-id');
                const team1Name = this.getAttribute('data-team1-name');
                const team2Name = this.getAttribute('data-team2-name');
                const title = `${team1Name} vs ${team2Name}`;
                
                Swal.fire({
                    title: 'Publish Result?',
                    html: `Are you sure you want to publish <strong>"${title}"</strong>?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, publish it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/pub_result/${resultId}/publish`;
                        form.style.display = 'none';
                        
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = "{{ csrf_token() }}";
                        form.appendChild(csrfInput);
                        
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });

        // ===== BULK DELETE =====
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', function() {
                const selectedItems = Array.from(document.querySelectorAll('.item-checkbox:checked'));

                if (selectedItems.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'No Items Selected',
                        text: 'Please select at least one item to delete',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    return;
                }

                Swal.fire({
                    title: 'Delete Selected Items?',
                    html: `Are you sure you want to delete <strong>${selectedItems.length}</strong> selected result(s)?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete them!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        const bulkDeleteForm = document.getElementById('bulkDeleteForm');
                        const selectedIds = selectedItems.map(item => item.value);

                        bulkDeleteForm.querySelectorAll('input[name="selected[]"]').forEach(input => {
                            input.remove();
                        });

                        selectedIds.forEach(id => {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = 'selected[]';
                            input.value = id;
                            bulkDeleteForm.appendChild(input);
                        });

                        bulkDeleteForm.submit();
                    }
                });
            });
        }

        // ===== AUTO SUBMIT FILTER =====
        const filterForm = document.getElementById('filterForm');
        if (filterForm) {
            const filterSelects = filterForm.querySelectorAll('select:not([name="per_page"])');
            
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    filterForm.submit();
                });
            });

            const perPageSelect = document.querySelector('select[name="per_page"]');
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    filterForm.submit();
                });
            }
        }

        // ===== TOOLTIPS =====
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush
@endsection