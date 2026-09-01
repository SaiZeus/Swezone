@php
    $event = $attendee->ticketCategory->event;
    $bannerPath = ($event->image && Storage::disk('public')->exists($event->image)) ? storage_path('app/public/' . $event->image) : null;
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
        .banner-container { width: 100%; max-height: 240px; overflow: hidden; background: #111; }
        .banner-container img { width: 100%; height: auto; max-height: 240px; object-fit: cover; display: block; }
        .content { padding: 30px; color: #1f2937; }
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
            <!-- LOGO HEADER (Increased size for better visibility) -->
            <div class="header">
                @if(isset($message))
                    <img src="{{ $message->embed(public_path('assets/img/logo/Swezon_Logo1.1V.png')) }}" alt="Logo" style="max-height: 110px; width: auto; object-fit: contain; display: block; margin: 0 auto;">
                @else
                    <img src="{{ asset('assets/img/logo/Swezon_Logo1.1V.png') }}" alt="Logo" style="max-height: 110px; width: auto; object-fit: contain; display: block; margin: 0 auto;">
                @endif
            </div>

            <!-- EVENT BANNER -->
            @isset($bannerPath)
            <div class="banner-container">
                @if(isset($message))
                    <img src="{{ $message->embed($bannerPath) }}" alt="{{ $event->title }}">
                @else
                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}">
                @endif
            </div>
            @endisset

            <div class="content">
                <h2 style="color: #172033; font-size: 20px; margin-top: 0; font-weight: 800;">{{ $event->title }}</h2>
                <p>Dear <strong>{{ $attendee->full_name }}</strong>,</p>
                <p>Congratulations! You have successfully registered for <strong>{{ $event->title }}</strong>, title-sponsored by Myanmar Airways International (MAI).</p>
                
                <table class="details-table">
                    <tr>
                        <td class="label-col">Registration Reference No.:</td>
                        <td class="val-col"><span class="highlight-text">{{ $attendee->ticket_uuid ?? $attendee->id }}</span></td>
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

                <div class="section-box">
                    <strong>Information from the organizer</strong><br>
                    <strong>IMPORTANT</strong><br>
                    <strong>RACE PACK COLLECTION</strong><br>
                    Dates and Times: (to be announced)<br>
                    Location: (to be announced)<br>
                    &lt;google map link&gt;
                </div>

                <p style="font-size: 13px;"><em>You may authorize someone to collect your race entry packet on your behalf.</em></p>
                
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