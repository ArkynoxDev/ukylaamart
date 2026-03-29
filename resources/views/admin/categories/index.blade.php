@extends('layouts.admin')
@section('title','Kategori')
@section('content')
<div style="display:grid;grid-template-columns:320px 1fr;gap:20px;align-items:start">
    <div class="card">
        <div class="card-header"><h3>➕ Tambah Kategori</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Nama Kategori</label>
                    <input class="form-input" type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Kue & Snack" required>
                    @error('name')<p class="form-error" style="color:var(--red);font-size:12px;margin-top:4px">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">Tambah</button>
            </form>
        </div>
    </div>
    <div class="card">
        <div class="card-header"><h3>🏷️ Daftar Kategori</h3></div>
        <div class="table-wrap">
            <table>
                <thead><tr><th>Nama</th><th>Jumlah Produk</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($categories as $cat)
                    <tr>
                        <td style="font-weight:600">{{ $cat->name }}</td>
                        <td><span class="badge badge-info">{{ $cat->products_count }} produk</span></td>
                        <td>
                            <div style="display:flex;gap:6px">
                                <button class="btn btn-sm btn-secondary" onclick="editCat({{ $cat->id }},'{{ addslashes($cat->name) }}')">✏️ Edit</button>
                                <form method="POST" action="{{ route('admin.categories.destroy',$cat) }}" style="display:inline"
                                    onsubmit="return confirm('Hapus kategori {{ addslashes($cat->name) }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" style="text-align:center;color:var(--text-light);padding:24px">Belum ada kategori</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="editModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:999;align-items:center;justify-content:center">
    <div style="background:#fff;border-radius:16px;padding:28px;width:360px">
        <h3 style="font-family:'Playfair Display',serif;font-size:18px;color:var(--brown);margin-bottom:16px">Edit Kategori</h3>
        <form method="POST" id="editForm">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Kategori</label>
                <input class="form-input" type="text" name="name" id="editName" required>
            </div>
            <div style="display:flex;gap:8px">
                <button type="submit" class="btn btn-primary" style="flex:1;justify-content:center">Simpan</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('editModal').style.display='none'">Batal</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script>
function editCat(id,name){
    document.getElementById('editName').value=name;
    document.getElementById('editForm').action='/ukylaamart/public/admin/categories/'+id;
    document.getElementById('editModal').style.display='flex';
}
</script>
@endsection