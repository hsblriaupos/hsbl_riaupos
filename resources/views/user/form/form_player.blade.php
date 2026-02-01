@extends('user.form.layout')

@section('title', 'Form Pendaftaran Pemain - HSBL')

@section('content')
<div class="container py-3">
    <!-- Role Indicator -->
    @if($role === 'Leader')
    <div class="alert alert-warning mb-3 py-2">
        <div class="d-flex align-items-center">
            <i class="fas fa-crown me-2"></i>
            <div>
                <h6 class="mb-1 fw-bold">🏆 Anda adalah Leader Tim!</h6>
                <p class="mb-0 small">Anda bertanggung jawab untuk pembayaran tim.</p>
            </div>
        </div>
    </div>
    @else
    <div class="alert alert-info mb-3 py-2">
        <div class="d-flex align-items-center">
            <i class="fas fa-users me-2"></i>
            <div>
                <h6 class="mb-1 fw-bold">🤝 Anda adalah Member Tim</h6>
                <p class="mb-0 small">Anda bergabung dengan tim menggunakan referral code.</p>
            </div>
        </div>
    @endif

    <!-- Main Form Card -->
    <div class="card shadow-sm mx-auto" style="max-width: 800px;">
        <!-- Card Header -->
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1 fw-bold">
                        @if($role === 'Leader')
                        <i class="fas fa-crown me-2 text-warning"></i>Form Leader
                        @else
                        <i class="fas fa-user me-2 text-primary"></i>Form Pemain
                        @endif
                        {{ ucfirst($category) }}
                    </h5>
                    <p class="text-muted mb-0 small">
                        {{ $team->school_name }} | 
                        @php
                            $displayCategory = $team->team_category;
                            if (str_contains(strtolower($displayCategory), 'basket')) {
                                $displayCategory = 'Basket ' . ucfirst($category);
                            }
                        @endphp
                        {{ $displayCategory }}
                    </p>
                </div>
                <a href="{{ route('form.team.create') }}" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Form Content -->
        <div class="card-body p-3">
            <form id="playerForm" action="{{ route('form.player.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Hidden Fields -->
                <input type="hidden" name="team_id" value="{{ $team->team_id }}">
                <input type="hidden" name="category" value="{{ $category }}">
                <input type="hidden" name="team_role" value="{{ $role }}">

                <!-- Section 1: Data Pribadi (3 Rows) -->
                <div class="mb-3">
                    <h6 class="mb-2 pb-1 border-bottom fw-bold small">
                        <i class="fas fa-id-card me-1"></i>Data Pribadi
                    </h6>

                    <div class="row g-2 mb-2">
                        <!-- Row 1 -->
                        <div class="col-md-4">
                            <label for="nik" class="form-label small fw-medium mb-1">
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
                        </div>

                        <div class="col-md-4">
                            <label for="name" class="form-label small fw-medium mb-1">
                                Nama Lengkap <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control form-control-sm @error('name') is-invalid @enderror"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                placeholder="Nama lengkap">
                        </div>

                        <div class="col-md-4">
                            <label for="birthdate" class="form-label small fw-medium mb-1">
                                Tgl Lahir <span class="text-danger">*</span>
                            </label>
                            <input type="date"
                                class="form-control form-control-sm @error('birthdate') is-invalid @enderror"
                                id="birthdate"
                                name="birthdate"
                                value="{{ old('birthdate') }}"
                                required
                                max="{{ date('Y-m-d', strtotime('-10 years')) }}">
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <!-- Row 2 -->
                        <div class="col-md-4">
                            <label for="gender" class="form-label small fw-medium mb-1">
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

                        <div class="col-md-4">
                            <label for="phone" class="form-label small fw-medium mb-1">
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
                        </div>

                        <div class="col-md-4">
                            <label for="email" class="form-label small fw-medium mb-1">
                                Email <span class="text-danger">*</span>
                            </label>
                            <input type="email"
                                class="form-control form-control-sm @error('email') is-invalid @enderror"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                placeholder="email@example.com">
                        </div>
                    </div>

                    <div class="row g-2">
                        <!-- Row 3 -->
                        <div class="col-md-6">
                            <label for="school" class="form-label small fw-medium mb-1">
                                Sekolah <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control form-control-sm bg-light"
                                id="school"
                                value="{{ $team->school_name }}"
                                readonly>
                            <input type="hidden" name="school_name" value="{{ $team->school_name }}">
                        </div>

                        <div class="col-md-3">
                            <label for="grade" class="form-label small fw-medium mb-1">
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

                        <div class="col-md-3">
                            <label for="sttb_year" class="form-label small fw-medium mb-1">
                                Tahun STTB <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                class="form-control form-control-sm @error('sttb_year') is-invalid @enderror"
                                id="sttb_year"
                                name="sttb_year"
                                value="{{ old('sttb_year') }}"
                                required
                                placeholder="2024"
                                maxlength="4"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                    </div>
                </div>

                <!-- Section 2: Data Fisik (3 Rows) -->
                <div class="mb-3">
                    <h6 class="mb-2 pb-1 border-bottom fw-bold small">
                        <i class="fas fa-running me-1"></i>Data Fisik
                    </h6>

                    <div class="row g-2 mb-2">
                        <!-- Row 1 -->
                        <div class="col-md-3">
                            <label for="height" class="form-label small fw-medium mb-1">
                                Tinggi (cm) <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                class="form-control form-control-sm @error('height') is-invalid @enderror"
                                id="height"
                                name="height"
                                value="{{ old('height') }}"
                                required
                                min="100" max="250" step="1"
                                placeholder="170">
                        </div>

                        <div class="col-md-3">
                            <label for="weight" class="form-label small fw-medium mb-1">
                                Berat (kg) <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                class="form-control form-control-sm @error('weight') is-invalid @enderror"
                                id="weight"
                                name="weight"
                                value="{{ old('weight') }}"
                                required
                                min="30" max="150" step="0.5"
                                placeholder="65.5">
                        </div>

                        <div class="col-md-3">
                            <label for="tshirt_size" class="form-label small fw-medium mb-1">
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

                        <div class="col-md-3">
                            <label for="shoes_size" class="form-label small fw-medium mb-1">
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

                    <div class="row g-2">
                        <!-- Row 2 - Basketball Position -->
                        @if($category !== 'dancer')
                        <div class="col-md-6">
                            <label for="basketball_position" class="form-label small fw-medium mb-1">
                                Posisi Basket <span class="text-muted">(Opsional)</span>
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
                        @endif
                    </div>
                </div>

                <!-- Section 3: Orang Tua (2 Rows) -->
                <div class="mb-3">
                    <h6 class="mb-2 pb-1 border-bottom fw-bold small">
                        <i class="fas fa-users me-1"></i>Data Orang Tua
                    </h6>

                    <div class="row g-2 mb-2">
                        <!-- Ayah -->
                        <div class="col-md-6">
                            <div class="row g-1">
                                <div class="col-12">
                                    <label class="form-label small fw-medium mb-1">Ayah</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text"
                                        class="form-control form-control-sm @error('father_name') is-invalid @enderror"
                                        id="father_name"
                                        name="father_name"
                                        value="{{ old('father_name') }}"
                                        placeholder="Nama ayah">
                                </div>
                                <div class="col-md-6">
                                    <input type="tel"
                                        class="form-control form-control-sm @error('father_phone') is-invalid @enderror"
                                        id="father_phone"
                                        name="father_phone"
                                        value="{{ old('father_phone') }}"
                                        placeholder="No. telepon"
                                        oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                </div>
                            </div>
                        </div>

                        <!-- Ibu -->
                        <div class="col-md-6">
                            <div class="row g-1">
                                <div class="col-12">
                                    <label class="form-label small fw-medium mb-1">Ibu</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="text"
                                        class="form-control form-control-sm @error('mother_name') is-invalid @enderror"
                                        id="mother_name"
                                        name="mother_name"
                                        value="{{ old('mother_name') }}"
                                        placeholder="Nama ibu">
                                </div>
                                <div class="col-md-6">
                                    <input type="tel"
                                        class="form-control form-control-sm @error('mother_phone') is-invalid @enderror"
                                        id="mother_phone"
                                        name="mother_phone"
                                        value="{{ old('mother_phone') }}"
                                        placeholder="No. telepon"
                                        oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 4: Dokumen dengan Grid Card -->
                <div class="mb-3">
                    <h6 class="mb-2 pb-1 border-bottom fw-bold small">
                        <i class="fas fa-file-upload me-1"></i>Dokumen
                    </h6>

                    <!-- Dokumen Wajib - Grid 2x2 -->
                    <div class="mb-3">
                        <h6 class="mb-2 small fw-bold text-success">Dokumen Wajib (PDF)</h6>
                        <div class="row g-2">
                            <!-- Row 1 -->
                            <div class="col-md-6">
                                <div class="card border h-100">
                                    <div class="card-body p-2">
                                        <label for="birth_certificate" class="form-label small fw-medium mb-1">
                                            Akta Kelahiran <span class="text-danger">*</span>
                                        </label>
                                        <input type="file"
                                            class="form-control form-control-sm @error('birth_certificate') is-invalid @enderror"
                                            id="birth_certificate"
                                            name="birth_certificate"
                                            accept=".pdf"
                                            required>
                                        <div class="form-text small">PDF, maks. 1MB</div>
                                        <div class="preview-container mt-1" id="birth_preview" style="display: none;">
                                            <small class="text-muted">Preview:</small>
                                            <div class="preview-box small bg-light p-1 rounded mt-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border h-100">
                                    <div class="card-body p-2">
                                        <label for="kk" class="form-label small fw-medium mb-1">
                                            Kartu Keluarga (KK) <span class="text-danger">*</span>
                                        </label>
                                        <input type="file"
                                            class="form-control form-control-sm @error('kk') is-invalid @enderror"
                                            id="kk"
                                            name="kk"
                                            accept=".pdf"
                                            required>
                                        <div class="form-text small">PDF, maks. 1MB</div>
                                        <div class="preview-container mt-1" id="kk_preview" style="display: none;">
                                            <small class="text-muted">Preview:</small>
                                            <div class="preview-box small bg-light p-1 rounded mt-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Row 2 -->
                            <div class="col-md-6">
                                <div class="card border h-100">
                                    <div class="card-body p-2">
                                        <label for="shun" class="form-label small fw-medium mb-1">
                                            SHUN <span class="text-danger">*</span>
                                        </label>
                                        <input type="file"
                                            class="form-control form-control-sm @error('shun') is-invalid @enderror"
                                            id="shun"
                                            name="shun"
                                            accept=".pdf"
                                            required>
                                        <div class="form-text small">Surat Hasil Ujian Nasional</div>
                                        <div class="preview-container mt-1" id="shun_preview" style="display: none;">
                                            <small class="text-muted">Preview:</small>
                                            <div class="preview-box small bg-light p-1 rounded mt-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border h-100">
                                    <div class="card-body p-2">
                                        <label for="report_identity" class="form-label small fw-medium mb-1">
                                            Laporan Identitas <span class="text-danger">*</span>
                                        </label>
                                        <input type="file"
                                            class="form-control form-control-sm @error('report_identity') is-invalid @enderror"
                                            id="report_identity"
                                            name="report_identity"
                                            accept=".pdf"
                                            required>
                                        <div class="form-text small">PDF, maks. 1MB</div>
                                        <div class="preview-container mt-1" id="report_preview" style="display: none;">
                                            <small class="text-muted">Preview:</small>
                                            <div class="preview-box small bg-light p-1 rounded mt-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dokumen Tambahan - Grid 2x1 -->
                    <div class="mb-3">
                        <h6 class="mb-2 small fw-bold text-primary">Dokumen Tambahan</h6>
                        <div class="row g-2">
                            <div class="col-md-6">
                                <div class="card border h-100">
                                    <div class="card-body p-2">
                                        <label for="last_report_card" class="form-label small fw-medium mb-1">
                                            Raport Terakhir <span class="text-danger">*</span>
                                        </label>
                                        <input type="file"
                                            class="form-control form-control-sm @error('last_report_card') is-invalid @enderror"
                                            id="last_report_card"
                                            name="last_report_card"
                                            accept=".pdf"
                                            required>
                                        <div class="form-text small">PDF, maks. 1MB</div>
                                        <div class="preview-container mt-1" id="reportcard_preview" style="display: none;">
                                            <small class="text-muted">Preview:</small>
                                            <div class="preview-box small bg-light p-1 rounded mt-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border h-100">
                                    <div class="card-body p-2">
                                        <label for="formal_photo" class="form-label small fw-medium mb-1">
                                            Foto Formal <span class="text-danger">*</span>
                                        </label>
                                        <input type="file"
                                            class="form-control form-control-sm @error('formal_photo') is-invalid @enderror"
                                            id="formal_photo"
                                            name="formal_photo"
                                            accept=".jpg,.jpeg,.png"
                                            required>
                                        <div class="form-text small">JPG/PNG, maks. 1MB</div>
                                        <div class="preview-container mt-1" id="photo_preview" style="display: none;">
                                            <small class="text-muted">Preview:</small>
                                            <img class="preview-img mt-1 rounded" style="max-width: 60px; max-height: 60px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Assignment Letter -->
                        <div class="row g-2 mt-2">
                            <div class="col-md-6">
                                <div class="card border h-100">
                                    <div class="card-body p-2">
                                        <label for="assignment_letter" class="form-label small fw-medium mb-1">
                                            Surat Penugasan <span class="text-muted">(Opsional)</span>
                                        </label>
                                        <input type="file"
                                            class="form-control form-control-sm @error('assignment_letter') is-invalid @enderror"
                                            id="assignment_letter"
                                            name="assignment_letter"
                                            accept=".pdf">
                                        <div class="form-text small">PDF, maks. 1MB</div>
                                        <div class="preview-container mt-1" id="assignment_preview" style="display: none;">
                                            <small class="text-muted">Preview:</small>
                                            <div class="preview-box small bg-light p-1 rounded mt-1"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- PAYMENT PROOF (HANYA UNTUK LEADER) -->
                            @if($role === 'Leader')
                            <div class="col-md-6">
                                <div class="card border-warning border h-100">
                                    <div class="card-body p-2">
                                        <h6 class="mb-1 small fw-bold text-warning">
                                            <i class="fas fa-money-bill-wave me-1"></i>Bukti Pembayaran
                                            <span class="badge bg-danger ms-1">WAJIB</span>
                                        </h6>
                                        
                                        <div class="alert alert-warning small mb-1 py-1">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Anda wajib membayar.
                                        </div>

                                        <label for="payment_proof" class="form-label small fw-medium mb-1">
                                            Upload Bukti <span class="text-danger">*</span>
                                        </label>
                                        <input type="file"
                                            class="form-control form-control-sm @error('payment_proof') is-invalid @enderror"
                                            id="payment_proof"
                                            name="payment_proof"
                                            accept=".jpg,.jpeg,.png,.pdf"
                                            required>
                                        <div class="form-text small">JPG/PNG/PDF, maks. 2MB</div>
                                        <div class="preview-container mt-1" id="payment_preview" style="display: none;">
                                            <small class="text-muted">Preview:</small>
                                            <div class="preview-box small bg-light p-1 rounded mt-1" id="payment_text_preview"></div>
                                            <img class="preview-img mt-1 rounded d-none" style="max-width: 60px; max-height: 60px;">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <div class="col-md-6">
                                <div class="card border-success border h-100">
                                    <div class="card-body p-2">
                                        <div class="alert alert-success small p-2">
                                            <div class="d-flex">
                                                <i class="fas fa-check-circle me-2"></i>
                                                <div>
                                                    <div class="fw-medium">✅ Biaya Sudah Dibayar</div>
                                                    <div class="small">Anda bergabung sebagai anggota tim.</div>
                                                </div>
                                            </div>
                                        </div>
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
                        type="checkbox"
                        id="terms"
                        name="terms"
                        required>
                    <label class="form-check-label small" for="terms">
                        Saya menyetujui Syarat & Ketentuan dan memastikan data benar.
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="border-top pt-3 mt-3">
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('form.team.create') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                        <button type="submit" class="btn btn-primary btn-sm px-3">
                            @if($role === 'Leader')
                            <i class="fas fa-crown me-1"></i>Daftar sebagai Leader
                            @else
                            <i class="fas fa-paper-plane me-1"></i>Kirim Pendaftaran
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
        // Setup preview for all file inputs
        const fileInputs = [
            { id: 'birth_certificate', previewId: 'birth_preview' },
            { id: 'kk', previewId: 'kk_preview' },
            { id: 'shun', previewId: 'shun_preview' },
            { id: 'report_identity', previewId: 'report_preview' },
            { id: 'last_report_card', previewId: 'reportcard_preview' },
            { id: 'formal_photo', previewId: 'photo_preview', isImage: true },
            { id: 'assignment_letter', previewId: 'assignment_preview' },
            { id: 'payment_proof', previewId: 'payment_preview', isPayment: true }
        ];

        fileInputs.forEach(fileConfig => {
            const input = document.getElementById(fileConfig.id);
            const previewContainer = document.getElementById(fileConfig.previewId);
            
            if (input && previewContainer) {
                input.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (!file) {
                        previewContainer.style.display = 'none';
                        return;
                    }

                    // Validate file size
                    let maxSize;
                    if (fileConfig.id === 'payment_proof') {
                        maxSize = 2 * 1024 * 1024; // 2MB
                    } else if (file.type.includes('image')) {
                        maxSize = 1 * 1024 * 1024; // 1MB
                    } else {
                        maxSize = 1 * 1024 * 1024; // 1MB for PDF
                    }

                    if (file.size > maxSize) {
                        alert(`File terlalu besar! Maksimal ${maxSize / (1024 * 1024)}MB`);
                        this.value = '';
                        previewContainer.style.display = 'none';
                        return;
                    }

                    // Show preview
                    if (fileConfig.isImage || (fileConfig.isPayment && file.type.includes('image'))) {
                        // Image preview
                        const img = previewContainer.querySelector('.preview-img');
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            img.src = e.target.result;
                            previewContainer.style.display = 'block';
                        };
                        reader.readAsDataURL(file);
                        
                        if (fileConfig.isPayment) {
                            const textPreview = previewContainer.querySelector('#payment_text_preview');
                            textPreview.style.display = 'none';
                            img.classList.remove('d-none');
                        }
                    } else {
                        // Text preview for PDF
                        const previewBox = previewContainer.querySelector('.preview-box');
                        previewBox.textContent = `📄 ${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
                        previewContainer.style.display = 'block';
                        
                        if (fileConfig.isPayment) {
                            const img = previewContainer.querySelector('.preview-img');
                            img.classList.add('d-none');
                            const textPreview = previewContainer.querySelector('#payment_text_preview');
                            textPreview.style.display = 'block';
                            textPreview.textContent = `📄 ${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
                        }
                    }
                });
            }
        });

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
                const role = "{{ $role }}";
                const submitBtn = this.querySelector('button[type="submit"]');
                if (role === 'Leader') {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memproses...';
                } else {
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Mengirim...';
                }
                submitBtn.disabled = true;
            }
        });
    });
</script>

<style>
    .card {
        border-radius: 6px;
        border: 1px solid #ddd;
    }
    
    .card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #ddd;
        padding: 0.75rem 1rem;
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    .form-label {
        font-size: 0.8rem;
        margin-bottom: 0.2rem;
    }
    
    .form-control-sm, .form-select-sm {
        font-size: 0.85rem;
        padding: 0.25rem 0.5rem;
        height: calc(1.5em + 0.5rem + 2px);
    }
    
    .btn-sm {
        font-size: 0.85rem;
        padding: 0.25rem 0.5rem;
    }
    
    .small {
        font-size: 0.8rem;
    }
    
    .alert {
        padding: 0.5rem;
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
    }
    
    h6 {
        font-size: 0.9rem;
    }
    
    h5 {
        font-size: 1rem;
    }
    
    .mb-1 {
        margin-bottom: 0.25rem !important;
    }
    
    .mb-2 {
        margin-bottom: 0.5rem !important;
    }
    
    .mb-3 {
        margin-bottom: 0.75rem !important;
    }
    
    .mt-2 {
        margin-top: 0.5rem !important;
    }
    
    .border-bottom {
        border-bottom-width: 1px !important;
    }
    
    .border-top {
        border-top-width: 1px !important;
    }
    
    .form-text {
        font-size: 0.75rem;
        margin-top: 0.2rem;
    }
    
    .preview-container {
        border-top: 1px solid #eee;
        padding-top: 0.5rem;
    }
    
    .preview-box {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
    }
    
    .badge {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .card-body {
            padding: 0.5rem !important;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .btn-sm {
            width: 100%;
        }
        
        .row > div {
            margin-bottom: 0.5rem;
        }
        
        .col-md-6, .col-md-3, .col-md-4 {
            margin-bottom: 0.5rem;
        }
    }
</style>
@endpush
@endsection