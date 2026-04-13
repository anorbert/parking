@extends('layouts.app')

@section('title', 'Change Password')
@section('body-class', 'login')

@section('content')
<div class="login_wrapper">
    <div class="animate form login_form">
        <section class="login_content">
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('PUT')
                <h1>Change Password</h1>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="form-group">
                    <input id="current_password" type="password"
                           class="form-control @error('current_password') is-invalid @enderror"
                           name="current_password" required placeholder="Enter your current password">
                    @error('current_password')
                        <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mt-3">
                    <input id="password" type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           name="password" required placeholder="Enter your new password">
                    @error('password')
                        <span class="invalid-feedback" style="display:block;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group mt-3">
                    <input id="password_confirmation" type="password"
                           class="form-control" name="password_confirmation"
                           required placeholder="Confirm your new password">
                </div>
                <button type="submit" class="btn btn-primary mt-4">Update Password</button>
            </form>

            <div class="clearfix"></div>

            <div class="separator">
                <br />
                <div>
                    <h1><i class="fa fa-car"></i> ParkFlow</h1>
                    <p>&copy; {{ date('Y') }} All Rights Reserved. Privacy and Terms</p>
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
