<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'location',
        'event_date',
        'image',
        'status',
        'creator_name',
        'creator_phone',
        'creator_email',
        'overall_capacity',
        'english_waiver',
        'burmese_waiver',
        'english_race_guide',
    'burmese_race_guide',
        'enabled_fields',
        'enable_bib_number',
        'share_bib_prefix',
        'event_bib_prefix',
        'event_bib_start_number',
    ];

    protected $casts = [
        'enabled_fields' => 'array',
        'enable_bib_number' => 'boolean',
        'share_bib_prefix' => 'boolean',
    ];

    public function ticketCategories()
    {
        return $this->hasMany(TicketCategory::class);
    }

    public function promoCodes()
    {
        return $this->hasMany(PromoCode::class);
    }

    public function items()
    {
        return $this->hasMany(EventItem::class);
    }
}