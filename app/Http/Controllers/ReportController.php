<?php

namespace App\Http\Controllers;

use App\Models\WorkOrder;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function generate(WorkOrder $workOrder)
    {
        $allApproved = $workOrder->samples->every(fn($s) => $s->tests->every(fn($t) => $t->result && $t->result->status == 'approved'));
        if (!$allApproved) {
            return redirect()->back()->with('error', 'All tests must be approved.');
        }
        if ($workOrder->report) {
            return $this->download($workOrder);
        }
        $this->reportService->generateReport($workOrder);
        return redirect()->route('samples.show', $workOrder)->with('success', 'Report generated.');
    }

    public function download(WorkOrder $workOrder)
    {
        $report = $workOrder->report;
        if (!$report) abort(404);
        $path = storage_path('app/public/' . $report->pdf_path);
        if (!file_exists($path)) {
            $this->reportService->generateReport($workOrder);
            $report->refresh();
        }
        return response()->download($path, 'report-'.$workOrder->id.'.pdf');
    }
}