@extends('layouts.app')

@section('title', 'Processing Payment — ParkFlow')
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
    background: radial-gradient(ellipse 80% 60% at 15% 10%, rgba(245,168,0,0.08) 0%, transparent 55%),
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

  .lp-card {
    width: 100%; max-width: 440px;
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
    background: linear-gradient(90deg, #F5A800, #FFD166 50%, #4ADE80);
  }

  .lp-spinner {
    width: 56px; height: 56px; margin: 0 auto 24px;
    border: 3px solid rgba(255,255,255,0.08);
    border-top-color: #F5A800;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  .lp-title {
    font-size: 22px; font-weight: 800;
    color: #F3F4F6; margin-bottom: 8px;
  }

  .lp-message {
    font-size: 13px; font-weight: 400;
    color: #9CA3AF; line-height: 1.6; margin-bottom: 24px;
  }

  .lp-status {
    font-size: 12px; font-weight: 700;
    color: #F5A800; letter-spacing: 1px;
    text-transform: uppercase;
  }

  .lp-status.success { color: #4ADE80; }
  .lp-status.failed { color: #F87171; }

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
  <div class="lp-card">
    <div class="lp-spinner" id="spinner"></div>
    <div class="lp-title" id="title">Processing Payment</div>
    <div class="lp-message" id="message">
      Please approve the payment on your phone.<br>
      Do not close this page.
    </div>
    <div class="lp-status" id="statusText">Waiting for confirmation...</div>
  </div>
  <div class="lp-footer">&copy; {{ date('Y') }} ParkFlow — Parking Management System</div>
</div>
@endsection

@push('scripts')
<script>
(function() {
  const trxRef = @json($trxRef);
  let attempts = 0;
  const maxAttempts = 60; // ~2 minutes

  function checkStatus() {
    attempts++;
    if (attempts > maxAttempts) {
      document.getElementById('title').textContent = 'Payment Timeout';
      document.getElementById('message').textContent = 'Payment confirmation timed out. Please try again.';
      document.getElementById('statusText').textContent = 'TIMED OUT';
      document.getElementById('statusText').className = 'lp-status failed';
      document.getElementById('spinner').style.display = 'none';
      setTimeout(() => window.location.href = '{{ route("subscription.expired") }}', 3000);
      return;
    }

    fetch('/api/subscription/check-status?trx_ref=' + encodeURIComponent(trxRef))
      .then(r => r.json())
      .then(data => {
        if (data.status === 'active') {
          document.getElementById('title').textContent = 'Payment Successful!';
          document.getElementById('message').textContent = 'Your subscription has been renewed. Redirecting...';
          document.getElementById('statusText').textContent = 'COMPLETED';
          document.getElementById('statusText').className = 'lp-status success';
          document.getElementById('spinner').style.display = 'none';

          // Determine redirect based on user role
          setTimeout(() => {
            window.location.href = '{{ auth()->user()->role_id == 2 ? route("admin.dashboard") : route("user.dashboard") }}';
          }, 2000);
        } else if (data.status === 'expired' || data.status === 'failed') {
          document.getElementById('title').textContent = 'Payment Failed';
          document.getElementById('message').textContent = data.message || 'Payment was not completed.';
          document.getElementById('statusText').textContent = 'FAILED';
          document.getElementById('statusText').className = 'lp-status failed';
          document.getElementById('spinner').style.display = 'none';
          setTimeout(() => window.location.href = '{{ route("subscription.expired") }}', 3000);
        } else {
          setTimeout(checkStatus, 2000);
        }
      })
      .catch(() => setTimeout(checkStatus, 3000));
  }

  setTimeout(checkStatus, 2000);
})();
</script>
@endpush
