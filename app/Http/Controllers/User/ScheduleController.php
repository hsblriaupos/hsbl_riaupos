<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MatchData;
use App\Models\MatchResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
    /**
     * Menampilkan halaman schedule_result dengan semua data
     */
    public function index(Request $request)
    {
        try {
            // Determine active tab from request
            $activeTab = $request->get('tab', 'schedules');
            
            // Log minimal
            Log::info('Loading schedule_result page', [
                'tab' => $activeTab,
                'series' => $request->input('series'),
                'year' => $request->input('year'),
                'results_series' => $request->input('results_series'),
                'results_season' => $request->input('results_season')
            ]);
            
            // Initialize variables
            $schedules = null;
            $results = null;
            $seriesList = collect();
            $seriesListResults = collect();
            $years = collect();
            $seasons = collect();
            
            // 1. GET SCHEDULES dengan pagination
            $schedulesQuery = MatchData::where(function($query) {
                $query->where('status', 'active')
                      ->orWhere('status', 'published')
                      ->orWhere('status', 'publish')
                      ->orWhereNull('status');
            });
            
            // Filter schedules berdasarkan series
            if ($request->filled('series')) {
                $schedulesQuery->where('series_name', $request->input('series'));
            }
            
            // Filter schedules berdasarkan year
            if ($request->filled('year')) {
                $schedulesQuery->whereYear('upload_date', $request->input('year'));
            }
            
            // Order by upload date (terbaru dulu)
            $schedules = $schedulesQuery->orderBy('upload_date', 'desc')
                ->paginate(8, ['*'], 'schedules_page')
                ->appends([
                    'tab' => 'schedules', 
                    'series' => $request->input('series'),
                    'year' => $request->input('year'),
                    'results_series' => $request->input('results_series'),
                    'results_season' => $request->input('results_season')
                ]);
            
            // Format schedules data
            $schedules->getCollection()->transform(function ($schedule) {
                return $this->formatScheduleData($schedule);
            });
            
            // Get series list for schedule filter
            $seriesList = MatchData::whereNotNull('series_name')
                ->where('series_name', '!=', '')
                ->distinct()
                ->pluck('series_name')
                ->sort()
                ->values();
            
            // Get years for schedule filter
            $years = MatchData::selectRaw('YEAR(upload_date) as year')
                ->whereNotNull('upload_date')
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year')
                ->filter()
                ->values();
            
            // Jika tidak ada data tahun, buat default
            if ($years->isEmpty()) {
                $years = collect([date('Y'), date('Y') - 1]);
            }
            
            // 2. GET RESULTS dengan pagination
            $resultsQuery = MatchResult::orderBy('match_date', 'desc');
            
            // Filter results berdasarkan status
            $resultsQuery->whereIn('status', ['completed', 'done', 'publish', 'live']);
            
            // Filter results berdasarkan series (gunakan results_series untuk menghindari conflict)
            if ($request->filled('results_series')) {
                $resultsQuery->where('series', $request->input('results_series'));
            } elseif ($request->filled('series') && $activeTab === 'results') {
                // Fallback untuk backward compatibility
                $resultsQuery->where('series', $request->input('series'));
            }
            
            // Filter results berdasarkan season
            if ($request->filled('results_season')) {
                $resultsQuery->where('season', $request->input('results_season'));
            } elseif ($request->filled('year') && $activeTab === 'results') {
                // Fallback untuk backward compatibility
                $resultsQuery->where('season', $request->input('year'));
            }
            
            // Execute query dengan pagination
            $results = $resultsQuery->paginate(10, ['*'], 'results_page')
                ->appends([
                    'tab' => 'results',
                    'series' => $request->input('series'),
                    'year' => $request->input('year'),
                    'results_series' => $request->input('results_series'),
                    'results_season' => $request->input('results_season')
                ]);
            
            // Format results data
            $results->getCollection()->transform(function ($result) {
                return $this->formatResultData($result);
            });
            
            // Get series list for results filter
            $seriesListResults = MatchResult::whereNotNull('series')
                ->where('series', '!=', '')
                ->distinct()
                ->pluck('series')
                ->sort()
                ->values();
            
            // Get seasons for results filter
            $seasons = MatchResult::select('season')
                ->whereNotNull('season')
                ->where('season', '!=', '')
                ->distinct()
                ->orderBy('season', 'desc')
                ->pluck('season')
                ->values();
            
            // Jika tidak ada season, buat default
            if ($seasons->isEmpty()) {
                $seasons = collect([date('Y'), date('Y') - 1]);
            }
            
            return view('user.publication.schedule_result', [
                'schedules' => $schedules,
                'results' => $results,
                'seriesList' => $seriesList,
                'seriesListResults' => $seriesListResults,
                'years' => $years,
                'seasons' => $seasons,
                'activeTab' => $activeTab,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error loading schedule_result page: ' . $e->getMessage());
            
            // Return view dengan data kosong
            return view('user.publication.schedule_result', [
                'schedules' => collect([]),
                'results' => collect([]),
                'seriesList' => collect([]),
                'seriesListResults' => collect([]),
                'years' => collect([]),
                'seasons' => collect([]),
                'activeTab' => 'schedules',
            ]);
        }
    }
    
    /**
     * Format data schedule
     */
    private function formatScheduleData($schedule)
    {
        // Format path gambar
        if ($schedule->layout_image) {
            // Cek jika sudah full URL
            if (str_starts_with($schedule->layout_image, 'http')) {
                $schedule->image_url = $schedule->layout_image;
            } 
            // Cek jika hanya nama file
            elseif (!str_contains($schedule->layout_image, '/')) {
                $schedule->image_url = asset('images/schedule/' . $schedule->layout_image);
            }
            // Jika sudah ada path relatif
            else {
                $schedule->image_url = asset($schedule->layout_image);
            }
        } else {
            $schedule->image_url = asset('images/default-schedule.jpg');
        }
        
        // Format tanggal untuk display
        if ($schedule->upload_date) {
            try {
                $schedule->formatted_date = \Carbon\Carbon::parse($schedule->upload_date)
                    ->locale('id')
                    ->translatedFormat('l, j F Y');
            } catch (\Exception $e) {
                $schedule->formatted_date = $schedule->upload_date;
            }
        } else {
            $schedule->formatted_date = 'Date TBD';
        }
        
        return $schedule;
    }
    
    /**
     * Format data result
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
        
        // Format logo paths
        $result->team1_logo = $this->formatLogoPath($result->team1_logo);
        $result->team2_logo = $this->formatLogoPath($result->team2_logo);
        
        // Status text untuk display
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
     * Download scoresheet untuk results
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
     * Download terms and conditions
     */
    public function downloadTerms()
    {
        try {
            $termsPath = public_path('documents/terms-and-conditions.pdf');
            
            if (file_exists($termsPath)) {
                return response()->download($termsPath, 'HSBL-Terms-and-Conditions.pdf');
            } else {
                return redirect()->route('user.schedule_result')
                    ->with('error', 'Terms and conditions document not found.');
            }
            
        } catch (\Exception $e) {
            Log::error('Error downloading terms: ' . $e->getMessage());
            
            return redirect()->route('user.schedule_result')
                ->with('error', 'Failed to download terms and conditions.');
        }
    }
}