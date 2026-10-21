@extends('admin.layout')

@section('title', 'Animal')
@section('page-title', 'Animal')

@push('styles')
<style>
    /* Everything is scoped under .ae so the admin layout / sidebar is never affected */
    .ae { --ae-g: var(--forest, #0e4a34); --ae-gold: var(--gold, #c98a2b); --ae-mut: var(--muted, #6b7370); --ae-ln: var(--line, #e6e0d3); --ae-bad: #b3261e; color: #1f2b28; }
    .ae *, .ae *::before, .ae *::after { box-sizing: border-box; }
    .ae [hidden] { display: none !important; }
    .ae .ico { width: 18px; height: 18px; flex-shrink: 0; fill: none; stroke: currentColor; stroke-width: 2; stroke-linecap: round; stroke-linejoin: round; }

    .ae-crumbs { display: flex; gap: 8px; align-items: center; font-size: .88rem; color: var(--ae-mut); margin-bottom: 10px; }
    .ae-crumbs a { color: var(--ae-g); text-decoration: none; font-weight: 600; }
    .ae-crumbs a:hover { text-decoration: underline; }

    .ae-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; }
    .ae-head h1 { margin: 0; font-family: 'Playfair Display', serif; font-size: 2.1rem; line-height: 1.15; color: var(--ae-g); display: flex; align-items: center; gap: 14px; flex-wrap: wrap; }
    .ae-head p { margin: 6px 0 0; color: var(--ae-mut); font-size: .95rem; }
    .ae-head-actions { display: flex; gap: 12px; flex-wrap: wrap; }

    .ae-pill { display: inline-flex; align-items: center; gap: 7px; font-family: inherit; font-size: .78rem; font-weight: 600; padding: 5px 12px; border-radius: 999px; background: #e3f1e9; color: #14633f; }
    .ae-pill i { width: 8px; height: 8px; border-radius: 50%; background: currentColor; display: inline-block; }
    .ae-pill.draft { background: #f3ecdc; color: #8a5413; }

    .ae-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 22px; border-radius: 999px; font: inherit; font-weight: 600; font-size: .92rem; cursor: pointer; text-decoration: none; border: 1px solid transparent; line-height: 1.2; transition: background .15s, box-shadow .15s; }
    .ae-btn-primary { background: var(--ae-g); color: #fff; }
    .ae-btn-primary:hover { box-shadow: 0 6px 16px rgba(14,74,52,.28); }
    .ae-btn-primary:disabled { opacity: .65; cursor: wait; }
    .ae-btn-ghost { background: #fff; color: var(--ae-g); border-color: #cfd8d2; }
    .ae-btn-ghost:hover { background: #f4f8f5; }
    .ae-btn.ae-sm { padding: 8px 14px; font-size: .84rem; }

    .ae-alert { background: #fdecea; border: 1px solid #f3b9b3; color: #7a1a13; border-radius: 12px; padding: 14px 18px; margin-bottom: 18px; font-size: .9rem; }
    .ae-alert strong { display: block; margin-bottom: 6px; }
    .ae-alert ul { margin: 0; padding-left: 18px; line-height: 1.6; }

    .ae-tabs { display: flex; gap: 4px; background: #fff; border: 1px solid var(--ae-ln); border-radius: 14px; padding: 6px; margin-bottom: 20px; overflow-x: auto; }
    .ae-tab { position: relative; display: inline-flex; align-items: center; gap: 9px; padding: 11px 18px; border: 0; background: transparent; border-radius: 10px; font: inherit; font-size: .9rem; font-weight: 600; color: var(--ae-mut); cursor: pointer; white-space: nowrap; }
    .ae-tab:hover { background: #f6f4ee; }
    .ae-tab.active { color: var(--ae-g); background: #eef5f0; box-shadow: inset 0 -2px 0 var(--ae-g); }
    .ae-tab.has-error::after { content: ""; position: absolute; top: 8px; right: 8px; width: 8px; height: 8px; border-radius: 50%; background: var(--ae-bad); }

    .ae-grid { display: grid; grid-template-columns: minmax(0, 1fr) 380px; gap: 24px; align-items: start; }
    .ae-stack { display: flex; flex-direction: column; gap: 24px; min-width: 0; }
    .ae-pane { display: flex; flex-direction: column; gap: 24px; }

    .ae-card { background: #fff; border: 1px solid var(--ae-ln); border-radius: 18px; padding: 24px; box-shadow: 0 2px 10px rgba(30,40,35,.04); min-width: 0; }
    .ae-card-t { display: flex; align-items: center; gap: 10px; margin: 0 0 4px; font-family: 'Playfair Display', serif; font-size: 1.2rem; color: var(--ae-g); }
    .ae-card-top { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 16px; }
    .ae-card-top .ae-card-t { margin: 0; }
    .ae-card-s { margin: 0 0 20px; color: var(--ae-mut); font-size: .88rem; }

    .ae-fg { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 20px; }
    .ae-fg .full { grid-column: 1 / -1; }
    .ae-f { margin-bottom: 18px; min-width: 0; }
    .ae-f > label.ae-lbl { display: block; margin: 0 0 7px; font-weight: 600; font-size: .86rem; color: #2b3a36; }
    .ae-req { color: var(--ae-bad); }
    .ae-hint { color: var(--ae-mut); font-size: .8rem; margin-top: 6px; line-height: 1.45; }
    .ae-count { text-align: right; color: var(--ae-mut); font-size: .78rem; margin-top: 4px; }
    .ae-err { color: var(--ae-bad); font-size: .82rem; margin-top: 6px; }

    .ae input[type=text], .ae input[type=url], .ae input[type=number], .ae select, .ae textarea {
        width: 100%; max-width: 100%; padding: 11px 14px; border: 1px solid #e0dacd; border-radius: 10px; background: #fff;
        font: inherit; font-size: .92rem; color: #1f2b28; margin: 0; outline: none; transition: border-color .15s, box-shadow .15s;
    }
    .ae input:focus, .ae select:focus, .ae textarea:focus { border-color: var(--ae-g); box-shadow: 0 0 0 3px rgba(14,74,52,.12); }
    .ae .is-invalid { border-color: #d46a6a !important; }
    .ae textarea { min-height: 110px; resize: vertical; line-height: 1.55; }
    .ae select { appearance: none; -webkit-appearance: none; padding-right: 38px; background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='%236c6a69' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 14px center; }

    /* Rich text editor */
    .ae-rte { border: 1px solid #e0dacd; border-radius: 12px; overflow: hidden; background: #fff; }
    .ae-rte:focus-within { border-color: var(--ae-g); box-shadow: 0 0 0 3px rgba(14,74,52,.12); }
    .ae-rte.is-invalid { border-color: #d46a6a; }
    .ae-tb { display: flex; gap: 4px; padding: 8px; border-bottom: 1px solid #ece7db; background: #faf9f5; flex-wrap: wrap; }
    .ae-tb button { width: 34px; height: 34px; border: 0; border-radius: 8px; background: transparent; color: #3d4a48; cursor: pointer; display: grid; place-items: center; font: inherit; font-weight: 700; }
    .ae-tb button:hover { background: #ece8dc; }
    .ae-tb button.on { background: #dfeee5; color: var(--ae-g); }
    .ae-tb .sep { width: 1px; background: #e4dfd2; margin: 4px 4px; }
    .ae-editor { min-height: 190px; padding: 14px 16px; outline: none; line-height: 1.65; font-size: .93rem; overflow-wrap: anywhere; }
    .ae-editor:empty::before { content: attr(data-placeholder); color: #a29d90; }
    .ae-editor ul, .ae-editor ol { margin: 6px 0 6px 22px; padding: 0; }
    .ae-editor a { color: var(--ae-g); text-decoration: underline; }

    /* Switch */
    .ae-sw { display: flex; align-items: center; gap: 12px; padding-top: 4px; }
    .ae-switch { position: relative; width: 48px; height: 28px; flex-shrink: 0; }
    .ae-switch input { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; margin: 0; cursor: pointer; z-index: 2; }
    .ae-switch .track { position: absolute; inset: 0; background: #d9d1c2; border-radius: 999px; transition: background .2s; }
    .ae-switch .track::after { content: ""; position: absolute; left: 3px; top: 3px; width: 22px; height: 22px; background: #fff; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,.2); transition: transform .2s; }
    .ae-switch input:checked + .track { background: var(--ae-g); }
    .ae-switch input:checked + .track::after { transform: translateX(20px); }
    .ae-switch input:focus-visible + .track { outline: 2px solid var(--ae-gold); outline-offset: 2px; }
    .ae-sw-d { color: var(--ae-mut); font-size: .8rem; line-height: 1.35; }
    .ae-sw-l { font-weight: 600; font-size: .86rem; color: #2b3a36; margin-bottom: 7px; }

    /* Main image */
    .ae-hero { position: relative; display: grid; place-items: center; width: 100%; aspect-ratio: 3 / 2; border-radius: 14px; overflow: hidden; background: #f3eee5; border: 2px dashed #d9cfba; cursor: pointer; margin: 0 0 16px; }
    .ae-hero.has { border-style: solid; border-color: var(--ae-ln); }
    .ae-hero.drag { border-color: var(--ae-gold); background: #fcefdc; }
    .ae-hero img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .ae-hero-empty { text-align: center; color: #9a8f78; padding: 16px; font-size: .88rem; line-height: 1.45; }
    .ae-tag { position: absolute; right: 10px; top: 10px; z-index: 2; background: rgba(11,47,34,.88); color: #fff; font-size: .74rem; font-weight: 600; padding: 5px 12px; border-radius: 999px; }
    .ae-tag.new { background: #14633f; }
    .ae-hero-name { font-family: 'Playfair Display', serif; font-size: 1.3rem; color: #1f2b28; font-weight: 700; margin-bottom: 8px; }
    .ae-chip { display: inline-flex; align-items: center; gap: 6px; background: #e6f1ea; color: #14633f; font-size: .78rem; font-weight: 600; padding: 4px 12px; border-radius: 999px; }
    .ae-hero-short { margin: 10px 0 0; color: #4f5654; font-size: .88rem; line-height: 1.5; }
    .ae-file-hidden { position: absolute; width: 1px; height: 1px; opacity: 0; overflow: hidden; pointer-events: none; }
    .ae-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 14px; }
    .ae-meta { font-size: .8rem; color: var(--ae-mut); margin-top: 10px; word-break: break-word; }
    .ae-check { display: none; margin-top: 10px; font-size: .82rem; padding: 9px 12px; border-radius: 10px; line-height: 1.4; }
    .ae-check.ok { display: block; background: #e3f1e9; color: #14633f; }
    .ae-check.warn { display: block; background: #fcefdc; color: #8a5413; }

    /* Gallery */
    .ae-gal { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 12px; }
    .ae-gal-empty { grid-column: 1 / -1; padding: 18px; text-align: center; color: var(--ae-mut); font-size: .85rem; border: 1px dashed #d9cfba; border-radius: 12px; }
    .ae-tile { position: relative; min-width: 0; }
    .ae-tile img { display: block; width: 100%; aspect-ratio: 4 / 3; object-fit: cover; border-radius: 10px; background: #f3eee5; }
    .ae-x { position: absolute; top: 6px; right: 6px; width: 22px; height: 22px; border: 0; border-radius: 50%; background: rgba(255,255,255,.95); color: #333; font-size: 15px; line-height: 1; cursor: pointer; display: grid; place-items: center; box-shadow: 0 1px 4px rgba(0,0,0,.3); padding: 0; }
    .ae-x:hover { background: #fff; color: var(--ae-bad); }
    .ae-cap { display: block; margin-top: 6px; font-size: .76rem; color: #4f5654; text-align: center; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; min-height: 1.2em; }
    .ae-tile.is-removed img { opacity: .3; filter: grayscale(1); }
    .ae-tile.is-removed .ae-cap { color: var(--ae-bad); text-decoration: line-through; }
    .ae-tile.is-new::after { content: "NEW"; position: absolute; left: 6px; top: 6px; background: #14633f; color: #fff; font-size: .64rem; font-weight: 700; padding: 2px 7px; border-radius: 999px; }

    /* Related */
    .ae-chips { display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; }
    .ae-rel { display: inline-flex; align-items: center; gap: 8px; border: 1px solid var(--ae-ln); background: #fff; border-radius: 999px; padding: 4px 8px 4px 4px; font-size: .8rem; max-width: 100%; }
    .ae-rel .av { width: 26px; height: 26px; border-radius: 50%; object-fit: cover; background: #e6f1ea; color: var(--ae-g); display: grid; place-items: center; font-weight: 700; font-size: .75rem; overflow: hidden; flex-shrink: 0; }
    .ae-rel span.n { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ae-rel button { border: 0; background: transparent; cursor: pointer; font-size: 15px; line-height: 1; color: #777; padding: 0 4px; }
    .ae-rel button:hover { color: var(--ae-bad); }
    .ae-link { color: var(--ae-g); font-size: .84rem; font-weight: 600; text-decoration: none; }
    .ae-link:hover { text-decoration: underline; }

    /* Image detail rows (Images & SEO) */
    .ae-irow { display: grid; grid-template-columns: 90px minmax(0, 1fr); gap: 16px; padding: 16px 0; border-top: 1px solid var(--ae-ln); }
    .ae-irow:first-of-type { border-top: 0; padding-top: 0; }
    .ae-irow img { width: 90px; height: 68px; object-fit: cover; border-radius: 10px; background: #f3eee5; }
    .ae-irow .fields { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 16px; }
    .ae-irow .fields .ae-f { margin-bottom: 12px; }
    .ae-irow.is-removed { opacity: .5; }
    .ae-irow-actions { grid-column: 2; display: flex; gap: 10px; }
    .ae-linkbtn { background: none; border: 0; padding: 0; font: inherit; font-size: .84rem; font-weight: 600; color: var(--ae-bad); cursor: pointer; }

    /* FAQ */
    .ae-faq { border: 1px solid var(--ae-ln); border-radius: 14px; padding: 18px; margin-bottom: 14px; background: #fcfbf8; }
    .ae-faq.is-removed { opacity: .5; }
    .ae-faq.is-removed input, .ae-faq.is-removed textarea { text-decoration: line-through; }
    .ae-faq-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; font-weight: 600; font-size: .86rem; color: #2b3a36; }

    .ae-note { display: flex; align-items: center; gap: 10px; background: #eef6f1; border: 1px solid #d3e7da; color: #1d5c3f; border-radius: 12px; padding: 13px 16px; font-size: .86rem; }
    .ae-actions { display: flex; gap: 12px; justify-content: flex-end; flex-wrap: wrap; margin-top: 4px; }

    @media (max-width: 1100px) { .ae-grid { grid-template-columns: 1fr; } }
    @media (max-width: 640px) {
        .ae-fg, .ae-irow .fields { grid-template-columns: 1fr; }
        .ae-irow { grid-template-columns: 1fr; }
        .ae-irow-actions { grid-column: 1; }
        .ae-head h1 { font-size: 1.6rem; }
    }
</style>
@endpush

@section('content')
@php
    if (!function_exists('wk_ae_media_url')) {
        function wk_ae_media_url($file, $dir)
        {
            if (empty($file) || !is_string($file)) { return null; }
            if (preg_match('#^(https?:)?//#i', $file)) { return $file; }

            $base = basename(str_replace('\\', '/', $file));
            $rel  = trim($dir, '/') . '/' . $base;

            try {
                $cfg = config('filesystems.disks.legacy_uploads', []);

                if (!empty($cfg['url'])) {
                    return rtrim($cfg['url'], '/') . '/' . $rel;
                }

                if (!empty($cfg['root'])) {
                    $root = realpath($cfg['root']);
                    $pub  = realpath(public_path());
                    if ($root && $pub) {
                        $root = str_replace('\\', '/', $root);
                        $pub  = str_replace('\\', '/', $pub);
                        if (strpos($root, $pub) === 0) {
                            $sub = trim(substr($root, strlen($pub)), '/');
                            return asset(($sub !== '' ? $sub . '/' : '') . $rel);
                        }
                    }
                }
            } catch (\Throwable $e) {
                // fall through to default below
            }

            return asset('uploads/' . $rel);
        }
    }

    $statusVal  = old('status', $animal->status ?: 'published');
    $mainUrl    = wk_ae_media_url($animal->image, 'animals');
    $categories = ['Mammals', 'Birds', 'Reptiles', 'Amphibians', 'Big Cats', 'Primates', 'Herbivores', 'Other'];
    $catVal     = old('category', $animal->category);
    if ($catVal && !in_array($catVal, $categories, true)) { array_unshift($categories, $catVal); }

    $taxonomy = ['kingdom' => 'Kingdom', 'animal_class' => 'Class', 'animal_order' => 'Order', 'family' => 'Family', 'genus' => 'Genus', 'species' => 'Species'];
    $rangeAreas = ['geographical_range' => 'Geographical range', 'native_countries' => 'Native countries', 'distribution' => 'Distribution'];
    $factInputs = ['diet' => 'Diet', 'lifespan' => 'Lifespan', 'average_weight' => 'Average weight', 'average_height_length' => 'Average height / length', 'speed' => 'Speed'];
    $factAreas = [
        'behaviour' => 'Behaviour', 'reproduction' => 'Reproduction', 'ecological_role' => 'Ecological role',
        'population_information' => 'Population information', 'major_threats' => 'Major threats',
        'conservation_efforts' => 'Conservation efforts', 'fun_fact' => 'Fun fact',
    ];
    $imgFields = ['alt_text' => 'Alt text', 'caption' => 'Caption', 'credit' => 'Image credit', 'source' => 'Image source'];

    $relPool = $allAnimals->filter(fn ($a) => $a->id !== $animal->id)
        ->map(fn ($a) => ['id' => $a->id, 'name' => $a->name, 'img' => wk_ae_media_url($a->image, 'animals')])
        ->values();
    $relSelected = collect(old('related_animals', $animal->relatedAnimals->modelKeys()))->map(fn ($v) => (int) $v)->values();
@endphp

<div class="ae">
<form id="animalForm" method="POST" action="{{ route('admin.animals.update', $animal) }}" enctype="multipart/form-data" novalidate>
    @csrf
    @method('PUT')

    <nav class="ae-crumbs" aria-label="Breadcrumb">
        <a href="{{ route('admin.animals.index') }}">Animals</a><span>/</span><span>Edit Animal</span>
    </nav>

    <div class="ae-head">
        <div>
            <h1>
                <span id="aeTitle">{{ $animal->name }}</span>
                <span class="ae-pill {{ $statusVal === 'published' ? '' : 'draft' }}" id="aePill"><i></i><span id="aePillTxt">{{ $statusVal === 'published' ? 'Published' : 'Draft' }}</span></span>
            </h1>
            <p>Manage the information displayed on your website.</p>
        </div>
        <div class="ae-head-actions">
            <a href="{{ route('admin.animals.preview', $animal) }}" target="_blank" rel="noopener" class="ae-btn ae-btn-ghost">
                <svg class="ico" viewBox="0 0 24 24"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/></svg>
                Preview Page
            </a>
            <button type="submit" class="ae-btn ae-btn-primary" data-save>
                <svg class="ico" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
                <span>Save Changes</span>
            </button>
        </div>
    </div>

    @if($errors->any())
        <div class="ae-alert" role="alert">
            <strong>Please fix the following before saving:</strong>
            <ul>
                @foreach($errors->all() as $message)
                    <li>{{ $message }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="ae-tabs" role="tablist">
        <button type="button" class="ae-tab active" data-tab="basic" role="tab">
            <svg class="ico" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>Basic Info
        </button>
        <button type="button" class="ae-tab" data-tab="classification" role="tab">
            <svg class="ico" viewBox="0 0 24 24"><path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L3 13V3h10l7.6 7.6a2 2 0 0 1 0 2.8z"/><circle cx="7.5" cy="7.5" r="1.2"/></svg>Classification
        </button>
        <button type="button" class="ae-tab" data-tab="habitat" role="tab">
            <svg class="ico" viewBox="0 0 24 24"><path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/></svg>Habitat &amp; Range
        </button>
        <button type="button" class="ae-tab" data-tab="facts" role="tab">
            <svg class="ico" viewBox="0 0 24 24"><path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.5 19 2c1 2 2 4.2 2 8 0 5.5-4.8 10-10 10z"/><path d="M2 21c0-3 1.9-5.5 6-7"/></svg>Facts &amp; Conservation
        </button>
        <button type="button" class="ae-tab" data-tab="images" role="tab">
            <svg class="ico" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/></svg>Images &amp; SEO
        </button>
        <button type="button" class="ae-tab" data-tab="faqs" role="tab">
            <svg class="ico" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 0 1 5.8 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>FAQs
        </button>
    </div>

    {{-- ===================== BASIC INFO ===================== --}}
    <section class="ae-pane" data-pane="basic">
        <div class="ae-grid">
            <div class="ae-stack">
                <div class="ae-card">
                    <h3 class="ae-card-t">
                        <svg class="ico" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/></svg>
                        Basic Information
                    </h3>
                    <p class="ae-card-s">Enter the core details about the animal.</p>

                    <div class="ae-fg">
                        <div class="ae-f">
                            <label class="ae-lbl" for="name">Animal Name <span class="ae-req">*</span></label>
                            <input id="name" name="name" type="text" maxlength="150" value="{{ old('name', $animal->name) }}" class="@error('name') is-invalid @enderror">
                            @error('name')<div class="ae-err">{{ $message }}</div>@enderror
                        </div>
                        <div class="ae-f">
                            <label class="ae-lbl" for="scientific_name">Scientific Name</label>
                            <input id="scientific_name" name="scientific_name" type="text" maxlength="180" value="{{ old('scientific_name', $animal->scientific_name) }}" class="@error('scientific_name') is-invalid @enderror">
                            @error('scientific_name')<div class="ae-err">{{ $message }}</div>@enderror
                        </div>
                        <div class="ae-f">
                            <label class="ae-lbl" for="slug">URL Slug</label>
                            <input id="slug" name="slug" type="text" maxlength="180" value="{{ old('slug', $animal->slug) }}" class="@error('slug') is-invalid @enderror">
                            <div class="ae-hint">Letters, numbers, dashes and underscores only.</div>
                            @error('slug')<div class="ae-err">{{ $message }}</div>@enderror
                        </div>
                        <div class="ae-f">
                            <label class="ae-lbl" for="category">Category <span class="ae-req">*</span></label>
                            <select id="category" name="category" class="@error('category') is-invalid @enderror">
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ $catVal === $category ? 'selected' : '' }}>{{ $category }}</option>
                                @endforeach
                            </select>
                            @error('category')<div class="ae-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="ae-f full">
                            <label class="ae-lbl" for="short_description">Short Description</label>
                            <textarea id="short_description" name="short_description" maxlength="160" style="min-height:80px;" class="@error('short_description') is-invalid @enderror">{{ old('short_description', $animal->short_description) }}</textarea>
                            <div class="ae-count"><span id="shortCount">0</span>/160</div>
                            @error('short_description')<div class="ae-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="ae-f full">
                            <label class="ae-lbl" for="descEditor">Full Description <span class="ae-req">*</span></label>
                            <div class="ae-rte @error('description') is-invalid @enderror" id="descBox">
                                <div class="ae-tb" id="descToolbar">
                                    <button type="button" data-cmd="bold" title="Bold" aria-label="Bold">B</button>
                                    <button type="button" data-cmd="italic" title="Italic" aria-label="Italic" style="font-style:italic;font-weight:600;">I</button>
                                    <button type="button" data-cmd="underline" title="Underline" aria-label="Underline" style="text-decoration:underline;font-weight:600;">U</button>
                                    <span class="sep"></span>
                                    <button type="button" data-cmd="insertUnorderedList" title="Bullet list" aria-label="Bullet list">
                                        <svg class="ico" viewBox="0 0 24 24"><path d="M9 6h11M9 12h11M9 18h11"/><circle cx="4.5" cy="6" r="1"/><circle cx="4.5" cy="12" r="1"/><circle cx="4.5" cy="18" r="1"/></svg>
                                    </button>
                                    <button type="button" data-cmd="insertOrderedList" title="Numbered list" aria-label="Numbered list">
                                        <svg class="ico" viewBox="0 0 24 24"><path d="M10 6h11M10 12h11M10 18h11M4 6h1v4M4 10h2M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg>
                                    </button>
                                    <span class="sep"></span>
                                    <button type="button" data-cmd="link" title="Add link" aria-label="Add link">
                                        <svg class="ico" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.1 0l3-3a5 5 0 0 0-7.1-7.1l-1.7 1.7"/><path d="M14 11a5 5 0 0 0-7.1 0l-3 3a5 5 0 0 0 7.1 7.1l1.7-1.7"/></svg>
                                    </button>
                                    <button type="button" data-cmd="unlink" title="Remove link" aria-label="Remove link">
                                        <svg class="ico" viewBox="0 0 24 24"><path d="m18.8 13.2 1.3-1.3a5 5 0 0 0-7.1-7.1l-1.3 1.3M5.2 10.8l-1.3 1.3a5 5 0 0 0 7.1 7.1l1.3-1.3M8 2v3M2 8h3M16 22v-3M22 16h-3"/></svg>
                                    </button>
                                </div>
                                <div class="ae-editor" id="descEditor" contenteditable="true" role="textbox" aria-multiline="true" data-placeholder="Write the full description of the animal here…"></div>
                            </div>
                            <textarea id="description" name="description" hidden>{{ old('description', $animal->description) }}</textarea>
                            <div class="ae-count"><span id="descCount">0</span> characters</div>
                            @error('description')<div class="ae-err">{{ $message }}</div>@enderror
                        </div>

                        <div class="ae-f">
                            <label class="ae-lbl" for="status">Status <span class="ae-req">*</span></label>
                            <select id="status" name="status" class="@error('status') is-invalid @enderror">
                                <option value="published" {{ $statusVal === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="draft" {{ $statusVal === 'draft' ? 'selected' : '' }}>Draft (hidden)</option>
                            </select>
                            @error('status')<div class="ae-err">{{ $message }}</div>@enderror
                        </div>
                        <div class="ae-f">
                            <div class="ae-sw-l">Featured Animal</div>
                            <div class="ae-sw">
                                <div class="ae-switch">
                                    <input type="hidden" name="is_featured" value="0">
                                    <input type="checkbox" name="is_featured" value="1" aria-label="Featured animal" {{ old('is_featured', $animal->is_featured) ? 'checked' : '' }}>
                                    <span class="track"></span>
                                </div>
                                <span class="ae-sw-d">Show on homepage and featured sections.</span>
                            </div>
                        </div>
                        <div class="ae-f">
                            <div class="ae-sw-l">Show on Homepage</div>
                            <div class="ae-sw">
                                <div class="ae-switch">
                                    <input type="hidden" name="is_homepage_visible" value="0">
                                    <input type="checkbox" name="is_homepage_visible" value="1" aria-label="Show on homepage" {{ old('is_homepage_visible', $animal->is_homepage_visible) ? 'checked' : '' }}>
                                    <span class="track"></span>
                                </div>
                                <span class="ae-sw-d">Allow this animal in homepage lists.</span>
                            </div>
                        </div>
                        <div class="ae-f">
                            <label class="ae-lbl" for="display_order">Display Order</label>
                            <input id="display_order" name="display_order" type="number" min="0" value="{{ old('display_order', $animal->display_order) }}" class="@error('display_order') is-invalid @enderror">
                            <div class="ae-hint">Lower numbers appear first.</div>
                            @error('display_order')<div class="ae-err">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="ae-stack">
                {{-- Current image --}}
                <div class="ae-card">
                    <h3 class="ae-card-t">
                        <svg class="ico" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/></svg>
                        Current Image
                    </h3>
                    <p class="ae-card-s" style="margin-bottom:14px;">Main photo shown on the website.</p>

                    <label for="mainImage" class="ae-hero {{ $mainUrl ? 'has' : '' }}" id="heroFrame">
                        <span class="ae-tag" id="heroTag" style="{{ $mainUrl ? '' : 'display:none;' }}">Main Image</span>
                        <img id="heroImg" src="{{ $mainUrl }}" alt="{{ $animal->name }}" style="{{ $mainUrl ? '' : 'display:none;' }}">
                        <div class="ae-hero-empty" id="heroEmpty" style="{{ $mainUrl ? 'display:none;' : '' }}">No image yet.<br>Click or drop a photo here.</div>
                    </label>
                    <input id="mainImage" name="image" type="file" accept="image/*" class="ae-file-hidden">

                    <div class="ae-hero-name" id="heroName">{{ $animal->name }}</div>
                    <span class="ae-chip" id="heroCat">{{ $catVal }}</span>
                    <p class="ae-hero-short" id="heroShort">{{ old('short_description', $animal->short_description) }}</p>

                    <div class="ae-row">
                        <label for="mainImage" class="ae-btn ae-btn-ghost ae-sm" style="margin:0;">{{ $mainUrl ? 'Replace Image' : 'Choose Image' }}</label>
                        <button type="button" class="ae-btn ae-btn-ghost ae-sm" id="heroReset" hidden>Undo change</button>
                    </div>
                    <div class="ae-meta" id="heroMeta"></div>
                    <div class="ae-check" id="heroCheck"></div>
                    <div class="ae-hint">Best size 1200 × 800 px (3:2). JPG, PNG or WebP, up to 5 MB.</div>
                    @error('image')<div class="ae-err">{{ $message }}</div>@enderror
                </div>

                {{-- Gallery --}}
                <div class="ae-card">
                    <div class="ae-card-top">
                        <h3 class="ae-card-t">
                            <svg class="ico" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.1-3.1a2 2 0 0 0-2.8 0L6 21"/></svg>
                            Gallery Images
                        </h3>
                        <button type="button" class="ae-btn ae-btn-ghost ae-sm" id="galAdd">
                            <svg class="ico" viewBox="0 0 24 24" style="width:15px;height:15px;"><path d="M12 5v14M5 12h14"/></svg>
                            Add Images
                        </button>
                    </div>
                    <input type="file" id="galPicker" accept="image/*" multiple class="ae-file-hidden">

                    <div class="ae-gal" id="galGrid">
                        @foreach($animal->images as $img)
                            @php
                                $imgUrl = wk_ae_media_url($img->image, 'animals/gallery');
                                $isRm = (bool) old('images.' . $img->id . '.remove');
                                $capNow = old('images.' . $img->id . '.caption', $img->caption);
                            @endphp
                            <div class="ae-tile {{ $isRm ? 'is-removed' : '' }}" data-key="e{{ $img->id }}">
                                <img src="{{ $imgUrl }}" alt="{{ $img->alt_text }}" loading="lazy" onerror="this.style.visibility='hidden'">
                                <button type="button" class="ae-x" data-rm="{{ $img->id }}" aria-label="Remove image" title="Remove / undo">{{ $isRm ? '↺' : '×' }}</button>
                                <input type="checkbox" class="ae-rm" name="images[{{ $img->id }}][remove]" value="1" hidden {{ $isRm ? 'checked' : '' }}>
                                <span class="ae-cap" data-cap-for="e{{ $img->id }}">{{ $capNow }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="ae-gal-empty" id="galEmpty" style="{{ $animal->images->isEmpty() ? '' : 'display:none;' }}">No gallery images yet.</div>
                    <div class="ae-hint" id="galHint">Up to 6 new images per save (5 MB each). New images upload when you click Save Changes. Add alt text and credits in the <b>Images &amp; SEO</b> tab.</div>
                    <div class="ae-err" id="galErr" hidden></div>
                    @error('new_gallery_images')<div class="ae-err">{{ $message }}</div>@enderror
                    <div id="pendingFiles" hidden></div>
                </div>

                {{-- Related animals --}}
                <div class="ae-card">
                    <div class="ae-card-top">
                        <h3 class="ae-card-t">
                            <svg class="ico" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.1 0l3-3a5 5 0 0 0-7.1-7.1l-1.7 1.7"/><path d="M14 11a5 5 0 0 0-7.1 0l-3 3a5 5 0 0 0 7.1 7.1l1.7-1.7"/></svg>
                            Related Animals
                        </h3>
                        <a class="ae-link" href="{{ route('admin.animals.index') }}">View All →</a>
                    </div>
                    <div class="ae-chips" id="relChips"></div>
                    <select id="relAdd" aria-label="Add related animal"></select>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== CLASSIFICATION ===================== --}}
    <section class="ae-pane" data-pane="classification" hidden>
        <div class="ae-card">
            <h3 class="ae-card-t">Conservation status</h3>
            <p class="ae-card-s">How threatened this animal is in the wild.</p>
            <div class="ae-f" style="max-width:520px;">
                <label class="ae-lbl" for="conservation_status">Conservation status <span class="ae-req">*</span></label>
                <input id="conservation_status" name="conservation_status" type="text" list="csList" maxlength="100" value="{{ old('conservation_status', $animal->conservation_status) }}" class="@error('conservation_status') is-invalid @enderror">
                <datalist id="csList">
                    @foreach(['Extinct', 'Extinct in the Wild', 'Critically Endangered', 'Endangered', 'Vulnerable', 'Near Threatened', 'Least Concern', 'Data Deficient', 'Not assessed'] as $cs)
                        <option value="{{ $cs }}"></option>
                    @endforeach
                </datalist>
                <div class="ae-hint">Pick a suggestion or type your own, e.g. Vulnerable, Endangered, Least Concern.</div>
                @error('conservation_status')<div class="ae-err">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="ae-card">
            <h3 class="ae-card-t">Scientific classification</h3>
            <p class="ae-card-s">Taxonomy details for this species.</p>
            <div class="ae-fg">
                @foreach($taxonomy as $field => $label)
                    <div class="ae-f">
                        <label class="ae-lbl" for="{{ $field }}">{{ $label }}</label>
                        <input id="{{ $field }}" name="{{ $field }}" type="text" maxlength="150" value="{{ old($field, $animal->$field) }}" class="@error($field) is-invalid @enderror">
                        @error($field)<div class="ae-err">{{ $message }}</div>@enderror
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== HABITAT & RANGE ===================== --}}
    <section class="ae-pane" data-pane="habitat" hidden>
        <div class="ae-card">
            <h3 class="ae-card-t">Habitat</h3>
            <p class="ae-card-s">Where this animal lives.</p>
            <div class="ae-fg">
                <div class="ae-f">
                    <label class="ae-lbl" for="habitat_id">Habitat</label>
                    <select id="habitat_id" name="habitat_id" class="@error('habitat_id') is-invalid @enderror">
                        <option value="">Select habitat</option>
                        @foreach($habitats as $habitat)
                            <option value="{{ $habitat->id }}" {{ (string) old('habitat_id', $animal->habitat_id) === (string) $habitat->id ? 'selected' : '' }}>{{ $habitat->name }}</option>
                        @endforeach
                    </select>
                    @error('habitat_id')<div class="ae-err">{{ $message }}</div>@enderror
                </div>
                <div class="ae-f">
                    <label class="ae-lbl" for="habitat">Habitat description</label>
                    <input id="habitat" name="habitat" type="text" maxlength="255" value="{{ old('habitat', $animal->habitat) }}" class="@error('habitat') is-invalid @enderror">
                    @error('habitat')<div class="ae-err">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="ae-card">
            <h3 class="ae-card-t">Range</h3>
            <p class="ae-card-s">Geographical range, native countries and distribution.</p>
            <div class="ae-fg">
                @foreach($rangeAreas as $field => $label)
                    <div class="ae-f full">
                        <label class="ae-lbl" for="{{ $field }}">{{ $label }}</label>
                        <textarea id="{{ $field }}" name="{{ $field }}" class="@error($field) is-invalid @enderror">{{ old($field, $animal->$field) }}</textarea>
                        @error($field)<div class="ae-err">{{ $message }}</div>@enderror
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== FACTS & CONSERVATION ===================== --}}
    <section class="ae-pane" data-pane="facts" hidden>
        <div class="ae-card">
            <h3 class="ae-card-t">Quick facts</h3>
            <p class="ae-card-s">Short facts shown in the animal details box.</p>
            <div class="ae-fg">
                @foreach($factInputs as $field => $label)
                    <div class="ae-f">
                        <label class="ae-lbl" for="{{ $field }}">{{ $label }}</label>
                        <input id="{{ $field }}" name="{{ $field }}" type="text" maxlength="255" value="{{ old($field, $animal->$field) }}" class="@error($field) is-invalid @enderror">
                        @error($field)<div class="ae-err">{{ $message }}</div>@enderror
                    </div>
                @endforeach
            </div>
        </div>

        <div class="ae-card">
            <h3 class="ae-card-t">Behaviour &amp; conservation</h3>
            <p class="ae-card-s">Longer write-ups about this animal.</p>
            <div class="ae-fg">
                @foreach($factAreas as $field => $label)
                    <div class="ae-f full">
                        <label class="ae-lbl" for="{{ $field }}">{{ $label }}</label>
                        <textarea id="{{ $field }}" name="{{ $field }}" class="@error($field) is-invalid @enderror">{{ old($field, $animal->$field) }}</textarea>
                        @error($field)<div class="ae-err">{{ $message }}</div>@enderror
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ===================== IMAGES & SEO ===================== --}}
    <section class="ae-pane" data-pane="images" hidden>
        <div class="ae-card">
            <h3 class="ae-card-t">Main image details</h3>
            <p class="ae-card-s">Used for accessibility, captions and credits. Change the photo itself from the Basic Info tab.</p>
            <div class="ae-fg">
                @foreach(['image_alt_text' => 'Alt text', 'image_caption' => 'Caption', 'image_credit' => 'Image credit', 'image_source' => 'Image source'] as $field => $label)
                    <div class="ae-f">
                        <label class="ae-lbl" for="{{ $field }}">{{ $label }}</label>
                        <input id="{{ $field }}" name="{{ $field }}" type="text" value="{{ old($field, $animal->$field) }}" class="@error($field) is-invalid @enderror">
                        @error($field)<div class="ae-err">{{ $message }}</div>@enderror
                    </div>
                @endforeach
            </div>
        </div>

        <div class="ae-card">
            <h3 class="ae-card-t">Gallery image details</h3>
            <p class="ae-card-s">Alt text, caption and credits for each gallery image.</p>

            <div id="galRows">
                @foreach($animal->images as $img)
                    @php
                        $imgUrl = wk_ae_media_url($img->image, 'animals/gallery');
                        $isRm = (bool) old('images.' . $img->id . '.remove');
                    @endphp
                    <div class="ae-irow {{ $isRm ? 'is-removed' : '' }}" data-row="e{{ $img->id }}">
                        <img src="{{ $imgUrl }}" alt="" loading="lazy" onerror="this.style.visibility='hidden'">
                        <div>
                            <div class="fields">
                                @foreach($imgFields as $field => $label)
                                    <div class="ae-f">
                                        <label class="ae-lbl" for="img-{{ $img->id }}-{{ $field }}">{{ $label }}</label>
                                        <input id="img-{{ $img->id }}-{{ $field }}" type="text" name="images[{{ $img->id }}][{{ $field }}]" value="{{ old('images.' . $img->id . '.' . $field, $img->$field) }}" @if($field === 'caption') data-cap="e{{ $img->id }}" @endif>
                                    </div>
                                @endforeach
                            </div>
                            <button type="button" class="ae-linkbtn" data-trigger-rm="{{ $img->id }}">{{ $isRm ? 'Undo remove' : 'Remove this image' }}</button>
                        </div>
                    </div>
                @endforeach
                <div id="pendingRows"></div>
            </div>
            <div class="ae-gal-empty" id="galRowsEmpty" style="{{ $animal->images->isEmpty() ? '' : 'display:none;' }}">No gallery images yet. Use "Add Images" on the Basic Info tab.</div>
        </div>

        <div class="ae-card">
            <h3 class="ae-card-t">Search &amp; sharing</h3>
            <p class="ae-card-s">How this page appears in Google and when shared.</p>
            <div class="ae-fg">
                <div class="ae-f">
                    <label class="ae-lbl" for="meta_title">Meta title</label>
                    <input id="meta_title" name="meta_title" type="text" maxlength="70" value="{{ old('meta_title', $animal->meta_title) }}" class="@error('meta_title') is-invalid @enderror">
                    <div class="ae-count"><span id="metaTitleCount">0</span>/70</div>
                    @error('meta_title')<div class="ae-err">{{ $message }}</div>@enderror
                </div>
                <div class="ae-f">
                    <label class="ae-lbl" for="canonical_url">Canonical URL</label>
                    <input id="canonical_url" name="canonical_url" type="url" maxlength="2048" value="{{ old('canonical_url', $animal->canonical_url) }}" class="@error('canonical_url') is-invalid @enderror" placeholder="https://">
                    @error('canonical_url')<div class="ae-err">{{ $message }}</div>@enderror
                </div>
                <div class="ae-f full">
                    <label class="ae-lbl" for="meta_description">Meta description</label>
                    <textarea id="meta_description" name="meta_description" maxlength="320" style="min-height:90px;" class="@error('meta_description') is-invalid @enderror">{{ old('meta_description', $animal->meta_description) }}</textarea>
                    <div class="ae-count"><span id="metaDescCount">0</span>/320</div>
                    @error('meta_description')<div class="ae-err">{{ $message }}</div>@enderror
                </div>
                <div class="ae-f full">
                    <label class="ae-lbl" for="social_image">Social sharing image URL</label>
                    <input id="social_image" name="social_image" type="url" maxlength="2048" value="{{ old('social_image', $animal->social_image) }}" class="@error('social_image') is-invalid @enderror" placeholder="https://">
                    @error('social_image')<div class="ae-err">{{ $message }}</div>@enderror
                </div>
                <div class="ae-f">
                    <div class="ae-sw">
                        <div class="ae-switch">
                            <input type="hidden" name="noindex" value="0">
                            <input type="checkbox" name="noindex" value="1" aria-label="No index" {{ old('noindex', $animal->noindex) ? 'checked' : '' }}>
                            <span class="track"></span>
                        </div>
                        <span class="ae-sw-d"><b style="color:#2b3a36;">No index</b><br>Ask search engines not to index this page.</span>
                    </div>
                </div>
                <div class="ae-f">
                    <div class="ae-sw">
                        <div class="ae-switch">
                            <input type="hidden" name="nofollow" value="0">
                            <input type="checkbox" name="nofollow" value="1" aria-label="No follow" {{ old('nofollow', $animal->nofollow) ? 'checked' : '' }}>
                            <span class="track"></span>
                        </div>
                        <span class="ae-sw-d"><b style="color:#2b3a36;">No follow</b><br>Ask search engines not to follow links.</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== FAQS ===================== --}}
    <section class="ae-pane" data-pane="faqs" hidden>
        <div class="ae-card">
            <div class="ae-card-top">
                <h3 class="ae-card-t">
                    <svg class="ico" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9.1 9a3 3 0 0 1 5.8 1c0 2-3 3-3 3"/><path d="M12 17h.01"/></svg>
                    Frequently Asked Questions
                </h3>
                <button type="button" class="ae-btn ae-btn-ghost ae-sm" id="faqAdd">
                    <svg class="ico" viewBox="0 0 24 24" style="width:15px;height:15px;"><path d="M12 5v14M5 12h14"/></svg>
                    Add Question
                </button>
            </div>

            @foreach($animal->faqs as $faq)
                @php $faqRm = (bool) old('faqs.' . $faq->id . '.remove'); @endphp
                <div class="ae-faq {{ $faqRm ? 'is-removed' : '' }}" data-faq="e{{ $faq->id }}">
                    <div class="ae-faq-top">
                        <span>Question</span>
                        <button type="button" class="ae-linkbtn" data-faq-rm>{{ $faqRm ? 'Undo remove' : 'Remove' }}</button>
                    </div>
                    <div class="ae-f">
                        <input type="text" name="faqs[{{ $faq->id }}][question]" maxlength="255" value="{{ old('faqs.' . $faq->id . '.question', $faq->question) }}" aria-label="Question" class="@error('faqs.' . $faq->id . '.question') is-invalid @enderror">
                        @error('faqs.' . $faq->id . '.question')<div class="ae-err">{{ $message }}</div>@enderror
                    </div>
                    <div class="ae-f" style="margin-bottom:0;">
                        <label class="ae-lbl">Answer</label>
                        <textarea name="faqs[{{ $faq->id }}][answer]" aria-label="Answer" class="@error('faqs.' . $faq->id . '.answer') is-invalid @enderror">{{ old('faqs.' . $faq->id . '.answer', $faq->answer) }}</textarea>
                        @error('faqs.' . $faq->id . '.answer')<div class="ae-err">{{ $message }}</div>@enderror
                    </div>
                    <input type="checkbox" class="ae-rm" name="faqs[{{ $faq->id }}][remove]" value="1" hidden {{ $faqRm ? 'checked' : '' }}>
                </div>
            @endforeach

            <div id="faqNew"></div>
            <div class="ae-gal-empty" id="faqEmpty" style="{{ $animal->faqs->isEmpty() ? '' : 'display:none;' }}">No questions yet. Click "Add Question" to create one.</div>
            <div class="ae-hint">You can add up to 6 new questions per save. A question needs both a question and an answer to be saved.</div>
        </div>

        <template id="faqTpl">
            <div class="ae-faq">
                <div class="ae-faq-top">
                    <span>New question</span>
                    <button type="button" class="ae-linkbtn" data-faq-del>Delete</button>
                </div>
                <div class="ae-f">
                    <input type="text" name="new_faqs[__i__][question]" maxlength="255" placeholder="Question" aria-label="New question">
                </div>
                <div class="ae-f" style="margin-bottom:0;">
                    <label class="ae-lbl">Answer</label>
                    <textarea name="new_faqs[__i__][answer]" placeholder="Answer" aria-label="New answer"></textarea>
                </div>
            </div>
        </template>
    </section>

    <div class="ae-note" style="margin-top:24px;">
        <svg class="ico" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4M12 8h.01"/></svg>
        Changes you make here will be visible on your public website after saving.
    </div>

    <div class="ae-actions" style="margin-top:18px;">
        <a href="{{ route('admin.animals.index') }}" class="ae-btn ae-btn-ghost">Cancel</a>
        <button type="submit" class="ae-btn ae-btn-primary" data-save>
            <svg class="ico" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></svg>
            <span>Save Changes</span>
        </button>
    </div>
</form>
</div>

<script>
(function () {
    var form = document.getElementById('animalForm');
    function $(s, c) { return (c || document).querySelector(s); }
    function $$(s, c) { return Array.prototype.slice.call((c || document).querySelectorAll(s)); }
    function byId(id) { return document.getElementById(id); }

    /* ---------------- Tabs ---------------- */
    var tabs = $$('.ae-tab'), panes = $$('.ae-pane');
    function openTab(name) {
        tabs.forEach(function (t) {
            var on = t.getAttribute('data-tab') === name;
            t.classList.toggle('active', on);
            t.setAttribute('aria-selected', on ? 'true' : 'false');
        });
        panes.forEach(function (p) { p.hidden = p.getAttribute('data-pane') !== name; });
    }
    function paneOf(el) { var p = el.closest('.ae-pane'); return p ? p.getAttribute('data-pane') : 'basic'; }
    tabs.forEach(function (t) { t.addEventListener('click', function () { openTab(t.getAttribute('data-tab')); }); });

    // mark tabs that contain server-side errors and open the first one
    var firstErrPane = null;
    panes.forEach(function (p) {
        if (p.querySelector('.ae-err, .is-invalid')) {
            var t = $('.ae-tab[data-tab="' + p.getAttribute('data-pane') + '"]');
            if (t) { t.classList.add('has-error'); }
            if (!firstErrPane) { firstErrPane = p.getAttribute('data-pane'); }
        }
    });
    if (firstErrPane) { openTab(firstErrPane); }

    /* ---------------- Small helpers ---------------- */
    function setErr(el, msg) {
        var holder = el.closest('.ae-f') || el.parentNode;
        var e = holder.querySelector('.ae-err.js');
        if (!msg) { if (e) { e.remove(); } return; }
        if (!e) { e = document.createElement('div'); e.className = 'ae-err js'; holder.appendChild(e); }
        e.textContent = msg;
    }
    function bindCounter(inputId, outId) {
        var inp = byId(inputId), out = byId(outId);
        if (!inp || !out) { return; }
        function upd() { out.textContent = inp.value.length; }
        inp.addEventListener('input', upd); upd();
    }
    bindCounter('short_description', 'shortCount');
    bindCounter('meta_title', 'metaTitleCount');
    bindCounter('meta_description', 'metaDescCount');

    /* ---------------- Live mirrors (title, pill, side card) ---------------- */
    var nameEl = byId('name'), catEl = byId('category'), shortEl = byId('short_description'), statusEl = byId('status');
    nameEl.addEventListener('input', function () {
        byId('aeTitle').textContent = nameEl.value || 'Animal';
        byId('heroName').textContent = nameEl.value;
        nameEl.classList.remove('is-invalid'); setErr(nameEl, '');
    });
    catEl.addEventListener('change', function () { byId('heroCat').textContent = catEl.value; });
    shortEl.addEventListener('input', function () { byId('heroShort').textContent = shortEl.value; });
    statusEl.addEventListener('change', function () {
        var pub = statusEl.value === 'published';
        byId('aePill').classList.toggle('draft', !pub);
        byId('aePillTxt').textContent = pub ? 'Published' : 'Draft';
    });
    byId('conservation_status').addEventListener('input', function (e) { e.target.classList.remove('is-invalid'); setErr(e.target, ''); });

    /* ---------------- Rich text editor ---------------- */
    var editor = byId('descEditor'), descTA = byId('description'), descBox = byId('descBox');
    var ALLOWED = { B: 1, STRONG: 1, I: 1, EM: 1, U: 1, UL: 1, OL: 1, LI: 1, A: 1, P: 1, BR: 1, DIV: 1 };

    function unwrap(parent, node) {
        while (node.firstChild) { parent.insertBefore(node.firstChild, node); }
        parent.removeChild(node);
    }
    function cleanNode(node) {
        Array.prototype.slice.call(node.childNodes).forEach(function (ch) {
            if (ch.nodeType === 3) { return; }
            if (ch.nodeType !== 1) { node.removeChild(ch); return; }
            var tag = ch.tagName;
            if (/^(SCRIPT|STYLE|IFRAME|OBJECT|EMBED|LINK|META|TEMPLATE|NOSCRIPT|SVG|MATH)$/.test(tag)) { node.removeChild(ch); return; }
            cleanNode(ch);
            if (!ALLOWED[tag]) { unwrap(node, ch); return; }
            Array.prototype.slice.call(ch.attributes).forEach(function (a) {
                if (!(tag === 'A' && a.name === 'href')) { ch.removeAttribute(a.name); }
            });
            if (tag === 'A') {
                var h = ch.getAttribute('href') || '';
                if (/^(https?:\/\/|mailto:|tel:|\/|#)/i.test(h)) {
                    ch.setAttribute('target', '_blank');
                    ch.setAttribute('rel', 'noopener noreferrer');
                } else { unwrap(node, ch); }
            }
        });
    }
    function cleanHtml(html) {
        var t = document.createElement('template');
        t.innerHTML = html;
        cleanNode(t.content);
        var d = document.createElement('div');
        d.appendChild(t.content);
        return d.innerHTML;
    }
    function toPlain(node) {
        var out = '';
        Array.prototype.forEach.call(node.childNodes, function (c) {
            if (c.nodeType === 3) { out += c.nodeValue; }
            else if (c.nodeType === 1) {
                if (c.tagName === 'BR') { out += '\n'; }
                else {
                    var inner = toPlain(c);
                    out += inner;
                    if (/^(DIV|P|LI)$/.test(c.tagName) && !/\n$/.test(inner)) { out += '\n'; }
                }
            }
        });
        return out;
    }
    function plainText() { return toPlain(editor).replace(/\u00a0/g, ' ').replace(/\n{3,}/g, '\n\n').trim(); }
    function escapeHtml(s) { return s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;'); }

    var initial = descTA.value || '';
    if (/<\/?(p|br|b|i|u|strong|em|ul|ol|li|a|div|span)\b/i.test(initial)) {
        editor.innerHTML = cleanHtml(initial);
    } else {
        editor.innerHTML = escapeHtml(initial).replace(/\r?\n/g, '<br>');
    }

    function syncDesc() {
        if (plainText() === '') { descTA.value = ''; return; }
        var html = cleanHtml(editor.innerHTML);
        // Only store HTML when real formatting was used, otherwise keep plain text
        if (/<(b|strong|i|em|u|ul|ol|li|a)[\s>]/i.test(html)) { descTA.value = html; }
        else { descTA.value = plainText(); }
    }
    function updDescCount() { byId('descCount').textContent = plainText().length; }
    editor.addEventListener('input', function () {
        updDescCount();
        descBox.classList.remove('is-invalid');
        var e = descBox.parentNode.querySelector('.ae-err.js'); if (e) { e.remove(); }
    });
    updDescCount();

    editor.addEventListener('paste', function (e) {
        e.preventDefault();
        var txt = (e.clipboardData || window.clipboardData).getData('text/plain');
        document.execCommand('insertText', false, txt);
    });

    var savedRange = null;
    function saveSel() {
        var s = window.getSelection();
        if (s.rangeCount && editor.contains(s.anchorNode)) { savedRange = s.getRangeAt(0).cloneRange(); }
    }
    function restoreSel() {
        if (!savedRange) { editor.focus(); return; }
        var s = window.getSelection(); s.removeAllRanges(); s.addRange(savedRange);
    }
    editor.addEventListener('keyup', saveSel);
    editor.addEventListener('mouseup', saveSel);

    var tb = byId('descToolbar');
    tb.addEventListener('mousedown', function (e) { if (e.target.closest('button')) { e.preventDefault(); } });
    tb.addEventListener('click', function (e) {
        var b = e.target.closest('button[data-cmd]');
        if (!b) { return; }
        var cmd = b.getAttribute('data-cmd');
        editor.focus();
        if (cmd === 'link') {
            saveSel();
            var url = window.prompt('Enter link URL (https://…)', 'https://');
            if (!url) { return; }
            if (!/^(https?:\/\/|mailto:|tel:)/i.test(url)) { window.alert('Please enter a link starting with https:// , http:// , mailto: or tel:'); return; }
            restoreSel();
            document.execCommand('createLink', false, url);
        } else {
            document.execCommand(cmd, false, null);
        }
        saveSel(); updDescCount(); refreshToolbar();
    });
    function refreshToolbar() {
        ['bold', 'italic', 'underline', 'insertUnorderedList', 'insertOrderedList'].forEach(function (c) {
            var b = $('[data-cmd="' + c + '"]', tb);
            if (b) { var on = false; try { on = document.queryCommandState(c); } catch (err) {} b.classList.toggle('on', on); }
        });
    }
    document.addEventListener('selectionchange', function () {
        var s = window.getSelection();
        if (s.rangeCount && editor.contains(s.anchorNode)) { refreshToolbar(); }
    });

    /* ---------------- Main image ---------------- */
    var mainInput = byId('mainImage'), heroFrame = byId('heroFrame'), heroImg = byId('heroImg'), heroEmpty = byId('heroEmpty'),
        heroTag = byId('heroTag'), heroMeta = byId('heroMeta'), heroCheck = byId('heroCheck'), heroReset = byId('heroReset');
    var heroOrig = { src: heroImg.getAttribute('src') || '', has: heroFrame.classList.contains('has') };

    heroImg.addEventListener('error', function () {
        heroImg.style.display = 'none'; heroEmpty.style.display = ''; heroFrame.classList.remove('has'); heroTag.style.display = 'none';
        heroOrig.has = false;
    });
    function heroCheckMsg(type, text) { heroCheck.className = 'ae-check ' + type; heroCheck.textContent = text; }
    function heroRestore() {
        mainInput.value = ''; heroMeta.textContent = ''; heroCheck.className = 'ae-check'; heroCheck.textContent = ''; heroReset.hidden = true;
        if (heroOrig.has) {
            heroImg.src = heroOrig.src; heroImg.style.display = 'block'; heroEmpty.style.display = 'none';
            heroFrame.classList.add('has'); heroTag.textContent = 'Main Image'; heroTag.className = 'ae-tag'; heroTag.style.display = '';
        } else {
            heroImg.removeAttribute('src'); heroImg.style.display = 'none'; heroEmpty.style.display = '';
            heroFrame.classList.remove('has'); heroTag.style.display = 'none';
        }
    }
    function heroHandle(file) {
        if (!file) { heroRestore(); return; }
        if (!file.type || file.type.indexOf('image/') !== 0) { heroRestore(); heroCheckMsg('warn', 'Please choose an image file (JPG, PNG or WebP).'); return; }
        var mb = file.size / 1048576;
        if (mb > 5) { heroRestore(); heroCheckMsg('warn', 'This file is ' + mb.toFixed(1) + ' MB. The limit is 5 MB, please choose a smaller image.'); return; }
        var url = URL.createObjectURL(file);
        heroImg.src = url; heroImg.style.display = 'block'; heroEmpty.style.display = 'none';
        heroFrame.classList.add('has'); heroTag.textContent = 'New image (not saved yet)'; heroTag.className = 'ae-tag new'; heroTag.style.display = '';
        heroReset.hidden = false;
        heroMeta.textContent = file.name + ' · ' + mb.toFixed(2) + ' MB';
        var probe = new Image();
        probe.onload = function () {
            var w = probe.naturalWidth, h = probe.naturalHeight, ratio = w / h, problems = [];
            heroMeta.textContent = file.name + ' · ' + w + ' × ' + h + ' px · ' + mb.toFixed(2) + ' MB';
            if (w < 800) { problems.push('Image is small and may look blurry. Use at least 1200 px wide.'); }
            if (ratio < 1.2 || ratio > 1.8) { problems.push('This shape will be cropped. A 3:2 landscape photo fits best.'); }
            if (problems.length) { heroCheckMsg('warn', problems.join(' ')); } else { heroCheckMsg('ok', 'Looks good. Click Save Changes to apply it.'); }
        };
        probe.src = url;
    }
    mainInput.addEventListener('change', function () { heroHandle(mainInput.files[0]); });
    heroReset.addEventListener('click', heroRestore);
    ['dragenter', 'dragover'].forEach(function (ev) { heroFrame.addEventListener(ev, function (e) { e.preventDefault(); heroFrame.classList.add('drag'); }); });
    ['dragleave', 'drop'].forEach(function (ev) { heroFrame.addEventListener(ev, function (e) { e.preventDefault(); heroFrame.classList.remove('drag'); }); });
    heroFrame.addEventListener('drop', function (e) {
        if (e.dataTransfer && e.dataTransfer.files && e.dataTransfer.files.length) {
            try { mainInput.files = e.dataTransfer.files; heroHandle(mainInput.files[0]); } catch (err) {}
        }
    });

    /* ---------------- Gallery ---------------- */
    var MAX_NEW = 6, nIdx = 0, pending = {};
    var picker = byId('galPicker'), grid = byId('galGrid'), pendingBox = byId('pendingFiles'), pendingRows = byId('pendingRows'),
        galErr = byId('galErr'), galEmpty = byId('galEmpty'), rowsEmpty = byId('galRowsEmpty');
    var labelFields = [['alt_text', 'Alt text'], ['caption', 'Caption'], ['credit', 'Image credit'], ['source', 'Image source']];

    function pendingCount() { return Object.keys(pending).length; }
    function refreshGalEmpty() {
        var any = grid.querySelectorAll('.ae-tile').length > 0;
        galEmpty.style.display = any ? 'none' : '';
        rowsEmpty.style.display = (any || $('#galRows .ae-irow')) ? 'none' : '';
    }
    function showGalErr(msgs) {
        if (!msgs.length) { galErr.hidden = true; galErr.textContent = ''; return; }
        galErr.hidden = false; galErr.textContent = msgs.join(' ');
    }
    byId('galAdd').addEventListener('click', function () { picker.click(); });
    picker.addEventListener('change', function () {
        var files = Array.prototype.slice.call(picker.files), notes = [];
        files.forEach(function (f) {
            if (pendingCount() >= MAX_NEW) { if (notes.indexOf('Only ' + MAX_NEW + ' new images can be added per save.') < 0) { notes.push('Only ' + MAX_NEW + ' new images can be added per save.'); } return; }
            if (!f.type || f.type.indexOf('image/') !== 0) { notes.push(f.name + ' is not an image.'); return; }
            if (f.size > 5 * 1048576) { notes.push(f.name + ' is over 5 MB.'); return; }
            try { addPending(f); } catch (err) { notes.push('Your browser could not attach ' + f.name + '. Please try a newer browser.'); }
        });
        picker.value = '';
        showGalErr(notes);
    });

    function addPending(file) {
        var i = nIdx++;
        var inp = document.createElement('input');
        inp.type = 'file'; inp.name = 'new_gallery_images[' + i + '][file]';
        var dt = new DataTransfer(); dt.items.add(file); inp.files = dt.files;
        pendingBox.appendChild(inp);

        var url = URL.createObjectURL(file);
        var key = 'n' + i;

        var tile = document.createElement('div');
        tile.className = 'ae-tile is-new'; tile.setAttribute('data-key', key);
        var im = document.createElement('img'); im.src = url; im.alt = '';
        var x = document.createElement('button'); x.type = 'button'; x.className = 'ae-x'; x.setAttribute('data-rm-new', i); x.setAttribute('aria-label', 'Remove image'); x.textContent = '×';
        var cap = document.createElement('span'); cap.className = 'ae-cap'; cap.setAttribute('data-cap-for', key); cap.textContent = 'New image';
        tile.appendChild(im); tile.appendChild(x); tile.appendChild(cap);
        grid.appendChild(tile);

        var row = document.createElement('div');
        row.className = 'ae-irow'; row.setAttribute('data-row', key);
        var rim = document.createElement('img'); rim.src = url; rim.alt = '';
        var right = document.createElement('div');
        var fields = document.createElement('div'); fields.className = 'fields';
        labelFields.forEach(function (lf) {
            var wrap = document.createElement('div'); wrap.className = 'ae-f';
            var lb = document.createElement('label'); lb.className = 'ae-lbl'; lb.setAttribute('for', 'newimg-' + i + '-' + lf[0]); lb.textContent = lf[1];
            var ip = document.createElement('input'); ip.type = 'text'; ip.id = 'newimg-' + i + '-' + lf[0]; ip.name = 'new_gallery_images[' + i + '][' + lf[0] + ']';
            if (lf[0] === 'caption') { ip.setAttribute('data-cap', key); }
            wrap.appendChild(lb); wrap.appendChild(ip); fields.appendChild(wrap);
        });
        var del = document.createElement('button'); del.type = 'button'; del.className = 'ae-linkbtn'; del.setAttribute('data-rm-new', i); del.textContent = 'Remove this new image';
        right.appendChild(fields); right.appendChild(del);
        row.appendChild(rim); row.appendChild(right);
        pendingRows.appendChild(row);

        pending[i] = { input: inp, tile: tile, row: row, url: url };
        refreshGalEmpty();
    }
    function removePending(i) {
        var p = pending[i]; if (!p) { return; }
        p.input.remove(); p.tile.remove(); p.row.remove();
        URL.revokeObjectURL(p.url); delete pending[i];
        showGalErr([]); refreshGalEmpty();
    }
    function toggleExisting(id) {
        var tile = $('.ae-tile[data-key="e' + id + '"]', grid);
        var row = $('.ae-irow[data-row="e' + id + '"]');
        if (!tile) { return; }
        var cb = $('.ae-rm', tile);
        cb.checked = !cb.checked;
        tile.classList.toggle('is-removed', cb.checked);
        $('.ae-x', tile).textContent = cb.checked ? '↺' : '×';
        if (row) {
            row.classList.toggle('is-removed', cb.checked);
            var tr = $('[data-trigger-rm]', row); if (tr) { tr.textContent = cb.checked ? 'Undo remove' : 'Remove this image'; }
        }
    }
    document.addEventListener('click', function (e) {
        var t;
        if ((t = e.target.closest('[data-rm]'))) { toggleExisting(t.getAttribute('data-rm')); }
        else if ((t = e.target.closest('[data-trigger-rm]'))) { toggleExisting(t.getAttribute('data-trigger-rm')); }
        else if ((t = e.target.closest('[data-rm-new]'))) { removePending(t.getAttribute('data-rm-new')); }
    });
    form.addEventListener('input', function (e) {
        var key = e.target.getAttribute && e.target.getAttribute('data-cap');
        if (!key) { return; }
        var lab = $('[data-cap-for="' + key + '"]', grid);
        if (lab) { lab.textContent = e.target.value || (key.charAt(0) === 'n' ? 'New image' : ''); }
    });
    refreshGalEmpty();

    /* ---------------- Related animals ---------------- */
    var POOL = @json($relPool);
    var selected = @json($relSelected);
    var relChips = byId('relChips'), relAdd = byId('relAdd');
    function poolItem(id) { for (var k = 0; k < POOL.length; k++) { if (POOL[k].id === id) { return POOL[k]; } } return null; }
    function renderRel() {
        relChips.innerHTML = '';
        selected.forEach(function (id) {
            var a = poolItem(id); if (!a) { return; }
            var chip = document.createElement('span'); chip.className = 'ae-rel';
            var av;
            if (a.img) { av = document.createElement('img'); av.className = 'av'; av.src = a.img; av.alt = ''; av.onerror = function () { this.replaceWith(initialAv(a.name)); }; }
            else { av = initialAv(a.name); }
            var nm = document.createElement('span'); nm.className = 'n'; nm.textContent = a.name;
            var rm = document.createElement('button'); rm.type = 'button'; rm.setAttribute('aria-label', 'Remove ' + a.name); rm.textContent = '×';
            rm.addEventListener('click', function () { selected = selected.filter(function (x) { return x !== id; }); renderRel(); });
            var hid = document.createElement('input'); hid.type = 'hidden'; hid.name = 'related_animals[]'; hid.value = id;
            chip.appendChild(av); chip.appendChild(nm); chip.appendChild(rm); chip.appendChild(hid);
            relChips.appendChild(chip);
        });
        relAdd.innerHTML = '';
        var first = document.createElement('option'); first.value = ''; first.textContent = POOL.length ? '+ Add related animal…' : 'No other animals available';
        relAdd.appendChild(first);
        POOL.forEach(function (a) {
            if (selected.indexOf(a.id) > -1) { return; }
            var o = document.createElement('option'); o.value = a.id; o.textContent = a.name; relAdd.appendChild(o);
        });
    }
    function initialAv(name) { var s = document.createElement('span'); s.className = 'av'; s.textContent = (name || '?').charAt(0).toUpperCase(); return s; }
    relAdd.addEventListener('change', function () {
        var v = parseInt(relAdd.value, 10);
        if (v && selected.indexOf(v) < 0) { selected.push(v); }
        renderRel();
    });
    renderRel();

    /* ---------------- FAQs ---------------- */
    var faqNew = byId('faqNew'), faqTpl = byId('faqTpl'), faqEmpty = byId('faqEmpty'), fIdx = 0, MAX_FAQ = 6;
    function refreshFaqEmpty() { faqEmpty.style.display = ($$('.ae-faq').length || faqNew.children.length) ? 'none' : ''; }
    function addFaq(q, a) {
        if (faqNew.children.length >= MAX_FAQ) { window.alert('You can add up to ' + MAX_FAQ + ' new questions per save.'); return; }
        var i = fIdx++;
        var holder = document.createElement('div');
        holder.innerHTML = faqTpl.innerHTML.replace(/__i__/g, i);
        var el = holder.firstElementChild;
        if (q) { el.querySelector('input').value = q; }
        if (a) { el.querySelector('textarea').value = a; }
        faqNew.appendChild(el);
        refreshFaqEmpty();
        return el;
    }
    byId('faqAdd').addEventListener('click', function () {
        var el = addFaq(); if (el) { el.querySelector('input').focus(); }
    });
    document.addEventListener('click', function (e) {
        var d = e.target.closest('[data-faq-del]');
        if (d) { d.closest('.ae-faq').remove(); refreshFaqEmpty(); return; }
        var r = e.target.closest('[data-faq-rm]');
        if (r) {
            var box = r.closest('.ae-faq'), cb = box.querySelector('.ae-rm');
            cb.checked = !cb.checked; box.classList.toggle('is-removed', cb.checked);
            r.textContent = cb.checked ? 'Undo remove' : 'Remove';
        }
    });
    var oldNewFaqs = @json(old('new_faqs', []));
    if (oldNewFaqs && typeof oldNewFaqs === 'object') {
        Object.keys(oldNewFaqs).forEach(function (k) {
            var row = oldNewFaqs[k] || {};
            if (row.question || row.answer) { addFaq(row.question || '', row.answer || ''); }
        });
    }
    refreshFaqEmpty();

    /* ---------------- Submit ---------------- */
    var saveBtns = $$('[data-save]');
    form.addEventListener('submit', function (e) {
        syncDesc();
        var bad = null;
        [['name', 'Please enter the animal name.'], ['category', 'Please choose a category.'], ['conservation_status', 'Please enter the conservation status.']].forEach(function (r) {
            var el = byId(r[0]);
            if (el && !el.value.trim()) { el.classList.add('is-invalid'); setErr(el, r[1]); if (!bad) { bad = el; } }
        });
        if (descTA.value.trim() === '') {
            descBox.classList.add('is-invalid');
            var holder = descBox.parentNode, ex = holder.querySelector('.ae-err.js');
            if (!ex) { ex = document.createElement('div'); ex.className = 'ae-err js'; holder.appendChild(ex); }
            ex.textContent = 'Please write a full description.';
            if (!bad) { bad = editor; }
        }
        if (bad) {
            e.preventDefault();
            openTab(paneOf(bad));
            bad.focus();
            try { bad.scrollIntoView({ block: 'center', behavior: 'smooth' }); } catch (err) {}
            return;
        }
        saveBtns.forEach(function (b) { b.disabled = true; var s = b.querySelector('span'); if (s) { s.textContent = 'Saving…'; } });
    });
    window.addEventListener('pageshow', function () {
        saveBtns.forEach(function (b) { b.disabled = false; var s = b.querySelector('span'); if (s) { s.textContent = 'Save Changes'; } });
    });
})();
</script>
@endsection