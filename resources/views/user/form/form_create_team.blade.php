@extends('user.form.layout')

@section('title', 'Buat Tim Baru - SBL')

@section('content')
<div class="container py-4">

    <!-- Progress Steps -->
    <div class="mb-4">
        <div class="d-flex justify-content-center align-items-center">
            <div class="text-center">
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2"
                    style="width: 36px; height: 36px; font-size: 1rem; font-weight: 500;">
                    1
                </div>
                <div class="small fw-semibold text-primary">Data Tim</div>
            </div>
            <div class="mx-3">
                <div style="width: 80px; height: 2px; background: linear-gradient(90deg, #4361ee, #e9ecef);"></div>
            </div>
            <div class="text-center">
                <div class="rounded-circle bg-white border d-flex align-items-center justify-content-center mx-auto mb-2"
                    style="width: 36px; height: 36px; font-size: 1rem; font-weight: 500; border-color: #dee2e6 !important;">
                    2
                </div>
                <div class="small text-secondary">Data Pemain</div>
            </div>
            <div class="mx-3">
                <div style="width: 80px; height: 2px; background: #e9ecef;"></div>
            </div>
            <div class="text-center">
                <div class="rounded-circle bg-white border d-flex align-items-center justify-content-center mx-auto mb-2"
                    style="width: 36px; height: 36px; font-size: 1rem; font-weight: 500; border-color: #dee2e6 !important;">
                    3
                </div>
                <div class="small text-secondary">Upload</div>
            </div>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 950px; border-radius: 20px;">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-semibold mb-1" style="color: #2b2d42;">
                        <i class="fas fa-plus-circle text-primary me-2" style="font-size: 1.2rem;"></i>
                        Form Pendaftaran Tim Baru
                    </h5>
                    <p class="text-muted small mb-0">Lengkapi data di bawah untuk membuat tim baru</p>
                </div>
                <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('warning'))
        <div class="px-4 pt-3">
            <div class="alert alert-warning alert-dismissible fade show py-2 border-0 bg-soft-warning" role="alert" style="border-radius: 12px;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-exclamation-triangle me-2" style="color: #f8961e;"></i>
                    <small>{{ session('warning') }}</small>
                    <button type="button" class="btn-close btn-sm ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
        @endif

        @if(session('info'))
        <div class="px-4 pt-3">
            <div class="alert alert-info alert-dismissible fade show py-2 border-0 bg-soft-info" role="alert" style="border-radius: 12px;">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle me-2" style="color: #0ea5e9;"></i>
                    <small>{{ session('info') }}</small>
                    <button type="button" class="btn-close btn-sm ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="px-4 pt-3">
            <div class="alert alert-danger alert-dismissible fade show py-2 border-0 bg-soft-danger" role="alert" style="border-radius: 12px;">
                <div class="d-flex align-items-start">
                    <i class="fas fa-times-circle me-2 mt-1" style="color: #f94144;"></i>
                    <div>
                        <small class="d-block fw-semibold">Terdapat {{ $errors->count() }} kesalahan:</small>
                        @foreach($errors->all() as $error)
                        <small class="d-block">{{ $error }}</small>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Content -->
        <div class="card-body p-4">
            <form id="createTeamForm" action="{{ route('form.team.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Quick Guide -->
                <div class="bg-light p-3 rounded-3 mb-4" style="background: #f8f9fa;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-lightbulb text-primary me-2" style="font-size: 1rem;"></i>
                        <span class="small fw-semibold me-3">Tips:</span>
                        <span class="small text-muted me-3">1. Buat Tim</span>
                        <span class="small text-muted me-3">→</span>
                        <span class="small text-muted me-3">2. Data Kapten</span>
                        <span class="small text-muted me-3">→</span>
                        <span class="small text-muted">3. Bayar</span>
                    </div>
                </div>

                <!-- 2 Kolom Layout -->
                <div class="row g-4">
                    <!-- KOLOM KIRI: Informasi Sekolah -->
                    <div class="col-md-6">
                        <div class="border-end pe-3">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-2 d-flex align-items-center justify-content-center me-2"
                                    style="width: 32px; height: 32px;">
                                    <i class="fas fa-school" style="font-size: 1rem;"></i>
                                </div>
                                <h6 class="fw-semibold mb-0" style="color: #2b2d42;">Informasi Sekolah</h6>
                            </div>

                            <!-- School Option Selection -->
                            <div class="row g-2 mb-3">
                                <div class="col-12">
                                    <div class="border rounded-3 p-3 cursor-pointer option-card" data-option="existing">
                                        <div class="d-flex align-items-center mb-2">
                                            <input class="form-check-input me-2" type="radio" name="school_option"
                                                id="school_existing" value="existing"
                                                {{ old('school_option', 'existing') == 'existing' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-medium small" for="school_existing">Pilih Sekolah</label>
                                        </div>
                                        <p class="text-muted small ms-4 mb-0">Gunakan data sekolah yang sudah ada</p>
                                    </div>
                                </div>
                                <div class="col-12 mt-2">
                                    <div class="border rounded-3 p-3 cursor-pointer option-card" data-option="new">
                                        <div class="d-flex align-items-center mb-2">
                                            <input class="form-check-input me-2" type="radio" name="school_option"
                                                id="school_new" value="new"
                                                {{ old('school_option') == 'new' ? 'checked' : '' }}>
                                            <label class="form-check-label fw-medium small" for="school_new">Sekolah Baru</label>
                                        </div>
                                        <p class="text-muted small ms-4 mb-0">Tambah sekolah baru</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Existing School Fields -->
                            <div id="existingSchoolSection" class="bg-light p-3 rounded-3 mb-3"
                                style="{{ old('school_option', 'existing') == 'new' ? 'display: none;' : '' }}">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small fw-medium mb-1">Pilih Sekolah <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm @error('existing_school_id') is-invalid @enderror"
                                            id="existing_school_id" name="existing_school_id">
                                            <option value="">-- Pilih Sekolah --</option>
                                            @foreach($schools as $school)
                                            <option value="{{ $school->id }}"
                                                {{ old('existing_school_id') == $school->id ? 'selected' : '' }}>
                                                {{ $school->school_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-medium mb-1" id="logoLabelExisting">Logo Sekolah</label>
                                        <input type="file" class="form-control form-control-sm" id="school_logo_existing"
                                            name="school_logo" accept=".jpg,.jpeg,.png,.webp">
                                        <small class="text-muted d-block mt-1" id="logoHintExisting"></small>
                                    </div>
                                </div>
                            </div>

                            <!-- New School Fields -->
                            <div id="newSchoolFields" class="bg-light p-3 rounded-3 mb-3"
                                style="{{ old('school_option') == 'new' ? 'display: block;' : 'display: none;' }}">
                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small fw-medium mb-1">Nama Sekolah <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm @error('new_school_name') is-invalid @enderror"
                                            id="new_school_name" name="new_school_name"
                                            value="{{ old('new_school_name') }}"
                                            placeholder="Contoh: SMA Negeri 1"
                                            autocomplete="off">
                                        <div id="schoolCheckMessage" class="mt-2" style="display: none;"></div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-medium mb-1">Logo Sekolah <span class="text-danger">*</span></label>
                                        <input type="file" class="form-control form-control-sm" id="school_logo_new"
                                            name="school_logo" accept=".jpg,.jpeg,.png,.webp">
                                        <small class="text-muted d-block mt-1">Upload logo sekolah (wajib untuk sekolah baru)</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-medium mb-1">Kota <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="new_city_id" name="new_city_id">
                                            <option value="">Pilih</option>
                                            @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('new_city_id') == $city->id ? 'selected' : '' }}>
                                                {{ $city->city_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-medium mb-1">Jenjang <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="new_category_name" name="new_category_name">
                                            <option value="">Pilih</option>
                                            <option value="SMA" {{ old('new_category_name') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                            <option value="SMK" {{ old('new_category_name') == 'SMK' ? 'selected' : '' }}>SMK</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-medium mb-1">Status <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm" id="new_type" name="new_type">
                                            <option value="">Pilih</option>
                                            <option value="NEGERI" {{ old('new_type') == 'NEGERI' ? 'selected' : '' }}>Negeri</option>
                                            <option value="SWASTA" {{ old('new_type') == 'SWASTA' ? 'selected' : '' }}>Swasta</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Logo Preview -->
                            <div id="logoPreview" class="mt-2 text-center" style="display: none;">
                                <img id="previewImage" src="#" alt="Preview Logo" style="max-width: 80px; max-height: 80px;" class="rounded border p-1">
                                <p class="small text-muted mt-1" id="previewStatus"></p>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: Informasi Tim & Kompetisi -->
                    <div class="col-md-6">
                        <div class="ps-3">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-2 d-flex align-items-center justify-content-center me-2"
                                    style="width: 32px; height: 32px;">
                                    <i class="fas fa-users" style="font-size: 1rem;"></i>
                                </div>
                                <h6 class="fw-semibold mb-0" style="color: #2b2d42;">Informasi Tim & Kompetisi</h6>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label small fw-medium mb-1">Kompetisi <span class="text-danger">*</span></label>
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

                                <div class="col-12">
                                    <label class="form-label small fw-medium mb-1">Kategori Tim <span class="text-danger">*</span></label>
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

                                <div class="col-md-6">
                                    <label class="form-label small fw-medium mb-1">Season <span class="text-danger">*</span></label>
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

                                <div class="col-md-6">
                                    <label class="form-label small fw-medium mb-1">Series <span class="text-danger">*</span></label>
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

                                <div class="col-12">
                                    <label class="form-label small fw-medium mb-1">Nama Pendaftar <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm @error('registered_by') is-invalid @enderror"
                                        id="registered_by" name="registered_by"
                                        value="{{ old('registered_by') }}"
                                        placeholder="Masukkan nama lengkap">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dokumen Pendukung -->
                <div class="mt-2">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-2 d-flex align-items-center justify-content-center me-2"
                            style="width: 32px; height: 32px;">
                            <i class="fas fa-file-alt" style="font-size: 1rem;"></i>
                        </div>
                        <h6 class="fw-semibold mb-0" style="color: #2b2d42;">Dokumen Pendukung</h6>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                    <span class="small fw-medium">Surat Persetujuan Kepala Sekolah <span class="text-danger">*</span></span>
                                </div>
                                <input type="file" class="form-control form-control-sm"
                                    id="recommendation_letter" name="recommendation_letter" accept=".pdf" required>
                                <small class="text-muted d-block mt-1">Maks. 2MB, PDF</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-newspaper text-warning me-2"></i>
                                    <span class="small fw-medium">Bukti Koran <span class="text-danger">*</span></span>
                                </div>
                                <input type="file" class="form-control form-control-sm"
                                    id="koran" name="koran" accept=".jpg,.jpeg,.png,.pdf" required>
                                <small class="text-muted d-block mt-1">Maks. 2MB, JPG/PNG/PDF</small>
                            </div>
                        </div>
                    </div>

                    <div class="alert mt-3 mb-0 py-2 px-3 border-0 bg-soft-warning" role="alert" style="border-radius: 10px;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-credit-card text-warning me-2" style="font-size: 0.9rem;"></i>
                            <small class="text-dark">
                                <span class="fw-medium">Pembayaran nanti:</span> Upload bukti setelah isi data kapten
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Hidden Field -->
                <input type="hidden" id="school_name" name="school_name" value="{{ old('school_name') }}">

                <!-- Submit Buttons -->
                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-4">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt text-primary me-1"></i>Data aman
                    </small>
                    <div>
                        <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4 me-2">
                            Batal
                        </a>
                        <button type="submit" id="submitBtn" class="btn btn-primary btn-sm rounded-pill px-4">
                            Buat Tim <i class="fas fa-arrow-right ms-1"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow" style="border-radius: 16px;">
            <div class="modal-body text-center p-4">
                <div class="spinner-border text-primary mb-3" style="width: 2rem; height: 2rem;" role="status"></div>
                <h6 class="fw-semibold mb-1">Memproses...</h6>
                <small class="text-muted">Mohon tunggu sebentar</small>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-soft-warning { background: rgba(248, 150, 30, 0.1); }
    .bg-soft-danger { background: rgba(249, 65, 68, 0.1); }
    .bg-soft-success { background: rgba(16, 185, 129, 0.1); }
    .bg-soft-info { background: rgba(14, 165, 233, 0.1); }
    
    .option-card {
        transition: all 0.2s;
        border: 1px solid #dee2e6;
        cursor: pointer;
    }
    .option-card:hover {
        border-color: #4361ee;
        background: rgba(67, 97, 238, 0.02);
    }
    .option-card.selected {
        border-color: #4361ee;
        background: rgba(67, 97, 238, 0.05);
    }
    
    .form-control-sm, .form-select-sm {
        border-radius: 8px;
        font-size: 0.9rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }
    
    .btn-sm {
        padding: 0.4rem 1rem;
        font-size: 0.9rem;
    }
    .btn-primary {
        background: #4361ee;
        border-color: #4361ee;
    }
    .btn-primary:hover {
        background: #3a56d4;
        border-color: #3a56d4;
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(67, 97, 238, 0.2);
    }
    
    .border-end {
        border-right: 1px solid #e9ecef !important;
        height: 100%;
    }
    
    @media (max-width: 768px) {
        .border-end {
            border-right: none !important;
            border-bottom: 1px solid #e9ecef !important;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .ps-3 { padding-left: 0 !important; }
        .pe-3 { padding-right: 0 !important; }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const existingSection = document.getElementById('existingSchoolSection');
    const newSection = document.getElementById('newSchoolFields');
    const existingSchoolSelect = document.getElementById('existing_school_id');
    const logoPreview = document.getElementById('logoPreview');
    const previewImage = document.getElementById('previewImage');
    const previewStatus = document.getElementById('previewStatus');
    const schoolNameHidden = document.getElementById('school_name');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('createTeamForm');
    const loadingModalEl = document.getElementById('loadingModal');
    const loadingModal = loadingModalEl ? new bootstrap.Modal(loadingModalEl) : null;
    
    // State
    let currentSchoolHasLogo = false;
    let currentSchoolLogoUrl = null;
    
    // Helper: Show notification
    function showNotification(message, type = 'info') {
        // Cek apakah sudah ada alert container
        let alertContainer = document.getElementById('dynamicAlert');
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.id = 'dynamicAlert';
            alertContainer.className = 'px-4 pt-3';
            const formCard = document.querySelector('.card-body');
            if (formCard) formCard.insertBefore(alertContainer, formCard.firstChild);
        }
        
        const alertClass = type === 'info' ? 'alert-info' : (type === 'warning' ? 'alert-warning' : 'alert-success');
        const icon = type === 'info' ? 'fa-info-circle' : (type === 'warning' ? 'fa-exclamation-triangle' : 'fa-check-circle');
        
        alertContainer.innerHTML = `
            <div class="alert ${alertClass} alert-dismissible fade show py-2 border-0 bg-soft-${type === 'info' ? 'info' : (type === 'warning' ? 'warning' : 'success')}" role="alert" style="border-radius: 12px;">
                <div class="d-flex align-items-center">
                    <i class="fas ${icon} me-2"></i>
                    <small>${message}</small>
                    <button type="button" class="btn-close btn-sm ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
        `;
        
        setTimeout(() => {
            const alert = alertContainer.querySelector('.alert');
            if (alert) {
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alertContainer.innerHTML.includes(message)) {
                        alertContainer.innerHTML = '';
                    }
                }, 300);
            }
        }, 5000);
    }
    
    // ==================== 1. TOGGLE SCHOOL FIELDS ====================
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
    
    // ==================== 2. UPDATE CARD SELECTION ====================
    function updateCardSelection() {
        const schoolOption = document.querySelector('input[name="school_option"]:checked').value;
        document.querySelectorAll('.option-card').forEach(card => {
            if (card.dataset.option === schoolOption) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
        });
    }
    
    // ==================== 3. HIDE LOGO PREVIEW ====================
    function hideLogoPreview() {
        if (logoPreview) logoPreview.style.display = 'none';
        if (previewImage) previewImage.src = '#';
        currentSchoolHasLogo = false;
        currentSchoolLogoUrl = null;
    }
    
    // ==================== 4. UPDATE LOGO REQUIREMENT UI ====================
    function updateLogoRequirement(required) {
        const logoLabel = document.getElementById('logoLabelExisting');
        const hintSpan = document.getElementById('logoHintExisting');
        const logoInput = document.getElementById('school_logo_existing');
        
        if (required) {
            if (logoLabel) logoLabel.innerHTML = 'Logo Sekolah <span class="text-danger">*</span>';
            if (hintSpan) hintSpan.innerHTML = 'Sekolah belum memiliki logo. Wajib upload logo.';
            if (logoInput) logoInput.required = true;
        } else {
            if (logoLabel) logoLabel.innerHTML = 'Logo Sekolah <span class="text-muted">(Opsional)</span>';
            if (hintSpan) hintSpan.innerHTML = 'Sekolah sudah memiliki logo. Upload hanya jika ingin mengganti.';
            if (logoInput) logoInput.required = false;
        }
    }
    
    // ==================== 5. CHECK SCHOOL LOGO (EXISTING SCHOOL) ====================
    function checkSchoolLogo(schoolId) {
        if (!schoolId) {
            hideLogoPreview();
            updateLogoRequirement(false);
            return;
        }
        
        if (previewStatus) previewStatus.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memeriksa logo...';
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
                if (previewStatus) previewStatus.innerHTML = '<i class="fas fa-check-circle text-success"></i> Logo sudah ada. Upload hanya jika ingin mengganti.';
                updateLogoRequirement(false);
            } else {
                currentSchoolHasLogo = false;
                currentSchoolLogoUrl = null;
                if (previewImage) previewImage.src = '{{ asset("images/default-school-logo.png") }}';
                if (previewStatus) previewStatus.innerHTML = '<i class="fas fa-exclamation-triangle text-warning"></i> Sekolah belum memiliki logo. Wajib upload logo.';
                updateLogoRequirement(true);
            }
            if (logoPreview) logoPreview.style.display = 'block';
        })
        .catch(error => {
            console.error('Error checking school logo:', error);
            if (previewStatus) previewStatus.innerHTML = '<i class="fas fa-times-circle text-danger"></i> Gagal memeriksa logo. Silakan upload manual.';
            updateLogoRequirement(true);
            if (logoPreview) logoPreview.style.display = 'block';
        });
    }
    
    // ==================== 6. CHECK SCHOOL EXISTS (NEW SCHOOL) ====================
    let typingTimer;
    const doneTypingInterval = 500;
    const schoolInput = document.getElementById('new_school_name');
    const schoolCheckMessage = document.getElementById('schoolCheckMessage');
    
    function checkSchoolExists(schoolName) {
        if (!schoolCheckMessage) return;
        schoolCheckMessage.innerHTML = '<div class="alert py-2 px-3 mb-0 border-0 bg-light"><small><i class="fas fa-spinner fa-spin me-1"></i>Memeriksa ketersediaan...</small></div>';
        schoolCheckMessage.style.display = 'block';
        
        fetch('{{ route("form.team.checkSchoolExists") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ school_name: schoolName })
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                schoolCheckMessage.innerHTML = `
                    <div class="alert alert-danger py-2 px-3 mb-0 border-0 bg-soft-danger" style="border-radius: 8px;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-times-circle me-2 mt-1" style="color: #dc2626;"></i>
                            <div>
                                <span class="fw-medium d-block small">❌ GAGAL: Sekolah sudah terdaftar!</span>
                                <span class="small">"${data.school.school_name}" sudah ada di database.</span><br>
                                <span class="small fw-semibold">Silakan gunakan opsi <strong>"Pilih Sekolah"</strong> untuk memilih sekolah ini.</span>
                            </div>
                        </div>
                    </div>
                `;
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.title = 'Sekolah sudah terdaftar, gunakan opsi Pilih Sekolah';
                }
            } else {
                schoolCheckMessage.innerHTML = `
                    <div class="alert alert-success py-2 px-3 mb-0 border-0 bg-soft-success" style="border-radius: 8px;">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-check-circle me-2 mt-1" style="color: #10b981;"></i>
                            <div>
                                <span class="fw-medium d-block small">✅ Sekolah tersedia!</span>
                                <span class="small">"${schoolName}" belum terdaftar. Silakan lengkapi data sekolah baru.</span>
                            </div>
                        </div>
                    </div>
                `;
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.title = '';
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (schoolCheckMessage) schoolCheckMessage.style.display = 'none';
            if (submitBtn) submitBtn.disabled = false;
        });
    }
    
    if (schoolInput) {
        schoolInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
            const schoolName = this.value.trim();
            if (schoolName.length < 3) {
                if (schoolCheckMessage) schoolCheckMessage.style.display = 'none';
                if (submitBtn) submitBtn.disabled = false;
                return;
            }
            typingTimer = setTimeout(() => checkSchoolExists(schoolName), doneTypingInterval);
        });
    }
    
    // ==================== 7. LOGO PREVIEW HANDLER ====================
    function handleLogoPreview(inputId) {
        const input = document.getElementById(inputId);
        if (!input) return;
        
        input.addEventListener('change', function(e) {
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
                    if (previewStatus) previewStatus.innerHTML = '<i class="fas fa-upload"></i> Logo baru akan diupload.';
                }
                reader.readAsDataURL(file);
            } else if (!currentSchoolHasLogo && inputId === 'school_logo_existing') {
                hideLogoPreview();
            }
        });
    }
    
    // ==================== 8. UPDATE SCHOOL NAME ====================
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
    
    // ==================== 9. FORM VALIDATION ====================
    function validateAllFields() {
        const schoolOption = document.querySelector('input[name="school_option"]:checked').value;
        
        if (schoolOption === 'existing') {
            if (!existingSchoolSelect.value) {
                alert('Pilih sekolah terlebih dahulu');
                existingSchoolSelect.focus();
                return false;
            }
            
            if (!currentSchoolHasLogo && !document.getElementById('school_logo_existing').files[0]) {
                alert('Sekolah ini belum memiliki logo. Silakan upload logo sekolah.');
                document.getElementById('school_logo_existing').focus();
                return false;
            }
        } else {
            const newSchoolName = document.getElementById('new_school_name').value.trim();
            if (!newSchoolName) {
                alert('Masukkan nama sekolah baru');
                document.getElementById('new_school_name').focus();
                return false;
            }
            
            if (schoolCheckMessage && schoolCheckMessage.innerHTML.includes('sudah terdaftar')) {
                alert('Sekolah sudah terdaftar! Silakan gunakan opsi "Pilih Sekolah"');
                document.getElementById('school_existing').checked = true;
                toggleSchoolFields();
                return false;
            }
            
            if (!document.getElementById('school_logo_new').files[0]) {
                alert('Upload logo sekolah (wajib untuk sekolah baru)');
                document.getElementById('school_logo_new').focus();
                return false;
            }
            
            if (!document.getElementById('new_city_id').value) {
                alert('Pilih kota');
                document.getElementById('new_city_id').focus();
                return false;
            }
            if (!document.getElementById('new_category_name').value) {
                alert('Pilih jenjang');
                document.getElementById('new_category_name').focus();
                return false;
            }
            if (!document.getElementById('new_type').value) {
                alert('Pilih status sekolah');
                document.getElementById('new_type').focus();
                return false;
            }
        }
        
        const requiredFields = [
            { id: 'competition', name: 'Kompetisi' },
            { id: 'team_category', name: 'Kategori Tim' },
            { id: 'season', name: 'Season' },
            { id: 'series', name: 'Series' },
            { id: 'registered_by', name: 'Nama Pendaftar' }
        ];
        
        for (const field of requiredFields) {
            const element = document.getElementById(field.id);
            if (!element || !element.value.trim()) {
                alert(`Pilih/Masukkan ${field.name}`);
                if (element) element.focus();
                return false;
            }
        }
        
        if (!document.getElementById('recommendation_letter').files[0]) {
            alert('Upload surat rekomendasi');
            document.getElementById('recommendation_letter').focus();
            return false;
        }
        if (!document.getElementById('koran').files[0]) {
            alert('Upload bukti koran');
            document.getElementById('koran').focus();
            return false;
        }
        
        return true;
    }
    
    // ==================== 10. FORM SUBMIT ====================
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateAllFields()) {
                e.preventDefault();
                return false;
            }
            
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                submitBtn.disabled = true;
            }
            if (loadingModal) loadingModal.show();
            return true;
        });
    }
    
    // ==================== 11. EVENT LISTENERS ====================
    document.querySelectorAll('.option-card').forEach(card => {
        card.addEventListener('click', function() {
            const option = this.dataset.option;
            const radio = document.getElementById(`school_${option}`);
            if (radio) radio.checked = true;
            toggleSchoolFields();
            updateCardSelection();
        });
    });
    
    document.querySelectorAll('input[name="school_option"]').forEach(radio => {
        radio.addEventListener('change', function() {
            toggleSchoolFields();
            updateCardSelection();
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
    
    handleLogoPreview('school_logo_existing');
    handleLogoPreview('school_logo_new');
    
    // Initialize
    toggleSchoolFields();
    updateCardSelection();
    
    if (existingSchoolSelect && existingSchoolSelect.value) {
        checkSchoolLogo(existingSchoolSelect.value);
    }
});
</script>
@endpush

@endsection