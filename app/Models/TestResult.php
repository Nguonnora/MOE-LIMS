<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'sample_test_id', 'result_value', 'remarks', 'status',
        'entered_by', 'entered_at', 'approved_by', 'approved_at'
    ];

    protected $casts = [
        'entered_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function sampleTest()
    {
        return $this->belongsTo(SampleTest::class);
    }

    public function enteredBy()
    {
        return $this->belongsTo(User::class, 'entered_by');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}