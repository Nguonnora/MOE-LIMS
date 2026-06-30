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

    // ---- Relationships ----
    public function workOrder()
    {
        return $this->belongsTo(WorkOrder::class);
    }

    // public function province()
    // {
    //     return $this->belongsTo(Province::class);
    // }

    // public function district()
    // {
    //     return $this->belongsTo(District::class);
    // }

    // public function commune()
    // {
    //     return $this->belongsTo(Commune::class);
    // }

    // public function village()
    // {
    //     return $this->belongsTo(Village::class);
    // }

    public function tests()
    {
        return $this->hasMany(SampleTest::class);
    }

    // ---- Generate sample code ----
    public static function generateSampleCode($workOrderId, $sequence)
    {
        $wo = WorkOrder::find($workOrderId);
        if (!$wo) {
            return 'S-' . str_pad($workOrderId, 6, '0', STR_PAD_LEFT);
        }

        // If only one sample is expected, the sample code is the work order number itself
        if ($wo->amount_of_sample == 1) {
            return $wo->wo_number;
        }

        $roman = self::toRoman($sequence);
        return $wo->wo_number . '-' . $roman;
    }

    // ---- Convert integer to Roman numeral (1 → I, 2 → II, ...) ----
    public static function toRoman($number)
    {
        if ($number < 1 || $number > 3999) {
            return (string) $number;
        }

        $map = [
            'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400,
            'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40,
            'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1
        ];

        $result = '';
        foreach ($map as $roman => $value) {
            while ($number >= $value) {
                $result .= $roman;
                $number -= $value;
            }
        }
        return $result;
    }
}