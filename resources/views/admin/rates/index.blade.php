@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Manage Parking Rates</h3>

    <form method="POST" action="{{ route('rates.store') }}" class="mb-4">
        @csrf
        <div class="form-row">
            <div class="col-md-4">
                <select name="zone_id" class="form-control">
                    <option value="">All Zones (Default)</option>
                    @foreach($zones as $zone)
                        <option value="{{ $zone->id }}">{{ $zone->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <input type="number" name="duration_minutes" class="form-control" placeholder="Duration (mins)" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="rate" step="0.01" class="form-control" placeholder="Rate (e.g. 500)" required>
            </div>
            <div class="col-md-2">
                <button class="btn btn-success">Add Rate</button>
            </div>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Zone</th>
                <th>Duration (mins)</th>
                <th>Rate</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rates as $rate)
                <tr>
                    <td>{{ $rate->zone->name ?? 'All Zones' }}</td>
                    <td>{{ $rate->duration_minutes }}</td>
                    <td>{{ number_format($rate->rate, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
