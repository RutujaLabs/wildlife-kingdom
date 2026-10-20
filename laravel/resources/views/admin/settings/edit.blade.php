@extends('admin.layout')

@section('title', 'Pages & Content')
@section('page-title', 'Pages & Content')

@section('content')
<div class="panel">
    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf
        @method('PUT')
        <div class="field">
            <label for="home_eyebrow">Homepage eyebrow</label>
            <input id="home_eyebrow" name="home_eyebrow" value="{{ old('home_eyebrow', $settings['home_eyebrow']) }}" required>
        </div>
        <div class="field">
            <label for="home_heading">Homepage heading</label>
            <input id="home_heading" name="home_heading" value="{{ old('home_heading', $settings['home_heading']) }}" required>
        </div>
        <div class="field">
            <label for="home_intro">Homepage introduction</label>
            <textarea id="home_intro" name="home_intro" required>{{ old('home_intro', $settings['home_intro']) }}</textarea>
        </div>
        <div class="field">
            <label for="footer_about">Footer description</label>
            <textarea id="footer_about" name="footer_about" required>{{ old('footer_about', $settings['footer_about']) }}</textarea>
        </div>
        <div class="grid-two">
            <div class="field">
                <label for="contact_address">Contact address</label>
                <textarea id="contact_address" name="contact_address" required>{{ old('contact_address', $settings['contact_address']) }}</textarea>
            </div>
            <div class="field">
                <label for="contact_email">Contact email</label>
                <input id="contact_email" name="contact_email" type="email" value="{{ old('contact_email', $settings['contact_email']) }}" required>
            </div>
        </div>
        <div style="display:flex; gap:12px; margin-top:18px;">
            <button type="submit" class="btn btn-primary">Save Website Content</button>
        </div>
    </form>
</div>
@endsection
