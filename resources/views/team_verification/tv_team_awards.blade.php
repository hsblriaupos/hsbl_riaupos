@extends('admin.layouts.app')
@section('title', 'Team Awards - Coming Soon')

@section('content')
@include('partials.sweetalert')

<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1 mt-2" style="font-size: 1.4rem; font-weight: 600;">
                <i class="fas fa-trophy me-2" style="color: #f6c23e;"></i> Team Awards
            </h1>
            <p class="text-muted mb-0" style="font-size: 0.9rem;">
                Manage team awards and achievements
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
                        <div class="bg-light-warning d-inline-flex p-4 rounded-circle">
                            <i class="fas fa-tools fa-4x" style="color: #f6c23e;"></i>
                        </div>
                    </div>
                    
                    <!-- Title -->
                    <h2 class="fw-bold mb-3" style="color: #2e3a59;">
                        Halaman Sedang Dalam Pengembangan
                    </h2>
                    
                    <!-- Description -->
                    <p class="text-muted mb-4" style="font-size: 1.1rem; max-width: 500px; margin: 0 auto;">
                        Fitur team awards sedang kami siapkan untuk mengelola penghargaan, MVP, top skor, dan pencapaian tim HSBL dengan lebih baik.
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
                    <i class="fas fa-medal fa-2x" style="color: #f6c23e; mb-3"></i>
                    <h6 class="fw-bold mt-3">MVP & Top Skor</h6>
                    <p class="small text-muted mb-0">Pemberian penghargaan pemain terbaik dan pencetak skor terbanyak</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-0 bg-light">
                <div class="card-body text-center p-4">
                    <i class="fas fa-award fa-2x" style="color: #f6c23e; mb-3"></i>
                    <h6 class="fw-bold mt-3">Penghargaan Tim</h6>
                    <p class="small text-muted mb-0">Rekap penghargaan juara, fair play, dan kategori khusus lainnya</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100 border-0 bg-light">
                <div class="card-body text-center p-4">
                    <i class="fas fa-certificate fa-2x" style="color: #f6c23e; mb-3"></i>
                    <h6 class="fw-bold mt-3">Sertifikat Digital</h6>
                    <p class="small text-muted mb-0">Generate sertifikat penghargaan otomatis untuk tim dan pemain</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-light-warning {
    background-color: rgba(246, 194, 62, 0.1);
}
</style>
@endsection