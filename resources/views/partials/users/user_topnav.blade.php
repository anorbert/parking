{{-- ParkFlow User Topbar --}}
<header class="uf-topbar">
  <div class="uf-topbar-left">
    <button class="uf-menu-btn" id="uf-menu-toggle">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    </button>
    <div class="uf-breadcrumb">
      <span class="uf-bc-root">ParkFlow</span>
      <span class="uf-bc-sep">/</span>
      <span class="uf-bc-cur">@yield('page-title', 'Parking Management')</span>
    </div>
  </div>

  <div class="uf-topbar-right">
    <div class="uf-tb-search">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" placeholder="Search plate number&hellip;" />
    </div>

    <div class="uf-tb-badge"><div class="uf-tb-dot"></div>System Online</div>
    <div class="uf-tb-time" id="uf-clock">--:--:--</div>
    <div class="uf-tb-divider"></div>

    <button class="uf-tb-icon-btn" title="Notifications">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      <div class="uf-tb-notif-dot"></div>
    </button>

    <div class="uf-tb-divider"></div>

    <div class="uf-tb-user" id="uf-user-toggle">
      <div class="uf-tb-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 2)) }}</div>
      <div>
        <div class="uf-tb-uname">{{ Auth::user()->name ?? 'Operator' }}</div>
        <div class="uf-tb-urole">{{ Auth::user()->role->name ?? 'Staff' }}</div>
      </div>
      <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2.5" stroke-linecap="round" style="margin-left:2px;"><polyline points="6 9 12 15 18 9"/></svg>

      <div class="uf-tb-dropdown" id="uf-user-dropdown">
        <a href="{{ route('logout') }}" class="uf-danger"
           onclick="event.preventDefault(); document.getElementById('uf-logout-topbar').submit();">
          <i class="fa fa-sign-out" style="width:14px;"></i> Log Out
        </a>
      </div>
    </div>
    <form id="uf-logout-topbar" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
  </div>
</header>
