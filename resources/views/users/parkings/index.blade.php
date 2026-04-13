@extends('layouts.user')

@section('title', 'Entry & Exit Logs — ParkFlow')
@section('page-title', 'Entry & Exit Logs')

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
.uf-card-body-np{padding:0}
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
.uf-pill-red{background:rgba(239,68,68,.1);color:#DC2626}
.uf-pill-gray{background:rgba(107,114,128,.1);color:#6B7280}
.uf-empty{text-align:center;padding:48px 20px;display:flex;flex-direction:column;align-items:center;gap:10px}
.uf-empty-icon{width:56px;height:56px;border-radius:14px;background:#F0F3F7;display:flex;align-items:center;justify-content:center;margin-bottom:4px}
.uf-empty-title{font-size:14px;font-weight:800;color:var(--uf-dark)}
.uf-empty-sub{font-size:12px;font-weight:500;color:var(--uf-muted);max-width:260px}
.uf-search-inline{display:flex;align-items:center;gap:8px;background:#F5F7FA;border:1.5px solid var(--uf-border);border-radius:8px;padding:8px 12px;width:220px;transition:border-color .2s}
.uf-search-inline:focus-within{border-color:var(--uf-blue)}
.uf-search-inline input{background:none;border:none;outline:none;font-family:var(--uf-font);font-size:13px;font-weight:600;color:var(--uf-dark);width:100%}
.uf-search-inline input::placeholder{color:#C4C9D0;font-weight:400}
@media(max-width:992px){.uf-tiles{grid-template-columns:repeat(2,1fr)}}
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
@if(session('error'))
  <div class="uf-alert uf-alert-danger">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    {{ session('error') }}
  </div>
@endif

{{-- PAGE HEADER --}}
<div class="uf-page-header">
  <div>
    <div class="uf-page-title">Entry &amp; Exit Logs</div>
    <div class="uf-page-sub">Complete history of all vehicle entries and exits</div>
  </div>
</div>

{{-- STAT TILES --}}
@php
  $totalLogs  = $parkings->count();
  $activeLogs = $parkings->where('status', 'active')->count();
  $exitedLogs = $parkings->where('status', 'inactive')->count();
  $cashLogs   = $parkings->where('payment_method', 'cash')->count();
  $momoLogs   = $parkings->where('payment_method', 'momo')->count();
@endphp

<div class="uf-tiles">
  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#3A9ED4,#60C4F5);"></div>
    <div class="uf-tile-icon" style="background:rgba(58,158,212,.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3A9ED4" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    </div>
    <div class="uf-tile-label">Total Records</div>
    <div class="uf-tile-value">{{ $totalLogs }}</div>
    <div class="uf-tile-delta"><span class="uf-delta-muted">All time logs</span></div>
  </div>
  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#F5A800,#FFD166);"></div>
    <div class="uf-tile-icon" style="background:rgba(245,168,0,.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F5A800" stroke-width="2" stroke-linecap="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    </div>
    <div class="uf-tile-label">Currently Parked</div>
    <div class="uf-tile-value">{{ $activeLogs }}</div>
    <div class="uf-tile-delta"><span class="uf-delta-up">● Active</span></div>
  </div>
  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#22C55E,#86EFAC);"></div>
    <div class="uf-tile-icon" style="background:rgba(34,197,94,.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="2" stroke-linecap="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
    </div>
    <div class="uf-tile-label">Exited</div>
    <div class="uf-tile-value">{{ $exitedLogs }}</div>
    <div class="uf-tile-delta"><span class="uf-delta-muted">Completed sessions</span></div>
  </div>
  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#8B5CF6,#A78BFA);"></div>
    <div class="uf-tile-icon" style="background:rgba(139,92,246,.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2" stroke-linecap="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    </div>
    <div class="uf-tile-label">Cash / MoMo</div>
    <div class="uf-tile-value" style="font-size:20px;">{{ $cashLogs }} <span style="font-size:12px;color:var(--uf-muted);">/</span> {{ $momoLogs }}</div>
    <div class="uf-tile-delta"><span class="uf-delta-muted">Payment split</span></div>
  </div>
</div>

{{-- LOGS TABLE --}}
<div class="uf-card">
  <div class="uf-card-head">
    <div>
      <div class="uf-card-title">All Parking Logs</div>
      <div class="uf-card-sub">Entry and exit records for your zone</div>
    </div>
    <div class="uf-search-inline">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="logSearch" placeholder="Search plate…" oninput="filterTable()">
    </div>
  </div>
  <div class="uf-card-body-np">
    @if($parkings->count() > 0)
    <div style="overflow-x:auto;">
      <table class="uf-tbl" id="logsTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Plate Number</th>
            <th>Entry Time</th>
            <th>Exit Time</th>
            <th>Payment Mode</th>
            <th>Bill (RWF)</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
          @foreach($parkings as $key => $log)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td><span class="uf-plate-badge">{{ $log->plate_number }}</span></td>
            <td><span class="uf-time-text">{{ $log->entry_time ? $log->entry_time->format('d M Y, H:i') : 'N/A' }}</span></td>
            <td><span class="uf-time-text">{{ $log->exit_time ? $log->exit_time->format('d M Y, H:i') : '—' }}</span></td>
            <td>
              @if($log->payment_method === 'momo')
                <span class="uf-pill uf-pill-blue">📱 MoMo</span>
              @elseif($log->payment_method === 'cash')
                <span class="uf-pill uf-pill-green">💵 Cash</span>
              @else
                <span class="uf-pill uf-pill-gray">—</span>
              @endif
            </td>
            <td>{{ $log->bill ? number_format($log->bill) : '—' }}</td>
            <td>
              @if($log->status === 'active')
                <span class="uf-pill uf-pill-yellow">● Parked</span>
              @else
                <span class="uf-pill uf-pill-green">● Exited</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @else
    <div class="uf-empty">
      <div class="uf-empty-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      </div>
      <div class="uf-empty-title">No parking logs yet</div>
      <div class="uf-empty-sub">Vehicle entry and exit records will appear here once activity begins.</div>
    </div>
    @endif
  </div>
</div>

@endsection

@push('scripts')
<script>
function filterTable() {
  var term = document.getElementById('logSearch').value.toUpperCase();
  var rows = document.querySelectorAll('#logsTable tbody tr');
  rows.forEach(function(row) {
    var plate = row.cells[1] ? row.cells[1].textContent.toUpperCase() : '';
    row.style.display = plate.indexOf(term) > -1 ? '' : 'none';
  });
}
</script>
@endpush
