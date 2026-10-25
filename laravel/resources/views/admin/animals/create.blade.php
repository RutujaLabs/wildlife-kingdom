@extends('admin.layout')

@section('title', 'Add Animal')
@section('page-title', 'Add Animal')

@section('content')
<style>
    .animal-create {
        --ac-green: #0f3d2a;
        --ac-green-2: #1b6b45;
        --ac-orange: #f08a24;
        --ac-cream: #f6f3ec;
        --ac-border: #e7e2d6;
        --ac-text: #1c2b24;
        --ac-muted: #6b7a72;
        --ac-radius: 18px;
        max-width: 1400px;
    }
    .animal-create * { box-sizing: border-box; }

    /* ---------- Header ---------- */
    .ac-crumbs { font-size: 14px; color: var(--ac-muted); margin-bottom: 6px; }
    .ac-crumbs a { color: var(--ac-green); font-weight: 600; text-decoration: none; }
    .ac-head { display: flex; align-items: flex-end; justify-content: flex-end; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; }
    .ac-head h2 { font-family: 'Playfair Display', Georgia, serif; font-size: 34px; margin: 0; color: var(--ac-green); line-height: 1.1; }
    .ac-head p { margin: 6px 0 0; color: var(--ac-muted); }
    .ac-head-actions { display: flex; gap: 10px; }

    /* ---------- Tabs ---------- */
    .ac-tabs {
        display: flex; gap: 6px; padding: 8px; margin-bottom: 24px; overflow-x: auto;
        background: #fff; border: 1px solid var(--ac-border); border-radius: var(--ac-radius);
        box-shadow: 0 6px 22px rgba(15, 61, 42, .05); scrollbar-width: thin;
    }
    .ac-tab {
        flex: none; display: inline-flex; align-items: center; gap: 8px; padding: 12px 18px;
        border: 0; border-bottom: 3px solid transparent; border-radius: 12px; background: transparent;
        font: inherit; font-size: 15px; font-weight: 600; color: var(--ac-muted); cursor: pointer; white-space: nowrap;
        transition: background .15s, color .15s;
    }
    .ac-tab:hover { background: var(--ac-cream); color: var(--ac-green); }
    .ac-tab[aria-selected="true"] { background: var(--ac-cream); color: var(--ac-green); border-bottom-color: var(--ac-green); }
    .ac-tab:focus-visible { outline: 3px solid rgba(27,107,69,.35); outline-offset: 1px; }
    .ac-tab[hidden] { display: none; }
    .ac-tab svg { width: 18px; height: 18px; flex: none; }
    .ac-tab .ac-dot { width: 8px; height: 8px; border-radius: 50%; background: #d64545; display: none; }
    .ac-tab.has-error .ac-dot { display: inline-block; }
    .ac-pane[hidden] { display: none; }

    /* ---------- Layout ---------- */
    .ac-layout { display: grid; grid-template-columns: minmax(0, 2fr) minmax(300px, 1fr); gap: 24px; align-items: start; }
    .ac-stack { display: grid; gap: 24px; min-width: 0; }
    .ac-card, .ac-sec {
        background: #fff; border: 1px solid var(--ac-border); border-radius: var(--ac-radius);
        padding: 26px; box-shadow: 0 6px 22px rgba(15, 61, 42, .05); min-width: 0; margin: 0;
    }
    .ac-card > h3 {
        font-family: 'Playfair Display', Georgia, serif; font-size: 20px; color: var(--ac-green); margin: 0 0 4px;
    }
    .ac-sub { margin: 0 0 20px; font-size: 14px; color: var(--ac-muted); }
    .ac-pane-stack { display: grid; gap: 24px; }

    /* ---------- Fields ---------- */
    .animal-create .grid-two { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 18px 20px; margin: 0; }
    .animal-create .field { display: flex; flex-direction: column; gap: 7px; min-width: 0; margin: 0; }
    .animal-create .field.full { grid-column: 1 / -1; }
    .animal-create .field > label:not(.ac-switch) { font-size: 14px; font-weight: 600; color: var(--ac-text); }
    .animal-create .req { color: #d64545; margin-left: 2px; }
    .animal-create input[type=text],
    .animal-create input[type=number],
    .animal-create input[type=url],
    .animal-create input[type=email],
    .animal-create select,
    .animal-create textarea {
        width: 100%; font: inherit; font-size: 15px; color: var(--ac-text);
        background: #fff; border: 1px solid var(--ac-border); border-radius: 12px;
        padding: 12px 14px; min-height: 48px; outline: none; transition: border-color .15s, box-shadow .15s;
    }
    .animal-create textarea { min-height: 110px; resize: vertical; line-height: 1.55; }
    .animal-create input:focus, .animal-create select:focus, .animal-create textarea:focus {
        border-color: var(--ac-green-2); box-shadow: 0 0 0 3px rgba(27, 107, 69, .14);
    }
    .animal-create select[multiple] { min-height: 150px; padding: 8px; }
    .ac-hint { font-size: 12.5px; color: var(--ac-muted); }
    .ac-count { text-align: right; font-size: 12.5px; color: var(--ac-muted); }

    /* ---------- Image upload ---------- */
    .ac-drop {
        position: relative; display: grid; place-items: center; text-align: center;
        aspect-ratio: 3 / 2; border: 2px dashed #d9d2c1; border-radius: 14px; background: var(--ac-cream);
        color: var(--ac-muted); font-size: 14px; cursor: pointer; overflow: hidden; transition: border-color .15s;
    }
    .ac-drop:hover, .ac-drop.is-over { border-color: var(--ac-green-2); }
    .ac-drop img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; display: none; }
    .ac-drop.has-image img { display: block; }
    .ac-drop.has-image .ac-drop-text { display: none; }
    .ac-drop input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; width: 100%; height: 100%; min-height: 0; padding: 0; border: 0; }
    .ac-drop-text strong { display: block; color: var(--ac-text); margin-bottom: 2px; }
    .ac-image-meta { display: grid; gap: 14px; margin-top: 18px; }

    /* ---------- Toggles ---------- */
    .ac-toggles { display: grid; gap: 14px; }
    .ac-switch {
        display: flex !important; flex-direction: row !important; align-items: center; gap: 14px;
        padding: 14px 16px; border: 1px solid var(--ac-border); border-radius: 14px; background: var(--ac-cream);
        cursor: pointer; font-weight: 600; color: var(--ac-text);
    }
    .ac-switch small { display: block; font-weight: 400; color: var(--ac-muted); font-size: 12.5px; }
    .ac-switch input[type=checkbox] {
        appearance: none; -webkit-appearance: none; flex: none; width: 46px; height: 26px; border-radius: 999px;
        background: #cfc9ba; position: relative; cursor: pointer; transition: background .2s; margin: 0; padding: 0; min-height: 0; border: 0;
    }
    .ac-switch input[type=checkbox]::after {
        content: ''; position: absolute; top: 3px; left: 3px; width: 20px; height: 20px; border-radius: 50%;
        background: #fff; transition: transform .2s; box-shadow: 0 1px 3px rgba(0,0,0,.25);
    }
    .ac-switch input[type=checkbox]:checked { background: var(--ac-green); }
    .ac-switch input[type=checkbox]:checked::after { transform: translateX(20px); }
    .ac-switch input[type=checkbox]:focus-visible { outline: 3px solid rgba(27,107,69,.35); outline-offset: 2px; }

    /* ---------- Sections pulled in from _extended-fields ---------- */
    .ac-sec h3, .ac-sec h4, .ac-sec legend {
        font-family: 'Playfair Display', Georgia, serif; font-size: 20px; color: var(--ac-green);
        margin: 0 0 20px; padding: 0;
    }
    .ac-sec .panel, .ac-sec section, .ac-sec fieldset {
        border: 0; box-shadow: none; padding: 0; background: transparent; margin: 0; min-width: 0;
    }
    .ac-sec .grid-two + .grid-two, .ac-sec .grid-two + .field, .ac-sec .field + .grid-two { margin-top: 18px; }
    .ac-sec .field > label { font-size: 14px; font-weight: 600; color: var(--ac-text); }
    .ac-sec label:has(> input[type=checkbox]) {
        display: flex; flex-direction: row; align-items: center; gap: 12px; font-weight: 600; font-size: 14px;
        padding: 12px 14px; border: 1px solid var(--ac-border); border-radius: 12px; background: var(--ac-cream);
        margin-top: 12px; cursor: pointer;
    }
    .ac-sec input[type=checkbox] { width: 18px; height: 18px; accent-color: var(--ac-green); margin: 0; flex: none; min-height: 0; }
    .ac-sec input[type=file] {
        width: 100%; padding: 10px; border: 1px dashed #d9d2c1; border-radius: 12px; background: var(--ac-cream); font-size: 14px; min-height: 48px;
    }
    /* repeating rows (FAQs / gallery): separate each entry */
    .ac-sec .grid-two:not(:first-of-type) { padding-top: 18px; border-top: 1px dashed var(--ac-border); }

    /* ---------- Footer ---------- */
    .ac-note {
        margin-top: 24px; padding: 14px 18px; border-radius: 12px; font-size: 14px;
        background: #e8f3ec; border: 1px solid #cfe6d7; color: var(--ac-green);
    }
    .ac-actions { display: flex; justify-content: flex-end; gap: 12px; margin-top: 18px; }
    .animal-create .btn { display: inline-flex; align-items: center; justify-content: center; padding: 14px 26px; border-radius: 999px; font-weight: 700; font-size: 15px; text-decoration: none; cursor: pointer; }
    .animal-create .btn-primary { background: var(--ac-green); color: #fff; border: 1px solid var(--ac-green); }
    .animal-create .btn-primary:hover { background: #0b2f20; }
    .animal-create .btn-outline { background: #fff; color: var(--ac-text); border: 1px solid var(--ac-border); }
    .animal-create .btn-outline:hover { border-color: var(--ac-green-2); }

    .ac-errors { background: #fdecec; border: 1px solid #f5c2c2; color: #9b2c2c; border-radius: 12px; padding: 14px 18px; margin-bottom: 20px; font-size: 14px; }
    .ac-errors ul { margin: 6px 0 0 18px; padding: 0; }

    @media (max-width: 1000px) { .ac-layout { grid-template-columns: 1fr; } }
    @media (max-width: 640px) {
        .animal-create .grid-two { grid-template-columns: 1fr; }
        .ac-card, .ac-sec { padding: 20px; }
        .ac-actions { flex-direction: column-reverse; }
        .animal-create .btn { width: 100%; }
    }
</style>

<div class="animal-create">
    <div class="ac-head">
        <div class="ac-head-actions">
            <a href="{{ route('admin.animals.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" form="animal-create-form" class="btn btn-primary">Save Animal</button>
        </div>
    </div>

    @if($errors->any())
        <div class="ac-errors">
            <strong>Please fix the following:</strong>
            <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <form id="animal-create-form" method="POST" action="{{ route('admin.animals.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- ========== TABS ========== --}}
        <div class="ac-tabs" role="tablist" aria-label="Animal sections">
            <button type="button" class="ac-tab" role="tab" data-pane="basic" aria-selected="true">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/></svg>
                Basic Info <span class="ac-dot"></span>
            </button>
            <button type="button" class="ac-tab" role="tab" data-pane="class" aria-selected="false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s-7-6.2-7-11a7 7 0 0 1 14 0c0 4.8-7 11-7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                Classification &amp; Range <span class="ac-dot"></span>
            </button>
            <button type="button" class="ac-tab" role="tab" data-pane="facts" aria-selected="false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 21c0-9 5-15 15-16-1 10-7 15-15 16z"/><path d="M5 21c2-5 5-8 9-10"/></svg>
                Facts &amp; Conservation <span class="ac-dot"></span>
            </button>
            <button type="button" class="ac-tab" role="tab" data-pane="media" aria-selected="false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="1.5"/><path d="m21 15-5-5L5 21"/></svg>
                Images &amp; SEO <span class="ac-dot"></span>
            </button>
            <button type="button" class="ac-tab" role="tab" data-pane="faq" aria-selected="false">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M9.5 9a2.5 2.5 0 1 1 3.5 2.3c-.7.4-1 1-1 1.7"/><path d="M12 17h.01"/></svg>
                FAQs <span class="ac-dot"></span>
            </button>
        </div>

        {{-- ========== TAB: BASIC INFO ========== --}}
        <div class="ac-pane" id="pane-basic" data-pane="basic" role="tabpanel">
            <div class="ac-layout">
                <div class="ac-stack">
                    <div class="ac-card">
                        <h3>Basic Information</h3>
                        <p class="ac-sub">Enter the core details about the animal.</p>

                        <div class="grid-two">
                            <div class="field">
                                <label for="name">Animal Name<span class="req">*</span></label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                            </div>
                            <div class="field">
                                <label for="scientific_name">Scientific Name</label>
                                <input id="scientific_name" name="scientific_name" type="text" value="{{ old('scientific_name') }}">
                            </div>
                            <div class="field">
                                <label for="slug">URL Slug</label>
                                <input id="slug" name="slug" type="text" value="{{ old('slug') }}">
                                <span class="ac-hint">Letters, numbers, dashes and underscores only.</span>
                            </div>
                            <div class="field">
                                <label for="category">Category<span class="req">*</span></label>
                                <select id="category" name="category" required>
                                    @foreach(['Mammals','Birds','Reptiles','Amphibians','Big Cats','Primates','Herbivores','Other'] as $category)
                                        <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label for="habitat_id">Habitat</label>
                                <select id="habitat_id" name="habitat_id">
                                    <option value="">Select habitat</option>
                                    @foreach($habitats as $habitat)
                                        <option value="{{ $habitat->id }}" {{ old('habitat_id') == $habitat->id ? 'selected' : '' }}>{{ $habitat->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label for="conservation_status">Conservation Status<span class="req">*</span></label>
                                <input id="conservation_status" name="conservation_status" type="text" value="{{ old('conservation_status', 'Not assessed') }}" required>
                            </div>

                            <div class="field full">
                                <label for="short_description">Short Description</label>
                                <input id="short_description" name="short_description" type="text" maxlength="500" value="{{ old('short_description') }}">
                                <span class="ac-count"><span id="short-count">0</span>/500</span>
                            </div>

                            <div class="field full">
                                <label for="description">Full Description<span class="req">*</span></label>
                                <textarea id="description" name="description" rows="8" required>{{ old('description') }}</textarea>
                            </div>

                            <div class="field">
                                <label for="status">Status<span class="req">*</span></label>
                                <select id="status" name="status">
                                    <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                </select>
                            </div>
                            <div class="field">
                                <label for="display_order">Display Order</label>
                                <input id="display_order" name="display_order" type="number" min="0" value="{{ old('display_order', 0) }}">
                                <span class="ac-hint">Lower numbers appear first.</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="ac-stack">
                    <div class="ac-card">
                        <h3>Main Image</h3>
                        <p class="ac-sub">Main photo shown on the website.</p>

                        <div class="ac-drop" id="image-drop">
                            <img id="image-preview" alt="Selected image preview">
                            <div class="ac-drop-text">
                                <strong>No image yet.</strong>
                                Click or drop a photo here.
                            </div>
                            <input id="image" name="image" type="file" accept="image/*" aria-label="Main image">
                        </div>
                        <p class="ac-hint" style="margin-top:10px;">Best size 1200 × 800 px (3:2). JPG, PNG or WebP.</p>

                        <div class="ac-image-meta">
                            <div class="field">
                                <label for="image_alt_text">Alt text</label>
                                <input id="image_alt_text" name="image_alt_text" type="text" value="{{ old('image_alt_text') }}">
                            </div>
                            <div class="field">
                                <label for="image_caption">Caption</label>
                                <input id="image_caption" name="image_caption" type="text" value="{{ old('image_caption') }}">
                            </div>
                            <div class="field">
                                <label for="image_credit">Credit</label>
                                <input id="image_credit" name="image_credit" type="text" value="{{ old('image_credit') }}">
                            </div>
                            <div class="field">
                                <label for="image_source">Source</label>
                                <input id="image_source" name="image_source" type="text" value="{{ old('image_source') }}">
                            </div>
                        </div>
                    </div>

                    <div class="ac-card">
                        <h3>Visibility</h3>
                        <p class="ac-sub">Choose where this animal shows up.</p>
                        <div class="ac-toggles">
                            <label class="ac-switch">
                                <input type="hidden" name="is_featured" value="0">
                                <input name="is_featured" type="checkbox" value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <span>Featured animal<small>Show on homepage and featured sections.</small></span>
                            </label>
                            <label class="ac-switch">
                                <input type="hidden" name="is_homepage_visible" value="0">
                                <input name="is_homepage_visible" type="checkbox" value="1" {{ old('is_homepage_visible', true) ? 'checked' : '' }}>
                                <span>Show on homepage<small>Allow this animal in homepage lists.</small></span>
                            </label>
                        </div>
                    </div>

                    <div id="ac-related-slot"></div>
                </div>
            </div>
        </div>

        {{-- ========== OTHER TABS (filled from _extended-fields by the script below) ========== --}}
        <div class="ac-pane" id="pane-class" data-pane="class" role="tabpanel" hidden><div class="ac-pane-stack"></div></div>
        <div class="ac-pane" id="pane-facts" data-pane="facts" role="tabpanel" hidden><div class="ac-pane-stack"></div></div>
        <div class="ac-pane" id="pane-media" data-pane="media" role="tabpanel" hidden><div class="ac-pane-stack"></div></div>
        <div class="ac-pane" id="pane-faq"   data-pane="faq"   role="tabpanel" hidden><div class="ac-pane-stack"></div></div>

        {{-- Source of the extended sections; the script moves its sections into the tabs above --}}
        <div id="ac-ext-source">
            @include('admin.animals._extended-fields')
        </div>

        <div class="ac-note">Changes you make here will be visible on your public website after saving.</div>

        <div class="ac-actions">
            <a href="{{ route('admin.animals.index') }}" class="btn btn-outline">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Animal</button>
        </div>
    </form>
</div>

<script>
(function () {
    var form = document.getElementById('animal-create-form');

    /* ---------- Sort the extended sections into tabs ---------- */
    var host = document.getElementById('ac-ext-source');
    var items = function (el) {
        return Array.prototype.filter.call(el.children, function (c) {
            return c.tagName !== 'STYLE' && c.tagName !== 'SCRIPT' && c.tagName !== 'INPUT';
        });
    };
    var list = items(host);
    // If the partial wraps everything in a single container, go one level deeper
    while (list.length === 1 && items(list[0]).length > 1) { host = list[0]; list = items(host); }

    var panes = {
        class: document.querySelector('#pane-class .ac-pane-stack'),
        facts: document.querySelector('#pane-facts .ac-pane-stack'),
        media: document.querySelector('#pane-media .ac-pane-stack'),
        faq:   document.querySelector('#pane-faq .ac-pane-stack')
    };
    var relatedSlot = document.getElementById('ac-related-slot');

    list.forEach(function (sec) {
        var heading = sec.querySelector('h1,h2,h3,h4,legend');
        var text = ((heading ? heading.textContent : sec.textContent.slice(0, 120)) || '').toLowerCase();
        sec.classList.add('ac-sec');

        if (/faq|frequently/.test(text)) {
            panes.faq.appendChild(sec);
        } else if (/related/.test(text)) {
            relatedSlot.appendChild(sec);
        } else if (/taxonom|range|classif/.test(text)) {
            panes.class.appendChild(sec);
        } else if (/fact|conserv/.test(text)) {
            panes.facts.appendChild(sec);
        } else if (/gallery|image/.test(text)) {
            panes.media.insertBefore(sec, panes.media.firstChild); // images first, then SEO
        } else {
            panes.media.appendChild(sec); // search & sharing + anything unrecognised
        }
    });

    /* ---------- Tabs ---------- */
    var tabs = Array.prototype.slice.call(document.querySelectorAll('.ac-tab'));
    var paneEls = Array.prototype.slice.call(document.querySelectorAll('.ac-pane'));

    tabs.forEach(function (t) {
        var p = t.getAttribute('data-pane');
        if (p !== 'basic' && panes[p] && panes[p].children.length === 0) t.hidden = true; // nothing to show
    });

    function activate(name) {
        tabs.forEach(function (t) { t.setAttribute('aria-selected', t.getAttribute('data-pane') === name ? 'true' : 'false'); });
        paneEls.forEach(function (p) { p.hidden = p.getAttribute('data-pane') !== name; });
    }
    tabs.forEach(function (t) {
        t.addEventListener('click', function () { activate(t.getAttribute('data-pane')); });
    });

    // If a required/invalid field sits in a hidden tab, jump to that tab
    form.addEventListener('invalid', function (e) {
        var pane = e.target.closest('.ac-pane');
        if (pane && pane.hidden) activate(pane.getAttribute('data-pane'));
    }, true);

    // If the server sent validation errors, jump to the first tab that has an invalid/errored field
    @if($errors->any())
        (function () {
            var keys = {!! json_encode($errors->keys()) !!};
            for (var i = 0; i < keys.length; i++) {
                var sel = '[name="' + keys[i] + '"], [name="' + keys[i].split('.')[0] + '[]"], [name^="' + keys[i].split('.')[0] + '["]';
                var el = document.querySelector(sel);
                var pane = el && el.closest('.ac-pane');
                if (pane) {
                    var tab = document.querySelector('.ac-tab[data-pane="' + pane.getAttribute('data-pane') + '"]');
                    if (tab) tab.classList.add('has-error');
                }
            }
            var firstBad = document.querySelector('.ac-tab.has-error');
            if (firstBad) activate(firstBad.getAttribute('data-pane'));
        })();
    @endif

    /* ---------- Main image preview ---------- */
    var drop = document.getElementById('image-drop');
    var input = document.getElementById('image');
    var preview = document.getElementById('image-preview');
    if (input) {
        input.addEventListener('change', function () {
            var file = input.files && input.files[0];
            if (!file) { drop.classList.remove('has-image'); preview.removeAttribute('src'); return; }
            preview.src = URL.createObjectURL(file);
            drop.classList.add('has-image');
        });
        ['dragenter', 'dragover'].forEach(function (ev) { drop.addEventListener(ev, function () { drop.classList.add('is-over'); }); });
        ['dragleave', 'drop'].forEach(function (ev) { drop.addEventListener(ev, function () { drop.classList.remove('is-over'); }); });
    }

    /* ---------- Short description counter ---------- */
    var short = document.getElementById('short_description');
    var count = document.getElementById('short-count');
    if (short && count) {
        var update = function () { count.textContent = short.value.length; };
        short.addEventListener('input', update);
        update();
    }

    /* ---------- Auto slug from name (until edited manually) ---------- */
    var name = document.getElementById('name');
    var slug = document.getElementById('slug');
    if (name && slug) {
        var touched = slug.value !== '';
        slug.addEventListener('input', function () { touched = true; });
        name.addEventListener('input', function () {
            if (touched) return;
            slug.value = name.value.toLowerCase().trim().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        });
    }
})();
</script>
@endsection