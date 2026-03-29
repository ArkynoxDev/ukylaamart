@extends('layouts.app')
@section('title','Belanja')
@section('styles')
<style>
.hero{background:linear-gradient(135deg,var(--warm),#fce8c8);padding:40px 32px;display:flex;align-items:center;gap:40px;border-bottom:1px solid var(--border);position:relative;overflow:hidden;}
.hero::before{content:'';position:absolute;right:-60px;top:-60px;width:300px;height:300px;background:var(--amber-light);border-radius:50%;opacity:.2;}
.hero h1{font-family:'Playfair Display',serif;font-size:36px;font-weight:900;color:var(--brown);line-height:1.1;}
.hero h1 span{color:var(--amber);}
.hero p{color:var(--text-mid);margin-top:8px;font-size:15px;max-width:400px;}
.hero-emoji{font-size:80px;}
.controls{background:#fff;padding:16px 32px;display:flex;align-items:center;gap:12px;border-bottom:1px solid var(--border);flex-wrap:wrap;box-shadow:0 2px 8px var(--shadow);}
.search-wrap{position:relative;flex:1;min-width:200px;}
.search-wrap input{width:100%;padding:10px 14px 10px 38px;border:1.5px solid var(--border);border-radius:10px;font-size:14px;font-family:'DM Sans',sans-serif;background:var(--cream);outline:none;transition:.2s;}
.search-wrap input:focus{border-color:var(--amber);background:#fff;}
.search-icon{position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--text-light);}
.sort-select{padding:10px 14px;border:1.5px solid var(--border);border-radius:10px;font-size:14px;font-family:'DM Sans',sans-serif;background:var(--cream);outline:none;cursor:pointer;}
.filter-tabs{display:flex;gap:6px;flex-wrap:wrap;}
.filter-tab{padding:7px 14px;border-radius:20px;border:1.5px solid var(--border);background:transparent;cursor:pointer;font-size:13px;font-family:'DM Sans',sans-serif;color:var(--text-mid);transition:.2s;font-weight:500;}
.filter-tab.active{background:var(--brown);color:#fff;border-color:var(--brown);}
.filter-tab:hover:not(.active){border-color:var(--amber);color:var(--amber);}
.products-section{padding:28px 32px;}
.section-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;}
.section-title{font-family:'Playfair Display',serif;font-size:24px;font-weight:700;color:var(--brown);}
.product-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:20px;}
.product-card{background:#fff;border-radius:16px;overflow:hidden;border:1.5px solid var(--border);transition:.25s;box-shadow:0 2px 8px var(--shadow);}
.product-card:hover{transform:translateY(-4px);box-shadow:0 12px 32px var(--shadow);border-color:var(--amber-light);}
.product-img{height:160px;display:flex;align-items:center;justify-content:center;font-size:64px;background:linear-gradient(135deg,var(--warm),#fff);position:relative;}
.product-img img{width:100%;height:100%;object-fit:cover;}
.stock-badge{position:absolute;top:10px;right:10px;background:var(--green);color:#fff;font-size:10px;font-weight:600;padding:3px 8px;border-radius:20px;}
.stock-badge.low{background:var(--amber);}
.stock-badge.out{background:var(--red);}
.product-info{padding:14px;}
.product-category{font-size:10px;font-weight:600;color:var(--amber);text-transform:uppercase;letter-spacing:.8px;margin-bottom:4px;}
.product-name{font-size:15px;font-weight:600;color:var(--text);margin-bottom:4px;line-height:1.3;}
.product-name a:hover{color:var(--amber);}
.product-desc{font-size:12px;color:var(--text-light);margin-bottom:10px;line-height:1.4;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.product-footer{display:flex;align-items:center;justify-content:space-between;}
.product-price{font-size:17px;font-weight:700;color:var(--amber-dark);font-family:'Playfair Display',serif;}
.btn-cart{background:var(--brown);color:#fff;border:none;width:34px;height:34px;border-radius:8px;font-size:16px;cursor:pointer;transition:.2s;display:flex;align-items:center;justify-content:center;}
.btn-cart:hover{background:var(--amber);}
.btn-cart:disabled{background:#ccc;cursor:not-allowed;}
.empty-state{text-align:center;padding:60px 20px;color:var(--text-light);grid-column:1/-1;}
.empty-state .ei{font-size:60px;margin-bottom:12px;}
</style>
@endsection

@section('content')
<div class="hero">
    <div>
        <h1>Belanja <span>Segar</span><br>dari Rumah 🌿</h1>
        <p>Produk rumahan berkualitas langsung dari dapur kami. Segar, higienis, dan penuh cinta.</p>
    </div>
    <div class="hero-emoji">🧆</div>
</div>

<div class="controls">
    <form method="GET" action="{{ route('shop.index') }}" id="filterForm" style="display:contents">
        <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..." id="searchInput">
        </div>
        <select name="sort" class="sort-select" onchange="document.getElementById('filterForm').submit()">
            <option value="">Urutkan</option>
            <option value="name-asc"   {{ request('sort')=='name-asc'?'selected':'' }}>Nama A–Z</option>
            <option value="name-desc"  {{ request('sort')=='name-desc'?'selected':'' }}>Nama Z–A</option>
            <option value="price-asc"  {{ request('sort')=='price-asc'?'selected':'' }}>Harga Terendah</option>
            <option value="price-desc" {{ request('sort')=='price-desc'?'selected':'' }}>Harga Tertinggi</option>
        </select>
        <div class="filter-tabs">
            <a href="{{ route('shop.index', array_merge(request()->except('category','page'),[])) }}"
               class="filter-tab {{ !request('category')?'active':'' }}">Semua</a>
            @foreach($categories as $cat)
            <a href="{{ route('shop.index', array_merge(request()->except('category','page'),['category'=>$cat->slug])) }}"
               class="filter-tab {{ request('category')==$cat->slug?'active':'' }}">
               {{ $cat->name }} <span style="opacity:.6;font-size:11px">({{ $cat->products_count }})</span>
            </a>
            @endforeach
        </div>
    </form>
</div>

<section class="products-section">
    <div class="section-header">
        <h2 class="section-title">Produk Kami</h2>
        <span style="font-size:13px;color:var(--text-light)">{{ $products->count() }} produk</span>
    </div>
    <div class="product-grid">
        @forelse($products as $product)
        <div class="product-card">
            <div class="product-img">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                @else
                    {{ $product->emoji }}
                @endif
                @if($product->stock==0)
                    <span class="stock-badge out">Habis</span>
                @elseif($product->stock<=5)
                    <span class="stock-badge low">Sisa {{ $product->stock }}</span>
                @else
                    <span class="stock-badge">Stok {{ $product->stock }}</span>
                @endif
            </div>
            <div class="product-info">
                <div class="product-category">{{ $product->category->name }}</div>
                <div class="product-name"><a href="{{ route('shop.show',$product->slug) }}">{{ $product->name }}</a></div>
                <div class="product-desc">{{ $product->description }}</div>
                <div class="product-footer">
                    <div class="product-price">{{ $product->price_formatted }}</div>
                    <button class="btn-cart" onclick="addToCart({{ $product->id }})" {{ $product->stock==0?'disabled':'' }}>🛒</button>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state"><div class="ei">🔍</div><p>Produk tidak ditemukan.</p></div>
        @endforelse
    </div>
</section>
@endsection

@section('scripts')
<script>
let searchTimer;
document.getElementById('searchInput').addEventListener('input',function(){
    clearTimeout(searchTimer);
    searchTimer=setTimeout(()=>document.getElementById('filterForm').submit(),500);
});
</script>
@endsection