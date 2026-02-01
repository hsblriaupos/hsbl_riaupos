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
                            <strong>Setelah memasukkan referral code</strong>, Anda akan diminta untuk:
                            <ol class="mt-2 mb-0">
                                <li><strong>Pilih Posisi</strong> (Basket Putra, Basket Putri, atau Dancer)</li>
                                <li><strong>Isi Form Pendaftaran</strong> sesuai posisi yang dipilih</li>
                            </ol>
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
                        
                        <!-- Submit Button -->
                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-5 py-3">
                                <i class="fas fa-users me-2"></i> Lanjut ke Pilih Posisi
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
@endsection