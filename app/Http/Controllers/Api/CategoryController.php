<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount(['products' => fn($q) => $q->where('is_active', 1)])
            ->get()
            ->map(fn($c) => [
                'id'             => $c->id,
                'name'           => $c->name,
                'slug'           => $c->slug,
                'products_count' => $c->products_count,
            ]);

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }
}