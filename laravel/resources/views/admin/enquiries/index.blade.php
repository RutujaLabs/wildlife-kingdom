@extends('admin.layout')

@section('title', 'Enquiries')
@section('page-title', 'Enquiries')

@section('content')
<div class="panel">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($enquiries as $enquiry)
                    <tr>
                        <td>{{ $enquiry->name }}</td>
                        <td>{{ $enquiry->email }}</td>
                        <td>{{ $enquiry->subject ?? '-' }}</td>
                        <td>
                            <form action="{{ route('admin.enquiries.update-status', $enquiry) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()">
                                    @foreach(['new','reviewed','replied','closed'] as $status)
                                        <option value="{{ $status }}" {{ $enquiry->status == $status ? 'selected' : '' }}>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td>
                            <details>
                                <summary class="btn btn-secondary" style="padding:8px 12px;">View</summary>
                                <p>{{ $enquiry->message }}</p>
                            </details>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="empty">No enquiries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
