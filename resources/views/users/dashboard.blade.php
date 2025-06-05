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
      <table id="datatable" class="table table-bordered table-striped">
        <thead class="thead-dark">
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

          <!-- Error message container -->
          <div id="exitModalError" class="alert alert-danger" style="display:none;"></div>

          <div class="row">
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
          <button type="submit" id="exitSubmitBtn" class="btn btn-success">Confirm & Pay</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
            Close
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
  let pollingInterval = null;

  function showModalError(message) {
      const errorDiv = document.getElementById('exitModalError');
      errorDiv.textContent = message;
      errorDiv.style.display = 'block';
  }

  function clearModalError() {
      const errorDiv = document.getElementById('exitModalError');
      errorDiv.style.display = 'none';
      errorDiv.textContent = '';
  }

  function togglePhoneField(value) {
      const phoneField = document.getElementById('phone_input_group');
      if (value === 'momo') {
          phoneField.style.display = 'block';
      } else {
          phoneField.style.display = 'none';
      }
  }

  function openExitModal(parkingId) {
      clearModalError();

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

              // Reset form fields and errors
              document.getElementById('payment_method').value = 'cash';
              togglePhoneField('cash');
              document.getElementById('modalPhoneNumber').value = '';
              clearModalError();

              // Remove any previous MoMo status
              const existingStatus = document.getElementById('momoStatusText');
              if (existingStatus) existingStatus.remove();

              const modal = new bootstrap.Modal(document.getElementById('exitModal'));
              modal.show();
          } else {
              showModalError(data.message || "Unable to load exit details.");
          }
      })
      .catch(() => showModalError("Something went wrong while fetching exit details."));
  }

  document.getElementById('exitForm').addEventListener('submit', function(event) {
      event.preventDefault();
      clearModalError();

      const submitBtn = document.getElementById('exitSubmitBtn');
      submitBtn.disabled = true;
      submitBtn.textContent = 'Processing...';

      const form = this;
      const actionUrl = form.action;
      const formData = new FormData(form);
      const paymentMethod = formData.get('payment_method');

      fetch(actionUrl, {
          method: 'POST',
          body: formData,
      })
      .then(async response => {
          const data = await response.json();
          if (!response.ok) {
              throw new Error(data.message || 'Server error');
          }
          return data;
      })
      .then(data => {
          if(data.success) {
              if (paymentMethod === 'momo') {
                  const trxRef = data.trx_ref;
                  if (!trxRef) throw new Error("Missing transaction reference.");
                  startMoMoPolling(trxRef);
              } else {
                  const modalEl = document.getElementById('exitModal');
                  const modal = bootstrap.Modal.getInstance(modalEl);
                  modal.hide();
                  location.reload();
              }
          } else {
              showModalError(data.message || 'Payment failed.');
          }
      })
      .catch(error => {
          showModalError(error.message);
      })
      .finally(() => {
          submitBtn.disabled = false;
          submitBtn.textContent = 'Confirm & Pay';
      });
  });

  function startMoMoPolling(trxRef) {
      // Clean up any old polling
      if (pollingInterval) clearInterval(pollingInterval);

      // Remove existing status message if any
      const existingStatus = document.getElementById('momoStatusText');
      if (existingStatus) existingStatus.remove();

      const statusText = document.createElement('p');
      statusText.id = 'momoStatusText';
      statusText.textContent = 'Waiting for MoMo confirmation...';
      statusText.style.color = 'blue';
      document.querySelector('#exitModal .modal-body').appendChild(statusText);

      let pollAttempts = 0;
      const maxAttempts = 40; // 40 * 3s = 2 minutes

      pollingInterval = setInterval(() => {
          pollAttempts++;
          if (pollAttempts > maxAttempts) {
              clearInterval(pollingInterval);
              statusText.textContent = 'Payment taking too long. Please try again.';
              statusText.style.color = 'red';
              return;
          }

          fetch(`{{ url('/api/check-payment-status') }}?trx_ref=${trxRef}`)
              .then(res => res.json())
              .then(data => {
                  if (data.status === 'Completed') {
                      clearInterval(pollingInterval);
                      statusText.textContent = 'Payment completed!';
                      statusText.style.color = 'green';
                      setTimeout(() => {
                          const modalEl = document.getElementById('exitModal');
                          const modal = bootstrap.Modal.getInstance(modalEl);
                          modal.hide();
                          location.reload();
                      }, 1500);
                  } else if (data.status === 'Failed') {
                      clearInterval(pollingInterval);
                      statusText.textContent = 'Payment failed.';
                      statusText.style.color = 'red';
                      setTimeout(() => {
                          statusText.remove();
                      }, 2500);
                  }
              })
              .catch(() => {
                  statusText.textContent = 'Error checking payment. Retrying...';
                  statusText.style.color = 'orange';
              });
      }, 4000);
  }
</script>



@endsection
