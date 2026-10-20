@extends('admin.layout')

@section('title', 'API Settings')
@section('page-title', 'API Settings')

@section('content')
<div class="panel">
    <p>Store an AI provider credential for future backend integrations. The credential is encrypted at rest and is never shown after saving or sent to public pages. This setting does not enable an AI feature by itself.</p>
    <p><strong>Credential status:</strong> {{ $settings['api_key_configured'] ? 'Configured' : 'Not configured' }}</p>
    <form method="POST" action="{{ route('admin.settings.api.update') }}">
        @csrf
        @method('PUT')
        <div class="field">
            <label for="ai_provider">Provider</label>
            <select id="ai_provider" name="ai_provider" required>
                @foreach(['openai' => 'OpenAI', 'anthropic' => 'Anthropic', 'google' => 'Google'] as $provider => $label)
                    <option value="{{ $provider }}" {{ old('ai_provider', $settings['ai_provider']) === $provider ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="field">
            <label for="ai_api_key">API key</label>
            <input id="ai_api_key" name="ai_api_key" type="password" autocomplete="new-password" value="" placeholder="{{ $settings['api_key_configured'] ? 'Saved securely; leave blank to keep it' : 'Enter provider API key' }}">
        </div>
        @if($settings['api_key_configured'])
            <div class="field">
                <label><input type="checkbox" name="remove_api_key" value="1"> Remove saved API key</label>
            </div>
        @endif
        <div class="field">
            <label><input type="hidden" name="ai_integration_enabled" value="0"><input type="checkbox" name="ai_integration_enabled" value="1" {{ old('ai_integration_enabled', $settings['ai_integration_enabled']) ? 'checked' : '' }}> Allow backend integrations to use this credential</label>
        </div>
        <button type="submit" class="btn btn-primary">Save API Settings</button>
    </form>
</div>
@endsection
