@extends('layouts.admin')
@section('title','Pesanan')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
    <h2 style="font-family:'Playfair Display',serif;font-size:22px;font-weight:700;color:var(--brown)">Daftar Pesanan</h2>
</div>
<form method="GET" style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="🔍 Cari no. pesanan / nama..." class="form-input" style="flex:1;min-width:200px;max-width:300px">
    <select name="status" class="form-select" style="width:180px" onchange="this.form.submit()">
        <option value="">Semua Status</option>
        <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Menunggu</option>
        <option value="processing" {{ request('status')=='processing'?'selected':'' }}>Diproses</option>
        <option value="shipped" {{ request('status')=='shipped'?'selected':'' }}>Dikirim</option>
        <option value="delivered" {{ request('status')=='delivered'?'selected':'' }}>Selesai</option>
        <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Dibatalkan</option>
    </select>
    <button type="submit" class="btn btn-secondary">Filter</button>
    @if(request()->hasAny(['q','status']))
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Reset</a>
    @endif
</form>
<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr><th>No. Pesanan</th><th>Penerima</th><th>Pembayaran</th><th>Pengiriman</th><th>Total</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($orders as $order)
                @php $s=$order->status_label; @endphp
                <tr>
                    <td style="font-weight:700;color:var(--amber-dark)">#{{ $order->order_number }}</td>
                    <td>
                        <div style="font-weight:600">{{ $order->recipient_name }}</div>
                        <div style="font-size:12px;color:var(--text-light)">{{ $order->recipient_phone }}</div>
                    </td>
                    <td style="font-size:13px">{{ $order->payment_label }}</td>
                    <td style="font-size:13px">{{ $order->shipping_label }}</td>
                    <td style="font-weight:700;color:var(--brown)">{{ $order->total_formatted }}</td>
                    <td><span class="badge badge-{{ $s['color'] }}">{{ $s['label'] }}</span></td>
                    <td style="font-size:12px;color:var(--text-light)">{{ $order->created_at->format('d M Y') }}</td>
                    <td><a href="{{ route('admin.orders.show',$order) }}" class="btn btn-sm btn-secondary">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="8" style="text-align:center;color:var(--text-light);padding:40px">Belum ada pesanan</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border)">{{ $orders->links() }}</div>
    @endif
</div>
@endsection