@extends('user.form.layout')
@section('title', 'Form Pendaftaran Official - HSBL')

@section('content')
<div class="container py-3 px-lg-4 px-md-3 px-sm-2">
    <!-- Main Form Card -->
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 800px; border-radius: 8px; width: 100%;">
        <!-- Card Header -->
        <div class="card-header bg-gradient-warning text-white py-2 px-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div class="flex-grow-1">
                    <h5 class="mb-0 fw-bold small">
                        <i class="fas fa-user-tie me-1"></i>Form Pendaftaran Official
                    </h5>
                    <p class="mb-0 opacity-75" style="font-size: 0.7rem;">
                        {{ $team->school_name }} • {{ $team->team_name ?: 'Tim' }}
                    </p>
                </div>
                <a href="{{ route('form.team.join.role') }}" class="btn btn-light btn-sm px-2 rounded-pill">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>

        <!-- Form Content -->
        <div class="card-body p-3 px-md-3 px-sm-2">
            <form id="officialForm" action="{{ route('form.official.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team_id }}">

                <!-- ========== SECTION PILIHAN KATEGORI ========== -->
                <div class="mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-wrapper bg-warning bg-opacity-10 p-1 rounded me-2" style="width: 32px; height: 32px;">
                            <i class="fas fa-tag text-warning" style="font-size: 0.9rem;"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark">Pilih Kategori Official</h6>
                    </div>
                    
                    <div class="alert alert-info border-info bg-info-subtle mb-3 py-2 px-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Pilih kategori tim yang akan Anda dampingi sebagai official.
                    </div>
                    
                    <div class="row g-3 mb-2">
                        <!-- Basket Putra -->
                        <div class="col-md-4">
                            <div class="category-card">
                                <input type="radio" 
                                    class="btn-check" 
                                    name="category" 
                                    id="category_basket_putra" 
                                    value="basket_putra"
                                    autocomplete="off"
                                    {{ old('category') == 'basket_putra' ? 'checked' : '' }}
                                    required>
                                <label class="btn btn-outline-primary w-100 py-3" for="category_basket_putra">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-basketball-ball fa-2x mb-2"></i>
                                        <span class="fw-bold">BASKET PUTRA</span>
                                        <small class="text-muted">Official tim putra</small>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Basket Putri -->
                        <div class="col-md-4">
                            <div class="category-card">
                                <input type="radio" 
                                    class="btn-check" 
                                    name="category" 
                                    id="category_basket_putri" 
                                    value="basket_putri"
                                    autocomplete="off"
                                    {{ old('category') == 'basket_putri' ? 'checked' : '' }}
                                    required>
                                <label class="btn btn-outline-danger w-100 py-3" for="category_basket_putri">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-basketball-ball fa-2x mb-2"></i>
                                        <span class="fw-bold">BASKET PUTRI</span>
                                        <small class="text-muted">Official tim putri</small>
                                    </div>
                                </label>
                            </div>
                        </div>
                        
                        <!-- Dancer -->
                        <div class="col-md-4">
                            <div class="category-card">
                                <input type="radio" 
                                    class="btn-check" 
                                    name="category" 
                                    id="category_dancer" 
                                    value="dancer"
                                    autocomplete="off"
                                    {{ old('category') == 'dancer' ? 'checked' : '' }}
                                    required>
                                <label class="btn btn-outline-success w-100 py-3" for="category_dancer">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-music fa-2x mb-2"></i>
                                        <span class="fw-bold">DANCER</span>
                                        <small class="text-muted">Official tim dancer</small>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    @error('category')
                        <div class="text-danger small mt-2">
                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- ========== SECTION DATA PRIBADI ========== -->
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon-wrapper bg-primary bg-opacity-10 p-1 rounded me-2" style="width: 28px; height: 28px;">
                            <i class="fas fa-id-card text-primary" style="font-size: 0.8rem;"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark small">Data Pribadi</h6>
                    </div>
                    
                    <!-- Row 1 -->
                    <div class="row gx-1 gy-1 mb-2">
                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                NIK <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                class="form-control form-control-sm @error('nik') is-invalid @enderror"
                                id="nik" 
                                name="nik" 
                                value="{{ old('nik') }}" 
                                required
                                placeholder="16 digit" 
                                maxlength="16"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <div class="invalid-feedback" id="nik-feedback"></div>
                            @error('nik')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                class="form-control form-control-sm @error('name') is-invalid @enderror"
                                id="name" 
                                name="name" 
                                value="{{ old('name') }}" 
                                required
                                placeholder="Sesuai KTP">
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Tanggal Lahir <span class="text-danger">*</span>
                            </label>
                            <input type="date" 
                                class="form-control form-control-sm @error('birthdate') is-invalid @enderror"
                                id="birthdate" 
                                name="birthdate" 
                                value="{{ old('birthdate') }}" 
                                required
                                max="{{ date('Y-m-d', strtotime('-18 years')) }}">
                            @error('birthdate')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Jenis Kelamin <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm @error('gender') is-invalid @enderror"
                                id="gender" 
                                name="gender" 
                                required>
                                <option value="">Pilih</option>
                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('gender')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 2 -->
                    <div class="row gx-1 gy-1 mb-2">
                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                WhatsApp <span class="text-danger">*</span>
                            </label>
                            <input type="tel" 
                                class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                id="phone" 
                                name="phone" 
                                value="{{ old('phone') }}" 
                                required
                                placeholder="081234567890"
                                oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" 
                                class="form-control form-control-sm @error('email') is-invalid @enderror"
                                id="email" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required
                                placeholder="email@example.com">
                            <div class="invalid-feedback" id="email-feedback"></div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Peran dalam Tim <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm @error('team_role') is-invalid @enderror"
                                id="team_role" 
                                name="team_role" 
                                required>
                                <option value="">Pilih Peran</option>
                                <option value="Coach" {{ old('team_role') == 'Coach' ? 'selected' : '' }}>Pelatih</option>
                                <option value="Assistant Coach" {{ old('team_role') == 'Assistant Coach' ? 'selected' : '' }}>Asisten Pelatih</option>
                                <option value="Manager" {{ old('team_role') == 'Manager' ? 'selected' : '' }}>Manajer</option>
                                <option value="Medical Support" {{ old('team_role') == 'Medical Support' ? 'selected' : '' }}>Dukungan Medis</option>
                                <option value="Pendamping" {{ old('team_role') == 'Pendamping' ? 'selected' : '' }}>Pendamping</option>
                            </select>
                            @error('team_role')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Sekolah <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                class="form-control form-control-sm bg-light"
                                value="{{ $team->school_name }}" 
                                readonly>
                            <input type="hidden" name="school" value="{{ $team->school_name }}">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Data Fisik -->
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon-wrapper bg-success bg-opacity-10 p-1 rounded me-2" style="width: 28px; height: 28px;">
                            <i class="fas fa-running text-success" style="font-size: 0.8rem;"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark small">Data Fisik (Opsional)</h6>
                    </div>
                    
                    <div class="row gx-1 gy-1 mb-2">
                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Tinggi Badan (cm)
                            </label>
                            <input type="number" 
                                class="form-control form-control-sm @error('height') is-invalid @enderror"
                                id="height" 
                                name="height" 
                                value="{{ old('height') }}"
                                min="100" 
                                max="250" 
                                step="1" 
                                placeholder="170">
                            @error('height')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Berat Badan (kg)
                            </label>
                            <input type="number" 
                                class="form-control form-control-sm @error('weight') is-invalid @enderror"
                                id="weight" 
                                name="weight" 
                                value="{{ old('weight') }}"
                                min="30" 
                                max="200" 
                                step="0.5" 
                                placeholder="65.5">
                            @error('weight')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Ukuran Kaos
                            </label>
                            <select class="form-select form-select-sm @error('tshirt_size') is-invalid @enderror"
                                id="tshirt_size" 
                                name="tshirt_size">
                                <option value="">Pilih</option>
                                <option value="S" {{ old('tshirt_size') == 'S' ? 'selected' : '' }}>S</option>
                                <option value="M" {{ old('tshirt_size') == 'M' ? 'selected' : '' }}>M</option>
                                <option value="L" {{ old('tshirt_size') == 'L' ? 'selected' : '' }}>L</option>
                                <option value="XL" {{ old('tshirt_size') == 'XL' ? 'selected' : '' }}>XL</option>
                                <option value="XXL" {{ old('tshirt_size') == 'XXL' ? 'selected' : '' }}>XXL</option>
                            </select>
                            @error('tshirt_size')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Ukuran Sepatu
                            </label>
                            <select class="form-select form-select-sm @error('shoes_size') is-invalid @enderror"
                                id="shoes_size" 
                                name="shoes_size">
                                <option value="">Pilih</option>
                                @for($i = 36; $i <= 46; $i++)
                                <option value="{{ $i }}" {{ old('shoes_size') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            @error('shoes_size')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Sosial Media (Opsional) -->
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon-wrapper bg-info bg-opacity-10 p-1 rounded me-2" style="width: 28px; height: 28px;">
                            <i class="fas fa-hashtag text-info" style="font-size: 0.8rem;"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark small">Sosial Media (Opsional)</h6>
                    </div>
                    
                    <div class="row gx-1 gy-1 mb-2">
                        <div class="col-md-6 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Instagram
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light">@</span>
                                <input type="text" 
                                    class="form-control form-control-sm @error('instagram') is-invalid @enderror"
                                    id="instagram" 
                                    name="instagram" 
                                    value="{{ old('instagram') }}"
                                    placeholder="username">
                            </div>
                            @error('instagram')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                TikTok
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light">@</span>
                                <input type="text" 
                                    class="form-control form-control-sm @error('tiktok') is-invalid @enderror"
                                    id="tiktok" 
                                    name="tiktok" 
                                    value="{{ old('tiktok') }}"
                                    placeholder="username">
                            </div>
                            @error('tiktok')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 4: Upload Dokumen -->
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon-wrapper bg-danger bg-opacity-10 p-1 rounded me-2" style="width: 28px; height: 28px;">
                            <i class="fas fa-file-upload text-danger" style="font-size: 0.8rem;"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark small">Upload Dokumen</h6>
                    </div>
                    
                    <div class="alert alert-warning border-warning bg-warning-subtle mb-3 py-1 px-2 small">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        Format: JPG, JPEG, PNG (maks 2MB). Pastikan dokumen terbaca jelas.
                    </div>
                    
                    <div class="row gx-1 gy-1 mb-2">
                        <div class="col-md-6 mb-1">
                            <label class="form-label fw-semibold small mb-1">
                                Foto Formal 3x4 <span class="text-danger">*</span>
                            </label>
                            <input type="file" 
                                class="form-control form-control-sm @error('formal_photo') is-invalid @enderror"
                                name="formal_photo" 
                                accept="image/*" 
                                required>
                            <small class="form-text text-muted" style="font-size: 0.7rem;">
                                Background merah/biru, pakaian formal
                            </small>
                            <div class="mt-1">
                                <img id="formal_photo_preview" src="" alt="Preview" 
                                    class="img-thumbnail d-none" style="max-height: 100px; max-width: 150px;">
                            </div>
                            @error('formal_photo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-1">
                            <label class="form-label fw-semibold small mb-1">
                                Foto KTP/SIM <span class="text-danger">*</span>
                            </label>
                            <input type="file" 
                                class="form-control form-control-sm @error('identity_card') is-invalid @enderror"
                                name="identity_card" 
                                accept="image/*" 
                                required>
                            <small class="form-text text-muted" style="font-size: 0.7rem;">
                                Foto jelas, tidak blur
                            </small>
                            <div class="mt-1">
                                <img id="identity_card_preview" src="" alt="Preview" 
                                    class="img-thumbnail d-none" style="max-height: 100px; max-width: 150px;">
                            </div>
                            @error('identity_card')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row gx-1 gy-1">
                        <div class="col-md-6 mb-1">
                            <label class="form-label fw-semibold small mb-1">
                                Lisensi/Sertifikat
                            </label>
                            <input type="file" 
                                class="form-control form-control-sm @error('license_photo') is-invalid @enderror"
                                name="license_photo" 
                                accept="image/*,.pdf">
                            <small class="form-text text-muted" style="font-size: 0.7rem;">
                                Lisensi kepelatihan/sertifikat (opsional)
                            </small>
                            <div class="mt-1">
                                <img id="license_photo_preview" src="" alt="Preview" 
                                    class="img-thumbnail d-none" style="max-height: 100px; max-width: 150px;">
                            </div>
                            @error('license_photo')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Terms Agreement -->
                <div class="form-check mb-3">
                    <input class="form-check-input @error('terms') is-invalid @enderror"
                        type="checkbox" 
                        id="terms" 
                        name="terms" 
                        required>
                    <label class="form-check-label fw-medium small" for="terms" style="font-size: 0.8rem;">
                        Saya menyatakan bahwa data yang diisi adalah benar dan saya menyetujui 
                        <a href="#" class="text-warning">Syarat & Ketentuan</a> yang berlaku.
                    </label>
                    @error('terms')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="border-top pt-2 mt-2">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('form.team.join.role') }}" class="btn btn-outline-secondary btn-sm px-3">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-warning btn-sm px-3" id="submitBtn">
                            <i class="fas fa-paper-plane me-1"></i>Daftar Official
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('officialForm');
    const nikInput = document.getElementById('nik');
    const emailInput = document.getElementById('email');
    const submitBtn = document.getElementById('submitBtn');
    const birthdateInput = document.getElementById('birthdate');
    const categoryRadios = document.querySelectorAll('input[name="category"]');
    
    // Set max birthdate untuk usia minimal 18 tahun
    const today = new Date();
    const minBirthDate = new Date();
    minBirthDate.setFullYear(today.getFullYear() - 18);
    birthdateInput.max = minBirthDate.toISOString().split('T')[0];
    
    // Validasi kategori harus dipilih
    function validateCategory() {
        let isSelected = false;
        categoryRadios.forEach(radio => {
            if (radio.checked) isSelected = true;
        });
        
        const categoryError = document.querySelector('.category-error');
        if (!isSelected) {
            if (!categoryError) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'text-danger small mt-2 category-error';
                errorDiv.innerHTML = '<i class="fas fa-exclamation-circle me-1"></i>Pilih kategori official';
                document.querySelector('.category-card').parentElement.parentElement.appendChild(errorDiv);
            }
            return false;
        } else {
            if (categoryError) categoryError.remove();
            return true;
        }
    }
    
    categoryRadios.forEach(radio => {
        radio.addEventListener('change', validateCategory);
    });
    
    // Real-time NIK validation
    nikInput.addEventListener('blur', function() {
        const nik = this.value.trim();
        if (nik.length === 16) {
            checkNikAvailability(nik);
        } else if (nik.length > 0) {
            showError(nikInput, 'NIK harus 16 digit');
        }
    });
    
    // Real-time Email validation
    emailInput.addEventListener('blur', function() {
        const email = this.value.trim();
        if (email.includes('@') && email.includes('.')) {
            checkEmailAvailability(email);
        } else if (email.length > 0) {
            showError(emailInput, 'Format email tidak valid');
        }
    });
    
    // Image preview
    document.querySelector('input[name="formal_photo"]').addEventListener('change', function(e) {
        previewImage(e.target, 'formal_photo_preview');
    });
    
    document.querySelector('input[name="license_photo"]').addEventListener('change', function(e) {
        previewImage(e.target, 'license_photo_preview');
    });
    
    document.querySelector('input[name="identity_card"]').addEventListener('change', function(e) {
        previewImage(e.target, 'identity_card_preview');
    });
    
    // File size and type validation
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const maxSize = 2 * 1024 * 1024; // 2MB
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            
            if (this.name !== 'license_photo') {
                if (!allowedTypes.includes(file.type)) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format Tidak Didukung',
                        text: 'Format file harus JPG, JPEG, atau PNG!',
                        confirmButtonColor: '#f59e0b'
                    });
                    this.value = '';
                    resetPreview(this.name + '_preview');
                    return;
                }
            } else {
                if (!allowedTypes.includes(file.type) && file.type !== 'application/pdf') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format Tidak Didukung',
                        text: 'Format file harus JPG, JPEG, PNG, atau PDF!',
                        confirmButtonColor: '#f59e0b'
                    });
                    this.value = '';
                    resetPreview(this.name + '_preview');
                    return;
                }
            }
            
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Ukuran file maksimal 2MB!',
                    confirmButtonColor: '#f59e0b'
                });
                this.value = '';
                resetPreview(this.name + '_preview');
            }
        });
    });
    
    // Form validation before submit
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            // Get selected category label
            let categoryLabel = '';
            categoryRadios.forEach(radio => {
                if (radio.checked) {
                    categoryLabel = radio.id.replace('category_', '').split('_').map(word => 
                        word.charAt(0).toUpperCase() + word.slice(1)
                    ).join(' ');
                }
            });
            
            Swal.fire({
                title: 'Konfirmasi Pendaftaran',
                html: `
                    <div class="text-start">
                        <p class="mb-2">Anda akan mendaftar sebagai:</p>
                        <p class="mb-1 fw-bold">${document.getElementById('team_role').options[document.getElementById('team_role').selectedIndex].text}</p>
                        <p class="mb-2">Kategori: <span class="badge bg-${categoryLabel.includes('Putra') ? 'primary' : categoryLabel.includes('Putri') ? 'danger' : 'success'}">${categoryLabel}</span></p>
                        <p class="mb-2">Tim: <strong>{{ $team->school_name }}</strong></p>
                        <hr>
                        <p class="mb-0 small text-muted">Pastikan semua data dan dokumen sudah benar.</p>
                    </div>
                `,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Daftar Sekarang',
                cancelButtonText: 'Periksa Kembali'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memproses...';
                    submitBtn.disabled = true;
                    form.submit();
                }
            });
        } else {
            const firstError = form.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian!',
                html: 'Silakan periksa kembali data yang diisi.<br>Pastikan semua field wajib telah terisi dengan benar.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ffc107'
            });
        }
    });
    
    // Auto-fill birthday from NIK
    nikInput.addEventListener('input', function() {
        const nik = this.value;
        if (nik.length === 16 && !birthdateInput.value) {
            const day = nik.substr(6, 2);
            const month = nik.substr(8, 2);
            const year = nik.substr(10, 2);
            
            if (parseInt(day) >= 1 && parseInt(day) <= 31 && 
                parseInt(month) >= 1 && parseInt(month) <= 12) {
                
                let fullYear;
                const yearNum = parseInt(year);
                
                if (yearNum <= 40) {
                    fullYear = 2000 + yearNum;
                } else {
                    fullYear = 1900 + yearNum;
                }
                
                const dateStr = `${fullYear}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
                const dateObj = new Date(dateStr);
                
                if (!isNaN(dateObj.getTime())) {
                    const age = today.getFullYear() - fullYear;
                    if (age >= 18) {
                        birthdateInput.value = dateStr;
                    }
                }
            }
        }
    });
    
    // Functions
    function checkNikAvailability(nik) {
        fetch('{{ route("form.official.checkNik") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ nik: nik })
        })
        .then(response => response.json())
        .then(data => {
            const feedback = document.getElementById('nik-feedback');
            if (data.available) {
                nikInput.classList.remove('is-invalid');
                nikInput.classList.add('is-valid');
                feedback.textContent = '';
            } else {
                showError(nikInput, data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function checkEmailAvailability(email) {
        fetch('{{ route("form.official.checkEmail") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(data => {
            const feedback = document.getElementById('email-feedback');
            if (data.available) {
                emailInput.classList.remove('is-invalid');
                emailInput.classList.add('is-valid');
                feedback.textContent = '';
            } else {
                showError(emailInput, data.message);
            }
        })
        .catch(error => console.error('Error:', error));
    }
    
    function previewImage(input, previewId) {
        const preview = document.getElementById(previewId);
        const file = input.files[0];
        
        if (file && file.type.match('image.*')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        } else {
            resetPreview(previewId);
        }
    }
    
    function resetPreview(previewId) {
        const preview = document.getElementById(previewId);
        if (preview) {
            preview.src = '';
            preview.classList.add('d-none');
        }
    }
    
    function showError(input, message) {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        const feedbackId = input.id + '-feedback';
        const feedback = document.getElementById(feedbackId);
        if (feedback) {
            feedback.textContent = message;
        }
    }
    
    function validateForm() {
        let isValid = true;
        
        // Reset validation states
        const inputs = form.querySelectorAll('.form-control, .form-select, .form-check-input');
        inputs.forEach(input => {
            input.classList.remove('is-invalid', 'is-valid');
        });
        
        // Check category
        if (!validateCategory()) {
            isValid = false;
        }
        
        // Check required fields
        const requiredInputs = form.querySelectorAll('[required]');
        requiredInputs.forEach(input => {
            if (input.type === 'checkbox') {
                if (!input.checked) {
                    input.classList.add('is-invalid');
                    isValid = false;
                }
            } else if (input.type === 'file') {
                if (!input.files || input.files.length === 0) {
                    input.classList.add('is-invalid');
                    isValid = false;
                }
            } else if (input.type === 'radio') {
                // Radio buttons are handled by validateCategory
            } else {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                }
            }
        });
        
        // Check NIK length
        if (nikInput.value.length !== 16 && nikInput.value.length > 0) {
            showError(nikInput, 'NIK harus 16 digit');
            isValid = false;
        }
        
        // Check email format
        if (emailInput.value && !isValidEmail(emailInput.value)) {
            showError(emailInput, 'Format email tidak valid');
            isValid = false;
        }
        
        // Check age
        if (birthdateInput.value) {
            const birthDate = new Date(birthdateInput.value);
            const age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            if (age < 18) {
                showError(birthdateInput, 'Usia minimal 18 tahun');
                isValid = false;
            }
        }
        
        // Check phone number
        const phoneInput = document.getElementById('phone');
        if (phoneInput.value && phoneInput.value.length < 10) {
            showError(phoneInput, 'Nomor WhatsApp tidak valid');
            isValid = false;
        }
        
        return isValid;
    }
    
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
});
</script>

<style>
.container { 
    padding-top: 0.5rem !important;
    padding-left: 0.75rem !important;
    padding-right: 0.75rem !important;
}

.card { 
    border-radius: 8px; 
    max-width: 800px !important;
    margin: 0 auto;
}

.card-header { 
    border-radius: 8px 8px 0 0 !important; 
    padding-left: 1rem !important;
    padding-right: 1rem !important;
}

.bg-gradient-warning {
    background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%) !important;
}

.form-control-sm, .form-select-sm {
    padding: 0.3rem 0.6rem !important;
    font-size: 0.8rem !important;
    height: calc(1.5em + 0.6rem + 2px) !important;
    border-radius: 4px !important;
}

.form-label {
    font-size: 0.75rem !important;
    margin-bottom: 0.15rem !important;
    font-weight: 600;
}

.mb-1 { margin-bottom: 0.3rem !important; }
.mb-2 { margin-bottom: 0.6rem !important; }
.mb-3 { margin-bottom: 1rem !important; }

.row.gx-1 {
    --bs-gutter-x: 0.4rem;
}
.row.gy-1 {
    --bs-gutter-y: 0.4rem;
}

.alert {
    padding: 0.6rem !important;
    margin-bottom: 0.8rem !important;
    font-size: 0.8rem !important;
}

.btn-sm {
    padding: 0.3rem 0.8rem !important;
    font-size: 0.8rem !important;
    border-radius: 4px !important;
}

.card-body {
    padding: 1rem !important;
}

.form-check-input {
    width: 0.9em;
    height: 0.9em;
    margin-top: 0.15em;
}

.form-check-label {
    font-size: 0.8rem !important;
}

.border-top {
    border-top: 1px solid #dee2e6 !important;
    padding-top: 0.75rem !important;
    margin-top: 0.75rem !important;
}

.btn-warning {
    background-color: #f59e0b;
    border-color: #f59e0b;
    color: white;
}

.btn-warning:hover {
    background-color: #d97706;
    border-color: #d97706;
    color: white;
}

.input-group-sm .input-group-text {
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
}

.img-thumbnail {
    padding: 0.25rem;
    background-color: #fff;
    border: 1px solid #dee2e6;
    border-radius: 4px;
    max-width: 100%;
    height: auto;
}

.form-text {
    font-size: 0.7rem !important;
    color: #6c757d !important;
    margin-top: 0.15rem !important;
}

.icon-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Category Card Styles */
.category-card {
    transition: all 0.3s;
}

.btn-check:checked + .btn-outline-primary {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
    transform: scale(1.02);
    box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
}

.btn-check:checked + .btn-outline-danger {
    background-color: #dc3545;
    color: white;
    border-color: #dc3545;
    transform: scale(1.02);
    box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
}

.btn-check:checked + .btn-outline-success {
    background-color: #198754;
    color: white;
    border-color: #198754;
    transform: scale(1.02);
    box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3);
}

.btn-outline-primary, .btn-outline-danger, .btn-outline-success {
    border-width: 2px;
    transition: all 0.2s;
}

.btn-outline-primary:hover, 
.btn-outline-danger:hover, 
.btn-outline-success:hover {
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .container {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }
    
    .card-body {
        padding: 0.75rem !important;
    }
    
    .col-md-3, .col-md-6, .col-md-4 {
        margin-bottom: 0.4rem !important;
    }
    
    .row.gx-1 {
        --bs-gutter-x: 0.3rem;
    }
    
    .form-control-sm, .form-select-sm {
        padding: 0.25rem 0.5rem !important;
        font-size: 0.75rem !important;
    }
    
    .btn-sm {
        padding: 0.25rem 0.6rem !important;
        font-size: 0.75rem !important;
    }
    
    .d-flex.justify-content-between {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .btn-sm {
        width: 100%;
    }
    
    .img-thumbnail {
        max-height: 80px !important;
        max-width: 120px !important;
    }
    
    .btn-outline-primary, .btn-outline-danger, .btn-outline-success {
        padding: 0.5rem !important;
    }
}

/* Validation states */
.is-valid {
    border-color: #198754 !important;
}

.is-invalid {
    border-color: #dc3545 !important;
}

.bg-info-subtle {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

.border-info {
    border-color: #0dcaf0 !important;
}
</style>
@endsection