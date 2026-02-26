@extends('user.layouts.app')

@section('title', 'Photo Gallery - SBL Riau Pos')

@section('content')
<div class="min-h-screen">
    <!-- Header Section -->
    <div class="mb-6 md:mb-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Photo Gallery</h1>
                <p class="text-sm text-gray-600">Explore basketball moments from SBL competitions</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-xs text-gray-500">Total:</span>
                <span class="px-2.5 py-1 bg-blue-100 text-blue-600 text-xs font-medium rounded-full">{{ $galleries->total() }} Collections</span>
            </div>
        </div>
    </div>

    <!-- Compact Search and Filter Section -->
    <div class="mb-8">
        <div class="bg-white rounded-lg shadow-sm p-3">
            <div class="flex flex-wrap items-center gap-2">
                <!-- Search Input -->
                <div class="relative flex-1 min-w-[200px]">
                    <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                        <i class="fas fa-search text-gray-400 text-xs"></i>
                    </div>
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Search school, competition..." 
                           class="block w-full pl-8 pr-8 py-2 text-sm border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                           value="{{ request()->get('search') }}">
                    @if(request()->get('search'))
                    <button onclick="resetSearch()" 
                            class="absolute inset-y-0 right-0 pr-2.5 flex items-center text-gray-400 hover:text-red-500">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                    @endif
                </div>
                
                <!-- Competition Filter -->
                <div class="relative w-36">
                    <select id="competitionFilter" 
                            class="appearance-none w-full px-3 py-2 pr-8 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                        <option value="">All Competitions</option>
                        @foreach($competitions ?? [] as $competition)
                            <option value="{{ $competition }}" {{ request()->get('competition') == $competition ? 'selected' : '' }}>{{ $competition }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                    </div>
                </div>
                
                <!-- Season Filter -->
                <div class="relative w-28">
                    <select id="seasonFilter" 
                            class="appearance-none w-full px-3 py-2 pr-8 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                        <option value="">All Seasons</option>
                        @foreach($seasons ?? [] as $season)
                            <option value="{{ $season }}" {{ request()->get('season') == $season ? 'selected' : '' }}>{{ $season }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                    </div>
                </div>
                
                <!-- Series Filter -->
                <div class="relative w-28">
                    <select id="seriesFilter" 
                            class="appearance-none w-full px-3 py-2 pr-8 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                        <option value="">All Series</option>
                        @foreach($series ?? [] as $seriesItem)
                            <option value="{{ $seriesItem }}" {{ request()->get('series') == $seriesItem ? 'selected' : '' }}>{{ $seriesItem }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                    </div>
                </div>
                
                <!-- Sort Dropdown -->
                <div class="relative w-32">
                    <select id="sortSelect" 
                            class="appearance-none w-full px-3 py-2 pr-8 text-sm border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                        <option value="newest" {{ request()->get('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                        <option value="oldest" {{ request()->get('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="downloads" {{ request()->get('sort') == 'downloads' ? 'selected' : '' }}>Most Downloaded</option>
                        <option value="name" {{ request()->get('sort') == 'name' ? 'selected' : '' }}>A-Z</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                    </div>
                </div>
                
                <!-- Apply Button -->
                <button onclick="applyFilters()"
                        class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors duration-200 flex items-center">
                    <i class="fas fa-check mr-1 text-xs"></i>
                    Apply
                </button>
                
                <!-- Reset Button -->
                <button id="resetButton" 
                        onclick="resetAllFilters()"
                        class="px-3 py-2 border border-red-200 text-red-600 text-sm rounded-lg bg-red-50 hover:bg-red-100 transition-colors duration-200 flex items-center {{ $hasActiveFilters ? '' : 'hidden' }}">
                    <i class="fas fa-redo text-xs mr-1"></i>
                    Reset
                </button>
                
                <!-- Active Filter Count Badge -->
                @if($hasActiveFilters)
                <span id="filterCount" class="px-2 py-1 bg-blue-500 text-white text-xs rounded-full">
                    {{ $activeFilterCount }} active
                </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="mb-10">
        @if($galleries->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
                @foreach($galleries as $gallery)
                    @php
                        // Format file size
                        $fileSize = '';
                        if ($gallery->file_size) {
                            if ($gallery->file_size < 1024 * 1024) {
                                $fileSize = round($gallery->file_size / 1024, 1) . ' KB';
                            } else {
                                $fileSize = round($gallery->file_size / (1024 * 1024), 1) . ' MB';
                            }
                        }
                        
                        // Get file extension
                        $fileExt = $gallery->file_type ? strtoupper(pathinfo($gallery->original_filename, PATHINFO_EXTENSION)) : 'ZIP';
                        
                        // Generate download URL
                        $downloadUrl = route('user.gallery.photos.download', ['id' => $gallery->id]);
                    @endphp
                    
                    <div class="gallery-card group relative bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden cursor-pointer"
                         onclick="showGalleryDetails({{ $gallery->id }})"
                         data-gallery-id="{{ $gallery->id }}"
                         data-school="{{ strtolower($gallery->school_name) }}"
                         data-competition="{{ strtolower($gallery->competition) }}"
                         data-season="{{ strtolower($gallery->season) }}"
                         data-series="{{ strtolower($gallery->series) }}"
                         data-download-url="{{ $downloadUrl }}"
                         data-has-photo="{{ $gallery->hasPhoto() ? 'true' : 'false' }}">
                        
                        <!-- Cover Image -->
                        <div class="relative h-40 overflow-hidden bg-gray-100">
                            @if($gallery->hasPhoto() && $gallery->photo_url)
                                <img src="{{ $gallery->photo_url }}" 
                                     alt="{{ $gallery->school_name }} Gallery Cover" 
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                     onerror="this.onerror=null; this.classList.add('hidden'); this.parentElement.querySelector('.fallback-placeholder').classList.remove('hidden');">
                                <div class="fallback-placeholder hidden w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100">
                                    <div class="text-center">
                                        <i class="fas fa-image text-blue-300 text-4xl mb-1"></i>
                                        <p class="text-xs text-gray-400">Cover unavailable</p>
                                    </div>
                                </div>
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100">
                                    <div class="text-center">
                                        <i class="fas fa-images text-blue-300 text-4xl mb-1"></i>
                                        <p class="text-xs text-gray-400">No cover</p>
                                    </div>
                                </div>
                            @endif
                            
                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Download Count Badge -->
                            <div class="absolute top-2 right-2 bg-black/70 text-white px-1.5 py-0.5 rounded-full text-xs">
                                <i class="fas fa-download mr-0.5 text-xs"></i>
                                {{ $gallery->download_count }}
                            </div>
                            
                            <!-- ZIP File Badge -->
                            <div class="absolute bottom-2 left-2">
                                <span class="px-1.5 py-0.5 bg-blue-500 text-white text-xs font-semibold rounded-full">
                                    <i class="fas fa-file-archive mr-0.5 text-xs"></i>{{ $fileExt }}
                                </span>
                            </div>

                            <!-- Has Cover Indicator -->
                            @if($gallery->hasPhoto())
                                <div class="absolute top-2 left-2">
                                    <span class="px-1.5 py-0.5 bg-green-500 text-white text-xs font-semibold rounded-full">
                                        <i class="fas fa-camera mr-0.5 text-xs"></i>Cover
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Card Content -->
                        <div class="p-3">
                            <!-- School Name -->
                            <h3 class="text-sm font-bold text-gray-800 mb-1 line-clamp-1" title="{{ $gallery->school_name }}">
                                {{ $gallery->school_name }}
                            </h3>
                            
                            <!-- Competition -->
                            <div class="flex items-center mb-2">
                                <i class="fas fa-trophy text-yellow-500 mr-1 text-xs"></i>
                                <span class="text-xs text-gray-700">{{ $gallery->competition }}</span>
                            </div>
                            
                            <!-- File Info -->
                            <div class="flex items-center justify-between text-xs text-gray-500">
                                <div class="flex items-center">
                                    <i class="far fa-file mr-1"></i>
                                    <span>{{ $fileExt }}</span>
                                </div>
                                @if($fileSize)
                                    <div class="flex items-center">
                                        <i class="fas fa-weight-hanging mr-1"></i>
                                        <span>{{ $fileSize }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="mt-2 pt-2 border-t border-gray-100 flex justify-between items-center">
                                <button onclick="event.stopPropagation(); downloadGallery({{ $gallery->id }})"
                                        class="inline-flex items-center px-2 py-1 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-600 transition-colors">
                                    <i class="fas fa-download mr-1 text-xs"></i>
                                    Download
                                </button>
                                
                                <span class="text-xs text-gray-400">
                                    {{ $gallery->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            @if($galleries->hasPages())
                <div class="mt-6">
                    {{ $galleries->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-100 mb-3">
                    <i class="fas fa-images text-blue-500 text-xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-700 mb-1">No Photo Galleries Available</h3>
                <p class="text-sm text-gray-500 max-w-md mx-auto mb-4">Photo galleries from competitions will appear here once they are uploaded.</p>
                @if($hasActiveFilters)
                <button onclick="resetAllFilters()"
                        class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white text-sm rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-redo mr-1.5 text-xs"></i>
                    Reset All Filters
                </button>
                @endif
            </div>
        @endif
    </div>

    <!-- Statistics Section -->
    <div class="bg-white rounded-lg shadow-sm p-4 mb-8">
        <h2 class="text-sm font-bold text-gray-800 mb-3">Gallery Statistics</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
            <div class="bg-blue-50 rounded p-3">
                <div class="flex items-center">
                    <div class="mr-2">
                        <i class="fas fa-images text-blue-500 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Collections</p>
                        <p class="text-lg font-bold text-gray-800">{{ $galleries->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 rounded p-3">
                <div class="flex items-center">
                    <div class="mr-2">
                        <i class="fas fa-download text-green-500 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Downloads</p>
                        <p class="text-lg font-bold text-gray-800">{{ $totalDownloads ?? $galleries->sum('download_count') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-purple-50 rounded p-3">
                <div class="flex items-center">
                    <div class="mr-2">
                        <i class="fas fa-university text-purple-500 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Schools</p>
                        <p class="text-lg font-bold text-gray-800">{{ $galleries->unique('school_name')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-orange-50 rounded p-3">
                <div class="flex items-center">
                    <div class="mr-2">
                        <i class="fas fa-trophy text-orange-500 text-sm"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600">Competitions</p>
                        <p class="text-lg font-bold text-gray-800">{{ $galleries->unique('competition')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gallery Details Modal -->
<div id="galleryModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <!-- Cover Image Column -->
                    <div class="sm:w-2/5 mb-4 sm:mb-0 sm:mr-4">
                        <div class="rounded-lg overflow-hidden bg-gray-100 h-48 sm:h-auto flex items-center justify-center relative" id="modalCoverContainer">
                            <img id="modalCoverImage" 
                                 src="" 
                                 alt="Gallery Cover" 
                                 class="w-full h-full object-cover hidden"
                                 onerror="this.onerror=null; this.classList.add('hidden'); document.getElementById('modalCoverPlaceholder').classList.remove('hidden');">
                            <div id="modalCoverPlaceholder" class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-images text-gray-300 text-5xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Details Column -->
                    <div class="sm:w-3/5">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modalSchoolName"></h3>
                            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        
                        <div class="space-y-2">
                            <!-- Competition -->
                            <div class="flex items-start">
                                <div class="w-24 text-xs text-gray-500">Competition:</div>
                                <div class="flex-1 text-sm font-medium" id="modalCompetition"></div>
                            </div>
                            
                            <!-- Season/Series -->
                            <div class="flex items-start">
                                <div class="w-24 text-xs text-gray-500">Season/Series:</div>
                                <div class="flex-1 text-sm" id="modalSeasonSeries"></div>
                            </div>
                            
                            <!-- File Name -->
                            <div class="flex items-start">
                                <div class="w-24 text-xs text-gray-500">File Name:</div>
                                <div class="flex-1 text-sm break-all" id="modalFilename"></div>
                            </div>
                            
                            <!-- File Info Row -->
                            <div class="flex items-start">
                                <div class="w-24 text-xs text-gray-500">File Info:</div>
                                <div class="flex-1">
                                    <span class="text-sm" id="modalFileSize"></span>
                                    <span class="text-xs text-gray-400 mx-1">•</span>
                                    <span class="text-sm" id="modalFileType"></span>
                                </div>
                            </div>
                            
                            <!-- Downloads -->
                            <div class="flex items-start">
                                <div class="w-24 text-xs text-gray-500">Downloads:</div>
                                <div class="flex-1">
                                    <span class="text-sm font-semibold text-blue-600" id="modalDownloads">0</span>
                                    <span class="text-xs text-gray-500"> times</span>
                                </div>
                            </div>
                            
                            <!-- Status -->
                            <div class="flex items-start">
                                <div class="w-24 text-xs text-gray-500">Status:</div>
                                <div class="flex-1">
                                    <span id="modalStatus" class="px-2 py-1 rounded-full text-xs font-medium"></span>
                                </div>
                            </div>
                            
                            <!-- Upload Date -->
                            <div class="flex items-start">
                                <div class="w-24 text-xs text-gray-500">Uploaded:</div>
                                <div class="flex-1 text-sm" id="modalUploadDate"></div>
                            </div>
                            
                            <!-- Description -->
                            <div class="flex items-start mt-2">
                                <div class="w-24 text-xs text-gray-500">Description:</div>
                                <div class="flex-1 text-xs text-gray-600 max-h-20 overflow-y-auto pr-2" id="modalDescription"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Footer -->
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                <button type="button" 
                        onclick="downloadFromModal()"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    <i class="fas fa-download mr-2"></i>
                    Download
                </button>
                <button type="button" 
                        onclick="closeModal()"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .line-clamp-1 {
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
    }
    
    .gallery-card {
        transition: all 0.2s ease;
    }
    
    .gallery-card:hover {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
    
    /* Make all filter elements consistent height */
    select, button, input {
        height: 36px;
    }
    
    /* Modal animation */
    #galleryModal {
        transition: opacity 0.2s ease;
    }
</style>
@endpush

@push('scripts')
<script>
    // Gallery data from backend
    const galleryData = @json($galleries->items());
    
    // Modal functionality
    let currentGalleryId = null;
    let currentGalleryDownloadUrl = null;
    let downloadInProgress = false;
    
    // Show gallery details in modal
    function showGalleryDetails(galleryId) {
        currentGalleryId = galleryId;
        const gallery = galleryData.find(g => g.id === galleryId);
        
        if (!gallery) return;
        
        // Set modal content
        document.getElementById('modalSchoolName').textContent = gallery.school_name;
        document.getElementById('modalCompetition').textContent = gallery.competition;
        
        // Combine season and series
        const seasonSeries = gallery.season + (gallery.series ? ' - ' + gallery.series : '');
        document.getElementById('modalSeasonSeries').textContent = seasonSeries;
        
        document.getElementById('modalFileType').textContent = gallery.file_type || 'ZIP Archive';
        document.getElementById('modalFilename').textContent = gallery.original_filename;
        document.getElementById('modalDescription').textContent = gallery.description || 'No description provided.';
        document.getElementById('modalDownloads').textContent = gallery.download_count;
        document.getElementById('modalUploadDate').textContent = new Date(gallery.created_at).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        // Format file size
        let fileSize = '';
        if (gallery.file_size) {
            if (gallery.file_size < 1024 * 1024) {
                fileSize = Math.round(gallery.file_size / 1024) + ' KB';
            } else {
                fileSize = (gallery.file_size / (1024 * 1024)).toFixed(1) + ' MB';
            }
        }
        document.getElementById('modalFileSize').textContent = fileSize;
        
        // Set status badge
        const statusEl = document.getElementById('modalStatus');
        statusEl.textContent = gallery.status.charAt(0).toUpperCase() + gallery.status.slice(1);
        statusEl.className = 'px-2 py-1 rounded-full text-xs font-medium ' + 
            (gallery.status === 'published' ? 'bg-green-100 text-green-800' :
             gallery.status === 'draft' ? 'bg-yellow-100 text-yellow-800' :
             'bg-gray-100 text-gray-800');
        
        // Set cover image di modal
        const modalCoverImage = document.getElementById('modalCoverImage');
        const modalCoverPlaceholder = document.getElementById('modalCoverPlaceholder');
        
        // Reset
        modalCoverImage.classList.add('hidden');
        modalCoverPlaceholder.classList.remove('hidden');
        
        // Cek apakah gallery memiliki photo_url
        if (gallery.photo_url) {
            modalCoverImage.src = gallery.photo_url;
            modalCoverImage.classList.remove('hidden');
            modalCoverPlaceholder.classList.add('hidden');
        }
        
        // Set download URL for modal
        const galleryCard = document.querySelector(`.gallery-card[data-gallery-id="${galleryId}"]`);
        if (galleryCard) {
            currentGalleryDownloadUrl = galleryCard.getAttribute('data-download-url');
        } else {
            currentGalleryDownloadUrl = `/user/gallery/photos/${galleryId}/download`;
        }
        
        // Show modal
        document.getElementById('galleryModal').classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }
    
    function closeModal() {
        document.getElementById('galleryModal').classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        currentGalleryId = null;
        currentGalleryDownloadUrl = null;
    }
    
    // Download function
    function downloadGallery(galleryId) {
        if (downloadInProgress) return;
        
        if (event) event.stopPropagation();
        
        const cardElement = document.querySelector(`.gallery-card[data-gallery-id="${galleryId}"]`);
        let downloadUrl;
        
        if (cardElement) {
            downloadUrl = cardElement.getAttribute('data-download-url');
        } else {
            downloadUrl = `/user/gallery/photos/${galleryId}/download`;
        }
        
        if (!downloadUrl) {
            Swal.fire({
                icon: 'error',
                title: 'Download Failed',
                text: 'Download URL not found.',
                confirmButtonText: 'OK'
            });
            return;
        }
        
        downloadInProgress = true;
        
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = downloadUrl;
        document.body.appendChild(iframe);
        
        setTimeout(() => {
            downloadInProgress = false;
            
            const gallery = galleryData.find(g => g.id === galleryId);
            const schoolName = gallery ? gallery.school_name : 'Gallery';
            
            Swal.fire({
                icon: 'success',
                title: 'Download Started',
                html: `<b>${schoolName}</b> photos are being downloaded.<br><small>Check your downloads folder.</small>`,
                timer: 3000,
                showConfirmButton: false,
                position: 'top-end',
                toast: true,
                background: '#f0fdf4',
                iconColor: '#16a34a'
            });
        }, 1000);
        
        setTimeout(() => {
            if (iframe.parentNode) {
                iframe.parentNode.removeChild(iframe);
            }
        }, 5000);
    }
    
    function downloadFromModal() {
        if (!currentGalleryDownloadUrl || downloadInProgress) return;
        
        downloadInProgress = true;
        
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = currentGalleryDownloadUrl;
        document.body.appendChild(iframe);
        
        setTimeout(() => {
            downloadInProgress = false;
            
            const schoolName = document.getElementById('modalSchoolName').textContent;
            
            Swal.fire({
                icon: 'success',
                title: 'Download Started',
                html: `<b>${schoolName}</b> photos are being downloaded.<br><small>Check your downloads folder.</small>`,
                timer: 3000,
                showConfirmButton: false,
                position: 'top-end',
                toast: true,
                background: '#f0fdf4',
                iconColor: '#16a34a'
            });
            
            closeModal();
        }, 1000);
        
        setTimeout(() => {
            if (iframe.parentNode) {
                iframe.parentNode.removeChild(iframe);
            }
        }, 5000);
    }
    
    // Filter functions
    function resetSearch() {
        document.getElementById('searchInput').value = '';
        applyFilters();
    }
    
    function resetAllFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('competitionFilter').value = '';
        document.getElementById('seasonFilter').value = '';
        document.getElementById('seriesFilter').value = '';
        document.getElementById('sortSelect').value = 'newest';
        
        window.location.href = "{{ route('user.gallery.photos.index') }}";
    }
    
    function applyFilters() {
        const searchTerm = document.getElementById('searchInput').value;
        const competition = document.getElementById('competitionFilter').value;
        const season = document.getElementById('seasonFilter').value;
        const series = document.getElementById('seriesFilter').value;
        const sort = document.getElementById('sortSelect').value;
        
        const url = new URL("{{ route('user.gallery.photos.index') }}");
        
        if (searchTerm) url.searchParams.set('search', searchTerm);
        if (competition) url.searchParams.set('competition', competition);
        if (season) url.searchParams.set('season', season);
        if (series) url.searchParams.set('series', series);
        if (sort !== 'newest') url.searchParams.set('sort', sort);
        
        window.location.href = url.toString();
    }
    
    // Event listeners
    document.getElementById('sortSelect').addEventListener('change', applyFilters);
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') applyFilters();
    });
    document.getElementById('competitionFilter').addEventListener('change', applyFilters);
    document.getElementById('seasonFilter').addEventListener('change', applyFilters);
    document.getElementById('seriesFilter').addEventListener('change', applyFilters);
    
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });
</script>
@endpush

@endsection