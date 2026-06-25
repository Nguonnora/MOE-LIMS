<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'organization',
        'address', 'city', 'country', 'tax_id',
        'notes', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function getFullAddressAttribute()
    {
        $parts = array_filter([$this->address, $this->city, $this->country]);
        return implode(', ', $parts);
    }

    public function getDisplayNameAttribute()
    {
        $name = $this->name;
        if ($this->organization) {
            $name .= ' (' . $this->organization . ')';
        }
        return $name;
    }
}