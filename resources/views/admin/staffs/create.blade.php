@extends('layouts.admin')

@section('title', 'Add Staff — ParkFlow')
@section('page-title', 'Add Staff')

@push('styles')
<style>
.pf-panel{background:var(--pf-card);border:1px solid var(--pf-border);border-radius:14px;overflow:hidden;margin-bottom:16px}
.pf-panel-head{padding:14px 18px;border-bottom:1px solid rgba(0,0,0,.06);font-size:14px;font-weight:800;color:var(--pf-text);display:flex;align-items:center;justify-content:space-between}
.pf-panel-body{padding:18px}
.pf-form-label{font-size:11px;font-weight:700;color:var(--pf-muted);text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px;display:block}
.pf-form-input{border-radius:8px;font-family:var(--pf-font);font-weight:600;border:1px solid var(--pf-border);padding:8px 14px;width:100%;font-size:13px}
.pf-form-input:focus{border-color:var(--pf-blue);outline:none;box-shadow:0 0 0 3px rgba(58,158,212,.15)}
</style>
@endpush

@section('content')

@if($errors->any())
  <div class="alert alert-danger" style="border-radius:10px;">
    <ul style="margin:0;padding-left:18px;">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="pf-panel">
  <div class="pf-panel-head">
    <span><i class="fa fa-user-plus" style="color:var(--pf-green);margin-right:6px;"></i> Register New Staff</span>
    <a href="{{ route('admin.staff.index') }}" class="btn btn-sm" style="background:rgba(148,163,184,.15);color:var(--pf-muted);font-weight:700;border-radius:8px;font-family:var(--pf-font);font-size:12px;">
      <i class="fa fa-arrow-left"></i> Back
    </a>
  </div>
  <div class="pf-panel-body">
    <form method="POST" action="{{ route('admin.staff.store') }}">
      @csrf
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="pf-form-label">Full Name</label>
            <input type="text" name="name" class="form-control pf-form-input" value="{{ old('name') }}" placeholder="Enter full name" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="pf-form-label">Email Address</label>
            <input type="email" name="email" class="form-control pf-form-input" value="{{ old('email') }}" placeholder="email@example.com" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="pf-form-label">Phone Number</label>
            <input type="text" name="phone_number" class="form-control pf-form-input" value="{{ old('phone_number') }}" placeholder="078xxxxxxx" required>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="pf-form-label">Role</label>
            <select name="role_id" class="form-control pf-form-input" required>
              <option value="">-- Select Role --</option>
              @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="pf-form-label">Assigned Zone</label>
            <select name="zone_id" class="form-control pf-form-input">
              <option value="">-- No Zone --</option>
              @foreach($zones as $zone)
                <option value="{{ $zone->id }}" {{ old('zone_id') == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      <div style="margin-top:8px;padding-top:14px;border-top:1px solid var(--pf-border);display:flex;justify-content:flex-end;gap:8px;">
        <a href="{{ route('admin.staff.index') }}" class="btn" style="background:rgba(148,163,184,.15);color:var(--pf-muted);font-weight:700;border-radius:8px;font-family:var(--pf-font);">Cancel</a>
        <button type="submit" class="btn" style="background:var(--pf-green);color:#0D0F11;font-weight:700;border-radius:8px;font-family:var(--pf-font);">
          <i class="fa fa-check"></i> Create Staff
        </button>
      </div>
    </form>
  </div>
</div>

@endsection
