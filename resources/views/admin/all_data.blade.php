@extends('admin.layouts.app')
@section('title', 'Master Data - Administrator')

@section('content')
@php $activeTab = 'data'; @endphp
@include('partials.tabs', compact('activeTab'))

{{-- Include sweetalert --}}
@include('partials.sweetalert')

@push('styles')
<style>
    .page-header {
        margin-bottom: 15px;
        margin-top: 5px;
    }

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

    .card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        padding: 12px 16px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #2c3e50;
    }

    .card-body {
        padding: 16px;
    }

    .form-label {
        font-size: 0.9rem;
        font-weight: 500;
        color: #2c3e50;
        margin-bottom: 6px;
        display: block;
    }

    .form-control {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding: 8px 12px;
        font-size: 0.9rem;
        width: 100%;
        transition: all 0.2s;
    }

    .form-control:focus {
        border-color: #3498db;
        box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        outline: none;
    }

    .btn-submit {
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-submit:hover {
        background-color: #2980b9;
    }

    .btn-secondary {
        background-color: #95a5a6;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-secondary:hover {
        background-color: #7f8c8d;
    }

    /* ===== DATA LIST CARDS ===== */
    .data-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
        height: 100%;
    }

    .data-card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e0e0e0;
        padding: 12px 16px;
        font-size: 0.95rem;
        font-weight: 600;
        color: #2c3e50;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .badge-count {
        background-color: #3498db;
        color: white;
        border-radius: 12px;
        padding: 2px 8px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .data-card-body {
        padding: 12px;
        max-height: 300px;
        overflow-y: auto;
    }

    .data-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 8px;
        border-bottom: 1px solid #f0f0f0;
        transition: background-color 0.2s;
    }

    .data-item:hover {
        background-color: #f8fafc;
    }

    .data-item:last-child {
        border-bottom: none;
    }

    .data-text {
        flex: 1;
        margin-right: 10px;
        word-break: break-word;
        font-size: 0.9rem;
    }

    .action-buttons {
        display: flex;
        gap: 5px;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.85rem;
    }

    .btn-edit {
        background-color: #e3f2fd;
        color: #1976d2;
        border-color: #bbdefb;
    }

    .btn-edit:hover {
        background-color: #bbdefb;
    }

    .btn-delete {
        background-color: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .btn-delete:hover {
        background-color: #fecaca;
    }

    .empty-state {
        text-align: center;
        padding: 30px;
        color: #95a5a6;
        font-size: 0.9rem;
    }

    .empty-state i {
        font-size: 2rem;
        margin-bottom: 10px;
        color: #bdc3c7;
    }

    /* Modal Styles */
    .modal-header {
        background-color: #3498db;
        color: white;
        border-radius: 8px 8px 0 0;
        padding: 12px 16px;
    }

    .modal-title {
        font-size: 1rem;
        font-weight: 600;
    }

    .btn-close-white {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    /* ===== RESPONSIVE FIX - FINAL VERSION ===== */
    @media (max-width: 768px) {

        /* Fix body overflow */
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

        /* Container */
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

        /* Force all columns to be full width */
        .row>[class*="col-"] {
            padding-left: 3px !important;
            padding-right: 3px !important;
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }

        /* Form inputs jadi full width */
        .row.g-3>[class*="col-"] {
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
            padding-left: 3px !important;
            padding-right: 3px !important;
        }

        /* Button submit di HP */
        .col-md-6.d-flex.align-items-end {
            margin-top: 10px;
            width: 100% !important;
            padding-left: 3px !important;
            padding-right: 3px !important;
        }

        /* Data Cards - Force 1 column */
        .col-md-4,
        .col-md-6 {
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
            padding-left: 3px !important;
            padding-right: 3px !important;
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

        .data-card {
            margin-bottom: 12px;
            width: 100% !important;
        }

        .data-card-header {
            padding: 8px 10px;
            font-size: 0.9rem;
        }

        .data-card-body {
            padding: 6px;
            max-height: 250px;
        }

        /* Data items - horizontal */
        .data-item {
            padding: 6px 4px;
            flex-direction: row;
            align-items: center;
            gap: 6px;
            width: 100%;
        }

        .data-text {
            font-size: 0.8rem;
            max-width: 65%;
            word-break: break-word;
        }

        .action-buttons {
            gap: 3px;
            align-self: auto;
        }

        .btn-action {
            width: 26px;
            height: 26px;
            font-size: 0.7rem;
        }

        .page-title {
            font-size: 1.1rem;
            padding-left: 3px;
        }

        .page-subtitle {
            font-size: 0.75rem;
            padding-left: 3px;
        }

        .badge-count {
            font-size: 0.65rem;
            padding: 1px 5px;
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

        .data-item {
            padding: 5px 2px;
        }

        .data-text {
            font-size: 0.75rem;
            max-width: 60%;
        }

        .btn-action {
            width: 24px;
            height: 24px;
            font-size: 0.65rem;
        }

        .page-title {
            font-size: 1rem;
        }

        .card-header {
            padding: 6px 8px;
        }

        .card-body {
            padding: 8px;
        }

        .form-label {
            font-size: 0.7rem;
            margin-bottom: 3px;
        }

        .form-control {
            padding: 5px 6px;
            font-size: 0.75rem;
        }

        .badge-count {
            font-size: 0.6rem;
            padding: 1px 4px;
        }
    }
</style>
@endpush

<div class="container" style="max-width: 100%; padding-left: 15px; padding-right: 15px;">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title mt-2">
            <i class="fas fa-database me-2"></i> Data Management
        </h1>
        <p class="page-subtitle">Manage seasons, series, competitions, and phases</p>
    </div>

    <!-- Form Add Data -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-plus-circle me-2"></i> Tambah Data Baru
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.data.store') }}" id="addDataForm">
                @csrf

                <div class="row g-3 mb-1">
                    <div class="col-md-3">
                        <label class="form-label">Season</label>
                        <input type="text" name="season_name" class="form-control"
                            placeholder="Contoh: Season 2024" value="{{ old('season_name') }}">
                        @error('season_name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Series</label>
                        <input type="text" name="series_name" class="form-control"
                            placeholder="Contoh: Series 1" value="{{ old('series_name') }}">
                        @error('series_name')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Competition</label>
                        <input type="text" name="competition" class="form-control"
                            placeholder="Contoh: SBL Regional" value="{{ old('competition') }}">
                        @error('competition')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Competition Type</label>
                        <input type="text" name="competition_type" class="form-control"
                            placeholder="Contoh: Regional, National" value="{{ old('competition_type') }}">
                        @error('competition_type')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Phase</label>
                        <input type="text" name="phase" class="form-control"
                            placeholder="Contoh: Preliminary, Final" value="{{ old('phase') }}">
                        @error('phase')
                        <div class="text-danger mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn-submit w-100" id="submitBtn">
                            <i class="fas fa-save me-2"></i> Submit Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Lists Section -->
    <div class="row g-3 mb-2">
        @foreach ([
        'season' => ['data' => $seasons, 'icon' => 'fas fa-calendar'],
        'series' => ['data' => $series, 'icon' => 'fas fa-layer-group'],
        'competition' => ['data' => $competitions, 'icon' => 'fas fa-trophy'],
        ] as $type => $item)
        <div class="col-md-4">
            <div class="data-card">
                <div class="data-card-header">
                    <div>
                        <i class="{{ $item['icon'] }} me-2"></i>
                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                    </div>
                    <span class="badge-count">{{ $item['data']->count() }}</span>
                </div>
                <div class="data-card-body">
                    @if($item['data']->count())
                    @foreach ($item['data'] as $value)
                    @if(!empty($value))
                    <div class="data-item">
                        <span class="data-text">{{ $value }}</span>
                        <div class="action-buttons">
                            <button type="button"
                                class="btn-action btn-edit"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal"
                                onclick="setEditData('{{ $type }}', '{{ addslashes($value) }}')">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button type="button"
                                class="btn-action btn-delete btn-delete-item"
                                data-item-name="{{ $value }}"
                                onclick="confirmDelete('{{ $type }}', '{{ addslashes($value) }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @else
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No {{ $type }} data available</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Row 2: 2 Cards -->
    <div class="row g-3">
        @foreach ([
        'phase' => ['data' => $phases, 'icon' => 'fas fa-flag'],
        'competition_type' => ['data' => $competition_types ?? collect(), 'icon' => 'fas fa-tag'],
        ] as $type => $item)
        <div class="col-md-6">
            <div class="data-card">
                <div class="data-card-header">
                    <div>
                        <i class="{{ $item['icon'] }} me-2"></i>
                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                    </div>
                    <span class="badge-count">{{ $item['data']->count() }}</span>
                </div>
                <div class="data-card-body">
                    @if($item['data']->count())
                    @foreach ($item['data'] as $value)
                    @if(!empty($value))
                    <div class="data-item">
                        <span class="data-text">{{ $value }}</span>
                        <div class="action-buttons">
                            <button type="button"
                                class="btn-action btn-edit"
                                data-bs-toggle="modal"
                                data-bs-target="#editModal"
                                onclick="setEditData('{{ $type }}', '{{ addslashes($value) }}')">
                                <i class="fas fa-edit"></i>
                            </button>

                            <button type="button"
                                class="btn-action btn-delete btn-delete-item"
                                data-item-name="{{ $value }}"
                                onclick="confirmDelete('{{ $type }}', '{{ addslashes($value) }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @else
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No {{ $type }} data available</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i> Edit Data
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="editForm" method="POST" action="{{ route('admin.data.edit') }}">
                    @csrf

                    <input type="hidden" name="table" value="add_data">
                    <input type="hidden" name="type" id="editType">
                    <input type="hidden" name="old_value" id="editOldValue">

                    <div class="mb-3">
                        <label class="form-label fw-medium">New Value</label>
                        <input type="text"
                            name="new_value"
                            id="editNewValue"
                            class="form-control"
                            placeholder="Enter new value"
                            required>
                        <small class="text-muted">Leave empty to set as NULL</small>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn-submit" id="saveEditBtn">
                            <i class="fas fa-save me-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Hidden Form -->
<form id="deleteForm" method="POST" action="{{ route('admin.data.delete') }}" style="display: none;">
    @csrf
    <input type="hidden" name="table" value="add_data">
    <input type="hidden" name="type" id="deleteType">
    <input type="hidden" name="selected[]" id="deleteValue">
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Set data for edit modal
    function setEditData(type, oldValue) {
        try {
            oldValue = oldValue.replace(/\\/g, '');

            document.getElementById('editType').value = type;
            document.getElementById('editOldValue').value = oldValue;
            document.getElementById('editNewValue').value = oldValue;

            setTimeout(() => {
                document.getElementById('editNewValue').focus();
            }, 300);
        } catch (error) {
            console.error('Error setting edit data:', error);
        }
    }

    // Confirm delete dengan SweetAlert (konsisten)
    function confirmDelete(type, value) {
        try {
            value = value.replace(/\\/g, '');
            const typeName = type.replace('_', ' ');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                html: `Anda akan menghapus <strong>"${value}"</strong> dari ${typeName}`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then(result => {
                if (result.isConfirmed) {
                    // Show loading popup
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Sedang menghapus data',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    document.getElementById('deleteType').value = type;
                    document.getElementById('deleteValue').value = value;

                    // Submit form
                    setTimeout(() => {
                        document.getElementById('deleteForm').submit();
                    }, 500);
                }
            });

        } catch (error) {
            console.error('Error confirming delete:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan: ' + error.message,
                timer: 3000,
                showConfirmButton: false
            });
        }
    }

    // Handle form submissions dengan loading
    document.addEventListener('DOMContentLoaded', function() {
        // Add Data Form
        const addDataForm = document.getElementById('addDataForm');
        const submitBtn = document.getElementById('submitBtn');

        if (addDataForm && submitBtn) {
            addDataForm.addEventListener('submit', function(e) {
                // Disable button dan show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';

                // Show loading toast
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang menyimpan data',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            });
        }

        // Edit Form
        const editForm = document.getElementById('editForm');
        const saveEditBtn = document.getElementById('saveEditBtn');

        if (editForm && saveEditBtn) {
            editForm.addEventListener('submit', function(e) {
                const oldValue = document.getElementById('editOldValue').value;
                const newValue = document.getElementById('editNewValue').value.trim();

                if (!newValue) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Silakan masukkan nilai baru atau kosongkan untuk NULL',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    return false;
                }

                if (oldValue === newValue) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'info',
                        title: 'Tidak ada perubahan',
                        text: 'Nilai baru sama dengan nilai lama',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    return false;
                }

                // Disable button dan show loading
                saveEditBtn.disabled = true;
                saveEditBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';

                // Show loading popup
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang mengupdate data',
                    allowOutsideClick: false,
                    showConfirmButton: false,
                    timer: 1500,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                return true;
            });
        }
    });
</script>
@endpush

@endsection