<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Ukylaamart') — Toko Rumahan</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--cream:#faf6f0;--warm:#f5ede0;--amber:#d4862a;--amber-dark:#b06e1a;--amber-light:#f0c070;--brown:#4a2e0e;--brown-mid:#7a4e1e;--green:#3d6b4f;--green-light:#6aab82;--red:#c0392b;--text:#2a1a0a;--text-mid:#6b4a2a;--text-light:#9a7a5a;--border:#e8d8c0;--shadow:rgba(74,46,14,0.12);}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'DM Sans',sans-serif;background:var(--cream);color:var(--text);min-height:100vh;}
a{text-decoration:none;color:inherit;}
::-webkit-scrollbar{width:6px;} ::-webkit-scrollbar-track{background:var(--warm);} ::-webkit-scrollbar-thumb{background:var(--amber);border-radius:3px;}
header{background:linear-gradient(135deg,var(--brown),var(--brown-mid));padding:0 32px;display:flex;align-items:center;justify-content:space-between;height:68px;position:sticky;top:0;z-index:500;box-shadow:0 2px 20px rgba(74,46,14,.3);}
.logo{display:flex;align-items:center;gap:10px;}
.logo-icon{width:38px;height:38px;background:var(--amber);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:20px;}
.logo-text{font-family:'Playfair Display',serif;font-size:22px;font-weight:900;color:#fff;letter-spacing:-.5px;}
.logo-sub{font-size:11px;color:var(--amber-light);font-weight:400;letter-spacing:1px;text-transform:uppercase;}
.header-right{display:flex;align-items:center;gap:12px;}
.btn-header{background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.3);color:#fff;padding:8px 16px;border-radius:8px;font-size:13px;font-weight:500;cursor:pointer;transition:.2s;font-family:'DM Sans',sans-serif;}
.btn-header:hover{background:rgba(255,255,255,.25);}
.cart-btn{position:relative;width:42px;height:42px;border-radius:10px;border:none;cursor:pointer;background:rgba(255,255,255,.12);color:#fff;font-size:18px;display:flex;align-items:center;justify-content:center;transition:.2s;}
.cart-btn:hover{background:rgba(255,255,255,.22);}
.cart-badge{position:absolute;top:-4px;right:-4px;background:var(--amber);color:var(--brown);font-size:10px;font-weight:700;width:18px;height:18px;border-radius:50%;display:flex;align-items:center;justify-content:center;border:2px solid var(--brown);}
.alert{padding:12px 20px;border-radius:10px;margin:16px 32px;font-size:14px;font-weight:500;display:flex;align-items:center;gap:8px;}
.alert-success{background:#d4edda;color:#155724;border:1px solid #c3e6cb;}
.alert-error{background:#f8d7da;color:#721c24;border:1px solid #f5c6cb;}
footer{background:var(--brown);color:rgba(255,255,255,.6);text-align:center;padding:20px;font-size:13px;margin-top:40px;}
footer span{color:var(--amber-light);font-weight:600;}
</style>
@yield('styles')
</head>
<body>
<header>
    <a href="{{ route('shop.index') }}" class="logo">
        <div class="logo-icon">🏠</div>
        <div>
            <div class="logo-text">Ukylaamart</div>
            <div class="logo-sub">Toko Rumahan</div>
        </div>
    </a>
    <div class="header-right">
        <a href="{{ route('admin.dashboard') }}" class="btn-header" style="font-size:12px">⚙️ Admin</a>
        <a href="{{ route('cart.index') }}" class="cart-btn">
            🛒
            @php $cartCount = collect(session('cart',[]))->sum('qty'); @endphp
            <span class="cart-badge" id="cartBadge">{{ collect(session('cart',[]))->sum('qty') }}</span>
        </a>
    </div>
</header>

@if(session('success'))
    <div class="alert alert-success">✅ {{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="alert alert-error">❌ {{ session('error') }}</div>
@endif

@yield('content')

<footer>&copy; {{ date('Y') }} <span>Ukylaamart</span> — Toko Rumahan Terpercaya</footer>

<script>
function updateCartBadge(count) {
    document.getElementById('cartBadge').textContent = count;
}
function addToCart(productId) {
    fetch('{{ route("cart.add") }}', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Accept':'application/json'},
        body:JSON.stringify({product_id:productId,qty:1})
    })
    .then(r=>r.json())
    .then(data=>{
        if(data.success){ updateCartBadge(data.count); showToast('🛒 '+data.message,'success'); }
    });
}
function showToast(msg,type=''){
    const t=document.createElement('div');
    t.style.cssText='position:fixed;bottom:24px;right:24px;z-index:9999;background:'+(type==='success'?'#3d6b4f':'#4a2e0e')+';color:#fff;padding:12px 18px;border-radius:10px;font-size:14px;font-weight:500;box-shadow:0 4px 20px rgba(0,0,0,.2);';
    t.textContent=msg;
    document.body.appendChild(t);
    setTimeout(()=>{t.style.opacity='0';t.style.transition='.3s';setTimeout(()=>t.remove(),300);},2800);
}
</script>
@yield('scripts')
</body>
</html>