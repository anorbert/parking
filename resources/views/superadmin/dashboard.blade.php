@extends('layouts.admin')

@section('title', 'Super Admin Dashboard — ParkFlow')
@section('page-title', 'Super Admin Dashboard')

@push('styles')
<style>
.pf-tiles { display: grid; grid-template-columns: repeat(4, 1fr); gap: 14px; margin-bottom: 18px; }
.pf-tile { background: var(--pf-card); border: 1px solid var(--pf-border); border-radius: 14px; padding: 18px 18px 14px; position: relative; overflow: hidden; transition: border-color 0.2s; }
.pf-tile:hover { border-color: var(--pf-border2); }
.pf-tile-accent { position: absolute; top: 0; left: 0; right: 0; height: 2px; }
.pf-tile-icon { width: 38px; height: 38px; border-radius: 10px; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; }
.pf-tile-label { font-size: 10px; font-weight: 700; letter-spacing: 1.8px; text-transform: uppercase; color: var(--pf-muted); margin-bottom: 5px; }
.pf-tile-value { font-size: 28px; font-weight: 800; color: var(--pf-text); line-height: 1; letter-spacing: -0.5px; }
.pf-tile-delta { display: flex; align-items: center; gap: 4px; margin-top: 8px; font-size: 11px; font-weight: 600; }
.pf-delta-up { color: var(--pf-green); }
.pf-delta-down { color: var(--pf-red); }
.pf-delta-text { color: var(--pf-muted); }

.pf-grid2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 18px; }
.pf-grid3 { display: grid; grid-template-columns: 2fr 1fr; gap: 14px; margin-bottom: 14px; }

.pf-panel { background: var(--pf-card); border: 1px solid var(--pf-border); border-radius: 14px; overflow: hidden; }
.pf-panel-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px; border-bottom: 1px solid rgba(0,0,0,0.08); }
.pf-panel-title { font-size: 13px; font-weight: 800; letter-spacing: 0.3px; color: var(--pf-text); }
.pf-panel-sub { font-size: 11px; font-weight: 500; color: var(--pf-muted); margin-top: 1px; }
.pf-panel-badge { font-size: 10px; font-weight: 700; letter-spacing: 1px; padding: 4px 10px; border-radius: 999px; }
.pf-panel-body { padding: 16px 18px; }

.pf-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
.pf-tbl thead tr { border-bottom: 1px solid rgba(0,0,0,0.08); }
.pf-tbl thead th { padding: 9px 12px; text-align: left; font-size: 10px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase; color: var(--pf-muted); white-space: nowrap; }
.pf-tbl tbody tr { border-bottom: 1px solid rgba(0,0,0,0.07); transition: background 0.1s; }
.pf-tbl tbody tr:hover { background: rgba(0,0,0,0.02); }
.pf-tbl tbody tr:last-child { border-bottom: none; }
.pf-tbl td { padding: 10px 12px; font-weight: 500; color: var(--pf-soft); vertical-align: middle; }
.pf-tbl td:first-child { color: var(--pf-text); font-weight: 600; }

.pf-pill { display: inline-flex; align-items: center; gap: 5px; padding: 3px 9px; border-radius: 999px; font-size: 10px; font-weight: 700; letter-spacing: 0.5px; }
.pf-pill-green { background: rgba(74,222,128,0.12); color: var(--pf-green); }
.pf-pill-yellow { background: rgba(245,168,0,0.12); color: var(--pf-yellow); }
.pf-pill-red { background: rgba(248,113,113,0.12); color: var(--pf-red); }
.pf-pill-blue { background: rgba(58,158,212,0.12); color: var(--pf-blue2); }

.pf-chart-wrap { position: relative; }

.pf-prog-list { display: flex; flex-direction: column; gap: 14px; }
.pf-prog-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; }
.pf-prog-label { font-size: 12px; font-weight: 700; color: var(--pf-text); }
.pf-prog-val { font-size: 12px; font-weight: 700; color: var(--pf-soft); }
.pf-prog-bar { height: 5px; border-radius: 3px; background: rgba(0,0,0,0.06); overflow: hidden; }
.pf-prog-fill { height: 100%; border-radius: 3px; transition: width 1s ease; }

@media (max-width: 992px) { .pf-tiles { grid-template-columns: repeat(2, 1fr); } .pf-grid2, .pf-grid3 { grid-template-columns: 1fr; } }
@media (max-width: 576px) { .pf-tiles { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')

  {{-- STAT TILES --}}
  <div class="pf-tiles">
    <div class="pf-tile">
      <div class="pf-tile-accent" style="background: linear-gradient(90deg,#3A9ED4,#60C4F5);"></div>
      <div class="pf-tile-icon" style="background:rgba(58,158,212,0.12);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#3A9ED4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      </div>
      <div class="pf-tile-label">Total Companies</div>
      <div class="pf-tile-value">{{ number_format($totalCompanies) }}</div>
      <div class="pf-tile-delta">
        <span class="pf-delta-up">{{ $activeCompanies }}</span>
        <span class="pf-delta-text">active</span>
      </div>
    </div>

    <div class="pf-tile">
      <div class="pf-tile-accent" style="background: linear-gradient(90deg,#4ADE80,#86EFAC);"></div>
      <div class="pf-tile-icon" style="background:rgba(74,222,128,0.12);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#4ADE80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      </div>
      <div class="pf-tile-label">Active Subscriptions</div>
      <div class="pf-tile-value">{{ number_format($activeSubscriptions) }}</div>
      <div class="pf-tile-delta">
        <span class="pf-delta-down">{{ $expiredSubscriptions }}</span>
        <span class="pf-delta-text">expired</span>
      </div>
    </div>

    <div class="pf-tile">
      <div class="pf-tile-accent" style="background: linear-gradient(90deg,#F5A800,#FFD166);"></div>
      <div class="pf-tile-icon" style="background:rgba(245,168,0,0.12);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#F5A800" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      </div>
      <div class="pf-tile-label">Total Revenue</div>
      <div class="pf-tile-value" style="font-size:22px;">{{ number_format($totalRevenue) }} <span style="font-size:12px;color:var(--pf-muted);font-weight:600;">RWF</span></div>
      <div class="pf-tile-delta"><span class="pf-delta-text">All companies combined</span></div>
    </div>

    <div class="pf-tile">
      <div class="pf-tile-accent" style="background: linear-gradient(90deg,#A78BFA,#C4B5FD);"></div>
      <div class="pf-tile-icon" style="background:rgba(167,139,250,0.12);">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#A78BFA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      </div>
      <div class="pf-tile-label">Total Users</div>
      <div class="pf-tile-value">{{ number_format($totalUsers) }}</div>
      <div class="pf-tile-delta"><span class="pf-delta-text">All company staff</span></div>
    </div>
  </div>

  {{-- CHARTS ROW --}}
  <div class="pf-grid3">
    {{-- Subscription Revenue Trend --}}
    <div class="pf-panel">
      <div class="pf-panel-head">
        <div>
          <div class="pf-panel-title">Subscription Revenue</div>
          <div class="pf-panel-sub">Last 6 months</div>
        </div>
        <span class="pf-panel-badge" style="background:rgba(245,168,0,0.1);color:var(--pf-yellow);">{{ number_format($subscriptionRevenue) }} RWF</span>
      </div>
      <div class="pf-panel-body">
        <div class="pf-chart-wrap" style="height:200px;">
          <canvas id="pf-sub-chart"></canvas>
        </div>
      </div>
    </div>

    {{-- Revenue by Company --}}
    <div class="pf-panel">
      <div class="pf-panel-head">
        <div>
          <div class="pf-panel-title">Top Companies</div>
          <div class="pf-panel-sub">By revenue</div>
        </div>
      </div>
      <div class="pf-panel-body" style="padding: 12px 18px;">
        <div class="pf-prog-list">
          @php $maxRev = $revenueByCompany->max('total_revenue') ?: 1; @endphp
          @forelse($revenueByCompany->take(5) as $c)
          <div>
            <div class="pf-prog-top">
              <span class="pf-prog-label">{{ $c->name }}</span>
              <span class="pf-prog-val">{{ number_format($c->total_revenue) }} RWF</span>
            </div>
            <div class="pf-prog-bar">
              <div class="pf-prog-fill" style="width:{{ $maxRev > 0 ? round(($c->total_revenue / $maxRev) * 100) : 0 }}%;background:var(--pf-yellow);"></div>
            </div>
          </div>
          @empty
          <p style="font-size:12px;color:var(--pf-muted);">No revenue data yet</p>
          @endforelse
        </div>
      </div>
    </div>
  </div>

  {{-- RECENT COMPANIES --}}
  <div class="pf-panel">
    <div class="pf-panel-head">
      <div>
        <div class="pf-panel-title">Recent Companies</div>
        <div class="pf-panel-sub">Recently registered</div>
      </div>
      <a href="{{ route('superadmin.companies.index') }}" style="font-size:11px;font-weight:700;color:var(--pf-blue);text-decoration:none;">View All →</a>
    </div>
    <div class="pf-panel-body" style="padding: 0;">
      <table class="pf-tbl">
        <thead>
          <tr>
            <th>Company</th>
            <th>TIN</th>
            <th>Email</th>
            <th>Subscription</th>
            <th>Status</th>
            <th>Registered</th>
          </tr>
        </thead>
        <tbody>
          @forelse($recentCompanies as $company)
          <tr>
            <td>{{ $company->name }}</td>
            <td>{{ $company->tin ?? '—' }}</td>
            <td>{{ $company->email ?? '—' }}</td>
            <td>
              @if($company->activeSubscription)
                <span class="pf-pill pf-pill-green">Active</span>
              @else
                <span class="pf-pill pf-pill-red">Expired</span>
              @endif
            </td>
            <td>
              <span class="pf-pill {{ $company->status === 'Active' ? 'pf-pill-green' : 'pf-pill-red' }}">{{ $company->status }}</span>
            </td>
            <td>{{ $company->created_at->format('d M Y') }}</td>
          </tr>
          @empty
          <tr><td colspan="6" style="text-align:center;color:var(--pf-muted);">No companies yet</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function() {
  var ctx = document.getElementById('pf-sub-chart');
  if (!ctx) return;
  new Chart(ctx.getContext('2d'), {
    type: 'bar',
    data: {
      labels: @json($months),
      datasets: [{
        label: 'Subscription Revenue',
        data: @json($subRevenues),
        backgroundColor: 'rgba(245,168,0,0.18)',
        borderColor: '#F5A800',
        borderWidth: 2,
        borderRadius: 6,
        barPercentage: 0.6,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { font: { family: 'Dosis', size: 10, weight: 600 }, color: '#9CA3AF' } },
        x: { grid: { display: false }, ticks: { font: { family: 'Dosis', size: 10, weight: 600 }, color: '#9CA3AF' } }
      }
    }
  });
})();
</script>
@endpush
