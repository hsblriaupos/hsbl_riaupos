<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MatchData;
use App\Models\MatchResult;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ScheduleController extends Controller
{
    /**
     * Display match schedule page - AMBIL DATA DARI match_data
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $series = $request->input('series');
        $season = $request->input('season');
        $date = $request->input('date');
        
        // ========== BASE QUERY: AMBIL DATA DARI match_data ==========
        $query = MatchData::query()
            ->whereIn('status', ['published', 'active', 'publish'])
            ->orderBy('upload_date', 'asc');
        
        // Apply filters
        if ($series) {
            $query->where('series_name', 'like', '%' . $series . '%');
        }
        
        if ($season) {
            // Jika match_data tidak punya season, kita bisa filter berdasarkan tahun dari upload_date
            $query->whereYear('upload_date', $season);
        }
        
        if ($date) {
            $query->whereDate('upload_date', $date);
        }
        
        // Get matches
        $matches = $query->get();
        
        // Transform data untuk ditampilkan seperti format match_result
        $transformedMatches = $matches->map(function($match) {
            return (object) [
                'id' => $match->id,
                'match_data_id' => $match->id,
                'match_date' => $match->upload_date,
                'season' => $match->upload_date ? $match->upload_date->format('Y') : date('Y'),
                'competition' => $match->main_title ?? 'HSBL Match',
                'competition_type' => 'Basketball',
                'series' => $match->series_name ?? 'Regular Series',
                'phase' => 'Group Stage',
                'team1_id' => null,
                'team2_id' => null,
                'team1_name' => $this->extractTeamName($match->main_title ?? '', 1),
                'team2_name' => $this->extractTeamName($match->main_title ?? '', 2),
                'score_1' => null,
                'score_2' => null,
                'status' => $this->mapStatus($match->status),
                'scoresheet' => null,
                'venue' => $this->extractVenue($match->caption ?? ''),
                'created_at' => $match->created_at,
                'updated_at' => $match->updated_at,
                'team1' => null,
                'team2' => null,
                'source' => 'match_data',
                'has_layout_image' => !empty($match->layout_image),
                'layout_image_url' => $match->layout_image ? 
                    asset('uploads/layouts/' . $match->layout_image) : null,
                'caption' => $match->caption,
            ];
        });
        
        // Group matches by date
        $groupedMatches = $transformedMatches->groupBy(function($item) {
            return Carbon::parse($item->match_date)->format('Y-m-d');
        });
        
        // Get filter options dari match_data
        $seriesList = MatchData::distinct()
            ->whereNotNull('series_name')
            ->where('series_name', '!=', '')
            ->pluck('series_name');
            
        // Seasons dari tahun upload_date
        $seasons = MatchData::selectRaw('YEAR(upload_date) as year')
            ->distinct()
            ->whereNotNull('upload_date')
            ->orderBy('year', 'desc')
            ->pluck('year');
        
        // Get current and next 7 days for date filter
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->addDays($i);
            $dates[$date->format('Y-m-d')] = $date->format('D, j M');
        }
        
        // Get live matches dari match_result (jika ada)
        $liveMatches = MatchResult::where('status', 'live')
            ->with(['team1', 'team2'])
            ->orderBy('match_date', 'desc')
            ->get();
        
        return view('user.publication.schedule_result', [
            'upcomingMatches' => $transformedMatches,
            'completedMatches' => collect(), // Empty for schedule tab
            'liveMatches' => $liveMatches,
            'groupedMatches' => $groupedMatches,
            'seriesList' => $seriesList,
            'seasons' => $seasons,
            'dates' => $dates,
            'activeTab' => 'schedule',
            'totalMatches' => $transformedMatches->count(),
        ]);
    }
    
    /**
     * Display match schedule details
     */
    public function show($id)
    {
        // Coba ambil dari match_data terlebih dahulu
        $matchData = MatchData::find($id);
        
        if ($matchData) {
            // Transform data dari match_data
            $match = (object) [
                'id' => $matchData->id,
                'match_data_id' => $matchData->id,
                'match_date' => $matchData->upload_date,
                'season' => $matchData->upload_date ? $matchData->upload_date->format('Y') : date('Y'),
                'competition' => $matchData->main_title ?? 'HSBL Match',
                'competition_type' => 'Basketball',
                'series' => $matchData->series_name ?? 'Regular Series',
                'phase' => 'Group Stage',
                'team1_id' => null,
                'team2_id' => null,
                'team1_name' => $this->extractTeamName($matchData->main_title ?? '', 1),
                'team2_name' => $this->extractTeamName($matchData->main_title ?? '', 2),
                'score_1' => null,
                'score_2' => null,
                'status' => $this->mapStatus($matchData->status),
                'scoresheet' => null,
                'venue' => $this->extractVenue($matchData->caption ?? ''),
                'created_at' => $matchData->created_at,
                'updated_at' => $matchData->updated_at,
                'team1' => null,
                'team2' => null,
                'source' => 'match_data',
                'has_layout_image' => !empty($matchData->layout_image),
                'layout_image_url' => $matchData->layout_image ? 
                    asset('uploads/layouts/' . $matchData->layout_image) : null,
                'caption' => $matchData->caption,
            ];
            
            // Get related matches (same series)
            $upcomingSeriesMatches = MatchData::where('id', '!=', $id)
                ->where('series_name', $matchData->series_name)
                ->whereIn('status', ['published', 'active', 'publish'])
                ->orderBy('upload_date', 'asc')
                ->limit(6)
                ->get()
                ->map(function($relatedMatch) {
                    return (object) [
                        'id' => $relatedMatch->id,
                        'match_date' => $relatedMatch->upload_date,
                        'competition' => $relatedMatch->main_title,
                        'team1_name' => $this->extractTeamName($relatedMatch->main_title ?? '', 1),
                        'team2_name' => $this->extractTeamName($relatedMatch->main_title ?? '', 2),
                        'venue' => $this->extractVenue($relatedMatch->caption ?? ''),
                    ];
                });
            
            return view('user.publication.schedule_detail', [
                'match' => $match,
                'matchData' => $matchData,
                'upcomingSeriesMatches' => $upcomingSeriesMatches,
            ]);
        }
        
        // Jika tidak ditemukan di match_data, coba dari match_result
        $match = MatchResult::with(['team1', 'team2'])->findOrFail($id);
        
        // Get venue information from match_data if available
        $matchData = MatchData::where('id', $match->match_data_id)->first();
        
        // Get upcoming matches in same series
        $upcomingSeriesMatches = MatchResult::where('id', '!=', $id)
            ->where('series', $match->series)
            ->where('status', 'scheduled')
            ->orderBy('match_date', 'asc')
            ->limit(6)
            ->get();
        
        return view('user.publication.schedule_detail', [
            'match' => $match,
            'matchData' => $matchData,
            'upcomingSeriesMatches' => $upcomingSeriesMatches,
        ]);
    }
    
    /**
     * Get schedule via AJAX - AMBIL DARI match_data
     */
    public function getSchedule(Request $request)
    {
        $query = MatchData::query()
            ->whereIn('status', ['published', 'active', 'publish'])
            ->orderBy('upload_date', 'asc');
        
        // Apply search filter
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('main_title', 'like', '%' . $search . '%')
                  ->orWhere('series_name', 'like', '%' . $search . '%')
                  ->orWhere('caption', 'like', '%' . $search . '%');
            });
        }
        
        // Apply series filter
        if ($request->has('series') && $request->input('series') !== 'all') {
            $query->where('series_name', $request->input('series'));
        }
        
        // Apply date range filter
        if ($request->has('date_range')) {
            $dates = explode(' to ', $request->input('date_range'));
            if (count($dates) === 2) {
                $query->whereBetween('upload_date', [$dates[0], $dates[1]]);
            }
        }
        
        $matches = $query->paginate(12);
        
        // Transform data untuk view
        $transformedMatches = $matches->getCollection()->map(function($match) {
            return (object) [
                'id' => $match->id,
                'match_date' => $match->upload_date,
                'series' => $match->series_name ?? 'Regular Series',
                'competition' => $match->main_title ?? 'HSBL Match',
                'competition_type' => 'Basketball',
                'phase' => 'Group Stage',
                'team1_id' => null,
                'team2_id' => null,
                'team1_name' => $this->extractTeamName($match->main_title ?? '', 1),
                'team2_name' => $this->extractTeamName($match->main_title ?? '', 2),
                'score_1' => null,
                'score_2' => null,
                'status' => $this->mapStatus($match->status),
                'scoresheet' => null,
                'venue' => $this->extractVenue($match->caption ?? ''),
                'created_at' => $match->created_at,
                'updated_at' => $match->updated_at,
                'team1' => null,
                'team2' => null,
                'has_layout_image' => !empty($match->layout_image),
                'layout_image_url' => $match->layout_image ? 
                    asset('uploads/layouts/' . $match->layout_image) : null,
            ];
        });
        
        $matches->setCollection($transformedMatches);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'matches' => $matches,
                'html' => view('user.publication.partials.schedule_cards', ['matches' => $matches])->render()
            ]);
        }
        
        return view('user.publication.partials.schedule_cards', ['matches' => $matches]);
    }
    
    /**
     * Get today's matches - AMBIL DARI match_data
     */
    public function getTodayMatches()
    {
        $today = Carbon::today()->format('Y-m-d');
        
        $matches = MatchData::whereDate('upload_date', $today)
            ->whereIn('status', ['published', 'active', 'publish'])
            ->orderBy('upload_date', 'asc')
            ->get()
            ->map(function($match) {
                return (object) [
                    'id' => $match->id,
                    'match_date' => $match->upload_date,
                    'competition' => $match->main_title ?? 'HSBL Match',
                    'team1_name' => $this->extractTeamName($match->main_title ?? '', 1),
                    'team2_name' => $this->extractTeamName($match->main_title ?? '', 2),
                    'venue' => $this->extractVenue($match->caption ?? ''),
                    'status' => $this->mapStatus($match->status),
                ];
            });
        
        return response()->json([
            'success' => true,
            'matches' => $matches,
            'date' => Carbon::today()->format('l, F j, Y')
        ]);
    }
    
    /**
     * Get match calendar (for calendar view) - AMBIL DARI match_data
     */
    public function getCalendar(Request $request)
    {
        $year = $request->input('year', date('Y'));
        $month = $request->input('month', date('m'));
        
        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = Carbon::create($year, $month, 1)->endOfMonth();
        
        $matches = MatchData::whereBetween('upload_date', [$startDate, $endDate])
            ->whereIn('status', ['published', 'active', 'publish'])
            ->orderBy('upload_date', 'asc')
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->upload_date)->format('Y-m-d');
            })
            ->map(function($dayMatches) {
                return $dayMatches->map(function($match) {
                    return (object) [
                        'id' => $match->id,
                        'match_date' => $match->upload_date,
                        'competition' => $match->main_title ?? 'HSBL Match',
                        'team1_name' => $this->extractTeamName($match->main_title ?? '', 1),
                        'team2_name' => $this->extractTeamName($match->main_title ?? '', 2),
                        'venue' => $this->extractVenue($match->caption ?? ''),
                    ];
                });
            });
        
        return response()->json([
            'success' => true,
            'matches' => $matches,
            'month' => $startDate->format('F Y'),
            'days_in_month' => $startDate->daysInMonth,
            'first_day' => $startDate->dayOfWeek
        ]);
    }
    
    /**
     * Set match reminder
     */
    public function setReminder(Request $request, $id)
    {
        // Cari match di match_data terlebih dahulu
        $matchData = MatchData::find($id);
        
        if ($matchData) {
            $matchDate = $matchData->upload_date;
            $teamNames = $this->extractTeamName($matchData->main_title ?? '', 1) . ' vs ' . 
                         $this->extractTeamName($matchData->main_title ?? '', 2);
        } else {
            // Jika tidak ditemukan di match_data, cari di match_result
            $match = MatchResult::findOrFail($id);
            $matchDate = $match->match_date;
            $teamNames = ($match->team1->school_name ?? 'Team A') . ' vs ' . 
                        ($match->team2->school_name ?? 'Team B');
        }
        
        $request->validate([
            'email' => 'required|email',
            'reminder_time' => 'required|in:15,30,60,120'
        ]);
        
        $reminderTime = (int) $request->input('reminder_time');
        $reminderDate = Carbon::parse($matchDate)->subMinutes($reminderTime);
        
        // Store reminder in session
        $reminders = session('match_reminders', []);
        $reminders[$id] = [
            'match_id' => $id,
            'email' => $request->input('email'),
            'reminder_time' => $reminderTime,
            'reminder_date' => $reminderDate,
            'match_date' => $matchDate,
            'teams' => $teamNames
        ];
        
        session(['match_reminders' => $reminders]);
        
        return response()->json([
            'success' => true,
            'message' => "Reminder set for {$reminderTime} minutes before the match",
            'reminder_date' => $reminderDate->format('Y-m-d H:i:s')
        ]);
    }
    
    /**
     * Helper untuk ekstrak nama tim dari title
     */
    private function extractTeamName($title, $teamNumber = 1)
    {
        // Jika title mengandung "vs", split berdasarkan "vs"
        if (strpos($title, ' vs ') !== false) {
            $parts = explode(' vs ', $title);
            if ($teamNumber == 1 && isset($parts[0])) {
                return trim($parts[0]);
            } elseif ($teamNumber == 2 && isset($parts[1])) {
                return trim($parts[1]);
            }
        }
        
        // Jika title mengandung "-", split berdasarkan "-"
        if (strpos($title, ' - ') !== false) {
            $parts = explode(' - ', $title);
            if ($teamNumber == 1 && isset($parts[0])) {
                return trim($parts[0]);
            } elseif ($teamNumber == 2 && isset($parts[1])) {
                return trim($parts[1]);
            }
        }
        
        // Jika tidak ada format pemisah, coba tebak berdasarkan kata
        $words = explode(' ', $title);
        if (count($words) >= 4) {
            if ($teamNumber == 1) {
                return trim($words[0] . ' ' . $words[1]);
            } else {
                return trim($words[2] . ' ' . $words[3]);
            }
        }
        
        // Default fallback
        return $teamNumber == 1 ? 'Team A' : 'Team B';
    }
    
    /**
     * Helper untuk ekstrak venue dari caption
     */
    private function extractVenue($caption)
    {
        // Cari kata kunci venue dalam caption
        $venueKeywords = ['at', 'venue', 'lokasi', 'tempat', 'stadion', 'arena', 'gym'];
        
        foreach ($venueKeywords as $keyword) {
            $position = stripos($caption, $keyword);
            if ($position !== false) {
                // Ambil 50 karakter setelah keyword
                $venue = substr($caption, $position + strlen($keyword), 50);
                return trim(preg_replace('/[^a-zA-Z0-9\s,.-]/', '', $venue));
            }
        }
        
        return 'TBD';
    }
    
    /**
     * Helper untuk mapping status dari match_data ke format yang diharapkan
     */
    private function mapStatus($status)
    {
        $statusMap = [
            'published' => 'upcoming',
            'publish' => 'upcoming',
            'active' => 'upcoming',
            'draft' => 'scheduled',
            'archived' => 'completed',
        ];
        
        return $statusMap[strtolower($status)] ?? 'upcoming';
    }
}