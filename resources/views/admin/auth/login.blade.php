<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login Admin — Ukylaamart</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<style>
:root{--cream:#faf6f0;--warm:#f5ede0;--amber:#d4862a;--amber-dark:#b06e1a;--brown:#4a2e0e;--brown-mid:#7a4e1e;--text:#2a1a0a;--text-mid:#6b4a2a;--text-light:#9a7a5a;--border:#e8d8c0;--red:#c0392b;}
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:'DM Sans',sans-serif;background:linear-gradient(135deg,var(--brown) 0%,var(--brown-mid) 50%,#a06030 100%);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px;}
.box{background:#fff;border-radius:20px;padding:40px;width:100%;max-width:400px;box-shadow:0 24px 80px rgba(74,46,14,.4);}
.logo{text-align:center;margin-bottom:28px;}
.logo-icon{width:60px;height:60px;background:var(--amber);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:28px;margin:0 auto 12px;}
.logo-title{font-family:'Playfair Display',serif;font-size:24px;font-weight:900;color:var(--brown);}
.logo-sub{font-size:13px;color:var(--text-light);margin-top:4px;}
.form-group{margin-bottom:16px;}
.form-label{display:block;font-size:13px;font-weight:600;color:var(--text-mid);margin-bottom:6px;}
.form-input{width:100%;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:14px;font-family:'DM Sans',sans-serif;color:var(--text);background:var(--cream);outline:none;transition:.2s;}
.form-input:focus{border-color:var(--amber);background:#fff;}
.alert-error{background:#f8d7da;color:#721c24;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px;border:1px solid #f5c6cb;}
.hint{background:var(--warm);border-radius:8px;padding:10px 14px;font-size:12px;color:var(--text-light);margin-bottom:16px;border-left:3px solid var(--amber);}
.btn{width:100%;background:linear-gradient(135deg,var(--amber),var(--amber-dark));color:#fff;border:none;padding:13px;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;margin-top:4px;transition:.2s;}
.btn:hover{opacity:.9;}
.back{text-align:center;margin-top:16px;font-size:13px;color:var(--text-light);}
.back a{color:var(--amber);font-weight:600;text-decoration:none;}
</style>
</head>
<body>
<div class="box">
    <div class="logo">
        <div class="logo-icon">🔐</div>
        <div class="logo-title">Admin Panel</div>
        <div class="logo-sub">Ukylaamart — Toko Rumahan</div>
    </div>

    @if($errors->any())
        <div class="alert-error">❌ {{ $errors->first() }}</div>
    @endif
    @if(session('error'))
        <div class="alert-error">❌ {{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}">
        @csrf
        <div class="form-group">
            <label class="form-label">Email</label>
            <input class="form-input" type="email" name="email" value="{{ old('email') }}" placeholder="Masukkan email" required autofocus>
        </div>
        <div class="form-group">
            <label class="form-label">Password</label>
            <input class="form-input" type="password" name="password" placeholder="••••••••" required>
        </div>
        <button type="submit" class="btn">Masuk ke Dashboard →</button>
    </form>
    <div class="back"><a href="{{ route('shop.index') }}">← Kembali ke Toko</a></div>
</div>
</body>
</html>