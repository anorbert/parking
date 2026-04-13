<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'ParkFlow — Admin')</title>
<link href="{{ asset('img/pfr_logo2.png') }}" rel="icon">
<link href="https://fonts.googleapis.com/css2?family=Dosis:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

<!-- Bootstrap -->
<link href="{{ asset('gentelella/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
<!-- Font Awesome -->
<link href="{{ asset('gentelella/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
<!-- NProgress -->
<link href="{{ asset('gentelella/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
<!-- Gentelella compat (x_panel) -->
<link href="{{ asset('gentelella/build/css/custom.min.css') }}" rel="stylesheet">
<!-- Datatables -->
<link href="{{ asset('gentelella/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('gentelella/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('gentelella/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css') }}" rel="stylesheet">

@stack('styles')

<style>
/* ═══════════════════════════════════════
   PARKFLOW ADMIN THEME
   ═══════════════════════════════════════ */
:root {
  --pf-yellow:  #F5A800;
  --pf-yellow2: #FFD166;
  --pf-blue:    #3A9ED4;
  --pf-blue2:   #60C4F5;
  --pf-green:   #4ADE80;
  --pf-red:     #F87171;
  --pf-purple:  #A78BFA;
  --pf-bg:      #3A4048;
  --pf-sidebar-bg: #1B2235;
  --pf-sidebar-dark: #141929;
  --pf-card:    #FFFFFF;
  --pf-border:  rgba(0,0,0,0.08);
  --pf-border2: rgba(0,0,0,0.14);
  --pf-muted:   #7A8290;
  --pf-soft:    #4A5260;
  --pf-text:    #1A2030;
  --pf-font:    'Dosis', sans-serif;
}

body, html {
  height: 100% !important;
  font-family: var(--pf-font) !important;
  background: var(--pf-bg) !important;
  color: var(--pf-text);
  overflow: hidden !important;
  margin: 0; padding: 0;
}

/* ── SHELL LAYOUT ── */
.pf-shell { display: flex; height: 100vh; }
.pf-main  { flex: 1; display: flex; flex-direction: column; overflow: hidden; }

.pf-content {
  flex: 1; overflow-y: auto; padding: 20px 22px;
  background: #F5F7FA;
}
.pf-content::-webkit-scrollbar { width: 4px; }
.pf-content::-webkit-scrollbar-thumb { background: #B0B8C4; border-radius: 2px; }

/* ── SIDEBAR ── */
.pf-sidebar {
  width: 240px; flex-shrink: 0;
  background: var(--pf-sidebar-bg);
  display: flex; flex-direction: column;
  overflow: hidden;
  box-shadow: 4px 0 20px rgba(0,0,0,0.25);
  z-index: 100;
  transition: margin-left 0.3s ease;
}
.pf-sidebar.collapsed { margin-left: -240px; }

.pf-sb-brand {
  display: flex; align-items: center; gap: 12px;
  padding: 0 20px; height: 64px;
  background: var(--pf-sidebar-dark);
  border-bottom: 1px solid rgba(255,255,255,0.06);
  flex-shrink: 0;
}
.pf-sb-icon {
  width: 36px; height: 36px; border-radius: 9px; flex-shrink: 0;
  background: linear-gradient(135deg, var(--pf-yellow), var(--pf-yellow2));
  display: flex; align-items: center; justify-content: center;
}
.pf-sb-icon span { font-weight: 800; font-size: 19px; color: #0D0F11; line-height: 1; }
.pf-sb-name { font-size: 15px; font-weight: 800; letter-spacing: 2.5px; text-transform: uppercase; color: #FFFFFF; }
.pf-sb-sub  { font-size: 9px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.3); margin-top: 2px; }

.pf-sb-section {
  padding: 20px 20px 6px;
  font-size: 9px; font-weight: 800;
  letter-spacing: 2.5px; text-transform: uppercase;
  color: rgba(255,255,255,0.25);
}

.pf-sb-nav { flex: 1; overflow-y: auto; padding: 8px 12px 12px; }
.pf-sb-nav::-webkit-scrollbar { width: 2px; }
.pf-sb-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }

.pf-sb-link {
  display: flex; align-items: center; gap: 11px;
  padding: 10px 12px; border-radius: 8px; margin-bottom: 1px;
  font-size: 13px; font-weight: 600;
  color: rgba(255,255,255,0.45);
  cursor: pointer; transition: all 0.18s; text-decoration: none;
  position: relative;
}
.pf-sb-link svg { flex-shrink: 0; width: 15px; height: 15px; }
.pf-sb-link:hover, .pf-sb-link:focus {
  background: rgba(255,255,255,0.07);
  color: rgba(255,255,255,0.85);
  text-decoration: none;
}
.pf-sb-link.active {
  background: linear-gradient(90deg, rgba(245,168,0,0.18) 0%, rgba(245,168,0,0.06) 100%);
  color: var(--pf-yellow);
}
.pf-sb-link.active::before {
  content: '';
  position: absolute; left: 0; top: 20%; bottom: 20%;
  width: 3px; border-radius: 0 3px 3px 0;
  background: var(--pf-yellow);
}

.pf-sb-tag {
  margin-left: auto;
  font-size: 9px; font-weight: 800;
  padding: 2px 7px; border-radius: 999px;
  background: rgba(245,168,0,0.18);
  color: var(--pf-yellow); letter-spacing: 0.5px;
}

/* Sidebar submenus */
.pf-sb-submenu { list-style: none; padding: 0; margin: 0 0 4px 26px; }
.pf-sb-submenu a {
  display: block; padding: 6px 12px; border-radius: 6px;
  font-size: 12px; font-weight: 500;
  color: rgba(255,255,255,0.35);
  text-decoration: none; transition: all 0.15s;
}
.pf-sb-submenu a:hover, .pf-sb-submenu a.active {
  color: rgba(255,255,255,0.8);
  background: rgba(255,255,255,0.05);
}

.pf-sb-toggle { margin-left: auto; transition: transform 0.2s; }
.pf-sb-toggle.open { transform: rotate(180deg); }

/* Sidebar user */
.pf-sb-user {
  padding: 14px 16px;
  background: var(--pf-sidebar-dark);
  border-top: 1px solid rgba(255,255,255,0.06);
  display: flex; align-items: center; gap: 10px;
}
.pf-user-avatar {
  width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
  background: linear-gradient(135deg, var(--pf-blue), var(--pf-blue2));
  display: flex; align-items: center; justify-content: center;
  font-weight: 800; font-size: 13px; color: #0D0F11;
}
.pf-user-name { font-size: 13px; font-weight: 700; color: #FFFFFF; }
.pf-user-role { font-size: 10px; font-weight: 500; color: rgba(255,255,255,0.3); letter-spacing: 0.5px; }

.pf-sb-user-actions { margin-left: auto; display: flex; gap: 6px; }
.pf-sb-user-btn {
  width: 26px; height: 26px; border-radius: 6px;
  background: rgba(255,255,255,0.07);
  border: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  color: rgba(255,255,255,0.4); transition: all 0.15s;
}
.pf-sb-user-btn:hover { background: rgba(255,255,255,0.12); color: rgba(255,255,255,0.8); }

/* ── TOPBAR ── */
.pf-topbar {
  height: 64px; flex-shrink: 0;
  background: #FFFFFF;
  border-bottom: 1px solid #E8ECF0;
  display: flex; align-items: center;
  justify-content: space-between;
  padding: 0 24px;
  box-shadow: 0 1px 8px rgba(0,0,0,0.06);
}
.pf-topbar-left  { display: flex; align-items: center; gap: 16px; }
.pf-topbar-right { display: flex; align-items: center; gap: 10px; }

.pf-menu-btn {
  width: 34px; height: 34px; border-radius: 8px;
  background: #F0F3F7; border: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  color: #6B7280; transition: background 0.15s;
}
.pf-menu-btn:hover { background: #E4E8EE; }

.pf-breadcrumb { display: flex; align-items: center; gap: 6px; }
.pf-bc-root { font-size: 12px; font-weight: 600; color: #9CA3AF; }
.pf-bc-sep  { font-size: 12px; color: #D1D5DB; }
.pf-bc-cur  { font-size: 13px; font-weight: 800; color: #1A2030; letter-spacing: 0.2px; }

.pf-tb-search {
  display: flex; align-items: center; gap: 8px;
  background: #F5F7FA; border: 1px solid #E2E6EA;
  border-radius: 8px; padding: 7px 12px; width: 200px;
}
.pf-tb-search input {
  background: none; border: none; outline: none;
  font-family: var(--pf-font); font-size: 12px; font-weight: 500;
  color: #1A2030; width: 100%;
}
.pf-tb-search input::placeholder { color: #9CA3AF; }
.pf-tb-search svg { color: #9CA3AF; flex-shrink: 0; }

.pf-tb-icon-btn {
  width: 36px; height: 36px; border-radius: 9px;
  background: #F5F7FA; border: 1px solid #E2E6EA;
  cursor: pointer; display: flex; align-items: center; justify-content: center;
  color: #6B7280; position: relative; transition: all 0.15s;
}
.pf-tb-icon-btn:hover { background: #EAECF0; border-color: #D0D5DD; }

.pf-tb-notif-dot {
  position: absolute; top: 7px; right: 7px;
  width: 7px; height: 7px; border-radius: 50%;
  background: var(--pf-yellow);
  border: 1.5px solid #FFFFFF;
}
.pf-tb-divider { width: 1px; height: 28px; background: #E2E6EA; margin: 0 4px; }

.pf-tb-badge {
  display: flex; align-items: center; gap: 6px;
  background: rgba(74,222,128,0.1);
  border: 1px solid rgba(74,222,128,0.25);
  border-radius: 999px; padding: 5px 12px;
  font-size: 11px; font-weight: 700; color: #22C55E;
  letter-spacing: 0.5px;
}
.pf-tb-dot {
  width: 6px; height: 6px; border-radius: 50%;
  background: var(--pf-green);
  box-shadow: 0 0 6px rgba(74,222,128,0.6);
  animation: pf-pulse 2s infinite;
}
@keyframes pf-pulse { 0%,100%{opacity:1;} 50%{opacity:0.4;} }

.pf-tb-time { font-size: 12px; font-weight: 700; color: #4B5563; font-variant-numeric: tabular-nums; }

.pf-tb-user {
  display: flex; align-items: center; gap: 9px;
  padding: 5px 10px 5px 5px;
  border-radius: 10px; cursor: pointer;
  border: 1px solid #E2E6EA; background: #F9FAFB;
  transition: all 0.15s; position: relative;
}
.pf-tb-user:hover { background: #F0F3F7; border-color: #D0D5DD; }
.pf-tb-avatar {
  width: 30px; height: 30px; border-radius: 50%;
  background: linear-gradient(135deg, var(--pf-yellow), var(--pf-yellow2));
  display: flex; align-items: center; justify-content: center;
  font-weight: 800; font-size: 12px; color: #0D0F11;
}
.pf-tb-uname { font-size: 12px; font-weight: 700; color: #1A2030; }
.pf-tb-urole { font-size: 10px; font-weight: 500; color: #9CA3AF; }

/* Topbar user dropdown */
.pf-tb-dropdown {
  display: none; position: absolute; top: 100%; right: 0;
  margin-top: 6px; background: #FFFFFF; border: 1px solid #E2E6EA;
  border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,0.12);
  min-width: 160px; z-index: 200; overflow: hidden;
}
.pf-tb-dropdown.open { display: block; }
.pf-tb-dropdown a {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 16px; font-size: 12px; font-weight: 600;
  color: #4A5260; text-decoration: none; transition: all 0.15s;
}
.pf-tb-dropdown a:hover { background: #F5F7FA; color: #1A2030; }
.pf-tb-dropdown a.pf-danger { color: var(--pf-red); }
.pf-tb-dropdown a.pf-danger:hover { background: rgba(248,113,113,0.08); }

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
  .pf-sidebar { position: fixed; left: 0; top: 0; bottom: 0; margin-left: -240px; z-index: 200; }
  .pf-sidebar.mobile-open { margin-left: 0; }
  .pf-tb-search, .pf-tb-badge { display: none; }
}
</style>
</head>
<body>

<div class="pf-shell">
  @include('partials.admin.sidebar')

  <div class="pf-main">
    @include('partials.admin.topnav')

    <div class="pf-content">
      @yield('content')
    </div>

    @include('partials.admin.footer')
  </div>
</div>

<!-- jQuery -->
<script src="{{ asset('gentelella/vendors/jquery/dist/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('gentelella/vendors/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<!-- NProgress -->
<script src="{{ asset('gentelella/vendors/nprogress/nprogress.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('gentelella/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('gentelella/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('gentelella/vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('gentelella/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('gentelella/vendors/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('gentelella/vendors/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset('gentelella/vendors/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('gentelella/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js') }}"></script>
<!-- Moment -->
<script src="{{ asset('gentelella/vendors/moment/min/moment.min.js') }}"></script>

<!-- ParkFlow Shell Scripts -->
<script>
(function() {
  // Clock
  var clock = document.getElementById('pf-clock');
  if (clock) {
    function tick() { clock.textContent = new Date().toLocaleTimeString('en-GB', { hour12: false }); }
    tick(); setInterval(tick, 1000);
  }

  // Sidebar toggle
  var menuBtn = document.getElementById('pf-menu-toggle');
  var sidebar = document.getElementById('pf-sidebar');
  if (menuBtn && sidebar) {
    menuBtn.addEventListener('click', function() {
      if (window.innerWidth <= 768) {
        sidebar.classList.toggle('mobile-open');
      } else {
        sidebar.classList.toggle('collapsed');
      }
    });
  }

  // Sidebar submenu toggles
  document.querySelectorAll('.pf-sb-toggle-btn').forEach(function(btn) {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      var group = this.closest('.pf-sb-group');
      var sub = group ? group.querySelector('.pf-sb-submenu') : null;
      var arrow = this.querySelector('.pf-sb-toggle');
      if (sub) {
        sub.style.display = sub.style.display === 'none' ? 'block' : 'none';
        if (arrow) arrow.classList.toggle('open');
      }
    });
  });

  // User dropdown
  var userToggle = document.getElementById('pf-user-toggle');
  var userDropdown = document.getElementById('pf-user-dropdown');
  if (userToggle && userDropdown) {
    userToggle.addEventListener('click', function(e) {
      e.stopPropagation();
      userDropdown.classList.toggle('open');
    });
    document.addEventListener('click', function() {
      userDropdown.classList.remove('open');
    });
  }
})();
</script>

@stack('scripts')
</body>
</html>
