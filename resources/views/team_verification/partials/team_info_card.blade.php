@props(['team', 'category', 'stats' => []])

@php
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
    <!-- Logo Column - DIPERBESAR -->
    <div class="logo-column">
        @if($logoUrl)
            <img src="{{ $logoUrl }}" 
                 alt="Logo Sekolah {{ $schoolName }}"
                 class="team-logo-img "
                 onclick="showLogoPopup('{{ $logoUrl }}', '{{ $schoolName }}', '{{ $category }}')"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="logo-placeholder" style="display: none;">
                <i class="fas fa-school"></i>
                <span>Logo</span>
            </div>
        @else
            <div class="logo-placeholder">
                <i class="fas fa-school"></i>
                <span>Logo</span>
            </div>
        @endif
    </div>

    <!-- Content Column -->
    <div class="content-column">
        <!-- Info Grid 2 Kolom - PROPORSIONAL -->
        <div class="info-grid">
            <div class="info-col">
                <div class="info-item">
                    <span class="info-label">Tim ID</span>
                    <span class="info-value code">{{ $team->referral_code ?? 'N/A' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Leader</span>
                    <span class="info-value">{{ $leaderName }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">School</span>
                    <span class="info-value">{{ $schoolName }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Competition</span>
                    <span class="info-value">{{ $team->competition ?? 'HSBL' }}</span>
                </div>
            </div>
            <div class="info-col">
                <div class="info-item">
                    <span class="info-label">Season</span>
                    <span class="info-value">{{ $team->season ?? date('Y') }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Series</span>
                    <span class="info-value">{{ $team->series ?? '1' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Lock Status</span>
                    <span class="info-value">
                        @if($team->locked_status == 'locked')
                            <span class="status-locked">🔒 Locked</span>
                        @else
                            <span class="status-open">🔓 Unlocked</span>
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Verify Status</span>
                    <span class="info-value">
                        @if($team->verification_status == 'verified')
                            <span class="status-verified">✓ Verified</span>
                        @else
                            <span class="status-unverified">○ Unverified</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <!-- Dokumen Section - DIPERSEMPIT -->
        <div class="docs-section">
            <div class="docs-title">
                <i class="fas fa-file-alt"></i> Document
            </div>
            <div class="docs-list">
                @php
                    $recUrl = null;
                    if (!empty($team->recommendation_letter)) {
                        if (file_exists(public_path('storage/' . $team->recommendation_letter))) {
                            $recUrl = asset('storage/' . $team->recommendation_letter) . '?v=' . time();
                        } elseif (Storage::disk('public')->exists($team->recommendation_letter)) {
                            $recUrl = Storage::url($team->recommendation_letter) . '?v=' . time();
                        }
                    }
                    
                    $koranUrl = null;
                    if (!empty($team->koran)) {
                        if (file_exists(public_path('storage/' . $team->koran))) {
                            $koranUrl = asset('storage/' . $team->koran) . '?v=' . time();
                        } elseif (Storage::disk('public')->exists($team->koran)) {
                            $koranUrl = Storage::url($team->koran) . '?v=' . time();
                        }
                    }
                @endphp
                
                <a href="{{ $recUrl ?: '#' }}" 
                   target="{{ $recUrl ? '_blank' : '_self' }}"
                   class="doc-link {{ $recUrl ? 'doc-available' : 'doc-missing' }}"
                   onclick="{{ !$recUrl ? 'showAlert(\'Surat Rekomendasi\'); return false;' : '' }}">
                    <i class="fas fa-file-pdf"></i>
                    <span>Surat Rekomendasi</span>
                </a>
                
                <a href="{{ $koranUrl ?: '#' }}" 
                   target="{{ $koranUrl ? '_blank' : '_self' }}"
                   class="doc-link {{ $koranUrl ? 'doc-available' : 'doc-missing' }}"
                   onclick="{{ !$koranUrl ? 'showAlert(\'Bukti Koran\'); return false;' : '' }}">
                    <i class="fas fa-newspaper"></i>
                    <span>Bukti Koran</span>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
/* ===== MAIN LAYOUT ===== */
.team-info {
    display: flex;
    gap: 28px;
    align-items: flex-start;
}

/* ===== LOGO COLUMN - DIPERBESAR ===== */
.logo-column {
    flex: 0 0 140px;
}

.team-logo-img {
    width: 140px;
    height: 140px;
    object-fit: contain;
    cursor: pointer;
    transition: opacity 0.2s ease;
    background: transparent;
    display: block;
}

.team-logo-img:hover {
    opacity: 0.85;
}

.logo-placeholder {
    width: 140px;
    height: 140px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #f8fafc;
    border-radius: 12px;
    color: #94a3b8;
    font-size: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.logo-placeholder:hover {
    background: #f1f5f9;
}

.logo-placeholder i {
    font-size: 48px;
    margin-bottom: 8px;
    color: #cbd5e1;
}

/* ===== CONTENT COLUMN ===== */
.content-column {
    flex: 1;
    min-width: 0;
    max-width: 580px;
}

/* ===== INFO GRID 2 KOLOM - PROPORSIONAL ===== */
.info-grid {
    display: flex;
    gap: 32px;
    margin-bottom: 20px;
}

.info-col {
    flex: 1;
    min-width: 0;
}

.info-item {
    display: flex;
    align-items: baseline;
    padding: 8px 0;
    font-size: 13px;
    border-bottom: 1px solid #f0f2f5;
}

.info-label {
    width: 80px;
    flex-shrink: 0;
    color: #64748b;
    font-weight: 500;
    font-size: 12px;
}

.info-value {
    flex: 1;
    color: #1e293b;
    font-weight: 400;
    word-break: break-word;
}

.info-value.code {
    font-family: 'SF Mono', 'Monaco', monospace;
    font-size: 12px;
    font-weight: 500;
    color: #2563eb;
    letter-spacing: 0.3px;
}

/* Status - Tanpa Background */
.status-locked {
    color: #dc2626;
}

.status-open {
    color: #10b981;
}

.status-verified {
    color: #10b981;
}

.status-unverified {
    color: #f59e0b;
}

/* ===== DOKUMEN SECTION - DIPERSEMPIT ===== */
.docs-section {
    margin-top: 8px;
    padding-top: 12px;
    border-top: 1px solid #f0f2f5;
}

.docs-title {
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.docs-title i {
    font-size: 11px;
    color: #94a3b8;
}

.docs-list {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.doc-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 4px 0;
    font-size: 12px;
    font-weight: 450;
    text-decoration: none;
    border-bottom: 1px solid transparent;
    transition: all 0.2s ease;
}

.doc-link i {
    font-size: 13px;
}

.doc-available {
    color: #3b82f6;
    border-bottom-color: #e2e8f0;
}

.doc-available:hover {
    border-bottom-color: #3b82f6;
    opacity: 0.8;
}

.doc-missing {
    color: #94a3b8;
    cursor: not-allowed;
    opacity: 0.6;
}

/* ===== RESPONSIVE ===== */
@media (max-width: 700px) {
    .team-info {
        flex-direction: column;
        align-items: center;
        gap: 20px;
    }
    
    .logo-column {
        flex: 0 0 auto;
    }
    
    .content-column {
        max-width: 100%;
    }
    
    .info-grid {
        flex-direction: column;
        gap: 0;
    }
    
    .info-item {
        justify-content: space-between;
    }
    
    .docs-list {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .logo-column {
        flex: 0 0 100px;
    }
    
    .team-logo-img, .logo-placeholder {
        width: 100px;
        height: 100px;
    }
    
    .logo-placeholder i {
        font-size: 36px;
    }
    
    .info-label {
        width: 70px;
        font-size: 11px;
    }
    
    .info-value {
        font-size: 11px;
    }
}
</style>