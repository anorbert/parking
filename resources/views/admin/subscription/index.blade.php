@extends('layouts.admin')

@section('title', 'My Subscription — ParkFlow')

@section('content')
<style>
/* ═══════ SUBSCRIPTION PAGE ═══════ */
.sp-header { margin-bottom: 24px; }
.sp-header h1 { font-size: 22px; font-weight: 800; color: #1A2030; margin: 0 0 4px; }
.sp-header p { font-size: 13px; font-weight: 500; color: #7A8290; margin: 0; }

.sp-alert {
  padding: 12px 16px; border-radius: 10px; font-size: 13px; font-weight: 600;
  margin-bottom: 20px; display: flex; align-items: center; gap: 10px;
}
.sp-alert-error { background: #FEF2F2; color: #DC2626; border: 1px solid #FECACA; }
.sp-alert-success { background: #F0FDF4; color: #16A34A; border: 1px solid #BBF7D0; }

/* ── Current Plan Card ── */
.sp-current {
  background: #FFFFFF; border-radius: 14px; padding: 24px;
  border: 1px solid #E8ECF0; margin-bottom: 24px;
  box-shadow: 0 1px 6px rgba(0,0,0,0.04);
}
.sp-current-top { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 18px; flex-wrap: wrap; gap: 12px; }
.sp-current-label { font-size: 10px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: #9CA3AF; margin-bottom: 6px; }
.sp-current-plan { font-size: 22px; font-weight: 800; color: #1A2030; }
.sp-current-price { font-size: 14px; font-weight: 700; color: #F5A800; margin-top: 2px; }

.sp-badge {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 5px 12px; border-radius: 20px;
  font-size: 11px; font-weight: 800; letter-spacing: 0.5px;
}
.sp-badge-active { background: #ECFDF5; color: #059669; }
.sp-badge-expired { background: #FEF2F2; color: #DC2626; }
.sp-badge-pending { background: #FFFBEB; color: #D97706; }

.sp-current-grid {
  display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
  gap: 12px; margin-bottom: 18px;
}
.sp-stat {
  background: #F8FAFC; border-radius: 10px; padding: 14px;
  border: 1px solid #F0F3F7;
}
.sp-stat-label { font-size: 10px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: #9CA3AF; margin-bottom: 4px; }
.sp-stat-value { font-size: 16px; font-weight: 800; color: #1A2030; }
.sp-stat-sub { font-size: 10px; font-weight: 500; color: #9CA3AF; margin-top: 2px; }

.sp-progress-wrap { margin-bottom: 6px; }
.sp-progress-bar {
  width: 100%; height: 6px; background: #E8ECF0; border-radius: 3px; overflow: hidden;
}
.sp-progress-fill {
  height: 100%; border-radius: 3px; transition: width 0.5s ease;
}
.sp-progress-fill.green { background: linear-gradient(90deg, #4ADE80, #22C55E); }
.sp-progress-fill.yellow { background: linear-gradient(90deg, #F5A800, #FFD166); }
.sp-progress-fill.red { background: linear-gradient(90deg, #F87171, #EF4444); }
.sp-progress-text { display: flex; justify-content: space-between; margin-top: 4px; }
.sp-progress-text span { font-size: 10px; font-weight: 600; color: #9CA3AF; }

/* ── No Subscription ── */
.sp-empty {
  background: #FFFFFF; border-radius: 14px; padding: 40px;
  border: 1px solid #E8ECF0; margin-bottom: 24px; text-align: center;
}
.sp-empty-icon {
  width: 56px; height: 56px; border-radius: 14px;
  background: #FFF7ED; display: flex; align-items: center; justify-content: center;
  margin: 0 auto 16px;
}
.sp-empty h3 { font-size: 17px; font-weight: 800; color: #1A2030; margin: 0 0 6px; }
.sp-empty p { font-size: 13px; font-weight: 500; color: #7A8290; margin: 0; }

/* ── Plans Comparison ── */
.sp-plans-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 16px; flex-wrap: wrap; gap: 8px;
}
.sp-plans-title { font-size: 16px; font-weight: 800; color: #1A2030; }
.sp-plans-sub { font-size: 12px; font-weight: 500; color: #7A8290; }

.sp-plans-grid {
  display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 16px; margin-bottom: 24px;
}

.sp-plan-card {
  background: #FFFFFF; border-radius: 14px; padding: 24px;
  border: 2px solid #E8ECF0; position: relative; overflow: hidden;
  transition: all 0.25s; cursor: default;
}
.sp-plan-card:hover { border-color: #D0D5DD; box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
.sp-plan-card.current { border-color: #F5A800; }
.sp-plan-card.current::before {
  content: 'CURRENT PLAN';
  position: absolute; top: 12px; right: -28px;
  background: linear-gradient(135deg, #F5A800, #FFD166);
  color: #0D0F11; font-size: 8px; font-weight: 800; letter-spacing: 1.5px;
  padding: 3px 32px; transform: rotate(45deg);
}
.sp-plan-card.recommended { border-color: #3A9ED4; }
.sp-plan-card.recommended::after {
  content: '★ RECOMMENDED';
  position: absolute; top: 0; left: 0; right: 0;
  background: linear-gradient(90deg, #3A9ED4, #60C4F5);
  color: #fff; font-size: 9px; font-weight: 800; letter-spacing: 1.5px;
  padding: 4px; text-align: center;
}
.sp-plan-card.recommended { padding-top: 36px; }

.sp-plan-name { font-size: 18px; font-weight: 800; color: #1A2030; margin-bottom: 4px; }
.sp-plan-desc { font-size: 12px; font-weight: 500; color: #7A8290; margin-bottom: 12px; line-height: 1.5; }

.sp-plan-price-row { display: flex; align-items: baseline; gap: 4px; margin-bottom: 14px; }
.sp-plan-amount { font-size: 28px; font-weight: 800; color: #1A2030; }
.sp-plan-currency { font-size: 13px; font-weight: 700; color: #9CA3AF; }
.sp-plan-period { font-size: 11px; font-weight: 600; color: #9CA3AF; }

.sp-plan-features { list-style: none; padding: 0; margin: 0 0 18px; }
.sp-plan-features li {
  display: flex; align-items: center; gap: 8px;
  padding: 6px 0; font-size: 12px; font-weight: 600; color: #4A5260;
  border-bottom: 1px solid #F5F7FA;
}
.sp-plan-features li:last-child { border-bottom: none; }
.sp-plan-features li svg { flex-shrink: 0; }
.sp-feat-yes { color: #059669; }
.sp-feat-no { color: #D1D5DB; }

.sp-plan-btn {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  width: 100%; padding: 11px; border: none; border-radius: 10px;
  font-family: 'Dosis', sans-serif; font-size: 13px; font-weight: 800;
  letter-spacing: 0.5px; cursor: pointer; transition: all 0.2s;
}
.sp-plan-btn-primary {
  background: linear-gradient(135deg, #F5A800, #FFD166);
  color: #0D0F11;
}
.sp-plan-btn-primary:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(245,168,0,0.3); }
.sp-plan-btn-upgrade {
  background: linear-gradient(135deg, #3A9ED4, #60C4F5);
  color: #fff;
}
.sp-plan-btn-upgrade:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(58,158,212,0.3); }
.sp-plan-btn-current {
  background: #F0F3F7; color: #9CA3AF; cursor: default;
}
.sp-plan-btn-current:hover { transform: none; box-shadow: none; }

/* ── Payment Modal ── */
.sp-modal-overlay {
  position: fixed; inset: 0; z-index: 9999;
  background: rgba(0,0,0,0.5);
  display: none; align-items: center; justify-content: center;
  padding: 20px;
}
.sp-modal-overlay.show { display: flex; }
.sp-modal {
  background: #FFFFFF; border-radius: 16px; padding: 32px;
  max-width: 420px; width: 100%;
  box-shadow: 0 20px 60px rgba(0,0,0,0.2);
  position: relative;
}
.sp-modal-close {
  position: absolute; top: 12px; right: 12px;
  width: 30px; height: 30px; border-radius: 8px;
  background: #F5F7FA; border: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  color: #6B7280; transition: all 0.15s;
}
.sp-modal-close:hover { background: #E8ECF0; color: #1A2030; }
.sp-modal h3 { font-size: 18px; font-weight: 800; color: #1A2030; margin: 0 0 4px; }
.sp-modal-sub { font-size: 12px; font-weight: 500; color: #7A8290; margin-bottom: 20px; }

.sp-modal-plan {
  display: flex; align-items: center; justify-content: space-between;
  background: #F8FAFC; border-radius: 10px; padding: 14px;
  border: 1px solid #E8ECF0; margin-bottom: 16px;
}
.sp-modal-plan-name { font-size: 15px; font-weight: 800; color: #1A2030; }
.sp-modal-plan-dur { font-size: 11px; font-weight: 500; color: #7A8290; }
.sp-modal-plan-price { font-size: 18px; font-weight: 800; color: #F5A800; }
.sp-modal-plan-price small { font-size: 11px; font-weight: 500; color: #9CA3AF; }

.sp-modal-label {
  font-size: 11px; font-weight: 800; letter-spacing: 1.5px;
  text-transform: uppercase; color: #7A8290; margin-bottom: 8px;
}
.sp-modal-input {
  width: 100%; padding: 12px 14px; border-radius: 10px;
  border: 1px solid #E2E6EA; background: #FFFFFF;
  font-family: 'Dosis', sans-serif; font-size: 14px; font-weight: 600;
  color: #1A2030; box-sizing: border-box; transition: border-color 0.2s;
  margin-bottom: 16px;
}
.sp-modal-input::placeholder { color: #B0B8C4; }
.sp-modal-input:focus { outline: none; border-color: #F5A800; box-shadow: 0 0 0 3px rgba(245,168,0,0.12); }

.sp-modal-btn {
  display: flex; align-items: center; justify-content: center; gap: 8px;
  width: 100%; padding: 13px; border: none; border-radius: 10px;
  font-family: 'Dosis', sans-serif; font-size: 14px; font-weight: 800;
  letter-spacing: 0.5px; cursor: pointer; transition: all 0.2s;
}
.sp-modal-btn-pay {
  background: linear-gradient(135deg, #F5A800, #FFD166); color: #0D0F11;
}
.sp-modal-btn-pay:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(245,168,0,0.3); }
.sp-modal-btn-pay:disabled { opacity: 0.5; cursor: not-allowed; transform: none; box-shadow: none; }

.sp-modal-note {
  display: flex; align-items: flex-start; gap: 8px;
  background: #FFFBEB; border-radius: 8px; padding: 10px 12px;
  margin-top: 14px; font-size: 11px; font-weight: 500;
  color: #92400E; line-height: 1.5;
}
.sp-modal-note svg { flex-shrink: 0; margin-top: 1px; }

/* ── History Table ── */
.sp-history {
  background: #FFFFFF; border-radius: 14px; padding: 24px;
  border: 1px solid #E8ECF0;
  box-shadow: 0 1px 6px rgba(0,0,0,0.04);
}
.sp-history-title { font-size: 16px; font-weight: 800; color: #1A2030; margin-bottom: 16px; }

.sp-table { width: 100%; border-collapse: collapse; }
.sp-table th {
  text-align: left; padding: 10px 12px;
  font-size: 10px; font-weight: 800; letter-spacing: 1.5px;
  text-transform: uppercase; color: #9CA3AF;
  border-bottom: 2px solid #F0F3F7;
}
.sp-table td {
  padding: 12px; font-size: 13px; font-weight: 600; color: #4A5260;
  border-bottom: 1px solid #F5F7FA;
}
.sp-table tr:last-child td { border-bottom: none; }
.sp-table tr:hover td { background: #FAFBFC; }

.sp-td-badge {
  display: inline-flex; padding: 3px 10px; border-radius: 12px;
  font-size: 10px; font-weight: 800; letter-spacing: 0.5px;
}

@media (max-width: 640px) {
  .sp-plans-grid { grid-template-columns: 1fr; }
  .sp-current-top { flex-direction: column; }
  .sp-current-grid { grid-template-columns: 1fr 1fr; }
  .sp-modal { padding: 24px; }
}
</style>

<div class="sp-header">
  <h1>My Subscription</h1>
  <p>Manage your plan, compare options, and renew or upgrade your subscription.</p>
</div>

@if(session('error'))
  <div class="sp-alert sp-alert-error">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    {{ session('error') }}
  </div>
@endif
@if(session('success'))
  <div class="sp-alert sp-alert-success">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    {{ session('success') }}
  </div>
@endif

{{-- ═══════ CURRENT SUBSCRIPTION ═══════ --}}
@if($currentSubscription)
  @php
    $plan = $currentSubscription->plan;
    $isActive = $currentSubscription->status === 'Active' && $currentSubscription->end_date->gte(now());
    $isPending = $currentSubscription->status === 'Pending';
    $isExpired = !$isActive && !$isPending;

    $totalDays = $currentSubscription->start_date->diffInDays($currentSubscription->end_date);
    $daysLeft = max(0, (int) now()->diffInDays($currentSubscription->end_date, false));
    $daysUsed = $totalDays - $daysLeft;
    $pct = $totalDays > 0 ? round(($daysLeft / $totalDays) * 100) : 0;

    $barColor = 'green';
    if ($pct <= 30) $barColor = 'red';
    elseif ($pct <= 60) $barColor = 'yellow';
  @endphp

  <div class="sp-current">
    <div class="sp-current-top">
      <div>
        <div class="sp-current-label">Current Plan</div>
        <div class="sp-current-plan">{{ $plan->name ?? 'Standard Plan' }}</div>
        <div class="sp-current-price">{{ number_format($currentSubscription->amount) }} RWF / {{ $plan->duration_days ?? 30 }} days</div>
      </div>
      <div>
        @if($isActive)
          <span class="sp-badge sp-badge-active">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Active
          </span>
        @elseif($isPending)
          <span class="sp-badge sp-badge-pending">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Pending Payment
          </span>
        @else
          <span class="sp-badge sp-badge-expired">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>
            Expired
          </span>
        @endif
      </div>
    </div>

    <div class="sp-current-grid">
      <div class="sp-stat">
        <div class="sp-stat-label">Start Date</div>
        <div class="sp-stat-value">{{ $currentSubscription->start_date->format('d M Y') }}</div>
      </div>
      <div class="sp-stat">
        <div class="sp-stat-label">End Date</div>
        <div class="sp-stat-value">{{ $currentSubscription->end_date->format('d M Y') }}</div>
      </div>
      <div class="sp-stat">
        <div class="sp-stat-label">Days Remaining</div>
        <div class="sp-stat-value" style="color: {{ $isExpired ? '#DC2626' : ($daysLeft <= 7 ? '#D97706' : '#059669') }};">
          {{ $isExpired ? 'Expired' : $daysLeft . ' days' }}
        </div>
        @if(!$isExpired && $daysLeft <= 7)
          <div class="sp-stat-sub" style="color: #D97706;">⚠ Renew soon!</div>
        @endif
      </div>
      <div class="sp-stat">
        <div class="sp-stat-label">Payment Method</div>
        <div class="sp-stat-value" style="font-size: 14px;">{{ ucfirst($currentSubscription->payment_method ?? 'N/A') }}</div>
      </div>
    </div>

    @if($isActive)
    <div class="sp-progress-wrap">
      <div class="sp-progress-bar">
        <div class="sp-progress-fill {{ $barColor }}" style="width: {{ $pct }}%;"></div>
      </div>
      <div class="sp-progress-text">
        <span>{{ $daysUsed }} days used</span>
        <span>{{ $daysLeft }} days left</span>
      </div>
    </div>
    @endif
  </div>
@else
  <div class="sp-empty">
    <div class="sp-empty-icon">
      <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#D97706" stroke-width="2">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
      </svg>
    </div>
    <h3>No Subscription Found</h3>
    <p>Choose a plan below to get started with your subscription.</p>
  </div>
@endif

{{-- ═══════ PLAN COMPARISON ═══════ --}}
<div class="sp-plans-header">
  <div>
    <div class="sp-plans-title">Available Plans</div>
    <div class="sp-plans-sub">Compare features and choose the best plan for your company.</div>
  </div>
</div>

@if($plans->count())
<div class="sp-plans-grid">
  @foreach($plans as $index => $plan)
    @php
      $currentPlanPrice = $currentSubscription ? ($currentSubscription->plan->price ?? $currentSubscription->amount) : 0;
      $hasActiveSub = $currentSubscription && $currentSubscription->status === 'Active' && $currentSubscription->end_date->gte(now());
      $isCurrent = $hasActiveSub && $currentSubscription->plan_id === $plan->id && $currentSubscription->plan_id !== null;
      $isUpgrade = $hasActiveSub && !$isCurrent && $plan->price > $currentPlanPrice;
      $isRecommended = !$isCurrent && $index === 1 && $plans->count() > 1;
    @endphp
    <div class="sp-plan-card {{ $isCurrent ? 'current' : '' }} {{ $isRecommended ? 'recommended' : '' }}">
      <div class="sp-plan-name">{{ $plan->name }}</div>
      @if($plan->description)
        <div class="sp-plan-desc">{{ $plan->description }}</div>
      @endif

      <div class="sp-plan-price-row">
        <span class="sp-plan-amount">{{ number_format($plan->price) }}</span>
        <span class="sp-plan-currency">RWF</span>
        <span class="sp-plan-period">/ {{ $plan->duration_days }} days</span>
      </div>

      <ul class="sp-plan-features">
        <li>
          <svg class="sp-feat-yes" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          {{ $plan->max_zones ? $plan->max_zones . ' Zones' : 'Unlimited Zones' }}
        </li>
        <li>
          <svg class="sp-feat-yes" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          {{ $plan->max_slots ? $plan->max_slots . ' Slots' : 'Unlimited Slots' }}
        </li>
        <li>
          <svg class="sp-feat-yes" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          {{ $plan->max_users ? $plan->max_users . ' Staff Users' : 'Unlimited Staff' }}
        </li>
        <li>
          @if($plan->momo_payments)
            <svg class="sp-feat-yes" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          @else
            <svg class="sp-feat-no" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          @endif
          MoMo Payments
        </li>
        <li>
          @if($plan->reports_enabled)
            <svg class="sp-feat-yes" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          @else
            <svg class="sp-feat-no" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
          @endif
          Advanced Reports
        </li>
      </ul>

      @if($isCurrent)
        <button class="sp-plan-btn sp-plan-btn-current" disabled>
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          Current Plan
        </button>
      @elseif($isUpgrade)
        <button class="sp-plan-btn sp-plan-btn-upgrade" onclick="openPayModal({{ $plan->id }}, '{{ addslashes($plan->name) }}', '{{ number_format($plan->price) }}', '{{ $plan->duration_days }}', 'upgrade')">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg>
          Upgrade to {{ $plan->name }}
        </button>
      @else
        <button class="sp-plan-btn sp-plan-btn-primary" onclick="openPayModal({{ $plan->id }}, '{{ addslashes($plan->name) }}', '{{ number_format($plan->price) }}', '{{ $plan->duration_days }}', 'renew')">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
          {{ $currentSubscription ? 'Renew with ' . $plan->name : 'Choose ' . $plan->name }}
        </button>
      @endif
    </div>
  @endforeach
</div>
@else
  <div class="sp-empty" style="margin-bottom: 24px;">
    <h3>No Plans Available</h3>
    <p>No subscription plans are available at the moment. Please contact support.</p>
  </div>
@endif

{{-- ═══════ SUBSCRIPTION HISTORY ═══════ --}}
@if($subscriptionHistory->count())
<div class="sp-history">
  <div class="sp-history-title">Subscription History</div>
  <div style="overflow-x: auto;">
    <table class="sp-table">
      <thead>
        <tr>
          <th>Plan</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Start</th>
          <th>End</th>
          <th>Payment</th>
        </tr>
      </thead>
      <tbody>
        @foreach($subscriptionHistory as $sub)
        <tr>
          <td style="font-weight: 800; color: #1A2030;">{{ $sub->plan->name ?? 'Standard' }}</td>
          <td>{{ number_format($sub->amount) }} RWF</td>
          <td>
            @if($sub->status === 'Active' && $sub->end_date->gte(now()))
              <span class="sp-td-badge" style="background: #ECFDF5; color: #059669;">Active</span>
            @elseif($sub->status === 'Pending')
              <span class="sp-td-badge" style="background: #FFFBEB; color: #D97706;">Pending</span>
            @else
              <span class="sp-td-badge" style="background: #FEF2F2; color: #DC2626;">Expired</span>
            @endif
          </td>
          <td>{{ $sub->start_date->format('d M Y') }}</td>
          <td>{{ $sub->end_date->format('d M Y') }}</td>
          <td>{{ ucfirst($sub->payment_method ?? 'N/A') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif

{{-- ═══════ PAYMENT MODAL ═══════ --}}
<div class="sp-modal-overlay" id="payModal">
  <div class="sp-modal">
    <button class="sp-modal-close" onclick="closePayModal()">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>

    <h3 id="modalTitle">Renew Subscription</h3>
    <div class="sp-modal-sub" id="modalSub">Pay via MTN/Airtel MoMo to activate your plan instantly.</div>

    <div class="sp-modal-plan">
      <div>
        <div class="sp-modal-plan-name" id="modalPlanName">—</div>
        <div class="sp-modal-plan-dur" id="modalPlanDur">—</div>
      </div>
      <div class="sp-modal-plan-price" id="modalPlanPrice">—</div>
    </div>

    <form id="payForm" method="POST">
      @csrf
      <input type="hidden" name="plan_id" id="modalPlanId">

      <div class="sp-modal-label">MoMo Phone Number</div>
      <input type="text" name="phone" class="sp-modal-input" id="modalPhone"
             placeholder="e.g. 0781234567" required
             pattern="^0(78|79|72|73)\d{7}$" maxlength="10">

      <button type="submit" class="sp-modal-btn sp-modal-btn-pay" id="modalPayBtn">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        Pay &amp; Activate
      </button>
    </form>

    <div class="sp-modal-note">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
      <span>You'll receive a payment prompt on your phone. Approve it to activate your subscription instantly. If payment fails, you can try again.</span>
    </div>
  </div>
</div>

<script>
function openPayModal(planId, planName, planPrice, duration, type) {
  document.getElementById('modalPlanId').value = planId;
  document.getElementById('modalPlanName').textContent = planName;
  document.getElementById('modalPlanPrice').innerHTML = planPrice + ' <small>RWF</small>';
  document.getElementById('modalPlanDur').textContent = duration + ' days';
  document.getElementById('modalPhone').value = '';

  if (type === 'upgrade') {
    document.getElementById('modalTitle').textContent = 'Upgrade Plan';
    document.getElementById('modalSub').textContent = 'Upgrade to ' + planName + ' — pay via MoMo to switch instantly.';
    document.getElementById('payForm').action = '{{ route("admin.subscription.upgrade") }}';
    document.getElementById('modalPayBtn').innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="19" x2="12" y2="5"/><polyline points="5 12 12 5 19 12"/></svg> Pay &amp; Upgrade';
  } else {
    document.getElementById('modalTitle').textContent = 'Renew Subscription';
    document.getElementById('modalSub').textContent = 'Renew with ' + planName + ' — pay via MoMo to activate instantly.';
    document.getElementById('payForm').action = '{{ route("admin.subscription.renew") }}';
    document.getElementById('modalPayBtn').innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg> Pay &amp; Renew';
  }

  document.getElementById('payModal').classList.add('show');
  document.getElementById('modalPhone').focus();
}

function closePayModal() {
  document.getElementById('payModal').classList.remove('show');
}

// Close modal on background click
document.getElementById('payModal').addEventListener('click', function(e) {
  if (e.target === this) closePayModal();
});

// Close on Escape
document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') closePayModal();
});
</script>
@endsection
