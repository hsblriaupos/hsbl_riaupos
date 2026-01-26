@extends('user.form.layout')
@section('title', 'Gabung ke Tim - HSBL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow border-0">
                <!-- Header -->
                <div class="card-header bg-gradient-success text-white py-4">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('form.team.choice') }}" class="text-white me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h2 class="mb-0">🤝 Gabung ke Tim</h2>
                            <p class="mb-0 opacity-75">Masukkan referral code dari Leader tim</p>
                        </div>
                    </div>
                </div>
                
                <!-- Form -->
                <div class="card-body p-5">
                    <form action="{{ route('form.team.join.submit') }}" method="POST">
                        @csrf
                        
                        <!-- Info Box -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Referral code</strong> bisa kamu dapatkan dari teman yang sudah 
                            mendaftar sebagai Leader terlebih dahulu.
                        </div>
                        
                        <!-- Referral Code Input -->
                        <div class="mb-4">
                            <label for="referral_code" class="form-label">
                                Masukkan Referral Code <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-key text-success"></i>
                                </span>
                                <input type="text" 
                                       class="form-control @error('referral_code') is-invalid @enderror" 
                                       id="referral_code" 
                                       name="referral_code" 
                                       value="{{ old('referral_code') }}" 
                                       required
                                       placeholder="Contoh: SMA-NEGERI-1-JAKARTA-ABCD"
                                       style="font-family: monospace; letter-spacing: 1px;">
                            </div>
                            @error('referral_code')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-lightbulb me-1"></i>
                                Minta referral code ke teman yang sudah daftar sebagai Leader
                            </small>
                        </div>
                        
                        <!-- What happens next -->
                        <div class="card border-0 bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title text-success mb-3">
                                    <i class="fas fa-forward me-2"></i>Apa yang terjadi selanjutnya?
                                </h6>
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <span class="badge bg-success rounded-circle p-2">1</span>
                                    </div>
                                    <div>
                                        <strong>Verifikasi Kode</strong>
                                        <p class="text-muted mb-0">Kami akan cek kevalidan referral code</p>
                                    </div>
                                </div>
                                <div class="d-flex mb-3">
                                    <div class="me-3">
                                        <span class="badge bg-success rounded-circle p-2">2</span>
                                    </div>
                                    <div>
                                        <strong>Isi Data Pribadi</strong>
                                        <p class="text-muted mb-0">Isi form data anggota tim</p>
                                    </div>
                                </div>
                                <div class="d-flex">
                                    <div class="me-3">
                                        <span class="badge bg-success rounded-circle p-2">3</span>
                                    </div>
                                    <div>
                                        <strong>Upload Dokumen</strong>
                                        <p class="text-muted mb-0">Upload dokumen yang diperlukan</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-5 py-3">
                                <i class="fas fa-users me-2"></i> Gabung ke Tim
                            </button>
                            <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary ms-3">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Additional Help -->
            <div class="text-center mt-4">
                <p class="text-muted">
                    Belum punya referral code? 
                    <a href="{{ route('form.team.create') }}" class="text-success fw-bold">
                        Buat tim baru sebagai Leader
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.card-header {
    border-radius: 0 !important;
}
.input-group-text {
    border-right: none;
}
.form-control {
    border-left: none;
    padding-left: 0;
}
.bg-gradient-success {
    background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%);
}
.badge.rounded-circle {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
}
</style>
@endsection