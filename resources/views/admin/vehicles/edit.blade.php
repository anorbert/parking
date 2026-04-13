<form method="POST" action="{{ route('admin.vehicles.update', $vehicle->id) }}">
  @csrf
  @method('PUT')
  <div class="form-group">
    <label style="font-size:11px;font-weight:700;color:var(--pf-muted);">Plate Number</label>
    <input type="text" name="plate_number" class="form-control" value="{{ $vehicle->plate_number }}" required
           style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
  </div>
  <div class="form-group">
    <label style="font-size:11px;font-weight:700;color:var(--pf-muted);">Owner Name</label>
    <input type="text" name="owner_name" class="form-control" value="{{ $vehicle->owner_name }}" required
           style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
  </div>
  <div class="form-group">
    <label style="font-size:11px;font-weight:700;color:var(--pf-muted);">Owner Contact</label>
    <input type="text" name="owner_contact" class="form-control" value="{{ $vehicle->owner_contact }}" required
           style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
  </div>
  <div class="row">
    <div class="col-md-6">
      <div class="form-group">
        <label style="font-size:11px;font-weight:700;color:var(--pf-muted);">Billing Type</label>
        <select name="billing_type" class="form-control" required style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
          <option value="free" {{ $vehicle->billing_type == 'free' ? 'selected' : '' }}>Free</option>
          <option value="monthly" {{ $vehicle->billing_type == 'monthly' ? 'selected' : '' }}>Monthly</option>
          <option value="annual" {{ $vehicle->billing_type == 'annual' ? 'selected' : '' }}>Annual</option>
        </select>
      </div>
    </div>
    <div class="col-md-6">
      <div class="form-group">
        <label style="font-size:11px;font-weight:700;color:var(--pf-muted);">Vehicle Type</label>
        <input type="text" name="vehicle_type" class="form-control" value="{{ $vehicle->vehicle_type ?? '' }}"
               placeholder="e.g. Sedan" style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
      </div>
    </div>
  </div>
  <div class="form-group">
    <label style="font-size:11px;font-weight:700;color:var(--pf-muted);">Reason</label>
    <input type="text" name="reason" class="form-control" value="{{ $vehicle->reason ?? '' }}"
           style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
  </div>
  <div style="text-align:right;margin-top:12px;">
    <button type="button" class="btn" data-dismiss="modal" style="font-weight:700;border-radius:8px;font-family:var(--pf-font);">Cancel</button>
    <button type="submit" class="btn" style="background:var(--pf-green);color:#0D0F11;font-weight:700;border-radius:8px;font-family:var(--pf-font);">
      <i class="fa fa-save"></i> Update
    </button>
  </div>
</form>
