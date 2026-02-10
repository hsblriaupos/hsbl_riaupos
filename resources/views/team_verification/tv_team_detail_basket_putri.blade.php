@if($teamData['Basket Putri']['exists'])
@php
$teamInfo = $teamData['Basket Putri'];
$team = $teamInfo['team'];
$players = $teamInfo['players'];
$officials = $teamInfo['officials'];
@endphp

<style>
    /* Team Information Grid */
    .team-info {
        display: grid;
        grid-template-columns: 180px 1fr;
        gap: 25px;
        align-items: start;
    }

    /* Logo Column */
    .logo-column {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .logo-box-square {
        text-align: center;
        padding: 20px;
        background: #f8fafc;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 15px;
        width: 180px;
        height: 180px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
    }

    .logo-box-square:hover {
        border-color: #ec4899;
        box-shadow: 0 4px 15px rgba(236, 72, 153, 0.2);
        transform: translateY(-3px);
    }

    .logo-box-square img {
        width: 140px;
        height: 140px;
        object-fit: contain;
        margin-bottom: 10px;
        border-radius: 6px;
        background: white;
        padding: 10px;
        border: 1px solid #e2e8f0;
    }

    .logo-placeholder {
        width: 140px;
        height: 140px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #fdf2f8, #fce7f3);
        border: 2px dashed #fbcfe8;
        border-radius: 10px;
        color: #ec4899;
        font-size: 14px;
        margin-bottom: 10px;
        transition: all 0.3s ease;
    }

    .logo-box-square:hover .logo-placeholder {
        border-color: #ec4899;
        background: linear-gradient(135deg, #fce7f3, #fbcfe8);
    }

    .logo-placeholder i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #ec4899;
    }

    /* Content Column */
    .content-column {
        display: flex;
        flex-direction: column;
    }

    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
    }

    .info-section,
    .status-doc-section {
        display: flex;
        flex-direction: column;
    }

    /* Info Tables */
    .info-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .info-table tr {
        border-bottom: 1px solid #f1f5f9;
    }

    .info-table tr:last-child {
        border-bottom: none;
    }

    .info-table td {
        padding: 12px 15px;
        vertical-align: middle;
        font-size: 14px;
        color: #4a5568;
    }

    .info-table td:first-child {
        width: 150px;
        color: #2d3748;
        font-weight: 600;
        background: #f8fafc;
        border-right: 1px solid #e2e8f0;
    }

    .info-table.compact td {
        padding: 10px 12px;
        font-size: 13px;
    }

    .info-table.compact td:first-child {
        width: 140px;
    }

    /* Status Styles */
    .status-open {
        color: #dc2626;
        font-weight: 600;
        background: #fef2f2;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border: 1px solid #fecaca;
    }

    .status-locked {
        color: #059669;
        font-weight: 600;
        background: #d1fae5;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border: 1px solid #a7f3d0;
    }

    .status-verified {
        color: #059669;
        font-weight: 600;
        background: #d1fae5;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border: 1px solid #a7f3d0;
    }

    .status-unverified {
        color: #d97706;
        font-weight: 600;
        background: #fef3c7;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border: 1px solid #fde68a;
    }

    /* Documents Section */
    .documents-section h4 {
        font-size: 15px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 2px solid #fce7f3;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .documents-section h4 i {
        color: #ec4899;
    }

    .document-links.compact {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .document-link {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        padding: 12px 15px;
        border-radius: 8px;
        transition: all 0.2s ease;
        width: 100%;
        box-sizing: border-box;
        border: 1px solid transparent;
    }

    .document-link.available {
        color: #1e40af;
        background: #eff6ff;
        border-color: #dbeafe;
    }

    .document-link.available:hover {
        background: #e1f5ff;
        color: #1e3a8a;
        border-color: #93c5fd;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(66, 153, 225, 0.15);
    }

    .document-link.available i {
        color: #3b82f6;
        font-size: 16px;
        min-width: 20px;
    }

    .document-link.warning {
        color: #92400e;
        background: #fffbeb;
        border-color: #fde68a;
    }

    .document-link.warning:hover {
        background: #fef3c7;
        color: #78350f;
        border-color: #fcd34d;
    }

    .document-link.warning i {
        color: #f59e0b;
    }

    .document-link.danger {
        color: #991b1b;
        background: #fef2f2;
        border-color: #fecaca;
    }

    .document-link.danger:hover {
        background: #fee2e2;
        color: #7f1d1d;
        border-color: #fca5a5;
    }

    .document-link.danger i {
        color: #ef4444;
    }

    /* Action Buttons */
    .action-buttons h4 {
        font-size: 15px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 2px solid #fce7f3;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-buttons h4 i {
        color: #ec4899;
    }

    .action-buttons-row {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .action-buttons-row.compact {
        gap: 10px;
    }

    /* Button Styles */
    .btn-action-simple {
        color: #fff;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        white-space: nowrap;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-action-simple:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        color: white;
    }

    .btn-action-simple i {
        font-size: 14px;
    }

    .btn-lock {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .btn-lock:hover {
        background: linear-gradient(135deg, #047857 0%, #065f46 100%);
    }

    .btn-unlock {
        background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%);
    }

    .btn-unlock:hover {
        background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    }

    .btn-verify {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .btn-verify:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }

    .btn-unverify {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }

    .btn-unverify:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
    }

    /* Table Styles */
    .table-container {
        overflow-x: auto;
        border-radius: 10px;
        border: 2px solid #fce7f3;
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    table.data {
        width: 100%;
        border-collapse: collapse;
        font-size: 14px;
        min-width: 1000px;
    }

    table.data th {
        background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);
        font-weight: 600;
        color: #2d3748;
        padding: 16px 15px;
        text-align: left;
        border-bottom: 2px solid #fce7f3;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        white-space: nowrap;
    }

    table.data td {
        border-bottom: 1px solid #f1f5f9;
        padding: 14px 15px;
        vertical-align: middle;
        color: #4a5568;
        font-size: 14px;
    }

    table.data tr:hover {
        background-color: #fdf2f8;
    }

    table.data tr:last-child td {
        border-bottom: none;
    }

    /* Button Detail */
    .btn-detail {
        background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 2px 6px rgba(236, 72, 153, 0.2);
        min-width: 100px;
        height: 36px;
        cursor: pointer;
    }

    .btn-detail:hover {
        background: linear-gradient(135deg, #db2777 0%, #9d174d 100%);
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);
        color: white;
    }

    /* Jersey Gallery */
    .jersey-single-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 30px;
        background: linear-gradient(135deg, #fdf2f8 0%, #fce7f3 100%);
        border-radius: 12px;
        border: 2px solid #fce7f3;
        min-height: 320px;
    }

    .jersey-main-title {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 25px;
        text-align: center;
        position: relative;
        padding-bottom: 10px;
    }

    .jersey-main-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 3px;
        background: linear-gradient(135deg, #ec4899 0%, #be185d 100%);
        border-radius: 3px;
    }

    .jersey-image-container {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 25px;
        width: 100%;
        max-width: 900px;
    }

    .jersey-single-item {
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        background: white;
        border-radius: 10px;
        padding: 20px;
        border: 2px solid #fce7f3;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .jersey-single-item:hover {
        transform: translateY(-5px);
        border-color: #ec4899;
        box-shadow: 0 8px 20px rgba(236, 72, 153, 0.15);
    }

    .jersey-single-item p {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
        margin-top: 0;
    }

    .jersey-image {
        width: 100%;
        height: 200px;
        object-fit: contain;
        border-radius: 6px;
        background: white;
        padding: 15px;
        border: 1px solid #e2e8f0;
        margin-bottom: 10px;
    }

    .no-image {
        width: 100%;
        height: 200px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #f7fafc, #edf2f7);
        border: 2px dashed #cbd5e0;
        border-radius: 6px;
        color: #718096;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .no-image i {
        font-size: 3rem;
        margin-bottom: 15px;
        color: #a0aec0;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 40px 20px;
        background: #f8fafc;
        border-radius: 10px;
        border: 2px dashed #fce7f3;
        margin: 20px 0;
    }

    .empty-state i {
        font-size: 3.5rem;
        color: #ec4899;
        margin-bottom: 20px;
        opacity: 0.7;
    }

    .empty-state h5 {
        font-size: 18px;
        color: #2c3e50;
        margin-bottom: 10px;
        font-weight: 600;
    }

    .empty-state p {
        font-size: 14px;
        color: #718096;
        margin-bottom: 15px;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .team-info {
            grid-template-columns: 160px 1fr;
            gap: 20px;
        }

        .logo-box-square {
            width: 160px;
            height: 160px;
        }

        .logo-box-square img {
            width: 120px;
            height: 120px;
        }
    }

    @media (max-width: 992px) {
        .team-info {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .logo-column {
            align-items: center;
        }

        .content-grid {
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .jersey-image-container {
            grid-template-columns: 1fr;
            gap: 20px;
            max-width: 400px;
        }
    }

    @media (max-width: 768px) {
        .info-table td {
            padding: 10px 12px;
            font-size: 13px;
        }

        .info-table td:first-child {
            width: 130px;
        }

        .action-buttons-row.compact {
            flex-direction: column;
        }

        table.data {
            min-width: 800px;
        }

        .jersey-single-item {
            padding: 15px;
        }

        .jersey-image,
        .no-image {
            height: 180px;
        }
    }

    @media (max-width: 576px) {
        .logo-box-square {
            width: 140px;
            height: 140px;
            padding: 15px;
        }

        .logo-box-square img {
            width: 100px;
            height: 100px;
        }

        .logo-placeholder {
            width: 100px;
            height: 100px;
        }

        .btn-action-simple {
            padding: 10px 15px;
            font-size: 13px;
        }

        .btn-detail {
            padding: 6px 12px;
            font-size: 12px;
            min-width: 80px;
            height: 32px;
        }

        .jersey-main-title {
            font-size: 18px;
        }
    }
</style>

<div class="card">
    <div class="card-header" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); color: white;">
        <i class="fas fa-info-circle"></i>
        <span>Informasi Tim Basket Putri</span>
    </div>
    <div class="card-body">
        <div class="team-info">
            <!-- Logo di Kiri -->
            <div class="logo-column">
                <div class="logo-box-square" onclick="showLogoPopup()">
                    @if($team->school_logo)
                    @php
                    $logoPath = storage_path('app/public/' . $team->school_logo);
                    $logoExists = file_exists($logoPath);
                    @endphp

                    @if($logoExists)
                    <img src="{{ asset('storage/' . $team->school_logo) }}"
                        alt="Logo Sekolah {{ $team->school_name }}"
                        id="team-logo"
                        onerror="this.style.display='none'; document.getElementById('logo-placeholder-fallback').style.display='flex';">
                    <div class="logo-placeholder" id="logo-placeholder-fallback" style="display: none;">
                        <i class="fas fa-school"></i>
                        <span>Logo Tidak Ditemukan</span>
                    </div>
                    @else
                    <div class="logo-placeholder">
                        <i class="fas fa-school"></i>
                        <span>Logo Tidak Ditemukan</span>
                    </div>
                    @endif
                    @else
                    <div class="logo-placeholder">
                        <i class="fas fa-school"></i>
                        <span>No Logo</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Konten di Kanan -->
            <div class="content-column">
                <div class="content-grid">
                    <!-- Info Dasar -->
                    <div class="info-section">
                        <table class="info-table">
                            <tr>
                                <td>ID Tim</td>
                                <td>: <strong>{{ $team->referral_code ?? 'N/A' }}</strong></td>
                            </tr>
                            <tr>
                                <td>Leader</td>
                                <td>: {{ $team->registered_by ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Nama Sekolah</td>
                                <td>: <strong>{{ $team->school_name }}</strong></td>
                            </tr>
                            <tr>
                                <td>Kompetisi</td>
                                <td>: {{ $team->competition ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Musim</td>
                                <td>: {{ $team->season ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Seri</td>
                                <td>: {{ $team->series ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td>Wilayah</td>
                                <td>: {{ $team->region ?? '-' }}</td>
                            </tr>
                        </table>
                    </div>

                    <!-- Status dan Dokumen -->
                    <div class="status-doc-section">
                        <!-- Status -->
                        <div class="status-section">
                            <table class="info-table compact">
                                <tr>
                                    <td>Status Terkunci</td>
                                    <td>:
                                        @if($team->locked_status == 'locked')
                                        <span class="status-locked"><i class="fas fa-lock"></i> Terkunci</span>
                                        @else
                                        <span class="status-open"><i class="fas fa-lock-open"></i> Terbuka</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Status Verifikasi</td>
                                    <td>:
                                        @if($team->verification_status == 'verified')
                                        <span class="status-verified"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                                        @else
                                        <span class="status-unverified"><i class="fas fa-clock"></i> Belum Diverifikasi</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Dokumen -->
                        <div class="documents-section">
                            <h4><i class="fas fa-file-alt"></i> Dokumen</h4>
                            <div class="document-links compact">
                                <!-- Surat Rekomendasi -->
                                @if($team->recommendation_letter)
                                <a href="{{ asset('storage/' . $team->recommendation_letter) }}"
                                    target="_blank"
                                    class="document-link available">
                                    <i class="fas fa-file-pdf"></i>
                                    <span>Surat Rekomendasi</span>
                                    <i class="fas fa-external-link-alt ms-auto" style="font-size: 12px;"></i>
                                </a>
                                @else
                                <a href="#"
                                    class="document-link warning mb-2"
                                    onclick="showAlert('Surat Rekomendasi')">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>Surat Rekomendasi (Belum Upload)</span>
                                </a>
                                @endif

                                <!-- Bukti Langganan Koran -->
                                @if($team->koran)
                                <a href="{{ asset('storage/' . $team->koran) }}"
                                    target="_blank"
                                    class="document-link available">
                                    <i class="fas fa-newspaper"></i>
                                    <span>Bukti Langganan Koran</span>
                                    <i class="fas fa-external-link-alt ms-auto" style="font-size: 12px;"></i>
                                </a>
                                @else
                                <a href="#"
                                    class="document-link danger"
                                    onclick="showAlert('Bukti Langganan Koran')">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Bukti Langganan Koran (Belum Upload)</span>
                                </a>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons mt-2">
                            <h4><i class="fas fa-cogs"></i> Aksi Tim</h4>
                            <div class="action-buttons-row compact">
                                @if($team->locked_status != 'locked')
                                <form action="{{ route('admin.team.lock', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Kunci tim Basket Putri {{ $team->school_name }}?')">
                                    @csrf
                                    <button type="submit" class="btn-action-simple btn-lock">
                                        <i class="fas fa-lock"></i> Kunci Tim
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.team.unlock', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Buka kunci tim Basket Putri {{ $team->school_name }}?')">
                                    @csrf
                                    <button type="submit" class="btn-action-simple btn-unlock">
                                        <i class="fas fa-unlock"></i> Buka Kunci
                                    </button>
                                </form>
                                @endif

                                @if($team->verification_status != 'verified')
                                <form action="{{ route('admin.team.verify', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Verifikasi tim Basket Putri {{ $team->school_name }}?')">
                                    @csrf
                                    <button type="submit" class="btn-action-simple btn-verify">
                                        <i class="fas fa-check"></i> Verifikasi Tim
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.team.unverify', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Batalkan verifikasi tim Basket Putri {{ $team->school_name }}?')">
                                    @csrf
                                    <button type="submit" class="btn-action-simple btn-unverify">
                                        <i class="fas fa-times"></i> Batalkan Verifikasi
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
</div>

<!-- Player List Card -->
<div class="card">
    <div class="card-header" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); color: white;">
        <i class="fas fa-users"></i>
        <span>Daftar Pemain Basket Putri ({{ $players->count() }})</span>
    </div>
    <div class="card-body">
        @if($players->count() > 0)
        <div class="table-container">
            <table class="data">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Nama</th>
                        <th>Role</th>
                        <th width="80">No. Jersey</th>
                        <th width="120">Tanggal Lahir</th>
                        <th width="100">Jenis Kelamin</th>
                        <th width="100">Posisi</th>
                        <th width="100">Kelas</th>
                        <th width="100">Tahun STTB</th>
                        <th width="100" class="text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($players as $index => $player)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $player->name ?? 'N/A' }}</strong></td>
                        <td>
                            @if($player->role == 'Leader')
                            <span class="status-locked"><i class="fas fa-crown"></i> Leader</span>
                            @else
                            <span class="status-open"><i class="fas fa-user"></i> Pemain</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span style="display: inline-block; width: 30px; height: 30px; line-height: 30px; background: #ec4899; color: white; border-radius: 50%; font-weight: bold;">
                                {{ $player->jersey_number ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @if($player->birthdate)
                            {{ \Carbon\Carbon::parse($player->birthdate)->isoFormat('D MMMM YYYY') }}
                            @else
                            <span style="color: #718096;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($player->gender == 'Male')
                            <i class="fas fa-mars text-primary me-1"></i> Laki-laki
                            @elseif($player->gender == 'Female')
                            <i class="fas fa-venus text-pink me-1"></i> Perempuan
                            @else
                            <span style="color: #718096;">-</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $player->basketball_position ?? '-' }}</span>
                        </td>
                        <td>{{ $player->grade ?? '-' }}</td>
                        <td>{{ $player->sttb_year ?? '-' }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.player.detail', $player->id) }}" class="btn-detail">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-users"></i>
            <h5>Belum ada data pemain</h5>
            <p>Data pemain akan muncul di sini setelah mendaftar menggunakan referral code tim ini.</p>
            <p class="text-muted mt-2" style="font-size: 12px;">
                <i class="fas fa-info-circle"></i>
                Tim ini memiliki referral code: <strong>{{ $team->referral_code ?? 'N/A' }}</strong>
            </p>
        </div>
        @endif
    </div>
</div>

<!-- Official List Card -->
<div class="card">
    <div class="card-header" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); color: white;">
        <i class="fas fa-user-tie"></i>
        <span>Daftar Official Basket Putri ({{ $officials->count() }})</span>
    </div>
    <div class="card-body">
        @if($officials->count() > 0)
        <div class="table-container">
            <table class="data">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Nama</th>
                        <th width="120">Jabatan</th>
                        <th width="150">Email</th>
                        <th width="100">Telepon</th>
                        <th width="120">Jenis Kelamin</th>
                        <th width="120">Status Verifikasi</th>
                        <th width="100" class="text-center">Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($officials as $index => $official)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $official->name ?? 'N/A' }}</strong></td>
                        <td>
                            <span class="badge bg-light text-dark border">{{ $official->team_role ?? '-' }}</span>
                        </td>
                        <td>
                            <a href="mailto:{{ $official->email }}" class="text-decoration-none">
                                <i class="fas fa-envelope me-1 text-primary"></i>
                                {{ $official->email ?? '-' }}
                            </a>
                        </td>
                        <td>
                            @if($official->phone)
                            <a href="tel:{{ $official->phone }}" class="text-decoration-none">
                                <i class="fas fa-phone me-1 text-success"></i>
                                {{ $official->phone }}
                            </a>
                            @else
                            <span style="color: #718096;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($official->gender == 'male')
                            <i class="fas fa-mars text-primary me-1"></i> Laki-laki
                            @elseif($official->gender == 'female')
                            <i class="fas fa-venus text-pink me-1"></i> Perempuan
                            @else
                            <span style="color: #718096;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($official->verification_status == 'verified')
                            <span class="status-verified"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                            @elseif($official->verification_status == 'rejected')
                            <span class="status-open"><i class="fas fa-times-circle"></i> Ditolak</span>
                            @else
                            <span class="status-unverified"><i class="fas fa-clock"></i> Belum Diverifikasi</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.official.detail', $official->official_id) }}" class="btn-detail">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h5>Belum ada data official</h5>
            <p>Data official akan muncul di sini setelah ditambahkan.</p>
        </div>
        @endif
    </div>
</div>

<!-- Jersey List Card -->
<div class="card">
    <div class="card-header" style="background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); color: white;">
        <i class="fas fa-tshirt"></i>
        <span>Daftar Jersey Basket Putri</span>
    </div>
    <div class="card-body">
        <div class="jersey-single-container">
            <h3 class="jersey-main-title">Galeri Jersey Tim Basket Putri</h3>
            <div class="jersey-image-container">
                <!-- Jersey Kandang -->
                <div class="jersey-single-item" onclick="showJerseyPopup('home')">
                    <p>Jersey Kandang</p>
                    @if($team->jersey_home)
                    <img src="{{ asset('storage/' . $team->jersey_home) }}"
                        alt="Jersey Kandang {{ $team->school_name }}"
                        class="jersey-image"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="no-image" style="display: none;">
                        <i class="fas fa-tshirt"></i>
                        <span>Gambar Error</span>
                    </div>
                    @else
                    <div class="no-image">
                        <i class="fas fa-tshirt"></i>
                        <span>Belum Upload</span>
                    </div>
                    @endif
                </div>

                <!-- Jersey Tandang -->
                <div class="jersey-single-item" onclick="showJerseyPopup('away')">
                    <p>Jersey Tandang</p>
                    @if($team->jersey_away)
                    <img src="{{ asset('storage/' . $team->jersey_away) }}"
                        alt="Jersey Tandang {{ $team->school_name }}"
                        class="jersey-image"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="no-image" style="display: none;">
                        <i class="fas fa-tshirt"></i>
                        <span>Gambar Error</span>
                    </div>
                    @else
                    <div class="no-image">
                        <i class="fas fa-tshirt"></i>
                        <span>Belum Upload</span>
                    </div>
                    @endif
                </div>

                <!-- Jersey Alternatif -->
                <div class="jersey-single-item" onclick="showJerseyPopup('alternate')">
                    <p>Jersey Alternatif</p>
                    @if($team->jersey_alternate)
                    <img src="{{ asset('storage/' . $team->jersey_alternate) }}"
                        alt="Jersey Alternatif {{ $team->school_name }}"
                        class="jersey-image"
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="no-image" style="display: none;">
                        <i class="fas fa-tshirt"></i>
                        <span>Gambar Error</span>
                    </div>
                    @else
                    <div class="no-image">
                        <i class="fas fa-tshirt"></i>
                        <span>Belum Upload</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Basket Putri Detail Page Loaded');
    });

    // Function to show alert for missing documents
    window.showAlert = function(documentName) {
        Swal.fire({
            title: 'Dokumen Tidak Tersedia',
            html: `<strong>${documentName}</strong> belum diupload oleh tim.<br><br>
              Silakan hubungi tim untuk mengupload dokumen ini.`,
            icon: 'warning',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#ec4899',
            showCancelButton: false,
            timer: 5000,
            timerProgressBar: true
        });
        return false;
    };

    // Function for confirmation actions
    window.confirmAction = function(message) {
        return Swal.fire({
            title: 'Konfirmasi',
            text: message,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ec4899',
            cancelButtonColor: '#6b7280',
            reverseButtons: true
        }).then((result) => {
            return result.isConfirmed;
        });
    };

    // Function to show logo popup
    window.showLogoPopup = function() {
        const logoImg = document.getElementById('team-logo');

        if (logoImg && logoImg.src && !logoImg.src.includes('logo-placeholder')) {
            Swal.fire({
                title: 'Logo Sekolah',
                html: `<div style="text-align: center;">
                    <img src="${logoImg.src}" alt="Logo Sekolah" style="max-width: 300px; max-height: 300px; border-radius: 8px; margin-bottom: 15px;">
                    <p style="color: #666; font-size: 14px;">{{ $team->school_name }} - Basket Putri</p>
                  </div>`,
                showCloseButton: true,
                showConfirmButton: false,
                width: 500,
                padding: '20px',
                background: '#fff',
                customClass: {
                    popup: 'logo-popup'
                }
            });
        } else {
            Swal.fire({
                title: 'Logo Sekolah',
                html: `<div style="text-align: center;">
                    <div style="width: 200px; height: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #fdf2f8, #fce7f3); border: 2px dashed #fbcfe8; border-radius: 8px; margin: 0 auto 15px;">
                        <i class="fas fa-school" style="font-size: 3rem; color: #ec4899; margin-bottom: 15px;"></i>
                        <span style="color: #ec4899;">Logo Tidak Tersedia</span>
                    </div>
                    <p style="color: #666; font-size: 14px;">{{ $team->school_name }} - Basket Putri</p>
                  </div>`,
                showCloseButton: true,
                showConfirmButton: false,
                width: 450,
                padding: '20px',
                background: '#fff',
                customClass: {
                    popup: 'logo-popup'
                }
            });
        }
    };

    // Function to show jersey popup
    window.showJerseyPopup = function(type) {
        const jerseyNames = {
            'home': 'Jersey Kandang',
            'away': 'Jersey Tandang',
            'alternate': 'Jersey Alternatif'
        };

        const jerseyName = jerseyNames[type] || 'Jersey';
        const jerseyItem = document.querySelector(`.jersey-single-item:nth-child(${type === 'home' ? 1 : type === 'away' ? 2 : 3})`);

        if (!jerseyItem) return;

        const jerseyImg = jerseyItem.querySelector('.jersey-image');

        if (jerseyImg && jerseyImg.style.display !== 'none' && jerseyImg.src) {
            Swal.fire({
                title: `${jerseyName} - Basket Putri`,
                html: `<div style="text-align: center;">
                    <img src="${jerseyImg.src}" alt="${jerseyName}" style="max-width: 400px; max-height: 400px; border-radius: 8px; margin-bottom: 15px;">
                    <p style="color: #666; font-size: 14px;">{{ $team->school_name }}</p>
                  </div>`,
                showCloseButton: true,
                showConfirmButton: false,
                width: 550,
                padding: '20px',
                background: '#fff',
                customClass: {
                    popup: 'jersey-popup'
                }
            });
        } else {
            Swal.fire({
                title: `${jerseyName} - Basket Putri`,
                html: `<div style="text-align: center;">
                    <div style="width: 300px; height: 300px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #fdf2f8, #fce7f3); border: 2px dashed #fbcfe8; border-radius: 8px; margin: 0 auto 15px;">
                        <i class="fas fa-tshirt" style="font-size: 4rem; color: #ec4899; margin-bottom: 20px;"></i>
                        <span style="color: #ec4899; font-size: 16px;">${jerseyName} Belum Diupload</span>
                    </div>
                    <p style="color: #666; font-size: 14px;">{{ $team->school_name }}</p>
                  </div>`,
                showCloseButton: true,
                showConfirmButton: false,
                width: 500,
                padding: '20px',
                background: '#fff',
                customClass: {
                    popup: 'jersey-popup'
                }
            });
        }
    };
</script>
@endif