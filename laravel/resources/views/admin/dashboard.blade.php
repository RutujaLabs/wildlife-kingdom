@extends('admin.layout')

@section('title', 'Wildlife Kingdom Dashboard')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    .hero { position: relative; width: 100%; aspect-ratio: 2.5 / 1; max-height: 300px; min-height: 150px; overflow: hidden; background: #cfe3d6; }
    .hero img { width: 100%; height: 100%; object-fit: cover; object-position: 50% 42%; display: block; }

    .dash { display: grid; gap: 22px; }
    .ico { fill: none; stroke: currentColor; stroke-width: 1.9; stroke-linecap: round; stroke-linejoin: round; }

    /* Stat cards */
    .stat-row { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 20px; }
    .stat { position: relative; overflow: hidden; display: flex; gap: 16px; align-items: flex-start; padding: 20px 22px; border-radius: 18px; text-decoration: none; border: 1px solid; transition: transform .2s, box-shadow .2s; }
    .stat:hover { transform: translateY(-3px); box-shadow: 0 14px 30px rgba(20,33,26,.12); }
    .stat .circle { width: 52px; height: 52px; border-radius: 50%; display: grid; place-items: center; color: #fff; flex-shrink: 0; }
    .stat .circle svg { width: 26px; height: 26px; }
    .stat .label { color: #2d3a38; font-weight: 500; font-size: .98rem; }
    .stat .num { font-size: 2.2rem; font-weight: 700; line-height: 1.1; color: #14201f; margin: 2px 0 6px; }
    .stat .trend { display: flex; align-items: center; gap: 6px; font-size: .8rem; color: #4b5a57; }
    .stat .trend svg { width: 13px; height: 13px; }
    .stat .bg-ico { position: absolute; right: 16px; bottom: 12px; width: 62px; height: 62px; opacity: .13; }
    .stat.s1 { background: linear-gradient(135deg, #eaf4ee, #f7fbf8); border-color: #d3e8da; }
    .stat.s1 .circle { background: #146c43; }
    .stat.s2 { background: linear-gradient(135deg, #eaf5ec, #f7fbf8); border-color: #d3e8da; }
    .stat.s2 .circle { background: #1f9d55; }
    .stat.s3 { background: linear-gradient(135deg, #fdf1e4, #fffaf5); border-color: #f6dfc3; }
    .stat.s3 .circle { background: #f28a1f; }
    .stat.s4 { background: linear-gradient(135deg, #efeafc, #f9f7ff); border-color: #ddd3f6; }
    .stat.s4 .circle { background: #7a4fd1; }

    /* Cards */
    .card { background: #fff; border-radius: 18px; padding: 22px; border: 1px solid #efe9dc; box-shadow: 0 8px 26px rgba(20,33,26,.06); }
    .card-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 14px; }
    .card-head .t { display: flex; align-items: center; gap: 10px; }
    .card-head .t svg { width: 22px; height: 22px; color: #14201f; }
    .card-head h3 { margin: 0; font-size: 1.12rem; font-weight: 600; color: #14201f; }
    .card-head .sub { color: var(--muted); font-size: .85rem; margin-top: 2px; }
    .view-all { display: inline-flex; align-items: center; gap: 6px; color: #14201f; text-decoration: none; font-size: .85rem; font-weight: 500; }
    .view-all svg { width: 14px; height: 14px; }
    .view-all:hover { color: #14633f; }

    .row-2 { display: grid; grid-template-columns: minmax(0, 1.4fr) minmax(0, 1fr); gap: 22px; }
    .row-3 { display: grid; grid-template-columns: minmax(0, 1.35fr) minmax(0, 1fr) minmax(0, .8fr); grid-template-rows: 430px; gap: 22px; align-items: stretch; }

    /* Chart */
    .range { display: inline-flex; align-items: center; gap: 8px; border: 1px solid #e6dfd0; border-radius: 10px; padding: 7px 12px; font-size: .84rem; background: #fff; color: #2d3a38; }
    .range svg { width: 13px; height: 13px; }
    .sample-tag { display: inline-block; margin-left: 8px; font-size: .7rem; font-weight: 600; background: #fcefdc; color: #a8681a; padding: 2px 10px; border-radius: 999px; vertical-align: middle; }
    #visitorChart { width: 100%; height: auto; display: block; }

    /* Quick actions */
    .qa-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    .row-2 > .card:last-child { display: flex; flex-direction: column; }
    .row-2 .qa-grid { flex: 1; grid-template-rows: repeat(2, minmax(0, 1fr)); }
    .qa { position: relative; display: flex; align-items: center; gap: 14px; padding: 18px 16px; border-radius: 14px; text-decoration: none; transition: transform .2s, box-shadow .2s; }
    .qa:hover { transform: translateY(-2px); box-shadow: 0 10px 22px rgba(20,33,26,.1); }
    .qa .qi { width: 46px; height: 46px; border-radius: 50%; display: grid; place-items: center; flex-shrink: 0; }
    .qa .qi svg { width: 24px; height: 24px; }
    .qa .qt { font-weight: 600; color: #14201f; font-size: .98rem; }
    .qa .qs { color: #5c6967; font-size: .8rem; margin-top: 2px; }
    .qa .arrow { position: absolute; right: 14px; top: 14px; width: 16px; height: 16px; color: #2d3a38; }
    .qa.g { background: #e7f3ec; } .qa.g .qi { background: #d4eadc; color: #146c43; }
    .qa.o { background: #fdf0e3; } .qa.o .qi { background: #fbddc0; color: #e0701a; }
    .qa.p { background: #eeeafc; } .qa.p .qi { background: #ddd4f7; color: #6a41c4; }

    /* Animals table */
    .mini-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
    .mini-table th { text-align: left; }
    .clamp1, .clamp2 { display: -webkit-box; -webkit-box-orient: vertical; overflow: hidden; word-break: break-word; line-height: 1.35; }
    .clamp1 { -webkit-line-clamp: 1; line-clamp: 1; }
    .clamp2 { -webkit-line-clamp: 2; line-clamp: 2; }
    .pill { white-space: nowrap; }
    .mini-table th { background: #f6f3ec; color: #4b5a57; font-size: .78rem; font-weight: 600; padding: 9px 10px; border: 0; }
    .mini-table th:first-child { border-radius: 8px 0 0 8px; } .mini-table th:last-child { border-radius: 0 8px 8px 0; }
    .mini-table td { padding: 9px 10px; border-bottom: 1px solid #f1ece0; font-size: .86rem; vertical-align: middle; }
    .mini-table tr:last-child td { border-bottom: 0; }
    .thumb { position: relative; width: 48px; height: 40px; border-radius: 8px; overflow: hidden; background: #0B2F22; color: #F5C77E; display: grid; place-items: center; font-weight: 700; }
    .thumb img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .a-name { font-weight: 600; color: #14201f; }
    .pill { display: inline-block; padding: 4px 14px; border-radius: 999px; font-size: .78rem; font-weight: 600; background: #dcf1e3; color: #14633f; }
    .pill.draft { background: #f0ece4; color: #6b655c; }

    /* Activity */
    .act { display: flex; align-items: center; gap: 12px; padding: 10px 0; border-bottom: 1px solid #f3eee3; }
    .act:last-child { border-bottom: 0; }
    .act .ai { width: 40px; height: 40px; border-radius: 50%; display: grid; place-items: center; flex-shrink: 0; }
    .act .ai svg { width: 20px; height: 20px; }
    .act .ai.green { background: #dcf1e3; color: #146c43; }
    .act .ai.orange { background: #fde5cb; color: #e0701a; }
    .act .ai.blue { background: #dfe6fb; color: #3b5bdb; }
    .act .at { font-weight: 600; font-size: .88rem; color: #14201f; }
    .act .as { color: #5c6967; font-size: .8rem; margin-top: 1px; }
    .act .tm { margin-left: auto; color: #6c6a69; font-size: .76rem; white-space: nowrap; }
    .act-body { min-width: 0; }
    .empty-note { color: var(--muted); font-size: .9rem; padding: 18px 0; text-align: center; }

    /* Bottom row: teeno blocks ki height barabar aur fixed. Naye records aane par bhi stretch nahi hota. */
    .row-3 > .card { display: flex; flex-direction: column; min-width: 0; overflow: hidden; }
    .row-3 > .card .card-head { flex-shrink: 0; }
    .row-3 .table-wrap, .row-3 .act-list { flex: 1; min-height: 0; overflow: hidden; }
    .act .at, .act .as { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* Animals table -> rows card ki poori height barabar bhar dete hain */
    .row-3 .table-wrap { display: flex; flex-direction: column; }
    .row-3 .mini-table { display: flex; flex-direction: column; flex: 1; min-height: 0; }
    .row-3 .mini-table thead { display: block; flex-shrink: 0; }
    .row-3 .mini-table tbody { display: flex; flex-direction: column; flex: 1; min-height: 0; }
    .row-3 .mini-table tr { display: grid; grid-template-columns: 72px minmax(0, 1.1fr) minmax(0, .9fr) minmax(0, 1.7fr) 96px; align-items: center; }
    .row-3 .mini-table th, .row-3 .mini-table td { min-width: 0; }
    .row-3 .mini-table td { border-bottom: 0; }
    .row-3 .mini-table tbody tr { flex: 1 1 0; min-height: 58px; max-height: 92px; border-bottom: 1px solid #f1ece0; }
    .row-3 .mini-table tbody tr:last-child { border-bottom: 0; }

    /* Activity list -> items bhi poori height barabar bhar dete hain */
    .row-3 .act-list { display: flex; flex-direction: column; }
    .row-3 .act { flex: 1 1 0; min-height: 62px; max-height: 84px; }

    /* Quote card */
    .quote-card { position: relative; overflow: hidden; border-radius: 18px; min-height: 0; display: flex; flex-direction: column; justify-content: flex-end; padding: 0 18px 22px; color: #fff; text-align: center; background-color: #0b2a1d; background-image: linear-gradient(180deg, rgba(8,32,22,0) 60%, rgba(8,32,22,.8) 85%, rgba(8,32,22,.95) 100%), var(--quote-img, none); background-size: 100% 100%, cover; background-position: center, center top; background-repeat: no-repeat; box-shadow: 0 8px 26px rgba(20,33,26,.12); }
    .quote-card svg.scene { position: absolute; inset: 0; width: 100%; height: 100%; }
    .quote-card .q, .quote-card .by, .quote-card .lf { position: relative; z-index: 1; }
    .quote-card .q { font-family: 'Playfair Display', serif; font-size: 1.12rem; line-height: 1.35; text-shadow: 0 2px 10px rgba(0,0,0,.55); }
    .quote-card .by { margin-top: 10px; font-size: .8rem; color: rgba(255,255,255,.9); }
    .quote-card .lf { margin: 10px auto 0; width: 18px; height: 18px; fill: #6cc788; }

    @media (max-width: 1200px) {
        .stat-row { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .row-3 { grid-template-columns: 1fr 1fr; grid-template-rows: 430px 300px; }
        .quote-card { grid-column: 1 / -1; background-position: center, center 30%; }
    }
    @media (max-width: 900px) {
        .row-2, .row-3 { grid-template-columns: 1fr; }
        .row-3 { grid-template-rows: none; }
        .row-3 > .card { height: auto; }
        .row-3 .table-wrap, .row-3 .act-list { overflow: visible; }
        .quote-card { height: 320px; }
    }
    @media (max-width: 560px) {
        .stat-row { grid-template-columns: 1fr; }
        .qa-grid { grid-template-columns: 1fr; grid-template-rows: none; }
        .mini-table .hide-xs { display: none; }
        .row-3 .mini-table tr { grid-template-columns: 72px minmax(0, 1fr) 96px; }
    }
</style>
@endpush

@section('hero')
<div class="hero">
    <img src="{{ asset('images/wildlife-guardian-banner.webp') }}" alt="Welcome back, Wildlife Guardian">
</div>
@endsection

@section('content')
@php
    // ---- Image URL finder (works with public/, public/storage and storage/app/public) ----
    if (!function_exists('wk_animal_image_url')) {
        function wk_animal_image_url($animal) {
            $raw = null;
            foreach (['image', 'image_path', 'image_url', 'photo', 'thumbnail', 'featured_image'] as $col) {
                $v = $animal->getAttribute($col);
                if (!empty($v) && is_string($v)) { $raw = $v; break; }
            }
            if (!$raw) return null;
            if (preg_match('#^https?://#i', $raw)) return $raw;

            $path = ltrim(str_replace('\\', '/', $raw), '/');
            $candidates = [
                [public_path($path), $path],
                [public_path('storage/' . $path), 'storage/' . $path],
                [storage_path('app/public/' . $path), 'storage/' . $path],
            ];
            if (strpos($path, 'public/') === 0) {
                $t = substr($path, 7);
                $candidates[] = [storage_path('app/public/' . $t), 'storage/' . $t];
                $candidates[] = [public_path($t), $t];
            }
            if (strpos($path, 'storage/') === 0) {
                $t = substr($path, 8);
                $candidates[] = [storage_path('app/public/' . $t), $path];
            }
            foreach ($candidates as $c) {
                if (is_file($c[0])) return asset($c[1]);
            }
            return asset(strpos($path, 'storage/') === 0 ? $path : 'storage/' . $path);
        }
    }

    // ---- "this month" counts (hidden automatically if the model is not found) ----
    $monthly = function (array $classes) {
        foreach ($classes as $c) {
            if (class_exists($c)) {
                try { return $c::where('created_at', '>=', now()->startOfMonth())->count(); }
                catch (\Throwable $e) { return null; }
            }
        }
        return null;
    };
    $m = [
        'animals'  => $monthly(['App\\Models\\Animal']),
        'habitats' => $monthly(['App\\Models\\Habitat']),
        'events'   => $monthly(['App\\Models\\Event']),
        'gallery'  => $monthly(['App\\Models\\GalleryImage', 'App\\Models\\Gallery', 'App\\Models\\GalleryItem']),
    ];

    // ---- Quick action links (fall back to the list page if "create" route is missing) ----
    $go = function ($create, $index) {
        return \Illuminate\Support\Facades\Route::has($create) ? route($create) : route($index);
    };

    // ---- Recent activity built from real records ----
    $activity = collect();
    foreach ($recentAnimals as $a)   { $activity->push(['ts' => $a->created_at, 'icon' => 'paw',   'color' => 'green',  'title' => 'New animal added',       'sub' => $a->name]); }
    foreach ($recentHabitats as $h)  { $activity->push(['ts' => $h->created_at, 'icon' => 'leaf',  'color' => 'green',  'title' => 'Habitat added',          'sub' => $h->name]); }
    foreach ($upcomingEvents as $e)  { $activity->push(['ts' => $e->created_at, 'icon' => 'cal',   'color' => 'orange', 'title' => 'Event scheduled',        'sub' => $e->title]); }
    foreach ($recentGallery as $g)   { $activity->push(['ts' => $g->created_at, 'icon' => 'image', 'color' => 'blue',   'title' => 'Gallery image uploaded', 'sub' => $g->title]); }
    $activity = $activity->filter(fn ($i) => !empty($i['ts']))->sortByDesc(fn ($i) => $i['ts']->timestamp)->take(5);
@endphp

<div class="dash">

    {{-- Stat cards --}}
    <div class="stat-row">
        <a href="{{ route('admin.animals.index') }}" class="stat s1">
            <div class="circle"><svg class="ico" viewBox="0 0 24 24"><circle cx="11" cy="4" r="2"/><circle cx="18" cy="8" r="2"/><circle cx="20" cy="16" r="2"/><path d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z"/></svg></div>
            <div>
                <div class="label">Total Animals</div>
                <div class="num">{{ $stats['animals'] }}</div>
                @if($m['animals'] !== null)<div class="trend"><svg class="ico" viewBox="0 0 24 24"><path d="M12 19V5M5 12l7-7 7 7"/></svg>+{{ $m['animals'] }} this month</div>@endif
            </div>
            <svg class="ico bg-ico" viewBox="0 0 24 24"><circle cx="11" cy="4" r="2"/><circle cx="18" cy="8" r="2"/><circle cx="20" cy="16" r="2"/><path d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z"/></svg>
        </a>

        <a href="{{ route('admin.habitats.index') }}" class="stat s2">
            <div class="circle"><svg class="ico" viewBox="0 0 24 24"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg></div>
            <div>
                <div class="label">Habitats</div>
                <div class="num">{{ $stats['habitats'] }}</div>
                @if($m['habitats'] !== null)<div class="trend"><svg class="ico" viewBox="0 0 24 24"><path d="M12 19V5M5 12l7-7 7 7"/></svg>+{{ $m['habitats'] }} this month</div>@endif
            </div>
            <svg class="ico bg-ico" viewBox="0 0 24 24"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
        </a>

        <a href="{{ route('admin.events.index') }}" class="stat s3">
            <div class="circle"><svg class="ico" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div>
            <div>
                <div class="label">Upcoming Events</div>
                <div class="num">{{ $stats['upcoming_events'] }}</div>
                @if($m['events'] !== null)<div class="trend"><svg class="ico" viewBox="0 0 24 24"><path d="M12 19V5M5 12l7-7 7 7"/></svg>+{{ $m['events'] }} this month</div>@endif
            </div>
            <svg class="ico bg-ico" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
        </a>

        <a href="{{ route('admin.gallery.index') }}" class="stat s4">
            <div class="circle"><svg class="ico" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/></svg></div>
            <div>
                <div class="label">Gallery Images</div>
                <div class="num">{{ $stats['gallery_images'] }}</div>
                @if($m['gallery'] !== null)<div class="trend"><svg class="ico" viewBox="0 0 24 24"><path d="M12 19V5M5 12l7-7 7 7"/></svg>+{{ $m['gallery'] }} this month</div>@endif
            </div>
            <svg class="ico bg-ico" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/></svg>
        </a>
    </div>

    {{-- Visitors + Quick actions --}}
    <div class="row-2">
        <div class="card">
            <div class="card-head">
                <div class="t">
                    <svg class="ico" viewBox="0 0 24 24"><circle cx="11" cy="4" r="2"/><circle cx="18" cy="8" r="2"/><circle cx="20" cy="16" r="2"/><path d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z"/></svg>
                    <div>
                        <h3>Website Visitors <span class="sample-tag">Sample data</span></h3>
                        <div class="sub">Last 7 Days</div>
                    </div>
                </div>
                <div class="range">Last 7 Days <svg class="ico" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg></div>
            </div>
            <svg id="visitorChart" viewBox="0 0 640 250" role="img" aria-label="Website visitors in the last 7 days"></svg>
        </div>

        <div class="card">
            <div class="card-head">
                <div class="t">
                    <svg class="ico" viewBox="0 0 24 24"><path d="M13 2 3 14h9l-1 8 10-12h-9l1-8z"/></svg>
                    <h3>Quick Actions</h3>
                </div>
            </div>
            <div class="qa-grid">
                <a href="{{ $go('admin.animals.create', 'admin.animals.index') }}" class="qa g">
                    <div class="qi"><svg class="ico" viewBox="0 0 24 24"><circle cx="11" cy="4" r="2"/><circle cx="18" cy="8" r="2"/><circle cx="20" cy="16" r="2"/><path d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z"/></svg></div>
                    <div><div class="qt">Add Animal</div><div class="qs">Register a new animal</div></div>
                    <svg class="ico arrow" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ $go('admin.habitats.create', 'admin.habitats.index') }}" class="qa g">
                    <div class="qi"><svg class="ico" viewBox="0 0 24 24"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg></div>
                    <div><div class="qt">Add Habitat</div><div class="qs">Create new habitat</div></div>
                    <svg class="ico arrow" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ $go('admin.events.create', 'admin.events.index') }}" class="qa o">
                    <div class="qi"><svg class="ico" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg></div>
                    <div><div class="qt">Add Event</div><div class="qs">Schedule an event</div></div>
                    <svg class="ico arrow" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ $go('admin.gallery.create', 'admin.gallery.index') }}" class="qa p">
                    <div class="qi"><svg class="ico" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/></svg></div>
                    <div><div class="qt">Upload Gallery</div><div class="qs">Add images &amp; videos</div></div>
                    <svg class="ico arrow" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>

    {{-- Recent animals + Activity + Quote --}}
    <div class="row-3">
        <div class="card">
            <div class="card-head">
                <div class="t">
                    <svg class="ico" viewBox="0 0 24 24"><circle cx="11" cy="4" r="2"/><circle cx="18" cy="8" r="2"/><circle cx="20" cy="16" r="2"/><path d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z"/></svg>
                    <h3>Recent Animals</h3>
                </div>
                <a href="{{ route('admin.animals.index') }}" class="view-all">View All</a>
            </div>
            @if($recentAnimals->count())
                <div class="table-wrap">
                    <table class="mini-table">
                        <thead>
                            <tr><th>Image</th><th>Name</th><th class="hide-xs">Species</th><th class="hide-xs">Habitat</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @foreach($recentAnimals->take(4) as $animal)
                                @php
                                    $thumbUrl = wk_animal_image_url($animal);
                                    $hv = $animal->habitat;
                                    $habitatName = is_object($hv) ? ($hv->name ?? null) : $hv;
                                    $isActive = strtolower((string) $animal->status) === 'published';
                                @endphp
                                <tr>
                                    <td>
                                        <div class="thumb">
                                            {{ strtoupper(substr($animal->name, 0, 1)) }}
                                            @if($thumbUrl)<img src="{{ $thumbUrl }}" alt="{{ $animal->name }}" loading="lazy" onerror="this.remove()">@endif
                                        </div>
                                    </td>
                                    <td class="a-name"><div class="clamp2">{{ $animal->name }}</div></td>
                                    <td class="hide-xs"><div class="clamp1">{{ $animal->category ?? '-' }}</div></td>
                                    <td class="hide-xs"><div class="clamp2" title="{{ $habitatName }}">{{ !empty($habitatName) ? $habitatName : '-' }}</div></td>
                                    <td><span class="pill {{ $isActive ? '' : 'draft' }}">{{ $isActive ? 'Active' : ucfirst((string) $animal->status) }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-note">No animals yet.</div>
            @endif
        </div>

        <div class="card">
            <div class="card-head">
                <div class="t">
                    <svg class="ico" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                    <h3>Recent Activity</h3>
                </div>
                <a href="{{ route('admin.animals.index') }}" class="view-all">View All <svg class="ico" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg></a>
            </div>
            <div class="act-list">
            @forelse($activity as $item)
                <div class="act">
                    <div class="ai {{ $item['color'] }}">
                        @if($item['icon'] === 'paw')
                            <svg class="ico" viewBox="0 0 24 24"><circle cx="11" cy="4" r="2"/><circle cx="18" cy="8" r="2"/><circle cx="20" cy="16" r="2"/><path d="M9 10a5 5 0 0 1 5 5v3.5a3.5 3.5 0 0 1-6.84 1.045Q6.52 17.48 4.46 16.84A3.5 3.5 0 0 1 5.5 10Z"/></svg>
                        @elseif($item['icon'] === 'leaf')
                            <svg class="ico" viewBox="0 0 24 24"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/></svg>
                        @elseif($item['icon'] === 'cal')
                            <svg class="ico" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                        @else
                            <svg class="ico" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/></svg>
                        @endif
                    </div>
                    <div class="act-body">
                        <div class="at">{{ $item['title'] }}</div>
                        <div class="as">{{ Str::limit($item['sub'], 28) }}</div>
                    </div>
                    <div class="tm">{{ $item['ts']->diffForHumans(null, true, true) }} ago</div>
                </div>
            @empty
                <div class="empty-note">No activity yet.</div>
            @endforelse
            </div>
        </div>

        <div class="quote-card" style="--quote-img: url('{{ asset('images/wildlife-kingdom-quote.png') }}');">
            
            <div class="q">&ldquo;A healthier planet is a<br>brighter future for all<br>living beings.&rdquo;</div>
            <div class="by">&mdash; Wildlife Kingdom</div>
            <svg class="lf" viewBox="0 0 24 24"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/></svg>
        </div>
    </div>
</div>

<script>
    (function () {
        var svg = document.getElementById('visitorChart');
        if (!svg) return;
        var data = [40, 62, 100, 66, 98, 130, 160];
        var max = 200, W = 640, H = 250, padL = 44, padR = 34, padT = 14, padB = 34;
        var iw = W - padL - padR, ih = H - padT - padB, n = data.length;
        var ns = 'http://www.w3.org/2000/svg';
        function el(name, attrs) {
            var e = document.createElementNS(ns, name);
            for (var k in attrs) e.setAttribute(k, attrs[k]);
            return e;
        }
        function xAt(i) { return padL + i * (iw / (n - 1)); }
        function yAt(v) { return padT + ih - (v / max) * ih; }

        var defs = el('defs', {});
        var grad = el('linearGradient', { id: 'visGrad', x1: 0, y1: 0, x2: 0, y2: 1 });
        grad.appendChild(el('stop', { offset: '0%', 'stop-color': '#2fa866', 'stop-opacity': '0.35' }));
        grad.appendChild(el('stop', { offset: '100%', 'stop-color': '#2fa866', 'stop-opacity': '0.02' }));
        defs.appendChild(grad);
        svg.appendChild(defs);

        [0, 50, 100, 150, 200].forEach(function (v) {
            var y = yAt(v);
            svg.appendChild(el('line', { x1: padL, x2: W - padR, y1: y, y2: y, stroke: '#ebe6da', 'stroke-width': 1 }));
            var t = el('text', { x: padL - 10, y: y + 4, 'text-anchor': 'end', 'font-size': 12, fill: '#6c6a69', 'font-family': 'Outfit, sans-serif' });
            t.textContent = v;
            svg.appendChild(t);
        });

        var line = '', area = 'M' + xAt(0) + ',' + yAt(0) + ' ';
        data.forEach(function (v, i) {
            line += (i ? ' L' : 'M') + xAt(i) + ',' + yAt(v);
            area += 'L' + xAt(i) + ',' + yAt(v) + ' ';
        });
        area += 'L' + xAt(n - 1) + ',' + yAt(0) + ' Z';
        svg.appendChild(el('path', { d: area, fill: 'url(#visGrad)' }));
        svg.appendChild(el('path', { d: line, fill: 'none', stroke: '#1d9a55', 'stroke-width': 2.4, 'stroke-linejoin': 'round', 'stroke-linecap': 'round' }));

        data.forEach(function (v, i) {
            svg.appendChild(el('circle', { cx: xAt(i), cy: yAt(v), r: 3.8, fill: '#1d9a55', stroke: '#fff', 'stroke-width': 1.5 }));
            var d = new Date();
            d.setDate(d.getDate() - (n - 1 - i));
            var label = d.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            var t = el('text', { x: xAt(i), y: H - 10, 'text-anchor': 'middle', 'font-size': 12, fill: '#4b5a57', 'font-family': 'Outfit, sans-serif' });
            t.textContent = label;
            svg.appendChild(t);
        });
    })();
</script>
@endsection