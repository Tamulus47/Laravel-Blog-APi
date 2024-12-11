<?php

namespace App\Http\Controllers;

use App\Services\StatsService;

class StatsController extends Controller
{
    public function index()
    {
        $statsService = new StatsService();
        $statistics = $statsService->getStatistics();

        return response()->json($statistics, 200);
    }
}
