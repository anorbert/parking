@extends('layouts.admin')

@section('title', 'Reports — ParkFlow')
@section('page-title', 'Reports')

@push('styles')
<style>
.pf-panel{background:var(--pf-card);border:1px solid var(--pf-border);border-radius:14px;overflow:hidden;margin-bottom:16px}
.pf-panel-head{padding:14px 18px;border-bottom:1px solid rgba(0,0,0,.06);font-size:14px;font-weight:800;color:var(--pf-text);display:flex;align-items:center;justify-content:space-between}
.pf-panel-body{padding:18px}
.pf-filter-bar{display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap}
.pf-filter-bar label{font-size:11px;font-weight:700;color:var(--pf-muted);display:block;margin-bottom:4px}
.pf-filter-bar .form-control{border-radius:8px;font-family:var(--pf-font);font-weight:600;font-size:12px}
.pf-stat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;margin-bottom:16px}
.pf-stat-card{background:var(--pf-card);border:1px solid var(--pf-border);border-radius:12px;padding:18px 20px;transition:border-color .2s}
.pf-stat-card:hover{border-color:var(--pf-border2)}
.pf-stat-label{font-size:11px;font-weight:700;color:var(--pf-muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:6px}
.pf-stat-value{font-size:22px;font-weight:800;color:var(--pf-text)}
.pf-stat-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:10px;font-size:16px}
.pf-stat-icon-yellow{background:rgba(245,168,0,.12);color:var(--pf-yellow)}
.pf-stat-icon-green{background:rgba(74,222,128,.12);color:var(--pf-green)}
.pf-stat-icon-blue{background:rgba(58,158,212,.12);color:var(--pf-blue)}
.pf-stat-icon-red{background:rgba(248,113,113,.12);color:var(--pf-red)}
.pf-stat-icon-purple{background:rgba(168,85,247,.12);color:#A855F7}
</style>
@endpush

@section('content')

{{-- Filters --}}
<div class="pf-panel">
  <div class="pf-panel-head">
    <span><i class="fa fa-filter" style="color:var(--pf-blue);margin-right:6px;"></i> Filter Reports</span>
  </div>
  <div class="pf-panel-body">
    <form method="GET" action="{{ route('admin.reports.index') }}">
      <div class="pf-filter-bar">
        <div>
          <label>Start Date</label>
          <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div>
          <label>End Date</label>
          <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div>
          <label>Payment Method</label>
          <select name="payment_method" class="form-control">
            <option value="">All Methods</option>
            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="momo" {{ request('payment_method') == 'momo' ? 'selected' : '' }}>MoMo</option>
          </select>
        </div>
        <div>
          <label style="color:transparent;">Action</label>
          <button type="submit" class="btn" style="background:var(--pf-blue);color:#fff;font-weight:700;border-radius:8px;font-family:var(--pf-font);">
            <i class="fa fa-bar-chart"></i> Generate
          </button>
        </div>
        <div>
          <label style="color:transparent;">Reset</label>
          <a href="{{ route('admin.reports.index') }}" class="btn" style="background:rgba(148,163,184,.15);color:var(--pf-muted);font-weight:700;border-radius:8px;font-family:var(--pf-font);">
            <i class="fa fa-refresh"></i> Reset
          </a>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Stats Grid --}}
<div class="pf-stat-grid">
  {{-- Total Revenue --}}
  <div class="pf-stat-card">
    <div class="pf-stat-icon pf-stat-icon-green"><i class="fa fa-money"></i></div>
    <div class="pf-stat-label">Total Revenue</div>
    <div class="pf-stat-value">{{ number_format($totalRevenue) }} <small style="font-size:12px;color:var(--pf-muted);">RWF</small></div>
  </div>

  {{-- Cash Payments --}}
  <div class="pf-stat-card">
    <div class="pf-stat-icon pf-stat-icon-yellow"><i class="fa fa-money"></i></div>
    <div class="pf-stat-label">Cash Payments</div>
    <div class="pf-stat-value">{{ number_format($cashPayments) }} <small style="font-size:12px;color:var(--pf-muted);">RWF</small></div>
  </div>

  {{-- MoMo Payments --}}
  <div class="pf-stat-card">
    <div class="pf-stat-icon pf-stat-icon-blue"><i class="fa fa-mobile"></i></div>
    <div class="pf-stat-label">MoMo Payments</div>
    <div class="pf-stat-value">{{ number_format($momoPayments) }} <small style="font-size:12px;color:var(--pf-muted);">RWF</small></div>
  </div>

  {{-- Most Used Zone --}}
  <div class="pf-stat-card">
    <div class="pf-stat-icon pf-stat-icon-purple"><i class="fa fa-map-marker"></i></div>
    <div class="pf-stat-label">Most Used Zone</div>
    <div class="pf-stat-value" style="font-size:16px;">{{ $mostUsedZone ?? 'N/A' }}</div>
  </div>

  {{-- Top Client --}}
  <div class="pf-stat-card">
    <div class="pf-stat-icon pf-stat-icon-yellow"><i class="fa fa-star"></i></div>
    <div class="pf-stat-label">Top Client (Plate)</div>
    <div class="pf-stat-value" style="font-size:16px;">{{ $topClient ?? 'N/A' }}</div>
  </div>

  {{-- Avg Duration --}}
  <div class="pf-stat-card">
    <div class="pf-stat-icon pf-stat-icon-blue"><i class="fa fa-clock-o"></i></div>
    <div class="pf-stat-label">Avg Duration</div>
    <div class="pf-stat-value" style="font-size:16px;">{{ $avgDuration ? round($avgDuration) . ' min' : 'N/A' }}</div>
  </div>

  {{-- Exempted Vehicles --}}
  <div class="pf-stat-card">
    <div class="pf-stat-icon pf-stat-icon-red"><i class="fa fa-ban"></i></div>
    <div class="pf-stat-label">Exempted Vehicles</div>
    <div class="pf-stat-value">{{ $exemptedCount }}</div>
  </div>
</div>

@endsection
