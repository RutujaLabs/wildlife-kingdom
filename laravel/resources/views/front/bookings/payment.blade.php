@extends('front.layout')

@section('title', 'Test Payment | Wildlife Kingdom')

@section('styles')
<style>
    .wk-payment { padding: 48px 0 64px; background: #f6f3ed; }
    .wk-payment-card { max-width: 680px; margin: auto; padding: clamp(22px, 5vw, 40px); border: 1px solid #eee6d8; border-radius: 18px; background: #fff; box-shadow: 0 8px 26px rgba(20,33,26,.07); }
    .wk-mode { padding: 14px 16px; border: 1px solid #efcc91; border-radius: 12px; background: #fff6e8; color: #69420b; font-weight: 600; }
    .wk-payment-card h1 { color: #0B2F22; font-family: 'Fraunces', serif; }
    .wk-payment-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 24px; }
    .wk-payment-actions form { margin: 0; }
    .wk-payment-actions .btn { min-height: 44px; }
</style>
@endsection

@section('content')
<section class="wk-payment">
    <div class="container">
        <div class="wk-payment-card">
            <div class="wk-mode">TEST MODE — simulated payment only. No real money is charged.</div>
            @if($errors->any())
                <div role="alert" style="margin-top:16px;padding:14px;border:1px solid #f3c0c0;border-radius:10px;background:#fff0f0;color:#7f1d1d;">
                    @foreach($errors->all() as $error)<p style="margin:0;">{{ $error }}</p>@endforeach
                </div>
            @endif
            <h1>Test payment</h1>
            <p>Booking reference: <strong>{{ $booking->booking_reference }}</strong></p>
            <p>{{ $booking->ticket_quantity }} × {{ $booking->ticket->ticket_type }} · {{ $booking->visit_date->format('F j, Y') }}</p>
            <p><strong>Test total: ${{ number_format($booking->total_amount, 2) }}</strong></p>
            <p>Choose an outcome to verify the booking flow. This does not connect to a payment gateway.</p>

            <div class="wk-payment-actions">
                <form method="POST" action="{{ route('bookings.payment.process', $booking) }}">
                    @csrf
                    <button class="btn btn-primary" type="submit" name="test_outcome" value="success">Simulate Test Success</button>
                </form>
                <form method="POST" action="{{ route('bookings.payment.process', $booking) }}">
                    @csrf
                    <button class="btn btn-danger" type="submit" name="test_outcome" value="failure">Simulate Test Failure</button>
                </form>
                <form method="POST" action="{{ route('bookings.payment.process', $booking) }}">
                    @csrf
                    <button class="btn btn-outline" type="submit" name="test_outcome" value="cancel">Cancel Test</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
