@extends('user.layouts.app')

@section('title', 'Dashboard - HSBL Riau Pos')

@php
    use Illuminate\Support\Str;
    use Carbon\Carbon;
    
    // Ambil 4 berita terbaru untuk ditampilkan di dashboard
    $latestNews = \App\Models\News::where('status', 'view')
                    ->latest()
                    ->take(4)
                    ->get();
    
    // Ambil 4 schedule terbaru
    $latestSchedules = \App\Models\MatchData::where(function($query) {
                        $query->where('status', 'active')
                              ->orWhere('status', 'published')
                              ->orWhere('status', 'publish')
                              ->orWhereNull('status');
                    })
                    ->whereNotNull('layout_image')
                    ->latest('upload_date')
                    ->take(4)
                    ->get();
    
    // Format schedules data
    $latestSchedules->transform(function ($schedule) {
        // Format path gambar
        if ($schedule->layout_image) {
            if (str_starts_with($schedule->layout_image, 'http')) {
                $schedule->image_url = $schedule->layout_image;
            } elseif (!str_contains($schedule->layout_image, '/')) {
                $schedule->image_url = asset('images/schedule/' . $schedule->layout_image);
            } else {
                $schedule->image_url = asset($schedule->layout_image);
            }
        } else {
            $schedule->image_url = asset('images/default-schedule.jpg');
        }
        
        // Format tanggal
        if ($schedule->upload_date) {
            try {
                $schedule->formatted_date = Carbon::parse($schedule->upload_date)
                    ->locale('id')
                    ->translatedFormat('j F Y');
            } catch (\Exception $e) {
                $schedule->formatted_date = $schedule->upload_date;
            }
        } else {
            $schedule->formatted_date = 'Date TBD';
        }
        
        return $schedule;
    });
    
    // Ambil 4 hasil pertandingan terbaru
    $latestResults = \App\Models\MatchResult::whereIn('status', ['completed', 'done', 'publish', 'live'])
                    ->latest('match_date')
                    ->take(4)
                    ->get();
    
    // Format results data sesuai dengan ResultController
    $latestResults->transform(function ($result) {
        // Format tanggal
        if ($result->match_date) {
            try {
                $result->match_date_formatted = Carbon::parse($result->match_date)
                    ->locale('id')
                    ->translatedFormat('j F Y');
                $result->match_time = Carbon::parse($result->match_date)->format('H:i');
            } catch (\Exception $e) {
                $result->match_date_formatted = $result->match_date;
                $result->match_time = '00:00';
            }
        } else {
            $result->match_date_formatted = 'Date TBD';
            $result->match_time = '00:00';
        }
        
        // Format logo - SEMUA JADI IKON SEKOLAH (tidak pakai logo asli)
        $result->team1_logo = null; // Set null agar menggunakan ikon default
        $result->team2_logo = null; // Set null agar menggunakan ikon default
        
        // Score
        $result->score_1 = isset($result->score_1) ? (int) $result->score_1 : 0;
        $result->score_2 = isset($result->score_2) ? (int) $result->score_2 : 0;
        
        // Has scoresheet
        $result->has_scoresheet = !empty($result->scoresheet);
        
        return $result;
    });
    
    // Fungsi untuk ekstrak YouTube ID
    function extractYouTubeId($url) {
        if (empty($url)) return null;
        
        $patterns = [
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/i',
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/i',
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/i',
            '/youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/i',
            '/m\.youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/i',
            '/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }
        
        return null;
    }
    
    // Ambil 6 video terbaru
    $latestVideos = \App\Models\Video::where('status', 'view')
                    ->latest()
                    ->take(6)
                    ->get();
    
    // Format video data
    $latestVideos->transform(function ($video) {
        // Extract YouTube info
        $video->is_youtube = false;
        $video->youtube_id = null;
        
        if (!empty($video->youtube_link)) {
            $videoId = extractYouTubeId($video->youtube_link);
            if ($videoId) {
                $video->is_youtube = true;
                $video->youtube_id = $videoId;
                $video->thumbnail_url = "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg";
            }
        }
        
        // If not YouTube or no thumbnail, use local thumbnail
        if (!$video->is_youtube && !empty($video->thumbnail)) {
            $video->thumbnail_url = asset($video->thumbnail);
        } elseif (!$video->is_youtube) {
            $video->thumbnail_url = asset('images/default-video.jpg');
        }
        
        // Get view count
        $video->view_count = 0;
        if (\Illuminate\Support\Facades\Schema::hasColumn('media_videos', 'views')) {
            $video->view_count = $video->views ?? 0;
        } elseif (\Illuminate\Support\Facades\Schema::hasColumn('media_videos', 'view_count')) {
            $video->view_count = $video->view_count ?? 0;
        }
        
        // Format duration if available
        $video->duration_formatted = $video->duration ?? null;
        if ($video->duration && is_numeric($video->duration)) {
            $minutes = floor($video->duration / 60);
            $seconds = $video->duration % 60;
            $video->duration_formatted = sprintf("%d:%02d", $minutes, $seconds);
        }
        
        return $video;
    });
    
    // ========== NEW: AMBIL FOTO TERBARU UNTUK GALLERY ==========
    $latestPhotos = \App\Models\MediaGallery::where('status', 'published')
                    ->latest()
                    ->take(8)
                    ->get();
    
    // Format photos data dengan gambar random basketball
    $latestPhotos->transform(function ($photo) {
        // Generate random basketball-themed image URL based on school name
        $schoolHash = crc32($photo->school_name ?? 'HSBL');
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
        
        $photo->cover_image = $basketballImages[$imageIndex - 1];
        
        // Format file size
        $photo->file_size_formatted = '';
        if ($photo->file_size) {
            if ($photo->file_size < 1024 * 1024) {
                $photo->file_size_formatted = round($photo->file_size / 1024, 1) . ' KB';
            } else {
                $photo->file_size_formatted = round($photo->file_size / (1024 * 1024), 1) . ' MB';
            }
        }
        
        // Format date
        $photo->formatted_date = $photo->created_at->format('d M Y');
        
        return $photo;
    });
    // ========== END NEW: AMBIL FOTO TERBARU UNTUK GALLERY ==========
    
@endphp

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    {{-- Hero Welcome Section --}}
    <div class="mb-8 md:mb-12 animate-fadeIn">
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-blue-500 to-cyan-500 shadow-xl">
            <div class="absolute inset-0 bg-black/5"></div>
            {{-- Animated Background Elements --}}
            <div class="absolute top-0 left-0 w-64 h-64 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2 animate-pulse-slow"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/5 rounded-full translate-x-1/2 translate-y-1/2 animate-pulse-slow animation-delay-2000"></div>
            
            <div class="relative px-6 py-8 md:px-10 md:py-12">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="md:w-2/3 mb-6 md:mb-0 animate-slideInLeft">
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                                <i class="fas fa-basketball-ball text-white text-xl"></i>
                            </div>
                            <span class="text-white/90 text-sm font-medium tracking-wider">HONDA STUDENT BASKETBALL LEAGUE</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-3 leading-tight">
                            <span class="animate-pulse-slow inline-block">Welcome to project alumni</span><br>
                            <span class="text-yellow-300 animate-bounce-slow inline-block">HSBL Riau Pos</span>
                        </h1>
                        <p class="text-lg text-blue-100 mb-6 max-w-2xl animate-pulse-slow animation-delay-1000">
                            The biggest student basketball competition in Riau, where young talents showcase their skills and fighting spirit.
                        </p>
                    </div>
                    <div class="md:w-1/3 flex justify-center animate-slideInRight">
                        <div class="relative">
                            <div class="w-40 h-40 md:w-48 md:h-48 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-sm border-4 border-white/20">
                                <div class="w-32 h-32 md:w-40 md:h-40 bg-white/20 rounded-full flex items-center justify-center">
                                    <img src="{{ asset('uploads/logo/hsbl.png') }}" 
                                         alt="HSBL Logo" 
                                         class="w-24 h-24 md:w-32 md:h-32">
                                </div>
                            </div>
                            <div class="absolute -top-2 -right-2 w-16 h-16 bg-yellow-400 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-trophy text-white text-xl"></i>
                            </div>
                            <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-red-500 rounded-full flex items-center justify-center shadow-lg">
                                <i class="fas fa-basketball-ball text-white text-lg"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- About HSBL Section --}}
    <div class="bg-white rounded-xl shadow-lg mb-8 overflow-hidden transform transition-all duration-500 hover:shadow-2xl hover:-translate-y-1 animate-fadeInUp">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50 to-blue-50">
            <h2 class="font-semibold text-lg text-gray-800 flex items-center space-x-2">
                <i class="fas fa-landmark text-indigo-500 animate-pulse"></i>
                <span>About Riau Pos - Honda HSBL</span>
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="relative group animate-fadeInLeft">
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-500 to-blue-500 opacity-0 group-hover:opacity-10 rounded-lg transition-opacity duration-300"></div>
                    <h3 class="font-semibold text-gray-800 text-lg mb-3 flex items-center">
                        <span class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center mr-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-history text-indigo-500 text-sm"></i>
                        </span>
                        History
                    </h3>
                    <div class="prose max-w-none pl-10">
                        <p class="text-gray-700 mb-3 leading-relaxed">
                            <strong class="text-indigo-600">Riau Pos Honda Student Basketball League (HSBL)</strong> is a basketball and dance competition for high school students and equivalent levels in Riau Province, focusing on youth development and talent scouting.
                        </p>
                        <p class="text-gray-700 leading-relaxed">
                            First organized in 2008 under the name Student Basketball League (SBL) and renamed to Riau Pos Honda HSBL since 2010, this annual event was temporarily halted in 2019 due to the COVID-19 pandemic and resumed in 2024.
                        </p>
                    </div>
                </div>
                
                <div class="relative group animate-fadeInRight">
                    <div class="absolute inset-0 bg-gradient-to-r from-green-500 to-teal-500 opacity-0 group-hover:opacity-10 rounded-lg transition-opacity duration-300"></div>
                    <h3 class="font-semibold text-gray-800 text-lg mb-3 flex items-center">
                        <span class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-2 group-hover:scale-110 transition-transform">
                            <i class="fas fa-bullseye text-green-500 text-sm"></i>
                        </span>
                        Objectives
                    </h3>
                    <ul class="space-y-2 pl-10">
                        <li class="flex items-start group/item">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2 group-hover/item:scale-110 transition-transform"></i>
                            <span class="text-gray-700">Develop basketball talents among high school students</span>
                        </li>
                        <li class="flex items-start group/item">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2 group-hover/item:scale-110 transition-transform"></i>
                            <span class="text-gray-700">Promote healthy competition and sportsmanship</span>
                        </li>
                        <li class="flex items-start group/item">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2 group-hover/item:scale-110 transition-transform"></i>
                            <span class="text-gray-700">Provide platform for youth development</span>
                        </li>
                        <li class="flex items-start group/item">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2 group-hover/item:scale-110 transition-transform"></i>
                            <span class="text-gray-700">Strengthen sports culture in Riau Province</span>
                        </li>
                        <li class="flex items-start group/item">
                            <i class="fas fa-check-circle text-green-500 mt-1 mr-2 group-hover/item:scale-110 transition-transform"></i>
                            <span class="text-gray-700">Discover and nurture future basketball stars</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- Latest News Section -- LAYOUT BARU: 1 Berita Besar di Atas, 3 di Bawah --}}
    @if($latestNews->count() > 0)
    <div class="mb-8 animate-fadeInUp">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="bg-blue-600 w-1.5 h-6 rounded-full mr-3 animate-pulse"></span>
                Latest News
            </h2>
            <a href="{{ route('user.news.index') }}" class="group flex items-center text-blue-600 hover:text-blue-700 font-medium">
                <span>View All</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Berita Utama (Featured) - Paling atas memenuhi lebar --}}
            @if($latestNews->count() >= 1)
            <div class="lg:col-span-2 group animate-scaleIn">
                <a href="{{ route('user.news.show', $latestNews[0]->id) }}" class="block relative h-full">
                    <div class="relative h-96 rounded-xl overflow-hidden">
                        @if($latestNews[0]->image && file_exists(public_path($latestNews[0]->image)))
                            <img src="{{ asset($latestNews[0]->image) }}" alt="{{ $latestNews[0]->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center">
                                <i class="fas fa-newspaper text-6xl text-white/30"></i>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
                        
                        {{-- Content Overlay --}}
                        <div class="absolute bottom-0 left-0 right-0 p-8 text-white">
                            @if($latestNews[0]->series)
                                <span class="inline-block px-3 py-1 bg-blue-600 rounded-full text-xs font-semibold mb-3 animate-pulse">
                                    {{ $latestNews[0]->series }}
                                </span>
                            @endif
                            <h3 class="text-3xl md:text-4xl font-bold mb-3 line-clamp-2">{{ $latestNews[0]->title }}</h3>
                            <div class="flex items-center text-sm text-gray-200 mb-4">
                                <i class="far fa-calendar-alt mr-2"></i>
                                {{ $latestNews[0]->created_at->format('d F Y') }}
                            </div>
                            <p class="text-gray-200 line-clamp-3 mb-6 max-w-3xl">{{ Str::limit(strip_tags($latestNews[0]->content), 200) }}</p>
                            <span class="inline-flex items-center text-white bg-blue-600 px-6 py-3 rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors transform hover:scale-105">
                                Read Full Article <i class="fas fa-arrow-right ml-2"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            {{-- 3 Berita di Bawah --}}
            @for($i = 1; $i < min(4, $latestNews->count()); $i++)
            <div class="group animate-fadeInUp" style="animation-delay: {{ ($i - 1) * 0.1 }}s">
                <a href="{{ route('user.news.show', $latestNews[$i]->id) }}" class="block bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="relative h-48 overflow-hidden">
                        @if($latestNews[$i]->image && file_exists(public_path($latestNews[$i]->image)))
                            <img src="{{ asset($latestNews[$i]->image) }}" alt="{{ $latestNews[$i]->title }}" 
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <i class="fas fa-newspaper text-4xl text-gray-400"></i>
                            </div>
                        @endif
                        @if($latestNews[$i]->series)
                            <span class="absolute top-3 left-3 px-3 py-1.5 bg-blue-600 text-white text-xs rounded-full shadow-lg">
                                {{ Str::limit($latestNews[$i]->series, 15) }}
                            </span>
                        @endif
                    </div>
                    <div class="p-5">
                        <div class="flex items-center text-xs text-gray-500 mb-3">
                            <i class="far fa-calendar-alt mr-2"></i>
                            {{ $latestNews[$i]->created_at->format('d M Y') }}
                        </div>
                        <h3 class="font-bold text-gray-800 mb-3 line-clamp-2 group-hover:text-blue-600 transition-colors text-lg">
                            {{ $latestNews[$i]->title }}
                        </h3>
                        <span class="text-blue-600 text-sm font-medium inline-flex items-center group-hover:translate-x-2 transition-transform">
                            Read more <i class="fas fa-arrow-right ml-2"></i>
                        </span>
                    </div>
                </a>
            </div>
            @endfor
        </div>
    </div>
    @endif

    {{-- Schedules Section -- TANPA TEKS, HANYA GAMBAR + IKON KECIL SAAT HOVER --}}
    @if($latestSchedules->count() > 0)
    <div class="mb-8 animate-fadeInUp">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="bg-green-600 w-1.5 h-6 rounded-full mr-3 animate-pulse"></span>
                Schedules
            </h2>
            <a href="{{ route('user.schedule_result') }}?tab=schedules" class="group flex items-center text-green-600 hover:text-green-700 font-medium">
                <span>View All</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @foreach($latestSchedules as $index => $schedule)
                <div class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 animate-scaleIn" style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="aspect-w-3 aspect-h-4">
                        <img src="{{ $schedule->image_url }}" alt="Schedule" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        
                        {{-- Hover Icons - HANYA IKON KECIL, TANPA BACKGROUND --}}
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            <div class="flex space-x-4">
                                <a href="{{ route('user.schedule_result') }}?tab=schedules" 
                                   class="text-white hover:text-green-300 transition-colors duration-200">
                                    <i class="fas fa-calendar-alt text-2xl drop-shadow-md"></i>
                                </a>
                                <button onclick="openImageModal('{{ $schedule->image_url }}', '{{ $schedule->main_title ?? 'Schedule' }}')"
                                        class="text-white hover:text-blue-300 transition-colors duration-200">
                                    <i class="fas fa-expand text-2xl drop-shadow-md"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Match Results Section -- LOGO SEMUA JADI IKON SEKOLAH --}}
    @if($latestResults->count() > 0)
    <div class="mb-8 animate-fadeInUp">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="bg-orange-600 w-1.5 h-6 rounded-full mr-3 animate-pulse"></span>
                Match Results
            </h2>
            <a href="{{ route('user.schedule_result') }}?tab=results" class="group flex items-center text-orange-600 hover:text-orange-700 font-medium">
                <span>View All</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($latestResults as $index => $result)
            <div class="bg-white rounded-xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 border border-gray-100 transform hover:-translate-y-1 animate-fadeInLeft" style="animation-delay: {{ $index * 0.1 }}s">
                <div class="p-4 bg-gradient-to-r from-orange-50 to-amber-50">
                    {{-- Header dengan Series dan Tanggal --}}
                    <div class="flex justify-between items-center mb-3">
                        <div class="flex items-center gap-2">
                            @if($result->series)
                                <span class="text-xs font-semibold text-orange-600 bg-orange-100 px-3 py-1.5 rounded-full">
                                    <i class="fas fa-layer-group mr-1 text-xs"></i>
                                    {{ $result->series }}
                                </span>
                            @endif
                            @if($result->competition_type)
                                <span class="text-xs font-semibold text-blue-600 bg-blue-100 px-3 py-1.5 rounded-full">
                                    <i class="fas fa-tag mr-1 text-xs"></i>
                                    {{ $result->competition_type }}
                                </span>
                            @endif
                        </div>
                        <span class="text-xs font-medium text-gray-600 bg-white/80 px-3 py-1.5 rounded-full shadow-sm">
                            <i class="far fa-calendar-alt mr-1 text-orange-500"></i>
                            {{ $result->match_date_formatted }}
                        </span>
                    </div>
                    
                    {{-- Teams vs Score -- DENGAN LOGO SEKOLAH (ikon saja) --}}
                    <div class="flex items-center justify-between gap-3 mb-3">
                        {{-- Team 1 --}}
                        <div class="flex-1 text-center">
                            <div class="relative mb-2 group/team">
                                <div class="w-14 h-14 mx-auto flex items-center justify-center bg-gradient-to-br from-blue-500 to-blue-600 rounded-full p-1.5 shadow-md border-2 border-orange-200 transform group-hover/team:scale-110 transition-transform duration-300">
                                    <div class="w-full h-full bg-white rounded-full flex items-center justify-center">
                                        <i class="fas fa-school text-blue-600 text-xl"></i>
                                    </div>
                                </div>
                                {{-- Hover effect --}}
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-white opacity-0 group-hover/team:opacity-100 transition-opacity"></div>
                            </div>
                            <div class="font-bold text-sm text-gray-800 truncate max-w-[120px] mx-auto" title="{{ $result->team1_name ?? 'Team A' }}">
                                {{ Str::limit($result->team1_name ?? 'Team A', 15) }}
                            </div>
                        </div>
                        
                        {{-- Score --}}
                        <div class="flex-shrink-0">
                            <div class="bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold text-xl px-4 py-2 rounded-xl shadow-md transform hover:scale-105 transition-transform">
                                {{ $result->score_1 }} - {{ $result->score_2 }}
                            </div>
                            <div class="text-center mt-2">
                                <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Final
                                </span>
                            </div>
                        </div>
                        
                        {{-- Team 2 --}}
                        <div class="flex-1 text-center">
                            <div class="relative mb-2 group/team">
                                <div class="w-14 h-14 mx-auto flex items-center justify-center bg-gradient-to-br from-purple-500 to-purple-600 rounded-full p-1.5 shadow-md border-2 border-orange-200 transform group-hover/team:scale-110 transition-transform duration-300">
                                    <div class="w-full h-full bg-white rounded-full flex items-center justify-center">
                                        <i class="fas fa-school text-purple-600 text-xl"></i>
                                    </div>
                                </div>
                                {{-- Hover effect --}}
                                <div class="absolute -top-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-white opacity-0 group-hover/team:opacity-100 transition-opacity"></div>
                            </div>
                            <div class="font-bold text-sm text-gray-800 truncate max-w-[120px] mx-auto" title="{{ $result->team2_name ?? 'Team B' }}">
                                {{ Str::limit($result->team2_name ?? 'Team B', 15) }}
                            </div>
                        </div>
                    </div>
                    
                    {{-- Phase & Scoresheet --}}
                    <div class="flex items-center justify-between pt-2 border-t border-orange-200">
                        <div class="flex items-center gap-2">
                            @if($result->phase)
                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">
                                    <i class="fas fa-flag mr-1"></i>
                                    {{ $result->phase }}
                                </span>
                            @endif
                            @if($result->season)
                                <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full">
                                    <i class="fas fa-calendar-star mr-1"></i>
                                    {{ $result->season }}
                                </span>
                            @endif
                        </div>
                        
                        @if($result->has_scoresheet)
                        <a href="{{ route('user.results.download.scoresheet', $result->id) }}" 
                           class="inline-flex items-center text-xs text-green-600 hover:text-white px-3 py-1.5 bg-green-100 hover:bg-green-600 rounded-full transition-all duration-300 group transform hover:scale-105">
                            <i class="fas fa-download mr-1 group-hover:text-white"></i>
                            <span class="group-hover:text-white">Scoresheet</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Latest Videos Section --}}
    @if($latestVideos->count() > 0)
    <div class="mb-8 animate-fadeInUp">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="bg-red-600 w-1.5 h-6 rounded-full mr-3 animate-pulse"></span>
                Latest Videos
            </h2>
            <a href="{{ route('user.videos') }}" class="group flex items-center text-red-600 hover:text-red-700 font-medium">
                <span>View All</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            @foreach($latestVideos as $index => $video)
                <div class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 animate-scaleIn {{ $index == 0 ? 'col-span-2 row-span-2' : '' }}" style="animation-delay: {{ $index * 0.1 }}s">
                    <div class="{{ $index == 0 ? 'aspect-w-16 aspect-h-9' : 'aspect-w-1 aspect-h-1' }}">
                        <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        {{-- Badges --}}
                        <div class="absolute top-2 left-2 flex gap-1">
                            @if($video->is_youtube)
                                <span class="bg-red-600 text-white text-xs px-2 py-1 rounded-lg transform transition-transform group-hover:scale-110">
                                    <i class="fab fa-youtube"></i>
                                </span>
                            @endif
                            @if($video->type === 'live')
                                <span class="bg-red-600 text-white text-xs px-2 py-1 rounded-lg animate-pulse">
                                    <i class="fas fa-broadcast-tower"></i> LIVE
                                </span>
                            @endif
                        </div>
                        
                        {{-- Duration --}}
                        @if($video->duration_formatted)
                        <div class="absolute bottom-2 right-2">
                            <span class="bg-black/70 text-white text-xs px-2 py-1 rounded-lg backdrop-blur-sm">
                                <i class="far fa-clock"></i> {{ $video->duration_formatted }}
                            </span>
                        </div>
                        @endif
                        
                        {{-- Play Button Overlay --}}
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-90 group-hover:scale-100">
                            <div class="w-12 h-12 bg-white/90 rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform">
                                <i class="fas fa-play text-red-600 ml-1"></i>
                            </div>
                        </div>
                        
                        {{-- Title for featured video --}}
                        @if($index == 0)
                        <div class="absolute bottom-0 left-0 right-0 p-4 text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <h3 class="font-bold text-sm line-clamp-2">{{ $video->title }}</h3>
                            <div class="flex items-center text-xs text-gray-200 mt-1">
                                <i class="far fa-eye mr-1"></i>
                                {{ number_format($video->view_count) }} views
                            </div>
                        </div>
                        @endif
                    </div>
                    
                    {{-- Title for non-featured videos --}}
                    @if($index != 0)
                    <div class="absolute bottom-0 left-0 right-0 p-2 text-white bg-gradient-to-t from-black/90 to-transparent transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        <h3 class="text-xs font-semibold line-clamp-2">{{ $video->title }}</h3>
                    </div>
                    @endif
                    
                    <a href="{{ route('user.videos.detail', $video->slug ?? $video->id) }}" class="absolute inset-0"></a>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- ========== NEW: LATEST PHOTOS GALLERY SECTION ========== --}}
    @if($latestPhotos->count() > 0)
    <div class="mb-8 animate-fadeInUp">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <span class="bg-purple-600 w-1.5 h-6 rounded-full mr-3 animate-pulse"></span>
                Latest Photos
            </h2>
            <a href="{{ route('user.gallery.photos.index') }}" class="group flex items-center text-purple-600 hover:text-purple-700 font-medium">
                <span>View All</span>
                <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($latestPhotos as $index => $photo)
                <div class="group relative overflow-hidden rounded-xl shadow-md hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 animate-scaleIn" style="animation-delay: {{ $index * 0.1 }}s">
                    {{-- Photo Container dengan aspect ratio 4:3 --}}
                    <div class="aspect-w-4 aspect-h-3 relative">
                        {{-- Cover Image --}}
                        <img src="{{ $photo->cover_image }}" 
                             alt="{{ $photo->school_name }} Gallery" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        
                        {{-- Overlay Gradient --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        {{-- Badge Atas --}}
                        <div class="absolute top-2 left-2">
                            <span class="px-2 py-1 bg-purple-600 text-white text-xs font-semibold rounded-lg shadow-lg transform -translate-y-1 group-hover:translate-y-0 transition-transform duration-300">
                                <i class="fas fa-camera mr-1"></i>
                                {{ $photo->file_type ? strtoupper(pathinfo($photo->original_filename, PATHINFO_EXTENSION)) : 'PHOTO' }}
                            </span>
                        </div>
                        
                        {{-- Download Count Badge --}}
                        <div class="absolute top-2 right-2">
                            <span class="bg-black/70 text-white px-2 py-1 rounded-lg text-xs backdrop-blur-sm">
                                <i class="fas fa-download mr-1"></i>
                                {{ $photo->download_count }}
                            </span>
                        </div>
                        
                        {{-- Content Overlay (muncul saat hover) --}}
                        <div class="absolute bottom-0 left-0 right-0 p-4 text-white transform translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                            <h3 class="font-bold text-sm mb-1 line-clamp-1">{{ $photo->school_name }}</h3>
                            <div class="flex items-center text-xs text-gray-200 mb-2">
                                <i class="fas fa-trophy mr-1"></i>
                                <span class="line-clamp-1">{{ $photo->competition }}</span>
                            </div>
                            
                            {{-- File Info --}}
                            <div class="flex items-center justify-between text-xs text-gray-300 mb-2">
                                <span><i class="far fa-calendar-alt mr-1"></i>{{ $photo->formatted_date }}</span>
                                @if($photo->file_size_formatted)
                                    <span><i class="fas fa-weight-hanging mr-1"></i>{{ $photo->file_size_formatted }}</span>
                                @endif
                            </div>
                            
                            {{-- Action Buttons --}}
                            <div class="flex gap-2">
                                <button onclick="openPhotoModal('{{ $photo->cover_image }}', '{{ $photo->school_name }} - {{ $photo->competition }}')"
                                        class="bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white text-xs font-medium py-2 px-3 rounded-lg transition-colors duration-300">
                                    <i class="fas fa-expand"></i>
                                </button>
                            </div>
                        </div>
                        
                        {{-- Quick View Icon (muncul di tengah saat hover) --}}
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300 transform scale-90 group-hover:scale-100 pointer-events-none">
                            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center border-2 border-white/50">
                                <i class="fas fa-eye text-white text-lg"></i>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Link ke halaman gallery --}}
                    <a href="{{ route('user.gallery.photos.index') }}" class="absolute inset-0"></a>
                </div>
            @endforeach
        </div>
        
        {{-- Mini Statistics untuk Photos --}}
        <div class="mt-4 flex items-center justify-end text-xs text-gray-500">
            <span class="flex items-center mr-4">
                <i class="fas fa-images text-purple-500 mr-1"></i>
                {{ $latestPhotos->count() }} photos
            </span>
            <span class="flex items-center">
                <i class="fas fa-download text-green-500 mr-1"></i>
                {{ $latestPhotos->sum('download_count') }} total downloads
            </span>
        </div>
    </div>
    @endif
    {{-- ========== END NEW: LATEST PHOTOS GALLERY SECTION ========== --}}

    {{-- Development Team Section --}}
    <div class="bg-white rounded-xl shadow-lg overflow-hidden transform transition-all duration-500 hover:shadow-2xl hover:-translate-y-1 animate-fadeInUp">
        <div class="px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-purple-50 to-pink-50">
            <h2 class="font-semibold text-lg text-gray-800 flex items-center space-x-2">
                <i class="fas fa-users text-purple-500 animate-pulse"></i>
                <span>Development Team</span>
            </h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Developer 1 --}}
                <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl border border-blue-100 transform transition-all duration-500 hover:scale-[1.02] hover:shadow-xl animate-fadeInLeft">
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                        <div class="flex-shrink-0">
                            <div class="relative">
                                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg transform transition-all duration-500 hover:rotate-6 hover:scale-110">
                                    <img src="{{ asset('images/Developer/Mutia Rizki.jpeg') }}" 
                                         alt="Mutia Rizkianti" 
                                         class="w-full h-full object-cover"
                                         onerror="this.src='https://ui-avatars.com/api/?name=Mutia+Rizkianti&background=3b82f6&color=fff&size=128'">
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-blue-500 rounded-full border-2 border-white flex items-center justify-center animate-pulse">
                                    <i class="fas fa-code text-white text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800 text-lg mb-1 group-hover:text-blue-600 transition-colors">Mutia Rizkianti</h3>
                            <p class="text-sm text-gray-600 mb-3">Computer Science Graduate 2025</p>
                            <div class="mb-3">
                                <span class="inline-block bg-blue-600 text-white text-xs px-3 py-1 rounded-full font-medium shadow-md transform hover:scale-105 transition-transform">
                                    <i class="fas fa-laptop-code mr-1"></i> Full-Stack Developer
                                </span>
                            </div>
                            <p class="text-sm text-gray-700 mb-2 leading-relaxed">
                                Experienced as a programmer at Digital Innovation Hub research, HSBL website development, internship at Riau Pos Event Organizer Division, and freelance layout designer and editor.
                            </p>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                Specializes in integrated system development with focus on structured architecture and clean UI design.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Developer 2 --}}
                <div class="p-6 bg-gradient-to-r from-purple-50 to-pink-50 rounded-xl border border-purple-100 transform transition-all duration-500 hover:scale-[1.02] hover:shadow-xl animate-fadeInRight">
                    <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                        <div class="flex-shrink-0">
                            <div class="relative">
                                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg transform transition-all duration-500 hover:-rotate-6 hover:scale-110">
                                    <img src="{{ asset('images/Developer/Wafiq WW.jpeg') }}" 
                                         alt="Wafiq Wardatul Khairani" 
                                         class="w-full h-full object-cover"
                                         onerror="this.src='https://ui-avatars.com/api/?name=Wafiq+Wardatul+Khairani&background=8b5cf6&color=fff&size=128'">
                                </div>
                                <div class="absolute -bottom-1 -right-1 w-8 h-8 bg-purple-500 rounded-full border-2 border-white flex items-center justify-center animate-pulse">
                                    <i class="fas fa-code text-white text-xs"></i>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-bold text-gray-800 text-lg mb-1 group-hover:text-purple-600 transition-colors">Wafiq Wardatul Khairani</h3>
                            <p class="text-sm text-gray-600 mb-3">Information Systems Graduate 2025</p>
                            <div class="mb-3">
                                <span class="inline-block bg-purple-600 text-white text-xs px-3 py-1 rounded-full font-medium shadow-md transform hover:scale-105 transition-transform">
                                    <i class="fas fa-database mr-1"></i> Full-Stack Developer
                                </span>
                            </div>
                            <p class="text-sm text-gray-700 mb-2 leading-relaxed">
                                Experienced in HSBL website development, programmer at Digital Innovation Hub research, and internship at Riau Pos Event Organizer Division.
                            </p>
                            <p class="text-sm text-gray-700 leading-relaxed">
                                Specializes in integrated backend and frontend development with comprehensive system architecture.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Image Modal untuk Schedule Preview --}}
<div id="imageModal" class="fixed inset-0 z-50 hidden bg-black/90 flex items-center justify-center p-4">
    <div class="relative max-w-3xl w-full max-h-[90vh] flex flex-col">
        {{-- Close Button --}}
        <button onclick="closeImageModal()" 
                class="absolute -top-10 right-0 text-white hover:text-blue-300 text-2xl transition-all hover:scale-110 z-10">
            <i class="fas fa-times-circle"></i>
        </button>
        
        {{-- Scrollable Image Container --}}
        <div class="bg-white rounded-lg overflow-hidden shadow-2xl flex flex-col h-full">
            <div class="overflow-y-auto flex-1 p-1 bg-gray-100">
                <img id="modalImage" src="" alt="" class="w-full h-auto object-contain mx-auto">
            </div>
            
            {{-- Caption Section (optional) --}}
            <div class="p-3 bg-white border-t border-gray-200">
                <h3 id="modalTitle" class="font-semibold text-gray-900 text-center"></h3>
            </div>
        </div>
    </div>
</div>

{{-- Photo Modal untuk Preview Foto Gallery --}}
<div id="photoModal" class="fixed inset-0 z-50 hidden bg-black/95 flex items-center justify-center p-4">
    <div class="relative max-w-5xl w-full max-h-[90vh] flex flex-col">
        {{-- Close Button --}}
        <button onclick="closePhotoModal()" 
                class="absolute -top-12 right-0 text-white hover:text-purple-300 text-3xl transition-all hover:scale-110 z-10">
            <i class="fas fa-times-circle"></i>
        </button>
        
        {{-- Image Container --}}
        <div class="bg-white rounded-xl overflow-hidden shadow-2xl flex flex-col h-full">
            <div class="overflow-auto flex-1 bg-gray-900 p-2 flex items-center justify-center">
                <img id="photoModalImage" src="" alt="" class="max-w-full max-h-[70vh] object-contain rounded-lg">
            </div>
            
            {{-- Caption Section --}}
            <div class="p-4 bg-white border-t border-gray-200">
                <h3 id="photoModalTitle" class="font-semibold text-gray-900 text-center text-lg"></h3>
                <div class="flex justify-center mt-2 space-x-4">
                    <a id="photoModalDownload" href="#" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-download mr-2"></i>
                        Download Gallery
                    </a>
                    <a href="{{ route('user.gallery.photos.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 text-sm rounded-lg hover:bg-gray-200 transition-colors">
                        <i class="fas fa-images mr-2"></i>
                        View All Photos
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Animasi Keyframes */
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes scaleIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    @keyframes pulse-slow {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
    
    @keyframes bounce-slow {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-5px); }
    }
    
    /* Animation Classes */
    .animate-fadeIn {
        animation: fadeIn 1s ease-out;
    }
    
    .animate-fadeInUp {
        animation: fadeInUp 0.8s ease-out;
    }
    
    .animate-fadeInLeft {
        animation: fadeInLeft 0.8s ease-out;
    }
    
    .animate-fadeInRight {
        animation: fadeInRight 0.8s ease-out;
    }
    
    .animate-slideInLeft {
        animation: slideInLeft 0.8s ease-out;
    }
    
    .animate-slideInRight {
        animation: slideInRight 0.8s ease-out;
    }
    
    .animate-scaleIn {
        animation: scaleIn 0.6s ease-out;
    }
    
    .animate-pulse-slow {
        animation: pulse-slow 3s infinite;
    }
    
    .animate-bounce-slow {
        animation: bounce-slow 2s infinite;
    }
    
    /* Animation Delays */
    .animation-delay-1000 {
        animation-delay: 1s;
    }
    
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    
    .animation-delay-3000 {
        animation-delay: 3s;
    }
    
    /* Line Clamp */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
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
    
    /* Aspect Ratio */
    .aspect-w-1 {
        position: relative;
        padding-bottom: 100%;
    }
    
    .aspect-w-16 {
        position: relative;
        padding-bottom: 56.25%;
    }
    
    .aspect-w-3 {
        position: relative;
        padding-bottom: 133.33%;
    }
    
    .aspect-w-4 {
        position: relative;
        padding-bottom: 75%;
    }
    
    .aspect-w-4.aspect-h-3 {
        padding-bottom: 75%; /* 4:3 aspect ratio */
    }
    
    .aspect-w-1 > *,
    .aspect-w-16 > *,
    .aspect-w-3 > *,
    .aspect-w-4 > * {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }
    
    /* Smooth Transitions */
    * {
        transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
    
    /* Hover Effects */
    .group:hover .group-hover\:scale-105 {
        transform: scale(1.05);
    }
    
    .group:hover .group-hover\:opacity-100 {
        opacity: 1;
    }
    
    .group:hover .group-hover\:translate-y-0 {
        transform: translateY(0);
    }
    
    /* Custom Scrollbar */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #3b82f6, #2563eb);
        border-radius: 4px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #2563eb, #1d4ed8);
    }
    
    /* Gradient Animation */
    .bg-gradient-to-r {
        background-size: 200% 200%;
        animation: gradient 15s ease infinite;
    }
    
    @keyframes gradient {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }
    
    /* Drop shadow for icons */
    .drop-shadow-md {
        filter: drop-shadow(0 4px 3px rgb(0 0 0 / 0.3)) drop-shadow(0 2px 2px rgb(0 0 0 / 0.2));
    }
    
    /* Modal Styles */
    #imageModal .overflow-y-auto,
    #photoModal .overflow-auto {
        scrollbar-width: thin;
        scrollbar-color: #3b82f6 #f1f1f1;
    }
    
    #imageModal .overflow-y-auto::-webkit-scrollbar,
    #photoModal .overflow-auto::-webkit-scrollbar {
        width: 6px;
    }
    
    #imageModal .overflow-y-auto::-webkit-scrollbar-track,
    #photoModal .overflow-auto::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 3px;
    }
    
    #imageModal .overflow-y-auto::-webkit-scrollbar-thumb,
    #photoModal .overflow-auto::-webkit-scrollbar-thumb {
        background: #3b82f6;
        border-radius: 3px;
    }
    
    #imageModal .overflow-y-auto::-webkit-scrollbar-thumb:hover,
    #photoModal .overflow-auto::-webkit-scrollbar-thumb:hover {
        background: #2563eb;
    }
    
    /* Photo Gallery Card Styles */
    .group:hover .group-hover\:translate-y-0 {
        transform: translateY(0);
    }
    
    .group .translate-y-full {
        transform: translateY(100%);
    }
    
    /* Backdrop blur */
    .backdrop-blur-sm {
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }
</style>
@endpush

@push('scripts')
<script>
// Image Modal Functions untuk Schedule
function openImageModal(imageUrl, title) {
    const modal = document.getElementById('imageModal');
    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('modalTitle');
    
    // Set image source
    modalImage.src = imageUrl;
    
    // Set title
    modalTitle.textContent = title || 'Schedule';
    
    // Show modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Reset scroll position ke atas
    const scrollContainer = document.querySelector('#imageModal .overflow-y-auto');
    if (scrollContainer) {
        scrollContainer.scrollTop = 0;
    }
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Clear image source untuk menghemat memori
    const modalImage = document.getElementById('modalImage');
    modalImage.src = '';
}

// Photo Modal Functions untuk Gallery
function openPhotoModal(imageUrl, title) {
    const modal = document.getElementById('photoModal');
    const modalImage = document.getElementById('photoModalImage');
    const modalTitle = document.getElementById('photoModalTitle');
    const downloadLink = document.getElementById('photoModalDownload');
    
    // Set image source
    modalImage.src = imageUrl;
    
    // Set title
    modalTitle.textContent = title || 'Photo Gallery';
    
    // Set download link (tetap ke halaman gallery)
    downloadLink.href = '{{ route("user.gallery.photos.index") }}';
    
    // Show modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Reset scroll position
    const scrollContainer = document.querySelector('#photoModal .overflow-auto');
    if (scrollContainer) {
        scrollContainer.scrollTop = 0;
    }
}

function closePhotoModal() {
    const modal = document.getElementById('photoModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    // Clear image source
    const modalImage = document.getElementById('photoModalImage');
    modalImage.src = '';
}

// Close modals with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
        closePhotoModal();
    }
});

// Close modals when clicking outside the image container
document.getElementById('imageModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

document.getElementById('photoModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closePhotoModal();
    }
});

// Prevent closing when clicking inside the modal content
document.querySelector('#imageModal .bg-white')?.addEventListener('click', function(e) {
    e.stopPropagation();
});

document.querySelector('#photoModal .bg-white')?.addEventListener('click', function(e) {
    e.stopPropagation();
});

// Add animation on scroll
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fadeInUp');
            }
        });
    }, { threshold: 0.1 });
    
    document.querySelectorAll('.section-content').forEach(section => {
        observer.observe(section);
    });
});
</script>
@endpush

@endsection