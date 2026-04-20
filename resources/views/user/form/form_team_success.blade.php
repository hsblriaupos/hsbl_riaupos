@extends('user.form.layout')

@section('title', 'Tim Berhasil Dibuat - SBL')

@section('content')
<div class="container py-2">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-7 col-lg-5">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    
                    {{-- Header Tengah --}}
                    <div class="text-center mb-3">
                        <i class="fas fa-check-circle text-success mb-1" style="font-size: 2rem;"></i>
                        <h6 class="fw-bold mb-0" style="font-size: 0.9rem;">Tim Berhasil Dibuat!</h6>
                        <p class="text-secondary mb-0" style="font-size: 0.7rem;">{{ $team->school_name }} • {{ $team->team_category }}</p>
                    </div>

                    {{-- Alert Wajib Kapten --}}
                    <div class="custom-alert-warning p-2 mb-2" style="border-radius: 6px; border-left: 3px solid #f59e0b; background: #fff8e5; font-size: 0.7rem;">
                        <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                        <strong>Wajib:</strong> Daftar Kapten untuk mengaktifkan tim.
                    </div>

                    {{-- Detail Tim --}}
                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <span class="text-secondary d-block" style="font-size: 0.6rem;">Kompetisi</span>
                            <span style="font-size: 0.7rem;">{{ $team->competition ?? '-' }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-secondary d-block" style="font-size: 0.6rem;">Season/Series</span>
                            <span style="font-size: 0.7rem;">{{ $team->season }}/{{ $team->series }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-secondary d-block" style="font-size: 0.6rem;">Pendaftar</span>
                            <span style="font-size: 0.7rem;">{{ $team->registered_by }}</span>
                        </div>
                        <div class="col-6">
                            <span class="text-secondary d-block" style="font-size: 0.6rem;">Status</span>
                            <span class="badge bg-warning text-dark" style="font-size: 0.6rem; padding: 2px 5px;">Menunggu Kapten</span>
                        </div>
                    </div>

                    {{-- Note Referral --}}
                    <div class="custom-alert-info p-2 mb-3" style="border-radius: 6px; border-left: 3px solid #0dcaf0; background: #e7f3ff; font-size: 0.7rem;">
                        <i class="fas fa-ticket-alt text-info me-1"></i>
                        <strong>Referral Code</strong> diberikan setelah Kapten selesai daftar & bayar.
                    </div>

                    {{-- Tombol --}}
                    <div class="d-grid gap-2">
                        <a href="{{ route('form.player.create', ['team_id' => $team->team_id]) }}" 
                           class="btn btn-primary btn-sm py-2" style="font-size: 0.75rem;">
                            <i class="fas fa-crown me-1"></i>Daftar Kapten
                        </a>
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-sm py-2" style="font-size: 0.75rem;">
                            <i class="fas fa-arrow-left me-1"></i>Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .container {
        padding-left: 10px !important;
        padding-right: 10px !important;
    }
    .card {
        margin: 0 auto;
    }
    .custom-alert-warning {
        background-color: #fff8e5 !important;
        color: #664d03 !important;
    }
    .custom-alert-info {
        background-color: #e7f3ff !important;
        color: #084298 !important;
    }
    .text-secondary {
        color: #6c757d !important;
    }
    .fw-medium {
        font-weight: 500 !important;
    }
</style>
@endpush

@endsection