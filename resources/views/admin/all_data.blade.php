@extends('admin.layouts.app')
@section('title', 'Master Data - Administrator')

@section('content')
@php $activeTab = 'data'; @endphp
@include('partials.tabs', compact('activeTab'))

{{-- Include sweetalert --}}
@include('partials.sweetalert')

@push('styles')
<style>
    /* ===== CARD STYLES YANG DISERAGAMKAN DENGAN DASHBOARD ===== */
    :root {
        --color-text-primary: #0f172a;
        --color-text-secondary: #64748b;
        --color-border: #f1f5f9;
    }

    .page-header {
        margin-bottom: 1.5rem;
    }

    .page-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 0.25rem;
        letter-spacing: -0.01em;
    }

    .page-subtitle {
        color: #64748b;
        font-size: 0.8rem;
    }

    /* Card style sama seperti dashboard */
    .card {
        border: none !important;
        border-radius: 10px !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
        margin-bottom: 1rem;
        transition: all 0.2s ease;
    }

    .card:hover {
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
    }

    .card-header {
        background-color: #f8fafc !important;
        border-bottom: 1px solid #f1f5f9 !important;
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        color: #1e293b;
        border-radius: 10px 10px 0 0 !important;
    }

    .card-body {
        padding: 1rem;
    }

    /* Form controls */
    .form-label {
        font-size: 0.75rem;
        font-weight: 500;
        color: #64748b;
        margin-bottom: 0.25rem;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }

    .form-control {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
        width: 100%;
        transition: all 0.2s;
        background-color: #fff;
    }

    .form-control:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        outline: none;
    }

    /* Button styles sama dashboard */
    .btn-submit {
        background-color: #2563eb;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        height: 38px;
    }

    .btn-submit:hover {
        background-color: #1d4ed8;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(37, 99, 235, 0.1);
    }

    .btn-submit:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .btn-secondary {
        background-color: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-secondary:hover {
        background-color: #e2e8f0;
        color: #334155;
    }

    /* Data Cards - Lebih compact */
    .data-card {
        border: none !important;
        border-radius: 10px !important;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05) !important;
        margin-bottom: 1rem;
        height: 100%;
        background: white;
    }

    .data-card-header {
        background-color: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        padding: 0.75rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        color: #1e293b;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-radius: 10px 10px 0 0;
    }

    .badge-count {
        background-color: #e2e8f0;
        color: #64748b;
        border-radius: 20px;
        padding: 0.15rem 0.6rem;
        font-size: 0.65rem;
        font-weight: 500;
    }

    .data-card-body {
        padding: 0.5rem;
        max-height: 300px;
        overflow-y: auto;
    }

    /* Scrollbar styling */
    .data-card-body::-webkit-scrollbar {
        width: 4px;
    }

    .data-card-body::-webkit-scrollbar-track {
        background: #f1f5f9;
    }

    .data-card-body::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .data-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0.75rem;
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.2s;
        font-size: 0.85rem;
    }

    .data-item:hover {
        background-color: #f8fafc;
    }

    .data-item:last-child {
        border-bottom: none;
    }

    .data-text {
        flex: 1;
        margin-right: 0.5rem;
        word-break: break-word;
        color: #0f172a;
    }

    .action-buttons {
        display: flex;
        gap: 0.25rem;
        flex-shrink: 0;
    }

    .btn-action {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 0.75rem;
    }

    .btn-edit {
        background-color: #eef2ff;
        color: #4f46e5;
        border-color: #c7d2fe;
    }

    .btn-edit:hover {
        background-color: #4f46e5;
        color: white;
        border-color: #4f46e5;
    }

    .btn-delete {
        background-color: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .btn-delete:hover {
        background-color: #dc2626;
        color: white;
        border-color: #dc2626;
    }

    .empty-state {
        text-align: center;
        padding: 1.5rem;
        color: #94a3b8;
        font-size: 0.8rem;
    }

    .empty-state i {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
        color: #cbd5e1;
    }

    /* Modal styles */
    .modal-content {
        border: none !important;
        border-radius: 12px !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1) !important;
    }

    .modal-header {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        border-radius: 12px 12px 0 0;
        padding: 1rem 1.25rem;
    }

    .modal-title {
        font-size: 0.95rem;
        font-weight: 500;
    }

    .btn-close-white {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    .modal-body {
        padding: 1.25rem;
    }

    /* Responsive - sama kayak dashboard */
    @media (max-width: 768px) {
        .container {
            padding-left: 0.5rem !important;
            padding-right: 0.5rem !important;
        }
        
        .card-body {
            padding: 0.75rem;
        }
        
        .data-item {
            padding: 0.5rem;
        }
        
        .btn-action {
            width: 32px;
            height: 32px;
        }
    }

    @media (max-width: 576px) {
        .data-item {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .action-buttons {
            width: 100%;
            justify-content: flex-end;
        }
        
        .page-title {
            font-size: 1.1rem;
        }
    }

    /* Utility */
    .gap-2 { gap: 0.5rem; }
    .text-secondary { color: #64748b !important; }
</style>
@endpush

<div class="container-fluid px-2 px-sm-3 px-md-4">
    <!-- Page Header -->
    <div class="page-header d-flex align-items-center gap-2 mb-3">
        <div class="bg-primary bg-opacity-10 p-1 rounded-2">
            <i class="fas fa-database text-primary" style="font-size: 0.9rem;"></i>
        </div>
        <div>
            <h1 class="page-title">Data Management</h1>
            <p class="page-subtitle mb-0">Manage seasons, series, competitions, and phases</p>
        </div>
    </div>

    <!-- Form Add Data -->
    <div class="card mb-3">
        <div class="card-header">
            <i class="fas fa-plus-circle me-2"></i> Tambah Data Baru
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.data.store') }}" id="addDataForm">
                @csrf

                <div class="row g-2 g-sm-3 mb-3">
                    <div class="col-12 col-md-3">
                        <label class="form-label">Season</label>
                        <input type="text" name="season_name" class="form-control"
                            placeholder="Season 2024" value="{{ old('season_name') }}">
                        @error('season_name')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Series</label>
                        <input type="text" name="series_name" class="form-control"
                            placeholder="Series 1" value="{{ old('series_name') }}">
                        @error('series_name')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Competition</label>
                        <input type="text" name="competition" class="form-control"
                            placeholder="HSBL Regional" value="{{ old('competition') }}">
                        @error('competition')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="form-label">Competition Type</label>
                        <input type="text" name="competition_type" class="form-control"
                            placeholder="Regional, National" value="{{ old('competition_type') }}">
                        @error('competition_type')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-2 g-sm-3">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Phase</label>
                        <input type="text" name="phase" class="form-control"
                            placeholder="Preliminary, Final" value="{{ old('phase') }}">
                        @error('phase')
                        <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-12 col-md-6">
                        <label class="form-label d-block d-md-none">&nbsp;</label>
                        <button type="submit" class="btn-submit w-100" id="submitBtn">
                            <i class="fas fa-save me-2"></i> Submit Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Lists Section - Grid Responsif -->
    <div class="row g-2 g-sm-3 mb-3">
        @foreach ([
        'season' => ['data' => $seasons, 'icon' => 'fas fa-calendar'],
        'series' => ['data' => $series, 'icon' => 'fas fa-layer-group'],
        'competition' => ['data' => $competitions, 'icon' => 'fas fa-trophy'],
        ] as $type => $item)
        <div class="col-12 col-md-4">
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
                        <span class="data-text">{{ Str::limit($value, 25) }}</span>
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
                        <p>No {{ $type }} data</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Row 2: 2 Cards -->
    <div class="row g-2 g-sm-3">
        @foreach ([
        'phase' => ['data' => $phases, 'icon' => 'fas fa-flag'],
        'competition_type' => ['data' => $competition_types ?? collect(), 'icon' => 'fas fa-tag'],
        ] as $type => $item)
        <div class="col-12 col-md-6">
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
                        <span class="data-text">{{ Str::limit($value, 30) }}</span>
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
                        <p>No {{ $type }} data</p>
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
                        <label class="form-label">New Value</label>
                        <input type="text"
                            name="new_value"
                            id="editNewValue"
                            class="form-control"
                            placeholder="Enter new value"
                            required>
                        <small class="text-muted small">Leave empty to set as NULL</small>
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

    // Confirm delete dengan SweetAlert
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

    // Handle form submissions
    document.addEventListener('DOMContentLoaded', function() {
        // Add Data Form
        const addDataForm = document.getElementById('addDataForm');
        const submitBtn = document.getElementById('submitBtn');

        if (addDataForm && submitBtn) {
            addDataForm.addEventListener('submit', function(e) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
                
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

                saveEditBtn.disabled = true;
                saveEditBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
                
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