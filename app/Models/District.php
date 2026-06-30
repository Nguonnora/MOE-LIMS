<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'province_code', 'name_kh', 'name_en', 'name'];

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'code');
    }

    public function communes()
    {
        return $this->hasMany(Commune::class, 'district_code', 'code');
    }
}