@extends('user.form.layout')

@section('title', 'Pendaftaran Berhasil - SBL')

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
                        <h4 class="fw-bold text-success mb-1">
                            @if($isCaptain)
                            👑 Pendaftaran Kapten Berhasil!
                            @else
                            🎉 Pendaftaran Berhasil!
                            @endif
                        </h4>
                        <p class="text-muted small">{{ $successMessage }}</p>
                    </div>

                    <!-- Alert Penting untuk Kapten -->
                    @if($isCaptain)
                    <div class="alert alert-warning border-0 bg-soft-warning p-3 mb-4" style="border-radius: 12px;">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-triangle text-warning me-3 mt-1" style="font-size: 1.2rem;"></i>
                            <div>
                                <strong class="d-block mb-1">⚠️ Simpan Referral Code Anda!</strong>
                                <span class="small">Referral code hanya muncul sekali. Screenshot atau catat sekarang untuk dibagikan ke anggota tim.</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Informasi Pendaftaran -->
                    <div class="bg-light p-3 rounded-3 mb-4" style="background: #f8f9fa;">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <i class="fas fa-info-circle me-2"></i>Informasi Pendaftaran
                        </h6>
                        
                        <div class="row g-2">
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Nama Lengkap:</span>
                                    <span class="fw-semibold small">{{ $player->name }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">NIK:</span>
                                    <span class="fw-semibold small">{{ $player->nik }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Sekolah:</span>
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
                                    <span class="text-muted small">Role Anda:</span>
                                    <span class="badge {{ $isCaptain ? 'bg-warning' : 'bg-info' }} text-dark px-2 py-1">
                                        {{ $isCaptain ? 'KAPTEN' : 'ANGGOTA' }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Nomor Jersey:</span>
                                    <span class="fw-semibold small">{{ $player->jersey_number ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">Email:</span>
                                    <span class="fw-semibold small">{{ $player->email }}</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="d-flex justify-content-between py-1">
                                    <span class="text-muted small">WhatsApp:</span>
                                    <span class="fw-semibold small">{{ $player->phone }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- REFERRAL CODE SECTION (Hanya untuk Kapten) -->
                    @if($isCaptain)
                        @if($referralCode && $referralCode !== '')
                        <div class="bg-primary bg-opacity-10 p-3 rounded-3 mb-4 text-center">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-key text-primary mb-2" style="font-size: 1.5rem;"></i>
                                <strong class="d-block mb-2">🎫 REFERRAL CODE ANDA</strong>
                                <div class="bg-white p-2 rounded border border-primary d-inline-block mb-3">
                                    <code class="fw-bold fs-4">{{ $referralCode }}</code>
                                </div>
                                <div class="d-flex gap-2">
                                    <button onclick="copyReferralCode()" class="btn btn-primary btn-sm">
                                        <i class="fas fa-copy me-1"></i> Salin Kode
                                    </button>
                                    <button onclick="printPage()" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-print me-1"></i> Print
                                    </button>
                                </div>
                                <small class="text-muted mt-2">Bagikan kode ini ke anggota tim untuk bergabung</small>
                            </div>
                        </div>

                        <!-- Cara Bergabung untuk Anggota -->
                        <div class="bg-info bg-opacity-10 p-3 rounded-3 mb-4">
                            <div class="d-flex">
                                <i class="fas fa-users text-info me-3 mt-1" style="font-size: 1.2rem;"></i>
                                <div>
                                    <strong class="d-block mb-1">📋 Cara Anggota Bergabung:</strong>
                                    <ol class="small mb-0 ps-3">
                                        <li>Buka halaman "Gabung Tim"</li>
                                        <li>Masukkan referral code: <code class="bg-white p-1 rounded">{{ $referralCode }}</code></li>
                                        <li>Isi data diri sebagai anggota</li>
                                        <li><strong>Tidak perlu membayar</strong> - biaya sudah ditanggung Kapten</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        @else
                        <!-- Jika Kapten tapi belum ada referral code -->
                        <div class="alert alert-danger border-0 bg-soft-danger p-3 mb-4" style="border-radius: 12px;">
                            <div class="d-flex">
                                <i class="fas fa-times-circle text-danger me-3 mt-1" style="font-size: 1.2rem;"></i>
                                <div>
                                    <strong class="d-block mb-1">❌ Referral Code Belum Tersedia</strong>
                                    <span class="small">Hubungi panitia SBL untuk mendapatkan referral code tim Anda.</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    @else
                        <!-- Untuk Member (bukan Kapten) -->
                        <div class="bg-success bg-opacity-10 p-3 rounded-3 mb-4">
                            <div class="d-flex">
                                <i class="fas fa-check-circle text-success me-3 mt-1" style="font-size: 1.2rem;"></i>
                                <div>
                                    <strong class="d-block mb-1">✅ Pendaftaran Anggota Berhasil</strong>
                                    <span class="small">Anda telah bergabung sebagai anggota tim. Biaya pendaftaran sudah ditanggung oleh Kapten tim.</span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Langkah Selanjutnya -->
                    <div class="mb-4">
                        <h6 class="fw-semibold mb-3 text-primary">
                            <i class="fas fa-list-check me-2"></i>Langkah Selanjutnya
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-4 col-6">
                                <div class="text-center p-2 border rounded-3 h-100 bg-white">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 32px; height: 32px;">
                                        <span class="fw-bold small">1</span>
                                    </div>
                                    <span class="small">Screenshot Halaman Ini</span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="text-center p-2 border rounded-3 h-100 bg-white">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 32px; height: 32px;">
                                        <span class="fw-bold small">2</span>
                                    </div>
                                    <span class="small">Simpan Referral Code</span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6">
                                <div class="text-center p-2 border rounded-3 h-100 bg-white">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2" style="width: 32px; height: 32px;">
                                        <span class="fw-bold small">3</span>
                                    </div>
                                    <span class="small">Tunggu Verifikasi</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Penting -->
                    <div class="alert alert-danger border-0 bg-soft-danger p-3 mb-4" style="border-radius: 12px;">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-triangle text-danger me-3 mt-1" style="font-size: 1.2rem;"></i>
                            <div>
                                <strong class="d-block mb-1">📌 Informasi Penting:</strong>
                                <ul class="small mb-0 ps-3">
                                    <li>Data Anda akan diverifikasi oleh panitia dalam 1x24 jam</li>
                                    <li>Simpan halaman ini sebagai bukti pendaftaran</li>
                                    <li>Hubungi panitia jika ada perubahan data</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('user.dashboard') }}" class="btn btn-primary py-2 fw-semibold">
                            <i class="fas fa-tachometer-alt me-2"></i> Ke Dashboard
                        </a>
                        <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary py-2">
                            <i class="fas fa-home me-2"></i> Kembali ke Halaman Utama
                        </a>
                    </div>

                    <!-- Footer Note -->
                    <div class="text-center mt-4 pt-2 border-top">
                        <small class="text-muted">
                            <i class="fas fa-envelope me-1"></i> Ada pertanyaan? Hubungi support@sbl.com
                        </small>
                        <br>
                        <small class="text-muted">
                            ID Pendaftaran: {{ $team->team_id }}-{{ $player->id }}
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
    .bg-soft-danger {
        background: rgba(249, 65, 68, 0.1);
    }
    .bg-opacity-10 {
        background: rgba(67, 97, 238, 0.1);
    }
    .bg-success {
        background: #28a745 !important;
    }
    .bg-success.bg-opacity-10 {
        background: rgba(40, 167, 69, 0.1) !important;
    }
    
    @media print {
        .btn {
            display: none !important;
        }
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function copyReferralCode() {
        const referralCode = '{{ $referralCode }}';
        
        if (!referralCode) {
            Swal.fire({
                icon: 'error',
                title: 'Referral Code Tidak Tersedia',
                text: 'Hubungi panitia untuk mendapatkan referral code.',
                confirmButtonColor: '#dc3545'
            });
            return;
        }
        
        // Copy ke clipboard
        navigator.clipboard.writeText(referralCode).then(function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil Disalin!',
                html: `Referral code <strong class="text-primary">${referralCode}</strong> telah disalin ke clipboard.`,
                confirmButtonColor: '#28a745',
                timer: 2000,
                showConfirmButton: true
            });
        }).catch(function() {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Menyalin',
                text: 'Silakan salin manual: ' + referralCode,
                confirmButtonColor: '#dc3545'
            });
        });
    }

    function printPage() {
        Swal.fire({
            title: 'Cetak Halaman',
            text: 'Pastikan semua informasi terlihat sebelum mencetak.',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Cetak',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#28a745'
        }).then((result) => {
            if (result.isConfirmed) {
                window.print();
            }
        });
    }

    // Peringatan sebelum keluar halaman (khusus Kapten dengan referral code)
    @if($isCaptain && $referralCode)
    let hasCopied = false;
    
    window.addEventListener('beforeunload', function(e) {
        if (!hasCopied) {
            e.preventDefault();
            e.returnValue = '⚠️ Jangan lupa salin referral code Anda! Kode hanya muncul sekali.';
        }
    });
    
    // Tandai sudah copy
    document.querySelector('[onclick="copyReferralCode()"]')?.addEventListener('click', function() {
        hasCopied = true;
    });
    @endif
</script>
@endpush

@endsection