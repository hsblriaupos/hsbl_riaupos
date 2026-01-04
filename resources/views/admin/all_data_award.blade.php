@extends('admin.layouts.app')
@section('title', 'Data Award - Administrator')

@section('content')
@php $activeTab = 'award'; @endphp
@include('partials.tabs', compact('activeTab'))

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
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
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
        margin-right: 10px;
    }
    
    .btn-secondary:hover {
        background-color: #7f8c8d;
    }
    
    .table-container {
        overflow-x: auto;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }
    
    .data-table th {
        background-color: #f8f9fa;
        padding: 10px 12px;
        text-align: left;
        font-weight: 600;
        color: #2c3e50;
        border-bottom: 1px solid #e0e0e0;
    }
    
    .data-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #f0f0f0;
        vertical-align: middle;
    }
    
    .data-table tbody tr:hover {
        background-color: #f8fafc;
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
    
    .badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 500;
        display: inline-block;
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
    
    .badge-primary {
        background-color: #e8f5e9;
        color: #2e7d32;
        border: 1px solid #c8e6c9;
    }
    
    .list-item {
        padding: 10px 12px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: background-color 0.2s;
    }
    
    .list-item:hover {
        background-color: #f8fafc;
    }
    
    .list-item:last-child {
        border-bottom: none;
    }
    
    .list-content {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .action-icons {
        display: flex;
        gap: 8px;
        justify-content: center;
    }
    
    .action-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        font-size: 0.85rem;
    }
    
    .edit-icon {
        background-color: #e3f2fd;
        color: #1976d2;
        border: 1px solid #bbdefb;
    }
    
    .edit-icon:hover {
        background-color: #bbdefb;
    }
    
    .delete-icon {
        background-color: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    
    .delete-icon:hover {
        background-color: #fecaca;
    }
    
    /* Pagination */
    .pagination-info {
        font-size: 0.85rem;
        color: #7f8c8d;
        margin-right: 15px;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 12px;
        }
        
        .data-table th,
        .data-table td {
            padding: 8px 10px;
            font-size: 0.85rem;
        }
        
        .list-item {
            padding: 8px 10px;
        }
        
        .list-content {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
    }
</style>
@endpush

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title mt-2">
            <i class="fas fa-trophy me-2"></i> Data Award
        </h1>
        <p class="page-subtitle">Kelola jenis dan kategori award untuk kompetisi HSBL</p>
    </div>

    <!-- Form Tambah Award -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-plus-circle me-2"></i> Tambah Award Baru
        </div>
        <div class="card-body">
            <form action="{{ url('/admin/award') }}" method="POST" id="addAwardForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="award_type" class="form-label">Jenis Award <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="award_type" 
                                   id="award_type" 
                                   class="form-control" 
                                   placeholder="Contoh: Medali Emas, Piala, Sertifikat"
                                   required>
                            <small class="text-muted">Masukkan jenis award (Medali, Piala, Sertifikat, dll)</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori Award <span class="text-danger">*</span></label>
                            <input type="text" 
                                   name="category" 
                                   id="category" 
                                   class="form-control" 
                                   placeholder="Contoh: Individu, Tim, Keseluruhan"
                                   required>
                            <small class="text-muted">Masukkan kategori award</small>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button type="reset" class="btn-secondary">
                        <i class="fas fa-redo me-2"></i> Reset
                    </button>
                    <button type="submit" class="btn-submit">
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
                <span class="badge bg-primary ms-2">{{ $awardTypes->count() + $awardCategories->count() }}</span>
            </div>
        </div>
        
        <div class="card-body">
            <div class="row g-4">
                <!-- Jenis Award -->
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-medal me-2 text-warning"></i>
                                Jenis Award
                                <span class="badge badge-warning ms-2">{{ $awardTypes->count() }}</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($awardTypes->count())
                            <div>
                                @foreach ($awardTypes as $type)
                                @if($type)
                                <div class="list-item">
                                    <div class="list-content">
                                        <span class="badge badge-warning">
                                            <i class="fas fa-medal me-1"></i>
                                        </span>
                                        <span class="fw-medium">{{ $type }}</span>
                                    </div>
                                    <div class="action-icons">
                                        <button type="button" 
                                                class="action-icon edit-icon"
                                                title="Edit"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editAwardModal"
                                                data-type="award_type"
                                                data-value="{{ $type }}"
                                                onclick="setEditAwardData(this)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <form method="POST" action="{{ url('/admin/award/delete') }}" 
                                              class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="table" value="awards">
                                            <input type="hidden" name="field" value="award_type">
                                            <input type="hidden" name="value" value="{{ $type }}">
                                            <button type="button" 
                                                    class="action-icon delete-icon btn-delete"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
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
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-tags me-2 text-info"></i>
                                Kategori Award
                                <span class="badge badge-info ms-2">{{ $awardCategories->count() }}</span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($awardCategories->count())
                            <div>
                                @foreach ($awardCategories as $category)
                                @if($category)
                                <div class="list-item">
                                    <div class="list-content">
                                        <span class="badge badge-info">
                                            <i class="fas fa-tag me-1"></i>
                                        </span>
                                        <span class="fw-medium">{{ $category }}</span>
                                    </div>
                                    <div class="action-icons">
                                        <button type="button" 
                                                class="action-icon edit-icon"
                                                title="Edit"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editAwardModal"
                                                data-type="category"
                                                data-value="{{ $category }}"
                                                onclick="setEditAwardData(this)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        
                                        <form method="POST" action="{{ url('/admin/award/delete') }}" 
                                              class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="table" value="awards">
                                            <input type="hidden" name="field" value="category">
                                            <input type="hidden" name="value" value="{{ $category }}">
                                            <button type="button" 
                                                    class="action-icon delete-icon btn-delete"
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endif
                                @endforeach
                            </div>
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
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i> Edit Award
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editAwardForm" method="POST" action="{{ url('/admin/award/edit') }}">
                    @csrf
                    <input type="hidden" name="table" value="awards">
                    <input type="hidden" name="field" id="editAwardField">
                    <input type="hidden" name="original_value" id="editOriginalValue">
                    
                    <div class="mb-3">
                        <label class="form-label" id="editFieldLabel">Nilai Baru</label>
                        <input type="text" name="new_value" id="editNewValue" 
                               class="form-control" required>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn-submit">
                            <i class="fas fa-save me-2"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Set data for edit award modal
    function setEditAwardData(button) {
        const field = button.dataset.type;
        const value = button.dataset.value;
        
        document.getElementById('editAwardField').value = field;
        document.getElementById('editOriginalValue').value = value;
        document.getElementById('editNewValue').value = value;
        
        // Update label based on field type
        const fieldLabel = field === 'award_type' ? 'Jenis Award' : 'Kategori Award';
        document.getElementById('editFieldLabel').textContent = `Edit ${fieldLabel}`;
    }

    // Delete Confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const awardValue = form.querySelector('[name="value"]').value;
                const field = form.querySelector('[name="field"]').value;
                const fieldLabel = field === 'award_type' ? 'jenis award' : 'kategori award';
                
                Swal.fire({
                    title: 'Hapus Data Award?',
                    html: `Apakah Anda yakin ingin menghapus <strong>${awardValue}</strong> dari ${fieldLabel}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Menghapus...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        
                        form.submit();
                    }
                });
            });
        });
        
        // Form validation
        const addForm = document.getElementById('addAwardForm');
        const editForm = document.getElementById('editAwardForm');
        
        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                const awardType = this.querySelector('[name="award_type"]').value.trim();
                const category = this.querySelector('[name="category"]').value.trim();
                
                if (!awardType || !category) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Form Tidak Lengkap',
                        text: 'Harap lengkapi semua field yang wajib diisi',
                        icon: 'warning',
                        confirmButtonColor: '#3085d6',
                    });
                }
            });
        }
        
        if (editForm) {
            editForm.addEventListener('submit', function(e) {
                const newValue = this.querySelector('#editNewValue').value.trim();
                
                if (!newValue) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Nilai Kosong',
                        text: 'Harap masukkan nilai baru',
                        icon: 'warning',
                        confirmButtonColor: '#3085d6',
                    });
                }
            });
        }
        
        
    });
</script>
@endpush
@endsection