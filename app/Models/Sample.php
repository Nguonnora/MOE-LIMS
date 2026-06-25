<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id', 'sample_code', 'sample_type', 'sample_matrix',
        'sampling_location', 'sampling_date', 'sampling_time',
        'sampling_method', 'container_type', 'preservation_method',
        'received_date', 'received_time', 'received_by',
        'sample_description', 'sample_condition',
        'sample_quantity', 'quantity_unit', 'status'
    ];

    protected $casts = [
        'sampling_date' => 'date',
        'received_date' => 'date',
        'sampling_time' => 'datetime',
        'received_time' => 'datetime',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function tests()
    {
        return $this->hasMany(SampleTest::class);
    }

    public static function generateSampleCode($workOrderId)
    {
        $count = self::where('work_order_id', $workOrderId)->count() + 1;
        return 'S-' . str_pad($workOrderId, 4, '0', STR_PAD_LEFT) . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }
}