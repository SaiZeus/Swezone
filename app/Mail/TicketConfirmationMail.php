<?php

namespace App\Mail;

use App\Models\Attendee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
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
        $event = $this->attendee->ticketCategory->event;

        // Auto-generate verification token if it's missing
        if (empty($this->attendee->verification_token)) {
            $this->attendee->verification_token = Str::random(64);
            $this->attendee->save();
        }

        // 1. Generate Ticket PDF via DomPDF
        $pdf = Pdf::loadView('emails.ticket_pdf', ['attendee' => $this->attendee]);

        // 2. Generate QR Code pointing to the verification URL
        $verificationUrl = route('ticket.verify', ['token' => $this->attendee->verification_token]);
        $qrImage = (string) QrCode::format('svg')->size(300)->generate($verificationUrl);

        $mail = $this->subject('Congratulations! Your Event Ticket - ' . $event->title)
                    ->view('emails.ticket_notification')
                    // FIRST: Attach Ticket PDF
                    ->attachData($pdf->output(), 'Ticket_' . ($this->attendee->ticket_uuid ?? $this->attendee->id) . '.pdf', [
                        'mime' => 'application/pdf',
                    ])
                    // SECOND: Attach QR Code
                    ->attachData($qrImage, 'Participant_QR.svg', [
                        'mime' => 'image/svg+xml',
                    ]);

        // THIRD: Attach Event Items (e.g., T-shirt size chart) ONLY if they exist
        if ($event->items && $event->items->count() > 0) {
            foreach ($event->items as $item) {
                if ($item->image && Storage::disk('public')->exists($item->image)) {
                    $mail->attach(storage_path('app/public/' . $item->image), [
                        'as' => Str::slug($item->title) . '.' . pathinfo($item->image, PATHINFO_EXTENSION)
                    ]);
                }
            }
        }

        // FOURTH: Attach Waivers & Race Guides PDFs if uploaded
        if ($event->english_waiver && Storage::disk('public')->exists($event->english_waiver)) {
            $mail->attach(storage_path('app/public/' . $event->english_waiver), ['as' => 'English_Waiver.pdf']);
        }
        if ($event->burmese_waiver && Storage::disk('public')->exists($event->burmese_waiver)) {
            $mail->attach(storage_path('app/public/' . $event->burmese_waiver), ['as' => 'Burmese_Waiver.pdf']);
        }
        if ($event->english_race_guide && Storage::disk('public')->exists($event->english_race_guide)) {
            $mail->attach(storage_path('app/public/' . $event->english_race_guide), ['as' => 'English_Race_Guide.pdf']);
        }
        if ($event->burmese_race_guide && Storage::disk('public')->exists($event->burmese_race_guide)) {
            $mail->attach(storage_path('app/public/' . $event->burmese_race_guide), ['as' => 'Burmese_Race_Guide.pdf']);
        }

        return $mail;
    }
}