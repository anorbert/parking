@extends('layouts.app')

@section('title', 'Register - ParkFlow')
@section('body-class', 'lp-body')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Dosis:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  .lp-body { background: #111315 !important; min-height: 100vh; margin: 0; padding: 0; }
  .lp-bg { position: fixed; inset: 0; z-index: 0; background: radial-gradient(ellipse 80% 60% at 15% 10%, rgba(245,168,0,0.12) 0%, transparent 55%), radial-gradient(ellipse 60% 50% at 85% 90%, rgba(58,158,212,0.10) 0%, transparent 55%), linear-gradient(160deg, #0E1012 0%, #141618 50%, #0C1018 100%); }
  .lp-grid { position: absolute; inset: 0; background-image: linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px); background-size: 48px 48px; mask-image: radial-gradient(ellipse 80% 80% at 50% 50%, black 20%, transparent 80%); }
  .lp-page { position: relative; z-index: 1; min-height: 100vh; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 24px; font-family: 'Dosis', sans-serif; }
  .lp-logo-bar { display: flex; align-items: center; gap: 12px; margin-bottom: 28px; }
  .lp-logo-icon { width: 44px; height: 44px; border-radius: 10px; background: linear-gradient(135deg, #F5A800, #FFD166); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .lp-logo-icon span { font-family: 'Dosis', sans-serif; font-weight: 800; font-size: 24px; color: #0D0F11; line-height: 1; }
  .lp-logo-name { font-size: 22px; font-weight: 800; letter-spacing: 3px; text-transform: uppercase; color: #F3F4F6; }
  .lp-logo-sub { font-size: 10px; font-weight: 500; letter-spacing: 2.5px; text-transform: uppercase; color: #6B7280; margin-top: 1px; }
  .lp-card { width: 100%; max-width: 540px; background: #1E2226; border: 1px solid rgba(255,255,255,0.09); border-radius: 20px; padding: 36px 36px 30px; position: relative; overflow: hidden; }
  .lp-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #F5A800, #FFD166 40%, #3A9ED4 65%, #60C4F5); }
  .lp-eyebrow { font-size: 11px; font-weight: 600; letter-spacing: 3px; text-transform: uppercase; color: #F5A800; margin-bottom: 8px; font-family: 'Dosis', sans-serif; }
  .lp-title { font-size: 34px; font-weight: 800; letter-spacing: 1px; color: #F3F4F6; line-height: 1.1; margin-bottom: 4px; font-family: 'Dosis', sans-serif; }
  .lp-sub { font-size: 13px; font-weight: 400; color: #6B7280; margin-bottom: 28px; line-height: 1.5; }
  .lp-divider { border: none; border-top: 1px solid rgba(255,255,255,0.09); margin-bottom: 24px; }
  .lp-alert { padding: 10px 14px; border-radius: 8px; font-size: 13px; font-weight: 500; margin-bottom: 16px; }
  .lp-alert-error { background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.25); color: #FCA5A5; }
  .lp-alert-success { background: rgba(74,222,128,0.12); border: 1px solid rgba(74,222,128,0.25); color: #86EFAC; }
  .lp-field { margin-bottom: 18px; }
  .lp-field label { display: block; font-size: 12px; font-weight: 700; letter-spacing: 1.8px; text-transform: uppercase; color: #9CA3AF; margin-bottom: 8px; }
  .lp-input-wrap { position: relative; }
  .lp-input-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #6B7280; display: flex; align-items: center; pointer-events: none; }
  .lp-field input { width: 100%; background: #252A2F; border: 1px solid rgba(255,255,255,0.09); border-radius: 10px; padding: 13px 16px 13px 42px; font-family: 'Dosis', sans-serif; font-size: 15px; font-weight: 500; color: #F3F4F6; outline: none; transition: border-color 0.2s, background 0.2s, box-shadow 0.2s; letter-spacing: 0.3px; }
  .lp-field input::placeholder { color: #3D4550; font-weight: 400; }
  .lp-field input:focus { border-color: #3A9ED4; background: rgba(58,158,212,0.06); box-shadow: 0 0 0 3px rgba(58,158,212,0.12); }
  .lp-ferr { margin-top: 6px; font-size: 12px; font-weight: 500; color: #EF4444; }
  .lp-pin-wrap input { padding: 13px 16px 13px 42px; font-size: 20px; font-weight: 700; letter-spacing: 12px; caret-color: #F5A800; }
  .lp-pin-wrap input:focus { border-color: #F5A800; background: rgba(245,168,0,0.06); box-shadow: 0 0 0 3px rgba(245,168,0,0.14); }
  .lp-btn { width: 100%; padding: 14px; border: none; border-radius: 10px; background: linear-gradient(90deg, #F5A800 0%, #FFD166 50%, #F5A800 100%); background-size: 200% 100%; color: #0D0F11; font-family: 'Dosis', sans-serif; font-size: 14px; font-weight: 800; letter-spacing: 2.5px; text-transform: uppercase; cursor: pointer; transition: background-position 0.5s, transform 0.1s, box-shadow 0.3s; box-shadow: 0 4px 24px rgba(245,168,0,0.25); position: relative; overflow: hidden; }
  .lp-btn:hover { background-position: right center; box-shadow: 0 6px 32px rgba(245,168,0,0.40); }
  .lp-btn:active { transform: scale(0.99); }
  .lp-link { display: block; text-align: center; margin-top: 16px; font-size: 13px; color: #6B7280; }
  .lp-link a { color: #60C4F5; text-decoration: none; font-weight: 600; }
  .lp-link a:hover { color: #F5A800; }
  .lp-footer { margin-top: 20px; font-size: 11px; font-weight: 500; letter-spacing: 0.5px; color: #353A42; text-align: center; }
  .lp-section { font-size: 10px; font-weight: 800; letter-spacing: 2.5px; text-transform: uppercase; color: #F5A800; margin: 24px 0 12px; padding-bottom: 8px; border-bottom: 1px solid rgba(255,255,255,0.06); display: flex; align-items: center; gap: 8px; }
  .lp-section-icon { width: 24px; height: 24px; border-radius: 6px; background: rgba(245,168,0,0.12); display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
  .lp-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
  .lp-row .lp-field { margin-bottom: 14px; }
  @media (max-width: 480px) { .lp-card { padding: 28px 20px 24px; border-radius: 14px; } .lp-title { font-size: 28px; } .lp-row { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="lp-bg"><div class="lp-grid"></div></div>

<div class="lp-page">
  <div class="lp-logo-bar">
    <div class="lp-logo-icon"><span>P</span></div>
    <div>
      <div class="lp-logo-name">ParkFlow</div>
      <div class="lp-logo-sub">Management System</div>
    </div>
  </div>

  <div class="lp-card">
    <div class="lp-eyebrow">New Account</div>
    <div class="lp-title">Create Account</div>
    <div class="lp-sub">Register your company and start managing parking.</div>

    <hr class="lp-divider">

    @if(session('error'))
      <div class="lp-alert lp-alert-error">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('register.store') }}" autocomplete="off">
      @csrf

      {{-- ── PERSONAL INFORMATION ── --}}
      <div class="lp-section">
        <div class="lp-section-icon">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#F5A800" stroke-width="2.5" stroke-linecap="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        </div>
        Personal Information
      </div>

      {{-- Full Name --}}
      <div class="lp-field">
        <label>Full Name</label>
        <div class="lp-input-wrap">
          <span class="lp-input-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </span>
          <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="John Doe" required autofocus />
        </div>
        @error('name') <div class="lp-ferr">{{ $message }}</div> @enderror
      </div>

      {{-- Phone Number --}}
      <div class="lp-field">
        <label>Phone Number</label>
        <div class="lp-input-wrap">
          <span class="lp-input-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3-8.59A2 2 0 0 1 3.69 1.5h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9a16 16 0 0 0 6.91 6.91l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </span>
          <input type="tel" name="phone_number" id="phone_number" value="{{ old('phone_number') }}" placeholder="078 XXX XXXX" maxlength="10" required />
        </div>
        @error('phone_number') <div class="lp-ferr">{{ $message }}</div> @enderror
      </div>

      <div class="lp-row">
        {{-- PIN --}}
        <div class="lp-field">
          <label>4-Digit PIN</label>
          <div class="lp-pin-wrap lp-input-wrap">
            <span class="lp-input-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <input type="password" name="pin" id="pin" inputmode="numeric" maxlength="4" placeholder="••••" required />
          </div>
          @error('pin') <div class="lp-ferr">{{ $message }}</div> @enderror
        </div>

        {{-- Confirm PIN --}}
        <div class="lp-field">
          <label>Confirm PIN</label>
          <div class="lp-pin-wrap lp-input-wrap">
            <span class="lp-input-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </span>
            <input type="password" name="pin_confirmation" id="pin_confirmation" inputmode="numeric" maxlength="4" placeholder="••••" required />
          </div>
        </div>
      </div>

      {{-- ── COMPANY INFORMATION ── --}}
      <div class="lp-section">
        <div class="lp-section-icon">
          <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#F5A800" stroke-width="2.5" stroke-linecap="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        </div>
        Company Details
      </div>

      {{-- Company Name --}}
      <div class="lp-field">
        <label>Company Name</label>
        <div class="lp-input-wrap">
          <span class="lp-input-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
          </span>
          <input type="text" name="company_name" value="{{ old('company_name') }}" placeholder="Parking Solutions Ltd" required />
        </div>
        @error('company_name') <div class="lp-ferr">{{ $message }}</div> @enderror
      </div>

      <div class="lp-row">
        {{-- TIN --}}
        <div class="lp-field">
          <label>TIN Number</label>
          <div class="lp-input-wrap">
            <span class="lp-input-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </span>
            <input type="text" name="company_tin" value="{{ old('company_tin') }}" placeholder="1234567890" />
          </div>
          @error('company_tin') <div class="lp-ferr">{{ $message }}</div> @enderror
        </div>

        {{-- Company Phone --}}
        <div class="lp-field">
          <label>Company Phone</label>
          <div class="lp-input-wrap">
            <span class="lp-input-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3-8.59A2 2 0 0 1 3.69 1.5h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9a16 16 0 0 0 6.91 6.91l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
            </span>
            <input type="tel" name="company_phone" value="{{ old('company_phone') }}" placeholder="078 XXX XXXX" />
          </div>
          @error('company_phone') <div class="lp-ferr">{{ $message }}</div> @enderror
        </div>
      </div>

      <div class="lp-row">
        {{-- Company Email --}}
        <div class="lp-field">
          <label>Company Email</label>
          <div class="lp-input-wrap">
            <span class="lp-input-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
            </span>
            <input type="email" name="company_email" value="{{ old('company_email') }}" placeholder="info@company.com" />
          </div>
          @error('company_email') <div class="lp-ferr">{{ $message }}</div> @enderror
        </div>

        {{-- Company Address --}}
        <div class="lp-field">
          <label>Address</label>
          <div class="lp-input-wrap">
            <span class="lp-input-icon">
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
            </span>
            <input type="text" name="company_address" value="{{ old('company_address') }}" placeholder="Kigali, Rwanda" />
          </div>
          @error('company_address') <div class="lp-ferr">{{ $message }}</div> @enderror
        </div>
      </div>

      <button type="submit" class="lp-btn">Create Account</button>

      <div class="lp-link">
        Already have an account? <a href="{{ route('login') }}">Log in here</a>
      </div>
    </form>
  </div>

  <div class="lp-footer">&copy; {{ date('Y') }} ParkFlow Parking System &middot; All Rights Reserved</div>
</div>
@endsection
