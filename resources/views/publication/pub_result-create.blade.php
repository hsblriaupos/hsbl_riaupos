@extends('admin.layouts.app')
@section('title', 'Add Result - Administrator')

@section('content')

@php $activeTab = 'result'; @endphp
@include('partials.tabs-pub', compact('activeTab'))

{{-- Include SweetAlert2 --}}
@include('partials.sweetalert')

<div class="container mt-4">
    <!-- Page Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
        <div class="mb-3 mb-md-0">
            <h1 class="page-title">
                <i class="fas fa-plus-circle text-primary me-2"></i> Add New Result
            </h1>
            <p class="page-subtitle">Add new match result</p>
        </div>
        
        <!-- Back Button -->
        <div>
            <a href="{{ route('admin.pub_result.index') }}" 
               class="btn btn-outline-secondary d-flex align-items-center">
                <i class="fas fa-arrow-left me-2"></i> Back to Results
            </a>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.pub_result.store') }}" method="POST" enctype="multipart/form-data" id="resultForm">
                @csrf
                
                {{-- Hidden input untuk action_type --}}
                <input type="hidden" name="action_type" id="action_type" value="draft">
                
                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <!-- Match Date -->
                        <div class="mb-3">
                            <label for="match_date" class="form-label required">
                                <i class="fas fa-calendar-day me-1"></i> Match Date
                            </label>
                            <input type="date" 
                                   class="form-control @error('match_date') is-invalid @enderror" 
                                   id="match_date" 
                                   name="match_date" 
                                   value="{{ old('match_date', date('Y-m-d')) }}"
                                   required>
                            @error('match_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Date when the match was played</small>
                        </div>

                        <!-- Season -->
                        <div class="mb-3">
                            <label for="season" class="form-label required">
                                <i class="fas fa-calendar-alt me-1"></i> Season
                            </label>
                            <select class="form-select @error('season') is-invalid @enderror" 
                                    id="season" 
                                    name="season"
                                    required>
                                <option value="">-- Select Season --</option>
                                @foreach($seasons as $season)
                                    <option value="{{ $season->season_name }}" {{ old('season') == $season->season_name ? 'selected' : '' }}>
                                        {{ $season->season_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('season')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Select the season</small>
                        </div>

                        <!-- Team 1 -->
                        <div class="mb-3">
                            <label for="team1_id" class="form-label required">
                                <i class="fas fa-users me-1"></i> Team 1
                            </label>
                            <select class="form-select @error('team1_id') is-invalid @enderror" 
                                    id="team1_id" 
                                    name="team1_id"
                                    required>
                                <option value="">-- Select Team 1 --</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->team_id }}" {{ old('team1_id') == $team->team_id ? 'selected' : '' }}>
                                        {{ $team->school_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('team1_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Select first team</small>
                        </div>

                        <!-- Team 2 -->
                        <div class="mb-3">
                            <label for="team2_id" class="form-label required">
                                <i class="fas fa-users me-1"></i> Team 2
                            </label>
                            <select class="form-select @error('team2_id') is-invalid @enderror" 
                                    id="team2_id" 
                                    name="team2_id"
                                    required>
                                <option value="">-- Select Team 2 --</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->team_id }}" {{ old('team2_id') == $team->team_id ? 'selected' : '' }}>
                                        {{ $team->school_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('team2_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Select second team</small>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <!-- Score Team 1 -->
                        <div class="mb-3">
                            <label for="score_1" class="form-label required">
                                <i class="fas fa-futbol me-1"></i> Score Team 1
                            </label>
                            <input type="number" 
                                   class="form-control @error('score_1') is-invalid @enderror" 
                                   id="score_1" 
                                   name="score_1" 
                                   value="{{ old('score_1', 0) }}"
                                   min="0"
                                   required>
                            @error('score_1')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Score for Team 1</small>
                        </div>

                        <!-- Score Team 2 -->
                        <div class="mb-3">
                            <label for="score_2" class="form-label required">
                                <i class="fas fa-futbol me-1"></i> Score Team 2
                            </label>
                            <input type="number" 
                                   class="form-control @error('score_2') is-invalid @enderror" 
                                   id="score_2" 
                                   name="score_2" 
                                   value="{{ old('score_2', 0) }}"
                                   min="0"
                                   required>
                            @error('score_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Score for Team 2</small>
                        </div>

                        <!-- Competition -->
                        <div class="mb-3">
                            <label for="competition" class="form-label required">
                                <i class="fas fa-trophy me-1"></i> Competition
                            </label>
                            <select class="form-select @error('competition') is-invalid @enderror" 
                                    id="competition" 
                                    name="competition"
                                    required>
                                <option value="">-- Select Competition --</option>
                                @foreach($competitions as $competition)
                                    <option value="{{ $competition->competition }}" {{ old('competition') == $competition->competition ? 'selected' : '' }}>
                                        {{ $competition->competition }}
                                    </option>
                                @endforeach
                            </select>
                            @error('competition')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Select the competition</small>
                        </div>

                        <!-- Competition Type -->
                        <div class="mb-3">
                            <label for="competition_type" class="form-label required">
                                <i class="fas fa-basketball-ball me-1"></i> Competition Type
                            </label>
                            <select class="form-select @error('competition_type') is-invalid @enderror" 
                                    id="competition_type" 
                                    name="competition_type"
                                    required>
                                <option value="">-- Select Competition Type --</option>
                                @foreach($competitionTypes as $type)
                                    <option value="{{ $type->competition_type }}" {{ old('competition_type') == $type->competition_type ? 'selected' : '' }}>
                                        {{ $type->competition_type }}
                                    </option>
                                @endforeach
                            </select>
                            @error('competition_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Type of competition</small>
                        </div>
                    </div>
                </div>

                <!-- Row 2: Series and Phase -->
                <div class="row mt-3">
                    <div class="col-md-6">
                        <!-- Series -->
                        <div class="mb-3">
                            <label for="series" class="form-label required">
                                <i class="fas fa-map-marker-alt me-1"></i> Series
                            </label>
                            <select class="form-select @error('series') is-invalid @enderror" 
                                    id="series" 
                                    name="series"
                                    required>
                                <option value="">-- Select Series --</option>
                                @foreach($series as $s)
                                    <option value="{{ $s }}" {{ old('series') == $s ? 'selected' : '' }}>
                                        {{ $s }}
                                    </option>
                                @endforeach
                            </select>
                            @error('series')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Select series (kabupaten/kota di Riau)</small>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <!-- Phase -->
                        <div class="mb-3">
                            <label for="phase" class="form-label required">
                                <i class="fas fa-flag me-1"></i> Phase
                            </label>
                            <select class="form-select @error('phase') is-invalid @enderror" 
                                    id="phase" 
                                    name="phase"
                                    required>
                                <option value="">-- Select Phase --</option>
                                @foreach($phases as $phaseItem)
                                    <option value="{{ $phaseItem->phase }}" {{ old('phase') == $phaseItem->phase ? 'selected' : '' }}>
                                        {{ $phaseItem->phase }}
                                    </option>
                                @endforeach
                            </select>
                            @error('phase')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Match phase/round</small>
                        </div>
                    </div>
                </div>

                <!-- Scoresheet -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <!-- Scoresheet (Excel) -->
                        <div class="mb-3">
                            <label for="scoresheet" class="form-label">
                                <i class="fas fa-file-excel me-1 text-success"></i> Scoresheet (Optional - Excel)
                            </label>
                            <input type="file" 
                                   class="form-control @error('scoresheet') is-invalid @enderror" 
                                   id="scoresheet" 
                                   name="scoresheet"
                                   accept=".xlsx,.xls,.xlsm,.xlsb,.csv">
                            @error('scoresheet')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <small class="text-muted d-block">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Upload scoresheet in Excel format
                                </small>
                                <small class="text-muted d-block">
                                    <i class="fas fa-check-circle me-1 text-success"></i>
                                    Accepted formats: .xlsx, .xls, .xlsm, .xlsb, .csv
                                </small>
                                <small class="text-muted d-block">
                                    <i class="fas fa-exclamation-triangle me-1 text-warning"></i>
                                    Maximum file size: 10MB
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Info -->
                <div class="row mt-3">
                    <div class="col-md-12">
                        <div class="alert alert-light border">
                            <label class="form-label">
                                <i class="fas fa-info-circle me-1"></i> Status Information
                            </label>
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <span class="badge bg-warning bg-opacity-20 text-warning d-inline-flex align-items-center">
                                        <i class="fas fa-edit me-1"></i> Draft
                                    </span>
                                    <small class="ms-2">Default status. Only visible to admins</small>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <span class="badge bg-success bg-opacity-20 text-success d-inline-flex align-items-center">
                                        <i class="fas fa-globe me-1"></i> Published
                                    </span>
                                    <small class="ms-2">Visible to public. Can be changed later</small>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <span class="badge bg-primary bg-opacity-20 text-primary d-inline-flex align-items-center">
                                        <i class="fas fa-check-double me-1"></i> Done
                                    </span>
                                    <small class="ms-2">Result is final. Cannot be edited</small>
                                </div>
                            </div>
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-exclamation-triangle text-warning me-1"></i>
                                Status will be set to "Draft" by default. You can publish it later from the result list.
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.pub_result.index') }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-times me-2"></i> Cancel
                            </a>
                            <div class="d-flex gap-2">
                                <button type="button" onclick="submitForm('draft')" 
                                        class="btn btn-warning">
                                    <i class="fas fa-save me-2"></i> Save as Draft
                                </button>
                                <button type="button" onclick="submitForm('publish')" 
                                        class="btn btn-success">
                                    <i class="fas fa-paper-plane me-2"></i> Save & Publish
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .page-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .page-subtitle {
        color: #7f8c8d;
        font-size: 0.875rem;
    }

    .card {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    .card-body {
        padding: 24px;
    }

    .form-label {
        font-weight: 500;
        font-size: 0.9rem;
        color: #495057;
        margin-bottom: 6px;
    }

    .form-label.required:after {
        content: " *";
        color: #dc3545;
    }

    .form-control, .form-select {
        font-size: 0.9rem;
        padding: 8px 12px;
        border-radius: 6px;
        border: 1px solid #ced4da;
    }

    .form-control:focus, .form-select:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .invalid-feedback {
        font-size: 0.8rem;
    }

    .alert {
        border-radius: 6px;
        font-size: 0.9rem;
    }

    .badge {
        padding: 0.3em 0.6em;
        font-weight: 500;
        font-size: 0.75em;
    }

    .btn {
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
        border-radius: 6px;
    }

    .btn-primary {
        background-color: #3498db;
        border-color: #3498db;
    }

    .btn-primary:hover {
        background-color: #2980b9;
        border-color: #2980b9;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }

    .btn-outline-secondary {
        border-color: #6c757d;
        color: #6c757d;
    }

    @media (max-width: 768px) {
        .container {
            padding-left: 15px;
            padding-right: 15px;
        }
        
        .card-body {
            padding: 16px;
        }
        
        .d-flex.flex-md-row {
            flex-direction: column !important;
        }
        
        .d-flex.justify-content-between {
            flex-direction: column !important;
            gap: 10px;
        }
        
        .d-flex.gap-2 {
            flex-wrap: wrap;
            gap: 5px !important;
        }
        
        .btn {
            padding: 6px 12px;
            font-size: 0.85rem;
        }
        
        .row .col-md-4 {
            margin-bottom: 10px;
        }
    }
    
    @media (max-width: 576px) {
        .page-title {
            font-size: 1rem;
        }
        
        .form-label {
            font-size: 0.85rem;
        }
        
        .btn {
            padding: 5px 10px;
            font-size: 0.8rem;
        }
        
        .col-md-6 {
            margin-bottom: 15px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set today's date as default if not set
        const matchDateInput = document.getElementById('match_date');
        if (matchDateInput && !matchDateInput.value) {
            matchDateInput.value = new Date().toISOString().split('T')[0];
        }
        
        // Validate Team 1 and Team 2 are not the same
        const team1Select = document.getElementById('team1_id');
        const team2Select = document.getElementById('team2_id');
        
        function validateTeams() {
            if (team1Select.value && team2Select.value && team1Select.value === team2Select.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Team Selection Error',
                    text: 'Team 1 and Team 2 cannot be the same!',
                    timer: 3000,
                    showConfirmButton: false
                });
                team2Select.value = '';
                team2Select.classList.add('is-invalid');
                return false;
            }
            return true;
        }
        
        if (team1Select && team2Select) {
            team1Select.addEventListener('change', validateTeams);
            team2Select.addEventListener('change', validateTeams);
        }
        
        // Validate file type for scoresheet
        const scoresheetInput = document.getElementById('scoresheet');
        if (scoresheetInput) {
            scoresheetInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    // Check file size (max 10MB)
                    const maxSize = 10 * 1024 * 1024; // 10MB in bytes
                    if (file.size > maxSize) {
                        Swal.fire({
                            icon: 'error',
                            title: 'File Too Large',
                            text: 'File size exceeds 10MB maximum limit!',
                            timer: 3000,
                            showConfirmButton: false
                        });
                        this.value = '';
                        this.classList.add('is-invalid');
                        return;
                    }
                    
                    // Check file extension
                    const allowedExtensions = ['.xlsx', '.xls', '.xlsm', '.xlsb', '.csv'];
                    const fileName = file.name.toLowerCase();
                    const isValidExtension = allowedExtensions.some(ext => fileName.endsWith(ext));
                    
                    if (!isValidExtension) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid File Type',
                            html: 'Please upload only Excel files.<br>Allowed formats: .xlsx, .xls, .xlsm, .xlsb, .csv',
                            timer: 3000,
                            showConfirmButton: false
                        });
                        this.value = '';
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                        this.classList.add('is-valid');
                    }
                }
            });
        }
        
        // Remove invalid class on input
        document.querySelectorAll('.form-control, .form-select').forEach(element => {
            element.addEventListener('input', function() {
                this.classList.remove('is-invalid');
            });
            
            element.addEventListener('change', function() {
                this.classList.remove('is-invalid');
            });
        });
    });
    
    // Function to submit form with status - VERSI SEDERHANA
    function submitForm(actionType) {
        console.log('Submitting form with action:', actionType);
        
        const form = document.getElementById('resultForm');
        const actionInput = document.getElementById('action_type');
        
        // Set action type
        actionInput.value = actionType;
        
        // Validasi sederhana hanya untuk tim yang sama
        const team1 = document.getElementById('team1_id');
        const team2 = document.getElementById('team2_id');
        
        if (team1.value && team2.value && team1.value === team2.value) {
            Swal.fire({
                icon: 'error',
                title: 'Team Selection Error',
                text: 'Team 1 and Team 2 cannot be the same!',
                timer: 3000,
                showConfirmButton: false
            });
            return;
        }
        
        // Tampilkan konfirmasi
        if (actionType === 'publish') {
            Swal.fire({
                title: 'Save & Publish Result?',
                html: 'This result will be immediately visible to the public.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Save & Publish',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log('Form submitted for publishing');
                    form.submit();
                }
            });
        } else {
            Swal.fire({
                title: 'Save as Draft?',
                html: 'This result will only be visible to administrators.',
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Save as Draft',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log('Form submitted as draft');
                    form.submit();
                }
            });
        }
    }
</script>
@endsection