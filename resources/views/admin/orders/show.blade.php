@extends('layouts.admin')
@section('title','Detail Pesanan')
@section('content')
<div style="margin-bottom:16px">
    <a href="{{ route('admin.orders.index') }}" style="color:var(--amber);font-size:13px">← Kembali ke daftar pesanan</a>
</div>
@php $s=$order->status_label; @endphp
<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start">
    <div>
        <div class="card" style="margin-bottom:16px">
            <div class="card-header">
                <h3>📦 Pesanan #{{ $order->order_number }}</h3>
                <span class="badge badge-{{ $s['color'] }}">{{ $s['label'] }}</span>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
                    <div style="background:var(--warm);border-radius:10px;padding:12px">
                        <div style="font-size:11px;color:var(--text-light);font-weight:700;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Penerima</div>
                        <div style="font-weight:700;font-size:15px">{{ $order->recipient_name }}</div>
                        <div style="font-size:13px;color:var(--text-mid)">{{ $order->recipient_phone }}</div>
                    </div>
                    <div style="background:var(--warm);border-radius:10px;padding:12px">
                        <div style="font-size:11px;color:var(--text-light);font-weight:700;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Tanggal</div>
                        <div style="font-weight:700;font-size:15px">{{ $order->created_at->format('d M Y') }}</div>
                        <div style="font-size:13px;color:var(--text-mid)">{{ $order->created_at->format('H:i') }} WIB</div>
                    </div>
                    <div style="background:var(--warm);border-radius:10px;padding:12px">
                        <div style="font-size:11px;color:var(--text-light);font-weight:700;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Pembayaran</div>
                        <div style="font-weight:700">{{ $order->payment_label }}</div>
                    </div>
                    <div style="background:var(--warm);border-radius:10px;padding:12px">
                        <div style="font-size:11px;color:var(--text-light);font-weight:700;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Pengiriman</div>
                        <div style="font-weight:700">{{ $order->shipping_label }}</div>
                    </div>
                </div>
                <div style="background:var(--warm);border-radius:10px;padding:12px;margin-top:12px">
                    <div style="font-size:11px;color:var(--text-light);font-weight:700;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px">Alamat</div>
                    <div style="font-size:14px">{{ $order->recipient_address }}</div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h3>🛍️ Produk Dipesan</h3></div>
            <div class="table-wrap">
                <table>
                    <thead><tr><th>Produk</th><th>Harga</th><th>Qty</th><th>Subtotal</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr>
                            <td>
                                <span style="font-size:20px;margin-right:8px">{{ $item->product_emoji }}</span>
                                <span style="font-weight:600">{{ $item->product_name }}</span>
                            </td>
                            <td>Rp {{ number_format($item->price,0,',','.') }}</td>
                            <td>x{{ $item->qty }}</td>
                            <td style="font-weight:700;color:var(--amber-dark)">Rp {{ number_format($item->subtotal,0,',','.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="padding:16px 20px;border-top:1px solid var(--border)">
                <div style="display:flex;justify-content:space-between;font-size:14px;color:var(--text-mid);margin-bottom:6px">
                    <span>Subtotal</span><span>Rp {{ number_format($order->subtotal,0,',','.') }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:14px;color:var(--text-mid);margin-bottom:10px">
                    <span>Ongkos Kirim</span><span>{{ $order->shipping_cost>0?'Rp '.number_format($order->shipping_cost,0,',','.') :'Gratis' }}</span>
                </div>
                <div style="display:flex;justify-content:space-between;font-size:20px;font-weight:700;color:var(--brown);font-family:'Playfair Display',serif;padding-top:10px;border-top:2px solid var(--border)">
                    <span>Total</span><span>{{ $order->total_formatted }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>⚙️ Update Status</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.orders.status',$order) }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Status Pesanan</label>
                    <select class="form-select" name="status">
                        @foreach(['pending'=>'Menunggu','processing'=>'Diproses','shipped'=>'Dikirim','delivered'=>'Selesai','cancelled'=>'Dibatalkan'] as $val=>$label)
                        <option value="{{ $val }}" {{ $order->status==$val?'selected':'' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Update Status</button>
            </form>
          <div style="margin-top:10px">
                <a href="{{ route('admin.orders.invoice',$order) }}" target="_blank" 
                   class="btn btn-secondary" style="width:100%;justify-content:center">
                    🖨️ Cetak Invoice PDF
                </a>
            </div>
        </div>
    </div>
</div>
@endsection