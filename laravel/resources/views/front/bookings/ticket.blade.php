<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <style>
        @page { margin: 32px; }
        body { color: #20302b; font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .ticket { border: 2px solid #0B2F22; border-radius: 18px; padding: 28px; }
        .brand { margin: -28px -28px 24px; padding: 22px 28px; border-radius: 14px 14px 0 0; background: #0B2F22; color: #fff; }
        .brand h1 { margin: 0 0 6px; color: #E8A15B; font-size: 24px; }
        .brand p { margin: 0; color: #fff; }
        .reference { color: #C9822F; font-size: 16px; font-weight: bold; }
        .details { width: 100%; margin-top: 18px; border-collapse: collapse; }
        .details td { padding: 10px 4px; border-bottom: 1px solid #eee6d8; }
        .details td:first-child { width: 36%; color: #5c6967; font-weight: bold; }
        .qr { margin-top: 24px; text-align: center; }
        .qr img { width: 190px; height: 190px; }
        .disclaimer { margin-top: 20px; color: #6c6a69; font-size: 10px; text-align: center; }
    </style>
</head>
<body>
    <div class="ticket">
        <header class="brand">
            <h1>WILDLIFE KINGDOM</h1>
            <p>Admission ticket · TEST MODE booking</p>
        </header>
        <p class="reference">Booking reference: {{ $booking->booking_reference }}</p>
        <table class="details">
            <tr><td>Visitor</td><td>{{ $booking->visitor_name }}</td></tr>
            <tr><td>Visit date</td><td>{{ $booking->visit_date->format('F j, Y') }}</td></tr>
            <tr><td>Ticket type</td><td>{{ $booking->ticket->ticket_type }}</td></tr>
            <tr><td>Quantity</td><td>{{ $booking->ticket_quantity }}</td></tr>
            <tr><td>Ticket number</td><td>{{ $booking->ticket_number }}</td></tr>
        </table>
        <div class="qr"><img src="{{ $qrDataUri }}" alt="Ticket verification QR code"></div>
        <p class="disclaimer">This ticket was issued after a successful simulated test payment. No real payment was collected.</p>
    </div>
</body>
</html>
