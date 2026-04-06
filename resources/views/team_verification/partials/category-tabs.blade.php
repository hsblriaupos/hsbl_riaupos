@php
    $tabs = [
        'Basket Putra' => [
            'label' => 'Basket Putra', 
            'icon' => 'fas fa-basketball-ball',
            'color' => '#3b82f6',
            'route' => 'admin.team.detail.basket-putra',
            'exists' => isset($teamData['Basket Putra']) && $teamData['Basket Putra']['exists']
        ],
        'Basket Putri' => [
            'label' => 'Basket Putri', 
            'icon' => 'fas fa-basketball-ball',
            'color' => '#ec4899',
            'route' => 'admin.team.detail.basket-putri',
            'exists' => isset($teamData['Basket Putri']) && $teamData['Basket Putri']['exists']
        ],
        'Dancer' => [
            'label' => 'Dancer', 
            'icon' => 'fas fa-music',
            'color' => '#8b5cf6',
            'route' => 'admin.team.detail.dancer',
            'exists' => isset($teamData['Dancer']) && $teamData['Dancer']['exists']
        ],
    ];
@endphp

<div class="category-tabs-wrapper">
    <div class="category-tabs-container">
        <nav class="category-tabs-nav">
            @foreach($tabs as $key => $tab)
                @php 
                    $teamInfo = $teamData[$key] ?? null;
                    $teamExists = $teamInfo && $teamInfo['exists'];
                    $isActive = ($activeTab === $key);
                    
                    if($teamExists) {
                        $playersCount = $teamInfo['players']->count();
                        $officialsCount = $teamInfo['officials']->count();
                        $totalCount = $playersCount + $officialsCount;
                        $verified = isset($teamInfo['team']) && $teamInfo['team']->verification_status == 'verified';
                        $locked = isset($teamInfo['team']) && $teamInfo['team']->locked_status == 'locked';
                    }
                    
                    $tabRoute = route($tab['route'], $mainTeam->team_id);
                @endphp
                
                <a href="{{ $tabRoute }}" 
                   data-category="{{ $key }}"
                   class="category-tabs-item {{ $isActive ? 'active' : '' }}"
                   style="{{ $isActive ? 'border-bottom-color: ' . $tab['color'] . '; color: ' . $tab['color'] . ';' : '' }}">
                    <i class="{{ $tab['icon'] }} tab-icon"></i>
                    <span class="tab-label">{{ $tab['label'] }}</span>
                    
                    @if($teamExists)
                        <div class="tab-status">
                            @if($verified)
                                <span class="status-badge verified" title="Terverifikasi">
                                    <i class="fas fa-check-circle"></i>
                                </span>
                            @endif
                            @if($locked)
                                <span class="status-badge locked" title="Terkunci">
                                    <i class="fas fa-lock"></i>
                                </span>
                            @endif
                        </div>
                        @if($totalCount > 0)
                            <span class="badge-count">{{ $totalCount }}</span>
                        @endif
                    @else
                        {{-- HAPUS TANDA SERU - KOSONGKAN SAJA --}}
                        <span class="status-badge unavailable" style="display: none;"></span>
                    @endif
                </a>
            @endforeach
        </nav>
    </div>
</div>

<style>
    .category-tabs-wrapper {
        margin-bottom: 20px;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .category-tabs-wrapper::-webkit-scrollbar {
        height: 4px;
    }
    
    .category-tabs-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    .category-tabs-wrapper::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .category-tabs-container {
        background: white;
        border-radius: 12px;
        min-width: min-content;
    }

    .category-tabs-nav {
        display: flex;
        gap: 0;
        padding: 0;
        margin: 0;
    }

    .category-tabs-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 14px 24px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
        border-bottom: 3px solid transparent;
        background: white;
        transition: all 0.2s ease;
        white-space: nowrap;
        flex-shrink: 0;
    }
    
    .category-tabs-item:hover {
        color: #3b82f6;
        background: #f8fafc;
        border-bottom-color: #93c5fd;
    }
    
    .category-tabs-item.active {
        color: #3b82f6;
        border-bottom-color: #3b82f6;
        background: #eff6ff;
    }
    
    .category-tabs-item .tab-icon {
        font-size: 16px;
    }
    
    .category-tabs-item .tab-label {
        font-weight: 600;
    }
    
    .tab-status {
        display: inline-flex;
        gap: 4px;
        margin-left: 4px;
    }
    
    .status-badge {
        font-size: 11px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .status-badge.verified {
        color: #059669;
    }
    
    .status-badge.locked {
        color: #d97706;
    }
    
    .status-badge.unavailable {
        color: #9ca3af;
    }
    
    .badge-count {
        background: #e2e8f0;
        color: #475569;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
        margin-left: 6px;
    }
    
    .category-tabs-item.active .badge-count {
        background: #3b82f6;
        color: white;
    }
    
    @media (max-width: 640px) {
        .category-tabs-item {
            padding: 10px 16px;
            font-size: 12px;
        }
        .category-tabs-item .tab-icon {
            font-size: 14px;
        }
        .badge-count {
            font-size: 9px;
            padding: 1px 6px;
        }
    }
</style>