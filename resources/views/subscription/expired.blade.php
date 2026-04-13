@extends('layouts.app')

@section('title', 'Subscription Expired — ParkFlow')
@section('body-class', 'lp-body')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Dosis:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  .lp-body {
    background: #111315 !important;
    overflow-y: auto !important;
    min-height: 100vh;
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
    width: 100%; max-width: 600px;
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

  /* Plans Grid */
  .lp-plans { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; margin-bottom: 24px; text-align: left; }
  .lp-plan-card {
    background: rgba(255,255,255,0.04);
    border: 2px solid rgba(255,255,255,0.08);
    border-radius: 14px; padding: 16px; cursor: pointer;
    transition: all 0.2s;
  }
  .lp-plan-card:hover { border-color: rgba(245,168,0,0.3); }
  .lp-plan-card.selected { border-color: #F5A800; background: rgba(245,168,0,0.06); }
  .lp-plan-name { font-size: 15px; font-weight: 800; color: #F3F4F6; margin-bottom: 4px; }
  .lp-plan-price { font-size: 20px; font-weight: 800; color: #F5A800; }
  .lp-plan-price small { font-size: 11px; font-weight: 500; color: #6B7280; }
  .lp-plan-duration { font-size: 11px; font-weight: 500; color: #6B7280; margin-top: 2px; }
  .lp-plan-desc { font-size: 11px; font-weight: 400; color: #6B7280; margin-top: 6px; line-height: 1.5; }
  .lp-plan-features { margin-top: 8px; display: flex; gap: 4px; flex-wrap: wrap; }
  .lp-plan-feat { font-size: 9px; font-weight: 700; padding: 2px 6px; border-radius: 4px; background: rgba(58,158,212,0.12); color: #3A9ED4; letter-spacing: 0.3px; }

  /* Payment Form */
  .lp-pay-section { text-align: left; margin-bottom: 20px; }
  .lp-pay-title { font-size: 12px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: #6B7280; margin-bottom: 10px; }
  .lp-input-wrap { position: relative; margin-bottom: 12px; }
  .lp-input {
    width: 100%; padding: 12px 14px; border-radius: 10px;
    border: 1px solid rgba(255,255,255,0.12); background: rgba(255,255,255,0.05);
    font-family: 'Dosis', sans-serif; font-size: 14px; font-weight: 600;
    color: #F3F4F6; box-sizing: border-box; transition: border-color 0.2s;
  }
  .lp-input::placeholder { color: #4B5563; }
  .lp-input:focus { outline: none; border-color: #F5A800; box-shadow: 0 0 0 3px rgba(245,168,0,0.15); }

  .lp-btn-pay {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    width: 100%; padding: 14px; border: none; border-radius: 10px;
    background: linear-gradient(135deg, #F5A800, #FFD166);
    color: #0D0F11; font-family: 'Dosis', sans-serif; font-size: 14px;
    font-weight: 800; letter-spacing: 1px; cursor: pointer;
    transition: all 0.2s;
  }
  .lp-btn-pay:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(245,168,0,0.3); }
  .lp-btn-pay:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

  .lp-divider { display: flex; align-items: center; gap: 12px; margin: 20px 0; }
  .lp-divider::before, .lp-divider::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,0.08); }
  .lp-divider span { font-size: 10px; font-weight: 700; color: #4B5563; letter-spacing: 2px; text-transform: uppercase; }

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

  .lp-alert {
    padding: 10px 16px; border-radius: 10px; font-size: 12px; font-weight: 600;
    margin-bottom: 16px; text-align: center;
  }
  .lp-alert-error { background: rgba(248,113,113,0.15); color: #FCA5A5; border: 1px solid rgba(248,113,113,0.2); }
  .lp-alert-success { background: rgba(74,222,128,0.15); color: #4ADE80; border: 1px solid rgba(74,222,128,0.2); }

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
      Your company's subscription has expired. Choose a plan below and pay via MoMo to renew instantly.
    </div>

    @if(session('error'))
      <div class="lp-alert lp-alert-error">{{ session('error') }}</div>
    @endif
    @if(session('success'))
      <div class="lp-alert lp-alert-success">{{ session('success') }}</div>
    @endif

    @if(isset($plans) && $plans->count())
    <form action="{{ route('subscription.pay') }}" method="POST" id="payForm">
      @csrf
      <input type="hidden" name="plan_id" id="selectedPlan" value="">

      {{-- Plan Selection --}}
      <div class="lp-plans">
        @foreach($plans as $plan)
        <div class="lp-plan-card" data-plan-id="{{ $plan->id }}" onclick="selectPlan(this, {{ $plan->id }})">
          <div class="lp-plan-name">{{ $plan->name }}</div>
          <div class="lp-plan-price">{{ number_format($plan->price) }} <small>RWF</small></div>
          <div class="lp-plan-duration">{{ $plan->duration_days }} days</div>
          @if($plan->description)
            <div class="lp-plan-desc">{{ $plan->description }}</div>
          @endif
          <div class="lp-plan-features">
            @if($plan->momo_payments) <span class="lp-plan-feat">MoMo</span> @endif
            @if($plan->reports_enabled) <span class="lp-plan-feat">Reports</span> @endif
            @if($plan->max_zones) <span class="lp-plan-feat">{{ $plan->max_zones }} Zones</span> @endif
            @if($plan->max_slots) <span class="lp-plan-feat">{{ $plan->max_slots }} Slots</span> @endif
          </div>
        </div>
        @endforeach
      </div>

      {{-- MoMo Payment --}}
      <div class="lp-pay-section">
        <div class="lp-pay-title">Pay with MTN/Airtel MoMo</div>
        <div class="lp-input-wrap">
          <input type="text" name="phone" class="lp-input" placeholder="Enter MoMo number (e.g. 0781234567)" required
                 pattern="^0(78|79|72|73)\d{7}$" maxlength="10">
        </div>
        <button type="submit" class="lp-btn-pay" id="payBtn" disabled>
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
          Pay &amp; Renew Subscription
        </button>
      </div>
    </form>
    @else
      <div class="lp-sub">No subscription plans are available. Please contact support.</div>
    @endif

    <div class="lp-divider"><span>or</span></div>

    <div class="lp-contact" style="font-size:12px;font-weight:600;color:#9CA3AF;padding:12px 16px;border-radius:10px;background:rgba(255,255,255,0.04);border:1px solid rgba(255,255,255,0.07);margin-bottom:20px;">
      Contact support: <strong style="color:#F3F4F6;">support@parkflow.rw</strong>
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

@push('scripts')
<script>
function selectPlan(el, planId) {
  document.querySelectorAll('.lp-plan-card').forEach(c => c.classList.remove('selected'));
  el.classList.add('selected');
  document.getElementById('selectedPlan').value = planId;
  document.getElementById('payBtn').disabled = false;
}
</script>
@endpush
