@extends('admin.layout')

@section('title', 'Tickets')
@section('page-title', 'Tickets')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h3 style="margin:0;">Ticket management</h3>
    <a href="{{ route('admin.tickets.create') }}" class="btn btn-primary">Add Ticket</a>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Price</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Bookings</th>
                    <th style="width:190px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                    <tr>
                        <td>{{ $ticket->ticket_type }}</td>
                        <td>${{ number_format($ticket->price, 2) }}</td>
                        <td>{{ $ticket->description ?: '—' }}</td>
                        <td>{{ ucfirst($ticket->status) }}</td>
                        <td>{{ $ticket->bookings_count }}</td>
                        <td>
                            <a href="{{ route('admin.tickets.edit', $ticket) }}" class="btn btn-secondary" style="padding:8px 12px;">Edit</a>
                            @if($ticket->bookings_count)
                                <span title="Bookings depend on this ticket type">In use</span>
                            @else
                                <form action="{{ route('admin.tickets.destroy', $ticket) }}" method="POST" style="display:inline; margin-left:6px;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding:8px 12px;" onclick="return confirm('Delete this ticket?')">Delete</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty">No ticket pricing found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
