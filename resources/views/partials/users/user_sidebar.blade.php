{{-- ParkFlow User Sidebar --}}
<aside class="uf-sidebar" id="uf-sidebar">
  <div class="uf-sb-brand">
    <div class="uf-sb-icon"><span>P</span></div>
    <div>
      <div class="uf-sb-name">ParkFlow</div>
      <div class="uf-sb-sub">User Panel</div>
    </div>
  </div>

  <nav class="uf-sb-nav">
    <div class="uf-sb-section">Menu</div>

    <a class="uf-nav-item {{ request()->routeIs('user.dashboard') ? 'active' : '' }}" href="{{ route('user.dashboard') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><rect x="1" y="3" width="15" height="13" rx="1"/><path d="M16 8h4l3 3v5h-7V8z"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
      Parking Management
    </a>

    <a class="uf-nav-item {{ request()->routeIs('user.parkings.*') ? 'active' : '' }}" href="{{ route('user.parkings.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      Entry &amp; Exit Logs
    </a>

    <a class="uf-nav-item {{ request()->routeIs('user.vehicles.*') ? 'active' : '' }}" href="{{ route('user.vehicles.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      Exempted Vehicles
    </a>

    <hr class="uf-sb-divider">
    <div class="uf-sb-section">Finance</div>

    <a class="uf-nav-item {{ request()->routeIs('user.payments.*') ? 'active' : '' }}" href="{{ route('user.payments.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
      Payments
    </a>

    <a class="uf-nav-item {{ request()->routeIs('user.reports.*') ? 'active' : '' }}" href="{{ route('user.reports.index') }}">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      Reports
    </a>

    <hr class="uf-sb-divider">
    <div class="uf-sb-section">Account</div>

    <a class="uf-nav-item" href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('uf-logout-form').submit();">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Logout
    </a>
    <form id="uf-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
  </nav>

  <div class="uf-sb-user">
    <div class="uf-user-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}</div>
    <div>
      <div class="uf-user-name">{{ Auth::user()->name ?? 'Operator' }}</div>
      <div class="uf-user-role">{{ Auth::user()->role->name ?? 'Staff' }}</div>
    </div>
    <div class="uf-sb-user-actions">
      <button class="uf-sb-user-btn" title="Logout"
              onclick="event.preventDefault(); document.getElementById('uf-logout-form').submit();">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      </button>
    </div>
  </div>
</aside>
