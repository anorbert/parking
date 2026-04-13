@extends('layouts.admin')

@section('title', 'Staff Profile — ParkFlow')
@section('page-title', 'Staff Profile')

@push('styles')
<style>
.pf-panel{background:var(--pf-card);border:1px solid var(--pf-border);border-radius:14px;overflow:hidden;margin-bottom:16px}
.pf-panel-head{padding:14px 18px;border-bottom:1px solid rgba(0,0,0,.06);font-size:14px;font-weight:800;color:var(--pf-text);display:flex;align-items:center;justify-content:space-between}
.pf-panel-body{padding:18px}
.pf-profile{display:flex;align-items:flex-start;gap:24px;flex-wrap:wrap}
.pf-profile-avatar{width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,var(--pf-blue),var(--pf-green));color:#fff;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:28px;flex-shrink:0}
.pf-profile-info{flex:1;min-width:200px}
.pf-profile-name{font-size:20px;font-weight:800;color:var(--pf-text);margin-bottom:4px}
.pf-profile-role{display:inline-block;padding:3px 10px;border-radius:6px;font-size:10px;font-weight:700;letter-spacing:.5px}
.pf-detail-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:14px;margin-top:18px}
.pf-detail-item{padding:14px 16px;background:var(--pf-bg);border-radius:10px;border:1px solid var(--pf-border)}
.pf-detail-label{font-size:10px;font-weight:700;color:var(--pf-muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:4px}
.pf-detail-value{font-size:14px;font-weight:700;color:var(--pf-text)}
.pf-pill-green{background:rgba(74,222,128,.12);color:var(--pf-green)}
.pf-pill-blue{background:rgba(58,158,212,.12);color:var(--pf-blue)}
.pf-pill-yellow{background:rgba(245,168,0,.12);color:var(--pf-yellow)}
.pf-pill-red{background:rgba(248,113,113,.12);color:var(--pf-red)}
</style>
@endpush

@section('content')

<div class="pf-panel">
  <div class="pf-panel-head">
    <span><i class="fa fa-id-card" style="color:var(--pf-blue);margin-right:6px;"></i> Staff Profile</span>
    <div>
      <a href="{{ route('admin.staff.edit', $user->id) }}" class="btn btn-sm" style="background:rgba(58,158,212,.12);color:var(--pf-blue);font-weight:700;border-radius:8px;font-family:var(--pf-font);font-size:12px;">
        <i class="fa fa-pencil"></i> Edit
      </a>
      <a href="{{ route('admin.staff.index') }}" class="btn btn-sm" style="background:rgba(148,163,184,.15);color:var(--pf-muted);font-weight:700;border-radius:8px;font-family:var(--pf-font);font-size:12px;">
        <i class="fa fa-arrow-left"></i> Back
      </a>
    </div>
  </div>
  <div class="pf-panel-body">
    <div class="pf-profile">
      <div class="pf-profile-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
      <div class="pf-profile-info">
        <div class="pf-profile-name">{{ $user->name }}</div>
        @if($user->role)
          <span class="pf-profile-role {{ $user->role_id == 1 ? 'pf-pill-yellow' : ($user->role_id == 2 ? 'pf-pill-blue' : 'pf-pill-green') }}">
            {{ $user->role->name }}
          </span>
        @endif
      </div>
    </div>

    <div class="pf-detail-grid">
      <div class="pf-detail-item">
        <div class="pf-detail-label">Email</div>
        <div class="pf-detail-value">{{ $user->email }}</div>
      </div>
      <div class="pf-detail-item">
        <div class="pf-detail-label">Phone</div>
        <div class="pf-detail-value">{{ $user->phone_number }}</div>
      </div>
      <div class="pf-detail-item">
        <div class="pf-detail-label">Zone</div>
        <div class="pf-detail-value">{{ $user->zone->name ?? 'Not Assigned' }}</div>
      </div>
      <div class="pf-detail-item">
        <div class="pf-detail-label">Verified</div>
        <div class="pf-detail-value">
          @if($user->email_verified_at)
            <span class="pf-profile-role pf-pill-green">Verified</span>
          @else
            <span class="pf-profile-role pf-pill-red">Not Verified</span>
          @endif
        </div>
      </div>
      <div class="pf-detail-item">
        <div class="pf-detail-label">Created</div>
        <div class="pf-detail-value">{{ $user->created_at->format('d M Y, H:i') }}</div>
      </div>
      <div class="pf-detail-item">
        <div class="pf-detail-label">Last Updated</div>
        <div class="pf-detail-value">{{ $user->updated_at->format('d M Y, H:i') }}</div>
      </div>
    </div>
  </div>
</div>

@endsection
