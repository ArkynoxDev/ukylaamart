@extends('layouts.admin')
@section('title','Produk')
@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
    <div>
        <h2 style="font-family:'Playfair Display',serif;font-size:22px;font-weight:700;color:var(--brown)">Kelola Produk</h2>
        <p style="font-size:13px;color:var(--text-light);margin-top:2px">{{ $products->total() }} total produk</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">＋ Tambah Produk</a>
</div>

<form method="GET" style="display:flex;gap:10px;margin-bottom:16px;flex-wrap:wrap">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="🔍 Cari produk..." class="form-input" style="flex:1;min-width:180px;max-width:300px">
    <select name="category" class="form-select" style="width:180px" onchange="this.form.submit()">
        <option value="">Semua Kategori</option>
        @foreach($categories as $cat)
        <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
        @endforeach
    </select>
    <button type="submit" class="btn btn-secondary">Filter</button>
    @if(request()->hasAny(['q','category']))
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Reset</a>
    @endif
</form>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Produk</th><th>Kategori</th><th>Harga</th><th>Stok</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px">
                            <div style="width:44px;height:44px;background:var(--warm);border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:24px;flex-shrink:0;overflow:hidden">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" style="width:100%;height:100%;object-fit:cover">
                                @else
                                    {{ $product->emoji }}
                                @endif
                            </div>
                            <div>
                                <div style="font-weight:600;font-size:14px">{{ $product->name }}</div>
                                <div style="font-size:12px;color:var(--text-light);max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $product->description }}</div>
                            </div>
                        </div>
                    </td>
                    <td><span class="badge badge-secondary">{{ $product->category->name }}</span></td>
                    <td style="font-weight:600;color:var(--amber-dark)">{{ $product->price_formatted }}</td>
                    <td>
                        @if($product->stock==0)
                            <span class="badge badge-danger">Habis</span>
                        @elseif($product->stock<=5)
                            <span class="badge badge-warning">{{ $product->stock }} pcs</span>
                        @else
                            <span class="badge badge-success">{{ $product->stock }} pcs</span>
                        @endif
                    </td>
                    <td>
                        @if($product->is_active)
                            <span class="badge badge-success">Aktif</span>
                        @else
                            <span class="badge badge-danger">Nonaktif</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:6px;flex-wrap:wrap">
                            <a href="{{ route('admin.products.edit',$product) }}" class="btn btn-sm btn-secondary">✏️ Edit</a>
                            <form method="POST" action="{{ route('admin.products.toggle',$product) }}" style="display:inline">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $product->is_active?'btn-secondary':'btn-success' }}">
                                    {{ $product->is_active?'🚫 Nonaktif':'✅ Aktif' }}
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.products.destroy',$product) }}" style="display:inline"
                                onsubmit="return confirm('Hapus produk {{ addslashes($product->name) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-light)">
                    Tidak ada produk. <a href="{{ route('admin.products.create') }}" style="color:var(--amber)">Tambah sekarang</a>
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border)">{{ $products->links() }}</div>
    @endif
</div>
@endsection