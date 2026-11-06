@php
    $baseUrl = request()->getBaseUrl();
    $siteRoot = $baseUrl !== '' ? rtrim(dirname(dirname($baseUrl)), '/') : '';
    $paymentQrPath = base_path('../assets/images/static/payment-qr.png');
@endphp
<div class="wk-modal-content">
    <div class="wk-modal-heading">
        <div>
            <span class="wk-modal-eyebrow">Payment</span>
            <h2>Complete your payment</h2>
        </div>
        <button class="wk-modal-close" type="button" data-modal-close aria-label="Close booking dialog">&times;</button>
    </div>

    <div class="wk-payment-summary">
        <span>{{ $booking->ticket_quantity }} × {{ $booking->ticket->ticket_type }}</span>
        <strong>Amount to pay: ₹{{ number_format($booking->total_amount, 2) }}</strong>
    </div>
    <div class="wk-payment-layout">
        <div class="wk-payment-qr" aria-label="Payment QR code area">
            @if(is_file($paymentQrPath))
                <img src="{{ $siteRoot }}/assets/images/static/payment-qr.png?v={{ filemtime($paymentQrPath) }}" alt="Wildlife Kingdom payment QR code (UPI: rutujabhapkar855@oksbi)" style="display:block;width:100%;height:auto;max-width:100%;object-fit:contain;border-radius:12px;">
            @else
                <span class="wk-payment-qr-mark" aria-hidden="true">QR</span>
                <strong>Payment QR code</strong>
                <span>Your UPI QR image will appear here.</span>
            @endif
        </div>
        <div class="wk-payment-instructions">
            <p>Scan the QR code and pay the exact amount shown above.</p>
            <p>After paying, enter the UTR / transaction ID from your payment app. Your booking stays pending until an administrator verifies the payment.</p>
        </div>
    </div>

    @if($booking->payment_reference)
        <div class="wk-modal-notice" role="status">
            Transaction reference submitted. Payment is awaiting manual verification; this is not a payment confirmation.
        </div>
        <button class="wk-modal-submit" type="button" data-load-fragment="{{ route('bookings.confirmation.modal', $booking) }}">Check booking status</button>
    @else
        <form method="POST" action="{{ route('bookings.payment.process', $booking) }}" data-payment-form>
            @csrf
            <div class="wk-modal-field">
                <label for="wk_payment_reference">Transaction / UTR ID</label>
                <input id="wk_payment_reference" name="payment_reference" type="text" maxlength="100" autocomplete="off" required>
            </div>
            <div class="wk-modal-errors" role="alert" data-form-errors hidden></div>
            <button class="wk-modal-submit" type="submit">Submit for payment verification</button>
        </form>
    @endif
</div>