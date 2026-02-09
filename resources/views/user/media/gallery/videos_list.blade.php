@extends('user.layouts.app')

@section('title', 'Videos')

@php
    use Illuminate\Support\Str;
    $perPage = 20;
    if (request()->has('per_page')) {
        $perPage = request()->input('per_page');
    }
@endphp

@section('content')
<main class="bg-gray-50 min-h-screen">
    {{-- Header dengan warna utama biru --}}
    <div class="bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4 tracking-tight">Videos</h1>
                    <p class="text-blue-100 text-lg">The latest and most popular video collection</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="flex items-center space-x-2">
                        <span class="bg-orange-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                            <i class="fas fa-play-circle mr-2"></i>VIDEO
                        </span>
                        <span class="text-blue-100">{{ $videos->total() }} videos available</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter dan Search Bar --}}
    <div class="sticky top-0 z-10 bg-white shadow-lg border-b">
        <div class="max-w-7xl mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                {{-- Filter Tabs --}}
                <div class="flex flex-wrap items-center gap-2 overflow-x-auto pb-2 md:pb-0">
                    @php $activeAll = request('type', '') === ''; @endphp
                    <a href="{{ route('user.videos') }}" class="inline-flex items-center">
                        <button type="button"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300
                                   {{ $activeAll ? 'bg-blue-600 text-white shadow-lg' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}
                                   flex items-center">
                            <i class="fas fa-globe mr-2"></i> Semua
                        </button>
                    </a>
                    
                    @php $activeVideo = request('type') === 'video'; @endphp
                    <a href="{{ route('user.videos', ['type' => 'video']) }}" class="inline-flex items-center">
                        <button type="button"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300
                                   {{ $activeVideo ? 'bg-green-600 text-white shadow-lg' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}
                                   flex items-center">
                            <i class="fas fa-play-circle mr-2"></i> Video
                        </button>
                    </a>
                    
                    @php $activeLive = request('type') === 'live'; @endphp
                    <a href="{{ route('user.videos', ['type' => 'live']) }}" class="inline-flex items-center">
                        <button type="button"
                            class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-300
                                   {{ $activeLive ? 'bg-orange-500 text-white shadow-lg' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}
                                   flex items-center">
                            <i class="fas fa-broadcast-tower mr-2"></i> Live
                        </button>
                    </a>
                </div>

                {{-- Search Bar dengan Button Submit --}}
                <form method="GET" action="{{ route('user.videos') }}" 
                      class="flex-1 max-w-md ml-auto">
                    <div class="flex items-center">
                        <div class="relative flex-1">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari video..."
                                class="w-full px-4 py-2 pl-12 pr-4 bg-gray-100 border border-gray-300 rounded-l-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-300"
                            />
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">
                                <i class="fas fa-search"></i>
                            </div>
                        </div>
                        <button type="submit"
                                class="bg-blue-600 text-white px-5 py-2 rounded-r-full hover:bg-blue-700 transition-colors duration-300 border border-blue-600 border-l-0">
                            <i class="fas fa-search"></i>
                            <span class="sr-only">Cari</span>
                        </button>
                        @if(request('search'))
                            <a href="{{ route('user.videos') }}" 
                               class="ml-2 text-gray-500 hover:text-blue-600 p-2">
                                <i class="fas fa-times"></i>
                                <span class="sr-only">Hapus pencarian</span>
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Konten Video Grid --}}
    <div class="max-w-7xl mx-auto px-4 py-8">
        {{-- Info hasil pencarian --}}
        @if(request('search'))
            <div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                <div class="flex items-center justify-between">
                    <p class="text-blue-800">
                        Hasil pencarian untuk: <span class="font-semibold">"{{ request('search') }}"</span>
                    </p>
                    <a href="{{ route('user.videos') }}" 
                       class="text-orange-600 hover:text-orange-800 flex items-center">
                        <i class="fas fa-times mr-1"></i> Hapus filter
                    </a>
                </div>
            </div>
        @endif

        {{-- Grid Video --}}
        @if($videos->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($videos as $video)
                    <div class="group cursor-pointer bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
                        <div class="relative overflow-hidden bg-gray-900">
                            {{-- Thumbnail dengan aspect ratio 16:9 --}}
                            <a href="{{ route('user.videos.detail', $video->slug) }}">
                                <div class="relative w-full pt-[56.25%]">
                                    <img
                                        src="{{ asset($video->thumbnail) }}"
                                        alt="{{ $video->title }}"
                                        class="absolute top-0 left-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                        loading="lazy"
                                    />
                                    
                                    {{-- Overlay --}}
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    
                                    {{-- Durasi video (jika ada) --}}
                                    @if($video->duration)
                                        <div class="absolute bottom-2 right-2 bg-black/80 text-white text-xs px-2 py-1 rounded">
                                            {{ $video->duration }}
                                        </div>
                                    @endif
                                    
                                    {{-- Badge sesuai type --}}
                                    @if($video->type === 'live')
                                        <div class="absolute top-2 left-2 bg-orange-500 text-white text-xs px-2 py-1 rounded-md font-semibold flex items-center">
                                            <i class="fas fa-broadcast-tower mr-1"></i> LIVE
                                        </div>
                                    @elseif($video->type === 'video')
                                        <div class="absolute top-2 left-2 bg-green-600 text-white text-xs px-2 py-1 rounded-md font-semibold flex items-center">
                                            <i class="fas fa-play mr-1"></i> VIDEO
                                        </div>
                                    @endif
                                    
                                    {{-- Play button overlay --}}
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="bg-blue-600 text-white p-4 rounded-full transform scale-0 group-hover:scale-100 transition-transform duration-300">
                                            <i class="fas fa-play text-xl"></i>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        
                        {{-- Video info --}}
                        <div class="p-4">
                            <div class="flex">
                                {{-- Channel avatar (optional) --}}
                                @if(isset($video->channel_avatar))
                                    <div class="flex-shrink-0 mr-3">
                                        <img src="{{ asset($video->channel_avatar) }}" 
                                             alt="Channel" 
                                             class="w-9 h-9 rounded-full object-cover border-2 border-blue-200">
                                    </div>
                                @endif
                                
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('user.videos.detail', $video->slug) }}">
                                        <h3 class="font-semibold text-gray-900 group-hover:text-blue-600 transition-colors duration-300 line-clamp-2 mb-1">
                                            {{ Str::limit($video->title, 60) }}
                                        </h3>
                                    </a>
                                    
                                    <div class="flex flex-wrap items-center text-sm text-gray-600 space-x-2">
                                        @if(isset($video->channel_name))
                                            <span class="hover:text-gray-900 transition-colors">{{ $video->channel_name }}</span>
                                            <span class="text-gray-400">•</span>
                                        @endif
                                        
                                        <span>{{ $video->created_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    {{-- Tags (jika ada) --}}
                                    @if($video->tags && count($video->tags) > 0)
                                        <div class="mt-2 flex flex-wrap gap-1">
                                            @foreach(array_slice($video->tags, 0, 2) as $tag)
                                                <span class="text-xs bg-blue-50 text-blue-700 px-2 py-1 rounded border border-blue-100">
                                                    {{ $tag }}
                                                </span>
                                            @endforeach
                                            @if(count($video->tags) > 2)
                                                <span class="text-xs text-gray-500">+{{ count($video->tags) - 2 }}</span>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty state --}}
            <div class="text-center py-16">
                <div class="mx-auto w-24 h-24 text-gray-300 mb-6">
                    <i class="fas fa-video-slash text-6xl"></i>
                </div>
                <h3 class="text-2xl font-semibold text-gray-700 mb-2">Tidak ada video ditemukan</h3>
                <p class="text-gray-500 max-w-md mx-auto mb-6">
                    @if(request('search'))
                        Coba gunakan kata kunci lain atau hapus filter pencarian.
                    @else
                        Belum ada video yang tersedia. Silakan kembali lagi nanti.
                    @endif
                </p>
                @if(request('search'))
                    <a href="{{ route('user.videos') }}" 
                       class="inline-flex items-center px-5 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors duration-300">
                        <i class="fas fa-times mr-2"></i> Hapus Pencarian
                    </a>
                @endif
            </div>
        @endif

        {{-- Pagination dengan warna orange --}}
        @if($videos->hasPages() && $videos->count() > 0)
            <div class="mt-12 border-t border-gray-200 pt-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <div class="text-gray-600 mb-4 md:mb-0">
                        Menampilkan <span class="font-semibold">{{ $videos->firstItem() }}</span> 
                        sampai <span class="font-semibold">{{ $videos->lastItem() }}</span> 
                        dari <span class="font-semibold">{{ $videos->total() }}</span> video
                    </div>
                    <div class="w-full md:w-auto">
                        @php
                            $pagination = $videos->withQueryString();
                        @endphp
                        
                        <nav class="flex items-center space-x-1">
                            {{-- Previous Page Link --}}
                            @if ($pagination->onFirstPage())
                                <span class="px-3 py-2 text-gray-400 border border-gray-300 rounded-md cursor-not-allowed">
                                    <i class="fas fa-chevron-left"></i>
                                </span>
                            @else
                                <a href="{{ $pagination->previousPageUrl() }}" 
                                   class="px-3 py-2 text-blue-600 border border-blue-300 rounded-md hover:bg-blue-50 transition-colors duration-300">
                                    <i class="fas fa-chevron-left"></i>
                                </a>
                            @endif

                            {{-- Pagination Elements --}}
                            @foreach ($pagination->getUrlRange(1, $pagination->lastPage()) as $page => $url)
                                @if ($page == $pagination->currentPage())
                                    <span class="px-4 py-2 bg-orange-500 text-white border border-orange-500 rounded-md font-medium">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}" 
                                       class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-300">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach

                            {{-- Next Page Link --}}
                            @if ($pagination->hasMorePages())
                                <a href="{{ $pagination->nextPageUrl() }}" 
                                   class="px-3 py-2 text-blue-600 border border-blue-300 rounded-md hover:bg-blue-50 transition-colors duration-300">
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @else
                                <span class="px-3 py-2 text-gray-400 border border-gray-300 rounded-md cursor-not-allowed">
                                    <i class="fas fa-chevron-right"></i>
                                </span>
                            @endif
                        </nav>
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- Floating Action Button dengan warna hijau --}}
    <button class="fixed bottom-6 right-6 md:hidden bg-green-600 text-white p-4 rounded-full shadow-lg hover:bg-green-700 transition-colors duration-300 z-20">
        <i class="fas fa-play text-xl"></i>
    </button>
</main>

{{-- Styles untuk line clamp --}}
<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

/* Custom scrollbar dengan warna tema */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #3b82f6; /* blue-500 */
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #2563eb; /* blue-600 */
}
</style>
@endsection