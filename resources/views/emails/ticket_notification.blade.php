<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; }
        .header { text-align: center; background-color: #111; color: #fff; padding: 20px; }
        .content { padding: 20px; }
        .footer { text-align: center; font-size: 12px; color: #777; margin-top: 30px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ $attendee->ticketCategory->event->title }}</h2>
        </div>
        <div class="content">
            <p>Dear <strong>{{ $attendee->full_name }}</strong>,</p>
            <p>Congratulations! Your ticket purchase was successful. We are thrilled to have you join us for the upcoming event.</p>
            
            <p><strong>Event Details:</strong></p>
            <ul>
                <li><strong>Location:</strong> {{ $attendee->ticketCategory->event->location }}</li>
                <li><strong>Date:</strong> {{ \Carbon\Carbon::parse($attendee->ticketCategory->event->event_date)->format('F d, Y - h:i A') }}</li>
                <li><strong>Category:</strong> {{ $attendee->ticketCategory->name }}</li>
                <li><strong>T-Shirt Size:</strong> {{ $attendee->tshirt_size }}</li>
            </ul>

            <p>Your official entry ticket is attached to this email as a PDF. Please present this ticket at the registration desk on event day.</p>
            
            <p>Thank you,<br><strong>Our Team</strong></p>
        </div>
        <div class="footer">
            <p>This is an automated message. Please do not reply directly to this email.</p>
        </div>
    </div>
</body>
</html>