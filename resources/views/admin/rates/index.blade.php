@extends('layouts.admin')

@section('title', 'Parking Rates — ParkFlow')
@section('page-title', 'Parking Rates')

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
.pf-pill-blue{background:rgba(58,158,212,.12);color:var(--pf-blue)}
.pf-pill-green{background:rgba(74,222,128,.12);color:var(--pf-green)}
</style>
@endpush

@section('content')

@if(session('success'))
  <div class="alert alert-success alert-dismissible" style="border-radius:10px;">{{ session('success') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>
@endif
@if(session('error'))
  <div class="alert alert-danger alert-dismissible" style="border-radius:10px;">{{ session('error') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>
@endif

{{-- Add Rate Form --}}
<div class="pf-panel">
  <div class="pf-panel-head">
    <span><i class="fa fa-plus-circle" style="color:var(--pf-green);margin-right:6px;"></i> Add New Rate</span>
  </div>
  <div class="pf-panel-body">
    <form method="POST" action="{{ route('admin.rates.store') }}">
      @csrf
      <div class="row">
        <div class="col-md-4 mb-2">
          <label style="font-size:11px;font-weight:700;color:var(--pf-muted);">Zone</label>
          <select name="zone_id" class="form-control" required style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
            <option value="">-- Select Zone --</option>
            @foreach($zones as $zone)
              <option value="{{ $zone->id }}">{{ $zone->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3 mb-2">
          <label style="font-size:11px;font-weight:700;color:var(--pf-muted);">Duration (minutes)</label>
          <input type="number" name="duration_minutes" class="form-control" placeholder="e.g. 60" required min="1"
                 style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
        </div>
        <div class="col-md-3 mb-2">
          <label style="font-size:11px;font-weight:700;color:var(--pf-muted);">Rate (RWF)</label>
          <input type="number" name="rate" class="form-control" placeholder="e.g. 500" required min="0"
                 style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
        </div>
        <div class="col-md-2 mb-2">
          <label style="font-size:11px;font-weight:700;color:transparent;">Submit</label>
          <button type="submit" class="btn btn-block" style="background:var(--pf-green);color:#0D0F11;font-weight:700;border-radius:8px;font-family:var(--pf-font);">
            <i class="fa fa-plus"></i> Add
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Rates Table --}}
<div class="pf-panel">
  <div class="pf-panel-head">
    <span><i class="fa fa-tags" style="color:var(--pf-yellow);margin-right:6px;"></i> All Rates</span>
    <span class="pf-pill pf-pill-blue">{{ $rates->count() }} rates</span>
  </div>
  <div class="pf-panel-body" style="padding:0;">
    <div class="table-responsive">
      <table class="pf-tbl" id="ratesTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Zone</th>
            <th>Duration</th>
            <th>Rate (RWF)</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($rates as $i => $rate)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td><span class="pf-pill pf-pill-blue">{{ $rate->zone->name ?? 'N/A' }}</span></td>
            <td style="font-weight:800;">{{ $rate->duration_minutes }} min</td>
            <td style="font-weight:800;">{{ number_format($rate->rate) }}</td>
            <td>
              <a href="{{ route('admin.rates.edit', $rate->id) }}" class="btn btn-sm" style="background:rgba(58,158,212,.12);color:var(--pf-blue);font-weight:700;border-radius:6px;font-size:11px;font-family:var(--pf-font);">
                <i class="fa fa-pencil"></i>
              </a>
              <form method="POST" action="{{ route('admin.rates.destroy', $rate->id) }}" style="display:inline;" onsubmit="return confirm('Delete this rate?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm" style="background:rgba(248,113,113,.12);color:var(--pf-red);font-weight:700;border-radius:6px;font-size:11px;font-family:var(--pf-font);">
                  <i class="fa fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" style="text-align:center;padding:30px;color:var(--pf-muted);font-weight:700;">No rates configured. Add your first rate above.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
$(function(){
  $('#ratesTable').DataTable({
    pageLength: 15,
    order: [[1,'asc']],
    language: { search: "", searchPlaceholder: "Search rates..." }
  });
});
</script>
@endpush
