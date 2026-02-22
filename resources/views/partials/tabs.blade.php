@php
    $tabs = [
        'data'   => ['label' => 'Data',   'route' => 'admin.all_data',   'icon' => 'fas fa-database'],
        'city'   => ['label' => 'City',   'route' => 'admin.all_data_city', 'icon' => 'fas fa-city'],
        'school' => ['label' => 'School', 'route' => 'admin.all_data_school', 'icon' => 'fas fa-school'],
        'venue'  => ['label' => 'Venue',  'route' => 'admin.all_data_venue', 'icon' => 'fas fa-map-marker-alt'],
        'award'  => ['label' => 'Award',  'route' => 'admin.all_data_award', 'icon' => 'fas fa-trophy'],
    ];
@endphp

<div class="admin-tabs-wrapper">
    <div class="admin-tabs-container">
        <nav class="admin-tabs-nav">
            @foreach($tabs as $key => $tab)
                @php $isActive = ($activeTab === $key); @endphp
                
                <a href="{{ route($tab['route']) }}" 
                   class="admin-tabs-item {{ $isActive ? 'active' : '' }}">
                    <i class="{{ $tab['icon'] }} tab-icon"></i>
                    <span class="tab-label">{{ $tab['label'] }}</span>
                </a>
            @endforeach
        </nav>
    </div>
</div>