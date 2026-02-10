@if($teamData['Dancer']['exists'])
@php
$teamInfo = $teamData['Dancer'];
$team = $teamInfo['team'];
$players = $teamInfo['players'];
$officials = $teamInfo['officials'];
@endphp

<div class="card">
    <div class="card-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
        <i class="fas fa-info-circle"></i>
        <span>Informasi Tim Dancer</span>
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
                                        @else
                                        <span class="status-unverified">Belum Diverifikasi</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Dokumen -->
                        <div class="documents-section">
                            <h4>Dokumen</h4>
                            <div class="document-links compact">
                                <!-- Surat Rekomendasi -->
                                @if($team->recommendation_letter)
                                <a href="{{ asset('storage/' . $team->recommendation_letter) }}"
                                    target="_blank"
                                    class="document-link available">
                                    <i class="fas fa-file-pdf"></i>
                                    <span>Surat Rekomendasi</span>
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
                            <h4>Aksi Tim</h4>
                            <div class="action-buttons-row compact">
                                @if($team->locked_status != 'locked')
                                <form action="{{ route('admin.team.lock', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Kunci tim Dancer {{ $team->school_name }}?')">
                                    @csrf
                                    <button type="submit" class="btn-action-simple btn-lock">
                                        <i class="fas fa-lock"></i> Kunci Tim
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.team.unlock', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Buka kunci tim Dancer {{ $team->school_name }}?')">
                                    @csrf
                                    <button type="submit" class="btn-action-simple btn-unlock">
                                        <i class="fas fa-unlock"></i> Buka Kunci
                                    </button>
                                </form>
                                @endif

                                @if($team->verification_status != 'verified')
                                <form action="{{ route('admin.team.verify', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Verifikasi tim Dancer {{ $team->school_name }}?')">
                                    @csrf
                                    <button type="submit" class="btn-action-simple btn-verify">
                                        <i class="fas fa-check"></i> Verifikasi Tim
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('admin.team.unverify', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Batalkan verifikasi tim Dancer {{ $team->school_name }}?')">
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

<!-- Player List Card (Dancer) -->
<div class="card">
    <div class="card-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
        <i class="fas fa-music"></i>
        <span>Daftar Dancer ({{ $players->count() }})</span>
    </div>
    <div class="card-body">
        @if($players->count() > 0)
        <div class="table-container">
            <table class="data">
                <thead>
                    <tr>
                        <th width="50">#</th>
                        <th>Nama</th>
                        <th width="100">Role</th>
                        <th width="120">Tanggal Lahir</th>
                        <th width="100">Jenis Kelamin</th>
                        <th width="100">Tinggi</th>
                        <th width="100">Berat</th>
                        <th width="100">Ukuran Kaos</th>
                        <th width="100">Status Verifikasi</th>
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
                            <span class="status-locked">Leader</span>
                            @else
                            <span class="status-open">Member</span>
                            @endif
                        </td>
                        <td>
                            @if($player->birthdate)
                            {{ \Carbon\Carbon::parse($player->birthdate)->isoFormat('D MMMM YYYY') }}
                            @else
                            <span style="color: #718096;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($player->gender == 'male' || $player->gender == 'Laki-laki')
                            Laki-laki
                            @elseif($player->gender == 'female' || $player->gender == 'Perempuan')
                            Perempuan
                            @else
                            <span style="color: #718096;">-</span>
                            @endif
                        </td>
                        <td>{{ $player->height ?? '-' }} cm</td>
                        <td>{{ $player->weight ?? '-' }} kg</td>
                        <td>{{ $player->tshirt_size ?? '-' }}</td>
                        <td>
                            @if($player->verification_status == 'verified')
                            <span class="status-verified">Terverifikasi</span>
                            @elseif($player->verification_status == 'rejected')
                            <span class="status-open">Ditolak</span>
                            @else
                            <span class="status-unverified">Belum Diverifikasi</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('admin.dancer.detail', $player->dancer_id) }}" class="btn-detail">
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
            <i class="fas fa-music"></i>
            <h5>Belum ada data dancer</h5>
            <p>Data dancer akan muncul di sini setelah mendaftar menggunakan referral code tim ini.</p>
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
    <div class="card-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
        <i class="fas fa-user-tie"></i>
        <span>Daftar Official Dancer ({{ $officials->count() }})</span>
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
                        <td>{{ $official->team_role ?? '-' }}</td>
                        <td>{{ $official->email ?? '-' }}</td>
                        <td>{{ $official->phone ?? '-' }}</td>
                        <td>
                            @if($official->gender == 'male')
                            Laki-laki
                            @elseif($official->gender == 'female')
                            Perempuan
                            @else
                            <span style="color: #718096;">-</span>
                            @endif
                        </td>
                        <td>
                            @if($official->verification_status == 'verified')
                            <span class="status-verified">Terverifikasi</span>
                            @elseif($official->verification_status == 'rejected')
                            <span class="status-open">Ditolak</span>
                            @else
                            <span class="status-unverified">Belum Diverifikasi</span>
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

<!-- Costume List Card -->
<div class="card">
    <div class="card-header" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white;">
        <i class="fas fa-tshirt"></i>
        <span>Daftar Kostum Dancer</span>
    </div>
    <div class="card-body">
        <div class="jersey-single-container">
            <h3 class="jersey-main-title">Galeri Kostum Tim Dancer</h3>
            <div class="jersey-image-container">
                <!-- Kostum Utama -->
                <div class="jersey-single-item" onclick="showJerseyPopup('home')">
                    <p>Kostum Utama</p>
                    @if($team->jersey_home)
                    <img src="{{ asset('storage/' . $team->jersey_home) }}"
                        alt="Kostum Utama {{ $team->school_name }}"
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

                <!-- Kostum Alternatif -->
                <div class="jersey-single-item" onclick="showJerseyPopup('away')">
                    <p>Kostum Alternatif</p>
                    @if($team->jersey_away)
                    <img src="{{ asset('storage/' . $team->jersey_away) }}"
                        alt="Kostum Alternatif {{ $team->school_name }}"
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

                <!-- Kostum Khusus -->
                <div class="jersey-single-item" onclick="showJerseyPopup('alternate')">
                    <p>Kostum Khusus</p>
                    @if($team->jersey_alternate)
                    <img src="{{ asset('storage/' . $team->jersey_alternate) }}"
                        alt="Kostum Khusus {{ $team->school_name }}"
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
        console.log('Dancer Detail Page Loaded');
    });

    // Function to show alert for missing documents
    window.showAlert = function(documentName) {
        Swal.fire({
            title: 'Dokumen Tidak Tersedia',
            html: `<strong>${documentName}</strong> belum diupload oleh tim.<br><br>
              Silakan hubungi tim untuk mengupload dokumen ini.`,
            icon: 'warning',
            confirmButtonText: 'Mengerti',
            confirmButtonColor: '#f39c12',
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
            confirmButtonColor: '#8b5cf6',
            cancelButtonColor: '#e74c3c',
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
                    <p style="color: #666; font-size: 14px;">{{ $team->school_name }} - Dancer</p>
                  </div>`,
                showCloseButton: true,
                showConfirmButton: false,
                width: 500,
                padding: '20px',
                background: '#fff'
            });
        } else {
            Swal.fire({
                title: 'Logo Sekolah',
                html: `<div style="text-align: center;">
                    <div style="width: 200px; height: 200px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #f7fafc, #edf2f7); border: 2px dashed #cbd5e0; border-radius: 8px; margin: 0 auto 15px;">
                        <i class="fas fa-school" style="font-size: 3rem; color: #a0aec0; margin-bottom: 15px;"></i>
                        <span style="color: #718096;">Logo Tidak Tersedia</span>
                    </div>
                    <p style="color: #666; font-size: 14px;">{{ $team->school_name }} - Dancer</p>
                  </div>`,
                showCloseButton: true,
                showConfirmButton: false,
                width: 450,
                padding: '20px',
                background: '#fff'
            });
        }
    };

    // Function to show costume popup
    window.showJerseyPopup = function(type) {
        const costumeNames = {
            'home': 'Kostum Utama',
            'away': 'Kostum Alternatif',
            'alternate': 'Kostum Khusus'
        };

        const costumeName = costumeNames[type] || 'Kostum';
        const costumeItem = document.querySelector(`.jersey-single-item:nth-child(${type === 'home' ? 1 : type === 'away' ? 2 : 3})`);

        if (!costumeItem) return;

        const costumeImg = costumeItem.querySelector('.jersey-image');

        if (costumeImg && costumeImg.style.display !== 'none' && costumeImg.src) {
            Swal.fire({
                title: `${costumeName} - Dancer`,
                html: `<div style="text-align: center;">
                    <img src="${costumeImg.src}" alt="${costumeName}" style="max-width: 400px; max-height: 400px; border-radius: 8px; margin-bottom: 15px;">
                    <p style="color: #666; font-size: 14px;">{{ $team->school_name }}</p>
                  </div>`,
                showCloseButton: true,
                showConfirmButton: false,
                width: 550,
                padding: '20px',
                background: '#fff'
            });
        } else {
            Swal.fire({
                title: `${costumeName} - Dancer`,
                html: `<div style="text-align: center;">
                    <div style="width: 300px; height: 300px; display: flex; flex-direction: column; align-items: center; justify-content: center; background: linear-gradient(135deg, #f7fafc, #edf2f7); border: 2px dashed #cbd5e0; border-radius: 8px; margin: 0 auto 15px;">
                        <i class="fas fa-tshirt" style="font-size: 4rem; color: #a0aec0; margin-bottom: 20px;"></i>
                        <span style="color: #718096; font-size: 16px;">${costumeName} Belum Diupload</span>
                    </div>
                    <p style="color: #666; font-size: 14px;">{{ $team->school_name }}</p>
                  </div>`,
                showCloseButton: true,
                showConfirmButton: false,
                width: 500,
                padding: '20px',
                background: '#fff'
            });
        }
    };
</script>
@endif