<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\AddData;
use App\Models\TeamList;
use App\Models\MediaNews; // Ubah dari News ke MediaNews
use App\Models\MatchData;
use App\Models\MatchResult;
use App\Models\MediaVideo; // Ubah dari Video ke MediaVideo
use App\Models\Sponsor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Display the user dashboard/home page.
     */
    public function index()
    {
        try {
            // Get total competitions - Hanya ambil data dari kolom competition
            $totalCompetitions = Cache::remember('total_competitions', 3600, function () {
                try {
                    if (DB::getSchemaBuilder()->hasTable('add_data')) {
                        // Ambil data unik dari kolom competition
                        return AddData::distinct('competition')->count('competition');
                    }
                    return 12; // Fallback value jika tabel tidak ada
                } catch (\Exception $e) {
                    return 12; // Fallback value
                }
            });

            // Get total unique schools - dari tabel team_list kolom school_name
            $totalSchools = Cache::remember('total_schools', 3600, function () {
                try {
                    if (!DB::getSchemaBuilder()->hasTable('team_list')) { // Ubah ke team_list
                        return 24; // Fallback value
                    }
                    
                    if (DB::getSchemaBuilder()->hasColumn('team_list', 'school_name')) {
                        return TeamList::distinct('school_name')->count('school_name');
                    }
                    
                    return 24; // Fallback value
                } catch (\Exception $e) {
                    return 24; // Fallback value
                }
            });

            // Get latest news (3 items) - dari tabel media_news
            $latestNews = Cache::remember('latest_news_home', 1800, function () {
                try {
                    if (!DB::getSchemaBuilder()->hasTable('media_news')) { // Ubah ke media_news
                        return collect();
                    }
                    
                    $query = DB::table('media_news'); // Gunakan DB::table untuk media_news
                    
                    if (DB::getSchemaBuilder()->hasColumn('media_news', 'status')) {
                        $query->where(function($q) {
                            $q->where('status', 'published')
                              ->orWhere('status', 'active')
                              ->orWhereNull('status');
                        });
                    }
                    
                    return $query->orderBy('created_at', 'desc')
                        ->take(3)
                        ->get(['id', 'title as title', 'content', 'created_at', 'thumbnail']); // Kolom tittle (bukan title)
                } catch (\Exception $e) {
                    return collect();
                }
            });

            // Get upcoming matches (3 items)
            $upcomingMatches = Cache::remember('upcoming_matches_home', 900, function () {
                try {
                    if (!DB::getSchemaBuilder()->hasTable('match_data')) {
                        return collect();
                    }
                    
                    $query = MatchData::where('date', '>=', now()->format('Y-m-d'));
                    
                    if (DB::getSchemaBuilder()->hasColumn('match_data', 'status')) {
                        $query->where(function($q) {
                            $q->where('status', 'published')
                              ->orWhere('status', 'active')
                              ->orWhereNull('status');
                        });
                    }
                    
                    return $query->orderBy('date', 'asc')
                        ->orderBy('time', 'asc')
                        ->take(3)
                        ->get(['id', 'main_title as main_title', 'layout_image', 'date', 'time', 'venue']);
                } catch (\Exception $e) {
                    return collect();
                }
            });

            // Get latest results (3 items)
            $latestResults = Cache::remember('latest_results_home', 900, function () {
                try {
                    if (!DB::getSchemaBuilder()->hasTable('match_results')) {
                        return collect();
                    }
                    
                    $query = MatchResult::with(['team1:id,school_name', 'team2:id,school_name']);
                    
                    if (DB::getSchemaBuilder()->hasColumn('match_results', 'status')) {
                        $query->where(function($q) {
                            $q->where('status', 'published')
                              ->orWhere('status', 'active')
                              ->orWhereNull('status');
                        });
                    }
                    
                    return $query->orderBy('match_date', 'desc')
                        ->take(3)
                        ->get(['id', 'match_date', 'competition', 'series', 'phase', 'team1_id', 'team2_id', 'team1_score', 'team2_score']);
                } catch (\Exception $e) {
                    return collect();
                }
            });

            // Get videos (3 items) - dari tabel media_videos
            $videos = Cache::remember('videos_home', 1800, function () {
                try {
                    if (!DB::getSchemaBuilder()->hasTable('media_videos')) { // Ubah ke media_videos
                        return collect();
                    }
                    
                    $query = DB::table('media_videos'); // Gunakan DB::table untuk media_videos
                    
                    if (DB::getSchemaBuilder()->hasColumn('media_videos', 'status')) {
                        $query->where(function($q) {
                            $q->where('status', 'active')
                              ->orWhere('status', 'published');
                        });
                    }
                    
                    if (DB::getSchemaBuilder()->hasColumn('media_videos', 'type')) {
                        $query->orderByRaw("CASE WHEN type = 'live' THEN 1 WHEN type = 'highlight' THEN 2 ELSE 3 END");
                    }
                    
                    return $query->orderBy('created_at', 'desc')
                        ->take(3)
                        ->get(['id', 'title as title', 'thumbnail', 'youtube_link', 'type', 'created_at']);
                } catch (\Exception $e) {
                    return collect();
                }
            });

            // Get sponsors grouped by category for the footer
            $sponsors = Cache::remember('sponsors_home', 3600, function () {
                try {
                    if (!DB::getSchemaBuilder()->hasTable('sponsors')) {
                        return collect();
                    }
                    
                    $query = Sponsor::query();
                    
                    if (DB::getSchemaBuilder()->hasColumn('sponsors', 'status')) {
                        $query->where(function($q) {
                            $q->where('status', 'active')
                              ->orWhere('status', 'published');
                        });
                    }
                    
                    return $query->orderBy('category')
                        ->orderBy('created_at', 'desc')
                        ->get()
                        ->groupBy('category');
                } catch (\Exception $e) {
                    return collect();
                }
            });

            // Process images path
            $upcomingMatches = $upcomingMatches->map(function ($match) {
                if ($match->layout_image && !str_starts_with($match->layout_image, 'http')) {
                    $match->layout_image = 'images/schedule/' . $match->layout_image;
                }
                return $match;
            });

            $videos = $videos->map(function ($video) {
                if ($video->thumbnail && !str_starts_with($video->thumbnail, 'http')) {
                    $video->thumbnail = 'storage/uploads/videos/' . $video->thumbnail;
                }
                return $video;
            });

            // Prepare data for view
            $data = [
                'totalCompetitions' => $totalCompetitions,
                'totalSchools' => $totalSchools,
                'latestNews' => $latestNews,
                'upcomingMatches' => $upcomingMatches,
                'latestResults' => $latestResults,
                'videos' => $videos,
                'groupedSponsors' => $sponsors,
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
                'upcomingMatches' => collect(),
                'latestResults' => collect(),
                'videos' => collect(),
                'groupedSponsors' => collect(),
            ]);
        }
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
                        try {
                            if (DB::getSchemaBuilder()->hasTable('add_data')) {
                                $widgets['competitions'] = AddData::distinct('competition')->count('competition');
                            } else {
                                $widgets['competitions'] = 12;
                            }
                        } catch (\Exception $e) {
                            $widgets['competitions'] = 12;
                        }
                        break;
                        
                    case 'schools':
                        try {
                            if (!DB::getSchemaBuilder()->hasTable('team_list')) { // Ubah ke team_list
                                $widgets['schools'] = 24;
                                break;
                            }
                            
                            if (DB::getSchemaBuilder()->hasColumn('team_list', 'school_name')) {
                                $widgets['schools'] = TeamList::distinct('school_name')->count('school_name');
                            } else {
                                $widgets['schools'] = 24;
                            }
                        } catch (\Exception $e) {
                            $widgets['schools'] = 24;
                        }
                        break;
                        
                    case 'live_matches':
                        try {
                            if (!DB::getSchemaBuilder()->hasTable('match_data')) {
                                $widgets['live_matches'] = 0;
                                break;
                            }
                            
                            $query = MatchData::where('date', now()->format('Y-m-d'));
                            
                            if (DB::getSchemaBuilder()->hasColumn('match_data', 'status')) {
                                $query->where(function($q) {
                                    $q->where('status', 'published')
                                      ->orWhere('status', 'active')
                                      ->orWhereNull('status');
                                });
                            }
                            
                            $widgets['live_matches'] = $query->count();
                        } catch (\Exception $e) {
                            $widgets['live_matches'] = 0;
                        }
                        break;
                        
                    case 'latest_news':
                        try {
                            if (!DB::getSchemaBuilder()->hasTable('media_news')) { // Ubah ke media_news
                                $widgets['latest_news'] = collect();
                                break;
                            }
                            
                            $query = DB::table('media_news');
                            
                            if (DB::getSchemaBuilder()->hasColumn('media_news', 'status')) {
                                $query->where(function($q) {
                                    $q->where('status', 'published')
                                      ->orWhere('status', 'active')
                                      ->orWhereNull('status');
                                });
                            }
                            
                            $widgets['latest_news'] = $query->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get(['id', 'title as title', 'created_at']);
                        } catch (\Exception $e) {
                            $widgets['latest_news'] = collect();
                        }
                        break;
                }
            } else {
                // Return all widgets
                $widgets = [
                    'competitions' => function() {
                        try {
                            if (DB::getSchemaBuilder()->hasTable('add_data')) {
                                return AddData::distinct('competition')->count('competition');
                            }
                            return 12;
                        } catch (\Exception $e) {
                            return 12;
                        }
                    },
                    'schools' => function() {
                        try {
                            if (!DB::getSchemaBuilder()->hasTable('team_list')) { // Ubah ke team_list
                                return 24;
                            }
                            
                            if (DB::getSchemaBuilder()->hasColumn('team_list', 'school_name')) {
                                return TeamList::distinct('school_name')->count('school_name');
                            }
                            
                            return 24;
                        } catch (\Exception $e) {
                            return 24;
                        }
                    },
                    'live_matches' => function() {
                        try {
                            if (!DB::getSchemaBuilder()->hasTable('match_data')) {
                                return 0;
                            }
                            
                            $query = MatchData::where('date', now()->format('Y-m-d'));
                            
                            if (DB::getSchemaBuilder()->hasColumn('match_data', 'status')) {
                                $query->where(function($q) {
                                    $q->where('status', 'published')
                                      ->orWhere('status', 'active')
                                      ->orWhereNull('status');
                                });
                            }
                            
                            return $query->count();
                        } catch (\Exception $e) {
                            return 0;
                        }
                    },
                    'today_results' => function() {
                        try {
                            if (DB::getSchemaBuilder()->hasTable('match_results')) {
                                return MatchResult::whereDate('match_date', now()->format('Y-m-d'))->count();
                            }
                            return 0;
                        } catch (\Exception $e) {
                            return 0;
                        }
                    },
                ];

                foreach ($widgets as $key => $callback) {
                    $widgets[$key] = $callback();
                }
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
     * Get dashboard statistics summary
     */
    public function statisticsSummary()
    {
        try {
            $stats = Cache::remember('dashboard_stats_summary', 300, function () {
                $competitions = function() {
                    try {
                        if (DB::getSchemaBuilder()->hasTable('add_data')) {
                            return [
                                'total' => AddData::distinct('competition')->count('competition'),
                                'this_month' => AddData::whereMonth('created_at', now()->month)
                                    ->distinct('competition')
                                    ->count('competition'),
                            ];
                        }
                        return ['total' => 12, 'this_month' => 0];
                    } catch (\Exception $e) {
                        return ['total' => 12, 'this_month' => 0];
                    }
                };

                $schools = function() {
                    try {
                        if (!DB::getSchemaBuilder()->hasTable('team_list')) { // Ubah ke team_list
                            return ['total' => 24, 'active' => 0];
                        }
                        
                        if (DB::getSchemaBuilder()->hasColumn('team_list', 'school_name')) {
                            $total = TeamList::distinct('school_name')->count('school_name');
                            $active = TeamList::where('verification_status', 'verified')
                                ->distinct('school_name')
                                ->count('school_name');
                        } else {
                            $total = 24;
                            $active = 0;
                        }
                        
                        return [
                            'total' => $total,
                            'active' => $active,
                        ];
                    } catch (\Exception $e) {
                        return ['total' => 24, 'active' => 0];
                    }
                };

                $matches = function() {
                    try {
                        if (!DB::getSchemaBuilder()->hasTable('match_data')) {
                            return ['upcoming' => 0, 'completed' => 0];
                        }
                        
                        $upcomingQuery = MatchData::where('date', '>=', now()->format('Y-m-d'));
                        
                        if (DB::getSchemaBuilder()->hasColumn('match_data', 'status')) {
                            $upcomingQuery->where(function($q) {
                                $q->where('status', 'published')
                                  ->orWhere('status', 'active')
                                  ->orWhereNull('status');
                            });
                        }
                        
                        $completed = DB::getSchemaBuilder()->hasTable('match_results') ? 
                            MatchResult::count() : 0;
                        
                        return [
                            'upcoming' => $upcomingQuery->count(),
                            'completed' => $completed,
                        ];
                    } catch (\Exception $e) {
                        return ['upcoming' => 0, 'completed' => 0];
                    }
                };

                $media = function() {
                    try {
                        $newsCount = 0;
                        $videosCount = 0;
                        
                        if (DB::getSchemaBuilder()->hasTable('media_news')) { // Ubah ke media_news
                            $newsQuery = DB::table('media_news');
                            if (DB::getSchemaBuilder()->hasColumn('media_news', 'status')) {
                                $newsQuery->where(function($q) {
                                    $q->where('status', 'published')
                                      ->orWhere('status', 'active');
                                });
                            }
                            $newsCount = $newsQuery->count();
                        }
                        
                        if (DB::getSchemaBuilder()->hasTable('media_videos')) { // Ubah ke media_videos
                            $videoQuery = DB::table('media_videos');
                            if (DB::getSchemaBuilder()->hasColumn('media_videos', 'status')) {
                                $videoQuery->where(function($q) {
                                    $q->where('status', 'active')
                                      ->orWhere('status', 'published');
                                });
                            }
                            $videosCount = $videoQuery->count();
                        }
                        
                        return [
                            'news' => $newsCount,
                            'videos' => $videosCount,
                        ];
                    } catch (\Exception $e) {
                        return ['news' => 0, 'videos' => 0];
                    }
                };

                return [
                    'competitions' => $competitions(),
                    'schools' => $schools(),
                    'matches' => $matches(),
                    'media' => $media()
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
     * Simple dashboard data (without cache for debugging)
     */
    public function simpleData()
    {
        try {
            // Simple counts without complex queries
            $data = [
                'totalCompetitions' => DB::getSchemaBuilder()->hasTable('add_data') ? 
                    AddData::distinct('competition')->count('competition') : 12,
                'totalSchools' => DB::getSchemaBuilder()->hasTable('team_list') ? // Ubah ke team_list
                    TeamList::distinct('school_name')->count('school_name') : 24,
                'newsCount' => DB::getSchemaBuilder()->hasTable('media_news') ? // Ubah ke media_news
                    DB::table('media_news')->count() : 0,
                'matchesCount' => DB::getSchemaBuilder()->hasTable('match_data') ? 
                    MatchData::count() : 0,
                'resultsCount' => DB::getSchemaBuilder()->hasTable('match_results') ? 
                    MatchResult::count() : 0,
                'videosCount' => DB::getSchemaBuilder()->hasTable('media_videos') ? // Ubah ke media_videos
                    DB::table('media_videos')->count() : 0,
            ];

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load simple data',
                'error' => $e->getMessage()
            ], 500);
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
                'upcoming_matches_home',
                'latest_results_home',
                'videos_home',
                'sponsors_home',
                'dashboard_stats_summary',
                'recent_activities'
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

    /**
     * Get database schema info for debugging
     */
    public function schemaInfo()
    {
        try {
            // Update table names sesuai deskripsi
            $tables = ['add_data', 'team_list', 'media_news', 'match_data', 'match_results', 'media_videos', 'sponsors'];
            $schema = [];
            
            foreach ($tables as $table) {
                if (DB::getSchemaBuilder()->hasTable($table)) {
                    $columns = DB::getSchemaBuilder()->getColumnListing($table);
                    $schema[$table] = [
                        'exists' => true,
                        'columns' => $columns,
                        'count' => DB::table($table)->count()
                    ];
                } else {
                    $schema[$table] = [
                        'exists' => false,
                        'columns' => [],
                        'count' => 0
                    ];
                }
            }
            
            return response()->json([
                'success' => true,
                'schema' => $schema
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get schema info',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}