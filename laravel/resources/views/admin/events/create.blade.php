@extends('admin.layout')

@section('title', 'Add Event')
@section('page-title', 'Event')

@section('content')
@php
    $basicFields = ['title', 'location', 'event_date', 'event_time', 'description', 'status', 'display_order', 'is_featured', 'is_homepage_visible', 'image'];
    $seoFields   = ['image_alt_text', 'image_caption', 'image_credit', 'meta_title', 'canonical_url', 'meta_description', 'noindex'];
    $basicErr = $errors->hasAny($basicFields);
    $seoErr   = $errors->hasAny($seoFields);
    $startTab = ($seoErr && !$basicErr) ? 'seo' : 'basic';
    $curStatus = old('status', 'published');
@endphp

<style>
    .ea { --g-d:#0b3d26; --g:#0f5132; --orange:#f28c28; --line:#e9e4d8; --text:#1c2a22; --muted:#6b7a70; --red:#d63a2f; }
    .ea, .ea * { box-sizing:border-box; }

    /* ---------- Header ---------- */
    .ea .ea-head { display:flex; align-items:flex-start; justify-content:space-between; gap:20px; flex-wrap:wrap; margin:0 0 22px; }
    .ea .ea-crumb { margin:0 0 6px; font-size:16px; color:var(--muted); }
    .ea .ea-crumb a { color:var(--text); font-weight:600; text-decoration:none; }
    .ea .ea-crumb a:hover { color:var(--g); }
    .ea .ea-title { display:flex; align-items:center; gap:16px; flex-wrap:wrap; margin:0 0 6px !important; padding:0 !important; font-family:'Playfair Display','Georgia',serif; font-weight:800; font-size:44px; line-height:1.12; color:var(--g-d); }
    .ea .ea-badge { display:inline-flex; align-items:center; gap:8px; height:32px; padding:0 15px; border-radius:999px; font-family:inherit; font-size:14px; font-weight:700; letter-spacing:0; }
    .ea .ea-badge::before { content:""; width:8px; height:8px; border-radius:50%; background:currentColor; }
    .ea .ea-pub { background:#e2f4e9; color:#157347; }
    .ea .ea-dra { background:#fdeed9; color:#b86a0c; }
    .ea .ea-sub { margin:0 !important; padding:0 !important; font-size:17px; color:var(--muted); }

    /* ---------- Buttons ---------- */
    .ea .ea-btn { display:inline-flex; align-items:center; justify-content:center; gap:10px; height:52px !important; padding:0 28px !important; margin:0 !important; border-radius:999px; font-family:inherit; font-weight:700; font-size:16px; line-height:1 !important; cursor:pointer; text-decoration:none !important; white-space:nowrap; transition:.18s; }
    .ea .ea-btn svg { width:19px; height:19px; flex-shrink:0; }
    .ea .ea-btn-primary { background:var(--g-d); color:#fff !important; border:1px solid var(--g-d) !important; box-shadow:0 8px 18px rgba(11,61,38,.3); }
    .ea .ea-btn-primary:hover { background:var(--g); border-color:var(--g) !important; transform:translateY(-1px); }
    .ea .ea-btn-outline { background:#fff; color:var(--g-d) !important; border:1px solid var(--line) !important; }
    .ea .ea-btn-outline:hover { border-color:var(--g) !important; background:#f6faf7; }
    .ea .ea-btn-sm { height:44px !important; padding:0 22px !important; font-size:15px; }
    .ea .ea-btn-ghost { background:transparent; color:#b02a20 !important; border:1px solid transparent !important; }
    .ea .ea-btn-ghost:hover { background:#fdeceb; }

    /* ---------- Tabs ---------- */
    .ea .ea-tabs { display:flex; gap:8px; padding:10px; margin:0 0 24px; background:#fff; border:1px solid var(--line); border-radius:22px; }
    .ea .ea-tab { position:relative; display:inline-flex; align-items:center; gap:10px; height:50px; padding:0 22px; border:0; border-radius:14px; background:transparent; font-family:inherit; font-size:16px; font-weight:700; color:var(--muted); cursor:pointer; transition:.18s; }
    .ea .ea-tab svg { width:20px; height:20px; fill:none; stroke:currentColor; stroke-width:1.9; stroke-linecap:round; stroke-linejoin:round; }
    .ea .ea-tab:hover { color:var(--g-d); background:#f6f3ea; }
    .ea .ea-tab.is-active { color:var(--g-d); background:#f3f0e6; box-shadow:inset 0 -3px 0 var(--g-d); }
    .ea .ea-dot { display:none; width:9px; height:9px; border-radius:50%; background:var(--red); position:absolute; top:10px; right:12px; }
    .ea .ea-tab.has-err .ea-dot { display:block; }

    .ea .ea-panel { display:none; }
    .ea .ea-panel.is-active { display:block; animation:eaFade .35s ease; }
    @keyframes eaFade { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:none; } }

    /* ---------- Layout ---------- */
    .ea .ea-grid { display:grid; grid-template-columns:minmax(0,1fr) 450px; gap:24px; align-items:start; }
    .ea .ea-col { display:flex; flex-direction:column; gap:24px; min-width:0; }
    .ea .ea-card { background:#fff; border:1px solid var(--line); border-radius:26px; padding:28px 30px 30px; box-shadow:0 6px 22px rgba(15,81,50,.06); }
    .ea .ea-card-head { display:flex; align-items:center; gap:12px; margin:0 0 4px; }
    .ea .ea-card-head svg { width:24px; height:24px; fill:none; stroke:var(--g-d); stroke-width:1.9; stroke-linecap:round; stroke-linejoin:round; flex-shrink:0; }
    .ea .ea-card-head h2 { margin:0 !important; padding:0 !important; font-family:'Playfair Display','Georgia',serif; font-weight:700; font-size:27px; color:var(--g-d); }
    .ea .ea-card-desc { margin:0 0 22px !important; font-size:15px; color:var(--muted); }
    .ea .ea-sticky { position:sticky; top:20px; }

    /* ---------- Fields ---------- */
    .ea .ea-fields { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:20px 22px; }
    .ea .ea-full { grid-column:1 / -1; }
    .ea .ea-field { display:flex; flex-direction:column; gap:9px; min-width:0; }
    .ea .ea-field > label, .ea .ea-label-row label { margin:0 !important; font-size:15px; font-weight:700; color:var(--text); }
    .ea .ea-req { color:var(--red); }
    .ea .ea-label-row { display:flex; justify-content:space-between; align-items:baseline; gap:10px; }
    .ea .ea-count { font-size:13px; color:var(--muted); font-weight:600; }
    .ea .ea-count.warn { color:var(--red); }
    .ea .ea-field input[type=text], .ea .ea-field input[type=url], .ea .ea-field input[type=number], .ea .ea-field input[type=date], .ea .ea-field select, .ea .ea-field textarea {
        width:100% !important; height:58px; margin:0 !important; padding:0 20px !important; border:1px solid var(--line) !important; border-radius:16px !important;
        background-color:#fff; font:inherit; font-size:16px; color:var(--text); box-shadow:none !important; outline:0; transition:.15s; }
    .ea .ea-field textarea { height:auto; min-height:130px; padding:16px 20px !important; resize:vertical; line-height:1.6; }
    .ea .ea-field textarea.ea-tall { min-height:240px; }
    .ea .ea-field select { cursor:pointer; }
    .ea .ea-field input::placeholder, .ea .ea-field textarea::placeholder { color:#a4aea7; }
    .ea .ea-field input:focus, .ea .ea-field select:focus, .ea .ea-field textarea:focus { border-color:var(--g) !important; box-shadow:0 0 0 4px rgba(15,81,50,.12) !important; }
    .ea .ea-invalid { border-color:#e03131 !important; }
    .ea .ea-hint { margin:0; font-size:13px; color:var(--muted); }
    .ea .ea-err { margin:0; font-size:13px; font-weight:600; color:#c92a2a; }

    /* ---------- Image upload ---------- */
    .ea .ea-drop { position:relative; display:flex; align-items:center; justify-content:center; width:100%; aspect-ratio:3/2; border:2px dashed #d3cfc2; border-radius:20px; background:#f6f3ea; overflow:hidden; cursor:pointer; transition:.18s; }
    .ea .ea-drop:hover, .ea .ea-drop.ea-over { border-color:var(--g); background:#eef5f0; }
    .ea .ea-drop.has-img { border-style:solid; border-color:var(--line); background:#0f5132; box-shadow:0 10px 24px rgba(15,81,50,.2); }
    .ea .ea-drop img { position:absolute; inset:0; width:100% !important; height:100% !important; object-fit:cover; display:none; margin:0; }
    .ea .ea-drop.has-img img { display:block; }
    .ea .ea-drop-empty { display:flex; flex-direction:column; align-items:center; gap:6px; padding:16px; text-align:center; color:var(--muted); }
    .ea .ea-drop-empty svg { width:40px; height:40px; fill:none; stroke:var(--g); stroke-width:1.6; stroke-linecap:round; stroke-linejoin:round; margin-bottom:4px; }
    .ea .ea-drop-empty b { font-size:17px; color:var(--text); }
    .ea .ea-drop-empty span { font-size:14px; }
    .ea .ea-drop.has-img .ea-drop-empty { display:none; }
    .ea .ea-file { position:absolute; width:1px; height:1px; opacity:0; pointer-events:none; }
    .ea .ea-img-meta { margin-top:20px; }
    .ea .ea-img-meta h3 { margin:0 0 14px !important; padding:0 !important; font-family:'Playfair Display','Georgia',serif; font-weight:700; font-size:25px; line-height:1.2; color:var(--g-d); word-break:break-word; }
    .ea .ea-img-actions { display:flex; flex-wrap:wrap; gap:10px; }
    .ea .ea-fname { margin:12px 0 0; font-size:14px; font-weight:600; color:var(--g); word-break:break-all; display:none; }
    .ea .ea-card .ea-hint.ea-tip { margin-top:14px; line-height:1.5; }

    /* ---------- Toggle ---------- */
    .ea .ea-toggle { display:flex; align-items:center; justify-content:space-between; gap:16px; padding:15px 18px; border:1px solid var(--line); border-radius:16px; background:#faf8f2; }
    .ea .ea-toggle b { display:block; font-size:15px; color:var(--text); }
    .ea .ea-toggle span.ea-t-sub { display:block; margin-top:2px; font-size:13px; color:var(--muted); }
    .ea .ea-switch { position:relative; width:54px; height:30px; flex-shrink:0; }
    .ea .ea-switch input[type=checkbox] { position:absolute; inset:0; width:100%; height:100%; margin:0; opacity:0; cursor:pointer; z-index:2; }
    .ea .ea-slider { position:absolute; inset:0; border-radius:999px; background:#d4d9d2; transition:.2s; }
    .ea .ea-slider::before { content:""; position:absolute; top:3px; left:3px; width:24px; height:24px; border-radius:50%; background:#fff; box-shadow:0 2px 5px rgba(0,0,0,.25); transition:.2s; }
    .ea .ea-switch input:checked + .ea-slider { background:var(--g); }
    .ea .ea-switch input:checked + .ea-slider::before { transform:translateX(24px); }
    .ea .ea-switch input:focus-visible + .ea-slider { box-shadow:0 0 0 4px rgba(15,81,50,.2); }
    .ea .ea-toggles { display:flex; flex-direction:column; gap:12px; }

    /* ---------- Search preview ---------- */
    .ea .ea-serp { padding:18px 20px; border:1px solid var(--line); border-radius:16px; background:#faf8f2; }
    .ea .ea-serp-url { margin:0; font-size:13px; color:#1e7a46; word-break:break-all; }
    .ea .ea-serp-title { margin:3px 0 5px; font-size:20px; line-height:1.3; color:#1a4fb4; font-weight:500; word-break:break-word; }
    .ea .ea-serp-desc { margin:0; font-size:14px; line-height:1.55; color:#4b5a51; word-break:break-word; }

    /* ---------- Footer ---------- */
    .ea .ea-foot { display:flex; justify-content:flex-end; gap:14px; margin-top:28px; }

    @media (max-width:1180px) {
        .ea .ea-grid { grid-template-columns:1fr; }
        .ea .ea-sticky { position:static; }
    }
    @media (max-width:640px) {
        .ea .ea-title { font-size:32px; }
        .ea .ea-fields { grid-template-columns:1fr; }
        .ea .ea-card { padding:22px 20px 24px; border-radius:22px; }
        .ea .ea-tabs { overflow-x:auto; }
        .ea .ea-tab { padding:0 16px; white-space:nowrap; }
        .ea .ea-head .ea-btn, .ea .ea-foot .ea-btn { flex:1; }
        .ea .ea-foot { flex-direction:column-reverse; }
    }
</style>

<form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data" class="ea" id="eaForm">
    @csrf

    {{-- ===== Header ===== --}}
    <div class="ea-head">
        <div>
            <div class="ea-crumb"><a href="{{ route('admin.events.index') }}">Events</a> &nbsp;/&nbsp; Add Event</div>
            <h1 class="ea-title">
                <span id="eaTitleText">{{ old('title') ?: 'New Event' }}</span>
                <span class="ea-badge {{ $curStatus === 'published' ? 'ea-pub' : 'ea-dra' }}" id="eaBadge">{{ $curStatus === 'published' ? 'Published' : 'Draft' }}</span>
            </h1>
            <p class="ea-sub">Manage the information displayed on your website.</p>
        </div>
        <button type="submit" class="ea-btn ea-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
            Save Event
        </button>
    </div>

    {{-- ===== Tabs ===== --}}
    <div class="ea-tabs" role="tablist">
        <button type="button" class="ea-tab {{ $startTab === 'basic' ? 'is-active' : '' }} {{ $basicErr ? 'has-err' : '' }}" data-tab="basic" role="tab" aria-selected="{{ $startTab === 'basic' ? 'true' : 'false' }}">
            <svg viewBox="0 0 24 24"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/></svg>
            Basic Info <i class="ea-dot"></i>
        </button>
        <button type="button" class="ea-tab {{ $startTab === 'seo' ? 'is-active' : '' }} {{ $seoErr ? 'has-err' : '' }}" data-tab="seo" role="tab" aria-selected="{{ $startTab === 'seo' ? 'true' : 'false' }}">
            <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="3"/><circle cx="9" cy="10" r="1.8"/><path d="M21 16l-5-5-8 9"/></svg>
            Images &amp; SEO <i class="ea-dot"></i>
        </button>
    </div>

    {{-- ===================== TAB 1: BASIC INFO ===================== --}}
    <div class="ea-panel {{ $startTab === 'basic' ? 'is-active' : '' }}" id="ea-basic" role="tabpanel">
        <div class="ea-grid">

            {{-- Left column --}}
            <div class="ea-col">
                <div class="ea-card">
                    <div class="ea-card-head">
                        <svg viewBox="0 0 24 24"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/></svg>
                        <h2>Basic Information</h2>
                    </div>
                    <p class="ea-card-desc">Enter the core details about the event.</p>

                    <div class="ea-fields">
                        <div class="ea-field">
                            <label for="title">Title <span class="ea-req">*</span></label>
                            <input id="title" name="title" type="text" value="{{ old('title') }}" placeholder="e.g. Sunrise Safari Walk" class="@error('title') ea-invalid @enderror" required>
                            @error('title')<p class="ea-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="ea-field">
                            <label for="location">Location <span class="ea-req">*</span></label>
                            <input id="location" name="location" type="text" value="{{ old('location') }}" placeholder="e.g. Savannah Plains" class="@error('location') ea-invalid @enderror" required>
                            @error('location')<p class="ea-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="ea-field">
                            <label for="event_date">Event Date <span class="ea-req">*</span></label>
                            <input id="event_date" name="event_date" type="date" value="{{ old('event_date') }}" class="@error('event_date') ea-invalid @enderror" required>
                            @error('event_date')<p class="ea-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="ea-field">
                            <label for="event_time">Event Time</label>
                            <input id="event_time" name="event_time" type="text" value="{{ old('event_time') }}" placeholder="08:00 AM" class="@error('event_time') ea-invalid @enderror">
                            <p class="ea-hint">Write it like 08:00 AM or 11:30 AM.</p>
                            @error('event_time')<p class="ea-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="ea-field ea-full">
                            <label for="description">Description <span class="ea-req">*</span></label>
                            <textarea id="description" name="description" class="ea-tall @error('description') ea-invalid @enderror" placeholder="Write what visitors can expect from this event..." required>{{ old('description') }}</textarea>
                            <p class="ea-hint">Tip: leave a blank line between paragraphs. They show as separate paragraphs on the website.</p>
                            @error('description')<p class="ea-err">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right column --}}
            <div class="ea-col">

                {{-- Image upload --}}
                <div class="ea-card">
                    <div class="ea-card-head">
                        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="3"/><circle cx="9" cy="10" r="1.8"/><path d="M21 16l-5-5-8 9"/></svg>
                        <h2>Event Image</h2>
                    </div>
                    <p class="ea-card-desc">Main photo shown on the website.</p>

                    <label class="ea-drop" id="eaDrop" for="image">
                        <img id="eaImg" alt="">
                        <div class="ea-drop-empty">
                            <svg viewBox="0 0 24 24"><path d="M12 16V4M7 9l5-5 5 5"/><path d="M4 16v3a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-3"/></svg>
                            <b>No image yet</b>
                            <span>Click or drop a photo here</span>
                        </div>
                    </label>
                    <input id="image" name="image" type="file" accept="image/*" class="ea-file">

                    <div class="ea-img-meta">
                        <h3 id="eaImgTitle">{{ old('title') ?: 'Event title' }}</h3>
                        <div class="ea-img-actions">
                            <label for="image" class="ea-btn ea-btn-outline ea-btn-sm" id="eaPick">Choose Image</label>
                            <button type="button" class="ea-btn ea-btn-ghost ea-btn-sm" id="eaClear" style="display:none">Remove</button>
                        </div>
                        <p class="ea-fname" id="eaFname"></p>
                    </div>
                    @error('image')<p class="ea-err" style="margin-top:10px">{{ $message }}</p>@enderror
                    <p class="ea-hint ea-tip">Best size 1200 × 800 px (3:2). JPG, PNG or WebP, up to 5 MB.</p>
                </div>

                {{-- Publishing --}}
                <div class="ea-card">
                    <div class="ea-card-head">
                        <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.1a1.7 1.7 0 0 0-1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.1a1.7 1.7 0 0 0 1.5-1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3h0a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.1a1.7 1.7 0 0 0 1 1.5h0a1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8v0a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.1a1.7 1.7 0 0 0-1.5 1z"/></svg>
                        <h2>Publishing</h2>
                    </div>
                    <p class="ea-card-desc">Control where and how this event appears.</p>

                    <div class="ea-fields">
                        <div class="ea-field">
                            <label for="status">Status <span class="ea-req">*</span></label>
                            <select id="status" name="status">
                                <option value="published" {{ $curStatus == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ $curStatus == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                            @error('status')<p class="ea-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="ea-field">
                            <label for="display_order">Display Order</label>
                            <input id="display_order" name="display_order" type="number" min="0" value="{{ old('display_order', 0) }}">
                            @error('display_order')<p class="ea-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="ea-field ea-full" style="margin-top:-8px">
                            <p class="ea-hint">Lower numbers appear first.</p>
                        </div>
                        <div class="ea-field ea-full">
                            <div class="ea-toggles">
                                <div class="ea-toggle">
                                    <div><b>Featured Event</b><span class="ea-t-sub">Highlight this event with a Featured tag.</span></div>
                                    <div class="ea-switch">
                                        <input type="hidden" name="is_featured" value="0">
                                        <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                        <span class="ea-slider"></span>
                                    </div>
                                </div>
                                <div class="ea-toggle">
                                    <div><b>Show on Homepage</b><span class="ea-t-sub">Allow this event in homepage lists.</span></div>
                                    <div class="ea-switch">
                                        <input type="hidden" name="is_homepage_visible" value="0">
                                        <input type="checkbox" name="is_homepage_visible" value="1" {{ old('is_homepage_visible', true) ? 'checked' : '' }}>
                                        <span class="ea-slider"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== TAB 2: IMAGES & SEO ===================== --}}
    <div class="ea-panel {{ $startTab === 'seo' ? 'is-active' : '' }}" id="ea-seo" role="tabpanel">
        <div class="ea-grid">

            <div class="ea-col">
                {{-- Image information --}}
                <div class="ea-card">
                    <div class="ea-card-head">
                        <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="3"/><circle cx="9" cy="10" r="1.8"/><path d="M21 16l-5-5-8 9"/></svg>
                        <h2>Image Information</h2>
                    </div>
                    <p class="ea-card-desc">Alt text, caption and credit help with accessibility and SEO.</p>

                    <div class="ea-fields">
                        <div class="ea-field ea-full">
                            <label for="image_alt_text">Image alt text</label>
                            <input id="image_alt_text" name="image_alt_text" type="text" value="{{ old('image_alt_text') }}" placeholder="Describe what is in the image">
                            <p class="ea-hint">Read by screen readers and search engines.</p>
                            @error('image_alt_text')<p class="ea-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="ea-field">
                            <label for="image_caption">Image caption</label>
                            <input id="image_caption" name="image_caption" type="text" value="{{ old('image_caption') }}" placeholder="Short caption under the image">
                            @error('image_caption')<p class="ea-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="ea-field">
                            <label for="image_credit">Image credit</label>
                            <input id="image_credit" name="image_credit" type="text" value="{{ old('image_credit') }}" placeholder="Photographer name">
                            @error('image_credit')<p class="ea-err">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>

                {{-- SEO settings --}}
                <div class="ea-card">
                    <div class="ea-card-head">
                        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                        <h2>SEO Settings</h2>
                    </div>
                    <p class="ea-card-desc">Control how this event appears in search engines.</p>

                    <div class="ea-fields">
                        <div class="ea-field">
                            <div class="ea-label-row"><label for="meta_title">Meta title</label><span class="ea-count" id="eaCntT">0/70</span></div>
                            <input id="meta_title" name="meta_title" type="text" maxlength="70" value="{{ old('meta_title') }}" placeholder="Title shown in search results">
                            @error('meta_title')<p class="ea-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="ea-field">
                            <label for="canonical_url">Canonical URL</label>
                            <input id="canonical_url" name="canonical_url" type="url" value="{{ old('canonical_url') }}" placeholder="https://example.com/events/event-name">
                            @error('canonical_url')<p class="ea-err">{{ $message }}</p>@enderror
                        </div>
                        <div class="ea-field ea-full">
                            <div class="ea-label-row"><label for="meta_description">Meta description</label><span class="ea-count" id="eaCntD">0/320</span></div>
                            <textarea id="meta_description" name="meta_description" maxlength="320" placeholder="Short summary shown in search results (about 150 characters works best)">{{ old('meta_description') }}</textarea>
                            @error('meta_description')<p class="ea-err">{{ $message }}</p>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="ea-col">
                <div class="ea-sticky ea-col">
                    {{-- Search preview --}}
                    <div class="ea-card">
                        <div class="ea-card-head">
                            <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
                            <h2>Search Preview</h2>
                        </div>
                        <p class="ea-card-desc">How your event may look on Google.</p>
                        <div class="ea-serp">
                            <p class="ea-serp-url" id="eaSerpUrl">yourwebsite.com › events</p>
                            <p class="ea-serp-title" id="eaSerpTitle">Event title</p>
                            <p class="ea-serp-desc" id="eaSerpDesc">Your meta description will appear here.</p>
                        </div>
                    </div>

                    {{-- Visibility --}}
                    <div class="ea-card">
                        <div class="ea-card-head">
                            <svg viewBox="0 0 24 24"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg>
                            <h2>Visibility</h2>
                        </div>
                        <p class="ea-card-desc">Search engine settings for this event.</p>
                        <div class="ea-toggle">
                            <div><b>Do not index</b><span class="ea-t-sub">Ask search engines not to list this page.</span></div>
                            <div class="ea-switch">
                                <input type="hidden" name="noindex" value="0">
                                <input type="checkbox" name="noindex" value="1" {{ old('noindex') ? 'checked' : '' }}>
                                <span class="ea-slider"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== Footer ===== --}}
    <div class="ea-foot">
        <a href="{{ route('admin.events.index') }}" class="ea-btn ea-btn-outline">Cancel</a>
        <button type="submit" class="ea-btn ea-btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
            Save Event
        </button>
    </div>
</form>

<script>
(function () {
    function $(id) { return document.getElementById(id); }

    /* ---------- Tabs ---------- */
    var tabs = document.querySelectorAll('.ea-tab');
    var panels = { basic: $('ea-basic'), seo: $('ea-seo') };
    tabs.forEach(function (t) {
        t.addEventListener('click', function () {
            tabs.forEach(function (x) { x.classList.remove('is-active'); x.setAttribute('aria-selected', 'false'); });
            t.classList.add('is-active'); t.setAttribute('aria-selected', 'true');
            Object.keys(panels).forEach(function (k) { panels[k].classList.toggle('is-active', k === t.dataset.tab); });
        });
    });

    /* ---------- Image upload + preview ---------- */
    var input = $('image'), drop = $('eaDrop'), img = $('eaImg'),
        fname = $('eaFname'), clear = $('eaClear'), pick = $('eaPick');

    function showFile(file) {
        if (!file || !file.type || file.type.indexOf('image/') !== 0) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            img.src = e.target.result;
            drop.classList.add('has-img');
            clear.style.display = '';
            pick.textContent = 'Replace Image';
        };
        reader.readAsDataURL(file);
        fname.textContent = 'Selected: ' + file.name;
        fname.style.display = 'block';
    }
    function reset() {
        input.value = '';
        img.removeAttribute('src');
        drop.classList.remove('has-img');
        clear.style.display = 'none';
        pick.textContent = 'Choose Image';
        fname.style.display = 'none';
    }
    input.addEventListener('change', function () { showFile(input.files[0]); });
    clear.addEventListener('click', reset);
    ['dragenter', 'dragover'].forEach(function (ev) {
        drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add('ea-over'); });
    });
    ['dragleave', 'drop'].forEach(function (ev) {
        drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.remove('ea-over'); });
    });
    drop.addEventListener('drop', function (e) {
        if (e.dataTransfer && e.dataTransfer.files.length) {
            input.files = e.dataTransfer.files;
            showFile(input.files[0]);
        }
    });

    /* ---------- Live title + status badge ---------- */
    var title = $('title'), status = $('status'), hTitle = $('eaTitleText'), badge = $('eaBadge'), imgTitle = $('eaImgTitle');
    function syncHead() {
        var t = title.value.trim();
        hTitle.textContent = t || 'New Event';
        imgTitle.textContent = t || 'Event title';
        var pub = status.value === 'published';
        badge.textContent = pub ? 'Published' : 'Draft';
        badge.className = 'ea-badge ' + (pub ? 'ea-pub' : 'ea-dra');
    }

    /* ---------- SEO counters + search preview ---------- */
    var mt = $('meta_title'), md = $('meta_description'), cu = $('canonical_url'), desc = $('description');
    var cT = $('eaCntT'), cD = $('eaCntD'), sUrl = $('eaSerpUrl'), sTitle = $('eaSerpTitle'), sDesc = $('eaSerpDesc');
    function counter(el, out, max) {
        out.textContent = el.value.length + '/' + max;
        out.classList.toggle('warn', el.value.length >= max);
    }
    function syncSeo() {
        counter(mt, cT, 70);
        counter(md, cD, 320);
        sTitle.textContent = mt.value.trim() || title.value.trim() || 'Event title';
        sUrl.textContent = cu.value.trim() || 'yourwebsite.com › events';
        var d = md.value.trim() || desc.value.trim().replace(/\s+/g, ' ').slice(0, 160);
        sDesc.textContent = d || 'Your meta description will appear here.';
    }
    function syncAll() { syncHead(); syncSeo(); }

    [title, status, mt, md, cu, desc].forEach(function (el) {
        el.addEventListener('input', syncAll);
        el.addEventListener('change', syncAll);
    });
    syncAll();
})();
</script>
@endsection