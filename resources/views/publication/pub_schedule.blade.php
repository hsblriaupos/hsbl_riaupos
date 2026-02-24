@extends('admin.layouts.app')
@section('title', 'Schedules Management - Administrator')

@section('content')
@php $activeTab = 'schedule'; @endphp
@include('partials.tabs-pub', compact('activeTab'))

{{-- Include SweetAlert2 --}}
@include('partials.sweetalert')

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

    .form-control, .form-select {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 8px 12px;
        font-size: 0.9rem;
        width: 100%;
        transition: all 0.2s;
    }

    .form-control:focus, .form-select:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        outline: none;
    }

    .form-control-sm, .form-select-sm {
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

    .btn-group-sm > .btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.7rem;
        border-radius: 3px;
    }

    .btn-outline-primary, .btn-outline-danger, .btn-outline-success {
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

    /* Image thumbnail */
    .img-thumbnail-wrapper {
        width: 40px;
        height: 30px;
        background-color: #f8f9fa;
        border-radius: 4px;
        overflow: hidden;
        cursor: pointer;
        border: 1px solid #e0e0e0;
    }

    .img-thumbnail-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Text utilities */
    .text-truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #95a5a6;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #bdc3c7;
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

        .row > [class*="col-"] {
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

        .btn-primary, .btn-outline-secondary, .btn-dark {
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
            gap: 10px;
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

        .btn-group-sm > .btn {
            padding: 0.15rem 0.3rem;
            font-size: 0.65rem;
        }

        .btn-group-sm > .btn i {
            font-size: 0.6rem;
        }

        .img-thumbnail-wrapper {
            width: 35px;
            height: 25px;
        }

        /* Filter Section */
        .row.g-3 {
            margin: 0;
        }

        .col-md-2 {
            padding: 0 3px !important;
        }

        .form-label.small {
            font-size: 0.7rem !important;
            margin-bottom: 2px;
        }

        .form-control-sm, .form-select-sm {
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

        .row > [class*="col-"] {
            padding-left: 2px !important;
            padding-right: 2px !important;
        }

        .table {
            font-size: 0.7rem;
        }

        .table th, .table td {
            padding: 4px 3px;
            font-size: 0.65rem;
        }

        .page-title {
            font-size: 1rem;
        }

        .btn-primary, .btn-outline-secondary, .btn-dark {
            font-size: 0.7rem;
            padding: 4px 8px;
        }

        .badge {
            font-size: 0.6rem;
            padding: 1px 4px;
        }

        .btn-group-sm > .btn {
            padding: 0.1rem 0.2rem;
        }

        .btn-group-sm > .btn i {
            font-size: 0.55rem;
        }

        .img-thumbnail-wrapper {
            width: 30px;
            height: 22px;
        }

        .form-label.small {
            font-size: 0.65rem !important;
        }

        .form-control-sm, .form-select-sm {
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
    }
</style>
@endpush

<div class="container" style="max-width: 100%; padding-left: 15px; padding-right: 15px;">
    <!-- Page Header with Action Buttons -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">
        <div class="mb-3 mb-md-0 mt-2">
            <h1 class="page-title">
                <i class="fas fa-calendar-alt text-primary me-2"></i> Schedules Management
            </h1>
            <p class="page-subtitle">Manage match schedules and timetables</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="d-flex gap-2">
            <a href="{{ route('admin.pub_schedule.create') }}" 
               class="btn btn-primary d-flex align-items-center">
                <i class="fas fa-plus "></i> Add Schedule
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.pub_schedule.index') }}" class="row g-3 align-items-end">
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Search Title</label>
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
                
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Series Filter</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-filter text-muted"></i>
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
                
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Year Filter</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-calendar-alt text-muted"></i>
                        </span>
                        <select name="year" class="form-select border-start-0">
                            <option value="">All Years</option>
                            @php
                                $currentYear = date('Y');
                                $minYear = $schedules->isNotEmpty() ? $schedules->min('created_at')->format('Y') : $currentYear;
                                $years = range($minYear, $currentYear);
                            @endphp
                            @foreach(array_reverse($years) as $year)
                                <option value="{{ $year }}" @selected(request('year')==$year)>
                                    {{ $year }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Status Filter</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-flag text-muted"></i>
                        </span>
                        <select name="status" class="form-select border-start-0">
                            <option value="">All Status</option>
                            <option value="draft" @selected(request('status')=='draft')>Draft</option>
                            <option value="publish" @selected(request('status')=='publish')>Published</option>
                            <option value="done" @selected(request('status')=='done')>Done</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-2">
                    <label class="form-label small text-muted mb-1">Show Per Page</label>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-list text-muted"></i>
                        </span>
                        <select name="per_page" class="form-select border-start-0" onchange="this.form.submit()">
                            <option value="10" @selected(request('per_page', 10)==10)>10</option>
                            <option value="25" @selected(request('per_page')==25)>25</option>
                            <option value="50" @selected(request('per_page')==50)>50</option>
                            <option value="100" @selected(request('per_page')==100)>100</option>
                        </select>
                    </div>
                </div>
                
                <div class="col-md-1">
                    <button type="submit" class="btn btn-dark btn-sm w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                </div>
                
                <div class="col-md-1">
                    <a href="{{ route('admin.pub_schedule.index') }}" 
                       class="btn btn-outline-secondary btn-sm w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-redo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Schedules Table -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="px-2 py-1" style="width: 30px;">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th class="px-2 py-1" style="width: 40px;">No</th>
                            <th class="px-2 py-1" style="width: 80px;">Schedule Date</th>
                            <th class="px-2 py-1" style="min-width: 100px;">Title</th>
                            <th class="px-2 py-1" style="min-width: 120px;">Series</th>
                            <th class="px-2 py-1" style="width: 60px;">Image</th>
                            <th class="px-2 py-1" style="width: 100px;">Caption</th>
                            <th class="px-2 py-1" style="width: 80px;">Status</th>
                            <th class="px-2 py-1" style="width: 80px;">Created</th>
                            <th class="px-2 py-1 text-center" style="width: 140px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $index => $schedule)
                            <tr>
                                <td class="px-2 py-1">
                                    <input type="checkbox" 
                                           name="selected[]" 
                                           value="{{ $schedule->id }}" 
                                           class="form-check-input item-checkbox">
                                </td>
                                <td class="px-2 py-1 fw-medium text-muted">
                                    {{ $schedules->firstItem() + $index }}
                                </td>
                                <td class="px-2 py-1">
                                    {{ \Carbon\Carbon::parse($schedule->upload_date)->format('d/m/Y') }}
                                </td>
                                <td class="px-2 py-1">
                                    <span class="text-truncate d-inline-block" style="max-width: 100px;" 
                                          title="{{ $schedule->main_title }}">
                                        {{ Str::limit($schedule->main_title, 15) }}
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25"
                                          title="{{ $schedule->series_name }}">
                                        {{ Str::limit($schedule->series_name, 20) }}
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    @if($schedule->layout_image)
                                        @php
                                            $imagePath = $schedule->layout_image;
                                            $fullPath = asset($imagePath);
                                        @endphp
                                        <div class="img-thumbnail-wrapper" onclick="showImage('{{ $fullPath }}')">
                                            <img src="{{ $fullPath }}" 
                                                 alt="{{ $schedule->main_title }}"
                                                 onerror="this.onerror=null; this.src='{{ asset('img/no-image.png') }}';">
                                        </div>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="px-2 py-1">
                                    <span class="text-truncate d-inline-block" style="max-width: 100px;"
                                          title="{{ $schedule->caption ?: 'No caption' }}">
                                        {{ $schedule->caption ? Str::limit($schedule->caption, 20) : '-' }}
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    @php
                                        if ($schedule->status === 'draft') {
                                            $badgeClass = 'bg-warning bg-opacity-20';
                                            $badgeIcon = 'fas fa-edit';
                                            $statusText = 'Draft';
                                        } elseif ($schedule->status === 'publish') {
                                            $badgeClass = 'bg-success bg-opacity-20';
                                            $badgeIcon = 'fas fa-check-circle';
                                            $statusText = 'Published';
                                        } elseif ($schedule->status === 'done') {
                                            $badgeClass = 'bg-primary bg-opacity-20';
                                            $badgeIcon = 'fas fa-check-double';
                                            $statusText = 'Done';
                                        } else {
                                            $badgeClass = 'bg-secondary bg-opacity-10';
                                            $badgeIcon = 'fas fa-archive';
                                            $statusText = ucfirst($schedule->status);
                                        }
                                    @endphp
                                    <span class="badge {{ $badgeClass }}" title="{{ $statusText }}">
                                        <i class="{{ $badgeIcon }} me-1"></i>
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-2 py-1">
                                    @php
                                        $createdDate = $schedule->created_at ?? now();
                                    @endphp
                                    <div title="{{ $createdDate->format('d F Y H:i') }}">
                                        {{ $createdDate->format('d/m/Y') }}
                                    </div>
                                    <small class="text-muted">{{ $createdDate->format('H:i') }}</small>
                                </td>
                                <td class="px-2 py-1 text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if (strtolower($schedule->status) !== 'done')
                                            <a href="{{ route('admin.pub_schedule.edit', $schedule->id) }}" 
                                               class="btn btn-outline-primary"
                                               title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @else
                                            <button type="button" 
                                                    class="btn btn-outline-secondary"
                                                    disabled
                                                    title="Cannot edit done schedule">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif
                                        
                                        <form action="{{ route('admin.pub_schedule.destroy', $schedule->id) }}" 
                                              method="POST" 
                                              class="d-inline delete-form"
                                              data-title="{{ $schedule->main_title }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-delete"
                                                    title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        
                                        @if ($schedule->status === 'draft')
                                            <form action="{{ route('admin.pub_schedule.publish', $schedule->id) }}" 
                                                  method="POST" 
                                                  class="d-inline publish-form"
                                                  data-title="{{ $schedule->main_title }}">
                                                @csrf
                                                <button type="button" 
                                                        class="btn btn-outline-success btn-publish"
                                                        title="Publish">
                                                    <i class="fas fa-paper-plane"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-calendar-alt"></i>
                                        <h6 class="text-muted">No Schedules Found</h6>
                                        <p class="text-muted small mb-3">Start by adding your first schedule</p>
                                        <a href="{{ route('admin.pub_schedule.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-1"></i> Add First Schedule
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
        @if($schedules->hasPages() || $schedules->total() > 0)
            <div class="card-footer">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <div class="mb-2 mb-md-0">
                        <p class="small text-muted mb-0">
                            Showing <span class="fw-semibold">{{ $schedules->firstItem() ?: 0 }}</span> to 
                            <span class="fw-semibold">{{ $schedules->lastItem() ?: 0 }}</span> of 
                            <span class="fw-semibold">{{ $schedules->total() }}</span> results
                        </p>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if($schedules->hasPages())
                            {{ $schedules->withQueryString()->onEachSide(1)->links('pagination::bootstrap-5') }}
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
                Select all items ({{ $schedules->total() }} total)
            </label>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn">
                <i class="fas fa-trash me-1"></i> Delete Selected
            </button>
        </div>
    </div>
</div>

{{-- Image Modal --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Schedule Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img
                    id="modalImage"
                    src=""
                    class="img-fluid rounded"
                    alt="Schedule Image"
                    style="max-height: 70vh;">
            </div>
        </div>
    </div>
</div>

{{-- Hidden Bulk Delete Form --}}
<form id="bulkDeleteForm" method="POST" action="{{ route('admin.pub_schedule.bulk-destroy') }}" style="display: none;">
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

        // ===== SHOW IMAGE =====
        window.showImage = function(src) {
            document.getElementById('modalImage').src = src;
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            imageModal.show();
        };

        // ===== DELETE CONFIRMATION - SEDERHANA =====
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const title = form.getAttribute('data-title') || 'this schedule';

                Swal.fire({
                    title: 'Delete Schedule?',
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
                        form.submit();
                    }
                });
            });
        });

        // ===== PUBLISH CONFIRMATION =====
        document.querySelectorAll('.btn-publish').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const title = form.getAttribute('data-title') || 'this schedule';

                Swal.fire({
                    title: 'Publish Schedule?',
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
                    html: `Are you sure you want to delete <strong>${selectedItems.length}</strong> selected schedule(s)?`,
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
    });
</script>
@endpush
@endsection