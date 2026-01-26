@extends('user.form.layout')

@section('title', 'Buat Tim Baru - HSBL')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow border-0">
                <!-- Header -->
                <div class="card-header bg-gradient-primary text-white py-4">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('form.team.choice') }}" class="text-white me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h2 class="mb-0">🚀 Buat Tim Baru</h2>
                            <p class="mb-0 opacity-75">Kamu akan menjadi Leader pertama dari tim ini</p>
                        </div>
                    </div>
                </div>
                
                <!-- Progress Bar -->
                <div class="px-4 pt-4">
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" 
                             aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <div class="d-flex justify-content-between mt-2">
                        <small class="text-primary fw-bold">📋 Form Tim</small>
                        <small class="text-muted">💰 Pembayaran</small>
                        <small class="text-muted">👤 Data Leader</small>
                        <small class="text-muted">✅ Selesai</small>
                    </div>
                </div>
                
                <!-- Form -->
                <div class="card-body p-5">
                    <form id="createTeamForm" action="{{ route('form.team.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Informasi Leader -->
                        <div class="alert alert-warning mb-4">
                            <i class="fas fa-crown me-2"></i>
                            <strong>Kamu akan menjadi Leader!</strong> Sebagai Leader, kamu bertanggung jawab 
                            untuk pembayaran tim dan akan mendapatkan referral code untuk dibagikan ke anggota.
                        </div>
                        
                        <!-- School Information -->
                        <div class="mb-4">
                            <h5 class="mb-3">🏫 Informasi Sekolah</h5>
                            
                            <!-- School Name with Autocomplete -->
                            <div class="mb-3">
                                <label for="school_name" class="form-label">
                                    Nama Sekolah <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('school_name') is-invalid @enderror" 
                                       id="school_name" 
                                       name="school_name" 
                                       value="{{ old('school_name') }}" 
                                       required
                                       list="schoolsList"
                                       placeholder="Ketikan nama sekolah...">
                                <datalist id="schoolsList"></datalist>
                                <small class="text-muted">Nama sekolah akan menjadi nama tim</small>
                                @error('school_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Competition Category -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="competition" class="form-label">Kompetisi <span class="text-danger">*</span></label>
                                    <select class="form-select @error('competition') is-invalid @enderror" 
                                            id="competition" name="competition" required>
                                        <option value="">Pilih Kompetisi</option>
                                        @foreach($competitions as $competition)
                                            <option value="{{ $competition }}" {{ old('competition') == $competition ? 'selected' : '' }}>
                                                {{ $competition }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="team_category" class="form-label">Kategori Tim <span class="text-danger">*</span></label>
                                    <select class="form-select @error('team_category') is-invalid @enderror" 
                                            id="team_category" name="team_category" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($teamCategoryEnums as $category)
                                            <option value="{{ $category }}" {{ old('team_category') == $category ? 'selected' : '' }}>
                                                {{ $category }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Season & Series -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="season" class="form-label">Season <span class="text-danger">*</span></label>
                                    <select class="form-select @error('season') is-invalid @enderror" 
                                            id="season" name="season" required>
                                        <option value="">Pilih Season</option>
                                        @foreach($seasons as $season)
                                            <option value="{{ $season }}" {{ old('season') == $season ? 'selected' : '' }}>
                                                {{ $season }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div class="col-md-6">
                                    <label for="series" class="form-label">Series <span class="text-danger">*</span></label>
                                    <select class="form-select @error('series') is-invalid @enderror" 
                                            id="series" name="series" required>
                                        <option value="">Pilih Series</option>
                                        @foreach($series as $serie)
                                            <option value="{{ $serie }}" {{ old('series') == $serie ? 'selected' : '' }}>
                                                {{ $serie }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Registered By -->
                            <div class="mb-3">
                                <label for="registered_by" class="form-label">
                                    Nama Pendaftar (Leader) <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control @error('registered_by') is-invalid @enderror" 
                                       id="registered_by" 
                                       name="registered_by" 
                                       value="{{ old('registered_by') }}" 
                                       required
                                       placeholder="Nama lengkap kamu">
                                <small class="text-muted">Nama ini akan tercatat sebagai Leader tim</small>
                            </div>
                        </div>
                        
                        <!-- Document Upload -->
                        <div class="mb-4">
                            <h5 class="mb-3">📎 Upload Dokumen</h5>
                            
                            <!-- Recommendation Letter -->
                            <div class="mb-3">
                                <label for="recommendation_letter" class="form-label">
                                    Surat Rekomendasi Sekolah <span class="text-danger">*</span>
                                </label>
                                <input type="file" 
                                       class="form-control @error('recommendation_letter') is-invalid @enderror" 
                                       id="recommendation_letter" 
                                       name="recommendation_letter" 
                                       accept=".pdf" 
                                       required>
                                <small class="text-muted">Format: PDF, Maks: 2MB</small>
                                @error('recommendation_letter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <!-- Koran -->
                            <div class="mb-3">
                                <label for="koran" class="form-label">
                                    Langganan Koran <span class="text-danger">*</span>
                                </label>
                                <input type="file" 
                                       class="form-control @error('koran') is-invalid @enderror" 
                                       id="koran" 
                                       name="koran" 
                                       accept=".jpg,.jpeg,.png,.pdf" 
                                       required>
                                <small class="text-muted">Format: JPG, PNG, atau PDF, Maks: 2MB</small>
                                @error('koran')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Warning Box -->
                        <div class="alert alert-danger mb-4">
                            <div class="d-flex">
                                <i class="fas fa-exclamation-triangle fa-2x me-3 mt-1"></i>
                                <div>
                                    <h6 class="alert-heading">Perhatian!</h6>
                                    <ul class="mb-0">
                                        <li>Sebagai Leader, kamu <strong>bertanggung jawab untuk pembayaran tim</strong></li>
                                        <li>Setelah submit, kamu akan mendapatkan <strong>referral code</strong></li>
                                        <li>Bagikan referral code ke teman sekelas untuk bergabung</li>
                                        <li>Pastikan data yang diisi sudah benar</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="text-center mt-5">
                            <button type="submit" class="btn btn-primary btn-lg px-5 py-3">
                                <i class="fas fa-rocket me-2"></i> Buat Tim & Lanjut Pembayaran
                            </button>
                            <a href="{{ route('form.team.choice') }}" class="btn btn-outline-secondary ms-3">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Existing Team Modal -->
<div class="modal fade" id="existingTeamModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title">⚠️ Tim Sudah Ada</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="existingTeamMessage"></p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Lebih baik bergabung dengan tim yang sudah ada menggunakan referral code
                </div>
            </div>
            <div class="modal-footer">
                <a href="{{ route('form.team.join') }}" class="btn btn-primary">
                    <i class="fas fa-users me-2"></i>Gabung ke Tim
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tetap Buat Baru</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Autocomplete sekolah
document.getElementById('school_name').addEventListener('input', function() {
    const query = this.value;
    if (query.length < 2) return;
    
    fetch(`{{ route('form.team.checkSchool') }}?query=${query}`)
        .then(response => response.json())
        .then(data => {
            const datalist = document.getElementById('schoolsList');
            datalist.innerHTML = '';
            data.forEach(school => {
                const option = document.createElement('option');
                option.value = school.school_name;
                datalist.appendChild(option);
            });
        });
});

// Cek tim yang sudah ada
function checkExistingTeam() {
    const schoolName = document.getElementById('school_name').value;
    const teamCategory = document.getElementById('team_category').value;
    const season = document.getElementById('season').value;
    
    if (!schoolName || !teamCategory || !season) return;
    
    const formData = new FormData();
    formData.append('school_name', schoolName);
    formData.append('team_category', teamCategory);
    formData.append('season', season);
    
    fetch('{{ route("form.team.checkExisting") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.exists) {
            document.getElementById('existingTeamMessage').textContent = data.message;
            const modal = new bootstrap.Modal(document.getElementById('existingTeamModal'));
            modal.show();
        }
    });
}

// Trigger check when category or season changes
document.getElementById('team_category').addEventListener('change', checkExistingTeam);
document.getElementById('season').addEventListener('change', checkExistingTeam);

// Form validation
document.getElementById('createTeamForm').addEventListener('submit', function(e) {
    const files = {
        recommendation: document.getElementById('recommendation_letter').files[0],
        koran: document.getElementById('koran').files[0]
    };
    
    // File size validation
    for (const [name, file] of Object.entries(files)) {
        if (file && file.size > 2 * 1024 * 1024) {
            e.preventDefault();
            alert(`File ${name} terlalu besar! Maksimal 2MB`);
            return;
        }
    }
});
</script>

<style>
.card-header {
    border-radius: 0 !important;
}
.progress {
    border-radius: 4px;
}
.form-label {
    font-weight: 600;
    margin-bottom: 0.5rem;
}
.alert h6 {
    margin-bottom: 0.5rem;
}
</style>
@endpush
@endsection