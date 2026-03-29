@extends('layouts.admin')
@section('title', isset($product)?'Edit Produk':'Tambah Produk')
@section('styles')
<style>
.emoji-grid{display:grid;grid-template-columns:repeat(10,1fr);gap:6px;margin-top:6px;}
.emoji-btn{width:38px;height:38px;border:1.5px solid var(--border);border-radius:8px;background:var(--cream);cursor:pointer;font-size:20px;transition:.2s;display:flex;align-items:center;justify-content:center;}
.emoji-btn:hover,.emoji-btn.selected{border-color:var(--amber);background:var(--warm);box-shadow:0 0 0 2px var(--amber-light);}
.img-preview{width:100px;height:100px;border-radius:12px;object-fit:cover;border:2px solid var(--border);display:none;margin-top:8px;}
.img-preview.show{display:block;}
</style>
@endsection
@section('content')
<div style="margin-bottom:16px">
    <a href="{{ route('admin.products.index') }}" style="color:var(--amber);font-size:13px">← Kembali ke daftar produk</a>
</div>
<div style="max-width:700px">
    <h2 style="font-family:'Playfair Display',serif;font-size:22px;font-weight:700;color:var(--brown);margin-bottom:20px">
        {{ isset($product)?'✏️ Edit Produk':'➕ Tambah Produk Baru' }}
    </h2>

    <form method="POST"
        action="{{ isset($product)?route('admin.products.update',$product):route('admin.products.store') }}"
        enctype="multipart/form-data">
        @csrf
        @if(isset($product)) @method('PUT') @endif

        <div class="card" style="margin-bottom:16px">
            <div class="card-header"><h3>Informasi Produk</h3></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Nama Produk <span>*</span></label>
                    <input class="form-input" type="text" name="name" value="{{ old('name',$product->name??'') }}" placeholder="Contoh: Kue Nastar Keju" required>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Deskripsi</label>
                    <textarea class="form-textarea" name="description" placeholder="Deskripsi singkat produk...">{{ old('description',$product->description??'') }}</textarea>
                </div>
                <div class="form-row">
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Kategori <span>*</span></label>
                        <select class="form-select" name="category_id" required>
                            <option value="">Pilih kategori</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id',$product->category_id??'')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="is_active">
                            <option value="1" {{ old('is_active',($product->is_active??1)?'1':'0')=='1'?'selected':'' }}>Aktif (tampil di toko)</option>
                            <option value="0" {{ old('is_active',($product->is_active??1)?'1':'0')=='0'?'selected':'' }}>Nonaktif (sembunyikan)</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom:16px">
            <div class="card-header"><h3>Harga & Stok</h3></div>
            <div class="card-body">
                <div class="form-row">
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Harga (Rp) <span>*</span></label>
                        <input class="form-input" type="number" name="price" value="{{ old('price',$product->price??'') }}" placeholder="25000" min="0" required>
                        @error('price')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group" style="margin:0">
                        <label class="form-label">Stok <span>*</span></label>
                        <input class="form-input" type="number" name="stock" value="{{ old('stock',$product->stock??'') }}" placeholder="50" min="0" required>
                        @error('stock')<p class="form-error">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card" style="margin-bottom:20px">
            <div class="card-header"><h3>Foto & Ikon</h3></div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-label">Upload Foto (jpg, png, webp — max 2MB)</label>
                    @if(isset($product) && $product->image_url)
                    <div style="margin-bottom:10px">
                        <img src="{{ $product->image_url }}" style="width:80px;height:80px;border-radius:10px;object-fit:cover;border:2px solid var(--border)">
                        <span style="font-size:12px;color:var(--text-light);margin-left:10px">Foto saat ini</span>
                    </div>
                    @endif
                    <input type="file" name="image" class="form-input" accept="image/*" onchange="previewImg(this)" style="padding:8px">
                    <img id="imgPreview" class="img-preview" alt="Preview">
                    @error('image')<p class="form-error">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Ikon Emoji (jika tidak ada foto)</label>
                    <input type="hidden" name="emoji" id="emojiInput" value="{{ old('emoji',$product->emoji??'🍱') }}" required>
                    <div style="margin-bottom:8px;font-size:13px;color:var(--text-light)">Dipilih: <span id="selectedEmoji" style="font-size:20px">{{ old('emoji',$product->emoji??'🍱') }}</span></div>
                    <div class="emoji-grid" id="emojiGrid"></div>
                </div>
            </div>
        </div>

        <div style="display:flex;gap:10px">
            <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center;padding:12px">
                💾 {{ isset($product)?'Simpan Perubahan':'Tambah Produk' }}
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary" style="padding:12px 20px">Batal</a>
        </div>
    </form>
</div>
@endsection
@section('scripts')
<script>
const EMOJIS=['🍱','🍜','🍚','🍛','🍲','🥘','🥗','🧆','🧇','🥞','🍳','🥚','🍞','🥐','🧁','🎂','🍰','🍩','🍪','🍫','🍬','🍭','🍮','🍯','🥧','🧃','🥤','☕','🍵','🧋','🥛','🧂','🌶️','🧄','🥕','🌽','🍅','🥑','🫙','🥩'];
let selected=document.getElementById('emojiInput').value;
function buildGrid(){
    document.getElementById('emojiGrid').innerHTML=EMOJIS.map(e=>
        `<button type="button" class="emoji-btn ${e===selected?'selected':''}" onclick="pickEmoji('${e}')">${e}</button>`
    ).join('');
}
function pickEmoji(e){selected=e;document.getElementById('emojiInput').value=e;document.getElementById('selectedEmoji').textContent=e;buildGrid();}
function previewImg(input){
    const p=document.getElementById('imgPreview');
    if(input.files&&input.files[0]){const r=new FileReader();r.onload=e=>{p.src=e.target.result;p.classList.add('show');};r.readAsDataURL(input.files[0]);}
}
buildGrid();
</script>
@endsection