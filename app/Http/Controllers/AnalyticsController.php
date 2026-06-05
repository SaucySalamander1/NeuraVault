<?php

namespace App\Http\Controllers;

use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function __construct(private AnalyticsService $analyticsService)
    {
    }

    public function index(Request $request)
    {
        $days = $request->query('days', 30);
        $stats = $this->analyticsService->getStats(auth()->id(), $days);

        return view('analytics.index', compact('stats', 'days'));
    }
}