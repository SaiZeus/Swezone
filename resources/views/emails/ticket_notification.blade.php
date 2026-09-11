@php
    $event = $attendee->ticketCategory->event;
    $eventId = $attendee->ticketCategory->event_id;

    $position = \App\Models\Attendee::whereHas('ticketCategory', function ($q) use ($eventId) {
            $q->where('event_id', $eventId);
        })
        ->where('created_at', '<=', $attendee->created_at)
        ->where('id', '<=', $attendee->id)
        ->count();

    $formattedTicketRef = 'BGR26' . str_pad($position, 4, '0', STR_PAD_LEFT);
    
    // Banner asset logic
    $ticketPath = public_path('assets/img/ticket/ticket.jpg');

    $ticketBase64 = file_exists($ticketPath)
    ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($ticketPath))
    : null;
    /*
    |--------------------------------------------------------------------------
    | Verification Token
    |--------------------------------------------------------------------------
    */

    if (empty($attendee->verification_token)) {
        $attendee->verification_token = \Illuminate\Support\Str::random(64);
        $attendee->save();
    }


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
    | Same method as the working downloadAttendeeTicket().
    | Uses QRServer PNG.
    | Does NOT require Imagick.
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
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Registration Confirmation</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333333; background-color: #f7f8fc; margin: 0; padding: 0; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table { border-collapse: collapse; }
        .wrapper { width: 100%; background-color: #f7f8fc; padding: 30px 0; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border: 1px solid #e7eaf0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .header { text-align: center; background: #f8f9ff; padding: 30px 20px; border-bottom: 1px solid #e9ebf1; }
        
        /* Responsive 19:6 Ticket Banner Styling */
        .ticket-banner-wrapper {
    width: 100%;
    background-color: #111111;
    text-align: center;
    overflow: hidden;
    /* Height removed so wrapper shrinks/grows to fit full image height */
}

.ticket-banner-img {
    width: 100% !important;
    max-width: 100% !important;
    height: auto !important; /* Forces vertical scale without cropping */
    display: block;
    border: 0;
    outline: none;
    text-decoration: none;
}

        .content { padding: 30px; color: #1f2937; }
        .footer { text-align: center; font-size: 11px; color: #98a2b3; padding: 20px; background: #fafbfc; border-top: 1px solid #e9ebf1; }
        
        table.details-table { width: 100%; margin: 20px 0; background: #fdfdff; border: 1px solid #e3e7ed; border-radius: 8px; overflow: hidden; }
        table.details-table td { padding: 10px 14px; vertical-align: top; border-bottom: 1px solid #e3e7ed; font-size: 13px; }
        table.details-table tr:last-child td { border-bottom: none; }
        .label-col { width: 38%; font-weight: 700; color: #4b5563; background: #f8f9fc; }
        .val-col { width: 62%; color: #111827; }
        
        .section-box { background: #fafbfc; border: 1px solid #e5e8ee; border-radius: 10px; padding: 15px; margin: 20px 0; font-size: 13px; }
        ul.attachments-list { padding-left: 20px; margin: 10px 0; }
        ul.attachments-list li { margin-bottom: 6px; font-size: 13px; color: #4f46e5; font-weight: 600; }
        .highlight-text { color: #4f46e5; font-weight: bold; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            
            <!-- LOGO HEADER -->
            <div class="header">
                @if(isset($message))
                    <img src="{{ $message->embed(public_path('assets/img/logo/Swezon_Logo1.1V.png')) }}" alt="Swezon Logo" style="max-height: 110px; width: auto; object-fit: contain; display: block; margin: 0 auto; border: 0;">
                @else
                    <img src="{{ asset('assets/img/logo/Swezon_Logo1.1V.png') }}" alt="Swezon Logo" style="max-height: 110px; width: auto; object-fit: contain; display: block; margin: 0 auto; border: 0;">
                @endif
            </div>

            <!-- 19:6 TICKET GRAPHIC CONTAINER -->
            @if($ticketBase64)
                <div style="
                    width: 100%;
                    max-width: 800px;
                    margin: 20px auto;
                    position: relative;
                    line-height: 0;
                    overflow: hidden;
                ">

                    <!-- TICKET BACKGROUND -->
                    <img
                        src="{{ $ticketBase64 }}"
                        alt="Event Ticket"
                        style="
                            display: block;
                            width: 100%;
                            height: auto;
                            border: 0;
                        "
                    >

                    <!-- QR CODE -->
                    @if($qrBase64)
                        <img
                            src="{{ $qrBase64 }}"
                            alt="QR Code"
                            style="
                                position: absolute;
                                width: 19.375%;
                                height: auto;
                                top: 24.37%;
                                left: 59.375%;
                                display: block;
                            "
                        >
                    @endif

                    <!-- TICKET NUMBER -->
                    <div style="
                        position: absolute;
                        top: 58%;
                        left: 86.8%;
                        width: 4%;
                        font-family: Arial, Helvetica, sans-serif;
                        font-size: 13px;
                        font-weight: 800;
                        color: #000000;
                        white-space: nowrap;
                        transform: rotate(270deg);
                        transform-origin: top left;
                        line-height: 1;
                    ">
                        {{ $formattedTicketRef }}
                    </div>

                    <!-- NAME + PHONE -->
                    <div style="
                        position: absolute;
                        top: 80%;
                        left: 92.5%;
                        width: 4%;
                        font-family: Arial, Helvetica, sans-serif;
                        color: #ffffff;
                        white-space: nowrap;
                        transform: rotate(270deg);
                        transform-origin: top left;
                        line-height: 1.2;
                    ">
                        <div style="
                            font-size: 12px;
                            font-weight: 700;
                            text-transform: uppercase;
                            margin-bottom: 5px;
                        ">
                            {{ $attendee->full_name }}
                        </div>

                        <div style="
                            font-size: 10px;
                            font-weight: 600;
                        ">
                            {{ $attendee->phone }}
                        </div>
                    </div>

                </div>
            @endif

            <!-- MAIN EMAIL CONTENT -->
            <div class="content">
                <h2 style="color: #172033; font-size: 20px; margin-top: 0; font-weight: 800;">{{ $event->title }}</h2>
                <p>Dear <strong>{{ $attendee->full_name }}</strong>,</p>
                <p>Congratulations! You have successfully registered for <strong>{{ $event->title }}</strong>, title-sponsored by Myanmar Airways International (MAI).</p>
                
                <table class="details-table" cellpadding="0" cellspacing="0">
                    <tr>
                        <td class="label-col">Registration Reference No.:</td>
                        <td class="val-col"><span class="highlight-text">{{ $formattedTicketRef }}</span></td>
                    </tr>
                    <tr>
                        <td class="label-col">Event Date:</td>
                        <td class="val-col">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y (l)') }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">Start Time:</td>
                        <td class="val-col">{{ \Carbon\Carbon::parse($event->event_date)->format('h:i A') }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">Category:</td>
                        <td class="val-col">{{ $attendee->ticketCategory->name }}</td>
                    </tr>
                    <tr>
                        <td class="label-col">Start Address:</td>
                        <td class="val-col">{{ $event->location }}</td>
                    </tr>
                </table>

                <p style="font-size: 13px;"><strong>REFUND POLICY:</strong><br>
                Non-refundable. Date and time are subject to change. Check the official website a few days before the event for updates.</p>

                <div class="section-box">
                    <strong>Event Contact Details</strong><br>
                    Email: {{ $event->creator_email ?? 'N/A' }}<br>
                    Phone: {{ $event->creator_phone ?? 'N/A' }}<br>
                    Website: <a href="https://swezon.com.mm/" target="_blank" style="color: #4f46e5; text-decoration: none;">https://swezon.com.mm/</a>
                </div>

                <p><strong>Documents Attached:</strong></p>
                <ul class="attachments-list">
                    <li>Participant’s QR &gt; Attached</li>
                    <li>Ticket PDF &gt; Attached</li>
                    @if($event->items && $event->items->count() > 0)
                        <li>Event Items &amp; T-Shirt Size Chart &gt; Attached</li>
                    @endif
                    @if($event->english_waiver || $event->burmese_waiver)
                        <li>Waiver &gt; Attached</li>
                    @endif
                    @if($event->english_race_guide || $event->burmese_race_guide)
                        <li>Race Guide &gt; Attached</li>
                    @endif
                </ul>

                <p style="font-size: 13px; color: #555; margin-top: 20px;">If the above information is incorrect, please contact us at <a href="mailto:contact@ahotu.com" style="color: #4f46e5;">contact@ahotu.com</a>.</p>
                
                <p style="margin-top: 25px;">Thank you for your registration!<br>
                If you have any questions about the event, please contact <strong>{{ $event->creator_email ?? 'xxxxxxxxxxx@gmail.com' }}</strong>. For any questions about your payment or to change your registration information, please contact <strong>swezonticketing@gmail.com</strong>.</p>
            </div>

            <!-- FOOTER -->
            <div class="footer">
                <p>This is an automated message. Please do not reply directly to this email.<br>&copy; {{ date('Y') }} Swezon. All rights reserved.</p>
            </div>
            
        </div>
    </div>
</body>
</html>