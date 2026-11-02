@extends('front.layout')

@section('title', 'Tickets | Wildlife Kingdom')

@section('styles')
<style>
    .wk-tickets { padding: 64px 0; background: #f6f3ed; }
    .wk-tickets .ticket-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 260px), 1fr)); gap: 24px; }
    .wk-ticket-card { display: flex; flex-direction: column; padding: 28px; border: 1px solid #eee6d8; border-radius: 18px; background: #fff; box-shadow: 0 8px 26px rgba(20,33,26,.06); }
    .wk-ticket-card h2 { margin: 0 0 12px; color: #0B2F22; font-family: 'Fraunces', serif; }
    .wk-ticket-card .price { color: #C9822F; font-size: 2rem; font-weight: 700; }
    .wk-ticket-card .description { flex: 1; color: #5c6967; line-height: 1.65; }
    .wk-ticket-card .btn { margin-top: 18px; align-self: flex-start; }
    .wk-empty { padding: 30px; border-radius: 16px; background: #fff; color: #5c6967; text-align: center; }
</style>
@endsection

@section('content')
<section class="page-hero" style="background:linear-gradient(rgba(11,47,34,.76),rgba(11,47,34,.76)),url('{{ asset('assets/images/static/hero-wildlife.jpg') }}') center/cover no-repeat;min-height:300px;display:flex;align-items:center;">
    <div class="container" style="color:#fff;">
        <span class="eyebrow" style="color:#E8A15B;">Plan your visit</span>
        <h1>Wildlife Kingdom Tickets</h1>
        <p>Every visit helps support wildlife care and conservation.</p>
    </div>
</section>

<section class="wk-tickets">
    <div class="container">
        @if($tickets->isNotEmpty())
            <div class="ticket-grid">
                @foreach($tickets as $ticket)
                    <article class="wk-ticket-card">
                        <h2>{{ $ticket->ticket_type }}</h2>
                        <div class="price">${{ number_format($ticket->price, 2) }}</div>
                        <p class="description">{{ $ticket->description ?: 'Enjoy a day of discovery at Wildlife Kingdom.' }}</p>
                        <a class="btn btn-gold" href="{{ route('bookings.create', $ticket) }}">Book Now</a>
                    </article>
                @endforeach
            </div>
        @else
            <p class="wk-empty">Tickets are not available right now. Please check back soon.</p>
        @endif
    </div>
</section>
@endsection
