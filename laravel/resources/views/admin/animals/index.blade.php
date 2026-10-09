@extends('admin.layout')

@section('title', 'Animals')
@section('page-title', 'Animals')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h3 style="margin:0;">Animal management</h3>
    <a href="{{ route('admin.animals.create') }}" class="btn btn-primary">Add Animal</a>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Scientific</th>
                    <th>Habitat</th>
                    <th>Status</th>
                    <th style="width:150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($animals as $animal)
                    <tr>
                        <td>{{ $animal->name }}</td>
                        <td>{{ $animal->scientific_name ?? '-' }}</td>
                        <td>{{ $animal->habitat?->name ?? '-' }}</td>
                        <td>{{ ucfirst($animal->status) }}</td>
                        <td>
                            <a href="{{ route('admin.animals.edit', $animal) }}" class="btn btn-secondary" style="padding:8px 12px;">Edit</a>
                            <form action="{{ route('admin.animals.destroy', $animal) }}" method="POST" style="display:inline; margin-left:6px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding:8px 12px;" onclick="return confirm('Delete this animal?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty">No animals found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
