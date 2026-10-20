@extends('admin.layout')

@section('title', 'Add Gallery Image')
@section('page-title', 'Add Gallery Image')

@section('content')
<div class="panel">
    <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid-two">
            <div class="field">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title') }}" required>
            </div>
            <div class="field">
                <label for="category">Category</label>
                <input id="category" name="category" type="text" value="{{ old('category', 'wildlife') }}" required>
            </div>
            <div class="field">
                <label for="image">Image</label>
                <input id="image" name="image" type="file" accept="image/*" required>
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
                <label for="image_source">Image source</label>
                <input id="image_source" name="image_source" type="text" value="{{ old('image_source') }}">
            </div>
            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
            <div class="field">
                <label for="display_order">Display order</label>
                <input id="display_order" name="display_order" type="number" min="0" value="{{ old('display_order', 0) }}">
            </div>
        </div>

        <div class="field">
            <label><input type="hidden" name="is_homepage_visible" value="0"><input type="checkbox" name="is_homepage_visible" value="1" {{ old('is_homepage_visible', true) ? 'checked' : '' }}> Show on homepage</label>
        </div>
        <div class="field">
            <label for="description">Description</label>
            <textarea id="description" name="description">{{ old('description') }}</textarea>
        </div>

        <div style="display:flex; gap:12px; margin-top:18px;">
            <button type="submit" class="btn btn-primary">Save Image</button>
            <a href="{{ route('admin.gallery.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
