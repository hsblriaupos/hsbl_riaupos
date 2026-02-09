<?php

namespace App\Http\Controllers\Publication;

use App\Http\Controllers\Controller;

class StatisticsController extends Controller
{
    /**
     * Display statistics page (Coming Soon for Admin)
     */
    public function index()
    {
        // SESUAIKAN DENGAN PATH YANG BENAR
        return view('admin.statisctics.statistic');
    }
    
    /**
     * Get match statistics data for charts (API)
     */
    public function getMatchStatistics()
    {
        // Untuk development - return sample data
        return response()->json([
            'labels' => ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'],
            'datasets' => [
                [
                    'label' => 'Total Points',
                    'data' => [120, 135, 150, 145, 160, 155],
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                    'borderColor' => 'rgb(59, 130, 246)',
                    'borderWidth' => 2
                ],
                [
                    'label' => 'Matches Played',
                    'data' => [8, 10, 12, 11, 13, 12],
                    'backgroundColor' => 'rgba(16, 185, 129, 0.5)',
                    'borderColor' => 'rgb(16, 185, 129)',
                    'borderWidth' => 2
                ]
            ]
        ]);
    }
    
    /**
     * Get team statistics data (API)
     */
    public function getTeamStatistics()
    {
        // Untuk development - return sample data
        return response()->json([
            'total' => 0,
            'withPlayers' => 0,
            'withoutPlayers' => 0,
            'teams' => []
        ]);
    }
}