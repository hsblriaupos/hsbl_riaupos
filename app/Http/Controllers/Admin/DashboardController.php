<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\TeamList;
use App\Models\MatchData;
use App\Models\MatchResult;
use App\Models\School;
use App\Models\News;
use App\Models\Award;
use App\Models\Sponsor;      // <-- PASTIKAN INI ADA
use App\Models\PlayerList;
use App\Models\DancerList;
use App\Models\OfficialList;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // CEK MATCH RESULTS
        $matchResultExists = Schema::hasTable('match_results') && MatchResult::count() > 0;
        
        $data = [
            // TEAM STATS
            'total_teams' => TeamList::count(),
            'verified_teams' => TeamList::where('verification_status', 'verified')->count(),
            'pending_verification' => TeamList::where('verification_status', 'unverified')->count(),
            
            'locked_teams' => TeamList::where('locked_status', 'locked')->count(),
            'unlocked_teams' => TeamList::where('locked_status', 'unlocked')->count(),
            
            'paid_teams' => TeamList::where('payment_status', 'paid')->count(),
            'pending_payment' => TeamList::where('payment_status', 'pending')->count(),
            'failed_payment' => TeamList::where('payment_status', 'failed')->count(),
            'leader_paid' => TeamList::where('is_leader_paid', 1)->count(),
            
            'basket_putra' => TeamList::where('team_category', 'Basket Putra')->count(),
            'basket_putri' => TeamList::where('team_category', 'Basket Putri')->count(),
            'dancer_teams' => TeamList::where('team_category', 'Dancer')->count(),
            
            // SCHOOL STATS
            'total_schools' => School::count(),
            
            // MEMBER STATS
            'total_players' => PlayerList::count(),
            'total_dancers' => DancerList::count(),
            'total_officials' => OfficialList::count(),
            
            // MATCH STATS
            'upcoming_matches' => MatchData::where('upload_date', '>=', Carbon::today())
                                          ->where('status', 'publish')
                                          ->count(),
            'total_matches' => MatchData::where('status', 'publish')->count(),
            'completed_matches' => $matchResultExists ? MatchResult::count() : 0,
            
            // RECENT MATCHES
            'recent_matches' => MatchData::where('status', 'publish')
                                       ->where('upload_date', '>=', Carbon::today())
                                       ->orderBy('upload_date')
                                       ->take(5)
                                       ->get(),
            
            // CONTENT STATS - LENGKAP!
            'total_news' => News::count(),
            'total_awards' => Award::count(),
            'total_sponsors' => Sponsor::count(),  // <-- INI YANG KURANG!
            
            // RECENT DATA
            'recent_teams' => TeamList::with('school')
                                    ->latest()
                                    ->take(5)
                                    ->get(),
            
            'recent_news' => News::latest()
                                ->take(5)
                                ->get(),
            
            // GROWTH
            'team_growth' => TeamList::where('created_at', '>=', Carbon::now()->subMonth())->count(),
        ];

        // Hitung persentase
        $lastMonthTeams = TeamList::where('created_at', '<', Carbon::now()->subMonth())->count();
        $data['team_growth_percentage'] = $this->calculateGrowth($lastMonthTeams, $data['team_growth']);
        
        $data['verification_percentage'] = $data['total_teams'] > 0 
            ? round(($data['verified_teams'] / $data['total_teams']) * 100, 1) 
            : 0;
            
        $data['payment_percentage'] = $data['total_teams'] > 0
            ? round(($data['paid_teams'] / $data['total_teams']) * 100, 1)
            : 0;

        return view('admin.dashboard', compact('data'));
    }

    private function calculateGrowth($previous, $current)
    {
        if ($previous == 0) return 100;
        return round(($current - $previous) / $previous * 100, 1);
    }
}