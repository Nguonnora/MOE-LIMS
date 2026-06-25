<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestParameter extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'category', 'unit', 'method',
        'reference_method', 'detection_limit', 'quantification_limit',
        'accreditation', 'is_subcontracted', 'default_price', 'description'
    ];

    protected $casts = [
        'is_subcontracted' => 'boolean',
    ];
}