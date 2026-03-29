@extends('layouts.app')
@section('title','Checkout')
@section('styles')
<style>
.page-header{background:linear-gradient(135deg,var(--warm),#fce8c8);padding:28px 32px;border-bottom:1px solid var(--border);}
.page-header h1{font-family:'Playfair Display',serif;font-size:28px;font-weight:900;color:var(--brown);}
.checkout-wrap{padding:28px 32px;display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;}
.form-card{background:#fff;border-radius:14px;border:1.5px solid var(--border);overflow:hidden;box-shadow:0 2px 8px var(--shadow);margin-bottom:16px;}
.form-card-header{padding:14px 20px;background:var(--warm);border-bottom:1px solid var(--border);}
.form-card-header h3{font-size:14px;font-weight:700;color:var(--brown-mid);text-transform:uppercase;letter-spacing:.5px;}
.form-card-body{padding:20px;}
.form-group{margin-bottom:14px;}
.form-label{display:block;font-size:13px;font-weight:600;color:var(--text-mid);margin-bottom:5px;}
.form-input,.form-select,.form-textarea{width:100%;padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:14px;font-family:'DM Sans',sans-serif;color:var(--text);background:var(--cream);outline:none;transition:.2s;}
.form-input:focus,.form-select:focus,.form-textarea:focus{border-color:var(--amber);background:#fff;}
.form-textarea{resize:vertical;min-height:80px;}
.form-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;}
.form-error{color:var(--red);font-size:12px;margin-top:3px;}
.radio-group{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
.radio-card{border:1.5px solid var(--border);border-radius:10px;padding:12px;cursor:pointer;transition:.2s;display:flex;align-items:center;gap:10px;}
.radio-card:hover{border-color:var(--amber);}
.radio-card input{accent-color:var(--amber);}
.radio-card.selected{border-color:var(--amber);background:var(--warm);}
.radio-label{font-size:13px;font-weight:600;color:var(--text);}
.radio-sub{font-size:11px;color:var(--text-light);}
.order-summary{background:#fff;border-radius:14px;border:1.5px solid var(--border);overflow:hidden;box-shadow:0 2px 8px var(--shadow);position:sticky;top:20px;}
.summary-header{padding:14px 20px;background:var(--warm);border-bottom:1px solid var(--border);}
.summary-header h3{font-size:14px;font-weight:700;color:var(--brown-mid);text-transform:uppercase;letter-spacing:.5px;}
.summary-item{display:flex;gap:10px;align-items:center;padding:10px 20px;border-bottom:1px solid var(--border);}
.si-emoji{font-size:24px;width:36px;height:36px;background:var(--warm);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.si-name{font-size:13px;font-weight:600;flex:1;}
.si-qty{font-size:12px;color:var(--text-light);}
.si-price{font-size:13px;font-weight:700;color:var(--amber-dark);}
.summary-totals{padding:16px 20px;}
.st-row{display:flex;justify-content:space-between;font-size:13px;color:var(--text-mid);margin-bottom:8px;}
.st-total{display:flex;justify-content:space-between;font-size:18px;font-weight:700;color:var(--brown);font-family:'Playfair Display',serif;padding-top:10px;border-top:2px solid var(--border);}
.btn-order{display:block;width:100%;background:linear-gradient(135deg,var(--amber),var(--amber-dark));color:#fff;border:none;padding:14px;border-radius:10px;font-size:15px;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;margin-top:16px;transition:.2s;}
.btn-order:hover{opacity:.9;}
</style>
@endsection
@section('content')
<div class="page-header">
    <h1>📋 Checkout</h1>
    <p>Lengkapi data pengiriman untuk menyelesaikan pesanan</p>
</div>
<form method="POST" action="{{ route('checkout.store') }}">
@csrf
<div class="checkout-wrap">
    <div>
        <div class="form-card">
            <div class="form-card-header"><h3>📍 Data Penerima</h3></div>
            <div class="form-card-body">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Penerima</label>
                        <input class="form-input" type="text" name="recipient_name" value="{{ old('recipient_name') }}" placeholder="Nama lengkap" required>
                        @error('recipient_name')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nomor HP / WA</label>
                        <input class="form-input" type="text" name="recipient_phone" value="{{ old('recipient_phone') }}" placeholder="08xxxxxxxxxx" required>
                        @error('recipient_phone')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat Lengkap</label>
                    <textarea class="form-textarea" name="recipient_address" placeholder="Jl. nama jalan, No. rumah, RT/RW, Kelurahan, Kecamatan, Kota" required>{{ old('recipient_address') }}</textarea>
                    @error('recipient_address')<p class="form-error">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header"><h3>💳 Metode Pembayaran</h3></div>
            <div class="form-card-body">
                <div class="radio-group">
                    <label class="radio-card" id="rc-cash">
                        <input type="radio" name="payment_method" value="cash" {{ old('payment_method','cash')=='cash'?'checked':'' }} onchange="selectRadio('cash')">
                        <div><div class="radio-label">💵 Tunai</div><div class="radio-sub">Bayar saat terima</div></div>
                    </label>
                    <label class="radio-card" id="rc-cashless">
                        <input type="radio" name="payment_method" value="cashless" {{ old('payment_method')=='cashless'?'checked':'' }} onchange="selectRadio('cashless')">
                        <div><div class="radio-label">📱 Non-Tunai</div><div class="radio-sub">Transfer / e-wallet</div></div>
                    </label>
                </div>
                @error('payment_method')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header"><h3>🚚 Metode Pengiriman</h3></div>
            <div class="form-card-body">
                <div class="radio-group">
                    <label class="radio-card" id="rs-cod">
                        <input type="radio" name="shipping_method" value="cod" {{ old('shipping_method','cod')=='cod'?'checked':'' }} onchange="selectShipping('cod')">
                        <div><div class="radio-label">🏍️ COD</div><div class="radio-sub">Gratis ongkir</div></div>
                    </label>
                    <label class="radio-card" id="rs-jne">
                        <input type="radio" name="shipping_method" value="jne" {{ old('shipping_method')=='jne'?'checked':'' }} onchange="selectShipping('jne')">
                        <div><div class="radio-label">📦 JNE</div><div class="radio-sub">Rp 15.000</div></div>
                    </label>
                </div>
                @error('shipping_method')<p class="form-error">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="form-card">
            <div class="form-card-header"><h3>📝 Catatan (opsional)</h3></div>
            <div class="form-card-body">
                <textarea class="form-textarea" name="notes" placeholder="Contoh: tolong dibungkus rapi, jangan dibalik...">{{ old('notes') }}</textarea>
            </div>
        </div>
    </div>

    <div class="order-summary">
        <div class="summary-header"><h3>Ringkasan Pesanan</h3></div>
            @foreach($cart as $item)
        <div class="summary-item">
            <div class="si-emoji">{{ $item['product']->emoji ?? '🍱' }}</div>
            <div>
                <div class="si-name">{{ $item['product']->name }}</div>
                <div class="si-qty">x{{ $item['qty'] }}</div>
            </div>
            <div class="si-price">Rp {{ number_format($item['subtotal'],0,',','.') }}</div>
        </div>
        @endforeach
        @php
            $subtotal = collect($cart)->sum('subtotal');
            $shipping = 0;
        @endphp
        <div class="summary-totals">
            <div class="st-row"><span>Subtotal</span><span>Rp {{ number_format($subtotal,0,',','.') }}</span></div>
            <div class="st-row"><span>Ongkos Kirim</span><span id="shippingCost">Gratis</span></div>
            <div class="st-total"><span>Total</span><span id="totalPrice">Rp {{ number_format($subtotal,0,',','.') }}</span></div>
            <button type="submit" class="btn-order">🛍️ Pesan Sekarang</button>
        </div>
    </div>
</div>
</form>
@endsection
@section('scripts')
<script>
const subtotal = {{ collect($cart)->sum(fn($i)=>$i['price']*$i['qty']) }};
function fmt(n){return 'Rp '+n.toLocaleString('id-ID');}
function selectRadio(v){
    document.querySelectorAll('[id^="rc-"]').forEach(e=>e.classList.remove('selected'));
    document.getElementById('rc-'+v).classList.add('selected');
}
function selectShipping(v){
    document.querySelectorAll('[id^="rs-"]').forEach(e=>e.classList.remove('selected'));
    document.getElementById('rs-'+v).classList.add('selected');
    const cost = v==='jne'?15000:0;
    document.getElementById('shippingCost').textContent = cost>0?fmt(cost):'Gratis';
    document.getElementById('totalPrice').textContent = fmt(subtotal+cost);
}
selectRadio('{{ old('payment_method','cash') }}');
selectShipping('{{ old('shipping_method','cod') }}');
</script>
@endsection