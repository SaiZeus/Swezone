<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'ticket_category_id',
        'company_name',
        'code',
        'discount_type',
        'discount_value',
        'max_uses',
        'uses_count',
        'status',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function attendees()
    {
        return $this->hasMany(Attendee::class);
    }

    /**
     * Helper: Check if promo code is valid and available for use
     */
    public function isValidForCategory(?int $categoryId = null): bool
    {
        if ($this->status !== 'active') {
            return false;
        }

        if ($this->max_uses > 0 && $this->uses_count >= $this->max_uses) {
            return false;
        }

        if ($this->ticket_category_id && $categoryId && $this->ticket_category_id != $categoryId) {
            return false;
        }

        return true;
    }
}