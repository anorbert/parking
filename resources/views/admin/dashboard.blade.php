@extends('layouts.admin')

@section('title', 'Dashboard — ParkFlow')
@section('page-title', 'Dashboard')

@push('styles')
<style>
/* ═══════════════════════════════════════
   PARKFLOW DASHBOARD STYLES
   ═══════════════════════════════════════ */

/* ── STAT TILES ── */
.pf-tiles { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 18px; }

.pf-tile {
  background: var(--pf-card);
  border: 1px solid var(--pf-border);
  border-radius: 14px; padding: 18px 18px 14px;
  position: relative; overflow: hidden;
  transition: border-color 0.2s;
}
.pf-tile:hover { border-color: var(--pf-border2); }

.pf-tile-accent {
  position: absolute; top: 0; left: 0; right: 0; height: 2px;
}

.pf-tile-icon {
  width: 38px; height: 38px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 12px;
}

.pf-tile-label { font-size: 10px; font-weight: 700; letter-spacing: 1.8px; text-transform: uppercase; color: var(--pf-muted); margin-bottom: 5px; }

.pf-tile-value { font-size: 28px; font-weight: 800; color: var(--pf-text); line-height: 1; letter-spacing: -0.5px; }

.pf-tile-delta {
  display: flex; align-items: center; gap: 4px;
  margin-top: 8px; font-size: 11px; font-weight: 600;
}

.pf-delta-up   { color: var(--pf-green); }
.pf-delta-down { color: var(--pf-red); }
.pf-delta-text { color: var(--pf-muted); }

/* ── GRID LAYOUTS ── */
.pf-grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 18px; }
.pf-grid3 { display: grid; grid-template-columns: 2fr 1fr; gap: 14px; margin-bottom: 14px; }

/* ── PANEL ── */
.pf-panel {
  background: var(--pf-card);
  border: 1px solid var(--pf-border);
  border-radius: 14px; overflow: hidden;
}

.pf-panel-head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 14px 18px; border-bottom: 1px solid rgba(0,0,0,0.08);
}

.pf-panel-title { font-size: 13px; font-weight: 800; letter-spacing: 0.3px; color: var(--pf-text); }
.pf-panel-sub   { font-size: 11px; font-weight: 500; color: var(--pf-muted); margin-top: 1px; }

.pf-panel-badge {
  font-size: 10px; font-weight: 700; letter-spacing: 1px;
  padding: 4px 10px; border-radius: 999px;
}

.pf-panel-body { padding: 16px 18px; }

/* ── CHART ── */
.pf-chart-wrap { position: relative; }

/* ── TABLE ── */
.pf-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }

.pf-tbl thead tr { border-bottom: 1px solid rgba(0,0,0,0.08); }
.pf-tbl thead th { padding: 9px 12px; text-align: left; font-size: 10px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--pf-muted); white-space: nowrap; }

.pf-tbl tbody tr { border-bottom: 1px solid rgba(0,0,0,0.07); transition: background 0.1s; }
.pf-tbl tbody tr:hover { background: rgba(0,0,0,0.02); }
.pf-tbl tbody tr:last-child { border-bottom: none; }

.pf-tbl td { padding: 10px 12px; font-weight: 500; color: var(--pf-soft); vertical-align: middle; }
.pf-tbl td:first-child { color: var(--pf-text); font-weight: 600; }
.pf-tbl td strong { font-weight: 700; color: var(--pf-text); }

/* ── PILLS ── */
.pf-pill {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 3px 9px; border-radius: 999px; font-size: 10px; font-weight: 700; letter-spacing: 0.5px;
}
.pf-pill-green  { background: rgba(74,222,128,0.12); color: var(--pf-green); }
.pf-pill-yellow { background: rgba(245,168,0,0.12); color: var(--pf-yellow); }
.pf-pill-blue   { background: rgba(58,158,212,0.12); color: var(--pf-blue2); }
.pf-pill-red    { background: rgba(248,113,113,0.12); color: var(--pf-red); }

/* ── PROGRESS LIST ── */
.pf-prog-list { display: flex; flex-direction: column; gap: 14px; }

.pf-prog-top  { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
.pf-prog-label { font-size: 12px; font-weight: 700; color: var(--pf-text); }
.pf-prog-val   { font-size: 12px; font-weight: 700; color: var(--pf-soft); }

.pf-prog-bar {
  height: 5px; border-radius: 3px;
  background: rgba(0,0,0,0.06); overflow: hidden;
}

.pf-prog-fill { height: 100%; border-radius: 3px; transition: width 1s ease; }

/* ── ACTIVITY FEED ── */
.pf-activity { display: flex; flex-direction: column; gap: 0; }

.pf-act-item {
  display: flex; align-items: flex-start; gap: 12px;
  padding: 11px 0;
  border-bottom: 1px solid rgba(0,0,0,0.07);
}
.pf-act-item:last-child { border-bottom: none; }

.pf-act-dot {
  width: 8px; height: 8px; border-radius: 50%;
  margin-top: 4px; flex-shrink: 0;
}

.pf-act-msg  { font-size: 12px; font-weight: 600; color: var(--pf-text); line-height: 1.4; }
.pf-act-time { font-size: 10px; font-weight: 500; color: var(--pf-muted); margin-top: 2px; }

/* ── RESPONSIVE ── */
@media (max-width: 992px) {
  .pf-tiles { grid-template-columns: repeat(2, 1fr); }
  .pf-grid2, .pf-grid3 { grid-template-columns: 1fr; }
}
@media (max-width: 576px) {
  .pf-tiles { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')

  {{-- STAT TILES --}}
  <div class="pf-tiles">

    <div class="pf-tile">
      <div class="pf-tile-accent" style="background: linear-gradient(90deg,#3A9ED4,#60C4F5);"></div>
      <div class="pf-tile-icon" style="background:rgba(58,158,212,0.12);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3A9ED4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      </div>
      <div class="pf-tile-label">Total Slots</div>
      <div class="pf-tile-value">{{ number_format($totalSlots) }}</div>
      <div class="pf-tile-delta"><span class="pf-delta-text">All parking zones</span></div>
    </div>

    <div class="pf-tile">
      <div class="pf-tile-accent" style="background: linear-gradient(90deg,#F87171,#FCA5A5);"></div>
      <div class="pf-tile-icon" style="background:rgba(248,113,113,0.12);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F87171" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13" rx="1"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/><path d="M16 8h4l3 3v5h-7V8z"/></svg>
      </div>
      <div class="pf-tile-label">Occupied Slots</div>
      <div class="pf-tile-value">{{ number_format($occupiedSlots) }}</div>
      <div class="pf-tile-delta">
        @if($totalSlots > 0)
          <span class="{{ ($occupiedSlots / $totalSlots) >= 0.8 ? 'pf-delta-down' : 'pf-delta-up' }}">{{ round(($occupiedSlots / $totalSlots) * 100) }}%</span>
        @endif
        <span class="pf-delta-text">occupancy rate</span>
      </div>
    </div>

    <div class="pf-tile">
      <div class="pf-tile-accent" style="background: linear-gradient(90deg,#F5A800,#FFD166);"></div>
      <div class="pf-tile-icon" style="background:rgba(245,168,0,0.12);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F5A800" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      </div>
      <div class="pf-tile-label">Daily Revenue</div>
      <div class="pf-tile-value" style="font-size:22px;">{{ number_format($totalRevenue) }} <span style="font-size:12px;color:var(--pf-muted);font-weight:600;">RWF</span></div>
      <div class="pf-tile-delta"><span class="pf-delta-text">MoMo: {{ number_format($momo) }} | Cash: {{ number_format($cash) }}</span></div>
    </div>

    <div class="pf-tile">
      <div class="pf-tile-accent" style="background: linear-gradient(90deg,#4ADE80,#86EFAC);"></div>
      <div class="pf-tile-icon" style="background:rgba(74,222,128,0.12);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4ADE80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      </div>
      <div class="pf-tile-label">Active Tickets</div>
      <div class="pf-tile-value">{{ number_format($activeTickets) }}</div>
      <div class="pf-tile-delta"><span class="pf-delta-text">Currently active</span></div>
    </div>

  </div>

  {{-- CHARTS ROW --}}
  <div class="pf-grid3">

    {{-- Revenue Trend --}}
    <div class="pf-panel">
      <div class="pf-panel-head">
        <div>
          <div class="pf-panel-title">Revenue Trend</div>
          <div class="pf-panel-sub">Monthly RWF collected</div>
        </div>
        <span class="pf-panel-badge pf-pill pf-pill-green">Live</span>
      </div>
      <div class="pf-panel-body" style="padding-top:12px;">
        <div class="pf-chart-wrap" style="height:160px;">
          <canvas id="revenueChart"></canvas>
        </div>
      </div>
    </div>

    {{-- Slot Occupancy Donut --}}
    <div class="pf-panel">
      <div class="pf-panel-head">
        <div>
          <div class="pf-panel-title">Slot Occupancy</div>
          <div class="pf-panel-sub">Current status</div>
        </div>
      </div>
      <div class="pf-panel-body" style="display:flex;flex-direction:column;align-items:center;gap:10px;padding-top:10px;">
        <div class="pf-chart-wrap" style="height:120px;width:120px;">
          <canvas id="donutChart"></canvas>
        </div>
        <div style="display:flex;gap:18px;">
          <div style="text-align:center;">
            <div style="font-size:16px;font-weight:800;color:var(--pf-red);">{{ $occupiedSlots }}</div>
            <div style="font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--pf-muted);">Occupied</div>
          </div>
          <div style="text-align:center;">
            <div style="font-size:16px;font-weight:800;color:var(--pf-green);">{{ $totalSlots - $occupiedSlots }}</div>
            <div style="font-size:9px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--pf-muted);">Available</div>
          </div>
        </div>
      </div>
    </div>

  </div>

  {{-- TABLE + ZONE CAPACITY + ACTIVITY --}}
  <div class="pf-grid2">

    {{-- Quick Reports Table --}}
    <div class="pf-panel">
      <div class="pf-panel-head">
        <div>
          <div class="pf-panel-title">Quick Reports &mdash; Today</div>
          <div class="pf-panel-sub">Real-time operational snapshot</div>
        </div>
        <span class="pf-panel-badge pf-pill pf-pill-yellow">Today</span>
      </div>
      <div class="pf-panel-body" style="padding:0;">
        <table class="pf-tbl">
          <thead>
            <tr>
              <th>Report</th>
              <th>Value</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Today's Revenue</td>
              <td><strong>{{ number_format($todaysRevenue) }} RWF</strong></td>
              <td><span class="pf-pill pf-pill-green">On Track</span></td>
            </tr>
            <tr>
              <td>Transactions</td>
              <td><strong>{{ number_format($todaysTransactions) }}</strong></td>
              <td><span class="pf-pill pf-pill-blue">Normal</span></td>
            </tr>
            <tr>
              <td>MoMo Revenue</td>
              <td><strong>{{ number_format($momo) }} RWF</strong></td>
              <td><span class="pf-pill pf-pill-green">{{ $totalRevenue > 0 ? round(($momo / $totalRevenue) * 100) : 0 }}%</span></td>
            </tr>
            <tr>
              <td>Cash Revenue</td>
              <td><strong>{{ number_format($cash) }} RWF</strong></td>
              <td><span class="pf-pill pf-pill-yellow">{{ $totalRevenue > 0 ? round(($cash / $totalRevenue) * 100) : 0 }}%</span></td>
            </tr>
            <tr>
              <td>Avg. Duration</td>
              <td><strong>{{ $avgDuration ? number_format($avgDuration, 1) : '0' }} min</strong></td>
              <td><span class="pf-pill pf-pill-blue">Normal</span></td>
            </tr>
            <tr>
              <td>Exempted Vehicles</td>
              <td><strong>{{ number_format($exemptedCount) }}</strong></td>
              <td><span class="pf-pill pf-pill-red">Active</span></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    {{-- Zone Capacity + Activity --}}
    <div style="display:flex;flex-direction:column;gap:14px;">

      {{-- Zone Progress --}}
      <div class="pf-panel">
        <div class="pf-panel-head">
          <div>
            <div class="pf-panel-title">Zone Capacity</div>
            <div class="pf-panel-sub">Occupancy by area</div>
          </div>
        </div>
        <div class="pf-panel-body">
          <div class="pf-prog-list">
            @foreach($zoneNames as $index => $zoneName)
              @php
                $zone = \App\Models\Zone::where('name', $zoneName)->withCount('slots')->first();
                $occupied = $occupancyCounts[$index] ?? 0;
                $total = $zone ? $zone->slots_count : 0;
                $percent = $total > 0 ? round(($occupied / $total) * 100) : 0;

                if ($percent >= 90) {
                    $gradient = 'linear-gradient(90deg,#F87171,#FCA5A5)';
                } elseif ($percent >= 60) {
                    $gradient = 'linear-gradient(90deg,#F5A800,#FFD166)';
                } else {
                    $gradient = 'linear-gradient(90deg,#4ADE80,#86EFAC)';
                }
              @endphp
              <div class="pf-prog-item">
                <div class="pf-prog-top">
                  <span class="pf-prog-label">{{ $zoneName }}</span>
                  <span class="pf-prog-val">{{ $occupied }}/{{ $total }}</span>
                </div>
                <div class="pf-prog-bar">
                  <div class="pf-prog-fill" style="width:{{ $percent }}%;background:{{ $gradient }};"></div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Activity Feed --}}
      <div class="pf-panel">
        <div class="pf-panel-head">
          <div class="pf-panel-title">Recent Activity</div>
        </div>
        <div class="pf-panel-body" style="padding:8px 16px;">
          <div class="pf-activity">
            @if($mostUsedZone && $mostUsedZone->zone)
            <div class="pf-act-item">
              <div class="pf-act-dot" style="background:var(--pf-blue2);box-shadow:0 0 5px rgba(96,196,245,0.5);"></div>
              <div>
                <div class="pf-act-msg">Most used zone: {{ $mostUsedZone->zone->name }} &mdash; {{ $mostUsedZone->count }} entries this week</div>
                <div class="pf-act-time">This week</div>
              </div>
            </div>
            @endif
            <div class="pf-act-item">
              <div class="pf-act-dot" style="background:var(--pf-green);box-shadow:0 0 5px rgba(74,222,128,0.5);"></div>
              <div>
                <div class="pf-act-msg">{{ number_format($todaysTransactions) }} transactions processed today</div>
                <div class="pf-act-time">Today</div>
              </div>
            </div>
            <div class="pf-act-item">
              <div class="pf-act-dot" style="background:var(--pf-yellow);box-shadow:0 0 5px rgba(245,168,0,0.5);"></div>
              <div>
                <div class="pf-act-msg">MoMo payments: {{ number_format($momo) }} RWF collected</div>
                <div class="pf-act-time">Today</div>
              </div>
            </div>
            <div class="pf-act-item">
              <div class="pf-act-dot" style="background:var(--pf-red);box-shadow:0 0 5px rgba(248,113,113,0.5);"></div>
              <div>
                <div class="pf-act-msg">{{ number_format($exemptedCount) }} exempted vehicles active</div>
                <div class="pf-act-time">Current</div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>

@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
  // Chart defaults
  Chart.defaults.color = '#5C636E';
  Chart.defaults.font.family = "'Dosis', sans-serif";
  Chart.defaults.font.weight = '600';

  // Revenue line chart
  new Chart(document.getElementById('revenueChart'), {
    type: 'line',
    data: {
      labels: {!! json_encode($months) !!},
      datasets: [{
        label: 'Revenue (RWF)',
        data: {!! json_encode($revenues) !!},
        borderColor: '#F5A800',
        backgroundColor: 'rgba(245,168,0,0.08)',
        borderWidth: 2, fill: true, tension: 0.4,
        pointBackgroundColor: '#F5A800', pointRadius: 3, pointHoverRadius: 5
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false,
      plugins: {
        legend: { display: true, position: 'top', labels: { boxWidth: 10, padding: 16, font: { size: 11, weight: '700' } } }
      },
      scales: {
        x: { grid: { color: 'rgba(0,0,0,0.06)' }, ticks: { font: { size: 10 } } },
        y: { grid: { color: 'rgba(0,0,0,0.06)' }, ticks: { font: { size: 10 }, callback: function(v) { return (v/1000)+'K'; } } }
      }
    }
  });

  // Donut
  new Chart(document.getElementById('donutChart'), {
    type: 'doughnut',
    data: {
      labels: ['Occupied','Available'],
      datasets: [{
        data: [{{ $occupiedSlots }}, {{ $totalSlots - $occupiedSlots }}],
        backgroundColor: ['#F87171','#4ADE80'],
        borderColor: '#FFFFFF',
        borderWidth: 3,
        hoverOffset: 4
      }]
    },
    options: {
      responsive: true, maintainAspectRatio: false, cutout: '72%',
      plugins: { legend: { display: false } }
    }
  });
</script>
@endpush
