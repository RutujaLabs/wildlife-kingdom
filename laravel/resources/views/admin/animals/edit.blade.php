@extends('admin.layout')

@section('title', 'Habitats')
@section('page-title', 'Habitats')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<style>
    .hb-wrap { --hb-forest: #0f3d24; --hb-green: #1f7a4a; --hb-orange: #f28c1e; --hb-ink: #1d2b24; --hb-muted: #6f7a73; --hb-line: #ece8df; }
    .hb-wrap * { box-sizing: border-box; }

    /* ---------- Hero banner ---------- */
    .hb-hero { position: relative; overflow: hidden; border-radius: 24px; padding: 26px 30px; margin-bottom: 18px; min-height: 150px; display: flex; align-items: center; justify-content: space-between; gap: 20px; background: #eef3e4; box-shadow: 0 4px 20px rgba(30,45,36,.06); }
    .hb-hero-art, .hb-hero-img { position: absolute; inset: 0; width: 100%; height: 100%; z-index: 0; }
    .hb-hero-img { object-fit: cover; }
    .hb-hero::before { content: ''; position: absolute; inset: 0; z-index: 1; background: linear-gradient(90deg, #f4f1e6 0%, rgba(244,241,230,.92) 28%, rgba(244,241,230,.35) 55%, rgba(244,241,230,0) 75%); }
    .hb-hero > *:not(.hb-hero-art):not(.hb-hero-img) { position: relative; z-index: 2; }

    .hb-crumb { font-size: .95rem; color: var(--hb-muted); margin-bottom: 6px; }
    .hb-crumb a { color: var(--hb-muted); text-decoration: none; }
    .hb-crumb .current { color: var(--hb-forest); font-weight: 600; }
    .hb-crumb .sep { margin: 0 8px; }
    .hb-title { display: flex; align-items: center; gap: 12px; margin: 0; font-family: 'Playfair Display', Georgia, serif; font-size: 2.7rem; font-weight: 700; color: var(--hb-forest); line-height: 1.1; }
    .hb-title svg { width: 30px; height: 30px; fill: #2c8a56; flex-shrink: 0; }
    .hb-sub { margin: 6px 0 0; font-size: 1.05rem; color: #3f4c45; }

    .hb-hero-right { display: flex; align-items: center; gap: 22px; flex-shrink: 0; }
    .hb-quote { max-width: 190px; text-align: center; font-family: 'Playfair Display', Georgia, serif; font-style: italic; font-size: 1.05rem; line-height: 1.35; color: #fff; text-shadow: 0 1px 8px rgba(0,0,0,.45); }
    .hb-add { display: inline-flex; align-items: center; gap: 10px; padding: 14px 26px; border-radius: 999px; background: #0d3b22; color: #fff; font-weight: 600; font-size: 1rem; text-decoration: none; white-space: nowrap; box-shadow: 0 6px 16px rgba(13,59,34,.3); transition: transform .15s, background .15s; }
    .hb-add:hover { background: #0a2f1b; color: #fff; transform: translateY(-1px); }
    .hb-add svg { width: 16px; height: 16px; fill: none; stroke: #fff; stroke-width: 2.6; stroke-linecap: round; }

    /* ---------- Stat cards ---------- */
    .hb-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 18px; margin-bottom: 18px; }
    .hb-stat { position: relative; overflow: hidden; display: flex; align-items: center; gap: 16px; padding: 20px 22px; border-radius: 16px; border: 1px solid; min-height: 112px; }
    .hb-stat .ic { width: 52px; height: 52px; border-radius: 50%; display: grid; place-items: center; flex-shrink: 0; color: #fff; }
    .hb-stat .ic svg { width: 26px; height: 26px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .hb-stat .ic svg.fill { fill: currentColor; stroke: none; }
    .hb-stat .lbl { font-size: .95rem; font-weight: 600; color: var(--hb-ink); }
    .hb-stat .num { font-size: 2.3rem; font-weight: 600; color: var(--hb-ink); line-height: 1.1; }
    .hb-stat .deco { position: absolute; right: 14px; bottom: 8px; width: 78px; height: 62px; opacity: .16; }
    .hb-stat.s1 { background: #eaf6ee; border-color: #cfe6d8; } .hb-stat.s1 .ic { background: #145a35; } .hb-stat.s1 .deco { fill: #1f7a4a; }
    .hb-stat.s2 { background: #eaf6ee; border-color: #cfe6d8; } .hb-stat.s2 .ic { background: #1f8a50; } .hb-stat.s2 .deco { fill: #1f8a50; }
    .hb-stat.s3 { background: #fff3e6; border-color: #f7dcc0; } .hb-stat.s3 .ic { background: #f28c1e; } .hb-stat.s3 .deco { fill: #f28c1e; }
    .hb-stat.s4 { background: #f1eefc; border-color: #ddd6f5; } .hb-stat.s4 .ic { background: #6c4fd3; } .hb-stat.s4 .deco { fill: #6c4fd3; }

    /* ---------- Panel + toolbar ---------- */
    .hb-panel { background: #fff; border-radius: 20px; padding: 18px 20px 20px; box-shadow: 0 4px 24px rgba(30,45,36,.06); }
    .hb-toolbar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 14px; }
    .hb-search { position: relative; flex: 1 1 260px; min-width: 220px; }
    .hb-search svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 17px; height: 17px; fill: none; stroke: #4a554e; stroke-width: 2; stroke-linecap: round; }
    .hb-wrap .hb-search input { width: 100%; border: 0; background: transparent; padding: 11px 12px 11px 42px; font-size: .92rem; font-family: inherit; box-shadow: none; outline: none; }
    .hb-sel { position: relative; flex: 0 0 auto; }
    .hb-wrap .hb-sel select { appearance: none; -webkit-appearance: none; width: 150px; border: 1px solid var(--hb-line); background: #fff; border-radius: 10px; padding: 10px 34px 10px 14px; font-size: .88rem; color: #3e4a43; cursor: pointer; font-family: inherit; }
    .hb-wrap .hb-sel.sort select { width: 160px; }
    .hb-sel svg { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); width: 13px; height: 13px; fill: none; stroke: #4a554e; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; pointer-events: none; }
    .hb-sortlbl { font-size: .88rem; color: var(--hb-muted); margin-left: 4px; }
    .hb-reset { width: 38px; height: 38px; border-radius: 10px; border: 1px solid var(--hb-line); background: #f7f5f0; display: grid; place-items: center; cursor: pointer; padding: 0; }
    .hb-reset svg { width: 16px; height: 16px; fill: none; stroke: #4a554e; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .hb-reset:hover { background: #efece4; }

    /* ---------- Table ---------- */
    .hb-table-wrap { overflow-x: auto; }
    .hb-table { width: 100%; min-width: 1020px; table-layout: fixed; border-collapse: separate; border-spacing: 0; }
    .hb-table thead th { background: #f4f1e8; color: #3e4a43; font-size: .9rem; font-weight: 600; text-align: left; padding: 13px 14px; white-space: nowrap; }
    .hb-table thead th:first-child { border-radius: 10px 0 0 10px; padding-left: 14px; }
    .hb-table thead th:last-child { border-radius: 0 10px 10px 0; }
    .hb-table tbody td { padding: 10px 14px; border-bottom: 1px solid var(--hb-line); vertical-align: middle; color: var(--hb-ink); font-size: .92rem; }
    .hb-table tbody tr:hover { background: #fcfbf8; }
    .hb-table th.c, .hb-table td.c { text-align: center; }

    .hb-thumb { width: 66px; height: 42px; border-radius: 6px; object-fit: cover; display: block; background: #e8efe9; }
    .hb-thumb.ph { display: grid; place-items: center; background: linear-gradient(135deg, #2c6b47, #14402a); color: #f2b84b; }
    .hb-thumb.ph svg { width: 20px; height: 20px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    .hb-name { display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: .98rem; color: var(--hb-ink); min-width: 0; }
    .hb-name svg { width: 18px; height: 18px; flex-shrink: 0; fill: none; stroke: #1f7a4a; stroke-width: 1.9; stroke-linecap: round; stroke-linejoin: round; }
    .hb-name svg.fill { fill: #1f7a4a; stroke: none; }
    .hb-name span { overflow-wrap: anywhere; }
    .hb-desc { color: #5a655e; font-size: .88rem; line-height: 1.4; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .hb-region { display: flex; align-items: center; gap: 8px; color: #4a554e; }
    .hb-region svg { width: 16px; height: 16px; flex-shrink: 0; fill: none; stroke: #4a554e; stroke-width: 1.9; stroke-linecap: round; stroke-linejoin: round; }

    .hb-count { display: inline-block; min-width: 38px; padding: 4px 12px; border-radius: 999px; background: #dfeafb; color: #2756a3; font-weight: 600; font-size: .85rem; text-align: center; }
    .hb-pill { display: inline-flex; align-items: center; gap: 7px; padding: 5px 12px; border-radius: 999px; font-size: .84rem; font-weight: 600; white-space: nowrap; }
    .hb-pill::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
    .hb-pill.on { background: #e0f2e6; color: #14633f; }
    .hb-pill.off { background: #f0ece4; color: #6b655c; }

    .hb-actions { display: flex; align-items: center; gap: 10px; flex-wrap: nowrap; }
    .hb-ic { width: 36px; height: 36px; border-radius: 8px; border: 0; display: grid; place-items: center; cursor: pointer; padding: 0; text-decoration: none; transition: filter .15s, transform .15s; }
    .hb-ic:hover { filter: brightness(.92); transform: translateY(-1px); }
    .hb-ic svg { width: 17px; height: 17px; fill: none; stroke: #fff; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .hb-ic.edit { background: #14633f; } .hb-ic.del { background: #ef4a4a; }
    .hb-more { width: 34px; height: 34px; border-radius: 8px; border: 1px solid var(--hb-line); background: #fff; display: grid; place-items: center; cursor: pointer; padding: 0; margin-left: 6px; }
    .hb-more svg { width: 16px; height: 16px; fill: #3e4a43; }
    .hb-more:hover { background: #f7f5f0; }

    .hb-menu { position: fixed; z-index: 9999; display: none; min-width: 150px; background: #fff; border: 1px solid var(--hb-line); border-radius: 12px; box-shadow: 0 12px 32px rgba(20,30,25,.16); padding: 6px; }
    .hb-menu.open { display: block; }
    .hb-menu a, .hb-menu button { width: 100%; display: flex; align-items: center; gap: 10px; border: 0; background: transparent; padding: 10px 12px; border-radius: 8px; font-weight: 600; font-size: .9rem; cursor: pointer; font-family: inherit; text-decoration: none; color: #1d2b24; }
    .hb-menu button { color: #a12626; }
    .hb-menu a:hover { background: #f1f5ef; } .hb-menu button:hover { background: #fbe9e9; }
    .hb-menu svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    .hb-empty td { text-align: center; padding: 44px 16px !important; color: var(--hb-muted); }
    .hb-nomatch { display: none; }

    /* ---------- Footer ---------- */
    .hb-foot { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; padding-top: 18px; color: var(--hb-muted); font-size: .92rem; }
    .hb-pager { display: flex; gap: 8px; }
    .hb-pager a, .hb-pager span { width: 38px; height: 38px; border-radius: 9px; border: 1px solid var(--hb-line); background: #fff; display: grid; place-items: center; color: #3e4a43; text-decoration: none; font-weight: 600; font-size: .9rem; }
    .hb-pager svg { width: 15px; height: 15px; fill: none; stroke: currentColor; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
    .hb-pager .on { background: #0d3b22; border-color: #0d3b22; color: #fff; }
    .hb-pager .off { opacity: .45; }

    @media (max-width: 1100px) {
        .hb-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .hb-quote { display: none; }
    }
    @media (max-width: 700px) {
        .hb-hero { flex-direction: column; align-items: flex-start; padding: 22px 20px; }
        .hb-title { font-size: 2.1rem; }
        .hb-stats { grid-template-columns: 1fr; }
        .hb-wrap .hb-sel select, .hb-wrap .hb-sel.sort select { width: 100%; }
        .hb-sel { flex: 1 1 140px; }
    }
</style>
@endpush

@section('content')
@php
    $isPaginator = method_exists($habitats, 'total');

    // ---- helpers (column names are guessed so this works with your current model) ----
    $pick = function ($model, array $cols) {
        foreach ($cols as $c) { if (!empty($model->{$c})) return $model->{$c}; }
        return null;
    };
    $imageOf = function ($h) use ($pick) {
        $path = $pick($h, ['image', 'image_path', 'photo', 'thumbnail', 'featured_image', 'main_image', 'cover_image', 'banner_image', 'picture', 'image_url']);
        if (empty($path)) return null;
        if (preg_match('#^(https?:)?//#', $path)) return $path;
        $path = ltrim($path, '/');
        return file_exists(public_path($path)) ? asset($path) : asset('storage/' . $path);
    };
    $regionOf = fn ($h) => $pick($h, ['region', 'location', 'geographical_range', 'country', 'continent']);
    $typeOf   = fn ($h) => $pick($h, ['type', 'ecosystem', 'category']) ?? $h->name;
    $countOf  = function ($h) {
        if (isset($h->animals_count)) return (int) $h->animals_count;
        if (method_exists($h, 'animals')) { try { return (int) $h->animals()->count(); } catch (\Throwable $e) {} }
        return 0;
    };
    $isActive = fn ($h) => in_array(strtolower((string) $h->status), ['active', 'published', '1'], true);

    // icon key by habitat name
    $iconKey = function ($name) {
        $n = strtolower($name);
        if (preg_match('/desert|dune|arid/', $n)) return 'cactus';
        if (preg_match('/lake|river|water|ocean|sea|wetland|marsh|reef/', $n)) return 'waves';
        if (preg_match('/savann|grass|plain|prairie|steppe/', $n)) return 'grass';
        if (preg_match('/mountain|alpine|hill|cliff/', $n)) return 'mountain';
        return 'leaf';
    };

    $icons = [
        'leaf'     => '<svg class="fill" viewBox="0 0 24 24"><path d="M20.5 3.5C11 3 5 7.5 5 14c0 1.3.3 2.4.8 3.3L3 20.5l1.5 1 2.8-3.2c.9.5 2 .7 3.2.7 6.5 0 10.5-6 10-15.5Z"/></svg>',
        'grass'    => '<svg viewBox="0 0 24 24"><path d="M5 20c0-5 1-9 3-12M12 20V5M19 20c0-5-1-9-3-12M8.5 20c0-3 .5-5 1.5-7M15.5 20c0-3-.5-5-1.5-7"/></svg>',
        'cactus'   => '<svg viewBox="0 0 24 24"><path d="M12 21V5a2 2 0 0 1 4 0v6M12 21V9a2 2 0 0 0-4 0M16 15h1a3 3 0 0 0 3-3v-1M8 14H7a3 3 0 0 1-3-3v-1M8 21h8"/></svg>',
        'waves'    => '<svg viewBox="0 0 24 24"><path d="M3 8c2 0 2-1.500 4.500-1.500S10 8 12 8s2.500-1.500 4.500-1.500S19 8 21 8M3 13c2 0 2-1.500 4.500-1.500S10 13 12 13s2.500-1.500 4.500-1.500S19 13 21 13M3 18c2 0 2-1.500 4.500-1.500S10 18 12 18s2.500-1.500 4.500-1.500S19 18 21 18"/></svg>',
        'mountain' => '<svg viewBox="0 0 24 24"><path d="m2.500 19 6-10 4 6 2.500-3.500 6.500 7.500Z"/></svg>',
    ];

    $list = collect($habitats);
    $totalHabitats = $isPaginator ? $habitats->total() : $list->count();
    $activeCount   = $list->filter(fn ($h) => $isActive($h))->count();
    $regionNames   = $list->map(fn ($h) => $regionOf($h))->filter()->unique()->values();
    $ecoCount      = $list->map(fn ($h) => strtolower($typeOf($h)))->unique()->count();

    $heroImg = file_exists(public_path('images/habitats-banner.jpg')) ? asset('images/habitats-banner.jpg') : null;
@endphp

<div class="hb-wrap">

    {{-- ===== Hero ===== --}}
    <div class="hb-hero">
        @if($heroImg)
            <img class="hb-hero-img" src="{{ $heroImg }}" alt="">
        @else
            <svg class="hb-hero-art" viewBox="0 0 1200 220" preserveAspectRatio="xMaxYMid slice" aria-hidden="true">
                <defs>
                    <linearGradient id="hbSky" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#e3eed6"/><stop offset="1" stop-color="#bfd8ad"/></linearGradient>
                    <linearGradient id="hbRiver" x1="0" y1="0" x2="1" y2="0"><stop offset="0" stop-color="#d9ecf2"/><stop offset="1" stop-color="#a9d3e0"/></linearGradient>
                </defs>
                <rect width="1200" height="220" fill="url(#hbSky)"/>
                <path d="M300 150 L470 70 L560 110 L700 40 L860 120 L980 70 L1200 130 V220 H300Z" fill="#9fc08a" opacity=".75"/>
                <path d="M420 170 L600 95 L720 140 L880 80 L1060 150 L1200 110 V220 H420Z" fill="#6f9d62" opacity=".85"/>
                <path d="M520 190 L700 130 L860 170 L1000 120 L1200 170 V220 H520Z" fill="#3f7a4a"/>
                <path d="M640 220 C700 190 760 205 820 185 C880 165 960 190 1040 170 C1100 156 1150 165 1200 160 V172 C1140 176 1090 170 1040 184 C960 204 880 180 826 198 C770 216 712 204 676 220Z" fill="url(#hbRiver)"/>
            </svg>
        @endif

        <div>
            <div class="hb-crumb">
                <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin') }}">Dashboard</a><span class="sep">/</span><span class="current">Habitats</span>
            </div>
            <h1 class="hb-title">
                <svg viewBox="0 0 24 24"><path d="M20.5 3.5C11 3 5 7.5 5 14c0 1.3.3 2.4.8 3.3L3 20.5l1.5 1 2.8-3.2c.9.5 2 .7 3.2.7 6.5 0 10.5-6 10-15.5Z"/></svg>
                Habitats
                <svg viewBox="0 0 24 24"><path d="M20.5 3.5C11 3 5 7.5 5 14c0 1.3.3 2.4.8 3.3L3 20.5l1.5 1 2.8-3.2c.9.5 2 .7 3.2.7 6.5 0 10.5-6 10-15.5Z"/></svg>
            </h1>
            <p class="hb-sub">Manage and organize animal habitats in your wildlife database.</p>
        </div>

        <div class="hb-hero-right">
            <div class="hb-quote">&ldquo;Healthy habitats support healthy wildlife.&rdquo;</div>
            <a href="{{ route('admin.habitats.create') }}" class="hb-add">
                <svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                Add Habitat
            </a>
        </div>
    </div>

    {{-- ===== Stat cards ===== --}}
    <div class="hb-stats">
        <div class="hb-stat s1">
            <div class="ic"><svg class="fill" viewBox="0 0 24 24"><ellipse cx="6.5" cy="10" rx="2" ry="2.6"/><ellipse cx="10.5" cy="6" rx="2" ry="2.6"/><ellipse cx="15.5" cy="6" rx="2" ry="2.6"/><ellipse cx="19" cy="10.5" rx="2" ry="2.6"/><path d="M12.800 11.500c-2.600 0-5.300 3-5.300 5.300 0 1.800 1.500 2.700 3.100 2.700.9 0 1.400-.3 2.200-.3s1.300.3 2.200.3c1.600 0 3.100-.9 3.100-2.700 0-2.300-2.700-5.300-5.300-5.300Z"/></svg></div>
            <div><div class="lbl">Total Habitats</div><div class="num">{{ $totalHabitats }}</div></div>
            <svg class="deco" viewBox="0 0 24 20"><path d="m0 20 8-14 5 8 3-4 8 10Z"/></svg>
        </div>
        <div class="hb-stat s2">
            <div class="ic"><svg viewBox="0 0 24 24"><path d="M20.500 3.500C11 3 5 7.500 5 14c0 1.300.3 2.400.8 3.300L3 20.500M9 15c3-5 6-7 10-8"/></svg></div>
            <div><div class="lbl">Active Habitats</div><div class="num">{{ $activeCount }}</div></div>
            <svg class="deco" viewBox="0 0 24 24"><path d="M20.5 3.5C11 3 5 7.5 5 14c0 1.3.3 2.4.8 3.3L3 20.5l1.5 1 2.8-3.2c.9.5 2 .7 3.2.7 6.5 0 10.5-6 10-15.5Z"/></svg>
        </div>
        <div class="hb-stat s3">
            <div class="ic"><svg viewBox="0 0 24 24"><path d="M12 21s7-6.200 7-11.500A7 7 0 0 0 5 9.500C5 14.800 12 21 12 21Z"/><circle cx="12" cy="9.500" r="2.500"/></svg></div>
            <div><div class="lbl">Regions Covered</div><div class="num">{{ $regionNames->count() }}</div></div>
            <svg class="deco" viewBox="0 0 24 24"><circle cx="12" cy="12" r="11"/></svg>
        </div>
        <div class="hb-stat s4">
            <div class="ic"><svg class="fill" viewBox="0 0 24 24"><path d="m2 19 7-12 4 6 2.500-3.500L22 19Z"/></svg></div>
            <div><div class="lbl">Unique Ecosystems</div><div class="num">{{ $ecoCount }}</div></div>
            <svg class="deco" viewBox="0 0 24 20"><path d="m0 20 8-14 5 8 3-4 8 10Z"/></svg>
        </div>
    </div>

    {{-- ===== Table panel ===== --}}
    <div class="hb-panel">
        <div class="hb-toolbar">
            <div class="hb-search">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.300-4.300"/></svg>
                <input type="text" id="hbSearch" placeholder="Search habitats..." autocomplete="off">
            </div>

            <div class="hb-sel">
                <select id="hbStatus">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <svg viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
            </div>

            <div class="hb-sel">
                <select id="hbRegion">
                    <option value="">All Regions</option>
                    @foreach($regionNames as $rn)
                        <option value="{{ strtolower($rn) }}">{{ \Illuminate\Support\Str::limit($rn, 28) }}</option>
                    @endforeach
                </select>
                <svg viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
            </div>

            <span class="hb-sortlbl">Sort by</span>
            <div class="hb-sel sort">
                <select id="hbSort">
                    <option value="az">Name (A-Z)</option>
                    <option value="za">Name (Z-A)</option>
                    <option value="animals">Most Animals</option>
                    <option value="newest">Newest First</option>
                </select>
                <svg viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
            </div>

            <button type="button" class="hb-reset" id="hbReset" title="Reset filters" aria-label="Reset filters">
                <svg viewBox="0 0 24 24"><path d="M3 12a9 9 0 1 0 3-6.700"/><path d="M3 4v5h5"/></svg>
            </button>
        </div>

        <div class="hb-table-wrap">
            <table class="hb-table">
                <colgroup>
                    <col style="width:64px"><col style="width:96px"><col style="width:21%"><col><col style="width:16%"><col style="width:130px"><col style="width:120px"><col style="width:190px">
                </colgroup>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Region</th>
                        <th class="c">Animals Count</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="hbRows">
                    @forelse($habitats as $habitat)
                        @php
                            $img    = $imageOf($habitat);
                            $region = $regionOf($habitat);
                            $count  = $countOf($habitat);
                            $active = $isActive($habitat);
                            $ikey   = $iconKey($habitat->name);
                        @endphp
                        <tr class="hb-row"
                            data-id="{{ $habitat->getKey() }}"
                            data-name="{{ strtolower($habitat->name) }}"
                            data-search="{{ strtolower($habitat->name . ' ' . $habitat->short_description . ' ' . $region) }}"
                            data-status="{{ $active ? 'active' : 'inactive' }}"
                            data-region="{{ strtolower($region ?? '') }}"
                            data-animals="{{ $count }}">
                            <td>{{ $habitat->getKey() }}</td>
                            <td>
                                @if($img)
                                    <img class="hb-thumb" src="{{ $img }}" alt="{{ $habitat->name }}" data-icon="{{ $ikey }}">
                                @else
                                    <div class="hb-thumb ph">{!! $icons[$ikey] !!}</div>
                                @endif
                            </td>
                            <td><div class="hb-name">{!! $icons[$ikey] !!}<span>{{ $habitat->name }}</span></div></td>
                            <td><div class="hb-desc" title="{{ $habitat->short_description }}">{{ $habitat->short_description ?: '-' }}</div></td>
                            <td>
                                @if($region)
                                    <div class="hb-region">
                                        <svg viewBox="0 0 24 24"><path d="M12 21s7-6.200 7-11.500A7 7 0 0 0 5 9.500C5 14.800 12 21 12 21Z"/><circle cx="12" cy="9.500" r="2.500"/></svg>
                                        <span>{{ $region }}</span>
                                    </div>
                                @else
                                    <span style="color:#b3ada3">-</span>
                                @endif
                            </td>
                            <td class="c"><span class="hb-count">{{ $count }}</span></td>
                            <td><span class="hb-pill {{ $active ? 'on' : 'off' }}">{{ ucfirst($habitat->status) }}</span></td>
                            <td>
                                <div class="hb-actions">
                                    <a href="{{ route('admin.habitats.edit', $habitat) }}" class="hb-ic edit" title="Edit" aria-label="Edit">
                                        <svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.500 3.500a2.100 2.100 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                    </a>
                                    <form id="del-{{ $habitat->getKey() }}" action="{{ route('admin.habitats.destroy', $habitat) }}" method="POST" style="display:none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                    <button type="submit" form="del-{{ $habitat->getKey() }}" class="hb-ic del" title="Delete" aria-label="Delete" onclick="return confirm('Delete this habitat?')">
                                        <svg viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="m6 6 1 14h10l1-14"/></svg>
                                    </button>
                                    <button type="button" class="hb-more" data-menu="hmenu-{{ $habitat->getKey() }}" aria-label="More actions">
                                        <svg viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.800"/><circle cx="12" cy="12" r="1.800"/><circle cx="12" cy="19" r="1.800"/></svg>
                                    </button>
                                    <div class="hb-menu" id="hmenu-{{ $habitat->getKey() }}">
                                        <a href="{{ route('admin.habitats.edit', $habitat) }}">
                                            <svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.500 3.500a2.100 2.100 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>Edit
                                        </a>
                                        <button type="submit" form="del-{{ $habitat->getKey() }}" onclick="return confirm('Delete this habitat?')">
                                            <svg viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="m6 6 1 14h10l1-14"/></svg>Delete
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="hb-empty"><td colspan="8">No habitats found. Click "Add Habitat" to create the first one.</td></tr>
                    @endforelse
                    <tr class="hb-empty hb-nomatch" id="hbNoMatch"><td colspan="8">No habitats match your filters.</td></tr>
                </tbody>
            </table>
        </div>

        <div class="hb-foot">
            <div>
                @if($isPaginator && $habitats->total() > 0)
                    Showing {{ $habitats->firstItem() }}&ndash;{{ $habitats->lastItem() }} of {{ $habitats->total() }} {{ \Illuminate\Support\Str::plural('habitat', $habitats->total()) }}
                @else
                    Showing {{ $list->count() ? '1–' . $list->count() : '0' }} of {{ $list->count() }} {{ \Illuminate\Support\Str::plural('habitat', $list->count()) }}
                @endif
            </div>
            <div class="hb-pager">
                @if($isPaginator)
                    @if($habitats->onFirstPage())
                        <span class="off"><svg viewBox="0 0 24 24"><path d="m15 6-6 6 6 6"/></svg></span>
                    @else
                        <a href="{{ $habitats->previousPageUrl() }}"><svg viewBox="0 0 24 24"><path d="m15 6-6 6 6 6"/></svg></a>
                    @endif
                    @foreach($habitats->getUrlRange(max(1, $habitats->currentPage() - 2), min($habitats->lastPage(), $habitats->currentPage() + 2)) as $page => $url)
                        @if($page == $habitats->currentPage())
                            <span class="on">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach
                    @if($habitats->hasMorePages())
                        <a href="{{ $habitats->nextPageUrl() }}"><svg viewBox="0 0 24 24"><path d="m9 6 6 6-6 6"/></svg></a>
                    @else
                        <span class="off"><svg viewBox="0 0 24 24"><path d="m9 6 6 6-6 6"/></svg></span>
                    @endif
                @else
                    <span class="off"><svg viewBox="0 0 24 24"><path d="m15 6-6 6 6 6"/></svg></span>
                    <span class="on">1</span>
                    <span class="off"><svg viewBox="0 0 24 24"><path d="m9 6 6 6-6 6"/></svg></span>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        var body = document.getElementById('hbRows');
        var rows = Array.prototype.slice.call(document.querySelectorAll('.hb-row'));
        var noMatch = document.getElementById('hbNoMatch');
        var q = document.getElementById('hbSearch');
        var st = document.getElementById('hbStatus');
        var rg = document.getElementById('hbRegion');
        var so = document.getElementById('hbSort');

        function run() {
            var text = q.value.trim().toLowerCase(), s = st.value, r = rg.value, shown = 0;

            rows.sort(function (a, b) {
                var A = a.dataset, B = b.dataset;
                switch (so.value) {
                    case 'za': return B.name.localeCompare(A.name);
                    case 'animals': return (+B.animals) - (+A.animals);
                    case 'newest': return (+B.id) - (+A.id);
                    default: return A.name.localeCompare(B.name);
                }
            }).forEach(function (row) { body.insertBefore(row, noMatch); });

            rows.forEach(function (row) {
                var ok = (!text || row.dataset.search.indexOf(text) !== -1) &&
                         (!s || row.dataset.status === s) &&
                         (!r || row.dataset.region === r);
                row.style.display = ok ? '' : 'none';
                if (ok) shown++;
            });
            if (noMatch) noMatch.style.display = (rows.length && shown === 0) ? 'table-row' : 'none';
        }

        if (rows.length) {
            q.addEventListener('input', run);
            [st, rg, so].forEach(function (el) { el.addEventListener('change', run); });
            document.getElementById('hbReset').addEventListener('click', function () {
                q.value = ''; st.value = ''; rg.value = ''; so.value = 'az'; run();
            });
            run();
        }

        // broken image -> icon tile
        document.querySelectorAll('img.hb-thumb[data-icon]').forEach(function (img) {
            function swap() {
                var d = document.createElement('div');
                d.className = 'hb-thumb ph';
                var ic = img.closest('tr').querySelector('.hb-name svg');
                d.innerHTML = ic ? ic.outerHTML : '';
                img.replaceWith(d);
            }
            if (img.complete && img.naturalWidth === 0) swap(); else img.addEventListener('error', swap);
        });

        // three-dot menu
        var open = null;
        function closeMenu() { if (open) { open.classList.remove('open'); open = null; } }
        document.querySelectorAll('.hb-more').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var menu = document.getElementById(btn.dataset.menu);
                if (open === menu) { closeMenu(); return; }
                closeMenu();
                var r = btn.getBoundingClientRect();
                menu.classList.add('open');
                var h = menu.offsetHeight, w = menu.offsetWidth, top = r.bottom + 6;
                if (top + h > window.innerHeight) top = r.top - h - 6;
                menu.style.top = top + 'px';
                menu.style.left = Math.max(8, r.right - w) + 'px';
                open = menu;
            });
        });
        document.addEventListener('click', closeMenu);
        window.addEventListener('scroll', closeMenu, true);
        window.addEventListener('resize', closeMenu);
    })();
</script>

<script>
    // Layout ka apna "Habitats" heading hide karta hai (layout file ko touch kiye bina)
    (function () {
        document.querySelectorAll('h1, h2, h3, .page-title, .page-heading').forEach(function (el) {
            if (el.closest('.hb-wrap') || el.closest('aside') || el.closest('nav') || el.closest('header')) return;
            var t = el.textContent.trim();
            if (t === 'Habitats' || t === 'Habitat management') el.style.display = 'none';
        });
    })();
</script>
@endsection