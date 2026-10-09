@extends('admin.layout')

@section('title', 'Edit Animal')
@section('page-title', 'Edit Animal')

@section('content')
<div class="panel">
    <form method="POST" action="{{ route('admin.animals.update', $animal) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid-two">
            <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="{{ old('name', $animal->name) }}" required>
            </div>
            <div class="field">
                <label for="scientific_name">Scientific Name</label>
                <input id="scientific_name" name="scientific_name" type="text" value="{{ old('scientific_name', $animal->scientific_name) }}">
            </div>
            <div class="field">
                <label for="conservation_status">Conservation Status</label>
                <input id="conservation_status" name="conservation_status" type="text" value="{{ old('conservation_status', $animal->conservation_status) }}" required>
            </div>
            <div class="field">
                <label for="category">Category</label>
                <select id="category" name="category" required>
                    @foreach(['Mammals','Birds','Reptiles','Amphibians','Big Cats','Primates','Herbivores','Other'] as $category)
                        <option value="{{ $category }}" {{ old('category', $animal->category) == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="habitat_id">Habitat</label>
                <select id="habitat_id" name="habitat_id">
                    <option value="">Select habitat</option>
                    @foreach($habitats as $habitat)
                        <option value="{{ $habitat->id }}" {{ old('habitat_id', $animal->habitat_id) == $habitat->id ? 'selected' : '' }}>{{ $habitat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label for="image">Replace Image</label>
                <input id="image" name="image" type="file" accept="image/*">
            </div>
            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="published" {{ old('status', $animal->status) == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ old('status', $animal->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
        </div>

        <div class="field">
            <label for="description">Description</label>
            <textarea id="description" name="description" required>{{ old('description', $animal->description) }}</textarea>
        </div>
        <div class="field">
            <label><input name="is_featured" type="checkbox" value="1" {{ old('is_featured', $animal->is_featured) ? 'checked' : '' }}> Featured animal</label>
        </div>

        <div style="display:flex; gap:12px; margin-top:18px;">
            <button type="submit" class="btn btn-primary">Update Animal</button>
            <a href="{{ route('admin.animals.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
