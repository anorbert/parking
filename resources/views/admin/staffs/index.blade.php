@extends('layouts.admin')

@section('title', 'Staff — ParkFlow')
@section('page-title', 'Users (Staff)')

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
.pf-pill-yellow{background:rgba(245,168,0,.12);color:var(--pf-yellow)}
.pf-avatar-sm{width:32px;height:32px;border-radius:50%;background:var(--pf-blue);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-weight:800;font-size:12px;margin-right:8px}
</style>
@endpush

@section('content')

@if(session('success'))
  <div class="alert alert-success alert-dismissible" style="border-radius:10px;">{{ session('success') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>
@endif
@if(session('error'))
  <div class="alert alert-danger alert-dismissible" style="border-radius:10px;">{{ session('error') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>
@endif

<div class="pf-panel">
  <div class="pf-panel-head">
    <span><i class="fa fa-users" style="color:var(--pf-blue);margin-right:6px;"></i> Staff Members</span>
    <a href="{{ route('admin.staff.create') }}" class="btn btn-sm" style="background:var(--pf-green);color:#0D0F11;font-weight:700;border-radius:8px;font-family:var(--pf-font);font-size:12px;">
      <i class="fa fa-plus"></i> Add Staff
    </a>
  </div>
  <div class="pf-panel-body" style="padding:0;">
    <div class="table-responsive">
      <table class="pf-tbl">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Role</th>
            <th>Created</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($staff as $i => $user)
          <tr>
            <td>{{ $staff->firstItem() + $i }}</td>
            <td>
              <span class="pf-avatar-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
              {{ $user->name }}
            </td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone_number }}</td>
            <td>
              @if($user->role_id == 1)
                <span class="pf-pill pf-pill-yellow">Admin</span>
              @elseif($user->role_id == 2)
                <span class="pf-pill pf-pill-blue">Editor</span>
              @else
                <span class="pf-pill pf-pill-green">User</span>
              @endif
            </td>
            <td>{{ $user->created_at->format('d M Y') }}</td>
            <td>
              <a href="{{ route('admin.staff.show', $user->id) }}" class="btn btn-sm" style="background:rgba(168,85,247,.12);color:#A855F7;font-weight:700;border-radius:6px;font-size:11px;font-family:var(--pf-font);">
                <i class="fa fa-eye"></i>
              </a>
              <a href="{{ route('admin.staff.edit', $user->id) }}" class="btn btn-sm" style="background:rgba(58,158,212,.12);color:var(--pf-blue);font-weight:700;border-radius:6px;font-size:11px;font-family:var(--pf-font);">
                <i class="fa fa-pencil"></i>
              </a>
              <form method="POST" action="{{ route('admin.staff.destroy', $user->id) }}" style="display:inline;" onsubmit="return confirm('Delete this staff member?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-sm" style="background:rgba(248,113,113,.12);color:var(--pf-red);font-weight:700;border-radius:6px;font-size:11px;font-family:var(--pf-font);">
                  <i class="fa fa-trash"></i>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" style="text-align:center;padding:30px;color:var(--pf-muted);font-weight:700;">No staff members found.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @if($staff->hasPages())
      <div style="padding:14px 18px;display:flex;justify-content:center;">
        {{ $staff->links() }}
      </div>
    @endif
  </div>
</div>

@endsection
