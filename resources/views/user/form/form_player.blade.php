@extends('user.form.layout')

@section('title', 'Form Pendaftaran Pemain - SBL')

@section('content')
<div class="container py-2">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10 col-lg-8">
            <!-- Progress Steps - Super Compact -->
            <div class="progress-steps mb-2">
                <div class="step-item">
                    <div class="step-circle completed">1</div>
                    <span class="step-label">Data Tim</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item">
                    <div class="step-circle active">2</div>
                    <span class="step-label">Data Pemain</span>
                </div>
                <div class="step-line"></div>
                <div class="step-item">
                    <div class="step-circle">3</div>
                    <span class="step-label">Upload</span>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="card border-0 shadow-sm form-card">
                <div class="card-header bg-white border-0 pt-2 px-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-1">
                        <div>
                            <h6 class="fw-semibold mb-0">
                                <i class="fas fa-user-plus text-primary me-1"></i>
                                @if($role === 'Leader')
                                Form Leader {{ ucfirst($category) }}
                                @else
                                Form Pemain {{ ucfirst($category) }}
                                @endif
                            </h6>
                            <p class="text-muted small mb-0" style="font-size: 10px;">{{ $team->school_name }} • {{ $team->team_category }}</p>
                        </div>
                        <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary btn-sm rounded-pill" style="font-size: 11px; padding: 3px 10px;">
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body p-3">
                    <form id="playerForm" action="{{ route('form.player.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="team_id" value="{{ $team->team_id }}">
                        <input type="hidden" name="category" value="{{ $category }}">
                        <input type="hidden" name="team_role" value="{{ $role }}">

                        <!-- Role Alert - STAY, super compact -->
                        @if($role === 'Leader')
                        <div class="alert alert-warning py-1 px-2 mb-2 stay-alert" style="animation: none; transition: none; font-size: 11px;">
                            <i class="fas fa-crown me-1"></i> Anda Leader - Bertanggung jawab pembayaran & upload jersey
                        </div>
                        @else
                        <div class="alert alert-info py-1 px-2 mb-2 stay-alert" style="animation: none; transition: none; font-size: 11px;">
                            <i class="fas fa-users me-1"></i> Anda Member - Bergabung dengan referral code
                        </div>
                        @endif

                        <!-- Data Pribadi -->
                        <div class="form-section">
                            <div class="section-title mb-1">
                                <div class="title-icon">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <h6 class="mb-0">Data Pribadi</h6>
                            </div>

                            <div class="row g-1">
                                <div class="col-12">
                                    <label class="form-label">NIK <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm @error('nik') is-invalid @enderror"
                                        name="nik" value="{{ old('nik') }}" required
                                        placeholder="16 digit angka" maxlength="16"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    @error('nik')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" required>
                                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Tgl Lahir <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control form-control-sm @error('birthdate') is-invalid @enderror"
                                        name="birthdate" value="{{ old('birthdate') }}" required
                                        max="{{ date('Y-m-d', strtotime('-10 years')) }}">
                                    @error('birthdate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm @error('gender') is-invalid @enderror" name="gender" required>
                                        <option value="">Pilih</option>
                                        @foreach($genderOptions as $gender)
                                        <option value="{{ $gender }}" {{ old('gender') == $gender ? 'selected' : '' }}>
                                            {{ $gender == 'Male' ? 'Laki' : 'Perempuan' }}
                                        </option>
                                        @endforeach
                                    </select>
                                    @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-6">
                                    <label class="form-label">WhatsApp <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control form-control-sm @error('phone') is-invalid @enderror"
                                        name="phone" value="{{ old('phone') }}" required placeholder="081234567890"
                                        oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required>
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Kelas <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm @error('grade') is-invalid @enderror" name="grade" required>
                                        <option value="">Pilih</option>
                                        @foreach($grades as $grade)
                                        <option value="{{ $grade }}" {{ old('grade') == $grade ? 'selected' : '' }}>{{ $grade }}</option>
                                        @endforeach
                                    </select>
                                    @error('grade')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Tahun STTB <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm @error('sttb_year') is-invalid @enderror"
                                        name="sttb_year" value="{{ old('sttb_year') }}" required placeholder="2024" maxlength="4"
                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                    @error('sttb_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Data Fisik & Basket -->
                        <div class="form-section">
                            <div class="section-title mb-1">
                                <div class="title-icon">
                                    <i class="fas fa-running"></i>
                                </div>
                                <h6 class="mb-0">Data Fisik & Basket</h6>
                            </div>

                            <div class="row g-1">
                                <div class="col-6">
                                    <label class="form-label">Tinggi (cm) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control form-control-sm @error('height') is-invalid @enderror"
                                        name="height" value="{{ old('height') }}" required min="100" max="250" placeholder="170">
                                    @error('height')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Berat (kg) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control form-control-sm @error('weight') is-invalid @enderror"
                                        name="weight" value="{{ old('weight') }}" required min="30" max="150" step="0.5" placeholder="65">
                                    @error('weight')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Ukuran Kaos <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm @error('tshirt_size') is-invalid @enderror" name="tshirt_size" required>
                                        <option value="">Pilih</option>
                                        @foreach($tshirtSizes as $size)
                                        <option value="{{ $size }}" {{ old('tshirt_size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                    @error('tshirt_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Ukuran Sepatu <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm @error('shoes_size') is-invalid @enderror" name="shoes_size" required>
                                        <option value="">Pilih</option>
                                        @foreach($shoesSizes as $size)
                                        <option value="{{ $size }}" {{ old('shoes_size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                    @error('shoes_size')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                @if($category !== 'dancer')
                                <div class="col-6">
                                    <label class="form-label">Posisi Basket <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm @error('basketball_position') is-invalid @enderror" name="basketball_position" required>
                                        <option value="">Pilih</option>
                                        @foreach($basketballPositions as $position)
                                        <option value="{{ $position }}" {{ old('basketball_position') == $position ? 'selected' : '' }}>{{ $position }}</option>
                                        @endforeach
                                    </select>
                                    @error('basketball_position')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-6">
                                    <label class="form-label">Nomor Jersey <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control form-control-sm @error('jersey_number') is-invalid @enderror"
                                        name="jersey_number" value="{{ old('jersey_number') }}" required min="0" max="99" placeholder="0-99">
                                    @error('jersey_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Data Sosial Media -->
                        <div class="form-section">
                            <div class="section-title mb-1">
                                <div class="title-icon">
                                    <i class="fab fa-instagram"></i>
                                </div>
                                <h6 class="mb-0">Media Sosial</h6>
                            </div>

                            <div class="row g-1">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Instagram</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light">@</span>
                                        <input type="text" class="form-control form-control-sm @error('instagram') is-invalid @enderror"
                                            name="instagram" value="{{ old('instagram') }}" placeholder="username">
                                    </div>
                                    @error('instagram')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">TikTok</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light">@</span>
                                        <input type="text" class="form-control form-control-sm @error('tiktok') is-invalid @enderror"
                                            name="tiktok" value="{{ old('tiktok') }}" placeholder="username">
                                    </div>
                                    @error('tiktok')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Data Orang Tua -->
                        <div class="form-section">
                            <div class="section-title mb-1">
                                <div class="title-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h6 class="mb-0">Data Orang Tua</h6>
                            </div>

                            <div class="row g-1">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Nama Ayah <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm @error('father_name') is-invalid @enderror"
                                        name="father_name" value="{{ old('father_name') }}" required>
                                    @error('father_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">No. Telepon Ayah <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control form-control-sm @error('father_phone') is-invalid @enderror"
                                        name="father_phone" value="{{ old('father_phone') }}" required placeholder="081234567890"
                                        oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                    @error('father_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">Nama Ibu <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm @error('mother_name') is-invalid @enderror"
                                        name="mother_name" value="{{ old('mother_name') }}" required>
                                    @error('mother_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">No. Telepon Ibu <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control form-control-sm @error('mother_phone') is-invalid @enderror"
                                        name="mother_phone" value="{{ old('mother_phone') }}" required placeholder="081234567890"
                                        oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                    @error('mother_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Dokumen Wajib dengan Thumbnail Preview - VERSION FIXED -->
                        <div class="form-section">
                            <div class="section-title mb-1">
                                <div class="title-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <h6 class="mb-0">Dokumen Wajib</h6>
                                <span class="badge bg-primary bg-opacity-10 text-primary ms-1" style="font-size: 9px;">6</span>
                            </div>

                            <div class="row g-1">
                                @php
                                $documents = [
                                ['name' => 'birth_certificate', 'label' => 'Akta Kelahiran', 'accept' => '.pdf', 'icon' => 'fa-file-pdf', 'color' => 'danger'],
                                ['name' => 'kk', 'label' => 'Kartu Keluarga', 'accept' => '.pdf', 'icon' => 'fa-file-pdf', 'color' => 'danger'],
                                ['name' => 'shun', 'label' => 'SHUN', 'accept' => '.pdf', 'icon' => 'fa-file-pdf', 'color' => 'danger'],
                                ['name' => 'last_report_card', 'label' => 'Raport Terakhir', 'accept' => '.pdf', 'icon' => 'fa-file-pdf', 'color' => 'danger'],
                                ['name' => 'formal_photo', 'label' => 'Foto Formal', 'accept' => 'image/*', 'icon' => 'fa-file-image', 'color' => 'primary'],
                                ['name' => 'assignment_letter', 'label' => 'Surat Tugas', 'accept' => '.pdf', 'icon' => 'fa-file-pdf', 'color' => 'danger']
                                ];
                                @endphp

                                @foreach($documents as $doc)
                                <div class="col-12 col-md-6">
                                    <div class="doc-upload-box p-2" data-doc="{{ $doc['name'] }}" id="doc-box-{{ $doc['name'] }}">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <i class="fas {{ $doc['icon'] }} text-{{ $doc['color'] }}" style="font-size: 12px;"></i>
                                            <span class="fw-medium" style="font-size: 11px;">{{ $doc['label'] }} <span class="text-danger">*</span></span>
                                        </div>
                                        <input type="file" class="form-control form-control-sm doc-input"
                                            name="{{ $doc['name'] }}" accept="{{ $doc['accept'] }}" required
                                            data-doc="{{ $doc['name'] }}" id="input-{{ $doc['name'] }}">
                                        <small class="text-muted d-block mt-1" style="font-size: 9px;">Max 1MB, {{ $doc['accept'] == 'image/*' ? 'JPG/PNG' : 'PDF' }}</small>

                                        <!-- Thumbnail Preview Area -->
                                        <div class="thumbnail-container mt-1" id="thumbnail-{{ $doc['name'] }}" style="display: none;">
                                            <div class="thumbnail-wrapper">
                                                <div class="thumbnail-file" id="thumb-file-{{ $doc['name'] }}"></div>
                                                <div class="thumbnail-info">
                                                    <span class="thumb-name" id="thumb-name-{{ $doc['name'] }}"></span>
                                                    <button type="button" class="btn-remove-thumb" data-doc="{{ $doc['name'] }}" title="Hapus">
                                                        <i class="fas fa-times-circle"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Khusus Leader -->
                        @if($role === 'Leader')
                        <div class="form-section border-top pt-2 mt-1">
                            <div class="section-title mb-2">
                                <div class="title-icon bg-warning bg-opacity-10 text-warning">
                                    <i class="fas fa-crown"></i>
                                </div>
                                <h6 class="mb-0">Khusus Leader</h6>
                            </div>

                            @if($category !== 'dancer')
                            <div class="mb-3">
                                <label class="form-label" style="font-size: 11px;">Jersey Tim <span class="text-danger">*</span></label>
                                <div class="row g-2">
                                    <!-- Jersey Home -->
                                    <div class="col-4">
                                        <div class="jersey-card" data-jersey="home">
                                            <div class="jersey-label">Home Jersey</div>
                                            <div class="jersey-preview-area" id="jersey-preview-home">
                                                <div class="jersey-placeholder">
                                                    <i class="fas fa-camera"></i>
                                                    <span>Belum Upload</span>
                                                </div>
                                            </div>
                                            <input type="file" class="jersey-file-input" name="jersey_home" accept="image/*" data-jersey="home" style="display: none;">
                                        </div>
                                    </div>

                                    <!-- Jersey Away -->
                                    <div class="col-4">
                                        <div class="jersey-card" data-jersey="away">
                                            <div class="jersey-label">Away jersey</div>
                                            <div class="jersey-preview-area" id="jersey-preview-away">
                                                <div class="jersey-placeholder">
                                                    <i class="fas fa-camera"></i>
                                                    <span>Belum Upload</span>
                                                </div>
                                            </div>
                                            <input type="file" class="jersey-file-input" name="jersey_away" accept="image/*" data-jersey="away" style="display: none;">
                                        </div>
                                    </div>

                                    <!-- Jersey Alternate -->
                                    <div class="col-4">
                                        <div class="jersey-card" data-jersey="alternate">
                                            <div class="jersey-label">Alternate jersey</div>
                                            <div class="jersey-preview-area" id="jersey-preview-alternate">
                                                <div class="jersey-placeholder">
                                                    <i class="fas fa-camera"></i>
                                                    <span>Belum Upload</span>
                                                </div>
                                            </div>
                                            <input type="file" class="jersey-file-input" name="jersey_alternate" accept="image/*" data-jersey="alternate" style="display: none;">
                                        </div>
                                    </div>
                                </div>

                                <!-- Caption minimal upload 1 jersey - STAY, no animation -->
<div class="jersey-caption mt-2 mb-0" style="font-size: 9px; color: #856404; background: #fef3c7; padding: 4px 8px; border-radius: 4px; border-left: 3px solid #f59e0b; animation: none; transition: none;">
    <i class="fas fa-info-circle me-1" style="animation: none;"></i>
    <strong>Catatan:</strong> Minimal upload 1 foto jersey (Home/Away/Alternate)
</div>
                            </div>
                            @endif

                            <div>
                                <label class="form-label" style="font-size: 11px;">Bukti Transfer <span class="text-danger">*</span></label>
                                <div class="doc-upload-box p-2">
                                    <input type="file" class="form-control form-control-sm" name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" required>
                                    <small class="text-muted d-block mt-1" style="font-size: 9px;">Max 2MB, JPG/PNG/PDF</small>
                                    <div class="thumbnail-container mt-1" id="thumbnail-payment" style="display: none;">
                                        <div class="thumbnail-wrapper">
                                            <div class="thumbnail-file" id="thumb-file-payment"></div>
                                            <div class="thumbnail-info">
                                                <span class="thumb-name" id="thumb-name-payment"></span>
                                                <button type="button" class="btn-remove-thumb" data-doc="payment" title="Hapus">
                                                    <i class="fas fa-times-circle"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Terms Agreement -->
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="terms" name="terms" required style="margin-top: 2px;">
                            <label class="form-check-label" for="terms" style="font-size: 10px;">
                                Saya menyetujui Syarat & Ketentuan dan memastikan semua data yang diisi adalah benar.
                            </label>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-1 pt-2 border-top mt-2">
                            <small class="text-muted" style="font-size: 10px;">
                                <i class="fas fa-shield-alt text-primary me-1"></i>Data aman
                            </small>
                            <div class="d-flex gap-1">
                                <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary btn-sm rounded-pill" style="font-size: 11px; padding: 4px 12px;">Batal</a>
                                <button type="submit" class="btn btn-primary btn-sm rounded-pill" style="font-size: 11px; padding: 4px 12px;">
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
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content text-center p-2">
            <div class="spinner-border text-primary" style="width: 1.2rem; height: 1.2rem;"></div>
            <p class="mb-0 small mt-1">Memproses...</p>
        </div>
    </div>
</div>

<style>
    /* Progress Steps */
    .progress-steps {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .step-item {
        text-align: center;
    }

    .step-circle {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 11px;
        margin: 0 auto 2px;
    }

    .step-circle.active {
        background: #4361ee;
        color: white;
    }

    .step-circle.completed {
        background: #10b981;
        color: white;
    }

    .step-label {
        font-size: 9px;
        color: #6c757d;
    }

    .step-line {
        width: 30px;
        height: 2px;
        background: #e9ecef;
    }

    @media (max-width: 576px) {
        .step-line {
            width: 20px;
        }

        .step-label {
            font-size: 7px;
        }

        .step-circle {
            width: 24px;
            height: 24px;
            font-size: 9px;
        }
    }

    /* Form Card */
    .form-card {
        border-radius: 12px;
        overflow: hidden;
    }

    /* Form Section */
    .form-section {
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #e9ecef;
    }

    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }

    .section-title {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .title-icon {
        width: 24px;
        height: 24px;
        background: rgba(67, 97, 238, 0.1);
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4361ee;
    }

    .title-icon i {
        font-size: 11px;
    }

    .section-title h6 {
        font-weight: 600;
        font-size: 12px;
        margin: 0;
    }

    /* Form Labels & Inputs */
    .form-label {
        font-size: 10px;
        font-weight: 500;
        margin-bottom: 2px;
        color: #2b2d42;
    }

    .form-control,
    .form-select {
        border-radius: 5px;
        font-size: 11px;
        padding: 4px 8px;
    }

    .form-control-sm,
    .form-select-sm {
        padding: 4px 6px;
        font-size: 11px;
        border-radius: 5px;
    }

    .input-group-sm>.form-control,
    .input-group-sm>.input-group-text {
        padding: 4px 6px;
        font-size: 11px;
        border-radius: 5px;
    }

    .input-group-text {
        padding: 4px 8px;
        font-size: 10px;
    }

    /* Alert */
    .alert {
        border-radius: 6px;
    }

    .stay-alert {
        animation: none !important;
        transition: none !important;
        transform: none !important;
        opacity: 1 !important;
        display: block !important;
        visibility: visible !important;
    }

    /* Doc Upload Box */
    .doc-upload-box {
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 6px;
        background: #f8f9fa;
    }

    /* Thumbnail Preview - Seperti Google Drive */
    .thumbnail-container {
        margin-top: 6px;
    }

    .thumbnail-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 6px;
        padding: 4px 8px;
    }

    .thumbnail-file {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        border-radius: 4px;
        flex-shrink: 0;
    }

    .thumbnail-file i {
        font-size: 18px;
    }

    .thumbnail-file img {
        width: 28px;
        height: 28px;
        object-fit: cover;
        border-radius: 3px;
    }

    .thumbnail-info {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }

    .thumb-name {
        font-size: 10px;
        color: #2b2d42;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        flex: 1;
    }

    .btn-remove-thumb {
        background: none;
        border: none;
        color: #dc2626;
        cursor: pointer;
        padding: 2px;
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.7;
    }

    .btn-remove-thumb:hover {
        opacity: 1;
    }

    /* Jersey Thumbnail */
    .jersey-thumb {
        margin-top: 4px;
    }

    .jersey-thumb img {
        width: 100%;
        max-height: 50px;
        object-fit: cover;
        border-radius: 4px;
        border: 1px solid #e9ecef;
    }

    /* Button */
    .btn-primary {
        background: #4361ee;
        border: none;
    }

    .btn-primary:hover {
        background: #3a56d4;
    }

    /* Row gap */
    .g-1 {
        --bs-gutter-y: 0.25rem;
        --bs-gutter-x: 0.25rem;
    }

    /* Mobile */
    @media (max-width: 768px) {
        .card-body {
            padding: 12px !important;
        }

        .card-header {
            padding: 8px 12px !important;
        }

        .doc-upload-box {
            padding: 6px;
        }

        .thumbnail-wrapper {
            padding: 3px 6px;
        }

        .thumbnail-file {
            width: 28px;
            height: 28px;
        }

        .thumb-name {
            font-size: 9px;
        }
    }

    /* Jersey Card Styles */
    .jersey-card {
        background: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .jersey-card:hover {
        border-color: #4361ee;
        box-shadow: 0 2px 8px rgba(67, 97, 238, 0.15);
    }

    .jersey-label {
        background: #e9ecef;
        padding: 4px 6px;
        font-size: 9px;
        font-weight: 500;
        text-align: center;
        color: #2b2d42;
        border-bottom: 1px solid #e9ecef;
    }

    .jersey-preview-area {
        min-height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
    }

    .jersey-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 12px 4px;
        color: #adb5bd;
    }

    .jersey-placeholder i {
        font-size: 20px;
        margin-bottom: 4px;
    }

    .jersey-placeholder span {
        font-size: 8px;
    }

    .jersey-preview-image {
        width: 100%;
        height: auto;
        max-height: 70px;
        object-fit: cover;
    }

    /* Remove button on jersey */
    .jersey-remove-btn {
        position: absolute;
        top: 2px;
        right: 2px;
        background: rgba(220, 53, 69, 0.9);
        border: none;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 10px;
        cursor: pointer;
        z-index: 10;
    }

    .jersey-remove-btn:hover {
        background: #dc2626;
    }

    .jersey-card {
        position: relative;
    }
</style>

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('playerForm');
        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));

        // Lock stay alerts - prevent any hiding
        function lockStayAlerts() {
            document.querySelectorAll('.stay-alert').forEach(function(alert) {
                alert.style.display = 'block';
                alert.style.opacity = '1';
                alert.style.visibility = 'visible';
                alert.classList.remove('d-none', 'fade', 'hide', 'invisible');
            });
        }
        lockStayAlerts();
        setInterval(lockStayAlerts, 500);

        // Set max birthdate
        const birthdateInput = document.querySelector('input[name="birthdate"]');
        if (birthdateInput) {
            const today = new Date();
            const minDate = new Date(today.setFullYear(today.getFullYear() - 10));
            birthdateInput.max = minDate.toISOString().split('T')[0];
        }

        // ==================== FUNGSI THUMBNAIL ====================

        // Fungsi membuat thumbnail untuk file
        function createFileThumbnail(file, docName, isImage = false) {
            const container = document.getElementById(`thumbnail-${docName}`);
            if (!container) return;

            const fileDiv = document.getElementById(`thumb-file-${docName}`);
            const nameSpan = document.getElementById(`thumb-name-${docName}`);

            if (fileDiv) {
                if (isImage || file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        fileDiv.innerHTML = `<img src="${e.target.result}" alt="preview" style="width:28px; height:28px; object-fit:cover; border-radius:3px;">`;
                    };
                    reader.readAsDataURL(file);
                } else {
                    // Icon untuk file non-gambar
                    const iconMap = {
                        'application/pdf': 'fa-file-pdf',
                        'application/msword': 'fa-file-word',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document': 'fa-file-word',
                        'application/vnd.ms-excel': 'fa-file-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet': 'fa-file-excel'
                    };
                    const icon = iconMap[file.type] || 'fa-file';
                    fileDiv.innerHTML = `<i class="fas ${icon} text-secondary" style="font-size: 18px;"></i>`;
                }
            }

            if (nameSpan) {
                let fileName = file.name;
                if (fileName.length > 25) fileName = fileName.substring(0, 22) + '...';
                nameSpan.textContent = fileName;
            }

            container.style.display = 'block';
        }

        // Fungsi remove file dan thumbnail
        function removeFileAndThumbnail(docName) {
            const fileInput = document.getElementById(`input-${docName}`);
            const thumbnail = document.getElementById(`thumbnail-${docName}`);

            if (fileInput) {
                fileInput.value = ''; // Reset file input
            }

            if (thumbnail) {
                thumbnail.style.display = 'none';
                // Kosongkan thumbnail file
                const fileDiv = document.getElementById(`thumb-file-${docName}`);
                if (fileDiv) fileDiv.innerHTML = '';

                const nameSpan = document.getElementById(`thumb-name-${docName}`);
                if (nameSpan) nameSpan.textContent = '';
            }

            // Untuk payment proof (special case)
            if (docName === 'payment') {
                const paymentInputField = document.querySelector('input[name="payment_proof"]');
                if (paymentInputField) paymentInputField.value = '';
            }
        }

        // ==================== DOCUMENT UPLOAD (6 Dokumen Wajib) ====================

        // Setup file inputs untuk dokumen wajib
        const docNames = ['birth_certificate', 'kk', 'shun', 'last_report_card', 'formal_photo', 'assignment_letter'];

        docNames.forEach(function(docName) {
            const fileInput = document.getElementById(`input-${docName}`);
            if (fileInput) {
                // Buang event listener lama dengan clone
                const newInput = fileInput.cloneNode(true);
                fileInput.parentNode.replaceChild(newInput, fileInput);

                newInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        const file = this.files[0];
                        const isImage = file.type.startsWith('image/');
                        createFileThumbnail(file, docName, isImage);
                    }
                });
            }
        });

        // ==================== TOMBOL HAPUS - EVENT DELEGATION ====================

        // Event delegation untuk semua tombol hapus (termasuk yang dibuat dinamis)
        document.addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.btn-remove-thumb');
            if (removeBtn) {
                e.preventDefault();
                e.stopPropagation();
                const docName = removeBtn.getAttribute('data-doc');
                if (docName) {
                    removeFileAndThumbnail(docName);
                }
                return false;
            }
        });

        // ==================== JERSEY PREVIEW (Click to upload) ====================

        // Setup jersey cards untuk upload via klik
        document.querySelectorAll('.jersey-card').forEach(function(card) {
            const jerseyType = card.dataset.jersey;
            const fileInput = card.querySelector('.jersey-file-input');
            const previewArea = document.getElementById(`jersey-preview-${jerseyType}`);

            if (!fileInput) return;

            // Klik pada card untuk trigger file input
            card.addEventListener('click', function(e) {
                // Jangan trigger jika klik tombol hapus
                if (e.target.classList.contains('jersey-remove-btn') ||
                    e.target.parentElement?.classList?.contains('jersey-remove-btn')) {
                    return;
                }
                fileInput.click();
            });

            // Handle file selection
            fileInput.addEventListener('change', function(e) {
                e.stopPropagation();

                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        // Hapus placeholder dan tampilkan gambar
                        previewArea.innerHTML = `
                    <div style="position: relative; width: 100%;">
                        <img src="${e.target.result}" class="jersey-preview-image" alt="Jersey ${jerseyType}">
                        <button type="button" class="jersey-remove-btn" data-jersey="${jerseyType}" title="Hapus">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;

                        // Tambah event listener ke tombol hapus
                        const removeBtn = previewArea.querySelector('.jersey-remove-btn');
                        if (removeBtn) {
                            removeBtn.addEventListener('click', function(btnEvent) {
                                btnEvent.stopPropagation();
                                // Reset file input
                                fileInput.value = '';
                                // Kembalikan ke placeholder
                                previewArea.innerHTML = `
                            <div class="jersey-placeholder">
                                <i class="fas fa-camera"></i>
                                <span>Belum Upload</span>
                            </div>
                        `;
                            });
                        }
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // Untuk jersey yang sudah ada sebelumnya (jika ada data lama)
        function initExistingJersey() {
            // Function ini bisa digunakan jika ingin menampilkan jersey yang sudah diupload sebelumnya
            // Saat ini kosong karena form baru
        }

        initExistingJersey();

        // ==================== PAYMENT PROOF THUMBNAIL ====================

        const paymentInputField = document.querySelector('input[name="payment_proof"]');
        if (paymentInputField) {
            const newPaymentInput = paymentInputField.cloneNode(true);
            paymentInputField.parentNode.replaceChild(newPaymentInput, paymentInputField);

            newPaymentInput.addEventListener('change', function() {
                const thumbnailId = 'thumbnail-payment';

                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const isImage = file.type.startsWith('image/');

                    let container = document.getElementById(thumbnailId);
                    if (!container) {
                        const parent = this.closest('.doc-upload-box');
                        const newContainer = document.createElement('div');
                        newContainer.id = thumbnailId;
                        newContainer.className = 'thumbnail-container mt-1';
                        newContainer.innerHTML = `
                        <div class="thumbnail-wrapper">
                            <div class="thumbnail-file" id="thumb-file-payment"></div>
                            <div class="thumbnail-info">
                                <span class="thumb-name" id="thumb-name-payment"></span>
                                <button type="button" class="btn-remove-thumb" data-doc="payment" title="Hapus">
                                    <i class="fas fa-times-circle"></i>
                                </button>
                            </div>
                        </div>
                    `;
                        parent.appendChild(newContainer);
                        container = newContainer;
                    }

                    // Buat thumbnail
                    const fileDiv = document.getElementById('thumb-file-payment');
                    const nameSpan = document.getElementById('thumb-name-payment');

                    if (fileDiv) {
                        if (isImage) {
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                fileDiv.innerHTML = `<img src="${e.target.result}" alt="preview" style="width:28px; height:28px; object-fit:cover; border-radius:3px;">`;
                            };
                            reader.readAsDataURL(file);
                        } else {
                            fileDiv.innerHTML = `<i class="fas fa-file-pdf text-danger" style="font-size: 18px;"></i>`;
                        }
                    }

                    if (nameSpan) {
                        let fileName = file.name;
                        if (fileName.length > 25) fileName = fileName.substring(0, 22) + '...';
                        nameSpan.textContent = fileName;
                    }

                    container.style.display = 'block';
                }
            });
        }

        // ==================== FORM VALIDATION ====================

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Required fields validation
            const requiredFields = ['nik', 'name', 'birthdate', 'gender', 'phone', 'email', 'grade', 'sttb_year',
                'height', 'weight', 'tshirt_size', 'shoes_size', 'father_name', 'father_phone', 'mother_name', 'mother_phone'
            ];

            @if($category !== 'dancer')
            requiredFields.push('jersey_number');
            @endif

            for (const field of requiredFields) {
                const input = document.querySelector(`[name="${field}"]`);
                if (!input || !input.value.trim()) {
                    Swal.fire('Error', `Field ${field} wajib diisi`, 'error');
                    input?.focus();
                    return;
                }
            }

            // Validate NIK (16 digits)
            const nik = document.querySelector('input[name="nik"]').value;
            if (!/^\d{16}$/.test(nik)) {
                Swal.fire('Error', 'NIK harus 16 digit angka', 'error');
                return;
            }

            // Validate email format
            const email = document.querySelector('input[name="email"]').value;
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                Swal.fire('Error', 'Email tidak valid', 'error');
                return;
            }

            // Validate phone number (10-13 digits)
            const phone = document.querySelector('input[name="phone"]').value.replace(/\D/g, '');
            if (phone.length < 10 || phone.length > 13) {
                Swal.fire('Error', 'Nomor WhatsApp harus 10-13 digit', 'error');
                return;
            }

            // Validate documents (6 dokumen wajib)
            const requiredDocs = ['birth_certificate', 'kk', 'shun', 'last_report_card', 'formal_photo'];
            for (const doc of requiredDocs) {
                const input = document.querySelector(`input[name="${doc}"]`);
                if (!input || !input.files || !input.files.length) {
                    Swal.fire('Error', `Dokumen ${doc} wajib diupload`, 'error');
                    return;
                }
            }

            // Leader specific validation
            @if($role === 'Leader')
            const paymentProof = document.querySelector('input[name="payment_proof"]');
            if (!paymentProof || !paymentProof.files || !paymentProof.files.length) {
                Swal.fire('Error', 'Bukti transfer wajib diupload', 'error');
                return;
            }

            @if($category !== 'dancer')
            const jerseyHome = document.querySelector('input[name="jersey_home"]');
            const jerseyAway = document.querySelector('input[name="jersey_away"]');
            const jerseyAlt = document.querySelector('input[name="jersey_alternate"]');
            const hasJersey = (jerseyHome?.files?.length > 0) ||
                (jerseyAway?.files?.length > 0) ||
                (jerseyAlt?.files?.length > 0);
            if (!hasJersey) {
                Swal.fire('Error', 'Upload minimal 1 foto jersey tim', 'error');
                return;
            }
            @endif
            @endif

            // Terms agreement
            if (!document.getElementById('terms').checked) {
                Swal.fire('Error', 'Harap setujui syarat & ketentuan', 'error');
                return;
            }

            // Confirmation dialog
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Pastikan semua data sudah benar',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Kirim!',
                cancelButtonText: 'Cek Lagi',
                confirmButtonColor: '#4361ee'
            }).then((result) => {
                if (result.isConfirmed) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Memproses...';
                    loadingModal.show();
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
@endsection