@extends('admin.layouts.app')
@section('title', 'Data Venue - Administrator')

@section('content')
@php $activeTab = 'venue'; @endphp
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

    .badge-secondary {
        background-color: #f8f9fa;
        color: #6c757d;
        border: 1px solid #dee2e6;
    }

    .img-thumbnail {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 5px;
        border: 1px solid #e0e0e0;
        cursor: pointer;
        transition: all 0.2s;
    }

    .img-thumbnail:hover {
        transform: scale(1.8);
        border-color: #3498db;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        z-index: 10;
        position: relative;
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

    /* Filter Section */
    .filter-section {
        background-color: #f8fafc;
        padding: 12px 16px;
        border-bottom: 1px solid #e0e0e0;
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        align-items: center;
    }

    .filter-select {
        min-width: 150px;
        max-width: 200px;
    }

    .search-box {
        flex-grow: 1;
        min-width: 200px;
        max-width: 300px;
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

        .filter-form {
            flex-direction: column;
            align-items: stretch;
        }

        .filter-select,
        .search-box {
            max-width: 100%;
        }

        .img-thumbnail {
            width: 50px;
            height: 50px;
        }
    }
</style>
@endpush

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title mt-2">
            <i class="fas fa-map-marker-alt me-2"></i> Venue Management
        </h1>
        <p class="page-subtitle">Manage the list of venues for HSBL competitions</p>
    </div>

    <!-- Form Tambah Venue -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-plus-circle me-2"></i> Tambah Venue Baru
        </div>
        <div class="card-body">
            <form action="{{ url('/admin/venue') }}" method="POST" enctype="multipart/form-data" id="addVenueForm">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="venue_name" class="form-label">Nama Venue <span class="text-danger">*</span></label>
                            <input type="text"
                                name="venue_name"
                                id="venue_name"
                                class="form-control"
                                placeholder="Contoh: GOR UNILAK"
                                required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="city_id" class="form-label">Kota <span class="text-danger">*</span></label>
                            <select name="city_id" id="city_id" class="form-select" required>
                                <option value="">-- Pilih Kota --</option>
                                @foreach($cities as $city)
                                <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="location" class="form-label">Lokasi <span class="text-danger">*</span></label>
                            <input type="text"
                                name="location"
                                id="location"
                                class="form-control"
                                placeholder="Contoh: Jl. Riau No. 123"
                                required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="layout" class="form-label">Layout/Gambar</label>
                            <input type="file"
                                name="layout"
                                id="layout"
                                class="form-control"
                                accept="image/*">
                            <small class="text-muted">Opsional: Upload layout venue (JPG, PNG, GIF)</small>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button type="reset" class="btn-secondary">
                        <i class="fas fa-redo me-2"></i> Reset
                    </button>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-plus me-2"></i> Tambah Venue
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-filter me-2"></i> Filter & Pencarian
        </div>
        <div class="filter-section">
            <form method="GET" action="{{ url('/admin/venue') }}" class="filter-form">
                <select name="city_id" class="form-control filter-select" onchange="this.form.submit()">
                    <option value="">Semua Kota</option>
                    @foreach($cities as $city)
                    <option value="{{ $city->id }}" {{ request('city_id') == $city->id ? 'selected' : '' }}>
                        {{ $city->city_name }}
                    </option>
                    @endforeach
                </select>

                <div class="input-group search-box">
                    <input type="text"
                        name="search"
                        class="form-control"
                        placeholder="Cari venue..."
                        value="{{ request('search') }}">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <span class="pagination-info">
                        Menampilkan {{ $venues->firstItem() ?? 0 }}-{{ $venues->lastItem() ?? 0 }} dari {{ $venues->total() }}
                    </span>
                    <select name="per_page" class="form-control" style="width: auto;" onchange="this.form.submit()">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabel Data Venue -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-list me-2"></i> Daftar Venue
                <span class="badge bg-primary ms-2">{{ $venues->total() }}</span>
            </div>
            <div>
                <a href="{{ url('/admin/venue') }}" class="btn-secondary btn-sm">
                    <i class="fas fa-redo me-1"></i> Reset Filter
                </a>
                <a href="{{ url('/admin/export/venue') }}" class="btn-submit btn-sm">
                    <i class="fas fa-file-export me-1"></i> Export
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">No.</th>
                            <th>Nama Venue</th>
                            <th>Kota</th>
                            <th>Lokasi</th>
                            <th style="width: 80px;">Layout</th>
                            <th style="width: 100px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($venues as $index => $venue)
                        <tr>
                            <td class="text-center">{{ $venues->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    <div>
                                        <div class="fw-medium">{{ $venue->venue_name }}</div>
                                        <small class="text-muted">ID: {{ $venue->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <i class="fas fa-city text-info me-1"></i>
                                {{ $venue->city->city_name ?? 'N/A' }}
                            </td>
                            <td>
                                <i class="fas fa-location-dot text-danger me-1"></i>
                                {{ $venue->location ?? '-' }}
                            </td>
                            <td class="text-center">
                                @if($venue->layout)
                                <img src="{{ asset('storage/' . $venue->layout) }}"
                                    alt="Layout"
                                    class="img-thumbnail"
                                    data-bs-toggle="modal"
                                    data-bs-target="#imageModal"
                                    onclick="showImage('{{ asset('storage/' . $venue->layout) }}')">
                                @else
                                <span class="badge badge-secondary">
                                    <i class="fas fa-image-slash me-1"></i> -
                                </span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="action-icons">
                                    <button type="button"
                                        class="action-icon edit-icon"
                                        title="Edit"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editVenueModal"
                                        data-id="{{ $venue->id }}"
                                        data-name="{{ $venue->venue_name }}"
                                        data-city="{{ $venue->city_id }}"
                                        data-location="{{ $venue->location }}"
                                        onclick="setEditVenueData(this)">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <form method="POST" action="{{ url('/admin/venue/delete') }}"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="table" value="venue">
                                        <input type="hidden" name="id" value="{{ $venue->id }}">
                                        <button type="button"
                                            class="action-icon delete-icon btn-delete"
                                            title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <p>Belum ada data venue.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($venues->hasPages())
            <div class="p-3 border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="pagination-info">
                        Menampilkan {{ $venues->firstItem() }} sampai {{ $venues->lastItem() }} dari {{ $venues->total() }} data
                    </div>
                    <div>
                        {{ $venues->onEachSide(1)->links('pagination::simple-bootstrap-4') }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-image me-2"></i> Layout Venue
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modalImage" src="" alt="Layout Venue" class="img-fluid rounded">
            </div>
        </div>
    </div>
</div>

<!-- Edit Venue Modal -->
<div class="modal fade" id="editVenueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i> Edit Venue
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editVenueForm" method="POST" action="{{ url('/admin/venue/edit') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="table" value="venue">
                    <input type="hidden" name="id" id="editVenueId">

                    <div class="mb-3">
                        <label class="form-label">Nama Venue <span class="text-danger">*</span></label>
                        <input type="text" name="venue_name" id="editVenueName"
                            class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kota <span class="text-danger">*</span></label>
                        <select name="city_id" id="editVenueCity" class="form-select" required>
                            <option value="">-- Pilih Kota --</option>
                            @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->city_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi <span class="text-danger">*</span></label>
                        <input type="text" name="location" id="editVenueLocation"
                            class="form-control" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Layout/Gambar Baru</label>
                        <input type="file" name="layout" id="editVenueLayout" class="form-control" accept="image/*">
                        <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar</small>
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
    // Set data for edit venue modal
    function setEditVenueData(button) {
        document.getElementById('editVenueId').value = button.dataset.id;
        document.getElementById('editVenueName').value = button.dataset.name;
        document.getElementById('editVenueCity').value = button.dataset.city;
        document.getElementById('editVenueLocation').value = button.dataset.location;
    }

    // Show image in modal
    function showImage(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
    }

    // Delete Confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const venueName = form.closest('tr').querySelector('.fw-medium').textContent;

                Swal.fire({
                    title: 'Hapus Data Venue?',
                    html: `Apakah Anda yakin ingin menghapus venue <strong>${venueName}</strong>?`,
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
        const addForm = document.getElementById('addVenueForm');
        const editForm = document.getElementById('editVenueForm');

        if (addForm) {
            addForm.addEventListener('submit', function(e) {
                const venueName = this.querySelector('[name="venue_name"]').value.trim();
                const cityId = this.querySelector('[name="city_id"]').value;
                const location = this.querySelector('[name="location"]').value.trim();

                if (!venueName || !cityId || !location) {
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
                const venueName = this.querySelector('[name="venue_name"]').value.trim();
                const cityId = this.querySelector('[name="city_id"]').value;
                const location = this.querySelector('[name="location"]').value.trim();

                if (!venueName || !cityId || !location) {
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


    });
</script>
@endpush
@endsection