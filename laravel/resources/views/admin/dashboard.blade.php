@extends('admin.layout')

@section('title', 'Wildlife Kingdom Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="stats-grid">
    <div class="stat-box">
        <div class="label">Total Animals</div>
        <div class="value">{{ $stats['animals'] }}</div>
    </div>
    <div class="stat-box">
        <div class="label">Total Habitats</div>
        <div class="value">{{ $stats['habitats'] }}</div>
    </div>
    <div class="stat-box">
        <div class="label">Upcoming Events</div>
        <div class="value">{{ $stats['upcoming_events'] }}</div>
    </div>
    <div class="stat-box">
        <div class="label">Gallery Images</div>
        <div class="value">{{ $stats['gallery_images'] }}</div>
    </div>
</div>

<div class="grid-two" style="margin-top:26px;">
    <div class="panel">
        <h3>Recent Animals</h3>
        @forelse($recentAnimals as $animal)
            <div style="padding:10px 0; border-bottom:1px solid #eee;">
                <strong>{{ $animal->name }}</strong><br>
                <small>{{ $animal->category }}</small>
            </div>
        @empty
            <div class="empty">No animals yet.</div>
        @endforelse
    </div>

    <div class="panel">
        <h3>Recent Habitats</h3>
        @forelse($recentHabitats as $habitat)
            <div style="padding:10px 0; border-bottom:1px solid #eee;">
                <strong>{{ $habitat->name }}</strong><br>
                <small>{{ Str::limit($habitat->short_description ?? $habitat->description, 80) }}</small>
            </div>
        @empty
            <div class="empty">No habitats yet.</div>
        @endforelse
    </div>
</div>

<div class="panel" style="margin-top:26px;">
    <h3>Upcoming Events</h3>
    @forelse($upcomingEvents as $event)
        <div style="padding:10px 0; border-bottom:1px solid #eee;">
            <strong>{{ $event->title }}</strong>
            <div>{{ $event->event_date->format('d M Y') }} at {{ $event->event_time }}</div>
        </div>
    @empty
        <div class="empty">No upcoming events scheduled.</div>
    @endforelse
</div>

<div class="panel" style="margin-top:26px;">
    <h3>Recent Gallery</h3>
    @forelse($recentGallery as $item)
        <div style="padding:10px 0; border-bottom:1px solid #eee;">
            <strong>{{ $item->title }}</strong>
            <div>{{ $item->category }}</div>
        </div>
    @empty
        <div class="empty">No gallery images yet.</div>
    @endforelse
</div>
@endsection
