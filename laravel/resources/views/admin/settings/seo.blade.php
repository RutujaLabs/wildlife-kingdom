@extends('admin.layout')

@section('title', 'SEO Settings')
@section('page-title', 'SEO Settings')

@section('content')
<div class="panel">
    <p>These defaults are used on public pages that do not define their own SEO values. Animal-specific SEO settings continue to take precedence.</p>
    <form method="POST" action="{{ route('admin.settings.seo.update') }}">
        @csrf
        @method('PUT')
        <div class="field">
            <label for="seo_site_title">Default site title</label>
            <input id="seo_site_title" name="seo_site_title" maxlength="70" value="{{ old('seo_site_title', $settings['seo_site_title']) }}" required>
        </div>
        <div class="field">
            <label for="seo_site_description">Default meta description</label>
            <textarea id="seo_site_description" name="seo_site_description" maxlength="320" required>{{ old('seo_site_description', $settings['seo_site_description']) }}</textarea>
        </div>
        <div class="field">
            <label for="seo_canonical_base_url">Canonical base URL (optional)</label>
            <input id="seo_canonical_base_url" name="seo_canonical_base_url" type="url" value="{{ old('seo_canonical_base_url', $settings['seo_canonical_base_url']) }}" placeholder="https://example.com">
        </div>
        <div class="field">
            <label><input type="hidden" name="seo_default_noindex" value="0"><input type="checkbox" name="seo_default_noindex" value="1" {{ old('seo_default_noindex', $settings['seo_default_noindex']) ? 'checked' : '' }}> Apply noindex by default</label>
            <label><input type="hidden" name="seo_default_nofollow" value="0"><input type="checkbox" name="seo_default_nofollow" value="1" {{ old('seo_default_nofollow', $settings['seo_default_nofollow']) ? 'checked' : '' }}> Apply nofollow by default</label>
        </div>
        <button type="submit" class="btn btn-primary">Save SEO Settings</button>
    </form>
</div>
@endsection
