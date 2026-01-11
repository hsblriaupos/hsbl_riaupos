@extends('admin.layouts.app')
@section('title', 'Detail Tim - Administrator')

@section('content')
@include('partials.sweetalert')

@push('styles')
<style>
    body {
        background: #f4f6f9;
        font-family: Arial, sans-serif;
    }

    .page-header {
        margin-bottom: 20px;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .page-subtitle {
        color: #7f8c8d;
        font-size: 0.9rem;
    }

    /* Card Styles */
    .card {
        background: #fff;
        border-radius: 6px;
        margin-bottom: 25px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        border-top: 4px solid #00a65a;
        overflow: hidden;
    }

    .card-header {
        padding: 14px 18px;
        font-weight: bold;
        border-bottom: 1px solid #eee;
        background-color: #f8f9fa;
        color: #2c3e50;
        font-size: 1rem;
    }

    .card-body {
        padding: 20px;
    }

    /* Team Information Grid */
    .team-info {
        display: grid;
        grid-template-columns: 180px 1fr 280px;
        gap: 25px;
        align-items: start;
    }

    .logo-box {
        text-align: center;
        padding: 15px;
        background: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #eee;
    }

    .logo-box img {
        width: 140px;
        height: 140px;
        object-fit: contain;
        margin-bottom: 10px;
    }

    .logo-box p {
        margin-top: 10px;
        font-size: 13px;
        color: #555;
        font-weight: 600;
    }

    /* Info Tables */
    .info-table,
    .info-side table {
        width: 100%;
        border-collapse: collapse;
    }

    .info-table td,
    .info-side td {
        padding: 8px 6px;
        vertical-align: top;
        font-size: 14px;
        border-bottom: 1px solid #f0f0f0;
    }

    .info-table td:first-child,
    .info-side td:first-child {
        width: 160px;
        color: #555;
        font-weight: 600;
    }

    .info-table tr:last-child td,
    .info-side tr:last-child td {
        border-bottom: none;
    }

    /* Status Styles */
    .status-open {
        color: #e74c3c;
        font-weight: bold;
        background: #ffebee;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 12px;
    }

    .status-locked {
        color: #2ecc71;
        font-weight: bold;
        background: #e8f5e9;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 12px;
    }

    .status-verified {
        color: #27ae60;
        font-weight: bold;
        background: #d5f4e6;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 12px;
    }

    .status-unverified {
        color: #f39c12;
        font-weight: bold;
        background: #fef9e7;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 12px;
    }

    /* ===== ACTION BUTTONS ===== */
    .action-buttons-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-top: 20px;
    }

    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    .btn-lock {
        background: #00a65a;
        color: #fff;
        padding: 10px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .btn-lock:hover {
        background: #008d4c;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .btn-unlock {
        background: #3498db;
        color: #fff;
        padding: 10px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .btn-unlock:hover {
        background: #2980b9;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .btn-verify {
        background: #27ae60;
        color: #fff;
        padding: 10px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .btn-verify:hover {
        background: #219653;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .btn-unverify {
        background: #f39c12;
        color: #fff;
        padding: 10px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .btn-unverify:hover {
        background: #d68910;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .btn-reject {
        background: #e74c3c;
        color: #fff;
        padding: 10px 16px;
        border-radius: 4px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .btn-reject:hover {
        background: #c0392b;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .action-label {
        font-size: 12px;
        color: #7f8c8d;
        font-weight: 600;
        margin-bottom: 5px;
        display: block;
    }

    /* Data Tables */
    table.data {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        margin-top: 10px;
    }

    table.data th,
    table.data td {
        border: 1px solid #ddd;
        padding: 10px 12px;
        text-align: left;
        vertical-align: middle;
    }

    table.data th {
        background: #f5f5f5;
        font-weight: 600;
        color: #2c3e50;
    }

    table.data tbody tr:hover {
        background-color: #f9f9f9;
    }

    .btn-detail {
        background: #00c0ef;
        color: #fff;
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 13px;
        text-decoration: none;
        display: inline-block;
        font-weight: 500;
        transition: background 0.3s;
    }

    .btn-detail:hover {
        background: #00a7d0;
        color: white;
        text-decoration: none;
    }

    /* ===== JERSEY SINGLE FULL BOX ===== */
    .jersey-single-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 30px;
        background: #f8f9fa;
        border-radius: 6px;
        border: 2px solid #e3e6f0;
        min-height: 300px;
    }

    .jersey-main-title {
        font-size: 18px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 25px;
        text-align: center;
    }

    .jersey-image-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 40px;
        flex-wrap: wrap;
    }

    .jersey-single-item {
        text-align: center;
        padding: 20px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        min-width: 200px;
        border: 1px solid #ddd;
    }

    .jersey-single-item p {
        margin-bottom: 15px;
        font-weight: bold;
        color: #2c3e50;
        font-size: 16px;
    }

    .jersey-single-item .no-image {
        width: 180px;
        height: 180px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #ecf0f1, #f8f9fa);
        border: 2px dashed #bdc3c7;
        border-radius: 6px;
        color: #7f8c8d;
        font-size: 14px;
        margin: 0 auto;
    }

    .jersey-single-item .no-image i {
        font-size: 3rem;
        margin-bottom: 10px;
        color: #95a5a6;
    }

    /* Back Link */
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #2c3e50;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 20px;
        padding: 10px 15px;
        background: white;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    .back-link:hover {
        background: #f8f9fa;
        color: #2c3e50;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    }

    /* Verification Buttons */
    .verification-buttons {
        display: flex;
        gap: 15px;
        justify-content: center;
        margin-top: 30px;
        padding: 20px;
        background: #f8f9fa;
        border-radius: 6px;
        border: 1px solid #e3e6f0;
    }

    /* Link styles */
    a {
        color: #3498db;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #95a5a6;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #bdc3c7;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .team-info {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .logo-box {
            max-width: 200px;
            margin: 0 auto;
        }

        .jersey-image-container {
            gap: 20px;
        }
    }

    @media (max-width: 768px) {
        .jersey-image-container {
            flex-direction: column;
            gap: 20px;
        }

        table.data {
            display: block;
            overflow-x: auto;
        }

        .verification-buttons {
            flex-direction: column;
        }

        .action-buttons {
            flex-direction: column;
        }

        .action-buttons button {
            width: 100%;
            justify-content: center;
        }
    }

    @media (max-width: 576px) {
        .card-body {
            padding: 15px;
        }

        table.data th,
        table.data td {
            padding: 8px 10px;
            font-size: 13px;
        }

        .jersey-single-item {
            min-width: 100%;
        }

        .jersey-single-item .no-image {
            width: 150px;
            height: 150px;
        }
    }
</style>
@endpush

<div class="container-fluid py-4">
    <!-- Back Button -->
    <a href="{{ route('admin.tv_team_list') }}" class="back-link">
        <i class="fas fa-arrow-left"></i>
        Kembali ke Daftar Tim
    </a>

    <!-- Page Header -->
    <div class="page-header">
        <h1 class="page-title">Informasi Tim</h1>
        <p class="page-subtitle">Detail informasi tim {{ $team->school_name }}</p>
    </div>

    <!-- Team Information Card -->
    <div class="card">
        <div class="card-header">Informasi Tim</div>
        <div class="card-body">
            <div class="team-info">
                <!-- Logo Box -->
                <div class="logo-box">
                    <img src="{{ asset('uploads/logo/hsbl.png') }}" alt="Logo Sekolah">
                    <p>Logo Sekolah</p>
                </div>

                <!-- Main Info -->
                <table class="info-table">
                    <tr>
                        <td>ID Tim</td>
                        <td>: {{ $team->referral_code ?? 'Tahun 1986' }}</td>
                    </tr>
                    <tr>
                        <td>Pemimpin</td>
                        <td>: {{ $team->registered_by ?? 'Muhammad Alfah Reza' }}</td>
                    </tr>
                    <tr>
                        <td>Nama Sekolah</td>
                        <td>: {{ $team->school_name }}</td>
                    </tr>
                    <tr>
                        <td>Kompetisi</td>
                        <td>: {{ $team->competition }}</td>
                    </tr>
                    <tr>
                        <td>Status Terkunci</td>
                        <td>:
                            @if($team->locked_status == 'locked')
                            <span class="status-locked">Terkunci</span>
                            @else
                            <span class="status-open">Terbuka</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Status Verifikasi</td>
                        <td>:
                            @if($team->verification_status == 'verified')
                            <span class="status-verified">Terverifikasi</span>
                            @elseif($team->verification_status == 'rejected')
                            <span class="status-open">Ditolak</span>
                            @else
                            <span class="status-unverified">Tidak terverifikasi</span>
                            @endif
                        </td>
                    </tr>
                </table>

                <!-- Side Info -->
                <div class="info-side">
                    <table>
                        <tr>
                            <td>Musim</td>
                            <td>: {{ $team->season ?? 'Honda DBL 2019' }}</td>
                        </tr>
                        <tr>
                            <td>Seri</td>
                            <td>: {{ $team->series ?? 'Seri Riau' }}</td>
                        </tr>
                        <tr>
                            <td>Wilayah</td>
                            <td>: -</td>
                        </tr>
                        <tr>
                            <td>Surat Rekomendasi</td>
                            <td>: <a href="#">lihat di sini</a></td>
                        </tr>
                    </table>

                    <!-- Action Buttons -->
                    <div class="action-buttons-container">
                        <div>
                            <span class="action-label">Status Kunci:</span>
                            <div class="action-buttons">
                                @if($team->locked_status != 'locked')
                                <form action="{{ route('admin.team.lock', $team->team_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-lock" onclick="return confirm('Kunci tim ini?')">
                                        <i class="fas fa-lock"></i> Kunci Tim
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.team.unlock', $team->team_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-unlock" onclick="return confirm('Buka kunci tim ini?')">
                                        <i class="fas fa-unlock"></i> Buka Kunci
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>

                        <div>
                            <span class="action-label">Status Verifikasi:</span>
                            <div class="action-buttons">
                                @if($team->verification_status != 'verified')
                                <form action="{{ route('admin.team.verify', $team->team_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-verify" onclick="return confirm('Verifikasi tim ini?')">
                                        <i class="fas fa-check-circle"></i> Verifikasi
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.team.unverify', $team->team_id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn-unverify" onclick="return confirm('Batalkan verifikasi tim ini?')">
                                        <i class="fas fa-times-circle"></i> Batalkan Verifikasi
                                    </button>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Player List Card -->
    <div class="card">
        <div class="card-header">Daftar Pemain</div>
        <div class="card-body">
            <table class="data">
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
                            <a class="btn-detail" href="#">
                                <i class="fas fa-eye me-1"></i> detail
                            </a>
                        </td>
                    </tr>
                    <!-- Tambahkan pemain lain jika ada -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Official List Card -->
    <div class="card">
        <div class="card-header">Daftar Resmi</div>
        <div class="card-body">
            <table class="data">
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
                    <tr>
                        <td colspan="7" style="text-align:center;color:#999;padding:30px;">
                            <i class="fas fa-inbox" style="font-size:2rem;margin-bottom:10px;display:block;"></i>
                            Belum ada data
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Jersey List Card -->
    <div class="card">
        <div class="card-header">Daftar Jersey</div>
        <div class="card-body">
            <div class="jersey-single-container">
                <h3 class="jersey-main-title">Galeri Jersey Tim</h3>
                <div class="jersey-image-container">
                    <div class="jersey-single-item">
                        <p>Jersey Kandang</p>
                        <div class="no-image">
                            <i class="fas fa-tshirt"></i>
                            <br>
                            No Image
                        </div>
                    </div>
                    <div class="jersey-single-item">
                        <p>Jersey Tandang</p>
                        <div class="no-image">
                            <i class="fas fa-tshirt"></i>
                            <br>
                            No Image
                        </div>
                    </div>
                    <div class="jersey-single-item">
                        <p>Jersey Alternatif</p>
                        <div class="no-image">
                            <i class="fas fa-tshirt"></i>
                            <br>
                            No Image
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Confirmation for all actions
    document.addEventListener('DOMContentLoaded', function() {
        // Player detail button click
        const detailButtons = document.querySelectorAll('.btn-detail');
        detailButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                alert('Fitur detail pemain akan segera tersedia!');
                e.preventDefault();
            });
        });

        // Link to recommendation letter
        const letterLink = document.querySelector('a[href="#"]');
        if (letterLink) {
            letterLink.addEventListener('click', function(e) {
                alert('Surat rekomendasi belum tersedia!');
                e.preventDefault();
            });
        }
    });
</script>
@endpush
@endsection