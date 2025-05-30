@extends('layouts.user')

@section('content')
<div class="container-fluid">
  <div class="page-title mb-4">
    <h3>Parking Management</h3>
  </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

  {{-- Add New Car Entry --}}
  <div class="card mb-4">
    <div class="card-header">New Car Entry</div>
    <div class="card-body">
      <form method="POST" action="{{ route('parking.entry') }}">
        @csrf
        <div class="form-row">
          <div class="form-group col-md-8">
            <label for="plate_number">Plate Number</label>
            <input type="text" class="form-control" name="plate_number" required 
                   pattern="^[A-Z]{3}\d{3}[A-Z]$"
                   placeholder="e.g. RAB123Z" title="Format: RAB123Z">
          </div>
          <div class="form-group col-md-4">
            <label for="submit_button">&nbsp;</label>
            <input type="submit" class="form-control btn btn-primary" value="Validate & Enter">
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Active Parked Vehicles --}}
  <div class="card">
    <div class="card-header">Currently Parked Vehicles</div>
    <div class="card-body">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Plate</th>
            <th>Entry Time</th>
            <th>Zone</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($activeParkings as $parking)
            <tr>
              <td>{{ $parking->plate_number }}</td>
              <td>{{ \Carbon\Carbon::parse($parking->entry_time)->format('d M Y, H:i') }}</td>
              <td>{{ $parking->zone->name ?? 'N/A' }}</td>
              <td>
                <button class="btn btn-sm btn-danger" onclick="openExitModal({{ $parking->id }})">Exit & Bill</button>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center">No active parkings found.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- Exit Modal -->
<div class="modal fade" id="exitModal" tabindex="-1" aria-labelledby="exitModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" id="exitForm">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Confirm Vehicle Exit</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <div class="modal-body">
          <div class="col-md-8">
            <p><strong>Plate Number:</strong> <span id="modalPlate"></span></p>
            <p><strong>Zone:</strong> <span id="modalZone"></span></p>
            <p><strong>Entry Time:</strong> <span id="modalEntry"></span></p>
            <p><strong>Exit Time:</strong> <span id="modalExit"></span></p>
            <p><strong>Duration:</strong> <span id="modalDuration" style="color: red"></span></p>
          </div>
          <div class="col-md-4">
            <p style="font-size: 32px;"><strong>Pay:</strong> <span id="modalAmountLarge"></span> RWF</p>
          </div>
          <div class="col-md-12">
            <label for="payment_method">Payment Method</label>
            <select name="payment_method" id="payment_method" class="form-control" required onchange="togglePhoneField(this.value)">
                <option value="cash">Cash</option>
                <option value="momo">MoMo</option>
            </select>

            <div id="phone_input_group" style="display: none; margin-top: 10px;">
                <label for="phone_number">Phone Number for MoMo</label>
                <input type="tel" class="form-control" name="phone_number" id="modalPhoneNumber" 
                    pattern="^07[2,3,8,9]\d{7}$"
                    placeholder="e.g. 0781234567"
                    title="Phone must start with 07 and contain 10 digits">
            </div>
            <input type="hidden" name="amount" id="modalAmountInput">
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Confirm & Pay</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function openExitModal(parkingId) {
    fetch(`/parking/exit-info/${parkingId}`)
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('modalPlate').textContent = data.plate_number;
                document.getElementById('modalZone').textContent = data.zone_name || 'N/A';
                document.getElementById('modalEntry').textContent = data.entry_time;
                document.getElementById('modalExit').textContent = data.exit_time;
                document.getElementById('modalDuration').textContent = data.duration + ' mins';
                document.getElementById('modalAmountLarge').textContent = Number(data.amount).toLocaleString();
                document.getElementById('modalAmountInput').value = data.amount;

                document.getElementById('exitForm').action = `/parking/exit/${parkingId}`;
                const modal = new bootstrap.Modal(document.getElementById('exitModal'));
                modal.show();
            } else {
                alert(data.message || "Unable to load exit details.");
            }
        })
        .catch(() => alert("Something went wrong while fetching exit details."));
}

function togglePhoneField(value) {
        const phoneField = document.getElementById('phone_input_group');
        if (value === 'momo') {
            phoneField.style.display = 'block';
        } else {
            phoneField.style.display = 'none';
        }
    }
</script>
@endsection
