@extends('front.layout')

@section('title', 'Book Tickets | Wildlife Kingdom')

@section('styles')
<style>
    .wk-booking { padding: 48px 0 64px; background: #f6f3ed; }
    .wk-booking-card { max-width: 760px; margin: auto; padding: clamp(22px, 5vw, 40px); border: 1px solid #eee6d8; border-radius: 18px; background: #fff; box-shadow: 0 8px 26px rgba(20,33,26,.07); }
    .wk-booking-card h1 { margin-top: 0; color: #0B2F22; font-family: 'Fraunces', serif; }
    .wk-booking-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px; }
    .wk-booking-field { display: flex; flex-direction: column; gap: 7px; }
    .wk-booking-field.wide { grid-column: 1 / -1; }
    .wk-booking-field label { color: #0B2F22; font-weight: 600; }
    .wk-booking-field input, .wk-booking-field select { width: 100%; min-height: 46px; padding: 10px 12px; border: 1px solid #d8d1c5; border-radius: 10px; font: inherit; }
    .wk-booking-field input:focus, .wk-booking-field select:focus { outline: 2px solid #E8A15B; border-color: #C9822F; }
    .wk-price-box { margin-top: 20px; padding: 16px; border-radius: 12px; background: #f6f3ed; color: #0B2F22; }
    .wk-test-note { margin: 18px 0 0; color: #5c6967; font-size: .92rem; }
    @media (max-width: 600px) { .wk-booking-grid { grid-template-columns: 1fr; } .wk-booking-field.wide { grid-column: auto; } }
</style>
@endsection

@section('content')
<section class="wk-booking">
    <div class="container">
        <div class="wk-booking-card">
            <span class="eyebrow">Reserve your visit</span>
            <h1>Book Wildlife Kingdom Tickets</h1>
            <p>Select your visit details and enter your contact information. Payment is simulated in test mode only.</p>

            @if($errors->any())
                <div role="alert" style="margin:18px 0;padding:14px;border:1px solid #f3c0c0;border-radius:10px;background:#fff0f0;color:#7f1d1d;">
                    <ul style="margin:0;padding-left:18px;">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm">
                @csrf
                <input type="hidden" name="submission_token" value="{{ $submissionToken }}">
                <div class="wk-booking-grid">
                    <div class="wk-booking-field wide">
                        <label for="ticket_id">Ticket type</label>
                        <select id="ticket_id" name="ticket_id" required>
                            @foreach($tickets as $availableTicket)
                                <option value="{{ $availableTicket->id }}" data-price="{{ number_format($availableTicket->price, 2, '.', '') }}" {{ (string) old('ticket_id', $ticket->id) === (string) $availableTicket->id ? 'selected' : '' }}>
                                    {{ $availableTicket->ticket_type }} — ${{ number_format($availableTicket->price, 2) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="wk-booking-field">
                        <label for="visit_date">Visit date</label>
                        <input id="visit_date" name="visit_date" type="date" min="{{ now()->toDateString() }}" value="{{ old('visit_date') }}" required>
                    </div>
                    <div class="wk-booking-field">
                        <label for="ticket_quantity">Number of tickets</label>
                        <input id="ticket_quantity" name="ticket_quantity" type="number" min="1" max="20" value="{{ old('ticket_quantity', 1) }}" required>
                    </div>
                    <div class="wk-booking-field">
                        <label for="visitor_name">Full name</label>
                        <input id="visitor_name" name="visitor_name" type="text" maxlength="150" autocomplete="name" value="{{ old('visitor_name') }}" required>
                    </div>
                    <div class="wk-booking-field">
                        <label for="visitor_email">Email</label>
                        <input id="visitor_email" name="visitor_email" type="email" maxlength="254" autocomplete="email" value="{{ old('visitor_email') }}" required>
                    </div>
                    <div class="wk-booking-field wide">
                        <label for="visitor_phone">Phone number</label>
                        <input id="visitor_phone" name="visitor_phone" type="tel" maxlength="32" autocomplete="tel" value="{{ old('visitor_phone') }}" required>
                    </div>
                </div>

                <div class="wk-price-box" aria-live="polite">
                    <strong>Ticket price:</strong> $<span id="unitPrice">{{ number_format($ticket->price, 2) }}</span>
                    &nbsp; × &nbsp;
                    <strong>Quantity:</strong> <span id="quantitySummary">{{ old('ticket_quantity', 1) }}</span>
                    &nbsp; = &nbsp;
                    <strong>Total:</strong> $<span id="totalAmount">{{ number_format($ticket->price * (int) old('ticket_quantity', 1), 2) }}</span>
                </div>
                <p class="wk-test-note"><strong>TEST MODE:</strong> No real payment will be charged. You will choose a simulated test outcome on the next step.</p>
                <button class="btn btn-gold" type="submit" style="margin-top:20px;">Continue to Test Payment</button>
                <a class="btn btn-outline-dark" href="{{ route('front.tickets.index') }}" style="margin:20px 0 0 8px;">Back to tickets</a>
            </form>
        </div>
    </div>
</section>
<script>
    (function () {
        var type = document.getElementById('ticket_id');
        var quantity = document.getElementById('ticket_quantity');
        var unit = document.getElementById('unitPrice');
        var quantitySummary = document.getElementById('quantitySummary');
        var total = document.getElementById('totalAmount');
        function updateTotal() {
            var price = Number(type.options[type.selectedIndex].dataset.price || 0);
            var count = Math.max(1, Number(quantity.value || 1));
            unit.textContent = price.toFixed(2);
            quantitySummary.textContent = String(count);
            total.textContent = (price * count).toFixed(2);
        }
        type.addEventListener('change', updateTotal);
        quantity.addEventListener('input', updateTotal);
        updateTotal();
    })();
</script>
@endsection
