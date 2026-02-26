@extends('user.form.layout')

@section('title', 'Pendaftaran Berhasil - SBL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Success Card -->
            <div class="card border-0 shadow-lg">
                <!-- Card Header -->
                <div class="card-header bg-gradient-success text-white py-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h2 class="mb-0"><i class="fas fa-check-circle me-2"></i>Pendaftaran Berhasil!</h2>
                            <p class="mb-0 opacity-75">Data Anda telah tersimpan di sistem SBL</p>
                        </div>
                        <div class="bg-white text-success rounded-circle p-3">
                            <i class="fas fa-music fa-2x"></i>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-5">
                    <!-- Success Message -->
                    <div class="text-center mb-5">
                        <div class="mb-4">
                            <i class="fas fa-check-circle fa-4x text-success"></i>
                        </div>
                        <h3 class="fw-bold text-success mb-3">{{ $successMessage }}</h3>
                        <p class="text-muted mb-4">{{ $instructions }}</p>
                    </div>

                    <!-- Information Box -->
                    <div class="alert alert-info border-info border-start border-5 mb-5">
                        <h5><i class="fas fa-info-circle me-2"></i>Informasi Penting</h5>
                        <ul class="mb-0">
                            <li>Status verifikasi akan diupdate oleh admin dalam 1-3 hari kerja</li>
                            <li>Simpan screenshot halaman ini sebagai bukti pendaftaran</li>
                            <li>Hubungi kapten/leader tim untuk informasi lebih lanjut</li>
                        </ul>
                    </div>

                    <!-- Registration Details -->
                    <div class="card border mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0"><i class="fas fa-file-alt me-2"></i>Detail Pendaftaran</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="40%">Nama Dancer:</th>
                                            <td>{{ $dancer->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Posisi:</th>
                                            <td>
                                                @if($isLeader)
                                                <span class="badge bg-warning text-dark">LEADER</span>
                                                @else
                                                <span class="badge bg-info">MEMBER</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Sekolah:</th>
                                            <td>{{ $team->school_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Tim Dancer:</th>
                                            <td>{{ $team->school_name }} Dancer</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm">
                                        <tr>
                                            <th width="40%">ID Dancer:</th>
                                            <td><code>{{ $dancer->dancer_id }}</code></td>
                                        </tr>
                                        <tr>
                                            <th>Tanggal:</th>
                                            <td>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status:</th>
                                            <td><span class="badge bg-secondary">Menunggu Verifikasi</span></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Referral Code Box (only for Leader) -->
                    @if($isLeader && $referralCode)
                    <div class="card border-success mb-4">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0"><i class="fas fa-key me-2"></i>Referral Code Tim</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center">
                                <p class="mb-2">Berikan referral code ini kepada teman yang ingin bergabung:</p>
                                <div class="bg-light border rounded p-4 mb-3">
                                    <h2 class="text-success fw-bold mb-0" style="letter-spacing: 2px;">{{ $referralCode }}</h2>
                                </div>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>Simpan referral code ini!</strong> Anggota tim perlu kode ini untuk bergabung.
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="border-top pt-4 mt-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <button class="btn btn-outline-primary w-100" onclick="window.print()">
                                    <i class="fas fa-print me-2"></i>Cetak Bukti
                                </button>
                            </div>
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('form.team.choice') }}" class="btn btn-success w-100">
                                    <i class="fas fa-home me-2"></i>Kembali ke Beranda
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="text-center mt-4">
                <p class="text-muted">
                    <i class="fas fa-question-circle me-1"></i> 
                    Pertanyaan? Hubungi Admin di 
                    <a href="mailto:support@sbl.com" class="text-decoration-none fw-bold">support@sbl.com</a>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-success {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
}

.card {
    border-radius: 12px;
}

.badge {
    font-size: 0.8rem;
    padding: 0.3rem 0.6rem;
}

.table th {
    font-weight: 600;
    font-size: 0.85rem;
}

.table td {
    font-size: 0.85rem;
}

/* Print styles */
@media print {
    .btn, .alert-warning {
        display: none !important;
    }
    
    .card {
        border: 1px solid #000 !important;
        box-shadow: none !important;
    }
}
</style>
@endsection