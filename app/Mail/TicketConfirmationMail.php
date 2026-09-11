<?php

namespace App\Mail;

use App\Models\Attendee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $attendee;

    public function __construct(Attendee $attendee)
    {
        $this->attendee = $attendee;
    }

    public function build()
    {
        $attendee = $this->attendee;
        $event = $attendee->ticketCategory->event;

        if (empty($attendee->verification_token)) {
            $attendee->verification_token = Str::random(64);
            $attendee->save();
        }

        // Calculate sequential ticket number matching controller & blade logic
        $eventId = $attendee->ticketCategory->event_id;
        $position = Attendee::whereHas('ticketCategory', function ($q) use ($eventId) {
                $q->where('event_id', $eventId);
            })
            ->where('created_at', '<=', $attendee->created_at)
            ->where('id', '<=', $attendee->id)
            ->count();

        $formattedTicketRef = 'BGR26' . str_pad($position, 4, '0', STR_PAD_LEFT);

        // Load background ticket image for PDF and email view
        $bgPath = public_path('assets/img/ticket/ticket1.jpg');
        $ticketBgBase64 = file_exists($bgPath) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($bgPath)) : null;

        // Generate QR code using the same verification URL as the working PDF ticket
        $verificationUrl = route(
            'ticket.verify',
            [
                'token' => $attendee->verification_token
            ]
        );

        $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=310x310&data='
            . urlencode($verificationUrl);

        $context = stream_context_create([
            'http' => [
                'timeout' => 5
            ]
        ]);

        $qrImageData = @file_get_contents(
            $qrApiUrl,
            false,
            $context
        );

        $qrBase64 = $qrImageData
            ? 'data:image/png;base64,' . base64_encode($qrImageData)
            : null;

        $data = [
            'attendee' => $attendee,
            'formattedTicketRef' => $formattedTicketRef,
            'ticketBgBase64' => $ticketBgBase64,
            'qrBase64' => $qrBase64,
        ];

        $pdf = Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif',
            'isFontSubsettingEnabled' => true,
        ])->loadView('emails.ticket_pdf', $data);

        // Pass the exact same compiled array to your email view so it renders the ticket cleanly
        $mail = $this->subject('Congratulations! Your Event Ticket - ' . $event->title)
                    ->view('emails.ticket_notification', $data)
                    ->attachData($pdf->output(), 'Ticket_' . $formattedTicketRef . '.pdf', [
                        'mime' => 'application/pdf',
                    ]);

        if ($event->items && $event->items->count() > 0) {
            foreach ($event->items as $item) {
                if ($item->image && Storage::disk('public')->exists($item->image)) {
                    $mail->attach(storage_path('app/public/' . $item->image), [
                        'as' => Str::slug($item->title) . '.' . pathinfo($item->image, PATHINFO_EXTENSION)
                    ]);
                }
            }
        }

        if ($event->english_waiver && Storage::disk('public')->exists($event->english_waiver)) {
            $mail->attach(storage_path('app/public/' . $event->english_waiver), ['as' => 'English_Waiver.pdf']);
        }
        if ($event->burmese_waiver && Storage::disk('public')->exists($event->burmese_waiver)) {
            $mail->attach(storage_path('app/public/' . $event->burmese_waiver), ['as' => 'Burmese_Waiver.pdf']);
        }
        if ($event->english_consent && Storage::disk('public')->exists($event->english_consent)) {
            $mail->attach(storage_path('app/public/' . $event->english_consent), ['as' => 'English_Consent.pdf']);
        }
        if ($event->burmese_consent && Storage::disk('public')->exists($event->burmese_consent)) {
            $mail->attach(storage_path('app/public/' . $event->burmese_consent), ['as' => 'Burmese_Consent.pdf']);
        }
        if ($event->english_race_guide && Storage::disk('public')->exists($event->english_race_guide)) {
            $mail->attach(storage_path('app/public/' . $event->english_race_guide), ['as' => 'English_Race_Guide.pdf']);
        }
        if ($event->burmese_race_guide && Storage::disk('public')->exists($event->burmese_race_guide)) {
            $mail->attach(storage_path('app/public/' . $event->burmese_race_guide), ['as' => 'Burmese_Race_Guide.pdf']);
        }

        return $mail;
    }

    private function resizeImageToBase64($filePath, $maxWidth, $maxHeight)
    {
        if (!file_exists($filePath)) {
            return null;
        }

        if (!function_exists('imagecreatefromjpeg')) {
            $imageBinary = @file_get_contents($filePath);
            $mime = mime_content_type($filePath) ?: 'image/jpeg';
            return $imageBinary ? 'data:' . $mime . ';base64,' . base64_encode($imageBinary) : null;
        }

        $imageInfo = @getImageSize($filePath);
        if (!$imageInfo) {
            return null;
        }

        list($origWidth, $origHeight, $imageType) = $imageInfo;

        switch ($imageType) {
            case IMAGETYPE_JPEG: $sourceImage = @imagecreatefromjpeg($filePath); break;
            case IMAGETYPE_PNG: $sourceImage = @imagecreatefrompng($filePath); break;
            case IMAGETYPE_GIF: $sourceImage = @imagecreatefromgif($filePath); break;
            default: return null;
        }

        if (!$sourceImage) {
            return null;
        }

        $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
        if ($ratio > 1) $ratio = 1;
        
        $newWidth = max(1, round($origWidth * $ratio));
        $newHeight = max(1, round($origHeight * $ratio));

        $virtualImage = imagecreatetruecolor($newWidth, $newHeight);

        if ($imageType == IMAGETYPE_PNG || $imageType == IMAGETYPE_GIF) {
            imagecolortransparent($virtualImage, imagecolorallocatealpha($virtualImage, 0, 0, 0, 127));
            imagealphablending($virtualImage, false);
            imagesavealpha($virtualImage, true);
        }

        imagecopyresampled($virtualImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);

        ob_start();
        if ($imageType == IMAGETYPE_JPEG) {
            imagejpeg($virtualImage, null, 75);
            $mime = 'image/jpeg';
        } else {
            imagepng($virtualImage, null, 6);
            $mime = 'image/png';
        }
        $imageData = ob_get_clean();

        imagedestroy($sourceImage);
        imagedestroy($virtualImage);

        return 'data:' . $mime . ';base64,' . base64_encode($imageData);
    }
}