<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_products'   => Product::count(),
            'total_orders'     => Order::count(),
            'pending_orders'   => Order::where('status','pending')->count(),
            'total_revenue' => Order::where('status','!=','cancelled')->sum('total'),
            'low_stock'        => Product::where('stock','<=',5)->where('stock','>',0)->count(),
            'out_of_stock'     => Product::where('stock',0)->count(),
            'total_categories' => Category::count(),
        ];

        $recentOrders     = Order::with('items')->latest()->take(5)->get();
        $lowStockProducts = Product::with('category')->where('stock','<=',5)->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats','recentOrders','lowStockProducts'));
    }
}