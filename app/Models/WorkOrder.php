<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'wo_number',
        'reception_date',
        'client_id',
        'project_description',
        'contact_person',
        'phone',
        'purpose_id',
        'priority',
        'sample_matrix',
        'amount_of_sample',
        'status',
        'total_amount',
        'invoice_number',
        'created_by'
    ];

    protected $casts = [
        'reception_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function purpose()
    {
        return $this->belongsTo(Purpose::class);
    }

    public function samples()
    {
        return $this->hasMany(Sample::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function report()
    {
        return $this->hasOne(Report::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function generateWONumber()
    {
        $date = now()->format('ymd');
        $last = self::where('wo_number', 'LIKE', $date . '-%')
                    ->orderBy('wo_number', 'desc')
                    ->first();
        if ($last) {
            $number = intval(substr($last->wo_number, -3)) + 1;
        } else {
            $number = 1;
        }
        return $date . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }

    public function getClientDisplayNameAttribute()
    {
        return $this->client ? $this->client->name : 'N/A';
    }
}