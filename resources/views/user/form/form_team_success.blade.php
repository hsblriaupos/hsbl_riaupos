@extends('user.form.layout')

@section('title', 'Tim Berhasil Dibuat - HSBL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <!-- Header -->
                <div class="card-header bg-gradient-primary text-white py-4 text-center">
                    <h1 class="mb-0">🎉 Selamat!</h1>
                    <p class="mb-0 opacity-75">Tim kamu berhasil didaftarkan</p>
                </div>
                
                <!-- Body -->
                <div class="card-body p-5">
                    <!-- Success Icon -->
                    <div class="text-center mb-4">
                        <div class="d-inline-block p-4 rounded-circle bg-success bg-opacity-10">
                            <i class="fas fa-check-circle fa-4x text-success"></i>
                        </div>
                    </div>
                    
                    <!-- Team Info -->
                    <div class="card border-primary mb-4">
                        <div class="card-body">
                            <h5 class="card-title text-primary mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informasi Tim
                            </h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <strong>Nama Sekolah:</strong>
                                    <p class="mb-0">{{ $team->school_name }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Kategori:</strong>
                                    <p class="mb-0">{{ $team->team_category }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Season:</strong>
                                    <p class="mb-0">{{ $team->season }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <strong>Series:</strong>
                                    <p class="mb-0">{{ $team->series }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Referral Code Box -->
                    <div class="card border-success mb-4">
                        <div class="card-header bg-success text-white">
                            <i class="fas fa-key me-2"></i>Referral Code
                        </div>
                        <div class="card-body text-center">
                            <div class="display-4 fw-bold text-success mb-3 font-monospace">
                                {{ $team->referral_code }}
                            </div>
                            <p class="text-muted">
                                <i class="fas fa-share-alt me-1"></i>
                                Bagikan kode ini ke teman sekelas untuk bergabung
                            </p>
                        </div>
                    </div>
                    
                    <!-- Next Steps -->
                    <div class="card border-warning">
                        <div class="card-header bg-warning">
                            <i class="fas fa-forward me-2"></i>Langkah Selanjutnya
                        </div>
                        <div class="card-body">
                            <div class="d-flex mb-3">
                                <div class="me-3">
                                    <span class="badge bg-warning rounded-circle p-2">1</span>
                                </div>
                                <div>
                                    <strong>Pembayaran</strong>
                                    <p class="text-muted mb-0">
                                        Sebagai Leader, kamu perlu melakukan pembayaran terlebih dahulu
                                    </p>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="me-3">
                                    <span class="badge bg-warning rounded-circle p-2">2</span>
                                </div>
                                <div>
                                    <strong>Data Leader</strong>
                                    <p class="text-muted mb-0">Isi data pribadi kamu sebagai Leader</p>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="me-3">
                                    <span class="badge bg-warning rounded-circle p-2">3</span>
                                </div>
                                <div>
                                    <strong>Undang Anggota</strong>
                                    <p class="text-muted mb-0">
                                        Bagikan referral code dan undang teman bergabung
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="text-center mt-5">
                        <a href="{{ route('payment.form', ['team_id' => $team->team_id]) }}" 
                           class="btn btn-primary btn-lg px-5 py-3 me-3">
                            <i class="fas fa-credit-card me-2"></i>Lanjut ke Pembayaran
                        </a>
                        <a href="{{ route('form.player.create', ['team_id' => $team->team_id]) }}" 
                           class="btn btn-outline-primary btn-lg px-5 py-3">
                            <i class="fas fa-user me-2"></i>Isi Data Leader Dulu
                        </a>
                    </div>
                    
                    <!-- Share Options -->
                    <div class="text-center mt-4">
                        <p class="text-muted mb-2">Bagikan referral code:</p>
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-outline-success" onclick="shareCode('whatsapp')">
                                <i class="fab fa-whatsapp me-2"></i>WhatsApp
                            </button>
                            <button class="btn btn-outline-info" onclick="shareCode('telegram')">
                                <i class="fab fa-telegram me-2"></i>Telegram
                            </button>
                            <button class="btn btn-outline-primary" onclick="copyCode()">
                                <i class="fas fa-copy me-2"></i>Salin Kode
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function copyCode() {
    const code = '{{ $team->referral_code }}';
    navigator.clipboard.writeText(code).then(() => {
        alert('Referral code berhasil disalin!');
    });
}

function shareCode(platform) {
    const code = '{{ $team->referral_code }}';
    const message = `Halo! Gabung ke tim {{ $team->school_name }} ({{ $team->team_category }}) di HSBL! ` +
                   `Gunakan referral code: ${code}`;
    
    let url = '';
    
    switch(platform) {
        case 'whatsapp':
            url = `https://wa.me/?text=${encodeURIComponent(message)}`;
            break;
        case 'telegram':
            url = `https://t.me/share/url?url=${encodeURIComponent(window.location.href)}&text=${encodeURIComponent(message)}`;
            break;
    }
    
    if (url) {
        window.open(url, '_blank');
    }
}
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.font-monospace {
    font-family: 'Courier New', monospace;
    letter-spacing: 2px;
}
.badge.rounded-circle {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}
.card-header {
    border-radius: 0 !important;
}
</style>
@endpush
@endsection