@extends('front.layout')

@section('title', 'Book Tickets | Wildlife Kingdom')

@php
    $baseUrl = request()->getBaseUrl();
    $siteRoot = $baseUrl !== '' ? rtrim(dirname(dirname($baseUrl)), '/') : '';
@endphp

@section('content')
<section class="section section-light">
    <div class="container">
        <div class="panel">
            <h1>Choose a ticket</h1>
            <p>Ticket bookings now start on the main Wildlife Kingdom Tickets page.</p>
            <a class="btn btn-gold" href="{{ $siteRoot }}/public_html/tickets.php?book={{ $ticket->id }}">Continue on the Tickets page</a>
        </div>
    </div>
</section>
@endsection
