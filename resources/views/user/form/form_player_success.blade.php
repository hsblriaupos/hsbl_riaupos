@extends('user.form.layout')

@section('title', 'Pendaftaran Berhasil - SBL')

@section('content')
<div class="container py-2">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body p-3">
                    
                    <!-- Header -->
                    <div class="text-center mb-2 mt-2">
                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex p-1 mb-1">
                            <i class="fas fa-check-circle text-success" style="font-size: 1.5rem;"></i>
                        </div>
                        <h6 class="fw-bold text-success mb-0">
                            @if($isCaptain)
                            Pendaftaran Kapten Berhasil
                            @else
                            Pendaftaran Berhasil
                            @endif
                        </h6>
                        <p class="text-muted" style="font-size: 0.7rem; margin-bottom: 0;">{{ $successMessage }}</p>
                    </div>

                    <!-- Informasi Pendaftaran -->
                    <div class="bg-light rounded-3 mb-2" style="background: #f8f9fa; padding: 0.5rem;">
                        <div class="row" style="font-size: 0.7rem;">
                            <div class="col-6 mb-1">
                                <span class="text-muted">Nama:</span>
                                <span class="fw-semibold d-block">{{ $player->name }}</span>
                            </div>
                            <div class="col-6 mb-1">
                                <span class="text-muted">NIK:</span>
                                <span class="fw-semibold d-block">{{ $player->nik }}</span>
                            </div>
                            <div class="col-6 mb-1">
                                <span class="text-muted">Sekolah:</span>
                                <span class="fw-semibold d-block">{{ $team->school_name }}</span>
                            </div>
                            <div class="col-6 mb-1">
                                <span class="text-muted">Kategori:</span>
                                <span class="fw-semibold d-block">{{ $team->team_category }}</span>
                            </div>
                            <div class="col-6 mb-1">
                                <span class="text-muted">Role:</span>
                                <span class="badge {{ $isCaptain ? 'bg-warning' : 'bg-info' }} text-dark px-2" style="font-size: 0.65rem;">{{ $isCaptain ? 'KAPTEN' : 'ANGGOTA' }}</span>
                            </div>
                            <div class="col-6 mb-1">
                                <span class="text-muted">Jersey:</span>
                                <span class="fw-semibold d-block">{{ $player->jersey_number ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- REFERRAL CODE -->
                    @if($isCaptain && $referralCode && $referralCode !== '')
                    <div class="bg-primary bg-opacity-10 rounded-3 mb-2 text-center" style="padding: 0.5rem;">
                        <span class="d-block fw-semibold" style="font-size: 0.7rem;">Referral Code Tim</span>
                        <code class="fw-bold bg-white px-2 py-1 rounded border border-primary d-inline-block my-1" style="font-size: 0.9rem;">{{ $referralCode }}</code>
                        <div>
                            <button onclick="copyReferralCode()" class="btn btn-primary" style="font-size: 0.65rem; padding: 0.2rem 0.5rem;">
                                <i class="fas fa-copy me-1"></i> Salin
                            </button>
                        </div>
                    </div>
                    @endif

                    <!-- Tombol Aksi -->
                    <div class="d-grid gap-1">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-primary" style="font-size: 0.7rem; padding: 0.3rem 0.5rem;">
                            <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                        </a>
                        <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary" style="font-size: 0.7rem; padding: 0.3rem 0.5rem;">
                            <i class="fas fa-home me-1"></i> Kembali
                        </a>
                    </div>

                    <!-- Footer -->
                    <div class="text-center mt-2 pt-1 border-top">
                        <span style="font-size: 0.6rem;" class="text-muted">support@sbl.com</span>
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
    .btn-sm-custom {
        font-size: 0.7rem;
        padding: 0.2rem 0.5rem;
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