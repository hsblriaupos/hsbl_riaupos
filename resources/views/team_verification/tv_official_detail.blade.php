{{-- resources/views/team_verification/tv_official_detail.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detail Official - ' . ($official->name ?? ''))

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
                <a href="{{ route('admin.team-list.show', $official->team_id) }}" class="text-decoration-none">
                    <i class="fas fa-users fa-xs me-1"></i>Detail Tim
                </a>
            </li>
            <li class="breadcrumb-item active text-muted">
                <i class="fas fa-user-tie fa-xs me-1"></i>{{ $official->name ?? 'Official' }}
            </li>
        </ol>
    </nav>

    <!-- Tombol Kembali -->
    <div class="mb-3">
        <a href="{{ route('admin.team-list.show', $official->team_id) }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i>Kembali ke Tim
        </a>
    </div>

    <!-- Card Utama -->
    <div class="card border shadow-none">
        <!-- Header Card -->
        <div class="card-header bg-white border-bottom py-2">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0 fw-semibold">
                        <i class="fas fa-user-tie me-2 text-warning"></i>Detail Official
                    </h6>
                </div>
            </div>
        </div>

        <div class="card-body p-3">
            <!-- Header Official -->
            <div class="official-header mb-3 pb-2 border-bottom">
                <div class="row align-items-center">
                    <div class="col-auto">
                        @if($official->formal_photo)
                        <img src="{{ Storage::url($official->formal_photo) }}" 
                             alt="{{ $official->name }}" 
                             class="rounded-circle border" 
                             style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                        <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 50px; height: 50px; background-color: #e9ecef; color: #6c757d;">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                        @endif
                    </div>
                    <div class="col">
                        <h5 class="mb-0 fw-semibold">{{ $official->name ?? 'N/A' }}</h5>
                        <div class="text-muted small">
                            <span class="me-2">
                                <i class="fas fa-tag me-1"></i>{{ ucfirst($official->team_role ?? '-') }}
                            </span>
                            <span>
                                <i class="fas fa-venus-mars me-1"></i>
                                {{ $official->gender ?? '-' }}
                            </span>
                        </div>
                        <div class="small">
                            <i class="fas fa-school me-1"></i>{{ $official->school_name ?? 'N/A' }}
                            <span class="mx-1">•</span>
                            <i class="fas fa-building me-1"></i>{{ $official->team->school_name ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Official dalam Grid -->
            <div class="row g-2">
                <!-- Kolom Kiri -->
                <div class="col-lg-6">
                    <!-- Data Pribadi -->
                    <div class="section mb-3">
                        <h6 class="section-title mb-2 fw-semibold">
                            <i class="fas fa-id-card me-2"></i>Data Pribadi
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-0">NIK</label>
                                    <div class="form-control-static form-control-sm">{{ $official->nik ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-0">Tanggal Lahir</label>
                                    <div class="form-control-static form-control-sm">
                                        @if($official->birthdate)
                                        {{ \Carbon\Carbon::parse($official->birthdate)->isoFormat('D MMM YYYY') }}
                                        @else
                                        -
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-0">Email</label>
                                    <div class="form-control-static form-control-sm">{{ $official->email ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-0">Telepon</label>
                                    <div class="form-control-static form-control-sm">{{ $official->phone ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-0">Tinggi Badan</label>
                                    <div class="form-control-static form-control-sm">
                                        {{ $official->height ? intval($official->height) . ' cm' : '-' }}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-0">Berat Badan</label>
                                    <div class="form-control-static form-control-sm">
                                        {{ $official->weight ? intval($official->weight) . ' kg' : '-' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Social Media -->
                    <div class="section mb-3">
                        <h6 class="section-title mb-2 fw-semibold">
                            <i class="fas fa-share-alt me-2"></i>Media Sosial
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-0">Instagram</label>
                                    <div class="form-control-static form-control-sm">
                                        @if($official->instagram)
                                            <a href="https://instagram.com/{{ $official->instagram }}" target="_blank" class="small">
                                                <i class="fab fa-instagram me-1"></i>{{ $official->instagram }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-0">TikTok</label>
                                    <div class="form-control-static form-control-sm">
                                        @if($official->tiktok)
                                            <a href="https://tiktok.com/@{{ $official->tiktok }}" target="_blank" class="small">
                                                <i class="fab fa-tiktok me-1"></i>{{ $official->tiktok }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ukuran -->
                    <div class="section mb-3">
                        <h6 class="section-title mb-2 fw-semibold">
                            <i class="fas fa-tshirt me-2"></i>Ukuran
                        </h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-0">Ukuran Baju</label>
                                    <div class="form-control-static form-control-sm">{{ $official->tshirt_size ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-0">Ukuran Sepatu</label>
                                    <div class="form-control-static form-control-sm">{{ $official->shoes_size ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-lg-6">
                    <!-- Dokumen -->
                    <div class="section mb-3">
                        <h6 class="section-title mb-2 fw-semibold">
                            <i class="fas fa-file-alt me-2"></i>Dokumen
                        </h6>
                        <div class="row g-2">
                            @php
                                $documents = [
                                    ['field' => 'license_photo', 'name' => 'Foto Lisensi', 'icon' => 'fa-id-card'],
                                    ['field' => 'identity_card', 'name' => 'Kartu Identitas', 'icon' => 'fa-id-card'],
                                    ['field' => 'assignment_letter', 'name' => 'Surat Tugas', 'icon' => 'fa-file-signature'],
                                ];
                            @endphp

                            @foreach($documents as $doc)
                            <div class="col-12 col-md-6">
                                <div class="document-item p-2 border rounded h-100">
                                    <div class="d-flex align-items-center justify-content-between mb-1">
                                        <div>
                                            <i class="fas {{ $doc['icon'] }} me-1 {{ $official->{$doc['field']} ? 'text-danger' : 'text-muted' }}"></i>
                                            <small class="fw-semibold">{{ $doc['name'] }}</small>
                                        </div>
                                    </div>
                                    @if($official->{$doc['field']})
                                    <button type="button" 
                                            class="btn btn-outline-primary btn-xs w-100 mt-1"
                                            onclick="showDocumentPopup('{{ Storage::url($official->{$doc['field']}) }}', '{{ $doc['name'] }}')"
                                            style="font-size: 11px; padding: 4px 6px;">
                                        <i class="fas fa-eye fa-xs me-1"></i>Lihat
                                    </button>
                                    @else
                                    <div class="text-center py-1 bg-light rounded mt-1">
                                        <span class="badge bg-light text-muted" style="font-size: 10px;">Tidak ada</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Foto Formal -->
                    <div class="section mb-3">
                        <h6 class="section-title mb-2 fw-semibold">
                            <i class="fas fa-image me-2"></i>Foto Formal
                        </h6>
                        <div class="row">
                            <div class="col-12">
                                <div class="photo-item border rounded overflow-hidden">
                                    @if($official->formal_photo)
                                    <div class="position-relative d-flex justify-content-center bg-light">
                                        <img src="{{ Storage::url($official->formal_photo) }}" 
                                             alt="Foto Formal" 
                                             class="img-fluid"
                                             style="max-width: 100%; height: auto; max-height: 200px; object-fit: contain; cursor: pointer;"
                                             onclick="showPhotoPopup('{{ Storage::url($official->formal_photo) }}', 'Foto Formal - {{ $official->name }}')">
                                        <div class="position-absolute top-0 end-0 p-1">
                                            <button type="button" 
                                                    class="btn btn-light btn-xs rounded-circle"
                                                    onclick="showPhotoPopup('{{ Storage::url($official->formal_photo) }}', 'Foto Formal - {{ $official->name }}')"
                                                    style="padding: 2px 5px; font-size: 10px;">
                                                <i class="fas fa-search-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @else
                                    <div class="text-center py-3 bg-light">
                                        <i class="fas fa-user-tie fa-2x text-muted mb-1"></i>
                                        <div class="small text-muted">Belum ada foto</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Verifikasi -->
                    <div class="section">
                        <h6 class="section-title mb-2 fw-semibold">
                            <i class="fas fa-check-circle me-2"></i>Status Verifikasi
                        </h6>
                        <div class="list-group list-group-flush">
                            
                            <div class="list-group-item px-0 py-1 d-flex justify-content-between align-items-center">
                                <small><i class="fas fa-calendar-plus fa-xs me-2 text-muted"></i>Dibuat</small>
                                <small>{{ $official->created_at ? \Carbon\Carbon::parse($official->created_at)->isoFormat('D MMM YYYY HH:mm') : '-' }}</small>
                            </div>
                            <div class="list-group-item px-0 py-1 d-flex justify-content-between align-items-center">
                                <small><i class="fas fa-edit fa-xs me-2 text-muted"></i>Terakhir Update</small>
                                <small>{{ $official->updated_at ? \Carbon\Carbon::parse($official->updated_at)->isoFormat('D MMM YYYY HH:mm') : '-' }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Card -->
        <div class="card-footer bg-white border-top py-2">
            <div class="small text-muted">
                <i class="fas fa-clock me-1"></i>
                Data terakhir diperbarui: {{ $official->updated_at ? \Carbon\Carbon::parse($official->updated_at)->isoFormat('D MMMM YYYY HH:mm') : '-' }}
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
    font-size: 0.8rem;
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
    font-size: 0.85rem;
    color: #343a40;
    padding-bottom: 0.25rem;
    border-bottom: 1px solid #e9ecef;
}

.section {
    margin-bottom: 1rem;
}

/* Form Controls */
.form-label {
    font-size: 0.7rem;
    font-weight: 600;
    margin-bottom: 0;
}

.form-control-static {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    line-height: 1.4;
    color: #212529;
    background-color: #f8f9fa;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    word-break: break-word;
}

.form-control-sm {
    padding: 0.2rem 0.5rem;
    font-size: 0.75rem;
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
    font-size: 0.75rem;
}

/* Button */
.btn-xs {
    padding: 0.2rem 0.4rem;
    font-size: 0.7rem;
    border-radius: 0.2rem;
}

/* Custom Modal Styles */
.document-modal-content {
    max-height: 60vh;
    overflow-y: auto;
}

.pdf-container {
    width: 100%;
    min-height: 400px;
}

@media (max-width: 768px) {
    .container-fluid {
        padding-left: 0.75rem;
        padding-right: 0.75rem;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    .pdf-container {
        min-height: 300px;
    }
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk menampilkan dokumen (PDF/Gambar) dalam popup
        window.showDocumentPopup = function(url, title) {
            const fileExtension = url.split('.').pop().toLowerCase();
            
            if (fileExtension === 'pdf') {
                Swal.fire({
                    title: title,
                    html: `
                        <div class="document-modal-content">
                            <iframe src="${url}" 
                                    class="pdf-container"
                                    style="width: 100%; height: 400px; border: 1px solid #dee2e6; border-radius: 4px;"
                                    frameborder="0">
                            </iframe>
                            <div class="mt-2 text-center">
                                <a href="${url}" 
                                   class="btn btn-outline-primary btn-xs"
                                   target="_blank">
                                    <i class="fas fa-external-link-alt me-1"></i> Buka di Tab Baru
                                </a>
                            </div>
                        </div>
                    `,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: '550px',
                    padding: '0.8rem',
                    background: '#fff'
                });
            } else if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExtension)) {
                Swal.fire({
                    title: title,
                    html: `
                        <div class="text-center">
                            <img src="${url}" 
                                 alt="${title}" 
                                 style="max-width: 100%; max-height: 350px; border-radius: 4px; object-fit: contain;"
                                 class="img-fluid">
                            <div class="mt-2">
                                <a href="${url}" 
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
            } else {
                Swal.fire({
                    title: title,
                    html: `
                        <div class="text-center">
                            <i class="fas fa-file fa-3x text-muted mb-2"></i>
                            <p class="small">File tidak bisa ditampilkan di popup</p>
                            <div>
                                <a href="${url}" class="btn btn-primary btn-sm" download>
                                    <i class="fas fa-download me-1"></i> Download
                                </a>
                                <a href="${url}" class="btn btn-outline-secondary btn-sm ms-1" target="_blank">
                                    <i class="fas fa-external-link-alt me-1"></i> Buka Tab Baru
                                </a>
                            </div>
                        </div>
                    `,
                    showCloseButton: true,
                    showConfirmButton: false,
                    width: '400px',
                    padding: '1rem',
                    background: '#fff'
                });
            }
        };

        // Fungsi untuk menampilkan foto dalam popup
        window.showPhotoPopup = function(photoUrl, title) {
            Swal.fire({
                title: title,
                html: `
                    <div class="text-center">
                        <img src="${photoUrl}" 
                             alt="${title}" 
                             style="max-width: 100%; max-height: 300px; border-radius: 6px; object-fit: contain;"
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
                width: '420px',
                padding: '0.8rem',
                background: '#fff'
            });
        };
    });
</script>
@endpush
@endsection