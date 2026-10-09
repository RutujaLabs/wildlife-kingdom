@extends('admin.layout')

@section('title', 'Events')
@section('page-title', 'Events')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h3 style="margin:0;">Event management</h3>
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">Add Event</a>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th style="width:150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($events as $event)
                    <tr>
                        <td>{{ $event->title }}</td>
                        <td>{{ $event->event_date ? $event->event_date->format('d M Y') : '-' }}</td>
                        <td>{{ $event->location ?? '-' }}</td>
                        <td>{{ ucfirst($event->status) }}</td>
                        <td>
                            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-secondary" style="padding:8px 12px;">Edit</a>
                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" style="display:inline; margin-left:6px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding:8px 12px;" onclick="return confirm('Delete this event?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty">No events found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
