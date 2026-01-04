@extends('admin.layouts.app')
@section('title', 'Master Data - Administrator')

@section('content')
@php $activeTab = 'data'; @endphp
@include('partials.tabs', compact('activeTab'))

@push('styles')
<link href="{{ asset('css/data.css') }}" rel="stylesheet" />
@endpush

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-dark mb-1">Master Data Management</h2>
            <p class="text-muted small">Manage seasons, series, competitions, and phases</p>
        </div>
    </div>

    {{-- Add Data Card --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="fw-semibold mb-0 fs-5">
                <i class="fas fa-plus-circle me-2 text-primary fs-6"></i> Add New Data
            </h5>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.data.store') }}">
                @csrf
                
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <label class="form-label fw-medium small">Season</label>
                        <input type="text" name="season_name" class="form-control"
                            placeholder="e.g., Season 2024">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium small">Series</label>
                        <input type="text" name="series_name" class="form-control"
                            placeholder="e.g., Series 1">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium small">Competition</label>
                        <input type="text" name="competition" class="form-control"
                            placeholder="e.g., HSBL Regional">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-medium small">Competition Type</label>
                        <input type="text" name="competition_type" class="form-control"
                            placeholder="e.g., Regional, National">
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-medium small">Phase</label>
                        <input type="text" name="phase" class="form-control"
                            placeholder="e.g., Preliminary, Final">
                    </div>
                    <div class="col-md-6 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary px-4 w-100">
                            <i class="fas fa-save me-2"></i> Submit Data
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- List Data Section --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom py-3">
            <h5 class="fw-semibold mb-0 fs-5">
                <i class="fas fa-list me-2 text-primary fs-6"></i> Data Lists
            </h5>
        </div>
        <div class="card-body">
            {{-- Row 1: 3 Cards --}}
            <div class="row g-4 mb-4">
                @foreach ([
                    'season' => $seasons,
                    'series' => $series,
                    'competition' => $competitions,
                ] as $type => $collection)
                <div class="col-md-4">
                    <div class="card h-100 border">
                        <div class="card-header bg-light py-3">
                            <h6 class="fw-bold mb-0 text-dark fs-6">
                                <i class="fas fa-folder me-2 text-info fs-6"></i>
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            @if($collection->count())
                            <div class="list-group list-group-flush">
                                @foreach ($collection as $value)
                                <div class="list-group-item border-0 px-2 py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-truncate me-2 fs-6">{{ $value }}</span>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="openEditModal('{{ $type }}', '{{ $value }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.data.delete') }}" class="d-inline delete-form">
                                                @csrf
                                                <input type="hidden" name="table" value="add_data">
                                                <input type="hidden" name="type" value="{{ $type }}">
                                                <input type="hidden" name="selected[]" value="{{ $value }}">
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-inbox text-muted mb-3 fs-5"></i>
                                <p class="text-muted mb-0 small">No {{ $type }} data available</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Row 2: 2 Cards (same width as row 1) --}}
            <div class="row g-4">
                @foreach ([
                    'competition_type' => $competition_types ?? collect(),
                    'phase' => $phases,
                ] as $type => $collection)
                <div class="col-md-6">
                    <div class="card h-100 border">
                        <div class="card-header bg-light py-3">
                            <h6 class="fw-bold mb-0 text-dark fs-6">
                                <i class="fas fa-folder me-2 text-info fs-6"></i>
                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                            </h6>
                        </div>
                        <div class="card-body p-3">
                            @if($collection->count())
                            <div class="list-group list-group-flush">
                                @foreach ($collection as $value)
                                <div class="list-group-item border-0 px-2 py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-truncate me-2 fs-6">{{ $value }}</span>
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" class="btn btn-outline-primary btn-sm"
                                                onclick="openEditModal('{{ $type }}', '{{ $value }}')">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <form method="POST" action="{{ route('admin.data.delete') }}" class="d-inline delete-form">
                                                @csrf
                                                <input type="hidden" name="table" value="add_data">
                                                <input type="hidden" name="type" value="{{ $type }}">
                                                <input type="hidden" name="selected[]" value="{{ $value }}">
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-inbox text-muted mb-3 fs-5"></i>
                                <p class="text-muted mb-0 small">No {{ $type }} data available</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fs-5">
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
                        <button type="submit" class="btn btn-primary px-4">
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
@include('partials.sweetalert')

<script>
    // Edit Modal Functions
    function openEditModal(type, oldValue) {
        document.getElementById('editType').value = type;
        document.getElementById('editOldValue').value = oldValue;
        document.getElementById('editNewValue').value = oldValue;
        
        // Bootstrap 5 modal show
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
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
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