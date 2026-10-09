@extends('admin.layout')

@section('title', 'Gallery')
@section('page-title', 'Gallery')

@section('content')
<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h3 style="margin:0;">Gallery management</h3>
    <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">Add Image</a>
</div>

<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Status</th>
                    <th style="width:150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($gallery as $item)
                    <tr>
                        <td>{{ $item->title }}</td>
                        <td>{{ $item->category }}</td>
                        <td>{{ ucfirst($item->status) }}</td>
                        <td>
                            <a href="{{ route('admin.gallery.edit', $item) }}" class="btn btn-secondary" style="padding:8px 12px;">Edit</a>
                            <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" style="display:inline; margin-left:6px;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding:8px 12px;" onclick="return confirm('Delete this image?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty">No gallery images found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
