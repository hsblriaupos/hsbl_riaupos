@extends('admin.layouts.app')
@section('title', 'Detail Tim - Administrator')

@section('content')
@include('partials.sweetalert')

@push('styles')
<style>
    .page-header {
        margin-bottom: 30px;
    }

    .page-title {
        font-size: 1.3rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .page-subtitle {
        color: #6c757d;
        font-size: 0.9rem;
    }

    /* Card Styles */
    .info-card {
        background: white;
        border: 1px solid #e3e6f0;
        border-radius: 6px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.1);
        margin-bottom: 30px;
        overflow: hidden;
    }

    .card-header {
        background-color: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        padding: 15px 20px;
        font-weight: 600;
        color: #4a4c54;
        font-size: 1rem;
    }

    .card-body {
        padding: 20px;
    }

    /* Table Styles */
    .info-table {
        width: 100%;
        border-collapse: collapse;
    }

    .info-table tr {
        border-bottom: 1px solid #e3e6f0;
    }

    .info-table tr:last-child {
        border-bottom: none;
    }

    .info-table td {
        padding: 12px 15px;
        vertical-align: top;
    }

    .info-label {
        width: 180px;
        font-weight: 600;
        color: #2c3e50;
        background-color: #f8f9fc;
        border-right: 1px solid #e3e6f0;
    }

    .info-value {
        color: #495057;
    }

    /* Badge Styles */
    .status-badge {
        padding: 4px 10px;
        font-size: 0.75rem;
        font-weight: 500;
        border-radius: 4px;
        display: inline-block;
    }

    .badge-open {
        background-color: #d1fae5;
        color: #28a745;
    }

    .badge-locked {
        background-color: #fee2e2;
        color: #dc3545;
    }

    .badge-verified {
        background-color: #d1fae5;
        color: #28a745;
    }

    .badge-unverified {
        background-color: #fef3c7;
        color: #ffc107;
    }

    .badge-rejected {
        background-color: #fee2e2;
        color: #dc3545;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    /* Player List Styles */
    .player-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    .player-table th {
        background-color: #f8f9fc;
        padding: 12px 15px;
        text-align: left;
        font-weight: 600;
        color: #4a4c54;
        border-bottom: 2px solid #e3e6f0;
    }

    .player-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #e3e6f0;
    }

    .player-table tr:hover {
        background-color: #f8f9fc;
    }

    /* Section Header */
    .section-header {
        margin: 30px 0 15px 0;
        padding-bottom: 10px;
        border-bottom: 2px solid #e3e6f0;
        color: #2c3e50;
        font-size: 1.1rem;
        font-weight: 600;
    }

    /* Back Button */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #6c757d;
        text-decoration: none;
        font-size: 0.9rem;
        margin-bottom: 20px;
        padding: 8px 16px;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        background-color: #f8f9fa;
        transition: all 0.2s;
    }

    .back-link:hover {
        background-color: #e9ecef;
        color: #495057;
        text-decoration: none;
    }

    /* Jersey Images */
    .jersey-container {
        display: flex;
        gap: 30px;
        flex-wrap: wrap;
    }

    .jersey-item {
        text-align: center;
    }

    .jersey-placeholder {
        width: 150px;
        height: 150px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 0.85rem;
    }

    .jersey-label {
        margin-top: 10px;
        font-weight: 500;
        color: #495057;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-table {
            display: block;
        }
        
        .info-table tr {
            display: block;
            margin-bottom: 15px;
            border-bottom: 1px solid #e3e6f0;
            padding-bottom: 15px;
        }
        
        .info-table td {
            display: block;
            padding: 8px 0;
            border: none;
        }
        
        .info-label {
            width: 100%;
            background: none;
            border: none;
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .jersey-container {
            flex-direction: column;
            gap: 20px;
        }
        
        .action-buttons {
            flex-direction: column;
        }
        
        .action-buttons .btn {
            width: 100%;
            text-align: center;
        }
    }
</style>
@endpush

<div class="container-fluid px-3">
    <!-- Back Button -->
    <a href="{{ route('admin.tv_team_list') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        Kembali ke Daftar Tim
    </a>

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1 class="page-title">
                <i class="fas fa-users text-primary me-2"></i>Informasi Tim
            </h1>
        </div>
    </div>

    <!-- Team Information Card -->
    <div class="info-card">
        <div class="card-header">
            Informasi Tim
        </div>
        <div class="card-body">
            <table class="info-table">
                <tr>
                    <td class="info-label">ID Tim</td>
                    <td class="info-value">: Tahun 1986</td>
                    <td class="info-label">Musim</td>
                    <td class="info-value">: Honda DBL 2019</td>
                </tr>
                <tr>
                    <td class="info-label">Pemimpin</td>
                    <td class="info-value">: Muhammad Alfah Reza</td>
                    <td class="info-label">Seri</td>
                    <td class="info-value">: Seri Riau</td>
                </tr>
                <tr>
                    <td class="info-label">Nama Sekolah</td>
                    <td class="info-value">: {{ $team->school_name }}</td>
                    <td class="info-label">Wilayah</td>
                    <td class="info-value">: -</td>
                </tr>
                <tr>
                    <td class="info-label">Kompetisi</td>
                    <td class="info-value">: {{ $team->competition }}</td>
                    <td class="info-label">Surat</td>
                    <td class="info-value">: <a href="#" style="color: #0d6efd; text-decoration: none;">Lihat di sini</a></td>
                </tr>
                <tr>
                    <td class="info-label">Status Terkunci</td>
                    <td class="info-value">
                        : 
                        @if($team->locked_status == 'locked')
                        <span class="status-badge badge-locked">Terkunci</span>
                        @else
                        <span class="status-badge badge-open">Terbuka</span>
                        @endif
                    </td>
                    <td class="info-label">Rekomendasi</td>
                    <td class="info-value"></td>
                </tr>
                <tr>
                    <td class="info-label">Status Verifikasi</td>
                    <td class="info-value">
                        : 
                        @if($team->verification_status == 'verified')
                        <span class="status-badge badge-verified">Terverifikasi</span>
                        @elseif($team->verification_status == 'rejected')
                        <span class="status-badge badge-rejected">Ditolak</span>
                        @else
                        <span class="status-badge badge-unverified">Tidak terverifikasi</span>
                        @endif
                    </td>
                    <td class="info-label"></td>
                    <td class="info-value">
                        @if($team->locked_status != 'locked')
                        <form action="{{ url('/admin/team/' . $team->team_id . '/lock') }}" method="POST" class="d-inline">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-lock me-1"></i> Tandai sebagai Terkunci
                            </button>
                        </form>
                        @else
                        <form action="{{ url('/admin/team/' . $team->team_id . '/unlock') }}" method="POST" class="d-inline">
                            @csrf
                            @method('POST')
                            <button type="submit" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-unlock me-1"></i> Buka Kunci
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Player List Section -->
    <h3 class="section-header">Daftar Pemain</h3>
    
    <div class="info-card">
        <div class="card-body">
            <table class="player-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Jersey</th>
                        <th>Tanggal Lahir</th>
                        <th>Sekolah</th>
                        <th>Nilai</th>
                        <th>Tahun STTB</th>
                        <th>Peran Tim</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Muhammad Alfah Reza</td>
                        <td>1</td>
                        <td>02 April 2003</td>
                        <td>{{ $team->school_name }}</td>
                        <td>Kelas XI</td>
                        <td>Tahun 2018</td>
                        <td>Pemain</td>
                        <td>
                            <a href="#" class="btn btn-sm btn-outline-primary">detail</a>
                        </td>
                    </tr>
                    <!-- Add more players as needed -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Official List Section -->
    <h3 class="section-header">Daftar Resmi</h3>
    
    <div class="info-card">
        <div class="card-body">
            <table class="player-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama</th>
                        <th>Tanggal Lahir</th>
                        <th>Sekolah</th>
                        <th>Lisensi</th>
                        <th>Peran Tim</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Official list would go here -->
                    <tr>
                        <td colspan="7" class="text-center text-muted py-3">
                            Tidak ada data resmi
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Jersey List Section -->
    <h3 class="section-header">Daftar Jersey</h3>
    
    <div class="info-card">
        <div class="card-body">
            <div class="jersey-container">
                <div class="jersey-item">
                    <div class="jersey-placeholder">
                        Image not available
                    </div>
                    <div class="jersey-label">Jersey Kandang</div>
                </div>
                
                <div class="jersey-item">
                    <div class="jersey-placeholder">
                        Image not available
                    </div>
                    <div class="jersey-label">Jersey Tandang</div>
                </div>
                
                <div class="jersey-item">
                    <div class="jersey-placeholder">
                        Image not available
                    </div>
                    <div class="jersey-label">Jersey Alternatif</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Action Buttons -->
    @if(in_array($team->verification_status, ['unverified', 'pending', 'rejected']))
    <div class="action-buttons" style="margin-top: 30px; justify-content: center;">
        <form action="{{ url('/admin/team/' . $team->team_id . '/verify') }}" method="POST" class="d-inline">
            @csrf
            @method('POST')
            <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-check-circle me-2"></i> Verifikasi Tim
            </button>
        </form>
        
        <form action="{{ url('/admin/team/' . $team->team_id . '/reject') }}" method="POST" class="d-inline">
            @csrf
            @method('POST')
            <button type="submit" class="btn btn-danger btn-lg">
                <i class="fas fa-times-circle me-2"></i> Tolak Tim
            </button>
        </form>
    </div>
    @endif
</div>

@push('scripts')
<script>
    // Confirm before taking action
    document.addEventListener('DOMContentLoaded', function() {
        const actionForms = document.querySelectorAll('form[action*="/team/"]');
        actionForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const action = this.action;
                let message = '';
                
                if (action.includes('/verify')) {
                    message = 'Apakah Anda yakin ingin memverifikasi tim ini?';
                } else if (action.includes('/reject')) {
                    message = 'Apakah Anda yakin ingin menolak tim ini?';
                } else if (action.includes('/lock')) {
                    message = 'Apakah Anda yakin ingin mengunci tim ini? Tim tidak dapat diedit.';
                } else if (action.includes('/unlock')) {
                    message = 'Apakah Anda yakin ingin membuka kunci tim ini?';
                }
                
                if (message && !confirm(message)) {
                    e.preventDefault();
                }
            });
        });
    });
</script>
@endpush
@endsection