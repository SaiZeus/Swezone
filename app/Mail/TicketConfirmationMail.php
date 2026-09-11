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

        /*
        |--------------------------------------------------------------------------
        | Verification Token
        |--------------------------------------------------------------------------
        */

        if (empty($attendee->verification_token)) {
            $attendee->verification_token = Str::random(64);
            $attendee->save();
        }

        /*
        |--------------------------------------------------------------------------
        | Ticket Number
        |--------------------------------------------------------------------------
        */

        $eventId = $attendee->ticketCategory->event_id;

        $position = Attendee::whereHas('ticketCategory', function ($q) use ($eventId) {
                $q->where('event_id', $eventId);
            })
            ->where('created_at', '<=', $attendee->created_at)
            ->where('id', '<=', $attendee->id)
            ->count();

        $formattedTicketRef = 'BGR26' . str_pad(
            $position,
            4,
            '0',
            STR_PAD_LEFT
        );

        /*
        |--------------------------------------------------------------------------
        | Ticket Background
        |--------------------------------------------------------------------------
        */

        $bgPath = public_path('assets/img/ticket/ticket.jpg');

        /*
        |--------------------------------------------------------------------------
        | Verification URL
        |--------------------------------------------------------------------------
        */

        $verificationUrl = route('ticket.verify', [
            'token' => $attendee->verification_token
        ]);

        /*
        |--------------------------------------------------------------------------
        | QR CODE
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | CREATE COMPLETE TICKET IMAGE FOR GMAIL
        |--------------------------------------------------------------------------
        |
        | Everything is drawn onto ONE image:
        |
        | - Ticket background
        | - QR code
        | - Ticket number
        | - Attendee name
        | - Attendee phone
        |
        */

        $ticketImagePath = $this->createEmailTicketImage(
            $bgPath,
            $qrImageData,
            $formattedTicketRef,
            $attendee->full_name ?? '',
            $attendee->phone ?? ''
        );

        /*
        |--------------------------------------------------------------------------
        | PDF DATA
        |--------------------------------------------------------------------------
        */

        $ticketBgBase64 = file_exists($bgPath)
            ? 'data:image/jpeg;base64,' . base64_encode(
                file_get_contents($bgPath)
            )
            : null;

        $data = [
            'attendee' => $attendee,
            'formattedTicketRef' => $formattedTicketRef,
            'ticketBgBase64' => $ticketBgBase64,
            'qrBase64' => $qrBase64
        ];

        /*
        |--------------------------------------------------------------------------
        | PDF
        |--------------------------------------------------------------------------
        */

        $pdf = Pdf::setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'defaultFont' => 'sans-serif',
            'isFontSubsettingEnabled' => true,
        ])->loadView('emails.ticket_pdf', $data);

        /*
        |--------------------------------------------------------------------------
        | EMAIL
        |--------------------------------------------------------------------------
        */

        $mail = $this->subject(
                'Congratulations! Your Event Ticket - ' . $event->title
            )
            ->view('emails.ticket_notification', [
                ...$data,
                'emailTicketImagePath' => $ticketImagePath,
            ])
            ->attachData(
                $pdf->output(),
                'Ticket_' . $formattedTicketRef . '.pdf',
                [
                    'mime' => 'application/pdf',
                ]
            );

        /*
        |--------------------------------------------------------------------------
        | EVENT ITEM ATTACHMENTS
        |--------------------------------------------------------------------------
        */

        if ($event->items && $event->items->count() > 0) {

            foreach ($event->items as $item) {

                if (
                    $item->image &&
                    Storage::disk('public')->exists($item->image)
                ) {
                    $mail->attach(
                        storage_path('app/public/' . $item->image),
                        [
                            'as' =>
                                Str::slug($item->title)
                                . '.'
                                . pathinfo(
                                    $item->image,
                                    PATHINFO_EXTENSION
                                )
                        ]
                    );
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | WAIVERS
        |--------------------------------------------------------------------------
        */

        if (
            $event->english_waiver &&
            Storage::disk('public')->exists($event->english_waiver)
        ) {
            $mail->attach(
                storage_path('app/public/' . $event->english_waiver),
                [
                    'as' => 'English_Waiver.pdf'
                ]
            );
        }

        if (
            $event->burmese_waiver &&
            Storage::disk('public')->exists($event->burmese_waiver)
        ) {
            $mail->attach(
                storage_path('app/public/' . $event->burmese_waiver),
                [
                    'as' => 'Burmese_Waiver.pdf'
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | CONSENTS
        |--------------------------------------------------------------------------
        */

        if (
            $event->english_consent &&
            Storage::disk('public')->exists($event->english_consent)
        ) {
            $mail->attach(
                storage_path('app/public/' . $event->english_consent),
                [
                    'as' => 'English_Consent.pdf'
                ]
            );
        }

        if (
            $event->burmese_consent &&
            Storage::disk('public')->exists($event->burmese_consent)
        ) {
            $mail->attach(
                storage_path('app/public/' . $event->burmese_consent),
                [
                    'as' => 'Burmese_Consent.pdf'
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | RACE GUIDES
        |--------------------------------------------------------------------------
        */

        if (
            $event->english_race_guide &&
            Storage::disk('public')->exists($event->english_race_guide)
        ) {
            $mail->attach(
                storage_path('app/public/' . $event->english_race_guide),
                [
                    'as' => 'English_Race_Guide.pdf'
                ]
            );
        }

        if (
            $event->burmese_race_guide &&
            Storage::disk('public')->exists($event->burmese_race_guide)
        ) {
            $mail->attach(
                storage_path('app/public/' . $event->burmese_race_guide),
                [
                    'as' => 'Burmese_Race_Guide.pdf'
                ]
            );
        }

        return $mail;
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE COMPLETE EMAIL TICKET IMAGE
    |--------------------------------------------------------------------------
    */

    private function createEmailTicketImage(
        $backgroundPath,
        $qrImageData,
        $ticketNumber,
        $attendeeName,
        $attendeePhone
    ) {
        /*
        |--------------------------------------------------------------------------
        | Check GD
        |--------------------------------------------------------------------------
        */

        if (!function_exists('imagecreatefromjpeg')) {
            return null;
        }

        if (!file_exists($backgroundPath)) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Load Ticket Background
        |--------------------------------------------------------------------------
        */

        $background = @imagecreatefromjpeg($backgroundPath);

        if (!$background) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Force Exact Ticket Size
        |--------------------------------------------------------------------------
        */

        $ticketWidth = 1600;
        $ticketHeight = 517;

        $ticket = imagecreatetruecolor(
            $ticketWidth,
            $ticketHeight
        );

        imagecopyresampled(
            $ticket,
            $background,
            0,
            0,
            0,
            0,
            $ticketWidth,
            $ticketHeight,
            imagesx($background),
            imagesy($background)
        );

        imagedestroy($background);

        /*
        |--------------------------------------------------------------------------
        | QR CODE
        |--------------------------------------------------------------------------
        */

        if ($qrImageData) {

            $qr = @imagecreatefromstring($qrImageData);

            if ($qr) {

                imagecopyresampled(
                    $ticket,
                    $qr,

                    /*
                    | Destination
                    */
                    950,
                    126,

                    /*
                    | Source
                    */
                    0,
                    0,

                    /*
                    | Destination size
                    */
                    310,
                    310,

                    /*
                    | Source size
                    */
                    imagesx($qr),
                    imagesy($qr)
                );

                imagedestroy($qr);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | FONT
        |--------------------------------------------------------------------------
        */

        $fontPath = $this->findFont();

        /*
        |--------------------------------------------------------------------------
        | TICKET NUMBER
        |--------------------------------------------------------------------------
        */

        if ($fontPath) {

            $black = imagecolorallocate(
                $ticket,
                0,
                0,
                0
            );

            /*
            | PDF position:
            |
            | top  = 300
            | left = 1390
            |
            | We draw on a transparent layer and rotate it.
            */

            $numberLayerWidth = 500;
            $numberLayerHeight = 100;

            $numberLayer = imagecreatetruecolor(
                $numberLayerWidth,
                $numberLayerHeight
            );

            imagealphablending(
                $numberLayer,
                false
            );

            imagesavealpha(
                $numberLayer,
                true
            );

            $transparent = imagecolorallocatealpha(
                $numberLayer,
                0,
                0,
                0,
                127
            );

            imagefill(
                $numberLayer,
                0,
                0,
                $transparent
            );

            imagealphablending(
                $numberLayer,
                true
            );

            imagettftext(
                $numberLayer,
                24,
                0,
                5,
                30,
                $black,
                $fontPath,
                $ticketNumber
            );

            /*
            | Rotate 90 degrees.
            */
            $rotatedNumber = imagerotate(
                $numberLayer,
                90,
                $transparent
            );

            imagealphablending(
                $ticket,
                true
            );

            /*
            | Position manually here.
            */
            imagecopy(
                $ticket,
                $rotatedNumber,
                1390,
                300,
                0,
                0,
                imagesx($rotatedNumber),
                imagesy($rotatedNumber)
            );

            imagedestroy($numberLayer);
            imagedestroy($rotatedNumber);
        }

        /*
        |--------------------------------------------------------------------------
        | BUYER NAME + PHONE
        |--------------------------------------------------------------------------
        */

        if ($fontPath) {

            $white = imagecolorallocate(
                $ticket,
                255,
                255,
                255
            );

            /*
            |--------------------------------------------------------------------------
            | NAME
            |--------------------------------------------------------------------------
            */

            $nameLayerWidth = 700;
            $nameLayerHeight = 100;

            $nameLayer = imagecreatetruecolor(
                $nameLayerWidth,
                $nameLayerHeight
            );

            imagealphablending(
                $nameLayer,
                false
            );

            imagesavealpha(
                $nameLayer,
                true
            );

            $transparent = imagecolorallocatealpha(
                $nameLayer,
                0,
                0,
                0,
                127
            );

            imagefill(
                $nameLayer,
                0,
                0,
                $transparent
            );

            imagealphablending(
                $nameLayer,
                true
            );

            imagettftext(
                $nameLayer,
                30,
                0,
                5,
                38,
                $white,
                $fontPath,
                strtoupper($attendeeName)
            );

            /*
            |--------------------------------------------------------------------------
            | PHONE
            |--------------------------------------------------------------------------
            */

            imagettftext(
                $nameLayer,
                28,
                0,
                5,
                82,
                $white,
                $fontPath,
                $attendeePhone
            );

            /*
            | Rotate the complete name + phone group.
            */
            $rotatedBuyer = imagerotate(
                $nameLayer,
                90,
                $transparent
            );

            /*
            |--------------------------------------------------------------------------
            | BUYER POSITION
            |--------------------------------------------------------------------------
            |
            | Same starting coordinates as PDF:
            |
            | top  = 390
            | left = 1480
            |
            */

            imagecopy(
                $ticket,
                $rotatedBuyer,
                1480,
                390,
                0,
                0,
                imagesx($rotatedBuyer),
                imagesy($rotatedBuyer)
            );

            imagedestroy($nameLayer);
            imagedestroy($rotatedBuyer);
        }

        /*
        |--------------------------------------------------------------------------
        | SAVE IMAGE
        |--------------------------------------------------------------------------
        */

        $fileName = 'email_ticket_' . Str::random(20) . '.png';

        $directory = storage_path('app/public/email-tickets');

        if (!is_dir($directory)) {
            @mkdir(
                $directory,
                0755,
                true
            );
        }

        $outputPath = $directory . DIRECTORY_SEPARATOR . $fileName;

        imagepng(
            $ticket,
            $outputPath,
            6
        );

        imagedestroy($ticket);

        return file_exists($outputPath)
            ? $outputPath
            : null;
    }

    /*
    |--------------------------------------------------------------------------
    | FIND TTF FONT
    |--------------------------------------------------------------------------
    */

    private function findFont()
    {
        $possibleFonts = [

            /*
            | Windows XAMPP
            */
            'C:/Windows/Fonts/arial.ttf',

            /*
            | Windows Arial
            */
            'C:\\Windows\\Fonts\\arial.ttf',

            /*
            | Linux
            */
            '/usr/share/fonts/truetype/msttcorefonts/Arial.ttf',

            '/usr/share/fonts/truetype/msttcorefonts/arial.ttf',

            '/usr/share/fonts/truetype/liberation2/LiberationSans-Regular.ttf',

            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',

            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
        ];

        foreach ($possibleFonts as $font) {

            if (file_exists($font)) {
                return $font;
            }
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | EXISTING IMAGE RESIZER
    |--------------------------------------------------------------------------
    */

    private function resizeImageToBase64(
        $filePath,
        $maxWidth,
        $maxHeight
    ) {
        if (!file_exists($filePath)) {
            return null;
        }

        if (!function_exists('imagecreatefromjpeg')) {

            $imageBinary = @file_get_contents($filePath);

            $mime = mime_content_type($filePath)
                ?: 'image/jpeg';

            return $imageBinary
                ? 'data:' . $mime . ';base64,' . base64_encode($imageBinary)
                : null;
        }

        $imageInfo = @getimagesize($filePath);

        if (!$imageInfo) {
            return null;
        }

        list(
            $origWidth,
            $origHeight,
            $imageType
        ) = $imageInfo;

        switch ($imageType) {

            case IMAGETYPE_JPEG:
                $sourceImage = @imagecreatefromjpeg($filePath);
                break;

            case IMAGETYPE_PNG:
                $sourceImage = @imagecreatefrompng($filePath);
                break;

            case IMAGETYPE_GIF:
                $sourceImage = @imagecreatefromgif($filePath);
                break;

            default:
                return null;
        }

        if (!$sourceImage) {
            return null;
        }

        $ratio = min(
            $maxWidth / $origWidth,
            $maxHeight / $origHeight
        );

        if ($ratio > 1) {
            $ratio = 1;
        }

        $newWidth = max(
            1,
            round($origWidth * $ratio)
        );

        $newHeight = max(
            1,
            round($origHeight * $ratio)
        );

        $virtualImage = imagecreatetruecolor(
            $newWidth,
            $newHeight
        );

        if (
            $imageType == IMAGETYPE_PNG ||
            $imageType == IMAGETYPE_GIF
        ) {
            imagecolortransparent(
                $virtualImage,
                imagecolorallocatealpha(
                    $virtualImage,
                    0,
                    0,
                    0,
                    127
                )
            );

            imagealphablending(
                $virtualImage,
                false
            );

            imagesavealpha(
                $virtualImage,
                true
            );
        }

        imagecopyresampled(
            $virtualImage,
            $sourceImage,
            0,
            0,
            0,
            0,
            $newWidth,
            $newHeight,
            $origWidth,
            $origHeight
        );

        ob_start();

        if ($imageType == IMAGETYPE_JPEG) {

            imagejpeg(
                $virtualImage,
                null,
                75
            );

            $mime = 'image/jpeg';

        } else {

            imagepng(
                $virtualImage,
                null,
                6
            );

            $mime = 'image/png';
        }

        $imageData = ob_get_clean();

        imagedestroy($sourceImage);
        imagedestroy($virtualImage);

        return 'data:' . $mime . ';base64,' . base64_encode($imageData);
    }
}