<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'wo_number',
        'client_id',          // added
        'client_name',        // kept for fallback
        'client_email',
        'client_phone',
        'client_organization',
        'project_description',
        'order_date',
        'expected_completion_date',
        'priority',
        'status',
        'total_amount',
        'invoice_number',
        'created_by'
    ];

    protected $casts = [
        'order_date' => 'date',
        'expected_completion_date' => 'date',
    ];

    // Relationship with client
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    // Relationship with samples
    public function samples()
    {
        return $this->hasMany(Sample::class);
    }

    // Relationship with invoice
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    // Relationship with report
    public function report()
    {
        return $this->hasOne(Report::class);
    }

    // Relationship with creator user
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Generate work order number
    public static function generateWONumber()
    {
        $last = self::orderBy('id', 'desc')->first();
        $number = $last ? intval(substr($last->wo_number, 3)) + 1 : 1;
        return 'WO-' . str_pad($number, 6, '0', STR_PAD_LEFT);
    }

    // Helper to get client display name (fallback to client_name)
    public function getClientDisplayNameAttribute()
    {
        if ($this->client) {
            return $this->client->display_name;
        }
        return $this->client_name ?? 'N/A';
    }
}