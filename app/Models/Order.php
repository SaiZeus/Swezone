<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'reference',
        'order_number', 
        'total_amount', 
        'payment_method', 
        'payment_status', 
        'payment_proof'
    ];

    /**
     * Boot the model to auto-generate a reference code on creation.
     */
    protected static function booted()
    {
        static::creating(function ($order) {
            if (empty($order->reference)) {
                $order->reference = 'SWZ-' . strtoupper(Str::random(8));
            }
        });
    }

    /**
     * Get the route key for the model (replaces ID with reference in URLs).
     */
    public function getRouteKeyName(): string
    {
        return 'reference';
    }

    /**
     * Relationship to Event
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relationship to Attendees
     */
    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }
}