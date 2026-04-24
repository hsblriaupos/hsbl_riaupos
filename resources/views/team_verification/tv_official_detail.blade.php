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
                    <span class="badge {{ $official->category_badge_class ?? 'bg-secondary' }}">
                        <i class="{{ $official->category_badge_icon ?? 'fas fa-user-tie' }} me-1"></i>
                        {{ $official->formatted_category ?? $official->category }}
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
                        <img src="{{ $official->formal_photo_url ?? Storage::url($official->formal_photo) }}" 
                             alt="{{ $official->name }}" 
                             class="rounded-circle border" 
                             style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                        <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center" 
                             style="width: 60px; height: 60px; background-color: #e9ecef; color: #6c757d;">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        @endif
                    </div>
                    <div class="col">
                        <h4 class="mb-1 fw-semibold">{{ $official->name ?? 'N/A' }}</h4>
                        <div class="text-muted small">
                            <span class="me-3">
                                <i class="fas fa-tag me-1"></i>Team Role: <strong>{{ $official->formatted_team_role ?? ($official->team_role ?? '-') }}</strong>
                            </span>
                            <span>
                                <i class="fas fa-venus-mars me-1"></i>
                                {{ $official->gender ?? '-' }}
                            </span>
                        </div>
                        <div class="small mt-1">
                            <i class="fas fa-school me-1"></i>{{ $schoolName ?? ($official->school_name ?? 'N/A') }}
                            <span class="mx-2">•</span>
                            <i class="fas fa-users me-1"></i>{{ $official->team->school_name ?? 'N/A' }}
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
                                    <label class="form-label small text-muted mb-1">Pendidikan Terakhir</label>
                                    <div class="form-control-static">{{ $official->last_education ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Tahun Lulus</label>
                                    <div class="form-control-static">{{ $official->graduation_year ?? '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Data Orang Tua (untuk official yang masih pelajar) -->
                    @if(($official->father_name ?? '') || ($official->mother_name ?? ''))
                    <div class="section mb-4">
                        <h6 class="section-title mb-3 fw-semibold">
                            <i class="fas fa-users me-2"></i>Data Orang Tua
                        </h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Nama Ayah</label>
                                    <div class="form-control-static">{{ $official->father_name ?? '-' }}</div>
                                    @if($official->father_phone)
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-phone fa-xs me-1"></i>{{ $official->father_phone }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="form-group">
                                    <label class="form-label small text-muted mb-1">Nama Ibu</label>
                                    <div class="form-control-static">{{ $official->mother_name ?? '-' }}</div>
                                    @if($official->mother_phone)
                                    <div class="small text-muted mt-1">
                                        <i class="fas fa-phone fa-xs me-1"></i>{{ $official->mother_phone }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
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
                                    ['field' => 'cv', 'name' => 'CV / Daftar Riwayat Hidup'],
                                    ['field' => 'certificate', 'name' => 'Sertifikat'],
                                    ['field' => 'id_card', 'name' => 'Kartu Identitas'],
                                    ['field' => 'assignment_letter', 'name' => 'Surat Tugas'],
                                ];
                            @endphp

                            @foreach($documents as $doc)
                            <div class="col-6 col-md-4">
                                <div class="document-item text-center p-2 border rounded">
                                    <div class="mb-2">
                                        <i class="fas fa-file-pdf fa-lg @if($official->{$doc['field']}) text-danger @else text-muted @endif"></i>
                                    </div>
                                    <small class="d-block text-muted mb-2">{{ $doc['name'] }}</small>
                                    @if($official->{$doc['field']})
                                    <button type="button" 
                                            class="btn btn-outline-primary btn-sm w-100"
                                            onclick="openDocument('{{ Storage::url($official->{$doc['field']}) }}')">
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
                            <div class="col-md-6">
                                <div class="photo-item border rounded overflow-hidden">
                                    @if($official->formal_photo)
                                    <div class="position-relative">
                                        <img src="{{ $official->formal_photo_url ?? Storage::url($official->formal_photo) }}" 
                                             alt="Foto Formal" 
                                             class="img-fluid w-100"
                                             style="height: 150px; object-fit: cover;">
                                        <div class="position-absolute top-0 start-0 end-0 bottom-0 d-flex align-items-center justify-content-center bg-dark bg-opacity-25 opacity-0 hover-opacity-100 transition">
                                            <button type="button" 
                                                    class="btn btn-light btn-sm"
                                                    onclick="showPhotoPopup('{{ $official->formal_photo_url ?? Storage::url($official->formal_photo) }}', 'Foto Formal - {{ $official->name }}')">
                                                <i class="fas fa-search-plus me-1"></i>Lihat
                                            </button>
                                        </div>
                                    </div>
                                    @else
                                    <div class="text-center py-4 bg-light">
                                        <i class="fas fa-user-tie fa-2x text-muted mb-2"></i>
                                        <div class="small text-muted">Tidak ada foto</div>
                                    </div>
                                    @endif
                                    <div class="p-2 text-center border-top">
                                        <small class="text-muted">Foto Formal</small>
                                    </div>
                                </div>
                            </div>
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
                                    {{ $official->created_at ? $official->created_at->isoFormat('D MMM YYYY HH:mm') : '-' }}
                                </span>
                            </div>
                            @if($official->updated_at)
                            <div class="list-group-item px-0 py-2 d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="fas fa-clock fa-xs me-2 text-muted"></i>
                                    <span class="small">Terakhir Update</span>
                                </div>
                                <span class="small">
                                    {{ $official->updated_at->isoFormat('D MMM YYYY HH:mm') }}
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
                Data terakhir diperbarui: {{ $official->updated_at ? $official->updated_at->isoFormat('D MMMM YYYY HH:mm') : '-' }}
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
    word-break: break-word;
}

/* Document Items */
.document-item {
    height: 100%;
    transition: all 0.2s ease;
}

.document-item:hover:not(.empty) {
    border-color: #0d6efd;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

/* Photo Items */
.photo-item {
    transition: all 0.2s ease;
}

.photo-item:hover {
    box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
}

.hover-opacity-100:hover {
    opacity: 1 !important;
}

.transition {
    transition: opacity 0.2s ease;
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