<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->active();

        if ($request->filled('q')) $query->search($request->q);

        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        switch ($request->sort) {
            case 'name-asc':   $query->orderBy('name'); break;
            case 'name-desc':  $query->orderBy('name','desc'); break;
            case 'price-asc':  $query->orderBy('price'); break;
            case 'price-desc': $query->orderBy('price','desc'); break;
            default:           $query->latest(); break;
        }

        $products   = $query->get();
        $categories = Category::withCount(['products' => fn($q) => $q->active()])->get();

        return view('shop.index', compact('products','categories'));
    }

    public function show($slug)
    {
        $product = Product::with('category')->where('slug',$slug)->active()->firstOrFail();
        $related = Product::with('category')->active()
            ->where('category_id',$product->category_id)
            ->where('id','!=',$product->id)->take(4)->get();

        return view('shop.show', compact('product','related'));
    }
}