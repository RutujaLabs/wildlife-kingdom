@extends('admin.layout')

@section('title', 'Edit Ticket')
@section('page-title', 'Edit Ticket')

@section('content')
<div class="panel">
    <form method="POST" action="{{ route('admin.tickets.update', $ticket) }}">
        @csrf
        @method('PUT')
        <div class="grid-two">
            <div class="field">
                <label for="ticket_type">Ticket Type</label>
                <input id="ticket_type" name="ticket_type" type="text" value="{{ old('ticket_type', $ticket->ticket_type) }}" required>
            </div>
            <div class="field">
                <label for="price">Price</label>
                <input id="price" name="price" type="number" step="0.01" min="0" value="{{ old('price', $ticket->price) }}" required>
            </div>
            <div class="field">
                <label for="status">Status</label>
                <select id="status" name="status">
                    <option value="active" {{ old('status', $ticket->status) == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $ticket->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
        </div>

        <div class="field">
            <label for="description">Description</label>
            <textarea id="description" name="description">{{ old('description', $ticket->description) }}</textarea>
        </div>

        <div style="display:flex; gap:12px; margin-top:18px;">
            <button type="submit" class="btn btn-primary">Update Ticket</button>
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline">Cancel</a>
        </div>
    </form>
</div>
@endsection
