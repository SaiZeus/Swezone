@php
    $event = $attendee->ticketCategory->event;
    $eventId = $attendee->ticketCategory->event_id;

    $position = Attendee::whereHas('ticketCategory', function ($q) use ($eventId) {
            $q->where('event_id', $eventId);
        })
        ->where('created_at', '<=', $attendee->created_at)
        ->where('id', '<=', $attendee->id)
        ->count();

    $formattedTicketRef = 'BGR26' . str_pad($position, 4, '0', STR_PAD_LEFT);
    
    // Generate base64 background and QR for email embedding matching the PDF layout
    $bgPath = public_path('assets/img/ticket/ticket.jpg');
    $ticketBgBase64 = file_exists($bgPath) ? 'data:image/jpeg;base64,' . base64_encode(file_get_contents($bgPath)) : null;
    $qrSvg = SimpleSoftwareIO\QrCode\Facades\QrCode::format('svg')->size(310)->errorCorrection('H')->generate($formattedTicketRef);
    $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; line-height: 1.6; color: #333333; background-color: #f7f8fc; margin: 0; padding: 0; }
        .wrapper { width: 100%; background-color: #f7f8fc; padding: 30px 0; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border: 1px solid #e7eaf0; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .header { text-align: center; background: linear-gradient(135deg, #ffffff 0%, #f8f9ff 100%); padding: 30px 20px; border-bottom: 1px solid #e9ebf1; }
        
        /* Email Ticket Wrapper to match ticket_pdf.blade.php proportions safely within email width */
        .email-ticket-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background: #000;
            overflow: hidden;
        }
        .email-ticket-scaler {
            position: relative;
            width: 1600px;
            height: 517px;
            -webkit-transform: scale(0.375);
            transform: scale(0.375);
            -webkit-transform-origin: top left;
            transform-origin: top left;
            margin-bottom: -323px; /* Pulls subsequent content up to compensate for CSS scaling */
        }

        /* Exact ticket styles from ticket_pdf.blade.php */
        .ticket-wrapper {
            position: relative;
            width: 1600px;
            height: 517px;
        }
        .ticket-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 1600px;
            height: 517px;
        }
        .qr-box {
            position: absolute;
            top: 126px;
            left: 950px;
            width: 310px;
            height: 310px;
        }
        .qr-box img {
            width: 100%;
            height: 100%;
            display: block;
        }
        .ticket-number-area {
            position: absolute;
            top: 300px;
            left: 1390px;
            width: 60px;
            height: 360px;
        }
        .ticket-number {
            font-size: 24px;
            font-weight: 800;
            color: #000000;
            -webkit-transform: rotate(270deg);
            transform: rotate(270deg);
            -webkit-transform-origin: top left;
            transform-origin: top left;
            position: absolute;
            top: 0;
            left: 0;
            white-space: nowrap;
        }
        .buyer-data-area {
            position: absolute;
            top: 400px;
            left: 1520px;
            width: 60px;
            height: 500px;
        }
        .buyer-info-group {
            -webkit-transform: rotate(270deg);
            transform: rotate(270deg);
            -webkit-transform-origin: top left;
            transform-origin: top left;
            position: absolute;
            top: 0;
            left: 0;
            white-space: nowrap;
        }
        .buyer-name {
            font-size: 20px;
            font-weight: 700;
            color: #FFFFFF;
            text-transform: uppercase;
            display: block;
            margin-bottom: 10px;
        }
        .buyer-phone {
            font-size: 18px;
            font-weight: 600;
            color: #FFFFFF;
            display: block;
        }

        .content { padding: 30px; color: #1f2937; clear: both; }
        .footer { text-align: center; font-size: 11px; color: #98a2b3; padding: 20px; background: #fafbfc; border-top: 1px solid #e9ebf1; }
        table.details-table { width: 100%; border-collapse: collapse; margin: 20px 0; background: #fdfdff; border: 1px solid #e3e7ed; border-radius: 8px; overflow: hidden; }
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
                    <img src="{{ $message->embed(public_path('assets/img/logo/Swezon_Logo1.1V.png')) }}" alt="Logo" style="max-height: 110px; width: auto; object-fit: contain; display: block; margin: 0 auto;">
                @else
                    <img src="{{ asset('assets/img/logo/Swezon_Logo1.1V.png') }}" alt="Logo" style="max-height: 110px; width: auto; object-fit: contain; display: block; margin: 0 auto;">
                @endif
            </div>

            <!-- EMBEDDED EXACT TICKET VIEW -->
            <div class="email-ticket-container">
                <div class="email-ticket-scaler">
                    <div class="ticket-wrapper">
                        @isset($ticketBgBase64)
                            <img src="{{ $ticketBgBase64 }}" class="ticket-bg" alt="Ticket">
                        @endisset

                        <div class="qr-box">
                            @isset($qrBase64)
                                <img src="{{ $qrBase64 }}" alt="QR Code">
                            @endisset
                        </div>

                        <div class="ticket-number-area">
                            <div class="ticket-number">
                                {{ $formattedTicketRef }}
                            </div>
                        </div>

                        <div class="buyer-data-area">
                            <div class="buyer-info-group">
                                <span class="buyer-name">{{ $attendee->full_name ?? '' }}</span>
                                <span class="buyer-phone">{{ $attendee->phone ?? '' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <h2 style="color: #172033; font-size: 20px; margin-top: 0; font-weight: 800;">{{ $event->title }}</h2>
                <p>Dear <strong>{{ $attendee->full_name }}</strong>,</p>
                <p>Congratulations! You have successfully registered for <strong>{{ $event->title }}</strong>, title-sponsored by Myanmar Airways International (MAI).</p>
                
                <table class="details-table">
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

            <div class="footer">
                <p>This is an automated message. Please do not reply directly to this email.<br>&copy; {{ date('Y') }} Swezon. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html>