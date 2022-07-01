<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function index(Request $request)
    {
        $user_id = $request->user_id;
        if($user_id) {
            $carts = Cart::with('product')->where('user_id', $user_id)->get();
            return ResponseFormatter::success($carts);
        } else {
            $carts = Cart::all();
            return ResponseFormatter::success($carts);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer',
                'product_id' => 'required|integer',
                'quantity' => 'required|integer',
                'price' => 'required|integer',
                'total_price' => 'required|integer',
            ]);

            $cart = new Cart();
            $cart->user_id = $request->user_id;
            $cart->product_id = $request->product_id;
            $cart->quantity = $request->quantity;
            $cart->price = $request->price;
            $cart->total_price = $request->total_price;
            $cart->save();

            return ResponseFormatter::success($cart, 'Cart created successfully');
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'user_id' => 'required|integer',
                'product_id' => 'required|integer',
                'quantity' => 'required|integer',
                'price' => 'required|integer',
                'total_price' => 'required|integer',
            ]);

            $cart = Cart::find($id);
            $cart->user_id = $request->user_id;
            $cart->product_id = $request->product_id;
            $cart->quantity = $request->quantity;
            $cart->price = $request->price;
            $cart->total_price = $request->total_price;
            $cart->save();

            return ResponseFormatter::success($cart, 'Cart updated successfully');
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $cart = Cart::find($id);
            $cart->delete();

            return ResponseFormatter::success($cart, 'Cart deleted successfully');
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }
}
