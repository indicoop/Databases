<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\BusinessDetail;
use App\Models\Cooperative;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cooperative_id = Cooperative::where('user_id', $user->id)->first()->id;
        if ($user->role->id == 2) {
            $products = BusinessDetail::with([
                'products',
                'products.productCategory',
            ])->where('cooperative_id', $cooperative_id)->get();
            return ResponseFormatter::success([
                'cooperative' => Cooperative::where('user_id', $user->id)->first(),
                'products' => $products,
            ], 'Products fetched successfully');
        } else {
            return ResponseFormatter::error('Unauthorized', 'Unauthorized', 401);
        }
    }

    public function fetch($id)
    {
        $user = Auth::user();
        $cooperative_id = Cooperative::where('user_id', $user->id)->first()->id;
        if ($user->role->id == 2) {
            $products = BusinessDetail::with([
                'products',
                'products.productCategory',
            ])->where('cooperative_id', $cooperative_id)->where('id', $id)->get();
            return ResponseFormatter::success([
                'cooperative' => Cooperative::where('user_id', $user->id)->first(),
                'products' => $products,
            ], 'Products fetched successfully');
        } else {
            return ResponseFormatter::error('Unauthorized', 'Unauthorized', 401);
        }
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'business_detail_id' => 'required|exists:business_details,id',
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255|unique:products,name',
            'price' => 'required|min:0',
            'stock' => 'required|min:0',
            'description' => 'required|string|max:255',
            'thumbnail' => 'required|max:255',
            'production_date' => 'required|date',
            'discount' => 'nullable|min:0',
            'weight' => 'nullable|min:0',
            'voucher_id' => 'nullable|exists:vouchers,id',
        ]);
        if ($user->role->id == 2) {
            try {
                $product = Product::create($request->all());
                return ResponseFormatter::success($product, 'Product created successfully');
            } catch (Exception $e) {
                return ResponseFormatter::error('Internal Server Error', 'Internal Server Error', 500);
            }
        } else {
            return ResponseFormatter::error('Unauthorized', 'Unauthorized', 401);
        }
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $request->validate([
            'business_detail_id' => 'required|exists:business_details,id',
            'product_category_id' => 'required|exists:product_categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|min:0',
            'stock' => 'required|min:0',
            'description' => 'required|string|max:255',
            'thumbnail' => 'required|max:255',
            'production_date' => 'required|date',
            'discount' => 'nullable|min:0',
            'weight' => 'nullable|min:0',
            'voucher_id' => 'nullable|exists:vouchers,id',
        ]);
        if ($user->role->id == 2) {
            try {
                $product = Product::find($id);
                $product->update($request->all());
                return ResponseFormatter::success($product, 'Product updated successfully');
            } catch (Exception $e) {
                return ResponseFormatter::error('Internal Server Error', 'Internal Server Error', 500);
            }
        } else {
            return ResponseFormatter::error('Unauthorized', 'Unauthorized', 401);
        }
    }

    public function delete($id)
    {
        $user = Auth::user();
        if ($user->role->id == 2) {
            try {
                $product = Product::where('id', $id)->first();
                $product->delete();
                return ResponseFormatter::success($product, 'Product deleted successfully');
            } catch (Exception $e) {
                return ResponseFormatter::error('Internal Server Error', 'Internal Server Error', 500);
            }
        } else {
            return ResponseFormatter::error('Unauthorized', 'Unauthorized', 401);
        }
    }

    public function fetchAllProducts()
    {
        $products = BusinessDetail::with([
            'cooperative',
            'products',
            'business'
        ])->get();
        if ($products) {
            return ResponseFormatter::success($products, 'Products fetched successfully');
        } else {
            return ResponseFormatter::error('Products not found', 'Products not found', 404);
        }
    }

    public function fetchProduct($id)
    {
        $products = BusinessDetail::with([
            'cooperative',
            'products',
            'business'
        ])->where('id', $id)->get();

        if ($products) {
            return ResponseFormatter::success($products, 'Products fetched successfully');
        } else {
            return ResponseFormatter::error('Products not found', 'Products not found', 404);
        }
    }
}
