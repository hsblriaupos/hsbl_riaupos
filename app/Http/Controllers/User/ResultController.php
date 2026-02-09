<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MatchResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ResultController extends Controller
{
    /**
     * Menampilkan halaman results dengan pagination dan filter
     * UNTUK API ENDPOINT SAJA - untuk digunakan oleh halaman schedule_result
     */
    public function index(Request $request)
    {
        try {
            Log::info('ResultController: Fetching results with filters', [
                'results_series' => $request->input('results_series'),
                'results_season' => $request->input('results_season'),
                'status' => $request->input('status')
            ]);
            
            // Query dasar
            $query = MatchResult::orderBy('match_date', 'desc');
            
            // Filter berdasarkan status (selalu aktif untuk tampilan di schedule_result)
            $query->whereIn('status', ['completed', 'done', 'publish', 'live']);
            
            // Filter berdasarkan series (gunakan results_series untuk menghindari conflict dengan schedules)
            if ($request->filled('results_series')) {
                $query->where('series', $request->input('results_series'));
            } elseif ($request->filled('series')) {
                // Fallback untuk backward compatibility
                $query->where('series', $request->input('series'));
            }
            
            // Filter berdasarkan season
            if ($request->filled('results_season')) {
                $query->where('season', $request->input('results_season'));
            } elseif ($request->filled('year')) {
                // Fallback untuk backward compatibility
                $query->where('season', $request->input('year'));
            }
            
            // Pagination dengan nama page khusus untuk results
            $perPage = 10;
            $results = $query->paginate($perPage, ['*'], 'results_page');
            
            Log::info("ResultController: Found {$results->total()} results, showing {$results->count()} per page");
            
            // Format data untuk response
            $results->getCollection()->transform(function ($result) {
                return $this->formatResultData($result);
            });
            
            // Untuk request dari halaman schedule_result (non-AJAX)
            // Return array data untuk digunakan di view
            return [
                'results' => $results,
                'seasons' => $this->getSeasonsList(),
                'series' => $this->getSeriesList(),
            ];
            
        } catch (\Exception $e) {
            Log::error('ResultController: Error fetching results: ' . $e->getMessage());
            
            // Return array kosong jika error
            return [
                'results' => collect([])->paginate(10),
                'seasons' => collect([date('Y'), date('Y') - 1]),
                'series' => collect([]),
            ];
        }
    }
    
    /**
     * Format data result (untuk digunakan di schedule_result view)
     */
    private function formatResultData($result)
    {
        // Format tanggal match
        if ($result->match_date) {
            try {
                $result->match_date_formatted = \Carbon\Carbon::parse($result->match_date)
                    ->locale('id')
                    ->translatedFormat('l, j F Y');
                $result->match_time = \Carbon\Carbon::parse($result->match_date)->format('H:i');
            } catch (\Exception $e) {
                $result->match_date_formatted = $result->match_date;
                $result->match_time = '00:00';
            }
        } else {
            $result->match_date_formatted = 'Date TBD';
            $result->match_time = '00:00';
        }
        
        // Format scoresheet
        $result->has_scoresheet = !empty($result->scoresheet);
        
        // Format score dengan null coalescing
        $result->score_1 = isset($result->score_1) ? (int) $result->score_1 : 0;
        $result->score_2 = isset($result->score_2) ? (int) $result->score_2 : 0;
        
        // Format logo paths (untuk view schedule_result)
        $result->team1_logo = $this->formatLogoPath($result->team1_logo);
        $result->team2_logo = $this->formatLogoPath($result->team2_logo);
        
        // Status text untuk display (sesuai dengan view schedule_result)
        $statusConfig = [
            'completed' => ['text' => 'Completed', 'class' => 'bg-green-100 text-green-800', 'icon' => 'fas fa-check-circle'],
            'done' => ['text' => 'Completed', 'class' => 'bg-green-100 text-green-800', 'icon' => 'fas fa-check-circle'],
            'publish' => ['text' => 'Published', 'class' => 'bg-purple-100 text-purple-800', 'icon' => 'fas fa-upload'],
            'live' => ['text' => 'Live', 'class' => 'bg-red-100 text-red-800', 'icon' => 'fas fa-play-circle'],
            'upcoming' => ['text' => 'Upcoming', 'class' => 'bg-yellow-100 text-yellow-800', 'icon' => 'fas fa-clock'],
            'scheduled' => ['text' => 'Scheduled', 'class' => 'bg-blue-100 text-blue-800', 'icon' => 'fas fa-calendar-check']
        ];
        
        $status = isset($result->status) ? $result->status : 'scheduled';
        $result->status_info = isset($statusConfig[$status]) ? $statusConfig[$status] : $statusConfig['scheduled'];
        $result->status_text = isset($result->status_info['text']) ? $result->status_info['text'] : 'Scheduled';
        $result->status_class = isset($result->status_info['class']) ? $result->status_info['class'] : 'bg-blue-100 text-blue-800';
        $result->status_icon = isset($result->status_info['icon']) ? $result->status_info['icon'] : 'fas fa-calendar-check';
        
        return $result;
    }
    
    /**
     * Format logo path
     */
    private function formatLogoPath($logoPath)
    {
        if (!$logoPath) {
            return null;
        }
        
        if (str_starts_with($logoPath, 'http')) {
            return $logoPath;
        } elseif (!str_contains($logoPath, '/')) {
            return asset('storage/school_logos/' . $logoPath);
        } else {
            return asset($logoPath);
        }
    }
    
    /**
     * Download scoresheet
     */
    public function downloadScoresheet($id)
    {
        try {
            $result = MatchResult::findOrFail($id);
            
            if (!$result->scoresheet) {
                return redirect()->back()->with('error', 'Scoresheet not available');
            }
            
            // Cek berbagai kemungkinan path
            $filePath = null;
            $possiblePaths = [
                public_path('uploads/scoresheets/' . $result->scoresheet),
                storage_path('app/public/uploads/scoresheets/' . $result->scoresheet),
                public_path($result->scoresheet),
                storage_path('app/public/' . $result->scoresheet),
            ];
            
            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $filePath = $path;
                    break;
                }
            }
            
            if (!$filePath) {
                Log::error('Scoresheet file not found in any path for result: ' . $id);
                return redirect()->back()->with('error', 'Scoresheet file not found');
            }
            
            $originalName = isset($result->scoresheet_original_name) ? $result->scoresheet_original_name : 
                           'scoresheet_' . $result->id . '_' . date('Ymd') . '.xlsx';
            
            return response()->download($filePath, $originalName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $originalName . '"'
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error downloading scoresheet: ' . $e->getMessage());
            
            return redirect()->back()->with('error', 'Failed to download scoresheet');
        }
    }
    
    /**
     * Get unique seasons untuk filter dropdown (digunakan di controller utama)
     */
    private function getSeasonsList()
    {
        try {
            $seasons = MatchResult::select('season')
                ->distinct()
                ->whereNotNull('season')
                ->where('season', '!=', '')
                ->orderBy('season', 'desc')
                ->pluck('season')
                ->values();
            
            // Jika tidak ada season, buat default
            if ($seasons->isEmpty()) {
                $seasons = collect([date('Y'), date('Y') - 1]);
            }
            
            return $seasons;
            
        } catch (\Exception $e) {
            Log::error('Error fetching seasons: ' . $e->getMessage());
            return collect([date('Y'), date('Y') - 1]);
        }
    }
    
    /**
     * Get unique series untuk filter dropdown (digunakan di controller utama)
     */
    private function getSeriesList()
    {
        try {
            $series = MatchResult::select('series')
                ->distinct()
                ->whereNotNull('series')
                ->where('series', '!=', '')
                ->orderBy('series')
                ->pluck('series')
                ->values();
            
            return $series;
            
        } catch (\Exception $e) {
            Log::error('Error fetching series: ' . $e->getMessage());
            return collect([]);
        }
    }
    
    /**
     * API Endpoint untuk AJAX requests (jika diperlukan)
     */
    public function apiIndex(Request $request)
    {
        try {
            $result = $this->index($request);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $result['results'],
                    'metadata' => [
                        'total' => $result['results']->total(),
                        'current_page' => $result['results']->currentPage(),
                        'last_page' => $result['results']->lastPage(),
                    ]
                ]);
            }
            
            // Untuk non-AJAX, redirect ke schedule_result page
            return redirect()->route('user.schedule_result', [
                'tab' => 'results',
                'results_series' => $request->input('results_series'),
                'results_season' => $request->input('results_season')
            ]);
            
        } catch (\Exception $e) {
            Log::error('ResultController API Error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load results'
                ], 500);
            }
            
            return redirect()->route('user.schedule_result')
                ->with('error', 'Failed to load results');
        }
    }
}