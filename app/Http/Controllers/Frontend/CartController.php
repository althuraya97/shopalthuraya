<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // عرض محتويات السلة
    public function index() {
        $cart = session()->get('cart', []);
        return view('frontend.cart.index', compact('cart'));
    }

    // إضافة منتج للسلة
    public function add(Request $request) {
        $product = Product::findOrFail($request->product_id);
        $cart = session()->get('cart', []);

        // معرف فريد للمنتج مع مقاسه (للسماح بإضافة نفس المنتج بمقاسين مختلفين)
        $cartItemId = $product->id . '-' . ($request->size ?? 'default');

        if(isset($cart[$cartItemId])) {
            $cart[$cartItemId]['quantity'] += $request->quantity;
        } else {
            $cart[$cartItemId] = [
                "id" => $product->id,
                "name" => $product->name,
                "quantity" => $request->quantity,
                "price" => $product->price,
                "size" => $request->size,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'تمت إضافة المنتج للسلة بنجاح!');
    }

    // حذف منتج من السلة
    public function remove($id) {
        $cart = session()->get('cart');
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }
        return back()->with('success', 'تم حذف المنتج من السلة');
    }
}
