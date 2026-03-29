<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:sans-serif;font-size:12px;color:#2a1a0a;background:#fff;padding:20px;}
.header{text-align:center;border-bottom:2px solid #4a2e0e;padding-bottom:12px;margin-bottom:16px;}
.logo{font-size:22px;font-weight:900;color:#4a2e0e;}
.logo span{color:#d4862a;}
.tagline{font-size:10px;color:#9a7a5a;margin-top:2px;}
.invoice-title{font-size:16px;font-weight:700;color:#4a2e0e;margin:12px 0 4px;}
.invoice-number{font-size:12px;color:#d4862a;font-weight:700;}
.info-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;}
.info-box{background:#faf6f0;border-radius:6px;padding:10px;border:1px solid #e8d8c0;}
.info-label{font-size:9px;font-weight:700;color:#9a7a5a;text-transform:uppercase;letter-spacing:.5px;margin-bottom:4px;}
.info-value{font-size:12px;font-weight:600;color:#2a1a0a;}
.info-sub{font-size:11px;color:#6b4a2a;margin-top:2px;}
table{width:100%;border-collapse:collapse;margin-bottom:12px;}
th{background:#4a2e0e;color:#fff;padding:7px 8px;text-align:left;font-size:10px;text-transform:uppercase;letter-spacing:.3px;}
td{padding:8px;border-bottom:1px solid #e8d8c0;font-size:11px;vertical-align:middle;}
tr:last-child td{border-bottom:none;}
.text-right{text-align:right;}
.totals{background:#faf6f0;border-radius:6px;padding:10px;border:1px solid #e8d8c0;margin-bottom:16px;}
.total-row{display:flex;justify-content:space-between;font-size:11px;color:#6b4a2a;margin-bottom:5px;}
.total-final{display:flex;justify-content:space-between;font-size:15px;font-weight:900;color:#4a2e0e;padding-top:8px;border-top:2px solid #4a2e0e;margin-top:6px;}
.status-badge{display:inline-block;padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;background:#d4edda;color:#155724;}
.footer{text-align:center;font-size:10px;color:#9a7a5a;border-top:1px solid #e8d8c0;padding-top:10px;}
.footer strong{color:#4a2e0e;}
</style>
</head>
<body>
<div class="header">
    <div class="logo">Ukyla<span>amart</span></div>
    <div class="tagline">Toko Rumahan Terpercaya — Sidoarjo</div>
    <div class="invoice-title">INVOICE</div>
    <div class="invoice-number">#{{ $order->order_number }}</div>
</div>

<div class="info-grid">
    <div class="info-box">
        <div class="info-label">Penerima</div>
        <div class="info-value">{{ $order->recipient_name }}</div>
        <div class="info-sub">{{ $order->recipient_phone }}</div>
        <div class="info-sub" style="margin-top:4px">{{ $order->recipient_address }}</div>
    </div>
    <div class="info-box">
        <div class="info-label">Info Pesanan</div>
        <div class="info-value">{{ $order->created_at->format('d M Y') }}</div>
        <div class="info-sub">{{ $order->payment_label }}</div>
        <div class="info-sub">{{ $order->shipping_label }}</div>
        <div style="margin-top:6px"><span class="status-badge">{{ $order->status_label['label'] }}</span></div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>Produk</th>
            <th class="text-right">Harga</th>
            <th class="text-right">Qty</th>
            <th class="text-right">Subtotal</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr>
            <td>{{ $item->product_emoji }} {{ $item->product_name }}</td>
            <td class="text-right">Rp {{ number_format($item->price,0,',','.') }}</td>
            <td class="text-right">{{ $item->qty }}</td>
            <td class="text-right" style="font-weight:700">Rp {{ number_format($item->subtotal,0,',','.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="totals">
    <div class="total-row"><span>Subtotal</span><span>Rp {{ number_format($order->subtotal,0,',','.') }}</span></div>
    <div class="total-row"><span>Ongkos Kirim</span><span>{{ $order->shipping_cost>0?'Rp '.number_format($order->shipping_cost,0,',','.'):'Gratis' }}</span></div>
    <div class="total-final"><span>TOTAL</span><span>{{ $order->total_formatted }}</span></div>
</div>

<div class="footer">
    Terima kasih telah berbelanja di <strong>Ukylaamart</strong>! 🙏<br>
    Pertanyaan? Hubungi kami via WhatsApp.<br>
    <span style="color:#d4862a;font-weight:700">Dicetak {{ now()->format('d M Y H:i') }}</span>
</div>
</body>
</html>