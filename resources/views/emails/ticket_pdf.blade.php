<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Ticket</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; margin: 0; padding: 0; }
        .ticket-box { border: 2px dashed #333; padding: 20px; margin: 20px; border-radius: 8px; }
        .title { font-size: 22px; font-weight: bold; color: #111; margin-bottom: 5px; }
        .category { font-size: 18px; color: #e63946; font-weight: bold; }
        .info-table { width: 100%; margin-top: 20px; border-collapse: collapse; }
        .info-table td { padding: 8px 0; font-size: 14px; }
        .qr-section { text-align: right; }
    </style>
</head>
<body>
    <div class="ticket-box">
        <table style="width: 100%;">
            <tr>
                <td>
                    <div class="title">{{ $attendee->ticketCategory->event->title }}</div>
                    <div class="category">Category: {{ $attendee->ticketCategory->name }}</div>
                </td>
                <td class="qr-section">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $attendee->ticket_uuid }}" width="100" height="100">
                </td>
            </tr>
        </table>

        <hr style="border: 0; border-top: 1px solid #ccc; margin: 15px 0;">

        <table class="info-table">
            <tr>
                <td><strong>Runner Name:</strong> {{ $attendee->full_name }}</td>
                <td><strong>NRC / Passport:</strong> {{ $attendee->nrc_passport }}</td>
            </tr>
            <tr>
                <td><strong>Date:</strong> {{ \Carbon\Carbon::parse($attendee->ticketCategory->event->event_date)->format('F d, Y') }}</td>
                <td><strong>Location:</strong> {{ $attendee->ticketCategory->event->location }}</td>
            </tr>
            <tr>
                <td><strong>T-Shirt Size:</strong> {{ $attendee->tshirt_size }}</td>
                <td><strong>Ticket Ref:</strong> {{ substr($attendee->ticket_uuid, 0, 8) }}</td>
            </tr>
        </table>
    </div>
</body>
</html>