@extends('front.layout')

@section('title', 'Booking Status | Wildlife Kingdom')

@php
    $baseUrl = request()->getBaseUrl();
    $siteRoot = $baseUrl !== '' ? rtrim(dirname(dirname($baseUrl)), '/') : '';
@endphp

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
                @if($booking->payment_status === 'paid' && $booking->booking_status === 'confirmed')
                    Your booking is confirmed
                @elseif($booking->booking_status === 'cancelled')
                    Booking cancelled
                @else
                    Payment verification pending
                @endif
            </h1>
            <p class="wk-confirm-status">
                @if($booking->payment_status === 'paid' && $booking->booking_status === 'confirmed')
                    Payment verified · Booking confirmed
                @elseif($booking->payment_reference)
                    Transaction reference received. Payment has not yet been verified.
                @else
                    No transaction reference has been submitted.
                @endif
            </p>
            <p><strong>Booking ID:</strong> {{ $booking->booking_reference }}</p>
            <p><strong>Visitor name:</strong> {{ $booking->visitor_name }}</p>
            <p><strong>Email:</strong> {{ $booking->visitor_email }}</p>
            <p><strong>Phone:</strong> {{ $booking->visitor_phone }}</p>
            <p><strong>Ticket type:</strong> {{ $booking->ticket->ticket_type }}</p>
            <p><strong>Visit date:</strong> {{ $booking->visit_date->format('F j, Y') }}</p>
            <p><strong>Quantity:</strong> {{ $booking->ticket_quantity }}</p>
            <p><strong>Total amount:</strong> ₹{{ number_format($booking->total_amount, 2) }}</p>
            <p><strong>Payment status:</strong> {{ ucfirst($booking->payment_status) }}</p>
            <p><strong>Booking status:</strong> {{ ucfirst($booking->booking_status) }}</p>
            @if($booking->payment_status === 'paid' && $booking->booking_status === 'confirmed')
                <p><strong>Ticket number:</strong> {{ $booking->ticket_number }}</p>
                <a class="btn btn-gold" href="{{ route('bookings.ticket.download', $booking) }}">Download PDF ticket</a>
            @elseif($booking->payment_reference)
                <a class="btn btn-gold" href="{{ route('bookings.payment', $booking) }}">View payment status</a>
            @endif
            <a class="btn btn-outline-dark" href="{{ $siteRoot }}/public_html/tickets.php" style="margin-left:8px;">Back to tickets</a>
        </div>
    </div>
</section>
@endsection
