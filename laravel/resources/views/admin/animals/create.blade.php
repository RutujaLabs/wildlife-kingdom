@extends('admin.layout')

@section('title', 'Add Animal')
@section('page-title', 'Add Animal')

@section('content')
<div class="panel">
    <form method="POST" action="{{ route('admin.animals.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid-two">
            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required>
            </div>
            <div class="field">
                <label for="scientific_name">Scientific Name</label>
                <input id="scientific_name" name="scientific_name" type="text" value="{{ old('scientific_name') }}">
            </div>
            <div class="field">
                <label for="slug">URL slug</label>
                <input id="slug" name="slug" type="text" value="{{ old('slug') }}">
            </div>
            <div class="field">
                <label for="short_description">Short Description</label>
                <input id="short_description" name="short_description" type="text" maxlength="500" value="{{ old('short_description') }}">
            </div>
            <div class="field">
                <label for="conservation_status">Conservation Status</label>
                <input id="conservation_status" name="conservation_status" type="text" value="{{ old('conservation_status', 'Not assessed') }}" required>
            </div>
            <div class="field">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    @foreach(['Mammals','Birds','Reptiles','Amphibians','Big Cats','Primates','Herbivores','Other'] as $category)
                        <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="habitat_id">Habitat</label>
                <select id="habitat_id" name="habitat_id">
                    <option value="">Select habitat</option>
                    @foreach($habitats as $habitat)
                        <option value="{{ $habitat->id }}" {{ old('habitat_id') == $habitat->id ? 'selected' : '' }}>{{ $habitat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="image">Image</label>
                <input id="image" name="image" type="file" accept="image/*">
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
                <label for="image_alt_text">Main image alt text</label>
                <input id="image_alt_text" name="image_alt_text" type="text" value="{{ old('image_alt_text') }}">
            </div>
            <div class="field">
                <label for="image_caption">Main image caption</label>
                <input id="image_caption" name="image_caption" type="text" value="{{ old('image_caption') }}">
            </div>
            <div class="field">
                <label for="image_credit">Main image credit</label>
                <input id="image_credit" name="image_credit" type="text" value="{{ old('image_credit') }}">
            </div>
            <div class="field">
                <label for="image_source">Main image source</label>
                <input id="image_source" name="image_source" type="text" value="{{ old('image_source') }}">
            </div>
        </div>

        @include('admin.animals._extended-fields')

        <div class="grid-two">
            <div class="field">
                <label for="display_order">Display order</label>
                <input id="display_order" name="display_order" type="number" min="0" value="{{ old('display_order', 0) }}">
            </div>
            <div class="field">
                <label><input type="hidden" name="is_featured" value="0"><input name="is_featured" type="checkbox" value="1" {{ old('is_featured') ? 'checked' : '' }}> Featured animal</label>
                <label><input type="hidden" name="is_homepage_visible" value="0"><input name="is_homepage_visible" type="checkbox" value="1" {{ old('is_homepage_visible', true) ? 'checked' : '' }}> Show on homepage</label>
            </div>
        </div>
        <div style="display:flex; gap:12px; margin-top:18px;">
            <button type="submit" class="btn btn-primary">Save Animal</button>
            <a href="{{ route('admin.animals.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
