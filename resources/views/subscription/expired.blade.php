@extends('layouts.app')

@section('title', 'Subscription Expired — ParkFlow')
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
    background: radial-gradient(ellipse 80% 60% at 15% 10%, rgba(248,113,113,0.10) 0%, transparent 55%),
                radial-gradient(ellipse 60% 50% at 85% 90%, rgba(58,158,212,0.08) 0%, transparent 55%),
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
    width: 100%; max-width: 480px;
    background: #1E2226;
    border: 1px solid rgba(255,255,255,0.09);
    border-radius: 20px;
    padding: 40px 40px 34px;
    position: relative; overflow: hidden;
    text-align: center;
  }

  .lp-card::before {
    content: '';
    position: absolute; top: 0; left: 0; right: 0; height: 3px;
    background: linear-gradient(90deg, #F87171, #FCA5A5 40%, #F5A800 65%, #FFD166);
  }

  .lp-expired-icon {
    width: 64px; height: 64px; border-radius: 16px;
    background: rgba(248,113,113,0.12);
    display: flex; align-items: center; justify-content: center;
    margin: 0 auto 20px;
  }

  .lp-title {
    font-size: 26px; font-weight: 800;
    color: #F3F4F6; margin-bottom: 8px;
  }

  .lp-sub {
    font-size: 13px; font-weight: 400;
    color: #6B7280; line-height: 1.6; margin-bottom: 24px;
  }

  .lp-contact {
    font-size: 12px; font-weight: 600;
    color: #9CA3AF;
    padding: 12px 16px; border-radius: 10px;
    background: rgba(255,255,255,0.04);
    border: 1px solid rgba(255,255,255,0.07);
    margin-bottom: 20px;
  }

  .lp-contact strong { color: #F3F4F6; }

  .lp-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 12px 28px; border: none; border-radius: 10px;
    background: rgba(255,255,255,0.07);
    color: #9CA3AF;
    font-family: 'Dosis', sans-serif; font-size: 13px;
    font-weight: 700; letter-spacing: 1px;
    cursor: pointer; transition: all 0.2s;
  }
  .lp-btn:hover { background: rgba(255,255,255,0.12); color: #F3F4F6; }

  .lp-footer {
    margin-top: 20px;
    font-size: 11px; font-weight: 500;
    letter-spacing: 0.5px;
    color: #353A42; text-align: center;
  }
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
    <div class="lp-expired-icon">
      <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#F87171" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="10"/>
        <line x1="12" y1="8" x2="12" y2="12"/>
        <line x1="12" y1="16" x2="12.01" y2="16"/>
      </svg>
    </div>
    <div class="lp-title">Subscription Expired</div>
    <div class="lp-sub">
      Your company's subscription has expired or is pending activation.<br>
      Please contact your administrator or the ParkFlow support team to renew your subscription and regain access.
    </div>
    <div class="lp-contact">
      Contact support: <strong>support@parkflow.rw</strong>
    </div>
    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
      @csrf
      <button type="submit" class="lp-btn">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
        Logout
      </button>
    </form>
  </div>

  <div class="lp-footer">&copy; {{ date('Y') }} ParkFlow — Parking Management System</div>
</div>
@endsection
