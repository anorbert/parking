<form method="POST" action="{{ route('vehicles.update', $vehicle->id) }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Plate Number</label>
        <input type="text" name="plate_number" class="form-control" value="{{ $vehicle->plate_number }}" required>
    </div>

    <div class="form-group">
        <label>Owner Name</label>
        <input type="text" name="owner_name" class="form-control" value="{{ $vehicle->owner_name }}">
    </div>

    <div class="form-group">
        <label>Owner Contact</label>
        <input type="text" name="owner_contact" class="form-control" value="{{ $vehicle->owner_contact }}" required>
    </div>

    <div class="form-group">
        <label>Billing Type</label>
        <select name="billing_type" class="form-control" required>
            <option value="Monthly" {{ $vehicle->billing_type == 'Monthly' ? 'selected' : '' }}>Monthly</option>
            <option value="Free" {{ $vehicle->billing_type == 'Free' ? 'selected' : '' }}>Free</option>
            
        </select>
    </div>

    <div class="form-group">
        <label>Vehicle Type</label>
        <select name="vehicle_type" class="form-control" required>
            <option value="Car" {{ $vehicle->vehicle_type == 'Car' ? 'selected' : '' }}>Car</option>
            <option value="Motorcycle" {{ $vehicle->vehicle_type == 'Motorcycle' ? 'selected' : '' }}>Motorcycle</option>
            <option value="Truck" {{ $vehicle->vehicle_type == 'Truck' ? 'selected' : '' }}>Truck</option>
        </select>
    </div>

    <div class="form-group">
        <label>Reason</label>
        <input type="text" name="reason" class="form-control" value="{{ $vehicle->reason }}">
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-success">Update</button>
    </div>
</form>
