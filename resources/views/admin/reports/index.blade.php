@extends('layouts.admin')

@section('title', 'Reports — ParkFlow')
@section('page-title', 'Reports')

@push('styles')
<style>
.pf-panel{background:var(--pf-card);border:1px solid var(--pf-border);border-radius:14px;overflow:hidden;margin-bottom:16px}
.pf-panel-head{padding:14px 18px;border-bottom:1px solid rgba(0,0,0,.06);font-size:14px;font-weight:800;color:var(--pf-text);display:flex;align-items:center;justify-content:space-between}
.pf-panel-body{padding:18px}
.pf-filter-bar{display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap}
.pf-filter-bar label{font-size:11px;font-weight:700;color:var(--pf-muted);display:block;margin-bottom:4px}
.pf-filter-bar .form-control{border-radius:8px;font-family:var(--pf-font);font-weight:600;font-size:12px}
.pf-stat-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:14px;margin-bottom:16px}
.pf-stat-card{background:var(--pf-card);border:1px solid var(--pf-border);border-radius:12px;padding:18px 20px;transition:border-color .2s}
.pf-stat-card:hover{border-color:var(--pf-border2)}
.pf-stat-label{font-size:11px;font-weight:700;color:var(--pf-muted);text-transform:uppercase;letter-spacing:.8px;margin-bottom:6px}
.pf-stat-value{font-size:22px;font-weight:800;color:var(--pf-text)}
.pf-stat-icon{width:36px;height:36px;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:10px;font-size:16px}
.pf-stat-icon-yellow{background:rgba(245,168,0,.12);color:var(--pf-yellow)}
.pf-stat-icon-green{background:rgba(74,222,128,.12);color:var(--pf-green)}
.pf-stat-icon-blue{background:rgba(58,158,212,.12);color:var(--pf-blue)}
.pf-stat-icon-red{background:rgba(248,113,113,.12);color:var(--pf-red)}
.pf-stat-icon-purple{background:rgba(168,85,247,.12);color:#A855F7}
.pf-grid2{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:16px}
.pf-chart-wrap{padding:16px 18px}
.pf-chart-wrap canvas{max-height:250px}
.pf-tbl{width:100%;border-collapse:collapse;font-size:12px}
.pf-tbl thead tr{border-bottom:1px solid rgba(0,0,0,.08)}
.pf-tbl thead th{padding:9px 12px;text-align:left;font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--pf-muted);white-space:nowrap}
.pf-tbl tbody tr{border-bottom:1px solid rgba(0,0,0,.06)}
.pf-tbl tbody tr:last-child{border-bottom:none}
.pf-tbl td{padding:10px 12px;font-weight:500;color:var(--pf-soft);vertical-align:middle}
.pf-tbl td:first-child{color:var(--pf-text);font-weight:700}
.pf-pill{display:inline-flex;align-items:center;gap:5px;padding:3px 9px;border-radius:999px;font-size:10px;font-weight:700;letter-spacing:.5px}
.pf-pill-green{background:rgba(74,222,128,.12);color:var(--pf-green)}
.pf-pill-blue{background:rgba(58,158,212,.12);color:var(--pf-blue)}
.pf-pill-yellow{background:rgba(245,168,0,.12);color:#D97706}
.pf-plate{display:inline-flex;align-items:center;gap:5px;background:rgba(0,0,0,.04);border:1px solid rgba(0,0,0,.08);border-radius:6px;padding:3px 9px;font-size:12px;font-weight:800;letter-spacing:1.5px}
@media(max-width:992px){.pf-stat-grid{grid-template-columns:repeat(2,1fr)}.pf-grid2{grid-template-columns:1fr}}
@media(max-width:576px){.pf-stat-grid{grid-template-columns:1fr}}
</style>
@endpush

@section('content')

{{-- Filters --}}
<div class="pf-panel">
  <div class="pf-panel-head">
    <span><i class="fa fa-filter" style="color:var(--pf-blue);margin-right:6px;"></i> Filter Reports</span>
  </div>
  <div class="pf-panel-body">
    <form method="GET" action="{{ route('admin.reports.index') }}">
      <div class="pf-filter-bar">
        <div>
          <label>Start Date</label>
          <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
        </div>
        <div>
          <label>End Date</label>
          <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
        </div>
        <div>
          <label>Payment Method</label>
          <select name="payment_method" class="form-control">
            <option value="">All Methods</option>
            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="momo" {{ request('payment_method') == 'momo' ? 'selected' : '' }}>MoMo</option>
          </select>
        </div>
        <div>
          <label style="color:transparent;">Action</label>
          <button type="submit" class="btn" style="background:var(--pf-blue);color:#fff;font-weight:700;border-radius:8px;font-family:var(--pf-font);">
            <i class="fa fa-bar-chart"></i> Generate
          </button>
        </div>
        <div>
          <label style="color:transparent;">Reset</label>
          <a href="{{ route('admin.reports.index') }}" class="btn" style="background:rgba(148,163,184,.15);color:var(--pf-muted);font-weight:700;border-radius:8px;font-family:var(--pf-font);">
            <i class="fa fa-refresh"></i> Reset
          </a>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Stats Grid --}}
<div class="pf-stat-grid">
  <div class="pf-stat-card">
    <div class="pf-stat-icon pf-stat-icon-green"><i class="fa fa-money"></i></div>
    <div class="pf-stat-label">Total Revenue</div>
    <div class="pf-stat-value">{{ number_format($totalRevenue) }} <small style="font-size:12px;color:var(--pf-muted);">RWF</small></div>
  </div>
  <div class="pf-stat-card">
    <div class="pf-stat-icon pf-stat-icon-yellow"><i class="fa fa-money"></i></div>
    <div class="pf-stat-label">Cash Payments</div>
    <div class="pf-stat-value">{{ number_format($cashPayments) }} <small style="font-size:12px;color:var(--pf-muted);">RWF</small></div>
  </div>
  <div class="pf-stat-card">
    <div class="pf-stat-icon pf-stat-icon-blue"><i class="fa fa-mobile"></i></div>
    <div class="pf-stat-label">MoMo Payments</div>
    <div class="pf-stat-value">{{ number_format($momoPayments) }} <small style="font-size:12px;color:var(--pf-muted);">RWF</small></div>
  </div>
  <div class="pf-stat-card">
    <div class="pf-stat-icon pf-stat-icon-purple"><i class="fa fa-map-marker"></i></div>
    <div class="pf-stat-label">Most Used Zone</div>
    <div class="pf-stat-value" style="font-size:16px;">{{ $mostUsedZone }}</div>
  </div>
  <div class="pf-stat-card">
    <div class="pf-stat-icon pf-stat-icon-yellow"><i class="fa fa-star"></i></div>
    <div class="pf-stat-label">Top Client (Plate)</div>
    <div class="pf-stat-value" style="font-size:16px;">{{ $topClient }}</div>
  </div>
  <div class="pf-stat-card">
    <div class="pf-stat-icon pf-stat-icon-blue"><i class="fa fa-clock-o"></i></div>
    <div class="pf-stat-label">Avg Duration</div>
    <div class="pf-stat-value" style="font-size:16px;">{{ $avgDuration ? $avgDuration . ' min' : 'N/A' }}</div>
  </div>
  <div class="pf-stat-card">
    <div class="pf-stat-icon pf-stat-icon-red"><i class="fa fa-ban"></i></div>
    <div class="pf-stat-label">Exempted Vehicles</div>
    <div class="pf-stat-value">{{ $exemptedCount }}</div>
  </div>
</div>

{{-- Charts --}}
<div class="pf-grid2">
  <div class="pf-panel">
    <div class="pf-panel-head"><span>Daily Revenue Trend (14 days)</span></div>
    <div class="pf-chart-wrap"><canvas id="dailyTrendChart"></canvas></div>
  </div>
  <div class="pf-panel">
    <div class="pf-panel-head"><span>Payment Method Split</span></div>
    <div class="pf-chart-wrap" style="display:flex;align-items:center;justify-content:center;">
      <canvas id="paymentPieChart" style="max-width:240px;max-height:240px;"></canvas>
    </div>
  </div>
</div>

{{-- Parking Records Table --}}
<div class="pf-panel">
  <div class="pf-panel-head">
    <span>Parking Records ({{ $filtered->count() }})</span>
  </div>
  <div style="overflow-x:auto;">
    <table class="pf-tbl">
      <thead>
        <tr>
          <th>#</th>
          <th>Date</th>
          <th>Plate</th>
          <th>Zone</th>
          <th>Operator</th>
          <th>Method</th>
          <th>Bill (RWF)</th>
        </tr>
      </thead>
      <tbody>
        @forelse($filtered->take(100) as $key => $p)
        <tr>
          <td>{{ $key + 1 }}</td>
          <td style="font-size:11px;font-weight:600;color:var(--pf-soft);">{{ $p->created_at->format('d M Y, H:i') }}</td>
          <td><span class="pf-plate">{{ $p->plate_number }}</span></td>
          <td>{{ $p->zone->name ?? '—' }}</td>
          <td>{{ $p->user->name ?? '—' }}</td>
          <td>
            @if($p->payment_method === 'momo')
              <span class="pf-pill pf-pill-blue">MoMo</span>
            @elseif($p->payment_method === 'cash')
              <span class="pf-pill pf-pill-green">Cash</span>
            @else
              <span class="pf-pill pf-pill-yellow">Pending</span>
            @endif
          </td>
          <td style="font-weight:800;">{{ number_format($p->bill) }}</td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:32px;color:var(--pf-muted);">No parking records found</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Daily Revenue Trend
    const trendLabels = {!! json_encode($dailyTrend->pluck('day')) !!};
    const trendData = {!! json_encode($dailyTrend->pluck('revenue')) !!};

    new Chart(document.getElementById('dailyTrendChart'), {
        type: 'line',
        data: {
            labels: trendLabels.map(d => {
                const dt = new Date(d);
                return dt.getDate() + ' ' + ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][dt.getMonth()];
            }),
            datasets: [{
                label: 'Revenue (RWF)',
                data: trendData,
                borderColor: '#3A9ED4',
                backgroundColor: 'rgba(58,158,212,0.08)',
                fill: true,
                tension: 0.3,
                borderWidth: 2,
                pointRadius: 3,
                pointBackgroundColor: '#3A9ED4'
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

    // Payment Method Pie
    new Chart(document.getElementById('paymentPieChart'), {
        type: 'doughnut',
        data: {
            labels: ['Cash', 'MoMo'],
            datasets: [{
                data: [{{ $cashPayments }}, {{ $momoPayments }}],
                backgroundColor: ['#4ADE80', '#3A9ED4'],
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
