<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_id', 'test_code', 'test_name', 'test_category',
        'parameter', 'unit', 'method', 'reference_method',
        'detection_limit', 'quantification_limit', 'accreditation',
        'is_subcontracted', 'subcontracted_lab',
        'analysis_start_date', 'analysis_completion_date', 'price'
    ];

    protected $casts = [
        'analysis_start_date' => 'date',
        'analysis_completion_date' => 'date',
        'is_subcontracted' => 'boolean',
    ];

    public function sample()
    {
        return $this->belongsTo(Sample::class);
    }

    public function result()
    {
        return $this->hasOne(TestResult::class);
    }

    public function testParameter()
    {
    return $this->belongsTo(TestParameter::class);
    }
}