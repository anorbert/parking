@extends('layouts.' . $layout)

@section('title', 'My Profile — ParkFlow')
@section('page-title', 'My Profile')

@php $prefix = $layout === 'admin' ? 'pf' : 'uf'; @endphp

@push('styles')
<style>
.{{ $prefix }}-profile-wrap { max-width: 720px; }

.{{ $prefix }}-profile-card {
  background: {{ $prefix === 'pf' ? 'var(--pf-card)' : '#1E2226' }};
  border: 1px solid {{ $prefix === 'pf' ? 'var(--pf-border)' : 'rgba(255,255,255,0.09)' }};
  border-radius: 14px; overflow: hidden;
}

.{{ $prefix }}-profile-banner {
  height: 80px;
  background: linear-gradient(135deg, {{ $prefix === 'pf' ? '#F5A800' : '#F5A800' }}, {{ $prefix === 'pf' ? '#FFD166' : '#3A9ED4' }});
  position: relative;
}

.{{ $prefix }}-profile-avatar {
  width: 72px; height: 72px; border-radius: 16px;
  background: {{ $prefix === 'pf' ? '#0D0F11' : '#111315' }};
  border: 3px solid {{ $prefix === 'pf' ? 'var(--pf-card)' : '#1E2226' }};
  display: flex; align-items: center; justify-content: center;
  font-size: 24px; font-weight: 800;
  color: #F5A800;
  position: absolute; bottom: -36px; left: 24px;
  font-family: {{ $prefix === 'pf' ? 'var(--pf-font)' : "'Dosis', sans-serif" }};
}

.{{ $prefix }}-profile-body { padding: 48px 24px 24px; }

.{{ $prefix }}-profile-name {
  font-size: 20px; font-weight: 800;
  color: {{ $prefix === 'pf' ? 'var(--pf-text)' : '#F3F4F6' }};
  margin-bottom: 2px;
}

.{{ $prefix }}-profile-role {
  font-size: 11px; font-weight: 700;
  letter-spacing: 1.5px; text-transform: uppercase;
  color: {{ $prefix === 'pf' ? 'var(--pf-muted)' : '#6B7280' }};
}

.{{ $prefix }}-profile-info {
  display: grid; grid-template-columns: 1fr 1fr; gap: 16px;
  margin-top: 20px; padding-top: 16px;
  border-top: 1px solid {{ $prefix === 'pf' ? 'rgba(0,0,0,0.06)' : 'rgba(255,255,255,0.08)' }};
}

.{{ $prefix }}-info-label {
  font-size: 10px; font-weight: 700;
  letter-spacing: 1.5px; text-transform: uppercase;
  color: {{ $prefix === 'pf' ? 'var(--pf-muted)' : '#6B7280' }};
  margin-bottom: 3px;
}

.{{ $prefix }}-info-value {
  font-size: 13px; font-weight: 600;
  color: {{ $prefix === 'pf' ? 'var(--pf-text)' : '#F3F4F6' }};
}

/* Edit Form */
.{{ $prefix }}-edit-panel {
  background: {{ $prefix === 'pf' ? 'var(--pf-card)' : '#1E2226' }};
  border: 1px solid {{ $prefix === 'pf' ? 'var(--pf-border)' : 'rgba(255,255,255,0.09)' }};
  border-radius: 14px; overflow: hidden;
  margin-top: 16px;
}

.{{ $prefix }}-edit-head {
  padding: 14px 18px;
  border-bottom: 1px solid {{ $prefix === 'pf' ? 'rgba(0,0,0,0.08)' : 'rgba(255,255,255,0.08)' }};
}

.{{ $prefix }}-edit-title {
  font-size: 14px; font-weight: 800;
  color: {{ $prefix === 'pf' ? 'var(--pf-text)' : '#F3F4F6' }};
}

.{{ $prefix }}-edit-body { padding: 18px; }

.{{ $prefix }}-form-row {
  display: grid; grid-template-columns: 1fr 1fr;
  gap: 14px; margin-bottom: 12px;
}

.{{ $prefix }}-label {
  display: block; font-size: 11px; font-weight: 700;
  color: {{ $prefix === 'pf' ? 'var(--pf-soft)' : '#9CA3AF' }};
  margin-bottom: 4px; letter-spacing: 0.3px;
}

.{{ $prefix }}-input {
  width: 100%; padding: 9px 12px; border-radius: 8px;
  border: 1px solid {{ $prefix === 'pf' ? '#D0D5DD' : 'rgba(255,255,255,0.12)' }};
  background: {{ $prefix === 'pf' ? '#FAFBFC' : 'rgba(255,255,255,0.05)' }};
  font-family: {{ $prefix === 'pf' ? 'var(--pf-font)' : "'Dosis', sans-serif" }};
  font-size: 13px; font-weight: 500;
  color: {{ $prefix === 'pf' ? 'var(--pf-text)' : '#F3F4F6' }};
  transition: border-color 0.15s; box-sizing: border-box;
}

.{{ $prefix }}-input:focus {
  outline: none;
  border-color: {{ $prefix === 'pf' ? 'var(--pf-yellow)' : '#F5A800' }};
  box-shadow: 0 0 0 3px rgba(245,168,0,0.1);
}

.{{ $prefix }}-input.is-invalid {
  border-color: {{ $prefix === 'pf' ? 'var(--pf-red)' : '#F87171' }};
}

.{{ $prefix }}-error {
  font-size: 10px; font-weight: 600;
  color: {{ $prefix === 'pf' ? 'var(--pf-red)' : '#F87171' }};
  margin-top: 3px;
}

.{{ $prefix }}-form-actions {
  display: flex; gap: 10px; margin-top: 18px;
  padding-top: 14px;
  border-top: 1px solid {{ $prefix === 'pf' ? 'rgba(0,0,0,0.06)' : 'rgba(255,255,255,0.08)' }};
}

.{{ $prefix }}-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 9px 20px; border-radius: 8px; border: none; cursor: pointer;
  font-family: {{ $prefix === 'pf' ? 'var(--pf-font)' : "'Dosis', sans-serif" }};
  font-size: 12px; font-weight: 700;
  transition: all 0.15s; text-decoration: none;
}

.{{ $prefix }}-btn-primary {
  background: {{ $prefix === 'pf' ? 'var(--pf-yellow)' : '#F5A800' }};
  color: #0D0F11;
}
.{{ $prefix }}-btn-primary:hover { background: #e09800; }

.{{ $prefix }}-alert {
  padding: 10px 16px; border-radius: 8px;
  font-size: 12px; font-weight: 600; margin-bottom: 14px;
}
.{{ $prefix }}-alert-success {
  background: rgba(74,222,128,0.12);
  color: {{ $prefix === 'pf' ? 'var(--pf-green)' : '#4ADE80' }};
  border: 1px solid rgba(74,222,128,0.25);
}
.{{ $prefix }}-alert-error {
  background: rgba(248,113,113,0.12);
  color: {{ $prefix === 'pf' ? 'var(--pf-red)' : '#F87171' }};
  border: 1px solid rgba(248,113,113,0.25);
}
</style>
@endpush

@section('content')

  @if(session('success'))
    <div class="{{ $prefix }}-alert {{ $prefix }}-alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="{{ $prefix }}-alert {{ $prefix }}-alert-error">{{ session('error') }}</div>
  @endif

  <div class="{{ $prefix }}-profile-wrap">

    {{-- Profile Card --}}
    <div class="{{ $prefix }}-profile-card">
      <div class="{{ $prefix }}-profile-banner">
        <div class="{{ $prefix }}-profile-avatar">{{ strtoupper(substr($user->name, 0, 2)) }}</div>
      </div>
      <div class="{{ $prefix }}-profile-body">
        <div class="{{ $prefix }}-profile-name">{{ $user->name }}</div>
        <div class="{{ $prefix }}-profile-role">{{ $user->role->name ?? 'User' }}</div>

        <div class="{{ $prefix }}-profile-info">
          <div>
            <div class="{{ $prefix }}-info-label">Phone</div>
            <div class="{{ $prefix }}-info-value">{{ $user->phone_number ?? '—' }}</div>
          </div>
          <div>
            <div class="{{ $prefix }}-info-label">Email</div>
            <div class="{{ $prefix }}-info-value">{{ $user->email ?? '—' }}</div>
          </div>
          <div>
            <div class="{{ $prefix }}-info-label">Company</div>
            <div class="{{ $prefix }}-info-value">{{ $user->company->name ?? '—' }}</div>
          </div>
          <div>
            <div class="{{ $prefix }}-info-label">Member Since</div>
            <div class="{{ $prefix }}-info-value">{{ $user->created_at?->format('d M Y') ?? '—' }}</div>
          </div>
        </div>
      </div>
    </div>

    {{-- Edit Profile --}}
    <div class="{{ $prefix }}-edit-panel">
      <div class="{{ $prefix }}-edit-head">
        <div class="{{ $prefix }}-edit-title">Edit Profile</div>
      </div>
      <div class="{{ $prefix }}-edit-body">
        <form action="{{ route('account.profile.update') }}" method="POST">
          @csrf @method('PUT')

          <div class="{{ $prefix }}-form-row">
            <div>
              <label class="{{ $prefix }}-label">Full Name *</label>
              <input type="text" name="name" class="{{ $prefix }}-input @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
              @error('name') <div class="{{ $prefix }}-error">{{ $message }}</div> @enderror
            </div>
            <div>
              <label class="{{ $prefix }}-label">Phone Number *</label>
              <input type="text" name="phone_number" class="{{ $prefix }}-input @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $user->phone_number) }}" required>
              @error('phone_number') <div class="{{ $prefix }}-error">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="{{ $prefix }}-form-row">
            <div>
              <label class="{{ $prefix }}-label">Email</label>
              <input type="email" name="email" class="{{ $prefix }}-input @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}">
              @error('email') <div class="{{ $prefix }}-error">{{ $message }}</div> @enderror
            </div>
            <div></div>
          </div>

          <div class="{{ $prefix }}-form-actions">
            <button type="submit" class="{{ $prefix }}-btn {{ $prefix }}-btn-primary">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
              Save Changes
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
@endsection
