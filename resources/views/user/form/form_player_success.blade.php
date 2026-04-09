@extends('user.form.layout')

@section('title', 'Pendaftaran Berhasil - SBL')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <div class="card-body p-3">
                    
                    <!-- Header dengan Icon -->
                    <div class="text-center mb-3">
                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex p-2 mb-2">
                            <i class="fas fa-check-circle text-success" style="font-size: 2rem;"></i>
                        </div>
                        <h5 class="fw-bold text-success mb-0">
                            @if($isCaptain)
                            Pendaftaran Kapten Berhasil!
                            @else
                            Pendaftaran Berhasil!
                            @endif
                        </h5>
                        <p class="text-muted small mb-0">{{ $successMessage }}</p>
                    </div>

                    <!-- Informasi Pendaftaran -->
                    <div class="bg-light p-2 rounded-3 mb-3" style="background: #f8f9fa;">
                        <div class="row g-1">
                            <div class="col-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Nama:</span>
                                    <span class="fw-semibold small">{{ $player->name }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">NIK:</span>
                                    <span class="fw-semibold small">{{ $player->nik }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Sekolah:</span>
                                    <span class="fw-semibold small">{{ $team->school_name }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Kategori:</span>
                                    <span class="fw-semibold small">{{ $team->team_category }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Role:</span>
                                    <span class="badge {{ $isCaptain ? 'bg-warning' : 'bg-info' }} text-dark px-2 py-1">{{ $isCaptain ? 'KAPTEN' : 'ANGGOTA' }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Jersey:</span>
                                    <span class="fw-semibold small">{{ $player->jersey_number ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- REFERRAL CODE (Hanya untuk Kapten) -->
                    @if($isCaptain && $referralCode && $referralCode !== '')
                    <div class="bg-primary bg-opacity-10 p-2 rounded-3 mb-3 text-center">
                        <strong class="d-block small mb-1">Referral Code Tim</strong>
                        <div class="bg-white px-2 py-1 rounded border border-primary d-inline-block mb-1">
                            <code class="fw-bold fs-5">{{ $referralCode }}</code>
                        </div>
                        <div>
                            <button onclick="copyReferralCode()" class="btn btn-primary btn-sm py-0 px-2">
                                <i class="fas fa-copy me-1"></i> Salin
                            </button>
                        </div>
                    </div>
                    @endif

                    <!-- Tombol Aksi -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-primary btn-sm py-1">
                            <i class="fas fa-tachometer-alt me-1"></i> Ke Dashboard
                        </a>
                        <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary btn-sm py-1">
                            <i class="fas fa-home me-1"></i> Kembali
                        </a>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-3 pt-2 border-top">
                        <small class="text-muted">support@sbl.com</small>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function copyReferralCode() {
        const referralCode = '{{ $referralCode }}';
        
        navigator.clipboard.writeText(referralCode).then(function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Disalin!',
                text: 'Referral code telah disalin.',
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