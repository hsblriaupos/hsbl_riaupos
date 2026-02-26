@extends('user.form.layout')

@section('title', 'Form Pendaftaran Dancer - SBL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Header -->
            <div class="text-center mb-5">
                <span class="badge bg-soft-primary text-primary px-4 py-2 mb-3 rounded-pill">
                    💃 SBL Dancer Registration
                </span>
                <h1 class="display-5 fw-bold text-dark mb-3">
                    @if($role === 'Leader')
                    Form Leader Dancer
                    @else
                    Form Dancer
                    @endif
                </h1>
                <p class="lead text-muted">{{ $team->school_name }} • Dancer</p>
            </div>

            <!-- Role Indicator -->
            <div class="row justify-content-center mb-4">
                <div class="col-md-8">
                    @if($role === 'Leader')
                    <div class="card border-0 shadow-sm bg-soft-warning">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-orange me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-crown text-white"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Anda adalah Leader Tim Dancer!</h6>
                                    <p class="text-muted small mb-0">Bertanggung jawab untuk pembayaran tim</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="card border-0 shadow-sm bg-soft-teal">
                        <div class="card-body py-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-teal me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold text-dark mb-1">Anda adalah Member Tim Dancer</h6>
                                    <p class="text-muted small mb-0">Bergabung dengan referral code</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="card border-0 shadow-lg">
                <div class="card-body p-5">
                    <form id="dancerForm" action="{{ route('form.dancer.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="team_id" value="{{ $team->team_id }}">
                        <input type="hidden" name="team_role" value="{{ $role }}">

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
                                        max="{{ date('Y-m-d', strtotime('-10 years')) }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-select @error('gender') is-invalid @enderror"
                                        id="gender" name="gender" required>
                                        <option value="">Pilih</option>
                                        <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
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
                                    <label class="form-label fw-medium">Kelas <span class="text-danger">*</span></label>
                                    <select class="form-select @error('grade') is-invalid @enderror"
                                        id="grade" name="grade" required>
                                        <option value="">Pilih</option>
                                        <option value="X" {{ old('grade') == 'X' ? 'selected' : '' }}>X</option>
                                        <option value="XI" {{ old('grade') == 'XI' ? 'selected' : '' }}>XI</option>
                                        <option value="XII" {{ old('grade') == 'XII' ? 'selected' : '' }}>XII</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Tahun STTB <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('sttb_year') is-invalid @enderror"
                                        id="sttb_year" name="sttb_year" value="{{ old('sttb_year') }}" required
                                        placeholder="2024" maxlength="4"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Instagram <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('instagram') is-invalid @enderror"
                                        id="instagram" name="instagram" value="{{ old('instagram') }}" required
                                        placeholder="@username">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">TikTok <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('tiktok') is-invalid @enderror"
                                        id="tiktok" name="tiktok" value="{{ old('tiktok') }}" required
                                        placeholder="@username">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Data Fisik -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-circle bg-gradient-teal me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-running text-white"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Data Fisik</h5>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Tinggi (cm) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('height') is-invalid @enderror"
                                        id="height" name="height" value="{{ old('height') }}" required
                                        min="100" max="250" step="1" placeholder="170">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Berat (kg) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('weight') is-invalid @enderror"
                                        id="weight" name="weight" value="{{ old('weight') }}" required
                                        min="30" max="150" step="0.5" placeholder="65.5">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Ukuran Kaos <span class="text-danger">*</span></label>
                                    <select class="form-select @error('tshirt_size') is-invalid @enderror"
                                        id="tshirt_size" name="tshirt_size" required>
                                        <option value="">Pilih</option>
                                        <option value="S" {{ old('tshirt_size') == 'S' ? 'selected' : '' }}>S</option>
                                        <option value="M" {{ old('tshirt_size') == 'M' ? 'selected' : '' }}>M</option>
                                        <option value="L" {{ old('tshirt_size') == 'L' ? 'selected' : '' }}>L</option>
                                        <option value="XL" {{ old('tshirt_size') == 'XL' ? 'selected' : '' }}>XL</option>
                                        <option value="XXL" {{ old('tshirt_size') == 'XXL' ? 'selected' : '' }}>XXL</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-medium">Ukuran Sepatu <span class="text-danger">*</span></label>
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

                        <!-- SECTION 3: Data Orang Tua -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-circle bg-gradient-orange me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Data Orang Tua</h5>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm p-4">
                                        <h6 class="fw-bold text-primary mb-3">Ayah</h6>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <input type="text" class="form-control @error('father_name') is-invalid @enderror"
                                                    id="father_name" name="father_name" value="{{ old('father_name') }}" placeholder="Nama ayah" required>
                                            </div>
                                            <div class="col-12">
                                                <input type="tel" class="form-control @error('father_phone') is-invalid @enderror"
                                                    id="father_phone" name="father_phone" value="{{ old('father_phone') }}" placeholder="No. telepon" required
                                                    oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm p-4">
                                        <h6 class="fw-bold text-primary mb-3">Ibu</h6>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <input type="text" class="form-control @error('mother_name') is-invalid @enderror"
                                                    id="mother_name" name="mother_name" value="{{ old('mother_name') }}" placeholder="Nama ibu" required>
                                            </div>
                                            <div class="col-12">
                                                <input type="tel" class="form-control @error('mother_phone') is-invalid @enderror"
                                                    id="mother_phone" name="mother_phone" value="{{ old('mother_phone') }}" placeholder="No. telepon" required
                                                    oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: Dokumen Wajib -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-circle bg-gradient-teal me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-file-upload text-white"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Dokumen Wajib</h5>
                            </div>
                            
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Akta Kelahiran <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('birth_certificate') is-invalid @enderror"
                                        id="birth_certificate" name="birth_certificate" accept=".pdf" required>
                                    <small class="text-muted">Maks. 1MB, PDF</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Kartu Keluarga <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('kk') is-invalid @enderror"
                                        id="kk" name="kk" accept=".pdf" required>
                                    <small class="text-muted">Maks. 1MB, PDF</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">SHUN <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('shun') is-invalid @enderror"
                                        id="shun" name="shun" accept=".pdf" required>
                                    <small class="text-muted">Maks. 1MB, PDF</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Laporan Identitas <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('report_identity') is-invalid @enderror"
                                        id="report_identity" name="report_identity" accept=".pdf" required>
                                    <small class="text-muted">Maks. 1MB, PDF</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Raport Terakhir <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('last_report_card') is-invalid @enderror"
                                        id="last_report_card" name="last_report_card" accept=".pdf" required>
                                    <small class="text-muted">Maks. 1MB, PDF</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Foto Formal <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('formal_photo') is-invalid @enderror"
                                        id="formal_photo" name="formal_photo" accept=".jpg,.jpeg,.png" required>
                                    <small class="text-muted">Maks. 1MB, JPG/PNG</small>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Surat Penugasan <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('assignment_letter') is-invalid @enderror"
                                        id="assignment_letter" name="assignment_letter" accept=".pdf" required>
                                    <small class="text-muted">Maks. 1MB, PDF</small>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 5: Pembayaran -->
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-circle bg-gradient-teal me-3" style="width: 40px; height: 40px;">
                                    <i class="fas fa-credit-card text-white"></i>
                                </div>
                                <h5 class="fw-bold mb-0">Pembayaran</h5>
                            </div>
                            
                            @if($role === 'Leader')
                            <div class="card border-0 shadow-sm p-4">
                                <label class="form-label fw-medium">Bukti Transfer <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('payment_proof') is-invalid @enderror"
                                    id="payment_proof" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" required>
                                <small class="text-muted mt-2">Maks 2MB. Format: JPG, PNG, PDF</small>
                            </div>
                            @else
                            <div class="card border-0 shadow-sm p-4 text-center">
                                <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                                <h6 class="fw-bold mb-2">Biaya Pendaftaran sudah dibayar oleh Leader Tim</h6>
                                <p class="text-muted mb-0">Anda tidak perlu upload bukti pembayaran</p>
                            </div>
                            @endif
                        </div>

                        <!-- Terms Agreement -->
                        <div class="form-check mb-4">
                            <input class="form-check-input @error('terms') is-invalid @enderror"
                                type="checkbox" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                Saya menyetujui Syarat & Ketentuan dan memastikan semua data yang diisi adalah benar.
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
                                        @if($role === 'Leader')
                                        <i class="fas fa-crown me-2"></i>Daftar sebagai Leader
                                        @else
                                        <i class="fas fa-paper-plane me-2"></i>Kirim Pendaftaran
                                        @endif
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
    
    .form-control, .form-select {
        border: 1.5px solid #e9ecef;
        border-radius: 12px;
        padding: 0.75rem 1rem;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
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
    
    .alert {
        border-radius: 12px;
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
        
        .d-flex.justify-content-between > div {
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
    const form = document.getElementById('dancerForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    const nikInput = document.getElementById('nik');
    const emailInput = document.getElementById('email');
    const birthdateInput = document.getElementById('birthdate');
    const sttbYearInput = document.getElementById('sttb_year');
    const phoneInput = document.getElementById('phone');
    const fatherPhone = document.getElementById('father_phone');
    const motherPhone = document.getElementById('mother_phone');
    
    // Set max birthdate untuk usia minimal 10 tahun
    const today = new Date();
    const minBirthDate = new Date();
    minBirthDate.setFullYear(today.getFullYear() - 10);
    birthdateInput.max = minBirthDate.toISOString().split('T')[0];
    
    // Validasi STTB tahun (antara tahun lahir + 16 sampai tahun sekarang)
    birthdateInput.addEventListener('change', function() {
        if (this.value) {
            const birthYear = new Date(this.value).getFullYear();
            const minSttbYear = birthYear + 16;
            const currentYear = new Date().getFullYear();
            
            sttbYearInput.min = minSttbYear;
            sttbYearInput.max = currentYear;
            
            if (sttbYearInput.value && (sttbYearInput.value < minSttbYear || sttbYearInput.value > currentYear)) {
                alert(`Tahun STTB harus antara ${minSttbYear} - ${currentYear}`);
                sttbYearInput.value = '';
            }
        }
    });

    // NIK validation
    nikInput.addEventListener('blur', function() {
        if (this.value.length !== 16 && this.value.length > 0) {
            alert('NIK harus 16 digit');
            this.focus();
        }
    });

    // Phone validation
    function validatePhone(input, fieldName) {
        const phone = input.value.replace(/[^0-9]/g, '');
        if (phone.length > 0 && phone.length < 10) {
            alert(`${fieldName} minimal 10 digit`);
            input.focus();
            return false;
        }
        return true;
    }

    phoneInput.addEventListener('blur', function() {
        validatePhone(this, 'Nomor WhatsApp');
    });

    fatherPhone.addEventListener('blur', function() {
        if (this.value) validatePhone(this, 'Nomor telepon ayah');
    });

    motherPhone.addEventListener('blur', function() {
        if (this.value) validatePhone(this, 'Nomor telepon ibu');
    });

    // File size validation
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            let maxSize = 2 * 1024 * 1024;
            if (this.name === 'payment_proof') {
                maxSize = 2 * 1024 * 1024;
            } else {
                maxSize = 1 * 1024 * 1024;
            }
            
            if (file.size > maxSize) {
                alert(`File terlalu besar! Maksimal ${maxSize / (1024 * 1024)}MB`);
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
        // 1. Cek semua input text/select yang required
        const requiredInputs = [
            { id: 'nik', name: 'NIK' },
            { id: 'name', name: 'Nama Lengkap' },
            { id: 'birthdate', name: 'Tanggal Lahir' },
            { id: 'gender', name: 'Jenis Kelamin' },
            { id: 'phone', name: 'WhatsApp' },
            { id: 'email', name: 'Email' },
            { id: 'grade', name: 'Kelas' },
            { id: 'sttb_year', name: 'Tahun STTB' },
            { id: 'instagram', name: 'Instagram' },
            { id: 'tiktok', name: 'TikTok' },
            { id: 'height', name: 'Tinggi Badan' },
            { id: 'weight', name: 'Berat Badan' },
            { id: 'tshirt_size', name: 'Ukuran Kaos' },
            { id: 'shoes_size', name: 'Ukuran Sepatu' },
            { id: 'father_name', name: 'Nama Ayah' },
            { id: 'father_phone', name: 'No. Telepon Ayah' },
            { id: 'mother_name', name: 'Nama Ibu' },
            { id: 'mother_phone', name: 'No. Telepon Ibu' }
        ];

        for (const field of requiredInputs) {
            const element = document.getElementById(field.id);
            if (!element || !element.value.trim()) {
                alert(`Harap isi ${field.name}`);
                element?.focus();
                return false;
            }
        }

        // 2. Validasi khusus NIK harus 16 digit
        const nik = document.getElementById('nik').value;
        if (nik.length !== 16) {
            alert('NIK harus 16 digit');
            document.getElementById('nik').focus();
            return false;
        }

        // 3. Validasi email format
        const email = document.getElementById('email').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Format email tidak valid');
            document.getElementById('email').focus();
            return false;
        }

        // 4. Validasi phone minimal 10 digit
        const phone = document.getElementById('phone').value.replace(/[^0-9]/g, '');
        if (phone.length < 10) {
            alert('Nomor WhatsApp minimal 10 digit');
            document.getElementById('phone').focus();
            return false;
        }

        // 5. Validasi STTB tahun
        const sttbYear = document.getElementById('sttb_year').value;
        const birthYear = new Date(document.getElementById('birthdate').value).getFullYear();
        const minSttbYear = birthYear + 16;
        const currentYear = new Date().getFullYear();
        
        if (sttbYear < minSttbYear || sttbYear > currentYear) {
            alert(`Tahun STTB harus antara ${minSttbYear} - ${currentYear}`);
            document.getElementById('sttb_year').focus();
            return false;
        }

        // 6. Validasi nomor telepon orang tua (jika diisi)
        const fatherPhoneVal = document.getElementById('father_phone').value.replace(/[^0-9]/g, '');
        if (fatherPhoneVal && fatherPhoneVal.length < 10) {
            alert('Nomor telepon ayah minimal 10 digit');
            document.getElementById('father_phone').focus();
            return false;
        }

        const motherPhoneVal = document.getElementById('mother_phone').value.replace(/[^0-9]/g, '');
        if (motherPhoneVal && motherPhoneVal.length < 10) {
            alert('Nomor telepon ibu minimal 10 digit');
            document.getElementById('mother_phone').focus();
            return false;
        }

        // 7. Cek file upload (kecuali payment proof untuk member)
        const fileInputs = [
            { id: 'birth_certificate', name: 'Akta Kelahiran' },
            { id: 'kk', name: 'Kartu Keluarga' },
            { id: 'shun', name: 'SHUN' },
            { id: 'report_identity', name: 'Laporan Identitas' },
            { id: 'last_report_card', name: 'Raport Terakhir' },
            { id: 'formal_photo', name: 'Foto Formal' },
            { id: 'assignment_letter', name: 'Surat Penugasan' }
        ];

        @if($role === 'Leader')
        fileInputs.push({ id: 'payment_proof', name: 'Bukti Transfer' });
        @endif

        for (const file of fileInputs) {
            const element = document.getElementById(file.id);
            if (!element.files || element.files.length === 0) {
                alert(`Upload ${file.name}`);
                element.focus();
                return false;
            }
        }

        // 8. Cek terms checkbox
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