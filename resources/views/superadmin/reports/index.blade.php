@extends('layouts.admin')

@section('title', 'Reports — ParkFlow')
@section('page-title', 'Platform Reports')

@push('styles')
<style>
.pf-tiles { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 18px; }
.pf-tile { background: var(--pf-card); border: 1px solid var(--pf-border); border-radius: 14px; padding: 18px 18px 14px; position: relative; overflow: hidden; }
.pf-tile-accent { position: absolute; top: 0; left: 0; right: 0; height: 2px; }
.pf-tile-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
.pf-tile-label { font-size: 10px; font-weight: 700; letter-spacing: 1.8px; text-transform: uppercase; color: var(--pf-muted); margin-bottom: 5px; }
.pf-tile-value { font-size: 24px; font-weight: 800; color: var(--pf-text); line-height: 1; }
.pf-tile-delta { display: flex; align-items: center; gap: 4px; margin-top: 8px; font-size: 11px; font-weight: 600; }
.pf-delta-text { color: var(--pf-muted); }

.pf-panel { background: var(--pf-card); border: 1px solid var(--pf-border); border-radius: 14px; overflow: hidden; margin-bottom: 18px; }
.pf-panel-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-bottom: 1px solid rgba(0,0,0,0.08); }
.pf-panel-title { font-size: 13px; font-weight: 800; letter-spacing: 0.3px; color: var(--pf-text); }
.pf-panel-body { padding: 16px 18px; }

.pf-filter-bar { display:flex; align-items:flex-end; gap:12px; flex-wrap:wrap; }
.pf-filter-bar label { font-size:11px; font-weight:700; color:var(--pf-muted); display:block; margin-bottom:4px; }
.pf-filter-bar .form-control { border-radius:8px; font-family:var(--pf-font); font-weight:600; font-size:12px; }

.pf-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
.pf-tbl thead tr { border-bottom: 1px solid rgba(0,0,0,0.08); }
.pf-tbl thead th { padding: 9px 12px; text-align: left; font-size: 10px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--pf-muted); white-space: nowrap; }
.pf-tbl tbody tr { border-bottom: 1px solid rgba(0,0,0,0.07); }
.pf-tbl tbody tr:last-child { border-bottom: none; }
.pf-tbl td { padding: 10px 12px; font-weight: 500; color: var(--pf-soft); vertical-align: middle; }
.pf-tbl td:first-child { color: var(--pf-text); font-weight: 600; }

.pf-pill { display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px; border-radius: 999px; font-size: 10px; font-weight: 700; letter-spacing: 0.5px; }
.pf-pill-green { background: rgba(74,222,128,0.12); color: var(--pf-green); }
.pf-pill-red { background: rgba(248,113,113,0.12); color: var(--pf-red); }

.pf-grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 18px; }

.pf-chart-wrap { padding: 16px 18px; }
.pf-chart-wrap canvas { max-height: 260px; }

@media (max-width: 992px) { .pf-tiles { grid-template-columns: repeat(2, 1fr); } .pf-grid2 { grid-template-columns: 1fr; } }
@media (max-width: 576px) { .pf-tiles { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')

  {{-- FILTER FORM --}}
  <div class="pf-panel" style="margin-bottom:18px;">
    <div class="pf-panel-head">
      <div class="pf-panel-title"><i class="fa fa-filter" style="color:var(--pf-blue,#3A9ED4);margin-right:6px;"></i> Filter Reports</div>
    </div>
    <div class="pf-panel-body">
      <form method="GET" action="{{ route('superadmin.reports.index') }}">
        <div class="pf-filter-bar">
          <div>
            <label>Company</label>
            <select name="company_id" class="form-control">
              <option value="">All Companies</option>
              @foreach($companies as $company)
              <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label>Start Date</label>
            <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
          </div>
          <div>
            <label>End Date</label>
            <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
          </div>
          <div>
            <label style="color:transparent;">Action</label>
            <button type="submit" class="btn" style="background:var(--pf-blue,#3A9ED4);color:#fff;font-weight:700;border-radius:8px;font-family:var(--pf-font);">
              <i class="fa fa-bar-chart"></i> Generate
            </button>
          </div>
          <div>
            <label style="color:transparent;">Reset</label>
            <a href="{{ route('superadmin.reports.index') }}" class="btn" style="background:rgba(148,163,184,.15);color:var(--pf-muted);font-weight:700;border-radius:8px;font-family:var(--pf-font);">
              <i class="fa fa-refresh"></i> Reset
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- STAT TILES --}}
  <div class="pf-tiles">
    <div class="pf-tile">
      <div class="pf-tile-accent" style="background: linear-gradient(90deg,#4ADE80,#86EFAC);"></div>
      <div class="pf-tile-icon" style="background:rgba(74,222,128,0.12);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4ADE80" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      </div>
      <div class="pf-tile-label">Total Revenue</div>
      <div class="pf-tile-value">{{ number_format($totalRevenue) }} <span style="font-size:12px;color:var(--pf-muted);font-weight:600;">RWF</span></div>
      <div class="pf-tile-delta"><span class="pf-delta-text">{{ number_format($totalParkings) }} parkings</span></div>
    </div>

    <div class="pf-tile">
      <div class="pf-tile-accent" style="background: linear-gradient(90deg,#F5A800,#FFD166);"></div>
      <div class="pf-tile-icon" style="background:rgba(245,168,0,0.12);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F5A800" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      </div>
      <div class="pf-tile-label">Today's Revenue</div>
      <div class="pf-tile-value">{{ number_format($dailyRevenue) }} <span style="font-size:12px;color:var(--pf-muted);font-weight:600;">RWF</span></div>
    </div>

    <div class="pf-tile">
      <div class="pf-tile-accent" style="background: linear-gradient(90deg,#3A9ED4,#60C4F5);"></div>
      <div class="pf-tile-icon" style="background:rgba(58,158,212,0.12);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3A9ED4" stroke-width="2"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M2 10h20"/></svg>
      </div>
      <div class="pf-tile-label">Cash / MoMo</div>
      <div class="pf-tile-value" style="font-size:16px;">{{ number_format($cashTotal) }} <span style="font-size:11px;color:var(--pf-muted);">/</span> {{ number_format($momoTotal) }}</div>
      <div class="pf-tile-delta"><span class="pf-delta-text">Cash vs MoMo (RWF)</span></div>
    </div>

    <div class="pf-tile">
      <div class="pf-tile-accent" style="background: linear-gradient(90deg,#A78BFA,#C4B5FD);"></div>
      <div class="pf-tile-icon" style="background:rgba(167,139,250,0.12);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#A78BFA" stroke-width="2"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      </div>
      <div class="pf-tile-label">Active Parked</div>
      <div class="pf-tile-value">{{ number_format($activeParked) }}</div>
    </div>
  </div>

  {{-- CHARTS ROW --}}
  <div class="pf-grid2">
    {{-- Monthly Revenue Trend --}}
    <div class="pf-panel">
      <div class="pf-panel-head">
        <div class="pf-panel-title">Monthly Revenue Trend</div>
      </div>
      <div class="pf-chart-wrap">
        <canvas id="monthlyRevenueChart"></canvas>
      </div>
    </div>

    {{-- Payment Method Breakdown --}}
    <div class="pf-panel">
      <div class="pf-panel-head">
        <div class="pf-panel-title">Payment Method Breakdown</div>
      </div>
      <div class="pf-chart-wrap" style="display:flex;align-items:center;justify-content:center;">
        <canvas id="paymentMethodChart" style="max-width:240px;max-height:240px;"></canvas>
      </div>
    </div>
  </div>

  <div class="pf-grid2">
    {{-- Revenue by Company --}}
    <div class="pf-panel">
      <div class="pf-panel-head">
        <div class="pf-panel-title">Revenue by Company</div>
      </div>
      <div style="overflow-x:auto;">
        <table class="pf-tbl">
          <thead>
            <tr>
              <th>Company</th>
              <th>Parkings</th>
              <th>Revenue</th>
            </tr>
          </thead>
          <tbody>
            @forelse($revenueByCompany as $item)
            <tr>
              <td>{{ $item->company->name ?? 'Unknown' }}</td>
              <td>{{ number_format($item->total_parkings) }}</td>
              <td style="font-weight:700;">{{ number_format($item->total_revenue) }} RWF</td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align:center;color:var(--pf-muted);">No data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    {{-- Expired Subscriptions --}}
    <div class="pf-panel">
      <div class="pf-panel-head">
        <div class="pf-panel-title">Expired Subscriptions</div>
      </div>
      <div style="overflow-x:auto;">
        <table class="pf-tbl">
          <thead>
            <tr>
              <th>Company</th>
              <th>End Date</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            @forelse($expiredSubs as $sub)
            <tr>
              <td>{{ $sub->company->name ?? '—' }}</td>
              <td>{{ $sub->end_date ? \Carbon\Carbon::parse($sub->end_date)->format('d M Y') : '—' }}</td>
              <td><span class="pf-pill pf-pill-red">Expired</span></td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align:center;color:var(--pf-muted);">No expired subscriptions</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Monthly Revenue Trend
    const monthlyLabels = {!! json_encode($monthlyRevenue->pluck('month')) !!};
    const monthlyData = {!! json_encode($monthlyRevenue->pluck('revenue')) !!};

    new Chart(document.getElementById('monthlyRevenueChart'), {
        type: 'bar',
        data: {
            labels: monthlyLabels.map(m => {
                const [y, mo] = m.split('-');
                return ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'][parseInt(mo)-1] + ' ' + y;
            }),
            datasets: [{
                label: 'Revenue (RWF)',
                data: monthlyData,
                backgroundColor: 'rgba(58,158,212,0.18)',
                borderColor: '#3A9ED4',
                borderWidth: 2,
                borderRadius: 6,
                barPercentage: 0.6
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { font: { size: 10, weight: 600 } }, grid: { color: 'rgba(0,0,0,0.04)' } },
                x: { ticks: { font: { size: 10, weight: 600 } }, grid: { display: false } }
            }
        }
    });

    // Payment Method Breakdown
    new Chart(document.getElementById('paymentMethodChart'), {
        type: 'doughnut',
        data: {
            labels: ['Cash', 'MoMo'],
            datasets: [{
                data: [{{ $cashTotal }}, {{ $momoTotal }}],
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
