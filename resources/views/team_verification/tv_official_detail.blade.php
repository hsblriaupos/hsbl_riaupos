{{-- resources/views/team_verification/tv_official_detail.blade.php --}}
@extends('admin.layouts.app')

@section('title', 'Detail Official - ' . ($official->name ?? ''))

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
    <div class="mb-4">
        <a href="{{ route('admin.team-list.show', $official->team_id) }}" class="btn btn-outline-secondary btn-sm">
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
                        <i class="fas fa-user-tie me-2 text-warning"></i>Detail Official
                    </h5>
                </div>
                <div>
                    <span class="badge bg-info">
                        <i class="fas fa-id-card me-1"></i>
                        {{ ucfirst($official->role ?? 'Official') }}
                    </span>
                </div>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- Header Official -->
            <div class="official-header mb-4 pb-3 border-bottom">
                <div class="row align-items-center">
                    <div class="col-auto">
                        @if($official->formal_photo)
                        <img src="{{ Storage::url($official->formal_photo) }}" 
                             alt="{{ $official->name }}" 
                             class="rounded-circle border" 
                             style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                        <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px; background-color: #e9ecef; color: #6c757d;">
                            <i class="fas fa-user-tie fa-2x"></i>
                        </div>
                        @endif
                    </div>
                    <div class="col">
                        <h4 class="mb-1 fw-semibold">{{ $official->name ?? 'N/A' }}</h4>
                        <div class="text-muted small">
                            <span class="me-3">
                                <i class="fas fa-tag me-1"></i>Team Role: <strong>{{ ucfirst($official->team_role ?? '-') }}</strong>
                            </span>
                            <span>
                                <i class="fas fa-venus-mars me-1"></i>
                                {{ $official->gender ?? '-' }}
                            </span>
                        </div>
                        <div class="small mt-1">
                            <i class="fas fa-school me-1"></i>{{ $official->school_name ?? 'N/A' }}
                            <span class="mx-2">•</span>
                            <i class="fas fa-building me-1"></i>{{ $official->team->school_name ?? 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Official dalam Grid -->
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
                                    <div class="form-control-static">{{ $official->nik ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Tanggal Lahir</label>
                                    <div class="form-control-static">
                                        @if($official->birthdate)
                                        {{ \Carbon\Carbon::parse($official->birthdate)->isoFormat('D MMMM YYYY') }}
                                        @else
                                        -
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Email</label>
                                    <div class="form-control-static">{{ $official->email ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Telepon</label>
                                    <div class="form-control-static">{{ $official->phone ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Tinggi Badan</label>
                                    <div class="form-control-static">{{ $official->height ? $official->height . ' cm' : '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Berat Badan</label>
                                    <div class="form-control-static">{{ $official->weight ? $official->weight . ' kg' : '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Social Media -->
                    <div class="section mb-4">
                        <h6 class="section-title mb-3 fw-semibold">
                            <i class="fas fa-share-alt me-2"></i>Media Sosial
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Instagram</label>
                                    <div class="form-control-static">
                                        @if($official->instagram)
                                            <a href="https://instagram.com/{{ $official->instagram }}" target="_blank">
                                                <i class="fab fa-instagram me-1"></i>{{ $official->instagram }}
                                            </a>
                                        @else
                                            -
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">TikTok</label>
                                    <div class="form-control-static">
                                        @if($official->tiktok)
                                            <a href="https://tiktok.com/@{{ $official->tiktok }}" target="_blank">
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
                    <div class="section mb-4">
                        <h6 class="section-title mb-3 fw-semibold">
                            <i class="fas fa-tshirt me-2"></i>Ukuran
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Ukuran Baju</label>
                                    <div class="form-control-static">{{ $official->tshirt_size ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Ukuran Sepatu</label>
                                    <div class="form-control-static">{{ $official->shoes_size ?? '-' }}</div>
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
                                    ['field' => 'license_photo', 'name' => 'Foto Lisensi', 'icon' => 'fa-id-card'],
                                    ['field' => 'identity_card', 'name' => 'Kartu Identitas', 'icon' => 'fa-id-card'],
                                    ['field' => 'assignment_letter', 'name' => 'Surat Tugas', 'icon' => 'fa-file-signature'],
                                ];
                            @endphp

                            @foreach($documents as $doc)
                            <div class="col-12 col-md-6">
                                <div class="document-item p-3 border rounded h-100">
                                    <div class="d-flex align-items-start justify-content-between mb-2">
                                        <div>
                                            <i class="fas {{ $doc['icon'] }} fa-lg me-2 {{ $official->{$doc['field']} ? 'text-danger' : 'text-muted' }}"></i>
                                            <small class="fw-semibold">{{ $doc['name'] }}</small>
                                        </div>
                                    </div>
                                    @if($official->{$doc['field']})
                                    <div class="mt-2">
                                        <button type="button" 
                                                class="btn btn-outline-primary btn-sm w-100"
                                                onclick="openDocument('{{ Storage::url($official->{$doc['field']}) }}')">
                                            <i class="fas fa-eye fa-xs me-1"></i>Lihat Dokumen
                                        </button>
                                    </div>
                                    @else
                                    <div class="mt-2 text-center py-2 bg-light rounded">
                                        <span class="badge bg-light text-muted">Tidak ada dokumen</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Foto Formal -->
                    <div class="section mb-4">
                        <h6 class="section-title mb-3 fw-semibold">
                            <i class="fas fa-image me-2"></i>Foto Formal
                        </h6>
                        <div class="row">
                            <div class="col-12">
                                <div class="photo-item border rounded overflow-hidden">
                                    @if($official->formal_photo)
                                    <div class="position-relative">
                                        <img src="{{ Storage::url($official->formal_photo) }}" 
                                             alt="Foto Formal" 
                                             class="img-fluid w-100"
                                             style="height: 200px; object-fit: cover; cursor: pointer;"
                                             onclick="showPhotoPopup('{{ Storage::url($official->formal_photo) }}', 'Foto Formal - {{ $official->name }}')">
                                        <div class="position-absolute top-0 end-0 p-2">
                                            <button type="button" 
                                                    class="btn btn-light btn-sm rounded-circle"
                                                    onclick="showPhotoPopup('{{ Storage::url($official->formal_photo) }}', 'Foto Formal - {{ $official->name }}')">
                                                <i class="fas fa-search-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @else
                                    <div class="text-center py-5 bg-light">
                                        <i class="fas fa-user-tie fa-3x text-muted mb-2"></i>
                                        <div class="small text-muted">Belum ada foto formal</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Verifikasi -->
                    <div class="section">
                        <h6 class="section-title mb-3 fw-semibold">
                            <i class="fas fa-check-circle me-2"></i>Status Verifikasi
                        </h6>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-check-circle fa-xs me-2 text-muted"></i>
                                    <span class="small">Status Verifikasi</span>
                                </div>
                                <span class="badge {{ $official->verification_status == 'verified' ? 'bg-success' : ($official->verification_status == 'rejected' ? 'bg-danger' : 'bg-warning') }}">
                                    {{ ucfirst($official->verification_status ?? 'Pending') }}
                                </span>
                            </div>
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-lock fa-xs me-2 text-muted"></i>
                                    <span class="small">Finalisasi</span>
                                </div>
                                <span class="small">
                                    @if($official->is_finalized)
                                        <span class="badge bg-success">Telah Difinalisasi</span>
                                        <div>
                                            <small>{{ $official->finalized_at ? \Carbon\Carbon::parse($official->finalized_at)->isoFormat('D MMM YYYY HH:mm') : '-' }}</small>
                                        </div>
                                    @else
                                        <span class="badge bg-secondary">Belum Difinalisasi</span>
                                    @endif
                                </span>
                            </div>
                            @if($official->unlocked_by_admin)
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-unlock-alt fa-xs me-2 text-muted"></i>
                                    <span class="small">Dibuka oleh Admin</span>
                                </div>
                                <span class="small text-muted">
                                    {{ $official->unlocked_at ? \Carbon\Carbon::parse($official->unlocked_at)->isoFormat('D MMM YYYY HH:mm') : '-' }}
                                </span>
                            </div>
                            @endif
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-calendar-plus fa-xs me-2 text-muted"></i>
                                    <span class="small">Dibuat</span>
                                </div>
                                <span class="small">
                                    {{ $official->created_at ? \Carbon\Carbon::parse($official->created_at)->isoFormat('D MMM YYYY HH:mm') : '-' }}
                                </span>
                            </div>
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-edit fa-xs me-2 text-muted"></i>
                                    <span class="small">Terakhir Update</span>
                                </div>
                                <span class="small">
                                    {{ $official->updated_at ? \Carbon\Carbon::parse($official->updated_at)->isoFormat('D MMM YYYY HH:mm') : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Card -->
        <div class="card-footer bg-white border-top py-3">
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
    font-size: 0.875rem;
}

.breadcrumb-item a {
    color: #6c757d;
    text-decoration: none;
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
    border-bottom: 2px solid #e9ecef;
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
    word-break: break-word;
}

/* Document Items */
.document-item {
    transition: all 0.2s ease;
}

.document-item:hover {
    border-color: #0d6efd !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

/* Photo Items */
.photo-item {
    transition: all 0.2s ease;
}

.photo-item:hover {
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

/* List Group */
.list-group-item {
    border-color: rgba(0, 0, 0, 0.05);
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
    
    .official-header .text-muted {
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
    });
</script>
@endpush
@endsection