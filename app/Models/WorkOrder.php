<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'wo_number', 'client_name', 'client_email', 'client_phone',
        'client_organization', 'project_description', 'order_date',
        'expected_completion_date', 'priority', 'status', 'total_amount',
        'invoice_number', 'created_by'
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_completion_date' => 'date',
    ];

    public function samples()
    {
        return $this->hasMany(Sample::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateWONumber()
    {
        $last = self::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->wo_number, 3)) + 1 : 1;
        return 'WO-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}