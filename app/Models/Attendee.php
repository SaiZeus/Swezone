<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 
        'ticket_category_id', 
        'promo_code_id',
        'discount_amount',
        'full_name', 
        'father_name',
        'email', 
        'phone', 
        'viber',
        'emergency_contact',
        'nrc_passport', 
        'nationality', 
        'country',
        'gender',
        'date_of_birth',
        'bib_name',
        'tshirt_size', 
        'blood_type',
        'has_medical_condition',
        'medical_details',
        'itra',
        'itra_details',
        'address',
        'experience',
        'ticket_uuid',
        'ticket_code',
        'user_code'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class);
    }

    public function promoCode()
    {
        return $this->belongsTo(PromoCode::class);
    }
}