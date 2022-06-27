<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\BusinessDetail;
use App\Models\Cooperative;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            // count total number of cooperatives in transaction_details table
            $total_transaction = TransactionDetail::where('cooperative_id', $id)->where('status', 'success')->count();
            $total_quantity_sold = Transaction::selectRaw('sum(quantity) as total_quantity')->whereHas('transactionDetails', function ($query) {
                $query->where('status', 'success');
            })->groupBy('product_id')->first();
            // total product of cooperatives have
            $total_product = Product::whereHas('businessDetail', function ($query) use ($id) {
                $query->where('cooperative_id', $id);
            })->count();

            return ResponseFormatter::success([
                'cooperative' => $cooperative,
                'total_transaction' => $total_transaction,
                'total_quantity_sold' => (int) $total_quantity_sold->total_quantity,
                'total_product' => $total_product
            ]);
        }
    }

    public function getActiveCooperatives()
    {
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
                    'certificate' => 'required|mimes:pdf,jpg,jpeg,png',
                    'legal_entity_certificate' => 'required|mimes:pdf,jpg,jpeg,png',
                    'profile_picture' => 'required|mimes:pdf,jpg,jpeg,png',
                ]);
                // check if cooperative chairman is already registered
                $cooperative_chairman = Cooperative::where([
                    ['user_id', '=', $user->id],
                ])->first();
                if ($cooperative_chairman) {
                    return ResponseFormatter::error('You are already registered as a cooperative chairman');
                } else {
                    if ($request->hasFile('profile_picture') && $request->hasFile('certificate') && $request->hasFile('legal_entity_certificate')) {
                        $profile_picture = $request->file('profile_picture');
                        $certificate = $request->file('certificate');
                        $legal_entity_certificate = $request->file('legal_entity_certificate');

                        // generate random string for file name
                        $file_name = uniqid() . '.' . $profile_picture->getClientOriginalExtension();
                        $file_name_certificate = uniqid() . '.' . $certificate->getClientOriginalExtension();
                        $file_name_legal_entity_certificate = uniqid() . '.' . $legal_entity_certificate->getClientOriginalExtension();

                        // move file to public/uploads/cooperative/profile_picture
<<<<<<< HEAD
                        $profile_picture->move(public_path('uploads/cooperative/profile_picture'), $file_name);
                        $certificate->move(public_path('uploads/cooperative/certificate'), $file_name_certificate);
                        $legal_entity_certificate->move(public_path('uploads/cooperative/legal_entity_certificate'), $file_name_legal_entity_certificate);


=======
                        $profile_picture->move(public_path('profile_picture'), $file_name);
                        $certificate->move(public_path('certificate'), $file_name_certificate);
                        $legal_entity_certificate->move(public_path('legal_entity_certificate'), $file_name_legal_entity_certificate);
>>>>>>> a5b7f309b3f78885277dbffc02d850186bf63f73

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
<<<<<<< HEAD
                            'certificate' => 'uploads/cooperative/profile_picture/' . $file_name,
                            'legal_entity_certificate' => 'uploads/cooperative/legal_entity_certificate/' . $file_name_legal_entity_certificate,
                            'profile_picture' => 'uploads/cooperative/profile_picture/' . $file_name_certificate,
=======
                            'certificate' => 'certificate/' . $file_name,
                            'legal_entity_certificate' => 'legal_entity_certificate/' . $file_name_legal_entity_certificate,
                            'profile_picture' => 'profile_picture/' . $file_name_certificate,
>>>>>>> a5b7f309b3f78885277dbffc02d850186bf63f73
                            'is_verified' => false,
                        ]);
                        return ResponseFormatter::success($cooperative, 'Cooperative created successfully');
                    } else {
                        return ResponseFormatter::error('Please upload all required files');
                    }
                }
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 'Error creating cooperative');
        }
    }

    public function update(Request $request, $id)
    {
<<<<<<< HEAD
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
                'certificate' => 'required|mimes:pdf,jpg,jpeg,png',
                'legal_entity_certificate' => 'required|mimes:pdf,jpg,jpeg,png',
                'profile_picture' => 'required|mimes:pdf,jpg,jpeg,png',
            ]);

            if ($request->hasFile('certificate') && $request->hasFile('legal_entity_certificate') && $request->hasFile('profile_picture')) {


                // delete old files
                $cooperative = Cooperative::find($id);
                if ($cooperative->profile_picture) {
                    $old_file_name_legal = explode('/', $cooperative->profile_picture);
                    $old_file_name_legal = end($old_file_name_legal);
                    if (file_exists(public_path('uploads/cooperative/profile_picture/' . $old_file_name_legal))) {
                        unlink(public_path('uploads/cooperative/profile_picture/' . $old_file_name_legal));
                    }
                }
                if ($cooperative->certificate) {
                    $old_file_name_legal = explode('/', $cooperative->certificate);
                    $old_file_name_legal = end($old_file_name_legal);
                    if (file_exists(public_path('uploads/cooperative/certificate/' . $old_file_name_legal))) {
                        unlink(public_path('uploads/cooperative/certificate/' . $old_file_name_legal));
                    }
                }
                if ($cooperative->legal_entity_certificate) {
                    $old_file_name_legal = explode('/', $cooperative->legal_entity_certificate);
                    $old_file_name_legal = end($old_file_name_legal);
                    if (file_exists(public_path('uploads/cooperative/legal_entity_certificate/' . $old_file_name_legal))) {
                        unlink(public_path('uploads/cooperative/legal_entity_certificate/' . $old_file_name_legal));
                    }
                }

                $profile_picture = $request->file('profile_picture');
                $certificate = $request->file('certificate');
                $legal_entity_certificate = $request->file('legal_entity_certificate');

                // generate random string for file name
                $file_name = uniqid() . '.' . $profile_picture->getClientOriginalExtension();
                $file_name_certificate = uniqid() . '.' . $certificate->getClientOriginalExtension();
                $file_name_legal_entity_certificate = uniqid() . '.' . $legal_entity_certificate->getClientOriginalExtension();

                // move file to public/uploads/cooperative/profile_picture
                $profile_picture->move(public_path('uploads/cooperative/profile_picture'), $file_name);
                $certificate->move(public_path('uploads/cooperative/certificate'), $file_name_certificate);
                $legal_entity_certificate->move(public_path('uploads/cooperative/legal_entity_certificate'), $file_name_legal_entity_certificate);

                $cooperative = Cooperative::find($id);
                $cooperative->name = $request->name;
                $cooperative->effective_date = $request->effective_date;
                // $cooperative->registration_number = $request->registration_number;
                $cooperative->status_grade = $request->status_grade;
                $cooperative->date_of_establishment = $request->date_of_establishment;
                $cooperative->address = $request->address;
                $cooperative->email = $request->email;
                $cooperative->phone_number = $request->phone_number;
                $cooperative->form_of_cooperative = $request->form_of_cooperative;
                $cooperative->certificate = 'uploads/cooperative/profile_picture/' . $file_name;
                $cooperative->legal_entity_certificate = 'uploads/cooperative/legal_entity_certificate/' . $file_name_legal_entity_certificate;
                $cooperative->profile_picture = 'uploads/cooperative/profile_picture/' . $file_name_certificate;
                $cooperative->save();
            } else {
                return ResponseFormatter::error('Please upload all required files');
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
        return ResponseFormatter::success($cooperative);
=======
        $user = $request->user();
        try {
            //  if role is 2 (cooperative chairman)
            if ($user->role_id == 2) {
                $request->validate([
                    'name' => 'required|string|max:255|unique:cooperatives,name,' . $id,
                    'status' => 'required|boolean',
                    'effective_date' => 'required|date',
                    'status_grade' => 'required|string|max:255',
                    'date_of_establishment' => 'required|date',
                    'address' => 'required|string|max:255',
                    'email' => 'required|string|max:255|unique:cooperatives,email,' . $id,
                    'phone_number' => 'required|string|max:255',
                    'form_of_cooperative' => 'required|string|max:255',
                    'certificate' => 'required|mimes:pdf,jpg,jpeg,png',
                    'legal_entity_certificate' => 'required|mimes:pdf,jpg,jpeg,png',
                    'profile_picture' => 'required|mimes:pdf,jpg,jpeg,png',
                ]);

                if ($request->hasFile('profile_picture') && $request->hasFile('certificate') && $request->hasFile('legal_entity_certificate')) {

                    $cooperative_old = Cooperative::find($id);
                    $old_profile_picture = $cooperative_old->profile_picture;
                    $old_certificate = $cooperative_old->certificate;
                    $old_legal_entity_certificate = $cooperative_old->legal_entity_certificate;

                    // delete old files
                    if(file_exists(public_path($old_profile_picture))){
                        unlink(public_path($old_profile_picture));
                    }
                    if(file_exists(public_path($old_certificate))){
                        unlink(public_path($old_certificate));
                    }
                    if(file_exists(public_path($old_legal_entity_certificate))){
                        unlink(public_path($old_legal_entity_certificate));
                    }

                    $profile_picture = $request->file('profile_picture');
                    $certificate = $request->file('certificate');
                    $legal_entity_certificate = $request->file('legal_entity_certificate');

                    // generate random string for file name
                    $file_name = uniqid() . '.' . $profile_picture->getClientOriginalExtension();
                    $file_name_certificate = uniqid() . '.' . $certificate->getClientOriginalExtension();
                    $file_name_legal_entity_certificate = uniqid() . '.' . $legal_entity_certificate->getClientOriginalExtension();

                    $profile_picture->move(public_path('profile_picture'), $file_name);
                    $certificate->move(public_path('certificate'), $file_name_certificate);
                    $legal_entity_certificate->move(public_path('legal_entity_certificate'), $file_name_legal_entity_certificate);

                    $cooperative = Cooperative::find($id);
                    $cooperative->update([
                        'user_id' => $user->id,
                        'name' => $request->name,
                        'registration_number' => uniqid(),
                        'status' => $request->status,
                        'effective_date' => $request->effective_date,
                        'status_grade' => $request->status_grade,
                        'date_of_establishment' => $request->date_of_establishment,
                        'address' => $request->address,
                        'email' => $request->email,
                        'phone_number' => $request->phone_number,
                        'form_of_cooperative' => $request->form_of_cooperative,
                        'certificate' => 'profile_picture/' . $file_name,
                        'legal_entity_certificate' => 'legal_entity_certificate/' . $file_name_legal_entity_certificate,
                        'profile_picture' => 'profile_picture/' . $file_name_certificate,
                        'is_verified' => false,
                    ]);
                    return ResponseFormatter::success($cooperative, 'Cooperative updated successfully');
                } else {
                    return ResponseFormatter::error('Please upload all required files');
                }
            } else {
                return ResponseFormatter::error('You are not authorized to perform this action');
            }
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage(), 'Error updating cooperative');
        }
>>>>>>> a5b7f309b3f78885277dbffc02d850186bf63f73
    }
}
