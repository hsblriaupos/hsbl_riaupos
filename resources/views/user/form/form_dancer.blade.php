@extends('user.form.layout')

@section('title', 'Form Pendaftaran Dancer - HSBL')

@section('content')
<div class="row justify-content-center">
    <div class="col-12">
        <div class="form-container">
            <!-- Header -->
            <div class="form-header">
                <div class="d-flex align-items-center">
                    <a href="{{ route('form.team.choice') }}" class="text-white me-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h2 class="mb-0">
                            @if($role === 'Leader')
                            👑 Form Leader Dancer
                            @else
                            💃 Form Dancer
                            @endif
                        </h2>
                        <p class="mb-0 mt-2 opacity-75">
                            {{ $team->school_name }} | Dancer
                            @if($role === 'Leader')
                            <span class="badge bg-warning ms-2">Anda adalah Leader</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Role Indicator -->
            @if($role === 'Leader')
            <div class="alert alert-warning mx-4 mt-4 mb-0">
                <div class="d-flex">
                    <i class="fas fa-crown fa-2x me-3 mt-1"></i>
                    <div>
                        <h6 class="alert-heading">🏆 Anda adalah Leader Tim!</h6>
                        <p class="mb-0">
                            Sebagai Leader, Anda bertanggung jawab untuk pembayaran tim.
                            <strong class="text-danger">Bukti pembayaran wajib diupload</strong> dan biaya hanya dibayar sekali oleh Leader.
                            Setelah pembayaran, Anda akan mendapatkan <strong>Referral Code</strong> untuk dibagikan ke anggota.
                        </p>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-info mx-4 mt-4 mb-0">
                <div class="d-flex">
                    <i class="fas fa-users fa-2x me-3 mt-1"></i>
                    <div>
                        <h6 class="alert-heading">🤝 Anda adalah Member Tim</h6>
                        <p class="mb-0">
                            Anda bergabung dengan tim menggunakan referral code.
                            <strong class="text-success">Tidak perlu membayar</strong> - biaya sudah dibayar oleh Leader.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Form -->
            <div class="card-body p-4 p-md-5">
                <form id="dancerForm" action="{{ route('form.dancer.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Hidden Fields -->
                    <input type="hidden" name="team_id" value="{{ $team->team_id }}">
                    <input type="hidden" name="role" value="{{ $role }}">

                    <!-- Progress Steps -->
                    <div class="steps-container mb-5">
                        <div class="steps">
                            <div class="step active" data-step="1">
                                <div class="step-circle">1</div>
                                <div class="step-label">Data Pribadi</div>
                            </div>
                            <div class="step" data-step="2">
                                <div class="step-circle">2</div>
                                <div class="step-label">Data Sekolah</div>
                            </div>
                            <div class="step" data-step="3">
                                <div class="step-circle">3</div>
                                <div class="step-label">Data Fisik</div>
                            </div>
                            <div class="step" data-step="4">
                                <div class="step-circle">4</div>
                                <div class="step-label">Orang Tua</div>
                            </div>
                            <div class="step" data-step="5">
                                <div class="step-circle">5</div>
                                <div class="step-label">Dokumen</div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 1: Personal Data -->
                    <div class="step-content active" id="step-1">
                        <h4 class="mb-4 text-primary">
                            <i class="fas fa-user me-2"></i>Data Pribadi
                        </h4>

                        <div class="row">
                            <!-- NIK -->
                            <div class="col-md-6 mb-3">
                                <label for="nik" class="form-label">
                                    NIK <span class="text-danger">*</span>
                                    <i class="fas fa-id-card ms-1 text-muted"></i>
                                </label>
                                <input type="text"
                                    class="form-control @error('nik') is-invalid @enderror"
                                    id="nik"
                                    name="nik"
                                    value="{{ old('nik') }}"
                                    required
                                    placeholder="16 digit NIK"
                                    maxlength="16"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <div class="form-text">Nomor Induk Kependudukan 16 digit</div>
                                <div id="nik-feedback" class="invalid-feedback"></div>
                                @error('nik')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Full Name -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">
                                    Nama Lengkap <span class="text-danger">*</span>
                                    <i class="fas fa-signature ms-1 text-muted"></i>
                                </label>
                                <input type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    id="name"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    placeholder="Nama lengkap sesuai KTP">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Birthdate -->
                            <div class="col-md-4 mb-3">
                                <label for="birthdate" class="form-label">
                                    Tanggal Lahir <span class="text-danger">*</span>
                                    <i class="fas fa-birthday-cake ms-1 text-muted"></i>
                                </label>
                                <input type="date"
                                    class="form-control @error('birthdate') is-invalid @enderror"
                                    id="birthdate"
                                    name="birthdate"
                                    value="{{ old('birthdate') }}"
                                    required
                                    max="{{ date('Y-m-d', strtotime('-10 years')) }}">
                                <div class="form-text">Minimal usia 10 tahun</div>
                                @error('birthdate')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Gender -->
                            <div class="col-md-4 mb-3">
                                <label for="gender" class="form-label">
                                    Jenis Kelamin <span class="text-danger">*</span>
                                    <i class="fas fa-venus-mars ms-1 text-muted"></i>
                                </label>
                                <select class="form-select @error('gender') is-invalid @enderror"
                                    id="gender" name="gender" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    @foreach($genderOptions as $gender)
                                    <option value="{{ $gender }}" {{ old('gender') == $gender ? 'selected' : '' }}>
                                        {{ $gender }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('gender')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-md-4 mb-3">
                                <label for="phone" class="form-label">
                                    No. WhatsApp <span class="text-danger">*</span>
                                    <i class="fab fa-whatsapp ms-1 text-success"></i>
                                </label>
                                <input type="tel"
                                    class="form-control @error('phone') is-invalid @enderror"
                                    id="phone"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    required
                                    placeholder="0812-3456-7890"
                                    oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                <div class="form-text">Nomor aktif untuk konfirmasi</div>
                                @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">
                                    Email <span class="text-danger">*</span>
                                    <i class="fas fa-envelope ms-1 text-muted"></i>
                                </label>
                                <input type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    placeholder="email@example.com">
                                <div id="email-feedback" class="invalid-feedback"></div>
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- School (Readonly) -->
                            <div class="col-md-6 mb-3">
                                <label for="school" class="form-label">
                                    Sekolah <span class="text-danger">*</span>
                                    <i class="fas fa-school ms-1 text-muted"></i>
                                </label>
                                <input type="text"
                                    class="form-control bg-light"
                                    id="school"
                                    value="{{ $team->school_name }}"
                                    readonly>
                                <input type="hidden" name="school_name" value="{{ $team->school_name }}">
                                <div class="form-text">Sekolah sudah terdaftar dalam tim</div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary" disabled>
                                <i class="fas fa-arrow-left me-2"></i>Sebelumnya
                            </button>
                            <button type="button" class="btn btn-primary next-step" data-next="2">
                                Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: School Data -->
                    <div class="step-content" id="step-2">
                        <h4 class="mb-4 text-primary">
                            <i class="fas fa-graduation-cap me-2"></i>Data Sekolah
                        </h4>

                        <div class="row">
                            <!-- Grade -->
                            <div class="col-md-4 mb-3">
                                <label for="grade" class="form-label">
                                    Kelas <span class="text-danger">*</span>
                                    <i class="fas fa-layer-group ms-1 text-muted"></i>
                                </label>
                                <select class="form-select @error('grade') is-invalid @enderror"
                                    id="grade" name="grade" required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach($grades as $grade)
                                    <option value="{{ $grade }}" {{ old('grade') == $grade ? 'selected' : '' }}>
                                        Kelas {{ $grade }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('grade')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- STTB Year -->
                            <div class="col-md-4 mb-3">
                                <label for="sttb_year" class="form-label">
                                    Tahun STTB <span class="text-danger">*</span>
                                    <i class="fas fa-calendar-alt ms-1 text-muted"></i>
                                </label>
                                <input type="text"
                                    class="form-control @error('sttb_year') is-invalid @enderror"
                                    id="sttb_year"
                                    name="sttb_year"
                                    value="{{ old('sttb_year') }}"
                                    required
                                    placeholder="2024"
                                    maxlength="4"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                <div class="form-text">Tahun Surat Tanda Tamat Belajar</div>
                                @error('sttb_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Social Media -->
                            <div class="col-md-4 mb-3">
                                <label for="instagram" class="form-label">
                                    Instagram
                                    <i class="fab fa-instagram ms-1 text-danger"></i>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">@</span>
                                    <input type="text"
                                        class="form-control @error('instagram') is-invalid @enderror"
                                        id="instagram"
                                        name="instagram"
                                        value="{{ old('instagram') }}"
                                        placeholder="username">
                                </div>
                                @error('instagram')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="tiktok" class="form-label">
                                    TikTok
                                    <i class="fab fa-tiktok ms-1 text-dark"></i>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">@</span>
                                    <input type="text"
                                        class="form-control @error('tiktok') is-invalid @enderror"
                                        id="tiktok"
                                        name="tiktok"
                                        value="{{ old('tiktok') }}"
                                        placeholder="username">
                                </div>
                                @error('tiktok')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary prev-step" data-prev="1">
                                <i class="fas fa-arrow-left me-2"></i>Sebelumnya
                            </button>
                            <button type="button" class="btn btn-primary next-step" data-next="3">
                                Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Physical Data -->
                    <div class="step-content" id="step-3">
                        <h4 class="mb-4 text-primary">
                            <i class="fas fa-running me-2"></i>Data Fisik
                        </h4>

                        <div class="row">
                            <!-- Height -->
                            <div class="col-md-3 mb-3">
                                <label for="height" class="form-label">
                                    Tinggi Badan (cm) <span class="text-danger">*</span>
                                    <i class="fas fa-ruler-vertical ms-1 text-muted"></i>
                                </label>
                                <input type="number"
                                    class="form-control @error('height') is-invalid @enderror"
                                    id="height"
                                    name="height"
                                    value="{{ old('height') }}"
                                    required
                                    min="100" max="250" step="1"
                                    placeholder="170">
                                <div class="form-text">Dalam centimeter</div>
                                @error('height')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Weight -->
                            <div class="col-md-3 mb-3">
                                <label for="weight" class="form-label">
                                    Berat Badan (kg) <span class="text-danger">*</span>
                                    <i class="fas fa-weight ms-1 text-muted"></i>
                                </label>
                                <input type="number"
                                    class="form-control @error('weight') is-invalid @enderror"
                                    id="weight"
                                    name="weight"
                                    value="{{ old('weight') }}"
                                    required
                                    min="30" max="150" step="0.5"
                                    placeholder="65.5">
                                <div class="form-text">Dalam kilogram</div>
                                @error('weight')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- T-Shirt Size -->
                            <div class="col-md-3 mb-3">
                                <label for="tshirt_size" class="form-label">
                                    Ukuran Kaos <span class="text-danger">*</span>
                                    <i class="fas fa-tshirt ms-1 text-muted"></i>
                                </label>
                                <select class="form-select @error('tshirt_size') is-invalid @enderror"
                                    id="tshirt_size" name="tshirt_size" required>
                                    <option value="">Pilih Ukuran</option>
                                    @foreach($tshirtSizes as $size)
                                    <option value="{{ $size }}" {{ old('tshirt_size') == $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('tshirt_size')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Shoes Size -->
                            <div class="col-md-3 mb-3">
                                <label for="shoes_size" class="form-label">
                                    Ukuran Sepatu <span class="text-danger">*</span>
                                    <i class="fas fa-shoe-prints ms-1 text-muted"></i>
                                </label>
                                <select class="form-select @error('shoes_size') is-invalid @enderror"
                                    id="shoes_size" name="shoes_size" required>
                                    <option value="">Pilih Ukuran</option>
                                    @foreach($shoesSizes as $size)
                                    <option value="{{ $size }}" {{ old('shoes_size') == $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('shoes_size')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary prev-step" data-prev="2">
                                <i class="fas fa-arrow-left me-2"></i>Sebelumnya
                            </button>
                            <button type="button" class="btn btn-primary next-step" data-next="4">
                                Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 4: Parents Data -->
                    <div class="step-content" id="step-4">
                        <h4 class="mb-4 text-primary">
                            <i class="fas fa-users me-2"></i>Data Orang Tua/Wali
                        </h4>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <h5 class="border-bottom pb-2">
                                    <i class="fas fa-male text-primary me-2"></i>Data Ayah
                                </h5>

                                <div class="mb-3">
                                    <label for="father_name" class="form-label">
                                        Nama Ayah
                                        <i class="fas fa-user ms-1 text-muted"></i>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('father_name') is-invalid @enderror"
                                        id="father_name"
                                        name="father_name"
                                        value="{{ old('father_name') }}"
                                        placeholder="Nama lengkap ayah">
                                    @error('father_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="father_phone" class="form-label">
                                        No. Telepon Ayah
                                        <i class="fas fa-phone ms-1 text-muted"></i>
                                    </label>
                                    <input type="tel"
                                        class="form-control @error('father_phone') is-invalid @enderror"
                                        id="father_phone"
                                        name="father_phone"
                                        value="{{ old('father_phone') }}"
                                        placeholder="0812-3456-7890"
                                        oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                    @error('father_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 mb-4">
                                <h5 class="border-bottom pb-2">
                                    <i class="fas fa-female text-danger me-2"></i>Data Ibu
                                </h5>

                                <div class="mb-3">
                                    <label for="mother_name" class="form-label">
                                        Nama Ibu
                                        <i class="fas fa-user ms-1 text-muted"></i>
                                    </label>
                                    <input type="text"
                                        class="form-control @error('mother_name') is-invalid @enderror"
                                        id="mother_name"
                                        name="mother_name"
                                        value="{{ old('mother_name') }}"
                                        placeholder="Nama lengkap ibu">
                                    @error('mother_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="mother_phone" class="form-label">
                                        No. Telepon Ibu
                                        <i class="fas fa-phone ms-1 text-muted"></i>
                                    </label>
                                    <input type="tel"
                                        class="form-control @error('mother_phone') is-invalid @enderror"
                                        id="mother_phone"
                                        name="mother_phone"
                                        value="{{ old('mother_phone') }}"
                                        placeholder="0812-3456-7890"
                                        oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                    @error('mother_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Data orang tua diperlukan untuk keperluan administrasi dan kontak darurat.
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary prev-step" data-prev="3">
                                <i class="fas fa-arrow-left me-2"></i>Sebelumnya
                            </button>
                            <button type="button" class="btn btn-primary next-step" data-next="5">
                                Selanjutnya <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 5: Documents -->
                    <div class="step-content" id="step-5">
                        <h4 class="mb-4 text-primary">
                            <i class="fas fa-file-upload me-2"></i>Upload Dokumen
                        </h4>

                        <div class="row">
                            <!-- Required Documents -->
                            <div class="col-md-6 mb-4">
                                <h5 class="text-success mb-3">
                                    <i class="fas fa-file-pdf me-2"></i>Dokumen Wajib (PDF)
                                </h5>

                                <!-- Birth Certificate -->
                                <div class="mb-3">
                                    <label for="birth_certificate" class="form-label">
                                        Akta Kelahiran <span class="text-danger">*</span>
                                        <i class="fas fa-baby ms-1 text-muted"></i>
                                    </label>
                                    <input type="file"
                                        class="form-control @error('birth_certificate') is-invalid @enderror"
                                        id="birth_certificate"
                                        name="birth_certificate"
                                        accept=".pdf"
                                        required>
                                    <div class="form-text">Format: PDF, Maks: 1MB</div>
                                    @error('birth_certificate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- KK -->
                                <div class="mb-3">
                                    <label for="kk" class="form-label">
                                        Kartu Keluarga (KK) <span class="text-danger">*</span>
                                        <i class="fas fa-home ms-1 text-muted"></i>
                                    </label>
                                    <input type="file"
                                        class="form-control @error('kk') is-invalid @enderror"
                                        id="kk"
                                        name="kk"
                                        accept=".pdf"
                                        required>
                                    <div class="form-text">Format: PDF, Maks: 1MB</div>
                                    @error('kk')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- SHUN -->
                                <div class="mb-3">
                                    <label for="shun" class="form-label">
                                        SHUN <span class="text-danger">*</span>
                                        <i class="fas fa-certificate ms-1 text-muted"></i>
                                    </label>
                                    <input type="file"
                                        class="form-control @error('shun') is-invalid @enderror"
                                        id="shun"
                                        name="shun"
                                        accept=".pdf"
                                        required>
                                    <div class="form-text">Surat Hasil Ujian Nasional</div>
                                    @error('shun')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Report Identity -->
                                <div class="mb-3">
                                    <label for="report_identity" class="form-label">
                                        Laporan Identitas <span class="text-danger">*</span>
                                        <i class="fas fa-id-card ms-1 text-muted"></i>
                                    </label>
                                    <input type="file"
                                        class="form-control @error('report_identity') is-invalid @enderror"
                                        id="report_identity"
                                        name="report_identity"
                                        accept=".pdf"
                                        required>
                                    @error('report_identity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Additional Documents -->
                            <div class="col-md-6 mb-4">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-file-image me-2"></i>Dokumen Tambahan
                                </h5>

                                <!-- Last Report Card -->
                                <div class="mb-3">
                                    <label for="last_report_card" class="form-label">
                                        Raport Terakhir <span class="text-danger">*</span>
                                        <i class="fas fa-file-alt ms-1 text-muted"></i>
                                    </label>
                                    <input type="file"
                                        class="form-control @error('last_report_card') is-invalid @enderror"
                                        id="last_report_card"
                                        name="last_report_card"
                                        accept=".pdf"
                                        required>
                                    @error('last_report_card')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Formal Photo -->
                                <div class="mb-3">
                                    <label for="formal_photo" class="form-label">
                                        Foto Formal <span class="text-danger">*</span>
                                        <i class="fas fa-camera ms-1 text-muted"></i>
                                    </label>
                                    <input type="file"
                                        class="form-control @error('formal_photo') is-invalid @enderror"
                                        id="formal_photo"
                                        name="formal_photo"
                                        accept=".jpg,.jpeg,.png"
                                        required>
                                    <div class="form-text">Format: JPG/PNG, Maks: 1MB</div>
                                    @error('formal_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Assignment Letter (Optional) -->
                                <div class="mb-3">
                                    <label for="assignment_letter" class="form-label">
                                        Surat Penugasan
                                        <i class="fas fa-envelope-open-text ms-1 text-muted"></i>
                                    </label>
                                    <input type="file"
                                        class="form-control @error('assignment_letter') is-invalid @enderror"
                                        id="assignment_letter"
                                        name="assignment_letter"
                                        accept=".pdf">
                                    <div class="form-text">Opsional (jika ada)</div>
                                    @error('assignment_letter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- 🔥 PAYMENT PROOF (HANYA UNTUK LEADER) -->
                                @if($role === 'Leader')
                                <div class="mb-3 border border-warning p-3 rounded bg-warning bg-opacity-10">
                                    <h6 class="text-warning mb-3">
                                        <i class="fas fa-money-bill-wave me-2"></i>Bukti Pembayaran Leader
                                        <span class="badge bg-danger ms-2">WAJIB</span>
                                    </h6>

                                    <div class="alert alert-warning mb-3 py-2">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <small class="fw-medium">
                                            <strong>PERHATIAN KAPTEN!</strong> Anda wajib membayar biaya registrasi tim.
                                            Setelah upload bukti bayar, Anda akan mendapatkan <strong>referral code</strong>
                                            yang bisa dibagikan ke anggota tim.
                                        </small>
                                    </div>

                                    <!-- Payment Info -->
                                    <div class="card bg-light mb-3">
                                        <div class="card-body py-2">
                                            <h6 class="card-title mb-2">💳 Informasi Pembayaran:</h6>
                                            <ul class="mb-0 small">
                                                <li><strong>Biaya Registrasi:</strong> Rp 500.000 per tim</li>
                                                <li><strong>Bank:</strong> BCA (Bank Central Asia)</li>
                                                <li><strong>No. Rekening:</strong> 123-456-7890</li>
                                                <li><strong>Atas Nama:</strong> HSBL (High School Basketball League)</li>
                                                <li><strong>Kode Unik:</strong> {{ substr($team->team_id, -3) }}</li>
                                            </ul>
                                        </div>
                                    </div>

                                    <label for="payment_proof" class="form-label fw-bold">
                                        Upload Bukti Transfer <span class="text-danger">*</span>
                                    </label>
                                    <input type="file"
                                        class="form-control @error('payment_proof') is-invalid @enderror"
                                        id="payment_proof"
                                        name="payment_proof"
                                        accept=".jpg,.jpeg,.png,.pdf"
                                        required>
                                    <div class="form-text">
                                        Format: JPG, PNG, atau PDF. Maksimal: 2MB<br>
                                        <small class="text-success fw-medium">
                                            <i class="fas fa-info-circle"></i>
                                            Anggota lain cukup join pakai referral code, tidak perlu bayar lagi.
                                        </small>
                                    </div>
                                    @error('payment_proof')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                @else
                                <!-- ANGGOTA: Tidak perlu upload bukti bayar -->
                                <div class="alert alert-success p-3">
                                    <div class="d-flex">
                                        <i class="fas fa-check-circle fa-2x me-3 text-success"></i>
                                        <div>
                                            <h6 class="alert-heading">✅ Biaya Sudah Dibayar</h6>
                                            <p class="mb-0">
                                                Anda bergabung sebagai <strong>anggota tim</strong>.<br>
                                                <span class="text-success fw-bold">✓ Tidak perlu membayar</span> - biaya sudah ditanggung oleh Leader.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Role Confirmation -->
                        <div class="alert alert-info">
                            <div class="d-flex">
                                <i class="fas fa-user-tag fa-2x me-3"></i>
                                <div>
                                    <h6 class="alert-heading mb-2">Role Pendaftaran</h6>
                                    <p class="mb-0">
                                        Anda mendaftar sebagai: 
                                        <span class="badge {{ $role === 'Leader' ? 'bg-warning' : 'bg-info' }}">
                                            {{ $role === 'Leader' ? 'KAPTEN/LEADER' : 'ANGGOTA/DANCER' }}
                                        </span>
                                    </p>
                                    <small class="text-muted">
                                        @if($role === 'Leader')
                                        Sebagai Leader, Anda akan mendapatkan referral code setelah pembayaran diverifikasi.
                                        @else
                                        Sebagai Anggota, Anda bisa langsung bergabung tanpa biaya.
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Terms Agreement -->
                        <div class="form-check mb-4">
                            <input class="form-check-input @error('terms') is-invalid @enderror"
                                type="checkbox"
                                id="terms"
                                name="terms"
                                required>
                            <label class="form-check-label" for="terms">
                                Saya menyetujui
                                <a href="{{ route('user.download_terms') }}" target="_blank">
                                    Syarat & Ketentuan HSBL
                                </a>
                                dan memastikan data yang diisi adalah benar.
                            </label>
                            @error('terms')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary prev-step" data-prev="4">
                                <i class="fas fa-arrow-left me-2"></i>Sebelumnya
                            </button>
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                @if($role === 'Leader')
                                <i class="fas fa-crown me-2"></i>Daftar sebagai Leader
                                @else
                                <i class="fas fa-paper-plane me-2"></i>Kirim Pendaftaran
                                @endif
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Steps Progress */
    .steps-container {
        position: relative;
        margin-bottom: 3rem;
    }

    .steps {
        display: flex;
        justify-content: space-between;
        position: relative;
    }

    .steps::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 3px;
        background: #e9ecef;
        z-index: 1;
    }

    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
        flex: 1;
    }

    .step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 0.5rem;
        border: 3px solid white;
        transition: all 0.3s;
    }

    .step.active .step-circle {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        transform: scale(1.1);
    }

    .step-label {
        font-size: 0.9rem;
        color: #6c757d;
        text-align: center;
        font-weight: 500;
    }

    .step.active .step-label {
        color: #667eea;
        font-weight: 600;
    }

    /* Step Content */
    .step-content {
        display: none;
        animation: fadeIn 0.5s ease-in-out;
    }

    .step-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Form Styles */
    .form-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #495057;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }

    .form-control:focus+.input-group-text {
        border-color: #667eea;
        background-color: white;
    }

    /* File Upload Custom */
    .form-control[type="file"] {
        padding: 0.375rem;
    }

    .form-control[type="file"]::file-selector-button {
        padding: 0.375rem 0.75rem;
        margin-right: 0.75rem;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        color: #495057;
        cursor: pointer;
    }

    .form-control[type="file"]::file-selector-button:hover {
        background: #e9ecef;
    }

    /* Alert Custom */
    .alert h6 {
        margin-bottom: 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
    // Step Navigation
    document.addEventListener('DOMContentLoaded', function() {
        // Next Step
        document.querySelectorAll('.next-step').forEach(button => {
            button.addEventListener('click', function() {
                const currentStep = document.querySelector('.step-content.active');
                const nextStepId = this.getAttribute('data-next');
                const nextStep = document.getElementById('step-' + nextStepId);

                // Validate current step before proceeding
                if (validateStep(currentStep.id)) {
                    // Update steps progress
                    document.querySelectorAll('.step').forEach(step => {
                        step.classList.remove('active');
                        if (parseInt(step.getAttribute('data-step')) <= parseInt(nextStepId)) {
                            step.classList.add('active');
                        }
                    });

                    // Switch content
                    currentStep.classList.remove('active');
                    nextStep.classList.add('active');

                    // Scroll to top of form
                    nextStep.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Previous Step
        document.querySelectorAll('.prev-step').forEach(button => {
            button.addEventListener('click', function() {
                const currentStep = document.querySelector('.step-content.active');
                const prevStepId = this.getAttribute('data-prev');
                const prevStep = document.getElementById('step-' + prevStepId);

                // Update steps progress
                document.querySelectorAll('.step').forEach(step => {
                    step.classList.remove('active');
                    if (parseInt(step.getAttribute('data-step')) <= parseInt(prevStepId)) {
                        step.classList.add('active');
                    }
                });

                // Switch content
                currentStep.classList.remove('active');
                prevStep.classList.add('active');

                // Scroll to top of form
                prevStep.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
        });

        // Real-time validation for NIK
        const nikInput = document.getElementById('nik');
        if (nikInput) {
            nikInput.addEventListener('blur', function() {
                if (this.value.length === 16) {
                    checkNikAvailability(this.value);
                }
            });
        }

        // Real-time validation for Email
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('blur', function() {
                if (this.value.includes('@')) {
                    checkEmailAvailability(this.value);
                }
            });
        }

        // File size validation
        document.querySelectorAll('input[type="file"]').forEach(input => {
            input.addEventListener('change', function() {
                const file = this.files[0];
                const maxSize = this.getAttribute('accept').includes('.pdf') ? 1024 * 1024 : 2 * 1024 * 1024; // 1MB for PDF, 2MB for images

                if (file && file.size > maxSize) {
                    alert(`File terlalu besar! Maksimal ${maxSize / (1024 * 1024)}MB`);
                    this.value = '';
                }
            });
        });

        // Special validation for Leader payment proof
        const role = "{{ $role }}";
        if (role === 'Leader') {
            const form = document.getElementById('dancerForm');
            form.addEventListener('submit', function(e) {
                const paymentProof = document.getElementById('payment_proof');
                if (!paymentProof || !paymentProof.files || paymentProof.files.length === 0) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Bukti Pembayaran Wajib!',
                        html: `
                            <div class="text-start">
                                <p>Sebagai <strong class="text-warning">KAPTEN/LEADER</strong>, Anda wajib mengupload bukti pembayaran.</p>
                                <div class="alert alert-warning mt-3">
                                    <strong>Informasi Pembayaran:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li><strong>Biaya:</strong> Rp 500.000 per tim</li>
                                        <li><strong>Bank:</strong> BCA 123-456-7890 a/n HSBL</li>
                                        <li><strong>Kode Unik:</strong> {{ substr($team->team_id, -3) }}</li>
                                    </ul>
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'Mengerti',
                        confirmButtonColor: '#dc3545'
                    });
                    paymentProof.focus();
                }
            });
        }
    });

    // Validate step before proceeding
    function validateStep(stepId) {
        let isValid = true;
        const step = document.getElementById(stepId);

        // Get all required inputs in this step
        const requiredInputs = step.querySelectorAll('[required]');

        requiredInputs.forEach(input => {
            if (!input.value.trim() && input.type !== 'file') {
                isValid = false;
                input.classList.add('is-invalid');

                // Add error message
                if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('invalid-feedback')) {
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    errorDiv.textContent = 'Field ini wajib diisi';
                    input.parentNode.appendChild(errorDiv);
                }
            } else {
                input.classList.remove('is-invalid');

                // Remove error message if exists
                const errorDiv = input.nextElementSibling;
                if (errorDiv && errorDiv.classList.contains('invalid-feedback')) {
                    errorDiv.remove();
                }
            }
        });

        return isValid;
    }

    // Check NIK availability via AJAX
    function checkNikAvailability(nik) {
        fetch('/form/dancer/check-nik', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    nik: nik
                })
            })
            .then(response => response.json())
            .then(data => {
                const nikInput = document.getElementById('nik');
                const feedback = document.getElementById('nik-feedback');

                if (data.exists) {
                    nikInput.classList.add('is-invalid');
                    if (feedback) {
                        feedback.textContent = data.message;
                    }
                } else {
                    nikInput.classList.remove('is-invalid');
                    if (feedback) {
                        feedback.textContent = '';
                    }
                }
            });
    }

    // Check Email availability via AJAX
    function checkEmailAvailability(email) {
        fetch('/form/dancer/check-email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    email: email
                })
            })
            .then(response => response.json())
            .then(data => {
                const emailInput = document.getElementById('email');
                const feedback = document.getElementById('email-feedback');

                if (data.exists) {
                    emailInput.classList.add('is-invalid');
                    if (feedback) {
                        feedback.textContent = data.message;
                    }
                } else {
                    emailInput.classList.remove('is-invalid');
                    if (feedback) {
                        feedback.textContent = '';
                    }
                }
            });
    }

    // Form submission
    document.getElementById('dancerForm').addEventListener('submit', function(e) {
        // Final validation
        if (!validateAllSteps()) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Data Belum Lengkap',
                text: 'Harap lengkapi semua data yang wajib diisi.',
                confirmButtonText: 'OK',
                confirmButtonColor: '#dc3545'
            });
            return;
        }

        // Check file sizes
        const files = document.querySelectorAll('input[type="file"]');
        for (const fileInput of files) {
            if (fileInput.files[0]) {
                const file = fileInput.files[0];
                const maxSize = fileInput.getAttribute('accept').includes('.pdf') ? 1024 * 1024 : 2 * 1024 * 1024;

                if (file.size > maxSize) {
                    e.preventDefault();
                    alert(`File ${fileInput.name} terlalu besar! Maksimal ${maxSize / (1024 * 1024)}MB`);
                    return;
                }
            }
        }

        // Show loading
        const submitBtn = this.querySelector('button[type="submit"]');
        const role = "{{ $role }}";
        
        if (role === 'Leader') {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses Pembayaran...';
        } else {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
        }
        submitBtn.disabled = true;
    });

    function validateAllSteps() {
        let isValid = true;

        for (let i = 1; i <= 5; i++) {
            const step = document.getElementById('step-' + i);
            if (step) {
                const requiredInputs = step.querySelectorAll('[required]');

                requiredInputs.forEach(input => {
                    if (!input.value.trim() && input.type !== 'file') {
                        isValid = false;
                        input.classList.add('is-invalid');
                    }
                });
            }
        }

        return isValid;
    }
</script>
@endpush