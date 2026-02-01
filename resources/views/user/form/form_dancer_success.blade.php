@extends('user.form.layout')

@section('title', 'Pendaftaran Berhasil - HSBL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5 text-center">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <div class="bg-success bg-gradient rounded-circle d-inline-flex align-items-center justify-content-center" 
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-check fa-3x text-white"></i>
                        </div>
                    </div>
                    
                    <!-- Success Message -->
                    <h2 class="text-success mb-3">🎉 Pendaftaran Berhasil!</h2>
                    <p class="lead mb-4">
                        Terima kasih telah mendaftar sebagai <strong>Dancer</strong> dari
                        <strong>{{ $team->school_name }}</strong>
                    </p>
                    
                    <!-- Dancer Details -->
                    <div class="card border-success mb-4">
                        <div class="card-body text-start">
                            <h5 class="card-title text-success">
                                <i class="fas fa-user me-2"></i>Detail Pendaftaran
                            </h5>
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <p><strong>Nama:</strong> {{ $dancer->name }}</p>
                                    <p><strong>NIK:</strong> {{ $dancer->nik }}</p>
                                    <p><strong>Email:</strong> {{ $dancer->email }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Role:</strong> 
                                        <span class="badge {{ $dancer->role === 'Leader' ? 'bg-warning' : 'bg-info' }}">
                                            {{ $dancer->role === 'Leader' ? 'KAPTEN' : 'ANGGOTA' }}
                                        </span>
                                    </p>
                                    <p><strong>Status:</strong> 
                                        <span class="badge bg-secondary">{{ $dancer->verification_status_label }}</span>
                                    </p>
                                    <p><strong>ID Dancer:</strong> DNC-{{ str_pad($dancer->dancer_id, 5, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Next Steps -->
                    <div class="alert alert-info text-start">
                        <h6><i class="fas fa-info-circle me-2"></i>Langkah Selanjutnya:</h6>
                        <ul class="mb-0">
                            <li>Tunggu verifikasi dari admin HSBL</li>
                            <li>Jika ada kendala, hubungi support HSBL</li>
                            @if($dancer->role === 'Leader')
                            <li><strong>Referral Code:</strong> {{ $team->referral_code ?? 'Akan diberikan setelah verifikasi' }}</li>
                            <li class="text-warning">Bagikan referral code ke anggota tim Anda!</li>
                            @endif
                        </ul>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-5">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-home me-2"></i>Kembali ke Dashboard
                        </a>
                        <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary ms-3">
                            Daftar Lagi
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection