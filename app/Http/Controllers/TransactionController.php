<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Cart;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Exception;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        if ($user->role->id != 4) {
            $transactions = Transaction::with([
                'product',
                'voucher',
                'transactionDetails.user',
                'transactionDetails.courier',
                'transactionDetails.cooperative',
            ])->get();
            if ($transactions) {
                return ResponseFormatter::success($transactions, 'Transactions fetched successfully');
            } else {
                return ResponseFormatter::error('No transaction found', 404);
            }
        } else {
            return ResponseFormatter::error('You are not authorized to perform this action', 401);
        }
    }

    public function create(Request $request)
    {
        $request->validate([
            'voucher_id' => 'nullable',
        ]);
        $user = $request->user();
        try {
            // delete all cart of user if there is any
            $carts = Cart::where([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
            ])->get();

            if ($carts) {
                foreach ($carts as $cart) {
                    $cart->delete();
                }
            }

            $transaction = Transaction::create([
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'destination_address' => $request->destination_address,
                'voucher_id' => $request->voucher_id,
                'note' => $request->note,
            ]);

            if ($transaction) {
                $transactionDetails = TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'user_id' => $user->id,
                    'courier_id' => $request->courier_id,
                    'cooperative_id' => $request->cooperative_id,
                    'total_pay' => $request->total_pay,
                    'payment_method_id' => $request->payment_method_id,
                    'status' => $request->status,
                    'shipping_fee' => $request->shipping_fee,
                ]);
                if ($transactionDetails) {
                    return ResponseFormatter::success($transaction, 'Transaction created successfully');
                } else {
                    return ResponseFormatter::error('Transaction details not created', 500);
                }
            } else {
                return ResponseFormatter::error('Transaction not created', 500);
            }
        } catch (\Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        try {
            $transaction = Transaction::find($id);
            if ($transaction) {
                $transaction->update([
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'destination_address' => $request->destination_address,
                    'voucher_id' => $request->voucher_id,
                    'note' => $request->note,
                ]);
                if ($transaction) {
                    $transactionDetails = TransactionDetail::where([
                        'transaction_id' => $transaction->id,
                        'user_id' => $user->id,
                    ])->update([
                        'courier_id' => $request->courier_id,
                        'cooperative_id' => $request->cooperative_id,
                        'total_pay' => $request->total_pay,
                        'payment_method_id' => $request->payment_method_id,
                        'status' => $request->status,
                        'shipping_fee' => $request->shipping_fee,
                    ]);
                    if ($transactionDetails) {
                        return ResponseFormatter::success($transaction, 'Transaction updated successfully');
                    } else {
                        return ResponseFormatter::error('Transaction details not updated', 500);
                    }
                } else {
                    return ResponseFormatter::error('Transaction not updated', 500);
                }
            } else {
                return ResponseFormatter::error('Transaction not found', 404);
            }
        } catch (\Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function delete(Request $request, $id)
    {
        $user = $request->user();
        if($user->role->id != 4) {
            try {
                $transaction = Transaction::find($id);
                if ($transaction) {
                    $transaction->delete();
                    return ResponseFormatter::success($transaction, 'Transaction deleted successfully');
                } else {
                    return ResponseFormatter::error('Transaction not found', 404);
                }
            } catch (Exception $e) {
                return ResponseFormatter::error($e->getMessage(), 500);
            }
        } else {
            return ResponseFormatter::error('You are not authorized to perform this action', 401);
        }
    }

    public function fetch(Request $request)
    {
        $user = $request->user();
        if ($user->role->id != 4) {
            try {
                $transactions = Transaction::with(['product', 'voucher', 'transactionDetails'])->get();
                if ($transactions) {
                    return ResponseFormatter::success($transactions, 'Transactions fetched successfully');
                } else {
                    return ResponseFormatter::error('Transactions not found', 404);
                }
            } catch (Exception $e) {
                return ResponseFormatter::error($e->getMessage(), 500);
            }
        } else {
            return ResponseFormatter::error('You are not authorized to perform this action', 401);
        }
    }
}
