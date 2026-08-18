<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'order_number', 
        'total_amount', 
        'payment_method', 
        'payment_status', 
        'payment_proof'
    ];

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