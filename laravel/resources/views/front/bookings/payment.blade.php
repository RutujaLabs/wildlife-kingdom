@extends('front.layout')

@section('title', 'Payment | Wildlife Kingdom')

@section('styles')
@php
    $baseUrl = request()->getBaseUrl();
    $siteRoot = $baseUrl !== '' ? rtrim(dirname(dirname($baseUrl)), '/') : '';
    $paymentQrPath = base_path('../assets/images/static/payment-qr.png');
@endphp
<style>
    .wk-payment { padding: 48px 0 64px; background: #f6f3ed; }
    .wk-payment-card { max-width: 760px; margin: auto; padding: clamp(22px, 5vw, 40px); border: 1px solid #eee6d8; border-radius: 18px; background: #fff; box-shadow: 0 8px 26px rgba(20,33,26,.07); }
    .wk-payment-card h1 { color: #0B2F22; font-family: 'Fraunces', serif; }
    .wk-payment-qr { display: flex; min-height: 210px; max-width: 250px; flex-direction: column; align-items: center; justify-content: center; gap: 8px; padding: 14px; border: 2px dashed #c9b58e; border-radius: 14px; background: #fff; color: #6c6254; text-align: center; }
    .wk-payment-qr img { width: 100%; max-width: 190px; height: auto; }
</style>
@endsection

@section('content')
<section class="wk-payment">
    <div class="container">
        <div class="wk-payment-card">
            @if($errors->any())
                <div role="alert" style="margin-top:16px;padding:14px;border:1px solid #f3c0c0;border-radius:10px;background:#fff0f0;color:#7f1d1d;">
                    @foreach($errors->all() as $error)<p style="margin:0;">{{ $error }}</p>@endforeach
                </div>
            @endif
            <h1>Payment</h1>
            <p>Booking ID: <strong>{{ $booking->booking_reference }}</strong></p>
            <p>{{ $booking->ticket_quantity }} × {{ $booking->ticket->ticket_type }} · {{ $booking->visit_date->format('F j, Y') }}</p>
            <p><strong>Amount to pay: ₹{{ number_format($booking->total_amount, 2) }}</strong></p>
            <div class="wk-payment-qr">
                @if(is_file($paymentQrPath))
                    <img src="{{ $siteRoot }}/assets/images/static/payment-qr.png" alt="Wildlife Kingdom payment QR code">
                @else
                    <strong>Payment QR code</strong>
                    <span>Your UPI QR image will appear here.</span>
                @endif
            </div>
            <p>Scan the QR code and pay the exact amount shown above. After payment, enter the UTR / transaction ID. Your booking remains pending until payment is manually verified.</p>
            @if($booking->payment_reference)
                <p role="status">Transaction reference submitted. Awaiting payment verification; the booking is not confirmed yet.</p>
            @else
                <form method="POST" action="{{ route('bookings.payment.process', $booking) }}">
                    @csrf
                    <div class="field">
                        <label for="payment_reference">Transaction / UTR ID</label>
                        <input id="payment_reference" name="payment_reference" type="text" maxlength="100" required>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit for payment verification</button>
                </form>
            @endif
            <a class="btn btn-outline" href="{{ route('bookings.confirmation', $booking) }}" style="margin-top:12px;">Check booking status</a>
        </div>
    </div>
</section>
@endsection
