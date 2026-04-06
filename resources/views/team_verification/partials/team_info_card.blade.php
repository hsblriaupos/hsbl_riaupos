@props(['team', 'category', 'stats' => []])

@php
    // Fungsi untuk mendapatkan URL logo dengan fallback
    function getTeamLogoUrl($team) {
        if (!$team) return null;
        
        if (!empty($team->school_logo)) {
            $logoFile = basename($team->school_logo);
            
            if (file_exists(public_path('storage/school_logos/' . $logoFile))) {
                return asset('storage/school_logos/' . $logoFile) . '?v=' . time();
            }
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists('school_logos/' . $logoFile)) {
                return \Illuminate\Support\Facades\Storage::url('school_logos/' . $logoFile) . '?v=' . time();
            }
            if (file_exists(public_path('school_logos/' . $logoFile))) {
                return asset('school_logos/' . $logoFile) . '?v=' . time();
            }
            if (file_exists(public_path('uploads/school_logos/' . $logoFile))) {
                return asset('uploads/school_logos/' . $logoFile) . '?v=' . time();
            }
            if (file_exists(public_path($team->school_logo))) {
                return asset($team->school_logo) . '?v=' . time();
            }
        }
        
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
    
    if (isset($stats['leader_name']) && $stats['leader_name']) {
        $leaderName = $stats['leader_name'];
    }
@endphp

<div class="team-info">
    <!-- Logo Column -->
    <div class="logo-column">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" 
                 alt="Logo Sekolah {{ $schoolName }}"
                 id="team-logo-{{ $team->team_id ?? '0' }}"
                 class="team-logo-img"
                 onclick="showLogoPopup('{{ $logoUrl }}', '{{ $schoolName }}', '{{ $category }}')"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="logo-placeholder" style="display: none;" onclick="showLogoPopup(null, '{{ $schoolName }}', '{{ $category }}')">
                <i class="fas fa-school"></i>
                <span>Logo Tidak Ditemukan</span>
            </div>
        @else
            <div class="logo-placeholder" onclick="showLogoPopup(null, '{{ $schoolName }}', '{{ $category }}')">
                <i class="fas fa-school"></i>
                <span>No Logo</span>
            </div>
        @endif
    </div>

    <!-- Content Column -->
    <div class="content-column">
        <div class="content-grid">
            <!-- Basic Info -->
            <div class="info-section">
                <table class="info-table">
                    <tr>
                        <td><strong>ID Tim</strong></td>
                        <td>: <strong style="font-family: monospace; font-size: 14px; color: #2563eb;">{{ $team->referral_code ?? 'N/A' }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Leader</strong></strong></td>
                        <td>: {{ $leaderName }}</td>
                    </tr>
                    <tr>
                        <td><strong>Nama Sekolah</strong></strong></td>
                        <td>: <strong>{{ $schoolName }}</strong></td>
                    </tr>
                    <tr>
                        <td><strong>Kompetisi</strong></strong></td>
                        <td>: {{ $team->competition ?? 'HSBL' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Musim</strong></strong></td>
                        <td>: {{ $team->season ?? date('Y') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Seri</strong></strong></td>
                        <td>: {{ $team->series ?? '1' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status Terkunci</strong></strong></td>
                        <td>: {{ $team->locked_status == 'locked' ? '🔒 Terkunci' : '🔓 Terbuka' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status Verifikasi</strong></strong></td>
                        <td>: {{ $team->verification_status == 'verified' ? '✅ Terverifikasi' : '⏳ Belum Diverifikasi' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Documents -->
            <div class="status-doc-section">
                <div class="documents-section">
                    <h4><i class="fas fa-file-alt"></i> Dokumen</h4>
                    <div class="document-links compact">
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
                                    <i class="fas fa-external-link-alt ms-auto" style="font-size: 11px;"></i>
                                </a>
                            @else
                                <a href="#" class="document-link warning" onclick="showAlert('Surat Rekomendasi')">
                                    <i class="fas fa-exclamation-circle"></i>
                                    <span>Surat Rekomendasi (Error)</span>
                                </a>
                            @endif
                        @else
                            <a href="#" class="document-link warning" onclick="showAlert('Surat Rekomendasi')">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>Surat Rekomendasi</span>
                            </a>
                        @endif

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
                                    <span>Bukti Koran</span>
                                    <i class="fas fa-external-link-alt ms-auto" style="font-size: 11px;"></i>
                                </a>
                            @else
                                <a href="#" class="document-link danger" onclick="showAlert('Bukti Langganan Koran')">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Bukti Koran (Error)</span>
                                </a>
                            @endif
                        @else
                            <a href="#" class="document-link danger" onclick="showAlert('Bukti Langganan Koran')">
                                <i class="fas fa-times-circle"></i>
                                <span>Bukti Koran</span>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .content-grid {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    
    .info-section {
        flex: 2;
        min-width: 280px;
    }
    
    .status-doc-section {
        flex: 1;
        min-width: 220px;
    }
    
    .logo-column {
        flex: 0 0 120px;
    }
    
    .team-logo-img {
        width: 120px;
        height: 120px;
        object-fit: contain;
        cursor: pointer;
        border-radius: 12px;
        transition: transform 0.3s ease;
        background: white;
        padding: 10px;
        border: 1px solid #e2e8f0;
    }
    
    .team-logo-img:hover {
        transform: scale(1.05);
        border-color: #3b82f6;
    }
    
    .logo-placeholder {
        width: 120px;
        height: 120px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #f8fafc;
        border-radius: 12px;
        color: #94a3b8;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: 1px dashed #cbd5e1;
    }
    
    .logo-placeholder:hover {
        background: #f1f5f9;
        transform: scale(1.02);
    }
    
    .logo-placeholder i {
        font-size: 40px;
        margin-bottom: 8px;
    }
    
    .info-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 8px;
        overflow: hidden;
    }
    
    .info-table td {
        padding: 10px 12px;
        vertical-align: middle;
        font-size: 13px;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .info-table td:first-child {
        width: 130px;
        color: #475569;
        font-weight: 600;
        background: #f8fafc;
        border-right: 1px solid #e2e8f0;
    }
    
    .documents-section h4 {
        font-size: 12px;
        font-weight: 600;
        color: #475569;
        margin-bottom: 10px;
        padding-bottom: 6px;
        border-bottom: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .document-links.compact {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    
    .document-link {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        font-size: 11px;
        font-weight: 500;
        padding: 6px 10px;
        border-radius: 6px;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    
    .document-link.available {
        color: #1e40af;
        background: #eff6ff;
        border-color: #dbeafe;
    }
    
    .document-link.warning {
        color: #92400e;
        background: #fffbeb;
        border-color: #fde68a;
    }
    
    .document-link.danger {
        color: #7f1d1d;
        background: #fef2f2;
        border-color: #fecaca;
    }
    
    .document-link i:first-child {
        width: 16px;
        font-size: 12px;
    }
    
    @media (max-width: 768px) {
        .logo-column {
            flex: 0 0 100px;
        }
        .team-logo-img, .logo-placeholder {
            width: 100px;
            height: 100px;
        }
        .info-table td:first-child {
            width: 100px;
            font-size: 11px;
        }
        .info-table td {
            font-size: 11px;
            padding: 8px 10px;
        }
        .document-link {
            font-size: 10px;
            padding: 5px 8px;
        }
    }
</style>