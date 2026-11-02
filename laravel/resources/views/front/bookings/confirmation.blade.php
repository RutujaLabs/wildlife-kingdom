@extends('front.layout')

@section('title', 'Booking Status | Wildlife Kingdom')

@section('styles')
<style>
    .wk-confirm { padding: 48px 0 64px; background: #f6f3ed; }
    .wk-confirm-card { max-width: 720px; margin: auto; padding: clamp(22px, 5vw, 40px); border: 1px solid #eee6d8; border-radius: 18px; background: #fff; box-shadow: 0 8px 26px rgba(20,33,26,.07); }
    .wk-confirm-card h1 { color: #0B2F22; font-family: 'Fraunces', serif; }
    .wk-confirm-status { padding: 14px; border-radius: 12px; background: #f6f3ed; color: #0B2F22; }
</style>
@endsection

@section('content')
<section class="wk-confirm">
    <div class="container">
        <div class="wk-confirm-card">
            <span class="eyebrow">Booking update</span>
            <h1>
                @if($booking->booking_status === 'confirmed')
                    Your test booking is confirmed
                @elseif($booking->booking_status === 'failed')
                    Test payment was not successful
                @elseif($booking->booking_status === 'cancelled')
                    Test booking cancelled
                @else
                    Booking pending
                @endif
            </h1>
            <p class="wk-confirm-status">
                <strong>TEST MODE:</strong> No real payment was made.
                Payment: {{ ucfirst($booking->payment_status) }} · Booking: {{ ucfirst($booking->booking_status) }}
            </p>
            <p>Booking reference: <strong>{{ $booking->booking_reference }}</strong></p>
            @if($booking->booking_status === 'confirmed')
                <p>Your ticket number is <strong>{{ $booking->ticket_number }}</strong>.</p>
                <a class="btn btn-gold" href="{{ route('bookings.ticket.download', $booking) }}">Download PDF ticket</a>
            @elseif($booking->booking_status === 'pending')
                <a class="btn btn-gold" href="{{ route('bookings.payment', $booking) }}">Continue to test payment</a>
            @else
                <p>No confirmed ticket was issued for this booking.</p>
            @endif
            <a class="btn btn-outline-dark" href="{{ route('front.tickets.index') }}" style="margin-left:8px;">Back to tickets</a>
        </div>
    </div>
</section>
@endsection
