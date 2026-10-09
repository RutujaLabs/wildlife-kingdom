@extends('front.layout')

@section('title', 'Wildlife Kingdom | Home')

@section('content')
<section class="page-hero" style="background: linear-gradient(rgba(11,47,34,.62), rgba(11,47,34,.62)), url('{{ asset('assets/images/static/hero-wildlife.jpg') }}') center/cover no-repeat; min-height: 520px; display:flex; align-items:center;">
    <div class="container" style="color:#fff;">
        <div style="max-width:680px;">
            <span style="display:inline-flex; align-items:center; gap:12px; font-size:12px; letter-spacing: .24em; text-transform: uppercase; color:#E8A15B; margin-bottom:18px;">Wildlife Experience</span>
            <h1 style="font-size: clamp(2.5rem, 5vw, 5rem); margin:0 0 18px; line-height:1.1;">Discover the rhythm of the wild.</h1>
            <p style="max-width:550px; font-size:1.05rem; line-height:1.7; color: rgba(255,255,255,.86);">Explore majestic species, rich habitats, seasonal events and unforgettable conservation stories at Wildlife Kingdom.</p>
            <div style="display:flex; gap:16px; margin-top:24px; flex-wrap:wrap;">
                <a href="{{ route('animals') }}" class="btn btn-gold">Meet the Animals</a>
                <a href="../../public_html/tickets.php" class="btn btn-outline-dark" style="color:#fff; border-color:#fff;">Book Tickets</a>
            </div>
        </div>
    </div>
</section>

<section class="section section-light">
    <div class="container">
        <div class="section-head" style="text-align:center; margin-bottom:32px;">
            <span class="eyebrow">Featured Animals</span>
            <h2>Wildlife worth meeting</h2>
        </div>
        <div class="grid-3" data-reveal>
            @forelse($featuredAnimals as $animal)
                <div class="animal-card">
                    <img src="{{ $animal->image ? Storage::url($animal->image) : asset('assets/images/static/animal-placeholder.jpg') }}" alt="{{ $animal->name }}" onerror="this.src='{{ asset('assets/images/static/animal-placeholder.jpg') }}'">
                    <div class="animal-card-body">
                        <span class="status">{{ $animal->category ?? 'Wildlife' }}</span>
                        <h3>{{ $animal->name }}</h3>
                        <p class="sci">{{ $animal->scientific_name }}</p>
                        <a href="{{ route('animal.show', $animal) }}">View Details &rarr;</a>
                    </div>
                </div>
            @empty
                <p>No animals have been published yet.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="section section-sand">
    <div class="container">
        <div class="section-head" style="text-align:center; margin-bottom:32px;">
            <span class="eyebrow">Explore</span>
            <h2>Our habitats</h2>
        </div>
        <div class="habitat-grid" data-reveal>
            @forelse($featuredHabitats as $habitat)
                <div class="habitat-card">
                    <img src="{{ $habitat->image ? Storage::url($habitat->image) : asset('assets/images/static/habitat-placeholder.jpg') }}" alt="{{ $habitat->name }}" onerror="this.src='{{ asset('assets/images/static/habitat-placeholder.jpg') }}'">
                    <div class="habitat-overlay">
                        <h3>{{ $habitat->name }}</h3>
                        <span>{{ $habitat->short_description ?? 'Habitat experience' }}</span>
                    </div>
                </div>
            @empty
                <p>No habitats available right now.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="section section-light">
    <div class="container">
        <div class="section-head" style="text-align:center; margin-bottom:32px;">
            <span class="eyebrow">Upcoming</span>
            <h2>Events</h2>
        </div>
        <div class="event-list" data-reveal>
            @forelse($upcomingEvents as $event)
                <div class="event-row">
                    <div class="event-date"><strong>{{ date('d', strtotime($event->event_date)) }}</strong><span>{{ date('M', strtotime($event->event_date)) }}</span></div>
                    <div class="event-info">
                        <h3>{{ $event->title }}</h3>
                        <p>{{ $event->description }}</p>
                        <p>{{ $event->location }} &middot; {{ $event->event_time }}</p>
                    </div>
                </div>
            @empty
                <p>No upcoming events announced yet.</p>
            @endforelse
        </div>
    </div>
</section>

<section class="section section-dark">
    <div class="container">
        <div class="section-head" style="text-align:center; margin-bottom:32px; color:#fff;">
            <span class="eyebrow" style="color:#E8A15B;">Moments</span>
            <h2 style="color:#fff;">Gallery highlights</h2>
        </div>
        <div class="gallery-grid" data-reveal>
            @forelse($gallery as $item)
                <a href="{{ route('gallery') }}">
                    <img src="{{ $item->image ? Storage::url($item->image) : asset('assets/images/static/gallery-placeholder.jpg') }}" alt="{{ $item->title }}" onerror="this.src='{{ asset('assets/images/static/gallery-placeholder.jpg') }}'">
                </a>
            @empty
                <p style="color:#fff;">Gallery images will appear here soon.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection
