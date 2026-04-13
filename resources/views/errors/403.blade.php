@extends('layouts.app')

@section('title', 'Access Denied')
@section('body-class', 'lp-body')

@push('styles')
<style>
.lp-403-container {
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #111315;
    color: #F3F4F6;
    font-family: 'Dosis', sans-serif;
}
.lp-403-card {
    background: #1E2226;
    border-radius: 18px;
    padding: 48px 36px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    text-align: center;
    max-width: 420px;
    width: 100%;
}
.lp-403-title {
    font-size: 54px;
    font-weight: 900;
    color: #F5A800;
    margin-bottom: 12px;
    letter-spacing: 2px;
}
.lp-403-message {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 18px;
    color: #F3F4F6;
}
.lp-403-desc {
    font-size: 15px;
    color: #9CA3AF;
    margin-bottom: 28px;
}
.lp-403-btn {
    display: inline-block;
    padding: 12px 28px;
    background: linear-gradient(90deg, #F5A800 0%, #FFD166 100%);
    color: #0D0F11;
    font-weight: 800;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    letter-spacing: 1.5px;
    transition: background 0.2s;
}
.lp-403-btn:hover {
    background: linear-gradient(90deg, #FFD166 0%, #F5A800 100%);
}
</style>
@endpush

@section('content')
<div class="lp-403-container">
    <div class="lp-403-card">
        <div class="lp-403-title">403</div>
        <div class="lp-403-message">Access Denied</div>
        <div class="lp-403-desc">You are not assigned to any company.<br>Please contact your administrator to be assigned to a company before accessing this page.</div>
        <a href="{{ url('/') }}" class="lp-403-btn">Go to Home</a>
    </div>
</div>
@endsection
