@extends('admin.layout')

@section('title', 'Wildlife Kingdom Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .welcome { position: relative; overflow: hidden; background: linear-gradient(120deg, #0B2F22 0%, #14412f 60%, #1d5a40 100%); color: #fff; border-radius: 20px; padding: 30px 34px; margin-bottom: 24px; box-shadow: 0 14px 34px rgba(11,47,34,.25); }
    .welcome h2 { position: relative; z-index: 1; margin: 0 0 8px; font-family: 'Playfair Display', serif; font-size: 1.8rem; color: #f6d9ae; }
    .welcome p { position: relative; z-index: 1; margin: 0; color: rgba(255,255,255,.82); max-width: 560px; line-height: 1.55; }
    .welcome .date { position: relative; z-index: 1; margin-top: 16px; display: inline-block; background: rgba(255,255,255,.1); padding: 6px 14px; border-radius: 999px; font-size: .88rem; }

    .stat-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 18px; margin-bottom: 24px; }
    .stat-card { display: flex; align-items: center; gap: 16px; background: #fff; border-radius: 18px; padding: 20px; text-decoration: none; box-shadow: 0 8px 28px rgba(20,33,26,.07); border: 1px solid rgba(235,222,194,.5); transition: transform .2s, box-shadow .2s; }
    .stat-card:hover { transform: translateY(-3px); box-shadow: 0 14px 32px rgba(20,33,26,.12); }
    .stat-icon { width: 54px; height: 54px; border-radius: 14px; display: grid; place-items: center; flex-shrink: 0; }
    .stat-icon svg { width: 26px; height: 26px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .stat-icon.green { background: #e3f1e9; color: #14633f; }
    .stat-icon.gold { background: #fcefdc; color: #b8741f; }
    .stat-icon.brown { background: #f1e6dc; color: #8a5a36; }
    .stat-icon.olive { background: #eef0d9; color: #66701c; }
    .stat-card .num { font-size: 2rem; font-weight: 700; line-height: 1; color: var(--forest); }
    .stat-card .txt { color: var(--muted); font-size: .92rem; margin-top: 6px; }

    .dash-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 24px; margin-bottom: 24px; }
    .panel-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px; }
    .panel-head h3 { margin: 0; font-family: 'Playfair Display', serif; font-size: 1.25rem; color: var(--forest); }
    .panel-head a { color: var(--gold-deep); font-weight: 600; font-size: .9rem; text-decoration: none; }
    .panel-head a:hover { text-decoration: underline; }

    .row-item { display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 1px solid var(--line); }
    .row-item:last-child { border-bottom: 0; padding-bottom: 0; }
    .row-avatar { width: 42px; height: 42px; border-radius: 12px; background: var(--forest); color: var(--gold); display: grid; place-items: center; font-weight: 700; flex-shrink: 0; }
    .row-avatar.alt { background: #fcefdc; color: #b8741f; }
    .row-body { flex: 1; min-width: 0; }
    .row-title { font-weight: 600; color: var(--ink); }
    .row-sub { color: var(--muted); font-size: .88rem; margin-top: 2px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .badge { background: #e3f1e9; color: #14633f; font-size: .78rem; font-weight: 600; padding: 4px 12px; border-radius: 999px; white-space: nowrap; }
    .badge.gold { background: #fcefdc; color: #a8681a; }

    .date-box { width: 50px; border-radius: 12px; background: var(--forest); color: #fff; text-align: center; padding: 6px 0; flex-shrink: 0; }
    .date-box .d { font-size: 1.15rem; font-weight: 700; line-height: 1.1; color: var(--gold); }
    .date-box .m { font-size: .72rem; opacity: .85; }

    .empty-state { text-align: center; padding: 22px 10px; color: var(--muted); }
    .empty-state a { color: var(--gold-deep); font-weight: 600; text-decoration: none; }

    @media (max-width: 900px) { .dash-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')

<section class="welcome">
    <h2>Welcome back</h2>
    <p>Your park currently has {{ $stats['animals'] }} {{ Str::plural('animal', $stats['animals']) }} living across {{ $stats['habitats'] }} {{ Str::plural('habitat', $stats['habitats']) }}, with {{ $stats['upcoming_events'] }} upcoming {{ Str::plural('event', $stats['upcoming_events']) }}.</p>
    <span class="date">{{ now()->format('l, d F Y') }}</span>
</section>

<div class="stat-cards">
    <a href="{{ route('admin.animals.index') }}" class="stat-card">
        <div class="stat-icon green">
            <svg viewBox="0 0 24 24"><circle cx="11" cy="4" r="2"/><circle cx="18" cy="8" r="2"/><circle cx="20" cy="16" r="2"/><path d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z"/></svg>
        </div>
        <div>
            <div class="num">{{ $stats['animals'] }}</div>
            <div class="txt">Total animals</div>
        </div>
    </a>
    <a href="{{ route('admin.habitats.index') }}" class="stat-card">
        <div class="stat-icon gold">
            <svg viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
        </div>
        <div>
            <div class="num">{{ $stats['habitats'] }}</div>
            <div class="txt">Total habitats</div>
        </div>
    </a>
    <a href="{{ route('admin.events.index') }}" class="stat-card">
        <div class="stat-icon brown">
            <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        </div>
        <div>
            <div class="num">{{ $stats['upcoming_events'] }}</div>
            <div class="txt">Upcoming events</div>
        </div>
    </a>
    <a href="{{ route('admin.gallery.index') }}" class="stat-card">
        <div class="stat-icon olive">
            <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/></svg>
        </div>
        <div>
            <div class="num">{{ $stats['gallery_images'] }}</div>
            <div class="txt">Gallery images</div>
        </div>
    </a>
</div>

<div class="dash-grid">
    <div class="panel">
        <div class="panel-head">
            <h3>Recent animals</h3>
            <a href="{{ route('admin.animals.index') }}">View all</a>
        </div>
        @forelse($recentAnimals as $animal)
            <div class="row-item">
                <div class="row-avatar">{{ strtoupper(substr($animal->name, 0, 1)) }}</div>
                <div class="row-body">
                    <div class="row-title">{{ $animal->name }}</div>
                </div>
                <span class="badge">{{ $animal->category }}</span>
            </div>
        @empty
            <div class="empty-state">No animals yet. <a href="{{ route('admin.animals.index') }}">Add your first animal</a></div>
        @endforelse
    </div>

    <div class="panel">
        <div class="panel-head">
            <h3>Recent habitats</h3>
            <a href="{{ route('admin.habitats.index') }}">View all</a>
        </div>
        @forelse($recentHabitats as $habitat)
            <div class="row-item">
                <div class="row-avatar alt">{{ strtoupper(substr($habitat->name, 0, 1)) }}</div>
                <div class="row-body">
                    <div class="row-title">{{ $habitat->name }}</div>
                    <div class="row-sub">{{ Str::limit($habitat->short_description ?? $habitat->description, 60) }}</div>
                </div>
            </div>
        @empty
            <div class="empty-state">No habitats yet. <a href="{{ route('admin.habitats.index') }}">Add your first habitat</a></div>
        @endforelse
    </div>
</div>

<div class="dash-grid">
    <div class="panel">
        <div class="panel-head">
            <h3>Upcoming events</h3>
            <a href="{{ route('admin.events.index') }}">View all</a>
        </div>
        @forelse($upcomingEvents as $event)
            <div class="row-item">
                <div class="date-box">
                    <div class="d">{{ $event->event_date->format('d') }}</div>
                    <div class="m">{{ $event->event_date->format('M') }}</div>
                </div>
                <div class="row-body">
                    <div class="row-title">{{ $event->title }}</div>
                    <div class="row-sub">{{ $event->event_date->format('d M Y') }} at {{ $event->event_time }}</div>
                </div>
            </div>
        @empty
            <div class="empty-state">No upcoming events scheduled. <a href="{{ route('admin.events.index') }}">Schedule an event</a></div>
        @endforelse
    </div>

    <div class="panel">
        <div class="panel-head">
            <h3>Recent gallery</h3>
            <a href="{{ route('admin.gallery.index') }}">View all</a>
        </div>
        @forelse($recentGallery as $item)
            <div class="row-item">
                <div class="row-avatar alt">{{ strtoupper(substr($item->title, 0, 1)) }}</div>
                <div class="row-body">
                    <div class="row-title">{{ $item->title }}</div>
                </div>
                <span class="badge gold">{{ $item->category }}</span>
            </div>
        @empty
            <div class="empty-state">No gallery images yet. <a href="{{ route('admin.gallery.index') }}">Upload an image</a></div>
        @endforelse
    </div>
</div>
@endsection