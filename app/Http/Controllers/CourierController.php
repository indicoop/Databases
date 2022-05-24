<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Courier;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourierController extends Controller
{
    public function index()
    {
        $couriers = Courier::all();
        if ($couriers) {
            return ResponseFormatter::success($couriers, 'Couriers fetched successfully');
        } else {
            return ResponseFormatter::error('No courier found', 404);
        }
    }

    public function fetch(Request $request)
    {
        $courier = Courier::find($request->id);
        if ($courier) {
            return ResponseFormatter::success($courier, 'Courier fetched successfully');
        } else {
            return ResponseFormatter::error('Courier not found', 404);
        }
    }

    public function create(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'name' => 'required|string|unique:couriers,name',
            'phone_number' => 'required|string|unique:couriers,phone_number',
        ], [
            'name.required' => 'Courier name is required',
            'name.unique' => 'Courier name already exists',
            'phone_number.required' => 'Courier phone number is required',
            'phone_number.unique' => 'Courier phone number already exists',
        ]);
        if ($user->role->id == 1) {
            $courier = Courier::create($request->all());
            if ($courier) {
                return ResponseFormatter::success($courier, 'Courier created successfully');
            } else {
                return ResponseFormatter::error('Courier not created', 500);
            }
        } else {
            return ResponseFormatter::error('You are not authorized to perform this action', 401);
        }
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        if ($user->role->id == 1) {
            $courier = Courier::find($id);
            if ($courier) {
                $courier->update($request->all());
                return ResponseFormatter::success($courier, 'Courier updated successfully');
            } else {
                return ResponseFormatter::error('Courier not found', 404);
            }
        } else {
            return ResponseFormatter::error('You are not authorized to perform this action', 401);
        }
    }

    public function delete($id)
    {
        $user = Auth::user();
        $courier = Courier::find($id);
        try {
            if ($user->role->id == 1) {
                $courier->delete();
                return ResponseFormatter::success($courier, 'Courier deleted successfully');
            } else {
                return ResponseFormatter::error('You are not authorized to perform this action', 401);
            }
        } catch (Exception $e) {
            return ResponseFormatter::error('Courier not deleted', 500);
        }
    }
}
