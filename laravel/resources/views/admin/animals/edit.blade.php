@extends('admin.layout')

@section('title', 'Animal')
@section('page-title', 'Animal')

@push('styles')
<style>
    .edit-head { display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; margin-bottom: 22px; }
    .edit-head h2 { margin: 0; font-size: 1.25rem; font-weight: 600; color: var(--forest); }
    .edit-head p { margin: 4px 0 0; color: var(--muted); font-size: .92rem; }
    .back-link { display: inline-flex; align-items: center; gap: 6px; color: var(--forest); text-decoration: none; font-weight: 600; font-size: .92rem; }
    .back-link svg { width: 16px; height: 16px; fill: none; stroke: currentColor; stroke-width: 2.2; stroke-linecap: round; stroke-linejoin: round; }
    .back-link:hover { text-decoration: underline; }

    .edit-layout { display: grid; grid-template-columns: minmax(0, 1fr) 340px; gap: 24px; align-items: start; }
    .edit-main { display: flex; flex-direction: column; gap: 24px; }
    .edit-side { display: flex; flex-direction: column; gap: 24px; position: sticky; top: 24px; }

    .section-title { margin: 0 0 4px; font-family: 'Playfair Display', serif; font-size: 1.15rem; color: var(--forest); }
    .section-sub { margin: 0 0 20px; color: var(--muted); font-size: .88rem; }
    .form-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 18px; }
    .form-grid .full { grid-column: 1 / -1; }
    .edit-layout .field { margin-bottom: 18px; }
    .edit-layout .field:last-child { margin-bottom: 0; }
    .edit-layout label { font-size: .9rem; color: #3d4a48; }
    .req { color: #a12626; }
    .hint { color: var(--muted); font-size: .8rem; margin-top: 6px; }
    .field-error { color: #a12626; font-size: .82rem; margin-top: 6px; }
    .is-invalid { border-color: #d46a6a !important; }
    .edit-layout select { appearance: none; background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236c6a69' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 14px center; padding-right: 38px; }
    .edit-layout textarea { min-height: 170px; resize: vertical; line-height: 1.55; }

    /* Image card */
    .img-frame { position: relative; width: 100%; aspect-ratio: 3 / 2; border-radius: 14px; overflow: hidden; background: #f3eee5; border: 2px dashed #d9cfba; display: grid; place-items: center; transition: border-color .2s, background .2s; cursor: pointer; }
    .img-frame.has-image { border-style: solid; border-color: var(--line); }
    .img-frame.drag { border-color: var(--gold); background: #fcefdc; }
    .img-frame img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .img-placeholder { text-align: center; color: #9a8f78; padding: 16px; }
    .img-placeholder svg { width: 40px; height: 40px; fill: none; stroke: currentColor; stroke-width: 1.5; stroke-linecap: round; stroke-linejoin: round; margin-bottom: 8px; }
    .img-placeholder div { font-size: .88rem; line-height: 1.4; }
    .img-tag { position: absolute; left: 10px; top: 10px; background: rgba(11,47,34,.85); color: #fff; font-size: .74rem; font-weight: 600; padding: 4px 12px; border-radius: 999px; z-index: 2; }
    .img-tag.new { background: #14633f; }
    .img-actions { display: flex; align-items: center; gap: 10px; margin-top: 14px; flex-wrap: wrap; }
    .img-actions .btn { margin: 0; cursor: pointer; }
    .file-input-hidden { position: absolute; width: 1px; height: 1px; opacity: 0; overflow: hidden; pointer-events: none; }
    .img-meta { font-size: .82rem; color: var(--muted); margin-top: 10px; word-break: break-word; }
    .img-check { margin-top: 10px; font-size: .82rem; padding: 9px 12px; border-radius: 10px; display: none; line-height: 1.4; }
    .img-check.ok { display: block; background: #e3f1e9; color: #14633f; }
    .img-check.warn { display: block; background: #fcefdc; color: #8a5413; }

    .size-guide { margin-top: 16px; padding: 14px 16px; background: #faf7f2; border: 1px solid var(--line); border-radius: 12px; font-size: .84rem; color: #4f5654; }
    .size-guide strong { color: var(--forest); }
    .size-guide ul { margin: 8px 0 0; padding-left: 18px; line-height: 1.6; }

    /* Publish card */
    .switch-row { display: flex; justify-content: space-between; align-items: center; gap: 14px; padding: 14px 0 0; margin-top: 4px; border-top: 1px solid var(--line); }
    .switch-row .t { font-weight: 600; font-size: .92rem; color: #3d4a48; }
    .switch-row .d { color: var(--muted); font-size: .8rem; margin-top: 2px; }
    .switch { position: relative; width: 48px; height: 28px; flex-shrink: 0; }
    .switch input { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; margin: 0; cursor: pointer; z-index: 2; }
    .switch .track { position: absolute; inset: 0; background: #d9d1c2; border-radius: 999px; transition: background .2s; }
    .switch .track::after { content: ""; position: absolute; left: 3px; top: 3px; width: 22px; height: 22px; background: #fff; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,.2); transition: transform .2s; }
    .switch input:checked + .track { background: var(--forest); }
    .switch input:checked + .track::after { transform: translateX(20px); }
    .switch input:focus-visible + .track { outline: 2px solid var(--gold); outline-offset: 2px; }

    .form-actions { display: flex; gap: 12px; margin-top: 22px; }
    .form-actions .btn { flex: 1; padding: 13px 18px; }

    @media (max-width: 1000px) {
        .edit-layout { grid-template-columns: 1fr; }
        .edit-side { position: static; order: -1; }
    }
    @media (max-width: 560px) {
        .form-grid { grid-template-columns: 1fr; }
    }
</style>
@endpush

@section('content')
@php
    if (!function_exists('wk_animal_image_url')) {
        function wk_animal_image_url($animal) {
            $raw = null;
            foreach (['image', 'image_path', 'image_url', 'photo', 'thumbnail', 'featured_image'] as $col) {
                $v = $animal->getAttribute($col);
                if (!empty($v) && is_string($v)) { $raw = $v; break; }
            }
            if (!$raw) return null;
            if (preg_match('#^https?://#i', $raw)) return $raw;

            $path = ltrim(str_replace('\\', '/', $raw), '/');
            $candidates = [
                [public_path($path), $path],
                [public_path('storage/' . $path), 'storage/' . $path],
                [storage_path('app/public/' . $path), 'storage/' . $path],
            ];
            if (strpos($path, 'public/') === 0) {
                $t = substr($path, 7);
                $candidates[] = [storage_path('app/public/' . $t), 'storage/' . $t];
                $candidates[] = [public_path($t), $t];
            }
            if (strpos($path, 'storage/') === 0) {
                $t = substr($path, 8);
                $candidates[] = [storage_path('app/public/' . $t), $path];
            }
            foreach ($candidates as $c) {
                if (is_file($c[0])) return asset($c[1]);
            }
            return asset(strpos($path, 'storage/') === 0 ? $path : 'storage/' . $path);
        }
    }
@endphp
@php $imageUrl = wk_animal_image_url($animal); @endphp

<div class="edit-head">
    <div>
        <h2>{{ $animal->name }}</h2>
        <p>Changes are saved to your website as soon as you click "Update animal".</p>
    </div>
    <a href="{{ route('admin.animals.index') }}" class="back-link">
        <svg viewBox="0 0 24 24"><path d="m15 18-6-6 6-6"/></svg>
        Back to animals
    </a>
</div>

<form method="POST" action="{{ route('admin.animals.update', $animal) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="edit-layout">
        <div class="edit-main">
            <div class="panel">
                <h3 class="section-title">Basic details</h3>
                <p class="section-sub">The name and description visitors see on the website.</p>
                <div class="form-grid">
                    <div class="field">
                        <label for="name">Name <span class="req">*</span></label>
                        <input id="name" name="name" type="text" value="{{ old('name', $animal->name) }}" class="@error('name') is-invalid @enderror" required>
                        @error('name')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="scientific_name">Scientific name</label>
                        <input id="scientific_name" name="scientific_name" type="text" value="{{ old('scientific_name', $animal->scientific_name) }}" class="@error('scientific_name') is-invalid @enderror">
                        @error('scientific_name')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field full">
                        <label for="description">Description <span class="req">*</span></label>
                        <textarea id="description" name="description" class="@error('description') is-invalid @enderror" required>{{ old('description', $animal->description) }}</textarea>
                        @error('description')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="panel">
                <h3 class="section-title">Classification</h3>
                <p class="section-sub">Helps visitors filter and find this animal.</p>
                <div class="form-grid">
                    <div class="field">
                        <label for="category">Category <span class="req">*</span></label>
                        <select id="category" name="category" class="@error('category') is-invalid @enderror" required>
                            @foreach(['Mammals','Birds','Reptiles','Amphibians','Big Cats','Primates','Herbivores','Other'] as $category)
                                <option value="{{ $category }}" {{ old('category', $animal->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                        @error('category')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="habitat_id">Habitat</label>
                        <select id="habitat_id" name="habitat_id" class="@error('habitat_id') is-invalid @enderror">
                            <option value="">Select habitat</option>
                            @foreach($habitats as $habitat)
                                <option value="{{ $habitat->id }}" {{ old('habitat_id', $animal->habitat_id) == $habitat->id ? 'selected' : '' }}>{{ $habitat->name }}</option>
                            @endforeach
                        </select>
                        @error('habitat_id')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field full">
                        <label for="conservation_status">Conservation status <span class="req">*</span></label>
                        <input id="conservation_status" name="conservation_status" type="text" value="{{ old('conservation_status', $animal->conservation_status) }}" class="@error('conservation_status') is-invalid @enderror" required>
                        <div class="hint">For example: Vulnerable, Endangered, Least Concern.</div>
                        @error('conservation_status')<div class="field-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="edit-side">
            <div class="panel">
                <h3 class="section-title">Animal photo</h3>
                <p class="section-sub">This image appears on the website.</p>

                <label for="image" class="img-frame {{ $imageUrl ? 'has-image' : '' }}" id="imgFrame" style="margin:0;">
                    <span class="img-tag" id="imgTag" style="{{ $imageUrl ? '' : 'display:none;' }}">Current photo</span>
                    @if($imageUrl)
                        <img src="{{ $imageUrl }}" alt="{{ $animal->name }}" id="imgPreview" onerror="this.style.display='none'; document.getElementById('imgPlaceholder').style.display='block'; document.getElementById('imgFrame').classList.remove('has-image');">
                    @else
                        <img src="" alt="" id="imgPreview" style="display:none;">
                    @endif
                    <div class="img-placeholder" id="imgPlaceholder" style="{{ $imageUrl ? 'display:none;' : '' }}">
                        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/></svg>
                        <div>No photo yet.<br>Click or drop an image here.</div>
                    </div>
                </label>

                <input id="image" name="image" type="file" accept="image/*" class="file-input-hidden">

                <div class="img-actions">
                    <label for="image" class="btn btn-outline btn-sm" style="padding:9px 18px;">{{ $imageUrl ? 'Replace photo' : 'Choose photo' }}</label>
                    <button type="button" class="btn btn-sm" id="resetImage" style="display:none; padding:9px 18px; background:#f0ece4; color:#4f5654;">Undo change</button>
                </div>
                <div class="img-meta" id="imgMeta"></div>
                <div class="img-check" id="imgCheck"></div>
                @error('image')<div class="field-error">{{ $message }}</div>@enderror

                <div class="size-guide">
                    <strong>Best image size</strong>
                    <ul>
                        <li>1200 × 800 px (3:2, landscape)</li>
                        <li>JPG, PNG or WebP, under 2 MB</li>
                        <li>Keep the animal in the centre, as the edges can be cropped</li>
                    </ul>
                </div>
            </div>

            <div class="panel">
                <h3 class="section-title">Visibility</h3>
                <p class="section-sub">Control where this animal shows up.</p>
                <div class="field">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="published" {{ old('status', $animal->status) == 'published' ? 'selected' : '' }}>Published (visible on website)</option>
                        <option value="draft" {{ old('status', $animal->status) == 'draft' ? 'selected' : '' }}>Draft (hidden)</option>
                    </select>
                </div>
                <div class="switch-row">
                    <div>
                        <div class="t">Featured animal</div>
                        <div class="d">Highlight on the home page</div>
                    </div>
                    <div class="switch">
                        <input name="is_featured" type="checkbox" value="1" {{ old('is_featured', $animal->is_featured) ? 'checked' : '' }} aria-label="Featured animal">
                        <span class="track"></span>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Update animal</button>
                    <a href="{{ route('admin.animals.index') }}" class="btn btn-outline">Cancel</a>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    (function () {
        var input = document.getElementById('image');
        var frame = document.getElementById('imgFrame');
        var preview = document.getElementById('imgPreview');
        var placeholder = document.getElementById('imgPlaceholder');
        var tag = document.getElementById('imgTag');
        var meta = document.getElementById('imgMeta');
        var check = document.getElementById('imgCheck');
        var resetBtn = document.getElementById('resetImage');

        var original = {
            src: preview.getAttribute('src') || '',
            hasImage: frame.classList.contains('has-image')
        };

        function showCheck(type, text) {
            check.className = 'img-check ' + type;
            check.textContent = text;
        }

        function restore() {
            input.value = '';
            meta.textContent = '';
            check.className = 'img-check';
            check.textContent = '';
            resetBtn.style.display = 'none';
            if (original.hasImage) {
                preview.src = original.src;
                preview.style.display = 'block';
                placeholder.style.display = 'none';
                frame.classList.add('has-image');
                tag.textContent = 'Current photo';
                tag.className = 'img-tag';
                tag.style.display = '';
            } else {
                preview.removeAttribute('src');
                preview.style.display = 'none';
                placeholder.style.display = 'block';
                frame.classList.remove('has-image');
                tag.style.display = 'none';
            }
        }

        function handleFile(file) {
            if (!file) { restore(); return; }
            if (!file.type || file.type.indexOf('image/') !== 0) {
                restore();
                showCheck('warn', 'Please choose an image file (JPG, PNG or WebP).');
                return;
            }
            var url = URL.createObjectURL(file);
            preview.src = url;
            preview.style.display = 'block';
            placeholder.style.display = 'none';
            frame.classList.add('has-image');
            tag.textContent = 'New photo (not saved yet)';
            tag.className = 'img-tag new';
            tag.style.display = '';
            resetBtn.style.display = 'inline-flex';

            var mb = file.size / (1024 * 1024);
            meta.textContent = file.name + ' · ' + mb.toFixed(2) + ' MB';

            var probe = new Image();
            probe.onload = function () {
                var w = probe.naturalWidth, h = probe.naturalHeight;
                var ratio = w / h;
                meta.textContent = file.name + ' · ' + w + ' × ' + h + ' px · ' + mb.toFixed(2) + ' MB';
                var problems = [];
                if (w < 800) problems.push('The image is small and may look blurry. Use at least 1200 px wide.');
                if (mb > 2) problems.push('The file is over 2 MB and may load slowly or be rejected.');
                if (ratio < 1.2 || ratio > 1.8) problems.push('This shape will be cropped. A 3:2 landscape photo fits best.');
                if (problems.length) {
                    showCheck('warn', problems.join(' '));
                } else {
                    showCheck('ok', 'Looks good. Click "Update animal" to save it.');
                }
            };
            probe.src = url;
        }

        input.addEventListener('change', function () { handleFile(input.files[0]); });
        resetBtn.addEventListener('click', restore);

        ['dragenter', 'dragover'].forEach(function (evt) {
            frame.addEventListener(evt, function (e) { e.preventDefault(); frame.classList.add('drag'); });
        });
        ['dragleave', 'drop'].forEach(function (evt) {
            frame.addEventListener(evt, function (e) { e.preventDefault(); frame.classList.remove('drag'); });
        });
        frame.addEventListener('drop', function (e) {
            if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                handleFile(input.files[0]);
            }
        });
    })();
</script>
@endsection