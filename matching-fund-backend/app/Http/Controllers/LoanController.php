<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Loan;
use Exception;
use Illuminate\Http\Request;

class LoanController extends Controller
{
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            // check if user is cooperative chairman or super admin
            if ($user->role->id == 1 || $user->role->id == 2) {
                $loans = Loan::with([
                    'user',
                    'loanType',
                ])->get();
                return ResponseFormatter::success($loans);
            } else if ($user->role->id == 3) {
                // if role is 3 (member), fetch cooperatives where user is cooperative member
                $loans = $user->with('loan')->where([
                    ['role_id', '=', 3],
                    ['cooperative_id', '=', $user->cooperative_id]
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
            // check if user is cooperative chairman or super admin
            if ($user->role->id == 1 || $user->role->id == 2) {
                $loan = Loan::create($request->all());
                return ResponseFormatter::success($loan);
            } else if ($user->role->id == 3) {
                // if role is 3 (member), fetch cooperatives where user is cooperative member
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

}
