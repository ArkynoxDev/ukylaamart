<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function checkout()
    {
        $cart = session()->get('cart',[]);
        if (empty($cart)) return redirect()->route('shop.index')->with('error','Keranjang kosong!');

        $cart     = CartController::enrichCart($cart);
        $subtotal = collect($cart)->sum('subtotal');
        return view('shop.checkout', compact('cart','subtotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_name'    => 'required|string|max:255',
            'recipient_phone'   => 'required|string|max:30',
            'recipient_address' => 'required|string',
            'payment_method'    => 'required|in:cash,cashless',
            'shipping_method'   => 'required|in:cod,jne',
        ]);

        $cart = session()->get('cart',[]);
        if (empty($cart)) return redirect()->route('shop.index')->with('error','Keranjang kosong!');

        $items    = CartController::enrichCart($cart);
        $subtotal = collect($items)->sum('subtotal');
        $shipping = $request->shipping_method === 'jne' ? 15000 : 0;
        $total    = $subtotal + $shipping;

        DB::beginTransaction();
        try {
            $order = Order::create([
                'order_number'      => 'DK'.strtoupper(uniqid()),
                'recipient_name'    => $request->recipient_name,
                'recipient_phone'   => $request->recipient_phone,
                'recipient_address' => $request->recipient_address,
                'payment_method'    => $request->payment_method,
                'shipping_method'   => $request->shipping_method,
                'subtotal'          => $subtotal,
                'shipping_cost'     => $shipping,
                'total'             => $total,
                'status'            => 'pending',
                'notes'             => $request->notes,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item['product']->id,
                    'product_name'  => $item['product']->name,
                    'product_emoji' => $item['product']->emoji,
                    'qty'           => $item['qty'],
                    'price'         => $item['price'],
                    'subtotal'      => $item['subtotal'],
                ]);
                Product::where('id',$item['product']->id)->decrement('stock',$item['qty']);
            }

            DB::commit();
            session()->forget('cart');
            return redirect()->route('order.show',$order->order_number)->with('success','Pesanan berhasil!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error','Terjadi kesalahan: '.$e->getMessage());
        }
    }

    public function show($orderNumber)
    {
        $order = Order::with('items')->where('order_number',$orderNumber)->firstOrFail();
        return view('shop.order-success', compact('order'));
    }
        public function track(Request $request)
    {
        $order = null;
        if ($request->order_number) {
            $order = Order::with('items')
                ->where('order_number', $request->order_number)
                ->first();
        }
        return view('shop.order-track', compact('order'));
    }
}