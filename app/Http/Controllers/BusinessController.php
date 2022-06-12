<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Business;
use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        try {
            if ($user->role->id == 1) {
                if ($request->category) {
                    $business = Business::where('category', 'like', "%$request->category%")->get();
                } else {
                    $business = Business::all();
                }
                return ResponseFormatter::success($business, 'Business fetched successfully');
            } else {
                return ResponseFormatter::error('Unauthorized', 'You are not authorized to perform this action', 401);
            }
        } catch (Exception $th) {
            return ResponseFormatter::error('Internal Server Error', $th->getMessage(), 500);
        }
    }

    public function fetch($id)
    {
        $user = Auth::user();
        if ($id) {
            if ($user->role->id == 1) {
                try {
                    $bussiness = Business::with('businessDetails')->findOrFail($id);
                    return ResponseFormatter::success($bussiness, 'Business fetched successfully');
                } catch (Exception $th) {
                    return ResponseFormatter::error($th->getMessage(), 'Business not found', 404);
                }
            }
        }
    }

    public function create(Request $request)
    {
        $user = $request->user();
        try {
            if ($user->role->id == 1) {
                $request->validate([
                    'name' => 'required|string|max:255|unique:business,name',
                    'category' => 'required|string|max:255',
                ], [
                    'name.required' => 'Business name is required',
                    'name.unique' => 'Business name already exists',
                    'category.required' => 'Business category is required',
                ]);

                Business::create($request->all());
                $business = Business::where('name', $request->name)->first();

                return ResponseFormatter::success($business, 'Business created successfully');
            } else {
                return ResponseFormatter::error('Unauthorized', 'You are not authorized to perform this action', 401);
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 'Error creating business');
        }
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $business = Business::find($id);
        $name = $request->name;
        $category = $request->category;

        try {
            if ($user->role->id == 1) {
                if ($request->all()) {
                    $request->validate([
                        'name' => 'required|string|max:255|unique:business,name,' . $id,
                    ], [
                        'name.required' => 'Business name is required',
                        'name.unique' => 'Business name already exists',
                    ]);
                    $business = Business::find($id);
                    $business->fill($request->all());
                    $business->save();

                    return ResponseFormatter::success($business, 'Business updated successfully');
                } else if ($name) {
                    $request->validate([
                        'name' => 'required|string|max:255|unique:business,name,' . $id,
                    ], [
                        'name.required' => 'Business name is required',
                        'name.unique' => 'Business name already exists',
                    ]);

                    $business = Business::find($id);
                    $business->name = $name;
                    $business->save();

                    return ResponseFormatter::success($business, 'Business updated successfully');
                } else if ($category) {
                    $business = Business::find($id);
                    $business->category = $category;
                    $business->save();

                    return ResponseFormatter::success($business, 'Business updated successfully');
                } else {
                    return ResponseFormatter::error('Unauthorized', 'You are not authorized to perform this action', 401);
                }
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 'Error updating business');
        }
    }

    public function delete($id)
    {
        $user = Auth::user();
        try {
            if ($user->role->id == 1) {
                $business = Business::where('id', $id)->delete();
                return ResponseFormatter::success($business, 'Business deleted successfully');
            } else {
                return ResponseFormatter::error('Unauthorized', 'You are not authorized to perform this action', 401);
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 'Error deleting business');
        }
    }
}
