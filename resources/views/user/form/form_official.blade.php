@extends('user.form.layout')

@section('title', 'Form Pendaftaran Official - SBL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Header -->
            <div class="text-center mb-5">
                <span class="badge bg-soft-primary text-primary px-4 py-2 mb-3 rounded-pill">
                    📋 SBL Official Registration
                </span>
                <h1 class="display-5 fw-bold text-dark mb-3">
                    Form Pendaftaran Official
                </h1>
                <p class="lead text-muted">{{ $team->school_name }} • Official</p>
            </div>

            <!-- Info Kategori -->
            <div class="row justify-content-center mb-4">
                <div class="col-md-8">
                    <div class="card border-0 shadow-sm bg-soft-primary">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-primary me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-info-circle text-white"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Pilih Kategori Official</h6>
                                    <p class="text-muted small mb-0">Anda akan mendampingi tim yang dipilih</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <form id="officialForm" action="{{ route('form.official.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="team_id" value="{{ $team->team_id }}">

                        <!-- SECTION PILIHAN KATEGORI -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-circle bg-gradient-orange me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-tag text-white"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Pilih Kategori Tim</h5>
                                <span class="badge bg-soft-warning text-warning ms-3 px-3 py-2">Wajib Dipilih</span>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-body text-center p-4">
                                            <input type="radio" class="btn-check" name="category" id="category_basket_putra" value="basket_putra" autocomplete="off" {{ old('category') == 'basket_putra' ? 'checked' : '' }} required>
                                            <label class="btn btn-outline-primary w-100 py-3" for="category_basket_putra">
                                                <i class="fas fa-basketball-ball fa-3x mb-3"></i>
                                                <h6 class="fw-bold mb-2">BASKET PUTRA</h6>
                                                <p class="small text-muted mb-0">Official tim putra</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-body text-center p-4">
                                            <input type="radio" class="btn-check" name="category" id="category_basket_putri" value="basket_putri" autocomplete="off" {{ old('category') == 'basket_putri' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-danger w-100 py-3" for="category_basket_putri">
                                                <i class="fas fa-basketball-ball fa-3x mb-3"></i>
                                                <h6 class="fw-bold mb-2">BASKET PUTRI</h6>
                                                <p class="small text-muted mb-0">Official tim putri</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card border-0 shadow-sm h-100">
                                        <div class="card-body text-center p-4">
                                            <input type="radio" class="btn-check" name="category" id="category_dancer" value="dancer" autocomplete="off" {{ old('category') == 'dancer' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-success w-100 py-3" for="category_dancer">
                                                <i class="fas fa-music fa-3x mb-3"></i>
                                                <h6 class="fw-bold mb-2">DANCER</h6>
                                                <p class="small text-muted mb-0">Official tim dancer</p>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @error('category')
                            <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- SECTION 1: Data Pribadi -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-circle bg-gradient-primary me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-id-card text-white"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Data Pribadi</h5>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">NIK <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('nik') is-invalid @enderror"
                                        id="nik" name="nik" value="{{ old('nik') }}" required
                                        placeholder="16 digit" maxlength="16"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name') }}" required
                                        placeholder="Nama lengkap">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Tanggal Lahir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('birthdate') is-invalid @enderror"
                                        id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required
                                        max="{{ date('Y-m-d', strtotime('-18 years')) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-select @error('gender') is-invalid @enderror"
                                        id="gender" name="gender" required>
                                        <option value="">Pilih</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">WhatsApp <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone') }}" required
                                        placeholder="081234567890"
                                        oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email') }}" required
                                        placeholder="email@example.com">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Peran dalam Tim <span class="text-danger">*</span></label>
                                    <select class="form-select @error('team_role') is-invalid @enderror"
                                        id="team_role" name="team_role" required>
                                        <option value="">Pilih Peran</option>
                                        <option value="Coach" {{ old('team_role') == 'Coach' ? 'selected' : '' }}>Pelatih</option>
                                        <option value="Assistant Coach" {{ old('team_role') == 'Assistant Coach' ? 'selected' : '' }}>Asisten Pelatih</option>
                                        <option value="Manager" {{ old('team_role') == 'Manager' ? 'selected' : '' }}>Manajer</option>
                                        <option value="Medical Support" {{ old('team_role') == 'Medical Support' ? 'selected' : '' }}>Dukungan Medis</option>
                                        <option value="Pendamping" {{ old('team_role') == 'Pendamping' ? 'selected' : '' }}>Pendamping</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Sekolah <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control bg-light" value="{{ $team->school_name }}" readonly>
                                    <input type="hidden" name="school" value="{{ $team->school_name }}">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Data Fisik (Opsional) -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-circle bg-gradient-teal me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-running text-white"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Data Fisik</h5>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Tinggi (cm)</label>
                                    <input type="number" class="form-control @error('height') is-invalid @enderror"
                                        id="height" name="height" value="{{ old('height') }}"
                                        min="100" max="250" step="1" placeholder="170" required >
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Berat (kg)</label>
                                    <input type="number" class="form-control @error('weight') is-invalid @enderror"
                                        id="weight" name="weight" value="{{ old('weight') }}"
                                        min="30" max="200" step="0.5" placeholder="65.5" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Ukuran Kaos</label>
                                    <select class="form-select @error('tshirt_size') is-invalid @enderror required"
                                        id="tshirt_size" name="tshirt_size">
                                        <option value="">Pilih</option>
                                        <option value="S" {{ old('tshirt_size') == 'S' ? 'selected' : '' }}>S</option>
                                        <option value="M" {{ old('tshirt_size') == 'M' ? 'selected' : '' }}>M</option>
                                        <option value="L" {{ old('tshirt_size') == 'L' ? 'selected' : '' }}>L</option>
                                        <option value="XL" {{ old('tshirt_size') == 'XL' ? 'selected' : '' }}>XL</option>
                                        <option value="XXL" {{ old('tshirt_size') == 'XXL' ? 'selected' : '' }}>XXL</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Ukuran Sepatu</label>
                                    <select class="form-select @error('shoes_size') is-invalid @enderror"
                                        id="shoes_size" name="shoes_size" required>
                                        <option value="">Pilih</option>
                                        @for($i = 36; $i <= 46; $i++)
                                            <option value="{{ $i }}" {{ old('shoes_size') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: Sosial Media (Opsional) -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-circle bg-gradient-orange me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-hashtag text-white"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Sosial Media</h5>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Instagram</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">@</span>
                                        <input type="text" class="form-control @error('instagram') is-invalid @enderror"
                                            id="instagram" name="instagram" value="{{ old('instagram') }}" placeholder="username" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">TikTok</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">@</span>
                                        <input type="text" class="form-control @error('tiktok') is-invalid @enderror"
                                            id="tiktok" name="tiktok" value="{{ old('tiktok') }}" placeholder="username" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: Upload Dokumen -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-circle bg-gradient-teal me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-file-upload text-white"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Upload Dokumen</h5>
                            </div>

                            <!-- 🔥 ALERT INI TETAP ADA DAN TIDAK AKAN HILANG 🔥 -->
                            <div class="info-box" style="background-color: #cff4fc; border: 1px solid #b6effb; border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem; color: #055160;">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle" style="color: #0dcaf0; font-size: 1.2rem;"></i>
                                    </div>
                                    <div>
                                        <h6 style="font-weight: 600; margin-bottom: 0.25rem;">Informasi Upload Dokumen</h6>
                                        <p style="margin-bottom: 0; font-size: 0.9rem;">Format: JPG, JPEG, PNG (maks 2MB). Pastikan dokumen terbaca jelas.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Foto Formal 3x4 <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('formal_photo') is-invalid @enderror"
                                        id="formal_photo" name="formal_photo" accept="image/*" required>
                                    <small class="text-muted">Background merah/biru, pakaian formal</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Foto KTP/SIM <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('identity_card') is-invalid @enderror"
                                        id="identity_card" name="identity_card" accept="image/*" required>
                                    <small class="text-muted">Foto jelas, tidak blur</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Lisensi/Sertifikat <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('license_photo') is-invalid @enderror"
                                        id="license_photo" name="license_photo" accept="image/*,.pdf" required>
                                    <small class="text-muted">Lisensi kepelatihan/sertifikat</small>
                                </div>
                            </div>
                        </div>

                        <!-- Terms Agreement -->
                        <div class="form-check mb-4">
                            <input class="form-check-input @error('terms') is-invalid @enderror"
                                type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                Saya menyatakan bahwa data yang diisi adalah benar dan saya menyetujui
                                <a href="#" class="text-primary">Syarat & Ketentuan</a> yang berlaku.
                            </label>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="border-top pt-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    <i class="fas fa-shield-alt me-1"></i>Data Anda aman dan terenkripsi
                                </small>
                                <div>
                                    <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary px-4 me-2">
                                        <i class="fas fa-times me-2"></i>Batal
                                    </a>
                                    <button type="submit" id="submitBtn" class="btn btn-primary px-5">
                                        <i class="fas fa-paper-plane me-2"></i>Daftar Official
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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

<style>
    /* Custom Colors */
    .bg-gradient-orange {
        background: linear-gradient(135deg, #FF6B6B 0%, #FF8E53 100%);
    }

    .bg-gradient-teal {
        background: linear-gradient(135deg, #4ECDC4 0%, #556270 100%);
    }

    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .bg-soft-primary {
        background-color: rgba(79, 70, 229, 0.1);
    }

    .bg-soft-warning {
        background-color: rgba(255, 159, 67, 0.1);
    }

    .bg-soft-teal {
        background-color: rgba(78, 205, 196, 0.1);
    }

    .icon-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    .card {
        border-radius: 1.5rem;
        overflow: hidden;
    }

    .card-body {
        border-radius: 1.5rem;
    }

    .form-control,
    .form-select {
        border: 1.5px solid #e9ecef;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        transition: all 0.3s;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: #2b2d42;
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
        border: 1.5px solid #e9ecef;
        font-weight: 500;
    }

    .btn-outline-secondary:hover {
        background: #f8f9fa;
        border-color: #adb5bd;
        transform: translateY(-2px);
    }

    .btn-outline-primary,
    .btn-outline-danger,
    .btn-outline-success {
        border-width: 2px;
        transition: all 0.3s;
    }

    .btn-outline-primary:hover {
        background-color: rgba(13, 110, 253, 0.1);
        transform: translateY(-2px);
    }

    .btn-outline-danger:hover {
        background-color: rgba(220, 53, 69, 0.1);
        transform: translateY(-2px);
    }

    .btn-outline-success:hover {
        background-color: rgba(25, 135, 84, 0.1);
        transform: translateY(-2px);
    }

    .btn-check:checked+.btn-outline-primary {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
        transform: scale(1.02);
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.3);
    }

    .btn-check:checked+.btn-outline-danger {
        background-color: #dc3545;
        color: white;
        border-color: #dc3545;
        transform: scale(1.02);
        box-shadow: 0 4px 10px rgba(220, 53, 69, 0.3);
    }

    .btn-check:checked+.btn-outline-success {
        background-color: #198754;
        color: white;
        border-color: #198754;
        transform: scale(1.02);
        box-shadow: 0 4px 10px rgba(25, 135, 84, 0.3);
    }

    .alert {
        border-radius: 12px;
    }

    .input-group-text {
        border: 1.5px solid #e9ecef;
        border-radius: 12px 0 0 12px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 2rem !important;
        }

        .btn {
            width: 100%;
            margin-bottom: 0.5rem;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 1rem;
        }

        .d-flex.justify-content-between>div {
            width: 100%;
        }

        .d-flex.justify-content-between .btn {
            width: 100%;
            margin: 0.25rem 0;
        }
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('officialForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    const nikInput = document.getElementById('nik');
    const emailInput = document.getElementById('email');
    const birthdateInput = document.getElementById('birthdate');
    const categoryRadios = document.querySelectorAll('input[name="category"]');

    // Set max birthdate untuk usia minimal 18 tahun
    const today = new Date();
    const minBirthDate = new Date();
    minBirthDate.setFullYear(today.getFullYear() - 18);
    birthdateInput.max = minBirthDate.toISOString().split('T')[0];

    // Validasi kategori
    function validateCategory() {
        let isSelected = false;
        categoryRadios.forEach(radio => {
            if (radio.checked) isSelected = true;
        });
        return isSelected;
    }

    // NIK validation
    nikInput.addEventListener('blur', function() {
        if (this.value.length !== 16 && this.value.length > 0) {
            alert('NIK harus 16 digit');
            this.focus();
        }
    });

    // File size validation
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            if (file.size > 2 * 1024 * 1024) {
                alert('File terlalu besar! Maksimal 2MB');
                this.value = '';
            }
        });
    });

    // ===== 🔥 PERBAIKAN UTAMA: FORM VALIDATION 🔥 =====
    form.addEventListener('submit', function(e) {
        // Validasi lengkap terlebih dahulu
        if (!validateAllFields()) {
            e.preventDefault();
            return false;
        }

        // Baru tampilkan loading setelah validasi sukses
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        submitBtn.disabled = true;
        loadingModal.show();

        return true;
    });

    function validateAllFields() {
        // 1. Cek kategori
        if (!validateCategory()) {
            alert('Pilih kategori official terlebih dahulu!');
            return false;
        }

        // 2. Cek semua input text/select yang required
        const requiredInputs = [
            { id: 'nik', name: 'NIK' },
            { id: 'name', name: 'Nama Lengkap' },
            { id: 'birthdate', name: 'Tanggal Lahir' },
            { id: 'gender', name: 'Jenis Kelamin' },
            { id: 'phone', name: 'WhatsApp' },
            { id: 'email', name: 'Email' },
            { id: 'team_role', name: 'Peran dalam Tim' },
            { id: 'height', name: 'Tinggi Badan' },
            { id: 'weight', name: 'Berat Badan' },
            { id: 'tshirt_size', name: 'Ukuran Kaos' },
            { id: 'shoes_size', name: 'Ukuran Sepatu' },
            { id: 'instagram', name: 'Instagram' },
            { id: 'tiktok', name: 'TikTok' }
        ];

        for (const field of requiredInputs) {
            const element = document.getElementById(field.id);
            if (!element || !element.value.trim()) {
                alert(`Harap isi ${field.name}`);
                element?.focus();
                return false;
            }
        }

        // 3. Validasi khusus NIK harus 16 digit
        const nik = document.getElementById('nik').value;
        if (nik.length !== 16) {
            alert('NIK harus 16 digit');
            document.getElementById('nik').focus();
            return false;
        }

        // 4. Validasi email format
        const email = document.getElementById('email').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Format email tidak valid');
            document.getElementById('email').focus();
            return false;
        }

        // 5. Validasi phone minimal 10 digit
        const phone = document.getElementById('phone').value.replace(/[^0-9]/g, '');
        if (phone.length < 10) {
            alert('Nomor WhatsApp minimal 10 digit');
            document.getElementById('phone').focus();
            return false;
        }

        // 6. Cek file upload
        const fileInputs = [
            { id: 'formal_photo', name: 'Foto Formal' },
            { id: 'identity_card', name: 'Foto KTP/SIM' },
            { id: 'license_photo', name: 'Lisensi/Sertifikat' }
        ];

        for (const file of fileInputs) {
            const element = document.getElementById(file.id);
            if (!element.files || element.files.length === 0) {
                alert(`Upload ${file.name}`);
                element.focus();
                return false;
            }
        }

        // 7. Cek terms checkbox
        if (!document.getElementById('terms').checked) {
            alert('Anda harus menyetujui syarat & ketentuan');
            document.getElementById('terms').focus();
            return false;
        }

        return true;
    }
});
</script>
@endpush
@endsection