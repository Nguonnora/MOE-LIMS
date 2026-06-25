<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id', 'invoice_number', 'issue_date', 'due_date',
        'subtotal', 'tax', 'discount', 'total', 'notes', 'pdf_path'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'due_date' => 'date',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public static function generateInvoiceNumber()
    {
        $last = self::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->invoice_number, 4)) + 1 : 1;
        return 'INV-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}