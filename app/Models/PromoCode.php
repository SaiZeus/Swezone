<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = ['event_id', 'code', 'discount_type', 'discount_value'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}