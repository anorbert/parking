@extends('layouts.admin')

@section('title', 'Register Company — ParkFlow')
@section('page-title', 'Register Company')

@push('styles')
<style>
.pf-form-panel { background: var(--pf-card); border: 1px solid var(--pf-border); border-radius: 14px; overflow: hidden; max-width: 720px; }
.pf-form-head { padding: 14px 18px; border-bottom: 1px solid rgba(0,0,0,0.08); }
.pf-form-title { font-size: 14px; font-weight: 800; color: var(--pf-text); }
.pf-form-sub { font-size: 11px; font-weight: 500; color: var(--pf-muted); margin-top: 2px; }
.pf-form-body { padding: 18px; }
.pf-form-section { font-size: 11px; font-weight: 800; letter-spacing: 2px; text-transform: uppercase; color: var(--pf-muted); margin: 18px 0 10px; padding-bottom: 6px; border-bottom: 1px solid rgba(0,0,0,0.06); }
.pf-form-section:first-child { margin-top: 0; }

.pf-form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 12px; }
.pf-form-full { margin-bottom: 12px; }

.pf-label { display: block; font-size: 11px; font-weight: 700; color: var(--pf-soft); margin-bottom: 4px; letter-spacing: 0.3px; }
.pf-input {
  width: 100%; padding: 9px 12px; border-radius: 8px;
  border: 1px solid #D0D5DD; background: #FAFBFC;
  font-family: var(--pf-font); font-size: 13px; font-weight: 500;
  color: var(--pf-text); transition: border-color 0.15s;
  box-sizing: border-box;
}
.pf-input:focus { outline: none; border-color: var(--pf-yellow); box-shadow: 0 0 0 3px rgba(245,168,0,0.1); }
.pf-input.is-invalid { border-color: var(--pf-red); }

.pf-error { font-size: 10px; font-weight: 600; color: var(--pf-red); margin-top: 3px; }

.pf-form-actions { display: flex; gap: 10px; margin-top: 18px; padding-top: 14px; border-top: 1px solid rgba(0,0,0,0.06); }
.pf-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 9px 20px; border-radius: 8px; border: none; cursor: pointer;
  font-family: var(--pf-font); font-size: 12px; font-weight: 700;
  transition: all 0.15s; text-decoration: none;
}
.pf-btn-primary { background: var(--pf-yellow); color: #0D0F11; }
.pf-btn-primary:hover { background: #e09800; }
.pf-btn-ghost { background: #F0F3F7; color: var(--pf-soft); }
.pf-btn-ghost:hover { background: #E4E8EE; text-decoration: none; color: var(--pf-soft); }

.pf-alert { padding: 10px 16px; border-radius: 8px; font-size: 12px; font-weight: 600; margin-bottom: 14px; }
.pf-alert-error { background: rgba(248,113,113,0.12); color: var(--pf-red); border: 1px solid rgba(248,113,113,0.25); }
</style>
@endpush

@section('content')

  @if(session('error'))
    <div class="pf-alert pf-alert-error">{{ session('error') }}</div>
  @endif

  <div class="pf-form-panel">
    <div class="pf-form-head">
      <div class="pf-form-title">Register New Company</div>
      <div class="pf-form-sub">Enter company details and primary admin information</div>
    </div>
    <div class="pf-form-body">
      <form action="{{ route('superadmin.companies.store') }}" method="POST">
        @csrf

        <div class="pf-form-section">Company Information</div>

        <div class="pf-form-row">
          <div>
            <label class="pf-label">Company Name *</label>
            <input type="text" name="name" class="pf-input @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            @error('name') <div class="pf-error">{{ $message }}</div> @enderror
          </div>
          <div>
            <label class="pf-label">TIN Number</label>
            <input type="text" name="tin" class="pf-input @error('tin') is-invalid @enderror" value="{{ old('tin') }}">
            @error('tin') <div class="pf-error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="pf-form-row">
          <div>
            <label class="pf-label">Phone</label>
            <input type="text" name="phone" class="pf-input @error('phone') is-invalid @enderror" value="{{ old('phone') }}">
            @error('phone') <div class="pf-error">{{ $message }}</div> @enderror
          </div>
          <div>
            <label class="pf-label">Email</label>
            <input type="email" name="email" class="pf-input @error('email') is-invalid @enderror" value="{{ old('email') }}">
            @error('email') <div class="pf-error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="pf-form-full">
          <label class="pf-label">Address</label>
          <input type="text" name="address" class="pf-input @error('address') is-invalid @enderror" value="{{ old('address') }}">
          @error('address') <div class="pf-error">{{ $message }}</div> @enderror
        </div>

        <div class="pf-form-section">Company Admin</div>

        <div class="pf-form-row">
          <div>
            <label class="pf-label">Admin Name *</label>
            <input type="text" name="admin_name" class="pf-input @error('admin_name') is-invalid @enderror" value="{{ old('admin_name') }}" required>
            @error('admin_name') <div class="pf-error">{{ $message }}</div> @enderror
          </div>
          <div>
            <label class="pf-label">Admin Email *</label>
            <input type="email" name="admin_email" class="pf-input @error('admin_email') is-invalid @enderror" value="{{ old('admin_email') }}" required>
            @error('admin_email') <div class="pf-error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="pf-form-row">
          <div>
            <label class="pf-label">Admin Phone *</label>
            <input type="text" name="admin_phone" class="pf-input @error('admin_phone') is-invalid @enderror" value="{{ old('admin_phone') }}" required>
            @error('admin_phone') <div class="pf-error">{{ $message }}</div> @enderror
          </div>
          <div></div>
        </div>

        <p style="font-size:11px;color:var(--pf-muted);margin-top:4px;">A 4-digit PIN will be auto-generated for the admin user.</p>

        <div class="pf-form-actions">
          <button type="submit" class="pf-btn pf-btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            Register Company
          </button>
          <a href="{{ route('superadmin.companies.index') }}" class="pf-btn pf-btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>

@endsection
