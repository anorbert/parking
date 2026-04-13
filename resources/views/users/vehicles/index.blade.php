@extends('layouts.user')

@section('title', 'Exempted Vehicles — ParkFlow')
@section('page-title', 'Exempted Vehicles')

@push('styles')
<style>
.uf-alert{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:10px;font-size:13px;font-weight:600;margin-bottom:16px;animation:ufFadeIn .3s ease}
@keyframes ufFadeIn{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:translateY(0)}}
.uf-alert-success{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#16A34A}
.uf-alert-danger{background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.25);color:#DC2626}
.uf-page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
.uf-page-title{font-size:22px;font-weight:800;color:var(--uf-dark);letter-spacing:.2px}
.uf-page-sub{font-size:12px;font-weight:500;color:var(--uf-muted);margin-top:2px}
.uf-tiles{display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:20px}
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
.uf-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:999px;font-size:10px;font-weight:800;letter-spacing:.5px}
.uf-pill-green{background:rgba(34,197,94,.1);color:#16A34A}
.uf-pill-yellow{background:rgba(245,168,0,.1);color:#D97706}
.uf-pill-blue{background:rgba(58,158,212,.1);color:#0369A1}
.uf-pill-purple{background:rgba(139,92,246,.1);color:#7C3AED}
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
    <div class="uf-page-title">Exempted Vehicles</div>
    <div class="uf-page-sub">Vehicles with special billing or free-parking privileges</div>
  </div>
</div>

{{-- STAT TILES --}}
@php
  $totalVehicles = $vehicles->count();
  $freeVehicles  = $vehicles->where('billing_type', 'free')->count();
  $subVehicles   = $totalVehicles - $freeVehicles;
@endphp

<div class="uf-tiles">
  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#3A9ED4,#60C4F5);"></div>
    <div class="uf-tile-icon" style="background:rgba(58,158,212,.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3A9ED4" stroke-width="2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
    </div>
    <div class="uf-tile-label">Total Exempted</div>
    <div class="uf-tile-value">{{ $totalVehicles }}</div>
    <div class="uf-tile-delta"><span class="uf-delta-muted">Registered vehicles</span></div>
  </div>
  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#22C55E,#86EFAC);"></div>
    <div class="uf-tile-icon" style="background:rgba(34,197,94,.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="2" stroke-linecap="round"><polyline points="9 11 12 14 22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
    </div>
    <div class="uf-tile-label">Free Parking</div>
    <div class="uf-tile-value">{{ $freeVehicles }}</div>
    <div class="uf-tile-delta"><span class="uf-delta-up">● No charge</span></div>
  </div>
  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#8B5CF6,#A78BFA);"></div>
    <div class="uf-tile-icon" style="background:rgba(139,92,246,.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2" stroke-linecap="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
    </div>
    <div class="uf-tile-label">Subscription</div>
    <div class="uf-tile-value">{{ $subVehicles }}</div>
    <div class="uf-tile-delta"><span class="uf-delta-muted">Special billing</span></div>
  </div>
</div>

{{-- VEHICLES TABLE --}}
<div class="uf-card">
  <div class="uf-card-head">
    <div>
      <div class="uf-card-title">Exempted Vehicle List</div>
      <div class="uf-card-sub">All registered exempted vehicles and their details</div>
    </div>
    <div class="uf-search-inline">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="vehicleSearch" placeholder="Search plate…" oninput="filterVehicles()">
    </div>
  </div>
  <div class="uf-card-body-np">
    @if($vehicles->count() > 0)
    <div style="overflow-x:auto;">
      <table class="uf-tbl" id="vehiclesTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Plate Number</th>
            <th>Type</th>
            <th>Owner</th>
            <th>Contact</th>
            <th>Billing</th>
            <th>Reason</th>
          </tr>
        </thead>
        <tbody>
          @foreach($vehicles as $key => $car)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td><span class="uf-plate-badge">{{ $car->plate_number }}</span></td>
            <td>{{ $car->vehicle_type }}</td>
            <td>{{ $car->owner_name }}</td>
            <td>{{ $car->owner_contact }}</td>
            <td>
              @if($car->billing_type === 'free')
                <span class="uf-pill uf-pill-green">● Free</span>
              @else
                <span class="uf-pill uf-pill-purple">● {{ ucfirst($car->billing_type) }}</span>
              @endif
            </td>
            <td>{{ $car->reason }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @else
    <div class="uf-empty">
      <div class="uf-empty-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      </div>
      <div class="uf-empty-title">No exempted vehicles</div>
      <div class="uf-empty-sub">No vehicles have been marked as exempt yet.</div>
    </div>
    @endif
  </div>
</div>

@endsection

@push('scripts')
<script>
function filterVehicles() {
  var term = document.getElementById('vehicleSearch').value.toUpperCase();
  var rows = document.querySelectorAll('#vehiclesTable tbody tr');
  rows.forEach(function(row) {
    var plate = row.cells[1] ? row.cells[1].textContent.toUpperCase() : '';
    row.style.display = plate.indexOf(term) > -1 ? '' : 'none';
  });
}
</script>
@endpush
