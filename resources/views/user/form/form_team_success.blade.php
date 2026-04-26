@extends('user.form.layout')

@section('title', 'Tim Berhasil Dibuat - SBL')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    
                    <!-- Header -->
                    <div class="text-center mb-2">
                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex p-1 mb-1">
                            <i class="fas fa-check-circle text-success" style="font-size: 1.3rem;"></i>
                        </div>
                        <h6 class="fw-bold text-success mb-0" style="font-size: 0.85rem;">
                            Tim Berhasil Dibuat
                        </h6>
                        <p class="text-muted" style="font-size: 0.65rem; margin-bottom: 0;">
                            {{ $team->school_name }} • {{ $team->team_category }}
                        </p>
                    </div>

                    <!-- Alert Wajib Kapten + Note Referral digabung -->
                    <div class="bg-light rounded-3 mb-2" style="background: #f8f9fa; padding: 0.5rem;">
                        <div class="d-flex align-items-center mb-1">
                            <i class="fas fa-exclamation-triangle text-warning me-2" style="font-size: 0.7rem;"></i>
                            <span style="font-size: 0.65rem;">
                                <span class="fw-semibold">Wajib:</span> Daftar Kapten untuk mengaktifkan tim.
                            </span>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-ticket-alt text-info me-2" style="font-size: 0.7rem;"></i>
                            <span style="font-size: 0.65rem;">Referral Code diberikan setelah Kapten daftar & bayar.</span>
                        </div>
                    </div>

                    <!-- Informasi Tim (lebih compact) -->
                    <div class="bg-light rounded-3 mb-2" style="background: #f8f9fa; padding: 0.5rem;">
                        <div class="row" style="font-size: 0.65rem;">
                            <div class="col-6 mb-1">
                                <span class="text-muted">Kompetisi:</span>
                                <span class="fw-semibold d-block">{{ $team->competition ?? '-' }}</span>
                            </div>
                            <div class="col-6 mb-1">
                                <span class="text-muted">Season/Series:</span>
                                <span class="fw-semibold d-block">{{ $team->season }}/{{ $team->series }}</span>
                            </div>
                            <div class="col-6 mb-1">
                                <span class="text-muted">Pendaftar:</span>
                                <span class="fw-semibold d-block">{{ $team->registered_by }}</span>
                            </div>
                            <div class="col-6 mb-1">
                                <span class="text-muted">Status:</span>
                                <span class="fw-semibold d-block text-warning" style="font-size: 0.65rem;">Menunggu Kapten</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('form.player.create', ['team_id' => $team->team_id]) }}" 
                           class="btn btn-primary" style="font-size: 0.7rem; padding: 0.35rem 0.5rem;">
                            <i class="fas fa-crown me-1"></i> Daftar Kapten
                        </a>
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary" style="font-size: 0.7rem; padding: 0.35rem 0.5rem;">
                            <i class="fas fa-arrow-left me-1"></i> Dashboard
                        </a>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-2 pt-1 border-top">
                        <span style="font-size: 0.55rem;" class="text-muted">hondahsblriaupos@gmail.com</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-opacity-10 {
        background: rgba(67, 97, 238, 0.1);
    }
</style>
@endpush

@endsection