@extends('user.form.layout')

@section('title', 'Form Pendaftaran Pemain - HSBL')

@section('content')
<div class="container py-4"> <!-- py-5 → py-4 -->
    <div class="row justify-content-center">
        <div class="col-md-10">
            <!-- Header - UKURAN STANDAR -->
            <div class="text-center mb-4"> <!-- mb-5 → mb-4 -->
                <span class="badge bg-soft-primary text-primary px-3 py-1 mb-2 rounded-pill" style="font-size: 0.8rem;">
                    🏀 HSBL Registration
                </span>
                <h2 class="fw-bold text-dark mb-2" style="font-size: 1.8rem;"> <!-- h1 display-5 → h2 -->
                    @if($role === 'Leader')
                    Form Leader {{ ucfirst($category) }}
                    @else
                    Form Pemain {{ ucfirst($category) }}
                    @endif
                </h2>
                <p class="text-muted" style="font-size: 0.95rem;">{{ $team->school_name }} • 
                    @php
                        $displayCategory = $team->team_category;
                        if (str_contains(strtolower($displayCategory), 'basket')) {
                            $displayCategory = 'Basket ' . ucfirst($category);
                        }
                    @endphp
                    {{ $displayCategory }}
                </p>
            </div>

            <!-- Role Indicator - UKURAN STANDAR -->
            <div class="row justify-content-center mb-3"> <!-- mb-4 → mb-3 -->
                <div class="col-md-8">
                    @if($role === 'Leader')
                    <div class="card border-0 shadow-sm bg-soft-warning">
                        <div class="card-body py-2 px-3"> <!-- py-3 → py-2 -->
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-orange me-2" style="width: 40px; height: 40px;"> <!-- me-3 → me-2, 50px → 40px -->
                                    <i class="fas fa-crown text-white" style="font-size: 1.2rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-semibold text-dark mb-0" style="font-size: 0.9rem;">Anda adalah Leader Tim!</h6>
                                    <p class="text-muted small mb-0" style="font-size: 0.7rem;">Bertanggung jawab untuk pembayaran dan upload jersey tim</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="card border-0 shadow-sm bg-soft-teal">
                        <div class="card-body py-2 px-3">
                            <div class="d-flex align-items-center">
                                <div class="icon-circle bg-gradient-teal me-2" style="width: 40px; height: 40px;">
                                    <i class="fas fa-users text-white" style="font-size: 1.2rem;"></i>
                                </div>
                                <div>
                                    <h6 class="fw-semibold text-dark mb-0" style="font-size: 0.9rem;">Anda adalah Member Tim</h6>
                                    <p class="text-muted small mb-0" style="font-size: 0.7rem;">Bergabung dengan referral code</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Main Form Card - UKURAN STANDAR -->
            <div class="card border-0 shadow mx-auto" style="max-width: 950px; border-radius: 16px;"> <!-- shadow-lg → shadow, 20px → 16px -->
                <!-- Card Header - UKURAN STANDAR -->
                <div class="card-header bg-white border-0 pt-3 px-3"> <!-- pt-4 → pt-3, px-4 → px-3 -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="fw-semibold mb-0" style="color: #2b2d42; font-size: 1rem;"> <!-- mb-1 dihapus -->
                                <i class="fas fa-user-plus text-primary me-1" style="font-size: 1rem;"></i> <!-- me-2 → me-1, 1.2rem → 1rem -->
                                @if($role === 'Leader')
                                Form Leader {{ ucfirst($category) }}
                                @else
                                Form Pemain {{ ucfirst($category) }}
                                @endif
                            </h5>
                            <p class="text-muted small mb-0" style="font-size: 0.7rem;">Lengkapi data di bawah untuk mendaftar sebagai pemain</p>
                        </div>
                        <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-2" style="font-size: 0.75rem;"> <!-- px-3 → px-2 -->
                            <i class="fas fa-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>

                <!-- Alert Messages - UKURAN STANDAR -->
                @if($errors->any())
                <div class="px-3 pt-2"> <!-- px-4 → px-3, pt-3 → pt-2 -->
                    <div class="alert alert-danger alert-dismissible fade show py-1 px-2 border-0 bg-soft-danger" role="alert" style="border-radius: 8px; font-size: 0.7rem;"> <!-- py-2 → py-1, border radius 12px → 8px -->
                        <div class="d-flex align-items-start">
                            <i class="fas fa-times-circle me-1 mt-0" style="color: #f94144; font-size: 0.8rem;"></i> <!-- me-2 → me-1 -->
                            <div>
                                <small class="d-block fw-semibold">Terdapat {{ $errors->count() }} kesalahan:</small>
                                @foreach($errors->all() as $error)
                                <small class="d-block">{{ $error }}</small>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Form Content - UKURAN STANDAR -->
                <div class="card-body p-3"> <!-- p-4 → p-3 -->
                    <form id="playerForm" action="{{ route('form.player.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="team_id" value="{{ $team->team_id }}">
                        <input type="hidden" name="category" value="{{ $category }}">
                        <input type="hidden" name="team_role" value="{{ $role }}">

                        <!-- Quick Guide - UKURAN STANDAR -->
                        <div class="bg-light p-2 rounded-2 mb-3" style="background: #f8f9fa;"> <!-- p-3 → p-2, mb-4 → mb-3 -->
                            <div class="d-flex align-items-center">
                                <i class="fas fa-lightbulb text-primary me-1" style="font-size: 0.8rem;"></i> <!-- me-2 → me-1, 1rem → 0.8rem -->
                                <span class="fw-semibold me-2" style="font-size: 0.7rem;">Tips:</span>
                                <span class="text-muted me-1" style="font-size: 0.65rem;">1. Data Pribadi</span>
                                <span class="text-muted me-1" style="font-size: 0.65rem;">→</span>
                                <span class="text-muted me-1" style="font-size: 0.65rem;">2. Data Fisik</span>
                                <span class="text-muted me-1" style="font-size: 0.65rem;">→</span>
                                <span class="text-muted" style="font-size: 0.65rem;">3. Upload Dokumen</span>
                            </div>
                        </div>

                        <!-- LAYOUT 2 KOLOM - spacing standar -->
                        <div class="row g-3"> <!-- g-4 → g-3 -->
                            <!-- KOLOM KIRI: Data Pribadi & Data Fisik -->
                            <div class="col-md-6">
                                <div class="border-end pe-2"> <!-- pe-3 → pe-2 -->
                                    <!-- Data Pribadi - UKURAN STANDAR -->
                                    <div class="mb-3"> <!-- mb-4 → mb-3 -->
                                        <div class="d-flex align-items-center mb-2"> <!-- mb-3 → mb-2 -->
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-2 d-flex align-items-center justify-content-center me-1"
                                                style="width: 24px; height: 24px;"> <!-- 32px → 24px -->
                                                <i class="fas fa-id-card" style="font-size: 0.8rem;"></i> <!-- 1rem → 0.8rem -->
                                            </div>
                                            <h6 class="fw-semibold mb-0" style="color: #2b2d42; font-size: 0.9rem;">Data Pribadi</h6>
                                        </div>

                                        <div class="row g-2"> <!-- g-3 → g-2 -->
                                            <div class="col-12">
                                                <label class="form-label mb-0" style="font-size: 0.7rem;">NIK <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"
                                                    id="nik" name="nik" value="{{ old('nik') }}" required
                                                    placeholder="16 digit" maxlength="16"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label mb-0" style="font-size: 0.7rem;">Nama Lengkap <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"
                                                    id="name" name="name" value="{{ old('name') }}" required
                                                    placeholder="Nama lengkap">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label mb-0" style="font-size: 0.7rem;">Tanggal Lahir <span class="text-danger">*</span></label>
                                                <input type="date" class="form-control form-control-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"
                                                    id="birthdate" name="birthdate" value="{{ old('birthdate') }}" required
                                                    max="{{ date('Y-m-d', strtotime('-10 years')) }}">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label mb-0" style="font-size: 0.7rem;">Jenis Kelamin <span class="text-danger">*</span></label>
                                                <select class="form-select form-select-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"
                                                    id="gender" name="gender" required>
                                                    <option value="">Pilih</option>
                                                    @foreach($genderOptions as $gender)
                                                    <option value="{{ $gender }}" {{ old('gender') == $gender ? 'selected' : '' }}>{{ $gender }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label mb-0" style="font-size: 0.7rem;">WhatsApp <span class="text-danger">*</span></label>
                                                <input type="tel" class="form-control form-control-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"
                                                    id="phone" name="phone" value="{{ old('phone') }}" required
                                                    placeholder="081234567890"
                                                    oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label mb-0" style="font-size: 0.7rem;">Email <span class="text-danger">*</span></label>
                                                <input type="email" class="form-control form-control-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"
                                                    id="email" name="email" value="{{ old('email') }}" required
                                                    placeholder="email@example.com">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label mb-0" style="font-size: 0.7rem;">Kelas <span class="text-danger">*</span></label>
                                                <select class="form-select form-select-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"
                                                    id="grade" name="grade" required>
                                                    <option value="">Pilih</option>
                                                    @foreach($grades as $grade)
                                                    <option value="{{ $grade }}" {{ old('grade') == $grade ? 'selected' : '' }}>{{ $grade }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label mb-0" style="font-size: 0.7rem;">Tahun STTB <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"
                                                    id="sttb_year" name="sttb_year" value="{{ old('sttb_year') }}" required
                                                    placeholder="2024" maxlength="4"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Data Fisik - UKURAN STANDAR -->
                                    <div class="mb-3"> <!-- mb-4 → mb-3 -->
                                        <div class="d-flex align-items-center mb-2"> <!-- mb-3 → mb-2 -->
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-2 d-flex align-items-center justify-content-center me-1"
                                                style="width: 24px; height: 24px;">
                                                <i class="fas fa-running" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <h6 class="fw-semibold mb-0" style="color: #2b2d42; font-size: 0.9rem;">Data Fisik & Basket</h6>
                                        </div>

                                        <div class="row g-2"> <!-- g-3 → g-2 -->
                                            <div class="col-6">
                                                <label class="form-label mb-0" style="font-size: 0.7rem;">Tinggi (cm) <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"
                                                    id="height" name="height" value="{{ old('height') }}" required
                                                    min="100" max="250" step="1" placeholder="170">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label mb-0" style="font-size: 0.7rem;">Berat (kg) <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"
                                                    id="weight" name="weight" value="{{ old('weight') }}" required
                                                    min="30" max="150" step="0.5" placeholder="65.5">
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label mb-0" style="font-size: 0.7rem;">Ukuran Kaos <span class="text-danger">*</span></label>
                                                <select class="form-select form-select-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"
                                                    id="tshirt_size" name="tshirt_size" required>
                                                    <option value="">Pilih</option>
                                                    @foreach($tshirtSizes as $size)
                                                    <option value="{{ $size }}" {{ old('tshirt_size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label mb-0" style="font-size: 0.7rem;">Ukuran Sepatu <span class="text-danger">*</span></label>
                                                <select class="form-select form-select-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"
                                                    id="shoes_size" name="shoes_size" required>
                                                    <option value="">Pilih</option>
                                                    @foreach($shoesSizes as $size)
                                                    <option value="{{ $size }}" {{ old('shoes_size') == $size ? 'selected' : '' }}>{{ $size }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            @if($category !== 'dancer')
                                            <div class="col-6">
                                                <label class="form-label mb-0" style="font-size: 0.7rem;">Posisi Basket <span class="text-danger">*</span></label>
                                                <select class="form-select form-select-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"
                                                    id="basketball_position" name="basketball_position" required>
                                                    <option value="">Pilih Posisi</option>
                                                    @foreach($basketballPositions as $position)
                                                    <option value="{{ $position }}" {{ old('basketball_position') == $position ? 'selected' : '' }}>{{ $position }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label mb-0" style="font-size: 0.7rem;">Nomor Jersey <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;"
                                                    id="jersey_number" name="jersey_number" value="{{ old('jersey_number') }}" required
                                                    min="0" max="99" placeholder="0-99"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- KOLOM KANAN: Data Orang Tua & Upload Dokumen -->
                            <div class="col-md-6">
                                <div class="ps-2"> <!-- ps-3 → ps-2 -->
                                    <!-- Data Orang Tua - UKURAN STANDAR -->
                                    <div class="mb-3"> <!-- mb-4 → mb-3 -->
                                        <div class="d-flex align-items-center mb-2"> <!-- mb-3 → mb-2 -->
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-2 d-flex align-items-center justify-content-center me-1"
                                                style="width: 24px; height: 24px;">
                                                <i class="fas fa-users" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <h6 class="fw-semibold mb-0" style="color: #2b2d42; font-size: 0.9rem;">Data Orang Tua</h6>
                                        </div>

                                        <div class="row g-2"> <!-- g-3 → g-2 -->
                                            <div class="col-12">
                                                <div class="card border-0 shadow-sm p-2"> <!-- p-3 → p-2 -->
                                                    <h6 class="fw-bold text-primary mb-2" style="font-size: 0.75rem;">Ayah</h6> <!-- mb-3 → mb-2 -->
                                                    <div class="row g-1"> <!-- g-2 → g-1 -->
                                                        <div class="col-12">
                                                            <input type="text" class="form-control form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;"
                                                                id="father_name" name="father_name" value="{{ old('father_name') }}" placeholder="Nama ayah" required>
                                                        </div>
                                                        <div class="col-12">
                                                            <input type="tel" class="form-control form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;"
                                                                id="father_phone" name="father_phone" value="{{ old('father_phone') }}" placeholder="No. telepon" required
                                                                oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="card border-0 shadow-sm p-2"> <!-- p-3 → p-2 -->
                                                    <h6 class="fw-bold text-primary mb-2" style="font-size: 0.75rem;">Ibu</h6>
                                                    <div class="row g-1"> <!-- g-2 → g-1 -->
                                                        <div class="col-12">
                                                            <input type="text" class="form-control form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;"
                                                                id="mother_name" name="mother_name" value="{{ old('mother_name') }}" placeholder="Nama ibu" required>
                                                        </div>
                                                        <div class="col-12">
                                                            <input type="tel" class="form-control form-control-sm" style="font-size: 0.7rem; padding: 0.2rem 0.4rem;"
                                                                id="mother_phone" name="mother_phone" value="{{ old('mother_phone') }}" placeholder="No. telepon" required
                                                                oninput="this.value = this.value.replace(/[^0-9+]/g, '')">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Dokumen Wajib - UKURAN STANDAR -->
                                    <div class="mb-3"> <!-- mb-4 → mb-3 -->
                                        <div class="d-flex align-items-center mb-2"> <!-- mb-3 → mb-2 -->
                                            <div class="bg-primary bg-opacity-10 text-primary rounded-2 d-flex align-items-center justify-content-center me-1"
                                                style="width: 24px; height: 24px;">
                                                <i class="fas fa-file-upload" style="font-size: 0.8rem;"></i>
                                            </div>
                                            <h6 class="fw-semibold mb-0" style="color: #2b2d42; font-size: 0.9rem;">Dokumen Wajib</h6>
                                            <span class="badge bg-soft-primary text-primary ms-2 px-2" style="font-size: 0.6rem;">6 Dokumen</span> <!-- ms-3 → ms-2, px-3 → px-2 -->
                                        </div>

                                        <!-- Info Upload - STAY -->
                                        <div class="upload-info-alert mb-2 p-1" style="background: #e7f3ff; border-radius: 6px; font-size: 0.65rem; border-left: 3px solid #0ea5e9; padding: 0.4rem 0.6rem !important;">
                                            <i class="fas fa-hand-pointer me-1" style="font-size: 0.65rem;"></i>
                                            <span>Klik kotak untuk upload file</span>
                                        </div>

                                        <!-- Grid Dokumen - UKURAN STANDAR -->
                                        <div class="row g-1"> <!-- g-2 → g-1 -->
                                            @php
                                            $docs = [
                                                ['id' => 'birth', 'name' => 'Akta', 'file' => 'birth_certificate', 'icon' => 'fa-file-pdf', 'color' => 'text-danger'],
                                                ['id' => 'kk', 'name' => 'KK', 'file' => 'kk', 'icon' => 'fa-file-pdf', 'color' => 'text-danger'],
                                                ['id' => 'shun', 'name' => 'SHUN', 'file' => 'shun', 'icon' => 'fa-file-pdf', 'color' => 'text-danger'],
                                                ['id' => 'raport', 'name' => 'Raport', 'file' => 'last_report_card', 'icon' => 'fa-file-pdf', 'color' => 'text-danger'],
                                                ['id' => 'foto', 'name' => 'Foto', 'file' => 'formal_photo', 'icon' => 'fa-file-image', 'color' => 'text-primary'],
                                                ['id' => 'surat', 'name' => 'Surat', 'file' => 'assignment_letter', 'icon' => 'fa-file-pdf', 'color' => 'text-danger']
                                            ];
                                            @endphp

                                            @foreach($docs as $doc)
                                            <div class="col-4">
                                                <div class="file-card" id="file-card-{{ $doc['id'] }}" onclick="document.getElementById('{{ $doc['file'] }}').click()" style="padding: 0.6rem 0.25rem;">
                                                    <div class="file-preview" id="file-preview-{{ $doc['id'] }}" style="width: 40px; height: 40px;">
                                                        <i class="fas {{ $doc['icon'] }} {{ $doc['color'] }}" style="font-size: 1.5rem;"></i>
                                                    </div>
                                                    <div class="file-info" style="margin-bottom: 0.2rem;">
                                                        <span class="file-name" id="file-name-{{ $doc['id'] }}" style="font-size: 0.65rem;">{{ $doc['name'] }}</span>
                                                        <span class="file-meta" id="file-meta-{{ $doc['id'] }}" style="font-size: 0.5rem;">1MB</span>
                                                    </div>
                                                    <input type="file" class="file-input" id="{{ $doc['file'] }}" 
                                                           name="{{ $doc['file'] }}" 
                                                           accept="{{ $doc['id'] === 'foto' ? '.jpg,.jpeg,.png' : '.pdf' }}" required
                                                           onchange="handleFileSelect(this, '{{ $doc['id'] }}')">
                                                    <div class="file-status" id="file-status-{{ $doc['id'] }}" style="font-size: 0.5rem;">
                                                        <i class="fas fa-exclamation-circle" style="font-size: 0.5rem;"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>

                                        <!-- Info Upload Footer -->
                                        <div class="upload-info-footer mt-2 p-1" style="background: #f0f9ff; border-radius: 6px; font-size: 0.6rem; border: 1px dashed #7dd3fc; padding: 0.3rem 0.6rem !important;">
                                            <i class="fas fa-info-circle me-1" style="font-size: 0.6rem;"></i>
                                            <span>PDF maks 1MB, Foto maks 1MB</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION KHUSUS LEADER - UKURAN STANDAR -->
                        @if($role === 'Leader')
                        <div class="mt-3 pt-2 border-top"> <!-- mt-4 → mt-3, pt-3 → pt-2 -->
                            <div class="d-flex align-items-center mb-2"> <!-- mb-3 → mb-2 -->
                                <div class="bg-warning bg-opacity-10 text-warning rounded-2 d-flex align-items-center justify-content-center me-1"
                                    style="width: 24px; height: 24px;"> <!-- 32px → 24px -->
                                    <i class="fas fa-crown" style="font-size: 0.8rem;"></i> <!-- 1rem → 0.8rem -->
                                </div>
                                <h6 class="fw-semibold mb-0" style="color: #2b2d42; font-size: 0.9rem;">Khusus Leader</h6>
                                <span class="badge bg-soft-warning text-warning ms-2 px-2" style="font-size: 0.6rem;">Wajib</span> <!-- ms-3 → ms-2, px-3 → px-2 -->
                            </div>

                            <!-- Info Upload Leader -->
                            <div class="upload-info-alert mb-2 p-1" style="background: #e7f3ff; border-radius: 6px; font-size: 0.65rem; border-left: 3px solid #0ea5e9; padding: 0.4rem 0.6rem !important;">
                                <i class="fas fa-hand-pointer me-1"></i>
                                <span>Klik kotak untuk upload</span>
                            </div>

                            <div class="row g-2"> <!-- g-4 → g-2 -->
                                @if($category !== 'dancer')
                                <!-- Jersey Tim -->
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm p-2 h-100"> <!-- p-3 → p-2 -->
                                        <label class="form-label mb-1" style="font-size: 0.7rem;">
                                            <i class="fas fa-tshirt text-warning me-1"></i>Jersey Tim <span class="text-danger">*</span>
                                        </label>
                                        <div class="row g-1"> <!-- g-2 → g-1 -->
                                            @php
                                            $jerseys = [
                                                ['type' => 'home', 'name' => 'Home', 'id' => 'jersey_home'],
                                                ['type' => 'away', 'name' => 'Away', 'id' => 'jersey_away'],
                                                ['type' => 'alt', 'name' => 'Alt', 'id' => 'jersey_alternate']
                                            ];
                                            @endphp
                                            @foreach($jerseys as $jersey)
                                            <div class="col-4">
                                                <div class="jersey-card" id="jersey-card-{{ $jersey['type'] }}" onclick="document.getElementById('{{ $jersey['id'] }}').click()" style="padding: 0.4rem 0.15rem; min-height: 80px;">
                                                    <div class="jersey-preview" id="jersey-preview-{{ $jersey['type'] }}" style="width: 30px; height: 30px;">
                                                        <i class="fas fa-tshirt" style="font-size: 1rem;"></i>
                                                    </div>
                                                    <div class="jersey-info">
                                                        <span class="jersey-name" style="font-size: 0.6rem;">{{ $jersey['name'] }}</span>
                                                        <span class="jersey-size" id="jersey-size-{{ $jersey['type'] }}" style="font-size: 0.5rem;">-</span>
                                                    </div>
                                                    <input type="file" class="jersey-input" id="{{ $jersey['id'] }}" 
                                                           name="{{ $jersey['id'] }}" 
                                                           accept=".jpg,.jpeg,.png"
                                                           onchange="handleJerseySelect(this, '{{ $jersey['type'] }}')">
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <small class="text-muted d-block mt-1" style="font-size: 0.55rem;">Minimal 1 foto (maks 2MB)</small>
                                    </div>
                                </div>
                                @endif

                                <!-- Bukti Transfer -->
                                <div class="col-md-6">
                                    <div class="card border-0 shadow-sm p-2 h-100"> <!-- p-3 → p-2 -->
                                        <label class="form-label mb-1" style="font-size: 0.7rem;">
                                            <i class="fas fa-credit-card text-success me-1"></i>Bukti Transfer <span class="text-danger">*</span>
                                        </label>
                                        <div class="file-card" id="file-card-payment" onclick="document.getElementById('payment_proof').click()" style="padding: 0.6rem 0.25rem;">
                                            <div class="file-preview" id="file-preview-payment" style="width: 40px; height: 40px;">
                                                <i class="fas fa-file-invoice text-success" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <div class="file-info" style="margin-bottom: 0.2rem;">
                                                <span class="file-name" id="file-name-payment" style="font-size: 0.65rem;">Bukti</span>
                                                <span class="file-meta" id="file-meta-payment" style="font-size: 0.5rem;">2MB</span>
                                            </div>
                                            <input type="file" class="file-input" id="payment_proof" 
                                                   name="payment_proof" accept=".jpg,.jpeg,.png,.pdf" required
                                                   onchange="handleFileSelect(this, 'payment')">
                                            <div class="file-status" id="file-status-payment" style="font-size: 0.5rem;">
                                                <i class="fas fa-exclamation-circle" style="font-size: 0.5rem;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Info Upload Footer untuk Leader -->
                            <div class="upload-info-footer mt-2 p-1" style="background: #f0f9ff; border-radius: 6px; font-size: 0.6rem; border: 1px dashed #7dd3fc; padding: 0.3rem 0.6rem !important;">
                                <i class="fas fa-info-circle me-1"></i>
                                <span>Maks 2MB per file</span>
                            </div>
                        </div>
                        @else
                        <div class="mt-3 pt-2 border-top"> <!-- mt-4 → mt-3, pt-3 → pt-2 -->
                            <div class="card border-0 shadow-sm p-2 text-center bg-soft-teal"> <!-- p-3 → p-2 -->
                                <i class="fas fa-check-circle text-success mb-1" style="font-size: 1.2rem;"></i> <!-- fa-2x dihapus -->
                                <h6 class="fw-semibold mb-0" style="font-size: 0.8rem;">Biaya sudah dibayar Leader</h6>
                                <p class="text-muted mb-0" style="font-size: 0.65rem;">Anda tidak perlu upload bukti</p>
                            </div>
                        </div>
                        @endif

                        <!-- Terms Agreement - UKURAN STANDAR -->
                        <div class="form-check mt-2 mb-2"> <!-- mt-4 → mt-2, mb-4 → mb-2 -->
                            <input class="form-check-input @error('terms') is-invalid @enderror"
                                type="checkbox" id="terms" name="terms" required style="transform: scale(0.8);">
                            <label class="form-check-label" for="terms" style="font-size: 0.7rem;">
                                Saya menyetujui Syarat & Ketentuan
                            </label>
                        </div>

                        <!-- Submit Buttons - UKURAN STANDAR -->
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top"> <!-- pt-3 → pt-2 -->
                            <small class="text-muted" style="font-size: 0.6rem;">
                                <i class="fas fa-shield-alt text-primary me-1"></i>Data aman
                            </small>
                            <div>
                                <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary btn-sm rounded-pill px-2 me-1" style="font-size: 0.7rem; padding: 0.2rem 0.8rem;"> <!-- px-4 → px-2, me-2 → me-1 -->
                                    Batal
                                </a>
                                <button type="submit" id="submitBtn" class="btn btn-primary btn-sm rounded-pill px-3" style="font-size: 0.7rem; padding: 0.2rem 1rem;"> <!-- px-4 → px-3 -->
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
    </div>
</div>

<!-- Loading Modal - UKURAN STANDAR -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow" style="border-radius: 12px;"> <!-- 16px → 12px -->
            <div class="modal-body text-center p-3"> <!-- p-4 → p-3 -->
                <div class="spinner-border text-primary mb-2" style="width: 1.5rem; height: 1.5rem;" role="status"></div> <!-- 2rem → 1.5rem, mb-3 → mb-2 -->
                <h6 class="fw-semibold mb-0" style="font-size: 0.8rem;">Memproses...</h6>
                <small class="text-muted" style="font-size: 0.65rem;">Mohon tunggu</small>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Colors - TETAP */
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
    
    .bg-soft-danger {
        background-color: rgba(249, 65, 68, 0.1);
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
        border-radius: 1.25rem; /* 1.5rem → 1.25rem */
        overflow: hidden;
    }
    
    .card-body {
        border-radius: 1.25rem;
    }
    
    .border-end {
        border-right: 1px solid #e9ecef !important;
        height: 100%;
    }

    /* File Cards - UKURAN STANDAR */
    .file-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 10px; /* 16px → 10px */
        padding: 0.6rem 0.25rem;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        height: 100%;
        transition: all 0.2s ease;
    }

    .file-card:hover {
        border-color: #4361ee;
        transform: translateY(-1px);
    }

    .file-card.uploaded {
        border-color: #10b981;
        background: #f0fdf4;
    }

    .file-preview {
        width: 40px; /* 56px → 40px */
        height: 40px;
        border-radius: 8px; /* 12px → 8px */
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.25rem; /* 0.5rem → 0.25rem */
    }

    .file-preview i {
        font-size: 1.5rem; /* 2rem → 1.5rem */
    }

    .file-preview i.fa-file-pdf { color: #dc2626; }
    .file-preview i.fa-file-image { color: #3b82f6; }
    .file-preview i.fa-file-invoice { color: #10b981; }

    .file-preview.has-image {
        padding: 0;
        overflow: hidden;
    }

    .file-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }

    .file-info {
        width: 100%;
        margin-bottom: 0.2rem; /* 0.5rem → 0.2rem */
    }

    .file-name {
        display: block;
        font-weight: 500; /* 600 → 500 */
        font-size: 0.65rem; /* 0.8rem → 0.65rem */
        color: #2b2d42;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .file-meta {
        display: block;
        font-size: 0.5rem; /* 0.6rem → 0.5rem */
        color: #6c757d;
    }

    .file-input {
        display: none;
    }

    .file-status {
        font-size: 0.5rem; /* 0.65rem → 0.5rem */
        color: #f59e0b;
    }

    .file-status i {
        font-size: 0.5rem;
    }

    .file-card.uploaded .file-status {
        color: #10b981;
    }

    /* Jersey Cards - UKURAN STANDAR */
    .jersey-card {
        background: white;
        border: 1px solid #e9ecef;
        border-radius: 8px; /* 12px → 8px */
        padding: 0.4rem 0.15rem;
        cursor: pointer;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: all 0.2s ease;
        height: 100%;
        min-height: 80px; /* 120px → 80px */
    }

    .jersey-card:hover {
        border-color: #f59e0b;
        background: #fffaf0;
    }

    .jersey-card.uploaded {
        border-color: #10b981;
        background: #f0fdf4;
    }

    .jersey-preview {
        width: 30px; /* 48px → 30px */
        height: 30px;
        border-radius: 6px; /* 10px → 6px */
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 0.2rem; /* 0.5rem → 0.2rem */
    }

    .jersey-preview i {
        font-size: 1rem; /* 1.8rem → 1rem */
        color: #f59e0b;
    }

    .jersey-card.uploaded .jersey-preview i {
        color: #10b981;
    }

    .jersey-preview.has-image {
        padding: 0;
        overflow: hidden;
    }

    .jersey-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 6px;
    }

    .jersey-info {
        width: 100%;
    }

    .jersey-name {
        display: block;
        font-weight: 500; /* 600 → 500 */
        font-size: 0.6rem; /* 0.8rem → 0.6rem */
        color: #2b2d42;
    }

    .jersey-size {
        display: block;
        font-size: 0.5rem; /* 0.65rem → 0.5rem */
        color: #6c757d;
    }

    .jersey-input {
        display: none;
    }

    /* Upload Info */
    .upload-info-alert i, .upload-info-footer i {
        font-size: 0.65rem;
    }

    /* Form Controls */
    .form-control-sm, .form-select-sm {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
        height: auto;
    }

    .form-label {
        font-size: 0.7rem;
        margin-bottom: 0.1rem;
        color: #2b2d42;
        font-weight: 500;
    }

    .btn-sm {
        padding: 0.2rem 0.8rem;
        font-size: 0.7rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
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
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
        
        .ps-2 {
            padding-left: 0 !important;
        }
        
        h2 {
            font-size: 1.5rem !important;
        }
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('playerForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    const nikInput = document.getElementById('nik');
    const emailInput = document.getElementById('email');
    const birthdateInput = document.getElementById('birthdate');
    const sttbYearInput = document.getElementById('sttb_year');
    const phoneInput = document.getElementById('phone');
    const fatherPhone = document.getElementById('father_phone');
    const motherPhone = document.getElementById('mother_phone');
    const jerseyNumber = document.getElementById('jersey_number');
    
    // Set max birthdate
    const today = new Date();
    const minBirthDate = new Date();
    minBirthDate.setFullYear(today.getFullYear() - 10);
    birthdateInput.max = minBirthDate.toISOString().split('T')[0];
    
    // Validasi STTB tahun
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

    // Jersey number validation
    if (jerseyNumber) {
        jerseyNumber.addEventListener('input', function() {
            if (this.value < 0) this.value = 0;
            if (this.value > 99) this.value = 99;
        });
    }

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

    // ===== FUNGSI HANDLE FILE SELECT =====
    window.handleFileSelect = function(input, fileType) {
        const file = input.files[0];
        const previewDiv = document.getElementById(`file-preview-${fileType}`);
        const nameSpan = document.getElementById(`file-name-${fileType}`);
        const metaSpan = document.getElementById(`file-meta-${fileType}`);
        const statusDiv = document.getElementById(`file-status-${fileType}`);
        const cardDiv = document.getElementById(`file-card-${fileType}`);
        
        if (file) {
            let maxSize = fileType === 'payment' ? 2 * 1024 * 1024 : 1024 * 1024;
            
            if (file.size > maxSize) {
                alert(`File terlalu besar! Maksimal ${maxSize / (1024 * 1024)}MB`);
                input.value = '';
                resetFilePreview(fileType);
                return;
            }

            const fileSize = (file.size / 1024).toFixed(1);
            const sizeText = fileSize < 1024 ? `${fileSize} KB` : `${(fileSize / 1024).toFixed(1)} MB`;
            
            let fileName = file.name;
            if (fileName.length > 8) {
                fileName = fileName.substring(0, 5) + '...' + fileName.split('.').pop();
            }
            nameSpan.textContent = fileName;
            metaSpan.textContent = sizeText;
            
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewDiv.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    previewDiv.classList.add('has-image');
                }
                reader.readAsDataURL(file);
            } else {
                previewDiv.innerHTML = fileType === 'foto' ? '<i class="fas fa-file-image text-primary"></i>' : 
                                     (fileType === 'payment' ? '<i class="fas fa-file-invoice text-success"></i>' : 
                                     '<i class="fas fa-file-pdf text-danger"></i>');
                previewDiv.classList.remove('has-image');
            }
            
            statusDiv.innerHTML = '<i class="fas fa-check-circle"></i>';
            cardDiv.classList.add('uploaded');
            
        } else {
            resetFilePreview(fileType);
        }
    };

    function resetFilePreview(fileType) {
        const previewDiv = document.getElementById(`file-preview-${fileType}`);
        const nameSpan = document.getElementById(`file-name-${fileType}`);
        const metaSpan = document.getElementById(`file-meta-${fileType}`);
        const statusDiv = document.getElementById(`file-status-${fileType}`);
        const cardDiv = document.getElementById(`file-card-${fileType}`);
        
        const icons = {
            'birth': 'fa-file-pdf text-danger', 'kk': 'fa-file-pdf text-danger', 'shun': 'fa-file-pdf text-danger',
            'raport': 'fa-file-pdf text-danger', 'foto': 'fa-file-image text-primary', 'surat': 'fa-file-pdf text-danger',
            'payment': 'fa-file-invoice text-success'
        };
        
        previewDiv.innerHTML = `<i class="fas ${icons[fileType]}"></i>`;
        previewDiv.classList.remove('has-image');
        
        const defaultNames = {
            'birth': 'Akta', 'kk': 'KK', 'shun': 'SHUN',
            'raport': 'Raport', 'foto': 'Foto', 'surat': 'Surat',
            'payment': 'Bukti'
        };
        
        const defaultMetas = {
            'birth': '1MB', 'kk': '1MB', 'shun': '1MB',
            'raport': '1MB', 'foto': '1MB', 'surat': '1MB',
            'payment': '2MB'
        };
        
        nameSpan.textContent = defaultNames[fileType] || 'File';
        metaSpan.textContent = defaultMetas[fileType] || '1MB';
        statusDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i>';
        cardDiv.classList.remove('uploaded');
    }

    // Fungsi untuk handle jersey
    window.handleJerseySelect = function(input, jerseyType) {
        const file = input.files[0];
        const previewDiv = document.getElementById(`jersey-preview-${jerseyType}`);
        const sizeSpan = document.getElementById(`jersey-size-${jerseyType}`);
        const cardDiv = document.getElementById(`jersey-card-${jerseyType}`);
        
        if (file) {
            if (file.size > 2 * 1024 * 1024) {
                alert('File terlalu besar! Maksimal 2MB');
                input.value = '';
                resetJerseyPreview(jerseyType);
                return;
            }

            const fileSize = (file.size / 1024).toFixed(1);
            sizeSpan.textContent = fileSize < 1024 ? `${fileSize}KB` : `${(fileSize / 1024).toFixed(1)}MB`;

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewDiv.innerHTML = `<img src="${e.target.result}" alt="Jersey">`;
                    previewDiv.classList.add('has-image');
                }
                reader.readAsDataURL(file);
            }

            cardDiv.classList.add('uploaded');
        } else {
            resetJerseyPreview(jerseyType);
        }
    };

    function resetJerseyPreview(jerseyType) {
        const previewDiv = document.getElementById(`jersey-preview-${jerseyType}`);
        const sizeSpan = document.getElementById(`jersey-size-${jerseyType}`);
        const cardDiv = document.getElementById(`jersey-card-${jerseyType}`);
        
        previewDiv.innerHTML = '<i class="fas fa-tshirt"></i>';
        previewDiv.classList.remove('has-image');
        sizeSpan.textContent = '-';
        cardDiv.classList.remove('uploaded');
    }

    // Form validation
    form.addEventListener('submit', function(e) {
        if (!validateAllFields()) {
            e.preventDefault();
            return false;
        }

        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Loading';
        submitBtn.disabled = true;
        loadingModal.show();

        return true;
    });

    function validateAllFields() {
        // Validasi sederhana
        const requiredFields = ['nik', 'name', 'birthdate', 'gender', 'phone', 'email', 'grade', 'sttb_year', 
                               'height', 'weight', 'tshirt_size', 'shoes_size', 'father_name', 'father_phone', 
                               'mother_name', 'mother_phone'];
        
        @if($category !== 'dancer')
        requiredFields.push('basketball_position', 'jersey_number');
        @endif

        for (const field of requiredFields) {
            const el = document.getElementById(field);
            if (!el || !el.value.trim()) {
                alert('Lengkapi semua data');
                el?.focus();
                return false;
            }
        }

        // NIK 16 digit
        if (document.getElementById('nik').value.length !== 16) {
            alert('NIK harus 16 digit');
            return false;
        }

        // Email format
        const email = document.getElementById('email').value;
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            alert('Email tidak valid');
            return false;
        }

        // Phone
        if (document.getElementById('phone').value.replace(/[^0-9]/g, '').length < 10) {
            alert('WA minimal 10 digit');
            return false;
        }

        // Dokumen
        const docs = ['birth_certificate', 'kk', 'shun', 'last_report_card', 'formal_photo', 'assignment_letter'];
        for (const doc of docs) {
            const input = document.getElementById(doc);
            if (!input.files || input.files.length === 0) {
                alert('Upload semua dokumen');
                return false;
            }
        }

        @if($role === 'Leader')
        // Bukti transfer
        const payment = document.getElementById('payment_proof');
        if (!payment.files || payment.files.length === 0) {
            alert('Upload bukti transfer');
            return false;
        }
        
        @if($category !== 'dancer')
        // Jersey minimal 1
        const home = document.getElementById('jersey_home');
        const away = document.getElementById('jersey_away');
        const alt = document.getElementById('jersey_alternate');
        
        if (!(home?.files.length > 0 || away?.files.length > 0 || alt?.files.length > 0)) {
            alert('Upload minimal 1 foto jersey');
            return false;
        }
        @endif
        @endif

        // Terms
        if (!document.getElementById('terms').checked) {
            alert('Setujui syarat');
            return false;
        }

        return true;
    }
});
</script>
@endpush
@endsection