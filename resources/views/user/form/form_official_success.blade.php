@extends('user.form.layout')
@section('title', 'Pendaftaran Berhasil - SBL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <!-- Success Header -->
                <div class="card-header bg-gradient-success text-white py-5 text-center">
                    <div class="mb-3">
                        <i class="fas fa-check-circle fa-4x"></i>
                    </div>
                    <h1 class="mb-0">🎉 Pendaftaran Berhasil!</h1>
                    <p class="mb-0 opacity-75 mt-2">Data official telah tersimpan</p>
                </div>
                
                <!-- Success Content -->
                <div class="card-body p-5">
                    <!-- Official Info -->
                    <div class="text-center mb-5">
                        <h4 class="text-primary mb-3">
                            <i class="fas fa-user-tie me-2"></i>{{ $official->name }}
                        </h4>
                        <div class="row justify-content-center mb-4">
                            <div class="col-auto">
                                <span class="badge bg-warning fs-6 p-2">
                                    {{ $official->team_role_label }}
                                </span>
                            </div>
                            <div class="col-auto">
                                <span class="badge bg-info fs-6 p-2">
                                    {{ $official->role_label }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Team Info -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-users fa-2x me-3"></i>
                            <div>
                                <h5 class="mb-1">{{ $team->team_name }}</h5>
                                <p class="mb-0">{{ $team->school_name }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Next Steps -->
                    <div class="mb-5">
                        <h5 class="text-primary mb-3">
                            <i class="fas fa-list-check me-2"></i>Langkah Selanjutnya
                        </h5>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <i class="fas fa-user-plus fa-2x text-primary"></i>
                                        </div>
                                        <h6 class="card-title">Tambah Official Lain</h6>
                                        <p class="card-text small text-muted">
                                            Tambah official lain untuk tim Anda
                                        </p>
                                        <a href="{{ route('form.official.create', ['team_id' => $team->team_id]) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            Tambah Official
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card border-0 bg-light h-100">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <i class="fas fa-basketball-ball fa-2x text-success"></i>
                                        </div>
                                        <h6 class="card-title">Tambah Pemain</h6>
                                        <p class="card-text small text-muted">
                                            Tambah pemain basket untuk tim Anda
                                        </p>
                                        <a href="{{ route('form.player.create', ['team_id' => $team->team_id]) }}" 
                                           class="btn btn-outline-success btn-sm">
                                            Tambah Pemain
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Important Notes -->
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Catatan Penting:</h6>
                        <ul class="mb-0">
                            <li>Data yang sudah dikirim <strong>tidak dapat diubah</strong> kecuali oleh admin</li>
                            <li>Pastikan <strong>Leader tim sudah melakukan pembayaran</strong> untuk mengunci pendaftaran</li>
                            <li>Status verifikasi dapat dilihat di dashboard tim</li>
                        </ul>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="text-center mt-5">
                        <a href="{{ route('student.dashboard') }}" class="btn btn-success btn-lg px-5 py-3">
                            <i class="fas fa-home me-2"></i> Kembali ke Dashboard
                        </a>
                        <a href="{{ route('student.team') }}" class="btn btn-outline-primary ms-3">
                            <i class="fas fa-users me-2"></i> Lihat Tim Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection