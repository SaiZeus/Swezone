<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Ticket</title>
    <style>
        body { font-family: 'Helvetica', Arial, sans-serif; color: #3b0764; margin: 0; padding: 20px; }
        .ticket-box { border: 2px dashed #a855f7; border-radius: 12px; padding: 20px; background: #faf5ff; }
        .header { text-align: center; border-bottom: 1px solid #d8b4fe; padding-bottom: 15px; margin-bottom: 15px; }
        .title { font-size: 20px; font-weight: bold; color: #581c87; }
        .info-table { width: 100%; margin-top: 15px; }
        .info-table td { padding: 6px; font-size: 13px; }
        .qr-section { text-align: center; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="ticket-box">
        <div class="header">
            <div class="title">{{ $attendee->ticketCategory->event->title ?? 'Event Ticket' }}</div>
            <p style="margin: 5px 0; font-size: 12px; color: #7e22ce;">
                {{ \Carbon\Carbon::parse($attendee->ticketCategory->event->event_date)->format('F d, Y - h:i A') }}
            </p>
        </div>

        <table class="info-table">
            <tr>
                <td><strong>Participant:</strong> {{ $attendee->full_name }}</td>
                <td><strong>Ref Code:</strong> {{ $attendee->ticket_code }}</td>
            </tr>
            <tr>
                <td><strong>Category:</strong> {{ $attendee->ticketCategory->name }}</td>
                <td><strong>BIB:</strong> {{ $attendee->bib_name ?? 'N/A' }}</td>
            </tr>
            @if(!empty($attendee->tshirt_size))
            <tr>
                <td><strong>T-Shirt Size:</strong> {{ $attendee->tshirt_size }}</td>
                <td><strong>Phone:</strong> {{ $attendee->phone }}</td>
            </tr>
            @endif
        </table>

        <div class="qr-section">
            <img src="data:image/png;base64,{{ $qrCodeImage }}" alt="QR Verification Code">
            <p style="font-size: 11px; color: #6b21a8; margin-top: 5px;">Scan to verify ticket status</p>
        </div>
    </div>
</body>
</html>