@extends('admin.layout')

@section('title', 'Website Settings')
@section('page-title', 'Website Settings')

@section('content')
<style>
    .ws-wrap, .ws-wrap * { box-sizing: border-box; }
    .ws-wrap { max-width: 1100px; }

    .ws-alert {
        margin-bottom: 18px;
        padding: 14px 18px;
        border-radius: 12px;
        background: #e8f5ee;
        color: #1b5e3f;
        font-weight: 600;
    }

    .ws-card {
        background: #fff;
        border: 1px solid #ece7db;
        border-radius: 20px;
        box-shadow: 0 8px 28px rgba(15, 61, 42, .06);
        overflow: hidden;
    }

    .ws-note {
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
    .ws-note svg { flex: 0 0 20px; width: 20px; height: 20px; margin-top: 2px; color: #e8891c; }
    .ws-note p { margin: 0; }

    .ws-section { padding: 30px 32px; }
    .ws-section + .ws-section { padding-top: 0; }
    .ws-head {
        display: flex;
        align-items: center;
        gap: 14px;
        margin: 0 0 24px;
        padding-bottom: 18px;
        border-bottom: 1px solid #f0ece2;
    }
    .ws-icon {
        flex: 0 0 44px;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        background: #fdf0dc;
        color: #e8891c;
    }
    .ws-icon svg { width: 22px; height: 22px; display: block; }
    .ws-head h2 { margin: 0; font-size: 1.1rem; line-height: 1.25; color: #0f3d2a; }
    .ws-head p { margin: 3px 0 0; font-size: .85rem; line-height: 1.3; color: #7a857f; }

    /* Fixed 2-column grid: har row ke dono fields barabar */
    .ws-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 24px 28px;
        align-items: stretch;
    }

    .ws-form .field {
        display: grid;
        grid-template-rows: auto 1fr auto;
        gap: 8px;
        margin: 0;
        min-width: 0;
        width: auto;
    }
    .ws-form .field > label {
        display: block;
        margin: 0;
        font-weight: 600;
        font-size: .92rem;
        line-height: 1.3;
        color: #1d2a24;
    }
    .ws-form .field > label small { font-weight: 500; color: #7a857f; }
    .ws-form .field input,
    .ws-form .field textarea {
        display: block;
        width: 100%;
        max-width: none;
        height: 50px;
        margin: 0;
        padding: 0 16px;
        font: inherit;
        font-size: .96rem;
        color: #1d2a24;
        background: #fbfaf6;
        border: 1.5px solid #e4dfd2;
        border-radius: 12px;
        transition: border-color .2s, box-shadow .2s, background .2s;
    }
    .ws-form .field textarea {
        height: auto;
        min-height: 130px;
        padding: 13px 16px;
        line-height: 1.55;
        resize: vertical;
    }
    .ws-form .field input:hover,
    .ws-form .field textarea:hover { border-color: #cfc8b6; }
    .ws-form .field input:focus,
    .ws-form .field textarea:focus {
        outline: none;
        background: #fff;
        border-color: #1b5e3f;
        box-shadow: 0 0 0 4px rgba(27, 94, 63, .13);
    }
    .ws-form .field input::placeholder,
    .ws-form .field textarea::placeholder { color: #a8b0aa; }

    .ws-meta {
        min-height: 18px;
        font-size: .8rem;
        line-height: 1.3;
        color: #7a857f;
    }
    .ws-error { font-size: .82rem; line-height: 1.3; color: #c0392b; }
    .ws-form .has-error input,
    .ws-form .has-error textarea { border-color: #c0392b; }

    .ws-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 14px;
        padding: 18px 32px;
        background: #faf8f2;
        border-top: 1px solid #ece7db;
    }
    .ws-save, .ws-reset {
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
    .ws-save { color: #fff; background: #0f3d2a; border: 1.5px solid #0f3d2a; }
    .ws-save:hover {
        background: #1b5e3f;
        border-color: #1b5e3f;
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(15, 61, 42, .25);
    }
    .ws-reset { color: #6f7b74; background: transparent; border: 1.5px solid #e4dfd2; font-weight: 600; }
    .ws-reset:hover { background: #f3efe4; }
    .ws-save:focus-visible,
    .ws-reset:focus-visible { outline: 3px solid #f09a2f; outline-offset: 3px; }

    @media (max-width: 800px) {
        .ws-card { border-radius: 16px; }
        .ws-note { padding: 16px 18px; }
        .ws-section { padding: 22px 18px; }
        .ws-grid { grid-template-columns: 1fr; gap: 20px; }
        .ws-actions { flex-direction: column-reverse; align-items: stretch; padding: 16px 18px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .ws-wrap * { transition: none !important; }
    }
</style>

<div class="ws-wrap">
    @if (session('success'))
        <div class="ws-alert">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.settings.website.update') }}" class="ws-form ws-card" id="wsForm">
        @csrf
        @method('PUT')

        <div class="ws-note">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 11v5"/><path d="M12 8h.01"/></svg>
            <p>Manage the website identity and visitor contact details displayed in the existing public layout.</p>
        </div>

        {{-- Identity --}}
        <section class="ws-section">
            <div class="ws-head">
                <span class="ws-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3a14 14 0 010 18"/><path d="M12 3a14 14 0 000 18"/></svg>
                </span>
                <div>
                    <h2>Website identity</h2>
                    <p>Aapki site ka naam aur tagline</p>
                </div>
            </div>

            <div class="ws-grid">
                <div class="field @error('site_name') has-error @enderror">
                    <label for="site_name">Website name</label>
                    <input id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required>
                    <div class="ws-meta">
                        @error('site_name') <span class="ws-error">{{ $message }}</span>
                        @else <span>Header aur footer me ye naam dikhega</span> @enderror
                    </div>
                </div>

                <div class="field @error('site_tagline') has-error @enderror">
                    <label for="site_tagline">Website tagline <small>(optional)</small></label>
                    <input id="site_tagline" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline']) }}">
                    <div class="ws-meta">
                        @error('site_tagline') <span class="ws-error">{{ $message }}</span>
                        @else <span>Naam ke saath ek chhoti line</span> @enderror
                    </div>
                </div>
            </div>
        </section>

        {{-- Contact --}}
        <section class="ws-section">
            <div class="ws-head">
                <span class="ws-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 21s7-6.2 7-11a7 7 0 10-14 0c0 4.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg>
                </span>
                <div>
                    <h2>Visitor contact</h2>
                    <p>Visitors aap se kaise judenge aur kab aa sakte hain</p>
                </div>
            </div>

            <div class="ws-grid">
                <div class="field @error('contact_email') has-error @enderror">
                    <label for="contact_email">Contact email</label>
                    <input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $settings['contact_email']) }}" required>
                    <div class="ws-meta">
                        @error('contact_email') <span class="ws-error">{{ $message }}</span>
                        @else <span>Visitors isi email par message bhejenge</span> @enderror
                    </div>
                </div>

                <div class="field @error('contact_phone') has-error @enderror">
                    <label for="contact_phone">Contact phone <small>(optional)</small></label>
                    <input id="contact_phone" name="contact_phone" type="text" inputmode="tel" placeholder="+91 00000 00000"
                           value="{{ old('contact_phone', $settings['contact_phone']) }}">
                    <div class="ws-meta">
                        @error('contact_phone') <span class="ws-error">{{ $message }}</span>
                        @else <span>Country code ke saath likho</span> @enderror
                    </div>
                </div>

                <div class="field @error('contact_address') has-error @enderror">
                    <label for="contact_address">Contact address</label>
                    <textarea id="contact_address" name="contact_address" required>{{ old('contact_address', $settings['contact_address']) }}</textarea>
                    <div class="ws-meta">
                        @error('contact_address') <span class="ws-error">{{ $message }}</span>
                        @else <span>Har line alag likho, jaise road aur city</span> @enderror
                    </div>
                </div>

                <div class="field @error('opening_hours') has-error @enderror">
                    <label for="opening_hours">Opening hours <small>(optional)</small></label>
                    <textarea id="opening_hours" name="opening_hours" placeholder="Mon - Fri: 9:00 AM - 6:00 PM&#10;Sat - Sun: 10:00 AM - 4:00 PM">{{ old('opening_hours', $settings['opening_hours']) }}</textarea>
                    <div class="ws-meta">
                        @error('opening_hours') <span class="ws-error">{{ $message }}</span>
                        @else <span>Din aur time, har line me ek</span> @enderror
                    </div>
                </div>
            </div>
        </section>

        <div class="ws-actions">
            <button type="reset" class="ws-reset">Reset</button>
            <button type="submit" class="ws-save">Save Website Settings</button>
        </div>
    </form>
</div>
@endsection