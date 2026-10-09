@extends('admin.layout')

@section('title', 'Add Ticket')
@section('page-title', 'Add Ticket')

@section('content')
<div class="panel">
    <form method="POST" action="{{ route('admin.tickets.store') }}">
        @csrf
        <div class="grid-two">
            <div class="field">
                <label for="ticket_type">Ticket Type</label>
                <input id="ticket_type" name="ticket_type" type="text" value="{{ old('ticket_type') }}" required>
            </div>
            <div class="field">
                <label for="price">Price</label>
                <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', 0) }}" required>
            </div>
            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="field">
            <label for="description">Description</label>
            <textarea id="description" name="description">{{ old('description') }}</textarea>
        </div>

        <div style="display:flex; gap:12px; margin-top:18px;">
            <button type="submit" class="btn btn-primary">Save Ticket</button>
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
