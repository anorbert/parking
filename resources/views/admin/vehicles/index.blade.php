@extends('layouts.admin')

@section('content')
<div class="container">
    <h3>Manage Exempted Vehicles</h3>

    {{-- CREATE VEHICLE FORM --}}
    <form method="POST" action="{{ route('vehicles.store') }}" class="mb-4">
        @csrf
        <div class="form-row">
            <div class="col-md-12 d-flex gap-2 mb-2">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="plate_number" placeholder="Plate Number" required>
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="owner_name" placeholder="Owner Name">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="owner_contact" placeholder="Owner Contact" required>
                </div>
                
            </div>
            <div class="col-md-12 d-flex gap-2 mb-2">
                <div class="col-md-3">
                    <select name="billing_type" class="form-control" required>
                        <option value="">Select Billing Type</option>
                        <option value="Monthly">Monthly</option>
                        <option value="Free">Free</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control" name="expired_at" placeholder="Select Expiration Time">
                </div>
                {{-- <div class="col-md-3">
                    <select name="vehicle_type" class="form-control" required>
                        <option value="">Select Vehicle Type</option>
                        <option value="Car">Car</option>
                        <option value="Motorcycle">Motorcycle</option>
                        <option value="Truck">Truck</option>
                    </select>
                </div> --}}
                <div class="col-md-3">
                    <input type="text" class="form-control" name="reason" placeholder="Reason (e.g. Staff, Gov)">
                </div>
                <div class="col-md-3">
                    <button class="btn btn-success">Add Vehicle</button>
                </div>
            </div>
        </div>
    </form>

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
                <th>Expiration Date</th>
                <th>Reason</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($vehicles as $key => $car)
            {{-- Check if the expired_at is greater than the current date --}}
            @if($car->expired_at && \Carbon\Carbon::parse($car->expired_at)->isPast())
                <tr class="table-danger">
            @else            
            <tr>
            @endif
                <td>{{ $key + 1 }}</td>
                <td>{{ $car->plate_number }}</td>
                <td>{{ $car->vehicle_type }}</td>
                <td>{{ $car->owner_name }}</td>
                <td>{{ $car->owner_contact }}</td>
                <td>{{ $car->billing_type }}</td>
                <td>
                    @if($car->expired_at)
                        {{-- Check if the time expired and unique the time--}}
                        @if(\Carbon\Carbon::parse($car->expired_at)->isPast())
                            <span class="text-danger">Expired</span> (
                        {{-- Format the date --}}
                        {{ \Carbon\Carbon::parse($car->expired_at)->format('d M Y') }})
                        @else
                            <span class="text-success">{{ \Carbon\Carbon::parse($car->expired_at)->format('d M Y') }}</span>
                        @endif
                    @else
                        N/A
                    @endif
                </td>
                <td>{{ $car->reason }}</td>
                <td>
                    <a href="{{ route('vehicles.edit', $car->id) }}"
                       class="btn btn-primary btn-sm open-edit-modal"                       
                            data-toggle="modal" data-target="#editModal">
                       Edit
                    </a>

                    <form method="POST" action="{{ route('vehicles.destroy', $car->id) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this vehicle?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- EDIT MODAL --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Vehicle</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span>&times;</span>
        </button>
      </div>
      <div class="modal-body" id="editModalBody">
        <div class="text-center py-3">
            <i class="fa fa-spinner fa-spin"></i> Loading...
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
$(document).on('click', '.open-edit-modal', function (e) {
    e.preventDefault();
    const url = $(this).attr('href');
    $('#editModalBody').html('<div class="text-center py-3"><i class="fa fa-spinner fa-spin"></i> Loading...</div>');
    $('#editModal').modal('show');

    $.get(url, function(data) {
        $('#editModalBody').html(data);
    });
});
</script>
@endsection
