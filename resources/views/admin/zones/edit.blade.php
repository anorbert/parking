@extends('layouts.admin')

@section('title', 'Edit Zone — ParkFlow')
@section('page-title', 'Edit Zone')

@push('styles')
<style>
.pf-edit-card {
  background: var(--pf-card); border: 1px solid var(--pf-border);
  border-radius: 14px; overflow: hidden; max-width: 600px;
}
.pf-edit-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 16px 20px; border-bottom: 1px solid rgba(0,0,0,0.06);
}
.pf-edit-title { font-size: 15px; font-weight: 800; color: var(--pf-text); }
.pf-edit-body { padding: 20px; }
.pf-edit-body .form-group { margin-bottom: 16px; }
.pf-edit-body label {
  font-size: 11px; font-weight: 700; letter-spacing: 1.2px;
  text-transform: uppercase; color: var(--pf-muted); margin-bottom: 6px; display: block;
}
.pf-edit-body .form-control {
  border-radius: 8px; font-family: var(--pf-font); font-weight: 600;
  border: 1.5px solid var(--pf-border); padding: 10px 14px;
}
.pf-edit-body .form-control:focus {
  border-color: var(--pf-blue);
  box-shadow: 0 0 0 3px rgba(58,158,212,0.12);
}
.pf-edit-actions { display: flex; gap: 10px; margin-top: 20px; }
</style>
@endpush

@section('content')

@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:10px;">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
@endif
@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px;">
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
@endif
@if($errors->any())
  <div class="alert alert-danger" style="border-radius:10px;">
    <ul class="mb-0">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="pf-edit-card">
  <div class="pf-edit-header">
    <div class="pf-edit-title"><i class="fa fa-pencil" style="color:var(--pf-blue);margin-right:6px;"></i> Edit Zone: {{ $zone->name }}</div>
  </div>
  <div class="pf-edit-body">
    <form method="POST" action="{{ route('admin.zones.update', $zone->id) }}">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label for="name">Zone Name</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $zone->name) }}" required>
      </div>

      <div class="form-group">
        <label for="total_slots">Capacity (Number of Slots)</label>
        <input type="number" name="total_slots" id="total_slots" class="form-control" value="{{ old('total_slots', $zone->total_slots ?? $zone->slots->count()) }}" required min="1">
      </div>

      <div class="pf-edit-actions">
        <button type="submit" class="btn" style="background:var(--pf-blue);color:#fff;font-weight:700;border-radius:8px;font-family:var(--pf-font);padding:10px 24px;">
          <i class="fa fa-save"></i> Update Zone
        </button>
        <a href="{{ route('admin.zones.index') }}" class="btn" style="background:rgba(0,0,0,0.06);color:var(--pf-muted);font-weight:700;border-radius:8px;font-family:var(--pf-font);padding:10px 24px;">
          Cancel
        </a>
      </div>
    </form>
  </div>
</div>

@endsection
