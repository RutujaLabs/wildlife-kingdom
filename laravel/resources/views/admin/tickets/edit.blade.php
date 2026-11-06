@extends('admin.layout')

@section('title', 'Edit Ticket')
@section('page-title', 'Edit Ticket')

@section('content')
@php
    $currentImage = !empty($ticket->image) ? asset(ltrim($ticket->image, '/')) : null;
@endphp

<style>
    .et-wrap { --et-green:#173B2A; --et-green-d:#0B2F22; --et-orange:#E8A15B; --et-orange-d:#D98E45; --et-line:#e7e0d2; --et-muted:#6f685c; }

    .et-head { display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap; margin-bottom:20px; }
    .et-head p { margin:0; color:var(--et-muted); font-size:15px; }
    .et-back { display:inline-flex; align-items:center; gap:8px; color:var(--et-green); font-weight:700; text-decoration:none; }
    .et-back:hover { color:var(--et-orange-d); }
    .et-back svg { width:18px; height:18px; }

    .et-layout { display:grid; grid-template-columns:minmax(0,1.7fr) minmax(300px,1fr); gap:24px; align-items:start; }

    .et-card { background:#fff; border:1px solid rgba(23,59,42,.08); border-radius:22px; padding:26px; box-shadow:0 8px 26px rgba(23,59,42,.08); }
    .et-card + .et-card { margin-top:24px; }
    .et-card-title { display:flex; align-items:center; gap:12px; margin:0 0 20px; font:700 20px/1.2 'Playfair Display', Georgia, serif; color:var(--et-green-d); }
    .et-card-title i { display:grid; place-items:center; width:38px; height:38px; border-radius:50%; background:var(--et-green); color:#fff; flex:0 0 38px; }
    .et-card-title i svg { width:20px; height:20px; }

    .et-grid { display:grid; grid-template-columns:repeat(2, minmax(0,1fr)); gap:18px; }
    .et-field { display:flex; flex-direction:column; gap:8px; margin-bottom:18px; }
    .et-grid .et-field { margin-bottom:0; }
    .et-field-wide { grid-column:1 / -1; }
    .et-field > label { font-weight:700; color:var(--et-green-d); font-size:15px; }
    .et-field > label small { font-weight:400; color:var(--et-muted); margin-left:6px; }

    .et-input, .et-textarea {
        width:100%; padding:13px 15px; border:1.5px solid var(--et-line); border-radius:14px; background:#fffdf8;
        font:inherit; color:#2B241D; transition:border-color .2s, box-shadow .2s, background .2s;
    }
    .et-textarea { min-height:140px; resize:vertical; line-height:1.55; }
    .et-input:focus, .et-textarea:focus { outline:0; border-color:var(--et-orange-d); background:#fff; box-shadow:0 0 0 4px rgba(232,161,91,.22); }
    .et-input.is-invalid, .et-textarea.is-invalid { border-color:#c4472f; }

    .et-prefix { position:relative; }
    .et-prefix span { position:absolute; left:15px; top:50%; transform:translateY(-50%); font-weight:700; color:var(--et-green); }
    .et-prefix .et-input { padding-left:34px; }

    .et-counter { align-self:flex-end; font-size:13px; color:var(--et-muted); }
    .et-error { color:#c4472f; font-size:13.5px; margin:0; }

    /* Status pills */
    .et-status { display:flex; gap:10px; }
    .et-status label { flex:1; cursor:pointer; }
    .et-status input { position:absolute; opacity:0; pointer-events:none; }
    .et-status span {
        display:flex; align-items:center; justify-content:center; gap:8px; padding:12px 14px;
        border:1.5px solid var(--et-line); border-radius:14px; background:#fffdf8; font-weight:700; color:var(--et-muted); transition:.2s;
    }
    .et-status span::before { content:""; width:10px; height:10px; border-radius:50%; background:#c9c2b3; }
    .et-status input:checked + span.on { border-color:#2f8f5b; background:#eaf6ef; color:#1d6b42; }
    .et-status input:checked + span.on::before { background:#2f8f5b; }
    .et-status input:checked + span.off { border-color:#c4472f; background:#fdeeea; color:#9b3420; }
    .et-status input:checked + span.off::before { background:#c4472f; }
    .et-status input:focus-visible + span { outline:3px solid rgba(232,161,91,.5); outline-offset:2px; }

    /* Image upload container */
    .et-drop {
        position:relative; display:flex; flex-direction:column; align-items:center; justify-content:center; text-align:center;
        min-height:230px; padding:18px; border:2px dashed #cdbf9f; border-radius:18px; background:#fffaf0;
        cursor:pointer; overflow:hidden; transition:border-color .2s, background .2s;
    }
    .et-drop:hover, .et-drop.is-drag { border-color:var(--et-orange-d); background:#fff3df; }
    .et-drop input[type=file] { position:absolute; inset:0; width:100%; height:100%; opacity:0; cursor:pointer; }
    .et-drop-icon { display:grid; place-items:center; width:58px; height:58px; margin-bottom:10px; border-radius:50%; background:#fff; color:var(--et-green); box-shadow:0 4px 12px rgba(23,59,42,.12); }
    .et-drop-icon svg { width:28px; height:28px; }
    .et-drop strong { color:var(--et-green-d); }
    .et-drop small { color:var(--et-muted); margin-top:4px; }
    .et-drop.has-image { padding:0; border-style:solid; border-color:var(--et-line); background:#e3ece6; min-height:0; aspect-ratio:4 / 3; }
    .et-drop.has-image .et-drop-empty { display:none; }
    .et-drop-img { display:none; position:absolute; inset:0; width:100%; height:100%; object-fit:cover; pointer-events:none; }
    .et-drop.has-image .et-drop-img { display:block; }
    .et-drop-hover { display:none; position:absolute; inset:0; align-items:center; justify-content:center; background:rgba(11,47,34,.55); color:#fff; font-weight:700; pointer-events:none; }
    .et-drop.has-image:hover .et-drop-hover { display:flex; }

    .et-img-actions { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-top:12px; font-size:13.5px; color:var(--et-muted); }
    .et-link-danger { background:none; border:0; padding:0; color:#c4472f; font-weight:700; cursor:pointer; font-size:13.5px; }
    .et-link-danger:hover { text-decoration:underline; }
    .et-hint { margin:12px 0 0; padding:10px 12px; border-radius:12px; background:#f3efe4; color:var(--et-muted); font-size:13px; line-height:1.5; }

    /* Live preview */
    .et-preview { display:flex; gap:14px; padding:10px; border:1px solid rgba(23,59,42,.1); border-radius:18px; background:#fff; box-shadow:0 6px 18px rgba(23,59,42,.1); }
    .et-preview-img { flex:0 0 38%; min-height:130px; border-radius:40px 10px 40px 10px; overflow:hidden; background:#E3ECE6 center/cover no-repeat; }
    .et-preview-body { flex:1; min-width:0; padding:6px 6px 4px 0; display:flex; flex-direction:column; }
    .et-preview-body h4 { margin:0; font:700 18px/1.2 'Playfair Display', Georgia, serif; color:var(--et-green-d); overflow-wrap:anywhere; }
    .et-preview-price { margin:8px 0 4px; font:700 22px/1.1 'Playfair Display', Georgia, serif; color:var(--et-green-d); }
    .et-preview-desc { margin:0 0 10px; font-size:13.5px; color:#2B241D; line-height:1.45; flex:1; overflow-wrap:anywhere; }
    .et-preview-btn { align-self:flex-start; padding:7px 16px; border-radius:30px; background:var(--et-orange); color:var(--et-green); font-weight:700; font-size:13px; }

    .et-actions { display:flex; gap:12px; flex-wrap:wrap; margin-top:6px; }
    .et-actions .btn { min-width:160px; justify-content:center; }

    @media (max-width: 1100px) { .et-layout { grid-template-columns:1fr; } }
    @media (max-width: 600px) {
        .et-grid { grid-template-columns:1fr; }
        .et-card { padding:20px; }
        .et-actions .btn { flex:1; }
        .et-preview { flex-direction:column; }
        .et-preview-img { flex:none; height:150px; }
    }
</style>

<div class="et-wrap">
    <div class="et-head">
        <p>Update ticket details, pricing and the image shown on the website.</p>
        <a class="et-back" href="{{ route('admin.tickets.index') }}">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 6l-6 6 6 6"/></svg>
            Back to Tickets
        </a>
    </div>

    <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}" enctype="multipart/form-data" id="ticketForm" novalidate>
        @csrf
        @method('PUT')
        <input type="hidden" name="remove_image" id="remove_image" value="0">

        <div class="et-layout">
            {{-- ============ LEFT: DETAILS ============ --}}
            <div>
                <div class="et-card">
                    <h3 class="et-card-title">
                        <i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9a2 2 0 0 0 0 6v3a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1v-3a2 2 0 0 1 0-6V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1z"/><path d="M13 5v14" stroke-dasharray="2 3"/></svg></i>
                        Ticket Details
                    </h3>

                    <div class="et-grid">
                        <div class="et-field">
                            <label for="ticket_type">Ticket Type</label>
                            <input class="et-input @error('ticket_type') is-invalid @enderror" id="ticket_type" name="ticket_type" type="text"
                                   value="{{ old('ticket_type', $ticket->ticket_type) }}" placeholder="e.g. Adult" required>
                            @error('ticket_type') <p class="et-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="et-field">
                            <label for="price">Price (₹ INR)</label>
                            <div class="et-prefix">
                                <span>₹</span>
                                <input class="et-input @error('price') is-invalid @enderror" id="price" name="price" type="number" step="0.01" min="0"
                                       value="{{ old('price', $ticket->price) }}" placeholder="0.00" required>
                            </div>
                            @error('price') <p class="et-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="et-field et-field-wide">
                            <label>Status</label>
                            @php $status = old('status', $ticket->status); @endphp
                            <div class="et-status">
                                <label>
                                    <input type="radio" name="status" value="active" {{ $status === 'active' ? 'checked' : '' }}>
                                    <span class="on">Active</span>
                                </label>
                                <label>
                                    <input type="radio" name="status" value="inactive" {{ $status === 'inactive' ? 'checked' : '' }}>
                                    <span class="off">Inactive</span>
                                </label>
                            </div>
                            @error('status') <p class="et-error">{{ $message }}</p> @enderror
                        </div>

                        <div class="et-field et-field-wide">
                            <label for="description">Description <small>(shown on the ticket card)</small></label>
                            <textarea class="et-textarea @error('description') is-invalid @enderror" id="description" name="description" maxlength="200"
                                      placeholder="Short line about who this ticket is for">{{ old('description', $ticket->description) }}</textarea>
                            <span class="et-counter"><span id="descCount">0</span> / 200</span>
                            @error('description') <p class="et-error">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="et-actions" style="margin-top:24px;">
                    <button type="submit" class="btn btn-primary">Update Ticket</button>
                    <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </div>

            {{-- ============ RIGHT: IMAGE + PREVIEW ============ --}}
            <aside>
                <div class="et-card">
                    <h3 class="et-card-title">
                        <i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="3"/><circle cx="9" cy="9" r="1.8"/><path d="M21 16l-5-5-9 9"/></svg></i>
                        Ticket Image
                    </h3>

                    <div class="et-drop {{ $currentImage ? 'has-image' : '' }}" id="dropZone">
                        <input type="file" name="image" id="image" accept="image/png,image/jpeg,image/webp">
                        <img class="et-drop-img" id="imgPreview" alt="Ticket image preview" src="{{ $currentImage }}">
                        <div class="et-drop-hover">Click or drop to change image</div>
                        <div class="et-drop-empty">
                            <div class="et-drop-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 16V4M7 9l5-5 5 5"/><path d="M4 16v3a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-3"/></svg></div>
                            <strong>Click to upload or drag &amp; drop</strong>
                            <small>JPG, PNG or WebP &middot; max 2&nbsp;MB</small>
                        </div>
                    </div>

                    <div class="et-img-actions">
                        <span id="fileName">{{ $currentImage ? 'Current image' : 'No image selected' }}</span>
                        <button type="button" class="et-link-danger" id="removeImg" {!! $currentImage ? '' : 'hidden' !!}>Remove</button>
                    </div>
                    <p class="et-error" id="imgError" hidden></p>
                    @error('image') <p class="et-error">{{ $message }}</p> @enderror

                    <p class="et-hint">Tip: use a portrait or square image (about 800&times;800&nbsp;px). Lion, elephant or giraffe close-ups look best.</p>
                </div>

                <div class="et-card">
                    <h3 class="et-card-title">
                        <i><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7S1 12 1 12z"/><circle cx="12" cy="12" r="3"/></svg></i>
                        Live Preview
                    </h3>
                    <div class="et-preview">
                        <div class="et-preview-img" id="pvImg" @if($currentImage) style="background-image:url('{{ $currentImage }}')" @endif></div>
                        <div class="et-preview-body">
                            <h4 id="pvType">{{ old('ticket_type', $ticket->ticket_type) }}</h4>
                            <div class="et-preview-price" id="pvPrice">₹0.00</div>
                            <p class="et-preview-desc" id="pvDesc">{{ old('description', $ticket->description) }}</p>
                            <span class="et-preview-btn">Book Now &rarr;</span>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </form>
</div>

<script>
(function () {
    var MAX = 2 * 1024 * 1024;
    var OK = ['image/jpeg', 'image/png', 'image/webp'];

    var $ = function (id) { return document.getElementById(id); };
    var type = $('ticket_type'), price = $('price'), desc = $('description');
    var file = $('image'), zone = $('dropZone'), img = $('imgPreview');
    var pvImg = $('pvImg'), err = $('imgError'), nameEl = $('fileName');
    var removeBtn = $('removeImg'), removeInput = $('remove_image');

    function money(v) { var n = parseFloat(v); return '\u20B9' + (isNaN(n) ? 0 : n).toFixed(2); }

    function sync() {
        $('pvType').textContent = type.value || 'Ticket type';
        $('pvPrice').textContent = money(price.value);
        $('pvDesc').textContent = desc.value || 'Description';
        $('descCount').textContent = desc.value.length;
    }
    [type, price, desc].forEach(function (el) { el.addEventListener('input', sync); });
    sync();

    function showError(msg) { err.textContent = msg; err.hidden = !msg; }

    function setImage(src, label) {
        img.src = src;
        pvImg.style.backgroundImage = "url('" + src + "')";
        zone.classList.add('has-image');
        nameEl.textContent = label;
        removeBtn.hidden = false;
        removeInput.value = '0';
    }

    function handleFile(f) {
        showError('');
        if (!f) return;
        if (OK.indexOf(f.type) === -1) { showError('Only JPG, PNG or WebP images are allowed.'); file.value = ''; return; }
        if (f.size > MAX) { showError('Image is too large. Maximum size is 2 MB.'); file.value = ''; return; }
        var reader = new FileReader();
        reader.onload = function (e) { setImage(e.target.result, f.name); };
        reader.readAsDataURL(f);
    }

    file.addEventListener('change', function () { handleFile(file.files[0]); });

    ['dragenter', 'dragover'].forEach(function (ev) {
        zone.addEventListener(ev, function (e) { e.preventDefault(); zone.classList.add('is-drag'); });
    });
    ['dragleave', 'drop'].forEach(function (ev) {
        zone.addEventListener(ev, function () { zone.classList.remove('is-drag'); });
    });

    removeBtn.addEventListener('click', function () {
        file.value = '';
        img.removeAttribute('src');
        pvImg.style.backgroundImage = '';
        zone.classList.remove('has-image');
        nameEl.textContent = 'No image selected';
        removeBtn.hidden = true;
        removeInput.value = '1';
        showError('');
    });
})();
</script>
@endsection