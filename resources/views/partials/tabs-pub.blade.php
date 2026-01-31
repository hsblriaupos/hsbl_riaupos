@php
    $tabs = [
        'schedule' => ['label' => 'Schedule', 'route' => 'admin.pub_schedule.index', 'icon' => 'fas fa-calendar-alt'],
        'result'   => ['label' => 'Results',  'route' => 'admin.pub_result.index',   'icon' => 'fas fa-chart-line'],
    ];
@endphp

<div class="admin-tabs-container">
    <nav class="admin-tabs-nav">
        @foreach($tabs as $key => $tab)
            @php $isActive = ($activeTab === $key); @endphp
            
            <a href="{{ route($tab['route']) }}" 
               class="admin-tabs-item {{ $isActive ? 'active' : '' }}"
               style="flex: 1; min-width: 0; max-width: none;">
                <i class="{{ $tab['icon'] }} tab-icon"></i>
                <span class="tab-label">{{ $tab['label'] }}</span>
            </a>
        @endforeach
    </nav>
</div>

<style>
    .admin-tabs-container {
        background: white;
        border-bottom: 1px solid #e5e7eb;
        padding: 0;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        position: relative;
        z-index: 10;
        margin: 0;
        width: 100%;
    }

    .admin-tabs-nav {
        display: flex;
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        justify-content: stretch; /* Tambahkan ini */
    }

    .admin-tabs-nav::-webkit-scrollbar {
        display: none;
    }

    .admin-tabs-item {
        flex: 1; /* Ini akan membuat setiap item mengambil ruang yang sama */
        min-width: 0; /* Override min-width yang mungkin ada */
        max-width: none; /* Override max-width yang mungkin ada */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 12px 8px;
        text-decoration: none;
        transition: all 0.2s ease;
        border-right: 1px solid #f3f4f6;
        color: #6b7280;
        position: relative;
        white-space: nowrap;
    }

    .admin-tabs-item:last-child {
        border-right: none;
    }

    .admin-tabs-item:hover {
        background-color: #f9fafb;
        color: #374151;
    }

    .admin-tabs-item.active {
        background: linear-gradient(to bottom, #eff6ff, white);
        color: #1d4ed8;
        font-weight: 600;
        border-bottom: 2px solid #3b82f6;
    }

    .admin-tabs-item.active .tab-icon {
        color: #3b82f6;
    }

    .admin-tabs-item .tab-icon {
        margin-bottom: 6px;
        color: #9ca3af;
        font-size: 14px;
        transition: color 0.2s ease;
    }

    .admin-tabs-item:hover .tab-icon {
        color: #6b7280;
    }

    .admin-tabs-item .tab-label {
        font-size: 12px;
        font-weight: 500;
        letter-spacing: 0.025em;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .admin-tabs-item {
            padding: 10px 6px;
        }
        
        .admin-tabs-item .tab-icon {
            font-size: 12px;
            margin-bottom: 4px;
        }
        
        .admin-tabs-item .tab-label {
            font-size: 11px;
        }
    }
    
    @media (max-width: 576px) {
        .admin-tabs-item {
            padding: 8px 4px;
        }
        
        .admin-tabs-item .tab-icon {
            font-size: 11px;
        }
        
        .admin-tabs-item .tab-label {
            font-size: 10px;
        }
    }
</style>