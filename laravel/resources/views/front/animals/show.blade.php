@extends('front.layout')

@section('title', $animal->name . ' | Wildlife Kingdom')

@section('content')
<section class="page-hero">
  <div class="container">
    <h1>{{ $animal->name }}</h1>
    <div class="breadcrumb"><a href="{{ route('animals') }}">Animals</a> / {{ $animal->name }}</div>
  </div>
</section>

<section class="section section-light">
  <div class="container detail-grid" data-reveal>
    <div class="detail-media">
      <img src="{{ $animal->image ? Storage::url($animal->image) : asset('assets/images/static/animal-placeholder.jpg') }}" alt="{{ $animal->name }}" onerror="this.src='{{ asset('assets/images/static/animal-placeholder.jpg') }}'">
    </div>

    <div class="detail-heading">
      <span class="detail-status">{{ $animal->category ?? 'Wildlife' }}</span>
      <h1>{{ $animal->name }}</h1>
      <p class="sci">{{ $animal->scientific_name }}</p>
      <p style="margin-top:20px; opacity:.85;">{{ nl2br(e($animal->description)) }}</p>

      <div class="detail-facts">
        <div><span>Habitat</span><strong>{{ $animal->habitat?->name ?? 'Not specified' }}</strong></div>
        <div><span>Status</span><strong>{{ ucfirst($animal->status) }}</strong></div>
      </div>

      <a href="{{ route('animals') }}" class="btn btn-outline-dark" style="margin-top:28px;">&larr; Back to All Animals</a>
    </div>
  </div>
</section>
@endsection
