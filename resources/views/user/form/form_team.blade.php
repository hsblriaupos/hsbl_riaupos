@extends('user.form.layout')

@section('title', 'Daftar Tim HSBL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-gradient-primary text-white py-4">
                    <h2 class="text-center mb-0">🎯 Daftar Tim HSBL</h2>
                    <p class="text-center mb-0 mt-2 opacity-75">Pilih cara pendaftaran kamu yaa!</p>
                </div>
                
                <div class="card-body p-5">
                    <div class="row">
                        <!-- Option 1: Create New Team -->
                        <div class="col-md-6 mb-4">
                            <a href="{{ route('form.team.create') }}" class="card h-100 text-decoration-none border-primary border-2 hover-shadow">
                                <div class="card-body text-center py-5">
                                    <div class="mb-4">
                                        <i class="fas fa-plus-circle fa-4x text-primary"></i>
                                    </div>
                                    <h4 class="card-title text-primary mb-3">🚀 Buat Tim Baru</h4>
                                    <p class="card-text text-muted">
                                        Kamu yang pertama kali daftarin sekolah? Pilih ini!<br>
                                        <strong>Kamu akan jadi Leader</strong> dan bayar biaya pendaftaran.
                                    </p>
                                    <div class="mt-4">
                                        <span class="badge bg-primary">Leader</span>
                                        <span class="badge bg-info">Pembayaran</span>
                                        <span class="badge bg-success">Buat Referral Code</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Option 2: Join Existing Team -->
                        <div class="col-md-6 mb-4">
                            <a href="{{ route('form.team.join') }}" class="card h-100 text-decoration-none border-success border-2 hover-shadow">
                                <div class="card-body text-center py-5">
                                    <div class="mb-4">
                                        <i class="fas fa-users fa-4x text-success"></i>
                                    </div>
                                    <h4 class="card-title text-success mb-3">🤝 Gabung ke Tim</h4>
                                    <p class="card-text text-muted">
                                        Sudah ada teman yang daftar duluan?<br>
                                        <strong>Masukkan referral code</strong> untuk bergabung.
                                    </p>
                                    <div class="mt-4">
                                        <span class="badge bg-success">Member</span>
                                        <span class="badge bg-secondary">Gratis</span>
                                        <span class="badge bg-warning">Butuh Kode</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Informasi Penting -->
                    <div class="alert alert-info mt-4">
                        <h5 class="alert-heading">💡 Informasi Penting:</h5>
                        <ul class="mb-0">
                            <li>Setiap kategori (Basket Putra, Basket Putri, Dancer) memiliki Leader sendiri</li>
                            <li>Hanya Leader yang membayar biaya pendaftaran tim</li>
                            <li>Referral code akan diberikan setelah Leader mendaftar</li>
                            <li>Leader bisa membagikan referral code ke teman satu sekolah</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.hover-shadow {
    transition: transform 0.3s, box-shadow 0.3s;
}
.hover-shadow:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
}
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
@endsection