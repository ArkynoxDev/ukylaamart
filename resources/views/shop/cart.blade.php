@extends('layouts.app')
@section('title','Keranjang Belanja')
@section('styles')
<style>
.page-header{background:linear-gradient(135deg,var(--warm),#fce8c8);padding:28px 32px;border-bottom:1px solid var(--border);}
.page-header h1{font-family:'Playfair Display',serif;font-size:28px;font-weight:900;color:var(--brown);}
.page-header p{font-size:13px;color:var(--text-light);margin-top:4px;}
.cart-wrap{padding:28px 32px;display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;}
.cart-table{background:#fff;border-radius:14px;border:1.5px solid var(--border);overflow:hidden;box-shadow:0 2px 8px var(--shadow);}
.cart-item{display:grid;grid-template-columns:64px 1fr auto auto;gap:16px;align-items:center;padding:16px 20px;border-bottom:1px solid var(--border);}
.cart-item:last-child{border-bottom:none;}
.item-img{width:64px;height:64px;border-radius:10px;background:var(--warm);display:flex;align-items:center;justify-content:center;font-size:32px;overflow:hidden;flex-shrink:0;}
.item-img img{width:100%;height:100%;object-fit:cover;}
.item-name{font-weight:700;font-size:15px;color:var(--text);}
.item-cat{font-size:12px;color:var(--text-light);margin-top:2px;}
.item-price{font-size:13px;color:var(--amber-dark);font-weight:600;margin-top:4px;}
.qty-wrap{display:flex;align-items:center;gap:6px;}
.qty-btn{width:30px;height:30px;border:1.5px solid var(--border);border-radius:8px;background:var(--cream);cursor:pointer;font-size:16px;font-weight:700;display:flex;align-items:center;justify-content:center;transition:.2s;}
.qty-btn:hover{border-color:var(--amber);color:var(--amber);}
.qty-val{width:36px;text-align:center;font-weight:700;font-size:15px;}
.item-subtotal{font-weight:700;font-size:15px;color:var(--brown);min-width:80px;text-align:right;}
.btn-remove{background:none;border:none;cursor:pointer;font-size:18px;color:var(--text-light);padding:4px;transition:.2s;}
.btn-remove:hover{color:var(--red);}
.cart-footer{padding:14px 20px;background:var(--warm);display:flex;justify-content:space-between;align-items:center;}
.summary-card{background:#fff;border-radius:14px;border:1.5px solid var(--border);overflow:hidden;box-shadow:0 2px 8px var(--shadow);position:sticky;top:20px;}
.summary-header{padding:14px 20px;background:var(--warm);border-bottom:1px solid var(--border);}
.summary-header h3{font-size:14px;font-weight:700;color:var(--brown-mid);text-transform:uppercase;letter-spacing:.5px;}
.summary-body{padding:20px;}
.summary-row{display:flex;justify-content:space-between;font-size:14px;color:var(--text-mid);margin-bottom:10px;}
.summary-total{display:flex;justify-content:space-between;font-size:20px;font-weight:700;color:var(--brown);font-family:'Playfair Display',serif;padding-top:12px;border-top:2px solid var(--border);margin-top:4px;}
.btn-checkout{display:block;width:100%;background:linear-gradient(135deg,var(--amber),var(--amber-dark));color:#fff;border:none;padding:14px;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;margin-top:16px;text-align:center;text-decoration:none;transition:.2s;}
.btn-checkout:hover{opacity:.9;}
.btn-clear{background:none;border:1.5px solid var(--border);color:var(--text-light);padding:8px 16px;border-radius:8px;font-size:13px;cursor:pointer;font-family:'DM Sans',sans-serif;transition:.2s;}
.btn-clear:hover{border-color:var(--red);color:var(--red);}
.empty-cart{text-align:center;padding:60px 20px;}
.empty-cart .ei{font-size:64px;margin-bottom:16px;}
.empty-cart h2{font-family:'Playfair Display',serif;font-size:22px;color:var(--brown);margin-bottom:8px;}
.empty-cart p{color:var(--text-light);font-size:14px;margin-bottom:20px;}
.btn-shop{display:inline-block;background:var(--amber);color:#fff;padding:12px 28px;border-radius:10px;font-weight:700;font-size:14px;text-decoration:none;transition:.2s;}
.btn-shop:hover{background:var(--amber-dark);}
</style>
@endsection
@section('content')
<div class="page-header">
    <h1>🛒 Keranjang Belanja</h1>
    <p>{{ count($cart) }} jenis produk dalam keranjang</p>
</div>

@if(count($cart) > 0)
<div class="cart-wrap">
    <div>
        <div class="cart-table">
            @foreach($cart as $item)
            <div class="cart-item">
              <div class="item-img">
    @if($item['product']->image_url)
        <img src="{{ $item['product']->image_url }}" alt="{{ $item['product']->name }}">
    @else
        {{ $item['product']->emoji ?? '🍱' }}
    @endif
            </div>
            <div>
                <div class="item-name">{{ $item['product']->name }}</div>
                <div class="item-cat">{{ $item['product']->category->name ?? '' }}</div>
                <div class="item-price">Rp {{ number_format($item['price'],0,',','.') }}</div>
            </div>
            <div class="qty-wrap">
                <button class="qty-btn" onclick="updateCart({{ $item['product']->id }}, {{ $item['qty'] - 1 }})">−</button>
                <span class="qty-val">{{ $item['qty'] }}</span>
                <button class="qty-btn" onclick="updateCart({{ $item['product']->id }}, {{ $item['qty'] + 1 }})">+</button>
            </div>
            <div style="display:flex;align-items:center;gap:12px">
                <div class="item-subtotal">Rp {{ number_format($item['subtotal'],0,',','.') }}</div>
                <button class="btn-remove" onclick="removeCart({{ $item['product']->id }})" title="Hapus">🗑️</button>
            </div>
            </div>
            @endforeach
            <div class="cart-footer">
                <form method="POST" action="{{ route('cart.clear') }}" onsubmit="return confirm('Kosongkan keranjang?')">
                    @csrf
                    <button type="submit" class="btn-clear">🗑️ Kosongkan Keranjang</button>
                </form>
                <a href="{{ route('shop.index') }}" style="font-size:13px;color:var(--amber);font-weight:600">← Lanjut Belanja</a>
            </div>
        </div>
    </div>
    <div class="summary-card">
        <div class="summary-header"><h3>Ringkasan Belanja</h3></div>
        <div class="summary-body">
          @php $subtotal = collect($cart)->sum('subtotal'); @endphp
            <div class="summary-row"><span>{{ collect($cart)->sum('qty') }} item</span><span>Rp {{ number_format($subtotal,0,',','.') }}</span></div>
            <div class="summary-row"><span>Ongkos kirim</span><span style="color:var(--green)">Dihitung saat checkout</span></div>
            <div class="summary-total"><span>Total</span><span>Rp {{ number_format($subtotal,0,',','.') }}</span></div>
            <a href="{{ route('checkout.index') }}" class="btn-checkout">Lanjut ke Checkout →</a>
        </div>
    </div>
</div>
@else
<div class="empty-cart">
    <div class="ei">🛒</div>
    <h2>Keranjang Kosong</h2>
    <p>Belum ada produk yang ditambahkan ke keranjang.</p>
    <a href="{{ route('shop.index') }}" class="btn-shop">Mulai Belanja →</a>
</div>
@endif
@endsection
@section('scripts')
<script>
function updateCart(id, qty) {
    if(qty < 1){ removeCart(id); return; }
    fetch('{{ route("cart.update") }}', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body:JSON.stringify({product_id:id, qty:qty})
    }).then(()=>location.reload());
}
function removeCart(id) {
    fetch('{{ route("cart.remove") }}', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body:JSON.stringify({product_id:id})
    }).then(()=>location.reload());
}
</script>
@endsection