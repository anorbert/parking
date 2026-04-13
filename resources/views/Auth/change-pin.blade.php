@extends('layouts.app')

@section('title', 'Change PIN - ParkFlow')
@section('body-class', 'lp-body')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Dosis:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  .lp-body {
    background: #111315 !important;
    overflow: hidden !important;
    height: 100vh;
    margin: 0; padding: 0;
  }

  .lp-bg {
    position: fixed; inset: 0; z-index: 0;
    background: radial-gradient(ellipse 80% 60% at 15% 10%, rgba(245,168,0,0.12) 0%, transparent 55%),
                radial-gradient(ellipse 60% 50% at 85% 90%, rgba(58,158,212,0.10) 0%, transparent 55%),
                linear-gradient(160deg, #0E1012 0%, #141618 50%, #0C1018 100%);
  }

  .lp-grid {
    position: absolute; inset: 0;
    background-image:
      linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
      linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
    background-size: 48px 48px;
    mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 20%, transparent 80%);
  }

  .lp-page {
    position: relative; z-index: 1;
    height: 100vh;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    padding: 24px;
    font-family: 'Dosis', sans-serif;
  }

  .lp-logo-bar {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 28px;
  }

  .lp-logo-icon {
    width: 44px; height: 44px; border-radius: 10px;
    background: linear-gradient(135deg, #F5A800, #FFD166);
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
  }

  .lp-logo-icon span {
    font-family: 'Dosis', sans-serif; font-weight: 800;
    font-size: 24px; color: #0D0F11; line-height: 1;
  }

  .lp-logo-name {
    font-size: 22px; font-weight: 800;
    letter-spacing: 3px; text-transform: uppercase;
    color: #F3F4F6;
  }

  .lp-logo-sub {
    font-size: 10px; font-weight: 500;
    letter-spacing: 2.5px; text-transform: uppercase;
    color: #6B7280; margin-top: 1px;
  }

  .lp-card {
    width: 100%; max-width: 420px;
    background: #1E2226;
    border: 1px solid rgba(255,255,255,0.09);
    border-radius: 20px;
    padding: 36px 36px 30px;
    position: relative; overflow: hidden;
  }

  .lp-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, #F5A800, #FFD166 40%, #3A9ED4 65%, #60C4F5);
  }

  .lp-eyebrow {
    font-size: 11px; font-weight: 600;
    letter-spacing: 3px; text-transform: uppercase;
    color: #F5A800; margin-bottom: 8px;
  }

  .lp-title {
    font-size: 30px; font-weight: 800;
    letter-spacing: 1px; color: #F3F4F6;
    line-height: 1.1; margin-bottom: 4px;
  }

  .lp-sub {
    font-size: 13px; font-weight: 400;
    color: #6B7280; margin-bottom: 28px;
    line-height: 1.5;
  }

  .lp-divider {
    border: none; border-top: 1px solid rgba(255,255,255,0.09);
    margin-bottom: 24px;
  }

  .lp-alert {
    padding: 10px 14px; border-radius: 8px;
    font-size: 13px; font-weight: 500;
    margin-bottom: 16px;
  }

  .lp-alert-error {
    background: rgba(239,68,68,0.12);
    border: 1px solid rgba(239,68,68,0.25);
    color: #FCA5A5;
  }

  .lp-alert-success {
    background: rgba(74,222,128,0.12);
    border: 1px solid rgba(74,222,128,0.25);
    color: #86EFAC;
  }

  .lp-alert-warning {
    background: rgba(245,168,0,0.12);
    border: 1px solid rgba(245,168,0,0.25);
    color: #FFD166;
  }

  .lp-field { margin-bottom: 18px; }

  .lp-field label {
    display: block;
    font-size: 12px; font-weight: 700;
    letter-spacing: 1.8px; text-transform: uppercase;
    color: #9CA3AF; margin-bottom: 8px;
  }

  .lp-input-wrap { position: relative; }

  .lp-input-icon {
    position: absolute; left: 14px; top: 50%;
    transform: translateY(-50%);
    color: #6B7280; display: flex; align-items: center;
    pointer-events: none;
  }

  .lp-field input[type="password"] {
    width: 100%;
    background: #252A2F;
    border: 1px solid rgba(255,255,255,0.09);
    border-radius: 10px;
    padding: 13px 44px 13px 42px;
    font-family: 'Dosis', sans-serif;
    font-size: 20px; font-weight: 700;
    letter-spacing: 12px;
    color: #F3F4F6; outline: none;
    transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
    caret-color: #F5A800;
    box-sizing: border-box;
  }

  .lp-field input::placeholder {
    letter-spacing: 6px; font-size: 15px;
    font-weight: 400; color: #3D4550;
  }

  .lp-field input:focus {
    border-color: #F5A800;
    background: rgba(245,168,0,0.06);
    box-shadow: 0 0 0 3px rgba(245,168,0,0.14);
  }

  .lp-eye-btn {
    position: absolute; right: 13px; top: 50%;
    transform: translateY(-50%);
    color: #6B7280; cursor: pointer;
    background: none; border: none;
    display: flex; align-items: center;
    transition: color 0.2s; padding: 2px;
  }
  .lp-eye-btn:hover { color: #F5A800; }

  .lp-ferr {
    margin-top: 6px;
    font-size: 12px; font-weight: 500;
    color: #EF4444;
  }

  .lp-btn {
    width: 100%; padding: 14px;
    border: none; border-radius: 10px;
    background: linear-gradient(90deg, #F5A800 0%, #FFD166 50%, #F5A800 100%);
    background-size: 200% 100%;
    color: #0D0F11;
    font-family: 'Dosis', sans-serif; font-size: 14px;
    font-weight: 800; letter-spacing: 2.5px;
    text-transform: uppercase; cursor: pointer;
    transition: background-position 0.5s, transform 0.1s, box-shadow 0.3s;
    box-shadow: 0 4px 24px rgba(245,168,0,0.25);
    position: relative; overflow: hidden;
    margin-top: 6px;
  }

  .lp-btn:hover { background-position: right center; box-shadow: 0 6px 32px rgba(245,168,0,0.40); }
  .lp-btn:active { transform: scale(0.99); }

  .lp-skip {
    display: block; text-align: center;
    margin-top: 16px; font-size: 13px;
    color: #6B7280; text-decoration: none;
    transition: color 0.2s;
  }
  .lp-skip:hover { color: #60C4F5; }

  .lp-footer {
    margin-top: 20px;
    font-size: 11px; font-weight: 500;
    letter-spacing: 0.5px;
    color: #353A42; text-align: center;
  }

  @media (max-width: 480px) {
    .lp-card { padding: 28px 20px 24px; border-radius: 14px; }
    .lp-title { font-size: 24px; }
  }
</style>
@endpush

@section('content')
<div class="lp-bg"><div class="lp-grid"></div></div>

<div class="lp-page">

  {{-- Logo --}}
  <div class="lp-logo-bar">
    <div class="lp-logo-icon"><span>P</span></div>
    <div>
      <div class="lp-logo-name">ParkFlow</div>
      <div class="lp-logo-sub">Management System</div>
    </div>
  </div>

  {{-- Card --}}
  <div class="lp-card">
    <div class="lp-eyebrow">Security</div>
    <div class="lp-title">Change Your PIN</div>
    <div class="lp-sub">Enter your current PIN and choose a new 4-digit PIN to secure your account.</div>

    <hr class="lp-divider">

    @if(session('success'))
      <div class="lp-alert lp-alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="lp-alert lp-alert-error">{{ session('error') }}</div>
    @endif
    @if(session('warning'))
      <div class="lp-alert lp-alert-warning">{{ session('warning') }}</div>
    @endif

    <form method="POST" action="{{ route('user.change-pin.update', $user->id) }}" autocomplete="off">
      @csrf
      @method('PUT')

      {{-- Current PIN --}}
      <div class="lp-field">
        <label>Current PIN</label>
        <div class="lp-input-wrap">
          <span class="lp-input-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
          </span>
          <input type="password" name="current_password" id="current_pin" maxlength="4" inputmode="numeric" pattern="[0-9]*" placeholder="••••" required>
          <button type="button" class="lp-eye-btn" onclick="togglePin('current_pin', this)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
        @error('current_password') <div class="lp-ferr">{{ $message }}</div> @enderror
      </div>

      {{-- New PIN --}}
      <div class="lp-field">
        <label>New PIN</label>
        <div class="lp-input-wrap">
          <span class="lp-input-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/><circle cx="12" cy="16" r="1"/></svg>
          </span>
          <input type="password" name="new_password" id="new_pin" maxlength="4" inputmode="numeric" pattern="[0-9]*" placeholder="••••" required>
          <button type="button" class="lp-eye-btn" onclick="togglePin('new_pin', this)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
        @error('new_password') <div class="lp-ferr">{{ $message }}</div> @enderror
      </div>

      {{-- Confirm PIN --}}
      <div class="lp-field">
        <label>Confirm New PIN</label>
        <div class="lp-input-wrap">
          <span class="lp-input-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          </span>
          <input type="password" name="new_password_confirmation" id="confirm_pin" maxlength="4" inputmode="numeric" pattern="[0-9]*" placeholder="••••" required>
          <button type="button" class="lp-eye-btn" onclick="togglePin('confirm_pin', this)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          </button>
        </div>
      </div>

      <button type="submit" class="lp-btn">Update PIN</button>
    </form>
  </div>

  <div class="lp-footer">&copy; {{ date('Y') }} ParkFlow — Parking Management System</div>
</div>
@endsection

@push('scripts')
<script>
function togglePin(id, btn) {
  var input = document.getElementById(id);
  if (input.type === 'password') {
    input.type = 'text';
    btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>';
  } else {
    input.type = 'password';
    btn.innerHTML = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
  }
}
</script>
@endpush
