@extends('user.form.layout')

@section('title', 'Form Pendaftaran Pemain - HSBL')

@section('content')
<div class="container py-3 px-lg-4 px-md-3 px-sm-2">
    <!-- Role Indicator -->
    @if($role === 'Leader')
    <div class="alert alert-warning border-warning bg-warning-subtle mb-3 py-2 px-2 shadow-sm mx-auto" style="max-width: 780px;">
        <div class="d-flex align-items-center">
            <div class="bg-warning text-white rounded-circle p-1 me-2" style="width: 30px; height: 30px;">
                <i class="fas fa-crown"></i>
            </div>
            <div class="flex-grow-1">
                <h6 class="mb-0 fw-bold small text-dark">Anda adalah Leader Tim!</h6>
                <p class="mb-0 text-muted" style="font-size: 0.75rem;">Bertanggung jawab untuk pembayaran dan upload jersey tim</p>
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

    <!-- Main Form Card -->
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 800px; border-radius: 8px; width: 100%;">
        <!-- Card Header -->
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

        <!-- Form Content -->
        <div class="card-body p-3 px-md-3 px-sm-2">
            <form id="playerForm" action="{{ route('form.player.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team->team_id }}">
                <input type="hidden" name="category" value="{{ $category }}">
                <input type="hidden" name="team_role" value="{{ $role }}">

                <!-- SECTION 1: Data Pribadi -->
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

                    <!-- Row 3 -->
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

                <!-- SECTION 2: Data Fisik & Basket -->
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon-wrapper bg-success bg-opacity-10 p-1 rounded me-2" style="width: 28px; height: 28px;">
                            <i class="fas fa-running text-success" style="font-size: 0.8rem;"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark small">Data Fisik & Basket</h6>
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
                                Posisi Basket <span class="text-danger">*</span>
                            </label>
                            <select class="form-select form-select-sm @error('basketball_position') is-invalid @enderror"
                                id="basketball_position" name="basketball_position" required>
                                <option value="">Pilih Posisi</option>
                                @foreach($basketballPositions as $position)
                                <option value="{{ $position }}" {{ old('basketball_position') == $position ? 'selected' : '' }}>
                                    {{ $position }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-1">
                            <label class="form-label fw-semibold" style="font-size: 0.75rem;">
                                Nomor Jersey <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                class="form-control form-control-sm @error('jersey_number') is-invalid @enderror"
                                id="jersey_number" name="jersey_number" 
                                value="{{ old('jersey_number') }}" 
                                required
                                min="0" max="99" 
                                placeholder="0-99"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                    </div>
                    @endif
                </div>

                <!-- SECTION 3: Data Orang Tua -->
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

                <!-- SECTION 4: Dokumen Wajib -->
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
                                    Surat Penugasan <small class="text-muted"></small>
                                </label>
                                <input type="file" class="form-control form-control-sm @error('assignment_letter') is-invalid @enderror"
                                    name="assignment_letter" accept=".pdf" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 🔥🔥🔥 SECTION 5: KHUSUS LEADER - Upload Jersey Tim & Pembayaran -->
                @if($role === 'Leader' && $category !== 'dancer')
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon-wrapper bg-warning bg-opacity-10 p-1 rounded me-2" style="width: 28px; height: 28px;">
                            <i class="fas fa-tshirt text-warning" style="font-size: 0.8rem;"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark small">Upload Jersey Tim</h6>
                        <span class="badge bg-warning ms-2 small">Hanya Leader</span>
                    </div>
                    
                    <div class="row gx-2 gy-2">
                        <!-- Jersey Home -->
                        <div class="col-md-4 mb-1">
                            <div class="card border-warning h-100">
                                <div class="card-body p-2">
                                    <label class="form-label fw-bold small text-warning mb-1">
                                        <i class="fas fa-home"></i> Jersey Home
                                    </label>
                                    <div class="jersey-preview-container mb-2" id="preview-home" style="display: none;">
                                        <img id="img-preview-home" src="#" alt="Preview Jersey Home" 
                                            class="img-fluid rounded border" style="max-height: 120px; width: 100%; object-fit: contain;">
                                    </div>
                                    <input type="file" 
                                        class="form-control form-control-sm jersey-upload @error('jersey_home') is-invalid @enderror"
                                        id="jersey_home" name="jersey_home" 
                                        accept=".jpg,.jpeg,.png" 
                                        data-preview="img-preview-home"
                                        data-container="preview-home">
                                    <small class="text-muted d-block mt-1">Foto jersey kandang</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Jersey Away -->
                        <div class="col-md-4 mb-1">
                            <div class="card border-warning h-100">
                                <div class="card-body p-2">
                                    <label class="form-label fw-bold small text-warning mb-1">
                                        <i class="fas fa-plane"></i> Jersey Away
                                    </label>
                                    <div class="jersey-preview-container mb-2" id="preview-away" style="display: none;">
                                        <img id="img-preview-away" src="#" alt="Preview Jersey Away" 
                                            class="img-fluid rounded border" style="max-height: 120px; width: 100%; object-fit: contain;">
                                    </div>
                                    <input type="file" 
                                        class="form-control form-control-sm jersey-upload @error('jersey_away') is-invalid @enderror"
                                        id="jersey_away" name="jersey_away" 
                                        accept=".jpg,.jpeg,.png"
                                        data-preview="img-preview-away"
                                        data-container="preview-away">
                                    <small class="text-muted d-block mt-1">Foto jersey tandang</small>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Jersey Alternate -->
                        <div class="col-md-4 mb-1">
                            <div class="card border-warning h-100">
                                <div class="card-body p-2">
                                    <label class="form-label fw-bold small text-warning mb-1">
                                        <i class="fas fa-tshirt"></i> Jersey Alternate
                                    </label>
                                    <div class="jersey-preview-container mb-2" id="preview-alternate" style="display: none;">
                                        <img id="img-preview-alternate" src="#" alt="Preview Jersey Alternate" 
                                            class="img-fluid rounded border" style="max-height: 120px; width: 100%; object-fit: contain;">
                                    </div>
                                    <input type="file" 
                                        class="form-control form-control-sm jersey-upload @error('jersey_alternate') is-invalid @enderror"
                                        id="jersey_alternate" name="jersey_alternate" 
                                        accept=".jpg,.jpeg,.png"
                                        data-preview="img-preview-alternate"
                                        data-container="preview-alternate">
                                    <small class="text-muted d-block mt-1">Foto jersey alternatif</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-2 py-1 px-2 small">
                        <i class="fas fa-info-circle"></i> Upload minimal 1 foto jersey tim (boleh salah satu saja).
                    </div>
                </div>
                @endif

                <!-- SECTION 6: Pembayaran & Submit -->
                <div class="mb-3">
                    <div class="d-flex align-items-center mb-2">
                        <div class="icon-wrapper bg-success bg-opacity-10 p-1 rounded me-2" style="width: 28px; height: 28px;">
                            <i class="fas fa-credit-card text-success" style="font-size: 0.8rem;"></i>
                        </div>
                        <h6 class="mb-0 fw-bold text-dark small">Pembayaran & Submit</h6>
                    </div>
                    
                    <div class="row gx-1 gy-1">
                        @if($role === 'Leader')
                        <div class="col-md-12 mb-1">
                            <div class="card border-warning">
                                <div class="card-body p-2">
                                    <h6 class="fw-bold text-warning mb-1 small">
                                        <i class="fas fa-money-bill-wave"></i> Bukti Pembayaran <span class="badge bg-danger ms-1">WAJIB</span>
                                    </h6>
                                    <label class="form-label fw-semibold small mb-1">
                                        Upload Bukti Transfer <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control form-control-sm @error('payment_proof') is-invalid @enderror"
                                        name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" required>
                                    <small class="text-muted d-block mt-1">
                                        Maks 2MB. Format: JPG, PNG, PDF
                                    </small>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-md-12 mb-1">
                            <div class="card border-success">
                                <div class="card-body p-2 text-center">
                                    <i class="fas fa-check-circle text-success" style="font-size: 1.2rem;"></i>
                                    <p class="mb-0 small fw-bold">Biaya Pendaftaran sudah dibayar oleh Leader Tim</p>
                                    <p class="mb-0 text-muted small">Anda tidak perlu upload bukti pembayaran</p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Terms Agreement -->
                <div class="form-check mb-3">
                    <input class="form-check-input @error('terms') is-invalid @enderror"
                        type="checkbox" id="terms" name="terms" required>
                    <label class="form-check-label fw-medium small" for="terms" style="font-size: 0.8rem;">
                        Saya menyetujui Syarat & Ketentuan dan memastikan semua data yang diisi adalah benar.
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
    
    // Nomor Jersey validation
    const jerseyNumber = document.getElementById('jersey_number');
    if (jerseyNumber) {
        jerseyNumber.addEventListener('input', function() {
            if (this.value < 0) this.value = 0;
            if (this.value > 99) this.value = 99;
        });
    }

    // 🔥 PREVIEW FOTO JERSEY
    const jerseyUploads = document.querySelectorAll('.jersey-upload');
    jerseyUploads.forEach(input => {
        input.addEventListener('change', function(e) {
            const previewId = this.dataset.preview;
            const containerId = this.dataset.container;
            const previewContainer = document.getElementById(containerId);
            const previewImg = document.getElementById(previewId);
            
            const file = e.target.files[0];
            
            if (file) {
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    previewImg.src = event.target.result;
                    previewContainer.style.display = 'block';
                }
                
                reader.readAsDataURL(file);
            } else {
                previewContainer.style.display = 'none';
                previewImg.src = '#';
            }
        });
    });

    // File size validation
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            let maxSize = 2 * 1024 * 1024; // 2MB default
            
            if (this.name.includes('jersey')) {
                maxSize = 2 * 1024 * 1024; // 2MB untuk jersey
            } else if (this.name === 'payment_proof') {
                maxSize = 2 * 1024 * 1024; // 2MB untuk payment
            } else {
                maxSize = 1 * 1024 * 1024; // 1MB untuk dokumen lain
            }
            
            if (file.size > maxSize) {
                alert(`File terlalu besar! Maksimal ${maxSize / (1024 * 1024)}MB`);
                this.value = '';
                
                // Hide preview jika ada
                if (this.classList.contains('jersey-upload')) {
                    const containerId = this.dataset.container;
                    const previewContainer = document.getElementById(containerId);
                    if (previewContainer) {
                        previewContainer.style.display = 'none';
                    }
                }
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
            } else if (input.type === 'file' && input.hasAttribute('required')) {
                // Untuk file yang required
                if (!input.value) {
                    isValid = false;
                    input.classList.add('is-invalid');
                }
            } else {
                input.classList.remove('is-invalid');
            }
        });

        // Validasi khusus: Untuk Leader Basket, minimal upload 1 foto jersey
        @if($role === 'Leader' && $category !== 'dancer')
        const jerseyHome = document.querySelector('input[name="jersey_home"]');
        const jerseyAway = document.querySelector('input[name="jersey_away"]');
        const jerseyAlt = document.querySelector('input[name="jersey_alternate"]');
        
        const hasJersey = (jerseyHome && jerseyHome.value) || 
                         (jerseyAway && jerseyAway.value) || 
                         (jerseyAlt && jerseyAlt.value);
        
        if (!hasJersey) {
            isValid = false;
            alert('Sebagai Leader, Anda wajib upload minimal 1 foto jersey tim!');
            
            // Highlight semua input jersey
            [jerseyHome, jerseyAway, jerseyAlt].forEach(input => {
                if (input) input.classList.add('is-invalid');
            });
        }
        @endif

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
/* ... existing styles ... */

/* Jersey Preview */
.jersey-preview-container {
    background: #f8f9fa;
    padding: 0.5rem;
    border-radius: 4px;
    margin-bottom: 0.5rem;
}

.jersey-preview-container img {
    border: 1px solid #dee2e6;
    border-radius: 4px;
}

/* Card border-warning highlight */
.card.border-warning {
    border-width: 2px;
    transition: all 0.2s ease;
}

.card.border-warning:hover {
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
}

/* Invalid state untuk jersey */
.is-invalid {
    border-color: #dc3545 !important;
}

.is-invalid:focus {
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
}

/* Responsive untuk jersey section */
@media (max-width: 768px) {
    .col-md-4 {
        margin-bottom: 0.5rem;
    }
    
    .jersey-preview-container img {
        max-height: 80px !important;
    }
}
</style>
@endpush
@endsection