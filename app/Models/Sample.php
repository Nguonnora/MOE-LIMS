<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sample extends Model
{
    use HasFactory;

    protected $fillable = [
        'work_order_id',
        'sample_code',
        'sample_type',
        'sample_description',
        'sampling_date',
        'province_id',
        'district_id',
        'commune_id',
        'village_id',
        'coordinate_system',
        'coordinate_x',
        'coordinate_y',
        'status'
    ];

    protected $casts = [
        'sampling_date' => 'date',
    ];

    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function commune()
    {
        return $this->belongsTo(Commune::class);
    }

    public function village()
    {
        return $this->belongsTo(Village::class);
    }

    public function tests()
    {
        return $this->hasMany(SampleTest::class);
    }

    public static function generateSampleCode($workOrderId, $sequence)
    {
        $wo = WorkOrder::find($workOrderId);
        if (!$wo) {
            return 'S-' . str_pad($workOrderId, 6, '0', STR_PAD_LEFT);
        }
        // If amount_of_sample == 1, sample code = work order number
        if ($wo->amount_of_sample == 1) {
            return $wo->wo_number;
        }
        // Otherwise, append -01, -02, etc.
        return $wo->wo_number . '-' . str_pad($sequence, 2, '0', STR_PAD_LEFT);
    }
}