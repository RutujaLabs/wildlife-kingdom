@extends('admin.layout')

@section('title', 'Edit Event')
@section('page-title', 'Edit Event')

@section('content')
<div class="panel">
    <form method="POST" action="{{ route('admin.events.update', $event) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid-two">
            <div class="field">
                <label for="title">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title', $event->title) }}" required>
            </div>
            <div class="field">
                <label for="location">Location</label>
                <input id="location" name="location" type="text" value="{{ old('location', $event->location) }}" required>
            </div>
            <div class="field">
                <label for="event_date">Event Date</label>
                <input id="event_date" name="event_date" type="date" value="{{ old('event_date', $event->event_date?->format('Y-m-d')) }}" required>
            </div>
            <div class="field">
                <label for="event_time">Event Time</label>
                <input id="event_time" name="event_time" type="text" value="{{ old('event_time', $event->event_time) }}" placeholder="08:00 AM">
            </div>
            <div class="field">
                <label for="image">Replace Image</label>
                <input id="image" name="image" type="file" accept="image/*">
            </div>
            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="published" {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                </select>
            </div>
        </div>

        <div class="field">
            <label for="description">Description</label>
            <textarea id="description" name="description" required>{{ old('description', $event->description) }}</textarea>
        </div>

        <div style="display:flex; gap:12px; margin-top:18px;">
            <button type="submit" class="btn btn-primary">Update Event</button>
            <a href="{{ route('admin.events.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
