@extends('layouts.user')

@section('title', 'Reports — ParkFlow')
@section('page-title', 'Reports')

@push('styles')
<style>
.uf-alert{display:flex;align-items:center;gap:10px;padding:12px 16px;border-radius:10px;font-size:13px;font-weight:600;margin-bottom:16px;animation:ufFadeIn .3s ease}
@keyframes ufFadeIn{from{opacity:0;transform:translateY(-6px)}to{opacity:1;transform:translateY(0)}}
.uf-alert-success{background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.25);color:#16A34A}
.uf-page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
.uf-page-title{font-size:22px;font-weight:800;color:var(--uf-dark);letter-spacing:.2px}
.uf-page-sub{font-size:12px;font-weight:500;color:var(--uf-muted);margin-top:2px}
.uf-tiles{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px}
.uf-tile{background:var(--uf-card);border:1px solid var(--uf-border);border-radius:14px;padding:18px;position:relative;overflow:hidden}
.uf-tile-accent{position:absolute;top:0;left:0;right:0;height:3px}
.uf-tile-icon{width:38px;height:38px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:12px}
.uf-tile-label{font-size:10px;font-weight:700;letter-spacing:1.8px;text-transform:uppercase;color:var(--uf-muted);margin-bottom:5px}
.uf-tile-value{font-size:26px;font-weight:800;color:var(--uf-dark);line-height:1}
.uf-tile-delta{display:flex;align-items:center;gap:4px;margin-top:7px;font-size:11px;font-weight:600}
.uf-delta-up{color:var(--uf-green)}.uf-delta-muted{color:var(--uf-muted)}
.uf-card{background:var(--uf-card);border:1px solid var(--uf-border);border-radius:14px;overflow:hidden;margin-bottom:18px}
.uf-card-head{display:flex;align-items:center;justify-content:space-between;padding:16px 20px;border-bottom:1px solid var(--uf-border)}
.uf-card-title{font-size:14px;font-weight:800;color:var(--uf-dark)}
.uf-card-sub{font-size:11px;font-weight:500;color:var(--uf-muted);margin-top:2px}
.uf-card-body-np{padding:0}
.uf-filter-bar{display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap;padding:16px 20px;background:#FAFBFC;border-bottom:1px solid var(--uf-border)}
.uf-filter-field{display:flex;flex-direction:column;gap:5px}
.uf-filter-field label{font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--uf-muted)}
.uf-filter-field input,.uf-filter-field select{background:#FFF;border:1.5px solid var(--uf-border);border-radius:8px;padding:9px 12px;font-family:var(--uf-font);font-size:13px;font-weight:600;color:var(--uf-dark);outline:none;transition:border-color .2s,box-shadow .2s;min-width:150px}
.uf-filter-field input:focus,.uf-filter-field select:focus{border-color:var(--uf-blue);box-shadow:0 0 0 3px rgba(58,158,212,.12)}
.uf-btn{display:inline-flex;align-items:center;justify-content:center;gap:7px;padding:10px 18px;border-radius:9px;font-family:var(--uf-font);font-size:12px;font-weight:800;letter-spacing:1px;text-transform:uppercase;border:none;cursor:pointer;transition:all .18s;white-space:nowrap}
.uf-btn-primary{background:linear-gradient(90deg,#F5A800,#FFD166);background-size:200% 100%;color:#0D0F11;box-shadow:0 3px 14px rgba(245,168,0,.28)}
.uf-btn-primary:hover{background-position:right center;box-shadow:0 5px 20px rgba(245,168,0,.4)}
.uf-btn-secondary{background:#F0F3F7;color:#6B7280;border:1px solid var(--uf-border)}
.uf-btn-secondary:hover{background:#E4E8EE}
.uf-tbl{width:100%;border-collapse:collapse;font-size:13px}
.uf-tbl thead tr{border-bottom:2px solid #EEF0F3}
.uf-tbl thead th{padding:11px 16px;text-align:left;font-size:10px;font-weight:800;letter-spacing:1.5px;text-transform:uppercase;color:var(--uf-muted);white-space:nowrap;background:#FAFBFC}
.uf-tbl tbody tr{border-bottom:1px solid #F3F4F6;transition:background .1s}
.uf-tbl tbody tr:hover{background:#F8F9FB}
.uf-tbl tbody tr:last-child{border-bottom:none}
.uf-tbl td{padding:13px 16px;font-weight:600;color:var(--uf-soft);vertical-align:middle}
.uf-tbl td:first-child{font-weight:800;color:var(--uf-dark);letter-spacing:1px}
.uf-plate-badge{display:inline-flex;align-items:center;gap:6px;background:#F0F3F7;border:1px solid #DDE2E8;border-radius:6px;padding:4px 10px;font-size:13px;font-weight:800;color:var(--uf-dark);letter-spacing:2px}
.uf-time-text{font-size:12px;font-weight:600;color:var(--uf-soft)}
.uf-pill{display:inline-flex;align-items:center;gap:5px;padding:4px 10px;border-radius:999px;font-size:10px;font-weight:800;letter-spacing:.5px}
.uf-pill-green{background:rgba(34,197,94,.1);color:#16A34A}
.uf-pill-yellow{background:rgba(245,168,0,.1);color:#D97706}
.uf-pill-blue{background:rgba(58,158,212,.1);color:#0369A1}
.uf-empty{text-align:center;padding:48px 20px;display:flex;flex-direction:column;align-items:center;gap:10px}
.uf-empty-icon{width:56px;height:56px;border-radius:14px;background:#F0F3F7;display:flex;align-items:center;justify-content:center;margin-bottom:4px}
.uf-empty-title{font-size:14px;font-weight:800;color:var(--uf-dark)}
.uf-empty-sub{font-size:12px;font-weight:500;color:var(--uf-muted);max-width:260px}
.uf-revenue-box{background:linear-gradient(135deg,#1B2235,#232C42);border-radius:12px;padding:20px 24px;display:flex;align-items:center;justify-content:space-between;margin-bottom:20px}
.uf-revenue-label{font-size:12px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:rgba(255,255,255,.5)}
.uf-revenue-value{font-size:32px;font-weight:800;color:#F5A800;letter-spacing:-.5px;margin-top:4px}
.uf-revenue-cur{font-size:14px;font-weight:700;color:rgba(255,255,255,.5)}
.uf-search-inline{display:flex;align-items:center;gap:8px;background:#F5F7FA;border:1.5px solid var(--uf-border);border-radius:8px;padding:8px 12px;width:220px;transition:border-color .2s}
.uf-search-inline:focus-within{border-color:var(--uf-blue)}
.uf-search-inline input{background:none;border:none;outline:none;font-family:var(--uf-font);font-size:13px;font-weight:600;color:var(--uf-dark);width:100%}
.uf-search-inline input::placeholder{color:#C4C9D0;font-weight:400}
.uf-grid2{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px}
.uf-chart-wrap{padding:16px 20px}
.uf-chart-wrap canvas{max-height:240px}
@media(max-width:992px){.uf-tiles{grid-template-columns:repeat(2,1fr)}.uf-filter-bar{flex-direction:column;align-items:stretch}.uf-grid2{grid-template-columns:1fr}}
@media(max-width:576px){.uf-tiles{grid-template-columns:1fr}}
</style>
@endpush

@section('content')

{{-- PAGE HEADER --}}
<div class="uf-page-header">
  <div>
    <div class="uf-page-title">Reports</div>
    <div class="uf-page-sub">Analyze parking activity and revenue data</div>
  </div>
</div>

{{-- REVENUE BOX --}}
<div class="uf-revenue-box">
  <div>
    <div class="uf-revenue-label">Total Revenue</div>
    <div class="uf-revenue-value">{{ number_format($total) }}</div>
  </div>
  <div style="text-align:right;">
    <div class="uf-revenue-cur">RWF</div>
    <div style="font-size:11px;color:rgba(255,255,255,.35);margin-top:4px;">{{ $records->count() }} parking records</div>
  </div>
</div>

{{-- STAT TILES --}}
@php
  $totalRecords  = $records->count();
  $cashRecords   = $records->where('payment_method', 'cash');
  $momoRecords   = $records->where('payment_method', 'momo');
  $cashTotal     = $cashRecords->sum('bill');
  $momoTotal     = $momoRecords->sum('bill');
  $avgBill       = $totalRecords > 0 ? $total / $totalRecords : 0;
  $uniquePlates  = $records->unique('plate_number')->count();
@endphp

<div class="uf-tiles">
  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#3A9ED4,#60C4F5);"></div>
    <div class="uf-tile-icon" style="background:rgba(58,158,212,.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3A9ED4" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    </div>
    <div class="uf-tile-label">Total Records</div>
    <div class="uf-tile-value">{{ $totalRecords }}</div>
    <div class="uf-tile-delta"><span class="uf-delta-muted">Parking sessions</span></div>
  </div>
  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#22C55E,#86EFAC);"></div>
    <div class="uf-tile-icon" style="background:rgba(34,197,94,.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#22C55E" stroke-width="2" stroke-linecap="round"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M2 10h20"/></svg>
    </div>
    <div class="uf-tile-label">Cash Revenue</div>
    <div class="uf-tile-value" style="font-size:18px;">{{ number_format($cashTotal) }} <span style="font-size:11px;color:var(--uf-muted);">RWF</span></div>
    <div class="uf-tile-delta"><span class="uf-delta-up">{{ $cashRecords->count() }} payments</span></div>
  </div>
  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#F5A800,#FFD166);"></div>
    <div class="uf-tile-icon" style="background:rgba(245,168,0,.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F5A800" stroke-width="2" stroke-linecap="round"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
    </div>
    <div class="uf-tile-label">MoMo Revenue</div>
    <div class="uf-tile-value" style="font-size:18px;">{{ number_format($momoTotal) }} <span style="font-size:11px;color:var(--uf-muted);">RWF</span></div>
    <div class="uf-tile-delta"><span class="uf-delta-muted">{{ $momoRecords->count() }} payments</span></div>
  </div>
  <div class="uf-tile">
    <div class="uf-tile-accent" style="background:linear-gradient(90deg,#8B5CF6,#A78BFA);"></div>
    <div class="uf-tile-icon" style="background:rgba(139,92,246,.1);">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8B5CF6" stroke-width="2" stroke-linecap="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
    </div>
    <div class="uf-tile-label">Unique Vehicles</div>
    <div class="uf-tile-value">{{ $uniquePlates }}</div>
    <div class="uf-tile-delta"><span class="uf-delta-muted">Distinct plates</span></div>
  </div>
</div>

{{-- CHARTS --}}
<div class="uf-grid2">
  <div class="uf-card">
    <div class="uf-card-head">
      <div class="uf-card-title">Daily Revenue (14 days)</div>
    </div>
    <div class="uf-chart-wrap"><canvas id="dailyChart"></canvas></div>
  </div>
  <div class="uf-card">
    <div class="uf-card-head">
      <div class="uf-card-title">Payment Split</div>
    </div>
    <div class="uf-chart-wrap" style="display:flex;align-items:center;justify-content:center;">
      <canvas id="paymentChart" style="max-width:220px;max-height:220px;"></canvas>
    </div>
  </div>
</div>

{{-- REPORT TABLE --}}
<div class="uf-card">
  <div class="uf-card-head">
    <div>
      <div class="uf-card-title">Parking Report</div>
      <div class="uf-card-sub">Detailed breakdown of all parking sessions</div>
    </div>
    <div class="uf-search-inline">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" id="reportSearch" placeholder="Search plate…" oninput="filterReport()">
    </div>
  </div>

  {{-- FILTER BAR --}}
  <form method="GET" class="uf-filter-bar">
    <div class="uf-filter-field">
      <label>Start Date</label>
      <input type="date" name="start_date" value="{{ request('start_date') }}">
    </div>
    <div class="uf-filter-field">
      <label>End Date</label>
      <input type="date" name="end_date" value="{{ request('end_date') }}">
    </div>
    <button type="submit" class="uf-btn uf-btn-primary">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
      Filter
    </button>
    @if(request('start_date') || request('end_date'))
    <a href="{{ route('user.reports.index') }}" class="uf-btn uf-btn-secondary">Clear</a>
    @endif
  </form>

  <div class="uf-card-body-np">
    @if($records->count() > 0)
    <div style="overflow-x:auto;">
      <table class="uf-tbl" id="reportTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Date</th>
            <th>Plate Number</th>
            <th>Zone</th>
            <th>Payment Method</th>
            <th>Bill (RWF)</th>
          </tr>
        </thead>
        <tbody>
          @foreach($records as $key => $record)
          <tr>
            <td>{{ $key + 1 }}</td>
            <td><span class="uf-time-text">{{ $record->created_at->format('d M Y, H:i') }}</span></td>
            <td><span class="uf-plate-badge">{{ $record->plate_number }}</span></td>
            <td>{{ $record->zone->name ?? 'N/A' }}</td>
            <td>
              @if($record->payment_method === 'momo')
                <span class="uf-pill uf-pill-blue">📱 MoMo</span>
              @elseif($record->payment_method === 'cash')
                <span class="uf-pill uf-pill-green">💵 Cash</span>
              @else
                <span class="uf-pill uf-pill-yellow">● Pending</span>
              @endif
            </td>
            <td style="font-weight:800;color:var(--uf-dark);">{{ number_format($record->bill) }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @else
    <div class="uf-empty">
      <div class="uf-empty-icon">
        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="1.5" stroke-linecap="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      </div>
      <div class="uf-empty-title">No report data</div>
      <div class="uf-empty-sub">Adjust the date filters to find parking records.</div>
    </div>
    @endif
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
function filterReport() {
  var term = document.getElementById('reportSearch').value.toUpperCase();
  var rows = document.querySelectorAll('#reportTable tbody tr');
  rows.forEach(function(row) {
    var plate = row.cells[2] ? row.cells[2].textContent.toUpperCase() : '';
    row.style.display = plate.indexOf(term) > -1 ? '' : 'none';
  });
}

document.addEventListener('DOMContentLoaded', function() {
    // Daily Revenue Chart
    const trendLabels = {!! json_encode($dailyTrend->pluck('day')) !!};
    const trendData = {!! json_encode($dailyTrend->pluck('revenue')) !!};

    new Chart(document.getElementById('dailyChart'), {
        type: 'line',
        data: {
            labels: trendLabels.map(d => {
                const dt = new Date(d);
                return dt.getDate() + ' ' + ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][dt.getMonth()];
            }),
            datasets: [{
                label: 'Revenue (RWF)',
                data: trendData,
                borderColor: '#F5A800',
                backgroundColor: 'rgba(245,168,0,0.08)',
                fill: true,
                tension: 0.3,
                borderWidth: 2,
                pointRadius: 3,
                pointBackgroundColor: '#F5A800'
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { font: { size: 10, weight: 600 } }, grid: { color: 'rgba(0,0,0,0.04)' } },
                x: { ticks: { font: { size: 9, weight: 600 }, maxRotation: 45 }, grid: { display: false } }
            }
        }
    });

    // Payment Split Doughnut
    new Chart(document.getElementById('paymentChart'), {
        type: 'doughnut',
        data: {
            labels: ['Cash', 'MoMo'],
            datasets: [{
                data: [{{ $cashTotal }}, {{ $momoTotal }}],
                backgroundColor: ['#22C55E', '#F5A800'],
                borderWidth: 0,
                spacing: 3
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11, weight: 700 }, padding: 16 } }
            }
        }
    });
});
</script>
@endpush
