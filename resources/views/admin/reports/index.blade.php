@extends('layouts.admin')

@section('content')
<div class="x_panel">
  <div class="x_title">
    <h2>Parking System Reports</h2>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">

    {{-- Filter Form --}}
    <form method="GET" class="row mb-4">
      <div class="col-md-3">
        <label>Start Date</label>
        <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
      </div>
      <div class="col-md-3">
        <label>End Date</label>
        <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
      </div>
      <div class="col-md-3">
        <label>Payment Method</label>
        <select name="payment_method" class="form-control">
          <option value="">All</option>
          <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
          <option value="momo" {{ request('payment_method') == 'momo' ? 'selected' : '' }}>MoMo</option>
        </select>
      </div>
      <div class="col-md-3 d-flex align-items-end">
        <button type="submit" class="btn btn-primary">Filter</button>
      </div>
    </form>

    {{-- Reports Table --}}
    <table class="table table-bordered table-striped">
      <thead class="thead-dark">
        <tr>
          <th>Report Metric</th>
          <th>Value</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Total Revenue</td>
          <td><strong>{{ number_format($totalRevenue) }} RWF</strong></td>
        </tr>
        <tr>
          <td>Most Used Zone</td>
          <td>{{ $mostUsedZone['name'] ?? 'N/A' }} ({{ $mostUsedZone['count'] ?? 0 }} visits)</td>
        </tr>
        <tr>
          <td>Top Client</td>
          <td>{{ $topClient['name'] ?? 'N/A' }} ({{ $topClient['count'] ?? 0 }} parkings)</td>
        </tr>
        <tr>
          <td>Average Parking Duration</td>
          <td>{{ $avgDuration }} minutes</td>
        </tr>
        <tr>
          <td>Cash Payments</td>
          <td>{{ $cashPayments }}</td>
        </tr>
        <tr>
          <td>MoMo Payments</td>
          <td>{{ $momoPayments }}</td>
        </tr>
        <tr>
          <td>Exempted Vehicles (Currently Valid)</td>
          <td>{{ $exemptedCount }}</td>
        </tr>
      </tbody>
    </table>

  </div>
</div>
@endsection
