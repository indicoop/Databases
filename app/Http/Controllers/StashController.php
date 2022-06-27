<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Stash;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StashController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        if ($user->role->id != 4) {
            $stashes = Stash::where('user_id', $user->id)->get();
            if ($stashes->isEmpty()) {
                return ResponseFormatter::error('No stashes found.');
            } else {
                return ResponseFormatter::success($stashes, 'Stashes found.');
            }
        } else {
            return ResponseFormatter::error('You are not authorized to access this resource.');
        }
    }

    public function fetch($id)
    {
        $user = Auth::user();
        $stash = Stash::where('user_id', $user->id)->where('id', $id)->first();
        if ($user->role->id != 4) {
            if ($stash) {
                return ResponseFormatter::success($stash, 'Stash found.');
            } else {
                return ResponseFormatter::error('Stash not found.');
            }
        } else {
            return ResponseFormatter::error('You are not authorized to view this stash.');
        }
    }

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
            if ($user->role->id != 4) {
                $stash = Stash::create([
                    'user_id' => $user->id,
                    'beginning_balance' => $request->beginning_balance,
                    'ending_balance' => $request->ending_balance,
                    'stash_date' => $request->stash_date,
                    'stash_amount' => $request->stash_amount,
                ]);
                return ResponseFormatter::success($stash, 'Stash created successfully');
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 'Error creating stash');
        }
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        try {
            $stash = Stash::where('id', $id)->first();
            if ($user->role->id != 4) {
                $stash->update($request->all());
                return ResponseFormatter::success($stash, 'Stash updated successfully');
            } else {
                return ResponseFormatter::error('Unauthorized', 'You are not authorized to update this stash');
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 'Error updating stash');
        }
    }

    public function delete($id)
    {
        $user = Auth::user();
        if ($user->role->id != 4) {
            $stash = Stash::where('id', $id)->first();
            $stash->delete();
            return ResponseFormatter::success($stash, 'Stash deleted successfully');
        } else {
            return ResponseFormatter::error('Unauthorized', 'You are not authorized to delete this stash');
        }
    }
}
