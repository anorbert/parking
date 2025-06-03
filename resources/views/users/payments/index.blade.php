@extends('layouts.user')

@section('content')
<div class="x_panel">
  <div class="x_title">
    <h2>All Payments</h2>
    <div class="clearfix"></div>
  </div>

  <div class="x_content">

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('user.payments.index') }}" class="form-inline mb-4">
      <div class="form-group mr-3">
        <label for="start_date" class="mr-2">Start Date:</label>
        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
      </div>

      <div class="form-group mr-3">
        <label for="end_date" class="mr-2">End Date:</label>
        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
      </div>

      <div class="form-group mr-3">
        <label for="payment_method" class="mr-2">Payment Method:</label>
        <select name="payment_method" id="payment_method" class="form-control">
          <option value="">All</option>
          <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
          <option value="momo" {{ request('payment_method') == 'momo' ? 'selected' : '' }}>MoMo</option>
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Filter</button>
    </form>

    <hr>

    {{-- Payments Table --}}
    <table id="datatable" class="table table-striped">
      <thead>
        <tr>
          <th>ID</th>
          <th>Plate No</th>
          <th>Time Spent</th>
          <th>Amount (RWF)</th>
          <th>Method</th>
          <th>User</th>
          <th>Date</th>
        </tr>
      </thead>
      <tbody>
        @forelse($payments as $payment)
          <tr>
            <td>{{ $payment->id }}</td>
            <td>{{ $payment->plate_number ?? 'N/A' }}</td>
            <td>{{ $payment->duration ?? 'N/A' }}</td>
            <td>{{ number_format($payment->bill) }}</td>
            <td>{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
            <td>{{ $payment->user->name ?? 'N/A' }}</td>
            <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center">No payments found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>

    {{-- Total Revenue --}}
    <div class="mt-4">
      <h4>Total Revenue: 
        <strong class="text-success">
          RWF {{ number_format($payments->sum('bill')) }}
        </strong>
      </h4>
    </div>

  </div>
</div>
@endsection
