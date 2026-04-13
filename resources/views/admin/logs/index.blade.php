@extends('layouts.admin')

@section('title', 'Entry & Exit Logs — ParkFlow')
@section('page-title', 'Entry & Exit Logs')

@push('styles')
<style>
.pf-panel{background:var(--pf-card);border:1px solid var(--pf-border);border-radius:14px;overflow:hidden;margin-bottom:16px}
.pf-panel-head{padding:14px 18px;border-bottom:1px solid rgba(0,0,0,.06);font-size:14px;font-weight:800;color:var(--pf-text);display:flex;align-items:center;justify-content:space-between}
.pf-panel-body{padding:18px}
.pf-tbl{width:100%;font-size:12px;font-weight:600;border-collapse:collapse}
.pf-tbl thead th{color:var(--pf-muted);font-weight:700;padding:10px 14px;border-bottom:2px solid var(--pf-border);text-transform:uppercase;font-size:10px;letter-spacing:1px}
.pf-tbl tbody td{padding:10px 14px;border-bottom:1px solid var(--pf-border);color:var(--pf-text);vertical-align:middle}
.pf-tbl tbody tr:hover{background:rgba(245,168,0,.04)}
.pf-pill{padding:3px 10px;border-radius:6px;font-size:10px;font-weight:700;letter-spacing:.5px;display:inline-block}
.pf-pill-green{background:rgba(74,222,128,.12);color:var(--pf-green)}
.pf-pill-red{background:rgba(248,113,113,.12);color:var(--pf-red)}
.pf-pill-yellow{background:rgba(245,168,0,.12);color:var(--pf-yellow)}
.pf-pill-blue{background:rgba(58,158,212,.12);color:var(--pf-blue)}
</style>
@endpush

@section('content')

@if(session('success'))
  <div class="alert alert-success alert-dismissible" style="border-radius:10px;">{{ session('success') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>
@endif

<div class="pf-panel">
  <div class="pf-panel-head">
    <span><i class="fa fa-exchange" style="color:var(--pf-yellow);margin-right:6px;"></i> Parking Logs</span>
    <span class="pf-pill pf-pill-blue">{{ $parkingLogs->count() }} records</span>
  </div>
  <div class="pf-panel-body" style="padding:0;">
    <div class="table-responsive">
      <table class="pf-tbl" id="logsTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Plate Number</th>
            <th>Entry Time</th>
            <th>Exit Time</th>
            <th>Payment Method</th>
            <th>Bill (RWF)</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach($parkingLogs as $i => $log)
          <tr>
            <td>{{ $i + 1 }}</td>
            <td style="font-weight:800;">{{ $log->plate_number }}</td>
            <td>{{ \Carbon\Carbon::parse($log->entry_time)->format('d M Y H:i') }}</td>
            <td>
              @if($log->exit_time)
                {{ \Carbon\Carbon::parse($log->exit_time)->format('d M Y H:i') }}
              @else
                <span class="pf-pill pf-pill-yellow">Still Parked</span>
              @endif
            </td>
            <td>
              @if($log->payment_method)
                <span class="pf-pill {{ $log->payment_method == 'cash' ? 'pf-pill-green' : 'pf-pill-blue' }}">
                  {{ ucfirst($log->payment_method) }}
                </span>
              @else
                —
              @endif
            </td>
            <td>{{ $log->bill ? number_format($log->bill) : '—' }}</td>
            <td>
              @if($log->exit_time)
                <span class="pf-pill pf-pill-green">Completed</span>
              @else
                <span class="pf-pill pf-pill-yellow">Active</span>
              @endif
            </td>
            <td>
              @if(!$log->exit_time)
                <form method="POST" action="{{ route('admin.logs.destroy', $log->id) }}" style="display:inline;" onsubmit="return confirm('Remove this active log?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="btn btn-sm" style="background:rgba(248,113,113,.12);color:var(--pf-red);font-weight:700;border-radius:6px;font-size:11px;font-family:var(--pf-font);">
                    <i class="fa fa-trash"></i>
                  </button>
                </form>
              @else
                —
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
$(function(){
  $('#logsTable').DataTable({
    pageLength: 20,
    order: [[2,'desc']],
    language: { search: "", searchPlaceholder: "Search logs..." }
  });
});
</script>
@endpush
