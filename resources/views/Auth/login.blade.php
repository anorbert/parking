@extends('layouts.app')

@section('title', 'Login - ParkFlow')
@section('body-class', 'lp-body')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Dosis:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  .lp-body {
    background: #111315 !important;
    min-height: 100vh;
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
    min-height: 100vh;
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
    font-family: 'Dosis', sans-serif;
  }

  .lp-title {
    font-size: 34px; font-weight: 800;
    letter-spacing: 1px; color: #F3F4F6;
    line-height: 1.1; margin-bottom: 4px;
    font-family: 'Dosis', sans-serif;
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

  .lp-field input[type="tel"],
  .lp-field input[type="password"],
  .lp-field input[type="text"] {
    width: 100%;
    background: #252A2F;
    border: 1px solid rgba(255,255,255,0.09);
    border-radius: 10px;
    padding: 13px 16px 13px 42px;
    font-family: 'Dosis', sans-serif;
    font-size: 15px; font-weight: 500;
    color: #F3F4F6; outline: none;
    transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
    letter-spacing: 0.3px;
  }

  .lp-field input::placeholder { color: #3D4550; font-weight: 400; }

  .lp-field input:focus {
    border-color: #3A9ED4;
    background: rgba(58,158,212,0.06);
    box-shadow: 0 0 0 3px rgba(58,158,212,0.12);
  }

  .lp-ferr {
    margin-top: 6px;
    font-size: 12px; font-weight: 500;
    color: #EF4444;
  }

  .lp-pin-wrap input {
    padding: 13px 44px 13px 42px;
    font-size: 20px; font-weight: 700;
    letter-spacing: 12px;
    caret-color: #F5A800;
  }

  .lp-pin-wrap input::placeholder {
    letter-spacing: 6px; font-size: 15px;
    font-weight: 400; color: #3D4550;
  }

  .lp-pin-wrap input:focus {
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

  .lp-pin-dots {
    display: flex; gap: 7px;
    justify-content: center; margin-top: 10px;
  }

  .lp-pdot {
    width: 8px; height: 3px; border-radius: 2px;
    background: rgba(255,255,255,0.10);
    transition: background 0.18s, width 0.18s;
  }

  .lp-pdot.on {
    width: 24px;
    background: linear-gradient(90deg, #F5A800, #FFD166);
  }

  .lp-remember-row {
    display: flex; align-items: center;
    justify-content: space-between; margin-bottom: 22px;
  }

  .lp-remember {
    display: flex; align-items: center;
    gap: 8px; cursor: pointer;
  }

  .lp-remember input[type="checkbox"] {
    width: 16px; height: 16px;
    accent-color: #F5A800; cursor: pointer;
  }

  .lp-remember span {
    font-size: 13px; font-weight: 500;
    color: #6B7280;
  }

  .lp-forgot {
    font-size: 13px; font-weight: 600;
    color: #60C4F5; text-decoration: none;
    transition: color 0.2s;
  }
  .lp-forgot:hover { color: #F5A800; }

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
  }

  .lp-btn:hover { background-position: right center; box-shadow: 0 6px 32px rgba(245,168,0,0.40); }
  .lp-btn:active { transform: scale(0.99); }

  .lp-register-link {
    display: block; text-align: center;
    margin-top: 16px; font-size: 13px;
    color: #6B7280;
  }

  .lp-register-link a {
    color: #60C4F5; text-decoration: none; font-weight: 600;
  }
  .lp-register-link a:hover { color: #F5A800; }

  .lp-badge-row {
    display: flex; justify-content: center;
    gap: 10px; margin-top: 20px;
  }

  .lp-badge {
    display: flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.09);
    border-radius: 999px;
    padding: 5px 14px;
    font-size: 12px; font-weight: 600;
    color: #9CA3AF; letter-spacing: 0.5px;
  }

  .lp-bdot {
    width: 7px; height: 7px; border-radius: 50%;
  }

  .lp-bdot.y { background: #F5A800; box-shadow: 0 0 6px rgba(245,168,0,0.5); }
  .lp-bdot.b { background: #60C4F5; box-shadow: 0 0 6px rgba(96,196,245,0.5); }
  .lp-bdot.g { background: #4ADE80; box-shadow: 0 0 6px rgba(74,222,128,0.5); }

  .lp-register-link {
    display: block; text-align: center;
    margin-top: 18px; font-size: 13px;
    color: #6B7280;
  }
  .lp-register-link a {
    color: #60C4F5; text-decoration: none; font-weight: 600;
    transition: color 0.2s;
  }
  .lp-register-link a:hover { color: #F5A800; }

  .lp-info {
    width: 100%; max-width: 680px;
    margin-top: 28px;
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px;
  }
  .lp-info-card {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.07);
    border-radius: 14px;
    padding: 20px 18px;
    text-align: center;
    transition: border-color 0.2s, background 0.2s;
  }
  .lp-info-card:hover {
    background: rgba(255,255,255,0.05);
    border-color: rgba(245,168,0,0.2);
  }
  .lp-info-icon {
    width: 40px; height: 40px; border-radius: 10px;
    display: inline-flex; align-items: center; justify-content: center;
    margin-bottom: 10px; font-size: 18px;
  }
  .lp-info-icon.yellow { background: rgba(245,168,0,0.12); color: #F5A800; }
  .lp-info-icon.blue   { background: rgba(58,158,212,0.12); color: #60C4F5; }
  .lp-info-icon.green  { background: rgba(74,222,128,0.12); color: #4ADE80; }
  .lp-info-title {
    font-size: 13px; font-weight: 800;
    color: #E5E7EB; letter-spacing: 0.3px;
    margin-bottom: 5px;
  }
  .lp-info-desc {
    font-size: 11px; font-weight: 400;
    color: #6B7280; line-height: 1.5;
  }

  .lp-learn-more {
    display: block; text-align: center;
    margin-top: 18px;
  }
  .lp-learn-more a {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 9px 22px; border-radius: 8px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.09);
    color: #60C4F5; font-size: 12px; font-weight: 700;
    text-decoration: none; letter-spacing: 1px;
    text-transform: uppercase;
    transition: background 0.2s, border-color 0.2s;
  }
  .lp-learn-more a:hover {
    background: rgba(96,196,245,0.08);
    border-color: rgba(96,196,245,0.25);
  }

  .lp-footer {
    margin-top: 20px;
    font-size: 11px; font-weight: 500;
    letter-spacing: 0.5px;
    color: #353A42; text-align: center;
  }

  @media (max-width: 480px) {
    .lp-card { padding: 28px 20px 24px; border-radius: 14px; }
    .lp-title { font-size: 28px; }
    .lp-badge-row { flex-wrap: wrap; }
    .lp-info { grid-template-columns: 1fr; }
  }
  @media (min-width: 481px) and (max-width: 640px) {
    .lp-info { grid-template-columns: 1fr 1fr; }
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
    <div class="lp-eyebrow">Secure Access Portal</div>
    <div class="lp-title">Welcome Back</div>
    <div class="lp-sub">Sign in to manage parking operations, vehicles, and payments.</div>

    <hr class="lp-divider">

    {{-- Flash Messages --}}
    @if(session('error'))
      <div class="lp-alert lp-alert-error">{{ session('error') }}</div>
    @endif
    @if(session('success'))
      <div class="lp-alert lp-alert-success">{{ session('success') }}</div>
    @endif
    @if(session('warning'))
      <div class="lp-alert lp-alert-warning">{{ session('warning') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}" autocomplete="off">
      @csrf

      {{-- Phone --}}
      <div class="lp-field">
        <label>Phone Number</label>
        <div class="lp-input-wrap">
          <span class="lp-input-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12a19.79 19.79 0 0 1-3-8.59A2 2 0 0 1 3.69 1.5h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9a16 16 0 0 0 6.91 6.91l.86-.86a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
          </span>
          <input type="tel" name="phone" id="lp-phone" value="{{ old('phone') }}" placeholder="078 XXX XXXX" maxlength="10" required />
        </div>
        @error('phone')
          <div class="lp-ferr">{{ $message }}</div>
        @enderror
      </div>

      {{-- PIN --}}
      <div class="lp-field" style="margin-bottom: 14px;">
        <label>4-Digit PIN</label>
        <div class="lp-pin-wrap lp-input-wrap">
          <span class="lp-input-icon">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
          </span>
          <input type="password" name="pin" id="lp-pin" inputmode="numeric" maxlength="4" placeholder="••••" autocomplete="off" required />
          <button type="button" class="lp-eye-btn" id="lp-eye-toggle" tabindex="-1">
            <svg class="lp-eye-show" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            <svg class="lp-eye-hide" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
          </button>
        </div>
        <div class="lp-pin-dots">
          <div class="lp-pdot" id="lp-d1"></div>
          <div class="lp-pdot" id="lp-d2"></div>
          <div class="lp-pdot" id="lp-d3"></div>
          <div class="lp-pdot" id="lp-d4"></div>
        </div>
        @error('pin')
          <div class="lp-ferr">{{ $message }}</div>
        @enderror
      </div>

      {{-- Remember / Forgot --}}
      <div class="lp-remember-row">
        <label class="lp-remember">
          <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
          <span>Remember me</span>
        </label>
        <a class="lp-forgot" href="{{ route('password.request') }}">Forgot PIN?</a>
      </div>

      <button type="submit" class="lp-btn">Log In to System</button>

      <div class="lp-register-link">
        New company? <a href="{{ route('register') }}">Register here</a>
      </div>
    </form>
  </div>

  {{-- System Info --}}
  <div class="lp-info">
    <div class="lp-info-card">
      <div class="lp-info-icon yellow">&#9881;</div>
      <div class="lp-info-title">Smart Management</div>
      <div class="lp-info-desc">Real-time slot tracking, automated billing, and live occupancy monitoring across all zones.</div>
    </div>
    <div class="lp-info-card">
      <div class="lp-info-icon blue">&#128179;</div>
      <div class="lp-info-title">Flexible Payments</div>
      <div class="lp-info-desc">Accept cash or MoMo mobile money. Instant receipts and full payment history for every transaction.</div>
    </div>
    <div class="lp-info-card">
      <div class="lp-info-icon green">&#128200;</div>
      <div class="lp-info-title">Reports &amp; Analytics</div>
      <div class="lp-info-desc">Daily revenue trends, zone usage stats, and exportable reports to keep your business on track.</div>
    </div>
  </div>

  <div class="lp-learn-more">
    <a href="{{ route('about') }}">
      Learn More About ParkFlow
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
    </a>
  </div>

  {{-- Badge Row --}}
  <div class="lp-badge-row">
    <div class="lp-badge"><div class="lp-bdot y"></div>500+ Slots</div>
    <div class="lp-badge"><div class="lp-bdot g"></div>24/7 Uptime</div>
    <div class="lp-badge"><div class="lp-bdot b"></div>RWF Currency</div>
  </div>

  <div class="lp-footer">&copy; {{ date('Y') }} ParkFlow Parking System &middot; All Rights Reserved</div>

</div>
@endsection

@push('scripts')
<script>
  (function() {
    const pin  = document.getElementById('lp-pin');
    const dots = [1,2,3,4].map(i => document.getElementById('lp-d'+i));
    const eyeBtn = document.getElementById('lp-eye-toggle');

    if (pin) {
      pin.addEventListener('input', function() {
        this.value = this.value.replace(/\D/g,'').slice(0,4);
        dots.forEach((d,i) => d && d.classList.toggle('on', i < this.value.length));
      });
    }

    if (eyeBtn && pin) {
      eyeBtn.addEventListener('click', function() {
        const isPass = pin.type === 'password';
        pin.type = isPass ? 'text' : 'password';
        this.querySelector('.lp-eye-show').style.display = isPass ? 'none' : '';
        this.querySelector('.lp-eye-hide').style.display = isPass ? '' : 'none';
      });
    }
  })();
</script>
@endpush
