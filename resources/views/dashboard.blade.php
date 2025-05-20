@extends('layouts.admin') {{-- Assuming you're using a layout file --}}

@section('content')
  <div class="">
    <div class="page-title">
      <div class="title_left">
        <h3>Parking Management Dashboard</h3>
      </div>
    </div>

    <div class="clearfix"></div>

    {{-- Top Tiles --}}
    <div class="row tile_count">
      <div class="col-md-2 col-sm-4 tile_stats_count">
        <span class="count_top"><i class="fa fa-car"></i> Total Parking Slots</span>
        <div class="count">{{ $totalSlots ?? '120' }}</div>
        <span class="count_bottom"><i class="green">+5 </i> New This Week</span>
      </div>
      <div class="col-md-2 col-sm-4 tile_stats_count">
        <span class="count_top"><i class="fa fa-car-side"></i> Occupied Slots</span>
        <div class="count">{{ $occupiedSlots ?? '87' }}</div>
        <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i> 5% </i> Today</span>
      </div>
      <div class="col-md-2 col-sm-4 tile_stats_count">
        <span class="count_top"><i class="fa fa-money-bill-wave"></i> Total Revenue</span>
        <div class="count green">RWF {{ number_format($totalRevenue ?? 1200000) }}</div>
        <span class="count_bottom"><i class="green"><i class="fa fa-sort-asc"></i>15% </i> This Month</span>
      </div>
      <div class="col-md-2 col-sm-4 tile_stats_count">
        <span class="count_top"><i class="fa fa-users"></i> Registered Users</span>
        <div class="count">{{ $registeredUsers ?? '350' }}</div>
        <span class="count_bottom"><i class="green">+10 </i> This Week</span>
      </div>
      <div class="col-md-2 col-sm-4 tile_stats_count">
        <span class="count_top"><i class="fa fa-ticket-alt"></i> Active Tickets</span>
        <div class="count">{{ $activeTickets ?? '175' }}</div>
        <span class="count_bottom"><i class="red"><i class="fa fa-sort-desc"></i>2% </i> vs. Last Week</span>
      </div>
    </div>

    <div class="row">
      {{-- Occupancy by Zone --}}
      <div class="col-md-6 col-sm-6">
        <div class="x_panel">
          <div class="x_title">
            <h2>Occupancy by Zone</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">

          </div>
        </div>
      </div>

      {{-- Revenue Chart Placeholder --}}
      <div class="col-md-6 col-sm-6">
        <div class="x_panel">
          <div class="x_title">
            <h2>Revenue Trends</h2>
            <div class="clearfix"></div>
          </div>
          <div class="x_content">
            <canvas id="revenueChart" height="150"></canvas>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection
