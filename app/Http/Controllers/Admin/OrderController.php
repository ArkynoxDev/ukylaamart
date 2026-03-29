<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::latest();

        if ($request->q) {
            $query->where('order_number', 'like', '%'.$request->q.'%')
                  ->orWhere('recipient_name', 'like', '%'.$request->q.'%');
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $order->load('items');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:pending,processing,shipped,delivered,cancelled']);
        $order->update(['status' => $request->status]);
        return back()->with('success', 'Status pesanan berhasil diupdate!');
    }

    public function invoice(Order $order)
    {
        $order->load('items');
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.orders.invoice', compact('order'));
        $pdf->setPaper('a5', 'portrait');
        return $pdf->stream('invoice-'.$order->order_number.'.pdf');
    }
}