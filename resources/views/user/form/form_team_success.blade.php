@extends('user.form.layout')

@section('title', 'Tim Berhasil Dibuat - HSBL')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-3">
                    <!-- Icon & Status -->
                    <div class="text-center mb-2">
                        <i class="fas fa-check-circle text-success" style="font-size: 2rem;"></i>
                        <h5 class="text-success mt-1 mb-1">Tim Dibuat!</h5>
                        <small class="text-muted d-block">{{ $team->team_category }} - {{ $team->school_name }}</small>
                    </div>

                    <!-- Alert -->
                    <div class="alert alert-warning p-2 mb-3 small">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Catatan:</strong> Daftar sebagai Kapten untuk lanjut.
                    </div>

                    <!-- Info Singkat -->
                    <div class="bg-light p-2 rounded mb-3 small">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Sekolah:</span>
                            <span class="fw-semibold">{{ $team->school_name }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Kategori:</span>
                            <span class="fw-semibold">{{ $team->team_category }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Status:</span>
                            <span class="badge bg-warning text-dark px-2">Menunggu Kapten</span>
                        </div>
                    </div>

                    <!-- Step Kapten -->
                    <div class="mb-3 small">
                        <span class="fw-semibold text-primary"><i class="fas fa-crown me-1"></i>Langkah wajib:</span>
                        <ul class="text-muted ps-3 mt-1 mb-0">
                            <li>Kapten bayar & dapat referral code</li>
                            <li>Bagikan code ke anggota</li>
                        </ul>
                    </div>

                    <!-- Button -->
                    <div class="d-grid mb-2">
                        <a href="{{ route('form.player.create', ['team_id' => $team->team_id]) }}" 
                           class="btn btn-primary btn-sm py-2">
                            <i class="fas fa-crown me-1"></i> Daftar Kapten
                        </a>
                        <a href="{{ route('user.dashboard') }}" class="btn btn-outline-secondary btn-sm mt-1">
                            Dashboard
                        </a>
                    </div>

                    <!-- Link Petunjuk -->
                    <div class="text-center">
                        <small>
                            <a href="#" class="text-decoration-none text-muted" data-bs-toggle="modal" data-bs-target="#modalPetunjuk">
                                <i class="fas fa-question-circle me-1"></i>Petunjuk lengkap
                            </a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Petunjuk -->
<div class="modal fade" id="modalPetunjuk" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">📋 Petunjuk</h6>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-3 small">
                <ol class="ps-3 mb-0">
                    <li class="mb-1">Isi data diri</li>
                    <li class="mb-1">Upload dokumen</li>
                    <li class="mb-1">Upload bukti bayar</li>
                    <li class="mb-1">Submit & dapatkan referral</li>
                    <li>Bagikan referral ke anggota</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection