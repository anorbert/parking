@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Staff Members</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('staff.create') }}" class="btn btn-primary mb-3">+ Add New Staff</a>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Name</th>                
                <th>Role</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($staff as $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->role->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->phone_number }}</td>
                <td>
                    {{-- Optional future actions like Edit/Delete --}}
                    <a href="{{ route('staff.edit', $user->id) }}" class="btn btn-sm btn-warning">Edit</a>
                    <a href="{{ route('staff.show', $user->id) }}" class="btn btn-sm btn-info">View</a>
                    {{-- Delete form --}}
                    <form action="{{ route('staff.destroy', $user->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            {{ $staff->links() }}
        @empty
            <tr>
                <td colspan="6">No staff members found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
