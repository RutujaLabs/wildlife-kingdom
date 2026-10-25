@extends('admin.layout')

@section('title', 'API Settings')
@section('page-title', 'API Settings')

@section('content')
<style>
    .api-wrap, .api-wrap * { box-sizing: border-box; }
    .api-wrap { max-width: 1100px; }

    .api-alert {
        margin-bottom: 18px;
        padding: 14px 18px;
        border-radius: 12px;
        background: #e8f5ee;
        color: #1b5e3f;
        font-weight: 600;
    }

    .api-card {
        background: #fff;
        border: 1px solid #ece7db;
        border-radius: 20px;
        box-shadow: 0 8px 28px rgba(15, 61, 42, .06);
        overflow: hidden;
    }

    /* Security note */
    .api-note {
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
    .api-note svg { flex: 0 0 20px; width: 20px; height: 20px; margin-top: 2px; color: #e8891c; }
    .api-note p { margin: 0; }

    .api-section { padding: 30px 32px; }
    .api-section + .api-section { padding-top: 0; }
    .api-head {
        display: flex;
        align-items: center;
        gap: 14px;
        margin: 0 0 24px;
        padding-bottom: 18px;
        border-bottom: 1px solid #f0ece2;
    }
    .api-icon {
        flex: 0 0 44px;
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        background: #fdf0dc;
        color: #e8891c;
    }
    .api-icon svg { width: 22px; height: 22px; display: block; }
    .api-head h2 { margin: 0; font-size: 1.1rem; line-height: 1.25; color: #0f3d2a; }
    .api-head p { margin: 3px 0 0; font-size: .85rem; line-height: 1.3; color: #7a857f; }

    /* Status pill */
    .api-status {
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        height: 34px;
        padding: 0 14px;
        border-radius: 999px;
        font-size: .85rem;
        font-weight: 600;
        white-space: nowrap;
    }
    .api-status::before {
        content: "";
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
    }
    .api-status.is-ok { background: #e8f5ee; color: #1b5e3f; }
    .api-status.is-no { background: #f3f0e6; color: #7a857f; }

    .api-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 24px 28px;
    }
    .api-full { grid-column: 1 / -1; }

    /* Fields */
    .api-form .field {
        display: grid;
        grid-template-rows: auto auto auto;
        align-content: start;
        gap: 8px;
        margin: 0;
        min-width: 0;
    }
    .api-form .field > label {
        display: block;
        margin: 0;
        font-weight: 600;
        font-size: .92rem;
        line-height: 1.3;
        color: #1d2a24;
    }
    .api-form .field input[type="password"],
    .api-form .field input[type="text"],
    .api-form .field select {
        display: block;
        width: 100%;
        height: 50px;
        margin: 0;
        padding: 0 16px;
        font: inherit;
        font-size: .96rem;
        color: #1d2a24;
        background-color: #fbfaf6;
        border: 1.5px solid #e4dfd2;
        border-radius: 12px;
        transition: border-color .2s, box-shadow .2s, background-color .2s;
    }
    .api-form .field select {
        appearance: none;
        -webkit-appearance: none;
        padding-right: 44px;
        cursor: pointer;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none' stroke='%237a857f' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
    }
    .api-form .field input:hover,
    .api-form .field select:hover { border-color: #cfc8b6; }
    .api-form .field input:focus,
    .api-form .field select:focus {
        outline: none;
        background-color: #fff;
        border-color: #1b5e3f;
        box-shadow: 0 0 0 4px rgba(27, 94, 63, .13);
    }
    .api-form .field input::placeholder { color: #a8b0aa; }

    .api-pass { position: relative; }
    .api-pass input { padding-right: 56px !important; }
    .api-eye {
        position: absolute;
        top: 50%;
        right: 8px;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        display: grid;
        place-items: center;
        padding: 0;
        color: #7a857f;
        background: transparent;
        border: 0;
        border-radius: 10px;
        cursor: pointer;
        transition: background .2s, color .2s;
    }
    .api-eye:hover { background: #f0ece2; color: #0f3d2a; }
    .api-eye:focus-visible { outline: 3px solid #f09a2f; outline-offset: 1px; }
    .api-eye svg { width: 20px; height: 20px; }
    .api-eye .eye-off { display: none; }
    .api-eye.is-shown .eye-on { display: none; }
    .api-eye.is-shown .eye-off { display: block; }

    .api-meta {
        min-height: 18px;
        font-size: .8rem;
        line-height: 1.3;
        color: #7a857f;
    }
    .api-error { font-size: .82rem; line-height: 1.3; color: #c0392b; }
    .api-form .has-error input,
    .api-form .has-error select { border-color: #c0392b; }

    /* Toggle rows */
    .api-toggle {
        position: relative;
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 18px 20px;
        margin: 0;
        background: #fbfaf6;
        border: 1.5px solid #e4dfd2;
        border-radius: 14px;
        cursor: pointer;
        transition: border-color .2s, background .2s;
    }
    .api-toggle:hover { border-color: #cfc8b6; }
    .api-toggle.is-on { background: #f1f8f3; border-color: #b9d8c6; }
    .api-toggle.is-danger.is-on { background: #fdf1ef; border-color: #f0c4be; }
    .api-toggle .api-check {
        position: absolute;
        opacity: 0;
        width: 1px;
        height: 1px;
        margin: 0;
        padding: 0;
        pointer-events: none;
    }
    .api-track {
        position: relative;
        flex: 0 0 48px;
        width: 48px;
        height: 28px;
        border-radius: 999px;
        background: #d8d3c4;
        transition: background .2s;
    }
    .api-track::after {
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
    .api-check:checked + .api-track { background: #1b5e3f; }
    .api-toggle.is-danger .api-check:checked + .api-track { background: #c0392b; }
    .api-check:checked + .api-track::after { transform: translateX(20px); }
    .api-check:focus-visible + .api-track { outline: 3px solid #f09a2f; outline-offset: 3px; }
    .api-toggle-text { display: grid; gap: 3px; min-width: 0; }
    .api-toggle-title { font-weight: 600; font-size: .95rem; line-height: 1.3; color: #1d2a24; }
    .api-toggle-sub { font-size: .82rem; line-height: 1.4; color: #7a857f; }

    /* Action bar */
    .api-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 14px;
        padding: 18px 32px;
        background: #faf8f2;
        border-top: 1px solid #ece7db;
    }
    .api-save, .api-reset {
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
    .api-save { color: #fff; background: #0f3d2a; border: 1.5px solid #0f3d2a; }
    .api-save:hover {
        background: #1b5e3f;
        border-color: #1b5e3f;
        transform: translateY(-1px);
        box-shadow: 0 8px 18px rgba(15, 61, 42, .25);
    }
    .api-reset { color: #6f7b74; background: transparent; border: 1.5px solid #e4dfd2; font-weight: 600; }
    .api-reset:hover { background: #f3efe4; }
    .api-save:focus-visible,
    .api-reset:focus-visible { outline: 3px solid #f09a2f; outline-offset: 3px; }

    @media (max-width: 800px) {
        .api-card { border-radius: 16px; }
        .api-note { padding: 16px 18px; }
        .api-section { padding: 22px 18px; }
        .api-head { flex-wrap: wrap; }
        .api-status { margin-left: 58px; }
        .api-grid { grid-template-columns: 1fr; gap: 20px; }
        .api-actions { flex-direction: column-reverse; align-items: stretch; padding: 16px 18px; }
    }
    @media (prefers-reduced-motion: reduce) {
        .api-wrap * { transition: none !important; }
    }
</style>

<div class="api-wrap">
    @if (session('success'))
        <div class="api-alert">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.settings.api.update') }}" class="api-form api-card" id="apiForm">
        @csrf
        @method('PUT')

        <div class="api-note">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 018 0v4"/></svg>
            <p>Store an AI provider credential for future backend integrations. The credential is encrypted at rest and is never shown after saving or sent to public pages. This setting does not enable an AI feature by itself.</p>
        </div>

        {{-- Credential --}}
        <section class="api-section">
            <div class="api-head">
                <span class="api-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="8" cy="15" r="4"/><path d="M10.8 12.2L20 3"/><path d="M16 7l3 3"/></svg>
                </span>
                <div>
                    <h2>Provider credential</h2>
                    <p>Kis AI provider ka key save karna hai</p>
                </div>
                <span class="api-status {{ $settings['api_key_configured'] ? 'is-ok' : 'is-no' }}">
                    {{ $settings['api_key_configured'] ? 'Configured' : 'Not configured' }}
                </span>
            </div>

            <div class="api-grid">
                <div class="field @error('ai_provider') has-error @enderror">
                    <label for="ai_provider">Provider</label>
                    <select id="ai_provider" name="ai_provider" required>
                        @foreach(['openai' => 'OpenAI', 'anthropic' => 'Anthropic', 'google' => 'Google'] as $provider => $label)
                            <option value="{{ $provider }}" {{ old('ai_provider', $settings['ai_provider']) === $provider ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <div class="api-meta">
                        @error('ai_provider') <span class="api-error">{{ $message }}</span>
                        @else <span>Jis company ka key aap use kar rahe ho</span> @enderror
                    </div>
                </div>

                <div class="field @error('ai_api_key') has-error @enderror">
                    <label for="ai_api_key">API key</label>
                    <div class="api-pass">
                        <input id="ai_api_key" name="ai_api_key" type="password" autocomplete="new-password" value=""
                               placeholder="{{ $settings['api_key_configured'] ? 'Saved securely; leave blank to keep it' : 'Enter provider API key' }}">
                        <button type="button" class="api-eye" id="apiEye" aria-label="Show API key">
                            <svg class="eye-on" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-off" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3l18 18"/><path d="M10.6 6.1A10 10 0 0112 5c6 0 10 7 10 7a17 17 0 01-3.2 3.9"/><path d="M6.6 6.6A17 17 0 002 12s4 7 10 7a9.6 9.6 0 004.4-1.1"/><path d="M9.9 9.9a3 3 0 004.2 4.2"/></svg>
                        </button>
                    </div>
                    <div class="api-meta">
                        @error('ai_api_key') <span class="api-error">{{ $message }}</span>
                        @else <span>{{ $settings['api_key_configured'] ? 'Naya key daalo tabhi purana replace hoga' : 'Save hone ke baad ye dobara nahi dikhega' }}</span> @enderror
                    </div>
                </div>

                @if($settings['api_key_configured'])
                    <label class="api-toggle is-danger api-full">
                        <input class="api-check" type="checkbox" name="remove_api_key" value="1"
                               {{ old('remove_api_key') ? 'checked' : '' }}>
                        <span class="api-track"></span>
                        <span class="api-toggle-text">
                            <span class="api-toggle-title">Remove saved API key</span>
                            <span class="api-toggle-sub">Save karne par stored key hamesha ke liye delete ho jayegi</span>
                        </span>
                    </label>
                @endif
            </div>
        </section>

        {{-- Access --}}
        <section class="api-section">
            <div class="api-head">
                <span class="api-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6l8-3z"/><path d="M9 12l2 2 4-4"/></svg>
                </span>
                <div>
                    <h2>Integration access</h2>
                    <p>Backend is credential ko use kar sakta hai ya nahi</p>
                </div>
            </div>

            <div class="api-grid">
                <label class="api-toggle api-full">
                    <input type="hidden" name="ai_integration_enabled" value="0">
                    <input class="api-check" type="checkbox" name="ai_integration_enabled" value="1"
                           {{ old('ai_integration_enabled', $settings['ai_integration_enabled']) ? 'checked' : '' }}>
                    <span class="api-track"></span>
                    <span class="api-toggle-text">
                        <span class="api-toggle-title">Allow backend integrations to use this credential</span>
                        <span class="api-toggle-sub">Band rakhoge to key save rahegi par koi bhi integration use nahi karega</span>
                    </span>
                </label>
                @error('ai_integration_enabled')
                    <div class="api-error api-full">{{ $message }}</div>
                @enderror
            </div>
        </section>

        <div class="api-actions">
            <button type="reset" class="api-reset">Reset</button>
            <button type="submit" class="api-save">Save API Settings</button>
        </div>
    </form>
</div>

<script>
(function () {
    var form  = document.getElementById('apiForm');
    var input = document.getElementById('ai_api_key');
    var eye   = document.getElementById('apiEye');

    eye.addEventListener('click', function () {
        var show = input.type === 'password';
        input.type = show ? 'text' : 'password';
        eye.classList.toggle('is-shown', show);
        eye.setAttribute('aria-label', show ? 'Hide API key' : 'Show API key');
    });

    function syncToggles() {
        form.querySelectorAll('.api-toggle').forEach(function (row) {
            row.classList.toggle('is-on', row.querySelector('.api-check').checked);
        });
    }
    form.querySelectorAll('.api-check').forEach(function (c) { c.addEventListener('change', syncToggles); });
    form.addEventListener('reset', function () { setTimeout(syncToggles, 0); });
    syncToggles();
})();
</script>
@endsection