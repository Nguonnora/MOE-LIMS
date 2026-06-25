<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id', 'report_number', 'report_date',
        'executive_summary', 'methodology', 'conclusions',
        'recommendations', 'generated_by', 'pdf_path'
    ];

    protected $casts = [
        'report_date' => 'date',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function generator()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public static function generateReportNumber()
    {
        $last = self::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->report_number, 4)) + 1 : 1;
        return 'RPT-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}