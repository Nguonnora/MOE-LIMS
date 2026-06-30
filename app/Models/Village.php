<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Village extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'commune_code', 'name_kh', 'name_en', 'name'];

    public function commune()
    {
        return $this->belongsTo(Commune::class, 'commune_code', 'code');
    }
}