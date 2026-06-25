<?php

namespace App\Services;

use App\Models\WorkOrder;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ReportService
{
    public function generateReport(WorkOrder $workOrder)
    {
        $workOrder->load('samples.tests.result');
        $pdf = Pdf::loadView('reports.pdf', [
            'workOrder' => $workOrder,
            'samples' => $workOrder->samples,
            'generated_at' => now(),
        ]);
        $fileName = 'reports/report-' . $workOrder->id . '-' . time() . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        return Report::create([
            'work_order_id' => $workOrder->id,
            'report_number' => Report::generateReportNumber(),
            'report_date' => now(),
            'generated_by' => Auth::id(),
            'pdf_path' => $fileName,
        ]);
    }
}