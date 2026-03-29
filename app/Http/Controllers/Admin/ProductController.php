<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');
        if ($request->filled('q')) $query->where('name','like',"%{$request->q}%");
        if ($request->filled('category')) $query->where('category_id',$request->category);

        $products   = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::all();
        return view('admin.products.index', compact('products','categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'emoji'       => 'required|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'boolean',
        ]);

        $data['slug']      = $this->uniqueSlug($request->name);
        $data['is_active'] = $request->boolean('is_active',true);

        if ($request->hasFile('image')) $data['image'] = $this->uploadImage($request->file('image'));

        Product::create($data);
        return redirect()->route('admin.products.index')->with('success','Produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.form', compact('product','categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'emoji'       => 'required|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active'   => 'boolean',
        ]);

        if ($product->name !== $request->name)
            $data['slug'] = $this->uniqueSlug($request->name, $product->id);

        $data['is_active'] = $request->boolean('is_active',true);

        if ($request->hasFile('image')) {
            if ($product->image) {
                $old = public_path('uploads/products/'.$product->image);
                if (file_exists($old)) unlink($old);
            }
            $data['image'] = $this->uploadImage($request->file('image'));
        }

        $product->update($data);
        return redirect()->route('admin.products.index')->with('success','Produk berhasil diupdate!');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            $path = public_path('uploads/products/'.$product->image);
            if (file_exists($path)) unlink($path);
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with('success','Produk berhasil dihapus!');
    }

    public function toggle(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        $label = $product->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success',"Produk berhasil {$label}!");
    }

    private function uniqueSlug(string $name, $ignoreId = null): string
    {
        $slug  = Str::slug($name);
        $count = 0;
        while (true) {
            $check = $count ? "$slug-$count" : $slug;
            $query = Product::where('slug',$check);
            if ($ignoreId) $query->where('id','!=',$ignoreId);
            if (!$query->exists()) return $check;
            $count++;
        }
    }

    private function uploadImage($file): string
    {
        $filename = time().'_'.Str::random(8).'.'.$file->getClientOriginalExtension();
        $file->move(public_path('uploads/products'), $filename);
        return $filename;
    }
}