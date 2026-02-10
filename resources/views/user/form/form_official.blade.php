@extends('user.form.layout')
@section('title', 'Form Pendaftaran Official - HSBL')

@section('content')
<div class="container py-3 px-lg-4 px-md-3 px-sm-2">
    <!-- Role Indicator - Compact -->
    @if($role === 'Leader')
    <div class="alert alert-warning border-warning bg-warning-subtle mb-3 py-2 px-2 shadow-sm mx-auto" style="max-width: 780px;">
        <div class="d-flex align-items-center">
            <div class="bg-warning text-white rounded-circle p-1 me-2" style="width: 30px; height: 30px;">
                <i class="fas fa-crown"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-0 fw-bold small text-dark">Anda adalah Leader Official!</h6>
                <p class="mb-0 text-muted" style="font-size: 0.75rem;">Official pertama yang mendaftar</p>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info border-info bg-info-subtle mb-3 py-2 px-2 shadow-sm mx-auto" style="max-width: 780px;">
        <div class="d-flex align-items-center">
            <div class="bg-info text-white rounded-circle p-1 me-2" style="width: 30px; height: 30px;">
                <i class="fas fa-users"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-0 fw-bold small text-dark">Anda adalah Member Official</h6>
                <p class="mb-0 text-muted" style="font-size: 0.75rem;">Bergabung dengan referral code</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Form Card -->
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 800px; border-radius: 8px; width: 100%;">
        <!-- Card Header - Compact -->
        <div class="card-header bg-gradient-warning text-white py-2 px-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div class="flex-grow-1">
                    <h5 class="mb-0 fw-bold small">
                        @if($role === 'Leader')
                        <i class="fas fa-crown me-1"></i>Form Leader Official
                        @else
                        <i class="fas fa-user-tie me-1"></i>Form Official
                        @endif
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

        <!-- Form Content - Compact -->
        <div class="card-body p-3 px-md-3 px-sm-2">
            <form id="officialForm" action="{{ route('form.official.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team_id }}">
                <input type="hidden" name="team_category" value="{{ $teamCategory }}">

                <!-- Section 1: Data Pribadi -->
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
                            <input type="text" class="form-control form-control-sm @error('nik') is-invalid @enderror"
                                id="nik" name="nik" value="{{ old('nik') }}" required
                                placeholder="16 digit" maxlength="16"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <div class="invalid-feedback" id="nik-feedback"></div>
                            @error('nik')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Nama <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}" required
                                placeholder="Nama lengkap">
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Tgl Lahir <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control form-control-sm @error('birthdate') is-invalid @enderror"
                                id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required
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
                                id="gender" name="gender" required>
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
                            <input type="tel" class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                id="phone" name="phone" value="{{ old('phone') }}" required
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
                            <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" required
                                placeholder="email@example.com">
                            <div class="invalid-feedback" id="email-feedback"></div>
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Peran Tim <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm @error('team_role') is-invalid @enderror"
                                id="team_role" name="team_role" required>
                                <option value="">Pilih</option>
                                <option value="Coach" {{ old('team_role') == 'Coach' ? 'selected' : '' }}>Pelatih</option>
                                <option value="Manager" {{ old('team_role') == 'Manager' ? 'selected' : '' }}>Manajer</option>
                                <option value="Medical Support" {{ old('team_role') == 'Medical Support' ? 'selected' : '' }}>Dukungan Medis</option>
                                <option value="Assistant Coach" {{ old('team_role') == 'Assistant Coach' ? 'selected' : '' }}>Asisten Pelatih</option>
                                <option value="Pendamping" {{ old('team_role') == 'Pendamping' ? 'selected' : '' }}>Pendamping</option>
                            </select>
                            @error('team_role')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Kategori <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm @error('category') is-invalid @enderror"
                                id="category" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <option value="basket_putra" {{ (old('category') == 'basket_putra' || $teamCategory == 'basket_putra') ? 'selected' : '' }}>
                                    Basket Putra
                                </option>
                                <option value="basket_putri" {{ (old('category') == 'basket_putri' || $teamCategory == 'basket_putri') ? 'selected' : '' }}>
                                    Basket Putri
                                </option>
                                <option value="dancer" {{ (old('category') == 'dancer' || $teamCategory == 'dancer') ? 'selected' : '' }}>
                                    Dancer
                                </option>
                                <option value="lainnya" {{ old('category') == 'lainnya' ? 'selected' : '' }}>
                                    Lainnya
                                </option>
                            </select>
                            <small class="form-text text-muted" style="font-size: 0.7rem;">
                                Pilih kategori yang sesuai
                            </small>
                            @error('category')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 3 -->
                    <div class="row gx-1 gy-1 mb-2">
                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Sekolah <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-sm bg-light-subtle"
                                value="{{ $team->school_name }}" readonly>
                            <input type="hidden" name="school" value="{{ $team->school_name }}">
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Instagram
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light">
                                    <i class="fab fa-instagram text-danger"></i>
                                </span>
                                <input type="text" class="form-control form-control-sm @error('instagram') is-invalid @enderror"
                                    id="instagram" name="instagram" value="{{ old('instagram') }}"
                                    placeholder="username">
                            </div>
                            @error('instagram')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                TikTok
                            </label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light">
                                    <i class="fab fa-tiktok"></i>
                                </span>
                                <input type="text" class="form-control form-control-sm @error('tiktok') is-invalid @enderror"
                                    id="tiktok" name="tiktok" value="{{ old('tiktok') }}"
                                    placeholder="username">
                            </div>
                            @error('tiktok')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Data Fisik -->
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon-wrapper bg-success bg-opacity-10 p-1 rounded me-2" style="width: 28px; height: 28px;">
                            <i class="fas fa-running text-success" style="font-size: 0.8rem;"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark small">Data Fisik</h6>
                    </div>
                    
                    <div class="row gx-1 gy-1 mb-2">
                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Tinggi (cm)
                            </label>
                            <input type="number" class="form-control form-control-sm @error('height') is-invalid @enderror"
                                id="height" name="height" value="{{ old('height') }}"
                                min="100" max="250" step="1" placeholder="170">
                            <small class="form-text text-muted" style="font-size: 0.7rem;">
                                Contoh: 170
                            </small>
                            @error('height')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Berat (kg)
                            </label>
                            <input type="number" class="form-control form-control-sm @error('weight') is-invalid @enderror"
                                id="weight" name="weight" value="{{ old('weight') }}"
                                min="30" max="200" step="0.5" placeholder="65.5">
                            <small class="form-text text-muted" style="font-size: 0.7rem;">
                                Contoh: 65.5
                            </small>
                            @error('weight')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Uk. Kaos
                            </label>
                            <select class="form-select form-select-sm @error('tshirt_size') is-invalid @enderror"
                                id="tshirt_size" name="tshirt_size">
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
                                Uk. Sepatu
                            </label>
                            <select class="form-select form-select-sm @error('shoes_size') is-invalid @enderror"
                                id="shoes_size" name="shoes_size">
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

                <!-- Section 3: Upload Dokumen -->
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
                    
                    <!-- Dokumen Wajib -->
                    <div class="mb-3">
                        <h6 class="fw-bold mb-2 small text-success">
                            Dokumen Wajib <span class="badge bg-success ms-1 small">WAJIB</span>
                        </h6>
                        
                        <div class="row gx-1 gy-1 mb-2">
                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-semibold small mb-1">
                                    Foto Formal <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control form-control-sm @error('formal_photo') is-invalid @enderror"
                                    name="formal_photo" accept="image/*" required>
                                <div class="form-text" style="font-size: 0.7rem;">
                                    Formal 3x4, latar polos
                                </div>
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
                                <input type="file" class="form-control form-control-sm @error('identity_card') is-invalid @enderror"
                                    name="identity_card" accept="image/*" required>
                                <div class="form-text" style="font-size: 0.7rem;">
                                    Foto KTP/SIM yang berlaku
                                </div>
                                <div class="mt-1">
                                    <img id="identity_card_preview" src="" alt="Preview" 
                                         class="img-thumbnail d-none" style="max-height: 100px; max-width: 150px;">
                                </div>
                                @error('identity_card')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Dokumen Tambahan -->
                    <div class="mb-3">
                        <h6 class="fw-bold mb-2 small text-primary">
                            Dokumen Tambahan <span class="badge bg-info ms-1 small">OPSIONAL</span>
                        </h6>
                        
                        <div class="row gx-1 gy-1 mb-2">
                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-semibold small mb-1">
                                    Lisensi/Sertifikat
                                </label>
                                <input type="file" class="form-control form-control-sm @error('license_photo') is-invalid @enderror"
                                    name="license_photo" accept="image/*,.pdf">
                                <div class="form-text" style="font-size: 0.7rem;">
                                    Sertifikat pelatih/lisensi
                                </div>
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
                </div>

                <!-- Terms Agreement -->
                <div class="form-check mb-3">
                    <input class="form-check-input @error('terms') is-invalid @enderror"
                        type="checkbox" id="terms" name="terms" required>
                    <label class="form-check-label fw-medium small" for="terms" style="font-size: 0.8rem;">
                        Saya menyetujui Syarat & Ketentuan dan memastikan data benar.
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
                            @if($role === 'Leader')
                            <i class="fas fa-crown me-1"></i>Daftar
                            @else
                            <i class="fas fa-paper-plane me-1"></i>Kirim
                            @endif
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
    const categorySelect = document.getElementById('category');
    const teamRoleSelect = document.getElementById('team_role');
    
    // Set max birthdate untuk usia minimal 18 tahun
    const today = new Date();
    const minBirthDate = new Date();
    minBirthDate.setFullYear(today.getFullYear() - 18);
    birthdateInput.max = minBirthDate.toISOString().split('T')[0];
    
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
    
    // Auto-select category berdasarkan team_role
    teamRoleSelect.addEventListener('change', function() {
        const role = this.value;
        if (role === 'Coach' || role === 'Assistant Coach') {
            // Jika pelatih, set category berdasarkan tim atau pilihan user
            if (categorySelect.value === '') {
                const teamCategory = document.querySelector('input[name="team_category"]').value;
                if (teamCategory) {
                    categorySelect.value = teamCategory;
                }
            }
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
    
    // File size validation
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            const maxSize = 2 * 1024 * 1024; // 2MB
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            
            // Check file type
            if (!allowedTypes.includes(file.type) && file.type !== 'application/pdf') {
                alert(`Format file tidak didukung! Hanya JPG, JPEG, PNG, atau PDF.`);
                this.value = '';
                resetPreview(this.name + '_preview');
                return;
            }
            
            // Check file size
            if (file.size > maxSize) {
                alert(`File terlalu besar! Maksimal 2MB`);
                this.value = '';
                resetPreview(this.name + '_preview');
            }
        });
    });
    
    // Form validation before submit
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (validateForm()) {
            // Show confirmation
            Swal.fire({
                title: 'Konfirmasi Pendaftaran',
                html: `
                    <div class="text-start">
                        <p class="mb-2">Anda akan mendaftar sebagai <strong>${document.getElementById('team_role').options[document.getElementById('team_role').selectedIndex].text}</strong></p>
                        <p class="mb-2">Kategori: <strong>${document.getElementById('category').options[document.getElementById('category').selectedIndex].text}</strong></p>
                        <p class="mb-0 small text-muted">Pastikan data yang diisi sudah benar.</p>
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
                    // Show loading state
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memproses...';
                    submitBtn.disabled = true;
                    
                    // Submit form
                    form.submit();
                }
            });
        } else {
            // Scroll to first error
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
        .catch(error => {
            console.error('Error:', error);
        });
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
        .catch(error => {
            console.error('Error:', error);
        });
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
        const feedback = input.nextElementSibling;
        if (feedback && feedback.classList.contains('invalid-feedback')) {
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
        
        // Check age (min 18 years)
        if (birthdateInput.value) {
            const birthDate = new Date(birthdateInput.value);
            const age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            if (age < 18) {
                showError(birthdateInput, 'Usia minimal 18 tahun untuk official');
                isValid = false;
            }
        }
        
        // Check category selection
        if (!categorySelect.value) {
            showError(categorySelect, 'Pilih kategori official');
            isValid = false;
        }
        
        return isValid;
    }
    
    function isValidEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    
    // Auto-fill birthday from NIK (if possible)
    nikInput.addEventListener('input', function() {
        const nik = this.value;
        if (nik.length === 16 && !birthdateInput.value) {
            // Try to extract birthdate from NIK (positions 6-13 in format DDMMYYYY)
            const day = nik.substr(6, 2);
            const month = nik.substr(8, 2);
            const year = nik.substr(10, 2);
            
            // Validate day and month
            if (parseInt(day) >= 1 && parseInt(day) <= 31 && 
                parseInt(month) >= 1 && parseInt(month) <= 12) {
                
                // Determine century (for NIK, 00-40: 2000-2040, 41-99: 1941-1999)
                let fullYear;
                const yearNum = parseInt(year);
                
                if (yearNum <= 40) {
                    fullYear = 2000 + yearNum;
                } else {
                    fullYear = 1900 + yearNum;
                }
                
                // Check if date is valid
                const dateStr = `${fullYear}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
                const dateObj = new Date(dateStr);
                
                if (!isNaN(dateObj.getTime())) {
                    // Check if age is at least 18
                    const today = new Date();
                    const age = today.getFullYear() - fullYear;
                    
                    if (age >= 18) {
                        birthdateInput.value = dateStr;
                    }
                }
            }
        }
    });
});
</script>

<style>
/* Konsisten dengan form player */
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
.p-2 { padding: 0.5rem !important; }
.p-3 { padding: 0.8rem !important; }

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

.badge.small {
    font-size: 0.7rem !important;
    padding: 0.2rem 0.4rem !important;
}

.form-check-input {
    width: 0.9em;
    height: 0.9em;
    margin-top: 0.15em;
}

.form-check-label {
    font-size: 0.8rem !important;
}

h6.small {
    font-size: 0.85rem !important;
    font-weight: 600;
}

.border-top {
    border-top: 1px solid #dee2e6 !important;
    padding-top: 0.75rem !important;
    margin-top: 0.75rem !important;
}

/* Specific untuk official */
.bg-warning-subtle {
    background-color: rgba(251, 191, 36, 0.1) !important;
}

.border-warning {
    border-color: #fbbf24 !important;
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

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #f59e0b;
    border-radius: 3px;
}

/* Focus states */
.form-control:focus, .form-select:focus {
    border-color: #f59e0b;
    box-shadow: 0 0 0 0.2rem rgba(245, 158, 11, 0.25);
    outline: none;
}

/* Responsive */
@media (min-width: 768px) {
    .card-body {
        padding: 1.25rem !important;
    }
}

@media (min-width: 992px) {
    .container {
        padding-left: 1.5rem !important;
        padding-right: 1.5rem !important;
    }
    
    .card {
        max-width: 820px !important;
    }
}

@media (max-width: 768px) {
    .container {
        padding-left: 0.5rem !important;
        padding-right: 0.5rem !important;
    }
    
    .card-body {
        padding: 0.75rem !important;
    }
    
    .col-md-3, .col-md-6 {
        margin-bottom: 0.4rem !important;
    }
    
    .row.gx-1 {
        --bs-gutter-x: 0.3rem;
    }
    .row.gy-1 {
        --bs-gutter-y: 0.3rem;
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
        margin-bottom: 0.25rem;
    }
    
    .img-thumbnail {
        max-height: 80px !important;
        max-width: 120px !important;
    }
}

@media (min-width: 1400px) {
    .container {
        max-width: 900px;
        margin: 0 auto;
    }
}

/* Validation states */
.is-valid {
    border-color: #198754 !important;
}

.is-invalid {
    border-color: #dc3545 !important;
}

/* Category specific styling */
.category-option-basket_putra {
    position: relative;
}

.category-option-basket_putra::before {
    content: "🏀";
    margin-right: 5px;
}

.category-option-basket_putri::before {
    content: "🏀";
    margin-right: 5px;
}

.category-option-dancer::before {
    content: "💃";
    margin-right: 5px;
}

.category-option-lainnya::before {
    content: "👥";
    margin-right: 5px;
}
</style>
@endsection