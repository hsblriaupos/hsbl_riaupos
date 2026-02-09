@extends('user.layouts.app')

@section('title', 'Photo Gallery - HSBL Riau Pos')

@section('content')
<div class="min-h-screen">
    <!-- Header Section -->
    <div class="mb-8 md:mb-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">Photo Gallery</h1>
                <p class="text-gray-600">Explore collections of basketball moments from HSBL competitions</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">Total Photos:</span>
                <span class="px-3 py-1 bg-blue-100 text-blue-600 text-sm font-medium rounded-full">{{ $galleries->total() }} Collections</span>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="mb-8">
        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
            <div class="flex flex-col md:flex-row gap-4">
                <!-- Search Input -->
                <div class="flex-1">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                        <input type="text" 
                               id="searchInput" 
                               placeholder="Search by school name, competition..." 
                               class="block w-full pl-10 pr-12 py-2.5 border border-gray-200 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                               value="{{ request()->get('search') }}">
                        @if(request()->get('search'))
                        <button onclick="resetSearch()" 
                                class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-red-500 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                        @endif
                    </div>
                </div>
                
                <!-- Filter Button -->
                <button onclick="toggleFilters()"
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-200 rounded-lg bg-white hover:bg-gray-50 transition-colors duration-200">
                    <i class="fas fa-filter text-gray-600 mr-2"></i>
                    <span class="text-sm font-medium">Filters</span>
                    <span id="filterCount" class="ml-2 px-1.5 py-0.5 bg-blue-500 text-white text-xs rounded-full {{ $hasActiveFilters ? '' : 'hidden' }}">{{ $activeFilterCount }}</span>
                </button>
                
                <!-- Sort Dropdown -->
                <div class="relative">
                    <select id="sortSelect" 
                            class="appearance-none w-full md:w-auto px-4 py-2.5 pr-10 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 cursor-pointer">
                        <option value="newest" {{ request()->get('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ request()->get('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                        <option value="downloads" {{ request()->get('sort') == 'downloads' ? 'selected' : '' }}>Most Downloads</option>
                        <option value="name" {{ request()->get('sort') == 'name' ? 'selected' : '' }}>School Name A-Z</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <i class="fas fa-chevron-down text-gray-400"></i>
                    </div>
                </div>
                
                <!-- Reset Button (Visible when filters are active) -->
                <button id="resetButton" 
                        onclick="resetAllFilters()"
                        class="inline-flex items-center justify-center px-4 py-2.5 border border-red-200 text-red-600 rounded-lg bg-red-50 hover:bg-red-100 transition-colors duration-200 {{ $hasActiveFilters ? '' : 'hidden' }}">
                    <i class="fas fa-redo mr-2"></i>
                    <span class="text-sm font-medium">Reset</span>
                </button>
            </div>
            
            <!-- Filter Options (Hidden by Default) -->
            <div id="filterOptions" class="mt-4 {{ $hasActiveFilters ? '' : 'hidden' }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Competition Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Competition</label>
                        <select id="competitionFilter" 
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Competitions</option>
                            @foreach($competitions ?? [] as $competition)
                                <option value="{{ $competition }}" {{ request()->get('competition') == $competition ? 'selected' : '' }}>{{ $competition }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Season Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Season</label>
                        <select id="seasonFilter" 
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Seasons</option>
                            @foreach($seasons ?? [] as $season)
                                <option value="{{ $season }}" {{ request()->get('season') == $season ? 'selected' : '' }}>{{ $season }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Series Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Series</label>
                        <select id="seriesFilter" 
                                class="w-full px-3 py-2 border border-gray-200 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">All Series</option>
                            @foreach($series ?? [] as $seriesItem)
                                <option value="{{ $seriesItem }}" {{ request()->get('series') == $seriesItem ? 'selected' : '' }}>{{ $seriesItem }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-end space-x-3">
                    <button onclick="clearFilters()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                        Clear Filters
                    </button>
                    <button onclick="applyFilters()"
                            class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200 font-medium">
                        Apply Filters
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Grid -->
    <div class="mb-10">
        @if($galleries->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($galleries as $gallery)
                    @php
                        // Generate random basketball-themed image URL based on school name
                        $schoolHash = crc32($gallery->school_name);
                        $imageIndex = ($schoolHash % 12) + 1;
                        
                        $basketballImages = [
                            'https://images.unsplash.com/photo-1544919982-b61976a0d7ed?w=800&h=600&fit=crop&auto=format',
                            'https://images.unsplash.com/photo-1519861531473-920034658307?w=800&h=600&fit=crop&auto=format',
                            'https://images.unsplash.com/photo-1516906571665-49af58989c4e?w=800&h=600&fit=crop&auto=format',
                            'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=800&h=600&fit=crop&auto=format',
                            'https://images.unsplash.com/photo-1518406432532-9cbef5697723?w=800&h=600&fit=crop&auto=format',
                            'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=800&h=600&fit=crop&auto=format',
                            'https://images.unsplash.com/photo-1549242484-9a322b5d3a9b?w=800&h=600&fit=crop&auto=format',
                            'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop&auto=format',
                            'https://images.unsplash.com/photo-1551958219-acbc608c6377?w=800&h=600&fit=crop&auto=format',
                            'https://images.unsplash.com/photo-1534158914592-062992fbe900?w=800&h=600&fit=crop&auto=format',
                            'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=800&h=600&fit=crop&auto=format',
                            'https://images.unsplash.com/photo-1542744095-fcf48d80b0fd?w=800&h=600&fit=crop&auto=format',
                        ];
                        $coverImage = $basketballImages[$imageIndex - 1];
                        
                        // Format file size
                        $fileSize = '';
                        if ($gallery->file_size) {
                            if ($gallery->file_size < 1024 * 1024) {
                                $fileSize = round($gallery->file_size / 1024, 1) . ' KB';
                            } else {
                                $fileSize = round($gallery->file_size / (1024 * 1024), 1) . ' MB';
                            }
                        }
                        
                        // Generate download URL
                        $downloadUrl = route('user.gallery.photos.download', ['id' => $gallery->id]);
                    @endphp
                    
                    <div class="gallery-card group relative bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden cursor-pointer transform hover:-translate-y-1"
                         onclick="showGalleryDetails({{ $gallery->id }})"
                         data-school="{{ strtolower($gallery->school_name) }}"
                         data-competition="{{ strtolower($gallery->competition) }}"
                         data-season="{{ strtolower($gallery->season) }}"
                         data-series="{{ strtolower($gallery->series) }}"
                         data-download-url="{{ $downloadUrl }}">
                        <!-- Cover Image -->
                        <div class="relative h-48 overflow-hidden">
                            <img src="{{ $coverImage }}" 
                                 alt="{{ $gallery->school_name }} Basketball Gallery" 
                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            
                            <!-- Download Count Badge -->
                            <div class="absolute top-3 right-3 bg-black/70 text-white px-2 py-1 rounded-full text-xs font-medium">
                                <i class="fas fa-download mr-1"></i>
                                {{ $gallery->download_count }}
                            </div>
                            
                            <!-- ZIP File Badge -->
                            <div class="absolute bottom-3 left-3">
                                <span class="px-2 py-1 bg-blue-500 text-white text-xs font-semibold rounded-full">
                                    <i class="fas fa-file-archive mr-1"></i>ZIP
                                </span>
                            </div>
                        </div>
                        
                        <!-- Card Content -->
                        <div class="p-5">
                            <!-- School Name -->
                            <h3 class="text-lg font-bold text-gray-800 mb-2 line-clamp-1">{{ $gallery->school_name }}</h3>
                            
                            <!-- Competition -->
                            <div class="flex items-center mb-3">
                                <i class="fas fa-trophy text-yellow-500 mr-2 text-sm"></i>
                                <span class="text-sm font-medium text-gray-700">{{ $gallery->competition }}</span>
                            </div>
                            
                            <!-- File Info -->
                            <div class="flex items-center justify-between text-sm text-gray-500">
                                <div class="flex items-center">
                                    <i class="far fa-file mr-1.5"></i>
                                    <span>{{ $gallery->file_type ? strtoupper(pathinfo($gallery->original_filename, PATHINFO_EXTENSION)) : 'ZIP' }}</span>
                                </div>
                                @if($fileSize)
                                    <div class="flex items-center">
                                        <i class="fas fa-weight-hanging mr-1.5"></i>
                                        <span>{{ $fileSize }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="mt-4 pt-4 border-t border-gray-100 flex justify-between items-center">
                                <button onclick="event.stopPropagation(); downloadGallery({{ $gallery->id }})"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 transition-colors duration-200">
                                    <i class="fas fa-download mr-2"></i>
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
                <div class="mt-8">
                    {{ $galleries->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-100 mb-4">
                    <i class="fas fa-images text-blue-500 text-2xl"></i>
                </div>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No Photo Galleries Available</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">Photo galleries from competitions will appear here once they are uploaded.</p>
                @if($hasActiveFilters)
                <button onclick="resetAllFilters()"
                        class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200">
                    <i class="fas fa-redo mr-2"></i>
                    Reset All Filters
                </button>
                @endif
            </div>
        @endif
    </div>

    <!-- Statistics Section -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-10">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Gallery Statistics</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-blue-50 rounded-lg p-5">
                <div class="flex items-center">
                    <div class="mr-4">
                        <i class="fas fa-images text-blue-500 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Collections</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $galleries->total() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 rounded-lg p-5">
                <div class="flex items-center">
                    <div class="mr-4">
                        <i class="fas fa-download text-green-500 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Downloads</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $galleries->sum('download_count') }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-purple-50 rounded-lg p-5">
                <div class="flex items-center">
                    <div class="mr-4">
                        <i class="fas fa-university text-purple-500 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Unique Schools</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $galleries->unique('school_name')->count() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-orange-50 rounded-lg p-5">
                <div class="flex items-center">
                    <div class="mr-4">
                        <i class="fas fa-trophy text-orange-500 text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Competitions</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $galleries->unique('competition')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gallery Details Modal -->
<div id="galleryModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white rounded-xl shadow-xl max-w-4xl w-full mx-auto overflow-hidden transform transition-all">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-500 px-6 py-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-white">Gallery Details</h3>
                    <button onclick="closeModal()" class="text-white hover:text-blue-200 transition-colors">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            
            <!-- Modal Body -->
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Left Column - Image & Basic Info -->
                    <div class="lg:col-span-1">
                        <div class="bg-gray-100 rounded-lg overflow-hidden mb-4">
                            <img id="modalCoverImage" src="" alt="Gallery Cover" class="w-full h-48 object-cover">
                        </div>
                        
                        <!-- Quick Info -->
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <i class="fas fa-download text-blue-500 mr-3 w-5"></i>
                                <span class="text-sm text-gray-600">Downloads: </span>
                                <span id="modalDownloads" class="ml-2 font-medium"></span>
                            </div>
                            <div class="flex items-center">
                                <i class="far fa-calendar text-blue-500 mr-3 w-5"></i>
                                <span class="text-sm text-gray-600">Uploaded: </span>
                                <span id="modalUploadDate" class="ml-2 font-medium"></span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-weight-hanging text-blue-500 mr-3 w-5"></i>
                                <span class="text-sm text-gray-600">File Size: </span>
                                <span id="modalFileSize" class="ml-2 font-medium"></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Right Column - Details -->
                    <div class="lg:col-span-2">
                        <!-- School Name -->
                        <h2 id="modalSchoolName" class="text-2xl font-bold text-gray-800 mb-2"></h2>
                        
                        <!-- Competition -->
                        <div class="flex items-center mb-4">
                            <i class="fas fa-trophy text-yellow-500 mr-2"></i>
                            <span id="modalCompetition" class="font-medium text-gray-700"></span>
                        </div>
                        
                        <!-- Details Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 mb-1">Season</p>
                                <p id="modalSeason" class="font-medium"></p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 mb-1">Series</p>
                                <p id="modalSeries" class="font-medium"></p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 mb-1">File Type</p>
                                <p id="modalFileType" class="font-medium"></p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 mb-1">Status</p>
                                <span id="modalStatus" class="px-2 py-1 rounded-full text-xs font-medium"></span>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">Description</h4>
                            <p id="modalDescription" class="text-gray-600 bg-gray-50 p-4 rounded-lg"></p>
                        </div>
                        
                        <!-- File Info -->
                        <div class="mb-6">
                            <h4 class="text-sm font-semibold text-gray-700 mb-2">File Information</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-600 mb-1">
                                    <i class="far fa-file-alt mr-2"></i>
                                    <span id="modalFilename"></span>
                                </p>
                                <p class="text-sm text-gray-600">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span>This is a ZIP archive containing all photos from the gallery.</span>
                                </p>
                            </div>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3">
                            <button onclick="closeModal()"
                                    class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                                Close
                            </button>
                            <button id="modalDownloadBtn"
                                    onclick="downloadFromModal()"
                                    class="px-4 py-2.5 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors duration-200 font-medium">
                                <i class="fas fa-download mr-2"></i>
                                Download Gallery
                            </button>
                        </div>
                    </div>
                </div>
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
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .gallery-card:hover {
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1);
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
        document.getElementById('modalSeason').textContent = gallery.season;
        document.getElementById('modalSeries').textContent = gallery.series;
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
        
        // Set cover image
        const schoolHash = crc32(gallery.school_name);
        const imageIndex = (schoolHash % 12) + 1;
        
        const basketballImages = [
            'https://images.unsplash.com/photo-1544919982-b61976a0d7ed?w=800&h=600&fit=crop&auto=format',
            'https://images.unsplash.com/photo-1519861531473-920034658307?w=800&h=600&fit=crop&auto=format',
            'https://images.unsplash.com/photo-1516906571665-49af58989c4e?w=800&h=600&fit=crop&auto=format',
            'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=800&h=600&fit=crop&auto=format',
            'https://images.unsplash.com/photo-1518406432532-9cbef5697723?w=800&h=600&fit=crop&auto=format',
            'https://images.unsplash.com/photo-1575361204480-aadea25e6e68?w=800&h=600&fit=crop&auto=format',
            'https://images.unsplash.com/photo-1549242484-9a322b5d3a9b?w=800&h=600&fit=crop&auto=format',
            'https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=800&h=600&fit=crop&auto=format',
            'https://images.unsplash.com/photo-1551958219-acbc608c6377?w=800&h=600&fit=crop&auto=format',
            'https://images.unsplash.com/photo-1534158914592-062992fbe900?w=800&h=600&fit=crop&auto=format',
            'https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?w=800&h=600&fit=crop&auto=format',
            'https://images.unsplash.com/photo-1542744095-fcf48d80b0fd?w=800&h=600&fit=crop&auto=format',
        ];
        
        document.getElementById('modalCoverImage').src = basketballImages[imageIndex - 1];
        
        // Set download URL for modal
        const galleryCard = document.querySelector(`[data-download-url*="${galleryId}"]`);
        if (galleryCard) {
            currentGalleryDownloadUrl = galleryCard.getAttribute('data-download-url');
        } else {
            // Fallback: create download URL
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
    
    // SIMPLE DOWNLOAD FUNCTION - NO SWEETALERT PROGRESS
    function downloadGallery(galleryId) {
        if (downloadInProgress) return;
        
        // Prevent event bubbling
        event.stopPropagation();
        
        // Get download URL from the card element
        const cardElement = document.querySelector(`.gallery-card[data-download-url*="${galleryId}"]`);
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
        
        // Set download flag
        downloadInProgress = true;
        
        // Create hidden iframe for download
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = downloadUrl;
        document.body.appendChild(iframe);
        
        // Show success notification after short delay
        setTimeout(() => {
            downloadInProgress = false;
            
            // Get gallery name for the notification
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
        
        // Remove iframe after download
        setTimeout(() => {
            if (iframe.parentNode) {
                iframe.parentNode.removeChild(iframe);
            }
        }, 5000);
    }
    
    function downloadFromModal() {
        if (!currentGalleryDownloadUrl || downloadInProgress) return;
        
        downloadInProgress = true;
        
        // Create hidden iframe for download
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = currentGalleryDownloadUrl;
        document.body.appendChild(iframe);
        
        // Show success notification
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
            
            // Close modal after download starts
            closeModal();
        }, 1000);
        
        // Remove iframe after download
        setTimeout(() => {
            if (iframe.parentNode) {
                iframe.parentNode.removeChild(iframe);
            }
        }, 5000);
    }
    
    // Filter functionality
    function toggleFilters() {
        const filterOptions = document.getElementById('filterOptions');
        filterOptions.classList.toggle('hidden');
    }
    
    function clearFilters() {
        // Clear all filter inputs
        document.getElementById('competitionFilter').value = '';
        document.getElementById('seasonFilter').value = '';
        document.getElementById('seriesFilter').value = '';
        document.getElementById('searchInput').value = '';
        
        // Update UI
        updateFilterUI();
    }
    
    function resetSearch() {
        document.getElementById('searchInput').value = '';
        applyFilters();
    }
    
    function resetAllFilters() {
        // Clear all filters
        clearFilters();
        
        // Reset sort to default
        document.getElementById('sortSelect').value = 'newest';
        
        // Redirect to clean URL (remove all query parameters)
        window.location.href = "{{ route('user.gallery.photos.index') }}";
    }
    
    function applyFilters() {
        const searchTerm = document.getElementById('searchInput').value;
        const competition = document.getElementById('competitionFilter').value;
        const season = document.getElementById('seasonFilter').value;
        const series = document.getElementById('seriesFilter').value;
        const sort = document.getElementById('sortSelect').value;
        
        // Build URL with query parameters
        const url = new URL("{{ route('user.gallery.photos.index') }}");
        
        if (searchTerm) url.searchParams.set('search', searchTerm);
        if (competition) url.searchParams.set('competition', competition);
        if (season) url.searchParams.set('season', season);
        if (series) url.searchParams.set('series', series);
        if (sort !== 'newest') url.searchParams.set('sort', sort);
        
        // Redirect with filters
        window.location.href = url.toString();
    }
    
    function updateFilterUI() {
        const searchTerm = document.getElementById('searchInput').value;
        const competition = document.getElementById('competitionFilter').value;
        const season = document.getElementById('seasonFilter').value;
        const series = document.getElementById('seriesFilter').value;
        
        // Count active filters
        let activeFilterCount = 0;
        if (searchTerm) activeFilterCount++;
        if (competition) activeFilterCount++;
        if (season) activeFilterCount++;
        if (series) activeFilterCount++;
        
        // Update filter count badge
        const filterCount = document.getElementById('filterCount');
        const resetButton = document.getElementById('resetButton');
        
        if (activeFilterCount > 0) {
            filterCount.textContent = activeFilterCount;
            filterCount.classList.remove('hidden');
            resetButton.classList.remove('hidden');
        } else {
            filterCount.classList.add('hidden');
            resetButton.classList.add('hidden');
        }
    }
    
    // Sort functionality
    document.getElementById('sortSelect').addEventListener('change', function() {
        applyFilters();
    });
    
    // CRC32 function for consistent image selection
    function crc32(str) {
        for(var a, o = [], c = 0; c < 256; c++) {
            a = c;
            for(var f = 0; f < 8; f++) a = 1 & a ? 3988292384 ^ a >>> 1 : a >>> 1;
            o[c] = a;
        }
        for(var n = -1, t = 0; t < str.length; t++) n = n >>> 8 ^ o[255 & (n ^ str.charCodeAt(t))];
        return (-1 ^ n) >>> 0;
    }
    
    // Initialize UI on page load
    document.addEventListener('DOMContentLoaded', function() {
        updateFilterUI();
        
        // Auto-show filter options if filters are active
        if ({{ $hasActiveFilters ? 'true' : 'false' }}) {
            document.getElementById('filterOptions').classList.remove('hidden');
        }
    });
    
    // Initialize search input
    document.getElementById('searchInput').addEventListener('input', function() {
        clearTimeout(this.searchTimeout);
        this.searchTimeout = setTimeout(() => {
            updateFilterUI();
        }, 300);
    });
    
    // Add change listeners to filter dropdowns
    document.getElementById('competitionFilter').addEventListener('change', updateFilterUI);
    document.getElementById('seasonFilter').addEventListener('change', updateFilterUI);
    document.getElementById('seriesFilter').addEventListener('change', updateFilterUI);
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endpush
@endsection