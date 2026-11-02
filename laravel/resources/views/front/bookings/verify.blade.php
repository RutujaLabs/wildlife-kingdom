@extends('front.layout')

@section('title', 'Ticket Verification | Wildlife Kingdom')

@section('content')
<section class="section section-light">
    <div class="container" style="max-width:680px;">
        <div class="panel">
            @if($valid)
                <span class="eyebrow">Wildlife Kingdom</span>
                <h1 style="color:#0B2F22;">Ticket verified</h1>
                <p>This ticket is valid for admission.</p>
                <p><strong>Ticket number:</strong> {{ $booking->ticket_number }}</p>
                <p><strong>Visit date:</strong> {{ $booking->visit_date->format('F j, Y') }}</p>
                <p><strong>Ticket type:</strong> {{ $booking->ticket->ticket_type }}</p>
                <p><strong>Quantity:</strong> {{ $booking->ticket_quantity }}</p>
            @else
                <span class="eyebrow">Wildlife Kingdom</span>
                <h1 style="color:#0B2F22;">Ticket not valid</h1>
                <p>This QR code does not match an active, confirmed ticket.</p>
            @endif
        </div>
    </div>
</section>
@endsection
