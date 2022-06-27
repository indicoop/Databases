<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
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
        $user = $request->user();
        try {

            $request->validate([
                'product_id' => 'required|exists:products,id',
                'voucher_id' => 'required|exists:vouchers,id',
                'transaction_date' => 'required|date',
                'total_pay' => 'required|numeric',
                'payment_method_id' => 'required|exists:payment_methods,id',
                'status' => 'required|string',
                'shipping_fee' => 'nullable|numeric',
                'transaction_details' => 'required|array',
                'transaction_details.*.user_id' => 'required|exists:users,id',
                'transaction_details.*.courier_id' => 'required|exists:couriers,id',
                'transaction_details.*.cooperative_id' => 'required|exists:cooperatives,id',
                'transaction_details.*.total_pay' => 'required|numeric',
                'transaction_details.*.payment_method_id' => 'required|exists:payment_methods,id',
                'transaction_details.*.status' => 'required|string',
                'transaction_details.*.shipping_fee' => 'required|numeric',
                'transaction_details.*.transaction_date' => 'required|date',
            ]);

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
                    'transaction_date' => $request->transaction_date,
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
        if ($user->role->id != 4) {
            try {
                $request->validate([
                    'product_id' => 'nullable|exists:products,id',
                    'voucher_id' => 'nullable|exists:vouchers,id',
                    'transaction_date' => 'nullable|date',
                    'total_pay' => 'nullable|numeric',
                    'payment_method_id' => 'nullable|exists:payment_methods,id',
                    'status' => 'nullable|string',
                    'shipping_fee' => 'nullable|numeric',
                    'transaction_details' => 'nullable|array',
                    'transaction_details.*.user_id' => 'nullable|exists:users,id',
                    'transaction_details.*.courier_id' => 'nullable|exists:couriers,id',
                    'transaction_details.*.cooperative_id' => 'nullable|exists:cooperatives,id',
                    'transaction_details.*.total_pay' => 'nullable|numeric',
                    'transaction_details.*.payment_method_id' => 'nullable|exists:payment_methods,id',
                    'transaction_details.*.status' => 'nullable|string',
                    'transaction_details.*.shipping_fee' => 'nullable|numeric',
                    'transaction_details.*.transaction_date' => 'nullable|date',
                ]);

                $transaction = Transaction::find($id);
                if ($transaction) {
                    $transaction->product_id = $request->product_id;
                    $transaction->quantity = $request->quantity;
                    $transaction->destination_address = $request->destination_address;
                    $transaction->voucher_id = $request->voucher_id;
                    $transaction->note = $request->note;
                    $transaction->save();
                    if ($transaction) {
                        $transactionDetails = TransactionDetail::where('transaction_id', $transaction->id)->get();
                        if ($transactionDetails) {
                            foreach ($transactionDetails as $transactionDetail) {
                                $transactionDetail->user_id = $request->user_id;
                                $transactionDetail->courier_id = $request->courier_id;
                                $transactionDetail->cooperative_id = $request->cooperative_id;
                                $transactionDetail->total_pay = $request->total_pay;
                                $transactionDetail->payment_method_id = $request->payment_method_id;
                                $transactionDetail->status = $request->status;
                                $transactionDetail->shipping_fee = $request->shipping_fee;
                                $transactionDetail->transaction_date = $request->transaction_date;
                                $transactionDetail->save();
                            }
                            return ResponseFormatter::success($transaction, 'Transaction updated successfully');
                        } else {
                            return ResponseFormatter::error('Transaction details not found', 404);
                        }
                    } else {
                        return ResponseFormatter::error('Transaction not updated', 500);
                    }
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
