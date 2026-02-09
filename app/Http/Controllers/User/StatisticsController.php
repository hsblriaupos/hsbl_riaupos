<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class StatisticsController extends Controller // KEMBALIKAN KE NAMA SEMULA
{
    /**
     * Display statistics page (Coming Soon)
     */
    public function index()
    {
        return view('user.statistics.statistics');
    }
}