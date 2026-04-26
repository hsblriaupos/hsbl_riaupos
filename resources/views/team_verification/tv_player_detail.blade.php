{{-- resources/views/team_verification/tv_player_detail.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detail Pemain - ' . ($player->name ?? ''))

@section('content')
<div class="container-fluid py-3">
    <!-- Breadcrumb Minimalis -->
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb bg-transparent p-0 m-0">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.tv_team_list') }}" class="text-decoration-none">
                    <i class="fas fa-list fa-xs me-1"></i>Daftar Tim
                </a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.team-list.show', $player->team_id) }}" class="text-decoration-none">
                    <i class="fas fa-users fa-xs me-1"></i>Detail Tim
                </a>
            </li>
            <li class="breadcrumb-item active text-muted">
                <i class="fas fa-user-circle fa-xs me-1"></i>{{ $player->name ?? 'Pemain' }}
            </li>
        </ol>
    </nav>

    <!-- Tombol Kembali -->
    <div class="mb-3">
        <a href="{{ route('admin.team-list.show', $player->team_id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Kembali ke Tim
        </a>
    </div>

    <!-- Card Utama -->
    <div class="card border shadow-none">
        <!-- Header Card -->
        <div class="card-header bg-white border-bottom py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-semibold" style="font-size: 0.8rem;">
                        <i class="fas fa-user-circle me-2 text-primary"></i>Detail Pemain
                    </h6>
                </div>
                <div>
                    @if($player->is_finalized)
                    <span class="badge bg-success" style="font-size: 0.6rem;">
                        <i class="fas fa-check-circle me-1"></i>Final
                    </span>
                    @else
                    <span class="badge bg-warning" style="font-size: 0.6rem;">
                        <i class="fas fa-clock me-1"></i>Draft
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body p-3">
            <!-- Header Pemain -->
            <div class="player-header mb-3 pb-2 border-bottom">
                <div class="row align-items-center">
                    <div class="col-auto">
                        @if($player->formal_photo)
                        <img src="{{ Storage::url($player->formal_photo) }}" 
                             alt="{{ $player->name }}" 
                             class="rounded-circle border" 
                             style="width: 45px; height: 45px; object-fit: cover;">
                        @else
                        <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 45px; height: 45px; background-color: #e9ecef; color: #6c757d;">
                            <i class="fas fa-user fa-lg"></i>
                        </div>
                        @endif
                    </div>
                    <div class="col">
                        <h6 class="mb-0 fw-semibold" style="font-size: 0.85rem;">{{ $player->name ?? 'N/A' }}</h6>
                        <div class="text-muted" style="font-size: 0.7rem;">
                            <span class="me-2">
                                <i class="fas fa-tag fa-xs me-1"></i>No. Jersey: <strong>{{ $player->jersey_number ?? '-' }}</strong>
                            </span>
                            <span class="me-2">
                                <i class="fas fa-basketball-ball fa-xs me-1"></i>Posisi: <strong>{{ $player->basketball_position ?? '-' }}</strong>
                            </span>
                            <span>
                                <i class="fas fa-venus-mars fa-xs me-1"></i>
                                {{ $player->gender == 'Male' ? 'Laki-laki' : ($player->gender == 'Female' ? 'Perempuan' : '-') }}
                            </span>
                        </div>
                        <div class="text-muted" style="font-size: 0.65rem;">
                            <i class="fas fa-school fa-xs me-1"></i>{{ $player->school_name ?? ($player->school ?? 'N/A') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Pemain dalam Grid -->
            <div class="row g-2">
                <!-- Kolom Kiri -->
                <div class="col-lg-6">
                    <!-- Data Pribadi -->
                    <div class="section mb-3">
                        <h6 class="section-title mb-2 fw-semibold" style="font-size: 0.75rem;">
                            <i class="fas fa-id-card me-2"></i>Data Pribadi
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-muted mb-0" style="font-size: 0.65rem;">NIK</label>
                                    <div class="form-control-static form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">{{ $player->nik ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-muted mb-0" style="font-size: 0.65rem;">Tanggal Lahir</label>
                                    <div class="form-control-static form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                        @if($player->birthdate)
                                        {{ \Carbon\Carbon::parse($player->birthdate)->isoFormat('D MMM YYYY') }}
                                        @else
                                        -
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-muted mb-0" style="font-size: 0.65rem;">Email</label>
                                    <div class="form-control-static form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">{{ $player->email ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-muted mb-0" style="font-size: 0.65rem;">Telepon</label>
                                    <div class="form-control-static form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">{{ $player->phone ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pendidikan -->
                    <div class="section mb-3">
                        <h6 class="section-title mb-2 fw-semibold" style="font-size: 0.75rem;">
                            <i class="fas fa-graduation-cap me-2"></i>Data Pendidikan
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-muted mb-0" style="font-size: 0.65rem;">Kelas</label>
                                    <div class="form-control-static form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">{{ $player->grade ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-muted mb-0" style="font-size: 0.65rem;">Tahun STTB</label>
                                    <div class="form-control-static form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">{{ $player->sttb_year ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Fisik -->
                    <div class="section mb-3">
                        <h6 class="section-title mb-2 fw-semibold" style="font-size: 0.75rem;">
                            <i class="fas fa-ruler-vertical me-2"></i>Data Fisik
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label text-muted mb-0" style="font-size: 0.65rem;">Tinggi</label>
                                    <div class="form-control-static form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                        {{ $player->height ? intval($player->height) . ' cm' : '-' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label text-muted mb-0" style="font-size: 0.65rem;">Berat</label>
                                    <div class="form-control-static form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                        {{ $player->weight ? intval($player->weight) . ' kg' : '-' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label text-muted mb-0" style="font-size: 0.65rem;">Ukuran Baju</label>
                                    <div class="form-control-static form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">{{ $player->tshirt_size ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label text-muted mb-0" style="font-size: 0.65rem;">Ukuran Sepatu</label>
                                    <div class="form-control-static form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">{{ $player->shoes_size ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Orang Tua -->
                    <div class="section mb-3">
                        <h6 class="section-title mb-2 fw-semibold" style="font-size: 0.75rem;">
                            <i class="fas fa-users me-2"></i>Data Orang Tua
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-muted mb-0" style="font-size: 0.65rem;">Nama Ayah</label>
                                    <div class="form-control-static form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">{{ $player->father_name ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-muted mb-0" style="font-size: 0.65rem;">Telepon Ayah</label>
                                    <div class="form-control-static form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">{{ $player->father_phone ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-muted mb-0" style="font-size: 0.65rem;">Nama Ibu</label>
                                    <div class="form-control-static form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">{{ $player->mother_name ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-muted mb-0" style="font-size: 0.65rem;">Telepon Ibu</label>
                                    <div class="form-control-static form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">{{ $player->mother_phone ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Media Sosial -->
                    <div class="section mb-3">
                        <h6 class="section-title mb-2 fw-semibold" style="font-size: 0.75rem;">
                            <i class="fas fa-share-alt me-2"></i>Media Sosial
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-muted mb-0" style="font-size: 0.65rem;">Instagram</label>
                                    <div class="form-control-static form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                        @if($player->instagram)
                                        <a href="https://instagram.com/{{ ltrim($player->instagram, '@') }}" target="_blank" style="font-size: 0.7rem;">
                                            <i class="fab fa-instagram me-1"></i>{{ $player->instagram }}
                                        </a>
                                        @else
                                        -
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label text-muted mb-0" style="font-size: 0.65rem;">TikTok</label>
                                    <div class="form-control-static form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;">
                                        @if($player->tiktok)
                                        <a href="https://tiktok.com/@{{ $player->tiktok }}" target="_blank" style="font-size: 0.7rem;">
                                            <i class="fab fa-tiktok me-1"></i>{{ $player->tiktok }}
                                        </a>
                                        @else
                                        -
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-lg-6">
                    <!-- Dokumen -->
                    <div class="section mb-3">
                        <h6 class="section-title mb-2 fw-semibold" style="font-size: 0.75rem;">
                            <i class="fas fa-file-alt me-2"></i>Dokumen
                        </h6>
                        <div class="row g-2">
                            @php
                                $documents = [
                                    ['field' => 'birth_certificate', 'name' => 'Akta Kelahiran', 'icon' => 'fa-file-pdf'],
                                    ['field' => 'kk', 'name' => 'Kartu Keluarga', 'icon' => 'fa-file-pdf'],
                                    ['field' => 'shun', 'name' => 'SHUN', 'icon' => 'fa-file-pdf'],
                                    ['field' => 'report_identity', 'name' => 'Identitas Rapor', 'icon' => 'fa-file-pdf'],
                                    ['field' => 'last_report_card', 'name' => 'Raport Terakhir', 'icon' => 'fa-file-pdf'],
                                    ['field' => 'assignment_letter', 'name' => 'Surat Tugas', 'icon' => 'fa-file-signature'],
                                ];
                            @endphp

                            @foreach($documents as $doc)
                            <div class="col-12 col-md-6">
                                <div class="document-item p-2 border rounded h-100">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <div>
                                            <i class="fas {{ $doc['icon'] }} me-1 {{ $player->{$doc['field']} ? 'text-danger' : 'text-muted' }}" style="font-size: 0.7rem;"></i>
                                            <small class="fw-semibold" style="font-size: 0.65rem;">{{ $doc['name'] }}</small>
                                        </div>
                                    </div>
                                    @if($player->{$doc['field']})
                                    <button type="button" 
                                            class="btn btn-outline-primary btn-xs w-100 mt-1"
                                            onclick="window.open('{{ Storage::url($player->{$doc['field']}) }}', '_blank')"
                                            style="font-size: 0.6rem; padding: 3px 5px;">
                                        <i class="fas fa-eye fa-xs me-1"></i>Lihat
                                    </button>
                                    @else
                                    <div class="text-center py-1 bg-light rounded mt-1">
                                        <span class="badge bg-light text-muted" style="font-size: 0.6rem;">Tidak ada</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Foto Formal -->
                    <div class="section mb-3">
                        <h6 class="section-title mb-2 fw-semibold" style="font-size: 0.75rem;">
                            <i class="fas fa-image me-2"></i>Foto Formal
                        </h6>
                        <div class="row">
                            <div class="col-12">
                                <div class="photo-item border rounded overflow-hidden">
                                    @if($player->formal_photo)
                                    <div class="position-relative d-flex justify-content-center bg-light">
                                        <img src="{{ Storage::url($player->formal_photo) }}" 
                                             alt="Foto Formal" 
                                             class="img-fluid"
                                             style="max-width: 100%; height: auto; max-height: 180px; object-fit: contain; cursor: pointer;"
                                             onclick="showPhotoPopup('{{ Storage::url($player->formal_photo) }}', 'Foto Formal - {{ $player->name }}')">
                                        <div class="position-absolute top-0 end-0 p-1">
                                            <button type="button" 
                                                    class="btn btn-light btn-xs rounded-circle"
                                                    onclick="showPhotoPopup('{{ Storage::url($player->formal_photo) }}', 'Foto Formal - {{ $player->name }}')"
                                                    style="padding: 2px 4px; font-size: 0.6rem;">
                                                <i class="fas fa-search-plus fa-xs"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @else
                                    <div class="text-center py-3 bg-light">
                                        <i class="fas fa-user fa-2x text-muted mb-1"></i>
                                        <div class="small text-muted" style="font-size: 0.6rem;">Belum ada foto</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Verifikasi -->
                    <div class="section">
                        <h6 class="section-title mb-2 fw-semibold" style="font-size: 0.75rem;">
                            <i class="fas fa-check-circle me-2"></i>Status Verifikasi
                        </h6>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0 py-1 d-flex justify-content-between align-items-center" style="font-size: 0.7rem;">
                                <small style="font-size: 0.65rem;"><i class="fas fa-calendar-plus fa-xs me-2 text-muted"></i>Dibuat</small>
                                <small style="font-size: 0.65rem;">{{ $player->created_at ? \Carbon\Carbon::parse($player->created_at)->isoFormat('D MMM YYYY HH:mm') : '-' }}</small>
                            </div>
                            <div class="list-group-item px-0 py-1 d-flex justify-content-between align-items-center" style="font-size: 0.7rem;">
                                <small style="font-size: 0.65rem;"><i class="fas fa-edit fa-xs me-2 text-muted"></i>Terakhir Update</small>
                                <small style="font-size: 0.65rem;">{{ $player->updated_at ? \Carbon\Carbon::parse($player->updated_at)->isoFormat('D MMM YYYY HH:mm') : '-' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Card -->
        <div class="card-footer bg-white border-top py-2">
            <div class="text-muted" style="font-size: 0.6rem;">
                <i class="fas fa-clock me-1"></i>
                Data terakhir diperbarui: {{ $player->updated_at ? \Carbon\Carbon::parse($player->updated_at)->isoFormat('D MMMM YYYY HH:mm') : '-' }}
            </div>
        </div>
    </div>
</div>

<style>
/* Global Styles */
body {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
}

/* Breadcrumb */
.breadcrumb {
    font-size: 0.7rem;
}

.breadcrumb-item a {
    color: #6c757d;
    text-decoration: none;
}

.breadcrumb-item a:hover {
    color: #0d6efd;
}

/* Card Styles */
.card {
    border-radius: 6px;
    border-color: #e9ecef;
}

.card-header {
    background-color: #f8f9fa;
}

/* Section Styles */
.section-title {
    font-size: 0.75rem;
    color: #343a40;
    padding-bottom: 0.25rem;
    border-bottom: 1px solid #e9ecef;
}

.section {
    margin-bottom: 1rem;
}

/* Form Controls */
.form-label {
    font-size: 0.65rem;
    font-weight: 500;
    margin-bottom: 0;
}

.form-control-static {
    padding: 0.2rem 0.4rem;
    font-size: 0.7rem;
    line-height: 1.3;
    color: #212529;
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    word-break: break-word;
}

.form-control-sm {
    padding: 0.15rem 0.4rem;
    font-size: 0.7rem;
}

/* Document Items */
.document-item {
    transition: all 0.2s ease;
}

.document-item:hover {
    border-color: #0d6efd !important;
    background-color: #f8f9fa;
}

/* Photo Items */
.photo-item {
    transition: all 0.2s ease;
}

.photo-item:hover {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* List Group */
.list-group-item {
    border-color: rgba(0, 0, 0, 0.05);
}

/* Button */
.btn-xs {
    padding: 0.15rem 0.3rem;
    font-size: 0.6rem;
    border-radius: 0.2rem;
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    .card-body {
        padding: 0.75rem;
    }
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk menampilkan foto dalam popup (SweetAlert2)
        window.showPhotoPopup = function(photoUrl, title) {
            Swal.fire({
                title: title,
                html: `
                    <div class="text-center">
                        <img src="${photoUrl}" 
                             alt="${title}" 
                             style="max-width: 100%; max-height: 350px; border-radius: 6px; object-fit: contain;"
                             class="img-fluid">
                        <div class="mt-2">
                            <a href="${photoUrl}" 
                               class="btn btn-outline-primary btn-xs"
                               target="_blank">
                                <i class="fas fa-external-link-alt me-1"></i> Buka di Tab Baru
                            </a>
                        </div>
                    </div>
                `,
                showCloseButton: true,
                showConfirmButton: false,
                width: '450px',
                padding: '0.8rem',
                background: '#fff'
            });
        };
    });
</script>
@endpush
@endsection