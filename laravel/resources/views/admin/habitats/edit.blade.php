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
                <label for="image">Replace Image</label>
                <input id="image" name="image" type="file" accept="image/*">
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

        <div style="display:flex; gap:12px; margin-top:18px;">
            <button type="submit" class="btn btn-primary">Update Habitat</button>
            <a href="{{ route('admin.habitats.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
