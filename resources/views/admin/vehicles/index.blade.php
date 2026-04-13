@extends('layouts.admin')

@section('title', 'Exempted Vehicles — ParkFlow')
@section('page-title', 'Exempted Vehicles')

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
.pf-pill-red{background:rgba(248,113,113,.12);color:var(--pf-red)}
.pf-pill-blue{background:rgba(58,158,212,.12);color:var(--pf-blue)}
.pf-btn-sm{padding:4px 10px;border-radius:6px;font-size:11px;font-weight:700;border:none;cursor:pointer;font-family:var(--pf-font)}
</style>
@endpush

@section('content')

@if(session('success'))
  <div class="alert alert-success alert-dismissible" style="border-radius:10px;">{{ session('success') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>
@endif
@if(session('error'))
  <div class="alert alert-danger alert-dismissible" style="border-radius:10px;">{{ session('error') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>
@endif

{{-- Add Vehicle --}}
<div class="pf-panel">
  <div class="pf-panel-head">
    <span><i class="fa fa-car" style="color:var(--pf-green);margin-right:6px;"></i> Register Exempted Vehicle</span>
  </div>
  <div class="pf-panel-body">
    <form method="POST" action="{{ route('admin.vehicles.store') }}">
      @csrf
      <div class="row">
        <div class="col-md-4 mb-2">
          <label style="font-size:11px;font-weight:700;color:var(--pf-muted);">Plate Number</label>
          <input type="text" name="plate_number" class="form-control" placeholder="e.g. RAD 123A" required
                 style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
        </div>
        <div class="col-md-4 mb-2">
          <label style="font-size:11px;font-weight:700;color:var(--pf-muted);">Owner Name</label>
          <input type="text" name="owner_name" class="form-control" placeholder="Full name" required
                 style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
        </div>
        <div class="col-md-4 mb-2">
          <label style="font-size:11px;font-weight:700;color:var(--pf-muted);">Owner Contact</label>
          <input type="text" name="owner_contact" class="form-control" placeholder="Phone number" required
                 style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
        </div>
      </div>
      <div class="row" style="margin-top:8px;">
        <div class="col-md-3 mb-2">
          <label style="font-size:11px;font-weight:700;color:var(--pf-muted);">Billing Type</label>
          <select name="billing_type" class="form-control" required style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
            <option value="">-- Select --</option>
            <option value="free">Free</option>
            <option value="monthly">Monthly</option>
            <option value="annual">Annual</option>
          </select>
        </div>
        <div class="col-md-3 mb-2">
          <label style="font-size:11px;font-weight:700;color:var(--pf-muted);">Expiry Date</label>
          <input type="date" name="expired_at" class="form-control" style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
        </div>
        <div class="col-md-4 mb-2">
          <label style="font-size:11px;font-weight:700;color:var(--pf-muted);">Reason</label>
          <input type="text" name="reason" class="form-control" placeholder="Reason for exemption"
                 style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
        </div>
        <div class="col-md-2 mb-2">
          <label style="font-size:11px;font-weight:700;color:transparent;">Submit</label>
          <button type="submit" class="btn btn-block" style="background:var(--pf-green);color:#0D0F11;font-weight:700;border-radius:8px;font-family:var(--pf-font);">
            <i class="fa fa-plus"></i> Register
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Vehicles Table --}}
<div class="pf-panel">
  <div class="pf-panel-head">
    <span><i class="fa fa-list" style="color:var(--pf-blue);margin-right:6px;"></i> All Exempted Vehicles</span>
    <span class="pf-pill pf-pill-blue">{{ $vehicles->count() }} total</span>
  </div>
  <div class="pf-panel-body" style="padding:0;">
    <div class="table-responsive">
      <table class="pf-tbl" id="vehiclesTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Plate Number</th>
            <th>Owner</th>
            <th>Contact</th>
            <th>Billing</th>
            <th>Expires</th>
            <th>Reason</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($vehicles as $i => $vehicle)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td style="font-weight:800;">{{ $vehicle->plate_number }}</td>
            <td>{{ $vehicle->owner_name }}</td>
            <td>{{ $vehicle->owner_contact }}</td>
            <td><span class="pf-pill pf-pill-blue">{{ ucfirst($vehicle->billing_type) }}</span></td>
            <td>
              @if($vehicle->expired_at)
                <span class="{{ \Carbon\Carbon::parse($vehicle->expired_at)->isPast() ? 'pf-pill pf-pill-red' : 'pf-pill pf-pill-green' }}">
                  {{ \Carbon\Carbon::parse($vehicle->expired_at)->format('d M Y') }}
                </span>
              @else
                <span class="pf-pill pf-pill-green">No Expiry</span>
              @endif
            </td>
            <td>{{ $vehicle->reason ?? '—' }}</td>
            <td>
              @if($vehicle->expired_at && \Carbon\Carbon::parse($vehicle->expired_at)->isPast())
                <span class="pf-pill pf-pill-red">Expired</span>
              @else
                <span class="pf-pill pf-pill-green">Active</span>
              @endif
            </td>
            <td>
              <button class="pf-btn-sm btn-edit-vehicle" data-id="{{ $vehicle->id }}"
                      style="background:rgba(58,158,212,.12);color:var(--pf-blue);">
                <i class="fa fa-pencil"></i>
              </button>
              <form method="POST" action="{{ route('admin.vehicles.destroy', $vehicle->id) }}" style="display:inline;" onsubmit="return confirm('Delete this vehicle?')">
                @csrf @method('DELETE')
                <button type="submit" class="pf-btn-sm" style="background:rgba(248,113,113,.12);color:var(--pf-red);">
                  <i class="fa fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editVehicleModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content" style="border-radius:14px;border:1px solid var(--pf-border);">
      <div class="modal-header" style="border-bottom:1px solid var(--pf-border);">
        <h5 class="modal-title" style="font-weight:800;font-family:var(--pf-font);">Edit Vehicle</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="editVehicleBody">
        <p style="color:var(--pf-muted);text-align:center;">Loading...</p>
      </div>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
$(function(){
  $('#vehiclesTable').DataTable({
    pageLength: 15,
    order: [[0,'asc']],
    language: { search: "", searchPlaceholder: "Search vehicles..." }
  });

  $(document).on('click', '.btn-edit-vehicle', function(){
    var id = $(this).data('id');
    $('#editVehicleBody').html('<p style="color:var(--pf-muted);text-align:center;">Loading...</p>');
    $('#editVehicleModal').modal('show');
    $.get('/vehicles/' + id + '/edit', function(html){
      $('#editVehicleBody').html(html);
    });
  });
});
</script>
@endpush
