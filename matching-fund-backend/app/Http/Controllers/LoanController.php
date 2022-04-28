<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Loan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            // check if user is cooperative chairman or super admin
            if ($user->role->id == 1) {
                $loans = Loan::with([
                    'user',
                    'loanType',
                ])->get();
                return ResponseFormatter::success($loans);
            } else if ($user->role->id == 2) {
                // if role is 2 (member), fetch cooperatives where user is cooperative chairman
                $loans = Loan::where([
                    'user_id' => $user->id,
                ])->with([
                    'user',
                    'loanType',
                ])->get();
                return ResponseFormatter::success($loans);
            } else if ($user->role->id == 3) {
                // if role is 3 (member), fetch cooperatives where user is cooperative member
                $loans = Loan::where([
                    'user_id' => $user->id,
                ])->with([
                    'user',
                    'loanType',
                ])->get();
                return ResponseFormatter::success($loans);
            } else {
                return ResponseFormatter::error('Unauthorized', 401);
            }

        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function fetch($id)
    {
        try {
            $loans = Loan::where('id', $id)->with([
                'user',
                'loanType',
            ])->get();
    
            return ResponseFormatter::success($loans);
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $user = $request->user();
            // if role is 2 cooperative chairman or 3 member
            if ( $user->role->id == 2 || $user->role->id == 3) {
                // create loan by cooperative member id
                $loan = Loan::create([
                    'user_id' => $user->id,
                    'loan_date' => $request->loan_date,
                    'amount' => $request->amount,
                    'installment_principal' => $request->installment_principal,
                    'installment_interest' => $request->installment_interest,
                    'total_installment' => $request->total_installment,
                    'installment_remaining' => $request->installment_remaining,
                    'loan_type_id' => $request->loan_type_id,
                ]);
                return ResponseFormatter::success($loan, 'Loan created successfully');
            } else {
                return ResponseFormatter::error('Unauthorized', 401);
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 'Error creating loan');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // check if user is cooperative chairman
            $user = $request->user();
            if ($user->role->id == 2) {
                $loan = Loan::findOrFail($id);
                $loan->update($request->all());
                return ResponseFormatter::success($loan, 'Loan updated successfully');
            } else {
                return ResponseFormatter::error('Unauthorized', 401);
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 'Error updating loan');
        }
    }

    public function delete(Request $request ,$id)
    {
        try {
            $user = $request->user();
            if($user->role->id == 2) {
                $loan = Loan::findOrFail($id);
                $loan->delete();
                return ResponseFormatter::success($loan, 'Loan deleted successfully');
            } else {
                return ResponseFormatter::error('Unauthorized', 401);
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

}
