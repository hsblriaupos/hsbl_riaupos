@extends('user.form.layout')

@section('title', 'Pembayaran - HSBL')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border">
                <div class="card-header bg-white border-bottom py-3">
                    <h5 class="mb-0 fw-bold">💰 Pembayaran Registrasi Tim</h5>
                </div>
                
                <div class="card-body p-4">
                    <div class="alert alert-success mb-4">
                        <i class="fas fa-check-circle me-2"></i>
                        Tim <strong>{{ $team->school_name }}</strong> ({{ $team->team_category }}) berhasil dibuat!
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="fw-bold mb-3">Informasi Pembayaran</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="40%">Sekolah</th>
                                    <td>{{ $team->school_name }}</td>
                                </tr>
                                <tr>
                                    <th>Kategori Tim</th>
                                    <td>{{ $team->team_category }}</td>
                                </tr>
                                <tr>
                                    <th>Season</th>
                                    <td>{{ $team->season }}</td>
                                </tr>
                                <tr>
                                    <th>Biaya Registrasi</th>
                                    <td class="text-success fw-bold">Rp 500.000</td>
                                </tr>
                                <tr>
                                    <th>Metode Pembayaran</th>
                                    <td>
                                        <div class="mb-2">
                                            <strong>Bank Transfer</strong><br>
                                            BCA: 123-456-7890 (a.n. HSBL Competition)<br>
                                            Mandiri: 098-765-4321 (a.n. HSBL Competition)
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <form action="{{ route('form.payment.process', $team->team_id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">Upload Bukti Pembayaran</h6>
                            <div class="mb-3">
                                <label for="payment_proof" class="form-label fw-medium">
                                    Bukti Transfer <span class="text-danger">*</span>
                                </label>
                                <input type="file" 
                                       class="form-control @error('payment_proof') is-invalid @enderror" 
                                       id="payment_proof" 
                                       name="payment_proof" 
                                       accept=".jpg,.jpeg,.png,.pdf" 
                                       required>
                                <div class="form-text">Format: JPG, PNG, atau PDF, Maksimal: 2MB</div>
                                @error('payment_proof')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Setelah pembayaran berhasil:</strong><br>
                            1. Anda akan menjadi Kapten tim<br>
                            2. Anda akan mendapatkan referral code<br>
                            3. Anda akan diarahkan ke form data Kapten<br>
                            4. Referral code bisa digunakan untuk semua kategori di sekolah ini
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('form.team.create') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-check me-2"></i>Konfirmasi Pembayaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection