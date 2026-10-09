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
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
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
