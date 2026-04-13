@extends('layouts.admin')

@section('title', 'My Company — ParkFlow')
@section('page-title', 'My Company')

@push('styles')
<style>
.pf-company-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }

/* Info Card */
.pf-co-card {
  background: var(--pf-card); border: 1px solid var(--pf-border);
  border-radius: 14px; overflow: hidden;
}
.pf-co-banner {
  height: 72px; position: relative;
  background: linear-gradient(135deg, #1B2235, #2A3450);
}
.pf-co-banner-badge {
  position: absolute; top: 12px; right: 14px;
  padding: 3px 10px; border-radius: 999px;
  font-size: 10px; font-weight: 700; letter-spacing: 0.5px;
}
.pf-co-avatar {
  width: 56px; height: 56px; border-radius: 14px;
  background: linear-gradient(135deg, var(--pf-yellow), var(--pf-yellow2));
  border: 3px solid var(--pf-card);
  display: flex; align-items: center; justify-content: center;
  font-size: 22px; font-weight: 800; color: #0D0F11;
  position: absolute; bottom: -28px; left: 20px;
}
.pf-co-body { padding: 38px 20px 18px; }
.pf-co-name { font-size: 18px; font-weight: 800; color: var(--pf-text); margin-bottom: 2px; }
.pf-co-sub { font-size: 11px; font-weight: 600; color: var(--pf-muted); letter-spacing: 0.3px; }

.pf-co-details {
  display: grid; grid-template-columns: 1fr 1fr; gap: 14px;
  margin-top: 16px; padding-top: 14px;
  border-top: 1px solid rgba(0,0,0,0.06);
}
.pf-co-dlabel {
  font-size: 9px; font-weight: 700;
  letter-spacing: 1.5px; text-transform: uppercase;
  color: var(--pf-muted); margin-bottom: 2px;
}
.pf-co-dval { font-size: 13px; font-weight: 600; color: var(--pf-text); }

/* Stats */
.pf-co-stats {
  display: grid; grid-template-columns: 1fr 1fr; gap: 10px;
}
.pf-co-stat {
  background: var(--pf-card); border: 1px solid var(--pf-border);
  border-radius: 12px; padding: 14px 16px; text-align: center;
}
.pf-co-stat-icon {
  width: 34px; height: 34px; border-radius: 8px;
  display: inline-flex; align-items: center; justify-content: center;
  margin-bottom: 8px;
}
.pf-co-stat-val { font-size: 22px; font-weight: 800; color: var(--pf-text); line-height: 1; }
.pf-co-stat-lbl { font-size: 10px; font-weight: 700; color: var(--pf-muted); letter-spacing: 1px; text-transform: uppercase; margin-top: 4px; }

/* Subscription Card */
.pf-co-sub-card {
  background: var(--pf-card); border: 1px solid var(--pf-border);
  border-radius: 14px; overflow: hidden;
  grid-column: 1 / -1;
}
.pf-co-sub-head {
  padding: 14px 18px; display: flex; align-items: center; justify-content: space-between;
  border-bottom: 1px solid rgba(0,0,0,0.08);
}
.pf-co-sub-title { font-size: 13px; font-weight: 800; color: var(--pf-text); }
.pf-co-sub-body { padding: 16px 18px; display: flex; align-items: center; gap: 20px; }
.pf-co-sub-plan {
  flex: 1; display: flex; align-items: center; gap: 14px;
}
.pf-co-sub-icon {
  width: 42px; height: 42px; border-radius: 10px;
  background: rgba(74,222,128,0.12);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.pf-co-sub-name { font-size: 15px; font-weight: 800; color: var(--pf-text); }
.pf-co-sub-price { font-size: 12px; font-weight: 600; color: var(--pf-muted); }
.pf-co-sub-dates { font-size: 11px; font-weight: 600; color: var(--pf-soft); }
.pf-co-sub-days {
  padding: 6px 14px; border-radius: 8px;
  font-size: 12px; font-weight: 700;
}

/* Edit Form */
.pf-co-edit {
  background: var(--pf-card); border: 1px solid var(--pf-border);
  border-radius: 14px; overflow: hidden;
  grid-column: 1 / -1;
}
.pf-co-edit-head {
  padding: 14px 18px; border-bottom: 1px solid rgba(0,0,0,0.08);
}
.pf-co-edit-title { font-size: 13px; font-weight: 800; color: var(--pf-text); }
.pf-co-edit-body { padding: 18px; }
.pf-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 12px; }
.pf-form-full { margin-bottom: 12px; }
.pf-label { display: block; font-size: 11px; font-weight: 700; color: var(--pf-soft); margin-bottom: 4px; letter-spacing: 0.3px; }
.pf-input { width: 100%; padding: 9px 12px; border-radius: 8px; border: 1px solid #D0D5DD; background: #FAFBFC; font-family: var(--pf-font); font-size: 13px; font-weight: 500; color: var(--pf-text); transition: border-color 0.15s; box-sizing: border-box; }
.pf-input:focus { outline: none; border-color: var(--pf-yellow); box-shadow: 0 0 0 3px rgba(245,168,0,0.1); }
.pf-input.is-invalid { border-color: var(--pf-red); }
.pf-error { font-size: 10px; font-weight: 600; color: var(--pf-red); margin-top: 3px; }
.pf-form-actions { display: flex; gap: 10px; margin-top: 18px; padding-top: 14px; border-top: 1px solid rgba(0,0,0,0.06); }
.pf-btn { display: inline-flex; align-items: center; gap: 6px; padding: 9px 20px; border-radius: 8px; border: none; cursor: pointer; font-family: var(--pf-font); font-size: 12px; font-weight: 700; transition: all 0.15s; text-decoration: none; }
.pf-btn-primary { background: var(--pf-yellow); color: #0D0F11; }
.pf-btn-primary:hover { background: #e09800; }

.pf-pill { display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px; border-radius: 999px; font-size: 10px; font-weight: 700; letter-spacing: 0.5px; }
.pf-pill-green { background: rgba(74,222,128,0.12); color: var(--pf-green); }
.pf-pill-red { background: rgba(248,113,113,0.12); color: var(--pf-red); }
.pf-pill-yellow { background: rgba(245,168,0,0.12); color: var(--pf-yellow); }

.pf-alert { padding: 10px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; margin-bottom: 14px; }
.pf-alert-success { background: rgba(74,222,128,0.12); color: var(--pf-green); border: 1px solid rgba(74,222,128,0.25); }
.pf-alert-error { background: rgba(248,113,113,0.12); color: var(--pf-red); border: 1px solid rgba(248,113,113,0.25); }

@media (max-width: 768px) { .pf-company-grid { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')

  @if(session('success'))
    <div class="pf-alert pf-alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="pf-alert pf-alert-error">{{ session('error') }}</div>
  @endif

  <div class="pf-company-grid">

    {{-- Company Info Card --}}
    <div class="pf-co-card">
      <div class="pf-co-banner">
        <span class="pf-co-banner-badge {{ $company->status === 'Active' ? 'pf-pill-green' : 'pf-pill-red' }}">
          {{ $company->status }}
        </span>
        <div class="pf-co-avatar">{{ strtoupper(substr($company->name, 0, 2)) }}</div>
      </div>
      <div class="pf-co-body">
        <div class="pf-co-name">{{ $company->name }}</div>
        <div class="pf-co-sub">Registered {{ $company->created_at->format('d M Y') }}</div>

        <div class="pf-co-details">
          <div>
            <div class="pf-co-dlabel">TIN</div>
            <div class="pf-co-dval">{{ $company->tin ?? '—' }}</div>
          </div>
          <div>
            <div class="pf-co-dlabel">Phone</div>
            <div class="pf-co-dval">{{ $company->phone ?? '—' }}</div>
          </div>
          <div>
            <div class="pf-co-dlabel">Email</div>
            <div class="pf-co-dval">{{ $company->email ?? '—' }}</div>
          </div>
          <div>
            <div class="pf-co-dlabel">Address</div>
            <div class="pf-co-dval">{{ $company->address ?? '—' }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Stats --}}
    <div class="pf-co-stats">
      <div class="pf-co-stat">
        <div class="pf-co-stat-icon" style="background:rgba(58,158,212,0.12);">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#3A9ED4" stroke-width="2" stroke-linecap="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <div class="pf-co-stat-val">{{ $company->users_count }}</div>
        <div class="pf-co-stat-lbl">Staff</div>
      </div>
      <div class="pf-co-stat">
        <div class="pf-co-stat-icon" style="background:rgba(74,222,128,0.12);">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4ADE80" stroke-width="2" stroke-linecap="round"><rect x="3" y="3" width="18" height="18" rx="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
        </div>
        <div class="pf-co-stat-val">{{ $company->zones_count }}</div>
        <div class="pf-co-stat-lbl">Zones</div>
      </div>
      <div class="pf-co-stat">
        <div class="pf-co-stat-icon" style="background:rgba(245,168,0,0.12);">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#F5A800" stroke-width="2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
        </div>
        <div class="pf-co-stat-val">{{ $company->vehicles_count }}</div>
        <div class="pf-co-stat-lbl">Vehicles</div>
      </div>
      <div class="pf-co-stat">
        <div class="pf-co-stat-icon" style="background:rgba(167,139,250,0.12);">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#A78BFA" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        </div>
        <div class="pf-co-stat-val">
          @if($company->activeSubscription)
            <span class="pf-pill pf-pill-green">Active</span>
          @else
            <span class="pf-pill pf-pill-red">Expired</span>
          @endif
        </div>
        <div class="pf-co-stat-lbl">Subscription</div>
      </div>
    </div>

    {{-- Subscription Info --}}
    @if($company->activeSubscription)
    <div class="pf-co-sub-card">
      <div class="pf-co-sub-head">
        <div class="pf-co-sub-title">Current Subscription</div>
        <span class="pf-pill pf-pill-green">Active</span>
      </div>
      <div class="pf-co-sub-body">
        <div class="pf-co-sub-plan">
          <div class="pf-co-sub-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4ADE80" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          </div>
          <div>
            <div class="pf-co-sub-name">{{ $company->activeSubscription->plan->name ?? 'Standard Plan' }}</div>
            <div class="pf-co-sub-price">{{ number_format($company->activeSubscription->amount) }} RWF</div>
          </div>
        </div>
        <div style="text-align:right;">
          <div class="pf-co-sub-dates">
            {{ $company->activeSubscription->start_date->format('d M Y') }} — {{ $company->activeSubscription->end_date->format('d M Y') }}
          </div>
          @php
            $daysLeft = now()->diffInDays($company->activeSubscription->end_date, false);
          @endphp
          <div class="pf-co-sub-days {{ $daysLeft <= 7 ? 'pf-pill-yellow' : 'pf-pill-green' }}" style="margin-top:6px;display:inline-block;">
            {{ max(0, $daysLeft) }} days remaining
          </div>
        </div>
      </div>
    </div>
    @endif

    {{-- Edit Company --}}
    <div class="pf-co-edit">
      <div class="pf-co-edit-head">
        <div class="pf-co-edit-title">Edit Company Information</div>
      </div>
      <div class="pf-co-edit-body">
        <form action="{{ route('admin.company.update') }}" method="POST">
          @csrf @method('PUT')

          <div class="pf-form-row">
            <div>
              <label class="pf-label">Company Name *</label>
              <input type="text" name="name" class="pf-input @error('name') is-invalid @enderror" value="{{ old('name', $company->name) }}" required>
              @error('name') <div class="pf-error">{{ $message }}</div> @enderror
            </div>
            <div>
              <label class="pf-label">TIN Number</label>
              <input type="text" name="tin" class="pf-input @error('tin') is-invalid @enderror" value="{{ old('tin', $company->tin) }}">
              @error('tin') <div class="pf-error">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="pf-form-row">
            <div>
              <label class="pf-label">Phone</label>
              <input type="text" name="phone" class="pf-input @error('phone') is-invalid @enderror" value="{{ old('phone', $company->phone) }}">
              @error('phone') <div class="pf-error">{{ $message }}</div> @enderror
            </div>
            <div>
              <label class="pf-label">Email</label>
              <input type="email" name="email" class="pf-input @error('email') is-invalid @enderror" value="{{ old('email', $company->email) }}">
              @error('email') <div class="pf-error">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="pf-form-full">
            <label class="pf-label">Address</label>
            <input type="text" name="address" class="pf-input @error('address') is-invalid @enderror" value="{{ old('address', $company->address) }}">
            @error('address') <div class="pf-error">{{ $message }}</div> @enderror
          </div>

          <div class="pf-form-actions">
            <button type="submit" class="pf-btn pf-btn-primary">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
              Update Company
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
@endsection
