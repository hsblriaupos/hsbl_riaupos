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

<div class="category-tabs-container">
    <nav class="category-tabs-nav">
        @foreach($tabs as $key => $tab)
            @php 
                $teamInfo = $teamData[$key] ?? null;
                $teamExists = $teamInfo && $teamInfo['exists'];
                
                // Logika penentuan active tab
                $isActive = ($activeTab === $key);
                
                if($teamExists) {
                    $playersCount = $teamInfo['players']->count();
                    $officialsCount = $teamInfo['officials']->count();
                    $totalCount = $playersCount + $officialsCount;
                    
                    $verified = isset($teamInfo['team']) && $teamInfo['team']->verification_status == 'verified';
                    $locked = isset($teamInfo['team']) && $teamInfo['team']->locked_status == 'locked';
                }
                
                // Tentukan route - SELALU menggunakan mainTeam ID
                $tabRoute = route($tab['route'], $mainTeam->team_id);
            @endphp
            
            <a href="{{ $tabRoute }}" 
               data-category="{{ $key }}"
               class="category-tabs-item {{ $isActive ? 'active' : '' }}"
               style="{{ $isActive ? 'border-bottom-color: ' . $tab['color'] . '; color: ' . $tab['color'] . ';' : '' }}"
               title="{{ $teamExists ? 'Lihat detail ' . $key : 'Tim ' . $key . ' (belum terdaftar)' }}">
                <i class="{{ $tab['icon'] }} tab-icon" style="{{ $isActive ? 'color: ' . $tab['color'] . ';' : '' }}"></i>
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
                        <span class="badge-count" title="{{ $playersCount }} pemain + {{ $officialsCount }} official">
                            {{ $totalCount }}
                        </span>
                    @endif
                @else
                    <span class="status-badge unavailable" title="Belum terdaftar">
                        <i class="fas fa-exclamation-circle"></i>
                    </span>
                @endif
            </a>
        @endforeach
    </nav>
</div>

<style>
    .category-tabs-container {
        background: white;
        border-bottom: 1px solid #e5e7eb;
        padding: 0;
        margin-bottom: 20px;
        border-radius: 12px 12px 0 0;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .category-tabs-nav {
        display: flex;
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding: 0;
    }

    .category-tabs-nav::-webkit-scrollbar {
        display: none;
    }

    .category-tabs-item {
        flex: 1;
        min-width: 130px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 18px 12px;
        text-decoration: none;
        transition: all 0.3s ease;
        border-right: 1px solid #f3f4f6;
        color: #6b7280;
        position: relative;
        cursor: pointer;
        border-bottom: 4px solid transparent;
        background: white;
        border-radius: 0;
        margin: 0;
        transition: all 0.3s ease;
    }

    .category-tabs-item:last-child {
        border-right: none;
    }

    .category-tabs-item:hover {
        background-color: #f9fafb;
        color: #374151;
    }

    .category-tabs-item.active {
        background: linear-gradient(to bottom, #ffffff, #f8fafc);
        font-weight: 700;
        border-bottom: 4px solid;
        position: relative;
    }

    .category-tabs-item .tab-icon {
        margin-bottom: 10px;
        color: #9ca3af;
        font-size: 22px;
        transition: all 0.3s ease;
    }

    .category-tabs-item.active .tab-icon {
        transform: scale(1.1);
    }

    .category-tabs-item:hover .tab-icon {
        color: inherit;
        transform: scale(1.05);
    }

    .category-tabs-item .tab-label {
        font-size: 14px;
        font-weight: 600;
        letter-spacing: 0.025em;
        margin-bottom: 8px;
        text-align: center;
        line-height: 1.3;
    }

    .tab-status {
        display: flex;
        gap: 4px;
        margin-bottom: 5px;
    }

    .status-badge {
        font-size: 11px;
        padding: 3px 5px;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 18px;
        height: 18px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }

    .status-badge.verified {
        background: linear-gradient(135deg, #d1fae5, #a7f3d0);
        color: #065f46;
        border: 1px solid #10b981;
    }

    .status-badge.locked {
        background: linear-gradient(135deg, #fef3c7, #fde68a);
        color: #92400e;
        border: 1px solid #f59e0b;
    }

    .status-badge.unavailable {
        background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
        color: #6b7280;
        font-size: 10px;
        border: 1px solid #d1d5db;
    }

    .badge-count {
        position: absolute;
        top: 10px;
        right: 10px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: white;
        font-size: 11px;
        font-weight: 700;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 4px rgba(102, 126, 234, 0.3);
        border: 2px solid white;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .category-tabs-item {
            padding: 15px 8px;
            min-width: 110px;
        }
        
        .category-tabs-item .tab-icon {
            font-size: 18px;
            margin-bottom: 8px;
        }
        
        .category-tabs-item .tab-label {
            font-size: 12px;
        }
        
        .badge-count {
            top: 8px;
            right: 8px;
            width: 18px;
            height: 18px;
            font-size: 10px;
        }
        
        .status-badge {
            width: 16px;
            height: 16px;
            font-size: 10px;
        }
    }

    @media (max-width: 480px) {
        .category-tabs-item {
            min-width: 100px;
            padding: 12px 6px;
        }
        
        .category-tabs-item .tab-icon {
            font-size: 16px;
        }
        
        .category-tabs-item .tab-label {
            font-size: 11px;
        }
    }
</style>