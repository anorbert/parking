@extends('layouts.admin')

@section('title', $company->name . ' — ParkFlow')
@section('page-title', $company->name)

@push('styles')
<style>
.pf-detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 18px; }
.pf-detail-full { margin-bottom: 18px; }

.pf-panel { background: var(--pf-card); border: 1px solid var(--pf-border); border-radius: 14px; overflow: hidden; }
.pf-panel-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-bottom: 1px solid rgba(0,0,0,0.08); }
.pf-panel-title { font-size: 13px; font-weight: 800; letter-spacing: 0.3px; color: var(--pf-text); }
.pf-panel-sub { font-size: 11px; font-weight: 500; color: var(--pf-muted); margin-top: 1px; }
.pf-panel-body { padding: 16px 18px; }

.pf-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.pf-info-item label { display: block; font-size: 10px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--pf-muted); margin-bottom: 3px; }
.pf-info-item span { font-size: 14px; font-weight: 700; color: var(--pf-text); }

.pf-stat-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 18px; }
.pf-stat-card { background: var(--pf-card); border: 1px solid var(--pf-border); border-radius: 12px; padding: 16px; text-align: center; }
.pf-stat-val { font-size: 24px; font-weight: 800; color: var(--pf-text); }
.pf-stat-label { font-size: 10px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--pf-muted); margin-top: 4px; }

.pf-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
.pf-tbl thead tr { border-bottom: 1px solid rgba(0,0,0,0.08); }
.pf-tbl thead th { padding: 9px 12px; text-align: left; font-size: 10px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--pf-muted); white-space: nowrap; }
.pf-tbl tbody tr { border-bottom: 1px solid rgba(0,0,0,0.07); }
.pf-tbl tbody tr:last-child { border-bottom: none; }
.pf-tbl td { padding: 10px 12px; font-weight: 500; color: var(--pf-soft); vertical-align: middle; }

.pf-pill { display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px; border-radius: 999px; font-size: 10px; font-weight: 700; letter-spacing: 0.5px; }
.pf-pill-green { background: rgba(74,222,128,0.12); color: var(--pf-green); }
.pf-pill-red { background: rgba(248,113,113,0.12); color: var(--pf-red); }
.pf-pill-yellow { background: rgba(245,168,0,0.12); color: var(--pf-yellow); }

.pf-btn { display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; border-radius: 8px; border: none; cursor: pointer; font-family: var(--pf-font); font-size: 12px; font-weight: 700; transition: all 0.15s; text-decoration: none; }
.pf-btn-primary { background: var(--pf-yellow); color: #0D0F11; }
.pf-btn-primary:hover { background: #e09800; color: #0D0F11; text-decoration: none; }
.pf-btn-ghost { background: #F0F3F7; color: var(--pf-soft); }
.pf-btn-ghost:hover { background: #E4E8EE; text-decoration: none; color: var(--pf-soft); }

.pf-alert { padding: 10px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; margin-bottom: 14px; }
.pf-alert-success { background: rgba(74,222,128,0.12); color: var(--pf-green); border: 1px solid rgba(74,222,128,0.25); }

@media (max-width: 768px) {
  .pf-detail-grid { grid-template-columns: 1fr; }
  .pf-stat-row { grid-template-columns: repeat(2, 1fr); }
  .pf-info-grid { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

  @if(session('success'))
    <div class="pf-alert pf-alert-success">{{ session('success') }}</div>
  @endif

  {{-- Stats --}}
  <div class="pf-stat-row">
    <div class="pf-stat-card">
      <div class="pf-stat-val" style="color:var(--pf-blue);">{{ $company->users_count }}</div>
      <div class="pf-stat-label">Users</div>
    </div>
    <div class="pf-stat-card">
      <div class="pf-stat-val" style="color:var(--pf-green);">{{ $company->zones_count }}</div>
      <div class="pf-stat-label">Zones</div>
    </div>
    <div class="pf-stat-card">
      <div class="pf-stat-val" style="color:var(--pf-yellow);">{{ number_format($totalRevenue) }}</div>
      <div class="pf-stat-label">Revenue (RWF)</div>
    </div>
    <div class="pf-stat-card">
      <div class="pf-stat-val" style="color:var(--pf-purple);">{{ $company->vehicles_count }}</div>
      <div class="pf-stat-label">Vehicles</div>
    </div>
  </div>

  <div class="pf-detail-grid">
    {{-- Company Info --}}
    <div class="pf-panel">
      <div class="pf-panel-head">
        <div class="pf-panel-title">Company Information</div>
        <a href="{{ route('superadmin.companies.edit', $company->id) }}" class="pf-btn pf-btn-primary" style="padding:5px 12px;font-size:11px;">Edit</a>
      </div>
      <div class="pf-panel-body">
        <div class="pf-info-grid">
          <div class="pf-info-item">
            <label>Name</label>
            <span>{{ $company->name }}</span>
          </div>
          <div class="pf-info-item">
            <label>TIN</label>
            <span>{{ $company->tin ?? '—' }}</span>
          </div>
          <div class="pf-info-item">
            <label>Phone</label>
            <span>{{ $company->phone ?? '—' }}</span>
          </div>
          <div class="pf-info-item">
            <label>Email</label>
            <span>{{ $company->email ?? '—' }}</span>
          </div>
          <div class="pf-info-item">
            <label>Address</label>
            <span>{{ $company->address ?? '—' }}</span>
          </div>
          <div class="pf-info-item">
            <label>Status</label>
            <span class="pf-pill {{ $company->status === 'Active' ? 'pf-pill-green' : 'pf-pill-red' }}">{{ $company->status }}</span>
          </div>
        </div>
      </div>
    </div>

    {{-- Subscriptions --}}
    <div class="pf-panel">
      <div class="pf-panel-head">
        <div class="pf-panel-title">Recent Subscriptions</div>
        <form action="{{ route('superadmin.subscriptions.renew', $company->id) }}" method="POST" style="display:inline;">
          @csrf
          <button type="submit" class="pf-btn pf-btn-primary" style="padding:5px 12px;font-size:11px;">Renew</button>
        </form>
      </div>
      <div style="overflow-x:auto;">
        <table class="pf-tbl">
          <thead>
            <tr>
              <th>Period</th>
              <th>Amount</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($company->subscriptions as $sub)
            <tr>
              <td>{{ \Carbon\Carbon::parse($sub->start_date)->format('d M') }} – {{ \Carbon\Carbon::parse($sub->end_date)->format('d M Y') }}</td>
              <td>{{ number_format($sub->amount) }} RWF</td>
              <td>
                @if($sub->status === 'Active' && $sub->end_date >= now())
                  <span class="pf-pill pf-pill-green">Active</span>
                @elseif($sub->status === 'Pending')
                  <span class="pf-pill pf-pill-yellow">Pending</span>
                @else
                  <span class="pf-pill pf-pill-red">Expired</span>
                @endif
              </td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align:center;color:var(--pf-muted);">No subscriptions</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- Invoices --}}
  <div class="pf-detail-full">
    <div class="pf-panel">
      <div class="pf-panel-head">
        <div class="pf-panel-title">Recent Invoices</div>
      </div>
      <div style="overflow-x:auto;">
        <table class="pf-tbl">
          <thead>
            <tr>
              <th>Reference</th>
              <th>Amount</th>
              <th>Due Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($company->invoices as $inv)
            <tr>
              <td style="font-weight:700;">{{ $inv->reference }}</td>
              <td>{{ number_format($inv->amount) }} RWF</td>
              <td>{{ \Carbon\Carbon::parse($inv->due_date)->format('d M Y') }}</td>
              <td>
                <span class="pf-pill {{ $inv->status === 'Paid' ? 'pf-pill-green' : 'pf-pill-red' }}">{{ $inv->status }}</span>
              </td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;color:var(--pf-muted);">No invoices</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <a href="{{ route('superadmin.companies.index') }}" class="pf-btn pf-btn-ghost">← Back to Companies</a>

@endsection
