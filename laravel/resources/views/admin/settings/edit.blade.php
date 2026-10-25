@extends('admin.layout')

@section('title', 'Pages & Content')
@section('page-title', 'Pages & Content')

@section('content')
@php
    // Pehle wo tab kholo jisme validation error hai
    $activeTab = 'homepage';
    if ($errors->hasAny(['footer_about'])) $activeTab = 'footer';
    if ($errors->hasAny(['contact_email', 'contact_address'])) $activeTab = 'contact';
    if ($errors->hasAny(['home_eyebrow', 'home_heading', 'home_intro'])) $activeTab = 'homepage';
@endphp

<style>
    .pc-wrap, .pc-wrap * { box-sizing: border-box; }
    .pc-wrap { max-width: 1100px; }

    .pc-alert {
        margin-bottom: 18px;
        padding: 14px 18px;
        border-radius: 12px;
        background: #e8f5ee;
        color: #1b5e3f;
        font-weight: 600;
    }

    /* ---------- Card ---------- */
    .pc-card {
        background: #fff;
        border: 1px solid #ece7db;
        border-radius: 20px;
        box-shadow: 0 8px 28px rgba(15, 61, 42, .06);
        overflow: hidden;
    }

    /* ---------- Tabs ---------- */
    .pc-tabs {
        display: flex;
        gap: 6px;
        padding: 10px 14px 0;
        background: #faf8f2;
        border-bottom: 1px solid #ece7db;
        overflow-x: auto;
    }
    .pc-tab {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        height: 52px;
        padding: 0 22px;
        margin: 0;
        font: inherit;
        font-weight: 600;
        font-size: .95rem;
        white-space: nowrap;
        color: #6f7b74;
        background: transparent;
        border: 0;
        border-radius: 12px 12px 0 0;
        cursor: pointer;
        transition: color .2s, background .2s;
    }
    .pc-tab svg { width: 20px; height: 20px; flex: 0 0 20px; }
    .pc-tab:hover { color: #0f3d2a; background: #f3efe4; }
    .pc-tab[aria-selected="true"] {
        color: #0f3d2a;
        background: #fff;
        box-shadow: inset 0 3px 0 #f09a2f;
    }
    .pc-tab[aria-selected="true"] svg { color: #e8891c; }
    .pc-tab:focus-visible { outline: 3px solid #f09a2f; outline-offset: -3px; }
    .pc-tab .pc-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #c0392b;
        display: none;
    }
    .pc-tab.has-error .pc-dot { display: inline-block; }

    /* ---------- Panels ---------- */
    .pc-panel { display: none; padding: 30px 32px 34px; }
    .pc-panel.is-active { display: block; }
    .pc-panel-note { margin: 0 0 24px; font-size: .9rem; color: #7a857f; }

    .pc-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 24px 28px;
    }
    .pc-full { grid-column: 1 / -1; }

    .pc-form .field {
        display: grid;
        grid-template-rows: auto 1fr auto;
        gap: 8px;
        margin: 0;
        min-width: 0;
    }
    .pc-form .field label {
        display: block;
        margin: 0;
        font-weight: 600;
        font-size: .92rem;
        line-height: 1.3;
        color: #1d2a24;
    }
    .pc-form input,
    .pc-form textarea {
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
    .pc-form textarea {
        height: auto;
        min-height: 130px;
        padding: 13px 16px;
        line-height: 1.55;
        resize: vertical;
    }
    .pc-form input:hover,
    .pc-form textarea:hover { border-color: #cfc8b6; }
    .pc-form input:focus,
    .pc-form textarea:focus {
        outline: none;
        background: #fff;
        border-color: #1b5e3f;
        box-shadow: 0 0 0 4px rgba(27, 94, 63, .13);
    }

    .pc-meta {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        min-height: 18px;
        font-size: .8rem;
        line-height: 1.3;
        color: #7a857f;
    }
    .pc-meta b { font-weight: 600; color: #1b5e3f; }
    .pc-error { font-size: .82rem; line-height: 1.3; color: #c0392b; }
    .pc-form .has-error input,
    .pc-form .has-error textarea { border-color: #c0392b; }

    /* ---------- Action bar (hamesha dikhta hai) ---------- */
    .pc-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 14px;
        padding: 18px 32px;
        background: #faf8f2;
        border-top: 1px solid #ece7db;
    }
    .pc-save, .pc-reset {
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
    .pc-save { color: #fff; background: #0f3d2a; border: 1.5px solid #0f3d2a; }
    .pc-save:hover {
        background: #1b5e3f;
        border-color: #1b5e3f;
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(15, 61, 42, .25);
    }
    .pc-reset { color: #6f7b74; background: transparent; border: 1.5px solid #e4dfd2; font-weight: 600; }
    .pc-reset:hover { background: #f3efe4; }
    .pc-save:focus-visible,
    .pc-reset:focus-visible { outline: 3px solid #f09a2f; outline-offset: 3px; }

    @media (max-width: 800px) {
        .pc-card { border-radius: 16px; }
        .pc-tabs { padding: 8px 8px 0; }
        .pc-tab { padding: 0 16px; }
        .pc-panel { padding: 22px 18px 26px; }
        .pc-grid { grid-template-columns: 1fr; gap: 20px; }
        .pc-actions { flex-direction: column-reverse; align-items: stretch; padding: 16px 18px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .pc-wrap * { transition: none !important; }
    }
</style>

<div class="pc-wrap">
    @if (session('success'))
        <div class="pc-alert">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}" class="pc-form pc-card" id="pcForm">
        @csrf
        @method('PUT')

        {{-- Tabs --}}
        <div class="pc-tabs" role="tablist" aria-label="Website content sections">
            <button type="button" class="pc-tab {{ $errors->hasAny(['home_eyebrow','home_heading','home_intro']) ? 'has-error' : '' }}"
                    role="tab" id="tab-homepage" data-tab="homepage" aria-controls="panel-homepage"
                    aria-selected="{{ $activeTab === 'homepage' ? 'true' : 'false' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/></svg>
                Homepage <span class="pc-dot"></span>
            </button>
            <button type="button" class="pc-tab {{ $errors->has('footer_about') ? 'has-error' : '' }}"
                    role="tab" id="tab-footer" data-tab="footer" aria-controls="panel-footer"
                    aria-selected="{{ $activeTab === 'footer' ? 'true' : 'false' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 15h18"/></svg>
                Footer <span class="pc-dot"></span>
            </button>
            <button type="button" class="pc-tab {{ $errors->hasAny(['contact_email','contact_address']) ? 'has-error' : '' }}"
                    role="tab" id="tab-contact" data-tab="contact" aria-controls="panel-contact"
                    aria-selected="{{ $activeTab === 'contact' ? 'true' : 'false' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/></svg>
                Contact <span class="pc-dot"></span>
            </button>
        </div>

        {{-- Homepage --}}
        <section class="pc-panel {{ $activeTab === 'homepage' ? 'is-active' : '' }}" role="tabpanel" id="panel-homepage" aria-labelledby="tab-homepage">
            <p class="pc-panel-note">Website ki pehli screen par dikhne wala text.</p>
            <div class="pc-grid">
                <div class="field @error('home_eyebrow') has-error @enderror">
                    <label for="home_eyebrow">Homepage eyebrow</label>
                    <input id="home_eyebrow" name="home_eyebrow" maxlength="60"
                           value="{{ old('home_eyebrow', $settings['home_eyebrow']) }}" required>
                    <div class="pc-meta">
                        @error('home_eyebrow') <span class="pc-error">{{ $message }}</span>
                        @else <span>Heading ke upar chhota text</span> @enderror
                    </div>
                </div>

                <div class="field @error('home_heading') has-error @enderror">
                    <label for="home_heading">Homepage heading</label>
                    <input id="home_heading" name="home_heading" maxlength="120"
                           value="{{ old('home_heading', $settings['home_heading']) }}" required>
                    <div class="pc-meta">
                        @error('home_heading') <span class="pc-error">{{ $message }}</span>
                        @else <span>Main bada heading</span> @enderror
                    </div>
                </div>

                <div class="field pc-full @error('home_intro') has-error @enderror">
                    <label for="home_intro">Homepage introduction</label>
                    <textarea id="home_intro" name="home_intro" maxlength="300" required>{{ old('home_intro', $settings['home_intro']) }}</textarea>
                    <div class="pc-meta">
                        @error('home_intro') <span class="pc-error">{{ $message }}</span>
                        @else <span>Heading ke neeche short intro</span> @enderror
                        <span><b data-count="home_intro">0</b>/300</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Footer --}}
        <section class="pc-panel {{ $activeTab === 'footer' ? 'is-active' : '' }}" role="tabpanel" id="panel-footer" aria-labelledby="tab-footer">
            <p class="pc-panel-note">Website ke sabse neeche dikhne wali details.</p>
            <div class="pc-grid">
                <div class="field pc-full @error('footer_about') has-error @enderror">
                    <label for="footer_about">Footer description</label>
                    <textarea id="footer_about" name="footer_about" maxlength="300" required>{{ old('footer_about', $settings['footer_about']) }}</textarea>
                    <div class="pc-meta">
                        @error('footer_about') <span class="pc-error">{{ $message }}</span>
                        @else <span>Website ke baare me 1-2 line</span> @enderror
                        <span><b data-count="footer_about">0</b>/300</span>
                    </div>
                </div>
            </div>
        </section>

        {{-- Contact --}}
        <section class="pc-panel {{ $activeTab === 'contact' ? 'is-active' : '' }}" role="tabpanel" id="panel-contact" aria-labelledby="tab-contact">
            <p class="pc-panel-note">Visitors aap tak yahan se pahunchenge.</p>
            <div class="pc-grid">
                <div class="field pc-full @error('contact_email') has-error @enderror">
                    <label for="contact_email">Contact email</label>
                    <input id="contact_email" name="contact_email" type="email"
                           value="{{ old('contact_email', $settings['contact_email']) }}" required>
                    <div class="pc-meta">
                        @error('contact_email') <span class="pc-error">{{ $message }}</span>
                        @else <span>Visitors isi email par message bhejenge</span> @enderror
                    </div>
                </div>

                <div class="field pc-full @error('contact_address') has-error @enderror">
                    <label for="contact_address">Contact address</label>
                    <textarea id="contact_address" name="contact_address" required>{{ old('contact_address', $settings['contact_address']) }}</textarea>
                    <div class="pc-meta">
                        @error('contact_address') <span class="pc-error">{{ $message }}</span>
                        @else <span>Har line alag likho, jaise road aur city</span> @enderror
                    </div>
                </div>
            </div>
        </section>

        {{-- Save bar: har tab me neeche dikhta hai --}}
        <div class="pc-actions">
            <button type="reset" class="pc-reset">Reset</button>
            <button type="submit" class="pc-save">Save Website Content</button>
        </div>
    </form>
</div>

<script>
(function () {
    var form   = document.getElementById('pcForm');
    var tabs   = Array.prototype.slice.call(form.querySelectorAll('.pc-tab'));
    var panels = Array.prototype.slice.call(form.querySelectorAll('.pc-panel'));

    function openTab(name, focus) {
        tabs.forEach(function (t) {
            var on = t.dataset.tab === name;
            t.setAttribute('aria-selected', on ? 'true' : 'false');
            t.tabIndex = on ? 0 : -1;
            if (on && focus) t.focus();
        });
        panels.forEach(function (p) {
            p.classList.toggle('is-active', p.id === 'panel-' + name);
        });
        try { history.replaceState(null, '', '#' + name); } catch (e) {}
    }

    tabs.forEach(function (t, i) {
        t.addEventListener('click', function () { openTab(t.dataset.tab, false); });
        t.addEventListener('keydown', function (e) {
            var next = null;
            if (e.key === 'ArrowRight') next = tabs[(i + 1) % tabs.length];
            if (e.key === 'ArrowLeft')  next = tabs[(i - 1 + tabs.length) % tabs.length];
            if (next) { e.preventDefault(); openTab(next.dataset.tab, true); }
        });
    });

    // Page reload par same tab (sirf jab server error na ho)
    var hash = location.hash.replace('#', '');
    var hasServerError = tabs.some(function (t) { return t.classList.contains('has-error'); });
    if (!hasServerError && tabs.some(function (t) { return t.dataset.tab === hash; })) {
        openTab(hash, false);
    } else {
        tabs.forEach(function (t) { t.tabIndex = t.getAttribute('aria-selected') === 'true' ? 0 : -1; });
    }

    // Chhupe hue tab me empty required field ho to us tab ko khol do
    form.addEventListener('invalid', function (e) {
        var panel = e.target.closest('.pc-panel');
        if (panel && !panel.classList.contains('is-active')) {
            openTab(panel.id.replace('panel-', ''), false);
        }
    }, true);

    // Character counters
    form.querySelectorAll('[data-count]').forEach(function (el) {
        var field = document.getElementById(el.dataset.count);
        if (!field) return;
        var update = function () { el.textContent = field.value.length; };
        field.addEventListener('input', update);
        form.addEventListener('reset', function () { setTimeout(update, 0); });
        update();
    });
})();
</script>
@endsection