<?php

namespace App\Mail;

use App\Models\Attendee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $attendee;

    public function __construct(Attendee $attendee)
    {
        $this->attendee = $attendee;
    }

    public function build()
    {
        // Load the PDF view with attendee data
        $pdf = Pdf::loadView('emails.ticket_pdf', ['attendee' => $this->attendee]);

        return $this->subject('Congratulations! Your Event Ticket - ' . $this->attendee->ticketCategory->event->title)
                    ->view('emails.ticket_notification')
                    ->attachData($pdf->output(), 'Ticket_' . $this->attendee->ticket_uuid . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}
