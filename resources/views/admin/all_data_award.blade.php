@extends('admin.layouts.app')
@section('title', 'Data Award - Administrator')

@section('content')
@php $activeTab = 'award'; @endphp
@include('partials.tabs', compact('activeTab'))

{{-- Include sweetalert --}}
@include('partials.sweetalert')

@push('styles')
<style>
    .page-header {
        margin-bottom: 25px;
    }

    .page-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
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

    .award-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
        margin-right: 8px;
    }

    .badge-warning {
        background-color: #fff3e0;
        color: #ef6c00;
        border: 1px solid #ffcc80;
    }

    .badge-info {
        background-color: #e3f2fd;
        color: #1565c0;
        border: 1px solid #bbdefb;
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

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 12px;
        }

        .data-card-body {
            padding: 10px;
        }

        .data-item {
            padding: 8px 6px;
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }

        .action-buttons {
            align-self: flex-end;
        }
    }
</style>
@endpush

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title mt-2">
            <i class="fas fa-trophy me-2"></i> Award Management
        </h1>
        <p class="page-subtitle">Manage award types and categories for HSBL competitions</p>
    </div>

    <!-- Form Tambah Award -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-plus-circle me-2"></i> Tambah Award Baru
        </div>
        <div class="card-body">
            <form action="{{ route('admin.award.store') }}" method="POST" id="addAwardForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="award_type" class="form-label">Jenis Award</label>
                            <input type="text"
                                name="award_type"
                                id="award_type"
                                class="form-control"
                                placeholder="Contoh: Medali Emas, Piala, Sertifikat"
                                value="{{ old('award_type') }}">
                            @error('award_type')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Masukkan jenis award (Medali, Piala, Sertifikat, dll)</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori Award</label>
                            <input type="text"
                                name="category"
                                id="category"
                                class="form-control"
                                placeholder="Contoh: Individu, Tim, Keseluruhan"
                                value="{{ old('category') }}">
                            @error('category')
                            <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Masukkan kategori award</small>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button type="reset" class="btn-secondary">
                        <i class="fas fa-redo me-2"></i> Reset
                    </button>
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <i class="fas fa-plus me-2"></i> Tambah Award
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- List Data Award -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-list me-2"></i> Daftar Award
                <span class="badge-count">{{ $awardTypes->count() + $awardCategories->count() }}</span>
            </div>
        </div>

        <div class="card-body">
            <div class="row g-4">
                <!-- Jenis Award -->
                <div class="col-md-6">
                    <div class="data-card h-100">
                        <div class="data-card-header">
                            <div>
                                <i class="fas fa-medal me-2 text-warning"></i>
                                Jenis Award
                            </div>
                            <span class="badge-count">{{ $awardTypes->count() }}</span>
                        </div>
                        <div class="data-card-body">
                            @if($awardTypes->count())
                            @foreach ($awardTypes as $type)
                            @if(!empty($type))
                            <div class="data-item">
                                <div class="d-flex align-items-center">
                                    <span class="award-badge badge-warning me-2">
                                        <i class="fas fa-medal me-1"></i>
                                    </span>
                                    <span class="data-text">{{ $type }}</span>
                                </div>
                                <div class="action-buttons">
                                    <button type="button"
                                        class="btn-action btn-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editAwardModal"
                                        onclick="setEditAwardData('award_type', '{{ addslashes($type) }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button type="button"
                                        class="btn-action btn-delete"
                                        data-field="award_type"
                                        data-value="{{ $type }}"
                                        onclick="deleteAwardItem('award_type', '{{ addslashes($type) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endif
                            @endforeach
                            @else
                            <div class="empty-state">
                                <i class="fas fa-medal"></i>
                                <p>Belum ada data jenis award.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Kategori Award -->
                <div class="col-md-6">
                    <div class="data-card h-100">
                        <div class="data-card-header">
                            <div>
                                <i class="fas fa-tags me-2 text-info"></i>
                                Kategori Award
                            </div>
                            <span class="badge-count">{{ $awardCategories->count() }}</span>
                        </div>
                        <div class="data-card-body">
                            @if($awardCategories->count())
                            @foreach ($awardCategories as $category)
                            @if(!empty($category))
                            <div class="data-item">
                                <div class="d-flex align-items-center">
                                    <span class="award-badge badge-info me-2">
                                        <i class="fas fa-tag me-1"></i>
                                    </span>
                                    <span class="data-text">{{ $category }}</span>
                                </div>
                                <div class="action-buttons">
                                    <button type="button"
                                        class="btn-action btn-edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editAwardModal"
                                        onclick="setEditAwardData('category', '{{ addslashes($category) }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button type="button"
                                        class="btn-action btn-delete"
                                        data-field="category"
                                        data-value="{{ $category }}"
                                        onclick="deleteAwardItem('category', '{{ addslashes($category) }}')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endif
                            @endforeach
                            @else
                            <div class="empty-state">
                                <i class="fas fa-tags"></i>
                                <p>Belum ada data kategori award.</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Award Modal -->
<div class="modal fade" id="editAwardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i> Edit Award
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <!-- PERBAIKAN: route('admin.data.edit') -->
                <form id="editAwardForm" method="POST" action="{{ route('admin.data.edit') }}">
                    @csrf
                    <input type="hidden" name="table" value="awards">
                    <input type="hidden" name="field" id="editAwardField">
                    <input type="hidden" name="original_value" id="editOriginalValue">

                    <div class="mb-3">
                        <label class="form-label" id="editFieldLabel">Nilai Baru</label>
                        <input type="text"
                            name="new_value"
                            id="editNewValue"
                            class="form-control"
                            placeholder="Enter new value"
                            required>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn-submit" id="saveEditBtn">
                            <i class="fas fa-save me-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Set data for edit award modal
    function setEditAwardData(field, originalValue) {
        try {
            originalValue = originalValue.replace(/\\/g, '');

            document.getElementById('editAwardField').value = field;
            document.getElementById('editOriginalValue').value = originalValue;
            document.getElementById('editNewValue').value = originalValue;

            const fieldLabel = field === 'award_type' ? 'Jenis Award' : 'Kategori Award';
            document.getElementById('editFieldLabel').textContent = `Edit ${fieldLabel}`;

            setTimeout(() => {
                document.getElementById('editNewValue').focus();
            }, 300);
        } catch (error) {
            console.error('Error setting award edit data:', error);
        }
    }

    // Function untuk delete award item
    function deleteAwardItem(field, value) {
        try {
            value = value.replace(/\\/g, '');
            const fieldLabel = field === 'award_type' ? 'jenis award' : 'kategori award';

            Swal.fire({
                title: 'Hapus Data Award?',
                html: `Apakah Anda yakin ingin menghapus <strong>"${value}"</strong> dari ${fieldLabel}?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create dynamic form untuk delete
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = "{{ route('admin.data.delete') }}";
                    
                    // Add CSRF token
                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';
                    form.appendChild(csrfToken);
                    
                    // Add table
                    const tableInput = document.createElement('input');
                    tableInput.type = 'hidden';
                    tableInput.name = 'table';
                    tableInput.value = 'awards';
                    form.appendChild(tableInput);
                    
                    // Add field
                    const fieldInput = document.createElement('input');
                    fieldInput.type = 'hidden';
                    fieldInput.name = 'field';
                    fieldInput.value = field;
                    form.appendChild(fieldInput);
                    
                    // Add value
                    const valueInput = document.createElement('input');
                    valueInput.type = 'hidden';
                    valueInput.name = 'value';
                    valueInput.value = value;
                    form.appendChild(valueInput);
                    
                    // Add to body
                    document.body.appendChild(form);
                    
                    // Show loading sebelum submit
                    Swal.fire({
                        title: 'Menghapus...',
                        text: 'Sedang menghapus data award',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Submit form
                    form.submit();
                }
            });
        } catch (error) {
            console.error('Error deleting award:', error);
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
        // Add Award Form
        const addAwardForm = document.getElementById('addAwardForm');
        const submitBtn = document.getElementById('submitBtn');

        if (addAwardForm && submitBtn) {
            addAwardForm.addEventListener('submit', function(e) {
                // Disable button dan show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';
                
                // Show loading toast
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang menyimpan data award',
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

        // Edit Award Form
        const editAwardForm = document.getElementById('editAwardForm');
        const saveEditBtn = document.getElementById('saveEditBtn');

        if (editAwardForm && saveEditBtn) {
            editAwardForm.addEventListener('submit', function(e) {
                const oldValue = document.getElementById('editOriginalValue').value;
                const newValue = document.getElementById('editNewValue').value.trim();

                if (!newValue) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Harap masukkan nilai baru',
                        timer: 3000,
                        showConfirmButton: false
                    });
                    return false;
                }

                if (oldValue === newValue) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'info',
                        title: 'Tidak Ada Perubahan',
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
                    text: 'Sedang mengupdate data award',
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

        // Auto focus on award_type input
        const awardTypeInput = document.getElementById('award_type');
        if (awardTypeInput) {
            awardTypeInput.focus();
        }
    });
</script>
@endpush

@endsection