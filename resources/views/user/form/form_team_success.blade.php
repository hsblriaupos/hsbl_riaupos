@extends('user.form.layout')

@section('title', 'Tim Berhasil Dibuat - SBL')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm" style="border-radius: 20px;">
                <div class="card-body p-4">
                    
                    <!-- Header dengan Icon -->
                    <div class="text-center mb-4">
                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex p-3 mb-3">
                            <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="fw-bold text-success mb-1">Tim Berhasil Dibuat!</h4>
                        <p class="text-muted small">Selamat! Tim Anda telah terdaftar di sistem SBL</p>
                    </div>

                    <!-- Alert Penting -->
                    <div class="alert alert-warning border-0 bg-soft-warning p-3 mb-4" style="border-radius: 12px;">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-triangle text-warning me-3 mt-1" style="font-size: 1.2rem;"></i>
                            <div>
                                <strong class="d-block mb-1">⚠️ Langkah Wajib Selanjutnya:</strong>
                                <span class="small">Anda harus mendaftar sebagai <strong>KAPTEN</strong> untuk tim ini. Tanpa Kapten, tim tidak akan bisa diverifikasi dan tidak bisa mengikuti kompetisi.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Tim Lengkap -->
                    <div class="bg-light p-3 rounded-3 mb-4" style="background: #f8f9fa;">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <i class="fas fa-info-circle me-2"></i>Informasi Tim
                        </h6>
                        
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Nama Sekolah:</span>
                                    <span class="fw-semibold small">{{ $team->school_name }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Kategori Tim:</span>
                                    <span class="fw-semibold small">{{ $team->team_category }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Kompetisi:</span>
                                    <span class="fw-semibold small">{{ $team->competition ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Season / Series:</span>
                                    <span class="fw-semibold small">{{ $team->season }} / {{ $team->series }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Nama Pendaftar:</span>
                                    <span class="fw-semibold small">{{ $team->registered_by }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Status Tim:</span>
                                    <span class="badge bg-warning text-dark px-2 py-1">Menunggu Kapten</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Langkah-langkah Menjadi Kapten -->
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <i class="fas fa-crown me-2"></i>Langkah Menjadi Kapten
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-3 col-6">
                                <div class="text-center p-2 border rounded-3 h-100 bg-white">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 32px; height: 32px;">
                                        <span class="fw-bold small">1</span>
                                    </div>
                                    <span class="small">Isi Data Diri</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-center p-2 border rounded-3 h-100 bg-white">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 32px; height: 32px;">
                                        <span class="fw-bold small">2</span>
                                    </div>
                                    <span class="small">Upload Dokumen</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-center p-2 border rounded-3 h-100 bg-white">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 32px; height: 32px;">
                                        <span class="fw-bold small">3</span>
                                    </div>
                                    <span class="small">Upload Bukti Bayar</span>
                                </div>
                            </div>
                            <div class="col-md-3 col-6">
                                <div class="text-center p-2 border rounded-3 h-100 bg-white">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 32px; height: 32px;">
                                        <span class="fw-bold small">4</span>
                                    </div>
                                    <span class="small">Dapatkan Referral Code</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Referral Code -->
                    <div class="bg-info bg-opacity-10 p-3 rounded-3 mb-4">
                        <div class="d-flex">
                            <i class="fas fa-share-alt text-info me-3 mt-1" style="font-size: 1.2rem;"></i>
                            <div>
                                <strong class="d-block mb-1">🎫 Referral Code</strong>
                                <span class="small">Setelah Anda selesai mendaftar sebagai Kapten dan melakukan pembayaran, Anda akan mendapatkan <strong>Referral Code</strong> yang bisa dibagikan ke anggota tim lain (Pemain, Dancer, Official) untuk bergabung.</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('form.player.create', ['team_id' => $team->team_id]) }}" 
                           class="btn btn-primary py-2 fw-semibold">
                            <i class="fas fa-crown me-2"></i> Daftar Sebagai Kapten Sekarang
                        </a>
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary py-2">
                            <i class="fas fa-home me-2"></i> Kembali ke Dashboard
                        </a>
                    </div>

                    <!-- Footer Note -->
                    <div class="text-center mt-4 pt-2 border-top">
                        <small class="text-muted">
                            <i class="fas fa-envelope me-1"></i> Ada pertanyaan? Hubungi support@sbl.com
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-soft-warning {
        background: rgba(248, 150, 30, 0.1);
    }
    .bg-soft-info {
        background: rgba(14, 165, 233, 0.1);
    }
    .bg-opacity-10 {
        background: rgba(67, 97, 238, 0.1);
    }
</style>
@endpush

@endsection