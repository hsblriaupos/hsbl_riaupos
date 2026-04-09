@extends('user.form.layout')

@section('title', 'Pendaftaran Berhasil - SBL')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    
                    <!-- Header dengan Icon -->
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex p-3 mb-3">
                            <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="fw-bold text-success mb-1">
                            @if($isCaptain)
                            Pendaftaran Kapten Berhasil!
                            @else
                            Pendaftaran Berhasil!
                            @endif
                        </h4>
                        <p class="text-muted small">{{ $successMessage }}</p>
                    </div>

                    <!-- Informasi Pendaftaran -->
                    <div class="bg-light p-3 rounded-3 mb-4" style="background: #f8f9fa;">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <i class="fas fa-info-circle me-2"></i>Informasi Pendaftaran
                        </h6>
                        
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Nama Lengkap:</span>
                                    <span class="fw-semibold small">{{ $player->name }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">NIK:</span>
                                    <span class="fw-semibold small">{{ $player->nik }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Sekolah:</span>
                                    <span class="fw-semibold small">{{ $team->school_name }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Kategori Tim:</span>
                                    <span class="fw-semibold small">{{ $team->team_category }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Role:</span>
                                    <span class="badge {{ $isCaptain ? 'bg-warning' : 'bg-info' }} text-dark px-2 py-1">
                                        {{ $isCaptain ? 'KAPTEN' : 'ANGGOTA' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Nomor Jersey:</span>
                                    <span class="fw-semibold small">{{ $player->jersey_number ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Email:</span>
                                    <span class="fw-semibold small">{{ $player->email }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">WhatsApp:</span>
                                    <span class="fw-semibold small">{{ $player->phone }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- REFERRAL CODE (Hanya untuk Kapten) -->
                    @if($isCaptain && $referralCode && $referralCode !== '')
                    <div class="bg-primary bg-opacity-10 p-3 rounded-3 mb-4 text-center">
                        <i class="fas fa-key text-primary mb-2" style="font-size: 1.2rem;"></i>
                        <strong class="d-block mb-2">Referral Code Tim</strong>
                        <div class="bg-white p-2 rounded border border-primary d-inline-block mb-2">
                            <code class="fw-bold fs-4">{{ $referralCode }}</code>
                        </div>
                        <div>
                            <button onclick="copyReferralCode()" class="btn btn-primary btn-sm">
                                <i class="fas fa-copy me-1"></i> Salin Kode
                            </button>
                        </div>
                        <small class="text-muted d-block mt-2">Bagikan kode ini ke anggota tim untuk bergabung</small>
                    </div>
                    @endif

                    <!-- Tombol Aksi -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-primary py-2 fw-semibold">
                            <i class="fas fa-tachometer-alt me-2"></i> Ke Dashboard
                        </a>
                        <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary py-2">
                            <i class="fas fa-home me-2"></i> Kembali
                        </a>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-4 pt-2 border-top">
                        <small class="text-muted">
                            <i class="fas fa-envelope me-1"></i> support@sbl.com
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-soft-warning {
        background: rgba(248, 150, 30, 0.1);
    }
    .bg-opacity-10 {
        background: rgba(67, 97, 238, 0.1);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function copyReferralCode() {
        const referralCode = '{{ $referralCode }}';
        
        navigator.clipboard.writeText(referralCode).then(function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Disalin!',
                text: 'Referral code telah disalin ke clipboard.',
                confirmButtonColor: '#28a745',
                timer: 1500,
                showConfirmButton: false
            });
        }).catch(function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyalin',
                text: 'Silakan salin manual: ' + referralCode,
                confirmButtonColor: '#dc3545'
            });
        });
    }
</script>
@endpush

@endsection