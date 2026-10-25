@extends('admin.layout')

@section('title', 'Edit Event')
@section('page-title', 'Event')

@section('content')
@php
    $rawImg = $event->image_url ?? $event->image_path ?? $event->image ?? null;
    $imgSrc = null;
    if ($rawImg) {
        $imgSrc = preg_match('#^(https?:)?//#', $rawImg) || str_starts_with($rawImg, '/')
            ? $rawImg
            : asset('storage/' . ltrim($rawImg, '/'));
    }
    $status = strtolower(old('status', $event->status));
@endphp

<style>
    .ed-page {
        --ev-green: #0f3d2a; --ev-green-2: #1b6b45; --ev-orange: #f08a24;
        --ev-cream: #f6f3ec; --ev-border: #e7e2d6; --ev-text: #1c2b24; --ev-muted: #6b7a72;
        --ev-serif: 'Playfair Display', Georgia, 'Times New Roman', serif;
        color: var(--ev-text);
    }
    .ed-page * { box-sizing: border-box; }

    /* Header */
    .ed-crumbs { display: flex; gap: 8px; font-size: 14.5px; color: var(--ev-muted); margin-bottom: 10px; }
    .ed-crumbs a { color: var(--ev-text); font-weight: 700; text-decoration: none; }
    .ed-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; flex-wrap: wrap; margin-bottom: 22px; }
    .ed-title-row { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
    .ed-title { margin: 0; font-family: var(--ev-serif); font-size: 38px; line-height: 1.1; font-weight: 700; color: var(--ev-green); }
    .ed-sub { margin: 8px 0 0; font-size: 15.5px; color: var(--ev-muted); }
    .ed-pill { display: inline-flex; align-items: center; gap: 8px; padding: 6px 14px; border-radius: 999px; font-size: 13.5px; font-weight: 700; }
    .ed-pill::before { content: ''; width: 8px; height: 8px; border-radius: 50%; background: currentColor; }
    .ed-pill.is-published { background: #e1f2e7; color: #1b7a47; }
    .ed-pill.is-draft { background: #fdf0dc; color: #b86a0c; }
    .ed-pill.is-other { background: #ececec; color: #555; }

    /* Buttons */
    .ed-btn {
        display: inline-flex; align-items: center; justify-content: center; gap: 10px; height: 52px; padding: 0 26px;
        border-radius: 999px; font: inherit; font-size: 15.5px; font-weight: 700; text-decoration: none; cursor: pointer;
        transition: background .15s, color .15s, border-color .15s;
    }
    .ed-btn svg { width: 20px; height: 20px; flex: none; }
    .ed-btn-primary { background: var(--ev-green); color: #fff; border: 1px solid var(--ev-green); box-shadow: 0 8px 20px rgba(15, 61, 42, .22); }
    .ed-btn-primary:hover { background: #0b2f20; }
    .ed-btn-ghost { background: #fff; color: var(--ev-text); border: 1px solid var(--ev-border); }
    .ed-btn-ghost:hover { border-color: var(--ev-green-2); color: var(--ev-green-2); }
    .ed-btn-sm { height: 44px; padding: 0 20px; font-size: 14.5px; }

    /* Tabs */
    .ed-tabs { display: flex; gap: 6px; padding: 8px; margin-bottom: 24px; overflow-x: auto; background: #fff; border: 1px solid var(--ev-border); border-radius: 18px; }
    .ed-tab {
        flex: none; display: inline-flex; align-items: center; gap: 10px; height: 46px; padding: 0 20px; background: transparent;
        border: 0; border-bottom: 3px solid transparent; border-radius: 14px 14px 10px 10px;
        font: inherit; font-size: 15px; font-weight: 600; color: var(--ev-muted); cursor: pointer;
    }
    .ed-tab svg { width: 19px; height: 19px; flex: none; }
    .ed-tab.is-active { background: var(--ev-cream); color: var(--ev-text); font-weight: 700; border-bottom-color: var(--ev-green); }

    /* Layout */
    .ed-layout { display: grid; grid-template-columns: minmax(0, 1fr) 360px; gap: 28px; align-items: start; }
    .ed-pane { display: none; }
    .ed-pane.is-active { display: block; }
    .ed-card { background: #fff; border: 1px solid var(--ev-border); border-radius: 22px; padding: 28px 30px; box-shadow: 0 6px 22px rgba(15, 61, 42, .05); }
    .ed-card + .ed-card { margin-top: 24px; }
    .ed-card-title { display: flex; align-items: center; gap: 12px; margin: 0; font-family: var(--ev-serif); font-size: 24px; font-weight: 700; color: var(--ev-green); }
    .ed-card-title svg { width: 22px; height: 22px; flex: none; color: var(--ev-text); }
    .ed-card-sub { margin: 6px 0 22px; font-size: 14.5px; color: var(--ev-muted); }

    /* Fields */
    .ed-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 20px 26px; align-items: start; }
    .ed-full { grid-column: 1 / -1; }
    .ed-field { display: flex; flex-direction: column; gap: 8px; min-width: 0; margin: 0; }
    .ed-field > label, .ed-label { font-size: 14.5px; font-weight: 700; color: var(--ev-text); margin: 0; }
    .ed-req { color: #d64f5a; }
    .ed-page .ed-field input[type="text"], .ed-page .ed-field input[type="url"], .ed-page .ed-field input[type="date"],
    .ed-page .ed-field input[type="number"], .ed-page .ed-field select, .ed-page .ed-field textarea {
        display: block; width: 100%; margin: 0; height: 52px; padding: 0 18px; font: inherit; font-size: 15.5px; color: var(--ev-text);
        background-color: #fff; border: 1px solid var(--ev-border); border-radius: 14px; outline: none; transition: border-color .15s, box-shadow .15s;
    }
    .ed-page .ed-field select { padding-right: 40px; }
    .ed-page .ed-field textarea { height: auto; min-height: 110px; padding: 14px 18px; line-height: 1.6; resize: vertical; }
    .ed-page .ed-field textarea.is-tall { min-height: 240px; }
    .ed-page .ed-field input:focus, .ed-page .ed-field select:focus, .ed-page .ed-field textarea:focus { border-color: var(--ev-green-2); box-shadow: 0 0 0 3px rgba(27, 107, 69, .14); }

    /* Toggle */
    .ed-toggle { display: flex; align-items: center; gap: 14px; min-height: 52px; margin: 0; cursor: pointer; font-weight: 400; }
    .ed-toggle input[type="checkbox"] { position: absolute; opacity: 0; width: 0; height: 0; }
    .ed-switch { position: relative; flex: none; width: 60px; height: 34px; border-radius: 999px; background: #d9d6cc; transition: background .2s; }
    .ed-switch::after { content: ''; position: absolute; top: 3px; left: 3px; width: 28px; height: 28px; border-radius: 50%; background: #fff; box-shadow: 0 2px 6px rgba(0,0,0,.2); transition: transform .2s; }
    .ed-toggle input:checked + .ed-switch { background: var(--ev-green); }
    .ed-toggle input:checked + .ed-switch::after { transform: translateX(26px); }

    /* Image card */
    .ed-drop {
        position: relative; display: grid; place-items: center; width: 100%; aspect-ratio: 3 / 2; margin-bottom: 20px; overflow: hidden;
        border: 2px dashed #d9d2c1; border-radius: 16px; background: var(--ev-cream); cursor: pointer; text-align: center;
    }
    .ed-drop.has-image { border-style: solid; border-color: var(--ev-border); }
    .ed-drop img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; display: none; }
    .ed-drop.has-image img { display: block; }
    .ed-drop-empty { padding: 20px; font-size: 15px; font-weight: 600; line-height: 1.5; color: #8a8f87; }
    .ed-drop.has-image .ed-drop-empty { display: none; }
    .ed-img-name { margin: 0 0 14px; font-family: var(--ev-serif); font-size: 24px; font-weight: 700; color: var(--ev-green); line-height: 1.2; }
    .ed-file { position: absolute; width: 1px; height: 1px; opacity: 0; pointer-events: none; }

    /* Footer */
    .ed-foot { display: flex; align-items: center; justify-content: flex-end; gap: 14px; flex-wrap: wrap; margin-top: 28px; }

    @media (max-width: 1100px) { .ed-layout { grid-template-columns: 1fr; } }
    @media (max-width: 720px) {
        .ed-title { font-size: 30px; }
        .ed-card { padding: 20px; }
        .ed-grid { grid-template-columns: 1fr; }
        .ed-foot .ed-btn { flex: 1 1 auto; }
    }
</style>

<div class="ed-page">
    <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <nav class="ed-crumbs">
            <a href="{{ route('admin.events.index') }}">Events</a><span>/</span><span>Edit Event</span>
        </nav>

        <div class="ed-head">
            <div>
                <div class="ed-title-row">
                    <h1 class="ed-title">{{ $event->title }}</h1>
                    <span class="ed-pill {{ $status === 'published' ? 'is-published' : ($status === 'draft' ? 'is-draft' : 'is-other') }}">{{ ucfirst($status) }}</span>
                </div>
                <p class="ed-sub">Manage the information displayed on your website.</p>
            </div>
            <button type="submit" class="ed-btn ed-btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
                Save Changes
            </button>
        </div>

        <div class="ed-tabs" role="tablist">
            <button type="button" class="ed-tab is-active" data-tab="basic">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/></svg>
                Basic Info
            </button>
            <button type="button" class="ed-tab" data-tab="seo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
                Images &amp; SEO
            </button>
        </div>

        <div class="ed-layout">
            <div>
                {{-- TAB: BASIC INFO --}}
                <div class="ed-pane is-active" data-pane="basic">
                    <div class="ed-card">
                        <h2 class="ed-card-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/></svg>
                            Basic Information
                        </h2>
                        <p class="ed-card-sub">Enter the core details about the event.</p>

                        <div class="ed-grid">
                            <div class="ed-field">
                                <label for="title">Title <span class="ed-req">*</span></label>
                                <input id="title" name="title" type="text" value="{{ old('title', $event->title) }}" required>
                            </div>
                            <div class="ed-field">
                                <label for="location">Location <span class="ed-req">*</span></label>
                                <input id="location" name="location" type="text" value="{{ old('location', $event->location) }}" required>
                            </div>
                            <div class="ed-field">
                                <label for="event_date">Event Date <span class="ed-req">*</span></label>
                                <input id="event_date" name="event_date" type="date" value="{{ old('event_date', $event->event_date?->format('Y-m-d')) }}" required>
                            </div>
                            <div class="ed-field">
                                <label for="event_time">Event Time</label>
                                <input id="event_time" name="event_time" type="text" value="{{ old('event_time', $event->event_time) }}" placeholder="08:00 AM">
                            </div>
                            <div class="ed-field ed-full">
                                <label for="description">Description <span class="ed-req">*</span></label>
                                <textarea id="description" name="description" class="is-tall" required>{{ old('description', $event->description) }}</textarea>
                            </div>
                            <div class="ed-field">
                                <label for="status">Status <span class="ed-req">*</span></label>
                                <select id="status" name="status">
                                    <option value="published" {{ $status === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="draft" {{ $status === 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                            <div class="ed-field">
                                <label for="display_order">Display Order</label>
                                <input id="display_order" name="display_order" type="number" min="0" value="{{ old('display_order', $event->display_order) }}">
                            </div>
                            <div class="ed-field">
                                <span class="ed-label">Featured Event</span>
                                <label class="ed-toggle">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}>
                                    <span class="ed-switch"></span>
                                </label>
                            </div>
                            <div class="ed-field">
                                <span class="ed-label">Show on Homepage</span>
                                <label class="ed-toggle">
                                    <input type="hidden" name="is_homepage_visible" value="0">
                                    <input type="checkbox" name="is_homepage_visible" value="1" {{ old('is_homepage_visible', $event->is_homepage_visible) ? 'checked' : '' }}>
                                    <span class="ed-switch"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB: IMAGES & SEO --}}
                <div class="ed-pane" data-pane="seo">
                    <div class="ed-card">
                        <h2 class="ed-card-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
                            Image Details
                        </h2>
                        <p class="ed-card-sub">Alt text, caption and credit for the event image.</p>
                        <div class="ed-grid">
                            <div class="ed-field ed-full">
                                <label for="image_alt_text">Image alt text</label>
                                <input id="image_alt_text" name="image_alt_text" type="text" value="{{ old('image_alt_text', $event->image_alt_text) }}">
                            </div>
                            <div class="ed-field">
                                <label for="image_caption">Image caption</label>
                                <input id="image_caption" name="image_caption" type="text" value="{{ old('image_caption', $event->image_caption) }}">
                            </div>
                            <div class="ed-field">
                                <label for="image_credit">Image credit</label>
                                <input id="image_credit" name="image_credit" type="text" value="{{ old('image_credit', $event->image_credit) }}">
                            </div>
                        </div>
                    </div>

                    <div class="ed-card">
                        <h2 class="ed-card-title">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
                            SEO Settings
                        </h2>
                        <p class="ed-card-sub">Search engine details for this event.</p>
                        <div class="ed-grid">
                            <div class="ed-field">
                                <label for="meta_title">Meta title</label>
                                <input id="meta_title" name="meta_title" type="text" maxlength="70" value="{{ old('meta_title', $event->meta_title) }}">
                            </div>
                            <div class="ed-field">
                                <label for="canonical_url">Canonical URL</label>
                                <input id="canonical_url" name="canonical_url" type="url" value="{{ old('canonical_url', $event->canonical_url) }}">
                            </div>
                            <div class="ed-field ed-full">
                                <label for="meta_description">Meta description</label>
                                <textarea id="meta_description" name="meta_description" maxlength="320">{{ old('meta_description', $event->meta_description) }}</textarea>
                            </div>
                            <div class="ed-field ed-full">
                                <span class="ed-label">Do not index</span>
                                <label class="ed-toggle">
                                    <input type="hidden" name="noindex" value="0">
                                    <input type="checkbox" name="noindex" value="1" {{ old('noindex', $event->noindex) ? 'checked' : '' }}>
                                    <span class="ed-switch"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SIDEBAR --}}
            <aside>
                <div class="ed-card">
                    <h2 class="ed-card-title">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-5-5L5 21"/></svg>
                        Current Image
                    </h2>
                    <p class="ed-card-sub">Main photo shown on the website.</p>

                    <label for="image" class="ed-drop {{ $imgSrc ? 'has-image' : '' }}" id="ed-drop">
                        <img id="ed-drop-img" src="{{ $imgSrc }}" alt="{{ $event->image_alt_text ?: $event->title }}">
                        <span class="ed-drop-empty">No image yet.<br>Click to choose a photo.</span>
                    </label>
                    <input id="image" name="image" type="file" accept="image/*" class="ed-file">

                    <h3 class="ed-img-name">{{ $event->title }}</h3>
                    <label for="image" class="ed-btn ed-btn-ghost ed-btn-sm">Replace Image</label>
                </div>
            </aside>
        </div>

        <div class="ed-foot">
            <a href="{{ route('admin.events.index') }}" class="ed-btn ed-btn-ghost">Cancel</a>
            <button type="submit" class="ed-btn ed-btn-primary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
    (function () {
        var tabs = document.querySelectorAll('.ed-tab'), panes = document.querySelectorAll('.ed-pane');
        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                var key = tab.getAttribute('data-tab');
                tabs.forEach(function (t) { t.classList.toggle('is-active', t === tab); });
                panes.forEach(function (p) { p.classList.toggle('is-active', p.getAttribute('data-pane') === key); });
            });
        });
        var input = document.getElementById('image'), drop = document.getElementById('ed-drop'), img = document.getElementById('ed-drop-img');
        if (input && drop && img) {
            input.addEventListener('change', function () {
                var f = input.files && input.files[0];
                if (!f) return;
                img.src = URL.createObjectURL(f);
                drop.classList.add('has-image');
            });
        }
    })();
</script>
@endsection