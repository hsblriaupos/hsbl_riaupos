@extends('user.form.layout')

@section('title', 'Form Pendaftaran Pemain - HSBL')

@section('content')
<div class="container py-3 px-lg-4 px-md-3 px-sm-2"> <!-- Tambah padding horizontal -->
    <!-- Role Indicator - Compact -->
    @if($role === 'Leader')
    <div class="alert alert-warning border-warning bg-warning-subtle mb-3 py-2 px-2 shadow-sm mx-auto" style="max-width: 780px;">
        <div class="d-flex align-items-center">
            <div class="bg-warning text-white rounded-circle p-1 me-2" style="width: 30px; height: 30px;">
                <i class="fas fa-crown"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-0 fw-bold small text-dark">Anda adalah Leader Tim!</h6>
                <p class="mb-0 text-muted" style="font-size: 0.75rem;">Bertanggung jawab untuk pembayaran tim</p>
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
                <h6 class="mb-0 fw-bold small text-dark">Anda adalah Member Tim</h6>
                <p class="mb-0 text-muted" style="font-size: 0.75rem;">Bergabung dengan referral code</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Form Card - Tambah sedikit lebar -->
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 800px; border-radius: 8px; width: 100%;">
        <!-- Card Header - Compact -->
        <div class="card-header bg-gradient-primary text-white py-2 px-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div class="flex-grow-1">
                    <h5 class="mb-0 fw-bold small">
                        @if($role === 'Leader')
                        <i class="fas fa-crown me-1"></i>Form Leader {{ ucfirst($category) }}
                        @else
                        <i class="fas fa-user me-1"></i>Form Pemain {{ ucfirst($category) }}
                        @endif
                    </h5>
                    <p class="mb-0 opacity-75" style="font-size: 0.7rem;">
                        {{ $team->school_name }} • 
                        @php
                            $displayCategory = $team->team_category;
                            if (str_contains(strtolower($displayCategory), 'basket')) {
                                $displayCategory = 'Basket ' . ucfirst($category);
                            }
                        @endphp
                        {{ $displayCategory }}
                    </p>
                </div>
                <a href="{{ route('form.team.create') }}" class="btn btn-light btn-sm px-2 rounded-pill">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>
        </div>

        <!-- Form Content - Compact dengan sedikit lebih lega -->
        <div class="card-body p-3 px-md-3 px-sm-2">
            <form id="playerForm" action="{{ route('form.player.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team->team_id }}">
                <input type="hidden" name="category" value="{{ $category }}">
                <input type="hidden" name="team_role" value="{{ $role }}">

                <!-- Section 1: Data Pribadi dengan sedikit spacing -->
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon-wrapper bg-primary bg-opacity-10 p-1 rounded me-2" style="width: 28px; height: 28px;">
                            <i class="fas fa-id-card text-primary" style="font-size: 0.8rem;"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark small">Data Pribadi</h6>
                    </div>
                    
                    <!-- Row 1: 4 Kolom Compact dengan sedikit gap -->
                    <div class="row gx-1 gy-1 mb-2">
                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                NIK <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-sm @error('nik') is-invalid @enderror"
                                id="nik" name="nik" value="{{ old('nik') }}" required
                                placeholder="16 digit" maxlength="16"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Nama <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}" required
                                placeholder="Nama lengkap">
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Tgl Lahir <span class="text-danger">*</span>
                            </label>
                            <input type="date" class="form-control form-control-sm @error('birthdate') is-invalid @enderror"
                                id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required
                                max="{{ date('Y-m-d', strtotime('-10 years')) }}">
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Jenis Kelamin <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm @error('gender') is-invalid @enderror"
                                id="gender" name="gender" required>
                                <option value="">Pilih</option>
                                @foreach($genderOptions as $gender)
                                <option value="{{ $gender }}" {{ old('gender') == $gender ? 'selected' : '' }}>
                                    {{ $gender }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Row 2: 4 Kolom Compact -->
                    <div class="row gx-1 gy-1 mb-2">
                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                WhatsApp <span class="text-danger">*</span>
                            </label>
                            <input type="tel" class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                id="phone" name="phone" value="{{ old('phone') }}" required
                                placeholder="081234567890"
                                oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror"
                                id="email" name="email" value="{{ old('email') }}" required
                                placeholder="email@example.com">
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Sekolah <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-sm bg-light-subtle"
                                value="{{ $team->school_name }}" readonly>
                            <input type="hidden" name="school_name" value="{{ $team->school_name }}">
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Kelas <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm @error('grade') is-invalid @enderror"
                                id="grade" name="grade" required>
                                <option value="">Pilih</option>
                                @foreach($grades as $grade)
                                <option value="{{ $grade }}" {{ old('grade') == $grade ? 'selected' : '' }}>
                                    {{ $grade }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Row 3: STTB Tahun -->
                    <div class="row gx-1 gy-1">
                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Tahun STTB <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-sm @error('sttb_year') is-invalid @enderror"
                                id="sttb_year" name="sttb_year" value="{{ old('sttb_year') }}" required
                                placeholder="2024" maxlength="4"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                Tinggi (cm) <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control form-control-sm @error('height') is-invalid @enderror"
                                id="height" name="height" value="{{ old('height') }}" required
                                min="100" max="250" step="1" placeholder="170">
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Berat (kg) <span class="text-danger">*</span>
                            </label>
                            <input type="number" class="form-control form-control-sm @error('weight') is-invalid @enderror"
                                id="weight" name="weight" value="{{ old('weight') }}" required
                                min="30" max="150" step="0.5" placeholder="65.5">
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Uk. Kaos <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm @error('tshirt_size') is-invalid @enderror"
                                id="tshirt_size" name="tshirt_size" required>
                                <option value="">Pilih</option>
                                @foreach($tshirtSizes as $size)
                                <option value="{{ $size }}" {{ old('tshirt_size') == $size ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Uk. Sepatu <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm @error('shoes_size') is-invalid @enderror"
                                id="shoes_size" name="shoes_size" required>
                                <option value="">Pilih</option>
                                @foreach($shoesSizes as $size)
                                <option value="{{ $size }}" {{ old('shoes_size') == $size ? 'selected' : '' }}>
                                    {{ $size }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if($category !== 'dancer')
                    <div class="row gx-1 gy-1">
                        <div class="col-md-4 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Posisi Basket <small class="text-muted">(Opsional)</small>
                            </label>
                            <select class="form-select form-select-sm @error('basketball_position') is-invalid @enderror"
                                id="basketball_position" name="basketball_position">
                                <option value="">Pilih Posisi</option>
                                @foreach($basketballPositions as $position)
                                <option value="{{ $position }}" {{ old('basketball_position') == $position ? 'selected' : '' }}>
                                    {{ $position }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Section 3: Data Orang Tua - Compact -->
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon-wrapper bg-info bg-opacity-10 p-1 rounded me-2" style="width: 28px; height: 28px;">
                            <i class="fas fa-users text-info" style="font-size: 0.8rem;"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark small">Data Orang Tua</h6>
                    </div>
                    
                    <div class="row gx-1 gy-1">
                        <div class="col-md-6 mb-1">
                            <div class="card border h-100 p-2">
                                <label class="form-label fw-semibold small mb-1">Ayah</label>
                                <div class="row gx-1 gy-1">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control form-control-sm @error('father_name') is-invalid @enderror"
                                            name="father_name" value="{{ old('father_name') }}" placeholder="Nama ayah">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="tel" class="form-control form-control-sm @error('father_phone') is-invalid @enderror"
                                            name="father_phone" value="{{ old('father_phone') }}" placeholder="No. telepon"
                                            oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-1">
                            <div class="card border h-100 p-2">
                                <label class="form-label fw-semibold small mb-1">Ibu</label>
                                <div class="row gx-1 gy-1">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control form-control-sm @error('mother_name') is-invalid @enderror"
                                            name="mother_name" value="{{ old('mother_name') }}" placeholder="Nama ibu">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="tel" class="form-control form-control-sm @error('mother_phone') is-invalid @enderror"
                                            name="mother_phone" value="{{ old('mother_phone') }}" placeholder="No. telepon"
                                            oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Dokumen - Compact -->
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon-wrapper bg-danger bg-opacity-10 p-1 rounded me-2" style="width: 28px; height: 28px;">
                            <i class="fas fa-file-upload text-danger" style="font-size: 0.8rem;"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark small">Upload Dokumen</h6>
                    </div>
                    
                    <!-- Dokumen Wajib -->
                    <div class="mb-3">
                        <h6 class="fw-bold mb-2 small text-success">
                            Dokumen Wajib <span class="badge bg-success ms-1 small">WAJIB</span>
                        </h6>
                        
                        <div class="row gx-1 gy-1 mb-2">
                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-semibold small mb-1">
                                    Akta Kelahiran <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control form-control-sm @error('birth_certificate') is-invalid @enderror"
                                    name="birth_certificate" accept=".pdf" required>
                            </div>

                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-semibold small mb-1">
                                    Kartu Keluarga <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control form-control-sm @error('kk') is-invalid @enderror"
                                    name="kk" accept=".pdf" required>
                            </div>

                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-semibold small mb-1">
                                    SHUN <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control form-control-sm @error('shun') is-invalid @enderror"
                                    name="shun" accept=".pdf" required>
                            </div>

                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-semibold small mb-1">
                                    Laporan Identitas <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control form-control-sm @error('report_identity') is-invalid @enderror"
                                    name="report_identity" accept=".pdf" required>
                            </div>
                        </div>
                    </div>

                    <!-- Dokumen Tambahan -->
                    <div class="mb-3">
                        <h6 class="fw-bold mb-2 small text-primary">Dokumen Tambahan</h6>
                        
                        <div class="row gx-1 gy-1 mb-2">
                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-semibold small mb-1">
                                    Raport Terakhir <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control form-control-sm @error('last_report_card') is-invalid @enderror"
                                    name="last_report_card" accept=".pdf" required>
                            </div>

                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-semibold small mb-1">
                                    Foto Formal <span class="text-danger">*</span>
                                </label>
                                <input type="file" class="form-control form-control-sm @error('formal_photo') is-invalid @enderror"
                                    name="formal_photo" accept=".jpg,.jpeg,.png" required>
                            </div>

                            <div class="col-md-6 mb-1">
                                <label class="form-label fw-semibold small mb-1">
                                    Surat Penugasan <small class="text-muted">(Opsional)</small>
                                </label>
                                <input type="file" class="form-control form-control-sm @error('assignment_letter') is-invalid @enderror"
                                    name="assignment_letter" accept=".pdf">
                            </div>

                            @if($role === 'Leader')
                            <div class="col-md-6 mb-1">
                                <div class="card border-warning h-100 p-2">
                                    <h6 class="fw-bold text-warning mb-1 small">
                                        Bukti Pembayaran <span class="badge bg-danger ms-1 small">WAJIB</span>
                                    </h6>
                                    <label class="form-label fw-semibold small mb-1">
                                        Upload Bukti <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control form-control-sm @error('payment_proof') is-invalid @enderror"
                                        name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" required>
                                </div>
                            </div>
                            @else
                            <div class="col-md-6 mb-1">
                                <div class="card border-success h-100 p-2">
                                    <div class="text-center p-1">
                                        <i class="fas fa-check-circle text-success"></i>
                                        <p class="mb-0 small fw-bold">Biaya Sudah Dibayar</p>
                                    </div>
                                </div>
                            </div>
                            @endif
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
                </div>

                <!-- Submit Button -->
                <div class="border-top pt-2 mt-2">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('form.team.create') }}" class="btn btn-outline-secondary btn-sm px-3">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm px-3">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // NIK validation
    const nikInput = document.getElementById('nik');
    if (nikInput) {
        nikInput.addEventListener('blur', function() {
            if (this.value.length !== 16 && this.value.length > 0) {
                alert('NIK harus 16 digit');
                this.focus();
            }
        });
    }

    // File size validation
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            let maxSize = this.name === 'payment_proof' ? 2 * 1024 * 1024 : 1 * 1024 * 1024;
            
            if (file.size > maxSize) {
                alert(`File terlalu besar! Maksimal ${maxSize / (1024 * 1024)}MB`);
                this.value = '';
            }
        });
    });

    // Form validation
    const form = document.getElementById('playerForm');
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const requiredInputs = this.querySelectorAll('[required]');
        
        requiredInputs.forEach(input => {
            if (!input.value.trim() && input.type !== 'file' && input.type !== 'checkbox') {
                isValid = false;
                input.classList.add('is-invalid');
            } else if (input.type === 'checkbox' && !input.checked) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Harap lengkapi semua data yang wajib diisi.');
        } else {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memproses...';
            submitBtn.disabled = true;
        }
    });
});
</script>

<style>
/* Lebih lega sedikit di kanan kiri */
.container { 
    padding-top: 0.5rem !important;
    padding-left: 0.75rem !important;
    padding-right: 0.75rem !important;
}

/* Card lebih lebar sedikit */
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

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

/* Form controls sedikit lebih lega */
.form-control-sm, .form-select-sm {
    padding: 0.3rem 0.6rem !important;
    font-size: 0.8rem !important;
    height: calc(1.5em + 0.6rem + 2px) !important;
    border-radius: 4px !important;
}

/* Labels sedikit lebih lega */
.form-label {
    font-size: 0.75rem !important;
    margin-bottom: 0.15rem !important;
    font-weight: 600;
}

/* Spacing sedikit lebih longgar */
.mb-1 { margin-bottom: 0.3rem !important; }
.mb-2 { margin-bottom: 0.6rem !important; }
.mb-3 { margin-bottom: 1rem !important; }
.p-2 { padding: 0.5rem !important; }
.p-3 { padding: 0.8rem !important; }

/* Grid dengan ruang sedikit lebih banyak */
.row.gx-1 {
    --bs-gutter-x: 0.4rem;
}
.row.gy-1 {
    --bs-gutter-y: 0.4rem;
}

/* Alert sedikit lebih lebar */
.alert {
    padding: 0.6rem !important;
    margin-bottom: 0.8rem !important;
    font-size: 0.8rem !important;
}

/* Button sedikit lebih lebar */
.btn-sm {
    padding: 0.3rem 0.8rem !important;
    font-size: 0.8rem !important;
    border-radius: 4px !important;
}

/* Card body dengan sedikit lebih banyak ruang */
.card-body {
    padding: 1rem !important;
}

@media (min-width: 768px) {
    .card-body {
        padding: 1.25rem !important;
    }
}

/* Badge sedikit lebih besar */
.badge.small {
    font-size: 0.7rem !important;
    padding: 0.2rem 0.4rem !important;
}

/* Card dalam card sedikit lebih banyak ruang */
.card .card {
    padding: 0.5rem !important;
}

/* Responsive - lebih lega di desktop */
@media (min-width: 992px) {
    .container {
        padding-left: 1.5rem !important;
        padding-right: 1.5rem !important;
    }
    
    .card {
        max-width: 820px !important;
    }
}

/* Responsive - mobile masih compact tapi dengan sedikit breathing room */
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
}

/* Custom Scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #667eea;
    border-radius: 3px;
}

/* Focus states */
.form-control:focus, .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    outline: none;
}

/* File input styling */
input[type="file"] {
    font-size: 0.75rem !important;
}

/* Checkbox sizing */
.form-check-input {
    width: 0.9em;
    height: 0.9em;
    margin-top: 0.15em;
}

.form-check-label {
    font-size: 0.8rem !important;
}

/* Section headers */
h6.small {
    font-size: 0.85rem !important;
    font-weight: 600;
}

/* Card hover effect minimal */
.card:hover {
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: box-shadow 0.2s ease;
}

/* Border top dengan sedikit lebih banyak ruang */
.border-top {
    border-top: 1px solid #dee2e6 !important;
    padding-top: 0.75rem !important;
    margin-top: 0.75rem !important;
}

/* Container max-width untuk sangat besar screen */
@media (min-width: 1400px) {
    .container {
        max-width: 900px;
        margin: 0 auto;
    }
}
</style>
@endpush
@endsection