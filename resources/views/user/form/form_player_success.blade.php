@extends('user.form.layout')

@section('title', 'Pendaftaran Berhasil - SBL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <!-- Success Notification -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="fas fa-check-circle fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-1">🎉 Pendaftaran Berhasil!</h5>
                        <p class="mb-0">{{ session('success') }}</p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Debug Info (Hanya untuk development) -->
            @if(env('APP_DEBUG'))
            <div class="alert alert-info mb-4">
                <h6 class="alert-heading">🔍 DEBUG INFO:</h6>
                <ul class="mb-0">
                    <li>Role Player: <strong>{{ $player->role }}</strong></li>
                    <li>Is Captain: <strong>{{ $isCaptain ? 'YA' : 'TIDAK' }}</strong></li>
                    <li>Referral Code: <strong>{{ $referralCode ?: 'NULL/EMPTY' }}</strong></li>
                    <li>Team Paid: <strong>{{ $team->is_leader_paid ? 'YA' : 'BELUM' }}</strong></li>
                    <li>Team ID: <strong>{{ $team->team_id }}</strong></li>
                </ul>
            </div>
            @endif

            <!-- Screenshot Warning -->
            <div class="alert alert-warning alert-dismissible fade show mb-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-camera fa-2x me-3"></i>
                    <div>
                        <h5 class="alert-heading mb-1">📸 Screenshot Halaman Ini!</h5>
                        <p class="mb-0">
                            <strong>Simpan bukti pendaftaran Anda!</strong> 
                            Screenshot atau salin informasi di bawah untuk referensi.
                            Referral code hanya muncul sekali!
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>

            <!-- Success Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-success text-white py-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-0">
                                <i class="fas fa-trophy me-2"></i>
                                @if($isCaptain)
                                KAPTEN TIM TERDAFTAR
                                @else
                                ANGGOTA TIM TERDAFTAR
                                @endif
                            </h4>
                            <small class="opacity-75">SBL Registration System</small>
                        </div>
                        <div class="text-end">
                            <small class="opacity-75">ID: {{ $team->team_id }}/{{ $player->id }}</small>
                            <br>
                            <small class="opacity-75">{{ now()->format('d/m/Y H:i') }}</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-5">
                    <!-- Success Icon -->
                    <div class="text-center mb-4">
                        <div class="success-icon mb-3">
                            @if($isCaptain)
                            <i class="fas fa-crown"></i>
                            @else
                            <i class="fas fa-user-check"></i>
                            @endif
                        </div>
                        <h2 class="text-success fw-bold mb-2">
                            @if($isCaptain)
                            👑 SELAMAT, ANDA KAPTEN!
                            @else
                            🎉 SELAMAT, ANDA TERDAFTAR!
                            @endif
                        </h2>
                        <p class="text-muted">
                            {{ $successMessage }}
                        </p>
                    </div>

                    <!-- Team Info Card -->
                    <div class="card border mb-4">
                        <div class="card-header bg-light">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-info-circle me-2"></i>Informasi Pendaftaran
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <i class="fas fa-school text-primary"></i>
                                        <div>
                                            <small class="text-muted">Sekolah</small>
                                            <p class="mb-0 fw-bold">{{ $team->school_name }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <i class="fas fa-basketball-ball text-danger"></i>
                                        <div>
                                            <small class="text-muted">Kategori Tim</small>
                                            <p class="mb-0 fw-bold">{{ $team->team_category }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="info-item">
                                        <i class="fas fa-calendar text-success"></i>
                                        <div>
                                            <small class="text-muted">Season</small>
                                            <p class="mb-0 fw-bold">{{ $team->season }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="info-item">
                                        <i class="fas fa-user-tag text-warning"></i>
                                        <div>
                                            <small class="text-muted">Role Anda</small>
                                            <p class="mb-0 fw-bold">
                                                @if($isCaptain)
                                                <span class="badge bg-warning">KAPTEN</span>
                                                @else
                                                <span class="badge bg-info">ANGGOTA</span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="info-item">
                                        <i class="fas fa-user text-warning"></i>
                                        <div>
                                            <small class="text-muted">Nama Anda</small>
                                            <p class="mb-0 fw-bold">{{ $player->name }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <i class="fas fa-id-card text-info"></i>
                                        <div>
                                            <small class="text-muted">NIK</small>
                                            <p class="mb-0 fw-bold">{{ $player->nik }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="info-item">
                                        <i class="fas fa-envelope text-purple"></i>
                                        <div>
                                            <small class="text-muted">Email</small>
                                            <p class="mb-0 fw-bold">{{ $player->email }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 🔥 REFERRAL CODE SECTION (Hanya untuk Leader) -->
                    @if($isCaptain)
                        @if($referralCode && $referralCode !== '')
                        <div class="card border-success border-3 mb-4">
                            <div class="card-header bg-success bg-opacity-10 border-success">
                                <h5 class="card-title mb-0 text-success">
                                    <i class="fas fa-gift me-2"></i>REFERRAL CODE ANDA
                                </h5>
                            </div>
                            <div class="card-body text-center py-4">
                                <!-- Important Notice -->
                                <div class="alert alert-warning mb-4">
                                    <div class="d-flex">
                                        <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                        <div>
                                            <h6 class="alert-heading mb-2">⚠️ SIMPAN REFERRAL CODE INI!</h6>
                                            <p class="mb-0">
                                                <strong>Kode ini hanya muncul sekali!</strong> 
                                                Screenshot atau salin sekarang. 
                                                Anggota lain perlu kode ini untuk bergabung.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Referral Code Display -->
                                <div class="referral-code-display mb-4">
                                    <div class="referral-code-label">
                                        <i class="fas fa-key me-2"></i>Kode Referral
                                    </div>
                                    <div class="referral-code-value">
                                        {{ $referralCode }}
                                    </div>
                                    <small class="text-muted mt-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Bagikan kode ini ke calon anggota tim
                                    </small>
                                </div>
                                
                                <!-- Action Buttons -->
                                <div class="d-flex flex-column flex-md-row justify-content-center gap-3 mb-4">
                                    <button class="btn btn-success" onclick="copyReferralCode()">
                                        <i class="fas fa-copy me-2"></i>Salin Referral Code
                                    </button>
                                    <button class="btn btn-outline-success" onclick="printPage()">
                                        <i class="fas fa-print me-2"></i>Print Halaman Ini
                                    </button>
                                    <a href="whatsapp://send?text=Halo!%20Join%20tim%20{{ $team->school_name }}%20di%20HSBL.%20Referral%20Code:%20{{ $referralCode }}%0A%0ALink:%20{{ url('/form/team/join') }}" 
                                       class="btn btn-outline-primary" target="_blank">
                                        <i class="fab fa-whatsapp me-2"></i>Share via WhatsApp
                                    </a>
                                </div>

                                <!-- Join Instructions -->
                                <div class="alert alert-info">
                                    <h6 class="alert-heading">
                                        <i class="fas fa-users me-2"></i>Cara Bergabung untuk Anggota:
                                    </h6>
                                    <ol class="mb-0">
                                        <li>Buka <a href="{{ url('/form/team/join') }}">halaman "Gabung Tim"</a></li>
                                        <li>Masukkan referral code: <code class="bg-light p-1">{{ $referralCode }}</code></li>
                                        <li>Isi data diri sebagai anggota</li>
                                        <li><strong>Tidak perlu membayar</strong> - biaya sudah ditanggung Kapten</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- 🔥 Jika Leader tapi belum ada referral code -->
                        <div class="card border-danger border-3 mb-4">
                            <div class="card-header bg-danger bg-opacity-10 border-danger">
                                <h5 class="card-title mb-0 text-danger">
                                    <i class="fas fa-exclamation-circle me-2"></i>REFERRAL CODE BELUM TERSEDIA
                                </h5>
                            </div>
                            <div class="card-body text-center py-4">
                                <div class="alert alert-danger mb-4">
                                    <div class="d-flex">
                                        <i class="fas fa-times-circle fa-2x me-3"></i>
                                        <div>
                                            <h6 class="alert-heading mb-2">❌ TERJADI KESALAHAN SISTEM</h6>
                                            <p class="mb-0">
                                                Sebagai Kapten, Anda seharusnya sudah mendapatkan referral code.<br>
                                                <strong>Hubungi panitia SBL segera!</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-light p-4 rounded mb-4">
                                    <h6 class="mb-3">📋 Informasi untuk Panitia:</h6>
                                    <ul class="list-unstyled text-start">
                                        <li><strong>ID Tim:</strong> {{ $team->team_id }}</li>
                                        <li><strong>ID Player:</strong> {{ $player->id }}</li>
                                        <li><strong>Nama Leader:</strong> {{ $player->name }}</li>
                                        <li><strong>Sekolah:</strong> {{ $team->school_name }}</li>
                                        <li><strong>Kategori:</strong> {{ $team->team_category }}</li>
                                        <li><strong>Email:</strong> {{ $player->email }}</li>
                                    </ul>
                                </div>
                                
                                <div class="alert alert-warning">
                                    <i class="fas fa-phone-alt me-2"></i>
                                    <strong>Kontak Panitia:</strong> 
                                    <a href="mailto:support@sbl.com" class="text-decoration-none">support@sbl.com</a> | 
                                    <a href="https://wa.me/6281234567890" class="text-decoration-none">WhatsApp</a>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Instructions for Leader -->
                        <div class="card border-info mb-4">
                            <div class="card-header bg-info bg-opacity-10 border-info">
                                <h5 class="card-title mb-0 text-info">
                                    <i class="fas fa-list-check me-2"></i>LANGKAH SELANJUTNYA UNTUK KAPTEN
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="step-card">
                                            <div class="step-number">1</div>
                                            <h6>Bagikan Referral Code</h6>
                                            <p class="small mb-0">Berikan referral code ke calon anggota tim</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="step-card">
                                            <div class="step-number">2</div>
                                            <h6>Anggota Bergabung</h6>
                                            <p class="small mb-0">Anggota join dengan referral code tanpa bayar</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="step-card">
                                            <div class="step-number">3</div>
                                            <h6>Pantau Tim</h6>
                                            <p class="small mb-0">Cek dashboard untuk lihat anggota yang join</p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="step-card">
                                            <div class="step-number">4</div>
                                            <h6>Tunggu Konfirmasi</h6>
                                            <p class="small mb-0">Tunggu verifikasi dari panitia SBL</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- For Member -->
                        <div class="card border-success mb-4">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                                <h4 class="text-success mb-3">🎉 BERHASIL BERGABUNG!</h4>
                                <p class="lead">
                                    Anda telah terdaftar sebagai anggota tim 
                                    <strong>{{ $team->school_name }}</strong>.
                                </p>
                                <div class="alert alert-success">
                                    <i class="fas fa-money-bill-wave me-2"></i>
                                    <strong>Tidak perlu membayar!</strong> 
                                    Biaya registrasi sudah ditanggung oleh Kapten tim.
                                </div>
                                
                                @if($team->referral_code && $team->referral_code !== '')
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Referral code tim: <code class="bg-light p-1">{{ $team->referral_code }}</code>
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Important Notes -->
                    <div class="alert alert-danger">
                        <h6 class="alert-heading">
                            <i class="fas fa-exclamation-triangle me-2"></i>PENTING: SIMPAN BUKTI PENDAFTARAN
                        </h6>
                        <ul class="mb-0">
                            <li><strong>Screenshot halaman ini</strong> sebagai bukti pendaftaran</li>
                            @if($isCaptain)
                            <li><strong>Simpan referral code</strong> jika sudah ada</li>
                            @endif
                            <li>Data Anda akan diverifikasi oleh panitia dalam 1x24 jam</li>
                            <li>Jika ada masalah, hubungi panitia dengan menyertakan ID Pendaftaran</li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-3">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-primary btn-lg py-3">
                            <i class="fas fa-tachometer-alt me-2"></i>PERGI KE DASHBOARD
                        </a>
                        
                        <div class="row g-2">
                            @if($isCaptain && $referralCode && $referralCode !== '')
                            <div class="col-md-6">
                                <a href="{{ route('form.team.join') }}" class="btn btn-success w-100 py-3">
                                    <i class="fas fa-share-alt me-2"></i>BAGIKAN LINK GABUNG
                                </a>
                            </div>
                            @endif
                            <div class="{{ $isCaptain && $referralCode ? 'col-md-6' : 'col-12' }}">
                                <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary w-100 py-3">
                                    <i class="fas fa-home me-2"></i>KEMBALI KE HALAMAN UTAMA
                                </a>
                            </div>
                        </div>
                        
                        <button class="btn btn-outline-dark" onclick="printPage()">
                            <i class="fas fa-print me-2"></i>PRINT HALAMAN INI
                        </button>
                    </div>

                    <!-- Footer Info -->
                    <div class="mt-5 pt-4 border-top text-center">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-headset me-2"></i>BUTUH BANTUAN?
                        </h6>
                        <div class="row justify-content-center">
                            <div class="col-md-6 mb-2">
                                <i class="fas fa-envelope me-2"></i>
                                <a href="mailto:support@sbl.com" class="text-decoration-none">
                                    support@sbl.com
                                </a>
                            </div>
                            <div class="col-md-6 mb-2">
                                <i class="fab fa-whatsapp me-2 text-success"></i>
                                <a href="https://wa.me/6281234567890" class="text-decoration-none">
                                    +62 812-3456-7890
                                </a>
                            </div>
                        </div>
                        <small class="text-muted mt-3 d-block">
                            ID Pendaftaran: {{ $team->team_id }}-{{ $player->id }} • 
                            Tanggal: {{ now()->format('d/m/Y H:i') }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Success Icon */
    .success-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto;
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 60px;
        box-shadow: 0 10px 20px rgba(40, 167, 69, 0.3);
    }

    /* Info Item */
    .info-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 10px;
        border-radius: 8px;
        background: #f8f9fa;
        transition: all 0.3s;
    }
    
    .info-item:hover {
        background: #e9ecef;
        transform: translateY(-2px);
    }
    
    .info-item i {
        font-size: 24px;
        width: 40px;
        text-align: center;
    }

    /* Referral Code Display */
    .referral-code-display {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 2rem;
        border-radius: 15px;
        border: 3px dashed #28a745;
        margin: 2rem 0;
    }
    
    .referral-code-label {
        font-size: 1.1rem;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    
    .referral-code-value {
        font-size: 2.5rem;
        font-weight: 900;
        letter-spacing: 3px;
        color: #212529;
        background: white;
        padding: 1rem 2rem;
        border-radius: 10px;
        border: 2px solid #28a745;
        margin: 1rem 0;
        font-family: 'Courier New', monospace;
    }

    /* Step Cards */
    .step-card {
        text-align: center;
        padding: 1.5rem;
        border-radius: 10px;
        background: #f8f9fa;
        height: 100%;
        transition: all 0.3s;
    }
    
    .step-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .step-number {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin: 0 auto 1rem;
    }

    /* Buttons */
    .btn {
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .btn:hover {
        transform: translateY(-3px);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
    }

    /* Alerts */
    .alert {
        border-radius: 10px;
        border: none;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    /* Print Styles */
    @media print {
        .btn, .alert-dismissible, .d-print-none {
            display: none !important;
        }
        
        .card {
            border: 2px solid #000 !important;
            box-shadow: none !important;
        }
        
        .referral-code-value {
            font-size: 2rem !important;
            border: 3px solid #000 !important;
        }
        
        body {
            font-size: 12pt !important;
            background: white !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    // Copy Referral Code to Clipboard
    function copyReferralCode() {
        const referralCode = '{{ $referralCode }}';
        
        if (!referralCode) {
            Swal.fire({
                icon: 'error',
                title: 'Referral Code Tidak Tersedia',
                text: 'Referral code belum tersedia. Hubungi panitia.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
            return;
        }
        
        // Create temporary input
        const tempInput = document.createElement('input');
        tempInput.value = referralCode;
        document.body.appendChild(tempInput);
        
        // Select and copy
        tempInput.select();
        tempInput.setSelectionRange(0, 99999);
        
        try {
            const successful = document.execCommand('copy');
            if (successful) {
                // Show sweet alert
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil Disalin!',
                    html: `
                        <div class="text-center">
                            <h5 class="mb-3">Referral Code:</h5>
                            <div class="bg-light p-3 rounded">
                                <code class="fs-4 fw-bold">${referralCode}</code>
                            </div>
                            <p class="mt-3">Kode telah disalin ke clipboard.</p>
                        </div>
                    `,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#28a745'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyalin',
                    html: `
                        <div class="text-center">
                            <p>Gagal menyalin referral code secara otomatis.</p>
                            <div class="bg-light p-3 rounded mt-3">
                                <code class="fs-5 fw-bold">${referralCode}</code>
                            </div>
                            <p class="mt-3">Silakan salin manual kode di atas.</p>
                        </div>
                    `,
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#dc3545'
                });
            }
        } catch (err) {
            console.error('Copy failed:', err);
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyalin',
                text: 'Silakan salin manual: ' + referralCode,
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
        }
        
        // Clean up
        document.body.removeChild(tempInput);
    }

    // Print Page
    function printPage() {
        Swal.fire({
            title: 'Print Halaman',
            text: 'Pastikan semua informasi terlihat sebelum print.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Print',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                window.print();
            }
        });
    }

    // Auto-hide alerts after 10 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 10000);
        
        // Auto-scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });

        // Add beforeunload warning for Captain
        @if($isCaptain && $referralCode)
        window.addEventListener('beforeunload', function (e) {
            // Cancel the event
            e.preventDefault();
            // Chrome requires returnValue to be set
            e.returnValue = '⚠️ JANGAN TUTUP! Pastikan Anda telah menyimpan/screenshot referral code.';
        });
        @endif
    });
</script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush
@endsection