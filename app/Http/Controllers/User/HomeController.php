<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AddData;
use App\Models\TeamList;
use App\Models\News; // Perbaikan: dari MediaNews ke News
use App\Models\MatchData;
use App\Models\MatchResult;
use App\Models\Video; // Perbaikan: dari MediaVideo ke Video
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Display the user dashboard/home page.
     */
    public function index()
    {
        try {
            // Get total competitions
            $totalCompetitions = Cache::remember('total_competitions', 3600, function () {
                try {
                    if (DB::getSchemaBuilder()->hasTable('add_data')) {
                        return AddData::distinct('competition')->count('competition');
                    }
                    return 12;
                } catch (\Exception $e) {
                    return 12;
                }
            });

            // Get total unique schools
            $totalSchools = Cache::remember('total_schools', 3600, function () {
                try {
                    if (!DB::getSchemaBuilder()->hasTable('team_list')) {
                        return 24;
                    }
                    
                    if (DB::getSchemaBuilder()->hasColumn('team_list', 'school_name')) {
                        return TeamList::distinct('school_name')->count('school_name');
                    }
                    
                    return 24;
                } catch (\Exception $e) {
                    return 24;
                }
            });

            // Get latest news (4 items) - Menggunakan model News
            $latestNews = Cache::remember('latest_news_home', 1800, function () {
                try {
                    if (!DB::getSchemaBuilder()->hasTable('news')) {
                        return collect();
                    }
                    
                    $query = News::where('status', 'view'); // Sesuai dengan NewsController
                    
                    return $query->latest()
                        ->take(4)
                        ->get(['id', 'title', 'content', 'image', 'series', 'created_at']);
                } catch (\Exception $e) {
                    \Log::error('Error fetching latest news: ' . $e->getMessage());
                    return collect();
                }
            });

            // Get latest schedules (4 items)
            $latestSchedules = Cache::remember('latest_schedules_home', 900, function () {
                try {
                    if (!DB::getSchemaBuilder()->hasTable('match_data')) {
                        return collect();
                    }
                    
                    $query = MatchData::where(function($q) {
                        $q->where('status', 'active')
                          ->orWhere('status', 'published')
                          ->orWhere('status', 'publish')
                          ->orWhereNull('status');
                    })
                    ->whereNotNull('layout_image');
                    
                    $schedules = $query->latest('upload_date')
                        ->take(4)
                        ->get(['id', 'main_title', 'layout_image', 'series_name', 'upload_date', 'caption']);
                    
                    // Format schedules data
                    $schedules->transform(function ($schedule) {
                        // Format image URL
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
                        
                        // Format date
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
                    
                    return $schedules;
                } catch (\Exception $e) {
                    \Log::error('Error fetching latest schedules: ' . $e->getMessage());
                    return collect();
                }
            });

            // Get latest results (4 items)
            $latestResults = Cache::remember('latest_results_home', 900, function () {
                try {
                    if (!DB::getSchemaBuilder()->hasTable('match_results')) {
                        return collect();
                    }
                    
                    $results = MatchResult::with(['team1:id,school_name,logo', 'team2:id,school_name,logo'])
                        ->whereIn('status', ['completed', 'done', 'publish', 'live'])
                        ->latest('match_date')
                        ->take(4)
                        ->get();
                    
                    // Format results data sesuai ResultController
                    $results->transform(function ($result) {
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
                        
                        // Format logo dari relasi team
                        if ($result->team1 && $result->team1->logo) {
                            $result->team1_logo = str_starts_with($result->team1->logo, 'http') 
                                ? $result->team1->logo 
                                : (str_contains($result->team1->logo, '/') 
                                    ? asset($result->team1->logo) 
                                    : asset('storage/school_logos/' . $result->team1->logo));
                        } else {
                            $result->team1_logo = null;
                        }
                        
                        if ($result->team2 && $result->team2->logo) {
                            $result->team2_logo = str_starts_with($result->team2->logo, 'http') 
                                ? $result->team2->logo 
                                : (str_contains($result->team2->logo, '/') 
                                    ? asset($result->team2->logo) 
                                    : asset('storage/school_logos/' . $result->team2->logo));
                        } else {
                            $result->team2_logo = null;
                        }
                        
                        // Team names
                        $result->team1_name = $result->team1->school_name ?? 'Team A';
                        $result->team2_name = $result->team2->school_name ?? 'Team B';
                        
                        // Score
                        $result->score_1 = isset($result->team1_score) ? (int) $result->team1_score : 0;
                        $result->score_2 = isset($result->team2_score) ? (int) $result->team2_score : 0;
                        
                        // Has scoresheet
                        $result->has_scoresheet = !empty($result->scoresheet);
                        
                        return $result;
                    });
                    
                    return $results;
                } catch (\Exception $e) {
                    \Log::error('Error fetching latest results: ' . $e->getMessage());
                    return collect();
                }
            });

            // Get videos (6 items) - Menggunakan model Video
            $latestVideos = Cache::remember('latest_videos_home', 1800, function () {
                try {
                    if (!DB::getSchemaBuilder()->hasTable('videos')) { // Sesuaikan dengan nama tabel
                        return collect();
                    }
                    
                    $videos = Video::where('status', 'view') // Sesuai dengan VideoController
                        ->latest()
                        ->take(6)
                        ->get(['id', 'title', 'thumbnail', 'youtube_link', 'duration', 'views', 'slug', 'created_at']);
                    
                    // Format video data
                    $videos->transform(function ($video) {
                        // Extract YouTube ID
                        $video->is_youtube = false;
                        $video->youtube_id = null;
                        
                        if (!empty($video->youtube_link)) {
                            $videoId = $this->extractYouTubeId($video->youtube_link);
                            if ($videoId) {
                                $video->is_youtube = true;
                                $video->youtube_id = $videoId;
                                $video->thumbnail_url = "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg";
                            }
                        }
                        
                        // If not YouTube or no thumbnail, use local thumbnail
                        if (!$video->is_youtube && !empty($video->thumbnail)) {
                            $video->thumbnail_url = str_starts_with($video->thumbnail, 'http') 
                                ? $video->thumbnail 
                                : asset($video->thumbnail);
                        } elseif (!$video->is_youtube) {
                            $video->thumbnail_url = asset('images/default-video.jpg');
                        }
                        
                        // View count
                        $video->view_count = $video->views ?? 0;
                        
                        // Format duration
                        $video->duration_formatted = null;
                        if ($video->duration && is_numeric($video->duration)) {
                            $minutes = floor($video->duration / 60);
                            $seconds = $video->duration % 60;
                            $video->duration_formatted = sprintf("%d:%02d", $minutes, $seconds);
                        }
                        
                        return $video;
                    });
                    
                    return $videos;
                } catch (\Exception $e) {
                    \Log::error('Error fetching videos: ' . $e->getMessage());
                    return collect();
                }
            });

            // Get sponsors grouped by category
            $groupedSponsors = Cache::remember('sponsors_home', 3600, function () {
                try {
                    if (!DB::getSchemaBuilder()->hasTable('sponsors')) {
                        return collect();
                    }
                    
                    $query = Sponsor::query();
                    
                    if (DB::getSchemaBuilder()->hasColumn('sponsors', 'status')) {
                        $query->where('status', 'active');
                    }
                    
                    return $query->orderBy('category')
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->groupBy('category');
                } catch (\Exception $e) {
                    return collect();
                }
            });

            // Prepare data for view
            $data = [
                'totalCompetitions' => $totalCompetitions,
                'totalSchools' => $totalSchools,
                'latestNews' => $latestNews,
                'latestSchedules' => $latestSchedules,
                'latestResults' => $latestResults,
                'latestVideos' => $latestVideos,
                'groupedSponsors' => $groupedSponsors,
            ];

            return view('user.dashboard', $data);
            
        } catch (\Exception $e) {
            \Log::error('HomeController error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            // Return fallback data
            return view('user.dashboard', [
                'totalCompetitions' => 12,
                'totalSchools' => 24,
                'latestNews' => collect(),
                'latestSchedules' => collect(),
                'latestResults' => collect(),
                'latestVideos' => collect(),
                'groupedSponsors' => collect(),
            ]);
        }
    }

    /**
     * Extract YouTube ID from URL
     */
    private function extractYouTubeId($url)
    {
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

    /**
     * API endpoint for dashboard widgets (AJAX)
     */
    public function dashboardWidgets(Request $request)
    {
        try {
            $widgets = [];

            if ($request->has('widget')) {
                switch ($request->widget) {
                    case 'competitions':
                        $widgets['competitions'] = $this->getTotalCompetitions();
                        break;
                        
                    case 'schools':
                        $widgets['schools'] = $this->getTotalSchools();
                        break;
                        
                    case 'live_matches':
                        $widgets['live_matches'] = $this->getLiveMatchesCount();
                        break;
                        
                    case 'latest_news':
                        $widgets['latest_news'] = $this->getLatestNews(5);
                        break;
                }
            } else {
                // Return all widgets
                $widgets = [
                    'competitions' => $this->getTotalCompetitions(),
                    'schools' => $this->getTotalSchools(),
                    'live_matches' => $this->getLiveMatchesCount(),
                    'today_results' => $this->getTodayResultsCount(),
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $widgets,
                'timestamp' => now()->toIso8601String()
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Dashboard widgets error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load dashboard widgets'
            ], 500);
        }
    }

    /**
     * Get total competitions
     */
    private function getTotalCompetitions()
    {
        try {
            if (DB::getSchemaBuilder()->hasTable('add_data')) {
                return AddData::distinct('competition')->count('competition');
            }
            return 12;
        } catch (\Exception $e) {
            return 12;
        }
    }

    /**
     * Get total schools
     */
    private function getTotalSchools()
    {
        try {
            if (!DB::getSchemaBuilder()->hasTable('team_list')) {
                return 24;
            }
            
            if (DB::getSchemaBuilder()->hasColumn('team_list', 'school_name')) {
                return TeamList::distinct('school_name')->count('school_name');
            }
            
            return 24;
        } catch (\Exception $e) {
            return 24;
        }
    }

    /**
     * Get live matches count
     */
    private function getLiveMatchesCount()
    {
        try {
            if (!DB::getSchemaBuilder()->hasTable('match_data')) {
                return 0;
            }
            
            $query = MatchData::where('date', now()->format('Y-m-d'));
            
            if (DB::getSchemaBuilder()->hasColumn('match_data', 'status')) {
                $query->whereIn('status', ['published', 'active', 'live']);
            }
            
            return $query->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get today results count
     */
    private function getTodayResultsCount()
    {
        try {
            if (DB::getSchemaBuilder()->hasTable('match_results')) {
                return MatchResult::whereDate('match_date', now()->format('Y-m-d'))->count();
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get latest news for widget
     */
    private function getLatestNews($limit = 5)
    {
        try {
            if (!DB::getSchemaBuilder()->hasTable('news')) {
                return collect();
            }
            
            $query = News::where('status', 'view');
            
            return $query->latest()
                ->take($limit)
                ->get(['id', 'title', 'created_at']);
        } catch (\Exception $e) {
            return collect();
        }
    }

    /**
     * Get dashboard statistics summary
     */
    public function statisticsSummary()
    {
        try {
            $stats = Cache::remember('dashboard_stats_summary', 300, function () {
                return [
                    'competitions' => [
                        'total' => $this->getTotalCompetitions(),
                        'this_month' => $this->getCompetitionsThisMonth(),
                    ],
                    'schools' => [
                        'total' => $this->getTotalSchools(),
                        'active' => $this->getActiveSchools(),
                    ],
                    'matches' => [
                        'upcoming' => $this->getUpcomingMatchesCount(),
                        'completed' => $this->getCompletedMatchesCount(),
                    ],
                    'media' => [
                        'news' => $this->getTotalNews(),
                        'videos' => $this->getTotalVideos(),
                    ]
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Statistics summary error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load statistics'
            ], 500);
        }
    }

    /**
     * Get competitions this month
     */
    private function getCompetitionsThisMonth()
    {
        try {
            if (DB::getSchemaBuilder()->hasTable('add_data')) {
                return AddData::whereMonth('created_at', now()->month)
                    ->distinct('competition')
                    ->count('competition');
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get active schools
     */
    private function getActiveSchools()
    {
        try {
            if (!DB::getSchemaBuilder()->hasTable('team_list')) {
                return 0;
            }
            
            if (DB::getSchemaBuilder()->hasColumn('team_list', 'verification_status')) {
                return TeamList::where('verification_status', 'verified')
                    ->distinct('school_name')
                    ->count('school_name');
            }
            
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get upcoming matches count
     */
    private function getUpcomingMatchesCount()
    {
        try {
            if (!DB::getSchemaBuilder()->hasTable('match_data')) {
                return 0;
            }
            
            $query = MatchData::where('date', '>=', now()->format('Y-m-d'));
            
            if (DB::getSchemaBuilder()->hasColumn('match_data', 'status')) {
                $query->whereIn('status', ['published', 'active']);
            }
            
            return $query->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get completed matches count
     */
    private function getCompletedMatchesCount()
    {
        try {
            if (DB::getSchemaBuilder()->hasTable('match_results')) {
                return MatchResult::count();
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get total news count
     */
    private function getTotalNews()
    {
        try {
            if (!DB::getSchemaBuilder()->hasTable('news')) {
                return 0;
            }
            
            $query = News::where('status', 'view');
            
            return $query->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Get total videos count
     */
    private function getTotalVideos()
    {
        try {
            if (!DB::getSchemaBuilder()->hasTable('videos')) {
                return 0;
            }
            
            $query = Video::where('status', 'view');
            
            return $query->count();
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Clear dashboard cache
     */
    public function clearCache()
    {
        try {
            $cacheKeys = [
                'total_competitions',
                'total_schools',
                'latest_news_home',
                'latest_schedules_home',
                'latest_results_home',
                'latest_videos_home',
                'sponsors_home',
                'dashboard_stats_summary',
            ];

            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }

            return response()->json([
                'success' => true,
                'message' => 'Dashboard cache cleared successfully'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Clear cache error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cache'
            ], 500);
        }
    }
}