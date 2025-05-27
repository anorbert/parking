@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Manage Parking Zones & Slots</h3>

    {{-- Add Zone --}}
    <form method="POST" action="{{ route('zones.store') }}" class="mb-4">
        @csrf
        <div class="form-row">
            <div class="col-md-3">
                <input type="text" name="name" class="form-control" placeholder="New Zone Name (e.g. Zone A)" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="capacity" class="form-control" placeholder="Capacity" required>
            </div>
            <div class="col-md-3">
                <button class="btn btn-success">Add Zone</button>
            </div>
        </div>
    </form>

    {{-- Add Slot --}}
    <form method="POST" action="{{ route('zones.slotstore') }}" class="mb-4">
        @csrf
        <div class="form-row">
            <div class="col-md-4">
                <select name="zone_id" class="form-control" required>
                    <option value="">-- Select Zone --</option>
                    @foreach($zones as $zone)
                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <input type="text" name="number" class="form-control" placeholder="Slot Number (e.g. A1)" required>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary">Add Slot</button>
            </div>
        </div>
    </form>

    {{-- Zones List --}}
    @foreach($zones as $zone)
    <div class="card mb-3">
        <div class="card-header">
            <strong>{{ $zone->name }}</strong> ({{ $zone->slots->count() }} slots)
        </div>
        <div class="card-body">
            @if($zone->slots->isEmpty())
                <p>No slots added yet.</p>
            @else
                <div class="row">
                    @foreach($zone->slots as $slot)
                        <div class="col-md-2 mb-2">
                            <span class="badge badge-{{ $slot->is_occupied ? 'danger' : 'success' }}">
                                {{ $slot->number }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endsection
