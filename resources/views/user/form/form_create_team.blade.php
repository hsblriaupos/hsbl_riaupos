@extends('user.form.layout')

@section('title', 'Buat Tim Baru - HSBL')

@section('content')
<div class="container py-4">
    <!-- Progress Steps - Slim -->
    <div class="mb-4">
        <div class="d-flex justify-content-center align-items-center">
            <div class="text-center position-relative">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-1" 
                     style="width: 36px; height: 36px;">
                    <span class="fw-medium">1</span>
                </div>
                <div class="small fw-semibold mt-1">Data Tim</div>
            </div>
            <div class="mx-4 position-relative">
                <div class="progress" style="width: 100px; height: 3px;">
                    <div class="progress-bar bg-secondary" style="width: 100%"></div>
                </div>
            </div>
            <div class="text-center position-relative">
                <div class="rounded-circle bg-light text-secondary d-inline-flex align-items-center justify-content-center mb-1 border border-secondary" 
                     style="width: 36px; height: 36px;">
                    <span class="fw-medium">2</span>
                </div>
                <div class="small text-muted mt-1">Data Kapten</div>
            </div>
        </div>
    </div>

    <!-- Main Form Card -->
    <div class="card border-0 shadow-lg mx-auto" style="max-width: 900px;">
        <!-- Card Header -->
        <div class="card-header bg-white border-bottom py-4 px-5">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 fw-bold text-primary">
                        <i class="fas fa-plus-circle me-2"></i>Form Pendaftaran Tim Baru
                    </h5>
                    <p class="text-muted mb-0 small">Lengkapi data di bawah untuk membuat tim baru</p>
                </div>
                <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('warning'))
        <div class="mx-5 mt-4">
            <div class="alert alert-warning alert-dismissible fade show py-2" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="mx-5 mt-4">
            <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                <i class="fas fa-times-circle me-2"></i>
                <strong class="me-2">Error:</strong>
                @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
                @endforeach
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        <!-- Form Content -->
        <div class="card-body p-5">
            <form id="createTeamForm" action="{{ route('form.team.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Quick Guide -->
                <div class="alert alert-info border-start border-info border-4 bg-light mb-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-lightbulb text-info fs-4"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="alert-heading mb-1">💡 Tips Pendaftaran</h6>
                            <div class="row small mt-2">
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="badge bg-primary rounded-circle me-2" style="width: 20px; height: 20px; line-height: 20px;">1</div>
                                        <span>Buat Tim (Gratis)</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="badge bg-primary rounded-circle me-2" style="width: 20px; height: 20px; line-height: 20px;">2</div>
                                        <span>Isi Data Kapten</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="badge bg-primary rounded-circle me-2" style="width: 20px; height: 20px; line-height: 20px;">3</div>
                                        <span>Bayar & Dapat Kode</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 1: School Information -->
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 40px; height: 40px;">
                            <i class="fas fa-school"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Informasi Sekolah</h5>
                            <p class="text-muted mb-0 small">Pilih sekolah yang sudah ada atau tambah sekolah baru</p>
                        </div>
                    </div>

                    <!-- School Option Selection -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card option-card h-100 cursor-pointer" data-option="existing">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="fas fa-list-alt text-primary fs-1"></i>
                                    </div>
                                    <h6 class="fw-bold mb-2">Pilih Sekolah</h6>
                                    <p class="text-muted small mb-0">Pilih dari sekolah yang sudah terdaftar di sistem</p>
                                </div>
                                <div class="card-footer text-center py-3 bg-transparent">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="school_option" 
                                               id="school_existing" value="existing" 
                                               {{ old('school_option', 'existing') == 'existing' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium" for="school_existing">
                                            PILIH INI
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card option-card h-100 cursor-pointer" data-option="new">
                                <div class="card-body text-center p-4">
                                    <div class="mb-3">
                                        <i class="fas fa-plus-circle text-success fs-1"></i>
                                    </div>
                                    <h6 class="fw-bold mb-2">Sekolah Baru</h6>
                                    <p class="text-muted small mb-0">Tambah data sekolah baru ke dalam sistem</p>
                                </div>
                                <div class="card-footer text-center py-3 bg-transparent">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="school_option" 
                                               id="school_new" value="new"
                                               {{ old('school_option') == 'new' ? 'checked' : '' }}>
                                        <label class="form-check-label fw-medium" for="school_new">
                                            PILIH INI
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Existing School Fields -->
                    <div id="existingSchoolSection" class="school-fields" 
                         style="{{ old('school_option', 'existing') == 'new' ? 'display: none;' : '' }}">
                        <div class="row g-3">
                            <div class="col-md-8">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-search me-2"></i>Pilih Sekolah
                                    <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('existing_school_id') is-invalid @enderror"
                                        id="existing_school_id" name="existing_school_id">
                                    <option value="">-- Cari dan Pilih Sekolah --</option>
                                    @foreach($schools as $school)
                                    <option value="{{ $school->id }}"
                                        {{ old('existing_school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->school_name }}
                                        @if($school->city)
                                        - {{ $school->city->city_name }}
                                        @endif
                                    </option>
                                    @endforeach
                                </select>
                                <div class="form-text small">
                                    Ketik nama sekolah untuk mencari lebih cepat
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-image me-2"></i>Logo Sekolah
                                </label>
                                <input type="file" class="form-control" id="school_logo_existing" 
                                       name="school_logo" accept=".jpg,.jpeg,.png,.webp" required>
                            </div>
                        </div>
                    </div>

                    <!-- New School Fields -->
                    <div id="newSchoolFields" class="school-fields" 
                         style="{{ old('school_option') == 'new' ? 'display: block;' : 'display: none;' }}">
                        <div class="row g-3 mb-3">
                            <div class="col-md-8">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-school me-2"></i>Nama Sekolah Baru
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" class="form-control @error('new_school_name') is-invalid @enderror"
                                       id="new_school_name" name="new_school_name"
                                       value="{{ old('new_school_name') }}"
                                       placeholder="Contoh: SMA Negeri 1 Jakarta">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">
                                    <i class="fas fa-image me-2"></i>Logo Sekolah
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control" id="school_logo_new" 
                                       name="school_logo" accept=".jpg,.jpeg,.png,.webp">
                            </div>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Kota/Kabupaten</label>
                                <select class="form-select" id="new_city_id" name="new_city_id">
                                    <option value="">Pilih Kota</option>
                                    @foreach($cities as $city)
                                    <option value="{{ $city->id }}" {{ old('new_city_id') == $city->id ? 'selected' : '' }}>
                                        {{ $city->city_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Jenjang</label>
                                <select class="form-select" id="new_category_name" name="new_category_name">
                                    <option value="">Pilih Jenjang</option>
                                    <option value="SMA" {{ old('new_category_name') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                    <option value="SMK" {{ old('new_category_name') == 'SMK' ? 'selected' : '' }}>SMK</option>
                                    <option value="MA" {{ old('new_category_name') == 'MA' ? 'selected' : '' }}>MA</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-medium">Status</label>
                                <select class="form-select" id="new_type" name="new_type">
                                    <option value="">Pilih Status</option>
                                    <option value="NEGERI" {{ old('new_type') == 'NEGERI' ? 'selected' : '' }}>Negeri</option>
                                    <option value="SWASTA" {{ old('new_type') == 'SWASTA' ? 'selected' : '' }}>Swasta</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Logo Preview -->
                    <div id="logoPreview" class="mt-3 text-center" style="display: none;">
                        <p class="small fw-medium mb-2">Pratinjau Logo:</p>
                        <img id="previewImage" src="#" alt="Preview Logo" 
                             class="img-thumbnail" style="max-width: 120px; border-radius: 10px;">
                    </div>
                </div>

                <!-- Section 2: Team & Competition -->
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 40px; height: 40px;">
                            <i class="fas fa-users"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Informasi Tim & Kompetisi</h5>
                            <p class="text-muted mb-0 small">Pilih kompetisi dan kategori tim</p>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Competition -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select @error('competition') is-invalid @enderror"
                                        id="competition" name="competition">
                                    <option value="">Pilih Kompetisi</option>
                                    @foreach($competitions as $competition)
                                    <option value="{{ $competition }}" {{ old('competition') == $competition ? 'selected' : '' }}>
                                        {{ $competition }}
                                    </option>
                                    @endforeach
                                </select>
                                <label for="competition" class="fw-medium">
                                    <i class="fas fa-trophy me-1"></i>Kompetisi
                                    <span class="text-danger">*</span>
                                </label>
                            </div>
                        </div>

                        <!-- Team Category -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select @error('team_category') is-invalid @enderror"
                                        id="team_category" name="team_category">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($teamCategoryEnums as $category)
                                    <option value="{{ $category }}" {{ old('team_category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                    @endforeach
                                </select>
                                <label for="team_category" class="fw-medium">
                                    <i class="fas fa-tag me-1"></i>Kategori Tim
                                    <span class="text-danger">*</span>
                                </label>
                            </div>
                        </div>

                        <!-- Season -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select @error('season') is-invalid @enderror"
                                        id="season" name="season">
                                    <option value="">Pilih Season</option>
                                    @foreach($seasons as $season)
                                    <option value="{{ $season }}" {{ old('season') == $season ? 'selected' : '' }}>
                                        {{ $season }}
                                    </option>
                                    @endforeach
                                </select>
                                <label for="season" class="fw-medium">
                                    <i class="fas fa-calendar me-1"></i>Season
                                    <span class="text-danger">*</span>
                                </label>
                            </div>
                        </div>

                        <!-- Series -->
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select @error('series') is-invalid @enderror"
                                        id="series" name="series">
                                    <option value="">Pilih Series</option>
                                    @foreach($series as $serie)
                                    <option value="{{ $serie }}" {{ old('series') == $serie ? 'selected' : '' }}>
                                        {{ $serie }}
                                    </option>
                                    @endforeach
                                </select>
                                <label for="series" class="fw-medium">
                                    <i class="fas fa-layer-group me-1"></i>Series
                                    <span class="text-danger">*</span>
                                </label>
                            </div>
                        </div>

                        <!-- Registered By -->
                        <div class="col-12">
                            <div class="form-floating mt-2">
                                <input type="text" class="form-control @error('registered_by') is-invalid @enderror"
                                       id="registered_by" name="registered_by"
                                       value="{{ old('registered_by') }}"
                                       placeholder="Nama Pendaftar">
                                <label for="registered_by" class="fw-medium">
                                    <i class="fas fa-user me-1"></i>Nama Pendaftar
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="form-text small">
                                    Nama ini akan tercatat sebagai pendaftar tim
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 3: Documents -->
                <div class="mb-5">
                    <div class="d-flex align-items-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                             style="width: 40px; height: 40px;">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold">Dokumen Pendukung</h5>
                            <p class="text-muted mb-0 small">Upload dokumen yang diperlukan</p>
                        </div>
                    </div>

                    <div class="row g-4">
                        <!-- Recommendation Letter -->
                        <div class="col-md-6">
                            <div class="card h-100 border">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-info bg-opacity-10 p-3 rounded me-3">
                                            <i class="fas fa-file-pdf text-info fs-4"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-2">Surat Rekomendasi</h6>
                                            <p class="text-muted small mb-3">
                                                Surat rekomendasi dari sekolah dalam format PDF
                                            </p>
                                            <input type="file" class="form-control form-control-sm" 
                                                   id="recommendation_letter" name="recommendation_letter"
                                                   accept=".pdf">
                                            <div class="form-text small mt-2">
                                                <i class="fas fa-info-circle me-1"></i>Maks. 2MB, format PDF
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Koran Subscription -->
                        <div class="col-md-6">
                            <div class="card h-100 border">
                                <div class="card-body">
                                    <div class="d-flex align-items-start">
                                        <div class="bg-warning bg-opacity-10 p-3 rounded me-3">
                                            <i class="fas fa-newspaper text-warning fs-4"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="fw-bold mb-2">Bukti Koran</h6>
                                            <p class="text-muted small mb-3">
                                                Bukti langganan koran atau bukti pembelian
                                            </p>
                                            <input type="file" class="form-control form-control-sm" 
                                                   id="koran" name="koran"
                                                   accept=".jpg,.jpeg,.png,.pdf">
                                            <div class="form-text small mt-2">
                                                <i class="fas fa-info-circle me-1"></i>Maks. 2MB, format JPG/PNG/PDF
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Note -->
                    <div class="alert alert-warning mt-4 border-start border-warning border-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-money-bill-wave fs-4 me-3"></i>
                            <div>
                                <h6 class="alert-heading mb-1">💳 Pembayaran Nanti</h6>
                                <p class="mb-0">
                                    Kamu bisa upload bukti pembayaran setelah mengisi data kapten di step selanjutnya.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden Field -->
                <input type="hidden" id="school_name" name="school_name" value="{{ old('school_name') }}">

                <!-- Submit Buttons -->
                <div class="border-top pt-4 mt-5">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted small mb-0">
                                <i class="fas fa-shield-alt me-1"></i>
                                Data Anda aman dan terlindungi
                            </p>
                        </div>
                        <div class="d-flex gap-3">
                            <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-times me-2"></i>Batal
                            </a>
                            <button type="submit" id="submitBtn" class="btn btn-primary px-5 py-2">
                                <i class="fas fa-check me-2"></i>Buat Tim
                                <span class="ms-2 small">➜ Lanjut ke Kapten</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Support Info -->
    <div class="text-center mt-4">
        <p class="text-muted small">
            <i class="fas fa-life-ring me-1"></i> Butuh bantuan? 
            <a href="#" class="text-decoration-none fw-medium">Chat Admin</a> atau 
            <a href="mailto:support@hsbl.com" class="text-decoration-none fw-medium">Email Support</a>
        </p>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center p-5">
                <div class="spinner-border text-primary mb-4" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="mb-2">Membuat Tim Anda...</h5>
                <p class="text-muted mb-0">Harap tunggu sebentar, proses sedang berjalan</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // School option cards click handler
        document.querySelectorAll('.option-card').forEach(card => {
            card.addEventListener('click', function() {
                const option = this.dataset.option;
                document.getElementById(`school_${option}`).checked = true;
                toggleSchoolFields();
                updateCardSelection();
            });
        });

        // Toggle school fields
        function toggleSchoolFields() {
            const schoolOption = document.querySelector('input[name="school_option"]:checked').value;
            const existingSection = document.getElementById('existingSchoolSection');
            const newSection = document.getElementById('newSchoolFields');
            const existingLogoInput = document.getElementById('school_logo_existing');
            const newLogoInput = document.getElementById('school_logo_new');

            if (schoolOption === 'new') {
                existingSection.style.display = 'none';
                newSection.style.display = 'block';
                if (existingLogoInput) existingLogoInput.disabled = true;
                if (newLogoInput) newLogoInput.disabled = false;
            } else {
                existingSection.style.display = 'block';
                newSection.style.display = 'none';
                if (existingLogoInput) existingLogoInput.disabled = false;
                if (newLogoInput) newLogoInput.disabled = true;
            }
        }

        // Update card selection visual
        function updateCardSelection() {
            const schoolOption = document.querySelector('input[name="school_option"]:checked').value;
            document.querySelectorAll('.option-card').forEach(card => {
                if (card.dataset.option === schoolOption) {
                    card.classList.add('selected');
                    card.style.border = '2px solid #4f46e5';
                    card.style.boxShadow = '0 0 0 3px rgba(79, 70, 229, 0.1)';
                } else {
                    card.classList.remove('selected');
                    card.style.border = '1px solid #dee2e6';
                    card.style.boxShadow = 'none';
                }
            });
        }

        // Logo preview for both inputs
        function handleLogoPreview(inputId) {
            const input = document.getElementById(inputId);
            if (!input) return;
            
            input.addEventListener('change', function(e) {
                const preview = document.getElementById('logoPreview');
                const previewImage = document.getElementById('previewImage');
                const file = e.target.files[0];
                
                if (file) {
                    // Validation
                    if (file.size > 2 * 1024 * 1024) {
                        alert('Ukuran file terlalu besar! Maksimal 2MB.');
                        this.value = '';
                        preview.style.display = 'none';
                        return;
                    }

                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        alert('Format file tidak valid! Gunakan JPG, PNG, atau WebP.');
                        this.value = '';
                        preview.style.display = 'none';
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        preview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.style.display = 'none';
                }
            });
        }

        // Form validation
        document.getElementById('createTeamForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = document.getElementById('submitBtn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
            submitBtn.disabled = true;

            // Show loading modal
            const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
            loadingModal.show();

            // Validate
            let isValid = true;
            const schoolOption = document.querySelector('input[name="school_option"]:checked').value;

            if (schoolOption === 'existing') {
                if (!document.getElementById('existing_school_id').value) {
                    isValid = false;
                    alert('Harap pilih sekolah dari daftar');
                }
            } else {
                const newSchoolName = document.getElementById('new_school_name').value.trim();
                const newLogo = document.getElementById('school_logo_new').files[0];
                
                if (!newSchoolName) {
                    isValid = false;
                    alert('Harap masukkan nama sekolah baru');
                } else if (!newLogo) {
                    isValid = false;
                    alert('Harap upload logo sekolah untuk sekolah baru');
                }
            }

            // Check required fields
            const requiredFields = [
                {id: 'competition', name: 'Kompetisi'},
                {id: 'team_category', name: 'Kategori Tim'},
                {id: 'season', name: 'Season'},
                {id: 'series', name: 'Series'},
                {id: 'registered_by', name: 'Nama Pendaftar'}
            ];
            
            for (const field of requiredFields) {
                const fieldElement = document.getElementById(field.id);
                if (fieldElement && !fieldElement.value.trim()) {
                    isValid = false;
                    alert(`Harap isi "${field.name}"`);
                    break;
                }
            }

            // Check file uploads
            const recommendationFile = document.getElementById('recommendation_letter')?.files[0];
            const koranFile = document.getElementById('koran')?.files[0];

            if (!recommendationFile) {
                isValid = false;
                alert('Harap upload Surat Rekomendasi Sekolah');
            } else if (!koranFile) {
                isValid = false;
                alert('Harap upload Bukti Langganan Koran');
            }

            if (!isValid) {
                loadingModal.hide();
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                return;
            }

            // Submit form
            setTimeout(() => {
                this.submit();
            }, 500);
        });

        // Initialize
        toggleSchoolFields();
        updateCardSelection();
        handleLogoPreview('school_logo_existing');
        handleLogoPreview('school_logo_new');

        // Update school name hidden field
        document.getElementById('existing_school_id')?.addEventListener('change', function() {
            if (this.options[this.selectedIndex]) {
                document.getElementById('school_name').value = this.options[this.selectedIndex].text.split(' - ')[0];
            }
        });

        document.getElementById('new_school_name')?.addEventListener('input', function() {
            document.getElementById('school_name').value = this.value;
        });
    });
</script>

<style>
    /* Custom Styles */
    .card {
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .option-card {
        border: 1px solid #dee2e6;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .option-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        border-color: #4f46e5;
    }

    .option-card.selected {
        border: 2px solid #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .form-floating > .form-control {
        height: calc(3.5rem + 2px);
        padding: 1rem 0.75rem;
    }

    .form-floating > label {
        padding: 1rem 0.75rem;
    }

    .bg-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        font-weight: 600;
        padding: 0.75rem 2rem;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }

    .btn-outline-secondary {
        border-width: 1.5px;
        font-weight: 500;
    }

    .alert {
        border-radius: 10px;
    }

    .border-start {
        border-left-width: 4px !important;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }
        
        .btn-primary, .btn-outline-secondary {
            width: 100%;
            margin-bottom: 0.5rem;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }
        
        .option-card .card-body {
            padding: 1.5rem !important;
        }
    }

    /* Smooth animations */
    .school-fields {
        animation: fadeIn 0.5s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Custom scrollbar for select */
    select::-webkit-scrollbar {
        width: 8px;
    }

    select::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    select::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    select::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Focus styles */
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
</style>
@endpush
@endsection