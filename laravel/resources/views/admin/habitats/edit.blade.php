@extends('admin.layout')

@section('title', 'Edit Habitat')
@section('page-title', 'Edit Habitat')

@section('content')
@php
    // Current image URL (path adjust karna ho to yahan change karo)
    $currentImage = null;
    if (!empty($habitat->image)) {
        $currentImage = \Illuminate\Support\Str::startsWith($habitat->image, ['http://', 'https://', '/'])
            ? $habitat->image
            : asset('storage/' . ltrim($habitat->image, '/'));
    }
@endphp

<style>
    .hb-edit { --hb-green:#0f3d2e; --hb-green-2:#1a5c43; --hb-orange:#f08a24; --hb-line:#e8e2d6; --hb-muted:#6b7a72; --hb-bg:#faf8f3; }
    .hb-edit * { box-sizing:border-box; }

    .hb-head { display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:22px; }
    .hb-head h2 { margin:0; font-family:'Playfair Display',serif; font-size:28px; color:var(--hb-green); }
    .hb-head p { margin:4px 0 0; color:var(--hb-muted); font-size:14px; }
    .hb-back { display:inline-flex; align-items:center; gap:6px; color:var(--hb-green); text-decoration:none; font-weight:600; font-size:14px; }

    .hb-layout { display:grid; grid-template-columns:minmax(0,1fr) 340px; gap:24px; align-items:start; }
    .hb-main, .hb-side { display:flex; flex-direction:column; gap:24px; min-width:0; }
    .hb-side { position:sticky; top:96px; }

    .hb-card { background:#fff; border:1px solid var(--hb-line); border-radius:18px; padding:24px; box-shadow:0 6px 24px rgba(15,61,46,.06); }
    .hb-card-title { display:flex; align-items:center; gap:10px; margin:0 0 4px; font-size:17px; font-weight:700; color:var(--hb-green); }
    .hb-card-title i { width:32px; height:32px; border-radius:10px; background:#e7f2ec; color:var(--hb-green-2); display:inline-flex; align-items:center; justify-content:center; font-style:normal; font-size:15px; }
    .hb-card-sub { margin:0 0 20px; color:var(--hb-muted); font-size:13px; }

    .hb-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:18px 20px; }
    .hb-full { grid-column:1 / -1; }

    .hb-field { display:flex; flex-direction:column; gap:7px; min-width:0; }
    .hb-field > label { font-size:13px; font-weight:600; color:#24362e; display:flex; justify-content:space-between; }
    .hb-field .hb-count { font-weight:400; color:var(--hb-muted); }
    .hb-field input[type=text], .hb-field input[type=url], .hb-field input[type=number], .hb-field input:not([type]),
    .hb-field select, .hb-field textarea {
        width:100%; padding:12px 14px; font:inherit; font-size:14px; color:#1b2a23; background:var(--hb-bg);
        border:1.5px solid var(--hb-line); border-radius:12px; outline:none; transition:border-color .15s, box-shadow .15s, background .15s;
    }
    .hb-field textarea { min-height:120px; resize:vertical; line-height:1.55; }
    .hb-field input:focus, .hb-field select:focus, .hb-field textarea:focus { background:#fff; border-color:var(--hb-green-2); box-shadow:0 0 0 4px rgba(26,92,67,.12); }
    .hb-hint { font-size:12px; color:var(--hb-muted); }
    .hb-error { font-size:12px; color:#c0392b; }

    /* Image container */
    .hb-drop { position:relative; display:block; border:2px dashed #cfd8d2; border-radius:16px; background:var(--hb-bg); overflow:hidden; cursor:pointer; transition:border-color .15s, background .15s; }
    .hb-drop:hover, .hb-drop.is-over { border-color:var(--hb-orange); background:#fff7ee; }
    .hb-drop input[type=file] { position:absolute; inset:0; width:100%; height:100%; opacity:0; cursor:pointer; }
    .hb-preview { aspect-ratio:4/3; width:100%; display:flex; align-items:center; justify-content:center; }
    .hb-preview img { width:100%; height:100%; object-fit:cover; display:block; }
    .hb-empty { text-align:center; color:var(--hb-muted); font-size:13px; padding:20px; }
    .hb-empty b { display:block; color:var(--hb-green); font-size:15px; margin:8px 0 2px; }
    .hb-empty .hb-ico { width:52px; height:52px; margin:0 auto; border-radius:50%; background:#e7f2ec; color:var(--hb-green-2); display:flex; align-items:center; justify-content:center; font-size:22px; }
    .hb-badge { position:absolute; left:12px; bottom:12px; background:rgba(15,61,46,.88); color:#fff; font-size:12px; font-weight:600; padding:6px 12px; border-radius:999px; pointer-events:none; }
    .hb-filename { margin-top:10px; font-size:12px; color:var(--hb-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .hb-img-fields { display:grid; gap:16px; margin-top:18px; }

    /* Toggles */
    .hb-toggle { display:flex; align-items:center; justify-content:space-between; gap:14px; padding:14px 0; border-top:1px solid var(--hb-line); cursor:pointer; }
    .hb-toggle:first-of-type { border-top:0; padding-top:0; }
    .hb-toggle strong { display:block; font-size:14px; color:#24362e; }
    .hb-toggle span.hb-sub { font-size:12px; color:var(--hb-muted); }
    .hb-toggle input { position:absolute; opacity:0; pointer-events:none; }
    .hb-switch { flex:none; width:44px; height:25px; border-radius:999px; background:#cfd8d2; position:relative; transition:background .2s; }
    .hb-switch::after { content:""; position:absolute; top:3px; left:3px; width:19px; height:19px; border-radius:50%; background:#fff; box-shadow:0 1px 3px rgba(0,0,0,.25); transition:transform .2s; }
    .hb-toggle input:checked + .hb-switch { background:var(--hb-green-2); }
    .hb-toggle input:checked + .hb-switch::after { transform:translateX(19px); }
    .hb-toggle input:focus-visible + .hb-switch { outline:3px solid rgba(240,138,36,.5); outline-offset:2px; }

    /* Actions */
    .hb-actions { display:flex; gap:12px; }
    .hb-actions .btn { flex:1; text-align:center; justify-content:center; padding:13px 18px; border-radius:999px; font-weight:700; }

    @media (max-width:1100px) { .hb-layout { grid-template-columns:1fr; } .hb-side { position:static; } }
    @media (max-width:640px) { .hb-grid { grid-template-columns:1fr; } .hb-card { padding:18px; } }
</style>

<div class="hb-edit">
    <div class="hb-head">
        <div>
            <h2>{{ $habitat->name }}</h2>
            <p>Habitat ki details, image aur SEO settings yahan se update karo.</p>
        </div>
        <a href="{{ route('admin.habitats.index') }}" class="hb-back">&larr; Back to habitats</a>
    </div>

    @if ($errors->any())
        <div class="hb-card" style="border-color:#f1c0b9;background:#fff5f3;margin-bottom:24px;">
            <strong style="color:#c0392b;">Form save nahi hua. Neeche ki galtiyan theek karo:</strong>
            <ul style="margin:8px 0 0 18px;color:#c0392b;font-size:13px;">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.habitats.update', $habitat) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="hb-layout">
            {{-- ============ LEFT / MAIN ============ --}}
            <div class="hb-main">

                <section class="hb-card">
                    <h3 class="hb-card-title"><i>🌿</i> Basic information</h3>
                    <p class="hb-card-sub">Habitat ka naam, climate aur location.</p>
                    <div class="hb-grid">
                        <div class="hb-field">
                            <label for="name">Name</label>
                            <input id="name" name="name" type="text" value="{{ old('name', $habitat->name) }}" required>
                            @error('name')<span class="hb-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="hb-field">
                            <label for="short_description">Short description</label>
                            <input id="short_description" name="short_description" type="text" value="{{ old('short_description', $habitat->short_description) }}">
                        </div>
                        <div class="hb-field">
                            <label for="climate">Climate</label>
                            <input id="climate" name="climate" type="text" placeholder="e.g. Tropical, humid" value="{{ old('climate', $habitat->climate) }}">
                        </div>
                        <div class="hb-field">
                            <label for="location">Location</label>
                            <input id="location" name="location" type="text" placeholder="e.g. Amazon Basin" value="{{ old('location', $habitat->location) }}">
                        </div>
                    </div>
                </section>

                <section class="hb-card">
                    <h3 class="hb-card-title"><i>📝</i> Content</h3>
                    <p class="hb-card-sub">Public page par dikhne wali detail.</p>
                    <div class="hb-grid">
                        <div class="hb-field hb-full">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" required>{{ old('description', $habitat->description) }}</textarea>
                            @error('description')<span class="hb-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="hb-field hb-full">
                            <label for="geographical_range">Geographical range</label>
                            <textarea id="geographical_range" name="geographical_range">{{ old('geographical_range', $habitat->geographical_range) }}</textarea>
                        </div>
                    </div>
                </section>

                <section class="hb-card">
                    <h3 class="hb-card-title"><i>🔎</i> SEO</h3>
                    <p class="hb-card-sub">Search engines mein yeh habitat kaise dikhega.</p>
                    <div class="hb-grid">
                        <div class="hb-field">
                            <label for="meta_title">Meta title <span class="hb-count" data-count-for="meta_title" data-max="70"></span></label>
                            <input id="meta_title" name="meta_title" type="text" maxlength="70" value="{{ old('meta_title', $habitat->meta_title) }}">
                        </div>
                        <div class="hb-field">
                            <label for="canonical_url">Canonical URL</label>
                            <input id="canonical_url" name="canonical_url" type="url" placeholder="https://" value="{{ old('canonical_url', $habitat->canonical_url) }}">
                        </div>
                        <div class="hb-field hb-full">
                            <label for="meta_description">Meta description <span class="hb-count" data-count-for="meta_description" data-max="320"></span></label>
                            <textarea id="meta_description" name="meta_description" maxlength="320" style="min-height:96px;">{{ old('meta_description', $habitat->meta_description) }}</textarea>
                        </div>
                    </div>
                </section>
            </div>

            {{-- ============ RIGHT / SIDEBAR ============ --}}
            <aside class="hb-side">

                <section class="hb-card">
                    <h3 class="hb-card-title"><i>🖼️</i> Habitat image</h3>
                    <p class="hb-card-sub">Click karo ya image drag & drop karo.</p>

                    <label class="hb-drop" id="dropzone" for="image">
                        <input id="image" name="image" type="file" accept="image/*">
                        <div class="hb-preview" id="preview">
                            @if ($currentImage)
                                <img src="{{ $currentImage }}" alt="{{ $habitat->image_alt_text ?: $habitat->name }}">
                                <span class="hb-badge">Current image</span>
                            @else
                                <div class="hb-empty">
                                    <div class="hb-ico">⬆</div>
                                    <b>Upload image</b>
                                    PNG, JPG ya WebP
                                </div>
                            @endif
                        </div>
                    </label>
                    <div class="hb-filename" id="filename">{{ $currentImage ? 'Naya file chunoge to purani image replace ho jayegi.' : 'Abhi koi image nahi hai.' }}</div>
                    @error('image')<span class="hb-error">{{ $message }}</span>@enderror

                    <div class="hb-img-fields">
                        <div class="hb-field">
                            <label for="image_alt_text">Alt text</label>
                            <input id="image_alt_text" name="image_alt_text" type="text" value="{{ old('image_alt_text', $habitat->image_alt_text) }}">
                        </div>
                        <div class="hb-field">
                            <label for="image_caption">Caption</label>
                            <input id="image_caption" name="image_caption" type="text" value="{{ old('image_caption', $habitat->image_caption) }}">
                        </div>
                        <div class="hb-field">
                            <label for="image_credit">Credit</label>
                            <input id="image_credit" name="image_credit" type="text" value="{{ old('image_credit', $habitat->image_credit) }}">
                        </div>
                    </div>
                </section>

                <section class="hb-card">
                    <h3 class="hb-card-title"><i>⚙️</i> Publishing</h3>
                    <p class="hb-card-sub">Status aur visibility control.</p>

                    <div class="hb-field" style="margin-bottom:18px;">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="published" {{ old('status', $habitat->status) == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="draft" {{ old('status', $habitat->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        </select>
                    </div>

                    <div class="hb-field" style="margin-bottom:18px;">
                        <label for="display_order">Display order</label>
                        <input id="display_order" name="display_order" type="number" min="0" value="{{ old('display_order', $habitat->display_order) }}">
                    </div>

                    <label class="hb-toggle">
                        <span><strong>Show on homepage</strong><span class="hb-sub">Homepage par habitat dikhao</span></span>
                        <input type="hidden" name="is_homepage_visible" value="0">
                        <input type="checkbox" name="is_homepage_visible" value="1" {{ old('is_homepage_visible', $habitat->is_homepage_visible) ? 'checked' : '' }}>
                        <span class="hb-switch"></span>
                    </label>

                    <label class="hb-toggle">
                        <span><strong>Do not index</strong><span class="hb-sub">Search engines se chhupao</span></span>
                        <input type="hidden" name="noindex" value="0">
                        <input type="checkbox" name="noindex" value="1" {{ old('noindex', $habitat->noindex) ? 'checked' : '' }}>
                        <span class="hb-switch"></span>
                    </label>
                </section>

                <div class="hb-actions">
                    <a href="{{ route('admin.habitats.index') }}" class="btn btn-outline">Cancel</a>
                    <button type="submit" class="btn btn-primary">Update habitat</button>
                </div>
            </aside>
        </div>
    </form>
</div>

<script>
(function () {
    var input = document.getElementById('image');
    var preview = document.getElementById('preview');
    var filename = document.getElementById('filename');
    var drop = document.getElementById('dropzone');

    function show(file) {
        if (!file || !file.type.startsWith('image/')) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt=""><span class="hb-badge">New image</span>';
        };
        reader.readAsDataURL(file);
        filename.textContent = file.name + ' (' + Math.round(file.size / 1024) + ' KB)';
    }

    input.addEventListener('change', function () { show(input.files[0]); });
    ['dragenter', 'dragover'].forEach(function (ev) {
        drop.addEventListener(ev, function () { drop.classList.add('is-over'); });
    });
    ['dragleave', 'drop'].forEach(function (ev) {
        drop.addEventListener(ev, function () { drop.classList.remove('is-over'); });
    });

    // Character counters
    document.querySelectorAll('[data-count-for]').forEach(function (el) {
        var field = document.getElementById(el.dataset.countFor);
        var update = function () { el.textContent = field.value.length + '/' + el.dataset.max; };
        field.addEventListener('input', update);
        update();
    });
})();
</script>
@endsection