@extends('layouts.admin')

@section('title', 'Edit Staff — ParkFlow')
@section('page-title', 'Edit Staff')

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
    <span><i class="fa fa-user-circle" style="color:var(--pf-blue);margin-right:6px;"></i> Edit: {{ $user->name }}</span>
    <a href="{{ route('admin.staff.index') }}" class="btn btn-sm" style="background:rgba(148,163,184,.15);color:var(--pf-muted);font-weight:700;border-radius:8px;font-family:var(--pf-font);font-size:12px;">
      <i class="fa fa-arrow-left"></i> Back
    </a>
  </div>
  <div class="pf-panel-body">
    <form method="POST" action="{{ route('admin.staff.update', $user->id) }}">
      @csrf
      @method('PUT')
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="pf-form-label">Full Name</label>
            <input type="text" name="name" class="form-control pf-form-input" value="{{ old('name', $user->name) }}" required>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="pf-form-label">Email Address</label>
            <input type="email" name="email" class="form-control pf-form-input" value="{{ old('email', $user->email) }}" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="pf-form-label">Phone Number</label>
            <input type="text" name="phone_number" class="form-control pf-form-input" value="{{ old('phone_number', $user->phone_number) }}" required>
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="pf-form-label">Role</label>
            <select name="role_id" class="form-control pf-form-input" required>
              @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
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
                <option value="{{ $zone->id }}" {{ old('zone_id', $user->zone_id) == $zone->id ? 'selected' : '' }}>{{ $zone->name }}</option>
              @endforeach
            </select>
          </div>
        </div>
      </div>

      {{-- Optional PIN Reset --}}
      <div class="row" style="margin-top:8px;">
        <div class="col-md-4">
          <div class="form-group">
            <label class="pf-form-label">New PIN <small style="text-transform:none;color:var(--pf-muted);">(leave blank to keep current)</small></label>
            <input type="password" name="pin" class="form-control pf-form-input" maxlength="4" placeholder="****">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="pf-form-label">Confirm PIN</label>
            <input type="password" name="pin_confirmation" class="form-control pf-form-input" maxlength="4" placeholder="****">
          </div>
        </div>
      </div>

      <div style="margin-top:8px;padding-top:14px;border-top:1px solid var(--pf-border);display:flex;justify-content:flex-end;gap:8px;">
        <a href="{{ route('admin.staff.index') }}" class="btn" style="background:rgba(148,163,184,.15);color:var(--pf-muted);font-weight:700;border-radius:8px;font-family:var(--pf-font);">Cancel</a>
        <button type="submit" class="btn" style="background:var(--pf-blue);color:#fff;font-weight:700;border-radius:8px;font-family:var(--pf-font);">
          <i class="fa fa-save"></i> Update Staff
        </button>
      </div>
    </form>
  </div>
</div>

@endsection
