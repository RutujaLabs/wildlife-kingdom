@extends('admin.layout')

@section('title', 'SEO Settings')
@section('page-title', 'SEO Settings')

@section('content')
<style>
    .seo-wrap, .seo-wrap * { box-sizing: border-box; }
    .seo-wrap { max-width: 1100px; }

    .seo-alert {
        margin-bottom: 18px;
        padding: 14px 18px;
        border-radius: 12px;
        background: #e8f5ee;
        color: #1b5e3f;
        font-weight: 600;
    }

    .seo-card {
        background: #fff;
        border: 1px solid #ece7db;
        border-radius: 20px;
        box-shadow: 0 8px 28px rgba(15, 61, 42, .06);
        overflow: hidden;
    }

    /* Info banner */
    .seo-note {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 18px 32px;
        background: #fdf6e9;
        border-bottom: 1px solid #f3e6c9;
        color: #5b4a24;
        font-size: .92rem;
        line-height: 1.55;
    }
    .seo-note svg { flex: 0 0 20px; width: 20px; height: 20px; margin-top: 2px; color: #e8891c; }
    .seo-note p { margin: 0; }

    .seo-section { padding: 30px 32px; }
    .seo-section + .seo-section { padding-top: 0; }
    .seo-head {
        display: flex;
        align-items: center;
        gap: 14px;
        margin: 0 0 24px;
        padding-bottom: 18px;
        border-bottom: 1px solid #f0ece2;
    }
    .seo-icon {
        flex: 0 0 44px;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        background: #fdf0dc;
        color: #e8891c;
    }
    .seo-icon svg { width: 22px; height: 22px; display: block; }
    .seo-head h2 { margin: 0; font-size: 1.1rem; line-height: 1.25; color: #0f3d2a; }
    .seo-head p { margin: 3px 0 0; font-size: .85rem; line-height: 1.3; color: #7a857f; }

    .seo-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 24px 28px;
    }
    .seo-full { grid-column: 1 / -1; }

    /* Fields */
    .seo-form .field {
        display: grid;
        grid-template-rows: auto 1fr auto;
        gap: 8px;
        margin: 0;
        min-width: 0;
    }
    .seo-form .field > label {
        display: block;
        margin: 0;
        font-weight: 600;
        font-size: .92rem;
        line-height: 1.3;
        color: #1d2a24;
    }
    .seo-form .field > label small { font-weight: 500; color: #7a857f; }
    .seo-form .field input[type="text"],
    .seo-form .field input[type="url"],
    .seo-form .field textarea {
        display: block;
        width: 100%;
        margin: 0;
        padding: 0 16px;
        height: 50px;
        font: inherit;
        font-size: .96rem;
        color: #1d2a24;
        background: #fbfaf6;
        border: 1.5px solid #e4dfd2;
        border-radius: 12px;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .seo-form .field input:not([type]) {
        display: block;
        width: 100%;
        margin: 0;
        padding: 0 16px;
        height: 50px;
        font: inherit;
        font-size: .96rem;
        color: #1d2a24;
        background: #fbfaf6;
        border: 1.5px solid #e4dfd2;
        border-radius: 12px;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .seo-form .field textarea {
        height: auto;
        min-height: 130px;
        padding: 13px 16px;
        line-height: 1.55;
        resize: vertical;
    }
    .seo-form .field input:hover,
    .seo-form .field textarea:hover { border-color: #cfc8b6; }
    .seo-form .field input:focus,
    .seo-form .field textarea:focus {
        outline: none;
        background: #fff;
        border-color: #1b5e3f;
        box-shadow: 0 0 0 4px rgba(27, 94, 63, .13);
    }
    .seo-form .field input::placeholder { color: #a8b0aa; }

    .seo-meta {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        min-height: 18px;
        font-size: .8rem;
        line-height: 1.3;
        color: #7a857f;
    }
    .seo-meta b { font-weight: 600; color: #1b5e3f; }
    .seo-error { font-size: .82rem; line-height: 1.3; color: #c0392b; }
    .seo-form .has-error input,
    .seo-form .has-error textarea { border-color: #c0392b; }

    /* Search preview */
    .seo-preview {
        padding: 18px 20px;
        background: #fbfaf6;
        border: 1.5px dashed #e0dac9;
        border-radius: 14px;
    }
    .seo-preview-label {
        margin: 0 0 12px;
        font-size: .82rem;
        font-weight: 600;
        color: #7a857f;
    }
    .seo-preview-url {
        font-size: .82rem;
        color: #3c6e52;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .seo-preview-title {
        margin: 4px 0 4px;
        font-size: 1.15rem;
        line-height: 1.35;
        font-weight: 600;
        color: #1a4fa0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .seo-preview-desc {
        margin: 0;
        font-size: .88rem;
        line-height: 1.55;
        color: #4d5a53;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Toggle rows */
    .seo-toggle {
        position: relative;
        display: flex;
        align-items: center;
        gap: 16px;
        height: 100%;
        padding: 18px 20px;
        margin: 0;
        background: #fbfaf6;
        border: 1.5px solid #e4dfd2;
        border-radius: 14px;
        cursor: pointer;
        transition: border-color .2s, background .2s;
    }
    .seo-toggle:hover { border-color: #cfc8b6; }
    .seo-toggle.is-on { background: #f1f8f3; border-color: #b9d8c6; }
    .seo-toggle .seo-check {
        position: absolute;
        opacity: 0;
        width: 1px;
        height: 1px;
        margin: 0;
        padding: 0;
        pointer-events: none;
    }
    .seo-track {
        position: relative;
        flex: 0 0 48px;
        width: 48px;
        height: 28px;
        border-radius: 999px;
        background: #d8d3c4;
        transition: background .2s;
    }
    .seo-track::after {
        content: "";
        position: absolute;
        top: 3px;
        left: 3px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .25);
        transition: transform .2s;
    }
    .seo-check:checked + .seo-track { background: #1b5e3f; }
    .seo-check:checked + .seo-track::after { transform: translateX(20px); }
    .seo-check:focus-visible + .seo-track { outline: 3px solid #f09a2f; outline-offset: 3px; }
    .seo-toggle-text { display: grid; gap: 3px; min-width: 0; }
    .seo-toggle-title { font-weight: 600; font-size: .95rem; line-height: 1.3; color: #1d2a24; }
    .seo-toggle-sub { font-size: .82rem; line-height: 1.4; color: #7a857f; }

    /* Action bar */
    .seo-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 14px;
        padding: 18px 32px;
        background: #faf8f2;
        border-top: 1px solid #ece7db;
    }
    .seo-save, .seo-reset {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 50px;
        padding: 0 30px;
        font: inherit;
        font-weight: 700;
        border-radius: 999px;
        cursor: pointer;
        transition: background .2s, transform .15s, box-shadow .15s;
    }
    .seo-save { color: #fff; background: #0f3d2a; border: 1.5px solid #0f3d2a; }
    .seo-save:hover {
        background: #1b5e3f;
        border-color: #1b5e3f;
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(15, 61, 42, .25);
    }
    .seo-reset { color: #6f7b74; background: transparent; border: 1.5px solid #e4dfd2; font-weight: 600; }
    .seo-reset:hover { background: #f3efe4; }
    .seo-save:focus-visible,
    .seo-reset:focus-visible { outline: 3px solid #f09a2f; outline-offset: 3px; }

    @media (max-width: 800px) {
        .seo-card { border-radius: 16px; }
        .seo-note { padding: 16px 18px; }
        .seo-section { padding: 22px 18px; }
        .seo-grid { grid-template-columns: 1fr; gap: 20px; }
        .seo-actions { flex-direction: column-reverse; align-items: stretch; padding: 16px 18px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .seo-wrap * { transition: none !important; }
    }
</style>

<div class="seo-wrap">
    @if (session('success'))
        <div class="seo-alert">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.settings.seo.update') }}" class="seo-form seo-card" id="seoForm">
        @csrf
        @method('PUT')

        <div class="seo-note">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8h.01"/></svg>
            <p>These defaults are used on public pages that do not define their own SEO values. Animal-specific SEO settings continue to take precedence.</p>
        </div>

        {{-- Search appearance --}}
        <section class="seo-section">
            <div class="seo-head">
                <span class="seo-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"/><path d="M20 20l-4-4"/></svg>
                </span>
                <div>
                    <h2>Search appearance</h2>
                    <p>Google jaise search engines me aapki site aise dikhegi</p>
                </div>
            </div>

            <div class="seo-grid">
                <div class="field seo-full @error('seo_site_title') has-error @enderror">
                    <label for="seo_site_title">Default site title</label>
                    <input id="seo_site_title" name="seo_site_title" maxlength="70"
                           value="{{ old('seo_site_title', $settings['seo_site_title']) }}" required>
                    <div class="seo-meta">
                        @error('seo_site_title') <span class="seo-error">{{ $message }}</span>
                        @else <span>Browser tab aur search result ka title</span> @enderror
                        <span><b data-count="seo_site_title">0</b>/70</span>
                    </div>
                </div>

                <div class="field seo-full @error('seo_site_description') has-error @enderror">
                    <label for="seo_site_description">Default meta description</label>
                    <textarea id="seo_site_description" name="seo_site_description" maxlength="320" required>{{ old('seo_site_description', $settings['seo_site_description']) }}</textarea>
                    <div class="seo-meta">
                        @error('seo_site_description') <span class="seo-error">{{ $message }}</span>
                        @else <span>Search result me title ke neeche dikhne wala summary</span> @enderror
                        <span><b data-count="seo_site_description">0</b>/320</span>
                    </div>
                </div>

                <div class="field seo-full @error('seo_canonical_base_url') has-error @enderror">
                    <label for="seo_canonical_base_url">Canonical base URL <small>(optional)</small></label>
                    <input id="seo_canonical_base_url" name="seo_canonical_base_url" type="url"
                           value="{{ old('seo_canonical_base_url', $settings['seo_canonical_base_url']) }}"
                           placeholder="https://example.com">
                    <div class="seo-meta">
                        @error('seo_canonical_base_url') <span class="seo-error">{{ $message }}</span>
                        @else <span>Aapki site ka main address, bina last slash ke</span> @enderror
                    </div>
                </div>

                <div class="seo-full seo-preview" aria-live="polite">
                    <p class="seo-preview-label">Search preview</p>
                    <div class="seo-preview-url" id="pvUrl"></div>
                    <div class="seo-preview-title" id="pvTitle"></div>
                    <p class="seo-preview-desc" id="pvDesc"></p>
                </div>
            </div>
        </section>

        {{-- Indexing --}}
        <section class="seo-section">
            <div class="seo-head">
                <span class="seo-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                </span>
                <div>
                    <h2>Indexing rules</h2>
                    <p>Search engines ko kya karne dena hai</p>
                </div>
            </div>

            <div class="seo-grid">
                <label class="seo-toggle">
                    <input type="hidden" name="seo_default_noindex" value="0">
                    <input class="seo-check" type="checkbox" name="seo_default_noindex" value="1"
                           {{ old('seo_default_noindex', $settings['seo_default_noindex']) ? 'checked' : '' }}>
                    <span class="seo-track"></span>
                    <span class="seo-toggle-text">
                        <span class="seo-toggle-title">Apply noindex by default</span>
                        <span class="seo-toggle-sub">Pages search results me nahi dikhenge</span>
                    </span>
                </label>

                <label class="seo-toggle">
                    <input type="hidden" name="seo_default_nofollow" value="0">
                    <input class="seo-check" type="checkbox" name="seo_default_nofollow" value="1"
                           {{ old('seo_default_nofollow', $settings['seo_default_nofollow']) ? 'checked' : '' }}>
                    <span class="seo-track"></span>
                    <span class="seo-toggle-text">
                        <span class="seo-toggle-title">Apply nofollow by default</span>
                        <span class="seo-toggle-sub">Search engines page ke links follow nahi karenge</span>
                    </span>
                </label>
            </div>
        </section>

        <div class="seo-actions">
            <button type="reset" class="seo-reset">Reset</button>
            <button type="submit" class="seo-save">Save SEO Settings</button>
        </div>
    </form>
</div>

<script>
(function () {
    var form  = document.getElementById('seoForm');
    var title = document.getElementById('seo_site_title');
    var desc  = document.getElementById('seo_site_description');
    var base  = document.getElementById('seo_canonical_base_url');

    function cut(str, max) {
        str = (str || '').trim();
        return str.length > max ? str.slice(0, max - 1).trim() + '\u2026' : str;
    }

    function renderPreview() {
        var url = (base.value || '').trim().replace(/\/+$/, '') || 'https://example.com';
        document.getElementById('pvUrl').textContent   = url;
        document.getElementById('pvTitle').textContent = cut(title.value, 60) || 'Your site title';
        document.getElementById('pvDesc').textContent  = cut(desc.value, 160) || 'Your meta description will appear here.';
    }

    function syncToggles() {
        form.querySelectorAll('.seo-toggle').forEach(function (row) {
            var box = row.querySelector('.seo-check');
            row.classList.toggle('is-on', box.checked);
        });
    }

    form.querySelectorAll('[data-count]').forEach(function (el) {
        var field = document.getElementById(el.dataset.count);
        if (!field) return;
        var update = function () { el.textContent = field.value.length; };
        field.addEventListener('input', update);
        update();
    });

    [title, desc, base].forEach(function (f) { f.addEventListener('input', renderPreview); });
    form.querySelectorAll('.seo-check').forEach(function (c) { c.addEventListener('change', syncToggles); });

    form.addEventListener('reset', function () {
        setTimeout(function () {
            form.querySelectorAll('[data-count]').forEach(function (el) {
                el.textContent = document.getElementById(el.dataset.count).value.length;
            });
            renderPreview();
            syncToggles();
        }, 0);
    });

    renderPreview();
    syncToggles();
})();
</script>
@endsection