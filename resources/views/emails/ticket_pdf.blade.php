<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Ticket</title>
    <style>
        @page {
            margin: 0;
            size: 1600px 517px;
        }
        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica', 'Arial', sans-serif;
        }
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
        
        /* Ticket Number Area */
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

        /* Name & Phone Area */
        .buyer-data-area {
            position: absolute;
            top: 390px;
            left: 1480px;
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
            font-size: 30px;
            font-weight: 700;
            color: #FFFFFF;
            text-transform: uppercase;
            display: block;
            margin-bottom: 10px;
        }
        .buyer-phone {
            font-size: 28px;
            font-weight: 600;
            color: #FFFFFF;
            display: block;
        }
    </style>
</head>
<body>
    <div class="ticket-wrapper">
        @isset($ticketBgBase64)
            <img src="{{ $ticketBgBase64 }}" class="ticket-bg" alt="Ticket">
        @endisset

        <div class="qr-box">
            @isset($qrBase64)
                <img src="{{ $qrBase64 }}" alt="QR Code">
            @endisset
        </div>

        <!-- Ticket Number Area -->
        <div class="ticket-number-area">
            <div class="ticket-number">
                {{ $formattedTicketRef ?? '' }}
            </div>
        </div>

        <!-- Name & Phone Area -->
        <div class="buyer-data-area">
            <div class="buyer-info-group">
                <span class="buyer-name">{{ $attendee->full_name ?? '' }}</span>
                <span class="buyer-phone">{{ $attendee->phone ?? '' }}</span>
            </div>
        </div>
    </div>
</body>
</html>