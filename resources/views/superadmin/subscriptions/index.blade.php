@extends('layouts.admin')

@section('title', 'Subscriptions — ParkFlow')
@section('page-title', 'Subscriptions')

@push('styles')
<style>
.pf-page-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
.pf-page-title { font-size: 18px; font-weight: 800; color: var(--pf-text); }

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
.pf-pill-blue { background: rgba(58,158,212,0.10); color: var(--pf-blue, #3A9ED4); }

.pf-btn { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; border: none; cursor: pointer; font-family: var(--pf-font); font-size: 11px; font-weight: 700; transition: all 0.15s; text-decoration: none; }
.pf-btn-green { background: rgba(74,222,128,0.12); color: var(--pf-green); }
.pf-btn-green:hover { background: rgba(74,222,128,0.22); color: var(--pf-green); text-decoration: none; }
.pf-btn-primary { background: var(--pf-yellow); color: #0D0F11; }
.pf-btn-primary:hover { background: #e09800; color: #0D0F11; text-decoration: none; }

.pf-select { padding: 5px 8px; border-radius: 6px; border: 1px solid #D0D5DD; background: #FAFBFC; font-family: var(--pf-font); font-size: 11px; font-weight: 600; color: var(--pf-text); }

.pf-alert { padding: 10px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; margin-bottom: 14px; }
.pf-alert-success { background: rgba(74,222,128,0.12); color: var(--pf-green); border: 1px solid rgba(74,222,128,0.25); }
.pf-alert-error { background: rgba(248,113,113,0.12); color: var(--pf-red); border: 1px solid rgba(248,113,113,0.25); }

.pf-renew-form { display: flex; align-items: center; gap: 6px; }
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
    <div class="pf-page-title">All Subscriptions</div>
  </div>

  <div class="pf-panel">
    <div class="pf-panel-head">
      <div class="pf-panel-title">Subscriptions ({{ $subscriptions->count() }})</div>
    </div>
    <div style="overflow-x:auto;">
      <table class="pf-tbl" id="subs-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Company</th>
            <th>Plan</th>
            <th>Amount</th>
            <th>Period</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Created By</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($subscriptions as $i => $sub)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $sub->company->name ?? '—' }}</td>
            <td>
              @if($sub->plan)
                <span class="pf-pill pf-pill-blue">{{ $sub->plan->name }}</span>
              @else
                <span style="color:var(--pf-muted);font-size:11px;">Legacy</span>
              @endif
            </td>
            <td style="font-weight:700;">{{ number_format($sub->amount) }} RWF</td>
            <td>
              <div style="font-size:11px;">{{ $sub->start_date ? \Carbon\Carbon::parse($sub->start_date)->format('d M Y') : '—' }}</div>
              <div style="font-size:10px;color:var(--pf-muted);">to {{ $sub->end_date ? \Carbon\Carbon::parse($sub->end_date)->format('d M Y') : '—' }}</div>
            </td>
            <td>
              @if($sub->status === 'Active' && $sub->end_date >= now())
                <span class="pf-pill pf-pill-green">Active</span>
              @elseif($sub->status === 'Pending')
                <span class="pf-pill pf-pill-yellow">Pending</span>
              @else
                <span class="pf-pill pf-pill-red">Expired</span>
              @endif
            </td>
            <td>
              <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;">{{ $sub->payment_method ?? 'cash' }}</span>
              @if($sub->paid_at)
                <div style="font-size:9px;color:var(--pf-muted);">{{ \Carbon\Carbon::parse($sub->paid_at)->format('d M Y') }}</div>
              @endif
            </td>
            <td>{{ $sub->creator->name ?? '—' }}</td>
            <td>
              @if($sub->status === 'Pending')
                <form action="{{ route('superadmin.subscriptions.activate', $sub->id) }}" method="POST" style="display:inline;">
                  @csrf
                  <button type="submit" class="pf-btn pf-btn-green">Activate</button>
                </form>
              @endif
              @if($sub->status === 'Expired' || ($sub->end_date && $sub->end_date < now()))
                <form action="{{ route('superadmin.subscriptions.renew', $sub->company_id) }}" method="POST" class="pf-renew-form">
                  @csrf
                  <select name="plan_id" class="pf-select" required>
                    @foreach($plans as $plan)
                      <option value="{{ $plan->id }}">{{ $plan->name }} ({{ number_format($plan->price) }})</option>
                    @endforeach
                  </select>
                  <button type="submit" class="pf-btn pf-btn-primary">Renew</button>
                </form>
              @endif
            </td>
          </tr>
          @empty
          <tr><td colspan="9" style="text-align:center;color:var(--pf-muted);padding:24px;">No subscriptions yet</td></tr>
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
    $('#subs-table').DataTable({
      pageLength: 25,
      order: [[0, 'asc']],
      language: { search: '', searchPlaceholder: 'Search subscriptions...' }
    });
  }
});
</script>
@endpush
