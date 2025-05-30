@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Entry & Exit Logs</h2>
    <p>View all parking logs including entry and exit times, payment methods, and statuses.</p>

    {{-- Add New Car Entry --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    


    <table id="datatable" class="table table-striped">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Plate</th>
                <th>Entry Time</th>
                <th>Exit Time</th>
                <th>Payment Mode</th>
                <th>Payment Status</th>                
                <th>Parking Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($parkingLogs as $key=>$log)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $log->plate_number }}</td>
                    <td>{{ $log->entry_time }}</td>
                    <td>{{ $log->exit_time ?? 'N/A' }}</td>
                    <td>{{ $log->payment_method }}</td>
                    <td>{{ $log->bill }}</td>
                    <td>{{ $log->status }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
