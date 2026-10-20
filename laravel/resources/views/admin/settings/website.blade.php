@extends('admin.layout')

@section('title', 'Website Settings')
@section('page-title', 'Website Settings')

@section('content')
<div class="panel">
    <p>Manage the website identity and visitor contact details displayed in the existing public layout.</p>
    <form method="POST" action="{{ route('admin.settings.website.update') }}">
        @csrf
        @method('PUT')
        <div class="grid-two">
            <div class="field">
                <label for="site_name">Website name</label>
                <input id="site_name" name="site_name" value="{{ old('site_name', $settings['site_name']) }}" required>
            </div>
            <div class="field">
                <label for="site_tagline">Website tagline</label>
                <input id="site_tagline" name="site_tagline" value="{{ old('site_tagline', $settings['site_tagline']) }}">
            </div>
            <div class="field">
                <label for="contact_email">Contact email</label>
                <input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $settings['contact_email']) }}" required>
            </div>
            <div class="field">
                <label for="contact_phone">Contact phone</label>
                <input id="contact_phone" name="contact_phone" type="text" value="{{ old('contact_phone', $settings['contact_phone']) }}">
            </div>
            <div class="field">
                <label for="contact_address">Contact address</label>
                <textarea id="contact_address" name="contact_address" required>{{ old('contact_address', $settings['contact_address']) }}</textarea>
            </div>
            <div class="field">
                <label for="opening_hours">Opening hours</label>
                <textarea id="opening_hours" name="opening_hours">{{ old('opening_hours', $settings['opening_hours']) }}</textarea>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Website Settings</button>
    </form>
</div>
@endsection
