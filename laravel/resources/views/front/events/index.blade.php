@extends('front.layout')

@section('title', 'Events | Wildlife Kingdom')

@section('content')
<section class="page-hero">
  <div class="container">
    <h1>Events</h1>
    <p>Talks, tours and encounters happening across the kingdom.</p>
  </div>
</section>

<section class="section section-dark">
  <div class="container">
    <div class="event-list" data-reveal>
      @forelse($events as $event)
        <div class="event-row">
          <div class="event-date"><strong>{{ date('d', strtotime($event->event_date)) }}</strong><span>{{ date('M', strtotime($event->event_date)) }}</span></div>
          <div class="event-info">
            <h3>{{ $event->title }}</h3>
            <p>{{ $event->description }}</p>
            <p>{{ $event->location }} &middot; {{ $event->event_time }}</p>
          </div>
        </div>
      @empty
        <p>No events scheduled right now.</p>
      @endforelse
    </div>
  </div>
</section>
@endsection
