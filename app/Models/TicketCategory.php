<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketCategory extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'name', 'local_price', 'foreign_price', 'capacity', 'tickets_sold'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }
}
