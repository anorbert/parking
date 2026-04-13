@extends('layouts.' . $layout)

@section('title', 'Settings — ParkFlow')
@section('page-title', 'Settings')

@php $prefix = $layout === 'admin' ? 'pf' : 'uf'; @endphp

@push('styles')
<style>
.{{ $prefix }}-settings-wrap { max-width: 720px; }

.{{ $prefix }}-settings-panel {
  background: {{ $prefix === 'pf' ? 'var(--pf-card)' : '#1E2226' }};
  border: 1px solid {{ $prefix === 'pf' ? 'var(--pf-border)' : 'rgba(255,255,255,0.09)' }};
  border-radius: 14px; overflow: hidden;
}

.{{ $prefix }}-settings-head {
  padding: 14px 18px; display: flex; align-items: center; gap: 10px;
  border-bottom: 1px solid {{ $prefix === 'pf' ? 'rgba(0,0,0,0.08)' : 'rgba(255,255,255,0.08)' }};
}

.{{ $prefix }}-settings-icon {
  width: 32px; height: 32px; border-radius: 8px;
  background: rgba(245,168,0,0.12);
  display: flex; align-items: center; justify-content: center;
}

.{{ $prefix }}-settings-title {
  font-size: 14px; font-weight: 800;
  color: {{ $prefix === 'pf' ? 'var(--pf-text)' : '#F3F4F6' }};
}

.{{ $prefix }}-settings-sub {
  font-size: 11px; font-weight: 600;
  color: {{ $prefix === 'pf' ? 'var(--pf-muted)' : '#6B7280' }};
}

.{{ $prefix }}-settings-body { padding: 18px; }

/* Form Styles */
.{{ $prefix }}-label {
  display: block; font-size: 11px; font-weight: 700;
  color: {{ $prefix === 'pf' ? 'var(--pf-soft)' : '#9CA3AF' }};
  margin-bottom: 4px; letter-spacing: 0.3px;
}

.{{ $prefix }}-pin-row {
  display: grid; grid-template-columns: 1fr 1fr 1fr;
  gap: 14px; margin-bottom: 14px;
}

.{{ $prefix }}-pin-group { position: relative; }

.{{ $prefix }}-pin-input {
  width: 100%; padding: 10px 40px 10px 12px; border-radius: 8px;
  border: 1px solid {{ $prefix === 'pf' ? '#D0D5DD' : 'rgba(255,255,255,0.12)' }};
  background: {{ $prefix === 'pf' ? '#FAFBFC' : 'rgba(255,255,255,0.05)' }};
  font-family: {{ $prefix === 'pf' ? 'var(--pf-font)' : "'Dosis', sans-serif" }};
  font-size: 18px; font-weight: 700; letter-spacing: 6px; text-align: center;
  color: {{ $prefix === 'pf' ? 'var(--pf-text)' : '#F3F4F6' }};
  transition: border-color 0.15s; box-sizing: border-box;
}

.{{ $prefix }}-pin-input:focus {
  outline: none;
  border-color: {{ $prefix === 'pf' ? 'var(--pf-yellow)' : '#F5A800' }};
  box-shadow: 0 0 0 3px rgba(245,168,0,0.1);
}

.{{ $prefix }}-pin-input.is-invalid {
  border-color: {{ $prefix === 'pf' ? 'var(--pf-red)' : '#F87171' }};
}

.{{ $prefix }}-pin-eye {
  position: absolute; right: 10px; top: 50%; transform: translateY(-50%);
  background: none; border: none; cursor: pointer; padding: 4px;
  color: {{ $prefix === 'pf' ? 'var(--pf-muted)' : '#6B7280' }};
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

.{{ $prefix }}-pin-hint {
  font-size: 11px; font-weight: 600;
  color: {{ $prefix === 'pf' ? 'var(--pf-muted)' : '#6B7280' }};
  margin-top: 6px;
}

@media (max-width: 640px) {
  .{{ $prefix }}-pin-row { grid-template-columns: 1fr; }
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

  <div class="{{ $prefix }}-settings-wrap">

    {{-- Change PIN --}}
    <div class="{{ $prefix }}-settings-panel">
      <div class="{{ $prefix }}-settings-head">
        <div class="{{ $prefix }}-settings-icon">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#F5A800" stroke-width="2" stroke-linecap="round">
            <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
          </svg>
        </div>
        <div>
          <div class="{{ $prefix }}-settings-title">Change PIN</div>
          <div class="{{ $prefix }}-settings-sub">Update your 4-digit access PIN</div>
        </div>
      </div>
      <div class="{{ $prefix }}-settings-body">
        <form action="{{ route('account.pin.update') }}" method="POST">
          @csrf @method('PUT')

          <div class="{{ $prefix }}-pin-row">
            <div class="{{ $prefix }}-pin-group">
              <label class="{{ $prefix }}-label">Current PIN *</label>
              <input type="password" name="current_pin" class="{{ $prefix }}-pin-input @error('current_pin') is-invalid @enderror" maxlength="4" inputmode="numeric" pattern="[0-9]{4}" required autocomplete="current-password">
              <button type="button" class="{{ $prefix }}-pin-eye" onclick="togglePin(this)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
              @error('current_pin') <div class="{{ $prefix }}-error">{{ $message }}</div> @enderror
            </div>

            <div class="{{ $prefix }}-pin-group">
              <label class="{{ $prefix }}-label">New PIN *</label>
              <input type="password" name="new_pin" class="{{ $prefix }}-pin-input @error('new_pin') is-invalid @enderror" maxlength="4" inputmode="numeric" pattern="[0-9]{4}" required autocomplete="new-password">
              <button type="button" class="{{ $prefix }}-pin-eye" onclick="togglePin(this)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
              @error('new_pin') <div class="{{ $prefix }}-error">{{ $message }}</div> @enderror
            </div>

            <div class="{{ $prefix }}-pin-group">
              <label class="{{ $prefix }}-label">Confirm New PIN *</label>
              <input type="password" name="new_pin_confirmation" class="{{ $prefix }}-pin-input" maxlength="4" inputmode="numeric" pattern="[0-9]{4}" required autocomplete="new-password">
            </div>
          </div>

          <div class="{{ $prefix }}-pin-hint">PIN must be exactly 4 digits.</div>

          <div class="{{ $prefix }}-form-actions">
            <button type="submit" class="{{ $prefix }}-btn {{ $prefix }}-btn-primary">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
              Update PIN
            </button>
          </div>
        </form>
      </div>
    </div>

  </div>
@endsection

@push('scripts')
<script>
function togglePin(btn) {
  const input = btn.previousElementSibling;
  input.type = input.type === 'password' ? 'text' : 'password';
}
</script>
@endpush
