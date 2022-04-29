<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Stash;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StashController extends Controller
{
    public function create(Request $request)
    {
        $user = $request->user();
        $request->validate([
            'beginning_balance' => 'required|numeric',
            'ending_balance' => 'required|numeric',
            'stash_date' => 'required|date',
            'stash_amount' => 'required|numeric',
        ], [
            'beginning_balance.required' => 'Beginning balance is required',
            'beginning_balance.numeric' => 'Beginning balance must be numeric',
            'ending_balance.required' => 'Ending balance is required',
            'ending_balance.numeric' => 'Ending balance must be numeric',
            'stash_date.required' => 'Stash date is required',
            'stash_date.date' => 'Stash date must be a valid date',
            'stash_amount.required' => 'Stash amount is required',
            'stash_amount.numeric' => 'Stash amount must be numeric',
        ]);
        try {
            $stash = Stash::create([
                'user_id' => $user->id,
                'beginning_balance' => $request->beginning_balance,
                'ending_balance' => $request->ending_balance,
                'stash_date' => $request->stash_date,
                'stash_amount' => $request->stash_amount,
            ]);
            return ResponseFormatter::success($stash, 'Stash created successfully');
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 'Error creating stash');
        }
    }
}
