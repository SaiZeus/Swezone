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
    'full_name', 
    'father_name',
    'email', 
    'phone', 
    'viber', // Added
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
    'itra',          // Added
    'itra_details',  // Added
    'address',
    'experience',
    'ticket_uuid'
];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function ticketCategory()
    {
        return $this->belongsTo(TicketCategory::class);
    }
}