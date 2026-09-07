<?php

namespace App\Jobs;

use App\Models\Attendee;
use App\Mail\TicketConfirmationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Exception;

class SendTicketEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $attendee;

    public function __construct(Attendee $attendee)
    {
        $this->attendee = $attendee;
    }

    public function handle()
    {
        try {
            Mail::to($this->attendee->email)
                ->bcc('swezonticketing@gmail.com')
                ->send(new TicketConfirmationMail($this->attendee));
        } catch (Exception $e) {
            Log::error("Failed to send queued ticket email to {$this->attendee->email}: " . $e->getMessage());
        }
    }
}