<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Cooperative;
use Exception;
use Illuminate\Http\Request;

class CooperativeController extends Controller
{
    public function fetch(Request $request)
    {
        try {
            $user = $request->user();
            // if role is 1 (super admin), fetch all cooperatives
            if ($user->role->id == 1) {
                $cooperatives = Cooperative::all();
                return ResponseFormatter::success($cooperatives);
            } else if ($user->role->id == 2) {
                // if role is 2 (admin), fetch cooperatives where user is cooperative chairman
                $cooperatives = $user->with('cooperative')->where([
                    ['role_id', '=', 2],
                    ['cooperative_id', '=', $user->cooperative_id]
                ])->get();

                $cooperative_detail = Cooperative::with([
                    'users',
                    'businessDetails',
                    'vouchers',
                    'transactionDetails',
                ])->where('id', $user->cooperative_id)->first();

                return ResponseFormatter::success([
                    'cooperative' => $cooperatives,
                    'cooperative_detail' => $cooperative_detail
                ]);
            } else if ($user->role->id == 3) {
                // if role is 3 (member), fetch cooperatives where user is cooperative member
                $cooperatives = $user->with('cooperative')->where([
                    ['role_id', '=', 3],
                    ['cooperative_id', '=', $user->cooperative_id]
                ])->get();
                return ResponseFormatter::success($cooperatives);
            }
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }

    public function fetchActiveCooperatives($id)
    {
        if ($id) {
            $cooperative = Cooperative::with([
                'users',
                'businessDetails',
                'vouchers',
                'transactionDetails',
            ])->where('id', $id)->first();
            return ResponseFormatter::success($cooperative);
        } else {
            try {
                $cooperatives = Cooperative::with([
                    'users',
                    'businessDetails',
                    'vouchers',
                    'transactionDetails',
                ])->where([
                    ['status', '=', true],
                    ['is_verified', '=', true],
                ])->get();

                return ResponseFormatter::success($cooperatives);
            } catch (Exception $th) {
                return ResponseFormatter::error($th->getMessage());
            }
        }
    }

    public function create(Request $request)
    {
        $user = $request->user();
        try {
            //  if role is 2 (cooperative chairman)
            if ($user->role_id == 2) {
                $request->validate([
                    'name' => 'required|string|max:255|unique:cooperatives,name',
                    'status' => 'required|boolean',
                    'effective_date' => 'required|date',
                    'status_grade' => 'required|string|max:255',
                    'date_of_establishment' => 'required|date',
                    'address' => 'required|string|max:255',
                    'email' => 'required|string|max:255|unique:cooperatives,email',
                    'phone_number' => 'required|string|max:255',
                    'form_of_cooperative' => 'required|string|max:255',
                    'certificate' => 'required|string|max:255',
                    'legal_entity_certificate' => 'required|string|max:255',
                ]);
                // check if cooperative chairman is already registered
                $cooperative_chairman = Cooperative::where([
                    ['user_id', '=', $user->id],
                ])->first();
                if ($cooperative_chairman) {
                    return ResponseFormatter::error('You are already registered as a cooperative chairman');
                } else {
                    $cooperative = Cooperative::create([
                        'user_id' => $user->id,
                        'name' => $request->name,
                        'registration_number' => $request->name . '-' . date('Y:m:d H:i:s'),
                        'status' => $request->status,
                        'effective_date' => $request->effective_date,
                        'status_grade' => $request->status_grade,
                        'date_of_establishment' => $request->date_of_establishment,
                        'address' => $request->address,
                        'email' => $request->email,
                        'phone_number' => $request->phone_number,
                        'form_of_cooperative' => $request->form_of_cooperative,
                        'certificate' => $request->certificate,
                        'legal_entity_certificate' => $request->legal_entity_certificate,
                        'is_verified' => false,
                    ]);
                    return ResponseFormatter::success($cooperative, 'Cooperative created successfully');
                }
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 'Error creating cooperative');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = $request->user();
            $request->validate([
                'name' => 'required|string',
                'effective_date' => 'required|date',
                // 'registration_number' => 'required|string',
                'status_grade' => 'required|string',
                'date_of_establishment' => 'required|date',
                'address' => 'required|string',
                'email' => 'required|email',
                'phone_number' => 'required|string',
                'form_of_cooperative' => 'required|string',
                'certificate' => 'required|string',
                'legal_entity_certificate' => 'required|string',
            ]);

            Cooperative::where('id', $id)->update([
                'user_id' => $user->id,
                'name' => $request->name,
                'registration_number' => $request->name . '-' . date('Y:m:d H:i:s'),
                'effective_date' => $request->effective_date,
                'status_grade' => $request->status_grade,
                'date_of_establishment' => $request->date_of_establishment,
                'address' => $request->address,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'form_of_cooperative' => $request->form_of_cooperative,
                'certificate' => $request->certificate,
                'legal_entity_certificate' => $request->legal_entity_certificate,
            ]);

            $cooperative = Cooperative::with([
                'users',
                'businessDetails',
                'vouchers',
                'transactionDetails',
            ])->where('id', $request->id)->first();
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
            return ResponseFormatter::success($cooperative);
    }
}
