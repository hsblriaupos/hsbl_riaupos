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
                        Tim {{ $team->team_category }} dari <strong>{{ $team->school_name }}</strong> telah berhasil didaftarkan.
                    </p>
                    
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>PERHATIAN!</strong> Pendaftaran tim BELUM SELESAI.
                    </div>

                    <!-- Important Step -->
                    <div class="card border-warning mb-4">
                        <div class="card-header bg-warning text-white">
                            <i class="fas fa-crown me-2"></i>LANGKAH WAJIB: Daftar sebagai KAPTEN
                        </div>
                        <div class="card-body">
                            <p class="mb-3 fw-bold fs-5">Anda HARUS mendaftarkan diri sebagai Kapten Tim!</p>
                            <ul class="text-start mb-3">
                                <li class="mb-2">✅ Kapten yang akan melakukan pembayaran biaya registrasi tim</li>
                                <li class="mb-2">✅ Setelah bayar, tim akan mendapatkan REFERRAL CODE</li>
                                <li class="mb-2">✅ Referral code dibagikan ke anggota lain untuk bergabung</li>
                                <li class="mb-2">✅ Tanpa Kapten, tim tidak bisa diikuti anggota lain</li>
                            </ul>
                            <div class="alert alert-info mb-0">
                                <i class="fas fa-info-circle"></i>
                                <strong>Referral code akan muncul SETELAH Anda mendaftar sebagai Kapten dan upload bukti pembayaran.</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Team Info -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h5 class="card-title">📋 Informasi Tim</h5>
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="text-muted text-start" style="width: 40%;">Sekolah</td>
                                    <td class="fw-bold text-start">{{ $team->school_name }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted text-start">Kategori</td>
                                    <td class="fw-bold text-start">{{ $team->team_category }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted text-start">Season/Series</td>
                                    <td class="fw-bold text-start">{{ $team->season }} - {{ $team->series }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted text-start">Kompetisi</td>
                                    <td class="fw-bold text-start">{{ $team->competition }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted text-start">Pendaftar</td>
                                    <td class="fw-bold text-start">{{ $team->registered_by }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted text-start">Status</td>
                                    <td class="fw-bold text-start">
                                        <span class="badge bg-warning">Menunggu Kapten</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-3">
                        <!-- Lanjut ke Form Kapten - HARUS INI! -->
                        <a href="{{ route('form.player.create', ['team_id' => $team->team_id]) }}" 
                           class="btn btn-warning btn-lg fw-bold">
                            <i class="fas fa-crown me-2"></i>LANJUTKAN DAFTAR SEBAGAI KAPTEN
                        </a>
                        
                        <!-- Dashboard -->
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </div>

                    <!-- Instructions -->
                    <div class="mt-5 pt-4 border-top">
                        <h5 class="mb-3">📝 Petunjuk Selanjutnya:</h5>
                        <ol class="text-start text-muted">
                            <li class="mb-2">1. Klik tombol <strong class="text-warning">"LANJUTKAN DAFTAR SEBAGAI KAPTEN"</strong> di atas</li>
                            <li class="mb-2">2. Isi semua data diri dengan lengkap dan benar</li>
                            <li class="mb-2">3. Upload dokumen yang diminta (Akta, KK, SHUN, Raport, Foto Formal)</li>
                            <li class="mb-2">4. <strong class="text-danger">Upload bukti pembayaran</strong> (wajib untuk Kapten)</li>
                            <li class="mb-2">5. Submit formulir</li>
                            <li class="mb-2">6. Setelah submit, Anda akan mendapatkan <strong>REFERRAL CODE</strong> tim</li>
                            <li class="mb-2">7. Bagikan referral code ke teman satu sekolah untuk bergabung</li>
                        </ol>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-4 pt-3 border-top">
                        <p class="text-muted small mb-0">
                            <i class="fas fa-question-circle"></i> Butuh bantuan? 
                            <a href="mailto:support@hsbl.com" class="text-decoration-none">support@hsbl.com</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
    
    .btn-warning {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        border: none;
        color: white;
        transition: transform 0.3s, box-shadow 0.3s;
    }
    
    .btn-warning:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(245, 87, 108, 0.3);
        color: white;
    }
    
    ol.text-start li {
        padding-left: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .table-borderless td {
        padding: 0.5rem 0;
    }
</style>
@endpush
@endsection