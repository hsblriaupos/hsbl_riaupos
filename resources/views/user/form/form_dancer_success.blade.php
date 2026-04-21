@extends('user.form.layout')

@section('title', 'Pendaftaran Berhasil - SBL')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-4 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header text-white py-2 text-center" style="background: #2c7da0 !important;">
                    <i class="fas fa-check-circle me-1"></i> Berhasil!
                </div>
                <div class="card-body p-3 text-center">
                    <!-- Icon -->
                    <i class="fas fa-check-circle text-success fa-2x mb-2"></i>
                    
                    <!-- Nama & Role -->
                    <div class="fw-bold mb-1">{{ $dancer->name }}</div>
                    @if($isLeader)
                    <span class="badge bg-warning text-dark mb-2 fw-normal px-2 py-1" style="font-size: 0.65rem;">LEADER</span>
                    @else
                    <span class="badge bg-info mb-2 fw-normal px-2 py-1" style="font-size: 0.65rem;">MEMBER</span>
                    @endif
                    
                    <!-- Sekolah -->
                    <div class="small text-muted mb-2">{{ $team->school_name }}</div>

                    <!-- Referral Code (only for Leader) -->
                    @if($isLeader && $referralCode)
                    <div class="bg-light rounded py-1 px-2 mt-2 d-flex align-items-center justify-content-between" style="font-size: 0.7rem;">
                        <span class="text-muted">Kode:</span>
                        <span class="fw-bold text-success">{{ $referralCode }}</span>
                        <button class="btn btn-sm btn-link text-success p-0" onclick="copyReferralCode('{{ $referralCode }}')" style="font-size: 0.7rem;">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                    @endif

                    <!-- Status -->
                    <div class="small text-muted mt-3">
                        <i class="fas fa-clock me-1"></i> Menunggu verifikasi
                    </div>

                    <!-- Button -->
                    <a href="{{ route('student.dashboard') }}" class="btn btn-primary btn-sm w-100 mt-3 py-1" style="font-size: 0.7rem;">
                        <i class="fas fa-home me-1"></i>Ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyReferralCode(code) {
    navigator.clipboard.writeText(code).then(function() {
        Swal.fire({
            icon: 'success',
            title: 'Tersalin!',
            text: 'Kode referral berhasil disalin',
            confirmButtonColor: '#2c7da0',
            timer: 1200,
            showConfirmButton: false
        });
    });
}
</script>

<style>
.card, .card-header, .card-body, .btn, .badge {
    transition: none !important;
    animation: none !important;
}

.card-header {
    background: #2c7da0 !important;
    border-bottom: none;
    border-radius: 6px 6px 0 0 !important;
}

.card {
    border-radius: 6px !important;
}

.btn-primary {
    background: #2c7da0 !important;
    border: none;
}

.btn-primary:hover {
    background: #1f5a73 !important;
}
</style>
@endsection