@extends('admin.layouts.app')
@section('title', 'Detail Dancer - Administrator')

@section('content')
@include('partials.sweetalert')

@push('styles')
<style>
    /* Reuse same styles, but change colors to purple */
    .card-header {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%) !important;
    }
    
    .btn-detail {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
    }
    
    .btn-detail:hover {
        background: linear-gradient(135deg, #7c3aed 0%, #6d28d9 100%);
        box-shadow: 0 3px 8px rgba(139, 92, 246, 0.25);
    }
</style>
@endpush

<div class="container-fluid py-4">
    <!-- Back Button -->
    <a href="{{ route('admin.team-list.show', $team->team_id) }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        Kembali ke Pilihan Kategori
    </a>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Detail Tim Dancer</h1>
        <p class="page-subtitle">Informasi lengkap tim Dancer <strong>{{ $team->school_name }}</strong></p>
    </div>

    <!-- Team Information Card -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-info-circle"></i>
            <span>Informasi Tim Dancer</span>
        </div>
        <div class="card-body">
            <!-- Same structure as basket putra, but for dancer -->
            <!-- ... content similar to basket putra ... -->
        </div>
    </div>

    <!-- Dancer List Card -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-music"></i>
            <span>Daftar Dancer ({{ $players->count() }})</span>
        </div>
        <div class="card-body">
            @if($players->count() > 0)
            <div class="table-container">
                <table class="data">
                    <thead>
                        <tr>
                            <th width="50">#</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th width="120">Tinggi Badan</th>
                            <th width="120">Berat Badan</th>
                            <th width="100">Role Dance</th>
                            <th width="120">Status Verifikasi</th>
                            <th width="100" class="text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($players as $index => $dancer)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $dancer->name ?? 'N/A' }}</strong></td>
                            <td>
                                @if($dancer->role == 'Leader')
                                <span class="status-locked">Leader</span>
                                @else
                                <span class="status-open">Member</span>
                                @endif
                            </td>
                            <td>{{ $dancer->height ?? '-' }} cm</td>
                            <td>{{ $dancer->weight ?? '-' }} kg</td>
                            <td>{{ $dancer->role ?? '-' }}</td>
                            <td>
                                @if($dancer->verification_status == 'verified')
                                <span class="status-verified">Terverifikasi</span>
                                @elseif($dancer->verification_status == 'rejected')
                                <span class="status-open">Ditolak</span>
                                @else
                                <span class="status-unverified">Belum Diverifikasi</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.dancer.detail', $dancer->dancer_id) }}" class="btn-detail">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="empty-state">
                <i class="fas fa-music"></i>
                <h5>Belum ada data dancer</h5>
                <p>Data dancer akan muncul di sini setelah mendaftar menggunakan referral code tim ini.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Official List Card (same as basket) -->
    <!-- Jersey List Card (same as basket) -->
</div>

@push('scripts')
<script>
    // Similar JavaScript as basket putra
    window.confirmAction = function(message) {
        return Swal.fire({
            title: 'Konfirmasi',
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#8b5cf6', // Purple for dancer
            cancelButtonColor: '#e74c3c',
            reverseButtons: true
        }).then((result) => {
            return result.isConfirmed;
        });
    };
</script>
@endpush
@endsection