<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'district_code', 'name_kh', 'name_en', 'name'];

    public function district()
    {
        return $this->belongsTo(District::class, 'district_code', 'code');
    }

    public function villages()
    {
        return $this->hasMany(Village::class, 'commune_code', 'code');
    }
}