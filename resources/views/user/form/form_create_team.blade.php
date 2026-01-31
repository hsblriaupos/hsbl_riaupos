@extends('user.form.layout')

@section('title', 'Buat Tim Baru - HSBL')

@section('content')
<div class="container py-4">
    <!-- Progress Steps -->
    <div class="mb-5">
        <div class="d-flex justify-content-center">
            <div class="text-center mx-3">
                <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                    1
                </div>
                <div class="small fw-medium">Pendaftaran Tim</div>
            </div>
            <div class="align-self-center mx-2">
                <div class="line bg-secondary" style="width: 80px; height: 2px;"></div>
            </div>
            <div class="text-center mx-3">
                <div class="rounded-circle bg-secondary text-white d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                    2
                </div>
                <div class="small text-muted">Data Kapten</div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <!-- Display Flash Messages -->
            @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <!-- Display Errors -->
            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <div class="card border">
                <!-- Card Header -->
                <div class="card-header bg-white border-bottom py-3">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('form.team.choice') }}" class="text-decoration-none text-dark me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h5 class="mb-0 fw-bold">Buat Tim Baru</h5>
                            <p class="mb-0 text-muted small">Form Pendaftaran Sekolah dan Tim</p>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body p-4">
                    <form id="createTeamForm" action="{{ route('form.team.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Important Notice -->
                        <div class="alert alert-info mb-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div>
                                    <p class="mb-1 fw-medium">Alur Pendaftaran:</p>
                                    <ul class="mb-0 ps-3 small">
                                        <li><strong>Step 1:</strong> Buat tim dulu (tanpa bayar)</li>
                                        <li><strong>Step 2:</strong> Lengkapi data Kapten & upload bukti bayar</li>
                                        <li><strong>Setelah bayar</strong>, dapat referral code untuk anggota</li>
                                        <li class="text-success fw-medium">Anggota cukup join pakai referral code!</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="mb-3 border-bottom pb-2 fw-bold">🏫 Informasi Sekolah</h6>

                            <!-- School Selection Option -->
                            <div class="mb-4">
                                <label class="form-label fw-medium">Pilih sekolah dari database atau tambah baru:</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="school_option" id="school_existing" value="existing" 
                                            {{ old('school_option', 'existing') == 'existing' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="school_existing">
                                            Pilih sekolah yang sudah ada
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="school_option" id="school_new" value="new"
                                            {{ old('school_option') == 'new' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="school_new">
                                            Tambah sekolah baru
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- EXISTING SCHOOL: Dropdown -->
                            <div class="mb-3" id="existingSchoolSection" 
                                style="{{ old('school_option', 'existing') == 'new' ? 'display: none;' : '' }}">
                                <label for="existing_school_id" class="form-label fw-medium">
                                    Pilih Sekolah <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('existing_school_id') is-invalid @enderror"
                                    id="existing_school_id" name="existing_school_id">
                                    <option value="">-- Pilih Sekolah --</option>
                                    @foreach($schools as $school)
                                    <option value="{{ $school->id }}"
                                        data-school-name="{{ $school->school_name }}"
                                        data-school-logo="{{ $school->school_logo }}"
                                        {{ old('existing_school_id') == $school->id ? 'selected' : '' }}>
                                        {{ $school->school_name }}
                                        ({{ $school->city->city_name ?? 'Kota tidak diketahui' }} - {{ $school->type }})
                                    </option>
                                    @endforeach
                                </select>
                                <div class="form-text">Pilih sekolah dari daftar yang sudah ada</div>
                                @error('existing_school_id')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- NEW SCHOOL: Form -->
                            <div id="newSchoolFields" style="{{ old('school_option') == 'new' ? 'display: block;' : 'display: none;' }}">
                                <div class="mb-3">
                                    <label for="new_school_name" class="form-label fw-medium">
                                        Nama Sekolah Baru <span class="text-danger">*</span>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('new_school_name') is-invalid @enderror"
                                        id="new_school_name"
                                        name="new_school_name"
                                        value="{{ old('new_school_name') }}"
                                        placeholder="Masukkan nama sekolah baru">
                                    <div class="form-text" id="schoolCheckResult"></div>
                                    @error('new_school_name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="new_city_id" class="form-label fw-medium">
                                            Kota/Kabupaten <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('new_city_id') is-invalid @enderror"
                                            id="new_city_id" name="new_city_id">
                                            <option value="">Pilih Kota/Kabupaten</option>
                                            @foreach($cities as $city)
                                            <option value="{{ $city->id }}" {{ old('new_city_id') == $city->id ? 'selected' : '' }}>
                                                {{ $city->city_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('new_city_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="new_category_name" class="form-label fw-medium">
                                            Jenjang Sekolah <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('new_category_name') is-invalid @enderror"
                                            id="new_category_name" name="new_category_name">
                                            <option value="">Pilih Jenjang</option>
                                            <option value="SMA" {{ old('new_category_name') == 'SMA' ? 'selected' : '' }}>SMA</option>
                                            <option value="SMK" {{ old('new_category_name') == 'SMK' ? 'selected' : '' }}>SMK</option>
                                            <option value="MA" {{ old('new_category_name') == 'MA' ? 'selected' : '' }}>MA</option>
                                        </select>
                                        @error('new_category_name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="new_type" class="form-label fw-medium">
                                            Status Sekolah <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select @error('new_type') is-invalid @enderror"
                                            id="new_type" name="new_type">
                                            <option value="">Pilih Status</option>
                                            <option value="NEGERI" {{ old('new_type') == 'NEGERI' ? 'selected' : '' }}>Negeri</option>
                                            <option value="SWASTA" {{ old('new_type') == 'SWASTA' ? 'selected' : '' }}>Swasta</option>
                                        </select>
                                        @error('new_type')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="alert alert-light border mb-3">
                                    <p class="mb-0 small">
                                        <i class="fas fa-lightbulb text-warning me-2"></i>
                                        Data sekolah akan disimpan ke database sistem.
                                    </p>
                                </div>
                            </div>

                            <!-- School Logo Upload Section -->
                            <div id="schoolLogoSection" style="{{ old('school_option') == 'existing' ? 'display: block;' : 'display: none;' }}">
                                <div class="mb-3">
                                    <label for="school_logo" class="form-label fw-medium">
                                        Logo Sekolah 
                                        <span id="logoRequired" class="text-danger" style="display: none;">*</span>
                                        <span id="logoOptional" class="text-muted">(Opsional)</span>
                                    </label>
                                    <input type="file"
                                        class="form-control @error('school_logo') is-invalid @enderror"
                                        id="school_logo"
                                        name="school_logo"
                                        accept=".jpg,.jpeg,.png,.webp">
                                    <div class="form-text">
                                        Format: JPG, PNG, atau WebP. Maksimal: 2MB. Rasio 1:1 (persegi) disarankan.
                                        <br>
                                        <span id="logoNoteNew" style="display: none;">Logo wajib diupload untuk sekolah baru.</span>
                                        <span id="logoNoteExisting">Logo opsional. Jika tidak diupload, akan menggunakan logo sekolah yang sudah ada (jika ada).</span>
                                    </div>
                                    @error('school_logo')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    
                                    <!-- Logo Preview -->
                                    <div id="logoPreview" class="mt-3 text-center" style="display: none;">
                                        <p class="small mb-2">Pratinjau Logo:</p>
                                        <img id="previewImage" src="#" alt="Preview Logo" 
                                             style="max-width: 150px; max-height: 150px; border: 1px solid #ddd; border-radius: 8px;">
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden input untuk nama sekolah -->
                            <input type="hidden" id="school_name" name="school_name" value="{{ old('school_name') }}">

                            <!-- Competition Category -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="competition" class="form-label fw-medium">
                                        Kompetisi <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('competition') is-invalid @enderror"
                                        id="competition" name="competition">
                                        <option value="">Pilih Kompetisi</option>
                                        @foreach($competitions as $competition)
                                        <option value="{{ $competition }}" {{ old('competition') == $competition ? 'selected' : '' }}>
                                            {{ $competition }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('competition')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="team_category" class="form-label fw-medium">
                                        Kategori Tim <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('team_category') is-invalid @enderror"
                                        id="team_category" name="team_category">
                                        <option value="">Pilih Kategori</option>
                                        @foreach($teamCategoryEnums as $category)
                                        <option value="{{ $category }}" {{ old('team_category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('team_category')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Season & Series -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="season" class="form-label fw-medium">
                                        Season <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('season') is-invalid @enderror"
                                        id="season" name="season">
                                        <option value="">Pilih Season</option>
                                        @foreach($seasons as $season)
                                        <option value="{{ $season }}" {{ old('season') == $season ? 'selected' : '' }}>
                                            {{ $season }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('season')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="series" class="form-label fw-medium">
                                        Series <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('series') is-invalid @enderror"
                                        id="series" name="series">
                                        <option value="">Pilih Series</option>
                                        @foreach($series as $serie)
                                        <option value="{{ $serie }}" {{ old('series') == $serie ? 'selected' : '' }}>
                                            {{ $serie }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('series')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Registered By -->
                            <div class="mb-3">
                                <label for="registered_by" class="form-label fw-medium">
                                    Nama Pendaftar <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    class="form-control @error('registered_by') is-invalid @enderror"
                                    id="registered_by"
                                    name="registered_by"
                                    value="{{ old('registered_by') }}"
                                    placeholder="Nama lengkap kamu">
                                <div class="form-text">Nama ini akan tercatat sebagai pendaftar tim</div>
                                @error('registered_by')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Document Upload Section -->
                        <div class="mb-4">
                            <h6 class="mb-3 border-bottom pb-2 fw-bold">📎 Upload Dokumen Tim</h6>

                            <!-- Recommendation Letter -->
                            <div class="mb-3">
                                <label for="recommendation_letter" class="form-label fw-medium">
                                    Surat Rekomendasi Sekolah <span class="text-danger">*</span>
                                </label>
                                <input type="file"
                                    class="form-control @error('recommendation_letter') is-invalid @enderror"
                                    id="recommendation_letter"
                                    name="recommendation_letter"
                                    accept=".pdf">
                                <div class="form-text">Format: PDF, Maksimal: 2MB</div>
                                @error('recommendation_letter')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Koran -->
                            <div class="mb-3">
                                <label for="koran" class="form-label fw-medium">
                                    Langganan Koran <span class="text-danger">*</span>
                                </label>
                                <input type="file"
                                    class="form-control @error('koran') is-invalid @enderror"
                                    id="koran"
                                    name="koran"
                                    accept=".jpg,.jpeg,.png,.pdf">
                                <div class="form-text">Format: JPG, PNG, atau PDF, Maksimal: 2MB</div>
                                @error('koran')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- NOTE: BUKTI PEMBAYARAN DILAKUKAN DI FORM KAPTEN -->
                            <div class="alert alert-warning">
                                <div class="d-flex">
                                    <i class="fas fa-money-bill-wave me-3"></i>
                                    <div>
                                        <p class="mb-0 fw-medium">Pembayaran dilakukan setelah ini!</p>
                                        <small>Setelah membuat tim, kamu akan langsung diarahkan ke form Kapten untuk upload bukti pembayaran.</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Terms & Conditions -->
                        <div class="alert alert-info mb-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-info-circle"></i>
                                </div>
                                <div>
                                    <p class="mb-2 fw-medium">Alur Pendaftaran:</p>
                                    <ol class="mb-0 ps-3 small">
                                        <li>Submit form ini → <strong>Buat tim dulu</strong></li>
                                        <li>Pergi ke form Kapten → <strong>Upload bukti bayar</strong></li>
                                        <li>Setelah bayar → <strong>Dapat referral code</strong></li>
                                        <li>Bagikan referral ke anggota lain</li>
                                        <li>Selesai! Anggota lain join tanpa bayar lagi</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between mt-5">
                            <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary px-4">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" id="submitBtn" class="btn btn-primary px-4">
                                <i class="fas fa-check me-2"></i>Buat Tim & Lanjut ke Data Kapten
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="mt-4 text-center">
                <p class="text-muted small mb-0">
                    Butuh bantuan? Hubungi kami di
                    <a href="mailto:support@hsbl.com" class="text-decoration-none">support@hsbl.com</a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Existing Team Modal -->
<div class="modal fade" id="existingTeamModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">⚠️ Tim Sudah Ada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="existingTeamMessage" class="mb-3"></p>
                <div id="referralInfo" style="display: none;">
                    <div class="alert alert-success">
                        <p class="mb-1"><strong>Referral Code:</strong> <span id="referralCodeDisplay"></span></p>
                        <p class="mb-0 small">Bagikan referral code ini untuk bergabung dengan tim</p>
                    </div>
                </div>
                <div class="alert alert-light border">
                    <p class="mb-1 small">Ingat: 1 sekolah hanya punya 1 referral code untuk semua kategori.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tetap Buat Baru</button>
                <a href="{{ route('form.team.join') }}" class="btn btn-primary">
                    <i class="fas fa-users me-2"></i>Gabung ke Tim
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h5 class="mb-2">Memproses Pendaftaran...</h5>
                <p class="text-muted mb-0">Harap tunggu, tim sedang dibuat</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Form loaded');
        
        // Set initial state
        toggleSchoolFields();

        // Event listener untuk radio button sekolah
        document.querySelectorAll('input[name="school_option"]').forEach(radio => {
            radio.addEventListener('change', function() {
                console.log(`School option clicked: ${this.value}`);
                toggleSchoolFields();
            });
        });

        // Update school name dan cek logo existing
        document.getElementById('existing_school_id')?.addEventListener('change', function() {
            console.log(`Existing school selected: ${this.value}`);
            updateSchoolNameFromDropdown();
            checkExistingTeam();
            
            // Tampilkan info logo sekolah yang sudah ada
            const selectedOption = this.options[this.selectedIndex];
            const existingLogo = selectedOption.getAttribute('data-school-logo');
            if (existingLogo) {
                console.log('Sekolah ini sudah punya logo:', existingLogo);
                // Bisa tambahkan info ke user jika mau
            }
        });

        // Check sekolah baru
        document.getElementById('new_school_name')?.addEventListener('blur', function() {
            const schoolName = this.value.trim();
            if (!schoolName) return;

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
                const resultElement = document.getElementById('schoolCheckResult');
                if (data.exists) {
                    resultElement.innerHTML = '<span class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i>Sekolah sudah ada di database!</span>';
                    if (data.school && data.school.city_id) {
                        document.getElementById('new_city_id').value = data.school.city_id;
                    }
                } else {
                    resultElement.innerHTML = '<span class="text-success"><i class="fas fa-check-circle me-1"></i>Sekolah belum terdaftar</span>';
                }
            });
        });

        // Logo preview
        document.getElementById('school_logo')?.addEventListener('change', function(e) {
            const preview = document.getElementById('logoPreview');
            const previewImage = document.getElementById('previewImage');
            const file = e.target.files[0];
            
            if (file) {
                // Validasi ukuran file
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB.');
                    this.value = '';
                    preview.style.display = 'none';
                    return;
                }

                // Validasi tipe file
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

        // Form validation
        document.getElementById('createTeamForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            
            console.log('Form submission started');
            
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
            submitBtn.disabled = true;

            // Show loading modal
            const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
            loadingModal.show();

            // Validate form
            const schoolOption = document.querySelector('input[name="school_option"]:checked').value;
            let isValid = true;
            let errorMessage = '';

            // Validate school
            if (schoolOption === 'existing') {
                const existingSchoolId = document.getElementById('existing_school_id')?.value;
                if (!existingSchoolId) {
                    isValid = false;
                    errorMessage = 'Harap pilih sekolah dari daftar';
                }
                // Logo opsional untuk sekolah yang sudah ada
                const schoolLogo = document.getElementById('school_logo')?.files[0];
                if (schoolLogo && schoolLogo.size > 2 * 1024 * 1024) {
                    isValid = false;
                    errorMessage = 'File logo terlalu besar! Maksimal 2MB';
                }
            } else {
                const newSchoolName = document.getElementById('new_school_name')?.value.trim();
                const cityId = document.getElementById('new_city_id')?.value;
                const category = document.getElementById('new_category_name')?.value;
                const type = document.getElementById('new_type')?.value;
                const schoolLogo = document.getElementById('school_logo')?.files[0];

                if (!newSchoolName) {
                    isValid = false;
                    errorMessage = 'Harap masukkan nama sekolah baru';
                } else if (!cityId) {
                    isValid = false;
                    errorMessage = 'Harap pilih kota/kabupaten';
                } else if (!category) {
                    isValid = false;
                    errorMessage = 'Harap pilih jenjang sekolah';
                } else if (!type) {
                    isValid = false;
                    errorMessage = 'Harap pilih status sekolah';
                } else if (!schoolLogo) {
                    isValid = false;
                    errorMessage = 'Harap upload logo sekolah';
                } else if (schoolLogo.size > 2 * 1024 * 1024) {
                    isValid = false;
                    errorMessage = 'File logo terlalu besar! Maksimal 2MB';
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
                    errorMessage = `Harap isi field "${field.name}"`;
                    break;
                }
            }

            // File validation
            const recommendationFile = document.getElementById('recommendation_letter')?.files[0];
            const koranFile = document.getElementById('koran')?.files[0];

            if (!recommendationFile) {
                isValid = false;
                errorMessage = 'Harap upload Surat Rekomendasi Sekolah';
            } else if (recommendationFile.size > 2 * 1024 * 1024) {
                isValid = false;
                errorMessage = 'File Surat Rekomendasi terlalu besar! Maksimal 2MB';
            } else if (!koranFile) {
                isValid = false;
                errorMessage = 'Harap upload Bukti Langganan Koran';
            } else if (koranFile.size > 2 * 1024 * 1024) {
                isValid = false;
                errorMessage = 'File Koran terlalu besar! Maksimal 2MB';
            }

            if (!isValid) {
                loadingModal.hide();
                submitBtn.innerHTML = '<i class="fas fa-check me-2"></i>Buat Tim & Lanjut ke Data Kapten';
                submitBtn.disabled = false;
                alert(errorMessage);
                return;
            }

            console.log('Form validation passed, submitting...');
            this.submit();
        });
    });

    function toggleSchoolFields() {
        const schoolOption = document.querySelector('input[name="school_option"]:checked').value;
        const existingSchoolSection = document.getElementById('existingSchoolSection');
        const newSchoolFields = document.getElementById('newSchoolFields');
        const schoolLogoSection = document.getElementById('schoolLogoSection');
        const logoRequired = document.getElementById('logoRequired');
        const logoOptional = document.getElementById('logoOptional');
        const logoNoteNew = document.getElementById('logoNoteNew');
        const logoNoteExisting = document.getElementById('logoNoteExisting');

        if (schoolOption === 'new') {
            // Sekolah baru: logo wajib
            if (existingSchoolSection) existingSchoolSection.style.display = 'none';
            if (newSchoolFields) newSchoolFields.style.display = 'block';
            if (schoolLogoSection) schoolLogoSection.style.display = 'block';
            if (logoRequired) logoRequired.style.display = 'inline';
            if (logoOptional) logoOptional.style.display = 'none';
            if (logoNoteNew) logoNoteNew.style.display = 'inline';
            if (logoNoteExisting) logoNoteExisting.style.display = 'none';
            
            document.getElementById('existing_school_id').value = '';
            document.getElementById('school_name').value = document.getElementById('new_school_name').value || '';
        } else {
            // Sekolah yang sudah ada: logo opsional
            if (existingSchoolSection) existingSchoolSection.style.display = 'block';
            if (newSchoolFields) newSchoolFields.style.display = 'none';
            if (schoolLogoSection) schoolLogoSection.style.display = 'block';
            if (logoRequired) logoRequired.style.display = 'none';
            if (logoOptional) logoOptional.style.display = 'inline';
            if (logoNoteNew) logoNoteNew.style.display = 'none';
            if (logoNoteExisting) logoNoteExisting.style.display = 'inline';
            
            document.getElementById('new_school_name').value = '';
            document.getElementById('new_city_id').value = '';
            document.getElementById('new_category_name').value = '';
            document.getElementById('new_type').value = '';
            document.getElementById('school_logo').value = '';
            document.getElementById('logoPreview').style.display = 'none';
            updateSchoolNameFromDropdown();
        }
    }

    function updateSchoolNameFromDropdown() {
        const dropdown = document.getElementById('existing_school_id');
        if (!dropdown || !dropdown.value) {
            document.getElementById('school_name').value = '';
            return;
        }
        const selectedOption = dropdown.options[dropdown.selectedIndex];
        const schoolName = selectedOption.getAttribute('data-school-name') || '';
        document.getElementById('school_name').value = schoolName;
    }

    function checkExistingTeam() {
        let schoolName = '';
        const schoolOption = document.querySelector('input[name="school_option"]:checked').value;

        if (schoolOption === 'existing') {
            const dropdown = document.getElementById('existing_school_id');
            if (dropdown && dropdown.value) {
                const selectedOption = dropdown.options[dropdown.selectedIndex];
                schoolName = selectedOption.getAttribute('data-school-name') || '';
            }
        } else {
            schoolName = document.getElementById('new_school_name')?.value || '';
        }

        const teamCategory = document.getElementById('team_category')?.value;
        const season = document.getElementById('season')?.value;

        if (!schoolName || !teamCategory || !season) return;

        const formData = new FormData();
        formData.append('school_name', schoolName);
        formData.append('team_category', teamCategory);
        formData.append('season', season);

        fetch('{{ route("form.team.checkExisting") }}', {
            method: 'POST',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.exists) {
                let modalMessage = data.message;
                if (data.has_paid_leader && data.team && data.team.referral_code) {
                    document.getElementById('referralInfo').style.display = 'block';
                    document.getElementById('referralCodeDisplay').textContent = data.team.referral_code;
                } else {
                    document.getElementById('referralInfo').style.display = 'none';
                }
                document.getElementById('existingTeamMessage').textContent = modalMessage;
                new bootstrap.Modal(document.getElementById('existingTeamModal')).show();
            }
        });
    }

    document.getElementById('team_category')?.addEventListener('change', checkExistingTeam);
    document.getElementById('season')?.addEventListener('change', checkExistingTeam);
</script>

<style>
    .line { background-color: #dee2e6; }
    .card { border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
    .card-header { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); }
    .form-label { margin-bottom: 0.5rem; font-weight: 500; }
    #newSchoolFields { border: 1px solid #dee2e6; border-radius: 6px; padding: 1.5rem; background-color: #f8f9fa; }
    .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(102, 126, 234, 0.3); }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    #newSchoolFields, #existingSchoolSection { animation: fadeIn 0.3s ease-out; }
</style>
@endpush
@endsection