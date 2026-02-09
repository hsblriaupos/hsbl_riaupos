<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class UserVideosController extends Controller
{
    /**
     * Display a listing of videos for user gallery.
     */
    public function index(Request $request)
    {
        // Hanya ambil video dengan status 'view' (published)
        $query = Video::where('status', 'view');
        
        // Search functionality
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        
        // Filter by type
        if ($request->has('type') && !empty($request->type)) {
            $query->where('type', $request->type);
        }
        
        // Filter by year
        if ($request->has('year') && !empty($request->year)) {
            $query->whereYear('created_at', $request->year);
        }
        
        // Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'popular':
                    if (Schema::hasColumn('media_videos', 'views')) {
                        $query->orderBy('views', 'desc');
                    } elseif (Schema::hasColumn('media_videos', 'view_count')) {
                        $query->orderBy('view_count', 'desc');
                    } else {
                        $query->orderBy('created_at', 'desc');
                    }
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        // Pagination
        $perPage = $request->per_page ?? 20;
        $videos = $query->paginate($perPage)->withQueryString();
        
        // Format data untuk view
        $videos->getCollection()->transform(function ($video) {
            $video->channel_avatar = $video->channel_avatar ?? null;
            $video->channel_name = $video->channel_name ?? 'Channel';
            
            // Get view count
            if (Schema::hasColumn('media_videos', 'views')) {
                $video->view_count = $video->views ?? 0;
            } elseif (Schema::hasColumn('media_videos', 'view_count')) {
                $video->view_count = $video->view_count ?? 0;
            } else {
                $video->view_count = 0;
            }
            
            return $video;
        });
        
        // Get distinct years for filter dropdown
        $years = Video::where('status', 'view')
                     ->selectRaw('YEAR(created_at) as year')
                     ->groupBy('year')
                     ->orderBy('year', 'desc')
                     ->pluck('year');
        
        // Get total counts for each type
        $videoCount = Video::where('status', 'view')
                          ->where('type', 'video')
                          ->count();
                          
        $liveCount = Video::where('status', 'view')
                         ->where('type', 'live')
                         ->count();
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'html' => view('user.media.gallery.partials.video_grid', compact('videos'))->render(),
                'hasMorePages' => $videos->hasMorePages(),
                'total' => $videos->total()
            ]);
        }
        
        return view('user.media.gallery.videos_list', compact('videos', 'years', 'videoCount', 'liveCount'));
    }
    
    /**
     * Display single video detail page.
     */
    public function show($slug)
    {
        \Log::info('UserVideosController@show called with parameter:', ['slug' => $slug]);
        
        if (empty($slug) || $slug === 'detail') {
            \Log::warning('Empty or invalid slug parameter, redirecting to videos list');
            return redirect()->route('user.videos');
        }
        
        try {
            $video = null;
            
            // Cek apakah parameter numeric (ID)
            if (is_numeric($slug)) {
                $video = Video::where('id', $slug)
                             ->where('status', 'view')
                             ->first();
                
                if ($video && !empty($video->slug)) {
                    return redirect()->route('user.videos.detail', $video->slug);
                }
            } else {
                // Cari berdasarkan slug
                $video = Video::where('slug', $slug)
                             ->where('status', 'view')
                             ->first();
                
                // Jika tidak ditemukan, coba cari berdasarkan ID di slug
                if (!$video && is_numeric($slug)) {
                    $video = Video::where('id', $slug)
                                 ->where('status', 'view')
                                 ->first();
                }
            }
            
            if (!$video) {
                abort(404, 'Video tidak ditemukan');
            }
            
            // Increment view count
            if (Schema::hasColumn('media_videos', 'views')) {
                $video->increment('views');
            } elseif (Schema::hasColumn('media_videos', 'view_count')) {
                $video->increment('view_count');
            }
            
            // Get related videos (max 8) - prioritize same type
            $relatedVideos = Video::where('status', 'view')
                                 ->where('id', '!=', $video->id)
                                 ->where(function($query) use ($video) {
                                     // First, try to get videos of the same type
                                     $query->where('type', $video->type);
                                 })
                                 ->inRandomOrder()
                                 ->limit(6) // Get 6 from same type
                                 ->get();
            
            // If we don't have enough related videos, get more random ones
            if ($relatedVideos->count() < 8) {
                $needed = 8 - $relatedVideos->count();
                $excludeIds = $relatedVideos->pluck('id')->push($video->id)->toArray();
                
                $additionalVideos = Video::where('status', 'view')
                                        ->where('id', '!=', $video->id)
                                        ->whereNotIn('id', $excludeIds)
                                        ->inRandomOrder()
                                        ->limit($needed)
                                        ->get();
                
                $relatedVideos = $relatedVideos->merge($additionalVideos);
            }
            
            // Limit to 8 videos maximum
            $relatedVideos = $relatedVideos->take(8);
            
            \Log::info('Related videos prepared:', [
                'count' => $relatedVideos->count(),
                'current_video_id' => $video->id,
                'current_video_type' => $video->type
            ]);
            
            // PERBAIKAN: Cek apakah video YouTube dan dapatkan video ID dengan benar
            $isYoutube = false;
            $videoId = null;
            $youtubeLink = $video->youtube_link ?? '';
            
            \Log::debug('Video data for YouTube extraction:', [
                'id' => $video->id,
                'title' => $video->title,
                'youtube_link' => $youtubeLink,
                'video_code' => $video->video_code ?? 'null'
            ]);
            
            if (!empty($youtubeLink)) {
                // Clean URL dari parameter tambahan dan ekstrak video ID
                $videoId = $this->extractYouTubeId($youtubeLink);
                
                if ($videoId) {
                    $isYoutube = true;
                    \Log::info('YouTube ID extracted successfully:', [
                        'videoId' => $videoId, 
                        'originalUrl' => $youtubeLink
                    ]);
                } else {
                    \Log::warning('Failed to extract YouTube ID from URL:', ['url' => $youtubeLink]);
                }
            }
            
            // Jika tidak ada dari youtube_link, coba dari video_code
            if (!$isYoutube && !empty($video->video_code)) {
                $videoId = $this->extractYouTubeId($video->video_code);
                if ($videoId) {
                    $isYoutube = true;
                    \Log::info('YouTube ID extracted from video_code:', ['videoId' => $videoId]);
                }
            }
            
            // Format data
            $video->channel_avatar = $video->channel_avatar ?? null;
            $video->channel_name = $video->channel_name ?? 'Channel';
            
            // Get view count
            if (Schema::hasColumn('media_videos', 'views')) {
                $video->view_count = $video->views ?? 0;
            } elseif (Schema::hasColumn('media_videos', 'view_count')) {
                $video->view_count = $video->view_count ?? 0;
            } else {
                $video->view_count = 0;
            }
            
            // PERBAIKAN: Tambahkan informasi YouTube ke video object untuk view
            $video->is_youtube = $isYoutube;
            $video->youtube_id = $videoId;
            $video->clean_youtube_link = $videoId ? "https://www.youtube.com/watch?v=" . $videoId : $youtubeLink;
            
            // Add YouTube info to related videos
            $relatedVideos->transform(function ($relatedVideo) {
                $relatedVideo->is_youtube = false;
                $relatedVideo->youtube_id = null;
                
                if (!empty($relatedVideo->youtube_link)) {
                    $videoId = $this->extractYouTubeId($relatedVideo->youtube_link);
                    if ($videoId) {
                        $relatedVideo->is_youtube = true;
                        $relatedVideo->youtube_id = $videoId;
                    }
                }
                
                // Get view count for related videos
                if (Schema::hasColumn('media_videos', 'views')) {
                    $relatedVideo->view_count = $relatedVideo->views ?? 0;
                } elseif (Schema::hasColumn('media_videos', 'view_count')) {
                    $relatedVideo->view_count = $relatedVideo->view_count ?? 0;
                } else {
                    $relatedVideo->view_count = 0;
                }
                
                return $relatedVideo;
            });
            
            return view('user.media.gallery.video_detail', compact('video', 'relatedVideos'));
            
        } catch (\Exception $e) {
            \Log::error('Error in UserVideosController@show: ' . $e->getMessage(), [
                'exception' => $e,
                'slug' => $slug
            ]);
            abort(404, 'Video tidak ditemukan');
        }
    }
    
    /**
     * Extract YouTube video ID from URL - IMPROVED VERSION
     */
    private function extractYouTubeId($url)
    {
        if (empty($url)) {
            return null;
        }
        
        // Clean URL terlebih dahulu
        $url = trim($url);
        
        \Log::debug('Extracting YouTube ID from URL:', ['url' => $url]);
        
        // Pattern untuk berbagai format YouTube URL
        $patterns = [
            // Standard YouTube watch URL - matches https://www.youtube.com/watch?v=VIDEO_ID
            '/youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/i',
            
            // YouTube short URL - matches https://youtu.be/VIDEO_ID
            '/youtu\.be\/([a-zA-Z0-9_-]{11})/i',
            
            // YouTube embed URL - matches https://www.youtube.com/embed/VIDEO_ID
            '/youtube\.com\/embed\/([a-zA-Z0-9_-]{11})/i',
            
            // YouTube watch dengan parameter tambahan (seperti playlist) - matches https://www.youtube.com/watch?v=VIDEO_ID&list=...
            '/youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/i',
            
            // YouTube mobile URL
            '/m\.youtube\.com\/watch\?v=([a-zA-Z0-9_-]{11})/i',
            
            // YouTube short URL dengan parameter - matches https://youtu.be/VIDEO_ID?param=value
            '/youtu\.be\/([a-zA-Z0-9_-]{11})\?/i',
            
            // YouTube live URL
            '/youtube\.com\/live\/([a-zA-Z0-9_-]{11})/i',
            
            // YouTube shorts URL
            '/youtube\.com\/shorts\/([a-zA-Z0-9_-]{11})/i',
        ];
        
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                $videoId = $matches[1];
                \Log::debug('Pattern matched:', ['pattern' => $pattern, 'videoId' => $videoId]);
                
                // Validasi video ID (harus 11 karakter untuk YouTube)
                if (strlen($videoId) === 11) {
                    return $videoId;
                }
            }
        }
        
        // Coba metode parsing manual untuk kasus kompleks
        $parsedUrl = parse_url($url);
        
        // Handle youtu.be URLs
        if (isset($parsedUrl['host']) && strpos($parsedUrl['host'], 'youtu.be') !== false) {
            if (isset($parsedUrl['path'])) {
                $path = trim($parsedUrl['path'], '/');
                if (preg_match('/^[a-zA-Z0-9_-]{11}$/', $path)) {
                    return $path;
                }
            }
        }
        
        // Handle standard YouTube URLs with query parameters
        if (isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $queryParams);
            if (isset($queryParams['v']) && preg_match('/^[a-zA-Z0-9_-]{11}$/', $queryParams['v'])) {
                return $queryParams['v'];
            }
        }
        
        \Log::warning('No valid YouTube ID found in URL:', ['url' => $url]);
        return null;
    }
    
    /**
     * Test YouTube URL and get embed status
     */
    public function testYouTubeEmbed($videoId)
    {
        try {
            if (empty($videoId) || strlen($videoId) !== 11) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid YouTube Video ID'
                ]);
            }
            
            // Coba buat embed URL
            $embedUrl = "https://www.youtube.com/embed/{$videoId}";
            
            // Gunakan curl untuk cek status
            $ch = curl_init($embedUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            $status = $httpCode == 200 ? 'Available' : 'Unavailable';
            
            return response()->json([
                'success' => true,
                'videoId' => $videoId,
                'embedUrl' => $embedUrl,
                'watchUrl' => "https://www.youtube.com/watch?v={$videoId}",
                'nocookieUrl' => "https://www.youtube-nocookie.com/embed/{$videoId}",
                'status' => $status,
                'httpCode' => $httpCode,
                'timestamp' => now()->toDateTimeString()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('YouTube embed test failed:', ['error' => $e->getMessage(), 'videoId' => $videoId]);
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'videoId' => $videoId
            ]);
        }
    }
    
    /**
     * Fix YouTube links in database (run once)
     */
    public function fixYouTubeLinks()
    {
        try {
            $videos = Video::where('status', 'view')
                          ->whereNotNull('youtube_link')
                          ->orWhereNotNull('video_code')
                          ->get();
            
            $fixedCount = 0;
            $skippedCount = 0;
            
            foreach ($videos as $video) {
                $videoId = null;
                
                // Coba ekstrak dari youtube_link
                if (!empty($video->youtube_link)) {
                    $videoId = $this->extractYouTubeId($video->youtube_link);
                }
                
                // Jika tidak ada, coba dari video_code
                if (!$videoId && !empty($video->video_code)) {
                    $videoId = $this->extractYouTubeId($video->video_code);
                }
                
                if ($videoId) {
                    // Update dengan clean URL
                    $cleanUrl = "https://www.youtube.com/watch?v={$videoId}";
                    
                    if ($video->youtube_link !== $cleanUrl) {
                        $video->youtube_link = $cleanUrl;
                        $video->save();
                        $fixedCount++;
                        \Log::info("Fixed YouTube link for video {$video->id}: {$cleanUrl}");
                    } else {
                        $skippedCount++;
                    }
                } else {
                    \Log::warning("Could not extract YouTube ID for video {$video->id}");
                    $skippedCount++;
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "Fixed {$fixedCount} YouTube links, skipped {$skippedCount}",
                'total_videos' => $videos->count(),
                'fixed' => $fixedCount,
                'skipped' => $skippedCount
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error fixing YouTube links:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Get video details for modal (AJAX request).
     */
    public function getVideoModal($id)
    {
        try {
            $video = Video::where('id', $id)
                         ->where('status', 'view')
                         ->first();
            
            if (!$video) {
                return response()->json([
                    'success' => false,
                    'message' => 'Video not found or not available'
                ], 404);
            }
            
            // Extract YouTube ID menggunakan metode yang sama
            $youtubeId = $this->extractYouTubeId($video->youtube_link);
            
            // Get thumbnail
            $thumbnail = $video->thumbnail ? asset($video->thumbnail) : null;
            if (!$thumbnail && $youtubeId) {
                $thumbnail = "https://img.youtube.com/vi/{$youtubeId}/maxresdefault.jpg";
            }
            
            // Get view count
            $viewCount = 0;
            if (Schema::hasColumn('media_videos', 'views')) {
                $viewCount = $video->views ?? 0;
            } elseif (Schema::hasColumn('media_videos', 'view_count')) {
                $viewCount = $video->view_count ?? 0;
            }
            
            // Cek apakah YouTube
            $isYoutube = !empty($youtubeId);
            
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $video->id,
                    'title' => $video->title,
                    'description' => $video->description,
                    'youtube_link' => $video->youtube_link,
                    'youtube_id' => $youtubeId,
                    'is_youtube' => $isYoutube,
                    'thumbnail' => $thumbnail,
                    'type' => $video->type,
                    'type_icon' => $video->type == 'live' ? 'fa-broadcast-tower' : 'fa-play-circle',
                    'type_text' => ucfirst($video->type),
                    'created_at' => $video->created_at->format('M d, Y'),
                    'created_at_full' => $video->created_at->format('F j, Y'),
                    'slug' => $video->slug,
                    'views' => $viewCount,
                    'video_code' => $video->video_code,
                    'status' => $video->status,
                ]
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Get video modal error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load video details'
            ], 500);
        }
    }
    
    /**
     * Get videos by type (AJAX filter).
     */
    public function getVideosByType(Request $request)
    {
        try {
            $type = $request->type;
            
            $query = Video::where('status', 'view');
            
            if ($type !== 'all') {
                $query->where('type', $type);
            }
            
            $videos = $query->orderBy('created_at', 'desc')
                           ->paginate($request->per_page ?? 20);
            
            // Format data untuk view
            $videos->getCollection()->transform(function ($video) {
                $video->channel_avatar = $video->channel_avatar ?? null;
                $video->channel_name = $video->channel_name ?? 'Channel';
                
                // Get view count
                if (Schema::hasColumn('media_videos', 'views')) {
                    $video->view_count = $video->views ?? 0;
                } elseif (Schema::hasColumn('media_videos', 'view_count')) {
                    $video->view_count = $video->view_count ?? 0;
                } else {
                    $video->view_count = 0;
                }
                
                return $video;
            });
            
            return response()->json([
                'success' => true,
                'html' => view('user.media.gallery.partials.video_grid', compact('videos'))->render(),
                'hasMorePages' => $videos->hasMorePages(),
                'total' => $videos->total()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Get videos by type error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load videos'
            ], 500);
        }
    }
    
    /**
     * Get latest videos for homepage/widget.
     */
    public function getLatestVideos($limit = 8)
    {
        $videos = Video::where('status', 'view')
                      ->orderBy('created_at', 'desc')
                      ->limit($limit)
                      ->get();
        
        // Format data
        $videos->transform(function ($video) {
            $video->channel_avatar = $video->channel_avatar ?? null;
            $video->channel_name = $video->channel_name ?? 'Channel';
            
            // Get view count
            if (Schema::hasColumn('media_videos', 'views')) {
                $video->view_count = $video->views ?? 0;
            } elseif (Schema::hasColumn('media_videos', 'view_count')) {
                $video->view_count = $video->view_count ?? 0;
            } else {
                $video->view_count = 0;
            }
            
            return $video;
        });
        
        return $videos;
    }
    
    /**
     * Get video counts for statistics.
     */
    public function getVideoStats()
    {
        $totalVideos = Video::where('status', 'view')->count();
        $videoCount = Video::where('status', 'view')->where('type', 'video')->count();
        $liveCount = Video::where('status', 'view')->where('type', 'live')->count();
        
        // Get videos per month for chart (last 6 months)
        $monthlyData = Video::where('status', 'view')
                           ->where('created_at', '>=', now()->subMonths(6))
                           ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, COUNT(*) as count')
                           ->groupBy('month')
                           ->orderBy('month')
                           ->get();
        
        return [
            'total' => $totalVideos,
            'videos' => $videoCount,
            'live' => $liveCount,
            'monthly_data' => $monthlyData
        ];
    }
    
    /**
     * Share video via social media.
     */
    public function shareVideo(Request $request)
    {
        $request->validate([
            'video_id' => 'required|exists:media_videos,id',
            'platform' => 'required|in:facebook,twitter,whatsapp,telegram,linkedin'
        ]);
        
        $video = Video::findOrFail($request->video_id);
        $url = route('user.videos.detail', $video->slug);
        $title = $video->title;
        
        // Generate share URLs
        $shareUrls = [
            'facebook' => "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($url),
            'twitter' => "https://twitter.com/intent/tweet?url=" . urlencode($url) . "&text=" . urlencode($title),
            'whatsapp' => "https://wa.me/?text=" . urlencode($title . " " . $url),
            'telegram' => "https://t.me/share/url?url=" . urlencode($url) . "&text=" . urlencode($title),
            'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url=" . urlencode($url)
        ];
        
        return response()->json([
            'success' => true,
            'share_url' => $shareUrls[$request->platform] ?? $url
        ]);
    }
    
    /**
     * Fallback method jika ada pemanggilan route tanpa parameter
     */
    public function redirectToVideos()
    {
        return redirect()->route('user.videos');
    }
    
    /**
     * Debug method untuk cek data video
     */
    public function debugVideo($id)
    {
        $video = Video::find($id);
        
        if (!$video) {
            return response()->json(['error' => 'Video not found'], 404);
        }
        
        $isYoutube = false;
        $videoId = null;
        
        if (!empty($video->youtube_link)) {
            $isYoutube = Str::contains($video->youtube_link, ['youtube.com', 'youtu.be']);
            $videoId = $this->extractYouTubeId($video->youtube_link);
        }
        
        // Test embed status
        $embedStatus = null;
        if ($videoId) {
            $testResult = $this->testYouTubeEmbed($videoId);
            $embedStatus = json_decode($testResult->getContent(), true);
        }
        
        return response()->json([
            'video' => [
                'id' => $video->id,
                'title' => $video->title,
                'youtube_link' => $video->youtube_link,
                'video_code' => $video->video_code,
                'is_youtube' => $isYoutube,
                'youtube_id' => $videoId,
                'thumbnail' => $video->thumbnail,
                'type' => $video->type,
                'status' => $video->status,
                'created_at' => $video->created_at->toDateTimeString(),
                'updated_at' => $video->updated_at->toDateTimeString(),
            ],
            'related_count' => Video::where('status', 'view')
                                   ->where('id', '!=', $video->id)
                                   ->count(),
            'total_videos' => Video::count(),
            'embed_status' => $embedStatus
        ]);
    }
    
    /**
     * Check if YouTube video is embeddable
     */
    public function checkYouTubeEmbeddable($videoId)
    {
        if (empty($videoId) || strlen($videoId) !== 11) {
            return response()->json([
                'success' => false,
                'error' => 'Invalid YouTube Video ID'
            ]);
        }
        
        try {
            // Buat URL untuk cek
            $checkUrl = "https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v={$videoId}&format=json";
            
            $ch = curl_init($checkUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            $embeddable = $httpCode === 200;
            
            return response()->json([
                'success' => true,
                'videoId' => $videoId,
                'embeddable' => $embeddable,
                'httpCode' => $httpCode,
                'message' => $embeddable ? 'Video is embeddable' : 'Video may not be embeddable'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}