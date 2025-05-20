@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container" style="margin-top: 100px; max-width: 500px;">
    <div class="card">
        <div class="card-header text-center">
            <h3>Create an Account</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group mb-3">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" class="form-control @error('name') is-invalid @enderror"
                        name="name" value="{{ old('name') }}" required autofocus>

                    @error('name')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required>

                    @error('email')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password">Password</label>
                    <input type="password" id="password" class="form-control @error('password') is-invalid @enderror"
                        name="password" required>

                    @error('password')
                        <span class="text-danger small">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group mb-3">
                    <label for="password-confirm">Confirm Password</label>
                    <input type="password" id="password-confirm" class="form-control" name="password_confirmation"
                        required>
                </div>

                <button type="submit" class="btn btn-success w-100">Register</button>

                <a class="d-block text-center mt-3" href="{{ route('login') }}">
                    Already have an account? Login here.
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
