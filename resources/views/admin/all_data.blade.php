@extends('admin.layouts.app')
@section('title', 'Master Data - Administrator')

@section('content')
@php $activeTab = 'data'; @endphp
@include('partials.tabs', compact('activeTab'))

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
    
    /* ===== DATA LIST CARDS ===== */
    .data-card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
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
    }
    
    .data-card-body {
        padding: 12px;
        max-height: 300px;
        overflow-y: auto;
    }
    
    .list-group-item {
        border: none;
        border-bottom: 1px solid #f0f0f0;
        padding: 10px 8px;
        font-size: 0.9rem;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
    
    .data-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .data-text {
        flex: 1;
        margin-right: 10px;
        word-break: break-word;
    }
    
    .btn-group-sm .btn {
        padding: 4px 8px;
        font-size: 0.8rem;
        border-radius: 4px;
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
        
        .list-group-item {
            padding: 8px 6px;
        }
    }
</style>
@endpush

<div class="container">
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
            <form method="POST" action="{{ route('admin.data.store') }}">
                @csrf
                
                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label class="form-label">Season</label>
                        <input type="text" name="season_name" class="form-control"
                            placeholder="Contoh: Season 2024" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Series</label>
                        <input type="text" name="series_name" class="form-control"
                            placeholder="Contoh: Series 1" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Competition</label>
                        <input type="text" name="competition" class="form-control"
                            placeholder="Contoh: HSBL Regional" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Competition Type</label>
                        <input type="text" name="competition_type" class="form-control"
                            placeholder="Contoh: Regional, National" required>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Phase</label>
                        <input type="text" name="phase" class="form-control"
                            placeholder="Contoh: Preliminary, Final" required>
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn-submit w-100">
                            <i class="fas fa-save me-2"></i> Submit Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Data Lists Section -->
    <div class="row g-4 mb-4">
        @foreach ([
            'season' => $seasons,
            'series' => $series,
            'competition' => $competitions,
        ] as $type => $collection)
        <div class="col-md-4">
            <div class="data-card">
                <div class="data-card-header">
                    <i class="fas fa-folder me-2"></i>
                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                </div>
                <div class="data-card-body">
                    @if($collection->count())
                    <div class="list-group">
                        @foreach ($collection as $value)
                        <div class="list-group-item">
                            <div class="data-item">
                                <span class="data-text">{{ $value }}</span>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary"
                                        onclick="openEditModal('{{ $type }}', '{{ $value }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.data.delete') }}" class="d-inline delete-form">
                                        @csrf
                                        <input type="hidden" name="table" value="add_data">
                                        <input type="hidden" name="type" value="{{ $type }}">
                                        <input type="hidden" name="selected[]" value="{{ $value }}">
                                        <button type="button" class="btn btn-outline-danger btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
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
    <div class="row g-4">
        @foreach ([
            'competition_type' => $competition_types ?? collect(),
            'phase' => $phases,
        ] as $type => $collection)
        <div class="col-md-6">
            <div class="data-card">
                <div class="data-card-header">
                    <i class="fas fa-folder me-2"></i>
                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                </div>
                <div class="data-card-body">
                    @if($collection->count())
                    <div class="list-group">
                        @foreach ($collection as $value)
                        <div class="list-group-item">
                            <div class="data-item">
                                <span class="data-text">{{ $value }}</span>
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-primary"
                                        onclick="openEditModal('{{ $type }}', '{{ $value }}')">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form method="POST" action="{{ route('admin.data.delete') }}" class="d-inline delete-form">
                                        @csrf
                                        <input type="hidden" name="table" value="add_data">
                                        <input type="hidden" name="type" value="{{ $type }}">
                                        <input type="hidden" name="selected[]" value="{{ $value }}">
                                        <button type="button" class="btn btn-outline-danger btn-delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
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

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-edit me-2"></i> Edit Data
                </h5>
                <button type="button" class="btn-close btn-close-white" onclick="closeEditModal()"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="{{ route('admin.data.edit') }}">
                    @csrf
                    <input type="hidden" name="table" value="add_data">
                    <input type="hidden" name="type" id="editType">
                    <input type="hidden" name="old_value" id="editOldValue">
                    
                    <div class="mb-3">
                        <label class="form-label fw-medium">New Value</label>
                        <input type="text" name="new_value" id="editNewValue" 
                            class="form-control" required>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button type="button" class="btn btn-secondary px-4" onclick="closeEditModal()">
                            Cancel
                        </button>
                        <button type="submit" class="btn-submit px-4">
                            <i class="fas fa-save me-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Edit Modal Functions
    function openEditModal(type, oldValue) {
        document.getElementById('editType').value = type;
        document.getElementById('editOldValue').value = oldValue;
        document.getElementById('editNewValue').value = oldValue;
        
        var editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
    }

    function closeEditModal() {
        var editModal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
        editModal.hide();
    }

    // Delete Confirmation
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const form = this.closest('form');
                const dataText = this.closest('.list-group-item').querySelector('.data-text').textContent;
                const type = this.closest('.data-card').querySelector('.data-card-header').textContent.trim();
                
                Swal.fire({
                    title: 'Hapus Data?',
                    html: `Apakah Anda yakin ingin menghapus <strong>${dataText}</strong> dari ${type}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush

@endsection