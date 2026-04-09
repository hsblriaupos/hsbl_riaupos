@extends('user.form.layout')

@section('title', 'Form Pendaftaran Pemain - SBL')

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
                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2"
                    style="width: 36px; height: 36px; font-size: 1rem; font-weight: 500;">
                    2
                </div>
                <div class="small fw-semibold text-primary">Data Pemain</div>
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
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 1000px; border-radius: 20px;">
        <!-- Card Header -->
        <div class="card-header bg-white border-0 pt-4 px-4">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="fw-semibold mb-1 text-primary">
                        <i class="fas {{ $role === 'Leader' ? 'fa-crown' : 'fa-user-plus' }} me-2"></i>
                        @if($role === 'Leader')
                            Form Pendaftaran Kapten
                        @else
                            Form Pendaftaran Pemain
                        @endif
                    </h5>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-school me-1"></i>{{ $team->school_name }}
                        <span class="mx-1">•</span>
                        <i class="fas fa-basketball me-1"></i>{{ $team->team_category }}
                        <span class="mx-1">•</span>
                        <i class="fas fa-tag me-1"></i>{{ ucfirst($category) }}
                    </p>
                </div>
                <div class="col-auto">
                    <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                        <i class="fas fa-arrow-left me-1"></i>Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Alert Errors -->
        @if($errors->any())
        <div class="px-4 pt-3">
            <div class="alert alert-danger alert-dismissible fade show py-2 border-0" role="alert" style="background: rgba(249, 65, 68, 0.1); border-radius: 12px;">
                <div class="d-flex align-items-start">
                    <i class="fas fa-times-circle me-2 mt-1" style="color: #f94144;"></i>
                    <div class="small">
                        <strong>Terdapat {{ $errors->count() }} kesalahan:</strong>
                        <ul class="mb-0 mt-1 ps-3">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button" class="btn-close btn-sm ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
        </div>
        @endif

        <!-- Form Content -->
        <div class="card-body p-4">
            <form id="playerForm" action="{{ route('form.player.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team->team_id }}">
                <input type="hidden" name="category" value="{{ $category }}">
                <input type="hidden" name="team_role" value="{{ $role }}">

                <!-- Role Alert Banner -->
                @if($role === 'Leader')
                <div class="alert mb-4 border-0" style="background: linear-gradient(135deg, rgba(248, 150, 30, 0.1), rgba(248, 150, 30, 0.05)); border-radius: 12px; border-left: 4px solid #f8961e !important;">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-crown text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <span class="fw-bold text-warning">Anda adalah KAPTEN TIM</span>
                            <p class="small text-muted mb-0 mt-1">Sebagai Kapten, Anda bertanggung jawab untuk:<br>
                            • Upload bukti pembayaran registrasi tim<br>
                            • Upload foto jersey tim (minimal 1 foto)<br>
                            • Mendapatkan referral code untuk anggota tim</p>
                        </div>
                    </div>
                </div>
                @else
                <div class="alert mb-4 border-0" style="background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05)); border-radius: 12px; border-left: 4px solid #10b981 !important;">
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="fas fa-users text-success" style="font-size: 1.5rem;"></i>
                        </div>
                        <div>
                            <span class="fw-bold text-success">Anda adalah ANGGOTA TIM</span>
                            <p class="small text-muted mb-0 mt-1">Biaya pendaftaran sudah ditanggung oleh Kapten tim.<br>
                            Anda hanya perlu mengisi data diri dan melengkapi dokumen yang diperlukan.</p>
                        </div>
                    </div>
                </div>
                @endif

                <!-- 2 KOLOM LAYOUT -->
                <div class="row g-4">
                    <!-- KOLOM KIRI -->
                    <div class="col-md-6">
                        <div class="pe-md-3">
                            <!-- Data Pribadi -->
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3 pb-1 border-bottom">
                                    <i class="fas fa-id-card text-primary me-2"></i>Data Pribadi
                                </h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label small fw-medium">NIK <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm @error('nik') is-invalid @enderror"
                                            id="nik" name="nik" value="{{ old('nik') }}" required
                                            placeholder="16 digit angka" maxlength="16">
                                        <div class="invalid-feedback small" id="nikFeedback"></div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-medium">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required
                                            placeholder="Nama lengkap sesuai KTP">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium">Tempat Lahir <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm @error('birth_place') is-invalid @enderror"
                                            id="birth_place" name="birth_place" value="{{ old('birth_place') }}" required
                                            placeholder="Kota lahir">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control form-control-sm @error('birthdate') is-invalid @enderror"
                                            id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm @error('gender') is-invalid @enderror"
                                            id="gender" name="gender" required>
                                            <option value="">Pilih</option>
                                            @foreach($genderOptions as $gender)
                                            <option value="{{ $gender }}" {{ old('gender') == $gender ? 'selected' : '' }}>{{ $gender == 'Male' ? 'Laki-laki' : 'Perempuan' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium">WhatsApp <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone') }}" required
                                            placeholder="081234567890">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-medium">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required
                                            placeholder="email@example.com">
                                        <div class="invalid-feedback small" id="emailFeedback"></div>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium">Kelas <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm @error('grade') is-invalid @enderror"
                                            id="grade" name="grade" required>
                                            <option value="">Pilih</option>
                                            @foreach($grades as $grade)
                                            <option value="{{ $grade }}" {{ old('grade') == $grade ? 'selected' : '' }}>{{ $grade }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium">Tahun STTB <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm @error('sttb_year') is-invalid @enderror"
                                            id="sttb_year" name="sttb_year" value="{{ old('sttb_year') }}" required
                                            placeholder="2024" maxlength="4">
                                    </div>
                                </div>
                            </div>

                            <!-- Data Fisik -->
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3 pb-1 border-bottom">
                                    <i class="fas fa-running text-primary me-2"></i>Data Fisik
                                </h6>
                                <div class="row g-3">
                                    <div class="col-6">
                                        <label class="form-label small fw-medium">Tinggi Badan (cm) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-sm @error('height') is-invalid @enderror"
                                            id="height" name="height" value="{{ old('height') }}" required
                                            min="100" max="250" step="1" placeholder="170">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium">Berat Badan (kg) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-sm @error('weight') is-invalid @enderror"
                                            id="weight" name="weight" value="{{ old('weight') }}" required
                                            min="30" max="150" step="0.5" placeholder="65">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium">Ukuran Kaos <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm @error('tshirt_size') is-invalid @enderror"
                                            id="tshirt_size" name="tshirt_size" required>
                                            <option value="">Pilih</option>
                                            @foreach($tshirtSizes as $size)
                                            <option value="{{ $size }}" {{ old('tshirt_size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium">Ukuran Sepatu <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm @error('shoes_size') is-invalid @enderror"
                                            id="shoes_size" name="shoes_size" required>
                                            <option value="">Pilih</option>
                                            @foreach($shoesSizes as $size)
                                            <option value="{{ $size }}" {{ old('shoes_size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if($category !== 'dancer')
                                    <div class="col-12">
                                        <label class="form-label small fw-medium">Posisi Basket <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm @error('basketball_position') is-invalid @enderror"
                                            id="basketball_position" name="basketball_position" required>
                                            <option value="">Pilih Posisi</option>
                                            @foreach($basketballPositions as $position)
                                            <option value="{{ $position }}" {{ old('basketball_position') == $position ? 'selected' : '' }}>{{ $position }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-medium">Nomor Jersey <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-sm @error('jersey_number') is-invalid @enderror"
                                            id="jersey_number" name="jersey_number" value="{{ old('jersey_number') }}" required
                                            min="0" max="99" placeholder="0-99">
                                        <small class="text-muted d-block mt-1">Nomor punggung yang akan dipakai saat bertanding</small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KANAN -->
                    <div class="col-md-6">
                        <div class="ps-md-3">
                            <!-- Data Orang Tua -->
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3 pb-1 border-bottom">
                                    <i class="fas fa-users text-primary me-2"></i>Data Orang Tua / Wali
                                </h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label small fw-medium">Nama Ayah <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm @error('father_name') is-invalid @enderror"
                                            id="father_name" name="father_name" value="{{ old('father_name') }}" required
                                            placeholder="Nama lengkap ayah">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-medium">No. Telepon Ayah <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control form-control-sm @error('father_phone') is-invalid @enderror"
                                            id="father_phone" name="father_phone" value="{{ old('father_phone') }}" required
                                            placeholder="081234567890">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-medium">Nama Ibu <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm @error('mother_name') is-invalid @enderror"
                                            id="mother_name" name="mother_name" value="{{ old('mother_name') }}" required
                                            placeholder="Nama lengkap ibu">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-medium">No. Telepon Ibu <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control form-control-sm @error('mother_phone') is-invalid @enderror"
                                            id="mother_phone" name="mother_phone" value="{{ old('mother_phone') }}" required
                                            placeholder="081234567890">
                                    </div>
                                </div>
                            </div>

                            <!-- Media Sosial -->
                            <div class="mb-4">
                                <h6 class="fw-semibold mb-3 pb-1 border-bottom">
                                    <i class="fab fa-instagram text-primary me-2"></i>Media Sosial (Opsional)
                                </h6>
                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label small fw-medium">Instagram</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light">@</span>
                                            <input type="text" class="form-control form-control-sm @error('instagram') is-invalid @enderror"
                                                id="instagram" name="instagram" value="{{ old('instagram') }}"
                                                placeholder="username">
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-medium">TikTok</label>
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-light">@</span>
                                            <input type="text" class="form-control form-control-sm @error('tiktok') is-invalid @enderror"
                                                id="tiktok" name="tiktok" value="{{ old('tiktok') }}"
                                                placeholder="username">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dokumen Wajib -->
                <div class="mt-4 pt-3 border-top">
                    <h6 class="fw-semibold mb-3">
                        <i class="fas fa-file-alt text-primary me-2"></i>Dokumen Wajib
                        <span class="badge bg-primary bg-opacity-10 text-primary ms-2 px-3 py-1 small">6 Dokumen</span>
                    </h6>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                    <span class="small fw-medium">Akta Kelahiran <span class="text-danger">*</span></span>
                                </div>
                                <input type="file" class="form-control form-control-sm"
                                    id="birth_certificate" name="birth_certificate" accept=".pdf" required>
                                <small class="text-muted d-block mt-1">PDF, maks. 1MB</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                    <span class="small fw-medium">Kartu Keluarga <span class="text-danger">*</span></span>
                                </div>
                                <input type="file" class="form-control form-control-sm"
                                    id="kk" name="kk" accept=".pdf" required>
                                <small class="text-muted d-block mt-1">PDF, maks. 1MB</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                    <span class="small fw-medium">SHUN / Ijazah <span class="text-danger">*</span></span>
                                </div>
                                <input type="file" class="form-control form-control-sm"
                                    id="shun" name="shun" accept=".pdf" required>
                                <small class="text-muted d-block mt-1">PDF, maks. 1MB</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                    <span class="small fw-medium">Raport Terakhir <span class="text-danger">*</span></span>
                                </div>
                                <input type="file" class="form-control form-control-sm"
                                    id="last_report_card" name="last_report_card" accept=".pdf" required>
                                <small class="text-muted d-block mt-1">PDF, maks. 1MB</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-image text-primary me-2"></i>
                                    <span class="small fw-medium">Foto Formal <span class="text-danger">*</span></span>
                                </div>
                                <input type="file" class="form-control form-control-sm"
                                    id="formal_photo" name="formal_photo" accept=".jpg,.jpeg,.png" required>
                                <small class="text-muted d-block mt-1">JPG/PNG, maks. 1MB (background merah)</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                    <span class="small fw-medium">Surat Penugasan <span class="text-danger">*</span></span>
                                </div>
                                <input type="file" class="form-control form-control-sm"
                                    id="assignment_letter" name="assignment_letter" accept=".pdf" required>
                                <small class="text-muted d-block mt-1">PDF, maks. 1MB</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION KHUSUS LEADER -->
                @if($role === 'Leader')
                <div class="mt-4 pt-3 border-top">
                    <h6 class="fw-semibold mb-3">
                        <i class="fas fa-crown text-warning me-2"></i>Khusus Kapten Tim
                        <span class="badge bg-warning bg-opacity-10 text-warning ms-2 px-3 py-1 small">Wajib diisi</span>
                    </h6>

                    <div class="row g-3">
                        @if($category !== 'dancer')
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-tshirt text-warning me-2"></i>
                                    <span class="small fw-medium">Foto Jersey Tim <span class="text-danger">*</span></span>
                                </div>
                                <div class="row g-2">
                                    <div class="col-4">
                                        <label class="small text-muted d-block mb-1">Home</label>
                                        <input type="file" class="form-control form-control-sm" id="jersey_home" 
                                               name="jersey_home" accept=".jpg,.jpeg,.png">
                                    </div>
                                    <div class="col-4">
                                        <label class="small text-muted d-block mb-1">Away</label>
                                        <input type="file" class="form-control form-control-sm" id="jersey_away" 
                                               name="jersey_away" accept=".jpg,.jpeg,.png">
                                    </div>
                                    <div class="col-4">
                                        <label class="small text-muted d-block mb-1">Alternate</label>
                                        <input type="file" class="form-control form-control-sm" id="jersey_alternate" 
                                               name="jersey_alternate" accept=".jpg,.jpeg,.png">
                                    </div>
                                </div>
                                <small class="text-warning d-block mt-2">⚠️ Minimal upload 1 foto jersey tim (maks 2MB per file)</small>
                            </div>
                        </div>
                        @endif

                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-credit-card text-success me-2"></i>
                                    <span class="small fw-medium">Bukti Transfer <span class="text-danger">*</span></span>
                                </div>
                                <input type="file" class="form-control form-control-sm"
                                    id="payment_proof" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" required>
                                <small class="text-muted d-block mt-1">JPG/PNG/PDF, maks. 2MB</small>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="mt-4 pt-3 border-top">
                    <div class="alert alert-success border-0 mb-0" style="background: rgba(16, 185, 129, 0.1); border-radius: 12px;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            <small class="fw-medium">✅ Biaya pendaftaran sudah dibayar oleh Kapten tim</small>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Terms Agreement -->
                <div class="form-check mt-4">
                    <input class="form-check-input @error('terms') is-invalid @enderror"
                        type="checkbox" id="terms" name="terms" required>
                    <label class="form-check-label small" for="terms">
                        Saya menyetujui <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#termsModal">Syarat & Ketentuan</a> dan memastikan semua data yang diisi adalah benar.
                    </label>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-4">
                    <small class="text-muted">
                        <i class="fas fa-shield-alt text-primary me-1"></i>Data Anda aman
                    </small>
                    <div>
                        <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-4 me-2">
                            <i class="fas fa-times me-1"></i>Batal
                        </a>
                        <button type="submit" id="submitBtn" class="btn btn-primary btn-sm rounded-pill px-4">
                            @if($role === 'Leader')
                            <i class="fas fa-crown me-1"></i> Daftar sebagai Kapten
                            @else
                            <i class="fas fa-paper-plane me-1"></i> Kirim Pendaftaran
                            @endif
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Terms Modal -->
<div class="modal fade" id="termsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Syarat & Ketentuan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body small">
                <p class="fw-semibold">1. Keabsahan Data</p>
                <p class="text-muted">Peserta wajib mengisi data dengan benar dan sesuai dokumen resmi.</p>
                
                <p class="fw-semibold mt-3">2. Dokumen</p>
                <p class="text-muted">Semua dokumen yang diupload harus asli dan dapat diverifikasi.</p>
                
                <p class="fw-semibold mt-3">3. Pembayaran</p>
                <p class="text-muted">Pembayaran dilakukan sesuai ketentuan yang berlaku dan tidak dapat dikembalikan.</p>
                
                <p class="fw-semibold mt-3">4. Verifikasi</p>
                <p class="text-muted">Tim berhak melakukan verifikasi data dan menolak pendaftaran yang tidak memenuhi syarat.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Setuju & Tutup</button>
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
                <h6 class="fw-semibold mb-1">Memproses Pendaftaran...</h6>
                <small class="text-muted">Mohon tunggu sebentar</small>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-opacity-10 {
        background: rgba(67, 97, 238, 0.1);
    }
    
    .form-control-sm, .form-select-sm {
        border-radius: 8px;
        font-size: 0.9rem;
        padding: 0.4rem 0.75rem;
    }

    .form-control:focus, .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.25rem;
        color: #2b2d42;
        font-size: 0.85rem;
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

    .border-end, .pe-md-3, .ps-md-3 {
        border-right: none !important;
    }
    
    @media (min-width: 768px) {
        .border-end {
            border-right: 1px solid #e9ecef !important;
        }
        .pe-md-3 {
            padding-right: 1rem !important;
            border-right: 1px solid #e9ecef;
        }
        .ps-md-3 {
            padding-left: 1rem !important;
        }
    }

    .invalid-feedback {
        font-size: 0.7rem;
    }

    @media (max-width: 768px) {
        .btn {
            width: 100%;
            margin-bottom: 0.25rem;
        }
        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 0.5rem;
        }
        .pe-md-3, .ps-md-3 {
            padding-left: 0 !important;
            padding-right: 0 !important;
            border-right: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('playerForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    
    // Set max birthdate (minimal 10 tahun)
    const today = new Date();
    const minBirthDate = new Date();
    minBirthDate.setFullYear(today.getFullYear() - 10);
    const birthdateInput = document.getElementById('birthdate');
    if (birthdateInput) {
        birthdateInput.max = minBirthDate.toISOString().split('T')[0];
    }

    // Validasi NIK (AJAX dengan debounce)
    const nikInput = document.getElementById('nik');
    if (nikInput) {
        let nikTimeout;
        nikInput.addEventListener('input', function() {
            clearTimeout(nikTimeout);
            const nik = this.value;
            if (nik.length === 16) {
                nikTimeout = setTimeout(() => {
                    fetch('{{ route("form.player.checkNik") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ nik: nik })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            Swal.fire({
                                icon: 'error',
                                title: 'NIK Sudah Terdaftar',
                                text: 'NIK ini sudah digunakan oleh pemain lain.',
                                confirmButtonColor: '#4361ee'
                            });
                            nikInput.classList.add('is-invalid');
                            document.getElementById('nikFeedback').innerText = 'NIK sudah terdaftar';
                        } else {
                            nikInput.classList.remove('is-invalid');
                            document.getElementById('nikFeedback').innerText = '';
                        }
                    });
                }, 500);
            }
        });
    }

    // Validasi Email (AJAX dengan debounce)
    const emailInput = document.getElementById('email');
    if (emailInput) {
        let emailTimeout;
        emailInput.addEventListener('input', function() {
            clearTimeout(emailTimeout);
            const email = this.value;
            if (email && email.includes('@')) {
                emailTimeout = setTimeout(() => {
                    fetch('{{ route("form.player.checkEmail") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ email: email })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.exists) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Email Sudah Terdaftar',
                                text: 'Email ini sudah digunakan oleh pemain lain.',
                                confirmButtonColor: '#4361ee'
                            });
                            emailInput.classList.add('is-invalid');
                            document.getElementById('emailFeedback').innerText = 'Email sudah terdaftar';
                        } else {
                            emailInput.classList.remove('is-invalid');
                            document.getElementById('emailFeedback').innerText = '';
                        }
                    });
                }, 500);
            }
        });
    }

    // Validasi STTB tahun
    const sttbYearField = document.getElementById('sttb_year');
    if (birthdateInput && sttbYearField) {
        birthdateInput.addEventListener('change', function() {
            if (this.value && sttbYearField.value) {
                const birthYear = new Date(this.value).getFullYear();
                const minSttbYear = birthYear + 16;
                const currentYear = new Date().getFullYear();
                
                if (parseInt(sttbYearField.value) < minSttbYear || parseInt(sttbYearField.value) > currentYear) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tahun STTB Tidak Valid',
                        text: `Tahun STTB harus antara ${minSttbYear} - ${currentYear}`,
                        confirmButtonColor: '#4361ee'
                    });
                    sttbYearField.value = '';
                }
            }
        });
    }

    // Phone number validation
    function validatePhone(input, fieldName) {
        if (input.value) {
            const phone = input.value.replace(/[^0-9]/g, '');
            if (phone.length < 10 || phone.length > 13) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Nomor Telepon Tidak Valid',
                    text: `${fieldName} harus 10-13 digit angka`,
                    confirmButtonColor: '#4361ee'
                });
                input.value = '';
                return false;
            }
        }
        return true;
    }

    document.getElementById('phone')?.addEventListener('blur', function() {
        validatePhone(this, 'Nomor WhatsApp');
    });
    document.getElementById('father_phone')?.addEventListener('blur', function() {
        validatePhone(this, 'Nomor telepon ayah');
    });
    document.getElementById('mother_phone')?.addEventListener('blur', function() {
        validatePhone(this, 'Nomor telepon ibu');
    });

    // Jersey number validation
    const jerseyNumber = document.getElementById('jersey_number');
    if (jerseyNumber) {
        jerseyNumber.addEventListener('input', function() {
            let val = parseInt(this.value);
            if (isNaN(val)) val = 0;
            if (val < 0) this.value = 0;
            if (val > 99) this.value = 99;
        });
    }

    // Form validation with SweetAlert
    form.addEventListener('submit', function(e) {
        if (!validateAllFields()) {
            e.preventDefault();
            return false;
        }

        // Konfirmasi sebelum submit
        Swal.fire({
            title: 'Konfirmasi Pendaftaran',
            text: 'Pastikan semua data sudah benar. Apakah Anda yakin?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#4361ee',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Cek Lagi'
        }).then((result) => {
            if (result.isConfirmed) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
                submitBtn.disabled = true;
                loadingModal.show();
                form.submit();
            }
        });
        
        e.preventDefault();
        return false;
    });

    function validateAllFields() {
        // Required text fields
        const requiredTextFields = [
            { id: 'nik', name: 'NIK' },
            { id: 'name', name: 'Nama Lengkap' },
            { id: 'birth_place', name: 'Tempat Lahir' },
            { id: 'birthdate', name: 'Tanggal Lahir' },
            { id: 'gender', name: 'Jenis Kelamin' },
            { id: 'phone', name: 'WhatsApp' },
            { id: 'email', name: 'Email' },
            { id: 'grade', name: 'Kelas' },
            { id: 'sttb_year', name: 'Tahun STTB' },
            { id: 'height', name: 'Tinggi Badan' },
            { id: 'weight', name: 'Berat Badan' },
            { id: 'tshirt_size', name: 'Ukuran Kaos' },
            { id: 'shoes_size', name: 'Ukuran Sepatu' },
            { id: 'father_name', name: 'Nama Ayah' },
            { id: 'father_phone', name: 'No. Telepon Ayah' },
            { id: 'mother_name', name: 'Nama Ibu' },
            { id: 'mother_phone', name: 'No. Telepon Ibu' }
        ];

        @if($category !== 'dancer')
        requiredTextFields.push(
            { id: 'basketball_position', name: 'Posisi Basket' },
            { id: 'jersey_number', name: 'Nomor Jersey' }
        );
        @endif

        for (const field of requiredTextFields) {
            const element = document.getElementById(field.id);
            if (!element || !element.value.trim()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Field Tidak Lengkap',
                    text: `Harap isi ${field.name}`,
                    confirmButtonColor: '#4361ee'
                });
                if (element) element.focus();
                return false;
            }
        }

        // NIK validation (16 digit)
        const nik = document.getElementById('nik').value;
        if (nik.length !== 16) {
            Swal.fire({
                icon: 'error',
                title: 'NIK Tidak Valid',
                text: 'NIK harus 16 digit angka',
                confirmButtonColor: '#4361ee'
            });
            document.getElementById('nik').focus();
            return false;
        }

        // Email format
        const email = document.getElementById('email').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            Swal.fire({
                icon: 'error',
                title: 'Email Tidak Valid',
                text: 'Format email tidak valid',
                confirmButtonColor: '#4361ee'
            });
            document.getElementById('email').focus();
            return false;
        }

        // Phone numbers
        const phone = document.getElementById('phone').value.replace(/[^0-9]/g, '');
        if (phone.length < 10 || phone.length > 13) {
            Swal.fire({
                icon: 'error',
                title: 'Nomor WhatsApp Tidak Valid',
                text: 'Nomor WhatsApp harus 10-13 digit',
                confirmButtonColor: '#4361ee'
            });
            document.getElementById('phone').focus();
            return false;
        }

        // STTB validation
        if (birthdateInput && birthdateInput.value) {
            const sttbYear = parseInt(document.getElementById('sttb_year').value);
            const birthYear = new Date(birthdateInput.value).getFullYear();
            const minSttbYear = birthYear + 16;
            const currentYear = new Date().getFullYear();
            
            if (sttbYear < minSttbYear || sttbYear > currentYear) {
                Swal.fire({
                    icon: 'error',
                    title: 'Tahun STTB Tidak Valid',
                    text: `Tahun STTB harus antara ${minSttbYear} - ${currentYear}`,
                    confirmButtonColor: '#4361ee'
                });
                document.getElementById('sttb_year').focus();
                return false;
            }
        }

        // File uploads
        const docInputs = [
            { id: 'birth_certificate', name: 'Akta Kelahiran' },
            { id: 'kk', name: 'Kartu Keluarga' },
            { id: 'shun', name: 'SHUN' },
            { id: 'last_report_card', name: 'Raport Terakhir' },
            { id: 'formal_photo', name: 'Foto Formal' },
            { id: 'assignment_letter', name: 'Surat Penugasan' }
        ];

        for (const doc of docInputs) {
            const element = document.getElementById(doc.id);
            if (!element.files || element.files.length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Dokumen Kurang',
                    text: `Upload ${doc.name}`,
                    confirmButtonColor: '#4361ee'
                });
                element.focus();
                return false;
            }
        }

        @if($role === 'Leader')
        // Payment proof
        const paymentProof = document.getElementById('payment_proof');
        if (!paymentProof.files || paymentProof.files.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Bukti Transfer Belum Diupload',
                text: 'Sebagai Kapten, Anda wajib upload bukti transfer',
                confirmButtonColor: '#4361ee'
            });
            paymentProof.focus();
            return false;
        }
        
        @if($category !== 'dancer')
        // Cek jersey (minimal 1)
        const jerseyHome = document.getElementById('jersey_home');
        const jerseyAway = document.getElementById('jersey_away');
        const jerseyAlt = document.getElementById('jersey_alternate');
        
        const hasJersey = (jerseyHome && jerseyHome.files.length > 0) || 
                         (jerseyAway && jerseyAway.files.length > 0) || 
                         (jerseyAlt && jerseyAlt.files.length > 0);
        
        if (!hasJersey) {
            Swal.fire({
                icon: 'error',
                title: 'Foto Jersey Belum Diupload',
                text: 'Sebagai Kapten, Anda wajib upload minimal 1 foto jersey tim!',
                confirmButtonColor: '#4361ee'
            });
            return false;
        }
        @endif
        @endif

        // Terms checkbox
        if (!document.getElementById('terms').checked) {
            Swal.fire({
                icon: 'warning',
                title: 'Syarat & Ketentuan',
                text: 'Anda harus menyetujui syarat & ketentuan',
                confirmButtonColor: '#4361ee'
            });
            document.getElementById('terms').focus();
            return false;
        }

        return true;
    }
});
</script>
@endpush

@endsection