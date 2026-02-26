@extends('admin.layouts.app')
@section('title', 'Campers Management - Coming Soon')

@section('content')
@include('partials.sweetalert')

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1 mt-2" style="font-size: 1.4rem; font-weight: 600;">
                <i class="fas fa-campground me-2" style="color: #1cc88a;"></i> Campers Management
            </h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">
                Manage campers and training participants
            </p>
        </div>
        <div class="text-muted" style="font-size: 0.9rem;">
            <i class="fas fa-calendar me-1"></i> {{ now()->format('l, d F Y') }}
        </div>
    </div>

    <!-- Coming Soon Content -->
    <div class="row justify-content-center mt-5">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <!-- Icon -->
                    <div class="mb-4">
                        <div class="bg-light-success d-inline-flex p-4 rounded-circle">
                            <i class="fas fa-tools fa-4x" style="color: #1cc88a;"></i>
                        </div>
                    </div>
                    
                    <!-- Title -->
                    <h2 class="fw-bold mb-3" style="color: #2e3a59;">
                        Halaman Sedang Dalam Pengembangan
                    </h2>
                    
                    <!-- Description -->
                    <p class="text-muted mb-4" style="font-size: 1.1rem; max-width: 500px; margin: 0 auto;">
                        Fitur campers management sedang kami siapkan untuk mengelola pendaftaran, jadwal pelatihan, dan perkembangan peserta camp SBL.
                    </p>
                    
                    <!-- Back Button -->
                    <div class="mt-5">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-arrow-left me-2"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Info Cards -->
    <div class="row mt-5">
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-0 bg-light">
                <div class="card-body text-center p-4">
                    <i class="fas fa-user-plus fa-2x" style="color: #1cc88a; mb-3"></i>
                    <h6 class="fw-bold mt-3">Registrasi Camper</h6>
                    <p class="small text-muted mb-0">Pendaftaran peserta camp dengan form terintegrasi</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-0 bg-light">
                <div class="card-body text-center p-4">
                    <i class="fas fa-calendar-alt fa-2x" style="color: #1cc88a; mb-3"></i>
                    <h6 class="fw-bold mt-3">Jadwal Pelatihan</h6>
                    <p class="small text-muted mb-0">Atur jadwal, materi, dan instruktur pelatihan</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-0 bg-light">
                <div class="card-body text-center p-4">
                    <i class="fas fa-chart-line fa-2x" style="color: #1cc88a; mb-3"></i>
                    <h6 class="fw-bold mt-3">Evaluasi Peserta</h6>
                    <p class="small text-muted mb-0">Tracking perkembangan dan penilaian peserta</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-light-success {
    background-color: rgba(28, 200, 138, 0.1);
}
</style>
@endsection