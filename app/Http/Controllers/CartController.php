<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    private function getCart() { return session()->get('cart',[]); }
    private function saveCart(array $cart) { session()->put('cart',$cart); }

    public function index()
{
    $cart  = $this->getCart();
    $items = $this->enrichCart($cart);
    $total = collect($items)->sum('subtotal');
    $cart  = $items; // alias biar blade bisa pakai $cart
    return view('shop.cart', compact('cart','items','total'));
}

    public function add(Request $request)
    {
        $request->validate(['product_id'=>'required|exists:products,id']);
        $product = Product::findOrFail($request->product_id);

        if (!$product->is_active || $product->stock <= 0)
            return back()->with('error','Produk tidak tersedia.');

        $cart = $this->getCart();
        $id   = $product->id;
        $qty  = $request->qty ?? 1;

        if (isset($cart[$id])) {
            $cart[$id]['qty'] = min($cart[$id]['qty'] + $qty, $product->stock);
        } else {
            $cart[$id] = ['qty' => min($qty, $product->stock)];
        }

        $this->saveCart($cart);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $product->name.' ditambahkan ke keranjang!',
                'count'   => collect($cart)->sum('qty'),
            ]);
        }

        return back()->with('success', $product->name.' ditambahkan!');
    }

    public function update(Request $request)
    {
        $request->validate(['product_id'=>'required','qty'=>'required|integer|min:0']);
        $cart    = $this->getCart();
        $id      = $request->product_id;
        $product = Product::find($id);

        if (!$product || $request->qty <= 0) unset($cart[$id]);
        else $cart[$id]['qty'] = min($request->qty, $product->stock);

        $this->saveCart($cart);

        if ($request->ajax()) {
            $items = $this->enrichCart($cart);
            return response()->json([
                'success'  => true,
                'subtotal' => 'Rp '.number_format(collect($items)->sum('subtotal'),0,',','.'),
                'count'    => collect($cart)->sum('qty'),
            ]);
        }
        return redirect()->route('cart.index');
    }

    public function remove(Request $request)
    {
        $cart = $this->getCart();
        unset($cart[$request->product_id]);
        $this->saveCart($cart);

        if ($request->ajax()) {
            $items = $this->enrichCart($cart);
            return response()->json([
                'success'  => true,
                'subtotal' => 'Rp '.number_format(collect($items)->sum('subtotal'),0,',','.'),
                'count'    => collect($cart)->sum('qty'),
            ]);
        }
        return redirect()->route('cart.index')->with('success','Item dihapus.');
    }

    public function clear()
    {
        session()->forget('cart');
        return redirect()->route('cart.index');
    }

    public static function enrichCart(array $cart): array
    {
        $items = [];
        foreach ($cart as $productId => $data) {
            $product = Product::with('category')->find($productId);
            if ($product) {
                $qty     = $data['qty'];
                $items[] = [
                    'product'  => $product,
                    'qty'      => $qty,
                    'price'    => $product->price,
                    'subtotal' => $product->price * $qty,
                ];
            }
        }
        return $items;
    }
}