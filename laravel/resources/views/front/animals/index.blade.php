@extends('front.layout')

@section('title', 'Animals | Wildlife Kingdom')

@section('content')
<section class="page-hero">
  <div class="container">
    <h1>Our Animals</h1>
    <p>Meet the residents of Wildlife Kingdom, from the smallest insects to the tallest giants.</p>
  </div>
</section>

<section class="section section-light">
  <div class="container">
    <div class="grid-3" data-reveal>
      @forelse($animals as $animal)
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
        <p>No animals added yet.</p>
      @endforelse
    </div>
  </div>
</section>
@endsection
