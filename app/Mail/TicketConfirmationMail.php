<?php

namespace App\Mail;

use App\Models\Attendee;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
        $attendee = $this->attendee;
        $event = $attendee->ticketCategory->event;

        if (empty($attendee->verification_token)) {
            $attendee->verification_token = Str::random(64);
            $attendee->save();
        }

        $bannerBase64 = null;
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            $bannerPath = storage_path('app/public/' . $event->image);
            if (file_exists($bannerPath) && filesize($bannerPath) <= 2 * 1024 * 1024) {
                $bannerBase64 = $this->resizeImageToBase64($bannerPath, 800, 300);
            }
        }

        $logoPath = public_path('assets/img/logo/Swezon_Logo1.1V.png');
        $logoBase64 = (file_exists($logoPath) && filesize($logoPath) <= 2 * 1024 * 1024) 
            ? $this->resizeImageToBase64($logoPath, 300, 100) 
            : null;

        $verificationUrl = route('ticket.verify', ['token' => $attendee->verification_token]);
        $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=75x75&data=' . urlencode($verificationUrl);
        $qrImageData = @file_get_contents($qrApiUrl);
        $qrBase64 = $qrImageData ? 'data:image/png;base64,' . base64_encode($qrImageData) : null;

        $pdf = Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif',
            'isFontSubsettingEnabled' => true,
        ])->loadView('emails.ticket_pdf', [
            'attendee' => $attendee,
            'bannerBase64' => $bannerBase64,
            'logoBase64' => $logoBase64,
            'qrBase64' => $qrBase64
        ]);

        $filename = 'Ticket_' . ($attendee->ticket_uuid ?? $attendee->id) . '.pdf';

        $mail = $this->subject('Congratulations! Your Event Ticket - ' . $event->title)
                    ->view('emails.ticket_notification')
                    ->attachData($pdf->output(), $filename, [
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