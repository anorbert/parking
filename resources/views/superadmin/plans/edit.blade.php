@extends('layouts.admin')

@section('title', 'Edit Plan — ParkFlow')
@section('page-title', 'Edit Plan')

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
.pf-input, .pf-textarea {
  width: 100%; padding: 9px 12px; border-radius: 8px;
  border: 1px solid #D0D5DD; background: #FAFBFC;
  font-family: var(--pf-font); font-size: 13px; font-weight: 500;
  color: var(--pf-text); transition: border-color 0.15s;
  box-sizing: border-box;
}
.pf-input:focus, .pf-textarea:focus { outline: none; border-color: var(--pf-yellow); box-shadow: 0 0 0 3px rgba(245,168,0,0.1); }
.pf-input.is-invalid { border-color: var(--pf-red); }
.pf-textarea { resize: vertical; min-height: 60px; }

.pf-error { font-size: 10px; font-weight: 600; color: var(--pf-red); margin-top: 3px; }

.pf-check-row { display: flex; align-items: center; gap: 8px; margin-bottom: 8px; }
.pf-check-row input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--pf-yellow); }
.pf-check-label { font-size: 12px; font-weight: 600; color: var(--pf-soft); }

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
      <div class="pf-form-title">Edit Plan: {{ $plan->name }}</div>
      <div class="pf-form-sub">Modify subscription plan details</div>
    </div>
    <div class="pf-form-body">
      <form action="{{ route('superadmin.plans.update', $plan) }}" method="POST">
        @csrf @method('PUT')

        <div class="pf-form-section">Plan Details</div>

        <div class="pf-form-row">
          <div>
            <label class="pf-label">Plan Name *</label>
            <input type="text" name="name" class="pf-input @error('name') is-invalid @enderror" value="{{ old('name', $plan->name) }}" required>
            @error('name') <div class="pf-error">{{ $message }}</div> @enderror
          </div>
          <div>
            <label class="pf-label">Price (RWF) *</label>
            <input type="number" name="price" class="pf-input @error('price') is-invalid @enderror" value="{{ old('price', $plan->price) }}" min="0" step="100" required>
            @error('price') <div class="pf-error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="pf-form-row">
          <div>
            <label class="pf-label">Duration (Days) *</label>
            <input type="number" name="duration_days" class="pf-input @error('duration_days') is-invalid @enderror" value="{{ old('duration_days', $plan->duration_days) }}" min="1" required>
            @error('duration_days') <div class="pf-error">{{ $message }}</div> @enderror
          </div>
          <div>
            <label class="pf-label">Sort Order</label>
            <input type="number" name="sort_order" class="pf-input @error('sort_order') is-invalid @enderror" value="{{ old('sort_order', $plan->sort_order) }}" min="0">
            @error('sort_order') <div class="pf-error">{{ $message }}</div> @enderror
          </div>
        </div>

        <div class="pf-form-full">
          <label class="pf-label">Description</label>
          <textarea name="description" class="pf-textarea @error('description') is-invalid @enderror">{{ old('description', $plan->description) }}</textarea>
          @error('description') <div class="pf-error">{{ $message }}</div> @enderror
        </div>

        <div class="pf-form-section">Limits</div>

        <div class="pf-form-row">
          <div>
            <label class="pf-label">Max Zones</label>
            <input type="number" name="max_zones" class="pf-input" value="{{ old('max_zones', $plan->max_zones) }}" min="0" placeholder="Leave empty for unlimited">
          </div>
          <div>
            <label class="pf-label">Max Slots</label>
            <input type="number" name="max_slots" class="pf-input" value="{{ old('max_slots', $plan->max_slots) }}" min="0" placeholder="Leave empty for unlimited">
          </div>
        </div>

        <div class="pf-form-row">
          <div>
            <label class="pf-label">Max Users</label>
            <input type="number" name="max_users" class="pf-input" value="{{ old('max_users', $plan->max_users) }}" min="0" placeholder="Leave empty for unlimited">
          </div>
          <div></div>
        </div>

        <div class="pf-form-section">Features</div>

        <div class="pf-check-row">
          <input type="checkbox" name="momo_payments" value="1" id="momo_payments" {{ old('momo_payments', $plan->momo_payments) ? 'checked' : '' }}>
          <label for="momo_payments" class="pf-check-label">Enable MoMo Payments</label>
        </div>

        <div class="pf-check-row">
          <input type="checkbox" name="reports_enabled" value="1" id="reports_enabled" {{ old('reports_enabled', $plan->reports_enabled) ? 'checked' : '' }}>
          <label for="reports_enabled" class="pf-check-label">Enable Reports</label>
        </div>

        <div class="pf-form-section">Status</div>

        <div class="pf-check-row">
          <input type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $plan->is_active) ? 'checked' : '' }}>
          <label for="is_active" class="pf-check-label">Plan is Active</label>
        </div>

        <div class="pf-form-actions">
          <button type="submit" class="pf-btn pf-btn-primary">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polyline points="20 6 9 17 4 12"/></svg>
            Update Plan
          </button>
          <a href="{{ route('superadmin.plans.index') }}" class="pf-btn pf-btn-ghost">Cancel</a>
        </div>
      </form>
    </div>
  </div>

@endsection
