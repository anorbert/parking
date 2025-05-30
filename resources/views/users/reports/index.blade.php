@extends('layouts.user')

@section('content')
<div class="x_panel">
  <div class="x_title">
    <h2>Parking Report</h2>
  </div>
  <div class="x_content">
    <form method="GET" class="row mb-4">
      <div class="col-md-3">
        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
      </div>
      <div class="col-md-3">
        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
      </div>
      <div class="col-md-3">
        <button class="btn btn-primary" type="submit">Filter</button>
      </div>
    </form>

    <table class="table table-bordered">
      <thead>
        <tr>
          <th>Date</th>
          <th>Plate No</th>
          <th>Zone</th>
          <th>Bill</th>
        </tr>
      </thead>
      <tbody>
        @foreach($records as $record)
          <tr>
            <td>{{ $record->created_at->format('Y-m-d H:i') }}</td>
            <td>{{ $record->plate_number }}</td>
            <td>{{ $record->zone->name ?? 'N/A' }}</td>
            <td>{{ number_format($record->bill) }} RWF</td>
          </tr>
        @endforeach
      </tbody>
    </table>

    <div class="text-end">
      <strong>Total Revenue:</strong> {{ number_format($total) }} RWF
    </div>
  </div>
</div>
@endsection
