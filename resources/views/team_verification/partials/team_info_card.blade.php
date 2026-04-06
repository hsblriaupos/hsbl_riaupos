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
            <!-- Basic Info - Lebar lebih besar untuk ID Tim -->
            <div class="info-section">
                <table class="info-table">
                    <tr>
                        <td><strong>ID Tim</strong></td>
                        <td>: <strong style="font-family: monospace; font-size: 14px; color: #2563eb;">{{ $team->referral_code ?? 'N/A' }}</strong></td>
                    </tr>
                    <tr>
                        <td>Leader</strong></td>
                        <td>: {{ $leaderName }}</td>
                    </tr>
                    <tr>
                        <td>Nama Sekolah</strong></td>
                        <td>: {{ $schoolName }}</strong></td>
                    </tr>
                    <tr>
                        <td>Kompetisi</strong></td>
                        <td>: {{ $team->competition ?? 'HSBL' }}</td>
                    </tr>
                    <tr>
                        <td>Musim</strong></td>
                        <td>: {{ $team->season ?? date('Y') }}</td>
                    </tr>
                    <tr>
                        <td>Seri</strong></td>
                        <td>: {{ $team->series ?? '1' }}</td>
                    </tr>
                    <tr>
                        <td>Status Terkunci</strong></td>
                        <td>: {{ $team->locked_status == 'locked' ? '🔒 Terkunci' : '🔓 Terbuka' }}</td>
                    </tr>
                    <tr>
                        <td>Status Verifikasi</strong></td>
                        <td>: {{ $team->verification_status == 'verified' ? '✅ Terverifikasi' : '⏳ Belum Diverifikasi' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Status and Documents - Lebar lebih kecil -->
            <div class="status-doc-section">
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

                <!-- Action Buttons -->
                <div class="action-buttons mt-3">
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

<style>
    /* Layout grid - info section lebih lebar, doc section lebih kecil */
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
    
    /* Style untuk logo */
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
    
    /* Style info table */
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
    
    /* Style dokumen - lebih ringkas */
    .documents-section h4, .action-buttons h4 {
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
    
    /* Action buttons */
    .action-buttons-row.compact {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    .btn-action-simple {
        color: #fff;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        transition: all 0.2s ease;
    }
    
    .btn-lock { background: linear-gradient(135deg, #059669 0%, #047857 100%); }
    .btn-unlock { background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%); }
    .btn-verify { background: linear-gradient(135deg, #10b981 0%, #059669 100%); }
    .btn-unverify { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); }
    
    .btn-action-simple:hover {
        transform: translateY(-1px);
        filter: brightness(1.05);
    }
    
    /* Responsive */
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
        .btn-action-simple {
            padding: 6px 10px;
            font-size: 10px;
        }
    }
</style>