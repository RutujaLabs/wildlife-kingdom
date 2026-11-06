@extends('admin.layout')

@section('title', 'Booking Details')
@section('page-title', 'Booking Details')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
    <div>
        <h2 style="margin:0;color:#0B2F22;">{{ $booking->booking_reference }}</h2>
        <p style="margin:4px 0 0;color:#6c6a69;">Booking #{{ $booking->id }}</p>
    </div>
    <a class="btn btn-outline" href="{{ route('admin.bookings.index') }}">Back to bookings</a>
</div>

<div class="grid-two">
    <section class="panel">
        <h3 style="margin-top:0;">Visitor and booking</h3>
        <p><strong>Visitor name:</strong> {{ $booking->visitor_name }}</p>
        <p><strong>Email:</strong> {{ $booking->visitor_email }}</p>
        <p><strong>Phone:</strong> {{ $booking->visitor_phone }}</p>
        <p><strong>Visit date:</strong> {{ $booking->visit_date->format('F j, Y') }}</p>
        <p><strong>Ticket type:</strong> {{ $booking->ticket->ticket_type }}</p>
        <p><strong>Number of tickets:</strong> {{ $booking->ticket_quantity }}</p>
        <p><strong>Unit price at booking:</strong> ₹{{ number_format($booking->unit_price, 2) }}</p>
        <p><strong>Total amount:</strong> ₹{{ number_format($booking->total_amount, 2) }}</p>
        <p><strong>Transaction / UTR ID:</strong> {{ $booking->payment_reference ?: 'Not submitted' }}</p>
        <p><strong>Payment status:</strong> {{ ucfirst($booking->payment_status) }}</p>
        <p><strong>Booking status:</strong> {{ ucfirst($booking->booking_status) }}</p>
        <p><strong>Created:</strong> {{ $booking->created_at->format('F j, Y g:i A') }}</p>
        @if($booking->paid_at)<p><strong>Payment verified:</strong> {{ $booking->paid_at->format('F j, Y g:i A') }}</p>@endif
    </section>

    <section class="panel">
        <h3 style="margin-top:0;">Booking actions</h3>
        @if($booking->payment_status === 'pending' && $booking->booking_status === 'pending' && $booking->payment_reference)
            <p>Verify the transaction against your payment provider before confirming this booking.</p>
            <form method="POST" action="{{ route('admin.bookings.verify-payment', $booking) }}" style="margin:16px 0;">
                @csrf
                <button class="btn btn-primary" type="submit" onclick="return confirm('Have you verified this payment with your payment provider?')">Verify payment and confirm booking</button>
            </form>
        @endif
        @if($booking->payment_status === 'paid' && $booking->booking_status === 'confirmed')
            <p><strong>Ticket number:</strong> {{ $booking->ticket_number }}</p>
            <a class="btn btn-primary" href="{{ route('admin.bookings.ticket', $booking) }}">Download PDF ticket</a>
        @elseif($booking->booking_status !== 'cancelled' && $booking->booking_status !== 'failed')
            <p>No confirmed ticket is available for this booking.</p>
        @else
            <p>This booking is closed; no confirmed ticket is available.</p>
        @endif

        @if($qrDataUri)
            <div style="max-width:230px;margin:20px auto;text-align:center;">
                <img src="{{ $qrDataUri }}" alt="Ticket verification QR code" style="display:block;width:220px;max-width:100%;margin:auto;">
                <p style="color:#6c6a69;font-size:.88rem;">QR contains only a private verification token; it does not expose visitor contact details.</p>
            </div>
        @endif

        @if(in_array($booking->booking_status, ['pending', 'confirmed'], true))
            <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" style="margin-top:16px;">
                @csrf
                @method('PATCH')
                @if($booking->payment_status === 'paid' && $booking->booking_status !== 'confirmed')
                    <button class="btn btn-primary" type="submit" name="booking_status" value="confirmed">Confirm booking</button>
                @endif
                <button class="btn btn-danger" type="submit" name="booking_status" value="cancelled" onclick="return confirm('Cancel this booking?')">Cancel booking</button>
            </form>
        @endif
    </section>
</div>
@endsection
