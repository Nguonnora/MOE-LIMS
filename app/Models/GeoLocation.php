<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeoLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'level', 'code', 'parent_code', 'name_kh', 'name_en', 'name'
    ];
}