{{-- ParkFlow Admin Topbar --}}
<header class="pf-topbar">
  <div class="pf-topbar-left">
    <button class="pf-menu-btn" id="pf-menu-toggle">
      <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
    </button>
    <div class="pf-breadcrumb">
      <span class="pf-bc-root">ParkFlow</span>
      <span class="pf-bc-sep">/</span>
      <span class="pf-bc-cur">@yield('page-title', 'Dashboard')</span>
    </div>
  </div>

  <div class="pf-topbar-right">
    <div class="pf-tb-search">
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" placeholder="Search vehicles, tickets&hellip;" />
    </div>

    <div class="pf-tb-badge"><div class="pf-tb-dot"></div>System Online</div>
    <div class="pf-tb-time" id="pf-clock">--:--:--</div>
    <div class="pf-tb-divider"></div>

    <div style="position:relative;">
      <button class="pf-tb-icon-btn" title="Notifications" id="pf-notif-toggle">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        <div class="pf-tb-notif-dot" id="pf-notif-dot" style="display:none;"></div>
      </button>
      <div class="pf-notif-dropdown" id="pf-notif-dropdown">
        <div class="pf-notif-header">
          <span style="font-size:13px;font-weight:800;color:var(--pf-text);">Notifications</span>
          <button id="pf-notif-read-all" style="background:none;border:none;font-size:11px;font-weight:700;color:var(--pf-blue,#3A9ED4);cursor:pointer;">Mark all read</button>
        </div>
        <div class="pf-notif-list" id="pf-notif-list">
          <div style="padding:24px;text-align:center;color:var(--pf-muted);font-size:12px;">Loading...</div>
        </div>
      </div>
    </div>

    <div class="pf-tb-divider"></div>

    <div class="pf-tb-user" id="pf-user-toggle">
      <div class="pf-tb-avatar">{{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}</div>
      <div>
        <div class="pf-tb-uname">{{ Auth::user()->name ?? 'Admin' }}</div>
        <div class="pf-tb-urole">{{ Auth::user()->role->name ?? 'Admin' }}</div>
      </div>
      <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="#9CA3AF" stroke-width="2.5" stroke-linecap="round" style="margin-left:2px;"><polyline points="6 9 12 15 18 9"/></svg>

      <div class="pf-tb-dropdown" id="pf-user-dropdown">
        <a href="{{ route('account.profile') }}"><i class="fa fa-user" style="width:14px;"></i> My Profile</a>
        @if(!Auth::user()->isSuperAdmin())
        <a href="{{ route('admin.company.profile') }}"><i class="fa fa-building" style="width:14px;"></i> My Company</a>
        @endif
        <a href="{{ route('account.settings') }}"><i class="fa fa-cog" style="width:14px;"></i> Settings</a>
        <a href="{{ route('logout') }}" class="pf-danger"
           onclick="event.preventDefault(); document.getElementById('pf-logout-topbar').submit();">
          <i class="fa fa-sign-out" style="width:14px;"></i> Log Out
        </a>
      </div>
    </div>
    <form id="pf-logout-topbar" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
  </div>
</header>
