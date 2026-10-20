@extends('front.layout')

@section('title', $animal->meta_title ?: $animal->name . ' | Wildlife Kingdom')
@section('meta-description', $animal->meta_description ?: ($animal->short_description ?: \Illuminate\Support\Str::limit(strip_tags($animal->description), 160)))
@section('canonical', $animal->canonical_url ?: (isset($preview) && $preview ? route('animal.show', $animal) : request()->url()))
@section('robots', (isset($preview) && $preview ? 'noindex' : ($animal->noindex ? 'noindex' : 'index')) . ', ' . (isset($preview) && $preview ? 'nofollow' : ($animal->nofollow ? 'nofollow' : 'follow')))
@section('social-title', $animal->meta_title ?: $animal->name . ' | Wildlife Kingdom')

@if($animal->social_image || $animal->image)
    @section('social-image', $animal->social_image ?: Storage::url($animal->image))
@endif

@push('structured-data')
    @php
        $animalDescription = $animal->meta_description ?: ($animal->short_description ?: \Illuminate\Support\Str::limit(strip_tags($animal->description), 160));
        $animalPageSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $animal->meta_title ?: $animal->name,
            'description' => $animalDescription,
            'url' => $animal->canonical_url ?: (isset($preview) && $preview ? route('animal.show', $animal) : request()->url()),
            'mainEntity' => [
                '@type' => 'Thing',
                'name' => $animal->name,
                'description' => $animalDescription,
            ],
        ];
        if ($animal->image) {
            $animalPageSchema['primaryImageOfPage'] = [
                '@type' => 'ImageObject',
                'url' => Storage::url($animal->image),
            ];
        }
        $animalBreadcrumbSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => route('home')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Animals', 'item' => route('animals')],
                ['@type' => 'ListItem', 'position' => 3, 'name' => $animal->name, 'item' => $animal->canonical_url ?: (isset($preview) && $preview ? route('animal.show', $animal) : request()->url())],
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($animalPageSchema, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
    <script type="application/ld+json">{!! json_encode($animalBreadcrumbSchema, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
    @if($animal->faqs->isNotEmpty())
        @php
            $animalFaqSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => $animal->faqs->map(fn ($faq) => [
                    '@type' => 'Question',
                    'name' => $faq->question,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq->answer,
                    ],
                ])->values()->all(),
            ];
        @endphp
        <script type="application/ld+json">{!! json_encode($animalFaqSchema, JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) !!}</script>
    @endif
@endpush

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
      <img src="{{ $animal->image ? Storage::url($animal->image) : asset('assets/images/static/animal-placeholder.jpg') }}" alt="{{ $animal->image_alt_text ?: $animal->name }}" onerror="this.src='{{ asset('assets/images/static/animal-placeholder.jpg') }}'">
      @if($animal->image_caption || $animal->image_credit || $animal->image_source)
        <p>{{ $animal->image_caption }}@if($animal->image_credit) <span>Photo: {{ $animal->image_credit }}</span>@endif @if($animal->image_source) <span>Source: {{ $animal->image_source }}</span>@endif</p>
      @endif
    </div>

    <div class="detail-heading">
      <span class="detail-status">{{ $animal->conservation_status ?: ($animal->category ?: 'Wildlife') }}</span>
      <h1>{{ $animal->name }}</h1>
      @if($animal->scientific_name)
        <p class="sci">{{ $animal->scientific_name }}</p>
      @endif
      @if($animal->short_description)
        <p style="margin-top:20px; opacity:.85;">{{ $animal->short_description }}</p>
      @endif
      <p style="margin-top:20px; opacity:.85;">{!! nl2br(e($animal->description)) !!}</p>

      <div class="detail-facts">
        @foreach([
            'Kingdom' => $animal->kingdom,
            'Class' => $animal->animal_class,
            'Order' => $animal->animal_order,
            'Family' => $animal->family,
            'Genus' => $animal->genus,
            'Species' => $animal->species,
            'Habitat' => $animal->getRelation('habitat')?->name ?: $animal->getAttribute('habitat'),
            'Geographical range' => $animal->geographical_range,
            'Native countries' => $animal->native_countries,
            'Diet' => $animal->diet,
            'Lifespan' => $animal->lifespan,
            'Average weight' => $animal->average_weight,
            'Average height / length' => $animal->average_height_length,
            'Speed' => $animal->speed,
            'Conservation status' => $animal->conservation_status,
        ] as $label => $value)
          @if(filled($value))
            <div><span>{{ $label }}</span><strong>{{ $value }}</strong></div>
          @endif
        @endforeach
      </div>

      @if($animal->fun_fact)
        <div class="detail-funfact"><strong>Did you know?</strong> {{ $animal->fun_fact }}</div>
      @endif
      <a href="{{ route('animals') }}" class="btn btn-outline-dark" style="margin-top:28px;">&larr; Back to All Animals</a>
    </div>
  </div>
</section>

@foreach([
    'distribution' => 'Distribution',
    'behaviour' => 'Behaviour',
    'reproduction' => 'Reproduction',
    'ecological_role' => 'Ecological role',
    'population_information' => 'Population information',
    'major_threats' => 'Major threats',
    'conservation_efforts' => 'Conservation efforts',
] as $field => $heading)
  @if($animal->$field)
    <section class="section section-light">
      <div class="container">
        <h2>{{ $heading }}</h2>
        <p>{!! nl2br(e($animal->$field)) !!}</p>
      </div>
    </section>
  @endif
@endforeach

@if($animal->images->isNotEmpty())
  <section class="section section-light">
    <div class="container">
      <h2>Image gallery</h2>
      <div class="grid-3">
        @foreach($animal->images as $image)
          <div class="animal-card">
            <img src="{{ Storage::url($image->image) }}" alt="{{ $image->alt_text ?: $animal->name }}" onerror="this.src='{{ asset('assets/images/static/animal-placeholder.jpg') }}'">
            <div class="animal-card-body">
              @if($image->caption)<p>{{ $image->caption }}</p>@endif
              @if($image->credit)<p>Photo: {{ $image->credit }}</p>@endif
              @if($image->source)<p>Source: {{ $image->source }}</p>@endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endif

@if($animal->faqs->isNotEmpty())
  <section class="section section-light">
    <div class="container">
      <h2>Frequently asked questions</h2>
      @foreach($animal->faqs as $faq)
        <details>
          <summary>{{ $faq->question }}</summary>
          <p>{!! nl2br(e($faq->answer)) !!}</p>
        </details>
      @endforeach
    </div>
  </section>
@endif

@if($animal->relatedAnimals->isNotEmpty())
  <section class="section section-light">
    <div class="container">
      <h2>Related animals</h2>
      <div class="grid-3">
        @foreach($animal->relatedAnimals as $relatedAnimal)
          <div class="animal-card">
            <img src="{{ $relatedAnimal->image ? Storage::url($relatedAnimal->image) : asset('assets/images/static/animal-placeholder.jpg') }}" alt="{{ $relatedAnimal->image_alt_text ?: $relatedAnimal->name }}" onerror="this.src='{{ asset('assets/images/static/animal-placeholder.jpg') }}'">
            <div class="animal-card-body">
              <h3>{{ $relatedAnimal->name }}</h3>
              <a href="{{ route('animal.show', $relatedAnimal) }}">View Details &rarr;</a>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
@endif
@endsection
