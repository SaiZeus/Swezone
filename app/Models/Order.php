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
     * Boot the model to auto-generate a reference code on creation 
     * and auto-generate ticket codes when an order is marked as paid.
     */
    protected static function booted()
    {
        static::creating(function ($order) {
            if (empty($order->reference)) {
                $order->reference = 'SWZ-' . strtoupper(Str::random(8));
            }
        });

        // Automatically generate ticket codes and bibs if payment status changes to paid
        static::updated(function ($order) {
            if ($order->isDirty('payment_status') && $order->payment_status === 'paid') {
                $order->load('attendees.ticketCategory.event');

                foreach ($order->attendees as $attendee) {
                    $category = $attendee->ticketCategory;
                    $event = $category->event ?? null;

                    if ($category && empty($attendee->ticket_code) && $event) {
                        $category->increment('tickets_sold');

                        // 1. Generate Bib Name if enabled
                        $generatedBib = $attendee->bib_name;
                        if (empty($generatedBib) && $event->enable_bib_number) {
                            $nextNum = 1; // Default fallback initialization to prevent undefined variable errors
                            
                            if ($event->share_bib_prefix) {
                                $prefix = !empty($event->event_bib_prefix) ? $event->event_bib_prefix : 'BIB';
                                $startNum = $event->event_bib_start_number ?? 1;
                                
                                $dbBibs = Attendee::whereHas('ticketCategory', function ($q) use ($event) {
                                        $q->where('event_id', $event->id);
                                    })
                                    ->whereNotNull('bib_name')
                                    ->pluck('bib_name')
                                    ->map(function ($bib) use ($prefix) {
                                        $clean = str_replace(strtoupper($prefix) . '-', '', strtoupper($bib));
                                        return is_numeric($clean) ? (int)$clean : null;
                                    })
                                    ->filter()
                                    ->toArray();

                                sort($dbBibs);
                                $nextNum = $startNum;
                                foreach ($dbBibs as $num) {
                                    if ($num == $nextNum) {
                                        $nextNum++;
                                    } elseif ($num > $nextNum) {
                                        break;
                                    }
                                }
                            } else {
                                $prefix = !empty($category->bib_prefix) ? $category->bib_prefix : 'BIB';
                                $startNum = $category->bib_start_number ?? 1;
                                
                                $dbBibs = Attendee::where('ticket_category_id', $category->id)
                                    ->whereNotNull('bib_name')
                                    ->pluck('bib_name')
                                    ->map(function ($bib) use ($prefix) {
                                        $clean = str_replace(strtoupper($prefix) . '-', '', strtoupper($bib));
                                        return is_numeric($clean) ? (int)$clean : null;
                                    })
                                    ->filter()
                                    ->toArray();

                                sort($dbBibs);
                                $nextNum = $startNum;
                                foreach ($dbBibs as $num) {
                                    if ($num == $nextNum) {
                                        $nextNum++;
                                    } elseif ($num > $nextNum) {
                                        break;
                                    }
                                }
                            }

                            $generatedBib = strtoupper($prefix) . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
                        }

                        // 2. Generate Ticket Code (Gap Filling Logic)
                        $ticketCode = $attendee->ticket_code;
                        if (empty($ticketCode)) {
                            $ticketPrefix = !empty($event->event_bib_prefix) ? $event->event_bib_prefix : 'BGR26';

                            $dbRegs = Attendee::whereHas('ticketCategory', function ($q) use ($event) {
                                    $q->where('event_id', $event->id);
                                })
                                ->whereNotNull('ticket_code')
                                ->pluck('ticket_code')
                                ->map(function ($code) use ($ticketPrefix) {
                                    $clean = str_replace(strtoupper($ticketPrefix), '', strtoupper($code));
                                    return is_numeric($clean) ? (int)$clean : null;
                                })
                                ->filter()
                                ->toArray();

                            sort($dbRegs);

                            $nextRegNum = 1;
                            foreach ($dbRegs as $num) {
                                if ($num == $nextRegNum) {
                                    $nextRegNum++;
                                } elseif ($num > $nextNum) {
                                    break;
                                }
                            }
                            $ticketCode = strtoupper($ticketPrefix) . str_pad($nextRegNum, 4, '0', STR_PAD_LEFT);
                        }

                        // Save the generated codes directly
                        $attendee->update([
                            'bib_name'    => $generatedBib,
                            'ticket_code' => $ticketCode,
                        ]);

                        // Increment promo code usage if applicable
                        if (!empty($attendee->promo_code_id)) {
                            $promo = PromoCode::find($attendee->promo_code_id);
                            if ($promo) {
                                $promo->increment('uses_count');
                            }
                        }
                    }
                }
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