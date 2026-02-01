@extends('admin.layouts.app')

@section('title', 'Detail Pemain - ' . ($player->name ?? ''))

@section('content')
<div class="container-fluid py-4">
    <!-- Breadcrumb Minimalis -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0">
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
    <div class="mb-4">
        <a href="{{ route('admin.team-list.show', $player->team_id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Kembali ke Tim
        </a>
    </div>

    <!-- Card Utama -->
    <div class="card border shadow-none">
        <!-- Header Card -->
        <div class="card-header bg-white border-bottom py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-user-circle me-2 text-primary"></i>Detail Pemain
                    </h5>
                </div>
                <div>
                    @if($player->is_finalized)
                    <span class="badge bg-success">
                        <i class="fas fa-check-circle me-1"></i>Final
                    </span>
                    @else
                    <span class="badge bg-warning">
                        <i class="fas fa-clock me-1"></i>Draft
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- Header Pemain -->
            <div class="player-header mb-4 pb-3 border-bottom">
                <div class="row align-items-center">
                    <div class="col-auto">
                        @if($player->formal_photo)
                        <img src="{{ Storage::url($player->formal_photo) }}" alt="{{ $player->name }}" 
                             class="rounded-circle border" style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                        <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px; background-color: #e9ecef; color: #6c757d;">
                            <i class="fas fa-user"></i>
                        </div>
                        @endif
                    </div>
                    <div class="col">
                        <h4 class="mb-1 fw-semibold">{{ $player->name ?? 'N/A' }}</h4>
                        <div class="text-muted small">
                            <span class="me-3">
                                <i class="fas fa-tag me-1"></i>No. Jersey: <strong>{{ $player->jersey_number ?? '-' }}</strong>
                            </span>
                            <span class="me-3">
                                <i class="fas fa-basketball-ball me-1"></i>Posisi: <strong>{{ $player->basketball_position ?? '-' }}</strong>
                            </span>
                            <span>
                                <i class="fas fa-venus-mars me-1"></i>
                                @if($player->gender == 'Male')
                                    Laki-laki
                                @elseif($player->gender == 'Female')
                                    Perempuan
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                        <div class="small mt-1">
                            <i class="fas fa-school me-1"></i>{{ $schoolName ?? ($player->school ?? 'N/A') }}
                            <span class="mx-2">•</span>
                            <i class="fas fa-users me-1"></i>{{ $player->team->school_name ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Pemain dalam Grid -->
            <div class="row g-4">
                <!-- Kolom Kiri -->
                <div class="col-lg-6">
                    <!-- Data Pribadi -->
                    <div class="section mb-4">
                        <h6 class="section-title mb-3 fw-semibold">
                            <i class="fas fa-id-card me-2"></i>Data Pribadi
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">NIK</label>
                                    <div class="form-control-static">{{ $player->nik ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Tanggal Lahir</label>
                                    <div class="form-control-static">
                                        @if($player->birthdate)
                                            {{ \Carbon\Carbon::parse($player->birthdate)->isoFormat('D MMMM YYYY') }}
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Email</label>
                                    <div class="form-control-static">{{ $player->email ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Telepon</label>
                                    <div class="form-control-static">{{ $player->phone ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Pendidikan -->
                    <div class="section mb-4">
                        <h6 class="section-title mb-3 fw-semibold">
                            <i class="fas fa-graduation-cap me-2"></i>Data Pendidikan
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Kelas</label>
                                    <div class="form-control-static">{{ $player->grade ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Tahun STTB</label>
                                    <div class="form-control-static">{{ $player->sttb_year ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Fisik -->
                    <div class="section mb-4">
                        <h6 class="section-title mb-3 fw-semibold">
                            <i class="fas fa-ruler-vertical me-2"></i>Data Fisik
                        </h6>
                        <div class="row">
                            <div class="col-6 col-md-3 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Tinggi</label>
                                    <div class="form-control-static">{{ $player->height ? $player->height . ' cm' : '-' }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Berat</label>
                                    <div class="form-control-static">{{ $player->weight ? $player->weight . ' kg' : '-' }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Kaos</label>
                                    <div class="form-control-static">{{ $player->tshirt_size ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Sepatu</label>
                                    <div class="form-control-static">{{ $player->shoes_size ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Orang Tua -->
                    <div class="section mb-4">
                        <h6 class="section-title mb-3 fw-semibold">
                            <i class="fas fa-users me-2"></i>Data Orang Tua
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Nama Ayah</label>
                                    <div class="form-control-static">{{ $player->father_name ?? '-' }}</div>
                                    @if($player->father_phone)
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-phone fa-xs me-1"></i>{{ $player->father_phone }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Nama Ibu</label>
                                    <div class="form-control-static">{{ $player->mother_name ?? '-' }}</div>
                                    @if($player->mother_phone)
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-phone fa-xs me-1"></i>{{ $player->mother_phone }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Media Sosial -->
                    <div class="section mb-4">
                        <h6 class="section-title mb-3 fw-semibold">
                            <i class="fas fa-share-alt me-2"></i>Media Sosial
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Instagram</label>
                                    @if($player->instagram)
                                    <div class="form-control-static">
                                        <a href="https://instagram.com/{{ ltrim($player->instagram, '@') }}" 
                                           target="_blank" 
                                           class="text-decoration-none">
                                            <i class="fab fa-instagram me-1 text-muted"></i>{{ $player->instagram }}
                                        </a>
                                    </div>
                                    @else
                                    <div class="form-control-static">-</div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">TikTok</label>
                                    @if($player->tiktok)
                                    <div class="form-control-static">
                                        <a href="https://tiktok.com/@{{ $player->tiktok }}" 
                                           target="_blank" 
                                           class="text-decoration-none">
                                            <i class="fab fa-tiktok me-1 text-muted"></i>{{ $player->tiktok }}
                                        </a>
                                    </div>
                                    @else
                                    <div class="form-control-static">-</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-lg-6">
                    <!-- Dokumen -->
                    <div class="section mb-4">
                        <h6 class="section-title mb-3 fw-semibold">
                            <i class="fas fa-file-alt me-2"></i>Dokumen
                        </h6>
                        <div class="row g-3">
                            @php
                                $documents = [
                                    ['field' => 'birth_certificate', 'name' => 'Akta Kelahiran'],
                                    ['field' => 'kk', 'name' => 'Kartu Keluarga'],
                                    ['field' => 'shun', 'name' => 'SHUN'],
                                    ['field' => 'report_identity', 'name' => 'Identitas Raport'],
                                    ['field' => 'last_report_card', 'name' => 'Raport Terakhir'],
                                    ['field' => 'assignment_letter', 'name' => 'Surat Tugas']
                                ];
                            @endphp
                            
                            @foreach($documents as $doc)
                            <div class="col-6 col-md-4">
                                <div class="document-item text-center p-2 border rounded">
                                    <div class="mb-2">
                                        <i class="fas fa-file-pdf fa-lg @if($player->{$doc['field']}) text-danger @else text-muted @endif"></i>
                                    </div>
                                    <small class="d-block text-muted mb-2">{{ $doc['name'] }}</small>
                                    @if($player->{$doc['field']})
                                    <button type="button" 
                                            class="btn btn-outline-primary btn-sm w-100"
                                            onclick="openDocument('{{ Storage::url($player->{$doc['field']}) }}')">
                                        <i class="fas fa-eye fa-xs me-1"></i>Lihat
                                    </button>
                                    @else
                                    <span class="badge bg-light text-muted border">Tidak ada</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Foto -->
                    <div class="section mb-4">
                        <h6 class="section-title mb-3 fw-semibold">
                            <i class="fas fa-images me-2"></i>Foto
                        </h6>
                        <div class="row g-3">
                            @php
                                $photos = [
                                    ['field' => 'formal_photo', 'name' => 'Foto Formal'],
                                ];
                            @endphp
                            
                            @foreach($photos as $index => $photo)
                            <div class="col-md-6">
                                <div class="photo-item border rounded overflow-hidden">
                                    @if($player->{$photo['field']})
                                    <div class="position-relative">
                                        <img src="{{ Storage::url($player->{$photo['field']}) }}" 
                                             alt="{{ $photo['name'] }}" 
                                             class="img-fluid w-100"
                                             style="height: 150px; object-fit: cover;">
                                        <div class="position-absolute top-0 start-0 end-0 bottom-0 d-flex align-items-center justify-content-center bg-dark bg-opacity-25 opacity-0 hover-opacity-100 transition">
                                            <button type="button" 
                                                    class="btn btn-light btn-sm"
                                                    onclick="showPhotoPopup('{{ Storage::url($player->{$photo['field']}) }}', '{{ $photo['name'] }} - {{ $player->name }}')">
                                                <i class="fas fa-search-plus me-1"></i>Lihat
                                            </button>
                                        </div>
                                    </div>
                                    @else
                                    <div class="text-center py-4 bg-light">
                                        <i class="fas fa-user fa-2x text-muted mb-2"></i>
                                        <div class="small text-muted">Tidak ada foto</div>
                                    </div>
                                    @endif
                                    <div class="p-2 text-center border-top">
                                        <small class="text-muted">{{ $photo['name'] }}</small>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="section">
                        <h6 class="section-title mb-3 fw-semibold">
                            <i class="fas fa-info-circle me-2"></i>Informasi
                        </h6>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-user-plus fa-xs me-2 text-muted"></i>
                                    <span class="small">Terdaftar</span>
                                </div>
                                <span class="small">
                                    {{ $player->created_at ? $player->created_at->isoFormat('D MMM YYYY HH:mm') : '-' }}
                                </span>
                            </div>
                            @if($player->finalized_at)
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-check-circle fa-xs me-2 text-muted"></i>
                                    <span class="small">Difinalisasi</span>
                                </div>
                                <span class="small">
                                    {{ $player->finalized_at->isoFormat('D MMM YYYY HH:mm') }}
                                </span>
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Card -->
        <div class="card-footer bg-white border-top py-3">
            <div class="small text-muted">
                <i class="fas fa-clock me-1"></i>
                Data terakhir diperbarui: {{ $player->updated_at ? $player->updated_at->isoFormat('D MMMM YYYY HH:mm') : '-' }}
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
    font-size: 0.875rem;
}

.breadcrumb-item a {
    color: #6c757d;
}

.breadcrumb-item a:hover {
    color: #0d6efd;
}

.breadcrumb-item.active {
    color: #495057;
}

/* Card Styles */
.card {
    border-radius: 6px;
    border-color: #e9ecef;
}

.card-header {
    background-color: #f8f9fa;
    font-weight: 500;
}

/* Section Styles */
.section-title {
    font-size: 0.95rem;
    color: #343a40;
    padding-bottom: 0.5rem;
    border-bottom: 1px solid #e9ecef;
}

.section {
    margin-bottom: 1.5rem;
}

/* Form Controls */
.form-label {
    font-size: 0.8125rem;
    font-weight: 500;
}

.form-control-static {
    min-height: calc(1.5em + 0.75rem + 2px);
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    font-weight: 400;
    line-height: 1.5;
    color: #212529;
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 4px;
}

/* Document Items */
.document-item {
    height: 100%;
    transition: all 0.2s ease;
}

.document-item:hover:not(.empty) {
    border-color: #0d6efd;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

/* Photo Items */
.photo-item {
    transition: all 0.2s ease;
}

.photo-item:hover {
    box-shadow: 0 3px 6px rgba(0,0,0,0.1);
}

.hover-opacity-100:hover {
    opacity: 1 !important;
}

.transition {
    transition: opacity 0.2s ease;
}

/* List Group */
.list-group-item {
    border-color: rgba(0,0,0,0.05);
}

/* Responsive */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .card-body {
        padding: 1.5rem;
    }
    
    .player-header .text-muted {
        font-size: 0.8125rem;
    }
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk membuka dokumen (PDF) di tab baru
        window.openDocument = function(url) {
            window.open(url, '_blank');
        };

        // Fungsi untuk menampilkan foto dalam popup
        window.showPhotoPopup = function(photoUrl, title) {
            Swal.fire({
                title: title,
                html: `
                    <div class="text-center">
                        <img src="${photoUrl}" 
                             alt="${title}" 
                             style="max-width: 100%; max-height: 500px; border-radius: 4px;"
                             class="img-fluid mb-3">
                        <div class="mt-3">
                            <a href="${photoUrl}" 
                               target="_blank" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-external-link-alt me-1"></i> Buka di Tab Baru
                            </a>
                        </div>
                    </div>
                `,
                showCloseButton: true,
                showConfirmButton: false,
                width: 600,
                padding: '1.5rem',
                background: '#fff'
            });
        };

        // Tambahkan event listener untuk semua button dokumen
        const docButtons = document.querySelectorAll('.btn-outline-primary[onclick*="openDocument"]');
        docButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const url = this.getAttribute('onclick').match(/openDocument\('([^']+)'\)/)[1];
                openDocument(url);
            });
        });

        // Tambahkan event listener untuk semua button foto
        const photoButtons = document.querySelectorAll('.btn-light[onclick*="showPhotoPopup"]');
        photoButtons.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const onclickAttr = this.getAttribute('onclick');
                const matches = onclickAttr.match(/showPhotoPopup\('([^']+)', '([^']+)'\)/);
                if (matches) {
                    const url = matches[1];
                    const title = matches[2];
                    showPhotoPopup(url, title);
                }
            });
        });
    });
</script>
@endpush
@endsection