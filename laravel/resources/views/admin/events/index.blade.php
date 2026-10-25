@extends('admin.layout')

@section('title', 'Events')
@section('page-title', 'Events')

@section('content')
@php
    $isPaginator = $events instanceof \Illuminate\Pagination\AbstractPaginator;
    $list        = $isPaginator ? $events->getCollection() : collect($events);
    $totalCount  = $isPaginator ? $events->total() : $list->count();
    $published   = $list->filter(fn ($e) => strtolower($e->status) === 'published')->count();
    $drafts      = $list->filter(fn ($e) => strtolower($e->status) === 'draft')->count();
    $upcoming    = $list->filter(fn ($e) => $e->event_date && $e->event_date->endOfDay()->isFuture())->count();
@endphp

<style>
    .ev-page {
        --ev-green: #0f3d2a;
        --ev-green-2: #1b6b45;
        --ev-orange: #f08a24;
        --ev-cream: #f6f3ec;
        --ev-border: #e7e2d6;
        --ev-text: #1c2b24;
        --ev-muted: #6b7a72;
        --ev-radius: 18px;
    }
    .ev-page * { box-sizing: border-box; }

    /* ---------- Header ---------- */
    .ev-head { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-bottom: 22px; }
    .ev-head h3 { margin: 0; font-size: 20px; color: var(--ev-text); }
    .ev-head p { margin: 4px 0 0; font-size: 14px; color: var(--ev-muted); }
    .ev-add {
        display: inline-flex; align-items: center; gap: 12px; padding: 8px 22px 8px 8px; border-radius: 999px;
        background: var(--ev-green); color: #fff; font-weight: 700; font-size: 15px; text-decoration: none;
        box-shadow: 0 8px 20px rgba(15, 61, 42, .25); transition: background .15s, transform .15s;
    }
    .ev-add:hover { background: #0b2f20; transform: translateY(-1px); }
    .ev-add-icon { display: grid; place-items: center; width: 34px; height: 34px; border-radius: 50%; background: var(--ev-orange); }
    .ev-add-icon svg { width: 18px; height: 18px; }

    /* ---------- Stat cards ---------- */
    .ev-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 20px; margin-bottom: 24px; }
    .ev-stat {
        display: flex; align-items: center; gap: 16px; padding: 22px; border-radius: var(--ev-radius); border: 1px solid;
    }
    .ev-stat-icon { flex: none; display: grid; place-items: center; width: 52px; height: 52px; border-radius: 50%; color: #fff; }
    .ev-stat-icon svg { width: 24px; height: 24px; }
    .ev-stat-label { font-size: 14px; font-weight: 700; color: var(--ev-text); }
    .ev-stat-value { font-size: 32px; font-weight: 700; line-height: 1.1; color: var(--ev-text); }
    .ev-stat.is-total   { background: #eaf4ee; border-color: #d3e8da; } .is-total .ev-stat-icon   { background: var(--ev-green); }
    .ev-stat.is-pub     { background: #eaf4ee; border-color: #d3e8da; } .is-pub .ev-stat-icon     { background: var(--ev-green-2); }
    .ev-stat.is-up      { background: #fdf1e2; border-color: #f7dfc0; } .is-up .ev-stat-icon      { background: var(--ev-orange); }
    .ev-stat.is-draft   { background: #fbeaea; border-color: #f4d3d3; } .is-draft .ev-stat-icon   { background: #d64f5a; }

    /* ---------- Panel / toolbar ---------- */
    .ev-panel {
        background: #fff; border: 1px solid var(--ev-border); border-radius: 22px; padding: 24px;
        box-shadow: 0 6px 22px rgba(15, 61, 42, .05);
    }
    .ev-toolbar {
        display: flex; align-items: center; gap: 14px; flex-wrap: nowrap; padding: 14px 18px; margin-bottom: 18px;
        background: var(--ev-cream); border-radius: 16px;
    }
    .ev-toolbar-title { display: flex; align-items: center; gap: 10px; font-weight: 700; color: var(--ev-green); margin-right: auto; font-size: 16px; white-space: nowrap; flex: none; }
    .ev-toolbar-title svg { width: 20px; height: 20px; }
    .ev-toolbar .ev-select, .ev-toolbar .ev-search input {
        font: inherit; font-size: 14.5px; color: var(--ev-text); background-color: #fff; border: 1px solid var(--ev-border);
        border-radius: 12px; height: 46px; margin: 0; outline: none; transition: border-color .15s, box-shadow .15s;
    }
    .ev-toolbar .ev-select { flex: 0 0 200px; width: 200px; min-width: 0; padding: 0 14px; }
    .ev-toolbar .ev-search { position: relative; display: block; flex: 0 1 340px; width: 340px; min-width: 0; margin: 0; }
    .ev-toolbar .ev-search svg { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px; color: var(--ev-muted); pointer-events: none; }
    .ev-toolbar .ev-search input { display: block; width: 100%; padding: 0 14px 0 42px; border-radius: 999px; }
    .ev-toolbar .ev-select:focus, .ev-toolbar .ev-search input:focus { border-color: var(--ev-green-2); box-shadow: 0 0 0 3px rgba(27, 107, 69, .14); }

    /* ---------- Table ---------- */
    .ev-wrap { overflow-x: auto; }
    .ev-table { width: 100%; border-collapse: collapse; min-width: 760px; table-layout: fixed; }
    .ev-table thead th {
        background: var(--ev-cream); text-align: left; padding: 16px 18px; font-size: 14.5px; font-weight: 700; color: var(--ev-text);
    }
    .ev-table thead th:first-child { border-radius: 14px 0 0 14px; }
    .ev-table thead th:last-child { border-radius: 0 14px 14px 0; }
    .ev-table tbody td { padding: 16px 18px; border-bottom: 1px solid var(--ev-border); vertical-align: middle; color: var(--ev-text); font-size: 15px; }
    .ev-table tbody tr:last-child td { border-bottom: 0; }
    .ev-table tbody tr:hover td { background: #fcfbf7; }
    .col-title { width: 31%; } .col-date { width: 17%; } .col-loc { width: 22%; } .col-status { width: 14%; } .col-actions { width: 16%; }

    .ev-title { display: flex; align-items: center; gap: 14px; min-width: 0; }
    .ev-badge {
        flex: none; display: flex; flex-direction: column; align-items: center; justify-content: center;
        width: 56px; height: 60px; border-radius: 14px; background: var(--ev-green); color: #fff; line-height: 1.1;
    }
    .ev-badge b { font-size: 20px; color: var(--ev-orange); }
    .ev-badge span { font-size: 12px; font-weight: 600; letter-spacing: .02em; }
    .ev-badge.is-none { background: var(--ev-cream); color: var(--ev-muted); }
    .ev-name { font-weight: 700; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ev-when { display: block; font-size: 12.5px; font-weight: 500; color: var(--ev-muted); }

    .ev-loc { display: flex; align-items: center; gap: 8px; min-width: 0; }
    .ev-loc svg { flex: none; width: 16px; height: 16px; color: var(--ev-muted); }
    .ev-loc span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

    .ev-pill { display: inline-flex; align-items: center; gap: 8px; padding: 7px 14px; border-radius: 999px; font-size: 13.5px; font-weight: 700; }
    .ev-pill::before { content: ''; width: 8px; height: 8px; border-radius: 50%; background: currentColor; }
    .ev-pill.is-published { background: #e1f2e7; color: #1b7a47; }
    .ev-pill.is-draft { background: #fdf0dc; color: #b86a0c; }
    .ev-pill.is-other { background: #ececec; color: #555; }

    .ev-actions { display: flex; align-items: center; gap: 10px; flex-wrap: nowrap; }
    .ev-actions form { margin: 0; display: inline-flex; }
    .ev-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 8px; height: 42px; padding: 0 18px;
        border-radius: 999px; font: inherit; font-size: 14.5px; font-weight: 700; text-decoration: none; cursor: pointer;
        background: #fff; transition: background .15s, color .15s;
    }
    .ev-btn svg { width: 16px; height: 16px; }
    .ev-btn-edit { color: var(--ev-orange); border: 1px solid var(--ev-orange); }
    .ev-btn-edit:hover { background: var(--ev-orange); color: #fff; }
    .ev-btn-del { width: 42px; padding: 0; color: #b3262e; border: 1px solid #efc5c8; }
    .ev-btn-del:hover { background: #b3262e; border-color: #b3262e; color: #fff; }
    .ev-btn:focus-visible { outline: 3px solid rgba(27,107,69,.35); outline-offset: 2px; }

    .ev-empty { text-align: center; padding: 54px 20px !important; color: var(--ev-muted); }
    .ev-empty strong { display: block; color: var(--ev-text); font-size: 17px; margin-bottom: 6px; }
    .ev-empty a { display: inline-block; margin-top: 14px; }
    #ev-nomatch { display: none; }

    .ev-foot { display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; margin-top: 18px; color: var(--ev-muted); font-size: 14px; }
    .ev-foot nav svg { width: 18px; height: 18px; }

    @media (max-width: 1100px) { .ev-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 900px) {
        .ev-toolbar { flex-wrap: wrap; }
        .ev-toolbar-title { flex: 1 1 100%; margin-right: 0; }
        .ev-toolbar .ev-select { flex: 1 1 180px; width: auto; }
        .ev-toolbar .ev-search { flex: 2 1 240px; width: auto; }
    }
    @media (max-width: 600px) {
        .ev-stats { grid-template-columns: 1fr; }
        .ev-panel { padding: 16px; }
    }
</style>

<div class="ev-page">

    <div class="ev-head">
        <div>
            <h3>Event management</h3>
            <p>Create and manage safaris, talks and special visits.</p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="ev-add">
            <span class="ev-add-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
            </span>
            Add Event
        </a>
    </div>

    {{-- ========== STATS ========== --}}
    <div class="ev-stats">
        <div class="ev-stat is-total">
            <span class="ev-stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M3 9h18M8 2v4M16 2v4"/></svg></span>
            <div><div class="ev-stat-label">Total Events</div><div class="ev-stat-value">{{ $totalCount }}</div></div>
        </div>
        <div class="ev-stat is-pub">
            <span class="ev-stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="m8 12 3 3 5-6"/></svg></span>
            <div><div class="ev-stat-label">Published</div><div class="ev-stat-value">{{ $published }}</div></div>
        </div>
        <div class="ev-stat is-up">
            <span class="ev-stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg></span>
            <div><div class="ev-stat-label">Upcoming</div><div class="ev-stat-value">{{ $upcoming }}</div></div>
        </div>
        <div class="ev-stat is-draft">
            <span class="ev-stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5M9 13h6M9 17h6"/></svg></span>
            <div><div class="ev-stat-label">Drafts</div><div class="ev-stat-value">{{ $drafts }}</div></div>
        </div>
    </div>

    {{-- ========== TABLE ========== --}}
    <div class="ev-panel">
        <div class="ev-toolbar">
            <div class="ev-toolbar-title">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 6h16M4 12h10M4 18h6"/></svg>
                All Events ({{ $totalCount }})
            </div>
            <select class="ev-select" id="ev-status-filter" aria-label="Filter by status">
                <option value="">All Statuses</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>
            <label class="ev-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                <input type="search" id="ev-search" placeholder="Search by title or location…" aria-label="Search events">
            </label>
        </div>

        <div class="ev-wrap">
            <table class="ev-table">
                <thead>
                    <tr>
                        <th class="col-title">Event</th>
                        <th class="col-date">Date</th>
                        <th class="col-loc">Location</th>
                        <th class="col-status">Status</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody id="ev-body">
                    @forelse($events as $event)
                        @php
                            $status = strtolower($event->status ?? '');
                            $pill = $status === 'published' ? 'is-published' : ($status === 'draft' ? 'is-draft' : 'is-other');
                        @endphp
                        <tr data-status="{{ $status }}" data-text="{{ strtolower(($event->title ?? '') . ' ' . ($event->location ?? '')) }}">
                            <td>
                                <div class="ev-title">
                                    @if($event->event_date)
                                        <div class="ev-badge"><b>{{ $event->event_date->format('d') }}</b><span>{{ $event->event_date->format('M') }}</span></div>
                                    @else
                                        <div class="ev-badge is-none"><b style="color:inherit;">–</b></div>
                                    @endif
                                    <span class="ev-name" title="{{ $event->title }}">{{ $event->title }}</span>
                                </div>
                            </td>
                            <td>
                                {{ $event->event_date ? $event->event_date->format('d M Y') : '-' }}
                                @if($event->event_date)
                                    <span class="ev-when">{{ $event->event_date->endOfDay()->isFuture() ? 'Upcoming' : 'Past' }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="ev-loc">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                                    <span title="{{ $event->location }}">{{ $event->location ?? '-' }}</span>
                                </div>
                            </td>
                            <td><span class="ev-pill {{ $pill }}">{{ ucfirst($event->status) }}</span></td>
                            <td>
                                <div class="ev-actions">
                                    <a href="{{ route('admin.events.edit', $event) }}" class="ev-btn ev-btn-edit">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.events.destroy', $event) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ev-btn ev-btn-del" aria-label="Delete {{ $event->title }}" title="Delete" onclick="return confirm('Delete this event?')">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="M6 6l1 14h10l1-14"/><path d="M10 11v5M14 11v5"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="ev-empty">
                                <strong>No events found.</strong>
                                Add your first event to show it on the website.
                                <br><a href="{{ route('admin.events.create') }}" class="ev-add" style="padding-right:22px;">Add Event</a>
                            </td>
                        </tr>
                    @endforelse
                    <tr id="ev-nomatch"><td colspan="5" class="ev-empty"><strong>No matching events.</strong>Try a different search or status.</td></tr>
                </tbody>
            </table>
        </div>

        <div class="ev-foot">
            @if($isPaginator)
                <span>Showing {{ $events->firstItem() ?? 0 }}–{{ $events->lastItem() ?? 0 }} of {{ $events->total() }} events</span>
                @if($events->hasPages())<nav>{{ $events->links() }}</nav>@endif
            @else
                <span>Showing {{ $list->count() }} {{ \Illuminate\Support\Str::plural('event', $list->count()) }}</span>
            @endif
        </div>
    </div>
</div>

<script>
(function () {
    var search = document.getElementById('ev-search');
    var status = document.getElementById('ev-status-filter');
    var rows = Array.prototype.slice.call(document.querySelectorAll('#ev-body tr[data-status]'));
    var none = document.getElementById('ev-nomatch');
    if (!rows.length) return;

    function apply() {
        var q = search.value.trim().toLowerCase();
        var s = status.value;
        var shown = 0;
        rows.forEach(function (r) {
            var ok = (!s || r.getAttribute('data-status') === s) && (!q || r.getAttribute('data-text').indexOf(q) !== -1);
            r.style.display = ok ? '' : 'none';
            if (ok) shown++;
        });
        none.style.display = shown ? 'none' : 'table-row';
    }
    search.addEventListener('input', apply);
    status.addEventListener('change', apply);
})();
</script>
@endsection