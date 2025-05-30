@extends('layouts.user')

@section('content')
<div class="container">
    <h3>Exempted Vehicles</h3>
    {{-- VEHICLE LIST --}}
    <table id="datatable" class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Plate Number</th>
                <th>Type</th>
                <th>Owner</th>
                <th>Contact</th>
                <th>Billing</th>
                <th>Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicles as $key => $car)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $car->plate_number }}</td>
                <td>{{ $car->vehicle_type }}</td>
                <td>{{ $car->owner_name }}</td>
                <td>{{ $car->owner_contact }}</td>
                <td>{{ $car->billing_type }}</td>
                <td>{{ $car->reason }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection
