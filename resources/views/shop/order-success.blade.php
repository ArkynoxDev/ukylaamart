@extends('layouts.app')
@section('title','Pesanan Berhasil')
@section('styles')
<style>
.success-wrap{max-width:600px;margin:40px auto;padding:0 20px;}
.success-card{background:#fff;border-radius:20px;border:1.5px solid var(--border);overflow:hidden;box-shadow:0 8px 32px var(--shadow);}
.success-header{background:linear-gradient(135deg,var(--green),#2d5a3d);padding:32px;text-align:center;color:#fff;}
.success-icon{font-size:56px;margin-bottom:12px;}
.success-header h1{font-family:'Playfair Display',serif;font-size:26px;font-weight:900;}
.success-header p{font-size:14px;opacity:.85;margin-top:6px;}
.order-number{background:rgba(255,255,255,.2);border-radius:10px;padding:10px 20px;display:inline-block;margin-top:12px;font-size:18px;font-weight:700;letter-spacing:1px;}
.detail-body{padding:24px;}
.detail-section{margin-bottom:20px;}
.detail-section h3{font-size:12px;font-weight:700;color:var(--text-light);text-transform:uppercase;letter-spacing:.8px;margin-bottom:10px;}
.detail-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
.detail-box{background:var(--warm);border-radius:10px;padding:12px;}
.detail-label{font-size:10px;color:var(--text-light);font-weight:700;text-transform:uppercase;margin-bottom:3px;}
.detail-value{font-size:14px;font-weight:600;color:var(--text);}
.items-list{border:1px solid var(--border);border-radius:10px;overflow:hidden;}
.order-item{display:flex;align-items:center;gap:10px;padding:12px;border-bottom:1px solid var(--border);}
.order-item:last-child{border-bottom:none;}
.oi-emoji{font-size:24px;width:40px;height:40px;background:var(--warm);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.oi-name{font-size:14px;font-weight:600;flex:1;}
.oi-qty{font-size:12px;color:var(--text-light);}
.oi-price{font-size:14px;font-weight:700;color:var(--amber-dark);}
.total-row{display:flex;justify-content:space-between;font-size:20px;font-weight:700;color:var(--brown);font-family:'Playfair Display',serif;padding:16px;border-top:2px solid var(--border);margin-top:4px;}
.action-buttons{padding:0 24px 24px;display:flex;gap:10px;}
.btn-primary{flex:1;background:linear-gradient(135deg,var(--amber),var(--amber-dark));color:#fff;border:none;padding:13px;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;text-align:center;text-decoration:none;display:block;}
.btn-secondary{flex:1;background:var(--warm);color:var(--brown);border:1.5px solid var(--border);padding:13px;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;text-align:center;text-decoration:none;display:block;}
</style>
@endsection
@section('content')
<div class="success-wrap">
    <div class="success-card">
        <div class="success-header">
            <div class="success-icon">🎉</div>
            <h1>Pesanan Berhasil!</h1>
            <p>Terima kasih! Pesanan kamu sedang kami proses.</p>
            <div class="order-number">#{{ $order->order_number }}</div>
        </div>
        <div class="detail-body">
            <div class="detail-section">
                <h3>Info Pesanan</h3>
                <div class="detail-grid">
                    <div class="detail-box">
                        <div class="detail-label">Penerima</div>
                        <div class="detail-value">{{ $order->recipient_name }}</div>
                    </div>
                    <div class="detail-box">
                        <div class="detail-label">No. HP</div>
                        <div class="detail-value">{{ $order->recipient_phone }}</div>
                    </div>
                    <div class="detail-box">
                        <div class="detail-label">Pembayaran</div>
                        <div class="detail-value">{{ $order->payment_label }}</div>
                    </div>
                    <div class="detail-box">
                        <div class="detail-label">Pengiriman</div>
                        <div class="detail-value">{{ $order->shipping_label }}</div>
                    </div>
                </div>
                <div class="detail-box" style="margin-top:10px">
                    <div class="detail-label">Alamat</div>
                    <div class="detail-value">{{ $order->recipient_address }}</div>
                </div>
            </div>
            <div class="detail-section">
                <h3>Produk Dipesan</h3>
                <div class="items-list">
                    @foreach($order->items as $item)
                    <div class="order-item">
                        <div class="oi-emoji">{{ $item->product_emoji }}</div>
                        <div>
                            <div class="oi-name">{{ $item->product_name }}</div>
                            <div class="oi-qty">x{{ $item->qty }}</div>
                        </div>
                        <div class="oi-price">Rp {{ number_format($item->subtotal,0,',','.') }}</div>
                    </div>
                    @endforeach
                    <div class="total-row">
                        <span>Total</span>
                        <span>{{ $order->total_formatted }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="action-buttons">
            <a href="{{ route('shop.index') }}" class="btn-secondary">🛍️ Belanja Lagi</a>
            <a href="{{ route('order.track', ['order_number' => $order->order_number]) }}" class="btn-primary">📦 Cek Status Pesanan</a>
        </div>
    </div>
</div>
@endsection