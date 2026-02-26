@extends('admin.layouts.app')
@section('title', 'Data Kota - Administrator')

@section('content')
@php $activeTab = 'city'; @endphp
@include('partials.tabs', compact('activeTab'))

{{-- Include sweetalert --}}
@include('partials.sweetalert')

@push('styles')
<style>
    .page-header {
        margin-bottom: 15px;
        margin-top: 0;
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

    /* ===== CARD STYLING ===== */
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

    /* ===== FORM ELEMENTS ===== */
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

    /* ===== BUTTONS ===== */
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

    /* ===== TABLE STYLING ===== */
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

    /* ===== ACTION BUTTONS - SAMA DENGAN MASTER DATA ===== */
    .action-icons {
        display: flex;
        gap: 5px;
        justify-content: center;
    }

    .action-icon {
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

    .edit-icon {
        background-color: #e3f2fd;
        color: #1976d2;
        border-color: #bbdefb;
    }

    .edit-icon:hover {
        background-color: #bbdefb;
    }

    .delete-icon {
        background-color: #fef2f2;
        color: #dc2626;
        border-color: #fecaca;
    }

    .delete-icon:hover {
        background-color: #fecaca;
    }

    /* ===== MODAL STYLES ===== */
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

    /* ===== RESPONSIVE FIX - SAMA DENGAN MASTER DATA ===== */
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

        /* Button submit di HP */
        .text-end {
            width: 100% !important;
            padding-left: 3px !important;
            padding-right: 3px !important;
        }

        .btn-submit {
            width: 100% !important;
            margin-top: 10px;
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

        .card-header {
            padding: 8px 10px;
            font-size: 0.9rem;
        }

        /* Table styling */
        .table-container {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .data-table {
            font-size: 0.8rem;
            min-width: 100%;
        }

        .data-table th,
        .data-table td {
            padding: 6px 4px;
            white-space: nowrap;
            font-size: 0.8rem;
        }

        .action-icons {
            gap: 3px;
        }

        .action-icon {
            width: 26px;
            height: 26px;
            font-size: 0.7rem;
        }

        .page-title {
            font-size: 1.1rem; /* SAMA DENGAN MASTER DATA */
            padding-left: 3px;
        }

        .page-subtitle {
            font-size: 0.8rem; /* SAMA DENGAN MASTER DATA */
            padding-left: 3px;
        }

        .badge {
            font-size: 0.7rem;
            padding: 2px 5px;
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

        .data-table {
            font-size: 0.75rem;
        }

        .data-table th,
        .data-table td {
            padding: 5px 3px;
            font-size: 0.75rem;
        }

        .action-icon {
            width: 24px;
            height: 24px;
            font-size: 0.65rem;
        }

        .page-title {
            font-size: 1rem; /* SAMA DENGAN MASTER DATA */
        }

        .card-header {
            padding: 6px 8px;
        }

        .card-body {
            padding: 8px;
        }

        .form-label {
            font-size: 0.7rem; /* SAMA DENGAN MASTER DATA */
            margin-bottom: 3px;
        }

        .form-control {
            padding: 5px 6px;
            font-size: 0.75rem; /* SAMA DENGAN MASTER DATA */
        }

        .badge {
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
            <i class="fas fa-city me-2"></i> City Management
        </h1>
        <p class="page-subtitle">Manage the list of cities for the SBL competitions</p>
    </div>

    <!-- Form Tambah Kota -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-plus-circle me-2"></i> Tambah Kota Baru
        </div>
        <div class="card-body">
            <form action="{{ route('admin.city.store') }}" method="POST" id="cityForm">
                @csrf
                <div class="mb-3">
                    <label for="city_name" class="form-label">Nama Kota</label>
                    <input type="text"
                        name="city_name"
                        id="city_name"
                        class="form-control"
                        placeholder="Contoh: Pekanbaru"
                        required
                        value="{{ old('city_name') }}">
                    @error('city_name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
                <div class="text-end">
                    <button type="submit" class="btn-submit" id="submitBtn">
                        <i class="fas fa-plus me-2"></i> Tambah Kota
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Kota -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-list me-2"></i> Daftar Kota
            <span class="badge bg-primary rounded-pill ms-2">{{ $cities->count() }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th>Nama Kota</th>
                            <th style="width: 100px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cities as $index => $city)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $city->city_name }}</td>
                            <td class="text-center">
                                <div class="action-icons">
                                    {{-- Edit Button --}}
                                    <button type="button"
                                        class="action-icon edit-icon"
                                        title="Edit"
                                        onclick="editCity('{{ $city->id }}', '{{ $city->city_name }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    {{-- Delete Form --}}
                                    <form method="POST"
                                        action="{{ route('admin.data.delete') }}"
                                        class="d-inline delete-form">
                                        @csrf
                                        <input type="hidden" name="table" value="cities">
                                        <input type="hidden" name="id" value="{{ $city->id }}">
                                        <button type="submit"
                                            class="action-icon delete-icon btn-delete"
                                            title="Hapus"
                                            data-item-name="{{ $city->city_name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3">
                                <div class="empty-state">
                                    <i class="fas fa-inbox"></i>
                                    <p>Belum ada data kota.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editCityModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i> Edit Kota
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="editCityForm" method="POST" action="{{ route('admin.data.edit') }}">
                    @csrf
                    <input type="hidden" name="table" value="cities">
                    <input type="hidden" name="id" id="editCityId">

                    <div class="mb-3">
                        <label class="form-label fw-medium">Nama Kota Baru</label>
                        <input type="text"
                            name="new_value"
                            id="editCityName"
                            class="form-control"
                            placeholder="Masukkan nama kota"
                            required>
                    </div>

                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="submit" class="btn-submit" id="saveEditBtn">
                            <i class="fas fa-save me-2"></i> Simpan Perubahan
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
    // Function to edit city
    function editCity(id, name) {
        document.getElementById('editCityId').value = id;
        document.getElementById('editCityName').value = name;

        const modal = new bootstrap.Modal(document.getElementById('editCityModal'));
        modal.show();

        // Focus on input after modal shows
        setTimeout(() => {
            document.getElementById('editCityName').focus();
            document.getElementById('editCityName').select();
        }, 300);
    }

    // Handle form submissions dengan loading
    document.addEventListener('DOMContentLoaded', function() {
        // City Form
        const cityForm = document.getElementById('cityForm');
        const submitBtn = document.getElementById('submitBtn');

        if (cityForm && submitBtn) {
            cityForm.addEventListener('submit', function(e) {
                // Disable button dan show loading
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Menyimpan...';

                // Show loading toast
                Swal.fire({
                    title: 'Menyimpan...',
                    text: 'Sedang menyimpan data kota',
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

        // Delete confirmation with SweetAlert
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const itemName = this.getAttribute('data-item-name') || 'item ini';

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    html: `Anda akan menghapus kota <strong>"${itemName}"</strong>`,
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
                        setTimeout(() => {
                            form.submit();
                        }, 500);
                    }
                });
            });
        });

        // Edit Form
        const editForm = document.getElementById('editCityForm');
        const saveEditBtn = document.getElementById('saveEditBtn');

        if (editForm && saveEditBtn) {
            editForm.addEventListener('submit', function(e) {
                const newName = document.getElementById('editCityName').value.trim();

                if (!newName) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: 'Nama kota tidak boleh kosong!',
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
                    text: 'Sedang mengupdate data kota',
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