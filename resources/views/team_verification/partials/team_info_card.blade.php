{{-- resources/views/team_verification/partials/team_info_card.blade.php --}}
@props(['team', 'category', 'stats' => []])

@php
    // Fungsi untuk mendapatkan URL logo dengan fallback (mengikuti TeamListProfileController)
    function getTeamLogoUrl($team) {
        if (!$team) return null;
        
        if (!empty($team->school_logo)) {
            $logoFile = basename($team->school_logo);
            
            // Priority 1: public/storage/school_logos/
            if (file_exists(public_path('storage/school_logos/' . $logoFile))) {
                return asset('storage/school_logos/' . $logoFile) . '?v=' . time();
            }
            
            // Priority 2: storage/app/public/school_logos/ via Storage facade
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists('school_logos/' . $logoFile)) {
                return \Illuminate\Support\Facades\Storage::url('school_logos/' . $logoFile) . '?v=' . time();
            }
            
            // Priority 3: public/school_logos/
            if (file_exists(public_path('school_logos/' . $logoFile))) {
                return asset('school_logos/' . $logoFile) . '?v=' . time();
            }
            
            // Priority 4: public/uploads/school_logos/
            if (file_exists(public_path('uploads/school_logos/' . $logoFile))) {
                return asset('uploads/school_logos/' . $logoFile) . '?v=' . time();
            }
            
            // Priority 5: langsung dari path database
            if (file_exists(public_path($team->school_logo))) {
                return asset($team->school_logo) . '?v=' . time();
            }
        }
        
        // Fallback ke logo_url jika ada
        if (isset($team->logo_url) && $team->logo_url) {
            if (filter_var($team->logo_url, FILTER_VALIDATE_URL)) {
                return $team->logo_url;
            }
            
            if (file_exists(public_path($team->logo_url))) {
                return asset($team->logo_url) . '?v=' . time();
            }
            
            return $team->logo_url;
        }
        
        return null;
    }
    
    $logoUrl = getTeamLogoUrl($team);
    $schoolName = $team->school_name ?? $team->team_name ?? 'N/A';
    $leaderName = $team->registered_by ?? 'N/A';
    
    // Ambil data leader dari players jika ada
    if (isset($stats['leader_name']) && $stats['leader_name']) {
        $leaderName = $stats['leader_name'];
    }
@endphp

<div class="team-info">
    <!-- Logo Column -->
    <div class="logo-column">
        <div class="logo-box-square" onclick="showLogoPopup('{{ $logoUrl }}', '{{ $schoolName }}', '{{ $category }}')">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" 
                     alt="Logo Sekolah {{ $schoolName }}"
                     id="team-logo-{{ $team->team_id ?? '0' }}"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="logo-placeholder" style="display: none;">
                    <i class="fas fa-school"></i>
                    <span>Logo Tidak Ditemukan</span>
                </div>
            @else
                <div class="logo-placeholder">
                    <i class="fas fa-school"></i>
                    <span>No Logo</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Content Column -->
    <div class="content-column">
        <div class="content-grid">
            <!-- Basic Info -->
            <div class="info-section">
                <table class="info-table">
                    <tr>
                        <td>ID Tim</td>
                        <td>: <strong>{{ $team->referral_code ?? 'N/A' }}</strong></td>
                    </tr>
                    <tr>
                        <td>Leader</td>
                        <td>: {{ $leaderName }}</td>
                    </tr>
                    <tr>
                        <td>Nama Sekolah</td>
                        <td>: <strong>{{ $schoolName }}</strong></td>
                    </tr>
                    <tr>
                        <td>Kompetisi</td>
                        <td>: {{ $team->competition ?? 'HSBL' }}</td>
                    </tr>
                    <tr>
                        <td>Musim</td>
                        <td>: {{ $team->season ?? date('Y') }}</td>
                    </tr>
                    <tr>
                        <td>Seri</td>
                        <td>: {{ $team->series ?? '1' }}</td>
                    </tr>
                    <tr>
                        <td>Wilayah</td>
                        <td>: {{ $team->region ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Status and Documents -->
            <div class="status-doc-section">
                <!-- Status -->
                <div class="status-section">
                    <table class="info-table compact">
                        <tr>
                            <td>Status Terkunci</td>
                            <td>:
                                @if($team->locked_status == 'locked')
                                    <span class="status-locked">
                                        <i class="fas fa-lock"></i> Terkunci
                                    </span>
                                @else
                                    <span class="status-open">
                                        <i class="fas fa-lock-open"></i> Terbuka
                                    </span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Status Verifikasi</td>
                            <td>:
                                @if($team->verification_status == 'verified')
                                    <span class="status-verified">
                                        <i class="fas fa-check-circle"></i> Terverifikasi
                                    </span>
                                @else
                                    <span class="status-unverified">
                                        <i class="fas fa-clock"></i> Belum Diverifikasi
                                    </span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>

                <!-- Documents -->
                <div class="documents-section">
                    <h4><i class="fas fa-file-alt"></i> Dokumen</h4>
                    <div class="document-links compact">
                        <!-- Surat Rekomendasi -->
                        @if($team->recommendation_letter)
                            @php
                                $recPath = null;
                                if (file_exists(public_path('storage/' . $team->recommendation_letter))) {
                                    $recPath = asset('storage/' . $team->recommendation_letter) . '?v=' . time();
                                } elseif (file_exists(storage_path('app/public/' . $team->recommendation_letter))) {
                                    $recPath = asset('storage/' . $team->recommendation_letter) . '?v=' . time();
                                }
                            @endphp
                            @if($recPath)
                                <a href="{{ $recPath }}" target="_blank" class="document-link available">
                                    <i class="fas fa-file-pdf"></i>
                                    <span>Surat Rekomendasi</span>
                                    <i class="fas fa-external-link-alt ms-auto" style="font-size: 12px;"></i>
                                </a>
                            @else
                                <a href="#" class="document-link warning" onclick="showAlert('Surat Rekomendasi')">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>Surat Rekomendasi (File Error)</span>
                                </a>
                            @endif
                        @else
                            <a href="#" class="document-link warning" onclick="showAlert('Surat Rekomendasi')">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>Surat Rekomendasi (Belum Upload)</span>
                            </a>
                        @endif

                        <!-- Bukti Langganan Koran -->
                        @if($team->koran)
                            @php
                                $koranPath = null;
                                if (file_exists(public_path('storage/' . $team->koran))) {
                                    $koranPath = asset('storage/' . $team->koran) . '?v=' . time();
                                } elseif (file_exists(storage_path('app/public/' . $team->koran))) {
                                    $koranPath = asset('storage/' . $team->koran) . '?v=' . time();
                                }
                            @endphp
                            @if($koranPath)
                                <a href="{{ $koranPath }}" target="_blank" class="document-link available">
                                    <i class="fas fa-newspaper"></i>
                                    <span>Bukti Langganan Koran</span>
                                    <i class="fas fa-external-link-alt ms-auto" style="font-size: 12px;"></i>
                                </a>
                            @else
                                <a href="#" class="document-link danger" onclick="showAlert('Bukti Langganan Koran')">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Bukti Langganan Koran (File Error)</span>
                                </a>
                            @endif
                        @else
                            <a href="#" class="document-link danger" onclick="showAlert('Bukti Langganan Koran')">
                                <i class="fas fa-times-circle"></i>
                                <span>Bukti Langganan Koran (Belum Upload)</span>
                            </a>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <h4><i class="fas fa-cogs"></i> Aksi Tim</h4>
                    <div class="action-buttons-row compact">
                        @if($team->locked_status != 'locked')
                            <form action="{{ route('admin.team.lock', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Kunci tim {{ $category }} {{ $schoolName }}?')">
                                @csrf
                                <button type="submit" class="btn-action-simple btn-lock">
                                    <i class="fas fa-lock"></i> Kunci Tim
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.team.unlock', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Buka kunci tim {{ $category }} {{ $schoolName }}?')">
                                @csrf
                                <button type="submit" class="btn-action-simple btn-unlock">
                                    <i class="fas fa-unlock"></i> Buka Kunci
                                </button>
                            </form>
                        @endif

                        @if($team->verification_status != 'verified')
                            <form action="{{ route('admin.team.verify', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Verifikasi tim {{ $category }} {{ $schoolName }}?')">
                                @csrf
                                <button type="submit" class="btn-action-simple btn-verify">
                                    <i class="fas fa-check"></i> Verifikasi Tim
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.team.unverify', $team->team_id) }}" method="POST" class="d-inline" onsubmit="return confirmAction('Batalkan verifikasi tim {{ $category }} {{ $schoolName }}?')">
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