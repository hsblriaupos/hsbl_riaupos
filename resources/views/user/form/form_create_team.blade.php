@extends('user.form.layout')

@section('title', 'Buat Tim Baru - SBL')

@section('content')
<div class="container py-2">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <!-- Progress Steps - SAMA PERSIS dengan form player -->
            <div class="progress-steps mb-2">
                <div class="step-item">
                    <div class="step-circle active">1</div>
                    <span class="step-label">Data Tim</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item">
                    <div class="step-circle">2</div>
                    <span class="step-label">Data Pemain</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item">
                    <div class="step-circle">3</div>
                    <span class="step-label">Upload</span>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="card border-0 shadow-sm form-card">
                <div class="card-header bg-white border-0 pt-2 px-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-1">
                        <div>
                            <h6 class="fw-semibold mb-0">
                                <i class="fas fa-plus-circle text-primary me-1"></i>
                                Form Pendaftaran Tim Baru
                            </h6>
                            <p class="text-muted small mb-0" style="font-size: 10px;">Lengkapi data di bawah untuk membuat tim baru</p>
                        </div>
                        <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary btn-sm rounded-pill" style="font-size: 11px; padding: 3px 10px;">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body p-3">
                    <form id="createTeamForm" action="{{ route('form.team.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Alert Messages - SAMA PERSIS dengan form player -->
                        @if(session('warning'))
                        <div class="alert alert-warning py-1 px-2 mb-2 stay-alert" style="animation: none; transition: none; font-size: 11px;">
                            <i class="fas fa-exclamation-triangle me-1"></i> {{ session('warning') }}
                        </div>
                        @endif

                        @if(session('info'))
                        <div class="alert alert-info py-1 px-2 mb-2 stay-alert" style="animation: none; transition: none; font-size: 11px;">
                            <i class="fas fa-info-circle me-1"></i> {{ session('info') }}
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="alert alert-danger py-1 px-2 mb-2 stay-alert" style="animation: none; transition: none; font-size: 11px;">
                            <i class="fas fa-times-circle me-1"></i> 
                            <strong>Terdapat {{ $errors->count() }} kesalahan:</strong>
                            <ul class="mb-0 mt-1 ps-3" style="font-size: 10px;">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- Informasi Sekolah -->
                        <div class="form-section">
                            <div class="section-title mb-1">
                                <div class="title-icon">
                                    <i class="fas fa-school"></i>
                                </div>
                                <h6 class="mb-0">Informasi Sekolah</h6>
                            </div>

                            <div class="row g-1">
                                <div class="col-12">
                                    <div class="row g-1">
                                        <div class="col-6">
                                            <div class="choice-card-mini" data-option="existing">
                                                <div class="d-flex align-items-center">
                                                    <input class="form-check-input me-1" type="radio" name="school_option"
                                                        id="school_existing" value="existing"
                                                        {{ old('school_option', 'existing') == 'existing' ? 'checked' : '' }}
                                                        style="margin-top: 0;">
                                                    <label class="form-check-label" for="school_existing" style="font-size: 10px;">Pilih Sekolah</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="choice-card-mini" data-option="new">
                                                <div class="d-flex align-items-center">
                                                    <input class="form-check-input me-1" type="radio" name="school_option"
                                                        id="school_new" value="new"
                                                        {{ old('school_option') == 'new' ? 'checked' : '' }}
                                                        style="margin-top: 0;">
                                                    <label class="form-check-label" for="school_new" style="font-size: 10px;">Sekolah Baru</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Existing School -->
                                <div class="col-12" id="existingSchoolSection" style="{{ old('school_option', 'existing') == 'new' ? 'display: none;' : '' }}">
                                    <label class="form-label">Pilih Sekolah <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm @error('existing_school_id') is-invalid @enderror"
                                        id="existing_school_id" name="existing_school_id">
                                        <option value="">-- Pilih Sekolah --</option>
                                        @foreach($schools as $school)
                                        <option value="{{ $school->id }}" {{ old('existing_school_id') == $school->id ? 'selected' : '' }}>
                                            {{ $school->school_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- New School Fields -->
                                <div class="col-12" id="newSchoolFields" style="{{ old('school_option') == 'new' ? 'display: block;' : 'display: none;' }}">
                                    <div class="row g-1">
                                        <div class="col-12">
                                            <label class="form-label">Nama Sekolah <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control form-control-sm @error('new_school_name') is-invalid @enderror"
                                                id="new_school_name" name="new_school_name"
                                                value="{{ old('new_school_name') }}" placeholder="Contoh: SMA Negeri 1">
                                            <div id="schoolCheckMessage" class="mt-1" style="display: none;"></div>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label">Kota <span class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" id="new_city_id" name="new_city_id">
                                                <option value="">Pilih</option>
                                                @foreach($cities as $city)
                                                <option value="{{ $city->id }}" {{ old('new_city_id') == $city->id ? 'selected' : '' }}>
                                                    {{ $city->city_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label">Jenjang <span class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" id="new_category_name" name="new_category_name">
                                                <option value="">Pilih</option>
                                                <option value="SMA" {{ old('new_category_name') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                                <option value="SMK" {{ old('new_category_name') == 'SMK' ? 'selected' : '' }}>SMK</option>
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label class="form-label">Status <span class="text-danger">*</span></label>
                                            <select class="form-select form-select-sm" id="new_type" name="new_type">
                                                <option value="">Pilih</option>
                                                <option value="NEGERI" {{ old('new_type') == 'NEGERI' ? 'selected' : '' }}>Negeri</option>
                                                <option value="SWASTA" {{ old('new_type') == 'SWASTA' ? 'selected' : '' }}>Swasta</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Logo Upload -->
                                <div class="col-12" id="logoSection">
                                    <label class="form-label" id="logoLabel">Logo Sekolah</label>
                                    <input type="file" class="form-control form-control-sm" id="school_logo"
                                        name="school_logo" accept=".jpg,.jpeg,.png,.webp">
                                    <small class="text-muted d-block mt-1" id="logoHint"></small>
                                </div>

                                <!-- Logo Preview - SAMA PERSIS dengan form player -->
                                <div class="col-12" id="logoPreview" style="display: none;">
                                    <div class="thumbnail-wrapper">
                                        <div class="thumbnail-file">
                                            <img id="previewImage" src="#" alt="Preview Logo" style="width: 28px; height: 28px; object-fit: cover; border-radius: 3px;">
                                        </div>
                                        <div class="thumbnail-info">
                                            <span class="thumb-name" id="previewStatus"></span>
                                            <button type="button" class="btn-remove-thumb" id="removeLogoBtn" title="Hapus">
                                                <i class="fas fa-times-circle"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Tim & Kompetisi - 3 KOLOM DESKTOP -->
                        <div class="form-section">
                            <div class="section-title mb-1">
                                <div class="title-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h6 class="mb-0">Informasi Tim & Kompetisi</h6>
                            </div>

                            <div class="row g-1">
                                <div class="col-12 col-md-4">
                                    <label class="form-label">Kompetisi <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm @error('competition') is-invalid @enderror"
                                        id="competition" name="competition">
                                        <option value="">Pilih</option>
                                        @foreach($competitions as $competition)
                                        <option value="{{ $competition }}" {{ old('competition') == $competition ? 'selected' : '' }}>
                                            {{ $competition }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label">Kategori Tim <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm @error('team_category') is-invalid @enderror"
                                        id="team_category" name="team_category">
                                        <option value="">Pilih</option>
                                        @foreach($teamCategoryEnums as $category)
                                        <option value="{{ $category }}" {{ old('team_category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-4">
                                    <label class="form-label">Season <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm @error('season') is-invalid @enderror"
                                        id="season" name="season">
                                        <option value="">Pilih</option>
                                        @foreach($seasons as $season)
                                        <option value="{{ $season }}" {{ old('season') == $season ? 'selected' : '' }}>
                                            {{ $season }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Series <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm @error('series') is-invalid @enderror"
                                        id="series" name="series">
                                        <option value="">Pilih</option>
                                        @foreach($series as $serie)
                                        <option value="{{ $serie }}" {{ old('series') == $serie ? 'selected' : '' }}>
                                            {{ $serie }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Nama Pendaftar <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm @error('registered_by') is-invalid @enderror"
                                        id="registered_by" name="registered_by" value="{{ old('registered_by') }}" placeholder="Masukkan nama lengkap">
                                </div>
                            </div>
                        </div>

                        <!-- Dokumen Pendukung - DENGAN PREVIEW SAMA PERSIS form player -->
                        <div class="form-section">
                            <div class="section-title mb-1">
                                <div class="title-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <h6 class="mb-0">Dokumen Pendukung</h6>
                            </div>

                            <div class="row g-1">
                                <!-- Surat Persetujuan -->
                                <div class="col-12 col-md-6">
                                    <div class="doc-upload-box p-2">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <i class="fas fa-file-pdf text-danger" style="font-size: 12px;"></i>
                                            <span class="fw-medium" style="font-size: 11px;">Surat Persetujuan Kepala Sekolah <span class="text-danger">*</span></span>
                                        </div>
                                        <input type="file" class="form-control form-control-sm doc-input"
                                            id="recommendation_letter" name="recommendation_letter" accept=".pdf" required>
                                        <small class="text-muted d-block mt-1" style="font-size: 9px;">Max 2MB, PDF</small>
                                        
                                        <!-- Thumbnail Preview -->
                                        <div class="thumbnail-container mt-1" id="thumbnail-recommendation" style="display: none;">
                                            <div class="thumbnail-wrapper">
                                                <div class="thumbnail-file" id="thumb-file-recommendation"></div>
                                                <div class="thumbnail-info">
                                                    <span class="thumb-name" id="thumb-name-recommendation"></span>
                                                    <button type="button" class="btn-remove-thumb" data-doc="recommendation" title="Hapus">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bukti Koran -->
                                <div class="col-12 col-md-6">
                                    <div class="doc-upload-box p-2">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <i class="fas fa-newspaper text-warning" style="font-size: 12px;"></i>
                                            <span class="fw-medium" style="font-size: 11px;">Bukti Koran <span class="text-danger">*</span></span>
                                        </div>
                                        <input type="file" class="form-control form-control-sm doc-input"
                                            id="koran" name="koran" accept=".jpg,.jpeg,.png,.pdf" required>
                                        <small class="text-muted d-block mt-1" style="font-size: 9px;">Max 2MB, JPG/PNG/PDF</small>
                                        
                                        <!-- Thumbnail Preview -->
                                        <div class="thumbnail-container mt-1" id="thumbnail-koran" style="display: none;">
                                            <div class="thumbnail-wrapper">
                                                <div class="thumbnail-file" id="thumb-file-koran"></div>
                                                <div class="thumbnail-info">
                                                    <span class="thumb-name" id="thumb-name-koran"></span>
                                                    <button type="button" class="btn-remove-thumb" data-doc="koran" title="Hapus">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info py-1 px-2 mt-2 mb-0 stay-alert" style="animation: none; transition: none; font-size: 11px;">
                                <i class="fas fa-credit-card me-1"></i>
                                <strong>Pembayaran nanti:</strong> Upload bukti transfer setelah mengisi data kapten
                            </div>
                        </div>

                        <input type="hidden" id="school_name" name="school_name" value="{{ old('school_name') }}">

                        <!-- Submit Buttons - SAMA PERSIS dengan form player -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-1 pt-2 border-top mt-2">
                            <small class="text-muted" style="font-size: 10px;">
                                <i class="fas fa-shield-alt text-primary me-1"></i>Data aman
                            </small>
                            <div class="d-flex gap-1">
                                <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary btn-sm rounded-pill" style="font-size: 11px; padding: 4px 12px;">Batal</a>
                                <button type="submit" class="btn btn-primary btn-sm rounded-pill" style="font-size: 11px; padding: 4px 12px;">
                                    Buat Tim <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal - SAMA PERSIS dengan form player -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center p-2">
            <div class="spinner-border text-primary" style="width: 1.2rem; height: 1.2rem;"></div>
            <p class="mb-0 small mt-1">Memproses...</p>
        </div>
    </div>
</div>

<style>
    /* ========== COPY PASTE PERSIS DARI FORM PLAYER ========== */
    
    .progress-steps {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .step-item {
        text-align: center;
    }

    .step-circle {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 11px;
        margin: 0 auto 2px;
    }

    .step-circle.active {
        background: #4361ee;
        color: white;
    }

    .step-circle.completed {
        background: #10b981;
        color: white;
    }

    .step-label {
        font-size: 9px;
        color: #6c757d;
    }

    .step-line {
        width: 30px;
        height: 2px;
        background: #e9ecef;
    }

    @media (max-width: 576px) {
        .step-line {
            width: 20px;
        }
        .step-label {
            font-size: 7px;
        }
        .step-circle {
            width: 24px;
            height: 24px;
            font-size: 9px;
        }
    }

    .form-card {
        border-radius: 12px;
        overflow: hidden;
    }

    .form-section {
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e9ecef;
    }

    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .title-icon {
        width: 24px;
        height: 24px;
        background: rgba(67, 97, 238, 0.1);
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4361ee;
    }

    .title-icon i {
        font-size: 11px;
    }

    .section-title h6 {
        font-weight: 600;
        font-size: 12px;
        margin: 0;
    }

    .form-label {
        font-size: 10px;
        font-weight: 500;
        margin-bottom: 2px;
        color: #2b2d42;
    }

    .form-control,
    .form-select {
        border-radius: 5px;
        font-size: 11px;
        padding: 4px 8px;
    }

    .form-control-sm,
    .form-select-sm {
        padding: 4px 6px;
        font-size: 11px;
        border-radius: 5px;
    }

    .alert {
        border-radius: 6px;
    }

    .stay-alert {
        animation: none !important;
        transition: none !important;
        transform: none !important;
        opacity: 1 !important;
        display: block !important;
        visibility: visible !important;
    }

    .doc-upload-box {
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 6px;
        background: #f8f9fa;
    }

    .thumbnail-container {
        margin-top: 6px;
    }

    .thumbnail-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 4px 8px;
    }

    .thumbnail-file {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        border-radius: 4px;
        flex-shrink: 0;
    }

    .thumbnail-file i {
        font-size: 18px;
    }

    .thumbnail-file img {
        width: 28px;
        height: 28px;
        object-fit: cover;
        border-radius: 3px;
    }

    .thumbnail-info {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .thumb-name {
        font-size: 10px;
        color: #2b2d42;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        flex: 1;
    }

    .btn-remove-thumb {
        background: none;
        border: none;
        color: #dc2626;
        cursor: pointer;
        padding: 2px;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.7;
    }

    .btn-remove-thumb:hover {
        opacity: 1;
    }

    .btn-primary {
        background: #4361ee;
        border: none;
    }

    .btn-primary:hover {
        background: #3a56d4;
    }

    .g-1 {
        --bs-gutter-y: 0.25rem;
        --bs-gutter-x: 0.25rem;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 12px !important;
        }
        .card-header {
            padding: 8px 12px !important;
        }
        .doc-upload-box {
            padding: 6px;
        }
        .thumbnail-wrapper {
            padding: 3px 6px;
        }
        .thumbnail-file {
            width: 28px;
            height: 28px;
        }
        .thumb-name {
            font-size: 9px;
        }
    }

    .choice-card-mini {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 6px 8px;
        cursor: pointer;
    }

    .choice-card-mini:hover {
        border-color: #4361ee;
        background: rgba(67, 97, 238, 0.02);
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('createTeamForm');
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    const existingSection = document.getElementById('existingSchoolSection');
    const newSection = document.getElementById('newSchoolFields');
    const existingSchoolSelect = document.getElementById('existing_school_id');
    const logoPreview = document.getElementById('logoPreview');
    const previewImage = document.getElementById('previewImage');
    const previewStatus = document.getElementById('previewStatus');
    const schoolNameHidden = document.getElementById('school_name');
    const logoInput = document.getElementById('school_logo');
    const logoLabel = document.getElementById('logoLabel');
    const logoHint = document.getElementById('logoHint');
    const removeLogoBtn = document.getElementById('removeLogoBtn');

    let currentSchoolHasLogo = false;
    let currentSchoolLogoUrl = null;

    // Lock stay alerts
    function lockStayAlerts() {
        document.querySelectorAll('.stay-alert').forEach(function(alert) {
            alert.style.display = 'block';
            alert.style.opacity = '1';
            alert.style.visibility = 'visible';
            alert.classList.remove('d-none', 'fade', 'hide', 'invisible');
        });
    }
    lockStayAlerts();
    setInterval(lockStayAlerts, 500);

    // ==================== FUNGSI THUMBNAIL SAMA PERSIS FORM PLAYER ====================
    function createFileThumbnail(file, docName, isImage = false) {
        const container = document.getElementById(`thumbnail-${docName}`);
        if (!container) return;

        const fileDiv = document.getElementById(`thumb-file-${docName}`);
        const nameSpan = document.getElementById(`thumb-name-${docName}`);

        if (fileDiv) {
            if (isImage || file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    fileDiv.innerHTML = `<img src="${e.target.result}" alt="preview" style="width:28px; height:28px; object-fit:cover; border-radius:3px;">`;
                };
                reader.readAsDataURL(file);
            } else {
                const iconMap = {
                    'application/pdf': 'fa-file-pdf',
                    'application/msword': 'fa-file-word',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'fa-file-word',
                    'application/vnd.ms-excel': 'fa-file-excel',
                    'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'fa-file-excel'
                };
                const icon = iconMap[file.type] || 'fa-file';
                fileDiv.innerHTML = `<i class="fas ${icon} text-secondary" style="font-size: 18px;"></i>`;
            }
        }

        if (nameSpan) {
            let fileName = file.name;
            if (fileName.length > 25) fileName = fileName.substring(0, 22) + '...';
            nameSpan.textContent = fileName;
        }

        container.style.display = 'block';
    }

    function removeFileAndThumbnail(docName) {
        const fileInput = document.getElementById(docName === 'recommendation' ? 'recommendation_letter' : 'koran');
        const thumbnail = document.getElementById(`thumbnail-${docName}`);

        if (fileInput) {
            fileInput.value = '';
        }

        if (thumbnail) {
            thumbnail.style.display = 'none';
            const fileDiv = document.getElementById(`thumb-file-${docName}`);
            if (fileDiv) fileDiv.innerHTML = '';
            const nameSpan = document.getElementById(`thumb-name-${docName}`);
            if (nameSpan) nameSpan.textContent = '';
        }
    }

    // Setup document upload thumbnails
    const docUploads = [
        { id: 'recommendation_letter', docName: 'recommendation' },
        { id: 'koran', docName: 'koran' }
    ];

    docUploads.forEach(function(doc) {
        const fileInput = document.getElementById(doc.id);
        if (fileInput) {
            const newInput = fileInput.cloneNode(true);
            fileInput.parentNode.replaceChild(newInput, fileInput);

            newInput.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const isImage = file.type.startsWith('image/');
                    createFileThumbnail(file, doc.docName, isImage);
                }
            });
        }
    });

    // Remove thumbnail event delegation
    document.addEventListener('click', function(e) {
        const removeBtn = e.target.closest('.btn-remove-thumb');
        if (removeBtn) {
            e.preventDefault();
            e.stopPropagation();
            const docName = removeBtn.getAttribute('data-doc');
            if (docName) {
                removeFileAndThumbnail(docName);
            }
            return false;
        }
    });

    function toggleSchoolFields() {
        const schoolOption = document.querySelector('input[name="school_option"]:checked').value;
        if (schoolOption === 'new') {
            existingSection.style.display = 'none';
            newSection.style.display = 'block';
            if (existingSchoolSelect) existingSchoolSelect.value = '';
            hideLogoPreview();
        } else {
            existingSection.style.display = 'block';
            newSection.style.display = 'none';
            document.getElementById('new_school_name').value = '';
            const schoolCheckMsg = document.getElementById('schoolCheckMessage');
            if (schoolCheckMsg) schoolCheckMsg.style.display = 'none';
            if (existingSchoolSelect && existingSchoolSelect.value) {
                checkSchoolLogo(existingSchoolSelect.value);
            } else {
                hideLogoPreview();
                updateLogoRequirement(false);
            }
        }
    }

    function hideLogoPreview() {
        if (logoPreview) logoPreview.style.display = 'none';
        if (previewImage) previewImage.src = '#';
        currentSchoolHasLogo = false;
        currentSchoolLogoUrl = null;
        if (logoInput) logoInput.value = '';
    }

    function updateLogoRequirement(required) {
        if (required) {
            if (logoLabel) logoLabel.innerHTML = 'Logo Sekolah <span class="text-danger">*</span>';
            if (logoHint) logoHint.innerHTML = 'Sekolah belum memiliki logo. Wajib upload logo.';
            if (logoInput) logoInput.required = true;
        } else {
            if (logoLabel) logoLabel.innerHTML = 'Logo Sekolah <span class="text-muted">(Opsional)</span>';
            if (logoHint) logoHint.innerHTML = 'Sekolah sudah memiliki logo. Upload hanya jika ingin mengganti.';
            if (logoInput) logoInput.required = false;
        }
    }

    function checkSchoolLogo(schoolId) {
        if (!schoolId) {
            hideLogoPreview();
            updateLogoRequirement(false);
            return;
        }
        if (previewStatus) previewStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memeriksa...';
        if (logoPreview) logoPreview.style.display = 'block';
        if (previewImage) previewImage.src = '#';
        
        fetch('{{ route("form.team.checkSchoolLogo") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ school_id: schoolId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.has_logo) {
                currentSchoolHasLogo = true;
                currentSchoolLogoUrl = data.logo_url;
                if (previewImage) previewImage.src = data.logo_url + '?v=' + Date.now();
                if (previewStatus) previewStatus.innerHTML = '<i class="fas fa-check-circle text-success"></i> Logo sudah ada';
                updateLogoRequirement(false);
            } else {
                currentSchoolHasLogo = false;
                currentSchoolLogoUrl = null;
                if (previewImage) previewImage.src = '{{ asset("images/default-school-logo.png") }}';
                if (previewStatus) previewStatus.innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i> Belum ada logo, wajib upload';
                updateLogoRequirement(true);
            }
            if (logoPreview) logoPreview.style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
            if (previewStatus) previewStatus.innerHTML = '<i class="fas fa-times-circle text-danger"></i> Gagal memeriksa';
            updateLogoRequirement(true);
            if (logoPreview) logoPreview.style.display = 'block';
        });
    }

    function updateSchoolName() {
        const schoolOption = document.querySelector('input[name="school_option"]:checked').value;
        if (schoolOption === 'existing') {
            const selectedOption = existingSchoolSelect.options[existingSchoolSelect.selectedIndex];
            if (selectedOption && selectedOption.value) {
                schoolNameHidden.value = selectedOption.textContent;
            }
        } else {
            schoolNameHidden.value = document.getElementById('new_school_name').value;
        }
    }

    // Logo preview handler
    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file maksimal 2MB');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    if (previewImage) previewImage.src = e.target.result;
                    if (logoPreview) logoPreview.style.display = 'block';
                    if (previewStatus) previewStatus.innerHTML = '<i class="fas fa-upload"></i> Logo baru akan diupload';
                }
                reader.readAsDataURL(file);
            }
        });
    }

    if (removeLogoBtn) {
        removeLogoBtn.addEventListener('click', function() {
            if (logoInput) logoInput.value = '';
            hideLogoPreview();
            if (!currentSchoolHasLogo) {
                updateLogoRequirement(true);
            }
        });
    }

    // Event listeners
    document.querySelectorAll('.choice-card-mini').forEach(card => {
        card.addEventListener('click', function() {
            const option = this.dataset.option;
            const radio = document.getElementById(`school_${option}`);
            if (radio) radio.checked = true;
            toggleSchoolFields();
        });
    });

    document.querySelectorAll('input[name="school_option"]').forEach(radio => {
        radio.addEventListener('change', function() {
            toggleSchoolFields();
        });
    });

    if (existingSchoolSelect) {
        existingSchoolSelect.addEventListener('change', function() {
            if (this.value) {
                checkSchoolLogo(this.value);
            } else {
                hideLogoPreview();
                updateLogoRequirement(false);
            }
            updateSchoolName();
        });
    }

    const newSchoolNameInput = document.getElementById('new_school_name');
    if (newSchoolNameInput) {
        newSchoolNameInput.addEventListener('input', updateSchoolName);
    }

    // Form validation and submit
    if (form) {
        form.addEventListener('submit', function(e) {
            const schoolOption = document.querySelector('input[name="school_option"]:checked').value;
            
            if (schoolOption === 'existing') {
                if (!existingSchoolSelect.value) {
                    alert('Pilih sekolah terlebih dahulu');
                    e.preventDefault();
                    return false;
                }
                if (!currentSchoolHasLogo && !logoInput.files[0]) {
                    alert('Sekolah ini belum memiliki logo. Silakan upload logo sekolah.');
                    e.preventDefault();
                    return false;
                }
            } else {
                if (!document.getElementById('new_school_name').value.trim()) {
                    alert('Masukkan nama sekolah baru');
                    e.preventDefault();
                    return false;
                }
                if (!logoInput.files[0]) {
                    alert('Upload logo sekolah (wajib untuk sekolah baru)');
                    e.preventDefault();
                    return false;
                }
                if (!document.getElementById('new_city_id').value) {
                    alert('Pilih kota');
                    e.preventDefault();
                    return false;
                }
            }

            const requiredFields = ['competition', 'team_category', 'season', 'series', 'registered_by'];
            for (const field of requiredFields) {
                const el = document.getElementById(field);
                if (!el.value.trim()) {
                    alert(`Masukkan ${el.previousElementSibling?.innerText?.replace('*', '') || field}`);
                    el.focus();
                    e.preventDefault();
                    return false;
                }
            }

            if (!document.getElementById('recommendation_letter').files[0]) {
                alert('Upload surat rekomendasi');
                e.preventDefault();
                return false;
            }
            if (!document.getElementById('koran').files[0]) {
                alert('Upload bukti koran');
                e.preventDefault();
                return false;
            }

            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memproses...';
            }
            if (loadingModal) loadingModal.show();
        });
    }

    toggleSchoolFields();
    if (existingSchoolSelect && existingSchoolSelect.value) {
        checkSchoolLogo(existingSchoolSelect.value);
    }
});
</script>
@endpush

@endsection