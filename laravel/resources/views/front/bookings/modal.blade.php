<div class="wk-modal-content">
    <div class="wk-modal-heading">
        <div>
            <span class="wk-modal-eyebrow">Reserve your visit</span>
            <h2 id="wkBookingTitle">Book {{ $ticket->ticket_type }} tickets</h2>
        </div>
        <button class="wk-modal-close" type="button" data-modal-close aria-label="Close booking dialog">&times;</button>
    </div>

    <form method="POST" action="{{ route('bookings.store') }}" data-booking-form>
        @csrf
        <input type="hidden" name="submission_token" value="{{ $submissionToken }}">
        <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">

        <div class="wk-modal-grid">
            <div class="wk-modal-field">
                <label for="wk_visit_date">Visit date</label>
                <input id="wk_visit_date" name="visit_date" type="date" min="{{ now()->toDateString() }}" required>
            </div>
            <div class="wk-modal-field">
                <label for="wk_ticket_quantity">Quantity</label>
                <input id="wk_ticket_quantity" name="ticket_quantity" type="number" min="1" max="20" value="1" required>
            </div>
            <div class="wk-modal-field">
                <label for="wk_visitor_name">Visitor name</label>
                <input id="wk_visitor_name" name="visitor_name" type="text" maxlength="150" autocomplete="name" required>
            </div>
            <div class="wk-modal-field">
                <label for="wk_visitor_email">Email</label>
                <input id="wk_visitor_email" name="visitor_email" type="email" maxlength="254" autocomplete="email" required>
            </div>
            <div class="wk-modal-field wk-modal-field-wide">
                <label for="wk_visitor_phone">Phone number</label>
                <input id="wk_visitor_phone" name="visitor_phone" type="tel" maxlength="32" autocomplete="tel" required>
            </div>
        </div>

        <div class="wk-modal-total" aria-live="polite">
            <span>{{ $ticket->ticket_type }} · ₹<span data-unit-price>{{ number_format($ticket->price, 2, '.', '') }}</span> each</span>
            <strong>Total amount: ₹<span data-total-amount>{{ number_format($ticket->price, 2, '.', '') }}</span></strong>
        </div>
        <div class="wk-modal-errors" role="alert" data-form-errors hidden></div>
        <button class="wk-modal-submit" type="submit">Continue to payment</button>
    </form>
</div>
