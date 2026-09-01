@php
    $event = $attendee->ticketCategory->event;
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Event Ticket</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Noto+Sans+Myanmar:wght@400;700&display=swap');
        
        body { 
            font-family: 'Noto Sans Myanmar', 'Helvetica', 'Arial', sans-serif; 
            margin: 0; 
            padding: 10px; 
            background-color: #f8fafc; 
            color: #1e293b; 
        }
        .ticket-wrapper { 
            width: 100%; 
            max-width: 540px; 
            margin: 0 auto; 
            background: #ffffff; 
            border: 1px solid #cbd5e1; 
            border-radius: 12px; 
            overflow: hidden; 
        }
        .banner-container { 
            width: 100%; 
            height: 130px; 
            overflow: hidden; 
            background: #0f172a; 
        }
        .banner-container img { 
            width: 100%; 
            height: 130px; 
            object-fit: cover; 
            display: block; 
        }
        .ticket-body { 
            padding: 16px 20px; 
        }
        .top-row { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 10px; 
        }
        .title { 
            font-size: 16px; 
            font-weight: 800; 
            color: #4f46e5; 
            margin-bottom: 2px; 
            line-height: 1.2; 
        }
        .category { 
            font-size: 12px; 
            color: #64748b; 
            font-weight: 700; 
            text-transform: uppercase; 
        }
        .qr-box { 
            border: 1px solid #e2e8f0; 
            border-radius: 8px; 
            padding: 3px; 
            background: #ffffff; 
            text-align: center; 
            width: 65px; 
            height: 65px; 
            vertical-align: middle; 
        }
        .divider { 
            border: 0; 
            border-top: 1px dashed #cbd5e1; 
            margin: 10px 0; 
        }
        .info-table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        .info-table td { 
            padding: 4px 2px; 
            font-size: 11px; 
            vertical-align: top; 
            color: #475569; 
        }
        .info-table td strong { 
            color: #0f172a; 
        }
        .logo-footer { 
            text-align: center; 
            padding: 10px 0; 
            background: #f8fafc; 
            border-top: 1px solid #e2e8f0; 
        }
    </style>
</head>
<body>
    <div class="ticket-wrapper">
        @isset($bannerBase64)
        <div class="banner-container">
            <img src="{{ $bannerBase64 }}" alt="{{ $event->title }}">
        </div>
        @endisset

        <div class="ticket-body">
            <table class="top-row">
                <tr>
                    <td>
                        <div class="title">{{ $event->title }}</div>
                        <div class="category">Category: {{ $attendee->ticketCategory->name }}</div>
                    </td>
                    <td align="right">
                        <div class="qr-box">
                            @isset($qrBase64)
                                <img src="{{ $qrBase64 }}" width="65" height="65" alt="QR Code">
                            @endisset
                        </div>
                    </td>
                </tr>
            </table>

            <hr class="divider">

            <table class="info-table">
                <tr>
                    <td style="width: 50%;"><strong>Runner Name:</strong> {{ $attendee->full_name }}</td>
                    <td style="width: 50%;">
                        <strong>NRC / Passport:</strong> 
                        @if($attendee->nrc_passport)
                            <span>{{ $attendee->nrc_passport }}</span>
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                <tr>
                    <td><strong>Date:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</td>
                    <td><strong>Location:</strong> {{ $event->location }}</td>
                </tr>
                <tr>
                    <td><strong>Start Time:</strong> {{ \Carbon\Carbon::parse($event->event_date)->format('h:i A') }}</td>
                    <td><strong>Ticket Ref:</strong> {{ $attendee->ticket_uuid ?? $attendee->id }}</td>
                </tr>
                @if($attendee->tshirt_size)
                <tr>
                    <td><strong>T-Shirt Size:</strong> {{ $attendee->tshirt_size }}</td>
                    <td></td>
                </tr>
                @endif
            </table>
        </div>

        @isset($logoBase64)
        <div class="logo-footer">
            <img src="{{ $logoBase64 }}" alt="Swezon Logo" width="100" style="height: auto; display: block; margin: 0 auto;">
        </div>
        @endisset
    </div>
</body>
</html>