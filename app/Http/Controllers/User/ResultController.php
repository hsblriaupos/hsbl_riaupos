<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MatchData;
use App\Models\MatchResult;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResultController extends Controller
{
    /**
     * Display match results page
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $series = $request->input('series');
        $season = $request->input('season');
        $phase = $request->input('phase');
        
        // Base query for completed matches
        $query = MatchResult::query()
            ->where('status', 'completed')
            ->with(['team1', 'team2'])
            ->orderBy('match_date', 'desc');
        
        // Apply filters
        if ($series) {
            $query->where('series', 'like', '%' . $series . '%');
        }
        
        if ($season) {
            $query->where('season', $season);
        }
        
        if ($phase) {
            $query->where('phase', $phase);
        }
        
        // Get paginated results
        $matches = $query->paginate(12);
        
        // Get filter options
        $seriesList = MatchResult::distinct()->whereNotNull('series')->pluck('series');
        $seasons = MatchResult::distinct()->whereNotNull('season')->orderBy('season', 'desc')->pluck('season');
        $phases = MatchResult::distinct()->whereNotNull('phase')->pluck('phase');
        
        // Calculate statistics
        $totalMatches = MatchResult::where('status', 'completed')->count();
        $highestScoring = MatchResult::where('status', 'completed')
            ->orderBy(DB::raw('score_1 + score_2'), 'desc')
            ->first();
        
        return view('user.publication.schedule_result', [
            'completedMatches' => $matches,
            'upcomingMatches' => collect(), // Empty for results tab
            'liveMatches' => collect(), // Empty for results tab
            'seriesList' => $seriesList,
            'seasons' => $seasons,
            'phases' => $phases,
            'totalMatches' => $totalMatches,
            'highestScoring' => $highestScoring,
            'activeTab' => 'results', // Set results as active
        ]);
    }
    
    /**
     * Display match result details
     */
    public function show($id)
    {
        $match = MatchResult::with(['team1', 'team2'])->findOrFail($id);
        
        // Get related matches (same series/season)
        $relatedMatches = MatchResult::where('id', '!=', $id)
            ->where('series', $match->series)
            ->where('season', $match->season)
            ->where('status', 'completed')
            ->orderBy('match_date', 'desc')
            ->limit(4)
            ->get();
        
        // Calculate head-to-head stats
        $headToHead = MatchResult::where('status', 'completed')
            ->where(function($query) use ($match) {
                $query->where('team1_id', $match->team1_id)
                      ->where('team2_id', $match->team2_id)
                      ->orWhere('team1_id', $match->team2_id)
                      ->where('team2_id', $match->team1_id);
            })
            ->get();
        
        return view('user.publication.result_detail', [
            'match' => $match,
            'relatedMatches' => $relatedMatches,
            'headToHead' => $headToHead,
        ]);
    }
    
    /**
     * Get match results via AJAX
     */
    public function getResults(Request $request)
    {
        $query = MatchResult::query()
            ->where('status', 'completed')
            ->with(['team1', 'team2'])
            ->orderBy('match_date', 'desc');
        
        // Apply search filter
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('team1', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('team2', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })->orWhere('series', 'like', '%' . $search . '%')
                  ->orWhere('competition', 'like', '%' . $search . '%')
                  ->orWhere('phase', 'like', '%' . $search . '%');
            });
        }
        
        // Apply series filter
        if ($request->has('series') && $request->input('series') !== 'all') {
            $query->where('series', $request->input('series'));
        }
        
        // Apply season filter
        if ($request->has('season') && $request->input('season') !== 'all') {
            $query->where('season', $request->input('season'));
        }
        
        $matches = $query->paginate(9);
        
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'matches' => $matches,
                'html' => view('user.publication.partials.result_cards', ['matches' => $matches])->render()
            ]);
        }
        
        return view('user.publication.partials.result_cards', ['matches' => $matches]);
    }
    
    /**
     * Download scoresheet
     */
    public function downloadScoresheet($id)
    {
        $match = MatchResult::findOrFail($id);
        
        if (!$match->scoresheet) {
            return redirect()->back()->with('error', 'Scoresheet not available.');
        }
        
        $path = storage_path('app/public/uploads/scoresheets/' . $match->scoresheet);
        
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File not found.');
        }
        
        return response()->download($path, "scoresheet-{$match->id}.pdf");
    }
}