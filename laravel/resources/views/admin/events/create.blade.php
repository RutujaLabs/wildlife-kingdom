@extends('admin.layout')

@section('title', 'Add Event')
@section('page-title', 'Add Event')

@section('content')
<div class="panel">
    <form method="POST" action="{{ route('admin.events.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid-two">
            <div class="field">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" required>
            </div>
            <div class="field">
                <label for="location">Location</label>
                <input id="location" name="location" type="text" value="{{ old('location') }}" required>
            </div>
            <div class="field">
                <label for="event_date">Event Date</label>
                <input id="event_date" name="event_date" type="date" value="{{ old('event_date') }}" required>
            </div>
            <div class="field">
                <label for="event_time">Event Time</label>
                <input id="event_time" name="event_time" type="text" value="{{ old('event_time') }}" placeholder="08:00 AM">
            </div>
            <div class="field">
                <label for="image">Image</label>
                <input id="image" name="image" type="file" accept="image/*">
            </div>
            <div class="field">
                <label for="image_alt_text">Image alt text</label>
                <input id="image_alt_text" name="image_alt_text" type="text" value="{{ old('image_alt_text') }}">
            </div>
            <div class="field">
                <label for="image_caption">Image caption</label>
                <input id="image_caption" name="image_caption" type="text" value="{{ old('image_caption') }}">
            </div>
            <div class="field">
                <label for="image_credit">Image credit</label>
                <input id="image_credit" name="image_credit" type="text" value="{{ old('image_credit') }}">
            </div>
            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
        </div>

        <div class="field">
            <label for="description">Description</label>
            <textarea id="description" name="description" required>{{ old('description') }}</textarea>
        </div>
        <div class="grid-two">
            <div class="field">
                <label for="meta_title">Meta title</label>
                <input id="meta_title" name="meta_title" maxlength="70" value="{{ old('meta_title') }}">
            </div>
            <div class="field">
                <label for="canonical_url">Canonical URL</label>
                <input id="canonical_url" name="canonical_url" type="url" value="{{ old('canonical_url') }}">
            </div>
            <div class="field">
                <label for="meta_description">Meta description</label>
                <textarea id="meta_description" name="meta_description" maxlength="320">{{ old('meta_description') }}</textarea>
            </div>
            <div class="field">
                <label for="display_order">Display order</label>
                <input id="display_order" name="display_order" type="number" min="0" value="{{ old('display_order', 0) }}">
            </div>
        </div>
        <div class="field">
            <label><input type="hidden" name="noindex" value="0"><input type="checkbox" name="noindex" value="1" {{ old('noindex') ? 'checked' : '' }}> Do not index</label>
            <label><input type="hidden" name="is_featured" value="0"><input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }}> Featured event</label>
            <label><input type="hidden" name="is_homepage_visible" value="0"><input type="checkbox" name="is_homepage_visible" value="1" {{ old('is_homepage_visible', true) ? 'checked' : '' }}> Show on homepage</label>
        </div>

        <div style="display:flex; gap:12px; margin-top:18px;">
            <button type="submit" class="btn btn-primary">Save Event</button>
            <a href="{{ route('admin.events.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
