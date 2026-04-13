@extends('layouts.user')

@section('title', 'Parking Management — ParkFlow')
@section('page-title', 'Parking Management')

@push('styles')
<style>
/* ALERT */
.uf-alert {
  display: flex; align-items: center; gap: 10px;
  padding: 12px 16px; border-radius: 10px;
  font-size: 13px; font-weight: 600;
  margin-bottom: 16px;
  animation: ufFadeIn 0.3s ease;
}
@keyframes ufFadeIn { from { opacity:0; transform: translateY(-6px); } to { opacity:1; transform: translateY(0); } }
.uf-alert-success { background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.25); color: #16A34A; }
.uf-alert-danger  { background: rgba(239,68,68,0.1);  border: 1px solid rgba(239,68,68,0.25);  color: #DC2626; }

/* STAT TILES */
.uf-tiles { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 20px; }
.uf-tile {
  background: var(--uf-card);
  border: 1px solid var(--uf-border); border-radius: 14px;
  padding: 18px; position: relative; overflow: hidden;
}
.uf-tile-accent { position: absolute; top: 0; left: 0; right: 0; height: 3px; }
.uf-tile-icon {
  width: 38px; height: 38px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center; margin-bottom: 12px;
}
.uf-tile-label { font-size: 10px; font-weight: 700; letter-spacing: 1.8px; text-transform: uppercase; color: var(--uf-muted); margin-bottom: 5px; }
.uf-tile-value { font-size: 26px; font-weight: 800; color: var(--uf-dark); line-height: 1; }
.uf-tile-delta { display: flex; align-items: center; gap: 4px; margin-top: 7px; font-size: 11px; font-weight: 600; }
.uf-delta-up   { color: var(--uf-green); }
.uf-delta-muted { color: var(--uf-muted); }

/* CARD */
.uf-card {
  background: var(--uf-card); border: 1px solid var(--uf-border);
  border-radius: 14px; overflow: hidden; margin-bottom: 18px;
}
.uf-card-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 16px 20px; border-bottom: 1px solid var(--uf-border);
}
.uf-card-title { font-size: 14px; font-weight: 800; color: var(--uf-dark); }
.uf-card-sub   { font-size: 11px; font-weight: 500; color: var(--uf-muted); margin-top: 2px; }
.uf-card-body  { padding: 20px; }
.uf-card-body-np { padding: 0; }

/* PAGE HEADER */
.uf-page-header {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 20px;
}
.uf-page-title { font-size: 22px; font-weight: 800; color: var(--uf-dark); letter-spacing: 0.2px; }
.uf-page-sub   { font-size: 12px; font-weight: 500; color: var(--uf-muted); margin-top: 2px; }

/* FORM GRID */
.uf-form-grid { display: flex; gap: 14px; align-items: flex-end; }
.uf-form-grid .uf-field { flex: 1; min-width: 0; }
.uf-form-grid .uf-btn { flex-shrink: 0; height: 48px; }
.uf-field { display: flex; flex-direction: column; gap: 7px; }
.uf-field label {
  font-size: 11px; font-weight: 700; letter-spacing: 1.5px;
  text-transform: uppercase; color: var(--uf-soft);
}
.uf-field input[type="text"],
.uf-field input[type="tel"],
.uf-field select {
  background: #F5F7FA; border: 1.5px solid var(--uf-border);
  border-radius: 9px; padding: 11px 14px;
  font-family: var(--uf-font); font-size: 14px; font-weight: 600;
  color: var(--uf-dark); outline: none;
  transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
  letter-spacing: 0.5px; width: 100%;
}
.uf-field input::placeholder { color: #C4C9D0; font-weight: 400; }
.uf-field input:focus, .uf-field select:focus {
  border-color: var(--uf-blue);
  background: #FFF;
  box-shadow: 0 0 0 3px rgba(58,158,212,0.12);
}
.uf-field-hint { font-size: 11px; color: var(--uf-muted); font-weight: 500; }

.uf-plate-input {
  font-size: 18px !important; font-weight: 800 !important;
  letter-spacing: 4px !important; text-transform: uppercase;
}

/* BUTTON */
.uf-btn {
  display: inline-flex; align-items: center; justify-content: center; gap: 7px;
  padding: 12px 20px; border-radius: 9px;
  font-family: var(--uf-font); font-size: 13px; font-weight: 800;
  letter-spacing: 1px; text-transform: uppercase;
  border: none; cursor: pointer; transition: all 0.18s;
  white-space: nowrap;
}
.uf-btn-primary {
  background: linear-gradient(90deg, #F5A800, #FFD166);
  background-size: 200% 100%;
  color: #0D0F11;
  box-shadow: 0 3px 14px rgba(245,168,0,0.28);
}
.uf-btn-primary:hover { background-position: right center; box-shadow: 0 5px 20px rgba(245,168,0,0.4); }
.uf-btn-danger {
  background: rgba(239,68,68,0.1); color: var(--uf-red);
  border: 1px solid rgba(239,68,68,0.2);
  padding: 7px 14px; font-size: 11px;
}
.uf-btn-danger:hover { background: rgba(239,68,68,0.18); border-color: rgba(239,68,68,0.35); }
.uf-btn-secondary {
  background: #F0F3F7; color: #6B7280;
  border: 1px solid var(--uf-border);
}
.uf-btn-secondary:hover { background: #E4E8EE; }
.uf-btn-success {
  background: linear-gradient(90deg, #16A34A, #22C55E);
  color: #FFF;
  box-shadow: 0 3px 14px rgba(34,197,94,0.25);
}
.uf-btn-success:hover { box-shadow: 0 5px 20px rgba(34,197,94,0.35); }

/* TABLE */
.uf-tbl { width: 100%; border-collapse: collapse; font-size: 13px; }
.uf-tbl thead tr { border-bottom: 2px solid #EEF0F3; }
.uf-tbl thead th {
  padding: 11px 16px; text-align: left;
  font-size: 10px; font-weight: 800;
  letter-spacing: 1.5px; text-transform: uppercase; color: var(--uf-muted);
  white-space: nowrap; background: #FAFBFC;
}
.uf-tbl tbody tr { border-bottom: 1px solid #F3F4F6; transition: background 0.1s; }
.uf-tbl tbody tr:hover { background: #F8F9FB; }
.uf-tbl tbody tr:last-child { border-bottom: none; }
.uf-tbl td { padding: 13px 16px; font-weight: 600; color: var(--uf-soft); vertical-align: middle; }
.uf-tbl td:first-child { font-weight: 800; color: var(--uf-dark); letter-spacing: 1px; }

.uf-plate-badge {
  display: inline-flex; align-items: center; gap: 6px;
  background: #F0F3F7; border: 1px solid #DDE2E8;
  border-radius: 6px; padding: 4px 10px;
  font-size: 13px; font-weight: 800;
  color: var(--uf-dark); letter-spacing: 2px;
}
.uf-time-text { font-size: 12px; font-weight: 600; color: var(--uf-soft); }
.uf-pill {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 4px 10px; border-radius: 999px;
  font-size: 10px; font-weight: 800; letter-spacing: 0.5px;
}
.uf-pill-green  { background: rgba(34,197,94,0.1);  color: #16A34A; }
.uf-pill-yellow { background: rgba(245,168,0,0.1);  color: #D97706; }
.uf-pill-blue   { background: rgba(58,158,212,0.1); color: #0369A1; }

/* EMPTY STATE */
.uf-empty {
  text-align: center; padding: 48px 20px;
  display: flex; flex-direction: column; align-items: center; gap: 10px;
}
.uf-empty-icon {
  width: 56px; height: 56px; border-radius: 14px;
  background: #F0F3F7; display: flex; align-items: center; justify-content: center;
  margin-bottom: 4px;
}
.uf-empty-title { font-size: 14px; font-weight: 800; color: var(--uf-dark); }
.uf-empty-sub   { font-size: 12px; font-weight: 500; color: var(--uf-muted); max-width: 260px; }

/* ═══ MODAL ═══ */
.uf-modal-overlay {
  position: fixed; inset: 0; z-index: 1000;
  background: rgba(10,14,22,0.55);
  backdrop-filter: blur(4px);
  display: flex; align-items: center; justify-content: center;
  padding: 20px;
  opacity: 0; pointer-events: none; transition: opacity 0.2s;
}
.uf-modal-overlay.open { opacity: 1; pointer-events: all; }

.uf-modal-box {
  background: #FFF; border-radius: 18px;
  width: 100%; max-width: 540px;
  box-shadow: 0 24px 60px rgba(0,0,0,0.22);
  overflow: hidden;
  transform: translateY(16px); transition: transform 0.25s;
}
.uf-modal-overlay.open .uf-modal-box { transform: translateY(0); }

.uf-modal-top {
  height: 4px;
  background: linear-gradient(90deg, #F5A800, #FFD166 40%, #3A9ED4 70%, #60C4F5);
}
.uf-modal-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 22px 24px 16px;
  border-bottom: 1px solid var(--uf-border);
}
.uf-modal-title { font-size: 18px; font-weight: 800; color: var(--uf-dark); }
.uf-modal-close {
  width: 32px; height: 32px; border-radius: 8px;
  background: #F0F3F7; border: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  color: #6B7280; font-size: 18px; transition: all 0.15s;
}
.uf-modal-close:hover { background: #E4E8EE; color: var(--uf-dark); }

.uf-modal-body { padding: 22px 24px; }

.uf-modal-alert {
  display: none; align-items: center; gap: 8px;
  background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);
  border-radius: 8px; padding: 10px 14px;
  font-size: 12px; font-weight: 600; color: #DC2626;
  margin-bottom: 16px;
}
.uf-modal-alert.show { display: flex; }

/* BILL */
.uf-bill-grid {
  display: grid; grid-template-columns: 1fr 1fr; gap: 14px;
  margin-bottom: 18px;
}
.uf-bill-card {
  background: #F8F9FB; border: 1px solid #EEF0F3;
  border-radius: 10px; padding: 14px;
}
.uf-bill-label { font-size: 10px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--uf-muted); margin-bottom: 5px; }
.uf-bill-value { font-size: 14px; font-weight: 800; color: var(--uf-dark); }

.uf-bill-amount-box {
  background: linear-gradient(135deg, #1B2235, #232C42);
  border-radius: 12px; padding: 18px 20px;
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 18px;
}
.uf-bill-amount-label { font-size: 12px; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: rgba(255,255,255,0.5); }
.uf-bill-amount-value { font-size: 32px; font-weight: 800; color: var(--uf-yellow); letter-spacing: -0.5px; }
.uf-bill-amount-cur   { font-size: 14px; font-weight: 700; color: rgba(255,255,255,0.5); }

/* PAYMENT METHOD */
.uf-pay-methods { display: flex; gap: 10px; margin-bottom: 14px; }
.uf-pay-method {
  flex: 1; border: 2px solid var(--uf-border);
  border-radius: 10px; padding: 12px;
  cursor: pointer; text-align: center; transition: all 0.15s;
  background: #F8F9FB;
}
.uf-pay-method:hover { border-color: #D0D5DD; background: #F0F3F7; }
.uf-pay-method.selected {
  border-color: var(--uf-yellow);
  background: rgba(245,168,0,0.06);
}
.uf-pay-method-icon { font-size: 22px; margin-bottom: 4px; }
.uf-pay-method-label { font-size: 11px; font-weight: 800; color: var(--uf-soft); letter-spacing: 0.5px; text-transform: uppercase; }
.uf-pay-method.selected .uf-pay-method-label { color: #D97706; }

.uf-phone-field { display: none; }
.uf-phone-field.show { display: block; margin-top: 4px; }

.uf-momo-status {
  display: none; align-items: center; gap: 10px;
  background: rgba(58,158,212,0.08); border: 1px solid rgba(58,158,212,0.2);
  border-radius: 8px; padding: 12px 14px;
  font-size: 12px; font-weight: 700; color: #0369A1;
  margin-top: 12px;
}
.uf-momo-status.show { display: flex; }
.uf-momo-spinner {
  width: 16px; height: 16px; border-radius: 50%;
  border: 2px solid rgba(58,158,212,0.3);
  border-top-color: var(--uf-blue);
  animation: ufSpin 0.8s linear infinite; flex-shrink: 0;
}
@keyframes ufSpin { to { transform: rotate(360deg); } }

.uf-modal-foot {
  display: flex; gap: 10px; justify-content: flex-end;
  padding: 16px 24px; border-top: 1px solid var(--uf-border);
  background: #FAFBFC;
}

@media (max-width: 992px) {
  .uf-tiles { grid-template-columns: repeat(2, 1fr); }
  .uf-form-grid { flex-direction: column; }
}
@media (max-width: 576px) {
  .uf-tiles { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

{{-- ALERTS --}}
@if(session('success'))
  <div class="uf-alert uf-alert-success">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
    {{ session('success') }}
  </div>
@endif
@if(session('error'))
  <div class="uf-alert uf-alert-danger">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    {{ session('error') }}
  </div>
@endif

{{-- PAGE HEADER --}}
<div class="uf-page-header">
  <div>
    <div class="uf-page-title">Parking Management</div>
    <div class="uf-page-sub">Register entries, process exits, and manage active vehicles</div>
  </div>
  <div id="ufLiveClock" style="font-size:13px;font-weight:700;color:var(--uf-muted);font-variant-numeric:tabular-nums;"></div>
</div>

{{-- NEW ENTRY CARD --}}
<div class="uf-card">
  <div class="uf-card-head">
    <div>
      <div class="uf-card-title">New Car Entry</div>
      <div class="uf-card-sub">Register a vehicle entering the parking facility</div>
    </div>
    <span class="uf-pill uf-pill-yellow">● Entry Point</span>
  </div>
  <div class="uf-card-body">
    <form method="POST" action="{{ route('user.parking.entry') }}" class="uf-form-grid">
      @csrf
      <div class="uf-field">
        <label>Plate Number</label>
        <input type="text" name="plate_number" class="uf-plate-input" placeholder="RAB 123 Z" required
               oninput="this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g,'')" />
        <span class="uf-field-hint">Format: RAB123Z &nbsp;·&nbsp; Letters &amp; numbers only</span>
      </div>
      <button type="submit" class="uf-btn uf-btn-primary">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
        Validate &amp; Enter
      </button>
    </form>
  </div>
</div>

{{-- STAT TILES --}}
<div class="uf-tiles">
  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#3A9ED4,#60C4F5);"></div>
    <div class="uf-tile-icon" style="background:rgba(58,158,212,0.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3A9ED4" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/></svg>
    </div>
    <div class="uf-tile-label">Total Slots</div>
    <div class="uf-tile-value">{{ $totalSlots ?? 0 }}</div>
    <div class="uf-tile-delta"><span class="uf-delta-up">● Active</span></div>
  </div>

  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#EF4444,#FCA5A5);"></div>
    <div class="uf-tile-icon" style="background:rgba(239,68,68,0.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#EF4444" stroke-width="2" stroke-linecap="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    </div>
    <div class="uf-tile-label">Currently Parked</div>
    <div class="uf-tile-value">{{ $activeParkings->count() }}</div>
    <div class="uf-tile-delta"><span class="uf-delta-muted">Vehicles inside</span></div>
  </div>

  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#22C55E,#86EFAC);"></div>
    <div class="uf-tile-icon" style="background:rgba(34,197,94,0.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="2" stroke-linecap="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
    </div>
    <div class="uf-tile-label">Available Slots</div>
    <div class="uf-tile-value">{{ ($totalSlots ?? 0) - $activeParkings->count() }}</div>
    <div class="uf-tile-delta"><span class="uf-delta-up">● Free</span></div>
  </div>

  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#F5A800,#FFD166);"></div>
    <div class="uf-tile-icon" style="background:rgba(245,168,0,0.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F5A800" stroke-width="2" stroke-linecap="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    </div>
    <div class="uf-tile-label">Revenue Today</div>
    <div class="uf-tile-value" style="font-size:20px;">{{ number_format($todayRevenue ?? 0) }} <span style="font-size:11px;color:var(--uf-muted);">RWF</span></div>
    <div class="uf-tile-delta"><span class="uf-delta-muted">Today's collection</span></div>
  </div>
</div>

{{-- ACTIVE VEHICLES TABLE --}}
<div class="uf-card">
  <div class="uf-card-head">
    <div>
      <div class="uf-card-title">Currently Parked Vehicles</div>
      <div class="uf-card-sub">Click "Exit &amp; Bill" to process a vehicle departure</div>
    </div>
    <span class="uf-pill uf-pill-green">● {{ $activeParkings->count() }} Active</span>
  </div>
  <div class="uf-card-body-np">
    @if($activeParkings->count() > 0)
    <table class="uf-tbl">
      <thead>
        <tr>
          <th>Plate Number</th>
          <th>Entry Time</th>
          <th>Zone</th>
          <th>Duration</th>
          <th>Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($activeParkings as $parking)
        @php
          $entryTime = \Carbon\Carbon::parse($parking->entry_time);
          $duration  = $entryTime->diff(now());
          $durationStr = $duration->h > 0 ? $duration->h . 'h ' . $duration->i . 'm' : $duration->i . 'm';
        @endphp
        <tr>
          <td><span class="uf-plate-badge">{{ $parking->plate_number }}</span></td>
          <td><span class="uf-time-text">{{ $entryTime->format('d M Y, H:i') }}</span></td>
          <td>{{ $parking->zone->name ?? 'N/A' }}</td>
          <td><span class="uf-pill {{ $duration->h > 0 ? 'uf-pill-yellow' : 'uf-pill-blue' }}">{{ $durationStr }}</span></td>
          <td><span class="uf-pill uf-pill-green">Parked</span></td>
          <td>
            <button class="uf-btn uf-btn-danger" onclick="openExitModal({{ $parking->id }})">Exit &amp; Bill</button>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
    @else
    <div class="uf-empty">
      <div class="uf-empty-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      </div>
      <div class="uf-empty-title">No active parkings</div>
      <div class="uf-empty-sub">All parking spots are empty. Use the form above to register a new vehicle entry.</div>
    </div>
    @endif
  </div>
</div>

{{-- ═══ EXIT MODAL ═══ --}}
<div class="uf-modal-overlay" id="exitOverlay" onclick="handleOverlayClick(event)">
  <div class="uf-modal-box">
    <div class="uf-modal-top"></div>
    <div class="uf-modal-head">
      <div class="uf-modal-title">Confirm Vehicle Exit</div>
      <button class="uf-modal-close" onclick="closeModal()">×</button>
    </div>
    <form id="exitForm" onsubmit="handlePayment(event)">
      @csrf
      <div class="uf-modal-body">

        <div class="uf-modal-alert" id="modalAlert">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <span id="modalAlertMsg"></span>
        </div>

        {{-- BILL AMOUNT --}}
        <div class="uf-bill-amount-box">
          <div>
            <div class="uf-bill-amount-label">Amount Due</div>
            <div class="uf-bill-amount-value" id="mAmountBig">0</div>
          </div>
          <div style="text-align:right;">
            <div class="uf-bill-amount-cur">RWF</div>
            <div style="font-size:12px;color:rgba(255,255,255,0.4);margin-top:4px;" id="mDuration">—</div>
          </div>
        </div>

        {{-- DETAILS --}}
        <div class="uf-bill-grid">
          <div class="uf-bill-card">
            <div class="uf-bill-label">Plate Number</div>
            <div class="uf-bill-value" id="mPlate" style="letter-spacing:2px;">—</div>
          </div>
          <div class="uf-bill-card">
            <div class="uf-bill-label">Zone</div>
            <div class="uf-bill-value" id="mZone">—</div>
          </div>
          <div class="uf-bill-card">
            <div class="uf-bill-label">Entry Time</div>
            <div class="uf-bill-value" id="mEntry">—</div>
          </div>
          <div class="uf-bill-card">
            <div class="uf-bill-label">Exit Time</div>
            <div class="uf-bill-value" id="mExit">—</div>
          </div>
        </div>

        {{-- PAYMENT METHOD --}}
        <div class="uf-field" style="margin-bottom:12px;">
          <label>Payment Method</label>
          <div class="uf-pay-methods">
            <div class="uf-pay-method selected" onclick="selectPayMethod('cash')" id="pm-cash">
              <div class="uf-pay-method-icon">💵</div>
              <div class="uf-pay-method-label">Cash</div>
            </div>
            <div class="uf-pay-method" onclick="selectPayMethod('momo')" id="pm-momo">
              <div class="uf-pay-method-icon">📱</div>
              <div class="uf-pay-method-label">MoMo</div>
            </div>
          </div>
        </div>

        <input type="hidden" name="payment_method" id="paymentMethodInput" value="cash">
        <input type="hidden" name="amount" id="modalAmountInput" value="0">

        <div class="uf-field uf-phone-field" id="phoneField">
          <label>MoMo Phone Number</label>
          <input type="tel" name="phone_number" id="momoPhone" placeholder="078 XXX XXXX" maxlength="10" />
        </div>

        <div class="uf-momo-status" id="momoStatus">
          <div class="uf-momo-spinner"></div>
          <span id="momoStatusText">Waiting for MoMo confirmation…</span>
        </div>

      </div>
      <div class="uf-modal-foot">
        <button type="button" class="uf-btn uf-btn-secondary" onclick="closeModal()">Cancel</button>
        <button type="submit" class="uf-btn uf-btn-success" id="confirmBtn">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
          Confirm &amp; Pay
        </button>
      </div>
    </form>
  </div>
</div>

@endsection

@push('scripts')
<script>
  // Live clock
  function ufTick() {
    var el = document.getElementById('ufLiveClock');
    if (el) el.textContent = new Date().toLocaleString('en-GB',{day:'2-digit',month:'short',year:'numeric',hour:'2-digit',minute:'2-digit',second:'2-digit',hour12:false});
  }
  ufTick(); setInterval(ufTick, 1000);

  // Modal state
  let currentPayMethod = 'cash';
  let currentParkingId = null;
  let pollingInterval = null;

  function openExitModal(parkingId) {
    currentParkingId = parkingId;
    clearModalAlert();
    selectPayMethod('cash');
    document.getElementById('momoPhone').value = '';
    document.getElementById('momoStatus').classList.remove('show');
    resetConfirmBtn();

    fetch(`/user/parking/exit-info/${parkingId}`)
      .then(function(r) { return r.json(); })
      .then(function(data) {
        if (data.success) {
          document.getElementById('mPlate').textContent    = data.plate_number;
          document.getElementById('mZone').textContent     = data.zone_name || 'N/A';
          document.getElementById('mEntry').textContent    = data.entry_time;
          document.getElementById('mExit').textContent     = data.exit_time;
          document.getElementById('mDuration').textContent = data.duration + ' mins';
          document.getElementById('mAmountBig').textContent = Number(data.amount).toLocaleString();
          document.getElementById('modalAmountInput').value = data.amount;
          document.getElementById('exitForm').action       = '/user/parking/exit/' + parkingId;
          document.getElementById('exitOverlay').classList.add('open');
        } else {
          alert(data.message || 'Unable to load exit details.');
        }
      })
      .catch(function() { alert('Something went wrong while fetching exit details.'); });
  }

  function closeModal() {
    document.getElementById('exitOverlay').classList.remove('open');
    if (pollingInterval) { clearInterval(pollingInterval); pollingInterval = null; }
  }

  function handleOverlayClick(e) {
    if (e.target === document.getElementById('exitOverlay')) closeModal();
  }

  function selectPayMethod(method) {
    currentPayMethod = method;
    document.getElementById('paymentMethodInput').value = method;
    document.getElementById('pm-cash').classList.toggle('selected', method === 'cash');
    document.getElementById('pm-momo').classList.toggle('selected', method === 'momo');
    document.getElementById('phoneField').classList.toggle('show', method === 'momo');
  }

  function clearModalAlert() {
    document.getElementById('modalAlert').classList.remove('show');
  }

  function showModalAlert(msg) {
    document.getElementById('modalAlertMsg').textContent = msg;
    document.getElementById('modalAlert').classList.add('show');
  }

  function resetConfirmBtn() {
    var btn = document.getElementById('confirmBtn');
    btn.disabled = false;
    btn.innerHTML = '<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg> Confirm &amp; Pay';
  }

  function handlePayment(e) {
    e.preventDefault();
    clearModalAlert();

    if (currentPayMethod === 'momo') {
      var phone = document.getElementById('momoPhone').value.trim();
      if (!/^07[2389]\d{7}$/.test(phone)) {
        showModalAlert('Please enter a valid MTN/Airtel MoMo number (e.g. 078XXXXXXX).');
        return;
      }
    }

    var btn = document.getElementById('confirmBtn');
    btn.disabled = true;
    btn.innerHTML = '<div style="width:15px;height:15px;border-radius:50%;border:2px solid rgba(255,255,255,0.3);border-top-color:#fff;animation:ufSpin 0.8s linear infinite;display:inline-block;"></div> Processing…';

    var form = document.getElementById('exitForm');
    var formData = new FormData(form);

    fetch(form.action, {
      method: 'POST',
      body: formData,
      headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
    })
    .then(function(response) {
      return response.json().then(function(data) {
        if (!response.ok) throw new Error(data.message || 'Server error');
        return data;
      });
    })
    .then(function(data) {
      if (data.success) {
        if (currentPayMethod === 'momo' && data.trx_ref) {
          startMoMoPolling(data.trx_ref);
        } else {
          closeModal();
          location.reload();
        }
      } else {
        showModalAlert(data.message || 'Payment failed.');
        resetConfirmBtn();
      }
    })
    .catch(function(error) {
      showModalAlert(error.message);
      resetConfirmBtn();
    });
  }

  function startMoMoPolling(trxRef) {
    if (pollingInterval) clearInterval(pollingInterval);

    var statusEl = document.getElementById('momoStatus');
    var statusText = document.getElementById('momoStatusText');
    statusEl.classList.add('show');
    statusText.textContent = 'Waiting for MoMo confirmation…';
    statusEl.style.background = 'rgba(58,158,212,0.08)';
    statusEl.style.borderColor = 'rgba(58,158,212,0.2)';
    statusEl.style.color = '#0369A1';

    var pollAttempts = 0;
    var maxAttempts = 40;

    pollingInterval = setInterval(function() {
      pollAttempts++;
      if (pollAttempts > maxAttempts) {
        clearInterval(pollingInterval);
        statusText.textContent = 'Payment taking too long. Please try again.';
        statusEl.style.color = '#DC2626';
        resetConfirmBtn();
        return;
      }

      fetch('{{ url("/api/check-payment-status") }}?trx_ref=' + trxRef)
        .then(function(r) { return r.json(); })
        .then(function(data) {
          if (data.status === 'Completed') {
            clearInterval(pollingInterval);
            statusText.textContent = 'Payment completed!';
            statusEl.style.background = 'rgba(34,197,94,0.08)';
            statusEl.style.borderColor = 'rgba(34,197,94,0.2)';
            statusEl.style.color = '#16A34A';
            document.querySelector('.uf-momo-spinner').style.display = 'none';
            setTimeout(function() { closeModal(); location.reload(); }, 1200);
          } else if (data.status === 'Failed') {
            clearInterval(pollingInterval);
            statusText.textContent = 'Payment failed.';
            statusEl.style.color = '#DC2626';
            resetConfirmBtn();
          }
        })
        .catch(function() {
          statusText.textContent = 'Error checking payment. Retrying…';
        });
    }, 4000);
  }
</script>
@endpush
