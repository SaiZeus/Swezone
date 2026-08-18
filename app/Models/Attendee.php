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
        'email', 
        'phone', 
        'nrc_passport', 
        'nationality', 
        'tshirt_size', 
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