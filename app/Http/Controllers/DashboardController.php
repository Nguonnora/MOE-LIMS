<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Models\Sample;
use App\Models\TestResult;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalWorkOrders = WorkOrder::count();
        $totalSamples = Sample::count();
        $pendingApprovals = TestResult::where('status', 'entered')->count();
        $totalRevenue = WorkOrder::sum('total_amount');

        $statusCounts = WorkOrder::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $recentWorkOrders = WorkOrder::with('client', 'samples')
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalWorkOrders',
            'totalSamples',
            'pendingApprovals',
            'totalRevenue',
            'statusCounts',
            'recentWorkOrders'
        ));
    }
}