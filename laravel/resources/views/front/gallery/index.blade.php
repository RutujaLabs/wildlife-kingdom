@extends('front.layout')

@section('title', 'Gallery | Wildlife Kingdom')

@section('content')
<section class="page-hero">
  <div class="container">
    <h1>Gallery</h1>
    <p>Moments from the wild captured in every season.</p>
  </div>
</section>

<section class="section section-light">
  <div class="container">
    <div class="gallery-grid" data-reveal>
      @forelse($gallery as $item)
        <a href="#" aria-label="{{ $item->title }}">
          <img src="{{ $item->image ? Storage::url($item->image) : asset('assets/images/static/gallery-placeholder.jpg') }}" alt="{{ $item->title }}" onerror="this.src='{{ asset('assets/images/static/gallery-placeholder.jpg') }}'">
        </a>
      @empty
        <p>No gallery images are available yet.</p>
      @endforelse
    </div>
  </div>
</section>
@endsection
