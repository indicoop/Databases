<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Installment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstallmentController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role->id != 4) {
            $installments = Installment::with('loan')->get();
            if ($installments) {
                return ResponseFormatter::success($installments, 'Installments fetched successfully');
            } else {
                return ResponseFormatter::error('No installment found', 404);
            }
        } else {
            return ResponseFormatter::error('You are not authorized to perform this action', 401);
        }
    }

    public function fetch($id)
    {
        $user = Auth::user();
        if ($user->role->id != 4) {
            $installment = Installment::with('loan')->find($id);
            if ($installment) {
                return ResponseFormatter::success($installment, 'Installment fetched successfully');
            } else {
                return ResponseFormatter::error('Installment not found', 404);
            }
        } else {
            return ResponseFormatter::error('You are not authorized to perform this action', 401);
        }
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        if ($user->role->id != 4) {
            try {
                $request->validate([
                    'loan_id' => 'required|exists:loans,id',
                    'installment' => 'required|string',
                    'installment_number' => 'required|integer',
                    'pay_date' => 'required|date',
                    'lateness_date' => 'nullable|date',
                    'total_installment' => 'required|numeric',
                    'interest' => 'required|numeric',
                    'fine' => 'required|numeric',
                    'total_pay' => 'required|numeric',
                ]);
                Installment::create($request->all());
                return ResponseFormatter::success('Installment created successfully');
            } catch (Exception $th) {
                return ResponseFormatter::error($th->getMessage(), 400);
            }
        } else {
            return ResponseFormatter::error('You are not authorized to perform this action', 401);
        }
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        try {
            if ($user->role->id != 4) {
                $installment = Installment::find($id);
                if ($installment) {
                    $request->validate([
                        'loan_id' => 'required|exists:loans,id',
                        'installment' => 'required|string',
                        'installment_number' => 'required|integer',
                        'pay_date' => 'required|date',
                        'lateness_date' => 'nullable|date',
                        'total_installment' => 'required|numeric',
                        'interest' => 'required|numeric',
                        'fine' => 'required|numeric',
                        'total_pay' => 'required|numeric',
                    ]);
                    $installment->update($request->all());
                    return ResponseFormatter::success('Installment updated successfully');
                } else {
                    return ResponseFormatter::error('Installment not found', 404);
                }
            } else {
                return ResponseFormatter::error('You are not authorized to perform this action', 401);
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 400);
        }
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();
        try {
            if ($user->role->id != 4) {
                $installment = Installment::find($id);
                if ($installment) {
                    $installment->delete();
                    return ResponseFormatter::success('Installment deleted successfully');
                } else {
                    return ResponseFormatter::error('Installment not found', 404);
                }
            } else {
                return ResponseFormatter::error('You are not authorized to perform this action', 401);
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 400);
        }
    }
}
