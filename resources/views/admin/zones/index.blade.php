@extends('layouts.admin')

@section('title', 'Zones — ParkFlow')
@section('page-title', 'Zones')

@push('styles')
<style>
.pf-zone-card {
  background: var(--pf-card); border: 1px solid var(--pf-border);
  border-radius: 14px; overflow: hidden; margin-bottom: 14px;
  transition: border-color 0.2s;
}
.pf-zone-card:hover { border-color: var(--pf-border2); }
.pf-zone-header {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 18px; border-bottom: 1px solid rgba(0,0,0,0.06);
}
.pf-zone-name { font-size: 14px; font-weight: 800; color: var(--pf-text); }
.pf-zone-count { font-size: 11px; font-weight: 700; color: var(--pf-muted); }
.pf-zone-body { padding: 14px 18px; }
.pf-slot-grid { display: flex; flex-wrap: wrap; gap: 8px; }
.pf-slot-badge {
  padding: 5px 12px; border-radius: 8px; font-size: 11px; font-weight: 700;
  letter-spacing: 0.5px;
}
.pf-slot-free { background: rgba(74,222,128,0.12); color: var(--pf-green); }
.pf-slot-occupied { background: rgba(248,113,113,0.12); color: var(--pf-red); }
.pf-zone-actions { display: flex; gap: 6px; }
.pf-zone-actions form { display: inline; }
</style>
@endpush

@section('content')

{{-- Flash Messages --}}
@if(session('success'))
  <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius:10px;">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
@endif
@if(session('error'))
  <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius:10px;">
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
@endif

{{-- Add Zone Form --}}
<div class="pf-zone-card">
  <div class="pf-zone-header">
    <div class="pf-zone-name"><i class="fa fa-plus-circle" style="color:var(--pf-green);margin-right:6px;"></i> Add New Zone</div>
  </div>
  <div class="pf-zone-body">
    <form method="POST" action="{{ route('admin.zones.store') }}">
      @csrf
      <div class="row">
        <div class="col-md-4 mb-2">
          <input type="text" name="name" class="form-control" placeholder="Zone Name (e.g. Zone A)" required
                 style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
        </div>
        <div class="col-md-3 mb-2">
          <input type="number" name="capacity" class="form-control" placeholder="Capacity" required min="1"
                 style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
        </div>
        <div class="col-md-2 mb-2">
          <button type="submit" class="btn btn-block" style="background:var(--pf-green);color:#0D0F11;font-weight:700;border-radius:8px;font-family:var(--pf-font);">
            <i class="fa fa-plus"></i> Add Zone
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Add Slots Form --}}
<div class="pf-zone-card">
  <div class="pf-zone-header">
    <div class="pf-zone-name"><i class="fa fa-th-large" style="color:var(--pf-blue);margin-right:6px;"></i> Add Slots to Zone</div>
    <span style="font-size:11px;font-weight:600;color:var(--pf-muted);">Create multiple slots at once</span>
  </div>
  <div class="pf-zone-body">
    <form method="POST" action="{{ route('admin.zones.slotstore') }}">
      @csrf
      <div class="row">
        <div class="col-md-3 mb-2">
          <label style="font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--pf-muted);margin-bottom:4px;display:block;">Zone</label>
          <select name="zone_id" class="form-control" required
                  style="border-radius:8px;font-family:var(--pf-font);font-weight:600;">
            <option value="">-- Select Zone --</option>
            @foreach($zones as $zone)
              <option value="{{ $zone->id }}">{{ $zone->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3 mb-2">
          <label style="font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--pf-muted);margin-bottom:4px;display:block;">Prefix</label>
          <input type="text" name="prefix" class="form-control" placeholder="e.g. A" required maxlength="5"
                 style="border-radius:8px;font-family:var(--pf-font);font-weight:600;text-transform:uppercase;"
                 oninput="this.value=this.value.toUpperCase();updateSlotPreview()">
        </div>
        <div class="col-md-2 mb-2">
          <label style="font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--pf-muted);margin-bottom:4px;display:block;">Start #</label>
          <input type="number" name="start" class="form-control" value="1" min="1" max="999" required
                 style="border-radius:8px;font-family:var(--pf-font);font-weight:600;"
                 oninput="updateSlotPreview()">
        </div>
        <div class="col-md-2 mb-2">
          <label style="font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--pf-muted);margin-bottom:4px;display:block;">Count</label>
          <input type="number" name="count" class="form-control" value="1" min="1" max="100" required
                 style="border-radius:8px;font-family:var(--pf-font);font-weight:600;"
                 oninput="updateSlotPreview()">
        </div>
        <div class="col-md-2 mb-2">
          <label style="font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:transparent;margin-bottom:4px;display:block;">&nbsp;</label>
          <button type="submit" class="btn btn-block" style="background:var(--pf-blue);color:#fff;font-weight:700;border-radius:8px;font-family:var(--pf-font);">
            <i class="fa fa-plus"></i> Create Slots
          </button>
        </div>
      </div>
      <div id="slotPreview" style="margin-top:10px;display:none;">
        <span style="font-size:10px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:var(--pf-muted);">Preview:</span>
        <div id="slotPreviewBadges" style="display:flex;flex-wrap:wrap;gap:6px;margin-top:6px;"></div>
      </div>
    </form>
  </div>
</div>

<script>
function updateSlotPreview() {
  var prefix = document.querySelector('input[name="prefix"]').value.toUpperCase();
  var start = parseInt(document.querySelector('input[name="start"]').value) || 1;
  var count = parseInt(document.querySelector('input[name="count"]').value) || 1;
  var preview = document.getElementById('slotPreview');
  var badges = document.getElementById('slotPreviewBadges');
  if (!prefix || count < 1) { preview.style.display = 'none'; return; }
  count = Math.min(count, 100);
  preview.style.display = 'block';
  var html = '';
  var show = Math.min(count, 12);
  for (var i = 0; i < show; i++) {
    html += '<span class="pf-slot-badge pf-slot-free" style="font-size:11px;">' + prefix + (start + i) + '</span>';
  }
  if (count > 12) html += '<span style="font-size:11px;font-weight:700;color:var(--pf-muted);padding:5px;">... +' + (count - 12) + ' more</span>';
  badges.innerHTML = html;
}
</script>

{{-- Zones List --}}
@forelse($zones as $zone)
  <div class="pf-zone-card">
    <div class="pf-zone-header">
      <div>
        <span class="pf-zone-name">{{ $zone->name }}</span>
        <span class="pf-zone-count" style="margin-left:10px;">{{ $zone->slots->count() }} slots</span>
      </div>
      <div class="pf-zone-actions">
        <a href="{{ route('admin.zones.edit', $zone->id) }}" class="btn btn-sm" style="background:rgba(58,158,212,0.12);color:var(--pf-blue);font-weight:700;border-radius:6px;font-size:11px;">
          <i class="fa fa-pencil"></i> Edit
        </a>
        <form method="POST" action="{{ route('admin.zones.destroy', $zone->id) }}" onsubmit="return confirm('Delete this zone?')">
          @csrf @method('DELETE')
          <button type="submit" class="btn btn-sm" style="background:rgba(248,113,113,0.12);color:var(--pf-red);font-weight:700;border-radius:6px;font-size:11px;">
            <i class="fa fa-trash"></i> Delete
          </button>
        </form>
      </div>
    </div>
    <div class="pf-zone-body">
      @if($zone->slots->isEmpty())
        <p style="color:var(--pf-muted);font-weight:600;font-size:12px;margin:0;">No slots added yet.</p>
      @else
        <div class="pf-slot-grid">
          @foreach($zone->slots as $slot)
            <span class="pf-slot-badge {{ $slot->is_occupied ? 'pf-slot-occupied' : 'pf-slot-free' }}">
              {{ $slot->number }}
            </span>
          @endforeach
        </div>
      @endif
    </div>
  </div>
@empty
  <div class="pf-zone-card">
    <div class="pf-zone-body text-center" style="padding:30px;">
      <p style="color:var(--pf-muted);font-weight:700;font-size:13px;margin:0;">No zones created yet. Add your first zone above.</p>
    </div>
  </div>
@endforelse

@endsection
