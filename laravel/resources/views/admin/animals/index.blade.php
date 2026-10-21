@extends('admin.layout')

@section('title', 'Animals')
@section('page-title', 'Animals')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
<style>
    .an-wrap { --an-forest: #0f3d24; --an-green: #1f7a4a; --an-orange: #f28c1e; --an-ink: #1d2b24; --an-muted: #6f7a73; --an-line: #ece8df; }

    /* Breadcrumb + heading */
    .an-crumb { font-size: .95rem; color: var(--an-muted); margin-bottom: 10px; }
    .an-crumb a { color: var(--an-muted); text-decoration: none; }
    .an-crumb span.current { color: var(--an-forest); font-weight: 500; }
    .an-crumb .sep { margin: 0 8px; }

    .an-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap; margin-bottom: 26px; }
    .an-title { display: flex; align-items: center; gap: 12px; margin: 0; font-family: 'Playfair Display', Georgia, serif; font-size: 2.7rem; font-weight: 700; color: var(--an-forest); line-height: 1.1; }
    .an-title svg { width: 28px; height: 28px; fill: #2c8a56; margin-top: -6px; }
    .an-sub { margin: 6px 0 0; font-size: 1.1rem; color: #4f5b54; }

    .an-add { display: inline-flex; align-items: center; gap: 12px; padding: 10px 28px 10px 12px; border-radius: 999px; background: #0d3b22; color: #fff; font-weight: 600; font-size: 1rem; text-decoration: none; box-shadow: 0 6px 16px rgba(13,59,34,.28); transition: transform .15s, background .15s; }
    .an-add:hover { background: #0a2f1b; transform: translateY(-1px); color: #fff; }
    .an-add .plus { width: 34px; height: 34px; border-radius: 50%; background: var(--an-orange); display: grid; place-items: center; }
    .an-add .plus svg { width: 16px; height: 16px; fill: none; stroke: #fff; stroke-width: 2.6; stroke-linecap: round; }

    /* Stat cards */
    .an-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; margin-bottom: 26px; }
    .an-stat { position: relative; overflow: hidden; display: flex; align-items: center; gap: 16px; padding: 20px 22px; border-radius: 16px; border: 1px solid; min-height: 118px; }
    .an-stat .ic { width: 58px; height: 58px; border-radius: 50%; display: grid; place-items: center; flex-shrink: 0; color: #fff; }
    .an-stat .ic svg { width: 28px; height: 28px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .an-stat .lbl { font-size: .98rem; font-weight: 600; color: var(--an-ink); }
    .an-stat .num { font-size: 2.3rem; font-weight: 600; color: var(--an-ink); line-height: 1.15; }
    .an-stat .deco { position: absolute; right: 14px; bottom: 10px; width: 64px; height: 64px; opacity: .14; fill: none; stroke: none; }
    .an-stat.s-total { background: #eaf6ee; border-color: #cfe6d8; }
    .an-stat.s-total .ic { background: #145a35; }
    .an-stat.s-total .deco { fill: #1f7a4a; }
    .an-stat.s-pub { background: #eaf6ee; border-color: #cfe6d8; }
    .an-stat.s-pub .ic { background: #1f8a50; }
    .an-stat.s-pub .deco { fill: #1f8a50; }
    .an-stat.s-hab { background: #fff3e6; border-color: #f7dcc0; }
    .an-stat.s-hab .ic { background: #f28c1e; }
    .an-stat.s-hab .deco { fill: #f28c1e; }
    .an-stat.s-draft { background: #fdeeee; border-color: #f6d3d3; }
    .an-stat.s-draft .ic { background: #e5616a; }
    .an-stat.s-draft .deco { fill: #e5616a; }

    /* Panel */
    .an-panel { background: #fff; border-radius: 20px; padding: 18px 20px 20px; box-shadow: 0 4px 24px rgba(30,45,36,.06); }
    .an-toolbar { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; background: #f7f5f0; border-radius: 12px; padding: 10px 14px; margin-bottom: 16px; }
    .an-all { display: flex; align-items: center; gap: 12px; font-weight: 600; font-size: 1.05rem; color: var(--an-forest); margin-right: auto; padding: 4px 6px; }
    .an-all svg { width: 20px; height: 20px; fill: none; stroke: var(--an-forest); stroke-width: 2; stroke-linecap: round; }
    .an-all .chev { width: 14px; height: 14px; }

    .an-select { position: relative; flex: 0 0 auto; }
    .an-select select { appearance: none; -webkit-appearance: none; border: 1px solid var(--an-line); background: #fff; border-radius: 10px; padding: 11px 38px 11px 16px; font-size: .9rem; color: #4a554e; cursor: pointer; font-family: inherit; }
    .an-wrap .an-select select { width: 190px; min-width: 0; max-width: 100%; text-overflow: ellipsis; }
    .an-select svg { position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 14px; height: 14px; fill: none; stroke: #4a554e; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; pointer-events: none; }

    .an-search { position: relative; width: auto; min-width: 220px; max-width: 380px; flex: 1 1 280px; }
    .an-search svg { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); width: 17px; height: 17px; fill: none; stroke: #6f7a73; stroke-width: 2; stroke-linecap: round; }
    .an-search input { width: 100%; border: 1px solid var(--an-line); background: #fff; border-radius: 999px; padding: 11px 18px 11px 44px; font-size: .9rem; font-family: inherit; box-shadow: none; }

    /* Table */
    .an-table-wrap { overflow-x: auto; }
    .an-table { width: 100%; border-collapse: separate; border-spacing: 0; min-width: 1000px; table-layout: fixed; }
    .an-table thead th { background: #f7f5f0; color: #3e4a43; font-size: .9rem; font-weight: 600; text-align: left; padding: 14px 16px; white-space: nowrap; }
    .an-table thead th:first-child { border-radius: 10px 0 0 10px; padding-left: 16px; }
    .an-table thead th:last-child { border-radius: 0 10px 10px 0; }
    .an-table tbody td { padding: 14px 16px; border-bottom: 1px solid var(--an-line); vertical-align: middle; color: var(--an-ink); font-size: .95rem; }
    .an-table tbody tr:last-child td { border-bottom: 1px solid var(--an-line); }
    .an-table tbody tr { transition: background .15s; }
    .an-table tbody tr:hover { background: #fcfbf8; }

    .an-animal { display: flex; align-items: center; gap: 14px; min-width: 0; }
    .an-thumb { width: 64px; height: 64px; border-radius: 10px; object-fit: cover; flex-shrink: 0; background: #e8efe9; }
    .an-thumb.ph { display: grid; place-items: center; background: #0f3d24; color: #f2b84b; font-weight: 700; font-size: 1.5rem; }
    .an-name { font-weight: 600; font-size: 1.05rem; color: var(--an-ink); min-width: 0; overflow-wrap: anywhere; }
    .an-sci { font-style: italic; color: #5d5a55; }

    .an-pill { display: inline-flex; align-items: center; gap: 8px; font-size: .86rem; padding: 7px 14px; border-radius: 999px; white-space: nowrap; }
    .an-pill svg { width: 18px; height: 18px; fill: none; stroke: currentColor; stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round; }
    .an-pill.habitat-none { background: #eceeed; color: #7d8782; }
    .an-pill.habitat-set { background: #eef0d9; color: #55601a; font-weight: 600; white-space: normal; max-width: 100%; min-width: 0; border-radius: 14px; line-height: 1.35; align-items: flex-start; }
    .an-clamp { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .an-table td.nw { white-space: nowrap; color: #4a554e; }
    .an-actions { flex-wrap: nowrap; }
    .an-pill.published { background: #e0f2e6; color: #14633f; font-weight: 600; }
    .an-pill.published::before, .an-pill.draft::before { content: ''; width: 8px; height: 8px; border-radius: 50%; background: #1f9a58; }
    .an-pill.draft { background: #f0ece4; color: #6b655c; font-weight: 600; }
    .an-pill.draft::before { background: #a39d92; }

    .an-actions { display: flex; align-items: center; gap: 12px; }
    .an-edit { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-width: 98px; padding: 9px 18px; border-radius: 999px; border: 1.5px solid #f6b56a; background: #fff; color: #c2650a; font-weight: 600; font-size: .9rem; text-decoration: none; transition: background .15s, color .15s; }
    .an-edit svg { width: 15px; height: 15px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }
    .an-edit:hover { background: var(--an-orange); border-color: var(--an-orange); color: #fff; }
    .an-more { width: 38px; height: 38px; border-radius: 50%; border: 1px solid var(--an-line); background: #fff; display: grid; place-items: center; cursor: pointer; padding: 0; }
    .an-more svg { width: 18px; height: 18px; fill: #3e4a43; }
    .an-more:hover { background: #f7f5f0; }

    .an-menu { position: fixed; z-index: 9999; display: none; min-width: 150px; background: #fff; border: 1px solid var(--an-line); border-radius: 12px; box-shadow: 0 12px 32px rgba(20,30,25,.16); padding: 6px; }
    .an-menu.open { display: block; }
    .an-menu button { width: 100%; display: flex; align-items: center; gap: 10px; border: 0; background: transparent; padding: 10px 12px; border-radius: 8px; color: #a12626; font-weight: 600; font-size: .9rem; cursor: pointer; font-family: inherit; }
    .an-menu button:hover { background: #fbe9e9; }
    .an-menu svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    .an-empty td { text-align: center; padding: 48px 16px !important; color: var(--an-muted); }
    .an-nomatch { display: none; }

    /* Footer */
    .an-foot { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; padding-top: 18px; color: var(--an-muted); font-size: .92rem; }
    .an-pager { display: flex; gap: 10px; }
    .an-pager a, .an-pager span { width: 38px; height: 38px; border-radius: 9px; border: 1px solid var(--an-line); background: #fff; display: grid; place-items: center; color: #3e4a43; text-decoration: none; font-weight: 600; font-size: .9rem; }
    .an-pager svg { width: 15px; height: 15px; fill: none; stroke: currentColor; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
    .an-pager .on { background: #0d3b22; border-color: #0d3b22; color: #fff; }
    .an-pager .off { opacity: .45; }

    @media (max-width: 1100px) { .an-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) {
        .an-stats { grid-template-columns: 1fr; }
        .an-title { font-size: 2.1rem; }
        .an-select, .an-select select { width: 100%; }
        .an-all { width: 100%; }
    }
</style>
@endpush

@section('content')
@php
    $isPaginator = method_exists($animals, 'total');

    $habitatOf = function ($animal) {
        $h = $animal->habitat;
        return is_object($h) ? ($h->name ?? null) : $h;
    };
    $imageOf = function ($animal) {
        $path = null;
        foreach (['image', 'image_path', 'photo', 'thumbnail', 'featured_image', 'main_image', 'cover_image', 'picture', 'image_url'] as $col) {
            if (!empty($animal->{$col})) { $path = $animal->{$col}; break; }
        }
        if (empty($path)) return null;
        if (preg_match('#^(https?:)?//#', $path)) return $path;
        $path = ltrim($path, '/');
        return file_exists(public_path($path)) ? asset($path) : asset('storage/' . $path);
    };

    $totalAnimals = $isPaginator ? $animals->total() : count($animals);
    $publishedCount = collect($animals)->filter(fn($a) => strtolower($a->status) === 'published')->count();
    $draftCount = collect($animals)->count() - $publishedCount;
    $habitatNames = collect($animals)->map(fn($a) => $habitatOf($a))->filter()->unique()->values();
    $habitatsAssigned = $habitatNames->count();
@endphp

<div class="an-wrap">

    <div class="an-crumb">
        <a href="{{ Route::has('admin.dashboard') ? route('admin.dashboard') : url('/admin') }}">Dashboard</a><span class="sep">/</span><span class="current">Animals</span>
    </div>

    <div class="an-head">
        <div>
            <h1 class="an-title">
                Animals
                <svg viewBox="0 0 24 24"><path d="M20.5 3.5C11 3 5 7.5 5 14c0 1.3.3 2.4.8 3.3L3 20.5l1.5 1 2.8-3.2c.9.5 2 .7 3.2.7 6.5 0 10.5-6 10-15.5Zm-5.8 6.2-5.6 6.1-1-.9 5.6-6.1 1 .9Z"/></svg>
            </h1>
            <p class="an-sub">Manage your wildlife collection</p>
        </div>
        <a href="{{ route('admin.animals.create') }}" class="an-add">
            <span class="plus"><svg viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg></span>
            Add Animal
        </a>
    </div>

    <div class="an-stats">
        <div class="an-stat s-total">
            <div class="ic"><svg viewBox="0 0 24 24" style="fill:currentColor;stroke:none"><ellipse cx="6.5" cy="10" rx="2" ry="2.6"/><ellipse cx="10.5" cy="6" rx="2" ry="2.6"/><ellipse cx="15.5" cy="6" rx="2" ry="2.6"/><ellipse cx="19" cy="10.5" rx="2" ry="2.6"/><path d="M12.8 11.5c-2.6 0-5.3 3-5.3 5.3 0 1.8 1.5 2.7 3.1 2.7 .9 0 1.4-.3 2.2-.3s1.3.3 2.2.3c1.600 0 3.100-.9 3.100-2.700 0-2.300-2.700-5.300-5.300-5.300Z"/></svg></div>
            <div><div class="lbl">Total Animals</div><div class="num" id="statTotal">{{ $totalAnimals }}</div></div>
            <svg class="deco" viewBox="0 0 24 24"><ellipse cx="6.5" cy="10" rx="2" ry="2.6"/><ellipse cx="10.5" cy="6" rx="2" ry="2.6"/><ellipse cx="15.5" cy="6" rx="2" ry="2.6"/><ellipse cx="19" cy="10.5" rx="2" ry="2.6"/><path d="M12.8 11.5c-2.6 0-5.3 3-5.3 5.3 0 1.8 1.5 2.7 3.1 2.7 .9 0 1.4-.3 2.2-.3s1.3.3 2.2.3c1.600 0 3.100-.9 3.100-2.700 0-2.300-2.700-5.300-5.300-5.300Z"/></svg>
        </div>
        <div class="an-stat s-pub">
            <div class="ic"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="9"/><path d="m8 12.5 3 3 5-6"/></svg></div>
            <div><div class="lbl">Published</div><div class="num">{{ $publishedCount }}</div></div>
            <svg class="deco" viewBox="0 0 24 24"><path d="M20.5 3.5C11 3 5 7.5 5 14c0 1.3.3 2.4.8 3.3L3 20.5l1.5 1 2.8-3.2c.9.5 2 .7 3.2.7 6.5 0 10.5-6 10-15.5Z"/></svg>
        </div>
        <div class="an-stat s-hab">
            <div class="ic"><svg viewBox="0 0 24 24"><path d="m2.500 19 6-10 4 6 2.500-3.500 6.500 7.500Z"/><path d="m8.500 9 1.800 2.800M15 11.500l1.200 1.600"/></svg></div>
            <div><div class="lbl">Habitats Assigned</div><div class="num">{{ $habitatsAssigned }}</div></div>
            <svg class="deco" viewBox="0 0 24 24"><path d="m1 20 7-12 4.500 7 3-4L23 20Z"/></svg>
        </div>
        <div class="an-stat s-draft">
            <div class="ic"><svg viewBox="0 0 24 24"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8Z"/><path d="M14 3v5h5M9 13h6M9 17h6"/></svg></div>
            <div><div class="lbl">Drafts</div><div class="num">{{ $draftCount }}</div></div>
            <svg class="deco" viewBox="0 0 24 24"><path d="M14 2H7a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7Z"/></svg>
        </div>
    </div>

    <div class="an-panel">
        <div class="an-toolbar">
            <div class="an-all">
                <svg viewBox="0 0 24 24"><path d="M4 6h16M4 12h10M4 18h16"/></svg>
                <span>All Animals (<span id="visibleCount">{{ $totalAnimals }}</span>)</span>
                <svg class="chev" viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
            </div>

            <div class="an-select">
                <select id="statusFilter">
                    <option value="">All Statuses</option>
                    <option value="published">Published</option>
                    <option value="draft">Draft</option>
                </select>
                <svg viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
            </div>

            <div class="an-select">
                <select id="habitatFilter">
                    <option value="">All Habitats</option>
                    @foreach($habitatNames as $hn)
                        <option value="{{ strtolower($hn) }}">{{ $hn }}</option>
                    @endforeach
                </select>
                <svg viewBox="0 0 24 24"><path d="m6 9 6 6 6-6"/></svg>
            </div>

            <div class="an-search">
                <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" id="animalSearch" placeholder="Search by animal name or scientific name..." autocomplete="off">
            </div>
        </div>

        <div class="an-table-wrap">
            <table class="an-table">
                <colgroup>
                    <col style="width:26%"><col style="width:19%"><col><col style="width:130px"><col style="width:130px"><col style="width:170px">
                </colgroup>
                <thead>
                    <tr>
                        <th>Animal</th>
                        <th>Scientific Name</th>
                        <th>Habitat</th>
                        <th>Status</th>
                        <th>Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="animalRows">
                    @forelse($animals as $animal)
                        @php
                            $habitatName = $habitatOf($animal);
                            $img = $imageOf($animal);
                            $isPub = strtolower($animal->status) === 'published';
                        @endphp
                        <tr class="animal-row"
                            data-status="{{ strtolower($animal->status) }}"
                            data-habitat="{{ strtolower($habitatName ?? '') }}"
                            data-search="{{ strtolower($animal->name . ' ' . $animal->scientific_name) }}">
                            <td>
                                <div class="an-animal">
                                    @if($img)
                                        <img class="an-thumb" src="{{ $img }}" alt="{{ $animal->name }}" data-ph="{{ strtoupper(substr($animal->name, 0, 1)) }}">
                                    @else
                                        <div class="an-thumb ph">{{ strtoupper(substr($animal->name, 0, 1)) }}</div>
                                    @endif
                                    <span class="an-name">{{ $animal->name }}</span>
                                </div>
                            </td>
                            <td>
                                @if($animal->scientific_name)
                                    <span class="an-sci">{{ $animal->scientific_name }}</span>
                                @else
                                    <span class="an-sci" style="color:#b3ada3">Not added</span>
                                @endif
                            </td>
                            <td>
                                @if(!empty($habitatName))
                                    <span class="an-pill habitat-set" title="{{ $habitatName }}"><span class="an-clamp">{{ $habitatName }}</span></span>
                                @else
                                    <span class="an-pill habitat-none">
                                        <svg viewBox="0 0 24 24"><path d="m2.500 19 6-10 4 6 2.500-3.500 6.500 7.500Z"/></svg>
                                        Not assigned
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="an-pill {{ $isPub ? 'published' : 'draft' }}">{{ ucfirst($animal->status) }}</span>
                            </td>
                            <td class="nw">{{ optional($animal->updated_at)->format('M d, Y') }}</td>
                            <td>
                                <div class="an-actions">
                                    <a href="{{ route('admin.animals.edit', $animal) }}" class="an-edit">
                                        <svg viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg>
                                        Edit
                                    </a>
                                    <button type="button" class="an-more" data-menu="menu-{{ $animal->getKey() }}" aria-label="More actions">
                                        <svg viewBox="0 0 24 24"><circle cx="12" cy="5" r="1.8"/><circle cx="12" cy="12" r="1.8"/><circle cx="12" cy="19" r="1.8"/></svg>
                                    </button>
                                    <div class="an-menu" id="menu-{{ $animal->getKey() }}">
                                        <form action="{{ route('admin.animals.destroy', $animal) }}" method="POST" style="margin:0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Delete this animal?')">
                                                <svg viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="m6 6 1 14h10l1-14"/></svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="an-empty">
                            <td colspan="6">No animals yet. Click "Add Animal" to create the first one.</td>
                        </tr>
                    @endforelse
                    <tr class="an-empty an-nomatch" id="noMatch">
                        <td colspan="6">No animals match your filters.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="an-foot">
            <div id="showingText">
                @if($isPaginator && $animals->total() > 0)
                    Showing {{ $animals->firstItem() }}–{{ $animals->lastItem() }} of {{ $animals->total() }} {{ Str::plural('animal', $animals->total()) }}
                @else
                    Showing {{ count($animals) ? '1–' . count($animals) : '0' }} of {{ count($animals) }} {{ Str::plural('animal', count($animals)) }}
                @endif
            </div>
            <div class="an-pager">
                @if($isPaginator)
                    @if($animals->onFirstPage())
                        <span class="off"><svg viewBox="0 0 24 24"><path d="m15 6-6 6 6 6"/></svg></span>
                    @else
                        <a href="{{ $animals->previousPageUrl() }}"><svg viewBox="0 0 24 24"><path d="m15 6-6 6 6 6"/></svg></a>
                    @endif
                    <span class="on">{{ $animals->currentPage() }}</span>
                    @if($animals->hasMorePages())
                        <a href="{{ $animals->nextPageUrl() }}"><svg viewBox="0 0 24 24"><path d="m9 6 6 6-6 6"/></svg></a>
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
        var search = document.getElementById('animalSearch');
        var statusSel = document.getElementById('statusFilter');
        var habitatSel = document.getElementById('habitatFilter');
        var rows = document.querySelectorAll('.animal-row');
        var counter = document.getElementById('visibleCount');
        var noMatch = document.getElementById('noMatch');

        function applyFilters() {
            var q = search.value.trim().toLowerCase();
            var st = statusSel.value;
            var hb = habitatSel.value;
            var shown = 0;
            rows.forEach(function (row) {
                var ok = (!q || row.dataset.search.indexOf(q) !== -1) &&
                         (!st || row.dataset.status === st) &&
                         (!hb || row.dataset.habitat === hb);
                row.style.display = ok ? '' : 'none';
                if (ok) shown++;
            });
            counter.textContent = shown;
            if (noMatch) noMatch.style.display = (rows.length && shown === 0) ? 'table-row' : 'none';
        }

        if (rows.length) {
            search.addEventListener('input', applyFilters);
            statusSel.addEventListener('change', applyFilters);
            habitatSel.addEventListener('change', applyFilters);
        }

        // Three-dot action menu
        var openMenu = null;
        function closeMenu() { if (openMenu) { openMenu.classList.remove('open'); openMenu = null; } }

        document.querySelectorAll('.an-more').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                var menu = document.getElementById(btn.dataset.menu);
                if (openMenu === menu) { closeMenu(); return; }
                closeMenu();
                var r = btn.getBoundingClientRect();
                menu.classList.add('open');
                var h = menu.offsetHeight, w = menu.offsetWidth;
                var top = r.bottom + 6;
                if (top + h > window.innerHeight) top = r.top - h - 6;
                menu.style.top = top + 'px';
                menu.style.left = Math.max(8, r.right - w) + 'px';
                openMenu = menu;
            });
        });
        document.addEventListener('click', closeMenu);
        window.addEventListener('scroll', closeMenu, true);
        window.addEventListener('resize', closeMenu);
    })();
</script>

<script>
    // thumbnail load na ho to letter tile dikhao
    (function () {
        document.querySelectorAll('img.an-thumb[data-ph]').forEach(function (img) {
            function swap() {
                var d = document.createElement('div');
                d.className = 'an-thumb ph';
                d.textContent = img.getAttribute('data-ph');
                img.replaceWith(d);
            }
            if (img.complete && img.naturalWidth === 0) swap();
            else img.addEventListener('error', swap);
        });
    })();
</script>

<script>
    // Layout ka apna "Animals" heading hide karta hai (layout file ko touch kiye bina)
    (function () {
        document.querySelectorAll('h1, h2, h3, .page-title, .page-heading').forEach(function (el) {
            if (el.closest('.an-wrap') || el.closest('aside') || el.closest('nav') || el.closest('header')) return;
            if (el.textContent.trim() === 'Animals') el.style.display = 'none';
        });
    })();
</script>
@endsection