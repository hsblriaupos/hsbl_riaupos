@extends('admin.layouts.app')
@section('title', 'Data Kota - Administrator')

@section('content')
@php $activeTab = 'city'; @endphp
@include('partials.tabs', compact('activeTab'))

{{-- Include sweetalert partial untuk handle delete confirmation --}}
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
    
    .delete-icon {
        background-color: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    
    .delete-icon:hover {
        background-color: #fecaca;
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
    }
</style>
@endpush

<div class="container">
    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title mt-2">
            <i class="fas fa-city me-2"></i> Data Kota
        </h1>
        <p class="page-subtitle">Kelola daftar kota untuk kompetisi HSBL</p>
    </div>

    <!-- Form Tambah Kota -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-plus-circle me-2"></i> Tambah Kota Baru
        </div>
        <div class="card-body">
            <form action="{{ route('admin.city.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="city_name" class="form-label">Nama Kota</label>
                    <input type="text" 
                           name="city_name" 
                           id="city_name" 
                           class="form-control" 
                           placeholder="Contoh: Pekanbaru"
                           required>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn-submit">
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
                                    <form method="POST" action="{{ route('admin.data.delete') }}" 
                                          class="delete-form">
                                        @csrf
                                        <input type="hidden" name="table" value="cities">
                                        <input type="hidden" name="field" value="id">
                                        <input type="hidden" name="value" value="{{ $city->id }}">
                                        <button type="submit" 
                                                class="action-icon delete-icon btn-delete"
                                                title="Hapus"
                                                onclick="return confirm('Yakin ingin menghapus kota {{ $city->city_name }}?')">
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

@push('scripts')
<script>
    // Auto focus on input when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const cityInput = document.getElementById('city_name');
        if (cityInput) {
            cityInput.focus();
        }
    });
    
    // Custom delete confirmation untuk kota (override dari partials/sweetalert)
    document.addEventListener('DOMContentLoaded', function() {
        // Hanya override jika ada form delete untuk kota
        const cityDeleteForms = document.querySelectorAll('.delete-form');
        
        cityDeleteForms.forEach(form => {
            const deleteButton = form.querySelector('.btn-delete');
            
            // Remove default onclick
            deleteButton.removeAttribute('onclick');
            
            // Add custom SweetAlert
            deleteButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Get city name from the table row
                const cityName = this.closest('tr').querySelector('td:nth-child(2)').textContent;
                
                Swal.fire({
                    title: 'Hapus Data Kota?',
                    html: `Apakah Anda yakin ingin menghapus kota <strong>${cityName}</strong>?`,
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