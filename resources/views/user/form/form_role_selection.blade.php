@extends('user.form.layout')

@section('title', 'Pilih Posisi - HSBL')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Card -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <!-- Header Simple -->
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('form.team.join') }}" class="text-secondary me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h5 class="fw-semibold mb-1">Pilih Posisi & Kategori</h5>
                            <p class="text-muted small mb-0">Pilih peran Anda dalam tim</p>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body p-4">
                    <!-- Tampilkan error jika ada -->
                    @if(session('error'))
                        <div class="alert alert-danger bg-soft-danger border-0 py-2 px-3 mb-4" style="border-radius: 8px;">
                            <small><i class="fas fa-exclamation-circle me-1"></i>{{ session('error') }}</small>
                        </div>
                    @endif

                    <form action="{{ route('form.team.join.select-role') }}" method="POST">
                        @csrf
                        <input type="hidden" name="referral_code" value="{{ $referralCode ?? old('referral_code') }}">

                        <!-- Info Singkat -->
                        <div class="bg-light rounded-3 p-3 mb-4">
                            <div class="d-flex">
                                <i class="fas fa-info-circle text-primary me-2 mt-1"></i>
                                <div>
                                    <small class="text-muted">Pilih posisi yang sesuai. Form pendaftaran akan menyesuaikan pilihan Anda.</small>
                                </div>
                            </div>
                        </div>

                        <!-- Pilihan Posisi - Semua Ukuran Sama -->
                        <div class="row g-3 mb-4">
                            <!-- Basket Putra -->
                            <div class="col-md-6">
                                <div class="position-card">
                                    <input type="radio" 
                                           class="position-radio" 
                                           name="team_category" 
                                           id="basket_putra" 
                                           value="Basket Putra"
                                           {{ old('team_category') == 'Basket Putra' ? 'checked' : '' }}
                                           required>
                                    <label for="basket_putra" class="position-label">
                                        <div class="p-3">
                                            <div class="text-center mb-2">
                                                <i class="fas fa-basketball-ball text-primary" style="font-size: 2rem;"></i>
                                            </div>
                                            <h6 class="fw-semibold text-center mb-1">Basket Putra</h6>
                                            <p class="text-muted small text-center mb-2">Pemain basket putra</p>
                                            <div class="text-center">
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">Putra</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Basket Putri -->
                            <div class="col-md-6">
                                <div class="position-card">
                                    <input type="radio" 
                                           class="position-radio" 
                                           name="team_category" 
                                           id="basket_putri" 
                                           value="Basket Putri"
                                           {{ old('team_category') == 'Basket Putri' ? 'checked' : '' }}>
                                    <label for="basket_putri" class="position-label">
                                        <div class="p-3">
                                            <div class="text-center mb-2">
                                                <i class="fas fa-basketball-ball text-danger" style="font-size: 2rem;"></i>
                                            </div>
                                            <h6 class="fw-semibold text-center mb-1">Basket Putri</h6>
                                            <p class="text-muted small text-center mb-2">Pemain basket putri</p>
                                            <div class="text-center">
                                                <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1">Putri</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Dancer -->
                            <div class="col-md-6">
                                <div class="position-card">
                                    <input type="radio" 
                                           class="position-radio" 
                                           name="team_category" 
                                           id="dancer" 
                                           value="Dancer"
                                           {{ old('team_category') == 'Dancer' ? 'checked' : '' }}>
                                    <label for="dancer" class="position-label">
                                        <div class="p-3">
                                            <div class="text-center mb-2">
                                                <i class="fas fa-music text-success" style="font-size: 2rem;"></i>
                                            </div>
                                            <h6 class="fw-semibold text-center mb-1">Dancer</h6>
                                            <p class="text-muted small text-center mb-2">Tim penari</p>
                                            <div class="text-center">
                                                <span class="badge bg-success bg-opacity-10 text-success px-2 py-1">Dancer</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Official -->
                            <div class="col-md-6">
                                <div class="position-card">
                                    <input type="radio" 
                                           class="position-radio" 
                                           name="team_category" 
                                           id="official" 
                                           value="Official"
                                           {{ old('team_category') == 'Official' ? 'checked' : '' }}>
                                    <label for="official" class="position-label">
                                        <div class="p-3">
                                            <div class="text-center mb-2">
                                                <i class="fas fa-clipboard-list text-warning" style="font-size: 2rem;"></i>
                                            </div>
                                            <h6 class="fw-semibold text-center mb-1">Official</h6>
                                            <p class="text-muted small text-center mb-2">Pelatih/manajer tim</p>
                                            <div class="text-center">
                                                <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1">Official</span>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Info Leader -->
                        <div class="alert alert-primary bg-opacity-10 border-0 py-2 px-3 mb-4" style="background: rgba(67, 97, 238, 0.05); border-radius: 8px;">
                            <small>
                                <i class="fas fa-star text-primary me-1"></i>
                                <span class="text-dark">Jika belum ada Leader, Anda bisa mendaftar sebagai Leader/Kapten tim</span>
                            </small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('form.team.join') }}" class="btn btn-outline-secondary flex-fill">
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary flex-fill" id="submitBtn">
                                Lanjut <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Text -->
            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="fas fa-question-circle me-1"></i>
                    Bingung? Hubungi Kapten tim Anda
                </small>
            </div>
        </div>
    </div>
</div>

<style>
    /* Position Cards */
    .position-card {
        position: relative;
        width: 100%;
    }
    
    .position-radio {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }
    
    .position-label {
        display: block;
        cursor: pointer;
        border: 2px solid #e9ecef;
        border-radius: 12px;
        background: white;
        transition: all 0.2s;
        height: 100%;
    }
    
    .position-label:hover {
        border-color: #4361ee;
        background: #f8f9ff;
    }
    
    .position-radio:checked + .position-label {
        border-color: #4361ee;
        background: #f0f4ff;
        box-shadow: 0 4px 8px rgba(67, 97, 238, 0.1);
    }
    
    /* Badges */
    .badge {
        font-weight: normal;
        border-radius: 20px;
    }
    
    .bg-soft-danger {
        background: rgba(249, 65, 68, 0.1);
    }
    
    /* Buttons */
    .btn {
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        border-radius: 8px;
    }
    
    .btn-primary {
        background: #4361ee;
        border-color: #4361ee;
    }
    
    .btn-primary:hover {
        background: #3a56d4;
        border-color: #3a56d4;
    }
    
    .btn-outline-secondary {
        border-color: #dee2e6;
        color: #6c757d;
    }
    
    .btn-outline-secondary:hover {
        background: #f8f9fa;
        border-color: #adb5bd;
        color: #495057;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .position-label {
            padding: 0.5rem !important;
        }
        
        .position-label i {
            font-size: 1.5rem !important;
        }
        
        .position-label h6 {
            font-size: 0.9rem;
        }
        
        .position-label p {
            font-size: 0.7rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const submitBtn = document.getElementById('submitBtn');
        
        form.addEventListener('submit', function(e) {
            // Tampilkan loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
            
            // Form akan submit secara normal
        });
    });
</script>
@endsection