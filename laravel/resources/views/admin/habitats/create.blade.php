@extends('admin.layout')

@section('title', 'Add Habitat')
@section('page-title', '')

@section('content')
@php
    // Optional hero banner: put a file named habitat-banner.(webp|jpg|jpeg|png) in public/images/
    // If none exists, the default green mountains artwork is shown.
    $bannerUrl = null;
    foreach (['webp', 'jpg', 'jpeg', 'png'] as $ext) {
        if (file_exists(public_path('images/habitat-banner.' . $ext))) {
            $bannerUrl = asset('images/habitat-banner.' . $ext);
            break;
        }
    }
@endphp

<style>
    .ge { --gx-green:#0f5132; --gx-green-d:#0b3d26; --gx-orange:#f28c28; --gx-line:#ece7dc; --gx-text:#1c2a22; --gx-muted:#6b7a70; }
    .ge, .ge * { box-sizing:border-box; }

    /* ---------- Hero ---------- */
    .ge .ge-hero { position:relative; overflow:hidden; border-radius:24px; padding:30px 38px; margin:0 0 24px; min-height:170px;
        background:linear-gradient(100deg,#f6f2e6 0%,#eef0dc 45%,#cfe0b8 100%); box-shadow:0 6px 24px rgba(15,81,50,.08); }
    .ge .ge-hero-img { position:absolute; top:0; right:0; bottom:0; width:65%; pointer-events:none;
        background-size:cover; background-position:center; background-repeat:no-repeat;
        -webkit-mask-image:linear-gradient(to right, transparent 0%, #000 45%); mask-image:linear-gradient(to right, transparent 0%, #000 45%); }
    .ge .ge-hero-img::after { content:""; position:absolute; inset:0; background:linear-gradient(to right, rgba(11,61,38,.05), rgba(11,61,38,.35)); }
    .ge .ge-hero-inner { position:relative; display:flex; justify-content:space-between; align-items:center; gap:20px; flex-wrap:wrap; min-height:110px; }
    .ge .ge-crumb { font-size:16px; line-height:1.3; color:var(--gx-muted); margin:0 0 4px; }
    .ge .ge-crumb a { color:var(--gx-muted); text-decoration:none; }
    .ge .ge-crumb a:hover { color:var(--gx-green); }
    .ge .ge-crumb b { color:var(--gx-text); font-weight:600; }
    .ge .ge-title { display:flex; align-items:center; gap:14px; margin:0 0 6px !important; padding:0 !important; font-family:'Playfair Display','Georgia',serif; font-weight:800; font-size:44px; line-height:1.15; color:var(--gx-green-d); }
    .ge .ge-title svg { width:26px; height:26px; color:#1f8a4c; flex-shrink:0; }
    .ge .ge-sub { margin:0 !important; padding:0 !important; font-size:18px; line-height:1.4; color:#33443a; }
    .ge .ge-badge { display:inline-flex; align-items:center; gap:7px; height:30px; padding:0 14px; border-radius:999px; font-size:14px; font-weight:600; vertical-align:middle; }
    .ge .ge-badge::before { content:""; width:8px; height:8px; border-radius:50%; background:currentColor; }
    .ge .ge-pub { background:#e2f4e9; color:#157347; }
    .ge .ge-dra { background:#fdeed9; color:#b86a0c; }
    .ge .ge-hero-actions { display:flex; gap:12px; align-items:center; position:relative; top:22px; }

    /* ---------- Buttons ---------- */
    .ge .ge-btn { display:inline-flex; align-items:center; justify-content:center; gap:9px; height:48px !important; padding:0 24px !important; margin:0 !important; border-radius:14px; font-family:inherit; font-weight:600; font-size:16px; line-height:1 !important; cursor:pointer; text-decoration:none !important; white-space:nowrap; transition:.15s; }
    .ge .ge-btn svg { width:18px; height:18px; }
    .ge .ge-btn-primary { background:var(--gx-green-d); color:#fff !important; border:1px solid var(--gx-green-d) !important; box-shadow:0 6px 14px rgba(11,61,38,.28); }
    .ge .ge-btn-primary:hover { background:var(--gx-green); border-color:var(--gx-green) !important; }
    .ge .ge-btn-outline { background:#fff; color:var(--gx-green-d) !important; border:1px solid var(--gx-line) !important; }
    .ge .ge-btn-outline:hover { border-color:var(--gx-green) !important; background:#f6faf7; }

    /* ---------- Layout ---------- */
    .ge .ge-grid { display:grid; grid-template-columns:minmax(0,1fr) 400px; gap:24px; align-items:start; }
    .ge .ge-col { display:flex; flex-direction:column; gap:24px; min-width:0; }
    .ge .ge-card { background:#fff; border-radius:22px; padding:26px 28px; box-shadow:0 6px 24px rgba(15,81,50,.07); }
    .ge .ge-card-head { display:flex; align-items:center; gap:12px; margin:0 0 4px; }
    .ge .ge-card-head svg { width:22px; height:22px; color:var(--gx-green); flex-shrink:0; }
    .ge .ge-card-head h2 { margin:0 !important; padding:0 !important; font-family:'Playfair Display','Georgia',serif; font-weight:700; font-size:24px; color:var(--gx-green-d); }
    .ge .ge-card-desc { margin:0 0 20px !important; font-size:15px; color:var(--gx-muted); }

    /* ---------- Fields ---------- */
    .ge .ge-fields { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:18px 20px; }
    .ge .ge-full { grid-column:1 / -1; }
    .ge .ge-field { display:flex; flex-direction:column; gap:8px; min-width:0; }
    .ge .ge-field label { margin:0 !important; font-size:15px; font-weight:600; color:var(--gx-text); }
    .ge .ge-field label .ge-req { color:#d9480f; }
    .ge .ge-field input[type=text], .ge .ge-field input[type=url], .ge .ge-field input[type=number], .ge .ge-field select, .ge .ge-field textarea {
        width:100% !important; height:50px; margin:0 !important; padding:0 16px !important; border:1px solid var(--gx-line) !important; border-radius:12px !important;
        background-color:#fff; font:inherit; font-size:16px; color:var(--gx-text); box-shadow:none !important; outline:0; transition:.15s; }
    .ge .ge-field textarea { height:auto; min-height:130px; padding:14px 16px !important; resize:vertical; line-height:1.55; }
    .ge .ge-field select { cursor:pointer; }
    .ge .ge-field input:focus, .ge .ge-field select:focus, .ge .ge-field textarea:focus { border-color:var(--gx-green) !important; box-shadow:0 0 0 3px rgba(15,81,50,.12) !important; }
    .ge .ge-hint { margin:0; font-size:13px; color:var(--gx-muted); }
    .ge .ge-err { margin:0; font-size:13px; color:#c92a2a; }
    .ge .ge-invalid { border-color:#e03131 !important; }

    /* ---------- Image container ---------- */
    .ge .ge-preview { position:relative; width:100%; aspect-ratio:3/2; border-radius:18px; overflow:hidden; background:linear-gradient(135deg,#0f5132,#1c7a4a); display:flex; align-items:center; justify-content:center; color:#f5c85c; box-shadow:0 8px 22px rgba(15,81,50,.18); }
    .ge .ge-preview img { position:absolute; inset:0; width:100% !important; height:100% !important; object-fit:cover; display:block; margin:0; border-radius:0; }
    .ge .ge-preview .ge-ph { display:flex; flex-direction:column; align-items:center; gap:8px; text-align:center; font-size:15px; color:#cfe6d6; }
    .ge .ge-preview .ge-ph svg { width:42px; height:42px; color:#f5c85c; }
    .ge .ge-chip { position:absolute; top:12px; left:12px; z-index:2; background:rgba(11,61,38,.82); color:#fff; font-size:12px; font-weight:600; padding:6px 12px; border-radius:999px; backdrop-filter:blur(4px); }
    .ge .ge-chip-new { background:rgba(242,140,40,.95); display:none; }
    .ge .ge-meta { margin:18px 0 0; }
    .ge .ge-meta h3 { margin:0 0 8px !important; padding:0 !important; font-family:'Playfair Display','Georgia',serif; font-weight:700; font-size:22px; color:var(--gx-green-d); }
    .ge .ge-cat { display:inline-flex; align-items:center; height:30px; padding:0 14px; border-radius:999px; background:#e2f4e9; color:#157347; font-size:14px; font-weight:600; text-transform:capitalize; }

    .ge .ge-drop { display:flex; flex-direction:column; align-items:center; gap:6px; margin-top:18px; padding:20px 16px; border:2px dashed #cfd9cb; border-radius:16px; background:#faf8f2; text-align:center; cursor:pointer; transition:.15s; }
    .ge .ge-drop:hover, .ge .ge-drop.ge-over { border-color:var(--gx-green); background:#f1f7f2; }
    .ge .ge-drop svg { width:30px; height:30px; color:var(--gx-green); }
    .ge .ge-drop b { font-size:16px; color:var(--gx-text); }
    .ge .ge-drop span { font-size:13px; color:var(--gx-muted); }
    .ge .ge-drop input[type=file] { display:none; }
    .ge .ge-fname { margin:10px 0 0; font-size:14px; color:var(--gx-green); font-weight:600; word-break:break-all; display:none; }

    /* ---------- Toggle ---------- */
    .ge .ge-toggle-row { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:14px 16px; border:1px solid var(--gx-line); border-radius:14px; background:#faf8f2; }
    .ge .ge-toggle-row b { display:block; font-size:15px; color:var(--gx-text); }
    .ge .ge-toggle-row span { font-size:13px; color:var(--gx-muted); }
    .ge .ge-switch { position:relative; width:52px; height:30px; flex-shrink:0; }
    .ge .ge-switch input { position:absolute; opacity:0; inset:0; width:100%; height:100%; margin:0; cursor:pointer; z-index:2; }
    .ge .ge-slider { position:absolute; inset:0; border-radius:999px; background:#d4d9d2; transition:.2s; }
    .ge .ge-slider::before { content:""; position:absolute; top:3px; left:3px; width:24px; height:24px; border-radius:50%; background:#fff; box-shadow:0 2px 5px rgba(0,0,0,.25); transition:.2s; }
    .ge .ge-switch input:checked + .ge-slider { background:var(--gx-green); }
    .ge .ge-switch input:checked + .ge-slider::before { transform:translateX(22px); }

    /* ---------- Footer bar ---------- */
    .ge .ge-info { display:flex; align-items:center; gap:12px; margin-top:24px; padding:16px 22px; border-radius:14px; background:#e9f6ef; border:1px solid #d3ebdd; color:var(--gx-green-d); font-size:15px; }
    .ge .ge-info svg { width:20px; height:20px; flex-shrink:0; }
    .ge .ge-foot { display:flex; justify-content:flex-end; gap:12px; margin-top:20px; }

    @media (max-width:1100px) {
        .ge .ge-grid { grid-template-columns:1fr; }
        .ge .ge-hero-actions { top:0; }
    }
    @media (max-width:600px) {
        .ge .ge-fields { grid-template-columns:1fr; }
        .ge .ge-title { font-size:32px; }
        .ge .ge-hero { padding:24px 22px; }
        .ge .ge-card { padding:22px 20px; }
        .ge .ge-hero-actions { width:100%; }
        .ge .ge-hero-actions .ge-btn { flex:1; }
    }

    .ge .ge-chip-new { background:rgba(242,140,40,.95); }
    .ge .ge-preview .ge-ph span { font-size:13px; opacity:.85; }
    .ge .ge-meta h3.ge-muted { color:#9aa8a0; }

    /* default hero art (shown when no banner image file exists) */
    .ge .ge-hero-art::before { content:""; position:absolute; right:0; bottom:0; width:62%; height:100%; pointer-events:none;
        background:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 600 190' preserveAspectRatio='none'><path d='M0 190 L120 70 L220 140 L340 30 L470 130 L600 60 L600 190Z' fill='%2394bd7e' fill-opacity='.55'/><path d='M0 190 L90 120 L200 165 L330 95 L450 160 L600 110 L600 190Z' fill='%2362986a' fill-opacity='.6'/><path d='M200 190 C330 160 460 190 600 168 L600 190Z' fill='%23bfe3ee'/></svg>") no-repeat right bottom / 100% 100%; }

    /* label row with counter */
    .ge .ge-label-row { display:flex; justify-content:space-between; align-items:baseline; gap:10px; }
    .ge .ge-count { font-size:13px; color:var(--gx-muted); font-weight:500; }
    .ge .ge-count.ge-warn { color:#c92a2a; }

    /* search preview */
    .ge .ge-serp { margin-top:4px; padding:16px 18px; border:1px solid var(--gx-line); border-radius:14px; background:#faf8f2; }
    .ge .ge-serp-label { margin:0 0 8px; font-size:12px; font-weight:700; letter-spacing:.06em; text-transform:uppercase; color:var(--gx-muted); }
    .ge .ge-serp-url { margin:0; font-size:13px; color:#1e7a46; word-break:break-all; }
    .ge .ge-serp-title { margin:2px 0 4px; font-size:19px; line-height:1.3; color:#1a4fb4; font-weight:500; word-break:break-word; }
    .ge .ge-serp-desc { margin:0; font-size:14px; line-height:1.5; color:#4b5a51; word-break:break-word; }

    .ge .ge-toggle-row + .ge-toggle-row { margin-top:12px; }
</style>

<form method="POST" action="{{ route('admin.habitats.store') }}" enctype="multipart/form-data" class="ge" id="geForm">
    @csrf

    {{-- Hero --}}
    <div class="ge-hero {{ $bannerUrl ? '' : 'ge-hero-art' }}">
        @if($bannerUrl)
            <div class="ge-hero-img" style="background-image:url('{{ $bannerUrl }}');"></div>
        @endif
        <div class="ge-hero-inner">
            <div>
                <div class="ge-crumb"><a href="{{ route('admin.habitats.index') }}">Habitats</a> &nbsp;/&nbsp; <b>Add Habitat</b></div>
                <h1 class="ge-title">
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 3C10 3 4 8 4 15c0 1.5.4 2.8 1 4 1.3-3.5 4-6.3 8-8-3 2.6-5 5.5-6 9 .9.6 2 1 3.2 1C16 21 21 14 20 3z"/></svg>
                    Add Habitat
                    <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20 3C10 3 4 8 4 15c0 1.5.4 2.8 1 4 1.3-3.5 4-6.3 8-8-3 2.6-5 5.5-6 9 .9.6 2 1 3.2 1C16 21 21 14 20 3z"/></svg>
                </h1>
                <p class="ge-sub">Create a new habitat for your wildlife website.</p>
            </div>
            <div class="ge-hero-actions">
                <a href="{{ route('admin.habitats.index') }}" class="ge-btn ge-btn-outline">Cancel</a>
                <button type="submit" class="ge-btn ge-btn-primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
                    Save Habitat
                </button>
            </div>
        </div>
    </div>

    <div class="ge-grid">

        {{-- LEFT COLUMN --}}
        <div class="ge-col">

            {{-- Basic information --}}
            <div class="ge-card">
                <div class="ge-card-head">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/></svg>
                    <h2>Basic Information</h2>
                </div>
                <p class="ge-card-desc">Enter the core details about this habitat.</p>

                <div class="ge-fields">
                    <div class="ge-field ge-full">
                        <label for="name">Name <span class="ge-req">*</span></label>
                        <input id="name" name="name" type="text" value="{{ old('name') }}" placeholder="e.g. African Savanna" class="@error('name') ge-invalid @enderror" required>
                        @error('name')<p class="ge-err">{{ $message }}</p>@enderror
                    </div>
                    <div class="ge-field ge-full">
                        <label for="short_description">Short Description</label>
                        <input id="short_description" name="short_description" type="text" value="{{ old('short_description') }}" placeholder="One line summary shown on cards">
                        @error('short_description')<p class="ge-err">{{ $message }}</p>@enderror
                    </div>
                    <div class="ge-field">
                        <label for="climate">Climate</label>
                        <input id="climate" name="climate" type="text" value="{{ old('climate') }}" placeholder="e.g. Tropical, warm and dry">
                        @error('climate')<p class="ge-err">{{ $message }}</p>@enderror
                    </div>
                    <div class="ge-field">
                        <label for="location">Location</label>
                        <input id="location" name="location" type="text" value="{{ old('location') }}" placeholder="e.g. Sub-Saharan Africa">
                        @error('location')<p class="ge-err">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- About --}}
            <div class="ge-card">
                <div class="ge-card-head">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><path d="M20 3C10 3 4 8 4 15c0 1.5.4 2.8 1 4 1.3-3.5 4-6.3 8-8-3 2.6-5 5.5-6 9 .9.6 2 1 3.2 1C16 21 21 14 20 3z"/></svg>
                    <h2>About this Habitat</h2>
                </div>
                <p class="ge-card-desc">Describe the habitat and where it is found.</p>

                <div class="ge-fields">
                    <div class="ge-field ge-full">
                        <label for="description">Description <span class="ge-req">*</span></label>
                        <textarea id="description" name="description" placeholder="Write the full description of this habitat..." class="@error('description') ge-invalid @enderror" style="min-height:170px" required>{{ old('description') }}</textarea>
                        @error('description')<p class="ge-err">{{ $message }}</p>@enderror
                    </div>
                    <div class="ge-field ge-full">
                        <label for="geographical_range">Geographical range</label>
                        <textarea id="geographical_range" name="geographical_range" placeholder="Countries, regions or continents where this habitat is found...">{{ old('geographical_range') }}</textarea>
                        @error('geographical_range')<p class="ge-err">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- SEO --}}
            <div class="ge-card">
                <div class="ge-card-head">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                    <h2>SEO Settings</h2>
                </div>
                <p class="ge-card-desc">Control how this habitat appears in search engines.</p>

                <div class="ge-fields">
                    <div class="ge-field ge-full">
                        <div class="ge-label-row">
                            <label for="meta_title">Meta title</label>
                            <span class="ge-count" id="geCntTitle">0/70</span>
                        </div>
                        <input id="meta_title" name="meta_title" type="text" maxlength="70" value="{{ old('meta_title') }}" placeholder="Title shown in search results">
                        @error('meta_title')<p class="ge-err">{{ $message }}</p>@enderror
                    </div>
                    <div class="ge-field ge-full">
                        <label for="canonical_url">Canonical URL</label>
                        <input id="canonical_url" name="canonical_url" type="url" value="{{ old('canonical_url') }}" placeholder="https://example.com/habitats/african-savanna">
                        @error('canonical_url')<p class="ge-err">{{ $message }}</p>@enderror
                    </div>
                    <div class="ge-field ge-full">
                        <div class="ge-label-row">
                            <label for="meta_description">Meta description</label>
                            <span class="ge-count" id="geCntDesc">0/320</span>
                        </div>
                        <textarea id="meta_description" name="meta_description" maxlength="320" placeholder="Short summary shown in search results">{{ old('meta_description') }}</textarea>
                        @error('meta_description')<p class="ge-err">{{ $message }}</p>@enderror
                    </div>

                    <div class="ge-field ge-full">
                        <div class="ge-serp">
                            <p class="ge-serp-label">Search preview</p>
                            <p class="ge-serp-url" id="geSerpUrl">yourwebsite.com › habitats</p>
                            <p class="ge-serp-title" id="geSerpTitle">Habitat name</p>
                            <p class="ge-serp-desc" id="geSerpDesc">Your meta description will appear here.</p>
                        </div>
                    </div>

                    <div class="ge-field ge-full">
                        <div class="ge-toggle-row">
                            <div>
                                <b>Do not index</b>
                                <span>Ask search engines not to list this page.</span>
                            </div>
                            <div class="ge-switch">
                                <input type="hidden" name="noindex" value="0">
                                <input type="checkbox" name="noindex" value="1" {{ old('noindex') ? 'checked' : '' }}>
                                <span class="ge-slider"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="ge-col">

            {{-- Image upload --}}
            <div class="ge-card">
                <div class="ge-card-head">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="3"/><circle cx="9" cy="10" r="1.8"/><path d="M21 16l-5-5-8 9"/></svg>
                    <h2>Habitat Image</h2>
                </div>
                <p class="ge-card-desc">Main photo shown on the website.</p>

                <div class="ge-preview" id="gePreview">
                    <span class="ge-chip ge-chip-new" id="geChipNew" style="display:none">Preview</span>
                    <div class="ge-ph" id="gePh">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="3"/><circle cx="9" cy="10" r="1.8"/><path d="M21 16l-5-5-8 9"/></svg>
                        No image yet
                        <span>Your preview will appear here</span>
                    </div>
                </div>

                <div class="ge-meta">
                    <h3 class="ge-muted" id="geLiveName">Habitat name</h3>
                    <span class="ge-cat" id="geLiveLoc" style="display:none"></span>
                </div>

                <label class="ge-drop" id="geDrop" for="image">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4M7 9l5-5 5 5"/><path d="M4 16v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3"/></svg>
                    <b>Choose Image</b>
                    <span>Click or drag &amp; drop a photo here</span>
                    <span>JPG, PNG or WebP</span>
                    <input id="image" name="image" type="file" accept="image/*">
                </label>
                <p class="ge-fname" id="geFname"></p>
                @error('image')<p class="ge-err" style="margin-top:8px">{{ $message }}</p>@enderror
            </div>

            {{-- Image information --}}
            <div class="ge-card">
                <div class="ge-card-head">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/></svg>
                    <h2>Image Information</h2>
                </div>
                <p class="ge-card-desc">Alt text, caption and credit for the image.</p>

                <div class="ge-fields">
                    <div class="ge-field ge-full">
                        <label for="image_alt_text">Image alt text</label>
                        <input id="image_alt_text" name="image_alt_text" type="text" value="{{ old('image_alt_text') }}" placeholder="Describe what is in the image">
                        @error('image_alt_text')<p class="ge-err">{{ $message }}</p>@enderror
                    </div>
                    <div class="ge-field ge-full">
                        <label for="image_caption">Image caption</label>
                        <input id="image_caption" name="image_caption" type="text" value="{{ old('image_caption') }}" placeholder="Short caption under the image">
                        @error('image_caption')<p class="ge-err">{{ $message }}</p>@enderror
                    </div>
                    <div class="ge-field ge-full">
                        <label for="image_credit">Image credit</label>
                        <input id="image_credit" name="image_credit" type="text" value="{{ old('image_credit') }}" placeholder="Photographer name">
                        @error('image_credit')<p class="ge-err">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            {{-- Publishing --}}
            <div class="ge-card">
                <div class="ge-card-head">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3h0a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5h0a1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8v0a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"/></svg>
                    <h2>Publishing</h2>
                </div>
                <p class="ge-card-desc">Control where and how this habitat appears.</p>

                <div class="ge-fields">
                    <div class="ge-field">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                        @error('status')<p class="ge-err">{{ $message }}</p>@enderror
                    </div>
                    <div class="ge-field">
                        <label for="display_order">Display order</label>
                        <input id="display_order" name="display_order" type="number" min="0" value="{{ old('display_order', 0) }}">
                        @error('display_order')<p class="ge-err">{{ $message }}</p>@enderror
                    </div>
                    <div class="ge-field ge-full">
                        <p class="ge-hint" style="margin-top:-6px">Lower numbers appear first.</p>
                    </div>
                    <div class="ge-field ge-full">
                        <div class="ge-toggle-row">
                            <div>
                                <b>Show on homepage</b>
                                <span>Allow this habitat in homepage lists.</span>
                            </div>
                            <div class="ge-switch">
                                <input type="hidden" name="is_homepage_visible" value="0">
                                <input type="checkbox" name="is_homepage_visible" value="1" {{ old('is_homepage_visible', true) ? 'checked' : '' }}>
                                <span class="ge-slider"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="ge-info">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="9"/><path d="M12 8h.01M11 12h1v5h1"/></svg>
        Published habitats will be visible on your public website after saving.
    </div>

    <div class="ge-foot">
        <a href="{{ route('admin.habitats.index') }}" class="ge-btn ge-btn-outline">Cancel</a>
        <button type="submit" class="ge-btn ge-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
            Save Habitat
        </button>
    </div>
</form>

<script>
(function () {
    function $(id) { return document.getElementById(id); }

    // ---------- Image upload + preview ----------
    var input = $('image'), drop = $('geDrop'), prev = $('gePreview'),
        fname = $('geFname'), chip = $('geChipNew'), ph = $('gePh');

    function showFile(file) {
        if (!file || !file.type || file.type.indexOf('image/') !== 0) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            var img = $('geImg');
            if (!img) {
                img = document.createElement('img');
                img.id = 'geImg';
                img.alt = '';
                prev.appendChild(img);
            }
            img.src = e.target.result;
            ph.style.display = 'none';
            chip.style.display = 'inline-block';
        };
        reader.readAsDataURL(file);
        fname.textContent = 'Selected: ' + file.name;
        fname.style.display = 'block';
    }

    input.addEventListener('change', function () { showFile(input.files[0]); });
    ['dragenter', 'dragover'].forEach(function (ev) {
        drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add('ge-over'); });
    });
    ['dragleave', 'drop'].forEach(function (ev) {
        drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.remove('ge-over'); });
    });
    drop.addEventListener('drop', function (e) {
        if (e.dataTransfer && e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            showFile(input.files[0]);
        }
    });

    // ---------- Live name / location in image card ----------
    var name = $('name'), loc = $('location'), lName = $('geLiveName'), lLoc = $('geLiveLoc');
    function syncMeta() {
        var n = name.value.trim();
        lName.textContent = n || 'Habitat name';
        lName.classList.toggle('ge-muted', !n);
        var l = loc.value.trim();
        lLoc.textContent = l;
        lLoc.style.display = l ? '' : 'none';
    }

    // ---------- SEO counters + search preview ----------
    var mt = $('meta_title'), md = $('meta_description'), cu = $('canonical_url'), sd = $('short_description');
    var cT = $('geCntTitle'), cD = $('geCntDesc');
    var sUrl = $('geSerpUrl'), sTitle = $('geSerpTitle'), sDesc = $('geSerpDesc');

    function counter(el, out, max) {
        out.textContent = el.value.length + '/' + max;
        out.classList.toggle('ge-warn', el.value.length >= max);
    }
    function syncSeo() {
        counter(mt, cT, 70);
        counter(md, cD, 320);
        sTitle.textContent = mt.value.trim() || name.value.trim() || 'Habitat name';
        sUrl.textContent = cu.value.trim() || 'yourwebsite.com › habitats';
        sDesc.textContent = md.value.trim() || sd.value.trim() || 'Your meta description will appear here.';
    }
    function syncAll() { syncMeta(); syncSeo(); }

    [name, loc, mt, md, cu, sd].forEach(function (el) { el.addEventListener('input', syncAll); });
    syncAll();
})();
</script>
@endsection