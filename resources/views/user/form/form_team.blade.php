@extends('user.form.layout')

@section('title', 'Daftar Tim HSBL')

@section('content')
<div class="container py-4"> <!-- py-5 → py-4 -->
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Header dengan desain lebih menarik - DIPERKECIL -->
            <div class="text-center mb-4"> <!-- mb-5 → mb-4 -->
                <span class="badge bg-soft-primary text-primary px-3 py-1 mb-2 rounded-pill" style="font-size: 0.8rem;"> <!-- px-4 py-2 → px-3 py-1, mb-3 → mb-2 -->
                    🏀 HSBL Registration
                </span>
                <h2 class="fw-bold text-dark mb-2" style="font-size: 2rem;">Daftar Tim HSBL</h2> <!-- h1 display-5 → h2, mb-3 → mb-2 -->
                <p class="text-muted" style="font-size: 1rem;">Pilih cara pendaftaran yang sesuai dengan kebutuhanmu!</p> <!-- lead → normal -->
            </div>

            <div class="row g-3"> <!-- g-4 → g-3 -->
                <!-- Option 1: Create New Team -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow hover-lift"> <!-- shadow-lg → shadow -->
                        <div class="card-body p-4"> <!-- p-5 → p-4 -->
                            <div class="text-center mb-3"> <!-- mb-4 → mb-3 -->
                                <div class="icon-circle bg-gradient-orange mb-3" style="width: 70px; height: 70px;"> <!-- width 80px → 70px, mb-4 → mb-3 -->
                                    <i class="fas fa-plus-circle fa-2x text-white"></i> <!-- fa-3x → fa-2x -->
                                </div>
                                <h4 class="fw-bold text-dark mb-1">Buat Tim Baru</h4> <!-- h3 → h4, mb-2 → mb-1 -->
                                <span class="badge bg-soft-warning text-warning px-2 py-1 rounded-pill mb-2" style="font-size: 0.7rem;"> <!-- px-3 py-2 → px-2 py-1, mb-3 → mb-2 -->
                                    👑 Kamu akan jadi Leader
                                </span>
                            </div>
                            
                            <p class="text-muted text-center mb-3" style="font-size: 0.9rem;"> <!-- mb-4 → mb-3 -->
                                Pertama kali daftarin sekolah? Pilih ini! Kamu yang akan mengelola tim dan membayar biaya pendaftaran.
                            </p>

                            <div class="feature-list mb-3 p-3" style="padding: 0.75rem !important;"> <!-- mb-4 → mb-3, p-1.25rem → p-3 -->
                                <div class="d-flex align-items-center mb-1 small"> <!-- mb-2 → mb-1 -->
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i> <!-- me-2 → me-1 -->
                                    <span style="font-size: 0.9rem;">Buat tim baru untuk sekolahmu</span>
                                </div>
                                <div class="d-flex align-items-center mb-1 small"> <!-- mb-2 → mb-1 -->
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.9rem;">Dapatkan referral code untuk anggota</span>
                                </div>
                                <div class="d-flex align-items-center small">
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.9rem;">Kelola anggota tim dengan mudah</span>
                                </div>
                            </div>

                            <a href="{{ route('form.team.create') }}" 
                               class="btn btn-warning w-100 py-2 fw-semibold hover-scale" style="font-size: 0.95rem;"> <!-- py-3 → py-2, fw-bold → fw-semibold -->
                                Buat Tim Baru
                                <i class="fas fa-arrow-right ms-1"></i> <!-- ms-2 → ms-1 -->
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Option 2: Join Existing Team -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow hover-lift"> <!-- shadow-lg → shadow -->
                        <div class="card-body p-4"> <!-- p-5 → p-4 -->
                            <div class="text-center mb-3"> <!-- mb-4 → mb-3 -->
                                <div class="icon-circle bg-gradient-teal mb-3" style="width: 70px; height: 70px;"> <!-- width 80px → 70px, mb-4 → mb-3 -->
                                    <i class="fas fa-users fa-2x text-white"></i> <!-- fa-3x → fa-2x -->
                                </div>
                                <h4 class="fw-bold text-dark mb-1">Gabung ke Tim</h4> <!-- h3 → h4, mb-2 → mb-1 -->
                                <span class="badge bg-soft-teal text-teal px-2 py-1 rounded-pill mb-2" style="font-size: 0.7rem;"> <!-- px-3 py-2 → px-2 py-1, mb-3 → mb-2 -->
                                    🔗 Pakai Referral Code
                                </span>
                            </div>
                            
                            <p class="text-muted text-center mb-3" style="font-size: 0.9rem;"> <!-- mb-4 → mb-3 -->
                                Udah ada temen yang daftar duluan? Gabung aja pake referral code dari leader tim!
                            </p>

                            <div class="feature-list mb-3 p-3" style="padding: 0.75rem !important;"> <!-- mb-4 → mb-3, p-1.25rem → p-3 -->
                                <div class="d-flex align-items-center mb-1 small"> <!-- mb-2 → mb-1 -->
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i> <!-- me-2 → me-1 -->
                                    <span style="font-size: 0.9rem;">Masuk ke tim yang sudah ada</span>
                                </div>
                                <div class="d-flex align-items-center mb-1 small"> <!-- mb-2 → mb-1 -->
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.9rem;">Gak perlu bayar pendaftaran</span>
                                </div>
                                <div class="d-flex align-items-center small">
                                    <i class="fas fa-check-circle text-success me-1" style="font-size: 0.9rem;"></i>
                                    <span style="font-size: 0.9rem;">Langsung jadi anggota tim</span>
                                </div>
                            </div>

                            <a href="{{ route('form.team.join') }}" 
                               class="btn btn-teal w-100 py-2 fw-semibold hover-scale" style="font-size: 0.95rem;"> <!-- py-3 → py-2, fw-bold → fw-semibold -->
                                Gabung ke Tim
                                <i class="fas fa-arrow-right ms-1"></i> <!-- ms-2 → ms-1 -->
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informasi Penting dengan desain lebih menarik - DIPERKECIL -->
            <div class="row mt-4"> <!-- mt-5 → mt-4 -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm bg-soft-primary">
                        <div class="card-body p-3"> <!-- p-4 → p-3 -->
                            <div class="d-flex align-items-start">
                                <div class="info-icon bg-primary text-white rounded-circle me-2" style="min-width: 30px; height: 30px; font-size: 0.9rem;"> <!-- me-3 → me-2, ukuran icon diperkecil -->
                                    <i class="fas fa-lightbulb"></i>
                                </div>
                                <div>
                                    <h6 class="text-primary fw-bold mb-2" style="font-size: 0.95rem;">💡 Informasi Penting:</h6> <!-- h5 → h6, mb-3 → mb-2 -->
                                    <div class="row g-1"> <!-- tambah gap kecil -->
                                        <div class="col-md-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-1 d-flex align-items-center"> <!-- mb-2 → mb-1 -->
                                                    <i class="fas fa-circle text-primary me-1" style="font-size: 6px;"></i> <!-- me-2 → me-1, font-size 8px → 6px -->
                                                    <span style="font-size: 0.85rem;">Setiap kategori punya Leader sendiri (Basket Putra, Putri, Dancer)</span>
                                                </li>
                                                <li class="mb-1 d-flex align-items-center"> <!-- mb-2 → mb-1 -->
                                                    <i class="fas fa-circle text-primary me-1" style="font-size: 6px;"></i>
                                                    <span style="font-size: 0.85rem;">Hanya Leader yang bayar biaya pendaftaran tim</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <ul class="list-unstyled mb-0">
                                                <li class="mb-1 d-flex align-items-center"> <!-- mb-2 → mb-1 -->
                                                    <i class="fas fa-circle text-primary me-1" style="font-size: 6px;"></i>
                                                    <span style="font-size: 0.85rem;">Referral code langsung didapat setelah Leader daftar</span>
                                                </li>
                                                <li class="mb-1 d-flex align-items-center"> <!-- mb-2 → mb-1 -->
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
    /* Custom Colors - TETAP SAMA */
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
        font-size: 0.95rem; /* ditambah */
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
        font-size: 0.95rem; /* ditambah */
    }
    
    .btn-warning:hover {
        background: linear-gradient(135deg, #ff5252 0%, #ff7b3f 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(255, 107, 107, 0.3);
    }
    
    .icon-circle {
        width: 70px; /* 80px → 70px */
        height: 70px; /* 80px → 70px */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }
    
    .icon-circle i {
        font-size: 2rem; /* 2.5rem → 2rem */
    }
    
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px); /* -10px → -5px */
        box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1) !important; /* lebih kecil */
    }
    
    .hover-lift:hover .icon-circle {
        transform: scale(1.05); /* 1.1 → 1.05 */
    }
    
    .hover-scale {
        transition: transform 0.3s ease;
    }
    
    .hover-scale:hover {
        transform: scale(1.01); /* 1.02 → 1.01 */
    }
    
    .feature-list {
        background-color: #f8f9fa;
        padding: 0.75rem !important; /* 1.25rem → 0.75rem */
        border-radius: 0.75rem; /* 1rem → 0.75rem */
    }
    
    .info-icon {
        min-width: 30px; /* 40px → 30px */
        height: 30px; /* 40px → 30px */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem; /* 1.2rem → 0.9rem */
    }
    
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
        font-size: 0.7rem; /* default → 0.7rem */
    }
    
    .card {
        border-radius: 1.25rem; /* 1.5rem → 1.25rem */
        overflow: hidden;
    }
    
    .card-body {
        border-radius: 1.25rem; /* 1.5rem → 1.25rem */
    }
    
    /* Responsive tambahan */
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
    }
</style>
@endsection