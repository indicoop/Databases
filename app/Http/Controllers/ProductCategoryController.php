<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\ProductCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCategoryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role->id == 1) {
            $product_categories = ProductCategory::all();
            return ResponseFormatter::success($product_categories, 'Product categories retrieved successfully');
        } else {
            return ResponseFormatter::error('You are not authorized to perform this action.');
        }
    }

    public function fetch($id)
    {
        $user = Auth::user();
        if ($user->role->id == 1) {
            $product_categories = ProductCategory::where('id', $id)->first();
            return ResponseFormatter::success($product_categories, 'Product categories retrieved successfully');
        } else {
            return ResponseFormatter::error('You are not authorized to perform this action.');
        }
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name',
            'thumbnail' => 'required|string|max:255',
        ]);

        if ($user->role->id == 1) {
            try {
                $product_category = ProductCategory::create($request->all());
                return ResponseFormatter::success($product_category, 'Product category created successfully');
            } catch (Exception $th) {
                return ResponseFormatter::error('Product category could not be created');
            }
        } else {
            return ResponseFormatter::error('You are not authorized to perform this action.');
        }
    }
    public function update(Request $request, $id)
    {
        $user = $request->user();
        /*
            check if user have role 1
            if request has name, check if name is unique, if not, return error, if yes, update name
            if request has thumbnail, update thumbnail, if not, return error
            if request has no name or thumbnail, return error
            if request has name and thumbnail, update name and thumbnail, if not, return error
        */
        $request->validate([
            'name' => 'string|max:255|unique:product_categories,name,' . $id,
            'thumbnail' => 'string|max:255',
        ]);

        if ($user->role->id == 1) {
            try {
                $product_category = ProductCategory::find($id);
                if ($request->has('name')) {
                    $product_category->name = $request->name;
                }
                if ($request->has('thumbnail')) {
                    $product_category->thumbnail = $request->thumbnail;
                }
                $product_category->save();
                return ResponseFormatter::success($product_category, 'Product category updated successfully');
            } catch (Exception $th) {
                return ResponseFormatter::error('Product category could not be updated');
            }
        } else {
            return ResponseFormatter::error('You are not authorized to perform this action.');
        }
    }
    
    public function delete($id)
    {
        $user = Auth::user();
        if ($user->role->id == 1) {
            try {
                $product_category = ProductCategory::find($id);
                $product_category->delete();
                return ResponseFormatter::success($product_category, 'Product category deleted successfully');
            } catch (Exception $th) {
                return ResponseFormatter::error('Product category could not be deleted');
            }
        } else {
            return ResponseFormatter::error('You are not authorized to perform this action.');
        }
    }
}
