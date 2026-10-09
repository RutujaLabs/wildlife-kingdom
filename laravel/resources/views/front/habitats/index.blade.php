@extends('front.layout')

@section('title', 'Habitats | Wildlife Kingdom')

@section('content')
<section class="page-hero">
  <div class="container">
    <h1>Our Habitats</h1>
    <p>Every zone recreates a real ecosystem, climate and terrain included, so animals live close to home.</p>
  </div>
</section>

<section class="section section-sand">
  <div class="container">
    <div class="habitat-grid" data-reveal>
      @forelse($habitats as $habitat)
        <div class="habitat-card">
          <img src="{{ $habitat->image ? Storage::url($habitat->image) : asset('assets/images/static/habitat-placeholder.jpg') }}" alt="{{ $habitat->name }}" onerror="this.src='{{ asset('assets/images/static/habitat-placeholder.jpg') }}'">
          <div class="habitat-overlay">
            <h3>{{ $habitat->name }}</h3>
            <span>{{ $habitat->short_description ?? 'Habitat experience' }}</span>
          </div>
        </div>
      @empty
        <p>No habitats added yet.</p>
      @endforelse
    </div>
  </div>
</section>
@endsection
