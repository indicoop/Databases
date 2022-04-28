<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Cooperative;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
                'role_id' => 'required|integer',
                'cooperative_id' => 'nullable|integer',
                'credit_card_number' => 'nullable|string',
                'phone_number' => 'nullable|string',
                'address' => 'required|string',
            ]);

            // var_dump((int) $request->role_id);

            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => (int) $request->role_id,
                'cooperative_id' => $request->cooperative_id,
                'credit_card_number' => $request->credit_card_number,
                'phone_number' => $request->phone_number,
                'address' => $request->address,
            ]);

            $user = User::where('email', $request->email)->first();
            // crete token
            $token = $user->createToken('authToken')->accessToken;

            return ResponseFormatter::success([
                'code' => 200,
                'user' => $user,
                'token-type' => 'Bearer',
                'token' => $token,
            ]);
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error('Unauthorized');
            }

            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password)) {
                return ResponseFormatter::error('Unauthorized');
            }

            return ResponseFormatter::success([
                'code' => 200,
                'user' => $user,
                'token-type' => 'Bearer',
                'token' => $user->createToken('authToken')->plainTextToken,
            ]);
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function fetch(Request $request)
    {
        $user = User::with([
            'role',
            'cooperative',
        ])->find($request->user()->id);
        try {
            return ResponseFormatter::success([
                'code' => 200,
                'user' => $user,
            ]);
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->tokens()->delete();
            return ResponseFormatter::success([
                'code' => 200,
                'message' => 'Logout Successfully',
            ]);
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'cooperative_id' => 'nullable|integer',
                'credit_card_number' => 'nullable|string',
                'phone_number' => 'nullable|string',
                'address' => 'required|string',
                'profile_photo_path' => 'nullable|string',
            ]);

            $user = User::find($request->user()->id);
            $user->name = $request->name;
            $user->email = $request->email;
            $user->cooperative_id = $request->cooperative_id;
            $user->credit_card_number = $request->credit_card_number;
            $user->phone_number = $request->phone_number;
            $user->address = $request->address;
            $user->profile_photo_path = $request->profile_photo_path;
            $user->save();

            return ResponseFormatter::success([
                'code' => 200,
                'user' => $user,
            ]);

        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage());
        }
    }

    public function updateVerificationCooperative(Request $request)
    {
        try {
            $user = $request->user();
            $request->validate([
                'is_verified' => 'required|boolean',
            ]);

            // update cooperative verification
            $cooperative = Cooperative::find($request->id);
            $cooperative->is_verified = $request->is_verified;
            $cooperative->save();

            // update role_id of user
            $user->role_id = 2;
            $user->save();

            return ResponseFormatter::success([
                'code' => 200,
                'user' => $user,
                'cooperative' => $cooperative,
            ], 'Cooperative Verification Updated & User Role Updated');
        } catch (Exception $th) {
            return ResponseFormatter::error($th->getMessage());
        }
    }
}
