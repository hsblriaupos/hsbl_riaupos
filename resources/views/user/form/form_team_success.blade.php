@extends('user.form.layout')

@section('title', 'Tim Berhasil Dibuat - HSBL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5 text-center">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <div class="success-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>

                    <!-- Success Message -->
                    <h2 class="text-success mb-3">🎉 Tim Berhasil Dibuat!</h2>
                    
                    <p class="lead mb-4">
                        Tim Anda telah berhasil didaftarkan dan pembayaran telah diverifikasi.
                    </p>
                    
                    <div class="alert alert-info mb-4">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Selanjutnya:</strong> Lengkapi data diri Anda sebagai <strong>Kapten Tim</strong>.
                    </div>

                    <!-- Team Info -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h5 class="card-title">📋 Informasi Tim</h5>
                            <ul class="list-unstyled mb-0">
                                <li><strong>Sekolah:</strong> {{ $team->school_name }}</li>
                                <li><strong>Kategori:</strong> {{ $team->team_category }}</li>
                                <li><strong>Season:</strong> {{ $team->season }}</li>
                                <li><strong>Series:</strong> {{ $team->series }}</li>
                                <li><strong>Kompetisi:</strong> {{ $team->competition }}</li>
                                <li><strong>Pendaftar:</strong> {{ $team->registered_by }}</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Referral Code -->
                    @if($referralCode)
                    <div class="card border-success mb-4">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-gift me-2"></i>Referral Code Tim
                        </div>
                        <div class="card-body text-center">
                            <h3 class="text-success mb-3">{{ $referralCode }}</h3>
                            <p class="text-muted small mb-0">
                                Kode ini akan digunakan oleh anggota tim untuk bergabung.
                                <strong>Simpan baik-baik!</strong>
                            </p>
                        </div>
                    </div>
                    
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Catatan Penting:</strong> 
                        1 referral code digunakan untuk semua kategori tim di sekolah yang sama.
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-grid gap-3">
                        <!-- Lanjut ke Form Kapten -->
                        <a href="{{ route('form.player.create', ['team_id' => $team->team_id]) }}" 
                           class="btn btn-primary btn-lg">
                            <i class="fas fa-user-shield me-2"></i>Lengkapi Data Kapten
                        </a>
                        
                        <!-- Bagikan Referral Code -->
                        <a href="#" onclick="copyReferralCode()" class="btn btn-outline-success">
                            <i class="fas fa-copy me-2"></i>Salin Referral Code
                        </a>
                        
                        <!-- Dashboard -->
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-primary">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </div>

                    <!-- Instructions -->
                    <div class="mt-5 pt-4 border-top">
                        <h5 class="mb-3">📝 Petunjuk Selanjutnya:</h5>
                        <ol class="text-start text-muted">
                            <li class="mb-2">Klik <strong>"Lengkapi Data Kapten"</strong> untuk mengisi data diri Anda</li>
                            <li class="mb-2">Upload dokumen yang diperlukan (KTP, foto, dll)</li>
                            <li class="mb-2">Setelah submit, Anda akan mendapatkan <strong>referral code</strong></li>
                            <li class="mb-2">Bagikan referral code ke anggota tim Anda</li>
                            <li>Anggota tim bisa join menggunakan referral code tersebut</li>
                        </ol>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-4 pt-3 border-top">
                        <p class="text-muted small mb-0">
                            Butuh bantuan? 
                            <a href="mailto:support@hsbl.com" class="text-decoration-none">support@hsbl.com</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function copyReferralCode() {
        const referralCode = "{{ $referralCode ?? '' }}";
        if (referralCode) {
            navigator.clipboard.writeText(referralCode)
                .then(() => {
                    alert('Referral code berhasil disalin: ' + referralCode);
                })
                .catch(err => {
                    console.error('Gagal menyalin: ', err);
                });
        }
    }
</script>

@push('styles')
<style>
    .success-icon {
        font-size: 5rem;
        color: #28a745;
        animation: bounceIn 1s;
    }
    
    @keyframes bounceIn {
        0% {
            transform: scale(0.3);
            opacity: 0;
        }
        50% {
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }
    
    .card {
        border-radius: 15px;
    }
    
    .btn-lg {
        padding: 0.75rem 2rem;
        font-size: 1.1rem;
    }
    
    ol.text-start li {
        padding-left: 0.5rem;
        margin-bottom: 0.5rem;
    }
</style>
@endpush
@endsection