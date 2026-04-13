@extends('layouts.admin')

@section('title', 'Payments — ParkFlow')
@section('page-title', 'Payments')

@push('styles')
<style>
.pf-panel{background:var(--pf-card);border:1px solid var(--pf-border);border-radius:14px;overflow:hidden;margin-bottom:16px}
.pf-panel-head{padding:14px 18px;border-bottom:1px solid rgba(0,0,0,.06);font-size:14px;font-weight:800;color:var(--pf-text);display:flex;align-items:center;justify-content:space-between}
.pf-panel-body{padding:18px}
.pf-tbl{width:100%;font-size:12px;font-weight:600;border-collapse:collapse}
.pf-tbl thead th{color:var(--pf-muted);font-weight:700;padding:10px 14px;border-bottom:2px solid var(--pf-border);text-transform:uppercase;font-size:10px;letter-spacing:1px}
.pf-tbl tbody td{padding:10px 14px;border-bottom:1px solid var(--pf-border);color:var(--pf-text);vertical-align:middle}
.pf-tbl tbody tr:hover{background:rgba(245,168,0,.04)}
.pf-pill{padding:3px 10px;border-radius:6px;font-size:10px;font-weight:700;letter-spacing:.5px;display:inline-block}
.pf-pill-green{background:rgba(74,222,128,.12);color:var(--pf-green)}
.pf-pill-blue{background:rgba(58,158,212,.12);color:var(--pf-blue)}
.pf-pill-yellow{background:rgba(245,168,0,.12);color:var(--pf-yellow)}
.pf-filter-bar{display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap}
.pf-filter-bar label{font-size:11px;font-weight:700;color:var(--pf-muted);display:block;margin-bottom:4px}
.pf-filter-bar .form-control{border-radius:8px;font-family:var(--pf-font);font-weight:600;font-size:12px}
.pf-revenue-box{background:rgba(74,222,128,.1);border:1px solid rgba(74,222,128,.25);border-radius:10px;padding:14px 20px;display:inline-flex;align-items:center;gap:10px;font-weight:800;color:var(--pf-green);font-size:15px}
</style>
@endpush

@section('content')

@if(session('success'))
  <div class="alert alert-success alert-dismissible" style="border-radius:10px;">{{ session('success') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>
@endif

{{-- Filters --}}
<div class="pf-panel">
  <div class="pf-panel-head">
    <span><i class="fa fa-filter" style="color:var(--pf-blue);margin-right:6px;"></i> Filter Payments</span>
  </div>
  <div class="pf-panel-body">
    <form method="GET" action="{{ route('admin.payments.index') }}">
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
            <i class="fa fa-search"></i> Filter
          </button>
        </div>
        <div>
          <label style="color:transparent;">Reset</label>
          <a href="{{ route('admin.payments.index') }}" class="btn" style="background:rgba(148,163,184,.15);color:var(--pf-muted);font-weight:700;border-radius:8px;font-family:var(--pf-font);">
            <i class="fa fa-refresh"></i> Reset
          </a>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Revenue Summary --}}
<div style="margin-bottom:16px;">
  <div class="pf-revenue-box">
    <i class="fa fa-money" style="font-size:20px;"></i>
    Total Revenue: {{ number_format($payments->sum('bill')) }} RWF
  </div>
</div>

{{-- Payments Table --}}
<div class="pf-panel">
  <div class="pf-panel-head">
    <span><i class="fa fa-credit-card" style="color:var(--pf-yellow);margin-right:6px;"></i> Payment Records</span>
    <span class="pf-pill pf-pill-blue">{{ $payments->count() }} records</span>
  </div>
  <div class="pf-panel-body" style="padding:0;">
    <div class="table-responsive">
      <table class="pf-tbl" id="paymentsTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Plate Number</th>
            <th>Time Spent</th>
            <th>Amount (RWF)</th>
            <th>Method</th>
            <th>Collected By</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          @foreach($payments as $i => $payment)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td style="font-weight:800;">{{ $payment->plate_number }}</td>
            <td>
              @if($payment->entry_time && $payment->exit_time)
                {{ \Carbon\Carbon::parse($payment->entry_time)->diffForHumans(\Carbon\Carbon::parse($payment->exit_time), true) }}
              @else
                —
              @endif
            </td>
            <td style="font-weight:800;">{{ number_format($payment->bill) }}</td>
            <td>
              <span class="pf-pill {{ $payment->payment_method == 'cash' ? 'pf-pill-green' : 'pf-pill-blue' }}">
                {{ ucfirst($payment->payment_method) }}
              </span>
            </td>
            <td>{{ $payment->user->name ?? '—' }}</td>
            <td>{{ \Carbon\Carbon::parse($payment->exit_time)->format('d M Y H:i') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
$(function(){
  $('#paymentsTable').DataTable({
    pageLength: 20,
    order: [[6,'desc']],
    language: { search: "", searchPlaceholder: "Search payments..." }
  });
});
</script>
@endpush
