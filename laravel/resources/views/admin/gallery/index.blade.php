@extends('admin.layout')

@section('title', 'Gallery')
@section('page-title', '')

@section('content')
@php
    $isPaginator = method_exists($gallery, 'total');
    $items       = $isPaginator ? collect($gallery->items()) : collect($gallery);
    $total       = $isPaginator ? $gallery->total() : $items->count();
    $published   = $items->filter(fn($i) => strtolower($i->status ?? '') === 'published')->count();
    $draft       = $items->count() - $published;
    $categories  = $items->pluck('category')->filter()->unique()->values();

    // Hero banner image: C:\xampp\htdocs\wildlife-kingdom\laravel\public\images\gallery-banner.webp
    $bannerUrl = asset('images/gallery-banner.webp');

    // returns [primaryUrl, fallbackUrl]
    $imgUrls = function ($item) {
        $path = $item->image ?? $item->image_path ?? $item->photo ?? $item->thumbnail ?? null;
        if (!$path) return [null, null];
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) return [$path, null];
        $clean = ltrim($path, '/');
        if (\Illuminate\Support\Str::startsWith($clean, 'storage/')) return [asset($clean), asset(substr($clean, 8))];
        return [asset('storage/' . $clean), asset($clean)];
    };
@endphp

<style>
    .gx { --gx-green:#0f5132; --gx-green-d:#0b3d26; --gx-orange:#f28c28; --gx-line:#ece7dc; --gx-text:#1c2a22; --gx-muted:#6b7a70; }
    .gx, .gx * { box-sizing:border-box; }

    /* ---------- Hero ---------- */
    .gx .gx-hero { position:relative; overflow:hidden; border-radius:24px; padding:30px 38px 30px; margin:0 0 24px; min-height:188px;
        background:linear-gradient(100deg,#f6f2e6 0%,#eef0dc 45%,#cfe0b8 100%); box-shadow:0 6px 24px rgba(15,81,50,.08); }
    .gx .gx-hero::before { content:""; position:absolute; right:0; bottom:0; width:62%; height:100%; pointer-events:none;
        background:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 190' preserveAspectRatio='none'><path d='M0 190 L120 70 L220 140 L340 30 L470 130 L600 60 L600 190Z' fill='%2394bd7e' fill-opacity='.55'/><path d='M0 190 L90 120 L200 165 L330 95 L450 160 L600 110 L600 190Z' fill='%2362986a' fill-opacity='.6'/><path d='M200 190 C330 160 460 190 600 168 L600 190Z' fill='%23bfe3ee'/></svg>") no-repeat right bottom / 100% 100%; }
    .gx .gx-hero-has-img::before { display:none; }
    .gx .gx-hero-img { position:absolute; top:0; right:0; bottom:0; width:65%; pointer-events:none;
        background-size:cover; background-position:center; background-repeat:no-repeat;
        -webkit-mask-image:linear-gradient(to right, transparent 0%, #000 45%); mask-image:linear-gradient(to right, transparent 0%, #000 45%); }
    .gx .gx-hero-has-img .gx-hero-img::after { content:""; position:absolute; inset:0; background:linear-gradient(to right, rgba(11,61,38,.05), rgba(11,61,38,.35)); }
    .gx .gx-hero-inner { position:relative; display:flex; justify-content:space-between; align-items:center; gap:20px; flex-wrap:wrap; }
    .gx .gx-crumb { font-size:16px; line-height:1.3; color:var(--gx-muted); margin:0 0 4px; }
    .gx .gx-crumb b { color:var(--gx-text); font-weight:600; }
    .gx .gx-title { display:flex; align-items:center; gap:14px; margin:0 0 6px !important; padding:0 !important; font-family:'Playfair Display','Georgia',serif; font-weight:800; font-size:50px; line-height:1.15; color:var(--gx-green-d); }
    .gx .gx-title svg { width:28px; height:28px; color:#1f8a4c; flex-shrink:0; }
    .gx .gx-sub { margin:0 !important; padding:0 !important; font-size:18px; line-height:1.4; color:#33443a; }
    .gx .gx-hero-right { display:flex; align-items:center; gap:28px; }
    .gx .gx-quote { font-family:'Playfair Display','Georgia',serif; font-style:italic; font-weight:600; color:#fff; text-align:center; font-size:20px; line-height:1.45; text-shadow:0 1px 6px rgba(15,81,50,.35); }
    .gx .gx-add { display:inline-flex; align-items:center; gap:10px; background:var(--gx-green-d); color:#fff !important; text-decoration:none !important; padding:0 24px !important; height:48px !important; line-height:1 !important; margin:0 !important; border-radius:14px; font-weight:600; font-size:16px; box-shadow:0 6px 14px rgba(11,61,38,.28); white-space:nowrap; }
    .gx .gx-add:hover { background:var(--gx-green); }
    .gx .gx-add { position:relative; top:32px; }
    .gx .gx-add svg { width:16px; height:16px; }

    /* ---------- Stat cards ---------- */
    .gx .gx-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:22px; margin:0 0 24px; }
    .gx .gx-stat { position:relative; overflow:hidden; display:flex; align-items:center; gap:20px; padding:0 28px; height:140px; border-radius:20px; border:1px solid; }
    .gx .gx-stat-icon { width:64px !important; height:64px !important; min-width:64px; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; flex-shrink:0; margin:0; padding:0; }
    .gx .gx-stat-icon svg { width:28px; height:28px; }
    .gx .gx-stat-label { font-weight:600; font-size:18px; line-height:1.3; color:var(--gx-text); margin:0; }
    .gx .gx-stat-num { font-size:42px; font-weight:700; line-height:1.1; color:var(--gx-text); margin:0; }
    .gx .gx-deco { position:absolute; right:14px; bottom:10px; width:90px; height:60px; opacity:.55; }
    .gx .gx-s1 { background:#e9f6ef; border-color:#d3ebdd; } .gx .gx-s1 .gx-stat-icon { background:#0f5132; } .gx .gx-s1 .gx-deco { color:#a9d3bb; }
    .gx .gx-s2 { background:#e9f6ef; border-color:#d3ebdd; } .gx .gx-s2 .gx-stat-icon { background:#1f9a5a; } .gx .gx-s2 .gx-deco { color:#a9d3bb; }
    .gx .gx-s3 { background:#fdf0e1; border-color:#f8e0c4; } .gx .gx-s3 .gx-stat-icon { background:#f28c28; } .gx .gx-s3 .gx-deco { color:#f9d5ab; }
    .gx .gx-s4 { background:#efecfb; border-color:#dfdaf4; } .gx .gx-s4 .gx-stat-icon { background:#6b45d6; } .gx .gx-s4 .gx-deco { color:#cfc6f0; }

    /* ---------- Table card ---------- */
    .gx .gx-card { background:#fff; border-radius:22px; padding:24px 25px; box-shadow:0 6px 24px rgba(15,81,50,.07); }

    .gx .gx-tools { display:flex; flex-direction:row; flex-wrap:nowrap; align-items:center; gap:14px; margin:0 0 20px; }
    .gx .gx-search { flex:1 1 auto; min-width:0; display:flex; align-items:center; gap:10px; padding:0 0 0 16px; color:var(--gx-muted); }
    .gx .gx-search svg { width:20px; height:20px; flex-shrink:0; }
    .gx .gx-search input { flex:1; width:100% !important; min-width:0; height:48px; margin:0 !important; padding:0 !important; border:0 !important; outline:0 !important; box-shadow:none !important; background:transparent !important; font:inherit; font-size:16px; color:var(--gx-text); border-radius:0 !important; }
    .gx .gx-tools select { flex:0 0 auto; width:187px !important; min-width:0; height:50px; margin:0 !important; padding:0 14px !important; border:1px solid var(--gx-line) !important; background-color:#fff; border-radius:12px !important; font:inherit; font-size:16px; color:var(--gx-text); cursor:pointer; box-shadow:none !important; }
    .gx .gx-tools select#gxSort { width:200px !important; }
    .gx .gx-sortlbl { flex:0 0 auto; color:var(--gx-muted); font-size:16px; margin:0 !important; padding:0 !important; display:inline !important; }
    .gx .gx-reset { flex:0 0 auto; width:48px !important; height:48px !important; min-width:48px; margin:0 !important; padding:0 !important; border-radius:12px; border:1px solid var(--gx-line) !important; background:#f4f1ea !important; display:flex; align-items:center; justify-content:center; cursor:pointer; color:var(--gx-text) !important; box-shadow:none !important; }
    .gx .gx-reset svg { width:18px; height:18px; }

    .gx .gx-wrap { overflow-x:auto; }
    .gx .gx-table { width:100%; border-collapse:collapse; border-spacing:0; min-width:720px; background:transparent; }
    .gx .gx-table thead th { background:#f4f1ea; text-align:left; font-weight:600; font-size:16px; height:55px; padding:0 18px; border:0; color:var(--gx-text); }
    .gx .gx-table thead th:first-child { border-radius:12px 0 0 12px; }
    .gx .gx-table thead th:last-child { border-radius:0 12px 12px 0; }
    .gx .gx-table tbody td { padding:14px 18px; border:0; border-bottom:1px solid var(--gx-line); vertical-align:middle; font-size:16px; color:var(--gx-text); background:transparent; }
    .gx .gx-thumb { position:relative; width:84px; height:54px; border-radius:10px; background:linear-gradient(135deg,#0f5132,#1c7a4a); display:flex; align-items:center; justify-content:center; color:#f5c85c; overflow:hidden; }
    .gx .gx-thumb img { position:absolute; inset:0; width:100% !important; height:100% !important; object-fit:cover; display:block; margin:0; border-radius:0; }
    .gx .gx-thumb svg { width:22px; height:22px; }
    .gx .gx-name { font-weight:600; font-size:17px; }
    .gx .gx-cat { display:inline-flex; align-items:center; gap:8px; color:#33443a; }
    .gx .gx-cat svg { width:16px; height:16px; color:var(--gx-muted); }
    .gx .gx-badge { display:inline-flex; align-items:center; gap:8px; padding:6px 14px; border-radius:999px; font-weight:600; font-size:15px; line-height:1.3; }
    .gx .gx-badge::before { content:""; width:8px; height:8px; border-radius:50%; background:currentColor; }
    .gx .gx-pub { background:#e2f4e9; color:#157347; }
    .gx .gx-dra { background:#fdeed9; color:#b86a0c; }
    .gx .gx-acts { display:flex; align-items:center; gap:8px; }
    .gx .gx-acts form { margin:0 !important; display:inline-flex; }
    .gx .gx-act { width:46px !important; height:46px !important; min-width:46px; border-radius:10px; border:0 !important; display:flex; align-items:center; justify-content:center; color:#fff !important; cursor:pointer; text-decoration:none !important; padding:0 !important; margin:0 !important; box-shadow:none; }
    .gx .gx-act:hover { filter:brightness(1.08); }
    .gx .gx-act svg { width:19px; height:19px; }
    .gx .gx-edit { background:var(--gx-green) !important; }
    .gx .gx-del { background:var(--gx-orange) !important; }
    .gx .gx-table tbody td.gx-empty { text-align:center; padding:40px 10px; color:var(--gx-muted); }

    .gx .gx-foot { display:flex; justify-content:space-between; align-items:center; margin-top:22px; flex-wrap:wrap; gap:12px; }
    .gx .gx-count { color:var(--gx-muted); font-size:16px; margin:0; }
    .gx .gx-pager { display:flex; gap:8px; }
    .gx .gx-pager a, .gx .gx-pager span { min-width:48px; height:48px; border-radius:12px; border:1px solid var(--gx-line); display:flex; align-items:center; justify-content:center; text-decoration:none; color:var(--gx-muted); font-weight:600; background:#fff; padding:0 8px; }
    .gx .gx-pager .gx-on { background:var(--gx-green-d); border-color:var(--gx-green-d); color:#fff; }
    .gx .gx-pager .gx-off { opacity:.45; }

    @media (max-width:1100px) {
        .gx .gx-stats { grid-template-columns:repeat(2,1fr); }
        .gx .gx-quote { display:none; }
        .gx .gx-tools { flex-wrap:wrap; }
        .gx .gx-search { flex:1 1 100%; }
    }
    @media (max-width:600px) {
        .gx .gx-stats { grid-template-columns:1fr; }
        .gx .gx-title { font-size:36px; }
        .gx .gx-hero { padding:24px 22px; }
        .gx .gx-tools select, .gx .gx-tools select#gxSort { width:100% !important; }
    }
</style>

<div class="gx">

    {{-- Hero --}}
    <div class="gx-hero {{ $bannerUrl ? 'gx-hero-has-img' : '' }}">
        @if($bannerUrl)
            <div class="gx-hero-img" style="background-image:url('{{ $bannerUrl }}');"></div>
        @endif
        <div class="gx-hero-inner">
            <div>
                <div class="gx-crumb">Dashboard &nbsp;/&nbsp; <b>Gallery</b></div>
                <h1 class="gx-title">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 3C10 3 4 8 4 15c0 1.5.4 2.8 1 4 1.3-3.5 4-6.3 8-8-3 2.6-5 5.5-6 9 .9.6 2 1 3.2 1C16 21 21 14 20 3z"/></svg>
                    Gallery
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 3C10 3 4 8 4 15c0 1.5.4 2.8 1 4 1.3-3.5 4-6.3 8-8-3 2.6-5 5.5-6 9 .9.6 2 1 3.2 1C16 21 21 14 20 3z"/></svg>
                </h1>
                <p class="gx-sub">Manage and organize images in your wildlife gallery.</p>
            </div>
            <div class="gx-hero-right">
                <div class="gx-quote">“Every picture tells<br>a wild story.”</div>
                <a href="{{ route('admin.gallery.create') }}" class="gx-add">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M12 5v14M5 12h14"/></svg>
                    Add Image
                </a>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="gx-stats">
        <div class="gx-stat gx-s1">
            <div class="gx-stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="3"/><circle cx="9" cy="10" r="1.8"/><path d="M21 16l-5-5-8 9"/></svg></div>
            <div><div class="gx-stat-label">Total Images</div><div class="gx-stat-num">{{ $total }}</div></div>
            <svg class="gx-deco" viewBox="0 0 90 60" fill="currentColor"><path d="M0 60L30 15l18 25 14-16 28 36z"/></svg>
        </div>
        <div class="gx-stat gx-s2">
            <div class="gx-stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5l4.5 4.5L19 7.5"/></svg></div>
            <div><div class="gx-stat-label">Published</div><div class="gx-stat-num">{{ $published }}</div></div>
            <svg class="gx-deco" viewBox="0 0 24 24" fill="currentColor"><path d="M20 3C10 3 4 8 4 15c0 1.5.4 2.8 1 4 1.3-3.5 4-6.3 8-8-3 2.6-5 5.5-6 9 .9.6 2 1 3.2 1C16 21 21 14 20 3z"/></svg>
        </div>
        <div class="gx-stat gx-s3">
            <div class="gx-stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h6l2 2h8v9a2 2 0 0 1-2 2H4z"/></svg></div>
            <div><div class="gx-stat-label">Categories</div><div class="gx-stat-num">{{ $categories->count() }}</div></div>
            <svg class="gx-deco" viewBox="0 0 60 60" fill="currentColor"><circle cx="38" cy="32" r="24"/></svg>
        </div>
        <div class="gx-stat gx-s4">
            <div class="gx-stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg></div>
            <div><div class="gx-stat-label">Drafts</div><div class="gx-stat-num">{{ $draft }}</div></div>
            <svg class="gx-deco" viewBox="0 0 90 60" fill="currentColor"><path d="M0 60L30 15l18 25 14-16 28 36z"/></svg>
        </div>
    </div>

    {{-- Table --}}
    <div class="gx-card">
        <div class="gx-tools">
            <div class="gx-search">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/></svg>
                <input type="text" id="gxSearch" placeholder="Search gallery...">
            </div>
            <select id="gxStatus">
                <option value="">All Status</option>
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>
            <select id="gxCategory">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ strtolower($cat) }}">{{ $cat }}</option>
                @endforeach
            </select>
            <span class="gx-sortlbl">Sort by</span>
            <select id="gxSort">
                <option value="az">Title (A-Z)</option>
                <option value="za">Title (Z-A)</option>
                <option value="new">Newest first</option>
                <option value="old">Oldest first</option>
            </select>
            <button type="button" class="gx-reset" id="gxReset" title="Reset filters">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 3-6.7L3 8"/><path d="M3 3v5h5"/></svg>
            </button>
        </div>

        <div class="gx-wrap">
            <table class="gx-table">
                <thead>
                    <tr>
                        <th style="width:70px;">ID</th>
                        <th style="width:130px;">Image</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th style="width:140px;">Actions</th>
                    </tr>
                </thead>
                <tbody id="gxBody">
                    @forelse($gallery as $item)
                        @php
                            $status = strtolower($item->status ?? 'draft');
                            [$u1, $u2] = $imgUrls($item);
                        @endphp
                        <tr class="gx-row"
                            data-id="{{ $item->id }}"
                            data-title="{{ strtolower($item->title) }}"
                            data-category="{{ strtolower($item->category ?? '') }}"
                            data-status="{{ $status }}">
                            <td>{{ $item->id }}</td>
                            <td>
                                <div class="gx-thumb">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="3"/><circle cx="9" cy="10" r="1.8"/><path d="M21 16l-5-5-8 9"/></svg>
                                    @if($u1)
                                        <img src="{{ $u1 }}" @if($u2) data-alt="{{ $u2 }}" @endif alt="" onerror="gxImgErr(this)">
                                    @endif
                                </div>
                            </td>
                            <td><span class="gx-name">{{ $item->title }}</span></td>
                            <td>
                                <span class="gx-cat">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h6l2 2h8v9a2 2 0 0 1-2 2H4z"/></svg>
                                    {{ $item->category ?: '—' }}
                                </span>
                            </td>
                            <td><span class="gx-badge {{ $status === 'published' ? 'gx-pub' : 'gx-dra' }}">{{ ucfirst($status) }}</span></td>
                            <td>
                                <div class="gx-acts">
                                    <a href="{{ route('admin.gallery.edit', $item) }}" class="gx-act gx-edit" title="Edit">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                                    </a>
                                    <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="gx-act gx-del" title="Delete" onclick="return confirm('Delete this image?')">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M10 11v6M14 11v6M6 7l1 13h10l1-13M9 7V4h6v3"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="gx-empty">No gallery images found. Add your first image to get started.</td>
                        </tr>
                    @endforelse
                    <tr id="gxNoMatch" style="display:none;">
                        <td colspan="6" class="gx-empty">No images match your filters.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="gx-foot">
            <div class="gx-count" id="gxCount">
                @if($isPaginator && $gallery->count())
                    Showing {{ $gallery->firstItem() }}–{{ $gallery->lastItem() }} of {{ $gallery->total() }} images
                @else
                    Showing {{ $items->count() }} of {{ $total }} images
                @endif
            </div>

            @if($isPaginator && $gallery->hasPages())
                <div class="gx-pager">
                    @if($gallery->onFirstPage())
                        <span class="gx-off">&lsaquo;</span>
                    @else
                        <a href="{{ $gallery->previousPageUrl() }}">&lsaquo;</a>
                    @endif

                    @foreach($gallery->getUrlRange(1, $gallery->lastPage()) as $page => $url)
                        @if($page == $gallery->currentPage())
                            <span class="gx-on">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if($gallery->hasMorePages())
                        <a href="{{ $gallery->nextPageUrl() }}">&rsaquo;</a>
                    @else
                        <span class="gx-off">&rsaquo;</span>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Image fallback: try alternate URL once, then remove <img> so the icon placeholder shows
function gxImgErr(img) {
    if (img.dataset.alt) {
        var next = img.dataset.alt;
        img.dataset.alt = '';
        img.src = next;
    } else {
        img.remove();
    }
}

(function () {
    var body   = document.getElementById('gxBody');
    var search = document.getElementById('gxSearch');
    var status = document.getElementById('gxStatus');
    var cat    = document.getElementById('gxCategory');
    var sort   = document.getElementById('gxSort');
    var reset  = document.getElementById('gxReset');
    var noMatch = document.getElementById('gxNoMatch');
    var count  = document.getElementById('gxCount');
    var rows   = Array.prototype.slice.call(body.querySelectorAll('.gx-row'));
    if (!rows.length) return;

    var originalCount = count.textContent.trim();

    function apply() {
        var q = search.value.trim().toLowerCase();
        var visible = 0;

        rows.sort(function (a, b) {
            switch (sort.value) {
                case 'za':  return b.dataset.title.localeCompare(a.dataset.title);
                case 'new': return (+b.dataset.id) - (+a.dataset.id);
                case 'old': return (+a.dataset.id) - (+b.dataset.id);
                default:    return a.dataset.title.localeCompare(b.dataset.title);
            }
        }).forEach(function (r) { body.insertBefore(r, noMatch); });

        rows.forEach(function (r) {
            var ok = (!q || r.dataset.title.indexOf(q) > -1 || r.dataset.category.indexOf(q) > -1)
                  && (!status.value || r.dataset.status === status.value)
                  && (!cat.value || r.dataset.category === cat.value);
            r.style.display = ok ? '' : 'none';
            if (ok) visible++;
        });

        noMatch.style.display = visible ? 'none' : '';
        var filtering = q || status.value || cat.value;
        count.textContent = filtering ? 'Showing ' + visible + ' of ' + rows.length + ' images' : originalCount;
    }

    search.addEventListener('input', apply);
    [status, cat, sort].forEach(function (el) { el.addEventListener('change', apply); });
    reset.addEventListener('click', function () {
        search.value = ''; status.value = ''; cat.value = ''; sort.value = 'az';
        apply();
    });
})();
</script>
@endsection