@extends('layouts.admin')
@section('title','Dashboard')
@section('content')
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">📦</div>
        <div class="stat-value">{{ $stats['total_products'] }}</div>
        <div class="stat-label">Total Produk</div>
    </div>
    <div class="stat-card">
        <div class="stat-icon">🛒</div>
        <div class="stat-value">{{ $stats['total_orders'] }}</div>
        <div class="stat-label">Total Pesanan</div>
    </div>
    <div class="stat-card" style="border-color:#ffeaa7">
        <div class="stat-icon">⏳</div>
        <div class="stat-value" style="color:var(--amber)">{{ $stats['pending_orders'] }}</div>
        <div class="stat-label">Menunggu Proses</div>
    </div>
    <div class="stat-card" style="border-color:#c3e6cb">
        <div class="stat-icon">💰</div>
        <div class="stat-value" style="font-size:18px;color:var(--green)">Rp {{ number_format($stats['total_revenue'],0,',','.') }}</div>
        <div class="stat-label">Total Pendapatan</div>
    </div>
    <div class="stat-card" style="border-color:#ffc107">
        <div class="stat-icon">⚠️</div>
        <div class="stat-value" style="color:#856404">{{ $stats['low_stock'] }}</div>
        <div class="stat-label">Stok Menipis</div>
    </div>
    <div class="stat-card" style="border-color:#f5c6cb">
        <div class="stat-icon">🚫</div>
        <div class="stat-value" style="color:var(--red)">{{ $stats['out_of_stock'] }}</div>
        <div class="stat-label">Stok Habis</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
    <div class="card">
        <div class="card-header">
            <h3>📦 Pesanan Terbaru</h3>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-secondary">Lihat Semua</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>No. Pesanan</th><th>Nama</th><th>Total</th><th>Status</th></tr></thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    @php $s = $order->status_label; @endphp
                    <tr>
                        <td><a href="{{ route('admin.orders.show',$order) }}" style="color:var(--amber);font-weight:600">#{{ $order->order_number }}</a></td>
                        <td>{{ $order->recipient_name }}</td>
                        <td style="font-weight:600">{{ $order->total_formatted }}</td>
                        <td><span class="badge badge-{{ $s['color'] }}">{{ $s['label'] }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" style="text-align:center;color:var(--text-light);padding:24px">Belum ada pesanan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>⚠️ Stok Menipis</h3>
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-secondary">Kelola Produk</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Produk</th><th>Kategori</th><th>Stok</th></tr></thead>
                <tbody>
                    @forelse($lowStockProducts as $product)
                    <tr>
                        <td>
                            <span style="margin-right:6px">{{ $product->emoji }}</span>
                            <a href="{{ route('admin.products.edit',$product) }}" style="color:var(--amber);font-weight:600">{{ $product->name }}</a>
                        </td>
                        <td style="color:var(--text-light);font-size:12px">{{ $product->category->name }}</td>
                        <td><span class="badge {{ $product->stock==0?'badge-danger':'badge-warning' }}">{{ $product->stock==0?'Habis':$product->stock.' pcs' }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;color:var(--text-light);padding:24px">Semua stok aman ✅</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection