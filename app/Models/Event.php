<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'location', 'event_date', 'image', 'status'];

    public function ticketCategories()
    {
        return $this->hasMany(TicketCategory::class);
    }

    public function promoCodes()
    {
        return $this->hasMany(PromoCode::class);
    }
}
