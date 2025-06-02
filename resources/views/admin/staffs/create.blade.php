@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Register New Staff</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <p class="mb-4">Fill in the details below to create a new staff account.</p>

    {{-- Staff Registration Form --}}

    <form action="{{ route('staff.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="phone_number" class="form-label">Phone Number (optional)</label>
            <input type="text" name="phone_number" class="form-control">
        </div>

        <div class="mb-3">
            <label for="zone_id" class="form-label">Select Zone (Optional)</label>
            <select name="zone_id" class="form-control">
                <option value="">-- Select Zone --</option>
                @foreach($zones as $zone)
                    <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="role_id" class="form-label">Select Role</label>
            <select name="role_id" class="form-control" required>
                <option value="" selected disabled>-- Select Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                @endforeach
            </select>
        </div>

        <button class="btn btn-primary" type="submit">Create Staff Account</button>
    </form>
</div>
@endsection
