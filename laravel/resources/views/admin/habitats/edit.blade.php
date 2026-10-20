@extends('admin.layout')

@section('title', 'Edit Habitat')
@section('page-title', 'Edit Habitat')

@section('content')
<div class="panel">
    <form method="POST" action="{{ route('admin.habitats.update', $habitat) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid-two">
            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $habitat->name) }}" required>
            </div>
            <div class="field">
                <label for="short_description">Short Description</label>
                <input id="short_description" name="short_description" type="text" value="{{ old('short_description', $habitat->short_description) }}">
            </div>
            <div class="field">
                <label for="climate">Climate</label>
                <input id="climate" name="climate" type="text" value="{{ old('climate', $habitat->climate) }}">
            </div>
            <div class="field">
                <label for="location">Location</label>
                <input id="location" name="location" type="text" value="{{ old('location', $habitat->location) }}">
            </div>
            <div class="field">
                <label for="image">Replace Image</label>
                <input id="image" name="image" type="file" accept="image/*">
            </div>
            <div class="field">
                <label for="image_alt_text">Image alt text</label>
                <input id="image_alt_text" name="image_alt_text" type="text" value="{{ old('image_alt_text', $habitat->image_alt_text) }}">
            </div>
            <div class="field">
                <label for="image_caption">Image caption</label>
                <input id="image_caption" name="image_caption" type="text" value="{{ old('image_caption', $habitat->image_caption) }}">
            </div>
            <div class="field">
                <label for="image_credit">Image credit</label>
                <input id="image_credit" name="image_credit" type="text" value="{{ old('image_credit', $habitat->image_credit) }}">
            </div>
            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="published" {{ old('status', $habitat->status) == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ old('status', $habitat->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
        </div>

        <div class="field">
            <label for="description">Description</label>
            <textarea id="description" name="description" required>{{ old('description', $habitat->description) }}</textarea>
        </div>
        <div class="field">
            <label for="geographical_range">Geographical range</label>
            <textarea id="geographical_range" name="geographical_range">{{ old('geographical_range', $habitat->geographical_range) }}</textarea>
        </div>
        <div class="grid-two">
            <div class="field">
                <label for="meta_title">Meta title</label>
                <input id="meta_title" name="meta_title" maxlength="70" value="{{ old('meta_title', $habitat->meta_title) }}">
            </div>
            <div class="field">
                <label for="canonical_url">Canonical URL</label>
                <input id="canonical_url" name="canonical_url" type="url" value="{{ old('canonical_url', $habitat->canonical_url) }}">
            </div>
            <div class="field">
                <label for="meta_description">Meta description</label>
                <textarea id="meta_description" name="meta_description" maxlength="320">{{ old('meta_description', $habitat->meta_description) }}</textarea>
            </div>
            <div class="field">
                <label for="display_order">Display order</label>
                <input id="display_order" name="display_order" type="number" min="0" value="{{ old('display_order', $habitat->display_order) }}">
            </div>
        </div>
        <div class="field">
            <label><input type="hidden" name="noindex" value="0"><input type="checkbox" name="noindex" value="1" {{ old('noindex', $habitat->noindex) ? 'checked' : '' }}> Do not index</label>
            <label><input type="hidden" name="is_homepage_visible" value="0"><input type="checkbox" name="is_homepage_visible" value="1" {{ old('is_homepage_visible', $habitat->is_homepage_visible) ? 'checked' : '' }}> Show on homepage</label>
        </div>

        <div style="display:flex; gap:12px; margin-top:18px;">
            <button type="submit" class="btn btn-primary">Update Habitat</button>
            <a href="{{ route('admin.habitats.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
