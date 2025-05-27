@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Staff Member Details</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="row">
                {{-- Avatar Placeholder --}}
                <div class="col-md-3 text-center">
                    <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar" class="img-fluid rounded-circle mb-3" width="120" height="120">
                    <p class="text-muted">Staff ID: #{{ $user->id }}</p>
                </div>

                {{-- Staff Info --}}
                <div class="col-md-9">
                    <h4>{{ $user->name }}</h4>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Phone:</strong> {{ $user->phone_number ?? 'N/A' }}</p>
                    <p>
                        <strong>Role:</strong> 
                        <span class="badge bg-primary">{{ $user->role->name }}</span>
                    </p>
                    <p>
                        <strong>Email Verified:</strong> 
                        @if ($user->email_verified_at)
                            <span class="text-success">Yes ({{ $user->email_verified_at->format('Y-m-d H:i') }})</span>
                        @else
                            <span class="text-danger">No</span>
                        @endif
                    </p>
                    <p><strong>Account Created:</strong> {{ $user->created_at->format('Y-m-d H:i') }}</p>
                    <p><strong>Last Updated:</strong> {{ $user->updated_at->format('Y-m-d H:i') }}</p>

                    <div class="mt-4">
                        <a href="{{ route('staff.edit', $user->id) }}" class="btn btn-warning">Edit</a>
                        <a href="{{ route('staff.index') }}" class="btn btn-secondary">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
