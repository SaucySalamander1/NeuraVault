<?php

namespace App\Http\Controllers;

use App\Models\ScanResult;

class SecurityController extends Controller
{
    public function index()
    {
        $findings = ScanResult::whereHas('document', function ($q) {
                        $q->where('user_id', auth()->id());
                    })
                    ->orderBy('severity', 'desc')
                    ->orderBy('created_at', 'desc')
                    ->paginate(50);

        $stats = [
            'total_threats' => $findings->total(),
            'critical' => ScanResult::whereHas('document', fn($q) => $q->where('user_id', auth()->id()))
                                     ->where('severity', 'critical')
                                     ->count(),
            'high' => ScanResult::whereHas('document', fn($q) => $q->where('user_id', auth()->id()))
                                ->where('severity', 'high')
                                ->count(),
            'medium' => ScanResult::whereHas('document', fn($q) => $q->where('user_id', auth()->id()))
                                  ->where('severity', 'medium')
                                  ->count(),
            'low' => ScanResult::whereHas('document', fn($q) => $q->where('user_id', auth()->id()))
                              ->where('severity', 'low')
                              ->count(),
        ];

        return view('security.index', compact('findings', 'stats'));
    }
}