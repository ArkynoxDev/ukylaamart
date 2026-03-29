<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->where('is_active', 1);

        if ($request->category) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        if ($request->q) {
            $query->where('name', 'like', '%'.$request->q.'%');
        }

        $sort = $request->sort;
        if ($sort == 'price-asc')  $query->orderBy('price', 'asc');
        elseif ($sort == 'price-desc') $query->orderBy('price', 'desc');
        elseif ($sort == 'name-asc')  $query->orderBy('name', 'asc');
        elseif ($sort == 'name-desc') $query->orderBy('name', 'desc');
        else $query->orderBy('created_at', 'desc');

        $products = $query->get()->map(fn($p) => [
            'id'          => $p->id,
            'name'        => $p->name,
            'slug'        => $p->slug,
            'description' => $p->description,
            'price'       => $p->price,
            'price_formatted' => $p->price_formatted,
            'stock'       => $p->stock,
            'emoji'       => $p->emoji,
            'image_url'   => $p->image_url,
            'is_active'   => $p->is_active,
            'category'    => [
                'id'   => $p->category->id,
                'name' => $p->category->name,
                'slug' => $p->category->slug,
            ],
        ]);

        return response()->json([
            'success' => true,
            'data'    => $products,
        ]);
    }

    public function show($slug)
    {
        $product = Product::with('category')
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data'    => [
                'id'          => $product->id,
                'name'        => $product->name,
                'slug'        => $product->slug,
                'description' => $product->description,
                'price'       => $product->price,
                'price_formatted' => $product->price_formatted,
                'stock'       => $product->stock,
                'emoji'       => $product->emoji,
                'image_url'   => $product->image_url,
                'category'    => [
                    'id'   => $product->category->id,
                    'name' => $product->category->name,
                ],
            ],
        ]);
    }
}