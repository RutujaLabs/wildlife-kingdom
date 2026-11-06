<div class="wk-modal-content">
    <div class="wk-modal-heading">
        <div>
            <span class="wk-modal-eyebrow">Wildlife Kingdom · Booking</span>
            @if($booking->payment_status === 'paid' && $booking->booking_status === 'confirmed')
                <h2>Your booking is confirmed</h2>
            @else
                <h2>Payment verification pending</h2>
            @endif
        </div>
        <button class="wk-modal-close" type="button" data-modal-close aria-label="Close booking dialog">&times;</button>
    </div>

    @if($booking->payment_status === 'paid' && $booking->booking_status === 'confirmed')
        <p class="wk-modal-notice" role="status">Payment verified. Your ticket is confirmed.</p>
        <dl class="wk-confirmation-details">
            <div><dt>Booking ID</dt><dd>{{ $booking->booking_reference }}</dd></div>
            <div><dt>Visitor name</dt><dd>{{ $booking->visitor_name }}</dd></div>
            <div><dt>Ticket type</dt><dd>{{ $booking->ticket->ticket_type }}</dd></div>
            <div><dt>Visit date</dt><dd>{{ $booking->visit_date->format('F j, Y') }}</dd></div>
            <div><dt>Quantity</dt><dd>{{ $booking->ticket_quantity }}</dd></div>
            <div><dt>Total amount</dt><dd>₹{{ number_format($booking->total_amount, 2) }}</dd></div>
            <div><dt>Payment status</dt><dd>Verified · Paid</dd></div>
        </dl>
        <div class="wk-confirmation-qr">
            <img src="{{ $qrDataUri }}" alt="Unique ticket verification QR code">
            <span>Ticket verification QR · scan to check validity</span>
        </div>
        <a class="wk-modal-submit wk-modal-link" href="{{ route('bookings.ticket.download', $booking) }}">Download PDF ticket</a>
    @else
        <p class="wk-modal-notice" role="status">
            Your transaction reference has been submitted. The booking is not confirmed until the payment is verified by Wildlife Kingdom.
        </p>
        <dl class="wk-confirmation-details">
            <div><dt>Booking ID</dt><dd>{{ $booking->booking_reference }}</dd></div>
            <div><dt>Visitor name</dt><dd>{{ $booking->visitor_name }}</dd></div>
            <div><dt>Ticket type</dt><dd>{{ $booking->ticket->ticket_type }}</dd></div>
            <div><dt>Visit date</dt><dd>{{ $booking->visit_date->format('F j, Y') }}</dd></div>
            <div><dt>Quantity</dt><dd>{{ $booking->ticket_quantity }}</dd></div>
            <div><dt>Total amount</dt><dd>₹{{ number_format($booking->total_amount, 2) }}</dd></div>
            <div><dt>Payment status</dt><dd>Pending verification</dd></div>
        </dl>
        <button class="wk-modal-submit" type="button" data-load-fragment="{{ route('bookings.confirmation.modal', $booking) }}">Check verification status</button>
    @endif
</div>
