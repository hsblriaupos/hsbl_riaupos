@extends('user.form.layout')

@section('title', 'Pendaftaran Berhasil - HSBL')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="form-container">
            <!-- Header -->
            <div class="form-header">
                <h2 class="text-center mb-0">🎉 Pendaftaran Berhasil!</h2>
                <p class="text-center mb-0 mt-2 opacity-75">Data Anda telah tersimpan dengan baik</p>
            </div>
            
            <!-- Body -->
            <div class="card-body p-5 text-center">
                <!-- Success Icon -->
                <div class="mb-4">
                    <div class="d-inline-block p-4 rounded-circle bg-success bg-opacity-10">
                        <i class="fas fa-check-circle fa-4x text-success"></i>
                    </div>
                </div>
                
                <!-- Success Message -->
                <h3 class="text-success mb-3">Terima kasih telah mendaftar!</h3>
                <p class="text-muted mb-4">
                    Data Anda sebagai <strong>{{ $player->team_role }}</strong> 
                    tim <strong>{{ $team->school_name }}</strong> 
                    telah berhasil disimpan.
                </p>
                
                <!-- Player Info -->
                <div class="card border-primary mb-4">
                    <div class="card-body">
                        <h5 class="card-title text-primary mb-3">
                            <i class="fas fa-user-check me-2"></i>Informasi Pendaftaran
                        </h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Nama:</strong>
                                <p class="mb-0">{{ $player->name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Role:</strong>
                                <p class="mb-0">
                                    <span class="badge {{ $player->team_role === 'Leader' ? 'bg-warning' : 'bg-info' }}">
                                        {{ $player->team_role }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Kategori:</strong>
                                <p class="mb-0">{{ ucfirst($player->category) }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Status Pembayaran:</strong>
                                <p class="mb-0">
                                    @if($player->team_role === 'Leader')
                                    <span class="badge bg-warning">Menunggu Verifikasi</span>
                                    @else
                                    <span class="badge bg-success">Gratis (Sudah dibayar Leader)</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Next Steps -->
                <div class="card border-success mb-4">
                    <div class="card-header bg-success text-white">
                        <i class="fas fa-forward me-2"></i>Langkah Selanjutnya
                    </div>
                    <div class="card-body text-start">
                        @if($player->team_role === 'Leader')
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <span class="badge bg-success rounded-circle p-2">1</span>
                            </div>
                            <div>
                                <strong>Tunggu Verifikasi Pembayaran</strong>
                                <p class="text-muted mb-0">Admin akan memverifikasi bukti pembayaran Anda</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <span class="badge bg-success rounded-circle p-2">2</span>
                            </div>
                            <div>
                                <strong>Bagikan Referral Code</strong>
                                <p class="text-muted mb-0">
                                    Kode: <code class="bg-light p-1 rounded">{{ $team->referral_code }}</code>
                                </p>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="me-3">
                                <span class="badge bg-success rounded-circle p-2">3</span>
                            </div>
                            <div>
                                <strong>Undang Anggota Lain</strong>
                                <p class="text-muted mb-0">Bagikan kode ke teman untuk bergabung</p>
                            </div>
                        </div>
                        @else
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <span class="badge bg-success rounded-circle p-2">1</span>
                            </div>
                            <div>
                                <strong>Tunggu Verifikasi Data</strong>
                                <p class="text-muted mb-0">Admin akan memverifikasi data Anda</p>
                            </div>
                        </div>
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <span class="badge bg-success rounded-circle p-2">2</span>
                            </div>
                            <div>
                                <strong>Persiapan Kompetisi</strong>
                                <p class="text-muted mb-0">Ikuti informasi selanjutnya dari Leader tim</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="d-flex justify-content-center gap-3 mt-4">
                    <a href="{{ route('form.team.choice') }}" class="btn btn-primary px-4">
                        <i class="fas fa-home me-2"></i>Kembali ke Beranda
                    </a>
                    
                    @if($player->team_role === 'Leader')
                    <a href="https://wa.me/?text={{ urlencode('Halo! Gabung ke tim ' . $team->school_name . ' di HSBL! Gunakan kode: ' . $team->referral_code) }}" 
                       target="_blank" class="btn btn-success px-4">
                        <i class="fab fa-whatsapp me-2"></i>Bagikan via WhatsApp
                    </a>
                    @endif
                </div>
                
                <!-- Important Note -->
                <div class="alert alert-info mt-4">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Info Penting:</strong> Semua komunikasi selanjutnya akan melalui WhatsApp.
                    Pastikan nomor Anda aktif dan cek email secara berkala.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection