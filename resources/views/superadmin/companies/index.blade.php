@extends('layouts.admin')

@section('title', 'Companies — ParkFlow')
@section('page-title', 'Companies')

@push('styles')
<style>
.pf-page-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.pf-page-title { font-size: 18px; font-weight: 800; color: var(--pf-text); }

.pf-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer;
  font-family: var(--pf-font); font-size: 12px; font-weight: 700;
  transition: all 0.15s; text-decoration: none;
}
.pf-btn-primary { background: var(--pf-yellow); color: #0D0F11; }
.pf-btn-primary:hover { background: #e09800; color: #0D0F11; text-decoration: none; }
.pf-btn-sm { padding: 5px 10px; font-size: 11px; border-radius: 6px; }
.pf-btn-blue { background: rgba(58,158,212,0.12); color: var(--pf-blue); }
.pf-btn-blue:hover { background: rgba(58,158,212,0.22); color: var(--pf-blue); text-decoration: none; }
.pf-btn-red { background: rgba(248,113,113,0.12); color: var(--pf-red); }
.pf-btn-red:hover { background: rgba(248,113,113,0.22); color: var(--pf-red); text-decoration: none; }

.pf-panel { background: var(--pf-card); border: 1px solid var(--pf-border); border-radius: 14px; overflow: hidden; }
.pf-panel-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-bottom: 1px solid rgba(0,0,0,0.08); }
.pf-panel-title { font-size: 13px; font-weight: 800; letter-spacing: 0.3px; color: var(--pf-text); }

.pf-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
.pf-tbl thead tr { border-bottom: 1px solid rgba(0,0,0,0.08); }
.pf-tbl thead th { padding: 9px 12px; text-align: left; font-size: 10px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--pf-muted); white-space: nowrap; }
.pf-tbl tbody tr { border-bottom: 1px solid rgba(0,0,0,0.07); transition: background 0.1s; }
.pf-tbl tbody tr:hover { background: rgba(0,0,0,0.02); }
.pf-tbl tbody tr:last-child { border-bottom: none; }
.pf-tbl td { padding: 10px 12px; font-weight: 500; color: var(--pf-soft); vertical-align: middle; }
.pf-tbl td:first-child { color: var(--pf-text); font-weight: 600; }

.pf-pill { display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px; border-radius: 999px; font-size: 10px; font-weight: 700; letter-spacing: 0.5px; }
.pf-pill-green { background: rgba(74,222,128,0.12); color: var(--pf-green); }
.pf-pill-red { background: rgba(248,113,113,0.12); color: var(--pf-red); }
.pf-pill-yellow { background: rgba(245,168,0,0.12); color: var(--pf-yellow); }

.pf-actions { display: flex; gap: 6px; }

.pf-alert { padding: 10px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; margin-bottom: 14px; }
.pf-alert-success { background: rgba(74,222,128,0.12); color: var(--pf-green); border: 1px solid rgba(74,222,128,0.25); }
.pf-alert-error { background: rgba(248,113,113,0.12); color: var(--pf-red); border: 1px solid rgba(248,113,113,0.25); }
</style>
@endpush

@section('content')

  @if(session('success'))
    <div class="pf-alert pf-alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="pf-alert pf-alert-error">{{ session('error') }}</div>
  @endif

  <div class="pf-page-bar">
    <div class="pf-page-title">All Companies</div>
    <a href="{{ route('superadmin.companies.create') }}" class="pf-btn pf-btn-primary">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Register Company
    </a>
  </div>

  <div class="pf-panel">
    <div class="pf-panel-head">
      <div class="pf-panel-title">Companies ({{ $companies->count() }})</div>
    </div>
    <div style="overflow-x:auto;">
      <table class="pf-tbl" id="companies-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Company Name</th>
            <th>TIN</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Users</th>
            <th>Zones</th>
            <th>Subscription</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($companies as $i => $company)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $company->name }}</td>
            <td>{{ $company->tin ?? '—' }}</td>
            <td>{{ $company->phone ?? '—' }}</td>
            <td>{{ $company->email ?? '—' }}</td>
            <td>{{ $company->users_count }}</td>
            <td>{{ $company->zones_count }}</td>
            <td>
              @if($company->activeSubscription)
                <span class="pf-pill pf-pill-green">Active</span>
              @else
                <span class="pf-pill pf-pill-red">Expired</span>
              @endif
            </td>
            <td>
              <span class="pf-pill {{ $company->status === 'Active' ? 'pf-pill-green' : 'pf-pill-red' }}">{{ $company->status }}</span>
            </td>
            <td>
              <div class="pf-actions">
                <a href="{{ route('superadmin.companies.show', $company->id) }}" class="pf-btn pf-btn-sm pf-btn-blue">View</a>
                <a href="{{ route('superadmin.companies.edit', $company->id) }}" class="pf-btn pf-btn-sm pf-btn-primary">Edit</a>
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="10" style="text-align:center;color:var(--pf-muted);padding:24px;">No companies registered yet</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

@endsection

@push('scripts')
<script>
$(function() {
  if ($.fn.DataTable) {
    $('#companies-table').DataTable({
      pageLength: 25,
      order: [[0, 'asc']],
      language: { search: '', searchPlaceholder: 'Search companies...' }
    });
  }
});
</script>
@endpush
