@extends('admin.layout')

@section('title', 'Habitats')
@section('page-title', 'Habitats')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h3 style="margin:0;">Habitat management</h3>
    <a href="{{ route('admin.habitats.create') }}" class="btn btn-primary">Add Habitat</a>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Short Description</th>
                    <th>Status</th>
                    <th style="width:150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($habitats as $habitat)
                    <tr>
                        <td>{{ $habitat->name }}</td>
                        <td>{{ $habitat->short_description ?? '-' }}</td>
                        <td>{{ ucfirst($habitat->status) }}</td>
                        <td>
                            <a href="{{ route('admin.habitats.edit', $habitat) }}" class="btn btn-secondary" style="padding:8px 12px;">Edit</a>
                            <form action="{{ route('admin.habitats.destroy', $habitat) }}" method="POST" style="display:inline; margin-left:6px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding:8px 12px;" onclick="return confirm('Delete this habitat?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty">No habitats found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
