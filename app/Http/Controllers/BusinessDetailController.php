<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\BusinessDetail;
use App\Models\Cooperative;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\VarDumper\VarDumper;

class BusinessDetailController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();
        $cooperative_id = Cooperative::where('user_id', $user->id)->first()->id;
        $business_id = $request->input('business_id');

        $request->validate([
            'business_id' => 'exists:business,id|required',
        ], [
            'business_id.exists' => 'Business not found',
            'business_id.required' => 'Business is required',
        ]);

        try {
            $business = BusinessDetail::where([
                ['cooperative_id', $cooperative_id],
                ['business_id', $business_id]
            ])->first();
            if ($business) {
                return ResponseFormatter::error('Business already exists');
            } else {
                if ($user->role->id == 2) {
                    $business_detail = new BusinessDetail();
                    $business_detail->cooperative_id = $cooperative_id;
                    $business_detail->business_id = $business_id;
                    $business_detail->save();

                    return ResponseFormatter::success($business_detail, 'Business created successfully');
                } else {
                    return ResponseFormatter::error('You are not authorized to perform this action.');
                }
            }
        } catch (\Throwable $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $request->validate([
            'business_id' => 'required|exists:business,id',
        ], [
            'business_id.required' => 'Business is required',
            'business_id.exists' => 'Business not found',
        ]);
        try {
            if ($user->role->id == 2) {
                $business_detail = BusinessDetail::where([
                    ['id', $id]
                ])->first();
                if ($business_detail->business_id == $request->input('business_id')) {
                    return ResponseFormatter::error('Please select a different business, this one is already linked to this cooperative.');
                } else {
                    if ($business_detail) {
                        $business_detail->business_id = $request->input('business_id');
                        $business_detail->save();

                        return ResponseFormatter::success($business_detail, 'Business updated successfully');
                    } else {
                        return ResponseFormatter::error('Business not found');
                    }
                }
            } else {
                return ResponseFormatter::error('You are not authorized to perform this action.');
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();
        try {
            $business_detail = BusinessDetail::where([
                ['id', $id]
            ])->first();
            if ($business_detail) {
                if ($user->role->id == 2) {
                    $business_detail->delete();
                    return ResponseFormatter::success($business_detail, 'Business deleted successfully');
                } else {
                    return ResponseFormatter::error('You are not authorized to perform this action.');
                }
            } else {
                return ResponseFormatter::error('Business not found');
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 'Error');
        }
    }

    public function index(Request $request)
    {
        $user = $request->user();
        try {
            if ($user->role->id == 2) {
                $business_details = BusinessDetail::with([
                    'cooperative',
                    'business',
                    'products'
                ])->where('cooperative_id', Cooperative::where('user_id', $user->id)->first()->id)->get();
                return ResponseFormatter::success($business_details, 'Businesses fetched successfully');
            } else {
                return ResponseFormatter::error('You are not authorized to perform this action.');
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 'Error');
        }
    }

    public function fetch(Request $request, $id)
    {
        $user = $request->user();
        try {
            if ($user->role->id == 2) {
                if ($id) {
                    $business_detail = BusinessDetail::with([
                        'cooperative',
                        'business',
                        'products'
                    ])->where('id', $id)->first();
                    return ResponseFormatter::success($business_detail, 'Business fetched successfully');
                } else {
                    $business_details = BusinessDetail::with([
                        'cooperative',
                        'business',
                        'products'
                    ])->where('cooperative_id', Cooperative::where('user_id', $user->id)->first()->id)->get();
                    return ResponseFormatter::success($business_details, 'Businesses fetched successfully');
                }
            } else {
                return ResponseFormatter::error('You are not authorized to perform this action.');
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 'Error');
        }
    }
}
