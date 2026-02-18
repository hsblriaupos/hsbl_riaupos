@extends('user.form.layout')

@section('title', 'Daftar Tim HSBL')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Header -->
            <div class="text-center mb-4">
                <span class="badge bg-soft-primary text-primary px-3 py-1 mb-2 rounded-pill" style="font-size: 0.8rem;">
                    🏀 HSBL Registration
                </span>
                <h2 class="fw-bold text-dark mb-2" style="font-size: 2rem;">Daftar Tim HSBL</h2>
                <p class="text-muted" style="font-size: 1rem;">Pilih cara pendaftaran yang sesuai dengan kebutuhanmu!</p>
            </div>

            <div class="row g-3">
                <!-- Option 1: Create New Team -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow hover-lift">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <div class="icon-circle bg-gradient-orange mb-3" style="width: 70px; height: 70px;">
                                    <i class="fas fa-plus-circle fa-2x text-white"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-1">Buat Tim Baru</h4>
                                <span class="badge bg-soft-warning text-warning px-2 py-1 rounded-pill mb-2" style="font-size: 0.7rem;">
                                    👑 Kamu akan jadi Leader
                                </span>
                            </div>
                            
                            <p class="text-muted text-center mb-3" style="font-size: 0.9rem;">
                                Pertama kali daftarin sekolah? Pilih ini! Kamu yang akan mengelola tim dan membayar biaya pendaftaran.
                            </p>

                            <div class="feature-list mb-3 p-3">
                                <div class="d-flex align-items-center mb-1 small">
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.9rem;">Buat tim baru untuk sekolahmu</span>
                                </div>
                                <div class="d-flex align-items-center mb-1 small">
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.9rem;">Dapatkan referral code untuk anggota</span>
                                </div>
                                <div class="d-flex align-items-center small">
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.9rem;">Kelola anggota tim dengan mudah</span>
                                </div>
                            </div>

                            <a href="{{ route('form.team.create') }}" 
                               class="btn btn-warning w-100 py-2 fw-semibold hover-scale" style="font-size: 0.95rem;">
                                Buat Tim Baru
                                <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Option 2: Join Existing Team -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow hover-lift">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <div class="icon-circle bg-gradient-teal mb-3" style="width: 70px; height: 70px;">
                                    <i class="fas fa-users fa-2x text-white"></i>
                                </div>
                                <h4 class="fw-bold text-dark mb-1">Gabung ke Tim</h4>
                                <span class="badge bg-soft-teal text-teal px-2 py-1 rounded-pill mb-2" style="font-size: 0.7rem;">
                                    🔗 Pakai Referral Code
                                </span>
                            </div>
                            
                            <p class="text-muted text-center mb-3" style="font-size: 0.9rem;">
                                Udah ada temen yang daftar duluan? Gabung aja pake referral code dari leader tim!
                            </p>

                            <div class="feature-list mb-3 p-3">
                                <div class="d-flex align-items-center mb-1 small">
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.9rem;">Masuk ke tim yang sudah ada</span>
                                </div>
                                <div class="d-flex align-items-center mb-1 small">
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.9rem;">Gak perlu bayar pendaftaran</span>
                                </div>
                                <div class="d-flex align-items-center small">
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.9rem;">Langsung jadi anggota tim</span>
                                </div>
                            </div>

                            <a href="{{ route('form.team.join') }}" 
                               class="btn btn-teal w-100 py-2 fw-semibold hover-scale" style="font-size: 0.95rem;">
                                Gabung ke Tim
                                <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Floating Download Button -->
            <div class="floating-download" id="floatingDownloadBtn">
                <div class="pulse-ring"></div>
                <div class="pulse-ring delay"></div>
                
                <a href="{{ route('user.download_terms') }}" 
                   class="download-btn"
                   id="downloadSnKButton"
                   target="_blank"
                   aria-label="Download Template">
                    <i class="fas fa-file-pdf"></i>
                </a>
                
                <span class="badge-penting">PENTING!</span>
                <span class="download-label">Download Template</span>
            </div>

            <!-- Informasi Penting -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card border-0 shadow-sm bg-soft-primary">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start">
                                <div class="info-icon bg-primary text-white rounded-circle me-2" style="min-width: 30px; height: 30px; font-size: 0.9rem;">
                                    <i class="fas fa-lightbulb"></i>
                                </div>
                                <div>
                                    <h6 class="text-primary fw-bold mb-2" style="font-size: 0.95rem;">💡 Informasi Penting:</h6>
                                    <div class="row g-1">
                                        <div class="col-md-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-1 d-flex align-items-center">
                                                    <i class="fas fa-circle text-primary me-1" style="font-size: 6px;"></i>
                                                    <span style="font-size: 0.85rem;">Setiap kategori punya Leader sendiri (Basket Putra, Putri, Dancer)</span>
                                                </li>
                                                <li class="mb-1 d-flex align-items-center">
                                                    <i class="fas fa-circle text-primary me-1" style="font-size: 6px;"></i>
                                                    <span style="font-size: 0.85rem;">Hanya Leader yang bayar biaya pendaftaran tim</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-1 d-flex align-items-center">
                                                    <i class="fas fa-circle text-primary me-1" style="font-size: 6px;"></i>
                                                    <span style="font-size: 0.85rem;">Referral code langsung didapat setelah Leader daftar</span>
                                                </li>
                                                <li class="mb-1 d-flex align-items-center">
                                                    <i class="fas fa-circle text-primary me-1" style="font-size: 6px;"></i>
                                                    <span style="font-size: 0.85rem;">Leader bisa bagikan referral code ke teman satu sekolah</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Colors */
    .bg-gradient-orange {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%);
    }
    
    .bg-gradient-teal {
        background: linear-gradient(135deg, #4ECDC4 0%, #556270 100%);
    }
    
    .bg-soft-primary {
        background-color: rgba(79, 70, 229, 0.1);
    }
    
    .bg-soft-warning {
        background-color: rgba(255, 159, 67, 0.1);
    }
    
    .bg-soft-teal {
        background-color: rgba(78, 205, 196, 0.1);
    }
    
    .text-teal {
        color: #4ECDC4 !important;
    }
    
    .btn-teal {
        background: linear-gradient(135deg, #4ECDC4 0%, #45b7aa 100%);
        border: none;
        color: white;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }
    
    .btn-teal:hover {
        background: linear-gradient(135deg, #45b7aa 0%, #3aa396 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(78, 205, 196, 0.3);
    }
    
    .btn-warning {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%);
        border: none;
        color: white;
        transition: all 0.3s ease;
        font-size: 0.95rem;
    }
    
    .btn-warning:hover {
        background: linear-gradient(135deg, #ff5252 0%, #ff7b3f 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(255, 107, 107, 0.3);
    }
    
    .icon-circle {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .icon-circle i {
        font-size: 2rem;
    }
    
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1) !important;
    }
    
    .hover-lift:hover .icon-circle {
        transform: scale(1.05);
    }
    
    .hover-scale {
        transition: transform 0.3s ease;
    }
    
    .hover-scale:hover {
        transform: scale(1.01);
    }
    
    .feature-list {
        background-color: #f8f9fa;
        padding: 0.75rem !important;
        border-radius: 0.75rem;
    }
    
    .info-icon {
        min-width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
        font-size: 0.7rem;
    }
    
    .card {
        border-radius: 1.25rem;
        overflow: hidden;
    }
    
    .card-body {
        border-radius: 1.25rem;
    }

    /* Floating Download Button Styles */
    .floating-download {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 9999;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    .download-btn {
        width: 65px;
        height: 65px;
        background: linear-gradient(135deg, #ff416c, #ff4b2b);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 28px;
        box-shadow: 0 10px 25px rgba(255, 75, 43, 0.4);
        border: 3px solid white;
        transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        text-decoration: none;
        position: relative;
        z-index: 2;
        cursor: pointer;
    }

    .download-btn:hover {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 15px 35px rgba(255, 65, 108, 0.6);
        color: white;
    }

    .download-btn:active {
        transform: scale(0.95);
    }

    .download-btn i {
        filter: drop-shadow(0 2px 5px rgba(0,0,0,0.2));
    }

    .pulse-ring {
        position: absolute;
        top: 0;
        left: 0;
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: rgba(255, 75, 43, 0.3);
        animation: pulse 2s infinite;
        z-index: 1;
    }

    .pulse-ring.delay {
        animation: pulse 2s infinite 0.5s;
        background: rgba(255, 65, 108, 0.3);
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 0.7;
        }
        50% {
            transform: scale(1.5);
            opacity: 0.3;
        }
        100% {
            transform: scale(1.8);
            opacity: 0;
        }
    }

    .badge-penting {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        font-size: 11px;
        font-weight: bold;
        padding: 4px 8px;
        border-radius: 30px;
        box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
        border: 2px solid white;
        z-index: 3;
        animation: blink 1.5s infinite;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    @keyframes blink {
        0% { opacity: 1; }
        50% { opacity: 0.7; transform: scale(0.95); }
        100% { opacity: 1; }
    }

    .download-label {
        background: #1e293b;
        color: white;
        padding: 8px 15px;
        border-radius: 30px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        border-left: 4px solid #ff4b2b;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transform: translateX(10px);
        transition: all 0.3s ease;
        position: absolute;
        bottom: 15px;
        right: 80px;
        pointer-events: none;
        z-index: 1;
    }

    .download-label::after {
        content: '';
        position: absolute;
        top: 50%;
        right: -8px;
        transform: translateY(-50%);
        border-left: 8px solid #1e293b;
        border-top: 8px solid transparent;
        border-bottom: 8px solid transparent;
    }

    .floating-download:hover .download-label {
        opacity: 1;
        visibility: visible;
        transform: translateX(0);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .icon-circle {
            width: 60px;
            height: 60px;
        }
        
        .icon-circle i {
            font-size: 1.5rem;
        }
        
        h2 {
            font-size: 1.5rem !important;
        }
        
        .floating-download {
            bottom: 20px;
            right: 20px;
        }
        
        .download-btn {
            width: 55px;
            height: 55px;
            font-size: 24px;
        }
        
        .pulse-ring {
            width: 55px;
            height: 55px;
        }
        
        .badge-penting {
            font-size: 10px;
            padding: 3px 6px;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const downloadBtn = document.getElementById('downloadSnKButton');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function(e) {
            const originalIcon = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
            
            setTimeout(() => {
                this.innerHTML = originalIcon;
            }, 1500);
        });
    }
});
</script>
@endsection