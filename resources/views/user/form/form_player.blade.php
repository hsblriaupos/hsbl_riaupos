@extends('user.form.layout')

@section('title', 'Form Pendaftaran Pemain - HSBL')

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
                <div style="width: 80px; height: 2px; background: linear-gradient(90deg, #4361ee, #e9ecef);"></div>
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
        <!-- Card Header -->
        <div class="card-header bg-white border-0 pt-4 px-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="fw-semibold mb-1" style="color: #2b2d42;">
                        <i class="fas fa-user-plus text-primary me-2" style="font-size: 1.2rem;"></i>
                        @if($role === 'Leader')
                        Form Leader {{ ucfirst($category) }}
                        @else
                        Form Pemain {{ ucfirst($category) }}
                        @endif
                    </h5>
                    <p class="text-muted small mb-0">{{ $team->school_name }} • {{ $team->team_category }}</p>
                </div>
                <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
            </div>
        </div>

        <!-- Alert Messages -->
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
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert"></button>
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

                <!-- Quick Guide -->
                <div class="bg-light p-3 rounded-3 mb-4" style="background: #f8f9fa;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-lightbulb text-primary me-2" style="font-size: 1rem;"></i>
                        <span class="small fw-semibold me-3">Tips:</span>
                        <span class="small text-muted me-3">1. Data Pribadi</span>
                        <span class="small text-muted me-3">→</span>
                        <span class="small text-muted me-3">2. Data Fisik</span>
                        <span class="small text-muted me-3">→</span>
                        <span class="small text-muted">3. Upload Dokumen</span>
                    </div>
                </div>

                <!-- Role Alert -->
                @if($role === 'Leader')
                <div class="alert bg-soft-warning py-2 px-3 mb-4 border-0" role="alert" style="border-radius: 10px;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-crown text-warning me-2"></i>
                        <small class="fw-medium">Anda adalah Leader Tim - Bertanggung jawab untuk pembayaran dan upload jersey tim</small>
                    </div>
                </div>
                @else
                <div class="alert bg-soft-teal py-2 px-3 mb-4 border-0" role="alert" style="border-radius: 10px;">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-users text-teal me-2"></i>
                        <small class="fw-medium">Anda adalah Member Tim - Bergabung dengan referral code</small>
                    </div>
                </div>
                @endif

                <!-- LAYOUT 2 KOLOM -->
                <div class="row g-4">
                    <!-- KOLOM KIRI: Data Pribadi & Data Fisik -->
                    <div class="col-md-6">
                        <div class="border-end pe-3">
                            <!-- Data Pribadi -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-2 d-flex align-items-center justify-content-center me-2"
                                        style="width: 32px; height: 32px;">
                                        <i class="fas fa-id-card" style="font-size: 1rem;"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-0" style="color: #2b2d42;">Data Pribadi</h6>
                                </div>

                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small fw-medium mb-1">NIK <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm @error('nik') is-invalid @enderror"
                                            id="nik" name="nik" value="{{ old('nik') }}" required
                                            placeholder="16 digit" maxlength="16"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-medium mb-1">Nama Lengkap <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required
                                            placeholder="Nama lengkap">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium mb-1">Tanggal Lahir <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control form-control-sm @error('birthdate') is-invalid @enderror"
                                            id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required
                                            max="{{ date('Y-m-d', strtotime('-10 years')) }}">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium mb-1">Jenis Kelamin <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm @error('gender') is-invalid @enderror"
                                            id="gender" name="gender" required>
                                            <option value="">Pilih</option>
                                            @foreach($genderOptions as $gender)
                                            <option value="{{ $gender }}" {{ old('gender') == $gender ? 'selected' : '' }}>{{ $gender }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium mb-1">WhatsApp <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                            id="phone" name="phone" value="{{ old('phone') }}" required
                                            placeholder="081234567890"
                                            oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium mb-1">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required
                                            placeholder="email@example.com">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium mb-1">Kelas <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm @error('grade') is-invalid @enderror"
                                            id="grade" name="grade" required>
                                            <option value="">Pilih</option>
                                            @foreach($grades as $grade)
                                            <option value="{{ $grade }}" {{ old('grade') == $grade ? 'selected' : '' }}>{{ $grade }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium mb-1">Tahun STTB <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm @error('sttb_year') is-invalid @enderror"
                                            id="sttb_year" name="sttb_year" value="{{ old('sttb_year') }}" required
                                            placeholder="2024" maxlength="4"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                </div>
                            </div>

                            <!-- Data Fisik -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-2 d-flex align-items-center justify-content-center me-2"
                                        style="width: 32px; height: 32px;">
                                        <i class="fas fa-running" style="font-size: 1rem;"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-0" style="color: #2b2d42;">Data Fisik & Basket</h6>
                                </div>

                                <div class="row g-2">
                                    <div class="col-6">
                                        <label class="form-label small fw-medium mb-1">Tinggi (cm) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-sm @error('height') is-invalid @enderror"
                                            id="height" name="height" value="{{ old('height') }}" required
                                            min="100" max="250" step="1" placeholder="170">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium mb-1">Berat (kg) <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-sm @error('weight') is-invalid @enderror"
                                            id="weight" name="weight" value="{{ old('weight') }}" required
                                            min="30" max="150" step="0.5" placeholder="65.5">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium mb-1">Ukuran Kaos <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm @error('tshirt_size') is-invalid @enderror"
                                            id="tshirt_size" name="tshirt_size" required>
                                            <option value="">Pilih</option>
                                            @foreach($tshirtSizes as $size)
                                            <option value="{{ $size }}" {{ old('tshirt_size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium mb-1">Ukuran Sepatu <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm @error('shoes_size') is-invalid @enderror"
                                            id="shoes_size" name="shoes_size" required>
                                            <option value="">Pilih</option>
                                            @foreach($shoesSizes as $size)
                                            <option value="{{ $size }}" {{ old('shoes_size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    @if($category !== 'dancer')
                                    <div class="col-6">
                                        <label class="form-label small fw-medium mb-1">Posisi Basket <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-sm @error('basketball_position') is-invalid @enderror"
                                            id="basketball_position" name="basketball_position" required>
                                            <option value="">Pilih Posisi</option>
                                            @foreach($basketballPositions as $position)
                                            <option value="{{ $position }}" {{ old('basketball_position') == $position ? 'selected' : '' }}>{{ $position }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label small fw-medium mb-1">Nomor Jersey <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control form-control-sm @error('jersey_number') is-invalid @enderror"
                                            id="jersey_number" name="jersey_number" value="{{ old('jersey_number') }}" required
                                            min="0" max="99" placeholder="0-99"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- KOLOM KANAN: Data Orang Tua -->
                    <div class="col-md-6">
                        <div class="ps-3">
                            <!-- Data Orang Tua -->
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-2 d-flex align-items-center justify-content-center me-2"
                                        style="width: 32px; height: 32px;">
                                        <i class="fas fa-users" style="font-size: 1rem;"></i>
                                    </div>
                                    <h6 class="fw-semibold mb-0" style="color: #2b2d42;">Data Orang Tua</h6>
                                </div>

                                <div class="row g-2">
                                    <div class="col-12">
                                        <label class="form-label small fw-medium mb-1">Nama Ayah <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm @error('father_name') is-invalid @enderror"
                                            id="father_name" name="father_name" value="{{ old('father_name') }}" placeholder="Nama ayah" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-medium mb-1">No. Telepon Ayah <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control form-control-sm @error('father_phone') is-invalid @enderror"
                                            id="father_phone" name="father_phone" value="{{ old('father_phone') }}" placeholder="No. telepon" required
                                            oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-medium mb-1">Nama Ibu <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control form-control-sm @error('mother_name') is-invalid @enderror"
                                            id="mother_name" name="mother_name" value="{{ old('mother_name') }}" placeholder="Nama ibu" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-medium mb-1">No. Telepon Ibu <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control form-control-sm @error('mother_phone') is-invalid @enderror"
                                            id="mother_phone" name="mother_phone" value="{{ old('mother_phone') }}" placeholder="No. telepon" required
                                            oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dokumen Wajib (Full Width) -->
                <div class="mt-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-2 d-flex align-items-center justify-content-center me-2"
                            style="width: 32px; height: 32px;">
                            <i class="fas fa-file-alt" style="font-size: 1rem;"></i>
                        </div>
                        <h6 class="fw-semibold mb-0" style="color: #2b2d42;">Dokumen Wajib</h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary ms-3 px-3 py-1 small">6 Dokumen</span>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                    <span class="small fw-medium">Akta Kelahiran <span class="text-danger">*</span></span>
                                </div>
                                <input type="file" class="form-control form-control-sm"
                                    id="birth_certificate" name="birth_certificate" accept=".pdf" required>
                                <small class="text-muted d-block mt-1">Maks. 1MB, PDF</small>
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
                                <small class="text-muted d-block mt-1">Maks. 1MB, PDF</small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-file-pdf text-danger me-2"></i>
                                    <span class="small fw-medium">SHUN <span class="text-danger">*</span></span>
                                </div>
                                <input type="file" class="form-control form-control-sm"
                                    id="shun" name="shun" accept=".pdf" required>
                                <small class="text-muted d-block mt-1">Maks. 1MB, PDF</small>
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
                                <small class="text-muted d-block mt-1">Maks. 1MB, PDF</small>
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
                                <small class="text-muted d-block mt-1">Maks. 1MB, JPG/PNG</small>
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
                                <small class="text-muted d-block mt-1">Maks. 1MB, PDF</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION KHUSUS LEADER -->
                @if($role === 'Leader')
                <div class="mt-4 pt-3 border-top">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-2 d-flex align-items-center justify-content-center me-2"
                            style="width: 32px; height: 32px;">
                            <i class="fas fa-crown" style="font-size: 1rem;"></i>
                        </div>
                        <h6 class="fw-semibold mb-0" style="color: #2b2d42;">Khusus Leader</h6>
                        <span class="badge bg-warning bg-opacity-10 text-warning ms-3 px-3 py-1 small">Wajib diisi</span>
                    </div>

                    <div class="row g-3">
                        @if($category !== 'dancer')
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-tshirt text-warning me-2"></i>
                                    <span class="small fw-medium">Jersey Tim <span class="text-danger">*</span></span>
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
                                <small class="text-muted d-block mt-2">Upload minimal 1 foto jersey (maks 2MB per file)</small>
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
                                <small class="text-muted d-block mt-1">Maks. 2MB, JPG/PNG/PDF</small>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="mt-4 pt-3 border-top">
                    <div class="alert bg-soft-teal py-2 px-3 mb-0 border-0" role="alert" style="border-radius: 10px;">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle text-teal me-2"></i>
                            <small>Biaya pendaftaran sudah dibayar oleh Leader Tim</small>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Terms Agreement -->
                <div class="form-check mt-4">
                    <input class="form-check-input @error('terms') is-invalid @enderror"
                        type="checkbox" id="terms" name="terms" required>
                    <label class="form-check-label small" for="terms">
                        Saya menyetujui Syarat & Ketentuan dan memastikan semua data yang diisi adalah benar.
                    </label>
                </div>

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
                            @if($role === 'Leader')
                            Daftar Leader <i class="fas fa-crown ms-1"></i>
                            @else
                            Kirim <i class="fas fa-paper-plane ms-1"></i>
                            @endif
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
    /* Soft Backgrounds */
    .bg-soft-warning {
        background: rgba(248, 150, 30, 0.1);
    }

    .bg-soft-danger {
        background: rgba(249, 65, 68, 0.1);
    }

    .bg-soft-teal {
        background: rgba(16, 185, 129, 0.1);
    }

    .bg-soft-success {
        background: rgba(16, 185, 129, 0.1);
    }

    .text-teal {
        color: #10b981;
    }

    /* Form Controls */
    .form-control-sm,
    .form-select-sm {
        border-radius: 8px;
        font-size: 0.9rem;
        padding: 0.4rem 0.75rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.25rem;
        color: #2b2d42;
        font-size: 0.85rem;
    }

    /* Alert */
    .alert {
        border-radius: 10px;
    }

    /* Button */
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

    .btn-outline-secondary {
        border-color: #dee2e6;
        color: #6c757d;
    }

    .btn-outline-secondary:hover {
        background: #f8f9fa;
        border-color: #adb5bd;
        color: #495057;
    }

    /* Border separator */
    .border-end {
        border-right: 1px solid #e9ecef !important;
        height: 100%;
    }

    /* Badge */
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
        font-size: 0.7rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .mx-3 {
            margin-left: 0.5rem !important;
            margin-right: 0.5rem !important;
        }

        .mx-3 div {
            width: 40px !important;
        }

        .btn {
            width: 100%;
            margin-bottom: 0.25rem;
        }

        .d-flex.justify-content-between {
            flex-direction: column;
            gap: 0.5rem;
        }

        .border-end {
            border-right: none !important;
            border-bottom: 1px solid #e9ecef !important;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .ps-3 {
            padding-left: 0 !important;
        }

        .pe-3 {
            padding-right: 0 !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('playerForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    
    // Set max birthdate
    const today = new Date();
    const minBirthDate = new Date();
    minBirthDate.setFullYear(today.getFullYear() - 10);
    const birthdateInput = document.getElementById('birthdate');
    if (birthdateInput) {
        birthdateInput.max = minBirthDate.toISOString().split('T')[0];
    }

    // Validasi STTB tahun
    const birthdateField = document.getElementById('birthdate');
    const sttbYearField = document.getElementById('sttb_year');
    
    if (birthdateField && sttbYearField) {
        birthdateField.addEventListener('change', function() {
            if (this.value) {
                const birthYear = new Date(this.value).getFullYear();
                const minSttbYear = birthYear + 16;
                const currentYear = new Date().getFullYear();
                
                if (sttbYearField.value && (parseInt(sttbYearField.value) < minSttbYear || parseInt(sttbYearField.value) > currentYear)) {
                    alert(`Tahun STTB harus antara ${minSttbYear} - ${currentYear}`);
                    sttbYearField.value = '';
                }
            }
        });
    }

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

    // Phone validation
    function validatePhone(input, fieldName) {
        if (input.value) {
            const phone = input.value.replace(/[^0-9]/g, '');
            if (phone.length < 10) {
                alert(`${fieldName} minimal 10 digit`);
                input.focus();
                return false;
            }
        }
        return true;
    }

    const phoneInput = document.getElementById('phone');
    const fatherPhone = document.getElementById('father_phone');
    const motherPhone = document.getElementById('mother_phone');

    if (phoneInput) {
        phoneInput.addEventListener('blur', function() {
            validatePhone(this, 'Nomor WhatsApp');
        });
    }

    if (fatherPhone) {
        fatherPhone.addEventListener('blur', function() {
            validatePhone(this, 'Nomor telepon ayah');
        });
    }

    if (motherPhone) {
        motherPhone.addEventListener('blur', function() {
            validatePhone(this, 'Nomor telepon ibu');
        });
    }

    // Jersey number validation
    const jerseyNumber = document.getElementById('jersey_number');
    if (jerseyNumber) {
        jerseyNumber.addEventListener('input', function() {
            if (this.value < 0) this.value = 0;
            if (this.value > 99) this.value = 99;
        });
    }

    // Form validation
    form.addEventListener('submit', function(e) {
        if (!validateAllFields()) {
            e.preventDefault();
            return false;
        }

        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';
        submitBtn.disabled = true;
        loadingModal.show();

        return true;
    });

    function validateAllFields() {
        // Required fields
        const requiredInputs = [
            { id: 'nik', name: 'NIK' },
            { id: 'name', name: 'Nama Lengkap' },
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
        requiredInputs.push(
            { id: 'basketball_position', name: 'Posisi Basket' },
            { id: 'jersey_number', name: 'Nomor Jersey' }
        );
        @endif

        for (const field of requiredInputs) {
            const element = document.getElementById(field.id);
            if (!element || !element.value.trim()) {
                alert(`Harap isi ${field.name}`);
                if (element) element.focus();
                return false;
            }
        }

        // NIK validation
        const nik = document.getElementById('nik').value;
        if (nik.length !== 16) {
            alert('NIK harus 16 digit');
            document.getElementById('nik').focus();
            return false;
        }

        // Email format
        const email = document.getElementById('email').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Format email tidak valid');
            document.getElementById('email').focus();
            return false;
        }

        // Phone numbers
        const phone = document.getElementById('phone').value.replace(/[^0-9]/g, '');
        if (phone.length < 10) {
            alert('Nomor WhatsApp minimal 10 digit');
            document.getElementById('phone').focus();
            return false;
        }

        const fatherPhoneVal = document.getElementById('father_phone').value.replace(/[^0-9]/g, '');
        if (fatherPhoneVal.length < 10) {
            alert('Nomor telepon ayah minimal 10 digit');
            document.getElementById('father_phone').focus();
            return false;
        }

        const motherPhoneVal = document.getElementById('mother_phone').value.replace(/[^0-9]/g, '');
        if (motherPhoneVal.length < 10) {
            alert('Nomor telepon ibu minimal 10 digit');
            document.getElementById('mother_phone').focus();
            return false;
        }

        // STTB year validation
        if (document.getElementById('birthdate').value) {
            const sttbYear = parseInt(document.getElementById('sttb_year').value);
            const birthYear = new Date(document.getElementById('birthdate').value).getFullYear();
            const minSttbYear = birthYear + 16;
            const currentYear = new Date().getFullYear();
            
            if (sttbYear < minSttbYear || sttbYear > currentYear) {
                alert(`Tahun STTB harus antara ${minSttbYear} - ${currentYear}`);
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
                alert(`Upload ${doc.name}`);
                element.focus();
                return false;
            }
        }

        @if($role === 'Leader')
        // Payment proof
        const paymentProof = document.getElementById('payment_proof');
        if (!paymentProof.files || paymentProof.files.length === 0) {
            alert('Upload bukti transfer');
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
            alert('Sebagai Leader, Anda wajib upload minimal 1 foto jersey tim!');
            return false;
        }
        @endif


        // Terms checkbox
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