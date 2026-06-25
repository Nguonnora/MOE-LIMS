<?php

namespace App\Services;

use App\Models\WorkOrder;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceService
{
    public function generateInvoice(WorkOrder $workOrder)
    {
        if ($workOrder->invoice) return $workOrder->invoice;

        $samples = $workOrder->samples()->with('tests')->get();
        $subtotal = $workOrder->total_amount;
        $tax = $subtotal * 0.1;
        $total = $subtotal + $tax;

        $data = [
            'workOrder' => $workOrder,
            'samples' => $samples,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
        ];

        $pdf = Pdf::loadView('invoices.pdf', $data);
        $fileName = 'invoices/' . $data['invoice_number'] . '.pdf';
        Storage::disk('public')->put($fileName, $pdf->output());

        $invoice = Invoice::create([
            'work_order_id' => $workOrder->id,
            'invoice_number' => $data['invoice_number'],
            'issue_date' => now(),
            'due_date' => now()->addDays(30),
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
            'pdf_path' => $fileName,
        ]);
        $workOrder->update(['invoice_number' => $data['invoice_number']]);
        return $invoice;
    }
}