@extends('user.layouts.app')

@section('title', $video->title)

@php
    use Illuminate\Support\Str;
    
    // Use data from controller
    $isYoutube = $video->is_youtube ?? false;
    $videoId = $video->youtube_id ?? null;
    
    // Current URL for sharing
    $currentUrl = url()->current();
    
    // Create embed URL with optimal parameters
    $embedUrl = null;
    if ($isYoutube && $videoId) {
        $embedUrl = "https://www.youtube-nocookie.com/embed/{$videoId}?rel=0&modestbranding=1&showinfo=0&autoplay=0&playsinline=1";
    }
    
    // URL to open directly on YouTube
    $youtubeWatchUrl = $video->clean_youtube_link ?? ($videoId ? "https://www.youtube.com/watch?v={$videoId}" : null);
    
    // Limit related videos to 8
    $relatedVideos = $relatedVideos->take(8);
@endphp

@section('content')
<main class="bg-gray-50 min-h-screen pb-16">
    {{-- Navigation Header with Back Button --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div class="max-w-6xl mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('user.videos') }}" 
                       class="flex items-center text-blue-600 hover:text-blue-800 transition-colors group mr-4">
                        <i class="fas fa-arrow-left mr-2 text-lg group-hover:-translate-x-1 transition-transform"></i>
                        <span class="font-medium hidden sm:inline">Back to Videos</span>
                        <span class="font-medium sm:hidden">Back</span>
                    </a>
                    <div class="hidden md:block ml-4 pl-4 border-l border-gray-300">
                        <h1 class="text-sm font-semibold text-gray-700 truncate max-w-md">{{ $video->title }}</h1>
                    </div>
                </div>
                
                <div class="flex items-center space-x-3">
                    <div class="hidden sm:flex items-center space-x-2 text-sm text-gray-500">
                        <span class="hidden md:inline">Share:</span>
                        <button onclick="shareVideo('facebook')" class="p-2 text-blue-600 hover:text-blue-800 rounded-full hover:bg-blue-50" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button onclick="shareVideo('whatsapp')" class="p-2 text-green-500 hover:text-green-700 rounded-full hover:bg-green-50" title="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </button>
                        <button onclick="copyVideoLink()" class="p-2 text-gray-600 hover:text-gray-800 rounded-full hover:bg-gray-100" title="Copy Link">
                            <i class="fas fa-link"></i>
                        </button>
                    </div>
                    @if($isYoutube && $videoId)
                    <a href="{{ $youtubeWatchUrl }}" target="_blank" 
                       class="inline-flex items-center px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                        <i class="fab fa-youtube mr-1.5"></i>
                        <span class="hidden sm:inline">YouTube</span>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Video Player Section --}}
    <div class="bg-black py-6 sm:py-8">
        <div class="max-w-6xl mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <div class="relative pt-[56.25%] rounded-lg sm:rounded-xl overflow-hidden shadow-2xl bg-black">
                    @if($isYoutube && $videoId && $embedUrl)
                        {{-- YouTube Embed with fallback mechanism --}}
                        <iframe 
                            id="youtubePlayer"
                            class="absolute top-0 left-0 w-full h-full"
                            src="{{ $embedUrl }}"
                            title="{{ $video->title }}"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen
                            referrerpolicy="strict-origin-when-cross-origin"
                            loading="lazy"
                            onerror="showYouTubeFallback()">
                        </iframe>
                        
                        {{-- Fallback container (hidden by default) --}}
                        <div id="youtubeFallback" class="absolute top-0 left-0 w-full h-full bg-gray-900 hidden flex-col items-center justify-center p-4 sm:p-6 text-center">
                            <div class="mb-3 sm:mb-4">
                                <i class="fab fa-youtube text-red-600 text-4xl sm:text-6xl"></i>
                            </div>
                            <h3 class="text-white text-lg sm:text-xl font-semibold mb-2">Video Cannot Be Played</h3>
                            <p class="text-gray-300 text-sm sm:text-base mb-4 px-2">This video may have embedding restrictions.</p>
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 justify-center">
                                @if($youtubeWatchUrl)
                                <a href="{{ $youtubeWatchUrl }}" target="_blank"
                                   class="inline-flex items-center justify-center px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium text-sm sm:text-base">
                                    <i class="fab fa-youtube mr-2"></i>Watch on YouTube
                                </a>
                                @endif
                                <button onclick="retryEmbed()" 
                                        class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm sm:text-base">
                                    <i class="fas fa-redo mr-2"></i>Try Again
                                </button>
                            </div>
                        </div>
                        
                    @elseif($video->type === 'live')
                        {{-- Live Stream Player --}}
                        <div class="absolute top-0 left-0 w-full h-full bg-gray-900 flex items-center justify-center">
                            <div class="text-center p-4">
                                <div class="mb-4">
                                    <div class="relative inline-block">
                                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-red-600 animate-ping opacity-75"></div>
                                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-red-600 flex items-center justify-center">
                                            <i class="fas fa-broadcast-tower text-white text-xl sm:text-2xl"></i>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="text-white text-lg sm:text-xl font-semibold mb-2">Live Streaming</h3>
                                <p class="text-gray-300 text-sm sm:text-base">Video will start soon...</p>
                            </div>
                        </div>
                        
                    @elseif(!empty($video->video_code))
                        {{-- Custom Video Player for local videos --}}
                        <div class="absolute top-0 left-0 w-full h-full">
                            <video 
                                class="w-full h-full"
                                controls
                                controlsList="nodownload"
                                poster="{{ $video->thumbnail ? asset($video->thumbnail) : '' }}"
                                preload="metadata">
                                <source src="{{ asset($video->video_code) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                        
                    @else
                        {{-- Fallback if no player available --}}
                        <div class="absolute top-0 left-0 w-full h-full bg-gray-800 flex items-center justify-center">
                            <div class="text-center p-6">
                                <i class="fas fa-video-slash text-gray-400 text-4xl sm:text-5xl mb-4"></i>
                                <h3 class="text-white text-lg sm:text-xl font-semibold mb-2">Video Unavailable</h3>
                                <p class="text-gray-300 text-sm sm:text-base">This video cannot be played at the moment.</p>
                            </div>
                        </div>
                    @endif
                    
                    {{-- Live badge --}}
                    @if($video->type === 'live')
                        <div class="absolute top-3 sm:top-4 left-3 sm:left-4">
                            <span class="bg-red-600 text-white px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-semibold flex items-center animate-pulse">
                                <i class="fas fa-circle mr-1.5 sm:mr-2 text-xs"></i> LIVE
                            </span>
                        </div>
                    @endif
                    
                    {{-- YouTube badge --}}
                    @if($isYoutube && $videoId)
                        <div class="absolute top-3 sm:top-4 right-3 sm:right-4">
                            <span class="bg-red-600 text-white px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-semibold flex items-center">
                                <i class="fab fa-youtube mr-1.5 sm:mr-2 text-xs sm:text-sm"></i> YouTube
                            </span>
                        </div>
                    @endif
                </div>
                
                {{-- Quick Back Button for Mobile --}}
                <div class="mt-4 sm:hidden">
                    <a href="{{ route('user.videos') }}" 
                       class="inline-flex items-center justify-center w-full px-4 py-3 bg-white border border-gray-300 text-gray-800 rounded-lg hover:bg-gray-50 transition-colors font-medium shadow-sm">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Videos
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Content Section --}}
    <div class="max-w-6xl mx-auto px-4 py-6 sm:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
            {{-- Left Column - Video Details --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Video Info Card --}}
                <div class="bg-white rounded-xl shadow-lg p-5 sm:p-6">
                    <div class="mb-5 sm:mb-6">
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-3">{{ $video->title }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-3 text-gray-600">
                            <div class="flex items-center text-sm">
                                <i class="far fa-calendar-alt mr-2 text-gray-400"></i>
                                <span>{{ $video->created_at->translatedFormat('F d, Y') }}</span>
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="far fa-clock mr-2 text-gray-400"></i>
                                <span>{{ $video->created_at->diffForHumans() }}</span>
                            </div>
                            @if($video->view_count > 0)
                            <div class="flex items-center text-sm">
                                <i class="far fa-eye mr-2 text-gray-400"></i>
                                <span>{{ number_format($video->view_count) }} views</span>
                            </div>
                            @endif
                            @if($video->type)
                            <div class="flex items-center text-sm">
                                <i class="fas fa-tag mr-2 text-gray-400"></i>
                                <span class="capitalize">{{ $video->type }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Video Description --}}
                    <div class="mb-6">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-3 pb-2 border-b border-gray-200">Description</h3>
                        <div class="prose max-w-none text-gray-700 bg-gray-50 rounded-lg p-4 sm:p-5">
                            @if(!empty($video->description))
                                {!! nl2br(e($video->description)) !!}
                            @else
                                <p class="text-gray-500 italic">No description available for this video.</p>
                            @endif
                        </div>
                    </div>
                    
                    {{-- Share Section --}}
                    <div class="border-t border-gray-200 pt-5 sm:pt-6">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900 mb-4">Share This Video</h3>
                        <div class="flex flex-wrap gap-3 mb-5">
                            <button onclick="shareVideo('facebook')" 
                                    class="flex items-center justify-center px-4 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors shadow-md flex-1 min-w-[140px]">
                                <i class="fab fa-facebook-f mr-2.5 text-lg"></i>
                                <span>Facebook</span>
                            </button>
                            <button onclick="shareVideo('twitter')" 
                                    class="flex items-center justify-center px-4 py-2.5 bg-blue-400 text-white rounded-lg hover:bg-blue-500 transition-colors shadow-md flex-1 min-w-[140px]">
                                <i class="fab fa-twitter mr-2.5 text-lg"></i>
                                <span>Twitter</span>
                            </button>
                            <button onclick="shareVideo('whatsapp')" 
                                    class="flex items-center justify-center px-4 py-2.5 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors shadow-md flex-1 min-w-[140px]">
                                <i class="fab fa-whatsapp mr-2.5 text-lg"></i>
                                <span>WhatsApp</span>
                            </button>
                            <button onclick="shareVideo('telegram')" 
                                    class="flex items-center justify-center px-4 py-2.5 bg-blue-300 text-white rounded-lg hover:bg-blue-400 transition-colors shadow-md flex-1 min-w-[140px]">
                                <i class="fab fa-telegram mr-2.5 text-lg"></i>
                                <span>Telegram</span>
                            </button>
                        </div>
                        
                        {{-- Copy Link --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Video Link</label>
                            <div class="flex">
                                <input type="text" id="videoLink" value="{{ $currentUrl }}" readonly
                                    class="flex-1 px-4 py-3 border border-gray-300 rounded-l-lg bg-gray-50 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent truncate">
                                <button onclick="copyVideoLink()" 
                                        class="px-4 sm:px-5 py-3 bg-blue-600 text-white rounded-r-lg hover:bg-blue-700 transition-colors font-medium whitespace-nowrap">
                                    <i class="fas fa-copy mr-2"></i>Copy
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    {{-- YouTube Direct Link --}}
                    @if($isYoutube && $videoId && $youtubeWatchUrl)
                    <div class="mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center mb-2">
                            <i class="fab fa-youtube text-red-600 mr-2 text-lg"></i>
                            <h4 class="font-semibold text-red-900">Alternative Playback</h4>
                        </div>
                        <p class="text-red-700 text-sm mb-3">If experiencing playback issues, open the video directly on YouTube:</p>
                        <a href="{{ $youtubeWatchUrl }}" target="_blank"
                           class="inline-flex items-center px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                            <i class="fab fa-youtube mr-2"></i>Open on YouTube
                        </a>
                    </div>
                    @endif
                </div>
                
                {{-- Video Details Card --}}
                <div class="bg-white rounded-xl shadow-lg p-5 sm:p-6">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Video Details</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600 font-medium">Title</span>
                            <span class="font-semibold text-gray-800 text-right max-w-xs">{{ Str::limit($video->title, 40) }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-t border-gray-100">
                            <span class="text-gray-600 font-medium">Video Type</span>
                            <span class="font-semibold {{ $video->type === 'live' ? 'text-orange-600' : 'text-green-600' }}">
                                {{ strtoupper($video->type) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-t border-gray-100">
                            <span class="text-gray-600 font-medium">Status</span>
                            <span class="font-semibold text-green-600">PUBLISHED</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-t border-gray-100">
                            <span class="text-gray-600 font-medium">Platform</span>
                            <span class="font-semibold">
                                @if($isYoutube)
                                    <i class="fab fa-youtube text-red-500 mr-1.5"></i> YouTube
                                @else
                                    <i class="fas fa-video text-blue-500 mr-1.5"></i> Local Video
                                @endif
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-t border-gray-100">
                            <span class="text-gray-600 font-medium">Upload Date</span>
                            <span class="font-semibold text-gray-800">{{ $video->created_at->format('d/m/Y') }}</span>
                        </div>
                        @if($video->view_count > 0)
                        <div class="flex justify-between items-center py-2 border-t border-gray-100">
                            <span class="text-gray-600 font-medium">Total Views</span>
                            <span class="font-semibold text-blue-600">{{ number_format($video->view_count) }}</span>
                        </div>
                        @endif
                        @if($video->updated_at->gt($video->created_at))
                        <div class="flex justify-between items-center py-2 border-t border-gray-100">
                            <span class="text-gray-600 font-medium">Last Updated</span>
                            <span class="font-semibold text-gray-800">{{ $video->updated_at->format('d/m/Y') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Right Column - Sidebar --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Back Button Card for Desktop --}}
                <div class="bg-white rounded-xl shadow-lg p-5 sm:p-6 hidden lg:block">
                    <a href="{{ route('user.videos') }}" 
                       class="flex items-center justify-center px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium group">
                        <i class="fas fa-arrow-left mr-3 group-hover:-translate-x-1 transition-transform"></i>
                        <span>Back to Videos</span>
                    </a>
                </div>
                
                {{-- Quick Share Card --}}
                <div class="bg-white rounded-xl shadow-lg p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-900">Quick Share</h3>
                        <i class="fas fa-share-alt text-gray-400"></i>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <button onclick="shareVideo('facebook')" 
                                class="flex flex-col items-center justify-center p-3 sm:p-4 bg-blue-500 text-white rounded-xl hover:bg-blue-600 transition-colors shadow-md">
                            <i class="fab fa-facebook-f text-xl sm:text-2xl mb-1.5 sm:mb-2"></i>
                            <span class="text-xs font-medium">Facebook</span>
                        </button>
                        <button onclick="shareVideo('twitter')" 
                                class="flex flex-col items-center justify-center p-3 sm:p-4 bg-blue-400 text-white rounded-xl hover:bg-blue-500 transition-colors shadow-md">
                            <i class="fab fa-twitter text-xl sm:text-2xl mb-1.5 sm:mb-2"></i>
                            <span class="text-xs font-medium">Twitter</span>
                        </button>
                        <button onclick="shareVideo('whatsapp')" 
                                class="flex flex-col items-center justify-center p-3 sm:p-4 bg-green-500 text-white rounded-xl hover:bg-green-600 transition-colors shadow-md">
                            <i class="fab fa-whatsapp text-xl sm:text-2xl mb-1.5 sm:mb-2"></i>
                            <span class="text-xs font-medium">WhatsApp</span>
                        </button>
                        <button onclick="shareVideo('telegram')" 
                                class="flex flex-col items-center justify-center p-3 sm:p-4 bg-blue-300 text-white rounded-xl hover:bg-blue-400 transition-colors shadow-md">
                            <i class="fab fa-telegram text-xl sm:text-2xl mb-1.5 sm:mb-2"></i>
                            <span class="text-xs font-medium">Telegram</span>
                        </button>
                    </div>
                </div>
                
                {{-- Related Videos Card --}}
                <div class="bg-white rounded-xl shadow-lg p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Related Videos</h3>
                            @if($relatedVideos->count() > 0)
                            <p class="text-xs text-gray-500 mt-1">{{ $relatedVideos->count() }} videos available</p>
                            @endif
                        </div>
                        <a href="{{ route('user.videos') }}" 
                           class="text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                            <span class="hidden sm:inline">All Videos</span>
                            <i class="fas fa-arrow-right ml-1 text-xs"></i>
                        </a>
                    </div>
                    
                    <div class="space-y-3 sm:space-y-4 max-h-[500px] overflow-y-auto pr-2">
                        @forelse($relatedVideos as $relatedVideo)
                            @php
                                $routeParam = ['id' => $relatedVideo->id];
                                if (!empty($relatedVideo->slug)) {
                                    $routeParam = ['slug' => $relatedVideo->slug];
                                }
                                
                                $isRelatedYoutube = false;
                                if (!empty($relatedVideo->youtube_link)) {
                                    $isRelatedYoutube = Str::contains($relatedVideo->youtube_link, ['youtube.com', 'youtu.be']);
                                }
                            @endphp
                            
                            <a href="{{ route('user.videos.detail', $routeParam) }}" 
                               class="block group transform hover:-translate-y-0.5 transition-transform duration-200">
                                <div class="flex bg-gray-50 hover:bg-blue-50 rounded-lg p-3 transition-colors duration-200 border border-transparent hover:border-blue-200">
                                    <div class="relative flex-shrink-0 w-28 h-16 sm:w-32 sm:h-20 rounded-lg overflow-hidden">
                                        @if($relatedVideo->thumbnail)
                                            <img src="{{ asset($relatedVideo->thumbnail) }}" alt="{{ $relatedVideo->title }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                                loading="lazy"
                                                onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzIwIiBoZWlnaHQ9IjE4MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZjFmMWYxIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZmlsbD0iIzk5OSI+VmlkZW8gVGh1bWJuYWlsPC90ZXh0Pjwvc3ZnPg=='">
                                        @else
                                            <div class="w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                                                <i class="fas fa-video text-gray-400 text-lg"></i>
                                            </div>
                                        @endif
                                        @if($relatedVideo->type === 'live')
                                            <div class="absolute top-1 left-1 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded-md">
                                                <i class="fas fa-broadcast-tower text-xs mr-0.5"></i>
                                            </div>
                                        @elseif($isRelatedYoutube)
                                            <div class="absolute top-1 left-1 bg-red-600 text-white text-xs px-1.5 py-0.5 rounded-md">
                                                <i class="fab fa-youtube text-xs"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3 flex-1 min-w-0">
                                        <h4 class="font-medium text-gray-900 group-hover:text-blue-600 line-clamp-2 mb-1 text-xs sm:text-sm leading-tight">
                                            {{ Str::limit($relatedVideo->title, 50) }}
                                        </h4>
                                        <div class="flex items-center text-gray-500 text-xs mt-1.5">
                                            <i class="far fa-clock mr-1.5 text-gray-400 text-xs"></i>
                                            <span>{{ $relatedVideo->created_at->diffForHumans() }}</span>
                                        </div>
                                        @if($isRelatedYoutube)
                                        <div class="flex items-center text-red-500 text-xs mt-1">
                                            <i class="fab fa-youtube mr-1.5 text-xs"></i>
                                            <span>YouTube</span>
                                        </div>
                                        @elseif($relatedVideo->type === 'live')
                                        <div class="flex items-center text-orange-500 text-xs mt-1">
                                            <i class="fas fa-broadcast-tower mr-1.5 text-xs"></i>
                                            <span>Live</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="text-center py-6 sm:py-8 bg-gray-50 rounded-lg">
                                <div class="inline-flex items-center justify-center w-12 h-12 sm:w-14 sm:h-14 bg-blue-100 rounded-full mb-3">
                                    <i class="fas fa-video text-blue-500 text-lg sm:text-xl"></i>
                                </div>
                                <p class="text-gray-600 mb-2 text-sm">No related videos yet</p>
                                <p class="text-gray-500 text-xs mb-4">Other videos will appear here</p>
                                <a href="{{ route('user.videos') }}" 
                                   class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-xs font-medium">
                                    <i class="fas fa-film mr-1.5"></i>View All Videos
                                </a>
                            </div>
                        @endforelse
                        
                        {{-- View All Button --}}
                        @if($relatedVideos->count() > 0)
                        <div class="pt-2">
                            <a href="{{ route('user.videos') }}" 
                               class="block text-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition-colors font-medium text-sm">
                                <i class="fas fa-list mr-2"></i>View All Videos
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// SweetAlert configuration
const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener('mouseenter', Swal.stopTimer)
        toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
})

// YouTube embed fallback functions
function showYouTubeFallback() {
    const fallback = document.getElementById('youtubeFallback');
    const player = document.getElementById('youtubePlayer');
    
    if (fallback && player) {
        player.style.display = 'none';
        fallback.classList.remove('hidden');
        fallback.classList.add('flex');
        
        console.log('YouTube embed failed, showing fallback');
    }
}

function retryEmbed() {
    const fallback = document.getElementById('youtubeFallback');
    const player = document.getElementById('youtubePlayer');
    
    if (fallback && player) {
        // Reload the iframe
        const src = player.src;
        player.src = '';
        setTimeout(() => {
            player.src = src;
            player.style.display = 'block';
            fallback.classList.add('hidden');
            fallback.classList.remove('flex');
        }, 100);
    }
}

// Share video function
function shareVideo(platform) {
    const url = encodeURIComponent('{{ $currentUrl }}');
    const title = encodeURIComponent('{{ $video->title }}');
    
    let shareUrl = '';
    switch(platform) {
        case 'facebook':
            shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${url}`;
            break;
        case 'twitter':
            shareUrl = `https://twitter.com/intent/tweet?url=${url}&text=${title}`;
            break;
        case 'whatsapp':
            shareUrl = `https://wa.me/?text=${title}%20${url}`;
            break;
        case 'telegram':
            shareUrl = `https://t.me/share/url?url=${url}&text=${title}`;
            break;
        default:
            return;
    }
    
    window.open(shareUrl, '_blank', 'width=600,height=400');
    
    Toast.fire({
        icon: 'success',
        title: `Successfully shared to ${platform}`
    });
}

// Copy video link to clipboard
function copyVideoLink() {
    const videoLink = document.getElementById('videoLink');
    videoLink.select();
    videoLink.setSelectionRange(0, 99999);
    
    navigator.clipboard.writeText(videoLink.value).then(() => {
        Toast.fire({
            icon: 'success',
            title: 'Link copied to clipboard!'
        });
    }).catch(err => {
        console.error('Error copying text: ', err);
        Toast.fire({
            icon: 'error',
            title: 'Failed to copy link'
        });
    });
}

// Auto-check YouTube embed status on page load
document.addEventListener('DOMContentLoaded', function() {
    const youtubePlayer = document.getElementById('youtubePlayer');
    
    if (youtubePlayer) {
        // Set timeout to check if video loaded (fallback detection)
        setTimeout(() => {
            try {
                // Try to access iframe content (may fail due to CORS)
                const iframeDoc = youtubePlayer.contentDocument || youtubePlayer.contentWindow.document;
                
                // Check for common YouTube error messages
                const bodyContent = iframeDoc.body.innerHTML;
                if (bodyContent.includes('Video unavailable') || 
                    bodyContent.includes('This video is not available') ||
                    bodyContent.includes('Embedding disabled')) {
                    showYouTubeFallback();
                }
            } catch (e) {
                // CORS error, can't check content directly
                console.log('Cannot check YouTube iframe content due to CORS');
                
                // Alternative: Check if iframe has reasonable dimensions
                setTimeout(() => {
                    if (youtubePlayer.offsetHeight < 100) {
                        showYouTubeFallback();
                    }
                }, 4000);
            }
        }, 3000);
    }
    
    // Add smooth transition to back button clicks
    const backButtons = document.querySelectorAll('a[href*="user.videos"]');
    backButtons.forEach(button => {
        if (!button.getAttribute('target')) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                // Add fade out animation
                document.body.style.opacity = '0.7';
                document.body.style.transition = 'opacity 0.2s';
                setTimeout(() => {
                    window.location.href = this.href;
                }, 200);
            });
        }
    });
});
</script>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.prose {
    line-height: 1.7;
    font-size: 0.95rem;
}

.prose p {
    margin-bottom: 0.75rem;
}

.prose p:last-child {
    margin-bottom: 0;
}

/* Custom scrollbar for related videos */
.max-h-\[500px\]::-webkit-scrollbar {
    width: 6px;
}

.max-h-\[500px\]::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.max-h-\[500px\]::-webkit-scrollbar-thumb {
    background: #3b82f6;
    border-radius: 3px;
}

.max-h-\[500px\]::-webkit-scrollbar-thumb:hover {
    background: #2563eb;
}

/* Smooth transitions for all interactive elements */
a, button, .transition-all {
    transition: all 0.2s ease-in-out;
}

/* Responsive adjustments */
@media (max-width: 640px) {
    .prose {
        font-size: 0.9rem;
        line-height: 1.6;
    }
    
    .max-h-\[500px\] {
        max-height: 400px;
    }
}

/* Animation for the live badge */
@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
@endsection