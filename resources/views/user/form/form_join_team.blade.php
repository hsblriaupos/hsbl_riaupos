@extends('user.form.layout')

@section('title', 'Gabung ke Tim - SBL')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <!-- Card Simple -->
            <div class="card border-0 shadow-sm" style="border-radius: 16px;">
                <!-- Header -->
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('form.team.choice') }}" class="text-secondary me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h5 class="fw-semibold mb-1">Gabung ke Tim</h5>
                            <p class="text-muted small mb-0">Masukkan referral code dari Leader</p>
                        </div>
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body p-4">
                    <form action="{{ route('form.team.join.submit') }}" method="POST">
                        @csrf

                        <!-- Info Singkat -->
                        <div class="bg-light rounded-3 p-3 mb-4">
                            <div class="d-flex">
                                <i class="fas fa-info-circle text-primary me-2 mt-1"></i>
                                <div>
                                    <small class="text-dark fw-medium d-block mb-1">Setelah memasukkan referral code:</small>
                                    <small class="text-muted d-block">1. Pilih posisi (Basket/Dancer/Official)</small>
                                    <small class="text-muted d-block">2. Isi data diri</small>
                                </div>
                            </div>
                        </div>

                        <!-- Referral Code Input -->
                        <div class="mb-4">
                            <label class="form-label small fw-medium mb-2">
                                Referral Code <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('referral_code') is-invalid @enderror" 
                                   id="referral_code" 
                                   name="referral_code" 
                                   value="{{ old('referral_code') }}" 
                                   required
                                   placeholder="Contoh: SBL-SMAN1-2024"
                                   style="text-transform: uppercase;">
                            @error('referral_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-lightbulb me-1"></i>
                                Minta referral code dari Leader tim
                            </small>
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary flex-fill">
                                Batal
                            </a>
                            <button type="submit" class="btn btn-primary flex-fill">
                                Lanjut <i class="fas fa-arrow-right ms-1"></i>
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <!-- Link Buat Tim -->
                    <div class="text-center">
                        <small class="text-muted d-block mb-2">Belum punya referral code?</small>
                        <a href="{{ route('form.team.create') }}" class="btn btn-link text-primary p-0">
                            Buat tim baru sebagai Leader
                        </a>
                    </div>
                </div>
            </div>

            <!-- Help Text -->
            <div class="text-center mt-3">
                <small class="text-muted">
                    <i class="fas fa-question-circle me-1"></i>
                    Butuh bantuan? <a href="#" class="text-decoration-none">Hubungi Admin</a>
                </small>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const input = document.getElementById('referral_code');
        
        // Auto uppercase
        if (input) {
            input.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });
        }
    });
</script>
@endpush
@endsection