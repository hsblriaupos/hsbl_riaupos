{{-- resources/views/team_verification/partials/jersey_gallery.blade.php --}}
@props(['team', 'category'])

@php
    $isDancer = $category == 'Dancer';
    $homeLabel = $isDancer ? 'Kostum Utama' : 'Jersey Kandang';
    $awayLabel = $isDancer ? 'Kostum Alternatif' : 'Jersey Tandang';
    $altLabel = $isDancer ? 'Kostum Khusus' : 'Jersey Alternatif';
    
    // Fungsi untuk mendapatkan URL jersey dengan fallback
    function getJerseyUrl($path) {
        if (empty($path)) return null;
        
        if (file_exists(public_path('storage/' . $path))) {
            return asset('storage/' . $path) . '?v=' . time();
        }
        
        if (file_exists(storage_path('app/public/' . $path))) {
            return asset('storage/' . $path) . '?v=' . time();
        }
        
        if (file_exists(public_path($path))) {
            return asset($path) . '?v=' . time();
        }
        
        return null;
    }
    
    $homeUrl = getJerseyUrl($team->jersey_home);
    $awayUrl = getJerseyUrl($team->jersey_away);
    $altUrl = getJerseyUrl($team->jersey_alternate);
@endphp

<div class="card">
    <div class="card-header">
        <i class="fas fa-tshirt"></i>
        <span>Daftar {{ $isDancer ? 'Kostum' : 'Jersey' }} {{ $category }}</span>
    </div>
    <div class="card-body">
        <div class="jersey-single-container">
            <h3 class="jersey-main-title">Galeri {{ $isDancer ? 'Kostum' : 'Jersey' }} Tim {{ $category }}</h3>
            <div class="jersey-image-container">
                <!-- Home/Kandang -->
                <div class="jersey-single-item" onclick="showJerseyPopup('{{ $homeUrl }}', '{{ $homeLabel }}', '{{ $team->school_name ?? $team->team_name ?? '' }}', '{{ $category }}')">
                    <p>{{ $homeLabel }}</p>
                    @if($homeUrl)
                        <img src="{{ $homeUrl }}" 
                             alt="{{ $homeLabel }}"
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

                <!-- Away/Tandang -->
                <div class="jersey-single-item" onclick="showJerseyPopup('{{ $awayUrl }}', '{{ $awayLabel }}', '{{ $team->school_name ?? $team->team_name ?? '' }}', '{{ $category }}')">
                    <p>{{ $awayLabel }}</p>
                    @if($awayUrl)
                        <img src="{{ $awayUrl }}" 
                             alt="{{ $awayLabel }}"
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

                <!-- Alternate/Alternatif -->
                <div class="jersey-single-item" onclick="showJerseyPopup('{{ $altUrl }}', '{{ $altLabel }}', '{{ $team->school_name ?? $team->team_name ?? '' }}', '{{ $category }}')">
                    <p>{{ $altLabel }}</p>
                    @if($altUrl)
                        <img src="{{ $altUrl }}" 
                             alt="{{ $altLabel }}"
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