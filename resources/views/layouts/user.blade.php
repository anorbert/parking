<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'ParkFlow — User Panel')</title>
<link href="{{ asset('img/pfr_logo2.png') }}" rel="icon">
<link href="https://fonts.googleapis.com/css2?family=Dosis:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('gentelella/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

@stack('styles')

<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --uf-yellow:  #F5A800;
  --uf-yellow2: #FFD166;
  --uf-blue:    #3A9ED4;
  --uf-blue2:   #60C4F5;
  --uf-green:   #22C55E;
  --uf-red:     #EF4444;
  --uf-bg:      #3A4048;
  --uf-sidebar: #1B2235;
  --uf-sbnav:   #141929;
  --uf-card:    #FFFFFF;
  --uf-content: #F5F7FA;
  --uf-border:  #E2E6EA;
  --uf-muted:   #9CA3AF;
  --uf-soft:    #4A5260;
  --uf-dark:    #1A2030;
  --uf-font:    'Dosis', sans-serif;
}

html, body { height: 100%; font-family: var(--uf-font); background: var(--uf-content); color: var(--uf-dark); overflow: hidden; }

/* ── LAYOUT ── */
.uf-shell { display: flex; height: 100vh; }

/* ── SIDEBAR ── */
.uf-sidebar {
  width: 240px; flex-shrink: 0;
  background: var(--uf-sidebar);
  display: flex; flex-direction: column;
  overflow: hidden;
  box-shadow: 4px 0 20px rgba(0,0,0,0.25);
  z-index: 100;
  transition: margin-left 0.3s ease;
}
.uf-sidebar.collapsed { margin-left: -240px; }

.uf-sb-brand {
  display: flex; align-items: center; gap: 12px;
  padding: 0 20px; height: 64px;
  background: var(--uf-sbnav);
  border-bottom: 1px solid rgba(255,255,255,0.06);
  flex-shrink: 0;
}
.uf-sb-icon {
  width: 36px; height: 36px; border-radius: 9px; flex-shrink: 0;
  background: linear-gradient(135deg, #F5A800, #FFD166);
  display: flex; align-items: center; justify-content: center;
}
.uf-sb-icon span { font-weight: 800; font-size: 19px; color: #0D0F11; line-height: 1; }
.uf-sb-name { font-size: 15px; font-weight: 800; letter-spacing: 2.5px; text-transform: uppercase; color: #FFF; }
.uf-sb-sub  { font-size: 9px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase; color: rgba(255,255,255,0.28); margin-top: 2px; }

.uf-sb-section { padding: 18px 20px 6px; font-size: 9px; font-weight: 800; letter-spacing: 2.5px; text-transform: uppercase; color: rgba(255,255,255,0.25); }

.uf-sb-nav { flex: 1; overflow-y: auto; padding: 8px 12px 12px; }
.uf-sb-nav::-webkit-scrollbar { width: 2px; }
.uf-sb-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 2px; }

.uf-nav-item {
  display: flex; align-items: center; gap: 11px;
  padding: 10px 12px; border-radius: 8px; margin-bottom: 1px;
  font-size: 13px; font-weight: 600;
  color: rgba(255,255,255,0.45);
  cursor: pointer; transition: all 0.18s; text-decoration: none;
  position: relative;
}
.uf-nav-item svg { flex-shrink: 0; width: 15px; height: 15px; }
.uf-nav-item:hover, .uf-nav-item:focus { background: rgba(255,255,255,0.07); color: rgba(255,255,255,0.85); text-decoration: none; }
.uf-nav-item.active {
  background: linear-gradient(90deg, rgba(245,168,0,0.18) 0%, rgba(245,168,0,0.06) 100%);
  color: #F5A800;
}
.uf-nav-item.active::before {
  content: '';
  position: absolute; left: 0; top: 20%; bottom: 20%;
  width: 3px; border-radius: 0 3px 3px 0;
  background: #F5A800;
}

.uf-sb-divider { margin: 8px 12px; border: none; border-top: 1px solid rgba(255,255,255,0.06); }

.uf-sb-user {
  padding: 13px 16px;
  background: var(--uf-sbnav);
  border-top: 1px solid rgba(255,255,255,0.06);
  display: flex; align-items: center; gap: 10px;
}
.uf-user-avatar {
  width: 34px; height: 34px; border-radius: 50%; flex-shrink: 0;
  background: linear-gradient(135deg, #3A9ED4, #60C4F5);
  display: flex; align-items: center; justify-content: center;
  font-weight: 800; font-size: 13px; color: #0D0F11;
}
.uf-user-name { font-size: 13px; font-weight: 700; color: #FFF; }
.uf-user-role { font-size: 10px; font-weight: 500; color: rgba(255,255,255,0.3); }
.uf-sb-user-actions { margin-left: auto; display: flex; gap: 6px; }
.uf-sb-user-btn {
  width: 26px; height: 26px; border-radius: 6px;
  background: rgba(255,255,255,0.07); border: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  color: rgba(255,255,255,0.4); transition: all 0.15s;
}
.uf-sb-user-btn:hover { background: rgba(255,255,255,0.13); color: rgba(255,255,255,0.85); }

/* ── MAIN ── */
.uf-main { flex: 1; display: flex; flex-direction: column; overflow: hidden; }

/* ── TOPBAR ── */
.uf-topbar {
  height: 64px; flex-shrink: 0;
  background: #FFF;
  border-bottom: 1px solid var(--uf-border);
  display: flex; align-items: center;
  justify-content: space-between;
  padding: 0 24px;
  box-shadow: 0 1px 8px rgba(0,0,0,0.06);
}
.uf-topbar-left { display: flex; align-items: center; gap: 16px; }
.uf-menu-btn {
  width: 34px; height: 34px; border-radius: 8px;
  background: #F0F3F7; border: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  color: #6B7280; transition: background 0.15s;
}
.uf-menu-btn:hover { background: #E4E8EE; }
.uf-breadcrumb { display: flex; align-items: center; gap: 6px; }
.uf-bc-root { font-size: 12px; font-weight: 600; color: #9CA3AF; }
.uf-bc-sep  { font-size: 12px; color: #D1D5DB; }
.uf-bc-cur  { font-size: 13px; font-weight: 800; color: var(--uf-dark); }
.uf-topbar-right { display: flex; align-items: center; gap: 10px; }
.uf-tb-search {
  display: flex; align-items: center; gap: 8px;
  background: #F5F7FA; border: 1px solid var(--uf-border);
  border-radius: 8px; padding: 7px 12px; width: 200px;
}
.uf-tb-search input {
  background: none; border: none; outline: none;
  font-family: var(--uf-font); font-size: 12px; font-weight: 500;
  color: var(--uf-dark); width: 100%;
}
.uf-tb-search input::placeholder { color: #9CA3AF; }
.uf-tb-badge {
  display: flex; align-items: center; gap: 6px;
  background: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.25);
  border-radius: 999px; padding: 5px 12px;
  font-size: 11px; font-weight: 700; color: #22C55E; letter-spacing: 0.5px;
}
.uf-tb-dot {
  width: 6px; height: 6px; border-radius: 50%;
  background: var(--uf-green);
  box-shadow: 0 0 6px rgba(34,197,94,0.6);
  animation: uf-pulse 2s infinite;
}
@keyframes uf-pulse { 0%,100%{opacity:1;} 50%{opacity:0.4;} }
.uf-tb-time { font-size: 12px; font-weight: 700; color: #4B5563; font-variant-numeric: tabular-nums; }
.uf-tb-icon-btn {
  width: 36px; height: 36px; border-radius: 9px;
  background: #F5F7FA; border: 1px solid var(--uf-border);
  cursor: pointer; display: flex; align-items: center; justify-content: center;
  color: #6B7280; position: relative; transition: all 0.15s;
}
.uf-tb-icon-btn:hover { background: #EAECF0; border-color: #D0D5DD; }
.uf-tb-notif-dot {
  position: absolute; top: 7px; right: 7px;
  width: 7px; height: 7px; border-radius: 50%;
  background: #F5A800; border: 1.5px solid #FFF;
}
.uf-tb-divider { width: 1px; height: 28px; background: var(--uf-border); margin: 0 4px; }
.uf-tb-user {
  display: flex; align-items: center; gap: 9px;
  padding: 5px 10px 5px 5px; border-radius: 10px; cursor: pointer;
  border: 1px solid var(--uf-border); background: #F9FAFB; transition: all 0.15s;
  position: relative;
}
.uf-tb-user:hover { background: #F0F3F7; border-color: #D0D5DD; }
.uf-tb-avatar {
  width: 30px; height: 30px; border-radius: 50%;
  background: linear-gradient(135deg, #F5A800, #FFD166);
  display: flex; align-items: center; justify-content: center;
  font-weight: 800; font-size: 12px; color: #0D0F11;
}
.uf-tb-uname { font-size: 12px; font-weight: 700; color: var(--uf-dark); }
.uf-tb-urole { font-size: 10px; font-weight: 500; color: #9CA3AF; }
.uf-tb-dropdown {
  display: none; position: absolute; top: 100%; right: 0;
  margin-top: 6px; background: #FFF; border: 1px solid var(--uf-border);
  border-radius: 10px; box-shadow: 0 8px 24px rgba(0,0,0,0.12);
  min-width: 160px; z-index: 200; overflow: hidden;
}
.uf-tb-dropdown.open { display: block; }
.uf-tb-dropdown a {
  display: flex; align-items: center; gap: 8px;
  padding: 10px 16px; font-size: 12px; font-weight: 600;
  color: #4A5260; text-decoration: none; transition: all 0.15s;
}
.uf-tb-dropdown a:hover { background: #F5F7FA; color: #1A2030; }
.uf-tb-dropdown a.uf-danger { color: var(--uf-red); }
.uf-tb-dropdown a.uf-danger:hover { background: rgba(239,68,68,0.08); }

/* ── CONTENT ── */
.uf-content { flex: 1; overflow-y: auto; padding: 22px 24px; background: var(--uf-content); }
.uf-content::-webkit-scrollbar { width: 4px; }
.uf-content::-webkit-scrollbar-thumb { background: #C8CDD4; border-radius: 2px; }

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
  .uf-sidebar { position: fixed; left: 0; top: 0; bottom: 0; margin-left: -240px; z-index: 200; }
  .uf-sidebar.mobile-open { margin-left: 0; }
  .uf-tb-search, .uf-tb-badge { display: none; }
}
</style>
</head>
<body>

<div class="uf-shell">
  @include('partials.users.user_sidebar')

  <div class="uf-main">
    @include('partials.users.user_topnav')

    <div class="uf-content">
      @yield('content')
    </div>
  </div>
</div>

<!-- jQuery -->
<script src="{{ asset('gentelella/vendors/jquery/dist/jquery.min.js') }}"></script>

<!-- Shell Scripts -->
<script>
(function() {
  var clock = document.getElementById('uf-clock');
  if (clock) {
    function tick() { clock.textContent = new Date().toLocaleTimeString('en-GB', { hour12: false }); }
    tick(); setInterval(tick, 1000);
  }
  var menuBtn = document.getElementById('uf-menu-toggle');
  var sidebar = document.getElementById('uf-sidebar');
  if (menuBtn && sidebar) {
    menuBtn.addEventListener('click', function() {
      if (window.innerWidth <= 768) {
        sidebar.classList.toggle('mobile-open');
      } else {
        sidebar.classList.toggle('collapsed');
      }
    });
  }
  var userToggle = document.getElementById('uf-user-toggle');
  var userDropdown = document.getElementById('uf-user-dropdown');
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
