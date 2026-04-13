@extends('layouts.user')

@section('title', 'Payments — ParkFlow')
@section('page-title', 'Payments')

@push('styles')
<style>
.uf-alert{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:10px;font-size:13px;font-weight:600;margin-bottom:16px;animation:ufFadeIn .3s ease}
@keyframes ufFadeIn{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:translateY(0)}}
.uf-alert-success{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#16A34A}
.uf-alert-danger{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#DC2626}
.uf-page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
.uf-page-title{font-size:22px;font-weight:800;color:var(--uf-dark);letter-spacing:.2px}
.uf-page-sub{font-size:12px;font-weight:500;color:var(--uf-muted);margin-top:2px}
.uf-tiles{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px}
.uf-tile{background:var(--uf-card);border:1px solid var(--uf-border);border-radius:14px;padding:18px;position:relative;overflow:hidden}
.uf-tile-accent{position:absolute;top:0;left:0;right:0;height:3px}
.uf-tile-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:12px}
.uf-tile-label{font-size:10px;font-weight:700;letter-spacing:1.8px;text-transform:uppercase;color:var(--uf-muted);margin-bottom:5px}
.uf-tile-value{font-size:26px;font-weight:800;color:var(--uf-dark);line-height:1}
.uf-tile-delta{display:flex;align-items:center;gap:4px;margin-top:7px;font-size:11px;font-weight:600}
.uf-delta-up{color:var(--uf-green)}.uf-delta-muted{color:var(--uf-muted)}
.uf-card{background:var(--uf-card);border:1px solid var(--uf-border);border-radius:14px;overflow:hidden;margin-bottom:18px}
.uf-card-head{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--uf-border)}
.uf-card-title{font-size:14px;font-weight:800;color:var(--uf-dark)}
.uf-card-sub{font-size:11px;font-weight:500;color:var(--uf-muted);margin-top:2px}
.uf-card-body{padding:20px}
.uf-card-body-np{padding:0}
.uf-filter-bar{display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap;padding:16px 20px;background:#FAFBFC;border-bottom:1px solid var(--uf-border)}
.uf-filter-field{display:flex;flex-direction:column;gap:5px}
.uf-filter-field label{font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--uf-muted)}
.uf-filter-field input,.uf-filter-field select{background:#FFF;border:1.5px solid var(--uf-border);border-radius:8px;padding:9px 12px;font-family:var(--uf-font);font-size:13px;font-weight:600;color:var(--uf-dark);outline:none;transition:border-color .2s,box-shadow .2s;min-width:150px}
.uf-filter-field input:focus,.uf-filter-field select:focus{border-color:var(--uf-blue);box-shadow:0 0 0 3px rgba(58,158,212,.12)}
.uf-btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:10px 18px;border-radius:9px;font-family:var(--uf-font);font-size:12px;font-weight:800;letter-spacing:1px;text-transform:uppercase;border:none;cursor:pointer;transition:all .18s;white-space:nowrap}
.uf-btn-primary{background:linear-gradient(90deg,#F5A800,#FFD166);background-size:200% 100%;color:#0D0F11;box-shadow:0 3px 14px rgba(245,168,0,.28)}
.uf-btn-primary:hover{background-position:right center;box-shadow:0 5px 20px rgba(245,168,0,.4)}
.uf-btn-secondary{background:#F0F3F7;color:#6B7280;border:1px solid var(--uf-border)}
.uf-btn-secondary:hover{background:#E4E8EE}
.uf-tbl{width:100%;border-collapse:collapse;font-size:13px}
.uf-tbl thead tr{border-bottom:2px solid #EEF0F3}
.uf-tbl thead th{padding:11px 16px;text-align:left;font-size:10px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;color:var(--uf-muted);white-space:nowrap;background:#FAFBFC}
.uf-tbl tbody tr{border-bottom:1px solid #F3F4F6;transition:background .1s}
.uf-tbl tbody tr:hover{background:#F8F9FB}
.uf-tbl tbody tr:last-child{border-bottom:none}
.uf-tbl td{padding:13px 16px;font-weight:600;color:var(--uf-soft);vertical-align:middle}
.uf-tbl td:first-child{font-weight:800;color:var(--uf-dark);letter-spacing:1px}
.uf-plate-badge{display:inline-flex;align-items:center;gap:6px;background:#F0F3F7;border:1px solid #DDE2E8;border-radius:6px;padding:4px 10px;font-size:13px;font-weight:800;color:var(--uf-dark);letter-spacing:2px}
.uf-time-text{font-size:12px;font-weight:600;color:var(--uf-soft)}
.uf-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:999px;font-size:10px;font-weight:800;letter-spacing:.5px}
.uf-pill-green{background:rgba(34,197,94,.1);color:#16A34A}
.uf-pill-yellow{background:rgba(245,168,0,.1);color:#D97706}
.uf-pill-blue{background:rgba(58,158,212,.1);color:#0369A1}
.uf-pill-gray{background:rgba(107,114,128,.1);color:#6B7280}
.uf-empty{text-align:center;padding:48px 20px;display:flex;flex-direction:column;align-items:center;gap:10px}
.uf-empty-icon{width:56px;height:56px;border-radius:14px;background:#F0F3F7;display:flex;align-items:center;justify-content:center;margin-bottom:4px}
.uf-empty-title{font-size:14px;font-weight:800;color:var(--uf-dark)}
.uf-empty-sub{font-size:12px;font-weight:500;color:var(--uf-muted);max-width:260px}
.uf-revenue-box{background:linear-gradient(135deg,#1B2235,#232C42);border-radius:12px;padding:20px 24px;display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
.uf-revenue-label{font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:rgba(255,255,255,.5)}
.uf-revenue-value{font-size:32px;font-weight:800;color:#F5A800;letter-spacing:-.5px;margin-top:4px}
.uf-revenue-cur{font-size:14px;font-weight:700;color:rgba(255,255,255,.5)}
.uf-search-inline{display:flex;align-items:center;gap:8px;background:#F5F7FA;border:1.5px solid var(--uf-border);border-radius:8px;padding:8px 12px;width:220px;transition:border-color .2s}
.uf-search-inline:focus-within{border-color:var(--uf-blue)}
.uf-search-inline input{background:none;border:none;outline:none;font-family:var(--uf-font);font-size:13px;font-weight:600;color:var(--uf-dark);width:100%}
.uf-search-inline input::placeholder{color:#C4C9D0;font-weight:400}
@media(max-width:992px){.uf-tiles{grid-template-columns:repeat(2,1fr)}.uf-filter-bar{flex-direction:column;align-items:stretch}}
@media(max-width:576px){.uf-tiles{grid-template-columns:1fr}}
</style>
@endpush

@section('content')

@if(session('success'))
  <div class="uf-alert uf-alert-success">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
    {{ session('success') }}
  </div>
@endif

{{-- PAGE HEADER --}}
<div class="uf-page-header">
  <div>
    <div class="uf-page-title">Payments</div>
    <div class="uf-page-sub">Track all parking payment transactions</div>
  </div>
</div>

{{-- REVENUE BOX --}}
@php
  $totalRevenue = $payments->sum('bill');
  $cashRevenue  = $payments->where('payment_method', 'cash')->sum('bill');
  $momoRevenue  = $payments->where('payment_method', 'momo')->sum('bill');
@endphp

<div class="uf-revenue-box">
  <div>
    <div class="uf-revenue-label">Total Revenue</div>
    <div class="uf-revenue-value">{{ number_format($totalRevenue) }}</div>
  </div>
  <div style="text-align:right;">
    <div class="uf-revenue-cur">RWF</div>
    <div style="font-size:11px;color:rgba(255,255,255,.35);margin-top:4px;">From {{ $payments->count() }} transactions</div>
  </div>
</div>

{{-- STAT TILES --}}
<div class="uf-tiles">
  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#3A9ED4,#60C4F5);"></div>
    <div class="uf-tile-icon" style="background:rgba(58,158,212,.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3A9ED4" stroke-width="2" stroke-linecap="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    </div>
    <div class="uf-tile-label">Transactions</div>
    <div class="uf-tile-value">{{ $payments->count() }}</div>
    <div class="uf-tile-delta"><span class="uf-delta-muted">Total count</span></div>
  </div>
  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#22C55E,#86EFAC);"></div>
    <div class="uf-tile-icon" style="background:rgba(34,197,94,.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="2" stroke-linecap="round"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M2 10h20"/></svg>
    </div>
    <div class="uf-tile-label">Cash Revenue</div>
    <div class="uf-tile-value" style="font-size:18px;">{{ number_format($cashRevenue) }} <span style="font-size:11px;color:var(--uf-muted);">RWF</span></div>
    <div class="uf-tile-delta"><span class="uf-delta-up">💵 Cash payments</span></div>
  </div>
  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#F5A800,#FFD166);"></div>
    <div class="uf-tile-icon" style="background:rgba(245,168,0,.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F5A800" stroke-width="2" stroke-linecap="round"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
    </div>
    <div class="uf-tile-label">MoMo Revenue</div>
    <div class="uf-tile-value" style="font-size:18px;">{{ number_format($momoRevenue) }} <span style="font-size:11px;color:var(--uf-muted);">RWF</span></div>
    <div class="uf-tile-delta"><span class="uf-delta-muted">📱 Mobile money</span></div>
  </div>
  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#8B5CF6,#A78BFA);"></div>
    <div class="uf-tile-icon" style="background:rgba(139,92,246,.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2" stroke-linecap="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
    </div>
    <div class="uf-tile-label">Avg. Amount</div>
    <div class="uf-tile-value" style="font-size:18px;">{{ $payments->count() > 0 ? number_format($totalRevenue / $payments->count()) : 0 }} <span style="font-size:11px;color:var(--uf-muted);">RWF</span></div>
    <div class="uf-tile-delta"><span class="uf-delta-muted">Per transaction</span></div>
  </div>
</div>

{{-- PAYMENTS TABLE --}}
<div class="uf-card">
  <div class="uf-card-head">
    <div>
      <div class="uf-card-title">Payment Records</div>
      <div class="uf-card-sub">All parking payment transactions</div>
    </div>
    <div class="uf-search-inline">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="paySearch" placeholder="Search plate…" oninput="filterPayments()">
    </div>
  </div>

  {{-- FILTER BAR --}}
  <form method="GET" action="{{ route('user.payments.index') }}" class="uf-filter-bar">
    <div class="uf-filter-field">
      <label>Start Date</label>
      <input type="date" name="start_date" value="{{ request('start_date') }}">
    </div>
    <div class="uf-filter-field">
      <label>End Date</label>
      <input type="date" name="end_date" value="{{ request('end_date') }}">
    </div>
    <div class="uf-filter-field">
      <label>Payment Method</label>
      <select name="payment_method">
        <option value="">All Methods</option>
        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
        <option value="momo" {{ request('payment_method') == 'momo' ? 'selected' : '' }}>MoMo</option>
      </select>
    </div>
    <button type="submit" class="uf-btn uf-btn-primary">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
      Filter
    </button>
    @if(request('start_date') || request('end_date') || request('payment_method'))
    <a href="{{ route('user.payments.index') }}" class="uf-btn uf-btn-secondary">Clear</a>
    @endif
  </form>

  <div class="uf-card-body-np">
    @if($payments->count() > 0)
    <div style="overflow-x:auto;">
      <table class="uf-tbl" id="paymentsTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Plate No</th>
            <th>Duration</th>
            <th>Amount (RWF)</th>
            <th>Method</th>
            <th>Operator</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          @foreach($payments as $key => $payment)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td><span class="uf-plate-badge">{{ $payment->plate_number ?? 'N/A' }}</span></td>
            <td><span class="uf-time-text">{{ $payment->duration ?? 'N/A' }}</span></td>
            <td style="font-weight:800;color:var(--uf-dark);">{{ number_format($payment->bill) }}</td>
            <td>
              @if($payment->payment_method === 'momo')
                <span class="uf-pill uf-pill-blue">📱 MoMo</span>
              @elseif($payment->payment_method === 'cash')
                <span class="uf-pill uf-pill-green">💵 Cash</span>
              @else
                <span class="uf-pill uf-pill-gray">—</span>
              @endif
            </td>
            <td>{{ $payment->user->name ?? 'N/A' }}</td>
            <td><span class="uf-time-text">{{ $payment->created_at->format('d M Y, H:i') }}</span></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @else
    <div class="uf-empty">
      <div class="uf-empty-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      </div>
      <div class="uf-empty-title">No payments found</div>
      <div class="uf-empty-sub">Try adjusting your filters or check back when transactions have been processed.</div>
    </div>
    @endif
  </div>
</div>

@endsection

@push('scripts')
<script>
function filterPayments() {
  var term = document.getElementById('paySearch').value.toUpperCase();
  var rows = document.querySelectorAll('#paymentsTable tbody tr');
  rows.forEach(function(row) {
    var plate = row.cells[1] ? row.cells[1].textContent.toUpperCase() : '';
    row.style.display = plate.indexOf(term) > -1 ? '' : 'none';
  });
}
</script>
@endpush
