@extends('layouts.admin')

@section('title', 'Subscription Plans — ParkFlow')
@section('page-title', 'Subscription Plans')

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

.pf-btn { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border-radius: 6px; border: none; cursor: pointer; font-family: var(--pf-font); font-size: 11px; font-weight: 700; transition: all 0.15s; text-decoration: none; }
.pf-btn-primary { background: var(--pf-yellow); color: #0D0F11; }
.pf-btn-primary:hover { background: #e09800; color: #0D0F11; text-decoration: none; }
.pf-btn-ghost { background: #F0F3F7; color: var(--pf-soft); }
.pf-btn-ghost:hover { background: #E4E8EE; text-decoration: none; color: var(--pf-soft); }
.pf-btn-red { background: rgba(248,113,113,0.12); color: var(--pf-red); }
.pf-btn-red:hover { background: rgba(248,113,113,0.22); }

.pf-alert { padding: 10px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; margin-bottom: 14px; }
.pf-alert-success { background: rgba(74,222,128,0.12); color: var(--pf-green); border: 1px solid rgba(74,222,128,0.25); }
.pf-alert-error { background: rgba(248,113,113,0.12); color: var(--pf-red); border: 1px solid rgba(248,113,113,0.25); }

.pf-features { display: flex; gap: 6px; flex-wrap: wrap; }
.pf-feat { font-size: 9px; font-weight: 700; padding: 2px 6px; border-radius: 4px; background: rgba(58,158,212,0.10); color: var(--pf-blue, #3A9ED4); letter-spacing: 0.3px; }
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
    <div class="pf-page-title">Subscription Plans</div>
    <a href="{{ route('superadmin.plans.create') }}" class="pf-btn pf-btn-primary">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      New Plan
    </a>
  </div>

  <div class="pf-panel">
    <div class="pf-panel-head">
      <div class="pf-panel-title">Plans ({{ $plans->count() }})</div>
    </div>
    <div style="overflow-x:auto;">
      <table class="pf-tbl">
        <thead>
          <tr>
            <th>#</th>
            <th>Plan Name</th>
            <th>Price</th>
            <th>Duration</th>
            <th>Limits</th>
            <th>Features</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($plans as $i => $plan)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td>
              <div style="font-weight:700;">{{ $plan->name }}</div>
              @if($plan->description)
                <div style="font-size:10px;color:var(--pf-muted);margin-top:2px;">{{ $plan->description }}</div>
              @endif
            </td>
            <td style="font-weight:700;">{{ number_format($plan->price) }} RWF</td>
            <td>{{ $plan->duration_days }} days</td>
            <td>
              <div style="font-size:10px;line-height:1.6;">
                @if($plan->max_zones) <div>Zones: {{ $plan->max_zones }}</div> @endif
                @if($plan->max_slots) <div>Slots: {{ $plan->max_slots }}</div> @endif
                @if($plan->max_users) <div>Users: {{ $plan->max_users }}</div> @endif
                @if(!$plan->max_zones && !$plan->max_slots && !$plan->max_users) <span style="color:var(--pf-muted);">Unlimited</span> @endif
              </div>
            </td>
            <td>
              <div class="pf-features">
                @if($plan->momo_payments) <span class="pf-feat">MoMo</span> @endif
                @if($plan->reports_enabled) <span class="pf-feat">Reports</span> @endif
              </div>
            </td>
            <td>
              @if($plan->is_active)
                <span class="pf-pill pf-pill-green">Active</span>
              @else
                <span class="pf-pill pf-pill-red">Inactive</span>
              @endif
            </td>
            <td>
              <div style="display:flex;gap:6px;">
                <a href="{{ route('superadmin.plans.edit', $plan) }}" class="pf-btn pf-btn-ghost">Edit</a>
                @if(!$plan->subscriptions()->exists())
                  <form action="{{ route('superadmin.plans.destroy', $plan) }}" method="POST" onsubmit="return confirm('Delete this plan?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="pf-btn pf-btn-red">Delete</button>
                  </form>
                @endif
              </div>
            </td>
          </tr>
          @empty
          <tr><td colspan="8" style="text-align:center;color:var(--pf-muted);padding:24px;">No plans created yet</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

@endsection
