@extends('layouts.app')
@section('title','Cek Status Pesanan')
@section('styles')
<style>
.page-header{background:linear-gradient(135deg,var(--warm),#fce8c8);padding:28px 32px;border-bottom:1px solid var(--border);}
.page-header h1{font-family:'Playfair Display',serif;font-size:28px;font-weight:900;color:var(--brown);}
.track-wrap{max-width:640px;margin:32px auto;padding:0 20px;}
.search-card{background:#fff;border-radius:14px;border:1.5px solid var(--border);padding:24px;box-shadow:0 2px 8px var(--shadow);margin-bottom:24px;}
.search-card h3{font-family:'Playfair Display',serif;font-size:18px;color:var(--brown);margin-bottom:16px;}
.search-row{display:flex;gap:10px;}
.form-input{flex:1;padding:11px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:14px;font-family:'DM Sans',sans-serif;outline:none;background:var(--cream);transition:.2s;}
.form-input:focus{border-color:var(--amber);background:#fff;}
.btn-search{background:var(--amber);color:#fff;border:none;padding:11px 20px;border-radius:10px;font-size:14px;font-weight:700;cursor:pointer;font-family:'DM Sans',sans-serif;transition:.2s;white-space:nowrap;}
.btn-search:hover{background:var(--amber-dark);}
.order-card{background:#fff;border-radius:14px;border:1.5px solid var(--border);overflow:hidden;box-shadow:0 2px 8px var(--shadow);}
.order-header{background:linear-gradient(135deg,var(--brown),var(--brown-mid));padding:20px 24px;color:#fff;}
.order-header h2{font-family:'Playfair Display',serif;font-size:20px;}
.order-header p{font-size:13px;opacity:.8;margin-top:4px;}
.order-body{padding:24px;}
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;}
.info-box{background:var(--warm);border-radius:10px;padding:12px;}
.info-label{font-size:10px;color:var(--text-light);font-weight:700;text-transform:uppercase;letter-spacing:.5px;margin-bottom:3px;}
.info-value{font-size:14px;font-weight:600;color:var(--text);}
.timeline{margin-bottom:20px;}
.timeline h3{font-size:13px;font-weight:700;color:var(--text-mid);text-transform:uppercase;letter-spacing:.5px;margin-bottom:14px;}
.tl-item{display:flex;gap:14px;margin-bottom:12px;}
.tl-dot{width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;flex-shrink:0;margin-top:2px;}
.tl-dot.done{background:var(--green);color:#fff;}
.tl-dot.active{background:var(--amber);color:#fff;}
.tl-dot.pending{background:var(--border);color:var(--text-light);}
.tl-content{}
.tl-title{font-size:14px;font-weight:600;color:var(--text);}
.tl-sub{font-size:12px;color:var(--text-light);margin-top:2px;}
.items-section h3{font-size:13px;font-weight:700;color:var(--text-mid);text-transform:uppercase;letter-spacing:.5px;margin-bottom:12px;}
.order-item{display:flex;align-items:center;gap:10px;padding:10px;background:var(--warm);border-radius:10px;margin-bottom:8px;}
.oi-emoji{font-size:22px;width:38px;height:38px;background:#fff;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
.oi-name{font-size:14px;font-weight:600;flex:1;}
.oi-qty{font-size:12px;color:var(--text-light);}
.oi-price{font-size:14px;font-weight:700;color:var(--amber-dark);}
.total-box{background:var(--brown);color:#fff;border-radius:10px;padding:14px 16px;display:flex;justify-content:space-between;align-items:center;margin-top:12px;}
.total-box span{font-size:14px;opacity:.8;}
.total-box strong{font-family:'Playfair Display',serif;font-size:20px;}
.alert-error{background:#f8d7da;color:#721c24;padding:12px 16px;border-radius:10px;font-size:14px;margin-bottom:16px;}
</style>
@endsection
@section('content')
<div class="page-header">
    <h1>📦 Cek Status Pesanan</h1>
</div>
<div class="track-wrap">
    <div class="search-card">
        <h3>🔍 Lacak Pesanan</h3>
        <form method="GET" action="{{ route('order.track') }}">
            <div class="search-row">
                <input class="form-input" type="text" name="order_number" 
                    value="{{ request('order_number') }}" 
                    placeholder="Masukkan nomor pesanan, contoh: DK67ABC123">
                <button type="submit" class="btn-search">Cek Sekarang</button>
            </div>
        </form>
    </div>

    @if(request('order_number'))
        @if($order)
        @php
            $statuses = ['pending','processing','shipped','delivered'];
            $currentIndex = array_search($order->status, $statuses);
            $steps = [
                'pending'    => ['icon'=>'📋','title'=>'Pesanan Diterima','sub'=>'Pesanan kamu sedang menunggu konfirmasi'],
                'processing' => ['icon'=>'👨‍🍳','title'=>'Sedang Diproses','sub'=>'Pesanan sedang disiapkan'],
                'shipped'    => ['icon'=>'🚚','title'=>'Dalam Pengiriman','sub'=>'Pesanan sedang dalam perjalanan'],
                'delivered'  => ['icon'=>'✅','title'=>'Pesanan Selesai','sub'=>'Pesanan telah diterima'],
            ];
        @endphp
        <div class="order-card">
            <div class="order-header">
                <h2>#{{ $order->order_number }}</h2>
                <p>{{ $order->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
            <div class="order-body">
                <div class="info-grid">
                    <div class="info-box">
                        <div class="info-label">Penerima</div>
                        <div class="info-value">{{ $order->recipient_name }}</div>
                    </div>
                    <div class="info-box">
                        <div class="info-label">No. HP</div>
                        <div class="info-value">{{ $order->recipient_phone }}</div>
                    </div>
                    <div class="info-box">
                        <div class="info-label">Pembayaran</div>
                        <div class="info-value">{{ $order->payment_label }}</div>
                    </div>
                    <div class="info-box">
                        <div class="info-label">Pengiriman</div>
                        <div class="info-value">{{ $order->shipping_label }}</div>
                    </div>
                </div>

                <div class="timeline">
                    <h3>Status Pesanan</h3>
                    @foreach($steps as $key => $step)
                    @php
                        $idx = array_search($key, $statuses);
                        $state = $idx < $currentIndex ? 'done' : ($idx == $currentIndex ? 'active' : 'pending');
                        if($order->status == 'cancelled') $state = 'pending';
                    @endphp
                    <div class="tl-item">
                        <div class="tl-dot {{ $state }}">{{ $step['icon'] }}</div>
                        <div class="tl-content">
                            <div class="tl-title" style="{{ $state=='pending'?'color:var(--text-light)':'' }}">{{ $step['title'] }}</div>
                            <div class="tl-sub">{{ $step['sub'] }}</div>
                        </div>
                    </div>
                    @endforeach
                    @if($order->status == 'cancelled')
                    <div class="tl-item">
                        <div class="tl-dot" style="background:var(--red);color:#fff">❌</div>
                        <div class="tl-content">
                            <div class="tl-title">Pesanan Dibatalkan</div>
                            <div class="tl-sub">Pesanan ini telah dibatalkan</div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="items-section">
                    <h3>Produk Dipesan</h3>
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
                    <div class="total-box">
                        <span>Total Pembayaran</span>
                        <strong>{{ $order->total_formatted }}</strong>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="alert-error">❌ Nomor pesanan <strong>{{ request('order_number') }}</strong> tidak ditemukan.</div>
        @endif
    @endif
</div>
@endsection