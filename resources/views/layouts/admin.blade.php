<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title') — Admin Ukylaamart</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{--cream:#faf6f0;--warm:#f5ede0;--amber:#d4862a;--amber-dark:#b06e1a;--amber-light:#f0c070;--brown:#4a2e0e;--brown-mid:#7a4e1e;--green:#3d6b4f;--green-light:#6aab82;--red:#c0392b;--text:#2a1a0a;--text-mid:#6b4a2a;--text-light:#9a7a5a;--border:#e8d8c0;--shadow:rgba(74,46,14,0.12);--sidebar:220px;}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'DM Sans',sans-serif;background:#f0ebe3;color:var(--text);display:flex;min-height:100vh;}
a{text-decoration:none;color:inherit;}

/* Sidebar */
.sidebar{width:var(--sidebar);background:linear-gradient(180deg,var(--brown),var(--brown-mid));display:flex;flex-direction:column;position:fixed;top:0;left:0;height:100vh;z-index:200;box-shadow:4px 0 20px rgba(74,46,14,.2);transition:transform .3s ease;}
.sidebar-logo{padding:22px 20px;border-bottom:1px solid rgba(255,255,255,.1);display:flex;align-items:center;gap:10px;}
.s-icon{width:34px;height:34px;background:var(--amber);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;}
.s-text{font-family:'Playfair Display',serif;font-size:16px;font-weight:900;color:#fff;}
.s-sub{font-size:10px;color:var(--amber-light);letter-spacing:1px;text-transform:uppercase;}
.sidebar-nav{flex:1;padding:16px 0;overflow-y:auto;}
.nav-section{padding:8px 20px 4px;font-size:10px;font-weight:700;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:1px;}
.nav-item{display:flex;align-items:center;gap:10px;padding:10px 20px;color:rgba(255,255,255,.7);font-size:14px;font-weight:500;transition:.2s;border-left:3px solid transparent;}
.nav-item:hover{background:rgba(255,255,255,.1);color:#fff;}
.nav-item.active{background:rgba(255,255,255,.15);color:#fff;border-left-color:var(--amber-light);}
.nav-item .ni{font-size:16px;width:20px;text-align:center;}
.sidebar-footer{padding:16px 20px;border-top:1px solid rgba(255,255,255,.1);}
.user-info{display:flex;align-items:center;gap:10px;margin-bottom:12px;}
.user-avatar{width:36px;height:36px;background:var(--amber);border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:16px;flex-shrink:0;}
.user-name{font-size:13px;font-weight:600;color:#fff;}
.user-role{font-size:11px;color:var(--amber-light);}
.btn-logout{width:100%;background:rgba(255,255,255,.1);border:1px solid rgba(255,255,255,.2);color:rgba(255,255,255,.8);padding:8px;border-radius:8px;font-size:13px;cursor:pointer;transition:.2s;font-family:'DM Sans',sans-serif;}
.btn-logout:hover{background:rgba(192,57,43,.5);color:#fff;}

/* Overlay */
.overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:150;}
.overlay.show{display:block;}

/* Main */
.main{margin-left:var(--sidebar);flex:1;display:flex;flex-direction:column;}
.topbar{background:#fff;padding:0 28px;height:58px;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--border);box-shadow:0 1px 4px var(--shadow);}
.topbar-title{font-family:'Playfair Display',serif;font-size:20px;font-weight:700;color:var(--brown);}
.hamburger{display:none;background:none;border:none;cursor:pointer;padding:8px;border-radius:8px;color:var(--brown);}
.hamburger:hover{background:var(--warm);}
.hamburger span{display:block;width:22px;height:2px;background:var(--brown);margin:5px 0;transition:.3s;border-radius:2px;}
.content{padding:24px 28px;flex:1;}

/* Alerts */
.alert{padding:12px 18px;border-radius:10px;margin-bottom:16px;font-size:14px;font-weight:500;display:flex;align-items:center;gap:8px;}
.alert-success{background:#d4edda;color:#155724;border:1px solid #c3e6cb;}
.alert-error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;}

/* Cards */
.card{background:#fff;border-radius:14px;border:1.5px solid var(--border);overflow:hidden;box-shadow:0 2px 8px var(--shadow);}
.card-header{padding:14px 20px;background:var(--warm);border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
.card-header h3{font-size:14px;font-weight:700;color:var(--brown-mid);text-transform:uppercase;letter-spacing:.5px;}
.card-body{padding:20px;}
.table-wrap{overflow-x:auto;}
table{width:100%;border-collapse:collapse;}
th{background:var(--warm);padding:10px 14px;text-align:left;font-size:12px;font-weight:700;color:var(--text-mid);text-transform:uppercase;letter-spacing:.5px;border-bottom:1px solid var(--border);}
td{padding:12px 14px;font-size:14px;border-bottom:1px solid var(--border);vertical-align:middle;}
tr:last-child td{border-bottom:none;}
tr:hover td{background:var(--cream);}

/* Badges */
.badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:11px;font-weight:700;}
.badge-success{background:#d4edda;color:#155724;}
.badge-warning{background:#fff3cd;color:#856404;}
.badge-danger{background:#f8d7da;color:#721c24;}
.badge-info{background:#cce5ff;color:#004085;}
.badge-primary{background:#d4e1f7;color:#1a4a9a;}
.badge-secondary{background:#e2e3e5;color:#383d41;}

/* Buttons */
.btn{display:inline-flex;align-items:center;gap:6px;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:600;cursor:pointer;transition:.2s;border:none;font-family:'DM Sans',sans-serif;text-decoration:none;}
.btn-primary{background:var(--amber);color:#fff;}
.btn-primary:hover{background:var(--amber-dark);}
.btn-sm{padding:5px 12px;font-size:12px;}
.btn-secondary{background:var(--warm);color:var(--brown);border:1px solid var(--border);}
.btn-secondary:hover{background:var(--border);}
.btn-danger{background:#f8d7da;color:var(--red);}
.btn-danger:hover{background:var(--red);color:#fff;}
.btn-success{background:#d4edda;color:var(--green);}
.btn-success:hover{background:var(--green);color:#fff;}

/* Forms */
.form-group{margin-bottom:16px;}
.form-label{display:block;font-size:13px;font-weight:600;color:var(--text-mid);margin-bottom:6px;}
.form-label span{color:var(--red);}
.form-input,.form-select,.form-textarea{width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:14px;font-family:'DM Sans',sans-serif;color:var(--text);background:var(--cream);outline:none;transition:.2s;}
.form-input:focus,.form-select:focus,.form-textarea:focus{border-color:var(--amber);background:#fff;}
.form-textarea{resize:vertical;min-height:80px;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:16px;}
.form-error{color:var(--red);font-size:12px;margin-top:4px;}

/* Stats */
.stats-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:16px;margin-bottom:24px;}
.stat-card{background:#fff;border-radius:14px;padding:18px;border:1.5px solid var(--border);box-shadow:0 2px 8px var(--shadow);}
.stat-icon{font-size:28px;margin-bottom:8px;}
.stat-value{font-family:'Playfair Display',serif;font-size:26px;font-weight:700;color:var(--brown);}
.stat-label{font-size:12px;color:var(--text-light);margin-top:2px;}

/* Mobile */
@media(max-width:768px){
    .sidebar{transform:translateX(-100%);}
    .sidebar.open{transform:translateX(0);}
    .main{margin-left:0;}
    .hamburger{display:block;}
    .topbar{padding:0 16px;}
    .content{padding:16px;}
    .stats-grid{grid-template-columns:1fr 1fr;}
    .form-row{grid-template-columns:1fr;}
    .topbar-title{font-size:16px;}
}
</style>
@yield('styles')
</head>
<body>

<div class="overlay" id="overlay" onclick="closeSidebar()"></div>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="s-icon">🏠</div>
        <div><div class="s-text">Ukylaamart</div><div class="s-sub">Admin Panel</div></div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">Menu</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard')?'active':'' }}" onclick="closeSidebar()">
            <span class="ni">📊</span> Dashboard
        </a>
        <div class="nav-section">Katalog</div>
        <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products*')?'active':'' }}" onclick="closeSidebar()">
            <span class="ni">🛍️</span> Produk
        </a>
        <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories*')?'active':'' }}" onclick="closeSidebar()">
            <span class="ni">🏷️</span> Kategori
        </a>
        <div class="nav-section">Transaksi</div>
        <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders*')?'active':'' }}" onclick="closeSidebar()">
            <span class="ni">📦</span> Pesanan
        </a>
        <div class="nav-section">Toko</div>
        <a href="{{ route('shop.index') }}" class="nav-item" target="_blank">
            <span class="ni">🌐</span> Lihat Toko
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar">👤</div>
            <div>
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn-logout">🚪 Logout</button>
        </form>
    </div>
</aside>

<div class="main">
    <div class="topbar">
        <div style="display:flex;align-items:center;gap:12px;">
            <button class="hamburger" onclick="toggleSidebar()">
                <span></span><span></span><span></span>
            </button>
            <div class="topbar-title">@yield('title')</div>
        </div>
        <div style="font-size:13px;color:var(--text-light)">{{ now()->format('d M Y') }}</div>
    </div>
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">❌ {{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</div>

<script>
function toggleSidebar(){
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('overlay').classList.toggle('show');
}
function closeSidebar(){
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('overlay').classList.remove('show');
}
</script>
@yield('scripts')
</body>
</html>